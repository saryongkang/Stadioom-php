<?php
class HomeSignUp extends CI_Controller {

	function index()
	{
		$data['fbAppId'] = "200987663288876";

		$this->load->view('homeSignUpView', $data);
	}
}
?>