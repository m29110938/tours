<?php
include("db_tools.php");

function getproductlist($conn,$orderno,$mid){
	try {
		
		//oid,order_no,sales_id,person_id,mobile_no,member_type,order_status,log_date
		$sql3 = "SELECT a.*,b.product_name,c.producttype_name,b.product_picture FROM ecorderlist as a ";
		$sql3 = $sql3." inner join (select pid, product_no, product_name,product_picture from product where product_trash = 0 ) as b on a.product_no=b.product_no  ";
		$sql3 = $sql3." inner join (select pid, producttype_name from producttype  where producttype_trash = 0) as c on b.pid=c.pid ";
		$sql3 = $sql3." where member_id=$mid and order_no='$orderno'";

		$sql3 = $sql3." order by producttype_name,product_name ";
		//echo $sql3;
		$rows = "";
		if ($result3 = mysqli_query($conn, $sql3)){
			if (mysqli_num_rows($result3) > 0){
				$rows = array();
				while($row3 = mysqli_fetch_array($result3)){
					//$rows[] = $row3;
					
					$data3 = [
						//'0'       				=> $row3['did'],   
						'did'       			=> $row3['did'],   
						//'1'       				=> $row3['order_no'], 
						'order_no'       		=> $row3['order_no'], 
						//'2'   					=> $row3['member_id'],   
						'member_id'   			=> $row3['member_id'],   
						//'3'    					=> $row3['product_no'],
						'product_no'    		=> $row3['product_no'],
						//'4'    					=> $row3['product_spec'],
						'product_spec'    		=> $row3['product_spec'],
						//'5'    					=> $row3['product_price'],
						'product_price'    		=> $row3['product_price'],
						//'6'    					=> $row3['order_qty'],
						'order_qty'    			=> $row3['order_qty'],
						//'7'    					=> $row3['total_amount'],
						'total_amount'    		=> $row3['total_amount'],
						//'8'    					=> $row3['deliverystatus'],
						'deliverystatus'    	=> $row3['deliverystatus'],
						//'9'    					=> $row3['order_status'],
						'order_status'    		=> $row3['order_status'],
						//'10'    				=> $row3['cart_created_at'],
						'cart_created_at'    	=> $row3['cart_created_at'],
						//'11'    				=> $row3['product_name'],
						'product_name'    		=> $row3['product_name'],
						//'12'    				=> $row3['producttype_name'],
						'producttype_name'    	=> $row3['producttype_name'],
						//'13'    				=> $row3['product_picture'],
						'product_picture'    	=> $row3['product_picture']
					];
					array_push($rows, $data3);
					
				}
			}else {
				$rows = "";
			}
		}else {
			$rows = "";
		}
	} catch (Exception $e) {
		$rows = "";
	}	
	return $rows;	
}

