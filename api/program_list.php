<?php
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$cid = isset($_POST['cid']) ? $_POST['cid'] : '';

	if (($member_id != '') && ($member_pwd != '') && ($cid != '')) {
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
			$cid  = mysqli_real_escape_string($link,$cid);
			//$order_startdate  = mysqli_real_escape_string($link,$order_startdate);
			//$order_enddate  = mysqli_real_escape_string($link,$order_enddate);
			
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
					//$membersid=0;
					$mid = 0;
					while($row = mysqli_fetch_array($result)){
						$mid = $row['mid'];
						$membername = $row['member_name'];
						//$membersid = $row['member_sid'];
					}
					try {
						//hid,store_id,nick_name,stylist_pic,service_code,hairstylist_date,hairstylist_trash
						$sql2 = "SELECT a.pid,a.cid,b.store_id,c.store_name,c.store_address,c.store_phone,b.class_name,b.class_descript,b.class_picture,program_code,program_name,program_descript,program_time,program_price,program_limit,program_status,program_trash FROM `program` as a ";
						$sql2 = $sql2." inner join ( select cid,store_id,class_name,class_descript,class_picture,class_status,class_trash from class) as b ON a.cid= b.cid ";
						$sql2 = $sql2." inner join ( select sid,store_name,store_address,store_phone,store_trash from store) as c ON b.store_id= c.sid ";
						$sql2 = $sql2." where a.pid>0 and a.program_trash=0 and b.class_trash=0 and c.store_trash=0 ";
						if ($cid != "") {	
							$sql2 = $sql2." and a.cid = ".$cid."";
						}
	
						$sql2 = $sql2." order by program_code,program_name asc";

//echo $sql2;

						//$data = "";
						if ($result2 = mysqli_query($link, $sql2)){
							if (mysqli_num_rows($result2) > 0){
								$rows = array();
								while($row2 = mysqli_fetch_array($result2)){
									$rows[] = $row2;

									//cid,store_id,nick_name,stylist_pic,service_code,hairstylist_date
								}
								header('Content-Type: application/json');
								echo (json_encode($rows, JSON_UNESCAPED_UNICODE));
								exit;
							}else{
								$data["status"]="false";
								$data["code"]="0x0201";
								$data["responseMessage"]="This is no program data!";									
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