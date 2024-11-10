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
    
    
    
    public function forgotForms(){
        $data = array(
            'pageTitle'=>'Forgot password',
            'validation'=>null
        );
        return view('backend/pages/auth/forgot', $data);
    }
    
    public function sendPasswordResetLink() {
        $validation = $this->validate([
            'email' => [
                'rules' => 'required|valid_email|is_not_unique[users.email]',
                'errors' => [
                    'required' => 'Email is required.',
                    'valid_email' => 'Please check email field. It does not appear to be valid.',
                    'is_not_unique' => 'Email does not exist in our system.'
                ]
            ]
        ]);
    
        if (!$validation) {
            return view('backend/pages/auth/forgot', [
                'pageTitle' => 'Forgot Password',
                'validation' => $this->validator
            ]);
        }
    
        // Get the user details from the email
        $userModel = new User();
        $user = $userModel->where('email', $this->request->getVar('email'))->first();
    
        // Generate reset token
        $token = bin2hex(openssl_random_pseudo_bytes(32));  // Generate a 32-byte token
    
        // Store token in the database (PasswordResetToken model)
        $passwordResetTokenModel = new PasswordResetToken();
        $passwordResetTokenModel->save([
            'email' => $user['email'],
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    
        // Generate the reset link
        $resetLink = base_url('auth/resetPassword/' . $token);
    
        // Prepare email content
        $emailContent = view('email-templates/forgot-password-email', [
            'user' => $user,
            'resetLink' => $resetLink
        ]);
    
        // Send the email using Gmail SMTP
        $email = \Config\Services::email();
        $email->setFrom('your-email@gmail.com', 'Your App Name');
        $email->setTo($user['email']);
        $email->setSubject('Password Reset Request');
        $email->setMessage($emailContent);
    
        // Send email and handle success/failure
        if ($email->send()) {
            return redirect()->to('/login')->with('success', 'We have emailed your password reset link!');
        } else {
            return redirect()->to('/forgot-password')->with('fail', 'There was an error sending the reset link.');
        }
    }

}
