<?php
require(APPPATH . '/libraries/REST_Controller.php');

class Auth extends REST_Controller {

    // -- Official PUBLIC APIs -------------------------------------------------
    public function signIn_post() {
        $grantType = $this->post('grantType');
        $code = $this->post('code');

        $this->_signIn($this->_getUser($grantType, $code));
    }

    public function signUp_post() {
        $grantType = $this->post('grantType');
        $code = $this->post('code');

        $user = $this->_getUser($grantType, $code);
        $user->setName($this->post('name'));
        $user->setGender($this->post('gender'));
        $dob = new DateTime();
        $dob->setTimestamp($this->post('dob'));
        $user->setDob($dob);

        $this->_signUp($user);
    }

    // -- Official INTERNAL APIs -----------------------------------------------
    private function _getUser(&$grantType, &$code) {
        if ($grantType != "authorization_code") {
            $this->response(array('code' => 501, 'message' => "Not Implemented"), 501);
        }

        $this->load->library('encrypt');
        $decryptedString = $this->encrypt->decode($code);
        parse_str($decryptedString, $userInfo);

        if ($userInfo['id'] == null || $userInfo['pwd'] == null) {
            $this->response(array('code' => 400, 'message' => "Invalid Format"), 400);
        }

        $user = new Entities\User();

        $user->setEmail($userInfo['id']);
        $user->setPassword($userInfo['pwd']);

        return $user;
    }

//    private function _signIn(&$grantType, &$code) {
//        if ($grantType != "authorization_code") {
//            $this->response(array('code' => 501, 'message' => "Not Implemented"), 501);
//        }
//
//        $this->load->library('encrypt');
//        $decryptedString = $this->encrypt->decode($code);
//        parse_str($decryptedString, $userInfo);
//
//        if ($userInfo['id'] == null || $userInfo['pwd'] == null) {
//            $this->response(array('code' => 400, 'message' => "Invalid Format"), 400);
//        }
//
//        $user = new Entities\User();
//
//        $user->setEmail($userInfo['id']);
//        $user->setPassword($userInfo['pwd']);
//
//        $this->_signIn2($user);
//    }

    private function _signIn(&$user) {
        $resMessage = "OK";
        $resCode = 200;

        $this->_checkEmail($user->getEmail());
        $this->_checkPassword($user->getPassword());

        $prevUser = $this->doctrine->em->getRepository('Entities\User')->findOneBy(array('email' => $user->getEmail()));
        if ($prevUser != null) {
            if ($prevUser->getPassword() != md5($user->getPassword())) {
                $resMessage = "Invalid email or password.";
                $resCode = 403;
            }
        } else {
            $resMessage = "Invalid email or password.";
            $resCode = 404;
        }

        if ($resCode != 200) {
            $this->response(array('code' => $resCode, 'message' => $resMessage), $resCode);
        }

        $this->response(array('code' => $resCode, 'accessToken' => "temporal_access_token", 'message' => $resMessage), $resCode);
    }

    private function _signUp(&$user) {
        $resMessage = "OK";
        $resCode = 200;

        $this->_checkEmail($user->getEmail());
        $this->_checkPassword($user->getPassword());
        $this->_checkName($user->getName());

        $prevUser = $this->doctrine->em->getRepository('Entities\User')->findOneBy(array('email' => $user->getEmail()));

        if ($prevUser == null) {
            // create a new user object
            $user->setPassword(md5($user->getPassword()));
            $user->setCreated(new DateTime());

            $this->doctrine->em->persist($user);
            $this->doctrine->em->flush();
        } else {
            $resCode = 406;
            $resMessage = "User Already Exist.";
        }
        
        if ($resCode != 200) {
            $this->response(array('code' => $resCode, 'message' => $resMessage), $resCode);
        }

        $this->response(array('code' => $resCode, 'accessToken' => "temporal_access_token", 'message' => $resMessage), $resCode);
    }

    private function _checkEmail(&$email) {
        $this->load->helper('email');
        if (!valid_email($email)) {
            $resMessage = "Invalid Email Format";
            $resCode = 400;
            $this->response($resMessage, $resCode);
        }
    }

    private function _checkPassword(&$password) {
        if (strlen($password) < 6) {
            $resMessage = "Password should be longer than 5.";
            $resCode = 400;
            $this->response($resMessage, $resCode);
        }
    }

    private function _checkName(&$name) {
        if (strlen($name) < 4) {
            $resMessage = "Name should be longer than 3.";
            $resCode = 400;
            $this->response($resMessage, $resCode);
        }
    }

    // -- Old-stype APIs (w/o Auth) --------------------------------------------
    public function signIn2_post() {
        $user = new Entities\User();
        $user->setEmail($this->post('email'));
        $user->setPassword($this->post('password'));

        $this->_signIn2($user);
    }

    public function signUp2_post() {
        $user = new Entities\User();
        $user->setEmail($this->post('email'));
        $user->setName($this->post('name'));
        $user->setPassword($this->post('password'));
        $user->setGender($this->post('gender'));
        $dob = new DateTime();
        $dob->setTimestamp($this->post('dob'));
        $user->setDob($dob);

        $this->_signUp($user);
    }

    // -- Test APIs ------------------------------------------------------------
    public function signIn_post_get() {
        $grantType = $this->get('grantType');
        $code = $this->get('code');

        $this->_signIn($this->_getUser($grantType, $code));
    }

    public function signIn2_post_get() {
        $user = new Entities\User();
        $user->setEmail($this->get('email'));
        $user->setPassword($this->get('password'));

        $this->_signIn($user);
    }

    public function signUp_post_get() {
        $grantType = $this->get('grantType');
        $code = $this->get('code');

        $user = $this->_getUser($grantType, $code);
        $user->setName($this->get('name'));
        $user->setGender($this->get('gender'));
        $dob = new DateTime();
        $dob->setTimestamp($this->get('dob'));
        $user->setDob($dob);

        $this->_signUp($user);
    }

    public function encode_get() {
        $msg = "id=" . $this->get('email') . "&pwd=" . $this->get('password');

        $this->load->library('encrypt');

        $code = $this->encrypt->encode($msg);
        $decryptedString = $this->encrypt->decode($code);

        $this->response($this->encrypt->encode($msg), 200);
    }

//    public function signUpFB_post() {
//        // create a new user object
//        $user = new Entities\User;
//        $user->setFbId($this->get('fbId'));
//        $user->setName($this->post('fbAccessToken'));
//        $user->setName($this->post('name'));
//        $user->setCreated(new DateTime());
//
//        $this->doctrine->em->persist($user);
//        $this->doctrine->em->flush();
//
//
//        $message = array('fbId' => $user->getFbId(), 'name' => $user->getName(), 'message' => 'ADDED!');
//        $this->response($message, 200);
//    }
}

?>
