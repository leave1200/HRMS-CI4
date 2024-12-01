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
               // return $session->get('userdata');

               $user = new User();
                return $user->asObject()->where('id',CIAuth::id())->first();

            }else{
                return null;
            }
        }else{
            return null;
        }
    }
    public static function logoutAllDevices($userId){
        // Set the user's policy to "Offline"
        $userModel = new User();
        $userModel->update($userId, ['policy' => 'Offline']);
        
        // Invalidate all sessions for this user
        $session = session();
        // Destroy the current session for this user
        $session->remove('logged_in');
        $session->remove('userdata');
        
        // Optionally, if you're storing sessions in the database:
        // $sessionModel = new SessionModel(); // Replace with your session model
        // $sessionModel->deleteWhere('user_id', $userId);  // Clear all sessions for this user
    }
    
}
