<?php
session_start();
if ($_SESSION['accname']==""){
	header("Location: logout.php"); 
}
function Save_Log($conn,$a,$b,$c,$d,$e){
	//member_id	member_name	store_id	function_name	log_date	action_type
	
		$sql="INSERT INTO syslog (member_id,member_name,store_id,function_name,log_date,action_type) VALUES ('$a', '$b',$c, '$d',NOW(),$e);";
		//echo $sql;
		//exit;
		mysqli_query($conn,$sql) or die(mysqli_error($conn));
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
      <li class="nav-item  active">
		<a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseUtilities2" aria-expanded="true" aria-controls="collapseUtilities2">
		  <i class="fas fa-fw fa-shopping-bag"></i>
		  <span>訂單管理</span>
		</a>	  
		<div id="collapseUtilities2" class="collapse show" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
		  <div class="bg-white py-2 collapse-inner rounded">
			<a class="collapse-item" href="order.php">訂單管理</a>
			<a class="collapse-item" href="profit.php">對帳管理</a>
			<?php if ($_SESSION['authority']!="4"){  ?>
			<a class="collapse-item active" href="report.php">分析報表</a>
			<?php } ?>			
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
			<h1 class="h5 mb-0 text-gray-800">分析報表</h1>
		  
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

			$shopping_area = isset($_POST['shopping_area']) ? $_POST['shopping_area'] : '';
			$shopping_area  = mysqli_real_escape_string($link,$shopping_area);	
			
			$report_no = isset($_POST['report_no']) ? $_POST['report_no'] : '';
			$report_no  = mysqli_real_escape_string($link,$report_no);
			
			$SDate = isset($_POST['txtSDate']) ? $_POST['txtSDate'] : '';
			$SDate  = mysqli_real_escape_string($link,$SDate);
			$EDate = isset($_POST['txtEDate']) ? $_POST['txtEDate'] : '';
			$EDate  = mysqli_real_escape_string($link,$EDate);
			//if ($SDate == "") {
			//	$SDate = date("Y-m-d");;
			//}
			//if ($EDate == "") {
			//	$EDate = date("Y-m-d");;
			//}			
			
			Save_Log($link,$_SESSION['userid'],$_SESSION['accname'],$_SESSION['loginsid'],'Order',$_SESSION['authority']);
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
				    <form action="report.php" method="Post" name='frm1' id='frm1' class="card">
					<input type="hidden" name="act" id="act"  value=""/>
					<input type="hidden" name="tid" id="tid"  value=""/>
					<input type="hidden" name="page" id="page" value="1">
					<div class="row">
                        <div class="col-md-6 col-lg-2">
							  <div class="form-group">
								<label class="form-label">紀錄時間(起)</label>
								<!--<input type="text" name="field-name1" class="form-control" data-mask="0000/00/00" data-mask-clearifnotmatch="true" placeholder="yyyy/mm/dd" />-->
								<input class="text-input small-input" type="date" name="txtSDate" id="txtSDate" value="<?=$SDate;?>" />
							  </div>						
						</div>
                        <div class="col-md-6 col-lg-2">
							  <div class="form-group">
								<label class="form-label">紀錄時間(迄)</label>
								<!--<input type="text" name="field-name2" class="form-control" data-mask="0000/00/00" data-mask-clearifnotmatch="true" placeholder="yyyy/mm/dd" />-->
								<input class="text-input small-input" type="date" name="txtEDate" id="txtEDate" value="<?=$EDate;?>" />
							  </div>		
						</div>						
						<div class="col-md-6 col-lg-2">
							<div class="form-group">
								<label class="form-label">目標對象報表:</label>
								<select name="report_no" id="report_no" class="form-control custom-select">
								  <option value="">--請選擇--</option>
								  <option value="1" <?php if ($report_no == 1) echo ' selected';?>>店長登入報表</option>
								  <option value="2" <?php if ($report_no == 2) echo ' selected';?>>會員登入報表</option>
								  <option value="3" <?php if ($report_no == 3) echo ' selected';?>>客戶開發報表</option>
								  <option value="4" <?php if ($report_no == 4) echo ' selected';?>>會員行為報表</option>
								</select>
							</div>
						</div>
						<div class="col-md-6 col-lg-2">
							<div class="form-group">
								<label class="form-label">商圈分類:</label>
								<select name="shopping_area" id="shopping_area" class="form-control custom-select">
								  <option value="">--全選--</option>
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
						<div class="col-md-6 col-lg-3">
						  <div class="form-group">
							  <label class="form-label">&nbsp;</label>
							  <div class="text-center">
								<button type="button" class='btn btn-success ml-auto' onclick='SubmitF();'>搜尋</button> &nbsp;<button type="reset" class="btn btn-info ml-auto">重設</button>
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
              <h6 class="m-0 font-weight-bold text-primary">統計列表</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
				<?php
				$act = isset($_POST['act']) ? $_POST['act'] : '';
				$havedata = 0;
				$tt = "";
				$cc = "";
							
				if ($report_no != '') {

					if ($report_no == 1) {
						$sql = "SELECT count(member_id) as totalcount, DATE_FORMAT(log_date, '%Y-%m-%d') as xAxes FROM syslog where rid>0 and store_id > 0 ";

						if ($shopping_area != "") {	
							$sql = $sql." and store_id in (select sid from store where shopping_area=$shopping_area and store_trash=0)";
						}						
						if ($SDate != "") {	
							$sql = $sql." and log_date >= '".$SDate." 00:00:00'";
						}
						if ($EDate != "") {	
							$sql = $sql." and log_date <= '".$EDate." 23:59:59'";
						}
			
						$sql = $sql." group by xAxes ";						
					}
					if ($report_no == 2) {
						//$sql = "SELECT count(member_id) as totalcount, DATE_FORMAT(log_date, '%Y-%m-%d') as xAxes FROM syslog where rid>0  and store_id = 0 ";
						 $sql = "SELECT DATE_FORMAT(w.log_date, '%Y%m%d') as xAxes,count(*) as totalcount FROM `syslog` as w";
						$sql = $sql." INNER JOIN member m ON w.member_id = m.member_id";
						 $sql = $sql." INNER JOIN membercard as mc ON m.mid = mc.member_id";
						 $sql = $sql." INNER JOIN store as s ON mc.store_id = s.sid";
						 //$sql = $sql." INNER JOIN shopping_area sa ON s.shopping_area = sa.aid";
						 //$sql = $sql." group by logdate";
						 $sql = $sql." where w.rid>0 ";
						if ($shopping_area != "") {	
							$sql = $sql." and s.shopping_area=$shopping_area";
						}
						if ($SDate != "") {	
							$sql = $sql." and w.log_date >= '".$SDate." 00:00:00'";
						}
						if ($EDate != "") {	
							$sql = $sql." and w.log_date <= '".$EDate." 23:59:59'";
						}			
						$sql = $sql." group by xAxes ";						
					}
					if ($report_no == 3) {
						//$sql = "SELECT count(member_id) as totalcount, cfrom as xAxes FROM weblog where rid>0 ";
						 $sql = "SELECT count(w.member_id) as totalcount, cfrom as xAxes FROM weblog as w";
						$sql = $sql." INNER JOIN member m ON w.member_id = right(m.member_id,9)";
						 $sql = $sql." INNER JOIN membercard as mc ON m.mid = mc.member_id";
						 $sql = $sql." INNER JOIN store as s ON mc.store_id = s.sid";
						 //$sql = $sql." INNER JOIN shopping_area sa ON s.shopping_area = sa.aid";
						 //$sql = $sql." group by logdate";
						 $sql = $sql." where w.rid>0 ";
						if ($shopping_area != "") {	
							$sql = $sql." and s.shopping_area=$shopping_area";
						}
						if ($SDate != "") {	
							$sql = $sql." and w.log_date >= '".$SDate." 00:00:00'";
						}
						if ($EDate != "") {	
							$sql = $sql." and w.log_date <= '".$EDate." 23:59:59'";
						}			
						$sql = $sql." group by xAxes order by xAxes ";						
					}
					if ($report_no == 4) {
						//$sql = "SELECT count(member_id) as totalcount, page as xAxes FROM weblog where rid>0 ";
						$sql = "SELECT count(w.member_id) as totalcount, page as xAxes FROM `weblog` as w";
						$sql = $sql." INNER JOIN member m ON w.member_id = right(m.member_id,9)";
						$sql = $sql." INNER JOIN membercard as mc ON m.mid = mc.member_id";
						$sql = $sql." INNER JOIN store as s ON mc.store_id = s.sid";
						 //$sql = $sql." INNER JOIN shopping_area sa ON s.shopping_area = sa.aid";
						 //$sql = $sql." group by logdate";
						$sql = $sql." where w.rid>0 ";
						if ($shopping_area != "") {	
							$sql = $sql." and s.shopping_area=$shopping_area";
						}
						if ($SDate != "") {	
							$sql = $sql." and w.log_date >= '".$SDate." 00:00:00'";
						}
						if ($EDate != "") {	
							$sql = $sql." and w.log_date <= '".$EDate." 23:59:59'";
						}			
						$sql = $sql." group by xAxes order by xAxes ";							
					}
					//if ($_SESSION['authority']=="4"){
					//	$sql = $sql." and b.sid=".$_SESSION['loginsid']."";
					//}
					//echo $sql;
					//exit;
					$idx = 0;
					if ($result = mysqli_query($link, $sql)){
						if (mysqli_num_rows($result) > 0){
							echo "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>";
							echo "  <thead>";
							echo "    <tr>";
							echo "	  <th>#</th>";
							echo "	  <th>統計標題</th>";
							echo "	  <th>次數</th>";
							echo "    </tr>";
							echo "  </thead>";
							echo "  <tbody>";


							while($row = mysqli_fetch_array($result)){
									$idx = $idx + 1;				
									echo "  <tr>";
									echo "    <td>".$idx."</td>";
									//echo "    <td>".$row['xAxes']."</td>";
									switch ($report_no) {
										case 1:
											echo "    <td>".$row['xAxes']."</td>";
											break;
										case 2:
											echo "    <td>".$row['xAxes']."</td>";
											break;
										case 3:
											switch ($row['xAxes']) {
												case 1:
													echo "    <td>FB活動宣傳</td>";
													break;
												case 2:
													echo "    <td>IG活動宣傳</td>";
													break;
												case 3:
													echo "    <td>店家現場宣傳</td>";
													break;
												case 4:
													echo "    <td>現場行銷活動</td>";
													break;
												default:
													echo "    <td>&nbsp;</td>";
											}
											break;
										case 4:
											switch ($row['xAxes']) {
												case 1:
													echo "    <td>登入</td>";
													break;
												case 2:
													echo "    <td>商城</td>";
													break;
												case 3:
													echo "    <td>購物車</td>";
													break;
												case 4:
													echo "    <td>Banner</td>";
													break;
												case 5:
													echo "    <td>xx</td>";
													break;
												default:
													echo "    <td>&nbsp;</td>";
											}
											break;
										default:
											echo "    <td>&nbsp;</td>";
									}									
									echo "    <td>".$row['totalcount']."</td>";
									echo "  </tr>";
									$tt = $tt."'".$row['xAxes']."'";
									$cc = $cc.$row['totalcount'];
									if ($idx < mysqli_num_rows($result)){
										$tt = $tt.",";
										$cc = $cc.",";										
									}
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
				}
				mysqli_close($link);	
				?>			  
				  </div>
				<?php
					if ($act == 'Qry'){
						if($havedata == 1){  ?>
				  <div class="col-md-12 ">
						<?php if ($_SESSION['authority']=="1"){ ?><!--<button type="button" onclick="ExportLog();" class="btn btn-info ml-auto">匯出</button>--><?php } ?>
				  </div>
					<?php }else{ echo "沒有符合條件的資料!";}} ?>
				</div>
			  </div>

            </div>
          </div>
		<?php
		if (($report_no == 1)||($report_no == 2)||($report_no == 3)){  ?>
		<form action="report.php" method="Post" name='frm2' id='frm2' >
		<input type="hidden" name="period" id="period"  value=""/>
          <!-- Content Row -->
          <div class="row">
	  
			<?php
			switch ($report_no) {
				case 1:
					$report_title = "店長登入報表";
					break;
				case 2:
					$report_title = "會員登入報表";
					break;
				case 3:
					$report_title = "客戶開發報表";
					break;
				case 4:
					$report_title = "會員行為報表";
					break;
				default:
					$report_title = "筆數統計";
			}
			?>
            <div class="col-lg-12 mb-8">

              <!-- Illustrations -->
            <div class="col-xl-12 col-lg-7">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary"><?=$report_title?></h6>
                  <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <!--<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">選單:</div>
                      <a class="dropdown-item" href="#" onclick='SubmitF(1);'>日統計</a>
                      <a class="dropdown-item" href="#" onclick='SubmitF(2);'>週統計</a>
                      <a class="dropdown-item" href="#" onclick='SubmitF(3);'>月統計</a>
                    </div>-->
                  </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                  </div>
                </div>
              </div>
            </div>
		
            </div>
          </div>
			</form>		
		<?php } ?>
			
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; 2021 Jotangi Technology Co., Ltd	</span>
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

	Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
	Chart.defaults.global.defaultFontColor = '#858796';

	function number_format(number, decimals, dec_point, thousands_sep) {
	  // *     example: number_format(1234.56, 2, ',', ' ');
	  // *     return: '1 234,56'
	  number = (number + '').replace(',', '').replace(' ', '');
	  var n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
		dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
		s = '',
		toFixedFix = function(n, prec) {
		  var k = Math.pow(10, prec);
		  return '' + Math.round(n * k) / k;
		};
	  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
	  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	  if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	  }
	  if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	  }
	  return s.join(dec);
	}

	// myAreaChart
	var ctx = document.getElementById("myAreaChart");
	var myLineChart = new Chart(ctx, {
	  type: 'line',
	  data: {
		labels: [<?php echo $tt; ?>],
		datasets: [{
		  label: "次數",
		  lineTension: 0.3,
		  backgroundColor: "rgba(78, 115, 223, 0.05)",
		  borderColor: "rgba(0, 255, 0, 1)",
		  pointRadius: 3,
		  pointBackgroundColor: "rgba(78, 115, 223, 1)",
		  pointBorderColor: "rgba(0, 255, 0, 1)",
		  pointHoverRadius: 3,
		  pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
		  pointHoverBorderColor: "rgba(78, 115, 223, 1)",
		  pointHitRadius: 10,
		  pointBorderWidth: 2,
		  data: [<?php echo $cc; ?>],
		}],
	  },
	  options: {
		maintainAspectRatio: false,
		layout: {
		  padding: {
			left: 10,
			right: 25,
			top: 25,
			bottom: 0
		  }
		},
		scales: {
		  xAxes: [{
			time: {
			  unit: 'date'
			},
			gridLines: {
			  display: false,
			  drawBorder: false
			},
			ticks: {
			  maxTicksLimit: 30
			}
		  }],
		  yAxes: [{
			ticks: {
			  maxTicksLimit: 5,
			  padding: 10,
			  // Include a dollar sign in the ticks
			  callback: function(value, index, values) {
				return '' + number_format(value);
			  }
			},
			gridLines: {
			  color: "rgb(234, 236, 244)",
			  zeroLineColor: "rgb(234, 236, 244)",
			  drawBorder: false,
			  borderDash: [2],
			  zeroLineBorderDash: [2]
			}
		  }],
		},
		legend: {
		  display: false
		},
		tooltips: {
		  backgroundColor: "rgb(255,255,255)",
		  bodyFontColor: "#858796",
		  titleMarginBottom: 10,
		  titleFontColor: '#6e707e',
		  titleFontSize: 14,
		  borderColor: '#dddfeb',
		  borderWidth: 1,
		  xPadding: 15,
		  yPadding: 15,
		  displayColors: false,
		  intersect: false,
		  mode: 'index',
		  caretPadding: 10,
		  callbacks: {
			label: function(tooltipItem, chart) {
			  var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
			  return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
			}
		  }
		}
	  }
	}); 

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
