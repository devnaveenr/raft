<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Drivers extends MX_Controller {
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
		$data['title'] = 'Drivers';

		$params = array();
		$params['select'] = "t1.*"; 
		$params['table'] = 'users t1';
		$where = "";
		$where = "t1.user_type=2";
		$params['where'] = $where;
		$params['order_by'] = 't1.user_id';
		$params['output'] = 'result_object';
		$data['users'] = $this->crud_model->getReports($params);

		$data['css_links']='global/css_links';
		$data['header']='global/header';
        $data['js_links']='global/js_links';
		$data['footer']='global/footer';
		$data['sidebar']='global/sidebar';
		$this->load->view('drivers',$data);
		
	}
	public function view_driver($id)
	{
		$this->is_logged_in();
		$data['title'] = 'Drivers';

		$params = array();
		$params['select'] = "t1.*,t2.*,t3.*,t4.*"; 
		$params['table'] = 'users t1';
		$join = array();
		$join[0]['table'] = 'driver t2';
		$join[0]['type'] = 'left';
		$join[0]['condition'] = 't2.user_id =t1.user_id ';
		$join[1]['table'] = 'cities t3';
		$join[1]['type'] = 'left';
		$join[1]['condition'] = 't3.city_id =t2.city';
		$join[2]['table'] = 'vehicle_types t4';
		$join[2]['type'] = 'left';
		$join[2]['condition'] = 't4.vehicle_type_id =t2.vehicle_type ';
		$params['join'] = $join;
		$where = "";
		$where = "t1.user_id=".$id;
		$params['where'] = $where;
		$params['order_by'] = 't1.user_id';
		$params['output'] = 'row_array';
		$data['driver'] = $this->crud_model->getReports($params);

		$data['css_links']='global/css_links';
		$data['header']='global/header';
        $data['js_links']='global/js_links';
		$data['footer']='global/footer';
		$data['sidebar']='global/sidebar';
		$this->load->view('view-driver',$data);
		
	}
	public function delete_driver($id) {
		$where = "user_id=".$id;
        $id = $this->crud_model->deleteItems('users',$where);
		$id = $this->crud_model->deleteItems('driver',$where);
    }
	public function change_user_status() {
		$data=array('user_status'=>$this->input->post('status'));
		$where = "user_id=".$this->input->post('id');
        $this->crud_model->updateItem('users',$where,$data);
        redirect('users','refresh');
    }
	public function change_documents_status() {
	    
	    $val = $this->input->post('val');
	    $user_id = $this->input->post('user_id');
	    $data['title'] = 'Drivers';

		$params = array();
		$params['select'] = "t1.*"; 
		$params['table'] = 'users t1';
		$where = "";
		$where = "t1.user_id=".$user_id;
		$params['where'] = $where;
		$params['order_by'] = 't1.user_id';
		$params['output'] = 'row_array';
		$driver = $this->crud_model->getReports($params);
        $token = $driver['fcm_token'];
        $name = $driver['name'];
	    if($val==1){
	        $notification = [
            'title' =>'Not Approved',
            'body' => 'Your Documents Not Approved. Please upload Proper documents as soon as possible',
            'sound' => 'mySound',
			'docstatus'=>1
        ];
        $extraNotificationData = ["message" => $notification,"docstatus" =>$val];
	        $not = $this->push_notification_android($token,$notification,$extraNotificationData);
	        echo $not;
	    }
	    if($val==2){
	        $notification = [
            'title' =>'Approved Successfully',
            'body' => 'Your Documents are Approved. Happy to Ride now',
            'sound' => 'mySound',
			'docstatus'=>2
        ];
        $extraNotificationData = ["message" => $notification,"docstatus" =>$val];
	        $not = $this->push_notification_android($token,$notification,$extraNotificationData);
	        echo $not;
	    }
		$data=array('documents_status'=>$val);
		$where = "user_id=".$user_id;
        $this->crud_model->updateItem('driver',$where,$data);
        redirect('users','refresh');
    }
   function push_notification_android($token,$notification,$extraNotificationData){

     $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
    define('API_ACCESS_KEY','AAAAbfqYWl8:APA91bGX3_J1KXH3LX9qeN21FDCFV01Ojmuw5M1pJP5A6kdcFa58tn3cGKKUhkhfqQcIYGjzfcn2gvu_1iopT_ajPGDG0ybqz-Ju79OG1q34tqY9eXIQeq9c4mDByT1eKPx4hQ64YHvW');
	$API_ACCESS_KEY = 'AAAAbfqYWl8:APA91bGX3_J1KXH3LX9qeN21FDCFV01Ojmuw5M1pJP5A6kdcFa58tn3cGKKUhkhfqQcIYGjzfcn2gvu_1iopT_ajPGDG0ybqz-Ju79OG1q34tqY9eXIQeq9c4mDByT1eKPx4hQ64YHvW';  
  
	$fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to'        => $token, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=' . $API_ACCESS_KEY,
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);


        echo $result;
}
}
