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
        // Get the reCAPTCHA token from the form
        $recaptchaResponse = $this->request->getVar('recaptcha_token');
    
        // Verify the reCAPTCHA token with Google's API
        $secretKey = '6LdcqXoqAAAAABIemrKHuNtlyIXuP5dPn--VQUhD'; // Replace with your secret key from Google reCAPTCHA
        $remoteIp = $this->request->getServer('REMOTE_ADDR');
        $verificationUrl = 'https://www.google.com/recaptcha/api/siteverify';
    
        // Send the POST request to Google's API for verification
        $response = \Config\Services::curlrequest()->post($verificationUrl, [
            'form_params' => [
                'secret' => $secretKey,
                'response' => $recaptchaResponse,
                'remoteip' => $remoteIp
            ]
        ]);
    
        // Decode the response
        $recaptchaData = json_decode($response->getBody(), true);
    
        // If reCAPTCHA verification fails
        if (!$recaptchaData['success']) {
            return redirect()->route('admin.login.form')->with('fail', 'reCAPTCHA verification failed. Please try again.')->withInput();
        }
    
        // Continue with the rest of the login logic if reCAPTCHA is successful
        $loginId = $this->request->getVar('login_id');
        $fieldType = filter_var($loginId, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    
        // Validation rules for login_id and password
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
    
        // Validate input
        if (!$this->validate($rules)) {
            return view('backend/pages/auth/login', [
                'pageTitle' => 'Login',
                'validation' => $this->validator
            ]);
        }
    
        // Check user credentials
        $userInfo = $this->userModel->where($fieldType, $loginId)->first();
    
        // Verify user password
        if (!$userInfo || !Hash::check($this->request->getVar('password'), $userInfo['password'])) {
            $attempts = session()->get('login_attempts') ?: 0;
            $attempts++;
            session()->set('login_attempts', $attempts);
    
            if ($attempts >= 3) {
                session()->set('wait_time', time() + 30); // Wait for 30 seconds
                return redirect()->route('admin.login.form')->with('fail', 'Too many incorrect attempts. Please wait 30 seconds before trying again.')->withInput();
            }
    
            return redirect()->route('admin.login.form')->with('fail', 'Invalid credentials')->withInput();
        }
    
        // Reset failed login attempts
        session()->remove('login_attempts');
        session()->remove('wait_time');
    
        // Set user session and authenticate
        CIAuth::setCIAuth($userInfo);
    
        session()->set([
            'user_id' => $userInfo['id'],
            'username' => $userInfo['username'],
            'userStatus' => $userInfo['status'],
            'isLoggedIn' => true
        ]);
            // Check if the user has accepted the terms and conditions
    if ($userInfo['terms'] != 1) {
        // If the user has not accepted the terms, redirect them to the terms acceptance page
        return redirect()->route('admin.terms')->with('fail', 'You must accept the terms and conditions to proceed.');
    }
        return redirect()->route('admin.home');
    }
    
    
    
    
    
    public function forgotForms(){
        $data = array(
            'pageTitle'=>'Forgot password',
            'validation'=>null
        );
        return view('backend/pages/auth/forgot', $data);
    }
    
    public function sendPasswordResetLink(){
        $isValid = $this->validate([
            'email'=>[
                'rules'=>'required|valid_email|is_not_unique[users.email]',
                'errors'=>[
                    'required'=>'Email required',
                    'valid_email'=>'Please check email field. It does not appears to be Valid.',
                    'is_not_unique'=>'Email not Exist in System',
                ],
            ]
        ]);

        if( !$isValid ){
            return view('backend/pages/auth/forgot',[
                'pageTitle'=>'Forgot password',
                'validation'=>$this->validator,
            ]);
        }else{
           
            $user = new User();
            $user_info = $user->asObject()->where('email',$this->request->getVar('email'))->first();

            //gerate token
            $token = bin2hex(openssl_random_pseudo_bytes(65));

           //get reset token
           $password_reset_token = new PasswordResetToken();
           $isOldTokenExists = $password_reset_token->asObject()->where('email',$user_info->email)->first();

            if($isOldTokenExists){
                // update existing token
                $password_reset_token->where('email', $user_info->email)
                                     ->set(['token'=>$token,'created_at'=>Carbon::now()])
                                     ->update();
            }else{
                $password_reset_token->insert([
                    'email'=>$user_info->email,
                    'token'=>$token,
                    'created_at'=>Carbon::now()
                ]);
            }
              // create action link
        // $actionLink = route_to('admin.reset-password', $token);
        $actionLink = base_url(route_to('admin.reset-password', $token));

        $mail_data = array(
            'actionLink'=> $actionLink,
            'user'=>$user_info,
        );

        $view = \Config\Services::renderer();
        $mail_body = $view->setVar('mail_data', $mail_data)->render('email-templates/forgot-email-template');

        $mailConfig = array(
            'mail_from_email'=>env('EMAIL_FROM_ADDRESS'),
            'mail_from_name'=>env('EMAIL_FROM_NAME'),
            'mail_recipient_email'=>$user_info->email,
            'mail_recipient_name'=>$user_info->name,
            'mail_subject'=>'Reset Password',
            'mail_body'=>$mail_body

        );

            // send email

            if(sendEmail($mailConfig) ){
                return redirect()->route('admin.forgot.form')->with('success','We have emailed your password reset link.');
            }else{
                return redirect()->route('admin.forgot.form')->with('fail','Something went wrong');
            }
        
        }
        
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
    public function resetPassword($token){
        $passwordResetPassword = new PasswordResetToken();
        $check_token = $passwordResetPassword->asObject()->where('token',$token)->first();

        if( !$check_token){
            return redirect()->route('admin.forgot.form')->with('fal','Invalid token. Request another reset password link.');
        }else{
            $diffMins = Carbon::createFromFormat("Y-m-d H:i:s", $check_token->created_at)->diffInMinutes(Carbon::now());

            if( $diffMins > 10){
                return redirect()->route('admin.forgot.form')->with('fal','Invalid token. Request another reset password link.');
            }else{
                return view('backend/pages/auth/reset', [
                    'pagesTitle' => 'Reset Password',
                    'validation' => null,
                    'token' => $token
                ]);
            }
        }
    }

    public function resetPasswordHandler($token){
        $isValid = $this->validate([
            'new_password'=>[
                'rules'=>'required|min_length[8]|max_length[15]|is_password_strong[new_password]',
                'errors'=>[
                    'required'=>'Enter New Password',
                    'min_length'=>'New Password must be 8 character',
                    'max_length'=>'New Password must be 15 characters',
                    'is_password_strong'=>'New Password Must contains atleast 1 upppercase, 1 lower case, 1 number and 1 special character',
                ]
            ],
                'confirm_new_password'=>[
                'rules'=>'required|matches[new_password]',
                'errors'=>[
                    'required'=>'Confirm New Password',
                    'matches'=>'Password not Matches',
                ]
            ]
        ]);

        if( !$isValid){
            return view('backend/pages/auth/reset',[
                'pageTitle'=>'Reset Password',
                'validation'=>null,
                'token'=>$token,
            ]);
        }else{
            //get token
            $passwordResetPassword = new PasswordResetToken();
            $get_token = $passwordResetPassword->asObject()->where('token', $token)->first();

            //get user data
            $user = new User();
            $user_info = $user->asObject()->where('email', $get_token->email)->first();
            if(!$get_token ){
                return redirect()->back()->with('fail','Invalid Token!')->withInput();
            }else{
                $user->where('email',$user_info->email)
                ->set(['password'=>Hash::make($this->request->getVar('new_password'))])
                ->update();
                ///send email
                $mail_data = array(
                    'user'=>$user_info,
                    'new_password'=>$this->request->getVar('new_password')
                );

                $view = \Config\Services::renderer();
                $mail_body = $view->setVar('mail_data', $mail_data)->render('email-templates/password-changed-email-template');

                $mailConfig = array(
                    'mail_from_email'=>env('EMAIL_FROM_ADDRESS'),
                    'mail_from_name'=>env('EMAIL_FROM_NAME'),
                    'mail_recipient_email'=>$user_info->email,
                    'mail_recipient_name'=>$user_info->name,
                    'mail_subject'=>'Reset Password',
                    'mail_body'=>$mail_body
        
                );
                if( sendEmail($mailConfig) ){
                    //delete token
                    $passwordResetPassword->where('email',$user_info->email)->delete();

                    return redirect()->route('admin.login.form')->with('success','Done!, Your Password has been changed. Use new password to login into the system');
                }else{
                    return redirect()->back->with('fai','Somethig went wrong')->withInput();
                }
            }
        }
    }
    ///////////////////////////////////////////////pin
    // Forgot Password Pin sender
    public function forgotPasswordPinPage()
    {
        return view('backend/pages/auth/forgot-password-pin', [
            'pageTitle' => 'Forgot Password with Pin'
        ]);
    }

    public function sendPinCode()
        {
            $isValid = $this->validate([
                'email' => [
                    'rules' => 'required|valid_email|is_not_unique[users.email]',
                    'errors' => [
                        'required' => 'Email required',
                        'valid_email' => 'Please check email field. It does not appear to be valid.',
                        'is_not_unique' => 'Email not exists in our system.',
                    ]
                ]
            ]);

            if (!$isValid) {
                return view('backend/pages/auth/forgot-with-pin', [
                    'pageTitle' => 'Forgot Password with Pin',
                    'validation' => $this->validator
                ]);
            }

            $user = new User();
            $user_info = $user->asObject()->where('email', $this->request->getVar('email'))->first();

            // Generate pin code
            $pinCode = rand(100000, 999999);

            // Save the pin code and its expiration
            $password_reset_token = new PasswordResetToken();
            $password_reset_token->insert([
                'email' => $user_info->email,
                'token' => $pinCode,
                'created_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMinutes(15) // Pin expires in 15 minutes
            ]);

            // Prepare email data
            $resetLink = base_url() . "backend/pages/auth/reset-password-with-pin";
            $mail_data = [
                'name' => $user_info->name,
                'pin_code' => $pinCode,
                'reset_link' => $resetLink,
            ];

            // Render email template
            $view = \Config\Services::renderer();
            $mail_body = $view->setVar('mail_data', $mail_data)->render('email-templates/password-reset-pin-email-template');

            // Send email
            $mailConfig = [
                'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                'mail_from_name' => env('EMAIL_FROM_NAME'),
                'mail_recipient_email' => $user_info->email,
                'mail_recipient_name' => $user_info->name,
                'mail_subject' => 'Password Reset Pin Code',
                'mail_body' => $mail_body
            ];

            if (sendEmail($mailConfig)) {
                return redirect()->route('forgot-password-pin')->with('success', 'A pin code has been sent to your email.');
            } else {
                return redirect()->route('forgot-password-pin')->with('fail', 'Failed to send the pin code.');
            }
        }

    // Pin verification page (reset password with pin)
    public function resetPasswordWithPin($pin)
    {
        $passwordResetPassword = new PasswordResetToken();
        $check_token = $passwordResetPassword->where('token', $pin)->first();

        if (!$check_token || Carbon::now()->isAfter($check_token->expires_at)) {
            return redirect()->route('forgot-password-pin')->with('fail', 'Invalid or expired pin. Please request a new one.');
        }

        return view('backend/pages/auth/reset-password-with-pin', [
            'pageTitle' => 'Reset Password with Pin',
            'pin' => $pin
        ]);
    }
    public function resetPasswordHandlerWithPin()
    {
        $isValid = $this->validate([
            'new_password' => [
                'rules' => 'required|min_length[8]|max_length[15]|is_password_strong[new_password]',
                'errors' => [
                    'required' => 'Enter New Password',
                    'min_length' => 'New Password must be 8 characters',
                    'max_length' => 'New Password must be 15 characters',
                    'is_password_strong' => 'New Password is weak. Please include a mix of letters and numbers.'
                ]
            ],
            'confirm_new_password' => 'required|matches[new_password]',
        ]);

        if (!$isValid) {
            return view('backend/pages/auth/reset-password-with-pin', [
                'validation' => $this->validator,
                'pin' => $this->request->getVar('pin')
            ]);
        }

        $passwordResetToken = new PasswordResetToken();
        $pin = $this->request->getVar('pin');
        $resetToken = $passwordResetToken->where('token', $pin)->first();

        if (!$resetToken) {
            return redirect()->route('admin.forgot-password-pin')->with('fail', 'Invalid or expired pin.');
        }

        // Update the user password
        $user = new User();
        $user->where('email', $resetToken->email)->set([
            'password' => password_hash($this->request->getVar('new_password'), PASSWORD_DEFAULT)
        ])->update();

        // Delete the used reset token
        $passwordResetToken->where('token', $pin)->delete();

        return redirect()->route('admin.login')->with('success', 'Password reset successful.');
    }


}
