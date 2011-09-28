<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class Auth extends Stadioom_REST_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('dao/UserDao');
    }

    /**
     * Sign-in to Stadioom service.
     * 
     * @return 200 OK with accessToken
     * 
     * @throws Exception 400 - if the code format is invalid.
     * @throws Exception 400 - if email or password is invalid.
     * @throws Exception 403 - if password is wrong.
     * @throws Exception 404 - if the user could not be found.
     */
    public function signIn_post() {
        $grantType = $this->post('grantType');
        $code = $this->post('code');

        try {
            $accessToken = $this->UserDao->signIn($this->toUser($grantType, $code));
            $this->responseOk(array('accessToken' => $accessToken));
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    /**
     * Sign-up to Stadioom service.
     * 
     * @throws Exception 400 - if the code format is invalid.
     * @throws Exception 400 - if email or password is invalid.
     * @throws Exception 406 - if the user has already registered.
     */
    public function signUp_post() {
        $grantType = $this->post('grantType');
        $code = $this->post('code');

        try {
            $user = $this->toUser($grantType, $code);
            $user->setName($this->post('name'));
            $user->setGender($this->post('gender'));
            $dob = new DateTime();
            $dob->setTimestamp($this->post('dob'));
            $user->setDob($dob);

            $this->UserDao->signUp($user);
        } catch (Exception $e) {
            $this->responseError($e);
        }

        $this->responseOk();
    }

    /**
     * Verify user.
     * 
     * @throws Exception 404 - if failed to verify by any reason.
     */
    public function verify_get() {
        $code = $this->get('code');
        $email = $this->get('email');

        // TODO (high):  MUST show result page instead of code.
        try {
            if ($this->UserDao->verifyUser($email, $code)) {
                $this->responseOk();
            }
            $this->response("Not Found.", 404);
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function invite_post() {
        $accessToken = $this->post('accessToken');

        try {
            $invitorId = $this->verifyToken($accessToken);

            $result = $this->UserDao->invite($invitorId, $this->post('inviteeEmails'), $this->post('invitationMessage'));
            $this->responseOk($result);
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    /**
     * Returns user instance if the grant info is correct.
     * 
     * @param string $grantType
     * @param string $code
     * @return Entities\User 
     * 
     * @throws Exception 400 - if the code format is invalid.
     */
    private function toUser(&$grantType, &$code) {
        if ($grantType != "authorization_code") {
            throw new Exception("Not Implemented", 501);
        }

        $decodedCode = base64_decode($code);
        $colonPosition = strpos($decodedCode, ":");
        if ($colonPosition == FALSE) {
            throw new Exception("Invalid code format ('user_id':'password')", 400);
        }

        $id = substr($decodedCode, 0, $colonPosition);
        $pwd = substr($decodedCode, $colonPosition + 1);

        if ($id == null || $pwd == null) {
            throw new Exception("Invalid code format ('user_id':'password')", 400);
        }

        $user = new Entities\User();

        $user->setEmail($id);
        $user->setPassword($pwd);

        return $user;
    }

}

?>
