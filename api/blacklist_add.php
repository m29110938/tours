<?php

$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';

$m_id = isset($_POST['m_id']) ? $_POST['m_id'] : '';
$member_name = isset($_POST['member_name']) ? $_POST['member_name'] : '';

$reason = isset($_POST['reason']) ? $_POST['reason'] : '';
//$blacklist_date = isset($_POST['blacklist_date']) ? $_POST['blacklist_date'] : '';

	if (($member_id != '') && ($member_pwd != '') && ($m_id != '')) {
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
			
			$m_id  = mysqli_real_escape_string($link,$m_id);
			$member_name  = mysqli_real_escape_string($link,$member_name);
			$reason  = mysqli_real_escape_string($link,$reason);
			//$blacklist_date  = mysqli_real_escape_string($link,$blacklist_date);

			
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

						$sql = "SELECT * FROM blacklist where blacklist_trash=0 and blacklist_status=0 ";
						if ($membersid != "") {	
							$sql = $sql." and store_id=".$membersid."";
						}
						if ($m_id != "") {	
							$sql = $sql." and member_id=".$m_id."";
						}
						if ($result = mysqli_query($link, $sql)){
							if (mysqli_num_rows($result) == 0){
								// add blacklist store_id	member_id	member_name	reason	blacklist_date	blacklist_status	blacklist_created_at	blacklist_created_by	blacklist_trash
								$sql="INSERT INTO blacklist (store_id,member_id,member_name,reason,blacklist_date,blacklist_status,blacklist_created_at) VALUES";
								$sql=$sql."($membersid, $m_id, '$member_name', '$reason',NOW(), 0, NOW());";

								mysqli_query($link,$sql) or die(mysqli_error($link));
							
								//echo "coupon data change ok!";
								$data["status"]="true";
								$data["code"]="0x0200";
								$data["responseMessage"]="The blacklist information has been added successfully!";	
							}else{
								$data["status"]="false";
								$data["code"]="0x0201";
								$data["responseMessage"]="The member is exist in the blacklist of this store!";	
								
							}
						}else {
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