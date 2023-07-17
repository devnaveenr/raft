<?php defined('BASEPATH') or exit('No direct script access allowed');


if (!function_exists('get_data')) {
	function get_data($table = '', $where_data = [], $output='')
	{
		$CI    = &get_instance();
		$CI->load->database();
		$CI->load->model('crud_model');

		$db_data = ['table' => $table, 'where' => $where_data, "output" => $output];
		$result = $CI->crud_model->getAnyItems($db_data);

		return $result;
	}
}



function send_sms($data = [])
{
	// Account details
	$apiKey = urlencode(TXTLOCAL_API_KEY);

	// Message details
	$numbers = array($data['receipients']);
	$sender = urlencode($data['sender']);
	$message = rawurlencode($data['message']);

	$numbers = implode(',', $numbers);

	// Prepare data for POST request
	$data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);

	// Send the POST request with cURL
	$ch = curl_init('https://api.txtlocal.com/send/');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//for debug only! comment below 2 lines in live cntro server
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	$response = curl_exec($ch);
	curl_exec($ch);
	
	if (curl_errno($ch)) {
		$error_msg = curl_error($ch);
	}
	curl_close($ch);

	if (isset($error_msg)) {
		echo $error_msg;
	}
	// Process your response here
	// echo "<pre>"; print_r($response); exit;
	return $response;
}


function export_xls($params = array())
{
	$fileName = $params['file_name'];
	// Column names 
	$fields = $params['fields'];
	$export_data = $params['export_data'];
	// Display column names as first row 
	$excelData = implode("\t", array_values($fields)) . "\n";
	if (count($export_data) > 0) {
		foreach ($export_data as $ekey => $lineData) {
			array_walk($lineData, 'filterData');
			$excelData .= implode("\t", array_values($lineData)) . "\n";
		}
	} else {
		$excelData .= 'No records found...' . "\n";
	}
	// Headers for download 
	// header("Content-Disposition: attachment; filename=\"$fileName\"");
	// header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Type: application/vnd.ms.excel");
	header("Content-Disposition: attachment; filename=\"$fileName\"");
	// Render excel data  
	return $excelData;
}

// Filter the excel data 
function filterData(&$str)
{
	$str = preg_replace("/\t/", "\\t", $str);
	$str = preg_replace("/\r?\n/", "\\n", $str);
	if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}

function get_distance_time($lat1, $lat2, $long1, $long2)
    {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?key=AIzaSyD42U4yHNvkoc7-eiOUcGq6qmqiG5AIERc&origins=" . $lat1 . "," . $long1 . "&destinations=" . $lat2 . "," . $long2 . "&mode=driving&language=en";
        //echo $url;exit;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response, true);
		//print_r($response_a);
		//exit;
		if($response_a['rows'][0]['elements'][0]['status']!='ZERO_RESULTS'){
			$dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
			$time = $response_a['rows'][0]['elements'][0]['duration']['text'];
			$origin_addresses=$response_a['origin_addresses'][0];
			$destination_addresses=$response_a['destination_addresses'][0];
			return array('distance' => $dist, 'time' => $time,'origin_addresses'=>$origin_addresses,'destination_addresses'=>$destination_addresses);
		}else{
			return array('distance' => 0, 'time' => 0,'origin_addresses'=>'','destination_addresses'=>'');
		}
        
    }

function pre_print($data = []){
	echo "<pre>"; print_r($data); exit;
}

function push_notification_android($token,$notification,$extraNotificationData){

	$fcmUrl = 'https://fcm.googleapis.com/fcm/send';
  
 
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


	   return $result;
}

if (!function_exists('get_table_row')) {

	function get_table_row($table_name = '', $where = '', $columns = '', $order_column = '', $order_by = 'asc', $limit = '')
	{
		$ci = &get_instance();
		if (!empty($columns)) {
			$tbl_columns = implode(',', $columns);
			$ci->db->select($tbl_columns);
		}
		if (!empty($where))
			$ci->db->where($where);
		if (!empty($order_column))
			$ci->db->order_by($order_column, $order_by);
		if (!empty($limit))
			$ci->db->limit($limit);
		$query = $ci->db->get($table_name);
		if ($columns == 'test') {
			echo $ci->db->last_query();
			exit;
		}
		//echo $ci->db->last_query();
		return $query->row_array();
	}
}


