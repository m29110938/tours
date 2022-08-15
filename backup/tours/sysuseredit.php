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
				
		$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
		$user_id  = mysqli_real_escape_string($link,$user_id);

		$group_id = isset($_POST['group_id']) ? $_POST['group_id'] : '0';
		$group_id  = mysqli_real_escape_string($link,$group_id);
		
		$user_name = isset($_POST['user_name']) ? $_POST['user_name'] : '';
		$user_name  = mysqli_real_escape_string($link,$user_name);

		$user_pwd = isset($_POST['user_pwd']) ? $_POST['user_pwd'] : '';
		$user_pwd  = mysqli_real_escape_string($link,$user_pwd);

		$user_mobile = isset($_POST['user_mobile']) ? $_POST['user_mobile'] : '';
		$user_mobile  = mysqli_real_escape_string($link,$user_mobile);


		$sql="update sysuser set user_id='$user_id',group_id=$group_id,user_name='$user_name',user_pwd='$user_pwd',user_mobile='$user_mobile' where sid=$tid ;";

		//echo $sql;
		//exit;

		mysqli_query($link,$sql) or die(mysqli_error($link));
		
		$_SESSION['saveresult']="修改系統使用者資料成功!";
			
		// once saved, redirect back to the view page
		header("Location: sysuser.php?act=Qry");

		mysqli_close($link);
	}else{
		header("Location: logout.php");
	}
?>