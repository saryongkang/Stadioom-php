<?php
class Main extends CI_Controller {

	function index()
	{
        $data['userdata'] = $this->session->userdata;
        
        //$this->template->add_css('home');
        
        
        //BUILDING THE TEMPLATE
        $this->template->add_css('main');
        
        $this->template->set_content('mainView', $data);
        $this->template->build('main');
    }
}
?>
