<?php
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$bonus_startdate = isset($_POST['bonus_startdate']) ? $_POST['bonus_startdate'] : '';
$bonus_enddate = isset($_POST['bonus_enddate']) ? $_POST['bonus_enddate'] : '';

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
			$bonus_startdate  = mysqli_real_escape_string($link,$bonus_startdate);
			$bonus_enddate  = mysqli_real_escape_string($link,$bonus_enddate);
			
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
						$sql2 = "SELECT a.bid,a.member_id, a.order_no, a.bonus_date, a.bonus_type ,a.bonus, c.store_name from mybonus as a";		
						$sql2 = $sql2." inner join ( select order_no, store_id from orderinfo ) as b on a.order_no=b.order_no ";
						$sql2 = $sql2." inner join ( select sid, store_name from store ) as c on c.sid=b.store_id ";
						$sql2 = $sql2." where a.bid>0  ";
						if ($mid != "") {	
							$sql2 = $sql2." and a.member_id=".$mid."";
						}
						if ($bonus_startdate != "") {	
							$sql2 = $sql2." and a.bonus_date >= '".$bonus_startdate."'";
						}
						if ($bonus_enddate != "") {	
							$sql2 = $sql2." and a.bonus_date <= '".$bonus_enddate."'";
						}
						$sql2 = $sql2." GROUP BY a.bid";
						$sql2 = $sql2." order by a.bid,a.member_id,a.bonus_date desc";
						//$data = "";
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