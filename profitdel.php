<?php
	//session_start();
	//if ($_SESSION['authority']!="1"){
	//	header("Location: main.php");
	//}
	$act = isset($_POST['act']) ? $_POST['act'] : '';
	if ($act == 'Del') {
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';

		$link = mysqli_connect($host, $user, $passwd, $database);
		mysqli_query($link,"SET NAMES 'utf8'");
		
		//usr_tid2
		$tid = isset($_POST['tid']) ? $_POST['tid'] : '';
		
		if ($tid != "") {
			$sql="update profit set billing_flag=1,	pay_date=NOW() where pid=$tid;";

			//echo $sql;
			//exit;
			mysqli_query($link,$sql) or die(mysqli_error($link));

			mysqli_close($link);
			// once saved, redirect back to the view page
			header("Location: profit.php?act=Qry");
		}else{
			header("Location: profit.php");
		}

	}else{
		header("Location: logout.php");
	}

?>