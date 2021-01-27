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
<div ng-controller='nEnquiryCtrl' >
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!finneq "><?php echo NAME_ENQUIRY_HEADING1; ?></a></li>
			<li><a href="#!finneq "><?php echo NAME_ENQUIRY_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo NAME_ENQUIRY_HEADING3; ?></span>
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
				<form name='nameEnquiryForm ' method='POST'>	
					<div class='row appcont'>	
						<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>										
							<label><?php echo NAME_ENQUIRY_ACCOUNT_NUMBER; ?><span class='spanre'>*</span>
							<span ng-show="nameEnquiryForm.accno.$dirty && nameEnquiryForm.accno.$invalid">
							<span class = 'err' ng-show="nameEnquiryForm.accno.$error.required"><?php echo REQUIRED;?></span></span></label>	
							<input type='text' name='accno' autofocus="true" numbers-only ng-model='accno' maxlength="10" ng-disabled = "isdisable" placeholder='<?php echo NAME_ENQUIRY_ACCOUNT_NUMBER; ?>' class='form-control'/>										
						</div>
						<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>										
							<label><?php echo NAME_ENQUIRY_RE_ENTER_ACCOUNT_NUMBER; ?><span class='spanre'>*</span>
							<span ng-show="nameEnquiryForm.reacc.$touched ||nameEnquiryForm.reacc.$dirty && nameEnquiryForm.reacc.$invalid">
								<span class = 'err' ng-show="nameEnquiryForm.reacc.$error.required"><?php echo REQUIRED;?></span></span>
							<span class = 'err' ng-show="(nameEnquiryForm.accno.$modelValue !== nameEnquiryForm.reacc.$modelValue) "><?php echo BANK_ACCOUNT_VALID_ACCNT_NO_DOESNOT_MATCH; ?></span></label>	
							<input type='text' name='reacc' numbers-only ng-model='reacc' maxlength="10" placeholder='<?php echo NAME_ENQUIRY_RE_ENTER_ACCOUNT_NUMBER; ?>' ng-disabled = "isdisable" class='form-control'/>										
						</div>

						<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
							<label><?php echo NAME_ENQUIRY_BANK; ?><span class='spanre'>*</span><span id='vali' ng-show="nameEnquiryForm.partner.$touched ||nameEnquiryForm.partner.$dirty && nameEnquiryForm.partner.$invalid">
							<span class = 'err' ng-show="nameEnquiryForm.partner.$error.required"><?php echo REQUIRED;?></span></span></label>
							<select ng-model="bank" class='form-control' name = 'bank' id='partner' ng-disabled = "isdisable" >											
								<option value=''><?php echo NAME_ENQUIRY_SELECT_PARTNER; ?></option>
								<option ng-repeat="bank in banks" lab='{{bank.name}}' value="{{bank.id}}">{{bank.name}}</option>
							</select>
						</div>	
						
						 <div  style='text-align: center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12' >
						 <br />
							<button type="button" class="btn btn-primary"  ng-click='nameEnquiryForm.$invalid=true;query();disable()' ng-disabled = "isdisable;"  ng-hide='isHide'  id="Query"><?php echo NAME_ENQUIRY_MAIN_BUTTON_QUERY; ?></button>
							<button type="button" class="btn btn-primary"   id="Refresh"><?php echo NAME_ENQUIRY_MAIN_BUTTON_REFRESH; ?></button>
						</div>
						
					</div>		
					<div class='row appcont' style='text-align:Cen' ng-hide='tabeHide' data-backdrop="static" data-keyboard="false">	
						<table class='table tbble-bordered' style='background-color:black;color:white'>
							<tr>
								<td><?php echo NAME_ENQUIRY_DETAIL_ID; ?> :</td>
								<td>{{sessionId}}</td>
							</tr>
							<tr>
								<td><?php echo NAME_ENQUIRY_DETAIL_ACCOUNT_NAME; ?> :</td>
								<td>{{accountName}}</td>
							</tr>							
							<tr>
								<td><?php echo NAME_ENQUIRY_DETAIL_ACCOUNT_NUMBER; ?> :</td>
								<td>{{accountNo}}</td>
							</tr>
							<tr>
								<td><?php echo NAME_ENQUIRY_DETAIL_DESTINATION_INSTITUTION_CODE; ?> :</td>
								<td>{{dcode}}</td>
							</tr>
							<tr>
								<td><?php echo NAME_ENQUIRY_DETAIL_CHANNEL_CODE; ?> :</td>
								<td>{{ccode}}</td>
							</tr>
							<tr>
								<td><?php echo NAME_ENQUIRY_DETAIL_BANK_VERFICATION_NUMBER; ?> :</td>
								<td>{{bvn}}</td>
							</tr>
							<tr>
								<td><?php echo NAME_ENQUIRY_DETAIL_KYC_LEVEL; ?> :</td>
								<td>{{kyclevel}}</td>
							</tr>
							
							<tr>
								<td><?php echo NAME_ENQUIRY_DETAIL_RESPONSE_CODE; ?></td>
								<td>{{responseCode}}</td>
							</tr>
							<tr>
								<td><?php echo NAME_ENQUIRY_DETAIL_REPONSE_DESCRIPTION; ?></td>
								<td>{{resdesc}}</td>
							</tr>
							
							</table>
						</div>
						<div ng-hide='tabeHide2' class='row appcont' style='text-align:Cen' >	
							<table  class='table table-bordered' style='background-color:red;color:white'>	
								<tr>
								<td><?php echo NAME_ENQUIRY_DETAIL_RESPONSE_CODE; ?></td>
								<td>{{rescode}}</td>
								</tr>
								<tr>
								<td><?php echo NAME_ENQUIRY_DETAIL_DESCRIPTION; ?></td>
								<td>{{resdesc}}</td>
								</tr>
								
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