<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class Sport extends Stadioom_REST_Controller {

    private $filterKeys4Sport = array('firstRevision', 'latestRevision', 'updateFlag', 'brands');
    private $filterKeys4Brand = array('firstRevision', 'latestRevision', 'updateFlag', 'sports');

    function __construct() {
        parent::__construct();

        $this->load->model('dao/BrandSportDao');

        force_ssl();
//        if (function_exists('force_ssl'))
//            remove_ssl();
    }

    // only for testing purpose.
    public function index_post() {
        $accessToken = $this->post('accessToken');
        try {
            $userId = $this->verifyToken($accessToken);

            $sport = new Entities\Sport();
            $sport->setStringId($this->post('stringId'));
            $sport->setName($this->post('name'));
            $sport->setDescription($this->post('desc'));
            $sport->setPriority($this->post('priority'));
            $sport->setFirstRevision($this->post('firstRevision'));
            $sport->setLatestRevision($this->post('latestRevision'));
            $sport->setUpdateFlag($this->post('updateFlag'));

            $result = $this->BrandSportDao->addSport($sport);
            $this->responseOk($result);
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    // only for testing purpose.
    public function index_delete() {
        $accessToken = $this->delete('accessToken');
        try {
            $userId = $this->verifyToken($accessToken);

            $id = $this->delete('id');

            $result = $this->BrandSportDao->removeSport($id);
            $this->responseOk($result);
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function index_get() {
        try {
            $sportId = $this->get('id');
            if ($sportId == null) {

                // TODO returns list ordered by priority.
                $allSports = $this->BrandSportDao->getAllSports();
                $array = array();
                foreach ($allSports as $sport) {
                    array_push($array, $this->filter($sport->toArray(), $this->filterKeys4Sport));
                }
                $data = array("data" => $array);
                $this->responseOk($data);
            } else {
                $sport = $this->BrandSportDao->getSport($sportId);

                $this->responseOk($this->filter($sport->toArray(), $this->$filterKeys4Sport));
            }
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function delta_get() {
        $accessToken = $this->get('accessToken');
        try {
            $userId = $this->verifyToken($accessToken);
            $after = $this->get('after');
            $allSports = $this->BrandSportDao->findSportsAfter($after);
            $array = array();
            foreach ($allSports as $sport) {
                array_push($array, $sport->toArray());
            }
            $data = array("data" => $array);
            $this->responseOk($data);
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function brands_get() {
        $accessToken = $this->get('accessToken');

        try {
//            if ($this->input->ip_address() != '127.0.0.1') {
//                $userId = $this->verifyToken($accessToken);
//            }

            $sportId = $this->get('id');
            $brands = $this->BrandSportDao->findAllSponsorsOf($sportId);
            
            $array = array();
            foreach ($brands as $brand) {
                array_push($array, $this->filter($brand->toArray(), $this->filterKeys4Brand));
            }
            
            $data = array("data" => $array);
            $this->responseOk($data);
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

}

?>
