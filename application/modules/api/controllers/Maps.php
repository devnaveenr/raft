<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';
 
class Maps extends REST_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->library('Authorization_Token');
        $headers = $this->input->request_headers();
		$this->global_user_id = '';
		$this->global_user_type = '';
        if (!isset($headers['Authorization'])) {
			$response = array(
				"status" => false,
				"message" => 'Authorization failed',
			);
			$this->response($response, REST_Controller::HTTP_UNAUTHORIZED);
		}
		$is_valid_token = $this->authorization_token->validateToken($headers['Authorization']);
		if (empty($is_valid_token) or $is_valid_token['status'] === FALSE) {
			$response = array(
				"status" => true,
				"message" => 'Invalid Access to this page',
				"result" => array()
			);
			$this->response($response, REST_Controller::HTTP_UNAUTHORIZED);
		} else {
			$this->global_user_id  = $is_valid_token['data']->user_id;
			$this->global_user_type  = $is_valid_token['data']->user_type;
		}

        $this->load->model('user_model', 'UserModel');
        $this->load->model('global/crud_model', 'CrudModel');
        
    }
    public function kmprice_post(){
        header("Access-Control-Allow-Origin: *");
        $_POST = $this->security->xss_clean($_POST);
        $user_id= $this->post('user_id');
        $km= $this->post('km');

        $params = array();
		$params['select'] = "t1.*"; 
		$params['table'] = 'vehicle_types t1';
		// $where = "";
		// $where = "t1.user_type=2";
		// $params['where'] = $where;
		$params['order_by'] = 't1.vehicle_type_id ';
		$params['output'] = 'result_array';
		$vehicle_types= $this->CrudModel->getReports($params);
        $all_vehicles = array();
        $i=0;
        foreach($vehicle_types as $vehicle_type){
            $allvehicles[$i]['vehicle_type_id']=$vehicle_type['vehicle_type_id'];
            $allvehicles[$i]['vehicle_type']=$vehicle_type['vehicle_type'];
            $price = $vehicle_type['priceperkm']*$km;
            $allvehicles[$i]['price']=number_format($price, 2);
            $allvehicles[$i]['kms']=$km;
            $i++;
        }  
        if(!empty($allvehicles)){
			$this->data['status'] = true;
			$this->data['message'] = 'Data found!';
			$this->data['result'] = $allvehicles;
			$this->response($this->data, REST_Controller::HTTP_OK);
		}else{
			$this->data['status'] = false;
			$this->data['message'] = 'Data not found.';
			$this->response($this->data, REST_Controller::HTTP_NOT_FOUND);
		}      
    }
	
   
}