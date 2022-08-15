<?php
//include("header_check.php");
// include("db_tools.php");
// $pid = isset($_POST['pid']) ? $_POST['pid'] : '';

// $push_no = isset($_POST['push_no']) ? $_POST['push_no'] : '';
// $authority = isset($_POST['authority']) ? $_POST['authority'] : '';
// echo $authority;
// $push_memberid = isset($_POST['push_memberid']) ? $_POST['push_memberid'] : '';
// $member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
// $member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';

// $store_id = isset($_POST['store_id']) ? $_POST['store_id'] : '';
// $push_title = isset($_POST['push_title']) ? $_POST['push_title'] : '';
// $push_body = isset($_POST['push_body']) ? $_POST['push_body'] : '';
$today = date("Y-m-d");

function push_tomember($push_no,$member,$push_title,$push_body){
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => 'http://localhost/tours/api/push_tomember.php',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => 'push_no='.$push_no.'&push_memberid='.$member.'&push_title='.$push_title.'&push_body='.$push_body,
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/x-www-form-urlencoded'
		),
	));
	$response = curl_exec($curl);
	curl_close($curl);
	// echo $response;
	usleep(100000);  // 停止0.1秒
}


// $today = '2022-08-17';
// echo $today;
// echo '<br>';

// $push_memberid = "3223";

$host = 'localhost';
$user = 'tours_user';
$passwd = 'tours0115';
$database = 'toursdb';
$link = mysqli_connect($host, $user, $passwd, $database);
mysqli_query($link,"SET NAMES 'utf8'");

$sql2 = "select * from push where DATE(push_date)='".$today."' and push_status = 0";

if ($result2 = mysqli_query($link, $sql2)) {
    if (mysqli_num_rows($result2) > 0) {
        while ($row2 = mysqli_fetch_array($result2)) {
			// echo $row2['push_no']."<br>";
			// echo $row2['push_memberid']."<br>";
			$push_no = $row2['push_no'];
			$members = $row2['push_memberid'];
			$push_title = $row2['push_title'];
			$push_body = $row2['push_body'];
			if ($members == 0){
				// echo $_SESSION['authority'];
				$sql = "select a.* from member as a";
				// $sql = $sql." select * from member";
				if ($row2['push_storeid']!="0"){
				  	$sql = $sql." INNER JOIN (SELECT * FROM membercard) as b on a.mid = b.member_id";
				  	$sql = $sql." INNER JOIN (SELECT * FROM store) as c on b.store_id = c.sid";
					$sql = $sql." where c.sid=".$row2['push_storeid']." and b.membercard_trash = 0 and a.member_trash = 0 and a.member_sid = 0 and a.notificationToken is not null";
				}else{
					$sql = $sql." where a.member_trash = 0 and a.member_sid = 0  and notificationToken is not null";
				}
				// echo $sql;
				if ($result = mysqli_query($link, $sql)) {
					if (mysqli_num_rows($result) > 0) {
						while ($row = mysqli_fetch_array($result)) {
							$member = $row['mid'];
							// echo $member.'<br>';
							push_tomember($push_no,$member,$push_title,$push_body);
							// echo "<br>";
						}
					}
				}
			}else{
				$member_array = explode(',',$members);
				// print_r($member_array);
				for($i=0;$i<count($member_array);$i++){
					$member = $member_array[$i];
					// echo $member;
					push_tomember($push_no,$member,$push_title,$push_body);
					// echo "<br>";
				}
			}
        }
    }
}
echo "完成";
?>