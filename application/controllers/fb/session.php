<?php

class Session extends CI_Controller {

	function index()
	{
        echo "Unaccessible";
    }
    
    function login($fbId,$fbAccessToken,$expires){
        $data['fbAccessToken'] = $fbAccessToken;
        $data['fbId'] = $fbId;
        $data['fbExpires'] = $expires;
        //print_r($data);
   
        //Logic to call update in database
        $this->load->model('dao/UserDao');
        if ($userData = $this->UserDao->fbConnect($data)){
        
            echo('<br /> FB User Succesfuly Connectedd <br />');

            //Create session
            $this->load->library('session');

            //$id = db.getUserId();

            $userSession = array(
                'id'  => $userData['id'],
                'fbAccessToken'  => $fbId,
                'stadioomAccessToken'  => $userData['accessToken'],
                'fbid'  => $fbId,
                'fullName'  => $userData['fullName'],
                'logged_in' => TRUE,
                'fb_connected' => TRUE
            );

            $this->session->set_userdata($userSession);
            print_r($userSession);
            //Load some php page just to redirect to the real system
        }else{
            echo "Oops! There was an error logging you in!";
        }
    }
    
    function logout(){
        echo "Bye bye ". $this->session->userdata('fullName');
        $this->load->library('session');
        $this->session->sess_destroy();
    }
}
?>