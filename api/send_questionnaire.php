<?php
function getcoupon($conn,$mid,$sqid,$eqid){
	//rid,member_id,qid,answer_date,a01-a10,others,others_remark,answer_trash
	$sql = "SELECT * FROM questionnaire_answer where answer_trash=0 and member_id=$mid and qid >= $sqid  and qid  <= $eqid ";
	$rvalue = 0;
	if ($result = mysqli_query($conn, $sql)){
		if (mysqli_num_rows($result) > 0){
			$gender = 0;
			$age = 0;
			$couponid='';
			while($row = mysqli_fetch_array($result)){
				//$qidd = $row['qid'];
				//$a01 = $row['a01'];
				switch ($row['qid']) {
					case 20:
						if ($row['a01'] == 1) $gender = 1;	//男
						if ($row['a02'] == 1) $gender = 2;  //女
						break;
					case 21:
						if (($row['a01'] == 1)||($row['a02'] == 1)) $age = 1;	//30以下
						if (($row['a03'] == 1)||($row['a04'] == 1)) $age = 2;  //30-60以上
						if ($row['a05'] == 1) $age = 3;  //60以上
						break;
					case 31:
						if ($row['a01'] == 1) $gender = 11;	//男
						if ($row['a02'] == 1) $gender = 12;  //女
						break;
					case 32:
						if (($row['a01'] == 1)||($row['a02'] == 1)) $age = 11;	//30以下
						if (($row['a03'] == 1)||($row['a04'] == 1)||($row['a05'] == 1)) $age = 12;  //30以上
						break;
					case 36:
						if ($row['a01'] == 1) $period = 11;	//上午
						if ($row['a02'] == 1) $period = 12; //下午
						if ($row['a03'] == 1) $period = 13; //晚上
						break;
				}
			}
			if ($gender < 10) {
				if (($gender == 2)&&($age == 1)) $couponid='QUESTIONNAIRE_COUPON5';  //QUESTIONNAIRE_COUPON1~4,5
				if (($gender == 2)&&($age == 2)) $couponid='QUESTIONNAIRE_COUPON6';  //QUESTIONNAIRE_COUPON1~4,6
				if (($gender == 1)||($age == 3)) $couponid='QUESTIONNAIRE_COUPON1';	//QUESTIONNAIRE_COUPON1~4
				if ($couponid == '') $couponid='QUESTIONNAIRE_COUPON2'; //QUESTIONNAIRE_COUPON1~4
			}else {
				if (($gender == 12)&&($age == 11)&&($period == 11)) $couponid='QUESTIONNAIRE_COUPON9';  //QUESTIONNAIRE_COUPON9~10
				if (($gender == 12)&&($age == 12)&&($period == 11)) $couponid='QUESTIONNAIRE_COUPON10';  //QUESTIONNAIRE_COUPON9~10,8
				if (($gender == 12)&&(($period == 12)||($period == 13))) $couponid='QUESTIONNAIRE_COUPON8';  //QUESTIONNAIRE_COUPON8
				if ($gender == 11) $couponid='QUESTIONNAIRE_COUPON8';	//QUESTIONNAIRE_COUPON8
				if ($couponid == '') $couponid='QUESTIONNAIRE_COUPON8'; //QUESTIONNAIRE_COUPON8
			}
			//getcoupon 
			$sql2 = "SELECT * FROM mycoupon where mycoupon_trash=0 and coupon_id = '".$couponid."' and mid=$mid";
			//echo $sql2;
			//exit;
			if ($result2 = mysqli_query($conn, $sql2)){
				if (mysqli_num_rows($result2) == 0){
					
					try {
						$coupon_no = uniqid();

						$sql="INSERT INTO mycoupon (mid, coupon_no, cid,coupon_id ,coupon_name, coupon_type, coupon_description, coupon_startdate, coupon_enddate, coupon_status, coupon_rule, coupon_discount, discount_amount, coupon_storeid, coupon_for, coupon_picture) ";
						$sql=$sql." select $mid,'$coupon_no',cid,coupon_id ,coupon_name, coupon_type, coupon_description, coupon_startdate, coupon_enddate, coupon_status, coupon_rule, coupon_discount, discount_amount, coupon_storeid, coupon_for, coupon_picture from coupon where coupon_id = '".$couponid."'";
						//echo $sql;
						//exit;
						mysqli_query($conn,$sql) or die(mysqli_error($conn));
						$rvalue = mysqli_affected_rows($conn);

					} catch (Exception $e) {
						$rvalue = 0;							
					}
				}
				else {
					$rvalue = 0;							
				}
			}else {
				$rvalue = 0;					
			}								
		}
	}
	return $rvalue;
}
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';

