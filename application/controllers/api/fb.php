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

        try {
            $accessToken = $this->UserDao->fbConnect($fbId, $fbAccessToken);
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
