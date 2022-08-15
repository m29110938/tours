<?php
session_start();
if ($_SESSION['accname']==""){
	header("Location: logout.php"); 
}
if (($_SESSION['authority']=="2")||($_SESSION['authority']=="3")){
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
			<a class="collapse-item" href="bonus.php">分潤設定</a>
			<?php } ?>
			<a class="collapse-item active" href="discount.php">獨家優惠(推薦碼)</a>
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
			<h1 class="h5 mb-0 text-gray-800">獨家優惠設定</h1>
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
			<form action="discountadd.php" name="frm3" id="frm3" method="post">
			<input type="hidden" name="act" id="act"  value="<?php echo $act;?>"/>				  
			  
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">新增獨家優惠券</h6>
                </div>
                <div class="card-body">
				<table width="100%">
				<tr >
				
					<td width="30%">
					  <div class="form-group">
						<label class="form-label">優惠券代碼:</label>
						<div class="row align-items-center form-inline">
						  <div class="col-auto">
						  <input type="hidden" name="sid_coupon_id" id="sid_coupon_id" class="form-control" value="">
						  <span id="store_id">店家代號</span>_<input type="text" name="coupon_id" id="coupon_id" class="form-control" oninput="inputvalue(event)" value="">
						  </div>
						</div>
					  </div>	
					  <div class="form-group">
						<label class="form-label">票券類型:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
								<select name="coupon_type" id="coupon_type" class="form-control custom-select" onchange="check_type()">
								  <option value="">--請選擇--</option>
								  <option value="1">店家優惠券</option>
								  <option value="2">店家生日禮</option>
								  <option value="3">店家入會禮</option>
								</select>
						  </div>
						</div>
					  </div>					
					  <div class="form-group">
						<label class="form-label">內容說明:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<textarea id="coupon_description" name="coupon_description" rows="4" cols="50"></textarea>	
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="form-label">狀態:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
								<select name="coupon_status" id="coupon_status" class="form-control custom-select">
								  <option value="">--請選擇--</option>
								  <option value="1">啟用</option>
								  <option value="0">停用</option>
								</select>
						  </div>
						</div>
					  </div>					  
					</td>
					<td width="10%">&nbsp;</td>
					<td width="40%" valign="top">
					  <div class="form-group">
						<label class="form-label">優惠券名稱:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="coupon_name" id="coupon_name" class="form-control" value="">
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="form-label">發放期間:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							  <input class="text-input small-input" type="date" name="coupon_issue_startdate" id="coupon_issue_startdate" value="" />
							  <input class="text-input small-input" type="date" name="coupon_issue_enddate" id="coupon_issue_enddate" value="" />
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="form-label">使用期限:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							  <input class="text-input small-input" type="date" name="coupon_startdate" id="coupon_startdate" value="" />
							  <input class="text-input small-input" type="date" name="coupon_enddate" id="coupon_enddate" value="" />
						  </div>
						</div>
					  </div>	
					  <div class="form-group">
						<label class="form-label">滿額使用:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="coupon_rule" id="coupon_rule" class="form-control" value="">
						  </div>
						  <div class="col-auto">
							元
						  </div>						
						</div>
					  </div>	
					  <div class="form-group">
						<label class="form-label">折扣方式:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
								<select name="coupon_discount" id="coupon_discount" class="form-control custom-select">
								  <option value="">--請選擇--</option>
								  <option value="1">折扣金額</option>
								  <option value="2">折扣%</option>
								</select>
						  </div>
						  <div class="col-auto">
								<input type="text" name="discount_amount" id="discount_amount" class="form-control" value=""/>
						  </div>
						  <div class="col-auto">
								元
						  </div>
						</div>
					  </div>	
					  <div class="form-group">
						<label class="form-label">發行店家:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<!--<input type="text" name="coupon_storeid" id="coupon_storeid" class="form-control" value=""/>-->
								<select name="coupon_storeid" id="coupon_storeid" class="form-control custom-select">
								  <option value="">--請選擇--</option>
								  <?php
									$sql3 = "select store_id,sid,store_name from store where store_trash=0 ";
									if ($_SESSION['authority']=="4"){
										$sql3 = $sql3." and sid=".$_SESSION['loginsid']."";
									}
									$sql3 = $sql3." order by shopping_area,sid ";									
									if ($result3 = mysqli_query($link, $sql3)){
										if (mysqli_num_rows($result3) > 0){
											$selectedflag = "";
											while($row3 = mysqli_fetch_array($result3)){
												if ($coupon_storeid == strval($row3['store_id'])) {
													$selectedflag = " selected ";
												}else{
													$selectedflag = "";
												}
												echo "<option value='".$row3['store_id']."' ".$selectedflag." >".$row3['store_name']."</option>";
											}
										}
									}								
								  ?>
								</select>								
						  </div>
						</div>
					  </div>	
					  <div class="form-group">
						<label class="form-label">發放數量:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="coupon_number" id="coupon_number" class="form-control" oninput = "value=value.replace(/[^\d]/g,'')" value="">
						  </div>
						</div>
					  </div>				  
					</td>
				</tr>
				<tr>
					<td>

					</td>
					<td width="10%">&nbsp;</td>
					<td width="40%">
				
					</td>
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
	document.getElementById("coupon_storeid").onchange=changecouponid;
	function changecouponid()
	{
		// console.log('==');
		document.getElementById("sid_coupon_id").value = document.getElementById("coupon_storeid").value+"_"+document.getElementById("coupon_id").value;
		document.getElementById("store_id").innerText = document.getElementById("coupon_storeid").value;
	}
	function inputvalue(e){
		// console.log('--');
		document.getElementById("sid_coupon_id").value = document.getElementById("coupon_storeid").value+"_"+document.getElementById("coupon_id").value;
	}
	function check_type(){
		if (document.getElementById("coupon_type").value == "2") {
			document.getElementById("coupon_number").value="";
			document.getElementById("coupon_number").disabled="disabled";
			document.getElementById("coupon_startdate").value="";
			document.getElementById("coupon_startdate").disabled="disabled";
			document.getElementById("coupon_enddate").value="";
			document.getElementById("coupon_enddate").disabled="disabled";
			document.getElementById("coupon_issue_startdate").type="month";
			document.getElementById("coupon_issue_enddate").type="month";
		}else{
			document.getElementById("coupon_number").disabled="";
			document.getElementById("coupon_startdate").value="";
			document.getElementById("coupon_startdate").disabled="";
			document.getElementById("coupon_enddate").value="";
			document.getElementById("coupon_enddate").disabled="";
			document.getElementById("coupon_issue_startdate").type="date";
			document.getElementById("coupon_issue_startdate").value="";
			document.getElementById("coupon_issue_enddate").type="date";
			document.getElementById("coupon_issue_enddate").value="";
		}
	}
	function Save_User(){

		if (!checkString(document.all.coupon_id,"請輸入優惠券代碼!"))	return false;
		if (document.getElementById("coupon_type").value == "") {
			alert("請選擇票券類型 !");
			document.getElementById("coupon_type").focus(); 			
			return false;
		}		
		if (!checkString(document.all.coupon_name,"請輸入優惠券名稱!"))	return false;
	
		if (!checkString(document.all.coupon_description,"請輸入內容說明!"))	return false;
		
		
		if (!checkString(document.all.coupon_issue_startdate,"請輸入發放期間(起)!"))	return false;
		if (!checkString(document.all.coupon_issue_enddate,"請輸入發放期間(迄)!"))	return false;
		if (document.all.coupon_issue_enddate.value <= document.all.coupon_issue_startdate.value) {
			alert("發放期間(迄)小於等於發放期間(起)!");
			document.all.coupon_enddate.focus();
			return false;
		}
		if (document.getElementById("coupon_type").value != "2") {
			if (!checkString(document.all.coupon_startdate,"請輸入使用期限(起)!"))	return false;
			if (!checkString(document.all.coupon_enddate,"請輸入使用期限(迄)!"))	return false;
			if (document.all.coupon_enddate.value <= document.all.coupon_startdate.value) {
				alert("使用期限(迄)小於等於使用期限(起)!");
				document.all.coupon_enddate.focus();
				return false;
			}
			if (!checkString(document.all.coupon_number,"請輸入發放數量!"))	return false;
		}
		
		
		if (!checkString(document.all.coupon_rule,"請輸入滿額使用條件!"))	return false;

		//if (!checkString(document.all.coupon_discount,"請輸入折扣方式!"))	return false;
		if (document.getElementById("coupon_discount").value == "") {
			alert("請選擇折扣方式 !");
			document.getElementById("coupon_discount").focus(); 			
			return false;
		}		
		if (!checkString(document.all.discount_amount,"請輸入折扣金額!"))	return false;

		//if (!checkString(document.all.coupon_storeid,"請輸入發行店家!"))	return false;
		if (document.getElementById("coupon_storeid").value == "") {
			alert("請選擇發行店家 !");
			document.getElementById("coupon_storeid").focus(); 			
			return false;
		}
		
		if (document.getElementById("coupon_status").value == "") {
			alert("請選擇店家啟用狀態 !");
			document.getElementById("coupon_status").focus(); 			
			return false;
		}
		//
		
		document.frm3.submit();
		mode = "0";
	}	
	function GoAccount()
	{
			self.location.replace("discount.php");
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
