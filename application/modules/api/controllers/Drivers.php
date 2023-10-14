<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';
 
class Drivers extends REST_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->library('Authorization_Token');
        $headers = $this->input->request_headers();
		$this->global_user_id = '';
		$this->global_user_type = '';
        if (!isset($headers['Authorization'])) {
            //ok testing
			$response = array(
				"status" => false,
				"message" => 'Authorization failed',
			);
			$this->response($response, REST_Controller::HTTP_UNAUTHORIZED);
		} 
		//testing hello one
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

        // $this->client_request = new stdClass();
        // $this->client_request = $this->security->xss_clean($_POST);
        //print_r($this->client_request);
       // $this->client_request = json_decode(json_encode($this->client_request), true);
        
    }
    public function driver_details_post(){
    	header("Access-Control-Allow-Origin: *");
        $_POST = $this->security->xss_clean($_POST);
        $user_id=$this->post('user_id');
        $params = array();
		$params['select'] = "t1.*,t2.*"; 
		$params['table'] = 'users t1';
		$join = array();
		$join[0]['table'] = 'driver t2';
		$join[0]['type'] = 'left';
		$join[0]['condition'] = 't2.user_id =t1.user_id ';
		$params['join'] = $join;
		$where = "";
		$where = "t1.user_id=".$user_id;
		$params['where'] = $where;
		$params['output'] = 'row_array';
		$driver_details = $this->CrudModel->getReports($params);
        if (!empty($driver_details) AND $driver_details != FALSE)
        { 
        	$driver_details_array = array(); 
        	$driver_details_array['name']= $driver_details['name'];  
        	$driver_details_array['user_id']= $driver_details['user_id'];
            $driver_details_array['email']= $driver_details['email'];
        	$driver_details_array['driver_profile_pic']= base_url().$driver_details['driver_profile_pic'];           
            $message = [
                'status' => true,
                'driver_details' => $driver_details_array
            ];
            $this->response($message, REST_Controller::HTTP_OK);  
           
        }else{
            $message = [
                'status' => false,
                'message' => "User Not Found"
            ];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

    }
    public function updateuser_post()
    {

        // $response = array('status' => false, 'message' => '');
        // $user_input = $this->client_request;
        // //print_r($user_input);
        // extract($user_input);

        // echo $user_id;

        // exit;
        header("Access-Control-Allow-Origin: *");
        $_POST = $this->security->xss_clean($_POST);
        $user_id= $this->post('user_id');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('owner_number', 'Owner Number', 'trim|required');
        $this->form_validation->set_rules('driver_number', 'Driver Number', 'trim|required');
        $this->form_validation->set_rules('city', 'City', 'trim|required');
        $this->form_validation->set_rules('vehicle_type', 'Vehicle Type', 'trim|required');
        $this->form_validation->set_rules('driving_licence_number', 'Driving Licence Number', 'trim|required');
        $this->form_validation->set_rules('vehicle_number', 'Vehicle Number', 'trim|required');

        if ($this->form_validation->run() == FALSE)
        {
            // Form Validation Errors
            $message = array(
                'status' => false,
                'error' => $this->form_validation->error_array(),
                'message' => validation_errors()
            );
           
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
        else
        {
            $data = array(
                'name' => $this->post('name'),
                'email' => $this->post('email'),
            );
            $where = 'user_id='.$user_id;
            $user = $this->CrudModel->updateItem('users',$where,$data);

            $data1 = array(
                'owner_number' => $this->post('owner_number'),
                'driver_number' => $this->post('driver_number'),
                'driver_name' => $this->post('name'),
                'city' => $this->post('city'),
                'vehicle_type' => $this->post('vehicle_type'),
                'driving_licence_number' => $this->post('driving_licence_number'),
                'vehicle_number'=>$this->post('vehicle_number')
            );
            $where1 = 'user_id='.$user_id;
           $user1 = $this->CrudModel->updateItem('driver',$where1,$data1);
            if (!empty($user) AND $user != FALSE AND !empty($user1) AND $user1 != FALSE)
            {                
                $message = [
                    'status' => true,
                    'message' => "User Updated"
                ];
                $this->response($message, REST_Controller::HTTP_OK);  
               
            }else{
                $message = [
                    'status' => false,
                    'message' => "User Not Updated"
                ];
                $this->response($message, REST_Controller::HTTP_OK);  
            }
        }
    }
    public function aadhar_post()
    {
        header("Access-Control-Allow-Origin: *");
        $_POST = $this->security->xss_clean($_POST);
        $user_id=$this->post('user_id');

        $this->load->library('upload');
        if (!empty($_FILES['aadharfront']['name'])) {
            $ext1 = pathinfo($_FILES['aadharfront']['name'], PATHINFO_EXTENSION);
            $new_file_name1 = "aadharfront".$user_id."_".time().".".$ext1;
           $config1['upload_path'] = 'assets/backend/images/aadhar';
           $config1['allowed_types'] = 'jpg|png|jpeg';
           $config1['file_name'] = $new_file_name1;
           
           $this->upload->initialize($config1);
           if($this->upload->do_upload('aadharfront')){
               $filePathImage1 = 'assets/backend/images/aadhar/'.$new_file_name1;
               if($filePathImage1!=''){
                   $filePathImage1 = $filePathImage1;
               }else{
                   $filePathImage1 = NULL;
               }
               }
        }
        if (!empty($_FILES['aadharback']['name'])) {
            $ext2 = pathinfo($_FILES['aadharback']['name'], PATHINFO_EXTENSION);
            $new_file_name2 = "aadharback".$user_id."_".time().".".$ext2;
           $config2['upload_path'] = 'assets/backend/images/aadhar';
           $config2['allowed_types'] = 'jpg|png|jpeg';
           $config2['file_name'] = $new_file_name2;
           
           $this->upload->initialize($config2);
           if($this->upload->do_upload('aadharback')){
               $filePathImage2 = 'assets/backend/images/aadhar/'.$new_file_name2;
               if($filePathImage2!=''){
                   $filePathImage2 = $filePathImage2;
               }else{
                   $filePathImage2 = NULL;
               }
               }
        }
        $data = array(
            'aadhar_front' => $filePathImage1,
            'aadhar_back' => $filePathImage2,
            'updated_date'=>date('Y-m-d H:i:s')
        );
        $where = 'user_id='.$user_id;
        $user = $this->CrudModel->updateItem('driver',$where,$data);

        if (!empty($user) AND $user != FALSE)
            {                
                $message = [
                    'status' => true,
                    'message' => "Aadhar Uploaded Successfully"
                ];
                $this->response($message, REST_Controller::HTTP_OK);  
               
            }else{
                $message = [
                    'status' => false,
                    'message' => "Aadhar Not Uploaded"
                ];
                $this->response($message, REST_Controller::HTTP_OK);  
            }
    }
    public function rc_post()
    {
        header("Access-Control-Allow-Origin: *");
        $_POST = $this->security->xss_clean($_POST);
        $user_id= $this->post('user_id');

        $this->load->library('upload');
        if (!empty($_FILES['rcfront']['name'])) {
            $ext1 = pathinfo($_FILES['rcfront']['name'], PATHINFO_EXTENSION);
            $new_file_name1 = "rcfront".$user_id."_".time().".".$ext1;
           $config1['upload_path'] = 'assets/backend/images/rc';
           $config1['allowed_types'] = 'jpg|png|jpeg';
           $config1['file_name'] = $new_file_name1;
           
           $this->upload->initialize($config1);
           if($this->upload->do_upload('rcfront')){
               $filePathImage1 = 'assets/backend/images/rc/'.$new_file_name1;
               if($filePathImage1!=''){
                   $filePathImage1 = $filePathImage1;
               }else{
                   $filePathImage1 = NULL;
               }
               }
        }
        if (!empty($_FILES['rcback']['name'])) {
            $ext2 = pathinfo($_FILES['rcback']['name'], PATHINFO_EXTENSION);
            $new_file_name2 = "rcback".$user_id."_".time().".".$ext2;
           $config2['upload_path'] = 'assets/backend/images/rc';
           $config2['allowed_types'] = 'jpg|png|jpeg';
           $config2['file_name'] = $new_file_name2;
           
           $this->upload->initialize($config2);
           if($this->upload->do_upload('rcback')){
               $filePathImage2 = 'assets/backend/images/rc/'.$new_file_name2;
               if($filePathImage2!=''){
                   $filePathImage2 = $filePathImage2;
               }else{
                   $filePathImage2 = NULL;
               }
               }
        }
        $data = array(
            'rc_front' => $filePathImage1,
            'rc_back' => $filePathImage2,
            'updated_date'=>date('Y-m-d H:i:s')
        );
        $where = 'user_id='.$user_id;
        $user = $this->CrudModel->updateItem('driver',$where,$data);

        if (!empty($user) AND $user != FALSE)
            {                
                $message = [
                    'status' => true,
                    'message' => "RC Uploaded Successfully"
                ];
                $this->response($message, REST_Controller::HTTP_OK);  
               
            }else{
                $message = [
                    'status' => false,
                    'message' => "RC Not Uploaded"
                ];
                $this->response($message, REST_Controller::HTTP_OK);  
            }
    }
    public function profilepic_post()
    {
        header("Access-Control-Allow-Origin: *");
        $_POST = $this->security->xss_clean($_POST);
        $user_id= $this->post('user_id');

        $this->load->library('upload');
        if (!empty($_FILES['driver_profile_pic']['name'])) {
            $ext1 = pathinfo($_FILES['driver_profile_pic']['name'], PATHINFO_EXTENSION);
            $new_file_name1 = "driver_profile_pic".$user_id."_".time().".".$ext1;
           $config1['upload_path'] = 'assets/backend/images/profilepic';
           $config1['allowed_types'] = 'jpg|png|jpeg';
           $config1['file_name'] = $new_file_name1;
           
           $this->upload->initialize($config1);
           if($this->upload->do_upload('driver_profile_pic')){
               $filePathImage1 = 'assets/backend/images/profilepic/'.$new_file_name1;
               if($filePathImage1!=''){
                   $filePathImage1 = $filePathImage1;
               }else{
                   $filePathImage1 = NULL;
               }
               }
        }
        
        $data = array(
            'driver_profile_pic' => $filePathImage1,
            'updated_date'=>date('Y-m-d H:i:s'),
            'documents_status'=>0
        );
        $where = 'user_id='.$user_id;
        $user = $this->CrudModel->updateItem('driver',$where,$data);

        if (!empty($user) AND $user != FALSE)
            {                
                $message = [
                    'status' => true,
                    'message' => "Profile Picture Uploaded Successfully"
                ];
                $this->response($message, REST_Controller::HTTP_OK);  
               
            }else{
                $message = [
                    'status' => false,
                    'message' => "Profile Picture Not Uploaded"
                ];
                $this->response($message, REST_Controller::HTTP_OK);  
            }
    }
    public function drivinglicence_post()
    {
        header("Access-Control-Allow-Origin: *");
        $_POST = $this->security->xss_clean($_POST);
        $user_id= $this->post('user_id');

        $this->load->library('upload');
        if (!empty($_FILES['drivinglicencefront']['name'])) {
            $ext1 = pathinfo($_FILES['drivinglicencefront']['name'], PATHINFO_EXTENSION);
            $new_file_name1 = "drivinglicencefront".$user_id."_".time().".".$ext1;
           $config1['upload_path'] = 'assets/backend/images/drivinglicence';
           $config1['allowed_types'] = 'jpg|png|jpeg';
           $config1['file_name'] = $new_file_name1;
           
           $this->upload->initialize($config1);
           if($this->upload->do_upload('drivinglicencefront')){
               $filePathImage1 = 'assets/backend/images/drivinglicence/'.$new_file_name1;
               if($filePathImage1!=''){
                   $filePathImage1 = $filePathImage1;
               }else{
                   $filePathImage1 = NULL;
               }
               }
        }
        if (!empty($_FILES['drivinglicenceback']['name'])) {
            $ext2 = pathinfo($_FILES['drivinglicenceback']['name'], PATHINFO_EXTENSION);
            $new_file_name2 = "drivinglicenceback".$user_id."_".time().".".$ext2;
           $config2['upload_path'] = 'assets/backend/images/drivinglicence';
           $config2['allowed_types'] = 'jpg|png|jpeg';
           $config2['file_name'] = $new_file_name2;
           
           $this->upload->initialize($config2);
           if($this->upload->do_upload('drivinglicenceback')){
               $filePathImage2 = 'assets/backend/images/drivinglicence/'.$new_file_name2;
               if($filePathImage2!=''){
                   $filePathImage2 = $filePathImage2;
               }else{
                   $filePathImage2 = NULL;
               }
               }
        }
        $data = array(
            'driving_licence_front' => $filePathImage1,
            'driving_licence_back' => $filePathImage2,
            'updated_date'=>date('Y-m-d H:i:s')
        );
        $where = 'user_id='.$user_id;
        $user = $this->CrudModel->updateItem('driver',$where,$data);

        if (!empty($user) AND $user != FALSE)
            {                
                $message = [
                    'status' => true,
                    'message' => "Driving Licence Uploaded Successfully"
                ];
                $this->response($message, REST_Controller::HTTP_OK);  
               
            }else{
                $message = [
                    'status' => false,
                    'message' => "Driving Licence Not Uploaded"
                ];
                $this->response($message, REST_Controller::HTTP_OK);  
            }
    }

    public function checkdocument_post(){
        header("Access-Control-Allow-Origin: *");
        $_POST = $this->security->xss_clean($_POST);
        $user_id= $this->post('user_id');
        $params = array();
		$params['select'] = "t1.*"; 
		$params['table'] = 'driver t1';
		$where = "";
		$where = "t1.user_id=".$user_id;
		$params['where'] = $where;
		$params['order_by'] = 't1.user_id';
		$params['output'] = 'row_array';
		$document_status = $this->CrudModel->getReports($params);
        if (!empty($document_status) AND $document_status != FALSE)
        {                
            $message = [
                'status' => true,
                'document_status' => (int)$document_status['documents_status']
            ];
            $this->response($message, REST_Controller::HTTP_OK);  
           
        }else{
            $message = [
                'status' => false,
                'message' => "User Not Found"
            ];
            $this->response($message, REST_Controller::HTTP_OK);  
        }
        
    }
    public function findlocation_post(){
        header("Access-Control-Allow-Origin: *");
        $_POST = $this->security->xss_clean($_POST);
        $lng= $this->post('lng');
        $lat= $this->post('lat');
        $user_id= $this->post('user_id');
       
        $data = [
            'lng'	=> $lng,
            'lat' =>$lat        
        ];
        
        $where = 'user_id='.$user_id;
        $user = $this->CrudModel->updateItem('users',$where,$data);
        if (!empty($user) AND $user != FALSE)
        {                
            $message = [
                'status' => true,
                'message' => "Location Updated"
            ];
            $this->response($message, REST_Controller::HTTP_OK);  
        
        }else{
            $message = [
                'status' => false,
                'message' => "Location Not Updated"
            ];
            $this->response($message, REST_Controller::HTTP_OK);  
        }
        
    }

   

    
    
    public function acceptride_post(){
        header("Access-Control-Allow-Origin: *");
        $_POST = $this->security->xss_clean($_POST);
        $order_id= $this->post('order_id');
        $driver_id= $this->post('driver_id');
        $user_id= $this->post('user_id');
        $booking_id= $this->post('booking_id');

        $params = array();
		$params['select'] = "t1.*"; 
		$params['table'] = 'users t1';
		$where = "";
		$where = "t1.user_id=".$user_id;
		$params['where'] = $where;
		$params['order_by'] = 't1.user_id';
		$params['output'] = 'row_array';
		$user_data = $this->CrudModel->getReports($params);
        $token = $user_data['fcm_token'];

        $data = array(
            'driver_id' => $driver_id,
            'status' => 'accepted',
            'accepted_date' => date('Y-m-d H:i:s')
        );
        $where = 'id='.$order_id;
        $user = $this->CrudModel->updateItem('orders',$where,$data);
        if($user!=1){
            $message = [
                'status' => false,
                'message' => "Problem for updating"
            ];
            $this->response($message, REST_Controller::HTTP_OK); 
        }else{
            $driver_details = get_table_row('users', array('user_id' => $driver_id));
            $user_details = get_table_row('users', array('user_id' => $user_id));
            $name = $driver_details['name'];
            
            $message = "Your ride with Booking ID: " . $booking_id . " has been accepted by $name ";
            $notification = [
                'title' =>'Ride Information',
                'body' => $message,
                'sound' => 'mySound'
            ];
            $extraNotificationData = ["message" => $notification];
            $not = push_notification_android($token,$notification,$extraNotificationData);
        }

    }

    public function rejectride_post(){
        header("Access-Control-Allow-Origin: *");
        $_POST = $this->security->xss_clean($_POST);
        $order_id= $this->post('order_id');
        $driver_id= $this->post('driver_id');
        $user_id= $this->post('user_id');
        $booking_id= $this->post('booking_id');

        $params = array();
		$params['select'] = "t1.*"; 
		$params['table'] = 'users t1';
		$where = "";
		$where = "t1.user_id=".$user_id;
		$params['where'] = $where;
		$params['order_by'] = 't1.user_id';
		$params['output'] = 'row_array';
		$user_data = $this->CrudModel->getReports($params);
        $token = $user_data['fcm_token'];

        $data = array(
            'driver_id' => 0,
            'status' => 'pending',
            'accepted_date' => ''
        );
        $where = 'id='.$order_id;
        $user = $this->CrudModel->updateItem('orders',$where,$data);
        if($user!=1){
            $message = [
                'status' => false,
                'message' => "Problem for updating"
            ];
            $this->response($message, REST_Controller::HTTP_OK); 
        }else{
            $driver_details = get_table_row('users', array('user_id' => $driver_id));
            $user_details = get_table_row('users', array('user_id' => $user_id));
            $name = $driver_details['name'];
            
            $message = "Your ride with Booking ID: " . $booking_id . " has been accepted by $name ";
            $notification = [
                'title' =>'Ride Information',
                'body' => $message,
                'sound' => 'mySound'
            ];
            $extraNotificationData = ["message" => $notification];
            $not = push_notification_android($token,$notification,$extraNotificationData);
            $message = [
                'status' => true,
                'message' => "Ride Rejected Successfully"
            ];
            $this->response($message, REST_Controller::HTTP_OK); 
        }

    }
    public function pickup_ride_post(){
        header("Access-Control-Allow-Origin: *");
        $_POST = $this->security->xss_clean($_POST);
        $order_id= $this->post('order_id');
        $driver_id= $this->post('driver_id');
        $vehicle_type= $this->post('vehicle_type');
        $booking_id= $this->post('booking_id');
        $data = array(
            'driver_id' => $driver_id,
            'vehicle_type' => $vehicle_type,
            'status' => 'started',
            'driver_status' => 'started',
            'updated_on' => date('Y-m-d H:i:s'),
            'ride_start_time'=>date('Y-m-d H:i:s')
        );
        $where = 'id='.$order_id;
        $update_user = $this->CrudModel->updateItem('orders',$where,$data);
        if ($update_user === FALSE) {
            $response = array('status' => false, 'message' => 'Trip not started!');
        } else {
            $orders = $this->ws_model->get_ride_orders($ride_id);
            if (!empty($orders)) {
                foreach ($orders as $order) {
                    $this->ws_model->send_push_notification('Trip started sucessfully!', 'user', $order['user_id'], 'ride_started', $order['order_id'], '', '', $order['vehicle_id'], $ride_id, $order['mode'], $order['ride_type']);
                }
            }
            $response = array('status' => true, 'message' => 'Trip started sucessfully!');
        }
    }

}

//17.497479, 78.390134 Sardar Patel Nagar1
// /17.496442, 78.390385  Sardar Patel Nager 2
//17.500791, 78.386878 Vasantha Nagar
//17.507299, 78.387024 Nizampet
//17.507002, 78.396100 Pragathi Nagar
//17.482745, 78.412355 Kukatpally
//17.476125, 78.422359 BalaNagar
//17.462081, 78.431068 BharatNagar
//17.438860, 78.442887 Sr Nagar
//17.425056, 78.457119 Somajiguda