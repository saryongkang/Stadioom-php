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
            $id = $this->verifyToken($accessToken);

            $this->UserDao->fbDeauthorizeWithId($id);
        } catch (Exception $e) {
            $this->responseError($e);
        }
        $this->responseOk();
    }

    public function invite_post() {
        $accessToken = $this->post('accessToken');

        try {
            $invitorId = $this->verifyToken($accessToken);

            $result = $this->UserDao->fbInvite($invitorId, $this->post('inviteeFbIds'));
            $this->responseOk($result);
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

}

?>
