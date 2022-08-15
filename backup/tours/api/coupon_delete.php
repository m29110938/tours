<?php
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$coupon_id = isset($_POST['coupon_id']) ? $_POST['coupon_id'] : '';

	if (($member_id != '') && ($member_pwd != '') && ($coupon_id != '')) {
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

			$coupon_id  = mysqli_real_escape_string($link,$coupon_id);

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
					$sql="update coupon set coupon_trash=1,coupon_updated_at=NOW(),coupon_updated_by='$mid' where cid=$coupon_id ;";

					//echo $sql;
					//exit;

					mysqli_query($link,$sql) or die(mysqli_error($link));
					
					//echo "coupon data change ok!";
					$data["status"]="true";
					$data["code"]="0x0200";
					$data["responseMessage"]="The coupon has been removed!";						
				}else{
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
			$data["status"]="false";
			$data["code"]="0x0202";
			$data["responseMessage"]=$e->getMessage();				
        }
		header('Content-Type: application/json');
		echo (json_encode($data, JSON_UNESCAPED_UNICODE));			
	}else{
		$data["status"]="false";
		$data["code"]="0x0203";
		$data["responseMessage"]="API parameter is required!";
		header('Content-Type: application/json');
		echo (json_encode($data, JSON_UNESCAPED_UNICODE));		
	}
?>