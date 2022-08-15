<?php
function SendCURL($membername,$memberid,$memberpwd){
	$crl = curl_init('https://ml-api.jotangi.com.tw/api/auth/register');
	$data = array(
	  'name' => $membername,
	  'mobile' => $memberid,
	  'password' => $memberpwd,
	);

	$post_data = json_encode($data);

	// Prepare new cURL resource
	
	curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($crl, CURLINFO_HEADER_OUT, true);
	curl_setopt($crl, CURLOPT_POST, true);
	curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);

	// Set HTTP Header for POST request 
	curl_setopt($crl, CURLOPT_HTTPHEADER, array(
	  'Content-Type: application/json',
	  'Content-Length: ' . strlen($post_data))
	);

	// Submit the POST request
	$result = curl_exec($crl);
	$obj = json_decode($result,true);

	// handle curl error
	//if ($result === false) {
	if ($obj["status"] == "error") {
	  // throw new Exception('Curl error: ' . curl_error($crl));
	  //print_r('Curl error: ' . curl_error($crl));
	  $result_noti = 0; //die();
	} else {

	  $result_noti = 1; //die();
	}
	curl_close($crl);
return $result_noti;
}	

$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$member_name = isset($_POST['member_name']) ? $_POST['member_name'] : '';
$member_type = isset($_POST['member_type']) ? $_POST['member_type'] : '';
$recommend_code = isset($_POST['recommend_code']) ? $_POST['recommend_code'] : '';

	if (($member_id != '') && ($member_pwd != '') && ($member_name != '') && ($member_type != ''))  // && ($recommend_code != '')
	{
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
			$member_name  = mysqli_real_escape_string($link,$member_name);
			$member_type  = mysqli_real_escape_string($link,$member_type);
			$recommend_code  = mysqli_real_escape_string($link,$recommend_code);
			$sid=0;
			$sql = "SELECT * FROM member where member_trash=0 ";
			if ($member_id != "") {	
				$sql = $sql." and member_id='".$member_id."'";
			}
			if ($result = mysqli_query($link, $sql)){
				if (mysqli_num_rows($result) > 0){
					// $this->_response(null, 400, '帳號已存在');
					//echo "帳號已存在!";
					$data["status"]="false";
					$data["code"]="0x0201";
					$data["responseMessage"]="此號碼已被註冊過!";						
				}else{
					
					if ($recommend_code != "") {
						// get $sid
						$sql2 = "SELECT * FROM store where store_trash=0 and store_id='".$recommend_code."'";
						if ($result2 = mysqli_query($link, $sql2)){
							if (mysqli_num_rows($result2) > 0){
								// login ok
								// user id 取得
								
								while($row2 = mysqli_fetch_array($result2)){
									$sid = $row2['sid'];
									//$storename = $row2['store_name'];
									$shoppingarea = $row2['shopping_area'];
								}
							}else{
								//";
								$data["status"]="false";
								$data["code"]="0x0205";
								$data["responseMessage"]="推薦碼輸入錯誤!";
								header('Content-Type: application/json');
								echo (json_encode($data, JSON_UNESCAPED_UNICODE));								
								exit;								
							}
							
						}else{
							$data["status"]="false";
							$data["code"]="0x0204";
							$data["responseMessage"]="SQL fail!";	
							header('Content-Type: application/json');
							echo (json_encode($data, JSON_UNESCAPED_UNICODE));								
							exit;								
						}	
					}					
					
					// register
					$sql="INSERT INTO member (member_id,member_pwd,member_name,member_type,recommend_code,member_status) VALUES ('$member_id', '$member_pwd', '$member_name',$member_type,'$recommend_code','1');";
					mysqli_query($link,$sql) or die(mysqli_error($link));

					// get $mid
					$sql = "SELECT * FROM member where member_trash=0 and member_id='".$member_id."'";
					if ($result = mysqli_query($link, $sql)){
						if (mysqli_num_rows($result) > 0){
							// login ok
							// user id 取得
							$mid=0;
							while($row = mysqli_fetch_array($result)){
								$mid = $row['mid'];
								$membername = $row['member_name'];
								//$xid = $row['mid'];
								$xmname = $row['member_name'];
								$xmid = $row['member_id'];
								$xmpwd = $row['member_pwd'];									
							}
						}
					}
			
					if ($member_type == "1") {  //一般會員發 註冊平台禮

						// get 註冊平台禮6 from coupon
						$sql2 = "SELECT * FROM coupon where coupon_trash=0 and coupon_type=6 ";   //and coupon_storeid='".$shoppingarea."'"
						
						if ($result2 = mysqli_query($link, $sql2)){
							if (mysqli_num_rows($result2) > 0){
								// login ok
								// coupon_id 取得 ; 發券
								$coupon_id="";
								while($row2 = mysqli_fetch_array($result2)){
									$coupon_id = $row2['coupon_id'];
								
									// 註冊平台禮
									$coupon_no = uniqid();
									$sql3="INSERT INTO mycoupon (mid, coupon_no, cid,coupon_id ,coupon_name, coupon_type, coupon_description, coupon_startdate, coupon_enddate, coupon_status, coupon_rule, coupon_discount, discount_amount, coupon_storeid, coupon_for, coupon_picture) ";
									$sql3=$sql3." select $mid,'$coupon_no',cid,coupon_id ,coupon_name, coupon_type, coupon_description, coupon_startdate, coupon_enddate, coupon_status, coupon_rule, coupon_discount, discount_amount, coupon_storeid, coupon_for, coupon_picture from coupon where coupon_id = '".$coupon_id."'";

									mysqli_query($link,$sql3) or die(mysqli_error($link));
						
								}
							}
						}
						if ($recommend_code != ""){
							// 加入店家會員卡 store_id member_id 	member_date card_type membercard_status
							$sql5="INSERT INTO membercard (store_id, member_id, 	member_date, card_type,membercard_status) VALUES ('$sid', '$mid', NOW(),1,0);";
							mysqli_query($link,$sql5) or die(mysqli_error($link));
							
							// get 加入店家會員禮
							$sql6 = "SELECT * FROM coupon where coupon_trash=0 and coupon_type=3 and coupon_storeid='".$sid."'";
							if ($result6 = mysqli_query($link, $sql6)){
								if (mysqli_num_rows($result6) > 0){
									// login ok
									// coupon_id 取得 ; 發券
									$coupon_id2="";
									while($row6 = mysqli_fetch_array($result6)){
										$coupon_id2 = $row6['coupon_id'];
									
										// 店家會員禮
										$coupon_no2 = uniqid();
										$sql7="INSERT INTO mycoupon (mid, coupon_no, cid,coupon_id ,coupon_name, coupon_type, coupon_description, coupon_startdate, coupon_enddate, coupon_status, coupon_rule, coupon_discount, discount_amount, coupon_storeid, coupon_for, coupon_picture) ";
										$sql7=$sql7." select $mid,'$coupon_no2',cid,coupon_id ,coupon_name, coupon_type, coupon_description, coupon_startdate, coupon_enddate, coupon_status, coupon_rule, coupon_discount, discount_amount, coupon_storeid, coupon_for, coupon_picture from coupon where coupon_id = '".$coupon_id2."'";

										mysqli_query($link,$sql7) or die(mysqli_error($link));
							
									}
								}
							}
						}
						//echo "register ok!";
						
						$result_key=SendCURL($xmname,$xmid,$xmpwd);
						
						$data["status"]="true";
						$data["code"]="0x0200";
						$data["responseMessage"]="This account is successfully registered!";						
					
					}else{
						$data["status"]="true";
						$data["code"]="0x0200";
						$data["responseMessage"]="This account is successfully registered!";						
					}
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