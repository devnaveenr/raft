<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';
 
class Razorpaydetails extends REST_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('global/crud_model', 'CrudModel');
    }
    public function list_get()
    {
        header("Access-Control-Allow-Origin: *");
        $params = array();
		$params['select'] = "t1.razorpay_id,t1.razor_key,t1.razor_secret_key"; 
		$params['table'] = 'razorpay_details t1';
		$params['output'] = 'row_array';
        $response = $this->CrudModel->getReports($params);
		if(!empty($response)){
			$this->data['status'] = true;
			$this->data['message'] = 'Data found!';
			$this->data['result'] = $response;
			$this->response($this->data, REST_Controller::HTTP_OK);
		}else{
			$this->data['status'] = false;
			$this->data['message'] = 'Data not found.';
			$this->response($this->data, REST_Controller::HTTP_NOT_FOUND);
		}
    }
   
    
}