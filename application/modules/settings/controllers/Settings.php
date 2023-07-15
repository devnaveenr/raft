<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MX_Controller {
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
	public function index($id)
	{
		$this->is_logged_in();
		$this->form_validation->set_rules('site_name', 'Site Name', 'required',array('required' => 'SITE NAME IS REQUIRED'));
		
	
		if ($this->form_validation->run() == FALSE)
        {
			$data['title'] = 'Edit Settings';		
			$params = array();
			$params['select'] = "t1.*"; 
			$params['table'] = 'settings t1';
			$where = "";
			$where = "t1.settings_id=".$id;
			$params['where'] = $where;
			$params['output'] = 'row_array';
			$data['editdata'] = $this->crud_model->getReports($params);

			$data['css_links']='global/css_links';
			$data['header']='global/header';
	        $data['js_links']='global/js_links';
			$data['footer']='global/footer';
			$data['sidebar']='global/sidebar';
			$this->load->view('settings',$data);
		}else{
			if (!empty($_FILES['logo']['name'])) {
				$new_file_name = time().'_'.$_FILES['logo']['name'];
			   $config['upload_path'] = 'assets/backend/images';
			   $config['allowed_types'] = 'gif|jpg|png';
			   $config['file_name'] = $new_file_name;
			   $this->load->library('upload', $config);
			
			   if($this->upload->do_upload('logo')){
				   $filePath = 'assets/backend/images/'.$new_file_name;
				   
			   }else{
				print_r($this->upload->display_errors());
			   }
			   
		   }else{
			   $filePath = $this->input->post('logo_image_edit');
		   }
				$data = array(
					'site_name' => $this->input->post('site_name'),
					'contact_no' => $this->input->post('contact_no'),
					'company_email' => $this->input->post('company_email'),
					'address' => $this->input->post('address'),
					'modified_date'=>date('Y-m-d H:i:s'),
					'logo' => $filePath
				);
				$where = "settings_id=".$id;
				$id = $this->crud_model->updateItem('settings',$where,$data);
				if($id){
					$this->session->set_flashdata('message_name', 'Settings Updated Successfully');
					redirect(base_url('settings/index/'.$id));
				}
		}
		
	}
	
   
}
