<?php
	session_start();
	//if ($_SESSION['authority']!="1"){
	//	header("Location: main.php");
	//}
	//---------------------------------------------------------------------------------------------------------
	$act = isset($_POST['act']) ? $_POST['act'] : '';
	if ($act == 'Add') {
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';
		
		$link = mysqli_connect($host, $user, $passwd, $database);
		mysqli_query($link,"SET NAMES 'utf8'");
				
		$bonus_name1 = isset($_POST['bonus_name1']) ? $_POST['bonus_name1'] : '';
		$bonus_name1  = mysqli_real_escape_string($link,$bonus_name1);

		$sys_rate1 = isset($_POST['sys_rate1']) ? $_POST['sys_rate1'] : '0';
		$sys_rate1  = mysqli_real_escape_string($link,$sys_rate1);
		
		$marketing_rate1 = isset($_POST['marketing_rate1']) ? $_POST['marketing_rate1'] : '0';
		$marketing_rate1  = mysqli_real_escape_string($link,$marketing_rate1);

		$bonus_name2 = isset($_POST['bonus_name2']) ? $_POST['bonus_name2'] : '';
		$bonus_name2  = mysqli_real_escape_string($link,$bonus_name2);

		$sys_rate2 = isset($_POST['sys_rate2']) ? $_POST['sys_rate2'] : '0';
		$sys_rate2  = mysqli_real_escape_string($link,$sys_rate2);
		
		$marketing_rate2 = isset($_POST['marketing_rate2']) ? $_POST['marketing_rate2'] : '0';
		$marketing_rate2  = mysqli_real_escape_string($link,$marketing_rate2);


		$user_rate = isset($_POST['user_rate']) ? $_POST['user_rate'] : '0';
		$user_rate  = mysqli_real_escape_string($link,$user_rate);

		$startdate = isset($_POST['startdate']) ? $_POST['startdate'] : '';
		$startdate  = mysqli_real_escape_string($link,$startdate);
		//$enddate = isset($_POST['enddate']) ? $_POST['enddate'] : '';
		//$enddate  = mysqli_real_escape_string($link,$enddate);


		$event_rate = isset($_POST['event_rate']) ? $_POST['event_rate'] : '0';
		$event_rate  = mysqli_real_escape_string($link,$event_rate);
		
		$event_startdate = isset($_POST['event_startdate']) ? $_POST['event_startdate'] : '';
		$event_startdate  = mysqli_real_escape_string($link,$event_startdate);

		$event_enddate = isset($_POST['event_enddate']) ? $_POST['event_enddate'] : '';
		$event_enddate  = mysqli_real_escape_string($link,$event_enddate);

		//$group_mode = isset($_POST['group_mode']) ? $_POST['group_mode'] : '0';
		//$group_mode  = mysqli_real_escape_string($link,$group_mode);

		//$groupmode_rate = isset($_POST['groupmode_rate']) ? $_POST['groupmode_rate'] : '0';
		//$groupmode_rate  = mysqli_real_escape_string($link,$groupmode_rate);

		//$hotel_mode = isset($_POST['hotel_mode']) ? $_POST['hotel_mode'] : '0';
		//$hotel_mode  = mysqli_real_escape_string($link,$hotel_mode);

		//$hotelmode_rate = isset($_POST['hotelmode_rate']) ? $_POST['hotelmode_rate'] : '0';
		//$hotelmode_rate  = mysqli_real_escape_string($link,$hotelmode_rate);

		$store_service = isset($_POST['storeservice']) ? $_POST['storeservice'] : '';
		$store_service  = mysqli_real_escape_string($link,$store_service);

		$bonus_status = isset($_POST['bonus_status']) ? $_POST['bonus_status'] : '0';
		$bonus_status  = mysqli_real_escape_string($link,$bonus_status);
		
		$contract_startdate = isset($_POST['contract_startdate']) ? $_POST['contract_startdate'] : '';
		$contract_startdate  = mysqli_real_escape_string($link,$contract_startdate);

		$contract_enddate = isset($_POST['contract_enddate']) ? $_POST['contract_enddate'] : '';
		$contract_enddate  = mysqli_real_escape_string($link,$contract_enddate);

		$profit_period = isset($_POST['profit_period']) ? $_POST['profit_period'] : '';
		$profit_period  = mysqli_real_escape_string($link,$profit_period);
		
		if ($sys_rate1 == '') $sys_rate1=0;
		if ($marketing_rate1 == '') $marketing_rate1=0;
		if ($sys_rate2 == '') $sys_rate2=0;
		if ($marketing_rate2 == '') $marketing_rate2=0;
		if ($event_rate == '') $event_rate=0;

		//if ($groupmode_rate == '') $groupmode_rate=0;
		//if ($hotelmode_rate == '') $hotelmode_rate=0;
		//$sql="INSERT INTO bonus_setting (bonus_name1,sys_rate1,marketing_rate1,bonus_name2,sys_rate2,marketing_rate2,user_rate,startdate,event_rate,event_startdate,event_enddate,group_mode,groupmode_rate,hotel_mode,hotelmode_rate,store_service,bonus_status) VALUES ('$bonus_name1','$sys_rate1','$marketing_rate1','$bonus_name2','$sys_rate2','$marketing_rate2',$user_rate";
		$sql="INSERT INTO bonus_setting (bonus_name1,sys_rate1,marketing_rate1,bonus_name2,sys_rate2,marketing_rate2,user_rate,startdate,event_rate,event_startdate,event_enddate,store_service,bonus_status,contract_startdate,contract_enddate,profit_period) VALUES ('$bonus_name1','$sys_rate1','$marketing_rate1','$bonus_name2','$sys_rate2','$marketing_rate2',$user_rate";
		if ($startdate == '') {
			$sql=$sql.",NULL";
		}else{
			$sql=$sql.",'$startdate'";
		}
		//if ($enddate == '') {
		//	$sql=$sql.",NULL";
		//}else{
		//	$sql=$sql.",'$enddate'";
		//}
		if ($event_rate == '') {
			$sql=$sql.",'0'";
		}else{
			$sql=$sql.",'$event_rate'";
		}		
		if ($event_startdate == '') {
			$sql=$sql.",NULL";
		}else{
			$sql=$sql.",'$event_startdate'";
		}
		if ($event_enddate == '') {
			$sql=$sql.",NULL";
		}else{
			$sql=$sql.",'$event_enddate'";
		}		
		
		
		//$sql=$sql.",$group_mode,'$groupmode_rate',$hotel_mode,'$hotelmode_rate','$store_service',$bonus_status);";
		$sql=$sql.",'$store_service',$bonus_status,'$contract_startdate','$contract_enddate','$profit_period');";

		//echo $sql;
		//exit;

		mysqli_query($link,$sql) or die(mysqli_error($link));
		
		//$_SESSION['saveresult']="新增商店資料成功!";
			
		// once saved, redirect back to the view page
		header("Location: bonus.php?act=Qry");

		mysqli_close($link);
	}else{
		header("Location: logout.php");
	}
?>