<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class Util extends Stadioom_REST_Controller {
    const logSomething = "logSomething";
    
    private $evm;
    private $em;
    
    function __construct() {
        parent::__construct();
        $this->load->library('doctrine');
        $this->em = $this->doctrine->em;

        $this->load->model('dao/UserDao');
        
        force_ssl();
//        if (function_exists('force_ssl'))
//            remove_ssl();
        $this->evm = new \Doctrine\Common\EventManager();
        $this->evm->addEventListener(array(self::logSomething), $this);
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
    
    public function event_get() {
        log_message('debug', 'dispatching event.');
        $this->evm->dispatchEvent(Util::logSomething);
        echo 'Hello';
    }

    public function logSomething(\Doctrine\Common\EventArgs $e) {
        log_message('debug', "Help~!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! It's an error.");
    }
    
    public function latest_get() {
        $now = new DateTime();
        $from = $now->sub(new DateInterval('P5D'));
        $now = new DateTime();
        $to = $now->sub(new DateInterval('P4D'));
        
        $matches = $this->UserDao->getLatestMatches(2);

//        $matches = $this->UserDao->getLatestMatches2(2, $from, $now);
        
//        foreach($matches as $match) {
//            echo $match->getId() . " " . $match->getTitle() . '<br>';
//        }
        $allMatches = array();
        foreach ($matches as $match) {
            array_push($allMatches, $match->toArray());
        }
        $this->responseOk(array('data' => $allMatches));

        foreach ($matches as $match) {
            echo $match->getId() . " " . $match->getTitle() . '<br>';
        }
    }
    public function latest1_get() {
        $matches = $this->UserDao->getLatestMatch(2);

//        $matches = $this->UserDao->getLatestMatches2(2, $from, $now);
        
//        foreach($matches as $match) {
//            echo $match->getId() . " " . $match->getTitle() . '<br>';
//        }
        $allMatches = array();
        foreach ($matches as $match) {
//            array_push($allMatches, $match->toArray());
//        }
//        $this->responseOk(array('data' => $allMatches));

//        foreach ($matches as $match) {
            echo $match->getId() . " " . $match->getTitle() . '<br>';
        }
    }
}

?>
