<?php
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';

$member_name = isset($_POST['member_name']) ? $_POST['member_name'] : '';
//$member_type = isset($_POST['member_type']) ? $_POST['member_type'] : '';
$member_gender = isset($_POST['member_gender']) ? $_POST['member_gender'] : '0';
$member_email = isset($_POST['member_email']) ? $_POST['member_email'] : '';
$member_birthday = isset($_POST['member_birthday']) ? $_POST['member_birthday'] : '';
$member_address = isset($_POST['member_address']) ? $_POST['member_address'] : '';
$member_phone = isset($_POST['member_phone']) ? $_POST['member_phone'] : '';
//recommend_code

	if (($member_id != '') && ($member_pwd != '') && ($member_name != '') && ($member_address != '')) {
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
			$member_name  = mysqli_real_escape_string($link,$member_name);
			$member_address  = mysqli_real_escape_string($link,$member_address);
			if ($member_phone == "") $member_phone = $member_id;
			
			$sql = "SELECT * FROM member where member_trash=0 ";
			if ($member_id != "") {	
				$sql = $sql." and member_id='".$member_id."'";
			}
			if ($member_pwd != "") {	
				$sql = $sql." and member_pwd='".$member_pwd."'";
			}
			if ($result = mysqli_query($link, $sql)){
				if (mysqli_num_rows($result) > 0){
					// login ok
					// echo "[]";
					$mid=0;
					while($row = mysqli_fetch_array($result)){
						$mid = $row['mid'];
					}
					
					// register
					$sql="update member set member_name='$member_name',member_gender=$member_gender,member_email='$member_email',member_birthday='$member_birthday',member_address='$member_address',member_phone='$member_phone' where mid=$mid ;";

					//echo $sql;
					//exit;

					mysqli_query($link,$sql) or die(mysqli_error($link));
					
					//echo "user data change ok!";
					$data["status"]="true";
					$data["code"]="0x0200";
					$data["responseMessage"]="The user information has been updated successfully!";						
				}else{
					// $this->_response(null, 400, '帳號不存在');
					//echo "帳號不存在!";
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