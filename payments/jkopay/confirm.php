<?php

//s_mid=&ret_code=00&tx_type=1&order_no=No202007280006&ret_msg=交易成功(Approved%20or%20completed%20successfully)
function wh_log($log_msg)
{
    $log_filename = "./log";
    if (!file_exists($log_filename)) 
    {
        // create directory/folder uploads.
        mkdir($log_filename, 0777, true);
    }
    $log_file_data = $log_filename.'/log_members_' . date('d-M-Y') . '.log';
    // if you don't add `FILE_APPEND`, the file will be erased each time you add a log
    file_put_contents($log_file_data, date("Y-m-d H:i:s")."  ------  ".$log_msg . "\n", FILE_APPEND);
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
//$log = "confirm";
//wh_log($log);
		$inputJSON = file_get_contents('php://input', 'r');
		$input = json_decode($inputJSON, TRUE); //convert JSON into array
$log = "input:".$inputJSON;
wh_log($log);

//echo $inputJSON;
$ret_msg = "失敗";
if($input['transaction']['status']==0)
{
	$transactionId = $input['transaction']['platform_order_id'];
	$order_no = $transactionId;
	$log = "order_no:".$order_no;
	wh_log($log);

	$host = 'localhost';
	$user = 'medicalec_user';
	$passwd = 'medicalec211013';
	$database = 'medicalecdb';

	$link = mysqli_connect($host, $user, $passwd, $database);
	mysqli_query($link,"SET NAMES 'utf8'");	

	$method = "JKOPAY";
	$statuscode = "1";

	$sql = "SELECT * FROM orderinfo where order_no='$order_no' and pay_status=-1 ";
	if ($result = mysqli_query($link, $sql)){
		if (mysqli_num_rows($result) > 0){

			$sql="UPDATE orderinfo SET pay_status='$statuscode',pay_type=4,order_updated_at = NOW() where order_no='$order_no'";

			//echo $sql;
			//exit;
			$result = mysqli_query($link,$sql); //or die(mysqli_error($link));
			wh_log($sql);
			$ret_msg = "成功";
			echo "1|OK";
		}	
		else
		{
			echo "System Error 0x01!!";
			exit;
		}
	}	
}

/*
if(intval($ret_code)==0)
{
	//echo "OK";
$dbhost = "localhost";
$dbuser = 'jtguser';
$dbpass = 'jtguser2017';
$dbname = 'member';	
$method = "TAISHIN_CREDIT_CARD";
$statuscode = "1";
	$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');
	mysql_query("SET NAMES 'utf8'");
	mysql_select_db($dbname);

	$sql = "select * from orderinfo where orderno='$order_no' and status=-1";
	$result = mysql_query($sql);
	$rowCount = mysql_num_rows($result);
	if ($rowCount > 0) {
	
		$sql = "update orderinfo set status='$statuscode', updatetime=NOW(), method='$method'";
		$sql .= "where orderno='$order_no'";
		
		//echo $sql;
		$result = mysql_query($sql);// or die('MySQL query error');		
		

		
	}
	//$sql = "select * from orderinfo where orderno='$order_no' and status=1";
	//$result = mysql_query($sql);// or die('MySQL query error');								
	
}
*/

?>
