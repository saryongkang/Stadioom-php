<?php

class Send extends CI_Controller {

	function index()
	{
		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');

		if ($this->form_validation->run() == FALSE)
		{
            $this->template->add_css('bootstrap.min');
            $this->template->add_css('home');

            $this->template->set_content('homeView', $data);
            $this->template->build('home');
		}
		else
		{
			$this->load->view('formSuccessView');
		}
	}
}
?>


