<?php
include("db_tools.php");

$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$product_type = isset($_POST['product_type']) ? $_POST['product_type'] : '';

	if (($member_id != '') && ($member_pwd != '')) {
		$loc_lat = 0.0;
		$loc_lng = 0.0;

		try {

			$member_id  = mysqli_real_escape_string($link,$member_id);
			$member_pwd  = mysqli_real_escape_string($link,$member_pwd);
			$product_type  = mysqli_real_escape_string($link,$product_type);
		   //echo "loc_lat:".$loc_lat;
		   //echo "loc_lng:".$loc_lng;			
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
				
						$sql2 = "SELECT a.`pid`, `product_no`, b.`product_type`, b.`producttype_name`,`product_name`, `product_price`,`product_picture`, `product_status` FROM product as a";
						$sql2 = $sql2." inner join ( select pid,product_type,producttype_name from producttype) as b ON b.pid= a.pid ";
						//$sql2 = $sql2." and coupon_enddate > NOW()";
						$sql2 = $sql2." where a.product_trash=0 and a.product_status=1";
						//SELECT `pid`, `product_no`, b.`product_type`, b.`producttype_name`,`product_name`, `product_price`,`product_picture`, `product_status` FROM product as a inner join ( select pid,product_type,producttype_name from producttype) as b ON b.pid= a.pid  where a.product_trash=0   order by b.product_type desc, product_no

						if ($product_type != "") {	
							$sql2 = $sql2." and b.product_type='".$product_type."'";
						}						
						$sql2 = $sql2." order by b.product_type , product_no";
						//echo $sql2;

						//$data = "";
						if ($result2 = mysqli_query($link, $sql2)){
							if (mysqli_num_rows($result2) > 0){
								$rows = array();
								while($row2 = mysqli_fetch_array($result2)){
									//$rows[] = $row2;
									$c1= $row2['pid'];
									$c2= $row2['product_no'];
									$c3= $row2['product_type'];
									$c4= $row2['producttype_name'];
									$c5= $row2['product_name'];
									$c6= $row2['product_price'];
									$c7= $row2['product_picture'];
									$c8= $row2['product_status'];
							
									$rows[] = array("0"=> $c1 , "pid"=> $c1 , "1"=> $c2,"product_no"=> $c2, "2"=> $c3, "product_type"=> $c3,"3"=> $c4,"producttype_name"=> $c4, "4"=> $c5,"product_name"=> $c5, "5"=> $c6,"product_price"=> $c6,"6"=> $c7,"product_picture"=> $c7,"7"=> $c8,"product_status"=> $c8);
								}
								
								header('Content-Type: application/json');
								echo (json_encode($rows, JSON_UNESCAPED_UNICODE));
								exit;
							}else{
								$data["status"]="false";
								$data["code"]="0x0201";
								$data["responseMessage"]="This is no product data!";									
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