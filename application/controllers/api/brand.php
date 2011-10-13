<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class Brand extends Stadioom_REST_Controller {

    private $filterKeys4Brand = array('firstRevision', 'latestRevision', 'updateFlag', 'sports');
    private $filterKeys4Sport = array('firstRevision', 'latestRevision', 'updateFlag', 'brands');

    function __construct() {
        parent::__construct();

        $this->load->model('dao/BrandDao');

        force_ssl();
//        if (function_exists('force_ssl'))
//            remove_ssl();
    }

    // only for testing purpose.
    public function index_post() {
        $accessToken = $this->post('accessToken');
        try {
            $userId = $this->verifyToken($accessToken);

            $brand = new Entities\Brand();
            $brand->setStringId($this->post('stringId'));
            $brand->setName($this->post('name'));
            $brand->setDescription($this->post('desc'));
            $brand->setPriority($this->post('priority'));
            $brand->setUrl($this->post('url'));
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
        try {
            $brandId = $this->get('id');
            if ($brandId == null) {

                $allSports = $this->BrandDao->getAllOrderedByPriority();
                $array = array();
                foreach ($allSports as $brand) {
                    array_push($array, $this->filter($brand->toArray(), $this->filterKeys4Brand));
                }

                $data = array("data" => $array);
                $this->responseOk($data);
            } else {
                // TODO (deprecated)
                $brand = $this->BrandDao->find($brandId);

                $this->responseOk($this->filter($brand->toArray(), $this->filterKeys4Brand));
            }
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function getBrand_get($id) {
        try {
            $brand = $this->BrandDao->find($id);

            $this->responseOk($this->filter($brand->toArray(), $this->filterKeys4Brand));
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }
    public function getSports_get($id) {
        try {
            $brand = $this->BrandDao->find($id);
            if ($brand == null) {
                throw new Exception("Brand Not Found", 404);
            }

            $sports = $brand->getSports();

            $array = array();
            foreach ($sports as $sport) {
                array_push($array, $this->filter($sport->toArray(), $this->filterKeys4Sport));
            }

            $data = array("data" => $array);
            $this->responseOk($data);
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

    // TODO (deprecated)
    public function sports_get() {
        try {
            $brandId = $this->get('id');
            $brand = $this->BrandDao->find($brandId);
            if ($brand == null) {
                throw new Exception("Brand Not Found", 404);
            }

            $sports = $brand->getSports();

            $array = array();
            foreach ($sports as $sport) {
                array_push($array, $this->filter($sport->toArray(), $this->filterKeys4Sport));
            }

            $data = array("data" => $array);
            $this->responseOk($data);
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function sports_post() {
        $accessToken = $this->post('accessToken');

        try {
            $userId = $this->verifyToken($accessToken);

            $brandId = $this->post('brandId');
            $sportIds = $this->post('sportIds');
            $this->BrandDao->setSponsoredSports($brandId, $sportIds);
            $this->responseOk();
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

}

?>
