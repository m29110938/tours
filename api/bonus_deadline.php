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
					$mid=0;
					while($row = mysqli_fetch_array($result)){
						$mid = $row['mid'];
						//$membername = $row['member_name'];
					}
					try {
		
						//echo mysqli_affected_rows($link);
						//exit;
						//$sql2 = "SELECT bid,member_id, order_no, bonus_date, bonus_type ,bonus from mybonus where bid>0 ";	
                        
						$sql4 = "SELECT bonus_end_date FROM `orderinfo` ";
						$sql4 = $sql4." WHERE bonus_end_date IS NOT NULL AND bonus_end_date > NOW() and member_id=$mid";
						$sql4 = $sql4." GROUP BY bonus_end_date";
						$sql4 = $sql4." ORDER BY bonus_end_date ASC";
						$sql4 = $sql4." LIMIT 1";
						$result4 = mysqli_query($link, $sql4);
						// echo $sql4;
						$row4 = mysqli_fetch_array($result4);
						$bonusEndDate = $row4['bonus_end_date'];
						$bonusEndDate = date("Y-m-d",strtotime("+0 day",strtotime($bonusEndDate)));
						$bonusEndDate1 = date("Y-m-d",strtotime("+1 day",strtotime($bonusEndDate)));
						$bonusEndDate2 = date("Y-m-d",strtotime("-1 year",strtotime($bonusEndDate1)));
						// echo $bonusEndDate."</br>";
						// echo $bonusEndDate2."</br>";



                        $sql2 = "SELECT member_id, SUM(bonus_point) as bonusPoint FROM `orderinfo`";
                        $sql2 = $sql2." WHERE order_status=1 and pay_status=1 and bonus_end_date >= NOW()";
                        // $sql2 = $sql2." WHERE bonus_end_date IS NOT NULL AND bonus_end_date > NOW()";
                        if ($mid != "") {
                            $sql2 = $sql2." and member_id=".$mid."";
                        }
                        // $sql2 = $sql2." GROUP BY bonus_end_date";
                        // $sql2 = $sql2." ORDER BY bonus_end_date ASC";
                        // $sql2 = $sql2." LIMIT 1";
						$result2 = mysqli_query($link, $sql2);
						// echo $sql3;
						$row2 = mysqli_fetch_array($result2);
						$bonusPoint = $row2['bonusPoint'];
						

						$sql3 = "SELECT bonus_end_date,SUM(bonus_get)-$bonusPoint as totalBonus FROM `orderinfo` ";
						$sql3 = $sql3." WHERE DATE(bonus_end_date)=DATE('".$bonusEndDate."') and member_id=$mid";
						$sql3 = $sql3." GROUP BY bonus_end_date";
						
						if ($result3 = mysqli_query($link, $sql3)){
							if (mysqli_num_rows($result3) > 0){
								$rows = array();
								while($row3 = mysqli_fetch_array($result3)){
									if ($row3['totalBonus']<0){
										$row3['totalBonus'] = "0";
										$row3['1'] = "0";
									}
									$rows[] = $row3;

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
							// echo "need mail and password!";
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