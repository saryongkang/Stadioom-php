<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class MatchTest extends Test_REST_Controller {

    protected $accessToken = 0;
    private $testUser1;
    private $testUser2;
    private $testUser3;
    private $testUser4;

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
        $result = $this->sendPost($this->config->item('base_url') . "api/fb/connect", array('fbId' => '649290919', 'fbAccessToken' => 'AAAC2zBZAGSiwBACtBgX0BEsBZAdL37VHp9fHxwGgcglC5vpioPzZC1ElpwaEVx0cIN5ZB7I0PvxARYWUfmjRMrDFHGdSdecZD', 'fbExpires' => 0));
        $this->accessToken = json_decode($result)->accessToken;
        
        $result = $this->sendGet($this->config->item('base_url') . "api/user/me", array('accessToken' => $this->accessToken));

        $this->testUser1 = $this->createTestUser();
//        $this->testUser2 = $this->createTestUser();
//        $this->testUser3 = $this->createTestUser();
//        $this->testUser4 = $this->createTestUser();
//
//        // add users (1, 2 for Team A, 3, 4 for Team B)
//        $result = $this->sendPost("api/auth/signUp", array('grantType' => 'authorization_code', 'code' => $this->generateGrantCode($this->testUser1['email'], $this->testUser1['password']), 'name' => $this->testUser1['name'], 'gender' => 'male', 'dob' => 1232123222));
//        $result = $this->sendPost("api/fb/connect", array('fbId' => $this->testUser2['fbId'], 'fbAccessToken' => $this->testUser2['fbAccessToken'], 'fbExpires' => 0));
//        $result = $this->sendPost("api/auth/signUp", array('grantType' => 'authorization_code', 'code' => $this->generateGrantCode($this->testUser1['email'], $this->testUser1['password']), 'name' => $this->testUser1['name'], 'gender' => 'male', 'dob' => 1232123222));
//        $result = $this->sendPost("api/fb/connect", array('fbId' => $this->testUser4['fbId'], 'fbAccessToken' => $this->testUser4['fbAccessToken'], 'fbExpires' => 0));
    }

    public function afterClass() {
        // TODO deregister users.
    }
    
    public function testLastMatch() {
        $param = array();
        $result = $this->runTest('Get Last Match', $this->config->item('base_url') . "api/match/lastMatch", $param, 'GET');
    }

