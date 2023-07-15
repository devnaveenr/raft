<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MX_Controller {
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
		$this->is_logged_in();
		$data['title'] = 'Dashboard';
		$params = array();
		$params['select'] = "t1.*"; 
		$params['table'] = 'users t1';
		$where = "";
		$where = "t1.user_status=1 and t1.user_type=1";
		$params['where'] = $where;
		$params['output'] = 'count';
		$data['users_count'] = $this->crud_model->getReports($params);

		$params = array();
		$params['select'] = "t1.*"; 
		$params['table'] = 'users t1';
		$where = "";
		$where = "t1.user_status=1 and t1.user_type=2";
		$params['where'] = $where;
		$params['output'] = 'count';
		$data['drivers_count'] = $this->crud_model->getReports($params);

		$data['css_links']='global/css_links';
		$data['header']='global/header';
        $data['js_links']='global/js_links';
		$data['footer']='global/footer';
		$data['sidebar']='global/sidebar';
		$this->load->view('dashboard',$data);
	}
	
}
