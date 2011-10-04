<?php

class UserDao extends CI_Model {

    private $em;

    public function __construct() {
        parent::__construct();
        $this->load->library('doctrine');

        $this->em = $this->doctrine->em;
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
        if (!$this->isValidEmail($user->getEmail())) {
            throw new Exception("Invalid email.", 400);
        }
        $password = $user->getPassword();
        $valid = $this->isValidPassword($password);

        if (!$this->isValidPassword($user->getPassword())) {
            throw new Exception("Invalid password.", 400);
        }

        $prevUser = $this->em->getRepository('Entities\User')->findOneByEmail($user->getEmail());
        if ($prevUser != null) {
            if ($this->config->item('user_verification_enabled') && !$prevUser->getVerified()) {
                throw new Exception("Waiting Verification. Check your email.", 403);
            }

            if ($prevUser->getPassword() != md5($user->getPassword())) {
                throw new Exception("Wrong password.", 403);
            }
        } else {
            throw new Exception("Unregistered account.", 404);
        }

        return $this->generateAccessToken($prevUser);
    }

    /**
     * Connect to Facebook with the given Facebook ID and access token.
     * It create Stadioom user account automatically if the Facebook User has not been registered yet.
     * 
     * @param string $fbId The Facebook user ID to connect.
     * @param string $fbAccessToken The Facebook access token for the user.
     * @return array Array contains 'id', 'fullName', and 'accessToken'.
     * 
     * @throws Exception 401 - if failed to access Facebook with the given fbAccessToken.
     */
    public function fbConnect($fbInfo) {
        if ($fbInfo === NULL
                || $fbInfo['fbId'] == null
                || $fbInfo['fbAccessToken'] == null
                || $fbInfo['fbExpires'] == NULL) {
            throw new Exception("Insufficient data. fbId=" . $fbInfo['fbId'] . " fbAccessToken=" . $fbInfo['fbAccessToken'] . " fbExpires=" . $fbInfo['fbExpires'], 400);
            //throw new Exception("Insufficient data.", 400);
        }

        $user = $this->em->getRepository('Entities\User')->findOneByFbId($fbInfo['fbId']);
        $result = array('id' => null, 'fullName' => null, 'accessToken' => null);

        // TODO: consider transaction.
        if ($user == null) {
            // get user data from FB.
            $this->load->library('fb_connect');
            $this->fb_connect->setAccessToken($fbInfo['fbAccessToken']);

            try {
                $fbMe = $this->fb_connect->api('/me', 'GET');
            } catch (FacebookApiException $e) {
                throw new Exception("Failed to get authorized by Facebook.", 401, $e);
            }

            $this->storeUserFb($fbInfo, $fbMe);

            $result['fullName'] = $fbMe['first_name'] . ' ' . $fbMe['last_name'];
            // check whether the same email is already in User table.
            $user = $this->em->getRepository('Entities\User')->findOneByEmail($fbMe['email']);

            if ($user != null) {
                // update User table.
                $user->setFbId($fbInfo['fbId']);
                $user->setFbLinked(TRUE);
                $user->setFbAuthorized(TRUE);

                $this->em->persist($user);
                $this->em->flush();
            } else {
                // create user account.
                $user = new Entities\User();
                $user->setFbId($fbInfo['fbId']);
                $user->setFbLinked(TRUE);
                $user->setFbAuthorized(TRUE);
                $user->setName($result['fullName']);
                $user->setEmail($fbMe['email']);
                $user->setGender($fbMe['gender']);
                if (array_key_exists('birthday', $fbMe)) {
                    $birthday = $fbMe['birthday'];
                    if ($birthday != null) {
                        $user->setDob(new DateTime($birthday));
                    }
                }
                $user->setVerified(TRUE);
                $this->em->persist($user);
                $this->em->flush();

                $user = $this->em->getRepository('Entities\User')->findOneByEmail($fbMe['email']);
                $result['id'] = $user->getId();

                $invitee = $this->em->getRepository('Entities\Invitee')->findOneByInviteeEmail($fbMe['email']);
                if ($invitee != null) {
                    // update 'acceptedDate' in Invitee table.
                    $invitee->setAcceptedDate(new DateTime());
                    $this->em->persist($invitee);
                    $this->em->flush();
                }
            }
            $inviteeFb = $this->em->getRepository('Entities\InviteeFb')->findOneByInviteeFbId($fbInfo['fbId']);
            if ($inviteeFb != null) {
                // update 'acceptedData' in InviteeFB table.
                $inviteeFb->setAcceptedDate(new DateTime());
                $this->em->persist($inviteeFb);
                $this->em->flush();
            }
        } else {
            // already registered
            $result['id'] = $user->getId();
            $result['fullName'] = $user->getName();
            if (!$user->getFbAuthorized()) {
                $user->setFbAuthorized(TRUE);

                $this->em->persist($user);
                $this->em->flush();
            }
        }

        $result['accessToken'] = $this->generateAccessToken($user);
        return $result;
    }

