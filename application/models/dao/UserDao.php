<?php
class UserDao extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->library('doctrine');
    }
    
    /**
     * Check if the email is valid or not.
     *
     * @param Entities\User $user
     * @return string accessToken
     * 
     * @throws Exception 400 - if email or password is invalid.
     * @throws Exception 403 - if password is wront.
     * @throws Exception 404 - if the user could not be found. 
     */
    public function signIn(&$user) {
        if (!$this->_checkEmail($user->getEmail())) {
            throw new Exception("Invalid email.", 400);
        }
        if (!$this->_checkPassword($user->getPassword())) {
            throw new Exception("Invalid password.", 400);
        }
        
        $prevUser = $this->doctrine->em->getRepository('Entities\User')->findOneBy(array('email' => $user->getEmail()));
        if ($prevUser != null) {
            if ($prevUser->getPassword() != md5($user->getPassword())) {
                throw new Exception("Wrong password.", 403);
            }
        } else {
            throw new Exception("Unregistered account.", 404);
        }

        return base64_encode($user->getEmail());
    }

    /**
     * Check if the email is valid or not.
     *
     * @param Entities\User $user
     * 
     * @throws Exception 400 - if email or password is invalid.
     * @throws Exception 406 - if the user has already registered.
     */
    public function signUp(&$user) {
        if (!$this->_checkEmail($user->getEmail())) {
            throw new Exception("Invalid email.", 400);
        }
        if (!$this->_checkPassword($user->getPassword())) {
            throw new Exception("Invalid password (> 5).", 400);
        }
        if (!$this->_checkName($user->getName())) {
            throw new Exception("Invalid name (> 3).", 400);
        }

        
        $prevUser = $this->doctrine->em->getRepository('Entities\User')->findOneBy(array('email' => $user->getEmail()));

        if ($prevUser == null) {
            // create a new user object
            $user->setPassword(md5($user->getPassword()));
            $user->setCreated(new DateTime());

            $this->doctrine->em->persist($user);
            $this->doctrine->em->flush();
        } else {
            throw new Exception("User already exist.", 406);
        }
    }
    
    /**
     * Check if the email exists already in the DB
     * Used for ajax check from the frontend and maybe other clients
     *
     * @return boolean
     */
    public function checkDuplicateEmail($email)
    {
        $q = $this->doctrine->em->createQuery('select u.email from Entities\User u where u.email = :email' );
        $q->setParameter('email', $email);
        $result = $q->getResult();
        
        if(count($result)>0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    
    // -- INTERNAL APIs --------------------------------------------------------
    /**
     * Check if the email is valid or not.
     *
     * @return boolean
     */
    private function _checkEmail(&$email) {
        $this->load->helper('email');
        
        return valid_email($email);
    }

    /**
     * Check if the password meet the requirements.
     *
     * @return boolean
     */
    private function _checkPassword(&$password) {
        return strlen($password) > 5;
    }

    /**
     * Check if the name meet the requirements.
     *
     * @return boolean
     */
    private function _checkName(&$name) {
        return strlen($name) > 3;
    }

}

?>
