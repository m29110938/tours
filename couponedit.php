<?php
	session_start();
	//if ($_SESSION['authority']!="1"){
	//	header("Location: main.php");
	//}
	function push_tomember($member,$push_title,$push_body){
		$vowels = array("\\n");
		$push_body = str_replace($vowels,"\n",$push_body);
		$curl = curl_init();
		curl_setopt_array($curl, array(
			// CURLOPT_URL => 'https://ddotapp.com.tw/tours/api/push_tomember.php',
			CURLOPT_URL => 'https://tripspottest.jotangi.net/tours/api/push_tomember.php',
			// CURLOPT_URL => 'http://localhost/tours/api/push_tomember.php',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => 'push_memberid='.$member.'&push_title='.$push_title.'&push_body='.$push_body,
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/x-www-form-urlencoded'
			),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		// echo $response."<br>";
		usleep(100000);  // 停止0.1秒
	}
	$act = isset($_POST['act']) ? $_POST['act'] : '';
	$tid = isset($_POST['tid']) ? $_POST['tid'] : '';
	if ($act == 'Edit') {
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';
		
		$link = mysqli_connect($host, $user, $passwd, $database);
		mysqli_query($link,"SET NAMES 'utf8'");
				
		// $coupon_id = isset($_POST['coupon_id']) ? $_POST['coupon_id'] : '';
		// $coupon_id  = mysqli_real_escape_string($link,$coupon_id);

		$sid_coupon_id = isset($_POST['sid_coupon_id']) ? $_POST['sid_coupon_id'] : '';
		$sid_coupon_id  = mysqli_real_escape_string($link,$sid_coupon_id);
		$storeid = explode("_", $sid_coupon_id)[0];
		// echo $storeid;


		$coupon_type = isset($_POST['coupon_type']) ? $_POST['coupon_type'] : '0';
		$coupon_type  = mysqli_real_escape_string($link,$coupon_type);
		
		$coupon_name = isset($_POST['coupon_name']) ? $_POST['coupon_name'] : '';
		$coupon_name  = mysqli_real_escape_string($link,$coupon_name);

		$coupon_description = isset($_POST['coupon_description']) ? $_POST['coupon_description'] : '';
		$coupon_description  = mysqli_real_escape_string($link,$coupon_description);

		$coupon_issue_startdate = isset($_POST['coupon_issue_startdate']) ? $_POST['coupon_issue_startdate'] : '0000-00-00 00:00:00';
		$coupon_issue_startdate  = mysqli_real_escape_string($link,$coupon_issue_startdate);

		$coupon_issue_enddate = isset($_POST['coupon_issue_enddate']) ? $_POST['coupon_issue_enddate'] : '0000-00-00 00:00:00';
		$coupon_issue_enddate  = mysqli_real_escape_string($link,$coupon_issue_enddate);

		if($coupon_type == 2 || $coupon_type == 5 ){
			$coupon_issue_startdate .= "-01";
			$coupon_issue_enddate .= "-01";
		}
		
		$coupon_startdate = isset($_POST['coupon_startdate']) ? $_POST['coupon_startdate'] : '0000-00-00 00:00:00';
		$coupon_startdate  = mysqli_real_escape_string($link,$coupon_startdate);

		$coupon_enddate = isset($_POST['coupon_enddate']) ? $_POST['coupon_enddate'] : '0000-00-00 00:00:00';
		$coupon_enddate  = mysqli_real_escape_string($link,$coupon_enddate);
		
		$coupon_rule = isset($_POST['coupon_rule']) ? $_POST['coupon_rule'] : '0';
		$coupon_rule  = mysqli_real_escape_string($link,$coupon_rule);

		$coupon_discount = isset($_POST['coupon_discount']) ? $_POST['coupon_discount'] : '0';
		$coupon_discount  = mysqli_real_escape_string($link,$coupon_discount);

		$discount_amount = isset($_POST['discount_amount']) ? $_POST['discount_amount'] : '0';
		$discount_amount  = mysqli_real_escape_string($link,$discount_amount);

		$coupon_storeid = isset($_POST['coupon_storeid']) ? $_POST['coupon_storeid'] : '0';
		$coupon_storeid  = mysqli_real_escape_string($link,$coupon_storeid);

		$coupon_status = isset($_POST['coupon_status']) ? $_POST['coupon_status'] : '0';
		$coupon_status  = mysqli_real_escape_string($link,$coupon_status);

		$sql = "select * from store where store_id = '$storeid'";
		// echo $sql;
		if ($result = mysqli_query($link, $sql)) {
			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_array($result)) {
					$coupon_storeid =  $row['sid'];
				}
			}
		}
		$sql2 = "select * from coupon where coupon_id = '$sid_coupon_id' and coupon_trash=0 and coupon_status = 1";
		// echo $sql2;
		if ($result2 = mysqli_query($link, $sql2)) {
			if (mysqli_num_rows($result2) == 1) {
				while ($row = mysqli_fetch_array($result2)) {
					$cid =  $row['cid'];
				}
				if ($cid != $tid){
					echo "<script>alert('優惠券編號重複');</script>";
					?>
					<form action="editcoupon.php" method="Post" name='frm1' id='frm1' class="card">
						<input type="hidden" name="act" id="act"  value="Edit"/>
						<input type="hidden" name="tid" id="tid"  value="<?=$tid?>"/>
					</form>
					<script>document.frm1.submit();</script>
					<?php
				}else{
					echo "<script>alert('修改成功');</script>";
					$sql="update coupon set coupon_id='$sid_coupon_id',coupon_type=$coupon_type,coupon_name='$coupon_name',coupon_description='$coupon_description',coupon_issue_startdate='$coupon_issue_startdate',coupon_issue_enddate='$coupon_issue_enddate',coupon_startdate='$coupon_startdate',coupon_enddate='$coupon_enddate',coupon_rule=$coupon_rule,coupon_discount=$coupon_discount,discount_amount=$discount_amount,coupon_storeid=$coupon_storeid,coupon_status=$coupon_status where cid=$tid ;";

					mysqli_query($link,$sql) or die(mysqli_error($link));
					$_SESSION['saveresult']="修改優惠票券資料成功!";
				}

				// echo $sql;
				//exit;
				
			
			}
			elseif(mysqli_num_rows($result2) == 0){
				echo "<script>alert('修改成功');</script>";
				$sql="update coupon set coupon_id='$sid_coupon_id',coupon_type=$coupon_type,coupon_name='$coupon_name',coupon_description='$coupon_description',coupon_issue_startdate='$coupon_issue_startdate',coupon_issue_enddate='$coupon_issue_enddate',coupon_startdate='$coupon_startdate',coupon_enddate='$coupon_enddate',coupon_rule=$coupon_rule,coupon_discount=$coupon_discount,discount_amount=$discount_amount,coupon_storeid=$coupon_storeid,coupon_status=$coupon_status where cid=$tid ;";

				mysqli_query($link,$sql) or die(mysqli_error($link));
				$_SESSION['saveresult']="修改優惠票券資料成功!";
			}
			else{
				echo "<script>alert('更新失敗');</script>";
				?>
				<form action="editcoupon.php" method="Post" name='frm1' id='frm1' class="card">
					<input type="hidden" name="act" id="act"  value="Edit"/>
					<input type="hidden" name="tid" id="tid"  value="<?=$tid?>"/>
				</form>
				<script>document.frm1.submit();</script>
				<?php
			}
		}
		
		// 發送生日禮
		if($coupon_type == "2" || $coupon_type == "5"){
			$today = date("Y-m-01");
			$sdate = date("Y-m-01 00:00:00");
			$edate = date('Y-m-t 23:59:59');	
			// 當天發送
			if($today >= $coupon_issue_startdate || $today <= $coupon_issue_enddate){
				// 判斷當月生日的會員
				$sql = "SELECT * FROM `member`  ";
				$sql = $sql." WHERE member_birthday IS not null and member_birthday != '0000-00-00' and member_sid = 0 and EXTRACT(month FROM member_birthday) = EXTRACT(month FROM '".$sdate."') and member_trash = 0 ";
				// echo $sql.'</br>';
				
				if ($result = mysqli_query($link, $sql)){
					if (mysqli_num_rows($result) > 0){
						// echo mysqli_num_rows($result)."</br>";
						//$mid=0;
						// $store_name = "";
						while($row = mysqli_fetch_array($result)){
							// $order_no = $row['order_no'];
							$mid = $row['mid'];
							$member_name = $row['member_name'];
							$member_birthday = $row['member_birthday'];
							$notificationToken = $row['notificationToken'];

							
							// echo $mid."<br>";
							// echo $member_birthday."<br>";
							// echo $member_name."<br>";

							$sql2 = "SELECT * FROM `coupon`  ";
							$sql2 = $sql2." WHERE coupon_id='$sid_coupon_id'";
							// echo $sql2;
							if ($result2 = mysqli_query($link, $sql2)) {
								if (mysqli_num_rows($result2) > 0) {
									while ($row2 = mysqli_fetch_array($result2)) {
										$push_title = "生日禮來囉!";
										$cid = $row2['cid'];
										$coupon_id = $row2['coupon_id'];
										$coupon_storeid = $row2['coupon_storeid'];
										$coupon_number_1 = $row2['coupon_number']-1;
										$coupon_no = uniqid();
										$coupon_name = $row2['coupon_name'];
										$coupon_type = $row2['coupon_type'];
										$coupon_description = $row2['coupon_description'];
										$coupon_startdate = $row2['coupon_startdate'];
										$coupon_enddate = $row2['coupon_enddate'];
										$coupon_status = $row2['coupon_status'];
										$coupon_rule = $row2['coupon_rule'];
										$coupon_discount = $row2['coupon_discount'];
										$discount_amount = $row2['discount_amount'];
										$coupon_storeid = $row2['coupon_storeid'];
										// echo $coupon_storeid;
										$coupon_for = $row2['coupon_for'];

										// echo $coupon_storeid;
										// echo $row2['coupon_name'];
										// echo $row2['coupon_type'];
										if ($row2['coupon_type'] == 2) {
											$sql5 = "SELECT * FROM `membercard` as a";
											$sql5=$sql5." INNER JOIN (SELECT * FROM store) as b on a.store_id = b.sid ";
											$sql5=$sql5." WHERE a.member_id = $mid and a.store_id = $coupon_storeid ";
											// echo $sql5;
											// $result5 = mysqli_query($link, $sql5);
											// echo mysqli_num_rows($result5);
											// echo "店家生日禮"."<br>";
											echo $sql5;
											if ($result5 = mysqli_query($link, $sql5)) {
												if (mysqli_num_rows($result5) > 0) {
													$row5 = mysqli_fetch_array($result5);
													// echo $row5['store_name']."<br>";

													$sql3="INSERT INTO mycoupon (mid, coupon_no, cid,coupon_id ,coupon_name, coupon_type, coupon_description, coupon_startdate, coupon_enddate, coupon_status, coupon_rule, coupon_discount, discount_amount, coupon_storeid, coupon_for, coupon_picture) VALUES ";
													$sql3=$sql3." ($mid,'$coupon_no','$cid','$coupon_id' ,'$coupon_name', '$coupon_type', '$coupon_description', '$sdate', '$edate', '$coupon_status', '$coupon_rule', '$coupon_discount', '$discount_amount', '$coupon_storeid', '$coupon_for', '' )";
													// echo $sql3;
													mysqli_query($link,$sql3) or die(mysqli_error($sql3));

													$push_body = '親愛的會員，'.$row5['store_name'].'送了您一份禮物，祝您生日快樂~~\n請記得到"優惠券"頁面使用哦~~';
													// echo $push_title."<br>";
													// echo $push_body."<br>";
													if ($notificationToken != ""){
														push_tomember($mid,$push_title,$push_body);
													}
												}
											}
										}
										elseif ($row2['coupon_type'] == 5) {
											$sql3="INSERT INTO mycoupon (mid, coupon_no, cid,coupon_id ,coupon_name, coupon_type, coupon_description, coupon_startdate, coupon_enddate, coupon_status, coupon_rule, coupon_discount, discount_amount, coupon_storeid, coupon_for, coupon_picture) VALUES ";
											$sql3=$sql3." ($mid,'$coupon_no','$cid','$coupon_id' ,'$coupon_name', '$coupon_type', '$coupon_description', '$sdate', '$edate', '$coupon_status', '$coupon_rule', '$coupon_discount', '$discount_amount', '$coupon_storeid', '$coupon_for', '' )";
											// echo $sql3;
											mysqli_query($link,$sql3) or die(mysqli_error($sql3));
											// echo "平台生日禮"."<br>";
											$push_body = '親愛的會員，點點送了您一份禮物，祝您生日快樂~~\n請記得到"優惠券"頁面使用哦~~';
											// echo $push_title."<br>";
											// echo $push_body."<br>";
											if ($notificationToken != ""){
												push_tomember($mid,$push_title,$push_body);
											}
										}
										// echo $member_birthday."<br>"; 

										
										// echo "成功";
										// $sql4="UPDATE coupon SET coupon_number=$coupon_number_1 where coupon_id = '".$coupon_id."' ";
										// echo $sql4;
										// mysqli_query($link,$sql4) or die(mysqli_error($sql4));

									}
								}
							}


							// echo "成功";
						}
					}else {
						echo "沒有人在當月份生日";
					}
				}else {
					echo "SQL錯誤";
				}
			}
		}
		
		
			
		echo "<script>window.location.href='coupon.php?act=Qry';</script>";
		// once saved, redirect back to the view page
		// header("Location: coupon.php?act=Qry");

		mysqli_close($link);
	}else{
		header("Location: logout.php");
	}
?>