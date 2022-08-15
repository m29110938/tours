<?php
require_once("Chinwei6_LinePay.php");
session_start();
//include_once('custom.php');
function wh_log($log_msg)
{
    $log_filename = "./log";
    if (!file_exists($log_filename)) 
    {
        // create directory/folder uploads.
        mkdir($log_filename, 0777, true);
    }
    $log_file_data = $log_filename.'/log_line_' . date('d-M-Y') . '.log';
    // if you don't add `FILE_APPEND`, the file will be erased each time you add a log
    file_put_contents($log_file_data, date("Y-m-d H:i:s")."  ------  ".$log_msg . "\n", FILE_APPEND);
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LinePay API - Confirm</title>
        <link rel="stylesheet" href="kule-lazy-full.3.0.1007beta.min.css" />
        <style type="text/css">
            body {
                min-width: 360px;
            }   
        </style>
    </head>
    <body>
        <header>
			<?php include('./blocks/api_basic_info.php'); ?>
            <?php include('./blocks/header.php'); ?>
        </header>

        <?php include('./blocks/payment_steps.php'); ?>

        <div class="container">
            <div class="panel">
                <div class="panel-header">
                    <h3 class="panel-title">LinePay 交易資料</h3>
                </div>
                <div class="panel-box">
                    <?php 
function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
//echo $url;			
            break;
        case "GET":
			
			//curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    //curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);
//echo $result;
    curl_close($curl);

    return $result;
};

