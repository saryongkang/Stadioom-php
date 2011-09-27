<?php
class Home extends CI_Controller {

	function index()
	{
		$data['fbAppId'] = "200987663288876";
        
        $this->load->helper(array('form', 'url'));
        
        
        //form validation
		$this->load->library('form_validation');
        
        $this->form_validation->set_rules('user[fullName]', 'Full Name', 'trim|required|min_length[5]|max_length[20]|xss_clean');
		$this->form_validation->set_rules('user[password]', 'Password', 'trim|required|min_length[5]|md5');
		$this->form_validation->set_rules('user[email]', 'E-mail', 'trim|required|valid_email');
    
        
        
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
			//redirect('/signup/send', 'refresh');
		}
	}
    
    function checkEmail() {
        $this->load->model('dao/UserDao');
        $email = $this->input->post('email');

        if($this->UserDao->checkDuplicate($email)) {
            echo 'true';
        } else {
            echo 'false';
        }
    }
}
?>