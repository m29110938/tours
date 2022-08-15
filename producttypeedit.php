<?php
	session_start();
	include("db_tools.php");
	
	//if ($_SESSION['authority']!="1"){
	//	header("Location: main.php");
	//}

	$act = isset($_POST['act']) ? $_POST['act'] : '';
	$tid = isset($_POST['tid']) ? $_POST['tid'] : '';
	if ($act == 'Edit') {
			
		$product_type = isset($_POST['product_type']) ? $_POST['product_type'] : '';
		$product_type  = mysqli_real_escape_string($link,$product_type);
		
		$producttype_name = isset($_POST['producttype_name']) ? $_POST['producttype_name'] : '';
		$producttype_name  = mysqli_real_escape_string($link,$producttype_name);


		$sql="update producttype set product_type='$product_type',producttype_name='$producttype_name' where pid=$tid ;";

		//echo $sql;
		//exit;

		mysqli_query($link,$sql) or die(mysqli_error($link));
		
		$_SESSION['saveresult']="修改商品分類成功!";
			
		// once saved, redirect back to the view page
		header("Location: producttype.php?act=Qry");

		mysqli_close($link);
	}else{
		header("Location: logout.php");
	}
?>