<?php
include('db_tools.php');

$login_status = isset($_POST['login_status']) ? $_POST['login_status'] : '0';

$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$member_pwd = isset($_POST['member_pwd']) ? $_POST['member_pwd'] : '';

$member_id_delete = isset($_POST['member_id_delete']) ? $_POST['member_id_delete'] : '';
// print_r($_POST);

if($login_status == "1"){

    $member_id_delete  = mysqli_real_escape_string($link,$member_id_delete);
    $member_id_delete = str_replace("from", "xxxx", str_replace("select", "xxxxx", str_replace("=", "x", str_replace("or", "xx", str_replace("and", "xxx", $member_id_delete)))));

    if($member_id_delete != "" ){
        $sql="update member set member_trash='1',member_updated_at=NOW() where member_id='$member_id_delete' ;";
        // echo $sql;
        mysqli_query($link, $sql);
        echo "<script>alert('刪除成功')</script>";
        echo "<script>window.location='delete_member.php';</script>";
    }
}else{
    if($member_id != "" && $member_pwd != ""){
        $member_id  = mysqli_real_escape_string($link,$member_id);
        $member_pwd  = mysqli_real_escape_string($link,$member_pwd);
        
        $member_id = str_replace("from", "xxxx", str_replace("select", "xxxxx", str_replace("=", "x", str_replace("or", "xx", str_replace("and", "xxx", $member_id)))));
        $member_pwd = str_replace("from", "xxxx", str_replace("select", "xxxxx", str_replace("=", "x", str_replace("or", "xx", str_replace("and", "xxx", $member_pwd)))));
        
        $sql = "SELECT * FROM member where member_trash=0 and member_status='1' ";
        if ($member_id != "") {	
            $sql = $sql." and member_id='".$member_id."'";
        }
        if ($member_pwd != "") {	
            $sql = $sql." and member_pwd='".$member_pwd."'";
        }
        if ($result = mysqli_query($link, $sql)){
            if (mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_array($result)){
                    $member_id = $row['member_id'];
                    $member_status = $row['member_status'];
                }
                if ($member_status == '1'){
                    $login_status = "1";
                }else{
                    echo "<script>alert('帳號或密碼錯誤')</script>";
                }
            }else{
                echo "<script>alert('帳號或密碼錯誤')</script>";
            }
        }else{
            echo "<script>alert('帳號或密碼錯誤')</script>";
        }
    }
    
}

// mysqli_close($link);


?>

<?php
if($login_status == "0"){
?>
<form action="delete_member.php" method="POST">
    帳號：<input type="text" id="member_id" name="member_id">
    <br>
    密碼：<input type="password" id="member_pwd" name="member_pwd">
    <br>
    <button type="submit">登入</button>
</form>
<?php
}
elseif($login_status == "1"){
?>
請問要刪除帳號嗎？
<form action="delete_member.php" method="POST" id="frm1" name="frm1">
    <input type="hidden" id="login_status" name="login_status" value="0">
    <input type="hidden" id="member_id_delete" name="member_id_delete">
    <button type="button" onclick="deletemember()">刪除</button>
    <button type="button" onclick="gohome()">返回</button>
</form>
<?php
}
?>

<script>
    function deletemember()
	{
		//alert("1");
        if (confirm("確定要刪除帳號嗎？")) {
            document.getElementById("login_status").value = '1';
            document.getElementById("member_id_delete").value = '<?=$member_id?>';
            document.frm1.submit();		
        } 
		//document.forms["frm1"].submit();
	}
    function gohome()
	{
        window.location='delete_member.php';
		//alert("1");
		// document.getElementById("login_status").value = '1';
		// document.getElementById("member_id_delete").value = '<?=$member_id?>';
		// document.frm1.submit();
		//document.forms["frm1"].submit();
	}
</script>