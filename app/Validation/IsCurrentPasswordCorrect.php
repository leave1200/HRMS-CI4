<?php

namespace App\Validation;
use App\Libraries\CIAuth;
use App\Libraries\Hash;
use App\Models\User;

class IsCurrentPasswordCorrect
{
    public function check_current_password($password): bool
    {
        $password = trim($password);
        $user_id = CIAuth::id();
        $user = new User();
        $user_info = $user->asObject()->where('id',$user_id)->first();


        if( !Hash::check($password, $user_info->password) ){
            return false;
        }
        return true;
    }
    public function is_password_strong(string $str, string &$error = null): bool
    {
        // Password must contain at least 1 uppercase, 1 lowercase, 1 number, and 1 special character
        if (!preg_match('/[A-Z]/', $str) ||
            !preg_match('/[a-z]/', $str) ||
            !preg_match('/[0-9]/', $str) ||
            !preg_match('/[\W]/', $str)) {
            $error = 'Password must contain at least 1 uppercase, 1 lowercase, 1 number, and 1 special character.';
            return false;
        }

        return true;
    }
    
}
