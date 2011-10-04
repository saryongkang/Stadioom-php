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
    
        $data['fbUser'] = null;
        
        // FB Stuff
          
        $facebook = new $this->facebook(array(
          'appId' => '200987663288876',
          'secret' => '6d3dd0e7aa9dae300920ec05552bddee',
        ));
        
        // Get User ID
        $data['fbUser'] = $facebook->getUser();

        if ($data['fbUser']) {

          try {
            // Proceed knowing you have a logged in user who's authenticated.
            $user_profile = $facebook->api('/me');
          } catch (FacebookApiException $e) {
            error_log($e);
            $data['fbUser'] = null;
          }
        }

        $fbLoginData = array(
            'scope' => 'email,user_checkins,user_likes,user_interests,user_hometown,user_location,user_education_history,user_birthday,user_activities,publish_stream',
            'redirect_uri' => 'https://stadioom.com/fb/session/login/'
        );
        
        // Login or logout url will be needed depending on current user state.
        if ($data['fbUser']) {
            
           $data['logoutUrl'] = $facebook->getLogoutUrl();
        } else {
           $data['loginUrl'] = $facebook->getLoginUrl($fbLoginData);
        }
        
        
        
        
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