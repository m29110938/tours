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
				
		$product_no = isset($_POST['product_no']) ? $_POST['product_no'] : '';
		$product_no  = mysqli_real_escape_string($link,$product_no);
		
		$product_name = isset($_POST['product_name']) ? $_POST['product_name'] : '';
		$product_name  = mysqli_real_escape_string($link,$product_name);

		$product_type = isset($_POST['product_type']) ? $_POST['product_type'] : '0';
		$product_type  = mysqli_real_escape_string($link,$product_type);

		$product_bonus = isset($_POST['product_bonus']) ? $_POST['product_bonus'] : '0';
		$product_bonus  = mysqli_real_escape_string($link,$product_bonus);

		$product_stock = isset($_POST['product_stock']) ? $_POST['product_stock'] : '0';
		$product_stock  = mysqli_real_escape_string($link,$product_stock);

		$product_description = isset($_POST['product_description']) ? $_POST['product_description'] : '';
		$product_description  = mysqli_real_escape_string($link,$product_description);

		$product_status = isset($_POST['product_status']) ? $_POST['product_status'] : '';
		$product_status  = mysqli_real_escape_string($link,$product_status);


		$sql="INSERT INTO product (product_no,product_name,pid,product_bonus,product_stock,product_description,product_status) VALUES ('$product_no', '$product_name', $product_type,$product_bonus,$product_stock,'$product_description','$product_status');";

		echo $sql;
		//exit;

		mysqli_query($link,$sql) or die(mysqli_error($link));
		
		$_SESSION['saveresult']="新增產品資料成功!";
		
		// once saved, redirect back to the view page
		header("Location: product.php?act=Qry");

		mysqli_close($link);
	}else{
		header("Location: logout.php");
	}
?>