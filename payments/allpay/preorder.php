<?php						
$uuid = $_SESSION['uuid'];

//write into MYSQL DB

//$dbhost = "localhost";
//$dbuser = 'jtguser';
//$dbpass = 'jtguser2017';
//$dbname = 'hpaydemo';

//$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');
//mysql_query("SET NAMES 'utf8'");
//mysql_select_db($dbname);

	$host = 'localhost';
	$user = 'medicalec_user';
	$passwd = 'medicalec211013';
	$database = 'medicalecdb';	

	$link = mysqli_connect($host, $user, $passwd, $database);
	mysqli_query($link,"SET NAMES 'utf8'");
	
$amount = $_POST['amount1'];
$orderno = $_POST['orderId'];
$cardno = "********";
$cardno .= $_POST['creditnumber4'];

$statuscode = -1;//-1:待付款, 1: 已付款, 2:已出貨
$t=time();
$time = date("Y-m-d H:i:s",$t);
//$sql = "UPDATE orderinfo SET payment_status='-1', total_pay = ".$amount." , mcardno = '".$cardno."' , order_created_at = NOW() where order_id='".$orderno."'";
//$sql = "UPDATE orderinfo SET status='-1', amount = ".$amount." , updatetime = NOW() where orderid=".$orderno;

$sql="UPDATE orderinfo SET pay_status=-1,pay_type=2,order_pay = ".$amount." , order_updated_at = NOW() where order_no='".$orderno."'";

//echo $sql;
//$result = mysql_query($sql);// or die('MySQL query error');								
$result = mysqli_query($link,$sql);							

$sid = isset($_POST['sid']) ? $_POST['sid'] : '';
$mid = isset($_POST['mid']) ? $_POST['mid'] : '';
	
if (($sid != '') && ($mid != '')) {	
	$sql = "SELECT * FROM payment where payment_trash=0 and store_id=$sid and payment_type='3' and  payment_status=1 ";
	if ($result = mysqli_query($link, $sql)){
		if (mysqli_num_rows($result) > 0){
			//$mid=0;
			$store_name = "";
			while($row = mysqli_fetch_array($result)){
				$ApiKey = $row['api_key'];
				$SecretKey = $row['secret_key'];
				$StoreIdKey = $row['storeid_key'];
				//$data["ApiKey"]="55fb5e8ebcde7ac22c714a14e34d0c916b867ef222926830a4eb5b5cdbb7009f";
				//$data["SecretKey"]="43f57457004403d02ab894b57ad9a73c7be0c5ba09df1d368c5af1421f846c92";
				//$data["StoreId"]="a08a9046-29b8-11ec-bd7b-0050568403ed";				
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
?>