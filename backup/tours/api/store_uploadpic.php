<?php

$member_id = "{$_REQUEST["member_id"]}";
$member_pwd = "{$_REQUEST["member_pwd"]}";
$sid = "{$_REQUEST["sid"]}";

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

					$errmsg = "";
					$date = date_create();
					$file_name = date_timestamp_get($date);
					$target_dir = "../uploads/";
					$target_file = $target_dir . basename($_FILES["upload_filename"]["name"]);
					$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
					$target_file = $target_dir . $file_name . "." . $imageFileType;

					//echo $target_file;
					//exit;
					$uploadOk = 1;
					$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
					// Check if image file is a actual image or fake image
					if(isset($_POST["submit"])) {
						$check = getimagesize($_FILES["upload_filename"]["tmp_name"]);
						if($check !== false) {
							//echo "File is an image - " . $check["mime"] . ".";
							$uploadOk = 1;
						} else {
							$errmsg = $errmsg."上傳的檔案不是圖檔.";
							$uploadOk = 0;
						}
					}
					// Check if file already exists
					if (file_exists($target_file)) {
						$errmsg = $errmsg."檔案已經存在.";
						$uploadOk = 0;
					}
					// Check file size
					if ($_FILES["upload_filename"]["size"] > 1024000) {
						$errmsg = $errmsg."圖檔太大了(1024K).";
						$uploadOk = 0;
					}
					// Allow certain file formats
					if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
					&& $imageFileType != "gif" ) {
						$errmsg = $errmsg."只支援JPG, JPEG, PNG & GIF格式的圖檔.";
						$uploadOk = 0;
					}
					// Check if $uploadOk is set to 0 by an error
					if ($uploadOk == 0) {
						$errmsg = $errmsg."檔案未上傳.";
						//header("Location: store.php");
						
						$data["status"]="false";
						$data["code"]="0x0201";
						$data["responseMessage"]=$errmsg;	
						
					// if everything is ok, try to upload file
					} else {
						if (move_uploaded_file($_FILES["upload_filename"]["tmp_name"], $target_file)) {
							//echo "The file ". basename( $_FILES["upload_filename"]["name"]). " has been uploaded.";
							//echo $txt;
							
							$image_info = getimagesize($target_file);

							//echo $sql;
							$target_file2 = "uploads/" . $file_name . "." . $imageFileType;
							$sql = "update store set store_picture='".$target_file2."',store_updated_at=NOW() where sid=".$sid."";
							//$result = mysql_query($sql) or die('MySQL query error');
							mysqli_query($link,$sql) or die(mysqli_error($link));
							
								//echo $result;
							//mysqli_close($link);
							$data["status"]="true";
							$data["code"]="0x0200";
							$data["responseMessage"]="This picture is successfully upload!";	
							 
						} else {
							$data["status"]="false";
							$data["code"]="0x0206";
							$data["responseMessage"]="Copy file error!";	
						}
					}				
					
				} catch (Exception $e) {

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