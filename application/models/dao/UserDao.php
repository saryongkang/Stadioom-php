<?php
class UserDao extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->library('doctrine');
    }
    
    public function signIn(&$user) {
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
            return array('code' => $resCode, 'message' => $resMessage);
        }

        return array('accessToken' => base64_encode($user->getEmail()));
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
}

?>
