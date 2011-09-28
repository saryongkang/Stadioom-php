<?php

require(APPPATH . '/libraries/Test_REST_Controller.php');

/**
 * Contains stuffs for just testing.
 */
class Fb extends Test_REST_Controller {

    public function all_get() {
        // connect with 'invalid' Facebook account.
        $result = $this->_post("api/fb/connect", array('fbId' => '111', 'fbAccessToken' => 'bypass me.', 'fbExpires' => 0
                ));
        $this->assertArray($result, 'error_code', 401);

        // get valid Facebook test account.
        $testUser = $this->createTestUser();

        // connect with valid Facebook account
        $result = $this->_post("api/fb/connect", array('fbId' => $testUser['fbId'], 'fbAccessToken' => $testUser['fbAccessToken'], 'fbExpires' => 0));

        $this->assertArray_NotNull($result, 'id');
        $this->assertArray($result, 'fullName', $testUser['name']);
        $this->assertArray_NotNull($result, 'accessToken');

        // keep the ID and access token for later tests.
        $id = json_decode($result)->id;

        // connect with valid Facebook account (again)
        $result = $this->_post("api/fb/connect", array('fbId' => $testUser['fbId'], 'fbAccessToken' => $testUser['fbAccessToken'], 'fbExpires' => 0));

        $this->assertArray_NotNull($result, 'id', $id);
        $this->assertArray($result, 'fullName', $testUser['name']);
        $this->assertArray_NotNull($result, 'accessToken');
        $accessToken = json_decode($result)->accessToken;

        // deauthorize with 'invalid' accessToken -> failed
        $result = $this->_post("api/fb/deauthorize", array('accessToken' => 'invalid_token'));
        $this->assertArray($result, 'error_code', 400);

        // deauthorize with valid registered fbId
        $result = $this->_post("api/fb/deauthorize", array('accessToken' => $accessToken));
        $this->assertEquals($result, 'OK');


        // invite 'invalid' accessToken -> failed
        $result = $this->_post("api/fb/invite", array('accessToken' => 'invalid_token', 'inviteeFbIds' => array('123', '456')));
        $this->assertArray($result, 'error_code', 400);

        // invite 'invalid' fbId -> invalid
        $result = $this->_post("api/fb/invite", array('accessToken' => $accessToken,
            'inviteeFbIds' => array('invalid.fb.id')));
        $this->assertArray($result, 'invalid.fb.id', 'invalid ID.');

        // invite 'already registered' fbId -> already registered
        $result = $this->_post("api/fb/invite", array('accessToken' => $accessToken,
            'inviteeFbIds' => array($testUser['fbId'])));
        $this->assertArray($result, $testUser['fbId'], 'already registered.');

        // invite new fbId -> succeed.
        $result = $this->_post("api/fb/invite", array('accessToken' => $accessToken,
            'inviteeFbIds' => array('123')));
        $this->assertArray($result, '123', 'invitation sent.');

        // invite nobody -> failed.
        $result = $this->_post("api/fb/invite", array('accessToken' => $accessToken
                ));
        $this->assertArray($result, 'error_code', 400);

        // invite all of them -> ..
        $result = $this->_post("api/fb/invite", array('accessToken' => $accessToken,
            'inviteeFbIds' => array($testUser['fbId'], 'invalid.fb.id', '123')
                ));
        $this->assertArray($result, 'invalid.fb.id', 'invalid ID.');
        $this->assertArray($result, $testUser['fbId'], 'already registered.');
        $this->assertArray($result, '123', 'invitation sent.');


        // invite all of them with 'invalid' access token -> failed.
        $result = $this->_post("api/fb/invite", array('accessToken' => 'invalid.addess.token',
            'inviteeFbIds' => array($testUser['fbId'], 'invalid.fb.id', '123')
                ));
        $this->assertArray($result, 'error_code', 400);

        echo "All Tests Passed.";
    }

}

?>
