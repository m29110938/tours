<?php
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';
//$sid = isset($_POST['sid']) ? $_POST['sid'] : '';
// $profit_month = isset($_POST['profit_month']) ? $_POST['profit_month'] : '';
$profit_startdate = isset($_POST['profit_startdate']) ? $_POST['profit_startdate'] : '';
$profit_enddate = isset($_POST['profit_enddate']) ? $_POST['profit_enddate'] : '';

	if (($member_id != '') && ($member_pwd != '')) {
	// if (($member_id != '') && ($member_pwd != '') && ($profit_month != '')) {
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
			//$sid  = mysqli_real_escape_string($link,$sid);
			// $profit_month  = mysqli_real_escape_string($link,$profit_month);
			$profit_startdate  = mysqli_real_escape_string($link,$profit_startdate);
			$profit_enddate  = mysqli_real_escape_string($link,$profit_enddate);


			
			$sql = "SELECT * FROM member where member_trash=0 and member_type=2 ";
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
					$membersid=0;
					while($row = mysqli_fetch_array($result)){
						//$mid = $row['mid'];
						//$membername = $row['member_name'];
						$membersid = $row['member_sid'];
					}
					try {
						//a.total_amount,a.total_order
						$sql2 = "SELECT a.pid,a.store_id, a.profit_month,a.start_date, a.end_date, a.total_amount,a.total_order,a.total_amountD,a.total_amountG,a.total_amountI,a.total_amountJ,a.profit_pdf,a.billing_date,a.billing_flag,a.pay_date FROM profit a ";
						//$sql2 = $sql2." inner join ( select mid,member_id,member_name,member_trash from member) as b ON a.member_id= b.mid ";
						$sql2 = $sql2." where a.pid>0 and a.profit_trash='0' ";
						if ($membersid != 0) {	
							$sql2 = $sql2." and a.store_id = ".$membersid."";
						}	

						if ($profit_startdate != "") {	
							$profit_startdate = $profit_startdate."-01";
							$sql2 = $sql2." and a.profit_month >= '".$profit_startdate."'";
						}if ($profit_enddate != "") {	
							$profit_enddate = $profit_enddate."-31";
							$sql2 = $sql2." and a.profit_month <= '".$profit_enddate."'";
						}
						// $sql2 = $sql2." and a.profit_month like '%".$profit_month."%'";
						//$sql2 = $sql2." order by order_date desc";

						// echo $sql2;
						//exit;
						//$data = "";
						if ($result2 = mysqli_query($link, $sql2)){
							if (mysqli_num_rows($result2) > 0){
								$rows = array();
								while($row2 = mysqli_fetch_array($result2)){
									$rows[] = $row2;

									//banner_subject, banner_date, banner_enddate, banner_descript	,banner_picture,banner_link
								}
								header('Content-Type: application/json');
								echo (json_encode($rows, JSON_UNESCAPED_UNICODE));
								exit;
							}else{
								$data["status"]="false";
								$data["code"]="0x0201";
								$data["responseMessage"]="This is no profit data!";									
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