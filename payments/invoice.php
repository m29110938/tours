<?php
session_start();
$amount = $_GET['amount'];
$quantity = $_GET['quantity'];
$name = $_GET['name'];
$orderno = $_GET['orderno'];

$amount = mysql_real_escape_string($amount);
$quantity = mysql_real_escape_string($quantity);
$name = mysql_real_escape_string($name);
$orderno = mysql_real_escape_string($orderno);
wh_log("invoice begin");
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
			
			//curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/form-data'));
			curl_setopt($ch, CURLOPT_HEADER, false);
			//curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
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
function array2csv($data, $delimiter = ',', $enclosure = '"', $escape_char = "\\")
{
    //$f = fopen('php://memory', 'r+');
	$f = fopen('mycsv', 'a');
    foreach ($data as $item) {
		//echo $item;
        fputcsv($f, $item, $delimiter, $enclosure, $escape_char);
    }
   // rewind($f);
   fclose($f);
    //return stream_get_contents($f);
}

// Ignore user aborts and allow the script to run forever
ignore_user_abort(true);
 
// disable php time limit
set_time_limit(0);


//$url = "https://api-test.cxn.com.tw/";
$url = "https://api.cxn.com.tw/";
//get track_list.php
$url_track = $url;
$url_track .= "get_track_list.php";

$url_c0401 = $url;
$url_c0401 .= "c0401.php";

$t=time(); 
$time = date("m",$t);
$date = date("d", $t);
if($time == "1" || $time == "2")
	$period = 0;
if($time == "3" || $time == "4")
	$period = 1;
if($time == "5" || $time == "6")
	$period = 2;
if($time == "7" || $time == "8")
	$period = 3;
if($time == "9" || $time == "10")
	$period = 4;
if($time == "11" || $time == "12")
	$period = 5;
$year = date("Y",$t);
/*
$data = array("id"=>"53758995", 
			  "user"=>"admin",
			  "passwd"=> "53758995",
			  "year"=>$year,
			  "period"=>$period,
			  "size"=>"1"
			  );
*/
$data = array("id"=>"53758995", 
			  "user"=>"53758995",
			  "passwd"=> "1qaz2wsx",
			  "year"=>$year,
			  "period"=>$period,
			  "size"=>"1"
			  );

echo $url_track;
$server_output = CallAPI("POST", $url_track, $data);
echo  $server_output;
wh_log($server_output);
//$out = json_decode($server_output, true);
$out = explode("&", $server_output);
//$ret = explode("=", $out);
if ($out[0]= "rtcode=0000")
{
	$info = $out[2];
	$ret = explode("=",$info);
	
	$ret = explode(",",$ret[1]);
	$InvoiceNumber = $ret[0];
	$InvoiceNumber .= $ret[1];
	echo "invoice:".$InvoiceNumber;
	wh_log("invoice:".$InvoiceNumber);
}

