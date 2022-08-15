<?php
session_start();
//if ($_SESSION['authority']=="3"){
//	header("Location: main.php");
//	exit;
//}

$tid = "{$_REQUEST["tid2"]}";
$errmsg = "";
$date = date_create();
$file_name = date_timestamp_get($date);
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload2"]["name"]);
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$target_file = $target_dir . $file_name . "." . $imageFileType;

//echo $target_file;
//exit;
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload2"]["tmp_name"]);
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
if ($_FILES["fileToUpload2"]["size"] > 1024000) {
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
	$_SESSION['downloadlog']=$errmsg;
	header("Location: store.php");
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload2"]["tmp_name"], $target_file)) {
        //echo "The file ". basename( $_FILES["fileToUpload2"]["name"]). " has been uploaded.";
		//echo $txt;
		
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';
		
		$link = mysqli_connect($host, $user, $passwd, $database);
		mysqli_query($link,"SET NAMES 'utf8'");

		$image_info = getimagesize($target_file);
		//$image_width = $image_info[0];
		//$image_height = $image_info[1];

		//$sql = "INSERT INTO mapinfo (mapname, mappath,mapfile,defaultmap,mapwidth,mapheight,inputdttime) VALUES ('".$txt."', '".$target_dir."', '".basename($target_file)."','0',".$image_width.",".$image_height.",NOW())";
		//echo $sql;
		$sql = "update coupon set coupon_picture='".$target_file."',coupon_updated_at=NOW() where cid=".$tid;
		//$result = mysql_query($sql) or die('MySQL query error');
		mysqli_query($link,$sql) or die(mysqli_error($link));
		
			//echo $result;
		$_SESSION['downloadlog']="1";
		mysqli_close($link);
		header("Location: coupon.php");
		 
    } else {
        $errmsg = $errmsg."上傳文件時出錯.";
		$_SESSION['downloadlog']=$errmsg;
		header("Location: coupon.php");
    }

}
?>