<?php

class Session extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->library('facebook');
    }

	function index()
	{
        echo "Unaccessible";
    }
    
    function login(){
        $this->load->helper('url');
        
        $facebook = new $this->facebook(array(
          'appId' => '200987663288876',
          'secret' => '6d3dd0e7aa9dae300920ec05552bddee',
        ));
        
        // Get User ID
        $data['fbId'] = $facebook->getUser();

        if ($data['fbId']) {

          try {
            // Proceed knowing you have a logged in user who's authenticated.
            $user_profile = $facebook->api('/me');
            $data['fbAccessToken'] = $facebook->getAccessToken();
            $data['fbExpires'] = 0;
            
            if ($this->_checkFBUserDB($data)){
                echo "Welcome";
            }else{
                redirect('home/loginDBErr', 'refresh');
            }
            
          } catch (FacebookApiException $e) {
            error_log($e);
            $data['fbUserID'] = null;
            redirect('home/loginErr', 'refresh');
          }
          
        }else{
            print_r($data);
            //redirect('home/loginFbErr', 'refresh');
        }

        //getAccessToken()
//        $data['fbAccessToken'] = $fbAccessToken;
//        $data['fbId'] = $fbId;
//        $data['fbExpires'] = $expires;
//        //print_r($data);
//   
//        //Logic to call update in database
//        $this->load->model('dao/UserDao');
//        if ($userData = $this->UserDao->fbConnect($data)){
//        
//            echo('<br /> FB User Succesfuly Connectedd <br />');
//
//            //Create session
//            $this->load->library('session');
//
//            //$id = db.getUserId();
//
//            $userSession = array(
//                'id'  => $userData['id'],
//                'fbAccessToken'  => $fbId,
//                'stadioomAccessToken'  => $userData['accessToken'],
//                'fbid'  => $fbId,
//                'fullName'  => $userData['fullName'],
//                'logged_in' => TRUE,
//                'fb_connected' => TRUE
//            );
//
//            $this->session->set_userdata($userSession);
//            print_r($userSession);
//            //Load some php page just to redirect to the real system
//        }else{
//            echo "Oops! There was an error logging you in!";
//        }
    }
    
    private function _checkFBUserDB($data){
                //getAccessToken()
        //print_r($data);
   
        //Logic to call update in database
        $this->load->model('dao/UserDao');
        if ($userData = $this->UserDao->fbConnect($data)){
        
            echo('<br /> FB User Succesfuly Connectedd <br />');

            //Create session
            $this->load->library('session');

            //$id = db.getUserId();
            

            $userSession = array(
                'stdUid'  => $userData['id'],
                'fbAccessToken'  => $data['accessToken'],
                'fbExpires'  => $data['fbExpires'],
                'fbUId'  => $data['fbId'],
                'fullName'  => $userData['fullName'],
                'logged_in' => TRUE,
                'fb_connected' => TRUE
            );

            $this->session->set_userdata($userSession);
            print_r($userSession);
            //Load some php page just to redirect to the real system
        }else{
            return false;
        }
        return true;
    }
    
    function logout(){
        echo "Bye bye ". $this->session->userdata('fullName');
        $this->load->library('session');
        $this->session->sess_destroy();
    }
}
?>