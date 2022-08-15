<?php
session_start();
if ($_SESSION['downloadlog']==""){
	header("Location: login.php"); 
}
$status = isset($_POST['status']) ? $_POST['status'] : '';
//$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
$user_mobile = isset($_POST['user_mobile']) ? $_POST['user_mobile'] : '';

$responseMessage=isset($_POST['responseMessage']) ? $_POST['responseMessage'] : '';
if ($status == "true") {
	$_SESSION['userid']="";
	$_SESSION['accname']="";
	$_SESSION['authority']="";
	$_SESSION['downloadlog']="";
	session_destroy();
	header("Location: login.php"); 
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>點點管理後台</title>
    <link rel="icon" href="./images/Favicon.ico" type="image/x-icon"/>
  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-2">國旅智慧商圈 - 重設密碼</h1>
                    <p class="mb-4">註冊手機: <?php echo $_SESSION['user_mobile'];?></p>
                  </div>
                  <form class="user" action="admin_resetpwd.php" id="form1" name="form1" method="post">
					<input type="hidden" name="uid" id="uid"  value="<?php echo $_SESSION['user_id'];?>"/>	
					<input type="hidden" name="password" id="password"  value=""/>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" id="PWD1" name="PWD1" aria-describedby="emailHelp" placeholder="重設密碼">
                    </div>
                    <!--<div class="form-group">
                      <input type="password" class="form-control form-control-user" id="PWD2" name="PWD2" aria-describedby="emailHelp" placeholder="確認密碼">
                    </div>-->
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" id="code" name="code" aria-describedby="emailHelp" placeholder="簡訊驗證碼">
                    </div>				
                    <a onclick="Submit()"  class="btn btn-primary btn-user btn-block">
                      送出
                    </a>
                  </form>
                  <hr>
					<div id='Error_AccPwd' class="alert alert-error" style='display:none;'>
					  <button type="button" class="close" onclick="CloseAlert()"></button>
						<div class="card bg-danger text-white shadow">
							<div class="card-body">
							  <?php echo $responseMessage;  ?>
							</div>
						</div>
					</div>	
                  <div class="text-center">
                    <a class="small" href="login.php">返回登入頁</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>
	<SCRIPT LANGUAGE=javascript>
	<!--
	
	<?php
	if ($status=="false"){
	?>
	document.getElementById("Error_AccPwd").style.display="block";
	//document.getElementById("SinginBt").style.display="none";
	<?php
	}
	?>
	
	function GoLogin()
	{
			self.location.replace("login.php");
	}	
	function Submit()
	{
		if (document.getElementById("PWD1").value !=""){
			//if (document.getElementById("PWD1").value == document.getElementById("PWD2").value ) {
			//	document.getElementById("password").value = document.getElementById("PWD1").value;
			//}else{
			//	alert("輸入的密碼不一致!");
			//	return false;			
			//}
			document.getElementById("password").value = document.getElementById("PWD1").value;
			if (document.getElementById("code").value !=""){
				
				document.form1.submit();
			}else{
				alert("請輸入驗證碼!");
				document.getElementById("code").focus();
				return false;
			}
		}else{
			alert("輸入重設密碼!");
			return false;
		}
	}
	document.getElementById("PWD1").focus(); 
	//-->
	</SCRIPT>
  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
