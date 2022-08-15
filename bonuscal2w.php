<?php

$date2 = new DateTime(date("Y-m-d"));
//echo $date2->format('Y-m-d') . "\n";

$date1 = $date2->modify('-14 day');
//echo $date1->format('Y-m-d') . "\n";			

	$host = 'localhost';
	$user = 'tours_user';
	$passwd = 'tours0115';
	$database = 'toursdb';
	$link = mysqli_connect($host, $user, $passwd, $database);
	mysqli_query($link,"SET NAMES 'utf8'");
	

	$sql = "SELECT a.*,b.member_id,b.urate,b.order_date,b.pay_status,b.order_status FROM profit_share as a ";
	$sql = $sql." inner join (select order_no,member_id,order_date,urate,pay_status,order_status from orderinfo) as b on a.order_no=b.order_no ";
    $sql = $sql." WHERE store_service='1' and profit_date >='".$date1->format('Y-m-d')." 00:00:00' and profit_date <='".$date1->format('Y-m-d')." 23:59:59' and b.pay_status=1 and b.order_status=1 ";
	//echo $sql."<br/>";
	//exit;
	if ($result = mysqli_query($link, $sql)){
		if (mysqli_num_rows($result) > 0){
			//$mid=0;
			$store_name = "";
			while($row = mysqli_fetch_array($result)){
				$order_no = $row['order_no'];
				$mid = $row['member_id'];
				$order_pay = $row['order_pay'];
				$urate = $row['urate'];
				$bonus = floor(intval($order_pay) * $urate) ;
				//$bonus_mode = 1;
				//if ($bonus_mode == 1) {
					
				//	$user_rate = $row['user_rate'];
				//	$bonus = floor(intval($order_pay) * $user_rate) ;
				//	$event_rate = 0;
				//}
				//if ($bonus_mode == 2) {
				//	$user_rate = 0;
				//	$event_rate = $row['event_rate'];
				//	$bonus = floor(intval($order_pay) * $event_rate) ;
				//	
				//}
				//$sql="INSERT INTO `profit_share` (order_no,order_amount,order_pay,store_id,bid,bonus_name1,sys_rate1,marketing_rate1,bonus_name2,sys_rate2,marketing_rate2,bonus_mode,user_rate,event_rate,group_mode,groupmode_rate,hotel_mode,hotelmode_rate,store_service,profit_date,profit_status) VALUES ";
				//$sql=$sql." ('$order_no',$order_amount,$order_pay,$membersid,$bid,'$bonus_name1',$sys_rate1,$marketing_rate1,'$bonus_name2','$sys_rate2','$marketing_rate2',$bonus_mode,$user_rate,$event_rate,'$group_mode',$groupmode_rate,'$hotel_mode',$hotelmode_rate,'$store_service',NOW(),0);";

				$sql1="INSERT INTO mybonus (member_id,order_no,bonus_date,bonus_type,bonus,bonus_created_at) VALUES ";
				$sql1=$sql1." ($mid,'$order_no',NOW(),1,$bonus,NOW());";
				//echo $sql1."<br/>";				
				mysqli_query($link,$sql1) or die(mysqli_error($link));
				
				//更新會員的點數總和:
				$sql2="update member set member_totalpoints=member_totalpoints+$bonus,member_updated_at=NOW() where mid=$mid";
				//echo $sql2."<br/>";
				mysqli_query($link,$sql2) or die(mysqli_error($link));		
				
				//bonus_get,a.bonus_date
				//$sql3="update orderinfo set bonus_get=$bonus,bonus_date=NOW() where order_no='".$order_no."'";
				//echo $sql3."<br/>";
				//mysqli_query($link,$sql3) or die(mysqli_error($link));											

			
			}
		}else {

		}
	}else {

	}

?>






