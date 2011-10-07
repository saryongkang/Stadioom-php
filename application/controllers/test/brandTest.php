<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class BrandTest extends Test_REST_Controller {

    protected $accessToken = 0;
    protected $brandId1 = 0;
    protected $brandId2 = 0;
    protected $brandId3 = 0;
    protected $sportId1 = 0;
    protected $sportId2 = 0;
    protected $sportId3 = 0;

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
        $result = $this->sendPost($this->config->item('base_url') . "api/brand", array('accessToken' => $this->accessToken, 'stringId' => 'brand_1', 'name' => 'brand_1', 'desc' => 'Brand for testing_1 with higher priority', 'priority' => 100, 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));
        $this->brandId1 = json_decode($result);
        $result = $this->sendPost($this->config->item('base_url') . "api/brand", array('accessToken' => $this->accessToken, 'stringId' => 'brand_2', 'name' => 'brand_2', 'desc' => 'Brand for testing_2 with lower priority', 'priority' => 1, 'firstRevision' => 1, 'latestRevision' => 2, 'updateFlag' => '2'));
        $this->brandId2 = json_decode($result);
        $result = $this->sendPost($this->config->item('base_url') . "api/brand", array('accessToken' => $this->accessToken, 'stringId' => 'brand_3', 'name' => 'brand_3', 'desc' => 'Brand for testing_3 with mid priority', 'priority' => 50, 'firstRevision' => 1, 'latestRevision' => 2, 'updateFlag' => '2'));
        $this->brandId3 = json_decode($result);

        // add sport 1, 2, 3.
        $result = $this->sendPost($this->config->item('base_url') . "api/sport", array('accessToken' => $this->accessToken, 'stringId' => 'brand_1', 'name' => 'sport_1', 'desc' => 'Sport for testing_1 with higher priority', 'priority' => 100, 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));
        $this->sportId1 = json_decode($result);
        $result = $this->sendPost($this->config->item('base_url') . "api/sport", array('accessToken' => $this->accessToken, 'stringId' => 'brand_2', 'name' => 'sport_2', 'desc' => 'Sport for testing_2 with lower priority', 'priority' => 1, 'firstRevision' => 1, 'latestRevision' => 2, 'updateFlag' => '2'));
        $this->sportId2 = json_decode($result);
        $result = $this->sendPost($this->config->item('base_url') . "api/sport", array('accessToken' => $this->accessToken, 'stringId' => 'brand_3', 'name' => 'sport_3', 'desc' => 'Sport for testing_3 with mid priority', 'priority' => 50, 'firstRevision' => 1, 'latestRevision' => 2, 'updateFlag' => '2'));
        $this->sportId3 = json_decode($result);

        // map brand 1 to sport 1.
        $result = $this->sendPost($this->config->item('base_url') . "api/brand/sports", array('accessToken' => $this->accessToken, 'brandId' => $this->brandId1, 'sportIds' => array($this->sportId1)));

        // map brand 2 to sport 1, 2.
        $result = $this->sendPost($this->config->item('base_url') . "api/brand/sports", array('accessToken' => $this->accessToken, 'brandId' => $this->brandId2, 'sportIds' => array($this->sportId1, $this->sportId2)));
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
        // TODO: enable asserts below in proper way.
//        Assert::assertInData($result, 0, 'id', $this->brandId1);
//        Assert::assertInData($result, 1, 'id', $this->brandId3);
//        Assert::assertInData($result, 2, 'id', $this->brandId2);
    }

    public function testGetSponsoredSports() {
        // get sports sponsored by brand 1.
        $result = $this->runTest("get sports sponsored by brand " . $this->brandId1, "api/brand/sports", array('accessToken' => $this->accessToken, 'id' => $this->brandId1), 'GET');
        Assert::assertArrayCount($result, 1);
        Assert::assertContainsInData($result, 'id', $this->sportId1);

        // get sports sponsored by brand 2.
        $result = $this->runTest("get sports sponsored by brand " . $this->brandId2, "api/brand/sports", array('accessToken' => $this->accessToken, 'id' => $this->brandId2), 'GET');
        Assert::assertArrayCount($result, 2);
        Assert::assertContainsInData($result, 'id', $this->sportId1);
        Assert::assertContainsInData($result, 'id', $this->sportId2);

        // get sports sponsored by brand 3.
        $result = $this->runTest("get sports sponsored by brand " . $this->brandId3, "api/brand/sports", array('accessToken' => $this->accessToken, 'id' => $this->brandId3), 'GET');
        $result = json_decode($result);
        Assert::assertTrue(count($result->data) == 0);
    }

    public function testDelta() {
        $result = $this->runTest("get all brands modified after revision 0", "api/brand/delta", array('accessToken' => $this->accessToken, 'after' => 0), 'GET');
        $jsonDecoded = json_decode($result);
        $jsonDecoded = $jsonDecoded->data;
        foreach ($jsonDecoded as $element) {
            if ($element->latestRevision <= 0) {
                Assert::fail();
            }
        }
        
        $result = $this->runTest("get all brands modified after revision 1", "api/brand/delta", array('accessToken' => $this->accessToken, 'after' => 1), 'GET');
        $jsonDecoded = json_decode($result);
        $jsonDecoded = $jsonDecoded->data;
        foreach ($jsonDecoded as $element) {
            if ($element->latestRevision <= 1) {
                Assert::fail();
            }
        }
        
        $result = $this->runTest("get all brands modified after revision 2", "api/brand/delta", array('accessToken' => $this->accessToken, 'after' => 2), 'GET');
        $jsonDecoded = json_decode($result);
        $jsonDecoded = $jsonDecoded->data;
        foreach ($jsonDecoded as $element) {
            if ($element->latestRevision <= 2) {
                Assert::fail();
            }
        }
    }

}