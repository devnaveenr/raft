<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicletypes extends MX_Controller {
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
		$data['title'] = 'Vehicle Types';
        

		$params = array();
		$params['select'] = "t1.*"; 
		$params['table'] = 'vehicle_types t1';
		$params['order_by'] = 't1.vehicle_type_id';
		$params['output'] = 'result_object';
		$data['vehicletypes'] = $this->crud_model->getReports($params);

		$data['css_links']='global/css_links';
		$data['header']='global/header';
        $data['js_links']='global/js_links';
		$data['footer']='global/footer';
		$data['sidebar']='global/sidebar';
		$this->load->view('vehicletypes',$data);
	}
	
	public function add_vehicletype()
	{
		$this->is_logged_in();
		$this->form_validation->set_rules('vehicle_type', 'vehicletype Name', 'required',array('required' => 'VEHICLE TYPE NAME IS REQUIRED'));
		if ($this->form_validation->run() == FALSE)
        {
			$data['title'] = 'Add vehicletype';
			$data['css_links']='global/css_links';
			$data['header']='global/header';
	        $data['js_links']='global/js_links';
			$data['footer']='global/footer';
			$data['sidebar']='global/sidebar';
			$this->load->view('add-vehicletype',$data);
		}else{
				$filename = 'vehicle_icon';
				$upload_path = 'assets/backend/images/vehicletypes';
				$allowed_types='gif|jpg|png|PNG';
				$this->load->library('upload');
				$filenames = $this->upload_file($filename,$upload_path,$allowed_types,$edit=false,$edit_image='');
				$data = array(
					'vehicle_type' => $this->input->post('vehicle_type'),
					'vehicle_icon'=>$filenames,
                    'created_date'=>date('Y-m-d H:i:s'),
                    'updated_date'=>date('Y-m-d H:i:s'),
				);
				$id = $this->crud_model->insertItem('vehicle_types',$data);
				if($id){
					$this->session->set_flashdata('message_name', 'vehicletype Added Successfully');
					redirect(base_url('vehicletypes'));
				}
		}
	}
	public function edit_vehicletype($id)
	{
		$this->is_logged_in();
		$this->form_validation->set_rules('vehicletype_name', 'vehicletype Name', 'required',array('required' => 'vehicletype NAME IS REQUIRED'));
		if ($this->form_validation->run() == FALSE)
        {
			$data['title'] = 'Edit vehicletypes';			
			$params = array();
			$params['select'] = "t1.*"; 
			$params['table'] = 'vehicle_types t1';
			$where = "";
			$where = "t1.vehicle_type_id=".$id;
			$params['where'] = $where;
			$params['output'] = 'row_array';
			$data['editdata'] = $this->crud_model->getReports($params);

			$data['css_links']='global/css_links';
			$data['header']='global/header';
	        $data['js_links']='global/js_links';
			$data['footer']='global/footer';
			$data['sidebar']='global/sidebar';
			$this->load->view('edit-vehicletype',$data);
		}else{
			$filename = 'vehicle_icon';
			$upload_path = 'assets/backend/images/vehicletypes';
			$allowed_types='gif|jpg|png|PNG';
			$edit_image = $this->input->post('vehicle_type_edit');
			$this->load->library('upload');
			$filenames = $this->upload_file($filename,$upload_path,$allowed_types,$edit=true,$edit_image);
			$data = array(
					'vehicletype_name' => $this->input->post('vehicletype_name'),
					'vehicle_icon'=>$filenames,
					'updated_date'=>date('Y-m-d H:i:s'),
				);
			$where = "vehicle_type_id=".$id;
			$id = $this->crud_model->updateItem('vehicle_types',$where,$data);
			
				$this->session->set_flashdata('message_name', 'vehicletype Updated Successfully');
					redirect(base_url('vehicletypes'));
		}
	}
	public function delete_vehicletype($id) {
		$where = "vehicle_type_id=".$id;
        $id = $this->crud_model->deleteItems('vehicle_types',$where);
    }
    public function change_vehicletype_status() {
		$data=array('vehicletype_status'=>$this->input->post('status'));
		$where = "vehicle_type_id=".$this->input->post('id');
        $this->crud_model->updateItem('vehicle_types',$where,$data);
        redirect('vehicletypes','refresh');
    }
	function upload_file($filename,$upload_path,$allowed_types,$edit,$edit_image){
		
		if (!empty($_FILES[$filename]['name'])) {
			$new_file_name = time().'_'.$_FILES[$filename]['name'];
			$config['upload_path'] = $upload_path;
			$config['allowed_types'] = $allowed_types;
			$config['file_name'] = $new_file_name;
			$this->upload->initialize($config);
			if($this->upload->do_upload($filename)){
				$filePathImage = $upload_path.'/'.$new_file_name;
				if($filePathImage!=''){
					$filePathImage = $filePathImage;
				}else{
					if($edit==true){
						$filePathImage = NULL;
					}else{
						$filePathImage = $edit_image;
					}
					
				} 
			}
		}else{
			$filePathImage = NULL;
		}
		return $filePathImage;
	}
   
}
