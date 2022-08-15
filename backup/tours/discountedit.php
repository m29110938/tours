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
				
		$coupon_id = isset($_POST['coupon_id']) ? $_POST['coupon_id'] : '';
		$coupon_id  = mysqli_real_escape_string($link,$coupon_id);

		$coupon_type = isset($_POST['coupon_type']) ? $_POST['coupon_type'] : '0';
		$coupon_type  = mysqli_real_escape_string($link,$coupon_type);
		
		$coupon_name = isset($_POST['coupon_name']) ? $_POST['coupon_name'] : '';
		$coupon_name  = mysqli_real_escape_string($link,$coupon_name);

		$coupon_description = isset($_POST['coupon_description']) ? $_POST['coupon_description'] : '';
		$coupon_description  = mysqli_real_escape_string($link,$coupon_description);

		$coupon_startdate = isset($_POST['coupon_startdate']) ? $_POST['coupon_startdate'] : '';
		$coupon_startdate  = mysqli_real_escape_string($link,$coupon_startdate);

		$coupon_enddate = isset($_POST['coupon_enddate']) ? $_POST['coupon_enddate'] : '';
		$coupon_enddate  = mysqli_real_escape_string($link,$coupon_enddate);
		
		$coupon_rule = isset($_POST['coupon_rule']) ? $_POST['coupon_rule'] : '0';
		$coupon_rule  = mysqli_real_escape_string($link,$coupon_rule);

		$coupon_discount = isset($_POST['coupon_discount']) ? $_POST['coupon_discount'] : '0';
		$coupon_discount  = mysqli_real_escape_string($link,$coupon_discount);

		$discount_amount = isset($_POST['discount_amount']) ? $_POST['discount_amount'] : '0';
		$discount_amount  = mysqli_real_escape_string($link,$discount_amount);

		$coupon_storeid = isset($_POST['coupon_storeid']) ? $_POST['coupon_storeid'] : '0';
		$coupon_storeid  = mysqli_real_escape_string($link,$coupon_storeid);

		$coupon_status = isset($_POST['coupon_status']) ? $_POST['coupon_status'] : '0';
		$coupon_status  = mysqli_real_escape_string($link,$coupon_status);

		$sql="update coupon set coupon_id='$coupon_id',coupon_type=$coupon_type,coupon_name='$coupon_name',coupon_description='$coupon_description',coupon_startdate='$coupon_startdate',coupon_enddate='$coupon_enddate',coupon_rule=$coupon_rule,coupon_discount=$coupon_discount,discount_amount=$discount_amount,coupon_storeid=$coupon_storeid,coupon_status=$coupon_status where cid=$tid ;";

		//echo $sql;
		//exit;

		mysqli_query($link,$sql) or die(mysqli_error($link));
		
		$_SESSION['saveresult']="修改優惠票券資料成功!";
			
		// once saved, redirect back to the view page
		header("Location: discount.php?act=Qry");

		mysqli_close($link);
	}else{
		header("Location: logout.php");
	}
?>