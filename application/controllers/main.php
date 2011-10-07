<?php
class Main extends FBAuth_Controller {

	function index()
	{
        $data['userdata'] = $this->session->userdata;
        
        
        //BUILDING THE TEMPLATE
        $this->template->add_css('main');
        
        $this->template->add_js('bootstrap/bootstrap-dropdown');
        $this->template->add_js('bootstrap/bootstrap-scrollspy');
        
        $this->template->set_content('mainView', $data);
        $this->template->build('main');
    }
}
?>
