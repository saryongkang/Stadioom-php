<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class I18n extends Stadioom_REST_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('dao/I18nDao');

        force_ssl();
    }

    public function trans_get() {
        try {
            $this->responseOk($this->I18nDao->translate($this->get('id'), $this->get("lang"), $this->get("clientType")));
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function delta_get() {
        try {
            $category = $this->get('category');
            $lang = $this->get('lang');
            $after = $this->get('after');

            return $this->responseOk($this->I18nDao->getDelta($category, $lang, $after));
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function lang_get($id = null) {
        if ($id == null) {
            $this->responseOk(array('data' => $this->I18nDao->getAllSupportedLanguages()));
        } else {
            $name = $this->I18nDao->getLanguageName($id);
            if ($name === null) {
                $name = 'Not supported';
            }
            $this->responseOk($name);
        }
    }
}

?>
