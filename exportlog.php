<?php
	set_time_limit(0);
	/**
     * 匯出CSV資料處理
     * 待優化項：如果匯出資料達到百萬以上，需要做分批匯出CSV檔案再新增到壓縮檔案統一打包下載
     * @desc 資料匯出到csv(csv檔案)
     * @param string $filename 檔名稱
     * @param array $tileArray 所有列名稱
     * @param array $dataArray 所有列資料
     */
	function formatV($a,$b) {
		$aa = floatval($a)/100;
		$bb = round($aa, $b);
		return $bb;
	}
	function formatVV($a,$b) {
		$aa = floatval($a)/1000;
		$bb = round($aa, $b);
		return $bb;
	}		 
    function exportToCsv($filename, $tileArray=array(), $dataArray=array()){
        //設定PHP最大單執行緒的獨立記憶體使用量
        ini_set('memory_limit','1024M');
        //程式超時設定設為不限時
        ini_set('max_execution_time ','0');
        ob_end_clean();
        ob_start();
        header("Content-Type: application/pdf"); //application/pdf    //text/csv
        //$filename .= date("Y-m-d").".csv";
        header("Content-Disposition:filename=".$filename);
        $fp=fopen('php://output','w');
        fwrite($fp, chr(0xEF).chr(0xBB).chr(0xBF));//轉碼 防止亂碼
        fputcsv($fp,$tileArray);
        $index = 0;
        foreach ($dataArray as $item) {
            if($index==1000){
                $index=0;
                ob_flush();
                flush();
            }
            $index++;
            fputcsv($fp,$item);
        }

        ob_flush();
        flush();
        ob_end_clean();
    }
	
	$host = 'localhost';
	$user = 'tours_user';
	$passwd = 'tours0115';
	$database = 'toursdb';
	$link = mysqli_connect($host, $user, $passwd, $database);
	mysqli_query($link,"SET NAMES 'utf8'");
	
	$pid = isset($_POST['pid']) ? $_POST['pid'] : '';
	$pid  = mysqli_real_escape_string($link,$pid);		
			
	$sql = "SELECT * FROM profit where pid = ".$pid;

	//if ($_SESSION['authority']=="4"){
	//	$sql = $sql." and b.sid=".$_SESSION['loginsid']."";
	//}
	
	//if ($billing_flag != "") {	
	//	$sql = $sql." and a.billing_flag=".$billing_flag."";
	//}					
	//echo $sql;
	//exit;
	$idx = 0;
	if ($result = mysqli_query($link, $sql)){
		if (mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_array($result)){
				$name = $row['profit_pdf'];
				//echo $name;
				break;
			}
			//$name = 'file.pdf';
			//file_get_contents is standard function			
			$path = $_SERVER['DOCUMENT_ROOT'];
			$fullPath = $path.'/tours/'.$name;
			//echo $name2;
			//exit;
			//$content = file_get_contents($name2);
			if (is_readable ($fullPath)) {
				$fsize = filesize($fullPath);
				$path_parts = pathinfo($fullPath);
				$ext = strtolower($path_parts["extension"]);
				
				header('Content-Type: application/pdf');
				header('Content-Length: '.strlen( $content ));
				header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\"");
				header("Content-length: $fsize");
				header("Cache-control: private"); //use this to open files directly
				readfile($fullPath);	
				exit;			
				//header('Cache-Control: public, must-revalidate, max-age=0');
				//header('Pragma: public');
				//header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
				//header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
				//echo $content;		
			}
		}
	}

	//$filename="BatteryLog";//匯出檔名
	//exportToCsv($filename,$headArr,$data);
?>