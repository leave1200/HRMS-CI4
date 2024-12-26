<?php
namespace App\Libraries;

class Hash
{
    public static function make($password){
        return password_hash($password,PASSWORD_ARGON2ID);
    }

    public static function check($password, $db_hashed_password){
        if(password_verify($password, $db_hashed_password) ){
            return true;
        }else{
            return false;
        }
    }
}