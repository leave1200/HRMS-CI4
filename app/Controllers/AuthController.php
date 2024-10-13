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
        
        $data =[
            'pageTitle'=>'Login',
            'validation'=>'null'
        ];
        return view('backend/pages/auth/login', $data);
    }

    // In your AuthController
public function loginHandler()
{
    // Start a session if not already started
    if (!session()->has('attempts')) {
        session()->set('attempts', 0); // Initialize attempts
    }

    // Define max attempts and lockout duration (in seconds)
    $maxAttempts = 3;
    $lockoutDuration = 30; // seconds

    // Check if the user is currently locked out
    if (session()->has('lockout_time')) {
        $lockoutTime = session()->get('lockout_time');
        if (time() < $lockoutTime) {
            $remainingTime = $lockoutTime - time();
            return redirect()->back()->with('fail', "Too many incorrect attempts. Please wait " . $remainingTime . " seconds before trying again.")
                                   ->with('remainingTime', $remainingTime);
        } else {
            // Reset lockout time if the lockout duration has expired
            session()->remove('lockout_time');
        }
    }

    // Validate login credentials
    $loginID = $this->request->getPost('login_id');
    $password = $this->request->getPost('password');

    // (Assuming you have a method to validate the user)
    if ($this->validateLogin($loginID, $password)) {
        // Successful login logic here
        session()->set('logged_in', true);
        session()->remove('attempts'); // Reset attempts on successful login
        return redirect()->to('dashboard'); // Redirect to dashboard
    } else {
        // Increment the attempts on failure
        session()->set('attempts', session()->get('attempts') + 1);

        // Check if attempts exceed the max allowed
        if (session()->get('attempts') >= $maxAttempts) {
            session()->set('lockout_time', time() + $lockoutDuration);
            return redirect()->back()->with('fail', "Too many incorrect attempts. Please wait " . $lockoutDuration . " seconds before trying again.")
                                   ->with('remainingTime', $lockoutDuration);
        }

        return redirect()->back()->with('fail', 'Invalid login credentials. Attempts left: ' . ($maxAttempts - session()->get('attempts')));
    }
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
            }
            $password_reset_token->insert([
                'email'=>$user_info->email,
                'token'=>$token,
                'created_at'=>Carbon::now()
            ]);
        }

        // create action link
        $actionLink = route_to('admin.reset-password', $token);

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
