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

        // TODO (high): reimplement generating access token mechanism.
        return base64_encode($prevUser->getEmail());
    }

    /**
     * Connect to Facebook with the given Facebook ID and access token.
     * It create Stadioom user account automatically if the Facebook User has not been registered yet.
     * 
     * @param string $fbId The Facebook user ID to connect.
     * @param string $fbAccessToken The Facebook access token for the user.
     * @return string accessToken
     * 
     * @throws Exception 401 - if failed to access Facebook with the given fbAccessToken.
     */
    public function fbConnect($fbInfo) {
        if ($fbInfo == NULL
                || $fbInfo['fbId'] == NULL
                || $fbInfo['fbAccessToken'] == NULL
                || $fbInfo['fbExpires'] == NULL) {
            throw new Exception("Insufficient data.", 400);
        }

        $user = $this->doctrine->em->getRepository('Entities\User')->findOneBy(array('fbId' => $fbInfo['fbId']));
        $result = array('id' => NULL, 'fullName' => NULL, 'accessToken' => NULL);

        if ($user == null) {
            // get user data from FB.
            $this->load->library('fb_connect');
            $this->fb_connect->setAccessToken($fbInfo['fbAccessToken']);

            try {
                $fbMe = $this->fb_connect->api('/me', 'GET');
            } catch (FacebookApiException $e) {
                throw new Exception("Failed to get authorized by Facebook.", 401, $e);
            }

            // add Facebook user info to UserFB table.
            $userFb = new Entities\UserFb();
            // TODO: deside what info should be included.
            $userFb->setFbId($fbInfo['fbId']);
            $userFb->setFbAccessToken($fbInfo['fbAccessToken']);
            $userFb->setFbExpires(new DateTime($fbInfo['fbExpires']));

            $this->doctrine->em->persist($userFb);
            $this->doctrine->em->flush();

            $result['accessToken'] = $fbMe['email'];
            $result['fullName'] = $fbMe['firstName'] . ' ' . $fbMe['lastName'];
            // check whether the same email is already in User table.
            $userWithSameEmail = $this->doctrine->em->getRepository('Entities\User')->findOneBy(array('email' => $fbMe['email']));

            if ($userWithSameEmail != NULL) {
                // update User table.
                $userWithSameEmail->setFbId($fbInfo['fbId']);
                $userWithSameEmail->setFbLinked(TRUE);
                $userWithSameEmail->setFbAuthorized(TRUE);

                $this->doctrine->em->persist($userWithSameEmail);
                $this->doctrine->em->flush();
            } else {

                // create user account.
                $user = new Entities\User();
                $user->setFbId($fbInfo['fbId']);
                $user->setFbLinked(TRUE);
                $user->setFbAuthorized(TRUE);
                $user->setName($result['fullName']);
                $user->setEmail($fbMe['email']);
                $user->setGender($fbMe['gender']);
                $user->setDob(new DateTime($fbMe['birthday']));
                $user->setVerified(TRUE);
                $user->setCreated(new DateTime());
                $this->doctrine->em->persist($user);
                $this->doctrine->em->flush();

                $user = $this->doctrine->em->getRepository('Entities\User')->findOneBy(array('email' => $fbMe['email']));
                $result['id'] = $user->getId();

                $invitee = $this->doctrine->em->getRepository('Entities\Invitee')->findOneBy(array('email' => $fbMe['email']));
                if ($invitee != NULL) {
                    // update 'acceptedDate' in Invitee table.
                    $invitee->setAcceptedDate(new DateTime());
                    $this->doctrine->em->persist($invitee);
                    $this->doctrine->em->flush();
                }
            }
            $inviteeFb = $this->doctrine->em->getRepository('Entities\InviteeFb')->findOneBy(array('fbId' => $fbInfo['fbId']));
            if ($inviteeFb != NULL) {
                // update 'acceptedData' in InviteeFB table.
                $inviteeFb->setAcceptedDate(new DateTime());
                $this->doctrine->em->persist($inviteeFb);
                $this->doctrine->em->flush();
            }
        } else {
            // already registered
            $result['id'] = $user->getId();
            $result['accessToken'] = $user->getEmail();
            $result['fullName'] = $user->getName();
            if (!$user->getFbAuthorized()) {
                $user->setFbAuthorized(TRUE);

                $this->doctrine->em->persist($user);
                $this->doctrine->em->flush();
            }
        }
//        $this->doctrine->em->flush();
//        $this->doctrine->em->commit();
        // TODO (high): reimplement generating access token mechanism.
        $result['accessToken'] = base64_encode($result['accessToken']);
        return $result;
    }

    /**
     * Deauthorizes of the given Faccbook user.
     * 
     * @param type $fbId 
     * @return Exception 400 - if the given fbId is invalid.
     * @return Exception 404 - if the given fbId could not be found.
     */
    public function fbDeauthorized($fbId) {
        if ($fbId == NULL) {
            throw new Exception("Invalid FB ID.", 400);
        }

        $user = $this->doctrine->em->getRepository('Entities\User')->findOneBy(array('fbId' => $fbId));
        if ($user == null) {
            throw new Exception("FB ID not found: " . $fbId, 404);
        }

        // Update authorized field in User table.
        $user->setFbAuthorized(FALSE);
        $this->doctrine->em->persist($user);
        $this->doctrine->em->flush();
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

            if ($this->config->item('user_verification_enabled')) {
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
            }
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
    public function isDuplicateEmail($email) {
        $q = $this->doctrine->em->createQuery('select u.email from Entities\User u where u.email = :email');
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
