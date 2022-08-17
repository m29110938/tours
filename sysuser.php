<?php
session_start();
if ($_SESSION['accname']==""){
	header("Location: logout.php"); 
}
if ($_SESSION['authority']!="1"){
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
    <?php
    include("sidebar.php"); 
	?>
	<script>
		document.querySelector('#ii').classList.add('active');
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
			<i class="fas fa-fw fa-user"></i>
			<h1 class="h5 mb-0 text-gray-800">系統管理</h1>
		  
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
			
			$userauthority = isset($_POST['userauthority']) ? $_POST['userauthority'] : '';
			$userauthority  = mysqli_real_escape_string($link,$userauthority);
			$userid = isset($_POST['user_id']) ? $_POST['user_id'] : '';
			$userid  = mysqli_real_escape_string($link,$userid);

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
				    <form action="sysuser.php" method="Post" name='frm1' id='frm1' class="card">
					<input type="hidden" name="act" id="act"  value=""/>
					<input type="hidden" name="tid" id="tid"  value=""/>
					<input type="hidden" name="page" id="page" value="1">					  
					<div class="row">
                        <div class="col-md-6 col-lg-3">
							<div class="form-group">
								<label class="form-label">權限群組:</label>
								<select name="userauthority" id="userauthority" class="form-control custom-select">
								  <option value="">--全選--</option>
								  <option value="1" <?php if ($userauthority=="1") echo "selected"; ?>>Administrator</option>
								  <option value="2" <?php if ($userauthority=="2") echo "selected"; ?>>Power User</option>
								  <option value="3" <?php if ($userauthority=="3") echo "selected"; ?>>User</option>
								</select>
							</div>
						</div>
						<div class="col-md-6 col-lg-3">
						  <div class="form-group">
							<label class="form-label">登入帳號:</label>
							<div class="row align-items-center">
							  <div class="col-auto">
								<input type="text" id="user_id" name="user_id" class="form-control w-12" value="<?php echo $userid; ?>">
							  </div>
							</div>
						  </div>	
						</div>
						<div class="col-md-6 col-lg-3">
						  <div class="form-group">
							  <label class="form-label">&nbsp;</label>
							  <div class="text-center">
								<button type="button" class="btn btn-info ml-auto" onclick='SubmitF();'>搜尋</button> &nbsp;<button type="button" class="btn btn-success ml-auto" onclick='GoAddUser()'>新增</button>
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
              <h6 class="m-0 font-weight-bold text-primary">系統帳號列表</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
				<?php
				$act = isset($_POST['act']) ? $_POST['act'] : '';
				$havedata = 0;
				//if ($act == 'Qry') {
				
					$sql = "SELECT * FROM sysuser where user_trash=0 ";
					if ($userauthority != "") {	
						$sql = $sql." and group_id=".$userauthority."";
					}
					if ($userid != "") {	
						$sql = $sql." and user_id like '%".$userid."%'";
					}
			
					$sql = $sql." order by user_id asc ";
					//echo $sql;
					//exit;

					$idx = 0;
					if ($result = mysqli_query($link, $sql)){
						if (mysqli_num_rows($result) > 0){
							echo "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>";
							echo "  <thead>";
							echo "    <tr>";
							echo "	  <th>No.</th>";
							echo "	  <th>帳號</th>";
							echo "	  <th>密碼</th>";
							echo "	  <th>權限群組</th>";						  
							echo "	  <th>姓名</th>";
							echo "	  <th>手機</th>";
							echo "	  <th>建檔日期</th>";
							echo "	  <th></th>";
							echo "	  <th></th>";
							echo "    </tr>";
							echo "  </thead>";
							echo "  <tbody>";
							while($row = mysqli_fetch_array($result)){
									$idx = $idx + 1;				
									echo "  <tr>";
									echo "    <td>".$idx."</td>";
									echo "    <td>".$row['user_id']."</td>";
									echo "    <td>".$row['user_pwd']."</td>";
									//echo "    <td>".$row['group_id']."</td>";
										switch ($row['group_id']) {
											case 1:
												echo "    <td>Administrator</td>";
												break;
											case 2:
												echo "    <td>Power User</td>";
												break;
											case 3:
												echo "    <td>User</td>";
												break;
											default:
												echo "    <td>&nbsp;</td>";
										}									
									echo "    <td>".$row['user_name']."</td>";
									echo "    <td>".$row['user_mobile']."</td>";
									echo "    <td>".$row['user_created_at']."</td>";
									echo "    <td>";
									echo "      <a href='javascript:GoEdit(".$row['sid'].")'><i class='fa fa-edit'></i></a>";
									echo "    </td>";
									echo "    <td>";
									echo "  	<a href='javascript:GoDel(".$row['sid'].")'><i class='fa fa-trash'></i></a>";					
									echo "    </td>   ";                      
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
				  <div class="col-md-12 ">
						<br/>
						<button type="button" onclick="ExportLog();" class="btn btn-info ml-auto">匯出</button>
				  </div>
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
  <SCRIPT LANGUAGE=javascript>
	<!--
	function ExportLog()
	{
		document.all.downloadlog.src="sysuser.php";
	}	
	function GoAddUser()
	{	
		document.getElementById("act").value = 'Add';
		document.frm1.action="addsysuser.php";
		//self.location.replace("addsysuser.php");
		document.frm1.submit();
	}
	//GoEdit()
	function GoEdit(id)
	{	
		document.getElementById("act").value = 'Edit';
		document.getElementById("tid").value = id;
		//alert(id);
		document.frm1.action="editsysuser.php";	
		//self.location.replace("editsysuser.php");	
		document.frm1.submit();
	}
	function GoDel(id)
	{	
		if (confirm('確定要刪除這筆資料嗎?')) {
			document.getElementById("act").value = 'Del';
			document.getElementById("tid").value = id;
			//alert(id);
			document.frm1.action="sysuserdel.php";	
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
