<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class WegraGoToSv extends Test_REST_Controller {

    private $em;

    function __construct() {
        parent::__construct();
        $this->load->library('doctrine');
        $this->em = $this->doctrine->em;
        
        $this->load->model('dao/UserDao');


        if (function_exists('force_ssl'))
            remove_ssl();
    }

    public function initBrandSport_get() {
        // sign in with test account.
        $grantCode = $this->generateGrantCode('wegra@seedshock.com', 'rabbitball5');
        $result = $this->sendPost($this->config->item('base_url') . "api/auth/signUp", array('grantType' => 'authorization_code', 'code' => $grantCode, 'name' => 'Temporal Admin', 'gender' => 'male', 'dob' => 1232123222));
        $result = $this->sendPost($this->config->item('base_url') . "api/auth/signIn", array('grantType' => 'authorization_code', 'code' => $grantCode));
        $accessToken = json_decode($result)->accessToken;

        // make brands
        $result = $this->sendPost($this->config->item('base_url') . "api/brand", array('accessToken' => $accessToken, 'stringId' => 'seedshock', 'name' => 'SeedShock, Inc.', 'desc' => 'Maker of Stadioom.', 'priority' => 100, 'url' => 'http://seedshock.com', 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));
        $result = $this->sendPost($this->config->item('base_url') . "api/brand", array('accessToken' => $accessToken, 'stringId' => 'stadioom', 'name' => 'Stadioom®', 'desc' => 'The product you are using now.', 'priority' => 100, 'url' => 'https://stadioom.com', 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));

        // make sports
        $result = $this->sendPost($this->config->item('base_url') . "api/sport", array('accessToken' => $accessToken, 'stringId' => 'baseball', 'name' => 'Baseball', 'desc' => 'Baseball.', 'priority' => 100, 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));
        $result = $this->sendPost($this->config->item('base_url') . "api/sport", array('accessToken' => $accessToken, 'stringId' => 'basketball', 'name' => 'Basketball', 'desc' => 'Basketball.', 'priority' => 100, 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));
        $result = $this->sendPost($this->config->item('base_url') . "api/sport", array('accessToken' => $accessToken, 'stringId' => 'cricket', 'name' => 'Cricket', 'desc' => 'Cricket.', 'priority' => 100, 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));
        $result = $this->sendPost($this->config->item('base_url') . "api/sport", array('accessToken' => $accessToken, 'stringId' => 'fieldhockey', 'name' => 'Field Hockey', 'desc' => 'Field Hockey.', 'priority' => 100, 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));
        $result = $this->sendPost($this->config->item('base_url') . "api/sport", array('accessToken' => $accessToken, 'stringId' => 'americanf', 'name' => 'American Football', 'desc' => 'American Football.', 'priority' => 100, 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));
        $result = $this->sendPost($this->config->item('base_url') . "api/sport", array('accessToken' => $accessToken, 'stringId' => 'icehockey', 'name' => 'Ice Hockey', 'desc' => 'Ice Hockey.', 'priority' => 100, 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));
        $result = $this->sendPost($this->config->item('base_url') . "api/sport", array('accessToken' => $accessToken, 'stringId' => 'rugby', 'name' => 'Rugby', 'desc' => 'Rugby.', 'priority' => 100, 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));
        $result = $this->sendPost($this->config->item('base_url') . "api/sport", array('accessToken' => $accessToken, 'stringId' => 'soccer', 'name' => 'Soccer', 'desc' => 'Also known as Football (not American Football).', 'priority' => 100, 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));
        $result = $this->sendPost($this->config->item('base_url') . "api/sport", array('accessToken' => $accessToken, 'stringId' => 'softball', 'name' => 'Softball', 'desc' => 'Softball.', 'priority' => 100, 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));
        $result = $this->sendPost($this->config->item('base_url') . "api/sport", array('accessToken' => $accessToken, 'stringId' => 'squash', 'name' => 'Squash', 'desc' => 'Squash.', 'priority' => 100, 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));
        $result = $this->sendPost($this->config->item('base_url') . "api/sport", array('accessToken' => $accessToken, 'stringId' => 'tabletennis', 'name' => 'Table Tennis', 'desc' => 'Table Tennis a.k.a. Ping Pong.', 'priority' => 100, 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));
        $result = $this->sendPost($this->config->item('base_url') . "api/sport", array('accessToken' => $accessToken, 'stringId' => 'tennis', 'name' => 'Tennis', 'desc' => 'Tennis.', 'priority' => 100, 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));
        $result = $this->sendPost($this->config->item('base_url') . "api/sport", array('accessToken' => $accessToken, 'stringId' => 'volleyball', 'name' => 'Volley Ball', 'desc' => 'Valley Ball.', 'priority' => 100, 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));

        // link them
        $result = $this->sendPost($this->config->item('base_url') . "api/brand/sports", array('accessToken' => $accessToken, 'brandId' => 1, 'sportIds' => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13)));
        $result = $this->sendPost($this->config->item('base_url') . "api/brand/sports", array('accessToken' => $accessToken, 'brandId' => 2, 'sportIds' => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13)));
    }

    public function clearUsers_get() {
        
    }

    public function clearTestUsers() {
        
    }

    public function clearMatches_get() {
        $q1 = $this->em->createQuery('DELETE Entities\MatchRecordMemberA m WHERE m.id != 0');
        $q2 = $this->em->createQuery('DELETE Entities\MatchRecordMemberB m WHERE m.id != 0');
        $q3 = $this->em->createQuery('DELETE Entities\MatchRecord m WHERE m.id != 0');

        $q1->execute();
        $q2->execute();
        $q3->execute();
    }

    public function createTestUsers_get() {
        for ($i = 1; $i <= 4; $i++) {
            $q = $this->em->createQuery("SELECT u FROM Entities\User u WHERE u.name = '" . 'testUser' . $i . "'");
            $user = $q->getResult();
            if ($user == null) {
                // create a user.
                $user = new Entities\User();
                $user->setName("testUser" . $i);
                $user->setEmail("testUser" . $i . "@seedshock.com");
                $user->setPassword(md5("testUser#~"));
                $user->setFbLinked(FALSE);
                $user->setFbAuthorized(FALSE);
                $user->setVerified(TRUE);
                $this->em->persist($user);
                $this->em->flush();
            }
            
            $q = $this->em->createQuery("SELECT u FROM Entities\User u WHERE u.name = '" . 'testUserFb' . $i . "'");
            $user = $q->getResult();
            if ($user == null) {
                // create a user.
                $fbUser = $this->createTestUser();
                $fbUser['fbExpires'] = 0;
                $result = $this->UserDao->fbConnect($fbUser);
                $q = $this->em->createQuery("UPDATE Entities\User u SET u.name = 'testUserFb" . $i . "' WHERE u.id = " . $result['id']);
                $q->execute();
            }
        }
    }

    public function removeTestUsers_get() {
        $q = $this->em->createQuery("DELETE Entities\User u WHERE u.name LIKE 'testUser%'");
        $q->execute();
    }
}

?>
