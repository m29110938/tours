<?php
//include("header_check.php");
// include("db_tools.php");
$push_no = isset($_POST['push_no']) ? $_POST['push_no'] : '';
$push_memberid = isset($_POST['push_memberid']) ? $_POST['push_memberid'] : '';
$push_title = isset($_POST['push_title']) ? $_POST['push_title'] : '';
$push_body = isset($_POST['push_body']) ? $_POST['push_body'] : '';
		

// $push_memberid = "3223";

$host = 'localhost';
$user = 'tours_user';
$passwd = 'tours0115';
$database = 'toursdb';
$link = mysqli_connect($host, $user, $passwd, $database);
mysqli_query($link,"SET NAMES 'utf8'");
	
// echo $push_memberid;
$mids = explode(",",$push_memberid);


for ( $i=0 ; $i<count($mids) ; $i++ ) {
	$mid = $mids[$i];
	// echo $mid.'<br>' ;
	$sql = "SELECT * FROM member where member_trash='0' and member_status='1' and member_sid ='0' and mid='$mid'";
	// echo $sql;
	if ($result = mysqli_query($link, $sql)) {
		if (mysqli_num_rows($result)>0) {
			while ($row = mysqli_fetch_array($result)) {
				$member_id = $row['member_id'];
				// echo $member_id."<br>";
				$notificationToken = $row['notificationToken'];
				// echo $notificationToken."<br>";
			}
			$FCM_extra = isset($_POST['FCM_extra']) ? $_POST['FCM_extra'] : '';
					
			if ($member_id != '') {
				//&& ($Person_id != '') 
				try {
					// $mobile_no  = mysqli_real_escape_string($link,$mobile_no);
					//$Person_id  = mysqli_real_escape_string($link,$Person_id);
					$FCM_extra  = mysqli_real_escape_string($link,$FCM_extra);
					
					if(strlen($notificationToken)<=2) 
					{
						$data["code"]="0x0205";
						$data["responseMessage"]="The notificationToken is NULL";
						header('Content-Type: application/json');
						echo (json_encode($data, JSON_UNESCAPED_UNICODE));								
						exit;
					}

					$FCM_title = $push_title;
					$FCM_content = $push_body;
					
					if ($FCM_extra != ''){								
							$fields = array(
								'to' => $notificationToken,
								"notification" => [
									"body"  => $FCM_content,
									"title" => $FCM_title,
									"icon"  => "ic_launcher",
									"sound" => "default",
									//"click_action" =>  "OPEN_ACTIVITY_1",
									"click_action" =>  $FCM_extra,
								],
								//"data" 		=> 	  [
								//	"extra" =>	$FCM_extra,
								//],								
							);
					}else{
							$fields = array(
								'to' => $notificationToken,
								"notification" => [
									"body" => $FCM_content,
									"title" => $FCM_title,
									"icon" => "ic_launcher",
									"sound" => "default",
								],
						
							);					
					}
					
					$headers = array(
						'Authorization: key=AAAAYWpN8Aw:APA91bHHBo9l3mGrAK18SVqi5WnBU_aApr8Nx1BuiJwoDG7tm_oAyGM8685d3kke0FBb3WZ6gEjds6vbckp7s8s0PEzmtCZOrGT6oEaT-m2IuXKM-yG6orlVWelad6rg6u-MQl6f6gZP',
						'Content-Type: application/json',
					);
					for($i = 0; $i < 3; $i++)
					{
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
						$fcmresult = curl_exec($ch);
						
						curl_close($ch);
						if(strlen($fcmresult)>2)
							break;							
					}
					$title = $FCM_title;
					$body = $FCM_content;
					$msg = $FCM_title."-".$FCM_content;
					$sql2 = "INSERT INTO notificationlog (Person_id, Role, msg, title,body,fcmresult, updatetime) VALUES ('$member_id', '0', '$msg','$title','$body', '$fcmresult', NOW())";
					// echo $sql2;
					mysqli_query($link, $sql2);
					// break;
					$data["status"]="true";
					$data["code"]="0x0200";
					$data["responseMessage"]="推播發送成功!";
					if ($push_no != ""){
						$sql1 = "update push set push_status='1',push_updated_at = NOW()";
						$sql1 .= " where push_no='$push_no'";
						// echo $sql1;
						mysqli_query($link, $sql1);
					}
					
				}catch (Exception $e) {
					//$this->_response(null, 401, $e->getMessage());
					//echo $e->getMessage();
					$data["status"]="false";
					$data["code"]="0x0202";
					$data["responseMessage"]="Exception error!";
				}	
	
			}else{
				//echo "need mail and password!";
				$data["status"]="false";
				$data["code"]="0x0203";
				$data["responseMessage"]="API parameter is required1!";
		
			}
		}else{
			// echo "need mail and password!";
			$data["status"]="false";
			$data["code"]="0x0203";
			$data["responseMessage"]="API parameter is required2!";
	
		}
	}else{
		// echo "need mail and password!";
		$data["status"]="false";
		$data["code"]="0x0203";
		$data["responseMessage"]="API parameter is required3!";

	}
}
header('Content-Type: application/json');
echo (json_encode($data, JSON_UNESCAPED_UNICODE));	

?>