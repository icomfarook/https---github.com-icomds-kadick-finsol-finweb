<?php 	
	include('../common/sessioncheck.php');
	include('reqchk.php');
	error_reporting(0);
	include('../common/admin/finsol_ini.php');
	$lang = $_SESSION['language_id'];
	if($lang == "1" || $lang=="") {
		include('../common/admin/finsol_label_ini.php');
	}
	if($lang == "2"){
		include('../common/admin/haus_finsol_label_ini.php');
	}
	//$profile_id  = $_SESSION['profile_id'];
	//error_log("prifilid".$profile_id);
	
																										
												
										
		
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title> Kadick Mo&#8358;ei :: Finance </title>
		<meta name="description" content="description">
		<meta name="icom" content="icom">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="../common/plugins/bootstrap/bootstrap.css" rel="stylesheet">
		<link href="../common/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
		<link href="../common/css/font-awesome.min.css" rel="stylesheet">
		<link href='../common/css/font-righteous.css' rel='stylesheet' type='text/css'>
		<link href="../common/plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
		<link href="../common/plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
		
		<link href="../common/plugins/xcharts/xcharts.min.css" rel="stylesheet">		
		<link href="../common/plugins/justified-gallery/justifiedGallery.css" rel="stylesheet">
		<link href="../common/css/style_v2.css" rel="stylesheet">	
		<link href="../common/plugins/chartist/chartist.min.css" rel="stylesheet">
		<link rel="shortcut icon" type="image/x-icon" href="../common/images/km_logo.ico" />
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
				<script src="http://getbootstrap.com/docs-assets/js/html5shiv.js"></script>
				<script src="http://getbootstrap.com/docs-assets/js/respond.min.js"></script>
		<![endif]-->
		
	</head>
<body ng-app="finsolApp"    >
<!--Start Header-->

<header class="navbar">
	<div class="container-fluid expanded-panel">
		<div class="row">
			<div id="logo" class="col-xs-12 col-sm-2" style='background: darkslategrey;'>
				<a href="index.php" style='color:gold'><img src="../common/images/km_logo.png" height="42" width="100"></a>
			</div>
			<div id="top-panel" class="col-xs-12 col-sm-10">
				<div class="row">
						
					<div class="col-xs-12 col-sm-12 top-panel-right" style='background: darkslategrey;'>						
						<a  href='#!actrcon' style='color:yellow;background-color: transparent; border: none;' >Contact</a>
						
						<ul ng-controller='changeLangCtrl' class="nav navbar-nav pull-right panel-menu">
						<button type='button' style='color:yellow;background-color: transparent; border: none;' >Welcome: <?php echo $_SESSION['user_name'];?> [ <?php echo $_SESSION['profile_name'];?> ] </button>
						<?php if($_SESSION['language_id']== "2") {?>
						<button type='button' ng-click = "englang();" style='color:aliceblue;background-color: transparent; border: none;'><span class="glyphicon glyphicon-tags"></span>&nbsp;&nbsp;English&nbsp;&nbsp;&nbsp;|</button>
						<?php } if($_SESSION['language_id']== "1" || $_SESSION['language_id']== "") {?>
						<button type='button' ng-click = "hauslang();"  style='color:aliceblue;background-color: transparent; border: none;'><span class="glyphicon glyphicon-tags"></span>&nbsp;&nbsp;Hausa&nbsp;&nbsp;&nbsp;|</button>
						<?php } ?>
						<a  href="logout.php" style='color:aquamarine;text-decoration:none'> <span class="glyphicon glyphicon-log-out"></span> Logout</a>
						<!--<li class="dropdown">
						<a href="#" class="dropdown-toggle account" data-toggle="dropdown">
						<div class="avatar">

						</div>
						<i class="fa fa-angle-down pull-right"></i>
						<div class="user-mini pull-right">
						<span class="welcome">Welcome</span>
						<span></span>
						</div>
						</a>
						<ul class="dropdown-menu">
						<li>
						<a href="#!logout">
						<i class="fa fa-power-off"></i>
						<span>Logout</span>
						</a>
						</li>
						</ul>
						</li> -->
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<style>
#breadcrumb {
    padding: 0;
    line-height: 40px;
    background: firebrick !important;
    margin-bottom: 10px;
}
	</style>
</header>

