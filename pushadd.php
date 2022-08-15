<?php
	session_start();
	//if ($_SESSION['authority']!="1"){
	//	header("Location: main.php");
	//}

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
		echo $response;
		// usleep(100000);  // 停止0.1秒
	}

	$act = isset($_POST['act']) ? $_POST['act'] : '';
	if ($act == 'Add') {
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';
		
		$link = mysqli_connect($host, $user, $passwd, $database);
		mysqli_query($link,"SET NAMES 'utf8'");
				
		$push_no = isset($_POST['push_no']) ? $_POST['push_no'] : '';
		$push_no  = mysqli_real_escape_string($link,$push_no);

		$push_date = isset($_POST['push_date']) ? $_POST['push_date'] : '';
		$push_date  = mysqli_real_escape_string($link,$push_date);
		
		$push_title = isset($_POST['push_title']) ? $_POST['push_title'] : '';
		$push_title  = mysqli_real_escape_string($link,$push_title);

		$push_body = isset($_POST['push_body']) ? $_POST['push_body'] : '';
		$push_body  = mysqli_real_escape_string($link,$push_body);

		// $member_type = isset($_POST['member_type']) ? $_POST['member_type'] : '0';
		// $member_type  = mysqli_real_escape_string($link,$member_type);

		$members = isset($_POST['members']) ? $_POST['members'] : '';
		$members  = mysqli_real_escape_string($link,$members);
		// if ($members == 0){

		// }
		


		$sid = isset($_POST['sid']) ? $_POST['sid'] : '0';
		$sid  = mysqli_real_escape_string($link,$sid);

		$date = date_create();
		$rand = sprintf("%04d", rand(0,9999));
		$push_no = date_timestamp_get($date).$rand;

		$sql="INSERT INTO push (push_no,push_date,push_memberid,push_storeid,push_title,push_body,push_status) VALUES ('$push_no','$push_date', '$members','$sid','$push_title','$push_body',0);";

		//echo $sql;
		//exit;

		mysqli_query($link,$sql) or die(mysqli_error($link));
		$today = date("Y-m-d");
		// echo $push_date;
		$_SESSION['saveresult']="新增推播訊息成功!";

		// $members = '0';
		// $members = '3221,3223';
		// 當天發送即時
		if($today == $push_date){
			echo "===";
			// call 推播api
			if ($members == 0){
				// echo $_SESSION['authority'];
				$sql = "select a.* from member as a";
				// $sql = $sql." select * from member";
				if ($_SESSION['authority']=="4"){
				  	$sql = $sql." INNER JOIN (SELECT * FROM membercard) as b on a.mid = b.member_id";
				  	$sql = $sql." INNER JOIN (SELECT * FROM store) as c on b.store_id = c.sid";
					$sql = $sql." where c.sid=".$_SESSION['loginsid']." and b.membercard_trash = 0 and a.member_trash = 0 and a.member_sid = 0 and a.notificationToken is not null";
				}else{
					$sql = $sql." where a.member_trash = 0 and a.member_sid = 0  and a.notificationToken is not null";
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
			
			header("Location: push.php?act=Qry");
		}else{
			header("Location: push.php?act=Qry");
		}
		// header("Location: push.php?act=Qry");

		mysqli_close($link);
	}else{
		header("Location: logout.php");
	}
?>