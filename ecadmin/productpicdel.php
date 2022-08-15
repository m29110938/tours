<?php
	include("db_tools.php");
	
	//session_start();
	//if ($_SESSION['authority']!="1"){
	//	header("Location: main.php");
	//}
	$act = isset($_POST['act']) ? $_POST['act'] : '';
	if ($act == 'Delpic') {
		
		//usr_tid2
		$tid = isset($_POST['tid']) ? $_POST['tid'] : '';
		$Dfilename = isset($_POST['Dfilename']) ? $_POST['Dfilename'] : '';
		
		if (($tid != "") && ($Dfilename != "")) {
			try {
				unlink($Dfilename);
			
				$sql="update product set product_picture=null,product_updated_at=NOW() where rid=$tid;";
				
				//echo $sql;
				//exit;
				mysqli_query($link,$sql) or die(mysqli_error($link));
			} catch (Exception $e) {
				echo 'Caught exception: ',  $e->getMessage(), "\n";
			}

			
			mysqli_close($link);
			// once saved, redirect back to the view page
			header("Location: product.php");
		}else{
			header("Location: product.php");
		}

	}else{
		header("Location: logout.php");
	}

?>