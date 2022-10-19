<?php
session_start();
if ($_SESSION['accname']==""){
	header("Location: logout.php"); 
}
function Save_Log($conn,$a,$b,$c,$d,$e){
	//member_id	member_name	store_id	function_name	log_date	action_type
	
		$sql="INSERT INTO syslog (member_id,member_name,store_id,function_name,log_date,action_type) VALUES ('$a', '$b',$c, '$d',NOW(),$e);";
		//echo $sql;
		//exit;
		mysqli_query($conn,$sql) or die(mysqli_error($conn));
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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    include("sidebar.php"); 
	?>
	<script>
		document.querySelector('#storememberphp').classList.add('active');
		document.querySelector('#bb').classList.add('active');
		document.querySelector('#b').classList.remove('collapsed');
		document.querySelector('#collapseUtilities').classList.add('show');
	</script>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
			<i class="fas fa-fw fa-address-book"></i>
			<h1 class="h5 mb-0 text-gray-800">店家會員管理</h1>
		  
          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['accname']; ?></span>
                <img class="img-profile rounded-circle" src="images/logo.png">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <!--<a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>-->
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  登出系統
                </a>
              </div>
            </li>

          </ul>

        </nav>

        <!-- End of Topbar -->
		<?php
			$act = isset($_POST['act']) ? $_POST['act'] : '';

			$host = 'localhost';
			$user = 'tours_user';
			$passwd = 'tours0115';
			$database = 'toursdb';
			$link = mysqli_connect($host, $user, $passwd, $database);
			mysqli_query($link,"SET NAMES 'utf8'");
			
			$staus = isset($_POST['staus']) ? $_POST['staus'] : '';
			$staus  = mysqli_real_escape_string($link,$staus);
			$gender = isset($_POST['gender']) ? $_POST['gender'] : '';
			$gender  = mysqli_real_escape_string($link,$gender);

		
			$mobile = isset($_POST['mobile']) ? $_POST['mobile'] : '';
			$mobile  = mysqli_real_escape_string($link,$mobile);
			$memberid = isset($_POST['member_id']) ? $_POST['member_id'] : '';
			$memberid  = mysqli_real_escape_string($link,$memberid);			
			$membername = isset($_POST['member_name']) ? $_POST['member_name'] : '';
			$membername  = mysqli_real_escape_string($link,$membername);
			$membertype = isset($_POST['member_type']) ? $_POST['member_type'] : '';
			$membertype  = mysqli_real_escape_string($link,$membertype);
			
			$SDate = isset($_POST['txtSDate']) ? $_POST['txtSDate'] : '';
			$SDate  = mysqli_real_escape_string($link,$SDate);
			$EDate = isset($_POST['txtEDate']) ? $_POST['txtEDate'] : '';
			$EDate  = mysqli_real_escape_string($link,$EDate);
			//if ($SDate == "") {
			//	$SDate = date("Y-m-d");;
			//}
			//if ($EDate == "") {
			//	$EDate = date("Y-m-d");;
			//}			
			Save_Log($link,$_SESSION['userid'],$_SESSION['accname'],$_SESSION['loginsid'],'Store member',$_SESSION['authority']);
		?>

		<form action="storemember_export.php" method="Post" name='frm5' id='frm5' class="card">
			<input type="hidden" name="loginsid" id="loginsid"  value="<?=$_SESSION['loginsid'];?>"/>
			<input type="hidden" name="authority" id="authority"  value="<?=$_SESSION['authority'];?>"/>
			<input type="hidden" name="gender" id="gender" value="<?=$gender;?>">
			<input type="hidden" name="staus" id="staus"  value="<?=$staus;?>"/>
			<input type="hidden" name="mobile" id="mobile"  value="<?=$mobile;?>"/>
			<input type="hidden" name="memberid" id="memberid"  value="<?=$memberid;?>"/>
			<input type="hidden" name="membername" id="membername" value="<?=$membername;?>">
			<input type="hidden" name="SDate" id="SDate"  value="<?=$SDate;?>"/>
			<input type="hidden" name="EDate" id="EDate" value="<?=$EDate;?>">
		</form>
        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->

          <!-- Content Row -->
          <div class="row">

            <div class="col-lg-12 mb-4">

              <!-- Illustrations -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">搜尋條件</h6>
                </div>
				  <div class="card-body">
				    <form action="storemember.php" method="Post" name='frm1' id='frm1' class="card">
					<input type="hidden" name="act" id="act"  value=""/>
					<input type="hidden" name="tid" id="tid"  value=""/>
					<input type="hidden" name="page" id="page" value="1">
					
					<div class="row">
                        <div class="col-md-6 col-lg-2">
							  <div class="form-group">
								<label class="form-label">註冊日期(起)</label>
								<!--<input type="text" name="field-name1" class="form-control" data-mask="0000/00/00" data-mask-clearifnotmatch="true" placeholder="yyyy/mm/dd" />-->
								<input class="text-input small-input" type="date" name="txtSDate" id="txtSDate" value="<?=$SDate;?>" />
							  </div>						
						</div>
                        <div class="col-md-6 col-lg-2">
							  <div class="form-group">
								<label class="form-label">註冊日期(迄)</label>
								<!--<input type="text" name="field-name2" class="form-control" data-mask="0000/00/00" data-mask-clearifnotmatch="true" placeholder="yyyy/mm/dd" />-->
								<input class="text-input small-input" type="date" name="txtEDate" id="txtEDate" value="<?=$EDate;?>" />
							  </div>		
						</div>						
						<div class="col-md-6 col-lg-2">
							<div class="form-group">
								<label class="form-label">性別:</label>
								<select name="gender" id="gender" class="form-control custom-select">
								  <option value="">--全選--</option>
								  <option value="0" <?php if ($gender=="0") echo "selected"; ?>>女</option>
								  <option value="1" <?php if ($gender=="1") echo "selected"; ?>>男</option>
								</select>
							</div>
						</div>						
						<div class="col-md-6 col-lg-3">
						  <div class="form-group">
							<label class="form-label">手機號碼:</label>
							<div class="row align-items-center">
							  <div class="col-auto">
								<input type="text" id="mobile" name="mobile" class="form-control w-12" value="<?php echo $mobile; ?>">
							  </div>
							</div>
						  </div>	
						</div>
						<div class="col-md-6 col-lg-3">
						  <div class="form-group">
							<label class="form-label">帳號:</label>
							<div class="row align-items-center">
							  <div class="col-auto">
								<input type="text" id="member_id" name="member_id" class="form-control w-12" value="<?php echo $memberid; ?>">
							  </div>
							</div>
						  </div>	
						</div>
						<div class="col-md-6 col-lg-3">
						  <div class="form-group">
							<label class="form-label">會員姓名:</label>
							<div class="row align-items-center">
							  <div class="col-auto">
								<input type="text" id="member_name" name="member_name" class="form-control w-12" value="<?php echo $membername; ?>">
							  </div>
							</div>
						  </div>	
						</div>	
						<div class="col-md-6 col-lg-2">
							<div class="form-group">
								<label class="form-label">啟用狀態:</label>
								<select name="staus" id="staus" class="form-control custom-select">
								  <option value="">--全選--</option>
								  <option value="1" <?php if ($staus=="1") echo "selected"; ?>>已啟用</option>
								  <option value="0" <?php if ($staus=="0") echo "selected"; ?>>未啟用</option>
								</select>
							</div>
						</div>
						
						<div class="col-md-6 col-lg-3">
						  <div class="form-group">
							  <label class="form-label">&nbsp;</label>
							  <div class="text-center">
								<button type="button" class='btn btn-info ml-auto' onclick='SubmitF();'>搜尋</button> &nbsp;
								<button type="button" onclick="ExportLog();" class="btn btn-success ml-auto">匯出</button>
								<!--<button type="button" class="btn btn-success ml-auto" onclick='GoAddUser()'>新增</button> &nbsp;<button type="submit" class="btn btn-primary ml-auto">匯入</button>-->
							  </div>							
						  </div>
						</div>						
					</div>					
					</form>
				  </div>
              </div>

              <!-- Approach -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">會員資料列表</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
				<?php
				$act = isset($_POST['act']) ? $_POST['act'] : '';
				$havedata = 0;
				//if ($act == 'Qry') {
				//membercard

					$sql = "SELECT a.*,b.rid,b.store_id,b.member_id as memberid,b.member_date,b.card_type,b.membercard_status,b.membercard_trash,c.store_name FROM member as a ";
					$sql = $sql." inner join ( select rid,store_id,member_id,member_date,card_type,membercard_status,membercard_trash from membercard) as b ON a.mid= b.member_id ";
					$sql = $sql." inner join ( select sid,store_name from store) c on b.store_id = c.sid ";

					$sql = $sql." where a.member_trash=0 and b.membercard_trash=0";
					//$sql = "SELECT * FROM member where member_trash=0 ";
					if ($_SESSION['authority']=="4"){
						$sql = $sql." and b.store_id=".$_SESSION['loginsid']."";
					}					
					if ($gender != "") {	
						$sql = $sql." and a.member_gender=".$gender."";
					}
					if ($staus != "") {	
						$sql = $sql." and b.membercard_status='".$staus."'";
					}					
					if ($mobile != "") {	
						$sql = $sql." and a.member_phone like '%".$mobile."%'";
					}
					if (trim($memberid) != "") {	
						$sql = $sql." and a.member_id like '%".trim($memberid)."%'";
					}
					if (trim($membername) != "") {	
						$sql = $sql." and a.member_name like '%".trim($membername)."%'";
					}	
				
					if ($SDate != "") {	
						$sql = $sql." and b.member_date >= '".$SDate." 00:00:00'";
					}
					if ($EDate != "") {	
						$sql = $sql." and b.member_date <= '".$EDate." 23:59:59'";
					}			
					$sql = $sql." order by b.store_id,a.member_id ";
					//echo $sql;
					//exit;

					$idx = 0;
					if ($result = mysqli_query($link, $sql)){
						if (mysqli_num_rows($result) > 0){
							echo "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>";
							echo "  <thead>";
							echo "    <tr>";
							echo "	  <th>#</th>";
							echo "	  <th>店家</th>";
							echo "	  <th>帳號</th>";
							echo "	  <th>姓名</th>";
							echo "	  <th>性別</th>";
							echo "	  <th>手機</th>";						  
							echo "	  <th>紅利點數</th>";
							echo "	  <th>註冊日期</th>";
							echo "	  <th>狀態</th>";
							if (($_SESSION['authority']=="1")||($_SESSION['authority']=="2")||($_SESSION['authority']=="4")){
								//echo "	  <th></th>";
							}
							if (($_SESSION['authority']=="1")||($_SESSION['authority']=="4")){
								//echo "	  <th></th>";
							}
							echo "    </tr>";
							echo "  </thead>";
							echo "  <tbody>";
							while($row = mysqli_fetch_array($result)){
									$idx = $idx + 1;
									$member_id = $row['member_id'];
									// $member_id = substr_replace($member_id,"XXXX",2,4);
									$member_name = $row['member_name'];
									// $member_namelen = mb_strlen($member_name,`utf-8`);
									// if($member_namelen <= 2){
									// 	$member_namestr1 = mb_substr($member_name,0,1,`utf-8`);
									// 	$member_namestr2 = mb_substr($member_name,2,`utf-8`);
									// 	$member_name = $member_namestr1."O".$member_namestr2;
									// }else{
									// 	$member_namestr1 = mb_substr($member_name,0,1,`utf-8`);
									// 	$member_namestr2 = mb_substr($member_name,3,`utf-8`);
									// 	$member_name = $member_namestr1."OO".$member_namestr2;
									// }
									$member_phone = $row['member_phone'];
									// if($member_phone!=""){
									// 	$member_phone = substr_replace($member_phone,"XXXX",2,4);
									// }
									echo "  <tr>";
									echo "    <td>".$idx."</td>";
									echo "    <td>".$row['store_name']."</td>";
									echo "    <td>".$member_id."</td>";
									//echo "    <td>".$row['member_type']."</td>";
							
									echo "    <td>".$member_name."</td>";
									//echo "    <td>".$row['member_gender']."</td>";
										switch ($row['member_gender']) {
											case 0:
												echo "    <td>女</td>";
												break;
											case 1:
												echo "    <td>男</td>";
												break;
											default:
												echo "    <td>&nbsp;</td>";
										}									
									echo "    <td>".$member_phone."</td>";
									
									echo "    <td align=right>".($row['member_totalpoints']-$row['member_usingpoints'])."</td>";
									echo "    <td>".$row['member_date']."</td>";
									//echo "    <td>".$row['member_status']."</td>";
										switch ($row['membercard_status']) {
											case 0:
												echo "    <td>已啟用</td>";
												break;
											case 1:
												echo "    <td>黑名單</td>";
												break;
											default:
												echo "    <td>&nbsp;";
										}				
									if (($_SESSION['authority']=="1")||($_SESSION['authority']=="2")||($_SESSION['authority']=="4")){
										//echo "    <td>";
										//echo "      <a href='javascript:GoEdit(".$row['rid'].")'><i class='fa fa-edit'></i></a>";
										//echo "    </td>";
									}
									if (($_SESSION['authority']=="1")||($_SESSION['authority']=="4")){
										//echo "    <td>";
										//echo "  	<a href='javascript:GoDel(".$row['rid'].")'><i class='fa fa-trash'></i></a>";					
										//echo "    </td>   ";   
									}
									echo "  </tr>";
							}
							echo "  </tbody>";
							echo "</table>";
							echo "<br/>";	
							$havedata = 1;
						}else{
							$page = 0;
							$pages = 0;
							$havedata = 0;
						}
					}
				//}
				mysqli_close($link);	
				?>

				  </div>
				<?php
					if ($act == 'Qry'){
						if($havedata == 1){  ?>
				  <div class="col-md-12 ">
						<br/>
						<button type="button" onclick="ExportLog();" class="btn btn-info ml-auto">匯出</button>
				  </div>
					<?php }else{ echo "沒有符合條件的資料!";}} ?>				  
				</div>
			  </div>

            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; 2020 Jotangi Technology Co., Ltd	</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">確定要離開?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">如果確定離開系統,請按下 "登出系統" .</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">返回</button>
          <a class="btn btn-primary" href="logout.php">登出系統</a>
        </div>
      </div>
    </div>
  </div>

  <SCRIPT LANGUAGE=javascript>
	<!--
	function ExportLog()
	{
		// document.all.downloadlog.src="storemember.php";
		// location.href="storemember_export.php";
		document.frm5.submit();
	}	
	function GoAddUser()
	{	
		document.getElementById("act").value = 'Add';
		document.frm1.action="addstoremember.php";
		document.frm1.submit();
	}
	//GoEdit()
	function GoEdit(id)
	{	
		document.getElementById("act").value = 'Edit';
		document.getElementById("tid").value = id;
		//alert(id);
		document.frm1.action="editstoremember.php";	
		document.frm1.submit();
	}
	function GoDel(id)
	{	
		if (confirm('確定要將這筆會員資料刪除嗎?')) {
			document.getElementById("act").value = 'Del';
			document.getElementById("tid").value = id;
			//alert(id);
			document.frm1.action="storememberdel.php";	
			document.frm1.submit();
		}
	}	
	function Logout(){
		//alert("登出系統!");
		self.location.replace('logout.php');
	}	
	function SubmitF()
	{
		document.getElementById("page").value = "1";	
		Submit();
	}	
	function Submit()
	{
		//alert("1");
		document.getElementById("act").value = 'Qry';
		document.frm1.submit();
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

  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <!--<script src="js/demo/chart-area-demo.js"></script>-->
  <!--<script src="js/demo/chart-pie-demo.js"></script>-->
  
  <!-- Page level plugins -->
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/datatables-demo.js"></script>
</body>

</html>
