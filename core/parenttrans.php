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
<div ng-controller='ParentTransCtrl'  data-ng-init="fn_load(<?php echo "'".$partyType."',"."'".$partyCode."'" ?>)" >
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!partrans"><?php echo TRANSACTION_ENQUIRY_MAIN_HEADING1; ?></a></li>
			<li><a href="#!partrans">Party Transfer</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Party Transfer</span>
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
				<form name='transactionEnqForm '  ng-hide='ispayRequestForm' method='POST'>	
					<div class='row appcont'>	
					
										 <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
											
											<label><?php echo INFO_PARTY_CODE_TYPE ; ?><span class='spanre'>*</span>
											<span ng-show="transactionEnqForm.partyType.$dirty && transactionEnqForm.partyType.$invalid">
											<span class = 'err' ng-show="transactionEnqForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></label>
											<select ng-change='partyload(this.partyType)' ng-model="partyType"  ng-disabled='parties' class='form-control' name = 'partyType' id='partytype' required>											
												<option value=""><?php echo INFO_VIEW_SELECT_TYPE; ?></option>
												<option value='MA'><?php echo INFO_VIEW_AGENT; ?></option>
												</select>
											
										</div>
										<div  class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>										
											<label><?php echo INFO_PARTY_CODE; ?><span class='spanre'>*</span>
											<span ng-show="transactionEnqForm.partyCode.$dirty && transactionEnqForm.partyCode.$invalid">
											<span class = 'err' ng-show="transactionEnqForm.partyCode.$error.required"><?php echo REQUIRED;?></span></span></label>	
											<select  ng-disabled='parties' ng-model='partyCode' id='selUser' class='form-control' name='partyCode' required >
											<option value=""><?php echo INFO_VIEW_SELECT_CODE; ?></option>												
											<option ng-repeat="info in infos" value="{{info.code}}">{{info.code}} - {{info.name}}</option>
											</select>										
										</div>
									
										 <div style='margin-top: 25px;'  class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
											<button type="button" class="btn btn-primary" ng-disabled = 'transactionEnqForm.$invalid' ng-click='transactionEnqForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo INFO_VIEW_QUERY_BUTTON; ?></button>
											<button type="button" class="btn btn-primary"   id="Refresh"><?php echo INFO_VIEW_REFRESH_BUTTON; ?></button>
										</div>
									</div>	
									</form>
									</div>
									</div>
									
						
								
								 
				<div class="modal-body">		
					<form action="" method="POST"  ng-hide='ispayRequestForm' name='infoViewForm' id="infoViewForm">				
						<div id='ParentTransferBody'  ng-hide='isLoader'>						
							<div  ng-hide='Listbox' class="box-content no-padding">
								<div class="box" style="width: 75%;margin-left: 10%;">
									<div class='row appcont'>	
										<table ng-repeat="x in ParentTransfer"  class='table table-bordered' style="margin: auto;background-color:black;color:white;width: 70%;">	
												<tr>
												<td>Party Type </td> <td>{{x.partyType}}</td>
												
												</tr>
												<tr>
												<td>Party Code </td> <td>{{x.partyCode}}</td>
												</tr>
												<tr>
												<td>Party Name </td> <td>{{x.party_name}}</td>
												</tr>
												<tr>
												<td>Current Parent </td> <td>{{x.parent_code}}</td>
												</tr>
											</table>
									</div><br />
									<div class='row appcont'>	
										<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
										<label>New Parent Type<span class='spanre'>*</span>
											<span ng-show="infoViewForm.NewpartyType.$dirty && infoViewForm.NewpartyType.$invalid">
											<span class = 'err' ng-show="infoViewForm.NewpartyType.$error.required"><?php echo REQUIRED;?></span></span></label>
											<select ng-change='Newpartyload(this.NewpartyType)' ng-model="NewpartyType"  class='form-control' name = 'NewpartyType' id='NewpartyType' required>											
												<option value=""><?php echo INFO_VIEW_SELECT_TYPE; ?></option>
												<option value='C'>Champion</option>
											</select>
															
										</div>
										<div  class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>										
											<label>New Parent Code<span class='spanre'>*</span>
											<span ng-show="infoViewForm.NewpartyCode.$dirty && infoViewForm.NewpartyCode.$invalid">
											<span class = 'err' ng-show="infoViewForm.NewpartyCode.$error.required"><?php echo REQUIRED;?></span></span></label>	
											<select  ng-model='NewpartyCode' id='selUser' class='form-control' name='NewpartyCode' required >
											<option value=""><?php echo INFO_VIEW_SELECT_CODE; ?></option>												
											<option ng-repeat="info in champ" value="{{info.code}}">{{info.code}} - {{info.name}}</option>
											</select>										
										</div>
											 <div  class='modal-footer '  class='col-lg-6 col-xs-12 col-sm-12 col-md-12' style="margin-top: 70px;text-align-last: center;">
											<button type="button" class="btn btn-primary" ng-disabled = 'infoViewForm.$invalid'  ng-click="Transfer(partyCode,partyType,parent_code)"     ng-hide='isPayout'  id="Transfer">Transfer</button>
											<button type="button" ng-click='cancel()' class="btn btn-primary"   id="Refresh">Cancel</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class='row appcont' ng-hide='isResDiv' style='height:100px;border: none !important;width: 50%;margin: auto;'>
					
					<div class='row appcont'>
						<h3><span style='color:blue'> {{msg}}  {{errorResponseDescription}}</span></h3>
					</div>
					<div class='row appcont' style='text-align:center'>
						<button type="button" class="btn btn-primary" ng-click='refresh()'  id="Ok"><?php echo PAYOUT_REQUEST_BUTTON_OK; ?></button>
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
 $("#selUser").select2();

  // Read selected option
  $('#but_read').click(function(){
    var username = $('#selUser option:selected').text();
    var userid = $('#selUser').val();

    $('#result').html("id : " + userid + ", name : " + username);

  });
  
   $("#selUser1").select2();

  // Read selected option
  $('#but_read').click(function(){
    var username = $('#selUser1 option:selected').text();
    var userid = $('#selUser1').val();

    $('#result').html("id : " + userid + ", name : " + username);

  });
});
</script>