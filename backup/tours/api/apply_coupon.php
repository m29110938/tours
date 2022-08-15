<?php
function Save_Log($conn,$a,$b,$c,$d,$e){
	//member_id	member_name	store_id	function_name	log_date	action_type
	
		$sql="INSERT INTO syslog (member_id,member_name,store_id,function_name,log_date,action_type) VALUES ('$a', '$b',$c, '$d',NOW(),$e);";
		mysqli_query($conn,$sql) or die(mysqli_error($conn));
}	

$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$m_id = isset($_POST['m_id']) ? $_POST['m_id'] : '';
$coupon_no = isset($_POST['coupon_no']) ? $_POST['coupon_no'] : '';

//add_order(member_id,store_id,coupon_no,order_amount,discount_amount,pay_type,order_pay,order_status


	if (($member_id != '') && ($member_pwd != '') && ($m_id != '') && ($coupon_no != '')) {
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
			$m_id  = mysqli_real_escape_string($link,$m_id);
			$coupon_no  = mysqli_real_escape_string($link,$coupon_no);
			
			$sql = "SELECT * FROM member where member_trash=0 and member_type=2 ";
			if ($member_id != "") {	
				$sql = $sql." and member_id='".$member_id."'";
			}
			if ($member_pwd != "") {	
				$sql = $sql." and member_pwd='".$member_pwd."'";
			}
			if ($result = mysqli_query($link, $sql)){
				if (mysqli_num_rows($result) > 0){
					//$mid=0;
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
							
							try {
						
								$sql="INSERT INTO applycoupon_list (store_id,member_id,member_name,apply_date,coupon_no,coupon_name,applycoupon_status) ";
								$sql=$sql." select $membersid,$mid,'$membername',NOW(),coupon_no,coupon_name,0 from mycoupon where using_flag=0 and coupon_no='$coupon_no' ;";
						
								mysqli_query($link,$sql) or die(mysqli_error($link));
								$rvalue = mysqli_affected_rows($link);
								if ($rvalue > 0) {
									//exit;
									$data["status"]="true";
									$data["code"]="0x0200";
									$data["responseMessage"]="The coupon data is successfully apply!";	
									
									$sql2="update mycoupon set using_flag=1, using_date=NOW() where using_flag=0 and coupon_no='$coupon_no'";
									mysqli_query($link,$sql2) or die(mysqli_error($link));
									
									// 更新coupon的使用 
									
									Save_Log($link,$member_id,$member_name,$membersid,'Apply coupon',4);
									
								}else{
									$data["status"]="false";
									$data["code"]="0x0201";
									$data["responseMessage"]="Apply coupon fail!";								
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
							$data["code"]="0x0206";
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