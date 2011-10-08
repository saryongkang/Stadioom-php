<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class Brand extends Stadioom_REST_Controller {

    private $filterKeys = array('firstRevision', 'latestRevision', 'updateFlag', 'sports');

    function __construct() {
        parent::__construct();

//        $this->load->model('dao/BrandDao');
        $this->load->model('dao/BrandSportDao');

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
            $brand->setFirstRevision($this->post('firstRevision'));
            $brand->setLatestRevision($this->post('latestRevision'));
            $brand->setUpdateFlag($this->post('updateFlag'));

            $result = $this->BrandSportDao->addBrand($brand);
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

            $result = $this->BrandSportDao->removeBrand($id);
            $this->responseOk($result);
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function index_get() {
        try {
            $brandId = $this->get('id');
            if ($brandId == null) {

                $allSports = $this->BrandSportDao->getAllBrands();
                $array = array();
                foreach ($allSports as $brand) {
                    array_push($array, $this->filter($brand->toArray(), $this->filterKeys));
                }
                
                $data = array("data" => $array);
                $this->responseOk($data);
            } else {
                $brand = $this->BrandSportDao->getBrand($brandId);

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
            $allSports = $this->BrandSportDao->findBrandsAfter($after);
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

    public function sports_get() {
        $accessToken = $this->get('accessToken');

        try {
//            if ($this->input->ip_address() != '127.0.0.1') {
//                $userId = $this->verifyToken($accessToken);
//            }

            $brandId = $this->get('id');
            $brand = $this->BrandSportDao->getBrand($brandId);
            if ($brand == null) {
                throw new Exception("Brand Not Found", 404);
            }
            
            $sports = $brand->getSports();
            
            $array = array();
            foreach ($sports as $sport) {
                array_push($array, $this->filter($sport->toArray(), $this->filterKeys));
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
            $this->BrandSportDao->link($brandId, $sportIds);
            $this->responseOk();
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

}

?>
