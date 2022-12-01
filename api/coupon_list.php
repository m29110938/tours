<?php
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$sid = isset($_POST['sid']) ? $_POST['sid'] : '';
$coupon_type = isset($_POST['coupon_type']) ? $_POST['coupon_type'] : '';

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
						//DATE_FORMAT(coupon_startdate, "%Y %m %d")
						// 店家優惠券（需要有店家會員）
						$sql2 = "SELECT a.cid,a.coupon_id,a.coupon_name, a.coupon_type, a.coupon_description, DATE_FORMAT(a.coupon_issue_startdate, '%Y-%m-%d') as coupon_issue_startdate,DATE_FORMAT(a.coupon_issue_enddate, '%Y-%m-%d') as coupon_issue_enddate, DATE_FORMAT(a.coupon_startdate, '%Y-%m-%d') as coupon_startdate,DATE_FORMAT(a.coupon_enddate, '%Y-%m-%d') as coupon_enddate,a.coupon_status,a.coupon_rule,a.coupon_discount,a.discount_amount,a.coupon_storeid,coupon_for,a.coupon_picture FROM coupon as a";
						$sql2 = $sql2." left join (select * from membercard) as b on a.coupon_storeid=b.store_id";
						$sql2 = $sql2." where a.coupon_trash=0 and a.coupon_status=1";
						$sql2 = $sql2." and a.coupon_issue_enddate >= '".date("Y-m-d")."'";
						$sql2 = $sql2." and a.coupon_issue_startdate <= '".date("Y-m-d")."'";
						$sql2 = $sql2." and a.coupon_enddate >= '".date("Y-m-d")."'";
						$sql2 = $sql2." and a.coupon_id not in (SELECT coupon_id from mycoupon where mid=$mid) ";
						$sql2 = $sql2." and a.coupon_number > 0";
						$sql2 = $sql2." and b.member_id='$mid'";
						if ($sid != "") {	
							$sql2 = $sql2." and a.coupon_storeid=".$sid."";
						}
						if ($coupon_type != "") {	
							$sql2 = $sql2." and a.coupon_type=".$coupon_type."";
						}else{
							$sql2 = $sql2." and a.coupon_type = 1";
						}
						$sql2 = $sql2." UNION ALL ";
						// 平台優惠券
						$sql2 = $sql2."SELECT a.cid,a.coupon_id,a.coupon_name, a.coupon_type, a.coupon_description, DATE_FORMAT(a.coupon_issue_startdate, '%Y-%m-%d') as coupon_issue_startdate,DATE_FORMAT(a.coupon_issue_enddate, '%Y-%m-%d') as coupon_issue_enddate, DATE_FORMAT(a.coupon_startdate, '%Y-%m-%d') as coupon_startdate,DATE_FORMAT(a.coupon_enddate, '%Y-%m-%d') as coupon_enddate,a.coupon_status,a.coupon_rule,a.coupon_discount,a.discount_amount,a.coupon_storeid,coupon_for,a.coupon_picture FROM coupon as a";
						$sql2 = $sql2." where a.coupon_trash=0 and a.coupon_status=1";
						$sql2 = $sql2." and a.coupon_issue_enddate >= '".date("Y-m-d")."'";
						$sql2 = $sql2." and a.coupon_issue_startdate <= '".date("Y-m-d")."'";
						$sql2 = $sql2." and a.coupon_enddate >= '".date("Y-m-d")."'";
						$sql2 = $sql2." and a.coupon_id not in (SELECT coupon_id from mycoupon where mid=$mid) ";
						$sql2 = $sql2." and a.coupon_number > 0";
						$sql2 = $sql2." and a.coupon_type = 4";
						$sql2 = $sql2." order by coupon_type,coupon_startdate";
						// echo $sql2;
						//$sql2 = "SELECT bid,banner_subject, banner_date, banner_enddate, banner_descript,banner_picture,banner_link FROM banner where banner_trash=0 ";
						//$sql2 = $sql2." and banner_enddate > NOW()";
						//$sql2 = $sql2." order by banner_date desc";
// echo $sql2;
//exit;
						//$data = "";
						if ($result2 = mysqli_query($link, $sql2)){
							if (mysqli_num_rows($result2) > 0){
								$rows = array();
								while($row2 = mysqli_fetch_array($result2)){
									$rows[] = $row2;

									//banner_subject, banner_date, banner_enddate, banner_descript	,banner_picture,banner_link
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