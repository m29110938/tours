<?php
class DesEdeCbc {
	private $cipher, $key, $iv;
	/**
	 * DesEdeCbc constructor.
	 * @param $cipher
	 * @param $key
	 * @param $iv
	 */
	public function __construct($cipher, $key, $iv) {
	$this->cipher = $cipher;
	$this->key= "AwBHMEUCIQCi7omUvYLm0b2LobtEeRAY";  //$this->getFormatKey($key);
	$this->iv = "77215989@jotangi";  //$iv;
	}
	/**
	 * @func  加密
	 * @param $msg
	 * @return string
	 */

	public function encrypt($msg) {
	$des = @openssl_encrypt($msg, $this->cipher, $this->key, OPENSSL_RAW_DATA, $this->iv);
	return base64_encode($des);
	}
	/**
	 * @func  解密
	 * @param $msg
	 * @return string
	 */
	public function decrypt($msg) {
	return @openssl_decrypt(base64_decode($msg), $this->cipher, $this->key, OPENSSL_RAW_DATA, $this->iv);
	}

	/**
	 * @func  生成24位长度的key
	 * @param $skey
	 * @return bool|string
	 */

	private function getFormatKey($skey) {
		$md5Value= md5($skey);
		$md5ValueLen = strlen($md5Value);
		$key = $md5Value . substr($md5Value, 0, $md5ValueLen / 2);
	return hex2bin($key);
	}
}

$cipher = 'DES-EDE-CBC';
$key = '12345678';
$iv  = "\x01\x02\x03\x04\x05\x06\x07\x08";
$des = new DesEdeCbc($cipher, $key, $iv);


$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';

	if (($member_id != '') && ($member_pwd != '')) {
		//check 帳號/密碼
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';
		

		try {
			$link = mysqli_connect($host, $user, $passwd, $database);
			mysqli_query($link,"SET NAMES 'utf8'");

			$member_id  = mysqli_real_escape_string($link,$member_id);
			$member_pwd  = mysqli_real_escape_string($link,$member_pwd);
			
			$sql = "SELECT * FROM member where member_trash=0 ";
			if ($member_id != "") {	
				$sql = $sql." and member_id='".$member_id."'";
			}
			if ($member_pwd != "") {	
				$sql = $sql." and member_pwd='".$member_pwd."'";
			}
			if ($result = mysqli_query($link, $sql)){
				if (mysqli_num_rows($result) > 0){
					// login ok
					// user id 取得
					$mid=0;
					while($row = mysqli_fetch_array($result)){
						$mid = $row['mid'];
						$membername = $row['member_name'];
					}
					try {
					
						$sql2 = "SELECT `mid`, `member_id`, `member_pwd`, `member_name`, `member_type`, `member_gender`, `member_email`, `member_birthday`, `member_address`, `member_phone`, `member_picture`, `member_totalpoints`, `member_usingpoints`, `member_status`, `recommend_code`,`member_sid` FROM member where member_trash=0 ";
						$sql2 = $sql2." and mid = ".$mid."";

						//$data = "";
						if ($result2 = mysqli_query($link, $sql2)){
							if (mysqli_num_rows($result2) > 0){
								$rows = array();
								//$i=0;
								while($row2 = mysqli_fetch_array($result2)){
									$rows[] = $row2;
									//$c1= $row2['mid'];

									//$c2= $row2['member_id'];
									//$c2 = $des->encrypt($c2);
									
									//$c3= $row2['member_pwd'];
									//$c3 = $des->encrypt($c3);
									
									//$c4= $row2['member_name'];
									//$c4 = $des->encrypt($c4);
									
									//$c5= $row2['member_type'];
									//$c6= $row2['member_gender'];
									
									//$c7= $row2['member_email'];
									//$c7 = $des->encrypt($c7);
									
									//$c8= $row2['member_birthday'];
									//$c8 = $des->encrypt($c8);
									
									//$c9= $row2['member_address'];
									//$c9 = $des->encrypt($c9);
									
									//$c10= $row2['member_phone'];
									//$c10 = $des->encrypt($c10);
									
									//$c11= $row2['member_picture'];
									//$c12= $row2['member_totalpoints'];
									//$c13= $row2['member_usingpoints'];
									//$c14= $row2['member_status'];
									
									//$c15= $row2['recommend_code'];
									//$c15 = $des->encrypt($c15);
									
									//$c16= $row2['member_sid'];
			
									//$row_id = $row_user ["id"];
									//$row_author = $row_user ["author"];
									//$row_code = $row_user ["code"];
									//$rows[] = array("0"=> $c1 , "mid"=> $c1 , "1"=> $c2,"member_id"=> $c2, "2"=> $c3, "member_pwd"=> $c3,"3"=> $c4,"member_name"=> $c4, "4"=> $c5,"member_type"=> $c5, "5"=> $c6,"member_gender"=> $c6,"6"=> $c7,"member_email"=> $c7,"7"=> $c8,"member_birthday"=> $c8,"8"=> $c9,"member_address"=> $c9,"9"=> $c10,"member_phone"=> $c10,"10"=> $c11,"member_picture"=> $c11,"11"=> $c12,"member_totalpoints"=> $c12,"12"=> $c13,"member_usingpoints"=> $c13,"13"=> $c14,"member_status"=> $c14,"14"=> $c15,"recommend_code"=> $c15,"15"=> $c16,"member_sid"=> $c16);
	   
									
									//$i = $i + 1;
									//`member_picture`, `member_totalpoints`, `member_usingpoints`, `member_status`, `recommend_code`,`member_sid`
									//banner_subject, banner_date, banner_enddate, banner_descript	,banner_picture,banner_link
								}
	
								header('Content-Type: application/json');
								echo (json_encode($rows, JSON_UNESCAPED_UNICODE));
								exit;
							}else{
								$data["status"]="false";
								$data["code"]="0x0201";
								$data["responseMessage"]="This is no member data!";									
							}
						}else{
							//echo "need mail and password!";
							$data["status"]="false";
							$data["code"]="0x0204";
							$data["responseMessage"]="SQL fail!";								
						}
					} catch (Exception $e) {
						//$this->_response(null, 401, $e->getMessage());
						//echo $e->getMessage();
						$data["status"]="false";
						$data["code"]="0x0202";
						$data["responseMessage"]=$e->getMessage();							
					}
				}else{
					$data["status"]="false";
					$data["code"]="0x0205";
					$data["responseMessage"]="ID or password is wrong!";						
				}
			}else {
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
		echo (json_encode($data, JSON_UNESCAPED_UNICODE));		
	}else{
		//echo "參數錯誤 !";
		$data["status"]="false";
		$data["code"]="0x0203";
		$data["responseMessage"]="API parameter is required!";
		header('Content-Type: application/json');
		echo (json_encode($data, JSON_UNESCAPED_UNICODE));		
	}
?>