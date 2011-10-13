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
        $started = DateTime::createFromFormat("Y-m-d H:i:s", "2011-10-05 16:01:23", new DateTimeZone("GMT"));
        echo $started->format("Y-m-d H:i:s");
    }

}

?>
