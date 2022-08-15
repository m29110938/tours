<?php
	//session_start();
	//if ($_SESSION['authority']!="1"){
	//	header("Location: main.php");
	//}
	$act = isset($_POST['act3']) ? $_POST['act3'] : '';
	if ($act == 'Edit') {
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';

		$link = mysqli_connect($host, $user, $passwd, $database);
		mysqli_query($link,"SET NAMES 'utf8'");

		$tid = isset($_POST['tid3']) ? $_POST['tid3'] : '';
		$tp0 = isset($_POST['tp0']) ? $_POST['tp0'] : '';
		$tp1 = isset($_POST['tp1']) ? $_POST['tp1'] : '';
		$tp2 = isset($_POST['tp2']) ? $_POST['tp2'] : '';
		$tp3 = isset($_POST['tp3']) ? $_POST['tp3'] : '';
		$tp4 = isset($_POST['tp4']) ? $_POST['tp4'] : '';
		$tp5 = isset($_POST['tp5']) ? $_POST['tp5'] : '';
		$tp6 = isset($_POST['tp6']) ? $_POST['tp6'] : '';
		
		
		if (($tid != "")) {

			$sql = "delete from timeperiod where tid > 0 and store_id=$tid";

			//echo $sql;
			//exit;
			mysqli_query($link,$sql) or die(mysqli_error($link));
			
			if ($tp0 != "0"){
				$tp0= str_replace(":00","",$tp0);
			}
			if ($tp1 != "0"){
				$tp1= str_replace(":00","",$tp1);
			}
			if ($tp2 != "0"){
				$tp2= str_replace(":00","",$tp2);
			}
			if ($tp3 != "0"){
				$tp3= str_replace(":00","",$tp3);
			}
			if ($tp4 != "0"){
				$tp4= str_replace(":00","",$tp4);
			}
			if ($tp5 != "0"){
				$tp5= str_replace(":00","",$tp5);
			}
			if ($tp6 != "0"){
				$tp6= str_replace(":00","",$tp6);
			}			
			$sql="insert into timeperiod (store_id,w0,w1,w2,w3,w4,w5,w6,updatedttime) values ($tid,'$tp0','$tp1','$tp2','$tp3','$tp4','$tp5','$tp6',NOW());";

			//echo $sql;
			//exit;
			mysqli_query($link,$sql) or die(mysqli_error($link));
			
			mysqli_close($link);
			// once saved, redirect back to the view page
			//header("Location: editbonus.php");
		}else{
			//header("Location: editbonus.php");
		}

	}else{
		//header("Location: logout.php");
	}

?>
<form id="myForm" action="editstore.php" method="post">
	<input type="hidden" name="act" id="act"  value="<?php echo $act;?>"/>		
	<input type="hidden" name="tid" id="tid"  value="<?php echo $tid;?>"/>	
</form>
<script type="text/javascript">
    document.getElementById('myForm').submit();
</script>