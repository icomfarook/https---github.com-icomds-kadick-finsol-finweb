<?php 	
	include('../common/sessioncheck.php');
	error_reporting(0);
	$lang = $_SESSION['language_id'];
	$user=  $_SESSION['user_name'];
	$profile_name= $_SESSION['profile_name'];
	$first_name= $_SESSION['first_name'];
	$last_name=  $_SESSION['last_name']; 
	$email =  $_SESSION['email'];
	$active=     $_SESSION['active'];
	$lastlogin = $_SESSION['last_login']; 
	$invalid_attempt = $_SESSION['invalid_attempt'];
	$sdate  = $_SESSION['start_date'];
	$edate = $_SESSION['expiry_date'];
	
	
		   	  
	if($lang == "1" || $lang=="") {
		include('../common/admin/finsol_label_ini.php');
	}
	if($lang == "2"){
		include('../common/admin/haus_finsol_label_ini.php');
	}
?>

<div ng-controller='MyaccCtrl'>



  
</head>

<body class="materialdesign">
	<!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    	<!-- Header top area start-->

<div id="overlay">
<div class="preloader-single shadow-reset mg-t-30 loadpag">
	<div class="ts_preloading_box">
		<div id="ts-preloader-absolute22">
			<div class="tsperloader22" id="first_tsperloader22"></div>
			<div class="tsperloader22" id="second_tsperloader22"></div>
			<div class="tsperloader22" id="third_tsperloader22"></div>
		</div>
	</div>
</div>
<div id="progstat">
</div>
<div id="progress"></div>
</div>

<div id="container">

</div>
  
    	<div class="wrapper-pro">
        	<div class="left-sidebar-pro">
            		<?php include('leftsidebar.php') ?>
        	</div>
        	<!-- Header top area start-->
        	<div class="content-inner-all">
		 	<?php include('uppernavbar.php') ?>
         		<?php include('mobilemenu.php') ?>  
            		<!-- Mobile Menu end -->

            		<!-- Static Table Start -->
            		<div class="login-form-area mg-t-30 mg-b-40">
                		<div class="container-fluid">
                    			<div class="row">					
<style>
login-input-head p {
    padding: 9px 0px;
    margin: 0;
    font-size: 14px;
	color:blue;
}
</style><div class="row" >
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#"><?php echo MY_ACC_HEADING1; ?></a></li>
		</ol>
		
	</div>
</div>
						<div class="col-lg-12">
							<div class="login-bg">                                 
								<div class="row">
									<div class="col-lg-12">
										<div class="login-title"><h3><?php echo MY_ACC_HOME; ?></h3>
										</div>
									</div>
								</div> 
								<table class='table table-bordered'>
									<tr>	
										<td width="50%"><div class="login-input-head"><?php echo MY_ACC_USER_NAME; ?> <?php echo $user ?></div></td>
										<td width="50%"><div class="login-input-head"><?php echo MY_ACC_PROFILE; ?> <?php echo $profile_name  ?></div></td>
										
									</tr>
									<tr>
										<td width="50%"><div class="login-input-head"><?php echo MY_ACC_FIRST_NAME; ?> <?php echo $first_name ?></div></td>
										<td width="50%"><div class="login-input-head"><?php echo MY_ACC_LAST_NAME; ?> <?php echo $last_name  ?></div></td>
									</tr>
									<tr>
										<td width="50%"><div class="login-input-head"><?php echo MY_ACC_EMAIL; ?>	 <?php echo $email ?></div></td>
										<td width="50%"><div class="login-input-head"><?php echo MY_ACC_ACTIVE; ?> <?php echo $active  ?></div></td>
									</tr>
									<tr>
										<td width="50%"><div class="login-input-head"><?php echo MY_ACC_START_DATE; ?> <?php echo $sdate ?></div></td>
										<td width="50%"><div class="login-input-head"><?php echo MY_ACC_EXPIRY_DATE; ?> <?php echo $edate  ?></div></td>
									</tr>
									<tr>
										<td width="50%"><div class="login-input-head"><?php echo MY_ACC_LAST_LOGIN; ?> <?php echo $lastlogin ?></div></td>
										<td width="50%"><div class="login-input-head"><?php echo MY_ACC_INVALID_ATTEMPT; ?> <?php echo $invalid_attempt  ?></div></td>
									</tr>
									
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
	
	// Run Datables plugin and create 3 variants of settings
function AllTables(){
	//TestTable1();
	//TestTable2();
	//TestTable3();
	//LoadSelect2Script(MakeSelect2);
}

$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	//WinMove();
	$("#EditstateDialogue, #AddStateDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	$("#EditstateDialogue, #AddstateDialogue").on("keypress",".sc", function (event) {
		    var regex = new RegExp("^[a-zA-Z0-9 \b:/.~!@#$*_-]+$");
 		    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
 		    if (!regex.test(key)) {
 		       event.preventDefault();
 		       return false;
 		    }
 		});
});
</script>
