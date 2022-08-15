<?php
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
	$obj = json_decode($result,true);

	// handle curl error
	//if ($result === false) {
	if ($obj["status"] == "error") {
	  // throw new Exception('Curl error: ' . curl_error($crl));
	  //print_r('Curl error: ' . curl_error($crl));
	  $result_noti = 0; //die();
	} else {

	  $result_noti = 1; //die();
	}
	curl_close($crl);
return $result_noti;
}	

$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$usercode = isset($_POST['code']) ? $_POST['code'] : '';

	if (($member_id != '') && ($member_pwd != '') && ($usercode != '')) {
		//check 帳號/密碼
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';
		
		//echo $sql;
		//exit;
		try {
			$link = mysqli_connect($host, $user, $passwd, $database);
			mysqli_query($link,"SET NAMES 'utf8'");

			$member_id  = mysqli_real_escape_string($link,$member_id);
			$member_pwd  = mysqli_real_escape_string($link,$member_pwd);
			$usercode  = mysqli_real_escape_string($link,$usercode);
			
			$sql = "SELECT * FROM member where member_trash=0 ";
			if ($member_id != "") {	
				$sql = $sql." and member_id='".$member_id."'";
			}			
			if ($usercode != "") {	
				$sql = $sql." and reset_code='".$usercode."'";
			}
					//echo $sql;
					//exit;			
			if ($result = mysqli_query($link, $sql)){
				if (mysqli_num_rows($result) > 0){
					// login ok
					// echo "[]";
					$mid=0;
					while($row = mysqli_fetch_array($result)){
						$mid = $row['mid'];
						$xmid = $row['member_id'];
					}
					$sql2="update member set member_pwd='$member_pwd',reset_code='',member_updated_at=NOW() where mid=".$mid." ;";

					//echo $sql;
					//exit;
					mysqli_query($link,$sql2) or die(mysqli_error($link));	
					
					$result_key=SendCURL2($xmid,$member_pwd);
					
					//echo "new password is applyed!";
					$data["status"]="true";
					$data["code"]="0x0200";
					$data["responseMessage"]="Reset password success!";					
				}else{
					//$this->_response(null, 400, validation_errors());
					//echo "error mail or password!";
					$data["status"]="false";
					$data["code"]="0x0201";
					$data["responseMessage"]="ID or code is wrong!";					
				}
			}else{
				//echo "need mail and password!";
				$data["status"]="false";
				$data["code"]="0x0204";
				$data["responseMessage"]="SQL fail!";					
			}
			mysqli_close($link);
		} catch (Exception $e) {
            //$this->_response(null, 401, $e->getMessage());
			//echo $e->getMessage();
			$data["status"]="false";
			$data["code"]="0x0202";
			$data["responseMessage"]=$e->getMessage();				
        }
		header('Content-Type: application/json');
		echo (json_encode($data, JSON_UNESCAPED_UNICODE));
	}else{
		//echo "need mail and password!";
		$data["status"]="false";
		$data["code"]="0x0203";
		$data["responseMessage"]="API parameter is required!";
		header('Content-Type: application/json');
		echo (json_encode($data, JSON_UNESCAPED_UNICODE));		
	}
?>