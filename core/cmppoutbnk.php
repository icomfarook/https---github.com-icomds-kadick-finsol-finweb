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
	$partyType = $_SESSION['party_type'];
	$partyCode = $_SESSION['party_code'];	
	$agent_name	=   $_SESSION['party_name'];
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
<div ng-controller='cmpPoutBankCtrl' data-ng-init="fn_load(<?php echo "'".$partyType."',"."'".$partyCode."'" ?>)">
<div class="row" >
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!paybank"><?php echo PAYOUT_REQUEST_HEADING1; ?></a></li>
			<li><a href="#!paybank">Payout Bank</a></li>
		</ol>
		
	</div>
</div>

	<div class="row">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Payout Bank</span>
				</div>
				
				<div class="no-move"></div> 
			</div>
			
		<div class="box-content" style='padding: 0px 10px !important;'>		
			<form  name='payOutListForm'  ng-model='payOutListForm' id='payOutListForm' >			
			 <h3>Payout Bank</h3><br />
			  <div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
				<div ng-init = 'creteria = "B"' id='PayentryCreateBody'  ng-hide='isLoader' ng-init="partycode='<?php echo $partycode; ?>';creteria='SP'">
					
					 
						<div class='row appcont' style='width:85%;margin:auto'>
					 <fieldset class='scheduler-border' ng-hide='isUpForm'>					
						<legend class='scheduler-border'><?php echo PAYOUT_REQUEST_SUB_TITLE; ?> </legend>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
									<label><?php echo INFO_PARTY_CODE_CHAMPION ; ?><span class='spanre'>*</span>
									<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
									<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input  readonly = 'true' ng-disabled="creteria==='TP'" [(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
								</div>					
								
							<div style="margin-top: 2%;"  class='col-lg-6 col-xs-12 col-md-12 col-sm-12'  ng-hide='isButtonDiv'>						
							<button  type='button' class='btn btn-primary' ng-click='payOutListForm.$invalid=true;query()'  id='Query' ng-hide='isQuery' ><?php echo PAYOUT_REQUEST_BUTTON_QUERY; ?></button>
							</div>
							
							
						
						</form>
					</fieldset >
					</div><br />
				<form ng-hide='ispayRequestForm' name='payRequestForm'  ng-hide='isLoader' ng-model='payRequestForm' id='payRequestForm' method='POST' action=''>	
					 <div class='rowcontent' style='width: 50%; margin: auto;'>
						<div class='row appcont' style='padding:4px 0px;'>						
						   <div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYOUT_REQUEST_CURRENT_COMMISSION_BALANCE; ?><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
								<span ng-show="payRequestForm.curbalance.$dirty && payRequestForm.curbalance.$invalid">
								<span class = 'err' ng-show="payRequestForm.curbalance.$error.required"><?php echo REQUIRED;?></span></span></label>	
									<input ng-model='curbalance' readonly='true' class='form-control' name='curbalance'/>
							</div>
						</div>
						<div class='row appcont' style='padding:4px 0px;'>		
							<div class='col-lg-10 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYOUT_REQUEST_PAYOUT_COMMISSION_AMOUNT; ?><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
								<span ng-show="payRequestForm.paycomamt.$dirty && payRequestForm.paycomamt.$invalid">
								<span class = 'err' ng-show="payRequestForm.paycomamt.$error.required"><?php echo REQUIRED;?></span></span></label>						
								<input ng-blur='cal()' numbers-only  ng-model='paycomamt'  id="paycomamt" class='form-control' name='paycomamt'/>
							</div>
							<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12'><br />
								<button type="button" class="btn btn-primary"  ng-disabled="payRequestForm.$invalid"  ng-click='payRequestForm.$invalid=ture;calculate()' ng-hide='isHideGo'   id="GO">Calculate</button>
							
							</div>
						</div>
						<div class='row appcont' style='padding:4px 0px;'>		
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYOUT_REQUEST_PROCESSING_CHARGE; ?><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
								<span ng-show="payRequestForm.procharge.$dirty && payRequestForm.procharge.$invalid">
								<span class = 'err' ng-show="payRequestForm.procharge.$error.required"><?php echo REQUIRED;?></span></span></label>													
								<input ng-init='procharge=0.00' ng-model='procharge' readonly='true' class='form-control' name='procharge'/>
							</div>
						</div>
						<div class='row appcont' style='padding:4px 0px;'>		
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYOUT_REQUEST_TOTAL_PAYOUT_COMMISSION; ?><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
								<span ng-show="payRequestForm.totalpaycom.$dirty && payRequestForm.totalpaycom.$invalid">
								<span class = 'err' ng-show="payRequestForm.totalpaycom.$error.required"><?php echo REQUIRED;?></span></span></label>						
								<input ng-model='totalpaycom' readonly='true' class='form-control' name='totalpaycom'/>
							</div>
						</div>
						<div class='row appcont' style='padding:4px 0px;'>	
							<div  class='col-lg-12 col-xs-12 col-md-12 col-sm-12' style='float:center' width="20%" ng-show="creteria == 'B'">
								<label><?php echo PAYOUT_REQUEST_BANK_ACCOUNT; ?><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
								<span ng-show="payRequestForm.bankaccount.$dirty && payRequestForm.bankaccount.$invalid">
								<span class = 'err' ng-show="payRequestForm.bankaccount.$error.required"><?php echo REQUIRED;?></span></span></label>									
									<select ng-model="bankaccount"  class='form-control' name = 'bankaccount' id='bankaccount' required>											
										<option value=""><?php echo PAYMENT_ENTRY_SELECT_BANK; ?></option>
										<option ng-repeat="bank in partybanks" value="{{bank.id}}">{{bank.name}}-{{bank.account}}</option>
								</select>
							</div>
						</div>	
																		
							<div  style='text-align:center'>
								<button type='button'  confirmed-click='payRequestForm.$invalid=true;payout()' class='btn btn-primary'  id='Payout' ng-confirm-click="Are you sure want to Transfer the Wallet - Amount to the  Bank ?"  ng-disabled = "payRequestForm.$invalid"  ng-hide='isPayout' ><?php echo PAYOUT_REQUES_PAYOUT; ?></button>
								<button type="button" class="btn btn-primary" ng-click='reset()'  ng-hide='isHideResetS' id="Reset"><?php echo PAYOUT_REQUEST_BUTTON_RESET; ?></button>		
							</div>
						</div>
				 </form>
				<div class='row appcont' ng-hide='isResDiv' style='height:100px;border: none !important;width: 50%;margin: auto;'>
					
					<div class='row appcont'>
						<h3><span style='color:blue'> {{msg}} : {{errorResponseDescription}}</span></h3>
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

	
	 $('#GO').attr('disabled',true);
    $('#paycomamt').keyup(function(){
		  if($(this).val().length !=0)
            $('#GO').attr('disabled', false);            
        else
            $('#GO').attr('disabled',true);
    })
	 
	
	$("#reset").click(function() {
		$('#GO').attr('disabled',true);
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
