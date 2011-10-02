<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class SportTest extends Test_REST_Controller {

    protected $accessToken = 0;
    protected $sportId1 = 0;
    protected $sportId2 = 0;

    function __construct() {
        parent::__construct();
        if (function_exists('force_ssl'))
            remove_ssl();
    }

    public function beforeClass() {
        // sign in with test account.
        $testAccountEmail = $this->config->item('test_account');
        $testAccountPassword = $this->config->item('test_account_password');
        $grantCode = $this->generateGrantCode($testAccountEmail, $testAccountPassword);
        $result = $this->sendPost($this->config->item('base_url') . "api/auth/signUp", array('grantType' => 'authorization_code', 'code' => $grantCode, 'name' => 'test account #1', 'gender' => 'male', 'dob' => 1232123222));
        $result = $this->sendPost($this->config->item('base_url') . "api/auth/signIn", array('grantType' => 'authorization_code', 'code' => $grantCode));
        $this->accessToken = json_decode($result)->accessToken;

        // add sport 1, 2.
        $result = $this->sendPost($this->config->item('base_url') . "api/sport", array('accessToken' => $this->accessToken, 'name' => 'sport_1', 'desc' => 'Sport for testing_1 with higher weight', 'weight' => 100, 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));
        $this->sportId1 = json_decode($result);
        $result = $this->sendPost($this->config->item('base_url') . "api/sport", array('accessToken' => $this->accessToken, 'name' => 'sport_2', 'desc' => 'Sport for testing_2 with lower weight', 'weight' => 1, 'firstRevision' => 1, 'latestRevision' => 2, 'updateFlag' => '2'));
        $this->sportId2 = json_decode($result);
    }

    public function afterClass() {
        // delete sport 1, 2.
//        $this->sendDelete($this->config->item('base_url') . 'api/sport', array('accessToken' => $this->accessToken, 'id' => $this->sportId1));
//        $this->sendDelete($this->config->item('base_url') . 'api/sport', array('accessToken' => $this->accessToken, 'id' => $this->sportId2));

        // sign out.
//        $this->sendPost($this->config->item('base_url') . "api/auth/signUp", array('accessToken' => $accessToken));
    }

    public function testGetById() {
        // get sport 1
        $result = $this->runTest("get a sport info by ID #" . $this->sportId1, "api/sport", array('accessToken' => $this->accessToken, 'id' => $this->sportId1, 'XDEBUG_SESSION_START' => 'netbeans-xdebug'), 'GET');
        Assert::assertArray($result, 'id', $this->sportId1);

        // get sport 2
        $result = $this->runTest("get a sport info by ID #" . $this->sportId2, "api/sport", array('accessToken' => $this->accessToken, 'id' => $this->sportId2), 'GET');
        Assert::assertArray($result, 'id', $this->sportId2);

        // get sport 3 (not exist)
        $result = $this->runTest("get a sport info by (non-existing) ID #" . ($this->sportId2 + 100), "api/sport", array('accessToken' => $this->accessToken, 'id' => ($this->sportId2 + 100)), 'GET');
        Assert::assertError($result, 404);

        // get sport 3 (not exist)
        $result = $this->runTest("get a sport info by (non-existing) ID #0", "api/sport", array('accessToken' => $this->accessToken, 'id' => 0), 'GET');
        Assert::assertError($result, 400);

        // get sport 'xyz' (invalid input)
        $result = $this->runTest("get a sport info by (invalid) ID #xyz", "api/sport", array('accessToken' => $this->accessToken, 'id' => 'xyz'), 'GET');
        Assert::assertError($result, 400);
    }

    public function testGetAll() {
        // get all sports
        $result = $this->runTest("get all sports", "api/sport", array('accessToken' => $this->accessToken), 'GET');
        Assert::assertInArray($result, 0, 'id', $this->sportId1);
        Assert::assertInArray($result, 1, 'id', $this->sportId2);
    }

}