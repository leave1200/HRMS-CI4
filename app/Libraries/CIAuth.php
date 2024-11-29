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
        // Clear session data
        $session->remove('logged_in');
        $session->remove('userdata');
        
        // Clear cookies
        setcookie('ci_session', '', time() - 3600, '/', '', true, true);
        setcookie('csrf_cookie_name', '', time() - 3600, '/', '', true, true);
        
        // Destroy session
        $session->destroy();
    
        // Optional: Redirect user to the login page
        return redirect()->to('/login');
    }
    

    public static function user(){
        $session = session();
        if( $session->has('logged_in') ){
            if( $session->has('userdata') ){
               // return the user data from the session or database
               $user = new User();
                return $user->asObject()->where('id',CIAuth::id())->first();
            }else{
                return null;
            }
        }else{
            return null;
        }
    }
    
}
