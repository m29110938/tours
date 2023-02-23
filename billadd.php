<?php
	session_start();
	//if ($_SESSION['authority']!="1"){
	//	header("Location: main.php");
	//}
	// function geocode($address){
	 
	// 	// url encode the address
	// 	$address = urlencode($address);
	// 	// google map geocode api url
	// 	//$url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyCkwwY-i2y7QO55xdRxhB8Ojrhog0BUfqw";
	// 	//$url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyCTysKZXVgovPYPDVYXxy1VDhzrFcYC_oQ";
	// 	// $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyD_1YB9j-QJE91x2_73S7RA3jtOGIBoMKU";
	 
	// 	// get the json response
	// 	// $resp_json = file_get_contents($url);
		 
	// 	// decode the json
	// 	// $resp = json_decode($resp_json, true);
	 
	// 	// response status will be 'OK', if able to geocode given address 
	// 	// if($resp['status']=='OK'){
	 
	// 	// 	// get the important data
	// 	// 	$lati = isset($resp['results'][0]['geometry']['location']['lat']) ? $resp['results'][0]['geometry']['location']['lat'] : "";
	// 	// 	$longi = isset($resp['results'][0]['geometry']['location']['lng']) ? $resp['results'][0]['geometry']['location']['lng'] : "";
	// 	// 	$formatted_address = isset($resp['results'][0]['formatted_address']) ? $resp['results'][0]['formatted_address'] : "";
			 
	// 	// 	// verify if data is complete
	// 	// 	if($lati && $longi && $formatted_address){
			 
	// 	// 		// put the data in the array
	// 	// 		$data_arr = array();            
				 
	// 	// 		array_push(
	// 	// 			$data_arr, 
	// 	// 				$lati, 
	// 	// 				$longi, 
	// 	// 				$formatted_address
	// 	// 			);
				 
	// 	// 		return $data_arr;
				 
	// 	// 	}else{
	// 	// 		return false;
	// 	// 	}
			 
	// 	// }
	 
	// 	// else{
	// 	// 	echo "<strong>ERROR: {$resp['status']}</strong>";
	// 	// 	return false;
	// 	// }
	// }
	//---------------------------------------------------------------------------------------------------------
	$act = isset($_POST['act']) ? $_POST['act'] : '';
	if ($act == 'Add') {
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';
		
		$link = mysqli_connect($host, $user, $passwd, $database);
		mysqli_query($link,"SET NAMES 'utf8'");
				
		$store_id = isset($_POST['store_id']) ? $_POST['store_id'] : '';
		$store_id  = mysqli_real_escape_string($link,$store_id);

		$register_name = isset($_POST['register_name']) ? $_POST['register_name'] : '';
		$register_name  = mysqli_real_escape_string($link,$register_name);
		
		$tax_id = isset($_POST['tax_id']) ? $_POST['tax_id'] : '';
		$tax_id  = mysqli_real_escape_string($link,$tax_id);

		$manager_name = isset($_POST['manager_name']) ? $_POST['manager_name'] : '';
		$manager_name  = mysqli_real_escape_string($link,$manager_name);

		$register_address = isset($_POST['register_address']) ? $_POST['register_address'] : '';
		$register_address  = mysqli_real_escape_string($link,$register_address);

		$bank_name = isset($_POST['bank_name']) ? $_POST['bank_name'] : '';
		$bank_name  = mysqli_real_escape_string($link,$bank_name);
		
		$branch_name = isset($_POST['branch_name']) ? $_POST['branch_name'] : '';
		$branch_name  = mysqli_real_escape_string($link,$branch_name);

		$bank_no = isset($_POST['bank_no']) ? $_POST['bank_no'] : '';
		$bank_no  = mysqli_real_escape_string($link,$bank_no);

		$account_no = isset($_POST['account_no']) ? $_POST['account_no'] : '';
		$account_no  = mysqli_real_escape_string($link,$account_no);

		$account_name = isset($_POST['account_name']) ? $_POST['account_name'] : '';
		$account_name  = mysqli_real_escape_string($link,$account_name);

		$name = isset($_POST['name']) ? $_POST['name'] : '';
		$name  = mysqli_real_escape_string($link,$name);

		$job_title = isset($_POST['job_title']) ? $_POST['job_title'] : '';
		$job_title  = mysqli_real_escape_string($link,$job_title);

		$office_phone = isset($_POST['office_phone']) ? $_POST['office_phone'] : '';
		$office_phone  = mysqli_real_escape_string($link,$office_phone);
		
		$mobile = isset($_POST['mobile']) ? $_POST['mobile'] : '';
		$mobile  = mysqli_real_escape_string($link,$mobile);
		
		$email = isset($_POST['email']) ? $_POST['email'] : '';
		$email  = mysqli_real_escape_string($link,$email);
		
		$bill_address = isset($_POST['bill_address']) ? $_POST['bill_address'] : '';
		$bill_address  = mysqli_real_escape_string($link,$bill_address);


		$sql2 = "select * from store_bill where tax_id = '$tax_id' and store_bill_trash=0";
		if ($result2 = mysqli_query($link, $sql2)) {
			if (mysqli_num_rows($result2) == 0) {
				
				echo "<script>alert('新增成功');</script>";
				$sql="INSERT INTO store_bill (store_id,register_name,tax_id,manager_name,register_address,bank_name,branch_name,bank_no,account_no,account_name,name,job_title,office_phone,mobile,email,bill_address) VALUES ('$store_id','$register_name','$tax_id',$manager_name,'$register_address','$bank_name','$branch_name','$bank_no','$account_no','$account_name','$name','$job_title','$office_phone','$mobile','$email','$bill_address');";

				// echo $sql;
				//exit;

				mysqli_query($link,$sql) or die(mysqli_error($link));
				
				$_SESSION['saveresult']="新增店家帳務成功!";
			
			}else{
				echo "<script>alert('統一編號重複');</script>";
			}
		}
		
			
		// once saved, redirect back to the view page
		echo "<script>window.location.href='bill.php?act=Qry';</script>";
		// header("Location: store.php?act=Qry");

		mysqli_close($link);
	}else{
		header("Location: logout.php");
	}
?>