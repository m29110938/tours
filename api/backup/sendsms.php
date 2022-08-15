<?php
$phone_no = isset($_POST['phone_no']) ? $_POST['phone_no'] : '';
$sms_data = isset($_POST['sms_data']) ? $_POST['sms_data'] : '';

	if (($phone_no != '') && ($sms_data != '')) {

		$cmd = "curl -X POST 'http://message.ttinet.com.tw/ensg/3lma236_online' --data 'id=3LMA236&pass=43bHJ2dA&pin=018008508556&telno=".$phone_no."&cont=".$sms_data."'";
		//$cmd = "curl -X POST 'https://face8.pakka.ai/api/v2/faceDetect' -H 'accept: application/json' -H 'Content-Type: multipart/form-data' -F 'api_key=".$key."' -F 'image_file=@".$target_file.";type=image/jpeg'";
		//echo $cmd;
		$result = shell_exec($cmd);
		echo $result; 

	}else{
		echo "API parameter is required!";
	}

?>