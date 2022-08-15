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
							
							try {

								$sql="INSERT INTO mycoupon (mid, coupon_no, cid,coupon_id ,coupon_name, coupon_type, coupon_description, coupon_startdate, coupon_enddate, coupon_status, coupon_rule, coupon_discount, discount_amount, coupon_storeid, coupon_for, coupon_picture) ";
								$sql=$sql." select $mid,'$coupon_no',cid,coupon_id ,coupon_name, coupon_type, coupon_description, coupon_startdate, coupon_enddate, coupon_status, coupon_rule, coupon_discount, discount_amount, coupon_storeid, coupon_for, coupon_picture from coupon where coupon_id = '".$coupon_id."'";
//echo $sql;
//exit;
								mysqli_query($link,$sql) or die(mysqli_error($link));
								$rvalue = mysqli_affected_rows($link);
								if ($rvalue > 0) {
									$data["status"]="true";
									$data["code"]="0x0200";
									$data["responseMessage"]="Get coupon success!";	
								}else{
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