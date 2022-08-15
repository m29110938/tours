<?php
include("db_tools.php");

$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$product_no = isset($_POST['product_no']) ? $_POST['product_no'] : '';
$order_qty = isset($_POST['order_qty']) ? $_POST['order_qty'] : '0';

//add_order(member_id,store_id,coupon_no,order_amount,discount_amount,pay_type,order_pay,order_status

	if (($member_id != '') && ($member_pwd != '') && ($product_no != '') && ($order_qty != '0')) {
	
		//echo $sql;
		//exit;
		try {

			$member_id  = mysqli_real_escape_string($link,$member_id);
			$member_pwd  = mysqli_real_escape_string($link,$member_pwd);
			$product_no  = mysqli_real_escape_string($link,$product_no);
			$order_qty  = mysqli_real_escape_string($link,$order_qty);

			//if ($product_price == "") $product_price = '0';
			if ($order_qty == "") $order_qty = '0';
			//if ($total_amount == "") $total_amount = '0';
			
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
						//$member_name = $row['member_name'];
						//$membersid = $row['member_sid'];
					}
					//select from shoppingcart
					$sql2 = "SELECT * FROM shoppingcart where member_id=$mid and product_no='$product_no'";
					//echo $sql2;	
					//exit;					
					if ($result2 = mysqli_query($link, $sql2)){
						if (mysqli_num_rows($result2) > 0){
							//update qty to the exist product
							if ($order_qty > 0){ 
								$sql3="update shoppingcart set order_qty=$order_qty,total_amount=product_price * $order_qty,cart_updated_at=NOW() where member_id=$mid and product_no='$product_no'";
								//echo $sql3;
								//exit;					
								mysqli_query($link,$sql3) or die(mysqli_error($link));
								$rvalue2 = mysqli_affected_rows($link);
								if ($rvalue2 > 0) {					
									$data["status"]="true";
									$data["code"]="0x0200";
									$data["responseMessage"]="Modify shopping cart product's qty successfully!";						
								}else{
									$data["status"]="false";
									$data["code"]="0x0201";
									$data["responseMessage"]="Modify shopping cart product's qty fail!";						
								}							
							}else{
								if ($order_qty == 0){ 
									$sql3="delete from shoppingcart where member_id=$mid and product_no='$product_no'";
									//echo $sql3;
									//exit;					
									mysqli_query($link,$sql3) or die(mysqli_error($link));
									$data["status"]="true";
									$data["code"]="0x0200";
									$data["responseMessage"]="Modify shopping cart product's qty successfully!";									
								}else{
									$data["status"]="false";
									$data["code"]="0x0201";
									$data["responseMessage"]="Modify shopping cart product's qty fail!";						
								}
							}
						}else{
								$data["status"]="false";
								$data["code"]="0x0201";
								$data["responseMessage"]="Modify shopping cart product's qty fail!";						
						}
					}else {
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