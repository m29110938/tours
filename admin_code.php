<?php

$userid = isset($_POST['UID']) ? $_POST['UID'] : '';

	if ($userid != '') {
		//check 帳號/密碼
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';

		try {
			$link = mysqli_connect($host, $user, $passwd, $database);
			mysqli_query($link,"SET NAMES 'utf8'");

			$userid  = mysqli_real_escape_string($link,$userid);
			//$password  = mysqli_real_escape_string($link,$password);
			
			//$sql = "SELECT * FROM member where member_trash=0 ";
			//if ($member_id != "") {	
			//	$sql = $sql." and member_id='".$member_id."'";
			//}
			$sql = "SELECT * FROM sysuser where sid>0 ";
			if ($userid != "") {	
				$sql = $sql." and user_id = '".$userid."'";
			}		
			//if ($userpwd != "") {	
			//	$sql = $sql." and user_pwd='".$userpwd."'";
			//}			
			if ($result = mysqli_query($link, $sql)){
				if (mysqli_num_rows($result) > 0){
					//echo "login success!";
					while($row = mysqli_fetch_array($result)){
						$sid = $row['sid'];
						$usermobile = $row['user_mobile'];
					}
					if (($sid > 0) && ($usermobile != '')) {
	
						$user_code=randomkeys(4);
						$smsdata = "旅行點點APP[密碼重設驗證簡訊],你的驗證碼為:".$user_code;   //
						//$smsdata = "Reset password : Your code is ".$user_code."!";
						try {
							if (($usermobile != '') && ($smsdata != '')) {

								$cmd = "curl -X POST 'http://message.ttinet.com.tw/ensg/3lma236_online' --data 'id=3LMA236&pass=43bHJ2dA&pin=018008508556&telno=".$usermobile."&cont=".$smsdata."'";
								//echo $cmd;
								$result = shell_exec($cmd);
								//echo $result; 
								try {
									$sql2 = "update sysuser set reset_code='".$user_code."' ,user_updated_at=NOW() where user_trash=0 and sid=".$sid."";
									mysqli_query($link,$sql2) or die(mysqli_error($link));
									
									$status="true";
									$responseMessage="重設密碼的簡訊已發送!";							
								} catch (Exception $e) {
									$status="false";
									$responseMessage=$e->getMessage();				
								}
							}else{
								$status="false";
								$responseMessage="註冊手機號碼輸入錯誤,請重新輸入!";
							}	
							
						}
						catch (Exception $e) {
							$status="false";
							$responseMessage=$e->getMessage();				
						}	
					}else{
						//$this->_response(null, 400, validation_errors());
						//echo "error mail or password!";
						$status="false";
						//$data["code"]="0x0201";
						$responseMessage="註冊手機號碼輸入錯誤,請重新輸入!";					
					}
				}else{
					$status="false";
					$responseMessage="註冊手機號碼輸入錯誤,請重新輸入!";					
				}
				mysqli_close($link);
			}
		} catch (Exception $e) {
			$status="false";
			$responseMessage=$e->getMessage();				
		}
	 
	}else{
		$status="false";
		$responseMessage="API 參數錯誤!";
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
<form id="myForm" action="forgotpwd.php" method="post">
	<input type="hidden" name="status" id="status"  value="<?php echo $status;?>"/>		
	<input type="hidden" name="responseMessage" id="responseMessage"  value="<?php echo $responseMessage;?>"/>	
	<input type="hidden" name="user_id" id="user_id"  value="<?php echo $userid?>"/>	
	<input type="hidden" name="user_mobile" id="user_mobile"  value="<?php echo $usermobile?>"/>	
	
</form>
<script type="text/javascript">
    document.getElementById('myForm').submit();
</script>