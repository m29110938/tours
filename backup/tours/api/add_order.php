<?php
function Save_Log($conn,$a,$b,$c,$d,$e){
	//member_id	member_name	store_id	function_name	log_date	action_type
	
		$sql="INSERT INTO syslog (member_id,member_name,store_id,function_name,log_date,action_type) VALUES ('$a', '$b',$c, '$d',NOW(),$e);";
		mysqli_query($conn,$sql) or die(mysqli_error($conn));
}	

$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$m_id = isset($_POST['m_id']) ? $_POST['m_id'] : '';
$sid = isset($_POST['sid']) ? $_POST['sid'] : '0';
$order_amount = isset($_POST['order_amount']) ? $_POST['order_amount'] : '0';

$coupon_no = isset($_POST['coupon_no']) ? $_POST['coupon_no'] : '';
$discount_amount = isset($_POST['discount_amount']) ? $_POST['discount_amount'] : '0';
$pay_type = isset($_POST['pay_type']) ? $_POST['pay_type'] : '0';
$order_pay = isset($_POST['order_pay']) ? $_POST['order_pay'] : '0';
$bonus_point = isset($_POST['bonus_point']) ? $_POST['bonus_point'] : '0';

//add_order(member_id,store_id,coupon_no,order_amount,discount_amount,pay_type,order_pay,order_status


	if (($member_id != '') && ($member_pwd != '') && ($m_id != '') && ($sid != '') && ($order_amount != '0')) {
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
			$m_id  = mysqli_real_escape_string($link,$m_id);
			$coupon_no  = mysqli_real_escape_string($link,$coupon_no);
			$order_amount  = mysqli_real_escape_string($link,$order_amount);
			$discount_amount  = mysqli_real_escape_string($link,$discount_amount);
			$pay_type  = mysqli_real_escape_string($link,$pay_type);
			$order_pay  = mysqli_real_escape_string($link,$order_pay);
			$bonus_point  = mysqli_real_escape_string($link,$bonus_point);
			
			$sql = "SELECT * FROM member where member_trash=0 and member_type=2 ";
			if ($member_id != "") {	
				$sql = $sql." and member_id='".$member_id."'";
			}
			if ($member_pwd != "") {	
				$sql = $sql." and member_pwd='".$member_pwd."'";
			}
			if ($result = mysqli_query($link, $sql)){
				if (mysqli_num_rows($result) > 0){
					$membersid = 0;
					while($row = mysqli_fetch_array($result)){
						//$mid = $row['mid'];
						$member_name = $row['member_name'];
						$membersid = $row['member_sid'];
					}
					
					$sql2 = "SELECT * FROM member where member_trash=0 and member_type=1 ";
					if ($m_id != "") {	
						$sql2 = $sql2." and member_id='".$m_id."'";
					}
					if ($result2 = mysqli_query($link, $sql2)){
						if (mysqli_num_rows($result2) > 0){
							$mid=0;
							//$membersid = 0;
							while($row = mysqli_fetch_array($result2)){
								$mid = $row['mid'];
								$membername = $row['member_name'];
								//$membersid = $row['member_sid'];
							}		
							//店家序號,如果沒有傳就用店家帳號內所建的序號
							if ($sid == '0') {
									$sid = $membersid;
							}
							
							try {
								$date = date_create();
								//$shopping_area=1;
								$tour_guide=1;
								
								$rand = sprintf("%04d", rand(0,9999));
								$order_no = date_timestamp_get($date).$rand;
								
								//oid,order_no,order_date,store_id,tour_guide,member_id,order_amount,coupon_no,discount_amount,pay_type,order_pay,pay_status,order_status

								$sql="INSERT INTO orderinfo (order_no,order_date,store_id,tour_guide,member_id,order_amount,coupon_no,discount_amount,pay_type,order_pay,pay_status,bonus_point,order_status) VALUES ";
								$sql=$sql." ('$order_no',NOW(),$sid,$tour_guide,$mid,$order_amount,'$coupon_no',$discount_amount,$pay_type,$order_pay,1,$bonus_point,1);";
								//add_order(member_id,store_id,coupon_no,order_amount,discount_amount,pay_type,order_pay,order_status
								//echo $sql;
								//exit;
						
								mysqli_query($link,$sql) or die(mysqli_error($link));
								$rvalue = mysqli_affected_rows($link);
								if ($rvalue > 0) {

									// 更新coupon的使用 update mycoupon set using_flag=1, usging_date=NOW() where coupon_no='$coupon_no';
									$sql2="update mycoupon set using_flag=1, using_date=NOW() where using_flag=0 and coupon_no='$coupon_no'";
									mysqli_query($link,$sql2) or die(mysqli_error($link));

									if (intval($bonus_point) > 0) {
										// 點數扣除
										$sql="INSERT INTO mybonus (member_id,order_no,bonus_date,bonus_type,bonus,bonus_created_at) VALUES ";
										$sql=$sql." ($mid,'$order_no',NOW(),2,$bonus_point,NOW());";
										mysqli_query($link,$sql) or die(mysqli_error($link));
									
										//更新會員的點數總和:
										$sql2="update member set member_usingpoints=member_usingpoints+$bonus_point,member_updated_at=NOW() where mid=$mid";
										mysqli_query($link,$sql2) or die(mysqli_error($link));		
									}

									// 馬上發點
									//if (intval($order_pay) > 0) {
									//	$bonus = intval($order_pay);
									//	$sql="INSERT INTO mybonus (member_id,order_no,bonus_date,bonus_type,bonus,bonus_created_at) VALUES ";
									//	$sql=$sql." ($mid,'$order_no',NOW(),1,$bonus_point,NOW());";
									//	mysqli_query($link,$sql) or die(mysqli_error($link));
										//更新會員的點數總和:
									//	$sql2="update member set member_totalpoints=member_totalpoints+$bonus,member_updated_at=NOW() where mid=$mid";
									//	mysqli_query($link,$sql2) or die(mysqli_error($link));		
									//	$sql3="update orderinfo set bonus=$bonus,order_status=2,order_updated_at=NOW() where order_no='".$order_no."'";
									//	mysqli_query($link,$sql3) or die(mysqli_error($link));		
									//}

									//Save_Log($link,$_SESSION['userid'],$_SESSION['accname'],$_SESSION['userid'],'Add Order',$_SESSION['authority']);
									Save_Log($link,$member_id,$member_name,$membersid,'Add Order',4);
					
									$data["status"]="true";
									$data["code"]="0x0200";
									$data["responseMessage"]="The order data is successfully added!";	
									
								}else{
									$data["status"]="false";
									$data["code"]="0x0206";
									$data["responseMessage"]="Add order fail!";								
								}
							} catch (Exception $e) {
								//$this->_response(null, 401, $e->getMessage());
								//echo $e->getMessage();
								$data["status"]="false";
								$data["code"]="0x0202";
								$data["responseMessage"]=$e->getMessage();							
							}									
						}else {
							$data["status"]="false";
							$data["code"]="0x0207";
							$data["responseMessage"]="The member id is wrong!";							
						}
					}else{
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
		//echo "need mail and password!";
		$data["status"]="false";
		$data["code"]="0x0203";
		$data["responseMessage"]="API parameter is required!";
		header('Content-Type: application/json');
		echo (json_encode($data, JSON_UNESCAPED_UNICODE));			
	}
?>