<?php

class MY_Controller extends CI_Controller
{
    /*TODO change to adapt for non-fb users */
    function __construct()
    {
        parent::__construct();
    }
}

class Auth_Controller extends MY_Controller{
    function __construct()
    {
        parent::__construct();
        
        $this->load->library('session');
        if (!$this->session->userdata('loggedin'))
        {
            header('Location: /fb/session/login');
        }
    }
}

class FBAuth_Controller extends MY_Controller{
    function __construct()
    {
        parent::__construct();
        
        force_ssl();
        
        $this->load->library('session');
        if (!$this->session->userdata('loggedin')){
            $this->_login();
        }
        
        
    }
    
    private function _login(){
        $this->load->helper('url');
        
        $this->load->library('fb_connect');
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
                    break;

                }else{
                    redirect('/home', 'refresh');
                }

              } catch ( FacebookApiException $e) {
                
                if($retry==true){
                    //Probably the key is expired, set to null and renew
                    $this->fb_connect->setAccessToken(null);
                    $retry=false;
                    
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

    }//end Login function
    
    private function _checkFBUserDB($data){
   
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
        echo "Bye bye ". $this->session->userdata('fullName');
        $this->load->library('session');
        $this->session->sess_destroy();
    }
}