//    public function testRegister() {
//        // register a match with valid info. (me vs. user 3)
//        $param = array('accessToken' => $this->accessToken,
//            'sportId' => 2,
//            'brandId' => 2,
//            'title' => 'test-sponsored match',
//            'matchType' => 2, // single
//            'leagueType' => 1, // amature
//            'started' => 12312312,
//            'ended' => 12312999,
//            'scoreA' => 4,
//            'scoreB' => 2,
//            'memberIdsA' => array('10', '20'),
//            'memberIdsB' => array('30', '40'),
//        );
////        $param = array('accessToken' => $this->accessToken,
////            'sportId' => 1,
////            'brandId' => 1,
////            'title' => 'test',
////            'matchType' => 1, // single
////            'leagueType' => 1, // amature
////            'started' => 12312312,
////            'ended' => 12312999,
////            'scoreA' => 3,
////            'scoreB' => 2,
////            'memberIdsA' => array('1', '2'),
////            'memberIdsB' => array('3', '4')
////        );
//////        $param['XDEBUG_SESSION_START'] = 'netbeans-xdebug';
////        $result = $this->runTest("register a match with valid info. (me vs. user 3)", "api/match", $param);
////        Assert::assertTrue(intval($result) > 0);
//        // register a match with valid info. (me vs. user 3)
//        $param = array('accessToken' => $this->accessToken,
//            'sportId' => 2,
//            'brandId' => 2,
//            'title' => 'test-sponsored match',
//            'matchType' => 2, // single
//            'leagueType' => 1, // amature
//            'started' => 12312312,
//            'ended' => 12312999,
//            'scoreA' => 4,
//            'scoreB' => 2,
//            'memberIdsA' => array('10', '20'),
//            'memberFbIdsA' => array(
//                '211333',
//                '6851770'),
//            'memberIdsB' => array('30', '40'),
//            'memberFbIdsB' => array(
//                '545718810',
//                '548237360')
//        );
////        $param['XDEBUG_SESSION_START'] = 'netbeans-xdebug';
//        $result = $this->runTest("register a match with valid info. (me vs. user 3)", "api/match", $param);
//        Assert::assertTrue(intval($result) > 0);
//
//
//
//        // register a match with valid info. ({me, user 1} vs. {user 3, 4})
//        // register a match with facebook IDs. ({me, user 1} vs. {user 3, fbId 1})
//        // register a match with facebook IDs. ({me, fbId 1} vs. {fbId 2, 3})
////        // get sport 1
////        $result = $this->runTest("get a sport info by ID #" . $this->sportId1, "api/sport", array('accessToken' => $this->accessToken, 'id' => $this->sportId1), 'GET');
////        Assert::assertArray($result, 'id', $this->sportId1);
////
////        // get sport 2
////        $result = $this->runTest("get a sport info by ID #" . $this->sportId2, "api/sport", array('accessToken' => $this->accessToken, 'id' => $this->sportId2), 'GET');
////        Assert::assertArray($result, 'id', $this->sportId2);
////
////        // get sport 3 (not exist)
////        $result = $this->runTest("get a sport info by (non-existing) ID #" . ($this->sportId2 + 100), "api/sport", array('accessToken' => $this->accessToken, 'id' => ($this->sportId2 + 100)), 'GET');
////        Assert::assertError($result, 404);
////
////        // get sport 3 (not exist)
////        $result = $this->runTest("get a sport info by (non-existing) ID #0", "api/sport", array('accessToken' => $this->accessToken, 'id' => 0), 'GET');
////        Assert::assertError($result, 400);
////
////        // get sport 'xyz' (invalid input)
////        $result = $this->runTest("get a sport info by (invalid) ID #xyz", "api/sport", array('accessToken' => $this->accessToken, 'id' => 'xyz'), 'GET');
////        Assert::assertError($result, 400);
//    }
//
//    public function testShare() {
//        // register a match with valid info. (me vs. user 3)
////        $param = array('accessToken' => $this->accessToken,
////            'sportId' => 1,
////            'brandId' => 1,
////            'title' => 'test',
////            'matchType' => 1, // single
////            'leagueType' => 1, // amature
////            'started' => 12312312,
////            'ended' => 12312999,
////            'scoreA' => 3,
////            'scoreB' => 2,
////            'memberIdsA' => array('1', '2'),
////            'memberIdsB' => array('3', '4')
////        );
//////        $param['XDEBUG_SESSION_START'] = 'netbeans-xdebug';
////        $result = $this->runTest("register a match with valid info. (me vs. user 3)", "api/match", $param);
////        Assert::assertTrue(intval($result) > 0);
//        // register a match with valid info. (me vs. user 3)
//        $param = array('accessToken' => $this->accessToken,
//            'matchId' => 1,
//            'targetMedia' => 'facebook',
//            'link' => 'facebook.com/shared_at_there',
//            'comment' => 'check it out'
//        );
////        $param['XDEBUG_SESSION_START'] = 'netbeans-xdebug';
//        $result = $this->runTest('share a media', "api/match/share", $param);
//        Assert::assertTrue(intval($result) > 0);
//    }
//
//    public function testRegister_N() {
//        // register a match with invalid info. ({me} vs. {user 3, 4, 6})
//        // register a match with invalid sport ID. (me vs. user 3)
//        // register a match with unsupported sport ID. (me vs. user 3)
//        // register a match with invalid brand ID. (me vs. user 3)
//        // register a match with unsupported brand ID. (me vs. user 3)
//    }
//
////    public function testGetList() {
////        // pre: register 5 matches (sport ID = 1, date = 2011-10-01)
////        // pre: register 9 matches (sport ID = 1, date = 2011-10-02)
////        // pre: register 3 matches (sport ID = 1, date = 2011-10-03)
////        // pre: register 3 matches (sport ID = 2, date = 2011-10-02)
////        $param = array('accessToken' => $this->accessToken,
////            'matchId' => 2);
////        $param['XDEBUG_SESSION_START'] = 'netbeans-xdebug';
////        $result = $this->runTest("get a match with id: " . $param['matchId'], "api/match", $param, 'GET');
////        Assert::assertArray($result, 'id', $param['matchId']);
////
////        $param = array('accessToken' => $this->accessToken,
////            'since' => 1317803729,
////            'sportId' => 1,
////            'limit' => 20,
////            'firstOffset' => 0);
////        $result = $this->runTest("get all matches since : " . $param['since'], "api/match", $param, 'GET');
////
////        $param = array('accessToken' => $this->accessToken,
////            'since' => 1317803729,
//////            'sportId' => 1, // every sports
////            'limit' => 20,
////            'firstOffset' => 0);
////        $result = $this->runTest("get all matches since : " . $param['since'], "api/match", $param, 'GET');
////        // get registered matches. (sport ID = 1, since = 2011-10-02, limit = 5, page = 1)
////        // get registered matches. (sport ID = 1, since = 2011-10-02, limit = 5, page = 2)
////        // get registered matches. (sport ID = 1, since = 2011-10-02, limit = 5, page = 3)
////        // get registered matches. (sport ID = 1, since = 2011-10-02, limit = 5, page = 4)
////        // get registered matches. (sport ID = 2, since = 2011-10-02, limit = 5, page = 1)
////        // get registered matches. (sport ID = 2, since = 2011-10-02, limit = 5, page = 2)
////        // get registered matches. (sport ID = 2, since = 2011-10-03, limit = 5, page = 1)
////        // get registered matches. (sport ID = 2, limit = 5, page = 1)
////        // get registered matches. (sport ID = null, since = 2011-10-02, limit = 5, page = 2)
////        // negatives..
////        // get registered matches. (sport ID = 3, limit = 5, page = 1)
////        // get registered matches. (sport ID = xyx, limit = 5, page = 1)
////        // get registered matches. (sport ID = 2, limit = xyz, page = 1)
////        // get registered matches. (sport ID = 2, limit = 5, page = xyz)
////        // get registered matches. (sport ID = -1, limit = 10, page = 1)
////        // get registered matches. (sport ID = 3, limit = 10000, page = 1)
////        // get registered matches. (sport ID = 3, limit = 10000, page = 0)
////        // get registered matches. (sport ID = 3, limit = 10000, page = -1)
////    }
////    public function testGetAll() {
////        // get all sports
////        $result = $this->runTest("get all sports", "api/sport", array('accessToken' => $this->accessToken), 'GET');
////        Assert::assertInArray($result, 0, 'id', $this->sportId1);
////        Assert::assertInArray($result, 1, 'id', $this->sportId3);
////        Assert::assertInArray($result, 2, 'id', $this->sportId2);
////    }
////
////
////    public function testGetSponsorBrands() {
////        // get brands sponsoring sport 1.
////        $result = $this->runTest("get brands sponsoring sport " . $this->sportId1, "api/sport/brand", array('accessToken' => $this->accessToken, 'sportId' => $this->sportId1), 'GET');
////        Assert::assertArrayCount($result, 2);
////        Assert::assertContainsInArray($result, 'id', $this->brandId1);
////        Assert::assertContainsInArray($result, 'id', $this->brandId2);
////
////        // get brands sponsoring sport 2.
////        $result = $this->runTest("get brands sponsoring sport " . $this->sportId2, "api/sport/brand", array('accessToken' => $this->accessToken, 'sportId' => $this->sportId2), 'GET');
////        Assert::assertArrayCount($result, 1);
////        Assert::assertContainsInArray($result, 'id', $this->brandId2);
////
////        // get brands sponsoring sport 3.
////        $result = $this->runTest("get brands sponsoring sport" . $this->sportId3, "api/sport/brand", array('accessToken' => $this->accessToken, 'sportId' => $this->sportId3), 'GET');
////        Assert::assertError($result, 404);
////    }
////
////
////    public function testDelta() {
////        $result = $this->runTest("get all sports modified after revision 0", "api/sport/delta", array('accessToken' => $this->accessToken, 'after' => 0), 'GET');
////        $jsonDecoded = json_decode($result);
////        foreach ($jsonDecoded as $element) {
////            if ($element->latestRevision <= 0) {
////                Assert::fail();
////            }
////        }
////        
////        $result = $this->runTest("get all sports modified after revision 1", "api/sport/delta", array('accessToken' => $this->accessToken, 'after' => 1), 'GET');
////        $jsonDecoded = json_decode($result);
////        foreach ($jsonDecoded as $element) {
////            if ($element->latestRevision <= 1) {
////                Assert::fail();
////            }
////        }
////        
////        $result = $this->runTest("get all sports modified after revision 2", "api/sport/delta", array('accessToken' => $this->accessToken, 'after' => 2), 'GET');
////        $jsonDecoded = json_decode($result);
////        foreach ($jsonDecoded as $element) {
////            if ($element->latestRevision <= 2) {
////                Assert::fail();
////            }
////        }
////    }
}