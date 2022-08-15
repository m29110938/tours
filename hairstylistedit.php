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

		$nick_name = isset($_POST['nick_name']) ? $_POST['nick_name'] : '0';
		$nick_name  = mysqli_real_escape_string($link,$nick_name);
		
		$service_code = isset($_POST['service_code']) ? $_POST['service_code'] : '';
		$service_code  = mysqli_real_escape_string($link,$service_code);

		$hairstylist_date = isset($_POST['hairstylist_date']) ? $_POST['hairstylist_date'] : '';
		$hairstylist_date  = mysqli_real_escape_string($link,$hairstylist_date);

		$hairstylist_status = isset($_POST['hairstylist_status']) ? $_POST['hairstylist_status'] : '';
		$hairstylist_status  = mysqli_real_escape_string($link,$hairstylist_status);

		$sql="update hairstylist set store_id='$store_id',nick_name='$nick_name',service_code='$service_code',hairstylist_date='$hairstylist_date',hairstylist_status='$hairstylist_status' where hid=$tid ;";

		//echo $sql;
		//exit;

		mysqli_query($link,$sql) or die(mysqli_error($link));
		
		//$_SESSION['saveresult']="修改商店資料成功!";
			
		// once saved, redirect back to the view page
		header("Location: hairstylist.php?act=Qry");

		mysqli_close($link);
	}else{
		header("Location: logout.php");
	}
?>