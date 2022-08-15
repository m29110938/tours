<?php
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : ''; // 店家帳號
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : ''; // 店家密碼
// $mid = isset($_POST['mid']) ? $_POST['mid'] : ''; // 會員id
// $using_flag = isset($_POST['using_flag']) ? $_POST['using_flag'] : ''; // 使用狀況
// $coupon_type = isset($_POST['coupon_type']) ? $_POST['coupon_type'] : ''; // 使用狀況
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
			// $using_flag  = mysqli_real_escape_string($link,$using_flag);
			// $mid  = mysqli_real_escape_string($link,$mid);
			
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
		
						//echo mysqli_affected_rows($link);
						//exit;
                        $sql2 = "SELECT * FROM `store`";
                        $sql2 = $sql2." WHERE sid='".$member_sid."'";
						// $sql2 = "SELECT pid,mid, coupon_no, using_flag, using_date, coupon_id ,coupon_name, coupon_type, coupon_description, DATE_FORMAT(coupon_startdate, '%Y-%m-%d') as coupon_startdate,DATE_FORMAT(coupon_enddate, '%Y-%m-%d') as coupon_enddate, coupon_status, coupon_rule, coupon_discount, discount_amount, coupon_storeid, coupon_for, coupon_picture from mycoupon where pid>0 and coupon_type <> 8 ";					
						// if ($mid != "") {	
						// 	$sql2 = $sql2." and mid=".$mid."";
						// }
						// if ($using_flag != "") {	
						// 	$sql2 = $sql2." and using_flag=".$using_flag."";
						// }
						// if ($sid != "") {	
						// 	$sql2 = $sql2." and coupon_storeid=".$sid."";
						// }
						// $sql2 = $sql2." order by mid,using_flag,coupon_no,using_date desc";
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
								$data["responseMessage"]="This is no coupon data!";									
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