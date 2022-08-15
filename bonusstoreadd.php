<?php
	//session_start();
	//if ($_SESSION['authority']!="1"){
	//	header("Location: main.php");
	//}
	$act = isset($_POST['act6']) ? $_POST['act6'] : '';
	if ($act == 'Edit') {
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';

		$link = mysqli_connect($host, $user, $passwd, $database);
		mysqli_query($link,"SET NAMES 'utf8'");
		
		$bonus_mode1 = isset($_POST['bonus_mode1']) ? $_POST['bonus_mode1'] : '0';
		$bonus_mode1  = mysqli_real_escape_string($link,$bonus_mode1);		
		//usr_tid2
		$tid = isset($_POST['tid6']) ? $_POST['tid6'] : '';
		$storecheck = isset($_POST['storecheck']) ? $_POST['storecheck'] : '';
		
		if (($tid != "") and ($storecheck != "")) {

			//$sid = split($storecheck,"|");
			//$sid = explode('|',$storecheck);
			
			//$var = 'a/asdas/fgdfg/zfdvs/sdfh';
			$store_id = explode('|', $storecheck);
			foreach ($store_id as $sid)
			{
				if ($sid != "") {
				   $sql="insert into bonus_store (store_id,bid,bonus_mode) values ($sid,$tid,$bonus_mode1);";
				   //echo $sql;
				   mysqli_query($link,$sql) or die(mysqli_error($link));
			    }
			}

			//$sql="insert into bonus_store (store_id,bid,bonus_mode) values ($tid,$bonus_mode);";

			//echo $sql;
			//exit;
			//mysqli_query($link,$sql) or die(mysqli_error($link));
			
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