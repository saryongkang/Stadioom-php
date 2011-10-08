<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class User extends Stadioom_REST_Controller {

    private $filterKeys = array('password', 'verified');

    function __construct() {
        parent::__construct();

        $this->load->model('dao/UserDao');

        force_ssl();
//        if (function_exists('force_ssl'))
//            remove_ssl();
    }

    public function index_get() {
        $accessToken = $this->get('accessToken');

        try {
            $userId = $this->get('id');
            if ($userId == null) {
                $this->responseError("'id' is required.", 400);
            }

            $user = $this->UserDao->find($userId);
            $this->responseOk($this->filter($user->toArray(), $this->filterKeys));
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function me_get() {
        $accessToken = $this->get('accessToken');

        try {
            $userId = $this->verifyToken($accessToken);

            $user = $this->UserDao->find($userId);
            $this->responseOk($this->filter($user->toArray(), $this->filterKeys));
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function search_get() {
        $type = $this->get('type');
        $keyword = $this->get('keyword');
        if ($keyword == null) {
            $this->responseError("'keyword' is required.", 400);
        }
        if (strlen($keyword) < 3) {
            $this->responseError("'keyword' should be longer than 2.", 400);
        }
        if ($type == null) {
            $type = 'all';
        }
        if ($type != 'name' && $type != 'email' && $type != 'all') {
            $this->responseError("Not supported type: " . $type, 400);
        }

        $users = $this->UserDao->search($type, $keyword);
        $array = array();
        foreach ($users as $user) {
            array_push($array, $this->filter($user->toArray(), $this->filterKeys));
        }
        $this->responseOk($array);
    }

}

?>
