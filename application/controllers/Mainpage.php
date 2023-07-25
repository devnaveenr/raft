<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mainpage extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
       
    }
    
    public function index()
    {
        //testing git now email checking hello abc
        $data = array();
        // $data['css_links'] = 'includes/css_links';
        // $data['top_header'] = 'includes/top_header';
        // $data['header'] = 'includes/header';
        // $data['js_links'] = 'includes/js_links';
        // $data['footer'] = 'includes/footer';
        $this->load->view('index', $data);
    }
    
}
