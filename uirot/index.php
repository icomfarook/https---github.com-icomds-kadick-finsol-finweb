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
		<title> Kadick Moni :: Root </title>
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

<body ng-app="finsolApp"    >
<!--Start Header-->

<header class="navbar">
	<div class="container-fluid expanded-panel">
		<div class="row">
			<div id="logo" class="col-xs-12 col-sm-2" style='background-color:black'>
				<a href="index.php" style='color:gold'><img src="../common/images/km_logo.png" height="42" width="100"></a>
			</div>
			<div id="top-panel" class="col-xs-12 col-sm-10">
				<div class="row">
						
					<div class="col-xs-12 col-sm-12 top-panel-right" style='background-color:black'>						
						<a  href='#!actrcon' style='color:yellow;background-color: transparent; border: none;' >Contact</a>
						
						<ul ng-controller='changeLangCtrl' class="nav navbar-nav pull-right panel-menu">
						<button type='button' style='color:yellow;background-color: transparent; border: none;' >Welcome: <?php echo $_SESSION['user_name'];?> [ <?php echo $_SESSION['profile_name'];?> ] </button>
						<?php if($_SESSION['language_id']== "2") {?>
						<button type='button' ng-click = "englang();" style='color:aliceblue;background-color: transparent; border: none;'><span class="glyphicon glyphicon-tags"></span>&nbsp;&nbsp;English&nbsp;&nbsp;&nbsp;|</button>
						<?php } if($_SESSION['language_id']== "1" || $_SESSION['language_id']== "") {?>
						<button type='button' ng-click = "hauslang();"  style='color:aliceblue;background-color: transparent; border: none;'><span class="glyphicon glyphicon-tags"></span>&nbsp;&nbsp;Hausa&nbsp;&nbsp;&nbsp;|</button>
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
		<div id="sidebar-left" class="col-xs-2 col-sm-2" style='background-color:black'>
			<ul class="nav main-menu" style='background-color:black'>
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
						<li><a class="ajax-link" href="#!mstreg"><?php echo Region; ?></a></li>
						<li><a class="ajax-link" href="#!mstste"><?php echo INDEX_STATE; ?></a></li>
						<li><a class="ajax-link" href="#!mstloc"><?php echo INDEX_LOCAL_GOVERNMENT; ?></a></li>
						<li><a class="ajax-link" href="#!mstsgp"><?php echo INDEX_SERVICE_GROUP; ?></a></li>
						<li><a class="ajax-link" href="#!mstsfm"><?php echo INDEX_SERVICE_FEATURE_MENU; ?></a></li>
						<li><a class="ajax-link" href="#!mstctl">Control Flag</a></li>
						<li><a class="ajax-link" href="#!ptncat"><?php echo INDEX_PARTNER_CATEGORY; ?></a></li>
						<li><a class="ajax-link" href="#!mastarg">Party Target Combo</a></li>
						<li><a class="ajax-link" href="#!mascatar">Party Category Target</a></li>
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
						<li class="dropdown">
						<a href="#" class="dropdown-toggle">
						<i class="fa fa-tablet"></i>
						<span style="color:white" class="hidden-xs" href="">POS</span>
						</a>
						<ul class="dropdown-menu">
						<li><a class="ajax-link" style="color:orange" href="#!acsacs"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;POS Access</a></li>
						<li><a class="ajax-link" style="color:orange" href="#!ascposmen"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;POS Menu</a></li>
						<li><a class="ajax-link" style="color:orange" href="#!acsact"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;POS Activity</a></li>
						</ul>
						</li>

						<li class="dropdown">
						<a href="#" class="dropdown-toggle">
						<i class="fa fa-mobile"></i>
						<span style="color:white" class="hidden-xs"  >Terminal</span>
						</a>
						<ul class="dropdown-menu">
						<li><a class="ajax-link"  style="color:orange" href="#!asctervend"><i class="fa fa-dot-circle-o" aria-hidden="true">&nbsp;</i>Terminal Vendor</a></li>
						<li><a class="ajax-link"  style="color:orange" href="#!ascterinvn"><i class="fa fa-dot-circle-o" aria-hidden="true">&nbsp;</i>Terminal Inventory</a></li>
						<li><a class="ajax-link"  style="color:orange" href="#!ascteralloc"><i class="fa fa-dot-circle-o" aria-hidden="true">&nbsp;</i>Terminal Allocation</a></li>
						<li><a class="ajax-link"  style="color:orange" href="#!ascterbond"><i class="fa fa-dot-circle-o" aria-hidden="true">&nbsp;</i>Terminal Bound</a></li>

						</ul>
						</li>
						
						<li class="dropdown">
						<a href="#" class="dropdown-toggle">
						<i class="fa fa-credit-card"></i>
						<span style="color:white" class="hidden-xs"  >Card</span>
						</a>
						<ul class="dropdown-menu">
						<li><a class="ajax-link"  style="color:orange" href="#!cardinvn"><i class="fa fa-dot-circle-o" aria-hidden="true">&nbsp;</i>Card Inventory</a></li>
						<li><a class="ajax-link"  style="color:orange" href="#!cardalloc"><i class="fa fa-dot-circle-o" aria-hidden="true">&nbsp;</i>Card Allocation</a></li>
						</ul>
						</li>
		
						<li><a class="ajax-link" href="#!waltblnce"><i class="fa fa-university" aria-hidden="true">&nbsp;</i>Wallet Balance</a></li>
						<li class="dropdown">
						<a href="#" class="dropdown-toggle">
						<i class="fa fa-building"></i>
						<span style="color:white" class="hidden-xs" href="">Bank</span>
						</a>
						<ul class="dropdown-menu">
						<li><a class="ajax-link" style="color:orange" href="#!accservice"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Account Service Bank</a></li>
						<li><a class="ajax-link" style="color:orange" href="#!bankacc"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Payment Bank</a></li>
						<li><a class="ajax-link" style="color:orange" href="#!casussd"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;CashOut USSD Bank</a></li>
						<li><a class="ajax-link" style="color:orange" href="#!casphone"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;CashOut Phone Bank</a></li>
						</ul>
						</li>
						

						<li class="dropdown">
						<a href="#" class="dropdown-toggle">
						<i class="fa fa-laptop"></i>
						<span style="color:white" class="hidden-xs" href="">Adempiere</span>
						</a>
						<ul class="dropdown-menu">
						<li><a class="ajax-link" style="color:orange" href="#!aiservice"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;AI Services</a></li>
						<li><a class="ajax-link" style="color:orange" href="#!aisummary"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;AI Summary</a></li>
						<li><a class="ajax-link" style="color:orange" href="#!aidetail"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;AI Detail</a></li>
						</ul>
						</li>
						<li><a class="ajax-link" href="#!ctmstype">&nbsp;CTMS Type State</a></li>
						<li><a class="ajax-link" href="#!nibss">&nbsp;NIBSS POSVAS</a></li>
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
						<li><a class="ajax-link" href="#!walhis">Wallet History</a></li>
						<li><a class="ajax-link" href="#!partrans">Party Transfer</a></li>
						<li><a class="ajax-link" href="#!ptyacc"><?php echo INDEX_PARTY_BANK_ACCOUNT; ?></a></li>
						<li><a class="ajax-link" href="#!ptyjen"><?php echo INDEX_PARTY_JOURNAL_ENTRY; ?></a></li>
						<li><a class="ajax-link" href="#!payacc"><?php echo INDEX_PARTY_PAYABLE_ACCOUNT; ?></a></li>
						<li><a class="ajax-link" href="#!ptyrac"><?php echo INDEX_PARTY_RECEIVABLE_ACCOUNT; ?></a></li>
						<li><a class="ajax-link" href="#!ptytss"><?php echo INDEX_PARTY_TSS_ACCOUNT; ?></a></li>
						<li><a class="ajax-link" href="#!ptykyc">KYC Update</a></li>
						<li class="dropdown">
						<a href="#" class="dropdown-toggle">
						<i class="fa fa-laptop"></i>
						<span style="color:white" class="hidden-xs" href="">Notification</span>
						</a>
						<ul class="dropdown-menu">
						<li><a class="ajax-link" style="color:orange" href="#!fcmnoti"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Send Notification</a></li>
						<li><a class="ajax-link" style="color:orange" href="#!fcmnotia90"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Send Notification A90</a></li>
						<li><a class="ajax-link" style="color:orange" href="#!clntlist"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Client List</a></li>
						<li><a class="ajax-link" style="color:orange" href="#!clnttop"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Client Topic</a></li>
						<li><a class="ajax-link" style="color:orange" href="#!topsub"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Topic Subscription</a></li>
						<li><a class="ajax-link" style="color:orange" href="#!nothis"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Notification History</a></li>
						</ul>
						</li>
						<li class="dropdown">
						<a href="#" class="dropdown-toggle">
						<i class="fa fa-newspaper-o"></i>
						<span style="color:white" class="hidden-xs" href="">Agent Ranking</span>
						</a>
						<ul class="dropdown-menu">
						<li><a class="ajax-link" style="color:orange" href="#!agntmnth"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Agent Rank - Monthly</a></li>
						<li><a class="ajax-link" style="color:orange" href="#!agntdaily"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Agent Rank - Daily</a></li>
						<li><a class="ajax-link" style="color:orange" href="#!agntsum"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Agent Rank - Summary</a></li>
						</ul>
						</li>
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
						<li class="dropdown">
							<a href="#" class="dropdown-toggle">
								<i class="fa fa-newspaper-o"></i>
								 <span style="color:white" class="hidden-xs"  >Sales</span>
							</a>
							<ul class="dropdown-menu">
								<li ><a class="ajax-link"  style="color:orange" href="#!rptsal"><i class="fa fa-dot-circle-o" aria-hidden="true">&nbsp;</i>Cash Sales Report</a></li>
								<li ><a class="ajax-link"  style="color:orange" href="#!rptbpsal"><i class="fa fa-dot-circle-o" aria-hidden="true">&nbsp;</i>Bill Pay Sales Report</a></li>
								<li><a class="ajax-link" style="color:orange" href="#!rptetr"><i class="fa fa-dot-circle-o" aria-hidden="true">&nbsp;</i>EVD Sales Report</a></li>
								<li ><a class="ajax-link"  style="color:orange" href="#!rptaccsal"><i class="fa fa-dot-circle-o" aria-hidden="true">&nbsp;</i>Acc. Service Sales Report</a></li>
							</ul>	
						</li>
						<li class="dropdown">
							<a href="" class="dropdown-toggle">
								<i class="fa fa-file"></i>
								 <span style="color:white" class="hidden-xs" >Statistical</span>
							</a>
							<ul class="dropdown-menu">
								<li><a class="ajax-link" style="color:orange" href="#!rptsta"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Cash Stat Report</a></li>
								<li><a class="ajax-link" style="color:orange" href="#!rptbpsta"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Bill Pay Stat Report</a></li>
								<li><a class="ajax-link"  style="color:orange" href="#!rptevdsta"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;EVD Stat Report</a></li>
								<li><a class="ajax-link" style="color:orange" href="#!rptaccsta"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Acc. Service Stat Report</a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a href="" class="dropdown-toggle">
								<i class="fa fa-money"></i>
								<span style="color:white" class="hidden-xs" href="#!rptfin">Finance</span>
							</a>
							<ul class="dropdown-menu">
								<li><a class="ajax-link" style="color:orange" href="#!rptfin"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Cash Finance Report</a></li>	
								<li><a class="ajax-link" style="color:orange" href="#!rptbpfin"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Bill Pay Finance Report</a></li>	
								<li><a class="ajax-link" style="color:orange" href="#!evdrptfin"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;EVD Finance Report</a></li>
								<li><a class="ajax-link" style="color:orange" href="#!rptaccfin"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Acc. Service Finance Report</a></li>
							</ul>	
						</li>
						<li class="dropdown">
							<a href="" class="dropdown-toggle">
								<i class="fa fa-book"></i>
								<span style="color:white" class="hidden-xs" >Transaction</span>
							</a>
							<ul class="dropdown-menu">
								<li><a class="ajax-link" style="color:orange" href="#!rpttra"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Cash Transaction Report</a></li>
								<li><a class="ajax-link" style="color:orange" href="#!rptbptra"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Bill Pay Transaction Report</a></li>
								<li><a class="ajax-link" style="color:orange" href="#!rptacctra"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Acc. Service Transaction Report</a></li>
								<li><a class="ajax-link" style="color:orange" href="#!nontrans"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;<?php echo INDEX_NON_TRANSACTION_REPORT; ?></a></li>
								<li><a class="ajax-link" style="color:orange" href="#!rptbtr"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Batch Transaction Report</a></li>
								<li><a class="ajax-link" style="color:orange" href="#!daitrans"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Daily Transaction Report</a></li>
								<li><a class="ajax-link" style="color:orange" href="#!mnthtrans"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Monthly Transaction Report</a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle">
								<i class="fa fa-money"></i>
							 	<span style="color:white" class="hidden-xs" href="">Payment</span>
							</a>
							<ul class="dropdown-menu">
								<li><a class="ajax-link" style="color:orange" href="#!rptfndwlt"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Fund Wallet Report</a></li>
								<li><a class="ajax-link" style="color:orange" href="#!rptcashoutpay"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Cash Out Report</a></li>
							</ul>	
						</li>
					
						<!--
						<li><a class="ajax-link" href="#!trpagt">Fin.Transaction per Agent</a></li>
						-->
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
						<li><a class="ajax-link" href="#!posapi">POSVAS API</a></li>
						<li><a class="ajax-link" href="#!rptdup">Duplicate Order</a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-tasks"></i>
						 <span class="hidden-xs"><?php echo INDEX_TREATMENT; ?></span>
					</a>
					<ul class="dropdown-menu">
						<li><a class="ajax-link" style="color:orange" href="#!treinf"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;<?php echo INDEX_TREATMENT_INFO; ?></a></li>
						<li><a class="ajax-link" style="color:orange" href="#!trewlt"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;<?php echo INDEX_TREATMENT_WALLET; ?></a></li>
						<li><a class="ajax-link" style="color:orange" href="#!cashotre"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Cash Out - Card</a></li>
						<li><a class="ajax-link" style="color:orange" href="#!billbet"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Betting</a></li>
						<li><a class="ajax-link" style="color:orange" href="#!batch"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Batch</a></li>

					</ul>
				</li>
				
					<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-tachometer"></i>
						 <span class="hidden-xs"><?php echo INDEX_RATING; ?></span>
					</a>
					<ul class="dropdown-menu">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle">
						<i class="fa fa-star"></i>
						<span style="color:white" class="hidden-xs" href="">Rate Rule</span>
						</a>
						<ul class="dropdown-menu">
						<li><a class="ajax-link" style="color:orange" href="#!ratfea"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Service Feature</a></li>
						<li><a class="ajax-link" style="color:orange" href="#!ratpty"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Service Charge Party</a></li>
						<li><a class="ajax-link" style="color:orange" href="#!ratgrp"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Service Charge Group</a></li>
						<li><a class="ajax-link" style="color:orange" href="#!ratcfg"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Service Feature Config</a></li>
						<li><a class="ajax-link" style="color:orange" href="#!ratrte"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;Service Charge Rate</a></li>
						</ul>
						</li>
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
<script src="../common/js/angular-material.min.js"></script>
<script src="../common/js/angular-animate_1.4.8.min.js"></script>
<script src="js/app.js"></script> 
<script src="js/controller.js"></script>
<script src="../controller/comcontroller.js"></script>
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
