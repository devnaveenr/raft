<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';
 
class Cities extends REST_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('global/crud_model', 'CrudModel');
    }
    public function list_get()
    {
        header("Access-Control-Allow-Origin: *");
        $params = array();
		$params['select'] = "t1.city_id as value,t1.city_name as label"; 
		$params['table'] = 'cities t1';
		$params['order_by'] = 't1.city_id';
		$params['output'] = 'result_object';
        $response = $this->CrudModel->getReports($params);
		if(!empty($response)){
			$this->data['status'] = true;
			$this->data['message'] = 'Data found!';
			$this->data['result'] = $response;
			$this->data['total_count'] = count($response);
			$this->response($this->data, REST_Controller::HTTP_OK);
		}else{
			$this->data['status'] = false;
			$this->data['message'] = 'Data not found.';
			$this->response($this->data, REST_Controller::HTTP_NOT_FOUND);
		}
    }
   
    
}