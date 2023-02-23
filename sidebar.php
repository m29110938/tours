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
		<li id="hh" class="nav-item">
		<a class="nav-link" href="main.php">
			<i class="fas fa-fw fa-home"></i>
			<span>首頁</span></a>
		</li>

		<hr class="sidebar-divider">

		<!-- Nav Item - Charts -->
		<?php if ($_SESSION['authority']!="4"){  ?>
		<li id="aa" class="nav-item">
		<a id="a" class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities0" aria-expanded="true" aria-controls="collapseUtilities0">
			<i class="fas fa-fw fa-address-card"></i>
			<span>會員中心</span>
		</a>	  
		<div id="collapseUtilities0" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">
			<a class="collapse-item" id="memberphp" href="member.php">會員管理</a>
			<a class="collapse-item" id="mycouponphp" href="mycoupon.php">票券列表</a>
			<a class="collapse-item" id="mybonusphp" href="mybonus.php">紅利點數列表</a>
			</div>
		</div>		  
		</li>	  
		<?php } ?>
		<li id="bb" class="nav-item">
        <a id="b" class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-fw fa-coffee"></i>
          <span>商圈管理</span>
        </a>	  
		<div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
		  <div class="bg-white py-2 collapse-inner rounded">
			<a class="collapse-item" id="storephp" href="store.php">店家設定</a>
			<a class="collapse-item" id="storememberphp" href="storemember.php">店家會員</a>
			<a class="collapse-item" id="billphp" href="bill.php">店家帳務管理</a>
			<a class="collapse-item" id="pushphp" href="push.php">推播訊息</a>
			<?php if ($_SESSION['authority']!="4"){  ?>
			<a class="collapse-item" id="storetypephp" href="storetype.php">類別設定</a>
			<a class="collapse-item" id="bonusphp" href="bonus.php">分潤設定</a>
			<a class="collapse-item" id="attractionphp" href="attraction.php">景點設定</a>
			<?php } ?>
			<a class="collapse-item" id="discountphp" href="discount.php">獨家優惠(推薦碼)</a>
			<a class="collapse-item" id="hairservicephp" style="display:none;" href="hairservice.php">服務項目管理</a>
			<a class="collapse-item" id="hairstylistphp" style="display:none;" href="hairstylist.php">員工資料管理</a>
		  </div>
		</div>
      </li>
		<?php if ($_SESSION['authority']!="4"){  ?>
		<li id="cc" class="nav-item">
		<a id="c" class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities1" aria-expanded="true" aria-controls="collapseUtilities1">
			<i class="fas fa-fw fa-cart-plus"></i>
			<span>商品維護</span>
		</a>	  
		<div id="collapseUtilities1" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">
			<a class="collapse-item" id="producttypephp" href="producttype.php">商品分類</a>
			<a class="collapse-item" id="productphp" href="product.php">商品維護</a>
			<a class="collapse-item" id="ecorderphp" href="ecorder.php">商城訂單管理</a>
			</div>
		</div>			  
		</li>
		<?php } ?>
		<li id="dd" class="nav-item">
		<a id="d" class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities2" aria-expanded="true" aria-controls="collapseUtilities2">
			<i class="fas fa-fw fa-shopping-bag"></i>
			<span>訂單管理</span>
		</a>	  
		<div id="collapseUtilities2" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">
			<a class="collapse-item" id="orderphp" href="order.php">訂單管理</a>
			<a class="collapse-item" id="profitphp" href="profit.php">對帳管理</a>
			</div>
		</div>			  
		</li>
		<?php if ($_SESSION['authority']!="4"){  ?>
		<!-- <li id="ee" class="nav-item">
		<a id="e" class="nav-link collapsed" href="coupon.php">
			<i class="fas fa-fw fa-chart-area"></i>
			<span>行銷活動管理</span></a>
		</li>	   -->
		<li id="ee" class="nav-item">
		<a id="e" class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities5" aria-expanded="true" aria-controls="collapseUtilities5">
			<i class="fas fa-fw fa-chart-area"></i>
			<span>行銷活動管理</span>
		</a>	  
		<div id="collapseUtilities5" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">
			<a class="collapse-item" id="couponphp" href="coupon.php">優惠券管理</a>
			<a class="collapse-item" id="arlistphp" href="arlist.php">AR 點位設定</a>
			</div>
		</div>		  
		</li>
		<li id="ff" class="nav-item">
		<a id="f" class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities3" aria-expanded="true" aria-controls="collapseUtilities3">
			<i class="fas fa-fw fa-info"></i>
			<span>客服中心</span>
		</a>	  
		<div id="collapseUtilities3" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">
			<a class="collapse-item" id="questiontypephp" href="questiontype.php">問題分類</a>
			<a class="collapse-item" id="questionphp" href="question.php">問題管理</a>
			</div>
		</div>		  
		</li>
		<li id="gg" class="nav-item">
		<a id="g" class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities4" aria-expanded="true" aria-controls="collapseUtilities4">
			<i class="fas fa-fw fa-folder-open"></i>
			<span>訊息中心</span>
		</a>	  
		<div id="collapseUtilities4" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">
			<a class="collapse-item" id="bannerphp" href="banner.php">Banner管理</a>
			<a class="collapse-item" id="newsphp" href="news.php">最新消息</a>
			</div>
		</div>		  
		</li>	 
		<?php } ?>
		<!-- Nav Item - Tables -->
		<?php if ($_SESSION['authority']=="1"){  ?>
		<li id="ii" class="nav-item">
		<a class="nav-link" href="sysuser.php">
			<i class="fas fa-fw fa-user"></i>
			<span>系統管理</span></a>
		</li>
		<?php } ?>
		
		<!-- Divider -->
		<hr class="sidebar-divider d-none d-md-block">

		<!-- Sidebar Toggler (Sidebar) -->
		<div class="text-center d-none d-md-inline">
		<button class="rounded-circle border-0" id="sidebarToggle"></button>
		</div>

	</ul>
	<!-- End of Sidebar -->
