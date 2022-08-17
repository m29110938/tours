<?php
session_start();
if ($_SESSION['accname']==""){
	header("Location: logout.php"); 
}
if (($_SESSION['authority']=="3")||($_SESSION['authority']=="4")){
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
    <?php
    include("sidebar.php"); 
	?>
	<script>
		document.querySelector('#memberphp').classList.add('active');
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
			<h1 class="h5 mb-0 text-gray-800">會員資料</h1>
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
			<form action="memberadd.php" name="frm3" id="frm3" method="post">
			<input type="hidden" name="act" id="act"  value="<?php echo $act;?>"/>			  
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">新增會員資料</h6>
                </div>
                <div class="card-body">
				<table width="100%">
				<tr >
					<td width="50%">
 					  <div class="form-group">
						<label class="form-label">帳號:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="member_id" id="member_id" class="form-control" value="">
						  </div>
						</div>
					  </div>					  
					  
					  <div class="form-group">
						<label class="form-label">密碼:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="password" name="member_pwd" id="member_pwd" class="form-control" value=""/>
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="form-label">確認密碼:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="password" name="member_pwd2" id="member_pwd2" class="form-control" value=""/>
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="form-label">會員名稱:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="member_name" id="member_name" class="form-control" value="">
						  </div>
						</div>
					  </div>				
					  
					</td>
					<td width="5%">&nbsp;</td>
					<td width="45%">
<?php if ($act == "Edit") {   //	member_picture ?>						
						<div>照片:</div>
						<div><img src="images/default.png" width="320"><button type="button" class="btn btn-success ml-auto">更換照片</button></div>
<?php }?>
					</td>
				</tr>
				<tr>
					<td>
					  <div class="form-group">
						<label class="form-label">會員類型:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
								<select name="member_type" id="member_type" class="form-control custom-select">
								  <option value="">--請選擇--</option>
								  <option value="1">一般會員</option>
								  <option value="2">特約店家</option>
								  <option value="3">特約旅行社</option>
								  <option value="4">導遊</option>
								</select>
						  </div>
						</div>
					  </div>	
					  <div class="form-group">
						<label class="form-label">性別:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
								<select name="member_gender" id="member_gender" class="form-control custom-select">
								  <option value="">--請選擇--</option>
								  <option value="0">女</option>
								  <option value="1">男</option>

								</select>
						  </div>
						</div>
					  </div>					  
					  <div class="form-group">
						<label class="form-label">電子信箱:</label>
						<div class="row align-items-center">
						  <div class="col-8">
							<input type="text" name="member_email" id="member_email" class="form-control" value=""/>
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="form-label">生日:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="member_birthday" id="member_birthday" class="form-control" value="">
						  </div>
						</div>
					  </div>			
					  <div class="form-group">
						<label class="form-label">地址:</label>
						<div class="row align-items-center">
						  <div class="col-8">
							<input type="text" name="member_address" id="member_address" class="form-control" value="">
						  </div>
						</div>
					  </div>
 					  <div class="form-group">
						<label class="form-label">電話:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="member_phone" id="member_phone" class="form-control" value="">
						  </div>
						</div>
					  </div>					  
					</td>
					<td colspan="2" valign="top">
					  <div class="form-group">
						<label class="form-label">店家名稱:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<select name="member_sid" id="member_sid" class="form-control custom-select">
							  <option value="0">--請選擇--</option>
							  <?php
								$sql3 = "select sid,store_name from store where store_trash=0 order by shopping_area,sid ";
								
								if ($result3 = mysqli_query($link, $sql3)){
									if (mysqli_num_rows($result3) > 0){
										$selectedflag = "";
										while($row3 = mysqli_fetch_array($result3)){
											if ($member_sid == strval($row3['sid'])) {
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
					  <!--<div class="form-group">
						<label class="form-label">特約店家代號:</label>
						<div class="row align-items-center">
						  <div class="col-sm-6">
							<input type="text" name="shopid" id="shopid" class="form-control" value="">
						  </div>
						</div>
					  </div>			
					  <div class="form-group">
						<label class="form-label">業務員代號:</label>
						<div class="row align-items-center">
						  <div class="col-sm-6">
							<input type="text" name="salesid" id="salesid" class="form-control" value="">
						  </div>
						</div>
					  </div> -->			
					  <div class="form-group">
						<label class="form-label">推薦碼:</label>
						<div class="row align-items-center">
						  <div class="col-sm-6">
							<input type="text" name="recommend_code" id="recommend_code" class="form-control" value="">
						  </div>
						</div>
					  </div>					
					</td>
				</tr>
				</table>
<?php if ($act == "Edit") { ?>				
					  <div class="form-group">
						<label class="form-label">總獲得紅利點數:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="point1" id="point1" disabled class="form-control" value="0">
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="form-label">已使用紅利點數:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="point2" id="point2" disabled class="form-control" value="0">
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="form-label">剩餘紅利點數:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="point3" id="point3" disabled class="form-control" value="0">
						  </div>
						</div>
					  </div>
<?php } ?>
					  <div class="form-group">
						<label class="form-label">會員啟用狀態:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
								<select name="member_status" id="member_status" class="form-control custom-select">
								  <option value="">--請選擇--</option>
								  <option value="1" selected >啟用</option>
								  <option value="0">停用</option>

								</select>
						  </div>
						</div>
					  </div>					  
					  <div class="card-footer text-center">
							<button type="button" class="btn btn-success ml-auto" onclick="Save_User()">儲存</button>&nbsp;&nbsp;<button type="button" class="btn btn-info ml-auto" name="backacc" onclick="GoAccount();">返回</button>
					  </div>		
                </div>
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

		if (!checkString(document.all.member_id,"請輸入帳號!"))	return false;
		//member_pwd
		if (!checkString(document.all.member_pwd,"請輸入密碼!"))	return false;
		if (!checkString(document.all.member_pwd2,"請輸入確認密碼!"))	return false;

		if (document.getElementById("member_pwd").value != document.getElementById("member_pwd2").value) {
			alert("確認密碼與密碼不一致!");
			document.getElementById("member_pwd2").focus(); 			
			return false;
		}
		if (!checkString(document.all.member_name,"請輸入會員名稱!"))	return false;
		
		if (document.getElementById("member_type").value == "") {
			alert("請選擇會員類型 !");
			document.getElementById("member_type").focus(); 			
			return false;
		}	
		
		if (document.getElementById("member_gender").value == "") {
			alert("請選擇性別 !");
			document.getElementById("member_gender").focus(); 			
			return false;
		}		
		//if (!checkString(document.all.member_email,"請輸入電子信箱!"))	return false;
		//if (!checkString(document.all.member_birthday,"請輸入生日!"))	return false;
		//if (!checkString(document.all.member_address,"請輸入地址!"))	return false;
		if (!checkString(document.all.member_phone,"請輸入電話!"))	return false;

		//if (!checkString(document.all.recommend_code,"請輸入推薦碼!"))	return false;
		
		if (document.getElementById("member_status").value == "") {
			alert("請選擇會員啟用狀態 !");
			document.getElementById("member_status").focus(); 			
			return false;
		}
		if ((document.getElementById("member_type").value == "2")&&(document.getElementById("member_sid").value == "0")) {
			alert("請選擇店家名稱 !");
			document.getElementById("member_sid").focus(); 			
			return false;
		}
		document.frm3.submit();
		mode = "0";
	}
	function GoAccount()
	{
			self.location.replace("member.php");
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