$flag = false;
                        // LinePay Server -> Store Server (calling confirmUrl)
                        if(isset($_GET['transactionId'])){// && isset($_SESSION['cache'])) {
                            //$apiEndpoint   = $_SESSION['cache']['apiEndpoint'];
                            //$channelId     = $_SESSION['cache']['channelId'];
                            //$channelSecret = $_SESSION['cache']['channelSecret'];

                            $params = [
                                "amount"   => $_GET['amount'], 
                                "currency" => $_GET['currency'],
                            ];

                            try {
								
								$host = 'localhost';
								$user = 'medicalec_user';
								$passwd = 'medicalec211013';
								$database = 'medicalecdb';

								$link = mysqli_connect($host, $user, $passwd, $database);
								mysqli_query($link,"SET NAMES 'utf8'");
									
								$sid =   isset($_GET['sid']) ? $_GET['sid'] : '';
                                $mid =   isset($_GET['mid']) ? $_GET['mid'] : '';
								
								if (($sid != '') && ($mid != '')) {	
									$sql = "SELECT * FROM payment where payment_trash=0 and store_id=$sid and payment_type='1' and  payment_status=1 ";
									wh_log($sql);
									if ($result = mysqli_query($link, $sql)){
										if (mysqli_num_rows($result) > 0){
											//$mid=0;
											$store_name = "";
											while($row = mysqli_fetch_array($result)){
												$ApiKey = $row['api_key'];
												$SecretKey = $row['secret_key'];
												$StoreIdKey = $row['storeid_key'];
												break;
											}
										}else {
											$ApiKey = "";
											$SecretKey = "";
											$StoreIdKey = "";
										}
									}else {
										$ApiKey = "";
										$SecretKey = "";
										$StoreIdKey = "";
									}
								}								
								if($SecretKey == "" || $StoreIdKey == "")
								{
									echo "System Error: No SecretKey!!";
									exit;
								}
								
								$channelId = $StoreIdKey;   	//"1653555191";
								$channelSecret = $SecretKey;    //"b5f7f10f010adaffcffe089be1b8b9dd";	
							
                                $LinePay = new Chinwei6\LinePay($apiEndpoint, $channelId, $channelSecret);

                                $result = $LinePay->confirm($_GET['transactionId'], $params);
                                //echo '<pre class="code">';
                                //echo json_encode($result, JSON_PRETTY_PRINT);
                                //echo '</pre>';
								
								
								if($result['returnCode'] == "0000")
								{
									$t=time();
									$time = date("Y-m-d H:i:s",$t);
									echo '<pre class="code">';
									echo "交易狀態:"."交易成功".'<br>';
									echo "交易時間:".$time.'<br>';
									echo "交易編號:".$result['info']['transactionId'].'<br>';
									echo "訂單編號:".$result['info']['orderId'].'<br>';
									echo "付款方式:".$result['info']['payInfo'][0]['method'].'<br>';
									echo "付款金額:".$result['info']['payInfo'][0]['amount'].'<br>';
									echo "卡片編號:".$result['info']['payInfo'][0]['maskedCreditCardNumber'].'<br>';
																
									//echo "已購買咖啡清單";									
									echo '</pre>';
									echo "<a href='hpay://payment?url=http://xxx.xx/'>回健康配</a>";		

									//echo "<SCRIPT LANGUAGE=javascript>";
									//echo "<!--";
									//echo " var orderno = ".$order_no."";
									//echo " var timer = setTimeout(function() { ";
									//echo "		window.location = '../comfirmorder.php?orderno='+orderno;';";
									//echo "}, 1000);	";
									//echo "//-->";
									//echo "</SCRIPT>";
									
									//Write into the MYSQL DB;
									
									//$dbhost = "localhost";
									//$dbuser = 'jtguser';
									//$dbpass = 'jtguser2017';
									//$dbname = 'hpaydemo';
										
									//$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');
									//mysql_query("SET NAMES 'utf8'");
									//mysql_select_db($dbname);


							
									$transactionId=$result['info']['transactionId'];
									$method='LINEPAY-'.$result['info']['payInfo'][0]['method'];
									$mcardno=$result['info']['payInfo'][0]['maskedCreditCardNumber'];
									$orderno = $result['info']['orderId'];
									$amount  = $result['info']['payInfo'][0]['amount'];									
									$statuscode = 1;//-1:待付款, 1: 已付款, 2:已出貨
									
									if(intval($amount)<=0)
									{
										echo "System Error 0x01!!";
										exit;
									}
									
									/*$t=time();
									$time = date("Y-m-d H:i:s",$t);
									
									$sql = "select * from orderinfo where orderno='$orderno' and status=-1";
									$result = mysql_query($sql);
									$rowCount = mysql_num_rows($result);
									$row = mysql_fetch_array($result);
									$name = $row['name'];
									if ($rowCount > 0) {
									
										$sql = "update orderinfo set status='$statuscode', updatetime='$time', method='$method' ";
										$sql .= "where orderid='$orderno'";									
										//echo $sql;
										$result = mysql_query($sql);// or die('MySQL query error');
										//$url = "https://coupon.jotangi.net:9443/reg/01/payments/invoice.php?amount=".$amount."&quantity=1&name=".$name."&orderno=".$orderno;
										//$data = "";
										//$out = CallAPI("GET", $url, $data);
										exit;
									}
									else
									{
										echo "System Error 0x02!!";
										exit;
									}
									*/
									$sql = "SELECT * FROM orderinfo where order_no='$orderno' and pay_status=-1 ";
									if ($result = mysqli_query($link, $sql)){
										if (mysqli_num_rows($result) > 0){

											$sql="UPDATE orderinfo SET pay_status='$statuscode',pay_type=3, transactionId='$transactionId',order_updated_at = NOW() where order_no='$orderno'";

											//echo $sql;
											//exit;
											$result = mysqli_query($link,$sql); //or die(mysqli_error($link));
											$flag = true;
											//exit;
										}	
										else
										{
											echo "System Error 0x02!!";
											exit;
										}
									}				
									
								}
								
                            }
                            catch(Exception $e) {
								$t=time();
								$time = date("Y-m-d H:i:s",$t);
								
                                echo '<pre class="code">';								
								echo "交易狀態:"."交易失敗".'<br>';
								echo "交易時間:".$time.'<br>';
								echo "錯誤訊息:".'<br>';
                                echo $e->getMessage();
									
                                echo '</pre>';
								echo "<a href='hpay://payment?url=http://xxx.xx/'>回健康配</a>";
                            }

                            unset($_SESSION['cache']);
                        }
                        else {
							$t=time();
							$time = date("Y-m-d H:i:s",$t);							
                            echo '<pre class="code">';
							echo "交易狀態:"."交易失敗".'<br>';
							echo "交易時間:".$time.'<br>';
							echo "錯誤訊息:".'<br>';
                            echo "No Params";
                            echo '</pre>';
							echo "<a href='hpay://payment?url=http://xxx.xx/'>回健康配</a>";	
                        }
                    ?>
                </div>
            </div>
        </div>

<?php if ($flag == true)	{	?>
	<SCRIPT LANGUAGE=javascript>
	<!--
	var orderno = "<?php echo $orderno ; ?>";
	var timer = setTimeout(function() {
			window.location = "../comfirmorder.php?orderno="+orderno;
	}, 1000);	
	//-->
	</SCRIPT>		
<?php	}	?>
    </body>
</html>