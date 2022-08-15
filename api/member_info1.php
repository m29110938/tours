<?php
function encrypt($key, $payload)
{
    //$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
	$iv = "77215989@jotangi";
    $encrypted = openssl_encrypt($payload, 'aes-256-cbc', $key, 1, $iv);
    //return base64_encode($encrypted . '::' . $iv);
	return base64_encode($encrypted);
}

function decrypt($key, $garble)
{
    //list($encrypted_data, $iv) = explode('::', base64_decode($garble), 2);
	$iv = "77215989@jotangi";
	$encrypted_data = base64_decode($garble);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 1, $iv);
}

$key = "AwBHMEUCIQCi7omUvYLm0b2LobtEeRAY";
//$c15 = encrypt($key, $c15);
//$decrypt = decrypt($key, $c15);

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
						$date2 = new DateTime(date("Y-m-d"));
						//echo $date2->format('Y-m-d') . "\n";					
						$sql1 = "SELECT sum(bonus_get) as bonuswillget FROM orderinfo where order_status=1 and pay_status=1 ";
						$sql1 = $sql1." and bonus_date > '".$date2->format('Y-m-d')." 23:59:59'";
						//$sql1 = $sql1." and bonus_date > '2022-01-25 23:59:59'";
						if ($mid != "") {
							$sql1 = $sql1." and member_id='".$mid."'";
						}
						//echo $sql2;
						if ($result1 = mysqli_query($link, $sql1)){
							if (mysqli_num_rows($result1) > 0){
								$rows = array();
								while($row1 = mysqli_fetch_array($result1)){
									//$rows[] = $row2;
									if ($row1['bonuswillget'] == null ){	
										$bonuswillget = 0;
									}else{	
										$bonuswillget = $row1['bonuswillget'];	
									}
								}
							}else{
								$bonuswillget = 0;									
							}
						}	

						// 總點數
						$sql3 = "SELECT member_id, SUM(bonus_get)-SUM(bonus_point) as totalBonus FROM `orderinfo`  where order_status=1 and pay_status=1 and bonus_end_date >=NOW() ";
						if ($mid != "") {
							$sql3 = $sql3." and member_id='".$mid."'";
						}
						//$sql1 = $sql1." and bonus_date > '2022-01-25 23:59:59'";
						// echo $sql3;
						if ($result3 = mysqli_query($link, $sql3)){
							if (mysqli_num_rows($result3) > 0){
								$rows = array();
								while($row3 = mysqli_fetch_array($result3)){
									//$rows[] = $row2;
									if ($row3['totalBonus'] == null ){	
										$totalBonus = 0;
									}else{	
										$totalBonus = $row3['totalBonus'];	
									}
								}
							}else{
								$totalBonus = 0;									
							}
						}	
						
						// echo $bonuswillget."</br>";
						$totalBonus = $totalBonus - $bonuswillget;
						// echo $totalBonus."</br>";

						$sql2 = "SELECT `mid`, `member_id`, `member_pwd`, `member_name`, `member_type`, `member_gender`, `member_email`, `member_birthday`, `member_address`, `member_phone`, `member_picture`, `member_totalpoints`, `member_usingpoints`, `member_status`, `recommend_code`,`member_sid` FROM member where member_trash=0 ";
						$sql2 = $sql2." and mid = ".$mid."";

						//$data = "";
						if ($result2 = mysqli_query($link, $sql2)){
							if (mysqli_num_rows($result2) > 0){
								$rows = array();
								//$i=0;
								while($row2 = mysqli_fetch_array($result2)){
									//$rows[] = $row2;
									$c1= $row2['mid'];

									$c2= $row2['member_id'];
									$c2 = encrypt($key, $c2);
									
									$c3= $row2['member_pwd'];
									$c3 = encrypt($key, $c3);
									
									$c4= $row2['member_name'];
									$c4 = encrypt($key, $c4);
									
									$c5= $row2['member_type'];
									$c6= $row2['member_gender'];
									
									$c7= $row2['member_email'];
									$c7 = encrypt($key, $c7);
									
									$c8= $row2['member_birthday'];
									$c8 = encrypt($key, $c8);
									
									$c9= $row2['member_address'];
									$c9 = encrypt($key, $c9);
									
									$c10= $row2['member_phone'];
									$c10 = encrypt($key, $c10);
									
									$c11= $row2['member_picture'];
									// 隱藏
									$c12= $row2['member_totalpoints'];
									// 改為0
									// $c13= $row2['member_usingpoints'];
									$c13= "0";
									$c14= $row2['member_status'];
									
									$c15= $row2['recommend_code'];
									$c15 = encrypt($key, $c15);
									
									$c16= $row2['member_sid'];
			
									$rows[] = array("0"=> $c1 , "mid"=> $c1 , "1"=> $c2,"member_id"=> $c2, "2"=> $c3, "member_pwd"=> $c3,"3"=> $c4,"member_name"=> $c4, "4"=> $c5,"member_type"=> $c5, "5"=> $c6,"member_gender"=> $c6,"6"=> $c7,"member_email"=> $c7,"7"=> $c8,"member_birthday"=> $c8,"8"=> $c9,"member_address"=> $c9,"9"=> $c10,"member_phone"=> $c10,"10"=> $c11,"member_picture"=> $c11,"11"=> "$totalBonus","member_totalpoints"=> "$totalBonus","12"=> $c13,"member_usingpoints"=> $c13,"13"=> $c14,"member_status"=> $c14,"14"=> $c15,"recommend_code"=> $c15,"15"=> $c16,"member_sid"=> $c16, "16"=> $bonuswillget,"bonuswillget" => $bonuswillget );
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