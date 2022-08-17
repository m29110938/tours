<?php
session_start();
if ($_SESSION['accname']==""){
	header("Location: logout.php"); 
}
if ($_SESSION['authority']=="4"){
	header("Location: main.php"); 
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
    <?php
    include("sidebar.php"); 
	?>
	<script>
		document.querySelector('#mycouponphp').classList.add('active');
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
			<h1 class="h5 mb-0 text-gray-800">票券列表</h1>
		  
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
			
			$staus = isset($_POST['staus']) ? $_POST['staus'] : '';
			$staus  = mysqli_real_escape_string($link,$staus);
			$coupondiscount = isset($_POST['coupon_discount']) ? $_POST['coupon_discount'] : '';
			$coupondiscount  = mysqli_real_escape_string($link,$coupondiscount);
			$memberid = isset($_POST['member_id']) ? $_POST['member_id'] : '';
			$memberid  = mysqli_real_escape_string($link,$memberid);

			$couponno = isset($_POST['coupon_no']) ? $_POST['coupon_no'] : '';
			$couponno  = mysqli_real_escape_string($link,$couponno);		
			$couponname = isset($_POST['coupon_name']) ? $_POST['coupon_name'] : '';
			$couponname  = mysqli_real_escape_string($link,$couponname);
		
			$shopping_area = isset($_POST['shopping_area']) ? $_POST['shopping_area'] : '';
			$shopping_area  = mysqli_real_escape_string($link,$shopping_area);
			$membername = isset($_POST['member_name']) ? $_POST['member_name'] : '';
			$membername  = mysqli_real_escape_string($link,$membername);
			$storename = isset($_POST['store_name']) ? $_POST['store_name'] : '';
			$storename  = mysqli_real_escape_string($link,$storename);			
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
		?>
		<form action="mycoupon_export.php" method="Post" name='frm5' id='frm5' class="card">
			<input type="hidden" name="staus" id="staus"  value="<?=$staus;?>"/>
			<input type="hidden" name="coupondiscount" id="coupondiscount"  value="<?=$coupondiscount;?>"/>
			<input type="hidden" name="couponno" id="couponno" value="<?=$couponno;?>">
			<input type="hidden" name="member_id" id="member_id" value="<?=$memberid;?>">
			<input type="hidden" name="couponname" id="couponname"  value="<?=$couponname;?>"/>
			<input type="hidden" name="membername" id="membername"  value="<?=$membername;?>"/>
			<input type="hidden" name="storename" id="storename" value="<?=$storename;?>">
			<input type="hidden" name="shopping_area" id="shopping_area" value="<?=$shopping_area;?>">
			<input type="hidden" name="SDate" id="SDate"  value="<?=$SDate;?>"/>
			<input type="hidden" name="EDate" id="EDate" value="<?=$EDate;?>">
		</form>
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
				    <form action="mycoupon.php" method="Post" name='frm1' id='frm1' class="card">
					<input type="hidden" name="act" id="act"  value=""/>
					<input type="hidden" name="tid" id="tid"  value=""/>
					<input type="hidden" name="page" id="page" value="1">
					<div class="row">
                        <div class="col-md-6 col-lg-2">
							  <div class="form-group">
								<label class="form-label">使用日期(起)</label>
								<!--<input type="text" name="field-name1" class="form-control" data-mask="0000/00/00" data-mask-clearifnotmatch="true" placeholder="yyyy/mm/dd" />-->
								<input class="text-input small-input" type="date" name="txtSDate" id="txtSDate" value="<?=$SDate;?>" />
							  </div>						
						</div>
                        <div class="col-md-6 col-lg-2">
							  <div class="form-group">
								<label class="form-label">使用日期(迄)</label>
								<!--<input type="text" name="field-name2" class="form-control" data-mask="0000/00/00" data-mask-clearifnotmatch="true" placeholder="yyyy/mm/dd" />-->
								<input class="text-input small-input" type="date" name="txtEDate" id="txtEDate" value="<?=$EDate;?>" />
							  </div>		
						</div>						
						<div class="col-md-6 col-lg-2">
							<div class="form-group">
								<label class="form-label">使用狀態:</label>
								<select name="staus" id="staus" class="form-control custom-select">
								  <option value="">--全選--</option>
								  <option value="1" <?php if ($staus=="1") echo "selected"; ?>>已使用</option>
								  <option value="0" <?php if ($staus=="0") echo "selected"; ?>>未使用</option>
								</select>
							</div>
						</div>
						<div class="col-md-6 col-lg-2">
						  <div class="form-group">
							<label class="form-label">票券號碼:</label>
							<div class="row align-items-center">
							  <div class="col-auto">
								<input type="text" id="coupon_no" name="coupon_no" class="form-control w-12" value="<?php echo $couponno; ?>">
							  </div>
							</div>
						  </div>	
						</div>
						<div class="col-md-6 col-lg-2">
						  <div class="form-group">
							<label class="form-label">票券名稱:</label>
							<div class="row align-items-center">
							  <div class="col-auto">
								<input type="text" id="coupon_name" name="coupon_name" class="form-control w-12" value="<?php echo $couponname; ?>">
							  </div>
							</div>
						  </div>	
						</div>
						<div class="col-md-6 col-lg-2">
						  <div class="form-group">
							<label class="form-label">會員姓名:</label>
							<div class="row align-items-center">
							  <div class="col-auto">
								<input type="text" id="member_name" name="member_name" class="form-control w-12" value="<?php echo $membername; ?>">
							  </div>
							</div>
						  </div>	
						</div>	
						<div class="col-md-6 col-lg-2">
						  <div class="form-group">
							<label class="form-label">手機號碼:</label>
							<div class="row align-items-center">
							  <div class="col-auto">
								<input type="text" id="member_id" name="member_id" class="form-control w-12" value="<?php echo $memberid; ?>">
							  </div>
							</div>
						  </div>	
						</div>
						<div class="col-md-6 col-lg-2">
						  <div class="form-group">
							<label class="form-label">適用店家:</label>
							<div class="row align-items-center">
							  <div class="col-auto">
								<input type="text" id="store_name" name="store_name" class="form-control w-12" value="<?php echo $storename; ?>">
							  </div>
							</div>
						  </div>	
						</div>							
						<div class="col-md-6 col-lg-2">
							<div class="form-group">
								<label class="form-label">折扣方式:</label>
								<select name="coupon_discount" id="coupon_discount" class="form-control custom-select">
								  <option value="">--全選--</option>
								  <option value="1" <?php if ($coupondiscount=="1") echo "selected"; ?>>折扣金額</option>
								  <option value="2" <?php if ($coupondiscount=="2") echo "selected"; ?>>折扣%</option>
								</select>
							</div>
						</div>		
						<div class="col-md-6 col-lg-2">
						<div class="form-group">
								<label class="form-label">商圈分類:</label>
								<select name="shopping_area" id="shopping_area" class="form-control custom-select">
								  <option value="">--全選--</option>
								  <?php
									$sql3 = "select aid,shopping_area from shopping_area where shoppingarea_trash=0";
									
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
						<div class="col-md-6 col-lg-2">
						  <div class="form-group">
							  <label class="form-label">&nbsp;</label>
							  <div class="text-center">
								<button type="button" class='btn btn-success ml-auto' onclick='SubmitF();'>搜尋</button>&nbsp;
								<button type="button" onclick="ExportLog();" class="btn btn-info ml-auto">匯出</button>
								<!-- &nbsp;<button type="reset" class="btn btn-info ml-auto">重設</button><button type="button" class="btn btn-success ml-auto" onclick='GoAddUser()'>新增</button> &nbsp;<button type="submit" class="btn btn-primary ml-auto">匯入</button> -->
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
              <h6 class="m-0 font-weight-bold text-primary">會員票券列表</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
				<?php
				$act = isset($_POST['act']) ? $_POST['act'] : '';
				$havedata = 0;
				//if ($act == 'Qry') {

					$sql = "SELECT a.*,b.member_name,b.member_id, c.store_name, d.shopping_area FROM mycoupon as a ";
					$sql = $sql." inner join ( select mid,member_name,member_id from member) as b ON a.mid= b.mid ";
					$sql = $sql." inner join ( select sid,store_name,shopping_area from store) c on a.coupon_storeid = c.sid ";
					$sql = $sql." inner join ( select aid,shopping_area from shopping_area) d on d.aid = c.shopping_area ";
					$sql = $sql." where a.mycoupon_trash=0 ";

					if ($staus != "") {	
						$sql = $sql." and a.using_flag = ".$staus."";
					}
					if ($coupondiscount != "") {	
						$sql = $sql." and a.coupon_discount = ".$coupondiscount."";
					}	
					if ($memberid != "") {	
						$sql = $sql." and b.member_id = ".$memberid."";
					}				
					if (trim($couponno) != "") {	
						$sql = $sql." and a.coupon_no like '%".trim($couponno)."%'";
					}
					if (trim($couponname) != "") {	
						$sql = $sql." and a.coupon_name like '%".trim($couponname)."%'";
					}
					if (trim($membername) != "") {	
						$sql = $sql." and b.member_name like '%".trim($membername)."%'";
					}	
					if (trim($storename) != "") {	
						$sql = $sql." and c.store_name like '%".trim($storename)."%'";
					}		
					if (trim($shopping_area) != "") {	
						$sql = $sql." and d.aid=".$shopping_area."";
					}	
					if ($SDate != "") {	
						$sql = $sql." and a.using_date >= '".$SDate." 00:00:00'";
					}
					if ($EDate != "") {	
						$sql = $sql." and a.using_date <= '".$EDate." 23:59:59'";
					}			
					$sql = $sql." order by a.coupon_no ";
					// echo $sql;
					//exit;
					$idx = 0;
					if ($result = mysqli_query($link, $sql)){
						if (mysqli_num_rows($result) > 0){
							echo "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>";
							echo "  <thead>";
							echo "    <tr>";
							echo "	  <th>#</th>";
							echo "	  <th>姓名</th>";
							echo "	  <th>手機號碼</th>";
							echo "	  <th>票券號碼</th>";
							echo "	  <th>票券名稱</th>";
							echo "	  <th>適用店家</th>";
							echo "	  <th>商圈分類</th>";
							echo "	  <th>折扣方式</th>";
							echo "	  <th>到期日期</th>";						  
							echo "	  <th>使用日期</th>";
							echo "	  <th>使用狀態</th>";
							//echo "	  <th></th>";
							//echo "	  <th></th>";
							echo "    </tr>";
							echo "  </thead>";
							echo "  <tbody>";
							while($row = mysqli_fetch_array($result)){
									$idx = $idx + 1;				
									echo "  <tr>";
									echo "    <td>".$idx."</td>";
									echo "    <td>".$row['member_name']."</td>";
									echo "    <td>".$row['member_id']."</td>";
									echo "    <td>".$row['coupon_no']."</td>";
									echo "    <td>".$row['coupon_name']."</td>";
									echo "    <td>".$row['store_name']."</td>";
									echo "    <td>".$row['shopping_area']."</td>";
									//echo "    <td>".$row['coupon_storeid']."</td>";
										switch ($row['coupon_discount']) {
											case 1:
												echo "    <td>折扣金額</td>";
												break;
											case 2:
												echo "    <td>折扣%</td>";
												break;
											default:
												echo "    <td>&nbsp;</td>";
										}									
								
									echo "    <td>".date('Y-m-d', strtotime($row['coupon_enddate']))."</td>";
									echo "    <td>".$row['using_date']."</td>";
									//echo "    <td>".$row['member_status']."</td>";
										switch ($row['using_flag']) {
											case 1:
												echo "    <td>已使用</td>";
												break;
											case 0:
												echo "    <td>未使用</td>";
												break;
											default:
												echo "    <td>未使用</td>";
										}									
									//echo "    <td>";
									//echo "      <a href='javascript:GoEdit(".$row['pid'].")'><i class='fa fa-edit'></i></a>";
									//echo "    </td>";
									//echo "    <td>";
									//echo "  	<a href='javascript:GoDel(".$row['pid'].")'><i class='fa fa-trash'></i></a>";					
									//echo "    </td>   ";                      
									echo "  </tr>";
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
				//}
				mysqli_close($link);	
				?>			  
				  </div>
				<?php
					if ($act == 'Qry'){
						if($havedata == 1){  ?>
				  <div class="col-md-12 ">
						<br/>
						<button type="button" onclick="ExportLog();" class="btn btn-info ml-auto">匯出</button>
				  </div>
					<?php }else{ echo "沒有符合條件的資料!";}} ?>
				</div>
			  </div>

            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; 2020 Jotangi Technology Co., Ltd	</span>
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
	function ExportLog()
	{
		// document.all.downloadlog.src="mycoupon.php";
		// location.href="mycoupon_export.php";
		document.frm5.submit();
	}	
	
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
