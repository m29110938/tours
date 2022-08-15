<?php
$uid = isset($_POST['uid']) ? $_POST['uid'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$usercode = isset($_POST['code']) ? $_POST['code'] : '';

	if (($uid != '') && ($password != '') && ($usercode != '')) {
		//check 帳號/密碼
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';
		
		//echo $sql;
		//exit;
		try {
			$link = mysqli_connect($host, $user, $passwd, $database);
			mysqli_query($link,"SET NAMES 'utf8'");

			$uid  = mysqli_real_escape_string($link,$uid);
			$password  = mysqli_real_escape_string($link,$password);
			$usercode  = mysqli_real_escape_string($link,$usercode);
			
			$sql = "SELECT * FROM sysuser where user_trash=0 ";
			if ($uid != "") {	
				$sql = $sql." and user_id='".$uid."'";
			}
			if ($usercode != "") {	
				$sql = $sql." and reset_code='".$usercode."'";
			}
					//echo $sql;
					//exit;			
			if ($result = mysqli_query($link, $sql)){
				if (mysqli_num_rows($result) > 0){
					$userid=0;
					while($row = mysqli_fetch_array($result)){
						$sid = $row['sid'];
					}
					$sql2="update sysuser set user_pwd='$password',reset_code='',user_updated_at=NOW() where sid=".$sid." ;";


					mysqli_query($link,$sql2) or die(mysqli_error($link));	

					$status="true";
					$responseMessage="Reset password success!";					
				}else{
					$status="false";
					$responseMessage="ID or code is wrong!";					
				}
			}else{
				//echo "need mail and password!";
				$status="false";
				$responseMessage="SQL fail!";					
			}
			mysqli_close($link);
		} catch (Exception $e) {
			$status="false";
			$responseMessage=$e->getMessage();				
        }
	}else{
		$status="false";
		$responseMessage="API parameter is required!";
	}
?>
<form id="myForm" action="resetpwd.php" method="post">
	<input type="hidden" name="status" id="status"  value="<?php echo $status;?>"/>		
	<input type="hidden" name="responseMessage" id="responseMessage"  value="<?php echo $responseMessage;?>"/>	
	<input type="hidden" name="uid" id="uid"  value="<?php echo $uid?>"/>	
	
</form>
<script type="text/javascript">
    document.getElementById('myForm').submit();
</script>