<?php
	session_start();
	include("db_tools.php");
	
	//if ($_SESSION['authority']!="1"){
	//	header("Location: main.php");
	//}

	$act = isset($_POST['act']) ? $_POST['act'] : '';
	if ($act == 'Add') {
				
		$product_type = isset($_POST['product_type']) ? $_POST['product_type'] : '';
		$product_type  = mysqli_real_escape_string($link,$product_type);
		
		$producttype_name = isset($_POST['producttype_name']) ? $_POST['producttype_name'] : '';
		$producttype_name  = mysqli_real_escape_string($link,$producttype_name);


		$sql="INSERT INTO producttype (product_type,producttype_name) VALUES ('$product_type', '$producttype_name');";

		//echo $sql;
		//exit;

		mysqli_query($link,$sql) or die(mysqli_error($link));
		
		$_SESSION['saveresult']="新增商品分類成功!";
			
		// once saved, redirect back to the view page
		header("Location: producttype.php?act=Qry");

		mysqli_close($link);
	}else{
		header("Location: logout.php");
	}
?>