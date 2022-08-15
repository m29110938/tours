<?php
session_start();
if ($_SESSION['accname']==""){
	header("Location: logout.php"); 
}
if (($_SESSION['authority']=="3")){
	header("Location: main.php"); 
}

include 'phpqrcode/qrlib.php';
  
	$act = isset($_POST['act']) ? $_POST['act'] : '';
	$tid = isset($_POST['tid']) ? $_POST['tid'] : '';
	//echo $tid;
	$host = 'localhost';
	$user = 'tours_user';
	$passwd = 'tours0115';
	$database = 'toursdb';
		
	$link = mysqli_connect($host, $user, $passwd, $database);
	mysqli_query($link,"SET NAMES 'utf8'");
	
	$sql = "SELECT * FROM store where store_trash=0 and sid=$tid";
	//echo $sql;
	//exit;

	if ($result = mysqli_query($link, $sql)){
		if (mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_array($result)){
				$store_id = $row['store_id'];
				$store_type = $row['store_type'];
				$store_name = $row['store_name'];
				$shopping_area = $row['shopping_area'];
				$store_phone = $row['store_phone'];
				$store_address = $row['store_address'];
				$store_website = $row['store_website'];
				$store_facebook = $row['store_facebook'];
				$store_descript = $row['store_descript'];
				$store_news = $row['store_news'];
				$store_opentime = $row['store_opentime'];
				$store_picture = $row['store_picture'];
				$store_longitude = $row['store_longitude'];
				$store_latitude = $row['store_latitude'];
				$store_status = $row['store_status'];
			}
		//mysqli_close($link);
		}else{
				$store_id = "";
				$store_type = "";
				$store_name = "";
				$shopping_area = "";
				$store_phone = "";
				$store_address = "";
				$store_website = "";
				$store_facebook = "";
				$store_descript = "";
				$store_news = "";
				$store_opentime = "";
				$store_picture = "";
				$store_longitude = "";
				$store_latitude = "";
				$store_status = "";
		}
	}	
	//$text = "https://coupon.jotangi.net:9443/app-store.php?tid=".$store_id;
	$text = "https://tripspot.jotangi.net/app-store.php?tid=".$store_id;
	//$text = "http://192.168.2.102/tours/app-store.php?tid=".$store_id;

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
			<a class="collapse-item active" href="store.php">店家設定</a>
			<a class="collapse-item" href="storemember.php">店家會員</a>
			<a class="collapse-item" href="push.php">推播訊息</a>
			<?php if ($_SESSION['authority']!="4"){  ?>
			<a class="collapse-item" href="storetype.php">類別設定</a>
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
			<h1 class="h5 mb-0 text-gray-800">店家設定</h1>
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
			<form action="storeedit.php" name="frm3" id="frm3" method="post">
			<input type="hidden" name="act" id="act"  value="<?php echo $act;?>"/>		
			<input type="hidden" name="tid" id="tid"  value="<?php echo $tid;?>"/>				  
			<input type="hidden" name="Dfilename" id="Dfilename"  value="<?php echo $store_picture;?>"/>
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">編輯店家資料</h6>
                </div>
                <div class="card-body">
 					  <div class="form-group">
						<label class="form-label">店家代碼:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="store_id" id="store_id" class="form-control" value="<?php echo $store_id;?>">
						  </div>
						</div>
					  </div>				
					  <div class="form-group">
						<label class="form-label">店家名稱:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="store_name" id="store_name" class="form-control" value="<?php echo $store_name;?>">
						  </div>
						</div>
					  </div>				
					  <div class="form-group">
						<label class="form-label">商圈分類:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
								<select name="shopping_area" id="shopping_area" class="form-control custom-select">
								  <option value="">--請選擇--</option>
								  <?php
									$sql3 = "select aid,shopping_area from shopping_area where shoppingarea_trash=0 order by shopping_area asc";
									
									if ($result3 = mysqli_query($link, $sql3)){
										if (mysqli_num_rows($result3) > 0){
											$selectedflag = "";
											while($row3 = mysqli_fetch_array($result3)){
												if ($shopping_area == strval($row3['aid'])) {
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
						<label class="form-label">店家分類:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
								<select name="store_type" id="store_type" class="form-control custom-select">
								  <option value="">--請選擇--</option>
								  <?php
									$sql2 = "select store_type,storetype_name from store_type where storetype_trash=0 order by store_type asc";
									
									if ($result2 = mysqli_query($link, $sql2)){
										if (mysqli_num_rows($result2) > 0){
											$selectedflag = "";
											while($row2 = mysqli_fetch_array($result2)){
												if ($store_type == strval($row2['store_type'])) {
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
						<label class="form-label">店家電話:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="store_phone" id="store_phone" class="form-control" value="<?php echo $store_phone;?>"/>
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="form-label">店家地址:</label>
						<div class="row align-items-center">
						  <div class="col-6">
							<input type="text" name="store_address" id="store_address" class="form-control" value="<?php echo $store_address;?>">
						  </div>
						</div>
					  </div>
				<table width="100%">
				<tr >
					<td width="30%">					  
					  <div class="form-group">
						<label class="form-label">店家封面圖案:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
					<?php if ($store_picture != "")  { ?>
							<img src="<?php echo $store_picture;?>" width="500"><br/><br/>
							<button type="button" id="del_map" name="del_map" onClick="DelRecord('<?php echo $tid; ?>','<?php echo $store_name; ?>');" class="btn btn-danger ml-auto">刪除</button>
							
 				    <?php }else{ ?>
							<img src="images/default_store.png" ><br/><br/>
 				    <?php } ?>
						  </div>
						</div>
					  </div>
					</td>
					<td width="10%">&nbsp;</td>
					<td width="40%" valign="top">
						<div class="form-group">
						<label class="form-label">店家QRCODE:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<?php
							try {
							
								$path = 'uploads/';
								$file = $path.$store_id.".png";
								  
								// $ecc stores error correction capability('L')
								$ecc = 'L';
								$pixel_size = 10;
								$frame_size = 10;
								QRcode::png($text, $file, $ecc, $pixel_size, $frame_size);
								//QRcode::png($text);
								// Displaying the stored QR code from directory
								echo "<center><img src='".$file."'></center>";
							} catch (Exception $e) {
								echo $e->getMessage();
							}

							?>						  
						  </div>
						</div>
						</div>						
						
					</td>
				</tr>
				</table>					  
					  <div class="form-group">
						<label class="form-label">店家網址:</label>
						<div class="row align-items-center">
						  <div class="col-6">
							<input type="text" name="store_website" id="store_website" class="form-control" value="<?php echo $store_website;?>">
						  </div>
						</div>
					  </div>			
					  <div class="form-group">
						<label class="form-label">店家FB:</label>
						<div class="row align-items-center">
						  <div class="col-6">
							<input type="text" name="store_facebook" id="store_facebook" class="form-control" value="<?php echo $store_facebook;?>">
						  </div>
						</div>
					  </div>					  <div class="form-group">
						<label class="form-label">店家介紹:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<textarea id="store_descript" name="store_descript" rows="4" cols="50"><?php echo $store_descript;?></textarea>							
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="form-label">店家最新消息:</label>
						<div class="row align-items-center">
						  <div class="col-6">
							<input type="text" name="store_news" id="store_news" class="form-control" value="<?php echo $store_news;?>">
						  </div>
						</div>
					  </div>					  
					  <div class="form-group">
						<label class="form-label">營業時間:</label>
						<div class="row align-items-center">
						  <div class="col-6">
							<input type="text" name="store_opentime" id="store_opentime" class="form-control" value="<?php echo $store_opentime;?>">
						  </div>
						</div>
					  </div>					  
					  <div class="form-group">
						<label class="form-label">店家啟用狀態:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
								<select name="store_status" id="store_status" class="form-control custom-select">
								  <option value="">--請選擇--</option>
								  <option value="0" <?php if ($store_status=="0") echo " selected"; ?>>啟用</option>
								  <option value="1" <?php if ($store_status=="1") echo " selected"; ?>>下架</option>

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
				<form action="uploadstorepic.php" name="frm2" id="frm2" method="post" enctype="multipart/form-data">
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
				document.frm3.action="storepicdel.php";	
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
	function validateMobile(sMobile) {
	  //var reEmail = /^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9](?:[a-zA-Z0-9\-](?!\.)){0,61}[a-zA-Z0-9]?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9\-](?!$)){0,61}[a-zA-Z0-9]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/;
	  var reMobile = /((?=(09))[0-9]{10})$/g;
	  if(!sMobile.match(reMobile)) {
		alert("輸入的 手機號碼 格式有誤!");
		return false;
	  }

	  return true;

	}		
	function Save_User(){
		if (!checkString(document.all.store_id,"請輸入店家代碼!"))	return false;
		if (document.getElementById("store_type").value == "") {
			alert("請選擇店家分類 !");
			document.getElementById("store_type").focus(); 			
			return false;
		}		

		if (!checkString(document.all.store_name,"請輸入店家名稱!"))	return false;
		if (document.getElementById("shopping_area").value == "0") {
			alert("請選擇商圈分類 !");
			document.getElementById("shopping_area").focus(); 			
			return false;
		}		
		if (!checkString(document.all.store_phone,"請輸入店家電話!"))	return false;
		if (!checkString(document.all.store_address,"請輸入店家地址!"))	return false;
		//if (!checkString(document.all.store_website,"請輸入店家網址!"))	return false;
		if (!checkString(document.all.store_descript,"請輸入店家介紹!"))	return false;
		if (!checkString(document.all.store_opentime,"請輸入營業時間!"))	return false;
		
		if (document.getElementById("store_status").value == "") {
			alert("請選擇店家啟用狀態 !");
			document.getElementById("store_status").focus(); 			
			return false;
		}
		document.frm3.submit();

		//if (validateMobile(document.getElementById("user_mobile").value)) {
		//	document.frm3.submit();
		//}else {
		//	return false;
		//}
		
		mode = "0";
	}	
	function GoAccount()
	{
			self.location.replace("store.php");
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
