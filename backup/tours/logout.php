<?php
	session_start();
	$_SESSION['loginsid']="";
	$_SESSION['userid']="";
	$_SESSION['accname']="";
	$_SESSION['authority']="";
	$_SESSION['downloadlog']="";
	session_destroy();
	header("Location: login.php"); 
?>
