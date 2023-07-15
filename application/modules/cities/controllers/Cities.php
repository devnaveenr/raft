<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cities extends MX_Controller {
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
		$data['title'] = 'Cities';

		$params = array();
		$params['select'] = "t1.*"; 
		$params['table'] = 'cities t1';
		$params['order_by'] = 't1.city_id';
		$params['output'] = 'result_object';
		$data['cities'] = $this->crud_model->getReports($params);

		$data['css_links']='global/css_links';
		$data['header']='global/header';
        $data['js_links']='global/js_links';
		$data['footer']='global/footer';
		$data['sidebar']='global/sidebar';
		$this->load->view('cities',$data);
	}
	
	public function add_city()
	{
		$this->is_logged_in();
		$this->form_validation->set_rules('city_name', 'City Name', 'required',array('required' => 'CITY NAME IS REQUIRED'));
		if ($this->form_validation->run() == FALSE)
        {
			$data['title'] = 'Add City';
			$data['css_links']='global/css_links';
			$data['header']='global/header';
	        $data['js_links']='global/js_links';
			$data['footer']='global/footer';
			$data['sidebar']='global/sidebar';
			$this->load->view('add-city',$data);
		}else{
				$data = array(
					'city_name' => $this->input->post('city_name'),	
					'city_priority' => $this->input->post('city_priority'),
                    'created_date'=>date('Y-m-d H:i:s'),
                    'updated_date'=>date('Y-m-d H:i:s'),
				);
				$id = $this->crud_model->insertItem('cities',$data);
				if($id){
					$this->session->set_flashdata('message_name', 'City Added Successfully');
					redirect(base_url('cities'));
				}
		}
	}
	public function edit_city($id)
	{
		$this->is_logged_in();
		$this->form_validation->set_rules('city_name', 'City Name', 'required',array('required' => 'CITY NAME IS REQUIRED'));
		if ($this->form_validation->run() == FALSE)
        {
			$data['title'] = 'Edit CIties';			
			$params = array();
			$params['select'] = "t1.*"; 
			$params['table'] = 'cities t1';
			$where = "";
			$where = "t1.city_id=".$id;
			$params['where'] = $where;
			$params['output'] = 'row_array';
			$data['editdata'] = $this->crud_model->getReports($params);

			$data['css_links']='global/css_links';
			$data['header']='global/header';
	        $data['js_links']='global/js_links';
			$data['footer']='global/footer';
			$data['sidebar']='global/sidebar';
			$this->load->view('edit-city',$data);
		}else{
			$data = array(
					'city_name' => $this->input->post('city_name'),
					'city_priority' => $this->input->post('city_priority'),
					'updated_date'=>date('Y-m-d H:i:s'),
				);
			$where = "city_id=".$id;
			$id = $this->crud_model->updateItem('cities',$where,$data);
			
				$this->session->set_flashdata('message_name', 'City Updated Successfully');
					redirect(base_url('cities'));
		}
	}
	public function delete_city($id) {
		$where = "city_id=".$id;
        $id = $this->crud_model->deleteItems('cities',$where);
    }
    public function change_city_status() {
		$data=array('city_status'=>$this->input->post('status'));
		$where = "city_id=".$this->input->post('id');
        $this->crud_model->updateItem('cities',$where,$data);
        redirect('cities','refresh');
    }
   
}
