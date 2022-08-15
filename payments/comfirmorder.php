<?php
session_start();
include("../db_tools.php");

//$orderno = isset($_POST['orderno']) ? $_POST['orderno'] : '';
$orderno = isset($_GET['orderno']) ? $_GET['orderno'] : '';
if($orderno == "")
{
	//$url = "Location: index";
	//header($url);
	exit;
}

	$sql = "SELECT * FROM orderinfo where oid>0 ";
	
	$sql = "SELECT a.*,b.store_name, c.member_id as memberid,c.member_name FROM orderinfo as a ";
	$sql = $sql." inner join ( select sid,store_id,store_name from store) as b ON b.sid= a.store_id ";
	$sql = $sql." inner join ( select mid,member_id,member_name from member) c on a.member_id = c.mid ";
	$sql = $sql." where a.oid>0 ";

	if (trim($orderno) != "") {	
		$sql = $sql." and a.order_no like '%".trim($orderno)."%'";
	}					

	//$sql = $sql." order by a.order_date ";
	if ($result = mysqli_query($link, $sql)){
		if (mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_array($result)){
				//echo "    <td>".$row['order_no']."</td>";
				$order_date = $row['order_date'];
				$store_name = $row['store_name'];
				$memberid = $row['memberid'];
				$order_amount = $row['order_amount'];

				switch ($row['pay_type']) {
					case 1:
						$pay_type="現金";
						break;
					case 2:
						$pay_type="刷卡";
						break;
					case 3:
						$pay_type="Line Pay";
						break;
					case 4:
						$pay_type="街口";
						break;
					default:
						$pay_type="&nbsp;";
				}									
				switch ($row['pay_status']) {
					case 0:
						$pay_status="未付款";
						break;
					case 1:
						$pay_status="已付款";
						break;
					default:
						$pay_status="處理中";
				}									
				switch ($row['order_status']) {
					case 0:
						$order_status="取消";
						break;
					case 1:
						$order_status="完成";
						break;
					default:
						$order_status="處理中";
				}	
				break;
			}
		}else{
				$order_date = "";
				$store_name = "";
				$memberid = "";
				$order_amount = "";
				$pay_type="";
				$pay_status="";
				$order_status="";
				exit;
		}
	}else{
		exit;
	}
//}
mysqli_close($link);	
?>			  

<!DOCTYPE html>
<html lang="en">
<!-- InstanceBegin template="/Templates/_Layout.dwt" codeOutsideHTMLIsLocked="false" -->

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- InstanceBeginEditable name="doctitle" -->
    <title>健康配</title>
    <!-- InstanceEndEditable -->
    <!-- slick -->
    <link rel="stylesheet" href="css/slick.css" />
    <link rel="stylesheet" href="css/slick-theme.css" />
    <!-- all.css -->
    <link rel="stylesheet" href="css/all.css" />
    <!-- InstanceBeginEditable name="head" -->
    <!-- InstanceEndEditable -->
</head>

<body>
    <main class="main">
        <!-- content -->
        <!-- InstanceBeginEditable name="main" -->
        <div class="Good">
            <div class="Good_order_established">
                <div class="text_center">
                    <img src="css/checked_g.svg" alt="">
                    <div class="text_green mt_1d5 mb_0d5 size_20 font_med">
						付款完成！
                    </div>
                    <div class="mb_1d5 text_green size_16 font_med">
                        付款完成，您可至交易記錄查詢明細
                    </div>
                </div>
				<table width='100%'>
				<tr>
					<td width='10%'>&nbsp;</td>
					<td width='80%'>
						<div class="Member_profile">
							<div class="form_wrap mb_1 ">
								<label for="" class="form_label"> 付款日期: <?=$order_date;?></label>
							</div>
							<div class="form_wrap mb_1 ">
								<label for="" class="form_label"> 訂單序號: <?=$orderno;?></label>
							</div>
							<div class="form_wrap mb_1 ">
								<label for="" class="form_label"> 消費店家: <?=$store_name;?></label>
							</div>
							<div class="form_wrap mb_1 ">
								<label for="" class="form_label"> 付款方法: <?=$pay_type;?></label>
							</div>
							<div class="form_wrap mb_1 ">
								<label for="" class="form_label"> 付款金額: <font color='red'>NT$ <?=$order_amount;?></font></label>
							</div>
						</div>
					</td>
					<td width='10%'>&nbsp;</td>
				</tr>
				</table>
                <button class="btn btn_green mb_1" onclick="gohome()">確認</button>
                <!--<button class="btn btn_outline_green ">查看訂單</button>-->
             
            </div>
			
        </div>
        <!-- InstanceEndEditable -->
        <!-- end content -->
    </main>
    <!-- jquery 
    <script src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>-->
    <!-- InstanceBeginEditable name="Scripts" -->

    <!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
	<SCRIPT LANGUAGE=javascript>
	<!--
	function gohome() 
	{
			//alert('go home');
			//self.location.replace("hpay://payment?url=http://xxx.xx/");
			window.location = "hpay://payment?url=http://xxx.xx/";
	}
	//-->
	</SCRIPT>
</html>