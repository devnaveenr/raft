<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';
 
class Login extends REST_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model', 'UserModel');
        $this->load->model('global/crud_model', 'CrudModel');
        
    }
    public function index_post()
    {
        header("Access-Control-Allow-Origin: *");
        $_POST = $this->security->xss_clean($_POST);
        $mobile= $this->post('mobile');
        $user_type = $this->post('user_type');
        $device_id = $this->post('device_id');
        $fcm_token = $this->post('fcm_token');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required');
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
            $user = $this->UserModel->check_mobile($mobile,$user_type);
            $userdetails = $this->UserModel->get_user_details($mobile,$user_type);
           
            if (!empty($user) AND $user != FALSE)
            {
                $otp = $this->generate_otp($mobile);

                $data = [
                    'otp'	=> $otp,
                    'mobile' =>$mobile,
                    'device_id'=>$device_id,
                    'fcm_token'=>$fcm_token,
                ];
                $this->UserModel->update_otp($mobile, $data);
                
                //$message = $otp." is your OTP. Do not share with anyone.";
                $this->send_sms($mobile,$otp);
                $message = [
                    'status' => true,
                    'data' => array(
                        'mobile'=>$userdetails->mobile,
                        'otp'=>$otp,
                        'user_id'=>$userdetails->user_id,
                    ),
                    'message' => "Mobile Saved"
                ];
                $this->response($message, REST_Controller::HTTP_OK);  
               
            } else
            {
                $otp = $this->generate_otp($mobile);
                $this->send_sms($mobile,$otp);
                
                $data = [
                    'mobile' => $mobile,
                    'otp'	=> $otp,
                    'user_status'	=> 0,
                    'device_id'=>$device_id,
                    'fcm_token'=>$fcm_token,
                    'user_type'	=> $user_type,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $id = $this->CrudModel->insertItem('users', $data);
                $userdetails = $this->UserModel->get_user_details_id($id);
                if($user_type==2){
                    $data1 = [
                        'user_id' => $id,
                        'created_date' => date('Y-m-d H:i:s'),
                    ];
                    $this->CrudModel->insertItem('driver', $data1);  
                }
                // Login Success
                $message = [
                    'status' => true,
                    'data' => array(
                        'mobile'=>$userdetails->mobile,
                        'otp'=>$userdetails->otp,
                        'user_id'=>$userdetails->user_id,
                    ),
                    'message' => "Mobile Saved"
                ];
                $this->response($message, REST_Controller::HTTP_OK);  
            }
        }
    }
    public function generate_otp($mobile) {
        //$OTP = substr($mobile, -6);
		$OTP 	=	rand(1,9);
		$OTP 	.=	rand(0,9);
		$OTP 	.=	rand(0,9);
		$OTP 	.=	rand(0,9);
		return $OTP;
	}
    public function token_post()
    {
        $mobile= $this->post('mobile');
        $otp = $this->post('otp');
        $user_id = $this->post('user_id');
        $this->load->library('Authorization_Token');
        $user = $this->UserModel->verify($mobile, $otp,$user_id);
        //print_r($user);exit;
        $user_token = $this->authorization_token->generateToken($user);
        if(!empty($user)){
            $message = [
                'status' => true,
                'token' => $user_token,
                'user_data'=>$user
            ];
            $this->response($message, REST_Controller::HTTP_OK); 
        }else{
            $message = [
                'status' => false,
                'message'=>'Please Enter Correct OTP'
            ];
            $this->response($message, REST_Controller::HTTP_OK); 
        }
    }
    public function send_sms($mobile,$otp){
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://2factor.in/API/V1/d91dd771-269e-11ed-9c12-0200cd936042/SMS/".$mobile."/".$otp."/RAFT",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        // if ($err) {
        //   echo "cURL Error #:" . $err;
        // } else {
        //   echo $response;
        // }
    }
    
}