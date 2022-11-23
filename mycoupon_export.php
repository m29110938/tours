<?php
// session_start();
// include("db_tools.php");
$host = 'localhost';
$user = 'tours_user';
$passwd = 'tours0115';
$database = 'toursdb';

$link = mysqli_connect($host, $user, $passwd, $database);
mysqli_query($link,"SET NAMES 'utf8'");


$staus = isset($_POST['staus']) ? $_POST['staus'] : '';
$staus  = mysqli_real_escape_string($link,$staus);
$coupondiscount = isset($_POST['coupondiscount']) ? $_POST['coupondiscount'] : '';
$coupondiscount  = mysqli_real_escape_string($link,$coupondiscount);
$memberid = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$memberid  = mysqli_real_escape_string($link,$memberid);

$couponno = isset($_POST['couponno']) ? $_POST['couponno'] : '';
$couponno  = mysqli_real_escape_string($link,$couponno);		
$couponname = isset($_POST['couponname']) ? $_POST['couponname'] : '';
$couponname  = mysqli_real_escape_string($link,$couponname);

$shopping_area = isset($_POST['shopping_area']) ? $_POST['shopping_area'] : '';
$shopping_area  = mysqli_real_escape_string($link,$shopping_area);
$membername = isset($_POST['membername']) ? $_POST['membername'] : '';
$membername  = mysqli_real_escape_string($link,$membername);
$storename = isset($_POST['storename']) ? $_POST['storename'] : '';
$storename  = mysqli_real_escape_string($link,$storename);			
$SDate = isset($_POST['SDate']) ? $_POST['SDate'] : '';
$SDate  = mysqli_real_escape_string($link,$SDate);
$EDate = isset($_POST['EDate']) ? $_POST['EDate'] : '';
$EDate  = mysqli_real_escape_string($link,$EDate);
$couponSDate = isset($_POST['couponSDate']) ? $_POST['couponSDate'] : '';
$couponSDate  = mysqli_real_escape_string($link,$couponSDate);
$couponEDate = isset($_POST['couponEDate']) ? $_POST['couponEDate'] : '';
$couponEDate  = mysqli_real_escape_string($link,$couponEDate);

// echo $couponno;
// header("Content-type: text/html; charset=utf-8");
// header("Content-type:application/vnd.ms-excel;charset=UTF-8");
// header("Content-Disposition:filename=member.csv"); //輸出的表格名稱
// header('Content-Type: application/xls');
// header('Content-Disposition: attachment; filename=info.xls');
// echo "店家\t";echo "帳號\t";echo "姓名\t";echo "性別\t";echo "手機\t";echo "紅利點數\t";echo "註冊日期\t";echo "狀態\t\n";

// echo "訂單時間\t";echo "訂單金額\t";echo "訂單編號\t";echo "實付金額\t";echo "付款狀態\t";echo "訂單狀態\t";echo "收件人姓名\t";echo "身分證後4碼\t";echo "取貨時間\t";echo "手機號碼\t";echo "E-MAIL\t";echo "商品名稱\t";echo "價格\t";echo "數量\t";echo "小計\t\n";
//這是表格頭欄位 加\T就是換格,加\T\N就是結束這一行,換行的意思
// $conn = mysqli_connect("localhost","使用者名稱","密碼") or die("不能連線資料庫");
// mysqli_select_db("資料庫名", $conn);
// mysqli_query("set names 'UTF-8'");
// $sql="SELECT * FROM ecorderinfo a ";
// $sql = $sql." where a.oid>0 ";
// $sql = $sql." order by a.order_date ";

$sql = "SELECT a.*,b.member_name,b.member_id, c.store_name, d.shopping_area FROM mycoupon as a ";
$sql = $sql." inner join ( select mid,member_name,member_id from member) as b ON a.mid= b.mid ";
$sql = $sql." inner join ( select sid,store_name,shopping_area from store) c on a.coupon_storeid = c.sid ";
$sql = $sql." inner join ( select aid,shopping_area from shopping_area) d on d.aid = c.shopping_area ";
$sql = $sql." where a.mycoupon_trash=0 ";

if ($staus != "") {	
    $sql = $sql." and a.using_flag = ".$staus."";
}
if ($coupondiscount != "") {	
    $sql = $sql." and a.coupon_discount = ".$coupondiscount."";
}	
if ($memberid != "") {	
    $sql = $sql." and b.member_id = ".$memberid."";
}				
if (trim($couponno) != "") {	
    $sql = $sql." and a.coupon_no like '%".trim($couponno)."%'";
}
if (trim($couponname) != "") {	
    $sql = $sql." and a.coupon_name like '%".trim($couponname)."%'";
}
if (trim($membername) != "") {	
    $sql = $sql." and b.member_name like '%".trim($membername)."%'";
}	
if (trim($storename) != "") {	
    $sql = $sql." and c.store_name like '%".trim($storename)."%'";
}		
if (trim($shopping_area) != "") {	
    $sql = $sql." and d.aid=".$shopping_area."";
}	
if ($SDate != "") {	
    $sql = $sql." and a.using_date >= '".$SDate." 00:00:00'";
}
if ($EDate != "") {	
    $sql = $sql." and a.using_date <= '".$EDate." 23:59:59'";
}		
if ($couponSDate != "") {	
    $sql = $sql." and a.mycoupon_created_at >= '".$couponSDate." 00:00:00'";
}
if ($couponEDate != "") {	
    $sql = $sql." and a.mycoupon_created_at <= '".$couponEDate." 23:59:59'";
}			
$sql = $sql." order by a.coupon_no ";

// echo $sql;

$result = mysqli_query($link, $sql);

$export = "";
$export .= '
<table> 
<tr> 
<th>姓名</th>
<th>手機號碼</th>
<th>票券號碼</th> 
<th>票券名稱</th> 
<th>適用店家</th> 
<th>商圈分類</th>
<th>折扣方式</th>
<th>到期日期</th> 
<th>領取日期</th> 
<th>使用日期</th> 
<th>使用狀態</th>
</tr>
';

while($row=mysqli_fetch_array($result)){
    
    
    
    switch ($row['coupon_discount']) {
        case 1:
            $coupon_discount = "折扣金額";
            break;
        case 2:
            $coupon_discount = "折扣%";
            break;
        default:
        $coupon_discount = "";
    }	
    switch ($row['using_flag']) {
        case 1:
            $using_flag =  "已使用";
            break;
        case 0:
            $using_flag =  "未使用";
            break;
        default:
            $using_flag =  "未使用";
    }
    $export .= '
    <tr>
        <td>'.$row["member_name"].'</td> 
        <td style="vnd.ms-excel.numberformat:@">'.$row["member_id"].'</td> 
        <td>'.$row["coupon_no"].'</td> 
        <td>'.$row["coupon_name"].'</td> 
        <td>'.$row["store_name"].'</td> 
        <td>'.$row["shopping_area"].'</td> 
        <td>'.$coupon_discount.'</td> 
        <td>'.date('Y-m-d', strtotime($row['coupon_enddate'])).'</td> 
        <td>'.$row['mycoupon_created_at'].'</td> 
        <td>'.$row['using_date'].'</td> 
        <td>'.$using_flag.'</td>
    </tr>
    ';

}
$export .= '</table>';
$today = date('Ymd');
$filename = "mycoupon_".$today.".xls";
header('Content-Type: application/xls');
header('Content-Disposition: attachment; filename='.$filename);
echo $export;
?>