<?php
require_once("Chinwei6_LinePay.php");
session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LinePay API - Reserve</title>
        <link rel="stylesheet" href="kule-lazy-full.3.0.1007beta.min.css" />
        <style type="text/css">
            body {
                min-width: 360px;
            }   
        </style>
    </head>
    <body>
        <header>
			<?php //include('./blocks/api_basic_info.php'); ?>
            <?php include('./blocks/header.php'); ?>
        </header>

        <?php include('./blocks/payment_steps.php'); ?>

        <div class="container">
            <div class="panel">
                <div class="panel-header">
                    <h3 class="panel-title">LinePay 伺服器回應</h3>
                </div>
                <div class="panel-box">
                    <?php
                        // Store Webpage -> Store Server
						//session_start();						
	$amount = $_POST['amount0'];
	
	if( intval($amount)<=0)
                {
                    echo "Can't be zero!!";
                    exit;
                 }
						
                        if(isset($_POST['productName'])) 
                        {
                            //$apiEndpoint   = $_POST['apiEndpoint'];
                            //$channelId     = $_POST['channelId'];
                            //$channelSecret = $_POST['channelSecret'];
							//write into MYSQL DB
							$host = 'localhost';
							$user = 'medicalec_user';
							$passwd = 'medicalec211013';
							$database = 'medicalecdb';

							$link = mysqli_connect($host, $user, $passwd, $database);
							mysqli_query($link,"SET NAMES 'utf8'");

							$orderno = $_POST['orderId'];
                            $amount  = $_POST['amount0'];
							//$amount = "1";
							$statuscode = -1;//-1:待付款, 1: 已付款, 2:已出貨
							$t=time();
							$time = date("Y-m-d H:i:s",$t);
							
							//$sql = "UPDATE orderinfo SET status=-1, amount = ".$amount." , updatetime = NOW() where orderid='".$orderno."'";
							//$result = mysql_query($sql);// or die('MySQL query error');								

							$sql="UPDATE orderinfo SET pay_status=-1,pay_type=3,order_pay = ".$amount." , order_updated_at = NOW() where order_no='".$orderno."'";

							//echo $sql;
							//exit;
							$result = mysqli_query($link,$sql); //or die(mysqli_error($link));

							$sid = isset($_POST['sid']) ? $_POST['sid'] : '';
							$mid = isset($_POST['mid']) ? $_POST['mid'] : '';
								
							if (($sid != '') && ($mid != '')) {	
								$sql = "SELECT * FROM payment where payment_trash=0 and store_id=$sid and payment_type='1' and  payment_status=1 ";
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
							}else{
									$data["status"]="false";
									$data["code"]="0x0204";
									$data["responseMessage"]="API parameter is required!";
									header('Content-Type: application/json');
									echo (json_encode($data, JSON_UNESCAPED_UNICODE));	
									exit;			
									//header("Location: payindex.php");
							}

							if (($SecretKey == "")||($StoreIdKey == ""))  {
									$data["status"]="false";
									$data["code"]="0x0205";
									$data["responseMessage"]="API parameter is required!";
									header('Content-Type: application/json');
									echo (json_encode($data, JSON_UNESCAPED_UNICODE));	
									exit;	
							}	

							$apiEndpoint = "https://api-pay.line.me/v2/payments/";
							$channelId = $StoreIdKey;   	//"1653555191";
							$channelSecret = $SecretKey;    //"b5f7f10f010adaffcffe089be1b8b9dd";							

							
							$url = "https://medicalec.jotangi.net/medicalec/payments/linepay/confirm.php";
							//$url = "https://coupon.jotangi.net:9443/hpaydemo/payments/linepay/confirm.php";
							$url .= "?amount=";
							$url .= $_POST['amount0'];
							$url .= "&currency=";
							$url .= $_POST['currency'];
							$url .= "&sid=".$sid;
							$url .= "&mid=".$mid;
                            $params = [
                                "productName"     => $_POST['productName'],
                                "productImageUrl" => $_POST['productImageUrl'],
                                "amount"          => $_POST['amount0'],
                                "currency"        => $_POST['currency'],
                                "confirmUrl"      => $url,
                                "orderId"         => $_POST['orderId'],
                                "confirmUrlType"  => "CLIENT",
                            ];

                            try {
                                $LinePay = new Chinwei6\LinePay($apiEndpoint, $channelId, $channelSecret);
                                
                                // Save params in the _SESSION
                                $_SESSION['cache'] = [
                                    "apiEndpoint"   => $apiEndpoint,    //$_POST['apiEndpoint'],
                                    "channelId"     => $channelId,		//$_POST['channelId'],
                                    "channelSecret" => $channelSecret,	//$_POST['channelSecret'],
                                    "amount"        => $_POST['amount0'],
                                    "currency"      => $_POST['currency'],
                                ];

                                $result = $LinePay->reserve($params);
                               // echo '<pre class="code">';
                                //echo json_encode($result, JSON_PRETTY_PRINT);
                               // echo '</pre>';

                                //if(isset($result['info']['paymentUrl']['web']))
                                  //  echo '<a target="_blank" href="' . $result['info']['paymentUrl']['web'] . '">點此連至 Line 頁面登入帳戶</a>';
							  $str = "Location:";
							  if(isset($result['info']['paymentUrl']['app']))
							  {
								$str .= $result['info']['paymentUrl']['app'];								
								header($str  );//go to this;
							  }
							  else
							  {
								  echo "Line Pay 伺服器無回應, 請稍候再試!!";
							  }
                            }
                            catch(Exception $e) {
                                echo '<pre class="code">';
                                echo $e->getMessage();
                                echo '</pre>';
                            }
                        }
                        else {
                            echo "No Data";
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>