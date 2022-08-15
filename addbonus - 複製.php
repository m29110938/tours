<?php
session_start();
if ($_SESSION['accname']==""){
	header("Location: logout.php"); 
}
if (($_SESSION['authority']!="1")){
	header("Location: main.php"); 
}
$act = isset($_POST['act']) ? $_POST['act'] : '';

$host = 'localhost';
$user = 'tours_user';
$passwd = 'tours0115';
$database = 'toursdb';
$link = mysqli_connect($host, $user, $passwd, $database);
mysqli_query($link,"SET NAMES 'utf8'");

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
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="main.php">
        <!--<div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div>-->

        <div class="sidebar-brand-text mx-3">點點</div>
      </a>

      <!-- Divider -->
      <!--<hr class="sidebar-divider my-0">-->

      <!-- Nav Item - Dashboard -->
      <li class="nav-item">
        <a class="nav-link" href="main.php">
          <i class="fas fa-fw fa-home"></i>
          <span>首頁</span></a>
      </li>

      <hr class="sidebar-divider">

      <!-- Nav Item - Charts -->
	  <?php if ($_SESSION['authority']!="4"){  ?>
      <li class="nav-item">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities0" aria-expanded="true" aria-controls="collapseUtilities0">
		  <i class="fas fa-fw fa-address-card"></i>
		  <span>會員中心</span>
		</a>	  
		<div id="collapseUtilities0" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
		  <div class="bg-white py-2 collapse-inner rounded">
			<a class="collapse-item" href="member.php">會員管理</a>
			<a class="collapse-item" href="mycoupon.php">票券列表</a>
			<a class="collapse-item" href="mybonus.php">紅利點數列表</a>
		  </div>
		</div>		  
      </li>
	  <?php } ?>
      <li class="nav-item active">
        <a class="nav-link " href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-fw fa-coffee"></i>
          <span>商圈管理</span>
        </a>	  
		<div id="collapseUtilities" class="collapse show" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
		  <div class="bg-white py-2 collapse-inner rounded">
			<a class="collapse-item" href="store.php">店家設定</a>
			<a class="collapse-item" href="storemember.php">店家會員</a>
			<a class="collapse-item" href="push.php">推播訊息</a>
			<?php if ($_SESSION['authority']!="4"){  ?>
			<a class="collapse-item" href="storetype.php">類別設定</a>
			<a class="collapse-item active" href="bonus.php">分潤設定</a>
			<?php } ?>
			<a class="collapse-item" href="discount.php">獨家優惠(推薦碼)</a>
		  </div>
		</div>
      </li>
	  <?php if ($_SESSION['authority']!="4"){  ?>
      <li class="nav-item">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities1" aria-expanded="true" aria-controls="collapseUtilities1">
		  <i class="fas fa-fw fa-cart-plus"></i>
		  <span>商品維護</span>
		</a>	  
		<div id="collapseUtilities1" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
		  <div class="bg-white py-2 collapse-inner rounded">
			<a class="collapse-item" href="producttype.php">商品分類</a>
			<a class="collapse-item" href="product.php">商品維護</a>
		  </div>
		</div>			  
      </li>	  
	  <?php } ?>
      <li class="nav-item">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities2" aria-expanded="true" aria-controls="collapseUtilities2">
		  <i class="fas fa-fw fa-shopping-bag"></i>
		  <span>訂單管理</span>
		</a>	  
		<div id="collapseUtilities2" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
		  <div class="bg-white py-2 collapse-inner rounded">
			<a class="collapse-item" href="order.php">訂單管理</a>
			<a class="collapse-item" href="profit.php">對帳管理</a>
		  </div>
		</div>			  
      </li>
	  <?php if ($_SESSION['authority']!="4"){  ?>
      <li class="nav-item">
        <a class="nav-link" href="coupon.php">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>行銷活動管理</span></a>
      </li>	  
      <li class="nav-item">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities3" aria-expanded="true" aria-controls="collapseUtilities3">
		  <i class="fas fa-fw fa-info"></i>
		  <span>客服中心</span>
		</a>	  
		<div id="collapseUtilities3" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
		  <div class="bg-white py-2 collapse-inner rounded">
			<a class="collapse-item" href="questiontype.php">問題分類</a>
			<a class="collapse-item" href="question.php">問題管理</a>
		  </div>
		</div>		  
      </li>
      <li class="nav-item">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities4" aria-expanded="true" aria-controls="collapseUtilities4">
		  <i class="fas fa-fw fa-folder-open"></i>
		  <span>訊息中心</span>
		</a>	  
		<div id="collapseUtilities4" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
		  <div class="bg-white py-2 collapse-inner rounded">
			<a class="collapse-item" href="banner.php">Banner管理</a>
			<a class="collapse-item" href="news.php">最新消息</a>
		  </div>
		</div>		  
      </li>	 
	  <?php } ?>	  
      <!-- Nav Item - Tables -->
 	  <?php if ($_SESSION['authority']=="1"){  ?>
     <li class="nav-item">
        <a class="nav-link" href="sysuser.php">
          <i class="fas fa-fw fa-user"></i>
          <span>系統管理</span></a>
      </li>
	  <?php } ?>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
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
			<h1 class="h5 mb-0 text-gray-800">分潤設定</h1>
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
			<form action="bonusadd.php" name="frm3" id="frm3" method="post">
			<input type="hidden" name="act" id="act"  value="<?php echo $act;?>"/>				  
			  
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">對象與分潤設定</h6>
                </div>
                <div class="card-body">
 					  <div class="form-group">
						<label class="form-label">簽約對象:</label>
						<div class="row align-items-center">
						  <div class="col-6">
							<input type="text" name="bonus_name1" id="bonus_name1" class="form-control" value="">
						  </div>
						</div>
					  </div>	
				  
					  <div class="form-group">
						<label class="form-label">系統費用:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="sys_rate1" id="sys_rate1" class="form-control" value="">
						  </div>
						</div>
					  </div>				
				  
					  <div class="form-group">
						<label class="form-label">行銷費用:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="marketing_rate1" id="marketing_rate1" class="form-control" value=""/>
						  </div>
						</div>
					  </div>
						<hr/>
 					  <div class="form-group">
						<label class="form-label">協力夥伴:</label>
						<div class="row align-items-center">
						  <div class="col-6">
							<input type="text" name="bonus_name2" id="bonus_name2" class="form-control" value="">
						  </div>
						</div>
					  </div>	
				  
					  <div class="form-group">
						<label class="form-label">系統費用:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="sys_rate2" id="sys_rate2" class="form-control" value="">
						  </div>
						</div>
					  </div>				
					  <div class="form-group">
						<label class="form-label">行銷費用:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="marketing_rate2" id="marketing_rate2" class="form-control" value=""/>
						  </div>
						</div>
					  </div>
				</div>
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">一般消費模式</h6>
                </div>
                <div class="card-body">
						  <div class="form-group">
							<label class="form-label">消費紅利:</label>
							<div class="row align-items-center">
							  <div class="col-auto">
								<input type="text" name="user_rate" id="user_rate" class="form-control" value=""/>
							  </div>
							</div>
						  </div>
						  <div class="form-group">
							<label class="form-label">生效日期:</label>
							<div class="row align-items-center">
							  <div class="col-auto">
								<input class="text-input small-input" type="date" name="startdate" id="startdate" value="" />
							  </div>
							</div>
						  </div>						  

				</div>
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">活動消費模式</h6>
                </div>
                <div class="card-body">
						  <div class="form-group">
							<label class="form-label">特殊紅利:</label>
							<div class="row align-items-center">
							  <div class="col-auto">
								<input type="text" name="event_rate" id="event_rate" class="form-control" value=""/>
							  </div>
							</div>
						  </div>
						  <div class="form-group">
							<label class="form-label">生效期間:</label>
							<div class="row align-items-center">
							  <div class="col-auto">
								<input class="text-input small-input" type="date" name="event_startdate" id="event_startdate" value="" />
							  </div>							  
							  <div class="col-auto">
								<input class="text-input small-input" type="date" name="event_enddate" id="event_enddate" value="" />
							  </div>							  
							</div>
						  </div>				  
				</div>
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">推薦回饋設定</h6>
                </div>
                <div class="card-body">
					  <div class="form-group">
						<input type="checkbox" name="group_mode" id="group_mode" value="1"><label class="form-label">團客模式店家:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="groupmode_rate" id="groupmode_rate" class="form-control" value="">						
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<input type="checkbox" name="hotel_mode" id="hotel_mode" value="2"><label class="form-label">旅宿模式店家:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="hotelmode_rate" id="hotelmode_rate" class="form-control" value="">						
						  </div>
						</div>
					  </div>					  
				</div>
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">服務內容設定</h6>
                </div>
                <div class="card-body">		
					  <input type="hidden" name="storeservice" id="storeservice"  value=""/>
					  <div class="form-group">
						<input type="checkbox" name="store_service" value="1" checked=true><label class="form-label">會員優惠店家</label>
					  </div>					  
					  <div class="form-group">
						<input type="checkbox" name="store_service" value="2" ><label class="form-label">紅利積點折抵店家</label>
					  </div>					  
					  <div class="form-group">
						<input type="checkbox" name="store_service" value="3" ><label class="form-label">點數立即入帳店家</label>
					  </div>					  
				</div>

                <div class="card-body">				
					  <div class="form-group">
						<label class="form-label">啟用狀態:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
								<select name="bonus_status" id="bonus_status" class="form-control custom-select">
								  <option value="">--請選擇--</option>
								  <option value="0">啟用</option>
								  <option value="1">下架</option>

								</select>
						  </div>
						</div>
					  </div>					  
                </div>
					  <div class="card-footer text-center">
							<button type="button" class="btn btn-success ml-auto" onclick="Save_User()">儲存</button>&nbsp;&nbsp;<button type="button" class="btn btn-info ml-auto" name="backacc" onclick="GoAccount();">返回</button>
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

		if (!checkString(document.all.bonus_name1,"請輸入簽約對象!"))	return false;
		if (!checkString(document.all.sys_rate1,"請輸入系統費用!"))	return false;
		if (!checkString(document.all.marketing_rate1,"請輸入行銷費用!"))	return false;
		if (!checkString(document.all.bonus_name2,"請輸入協力夥伴!"))	return false;
		if (!checkString(document.all.sys_rate2,"請輸入系統費用!"))	return false;
		if (!checkString(document.all.marketing_rate2,"請輸入行銷費用!"))	return false;
		
		if (document.getElementById("user_rate").value != "") {
			//alert(document.getElementById("event_startdate").value);
			//alert(document.getElementById("event_enddate").value);
			if ((document.getElementById("startdate").value == "" )){
				alert("請輸入生效日期!");
				document.getElementById("startdate").focus(); 			
				return false;
			}
			//document.getElementById("event_rate").focus(); 			
			//return false;
		}
		
		if (document.getElementById("event_rate").value != "") {
			//alert(document.getElementById("event_startdate").value);
			//alert(document.getElementById("event_enddate").value);
			if ((document.getElementById("event_startdate").value) > (document.getElementById("event_enddate").value)){
				alert("請輸入生效期間!");
				document.getElementById("event_startdate").focus(); 			
				return false;
			}
			//document.getElementById("event_rate").focus(); 			
			//return false;
		}		
		
		if (document.getElementById("group_mode").checked) {
				if (!checkString(document.all.groupmode_rate,"請輸入團客模式店家分潤"))	return false;
        }
		if (document.getElementById("hotel_mode").checked) {
				if (!checkString(document.all.hotelmode_rate,"請輸入旅宿模式店家分潤!"))	return false;
        }	
			
		var RQ_ck01 = document.getElementsByName("store_service");
		var store_service = '';
        for (var i = 0; i < RQ_ck01.length; i++) {
            if (!RQ_ck01[i].checked) {
				store_service = store_service+'0';
				if (i < RQ_ck01.length-1)
				    store_service = store_service+'|';
            }else{
				store_service = store_service+RQ_ck01[i].value;
				if (i < RQ_ck01.length-1)
				    store_service = store_service+'|';
			}
        }
		if (store_service == "0|0|0") {
			alert("請選擇服務內容設定!");
			document.getElementById("store_service")[0].focus(); 			
			return false;
		}		
		//alert(store_service); 
		document.getElementById("storeservice").value = store_service;		
		if (document.getElementById("bonus_status").value == "") {
			alert("請選擇啟用狀態 !");
			document.getElementById("bonus_status").focus(); 			
			return false;
		}
		document.frm3.submit();
		mode = "0";
	}	
	function GoAccount()
	{
			self.location.replace("bonus.php");
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
