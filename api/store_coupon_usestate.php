<?php
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';

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
					// $mid=0;
                    $member_sid = "";
					while($row = mysqli_fetch_array($result)){
						// $mid = $row['mid'];
						$member_sid = $row['member_sid'];
					}
					try {
						$total_coupon = 0;
						$use_coupon = 0;


                        $sql1 = "SELECT a.cid,b.coupon_name,a.coupon_picture FROM `mycoupon` as a";
                        $sql1 = $sql1." INNER JOIN (SELECT * FROM coupon) as b on b.cid = a.cid";
                        $sql1 = $sql1." INNER JOIN (SELECT * FROM store) as c on c.sid = a.coupon_storeid";
                        $sql1 = $sql1." WHERE c.sid = '".$member_sid."' and b.coupon_trash=0";
                        $sql1 = $sql1." group by a.cid";
						// echo $sql1;
						if ($result1 = mysqli_query($link, $sql1)){
							if (mysqli_num_rows($result1) > 0){
								$rows = array();
								//$i=0;
								while($row1 = mysqli_fetch_array($result1)){

									$cid = $row1['cid'];
									$coupon_name = $row1['coupon_name'];
									$coupon_picture = $row1['coupon_picture'];
									
									$sql2 = "SELECT COUNT(cid) as total_coupon FROM `mycoupon`";
									$sql2 = $sql2." WHERE cid = $cid";
									$result2 = mysqli_query($link, $sql2);
									$row2 = mysqli_fetch_array($result2);
									$total_coupon = $row2['total_coupon'];

									$sql2 = "SELECT COUNT(cid) as use_coupon FROM `mycoupon`";
									$sql2 = $sql2." WHERE cid = $cid and using_flag = 1";
									$result2 = mysqli_query($link, $sql2);
									$row2 = mysqli_fetch_array($result2);
									$use_coupon = $row2['use_coupon'];

									$rows[] = array("0"=> $cid,"cid"=> $cid,"1"=> $coupon_name,"coupon_name"=> $coupon_name, "1"=> $coupon_picture, "coupon_picture"=> $coupon_picture,"3"=> $total_coupon,"total_coupon"=> $total_coupon,"4"=> $use_coupon,"use_coupon"=> $use_coupon,);
								}
	
								header('Content-Type: application/json');
								echo (json_encode($rows, JSON_UNESCAPED_UNICODE));
								exit;
							}else{
								$data["status"]="false";
								$data["code"]="0x0201";
								$data["responseMessage"]="This is no member data!";									
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