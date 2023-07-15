<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MX_Controller {
	function __construct() {
        parent::__construct();
        $this->load->model("login_model");
		$this->load->model("global/crud_model");
    }
    private function is_logged_in()
    {
        if($this->session->userdata('UserId')==''){
        redirect(base_url('admin/login'));
        }
    }
	public function index()
	{
		$data["msg"]="";
		$data['title'] = 'Login';

		if($this->input->post())
		{
			$user = $this->login_model->login($this->input->post());

			if(!empty($user))
			{
				$data = array(
                             'UserId' => $user['admin_user_id'],
                             'Email'=>$user['email'],
                             'FirstName'=>$user['firstname'],
                             'LastName'=>$user['lastname']
				);
				$this->session->set_userdata($data);
				redirect(base_url('admin/dashboard'));
				
			}
			else{
				$data["error_message"]="INVALID LOGIN DETAILS";
			}
		}
		$this->load->view('login',$data);
	}

	public function forget_password()
	{
		$data["msg"]="";
		$data['title'] = 'Forget Password';
		$this->load->view('admin/forget-password',$data);
	}
	public function dashboard()
	{
		$this->is_logged_in();
		$data['title'] = 'Dashboard';
		//$data['courses_count'] = $this->login_model->get_courses_count();
		//$data['topics_count'] = $this->login_model->get_topics_count();
		$data['css_links']='global/css_links';
		$data['header']='global/header';
        $data['js_links']='global/js_links';
		$data['footer']='global/footer';
		$data['sidebar']='global/sidebar';
		$this->load->view('dashboard',$data);
	}
	public function logout()
    {
      $this->session->unset_userdata('UserId');
	  $this->session->unset_userdata('UserName');
	  $this->session->unset_userdata('Email');	  
      redirect(base_url('admin/login'));
    }
}
