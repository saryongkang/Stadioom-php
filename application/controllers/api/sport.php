<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class Sport extends Stadioom_REST_Controller {

    private $filterKeys4Sport = array('firstRevision', 'latestRevision', 'updateFlag', 'brands');
    private $filterKeys4Brand = array('firstRevision', 'latestRevision', 'updateFlag', 'sports');

    function __construct() {
        parent::__construct();

        $this->load->model('dao/SportDao');

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

            $result = $this->SportDao->add($sport);
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

            $result = $this->SportDao->remove($id);
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
                $allSports = $this->SportDao->getAllOrderedByPriority();
                
                // REMIND (Wegra) temporal code for old iPhone application.
                $this->load->library('user_agent');
                if (!$this->agent->is_browser()) {
                    $filteredSports = array();
                    foreach($allSports as $sport) {
                        $stringId = $sport->getStringId();
                        if ($stringId == "basketball"
                                || $stringId == "soccer"
                                || $stringId == "tennis") {
                            $filteredSports[] = $sport;
                        }
                    }
                    $allSports = $filteredSports;
                }
                // END of temporal code.
                
                $array = array();
                foreach ($allSports as $sport) {
                    array_push($array, $this->filter($sport->toArray(), $this->filterKeys4Sport));
                }
                $data = array("data" => $array);
                $this->responseOk($data);
            } else {
                // TODO (deprecated)
                $sport = $this->SportDao->find($sportId);

                $this->responseOk($this->filter($sport->toArray(), $this->filterKeys4Sport));
            }
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function getSport_get($id) {
        try {
            $sport = $this->SportDao->find($id);
            $this->responseOk($this->filter($sport->toArray(), $this->filterKeys4Sport));
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function delta_get() {
        $accessToken = $this->get('accessToken');
        try {
            $userId = $this->verifyToken($accessToken);
            $after = $this->get('after');
            $allSports = $this->SportDao->findAfter($after);
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

    // TODO (deprecated)
    public function brands_get() {
        try {
            $sportId = $this->get('id');
            $brands = $this->SportDao->findAllSponsorsOf($sportId);

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

    public function getBrands_get($id) {
        try {
            $brands = $this->SportDao->findAllSponsorsOf($id);

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
