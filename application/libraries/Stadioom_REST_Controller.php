<?php
require(APPPATH . '/libraries/REST_Controller.php');

/**
 * Common REST controller for Stadioom REST APIs.
 */
class Stadioom_REST_Controller extends REST_Controller {
    private $DEFAULT_SUCCEED_MSG = "OK";

    private function ex2Array($e) {
        return array('error_code' => $e->getCode(), 'error_msg' => $e->getMessage());
    }

    protected function responseOk($res = null) {
        if ($res != null) {
            $this->response($res, 200);
        }
        $this->responseOk($this->DEFAULT_SUCCEED_MSG);
    }

    protected function responseError($e) {
        $this->response($this->ex2Array($e), $e->getMessage());
    }
}

?>
