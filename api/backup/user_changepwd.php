<?php

$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$old_password = isset($_POST['old_password']) ? $_POST['old_password'] : '';
$new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';

	if (($member_id != '') && ($old_password != '') && ($new_password != '')) {
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
			$old_password  = mysqli_real_escape_string($link,$old_password);
			$new_password  = mysqli_real_escape_string($link,$new_password);
			
			$sql = "SELECT * FROM member where member_trash=0 ";
			if ($member_id != "") {	
				$sql = $sql." and member_id='".$member_id."'";
			}
			if ($old_password != "") {	
				$sql = $sql." and member_pwd='".$old_password."'";
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
					}
					$sql2="update member set member_pwd='$new_password',member_updated_at=NOW() where mid=".$mid." ;";

					//echo $sql;
					//exit;
					mysqli_query($link,$sql2) or die(mysqli_error($link));	
					//echo "new password is applyed!";
					$data["status"]="true";
					$data["code"]="0x0200";
					$data["responseMessage"]="Change password success!";					
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