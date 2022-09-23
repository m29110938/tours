<?php
	session_start();
	//if ($_SESSION['authority']!="1"){
	//	header("Location: main.php");
	//}
	function geocode($address){
	 
		// url encode the address
		$address = urlencode($address);
		// google map geocode api url
		//$url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyCkwwY-i2y7QO55xdRxhB8Ojrhog0BUfqw";
		//$url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyCTysKZXVgovPYPDVYXxy1VDhzrFcYC_oQ";
		$url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyD_1YB9j-QJE91x2_73S7RA3jtOGIBoMKU";
	 
		// get the json response
		$resp_json = file_get_contents($url);
		 
		// decode the json
		$resp = json_decode($resp_json, true);
	 
		// response status will be 'OK', if able to geocode given address 
		if($resp['status']=='OK'){
	 
			// get the important data
			$lati = isset($resp['results'][0]['geometry']['location']['lat']) ? $resp['results'][0]['geometry']['location']['lat'] : "";
			$longi = isset($resp['results'][0]['geometry']['location']['lng']) ? $resp['results'][0]['geometry']['location']['lng'] : "";
			$formatted_address = isset($resp['results'][0]['formatted_address']) ? $resp['results'][0]['formatted_address'] : "";
			 
			// verify if data is complete
			if($lati && $longi && $formatted_address){
			 
				// put the data in the array
				$data_arr = array();            
				 
				array_push(
					$data_arr, 
						$lati, 
						$longi, 
						$formatted_address
					);
				 
				return $data_arr;
				 
			}else{
				return false;
			}
			 
		}
	 
		else{
			echo "<strong>ERROR: {$resp['status']}</strong>";
			return false;
		}
	}
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

		$store_type = isset($_POST['store_type']) ? $_POST['store_type'] : '0';
		$store_type  = mysqli_real_escape_string($link,$store_type);
		
		$store_name = isset($_POST['store_name']) ? $_POST['store_name'] : '';
		$store_name  = mysqli_real_escape_string($link,$store_name);

		$shopping_area = isset($_POST['shopping_area']) ? $_POST['shopping_area'] : '';
		$shopping_area  = mysqli_real_escape_string($link,$shopping_area);

		$store_phone = isset($_POST['store_phone']) ? $_POST['store_phone'] : '';
		$store_phone  = mysqli_real_escape_string($link,$store_phone);

		$store_address = isset($_POST['store_address']) ? $_POST['store_address'] : '0';
		$store_address  = mysqli_real_escape_string($link,$store_address);
		
		try {
			$data_arr = geocode($store_address);
			if($data_arr){
				 
				$store_latitude = $data_arr[0];		//24.97
				$store_longitude = $data_arr[1];    //121.24
				//$formatted_address = $data_arr[2];
			} else {
				$store_latitude = "";
				$store_longitude = "";
			}
		} catch (Exception $e) {
			$store_latitude = "";
			$store_longitude = "";
		}	
		
		$store_website = isset($_POST['store_website']) ? $_POST['store_website'] : '';
		$store_website  = mysqli_real_escape_string($link,$store_website);

		$store_facebook = isset($_POST['store_facebook']) ? $_POST['store_facebook'] : '';
		$store_facebook  = mysqli_real_escape_string($link,$store_facebook);

		$store_descript = isset($_POST['store_descript']) ? $_POST['store_descript'] : '';
		$store_descript  = mysqli_real_escape_string($link,$store_descript);

		$store_news = isset($_POST['store_news']) ? $_POST['store_news'] : '';
		$store_news  = mysqli_real_escape_string($link,$store_news);

		$store_opentime = isset($_POST['store_opentime']) ? $_POST['store_opentime'] : '';
		$store_opentime  = mysqli_real_escape_string($link,$store_opentime);

		$store_status = isset($_POST['store_status']) ? $_POST['store_status'] : '0';
		$store_status  = mysqli_real_escape_string($link,$store_status);


		$sql2 = "select * from attraction where store_id = '$store_id' and store_trash=0 and store_status = 0";
		if ($result2 = mysqli_query($link, $sql2)) {
			if (mysqli_num_rows($result2) == 0) {
				
				echo "<script>alert('新增成功');</script>";
				$sql="INSERT INTO attraction (store_id,store_type,store_name,shopping_area,store_phone,store_address,store_website,store_facebook,store_descript,store_news,store_opentime,store_latitude,store_longitude,store_status) VALUES ('$store_id','$store_type','$store_name',$shopping_area,'$store_phone','$store_address','$store_website','$store_facebook','$store_descript','$store_news','$store_opentime','$store_latitude','$store_longitude','$store_status');";

				// echo $sql;
				//exit;

				mysqli_query($link,$sql) or die(mysqli_error($link));
				
				$_SESSION['saveresult']="新增景點資料成功!";
			
			}else{
				echo "<script>alert('景點代號重複');</script>";
			}
		}
		
			
		mysqli_close($link);
		// once saved, redirect back to the view page
		echo "<script>window.location.href='attraction.php?act=Qry';</script>";
		// header("Location: attraction.php?act=Qry");

	}else{
		header("Location: logout.php");
	}
?>