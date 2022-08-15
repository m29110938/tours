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

		$sql="INSERT INTO banner (banner_subject,banner_descript,banner_date,banner_enddate,banner_link) VALUES ('$banner_subject', '$banner_descript', '$banner_date','$banner_enddate','$banner_link');";

		//echo $sql;
		//exit;

		mysqli_query($link,$sql) or die(mysqli_error($link));
		
		$_SESSION['saveresult']="新增Banner成功!";
		
		// once saved, redirect back to the view page
		header("Location: banner.php?act=Qry");

		mysqli_close($link);
	}else{
		header("Location: logout.php");
	}
?>