<?php

class UserDao extends CI_Model {

    private $em;

    public function __construct() {
        parent::__construct();
        $this->load->library('doctrine');

        $this->em = $this->doctrine->em;
    }

    public function fbtest($fbInfo) {
        log_message('debug', "fbtest: enter.");
        $this->load->library('fb_connect');
        $this->fb_connect->setAccessToken($fbInfo['fbAccessToken']);
        try {
            $fbMe = $this->fb_connect->api('/me', 'GET');
            $fbFriends = $this->fb_connect->api('/me/friends');
            foreach ($fbFriends['data'] as $fbFriend) {
                $friend = $this->fb_connect->api('/' . $fbFriend['id']);
                $friendName = $friend['name'];
            }
        } catch (FacebookApiException $e) {
            log_message('error', "Failed to get authorized by Facebook.");
            throw new Exception("Failed to get authorized by Facebook.", 401, $e);
        }
        log_message('debug', "fbtest: exit.");
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
        log_message('debug', "signIn: enter.");
        if (!$this->isValidEmail($user->getEmail())) {
            log_message('error', "Invalid email.");
            throw new Exception("Invalid email.", 400);
        }
        $password = $user->getPassword();
        $valid = $this->isValidPassword($password);

        if (!$this->isValidPassword($user->getPassword())) {
            log_message('error', "Invalid password.");
            throw new Exception("Invalid password.", 400);
        }

        $prevUser = $this->em->getRepository('Entities\User')->findOneByEmail($user->getEmail());
        if ($prevUser != null) {
            if ($this->config->item('user_verification_enabled') && !$prevUser->getVerified()) {
                log_message('error', "Waiting Verification. Check your email.");
                throw new Exception("Waiting Verification. Check your email.", 403);
            }

            if ($prevUser->getPassword() != md5($user->getPassword())) {
                log_message('error', "Wrong password");
                throw new Exception("Wrong password.", 403);
            }
        } else {
            log_message('error', "Unregistered account");
            throw new Exception("Unregistered account.", 404);
        }

        log_message('debug', "signIn: exit.");
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
        log_message('debug', "fbConnect: enter.");
        if ($fbInfo === NULL
                || $fbInfo['fbId'] == null
                || $fbInfo['fbAccessToken'] == null
                || $fbInfo['fbExpires'] === NULL) {
            log_message('error', "Insufficient data. fbId=" . $fbInfo['fbId'] . " fbAccessToken=" . $fbInfo['fbAccessToken'] . " fbExpires=" . $fbInfo['fbExpires']);
            throw new Exception("Insufficient data. fbId=" . $fbInfo['fbId'] . " fbAccessToken=" . $fbInfo['fbAccessToken'] . " fbExpires=" . $fbInfo['fbExpires'], 400);
        }
        log_message('debug', "fbId: " . $fbInfo['fbId'] . ", fbAccessToken: " . $fbInfo['fbAccessToken']);

        $user = $this->em->getRepository('Entities\User')->findOneByFbId($fbInfo['fbId']);
        $result = array('id' => null, 'fullName' => null, 'accessToken' => null);

        if ($user == null) {
            log_message('debug', "New user: " . $fbInfo['fbId'] . '.. gathering user info from Facebook.');
            // get user data from FB.
            $this->load->library('fb_connect');
            $this->fb_connect->setAccessToken($fbInfo['fbAccessToken']);

            try {
                $fbMe = $this->fb_connect->api('/me', 'GET');
//                $fbLikes = $this->fb_connect->api('/me/likes', 'GET');
//                $fbActivities = $this->fb_connect->api('/me/activities', 'GET');
//                $fbInterests = $this->fb_connect->api('/me/interests', 'GET');
            } catch (FacebookApiException $e) {
                log_message('error', "Failed to get authorized by Facebook.");
                throw new Exception("Failed to get authorized by Facebook.", 401, $e);
            }
            log_message('debug', "Gathering done.");

            $userFb = $this->storeUserFb($fbInfo, $fbMe/* , $fbLikes, $fbActivities, $fbInterests */);

            log_message('debug', "Check whether the same email already exist.");
            $result['fullName'] = $fbMe['first_name'] . ' ' . $fbMe['last_name'];
            // check whether the same email is already in User table.
            $user = $this->em->getRepository('Entities\User')->findOneByEmail($fbMe['email']);

            if ($user != null) {
                log_message('debug', "Already exists. Makes it FB linked/authorized.");
                // update User table.
                $user->setFbId($fbInfo['fbId']);
                $user->setFbLinked(TRUE);
                $user->setFbAuthorized(TRUE);
//                $user->setUserFb($userFb);

                $this->em->persist($user);
                $this->em->flush();
            } else {
                log_message('debug', "Not exist. Creates a new account.");
                // create user account.
                $user = new Entities\User();
                log_message('debug', "1");
                $user->setFbId($fbInfo['fbId']);
                $user->setFbLinked(TRUE);
                $user->setFbAuthorized(TRUE);
                log_message('debug', "2");
                $user->setName($result['fullName']);
                $user->setEmail($fbMe['email']);
                $user->setGender($fbMe['gender']);
                log_message('debug', "3");
                if (array_key_exists('birthday', $fbMe)) {
                    log_message('debug', "3-in");
                    $birthday = $fbMe['birthday'];
                    log_message('debug', "3-in-in");
                    if ($birthday != null) {
                        $user->setDob(new DateTime($birthday));
                    }
                }
                log_message('debug', "4");
                $user->setVerified(TRUE);
//                $user->setUserFb($userFb);

                log_message('debug', "5");
                $this->em->persist($user);
                $this->em->flush();

                log_message('debug', "6");
                $user = $this->em->getRepository('Entities\User')->findOneByEmail($fbMe['email']);
                $result['id'] = $user->getId();

                log_message('debug', "Check email invitation table.");
                $invitee = $this->em->getRepository('Entities\Invitee')->findOneByInviteeEmail($fbMe['email']);
                if ($invitee != null) {
                    log_message('debug', "Invited user. Make the invitation being accepted.");
                    // update 'acceptedDate' in Invitee table.
                    $invitee->setAcceptedDate(new DateTime());
                    $this->em->persist($invitee);
                    $this->em->flush();
                }
            }
            log_message('debug', "Check Facebook invitation table.");
            $inviteeFb = $this->em->getRepository('Entities\InviteeFb')->findOneByInviteeFbId($fbInfo['fbId']);
            if ($inviteeFb != null) {
                log_message('debug', "Invited user. Make the invitation being accepted.");
                // update 'acceptedData' in InviteeFB table.
                $inviteeFb->setAcceptedDate(new DateTime());
                $this->em->persist($inviteeFb);
                $this->em->flush();
            }
        } else {
            // already registered
            log_message('debug', "User account already exists.");

            if (!$user->getFbLinked()) {                // auto-generated.
                log_message('debug', "Auto-generated user: " . $fbInfo['fbId'] . "... gathering user info from Facebook.");
                // get data from FB.
                $this->load->library('fb_connect');
                $this->fb_connect->setAccessToken($fbInfo['fbAccessToken']);

                try {
                    $fbMe = $this->fb_connect->api('/me', 'GET');
//                    $fbLikes = $this->fb_connect->api('/me/likes', 'GET');
//                    $fbActivities = $this->fb_connect->api('/me/activities', 'GET');
//                    $fbInterests = $this->fb_connect->api('/me/interests', 'GET');
                } catch (FacebookApiException $e) {
                    log_message('error', "Failed to get authorized by Facebook.");
                    throw new Exception("Failed to get authorized by Facebook.", 401, $e);
                }

                // add to UserFB table.
                $userFb = $this->storeUserFb($fbInfo, $fbMe/* , $fbLikes, $fbActivities, $fbInterests */);

                log_message('debug', "Update user info with Facebook data");
                // fill user data and persist it.
                $user->setName($fbMe['first_name'] . ' ' . $fbMe['last_name']);
                log_message('debug', $user->getName());
                $user->setEmail($fbMe['email']);
                log_message('debug', $user->getEmail());
                $user->setGender($fbMe['gender']);
                log_message('debug', $user->getGender());
                if (array_key_exists('birthday', $fbMe)) {
                    log_message('debug', 'birthday exists');
                    $birthday = $fbMe['birthday'];
                    if ($birthday != null) {
                        $user->setDob(new DateTime($birthday));
                    }
                    log_message('debug', 'birthday set');
                }
                $user->setVerified(TRUE);

                log_message('debug', 'find user by email.');
                // is the FB email exist in the User table?
                $prevUser = $this->em->getRepository('Entities\User')->findOneByEmail($fbMe['email']);
                log_message('debug', 'user table.');
                if ($prevUser != null) {
                    // TODO (critical????) merge data.
                    log_message('debug', "Same email is already occupied by others. Merge them.");
                }

                $user->setFbLinked(true);
//                $user->setUserFb($userFb);
            }

            log_message('debug', 'done..');
            $result['id'] = $user->getId();
            $result['fullName'] = $user->getName();
            if (!$user->getFbAuthorized()) {
                $user->setFbAuthorized(TRUE);

                log_message('debug', 'finally.. lets persist.');
                $this->em->persist($user);
                $this->em->flush();
            }
        }

        // TODO(?) store the token in DB and reuse it.
        log_message('debug', "Generate access token.");
        $result['accessToken'] = $this->generateAccessToken($user);

        log_message('debug', "fbConnect: exit.");
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
        log_message('debug', "fbDeauthorize: enter.");
        if ($fbId == null) {
            log_message('error', "Invalid FB ID: " . $fbId);
            throw new Exception("Invalid FB ID: " . $fbId, 400);
        }

        $user = $this->em->getRepository('Entities\User')->findOneByFbId($fbId);
        if ($user == null) {
            log_message('error', "FB ID not found: " . $fbId);
            throw new Exception("FB ID not found: " . $fbId, 404);
        }

        // Update authorized field in User table.
        if ($user->getFbAuthoried()) {
            $user->setFbAuthorized(FALSE);
            $this->em->persist($user);
            $this->em->flush();
        }
        log_message('debug', "fbDeauthorize: exit.");
    }

