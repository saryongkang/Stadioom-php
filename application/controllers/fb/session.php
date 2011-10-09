<?php

class Session extends CI_Controller {
    
    function __construct() {
        parent::__construct();
    }

	function index()
	{
        echo "Unaccessible";
    }
    
    function login(){
        $this->load->helper('url');
        
        $this->load->library('fb_connect');
        $param['redirect_uri']=site_url("fb/session/login");
	    //redirect($this->fb_connect->getLoginUrl($param));
        // Get User ID
        
          if ($this->fb_connect->user_id){
            $retry=true;
            $meAddress = '/me';
            $data['fbId'] = $fb_uid = $this->fb_connect->user_id;
            
            while(true){
              try {
                // Proceed knowing you have a logged in user who's authenticated.
                $data['fbAccessToken'] = $this->fb_connect->getAccessToken();

                //echo "fbToken: ".$data['fbAccessToken'];
                
                
                $user_profile = $this->fb_connect->api($meAddress);

                $data['fbExpires'] = '0';

                if ($this->_checkFBUserDB($data)){

                    //Properly logged and check in, redirect to the main app
                    $this->session->set_userdata('loggedin', true);
                    redirect('/main', 'refresh');

                }else{
                    redirect('/home/loginDBErr', 'refresh');
                }

              } catch ( FacebookApiException $e) {
                
                if($retry==true){
                    //Probably the key is expired, set to null and renew
                    $this->fb_connect->setAccessToken(null);
                    $retry=false;
                    //$meAddress = '/'.$data['fbId'];
//                    echo " Renewing token <br>";
                //Give up and redirect home with error messange
                }else{
                    error_log($e);
                    $data['fbId'] = null;
//                    echo 'LoginFBError: ' .$e->getMessage();
//                    echo '<br /> We gave up';
                    
                    $this->load->library('session');
                    $this->session->sess_destroy();
                    redirect('/home', 'refresh');
                }
                
              }
            } //endWhile

        }else{
            //echo "There is no FB User logged in / Redirect to Login";
            redirect('/home', 'refresh');
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
    }//end Login function
    
    private function _checkFBUserDB($data){
                //getAccessToken()
        //print_r($data);
   
        //Logic to call update in database
        $this->load->model('dao/UserDao');
        if ($userData = $this->UserDao->fbConnect($data)){
        
            //echo('Cleaning up the field...');

            //Create session
            $this->load->library('session');

            //$id = db.getUserId();
            

            $userSession = array(
                'stdUid'  => $userData['id'],
                'fbAccessToken'  => $data['fbAccessToken'],
                'fbExpires'  => $data['fbExpires'],
                'fbUId'  => $data['fbId'],
                'fullName'  => $userData['fullName'],
                'logged_in' => TRUE,
                'fb_connected' => TRUE
            );

            $this->session->set_userdata($userSession);
            //print_r($userSession);
            //Load some php page just to redirect to the real system
        }else{
            return false;
        }
        return true;
    }
    
    function logout(){
        $this->load->helper('url');
        //echo "Bye bye ". $this->session->userdata('fullName');
        $this->load->library('session');
        $this->session->sess_destroy();
        redirect('/home', 'refresh');
    }
}
?>