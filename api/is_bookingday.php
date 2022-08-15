<?php
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$hid = isset($_POST['hid']) ? $_POST['hid'] : '';
$reserve_date = isset($_POST['reserve_date']) ? $_POST['reserve_date'] : '';

//
	if (($member_id != '') && ($member_pwd != '') && ($hid != '') && ($reserve_date != '' )) {
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
			//$shopping_area  = mysqli_real_escape_string($link,$shopping_area);
			//$store_type  = mysqli_real_escape_string($link,$store_type);
			
			$sql = "SELECT * FROM member where member_trash=0  and member_type=1 ";
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
						//$membername = $row['member_name'];
					}
					try {

						$sql2 = "SELECT * FROM reserveinfo ";
			
						$sql2 = $sql2." where rid > 0 and reserve_trash=0 ";
						if ($hid != "") {	
							$sql2 = $sql2." and hid=".$hid."";
						}
						if ($reserve_date != "") {	
							$sql2 = $sql2." and reserve_date='".$reserve_date."'";
						}
						$sql2 = $sql2." order by reserve_time ";
//echo $sql2;
//exit;				
						$timeperiod = array();
						if ($result2 = mysqli_query($link, $sql2)){
							if (mysqli_num_rows($result2) > 0){
								$data2 = array();
								while($row2 = mysqli_fetch_array($result2)){
									$reserve_time = $row2['reserve_time'];
									$data2 = [
										'rid'    => $row2['rid'],   
										'booking_no'    => $row2['booking_no'],   
										'reserve_time'   => $reserve_time
									];									
									array_push($timeperiod, $data2);
								}
								$data["status"]="true";
								$data["code"]="0x0200";
								$data["responseMessage"]=$timeperiod;	
							}else{
								$data["status"]="false";
								$data["code"]="0x0201";
								$data["responseMessage"]="No appointment has been made for this day!";	
							}
						}else{
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