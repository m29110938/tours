<?php

$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';

$loc_lat1 = isset($_POST['loc_lat1']) ? $_POST['loc_lat1'] : 0;
$loc_lng1 = isset($_POST['loc_lng1']) ? $_POST['loc_lng1'] : 0;
$loc_lat2 = isset($_POST['loc_lat2']) ? $_POST['loc_lat2'] : 0;
$loc_lng2 = isset($_POST['loc_lng2']) ? $_POST['loc_lng2'] : 0;

	if (($member_id != '') && ($member_pwd != '')) {
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

			$loc_lat1  = mysqli_real_escape_string($link,$loc_lat1);
			$loc_lng1  = mysqli_real_escape_string($link,$loc_lng1);
			$loc_lat2  = mysqli_real_escape_string($link,$loc_lat2);
			$loc_lng2  = mysqli_real_escape_string($link,$loc_lng2);

			$cloc_lat1 = floatval($loc_lat1);
			$cloc_lng1 = floatval($loc_lng1);
			
			$cloc_lat2 = floatval($loc_lat2);
			$cloc_lng2 = floatval($loc_lng2);
			
			//$loc_lat = 25.066023528074158;
			//$loc_lng = 121.58103356188475;		
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
					// user id 取得
					

					try {

						//$distance = (round(6378.138*2*asin(sqrt(pow(sin(($loc_lat*pi()/180-$mallLlatitude*pi()/180)/2),2)+cos($loc_lat*pi()/180)*cos($mallLlatitude * pi()/180)* pow(sin(($loc_lng*pi()/180-$mallLongitude*pi()/180)/2),2)))*1000));
						$distance = (round(6378.138*2*asin(sqrt(pow(sin(($cloc_lat1*pi()/180-$cloc_lat2*pi()/180)/2),2)+cos($cloc_lat1*pi()/180)*cos($cloc_lat2 * pi()/180)* pow(sin(($cloc_lng1*pi()/180-$cloc_lng2*pi()/180)/2),2)))*1000));
						$data["status"]="true";
						$data["code"]="0x0200";
						$data["responseMessage"]=$distance;	
					} catch (Exception $e) {
						$data["status"]="false";
						$data["code"]="0x0201";
						$data["responseMessage"]="計算錯誤,".$e->getMessage();							
					}					
				}else{
					$data["status"]="false";
					$data["code"]="0x0205";
					$data["responseMessage"]="ID or password is wrong!";						
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