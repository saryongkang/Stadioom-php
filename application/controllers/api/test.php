<?php
require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

/**
 * Contains stuffs for just testing.
 */
class Test extends Stadioom_REST_Controller{
    
    public function encode_get() {
        $msg = $this->get('email') . ":" . $this->get('password');

        $this->response(base64_encode($msg), 200);
//        $this->response(urlencode(base64_encode($msg)), 200);
    }
}

?>
