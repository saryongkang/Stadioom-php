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
        $password = $user->getPassword();
        $valid = $this->_checkPassword($password);

        if (!$this->_checkPassword($user->getPassword())) {
            throw new Exception("Invalid password.", 400);
        }

        $prevUser = $this->doctrine->em->getRepository('Entities\User')->findOneBy(array('email' => $user->getEmail()));
        if ($prevUser != null) {
            if (!$prevUser->getVerified()) {
                throw new Exception("Waiting Verification. Check your email.", 403);
            }

            if ($prevUser->getPassword() != md5($user->getPassword())) {
                throw new Exception("Wrong password.", 403);
            }
        } else {
            throw new Exception("Unregistered account.", 404);
        }

        return base64_encode($prevUser->getEmail());
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
            $user->setVerified(FALSE);

            $this->doctrine->em->persist($user);
            $this->doctrine->em->flush();

            // generate verification code.
            $code = $this->generateVerificationCode($user->getEmail());
            // store verification info.
            $userVerification = new Entities\UserVerification();
            $userVerification->setEmail($user->getEmail());
            $userVerification->setCode($code);
            $userVerification->setIssuedDate(new DateTime());

            $this->doctrine->em->persist($userVerification);
            $this->doctrine->em->flush();

            // send verifiation email.
            $this->sendVerificationEmail($user->getEmail(), $code);
        } else {
            throw new Exception("User already exist.", 406);
        }
    }

    /**
     * Generates a verification code from the given email.
     * 
     * @param type $email The seed email.
     * @return string The verification code.
     */
    private function generateVerificationCode($email) {
        $secret = "Powered by " . $email . " SeedShock";
        $code = substr(md5($secret), 3, 10);

        return $code;
    }

    /**
     * Sends a user verification message.
     * 
     * @param type $email The email to send the verification message.
     * @param type $code The verification code.
     */
    public function sendVerificationEmail($email, $code) {
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.gmail.com',
            'smtp_port' => 465,
            'smtp_user' => 'wegra.lee@gmail.com',
            'smtp_pass' => 'ehdrnfrjdls',
            'mailtype' => 'html',
            'charset' => 'iso-8859-1'
        );
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");

        // TODO (Wegra): refine the email contents.
        // TODO (Wegra): the messages are mostly likely stored into outer file.
        $this->email->from('stadioom@seedshock.com', 'Stadioom @ SeedShock');
        $this->email->to($email);
        $this->email->bcc('wegra.lee@gmail.com');

        $this->email->subject('[Welcome to Stadioom] Your Verification Code');
        $this->email->message("Thanks for registering to Stadioom.\n Please click the following URL to verify your email.\n\n " . $this->config->item('base_url') . "/api/auth/verify?code=" . $code . "&email=" . $email . " \n\n Your Sincerely, SeedShock.");

        $this->email->send();
    }

    /**
     * Verifies user.
     * 
     * @param string $email The user's email to verify.
     * @param string $code The user's verification code.
     * @return boolean
     * 
     * @throws Exception 406 - if the user has already verified.
     */
    public function verifyUser($email, $code) {
        $user = $this->doctrine->em->getRepository('Entities\User')->findOneBy(array('email' => $email));
        if ($user->getVerified() != 0) {
            throw new Exception("Already Verified.", 406);
        }

        $userVerification = $this->doctrine->em->getRepository('Entities\UserVerification')->findOneBy(array('email' => $email));
        if ($userVerification != null && $userVerification->getCode() == $code) {
            $user->setVerified(1);

            $this->doctrine->em->beginTransaction();
            $this->doctrine->em->persist($user);
            $this->doctrine->em->remove($userVerification);
            $this->doctrine->em->flush();
            $this->doctrine->em->commit();

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Check if the email exists already in the DB
     * Used for ajax check from the frontend and maybe other clients
     *
     * @return boolean
     */
    public function isDuplicateEmail($email)
    {
        $q = $this->doctrine->em->createQuery('select u.email from Entities\User u where u.email = :email' );
        $q->setParameter('email', $email);
        $result = $q->getResult();

        if (count($result) > 0) {
            return true;
        } else {
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
        $length = strlen($password);
        return $length > 5 && $length <= 20;
    }

    /**
     * Check if the name meet the requirements.
     *
     * @return boolean
     */
    private function _checkName(&$name) {
        $length = strlen($name);
        return $length == 0 || ($length > 4 && $length <= 20);
    }

}

?>
