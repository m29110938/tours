<?php
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$store_id = isset($_POST['store_id']) ? $_POST['store_id'] : '';

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
			//$shopping_area  = mysqli_real_escape_string($link,$shopping_area);
			//$store_type  = mysqli_real_escape_string($link,$store_type);
			
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

						$sql2 = "SELECT a.member_id,b.sid,b.store_id,a.member_date,a.card_type,b.store_name, b.store_picture FROM membercard as a ";
						$sql2 = $sql2." inner join ( select sid,store_id,store_name,store_picture from store) as b ON a.store_id= b.sid ";
						//$sql = $sql." inner join ( select aid,shopping_area from shopping_area) c on a.shopping_area = c.aid ";
						
						$sql2 = $sql2." where a.membercard_trash=0 and a.member_id=$mid";
						$sql2 = $sql2." and b.store_id='$store_id'";
//echo $sql2;
//exit;
						//$data = "";
						//$sql2 = "select a.* from membercard where membercard_trash=0 and member_id=$mid";
						if ($result2 = mysqli_query($link, $sql2)){
							if (mysqli_num_rows($result2) > 0){
								$rows2 = array();
								while($row2 = mysqli_fetch_array($result2)){
									$rows[] = $row2;

									//banner_subject, banner_date, banner_enddate, banner_descript	,banner_picture,banner_link
								}
								header('Content-Type: application/json');
								echo (json_encode($rows, JSON_UNESCAPED_UNICODE));
								exit;
							}else{
								
								$sql3 = "select `sid`, `store_id`, `store_type`, `store_name`, `shopping_area`, `store_phone`, `store_address`, `store_website`, `store_facebook`,`store_news`,`store_descript`, `store_opentime`, `store_picture`, `store_latitude`, `store_longitude`, `store_status` from store where store_trash=0 and store_id='$store_id'";
								
								if ($result3 = mysqli_query($link, $sql3)){
									if (mysqli_num_rows($result3) > 0){
										$rows3 = array();
										while($row3 = mysqli_fetch_array($result3)){
											$rows[] = $row3;

											//banner_subject, banner_date, banner_enddate, banner_descript	,banner_picture,banner_link
										}
										$data["status"]="false";
										$data["code"]="0x0201";
										$data["responseMessage"]="This user is not the member of the store!";
										$data["storeinfo"]=json_encode($rows, JSON_UNESCAPED_UNICODE);									
									}else{
										$data["status"]="false";
										$data["code"]="0x0206";
										$data["responseMessage"]="This is no store data!";										
									}
								}else{
									$data["status"]="false";
									$data["code"]="0x0204";
									$data["responseMessage"]="SQL fail!";															
								}
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