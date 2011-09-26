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

        // TODO: validate access token.

        try {
            $this->UserDao->fbDeauthorized($this->post('fbId'));
        } catch (Exception $e) {
            $this->responseError($e);
        }
        $this->responseOk();
    }

}

?>
