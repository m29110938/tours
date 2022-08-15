<?php
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';

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
						//$membersid = $row['member_sid'];
					}
					$date2 = new DateTime(date("Y-m-d"));
					//echo $date2->format('Y-m-d') . "\n";					
					$sql2 = "SELECT sum(bonus_get) as bonuswillget FROM orderinfo where order_status=1 and pay_status=1 ";
					$sql2 = $sql2." and bonus_date > '".$date2->format('Y-m-d')." 23:59:59'";
					//$sql2 = $sql2." and bonus_date > '2022-01-25 23:59:59'";
					if ($mid != "") {	
						$sql2 = $sql2." and member_id='".$mid."'";
					}
					//echo $sql2;
					if ($result2 = mysqli_query($link, $sql2)){
						if (mysqli_num_rows($result2) > 0){
							$rows = array();
							while($row2 = mysqli_fetch_array($result2)){
								//$rows[] = $row2;
								if ($row2['bonuswillget'] == null ){	
									$bonuswillget = 0;
								}else{	
									$bonuswillget = $row2['bonuswillget'];	
								}
									
								$data2 = [
									'0'    					=> $bonuswillget,   
									'bonuswillget'    		=> $bonuswillget  
								];
							}
							//$data["status"]="true";
							//$data["code"]="0x0200";
							//$data["responseMessage"]=$data2;  //$timeperiod;	
							$data = $data2;						
							//header('Content-Type: application/json');
							//echo (json_encode($rows, JSON_UNESCAPED_UNICODE));
							//exit;
						}else{
							$data["status"]="false";
							$data["code"]="0x0201";
							$data["responseMessage"]="This member id is wrong!";									
						}
					}else{
						$data["status"]="false";
						$data["code"]="0x0204";
						$data["responseMessage"]="SQL fail!";							
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