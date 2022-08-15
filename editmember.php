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
	
	$sql = "SELECT * FROM member where member_trash=0 and mid=$tid";
	//echo $sql;
	//exit;

	if ($result = mysqli_query($link, $sql)){
		if (mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_array($result)){
				$member_id = $row['member_id'];
				$member_pwd = $row['member_pwd'];
				$member_name = $row['member_name'];
				$member_type = $row['member_type'];
				$member_gender = $row['member_gender'];
				$member_email = $row['member_email'];

				$member_birthday = $row['member_birthday'];
				$member_address = $row['member_address'];
				$member_phone = $row['member_phone'];
				$member_picture = $row['member_picture'];
				$member_totalpoints = $row['member_totalpoints'];
				$member_usingpoints = $row['member_usingpoints'];

				$member_status = $row['member_status'];
				$recommend_code = $row['recommend_code'];
				$member_sid = $row['member_sid'];
				
			}
		//mysqli_close($link);
		}else{
				$member_id = "";
				$member_pwd = "";
				$member_name = "";
				$member_type = "";
				$member_gender = "";
				$member_email = "";

				$member_birthday = "";
				$member_address = "";
				$member_phone = "";
				$member_picture = "";
				$member_totalpoints = "";
				$member_usingpoints = "";				
				 
				$member_status = "";
				$recommend_code = "";	
				$member_sid = "";				
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
      <li class="nav-item active">
		<a class="nav-link " href="#" data-toggle="collapse" data-target="#collapseUtilities0" aria-expanded="true" aria-controls="collapseUtilities0">
		  <i class="fas fa-fw fa-address-card"></i>
		  <span>會員中心</span>
		</a>	  
		<div id="collapseUtilities0" class="collapse show" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
		  <div class="bg-white py-2 collapse-inner rounded">
			<a class="collapse-item active" href="member.php">會員管理</a>
			<a class="collapse-item" href="mycoupon.php">票券列表</a>
			<a class="collapse-item" href="mybonus.php">紅利點數列表</a>
		  </div>
		</div>		  
      </li>	  
	  <?php } ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-fw fa-coffee"></i>
          <span>商圈管理</span>
        </a>	  
		<div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
		  <div class="bg-white py-2 collapse-inner rounded">
			<a class="collapse-item" href="store.php">店家設定</a>
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
			<form action="memberedit.php" name="frm3" id="frm3" method="post">
			<input type="hidden" name="act" id="act"  value="<?php echo $act;?>"/>		
			<input type="hidden" name="tid" id="tid"  value="<?php echo $tid;?>"/>
			<input type="hidden" name="Dfilename" id="Dfilename"  value="<?php echo $member_picture;?>"/>
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">修改會員資料</h6>
                </div>
                <div class="card-body">
				<table width="100%">
				<tr >
					<td width="50%">
					  <div class="form-group">
						<label class="form-label">會員名稱:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="member_name" id="member_name" class="form-control" value="<?php echo $member_name;?>">
						  </div>
						</div>
					  </div>				
 					  <div class="form-group">
						<label class="form-label">帳號:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="member_id" id="member_id" class="form-control" value="<?php echo $member_id;?>">
						  </div>
						</div>
					  </div>					  
					  
					  <div class="form-group">
						<label class="form-label">密碼:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="password" name="member_pwd" id="member_pwd" class="form-control" value="<?php echo $member_pwd;?>"/>
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
					</td>
					<td width="50%">
						<div>照片:</div>
						<div><!--<img src="images/default.png" width="320"><button type="button" class="btn btn-success ml-auto">更換照片</button>-->
					<?php if ($member_picture != "")  { ?>
							<img src="<?php echo $member_picture;?>" width="240" ><br/><br/>
							<button type="button" id="del_map" name="del_map" onClick="DelRecord('<?php echo $tid; ?>','<?php echo $member_name; ?>');" class="btn btn-danger ml-auto">刪除</button>
 				    <?php }else{ ?>
							<img src="images/default.png" width="240"><br/>
 				    <?php } ?>
						</div>
						<br/>
						<div>
						
						</div>
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
								  <option value="1" <?php if ($member_type=="1") echo "selected"; ?>>一般會員</option>
								  <option value="2" <?php if ($member_type=="2") echo "selected"; ?>>特約店家</option>
								  <option value="3" <?php if ($member_type=="3") echo "selected"; ?>>特約旅行社</option>
								  <option value="4" <?php if ($member_type=="4") echo "selected"; ?>>導遊</option>
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
								  <option value="0" <?php if ($member_gender=="0") echo "selected"; ?>>女</option>
								  <option value="1" <?php if ($member_gender=="1") echo "selected"; ?>>男</option>

								</select>
						  </div>
						</div>
					  </div>					  
					  <div class="form-group">
						<label class="form-label">電子信箱:</label>
						<div class="row align-items-center">
						  <div class="col-8">
							<input type="text" name="member_email" id="member_email" class="form-control" value="<?php echo $member_email;?>"/>
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="form-label">生日:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="member_birthday" id="member_birthday" class="form-control" value="<?php echo $member_birthday;?>">
						  </div>
						</div>
					  </div>			
					  <div class="form-group">
						<label class="form-label">地址:</label>
						<div class="row align-items-center">
						  <div class="col-8">
							<input type="text" name="member_address" id="member_address" class="form-control" value="<?php echo $member_address;?>">
						  </div>
						</div>
					  </div>
 					  <div class="form-group">
						<label class="form-label">電話:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="member_phone" id="member_phone" class="form-control" value="<?php echo $member_phone;?>">
						  </div>
						</div>
					  </div>					  
					</td>
					<td valign="top">
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
							<input type="text" name="store_id" id="store_id" class="form-control" value="">
						  </div>
						</div>
					  </div>			
					  <div class="form-group">
						<label class="form-label">業務員代號:</label>
						<div class="row align-items-center">
						  <div class="col-sm-6">
							<input type="text" name="sales_id" id="sales_id" class="form-control" value="">
						  </div>
						</div>
					  </div>	-->				
					  <div class="form-group">
						<label class="form-label">推薦碼:</label>
						<div class="row align-items-center">
						  <div class="col-sm-6">
							<input type="text" name="recommend_code" id="recommend_code" class="form-control" value="<?php echo $recommend_code;?>">
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
							<input type="text" name="member_totalpoints" id="member_totalpoints" disabled class="form-control" value="<?php echo $member_totalpoints;?>">
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="form-label">已使用紅利點數:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="member_usingpoints" id="member_usingpoints" disabled class="form-control" value="<?php echo $member_usingpoints;?>">
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="form-label">剩餘紅利點數:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="points" id="points" disabled class="form-control" value="<?php echo ($member_totalpoints - $member_usingpoints) ;?>">
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
								  <option value="1" <?php if ($member_status=="1") echo "selected"; ?> >啟用</option>
								  <option value="0" <?php if ($member_status=="0") echo "selected"; ?> >停用</option>

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
				<form action="uploadmemberpic.php" name="frm2" id="frm2" method="post" enctype="multipart/form-data">
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
				document.frm3.action="memberpicdel.php";	
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

		if (!checkString(document.all.member_name,"請輸入會員名稱!"))	return false;
		if (!checkString(document.all.member_id,"請輸入帳號!"))	return false;
		//member_pwd
		if (!checkString(document.all.member_pwd,"請輸入密碼!"))	return false;
		if (!checkString(document.all.member_pwd2,"請輸入確認密碼!"))	return false;

		if (document.getElementById("member_pwd").value != document.getElementById("member_pwd2").value) {
			alert("確認密碼與密碼不一致!");
			document.getElementById("member_pwd2").focus(); 			
			return false;
		}
		
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
			alert("請輸入會員啟用狀態 !");
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
