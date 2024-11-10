<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\CIAuth;
use App\Libraries\Hash;
use App\Models\User;
use App\Models\PasswordResetToken;
use Carbon\Carbon;

class AuthController extends BaseController
{
    protected $helpers = ['url', 'form', 'CIMail', 'CIFunctions', 'session'];

    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User(); // Load the UserModel
    }
    public function loginForm()
    {
        // Assume we have a function to check system availability
        if (!$this->isSystemAccessible()) {
            session()->setFlashdata('system_accessible', false);
        }
    
        return view('backend/pages/auth/login', [
            'pageTitle' => 'Login',
            'validation' => null,
        ]);
    }
    private function isSystemAccessible()
    {
        // Load the database connection service
        $db = \Config\Database::connect();

        // Check if the connection is successful
        try {
            // Perform a simple query to check the connection
            $db->query("SELECT 1");
            return true; // Connection is successful
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            log_message('error', 'Database connection failed: ' . $e->getMessage());
            return false; // Connection failed
        }
    }
    public function loginHandler()
    {
        // Check if the user is currently in a waiting state
        if (session()->get('wait_time') && session()->get('wait_time') > time()) {
            // Calculate remaining wait time
            $remainingTime = session()->get('wait_time') - time();
            return view('backend/pages/auth/login', [
                'pageTitle' => 'Login',
                'validation' => null,
                'waiting' => true,
                'remainingTime' => ceil($remainingTime)
            ]);
        }
    
        // Determine the field type (email or username)
        $loginId = $this->request->getVar('login_id');
        $fieldType = filter_var($loginId, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    
        // Validation rules
        $rules = [
            'login_id' => [
                'rules' => 'required|' . ($fieldType === 'email' ? 'valid_email|is_not_unique[users.email]' : 'is_not_unique[users.username]'),
                'errors' => [
                    'required' => $fieldType === 'email' ? 'Email is required' : 'Username is required',
                    'valid_email' => 'Please, check the email field. It does not appear to be valid.',
                    'is_not_unique' => $fieldType === 'email' ? 'Email does not exist in our system.' : 'Username does not exist in our system.'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[5]|max_length[25]',
                'errors' => [
                    'required' => 'Password is required',
                    'min_length' => 'Password must have at least 5 characters in length.',
                    'max_length' => 'Password must not have more than 25 characters in length.'
                ]
            ]
        ];
    
        // Validate the input
        if (!$this->validate($rules)) {
            return view('backend/pages/auth/login', [
                'pageTitle' => 'Login',
                'validation' => $this->validator
            ]);
        }
    
        // If validation passes, proceed to check user credentials
        $userInfo = $this->userModel->where($fieldType, $loginId)->first();
    
        // Verify the password
        if (!$userInfo || !Hash::check($this->request->getVar('password'), $userInfo['password'])) {
            // Increment the attempt count
            $attempts = session()->get('login_attempts') ?: 0;
            $attempts++;
            session()->set('login_attempts', $attempts);
    
            // Wait for 5 seconds after 3 attempts
            if ($attempts >= 3) {
                session()->set('wait_time', time() + 30); // Wait for 30 seconds
                return redirect()->route('admin.login.form')->with('fail', 'Too many incorrect attempts. Please wait 30 seconds before trying again.')->withInput();
            }
    
            return redirect()->route('admin.login.form')->with('fail', 'Invalid credentials')->withInput();
        }
    
        // Reset the attempts if login is successful
        session()->remove('login_attempts');
        session()->remove('wait_time');
    
        // Set user session or cookie using CIAuth
        CIAuth::setCIAuth($userInfo);
    
        // Set session data for logged-in user
        session()->set([
            'user_id' => $userInfo['id'],
            'username' => $userInfo['username'],
            'userStatus' => $userInfo['status'],
            'isLoggedIn' => true
        ]);
    
        // Redirect to the admin dashboard or home page
        return redirect()->route('admin.home');
    }
    
    
    
    public function forgotForms() {
        $data = array(
            'pageTitle' => 'Forgot password',
            'validation' => null
        );
        return view('backend/pages/auth/forgot', $data);
    }

    public function sendPasswordResetLink() {
        $isValid = $this->validate([
            'email' => [
                'rules' => 'required|valid_email|is_not_unique[users.email]',
                'errors' => [
                    'required' => 'Email required',
                    'valid_email' => 'Please check email field. It does not appear to be valid.',
                    'is_not_unique' => 'Email does not exist in the system',
                ],
            ]
        ]);

        if (!$isValid) {
            return view('backend/pages/auth/forgot', [
                'pageTitle' => 'Forgot password',
                'validation' => $this->validator,
            ]);
        } else {
            // Retrieve user info
            $user = new User();
            $user_info = $user->asObject()->where('email', $this->request->getVar('email'))->first();

            // Generate reset token
            $token = bin2hex(openssl_random_pseudo_bytes(65));

            // Check if token already exists
            $password_reset_token = new PasswordResetToken();
            $isOldTokenExists = $password_reset_token->asObject()->where('email', $user_info->email)->first();

            if ($isOldTokenExists) {
                // Update existing token
                $password_reset_token->where('email', $user_info->email)
                                     ->set(['token' => $token, 'created_at' => Carbon::now()])
                                     ->update();
            } else {
                // Insert new token
                $password_reset_token->insert([
                    'email' => $user_info->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
            }

            // Create reset action link
            $actionLink = route_to('admin.reset-password', $token);

            // Prepare email content
            $mail_data = array(
                'actionLink' => $actionLink,
                'user' => $user_info,
            );

            // Render the email template
            $view = Services::renderer();
            $mail_body = $view->setVar('mail_data', $mail_data)->render('email-templates/forgot-email-template');

            // Send email
            $mailConfig = array(
                'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                'mail_from_name' => env('EMAIL_FROM_NAME'),
                'mail_recipient_email' => $user_info->email,
                'mail_recipient_name' => $user_info->name,
                'mail_subject' => 'Reset Password',
                'mail_body' => $mail_body
            );

            if (sendEmail($mailConfig)) {
                return redirect()->route('admin.forgot.form')->with('success', 'We have emailed your password reset link.');
            } else {
                return redirect()->route('admin.forgot.form')->with('fail', 'Something went wrong');
            }
        }
    }
    public function resetPassword($token) {
        // Step 1: Validate the token
        $passwordResetToken = new PasswordResetToken();
        $resetRequest = $passwordResetToken->asObject()->where('token', $token)->first();
    
        // Step 2: Check if the token is valid and exists
        if (!$resetRequest) {
            return redirect()->route('admin.forgot.form')->with('fail', 'Invalid or expired token.');
        }
    
        // Step 3: Check if the token has expired (for example, expiration time can be 1 hour)
        $tokenCreatedAt = $resetRequest->created_at;
        $expirationTime = Carbon::parse($tokenCreatedAt)->addHour(); // Token expires in 1 hour
        if (Carbon::now()->greaterThan($expirationTime)) {
            // Delete expired token from the table
            $passwordResetToken->where('token', $token)->delete();
            return redirect()->route('admin.forgot.form')->with('fail', 'Token has expired. Please request a new password reset link.');
        }
    
        // Step 4: Fetch user associated with the token
        $user = new User();
        $userInfo = $user->asObject()->where('email', $resetRequest->email)->first();
    
        // Step 5: Display the password reset form (view)
        $data = [
            'pageTitle' => 'Reset Password',
            'validation' => null,
            'token' => $token, // Pass the token to the form to include in the request
        ];
    
        return view('backend/pages/auth/reset-password', $data);
    }
    
    public function updatePassword() {
        // Step 6: Handle the form submission for updating the password
        $token = $this->request->getVar('token'); // Token sent from the form
        $password = $this->request->getVar('password'); // New password
        $confirmPassword = $this->request->getVar('confirm_password'); // Confirm password
    
        // Step 7: Validate the password and confirm password fields
        if ($password !== $confirmPassword) {
            return redirect()->back()->with('fail', 'Passwords do not match.')->withInput();
        }
    
        // Password validation rule
        if (strlen($password) < 8) {
            return redirect()->back()->with('fail', 'Password must be at least 8 characters.')->withInput();
        }
    
        // Step 8: Get user by token and update the password
        $passwordResetToken = new PasswordResetToken();
        $resetRequest = $passwordResetToken->asObject()->where('token', $token)->first();
    
        if (!$resetRequest) {
            return redirect()->route('admin.forgot.form')->with('fail', 'Invalid or expired token.');
        }
    
        // Hash the new password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
        // Update the user's password
        $user = new User();
        $user->where('email', $resetRequest->email)
             ->set(['password' => $hashedPassword])
             ->update();
    
        // Step 9: Delete the token after use
        $passwordResetToken->where('token', $token)->delete();
    
        // Step 10: Redirect the user to login page
        return redirect()->route('admin.login.form')->with('success', 'Your password has been successfully updated.');
    }
    

    /// user info
    public function getName($id)
    {
        $userModel = new User();
        $user = $userModel->find($id);

        if ($user) {
            $name = $user['name'];
            return $this->response->setJSON(['name' => $name]);
        } else {
            return $this->response->setJSON(['error' => 'User not found'], 404);
        }
    }
}
