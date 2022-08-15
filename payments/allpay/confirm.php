<!DOCTYPE html>
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

$ret_code = $_GET['ret_code'];
$order_no = $_GET['order_no'];
$ret_msg = $_GET['ret_msg'];
echo $ret_msg;
$transactionId = $ret_code;
$ret_msg = "失敗";
if($ret_code=="00")
{

	$host = 'localhost';
	$user = 'medicalec_user';
	$passwd = 'medicalec211013';
	$database = 'medicalecdb';	

	$link = mysqli_connect($host, $user, $passwd, $database);
	mysqli_query($link,"SET NAMES 'utf8'");
	
//$dbhost = "localhost";
//$dbuser = 'jtguser';
//$dbpass = 'jtguser2017';
//$dbname = 'hpaydemo';

$method = "TAISHIN_CREDIT_CARD";
$statuscode = "1";
	//$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');
	//mysql_query("SET NAMES 'utf8'");
	//mysql_select_db($dbname);

					
	$sql = "select * from orderinfo where order_no='$order_no' and pay_status=-1";
	//echo $sql;
	$result = mysqli_query($link,$sql); 
	//$result = mysql_query($sql);
	$rowCount = mysqli_num_rows($result);  
	//$rowCount = mysql_num_rows($result);
	$row = mysqli_fetch_array($result);
	//$row = mysql_fetch_array($result);

	
	if ($rowCount > 0) {
		$sql = "update orderinfo set pay_status='$statuscode', order_updated_at = NOW()";
		$sql .= "where order_no='$order_no'";
		
		//echo $sql;
		//$result = mysql_query($sql);// or die('MySQL query error');	
		$result = mysqli_query($link,$sql); 		
		$ret_msg = "成功";				
	}
	else
	{
		echo "System Error 0x01!!";
		exit;
	}
	//$sql = "select * from orderinfo where orderno='$order_no' and status=1";
	//$result = mysql_query($sql);// or die('MySQL query error');								
	
}
?>
<html>
<div>
<table border="1">
<tr>
<td colspan="2" style="text-align: center; vertical-align: middle;"><H1>付款結果</H1></td>
</tr>
<tr>
	<td><H1>訂單編號</H1></td>
	<td><H1><?php echo $order_no ; ?></H1></td>
</tr>
<tr>
	<td><H1>授權結果</H1></td>
	<td><H1><?php echo $ret_msg ; ?></H1></td>
</tr>
<tr>
	<td colspan='2' align='center'><a href='hpay://payment?url=http://xxx.xx/'>回健康配</a></td>
</tr>
</table>
<?php if ($ret_msg == "成功") { ?>
	<SCRIPT LANGUAGE=javascript>
	<!--
	var orderno = "<?php echo $order_no ; ?>";
	var timer = setTimeout(function() {
			window.location = "../comfirmorder.php?orderno="+orderno;
	}, 1000);	
	//-->
	</SCRIPT>
<?php } ?>
</div>
</html>