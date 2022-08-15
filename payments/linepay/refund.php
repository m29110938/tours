<?php 
// Back-end for refund and record
require_once("Chinwei6_LinePay.php");
//include('./blocks/api_basic_info.php');

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
	$sql = "SELECT * FROM payment where payment_trash=0 and store_id=$sid and payment_type='1' and  payment_status=1 ";
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

if(isset($_POST['orderId'])) {


	$orderId = isset($_POST['orderId']) ? $_POST['orderId'] : null;
	$sql="SELECT * from  orderinfo where order_no = '$orderId'";
					
	//echo $sql;
	//exit;
							
	$transactionId = '';
	if ($result = mysqli_query($link, $sql)){
		if (mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_array($result)){
				$transactionId = $row['transactionId'];
				break;
			}
		}		
	}
    if(empty($transactionId) || $transactionId == '') {
        echo "transactionId is required.";
        return;
    }
	//echo $transactionId;
    //$apiEndpoint   = $apiEndpoint;
    //$channelId     = $channelId;
    //$channelSecret = $channelSecret;

	$apiEndpoint = "https://api-pay.line.me/v2/payments/";
	$channelId = $StoreIdKey;   	//"1653555191";
	$channelSecret = $SecretKey;   	//"b5f7f10f010adaffcffe089be1b8b9dd";
    
    $params = [
        "refundAmount" => isset($_POST['refundAmount']) ? $_POST['refundAmount'] : null,
    ];

    try {
        $LinePay = new Chinwei6\LinePay($apiEndpoint, $channelId, $channelSecret);
        $result = $LinePay->refund($transactionId, $params);
        //echo json_encode($result, JSON_PRETTY_PRINT);
		//{ "returnCode": "0000", "returnMessage": "Success.", "info": { "refundTransactionId": 2021122441892811811, "refundTransactionDate": "2021-12-24T05:53:52Z" } }
		
		if($result['returnCode'] == "0000")
		{
			echo "<p>退款成功!!</p>";
			echo "<p><a href='https://medicalec.jotangi.net/medicalec/main.php'>回健康配後台</a></p>";	
			$sql = "update orderinfo set pay_status='9', order_updated_at = NOW()";
			$sql .= "where order_no='$orderId'";
			$result = mysqli_query($link,$sql);
		}
		else
		{
			echo "<p>退款失敗!!</p>";
			echo "<p><a href='https://medicalec.jotangi.net/medicalec/main.php'>回健康配後台</a></p>";	
			echo json_encode($result, JSON_PRETTY_PRINT);
		}
		
    }
    catch(Exception $e) {
        echo $e->getMessage();
    }

}
?>