<?php
function getservice($conn,$sid,$service_item){
	try {
		
		//oid,order_no,sales_id,person_id,mobile_no,member_type,order_status,log_date
		$sql3 = "SELECT * FROM `hairservice` ";
		if ($sid != "") {	
			$sql3 = $sql3." where store_id='".$sid."' and service_trash = 0 and service_code in ($service_item)";
		}
		//echo $sql2;
		$service_name = "";
		if ($result2 = mysqli_query($conn, $sql3)){
			if (mysqli_num_rows($result2) > 0){
				while($row2 = mysqli_fetch_array($result2)){
					$service_name = $service_name.$row2['service_name'].",";
					//break;
				}
				$service_name = substr($service_name,0,strlen($service_name)-1);
			}else {
				$service_name = "";
			}
		}else {
			$service_name = "";
		}
	} catch (Exception $e) {
		$service_name="";
	}	
	return $service_name;	
}
function getprice($conn,$sid,$service_item){
	try {
		
		//oid,order_no,sales_id,person_id,mobile_no,member_type,order_status,log_date
		$sql3 = "SELECT * FROM `hairservice` ";
		if ($sid != "") {	
			$sql3 = $sql3." where store_id='".$sid."' and service_trash = 0 and service_code in ($service_item)";
		}
		//echo $sql2;
		$service_price = "";
		if ($result2 = mysqli_query($conn, $sql3)){
			if (mysqli_num_rows($result2) > 0){
				while($row2 = mysqli_fetch_array($result2)){
					$service_price = $service_price.$row2['service_price'].",";
					//break;
				}
				$service_price = substr($service_price,0,strlen($service_price)-1);
			}else {
				$service_price = "";
			}
		}else {
			$service_price = "";
		}
	} catch (Exception $e) {
		$service_price="";
	}	
	return $service_price;	
}

$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$booking_no = isset($_POST['booking_no']) ? $_POST['booking_no'] : '';

	if (($member_id != '') && ($member_pwd != '') && ($booking_no != '')) {
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
			$booking_no  = mysqli_real_escape_string($link,$booking_no);
			
			$sql = "SELECT * FROM member where member_trash=0 and member_type=2 ";
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
					$membersid=0;
					while($row = mysqli_fetch_array($result)){
						//$mid = $row['mid'];
						//$membername = $row['member_name'];
						$membersid = $row['member_sid'];
					}
					try {
					
						$sql2 = "SELECT a.rid,a.booking_no,a.store_id,a.hid,b.nick_name,a.reserve_date, a.reserve_time, a.mid,a.member_id, a.member_name,a.member_email, a.service_item, a.reserve_remark, a.reserve_status, a.reserve_created_at  FROM reserveinfo a ";
					    $sql2 = $sql2." left join ( select hid, nick_name from hairstylist) as b ON a.hid= b.hid ";
						$sql2 = $sql2." where a.rid>0 and a.reserve_trash=0 and a.reserve_status <= 3 ";   // 2 取消
						$sql2 = $sql2." and a.booking_no='$booking_no' and store_id=".$membersid."";
						//echo $sql2;
						//exit;
						//$data = "";
						if ($result2 = mysqli_query($link, $sql2)){
							if (mysqli_num_rows($result2) > 0){
								$rows = array();
								while($row2 = mysqli_fetch_array($result2)){
									//$rows[] = $row2;
									$data2 = [
										'0'       				=> $row2['rid'],   
										'rid'       			=> $row2['rid'],   
										'1'       				=> $row2['booking_no'], 
										'booking_no'       		=> $row2['booking_no'], 
										'2'   					=> $row2['store_id'],   
										'store_id'   			=> $row2['store_id'],   
										'3'      				=> $row2['hid'],
										'hid'      				=> $row2['hid'],
										'4'  					=> $row2['nick_name'],
										'nick_name'  			=> $row2['nick_name'],
										'5'    					=> $row2['reserve_date'],
										'reserve_date'    		=> $row2['reserve_date'],
										'6'    					=> $row2['reserve_time'],
										'reserve_time'    		=> $row2['reserve_time'],
										'7'    					=> $row2['mid'],
										'mid'    				=> $row2['mid'],
										'8'    					=> $row2['member_id'],
										'member_id'    			=> $row2['member_id'],
										'9'    					=> $row2['member_name'],
										'member_name'    		=> $row2['member_name'],
										'10'    				=> $row2['member_email'],
										'member_email'    		=> $row2['member_email'],
										'11'    				=> $row2['service_item'],
										'service_item'    		=> $row2['service_item'],
										'12'    				=> getservice($link,$row2['store_id'],$row2['service_item']),
										'service_name'    		=> getservice($link,$row2['store_id'],$row2['service_item']),
										'13'    				=> getprice($link,$row2['store_id'],$row2['service_item']),
										'service_price'    		=> getprice($link,$row2['store_id'],$row2['service_item']),
										'14'    				=> $row2['reserve_remark'],
										'reserve_remark'    	=> $row2['reserve_remark'],
										'15'    				=> $row2['reserve_status'],
										'reserve_status'    	=> $row2['reserve_status'],
										'16'    				=> $row2['reserve_created_at'],
										'reserve_created_at'    => $row2['reserve_created_at']
									];
									array_push($rows, $data2);
								}
								header('Content-Type: application/json');
								echo (json_encode($rows, JSON_UNESCAPED_UNICODE));
								exit;
							}else{
								$data["status"]="false";
								$data["code"]="0x0201";
								$data["responseMessage"]="This is no booking data!";									
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