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
					$mid=0;
					while($row = mysqli_fetch_array($result)){
						$mid = $row['mid'];
						$membername = $row['member_name'];
					}
					try {

						// test
						$sql2 = "SELECT *, sum(bonus_get) as total FROM orderinfo WHERE store_id = 129 AND DATE(bonus_end_date) = DATE(NOW()) AND member_id=$mid";
						if ($result2 = mysqli_query($link, $sql2)){
							// echo (mysqli_num_rows($result2));
							if (mysqli_num_rows($result2) > 0){
								$rows2 = array();
								while($row2 = mysqli_fetch_array($result2)){
									$rows[] = $row2;
									$total = $row2['order_amount'];
									//banner_subject, banner_date, banner_enddate, banner_descript	,banner_picture,banner_link
								}
								// echo (is_null($total));
								if (is_null($total)){
									$sql4 = "SELECT *, sum(bonus_get) as total1 FROM orderinfo WHERE DATE(bonus_end_date) = DATE(NOW()) AND member_id=$mid";
									if ($result3 = mysqli_query($link, $sql4)){
										if (mysqli_num_rows($result3) > 0){
											$rows3 = array();
											while($row3 = mysqli_fetch_array($result3)){
												$rows1[] = $row3;
												$total1 = $row3['total1'];
											}
											$date = date_create();
											$order_no = date_timestamp_get($date).$rand;
											echo ($order_no);
											// echo($total1);
											// store_id ： 測試版：129, 正式版：184
											// 新增到orderinfo
											$sql3 = "INSERT INTO orderinfo (order_no,store_id,tour_guide,member_id,order_amount,discount_amount,pay_type,order_pay,pay_status,bonus_point,urate,order_status,order_created_at,bonus_date,bonus_end_date,bonus_get) VALUES ";
											$sql3 = $sql3." ($order_no,129,1,$mid,-1,0,-1,0,1,0,0,1,NOW(),NOW(),NOW(),-$total1);";
											mysqli_query($link,$sql3) or die(mysqli_error($link));
											// 新增到mybonus
											$sql5 = "INSERT INTO mybonus (member_id,order_no,bonus_date,bonus_type,bonus,bonus_created_at) VALUES ";
											$sql5 = $sql5." ($mid,$order_no,NOW(),2,$total1,NOW());";
											mysqli_query($link,$sql5) or die(mysqli_error($link));

											//更新會員的點數總和:
											$sql2="update member set member_usingpoints=member_usingpoints+$total1,member_updated_at=NOW() where mid=$mid";
											mysqli_query($link,$sql2) or die(mysqli_error($link));		
											
											header('Content-Type: application/json');
											echo (json_encode($rows1, JSON_UNESCAPED_UNICODE));
											exit;
										}
									}
								}
								// $sql3 = "INSERT INTO orderinfo (store_id,tour_guide,member_id,order_amount,discount_amount,pay_type,order_pay,pay_status,bonus_point,urate,order_status,order_created_at,bonus_date,bonus_get) VALUES ";
								// $sql3 = $sql3." (-1,1,$mid,-1,0,-1,0,-1,0,0,0,NOW(),NOW(),$total);";
								// mysqli_query($link,$sql3) or die(mysqli_error($link));

								header('Content-Type: application/json');
								echo (json_encode($rows, JSON_UNESCAPED_UNICODE));
								exit;
							}else{
								$data["status"]="false";
								$data["code"]="0x0201";
								$data["responseMessage"]="This is no member card data!";									
							}
						}else{
							// echo "need mail and password!";
							$data["status"]="false";
							$data["code"]="0x0204";
							$data["responseMessage"]="SQL fail!";								
						}

						
                        // add-2022-05-20 新增扣除過期紅利的資料
						// $sql2 = "SELECT *, sum(bonus_get) as total FROM orderinfo WHERE DATE(bonus_end_date) = DATE(NOW()) AND member_id=$mid";
						// if ($result2 = mysqli_query($link, $sql2)){
						// 	if (mysqli_num_rows($result2) > 0){
						// 		$rows2 = array();
						// 		while($row2 = mysqli_fetch_array($result2)){
						// 			// $rows[] = $row2;
						// 			$total = $row2['total'];
						// 			//banner_subject, banner_date, banner_enddate, banner_descript	,banner_picture,banner_link
						// 		}
						// 		// echo ($total);
						// 		$sql3 = "INSERT INTO orderinfo (store_id,tour_guide,member_id,order_amount,discount_amount,pay_type,order_pay,pay_status,bonus_point,urate,order_status,order_created_at,bonus_date,bonus_get) VALUES ";
						// 		$sql3 = $sql3." (-1,1,$mid,-1,0,-1,0,-1,0,0,0,NOW(),NOW(),$total);";

						// 		mysqli_query($link,$sql3) or die(mysqli_error($link));
						// 		// header('Content-Type: application/json');
						// 		// echo (json_encode($rows, JSON_UNESCAPED_UNICODE));
						// 		// exit;
						// 	}else{
						// 		$data["status"]="false";
						// 		$data["code"]="0x0201";
						// 		$data["responseMessage"]="This is no member card data!";									
						// 	}
						// }else{
						// 	// echo "need mail and password!";
						// 	$data["status"]="false";
						// 	$data["code"]="0x0204";
						// 	$data["responseMessage"]="SQL fail!";								
						// }

						// add-2022-05-20 新增扣除過期紅利的資料
						// $sql1 = "SELECT * FROM orderinfo WHERE store_id = -1 AND DATE(bonus_end_date) = DATE(NOW()) AND member_id=$mid";
						// if ($result1 = mysqli_query($link, $sql1)){
						// 	if (mysqli_num_rows($result1) = 0){
						// 		try{
						// 			$sql2 = "INSERT INTO orderinfo (store_id,tour_guide,member_id,order_amount,discount_amount,pay_type,order_pay,pay_status,bonus_point,urate,order_status,order_created_at,bonus_date,bonus_get)
						// 					VALUES (-1,1,
						// 					(SELECT * FROM ( SELECT member_id FROM orderinfo WHERE DATE(bonus_end_date) = NOW() AND member_id =$mid GROUP by member_id=$mid) as member),
						// 					-1,0,-1,0,-1,0,0,0,NOW(),NOW(),
						// 					-(SELECT * FROM ( SELECT SUM(bonus_get) FROM orderinfo WHERE DATE(bonus_end_date) = NOW() AND member_id = $mid ) as total))"
						// 			mysqli_query($link,$sql2) or die(mysqli_error($link));
						// 		} catch (Exception $e) {
						// 			//$this->_response(null, 401, $e->getMessage());
						// 			//echo $e->getMessage();
						// 			$data["status"]="false";
						// 			$data["code"]="0x0202";
						// 			$data["responseMessage"]=$e->getMessage();	
						// 		}
						// 	}
						// }


						// else{
						// 	//echo "need mail and password!";
						// 	$data["status"]="false";
						// 	$data["code"]="0x0204";
						// 	$data["responseMessage"]="SQL fail!";								
						// }
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