<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class Fb extends Stadioom_REST_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('dao/UserDao');
        if (function_exists('force_ssl'))
            remove_ssl();
    }

    public function connect_post() {
        $fbId = $this->post('fbId');
        $fbAccessToken = $this->post('fbAccessToken');
        $fbExpires = $this->post('fbExpires');

        $fbInfo = array('fbId' => $fbId, 'fbAccessToken' => $fbAccessToken, 'fbExpires' => $fbExpires);

        try {
            $result = $this->UserDao->fbConnect($fbInfo);
            $this->responseOk($this->filter($result, array('id')));
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function test_get() {
//        $fbId = $this->post('fbId');
        $fbId = '649290919';
//        $fbAccessToken = $this->post('fbAccessToken');
        $fbAccessToken = 'AAAC2zBZAGSiwBACtBgX0BEsBZAdL37VHp9fHxwGgcglC5vpioPzZC1ElpwaEVx0cIN5ZB7I0PvxARYWUfmjRMrDFHGdSdecZD';

        $fbInfo = array('fbId' => $fbId, 'fbAccessToken' => $fbAccessToken);

        try {
            $result = $this->UserDao->fbtest($fbInfo);
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
            
            $data = array("data" => $result);
            $this->responseOk($data);
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

}

?>
