<?php
//phpinfo();
session_start();
	$host = 'localhost';
	$user = 'medicalec_user';
	$passwd = 'medicalec211013';
	$database = 'medicalecdb';	

	$link = mysqli_connect($host, $user, $passwd, $database);
	mysqli_query($link,"SET NAMES 'utf8'");

$sid = isset($_POST['sid']) ? $_POST['sid'] : '';
//$mid = isset($_POST['mid']) ? $_POST['mid'] : '';
	
if (($sid != '')) {	
	$sql = "SELECT * FROM payment where payment_trash=0 and store_id=$sid and payment_type='3' and  payment_status=1 ";
	if ($result = mysqli_query($link, $sql)){
		if (mysqli_num_rows($result) > 0){
			//$mid=0;
			$store_name = "";
			while($row = mysqli_fetch_array($result)){
				$ApiKey = $row['api_key'];
				$SecretKey = $row['secret_key'];
				$StoreIdKey = $row['storeid_key'];
		
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
//echo $SecretKey;
//echo $StoreIdKey;
//exit;
				
//include_once('preorder.php');
function encrypt($key, $payload)
{
    $iv = "8672731586517315";
    $encrypted = openssl_encrypt($payload, 'aes-128-cbc', $key,  $options=OPENSSL_RAW_DATA, $iv);
	$ciphertext = base64_encode($encrypted);
	return $ciphertext;
}
function decrypt($key, $ciphertext)
{
	$iv = "8672731586517315";
	$output = openssl_decrypt(base64_decode($ciphertext), 'aes-128-cbc', $key, $options=OPENSSL_RAW_DATA, $iv);
	return $output;
}
function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
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
}
ignore_user_abort(true);
$errLogin =0;
// disable php time limit
set_time_limit(0);
////////////////////////////////////////////////////////////////////////////////////////

 //$url =  "https://allpaytest.jotangi.com.tw/api/allpay/refund";

 $url =  "https://allpay.jotangi.com.tw/api/allpay/refund";


$orderId = isset($_POST['orderId']) ? $_POST['orderId'] : '';
$amount = isset($_POST['amount']) ? $_POST['amount'] : '';

if($orderId == '' || $amount=='' )
{
	echo "no api parameter";
	exit;
}
 /////////////////////////////////////////////////////////////////////////////////////////////
 /*
 $dbhost = "localhost";
$dbuser = 'jtguser';
$dbpass = 'jtguser2017';
$dbname = 'hpaydemo';
	
$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');
mysql_query("SET NAMES 'utf8'");
mysql_select_db($dbname);

if(isset($_POST['orderId'])) {


	$orderId = isset($_POST['orderId']) ? $_POST['orderId'] : null;
	$sql="SELECT * from  orderinfo where orderid = '$orderId'";
					
	//echo $sql;
	//exit;
	$transactionId = '';
	$result = mysql_query($sql);	
	while($row = mysql_fetch_array($result)) {
		$transactionId = $row['transactionId'];
	}
    if(empty($transactionId) || $transactionId == '') {
        echo "transactionId is required.";
        return;
    }
}
else
{
	echo "no orderId";
	exit;
}	
*/
 
// Ignore user aborts and allow the script to run forever

$time = date("Y/m/d H:i:s");

//$header = "'Channel': 'TAISHIN','AllPayMchID':'888888', 'MchID':'999812666555152',  'TradeKey': '12345678', 'TID':'T0000000', 'TradeKey':'12345678', 'TransTime':$time";
$data = Array();	

$headera = Array();
$headera["Channel"]="TAISHIN";
$headera["AllPayMchID"]="888888";
//$headera["MchID"]="999812666555152";//TEST
$headera["MchID"]=$StoreIdKey;  //"999812770060217";//Formal
$headera["TradeKey"]="12345678";
$headera["TID"]=$SecretKey;   	//"T0000000";
$headera["TransTime"]=$time;
//header('Content-Type: application/json');
$header = json_encode($headera);
$data["StoreOrderNo"]= $orderId;
$data["Amount"]=$amount;
//$data["CurrencyCode"]="NTD";
$data["PaymentType"]="20000";
$data["ResultFlag"] = "0";


//header('Content-Type: application/json');
//echo (json_encode($data, JSON_PRETTY_PRINT));
$jsondata = json_encode($data);
//echo $jsondata;
$requestData = Array();
$requestData["Header"]=$headera;
$requestData["Data"]=$jsondata;
//$requestData = "{".$header.","."Data:".$jsondata."}";
$requestData = json_encode($requestData);
//echo $requestData;

$enc1 = encrypt(base64_decode('Y3UJ147HKIYRT8Ovrsik0A=='), $requestData);

$request = Array();
$request["request"]=$enc1 ;

$requestenc = json_encode($request);
//echo $requestenc;



$server_output = CallAPI("POST", $url, $requestenc);
//echo "server_out:".$server_output;

$ret = explode(":",$server_output);
//echo $ret[1];

$result = decrypt(base64_decode('Y3UJ147HKIYRT8Ovrsik0A=='),$ret[1]);
//echo "\n\n########\n\n";
//echo "result:".$result;
$resultarry = json_decode($result, true);
$hurl = $resultarry["Data"];
$retcode = $resultarry["Header"]["RtnCode"];
//echo $retcode;
if($retcode !=0)
{
	//echo "System Error 0xf1";
	echo "<p>退款失敗! 請務必於信用卡訂單成立一周後,再行退款! (錯誤代碼 0xf1)</p>";
	echo "<p><a href='https://medicalec.jotangi.net/medicalec/main.php'>回健康配後台</a></p>";	
	exit;
}
if($retcode == "0000")
{

	echo "<p>退款成功!!</p>";
	echo "<p><a href='https://medicalec.jotangi.net/medicalec/main.php'>回健康配後台</a></p>";
	//$sql = "update orderinfo set status='9' where orderid = '$orderId' ";
	$sql = "update orderinfo set pay_status='9', order_updated_at = NOW()";
	$sql .= "where order_no='$orderId'";
		
	//$result = mysql_query($sql);
	$result = mysqli_query($link,$sql);							
	
}
else
{
	echo "<p>退款失敗!!</p>";
	echo "<p><a href='https://medicalec.jotangi.net/medicalec/main.php'>回健康配後台</a></p>";
	echo json_encode($result, JSON_PRETTY_PRINT);
}
?>