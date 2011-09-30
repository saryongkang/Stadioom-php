<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

/**
 * Contains stuffs for just testing.
 */
class Auth extends Test_REST_Controller {
    public function __construct() {
        parent::__construct();
        
        force_ssl();
    }

    public function all_get() {
        try {
            $grantCode = $this->generateGrantCode('valid@gmail.com', 'password');
            $result = $this->testPost("sign up with an yet-implemented authorization code.", "api/auth/signUp", array('grantType' => 'non-authorization_code',
                'code' => $grantCode,
                'name' => 'Wegra',
                'gender' => 'male',
                'dob' => 1232123222));
            $this->assertArray($result, 'error_code', 501);
            echo '=> PASSED.<br><br>';

            $grantCode = $this->generateGrantCode('wegra.lee', 'password');
            $result = $this->testPost("sign up with an 'invalid' email", "api/auth/signUp", array('grantType' => 'authorization_code',
                'code' => $grantCode,
                'name' => 'Wegra',
                'gender' => 'male',
                'dob' => 1232123222));
            $this->assertArray($result, 'error_code', 400);
            echo '=> PASSED.<br><br>';

            $grantCode = $this->generateGrantCode('wegra.lee', 'password');
            $result = $this->testPost("sign up with a 'too short(length = 3)' name", "api/auth/signUp", array('grantType' => 'authorization_code',
                'code' => $grantCode,
                'name' => '123',
                'gender' => 'female',
                'dob' => 1232123222));
            $this->assertArray($result, 'error_code', 400);
            echo '=> PASSED.<br><br>';

            $grantCode = $this->generateGrantCode('wegra.lee', 'password');
            $result = $this->testPost("sign up with a 'too long(length = 101)' name", "api/auth/signUp", array('grantType' => 'authorization_code',
                'code' => $grantCode,
                'name' => 'name_----1---------2---------3---------4---------5---------6---------7---------8---------9---------0-',
                'gender' => 'male',
                'dob' => 1232123222));
            $this->assertArray($result, 'error_code', 400);
            echo '=> PASSED.<br><br>';

            // get valid test accounts.
            $testUser1 = $this->createTestUser();
            $testUser2 = $this->createTestUser();
            $testUser3 = $this->createTestUser();

            $grantCode = $this->generateGrantCode($testUser1['email'], $testUser1['password']);
            $result = $this->testPost("sign up with a valid email_1", "api/auth/signUp", array('grantType' => 'authorization_code',
                'code' => $grantCode,
                'name' => $testUser1['name'],
                'gender' => 'male',
                'dob' => 1232123222));
            $this->assertEquals($result, 'OK');
            echo '=> PASSED.<br><br>';

            $grantCode = $this->generateGrantCode($testUser2['email'], $testUser2['password']);
            $result = $this->testPost("sign up with a valid email_2", "api/auth/signUp", array('grantType' => 'authorization_code',
                'code' => $grantCode,
                'name' => $testUser2['name'],
                'gender' => 'male',
                'dob' => 1232123222));
            $this->assertEquals($result, 'OK');
            echo '=> PASSED.<br><br>';

            $grantCode = $this->generateGrantCode($testUser2['email'], $testUser2['password']);
            $result = $this->testPost("sign up with a valid email_2 (already registered)", "api/auth/signUp", array('grantType' => 'authorization_code',
                'code' => $grantCode,
                'name' => $testUser2['name'],
                'gender' => 'male',
                'dob' => 1232123222));
            $this->assertArray($result, 'error_code', 406);
            echo '=> PASSED.<br><br>';

            $grantCode = $this->generateGrantCode($testUser2['email'], $testUser2['password']);
            $result = $this->testPost("sign in with a valid email_2", "api/auth/signIn", array('grantType' => 'authorization_code',
                'code' => $grantCode));
            $this->assertArray_NotNull($result, 'accessToken');
            echo '=> PASSED.<br><br>';

            $grantCode = $this->generateGrantCode($testUser2['email'], $testUser2['password'] . '_');
            $result = $this->testPost("sign in with a valid email_2 w/ 'invalid' password.", "api/auth/signIn", array('grantType' => 'authorization_code',
                'code' => $grantCode));
            $this->assertArray($result, 'error_code', 403);
            echo '=> PASSED.<br><br>';

            $grantCode = $this->generateGrantCode('unregistered@seedshock.com', 'password');
            $result = $this->testPost("sign in with an 'unregistered' email.", "api/auth/signIn", array('grantType' => 'authorization_code',
                'code' => $grantCode));
            $this->assertArray($result, 'error_code', 404);
            echo '=> PASSED.<br><br>';

            $grantCode = $this->generateGrantCode($testUser1['email'], $testUser1['password']);
            $result = $this->testPost("sign in with a valid email_1", "api/auth/signIn", array('grantType' => 'authorization_code',
                'code' => $grantCode));
            $this->assertArray_NotNull($result, 'accessToken');
            echo '=> PASSED.<br><br>';

            // now.. keep the access token of email_1 for later tests.
            $json = json_decode($result);
            $accessToken = $json->accessToken;

            $result = $this->testPost("invite an 'invalid' email", "api/auth/invite", array('accessToken' => $accessToken,
                'inviteeEmails' => array('invalid.email.com')));
            $this->assertArray($result, 'invalid.email.com', 'invalid email.');
            echo '=> PASSED.<br><br>';

            $result = $this->testPost("invite a valid email_2 (already registered)", "api/auth/invite", array('accessToken' => $accessToken,
                'inviteeEmails' => array($testUser2['email']), 'invitationMessage' => 'This is a custom message for test.'));
            $this->assertArray($result, $testUser2['email'], 'already registered.');
            echo '=> PASSED.<br><br>';

            $result = $this->testPost("invite a valid email_3", "api/auth/invite", array('accessToken' => $accessToken,
                'inviteeEmails' => array($testUser3['email']), 'invitationMessage' => 'This is a custom message for test.'));
            $this->assertArray($result, $testUser3['email'], 'invitation sent.');
            echo '=> PASSED.<br><br>';

            $result = $this->testPost("invite nobody", "api/auth/invite", array('accessToken' => $accessToken,
                'invitationMessage' => 'This is a custom message for test.'));
            $this->assertArray($result, 'error_code', 400);
            echo '=> PASSED.<br><br>';

            $result = $this->testPost("invite all of them", "api/auth/invite", array('accessToken' => $accessToken,
                'inviteeEmails' => array($testUser2['email'], 'invalid.email.com', 'xegra.lee@gmail.com'), 'invitationMessage' => 'This is a custom message for test.'));
            $this->assertArray($result, 'invalid.email.com', 'invalid email.');
            $this->assertArray($result, $testUser2['email'], 'already registered.');
            $this->assertArray($result, 'xegra.lee@gmail.com', 'invitation sent.');
            echo '=> PASSED.<br><br>';

            echo '| =============================================================================<br>';
            echo "| All Tests Passed.<br>";
            echo '| =============================================================================<br>';
        } catch (Exception $e) {
            echo '=> FAILED.<br><br>';
            echo "<pre>";
            echo 'Last Request: ' . $this->last_request . '<br>';
            echo 'Code: ' . $e->getCode() . '<br>';
            echo 'File: ' . $e->getFile() . '<br>';
            echo 'Line: ' . $e->getLine() . '<br>';
            echo 'Message: ' . $e->getMessage() . '<br>';
            echo 'Previous: ' . $e->getPrevious() . '<br>';
            echo 'Trace: <br>' . $e->getTraceAsString() . '<br>';
            echo "</pre>";
        }
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