<?php
namespace App\Controllers;

use App\Models\PasswordResetToken;
use CodeIgniter\Controller;
use CodeIgniter\Email\Email;

class PasswordController extends Controller
{
    public function requestReset()
    {  
        helper(['form', 'url']);
        
        // Validate the email input
        if ($this->request->getMethod() === 'post' && $this->validate(['email' => 'required|valid_email'])) {
            $email = $this->request->getPost('email');
            
            // Check if the email exists in your users table (example table: 'users')
            $userModel = new \App\Models\User();
            $user = $userModel->where('email', $email)->first();

            if ($user) {
                // Generate a token
                $token = bin2hex(random_bytes(50));
                $createdAt = date('Y-m-d H:i:s');
                
                // Save the token to the database
                $passwordResetModel = new PasswordResetToken();
                $passwordResetModel->save([
                    'email' => $email,
                    'token' => $token,
                    'created_at' => $createdAt
                ]);
                
                // Send the reset link via email
                $resetLink = site_url("password/reset/{$token}");
                $this->_sendResetEmail($email, $resetLink);
                
                return redirect()->to('/password/request')->with('message', 'A reset link has been sent to your email address.');
            } else {
                return redirect()->to('/password/request')->with('error', 'Email not found.');
            }
        }

        return view('password_reset_request');
    }

    private function _sendResetEmail($email, $resetLink)
    {
        $emailService = \Config\Services::email();
        
        $emailService->setTo($email);
        $emailService->setSubject('Password Reset Request');
        $emailService->setMessage("Click the following link to reset your password: $resetLink");
        
        if (!$emailService->send()) {
            log_message('error', 'Failed to send reset email to: ' . $email);
        }
    }
    // app/Controllers/PasswordController.php

public function reset($token = null)
{
    if (!$token) {
        return redirect()->to('/password/request')->with('error', 'Invalid reset token.');
    }

    $passwordResetModel = new PasswordResetToken();
    $resetToken = $passwordResetModel->where('token', $token)->first();

    if (!$resetToken) {
        return redirect()->to('/password/request')->with('error', 'Invalid or expired reset token.');
    }

    // Password reset form
    if ($this->request->getMethod() === 'post') {
        $newPassword = $this->request->getPost('password');
        
        if ($this->validate(['password' => 'required|min_length[8]'])) {
            // Update user's password in the database
            $userModel = new \App\Models\User();
            $userModel->where('email', $resetToken['email'])->set(['password' => password_hash($newPassword, PASSWORD_BCRYPT)])->update();
            
            // Delete the token from the password_reset_tokens table after use
            $passwordResetModel->where('token', $token)->delete();
            
            return redirect()->to('/login')->with('message', 'Your password has been reset successfully.');
        }
    }

    return view('password_reset', ['token' => $token]);
}

}
