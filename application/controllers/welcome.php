<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
         
//	public function index()
//	{
//		$this->load->view('welcome_message');
//	}
    
    function __construct(){
        parent::__construct();
    }

    function index() {
        $data = array('some_variable' => 'some_data');

        $this->template->add_message('success', 'You are using duellsys template library');
        $this->template->add_message('info', 'Awesome!');

        $this->template->set_content('example', $data);
        $this->template->build();
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */