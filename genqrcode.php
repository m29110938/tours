<?php
include 'phpqrcode/qrlib.php';
  
	$store_id = isset($_GET['tid']) ? $_GET['tid'] : '';

	$code = "https://tripspot.jotangi.net/app-store.php?tid=".$store_id;
	QRcode::png($code,false,'L',10,10);

?>