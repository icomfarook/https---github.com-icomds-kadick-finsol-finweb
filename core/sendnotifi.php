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
	$agent_name	=   $_SESSION['party_name'];
	//$partyType = "A";	
	//$partyCode = "AG0101";
	//$profileId = 1;
?>
<style>
#AddINFODialogue .table > tbody > tr > td {
	border:none;
}
.form_col12_element {
	margin-top:1%;
}
#selUser  .select2-selection .select2-selection--single{
	height:30px !important;
}
.left-column{
	width:30% !important;
}
.right-column{
	width:70% !important;
}

</style>
<div ng-controller='SendNotificationCtrl' data-ng-init="fn_load(<?php echo "'".$partyType."',"."'".$partyCode."'" ?>)">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!fcmnotia90"><?php echo INFO_MAIN_HEADING1; ?></a></li>
			<li><a href="#!fcmnotia90">Send Notification A90</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Send Notification A90</span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding"  data-backdrop="static" data-keyboard="false" >	
				<div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>																																	
				<form name='infoViewForm' method='POST'>	
					<div class='row appcont'>						
					
						 <div class='row appcont' ng-init="creteria='A'">
							
								<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>	
								<label><input value='A' ng-click='creterias' type='radio' name='creteria' ng-model='creteria' /></label>
								<label><?php echo STATISTICAL_REPORT_MAIN_AGENT_NAME; ?><span class='spanre'>*</span></label>
								<select  ng-init="agentCode = 'ALL'" id='selUser'  ng-model='agentCode' class='form-control' multiple="multiple" name='agentCode' required  ng-disabled="creteria !='A'">>
									<option value='ALL'>ALL</option>
									<option ng-repeat="agent in agents" value="{{agent.agent_code}}">{{agent.agent_code}} - {{agent.agent_name}}</option>
								</select>									
								</div>
							<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='UT' type='radio' ng-click='creterias' name='creteria' ng-model='creteria' /></label>

								<label>User Type<span class='spanre'>*</span><span ng-show="editINFOForm.userType.$touched ||editINFOForm.userType.$dirty && editINFOForm.userType.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="editINFOForm.userType.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select  ng-model="userType" ng-init="userType='ALL'"  class='form-control' name = 'userType' id='userType' ng-disabled="creteria !='UT'">>											
									<option value='ALL'>ALL</option>
									<option value='I'>Installed</option>
									<option value='R'>Registered</option>
									<option value='L'>Logged In</option>
								</select>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='STATE' type='radio'  ng-click='creterias' name='creteria' ng-model='creteria' /></label>
								<select ng-hide='hide=true' ng-model="country" ng-init="country='<?php echo ADMIN_COUNTRY_ID; ?>';countrychange(this.country)"   class='form-control' name = 'country' id='country' required  ng-disabled="creteria !='LOCAL_GOVT'">>											
									<option ng-repeat="country in countrys" value="{{country.id}}">{{country.description}}</option>
								</select>
								<label>State
								<span ng-show="infoViewForm.partyType.$dirty && infoViewForm.partyType.$invalid">
								<span class = 'err' ng-show="infoViewForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select  ng-model="state" ng-change='statechange(this.state)' ng-init="state=''"  class='form-control' name = 'state' id='state' required ng-disabled="creteria !='STATE' && creteria !='LOCAL_GOVT'">>											
									<option value=''>Select State</option>
									<option ng-repeat="state in states" value="{{state.id}}">{{state.name}}</option>
								</select>
							</div>
							
								
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='LOCAL_GOVT' ng-click='creterias' type='radio' name='creteria' ng-model='creteria' /></label>
								<label>Local Goverment<span ng-show="editINFOForm.local_govt_id.$touched ||editINFOForm.local_govt_id.$dirty && editINFOForm.local_govt_id.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="editINFOForm.local_govt_id.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="local_govt_id"   class='form-control' name = 'local_govt_id' id='LocalGoverment' ng-disabled="(creteria !='LOCAL_GOVT' && creteria !='STATE') || creteria =='STATE'">											
									<option value=''>Select Local Govt</option>
									<option ng-repeat="localgvt in localgvts" value="{{localgvt.id}}">{{localgvt.name}}</option>
								</select>
							</div>
							
						</div>	
						<div class="row">
							<div style='margin-top: 25px;display:flex;justify-content:center'  class='col'>
								<button type="button" class="btn btn-primary" ng-click='query()' ng-hide='isHide'  id="Query"><?php echo INFO_VIEW_QUERY_BUTTON; ?></button>&nbsp;&nbsp;
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo INFO_VIEW_REFRESH_BUTTON; ?></button>
							</div>
						</div>
								
								 
							</div>		
																	
					<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1" ng-if="countTable">
						
								 <tr>
									<th class="left-column">Total number of clients selected for notification:</th>
									<th class="right-column">{{count}}</th>
									
								</tr>
								<tr  ng-show="count > 0">
									<td class="left-column"><label>Title</label></td>
 									<td class="right-column"><input class="form-control"  style="width: -webkit-fill-available;" type="text" name="title" ng-model="title" maxlength='70'></td>
 								</tr>
								<tr  ng-show="count > 0">
									 <td class="left-column"><label>Content</label></td>
									<td class="right-column"><textarea class="form-control" style="width: -webkit-fill-available;" type="text" name="body" ng-model="body" maxlength='200'></textarea></td>
								</tr>
								<tr ng-show="count >0">
									 <td colspan='2'>								
										 <button type="button" class="btn btn-primary" ng-click='send(title, body)'  id="Query">Send Notification</button>
										 
									</td>
									
								</tr>
								<tr ng-show="count==0">
									<td colspan='2' style='text-align:center'>
										No Client is found    
									</td>
								</tr>
						</table>
						</form>
					</div>
				</div>
		</div>
	</div>
	 <div id='infoViewDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false" >
		<div class="modal-dialog modal">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'></h2>
		
				</div>				
					<div  style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
						<form action="" method="POST" name='editINFOForm' id="EditINFOForm">
						<div id='notification-modal-body'>				
						
							<div class='clearfix'></div>
						</div>
						</form>
					</div>				
					<div class='modal-footer'>
					</div>	
			</div>
		</div>	
	</div>	
   
</div>

<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	TestTable1();
	TestTable2();
	TestTable3();
	LoadSelect2Script();
}
$(document).ready(function() {

	$("#infoViewDialogue, #infoEditDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	$("#Refresh").click(function() {
		window.location.reload();
	});
   
	//this script for the datatable.
	$("#Query").click(function() {				
		LoadDataTablesScripts(AllTables);
		
	});

	$("#EditINFODialogue, #AddINFODialogue").on("keypress",".sc", function (event) {
		    var regex = new RegExp("^[a-zA-Z0-9 \b:/.~!@#$*_-]+$");
 		    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
 		    if (!regex.test(key)) {
 		       event.preventDefault();
 		       return false;
 		    }
 		});
		
		 $("#selUser").select2({
    multiple: true,
});

  // Read selected option
  $('#but_read').click(function(){
    var username = $('#selUser option:selected').text();
    var userid = $('#selUser').val();

    $('#result').html("id : " + userid + ", name : " + username);

  });
});
</script>