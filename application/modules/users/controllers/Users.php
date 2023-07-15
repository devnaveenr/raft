<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MX_Controller {
	function __construct() {
        parent::__construct();
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
		$data['title'] = 'Users';

		$params = array();
		$params['select'] = "t1.*"; 
		$params['table'] = 'users t1';
		$where = "";
		$where = "t1.user_type=1";
		$params['where'] = $where;
		$params['order_by'] = 't1.user_id';
		$params['output'] = 'result_object';
		$data['users'] = $this->crud_model->getReports($params);

		$data['css_links']='global/css_links';
		$data['header']='global/header';
        $data['js_links']='global/js_links';
		$data['footer']='global/footer';
		$data['sidebar']='global/sidebar';
		$this->load->view('users',$data);
		
	}
	public function view_user($id)
	{
		$this->is_logged_in();
		$data['title'] = 'Users';

		$params = array();
		$params['select'] = "t1.*"; 
		$params['table'] = 'users t1';
		$where = "";
		$where = "t1.user_id=".$id;
		$params['where'] = $where;
		$params['order_by'] = 't1.user_id';
		$params['output'] = 'row_array';
		$data['driver'] = $this->crud_model->getReports($params);

		$data['css_links']='global/css_links';
		$data['header']='global/header';
        $data['js_links']='global/js_links';
		$data['footer']='global/footer';
		$data['sidebar']='global/sidebar';
		$this->load->view('view-user',$data);
		
	}
	public function delete_user($id) {
		$where = "user_id=".$id;
        $id = $this->crud_model->deleteItems('users',$where);
    }
	public function change_user_status() {
		$data=array('user_status'=>$this->input->post('status'));
		$where = "user_id=".$this->input->post('id');
        $this->crud_model->updateItem('users',$where,$data);
        redirect('users','refresh');
    }
   
}