    public function fbDeauthorizeWithId($id) {
        log_message('debug', "fbDeauthorizeWithId: enter.");
        if ($id == null) {
            log_message('error', "Invalid ID: " . $id);
            throw new Exception("Invalid ID: " . $id, 400);
        }

        $user = $this->em->find('Entities\User', $id);
        if ($user == null) {
            log_message('error', "ID not found: " . $id);
            throw new Exception("ID not found: " . $id, 404);
        }

        // Update authorized field in User table.
        if ($user->getFbAuthorized()) {
            $user->setFbAuthorized(FALSE);
            $this->em->persist($user);
            $this->em->flush();
        }
        log_message('debug', "fbDeauthorizeWithId: exit.");
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
        log_message('debug', "fbInvite: enter.");
        // TODO: check whether the invitor is real.
        $invitedDate = new DateTime();
        if ($inviteeFbIds == null) {
            log_message('error', "At least one invitee is required.");
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
                log_message('debug', 'already invited (it happens if invition has been request by multiple clients simultaneously).');
                // ignore (it happens if invition has been request by multiple clients simultaneously).
            }
        }
        $this->em->flush();

        log_message('debug', "fbInvite: exit.");
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
        log_message('debug', "invite: enter.");
        if ($invitorId == null) {
            log_message('error', "The 'invitorId' MUST NOT be NULL.");
            throw new Exception("The 'invitorId' MUST NOT be NULL.", 400);
        }
        if ($inviteeEmails == null) {
            log_message('error', "The 'inviteeEmails' MUST NOT be NULL.");
            throw new Exception("The 'inviteeEmails' MUST NOT be NULL.", 400);
        }

