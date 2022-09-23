<?php

$mobile_no = isset($_POST['mobile_no']) ? $_POST['mobile_no'] : '';

	if ($mobile_no != '') {
		//check 帳號/密碼
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';

		try {
			$link = mysqli_connect($host, $user, $passwd, $database);
			mysqli_query($link,"SET NAMES 'utf8'");

			$user_code=randomkeys(4);
			$smsdata = "點點APP[手機驗證簡訊],你的驗證碼為:".$user_code;   

			//$uriBase2 = 'http://211.20.185.2/tours/api/sendsms.php';
			//$fields2 = [
			//	'phone_no'         => $mobile_no,
			//	'sms_data'         => $smsdata
			//];
			//$fields_string2 = http_build_query($fields2);	
			//$ch2 = curl_init();
			//curl_setopt($ch2,CURLOPT_URL, $uriBase2);
			//curl_setopt($ch2,CURLOPT_POST, true);
			//curl_setopt($ch2,CURLOPT_POSTFIELDS, $fields_string2);
			//curl_setopt($ch2,CURLOPT_RETURNTRANSFER, true); 
			//execute post
			// $result2 = curl_exec($ch2);
			//echo $result2;
//-----------------------------------------------------------------------------------------------------------------------------------

			if (($mobile_no != '') && ($smsdata != '')) {

				$cmd = "curl -X POST 'http://message.ttinet.com.tw/ensg/3lma236_online' --data 'id=3LMA236&pass=43bHJ2dA&pin=018008508556&telno=".$mobile_no."&cont=".$smsdata."'";
				//echo $cmd;
				$result = shell_exec($cmd);
				
				$sql3 = "delete from sendmsg_response where mobile_no='".$mobile_no."'";
				mysqli_query($link,$sql3) or die(mysqli_error($link));
				
				$sql3 = "insert into sendmsg_response(mobile_no,response) value ('".$mobile_no."','".$result."')";
				mysqli_query($link,$sql3) or die(mysqli_error($link));

				// echo $result; 
				try {
					//$sql2 = "update member set reset_code='".$user_code."' ,member_updated_at=NOW() where member_trash=0 and mid=".$mid."";

					$sql2 = "delete from verifymobile where mobile_no='".$mobile_no."'";
					mysqli_query($link,$sql2) or die(mysqli_error($link));
					
					$sql2 = "insert into verifymobile(mobile_no,verify_code) value ('".$mobile_no."','".$user_code."')";
					mysqli_query($link,$sql2) or die(mysqli_error($link));
					
					$data["status"]="true";
					$data["code"]="0x0200";
					$data["responseMessage"]="The verify sms already send!";							
				} catch (Exception $e) {
					//$this->_response(null, 401, $e->getMessage());
					//echo $e->getMessage();
					$data["status"]="false";
					$data["code"]="0x0202";
					$data["responseMessage"]=$e->getMessage();				
				}
			}else{
				$data["status"]="false";
				$data["code"]="0x0203";
				$data["responseMessage"]="API parameter is required!";
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
	
	function randomkeys($length){
	//$pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
	$pattern = "1234567890";
	$key = "";
	for($i=0;$i<$length;$i++){
		$key .= $pattern[rand(0,9)];
	}
	return $key;
	}		
?>