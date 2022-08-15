<?php
function Save_Log($conn,$a,$b,$c,$d,$e){
	//member_id	member_name	store_id	function_name	log_date	action_type
	
		$sql="INSERT INTO syslog (member_id,member_name,store_id,function_name,log_date,action_type) VALUES ('$a', '$b',$c, '$d',NOW(),$e);";
		mysqli_query($conn,$sql) or die(mysqli_error($conn));
}		
		
	session_start();
	$Error_login="0";
	$act = isset($_POST['act']) ? $_POST['act'] : '';
	if ($act == "Login"){
			$host = 'localhost';
			$user = 'tours_user';
			$passwd = 'tours0115';
			$database = 'toursdb';

		$link = mysqli_connect($host, $user, $passwd, $database);
		mysqli_query($link,"SET NAMES 'utf8'");
		
		$userid = isset($_POST['UID']) ? $_POST['UID'] : '';
		$userid  = mysqli_real_escape_string($link,$userid);
		$userpwd = isset($_POST['UPWD']) ? $_POST['UPWD'] : '';
		$userpwd  = mysqli_real_escape_string($link,$userpwd);

		$userid = str_replace("from", "xxxx", str_replace("select", "xxxxx", str_replace("=", "x", str_replace("or", "xx", str_replace("and", "xxx", $userid)))));
		$userpwd = str_replace("from", "xxxx", str_replace("select", "xxxxx", str_replace("=", "x", str_replace("or", "xx", str_replace("and", "xxx", $userpwd)))));

		
		if (($userid != "") && ($userpwd != "")) {
			$sql = "SELECT * FROM sysuser where sid>0 ";
			if ($userid != "") {	
				$sql = $sql." and user_id = '".$userid."'";
			}		
			if ($userpwd != "") {	
				$sql = $sql." and user_pwd='".$userpwd."'";
			}
			//echo $sql;
			//exit;
			//$result = mysql_query($sql) or die('MySQL query error');
			//$rowCount = mysql_num_rows($result);
			//echo $rowCount;
			//echo $sql;
			if ($result = mysqli_query($link, $sql)){
				if (mysqli_num_rows($result) > 0){
					$accname="";
					$authority=0;
					while($row = mysqli_fetch_array($result)){
						$accname=$row['user_name'];
						$authority=$row['group_id'];
					}
					$Error_login="0";
					$_SESSION['loginsid']="0";
					$_SESSION['userid']=$userid;
					$_SESSION['accname']=$accname;
					$_SESSION['authority']=$authority;
					$_SESSION['downloadlog']="";
					
					Save_Log($link,$userid,$accname,0,'Login',$authority);
					header("Location: main.php");
					exit;

				}else{
					//檢查是不是店家登入
					$sql = "SELECT * FROM member where member_trash=0 and member_type=2 ";
					if ($userid != "") {	
						$sql = $sql." and member_id='".$userid."'";
					}
					if ($userpwd != "") {	
						$sql = $sql." and member_pwd='".$userpwd."'";
					}
					if ($result = mysqli_query($link, $sql)){
						if (mysqli_num_rows($result) > 0){
							//$mid=0;
							$membersid = 0;
							while($row = mysqli_fetch_array($result)){
								//$mid = $row['mid'];
								$membername = $row['member_name'];
								$membersid = $row['member_sid'];
							}
							Save_Log($link,$userid,$membername,$membersid,'Login',4);
							
							$Error_login="0";
							$_SESSION['loginsid']=$membersid;
							$_SESSION['userid']=$userid;
							$_SESSION['accname']=$membername;
							$_SESSION['authority']="4";
							$_SESSION['downloadlog']="";
							header("Location: main.php");
							exit;							
						}
					}
					
					$_SESSION['loginsid']="";
					$_SESSION['userid']="";
					$_SESSION['accname']="";
					$_SESSION['authority']="";		
					$_SESSION['downloadlog']="";					
					$Error_login="l";
				}
				mysqli_close($link);
			}
		} else {
			$Error_login=="l";
		}
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

<meta property="og:site_name" content="點點" />
<meta property="fb:app_id" content="點點" />
<meta property="og:type" content="website" />
<meta property="og:title" content="點點" >
<meta property="og:description" content="點點後台" />
<meta property="og:image" content="https://ddotapp.com.tw/tours_web/assets/img/%E9%BB%9E%E9%BB%9E%E6%96%B9.png" />

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
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
				  <br/>
				  <br/>
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">點點管理後台</h1>
                  </div>
				  <br/>
                  <form action="login.php" id="form1" name="form1" method="post">
					<input type="hidden" name="act" id="act"  value="Login"/>
                    <div class="form-group">
                      <input type="email" class="form-control form-control-user" id="UID" name="UID" aria-describedby="emailHelp" placeholder="使用者帳號">
                    </div>
					<br/>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" id="UPWD" name="UPWD" placeholder="使用者密碼">
                    </div>
                    <br/>
                    <!--<a href="main.php" class="btn btn-primary btn-user btn-block">
                      登入系統
                    </a>-->
					<a  class="btn btn-primary btn-user btn-block" onclick="Submit()">
                      登入系統
                    </a>
                    <!--<hr>
                    <a href="main.php" class="btn btn-google btn-user btn-block">
                      <i class="fab fa-google fa-fw"></i> Login with Google
                    </a>
                    <a href="main.php" class="btn btn-facebook btn-user btn-block">
                      <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                    </a>-->
					<div id='Error_AccPwd' class="alert alert-error" style='display:none;'>
					  <button type="button" class="close" onclick="CloseAlert()"></button>
						<div class="card bg-danger text-white shadow">
							<div class="card-body">
							  帳號密碼錯誤!
							  <div class="text-white-50 small">請重新輸入帳號和密碼!</div>
							</div>
						</div>
					</div>					
                  </form>
				  <br/>
                  <div class="text-center">
                    <a class="small" href="forgotpwd.php">忘記密碼?</a>
                  </div>
                  <!--<div class="text-center">
                    <a class="small" href="resetpwd.html">
					<a href="resetpwd.html" class="small">重設密碼(測試)</a>
                  </div>-->
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
	if ($Error_login=="l"){
	?>
	document.getElementById("Error_AccPwd").style.display="block";
	//document.getElementById("SinginBt").style.display="none";
	<?php
	}
	?>
	function CloseAlert(){
		document.getElementById("SinginBt").style.display="block";
		document.getElementById("Error_AccPwd").style.display="none";
	}	
	function Submit()
	{
		if (document.getElementById("UID").value !=""){
	
			document.form1.submit();
				
		}else{
			alert("請輸入帳號密碼!");
			return false;
		}
	}
	function CheckKey()
	{
		if (event.keyCode==13)
		{
			Submit();
		}
	}	

	document.getElementById("UID").focus(); 
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
