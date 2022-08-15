<?php
//phpinfo();
session_start();
include_once('preorder.php');

if (($SecretKey == "")||($StoreIdKey == ""))  {
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

 //$url = "https://coupon.jotangi.net:9443/hpaydemo/payments/allpay/confirm.php";

 //$url = "https://tripspottest.jotangi.net/medicalec/payments/allpay/confirm.php";

 $url = "https://medicalec.jotangi.net/medicalec/payments/allpay/confirm.php";

$card1 = $_POST['creditnumber1'];
$card2 = $_POST['creditnumber2'];
$card3 = $_POST['creditnumber3'];
$card4 = $_POST['creditnumber4'];

$cardno = $card1.$card2.$card3.$card4;//"4162050100018705";//$_POST['creditnumber1'];
$extno = $_POST['extnumber'];//"871";//
$expire = $_POST['expiredateYear'].$_POST['expiredateMonth'];//"3509";//
$ci = $_SESSION['ci'];
$prodname=isset($_POST['productName']) ? $_POST['productName'] : '';
if($prodname=='')
{
	echo "system error 0xff";
	exit;
}
$orderno = $_POST['orderId'];
$amount = $_POST['amount1'];

 //$confirmurl = "https://allpaytest.jotangi.com.tw/api/allpay/payment";

 $confirmurl = "https://allpay.jotangi.com.tw/api/allpay/payment";

 /////////////////////////////////////////////////////////////////////////////////////////////

 
// Ignore user aborts and allow the script to run forever

$time = date("Y/m/d H:i:s");

//$header = "'Channel': 'TAISHIN','AllPayMchID':'888888', 'MchID':'999812666555152',  'TradeKey': '12345678', 'TID':'T0000000', 'TradeKey':'12345678', 'TransTime':$time";
$data = Array();	

$headera = Array();
$headera["Channel"]="TAISHIN";
$headera["AllPayMchID"]="888888";
//$headera["MchID"]="999812666555152";//TEST
$headera["MchID"]=$StoreIdKey; //"999812770060217";//Formal
$headera["TradeKey"]="12345678";
$headera["TID"]=$SecretKey;    //"T0000000";
$headera["TransTime"]=$time;
//header('Content-Type: application/json');
$header = json_encode($headera);
$data["StoreOrderNo"]= $orderno;
$data["Amount"]=$amount;
$data["CurrencyCode"]="NTD";
$data["PaymentType"]="20000";
$data["OrderDesc"]="健康配";
$data["CurrencyCode"]="NTD";
$data["products"][0]["name"]="健康配-診療費";
$data["products"][0]["quantity"]=1;
$data["products"][0]["price"]=intval($amount);
$data["OrderResultURL"]= $url;
$data["ReturnURL"]=$url;
$data["ClientBackURL"]=$url;
$data["AuthType"]="1";
$data["CaptFlag"] = "1";
$data["ResultFlag"] = "0";
//$data["capture"]=true;
/*
$data["CardID"] = $cardno;
$data["ExtenNo"] = $extno;

$data["ExpireDate"] = $expire;
//$data["ExpDate "] = $expire;
*/
$data["DeviceOS"]="3";

//header('Content-Type: application/json');
//echo (json_encode($data, JSON_PRETTY_PRINT));
$jsondata = json_encode($data);
//echo $jsondata;
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
$retcode = $resultarry["Header"]["RtnCode"];
echo $retcode;
if($retcode !=0)
{
	echo "System Error 0xf1";
	exit;
}
//echo "System Error 0xf11";
//echo $hurl;

//$url = "Location: https://coupon.jotangi.net:9443/ml/05/hilifelist";
//header($url);

$ret = json_decode($hurl, true);
$url = "Location: ".$ret["HppURL"];
//["HppURL"];
//echo $url;
header($url);

?>