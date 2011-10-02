<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class BrandTest extends Test_REST_Controller {

    protected $accessToken = 0;
    protected $brandId1 = 0;
    protected $brandId2 = 0;
    protected $brandId3 = 0;
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

        // add brand 1, 2, 3.
        $result = $this->sendPost($this->config->item('base_url') . "api/brand", array('accessToken' => $this->accessToken, 'name' => 'brand_1', 'desc' => 'Brand for testing_1 with higher weight', 'weight' => 100, 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));
        $this->brandId1 = json_decode($result);
        $result = $this->sendPost($this->config->item('base_url') . "api/brand", array('accessToken' => $this->accessToken, 'name' => 'brand_2', 'desc' => 'Brand for testing_2 with lower weight', 'weight' => 1, 'firstRevision' => 1, 'latestRevision' => 2, 'updateFlag' => '2'));
        $this->brandId2 = json_decode($result);
        $result = $this->sendPost($this->config->item('base_url') . "api/brand", array('accessToken' => $this->accessToken, 'name' => 'brand_3', 'desc' => 'Brand for testing_3 with lower weight', 'weight' => 50, 'firstRevision' => 1, 'latestRevision' => 2, 'updateFlag' => '2'));
        $this->brandId3 = json_decode($result);
        
        // add sport 1, 2.
//        $result = $this->sendPost($this->config->item('base_url') . "api/sport", array('accessToken' => $this->accessToken, 'name' => 'sport_1', 'desc' => 'Sport for testing_1 with higher weight', 'weight' => 100, 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));
//        $this->sportId1 = json_decode($result);
//        $result = $this->sendPost($this->config->item('base_url') . "api/sport", array('accessToken' => $this->accessToken, 'name' => 'sport_2', 'desc' => 'Sport for testing_2 with lower weight', 'weight' => 1, 'firstRevision' => 1, 'latestRevision' => 2, 'updateFlag' => '2'));
//        $this->sportId2 = json_decode($result);
        
        // map brand 1 to sport 1.
        // map brand 2 to sport 1, 2.
    }

    public function afterClass() {
        // remove sport 1, 2
//        $this->sendDelete($this->config->item('base_url') . 'api/sport', array('accessToken' => $this->accessToken, 'id' => $this->sportId1));
//        $this->sendDelete($this->config->item('base_url') . 'api/sport', array('accessToken' => $this->accessToken, 'id' => $this->sportId2));

        // remove brand 1, 2, 3
//        $this->sendDelete($this->config->item('base_url') . 'api/brand', array('accessToken' => $this->accessToken, 'id' => $this->brandId1));
//        $this->sendDelete($this->config->item('base_url') . 'api/brand', array('accessToken' => $this->accessToken, 'id' => $this->brandId2));
//        $this->sendDelete($this->config->item('base_url') . 'api/brand', array('accessToken' => $this->accessToken, 'id' => $this->brandId3));
        
        // (mapping info should be updated automatically)
    }

    public function testGetById() {
        // get brand 1
        $result = $this->runTest("get a brand info by ID #" . $this->brandId1, "api/brand", array('accessToken' => $this->accessToken, 'id' => $this->brandId1), 'GET');
        Assert::assertArray($result, 'id', $this->brandId1);

        // get brand 2
        $result = $this->runTest("get a brand info by ID #" . $this->brandId2, "api/brand", array('accessToken' => $this->accessToken, 'id' => $this->brandId2), 'GET');
        Assert::assertArray($result, 'id', $this->brandId2);

        // get brand 4 (not exist)
        $result = $this->runTest("get a brand info by (non-existing) ID #" . ($this->brandId3 + 100), "api/brand", array('accessToken' => $this->accessToken, 'id' => ($this->brandId3 + 100)), 'GET');
        Assert::assertError($result, 404);

        // get brand 5 (not exist)
        $result = $this->runTest("get a brand info by (non-existing) ID #0", "api/brand", array('accessToken' => $this->accessToken, 'id' => 0), 'GET');
        Assert::assertError($result, 400);

        // get brand 'xyz' (invalid input)
        $result = $this->runTest("get a brand info by (invalid) ID #xyz", "api/brand", array('accessToken' => $this->accessToken, 'id' => 'xyz'), 'GET');
        Assert::assertError($result, 400);
    }

    public function testGetAll() {
        // get all brands
        $result = $this->runTest("get all sports", "api/brand", array('accessToken' => $this->accessToken), 'GET');
        Assert::assertInArray($result, 0, 'id', $this->brandId1);
        Assert::assertInArray($result, 1, 'id', $this->brandId3);
        Assert::assertInArray($result, 2, 'id', $this->brandId2);
    }

    public function testGetSponsoredSports() {
        // get sports sponsored by brand 1.
        // get sports sponsored by brand 2.
        // get sports sponsored by brand 3.
        throw new Exception("Not Implemented", 501);
    }

}