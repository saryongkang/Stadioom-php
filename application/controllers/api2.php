<?php
require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class Auth extends Stadioom_REST_Controller {

    function __construct() {
        parent::__construct();

        force_ssl();
    }
    
//    public function index_get($category, $level1 = null, $level2 = null, $level3 = null) {
//        switch ($category) {
//            case 'user':
//            default:
//                throw new Ex
//                break;
//        }
//    }
}
?>
