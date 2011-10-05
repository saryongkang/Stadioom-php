<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class WegraGoToSv extends Test_REST_Controller {
    protected $accessToken = 0;

    function __construct() {
        parent::__construct();
        if (function_exists('force_ssl'))
            remove_ssl();
    }

    public function beforeClass() {
        // sign in with test account.
        $grantCode = $this->generateGrantCode('wegra@seedshock.com', 'rabbitball5');
        $result = $this->sendPost($this->config->item('base_url') . "api/auth/signUp", array('grantType' => 'authorization_code', 'code' => $grantCode, 'name' => 'Temporal Admin', 'gender' => 'male', 'dob' => 1232123222));
        $result = $this->sendPost($this->config->item('base_url') . "api/auth/signIn", array('grantType' => 'authorization_code', 'code' => $grantCode));
        $this->accessToken = json_decode($result)->accessToken;
        
        $result = $this->sendPost($this->config->item('base_url') . "api/brand", array('accessToken' => $this->accessToken, 'name' => 'SeedShock, Inc.', 'desc' => 'Maker of Stadioom.', 'priority' => 100, 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));
        $result = $this->sendPost($this->config->item('base_url') . "api/brand", array('accessToken' => $this->accessToken, 'name' => 'Stadioom®', 'desc' => 'The product you are using now.', 'priority' => 100, 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));
        
        $result = $this->sendPost($this->config->item('base_url') . "api/sport", array('accessToken' => $this->accessToken, 'name' => 'Basketball', 'desc' => 'Basketball.', 'priority' => 100, 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));
        $result = $this->sendPost($this->config->item('base_url') . "api/sport", array('accessToken' => $this->accessToken, 'name' => 'Soccer', 'desc' => 'Also known as Football (not American Football).', 'priority' => 100, 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));
        $result = $this->sendPost($this->config->item('base_url') . "api/sport", array('accessToken' => $this->accessToken, 'name' => 'Tennis', 'desc' => 'Tennis.', 'priority' => 100, 'firstRevision' => 1, 'latestRevision' => 1, 'updateFlag' => '1'));
        
        $result = $this->sendPost($this->config->item('base_url') . "api/brand/sport", array('accessToken' => $this->accessToken, 'brandId' => 1, 'sportId' => 1));
        $result = $this->sendPost($this->config->item('base_url') . "api/brand/sport", array('accessToken' => $this->accessToken, 'brandId' => 1, 'sportId' => 2));
        $result = $this->sendPost($this->config->item('base_url') . "api/brand/sport", array('accessToken' => $this->accessToken, 'brandId' => 1, 'sportId' => 3));
        
        $result = $this->sendPost($this->config->item('base_url') . "api/brand/sport", array('accessToken' => $this->accessToken, 'brandId' => 2, 'sportId' => 1));
        $result = $this->sendPost($this->config->item('base_url') . "api/brand/sport", array('accessToken' => $this->accessToken, 'brandId' => 2, 'sportId' => 2));
        $result = $this->sendPost($this->config->item('base_url') . "api/brand/sport", array('accessToken' => $this->accessToken, 'brandId' => 2, 'sportId' => 3));
    }
}
?>
