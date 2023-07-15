<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model {

    public function __construct() 
    {
        parent::__construct();
    }
    /** Login **/
    function login($post='')
    {
         $email=$post['email'];
         $password=$post['password'];
         $this->db->select('admin_user_id, firstname,lastname, email,password');
         $this->db->from('admin_users');
         $this->db->where('email',$email);
         $this->db->where('status',1);
         $query = $this->db->get();
         $result = $query->row_array();
         $count = $query->num_rows();

         if($count!=0)
         {
            if(password_verify($password, $result["password"]))  
            { 

                return $result;
            }
         }else{

            $result = array();
            return $result;
         }
    }
    
    /** Login **/

    function get_courses_count(){
        $this->db->select('course_id');
        $this->db->from('courses');
        $this->db->where('courses.course_status',1);
        return $this->db->count_all_results();
    }
    function get_topics_count(){
        $this->db->select('topic_id');
        $this->db->from('topics');
        $this->db->where('topics.topic_status',1);
        return $this->db->count_all_results();
    }

}
