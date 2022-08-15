<?php
//include("header_check.php");
//include("db_tools.php");

$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
		
$host = 'localhost';
$user = 'tours_user';
$passwd = 'tours0115';
$database = 'toursdb';
$link = mysqli_connect($host, $user, $passwd, $database);
mysqli_query($link,"SET NAMES 'utf8'");
	
if (($member_id != '') && ($member_pwd != '')) {

	$member_id  = mysqli_real_escape_string($link,$member_id);
	$member_pwd  = mysqli_real_escape_string($link,$member_pwd);
	
	$sql = "SELECT * FROM member where member_trash=0 ";   //and member_status='1' 
	if ($member_id != "") {	
		$sql = $sql." and member_id='".$member_id."'";
	}
	if ($member_pwd != "") {	
		$sql = $sql." and member_pwd='".$member_pwd."'";
	}
	if ($result = mysqli_query($link, $sql)){
		if (mysqli_num_rows($result)>0){

			$sid = isset($_POST['sid']) ? $_POST['sid'] : '';
			//$Person_id = isset($_POST['Person_id']) ? $_POST['Person_id'] : '';
			$FCM_title = isset($_POST['FCM_title']) ? $_POST['FCM_title'] : '';
			$FCM_content = isset($_POST['FCM_content']) ? $_POST['FCM_content'] : '';
					
			if (($sid != '') && ($FCM_title != '') && ($FCM_content != '') ) {
				//&& ($Person_id != '') 
				try {

					$sid  = mysqli_real_escape_string($link,$sid);
					//$Person_id  = mysqli_real_escape_string($link,$Person_id);
					$FCM_title  = mysqli_real_escape_string($link,$FCM_title);	
					$FCM_content  = mysqli_real_escape_string($link,$FCM_content);

					
					$sql = "SELECT * FROM member where member_trash=0 and member_type=2 ";   //and member_status='1' 
					if ($sid != "") {	
						$sql = $sql." and member_sid=".$sid."";
					}
					
					if ($result = mysqli_query($link, $sql)){
						if (mysqli_num_rows($result)>0){
							while($row = mysqli_fetch_array($result)){
								
								$notificationToken = $row['notificationToken'];
								$member_id = $row['member_id'];
								
								//$sql = "INSERT INTO `notification_history` (`account`,`accountType`,`type`,`msg`,`createDateTime`) VALUES ('$store_account[$k]','0','5','$notificationmsg','$nowtime')";
								//$db->query($sql);
								
								if(strlen($notificationToken)<=2) 
								{
									$data["code"]="0x0205";
									$data["responseMessage"]="The notificationToken is NULL";
									header('Content-Type: application/json');
									echo (json_encode($data, JSON_UNESCAPED_UNICODE));								
									exit;
								}
								
								$fields = array(
									'to' => $notificationToken,
									"notification" => [
										"body" => $FCM_content,
										"title" => $FCM_title,
										"icon" => "ic_launcher",
										"sound" => "default",
									],
								);

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
								$msg = $FCM_title."-".$FCM_content;
								$sql = "INSERT INTO notificationlog (Person_id, Role, msg, fcmresult, updatetime) VALUES ('$member_id', '1', '$msg', '$fcmresult', NOW())";
								mysqli_query($link, $sql);
								break;
							}
							$data["status"]="true";
							$data["code"]="0x0200";
							$data["responseMessage"]="推播發送成功!";							
						}else{
							$data["status"]="false";
							$data["code"]="0x0206";
							$data["responseMessage"]="無此店家會員,推播發送失敗!";						
						}
					}else{
						//echo "need mail and password!";
						$data["status"]="false";
						$data["code"]="0x0204";
						$data["responseMessage"]="SQL fail!";			
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
				$data["responseMessage"]="API parameter is required!";
		
			}
		}else{
			//$this->_response(null, 400, validation_errors());
			//echo "error mail or password!";
			$data["status"]="false";
			$data["code"]="0x0201";
			$data["responseMessage"]="ID or password is wrong!";					
		}
	}else{
		//echo "need mail and password!";
		$data["status"]="false";
		$data["code"]="0x0204";
		$data["responseMessage"]="SQL fail!";			
	}
	header('Content-Type: application/json');
	echo (json_encode($data, JSON_UNESCAPED_UNICODE));	
}else{
	//echo "need mail and password!";
	$data["status"]="false";
	$data["code"]="0x0203";
	$data["responseMessage"]="API parameter is required!";
	header('Content-Type: application/json');
	echo (json_encode($data, JSON_UNESCAPED_UNICODE));		
}	
	
?>