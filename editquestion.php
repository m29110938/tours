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
	
	$sql = "SELECT * FROM question where question_trash=0 and rid=$tid";
	//echo $sql;
	//exit;

	if ($result = mysqli_query($link, $sql)){
		if (mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_array($result)){
				$questiontype = $row['qid'];
				$question_subject = $row['question_subject'];
				$question_description = $row['question_description'];
			}
		//mysqli_close($link);
		}else{
				$questiontype = "";
				$question_subject = "";
				$question_description = "";
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
      document.querySelector('#questionphp').classList.add('active');
      document.querySelector('#ff').classList.add('active');
      document.querySelector('#f').classList.remove('collapsed');
      document.querySelector('#collapseUtilities3').classList.add('show');
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
			<h1 class="h5 mb-0 text-gray-800">問題管理</h1>
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
			<form action="questionedit.php" name="frm3" id="frm3" method="post">
			<input type="hidden" name="act" id="act"  value="<?php echo $act;?>"/>		
			<input type="hidden" name="tid" id="tid"  value="<?php echo $tid;?>"/>				  
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">編輯問題管理資料</h6>
                </div>
                <div class="card-body">
					  <div class="form-group">
						<label class="form-label">問題分類:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
								<select name="question_type" id="question_type" class="form-control custom-select">
								  <option value="0">--請選擇--</option>
								  <?php
										
									$sql2 = "select qid,questiontype_name from questiontype where questiontype_trash=0 order by questiontype_name asc";
									
									if ($result2 = mysqli_query($link, $sql2)){
										if (mysqli_num_rows($result2) > 0){
											$selectedflag = "";
											while($row2 = mysqli_fetch_array($result2)){
												if ($questiontype == strval($row2['qid'])) {
													$selectedflag = " selected ";
												}else{
													$selectedflag = "";
												}
												echo "<option value='".$row2['qid']."' ".$selectedflag." >".$row2['questiontype_name']."</option>";
											}
										}
									}								
								  ?>
								</select>
						  </div>
						</div>
					  </div>
 					  <div class="form-group">
						<label class="form-label">問題標題:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="question_subject" id="question_subject" class="form-control" value="<?php echo $question_subject;?>">
						  </div>
						</div>
					  </div>				
					  <div class="form-group">
						<label class="form-label">說明:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<textarea id="question_description" name="question_description" rows="4" cols="50"><?php echo $question_description;?></textarea>							
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
	function Save_User(){
		if (document.getElementById("question_type").value == "0") {
			alert("請選擇問題分類 !");
			document.getElementById("question_type").focus(); 			
			return false;
		}
		if (!checkString(document.all.question_subject,"請輸入問題標題!"))	return false;
		if (!checkString(document.all.question_description,"請輸入說明!"))	return false;
		
		document.frm3.submit();
		mode = "0";
	}	
	function GoAccount()
	{
			self.location.replace("question.php");
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
