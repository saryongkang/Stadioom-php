<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class Sport extends Stadioom_REST_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('dao/SportDao');
        $this->load->model('dao/BrandSportMapDao');
        
        if (function_exists('force_ssl'))
            remove_ssl();
    }

    // only for testing purpose.
    public function index_post() {
        $accessToken = $this->post('accessToken');
        try {
            $userId = $this->verifyToken($accessToken);

            $sport = new Entities\Sport();
            $sport->setName($this->post('name'));
            $sport->setDescription($this->post('desc'));
            $sport->setWeight($this->post('weight'));
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
        $accessToken = $this->get('accessToken');
        try {
            $userId = $this->verifyToken($accessToken);

            $sportId = $this->get('id');
            if ($sportId == null) {
                
                // TODO returns list ordered by weight.
                $allSports = $this->SportDao->getAll();
                $array = array();
                foreach ($allSports as $sport) {
                    array_push($array, $sport->toArray());
                }
                $this->responseOk($array);
            } else {
                $sport = $this->SportDao->find($sportId);
                
//                $arrayed = (array) $sport;
//                $object_vars = get_object_vars($sport);
                $sport_array = $sport->toArray();
                
                $this->responseOk($sport->toArray());
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


    public function brand_get() {
        $accessToken = $this->get('accessToken');

        try {
            $userId = $this->verifyToken($accessToken);

            $sportId = $this->get('sportId');
            $brands = $this->BrandSportMapDao->findSponsorsOf($sportId);
            $array = array();
            foreach ($brands as $brand) {
                array_push($array, $brand->toArray());
            }
            if ($array == null) {
                $this->responseError(new Exception("Not Found.", 404));
            }
            $this->responseOk($array);
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
