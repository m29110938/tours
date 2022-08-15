<?php
	//session_start();
	//if ($_SESSION['authority']!="1"){
	//	header("Location: main.php");
	//}
	$act = isset($_POST['act']) ? $_POST['act'] : '';
	if ($act == 'Del') {
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';

		$link = mysqli_connect($host, $user, $passwd, $database);
		mysqli_query($link,"SET NAMES 'utf8'");
		
		//usr_tid2
		$tid = isset($_POST['tid']) ? $_POST['tid'] : '';
		$rid = isset($_POST['rid']) ? $_POST['rid'] : '';
		if ($rid != "") {

			$sql="delete from bonus_store where rid=$rid;";

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
<form id="myForm" action="editbonus.php" method="post">
	<input type="hidden" name="act" id="act"  value="Edit"/>		
	<input type="hidden" name="tid" id="tid"  value="<?php echo $tid;?>"/>	
</form>
<script type="text/javascript">
    document.getElementById('myForm').submit();
</script>