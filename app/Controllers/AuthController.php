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
    
    
    
    public function forgotForms()
    {
        return view('backend/pages/auth/forgot', [
            'pageTitle' => 'Forgot Password',
            'validation' => null
        ]);
    }

    public function sendPasswordResetLink()
    {
        // Validate the email input
        $isValid = $this->validate([
            'email' => [
                'rules' => 'required|valid_email|is_not_unique[users.email]',
                'errors' => [
                    'required' => 'Email is required.',
                    'valid_email' => 'Please check the email field. It does not appear to be valid.',
                    'is_not_unique' => 'This email does not exist in our system.',
                ],
            ]
        ]);

        // If validation fails, re-render the form with error messages
        if (!$isValid) {
            return view('backend/pages/auth/forgot', [
                'pageTitle' => 'Forgot Password',
                'validation' => $this->validator,
            ]);
        } else {
            // Retrieve the user by email
            $user = $this->userModel->asObject()->where('email', $this->request->getVar('email'))->first();

            if (!$user) {
                // In case the email is not found in the database
                return redirect()->route('admin.forgot.form')->with('fail', 'Email not found in the system.');
            }

            // Generate a unique token for password reset
            $token = bin2hex(openssl_random_pseudo_bytes(65));

            // Check if there is an existing token for this email, and update it if exists
            $passwordResetTokenModel = new PasswordResetToken();
            $existingToken = $passwordResetTokenModel->asObject()->where('email', $user->email)->first();

            if ($existingToken) {
                // Update the existing token
                $passwordResetTokenModel->where('email', $user->email)
                    ->set(['token' => $token, 'created_at' => Carbon::now()])
                    ->update();
            } else {
                // Insert a new token if it doesn't exist
                $passwordResetTokenModel->insert([
                    'email' => $user->email,
                    'token' => $token,
                    'created_at' => Carbon::now(),
                ]);
            }

            // Generate the reset link
            $actionLink = route_to('admin.reset-password', $token);

            // Prepare email data
            $mailData = [
                'actionLink' => $actionLink,
                'user' => $user,
            ];

            // Prepare the email body using a view
            $view = \Config\Services::renderer();
            $mailBody = $view->setVar('mail_data', $mailData)->render('email-templates/forgot-email-template');

            // Email configuration
            $mailConfig = [
                'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                'mail_from_name' => env('EMAIL_FROM_NAME'),
                'mail_recipient_email' => $user->email,
                'mail_recipient_name' => $user->name,
                'mail_subject' => 'Password Reset Request',
                'mail_body' => $mailBody
            ];

            // Send the email
            if (sendEmail($mailConfig)) {
                // Redirect to the forgot password form with a success message
                return redirect()->route('admin.forgot.form')->with('success', 'We have emailed you the password reset link.');
            } else {
                // Handle error if email fails to send
                return redirect()->route('admin.forgot.form')->with('fail', 'Something went wrong while sending the email.');
            }
        }
    }
}
