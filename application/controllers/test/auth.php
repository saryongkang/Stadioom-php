<?php

require(APPPATH . '/libraries/Test_REST_Controller.php');

/**
 * Contains stuffs for just testing.
 */
class Auth extends Test_REST_Controller {

    public function all_get() {
        // sign up with not-implemented authorization code. -> failed
        $grantCode = $this->_getGrantCode('valid@gmail.com', 'password');
        $result = $this->_post("api/auth/signUp", array('grantType' => 'non-authorization_code',
            'code' => $grantCode,
            'name' => 'Wegra',
            'gender' => 'male',
            'dob' => 1232123222));
        $this->assertArray($result, 'error_code', 501);

        // sign up with 'invalid' email -> failed
        $grantCode = $this->_getGrantCode('wegra.lee', 'password');
        $result = $this->_post("api/auth/signUp", array('grantType' => 'authorization_code',
            'code' => $grantCode,
            'name' => 'Wegra',
            'gender' => 'male',
            'dob' => 1232123222));
        $this->assertArray($result, 'error_code', 400);

        // get valid test accounts.
        $testUser1 = $this->createTestUser();
        $testUser2 = $this->createTestUser();
        $testUser3 = $this->createTestUser();

        // sign up with email_1
        $grantCode = $this->_getGrantCode($testUser1['email'], $testUser1['password']);
        $result = $this->_post("api/auth/signUp", array('grantType' => 'authorization_code',
            'code' => $grantCode,
            'name' => $testUser1['name'],
            'gender' => 'male',
            'dob' => 1232123222));
        $this->assertEquals($result, 'OK');

        // sign up with email_2
        $grantCode = $this->_getGrantCode($testUser2['email'], $testUser2['password']);
        $result = $this->_post("api/auth/signUp", array('grantType' => 'authorization_code',
            'code' => $grantCode,
            'name' => $testUser2['name'],
            'gender' => 'male',
            'dob' => 1232123222));
        $this->assertEquals($result, 'OK');

        // sign up with email_2 (already registered) -> failed
        $grantCode = $this->_getGrantCode($testUser2['email'], $testUser2['password']);
        $result = $this->_post("api/auth/signUp", array('grantType' => 'authorization_code',
            'code' => $grantCode,
            'name' => $testUser2['name'],
            'gender' => 'male',
            'dob' => 1232123222));
        $this->assertArray($result, 'error_code', 406);

        // sign in with email_2
        $grantCode = $this->_getGrantCode($testUser2['email'], $testUser2['password']);
        $result = $this->_post("api/auth/signIn", array('grantType' => 'authorization_code',
            'code' => $grantCode));
        $this->assertArray_NotNull($result, 'accessToken');

        // sign in with email_2 w/ 'invalid' password. -> failed
        $grantCode = $this->_getGrantCode($testUser2['email'], $testUser2['password'] . '_');
        $result = $this->_post("api/auth/signIn", array('grantType' => 'authorization_code',
            'code' => $grantCode));
        $this->assertArray($result, 'error_code', 403);

        // sign in with 'unregistered' email. -> failed
        $grantCode = $this->_getGrantCode('unregistered@seedshock.com', 'password');
        $result = $this->_post("api/auth/signIn", array('grantType' => 'authorization_code',
            'code' => $grantCode));
        $this->assertArray($result, 'error_code', 404);

        // sign in with email_1
        $grantCode = $this->_getGrantCode($testUser1['email'], $testUser1['password']);
        $result = $this->_post("api/auth/signIn", array('grantType' => 'authorization_code',
            'code' => $grantCode));
        $this->assertArray_NotNull($result, 'accessToken');

        // now.. keep the access token of email_1 for later tests.
        $json = json_decode($result);
        $accessToken = $json->accessToken;

        // invite 'invalid' email -> invalid
        $result = $this->_post("api/auth/invite", array('accessToken' => $accessToken,
            'inviteeEmails' => array('invalid.email.com')));
        $this->assertArray($result, 'invalid.email.com', 'invalid email.');

        // invite email_2 -> already registered
        $result = $this->_post("api/auth/invite", array('accessToken' => $accessToken,
            'inviteeEmails' => array($testUser2['email']), 'invitationMessage' => 'This is a custom message for test.'));
        $this->assertArray($result, $testUser2['email'], 'already registered.');

        // invite email_3 -> succeed.
        $result = $this->_post("api/auth/invite", array('accessToken' => $accessToken,
            'inviteeEmails' => array($testUser3['email']), 'invitationMessage' => 'This is a custom message for test.'));
        $this->assertArray($result, $testUser3['email'], 'invitation sent.');

        // invite nobody -> failed.
        $result = $this->_post("api/auth/invite", array('accessToken' => $accessToken,
            'invitationMessage' => 'This is a custom message for test.'));
        $this->assertArray($result, 'error_code', 400);

        // invite all of them -> ..
        $result = $this->_post("api/auth/invite", array('accessToken' => $accessToken,
            'inviteeEmails' => array($testUser2['email'], 'invalid.email.com', 'xegra.lee@gmail.com'), 'invitationMessage' => 'This is a custom message for test.'));
        $this->assertArray($result, 'invalid.email.com', 'invalid email.');
        $this->assertArray($result, $testUser2['email'], 'already registered.');
        $this->assertArray($result, 'xegra.lee@gmail.com', 'invitation sent.');

        echo "All Tests Passed.";
    }

//
//    public function base64_encode_get() {
//        $msg = $this->get('email') . ":" . $this->get('password');
//
//        $this->response(base64_encode($msg), 200);
//    }
//
//    public function url_base64_encode_get() {
//        $msg = $this->get('email') . ":" . $this->get('password');
//
//        $this->response(urlencode(base64_encode($msg)), 200);
//    }
//
//    public function sendEmail_get() {
//        $this->UserDao->sendVerificationEmail("wegra.lee@gmail.com", "codecodecode");
//    }
}

?>
