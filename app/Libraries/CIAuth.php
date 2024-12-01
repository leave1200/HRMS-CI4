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
    }

    public static function user(){
        $session = session();
    
        if( $session->has('logged_in') ){
            if( $session->has('userdata') ){
                $user = new User();
                $currentUser = $user->asObject()->where('id', CIAuth::id())->first();
    
                // Check if the user's policy is "Offline"
                if ($currentUser && $currentUser->policy === 'Offline') {
                    // Log out the user and clear session for all users
                    self::logoutAllUsers();
                    return null;  // Return null because we just logged out the user
                }
    
                return $currentUser;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
    
    public static function logoutAllUsers() {
        // Assuming you have a session table or another way to track user sessions across devices
        // You would clear all session data for users who are currently logged in
    
        // For example, if you have a "sessions" table where user sessions are stored:
        // $sessionModel = new \App\Models\SessionModel();
        // $sessionModel->truncate(); // This will delete all session data for all users
    
        // Clear all session cookies for all users
        delete_cookie('csrf_cookie_name');  // Make sure this matches the cookie name you are using
        delete_cookie('ci_session');
    
        // Destroy the current session
        session()->sess_destroy();
    
        // Optionally, you can add additional logic to notify users that they have been logged out.
        // For example, you can send an email or show a message on the login screen.
    }
    
    
}
