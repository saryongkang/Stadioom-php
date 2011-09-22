<?php

require(APPPATH . '/libraries/REST_Controller.php');

class Auth extends REST_Controller {

    public function signIn_post() {
        $user = new Entities\User();
        $user->setEmail($this->post('email'));
        $user->setPassword($this->post('password'));

        $this->_signIn($user);
    }

    // (Wegra) temporal API for test.
    public function signIn_post_get() {
        $user = new Entities\User();
        $user->setEmail($this->get('email'));
        $user->setPassword($this->get('password'));

        $this->_signIn($user);
    }

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

        $this->response(array('code' => $resCode, 'message' => $resMessage), $resCode);
    }

    public function signUp_post() {
        $user = new Entities\User();
        $user->setEmail($this->post('email'));
        $user->setName($this->post('name'));
        $user->setPassword($this->post('password'));
        $user->setGender($this->post('gender'));
        $dob = new DateTime();
        $dob->setTimestamp($this->post('dob'));
        $user->setDob($dob);

        $this->_signUn($user);
    }

    // (Wegra) temporal API for test.
    public function signUp_post_get() {
        $user = new Entities\User();
        $user->setEmail($this->get('email'));
        $user->setName($this->get('name'));
        $user->setPassword($this->get('password'));
        $user->setGender($this->get('gender'));
        $dob = new DateTime();
        $dob->setTimestamp($this->get('dob'));
        $user->setDob($dob);

        $this->_signUp($user);
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
        $this->response(array('code' => $resCode, 'message' => $resMessage), $resCode);
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

}

?>
