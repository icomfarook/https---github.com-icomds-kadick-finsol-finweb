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
		<title> Kadick Mo&#8358;ei :: System </title>
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
		<link href="../common/css/v3.css" rel="stylesheet" />
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
				<script src="http://getbootstrap.com/docs-assets/js/html5shiv.js"></script>
				<script src="http://getbootstrap.com/docs-assets/js/respond.min.js"></script>
		<![endif]-->
<style>
#breadcrumb {
	background: crimson !important;
}
.nav.main-menu > li > a, .nav.msg-menu > li > a {
	color: aliceblue !important;	
}
</style>
	</head>
<script>
function seviceConfig(id,sfid) {
var row = $(".partyName").closest('tr');
$.ajax({
 url: "../ajax/load.php",
 type: "get", //send it through get method
 data: {
action: 'active',
for: 'servfeaconfig',
"id": id.value,
"sfid":sfid.value
 },
 success: function(response) {  
$(sfid).parent().closest('tr').find(".serfconfig option").remove();
$(sfid).parent().closest('tr').find(".serfconfig").append("<option value=''>--Select Config--</option>"+response);
}
});
$(document).ready(function(){  

    $('#Submit').attr('disabled',true);
$('.rateva').keyup(function(){
        if($(this).val().length ==0)
            $('#Submit').attr('disabled', true);            
       
    })
$('#Submit').attr('disabled',true);
    $('.active').keyup(function(){
        if($(this).val().length ==0)
            $('#Submit').attr('disabled', true);            
       
    })

$('#Submit').attr('disabled',true);
    $('#serfconfig').keyup(function(){
        if($(this).val().length ==0)
            $('#Submit').attr('disabled', true);            
       
    })
$('#Submit').attr('disabled',true);
    $('.sdate,.edate').keyup(function(){
        if($(this).val() != "")
            $('#Submit').attr('disabled', false);            
       
    })

$('#resTable').parent().closest('tr').find(".serfconfig,.partyName,.servicefeature,.rateva,.active,.sdate,.edate").each(function() {
if($(this).val() != "")
  $('#Submit').attr('disabled', false);
});
});
}
</script>


<body ng-app="finsolApp"  disable-right-click>
<!--Start Header-->

