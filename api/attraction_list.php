<?php

$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$shopping_area = isset($_POST['shopping_area']) ? $_POST['shopping_area'] : '';
$store_type = isset($_POST['store_type']) ? $_POST['store_type'] : '';

$loc_lat2 = isset($_POST['loc_lat']) ? $_POST['loc_lat'] : 0;
$loc_lng2 = isset($_POST['loc_lng']) ? $_POST['loc_lng'] : 0;

	if (($member_id != '') && ($member_pwd != '')) {
		//check 帳號/密碼
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';
		$loc_lat = 0.0;
		$loc_lng = 0.0;

		try {
			$link = mysqli_connect($host, $user, $passwd, $database);
			mysqli_query($link,"SET NAMES 'utf8'");

			$member_id  = mysqli_real_escape_string($link,$member_id);
			$member_pwd  = mysqli_real_escape_string($link,$member_pwd);
			$shopping_area  = mysqli_real_escape_string($link,$shopping_area);
			$store_type  = mysqli_real_escape_string($link,$store_type);
			$loc_lat2  = mysqli_real_escape_string($link,$loc_lat2);
			$loc_lng2  = mysqli_real_escape_string($link,$loc_lng2);
			$loc_lat = floatval($loc_lat2);
			$loc_lng = floatval($loc_lng2);
		   //echo "loc_lat:".$loc_lat;
		   //echo "loc_lng:".$loc_lng;			
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
						$membername = $row['member_name'];
					}
					try {
						//INSERT INTO `store` (`sid`, `store_id`, `store_type`, `store_name`, `shopping_area`, `store_phone`, `store_address`, `store_website`, `store_descript`, `store_picture`, `store_longitude`, `store_latitude`, `store_status`, `store_created_at`, `store_updated_at`, `store_created_by`, `store_updated_by`, `store_trash`) VALUES
					
						//$sql2 = "SELECT `sid`, `store_id`, `store_type`, `store_name`, `shopping_area`, `store_phone`, `store_address`, `store_website`, `store_descript`, `store_picture`, `store_longitude`, `store_latitude`, `store_status` FROM store where store_trash=0 ";
						$sql2 = "SELECT a.sid, a.store_id, a.store_type, a.store_name, a.shopping_area, a.store_phone, a.store_address, a.store_website, a.store_facebook,a.store_news,a.store_picture, a.store_latitude, a.store_longitude, a.store_status, a.store_opentime, a.store_descript FROM attraction as a";
						$sql2 = $sql2." where a.store_trash=0 ";
						if ($shopping_area != "") {	
							$sql2 = $sql2." and a.shopping_area=".$shopping_area."";
						}
						if ($store_type != "") {	
							$sql2 = $sql2." and a.store_type=".$store_type."";
						}		
						$sql2 = $sql2." and a.store_status=0";
						$sql2 = $sql2." order by a.shopping_area ASC, a.store_type,a.store_id";
						//$data = "";
						// echo $sql2;
						if ($result2 = mysqli_query($link, $sql2)){
							if (mysqli_num_rows($result2) > 0){
								$rows = array();
								while($row2 = mysqli_fetch_array($result2)){
									//$rows[] = $row2;
								    $mallLlatitude = floatval($row2['store_latitude']);
								    $mallLongitude = floatval($row2['store_longitude']);
									$c1= $row2['sid'];
									$c2= $row2['store_id'];
									$c3= $row2['store_type'];
									$c4= $row2['store_name'];
									$c5= $row2['shopping_area'];
									$c6= $row2['store_phone'];
									$c7= $row2['store_address'];
									$c8= $row2['store_website'];

									$c9= $row2['store_facebook'];
									$c10= $row2['store_news'];
									$c11= $row2['store_picture'];
									$c12= $row2['store_latitude'];
									$c13= $row2['store_longitude'];
									$c14= $row2['store_status'];
									$c15= $row2['store_opentime'];
									$c16= $row2['store_descript'];

									try {
										if (($mallLongitude != 0 )||($mallLlatitude !=0 )) {
										
											if (($loc_lat != 0)&&($loc_lng != 0)) {
												//24.701808378972547, 121.05745245332301
												//$loc_lat = 24.701808378972547;
												//$loc_lng = 121.05745245332301;	
												   //echo "loc_lat:".$loc_lat;
												   //echo "loc_lng:".$loc_lng;												
												$distance = (round(6378.138*2*asin(sqrt(pow(sin(($loc_lat*pi()/180-$mallLlatitude*pi()/180)/2),2)+cos($loc_lat*pi()/180)*cos($mallLlatitude * pi()/180)* pow(sin(($loc_lng*pi()/180-$mallLongitude*pi()/180)/2),2)))*1000));
												//$distance = getDistance($loc_lat, $loc_lng, $mallLlatitude, $mallLongitude);
											}else{  //25.066023528074158, 121.58103356188475 依德 GPS
												$loc_lat = 25.066023528074158;
												$loc_lng = 121.58103356188475;
												
												   //echo "loc_lat:".$loc_lat;
												   //echo "loc_lng:".$loc_lng;
												$distance = (round(6378.138*2*asin(sqrt(pow(sin(($loc_lat*pi()/180-$mallLlatitude*pi()/180)/2),2)+cos($loc_lat*pi()/180)*cos($mallLlatitude * pi()/180)* pow(sin(($loc_lng*pi()/180-$mallLongitude*pi()/180)/2),2)))*1000));
											}
										}else{
											$distance = 0;
										}
									} catch (Exception $e) {
										$distance = 0;						
									}							
									$rows[] = array("0"=> $c1 , "sid"=> $c1 , "1"=> $c2,"store_id"=> $c2, "2"=> $c3, "store_type"=> $c3,"3"=> $c4,"store_name"=> $c4, "4"=> $c5,"shopping_area"=> $c5, "5"=> $c6,"store_phone"=> $c6,"6"=> $c7,"store_address"=> $c7,"7"=> $c8,"store_website"=> $c8,"8"=> $c9,"store_facebook"=> $c9,"9"=> $c10,"store_news"=> $c10,"10"=> $c11,"store_picture"=> $c11,"11"=> $c12,"store_latitude"=> $c12,"12"=> $c13,"store_longitude"=> $c13,"13"=> $c14,"store_status"=> $c14,"14"=> $distance,"distance"=> $distance,"15"=> $c15,"store_opentime"=> $c15,"16"=> $c16,"store_descript"=> $c16);
									//$rows[] = array("0"=> $c1 , "sid"=> $c1 , "1"=> $c2,"store_id"=> $c2, "2"=> $c3, "store_type"=> $c3,"3"=> $c4,"store_name"=> $c4, "4"=> $distance,"distance"=> $distance);

									//`sid`, `store_id`, `store_type`, `store_name`, `shopping_area`, `store_phone`, `store_address`, `store_website`, `store_facebook`,`store_news`,`store_picture`, `store_latitude`, `store_longitude`, `store_status` 
								}
								
								usort($rows, function($a, $b) {
									return $a['distance'] <=> $b['distance'];
								});
								
								header('Content-Type: application/json');
								echo (json_encode($rows, JSON_UNESCAPED_UNICODE));
								exit;
							}else{
								$data["status"]="false";
								$data["code"]="0x0201";
								$data["responseMessage"]="This is no store data!";									
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