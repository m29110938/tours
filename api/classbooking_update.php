<?php
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
//$sid = isset($_POST['sid']) ? $_POST['sid'] : '';
$booking_no = isset($_POST['booking_no']) ? $_POST['booking_no'] : '';

$reserve_date = isset($_POST['reserve_date']) ? $_POST['reserve_date'] : '';
$reserve_time = isset($_POST['reserve_time']) ? $_POST['reserve_time'] : '';
$reserve_remark = isset($_POST['reserve_remark']) ? $_POST['reserve_remark'] : '';
$reserve_status = isset($_POST['reserve_status']) ? $_POST['reserve_status'] : '';
$program_person = isset($_POST['program_person']) ? $_POST['program_person'] : '0';

	if (($member_id != '') && ($member_pwd != '') && ($booking_no != '')) {
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
			//$sid  = mysqli_real_escape_string($link,$sid);
			$booking_no  = mysqli_real_escape_string($link,$booking_no);
			$reserve_date  = mysqli_real_escape_string($link,$reserve_date);
			$reserve_time  = mysqli_real_escape_string($link,$reserve_time);
			$reserve_remark  = mysqli_real_escape_string($link,$reserve_remark);
			$reserve_status  = mysqli_real_escape_string($link,$reserve_status);
			$program_person  = mysqli_real_escape_string($link,$program_person);
			
			if ($program_person == "") $program_person="0";
			
			$sql = "SELECT * FROM member where member_trash=0 and member_type=1 ";
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
					$mid=0;
					while($row = mysqli_fetch_array($result)){
						$mid = $row['mid'];
						$membername = $row['member_name'];
					}
					try {
					
						$sql2 = "SELECT * FROM class_reserveinfo where rid>0 ";
						$sql2 = $sql2." and reserve_trash=0 and booking_no='$booking_no' and mid=".$mid;
						//echo $sql2;
						//exit;
						//$data = "";
						if ($result2 = mysqli_query($link, $sql2)){
							if (mysqli_num_rows($result2) > 0){
								$rows = array();
								while($row2 = mysqli_fetch_array($result2)){
									$rid = $row2['rid'];
								}
								
								$sql3="update class_reserveinfo set reserve_status=$reserve_status ";
								if ($program_person != "0") {	
									$sql3 = $sql3." , program_person=".$program_person."";
								}
								if ($reserve_date != "") {	
									$sql3 = $sql3." , reserve_date='".$reserve_date."'";
								}
								if ($reserve_time != "") {	
									$sql3 = $sql3." , reserve_time='".$reserve_time."'";
								}
								if ($reserve_remark != "") {	
									$sql3 = $sql3." , reserve_remark='".$reserve_remark."'";
								}
								$sql3=$sql3." where rid=".$rid;
								//echo $sql3;
								mysqli_query($link,$sql3) or die(mysqli_error($link));

								$data["status"]="true";
								$data["code"]="0x0200";
								$data["responseMessage"]="The booking information have been updated!";
							}else{
								$data["status"]="false";
								$data["code"]="0x0201";
								$data["responseMessage"]="This is no booking data!";									
							}
						}else{
							//echo "need mail and password!";
							$data["status"]="false";
							$data["code"]="0x0204";
							$data["responseMessage"]="SQL fail!";								
						}
					} catch (Exception $e) {
						//$this->_response(null, 401, $e->getMessage());
						//echo $e->getMessage();
						$data["status"]="false";
						$data["code"]="0x0202";
						$data["responseMessage"]=$e->getMessage();							
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