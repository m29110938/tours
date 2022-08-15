<?php
    //Include the necessary library for Ubuntu
    //include('/usr/share/phpqrcode/qrlib.php');
	include '/phpqrcode/qrlib.php';
    //Set the data for QR
    $text = "PHP QR Code Generator";

    //check the class is exist or not
    //if(class_exists('QRcode'))
    //{
        //Generate QR
        QRcode::png($text, 'QRImage.png');
    //}else{
        //Print error message
    //    echo 'class is not loaded properly';
    //}

?>
<html>
    <head>
    <title>QR Code Generator</title>
    </head>
    <body>
        <!-- display the QR image -->
        <img src="QRImage.png" />
    </body>
</html>