//==========================
//"SerailNumber, "InvoiceNumber ","InvoiceDate","InvoiceTime","BuyerIdentifier","BuyerName","BuyerAddress",
// "BuyerTelephoneNumber","BuyerEmailAddress","SalesAmount","FreeTaxSalesAmount",
//"ZeroTaxSalesAmount","TaxType","TaxRate","TaxAmount","TotalAmount","PrintMark","RandomNumber",
//"CustomsClearanceMark","MainRemark","CheckNumber", "BuyerRemark", "Catergory", "RelateNumber", "GroupMark", "DonateMark",
//BonderAreaConfirm, DiscountAmount, OriginalCurrencyAmount, ExchangeRate, Currency,
// "CarrierType","CarrierId1","NPOBAN","Description","Quantity","UnitPrice","Amount","Remark", "Unit", DetailRelateNumber"
$SerailNumber = "";
$InvoiceNumber = $InvoiceNumber;
$InvoiceDate =  date("Ymd",$t);
$InvoiceTime = date("H:i:s", $t);
$BuyerIdentifier = '';
$BuyerAddress = '';
$BuyerName = $name;
$BuyerTelephoneNumber = '';
$BuyerEmailAddress = '';
$SalesAmount = $amount;
$FreeTaxSalesAmount = "0";
$ZeroTaxSalesAmount = "0";
$TaxType = "1";
$TaxRate = "0.05";
$TaxAmount = "0";//沒有打統編
$TotalAmount = $amount;
$PrintMark = "N";
$RandomNumber ="";
$CustomsClearanceMark = "";
$MainRemark = "Jtg";
$CheckNumber ="";
$BuyerRemark = "";
$Catergory = "";
$RelateNumber = $GroupMark = $DonateMark = $BonderAreaConfirm=$DiscountAmount=$OriginalCurrencyAmount=$ExchangeRate=$Currency = "";
$CarrierType = "";
$CarrierId1 = "";
$CarrierId2 = "";
$NPOBAN = "";
$Description = "Jtg";
$Quantity = $quantity;
$UnitPrice = $amount;
$Amount = $amount;
$Remark = "";
$DetailRelateNumber = "";
$list = array (
    ["InvoiceNumber", "InvoiceDate", "InvoiceTime", "BuyerIdentifier", "BuyerName", "BuyerAddress", "BuyerTelephoneNumber","BuyerEmailAddress" ,
	 "SalesAmount", "FreeTaxSalesAmount", "ZeroTaxSalesAmount", "TaxType", "TaxRate", "TaxAmount",
	"TotalAmount", "PrintMark", "RandomNumber", "MainRemark", 
	"CarrierType", "CarrierId1","CarrierId2", "NPOBAN","Description", "Quantity", "UnitPrice",  "Amount", "Remark" ],
	
    [$InvoiceNumber,  $InvoiceDate, $InvoiceTime, $BuyerIdentifier,$BuyerName, $BuyerAddress, $BuyerTelephoneNumber,$BuyerEmailAddress ,
	$SalesAmount, $FreeTaxSalesAmount, $ZeroTaxSalesAmount, $TaxType, $TaxRate, $TaxAmount,
	$TotalAmount, $PrintMark, $RandomNumber, $MainRemark,
	$CarrierType, $CarrierId1,$CarrierId2, $NPOBAN,$Description, $Quantity, $UnitPrice,  $Amount, $Remark ],
	['Finish']
);

$sn = date("YmdHis", $t);
$rand = rand(100, 200);
$filename = "/var/www/html/reg/01/payments/invoice/".$sn.$rand.".csv";
$f = fopen($filename, 'w'); // Configure fOpen to create, open and write only.
//fputcsv($f, array_keys($list[0])); // Add the keys as the column headers
// Loop over the array and passing in the values only.
foreach ($list as $row)
{
    fputcsv($f, $row);
}

// Close the file.
fclose($f);

//array2csv($list);
echo "\n\n\n\n\n";

//echo var_dump($csv);
//var_dump(array2csv($list));

/*
$data  = array("id"=>"53758995", 
			  "user"=>"admin",
			  "passwd"=> "53758995",
			  "csv" => base64_encode(file_get_contents($filename))
			  );
*/
			  
$data  = array("id"=>"53758995", 
			  "user"=>"53758995",
			  "passwd"=> "1qaz2wsx",
			  "csv" => base64_encode(file_get_contents($filename))
			  );
			  
$server_output = CallAPI("POST", $url_c0401, $data);
echo  $server_output;
wh_log($server_output);
$out = explode("&", $server_output);

$dbhost = "localhost";
$dbuser = 'jtguser';
$dbpass = 'jtguser2017';
$dbname = 'learningdb';
$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');
mysql_query("SET NAMES 'utf8'");
mysql_select_db($dbname);

if ($out[0]== "rtcode=0000")
{
	//insert invoice number into DB
					$sql = "update orderinfo SET invoice='".$InvoiceNumber."'  where order_id='".$orderno."'";
					echo $sql;
					$result = mysql_query($sql) or die('MySQL query error');
				//	echo "<SCRIPT>alert('已開立完成')</SCRIPT>";
					//$url = "Location: device.php";
					//header($url);
}
else
{
//	echo "<SCRIPT>alert('開立失敗')</SCRIPT>";
					$sql = "update orderinfo SET invoice='-1'  where order_id='".$orderno."'";
					echo $sql;
					$result = mysql_query($sql) or die('MySQL query error');
}
?>