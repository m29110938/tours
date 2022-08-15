<?php
session_start();
$status = isset($_POST['status']) ? $_POST['status'] : '';
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
$user_mobile = isset($_POST['user_mobile']) ? $_POST['user_mobile'] : '';

$_SESSION['downloadlog']=$user_mobile;

$responseMessage=isset($_POST['responseMessage']) ? $_POST['responseMessage'] : '';

if ($status == "true") {
	$_SESSION['user_mobile']=$user_mobile;
	$_SESSION['user_id']=$user_id;
	header("Location: resetpwd.php"); 
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
                    <h1 class="h4 text-gray-900 mb-2">忘記密碼?</h1>
                    <p class="mb-4">只要在下面輸入您的註冊帳號，我們就會向您發送驗證碼以重置密碼！</p>
                  </div>
                  <form class="user" action="admin_code.php" id="form1" name="form1" method="post">
                    <div class="form-group">
                      <input type="text" id="UID" name="UID" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="請輸入註冊帳號">
                    </div>
                    <a onclick="Submit()" class="btn btn-primary btn-user btn-block">
                      重設密碼
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
					<br/>
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

	function validateMobile(sMobile) {
	  //var reEmail = /^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9](?:[a-zA-Z0-9\-](?!\.)){0,61}[a-zA-Z0-9]?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9\-](?!$)){0,61}[a-zA-Z0-9]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/;
	  var reMobile = /((?=(09))[0-9]{10})$/g;
	  if(!sMobile.match(reMobile)) {
		alert("輸入的 手機號碼 格式有誤!");
		return false;
	  }

	  return true;

	}	
	function Submit()
	{
	
		if (document.getElementById("UID").value !=""){
			//if (validateMobile(document.getElementById("UID").value)) {
				alert(document.getElementById("UID").value);
				document.form1.submit();
			//}
			//else {
			//	return false;
			//}
		}else{
			alert("請輸入帳號 !");
			document.getElementById("UID").focus(); 
			return false;
		}
	}	
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
