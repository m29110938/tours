<?php
	//session_start();
	//if ($_SESSION['authority']!="1"){
	//	header("Location: main.php");
	//}
	$act = isset($_POST['act']) ? $_POST['act'] : '';
	if ($act == 'Cancel') {
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';

		$link = mysqli_connect($host, $user, $passwd, $database);
		mysqli_query($link,"SET NAMES 'utf8'");
		
		//usr_tid2
		$tid = isset($_POST['tid']) ? $_POST['tid'] : '';
		
		if ($tid != "") {
			$sql="update orderinfo set order_status=0,order_updated_at=NOW() where oid=$tid;";
			//echo $sql;
			//exit;
			mysqli_query($link,$sql) or die(mysqli_error($link));

			// 券取消使用

			// 點數返回

			// member 更新點數
			
			// 金額要確認有退? 刷卡刷退...
			
			mysqli_close($link);
			// once saved, redirect back to the view page
			header("Location: order.php");
		}else{
			header("Location: order.php");
		}

	}else{
		header("Location: logout.php");
	}

?>