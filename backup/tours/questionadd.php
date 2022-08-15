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
				
		$question_type = isset($_POST['question_type']) ? $_POST['question_type'] : '';
		$question_type  = mysqli_real_escape_string($link,$question_type);

		$question_subject = isset($_POST['question_subject']) ? $_POST['question_subject'] : '';
		$question_subject  = mysqli_real_escape_string($link,$question_subject);
		
		$question_description = isset($_POST['question_description']) ? $_POST['question_description'] : '';
		$question_description  = mysqli_real_escape_string($link,$question_description);


		$sql="INSERT INTO question (qid,question_subject,question_description) VALUES ($question_type,'$question_subject','$question_description');";

		//echo $sql;
		//exit;

		mysqli_query($link,$sql) or die(mysqli_error($link));
		
		$_SESSION['saveresult']="新增問題資料成功!";
			
		// once saved, redirect back to the view page
		header("Location: question.php?act=Qry");

		mysqli_close($link);
	}else{
		header("Location: logout.php");
	}
?>