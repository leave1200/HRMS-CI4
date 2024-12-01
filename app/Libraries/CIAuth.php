<?php
namespace App\Libraries;
use App\Models\User;

class CIAuth
{
    public static function setCIAuth($result){
        $session = session();
        $array = ['logged_in'=>true];
        $userdata = $result;
        $session->set('userdata', $userdata);
        $session->set($array);
    }

    public static function id(){
        $session = session();
        if($session->has('logged_in') ){
            if( $session->has('userdata') ){
                return $session->get('userdata')['id'];
            }else{
                return null;
            } 
        }else{
            return null;
        }
    }
    public static function check(){
        $session = session();
        return $session->has('logged_in');
    }

    public static function forget(){
        $session = session();
        $session->remove('logged_in');
        $session->remove('userdata');
        foreach ($_COOKIE as $key => $value) {
            delete_cookie($key);
        }
    }

    // public static function user(){
    //     $session = session();
    //     if( $session->has('logged_in') ){
    //         if( $session->has('userdata') ){
    //            // return $session->get('userdata');

    //            $user = new User();
    //             return $user->asObject()->where('id',CIAuth::id())->first();

    //         }else{
    //             return null;
    //         }
    //     }else{
    //         return null;
    //     }
    // }
     public static function user(){
        $session = session();
        if( $session->has('logged_in') ){
            if( $session->has('userdata') ){
                // Fetch user data
                $user = new User();
                $userData = $user->asObject()->where('id', CIAuth::id())->first();

                // Check if the policy is "Offline"
                if ($userData && $userData->policy === 'Offline') {
                    CIAuth::forget();
                    return null;
                }

                return $userData;
            }else{
                return null;
            }
        }else{
            return null;
        }
    }
    
    
}
