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
	$profileId = $_SESSION['profile_id'];
	
?>
<style>
fieldset.scheduler-border {
    border: 1px groove #ddd !important;
	padding: 0 0.1em 2.0em 0.1em !important;
    margin: 0 0 0.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
}

legend.scheduler-border {
    font-size: 1.0em !important;
    font-weight: bold !important;
    text-align: left !important;
	border:none;
	width:100px;
}
legend {
	border-bottom:none;
}
.center {
	text-align:center;
}
.appcont {
    margin: 0.5% 1%;
}
.box {
	border:none;
}
.form-control {
    display: inline-block;
     padding: 6px 12px;
    font-size: 13px;
	
}
.table > tbody > tr > td {
	border-top:none !important;
}
.rowcontent {
padding:0px;
}
#selUser  .select2-selection .select2-selection--single{
	height:30px !important;
}

</style>
<div ng-controller='payReqCtrl'>
<div class="row" >
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!compay"><?php echo PAYOUT_REQUEST_HEADING1; ?></a></li>
			<li><a href="#!compay"><?php echo PAYOUT_REQUEST_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

	<div class="row">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo PAYOUT_REQUEST_HEADING3; ?></span>
				</div>
				
				<div class="no-move"></div> 
			</div>
			
		<div class="box-content" style='padding: 0px 10px !important;'>		
			<form  name='payOutListForm'  ng-model='payOutListForm' id='payOutListForm' >			
			  <div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
				<div ng-init = 'creteria = "W"' id='PayentryCreateBody'  ng-hide='isLoader' ng-init="partycode='<?php echo $partycode; ?>';creteria='SP'">
					 <h3><?php echo PAYOUT_REQUEST_TITLE; ?></h3>
					 
						<div class='row appcont' style='width:85%;margin:auto'>
					 <fieldset class='scheduler-border' ng-hide='isUpForm'>					
						<legend class='scheduler-border'><?php echo PAYOUT_REQUEST_SUB_TITLE; ?> </legend>
							<?php  if($profileId == 1 || $profileId == 10 || $profileId == 20 || $profileId == 22 || $profileId == 26) {?>							
								<div  class='col-lg-3 col-xs-12 col-md-12 col-sm-12' >								
									<label><?php echo PAYOUT_REQUEST_PARTY_TYPE; ?> <span class='spanre'>*</span>
									<span ng-show="payOutListForm.partyType.$dirty && payOutListForm.partyType.$invalid">
									<span class = 'err' ng-show="payOutListForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></label>
										
									<select ng-change='partyload(this.partyType)' ng-model="partyType"  class='form-control' name = 'partyType' id='partytype' required>											
										<option value=""><?php echo PAY_OUT_MAIN_SELECT_TYPE; ?></option>
										<option value='MA'><?php echo PAY_OUT_MAIN_AGENT; ?></option>
										<option value='C'><?php echo PAY_OUT_MAIN_CHAMPION; ?></option>
										<option value='SA'><?php echo PAY_OUT_MAIN_SUB_AGENT; ?></option>
										<option value='P'><?php echo PAY_OUT_MAIN_PERSONAL; ?></option>
									</select>
								</div>								
								<div  class='col-lg-3 col-xs-12 col-md-12 col-sm-12' >														
									<label><?php echo PAYOUT_REQUEST_PARTY_CODE; ?><span class='spanre'>*</span>
									<span ng-show="payOutListForm.partyCode.$dirty && payOutListForm.partyCode.$invalid">
									<span class = 'err' ng-show="payOutListForm.partyCode.$error.required"><?php echo REQUIRED;?></span></span></label>	
								
									<select  ng-model='partyCode' id='selUser' class='form-control' name='partyCode' required >
										<option value=""><?php echo PAY_OUT_MAIN_SELECT_PARTY_CODE; ?></option>												
										<option ng-repeat="info in infos" value="{{info.code}}">{{info.code}} - {{info.name}}</option>
									</select>
								</div>
							
							<?php }  ?>
							<div  class='col-lg-3 col-xs-12 col-md-12 col-sm-12' >
								<label>&nbsp;<br /><span class='spanre'></span>
									<span ng-show="payOutListForm.partyCode.$dirty && payOutListForm.partyCode.$invalid">
									<span class = 'err' ng-show="payOutListForm.creteria.$error.required"></span></span></label>	
									<label style='margin-right:3%'><input value='W' type='radio' name='creteria' ng-model='creteria' /><?php echo PAYOUT_REQUEST_WALLET; ?></label>	
									<label><input value='B' type='radio' name='creteria' ng-model='creteria' /><?php echo PAYOUT_REQUEST_BANK; ?></label>	
							</div>
						<div  class='col-lg-3 col-xs-12 col-md-12 col-sm-12' style='text-align:center' ng-hide='isButtonDiv'>						
							<button type='button' class='btn btn-primary' ng-click='payOutListForm.$invalid=true;query()'  id='Query' ng-hide='isQuery' ><?php echo PAYOUT_REQUEST_BUTTON_QUERY; ?></button>
							<button type="reset" class="btn btn-primary"  ng-hide='isHideReset' id="Reset"><?php echo PAYOUT_REQUEST_BUTTON_RESET; ?></button>
						</div>
						</form>
					</fieldset >
					</div>
				<form ng-hide='ispayRequestForm' name='payRequestForm'  ng-hide='isLoader' ng-model='payRequestForm' id='payRequestForm' method='POST' action=''>	
					 <div class='rowcontent' style='width: 50%; margin: auto;'>
						<div class='row appcont' style='padding:4px 0px;'>						
						   <div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYOUT_REQUEST_CURRENT_COMMISSION_BALANCE; ?></label>		
									<input ng-model='curbalance' readonly='true' class='form-control' name='curbalance'/>
							</div>
						</div>
						<div class='row appcont' style='padding:4px 0px;'>		
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYOUT_REQUEST_PAYOUT_COMMISSION_AMOUNT; ?><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
								<span ng-show="payRequestForm.paycomamt.$dirty && payRequestForm.paycomamt.$invalid">
								<span class = 'err' ng-show="payRequestForm.paycomamt.$error.required"><?php echo REQUIRED;?></span></span></label>						
								<input ng-blur='cal()' ng-model='paycomamt' class='form-control' name='paycomamt'/>
							</div>
						</div>
						<div class='row appcont' style='padding:4px 0px;'>		
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYOUT_REQUEST_PROCESSING_CHARGE; ?></label>							
								<input ng-init='procharge=0.00' ng-model='procharge' readonly='true' class='form-control' name='procharge'/>
							</div>
						</div>
						<div class='row appcont' style='padding:4px 0px;'>		
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYOUT_REQUEST_TOTAL_PAYOUT_COMMISSION; ?></label>							
								<input ng-model='totalpaycom' readonly='true' class='form-control' name='totalpaycom'/>
							</div>
						</div>
						<div class='row appcont' style='padding:4px 0px;'>	
							<div  class='col-lg-12 col-xs-12 col-md-12 col-sm-12' style='float:center' width="20%" ng-show="creteria == 'B'">
								<label><?php echo PAYOUT_REQUEST_BANK_ACCOUNT; ?></label>								
									<select ng-model="bankaccount"  class='form-control' name = 'bankaccount' id='bankaccount' required>											
										<option value=""><?php echo PAYMENT_ENTRY_SELECT_BANK; ?></option>
										<option ng-repeat="bank in partybanks" value="{{bank.id}}">{{bank.name}}-{{bank.account}}</option>
								</select>
							</div>
						</div>	
																		
							<div  style='text-align:center'>
								<button type='button' ng-click='payRequestForm.$invalid=true;payout()' class='btn btn-primary'  id='Payout' ng-hide='isPayout' ><?php echo PAYOUT_REQUES_PAYOUT; ?></button>
								<button type="reset" class="btn btn-primary"  ng-hide='isHideResetS' id="Reset"><?php echo PAYOUT_REQUEST_BUTTON_RESET; ?></button>		
							</div>
						</div>
				 </form>
				<div class='row appcont' ng-hide='isResDiv' style='height:100px;border: none !important;width: 50%;margin: auto;'>
					
					<div class='row appcont'>
						<h3><span style='color:blue'>{{msg}} : {{errorResponseDescription}}</span></h3>
					</div>
					<div class='row appcont' style='text-align:center'>
						<button type="button" class="btn btn-primary"  id="Ok"><?php echo PAYOUT_REQUEST_BUTTON_OK; ?></button>
					</div>
				</div>
			 </div>			
		</div>
	</div>

<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	//TestTable1();
	//TestTable2();
	//TestTable3();
	//LoadSelect2Script();
}

$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	 // LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	//WinMove();
	
	
	
	$("#Reset").click(function() {
		$(".partytype, #mes").hide();
	});
	$("#Ok").click(function() {			
			window.location.reload();
	});
	 $("#selUser").select2();

  // Read selected option
  $('#but_read').click(function(){
    var username = $('#selUser option:selected').text();
    var userid = $('#selUser').val();

    $('#result').html("id : " + userid + ", name : " + username);

  });
});
</script>
