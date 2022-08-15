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
			//$text = "https://coupon.jotangi.net:9443/app-store.php?tid=".$store_id;
			$text = "https://tripspot.jotangi.net/app-store.php?tid=".$store_id;
			//$text = "http://192.168.2.102/tours/app-store.php?tid=".$store_id;
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
				$text = "";
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
			<a class="collapse-item active" href="store.php">店家設定</a>
			<a class="collapse-item" href="storemember.php">店家會員</a>
			<a class="collapse-item" href="push.php">推播訊息</a>
			<?php if ($_SESSION['authority']!="4"){  ?>
			<a class="collapse-item" href="storetype.php">類別設定</a>
			<a class="collapse-item" href="bonus.php">分潤設定</a>
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
						<label class="form-label">緯度:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="store_latitude" id="store_latitude" class="form-control" value="<?php echo $store_latitude;?>">
						  </div>
						</div>
					  </div>		
					  <div class="form-group">
						<label class="form-label">經度:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="store_longitude" id="store_longitude" class="form-control" value="<?php echo $store_longitude;?>">
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
						  <input type="file" class="custom-file-input" name="fileToUpload2" id="fileToUpload2" accept="image/jpeg" onchange="verificationPicFile(this)">
						  <label class="custom-file-label">選擇檔案</label>
						</div>
					</div>
					<div class="text-right">
					  <button type="button" class="btn btn-primary" onclick="Upload_Pic()" >上傳圖檔</button>
					</div>
				</form>	

            </div>
          </div>

          <div class="row">
			<table width='100%'>
			<tr>
			<td>
            <div class="col-lg-12 mb-4">
<?php
				$sql = "SELECT * FROM `timeperiod` where tid > 0 and store_id=$tid";
				//echo $sql;
				//exit;
				$w0 = "0";
				$w1 = "0";
				$w2 = "0";
				$w3 = "0";
				$w4 = "0";
				$w5 = "0";
				$w6 = "0";
				if ($result = mysqli_query($link, $sql)){
					if (mysqli_num_rows($result) > 0){
						while($row = mysqli_fetch_array($result)){
							$w0 = $row['w0'];
							$w1 = $row['w1'];
							$w2 = $row['w2'];
							$w3 = $row['w3'];
							$w4 = $row['w4'];
							$w5 = $row['w5'];
							$w6 = $row['w6'];
							break;
						}
					}
				}	
?>
              <!-- Illustrations -->
              <div class="card shadow mb-4">
			  <form action="timeperiodedit.php" name="frm7" id="frm7" method="post">
			  <input type="hidden" name="act3" id="act3"  value="<?php echo $act;?>"/>		
			  <input type="hidden" name="tid3" id="tid3"  value="<?php echo $tid;?>"/>				  
				<input type="hidden" name="tp1" id="tp1"  value=""/>
				<input type="hidden" name="tp2" id="tp2"  value=""/>
				<input type="hidden" name="tp3" id="tp3"  value=""/>
				<input type="hidden" name="tp4" id="tp4"  value=""/>
				<input type="hidden" name="tp5" id="tp5"  value=""/>
				<input type="hidden" name="tp6" id="tp6"  value=""/>
				<input type="hidden" name="tp0" id="tp0"  value=""/>
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">營業時間設定</h6>
                </div>
                <div class="card-body">
					<?php
					if ($w1 == "0") {
						$checkflag1 = "";
						$tp1[0]="10";
						$tp1[1]="18";
					}else{
						$checkflag1 = "checked";
						$tp1 = explode('-', $w1);
					}
					if ($w2 == "0") {
						$checkflag2 = "";
						$tp2[0]="10";
						$tp2[1]="18";
					}else{
						$checkflag2 = "checked";
						$tp2 = explode('-', $w2);
					}
					if ($w3 == "0") {
						$checkflag3 = "";
						$tp3[0]="10";
						$tp3[1]="18";
					}else{
						$checkflag3 = "checked";
						$tp3 = explode('-', $w3);
					}
					if ($w4 == "0") {
						$checkflag4 = "";
						$tp4[0]="10";
						$tp4[1]="18";
					}else{
						$checkflag4 = "checked";
						$tp4 = explode('-', $w4);
					}
					if ($w5 == "0") {
						$checkflag5 = "";
						$tp5[0]="10";
						$tp5[1]="18";
					}else{
						$checkflag5 = "checked";
						$tp5 = explode('-', $w5);
					}
					if ($w6 == "0") {
						$checkflag6 = "";
						$tp6[0]="10";
						$tp6[1]="18";
					}else{
						$checkflag6 = "checked";
						$tp6 = explode('-', $w6);
					}
					if ($w0 == "0") {
						$checkflag0 = "";
						$tp0[0]="10";
						$tp0[1]="18";
					}else{
						$checkflag0 = "checked";
						$tp0 = explode('-', $w0);
					}					
					?>
					  <div class="form-group">
						<input type="checkbox" name="w1" id="w1" value="1" <?php echo $checkflag1?> onClick="check"><label class="form-label">週一:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input class="text-input small-input" type="time" name="tps1" id="tps1" value="<?php if ($tp1[0] < 10) {echo "0".$tp1[0]; }else{ echo $tp1[0];}?>:00" /> ~ <input class="text-input small-input" type="time" name="tpe1" id="tpe1" value="<?php if ($tp1[1] < 10) {echo "0".$tp1[1]; }else{ echo $tp1[1];}?>:00" />
						  </div>
						</div>
					  </div>					
					  <div class="form-group">
						<input type="checkbox" name="w2" id="w2" value="1" <?php echo $checkflag2?>><label class="form-label">週二:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input class="text-input small-input" type="time" name="tps2" id="tps2" value="<?php if ($tp2[0] < 10) {echo "0".$tp2[0]; }else{ echo $tp2[0];}?>:00" /> ~ <input class="text-input small-input" type="time" name="tpe2" id="tpe2" value="<?php if ($tp2[1] < 10) {echo "0".$tp2[1]; }else{ echo $tp2[1];}?>:00" />
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<input type="checkbox" name="w3" id="w3" value="1" <?php echo $checkflag3?>><label class="form-label">週三:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input class="text-input small-input" type="time" name="tps3" id="tps3" value="<?php if ($tp3[0] < 10) {echo "0".$tp3[0]; }else{ echo $tp3[0];}?>:00" /> ~ <input class="text-input small-input" type="time" name="tpe3" id="tpe3" value="<?php if ($tp3[1] < 10) {echo "0".$tp3[1]; }else{ echo $tp3[1];}?>:00" />
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<input type="checkbox" name="w4" id="w4" value="1" <?php echo $checkflag4?>><label class="form-label">週四:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input class="text-input small-input" type="time" name="tps4" id="tps4" value="<?php if ($tp4[0] < 10) {echo "0".$tp4[0]; }else{ echo $tp4[0];}?>:00" /> ~ <input class="text-input small-input" type="time" name="tpe4" id="tpe4" value="<?php if ($tp4[1] < 10) {echo "0".$tp4[1]; }else{ echo $tp4[1];}?>:00" />
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<input type="checkbox" name="w5" id="w5" value="1" <?php echo $checkflag5?>><label class="form-label">週五:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input class="text-input small-input" type="time" name="tps5" id="tps5" value="<?php if ($tp5[0] < 10) {echo "0".$tp5[0]; }else{ echo $tp5[0];}?>:00" /> ~ <input class="text-input small-input" type="time" name="tpe5" id="tpe5" value="<?php if ($tp5[1] < 10) {echo "0".$tp5[1]; }else{ echo $tp5[1];}?>:00" />
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<input type="checkbox" name="w6" id="w6" value="1" <?php echo $checkflag6?>><label class="form-label">週六:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input class="text-input small-input" type="time" name="tps6" id="tps6" value="<?php if ($tp6[0] < 10) {echo "0".$tp6[0]; }else{ echo $tp6[0];}?>:00" /> ~ <input class="text-input small-input" type="time" name="tpe6" id="tpe6" value="<?php if ($tp6[1] < 10) {echo "0".$tp6[1]; }else{ echo $tp6[1];}?>:00" />
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<input type="checkbox" name="w0" id="w0" value="1" <?php echo $checkflag0?>><label class="form-label">週日:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input class="text-input small-input" type="time" name="tps0" id="tps0" value="<?php if ($tp0[0] < 10) {echo "0".$tp0[0]; }else{ echo $tp0[0];}?>:00" /> ~ <input class="text-input small-input" type="time" name="tpe0" id="tpe0" value="<?php if ($tp0[1] < 10) {echo "0".$tp0[1]; }else{ echo $tp0[1];}?>:00" />
						  </div>
						</div>
					  </div>
				  
					  <div class="card-footer text-center">
							<button type="button" class="btn btn-success ml-auto" onclick="Save_User2()">儲存</button>&nbsp;&nbsp;
					  </div>		
                </div>
			  </form>
              </div>
			  </div>
			</td>
			<td valign=top>
 
            <div class="col-lg-12 mb-4" id='list_holiday'>

              <!-- Illustrations -->
              <div class="card shadow mb-4">
			  <form action="" name="frm8" id="frm8" method="post">
			  <input type="hidden" name="act8" id="act8"  value="<?php echo $act;?>"/>		
			  <input type="hidden" name="tid8" id="tid8"  value="<?php echo $tid;?>"/>	
			  <input type="hidden" name="wid" id="wid"  value=""/>			  
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">公休時間&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-primary ml-auto" onclick='show_holiday(0);'>新增</button></h6> 
                </div>
                <div class="card-body">
<?php				
				$sql = "SELECT * FROM `holiday` where wid > 0 and store_id=$tid order by holiday";
				//echo $sql;
				//exit;

				if ($result = mysqli_query($link, $sql)){
					if (mysqli_num_rows($result) > 0){
						while($row = mysqli_fetch_array($result)){
							$wid = $row['wid'];
							$holiday = $row['holiday'];
							$close_reason = $row['close_reason'];
							//	close_reason
							//
						  echo "<div class='form-group'>";
						  echo "	<label class='form-label'>".$close_reason."</label>";
						  echo "	<div class='row align-items-center'>";
						  echo "	  <div class='col-auto'>";
						  echo "		<input class='text-input small-input' type='date' name='wdate$wid' id='wdate$wid' disabled value=".$holiday." /> <button type='button' class='btn btn-danger ml-auto' onclick='GoDel($wid)'>刪除</button>";
						  echo "	  </div>";
						  echo "	</div>";
						  echo "  </div>";
					  
						}
					//mysqli_close($link);
					}
				}	
?>	
					
				  
					  <!--<div class="card-footer text-center">
							<button type="button" class="btn btn-success ml-auto" onclick="Save_User3()">儲存</button>
					  </div>-->
                </div>
			  </form>
              </div>
            </div>
			
			  <div class="row" id='add_holiday' style='display: none;'>

				<div class="col-lg-12 mb-4">

				  <!-- Illustrations -->
				  <div class="card shadow mb-4">
				<form action="" name="frm6" id="frm6" method="post">
				<input type="hidden" name="act6" id="act6"  value="<?php echo $act;?>"/>	
				<input type="hidden" name="tid6" id="tid6"  value="<?php echo $tid;?>"/>				
					<div class="card-header py-3">
					  <h6 class="m-0 font-weight-bold text-primary">新增公休時間資料</h6>
					</div>
					<div class="card-body">
						  <div class="form-group">
							<label class="form-label">公休日:</label>
							<div class="row align-items-center">
							  <div class="col-auto">
								<input class="text-input small-input" type="date" name="holiday" id="holiday" value="" />
							  </div>
							</div>
						  </div>
				  
						  <div class="form-group">
							<label class="form-label">公休說明:</label>
							<div class="row align-items-center">
							  <div class="col-auto">
								<input type="text" name="close_reason" id="close_reason" class="form-control" value=""/>
							  </div>
							</div>
						  </div>
			  
						  <div class="card-footer text-center">
								<button type="button" class="btn btn-success ml-auto" onclick="Save_User3()">儲存</button>&nbsp;&nbsp;<button type="button" class="btn btn-info ml-auto" name="backacc" onclick="show_holiday(1);">返回</button>
						  </div>		
					</div>
				</form>
				  </div>
				</div>
			  </div>
		  
			</td>
			</tr>
			</table>

            </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->
	<SCRIPT LANGUAGE=javascript>
	<!--
	function GoDel(id)
	{	
		if (confirm('確定要刪除這筆資料嗎?')) {

			document.getElementById("wid").value = id;
			//alert(id);
			document.frm8.action="holidaydel.php";	
			document.frm8.submit();
		}
	}		
	//圖片大小驗證
	function verificationPicFile(file) {
		var fileSize = 0;
		var fileMaxSize = 1024;//1M
		var filePath = file.value;
		if(filePath){
			fileSize =file.files[0].size;
			var size = fileSize / 1024;
			if (size > fileMaxSize) {
				alert("檔案大小不能大於1M！");
				file.value = "";
				return false;
			}else if (size <= 0) {
				alert("檔案大小不能為0M！");
				file.value = "";
				return false;
			}
		}else{
			return false;
		}
	}
	function show_holiday(id)
	{
		//alert(id);
		if (id == 0) {
			//顯示TR
			//alert("show");
			var result_style = document.getElementById('list_holiday').style;
			result_style.display = 'none';
			var holiday_style = document.getElementById('add_holiday').style;
			holiday_style.display = 'block';
			document.getElementById("act1").value = 'Add';
			//document.getElementById("tid1").value = '';			
		} else {
			//隱藏TR
			//alert("hide");
			var result_style = document.getElementById('list_holiday').style;
			result_style.display = 'block';
			var holiday_style = document.getElementById('add_holiday').style;
			holiday_style.display = 'none';
		}
	}	
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
		if (!checkString(document.all.store_latitude,"請輸入店家緯度!"))	return false;
		if (!checkString(document.all.store_longitude,"請輸入店家精度!"))	return false;
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
	}	
	function Save_User2(){
		if (document.getElementById("w1").checked == true){
			var w1=parseInt(document.getElementById("tps1").value.substring(0, 2))+'-'+parseInt(document.getElementById("tpe1").value.substring(0, 2));
			document.getElementById("tp1").value = w1;
		}else{
			var w1=0;
			document.getElementById("tp1").value = w1;
		}
		if (document.getElementById("w2").checked == true){
			var w2=parseInt(document.getElementById("tps2").value.substring(0, 2))+'-'+parseInt(document.getElementById("tpe2").value.substring(0, 2));
			document.getElementById("tp2").value = w2;
		}else{
			var w2=0;
			document.getElementById("tp2").value = w2;
		}
		if (document.getElementById("w3").checked == true){
			var w3=parseInt(document.getElementById("tps3").value.substring(0, 2))+'-'+parseInt(document.getElementById("tpe3").value.substring(0, 2));
			document.getElementById("tp3").value = w3;
		}else{
			var w3=0;
			document.getElementById("tp3").value = w3;
		}
		if (document.getElementById("w4").checked == true){
			var w4=parseInt(document.getElementById("tps4").value.substring(0, 2))+'-'+parseInt(document.getElementById("tpe4").value.substring(0, 2));
			document.getElementById("tp4").value = w4;
		}else{
			var w4=0;
			document.getElementById("tp4").value = w4;
		}
		if (document.getElementById("w5").checked == true){
			var w5=parseInt(document.getElementById("tps5").value.substring(0, 2))+'-'+parseInt(document.getElementById("tpe5").value.substring(0, 2));
			document.getElementById("tp5").value = w5;
		}else{
			var w5=0;
			document.getElementById("tp5").value = w5;
		}
		if (document.getElementById("w6").checked == true){
			var w6=parseInt(document.getElementById("tps6").value.substring(0, 2))+'-'+parseInt(document.getElementById("tpe6").value.substring(0, 2));
			document.getElementById("tp6").value = w6;
		}else{
			var w6=0;
			document.getElementById("tp6").value = w6;
		}	
		if (document.getElementById("w0").checked == true){
			var w0=parseInt(document.getElementById("tps0").value.substring(0, 2))+'-'+parseInt(document.getElementById("tpe0").value.substring(0, 2));
			document.getElementById("tp0").value = w0;
		}else{
			var w0=0;
			document.getElementById("tp0").value = w0;
		}		
		document.frm7.submit();
		alert("資料儲存完畢!");
		
	}
	function Save_User3(){

		if (document.getElementById("holiday").value == "") {
			alert("請選擇公休日期 !");
			document.getElementById("holiday").focus(); 			
			return false;
		}		

		if (!checkString(document.all.close_reason,"請輸入公休說明!"))	return false;
		
		document.frm6.action="holidayadd.php";
		document.frm6.submit();
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
