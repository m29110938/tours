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
				
		// $coupon_id = isset($_POST['coupon_id']) ? $_POST['coupon_id'] : '';
		// $coupon_id  = mysqli_real_escape_string($link,$coupon_id);

		$sid_coupon_id = isset($_POST['sid_coupon_id']) ? $_POST['sid_coupon_id'] : '';
		$sid_coupon_id  = mysqli_real_escape_string($link,$sid_coupon_id);
		$storeid = explode("_", $sid_coupon_id)[0];

		$coupon_type = isset($_POST['coupon_type']) ? $_POST['coupon_type'] : '0';
		$coupon_type  = mysqli_real_escape_string($link,$coupon_type);
		
		$coupon_name = isset($_POST['coupon_name']) ? $_POST['coupon_name'] : '';
		$coupon_name  = mysqli_real_escape_string($link,$coupon_name);

		$coupon_description = isset($_POST['coupon_description']) ? $_POST['coupon_description'] : '';
		$coupon_description  = mysqli_real_escape_string($link,$coupon_description);

		$coupon_issue_startdate = isset($_POST['coupon_issue_startdate']) ? $_POST['coupon_issue_startdate'] : '0000-00-00 00:00:00';
		$coupon_issue_startdate  = mysqli_real_escape_string($link,$coupon_issue_startdate);

		$coupon_issue_enddate = isset($_POST['coupon_issue_enddate']) ? $_POST['coupon_issue_enddate'] : '0000-00-00 00:00:00';
		$coupon_issue_enddate  = mysqli_real_escape_string($link,$coupon_issue_enddate);

		
		$coupon_startdate = isset($_POST['coupon_startdate']) ? $_POST['coupon_startdate'] : '0000-00-00 00:00:00';
		$coupon_startdate  = mysqli_real_escape_string($link,$coupon_startdate);

		$coupon_enddate = isset($_POST['coupon_enddate']) ? $_POST['coupon_enddate'] : '0000-00-00 00:00:00';
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

		$coupon_number = isset($_POST['coupon_number']) ? $_POST['coupon_number'] : '0';
		$coupon_number  = mysqli_real_escape_string($link,$coupon_number);

		if($coupon_type == 2 || $coupon_type == 5){
			$coupon_issue_startdate .= "-01";
			$coupon_issue_enddate .= "-01";
			$coupon_number = '-9999';
		}

		$store_id = isset($_POST['store_id']) ? $_POST['store_id'] : '0';
		$store_id  = mysqli_real_escape_string($link,$store_id);

		$sql = "select * from store where store_id = '$storeid'";
		// echo $sql;
		if ($result = mysqli_query($link, $sql)) {
			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_array($result)) {
					$coupon_storeid =  $row['sid'];
				}
			}
		}
		// echo $coupon_storeid;
		$sql2 = "select * from coupon where coupon_id = '$sid_coupon_id' and coupon_trash=0 and coupon_status = 1";
		if ($result2 = mysqli_query($link, $sql2)) {
			if (mysqli_num_rows($result2) == 0) {
				
				echo "<script>alert('新增成功');</script>";
				$sql="INSERT INTO coupon (coupon_id,coupon_type,coupon_name,coupon_description,coupon_number,coupon_issue_startdate,coupon_issue_enddate,coupon_startdate,coupon_enddate,coupon_rule,coupon_discount,discount_amount,coupon_storeid,coupon_status) VALUES ('$sid_coupon_id',$coupon_type,'$coupon_name','$coupon_description','$coupon_number','$coupon_issue_startdate','$coupon_issue_enddate','$coupon_startdate','$coupon_enddate',$coupon_rule,$coupon_discount,$discount_amount,$coupon_storeid,$coupon_status);";

				// echo $sql;
				//exit;

				mysqli_query($link,$sql) or die(mysqli_error($link));
				
				$_SESSION['saveresult']="新增優惠票券資料成功!";
			
			}else{
				echo "<script>alert('優惠券編號重複');</script>";
			}
		}
		
			
		// once saved, redirect back to the view page
		echo "<script>window.location.href='coupon.php?act=Qry';</script>";
		// header("Location: store.php?act=Qry");

		

		// //echo $sql;
		// //exit;

		// mysqli_query($link,$sql) or die(mysqli_error($link));
		
		// $_SESSION['saveresult']="新增優惠票券資料成功!";
			
		// // once saved, redirect back to the view page
		// header("Location: discount.php?act=Qry");

		mysqli_close($link);
	}else{
		header("Location: logout.php");
	}
?>