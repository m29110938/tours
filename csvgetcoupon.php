<?php

// 連接資料庫
$host = 'localhost';
$user = 'tours_user';
$passwd = 'tours0115';
$database = 'toursdb';
// 設定編碼爲 utf8
$link = mysqli_connect($host, $user, $passwd, $database);
mysqli_query($link,"SET NAMES 'utf8'");
mysqli_set_charset($link,"utf8");
		
// 利用 fopen 功能讀取檔案
$handle = fopen("getcoupon20211129.csv", "r");
// 設定變數 i, 之後會用到
$i=0;

// 使用 fgetcsv 功能, 配合 while 迴圈, 可以拿到檔案內的每一行資料
while (($data = fgetcsv($handle, 1000, ',')))
{

    //如圖片所示, 第一行是行的名稱, 我們不想要將這行導入資料庫, 所以我們設定條件句, 
    //當變數 i 爲 0 正是跑到第一行, 進入條件句內, 變數 i 變爲 1, 並且 continue 使迴圈將之後的 code 都跳掉, 
    //直接回到迴圈的最上面在開始跑, 此時變數i已經是1, 所以將不會在進到條件句中。如此一來我們就完成我們的目標, 
    //只跳掉第一行。


    if($i == 0)
    {
        $i++;
        continue;
    }

    // 如 csv 的圖片所示, 降雨量那一行中有出現非數字的 NaN 字串, 
    // 但我們又想要將這一行的屬性設爲 float 或 decimal 方便之後若有需要用到計算。
    // 要避免資料匯入出錯, 我們必須將非數字的字串轉換爲數字, 
    // 因此利用條件句, 當 $data array 裏面的當三項爲 NaN 時, 替換爲0
    //if($data[2] == 'NaN')
    //{
    //    $data[2] = 0;
    //}
	$mid = $data[0];
	$coupondate = $data[1];
	$couponid = $data[2];
	$using_flag = $data[3];
	if ($using_flag == 1)  $using_date = $data[4];
	$using_date = $data[4];
	
	try {
		$coupon_no = uniqid();
		if ($using_flag == 1) {
				$sql="INSERT INTO mycoupon (mid, coupon_no,	using_flag,using_date ,cid,coupon_id ,coupon_name, coupon_type, coupon_description, coupon_startdate, coupon_enddate, coupon_status, coupon_rule, coupon_discount, discount_amount, coupon_storeid, coupon_for, coupon_picture, mycoupon_created_at) ";
				$sql=$sql." select $mid,'$coupon_no',$using_flag,'$using_date',cid,coupon_id ,coupon_name, coupon_type, coupon_description, coupon_startdate, coupon_enddate, coupon_status, coupon_rule, coupon_discount, discount_amount, coupon_storeid, coupon_for, coupon_picture,'$coupondate' from coupon where coupon_id = '".$couponid."'";
		}else{
				$sql="INSERT INTO mycoupon (mid, coupon_no,	using_flag,using_date ,cid,coupon_id ,coupon_name, coupon_type, coupon_description, coupon_startdate, coupon_enddate, coupon_status, coupon_rule, coupon_discount, discount_amount, coupon_storeid, coupon_for, coupon_picture, mycoupon_created_at) ";
				$sql=$sql." select $mid,'$coupon_no',$using_flag,NULL,cid,coupon_id ,coupon_name, coupon_type, coupon_description, coupon_startdate, coupon_enddate, coupon_status, coupon_rule, coupon_discount, discount_amount, coupon_storeid, coupon_for, coupon_picture,'$coupondate' from coupon where coupon_id = '".$couponid."'";
		}	
		echo $sql.'<br/>' ;
		//exit;
		mysqli_query($link,$sql) or die(mysqli_error($link));
		$rvalue = mysqli_affected_rows($link);

	} catch (Exception $e) {
		$rvalue = 0;							
	}

    // 最後, 將資料導入資料庫
    //$query = 'INSERT INTO rainfall (district, date, rainfall) VALUES ("'.$data[0] . '", "' . $data[1] . '", "' . $data[2].'")';

    //echo $query;
    //$result = mysqli_query($dbc, $query);

    //if ($result == false)
    //{
     //   echo 'Error description <br/>' . mysqli_error($dbc);
    //}
}
echo $i.'ok';
?>