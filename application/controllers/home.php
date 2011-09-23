<?php
class Home extends CI_Controller {

	function index()
	{
		$data['fbAppId'] = "200987663288876";
        
        $this->load->helper(array('form', 'url'));
        
        
        //form validation
		$this->load->library('form_validation');
        
        $this->form_validation->set_rules('fullName', 'Full Name', 'trim|required|min_length[5]|max_length[20]|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|md5');
		$this->form_validation->set_rules('email', 'E-mail', 'trim|required|valid_email');
    
        
        
        //$this->template->add_message('success', 'You are using duellsys template library');
        //$this->template->add_message('info', 'Awesome!');
        if ($this->form_validation->run() == FALSE)
		{
            $this->template->add_css('bootstrap.min');
            $this->template->add_css('home');
            $this->template->add_js('signup');

            $this->template->set_content('homeView', $data);
            $this->template->build('home');
		}
		else
		{
            curl('auth/signUp_post');
			$this->load->view('formSuccessView');
		}
	}
    
    function checkEmail() {
        $this->load->model('members_model');
        $email = $this->input->post('email');

        if($this->members_model->checkDuplicate($email)) {
            echo 'true';
        } else {
            echo 'false';
        }
    }
}
?>