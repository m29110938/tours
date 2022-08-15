<?php
function getcoupon($conn,$mid,$coupon_no){
		$rand = rand(1,10);    //sprintf("%04d", rand(1,10));
		$couponid = "QUESTIONNAIRE_COUPON".$rand;   //QUESTIONNAIRE_COUPON1 - QUESTIONNAIRE_COUPON10
		//getcoupon 
		$sql2 = "SELECT * FROM mycoupon where mycoupon_trash=0 and coupon_id = '".$couponid."' and mid=$mid";
		//echo $sql2;
		//exit;
		if ($result2 = mysqli_query($conn, $sql2)){
			if (mysqli_num_rows($result2) == 0){
				
				try {
					//$coupon_no = uniqid();

					$sql="INSERT INTO mycoupon (mid, coupon_no, cid,coupon_id ,coupon_name, coupon_type, coupon_description, coupon_startdate, coupon_enddate, coupon_status, coupon_rule, coupon_discount, discount_amount, coupon_storeid, coupon_for, coupon_picture) ";
					$sql=$sql." select $mid,'$coupon_no',cid,coupon_id ,coupon_name, coupon_type, coupon_description, coupon_startdate, coupon_enddate, coupon_status, coupon_rule, coupon_discount, discount_amount, coupon_storeid, coupon_for, coupon_picture from coupon where coupon_id = '".$couponid."'";
					//echo $sql;
					//exit;
					mysqli_query($conn,$sql) or die(mysqli_error($conn));
					$rvalue = mysqli_affected_rows($conn);
					
					$rvalue = $couponid;
					//echo $rvalue;
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

	return $rvalue;
}
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';

$gender = isset($_POST['gender']) ? $_POST['gender'] : '';
$birthday = isset($_POST['birthday']) ? $_POST['birthday'] : '';
//$location  = isset($_POST['location']) ? $_POST['location'] : '';
//$location_city = isset($_POST['location_city']) ? $_POST['location_city'] : '';
$drink = isset($_POST['drink']) ? $_POST['drink'] : '';
$housework = isset($_POST['housework']) ? $_POST['housework'] : '';
$emotion = isset($_POST['emotion']) ? $_POST['emotion'] : '';
$hobby = isset($_POST['hobby']) ? $_POST['hobby'] : '';

	if (($member_id != '') && ($member_pwd != '') && ($gender != '') && ($birthday != '') && ($drink != '') && ($housework != '') && ($emotion != '') && ($hobby != '') ) {
		//check 帳號/密碼
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';
		
		//if ($others == '') $others="0";
		
		try {
			$link = mysqli_connect($host, $user, $passwd, $database);
			mysqli_query($link,"SET NAMES 'utf8'");

			$member_id  = mysqli_real_escape_string($link,$member_id);
			$member_pwd  = mysqli_real_escape_string($link,$member_pwd);

			$gender  = mysqli_real_escape_string($link,$gender);
			$birthday  = mysqli_real_escape_string($link,$birthday);
			//$location  = mysqli_real_escape_string($link,$location);
			//$location_city  = mysqli_real_escape_string($link,$location_city);
			
			$drink  = mysqli_real_escape_string($link,$drink);
			$housework  = mysqli_real_escape_string($link,$housework);
			$emotion  = mysqli_real_escape_string($link,$emotion);
			$hobby  = mysqli_real_escape_string($link,$hobby);
			
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
						break;
					}	
							
					// save constellation_answer
					$sql="INSERT INTO constellation_answer (mid,gender,birthday, drink, housework, emotion, hobby,inputdate) VALUES";
					$sql=$sql."($mid,$gender,'$birthday', '$drink', '$housework','$emotion' ,'$hobby', NOW());";
					//echo $sql;
					//exit;
					mysqli_query($link,$sql) or die(mysqli_error($link));

					//星座
					$constellationdate = date('m-d', strtotime($birthday));
					$constellationdate = str_replace("-",".",$constellationdate);
					//echo $constellationdate;
					$sql = "SELECT * FROM constellation WHERE endday >= '$constellationdate' and startday <='$constellationdate' ";

					if ($result = mysqli_query($link, $sql)){
						if (mysqli_num_rows($result) > 0){
							while($row = mysqli_fetch_array($result)){
								$cname = $row['cname'];
								$startday = $row['startday'];
								$endday = $row['endday'];
								$description = $row['description'];
								$picture = $row['picture'];
								break;
							}							
						
						}else{
								$cname = "魔羯座";
								$startday = "12.22";
								$endday = "01.20";
								$description = "雖然魔羯座不歡探聽八卦，但適時打探周邊的人與狀況，也是必要的，朋友間要常連絡，維繫感情。工作與課業有不錯表現，任何想法請不要留在紙上談兵，要抓緊機會不要遲疑，做錯比錯過好；多多投資自己，也要注意理財，和家人家也要多點耐心。";
								$picture = "uploads/star012.jpg";
						}
					}else{
						$cname = "魔羯座";
						$startday = "12.22";
						$endday = "01.20";
						$description = "雖然魔羯座不歡探聽八卦，但適時打探周邊的人與狀況，也是必要的，朋友間要常連絡，維繫感情。工作與課業有不錯表現，任何想法請不要留在紙上談兵，要抓緊機會不要遲疑，做錯比錯過好；多多投資自己，也要注意理財，和家人家也要多點耐心。";
						$picture = "uploads/star012.jpg";
					}
					//發券  QUESTIONNAIRE_COUPON1 - QUESTIONNAIRE_COUPON10
					$coupon_no = uniqid();

					$cid = getcoupon($link, $mid,$coupon_no);	
					//echo $cid;
					$fields = array(
						'cname' 		=> $cname,
						'startday' 		=> $startday,
						'endday' 		=> $endday,
						'description' 	=> $description,
						'picture' 		=> $picture,
						'coupon_no' 	=> $coupon_no
					);	
			
					$data["status"]="true";
					$data["code"]="0x0200";
					$data["responseMessage"]="The questionnaire answer has been sended successfully!";	
					$data["constellation"]=$fields;

	
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