    /**
     * Deauthorizes of the given Faccbook user.
     * 
     * @param type $fbId 
     * @return Exception 400 - if the given fbId is invalid.
     * @return Exception 404 - if the given fbId could not be found.
     */
    public function fbDeauthorize($fbId) {
        if ($fbId == null) {
            throw new Exception("Invalid FB ID.", 400);
        }

        $user = $this->em->getRepository('Entities\User')->findOneByFbId($fbId);
        if ($user == null) {
            throw new Exception("FB ID not found: " . $fbId, 404);
        }

        // Update authorized field in User table.
        if ($user->getFbAuthoried()) {
            $user->setFbAuthorized(FALSE);
            $this->em->persist($user);
            $this->em->flush();
        }
    }

    public function fbDeauthorizeWithId($id) {
        if ($id == null) {
            throw new Exception("Invalid ID.", 400);
        }

        $user = $this->em->find('Entities\User', $id);
        if ($user == null) {
            throw new Exception("ID not found: " . $id, 404);
        }

        // Update authorized field in User table.
        if ($user->getFbAuthorized()) {
            $user->setFbAuthorized(FALSE);
            $this->em->persist($user);
            $this->em->flush();
        }
    }

    /**
     * Stores Facebook invitation result. (Unlike the 'invite' method, it does
     * not send invitation actually.)
     * 
     * @param type $invitorId Invitor's Stadioom ID.
     * @param type $inviteeFbIds Array of invitee's Facebook ID.
     * @return string Invitation results for each invitee.
     */
    public function fbInvite($invitorId, $inviteeFbIds) {
        // TODO: check whether the invitor is real.
        $invitedDate = new DateTime();
        if ($inviteeFbIds == null) {
            throw new Exception("At least one invitee is required.", 400);
        }
        $result;
        foreach ($inviteeFbIds as $fbId) {
            if (!ctype_digit($fbId)) {
                $result[$fbId] = "invalid ID.";
                continue;
            }
            $user = $this->em->getRepository('Entities\User')->findOneByFbId($fbId);
            if ($user != null) {
                $result[$fbId] = "already registered.";
                continue;
            }
            $result[$fbId] = "invitation sent.";
            $inviteeFb = $this->em->getRepository('Entities\InviteeFb')->findOneByInviteeFbId($fbId);
            if ($inviteeFb != null) {
                // already sent.
                continue;
            }
            try {
                $inviteeFb = new Entities\InviteeFb();
                $inviteeFb->setInviteeFbId($fbId);
                $inviteeFb->setInvitorId($invitorId);
                $inviteeFb->setInvitedDate($invitedDate);

                $this->em->persist($inviteeFb);
            } catch (Exception $e) {
                // ignore (it happens if invition has been request by multiple clients simultaneously).
            }
        }
        $this->em->flush();

        return $result;
    }

