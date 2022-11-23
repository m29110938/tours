<?php
// session_start();
// include("db_tools.php");
$host = 'localhost';
$user = 'tours_user';
$passwd = 'tours0115';
$database = 'toursdb';

$link = mysqli_connect($host, $user, $passwd, $database);
mysqli_query($link,"SET NAMES 'utf8'");

$loginsid = isset($_POST['loginsid']) ? $_POST['loginsid'] : '';
$authority = isset($_POST['authority']) ? $_POST['authority'] : '';
$staus = isset($_POST['staus']) ? $_POST['staus'] : '';
$staus  = mysqli_real_escape_string($link,$staus);
$gender = isset($_POST['gender']) ? $_POST['gender'] : '';
$gender  = mysqli_real_escape_string($link,$gender);


$mobile = isset($_POST['mobile']) ? $_POST['mobile'] : '';
$mobile  = mysqli_real_escape_string($link,$mobile);
$memberid = isset($_POST['memberid']) ? $_POST['memberid'] : '';
$memberid  = mysqli_real_escape_string($link,$memberid);			
$membername = isset($_POST['membername']) ? $_POST['membername'] : '';
$membername  = mysqli_real_escape_string($link,$membername);

$SDate = isset($_POST['SDate']) ? $_POST['SDate'] : '';
$SDate  = mysqli_real_escape_string($link,$SDate);
$EDate = isset($_POST['EDate']) ? $_POST['EDate'] : '';
$EDate  = mysqli_real_escape_string($link,$EDate);

$shopping_area = isset($_POST['shopping_area']) ? $_POST['shopping_area'] : '';
$shopping_area  = mysqli_real_escape_string($link,$shopping_area);	
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

$sql = "SELECT a.*,b.rid,b.store_id,b.member_id as memberid,b.member_date,b.card_type,b.membercard_status,b.membercard_trash,c.store_name,d.shopping_area FROM member as a ";
$sql = $sql." inner join ( select rid,store_id,member_id,member_date,card_type,membercard_status,membercard_trash from membercard) as b ON a.mid= b.member_id ";
$sql = $sql." inner join ( select sid,store_name,shopping_area from store) as c on b.store_id = c.sid ";
$sql = $sql." inner join ( select aid,shopping_area from shopping_area) as d on c.shopping_area = d.aid ";
$sql = $sql." where a.member_trash=0 and b.membercard_trash=0";
//$sql = "SELECT * FROM member where member_trash=0 ";
if ($authority=="4"){
    $sql = $sql." and b.store_id=".$loginsid."";
}					
if ($gender != "") {	
    $sql = $sql." and a.member_gender=".$gender."";
}
if ($staus != "") {	
    $sql = $sql." and b.membercard_status='".$staus."'";
}					
if ($mobile != "") {	
    $sql = $sql." and a.member_phone like '%".$mobile."%'";
}
if (trim($memberid) != "") {	
    $sql = $sql." and a.member_id like '%".trim($memberid)."%'";
}
if (trim($membername) != "") {	
    $sql = $sql." and a.member_name like '%".trim($membername)."%'";
}	

if ($SDate != "") {	
    $sql = $sql." and b.member_date >= '".$SDate." 00:00:00'";
}
if ($EDate != "") {	
    $sql = $sql." and b.member_date <= '".$EDate." 23:59:59'";
}				
if ($shopping_area != "") {	
    $sql = $sql." and c.shopping_area=".$shopping_area."";
}			
$sql = $sql." order by b.store_id,a.member_id ";
// echo $sql;
$result = mysqli_query($link, $sql);

$export = "";
$export .= '
<table> 
<tr> 
<th>商圈分類</th>
<th>店家</th>
<th>帳號</th> 
<th>姓名</th> 
<th>性別</th> 
<th>手機</th>
<th>紅利點數</th> 
<th>加入日期</th> 
<th>狀態</th>
</tr>
';

while($row=mysqli_fetch_array($result)){
    
    



    switch ($row['member_gender']) {
        case 0:
            $member_gender = "女";
            break;
        case 1:
            $member_gender = "男";
            break;
        default:
            $member_gender = "";
    }
    switch ($row['membercard_status']) {
        case 0:
            $membercard_status = "已啟用";
            break;
        case 1:
            $membercard_status = "男";
            break;
        default:
            $membercard_status = "";
    }
    $total = $row['member_totalpoints']-$row["member_usingpoints"];
    
    $export .= '
    <tr>
    <td>'.$row["shopping_area"].'</td> 
    <td>'.$row["store_name"].'</td> 
    <td>'.$row["member_id"].'</td> 
    <td>'.$row["member_name"].'</td> 
    <td>'.$member_gender.'</td> 
    <td>'.$row["member_phone"].'</td> 
    <td>'.$total.'</td> 
    <td>'.$row["member_date"].'</td> 
    <td>'.$membercard_status.'</td>
    </tr>
    ';
    


    // echo $row['store_name']."\t";echo $row['member_id']."\t";echo $row['member_name']."\t";echo $member_gender."\t";echo $row['member_phone']."\t";echo $row['member_totalpoints']-$row['member_usingpoints']."\t";echo $row['member_date']."\t";echo $membercard_status."\t"."\n";
    
}
$export .= '</table>';
$today = date('Ymd');
$filename = "storemember_".$today.".xls";
header('Content-Type: application/xls');
header('Content-Disposition: attachment; filename='.$filename);
echo $export;
?>