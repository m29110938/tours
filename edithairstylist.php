<?php
session_start();
if ($_SESSION['accname']==""){
	header("Location: logout.php"); 
}
if (($_SESSION['authority']=="3")){
	header("Location: main.php"); 
}
  
	$act = isset($_POST['act']) ? $_POST['act'] : '';
	$tid = isset($_POST['tid']) ? $_POST['tid'] : '0';
	//echo $tid;
	$host = 'localhost';
	$user = 'tours_user';
	$passwd = 'tours0115';
	$database = 'toursdb';
		
	$link = mysqli_connect($host, $user, $passwd, $database);
	mysqli_query($link,"SET NAMES 'utf8'");
	
	$sql = "SELECT * FROM hairstylist where hairstylist_trash=0 and hid=$tid";
	//echo $sql;
	//exit;

	if ($result = mysqli_query($link, $sql)){
		if (mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_array($result)){
				$store_id = $row['store_id'];
				$nick_name = $row['nick_name'];
				$service_code = $row['service_code'];
				$hairstylist_date = date('Y-m-d', strtotime($row['hairstylist_date']));  //$row['hairstylist_date'];
				$hairstylist_status = $row['hairstylist_status'];
				$stylist_pic = $row['stylist_pic'];
			}
		//mysqli_close($link);
		}else{
				$store_id = "";
				$nick_name = "";
				$service_code = "";
				$hairstylist_date = "";
				$hairstylist_status = "";
				$stylist_pic = "";
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
			<a class="collapse-item" href="hairservice.php">服務項目管理</a>
			<a class="collapse-item" href="hairstylist.php">員工資料管理</a>
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
			<h1 class="h5 mb-0 text-gray-800">員工資料管理</h1>
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
			<form action="hairstylistedit.php" name="frm3" id="frm3" method="post">
			<input type="hidden" name="act" id="act"  value="<?php echo $act;?>"/>		
			<input type="hidden" name="tid" id="tid"  value="<?php echo $tid;?>"/>				  
			<input type="hidden" name="Dfilename" id="Dfilename"  value="<?php echo $stylist_pic;?>"/>
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">編輯員工資料</h6>
                </div>
                <div class="card-body">
					  <div class="form-group">
						<label class="form-label">店家:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<!--<input type="text" name="coupon_storeid" id="coupon_storeid" class="form-control" value=""/>-->
								<select name="store_id" id="store_id" class="form-control custom-select">
								  <option value="">--請選擇--</option>
								  <?php
									$sql3 = "select sid,store_name from store where store_trash=0 and store_type=1";
									if ($_SESSION['authority']=="4"){
										$sql3 = $sql3." and sid=".$_SESSION['loginsid']."";
									}
									$sql3 = $sql3." order by shopping_area,sid ";									
									if ($result3 = mysqli_query($link, $sql3)){
										if (mysqli_num_rows($result3) > 0){
											$selectedflag = "";
											while($row3 = mysqli_fetch_array($result3)){
												if ($store_id == strval($row3['sid'])) {
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
					  <div class="form-group">
						<label class="form-label">設計師名稱:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="nick_name" id="nick_name" class="form-control" value="<?php echo $nick_name;?>">
						  </div>
						</div>
					  </div>				
				<table width="100%">
				<tr >
					<td width="30%">					  
					  <div class="form-group">
						<label class="form-label">設計師照片:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
					<?php if ($stylist_pic != "")  { ?>
							<img src="<?php echo $stylist_pic;?>" width='200'><br/><br/>
							<button type="button" id="del_map" name="del_map" onClick="DelRecord('<?php echo $tid; ?>','<?php echo $nick_name; ?>');" class="btn btn-danger ml-auto">刪除</button>
							
 				    <?php }else{ ?>
							<img src="images/default_hairstylist.png" ><br/><br/>
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
					  <div class="form-group">
						<label class="form-label">服務項目:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" name="service_code" id="service_code" class="form-control" value="<?php echo $service_code;?>"/>
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="form-label">到職日期:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<!--<input type="text" name="hairstylist_date" id="hairstylist_date" class="form-control" value="<?php echo $hairstylist_date;?>">-->
							<input class="text-input small-input" type="date" name="hairstylist_date" id="hairstylist_date" value="<?php echo $hairstylist_date;?>" />
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="form-label">員工狀態:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
								<select name="hairstylist_status" id="hairstylist_status" class="form-control custom-select">
								  <option value="">--請選擇--</option>
								  <option value="0" <?php if ($hairstylist_status=="0") echo " selected"; ?>>在職</option>
								  <option value="1" <?php if ($hairstylist_status=="1") echo " selected"; ?>>離職</option>

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
				<form action="uploadhairstylistpic.php" name="frm2" id="frm2" method="post" enctype="multipart/form-data">
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

          <div class="row" id='list_vacation'>

            <div class="col-lg-12 mb-4">

              <!-- Illustrations -->
              <div class="card shadow mb-4">
			  <form action="vacationdel.php" name="frm5" id="frm5" method="post">
			  <input type="hidden" name="act5" id="act5"  value=""/>		
			  <input type="hidden" name="tid5" id="tid5"  value="<?php echo $tid;?>"/>				  
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">休假日 &nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-primary ml-auto" onclick='show_vacation(0);'>新增</button></h6>
                </div>
                <div class="card-body">
              <div class="table-responsive">
				<?php
				//$act = isset($_POST['act']) ? $_POST['act'] : '';
				$havedata = 0;
				//if ($act == 'Qry') {
				
					$sql = "SELECT * FROM vacation where vid > 0 and hid=$tid";
					$sql = $sql." order by hid,vacation,vacation_name,vacation_period ";
					//echo $sql;
					//exit;

					$idx = 0;
					if ($result = mysqli_query($link, $sql)){
						if (mysqli_num_rows($result) > 0){
							echo "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>";
							echo "  <thead>";
							echo "    <tr>";
							echo "	  <th>休假日期</th>";
							echo "	  <th>假別</th>";
							echo "	  <th>時間區間</th>";
							//if (($_SESSION['authority']=="1")||($_SESSION['authority']=="2")||($_SESSION['authority']=="4")){ echo "	  <th></th>";  }
							if ($_SESSION['authority']=="1"){ echo "	  <th></th>"; }
							echo "    </tr>";
							echo "  </thead>";
							echo "  <tbody>";
							while($row = mysqli_fetch_array($result)){
									$idx = $idx + 1;				
									echo "  <tr>";
									echo "    <td>".date('Y-m-d', strtotime($row['vacation']))."</td>";
									echo "    <td>".$row['vacation_name']."</td>";
									echo "    <td>".$row['vacation_period']."</td>";
						
									//if (($_SESSION['authority']=="1")||($_SESSION['authority']=="2")||($_SESSION['authority']=="4")){
									//	echo "    <td>";
									//	echo "      <a href='javascript:GoEdit(".$row['vid'].")'><i class='fa fa-edit'></i></a>";
									//	echo "    </td>";
									//}
									if ($_SESSION['authority']=="1"){
										echo "    <td>";
										echo "  	<a href='javascript:GoDel(".$row['vid'].")'><i class='fa fa-trash'></i></a>";					
										echo "    </td>   ";   
									}
									echo " </tr>";
							}
							echo "  </tbody>";
							echo "</table>";
							echo "<br/>";	
							$havedata = 1;
						}else{
							$page = 0;
							$pages = 0;
							$havedata = 0;
							echo "沒有符合條件的資料!";
						}
					}
				//}
				mysqli_close($link);	
				?>

				  </div>
  
	
                </div>
			  </form>
              </div>
			</div>

            </div>
			
          <div class="row" id='add_vacation' style='display: none;'>

            <div class="col-lg-12 mb-4">

              <!-- Illustrations -->
              <div class="card shadow mb-4">
			<form action="" name="frm1" id="frm1" method="post">
			<input type="hidden" name="act1" id="act1"  value=""/>	
			<input type="hidden" name="tid1" id="tid1"  value="<?php echo $tid;?>"/>				
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">新增休假日資料</h6>
                </div>
                <div class="card-body">
					  <div class="form-group">
						<label class="form-label">休假日:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input class="text-input small-input" type="date" name="vacation" id="vacation" value="" />
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="form-label">假別:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
								<select name="vacation_name" id="vacation_name" class="form-control custom-select">
								  <option value="">--請選擇--</option>
								  <option value="特休">特休</option>
								  <option value="事假">事假</option>
								  <option value="病假">病假</option>
								  <option value="公假">公假</option>
								  <option value="喪假">喪假</option>

								</select>
						  </div>
						</div>
					  </div>					  
					  <div class="form-group">
						<label class="form-label">服務時間:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="hidden" name="vacation_period" id="vacation_period" class="form-control" value=""/>
							<input class="text-input small-input" type="time" name="start_time" id="start_time" value="09:00" /> ~ <input class="text-input small-input" type="time" name="end_time" id="end_time" value="19:00" />
						  </div>
						</div>
					  </div>
		  
					  <div class="card-footer text-center">
							<button type="button" class="btn btn-success ml-auto" onclick="Save_User2()">儲存</button>&nbsp;&nbsp;<button type="button" class="btn btn-info ml-auto" name="backacc" onclick="show_vacation(1);">返回</button>
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
	function GoEdit(id)
	{	
		document.getElementById("act").value = 'Edit';
		document.getElementById("tid").value = id;
		//alert(id);
		document.frm2.action="edithairstylist.php";	
		document.frm2.submit();
	}
	function GoDel(id)
	{	
		if (confirm('確定要刪除這筆資料嗎?')) {
			document.getElementById("act5").value = 'Del';
			document.getElementById("tid5").value = id;
			//alert(id);
			document.frm5.action="vacationdel.php";	
			document.frm5.submit();
		}
	}	
	function show_vacation(id)
	{
		//alert(id);
		if (id == 0) {
			//顯示TR
			//alert("show");
			var result_style = document.getElementById('list_vacation').style;
			result_style.display = 'none';
			var vacation_style = document.getElementById('add_vacation').style;
			vacation_style.display = 'block';
			document.getElementById("act1").value = 'Add';
			//document.getElementById("tid1").value = '';			
		} else {
			//隱藏TR
			//alert("hide");
			var result_style = document.getElementById('list_vacation').style;
			result_style.display = 'block';
			var vacation_style = document.getElementById('add_vacation').style;
			vacation_style.display = 'none';
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
				document.frm3.action="hairstylistpicdel.php";	
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
	function Save_User2(){

		//if (!checkString(document.all.store_id,"請輸入店家代碼!"))	return false;
		if (!checkString(document.all.vacation,"請輸入休假日!"))	return false;
	
		if (document.getElementById("vacation_name").value == "") {
			alert("請選擇假別 !");
			document.getElementById("vacation_name").focus(); 			
			return false;
		}		
		
		//if (!checkString(document.all.vacation_period,"請輸入休假區間!"))	return false;
		if (!checkString(document.all.start_time,"請輸入開始時間!"))	return false;
		if (!checkString(document.all.end_time,"請輸入結束時間!"))	return false;
		document.getElementById("act1").value = 'Add';
		document.frm1.action="vacationadd.php";
		document.frm1.submit();

	}
	function Save_User(){
		//if (!checkString(document.all.store_id,"請輸入店家代碼!"))	return false;
		if (document.getElementById("store_id").value == "") {
			alert("請選擇店家 !");
			document.getElementById("store_id").focus(); 			
			return false;
		}		
		if (!checkString(document.all.nick_name,"請輸入設計師名稱!"))	return false;
		
		//if (document.getElementById("shopping_area").value == "0") {
		//	alert("請選擇商圈分類 !");
		//	document.getElementById("shopping_area").focus(); 			
		//	return false;
		//}		
		if (!checkString(document.all.service_code,"請輸入服務項目!"))	return false;
		
		if (!checkString(document.all.hairstylist_date,"請輸入到職日期!"))	return false;

		if (document.getElementById("hairstylist_status").value == "") {
			alert("請選擇店家啟用狀態 !");
			document.getElementById("hairstylist_status").focus(); 			
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
			self.location.replace("hairstylist.php");
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
