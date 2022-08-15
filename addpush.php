<?php
session_start();
if ($_SESSION['accname']==""){
	header("Location: logout.php"); 
}
// if (($_SESSION['authority']!="1")){
// 	header("Location: main.php"); 
// }
$act = isset($_POST['act']) ? $_POST['act'] : '';
$today = date("Y-m-d");
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
	<script src="js/comm.js"></script>
  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">



  <!-- jQuery v1.9.1 -->
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <!-- Chosen v1.8.2 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.2/chosen.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.2/chosen.jquery.min.js"></script>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="main.php">
        <!--<div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div>-->

        <div class="sidebar-brand-text mx-3">點點</div>
      </a>

      <!-- Divider -->
      <!--<hr class="sidebar-divider my-0">-->

      <!-- Nav Item - Dashboard -->
      <li class="nav-item">
        <a class="nav-link" href="main.php">
          <i class="fas fa-fw fa-home"></i>
          <span>首頁</span></a>
      </li>

      <hr class="sidebar-divider">

      <!-- Nav Item - Charts -->
      <li class="nav-item">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities0" aria-expanded="true" aria-controls="collapseUtilities0">
		  <i class="fas fa-fw fa-address-card"></i>
		  <span>會員中心</span>
		</a>	  
		<div id="collapseUtilities0" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
		  <div class="bg-white py-2 collapse-inner rounded">
			<a class="collapse-item" href="member.php">會員管理</a>
			<a class="collapse-item" href="mycoupon.php">票券列表</a>
			<a class="collapse-item" href="mybonus.php">紅利點數列表</a>
		  </div>
		</div>		  
      </li>	
      <li class="nav-item active">
        <a class="nav-link " href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-fw fa-coffee"></i>
          <span>商圈管理</span>
        </a>	  
		<div id="collapseUtilities" class="collapse show" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
		  <div class="bg-white py-2 collapse-inner rounded">
			<a class="collapse-item" href="store.php">店家設定</a>
			<a class="collapse-item" href="storemember.php">店家會員</a>
			<a class="collapse-item active" href="push.php">推播訊息</a>
			<?php if ($_SESSION['authority']!="4"){  ?>
			<a class="collapse-item" href="storetype.php">類別設定</a>
			<a class="collapse-item" href="bonus.php">分潤設定</a>
			<?php } ?>
			<a class="collapse-item" href="discount.php">獨家優惠(推薦碼)</a>
		  </div>
		</div>
      </li>
      <li class="nav-item">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities1" aria-expanded="true" aria-controls="collapseUtilities1">
		  <i class="fas fa-fw fa-cart-plus"></i>
		  <span>商品維護</span>
		</a>	  
		<div id="collapseUtilities1" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
		  <div class="bg-white py-2 collapse-inner rounded">
			<a class="collapse-item" href="producttype.php">商品分類</a>
			<a class="collapse-item" href="product.php">商品維護</a>
		  </div>
		</div>			  
      </li>
      <li class="nav-item">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities2" aria-expanded="true" aria-controls="collapseUtilities2">
		  <i class="fas fa-fw fa-shopping-bag"></i>
		  <span>訂單管理</span>
		</a>	  
		<div id="collapseUtilities2" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
		  <div class="bg-white py-2 collapse-inner rounded">
			<a class="collapse-item" href="order.php">訂單管理</a>
			<a class="collapse-item" href="profit.php">對帳管理</a>
		  </div>
		</div>			  
      </li>
      <li class="nav-item">
        <a class="nav-link" href="coupon.php">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>行銷活動管理</span></a>
      </li>	  
      <li class="nav-item">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities3" aria-expanded="true" aria-controls="collapseUtilities3">
		  <i class="fas fa-fw fa-info"></i>
		  <span>客服中心</span>
		</a>	  
		<div id="collapseUtilities3" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
		  <div class="bg-white py-2 collapse-inner rounded">
			<a class="collapse-item" href="questiontype.php">問題分類</a>
			<a class="collapse-item" href="question.php">問題管理</a>
		  </div>
		</div>		  
      </li>
      <li class="nav-item active">
		<a class="nav-link " href="#" data-toggle="collapse" data-target="#collapseUtilities4" aria-expanded="true" aria-controls="collapseUtilities4">
		  <i class="fas fa-fw fa-folder-open"></i>
		  <span>訊息中心</span>
		</a>	  
		<div id="collapseUtilities4" class="collapse show" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
		  <div class="bg-white py-2 collapse-inner rounded">
			<a class="collapse-item" href="banner.php">Banner管理</a>
			<a class="collapse-item active" href="push.php">推播訊息</a>
			<a class="collapse-item" href="news.php">最新消息</a>
		  </div>
		</div>		  
      </li>	  
      <!-- Nav Item - Tables -->
      <li class="nav-item">
        <a class="nav-link" href="sysuser.php">
          <i class="fas fa-fw fa-user"></i>
          <span>系統管理</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
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
			<h1 class="h5 mb-0 text-gray-800">推播訊息設定</h1>
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

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <!-- Content Row -->
          <div class="row">

            <div class="col-lg-12 mb-4">

              <!-- Illustrations -->
              <div class="card shadow mb-4">
			<form action="pushadd.php" name="frm3" id="frm3" method="post">
			  <input type="hidden" name="act" id="act"  value="<?php echo $act;?>"/>				  
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">新增推播訊息</h6>
                </div>
                <div class="card-body">

					  <div class="form-group">
						<label class="form-label">發佈日期:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							  <input class="text-input small-input" type="date" name="push_date" id="push_date" value="" min="<?=$today?>"/>
						  </div>
						</div>
					  </div>	
            <div class="form-group">
						<label class="form-label">推播標題:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<input type="text" id="push_title" name="push_title"  maxlength=50>
						  </div>
						</div>
					  </div>				
					  <div class="form-group">
						<label class="form-label">推播內容:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
							<textarea id="push_body" name="push_body" rows="4" cols="50"></textarea>	
						  </div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="form-label">推播對象:</label>
						<div class="row align-items-center">
						  <div class="col-auto">
              <!-- <input type="checkbox" id="sa" name="sa" class="sa" value="0">
              <label>全選</label>
              <br> -->
              <?php
              if ($_SESSION['authority']=="4"){
                ?>
              <input type="hidden" id="sid" name="sid" class="sid" value="<?=$_SESSION['loginsid']?>">
              <?php
              }
              elseif($_SESSION['authority']=="1"){
              ?>
              <input type="hidden" id="sid" name="sid" class="sid" value="0">
              <?php
              }
              ?>
              <input type="hidden" id="members" name="members" class="members" value="">
              <select id="member" class="member" name="member" multiple style="width: 500px" data-placeholder="選擇會員">
                <option value='0' id="sa" name="sa">全選</option>
                <!-- <option value='-1' id="aa" name="aa" disabled="disabled">aa</option> -->
                <?php
                $host = 'localhost';
                $user = 'tours_user';
                $passwd = 'tours0115';
                $database = 'toursdb';
                
                $link = mysqli_connect($host, $user, $passwd, $database);
                mysqli_query($link,"SET NAMES 'utf8'");
                $sql = "select a.* from member as a";
                // $sql = $sql." select * from member";
                if ($_SESSION['authority']=="4"){

                  $sql = $sql." INNER JOIN (SELECT * FROM membercard) as b on a.mid = b.member_id";
                  $sql = $sql." INNER JOIN (SELECT * FROM store) as c on b.store_id = c.sid";
                  $sql = $sql." where c.sid=".$_SESSION['loginsid']." and b.membercard_trash = 0 and a.member_trash = 0 and a.member_sid = 0 and a.notificationToken is not null";
                }else{
                  $sql = $sql." where a.member_trash = 0 and a.member_sid = 0 and a.notificationToken is not null";
                }
                
                if ($result = mysqli_query($link, $sql)) {
                  if (mysqli_num_rows($result) > 0) {
                      while ($row = mysqli_fetch_array($result)) {
                          $mid = $row['mid'];
                          $member_name = $row['member_name'];
                          $member_id = $row['member_id'];
                          $member_gender = $row['member_gender'];
                          switch ($row['member_gender']) {
                            case 0:
                                $member_gender = "女";
                                break;
                            case 1:
                                $member_gender = "男";
                                break;
                            default:
                                $member_gender = "";
                          }
                          $member = $member_id.", ".$member_name.", ".$member_gender;
                          ?>
                          
                          <option value='<?=$mid?>' id="m" class="<?=$mid?>" name="m"><?=$member?></option>

                          <?php
                      }
                  }
                }
                ?>
              </select>
						  </div>
						</div>
					  </div>	
					  
					  <div class="card-footer text-center">
							<button type="button" class="btn btn-success ml-auto" onclick="Save_User()">儲存</button>&nbsp;&nbsp;<button type="button" class="btn btn-info ml-auto" name="backacc" onclick="GoAccount();">返回</button>
					  </div>		
                </div>
              </div>

            </div>
		    </form>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->
	<SCRIPT LANGUAGE=javascript>
	
	function Save_User(){

		if (!checkString(document.all.push_date,"請輸入發佈日期!"))	return false;
		if (!checkString(document.all.push_body,"請輸入推播內容!"))	return false;
		if (!checkString(document.all.push_title,"請輸入推播標題!"))	return false;
		if (!checkString(document.all.members,"請選擇推播人員!"))	return false;

		document.frm3.submit();
		mode = "0";
	}	
	function GoAccount()
	{
			self.location.replace("push.php");
	}
	function Logout(){
		//alert("登出系統!");
		self.location.replace('logout.php');
	}	
	//-->
  
	</SCRIPT>
  <script>
  $( ".member" ).chosen({
      // max_shown_results:10,
      placeholder_text_single: "選擇會員",
      no_results_text: "沒有會員資訊",
      search_contains:true,
      // width: "15%"
  });
  // $(".member").chosen().change(function(event){
  //     if(event.target == this){
  //         console.log($(this).val());
  //         $('.member option').prop('disabled', false); 
  //         $('.member').trigger('chosen:updated');
  //         document.getElementById('members').value = $(this).val();
  //     }
  // });
  $(".member").chosen().change(function(event){
      if(event.target == this){
          // alert($(this).val());
          console.log($(this).val());
          document.getElementById('members').value = $(this).val();
          if($(this).val() == null){
              console.log("--");
              $('.member #m').prop('disabled', false);
              $('.member').trigger('chosen:updated');
              // break;
          }
          else if ($(this).val().includes('0')){
              console.log("%%");
              // console.log($('.member option'));
              $('.member option').prop('selected', false); 
              $('.member #sa').prop('selected', true); 
              $('.member #m').prop('disabled', true);
              // $('.member').data('chosen').max_selected_options = 2;
              $('.member').trigger('chosen:updated');
              document.getElementById('members').value = 0;
              // $('.member').prop('disabled', true);
          }
          // if ($(this).val().includes('0')){
          //     console.log("@@");
          //     $('.member #sa #m').prop('selected', false); 
          //     $('.member #sa').prop('selected', true); 
          //     $('.member #m').prop('disabled', true);
          //     $('.member').trigger('chosen:updated');
          //     document.getElementById('members').value = 0;
          // }
          // else{
          //     document.getElementById('members').value = $(this).val();
          // }
      }

  });
  // $("#sa").change(function () {
  //     var item = $(this).is(':checked');
  //     // alert(item)
  //     if(item == true){
  //       // console.log("qqqq");
  //       document.getElementsByClassName('chosen-choices')[0].style.display = "none";
  //       $('.members').prop('select',false).trigger('chosen:updated');
  //       document.getElementById('members').value = 0;
  //     }else{
  //       // console.log("wwww");
  //       document.getElementsByClassName('chosen-choices')[0].style.display = "block";
  //       document.getElementById('members').value = "";
  //     }
  // });
  </script>
      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; 2020 Jotangi Technology Co., Ltd</span>
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

</body>

</html>
