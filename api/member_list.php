<?php
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$order_startdate = isset($_POST['order_startdate']) ? $_POST['order_startdate'] : '';
$order_enddate = isset($_POST['order_enddate']) ? $_POST['order_enddate'] : '';

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

						// 取得店家有的會員資料
						$sql2 = "SELECT a.store_id,a.member_id as mid,a.member_date,c.member_name FROM `membercard` as a";
						$sql2 = $sql2." INNER JOIN (SELECT * FROM store) as b ON a.store_id = b.sid";
						$sql2 = $sql2." INNER JOIN (SELECT * FROM member) as c ON a.member_id = c.mid";
						$sql2 = $sql2." WHERE c.member_trash=0 and a.membercard_trash=0 and b.sid = '".$member_sid."'";
						
						if ($order_startdate != "") {	
							$sql2 = $sql2." and a.member_date >= '".$order_startdate." 00:00:00'";
						}
						if ($order_enddate != "") {	
							$sql2 = $sql2." and a.member_date <= '".$order_enddate." 23:59:59'";
						}
						$sql2 = $sql2." ORDER BY a.member_date DESC";
						// echo $sql2;
						if ($result2 = mysqli_query($link, $sql2)){
							if (mysqli_num_rows($result2) > 0){
								$rows = array();
								while($row2 = mysqli_fetch_array($result2)){


									$member_name = $row2['member_name'];
									// $member_namestr1 = strstr($member_name,0,3);
									$member_namelen = mb_strlen($member_name,`utf-8`);
									if($member_namelen <= 2){
										$member_namestr1 = mb_substr($member_name,0,1,`utf-8`);
										$member_namestr2 = mb_substr($member_name,2,`utf-8`);
										$member_name = $member_namestr1."O".$member_namestr2;
									}
									else{
										$member_namestr1 = mb_substr($member_name,0,1,`utf-8`);
										$member_namestr2 = mb_substr($member_name,3,`utf-8`);
										$member_name = $member_namestr1."OO".$member_namestr2;
									}
									// $row2['3'] = $member_name;
									// echo $member_name;
									$row2['member_name'] = $member_name;

									$rows[] = $row2;

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