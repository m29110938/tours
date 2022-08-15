<?php
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$mid = isset($_POST['mid']) ? $_POST['mid'] : '';

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
                        $sql1 = "SELECT SUM(a.order_amount) as totla_amount FROM `orderinfo` as a";
                        $sql1 = $sql1." INNER JOIN (SELECT * FROM store) as b ON a.store_id = b.sid";
                        $sql1 = $sql1." WHERE b.sid = '".$member_sid."' and a.member_id = $mid";
						// echo $sql1;
						if ($result1 = mysqli_query($link, $sql1)){
							if (mysqli_num_rows($result1) > 0){
								$rows = array();
								while($row1 = mysqli_fetch_array($result1)){
									//$rows[] = $row2;
									if ($row1['totla_amount'] == null ){	
										$totla_amount = 0;
									}else{	
										$totla_amount = $row1['totla_amount'];	
									}
								}
							}else{
								$totla_amount = 0;									
							}
						}	

                        $sql2 = "SELECT count(a.order_date) as total_count FROM `orderinfo` as a";
                        $sql2 = $sql2." INNER JOIN (SELECT * FROM store) as b ON a.store_id = b.sid";
                        $sql2 = $sql2." WHERE b.store_name = '".$membername."' and a.member_id = $mid";
                        // echo $sql2;
						//$data = "";
						if ($result2 = mysqli_query($link, $sql2)){
							if (mysqli_num_rows($result2) > 0){
								$rows = array();
								//$i=0;
								while($row2 = mysqli_fetch_array($result2)){
									//$rows[] = $row2;
									$total_count= $row2['total_count'];
			
									$rows[] = array("0"=> $mid , "mid"=> $mid , "1"=> $totla_amount,"totla_amount"=> $totla_amount, "2"=> $total_count, "total_count"=> $total_count);
								}
	
								header('Content-Type: application/json');
								echo (json_encode($rows, JSON_UNESCAPED_UNICODE));
								exit;
							}else{
								$data["status"]="false";
								$data["code"]="0x0201";
								$data["responseMessage"]="This is no member data!";									
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