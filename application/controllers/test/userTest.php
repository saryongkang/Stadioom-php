<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class UserTest extends Test_REST_Controller {

    protected $accessToken = 0;

    function __construct() {
        parent::__construct();
        force_ssl();
//        if (function_exists('force_ssl'))
//            remove_ssl();
    }

    public function beforeClass() {
        // sign in with test account.
        $testAccountEmail = $this->config->item('test_account');
        $testAccountPassword = $this->config->item('test_account_password');
        $grantCode = $this->generateGrantCode($testAccountEmail, $testAccountPassword);
        $result = $this->sendPost($this->config->item('base_url') . "api/auth/signUp", array('grantType' => 'authorization_code', 'code' => $grantCode, 'name' => 'test account #1', 'gender' => 'male', 'dob' => 1232123222));
        $result = $this->sendPost($this->config->item('base_url') . "api/auth/signIn", array('grantType' => 'authorization_code', 'code' => $grantCode));
        $this->accessToken = json_decode($result)->accessToken;
    }

    public function afterClass() {
        // sign out.
//        $this->sendPost($this->config->item('base_url') . "api/auth/signUp", array('accessToken' => $accessToken));
    }

    public function testGet() {
        // register a match with valid info. (me vs. user 3)
        $param = array('accessToken' => $this->accessToken,
            'id' => 1
        );
        $result = $this->runTest("Get the user with ID: " . $param['id'], "api/user", $param, 'GET');
        $decoded = json_decode($result);
        Assert::assertTrue($decoded->id == $param['id']);
    }

    public function testSearch() {
        // register a match with valid info. (me vs. user 3)
        $param = array('accessToken' => $this->accessToken,
            'keyword' => 'test'
        );
        $result = $this->runTest("Search by name: "  . $param['keyword'], "api/user/search", $param, 'GET');
        
        $param = array('accessToken' => $this->accessToken,
            'keyword' => 'account'
        );
        $result = $this->runTest("Search by name: "  . $param['keyword'], "api/user/search", $param, 'GET');
        
        $param = array('accessToken' => $this->accessToken,
            'keyword' => 'seed'
        );
        $result = $this->runTest("Search by name: "  . $param['keyword'], "api/user/search", $param, 'GET');
        
        $param = array('accessToken' => $this->accessToken,
            'type' => 'email',
            'keyword' => 'seed'
        );
        $result = $this->runTest("Search by name: "  . $param['keyword'], "api/user/search", $param, 'GET');
        
        // negative
        $param = array('accessToken' => $this->accessToken,
            'type' => 'name',
            'keyword' => 'seed'
        );
        $result = $this->runTest("Search by name: "  . $param['keyword'], "api/user/search", $param, 'GET');
    }
}