<?php

namespace App\Validation;

class IsPasswordStrong
{
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
