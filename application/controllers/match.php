<?php

class Match extends CI_Controller {
    
    function __construct() {
        parent::__construct();
    }

	function index()
	{
        echo "Unaccessible";
    }
    
    function create(){
        $data['userdata'] = $this->session->userdata;
        
        $this->load->model('dao/SportDao');
        $data['sportsList'] = $this->SportDao->getAll();
        
        
        $this->template->add_css('main');
        $this->template->set_content('createMatchView', $data);
        $this->template->build('main');
    }
}
?>
