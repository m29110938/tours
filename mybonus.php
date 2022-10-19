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
		document.querySelector('#mybonusphp').classList.add('active');
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
			<h1 class="h5 mb-0 text-gray-800">紅利點數列表</h1>
		  
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
			
		
			$membername = isset($_POST['member_name']) ? $_POST['member_name'] : '';
			$membername  = mysqli_real_escape_string($link,$membername);
			
			$order_no = isset($_POST['order_no']) ? $_POST['order_no'] : '';
			$order_no  = mysqli_real_escape_string($link,$order_no);		
			
			$SDate = isset($_POST['txtSDate']) ? $_POST['txtSDate'] : '';
			$SDate  = mysqli_real_escape_string($link,$SDate);
			$EDate = isset($_POST['txtEDate']) ? $_POST['txtEDate'] : '';
			$EDate  = mysqli_real_escape_string($link,$EDate);

			$bSDate = isset($_POST['bSDate']) ? $_POST['bSDate'] : '';
			$bSDate  = mysqli_real_escape_string($link,$bSDate);
			$bEDate = isset($_POST['bEDate']) ? $_POST['bEDate'] : '';
			$bEDate  = mysqli_real_escape_string($link,$bEDate);
			$memberid = isset($_POST['member_id']) ? $_POST['member_id'] : '';
			$memberid  = mysqli_real_escape_string($link,$memberid);
			$store_name = isset($_POST['store_name']) ? $_POST['store_name'] : '';
			$store_name  = mysqli_real_escape_string($link,$store_name);
			//if ($SDate == "") {
			//	$SDate = date("Y-m-d");;
			//}
			//if ($EDate == "") {
			//	$EDate = date("Y-m-d");;
			//}			
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
				    <form action="mybonus.php" method="Post" name='frm1' id='frm1' class="card">
					<input type="hidden" name="act" id="act"  value=""/>
					<input type="hidden" name="tid" id="tid"  value=""/>
					<input type="hidden" name="page" id="page" value="1">
					<div class="row">
                        <div class="col-md-6 col-lg-2">
							  <div class="form-group">
								<label class="form-label">消費日期(起)</label>
								<!--<input type="text" name="field-name1" class="form-control" data-mask="0000/00/00" data-mask-clearifnotmatch="true" placeholder="yyyy/mm/dd" />-->
								<input class="text-input small-input" type="date" name="txtSDate" id="txtSDate" value="<?=$SDate;?>" />
							  </div>						
						</div>
                        <div class="col-md-6 col-lg-2">
							  <div class="form-group">
								<label class="form-label">消費日期(迄)</label>
								<!--<input type="text" name="field-name2" class="form-control" data-mask="0000/00/00" data-mask-clearifnotmatch="true" placeholder="yyyy/mm/dd" />-->
								<input class="text-input small-input" type="date" name="txtEDate" id="txtEDate" value="<?=$EDate;?>" />
							  </div>		
						</div>		
						<div class="col-md-6 col-lg-2">
							  <div class="form-group">
								<label class="form-label">到期日期(起)</label>
								<!--<input type="text" name="field-name1" class="form-control" data-mask="0000/00/00" data-mask-clearifnotmatch="true" placeholder="yyyy/mm/dd" />-->
								<input class="text-input small-input" type="date" name="bSDate" id="bSDate" value="<?=$bSDate;?>" />
							  </div>						
						</div>
                        <div class="col-md-6 col-lg-2">
							  <div class="form-group">
								<label class="form-label">到期日期(迄)</label>
								<!--<input type="text" name="field-name2" class="form-control" data-mask="0000/00/00" data-mask-clearifnotmatch="true" placeholder="yyyy/mm/dd" />-->
								<input class="text-input small-input" type="date" name="bEDate" id="bEDate" value="<?=$bEDate;?>" />
							  </div>		
						</div>					
						<div class="col-md-6 col-lg-2">
						  <div class="form-group">
							<label class="form-label">帳號:</label>
							<div class="row align-items-center">
							  <div class="col-auto">
								<input type="text" id="member_id" name="member_id" class="form-control w-12" value="<?php echo $memberid; ?>">
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
							<label class="form-label">商店名稱:</label>
							<div class="row align-items-center">
							  <div class="col-auto">
								<input type="text" id="store_name" name="store_name" class="form-control w-12" value="<?php echo $store_name; ?>">
							  </div>
							</div>
						  </div>	
						</div>				
						<div class="col-md-6 col-lg-2">
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
              <h6 class="m-0 font-weight-bold text-primary">紅利點數列表</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
				<?php
				$act = isset($_POST['act']) ? $_POST['act'] : '';
				$havedata = 0;
				//if ($act == 'Qry') {
				
					//$sql = "SELECT * FROM mybonus where bid>0 ";

					$sql = "(SELECT a.*,b.order_date,b.store_id,b.order_pay,b.bonus_date as bonus_get_date,b.bonus_end_date,c.member_id as memberid,c.member_name,d.store_name FROM mybonus as a ";
					$sql = $sql." inner join ( select mid,member_id,member_name from member) c on a.member_id = c.mid ";
					$sql = $sql." inner join ( select order_no,order_date,store_id,order_pay,bonus_date,bonus_end_date from orderinfo) b on a.order_no = b.order_no ";
					$sql = $sql." inner join ( select sid, store_name from store) d on b.store_id = d.sid ";
					$sql = $sql."  where a.bid>0 and a.bonus_type in (1,2) ";
					if (trim($membername) != "") {	
						$sql = $sql." and member_name like '%".trim($membername)."%'";
					}	
					if (trim($order_no) != "") {	
						$sql = $sql." and order_no like '%".trim($order_no)."%'";
					}					
					if ($SDate != "") {	
						$sql = $sql." and a.bonus_date >= '".$SDate." 00:00:00'";
					}
					if ($EDate != "") {	
						$sql = $sql." and a.bonus_date <= '".$EDate." 23:59:59'";
					}		
					// if ($SDate != "") {	
					// 	$sql = $sql." and bonus_date >= '".$SDate." 00:00:00'";
					// }
					// if ($EDate != "") {	
					// 	$sql = $sql." and bonus_date <= '".$EDate." 23:59:59'";
					// }		
					if ($memberid != "") {	
						$sql = $sql." and c.member_id like '%".trim($memberid)."%'";
					}
					if ($store_name != "") {	
						$sql = $sql." and store_name like '%".trim($store_name)."%'";
					}

					$sql = $sql." order by bonus_date )";
					// echo $sql;
					$sql = $sql." UNION ( ";
					$sql = $sql." SELECT a.*,a.bonus_date as order_date,'5358995' as store_id,'0' as order_pay, '0' as bonus_get_date, '0' as bonus_end_date, c.member_id as memberid,c.member_name,'' as store_name FROM mybonus as a ";
					$sql = $sql." inner join ( select mid,member_id,member_name from member) c on a.member_id = c.mid where a.bid>0 and a.bonus_type = 3 ";
					//$sql = $sql." inner join ( select order_no,order_date,store_id,order_pay from orderinfo) b on a.order_no = b.order_no where a.bid>0 and a.bonus_type in (1,2))";
					if (trim($membername) != "") {	
						$sql = $sql." and member_name like '%".trim($membername)."%'";
					}	
					if (trim($order_no) != "") {	
						$sql = $sql." and order_no like '%".trim($order_no)."%'";
					}					
					if ($SDate != "") {	
						$sql = $sql." and bonus_date >= '".$SDate." 00:00:00'";
					}
					if ($EDate != "") {	
						$sql = $sql." and bonus_date <= '".$EDate." 23:59:59'";
					}		
					if ($memberid != "") {	
						$sql = $sql." and c.member_id like '%".trim($memberid)."%'";
					}	

					$sql = $sql." order by bonus_date )";
					//$sql = $sql." )";
					// echo $sql;
					//$sql = $sql." where a.bid>0 ";
					
					//if (trim($membername) != "") {	
					//	$sql = $sql." and member_name like '%".trim($membername)."%'";
					//}	
					///if (trim($order_no) != "") {	
					//	$sql = $sql." and order_no like '%".trim($order_no)."%'";
					//}					
					//if ($SDate != "") {	
					//	$sql = $sql." and bonus_date >= '".$SDate." 00:00:00'";
					//}
					//if ($EDate != "") {	
					//	$sql = $sql." and bonus_date <= '".$EDate." 23:59:59'";
					//}			
					//$sql = $sql." order by bonus_date ";
					//echo $sql;
					//exit;
					$idx = 0;
					  
					if ($result = mysqli_query($link, $sql)){
						if (mysqli_num_rows($result) > 0){
							echo "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>";
							echo "  <thead>";
							echo "    <tr>";
							echo "	  <th>#</th>";
							echo "	  <th>姓名</th>";
							// echo "	  <th>訂單編號</th>";
							echo "	  <th>消費日期</th>";
							echo "	  <th>紅利歸戶日期</th>";
							echo "	  <th>紅利到期日期</th>";
							echo "	  <th>消費金額</th>";						  
							echo "	  <th>類別</th>";
							echo "	  <th>紅利點數</th>";
							echo "	  <th></th>";
							echo "    </tr>";
							echo "  </thead>";
							echo "  <tbody>";
							while($row = mysqli_fetch_array($result)){
									$idx = $idx + 1;				
									echo "  <tr>";
									echo "    <td>".$idx."</td>";
									echo "    <td>".$row['member_name']."</td>";
									// echo "    <td>".$row['order_no']."</td>";
									echo "    <td>".date('Y-m-d', strtotime($row['order_date']))."</td>";
									echo "    <td>".date('Y-m-d', strtotime($row['bonus_get_date']))."</td>";
									echo "    <td>".date('Y-m-d', strtotime($row['bonus_end_date']))."</td>";
									echo "    <td align=right>".$row['order_pay']."</td>";
									//echo "    <td>".$row['bonus_type']."</td>";
										switch ($row['bonus_type']) {
											case 1:
												echo "    <td>消費累積</td>";
												break;
											case 2:
												echo "    <td>消費折抵</td>";
												break;
											case 3:
												echo "    <td>平台贈點</td>";
												break;
											default:
												echo "    <td>&nbsp;</td>";
										}									
									echo "    <td align=right>".$row['bonus']."</td>";
									echo "    <td>";
									echo "      <a href='javascript:GoEdit(".$row['bid'].")'><i class='fa fa-list'></i></a>";
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
						<button type="button" onclick="ExportLog();" class="btn btn-info ml-auto">匯出</button>
				  </div>
					<?php }else{ echo "沒有符合條件的資料!";}} ?>				
				</div>	
				<!-- <?php echo "123"?> -->
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
		document.all.downloadlog.src="mybonus.php";
	}	
	//GoEdit()
	function GoEdit(id)
	{	
		document.getElementById("act").value = 'Edit';
		document.getElementById("tid").value = id;
		//alert(id);
		document.frm1.action="mybonusdetail.php";	
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
