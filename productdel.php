<?php
	include("db_tools.php"); 

	//session_start();
	//if ($_SESSION['authority']!="1"){
	//	header("Location: main.php");
	//}
	$act = isset($_POST['act']) ? $_POST['act'] : '';
	if ($act == 'Del') {
		
		//usr_tid2
		$tid = isset($_POST['tid']) ? $_POST['tid'] : '';
		
		if ($tid != "") {
			$sql="update product set product_trash=1,product_updated_at=NOW() where rid=$tid;";

			//echo $sql;
			//exit;
			mysqli_query($link,$sql) or die(mysqli_error($link));
			
			mysqli_close($link);
			// once saved, redirect back to the view page
			header("Location: product.php");
		}else{
			header("Location: product.php");
		}

	}else{
		header("Location: logout.php");
	}

?>