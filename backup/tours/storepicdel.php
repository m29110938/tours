<?php
	//session_start();
	//if ($_SESSION['authority']!="1"){
	//	header("Location: main.php");
	//}
	$act = isset($_POST['act']) ? $_POST['act'] : '';
	if ($act == 'Delpic') {
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';

		$link = mysqli_connect($host, $user, $passwd, $database);
		mysqli_query($link,"SET NAMES 'utf8'");
		
		//usr_tid2
		$tid = isset($_POST['tid']) ? $_POST['tid'] : '';
		$Dfilename = isset($_POST['Dfilename']) ? $_POST['Dfilename'] : '';
		
		if (($tid != "") && ($Dfilename != "")) {
			try {
				unlink($Dfilename);
			
				$sql="update store set store_picture=null,store_updated_at=NOW() where sid=$tid;";
				
				//echo $sql;
				//exit;
				mysqli_query($link,$sql) or die(mysqli_error($link));
			} catch (Exception $e) {
				echo 'Caught exception: ',  $e->getMessage(), "\n";
			}

			
			mysqli_close($link);
			// once saved, redirect back to the view page
			header("Location: store.php");
		}else{
			header("Location: store.php");
		}

	}else{
		header("Location: logout.php");
	}

?>