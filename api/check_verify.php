<?php
$mobile_no = isset($_POST['mobile_no']) ? $_POST['mobile_no'] : '';
$vcode = isset($_POST['vcode']) ? $_POST['vcode'] : '';

//
	if (($mobile_no != '') && ($vcode != '')) {
		//check 帳號/密碼
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';

		try {
			$link = mysqli_connect($host, $user, $passwd, $database);
			mysqli_query($link,"SET NAMES 'utf8'");

			$mobile_no  = mysqli_real_escape_string($link,$mobile_no);
			$vcode  = mysqli_real_escape_string($link,$vcode);

			$sql = "SELECT * FROM verifymobile where rid>0 and mobile_no='".$mobile_no."'";

			if ($result = mysqli_query($link, $sql)){
				if (mysqli_num_rows($result) > 0){
					// login ok
					$verify_code="";
					while($row = mysqli_fetch_array($result)){
						$verify_code = $row['verify_code'];
						break;
					}

					if ($verify_code == $vcode){
						
						$sql="update member set member_status='1' where member_id='$mobile_no'";
						mysqli_query($link,$sql) or die(mysqli_error($link));
						
						$data["status"]="true";
						$data["code"]="0x0200";
						$data["responseMessage"]="This verify code is correctly!";	
					}else{
						$data["status"]="false";
						$data["code"]="0x0201";
						$data["responseMessage"]="This verify code is wrong!";							
					}

				}else{
					$data["status"]="false";
					$data["code"]="0x0205";
					$data["responseMessage"]="The mobile number is wrong!";						
				}
			}else {
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
		//echo "參數錯誤 !";
		$data["status"]="false";
		$data["code"]="0x0203";
		$data["responseMessage"]="API parameter is required!";
		header('Content-Type: application/json');
		echo (json_encode($data, JSON_UNESCAPED_UNICODE));		
	}
?>