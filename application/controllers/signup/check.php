<?php

class Check extends CI_Controller {

	function index()
	{
        echo "Check";
	}
    
    function duplicateEmail() {
        $this->load->model('user');
        $email = $this->input->post('email');

        if($this->members_model->checkDuplicateEmail($email)) {
            echo 'true';
        } else {
            echo 'false';
        }
    }
    
}
?>
