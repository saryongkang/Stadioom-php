<?php

class Match extends FBAuth_Controller {
    
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
        
        $this->template->add_css('tdfriendselector');
        $this->template->add_js('tdfriendselector');
        $this->template->add_js('jquery.iphone-switch');
        $this->template->add_js('bootstrap/bootstrap-modal');
        
        $this->template->add_css('match');
        
        $this->template->add_css('main');
        $this->template->set_content('createMatchView', $data);
        $this->template->build('main');
    }
    
    function view(){
        $this->template->add_js('jquery.timeago');
    }
}
?>
