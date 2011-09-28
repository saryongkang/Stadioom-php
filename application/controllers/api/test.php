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

    private function _get($uri, $param = NULL) {
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
        $result = $this->_post("http://stadioom:8080/api/auth/invite", array('accessToken' => $accessToken,
            'inviteeEmails' => array('wegra@seedshock.com', 'invalid.email.com', 'xegra.lee@gmail.com'), 'invitationMessage' => 'This is a custom message for test.'
                ));
        $this->assertArray($result, 'invalid.email.com', 'invalid email.');
        $this->assertArray($result, 'wegra@seedshock.com', 'already registered.');
        $this->assertArray($result, 'xegra.lee@gmail.com', 'invitation sent.');

        echo "All Tests Passed.";
    }

    public function fb_get() {
        // connect with 'invalid' Facebook account.
        $result = $this->_post("http://stadioom:8080/api/fb/connect", array('fbId' => '111', 'fbAccessToken' => 'bypass me.', 'fbExpires' => 0
                ));
        $this->assertArray($result, 'error_code', 401);

        // get valid Facebook test account.
        $response = $this->_get("https://graph.facebook.com/200987663288876/accounts/test-users?installed=true&permissions=publish_stream,email,offline_access&method=post&access_token=200987663288876%7C6d3dd0e7aa9dae300920ec05552bddee");
        $json_decoded = json_decode($response);
        $fbId = $json_decoded->id;
        $email = $json_decoded->email;
        $password = $json_decoded->password;
        $fbAccessToken = $json_decoded->access_token;
        $fullName = $this->getFbUserName($fbAccessToken);

        // connect with valid Facebook account
        $result = $this->_post("http://stadioom:8080/api/fb/connect", array('fbId' => $fbId, 'fbAccessToken' => $fbAccessToken, 'fbExpires' => 0
//            , 'XDEBUG_SESSION_START' => 'netbeans-xdebug'
                ));

        $this->assertArray_NotNull($result, 'id');
        $this->assertArray($result, 'fullName', $fullName);
        $this->assertArray_NotNull($result, 'accessToken');

        // keep the ID and access token for later tests.
        $id = json_decode($result)->id;

        // connect with valid Facebook account (again)
        $result = $this->_post("http://stadioom:8080/api/fb/connect", array('fbId' => $fbId, 'fbAccessToken' => $fbAccessToken, 'fbExpires' => 0));

        $this->assertArray_NotNull($result, 'id', $id);
        $this->assertArray($result, 'fullName', $fullName);
        $this->assertArray_NotNull($result, 'accessToken');
        $accessToken = json_decode($result)->accessToken;

        // deauthorize with 'invalid' accessToken -> failed
        $result = $this->_post("http://stadioom:8080/api/fb/deauthorize", array('accessToken' => 'invalid_token'));
        $this->assertArray($result, 'error_code', 400);

        // deauthorize with valid registered fbId
        $result = $this->_post("http://stadioom:8080/api/fb/deauthorize", array('accessToken' => $accessToken
//            , 'XDEBUG_SESSION_START' => 'netbeans-xdebug'
            ));
        $this->assertEquals($result, 'OK');


        //
        // invite 'invalid' accessToken -> failed
        $result = $this->_post("http://stadioom:8080/api/fb/invite", array('accessToken' => 'invalid_token', 'inviteeFbIds' => array('123', '456')));
        $this->assertArray($result, 'error_code', 400);

////////////////////
        // invite 'invalid' fbId -> invalid
        $result = $this->_post("http://stadioom:8080/api/fb/invite", array('accessToken' => $accessToken,
            'inviteeFbIds' => array('invalid.fb.id')));
        $this->assertArray($result, 'invalid.fb.id', 'invalid ID.');

        // invite 'already registered' fbId -> already registered
        $result = $this->_post("http://stadioom:8080/api/fb/invite", array('accessToken' => $accessToken,
            'inviteeFbIds' => array($fbId)));
        $this->assertArray($result, $fbId, 'already registered.');

        // invite new fbId -> succeed.
        $result = $this->_post("http://stadioom:8080/api/fb/invite", array('accessToken' => $accessToken,
            'inviteeFbIds' => array('123')));
        $this->assertArray($result, '123', 'invitation sent.');

        // invite nobody -> failed.
        $result = $this->_post("http://stadioom:8080/api/fb/invite", array('accessToken' => $accessToken
                ));
        $this->assertArray($result, 'error_code', 400);

        // invite all of them -> ..
        $result = $this->_post("http://stadioom:8080/api/fb/invite", array('accessToken' => $accessToken,
            'inviteeFbIds' => array($fbId, 'invalid.fb.id', '123')
                ));
        $this->assertArray($result, 'invalid.fb.id', 'invalid ID.');
        $this->assertArray($result, $fbId, 'already registered.');
        $this->assertArray($result, '123', 'invitation sent.');


        // invite all of them with 'invalid' access token -> failed.
        $result = $this->_post("http://stadioom:8080/api/fb/invite", array('accessToken' => 'invalid.addess.token',
            'inviteeFbIds' => array($fbId, 'invalid.fb.id', '123')
                ));
        $this->assertArray($result, 'error_code', 400);

        echo "All Tests Passed.";
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
