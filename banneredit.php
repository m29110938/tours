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
				
		$banner_subject = isset($_POST['banner_subject']) ? $_POST['banner_subject'] : '';
		$banner_subject  = mysqli_real_escape_string($link,$banner_subject);
		
		$banner_descript = isset($_POST['banner_descript']) ? $_POST['banner_descript'] : '';
		$banner_descript  = mysqli_real_escape_string($link,$banner_descript);

		$banner_date = isset($_POST['banner_date']) ? $_POST['banner_date'] : '';
		$banner_date  = mysqli_real_escape_string($link,$banner_date);

		$banner_enddate = isset($_POST['banner_enddate']) ? $_POST['banner_enddate'] : '';
		$banner_enddate  = mysqli_real_escape_string($link,$banner_enddate);

		$banner_link = isset($_POST['banner_link']) ? $_POST['banner_link'] : '';
		$banner_link  = mysqli_real_escape_string($link,$banner_link);

		$sql="update banner set banner_subject='$banner_subject',banner_descript='$banner_descript',banner_date='$banner_date',banner_enddate='$banner_enddate',banner_link='$banner_link' where bid=$tid ;";

		//echo $sql;
		//exit;

		mysqli_query($link,$sql) or die(mysqli_error($link));
		
		$_SESSION['saveresult']="修改Banner資料成功!";
		
		// once saved, redirect back to the view page
		header("Location: banner.php?act=Qry");

		mysqli_close($link);
	}else{
		header("Location: logout.php");
	}
?>