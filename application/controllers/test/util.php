<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class Util extends Stadioom_REST_Controller {

    function __construct() {
        parent::__construct();
        force_ssl();
//        if (function_exists('force_ssl'))
//            remove_ssl();
    }

    public function user_get() {
        $email = $this->get('email');
        $pwd = $this->get('password');
        $grantCode = $this->generateGrantCode($email, $pwd);

        $result = $this->sendPost($this->config->item('base_url') . "api/auth/signIn", array('grantType' => 'authorization_code', 'code' => $grantCode));
        $result = json_decode($result);
        $token = $result->accessToken;
        $encodedtoken = urlencode($token);
        $decodedtoken = urldecode($token);
        $decodedtoken2 = urlencode($encodedtoken);

        echo '1. Original: ' . $token . '<br>';
        echo '2. URL encoded (from 1): ' . $encodedtoken . '<br>';
        echo '3. URL decoded (from 1): ' . $decodedtoken . '<br>';
        echo '4. URL decoded (from 2): ' . $decodedtoken2 . '<br>';

        $result = $this->sendGet($this->config->item('base_url') . "api/user/me", array('accessToken' => $token));
        $result = json_decode($result);
        echo '<br>';
        print_r($result);
    }

    public function date_get() {
echo date_default_timezone_get() . '<br>';
echo date('H:i:s') . '<br>';
date_default_timezone_set('GMT');
echo date_default_timezone_get() . '<br>';
echo date('H:i:s') . '<br>';
//$date = new DateTime();
//        $date = $date->getTimestamp();
//        echo $date . '<br>';
//
//        echo 'time: ' . time() . '<br>';
//        $utc_str = gmdate("M d Y H:i:s", time());
//        echo 'gmtime: ' . $utc_str . '<br>';
//        $utc = strtotime($utc_str);
//        echo $utc . '<br>';
//        $utc = strtotime(gmdate("M d Y H:i:s", time()));
//        echo $utc . '<br>';
//        
//        echo '<br><br><br>';
//        echo '<br><br><br>';
//        echo '<br><br><br>';
//        echo '<br><br><br>';
//        
//        $date = new DateTime();
////        echo $date . "<br>";
////        $date->setTimezone( new DateTimeZone('UTC') );
//        echo $date->getTimestamp() . "<br>";
    }

}

?>
