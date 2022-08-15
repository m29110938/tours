<?php

$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';

$sid = isset($_POST['sid']) ? $_POST['sid'] : '0';
$hid = isset($_POST['hid']) ? $_POST['hid'] : '0';
$reserve_date = isset($_POST['reserve_date']) ? $_POST['reserve_date'] : '';
$reserve_time = isset($_POST['reserve_time']) ? $_POST['reserve_time'] : '';
//$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
//$member_name = isset($_POST['member_name']) ? $_POST['member_name'] : '';
$service_item = isset($_POST['service_item']) ? $_POST['service_item'] : '';
$reserve_remark = isset($_POST['reserve_remark']) ? $_POST['reserve_remark'] : '';

	if (($member_id != '') && ($member_pwd != '') && ($hid != '') && ($reserve_date != '') && ($reserve_time != '') && ($service_item != '')) {
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

			$sid  = mysqli_real_escape_string($link,$sid);
			$hid  = mysqli_real_escape_string($link,$hid);
			$reserve_date  = mysqli_real_escape_string($link,$reserve_date);
			$reserve_time  = mysqli_real_escape_string($link,$reserve_time);
			$reserve_remark  = mysqli_real_escape_string($link,$reserve_remark);
			//$member_id  = mysqli_real_escape_string($link,$member_id);
			//$member_name  = mysqli_real_escape_string($link,$member_name);
			$service_item  = mysqli_real_escape_string($link,$service_item);

			
			$sql = "SELECT * FROM member where member_trash=0 and member_type=1 ";
			if ($member_id != "") {	
				$sql = $sql." and member_id='".$member_id."'";
			}
			if ($member_pwd != "") {	
				$sql = $sql." and member_pwd='".$member_pwd."'";
			}
			if ($result = mysqli_query($link, $sql)){
				if (mysqli_num_rows($result) > 0){
					$mid=0;
					while($row = mysqli_fetch_array($result)){
						$mid = $row['mid'];
						$member_name = $row['member_name'];
						$member_id = $row['member_id'];
						$member_email = $row['member_email'];
					}	
					$date = date_create();
					$rand = sprintf("%04d", rand(0,9999));
					$booking_no = date_timestamp_get($date).$rand;					
					// add reserveinfo
					$sql="INSERT INTO reserveinfo (	booking_no,store_id,hid, reserve_date, reserve_time, mid,member_id, member_name,member_email, service_item, reserve_remark, reserve_status, reserve_created_at) VALUES";
					$sql=$sql."('$booking_no',$sid, $hid, '$reserve_date', '$reserve_time', $mid, '$member_id', '$member_name', '$member_email', '$service_item','$reserve_remark', 0, NOW());";

					//echo $sql;
					//exit;

					mysqli_query($link,$sql) or die(mysqli_error($link));
					
					//echo "coupon data change ok!";
					$data["status"]="true";
					$data["code"]="0x0200";
					$data["responseMessage"]=$booking_no;   //"The booking information has been created successfully!";						
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