<header class="navbar">
	<div class="container-fluid expanded-panel">
		<div class="row">
			<div id="logo" class="col-xs-12 col-sm-2" style='background: darkcyan;'>
				<a href="index.php" style='color:gold'><img src="../common/images/km_logo.png" height="42" width="100"></a>
			</div>
			<div id="top-panel" class="col-xs-12 col-sm-10" style='background: darkcyan;'>
				<div class="row">
					
					<div class="col-xs-12 col-sm-12 top-panel-right ">
						<a  href='#!actrcon' style='color:yellow;background-color: transparent; border: none;' >Contact</a>
						<ul ng-controller='changeLangCtrl' class="nav navbar-nav pull-right panel-menu">
						<button type='button' style='color:yellow;background-color: transparent; border: none;' >Welcome: <?php echo $_SESSION['user_name'];?> [ <?php echo $_SESSION['profile_name'];?> ] </button>
						<?php if($_SESSION['language_id']== "2") {?>
						<button type='button' ng-click = "englang()" style='color:aliceblue;background-color: transparent; border: none;'><span class="glyphicon glyphicon-tags"></span>&nbsp;&nbsp;English&nbsp;&nbsp;&nbsp;|</button>
						<?php } if($_SESSION['language_id']== "1" || $_SESSION['language_id']== "") {?>
						<button type='button' ng-click = "hauslang()"  style='color:aliceblue;background-color: transparent; border: none;'><span class="glyphicon glyphicon-tags"></span>&nbsp;&nbsp;Hausa&nbsp;&nbsp;&nbsp;|</button>
						<?php } ?>
						<button type='button'style='color:aliceblue;background-color: transparent; border: none;'><span class="glyphicon glyphicon-screenshot"></span>&nbsp;&nbsp;<?php echo FINSOL_APP_VERSION; ?>&nbsp;&nbsp;&nbsp;- <?php echo FINSOL_DB_VERSION; ?>&nbsp;&nbsp;&nbsp;&nbsp;|</button>
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
</header>
<!--End Header-->
<!--Start Container-->
<div id="main" class="container-fluid">
	<div class="row">
		<div id="sidebar-left" class="col-xs-2 col-sm-2" style='background: darkcyan;'>
			<ul class="nav main-menu" style='background: darkcyan;'>
				<li class="dropdown">
					<a href="#!dash" class="dropdown-toggle">
						<i class="fa fa-table"></i>
						 <span class="hidden-xs">Dash Board</span>
					</a>
					
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-table"></i>
						 <span class="hidden-xs"><?php echo INDEX_APPLICATION; ?></span>
					</a>
					<ul class="dropdown-menu">
						<li><a class="ajax-link" href="#!papent"><?php echo INDEX_PRE_APPLICATION_ENTRY; ?></a></li>
						<li><a class="ajax-link" href="#!papviw"><?php echo INDEX_PRE_APPLICATION_VIEW; ?></a></li>
						<li><a class="ajax-link" href="#!appent"><?php echo INDEX_APPLICATION_ENTRY; ?></a></li>
						<li><a class="ajax-link" href="#!appviw"><?php echo INDEX_APPLICATION_VIEW; ?></a></li>
						<li><a class="ajax-link" href="#!appapr"><?php echo INDEX_APPLICATION_APPROVE; ?></a></li>
						<li><a class="ajax-link" href="#!appaut"><?php echo INDEX_APPLICATION_AUTHORIZE; ?></a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-database"></i>
						 <span class="hidden-xs"><?php echo INDEX_MASTER; ?></span>
					</a>
					<ul class="dropdown-menu">
						<li><a class="ajax-link" href="#!mstcnt"><?php echo INDEX_COUNTRY; ?></a></li>
						<li><a class="ajax-link" href="#!mstste"><?php echo INDEX_STATE; ?></a></li>
						<li><a class="ajax-link" href="#!mstloc"><?php echo INDEX_LOCAL_GOVERNMENT; ?></a></li>
						<li><a class="ajax-link" href="#!mstsgp"><?php echo INDEX_SERVICE_GROUP; ?></a></li>
						<li><a class="ajax-link" href="#!mstsfm"><?php echo INDEX_SERVICE_FEATURE_MENU; ?></a></li>
						<li><a class="ajax-link" href="#!mstctl">Control Flag</a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-arrows-alt"></i>
						 <span class="hidden-xs"><?php echo INDEX_ACCESS; ?></span>
					</a>
					<ul class="dropdown-menu">
						<li><a class="ajax-link" href="#!acssus"><?php echo INDEX_SYSTEM_USER; ?></a></li>
						<li><a class="ajax-link" href="#!acspro"><?php echo INDEX_PROFILE; ?></a></li>					
						<li><a class="ajax-link" href="#!acsaut"><?php echo INDEX_AUTHORIZATION; ?></a></li>
						<li><a class="ajax-link" href="#!acsusr"><?php echo INDEX_USER; ?></a></li>
						<li><a class="ajax-link" href="#!acsrsn"><?php echo INDEX_BLOCK_REASON; ?></a></li>
						<li><a class="ajax-link" href="#!acsacs"><?php echo INDEX_POS_ACCESS; ?></a></li>
						<li><a class="ajax-link" href="#!ascposmen">POS Menu</a></li>
						<li><a class="ajax-link" href="#!acsact"><?php echo INDEX_POS_ACTIVITY; ?></a></li>
						<li><a class="ajax-link" href="#!asctervend">Terminal Vendor </a></li>
						<li><a class="ajax-link" href="#!ascterinvn">Terminal Inventory </a></li>
						<li><a class="ajax-link" href="#!ascteralloc">Terminal Allocation </a></li>
						<li><a class="ajax-link" href="#!bankacc">Payment Bank</a></li>
						<li><a class="ajax-link" href="#!accservice">Account Service Bank</a></li>

					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-globe"></i>
						<span class="hidden-xs">Group Account</span>
					</a>
					<ul class="dropdown-menu">
						<li><a class="ajax-link" href="#!upgrade">Upgrade</a></li>
						<li><a class="ajax-link" href="#!crechild">Create Child</a></li>
						<li><a class="ajax-link" href="#!grouplist">Group List</a></li>
						<li><a class="ajax-link" href="#!transfund">Transfer Fund</a></li>
						<li><a class="ajax-link" href="#!transtatus">Transfer Status</a></li>
					</ul>
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
						<i class="fa fa-users"></i>
						 <span class="hidden-xs"><?php echo INDEX_PARTY; ?></span>
					</a>
					<ul class="dropdown-menu">
						<li><a class="ajax-link" href="#!ptyinf"><?php echo INDEX_PARTY_INFO; ?></a></li>
						<li><a class="ajax-link" href="#!ptywlt"><?php echo INDEX_PARTY_WALLET; ?></a></li>
						<li><a class="ajax-link" href="#!ptyacc"><?php echo INDEX_PARTY_BANK_ACCOUNT; ?></a></li>
						<li><a class="ajax-link" href="#!ptyjen"><?php echo INDEX_PARTY_JOURNAL_ENTRY; ?></a></li>
						<li><a class="ajax-link" href="#!payacc"><?php echo INDEX_PARTY_PAYABLE_ACCOUNT; ?></a></li>
						<li><a class="ajax-link" href="#!ptyrac"><?php echo INDEX_PARTY_RECEIVABLE_ACCOUNT; ?></a></li>
						<li><a class="ajax-link" href="#!ptytss"><?php echo INDEX_PARTY_TSS_ACCOUNT; ?></a></li>
					</ul>
				</li>
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
						<li><a class="ajax-link" href="#!rpttra"><?php echo INDEX_TRANSACTION_REPORT; ?></a></li>
						<li><a class="ajax-link" href="#!nontrans"><?php echo INDEX_NON_TRANSACTION_REPORT; ?></a></li>
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
						<li><a class="ajax-link" href="#!rpttraaud">Transaction Audit</a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-tasks"></i>
						 <span class="hidden-xs"><?php echo INDEX_TREATMENT; ?></span>
					</a>
					<ul class="dropdown-menu">
						<li><a class="ajax-link" href="#!treinf"><?php echo INDEX_TREATMENT_INFO; ?></a></li>
						<li><a class="ajax-link" href="#!trewlt"><?php echo INDEX_TREATMENT_WALLET; ?></a></li>
						<li><a class="ajax-link" href="#!cashotre">Cash Out - Card</a></li>
						<li><a class="ajax-link" href="#!batch">Batch</a></li>

					</ul>
				</li>
				
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-tachometer"></i>
						 <span class="hidden-xs"><?php echo INDEX_RATING; ?></span>
					</a>
					<ul class="dropdown-menu">
						<li><a class="ajax-link" href="#!ratfea"><?php echo INDEX_SERVICE_FEATURE; ?></a></li>
						<li><a class="ajax-link" href="#!ratpty"><?php echo INDEX_SERVICE_CHARGE_PARTY; ?></a></li>
						<li><a class="ajax-link" href="#!ratgrp"><?php echo INDEX_SERVICE_CHARGE_GROUP; ?></a></li>
						<li><a class="ajax-link" href="#!ratcfg"><?php echo INDEX_SERVICE_FEATURE_CONFIG; ?></a></li>
						<li><a class="ajax-link" href="#!ratrte"><?php echo SERVICE_CHARGE_RATE; ?></a></li>
						<li><a class="ajax-link" href="#!ratvat">Value Added Tax</a></li>
						<li><a class="ajax-link" href="#!ratflx">Flexi Rate</a></li>
						<li><a class="ajax-link" href="#!flxratagt">Flexi Rate - Agent</a></li>
						<li><a class="ajax-link" href="#!stamduty">Stamp Duty</a></li>
						<li><a class="ajax-link" href="#!rulval">Rule Validator</a></li>
					</ul>
				</li>				
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-university"></i>
						 <span class="hidden-xs"><?php echo INDEX_PARTNER; ?></span>
					</a>
					<ul class="dropdown-menu">
						<li><a class="ajax-link" href="#!ptnptn"><?php echo INDEX_PARTNER; ?></a></li>
						<li><a class="ajax-link" href="#!ptntyp"><?php echo INDEX_PARTNER_TYPE; ?></a></li>
						<li><a class="ajax-link" href="#!ptncat"><?php echo INDEX_PARTNER_CATEGORY; ?></a></li>
					</ul>
				</li>	
				<li class="dropdown">
				<a href="#" class="dropdown-toggle">
				<i class="fa fa-android"></i>
				<span class="hidden-xs">Debug </span>
				</a>
				<ul class="dropdown-menu">
				<li><a class="ajax-link" href="#!andapp">Android App</a></li>
				</ul>
				</li> 
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-gear"></i>
						 <span class="hidden-xs"><?php echo INDEX_CONTROL; ?></span>
					</a>
					<ul class="dropdown-menu">
						<li><a class="ajax-link" href="#!ctrpwd"><?php echo INDEX_PASSWORD; ?></a></li>
						<li><a class="ajax-link" href="#!actrcon">Contact</a></li>
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
<script src="js/app.js"></script> 
<script src="js/controller.js"></script>
<script src="../controller/comcontroller.js"></script>
<script src="../common/js/angular-material.min.js"></script>
<script src="../common/js/angular-animate_1.4.8.min.js"></script>
<script src="../common/js/angular-aria_1.4.8.js"></script>
<script src="../common/js/angular-messages_1.4.8.min.js"></script>
<script src="../common/js/devoops.js"></script>
<script src="../common/js/select1.js"></script>
<script>
function SetHeight(){
    var h = $(window).height();
    $("#sidebar-left").height(h);    
$("#sidebar-left").css('overflow','scroll');  
}

$(document).ready(SetHeight);
$(window).resize(SetHeight);
</script>
</body>
</html>
