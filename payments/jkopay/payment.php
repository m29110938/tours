<?php
//phpinfo();
//session_start();
//include_once('fix_mysql.inc.php');
include_once('preorder.php');

//$ApiKey = "";
//$SecretKey = "";
//$StoreIdKey = "";
		
if (($ApiKey == "")||($SecretKey == "")||($StoreIdKey == ""))  {
		$data["status"]="false";
		$data["code"]="0x0205";
		$data["responseMessage"]="API parameter is required!";
		header('Content-Type: application/json');
		echo (json_encode($data, JSON_UNESCAPED_UNICODE));	
		exit;	
}

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

 //$url = "https://coupon.jotangi.net:9443/hpaydemo/payments/jkopay/confirm.php";

 //$url = "https://tripspottest.jotangi.net/medicalec/payments/jkopay/confirm.php";
 //$urlUI = "https://tripspottest.jotangi.net/medicalec/payments/jkopay/UIconfirm.php";

 $url = "https://medicalec.jotangi.net/medicalec/payments/jkopay/confirm.php";
 $urlUI = "https://medicalec.jotangi.net/medicalec/payments/jkopay/UIconfirm.php";


$prodname=isset($_POST['productName']) ? $_POST['productName'] : '';
if($prodname=='')
{
	echo "system error 0xff";
	exit;
}

$orderno = $_POST['orderId'];
$amount = $_POST['amount2'];

$confirmurl = "https://allpay.jotangi.com.tw/api/allpay/payment";

//$confirmurl = "https://allpaytest.jotangi.com.tw/api/allpay/payment";

/////////////////////////////////////////////////////////////////////////////////////////////
 
 
// Ignore user aborts and allow the script to run forever

$time = date("Y/m/d H:i:s");

//$header = "'Channel': 'TAISHIN','AllPayMchID':'888888', 'MchID':'999812666555152',  'TradeKey': '12345678', 'TID':'T0000000', 'TradeKey':'12345678', 'TransTime':$time";
$data = Array();	

$headera = Array();
$headera["Channel"]="JKOPAY";
$headera["AllPayMchID"]="888888";
//$headera["MchID"]="999812666555152";//TEST
//$headera["MchID"]="000812770025071";//Formal
$headera["MchID"]="999812770060217";//健康配UAT
$headera["TradeKey"]="12345678";
$headera["TID"]="T0000000";
$headera["TransTime"]=$time;
//header('Content-Type: application/json');
$header = json_encode($headera);
$data["StoreOrderNo"]= $orderno;
$data["Amount"]=$amount;
$data["CurrencyCode"]="NTD";
$data["PaymentType"]="11700";//JKOPAY
$data["OrderDesc"]="健康配";
$data["CurrencyCode"]="NTD";
$data["products"][0]["name"]="健康配";
$data["products"][0]["quantity"]=1;
$data["products"][0]["price"]=intval($amount);
//$data["OrderResultURL"]= $url;
$data["ReturnURL"]=$url;
$data["ClientBackURL"]=$urlUI;  //https://coupon.jotangi.net:9443/reg/01/index";
$data["AuthType"]="0";
$data["CaptFlag"] = "1";
$data["ResultFlag"] = "0";

//$data["capture"]=true;
//$data["CardID"] = $cardno;
//$data["ExtenNo"] = $extno;
//$data["ExpireDate"] = $expire;
//$data["ExpDate "] = $expire;

//TEST
//$data["ApiKey"]="8JWgAOwrnMHNqjPXopnh7g8exk7PaeY4hJpQ";
//$data["SecretKey"]="0MalaxOxURfuhKEs3zZyf2wpRy5lFO8w0rqCu1_U5gTDF-OA8WT5veUzpSTuiqtCw9CUkOhr3-drv36u3_h1Ow";
//$data["StoreId"]="01c236c6-aeda-11ea-948d-0050568403ed";

//Formal
//$data["ApiKey"]="5iOPRAoNyicseJB80eNk8qLM5b3c0VPQSSt6";
//$data["SecretKey"]="6Zt4iXBIeGdrxF5BveCNqsyEoC37-0zJd1m_CiqchZPOCfGCNIy5my3cTAmWhCYzaMnPuX4lltp6HqjDV2c9nw";
//$data["StoreId"]="a4e169bf-e904-11ea-a74b-f8f21e0d1b98";

//UAT for 健康配
//$data["ApiKey"]="55fb5e8ebcde7ac22c714a14e34d0c916b867ef222926830a4eb5b5cdbb7009f";
//$data["SecretKey"]="43f57457004403d02ab894b57ad9a73c7be0c5ba09df1d368c5af1421f846c92";
//$data["StoreId"]="a08a9046-29b8-11ec-bd7b-0050568403ed";

$data["ApiKey"]=$ApiKey;
$data["SecretKey"]=$SecretKey;
$data["StoreId"]=$StoreIdKey;

//echo $ApiKey;
//echo $SecretKey;
//echo $StoreIdKey;
//exit;

$data["DeviceOS"]="3";

//header('Content-Type: application/json');
//echo (json_encode($data, JSON_PRETTY_PRINT));
$jsondata = json_encode($data);
echo $jsondata;
$requestData = Array();
$requestData["Header"]=$headera;
$requestData["Data"]=$jsondata;
//$requestData = "{".$header.","."Data:".$jsondata."}";
$requestData = json_encode($requestData);
echo $requestData;

$enc1 = encrypt(base64_decode('Y3UJ147HKIYRT8Ovrsik0A=='), $requestData);

$request = Array();
$request["request"]=$enc1 ;

$requestenc = json_encode($request);
//echo $requestenc;

$server_output = CallAPI("POST", $confirmurl, $requestenc);
$ret = explode(":",$server_output);
//echo $ret[1];

$result = decrypt(base64_decode('Y3UJ147HKIYRT8Ovrsik0A=='),$ret[1]);
//echo "\n\n########\n\n";
echo $result;
$resultarry = json_decode($result, true);
$hurl = $resultarry["Data"];
echo $hurl;
$retcode = $resultarry["Header"]["RtnCode"];
echo $retcode;
if($retcode !=0)
{
	echo "System Error 0xf1";
	exit;
}

//echo "System Error 0xf11";

$ret = json_decode($hurl, true);
$url = "Location: ".$ret["PaymentURL"];
//["HppURL"];
//echo $url;
//exit;
//$sql = "INSERT INTO orderinfo (order_id, amount, status, storeid,  updatetime ) VALUES ('$customerid','$orderno', '$amount', '$statuscode', '$storeid', '$time')";
//$sql = "UPDATE orderinfo SET status=-1, amount = ".$amount." , updatetime = NOW() where orderid='".$orderno."'";
//echo $sql;
//$result = mysql_query($sql);// or die('MySQL query error');								


header($url);

?>