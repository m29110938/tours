<?php
include("db_tools.php");

function Save_Log($conn,$a,$b,$c,$d,$e){
	//member_id	member_name	store_id	function_name	log_date	action_type
	
		$sql="INSERT INTO syslog (member_id,member_name,store_id,function_name,log_date,action_type) VALUES ('$a', '$b',$c, '$d',NOW(),$e);";
		mysqli_query($conn,$sql) or die(mysqli_error($conn));
}	

$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';

$order_amount = isset($_POST['order_amount']) ? $_POST['order_amount'] : '0';
$coupon_no = isset($_POST['coupon_no']) ? $_POST['coupon_no'] : '';
$discount_amount = isset($_POST['discount_amount']) ? $_POST['discount_amount'] : '0';
$order_pay = isset($_POST['order_pay']) ? $_POST['order_pay'] : '0';
$bonus_point = isset($_POST['bonus_point']) ? $_POST['bonus_point'] : '0';

$delivery_type = isset($_POST['delivery_type']) ? $_POST['delivery_type'] : '';
$recipient_name = isset($_POST['recipient_name']) ? $_POST['recipient_name'] : '';
$recipient_addr = isset($_POST['recipient_addr']) ? $_POST['recipient_addr'] : '';
$recipient_phone = isset($_POST['recipient_phone']) ? $_POST['recipient_phone'] : '';
$recipient_mail = isset($_POST['recipient_mail']) ? $_POST['recipient_mail'] : '';

$invoice_type = isset($_POST['invoice_type']) ? $_POST['invoice_type'] : '';
$invoice_phone = isset($_POST['invoice_phone']) ? $_POST['invoice_phone'] : '';
$company_title = isset($_POST['company_title']) ? $_POST['company_title'] : '';
$uniform_no = isset($_POST['uniform_no']) ? $_POST['uniform_no'] : '';

//$pay_type = isset($_POST['pay_type']) ? $_POST['pay_type'] : '0';

