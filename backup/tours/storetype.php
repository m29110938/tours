<?php
session_start();
if ($_SESSION['accname']==""){
	header("Location: logout.php"); 
}
if ($_SESSION['authority']=="4"){
	header("Location: main.php"); 
}
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
  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
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
			<a class="collapse-item active" href="storetype.php">類別設定</a>
			<a class="collapse-item" href="bonus.php">紅利點數設定</a>
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
			<a class="collapse-item" href="profit.php">分潤報表(對帳)</a>
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
			<h1 class="h5 mb-0 text-gray-800">店家類別設定</h1>
		  
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
		<?php
			$act = isset($_POST['act']) ? $_POST['act'] : '';

			$host = 'localhost';
			$user = 'tours_user';
			$passwd = 'tours0115';
			$database = 'toursdb';
			$link = mysqli_connect($host, $user, $passwd, $database);
			mysqli_query($link,"SET NAMES 'utf8'");
		
			$storetype = isset($_POST['store_type']) ? $_POST['store_type'] : '';
			$storetype  = mysqli_real_escape_string($link,$storetype);
			$storetypename = isset($_POST['storetype_name']) ? $_POST['storetype_name'] : '';
			$storetypename  = mysqli_real_escape_string($link,$storetypename);
		
		?>
        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->

          <!-- Content Row -->
          <div class="row">

            <div class="col-lg-12 mb-4">

              <!-- Illustrations -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">搜尋條件</h6>
                </div>
				  <div class="card-body">
				    <form action="storetype.php" method="Post" name='frm1' id='frm1' class="card">
					<input type="hidden" name="act" id="act"  value=""/>
					<input type="hidden" name="tid" id="tid"  value=""/>
					<input type="hidden" name="page" id="page" value="1">
					<div class="row">
						<div class="col-md-6 col-lg-3">
						  <div class="form-group">
							<label class="form-label">類別代碼:</label>
							<div class="row align-items-center">
							  <div class="col-auto">
								<input type="text" id="store_type" name="store_type" class="form-control w-12" value="<?php echo $storetype; ?>">
							  </div>
							</div>
						  </div>	
						</div>
						<div class="col-md-6 col-lg-3">
						  <div class="form-group">
							<label class="form-label">類別名稱:</label>
							<div class="row align-items-center">
							  <div class="col-auto">
								<input type="text" id="storetype_name" name="storetype_name" class="form-control w-12" value="<?php echo $storetypename; ?>">
							  </div>
							</div>
						  </div>	
						</div>	
						<div class="col-md-6 col-lg-2">
						  <div class="form-group">
							  <label class="form-label">&nbsp;</label>
							  <div class="text-center">
								<button type="button" class='btn btn-info ml-auto' onclick='SubmitF();'>搜尋</button> &nbsp;<?php if ($_SESSION['authority']=="1"){ ?><button type="button" class="btn btn-success ml-auto" onclick='GoAddUser()'>新增</button> <?php }  ?>
							  </div>							
						  </div>
						</div>						
					</div>					
					</form>
				  </div>
              </div>

              <!-- Approach -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">店家類別列表</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
				<?php
				$act = isset($_POST['act']) ? $_POST['act'] : '';
				$havedata = 0;
				//if ($act == 'Qry') {
				
					$sql = "SELECT * FROM store_type where storetype_trash=0 ";

					if ($storetype != "") {	
						$sql = $sql." and store_type like '%".$storetype."%'";
					}
					if ($storetypename != "") {	
						$sql = $sql." and storetype_name like '%".$storetypename."%'";
					}
			
					$sql = $sql." order by store_type,storetype_name asc ";
					//echo $sql;
					//exit;
					$idx = 0;
					if ($result = mysqli_query($link, $sql)){
						if (mysqli_num_rows($result) > 0){
							echo "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>";
							echo "  <thead>";
							echo "    <tr>";
							echo "	  <th>No.</th>";
							echo "	  <th>類別代碼</th>";
							echo "	  <th>類別名稱</th>";
							//echo "	  <th>建立時間</th>";						  
							if (($_SESSION['authority']=="1")||($_SESSION['authority']=="2")){ echo "	  <th></th>"; }
							if ($_SESSION['authority']=="1"){ echo "	  <th></th>"; }
							echo "    </tr>";
							echo "  </thead>";
							echo "  <tbody>";
							while($row = mysqli_fetch_array($result)){
									$idx = $idx + 1;				
									echo "  <tr>";
									echo "    <td>".$idx."</td>";
									echo "    <td>".$row['store_type']."</td>";
									echo "    <td>".$row['storetype_name']."</td>";
									//echo "    <td>".$row['storetype_created_at']."</td>";
								if (($_SESSION['authority']=="1")||($_SESSION['authority']=="2")){	
									echo "    <td>";
									echo "      <a href='javascript:GoEdit(".$row['rid'].")'><i class='fa fa-edit'></i></a>";
									echo "    </td>";
								}
								if ($_SESSION['authority']=="1"){
									echo "    <td>";
									echo "  	<a href='javascript:GoDel(".$row['rid'].")'><i class='fa fa-trash'></i></a>";					
									echo "    </td>   ";  
								}
									echo "  </tr>";
							}
							echo "  </tbody>";
							echo "</table>";
							echo "<br/>";	
							$havedata = 1;
						}else{
							$page = 0;
							$pages = 0;
							$havedata = 0;
						}
					}
				//}
				mysqli_close($link);	
				?>				  

				  </div>
				<?php
					if ($act == 'Qry'){
						if($havedata == 1){  ?>

					<?php }else{ echo "沒有符合條件的資料!";}} ?>
				</div>
			  </div>

            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->
	
      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; 2020 Jotangi Technology Co., Ltd	</span>
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
	
  <SCRIPT LANGUAGE=javascript>
	<!--
	function ExportLog()
	{
		document.all.downloadlog.src="storetype.php";
	}	
	function GoAddUser()
	{	
		document.getElementById("act").value = 'Add';
		document.frm1.action="addstoretype.php";
		//self.location.replace("addstoretype.php");
		document.frm1.submit();
	}
	//GoEdit()
	function GoEdit(id)
	{	
		document.getElementById("act").value = 'Edit';
		document.getElementById("tid").value = id;
		//alert(id);
		document.frm1.action="editstoretype.php";	
		//self.location.replace("editstoretype.php");	
		document.frm1.submit();
	}
	function GoDel(id)
	{	
		if (confirm('確定要刪除這筆資料嗎?')) {
			document.getElementById("act").value = 'Del';
			document.getElementById("tid").value = id;
			//alert(id);
			document.frm1.action="storetypedel.php";	
			document.frm1.submit();
		}
	}	
	function Logout(){
		//alert("登出系統!");
		self.location.replace('logout.php');
	}	
	function SubmitF()
	{
		document.getElementById("page").value = "1";	
		Submit();
	}	
	function Submit()
	{
		//alert("1");
		document.getElementById("act").value = 'Qry';
		document.frm1.submit();
	}	
	//-->
  </SCRIPT>			
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
