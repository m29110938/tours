<?php

$date2 = new DateTime(date("Y-m-d"));
//echo $date2->format('Y-m-d') . "\n";

$date1 = $date2->modify('-14 day');
//echo $date1->format('Y-m-d') . "\n";	
$sdate = "2021-12-01";
$edate = "2021-12-31";
$profit_month = "2021-12";

function insert_profitlist($conn,$sid,$sname,$urate,$sdate,$edate,$profit_month){
	try {
		
		//oid,order_no,sales_id,person_id,mobile_no,member_type,order_status,log_date
		$sql3 = "SELECT * FROM orderinfo where order_date > '".$sdate." 00:00:00' and order_date < '".$edate." 23:59:59' and store_id=$sid and order_status=1 and pay_status=1";
		//echo $sql3;
		if ($result2 = mysqli_query($conn, $sql3)){
			if (mysqli_num_rows($result2) > 0){
				$total_amountD = 0;
				$total_amountG = 0;
				$total_amountI = 0;
				$total_amountJ = 0;
				while($row2 = mysqli_fetch_array($result2)){
					$order_pay = $row2['order_pay'];
					$total_amountD = $total_amountD + $order_pay;
					$store_id = $row2['store_id'];
					$bonus = floor(intval($order_pay) * $urate) ;
					$total_amountG = $total_amountG + $bonus;
					$sys = floor(intval($order_pay) * 0.05) ;
					$total_amountI = $total_amountI + $sys;
					$total_amountJ = $total_amountJ + $bonus + $sys;
				}
				//insert profit_list
				$sql="INSERT INTO `profit` (store_id,profit_month,start_date,end_date, total_amountD,total_amountG,total_amountI,total_amountJ,profit_pdf,billing_date) VALUES ";
				$sql=$sql." ($store_id,'$profit_month','$sdate','$edate',$total_amountD,$total_amountG,$total_amountI,$total_amountJ,'uploads/profit.pdf',NOW());";
				//echo $sql;
				mysqli_query($conn,$sql) or die(mysqli_error($conn));
				
			}else {
				
			}
		}else {
			
		}
	} catch (Exception $e) {
		
	}	
	//return $pidpic;	
}

	$host = 'localhost';
	$user = 'tours_user';
	$passwd = 'tours0115';
	$database = 'toursdb';
	$link = mysqli_connect($host, $user, $passwd, $database);
	mysqli_query($link,"SET NAMES 'utf8'");
	
	$sql = "SELECT * FROM bonus_store as a ";
	$sql = $sql." inner JOIN (select * FROM bonus_setting) as b on a.bid=b.bid ";
	$sql = $sql." inner JOIN (select * FROM store) as c on a.store_id=c.sid ";
    $sql = $sql." where profit_period=0 and b.bonus_trash=0 and c.store_trash=0 ";
	//echo $sql."<br/>";
	//exit;
	if ($result = mysqli_query($link, $sql)){
		if (mysqli_num_rows($result) > 0){
			//$mid=0;
			while($row = mysqli_fetch_array($result)){
				$store_id = $row['store_id'];
				$sid = $row['sid'];
				$store_name = $row['store_name'];
				$user_rate = $row['user_rate'];
				
				insert_profitlist($link,$sid,$store_name,$user_rate,$sdate,$edate,$profit_month);
				
				//$sql="INSERT INTO `profit_share` (order_no,order_amount,order_pay,store_id,bid,bonus_name1,sys_rate1,marketing_rate1,bonus_name2,sys_rate2,marketing_rate2,bonus_mode,user_rate,event_rate,group_mode,groupmode_rate,hotel_mode,hotelmode_rate,store_service,profit_date,profit_status) VALUES ";
				//$sql=$sql." ('$order_no',$order_amount,$order_pay,$membersid,$bid,'$bonus_name1',$sys_rate1,$marketing_rate1,'$bonus_name2','$sys_rate2','$marketing_rate2',$bonus_mode,$user_rate,$event_rate,'$group_mode',$groupmode_rate,'$hotel_mode',$hotelmode_rate,'$store_service',NOW(),0);";
				//mysqli_query($link,$sql1) or die(mysqli_error($link));

				//$sql1="INSERT INTO mybonus (member_id,order_no,bonus_date,bonus_type,bonus,bonus_created_at) VALUES ";
				//$sql1=$sql1." ($mid,'$order_no',NOW(),1,$bonus,NOW());";
				//echo $sql1."<br/>";				
				//mysqli_query($link,$sql1) or die(mysqli_error($link));
			
			}
		}else {

		}
	}else {

	}

?>






