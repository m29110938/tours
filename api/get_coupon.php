<?php
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$coupon_id = isset($_POST['coupon_id']) ? $_POST['coupon_id'] : '';

//
	if (($member_id != '') && ($member_pwd != '') && ($coupon_id != '')) {
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
			$coupon_id  = mysqli_real_escape_string($link,$coupon_id);
			$coupon_no = uniqid();
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
					
					$sql2 = "SELECT * FROM mycoupon where mycoupon_trash=0 and coupon_id = '".$coupon_id."' and mid=$mid";
					//echo $sql2;
					//exit;
					if ($result2 = mysqli_query($link, $sql2)){
						if (mysqli_num_rows($result2) == 0){
							
							$sql3 = "select * from coupon where coupon_id='$coupon_id'";
							if ($result3 = mysqli_query($link, $sql3)) {
								if (mysqli_num_rows($result3) > 0) {
									while ($row3 = mysqli_fetch_array($result3)) {
										$coupon_number = $row3['coupon_number'];
									}
									if ($coupon_number > 0) {
										try {
											$sql="INSERT INTO mycoupon (mid, coupon_no, cid,coupon_id ,coupon_name, coupon_type, coupon_description, coupon_startdate, coupon_enddate, coupon_status, coupon_rule, coupon_discount, discount_amount, coupon_storeid, coupon_for, coupon_picture) ";
											$sql=$sql." select $mid,'$coupon_no',cid,coupon_id ,coupon_name, coupon_type, coupon_description, coupon_startdate, coupon_enddate, coupon_status, coupon_rule, coupon_discount, discount_amount, coupon_storeid, coupon_for, coupon_picture from coupon where coupon_id = '".$coupon_id."'";
											//echo $sql;
											//exit;
											mysqli_query($link, $sql) or die(mysqli_error($link));

											$minus = intval($coupon_number)-1;
											$sql4 = "update coupon set coupon_number='$minus' where coupon_id='$coupon_id'";
											mysqli_query($link, $sql4) or die(mysqli_error($link));

											$rvalue = mysqli_affected_rows($link);
											if ($rvalue > 0) {
												try {
													//DATE_FORMAT(coupon_startdate, "%Y %m %d")
													$sql2 = "SELECT cid,coupon_id,coupon_name, coupon_type, coupon_description, DATE_FORMAT(coupon_startdate, '%Y-%m-%d') as coupon_startdate,DATE_FORMAT(coupon_enddate, '%Y-%m-%d') as coupon_enddate,coupon_status,coupon_rule,coupon_discount,discount_amount,coupon_storeid,coupon_for,coupon_picture FROM mycoupon where mycoupon_trash=0 ";
													$sql2 = $sql2." and coupon_no = '".$coupon_no."'";
													//echo $sql2;
													//exit;

													if ($result2 = mysqli_query($link, $sql2)) {
														if (mysqli_num_rows($result2) > 0) {
															$rows = array();
															while ($row2 = mysqli_fetch_array($result2)) {
																$rows[] = $row2;

																//banner_subject, banner_date, banner_enddate, banner_descript	,banner_picture,banner_link
															}
															//header('Content-Type: application/json');
															//echo (json_encode($rows, JSON_UNESCAPED_UNICODE));
															$data["status"]="true";
															$data["code"]="0x0200";
															$data["responseMessage"]="Get coupon success!";
															$data["coupon_info"]=json_encode($rows, JSON_UNESCAPED_UNICODE);
															header('Content-Type: application/json');
															echo(json_encode($data, JSON_UNESCAPED_UNICODE));
															exit;
														} else {
															$data["status"]="true";
															$data["code"]="0x0200";
															$data["responseMessage"]="Get coupon success!";
														}
													} else {
														$data["status"]="true";
														$data["code"]="0x0200";
														$data["responseMessage"]="Get coupon success!";
													}
												} catch (Exception $e) {
													//$this->_response(null, 401, $e->getMessage());
													//echo $e->getMessage();
													$data["status"]="false";
													$data["code"]="0x0202";
													$data["responseMessage"]=$e->getMessage();
												}
											} else {
												$data["status"]="false";
												$data["code"]="0x0201";
												$data["responseMessage"]="Get coupon fail!";
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
										$data["code"]="0x0207";
										$data["responseMessage"]="Amount of coupon is 0!";	
									}
								}else{
									$data["status"]="false";
									$data["code"]="0x0208";
									$data["responseMessage"]="Coupon is not found!";	
								}
							}else{
								$data["status"]="false";
								$data["code"]="0x0204";
								$data["responseMessage"]="SQL fail!";	
							}
						}
						else {
							$data["status"]="false";
							$data["code"]="0x0206";
							$data["responseMessage"]="You already have this coupon!";								
						}
					}else {
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