//order_no,order_date,store_id,member_id,order_amount,coupon_no,discount_amount,pay_type,order_pay,pay_status,bonus_point,deliverytype,recipientname,recipientaddr,recipientphone,recipientmail,deliverystatus,invoicetype,invoicephone,companytitle,uniformno,invoicestatus
	if (($invoice_type == "3") && ($uniform_no == '')) $uniform_no = '-1';
	if (($invoice_type == "2") && ($invoice_phone == '')) $invoice_phone = '-1';
	
	if (($member_id != '') && ($member_pwd != '') && ($order_amount != '0') && ($delivery_type != '') && ($recipient_name != '') && ($recipient_addr != '') && ($recipient_phone != '') && ($invoice_phone != '-1') && ($uniform_no != '-1')) {
	
		//echo $sql;
		//exit;
		try {

			$member_id  = mysqli_real_escape_string($link,$member_id);
			$member_pwd  = mysqli_real_escape_string($link,$member_pwd);
			//$sid  = mysqli_real_escape_string($link,$sid);
			//$m_id  = mysqli_real_escape_string($link,$m_id);
			$coupon_no  = mysqli_real_escape_string($link,$coupon_no);
			$order_amount  = mysqli_real_escape_string($link,$order_amount);
			$discount_amount  = mysqli_real_escape_string($link,$discount_amount);
			//$pay_type  = mysqli_real_escape_string($link,$pay_type);
			$order_pay  = mysqli_real_escape_string($link,$order_pay);
			$bonus_point  = mysqli_real_escape_string($link,$bonus_point);

			$delivery_type  = mysqli_real_escape_string($link,$delivery_type);
			$recipient_name  = mysqli_real_escape_string($link,$recipient_name);
			$recipient_addr  = mysqli_real_escape_string($link,$recipient_addr);
			$recipient_phone  = mysqli_real_escape_string($link,$recipient_phone);
			$recipient_mail  = mysqli_real_escape_string($link,$recipient_mail);

			$invoice_type  = mysqli_real_escape_string($link,$invoice_type);
			$invoice_phone  = mysqli_real_escape_string($link,$invoice_phone);
			$company_title  = mysqli_real_escape_string($link,$company_title);
			$uniform_no  = mysqli_real_escape_string($link,$uniform_no);

			if ($discount_amount == "") $discount_amount = '0';
			if ($bonus_point == "") $bonus_point = '0';
			if ($order_pay == "") $order_pay = '0';
			
			$sql = "SELECT * FROM member where member_trash=0 and member_type=1 ";
			if ($member_id != "") {	
				$sql = $sql." and member_id='".$member_id."'";
			}
			if ($member_pwd != "") {	
				$sql = $sql." and member_pwd='".$member_pwd."'";
			}

			if ($result = mysqli_query($link, $sql)){
				if (mysqli_num_rows($result) > 0){
					//$membersid = 0;
					$mid = 0;
					while($row = mysqli_fetch_array($result)){
						$mid = $row['mid'];
						$member_name = $row['member_name'];
						//$membersid = $row['member_sid'];
					}
					
					$sid = 0;
					$membersid = 0;

					//使用coupon								
					if ($coupon_no != "") {		
			
						$sql3 = "select coupon_no,coupon_storeid from mycoupon where using_flag=0 and coupon_no='$coupon_no'" ;
						if ($result3 = mysqli_query($link, $sql3)){
							if (mysqli_num_rows($result3) > 0){

								//$coupon_storeid = 0;
								//while($row3 = mysqli_fetch_array($result3)){
								//	$coupon_storeid = $row3['coupon_storeid'];
								//}
									
								try {
									$date = date_create();
									//$shopping_area=1;
									
									$rand = sprintf("%04d", rand(0,9999));
									$order_no = date_timestamp_get($date).$rand;
									//	order_no,order_date,store_id,member_id,order_amount,coupon_no,discount_amount,pay_type,order_pay,pay_status,bonus_point,deliverytype,recipientname,recipientaddr,recipientphone,recipientmail,deliverystatus,invoicetype,invoicephone,companytitle,uniformno,invoicestatus,order_status,urate,bonus_get,bonus_date,order_created_at
									$sql="INSERT INTO ecorderinfo (order_no,order_date,store_id,member_id,order_amount,coupon_no,discount_amount,pay_type,order_pay,pay_status,bonus_point,deliverytype,recipientname,recipientaddr,recipientphone,recipientmail,deliverystatus,invoicetype,invoicephone,companytitle,uniformno,invoicestatus,order_status) VALUES ";
									$sql=$sql." ('$order_no',NOW(),$membersid,$mid,$order_amount,'$coupon_no',$discount_amount,-1,$order_pay,0,$bonus_point,$delivery_type,'$recipient_name','$recipient_addr','$recipient_phone','$recipient_mail',0,$invoice_type,'$invoice_phone','$company_title','$uniform_no',0,0);";

									//echo $sql;
									//exit;
							
									mysqli_query($link,$sql) or die(mysqli_error($link));
									$rvalue = mysqli_affected_rows($link);
									if ($rvalue > 0) {

										// 更新coupon的使用 update mycoupon set using_flag=1, usging_date=NOW() where coupon_no='$coupon_no';
										$sql2="update mycoupon set using_flag=1, using_date=NOW() where using_flag=0 and coupon_no='$coupon_no'";
										mysqli_query($link,$sql2) or die(mysqli_error($link));

										$sql3="insert into ecorderlist(order_no,member_id,product_no,product_spec,product_price,order_qty,total_amount) select '$order_no',member_id,product_no,product_spec,product_price,order_qty,total_amount from shoppingcart where member_id=$mid";
										mysqli_query($link,$sql3) or die(mysqli_error($link));

										$sql5="delete from shoppingcart where member_id=$mid ";
										mysqli_query($link,$sql5) or die(mysqli_error($link));

										Save_Log($link,$member_id,$member_name,$membersid,'Add EC Order',4);
										
										$data["status"]="true";
										$data["code"]="0x0200";
										$data["responseMessage"]="The EC order data is successfully added!";	
										//$order_no
										$data["responseMessage"]=$order_no;
									}else{
										$data["status"]="false";
										$data["code"]="0x0206";
										$data["responseMessage"]="Add EC order fail!";								
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
								$data["code"]="0x0207";
								$data["responseMessage"]="The coupon no is wrong!";										
							}
						}else{
							$data["status"]="false";
							$data["code"]="0x0204";
							$data["responseMessage"]="SQL fail!";									

						}	
						
					}else{ 
					// 沒使用coupon

						try {
							$date = date_create();
							//$shopping_area=1;
							$tour_guide=1;
							
							$rand = sprintf("%04d", rand(0,9999));
							$order_no = date_timestamp_get($date).$rand;
							
							$sql="INSERT INTO ecorderinfo (order_no,order_date,store_id,member_id,order_amount,coupon_no,discount_amount,pay_type,order_pay,pay_status,bonus_point,deliverytype,recipientname,recipientaddr,recipientphone,recipientmail,deliverystatus,invoicetype,invoicephone,companytitle,uniformno,invoicestatus,order_status) VALUES ";
							$sql=$sql." ('$order_no',NOW(),$membersid,$mid,$order_amount,'$coupon_no',$discount_amount,-1,$order_pay,0,$bonus_point,$delivery_type,'$recipient_name','$recipient_addr','$recipient_phone','$recipient_mail',0,$invoice_type,'$invoice_phone','$company_title','$uniform_no',0,0);";
							//echo $sql;
					
							mysqli_query($link,$sql) or die(mysqli_error($link));
							$rvalue = mysqli_affected_rows($link);
							if ($rvalue > 0) {

								$sql3="insert into ecorderlist(order_no,member_id,product_no,product_spec,product_price,order_qty,total_amount) select '$order_no',member_id,product_no,product_spec,product_price,order_qty,total_amount from shoppingcart where member_id=$mid";
								mysqli_query($link,$sql3) or die(mysqli_error($link));

								$sql5="delete from shoppingcart where member_id=$mid ";
								mysqli_query($link,$sql5) or die(mysqli_error($link));
							
								Save_Log($link,$member_id,$member_name,$membersid,'Add EC Order',4);
				
								$data["status"]="true";
								$data["code"]="0x0200";
								//$data["responseMessage"]="The EC order data is successfully added!";	
								$data["responseMessage"]=$order_no;
								
							}else{
								$data["status"]="false";
								$data["code"]="0x0206";
								$data["responseMessage"]="Add EC order fail!";								
							}
						} catch (Exception $e) {
							//$this->_response(null, 401, $e->getMessage());
							//echo $e->getMessage();
							$data["status"]="false";
							$data["code"]="0x0202";
							$data["responseMessage"]=$e->getMessage();							
						}

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