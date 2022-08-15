<?php
function Save_Log($conn,$a,$b,$c,$d,$e){
	//member_id	member_name	store_id	function_name	log_date	action_type
	
		$sql="INSERT INTO syslog (member_id,member_name,store_id,function_name,log_date,action_type) VALUES ('$a', '$b',$c, '$d',NOW(),$e);";
		mysqli_query($conn,$sql) or die(mysqli_error($conn));
}

$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$FCM_Token = isset($_POST['FCM_Token']) ? $_POST['FCM_Token'] : '';

	if (($member_id != '') && ($member_pwd != '')) {
		//if (strlen($member_pwd) < 8) {
		//	$data["status"]="false";
		//	$data["code"]="0x0204";
		//	$data["responseMessage"]="密碼長度不足8碼!";
		//	header('Content-Type: application/json');
		//	echo (json_encode($data, JSON_UNESCAPED_UNICODE));	
		//	exit;
		//}
		//check 帳號/密碼
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';

		try {
			$link = mysqli_connect($host, $user, $passwd, $database);
			mysqli_query($link,"SET NAMES 'utf8'");

			$member_id  = mysqli_real_escape_string($link,$member_id);
			$member_pwd  = mysqli_real_escape_string($link,$member_pwd);

			$member_id = str_replace("from", "xxxx", str_replace("select", "xxxxx", str_replace("=", "x", str_replace("or", "xx", str_replace("and", "xxx", $member_id)))));
			$member_pwd = str_replace("from", "xxxx", str_replace("select", "xxxxx", str_replace("=", "x", str_replace("or", "xx", str_replace("and", "xxx", $member_pwd)))));

			//if (($userid != "") && ($userpwd != "") && (strlen($userid) < 16) && (strlen($userpwd) < 16)){
			
			$sql = "SELECT * FROM member where member_trash=0 ";   //and member_status='1' 
			if ($member_id != "") {	
				$sql = $sql." and member_id='".$member_id."'";
			}
			if ($member_pwd != "") {	
				$sql = $sql." and member_pwd='".$member_pwd."'";
			}
			if ($result = mysqli_query($link, $sql)){
				if (mysqli_num_rows($result) > 0){
					while($row = mysqli_fetch_array($result)){
						$mid = $row['mid'];
						$member_name = $row['member_name'];
						$membersid = $row['member_sid'];
						$member_status = $row['member_status'];
					}
					if ($member_status == '1'){
						// login ok
						// echo "[]";
						//echo "login success!";
						Save_Log($link,$member_id,$member_name,$membersid,'APP Login',4);

						if ($FCM_Token != ''){
							$sql3="update member set notificationToken='$FCM_Token' where mid=".$mid;
							mysqli_query($link,$sql3) or die(mysqli_error($link));
						}
						
						$data["status"]="true";
						$data["code"]="0x0200";
						$data["responseMessage"]="Login success!";
					} else {
						$data["status"]="false";
						$data["code"]="0x0206";
						$data["responseMessage"]="此號碼尚未通過驗證!";						
					}
				}else{
					//$this->_response(null, 400, validation_errors());
					//echo "error mail or password!";
					$data["status"]="false";
					$data["code"]="0x0201";
					$data["responseMessage"]="ID or password is wrong!";					
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