        $invitor = $this->em->find('Entities\User', $invitorId);
        if ($invitor == null) {
            log_message('error', "Invitor not found.");
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

        log_message('debug', "invite: exit.");
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
        log_message('debug', "signUp: enter.");
        if (!$this->isValidEmail($user->getEmail())) {
            log_message('error', "Invalid email: " . $user->getEmail());
            throw new Exception("Invalid email: " . $user->getEmail(), 400);
        }
        if (!$this->isValidPassword($user->getPassword())) {
            log_message('error', "Invalid password (> 5).");
            throw new Exception("Invalid password (> 5).", 400);
        }
        if (!$this->isValidName($user->getName())) {
            log_message('error', "Invalid name (3 < name <= 100).");
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
            log_message('error', "User alread exist.");
            throw new Exception("User already exist.", 406);
        }
        log_message('debug', "signUp: exit.");
    }

    /**
     * Generates a verification code from the given email.
     * 
     * @param type $email The seed email.
     * @return string The verification code.
     */
    private function generateVerificationCode($email) {
        log_message('debug', "generateVerificationCode: enter.");
        $secret = "Powered by " . $email . " SeedShock";
        $code = substr(md5($secret), 3, 10);

        log_message('debug', "generateVerificationCode: exit.");
        return $code;
    }

