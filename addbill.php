<?php
session_start();
if ($_SESSION['accname']==""){
	header("Location: logout.php"); 
}
if (($_SESSION['authority']=="3")||($_SESSION['authority']=="4")){
	header("Location: main.php"); 
}

	$act = isset($_POST['act']) ? $_POST['act'] : '';
	$tid = isset($_POST['tid']) ? $_POST['tid'] : '';
	// echo $act;
	$host = 'localhost';
	$user = 'tours_user';
	$passwd = 'tours0115';
	$database = 'toursdb';
		
	$link = mysqli_connect($host, $user, $passwd, $database);
	mysqli_query($link,"SET NAMES 'utf8'");
	
	// $sql = "SELECT * FROM banner where banner_trash=0 and bid=$tid";
	//echo $sql;
	//exit;
	//user_id, user_mail  user_password, user_name, user_city, user_country, bluetooth_ver, phone_model, RCE_member, FB_Account,user_created_at,user_trash

	// if ($result = mysqli_query($link, $sql)){
	// 	if (mysqli_num_rows($result) > 0){
	// 		while($row = mysqli_fetch_array($result)){
	// 			$banner_subject = $row['banner_subject'];
	// 			$banner_descript = $row['banner_descript'];
	// 			$banner_date = $row['banner_date'];
	// 			$banner_enddate = $row['banner_enddate'];
	// 			$banner_link = $row['banner_link'];
	// 			$banner_picture = $row['banner_picture'];
	// 		}
	// 	mysqli_close($link);
	// 	}else{
	// 			$banner_subject = "";
	// 			$banner_descript = "";
	// 			$banner_date = "";
	// 			$banner_enddate = "";
	// 			$banner_link = "";
	// 			$banner_picture = "";
	// 	}
	// }	
