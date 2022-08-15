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
	$today = date("Y-m-d");

	// 找到訂單時間
	$sql = "SELECT * FROM `ecorderinfo` where pay_status = 0";
	// $sql = $sql." WHERE date_add(DATE(bonus_end_date),interval 1 day) = DATE('".$year.'-'.$today."')";
	// $sql = $sql." GROUP BY member_id";

	// echo $sql.'</br>';
	
	if ($result = mysqli_query($link, $sql)){
		if (mysqli_num_rows($result) > 0){
			echo mysqli_num_rows($result)."</br>";
			//$mid=0;
			// $store_name = "";
			while($row = mysqli_fetch_array($result)){
				$order_no = $row['order_no'];
				// $mid = $row['member_id'];
				$order_date = $row['order_date'];
				// $endBonusPoint = $row['endBonusPoint'];
				// echo $order_date.'</br>';
				// echo $endBonusPoint.'</br>';
                // echo $order_no."<br>";
                $time = ceil((strtotime($today) - strtotime($order_date))/ (60*60*24));
                // echo $time."<br>";
                if ($time > 7){
                    echo $order_date.'</br>';
                    echo $order_no."<br>";
                    echo "過期</br>";
                    $sql3 = "UPDATE ecorderinfo SET pay_status=-1 WHERE order_no=$order_no";
					// $sql3 = $sql3." ('$order_no',0,1,'$mid',0,0,0,0,1,0,0,1,NOW(),NOW(),'$bonusEndDate1','$addBonus1');";
					mysqli_query($link,$sql3) or die(mysqli_error($link));
                }
				
			}
		}else {
			echo "沒有超過七天的訂單";
		}
	}else {
		echo "一開始就連線錯誤";
	}

?>






