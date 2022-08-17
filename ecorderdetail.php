<?php
session_start();
if ($_SESSION['accname']==""){
	header("Location: logout.php"); 
}

	$act = isset($_POST['act']) ? $_POST['act'] : '';
	$tid = isset($_POST['tid']) ? $_POST['tid'] : '';
	//echo $tid;
	
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
		document.querySelector('#ecorderphp').classList.add('active');
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
			<h1 class="h5 mb-0 text-gray-800">商城訂單管理</h1>
		  
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
			<?php

				$host = 'localhost';
				$user = 'tours_user';
				$passwd = 'tours0115';
				$database = 'toursdb';
					
				$link = mysqli_connect($host, $user, $passwd, $database);
				mysqli_query($link,"SET NAMES 'utf8'");
				
				//$sql = "SELECT a.*,b.store_name,b.shopping_area, c.member_id as memberid,c.member_name FROM orderinfo as a ";
				//$sql = $sql." inner join ( select sid,store_id,store_name,shopping_area from store) as b ON b.sid= a.store_id ";
				//$sql = $sql." inner join ( select mid,member_id,member_name from member) c on a.member_id = c.mid ";
				$sql = "SELECT a.*, c.member_id as memberid,c.member_name FROM  ecorderinfo as a ";
				//$sql = $sql." inner join ( select sid,store_id,store_name,shopping_area from store) as b ON b.sid= a.store_id ";
				//$sql = $sql." inner join ( select aid,shopping_area from shopping_area) as d ON d.aid= b.shopping_area ";
				$sql = $sql." inner join ( select mid,member_id,member_name from member) c on a.member_id = c.mid ";
				
				$sql = $sql." where a.oid>0 ";
				if ($tid != ""){
					$sql = $sql." and a.oid = $tid";
				}
				//echo $sql;
				if ($result = mysqli_query($link, $sql)){
					if (mysqli_num_rows($result) > 0){
						while($row = mysqli_fetch_array($result)){
							$order_no = $row['order_no'];
							$order_date = $row['order_date'];
							//$store_name = $row['store_name'];
							$order_amount = $row['order_amount'];
							$coupon_no = $row['coupon_no'];
							$discount_amount = $row['discount_amount'];
							
							$bonus_get = $row['bonus_get'];
							$bonus_date = $row['bonus_date'];

							//$pay_type = $row['pay_type'];
							switch ($row['pay_type']) {
								case 1:
									$pay_type = "現金";
									break;
								case 2:
									$pay_type = "刷卡";
									break;
								case 3:
									$pay_type = "LINEPAY";
									break;
								case 4:
									$pay_type = "街口";
									break;
								default:
									$pay_type = "";
							}								
							$order_pay = $row['order_pay'];
							//$pay_status = $row['pay_status'];
							switch ($row['pay_status']) {
								case 0:
									$pay_status = "未付款";
									break;
								case 1:
									$pay_status = "已付款";
									break;
								default:
									$pay_status = "處理中";
							}							
							$bonus_point = $row['bonus_point'];
							$order_status = $row['order_status'];
							switch ($row['order_status']) {
								case 0:
									$orderstatus="處理中";
									break;
								case 1:
									$orderstatus="完成";
									break;
								case 2:
									$orderstatus="取消";
									break;
								default:
									$orderstatus="";
							}

							$memberid = $row['memberid'];
							$member_name = $row['member_name'];
							//$shopping_area = $row['shopping_area'];
							//$shoppingarea = $row['shoppingarea'];
						}
					//mysqli_close($link);
					}
				}
			?>
              <!-- Approach -->
			<div class="card shadow mb-4">
				<div class="card-header py-3">
				  <h6 class="m-0 font-weight-bold text-primary"><?php echo $order_no; ?> 訂單明細</h6>
				</div>
				<div class="card-body">
					<table width="100%">
					<tr>
						<td>訂單時間:</td><td><?php echo $order_date; ?></td><td>訂單金額:</td><td>NT <?php echo $order_amount; ?></td><td>&nbsp;</td><td>&nbsp;</td>
					</tr>
					<tr>
						<td>訂單編號:</td><td><?php echo $order_no; ?></td><td>實付金額:</td><td>NT <?php echo $order_pay; ?></td><td>&nbsp;</td><td>&nbsp;</td>
					</tr>
					<tr>
						<td>付款方式:</td><td><?php echo $pay_type; ?></td><td>訂單贈點:</td><td><?php echo $bonus_get; ?></td><td>&nbsp;</td><td>&nbsp;</td>
					</tr>
					<tr>
						<td width="10%">訂單狀態:</td><td width="20%"><?php echo $orderstatus; ?></td><td width="10%">紅利點數折抵:</td><td><?php echo $bonus_point; ?></td>
					</tr>
					</table>
				</div>
				<div class="card-header py-3">
				  <h6 class="m-0 font-weight-bold text-primary">付款人資訊</h6>
				</div>
				<div class="card-body">
					<table width="100%">
					<tr>
						<td width="10%">會員姓名:</td><td width="20%"><?php echo $member_name; ?></td><td width="10%">&nbsp;</td><td width="20%">&nbsp;</td><td width="10%">&nbsp;</td><td>&nbsp;</td>
					</tr>
					<tr>
						<td>會員電話:</td><td><?php echo $memberid; ?></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
					</tr>
					</table>
				</div>	
				<div class="card-header py-3">
				  <h6 class="m-0 font-weight-bold text-primary">訂單內容</h6>
				</div>
				<div class="card-body">
					<table class='table card-table table-vcenter text-nowrap'>
						<thead>
						<tr>
							<th>#</th>
							<th>商品編號</th>
							<th>商品名稱</th>
							<th>價格</th>
							<th>數量</th>
							<th>小計</th>
						</tr>
						</thead>
						<tbody>
					<?php 				
						$sql3 = "SELECT a.*,b.product_name,c.producttype_name,b.product_picture FROM ecorderlist as a ";
						$sql3 = $sql3." inner join (select pid, product_no, product_name,product_picture from product where product_trash = 0 ) as b on a.product_no=b.product_no  ";
						$sql3 = $sql3." inner join (select pid, producttype_name from producttype  where producttype_trash = 0) as c on b.pid=c.pid ";
						$sql3 = $sql3." where a.order_no='$order_no'";

						$sql3 = $sql3." order by producttype_name,product_name ";
						//echo $sql3;
						$rows = "";
						if ($result3 = mysqli_query($link, $sql3)){
							if (mysqli_num_rows($result3) > 0){
								$i = 0;
								while($row3 = mysqli_fetch_array($result3)){
									$i = $i + 1;
									echo "<tr>";
									echo "	<td>".$i."</td>";
									echo "	<td>".$row3['product_no']."</td>";
									echo "	<td>".$row3['product_name']."</td>";
									echo "	<td>".$row3['product_price']."</td>";
									echo "	<td>".$row3['order_qty']."</td>";
									echo "	<td>".$row3['total_amount']."</td>";
									echo "</tr>";
							
								}
							}
						}
					?>				
						</tr>
						</tbody>
					</table>
				</div>
				<!--<div class="card-header py-3">
				  <h6 class="m-0 font-weight-bold text-primary">推薦來源/分潤</h6>
				</div>
				<div class="card-body">
					<table width="100%">
					<tr>
						<td width="10%">推薦來源:</td><td width="20%">導遊一</td><td width="10%">分潤金額</td><td width="20%">NT 5</td><td width="10%">&nbsp;</td><td>&nbsp;</td>
					</tr>
					<tr>
						<td>推薦分潤:</td><td>5 %</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
					</tr>
					</table>
				</div>	-->			
			</div>
			<div class="card shadow mb-4">
				<div class="card-header py-3">
				  <h6 class="m-0 font-weight-bold text-primary">付款/退款紀錄</h6>
				</div>
				<div class="card-body">
				    <form action="" method="Post" name='frm1' id='frm1' class="card">
					<input type="hidden" name="act" id="act"  value="<?php echo $act;?>"/>
					<input type="hidden" name="tid" id="tid"  value="<?php echo $tid;?>"/>
					<table class='table card-table table-vcenter text-nowrap'>
						<thead>
						<tr>
							<th>訂單編號</th>
							<th>訂單日期</th>
							<th>訂單金額</th>
							<th>折扣金額</th>
							<th>付款方式</th>
							<th>付款狀態</th>
							<th>&nbsp;</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td><?php echo $order_no; ?></td>
							<td><?php echo $order_date; ?></td>
							<td><?php echo $order_amount; ?></td>
							<td><?php echo $discount_amount; ?></td>
							<td><?php echo $pay_type; ?></td>
							<td><?php echo $pay_status; ?></td>
							<td><?php if ($order_status == 1){ ?><button type="button" class='btn btn-danger ml-auto' onclick="CancelECOrder();">退款</button><?php } ?></td>
						</tr>
						</tbody>
					</table>
					  <div class="card-footer text-center">
							<button type="button" class="btn btn-info ml-auto" name="backacc" onclick="GoAccount();">返回</button>
					  </div>		
					</form>
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
	function CancelECOrder()
	{
		if (confirm('確定要取消這筆訂單嗎?')) {
			document.getElementById("act").value = 'Cancel';
			//document.getElementById("tid").value = id;
			//alert(document.getElementById("tid").value);
			document.frm1.action="cancelecorder.php";	
			document.frm1.submit();
		}		
	}
	function GoAccount()
	{
		self.location.replace("ecorder.php");
	}
	function Logout(){
		//alert("登出系統!");
		self.location.replace('logout.php');
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
