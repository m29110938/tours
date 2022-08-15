<?php

		$host = 'localhost';
		$user = 'ibattery_user';
		$passwd = 'GTBYjXcqT4REexZ8';
		$database = 'ibattery';

		try {
			$link = mysqli_connect($host, $user, $passwd, $database);
			mysqli_query($link,"SET NAMES 'utf8'");
			
			$sql = "SELECT * FROM general_settings ";

			if ($result = mysqli_query($link, $sql)){
				if (mysqli_num_rows($result) > 0){
					$data["android"]="";
					$data["ios"]="";
						
					while($row = mysqli_fetch_array($result)){

						switch ($row['type']) {
						case "android_ver":
							$data["android"]=$row['value'];
							break;
						case "ios_ver":
							$data["ios"]=$row['value'];
							break;
						}
					}
					$data["status"]="true";
					$data["code"]="0x0200";

				}else{
					//$this->_response(null, 400, validation_errors());
					//echo "error mail or password!";
					$data["status"]="false";
					$data["code"]="0x0201";
					$data["responseMessage"]="Can't find the general setting data!";					
				}
			}else{
				//echo "need mail and password!";
				$data["status"]="false";
				$data["code"]="0x0203";
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
		echo (json_encode($data, JSON_PRETTY_PRINT));
 

?>