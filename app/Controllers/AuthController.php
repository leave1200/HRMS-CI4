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
        // Check system accessibility
        if (!$this->isSystemAccessible()) {
            session()->setFlashdata('system_accessible', false);
        }
    
        // Check if the reCAPTCHA token exists in the session
        $recaptchaToken = session()->get('recaptcha_token');
    
        // If the token is not set or verification fails, redirect to reCAPTCHA form
        if (!$recaptchaToken || !$this->verifyReCaptcha($recaptchaToken)) {
            return redirect()->to('backend/pages/auth/recaptcha-form'); // Redirect to the reCAPTCHA form
        }
    
        return view('backend/pages/auth/login', [
            'pageTitle' => 'Login',
            'validation' => null,
        ]);
    }
    
    /**
     * Verify reCAPTCHA token.
     *
     * @param string $token
     * @return bool
     */
    private function verifyReCaptcha($token)
    {
        $secretKey = '6LfaHGsqAAAAAM7xGs-NS4gSJPaPqAZXeRZvjGnh'; // Replace with your actual secret key
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$token}");
        $responseKeys = json_decode($response, true);
    
        return intval($responseKeys["success"]) === 1; // Return true if verification was successful
    }
    

    private function isSystemAccessible()
    {
        // Check if the database connection is successful
        $db = \Config\Database::connect();
        try {
            $db->query("SELECT 1");
            return true; // Connection is successful
        } catch (\Exception $e) {
            log_message('error', 'Database connection failed: ' . $e->getMessage());
            return false; // Connection failed
        }
    }

    public function loginHandler()
    {
        // Get the CAPTCHA token from the form
        $recaptchaResponse = $this->request->getPost('recaptcha_token');
        $secretKey = '6LfaHGsqAAAAAM7xGs-NS4gSJPaPqAZXeRZvjGnh'; // Replace with your secret key
    
        // Verify the CAPTCHA
        $verifyCaptcha = $this->verifyRecaptcha($recaptchaResponse, $secretKey);

        // Check if the CAPTCHA verification is successful
        if (!$verifyCaptcha || !$verifyCaptcha->success || $verifyCaptcha->score < 0.5) {
            return redirect()->route('admin.login.form')->with('fail', 'Captcha verification failed. Please try again.');
        }

        // Check if the user is in a waiting state (e.g., after too many login attempts)
        if (session()->get('wait_time') && session()->get('wait_time') > time()) {
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
    
        // If validation passes, check user credentials
        $userInfo = $this->userModel->where($fieldType, $loginId)->first();
    
        // Verify the password
        if (!$userInfo || !Hash::check($this->request->getVar('password'), $userInfo['password'])) {
            $attempts = session()->get('login_attempts') ?: 0;
            $attempts++;
            session()->set('login_attempts', $attempts);
    
            // Wait for 30 seconds after 3 incorrect attempts
            if ($attempts >= 3) {
                session()->set('wait_time', time() + 30);
                return redirect()->route('admin.login.form')->with('fail', 'Too many incorrect attempts. Please wait 30 seconds before trying again.')->withInput();
            }
    
            return redirect()->route('admin.login.form')->with('fail', 'Invalid credentials')->withInput();
        }
    
        // Reset login attempts if successful
        session()->remove('login_attempts');
        session()->remove('wait_time');
    
        // Set user session or cookie using CIAuth
        CIAuth::setCIAuth($userInfo);
    
        // Set session data for the logged-in user
        session()->set([
            'user_id' => $userInfo['id'],
            'username' => $userInfo['username'],
            'userStatus' => $userInfo['status'],
            'isLoggedIn' => true
        ]);
    
        // Redirect to the admin dashboard
        return redirect()->route('admin.home');
    }

    public function forgotForms()
    {
        return view('backend/pages/auth/forgot', [
            'pageTitle' => 'Forgot password',
            'validation' => null
        ]);
    }

    public function sendPasswordResetLink()
    {
        $isValid = $this->validate([
            'email' => [
                'rules' => 'required|valid_email|is_not_unique[users.email]',
                'errors' => [
                    'required' => 'Email is required',
                    'valid_email' => 'Please check the email field. It does not appear to be valid.',
                    'is_not_unique' => 'Email does not exist in our system.',
                ]
            ]
        ]);

        if (!$isValid) {
            return view('backend/pages/auth/forgot', [
                'pageTitle' => 'Forgot password',
                'validation' => $this->validator,
            ]);
        }

        // Proceed to generate and store a password reset token
        $user = $this->userModel->asObject()->where('email', $this->request->getVar('email'))->first();
        $token = bin2hex(openssl_random_pseudo_bytes(65));

        // Check for existing token
        $passwordResetToken = new PasswordResetToken();
        $isOldTokenExists = $passwordResetToken->where('email', $user->email)->first();

        if ($isOldTokenExists) {
            // Update the existing token
            $passwordResetToken->where('email', $user->email)
                               ->set(['token' => $token, 'created_at' => Carbon::now()])
                               ->update();
        } else {
            // Insert new token
            $passwordResetToken->insert([
                'email' => $user->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
        }

        // Create the action link for resetting the password
        $actionLink = route_to('admin.reset-password', $token);
        $mail_data = ['actionLink' => $actionLink, 'user' => $user];
        $view = \Config\Services::renderer();
        $mail_body = $view->setVar('mail_data', $mail_data)->render('email-templates/forgot-email-template');

        // Send the email
        $mailConfig = [
            'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
            'mail_from_name' => env('EMAIL_FROM_NAME'),
            'mail_recipient_email' => $user->email,
            'mail_recipient_name' => $user->name,
            'mail_subject' => 'Reset Password',
            'mail_body' => $mail_body
        ];

        if (sendEmail($mailConfig)) {
            return redirect()->route('admin.forgot.form')->with('success', 'We have emailed your password reset link.');
        } else {
            return redirect()->route('admin.forgot.form')->with('fail', 'Something went wrong.');
        }
    }

}
