<?php
	session_start();
	//if ($_SESSION['authority']!="1"){
	//	header("Location: main.php");
	//}
	//---------------------------------------------------------------------------------------------------------
	$act = isset($_POST['act1']) ? $_POST['act1'] : '';
	
	if ($act == 'Add') {
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';
		
		$link = mysqli_connect($host, $user, $passwd, $database);
		mysqli_query($link,"SET NAMES 'utf8'");
				
		$hid = isset($_POST['tid1']) ? $_POST['tid1'] : '';
		$hid  = mysqli_real_escape_string($link,$hid);

		$vacation = isset($_POST['vacation']) ? $_POST['vacation'] : '';
		$vacation  = mysqli_real_escape_string($link,$vacation);

		$vacation_name = isset($_POST['vacation_name']) ? $_POST['vacation_name'] : '';
		$vacation_name  = mysqli_real_escape_string($link,$vacation_name);

		$start_time = isset($_POST['start_time']) ? $_POST['start_time'] : '0';
		$start_time  = mysqli_real_escape_string($link,$start_time);

		$end_time = isset($_POST['end_time']) ? $_POST['end_time'] : '';
		$end_time  = mysqli_real_escape_string($link,$end_time);

		$vacation_period = $start_time.'-'.$end_time;
		
		$sql="INSERT INTO vacation (hid,vacation,vacation_name,vacation_period) VALUES ($hid,'$vacation','$vacation_name','$vacation_period');";

		//echo $sql;
		//exit;

		mysqli_query($link,$sql) or die(mysqli_error($link));
		
		//$_SESSION['saveresult']="新增商店資料成功!";
			
		// once saved, redirect back to the view page
		header("Location: hairstylist.php?act=Qry");

		mysqli_close($link);
	}else{
		header("Location: logout.php");
	}
?>