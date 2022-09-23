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
    <?php
    include("sidebar.php"); 
	?>
	<script>
		document.querySelector('#attractionphp').classList.add('active');
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
			<h1 class="h5 mb-0 text-gray-800">景點設定</h1>
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
			<form action="attractionadd.php" name="frm3" id="frm3" method="post">
			<input type="hidden" name="act" id="act"  value="<?php echo $act;?>"/>				  
			  
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">新增景點資料</h6>
                </div>
                <div class="card-body">
 					  <div class="form-group">
						<label class="form-label">景點代碼:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="store_id" id="store_id" class="form-control" value="">
						  </div>
						</div>
					  </div>	
					  <div class="form-group">
						<label class="form-label">景點分類:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<select name="store_type" id="store_type" class="form-control custom-select">
							  <option value="">--選擇--</option>
							  <?php
								$sql2 = "select store_type,storetype_name from attraction_type where storetype_trash=0 order by store_type asc";
								
								if ($result2 = mysqli_query($link, $sql2)){
									if (mysqli_num_rows($result2) > 0){
										$selectedflag = "";
										while($row2 = mysqli_fetch_array($result2)){
											if ($storetype == strval($row2['store_type'])) {
												$selectedflag = " selected ";
											}else{
												$selectedflag = "";
											}
											echo "<option value='".$row2['store_type']."' ".$selectedflag." >".$row2['storetype_name']."</option>";
										}
									}
								}								
							  ?>
							</select>
						  </div>
						</div>
					  </div>					  
					  <div class="form-group">
						<label class="form-label">景點名稱:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="store_name" id="store_name" class="form-control" value="">
						  </div>
						</div>
					  </div>				
					  <div class="form-group">
						<label class="form-label">商圈分類:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<select name="shopping_area" id="shopping_area" class="form-control custom-select">
							  <option value="0">--請選擇--</option>
							  <?php
								$sql3 = "select aid,shopping_area from shopping_area where shoppingarea_trash=0 order by shopping_area asc";
								
								if ($result3 = mysqli_query($link, $sql3)){
									if (mysqli_num_rows($result3) > 0){
										$selectedflag = "";
										while($row3 = mysqli_fetch_array($result3)){
											if ($shopping_area == strval($row3['shopping_area'])) {
												$selectedflag = " selected ";
											}else{
												$selectedflag = "";
											}
											echo "<option value='".$row3['aid']."' ".$selectedflag." >".$row3['shopping_area']."</option>";
										}
									}
								}								
							  ?>
							</select>
						  </div>
						</div>
					  </div>					  
					  <div class="form-group">
						<label class="form-label">景點電話:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="store_phone" id="store_phone" class="form-control" value=""/>
						  </div>
						</div>
					  </div>

					  <div class="form-group">
						<label class="form-label">景點地址:</label>
						<div class="row align-items-center">
						  <div class="col-6">
							<input type="text" name="store_address" id="store_address" class="form-control" value="">
						  </div>
						</div>
					  </div>
					  <!--<div class="form-group">
						<label class="form-label">經度:</label>
						<div class="row align-items-center">
						  <div class="col-6">
							<input type="text" name="store_longitude" id="store_longitude" class="form-control" value="">
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="form-label">緯度:</label>
						<div class="row align-items-center">
						  <div class="col-6">
							<input type="text" name="store_latitude" id="store_latitude" class="form-control" value="">
						  </div>
						</div>
					  </div>	-->				  
<?php if ($act == "Edit") {   //	member_picture ?>						
				<table width="100%">
				<tr >
					<td width="30%">					  
					  <div class="form-group">
						<label class="form-label">景點封面圖案:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<img src="images/coupon1.png" height="250">
							<button type="submit" class="btn btn-danger ml-auto">刪除</button>
						  </div>
						</div>
					  </div>
					</td>
					<td width="10%">&nbsp;</td>
					<td width="40%">
						<form action="" name="frm2" id="frm2" method="post" enctype="multipart/form-data">
							<div class="form-group">
								<div class="form-label">圖檔上傳:</div>
								<div class="custom-file">
								  <input type="file" class="custom-file-input" name="fileToUpload" id="fileToUpload">
								  <label class="custom-file-label">選擇檔案</label>
								</div>
							</div>
							<div class="text-right">
							  <button type="submit" class="btn btn-primary">上傳圖檔</button>
							</div>
						</form>					
					</td>
				</tr>
				</table>					  
<?php } ?>
					  <div class="form-group">
						<label class="form-label">景點網址:</label>
						<div class="row align-items-center">
						  <div class="col-6">
							<input type="text" name="store_website" id="store_website" class="form-control" value="">
						  </div>
						</div>
					  </div>			
					  <div class="form-group">
						<label class="form-label">景點FB:</label>
						<div class="row align-items-center">
						  <div class="col-6">
							<input type="text" name="store_facebook" id="store_facebook" class="form-control" value="">
						  </div>
						</div>
					  </div>			
					  <div class="form-group">
						<label class="form-label">景點介紹:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<textarea id="store_descript" name="store_descript" rows="4" cols="50"></textarea>							
						  </div>
						</div>
					  </div>
					  <!-- <div class="form-group">
						<label class="form-label">景點最新消息:</label>
						<div class="row align-items-center">
						  <div class="col-6">
							<input type="text" name="store_news" id="store_news" class="form-control" value="">
						  </div>
						</div>
					  </div> -->
					  <div class="form-group">
						<label class="form-label">營業時間:</label>
						<div class="row align-items-center">
						  <div class="col-6">
							<input type="text" name="store_opentime" id="store_opentime" class="form-control" value="">
						  </div>
						</div>
					  </div>			
					  <div class="form-group">
						<label class="form-label">景點啟用狀態:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
								<select name="store_status" id="store_status" class="form-control custom-select">
								  <option value="">--請選擇--</option>
								  <option value="0">啟用</option>
								  <option value="1">下架</option>

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
	function Save_User(){

		if (!checkString(document.all.store_id,"請輸入景點代碼!"))	return false;
		if (document.getElementById("store_type").value == "") {
			alert("請選擇景點分類 !");
			document.getElementById("store_type").focus(); 			
			return false;
		}		
		if (!checkString(document.all.store_name,"請輸入景點名稱!"))	return false;
		if (document.getElementById("shopping_area").value == "0") {
			alert("請選擇商圈分類 !");
			document.getElementById("shopping_area").focus(); 			
			return false;
		}		
		// if (!checkString(document.all.store_phone,"請輸入景點電話!"))	return false;
		if (!checkString(document.all.store_address,"請輸入景點地址!"))	return false;
		//if (!checkString(document.all.store_website,"請輸入景點網址!"))	return false;
		if (!checkString(document.all.store_descript,"請輸入景點介紹!"))	return false;
		if (!checkString(document.all.store_opentime,"請輸入營業時間!"))	return false;
		if (document.getElementById("store_status").value == "") {
			alert("請選擇景點啟用狀態 !");
			document.getElementById("store_status").focus(); 			
			return false;
		}
		document.frm3.submit();
		mode = "0";
	}	
	function GoAccount()
	{
			self.location.replace("attraction.php");
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