<?php
require_once("../phpmailer/PHPMailerAutoload.php");

$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
//$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';

//$Mebermail = isset($_POST['mail']) ? $_POST['mail'] : '';

	if ($member_id != '') {
		//check 帳號/密碼
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';

		try {
			$link = mysqli_connect($host, $user, $passwd, $database);
			mysqli_query($link,"SET NAMES 'utf8'");

			$member_id  = mysqli_real_escape_string($link,$member_id);
			
			$sql = "SELECT * FROM member where member_trash=0 ";
			if ($member_id != "") {	
				$sql = $sql." and member_id='".$member_id."'";
			}
		//echo $sql;
		//exit;			
			if ($result = mysqli_query($link, $sql)){
				if (mysqli_num_rows($result) > 0){
					$mid = 0;
					$Mebermail = "";
					while($row = mysqli_fetch_array($result)){
						$mid = $row['mid'];
						$Mebermail = $row['member_email'];
					}					
					
					if (($Mebermail != '') && ($mid>0)) {
						//echo "login success!";
						// 產生 Mailer 實體
						$mail = new PHPMailer();
						// 設定為 SMTP 方式寄信
						$mail->IsSMTP();
						// SMTP 伺服器的設定，以及驗證資訊
						$mail->Host = "smtp.gmail.com";
						$mail->Port = 465;   //587
						$mail->SMTPAuth = true;
						$mail->SMTPSecure = 'ssl';
						// 信件內容的編碼方式       
						$mail->CharSet = "utf-8";
						// 信件處理的編碼方式
						$mail->Encoding = "base64";
						// SMTP 驗證的使用者資訊
						$mail->Username = 'jotangi.iot@jotangi.com';
						$mail->Password = '1688JotangI';	
						$user_code=randomkeys(4);
						// 信件內容設定  
						$mail->From = "jotangi.iot@jotangi.com"; //需要與上述的使用者資訊相同mail
						$mail->FromName = "旅行點點 APP 密碼重設"; //此顯示寄件者名稱
						$mail->Subject = "旅行點點 APP 密碼重設密碼驗證信"; //信件主旨
						$mail->Body = "你的驗證碼為 ".$user_code.", 15分鐘內有效!";   //信件內容
						$mail->IsHTML(true);

						// 收件人
						$mail->AddAddress($Mebermail, "旅行點點 會員"); //此為收件者的電子信箱及顯示名稱
						// 顯示訊息
						if(!$mail->Send()) {
							//echo "Mail error: " . $mail->ErrorInfo;   
							$data["status"]="false";
							$data["code"]="0x0205";
							$data["responseMessage"]="Mail error: " . $mail->ErrorInfo; 		
						} else {

							try {
								$sql2 = "update member set reset_code='".$user_code."' ,member_updated_at=NOW() where member_trash=0 and mid=".$mid."";
								mysqli_query($link,$sql2) or die(mysqli_error($link));
								
								$data["status"]="true";
								$data["code"]="0x0200";
								$data["responseMessage"]="The reset mail aleady sended!";							
							} catch (Exception $e) {
								//$this->_response(null, 401, $e->getMessage());
								//echo $e->getMessage();
								$data["status"]="false";
								$data["code"]="0x0202";
								$data["responseMessage"]=$e->getMessage();				
							}						
						}
					}else{
							$data["status"]="false";
							$data["code"]="0x0206";
							$data["responseMessage"]="Mail address is required!"; 					
					}
				}else{
					//$this->_response(null, 400, validation_errors());
					//echo "error mail or password!";
					$data["status"]="false";
					$data["code"]="0x0201";
					$data["responseMessage"]="ID is wrong!";					
				}
			}else{
				//echo "need mail and password!";
				$data["status"]="false";
				$data["code"]="0x0204";
				$data["responseMessage"]="SQL fail!";					
			}
			mysqli_close($link);
		} catch (Exception $e) {
            //$this->_response(null, 401, $e->getMessage());
			//echo $e->getMessage();
			$data["status"]="false";
			$data["code"]="0x0202";
			$data["responseMessage"]=$e->getMessage();				
        }
		header('Content-Type: application/json');
		echo (json_encode($data, JSON_PRETTY_PRINT));
 
	}else{
		//echo "need mail and password!";
		$data["status"]="false";
		$data["code"]="0x0203";
		$data["responseMessage"]="API parameter is required!";
		header('Content-Type: application/json');
		echo (json_encode($data, JSON_PRETTY_PRINT));		
	}
	
	function randomkeys($length){
	//$pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
	$pattern = "1234567890";
	$key = "";
	for($i=0;$i<$length;$i++){
		$key .= $pattern[rand(0,9)];
	}
	return $key;
	}		
?>