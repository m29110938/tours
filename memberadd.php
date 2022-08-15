<?php
	session_start();
	//if ($_SESSION['authority']!="1"){
	//	header("Location: main.php");
	//}
	function SendCURL($membername,$memberid,$memberpwd){
		$crl = curl_init('https://ml-api.jotangi.com.tw/api/auth/register');
		$data = array(
		  'name' => $membername,
		  'mobile' => $memberid,
		  'password' => $memberpwd,
		);

		$post_data = json_encode($data);

		// Prepare new cURL resource
		
		curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($crl, CURLINFO_HEADER_OUT, true);
		curl_setopt($crl, CURLOPT_POST, true);
		curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);

		// Set HTTP Header for POST request 
		curl_setopt($crl, CURLOPT_HTTPHEADER, array(
		  'Content-Type: application/json',
		  'Content-Length: ' . strlen($data))
		);

		// Submit the POST request
		$result = curl_exec($crl);
		$obj = json_decode($result,true);
		// handle curl error
		//if ($result === false) {
		if ($obj["status"] == "error") {
		  // throw new Exception('Curl error: ' . curl_error($crl));
		  //print_r('Curl error: ' . curl_error($crl));
		  $result_noti = 0; //die();
		} else {

		  $result_noti = 1; //die();
		}
		curl_close($crl);
	return $result_noti;
	}	

	$act = isset($_POST['act']) ? $_POST['act'] : '';
	if ($act == 'Add') {
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';
		
		$link = mysqli_connect($host, $user, $passwd, $database);
		mysqli_query($link,"SET NAMES 'utf8'");
				
		$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
		$member_id  = mysqli_real_escape_string($link,$member_id);

		$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
		$member_pwd  = mysqli_real_escape_string($link,$member_pwd);
		
		$member_name = isset($_POST['member_name']) ? $_POST['member_name'] : '';
		$member_name  = mysqli_real_escape_string($link,$member_name);

		$member_type = isset($_POST['member_type']) ? $_POST['member_type'] : '';
		$member_type  = mysqli_real_escape_string($link,$member_type);

		$member_gender = isset($_POST['member_gender']) ? $_POST['member_gender'] : '';
		$member_gender  = mysqli_real_escape_string($link,$member_gender);
//
		$member_email = isset($_POST['member_email']) ? $_POST['member_email'] : '';
		$member_email  = mysqli_real_escape_string($link,$member_email);
		
		$member_birthday = isset($_POST['member_birthday']) ? $_POST['member_birthday'] : '';
		$member_birthday  = mysqli_real_escape_string($link,$member_birthday);

		$member_address = isset($_POST['member_address']) ? $_POST['member_address'] : '';
		$member_address  = mysqli_real_escape_string($link,$member_address);

		$member_phone = isset($_POST['member_phone']) ? $_POST['member_phone'] : '';
		$member_phone  = mysqli_real_escape_string($link,$member_phone);

		$member_status = isset($_POST['member_status']) ? $_POST['member_status'] : '';
		$member_status  = mysqli_real_escape_string($link,$member_status);

		$recommend_code = isset($_POST['recommend_code']) ? $_POST['recommend_code'] : '';
		$recommend_code  = mysqli_real_escape_string($link,$recommend_code);

		$member_sid = isset($_POST['member_sid']) ? $_POST['member_sid'] : '0';
		$member_sid  = mysqli_real_escape_string($link,$member_sid);

		$sql="INSERT INTO member (member_id,member_pwd,member_name,member_type,member_gender,member_email,member_birthday,member_address,member_phone,member_status,recommend_code,member_sid) VALUES ('$member_id','$member_pwd','$member_name',$member_type,$member_gender,'$member_email'";

		if ($member_birthday != "") {
			$sql=$sql.",'$member_birthday'";
		}else{
			$sql=$sql.",null";
		}
		$sql=$sql.",'$member_address','$member_phone','$member_status','$recommend_code',$member_sid);";

		//echo $sql;
		//exit;

		mysqli_query($link,$sql) or die(mysqli_error($link));
		
		$result_key=SendCURL($member_name,$member_id,$member_pwd);
		
		$_SESSION['saveresult']="新增會員資料成功!";
			
		// once saved, redirect back to the view page
		header("Location: member.php?act=Qry");

		mysqli_close($link);
	}else{
		header("Location: logout.php");
	}
?>