<?php
session_start();
include("db_tools.php");

if ($_SESSION['accname']==""){
	header("Location: logout.php"); 
}
if (($_SESSION['authority']!="1")){
	header("Location: main.php"); 
}
$act = isset($_POST['act']) ? $_POST['act'] : '';

?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?=$htmltitle?></title>
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
		document.querySelector('#productphp').classList.add('active');
		document.querySelector('#cc').classList.add('active');
		document.querySelector('#c').classList.remove('collapsed');
		document.querySelector('#collapseUtilities1').classList.add('show');
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
			<h1 class="h5 mb-0 text-gray-800">商品維護</h1>
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
			<form action="productadd.php" name="frm3" id="frm3" method="post">
			<input type="hidden" name="act" id="act"  value="<?php echo $act;?>"/>
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">新增商品資料</h6>
                </div>
                <div class="card-body">
 					  <div class="form-group">
						<label class="form-label">商品代碼:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="product_no" id="product_no" class="form-control" value="">
						  </div>
						</div>
					  </div>				
					  <div class="form-group">
						<label class="form-label">商品名稱:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="product_name" id="product_name" class="form-control" value="">
						  </div>
						</div>
					  </div>				
					  <div class="form-group">
						<label class="form-label">商品分類:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
								<select name="product_type" id="product_type" class="form-control custom-select">
								  <option value="">--請選擇--</option>
								  <?php
							  
									$sql2 = "select pid,product_type,producttype_name from producttype where producttype_trash=0 order by product_type,producttype_name asc";
									
									if ($result2 = mysqli_query($link, $sql2)){
										if (mysqli_num_rows($result2) > 0){
											$selectedflag = "";
											while($row2 = mysqli_fetch_array($result2)){
												//if ($producttype == strval($row2['product_type'])) {
												//	$selectedflag = " selected ";
												//}else{
												//	$selectedflag = "";
												//}
												echo "<option value='".$row2['pid']."' ".$selectedflag." >".$row2['producttype_name']."</option>";
											}
										}
									}								
								  ?>
								</select>
						  </div>
						</div>
					  </div>					  
					  
					  <div class="form-group">
						<label class="form-label">商品售價:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="product_price" id="product_price" class="form-control" value=""/>
						  </div>
						</div>
					  </div>
					  <!--  
					  <div class="form-group">
						<label class="form-label">兌換點數:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="product_bonus" id="product_bonus" class="form-control" value=""/>
						  </div>
						</div>
					  </div>-->
					  <div class="form-group">
						<label class="form-label">庫存數量:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="product_stock" id="product_stock" class="form-control" value=""/>
						  </div>
						</div>
					  </div>

				<table width="100%">
				<tr >
					<td width="30%">					  
<?php if ($act == "Edit") {   //	member_picture ?>						
					  <div class="form-group">
						<label class="form-label">商品圖案:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<img src="images/coffee1.png" height="250">
							<button type="submit" class="btn btn-danger ml-auto">刪除</button>
						  </div>
						</div>
					  </div>
					</td>
<?php } ?>
					<td width="10%">&nbsp;</td>
					<td width="40%">
<?php if ($act == "Edit") {   //	member_picture ?>						
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
<?php } ?>
					</td>
				</tr>
				</table>					  
			
					  <div class="form-group">
						<label class="form-label">商品介紹:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<textarea id="product_description" name="product_description" rows="4" cols="50"></textarea>							
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="form-label">商品狀態:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
								<select name="product_status" id="product_status" class="form-control custom-select">
								  <option value="">--請選擇--</option>
								  <option value="1">上架</option>
								  <option value="0">下架</option>

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
			</form>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->
	<SCRIPT LANGUAGE=javascript>
	<!--
	function Save_User(){

		if (!checkString(document.all.product_no,"請輸入商品代碼!"))	return false;
		if (!checkString(document.all.product_name,"請輸入商品名稱!"))	return false;
		//if (!checkString(document.all.product_type,"請輸入商品分類!"))	return false;

		if (!checkString(document.all.product_price,"請輸入產品售價!"))	return false;
		if (!checkString(document.all.product_stock,"請輸入庫存數量!"))	return false;
		if (!checkString(document.all.product_description,"請輸入商品介紹!"))	return false;
		//if (!checkString(document.all.product_status,"請輸入商品狀態!"))	return false;
		
		document.frm3.submit();
		mode = "0";
	}	
	function GoAccount()
	{
			self.location.replace("product.php");
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
            <span>Copyright &copy; 2022 Jotangi Technology Co., Ltd</span>
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