$qid = isset($_POST['qid']) ? $_POST['qid'] : '';
$answer = isset($_POST['answer']) ? $_POST['answer'] : '';

$others = isset($_POST['others']) ? $_POST['others'] : '0';
$others_remark = isset($_POST['others_remark']) ? $_POST['others_remark'] : '';

	if (($member_id != '') && ($member_pwd != '') && ($qid != '')&& ($answer != '')) {
		//check 帳號/密碼
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';
		
		if ($others == '') $others="0";
		
		try {
			$link = mysqli_connect($host, $user, $passwd, $database);
			mysqli_query($link,"SET NAMES 'utf8'");

			$member_id  = mysqli_real_escape_string($link,$member_id);
			$member_pwd  = mysqli_real_escape_string($link,$member_pwd);

			$qid  = mysqli_real_escape_string($link,$qid);
			$answer  = mysqli_real_escape_string($link,$answer);

			$others_remark  = mysqli_real_escape_string($link,$others_remark);

			
			$sql = "SELECT * FROM member where member_trash=0 and member_type=1 ";
			if ($member_id != "") {	
				$sql = $sql." and member_id='".$member_id."'";
			}
			if ($member_pwd != "") {	
				$sql = $sql." and member_pwd='".$member_pwd."'";
			}
			if ($result = mysqli_query($link, $sql)){
				if (mysqli_num_rows($result) > 0){
					$mid=0;
					while($row = mysqli_fetch_array($result)){
						$mid = $row['mid'];
						//$member_name = $row['member_name'];
						//$member_id = $row['member_id'];
					}	
					if ($qid != "") {	//檢查問券是否存在
						$sql2 = "SELECT * FROM questionnaire where questionnaire_trash=0 and qid=$qid ";
						if ($result2 = mysqli_query($link, $sql2)){
							if (mysqli_num_rows($result2) > 0){
								//$ans[]=[1,0,0,0,0,0,0,0,0,0];
								$ans = explode(",",$answer);
								if (count($ans) <= 10) {
									for ($i=0; $i<10; $i++) {
										$ans[$i]=isset($ans[$i]) ? $ans[$i] : 0;
									}
							
									// add questionnaire_answer
									$sql="INSERT INTO questionnaire_answer (member_id,qid,answer_date, a01, a02, a03, a04,a05,a06,a07,a08,a09,a10,others, others_remark) VALUES";
									$sql=$sql."($mid,$qid, NOW(), $ans[0], $ans[1], $ans[2], $ans[3] ,$ans[4] ,$ans[5] ,$ans[6] ,$ans[7] ,$ans[8] ,$ans[9] , $others, '$others_remark');";
									//echo $sql;
									//exit;
									mysqli_query($link,$sql) or die(mysqli_error($link));

									$data["status"]="true";
									$data["code"]="0x0200";
									$data["responseMessage"]="The questionnaire answer has been sended successfully!";	
									//餐飲最後一題
									if ($qid == 30){
								
										getcoupon($link, $mid,20,$qid);
									}
									//美容美髮最後一題
									if ($qid == 41){
										getcoupon($link, $mid,31,$qid);
									}
								}else {
									$data["status"]="false";
									$data["code"]="0x0206";
									$data["responseMessage"]="The questionnaire answer is wrong!";									
								}
							}else {
								$data["status"]="false";
								$data["code"]="0x0205";
								$data["responseMessage"]="The questionnaire id is wrong!";									
							}
						}else{
							$data["status"]="false";
							$data["code"]="0x0204";
							$data["responseMessage"]="SQL fail!";								
						}
					}				
					
				}else{
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
		//echo "need mail and password!";
		$data["status"]="false";
		$data["code"]="0x0203";
		$data["responseMessage"]="API parameter is required!";
		header('Content-Type: application/json');
		echo (json_encode($data, JSON_UNESCAPED_UNICODE));			
	}
?>