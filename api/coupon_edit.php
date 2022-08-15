<?php
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$coupon_id = isset($_POST['coupon_id']) ? $_POST['coupon_id'] : '';

$coupon_name = isset($_POST['coupon_name']) ? $_POST['coupon_name'] : '';
$coupon_type = isset($_POST['coupon_type']) ? $_POST['coupon_type'] : '0';
$coupon_description = isset($_POST['coupon_description']) ? $_POST['coupon_description'] : '';
$coupon_startdate = isset($_POST['coupon_startdate']) ? $_POST['coupon_startdate'] : '';
$coupon_enddate = isset($_POST['coupon_enddate']) ? $_POST['coupon_enddate'] : '';

$coupon_status = isset($_POST['coupon_status']) ? $_POST['coupon_status'] : '0';
$coupon_rule = isset($_POST['coupon_rule']) ? $_POST['coupon_rule'] : '0';
$coupon_discount = isset($_POST['coupon_discount']) ? $_POST['coupon_discount'] : '0';
$discount_amount = isset($_POST['discount_amount']) ? $_POST['discount_amount'] : '0';
$coupon_storeid = isset($_POST['coupon_storeid']) ? $_POST['coupon_storeid'] : '';
$coupon_for = isset($_POST['coupon_for']) ? $_POST['coupon_for'] : '0';
//$coupon_picture = isset($_POST['coupon_picture']) ? $_POST['coupon_picture'] : '';

	if (($member_id != '') && ($member_pwd != '') && ($coupon_id != '')) {
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

			$coupon_id  = mysqli_real_escape_string($link,$coupon_id);
			$coupon_name  = mysqli_real_escape_string($link,$coupon_name);
			$coupon_type  = mysqli_real_escape_string($link,$coupon_type);
			$coupon_description  = mysqli_real_escape_string($link,$coupon_description);
			$coupon_startdate  = mysqli_real_escape_string($link,$coupon_startdate);
			$coupon_enddate  = mysqli_real_escape_string($link,$coupon_enddate);
			
			$coupon_status  = mysqli_real_escape_string($link,$coupon_status);
			$coupon_rule  = mysqli_real_escape_string($link,$coupon_rule);
			$coupon_discount  = mysqli_real_escape_string($link,$coupon_discount);
			$discount_amount  = mysqli_real_escape_string($link,$discount_amount);
			$coupon_storeid  = mysqli_real_escape_string($link,$coupon_storeid);
			$coupon_for  = mysqli_real_escape_string($link,$coupon_for);

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
					// echo "[]";
					$mid=0;
					while($row = mysqli_fetch_array($result)){
						$mid = $row['mid'];
					}
					
					// register
					$sql="update coupon set coupon_name='$coupon_name',coupon_type=$coupon_type,coupon_description='$coupon_description',coupon_startdate='$coupon_startdate',coupon_enddate='$coupon_enddate',coupon_status=$coupon_status,coupon_rule=$coupon_rule,coupon_discount=$coupon_discount,discount_amount=$discount_amount,coupon_storeid='$coupon_storeid',coupon_for=$coupon_for,coupon_updated_at=NOW(),coupon_updated_by='$mid' where cid=$coupon_id ;";

					//echo $sql;
					//exit;

					mysqli_query($link,$sql) or die(mysqli_error($link));
					
					//echo "coupon data change ok!";
					$data["status"]="true";
					$data["code"]="0x0200";
					$data["responseMessage"]="The coupon information has been updated successfully!";						
				}else{
					// $this->_response(null, 400, '帳號不存在');
					//echo "帳號不存在!";
					$data["status"]="false";
					$data["code"]="0x0201";
					$data["responseMessage"]="ID or password is wrong!";					
				}
			}else{
				//echo "need mail and password!";
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