    /**
     * Generates a access token.
     * 
     * @param type $email The seed email.
     * @return string The verification code.
     */
    private function generateAccessToken($user) {
        log_message('debug', "generateAccessToken: enter.");
        $this->load->library('encrypt');
        $expired = 0;   // forever.
        $msg = "SeedShock:" . $user->getId() . ':' . $expired;

        log_message('debug', "generateAccessToken: exit.");
        return $this->encrypt->encode($msg);
    }

    /**
     * Sends a user verification message.
     * 
     * @param type $email The email to send the verification message.
     * @param type $code The verification code.
     */
    public function sendVerificationEmail($email, $code) {
        log_message('debug', "sendVefiricationEmail: enter.");
        $this->sendEmail($email, '[Welcome to Stadioom] Your Verification Code', "Thanks for registering to Stadioom.<br>Please click the following URL to verify your email.<br><br> " . $this->config->item('base_url') . "/api/auth/verify?code=" . $code . "&email=" . $email . " <br><br>Your Sincerely, SeedShock.");
        log_message('debug', "sendVefiricationEmail: exit.");
    }

    private function sendEmail($toEmail, $subject, $message) {
        log_message('debug', "sendEmail: enter.");
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
        log_message('debug', "sendEmail: exit.");
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
        log_message('debug', "verifyUser: enter.");
        $user = $this->em->getRepository('Entities\User')->findOneByEmail($email);
        if ($user->getVerified() != 0) {
            log_message('debug', "Already verified.");
            throw new Exception("Already verified.", 406);
        }

        $verified = false;
        $userVerification = $this->em->getRepository('Entities\UserVerification')->findOneByEmail($email);
        if ($userVerification != null && $userVerification->getCode() == $code) {
            $user->setVerified(1);

            $this->em->beginTransaction();
            $this->em->persist($user);
            $this->em->remove($userVerification);
            $this->em->flush();
            $this->em->commit();

            $verified = true;
        }

        log_message('debug', "verifyUser: exit.");
        return $verified;
    }

    public function find($id) {
        log_message('debug', "find: enter.");
        $user = $this->em->find('Entities\User', $id);
        if ($user == null) {
            throw new Exception("Not found: " . $id, 404);
            throw new Exception("Not found: " . $id, 404);
        }

        log_message('debug', "find: exit.");
        return $user;
    }

    public function search($type, $keyword) {
        log_message('debug', "search: enter.");
        $dql = 'select u from Entities\User u where';
        if ($type == 'name') {
            $dql .= " u.name LIKE '%" . $keyword . "%'";
        } else if ($type == 'email') {
            $dql .= " u.email LIKE '%" . $keyword . "%'";
        } else {
            $dql .= " u.name LIKE '%" . $keyword . "%' OR u.email LIKE '%" . $keyword . "%'";
        }

        $q = $this->em->createQuery($dql);
        $result = $q->getResult();
        log_message('debug', "search: exit.");
        return $result;
    }

    /**
     * Check if the email exists already in the DB
     * Used for ajax check from the frontend and maybe other clients
     *
     * @return boolean
     */
    public function isDuplicateEmail($email) {
        log_message('debug', "isDuplicateEmail: enter.");
        $q = $this->em->createQuery('select u.email from Entities\User u where u.email = :email');
        $q->setParameter('email', $email);
        $result = $q->getResult();

        log_message('debug', "isDuplicateEmail: enter.");
        return (count($result) > 0);
    }

// -- INTERNAL APIs --------------------------------------------------------
    /**
     * Check if the email is valid or not.
     *
     * @return boolean
     */
    private function isValidEmail(&$email) {
        log_message('debug', "isValidEmail: enter.");
        $this->load->helper('email');

        log_message('debug', "isValidEmail: exit.");
        return valid_email($email);
    }

    /**
     * Check if the password meet the requirements.
     *
     * @return boolean
     */
    private function isValidPassword(&$password) {
        log_message('debug', "isValidPassword: enter.");
        $length = strlen($password);
        log_message('debug', "isValidPassword: exit.");
        return 5 < $length && $length <= 20;
    }

    /**
     * Check if the name meet the requirements.
     *
     * @return boolean
     */
    private function isValidName(&$name) {
        log_message('debug', "isValidName: enter.");
        $length = strlen($name);
        return $length == 0 || (3 < $length && $length <= 100);
        log_message('debug', "isValidName: exit.");
    }

