<?php
class Main extends FBAuth_Controller {

	function index()
	{
        $data['session'] = $this->session->userdata;
        
        
        
        $this->load->model('dao/UserDao');
        $data['lastMatch'] = $this->UserDao->getLatestMatches($data['session']['user']['id'], 1);
        
        $this->load->model('dao/SportDao');
        $data['sports'] = $this->SportDao->getAll();
        
        $this->load->model('dao/BrandDao');
        $data['brands'] = $this->BrandDao->getAll();
        
        //BUILDING THE TEMPLATE
        $this->template->add_css('main');
        
        $this->template->add_js('bootstrap/bootstrap-dropdown');
        $this->template->add_js('bootstrap/bootstrap-scrollspy');
        
        $this->template->set_content('mainView', $data);
        $this->template->build('main');
    }
}
?>
