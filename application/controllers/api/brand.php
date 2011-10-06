<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class Brand extends Stadioom_REST_Controller {

    private $filterKeys = array('firstRevision', 'latestRevision', 'updateFlag');

    function __construct() {
        parent::__construct();

        $this->load->model('dao/BrandDao');
        $this->load->model('dao/BrandSportMapDao');

        if (function_exists('force_ssl'))
            remove_ssl();
    }

    // only for testing purpose.
    public function index_post() {
        $accessToken = $this->post('accessToken');
        try {
            $userId = $this->verifyToken($accessToken);

            $brand = new Entities\Brand();
            $brand->setName($this->post('name'));
            $brand->setDescription($this->post('desc'));
            $brand->setPriority($this->post('priority'));
            $brand->setFirstRevision($this->post('firstRevision'));
            $brand->setLatestRevision($this->post('latestRevision'));
            $brand->setUpdateFlag($this->post('updateFlag'));

            $result = $this->BrandDao->add($brand);
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

            $result = $this->BrandDao->remove($id);
            $this->responseOk($result);
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function index_get() {
        $accessToken = $this->get('accessToken');
        try {
            $userId = $this->verifyToken($accessToken);

            $brandId = $this->get('id');
            if ($brandId == null) {

                $allSports = $this->BrandDao->getAll();
                $array = array();
                foreach ($allSports as $brand) {
                    array_push($array, $this->filter($brand->toArray(), $this->filterKeys));
                }
                
                $data = array("data" => $array);
                $this->responseOk($data);
            } else {
                $brand = $this->BrandDao->find($brandId);

                $this->responseOk($this->filter($brand->toArray(), $this->filterKeys));
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
            $allSports = $this->BrandDao->findAfter($after);
            $array = array();
            foreach ($allSports as $brand) {
                array_push($array, $brand->toArray());
            }
            $data = array("data" => $array);
            $this->responseOk($data);
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function sport_get() {
        $accessToken = $this->get('accessToken');

        try {
            $userId = $this->verifyToken($accessToken);

            $brandId = $this->get('brandId');
            $sports = $this->BrandSportMapDao->findSponsoredBy($brandId);
            $array = array();
            foreach ($sports as $sport) {
                array_push($array, $this->filter($sport->toArray(), $this->filterKeys));
            }

            if ($array == null) {
                $this->responseError(new Exception("Not Found.", 404));
            }
            
            $data = array("data" => $array);
            $this->responseOk($data);
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function sport_post() {
        $accessToken = $this->post('accessToken');

        try {
            $userId = $this->verifyToken($accessToken);

            $brandId = $this->post('brandId');
            $sportId = $this->post('sportId');
            $this->BrandSportMapDao->link($brandId, $sportId);
            $this->responseOk();
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function sport_delete() {
        $accessToken = $this->delete('accessToken');

        try {
            $userId = $this->verifyToken($accessToken);

            $brandId = $this->delete('brandId');
            $sportId = $this->delete('sportId');
            $this->BrandSportMapDao->unlink($brandId, $sportId);
            $this->responseOk();
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

}

?>
