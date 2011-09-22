<?php

require(APPPATH . '/libraries/REST_Controller.php');

class User extends REST_Controller {
    function registerPost_get() {
        // create a new user object
        $user = new Entities\User;
        $user->setFbId($this->get('fbId'));
        $user->setPassword(md5($this->get('password')));
        $user->setName($this->get('name'));
        $user->setEmail($this->get('email'));
        $user->setGender($this->get('gender'));
        $dob = new DateTime();
        $dob->setTimestamp($this->get('dob'));
        $user->setDob($dob);
        $user->setCreated(new DateTime());

        $this->doctrine->em->persist($user);
        $this->doctrine->em->flush();

        $message = array('fbId' => $user->getFbId(), 'name' => $user->getName(), 'email' => $user->getEmail(), 'message' => 'ADDED!');

        $this->response($message, 200); // 200 being the HTTP response code
    }

    // REMIND (Wegra) it's a temporal API for assisting test.
    function register_post() {
        // create a new user object
        $user = new Entities\User;
        $user->setFbId($this->post('fbId'));
        $user->setPassword(md5($this->post('password')));
        $user->setName($this->post('name'));
        $user->setEmail($this->post('email'));
        $user->setGender($this->post('gender'));
        $dob = new DateTime();
        $dob->setTimestamp($this->get('dob'));
        $user->setDob($dob);
        $user->setCreated(new DateTime());

        $this->doctrine->em->persist($user);
        $this->doctrine->em->flush();


        $message = array('fbId' => $user->getFbId(), 'name' => $user->getName(), 'email' => $user->getEmail(), 'message' => 'ADDED!');

        $this->response($message, 200); // 200 being the HTTP response code
    }
}

?>
