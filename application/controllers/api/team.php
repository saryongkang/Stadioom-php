<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class Team extends Stadioom_REST_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('dao/TeamDao');

        force_ssl();
//        if (function_exists('force_ssl'))
//            remove_ssl();
    }

    // only for testing purpose.
    public function add_get() {
        try {
            $team = new Entities\Team();
            $team->setName($this->get('name'));
            $team->setDescription($this->get('desc'));

            $result = $this->TeamDao->add($team);
            $this->responseOk($result);
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function get_get() {
        try {
            $teamId = $this->get('id');
            if ($teamId == null) {

                $allSports = $this->TeamDao->getAll();
                $array = array();
                foreach ($allSports as $brand) {
                    array_push($array, $brand->toArray());
                }

                $data = array("data" => $array);
                $this->responseOk($data);
            } else {
                // TODO (deprecated)
                $brand = $this->TeamDao->find($teamId);

                $this->responseOk($brand->toArray());
            }
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }
}

?>
