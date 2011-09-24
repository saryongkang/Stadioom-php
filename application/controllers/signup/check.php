<?php

class Check extends CI_Controller {

	function index()
	{
        echo "Check";
	}
    
    function duplicateEmail() {
        $this->load->model('dao/UserDao');
        $email = $this->input->post('email');

        if($this->UserDao->isDuplicateEmail($email)) {
            echo 'true';
        } else {
            echo 'false';
        }
    }
    
}
?>
