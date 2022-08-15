<?php
 //ob_end_clean();
 // A sample PHP Script to POST data using cURL
		//check 帳號/密碼
		
	//function SendCURL($membername, $memberid,$memberpwd){
	//	$crl = curl_init('https://ml-api.jotangi.com.tw/api/auth/register');
	//	$data = array(
	//	  'name' => $membername,
	//	  'mobile' => $memberid,
	//	  'password' => $memberpwd,
	//	);
	function SendCURL2($memberid,$memberpwd){
		$crl = curl_init('https://ml-api.jotangi.com.tw/api/auth/rewritepwd');
		$data = array(
		  'mobile' => $memberid,
		  'password' => $memberpwd,
		);
	
		$post_data = json_encode($data);

		// Prepare new cURL resource
		
		curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($crl, CURLINFO_HEADER_OUT, true);
		curl_setopt($crl, CURLOPT_POST, true);
		curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);

		// Set HTTP Header for POST request 
		curl_setopt($crl, CURLOPT_HTTPHEADER, array(
		  'Content-Type: application/json',
		  'Content-Length: ' . strlen($post_data))
		);

		// Submit the POST request
		$result = curl_exec($crl);
//echo $result;
		$obj = json_decode($result,true);

		//echo $obj->{'status'};
		//echo $obj->{'code'};
		//echo $obj->{'message'};
		
		echo $obj["status"]; 
		echo $obj["code"]; 
		echo $obj["message"]; 
		
		// handle curl error
		if ($obj["status"] == "error") {
		//if ($result === false) {
		  // throw new Exception('Curl error: ' . curl_error($crl));
		  //print_r('Curl error: ' . curl_error($crl));
		  $result_noti = $obj["message"]; //die();
		} else {

		  $result_noti = $obj["message"]; //die();
		}
		curl_close($crl);
		return $result_noti;
	}		
		
	$member_name = "Mike Chen";//isset($_POST['member_name']) ? $_POST['member_name'] : '';
	$member_id = "0912345678";//isset($_POST['member_id']) ? $_POST['member_id'] : '';
	//$member_pwd = "A12345678";//isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
					
	$member_pwd = "A12341234";				
	//$result_key=SendCURL($member_name,$member_id,$member_pwd);
	$result_key=SendCURL2($member_id,$member_pwd);
	echo $result_key;
?>