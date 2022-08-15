<?php

	// $host = 'localhost';
	// $user = 'tours_user';
	// $passwd = 'tours0115';
	// $database = 'price';
	// $link = mysqli_connect($host, $user,$passwd, $database);
	// mysqli_query($link,"SET NAMES 'utf8'");

	// $sql = "SELECT *,SUM(price) as total FROM `pay`";
	// if ($result = mysqli_query($link, $sql)){
	// 	if (mysqli_num_rows($result) > 0){
	// 		$rows = array();
	// 		while($row = mysqli_fetch_array($result)){
	// 			$rows[] = $row;
	// 			$total = $row['total'];
	// 		}
	// 	}
	// }
	// echo ($total);
	// // header('Content-Type: application/json');
	// // echo (json_encode($rows, JSON_UNESCAPED_UNICODE));
	// exit;
	
?>

<!DOCTYPE html>
<html lang="zh">

<head>
	<title>test</title>
    <meta charset="utf-8">
</head>

<body>	
	<?php	
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'price';
		$link = mysqli_connect($host, $user,$passwd, $database);
		mysqli_query($link,"SET NAMES 'utf8'");

		$sql = "SELECT *,SUM(price) as total FROM `pay`";
		if ($result = mysqli_query($link, $sql)){
			if (mysqli_num_rows($result) > 0){
				$rows = array();
				while($row = mysqli_fetch_array($result)){
					$rows[] = $row;
					$total = $row['total'];
				}
			}
		}
		echo ("total = ".$total);
		// header('Content-Type: application/json');
		// echo (json_encode($rows, JSON_UNESCAPED_UNICODE));
		exit;
	?>
</body>

</html>