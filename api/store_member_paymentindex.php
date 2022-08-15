<?php
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$order_startdate = isset($_POST['order_startdate']) ? $_POST['order_startdate'] : '';
$order_enddate = isset($_POST['order_enddate']) ? $_POST['order_enddate'] : '';
$mid = isset($_POST['mid']) ? $_POST['mid'] : '';

//
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
			$order_startdate  = mysqli_real_escape_string($link,$order_startdate);
			$order_enddate  = mysqli_real_escape_string($link,$order_enddate);
			$mid  = mysqli_real_escape_string($link,$mid);
			
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
					// $mid=0;
					$member_sid = "";
					while($row = mysqli_fetch_array($result)){
						// $mid = $row['mid'];
						$member_sid = $row['member_sid'];
					}
					try {
						// 取得店家會員的消費紀錄
                        $sql2 = "SELECT b.store_name,a.order_pay,a.order_date FROM `orderinfo` as a";
                        $sql2 = $sql2." INNER JOIN (SELECT * FROM store) as b ON a.store_id = b.sid";
                        $sql2 = $sql2." WHERE b.sid = '".$member_sid."' and a.member_id = $mid";
						if ($order_startdate != "") {	
							$sql2 = $sql2." and a.order_date >= '".$order_startdate." 00:00:00'";
						}
						if ($order_enddate != "") {	
							$sql2 = $sql2." and a.order_date <= '".$order_enddate." 23:59:59'";
						}
						// $sql2 = "SELECT mid,member_name,member_phone,member_birthday,member_gender,member_email,member_address FROM `member`";
						// $sql2 = $sql2." WHERE mid = $mid";
						// echo $sql2;
						if ($result2 = mysqli_query($link, $sql2)){
							if (mysqli_num_rows($result2) > 0){
								$rows = array();
								while($row2 = mysqli_fetch_array($result2)){
									$rows[] = $row2;

								}
								header('Content-Type: application/json');
								echo (json_encode($rows, JSON_UNESCAPED_UNICODE));
								exit;
							}else{
								$data["status"]="false";
								$data["code"]="0x0201";
								$data["responseMessage"]="This is no bonus data!";									
							}
						}else{
							//echo "need mail and password!";
							$data["status"]="false";
							$data["code"]="0x0204";
							$data["responseMessage"]="SQL fail1!";								
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
				$data["responseMessage"]="SQL fail2!";					
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