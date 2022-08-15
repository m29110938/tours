<?php
	session_start();
	//if ($_SESSION['authority']!="1"){
	//	header("Location: main.php");
	//}
	//---------------------------------------------------------------------------------------------------------
	$act = isset($_POST['act']) ? $_POST['act'] : '';
	$tid = isset($_POST['tid']) ? $_POST['tid'] : '';
	
	if ($act == 'Edit') {
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';
		
		$link = mysqli_connect($host, $user, $passwd, $database);
		mysqli_query($link,"SET NAMES 'utf8'");
				
		$store_id = isset($_POST['store_id']) ? $_POST['store_id'] : '';
		$store_id  = mysqli_real_escape_string($link,$store_id);

		$service_name = isset($_POST['service_name']) ? $_POST['service_name'] : '';
		$service_name  = mysqli_real_escape_string($link,$service_name);
		
		$service_code = isset($_POST['service_code']) ? $_POST['service_code'] : '';
		$service_code  = mysqli_real_escape_string($link,$service_code);

		$service_time = isset($_POST['service_time']) ? $_POST['service_time'] : '';
		$service_time  = mysqli_real_escape_string($link,$service_time);

		$service_price = isset($_POST['service_price']) ? $_POST['service_price'] : '0';
		$service_price  = mysqli_real_escape_string($link,$service_price);

		$service_status = isset($_POST['service_status']) ? $_POST['service_status'] : '';
		$service_status  = mysqli_real_escape_string($link,$service_status);

		$sql="update hairservice set store_id=$store_id,service_code='$service_code',service_name='$service_name',service_time='$service_time',service_price='$service_price',service_status=$service_status where xid=$tid;";

		//echo $sql;
		//exit;

		mysqli_query($link,$sql) or die(mysqli_error($link));
		
		//$_SESSION['saveresult']="修改服務資料成功!";
			
		// once saved, redirect back to the view page
		header("Location: hairservice.php?act=Qry");

		mysqli_close($link);
	}else{
		header("Location: logout.php");
	}
?>