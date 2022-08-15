<?php
// include("db_tools.php");

$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
	
	if (($member_id != '') && ($member_pwd != ''))  // && ($recommend_code != '')
	{
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';
		try {
			$link = mysqli_connect($host, $user, $passwd, $database);
			mysqli_query($link,"SET NAMES 'utf8'");

			$member_id  = mysqli_real_escape_string($link,$member_id);
			$member_pwd  = mysqli_real_escape_string($link,$member_pwd);

			$mid=0;
			$sql = "SELECT * FROM member where member_trash=0 ";
			if ($member_id != "") {	
				$sql = $sql." and member_id='".$member_id."'";
			}
			//if ($member_pwd != "") {	
			//	$sql = $sql." and member_pwd='".$member_pwd."'";
			//}
			
			if ($result2 = mysqli_query($link, $sql)){
				if (mysqli_num_rows($result2) > 0){
					while($row2 = mysqli_fetch_array($result2)){
						$mid = $row2['mid'];
						$member_pwd2 = $row2['member_pwd'];
						break;
					}
					if ($member_pwd == $member_pwd2) {	
						$sql2="update member set member_trash=1,member_updated_at=NOW() where mid=$mid";
						mysqli_query($link,$sql2) or die(mysqli_error($link));	

						$data["status"]="true";
						$data["code"]="0x0200";
						$data["responseMessage"]="This account is successfully unregistered!";
					}else{
						// register
						$data["status"]="false";
						$data["code"]="0x0205";
						$data["responseMessage"]="密碼錯誤,取消帳號失敗!";	
					}
				}else{
					// register
					$data["status"]="false";
					$data["code"]="0x0201";
					$data["responseMessage"]="此號碼未被註冊過!";	
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