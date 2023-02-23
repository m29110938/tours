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
	//echo $tid;
	$host = 'localhost';
	$user = 'tours_user';
	$passwd = 'tours0115';
	$database = 'toursdb';
		
	$link = mysqli_connect($host, $user, $passwd, $database);
	mysqli_query($link,"SET NAMES 'utf8'");
	// print_r($_POST);
	$sql = " SELECT *, b.member_id as memberid FROM mybonus as a ";
	$sql = $sql." INNER JOIN (select * from member) as b on a.member_id = b.mid ";
	$sql = $sql." INNER JOIN (select * from orderinfo) as c on a.order_no = c.order_no ";
	$sql = $sql." INNER JOIN (select * from store) as d on d.sid = c.store_id ";
	$sql = $sql." where bid=$tid ";
	// echo $sql;
	if ($result = mysqli_query($link, $sql)){
		if (mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_array($result)){
				$store_name = $row['store_name'];
				$order_created_at = $row['order_created_at'];
				$pay_status = $row['pay_status'];
				$pay_type = $row['pay_type'];
				$order_amount = $row['order_amount'];
				$discount_amount = $row['discount_amount'];
				$bonus_point = $row['bonus_point'];
				$order_pay = $row['order_pay'];
				$bonus_get = $row['bonus_get'];
				$bonus_date = $row['bonus_date'];
				$bonus_end_date = $row['bonus_end_date'];
				$member_name = $row['member_name'];
				$member_id = $row['memberid'];
				$bonus_end_date = $row['bonus_end_date'];
			}
		mysqli_close($link);
		}else{
				$store_type = "";
				$storetype_name = "";
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
		document.querySelector('#mybonusphp').classList.add('active');
		document.querySelector('#aa').classList.add('active');
		document.querySelector('#a').classList.remove('collapsed');
		document.querySelector('#collapseUtilities0').classList.add('show');
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
			<h3 class="h5 mb-0 text-gray-800">紅利點數列表</h3>
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
			<form action="storetypeedit.php" name="frm3" id="frm3" method="post">
			<input type="hidden" name="act" id="act"  value="<?php echo $act;?>"/>		
			<input type="hidden" name="tid" id="tid"  value="<?php echo $tid;?>"/>				  
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">紅利明細</h6>
                </div>
                <div class="card-body">
					  <div class="form-group">
                        <h3>紅利明細</h3>
						<label class="form-label">商店名稱: <?=$store_name?></label><br><br>
						<label class="form-label">訂單時間: <?=$order_created_at?></label><br><br>
						<label class="form-label">訂單狀態: <?=$pay_status?></label><br><br>
						<label class="form-label">付款方式: <?=$pay_type?></label><br><br>
						<label class="form-label">訂單金額: <?=$order_amount?></label><br><br>
						<label class="form-label">現金券折抵: <?=$discount_amount?></label><br><br>
						<label class="form-label">紅利折抵: <?=$bonus_point?></label><br><br>
						<label class="form-label">實付金額: <?=$order_pay?></label><br><br>
						<label class="form-label">紅利贈點: <?=$bonus_get?></label><br><br>
						<label class="form-label">紅利歸戶日期: <?=$bonus_date?></label><br><br>
						<label class="form-label">紅利到期日期: <?=$bonus_end_date?></label><br><br>
                        <h3>付款人資訊</h3>
						<label class="form-label">會員姓名: <?=$member_name?></label><br><br>
						<label class="form-label">會員電話: <?=$member_id?></label><br><br>
                        <h3>備註</h3>
						<label class="form-label">無</label><br><br>
						<!-- <div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="store_type" id="store_type" class="form-control" value="<?php echo $store_type;?>">
						  </div>
						</div>
					  </div>	 -->
				  
					  <div class="card-footer text-center">
							<!-- <button type="button" class="btn btn-success ml-auto" onclick="Save_User()">儲存</button>&nbsp;&nbsp; -->
                            <button type="button" class="btn btn-info ml-auto" name="backacc" onclick="GoAccount();">返回</button>
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
	function Save_User(){

		if (!checkString(document.all.store_type,"請輸入類別代碼!"))	return false;
		if (!checkString(document.all.storetype_name,"請輸入類別名稱!"))	return false;
		
		document.frm3.submit();
		mode = "0";
	}	
	function GoAccount()
	{
			self.location.replace("mybonus.php");
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
