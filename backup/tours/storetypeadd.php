<?php
	session_start();
	//if ($_SESSION['authority']!="1"){
	//	header("Location: main.php");
	//}

	$act = isset($_POST['act']) ? $_POST['act'] : '';
	if ($act == 'Add') {
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';
		
		$link = mysqli_connect($host, $user, $passwd, $database);
		mysqli_query($link,"SET NAMES 'utf8'");
				
		$store_type = isset($_POST['store_type']) ? $_POST['store_type'] : '';
		$store_type  = mysqli_real_escape_string($link,$store_type);
		
		$storetype_name = isset($_POST['storetype_name']) ? $_POST['storetype_name'] : '';
		$storetype_name  = mysqli_real_escape_string($link,$storetype_name);


		$sql="INSERT INTO store_type (store_type,storetype_name) VALUES ('$store_type', '$storetype_name');";

		//echo $sql;
		//exit;

		mysqli_query($link,$sql) or die(mysqli_error($link));
		
		$_SESSION['saveresult']="新增商店分類成功!";
			
		// once saved, redirect back to the view page
		header("Location: storetype.php?act=Qry");

		mysqli_close($link);
	}else{
		header("Location: logout.php");
	}
?>