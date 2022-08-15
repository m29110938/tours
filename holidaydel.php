<?php
	//session_start();
	//if ($_SESSION['authority']!="1"){
	//	header("Location: main.php");
	//}
	$act = isset($_POST['act8']) ? $_POST['act8'] : '';
	$tid = isset($_POST['tid8']) ? $_POST['tid8'] : '';
	$wid = isset($_POST['wid']) ? $_POST['wid'] : '';
	if ($tid != '') {
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';

		$link = mysqli_connect($host, $user, $passwd, $database);
		mysqli_query($link,"SET NAMES 'utf8'");
		
		//usr_tid2
		//$tid = isset($_POST['tid']) ? $_POST['tid'] : '';
		
		if ($wid != "") {
			$sql="delete from holiday where wid=$wid;";

			//echo $sql;
			//exit;
			mysqli_query($link,$sql) or die(mysqli_error($link));
			
			mysqli_close($link);
			// once saved, redirect back to the view page
			//header("Location: hairstylist.php");
		}else{
			//header("Location: hairstylist.php");
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