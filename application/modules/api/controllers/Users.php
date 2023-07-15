<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';
 
class Users extends REST_Controller
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
    public function updateuser_post()
    {
        header("Access-Control-Allow-Origin: *");
        $_POST = $this->security->xss_clean($_POST);
        $user_id= $this->post('user_id');
        $name = $this->post('name');
        $email = $this->post('email');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
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
                'name' => $name,
                'email' => $email,
            );
            $where = 'user_id='.$user_id;
            $user = $this->CrudModel->updateItem('users',$where,$data);
           
            if (!empty($user) AND $user != FALSE)
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

    public function usercheck_post()
    {
        header("Access-Control-Allow-Origin: *");
        $_POST = $this->security->xss_clean($_POST);
        $user_id= $this->post('user_id');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
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

            $params = array();
            $params['select'] = "t1.name,t1.email"; 
            $params['table'] = 'users t1';
            $where = "";
            $where = "t1.user_id=".$user_id;
            $params['where'] = $where;
            $params['output'] = 'row_array';
            $userdetails = $this->CrudModel->getReports($params);

            

            if($userdetails['name']==NULL && $userdetails['email']==NULL) 
            {
                $usercheck = 0;
            }else{
                $usercheck = 1;
            }


            if ($usercheck==1)
            {                
                $message = [
                    'status' => true,
                    'usercheck' => 1
                ];
                $this->response($message, REST_Controller::HTTP_OK);  
               
            }else{
                $message = [
                    'status' => false,
                    'usercheck' => 0
                ];
                $this->response($message, REST_Controller::HTTP_OK);  
            }
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
    public function getdrivers_post(){
        header("Access-Control-Allow-Origin: *");
        $_POST = $this->security->xss_clean($_POST);
        $lng= $this->post('lng');
        $lat= $this->post('lat');
        $user_id= $this->post('user_id');
        $params = array();
		$params['select'] = "t1.*,t2.*,t3.*,t1.user_id as driver_id,t3.vehicle_type as v_type"; 
		$params['table'] = 'users t1';
        $join = array();
		$join[0]['table'] = 'driver t2';
		$join[0]['type'] = 'left';
		$join[0]['condition'] = 't2.user_id =t1.user_id ';
		$join[1]['table'] = 'vehicle_types t3';
		$join[1]['type'] = 'left';
		$join[1]['condition'] = 't3.vehicle_type_id =t2.vehicle_type ';
		$params['join'] = $join;
		$where = "";
		$where = "t1.user_type=2 AND t1.user_type=2";
		$params['where'] = $where;
		$params['order_by'] = 't1.user_id';
		$params['output'] = 'result_object';
        $drivers = $this->CrudModel->getReports($params);
        
        $driversdatafinal = array();
        if(!empty($drivers)){
            $i=0;
            foreach($drivers as $driver){
                $driver_id=$driver->driver_id;
                $driverlat=$driver->lat;
                $driverlng=$driver->lng;
                
                $distancetime = get_distance_time($lat,$driverlat, $lng, $driverlng);
                //print_r($distancetime);exit;
                $minutes = explode(" ", $distancetime['time']);

                if (in_array("hours", $minutes) && in_array("mins", $minutes)) {
                    $seconds = ($minutes[0] * 3600) + ($minutes[2] * 60);
                } else {
                    $seconds = $minutes[0] * 60;
                }
                //echo $seconds;exit;
                $distance = explode(" ", $distancetime['distance']);
               // print_r($distance);
                if ($distance[1] == "m") {
                    $km = $distance[0] / 1000;
                } else {
                    $km = $distance[0];
                }
               
                //if($this->distance($latitude, $langitude, $drvierlatitude, $driverlangitude, "K") < 4)
                if($km < 4)
                {
                 $driversdata['user_id']=$driver_id;
                 $driversdata['name']=$driver->name;
                 $driversdata['vehicle_type']=$driver->v_type;
                 $driversdata['vehicle_icon']=base_url().$driver->vehicle_icon;
                 $driversdata['driverlng']=$driverlng;
                 $driversdata['driverlat']=$driverlat;
                 $driversdata['time']=$seconds;
                 $driversdata['distance']=$distancetime['distance'];
                 

                
                 $driversdatafinal[] = $driversdata;
                }
                else
                {
                //echo "False";
                }
                $i++;
            }
        }
        if (!empty($driversdatafinal) AND $driversdatafinal != FALSE)
        {                
            $message = [
                'status' => true,
                'data'=>$driversdatafinal,
                'message' => "Drivers Data"
            ];
            $this->response($message, REST_Controller::HTTP_OK);  
        
        }else{
            $message = [
                'status' => false,
                'message' => "Not Found"
            ];
            $this->response($message, REST_Controller::HTTP_OK);  
        }
        
    }

    public function requestdrivers_post(){
        header("Access-Control-Allow-Origin: *");
        $_POST = $this->security->xss_clean($_POST);
        $to_lat= $this->post('to_lat');
        $to_lng= $this->post('to_lng');
        $from_lat= $this->post('from_lat');
        $from_lng= $this->post('from_lng');
        $user_id= $this->post('user_id');
        $vehicle_type= $this->post('vehicle_type');

        $trip_distance = get_distance_time($from_lat, $to_lat, $from_lng, $to_lng);
        $minutes = explode(" ", $trip_distance['time']);

        if (in_array("hours", $minutes) && in_array("mins", $minutes)) {
            $seconds = ($minutes[0] * 3600) + ($minutes[2] * 60);
        } else {
            $seconds = $minutes[0] * 60;
        }
        $distance = explode(" ", $trip_distance['distance']);
        
        if ($distance[1] == "m") {
            $km = $distance[0] / 1000;
        } else {
            $km = $distance[0];
        }

        $booking_id = mt_rand(10000000, 99999999);

        $amount = 10;
        $total_amount = 15;
        $data = array(
            'booking_id' => $booking_id,
            'user_id' => $user_id,
            'from_lat' => $from_lat,
            'from_lng' => $from_lng,
            'from_address' => $trip_distance['origin_addresses'],
            'to_lat' => $to_lat,
            'to_lng' => $to_lng,
            'to_address' => $trip_distance['destination_addresses'],
            'vehicle_type' => $vehicle_type,
            'trip_distance' => $km,
            'amount' => round($amount),
            'total_amount' => round($total_amount),
            'created_on' => date('Y-m-d H:i:s')
        );

        $id = $this->CrudModel->insertItem('orders', $data);

        $params = array();
		$params['select'] = "t1.*,t2.*,t3.*,t1.user_id as driver_id,t3.vehicle_type as v_type"; 
		$params['table'] = 'users t1';
        $join = array();
		$join[0]['table'] = 'driver t2';
		$join[0]['type'] = 'left';
		$join[0]['condition'] = 't2.user_id =t1.user_id ';
		$join[1]['table'] = 'vehicle_types t3';
		$join[1]['type'] = 'left';
		$join[1]['condition'] = 't3.vehicle_type_id =t2.vehicle_type ';
		$params['join'] = $join;
		$where = "";
		$where = "t1.user_type=2 AND t2.vehicle_type=".$vehicle_type;
		$params['where'] = $where;
		$params['order_by'] = 't1.user_id';
		$params['output'] = 'result_object';
        $drivers = $this->CrudModel->getReports($params);
        
        $driversdatafinal = array();
        if(!empty($drivers)){
            $i=0;
            foreach($drivers as $driver){
                $driver_id=$driver->driver_id;
                $driverlng=$driver->lng;
                $driverlat=$driver->lat;

                $distancetime = get_distance_time($from_lat,$driverlat, $from_lng, $driverlng);
                $minutes = explode(" ", $distancetime['time']);

                if (in_array("hours", $minutes) && in_array("mins", $minutes)) {
                    $seconds = ($minutes[0] * 3600) + ($minutes[2] * 60);
                } else {
                    $seconds = $minutes[0] * 60;
                }
                //echo $seconds;exit;
                $distance = explode(" ", $distancetime['distance']);
                
                if ($distance[1] == "m") {
                    $km = $distance[0] / 1000;
                } else {
                    $km = $distance[0];
                }

                //echo $km;exit;


                if($km < 20)
                {
                 $driversdata['user_id']=$driver_id;
                 $driversdata['name']=$driver->name;
                 $driversdata['vehicle_type']=$driver->v_type;
                 $driversdata['vehicle_icon']=base_url().$driver->vehicle_icon;
                 $driversdata['driverlng']=$driverlng;
                 $driversdata['driverlat']=$driverlat;
                 $driversdatafinal[] = $driversdata;
                 $token = $driver->fcm_token;
                 
                 $notification = [
                    'title' =>'Ride Request',
                    'body' => 'User Requested for ride',
                    'sound' => 'mySound',
                    'booking_id'=>$booking_id,
                    'order_id'=>$id
                ];
                $extraNotificationData = ["message" => $notification,"userlangitude" =>$from_lat,"userlatitude" =>$from_lng,"userid"=>$user_id];
                //print_r($extraNotificationData);exit;
                $not = push_notification_android($token,$notification,$extraNotificationData);
               // echo $not;
                }
                else
                {
                //echo "False";
                }
                $i++;

               
            }
            $message = [
                'status' => true,
                'message' => "Sent Successfully",
                'response'=>$driversdatafinal
            ];
            $this->response($message, REST_Controller::HTTP_OK); 
        }
        // if (!empty($driversdatafinal) AND $driversdatafinal != FALSE)
        // {                
        //     $message = [
        //         'status' => true,
        //         'message' => "Sent Successfully"
        //     ];
        //     $this->response($message, REST_Controller::HTTP_OK);  
        
        // }else{
        //     $message = [
        //         'status' => false,
        //         'message' => "Not Found"
        //     ];
        //     $this->response($message, REST_Controller::HTTP_OK);  
        // }
        
    }

   
    
}