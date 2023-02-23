<?php
function Save_Log($conn,$a,$b,$c,$d,$e){
	//member_id	member_name	store_id	function_name	log_date	action_type
	
		$sql="INSERT INTO syslog (member_id,member_name,store_id,function_name,log_date,action_type) VALUES ('$a', '$b',$c, '$d',NOW(),$e);";
		mysqli_query($conn,$sql) or die(mysqli_error($conn));
}	

$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$m_id = isset($_POST['m_id']) ? $_POST['m_id'] : '';
$sid = isset($_POST['sid']) ? $_POST['sid'] : '0';
$order_amount = isset($_POST['order_amount']) ? $_POST['order_amount'] : '0';

$coupon_no = isset($_POST['coupon_no']) ? $_POST['coupon_no'] : '';
$discount_amount = isset($_POST['discount_amount']) ? $_POST['discount_amount'] : '0';
$pay_type = isset($_POST['pay_type']) ? $_POST['pay_type'] : '0';
$order_pay = isset($_POST['order_pay']) ? $_POST['order_pay'] : '0';
$bonus_point = isset($_POST['bonus_point']) ? $_POST['bonus_point'] : '0';

//add_order(member_id,store_id,coupon_no,order_amount,discount_amount,pay_type,order_pay,order_status


	if (($member_id != '') && ($member_pwd != '') && ($m_id != '') && ($sid != '') && ($order_amount != '0')) {
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
			$sid  = mysqli_real_escape_string($link,$sid);
			$m_id  = mysqli_real_escape_string($link,$m_id);
			$coupon_no  = mysqli_real_escape_string($link,$coupon_no);
			$order_amount  = mysqli_real_escape_string($link,$order_amount);
			$discount_amount  = mysqli_real_escape_string($link,$discount_amount);
			$pay_type  = mysqli_real_escape_string($link,$pay_type);
			$order_pay  = mysqli_real_escape_string($link,$order_pay);
			$bonus_point  = mysqli_real_escape_string($link,$bonus_point);

			if ($discount_amount == "") $discount_amount = '0';
			if ($bonus_point == "") $bonus_point = '0';
			if ($order_pay == "") $order_pay = '0';
			
			$sql = "SELECT * FROM member where member_trash=0 and member_type=2 ";
			if ($member_id != "") {	
				$sql = $sql." and member_id='".$member_id."'";
			}
			if ($member_pwd != "") {	
				$sql = $sql." and member_pwd='".$member_pwd."'";
			}

			if ($result = mysqli_query($link, $sql)){
				if (mysqli_num_rows($result) > 0){
					$membersid = 0;
					while($row = mysqli_fetch_array($result)){
						//$mid = $row['mid'];
						$member_name = $row['member_name'];
						$membersid = $row['member_sid'];
					}
					
					$sql2 = "SELECT * FROM member where member_trash=0 and member_type=1 ";
					if ($m_id != "") {	
						$sql2 = $sql2." and member_id='".$m_id."'";
					}
					
					if ($result2 = mysqli_query($link, $sql2)){
						if (mysqli_num_rows($result2) > 0){
							$mid=0;
							//$membersid = 0;
							while($row = mysqli_fetch_array($result2)){
								$mid = $row['mid'];
								$membername = $row['member_name'];
								//$membersid = $row['member_sid'];
							}		
							//店家序號,如果沒有傳就用店家帳號內所建的序號
							if ($sid == '0') {
									$sid = $membersid;
							}
						//使用coupon								
						if ($coupon_no != "") {		
				
							$sql3 = "select coupon_no,coupon_storeid from mycoupon where using_flag=0 and coupon_no='$coupon_no'" ;
							if ($result3 = mysqli_query($link, $sql3)){
								if (mysqli_num_rows($result3) > 0){
									//$mid=0;
									//$membersid = 0;
									$coupon_storeid = 0;
									while($row3 = mysqli_fetch_array($result3)){
										$coupon_storeid = $row3['coupon_storeid'];
									}
									// check coupon_storeid == $membersid?					
									if ($coupon_storeid == $membersid){

										
										try {
											$date = date_create();
											//$shopping_area=1;
											$tour_guide=1;
											
											$rand = sprintf("%04d", rand(0,9999));
											$order_no = date_timestamp_get($date).$rand;
											
											//oid,order_no,order_date,store_id,tour_guide,member_id,order_amount,coupon_no,discount_amount,pay_type,order_pay,pay_status,order_status

											$sql="INSERT INTO orderinfo (order_no,order_date,store_id,tour_guide,member_id,order_amount,coupon_no,discount_amount,pay_type,order_pay,pay_status,bonus_point,order_status) VALUES ";
											$sql=$sql." ('$order_no',NOW(),$membersid,$tour_guide,$mid,$order_amount,'$coupon_no',$discount_amount,$pay_type,$order_pay,1,$bonus_point,1);";
											//add_order(member_id,store_id,coupon_no,order_amount,discount_amount,pay_type,order_pay,order_status
											//echo $sql;
											//exit;
									
											mysqli_query($link,$sql) or die(mysqli_error($link));
											$rvalue = mysqli_affected_rows($link);
											if ($rvalue > 0) {

												// 更新coupon的使用 update mycoupon set using_flag=1, usging_date=NOW() where coupon_no='$coupon_no';
												$sql2="update mycoupon set using_flag=1, using_date=NOW() where using_flag=0 and coupon_no='$coupon_no'";
												mysqli_query($link,$sql2) or die(mysqli_error($link));

												if (intval($bonus_point) > 0) {
													// 點數扣除
													$sql="INSERT INTO mybonus (member_id,order_no,bonus_date,bonus_type,bonus,bonus_created_at) VALUES ";
													$sql=$sql." ($mid,'$order_no',NOW(),2,$bonus_point,NOW());";
													mysqli_query($link,$sql) or die(mysqli_error($link));
												
													//更新會員的點數總和:
													$sql2="update member set member_usingpoints=member_usingpoints+$bonus_point,member_updated_at=NOW() where mid=$mid";
													mysqli_query($link,$sql2) or die(mysqli_error($link));		
												}

												// 馬上發點
												//if (intval($order_pay) > 0) {
												//	$bonus = intval($order_pay);
												//	$sql="INSERT INTO mybonus (member_id,order_no,bonus_date,bonus_type,bonus,bonus_created_at) VALUES ";
												//	$sql=$sql." ($mid,'$order_no',NOW(),1,$bonus_point,NOW());";
												//	mysqli_query($link,$sql) or die(mysqli_error($link));
													//更新會員的點數總和:
												//	$sql2="update member set member_totalpoints=member_totalpoints+$bonus,member_updated_at=NOW() where mid=$mid";
												//	mysqli_query($link,$sql2) or die(mysqli_error($link));		
												//	$sql3="update orderinfo set bonus=$bonus,order_status=2,order_updated_at=NOW() where order_no='".$order_no."'";
												//	mysqli_query($link,$sql3) or die(mysqli_error($link));		
												//}
												//SELECT a.*,b.bonus_mode,c.* from orderinfo a inner join ( select store_id, bid,bonus_mode from bonus_store) as b ON a.store_id= b.store_id inner join ( select * from bonus_setting) as c ON b.bid= c.bid where order_no='16199753935763' and store_service='2';

												// store_service = 1 二週後發點; store_service = 2 馬上發點
												if (intval($order_pay) > 0) {
													$sql5="SELECT a.*,b.bonus_mode,c.* from orderinfo a inner join ( select store_id, bid,bonus_mode from bonus_store) as b ON a.store_id= b.store_id inner join ( select * from bonus_setting) as c ON b.bid= c.bid where order_no='".$order_no."' and c.bonus_status = 0 ";
													//echo $sql5;
													if ($result5 = mysqli_query($link, $sql5)){
														if (mysqli_num_rows($result5) > 0){

															while($row5 = mysqli_fetch_array($result5)){
																$bid = $row5['bid'];
																$bonus_mode = $row5['bonus_mode'];
																$bonus_name1 = $row5['bonus_name1'];
																$sys_rate1 = $row5['sys_rate1'];
																$marketing_rate1 = $row5['marketing_rate1'];
																$bonus_name2 = $row5['bonus_name2'];
																$sys_rate2 = $row5['sys_rate2'];
																$marketing_rate2 = $row5['marketing_rate2'];
																$store_service = $row5['store_service'];
																$bonus_mode = 1;
																if ($bonus_mode == 1) {
																	
																	$user_rate = $row5['user_rate'];
																	$bonus = floor(intval($order_pay) * $user_rate) ;
																	$event_rate = 0;
																}
																if ($bonus_mode == 2) {
																	$user_rate = 0;
																	$event_rate = $row5['event_rate'];
																	$bonus = floor(intval($order_pay) * $event_rate) ;
																	
																}
																$group_mode=$row5['group_mode'];								
																if ($row5['group_mode'] == 1) {
																	
																	$groupmode_rate = $row5['groupmode_rate'];
																	
																} else {
																	$groupmode_rate = 0;
																}
																$hotel_mode = $row5['hotel_mode'];
																if ($row5['hotel_mode'] == 2) {
																	
																	$hotelmode_rate = $row5['hotelmode_rate'];
																	
																} else {
																	$hotelmode_rate = 0;
																}	
																
																$sql="INSERT INTO `profit_share` (order_no,order_amount,order_pay,store_id,bid,bonus_name1,sys_rate1,marketing_rate1,bonus_name2,sys_rate2,marketing_rate2,bonus_mode,user_rate,event_rate,group_mode,groupmode_rate,hotel_mode,hotelmode_rate,store_service,profit_date,profit_status) VALUES ";
																$sql=$sql." ('$order_no',$order_amount,$order_pay,$membersid,$bid,'$bonus_name1',$sys_rate1,$marketing_rate1,'$bonus_name2','$sys_rate2','$marketing_rate2',$bonus_mode,$user_rate,$event_rate,'$group_mode',$groupmode_rate,'$hotel_mode',$hotelmode_rate,'$store_service',NOW(),0);";
																//echo $sql;
																mysqli_query($link,$sql) or die(mysqli_error($link));
																
																if ($store_service == '2') {
																	$sql="INSERT INTO mybonus (member_id,order_no,bonus_date,bonus_type,bonus,bonus_created_at) VALUES ";
																	$sql=$sql." ($mid,'$order_no',NOW(),1,$bonus,NOW());";
																	mysqli_query($link,$sql) or die(mysqli_error($link));
																	//更新會員的點數總和:
																	$sql2="update member set member_totalpoints=member_totalpoints+$bonus,member_updated_at=NOW() where mid=$mid";
																	mysqli_query($link,$sql2) or die(mysqli_error($link));		
																	//$sql3="update orderinfo set bonus=$bonus,order_status=2,order_updated_at=NOW() where order_no='".$order_no."'";
																	//mysqli_query($link,$sql3) or die(mysqli_error($link));
																	
																	
																	// add-2022-05-19:更新到期日期
																	// add-2022-06-01 更改到期日期
																	// add-2022-06-02 修bug
																	// date_default_timezone_set('Asia/Taipei');
																	$month = date('m');
																	if ($month >= 1 && $month <= 6){
																		$sql3="update orderinfo set urate=$user_rate,bonus_get=$bonus,bonus_date=NOW(),bonus_end_date= CONCAT(EXTRACT(YEAR FROM NOW()),'-12-31 23:59:59') where order_no=$order_no";
																	}else{
																		$sql3="update orderinfo set urate=$user_rate,bonus_get=$bonus,bonus_date=NOW(),bonus_end_date= CONCAT(EXTRACT(YEAR FROM NOW())+1,'-06-30 23:59:59') where order_no=$order_no";
																	}
																	
																	//bonus_get,a.bonus_date
																	// $sql3="update orderinfo set urate=$user_rate ,bonus_get=$bonus,bonus_date=NOW() where order_no='".$order_no."'";
																	mysqli_query($link,$sql3) or die(mysqli_error($link));		
																}
																if ($store_service == '1') {


																	// add-2022-06-02 新增延後發點 -> 新增到mybonus
																	$date2 = new DateTime(date("Y-m-d"));
																	$date1 = date('Y-m-d',strtotime('+14 day'));
																	$sql="INSERT INTO mybonus (member_id,order_no,bonus_date,bonus_type,bonus,bonus_created_at) VALUES ";
																	$sql=$sql." ($mid,'$order_no',".$date1->format('Y-m-d')." 00:04:00',1,$bonus,NOW());";
																	mysqli_query($link,$sql) or die(mysqli_error($link));
																	//更新會員的點數總和:
																	$sql2="update member set member_totalpoints=member_totalpoints+$bonus,member_updated_at=NOW() where mid=$mid";
																	mysqli_query($link,$sql2) or die(mysqli_error($link));


																	// $date2 = new DateTime(date("Y-m-d"));
																	//echo $date2->format('Y-m-d') . "\n";
																	// $date1 = $date2->modify('+14 day');

																	
																	// add-2022-05-19:更新到期日期
																	// add-2022-06-01 更改到期日期
																	// add-2022-06-02 修bug
																	// date_default_timezone_set('Asia/Taipei');
																	$date2 = new DateTime(date("Y-m-d"));
																	$date1 = date('Y-m-d',strtotime('+14 day'));
																	// $month = date('m');
																	if ($date1->format('m') >= 1 && $date1->format('m') <= 6){
																		$sql3="update orderinfo set urate=$user_rate,bonus_get=$bonus,bonus_date='".$date1->format('Y-m-d')." 00:04:00',bonus_end_date= '".date("Y",strtotime("+0 year",strtotime($date1)))."-12-31 23:59:59' where order_no=$order_no";
																	}else{
																		$sql3="update orderinfo set urate=$user_rate,bonus_get=$bonus,bonus_date='".$date1->format('Y-m-d')." 00:04:00',bonus_end_date= '".date("Y",strtotime("+1 year",strtotime($date1)))."-06-30 23:59:59' where order_no=$order_no";
																	}
																	

																	//echo $date1->format('Y-m-d') . "\n";	
																	// $sql3="update orderinfo set urate=$user_rate ,bonus_get=$bonus,bonus_date='".$date1->format('Y-m-d')." 00:05:00' where order_no='".$order_no."'";
																	//echo $sql3;
																	mysqli_query($link,$sql3) or die(mysqli_error($link));		
																	//echo $sql3;											
																}									
															}
															
														}
													}	
												}
												//Save_Log($link,$_SESSION['userid'],$_SESSION['accname'],$_SESSION['userid'],'Add Order',$_SESSION['authority']);
												Save_Log($link,$member_id,$member_name,$membersid,'Add Order',4);
								
												$data["status"]="true";
												$data["code"]="0x0200";
												$data["responseMessage"]="The order data is successfully added!";	
												
											}else{
												$data["status"]="false";
												$data["code"]="0x0206";
												$data["responseMessage"]="Add order fail!";								
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
											$data["code"]="0x0201";
											$data["responseMessage"]="不是本店家發行的票券無法核銷,核銷失敗!";											
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
											
											//oid,order_no,order_date,store_id,tour_guide,member_id,order_amount,coupon_no,discount_amount,pay_type,order_pay,pay_status,order_status

											$sql="INSERT INTO orderinfo (order_no,order_date,store_id,tour_guide,member_id,order_amount,coupon_no,discount_amount,pay_type,order_pay,pay_status,bonus_point,order_status) VALUES ";
											$sql=$sql." ('$order_no',NOW(),$membersid,$tour_guide,$mid,$order_amount,'$coupon_no',$discount_amount,$pay_type,$order_pay,1,$bonus_point,1);";
											//add_order(member_id,store_id,coupon_no,order_amount,discount_amount,pay_type,order_pay,order_status
											//echo $sql;
											//exit;  //('16289044253777',NOW(),2,1,1,100,'',,1,100,1,,1);
									
											mysqli_query($link,$sql) or die(mysqli_error($link));
											$rvalue = mysqli_affected_rows($link);
											if ($rvalue > 0) {
												if (intval($bonus_point) > 0) {
													// 點數扣除
													$sql="INSERT INTO mybonus (member_id,order_no,bonus_date,bonus_type,bonus,bonus_created_at) VALUES ";
													$sql=$sql." ($mid,'$order_no',NOW(),2,$bonus_point,NOW());";
													mysqli_query($link,$sql) or die(mysqli_error($link));
												
													//更新會員的點數總和:
													$sql2="update member set member_usingpoints=member_usingpoints+$bonus_point,member_updated_at=NOW() where mid=$mid";
													mysqli_query($link,$sql2) or die(mysqli_error($link));		
												}

												// 馬上發點
												//if (intval($order_pay) > 0) {
												//	$bonus = intval($order_pay);
												//	$sql="INSERT INTO mybonus (member_id,order_no,bonus_date,bonus_type,bonus,bonus_created_at) VALUES ";
												//	$sql=$sql." ($mid,'$order_no',NOW(),1,$bonus_point,NOW());";
												//	mysqli_query($link,$sql) or die(mysqli_error($link));
													//更新會員的點數總和:
												//	$sql2="update member set member_totalpoints=member_totalpoints+$bonus,member_updated_at=NOW() where mid=$mid";
												//	mysqli_query($link,$sql2) or die(mysqli_error($link));		
												//	$sql3="update orderinfo set bonus=$bonus,order_status=2,order_updated_at=NOW() where order_no='".$order_no."'";
												//	mysqli_query($link,$sql3) or die(mysqli_error($link));		
												//}
												// store_service = 1 二週後發點; store_service = 2 馬上發點
												if (intval($order_pay) > 0) {
													$sql5="SELECT a.*,b.bonus_mode,c.* from orderinfo a inner join ( select store_id, bid,bonus_mode from bonus_store) as b ON a.store_id= b.store_id inner join ( select * from bonus_setting) as c ON b.bid= c.bid where order_no='".$order_no."' and c.bonus_status = 0 ";
													//echo $sql5;
													if ($result5 = mysqli_query($link, $sql5)){
														if (mysqli_num_rows($result5) > 0){

															while($row5 = mysqli_fetch_array($result5)){
																$bid = $row5['bid'];
																$bonus_mode = $row5['bonus_mode'];
																$bonus_name1 = $row5['bonus_name1'];
																$sys_rate1 = $row5['sys_rate1'];
																$marketing_rate1 = $row5['marketing_rate1'];
																$bonus_name2 = $row5['bonus_name2'];
																$sys_rate2 = $row5['sys_rate2'];
																$marketing_rate2 = $row5['marketing_rate2'];
																$store_service = $row5['store_service'];
																$bonus_mode = 1;
																if ($bonus_mode == 1) {
																	
																	$user_rate = $row5['user_rate'];
																	$bonus = floor(intval($order_pay) * $user_rate) ;
																	$event_rate = 0;
																}
																if ($bonus_mode == 2) {
																	$user_rate = 0;
																	$event_rate = $row5['event_rate'];
																	$bonus = floor(intval($order_pay) * $event_rate) ;
																	
																}
																$group_mode=$row5['group_mode'];								
																if ($row5['group_mode'] == 1) {
																	
																	$groupmode_rate = $row5['groupmode_rate'];
																	
																} else {
																	$groupmode_rate = 0;
																}
																$hotel_mode = $row5['hotel_mode'];
																if ($row5['hotel_mode'] == 2) {
																	
																	$hotelmode_rate = $row5['hotelmode_rate'];
																	
																} else {
																	$hotelmode_rate = 0;
																}	
																$sql="INSERT INTO `profit_share` (order_no,order_amount,order_pay,store_id,bid,bonus_name1,sys_rate1,marketing_rate1,bonus_name2,sys_rate2,marketing_rate2,bonus_mode,user_rate,event_rate,group_mode,groupmode_rate,hotel_mode,hotelmode_rate,store_service,profit_date,profit_status) VALUES ";
																$sql=$sql." ('$order_no',$order_amount,$order_pay,$membersid,$bid,'$bonus_name1',$sys_rate1,$marketing_rate1,'$bonus_name2','$sys_rate2','$marketing_rate2',$bonus_mode,$user_rate,$event_rate,'$group_mode',$groupmode_rate,'$hotel_mode',$hotelmode_rate,'$store_service',NOW(),0);";
																//echo $sql;
																mysqli_query($link,$sql) or die(mysqli_error($link));
																
																if ($store_service == '2') {
																	$sql="INSERT INTO mybonus (member_id,order_no,bonus_date,bonus_type,bonus,bonus_created_at) VALUES ";
																	$sql=$sql." ($mid,'$order_no',NOW(),1,$bonus,NOW());";
																	mysqli_query($link,$sql) or die(mysqli_error($link));
																	//更新會員的點數總和:
																	$sql2="update member set member_totalpoints=member_totalpoints+$bonus,member_updated_at=NOW() where mid=$mid";
																	mysqli_query($link,$sql2) or die(mysqli_error($link));		
																	//$sql3="update orderinfo set bonus=$bonus,order_status=2,order_updated_at=NOW() where order_no='".$order_no."'";
																	//mysqli_query($link,$sql3) or die(mysqli_error($link));	
																	
																	//bonus_get,a.bonus_date


																	// add-2022-05-19:更新到期日期
																	// add-2022-06-01 更改到期日期
																	// add-2022-06-02 修bug
																	// date_default_timezone_set('Asia/Taipei');
																	$month = date('m');
																	if ($month >= 1 && $month <= 6){
																		$sql3="update orderinfo set urate=$user_rate,bonus_get=$bonus,bonus_date=NOW(),bonus_end_date= CONCAT(EXTRACT(YEAR FROM NOW()),'-12-31 23:59:59') where order_no=$order_no";
																	}else{
																		$sql3="update orderinfo set urate=$user_rate,bonus_get=$bonus,bonus_date=NOW(),bonus_end_date= CONCAT(EXTRACT(YEAR FROM NOW())+1,'-06-30 23:59:59') where order_no=$order_no";
																	}


																	// $sql3="update orderinfo urate=$user_rate ,set bonus_get=$bonus,bonus_date=NOW() where order_no='".$order_no."'";
																	mysqli_query($link,$sql3) or die(mysqli_error($link));												
																}		
																if ($store_service == '1') {

																	
																	// add-2022-06-02 新增延後發點 -> 新增到mybonus
																	$date2 = new DateTime(date("Y-m-d"));
																	$date1 = date('Y-m-d',strtotime('+14 day'));
																	$sql="INSERT INTO mybonus (member_id,order_no,bonus_date,bonus_type,bonus,bonus_created_at) VALUES ";
																	$sql=$sql." ($mid,'$order_no',".$date1->format('Y-m-d')." 00:04:00',1,$bonus,NOW());";
																	mysqli_query($link,$sql) or die(mysqli_error($link));
																	//更新會員的點數總和:
																	$sql2="update member set member_totalpoints=member_totalpoints+$bonus,member_updated_at=NOW() where mid=$mid";
																	mysqli_query($link,$sql2) or die(mysqli_error($link));

																	
																	// $date2 = new DateTime(date("Y-m-d"));
																	//echo $date2->format('Y-m-d') . "\n";
																	// $date1 = $date2->modify('+14 day');
																	//echo $date1->format('Y-m-d') . "\n";
																	

																	// add-2022-05-19:更新到期日期
																	// add-2022-06-01 更改到期日期
																	// add-2022-06-02 修bug
																	// date_default_timezone_set('Asia/Taipei');
																	$date2 = new DateTime(date("Y-m-d"));
																	$date1 = date('Y-m-d',strtotime('+14 day'));
																	// $month = date('m');
																	if ($date1->format('m') >= 1 && $date1->format('m') <= 6){
																		$sql3="update orderinfo set urate=$user_rate,bonus_get=$bonus,bonus_date='".$date1->format('Y-m-d')." 00:04:00',bonus_end_date= '".date("Y",strtotime("+0 year",strtotime($date1)))."-12-31 23:59:59' where order_no=$order_no";
																	}else{
																		$sql3="update orderinfo set urate=$user_rate,bonus_get=$bonus,bonus_date='".$date1->format('Y-m-d')." 00:04:00',bonus_end_date= '".date("Y",strtotime("+1 year",strtotime($date1)))."-06-30 23:59:59' where order_no=$order_no";
																	}
																	

																	// $sql3="update orderinfo set urate=$user_rate ,bonus_get=$bonus,bonus_date='".$date1->format('Y-m-d')." 00:05:00' where order_no='".$order_no."'";
																	//echo $sql3;
																	mysqli_query($link,$sql3) or die(mysqli_error($link));		
																	//echo $sql3;										
																}									
															}
															
														}
													}	
												}
												//Save_Log($link,$_SESSION['userid'],$_SESSION['accname'],$_SESSION['userid'],'Add Order',$_SESSION['authority']);
												Save_Log($link,$member_id,$member_name,$membersid,'Add Order',4);
								
												$data["status"]="true";
												$data["code"]="0x0200";
												$data["responseMessage"]="The order data is successfully added!";	
												
											}else{
												$data["status"]="false";
												$data["code"]="0x0206";
												$data["responseMessage"]="Add order fail!";								
											}
										} catch (Exception $e) {
											//$this->_response(null, 401, $e->getMessage());
											//echo $e->getMessage();
											$data["status"]="false";
											$data["code"]="0x0202";
											$data["responseMessage"]=$e->getMessage();							
										}

							}	
										
						}else {
							$data["status"]="false";
							$data["code"]="0x0207";
							$data["responseMessage"]="The member id is wrong!";							
						}
					}else{
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