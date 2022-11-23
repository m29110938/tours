<?php

$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$sid = isset($_POST['sid']) ? $_POST['sid'] : '0';

	if (($member_id != '') && ($member_pwd != '') && ($sid != '') ) {
		//check 帳號/密碼
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';
		
		//echo $sql;
		//exit;
		try {
			$link = mysqli_connect($host, $user, $passwd, $database);
			mysqli_query($link,"SET NAMES 'utf8'");

			$member_id  = mysqli_real_escape_string($link,$member_id);
			$member_pwd  = mysqli_real_escape_string($link,$member_pwd);
			$sid  = mysqli_real_escape_string($link,$sid);
			
			$sql = "SELECT * FROM member where member_trash=0 ";
			if ($member_id != "") {	
				$sql = $sql." and member_id='".$member_id."'";
			}
			if ($member_pwd != "") {	
				$sql = $sql." and member_pwd='".$member_pwd."'";
			}
			if ($result = mysqli_query($link, $sql)){
				if (mysqli_num_rows($result) > 0){
					$mid=0;
					while($row = mysqli_fetch_array($result)){
						$mid = $row['mid'];
						//$membername = $row['member_name'];
					}
					try {

						$sql2 = "SELECT * FROM membercard where membercard_trash=0 and member_id=$mid and store_id=$sid";
						if ($result2 = mysqli_query($link, $sql2)){
							if (mysqli_num_rows($result2) > 0){
									//echo "已經是該店會員!";
									$sql3 = "SELECT pid,mid, coupon_no, using_flag, using_date, coupon_id ,coupon_name, coupon_type, coupon_description, coupon_startdate, coupon_enddate, coupon_status, coupon_rule, coupon_discount, discount_amount, coupon_storeid, coupon_for, coupon_picture from mycoupon where pid>0 ";					
									if ($mid != "") {	
										$sql3 = $sql3." and mid=".$mid."";
									}
									if ($sid != "") {	
										$sql3 = $sql3." and coupon_storeid=".$sid."";
									}									
									if ($result3 = mysqli_query($link, $sql3)){
										if (mysqli_num_rows($result3) > 0){
											$rows3 = array();
											while($row3 = mysqli_fetch_array($result3)){
												$rows[] = $row3;

												//banner_subject, banner_date, banner_enddate, banner_descript	,banner_picture,banner_link
											}
											$data["status"]="false";
											$data["code"]="0x0206";
											$data["responseMessage"]="This membercard has already been registered!";	
											$data["couponinfo"]=json_encode($rows, JSON_UNESCAPED_UNICODE);									
										}else{
											$data["status"]="false";
											$data["code"]="0x0206";
											$data["responseMessage"]="This membercard has already been registered!";	

											$data["couponinfo"]="";										
										}
									}else{
										$data["status"]="false";
										$data["code"]="0x0206";
										$data["responseMessage"]="This membercard has already been registered!";	
										$data["couponinfo"]="";																
									}								
	
								
							}else{
								// 加入店家會員卡 store_id member_id 	member_date card_type membercard_status
								$sql="INSERT INTO membercard (store_id, member_id, member_date, card_type,membercard_status) VALUES ($sid, $mid, NOW(),1,0);";
					
								mysqli_query($link,$sql) or die(mysqli_error($link));
								$rvalue = mysqli_affected_rows($link);
								if ($rvalue > 0) {


									// get 加入店家會員禮
									$sql6 = "SELECT * FROM coupon where coupon_trash=0 and coupon_type=3 and coupon_storeid='".$sid."' where coupon_status=1 and coupon_trush=0";
									if ($result6 = mysqli_query($link, $sql6)){
										if (mysqli_num_rows($result6) > 0){
											// login ok
											// coupon_id 取得 ; 發券
											$coupon_id2="";
											while($row6 = mysqli_fetch_array($result6)){
												//$coupon_id2 = $row6['coupon_id'];
												$cid2 = $row6['cid'];
												// 店家會員禮
												$coupon_no2 = uniqid();
												$sql7="INSERT INTO mycoupon (mid, coupon_no, cid,coupon_id ,coupon_name, coupon_type, coupon_description, coupon_startdate, coupon_enddate, coupon_status, coupon_rule, coupon_discount, discount_amount, coupon_storeid, coupon_for, coupon_picture) ";
												$sql7=$sql7." select $mid,'$coupon_no2',cid,coupon_id ,coupon_name, coupon_type, coupon_description, coupon_startdate, coupon_enddate, coupon_status, coupon_rule, coupon_discount, discount_amount, coupon_storeid, coupon_for, coupon_picture from coupon where cid = '".$cid2."'";

												mysqli_query($link,$sql7) or die(mysqli_error($link));
									
											}
										}
									}
									//exit;

									//$sql3 = "select `sid`, `store_id`, `store_type`, `store_name`, `shopping_area`, `store_phone`, `store_address`, `store_website`, `store_facebook`,`store_news`,`store_descript`, `store_opentime`, `store_picture`, `store_latitude`, `store_longitude`, `store_status` from store where store_trash=0 and store_id='$store_id'";
									$sql3 = "SELECT pid,mid, coupon_no, using_flag, using_date, coupon_id ,coupon_name, coupon_type, coupon_description, coupon_startdate, coupon_enddate, coupon_status, coupon_rule, coupon_discount, discount_amount, coupon_storeid, coupon_for, coupon_picture from mycoupon where pid>0 and coupon_type=3 ";					
									if ($mid != "") {	
										$sql3 = $sql3." and mid=".$mid."";
									}
									if ($sid != "") {	
										$sql3 = $sql3." and coupon_storeid=".$sid."";
									}									
									if ($result3 = mysqli_query($link, $sql3)){
										if (mysqli_num_rows($result3) > 0){
											$rows3 = array();
											while($row3 = mysqli_fetch_array($result3)){
												$rows[] = $row3;

												//banner_subject, banner_date, banner_enddate, banner_descript	,banner_picture,banner_link
											}
											$data["status"]="true";
											$data["code"]="0x0200";
											$data["responseMessage"]="The member card data is successfully added!";	
											$data["couponinfo"]=json_encode($rows, JSON_UNESCAPED_UNICODE);									
										}else{
											$data["status"]="true";
											$data["code"]="0x0200";
											$data["responseMessage"]="The member card data is successfully added!";	
											$data["couponinfo"]="";										
										}
									}else{
										$data["status"]="true";
										$data["code"]="0x0200";
										$data["responseMessage"]="The member card data is successfully added!";	
										$data["couponinfo"]="";																
									}
									
									// 更新coupon的使用 update mycoupon set using_flag=1, usging_date=NOW() where coupon_no='$coupon_no';
								}else{
									$data["status"]="false";
									$data["code"]="0x0201";
									$data["responseMessage"]="Add member card fail!";								
								}

							}
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
		//echo "need mail and password!";
		$data["status"]="false";
		$data["code"]="0x0203";
		$data["responseMessage"]="API parameter is required!";
		header('Content-Type: application/json');
		echo (json_encode($data, JSON_UNESCAPED_UNICODE));			
	}
?>