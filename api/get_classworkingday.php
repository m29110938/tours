<?php
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$pid = isset($_POST['pid']) ? $_POST['pid'] : '';
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
if ($start_date == '') $start_date = date('Y-m-d');
//echo $start_date;
$end_date = date('Y-m-d', strtotime($start_date. ' + 1 month'));

//is_holiday($link, $hid, $new_date)
function is_holiday($conn,$sid,$check_date){
	try {
		$sql2 = "SELECT * FROM `holiday` where wid > 0 ";
		//echo $sql;
		if ($sid != "") {	
			$sql2 = $sql2." and store_id=".$sid."";
		}
		if ($check_date != "") {	
			$sql2 = $sql2." and holiday='".$check_date."'";
		}		
		//echo $sql2;
		if ($result2 = mysqli_query($conn, $sql2)){
			if (mysqli_num_rows($result2) > 0){
				$fields1 = "H";
			}else{
				$fields1 = "W";				
			}
		}else{
			$fields1="W";
		}
	} catch (Exception $e) {
		$fields1="W";
	}	
	return $fields1;
}

//is_vacation
function is_vacation($conn,$hid,$check_date){
	try {
		$sql2 = "SELECT * FROM `vacation` where vid > 0 ";
		//echo $sql;
		if ($hid != "") {	
			$sql2 = $sql2." and hid=".$hid."";
		}
		if ($check_date != "") {	
			$sql2 = $sql2." and vacation='".$check_date."'";
		}		
		//echo $sql2;
		if ($result2 = mysqli_query($conn, $sql2)){
			if (mysqli_num_rows($result2) > 0){
				$fields2 = "V";
			}else{
				$fields2 = "";				
			}
		}else{
			$fields2="";
		}
	} catch (Exception $e) {
		$fields2="";
	}	
	return $fields2;
}

	if (($member_id != '') && ($member_pwd != '') && ($pid != '')) {
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
			$pid  = mysqli_real_escape_string($link,$pid);

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
						//$membername = $row['member_name'];
						
					}
			
					$sql2 = "SELECT a.*,b.store_id FROM `program` as a inner join (select cid,store_id from class) as b on a.cid=b.cid where a.program_trash=0 and a.pid = ".$pid."";
					//echo $sql2;
					//exit;
					if ($result2 = mysqli_query($link, $sql2)){
						if (mysqli_num_rows($result2) > 0){
							while($row2 = mysqli_fetch_array($result2)){
								$sid = $row2['store_id'];
								//$membername = $row['member_name'];
								break;
							}

							$sql2 = "SELECT * FROM `timeperiod` where store_id = ".$sid." ";
							//echo $sql2;
							//exit;
							$timeperiod = array();
							if ($result2 = mysqli_query($link, $sql2)){
								if (mysqli_num_rows($result2) > 0){
									while($row2 = mysqli_fetch_array($result2)){
										array_push($timeperiod, $row2['w0']);
										array_push($timeperiod, $row2['w1']);
										array_push($timeperiod, $row2['w2']);
										array_push($timeperiod, $row2['w3']);
										array_push($timeperiod, $row2['w4']);
										array_push($timeperiod, $row2['w5']);
										array_push($timeperiod, $row2['w6']);
									}
								}	
							}
							//var_dump($timeperiod);
							if ($timeperiod != null) {
								try {
									//echo $sql;
									//exit;		
									$new_date = $start_date;
									
									$data2 = array();
									$responseMessage = array();								
									while($new_date < $end_date){

										$HStr = is_holiday($link, $sid, $new_date);

										//is_vacation($link, $hid, $new_date)
										if ($HStr == "W") {	
											//$VStr = is_vacation($link, $pid, $new_date);
											$VStr = "";
											$Weeknummer = date('w', strtotime($new_date));
											
											if ($VStr != "") {
												//echo $new_date.' '.$VStr.' '.$timeperiod[$Weeknummer].' ';
												$data2 = [
													'workingdate'    => $new_date,   
													'workingtype'    => $VStr,   
													'timeperiod'     => $timeperiod[$Weeknummer]
												];											
											}else	{
												//echo $new_date.' '.$HStr.' '.$timeperiod[$Weeknummer].' ';
												if ($timeperiod[$Weeknummer] == '0'){
													$data2 = [
														'workingdate'    => $new_date,   
														'workingtype'    => 'C',   
														'timeperiod'     => $timeperiod[$Weeknummer]
													];												
												}else	{
													$data2 = [
														'workingdate'    => $new_date,   
														'workingtype'    => $HStr,   
														'timeperiod'     => $timeperiod[$Weeknummer]
													];												
													
												}	
						
											}	
										}else	{
											//echo $new_date.' '.$HStr.' 0 ';
												$data2 = [
													'workingdate'    => $new_date,   
													'workingtype'    => $HStr,   
													'timeperiod'     => '0'
												];												
										}
										$new_date = date('Y-m-d', strtotime($new_date. ' + 1 day'));
										array_push($responseMessage, $data2);
									}

									
									
									mysqli_query($link,$sql) or die(mysqli_error($link));
									$rvalue = mysqli_affected_rows($link);
									if ($rvalue > 0) {
										$data["status"]="true";
										$data["code"]="0x0200";
										$data["responseMessage"]=$responseMessage;	
									}else{
										$data["status"]="false";
										$data["code"]="0x0201";
										$data["responseMessage"]="Get working day fail!";								
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
									$data["code"]="0x0207";
									$data["responseMessage"]="Get timeperiod fail!";								
							}
						}
						else {
							$data["status"]="false";
							$data["code"]="0x0206";
							$data["responseMessage"]="The program id is wrong!";								
						}
					}else {
						$data["status"]="false";
						$data["code"]="0x0204";
						$data["responseMessage"]="SQL fail!";					
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