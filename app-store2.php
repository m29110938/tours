<?php

	$tid = isset($_GET['tid']) ? $_GET['tid'] : '';
	if ($tid != ''){
		$applink = "spot://storeID=".$tid."&type=topic";
	}else{
		$applink = "spot://storeID=1&type=topic";
	}
	
?>
<!doctype html>
<html>
    <head>
        <title>Launch the application Demo</title>
    </head>
    <body>
	<a href="https://apps.apple.com/tw/app/id1551917653" id="openApp" style="display: none">旅行點點</a> 
<script type="text/javascript"> 
	document.getElementById('openApp').onclick = function(e){ 
	// 通過iframe的方式試圖開啟APP，如果能正常開啟，會直接切換到APP，並自動阻止a標籤的預設行為 
	// 否則開啟a標籤的href連結 
	var ifr = document.createElement('iframe'); 
		ifr.src = '<?php echo $applink;?>'; 
		ifr.style.display = 'none'; 
		document.body.appendChild(ifr); 
		window.setTimeout(function(){ 
		document.body.removeChild(ifr); 
		},3000) 
	}; 
	document.getElementById('openApp').click();
</script>
<script type="text/javascript" >

if(navigator.userAgent.match(/android/i)) {
	// 通過iframe的方式試圖開啟APP，如果能正常開啟，會直接切換到APP，並自動阻止a標籤的預設行為
	// 否則開啟a標籤的href連結
	var isInstalled;
	//下面是安卓端APP介面呼叫的地址，自己根據情況去修改
	var ifrSrc = 'cartooncomicsshowtwo://platformapi/startApp?type=0&id=${com.id}&phone_num=${com.phone_num}';
	var ifr = document.createElement('iframe');
	ifr.src = ifrSrc;
	ifr.style.display = 'none';
	ifr.onload = function() {
	 alert('Is installed.');
	isInstalled = true;
	alert(isInstalled);
	document.getElementById('openApp0').click();};
	ifr.onerror = function() {
	 alert('May be not installed.');
	isInstalled = false;
	alert(isInstalled);
	}
	document.body.appendChild(ifr);
	setTimeout(function() {
	document.body.removeChild(ifr);
	},1000);
}
//ios判斷
if(navigator.userAgent.match(/(iPhone|iPod|iPad);?/i))
if(navigator.userAgent.match(/(iPhone|iPod|iPad);?/i)) {
	//Animation://com.yz.animation
	var isInstalled;
	//var gz = '{"comName":"${com.short_name}","comID":"${com.id}","comPhoneNum":"${com.phone_num}","type":"0"}';
	//var jsongz =JSON.parse(gz);
	//下面是IOS呼叫的地址，自己根據情況去修改
	var ifrSrc = 'Animation://?comName=${com.short_name}&comID=${com.id}&comPhoneNum=${com.phone_num}&type=0';
	var ifr = document.createElement('iframe');
	ifr.src = ifrSrc;
	ifr.style.display = 'none';
	ifr.onload = function() {
	alert('Is installed.');
	isInstalled = true;
	alert(isInstalled);
	document.getElementById('openApp1').click();};
	ifr.onerror = function() {
		alert('May be not installed.');
		isInstalled = false;
		alert(isInstalled);
	}
	document.body.appendChild(ifr);
	setTimeout(function() {
	document.body.removeChild(ifr);
	},1000);
}
}
</script>
    </body>
</html>
