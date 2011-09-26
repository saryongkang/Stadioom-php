<?php

class Session extends CI_Controller {

	function index()
	{
        echo "Unaccessible";
    }
    
    function login($fbAccessToken, $fbId){
        $data['accessToken'] = $accessToken;
        $data['fbid'] = $fbuid;
        
        print_r($data);
        
        //Logic to call update in database
        $this->load->model('dao/UserDao');
        $this->UserDao->fbConnect($fbId, $fbAccessToken);
        
        echo('\n FB User Succesfuly Connectedd \n');
        
        //Load some php page just to redirect to the real system
    }
}
?>