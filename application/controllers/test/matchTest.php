<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class MatchTest extends Test_REST_Controller {

    private $em;
    private $accessToken = 0;
    private $me = NULL;
    private $meFb = NULL;
    private $testUsers = array();
    private $testUsersFb = array();
    private $matchIds = array();
    private $now;

    function __construct() {
        parent::__construct();
        $this->load->library('doctrine');
        $this->em = $this->doctrine->em;

        $this->load->model('dao/MatchDao');
        force_ssl();
//        if (function_exists('force_ssl'))
//            remove_ssl();
    }

    public function beforeClass() {
        // sign in with test account.
        $this->meFb = $this->createTestUser();
        $param = array('fbId' => $this->meFb['fbId'], 'fbAccessToken' => $this->meFb['fbAccessToken'], 'fbExpires' => 0);
        $result = $this->sendPost($this->config->item('base_url') . "api/fb/connect", $param);
        $this->accessToken = json_decode($result)->accessToken;

        $q = $this->em->createQuery("SELECT u FROM Entities\User u WHERE u.email = '" . $this->meFb['email'] . "'");
        $user = $q->getResult();
        $this->me = $user[0];

        // get test users;
        for ($i = 1; $i <= 4; $i++) {
            $q = $this->em->createQuery("SELECT u FROM Entities\User u WHERE u.name = '" . 'testUser' . $i . "'");
            $user = $q->getResult();
            array_push($this->testUsers, $user[0]);

            $q = $this->em->createQuery("SELECT u FROM Entities\User u WHERE u.name = '" . 'testUserFb' . $i . "'");
            $user = $q->getResult();
            array_push($this->testUsersFb, $user[0]);
        }

        // set current timestamp;
        $now = new DateTime();
        $this->now = $now->getTimestamp();
    }

    public function afterClass() {
        // delete all matches registered during this test.
//        if (is_array($this->matchIds) && count($this->matchIds) > 0) {
//            foreach ($this->matchIds as $matchId) {
//                $this->MatchDao->deleteMatch($matchId, $this->me->getId());
//            }
//        }
//
//        // delete temporal 'me';
//        $q = $this->em->createQuery("DELETE FROM Entities\User u WHERE u.id = " . $this->me->getId());
//        $q->execute();
    }

    public function testRegisterSingle_StadioomUser_get() {
        // me vs. user 1.
        $param = array('accessToken' => $this->accessToken,
            'sportId' => 1,
            'brandId' => 2,
            'title' => 'valid test match..',
            'leagueType' => 1, // amature
            'started' => $this->now,
            'ended' => $this->now,
            'scoreA' => 4,
            'scoreB' => 2,
            'memberFbIdsB' => array('649290919'),
            'memberFbIdsB' => array('100000155192872'),
        );
        $param['XDEBUG_SESSION_START'] = 'netbeans-xdebug';
        $result = $this->runTest("me vs. user 1.", "api/match", $param);
        $result = json_decode($result);
        Assert::assertTrue($result->id > 0);

        array_push($this->matchIds, $result->id);

//        // user 1 vs. user 2.
//        $param = array('accessToken' => $this->accessToken,
//            'sportId' => 1,
//            'brandId' => 2,
//            'title' => 'valid test match..',
//            'leagueType' => 2, // pro
//            'started' => $this->now,
//            'ended' => $this->now,
//            'scoreA' => 0,
//            'scoreB' => 3,
//            'memberIdsA' => array($this->testUsers[0]->getId()),
//            'memberIdsB' => array($this->testUsers[1]->getId())
//        );
//        $result = $this->runTest("user 1 vs. user 2.", "api/match", $param);
//        $result = json_decode($result);
//        Assert::assertTrue($result->id > 0);
//        
//        array_push($this->matchIds, $result->id);
    }

//    public function testRegisterSingle_StadioomUser_N_get() {
//        // me vs. nobody.
//        $param = array('accessToken' => $this->accessToken,
//            'sportId' => 1,
//            'brandId' => 2,
//            'title' => 'valid test match..',
//            'leagueType' => 1, // amature
//            'started' => $this->now,
//            'ended' => $this->now,
//            'scoreA' => 4,
//            'scoreB' => 2,
//            'memberIdsA' => array($this->me->getId()),
//            'memberIdsB' => array()
//        );
//        $result = $this->runTest("me vs. nobody(empty array).", "api/match", $param);
//        Assert::assertError($result, 400);
//        array_push($this->matchIds, $result);
//        
//        // me vs. nobody.
//        $param = array('accessToken' => $this->accessToken,
//            'sportId' => 1,
//            'brandId' => 2,
//            'title' => 'valid test match..',
//            'leagueType' => 1, // amature
//            'started' => $this->now,
//            'ended' => $this->now,
//            'scoreA' => 4,
//            'scoreB' => 2,
//            'memberIdsA' => array($this->me->getId())
//        );
//        $result = $this->runTest("me vs. nobody(missing).", "api/match", $param);
//        Assert::assertError($result, 400);
//        array_push($this->matchIds, $result);
//
//        // nobody vs. me.
//        $param = array('accessToken' => $this->accessToken,
//            'sportId' => 1,
//            'brandId' => 2,
//            'title' => 'valid test match..',
//            'leagueType' => 1, // amature
//            'started' => $this->now,
//            'ended' => $this->now,
//            'scoreA' => 4,
//            'scoreB' => 2,
//            'memberIdsA' => array(),
//            'memberIdsB' => array($this->me->getId())
//        );
//        $result = $this->runTest("nobody(empty array) vs. me.", "api/match", $param);
//        Assert::assertError($result, 400);
//        
//        // nobody vs. me.
//        array_push($this->matchIds, $result);
//                $param = array('accessToken' => $this->accessToken,
//            'sportId' => 1,
//            'brandId' => 2,
//            'title' => 'valid test match..',
//            'leagueType' => 1, // amature
//            'started' => $this->now,
//            'ended' => $this->now,
//            'scoreA' => 4,
//            'scoreB' => 2,
//            'memberIdsB' => array($this->me->getId())
//        );
//        $result = $this->runTest("nobody(missing) vs. me.", "api/match", $param);
//        Assert::assertError($result, 400);
//        array_push($this->matchIds, $result);
//
//        // ghost vs. me.
//        // me vs. me.
//        // me vs. user 1. (invalid sport)
//        // me vs. user 1. (negative scores)
//        // me vs. user 1 (ended with scores)
//        // me vs. user 1 (scored before being started).
//        // me vs. user 1, 2
//    }

    public function testRegisterTeam_StadioomUsers_get() {
        // {me, user 1} vs. {user 2, 3}
        // {user 1, user 2} vs. {user 3, 4}
    }

//    public function testRegister() {
//        $param = array('accessToken' => $this->accessToken,
//            'sportId' => 2,
//            'brandId' => 2,
//            'title' => 'another match..',
//            'matchType' => 2, // single
//            'leagueType' => 1, // amature
//            'started' => 12312312,
//            'ended' => 12312999,
//            'scoreA' => 4,
//            'scoreB' => 2,
//            'memberIdsA' => array('3'),
//            'memberIdsB' => array('2')
//        );
////        $param['XDEBUG_SESSION_START'] = 'netbeans-xdebug';
//        $result = $this->runTest("register a match with valid info. (me vs. user 3)", "api/match", $param);
//        Assert::assertTrue(intval($result) > 0);
//    }
    // register a match with valid info. (me vs. user 3)
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
//            'memberIdsA' => array('1', '2'),
//            'memberIdsB' => array('3', '4'),
//        );
//        $param = array('accessToken' => $this->accessToken,
//            'sportId' => 1,
//            'brandId' => 1,
//            'title' => 'test',
//            'matchType' => 1, // single
//            'leagueType' => 1, // amature
//            'started' => 12312312,
//            'ended' => 12312999,
//            'scoreA' => 3,
//            'scoreB' => 2,
//            'memberIdsA' => array('1', '2'),
//            'memberIdsB' => array('3', '4')
//        );
////        $param['XDEBUG_SESSION_START'] = 'netbeans-xdebug';
//        $result = $this->runTest("register a match with valid info. (me vs. user 3)", "api/match", $param);
//        Assert::assertTrue(intval($result) > 0);
    // register a match with valid info. (me vs. user 3)
//        $param = array('accessToken' => $this->accessToken,
//            'sportId' => 2,
//            'brandId' => 2,
//            'title' => 'another match..',
//            'matchType' => 2, // single
//            'leagueType' => 1, // amature
//            'started' => 12312312,
//            'ended' => 12312999,
//            'scoreA' => 4,
//            'scoreB' => 2,
//            'memberIdsA' => array('1', '2'),
//            'memberFbIdsA' => array(
//                '211333',
//                '545718810'),
//            'memberIdsB' => array('3', '4'),
//            'memberFbIdsB' => array(
//                '6851770',
//                '5482373601')
//        );
////        $param['XDEBUG_SESSION_START'] = 'netbeans-xdebug';
//        $result = $this->runTest("register a match with valid info. (me vs. user 3)", "api/match", $param);
//        Assert::assertTrue(intval($result) > 0);
    // register a match with valid info. ({me, user 1} vs. {user 3, 4})
    // register a match with facebook IDs. ({me, user 1} vs. {user 3, fbId 1})
    // register a match with facebook IDs. ({me, fbId 1} vs. {fbId 2, 3})
//        // get sport 1
//        $result = $this->runTest("get a sport info by ID #" . $this->sportId1, "api/sport", array('accessToken' => $this->accessToken, 'id' => $this->sportId1), 'GET');
//        Assert::assertArray($result, 'id', $this->sportId1);
//
//        // get sport 2
//        $result = $this->runTest("get a sport info by ID #" . $this->sportId2, "api/sport", array('accessToken' => $this->accessToken, 'id' => $this->sportId2), 'GET');
//        Assert::assertArray($result, 'id', $this->sportId2);
//
//        // get sport 3 (not exist)
//        $result = $this->runTest("get a sport info by (non-existing) ID #" . ($this->sportId2 + 100), "api/sport", array('accessToken' => $this->accessToken, 'id' => ($this->sportId2 + 100)), 'GET');
//        Assert::assertError($result, 404);
//
//        // get sport 3 (not exist)
//        $result = $this->runTest("get a sport info by (non-existing) ID #0", "api/sport", array('accessToken' => $this->accessToken, 'id' => 0), 'GET');
//        Assert::assertError($result, 400);
//
//        // get sport 'xyz' (invalid input)
//        $result = $this->runTest("get a sport info by (invalid) ID #xyz", "api/sport", array('accessToken' => $this->accessToken, 'id' => 'xyz'), 'GET');
//        Assert::assertError($result, 400);
//    }

    public function testShare() {
        // register a match with valid info. (me vs. user 3)
//        $param = array('accessToken' => $this->accessToken,
//            'sportId' => 1,
//            'brandId' => 1,
//            'title' => 'test',
//            'matchType' => 1, // single
//            'leagueType' => 1, // amature
//            'started' => 12312312,
//            'ended' => 12312999,
//            'scoreA' => 3,
//            'scoreB' => 2,
//            'memberIdsA' => array('1', '2'),
//            'memberIdsB' => array('3', '4')
//        );
////        $param['XDEBUG_SESSION_START'] = 'netbeans-xdebug';
//        $result = $this->runTest("register a match with valid info. (me vs. user 3)", "api/match", $param);
//        Assert::assertTrue(intval($result) > 0);
        // register a match with valid info. (me vs. user 3)
//        $param = array('accessToken' => $this->accessToken,
//            'matchId' => 1,
//            'targetMedia' => 'facebook',
//            'link' => 'facebook.com/shared_at_there',
//            'comment' => 'check it out'
//        );
////        $param['XDEBUG_SESSION_START'] = 'netbeans-xdebug';
//        $result = $this->runTest('share a media', "api/match/share", $param);
//        Assert::assertTrue(intval($result) > 0);
    }

    public function testRegister_N() {
        // register a match with invalid info. ({me} vs. {user 3, 4, 6})
        // register a match with invalid sport ID. (me vs. user 3)
        // register a match with unsupported sport ID. (me vs. user 3)
        // register a match with invalid brand ID. (me vs. user 3)
        // register a match with unsupported brand ID. (me vs. user 3)
    }

//    public function testGetList_get() {
//        $param = array('accessToken' => $this->accessToken,
//            'matchId' => 2);
//        $result = $this->runTest("get a match with id: " . $param['matchId'], "api/match", $param, 'GET');
//        Assert::assertArray($result, 'id', $param['matchId']);
//
//        $param = array('accessToken' => $this->accessToken,
//            'since' => 1317803729,
//            'sportId' => 1,
//            'limit' => 20,
//            'firstOffset' => 0);
//        $result = $this->runTest("get all matches since : " . $param['since'], "api/match", $param, 'GET');
//
//        $param = array('accessToken' => $this->accessToken,
//            'since' => 1317803729,
////            'sportId' => 1, // every sports
//            'limit' => 20,
//            'firstOffset' => 0);
//        $result = $this->runTest("get all matches since : " . $param['since'], "api/match", $param, 'GET');
//
//        $param = array('accessToken' => $this->accessToken,
////            'sportId' => 1, // every sports
//            'limit' => 20,
//            'ownerId' => 3,
//            'firstOffset' => 0);
//        $result = $this->runTest("get all matches since : " . $param['since'], "api/match", $param, 'GET');
//
//        $param = array('accessToken' => $this->accessToken,
////            'sportId' => 1, // every sports
//            'limit' => 20,
//            'memberId' => 2,
//            'firstOffset' => 0);
//        $result = $this->runTest("get all matches since : " . $param['since'], "api/match", $param, 'GET');
//    }

    public function testGetList() {
        // pre: register 5 matches (sport ID = 1, date = 2011-10-01)
        // pre: register 9 matches (sport ID = 1, date = 2011-10-02)
        // pre: register 3 matches (sport ID = 1, date = 2011-10-03)
        // pre: register 3 matches (sport ID = 2, date = 2011-10-02)
        // get registered matches. (sport ID = 1, since = 2011-10-02, limit = 5, page = 1)
        // get registered matches. (sport ID = 1, since = 2011-10-02, limit = 5, page = 2)
        // get registered matches. (sport ID = 1, since = 2011-10-02, limit = 5, page = 3)
        // get registered matches. (sport ID = 1, since = 2011-10-02, limit = 5, page = 4)
        // get registered matches. (sport ID = 2, since = 2011-10-02, limit = 5, page = 1)
        // get registered matches. (sport ID = 2, since = 2011-10-02, limit = 5, page = 2)
        // get registered matches. (sport ID = 2, since = 2011-10-03, limit = 5, page = 1)
        // get registered matches. (sport ID = 2, limit = 5, page = 1)
        // get registered matches. (sport ID = null, since = 2011-10-02, limit = 5, page = 2)
        // negatives..
        // get registered matches. (sport ID = 3, limit = 5, page = 1)
        // get registered matches. (sport ID = xyx, limit = 5, page = 1)
        // get registered matches. (sport ID = 2, limit = xyz, page = 1)
        // get registered matches. (sport ID = 2, limit = 5, page = xyz)
        // get registered matches. (sport ID = -1, limit = 10, page = 1)
        // get registered matches. (sport ID = 3, limit = 10000, page = 1)
        // get registered matches. (sport ID = 3, limit = 10000, page = 0)
        // get registered matches. (sport ID = 3, limit = 10000, page = -1)
    }

}