<?php
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
$sid = isset($_POST['sid']) ? $_POST['sid'] : '';

	if (($member_id != '') && ($member_pwd != '') && ($sid != '')) {
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
			$sid  = mysqli_real_escape_string($link,$sid);
			
			$sql = "SELECT * FROM member where member_trash=0  and member_type=1 ";
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
					try {

						$sql2 = "SELECT a.*,b.store_service,b.bonus_status,b.contract_startdate,b.contract_enddate,b.bonus_trash FROM bonus_store as a  ";
						$sql2 = $sql2." inner join (SELECT bid,store_service,bonus_status,bonus_trash,contract_startdate,contract_enddate FROM bonus_setting) as b on a.bid=b.bid ";
						$sql2 = $sql2." where a.rid > 0 and b.bonus_trash=0 ";
						if ($sid != "") {	
							$sql2 = $sql2." and a.store_id=".$sid."";
						}
						$sql2 = $sql2." order by bid,store_id";
//echo $sql2;
//exit;				
						//$timeperiod = array();
						if ($result2 = mysqli_query($link, $sql2)){
							if (mysqli_num_rows($result2) > 0){
								$data2 = array();
								while($row2 = mysqli_fetch_array($result2)){
									$data2 = [
										'0'    					=> $row2['store_id'],   
										'sid'    				=> $row2['store_id'],   
										'1'    					=> $row2['store_service'],   
										'store_service'    		=> $row2['store_service'],   
										'2'   					=> $row2['contract_startdate'],
										'contract_startdate'   	=> $row2['contract_startdate'],
										'3'  					=> $row2['contract_enddate'],
										'contract_enddate'  	=> $row2['contract_enddate']
									];									
									//array_push($timeperiod, $data2);
								}
								//$data["status"]="true";
								//$data["code"]="0x0200";
								//$data["responseMessage"]=$data2;  //$timeperiod;	
								$data = $data2;
							}else{
								$data["status"]="false";
								$data["code"]="0x0201";
								$data["responseMessage"]="This store is not a bonus store!";	
							}
						}else{
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