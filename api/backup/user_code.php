<?php
//require_once("../phpmailer/PHPMailerAutoload.php");

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
					//$Mebermail = "";
					while($row = mysqli_fetch_array($result)){
						$mid = $row['mid'];
						//$Mebermail = $row['member_email'];
					}					
					
					if ($mid>0) {
	
						$user_code=randomkeys(4);
						$smsdata = "旅行點點APP[密碼重設驗證簡訊],你的驗證碼為:".$user_code;   //
						//$smsdata = "Reset password : Your code is ".$user_code."!";
						try {
							//send sms
							//$cmd = "curl -X POST \"https://coupon.jotangi.net:9443/sendsms.php\" --data \"phone_no=".$member_id."&sms_data=".$smsdata."\"";
							//echo $cmd;
							//$result = shell_exec($cmd);
							//echo $result;
							//$uriBase2 = 'https://coupon.jotangi.net:9443/sendsms.php';
							//$fields2 = [
							//	'phone_no'         => $member_id,
							//	'sms_data'         => $smsdata
							//];
							//$fields_string2 = http_build_query($fields2);	
							//$ch2 = curl_init();
							//curl_setopt($ch2,CURLOPT_URL, $uriBase2);
							//curl_setopt($ch2,CURLOPT_POST, true);
							//curl_setopt($ch2,CURLOPT_POSTFIELDS, $fields_string2);
							//curl_setopt($ch2,CURLOPT_RETURNTRANSFER, true); 
							//execute post
							//$result2 = curl_exec($ch2);
							//echo $result2;
//-----------------------------------------------------------------------------------------------------------------------------------
							//$url = "https://coupon.jotangi.net:9443/sendsms.php";

							//$ch = curl_init();
							//curl_setopt($ch, CURLOPT_URL, $url);
							//curl_setopt($ch, CURLOPT_POST, true);
							//curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array("phone_no"=>$member_id, "sms_data"=>$smsdata))); 
							//$output = curl_exec($ch); 
							//curl_close($ch);
							//echo $output;
							if (($member_id != '') && ($smsdata != '')) {

								$cmd = "curl -X POST 'http://message.ttinet.com.tw/ensg/3lma236_online' --data 'id=3LMA236&pass=43bHJ2dA&pin=018008508556&telno=".$member_id."&cont=".$smsdata."'";
								//$cmd = "curl -X POST 'https://face8.pakka.ai/api/v2/faceDetect' -H 'accept: application/json' -H 'Content-Type: multipart/form-data' -F 'api_key=".$key."' -F 'image_file=@".$target_file.";type=image/jpeg'";
								//echo $cmd;
								$result = shell_exec($cmd);
								//echo $result; 
								try {
									$sql2 = "update member set reset_code='".$user_code."' ,member_updated_at=NOW() where member_trash=0 and mid=".$mid."";
									mysqli_query($link,$sql2) or die(mysqli_error($link));
									
									$data["status"]="true";
									$data["code"]="0x0200";
									$data["responseMessage"]="The reset sms aleady send!";							
								} catch (Exception $e) {
									//$this->_response(null, 401, $e->getMessage());
									//echo $e->getMessage();
									$data["status"]="false";
									$data["code"]="0x0202";
									$data["responseMessage"]=$e->getMessage();				
								}
							}else{
								$data["status"]="false";
								$data["code"]="0x0203";
								$data["responseMessage"]="API parameter is required!";
							}	
							
						}
						catch (Exception $e) {
								$data["status"]="false";
								$data["code"]="0x0202";
								$data["responseMessage"]=$e->getMessage();				
						}	
						
					}else{
							$data["status"]="false";
							$data["code"]="0x0206";
							$data["responseMessage"]="Member ID is required!"; 					
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