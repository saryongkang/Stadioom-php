<?php

class Check extends CI_Controller {

	function index()
	{
        echo "Check";
	}
    
    function duplicateEmail() {
        $user = new Entities\User;
        $email = $this->input->post('email');

        if($user->checkDuplicateEmail($email)) {
            echo 'true';
        } else {
            echo 'false';
        }
    }
    
}
?>
