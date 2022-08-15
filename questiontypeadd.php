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
				
		//$product_type = isset($_POST['product_type']) ? $_POST['product_type'] : '';
		//$product_type  = mysqli_real_escape_string($link,$product_type);
		
		$questiontype_name = isset($_POST['questiontype_name']) ? $_POST['questiontype_name'] : '';
		$questiontype_name  = mysqli_real_escape_string($link,$questiontype_name);


		$sql="INSERT INTO questiontype (questiontype_name) VALUES ('$questiontype_name');";

		//echo $sql;
		//exit;

		mysqli_query($link,$sql) or die(mysqli_error($link));
		
		$_SESSION['saveresult']="新增問題分類成功!";
			
		// once saved, redirect back to the view page
		header("Location: questiontype.php?act=Qry");

		mysqli_close($link);
	}else{
		header("Location: logout.php");
	}
?>