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
		document.querySelector('#profitphp').classList.add('active');
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
			<h1 class="h5 mb-0 text-gray-800">對帳管理</h1>
		  
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
			

			//$order_no = isset($_POST['order_no']) ? $_POST['order_no'] : '';
			//$order_no  = mysqli_real_escape_string($link,$order_no);		
		
			$store_name = isset($_POST['store_name']) ? $_POST['store_name'] : '';
			$store_name  = mysqli_real_escape_string($link,$store_name);		
			$billing_flag = isset($_POST['billing_flag']) ? $_POST['billing_flag'] : '';
			$billing_flag  = mysqli_real_escape_string($link,$billing_flag);
			
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
				    <form action="profit.php" method="Post" name='frm1' id='frm1' class="card">
					<input type="hidden" name="act" id="act"  value=""/>
					<input type="hidden" name="tid" id="tid"  value=""/>
					<input type="hidden" name="pid" id="pid"  value=""/>
					<input type="hidden" name="page" id="page" value="1">
					<div class="row">
                        <div class="col-md-6 col-lg-2">
							  <div class="form-group">
								<label class="form-label">對帳時間(起)</label>
								<!--<input type="text" name="field-name1" class="form-control" data-mask="0000/00/00" data-mask-clearifnotmatch="true" placeholder="yyyy/mm/dd" />-->
								<input class="text-input small-input" type="date" name="txtSDate" id="txtSDate" value="<?=$SDate;?>" />
							  </div>						
						</div>
                        <div class="col-md-6 col-lg-2">
							  <div class="form-group">
								<label class="form-label">對帳時間(迄)</label>
								<!--<input type="text" name="field-name2" class="form-control" data-mask="0000/00/00" data-mask-clearifnotmatch="true" placeholder="yyyy/mm/dd" />-->
								<input class="text-input small-input" type="date" name="txtEDate" id="txtEDate" value="<?=$EDate;?>" />
							  </div>		
						</div>						
						<div class="col-md-6 col-lg-2">
							<div class="form-group">
								<label class="form-label">付款狀態:</label>
								<select name="billing_flag" id="billing_flag" class="form-control custom-select">
								  <option value="">--全選--</option>
								  <option value="0" <?php if ($billing_flag=="0") echo "selected"; ?>>待付款</option>
								  <option value="1" <?php if ($billing_flag=="1") echo "selected"; ?>>已付款</option>
								</select>
							</div>
						</div>
						 <?php if ($_SESSION['authority'] != "4"){ ?>
									
						<div class="col-md-6 col-lg-3">
							<div class="form-group">
								<label class="form-label">店家名稱:</label>
								<div class="row align-items-center">
								  <div class="col-auto">
									<input type="text" id="store_name" name="store_name" class="form-control w-12" value="<?php echo $store_name; ?>">
								  </div>
								</div>
							</div>
						</div>	
						<?php } ?>
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
              <h6 class="m-0 font-weight-bold text-primary">對帳列表</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
				<?php
				$act = isset($_POST['act']) ? $_POST['act'] : '';
				$havedata = 0;
				//if ($act == 'Qry') {
				
					//$sql = "SELECT * FROM orderinfo where oid>0 ";
					
					$sql = "SELECT a.*,b.store_name FROM profit as a ";
					$sql = $sql." inner join ( select sid,store_id,store_name from store) as b ON b.sid= a.store_id ";
					//$sql = $sql." inner join ( select mid,member_id,member_name from member) c on a.member_id = c.mid ";
					
					$sql = $sql." where a.pid>0 and profit_trash=0";

					if ($_SESSION['authority']=="4"){
						$sql = $sql." and b.sid=".$_SESSION['loginsid']."";
					}
					
					if ($billing_flag != "") {	
						$sql = $sql." and a.billing_flag=".$billing_flag."";
					}					
					//if ($paytype != "") {	
					//	$sql = $sql." and a.pay_type=".$paytype."";
					//}	

					//if (trim($memberid) != "") {	
					//	$sql = $sql." and c.member_id like '%".trim($memberid)."%'";
					//}	
					//if (trim($order_no) != "") {	
					//	$sql = $sql." and a.order_no like '%".trim($order_no)."%'";
					//}					
					if ($store_name != "") {	
						$sql = $sql." and b.store_name like '%".$store_name."%'";
					}					
					if ($SDate != "") {	
						$sql = $sql." and a.start_date >= '".$SDate." 00:00:00'";
					}
					if ($EDate != "") {	
						$sql = $sql." and a.end_date <= '".$EDate." 23:59:59'";
					}			
					$sql = $sql." order by a.profit_month desc,a.store_id,a.start_date,a.end_date ";
					//echo $sql;
					//exit;
					$idx = 0;
					$sum1 = 0;$sum2 = 0;$sum3 = 0;$sum4 = 0;$sum5 = 0;$sum6 = 0;$sum7 = 0;$sum8 = 0;$sum9 = 0;
					if ($result = mysqli_query($link, $sql)){
						if (mysqli_num_rows($result) > 0){
							echo "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>";
							echo "  <thead>";
							echo "    <tr>";
							echo "	  <th>#</th>";
							echo "	  <th>店家名稱</th>";
							echo "	  <th>日期</th>";
							echo "	  <th>付款<br/>狀態</th>";
							echo "	  <th>實際支付<br/>金額</th>";
							echo "	  <th>實際紅利<br/>支付金額</th>";						  
							echo "	  <th>實際支付<br/>系統費用</th>";						  
							echo "	  <th>應付費用<br/>總額</th>";
							echo "	  <th>應收紅利<br/>金額</th>";
							echo "	  <th>對帳表<br/>下載</th>";							
						if ($_SESSION['authority']=="4"){							
							echo "	  <th>付款</th>";		
						}else{
							echo "	  <th>是否收<br/>訖款項</th>";
						}	
							//echo "	  <th></th>";
							echo "    </tr>";
							echo "  </thead>";
							echo "  <tbody>";
							while($row = mysqli_fetch_array($result)){
									$idx = $idx + 1;				
									echo "  <tr>";
									echo "    <td>".$idx."</td>";
									echo "    <td>".$row['store_name']."</td>";
									echo "    <td>".date('Y-m-d', strtotime($row['start_date'])).'-'.date('Y-m-d', strtotime($row['end_date']))."</td>";

									switch ($row['billing_flag']) {
										case 0:
											echo "    <td>待付款</td>";
											break;
										case 1:
											echo "    <td>已付款</td>";
											break;
										default:
											echo "    <td>處理中</td>";
									
									}
									//echo "    <td>".$row['total_amount']."</td>";
									echo "    <td align='right'>".number_format($row['total_amountD'],1)."</td>";
									echo "    <td align='right'>".number_format($row['total_amountG'],1)."</td>";
									echo "    <td align='right'>".number_format($row['total_amountI'],1)."</td>";
									echo "    <td align='right'>".number_format($row['total_amountJ'],1)."</td>";
									echo "    <td align='right'>".number_format($row['total_amountK'],1)."</td>";
									echo "    <td>";
									//echo "		<button type='button' class='btn btn-warning ml-auto' onclick=ExportLog(".$row['pid'].");>下載</button>";
									echo "		<button type='button' class='btn btn-warning ml-auto' onclick=ExportLog('".$row['store_id']."','".date('Y-m-d', strtotime($row['start_date']))."','".date('Y-m-d', strtotime($row['end_date']))."','".$row['urate']."');>下載</button>";
									echo "    </td>";
					if ($_SESSION['authority']=="4"){
										
									if ($row['billing_flag'] == 0) {
										echo "    <td>";
										//echo "      <a href='javascript:GoEdit(".$row['pid'].")'><i class='fa fa-list'></i></a>";
										echo "      <button type='button' class='btn btn-danger ml-auto' onclick='PayBill();'>付款</button>";
										echo "    </td>";
									}else {
										echo "    <td>&nbsp;</td>";
									}
					}else{
									if ($row['billing_flag'] == 0) {
										echo "    <td>";
										//echo "      <a href='javascript:GoEdit(".$row['pid'].")'><i class='fa fa-list'></i></a>";
										echo "      <button type='button' class='btn btn-danger ml-auto' onclick='PayStatus(".$row['pid'].");'>確認</button>";
										echo "    </td>";
									}else {
										echo "    <td>&nbsp;</td>";
									}						
					}
									echo "  </tr>";
							}
							//total
								
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
						<?php if ($_SESSION['authority']=="1"){ ?> <!--<button type="button" onclick="ExportLog();" class="btn btn-info ml-auto">匯出</button>--><?php } ?>
				  </div>
					<?php }else{ echo "沒有符合條件的資料!";}} ?>
				</div>
			  </div>
				    <form action="" method="Post" name='frm2' id='frm2' class="card">
					<input type="hidden" name="sdate" id="sdate"  value=""/>
					<input type="hidden" name="edate" id="edate"  value=""/>
					<input type="hidden" name="urate" id="urate"  value=""/>
					<input type="hidden" name="sid" id="sid"  value=""/>
					</form>
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
            <span>Copyright &copy; 2022 Jotangi Technology Co., Ltd	</span>
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
	function PayBill()
	{
		var msg = "請匯款到\r\n";
			msg += "銀行：聯邦銀行803　健行分行0353\r\n";
			msg += "帳戶：035-10-0025333\r\n";
			msg += "戶名：創思商業創新顧問股份有限公司\r\n";
			msg += "或現金支付\r\n";
			alert(msg);
	}
	function PayStatus(id)
	{
		if (confirm('請確認是否收訖此款項?')) {
		  // Save it!
			document.getElementById("act").value = 'Del';
			document.getElementById("tid").value = id;
			//alert(id);
			document.frm1.action="profitdel.php";
			document.frm1.submit();		  
		} else {
		  // Do nothing!
		  //console.log('Thing was not saved to the database.');
		  //alert('Do nothing!');
		}	
	}
	function ExportLog(id,b,c,d)
	{
		//document.getElementById("pid").value = id;
		//document.all.downloadlog.src="profilereport.html";
		//alert("profitreport.php?sid="+id+"&sdate="+b+"&edate="+c+"&urate="+d+"");
		//document.all.downloadlog.src="profitreport.php?sid="+id+"&sdate="+b+"&edate="+c+"&urate="+d+"";
		//self.location.replace("profitreport2.php?sid="+id+"&sdate="+b+"&edate="+c+"&urate="+d+"");

		document.getElementById("sdate").value = b;
		document.getElementById("edate").value = c;
		document.getElementById("urate").value = d;
		document.getElementById("sid").value = id;
		document.frm2.action="profitreport2.php";	
		document.frm2.submit();

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
  <iframe id="downloadlog" name="downloadlog" src='' width='1' height='1' scrolling='vertical'></iframe>
</body>

</html>
