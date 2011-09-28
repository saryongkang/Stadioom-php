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

    private function _post($uri, $param) {
        $this->curl->create($uri);
        $this->curl->post($param);
        return $this->curl->execute();
    }

    private function _get($uri, $param) {
        return $this->curl->simple_get($uri, $param);
    }

    private function _getGrantCode($email, $password) {
        return $this->_get("http://stadioom:8080/api/test/base64_encode", array('email' => $email,
                    'password' => $password));
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
    
    public function auth_get() {
        // sign up with not-implemented authorization code. -> failed
        $grandCode = $this->_getGrantCode('wegra.lee', 'password');
        $result = $this->_post("http://stadioom:8080/api/auth/signUp", array('grantType' => 'non-authorization_code',
            'code' => $grandCode,
            'name' => 'Wegra',
            'gender' => 'male',
            'dob' => 1232123222));
        $this->assertArray($result, 'error_code', 501);
        
        // sign up with 'invalid' email -> failed
        $grandCode = $this->_getGrantCode('wegra.lee', 'password');
        $result = $this->_post("http://stadioom:8080/api/auth/signUp", array('grantType' => 'authorization_code',
            'code' => $grandCode,
            'name' => 'Wegra',
            'gender' => 'male',
            'dob' => 1232123222));
        $this->assertArray($result, 'error_code', 400);

        // sign up with email_1
        $grandCode = $this->_getGrantCode('wegra.lee@gmail.com', 'password');
        $result = $this->_post("http://stadioom:8080/api/auth/signUp", array('grantType' => 'authorization_code',
            'code' => $grandCode,
            'name' => 'Wegra',
            'gender' => 'male',
            'dob' => 1232123222));
        $this->assertEquals($result, 'OK');

        // sign up with email_2
        $grandCode = $this->_getGrantCode('wegra@seedshock.com', 'password');
        $result = $this->_post("http://stadioom:8080/api/auth/signUp", array('grantType' => 'authorization_code',
            'code' => $grandCode,
            'name' => 'Wegra',
            'gender' => 'male',
            'dob' => 1232123222));
        $this->assertEquals($result, 'OK');
        
        // sign up with email_2 (already registered) -> failed
        $grandCode = $this->_getGrantCode('wegra@seedshock.com', 'password');
        $result = $this->_post("http://stadioom:8080/api/auth/signUp", array('grantType' => 'authorization_code',
            'code' => $grandCode,
            'name' => 'Wegra',
            'gender' => 'male',
            'dob' => 1232123222));
        $this->assertArray($result, 'error_code', 406);

        // sign in with email_2
        $grandCode = $this->_getGrantCode('wegra@seedshock.com', 'password');
        $result = $this->_post("http://stadioom:8080/api/auth/signIn", array('grantType' => 'authorization_code',
            'code' => $grandCode));
        $this->assertArray_NotNull($result, 'accessToken');

        // sign in with email_2 w/ 'invalid' password. -> failed
        $grandCode = $this->_getGrantCode('wegra@seedshock.com', 'invalid_password');
        $result = $this->_post("http://stadioom:8080/api/auth/signIn", array('grantType' => 'authorization_code',
            'code' => $grandCode));
        $this->assertArray($result, 'error_code', 403);

        // sign in with 'unregistered' email. -> failed
        $grandCode = $this->_getGrantCode('unregistered@seedshock.com', 'password');
        $result = $this->_post("http://stadioom:8080/api/auth/signIn", array('grantType' => 'authorization_code',
            'code' => $grandCode));
        $this->assertArray($result, 'error_code', 404);
        
        // sign in with email_1
        $grandCode = $this->_getGrantCode('wegra.lee@gmail.com', 'password');
        $result = $this->_post("http://stadioom:8080/api/auth/signIn", array('grantType' => 'authorization_code',
            'code' => $grandCode));
        $this->assertArray_NotNull($result, 'accessToken');
        
        // now.. keep the access token of email_1 for later tests.
        $json = json_decode($result);
        $accessToken = $json->accessToken;
        
        // invite 'invalid' email -> invalid
        $result = $this->_post("http://stadioom:8080/api/auth/invite", array('accessToken' => $accessToken,
            'inviteeEmails' => array('invalid.email.com')));
        $this->assertArray($result, 'invalid.email.com', 'invalid email.');
        
        // invite email_2 -> already registered
        $result = $this->_post("http://stadioom:8080/api/auth/invite", array('accessToken' => $accessToken,
            'inviteeEmails' => array('wegra@seedshock.com'), 'invitationMessage' => 'This is a custom message for test.'));
        $this->assertArray($result, 'wegra@seedshock.com', 'already registered.');
        
        // invite email_3 -> succeed.
        $result = $this->_post("http://stadioom:8080/api/auth/invite", array('accessToken' => $accessToken,
            'inviteeEmails' => array('xegra.lee@gmail.com'), 'invitationMessage' => 'This is a custom message for test.'));
        $this->assertArray($result, 'xegra.lee@gmail.com', 'invitation sent.');
        
        // invite nobody -> failed.
        $result = $this->_post("http://stadioom:8080/api/auth/invite", array('accessToken' => $accessToken,
            'invitationMessage' => 'This is a custom message for test.'));
        $this->assertArray($result, 'error_code', 400);
        
        // invite all of them -> ..
        // invite email_2 -> already registered
        $result = $this->_post("http://stadioom:8080/api/auth/invite", array('accessToken' => $accessToken,
            'inviteeEmails' => array('wegra@seedshock.com', 'invalid.email.com', 'xegra.lee@gmail.com'), 'invitationMessage' => 'This is a custom message for test.'
            ));
        $this->assertArray($result, 'invalid.email.com', 'invalid email.');
        $this->assertArray($result, 'wegra@seedshock.com', 'already registered.');
        $this->assertArray($result, 'xegra.lee@gmail.com', 'invitation sent.');
        
        echo "All Tests Passed.";
    }

    public function testStadioomFb_get() {
        // connect with 'invalid' fbId -> failed
        // connect with wegra.lee's fbId
        // connect with wegra.lee's fbId
        // now.. keep the access token of wegra.lee's
        // invite 'invalid(non-integer)' fbId -> failed
        // invite own '1', '2', and own fbId -> already registered
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

    public function loginByFacebook_get() {
        $this->load->library('fb_connect');
        $this->load->helper('url');
        $param['redirect_uri'] = site_url("api/test/facebook");
        redirect($this->fb_connect->getLoginUrl($param));
    }

    public function facebook_get() {
        $this->load->library('fb_connect');
        if (!$this->fb_connect->user_id) {
            //Handle not logged in,
        } else {
            $fb_uid = $this->fb_connect->user_id;
            $fb_usr = $this->fb_connect->user;
            //Hanlde user logged in, you can update your session with the available data
            //print_r($fb_usr) will help to see what is returned
        }
    }

    public function encoding_get() {
        $this->output->set_header("HTTP/1.1 200 OK");
        $this->output->set_content_type('text/html; charset=utf-8');

        $result = "";

        $data['name'] = 'Norú 복연';
        $intermediate = $data['name'];
        $result = $result . "original: " . $intermediate . "<br>";
        $intermediate = utf8_encode($intermediate);
        $result = $result . "utf8_encoded: " . $intermediate . "<br>";
        $intermediate = json_encode($intermediate);
        $result = $result . "json_encoded: " . $intermediate . "<br>";
        $intermediate = json_decode($intermediate);
        $result = $result . "json_decoded: " . $intermediate . "<br>";
        $intermediate = utf8_decode($intermediate);
        $result = $result . "utf8_encoded: " . $intermediate . "<br>";

        $this->output->set_output($result);
    }

}

?>