$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
//$sid = isset($_POST['sid']) ? $_POST['sid'] : '';
$order_no = isset($_POST['order_no']) ? $_POST['order_no'] : '';

	if (($member_id != '') && ($member_pwd != '') && ($order_no != '')) {

		try {

			$member_id  = mysqli_real_escape_string($link,$member_id);
			$member_pwd  = mysqli_real_escape_string($link,$member_pwd);
			//$sid  = mysqli_real_escape_string($link,$sid);
			$order_no  = mysqli_real_escape_string($link,$order_no);

			
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
					
						//$sql2 = "SELECT oid,order_no,order_date, store_id, member_id, order_amount,coupon_no,discount_amount,pay_type,order_pay,pay_status,bonus_point,order_status FROM orderinfo where oid>0 ";
						$sql2 = "SELECT oid,order_no,order_date,store_id,member_id,order_amount,coupon_no,discount_amount,pay_type,order_pay,pay_status,bonus_point,deliverytype,recipientname,recipientaddr,recipientphone,recipientmail,deliverystatus,invoicetype,invoicephone,companytitle,uniformno,invoicestatus,order_status FROM ecorderinfo where oid>0 ";
						//$sql2 = "SELECT a.oid,a.order_no,a.order_date, a.store_id, a.member_id, a.order_amount,a.coupon_no,a.discount_amount,a.pay_type,a.order_pay,a.pay_status,a.bonus_point,a.order_status,b.store_name FROM orderinfo as a ";
						//$sql2 = $sql2." inner join (select sid, store_name from store) as b on a.store_id=b.sid  where a.oid>0 ";
						//$sql2 = $sql2." and coupon_enddate > NOW()";

						$sql2 = $sql2." and order_no='$order_no'";
						//$sql2 = $sql2." order by order_date desc";

						//echo $sql2;
						//exit;
						//$data = "";
						if ($result2 = mysqli_query($link, $sql2)){
							if (mysqli_num_rows($result2) > 0){
								$rows = array();
								while($row2 = mysqli_fetch_array($result2)){
									//$rows[] = $row2;
									//oid,order_no,order_date,store_id,member_id,order_amount,coupon_no,discount_amount,pay_type,
									//order_pay,pay_status,bonus_point,deliverytype,recipientname,recipientaddr,recipientphone,recipientmail,deliverystatus,invoicetype,invoicephone,companytitle,uniformno,invoicestatus,order_status
									$data2 = [
										//'0'       				=> $row2['oid'],   
										'oid'       			=> $row2['oid'],   
										//'1'       				=> $row2['order_no'], 
										'order_no'       		=> $row2['order_no'], 
										//'2'   					=> $row2['order_date'],   
										'order_date'   			=> $row2['order_date'],   
										//'3'    					=> $row2['store_id'],
										'store_id'    			=> $row2['store_id'],
										//'4'    					=> $row2['member_id'],
										'member_id'    			=> $row2['member_id'],
										//'5'    					=> $row2['order_amount'],
										'order_amount'    		=> $row2['order_amount'],
										//'6'    					=> $row2['coupon_no'],
										'coupon_no'    			=> $row2['coupon_no'],
										//'7'    					=> $row2['discount_amount'],
										'discount_amount'    	=> $row2['discount_amount'],
										//'8'    					=> $row2['pay_type'],
										'pay_type'    			=> $row2['pay_type'],
										//'9'    					=> $row2['order_pay'],
										'order_pay'    			=> $row2['order_pay'],
										//'10'    				=> $row2['pay_status'],
										'pay_status'    		=> $row2['pay_status'],
										//'11'    				=> $row2['bonus_point'],
										'bonus_point'    		=> $row2['bonus_point'],
										//'12'    				=> $row2['deliverytype'],
										'deliverytype'    		=> $row2['deliverytype'],
										//'13'    				=> $row2['recipientname'],
										'recipientname'    		=> $row2['recipientname'],
										//'14'    				=> $row2['recipientaddr'],
										'recipientaddr'    		=> $row2['recipientaddr'],
										//'15'    				=> $row2['recipientphone'],
										'recipientphone'   		=> $row2['recipientphone'],
										//'16'    				=> $row2['recipientmail'],
										'recipientmail'    		=> $row2['recipientmail'],
										//'17'    				=> $row2['deliverystatus'],
										'deliverystatus'    	=> $row2['deliverystatus'],
										//'18'    				=> $row2['invoicetype'],
										'invoicetype'    		=> $row2['invoicetype'],
										//'19'    				=> $row2['invoicephone'],
										'invoicephone'    		=> $row2['invoicephone'],
										//'20'    				=> $row2['companytitle'],
										'companytitle'    		=> $row2['companytitle'],
										//'21'    				=> $row2['uniformno'],
										'uniformno'    			=> $row2['uniformno'],
										//'22'    				=> $row2['invoicestatus'],
										'invoicestatus'    		=> $row2['invoicestatus'],
										//'23'    				=> $row2['order_status'],
										'order_status'    		=> $row2['order_status'],
										//'24'    				=> getproductlist($link,$row2['order_no'],$row2['member_id']),
										'product_list'    		=> getproductlist($link,$row2['order_no'],$row2['member_id']),
									];
									array_push($rows, $data2);
								}
								header('Content-Type: application/json');
								echo (json_encode($rows, JSON_UNESCAPED_UNICODE));
								exit;
							}else{
								$data["status"]="false";
								$data["code"]="0x0201";
								$data["responseMessage"]="This is no ec order data!";									
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