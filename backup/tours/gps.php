<?php 
// function to geocode address, it will return false if unable to geocode address
function geocode($address){
 
    // url encode the address
    $address = urlencode($address);
     
    // google map geocode api url
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyCkwwY-i2y7QO55xdRxhB8Ojrhog0BUfqw";
 
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
//$gmap = new MapCalc('AIzaSyCkwwY-i2y7QO55xdRxhB8Ojrhog0BUfqw'); //填入金鑰
//$data = $gmap->getAddressLatLng('桃園市中壢區復華七街23-1號'); //填入地址

	$host = 'localhost';
	$user = 'tours_user';
	$passwd = 'tours0115';
	$database = 'toursdb';
	
	$link = mysqli_connect($host, $user, $passwd, $database);
	mysqli_query($link,"SET NAMES 'utf8'");
	
	$sql = "SELECT * FROM store where store_trash=0 ";
	if ($result = mysqli_query($link, $sql)){

		while($row = mysqli_fetch_array($result)){
			$tid = $row['sid'];
			$store_address = $row['store_address'];

			$data_arr = geocode($store_address);
			if($data_arr){
				$latitude = $data_arr[0];
				$longitude = $data_arr[1];
				$sql2="update store set store_latitude='$latitude',store_longitude='$longitude' where sid=$tid ;";
				mysqli_query($link,$sql2) or die(mysqli_error($link));
	//echo $sql;
	//exit;
			}
			sleep(1);
		}
		echo "OK";
	}
	//$data_arr = geocode("桃園市中壢區復華七街23-1號");
    //if($data_arr){
    //     
    //    	$latitude = $data_arr[0];
    //    	$longitude = $data_arr[1];
    //   	$formatted_address = $data_arr[2];
    //		echo $latitude . "<br/>";          
	//		echo $longitude;
	//}
?>