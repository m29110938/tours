<?php
include_once('../../custom.php');
//s_mid=&ret_code=00&tx_type=1&order_no=No202007280006&ret_msg=交易成功(Approved%20or%20completed%20successfully)
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
}
$orderno = $_GET['orderno'];
$amount = $_GET['amount'];
$currency = "TW";//$request->request->get('currency');
$transactionId = $_GET['orderno'];
$uuid = $_GET['uuid'];
		
$ret_msg = "失敗";
//$ret_code = $_GET['ret_code'];
//$order_no = $_GET['order_no'];
//$ret_msg = $_GET['ret_msg'];
//echo $ret_msg;
if($orderno != '')
{
	//echo "OK";
$dbhost = "localhost";
$dbuser = 'jtguser';
$dbpass = 'jtguser2017';
$dbname = 'member';	
$method = "APPLEPAY";
$statuscode = "1";
	$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');
	mysql_query("SET NAMES 'utf8'");
	mysql_select_db($dbname);
	$sql = "select * from mlcoupon where uuid='".$uuid."'";
	//echo $sql;
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$customerid = $row['tel'];
	$statuscode = "-1";
	$storeid = "01";
	$t=time();
	$time = date("Y-m-d H:i:s",$t);
	$sql = "INSERT INTO orderinfo (customerid, orderno, amount, status, storeid,  updatetime ) VALUES ('$customerid','$orderno', '$amount', '$statuscode', '$storeid', '$time')";
	$result = mysql_query($sql);
	
	$sql = "select * from orderinfo where orderno='$orderno' and status=-1";
	$result = mysql_query($sql);
	$rowCount = mysql_num_rows($result);
	if ($rowCount > 0) {
		$statuscode = "1";
		$sql = "update orderinfo set status='$statuscode', updatetime=NOW(), method='$method'";
		$sql .= "where orderno='$orderno'";
		
		//echo $sql;
		$result = mysql_query($sql);// or die('MySQL query error');		
		$ret_msg = "成功";
		
										//開始給卷號
										$num = intval($amount)/15;
										//begin;select * from hilifecoupon where status=0 for update;update hilifecoupon set status=1 where id=0000001;commit;
										$sql = "begin";
										$result = mysql_query($sql);
										$sql = "select * from hilifecoupon where status=0 order by id";
										$result = mysql_query($sql);
										//$id = array();
										$index = 0;
										$tel = $customerid;
										$tel = mysql_real_escape_string($tel);
										$First = array();
										$First[0]='F';$First[1]='R';$First[2]='A';$First[3]='N';$First[4]='K';$First[5]='W';$First[6]='E';$First[7]='D';
										$Middle = array();
										$Middle[0]='z';$Middle[1]='x';$Middle[2]='c';$Middle[3]='v';$Middle[4]='b';$Middle[5]='n';$Middle[6]='m';$Middle[7]='g';
										$iflag = 0;
										while($row = mysql_fetch_array($result)){
											//$id[$index] = $row['id'];
											$index++;
											
											//$sql = "update hilifecoupon set status=1, transactionId='$transactionId', tel='$tel', price=15 where id=".$row['id'];
											
											$to = rand(0, 7);
											$getsn = $First[$to];
											$getsn .= rand(100000, 999999);
											$to = rand(0, 7);
											$getsn .= $Middle[$to];											
											//1. SMS to notify user
											$url = "http://message.ttinet.com.tw/ensg/3lma236_online.tcl?id=3LMA236&pass=43bHJ2dA&pin=018008508556";
											$url .= "&telno=".$tel;
											//正式
											//$content = "您的咖啡券取貨代碼為".$getsn." 請在苗栗縣商圈活動網址:https://reurl.cc/8GM8Lb 點選已購買咖啡券後輸入，即可取得您的咖啡券。";
											//測試
											$content = "您的咖啡券取貨代碼為".$getsn." 請在".$title."活動網址:".$sms."  點選已購買咖啡券後輸入，即可取得您的咖啡券。";
											$url .= "&cont=".$content;
											$server_output = CallAPI("GET", $url);
											
											//2. update getsn
											$sql = "update hilifecoupon set status=1, transactionId='$transactionId', tel='$tel', price='15', updatetime=NOW(), getsn='$getsn', log='$server_output'  where id=".$row['id'];
											//echo $sql;
											$result1 = mysql_query($sql);
											if(strstr($server_output, "body")==FALSE)
											{
												$iflag = 1;
											}											
											
											if($index >= $num)
												break;
										}
										$sql = "commit;";
										mysql_query($sql);
										$_SESSION['tel']=$tel;
										if($iflag == 1)
										{
												echo "簡訊無法送達, 請聯絡客服EMAIL: jotangi.iot@jotangi.com";
												exit;
										}
										//$url = "Location: https://coupon.jotangi.net:9443/ml/05/hilifelist";
										//header($url);
										//exit;
		
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
<td><H1><?php echo $orderno ; ?></H1></td>
</tr>
<tr>
<td><H1>授權結果</H1></td>
<td><H1><?php echo $ret_msg ; ?></H1></td>
</tr>
</table>
</div>
</html>