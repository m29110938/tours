<?php
	session_start();
	//if ($_SESSION['authority']!="1"){
	//	header("Location: main.php");
	//}
	//---------------------------------------------------------------------------------------------------------
	$act = isset($_POST['act6']) ? $_POST['act6'] : '';
	$tid = isset($_POST['tid6']) ? $_POST['tid6'] : '';
	
	if ($tid != '') {
		$host = 'localhost';
		$user = 'tours_user';
		$passwd = 'tours0115';
		$database = 'toursdb';
		
		$link = mysqli_connect($host, $user, $passwd, $database);
		mysqli_query($link,"SET NAMES 'utf8'");

		$holiday = isset($_POST['holiday']) ? $_POST['holiday'] : '';
		$holiday  = mysqli_real_escape_string($link,$holiday);

		$close_reason = isset($_POST['close_reason']) ? $_POST['close_reason'] : '';
		$close_reason  = mysqli_real_escape_string($link,$close_reason);

	
		$sql="INSERT INTO holiday (store_id,holiday,close_reason) VALUES ($tid,'$holiday','$close_reason');";

		//echo $sql;
		//exit;

		mysqli_query($link,$sql) or die(mysqli_error($link));
		
		//$_SESSION['saveresult']="新增商店資料成功!";
			
		// once saved, redirect back to the view page
		//header("Location: hairstylist.php?act=Qry");

		mysqli_close($link);
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