//echo $admin_role;	
?>	
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>點點管理後台</title>
    <link rel="icon" href="./images/Favicon.ico" type="image/x-icon"/>
  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
	<script src="js/comm.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    include("sidebar.php"); 
	?>
	<script>
		document.querySelector('#billphp').classList.add('active');
		document.querySelector('#bb').classList.add('active');
		document.querySelector('#b').classList.remove('collapsed');
		document.querySelector('#collapseUtilities').classList.add('show');
	</script>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
			<i class="fas fa-fw fa-address-book"></i>
			<h1 class="h5 mb-0 text-gray-800">店家帳務管理</h1>
          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['accname']; ?></span>
                <img class="img-profile rounded-circle" src="images/logo.png">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <!--<a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>-->
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  登出系統
                </a>
              </div>
            </li>

          </ul>

        </nav>

        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <!-- Content Row -->
          <div class="row">

            <div class="col-lg-12 mb-4">

              <!-- Illustrations -->
              <div class="card shadow mb-4">
			<form action="billadd.php" name="frm3" id="frm3" method="post">
			<input type="hidden" name="act" id="act"  value="<?php echo $act;?>"/>		
			<input type="hidden" name="tid" id="tid"  value="<?php echo $tid;?>"/>				  
			<!-- <input type="hidden" name="Dfilename" id="Dfilename"  value="<?php echo $banner_picture;?>"/> -->

                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">新增店家帳務</h6>
                </div>
                <div class="card-body">
				<table width="100%">
				<tr >
				
					<td width="100%">
						<h5>店家資料</h5>
						<br>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<!-- <label class="form-label">店家名稱:</label>
									<div class="align-items-center">
									<div class="col-auto">
										<input type="text" name="store_name" id="store_name" class="form-control" value="">
									</div>
									</div> -->
                  <label class="form-label">店家名稱:</label>
                  <div class="align-items-center">
                    <div class="col-auto">
                      <select name="store_id" id="store_id" class="form-control custom-select">
                        <option value="">--請選擇--</option>
                        <?php
                        $sql2 = "select sid,store_name from store where store_trash=0";
                        
                        if ($result2 = mysqli_query($link, $sql2)){
                          if (mysqli_num_rows($result2) > 0){
                            $selectedflag = "";
                            while($row2 = mysqli_fetch_array($result2)){
                              if ($sid == strval($row2['sid'])) {
                                $selectedflag = " selected ";
                              }else{
                                $selectedflag = "";
                              }
                              echo "<option value='".$row2['sid']."' ".$selectedflag." >".$row2['store_name']."</option>";
                            }
                          }
                        }								
                        ?>
                      </select>
                    </div>
                  </div>
								</div>	
							</div>
							<div class="col">
								<div class="form-group">
									<label class="form-label">登記名稱:</label>
									<div class="align-items-center">
									<div class="col-auto">
										<input type="text" name="register_name" id="register_name" class="form-control" value="">
									</div>
									</div>
								</div>	
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label class="form-label">統一編號:</label>
									<div class="align-items-center">
									<div class="col-auto">
										<input type="text" name="tax_id" id="tax_id" class="form-control" value="">
									</div>
									</div>
								</div>	
							</div>
							<div class="col">
								<div class="form-group">
									<label class="form-label">負責人姓名:</label>
									<div class="align-items-center">
									<div class="col-auto">
										<input type="text" name="manager_name" id="manager_name" class="form-control" value="">
									</div>
									</div>
								</div>	
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label class="form-label">登記地址:</label>
									<div class="align-items-center">
									<div class="col-auto">
										<input type="text" name="register_address" id="register_address" class="form-control" value="">
									</div>
									</div>
								</div>	
							</div>
							<!-- <div class="col">
								<div class="form-group">
									<label class="form-label">登記名稱:</label>
									<div class="align-items-center">
									<div class="col-auto">
										<input type="text" name="banner_subject" id="banner_subject" class="form-control" value="">
									</div>
									</div>
								</div>	
							</div> -->
						</div>
						<hr>
						<h5>銀行帳號資料</h5>
						<br>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label class="form-label">銀行名稱:</label>
									<div class="align-items-center">
									<div class="col-auto">
										<input type="text" name="bank_name" id="bank_name" class="form-control" value="">
									</div>
									</div>
								</div>	
							</div>
							<div class="col">
								<div class="form-group">
									<label class="form-label">分行名稱:</label>
									<div class="align-items-center">
									<div class="col-auto">
										<input type="text" name="branch_name" id="branch_name" class="form-control" value="">
									</div>
									</div>
								</div>	
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label class="form-label">銀行代號:</label>
									<div class="align-items-center">
									<div class="col-auto">
										<input type="text" name="bank_no" id="bank_no" class="form-control" value="">
									</div>
									</div>
								</div>	
							</div>
							<div class="col">
								<div class="form-group">
									<label class="form-label">帳號:</label>
									<div class="align-items-center">
									<div class="col-auto">
										<input type="text" name="account_no" id="account_no" class="form-control" value="">
									</div>
									</div>
								</div>	
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label class="form-label">帳戶名稱:</label>
									<div class="align-items-center">
									<div class="col-auto">
										<input type="text" name="account_name" id="account_name" class="form-control" value="">
									</div>
									</div>
								</div>	
							</div>
							<div class="col">
								<label class="form-label">&nbsp;</label>
								
								<div class="form-group col-md-6 mb-3">
									<input type="checkbox" id="name_same">
									<label for="cbox1">同登記名稱</label>
								</div>
							</div>
						</div>
						<hr>
						<h5>帳務聯絡人</h5>
						<br>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label class="form-label">姓名:</label>
									<div class="align-items-center">
									<div class="col-auto">
										<input type="text" name="name" id="name" class="form-control" value="">
									</div>
									</div>
								</div>	
							</div>
							<div class="col">
								<div class="form-group">
									<label class="form-label">職稱:</label>
									<div class="align-items-center">
									<div class="col-auto">
										<input type="text" name="job_title" id="job_title" class="form-control" value="">
									</div>
									</div>
								</div>	
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label class="form-label">公司電話:</label>
									<div class="align-items-center">
									<div class="col-auto">
										<input type="text" name="office_phone" id="office_phone" class="form-control" value="">
									</div>
									</div>
								</div>	
							</div>
							<div class="col">
								<div class="form-group">
									<label class="form-label">手機:</label>
									<div class="align-items-center">
									<div class="col-auto">
										<input type="text" name="mobile" id="mobile" class="form-control" value="">
									</div>
									</div>
								</div>	
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label class="form-label">E-mail:</label>
									<div class="align-items-center">
									<div class="col-auto">
										<input type="text" name="email" id="email" class="form-control" value="">
									</div>
									</div>
								</div>	
							</div>
							<!-- <div class="col">
								<div class="form-group">
									<label class="form-label">登記名稱:</label>
									<div class="align-items-center">
									<div class="col-auto">
										<input type="text" name="banner_subject" id="banner_subject" class="form-control" value="">
									</div>
									</div>
								</div>	
							</div> -->
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label class="form-label">帳務地址:</label>
									<div class="align-items-center">
									<div class="col-auto">
										<input type="text" name="bill_address" id="bill_address" class="form-control" value="">
									</div>
									</div>
								</div>	
							</div>
							<!-- <div class="col">
								<div class="form-group">
									<label class="form-label">登記名稱:</label>
									<div class="align-items-center">
									<div class="col-auto">
										<input type="text" name="banner_subject" id="banner_subject" class="form-control" value="">
									</div>
									</div>
								</div>	
							</div> -->
						</div>
					  <!-- <div class="form-group">
						<label class="form-label">:</label>
						<div class="align-items-center">
						  <div class="col-auto">
							<textarea id="banner_descript" name="banner_descript" rows="4" cols="50"><?php echo $banner_descript;?></textarea>							
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="form-label">:</label>
						<div class="align-items-center">
						  <div class="col-auto">
							  <input class="text-input small-input" type="date" name="banner_date" id="banner_date" value="<?php echo $banner_date;?>" />
						  </div>
						</div>
					  </div>		
					  <div class="form-group">
						<label class="form-label">:</label>
						<div class="align-items-center">
						  <div class="col-auto">
							  <input class="text-input small-input" type="date" name="banner_enddate" id="banner_enddate" value="<?php echo $banner_enddate;?>" />
						  </div>
						</div>
					  </div>	
					  <div class="form-group">
						<label class="form-label">連結網址:</label>
						<div class="align-items-center">
						  <div class="col-auto">
							<input type="text" name="banner_link" id="banner_link" class="form-control" value="<?php echo $banner_link;?>">
						  </div>
						</div>
					  </div>					   -->
					</td>
					<td colspan="2">&nbsp;</td>
				</tr>
				</table>					  
					  <div class="card-footer text-center">
							<button type="button" class="btn btn-success ml-auto" onclick="Save_User()">儲存</button>&nbsp;&nbsp;<button type="button" class="btn btn-info ml-auto" name="backacc" onclick="GoAccount();">返回</button>
					  </div>		
                </div>
				</form>
              </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->
	<SCRIPT LANGUAGE=javascript>
	
	$("#name_same").change(function () {
		var item = $(this).is(':checked');
		// console.log(item)
		if (item == true){
			var register_name = document.getElementById("register_name").value;
			// var member_phone = document.getElementById("member_phone").value;
			// var member_postal_code = $("#twzipcode").twzipcode('get', 'zipcode')[0];
			// var member_address = document.getElementById("pickup_location").value;
			// console.log(member_postal_code);

			document.getElementById("account_name").value = register_name;
			// document.getElementById("receiver_phone").value = member_phone;
			// $('#receiver_twzipcode').twzipcode('set', member_postal_code);
			// document.getElementById("receiver_address").value = member_address;
			// console.log("aa");
		}else{
			document.getElementById("account_name").value = "";
			// document.getElementById("receiver_phone").value = "";
			// $('#receiver_twzipcode').twzipcode('set', "");
			// document.getElementById("receiver_address").value = "";
			// console.log("bb");
		}
	});
	function DelRecord(eid,ename)
	{
		if (ename != "") {
			if (confirm("確定要刪除這個圖片嗎"+'('+ename+')?')) {
				//self.location.replace("memberpicdel.php?tid="+eid);
				
				document.getElementById("act").value = 'Delpic';
				document.getElementById("tid").value = eid;
				
				//alert(eid);
				document.frm3.action="bannerpicdel.php";	
				document.frm3.submit();				
			} 
		}
	}
	function Upload_Pic(){
		//alert(document.getElementById("fileToUpload2").value);
		if (document.getElementById("fileToUpload2").value == "") {
			alert("請選擇要上傳的圖檔 !");
			document.getElementById("fileToUpload2").focus(); 			
			return false;
		}		
		document.frm2.submit();
	}	
	function Save_User(){

		if (!checkString(document.all.store_id,"請輸入店家名稱!"))	return false;
		if (!checkString(document.all.register_name,"請輸入登記名稱!"))	return false;
		if (!checkString(document.all.tax_id,"請輸入統一編號!"))	return false;
		if (!checkString(document.all.manager_name,"請輸入負責人姓名!"))	return false;
		if (!checkString(document.all.register_address,"請輸入登記地址!"))	return false;
		if (!checkString(document.all.bank_name,"請輸入銀行名稱!"))	return false;
		if (!checkString(document.all.branch_name,"請輸入分行名稱!"))	return false;
		if (!checkString(document.all.bank_no,"請輸入銀行代號!"))	return false;
		if (!checkString(document.all.account_no,"請輸入帳號!"))	return false;
		if (!checkString(document.all.account_name,"請輸入帳戶名稱!"))	return false;
		if (!checkString(document.all.name,"請輸入姓名!"))	return false;
		if (!checkString(document.all.job_title,"請輸入職稱!"))	return false;
		if (!checkString(document.all.office_phone,"請輸入公司電話!"))	return false;
		if (!checkString(document.all.mobile,"請輸入手機!"))	return false;
		if (!checkString(document.all.email,"請輸入E-mail!"))	return false;
		if (!checkString(document.all.bill_address,"請輸入帳務地址!"))	return false;

		// if (document.all.banner_enddate.value <= document.all.banner_date.value) {
		// 	alert("小於等於!");
		// 	document.all.banner_enddate.focus();
		// 	return false;
		// }
		//if (!checkString(document.all.banner_link,"請輸入連結網址!"))	return false;
		
		document.frm3.submit();
		mode = "0";
	}		
	function GoAccount()
	{
			self.location.replace("bill.php");
	}
	function Logout(){
		//alert("登出系統!");
		self.location.replace('logout.php');
	}	
	//-->
	</SCRIPT>
      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; 2020 Jotangi Technology Co., Ltd</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">確定要離開?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">如果確定離開系統,請按下 "登出系統" .</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">返回</button>
          <a class="btn btn-primary" href="logout.php">登出系統</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <!--<script src="js/demo/chart-area-demo.js"></script>-->
  <!--<script src="js/demo/chart-pie-demo.js"></script>-->
  
  <!-- Page level plugins -->
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/datatables-demo.js"></script>
</body>

</html>
