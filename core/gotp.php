 <?php 	
	include('../common/sessioncheck.php');
	error_reporting(0);
	$lang = $_SESSION['language_id'];
	if($lang == "1" || $lang=="") {
		include('../common/admin/finsol_label_ini.php');
	}
	if($lang == "2"){
		include('../common/admin/haus_finsol_label_ini.php');
	}
?>
<style>
#AddAuthorizationDialogue .table > tbody > tr > td {
	border:none;
}
.labspa {
	color:blue;
}
</style>
<div ng-controller='gOtpCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!finotp"><?php echo GENERATE_OTP_MAIN_HEADING1; ?></a></li>
			<li><a href="#!finotp"><?php echo GENERATE_OTP_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo GENERATE_OTP_MAIN_HEADING3; ?></span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding">			
			  <div style='text-align:center' ng-hide='isMainLoader' class="loading-spiner-holder" data-loading ><div class="loading-spiner"><img src="../common/img/gif1.gif" /></div></div>			
				<form name='gotpForm' method='POST'>	
					<div class='row appcont'>
						<div class='row appcont' ng-init="creteria='BT' ">
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo GENERATE_OTP_MAIN_MOBILE_NO;?><span class='spanre'>*</span><span ng-show="gotpForm.mobileno.$dirty && gotpForm.mobileno.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label><span style="color:Red" ng-show="gotpForm.mobileno.$dirty && gotpForm.mobileno.$error.minlength"> <?php echo MIN_11_NUMBERS_REQUIRED; ?> </span>
								<input ng-model="mobileno" numbers-only type='text' id='Mobile No' ng-minlength="11" maxlength = '20' name='mobileno' required class='form-control'/>
							</div>	
												
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo GENERATE_OTP_MAIN_ACCOUNT_NO;?><span class='spanre'>*</span><span ng-show="gotpForm.accountno.$dirty && gotpForm.accountno.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label><span style="color:Red" ng-show="gotpForm.accountno.$dirty && gotpForm.accountno.$error.minlength"> <?php echo MIN_11_NUMBERS_REQUIRED; ?> </span>
								<input ng-model="accountno" numbers-only type='text' id='Account No' ng-minlength="11" maxlength = '20' name='accountno' required class='form-control'/>
							</div>	
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo GENERATE_OTP_MAIN_AMOUNT;?><span class='spanre'>*</span></label>
								<input ng-model="amount" numbers-only type='text' id='Amount No' maxlength = '20' name='amount' required class='form-control'/>
							</div>
						</div>
						<div class='row appcont'>
							<div style='text-align:center' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-click='gotpForm.$invalid=true;gotp()' ng-hide='isHide'  id="GenerateOtp"><?php echo GENERATE_OTP_MAIN_GENERATE_OTP;?></button>
								<button type="button" ng-click='reset()' class="btn btn-primary"   id="Reset"><?php echo GENERATE_OTP_MAIN_BUTTON_RESET;?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo GENERATE_OTP_MAIN_BUTTON_REFRESH;?></button>
							</div>
						</div>	
						
					<div class='row appcont' ng-hide='isResDiv' style='border: 1px solid black !important;width: 50%;margin: auto;'>
						<div class='row appcont'>
							<p><?php echo GENERATE_OTP_MAIN_STATUS;?> <span style='color:blue'>{{responseDescription}}</span></p>
						</div>
						<div class='row appcont'>
							<p><?php echo GENERATE_OTP_MAIN_CODE;?>  <span style='color:blue'>{{responseCode}}</span></p>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
	
</div>
<script type="text/javascript">

$(document).ready(function() {
	$("#GenerateOtp").click(function() {		
		
	});
	$("#PaymentApproveDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	$("#Refresh").click(function() {
		window.location.reload();
	});	
	
});
</script>