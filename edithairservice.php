<?php
session_start();
if ($_SESSION['accname']==""){
	header("Location: logout.php"); 
}
if (($_SESSION['authority']=="3")){
	header("Location: main.php"); 
}


	$act = isset($_POST['act']) ? $_POST['act'] : '';
	$tid = isset($_POST['tid']) ? $_POST['tid'] : '';
	//echo $tid;
	$host = 'localhost';
	$user = 'tours_user';
	$passwd = 'tours0115';
	$database = 'toursdb';
		
	$link = mysqli_connect($host, $user, $passwd, $database);
	mysqli_query($link,"SET NAMES 'utf8'");
	
	$sql = "SELECT * FROM hairservice where service_trash=0 and xid=$tid";
	//echo $sql;
	//exit;

	if ($result = mysqli_query($link, $sql)){
		if (mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_array($result)){
				$store_id = $row['store_id'];
				$service_name = $row['service_name'];
				$service_code = $row['service_code'];
				$service_time = $row['service_time'];
				$service_status = $row['service_status'];
				$service_price = $row['service_price'];
			}
		//mysqli_close($link);
		}else{
				$store_id = "";
				$service_name = "";
				$service_code = "";
				$service_time = "";
				$service_status = "";
				$service_price = "";
		}
	}	

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
		document.querySelector('#hairservicephp').classList.add('active');
		document.querySelector('#bb').classList.add('active');
		document.querySelector('#b').classList.remove('collapsed');
		document.querySelector('#collapseUtilities').classList.add('show');
		document.querySelector('#hairservicephp').style.display = 'block';
		document.querySelector('#hairstylistphp').style.display = 'block';
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
			<h1 class="h5 mb-0 text-gray-800">服務項目管理</h1>
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
			<form action="hairserviceedit.php" name="frm3" id="frm3" method="post">
			<input type="hidden" name="act" id="act"  value="<?php echo $act;?>"/>		
			<input type="hidden" name="tid" id="tid"  value="<?php echo $tid;?>"/>				  
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">編輯服務資料</h6>
                </div>
                <div class="card-body">
					  <div class="form-group">
						<label class="form-label">店家:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<!--<input type="text" name="coupon_storeid" id="coupon_storeid" class="form-control" value=""/>-->
								<select name="store_id" id="store_id" class="form-control custom-select">
								  <option value="">--請選擇--</option>
								  <?php
									$sql3 = "select sid,store_name from store where store_trash=0 and store_type=1";
									if ($_SESSION['authority']=="4"){
										$sql3 = $sql3." and sid=".$_SESSION['loginsid']."";
									}
									$sql3 = $sql3." order by shopping_area,sid ";									
									if ($result3 = mysqli_query($link, $sql3)){
										if (mysqli_num_rows($result3) > 0){
											$selectedflag = "";
											while($row3 = mysqli_fetch_array($result3)){
												if ($store_id == strval($row3['sid'])) {
													$selectedflag = " selected ";
												}else{
													$selectedflag = "";
												}												
												echo "<option value='".$row3['sid']."' ".$selectedflag." >".$row3['store_name']."</option>";
											}
										}
									}								
								  ?>
								</select>								
						  </div>
						</div>
					  </div>	
				  
					  <div class="form-group">
						<label class="form-label">服務代碼:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
								<select name="service_code" id="service_code" class="form-control custom-select">
								  <option value="">--請選擇--</option>
								  <?php
									$selectedflag = "";
									for ($i=1;$i<=10;$i++){
										if (strval($service_code) == $i) {
											$selectedflag = " selected ";
										}else{
											$selectedflag = "";
										}												
										echo "<option value='".$i."' ".$selectedflag." >".$i."</option>";
									}
								
								  ?>
								</select>							
						  </div>
						</div>
					  </div>					  
					  <div class="form-group">
						<label class="form-label">服務名稱:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="service_name" id="service_name" class="form-control" value="<?php echo $service_name;?>">
						  </div>
						</div>
					  </div>				
					  <div class="form-group">
						<label class="form-label">服務時間:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="service_time" id="service_time" class="form-control" value="<?php echo $service_time;?>">
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="form-label">服務價格:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="service_price" id="service_price" class="form-control" value="<?php echo $service_price;?>">
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="form-label">服務狀態:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
								<select name="service_status" id="service_status" class="form-control custom-select">
								  <option value="">--請選擇--</option>
								  <option value="0" <?php if ($service_status=="0") echo " selected"; ?>>啟用</option>
								  <option value="1" <?php if ($service_status=="1") echo " selected"; ?>>下架</option>

								</select>
						  </div>
						</div>
					  </div>					  
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
	<!--

	function validateMobile(sMobile) {
	  //var reEmail = /^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9](?:[a-zA-Z0-9\-](?!\.)){0,61}[a-zA-Z0-9]?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9\-](?!$)){0,61}[a-zA-Z0-9]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/;
	  var reMobile = /((?=(09))[0-9]{10})$/g;
	  if(!sMobile.match(reMobile)) {
		alert("輸入的 手機號碼 格式有誤!");
		return false;
	  }

	  return true;

	}		
	function Save_User(){
		//if (!checkString(document.all.store_id,"請輸入店家代碼!"))	return false;
		if (document.getElementById("store_id").value == "") {
			alert("請選擇店家 !");
			document.getElementById("store_id").focus(); 			
			return false;
		}		
		//if (!checkString(document.all.service_code,"請輸入服務代碼!"))	return false;
		if (document.getElementById("service_code").value == "") {
			alert("請選擇服務代碼 !");
			document.getElementById("service_code").focus(); 			
			return false;
		}			
		if (!checkString(document.all.service_name,"請輸入服務名稱!"))	return false;
		
		//if (document.getElementById("shopping_area").value == "0") {
		//	alert("請選擇商圈分類 !");
		//	document.getElementById("shopping_area").focus(); 			
		//	return false;
		//}		
		if (!checkString(document.all.service_time,"請輸入服務時間!"))	return false;
		
		if (!checkString(document.all.service_price,"請輸入服務價格!"))	return false;
//Checkinno
		//if (!Checkinno(document.all.service_price,"請輸入數字!"))	return false;

		if (document.getElementById("service_status").value == "") {
			alert("請選擇服務狀態 !");
			document.getElementById("service_status").focus(); 			
			return false;
		}
		document.frm3.submit();

		//if (validateMobile(document.getElementById("user_mobile").value)) {
		//	document.frm3.submit();
		//}else {
		//	return false;
		//}
		
		mode = "0";
	}	
	function GoAccount()
	{
			self.location.replace("hairservice.php");
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
