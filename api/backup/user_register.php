<?php

$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$member_name = isset($_POST['member_name']) ? $_POST['member_name'] : '';
$member_type = isset($_POST['member_type']) ? $_POST['member_type'] : '';
$recommand_code = isset($_POST['recommand_code']) ? $_POST['recommand_code'] : '';

	if (($member_id != '') && ($member_pwd != '') && ($member_name != '') && ($member_type != '') && ($recommand_code != '')) {
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
			$member_type  = mysqli_real_escape_string($link,$member_type);
			$recommand_code  = mysqli_real_escape_string($link,$recommand_code);
			
			$sql = "SELECT * FROM member where member_trash=0 ";
			if ($member_id != "") {	
				$sql = $sql." and member_id='".$member_id."'";
			}
			if ($result = mysqli_query($link, $sql)){
				if (mysqli_num_rows($result) > 0){
					// $this->_response(null, 400, '帳號已存在');
					//echo "帳號已存在!";
					$data["status"]="false";
					$data["code"]="0x0201";
					$data["responseMessage"]="This account has already been registered!";						
				}else{
					// register
					$sql="INSERT INTO member (member_id,member_pwd,member_name,member_type,recommand_code) VALUES ('$member_id', '$member_pwd', '$member_name',$member_type,'$recommand_code');";

					//echo $sql;
					//exit;

					mysqli_query($link,$sql) or die(mysqli_error($link));
					
					//echo "register ok!";
					$data["status"]="true";
					$data["code"]="0x0200";
					$data["responseMessage"]="This account is successfully registered!";						
					
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
		echo (json_encode($data, JSON_PRETTY_PRINT));
	}else{
		//echo "need mail and password!";
		$data["status"]="false";
		$data["code"]="0x0203";
		$data["responseMessage"]="API parameter is required!";
		header('Content-Type: application/json');
		echo (json_encode($data, JSON_PRETTY_PRINT));			
	}
?>