<?php
	// add-2022-05-04 每日更新過期紅利
	$host = 'localhost';
	$user = 'tours_user';
	$passwd = 'tours0115';
	$database = 'toursdb';
	$link = mysqli_connect($host, $user, $passwd, $database);
	mysqli_query($link,"SET NAMES 'utf8'");

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

	// 測試用$today 01-01   07-01
	// $today = '07-01';
	// $year = 2024;
	// $year1 = $year-1;
	date_default_timezone_set('Asia/Taipei');
	$today = date("m");
	$sdate = date("Y-m-01 00:00:00");
	$edate = date('Y-m-t 23:59:59');
	
	// $today = "09";
	// $sdate = "2022-10-01 00:00:00";
	// $edate = "2022-10-31 23:59:59";

	// echo $edate."<br>";
	// echo $today."<br>";
	// $year = date("Y");
	// $year1 = date("Y")-1;

	// 判斷當月生日的會員
	$sql = "SELECT * FROM `member` ";
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

				$sql2 = "SELECT * FROM `coupon` as a ";
				$sql2 = $sql2." left join (select * from membercard) as b on a.coupon_storeid=b.store_id";
				$sql2 = $sql2." WHERE a.coupon_type in (2,5) and a.coupon_storeid > 0 and a.coupon_number = -9999 and a.coupon_status = 1 and a.coupon_trash = 0 and DATE(a.coupon_issue_startdate) <= DATE('".$sdate."') and DATE(a.coupon_issue_enddate) >= DATE('".$sdate."') and b.member_id='$mid'";
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
								$result5 = mysqli_query($link, $sql5);
								// echo mysqli_num_rows($result5);
								// echo "店家生日禮"."<br>";
								
								if ($result5 = mysqli_query($link, $sql5)) {
									if (mysqli_num_rows($result5) > 0) {
										$row5 = mysqli_fetch_array($result5);
										// echo $row5['store_name']."<br>";
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
								// echo "平台生日禮"."<br>";
								$push_body = '親愛的會員，點點送了您一份禮物，祝您生日快樂~~\n請記得到"優惠券"頁面使用哦~~';
								// echo $push_title."<br>";
								// echo $push_body."<br>";
								if ($notificationToken != ""){
									push_tomember($mid,$push_title,$push_body);
								}
							}
							// echo $member_birthday."<br>"; 

							$sql3="INSERT INTO mycoupon (mid, coupon_no, cid,coupon_id ,coupon_name, coupon_type, coupon_description, coupon_startdate, coupon_enddate, coupon_status, coupon_rule, coupon_discount, discount_amount, coupon_storeid, coupon_for, coupon_picture) VALUES ";
							$sql3=$sql3." ($mid,'$coupon_no','$cid','$coupon_id' ,'$coupon_name', '$coupon_type', '$coupon_description', '$sdate', '$edate', '$coupon_status', '$coupon_rule', '$coupon_discount', '$discount_amount', '$coupon_storeid', '$coupon_for', '' )";
							// echo $sql3;
							mysqli_query($link,$sql3) or die(mysqli_error($link));
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

?>






