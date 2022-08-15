<?php
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$sid = isset($_POST['sid']) ? $_POST['sid'] : '';
$booking_startdate = isset($_POST['booking_startdate']) ? $_POST['booking_startdate'] : '';
$booking_enddate = isset($_POST['booking_enddate']) ? $_POST['booking_enddate'] : '';

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
			$sid  = mysqli_real_escape_string($link,$sid);
			$booking_startdate  = mysqli_real_escape_string($link,$booking_startdate);
			$booking_enddate  = mysqli_real_escape_string($link,$booking_enddate);
			
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
						$memberid = $row['member_id'];
					}
					try {
					
						$sql2 = "SELECT a.rid,a.booking_no,a.store_id,a.hid, a.reserve_date, a.reserve_time, a.mid,a.member_id, a.member_name,a.member_email, a.service_item, a.reserve_remark, a.reserve_status, a.reserve_created_at,b.store_name,b.store_picture,c.nick_name from reserveinfo a ";
						$sql2 = $sql2." inner join ( select sid,store_name,store_picture,store_status from store where store_trash = 0 ) as b ON b.sid= a.store_id ";
						$sql2 = $sql2." left join ( select hid,nick_name,stylist_pic,hairstylist_status from hairstylist where hairstylist_trash = 0 ) as c ON a.hid= c.hid ";
						$sql2 = $sql2." where a.rid>0 and a.reserve_trash=0 ";
						if ($booking_startdate != "") {	
							$sql2 = $sql2." and a.reserve_date >= '".$booking_startdate." 00:00:00'";
						}
						if ($booking_enddate != "") {	
							$sql2 = $sql2." and a.reserve_date <= '".$booking_enddate." 23:59:59'";
						}
						if ($mid != 0) {	
							$sql2 = $sql2." and a.mid = ".$mid."";
						}
						if ($sid != "") {	
							$sql2 = $sql2." and a.store_id = ".$sid."";
						}						
						$sql2 = $sql2." order by a.store_id asc,a.reserve_date,a.reserve_time";

//echo $sql2;
						//$data = "";
						if ($result2 = mysqli_query($link, $sql2)){
							if (mysqli_num_rows($result2) > 0){
								$rows = array();
								while($row2 = mysqli_fetch_array($result2)){
									$rows[] = $row2;

									//booking_no,store_id,hid, reserve_date, reserve_time, mid,member_id, member_name, service_item, reserve_status
								}
								header('Content-Type: application/json');
								echo (json_encode($rows, JSON_UNESCAPED_UNICODE));
								exit;
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