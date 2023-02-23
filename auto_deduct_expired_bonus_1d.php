<?php
	// add-2022-05-04 每日更新過期紅利
	$host = 'localhost';
	$user = 'tours_user';
	$passwd = 'tours0115';
	$database = 'toursdb';
	$link = mysqli_connect($host, $user, $passwd, $database);
	mysqli_query($link,"SET NAMES 'utf8'");

	
	// 測試用$today 01-01   07-01
	// $today = '07-01';
	// $year = 2024;
	// $year1 = $year-1;
	$today = date("m-d");
	$year = date("Y");
	$year1 = date("Y")-1;

	// 紅利到期時間內的總紅利獲得、總紅利折抵
	$sql = "SELECT member_id,SUM(bonus_get) as endBonusGet,SUM(bonus_point) as endBonusPoint FROM `orderinfo`  ";
	// add-2022-06-01 修改到期紅利
	// 要改時間
	$sql = $sql." WHERE date_add(DATE(bonus_end_date),interval 1 day) = DATE('".$year.'-'.$today."')";
	$sql = $sql." GROUP BY member_id";

	// echo $sql.'</br>';
	
	if ($result = mysqli_query($link, $sql)){
		if (mysqli_num_rows($result) > 0){
			echo mysqli_num_rows($result)."</br>";
			//$mid=0;
			// $store_name = "";
			while($row = mysqli_fetch_array($result)){
				// $order_no = $row['order_no'];
				$mid = $row['member_id'];
				$endBonusGet = $row['endBonusGet'];
				$endBonusPoint = $row['endBonusPoint'];
				echo $endBonusGet.'</br>';
				echo $endBonusPoint.'</br>';


				// 一整年的總紅利折抵
				// 要改時間 新增訂單狀態及付款狀態判斷、點數入帳時間調整為訂單時間
				$sql1 = "SELECT member_id,SUM(bonus_point) as bonusPoint FROM `orderinfo`   ";
				$sql1 = $sql1." WHERE DATE(order_date) >= '".$year1.'-'.$today."' AND DATE(order_date) <= '".$year.'-'.$today."' and member_id = $mid and order_status=1 and pay_status=1 ";
				// 要改時間
				// $sql1 = "SELECT member_id,SUM(bonus_point) as bonusPoint FROM `orderinfo`   ";
				// $sql1 = $sql1." WHERE bonus_date BETWEEN '".$year1.'-'.$today."' AND '".$year.'-'.$today."' and member_id = $mid";
				// echo $sql1;
				$sql1 = $sql1." GROUP BY member_id";
				$result1 = mysqli_query($link, $sql1);
				$row1 = mysqli_fetch_array($result1);
				$bonusPoint = $row1['bonusPoint'];
				echo $bonusPoint.'</br>';

				// 訂單編號
				$date = date_create();
				$rand = sprintf("%04d", rand(0,9999));
				$order_no = date_timestamp_get($date).$rand;

				if (($endBonusGet - $bonusPoint) > 0 ){
					// echo "還有剩下的點數過期";
					
					$addBonus = $endBonusGet - $bonusPoint;  // 點數：給消費者看，-過期
					$addBonus1 = $bonusPoint - $endBonusPoint;  // 點數：隱藏，+被多抵銷的
					// store_id: 0
					
					if ($today == "01-01"){
						$bonusEndDate = $year1.'-12-31 23:59:59';  // 日期：給消費者看，-過期
						$bonusEndDate1 = $year.'-06-30 23:59:59';  // 日期：隱藏，+被多抵銷的
					}
					elseif ($today == "07-01"){
						$bonusEndDate = $year.'-06-30 23:59:59';  // 日期：給消費者看，-過期
						$bonusEndDate1 = $year.'-12-31 23:59:59';  // 日期：隱藏，+被多抵銷的
					}
					// 隱藏，+被多抵銷的
					$sql3 = "INSERT INTO orderinfo (order_no,store_id,tour_guide,member_id,order_amount,discount_amount,pay_type,order_pay,pay_status,bonus_point,urate,order_status,order_created_at,bonus_date,bonus_end_date,bonus_get) VALUES ";
					$sql3 = $sql3." ('$order_no',0,1,'$mid',0,0,0,0,1,0,0,1,NOW(),NOW(),'$bonusEndDate1','$addBonus1');";
					mysqli_query($link,$sql3) or die(mysqli_error($link));
					// 給消費者看，-過期
					// store_id ： 測試版： 129, 正式版： 184
					$sql4 = "INSERT INTO orderinfo (order_no,store_id,tour_guide,member_id,order_amount,discount_amount,pay_type,order_pay,pay_status,bonus_point,urate,order_status,order_created_at,bonus_date,bonus_end_date,bonus_get) VALUES ";
					$sql4 = $sql4." ('$order_no',184,1,'$mid',0,0,-1,0,0,0,0,0,'$bonusEndDate','$bonusEndDate','$bonusEndDate',-'$addBonus');";
					mysqli_query($link,$sql4) or die(mysqli_error($link));
					$sql5 = "INSERT INTO mybonus (member_id,order_no,bonus_date,bonus_type,bonus,bonus_created_at) VALUES ";
					$sql5 = $sql5." ('$mid','$order_no','$bonusEndDate',2,'$addBonus','$bonusEndDate');";
					mysqli_query($link,$sql5) or die(mysqli_error($link));

				}
				elseif (($endBonusGet - $bonusPoint) < 0 ){
					// echo "沒有剩下點數過期，並多扣了一些";
					
					$addBonus = $endBonusGet - $endBonusPoint;
					// store_id: 0
					
					if ($today == "01-01"){
						$bonusEndDate = $year.'-06-30 23:59:59';
					}
					elseif ($today == "07-01"){
						$bonusEndDate = $year.'-12-31 23:59:59';
					}
					// 隱藏，+被多抵銷的
					$sql3 = "INSERT INTO orderinfo (order_no,store_id,tour_guide,member_id,order_amount,discount_amount,pay_type,order_pay,pay_status,bonus_point,urate,order_status,order_created_at,bonus_date,bonus_end_date,bonus_get) VALUES ";
					$sql3 = $sql3." ('$order_no',0,1,'$mid',0,0,0,0,1,0,0,1,NOW(),NOW(),'$bonusEndDate','$addBonus');";
					mysqli_query($link,$sql3) or die(mysqli_error($link));
				}else{
					// echo "剛好沒有剩下";
				}

				// $date = date_create();
				// $rand = sprintf("%04d", rand(0,9999));
				// $order_no = date_timestamp_get($date).$rand;
				
				// // store_id ： 測試版：129, 正式版：184
				// // 新增過期點數到orderinfo
				// // add-2022-06-01 修改到期紅利
				// $sql3 = "INSERT INTO orderinfo (order_no,store_id,tour_guide,member_id,order_amount,discount_amount,pay_type,order_pay,pay_status,bonus_point,urate,order_status,order_created_at,bonus_date,bonus_get) VALUES ";
				// $sql3 = $sql3." ($order_no,129,1,$mid,-1,0,-1,0,1,0,0,1,NOW(),NOW(),-$total);";
				// mysqli_query($link,$sql3) or die(mysqli_error($link));
				// // 新增過期點數到mybonus
				// $sql5 = "INSERT INTO mybonus (member_id,order_no,bonus_date,bonus_type,bonus,bonus_created_at) VALUES ";
				// $sql5 = $sql5." ($mid,$order_no,NOW(),2,$total,NOW());";
				// mysqli_query($link,$sql5) or die(mysqli_error($link));

				// //更新會員的點數總和:
				// $sql2="update member set member_totalpoints=member_totalpoints-$total,member_updated_at=NOW() where mid=$mid";
				// mysqli_query($link,$sql2) or die(mysqli_error($link));	
                

				echo "</br>成功";
			}
		}else {
			echo "沒有過期的資料";
		}
	}else {
		echo "一開始就連線錯誤";
	}

?>