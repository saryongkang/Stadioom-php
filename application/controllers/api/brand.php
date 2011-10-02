<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class Brand extends Stadioom_REST_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('dao/BrandDao');
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
            $brand->setWeight($this->post('weight'));
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
                
                // TODO returns list ordered by weight.
                $allSports = $this->BrandDao->getAll();
                $array = array();
                foreach ($allSports as $brand) {
                    array_push($array, $brand->toArray());
                }
                $this->responseOk($array);
            } else {
                $brand = $this->BrandDao->find($brandId);
                
                $brand_array = $brand->toArray();
                
                $this->responseOk($brand->toArray());
            }
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function delta_get($after) {
        $accessToken = $this->get('accessToken');
        $after = $this->get('after');
        // TODO returns list of brands modified after the specified revision(exclusive).
        try {
            $userId = $this->verifyToken($accessToken);

            throw new Exception("Not Implemented.", 501);
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function sports_get() {
        $accessToken = $this->get('accessToken');
        $sportId = $this->get('id');

        try {
            $userId = $this->verifyToken($accessToken);

            throw new Exception("Not Implemented.", 501);
            
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

}

?>
