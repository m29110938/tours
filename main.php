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
	$host = 'localhost';
	$user = 'tours_user';
	$passwd = 'tours0115';
	$database = 'toursdb';
		
	$link = mysqli_connect($host, $user, $passwd, $database);
	mysqli_query($link,"SET NAMES 'utf8'");

	if ($_SESSION['authority']=="4"){
		$sql = "SELECT count(*) as member_count FROM membercard where membercard_trash=0 and store_id=".$_SESSION['loginsid']."";
	}else{
		$sql = "SELECT count(*) as member_count FROM member where member_trash=0 and member_type=1 ";
	}	
	//echo $sql;
	//exit;
	$member_count = 0;
	if ($result = mysqli_query($link, $sql)){
		if (mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_array($result)){
				$member_count = $row['member_count'];
			}
		}
	}

	if ($_SESSION['authority']=="4"){
		$sql = "SELECT count(*) as store_count FROM coupon where coupon_trash=0 and coupon_storeid=".$_SESSION['loginsid']."";
	}else{
		$sql = "SELECT count(*) as store_count FROM store where store_trash=0";
	}	

	//echo $sql;
	//exit;
	$store_count = 0;
	if ($result = mysqli_query($link, $sql)){
		if (mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_array($result)){
				$store_count = $row['store_count'];
			}
		}
	}
	if ($_SESSION['authority']=="4"){
		$sql = "SELECT sum(order_amount) as order_amount FROM orderinfo where order_status='1' and store_id=".$_SESSION['loginsid']."";
	}else{
		$sql = "SELECT sum(order_amount) as order_amount FROM orderinfo where order_status='1'";
	}
	//echo $sql;
	//exit;
	$order_amount = 0;
	if ($result = mysqli_query($link, $sql)){
		if (mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_array($result)){
				$order_amount = $row['order_amount'];
			}
		//
		}else{
			$order_amount = 0;
		}
	}
	Save_Log($link,$_SESSION['userid'],$_SESSION['accname'],$_SESSION['loginsid'],'Main',$_SESSION['authority']);
	//mysqli_close($link);
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
		// document.querySelector('#pushphp').classList.add('active');
		document.querySelector('#hh').classList.add('active');
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
			<i class="fas fa-fw fa-home"></i>
			<h1 class="h4 mb-0 text-gray-800">首頁</h1>
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

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">訂單總額 (每月)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">$<?php echo $order_amount;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">分潤總額 (每月)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">$<?php echo $order_amount*0.2;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">會員總人數</div>
                      <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                          <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $member_count;?></div>
                        </div>
                        <!--<div class="col">
                          <div class="progress progress-sm mr-2">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </div>-->
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
					<?php					
						if ($_SESSION['authority']=="4"){  
									echo "<div class='col mr-2'>";
									echo "<div class='text-xs font-weight-bold text-warning text-uppercase mb-1'>獨家優惠</div>";
									echo "<div class='h5 mb-0 font-weight-bold text-gray-800'>".$store_count."</div>";
									echo "</div>";
						}else{
									echo "<div class='col mr-2'>";
									echo "<div class='text-xs font-weight-bold text-warning text-uppercase mb-1'>加盟店家</div>";
									echo "<div class='h5 mb-0 font-weight-bold text-gray-800'>".$store_count."</div>";
									echo "</div>";
						}
					?>	
                    <div class="col-auto">
                      <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
<?php 

