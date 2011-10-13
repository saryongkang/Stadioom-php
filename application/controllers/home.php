<?php
class Home extends CI_Controller {

	function index()
	{
        
        $this->load->helper(array('form', 'url'));
        
        
        //form validation
//		$this->load->library('form_validation');
//        
//        $this->form_validation->set_rules('user[fullName]', 'Full Name', 'trim|required|min_length[5]|max_length[20]|xss_clean');
//		$this->form_validation->set_rules('user[password]', 'Password', 'trim|required|min_length[5]|md5');
//		$this->form_validation->set_rules('user[email]', 'E-mail', 'trim|required|valid_email');
        
        // FB Stuff
          
        $this->load->library('fb_connect');
        
        $extraScope = ',user_interests,user_activities,user_hometown,user_hometown,user_location';

        $fbLoginData = array(
            //'scope' => 'email,user_likes,user_interests,user_hometown,user_location,user_birthday,user_activities,publish_stream,offline_access',
            'scope' => 'email,user_birthday,publish_stream'.$extraScope ,
            'redirect_uri' => $this->config->item('base_ssl_url').'/'
        );
        
        $data['loginUrl'] = $this->fb_connect->getLoginUrl($fbLoginData);   
        
        //$this->template->add_message('success', 'You are using duellsys template library');
        //$this->template->add_message('info', 'Awesome!');
        
//        if ($this->form_validation->run() == FALSE)
//		{
            $this->template->add_css('home');
            //$this->template->add_js('signup');

            $this->template->set_content('homeView', $data);
            $this->template->build('home');
//		}
//		else
//		{
//			//redirect('/signup/send', 'refresh');
//		}
        
        
        
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