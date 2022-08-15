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
				
		// $coupon_id = isset($_POST['coupon_id']) ? $_POST['coupon_id'] : '';
		// $coupon_id  = mysqli_real_escape_string($link,$coupon_id);

		$sid_coupon_id = isset($_POST['sid_coupon_id']) ? $_POST['sid_coupon_id'] : '';
		$sid_coupon_id  = mysqli_real_escape_string($link,$sid_coupon_id);
		$storeid = explode("_", $sid_coupon_id)[0];
		// echo $storeid;


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

		if($coupon_type == 2 || $coupon_type == 5 ){
			$coupon_issue_startdate .= "-01";
			$coupon_issue_enddate .= "-01";
		}
		
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

		$sql = "select * from store where store_id = '$storeid'";
		// echo $sql;
		if ($result = mysqli_query($link, $sql)) {
			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_array($result)) {
					$coupon_storeid =  $row['sid'];
				}
			}
		}
		$sql2 = "select * from coupon where coupon_id = '$sid_coupon_id' and coupon_trash=0 and coupon_status = 1";
		// echo $sql2;
		if ($result2 = mysqli_query($link, $sql2)) {
			if (mysqli_num_rows($result2) == 1) {
				while ($row = mysqli_fetch_array($result2)) {
					$cid =  $row['cid'];
				}
				if ($cid != $tid){
					echo "<script>alert('優惠券編號重複');</script>";
					?>
					<form action="editcoupon.php" method="Post" name='frm1' id='frm1' class="card">
						<input type="hidden" name="act" id="act"  value="Edit"/>
						<input type="hidden" name="tid" id="tid"  value="<?=$tid?>"/>
					</form>
					<script>document.frm1.submit();</script>
					<?php
				}else{
					echo "<script>alert('修改成功');</script>";
					$sql="update coupon set coupon_id='$sid_coupon_id',coupon_type=$coupon_type,coupon_name='$coupon_name',coupon_description='$coupon_description',coupon_issue_startdate='$coupon_issue_startdate',coupon_issue_enddate='$coupon_issue_enddate',coupon_startdate='$coupon_startdate',coupon_enddate='$coupon_enddate',coupon_rule=$coupon_rule,coupon_discount=$coupon_discount,discount_amount=$discount_amount,coupon_storeid=$coupon_storeid,coupon_status=$coupon_status where cid=$tid ;";

					mysqli_query($link,$sql) or die(mysqli_error($link));
					$_SESSION['saveresult']="修改優惠票券資料成功!";
				}

				// echo $sql;
				//exit;
				
			
			}
			elseif(mysqli_num_rows($result2) == 0){
				echo "<script>alert('修改成功');</script>";
				$sql="update coupon set coupon_id='$sid_coupon_id',coupon_type=$coupon_type,coupon_name='$coupon_name',coupon_description='$coupon_description',coupon_issue_startdate='$coupon_issue_startdate',coupon_issue_enddate='$coupon_issue_enddate',coupon_startdate='$coupon_startdate',coupon_enddate='$coupon_enddate',coupon_rule=$coupon_rule,coupon_discount=$coupon_discount,discount_amount=$discount_amount,coupon_storeid=$coupon_storeid,coupon_status=$coupon_status where cid=$tid ;";

				mysqli_query($link,$sql) or die(mysqli_error($link));
				$_SESSION['saveresult']="修改優惠票券資料成功!";
			}
			else{
				echo "<script>alert('更新失敗');</script>";
				?>
				<form action="editcoupon.php" method="Post" name='frm1' id='frm1' class="card">
					<input type="hidden" name="act" id="act"  value="Edit"/>
					<input type="hidden" name="tid" id="tid"  value="<?=$tid?>"/>
				</form>
				<script>document.frm1.submit();</script>
				<?php
			}
		}
		

		
		
			
		echo "<script>window.location.href='coupon.php?act=Qry';</script>";
		// once saved, redirect back to the view page
		// header("Location: coupon.php?act=Qry");

		mysqli_close($link);
	}else{
		header("Location: logout.php");
	}
?>