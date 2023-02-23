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
    <?php
    include("sidebar.php"); 
	?>
	<script>
		document.querySelector('#orderphp').classList.add('active');
		document.querySelector('#dd').classList.add('active');
		document.querySelector('#d').classList.remove('collapsed');
		document.querySelector('#collapseUtilities2').classList.add('show');
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
			<h1 class="h5 mb-0 text-gray-800">訂單管理</h1>
		  
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
			
			$orderstatus = isset($_POST['order_status']) ? $_POST['order_status'] : '';
			$orderstatus  = mysqli_real_escape_string($link,$orderstatus);
			$paytype = isset($_POST['pay_type']) ? $_POST['pay_type'] : '';
			$paytype  = mysqli_real_escape_string($link,$paytype);


			$orderno = isset($_POST['order_no']) ? $_POST['order_no'] : '';
			$orderno  = mysqli_real_escape_string($link,$orderno);		
		
			$memberid = isset($_POST['member_id']) ? $_POST['member_id'] : '';
			$memberid  = mysqli_real_escape_string($link,$memberid);
			$storename = isset($_POST['store_name']) ? $_POST['store_name'] : '';
			$storename  = mysqli_real_escape_string($link,$storename);		

			$membername = isset($_POST['member_name']) ? $_POST['member_name'] : '';
			$membername  = mysqli_real_escape_string($link,$membername);
			
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
				    <form action="order.php" method="Post" name='frm1' id='frm1' class="card">
					<input type="hidden" name="act" id="act"  value=""/>
					<input type="hidden" name="tid" id="tid"  value=""/>
					<input type="hidden" name="page" id="page" value="1">
					<div class="row">
                        <div class="col-md-6 col-lg-2">
							  <div class="form-group">
								<label class="form-label">訂單時間(起)</label>
								<!--<input type="text" name="field-name1" class="form-control" data-mask="0000/00/00" data-mask-clearifnotmatch="true" placeholder="yyyy/mm/dd" />-->
								<input class="text-input small-input" type="date" name="txtSDate" id="txtSDate" value="<?=$SDate;?>" />
							  </div>						
						</div>
                        <div class="col-md-6 col-lg-2">
							  <div class="form-group">
								<label class="form-label">訂單時間(迄)</label>
								<!--<input type="text" name="field-name2" class="form-control" data-mask="0000/00/00" data-mask-clearifnotmatch="true" placeholder="yyyy/mm/dd" />-->
								<input class="text-input small-input" type="date" name="txtEDate" id="txtEDate" value="<?=$EDate;?>" />
							  </div>		
						</div>						
						<div class="col-md-6 col-lg-2">
							<div class="form-group">
								<label class="form-label">訂單狀態:</label>
								<select name="order_status" id="order_status" class="form-control custom-select">
								  <option value="">--全選--</option>
								  <option value="1">完成</option>
								  <option value="0">取消</option>
								</select>
							</div>
						</div>
						<div class="col-md-6 col-lg-2">
							<div class="form-group">
								<label class="form-label">付款方式:</label>
								<select name="pay_type" id="pay_type" class="form-control custom-select">
								  <option value="">--全選--</option>
								  <option value="1">現金</option>
								  <option value="2">刷卡</option>
								</select>
							</div>
						</div>						
						<div class="col-md-6 col-lg-3">
							<div class="form-group">
								<label class="form-label">店家名稱:</label>
								<div class="row align-items-center">
								  <div class="col-auto">
									<input type="text" id="store_name" name="store_name" class="form-control w-12" value="<?php echo $storename; ?>">
								  </div>
								</div>
							</div>
						</div>	
						<div class="col-md-6 col-lg-2">
						  <div class="form-group">
							<label class="form-label">會員帳號:</label>
							<div class="row align-items-center">
							  <div class="col-auto">
								<input type="text" id="member_id" name="member_id" class="form-control w-12" value="<?php echo $memberid; ?>">
							  </div>
							</div>
						  </div>	
						</div>
						<div class="col-md-6 col-lg-2">
						  <div class="form-group">
							<label class="form-label">訂單編號:</label>
							<div class="row align-items-center">
							  <div class="col-auto">
								<input type="text" id="order_no" name="order_no" class="form-control w-12" value="<?php echo $orderno; ?>">
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
              <h6 class="m-0 font-weight-bold text-primary">訂單列表</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
				<?php
				$act = isset($_POST['act']) ? $_POST['act'] : '';
				$havedata = 0;
				//if ($act == 'Qry') {
				
					$sql = "SELECT * FROM orderinfo where oid>0 ";
					
					$sql = "SELECT a.*,b.store_name, c.member_id as memberid,c.member_name FROM orderinfo as a ";
					$sql = $sql." inner join ( select sid,store_id,store_name from store) as b ON b.sid= a.store_id ";
					$sql = $sql." inner join ( select mid,member_id,member_name from member) c on a.member_id = c.mid ";
					
					$sql = $sql." where a.oid>0 and b.store_name != '紅利到期' and b.store_name != '點點商城' and b.store_name != '點點商城退款'";

					if ($_SESSION['authority']=="4"){
						$sql = $sql." and b.sid=".$_SESSION['loginsid']."";
					}
					
					if ($orderstatus != "") {	
						$sql = $sql." and a.order_status=".$orderstatus."";
					}					
					if ($paytype != "") {	
						$sql = $sql." and a.pay_type=".$paytype."";
					}	

					if (trim($memberid) != "") {	
						$sql = $sql." and c.member_id like '%".trim($memberid)."%'";
					}	
					if (trim($orderno) != "") {	
						$sql = $sql." and a.order_no like '%".trim($orderno)."%'";
					}					
					if ($storename != "") {	
						$sql = $sql." and b.store_name like '%".$storename."%'";
					}					
					if ($SDate != "") {	
						$sql = $sql." and a.order_date >= '".$SDate." 00:00:00'";
					}
					if ($EDate != "") {	
						$sql = $sql." and a.order_date <= '".$EDate." 23:59:59'";
					}			
					$sql = $sql." order by a.order_date";
					//echo $sql;
					//exit;
					$idx = 0;
					if ($result = mysqli_query($link, $sql)){
						if (mysqli_num_rows($result) > 0){
							echo "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>";
							echo "  <thead>";
							echo "    <tr>";
							echo "	  <th>#</th>";
							echo "	  <th>訂單編號</th>";
							echo "	  <th>訂單日期</th>";
							echo "	  <th>店家名稱</th>";
							//echo "	  <th>導遊姓名</th>";
							echo "	  <th>會員帳號</th>";						  
							echo "	  <th>訂單金額</th>";
							echo "	  <th>付款方式</th>";
							echo "	  <th>付款狀態</th>";
							echo "	  <th>訂單狀態</th>";
							//echo "	  <th></th>";
							echo "	  <th></th>";
							echo "    </tr>";
							echo "  </thead>";
							echo "  <tbody>";
							while($row = mysqli_fetch_array($result)){
									$idx = $idx + 1;				
									echo "  <tr>";
									echo "    <td>".$idx."</td>";
									echo "    <td>".$row['order_no']."</td>";
									echo "    <td>".date('Y-m-d', strtotime($row['order_date']))."</td>";
									echo "    <td>".$row['store_name']."</td>";
									//echo "    <td>".$row['tour_guide']."</td>";
									echo "    <td>".$row['memberid']."</td>";
									echo "    <td align=right>".$row['order_amount']."</td>";
									//echo "    <td>".$row['pay_type']."</td>";
									switch ($row['pay_type']) {
										case 1:
											echo "    <td>現金</td>";
											break;
										case 2:
											echo "    <td>刷卡</td>";
											break;
										default:
											echo "    <td>nbsp;</td>";
									}									
									//echo "    <td>".$row['pay_status']."</td>";
									switch ($row['pay_status']) {
										case 0:
											echo "    <td>未付款</td>";
											break;
										case 1:
											echo "    <td>已付款</td>";
											break;
										case 9:
											echo "    <td>已退款</td>";
											break;
										default:
											echo "    <td>處理中</td>";
									}									
									switch ($row['order_status']) {
										case 0:
											echo "    <td>取消</td>";
											break;
										case 1:
											echo "    <td>完成</td>";
											break;
										default:
											echo "    <td>處理中</td>";
									}									
									echo "    <td>";
									echo "      <a href='javascript:GoEdit(".$row['oid'].")'><i class='fa fa-list'></i></a>";
									echo "    </td>";
                    
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
						<?php if ($_SESSION['authority']=="1"){ ?><button type="button" onclick="ExportLog();" class="btn btn-info ml-auto">匯出</button><?php } ?>
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
		document.all.downloadlog.src="order.php";
	}	
	//GoEdit()
	function GoEdit(id)
	{	
		document.getElementById("act").value = 'Edit';
		document.getElementById("tid").value = id;
		//alert(id);
		document.frm1.action="orderdetail.php";	
		document.frm1.submit();
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
