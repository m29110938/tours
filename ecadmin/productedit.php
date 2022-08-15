<?php
	session_start();
	include("db_tools.php");
	
	//if ($_SESSION['authority']!="1"){
	//	header("Location: main.php");
	//}

	$act = isset($_POST['act']) ? $_POST['act'] : '';
	$tid = isset($_POST['tid']) ? $_POST['tid'] : '';
	if ($act == 'Edit') {
				
		$product_no = isset($_POST['product_no']) ? $_POST['product_no'] : '';
		$product_no  = mysqli_real_escape_string($link,$product_no);
		
		$product_name = isset($_POST['product_name']) ? $_POST['product_name'] : '';
		$product_name  = mysqli_real_escape_string($link,$product_name);

		$product_type = isset($_POST['product_type']) ? $_POST['product_type'] : '0';
		$product_type  = mysqli_real_escape_string($link,$product_type);

		$product_price = isset($_POST['product_price']) ? $_POST['product_price'] : '0';
		$product_price  = mysqli_real_escape_string($link,$product_price);

		//$product_bonus = isset($_POST['product_bonus']) ? $_POST['product_bonus'] : '0';
		//$product_bonus  = mysqli_real_escape_string($link,$product_bonus);

		$product_stock = isset($_POST['product_stock']) ? $_POST['product_stock'] : '0';
		$product_stock  = mysqli_real_escape_string($link,$product_stock);

		$product_description = isset($_POST['product_description']) ? $_POST['product_description'] : '';
		$product_description  = mysqli_real_escape_string($link,$product_description);

		$product_status = isset($_POST['product_status']) ? $_POST['product_status'] : '';
		$product_status  = mysqli_real_escape_string($link,$product_status);


		$sql="update product set product_no='$product_no',product_name='$product_name',pid=$product_type,product_price=$product_price,product_stock=$product_stock,product_description='$product_description',product_status='$product_status' where rid=$tid ;";

		//echo $sql;
		//exit;

		mysqli_query($link,$sql) or die(mysqli_error($link));
		
		$_SESSION['saveresult']="修改產品資料成功!";
		
		// once saved, redirect back to the view page
		header("Location: product.php?act=Qry");

		mysqli_close($link);
	}else{
		header("Location: logout.php");
	}
?>