<!--End Header-->
<!--Start Container-->
<div id="main" class="container-fluid">
	<div class="row">
		<div id="sidebar-left" class="col-xs-2 col-sm-2" style='background: darkslategrey;'>
			<ul class="nav main-menu" style='background: darkslategrey;'>
			<li class="dropdown">
					<a href="#!dash" class="dropdown-toggle">
						<i class="fa fa-table"></i>
						 <span class="hidden-xs">Dash Board</span>
					</a>
					
				</li>
				
				
				
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-bar-chart-o"></i>
						 <span class="hidden-xs"><?php echo INDEX_PAYEMENT; ?></span>
					</a>
					<ul class="dropdown-menu">
						<li><a class="ajax-link" href="#!payent"><?php echo INDEX_PAYMENT_ENTRY; ?></a></li>
						<li><a class="ajax-link" href="#!payviw"><?php echo INDEX_PAYMENT_VIEW; ?></a></li>
						<li><a class="ajax-link" href="#!payapr"><?php echo INDEX_PAYMENT_APPROVE; ?></a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-crosshairs"></i>
						 <span class="hidden-xs"><?php echo INDEX_ADJUSTMENT; ?></span>
					</a>
					<ul class="dropdown-menu">
						<li><a class="ajax-link" href="#!adjent"><?php echo INDEX_ADJUSTMENT_ENTRY; ?></a></li>
						<li><a class="ajax-link" href="#!adjviw"><?php echo INDEX_ADJUSTMENT_VIEW; ?></a></li>
						<li><a class="ajax-link" href="#!adjapr"><?php echo INDEX_ADJUSTMENT_APPROVE; ?></a></li>
					</ul>
				</li>
				
				<?php  foreach ($_SESSION['SERVICE_GROUP'] as $service_group_obj) { 
				error_log($service_group_obj);?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle">
							<i class="fa fa-shopping-cart"></i>
							 <span style="color:orange" class="hidden-xs"><?php echo $service_group_obj->name; ?></span>
						</a>
						<ul class="dropdown-menu">
						<?php foreach ($service_group_obj->features as $service_feature_obj) { ?>
							<li><a class="ajax-link" href="#!<?php echo $service_feature_obj->href; ?>"><?php echo $service_feature_obj->name; ?></a></li>
							<?php } ?>
						</ul>
						
					</li>
				
				<?php } ?>
				
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-usd"></i>
						 <span class="hidden-xs"><?php echo INDEX_COMMISSION; ?></span>
					</a>
					<ul class="dropdown-menu">
						<li><a class="ajax-link" href="#!comviw"><?php echo INDEX_COMMISSION_VIEW; ?></a></li>
						<li><a class="ajax-link" href="#!comlis"><?php echo INDEX_PAYOUT_LIST; ?></a></li>
						<li><a class="ajax-link" href="#!compay"><?php echo INDEX_PAYOUT; ?></a></li>
						<li><a class="ajax-link" href="#!comjco"><?php echo INDEX_JOURNAL_COMMISSION; ?></a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-line-chart"></i>
						 <span class="hidden-xs"><?php echo INDEX_REPORT; ?></span>
					</a>
					<ul class="dropdown-menu">
						<li><a class="ajax-link" href="#!rptsal">Sales Report</a></li>
						<li><a class="ajax-link" href="#!rptsta"><?php echo INDEX_STATISTICAL_REPORT; ?></a></li>
						<li><a class="ajax-link" href="#!rptfin"><?php echo INDEX_FINANCIAL_REPORT; ?></a></li>
						<li><a class="ajax-link" href="#!rptetr">EVD Sales Report</a></li>
						<li><a class="ajax-link" href="#!rptevdsta">EVD Statistical Report</a></li>
						<li><a class="ajax-link" href="#!evdrptfin">EVD Financial Report</a></li>
						<li><a class="ajax-link" href="#!rptfndwlt">Fund Wallet Report</a></li>
						<li><a class="ajax-link" href="#!rptcashoutpay">Cash Out Payment Report</a></li>
						<!--
						<li><a class="ajax-link" href="#!rpttra"><?php echo INDEX_TRANSACTION_REPORT; ?></a></li>
						<li><a class="ajax-link" href="#!nontrans"><?php echo INDEX_NON_TRANSACTION_REPORT; ?></a></li>
						-->
						<li><a class="ajax-link" href="#!rptbtr">Batch Transaction Report</a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-line-chart"></i>
						 <span class="hidden-xs"><?php echo INDEX_AUDIT; ?></span>
					</a>
					<ul class="dropdown-menu">		
						<li><a class="ajax-link" href="#!nibacc"><?php echo INDEX_PARTY_NIBSS_ACCOUNT_AUDIT; ?></a></li>
						<li><a class="ajax-link" href="#!rptloa">Agent List</a></li>
						<li><a class="ajax-link" href="#!rptlis">Agent Last Activity</a></li>
						<li><a class="ajax-link" href="#!rptwab">Wallet/Account balance</a></li>
					</ul>
				</li>
				
			</ul>			
		</div>
		<!--Start Content-->
		<div id="content" class="col-xs-12 col-sm-10">		
			
			<div id='ngview' ng-view></div>
		</div>
		<!--End Content-->
	</div>
</div>

<!--End Container-->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!--<script src="../common/js/jquery.js"></script>-->
<script src="../common/plugins/jquery/jquery.min.js"></script>
<script src="../common/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="../common/plugins/bootstrap/bootstrap.min.js"></script>
<script src="../common/plugins/tinymce/tinymce.min.js"></script>
<script src="../common/plugins/tinymce/jquery.tinymce.min.js"></script>
<!-- All functions for this theme + document.ready processing -->
<script src="../common/js/jquery.min.3.3.1.js"></script>
<script src="../common/js/bootstrap.min.3.3.7.js"></script>
<script src="../common/js/angular.min.1.6.4.js"></script>
<script src="../common/js/angular-route.1.6.4.js"></script>
<script src="../common/js/angular-material.min.js"></script>
<script src="../common/js/angular-animate_1.4.8.min.js"></script>
<script src="js/app.js"></script> 
<script src="js/controller.js"></script>
<script src="../controller/comcontroller.js"></script>
<script src="../common/js/angular-aria_1.4.8.js"></script>
<script src="../common/js/angular-messages_1.4.8.min.js"></script>
<script src="../common/js/devoops.js"></script>
</body>
</html>