if ($_SESSION['authority']=="4"){

$period = isset($_POST['period']) ? $_POST['period'] : '2';

	if ($_SESSION['authority']=="4"){
		$sql = "SELECT sum(order_amount) as order_amount FROM orderinfo where order_status='1' and store_id=".$_SESSION['loginsid']."";
	}else{
		$sql = "SELECT sum(order_amount) as order_amount FROM orderinfo where order_status='1'";
	}
	
function getordercount($conn,$a) {
		$total = 0;
		$sql2 = "SELECT count(*) as total FROM orderinfo where order_status='1' and store_id=".$_SESSION['loginsid']." ";
		if ($a != "") {	
			$sql2 = $sql2." and order_date >= '".$a." 00:00:00'";
		}
		if ($a != "") {	
			$sql2 = $sql2." and order_date <= '".$a." 23:59:59'";
		}
		//$sql2 = $sql2." group by user_id";
		//echo $sql2;
		if ($result2 = mysqli_query($conn, $sql2)){
			if (mysqli_num_rows($result2) > 0){
				while($row2 = mysqli_fetch_array($result2)){
					$total = $row2['total'];
				}
			}
		}
		//echo $total;
		return $total;
}
function getordersum($conn,$a) {
		$total = 0;
		$sql2 = "SELECT sum(order_amount) as total FROM orderinfo where order_status='1' and store_id=".$_SESSION['loginsid']." ";
		if ($a != "") {	
			$sql2 = $sql2." and order_date >= '".$a." 00:00:00'";
		}
		if ($a != "") {	
			$sql2 = $sql2." and order_date <= '".$a." 23:59:59'";
		}
		//$sql2 = $sql2." group by user_id";
		//echo $sql2;
		if ($result2 = mysqli_query($conn, $sql2)){
			if (mysqli_num_rows($result2) > 0){
				while($row2 = mysqli_fetch_array($result2)){
					$total = $row2['total'];
				}
			}
		}
		//echo $total;
		return $total;
}
date_default_timezone_set('asia/taipei'); //默认时区

//$TDate = isset($_GET['TDate']) ? $_GET['TDate'] : '';
//echo $TDate;
//$NowDate = isset($_POST['NowDate']) ? $_POST['NowDate'] : '';
//if ($TDate == "") {
$NowDate = date("Y-m-d",time());
//echo $NowDate;
//}
//$Now2Date = date("Y-m-d H:i:s",strtotime("-1 minutes"));

$y = 1;
switch ($period) {
	case 1:
		$EDate=date("Y-m-d H:i:s",time());
		$SDate=date("Y-m-d",strtotime($NowDate))." 00:00:00";
		$y = 1;
		break;
	case 2:
		$EDate=date("Y-m-d",strtotime($NowDate));
		$SDate=date("Y-m-d",strtotime("-7 Days",strtotime($NowDate)));
		$y = 7;
		break;
	case 3:
		$EDate=date("Y-m-d",strtotime($NowDate));
		$SDate=date("Y-m-d",strtotime("-30Days",strtotime($NowDate)));
		$y = 30;
		break;
}


$tt = "";
$cc = "";
$ss = "";
if ($period == 1) {
	$tt = "'".$NowDate."'";
	$cc = $cc.getordercount($link,$NowDate);
	$ss = $ss.getordersum($link,$NowDate);
}else{
	for ($x = 1; $x <= $y; $x++) {

		$tt = $tt."'".date('Y-m-d', strtotime('+'.$x.' Days', strtotime($SDate)))."'";
		$convertedTime = date('Y-m-d', strtotime('+'.$x.' Days', strtotime($SDate)));
		$cc = $cc.getordercount($link,$convertedTime);
		$ss = $ss.getordersum($link,$convertedTime);
		if ($x<$y){
			$tt = $tt.",";
			$cc = $cc.",";
			$ss = $ss.",";
		}
		//echo $tt."<br>";
		//echo $cc."<br>";
	}
}	

?>		  
		<form action="main.php" method="Post" name='frm1' id='frm1' >
		<input type="hidden" name="period" id="period"  value=""/>
          <!-- Content Row -->
          <div class="row">


            <div class="col-lg-12 mb-8">

              <!-- Illustrations -->
            <div class="col-xl-12 col-lg-7">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">訂單筆數統計</h6>
                  <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">選單:</div>
                      <a class="dropdown-item" href="#" onclick='SubmitF(1);'>日統計</a>
                      <a class="dropdown-item" href="#" onclick='SubmitF(2);'>週統計</a>
                      <a class="dropdown-item" href="#" onclick='SubmitF(3);'>月統計</a>
                      <!--<div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#">Something else here</a> -->
                    </div>
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

            <div class="col-xl-12 col-lg-7">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">訂單金額統計</h6>
                  <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">選單:</div>
                      <a class="dropdown-item" href="#" onclick='SubmitF(1);'>日統計</a>
                      <a class="dropdown-item" href="#" onclick='SubmitF(2);'>週統計</a>
                      <a class="dropdown-item" href="#" onclick='SubmitF(3);'>月統計</a>
                      <!--<div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#">Something else here</a> -->
                    </div>
                  </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="chart-area">
                    <canvas id="myAreaChart2"></canvas>
                  </div>
                </div>
              </div>
            </div>
			
            </div>
          </div>
        </div>
			</form>			
<?php }   ?>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

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

  <SCRIPT LANGUAGE=javascript>
	<!--

	function SubmitF(period)
	{
		//alert("1");
		document.getElementById("period").value = period;
		document.frm1.submit();
	}	
	
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
		  label: "訂單筆數",
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
			  return datasetLabel + ': $' + number_format(tooltipItem.yLabel);
			}
		  }
		}
	  }
	}); 

	// myAreaChart2
	var ctx = document.getElementById("myAreaChart2");
	var myLineChart = new Chart(ctx, {
	  type: 'line',
	  data: {
		labels: [<?php echo $tt; ?>],
		datasets: [{
		  label: "訂單筆數",
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
		  data: [<?php echo $ss; ?>],
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
			  return datasetLabel + ': $' + number_format(tooltipItem.yLabel);
			}
		  }
		}
	  }
	}); 
	
	//-->
  </SCRIPT>	
</body>

</html>
