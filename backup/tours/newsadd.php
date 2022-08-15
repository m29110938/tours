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
				
		$news_subject = isset($_POST['news_subject']) ? $_POST['news_subject'] : '';
		$news_subject  = mysqli_real_escape_string($link,$news_subject);
		
		$news_descript = isset($_POST['news_descript']) ? $_POST['news_descript'] : '';
		$news_descript  = mysqli_real_escape_string($link,$news_descript);

		$news_date = isset($_POST['news_date']) ? $_POST['news_date'] : '';
		$news_date  = mysqli_real_escape_string($link,$news_date);


		$sql="INSERT INTO news (news_subject,news_descript,news_date) VALUES ('$news_subject', '$news_descript', '$news_date');";

		//echo $sql;
		//exit;

		mysqli_query($link,$sql) or die(mysqli_error($link));
		
		$_SESSION['saveresult']="新增最新消息成功!";
			
		// once saved, redirect back to the view page
		header("Location: news.php?act=Qry");

		mysqli_close($link);
	}else{
		header("Location: logout.php");
	}
?>