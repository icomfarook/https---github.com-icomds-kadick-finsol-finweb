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
	$partyType = $_SESSION['party_type'];
	$profileId = $_SESSION['profile_id'];
	$partyCode = $_SESSION['party_code'];

?>
<style>
#AddINFODialogue .table > tbody > tr > td {
	border:none;
}
.form_col12_element {
	margin-top:1%;
}
</style>
<div ng-controller='traEnCtrl' >
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!fintra"><?php echo TRANSACTION_ENQUIRY_MAIN_HEADING1; ?></a></li>
			<li><a href="#!fintra"><?php echo TRANSACTION_ENQUIRY_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo TRANSACTION_ENQUIRY_MAIN_HEADING3; ?></span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding">	
			<div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>
				<form name='transactionEnqForm ' method='POST'>	
					<div class='row appcont'>	
						<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>										
							<label><?php echo TRANSACTION_ENQUIRY_MAIN_REFERENCE_NUMBER; ?><span class='spanre'>*</span>
							<span ng-show="transactionEnqForm.refno.$dirty && transactionEnqForm.refno.$invalid">
							<span class = 'err' ng-show="transactionEnqForm.refno.$error.required"><?php echo REQUIRED;?></span></span></label>	
							<input type='text' name='refno' ng-model='refno' maxlength="25" placeholder='<?php echo TRANSACTION_ENQUIRY_MAIN_PLACE_HOLDER_REFERENCE_NO; ?>' class='form-control'/>										
						</div>
						<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
							<label><?php echo TRANSACTION_ENQUIRY_MAIN_PARTNERS; ?><span class='spanre'>*</span><span id='vali' ng-show="transactionEnqForm.partner.$touched ||transactionEnqForm.partner.$dirty && transactionEnqForm.partner.$invalid">
							<span class = 'err' ng-show="transactionEnqForm.partner.$error.required"><?php echo REQUIRED;?></span></span></label>
							<select ng-model="partner" class='form-control' name = 'partner' id='partner' required>											
								<option value=''><?php echo FINANCE_SEVICE_ORDER_CASH_IN_SELECT_PARTNER; ?></option>
								<option ng-repeat="par in partners" lab='{{par.name}}' value="{{par.id}}">{{par.name}}</option>
							</select>
						</div>						
						 <div  class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
						 <br />
							<button type="button" class="btn btn-primary" ng-click='transactionEnqForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo TRANSACTION_ENQUIRY_MAIN_BUTTON_QUERY; ?></button>
							<button type="button" class="btn btn-primary"   id="Refresh"><?php echo TRANSACTION_ENQUIRY_MAIN_BUTTON_REFRESH; ?></button>
						</div>
					</div>		
					<div class='row appcont' style='text-align:Cen' ng-hide='tabeHide' data-backdrop="static" data-keyboard="false">	
						<table class='table tbble-bordered' style='background-color:black;color:white'>
							<tr>
								<td><?php echo TRANSACTION_ENQUIRY_MAIN_TRANSACTION_MODE; ?></td>
								<td>{{operation}}</td>
							</tr>
							<tr>
								<td><?php echo TRANSACTION_ENQUIRY_MAIN_TRANSACTION_ID; ?></td>
								<td>{{txid}}</td>
							</tr>
							<tr>
								<td><?php echo TRANSACTION_ENQUIRY_MAIN_TRANSACTION_REF; ?></td>
								<td>{{refno}}</td>
							</tr>
							<tr>
								<td><?php echo TRANSACTION_ENQUIRY_MAIN_TRANSACTION_DATE; ?></td>
								<td>{{txdate}}</td>
							</tr>
							<tr>
								<td><?php echo TRANSACTION_ENQUIRY_MAIN_CREDIT_ACCOUNT; ?></td>
								<td>{{creacc}}</td>
							</tr>
							<tr>
								<td><?php echo TRANSACTION_ENQUIRY_MAIN_DEBIT_ACCOUNT; ?></td>
								<td>{{debacc}}</td>
							</tr>
							<tr>
								<td><?php echo TRANSACTION_ENQUIRY_MAIN_TX_DESCRIPTION; ?></td>
								<td>{{txdesc}}</td>
							</tr>
							<tr>
								<td><?php echo TRANSACTION_ENQUIRY_MAIN_IS_REVERSED; ?></td>
								<td>{{isreversed}}</td>
							</tr>
							<tr>
								<td><?php echo TRANSACTION_ENQUIRY_MAIN_REVERSAL_DATE; ?></td>
								<td>{{reversaldate}}</td>
							</tr>
							<tr>
								<td><?php echo TRANSACTION_ENQUIRY_MAIN_AMOUNT; ?></td>
								<td>{{amount}}</td>
							</tr>
							<tr>
								<td><?php echo TRANSACTION_ENQUIRY_MAIN_STATUS; ?></td>
								<td>{{status}}</td>
							</tr>
							<tr>
								<td><?php echo TRANSACTION_ENQUIRY_MAIN_RESPONSE_CODE; ?></td>
								<td>{{rescode}}</td>
							</tr>
							<tr>
								<td><?php echo TRANSACTION_ENQUIRY_MAIN_RESPONSE_DESCRIPTION; ?></td>
								<td>{{resdesc}}</td>
							</tr>
							<tr>
								<td><?php echo TRANSACTION_ENQUIRY_MAIN_PROCESS_START_TIME; ?></td>
								<td>{{prostart}}</td>
							</tr>
							</table>
						</div>
						<div ng-hide='tabeHide2' class='row appcont' style='text-align:Cen' >	
							<table  class='table table-bordered' style='background-color:red;color:white'>	
								<tr>
								<td><?php echo TRANSACTION_ENQUIRY_MAIN_RESPONSE_CODE; ?></td>
								<td>{{rescode}}</td>
								</tr>
								<tr>
								<td><?php echo TRANSACTION_ENQUIRY_MAIN_DESCRIPTION; ?></td>
								<td>{{resdesc}}</td>
								</tr>
								<tr>
								<td><?php echo TRANSACTION_ENQUIRY_MAIN_PROCESS_START_TIME; ?></td>
								<td>{{prostart}}</td>
							</tr>
							</table>
						</div>
					</div>
					
					</div>
				</form>
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
}
$(document).ready(function() {

	$("#Refresh").click(function() {
		window.location.reload();
	});

});
</script>