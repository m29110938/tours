<?php
	session_start();
	//if ($_SESSION['authority']!="1"){
	//	header("Location: main.php");
	//}

	$act = isset($_POST['act']) ? $_POST['act'] : '';
	$tid = isset($_POST['tid']) ? $_POST['tid'] : '';	
	if ($act == 'Edit') {
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';
		
		$link = mysqli_connect($host, $user, $passwd, $database);
		mysqli_query($link,"SET NAMES 'utf8'");
				
		$push_date = isset($_POST['push_date']) ? $_POST['push_date'] : '';
		$push_date  = mysqli_real_escape_string($link,$push_date);
		
		$push_message = isset($_POST['push_message']) ? $_POST['push_message'] : '';
		$push_message  = mysqli_real_escape_string($link,$push_message);

		$member_type = isset($_POST['member_type']) ? $_POST['member_type'] : '0';
		$member_type  = mysqli_real_escape_string($link,$member_type);

		$sql="update pushmsg set push_date='$push_date',push_message='$push_message',member_type=$member_type where pid=$tid";

		//echo $sql;
		//exit;

		mysqli_query($link,$sql) or die(mysqli_error($link));
		
		$_SESSION['saveresult']="修改推播訊息資料成功!";
		
		// once saved, redirect back to the view page
		header("Location: pushmsg.php?act=Qry");

		mysqli_close($link);
	}else{
		header("Location: logout.php");
	}
?>