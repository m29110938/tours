<?php
 ob_end_clean();
 // A sample PHP Script to POST data using cURL
		//check 帳號/密碼
		
	function SendCURL($membername,$memberid,$memberpwd){
		$crl = curl_init('https://ml-api.jotangi.com.tw/api/auth/register');
		$data = array(
		  'name' => $membername,
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
		
		$obj = json_decode($result,true);
		
		echo $obj["status"]; 
		echo $obj["code"]; 
		echo $obj["message"];
		

		// handle curl error
		//if ($result === false) {
		if ($obj["status"] == "error") {	
		  // throw new Exception('Curl error: ' . curl_error($crl));
		  //print_r('Curl error: ' . curl_error($crl));
		  //$result_noti = 0; //die();
		  $result_noti = $obj["message"];
		} else {

		  //$result_noti = 1; //die();
		  $result_noti = $obj["message"];
		}
		curl_close($crl);
	return $result_noti;
	}		
		
	$host = 'localhost';
	$user = 'tours_user';
	$passwd = 'tours0115';
	$database = 'toursdb';
	

	try {
		$link = mysqli_connect($host, $user, $passwd, $database);
		mysqli_query($link,"SET NAMES 'utf8'");

		//$member_id  = mysqli_real_escape_string($link,$member_id);
		//$member_pwd  = mysqli_real_escape_string($link,$member_pwd);
		
		$sql = "SELECT * FROM member where member_trash=0 and member_type=1";
		//if ($member_id != "") {	
		//	$sql = $sql." and member_id='".$member_id."'";
		//}
		//if ($member_pwd != "") {	
		//	$sql = $sql." and member_pwd='".$member_pwd."'";
		//}
		if ($result = mysqli_query($link, $sql)){
			if (mysqli_num_rows($result) > 0){
				// login ok
				// user id 取得
				//$mid=0;
				
				while($row = mysqli_fetch_array($result)){
					$id = $row['mid'];
					$mname = $row['member_name'];
					$mid = $row['member_id'];
					$mpwd = $row['member_pwd'];
					
					$result_key=SendCURL($mname,$mid,$mpwd);
					

					// Close cURL session handle
					//curl_close($crl);
					echo $mid." : ".$result_key; 
					ob_flush();
					flush(); 
					sleep(2);
				}
				
			}
			echo "OK";
		}else{
			$data2["status"]="false";
			$data2["code"]="0x0204";
			$data2["responseMessage"]="SQL fail!";				
			header('Content-Type: application/json');				
			echo (json_encode($data2, JSON_UNESCAPED_UNICODE));
		}
	} catch (Exception $e) {
		//$this->_response(null, 401, $e->getMessage());
		//echo $e->getMessage();
		$data2["status"]="false";
		$data2["code"]="0x0202";
		$data2["responseMessage"]=$e->getMessage();			
		header('Content-Type: application/json');
		echo (json_encode($data2, JSON_UNESCAPED_UNICODE));		
	}
?>