    /**
     * Send invitations to the given email addresses.
     * 
     * @param type $invitorId Invitor's Stadioom ID.
     * @param type $inviteeEmails Email addresses of the invitees.
     * @param type $invitationMessage (optional) Custom invitation message.
     * @return string 
     */
    public function invite($invitorId, $inviteeEmails, $invitationMessage = null) {
        if ($invitorId == null) {
            throw new Exception("The 'invitorId' MUST NOT be NULL.", 400);
        }
        if ($inviteeEmails == null) {
            throw new Exception("The 'inviteeEmails' MUST NOT be NULL.", 400);
        }

        $invitor = $this->em->find('Entities\User', $invitorId);
        if ($invitor == null) {
            throw new Exception("Invitor not found.", 404);
        }
        $result;
        $invitedDate = new DateTime();
        foreach ($inviteeEmails as $email) {
            if (!$this->isValidEmail($email)) {
                $result[$email] = 'invalid email.';
                continue;
            }

            $user = $this->em->getRepository('Entities\User')->findOneByEmail($email);
            if ($user != null) {
                $result[$email] = "already registered.";
                continue;
            }

            $invitee = $this->em->getRepository('Entities\Invitee')->findOneByInviteeEmail($email);
            if ($invitee == null) {
                try {
                    $invitee = new Entities\Invitee();
                    $invitee->setInviteeEmail($email);
                    $invitee->setInvitorId($invitorId);
                    $invitee->setInvitedDate($invitedDate);

                    $this->em->persist($invitee);
                } catch (Exception $e) {
                    // ignore (it happens if invition has been request by multiple clients simultaneously).
                }
            }
            $subject = $invitor->getName() . " has invited you to Stadioom.";
            $message = 'Come to match!!';
            if ($invitationMessage != null) {
                $message = $message . '<br><br>From ' . $invitor->getName() . ':<br><br>' . $invitationMessage;
            }
            $message = $message . '<br><br> Your Sincerely, SeedShock.';

            $this->sendEmail($email, $subject, $message);
            $result[$email] = 'invitation sent.';
        }
        $this->em->flush();
        return $result;
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
        if (!$this->isValidEmail($user->getEmail())) {
            throw new Exception("Invalid email.", 400);
        }
        if (!$this->isValidPassword($user->getPassword())) {
            throw new Exception("Invalid password (> 5).", 400);
        }
        if (!$this->isValidName($user->getName())) {
            throw new Exception("Invalid name (3 < name <= 100).", 400);
        }

        $prevUser = $this->em->getRepository('Entities\User')->findOneByEmail($user->getEmail());

        if ($prevUser == null) {
            // create a new user object
            $user->setPassword(md5($user->getPassword()));
            $user->setFbLinked(FALSE);
            $user->setFbAuthorized(FALSE);
            $user->setVerified(FALSE);

            $this->em->persist($user);
            $this->em->flush();

            if ($this->config->item('user_verification_enabled')) {
                // generate verification code.
                $code = $this->generateVerificationCode($user->getEmail());

                // store verification info.
                $userVerification = new Entities\UserVerification();
                $userVerification->setEmail($user->getEmail());
                $userVerification->setCode($code);
                $userVerification->setIssuedDate(new DateTime());

                $this->em->persist($userVerification);
                $this->em->flush();

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
     * Generates a access token.
     * 
     * @param type $email The seed email.
     * @return string The verification code.
     */
    private function generateAccessToken($user) {
        $this->load->library('encrypt');
        $expired = 0;   // forever.
        $msg = "SeedShock:" . $user->getId() . ':' . $expired;

        return $this->encrypt->encode($msg);
    }

    /**
     * Sends a user verification message.
     * 
     * @param type $email The email to send the verification message.
     * @param type $code The verification code.
     */
    public function sendVerificationEmail($email, $code) {
        $this->sendEmail($email, '[Welcome to Stadioom] Your Verification Code', "Thanks for registering to Stadioom.<br>Please click the following URL to verify your email.<br><br> " . $this->config->item('base_url') . "/api/auth/verify?code=" . $code . "&email=" . $email . " <br><br>Your Sincerely, SeedShock.");
    }

    private function sendEmail($toEmail, $subject, $message) {
        $config = Array(
            'protocol' => $this->config->item('email_protocol'),
            'smtp_host' => $this->config->item('email_smtp_host'),
            'smtp_port' => $this->config->item('email_smtp_port'),
            'smtp_user' => $this->config->item('email_smtp_user'),
            'smtp_pass' => $this->config->item('email_smtp_pass'),
            'mailtype' => $this->config->item('email_mailtype'),
            'charset' => $this->config->item('email_charset')
        );
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");

        // TODO (Wegra): refine the email contents.
        // TODO (Wegra): the messages are mostly likely stored into outer file.
        $this->email->from($this->config->item('email_from'), $this->config->item('email_from_display_name'));
        $this->email->to($toEmail);
        $this->email->bcc($this->config->item('email_bcc'));

        $this->email->subject($subject);
        $this->email->message($message);

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
        $user = $this->em->getRepository('Entities\User')->findOneByEmail($email);
        if ($user->getVerified() != 0) {
            throw new Exception("Already Verified.", 406);
        }

        $userVerification = $this->em->getRepository('Entities\UserVerification')->findOneByEmail($email);
        if ($userVerification != null && $userVerification->getCode() == $code) {
            $user->setVerified(1);

            $this->em->beginTransaction();
            $this->em->persist($user);
            $this->em->remove($userVerification);
            $this->em->flush();
            $this->em->commit();

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
        $q = $this->em->createQuery('select u.email from Entities\User u where u.email = :email');
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
    private function isValidEmail(&$email) {
        $this->load->helper('email');

        return valid_email($email);
    }

    /**
     * Check if the password meet the requirements.
     *
     * @return boolean
     */
    private function isValidPassword(&$password) {
        $length = strlen($password);
        return 5 < $length && $length <= 20;
    }

    /**
     * Check if the name meet the requirements.
     *
     * @return boolean
     */
    private function isValidName(&$name) {
        $length = strlen($name);
        return $length == 0 || (3 < $length && $length <= 100);
    }

    /**
     * Stores Facebook user data to UserFb table.
     * 
     * @param array $fbInfo
     * @param array $fbMe 
     */
    private function storeUserFb($fbInfo, $fbMe) {
        // TODO: check duplication first.
        // add Facebook user info to UserFB table.
        $userFb = new Entities\UserFb();
        // TODO: deside what info should be included.
        $userFb->setFbId($fbInfo['fbId']);
        $userFb->setFbAccessToken($fbInfo['fbAccessToken']);
        $currentDate = new DateTime();
        $fbExpires = $currentDate->getTimeStamp() + $fbInfo['fbExpires'];
        $userFb->setFbExpires($fbExpires);
        $userFb->setGender($fbMe['gender']);


        if (array_key_exists('locale', $fbMe)) {
            $locale = $fbMe['locale'];
            if ($locale != null) {
                $userFb->setLocale($locale);
            }
        }
        if (array_key_exists('timezone', $fbMe)) {
            $timezone = $fbMe['timezone'];
            if ($timezone != null) {
                $userFb->setTimezone($timezone);
            }
        }
        if (array_key_exists('birthday', $fbMe)) {
            $birthday = $fbMe['birthday'];
            if ($birthday != null) {
                $userFb->setBirthday($birthday);
            }
        }
        if (array_key_exists('hometown', $fbMe)) {
            $hometown = $fbMe['hometown'];
            if ($hometown != null) {
                $userFb->setHometown($hometown['id'] . ' ' . $hometown['name']);
            }
        }
        if (array_key_exists('location', $fbMe)) {
            $location = $fbMe['location'];
            if ($location != null) {
                $userFb->setLocation($location['id'] . ' ' . $location['name']);
            }
        }
        if (array_key_exists('favorite_atheletes', $fbMe)) {
            // TODO: should be tested..
            $athletes = $fbMe['favorite_atheletes'];
            if ($athletes != null) {
                $userFb->setFavoriteAthletes(implode(",", $athletes));
            }
        }
        if (array_key_exists('favorite_teams', $fbMe)) {
            $teams = $fbMe['favorite_teams'];
            if ($athletes != null) {
                $userFb->setFavoriteTeams(implode(",", $teams));
            }
        }

        $this->em->persist($userFb);
        $this->em->flush();
    }

}

?>