    /**
     * Stores Facebook user data to UserFb table.
     * 
     * @param array $fbInfo
     * @param array $fbMe
     * 
     * @return Entities\UserFb
     */
    private function storeUserFb($fbInfo, $fbMe/* , $fbLikes, $fbActivities, $fbInterests */) {
        log_message('debug', "storeUserFb: enter.");
        // TODO: check duplication first.
        // add Facebook user info to UserFB table.
        $userFb = $this->em->getRepository('Entities\UserFb')->findOneByFbId($fbInfo['fbId']);
        if ($userFb == null) {
            $userFb = new Entities\UserFb();
        }
        // TODO: deside what info should be included.
        $userFb->setFbId($fbInfo['fbId']);
        $userFb->setFbAccessToken($fbInfo['fbAccessToken']);
        $currentDate = new DateTime();
        $fbExpires = $currentDate->getTimeStamp() + $fbInfo['fbExpires'];
        $userFb->setFbExpires($fbExpires);
        $userFb->setName($fbMe['name']);
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

//        // stores likes
//        log_message('debug', "Stores 'likes': begin.");
//        $likes = $fbLikes['data'];
//        foreach ($likes as $like) {
//            // TODO add filter.
//            $likesFb = $this->em->getRepository("Entities\LikesFb")->findOneById($like['id']);
//            if ($likesFb == null) {
//                $likesFb = new Entities\LikesFb();
//                $likesFb->setId($like['id']);
//                $likesFb->setName($like['name']);
//                $likesFb->setCategory($like['category']);
//                $this->em->persist($likesFb);
//            }
//
//            $userFb->addLikes($likesFb);
//        }
//        $this->em->flush();
//
//        log_message('debug', "Stores 'likes': ended.");
//
//        // stores activites
//        log_message('debug', "Stores 'activities': begin.");
//        $activities = $fbActivities['data'];
//        foreach ($activities as $activity) {
//            // TODO add filter.
//            $activitiesFb = $this->em->getRepository("Entities\ActivitiesFb")->findOneById($activity['id']);
//            if ($activitiesFb == null) {
//                $activitiesFb = new Entities\ActivitiesFb();
//                $activitiesFb->setId($activity['id']);
//                $activitiesFb->setName($activity['name']);
//                $activitiesFb->setCategory($activity['category']);
//                $this->em->persist($activitiesFb);
//            }
//
//            $userFb->addActivities($activitiesFb);
//        }
//        $this->em->flush();
//
//        log_message('debug', "Stores 'activities': ended.");
//
//        // stores interests
//        log_message('debug', "Stores 'interests': begin.");
//        $interests = $fbInterests['data'];
//        foreach ($interests as $interest) {
//            // TODO add filter.
//            $interestsFb = $this->em->getRepository("Entities\InterestsFb")->findOneById($interest['id']);
//            if ($interestsFb == null) {
//                $interestsFb = new Entities\InterestsFb();
//                $interestsFb->setId($interest['id']);
//                $interestsFb->setName($interest['name']);
//                $interestsFb->setCategory($interest['category']);
//                $this->em->persist($interestsFb);
//            }
//
//            $userFb->addInterests($interestsFb);
//        }
//        $this->em->flush();
//
//        log_message('debug', "Stores 'interests': ended.");

        $this->em->persist($userFb);
        $this->em->flush();
        log_message('debug', "storeUserFb: exit.");

        return $userFb;
    }

    /**
     * Returns the latest matches the specfied user has played or registered.
     * (ordered by the last updated time (DESC).)
     * 
     * @param integer $userId The ID of the user who's played or registered the matches.
     * @param integer $firstOffset The offset of the first element.
     * @param integer $maxResult The maximum number of result.
     * @return array of Entities\MatchRecord 
     */
    public function getLatestMatches($userId, $firstOffset, $maxResult
            ) {
        log_message('debug', "getLatestMatches: enter.");
        $dql = "SELECT m, a, b";
        $dql .= " FROM Entities\MatchRecord m";
        $dql .= " JOIN m.membersA a JOIN m.membersB b";
        $dql .= " WHERE m.ownerId = " . $userId . " OR a.id = " . $userId . " OR b.id = " . $userId;
        $dql .= ' ORDER BY m.lastUpdated DESC';
        $q = $this->em->createQuery($dql);
        $q->setFirstResult($firstOffset);
        $q->setMaxResults($maxResult);
        $result = $q->getResult();

        log_message('debug', "getLatestMatches: exit.");
        return $result;
    }

