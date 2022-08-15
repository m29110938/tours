<?php
session_start();
function build_order_no()
{
	return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}
$orderno = build_order_no();
		$storeid = isset($_GET['storeid']) ? $_GET['storeid'] : '';
		$storeid  = mysql_real_escape_string($storeid);
		$customerid = isset($_GET['customerid']) ? $_GET['customerid'] : '';
		$customerid  = mysql_real_escape_string($customerid);
		if($storeid != '' && $customerid != '')
		{
			$dbhost = "localhost";
			$dbuser = 'jtguser';
			$dbpass = 'jtguser2017';
			$dbname = 'armemberdb';
			$storename = '依德';
			$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');
			mysql_query("SET NAMES 'utf8'");
			mysql_select_db($dbname);
			$sql = "select * from storeinfo where storeid='".$storeid."'";
			//echo $sql;
			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result)){
					$storename=$row['storename'];
			}
			
			$productid = $storename.'_商圈線下商品';
			$_SESSION['customerid']=$customerid;
			$_SESSION['storeid']=$storeid;
		}
		else
		{
			echo "請連絡相關網管人員, 並輸入正確參數!!";
			exit;
		}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LINE Pay API</title>
        <link rel="stylesheet" href="kule-lazy-full.3.0.1007beta.min.css" />
        <style type="text/css">
            body {
                min-width: 360px;
            }   
        </style>
    </head>
    <body>
        <header>
            <?php include('./blocks/header.php'); ?>
        </header>

        <?php include('./blocks/payment_steps.php'); ?>
        
        <div class="container">
            <form class="form-horizontal" id="reserveForm" method="POST" action="reserve.php">
                <?php include('./blocks/api_basic_info.php'); ?>
                
                <div class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">訂單資料</h3>
                    </div>
                    <div class="panel-box">
                        <div class="ctrl-grp columns-12">
                            <label class="ctrl-label col-3">商店名稱</label>
                            <div class="ctrls col-9">
                                <input type="text" class="ctrl-input" name="productName" value="<?php echo $storename; ?>" readonly="readonly" required>
                            </div>
                        </div>
                        <div class="ctrl-grp columns-12">
                            <label class="ctrl-label col-3">商品名稱</label>
                            <div class="ctrls col-9">
                                <input type="text" class="ctrl-input" name="productName" value="<?php echo $productid; ?>" required>
                            </div>
                        </div>
                        <div class="ctrl-grp columns-12">
                            <label class="ctrl-label col-3">訂單編號</label>
                            <div class="ctrls col-9">
                                <input type="text" class="ctrl-input" name="orderId" value="<?php echo $orderno; ?>" readonly="readonly" required>
                            </div>
                        </div>
						
                        <div style="display:none" class="ctrl-grp columns-12">
                            <label class="ctrl-label col-3">訂單照片</label>
                            <div class="ctrls col-9">
                                <input type="text" class="ctrl-input" name="productImageUrl" value="image.jpeg" >
                            </div>
                        </div>
						
                        <div class="ctrl-grp columns-12">
                            <label class="ctrl-label col-3">訂單金額</label>
                            <div class="ctrls col-9">
                                <div class="input-grp">
                                    <span class="adorn">TWD</span>
                                    <input type="text" class="ctrl-input" name="amount" value="100" required>
                                    <input type="hidden" class="ctrl-input" name="currency" value="TWD">
                                </div>
                            </div>
                        </div>
                        <div class="ctrl-grp columns-12">
                            <div class="ctrls col-9 col-offset-3">
                                使用 <input type="image" src="linepay_logo_119x39.png"> 支付
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>