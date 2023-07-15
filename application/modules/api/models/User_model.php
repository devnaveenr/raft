<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_Model extends CI_Model
{
    public function check_mobile($mobile,$user_type) {
		$this->db->where(['mobile' => $mobile,'user_type'=>$user_type]);
		$query 	= $this->db->get('users');
		$result = $query->num_rows();
		return $result;
	}
	public function get_user_details($mobile,$user_type){
		$this->db->select('user_id,mobile,otp');
		$this->db->where(['mobile' => $mobile,'user_type'=>$user_type]);
		$query 	= $this->db->get('users');
		$result = $query->row();
		return $result;
	}
	public function get_user_details_id($user_id){
		$this->db->select('user_id,mobile,otp');
		$this->db->where(['user_id' => $user_id]);
		$query 	= $this->db->get('users');
		$result = $query->row();
		return $result;
	}
    public function update_otp($mobile, $data) {
		return $this->db->update('users', $data, ["mobile"=>$mobile]);
	}
    public function verify($mobile, $otp,$user_id) {
		$data = [];
		$this->db->where(['mobile' => $mobile, 'otp' => $otp,'user_id'=>$user_id]);
		$query = $this->db->get('users');
		$result = $query->row();
		//echo $this->db->last_query();
		if($result) {
			$data = [
				'user_id' 	=> $result->user_id,
				'name' 	=> $result->name,
                'email' => $result->email,
				'mobile' 	=> $result->mobile,
				'user_type' => $result->user_type,
			];
		}
		return $data;
	}
	
}