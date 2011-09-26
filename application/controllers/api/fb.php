<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class Fb extends Stadioom_REST_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('dao/UserDao');
    }

    public function connect_post() {
        $fbId = $this->post('fbId');
        $fbAccessToken = $this->post('fbAccessToken');
        $fbExpires = $this->post('fbExpires');

        $fbInfo = array('fbId' => $this->post('fbId'), 'fbAccessToken' => $this->post('fbAccessToken'), 'fbExpires' => $this->post('fbExpires'));

        try {
            $result = $this->UserDao->fbConnect($fbInfo);
            $this->responseOk($result);
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function deauthorize_post() {
        $accessToken = $this->post('accessToken');

        try {
            $this->verifyToken($accessToken);

            $this->UserDao->fbDeauthorize($this->post('fbId'));
        } catch (Exception $e) {
            $this->responseError($e);
        }
        $this->responseOk();
    }

    private function verifyToken($accessToken) {
        if ($accessToken == NULL) {
            throw new Exception("Invalid access token.", 400);
        }
        $this->load->library('encrypt');
        $decodedToken = $this->encrypt->decode($accessToken);
        $magicCode = strtok($decodedToken, ":");
        $userId = strtok($decodedToken, ":");
        $expired = strtok($decodedToken, ":");

        if ($magicCode != "SeedShock" || $userId > 0) {
            throw new Exception("Invaild access token.", 400);
        }
        
        $curDate = new DateTime();
        $curDate = $curDate->getTimestamp();
        if ($expired != 0 && $expired < $curDate) {
            throw new Exception("Token expired.", 406);
        }
    }

}

?>
