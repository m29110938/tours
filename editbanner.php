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
	
	$sql = "SELECT * FROM banner where banner_trash=0 and bid=$tid";
	//echo $sql;
	//exit;
	//user_id, user_mail  user_password, user_name, user_city, user_country, bluetooth_ver, phone_model, RCE_member, FB_Account,user_created_at,user_trash

	if ($result = mysqli_query($link, $sql)){
		if (mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_array($result)){
				$banner_subject = $row['banner_subject'];
				$banner_descript = $row['banner_descript'];
				$banner_date = $row['banner_date'];
				$banner_enddate = $row['banner_enddate'];
				$banner_link = $row['banner_link'];
				$banner_picture = $row['banner_picture'];
			}
		mysqli_close($link);
		}else{
				$banner_subject = "";
				$banner_descript = "";
				$banner_date = "";
				$banner_enddate = "";
				$banner_link = "";
				$banner_picture = "";
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
		document.querySelector('#bannerphp').classList.add('active');
		document.querySelector('#gg').classList.add('active');
		document.querySelector('#g').classList.remove('collapsed');
		document.querySelector('#collapseUtilities4').classList.add('show');
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
			<h1 class="h5 mb-0 text-gray-800">Banner管理設定</h1>
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
			<form action="banneredit.php" name="frm3" id="frm3" method="post">
			<input type="hidden" name="act" id="act"  value="<?php echo $act;?>"/>		
			<input type="hidden" name="tid" id="tid"  value="<?php echo $tid;?>"/>				  
			<input type="hidden" name="Dfilename" id="Dfilename"  value="<?php echo $banner_picture;?>"/>

                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">編輯Banner</h6>
                </div>
                <div class="card-body">
				<table width="100%">
				<tr >
				
					<td width="30%">
					  <div class="form-group">
						<label class="form-label">標題:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="banner_subject" id="banner_subject" class="form-control" value="<?php echo $banner_subject;?>">
						  </div>
						</div>
					  </div>	
				
					  <div class="form-group">
						<label class="form-label">內容說明:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<textarea id="banner_descript" name="banner_descript" rows="4" cols="50"><?php echo $banner_descript;?></textarea>							
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="form-label">發布日期:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							  <input class="text-input small-input" type="date" name="banner_date" id="banner_date" value="<?php echo $banner_date;?>" />
						  </div>
						</div>
					  </div>		
					  <div class="form-group">
						<label class="form-label">有效期限:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							  <input class="text-input small-input" type="date" name="banner_enddate" id="banner_enddate" value="<?php echo $banner_enddate;?>" />
						  </div>
						</div>
					  </div>	
					  <div class="form-group">
						<label class="form-label">連結網址:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="banner_link" id="banner_link" class="form-control" value="<?php echo $banner_link;?>">
						  </div>
						</div>
					  </div>					  
					</td>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>
					  <div class="form-group">
						<label class="form-label">Banner圖案:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
					<?php if ($banner_picture != "")  { ?>
							<img src="<?php echo $banner_picture;?>" ><br/><br/>
							<button type="button" id="del_map" name="del_map" onClick="DelRecord('<?php echo $tid; ?>','<?php echo $banner_subject; ?>');" class="btn btn-danger ml-auto">刪除</button>
							
 				    <?php }else{ ?>
							<img src="images/default_AD.png" ><br/><br/>
 				    <?php } ?>
						  </div>
						</div>
					  </div>
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
				<form action="uploadbannerpic.php" name="frm2" id="frm2" method="post" enctype="multipart/form-data">
				<input type="hidden" class="form-control" id="tid2" name="tid2" value="<?php echo $tid;?>">
					<div class="form-group">
						<div class="form-label">圖檔上傳:</div>
						<div class="custom-file">
						  <input type="file" class="custom-file-input" name="fileToUpload2" id="fileToUpload2">
						  <label class="custom-file-label">選擇檔案</label>
						</div>
					</div>
					<div class="text-right">
						<button type="button" class="btn btn-primary" onclick="Upload_Pic()" >上傳圖檔</button>
					</div>
				</form>	
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->
	<SCRIPT LANGUAGE=javascript>
	<!--
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

		if (!checkString(document.all.banner_subject,"請輸入標題!"))	return false;
		if (!checkString(document.all.banner_descript,"請輸入內容說明!"))	return false;
		if (!checkString(document.all.banner_date,"請輸入發布日期!"))	return false;
		if (!checkString(document.all.banner_enddate,"請輸入有效期限!"))	return false;

		if (document.all.banner_enddate.value <= document.all.banner_date.value) {
			alert("有效期限小於等於發布日期!");
			document.all.banner_enddate.focus();
			return false;
		}
		//if (!checkString(document.all.banner_link,"請輸入連結網址!"))	return false;
		
		document.frm3.submit();
		mode = "0";
	}		
	function GoAccount()
	{
			self.location.replace("banner.php");
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
