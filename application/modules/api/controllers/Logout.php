<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';
 
class Logout extends REST_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model', 'UserModel');
        $this->load->library('Authorization_Token');
        
    }
    public function logout_post()
    {  
        $is_valid_token = $this->authorization_token->validateToken();
        if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE){
            $message = [
                'status' => true,
            ];
            $this->response($message, REST_Controller::HTTP_OK);  
        }
        else{
            $message = [
                'status' => false,
            ];
            $this->response("Success", REST_Controller::HTTP_OK);
        }
    }

    
}