<?php
class Home extends CI_Controller {

	function index()
	{
		$data['fbAppId'] = "200987663288876";
                //$this->template->add_message('success', 'You are using duellsys template library');
                //$this->template->add_message('info', 'Awesome!');
                $this->template->add_css('bootstrap.min');
                $this->template->add_css('home');

                $this->template->set_content('homeView', $data);
                $this->template->build('home');
	}
}
?>