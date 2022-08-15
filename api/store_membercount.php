<?php
ini_set('date.timezone','Asia/Taipei');

function getcount($conn,$sid,$item){
	try {
		
		$sql = "SELECT count(*) as member_count FROM membercard where membercard_trash=0 and store_id=".$sid."";

		$date2 = new DateTime(date("Y-m-d"));
		$edate = $date2->format('Y-m-d');
		switch ($item) {
		case "d":
			$sdate = $date2->format('Y-m-d');
			break;
		case "w":
			$date1 = $date2->modify('-7 day');
			$sdate = $date1->format('Y-m-d');
			break;
		case "m":
			$date1 = $date2->modify('-30 day');
			$sdate = $date1->format('Y-m-d');
			break;
		}
		
		if ($sdate != "") {	
			$sql = $sql." and membercard_created_at >= '".$sdate." 00:00:00'";
		}
		if ($edate != "") {	
			$sql = $sql." and membercard_created_at <= '".$edate." 23:59:59'";
		}

		//echo $sql;
		//exit;
		$member_count = 0;
		if ($result = mysqli_query($conn, $sql)){
			if (mysqli_num_rows($result) > 0){
				while($row = mysqli_fetch_array($result)){
					$member_count = $row['member_count'];
				}
			//
			}else {
				$member_count = 0;
			}
		}else {
			$member_count = 0;
		}
	} catch (Exception $e) {
		$member_count = 0;
	}	
	return $member_count;	
}
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';

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
			
			$sql = "SELECT * FROM member where member_trash=0 and member_type=2 ";
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
					$membersid=0;
					while($row = mysqli_fetch_array($result)){
						//$mid = $row['mid'];
						//$membername = $row['member_name'];
						$membersid = $row['member_sid'];
					}
					try {
						//$data = "";
						
							if ($membersid > 0){
								$rows = array();

									//$rows[] = $row2;
									//echo getservice($link,$row2['store_id'],$row2['service_item'])."<br/>";
									//a.rid,a.booking_no,a.store_id,a.hid, b.nick_name ,a.reserve_date, a.reserve_time, a.mid,a.member_id, a.member_name, a.service_item, a.reserve_status, a.reserve_created_at
									$daycount = getcount($link,$membersid,  'd');
									$weekcount = getcount($link,$membersid,  'w');
									$monthcount = getcount($link,$membersid,  'm');
									$data2 = [
										'0'       				=> $daycount, 
										'day'       			=> $daycount,   
										'1'       				=> $weekcount, 
										'week'       			=> $weekcount, 
										'2'   					=> $monthcount,   
										'month'   				=> $monthcount
									];
									array_push($rows, $data2);
							

								header('Content-Type: application/json');
								echo (json_encode($rows, JSON_UNESCAPED_UNICODE));
								exit;
							}else{
								$data["status"]="false";
								$data["code"]="0x0201";
								$data["responseMessage"]="This is no store member data!";									
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