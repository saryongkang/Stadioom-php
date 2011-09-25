<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

/**
 * Contains stuffs for just testing.
 */
class Test extends Stadioom_REST_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('dao/UserDao');
    }

    public function base64_encode_get() {
        $msg = $this->get('email') . ":" . $this->get('password');

        $this->response(base64_encode($msg), 200);
    }

    public function url_base64_encode_get() {
        $msg = $this->get('email') . ":" . $this->get('password');

        $this->response(urlencode(base64_encode($msg)), 200);
    }

    public function sendEmail_get() {
        $this->UserDao->sendVerificationEmail("wegra.lee@gmail.com", "codecodecode");
    }

    public function getConfig_get() {
        $this->response(array('base_url' => $this->config->item('base_url'), 'user_verification_enabled' => $this->config->item('user_verification_enabled')), 200);
    }

}

?>