    /**
     * Returns the latest matches the specfied user has played and won.
     * (ordered by the ended time (DESC).)
     * 
     * @param integer $userId The ID of the user who's played the matches.
     * @param integer $firstOffset The offset of the first element.
     * @param integer $maxRestul The maximum number of result.
     * @return array of Entities\MatchRecord 
     */
    public function getLastestWonMatches($userId, $firstOffset, $maxResult) {
        $q = $this->em->createQuery("SELECT DISTINCT m"
                . " FROM Entities\MatchRecord m"
                . " JOIN m.membersA a JOIN m.membersB b"
                . " WHERE m.ended > '1970-01-01 00:00:00' AND ((a.id = " . $userId . " AND m.scoreA > m.scoreB) OR (b.id = " . $userId . " AND m.scoreA < m.scoreB))"
                . " ORDER BY m.ended DESC");
        $q->setFirstResult($firstOffset);
        $q->setMaxResults($maxResult);
        return $q->getResult();
    }

    /**
     * Returns the latest matches the specfied user has played and lost.
     * (ordered by the ended time (DESC).)
     * 
     * @param integer $userId The ID of the user who's played the matches.
     * @param integer $firstOffset The offset of the first element.
     * @param integer $maxRestul The maximum number of result.
     * @return array of Entities\MatchRecord 
     */
    public function getLastestLostMatches($userId, $startOffset, $maxResult) {
        $q = $this->em->createQuery("SELECT DISTINCT m"
                . " FROM Entities\MatchRecord m"
                . " JOIN m.membersA a JOIN m.membersB b"
                . " WHERE m.ended > '1970-01-01 00:00:00' AND ((a.id = " . $userId . " AND m.scoreA < m.scoreB) OR (b.id = " . $userId . " AND m.scoreA > m.scoreB))"
                . " ORDER BY m.ended DESC");
        $q->setFirstResult($firstOffset);
        $q->setMaxResults($$maxResult);
        return $q->getResult();
    }

    /**
     * Returns the latest matches the specfied user has played and tied.
     * (ordered by the ended time (DESC).)
     * 
     * @param integer $userId The ID of the user who's played the matches.
     * @param integer $firstOffset The offset of the first element.
     * @param integer $maxRestul The maximum number of result.
     * @return array of Entities\MatchRecord 
     */
    public function getLastestTiedMatches($userId, $startOffset, $maxResult) {
        $q = $this->em->createQuery("SELECT DISTINCT m"
                . " FROM Entities\MatchRecord m"
                . " JOIN m.membersA a JOIN m.membersB b"
                . " WHERE m.ended > '1970-01-01 00:00:00' AND m.scoreA = m.scoreB AND (a.id = " . $userId . " OR b.id = " . $userId . ")"
                . " ORDER BY m.ended DESC");
        $q->setFirstResult($firstOffset);
        $q->setMaxResults($maxResult);
        return $q->getResult();
    }

    /**
     * Returns the latest matches the specfied user has played (excluded the users who registered but not played).
     * (ordered by the ended time (DESC).)
     * 
     * @param integer $userId The ID of the user who's played the matches.
     * @param integer $firstOffset The offset of the first element.
     * @param integer $maxRestul The maximum number of result.
     * @return array of Entities\MatchRecord 
     */
    public function getLastestPlayedMatches($userId, $firstOffset, $maxResult) {
        $q = $this->em->createQuery("SELECT DISTINCT m"
                . " FROM Entities\MatchRecord m"
                . " JOIN m.membersA a JOIN m.membersB b"
                . " WHERE m.ended > '1970-01-01 00:00:00' AND (a.id = " . $userId . " OR b.id = " . $userId . ")"
                . " ORDER BY m.ended DESC");
        $q->setFirstResult($firstOffset);
        $q->setMaxResults($maxResult);
        return $q->getResult();
    }

    /**
     * Returns the latest matches the specfied user has registered (regardless of played or not).
     * (ordered by the last updated time (DESC).)
     * 
     * @param integer $userId The ID of the user who's registered the matches.
     * @param integer $firstOffset The offset of the first element.
     * @param integer $maxRestul The maximum number of result.
     * @return array of Entities\MatchRecord 
     */
    public function getLastestRegisteredMatches($userId, $firstOffset, $maxResult) {
        $q = $this->em->createQuery("SELECT DISTINCT m"
                . " FROM Entities\MatchRecord m"
                . " WHERE m.ownerId = " . $userId
                . " ORDER BY m.lastUpdated DESC");
        $q->setFirstResult($firstOffset);
        $q->setMaxResults($maxResult);
        return $q->getResult();
    }
}

?>
