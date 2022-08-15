<?php
//include("header_check.php");
//include("db_tools.php");
// $mids = isset($_POST['push_memberid']) ? $_POST['push_memberid'] : '';
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';

// $store_id = isset($_POST['store_id']) ? $_POST['store_id'] : '';
// $push_title = isset($_POST['push_title']) ? $_POST['push_title'] : '';
// $push_body = isset($_POST['push_body']) ? $_POST['push_body'] : '';
		
$host = 'localhost';
$user = 'tours_user';
$passwd = 'tours0115';
$database = 'toursdb';
$link = mysqli_connect($host, $user, $passwd, $database);
mysqli_query($link,"SET NAMES 'utf8'");
	
if (($member_id != '') && ($member_pwd != '')) {

	$member_id  = mysqli_real_escape_string($link,$member_id);
	$member_pwd  = mysqli_real_escape_string($link,$member_pwd);
	
	$sql = "SELECT * FROM member where member_trash=0 and member_status='1' and member_sid=0";   //and member_status='1' 
	if ($member_id != "") {	
		$sql = $sql." and member_id='".$member_id."'";
	}
	if ($member_pwd != "") {	
		$sql = $sql." and member_pwd='".$member_pwd."'";
	}
	if ($result = mysqli_query($link, $sql)){
		if (mysqli_num_rows($result)>0){
			while($row = mysqli_fetch_array($result)){

			}

			// $mobile_no = isset($_POST['mobile_no']) ? $_POST['mobile_no'] : '';
			//$Person_id = isset($_POST['Person_id']) ? $_POST['Person_id'] : '';
			$FCM_extra = isset($_POST['FCM_extra']) ? $_POST['FCM_extra'] : '';
					
			if ($member_id != '') {
				//&& ($Person_id != '') 
				try {

					// $mobile_no  = mysqli_real_escape_string($link,$mobile_no);
					//$Person_id  = mysqli_real_escape_string($link,$Person_id);
					$FCM_extra  = mysqli_real_escape_string($link,$FCM_extra);
					
					$sql = "SELECT * FROM member where member_trash=0 ";   //and member_status='1' 
					if ($member_id != "") {	
						$sql = $sql." and member_id='".$member_id."'";
					}
					
					if ($result = mysqli_query($link, $sql)){
						if (mysqli_num_rows($result)>0){
							while($row = mysqli_fetch_array($result)){
								
								$notificationToken = $row['notificationToken'];
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

								
								$FCM_title = "生日禮來囉!";
								$FCM_content = '親愛的會員，XXX送了您一份禮物 祝您生日快樂~~~ 請記得到 "優惠券" 頁面使用哦~~';

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
								$sql = "INSERT INTO notificationlog (Person_id, Role, msg, title,body,fcmresult, updatetime) VALUES ('$member_id', '0', '$msg','$title','$body', '$fcmresult', NOW())";
								// echo $sql;
								mysqli_query($link, $sql);
								break;
							}
							$data["status"]="true";
							$data["code"]="0x0200";
							$data["responseMessage"]="推播發送成功!";							
						}else{
							$data["status"]="false";
							$data["code"]="0x0206";
							$data["responseMessage"]="無此會員推播發送失敗!";						
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