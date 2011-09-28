<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class Test_REST_Controller extends Stadioom_REST_Controller {

    protected function _post($uri, $param) {
        $this->curl->create($this->config->item('base_url') . $uri);
        $this->curl->post($param);
        return $this->curl->execute();
    }

    protected function _get($uri, $param = NULL) {
        return $this->curl->simple_get($this->config->item('base_url') . $uri, $param);
    }

    protected function _getGrantCode($email, $password) {
        return base64_encode($email . ":" . $password);
    }

    protected function assertArray($result, $key, $value) {
        $json = json_decode($result);
        if ($json->$key != $value) {
            $this->response("[key:" . $key . "] expected:" . $value . ", actual:" . $json->$key);
        }
    }

    protected function assertEquals($result, $expected) {
        $actual = json_decode($result);
        if ($actual != $expected) {
            $this->response("[key] expected:" . $expected . ", actual:" . $actual);
        }
    }

    protected function assertArray_NotNull($result, $key) {
        $json = json_decode($result);
        if ($json->$key == NULL) {
            $this->response("[key:" . $key . "] expected: Not NULL");
        }
    }

    protected function createTestUser() {
        $response = $this->curl->simple_get("https://graph.facebook.com/200987663288876/accounts/test-users?installed=true&permissions=publish_stream,email,offline_access&method=post&access_token=200987663288876%7C6d3dd0e7aa9dae300920ec05552bddee");
        $json_decoded = json_decode($response);

        $testUser = array('fbId' => $json_decoded->id,
            'email' => $json_decoded->email,
            'password' => $json_decoded->password,
            'fbAccessToken' => $json_decoded->access_token,
            'name' => $this->getFbUserName($json_decoded->access_token));

        return $testUser;
    }


    private function getFbUserName($fbAccessToken) {
        $this->load->library('fb_connect');
        $this->fb_connect->setAccessToken($fbAccessToken);

        try {
            $fbMe = $this->fb_connect->api('/me', 'GET');
            return $fbMe['first_name'] . ' ' . $fbMe['last_name'];
        } catch (FacebookApiException $e) {
            throw new Exception("Failed to get authorized by Facebook.", 401, $e);
        }
    }
//    
//    private function base64_encode($) {
//        $msg = $this->get('email') . ":" . $this->get('password');
//
//        $this->response(base64_encode($msg), 200);
//    }
//
//    private function url_base64_encode_get() {
//        $msg = $this->get('email') . ":" . $this->get('password');
//
//        $this->response(urlencode(base64_encode($msg)), 200);
//    }
//
//    private function sendEmail_get() {
//        $this->UserDao->sendVerificationEmail("wegra.lee@gmail.com", "codecodecode");
//    }
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
