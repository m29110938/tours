<?php
session_start();
include("../db_tools.php");
	$sid = isset($_POST['sid']) ? $_POST['sid'] : '';
	$mid = isset($_POST['mid']) ? $_POST['mid'] : '';
	
if (($sid != "")&&($mid != ""))  {

	//$tid = isset($_POST['tid']) ? $_POST['tid'] : '';
	$amount0 = isset($_POST['amount0']) ? $_POST['amount0'] : '';
	$amount0  = mysqli_real_escape_string($link,$amount0);
		
	$date = date_create();
	$tour_guide=1;
	$pay_type = 0;  // cash 1, card 2, line pay 3, jakol 4
	
	$rand = sprintf("%04d", rand(0,9999));
	$orderid = date_timestamp_get($date).$rand;
										
	$sql="INSERT INTO orderinfo (order_no,order_date,store_id,tour_guide,member_id,order_amount,coupon_no,discount_amount,pay_type,order_pay,pay_status,bonus_point,order_status) VALUES ";
	$sql=$sql." ('$orderid',NOW(),$sid,$tour_guide,$mid,$amount0,'',0,$pay_type,$amount0,0,0,1);";
					
	//echo $sql;
	//exit;
	
	mysqli_query($link,$sql) or die(mysqli_error($link));
}else{
		$data["status"]="false";
		$data["code"]="0x0202";
		$data["responseMessage"]="API parameter is required!";
		header('Content-Type: application/json');
		echo (json_encode($data, JSON_UNESCAPED_UNICODE));	
		exit;			
		//header("Location: payindex.php");
}		
?>
<!DOCTYPE html>
<html>

<head> 
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>健康配</title> 
    <!--<link rel="icon" href="../images/favicon.ico" type="image/x-icon"/>-->
    <!--<link rel="shortcut icon" type="image/x-icon" href="../images/favicon.ico" />-->
    <!-- Generated: 2018-04-06 16:27:42 +0200 -->
    <title>健康配</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,500,500i,600,600i,700,700i&amp;subset=latin-ext">
    <script src="./assets/js/require.min.js"></script>
    <script>
      requirejs.config({
          baseUrl: '.'
      });
    </script>
    <!-- Dashboard Core -->
    <link href="./assets/css/dashboard.css" rel="stylesheet" />
    <script src="./assets/js/dashboard.js"></script>

    <!-- Input Mask Plugin -->
    <script src="./assets/plugins/input-mask/plugin.js"></script>
	<script src="js/rce_comm.js"></script>
	<style>
		.box {
		  display: flex; 
		  align-items: center;  
		  margin: auto;
		  width: 1200px;
		}	
	</style>
</head>

<body onload='Submit();'>

    <div id="wrapper">
   
     <div class="page-main">
        <div class="header py-4">
          <div class="container">
            <div class="d-flex">
              <a class="header-brand" href="./index.html">
                <img src="../images/h_logo.png" class="header-brand-img" alt="tabler logo" style="width:50px">
              </a>
              <h2 class="page-title">
                應付費用
              </h2>			  
              <a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#headerMenuCollapse">
                <span class="header-toggler-icon"></span>
              </a>
            </div>
          </div>
        </div>   


        <div class="my-3 my-md-5">
          <div class="container">
            <div class="row">
                <div class='row justify-content-center'>
					<form role="form"  name='frm1' id='frm1' method="POST" action="./linepay/reserve.php">
					<input type="hidden" type="text" class="ctrl-input" name="productName" value="應付費用" readonly="readonly" required>
					<input type="hidden" type="text" class="ctrl-input" name="orderId" value="<?php echo $orderid; ?>" readonly="readonly" required>									
					<input type="hidden" type="text" class="ctrl-input" name="productImageUrl" value="https://coupon.jotangi.net:9443/hpaydemo/images/h_logo.png" >
					<input type="hidden" class="ctrl-input" name="currency" value="TWD">			
					<input type="hidden" class="ctrl-input" name="amount0" value="<?php echo $amount0; ?>">	
				
					<input type="hidden" name="sid" id="sid" value="<?php echo $sid;  ?>">
					<input type="hidden" name="mid" id="mid" value="<?php echo $mid;  ?>">		

					</br></br></br></br>
					<button type="button" class="btn btn-primary ml-auto" onclick='Submit()'>確定付款</button>
					<button type="button" class="btn btn-info ml-auto" onclick="history.back();">返回前頁</button>
					</form>
                </div> 
				</div> 
            </div> 
		</div>
        </div>
        <!-- /#page-wrapper -->

	<p class='text-center'><br>Copyright &copy;  2022 Jotangi Technology Co., Ltd.<br> All Right Reserved.</p>
    </div>
    <!-- /#wrapper -->
 
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>  
    <script src="js/sb-admin.js"></script>  

</body>
<script>

function Submit()
{
	//alert("OLK");
	document.frm1.submit();
}

</script>
</html>
