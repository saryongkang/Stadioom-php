<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

/**
 * Contains stuffs for just testing.
 */
class Fb extends Test_REST_Controller {

    public function all_get() {
        try {
            $result = $this->testPost("connect with an 'invalid' Facebook account.", "api/fb/connect", array('fbId' => '111', 'fbAccessToken' => 'bypass me.', 'fbExpires' => 0));
            $this->assertArray($result, 'error_code', 401);
            echo '=> PASSED.<br><br>';

            // get valid Facebook test account.
            $testUser = $this->createTestUser();

            $result = $this->testPost("connect with a valid Facebook account", "api/fb/connect", array('fbId' => $testUser['fbId'], 'fbAccessToken' => $testUser['fbAccessToken'], 'fbExpires' => 0));

            $this->assertArray_NotNull($result, 'id');
            $this->assertArray($result, 'fullName', $testUser['name']);
            $this->assertArray_NotNull($result, 'accessToken');
            echo '=> PASSED.<br><br>';

            // keep the ID and access token for later tests.
            $id = json_decode($result)->id;

            $result = $this->testPost("connect with a valid Facebook account (again)", "api/fb/connect", array('fbId' => $testUser['fbId'], 'fbAccessToken' => $testUser['fbAccessToken'], 'fbExpires' => 0));

            $this->assertArray_NotNull($result, 'id', $id);
            $this->assertArray($result, 'fullName', $testUser['name']);
            $this->assertArray_NotNull($result, 'accessToken');
            echo '=> PASSED.<br><br>';

            $accessToken = json_decode($result)->accessToken;

            $result = $this->testPost("deauthorize with an 'invalid' accessToken", "api/fb/deauthorize", array('accessToken' => 'invalid_token'));
            $this->assertArray($result, 'error_code', 400);
            echo '=> PASSED.<br><br>';

            $result = $this->testPost("deauthorize with a valid registered fbId", "api/fb/deauthorize", array('accessToken' => $accessToken));
            $this->assertEquals($result, 'OK');
            echo '=> PASSED.<br><br>';


            $result = $this->testPost("invite an 'invalid' accessToken", "api/fb/invite", array('accessToken' => 'invalid_token', 'inviteeFbIds' => array('123', '456')));
            $this->assertArray($result, 'error_code', 400);
            echo '=> PASSED.<br><br>';

            $result = $this->testPost("invite an 'invalid' fbId", "api/fb/invite", array('accessToken' => $accessToken,
                'inviteeFbIds' => array('invalid.fb.id')));
            $this->assertArray($result, 'invalid.fb.id', 'invalid ID.');
            echo '=> PASSED.<br><br>';

            $result = $this->testPost("invite an already registered' fbId -> already registered", "api/fb/invite", array('accessToken' => $accessToken,
                'inviteeFbIds' => array($testUser['fbId'])));
            $this->assertArray($result, $testUser['fbId'], 'already registered.');
            echo '=> PASSED.<br><br>';

            $result = $this->testPost("invite a new fbId", "api/fb/invite", array('accessToken' => $accessToken,
                'inviteeFbIds' => array('123')));
            $this->assertArray($result, '123', 'invitation sent.');
            echo '=> PASSED.<br><br>';

            $result = $this->testPost("invite nobody", "api/fb/invite", array('accessToken' => $accessToken
                    ));
            $this->assertArray($result, 'error_code', 400);
            echo '=> PASSED.<br><br>';

            $result = $this->testPost("invite all of them", "api/fb/invite", array('accessToken' => $accessToken,
                'inviteeFbIds' => array($testUser['fbId'], 'invalid.fb.id', '123')));
            $this->assertArray($result, 'invalid.fb.id', 'invalid ID.');
            $this->assertArray($result, $testUser['fbId'], 'already registered.');
            $this->assertArray($result, '123', 'invitation sent.');
            echo '=> PASSED.<br><br>';

            $result = $this->testPost("invite all of them with an 'invalid' access token ", "api/fb/invite", array('accessToken' => 'invalid.addess.token',
                'inviteeFbIds' => array($testUser['fbId'], 'invalid.fb.id', '123')));
            $this->assertArray($result, 'error_code', 400);
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

}