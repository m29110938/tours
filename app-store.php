<?php

	$tid = isset($_GET['tid']) ? $_GET['tid'] : '';
	if ($tid != ''){
		$applink = "spot://storeID=".$tid."&type=topic";
	}else{
		$applink = "spot://storeID=1&type=topic";
	}
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>點點</title>
	
</head>
<body >

	<h1>點點</h1>

	<a style="display:inline-block;" href="https://apps.apple.com/tw/app/id1551917653">iOS	</a>
	<br><br>
	<a href="https://play.google.com/store/apps/details?id=com.jotangi.nickyen">Android	</a>

</body>
</html>

  <SCRIPT LANGUAGE=javascript>
	<!--
var link = "<?php echo $applink;?>";
var redirected = false;
var timer = null;
var t;
function openClient(scheme_url, download_url, timeout = 600) {
    var startTime = Date.now();
    var ifr = document.createElement('iframe');
    ifr.src = scheme_url;
    ifr.style.display = 'none';
    document.body.appendChild(ifr);
	
	//document.location = scheme_url;

    var t = setTimeout(function() {
        var endTime = Date.now();

        if (!startTime || endTime - startTime < timeout + 200) {
            //App is not installed
            //if (confirm("Application is not installed, go to the download?")) {
                //window.open(download_url, "_blank");
				document.location = download_url;
            //}
        }
    }, timeout);

    window.onblur = function() {
        clearTimeout(t);
        //window.addEventListener("DOMContentLoaded", function() {
        //    document.body.removeChild(ifr);
        //}, false);
    }
}

function isIOSDevice(){
   return !!navigator.platform && /iPad|iPhone|iPod/.test(navigator.platform);
}

if (isIOSDevice()) {
	//alert("iOS");
	//openClient(link, 'https://apps.apple.com/tw/app/id1551917653', 600);  //spot://storeID=1&type=topic
	
	var timer = setTimeout(function() {
		if (!document.webkitHidden) {
			//alert(document.webkitHidden);
			if (forcePhBlur == false)
			window.location = "https://apps.apple.com/tw/app/id1551917653";
		}
	}, 25);
	window.location = "<?php echo $applink;?>";
	//document.location = "<?php echo $applink;?>";

	
}else{
	var ua = navigator.userAgent.toLowerCase();
	var isAndroid = ua.indexOf("android") > -1; //&& ua.indexOf("mobile");
	if (isAndroid){
		//alert(isAndroid);
		//openClient(link, 'https://play.google.com/store/apps/details?id=com.jotangi.nickyen', 600);
		window.location = "<?php echo $applink;?>";
		setTimeout(function() {
			if (!document.webkitHidden && !redirected) {
			redirected = true;
			window.location = "https://play.google.com/store/apps/details?id=com.jotangi.nickyen";
			}
		}, 1000);	
	}
}
var forcePhBlur = false;

window.addEventListener("pagehide", function(evt){
    console.log("debug - common.js - pagehide - hiding the page");
    forcePhBlur = true;
	clearTimeout(timer);
}, false);
$(document).on('visibilitychange webkitvisibilitychange', function() {
    var tag = document.hidden || document.webkitHidden;
    if (tag) {
        clearTimeout(timer);
    }
})

$(window).on('pagehide', function() {
    clearTimeout(timer);
})

	//-->
  </SCRIPT>	