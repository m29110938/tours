<?php
	session_start();
	//if ($_SESSION['authority']!="1"){
	//	header("Location: main.php");
	//}
	//---------------------------------------------------------------------------------------------------------
	$act = isset($_POST['act']) ? $_POST['act'] : '';
	$tid = isset($_POST['tid']) ? $_POST['tid'] : '';
	// echo $tid;
	if ($act == 'Edit') {
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';
		
		$link = mysqli_connect($host, $user, $passwd, $database);
		mysqli_query($link,"SET NAMES 'utf8'");
				
		$store_id = isset($_POST['store_id']) ? $_POST['store_id'] : '';
		$store_id  = mysqli_real_escape_string($link,$store_id);

		$register_name = isset($_POST['register_name']) ? $_POST['register_name'] : '';
		$register_name  = mysqli_real_escape_string($link,$register_name);
		
		$tax_id = isset($_POST['tax_id']) ? $_POST['tax_id'] : '';
		$tax_id  = mysqli_real_escape_string($link,$tax_id);

		$manager_name = isset($_POST['manager_name']) ? $_POST['manager_name'] : '';
		$manager_name  = mysqli_real_escape_string($link,$manager_name);

		$register_address = isset($_POST['register_address']) ? $_POST['register_address'] : '';
		$register_address  = mysqli_real_escape_string($link,$register_address);

		$bank_name = isset($_POST['bank_name']) ? $_POST['bank_name'] : '';
		$bank_name  = mysqli_real_escape_string($link,$bank_name);
		
		$branch_name = isset($_POST['branch_name']) ? $_POST['branch_name'] : '';
		$branch_name  = mysqli_real_escape_string($link,$branch_name);

		$bank_no = isset($_POST['bank_no']) ? $_POST['bank_no'] : '';
		$bank_no  = mysqli_real_escape_string($link,$bank_no);

		$account_no = isset($_POST['account_no']) ? $_POST['account_no'] : '';
		$account_no  = mysqli_real_escape_string($link,$account_no);

		$account_name = isset($_POST['account_name']) ? $_POST['account_name'] : '';
		$account_name  = mysqli_real_escape_string($link,$account_name);

		$name = isset($_POST['name']) ? $_POST['name'] : '';
		$name  = mysqli_real_escape_string($link,$name);

		$job_title = isset($_POST['job_title']) ? $_POST['job_title'] : '';
		$job_title  = mysqli_real_escape_string($link,$job_title);

		$office_phone = isset($_POST['office_phone']) ? $_POST['office_phone'] : '';
		$office_phone  = mysqli_real_escape_string($link,$office_phone);
		
		$mobile = isset($_POST['mobile']) ? $_POST['mobile'] : '';
		$mobile  = mysqli_real_escape_string($link,$mobile);
		
		$email = isset($_POST['email']) ? $_POST['email'] : '';
		$email  = mysqli_real_escape_string($link,$email);
		
		$bill_address = isset($_POST['bill_address']) ? $_POST['bill_address'] : '';
		$bill_address  = mysqli_real_escape_string($link,$bill_address);

		$sql2 = "select * from store_bill where tax_id = '$tax_id' and store_bill_trash=0";
		// $sql2 = "select * from store where store_id = '$store_id' and store_trash=0 and store_status = 0";
		// echo $sql2;
		if ($result2 = mysqli_query($link, $sql2)) {
			if (mysqli_num_rows($result2) == 1) {
				while ($row = mysqli_fetch_array($result2)) {
					$bid =  $row['bid'];
				}
				if ($bid != $tid){
					echo "<script>alert('店家代號重複');</script>";
					?>
					<form action="editbill.php" method="Post" name='frm1' id='frm1' class="card">
						<input type="hidden" name="act" id="act"  value="Edit"/>
						<input type="hidden" name="tid" id="tid"  value="<?=$tid?>"/>
					</form>
					<script>document.frm1.submit();</script>
					<?php
				}else{
					echo "<script>alert('修改成功');</script>";
					$sql="update store_bill set store_id='$store_id',register_name='$register_name',tax_id='$tax_id',manager_name=$manager_name,register_address='$register_address',bank_name='$bank_name',branch_name='$branch_name',bank_no='$bank_no',account_no='$account_no',account_name='$account_name',name='$name',job_title='$job_title',office_phone='$office_phone',mobile='$mobile',email='$email',bill_address='$bill_address' where bid=$tid ;";
					// echo $sql;
					$_SESSION['saveresult']="修改店家帳務成功!";

					mysqli_query($link,$sql) or die(mysqli_error($link));
				}

				// echo $sql;
				//exit;
				
			
			}
			elseif(mysqli_num_rows($result2) == 0){
				echo "<script>alert('修改成功2');</script>";
				$sql="update store_bill set store_id='$store_id',register_name='$register_name',tax_id='$tax_id',manager_name=$manager_name,register_address='$register_address',bank_name='$bank_name',branch_name='$branch_name',bank_no='$bank_no',account_no='$account_no',account_name='$account_name',name='$name',job_title='$job_title',office_phone='$office_phone',mobile='$mobile',email='$email',bill_address='$bill_address' where bid=$tid ;";
				// echo $sql;
				$_SESSION['saveresult']="修改店家帳務成功!";

				mysqli_query($link,$sql) or die(mysqli_error($link));
			}
			else{
				echo "<script>alert('更新失敗');</script>";
				?>
				<form action="editbill.php" method="Post" name='frm1' id='frm1' class="card">
					<input type="hidden" name="act" id="act"  value="Edit"/>
					<input type="hidden" name="tid" id="tid"  value="<?=$tid?>"/>
				</form>
				<script>document.frm1.submit();</script>
				<?php
			}
		}
		
			
		// once saved, redirect back to the view page
		echo "<script>window.location.href='bill.php?act=Qry';</script>";
		// header("Location: store.php?act=Qry");

		mysqli_close($link);
	}else{
		header("Location: logout.php");
	}
?>
