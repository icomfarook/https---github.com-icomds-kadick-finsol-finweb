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
.labspa{
	color:blue;
	padding-left:10px;
}
.labspafin{
	color:red;
	padding-left:10px;
}
#container{
	width:30%
	
}
</style>
<div ng-controller='agentlistCtrl' >
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!comjco"><?php echo JOUNRAL_ENTRY_COMMI_HEADING1; ?></a></li>
			<li><a href="#!comjco">Agent Last Activity</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Agent Last Activity</span>
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
				<form name='infoViewForm' method='POST'>	
					<div class='row appcont'>									
					  <?php  if($profileId == 1 || $profileId == 10) {?>
						 <div class='row appcont' style='margin-left:9%'>

							 <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' id='container'>
								<select ng-hide='hide=true' ng-model="country" ng-init="country='<?php echo ADMIN_COUNTRY_ID; ?>';countrychange(this.country)"   class='form-control' name = 'country' id='country' required>											
									<option ng-repeat="country in countrys" value="{{country.id}}">{{country.description}}</option>
								</select>
								<label>State
								<span ng-show="infoViewForm.partyType.$dirty && infoViewForm.partyType.$invalid">
								<span class = 'err' ng-show="infoViewForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select  ng-model="state" ng-change='statechange(this.state)' ng-init="state='ALL'" class='form-control' name = 'state' id='state' required>											
									<option value='ALL'>ALL</option>
									<option ng-repeat="state in states" value="{{state.id}}">{{state.name}}</option>
								</select>
								
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' id='container'>										
								<label>Local Government
								<span ng-show="infoViewForm.partyCode.$dirty && infoViewForm.partyCode.$invalid">
								<span class = 'err' ng-show="infoViewForm.partyCode.$error.required"><?php echo REQUIRED;?></span></span></label>	
								<select  ng-model="localgovernment"   ng-init="localgovernment='ALL'"  class='form-control' name = 'localgovernment' id='LocalGoverment' required>											
									<option value='ALL'>ALL</option>
									<option ng-repeat="localgvt in localgvts" value="{{localgvt.id}}">{{localgvt.name}}</option>
								</select>										
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' id='container'>										
								<label>Agent Status
								<span ng-show="infoViewForm.partyCode.$dirty && infoViewForm.partyCode.$invalid">
								<span class = 'err' ng-show="infoViewForm.partyCode.$error.required"><?php echo REQUIRED;?></span></span></label>	
								<select  ng-model='status' class='form-control' ng-init="status='A'" name='status' required >
								<option value="A">Active</option>
								<option value="I">Inactive</option>												
								<option value="C">Dormant</option>	
								</select>										
							</div>

							<?php } else { ?>
								<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>												
									<label><?php echo JOUNRAL_ENTRY_COMMI_MAIN_PARTY_CODE ; ?><span class='spanre'>*</span>
									<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
									<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input  readonly = 'true'[(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
								</div>
							<?php } ?>

							 
						</div>	
							<div  style = 'text-align:Center' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled = 'infoViewForm.$invalid' ng-click='query()' ng-hide='isHide'  id="Query"><?php echo JOUNRAL_ENTRY_COMMI_MAIN_BUTTON_QUERY; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo JOUNRAL_ENTRY_COMMI_MAIN_BUTTON_REFRESH; ?></button>
							</div>
						</div>		
																	
					<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th>Agent Code</th>
									<th>Agent Name</th>
									<th>Login Name</th>
									<th>Parent Code</th>
									<th>Parent Type</th>
									<th>State</th>
									<th>Local Government</th>
									<th>Active</th>
									<th>Last Login</th>
									
								</tr>
							</thead>
							<tbody>
								 <tr ng-show='tablerow' ng-repeat="x in res">
									<td>{{ x.agent_code }}</td>
									<td>{{ x.agent_name }}</td>
									<td>{{ x.login_name }}</td>
									<td>{{ x.parent_code }}</td>
									<td>{{ x.parent_type }}</td>
									<td>{{ x.state }}</td>
									<td>{{ x.local_govt }}</td>
									<td>{{ x.active }}</td>
									<td>{{x.last_login}}</td>
								</tr>
								<tr ng-show="res.length==0">
									<td style='text-align:center' colspan='9' >
										<?php echo JOUNRAL_ENTRY_COMMI_MAIN_NO_DATA_FOUND; ?>            
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
			</div>
		</div>
	</div>
		 <div id='ApplicationViewDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content" ng-repeat="x in resview">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><span ng-show="x.waltype=='aw'"> Account Wallet View</span><span ng-show="x.waltype=='cw'"> Commission Wallet View</span> - {{code}}</h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'  ng-hide='isLoader'>
					<form action="" method="POST" name='ApplicatioViewDialgoue' id="ApplicatioViewDialgoue">
						<div id='ApplicationViewBody' >
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><span ng-show="partycode=='A'">Agent Code : </span><span ng-show="partycode=='C'">Champion Code : </span><span class='labspa'>{{code}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><span ng-show="partycode=='A'">Agent Name :</span><span ng-show="partycode=='C'">Champion Name : </span> 	<span class='labspa'>{{x.agent_name}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>State :<span class='labspa'>{{x.state}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Local Government :<span class='labspa'>{{x.local_govt}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Available Balance :<span class='labspafin'>{{x.available_balance}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Current Balance :<span class='labspafin'>{{x.current_balance}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Advance Amount :<span class='labspafin'>{{x.advance_amount}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Minimum Balance :<span class='labspafin'>{{x.minimum_balance}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Daily Limit :<span class='labspafin'>{{x.daily_limit}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Credit Limit :<span class='labspafin'>{{x.credit_limit}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Last Tx No. :<span class='labspa'>{{x.last_tx_no}}</span></label>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Last Tx Amount :<span class='labspa'>{{x.last_tx_amount}}</span></label>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Last Tx Date :<span class='labspa'>{{x.last_tx_date}}</span></label>
							</div>
							
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
</div>
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	TestTable1();
	TestTable2();
	TestTable3();
	//LoadSelect2Script();
}
$(document).ready(function() {
  //LoadDataTablesScripts(AllTables);
 // WinMove();
	$("#infoViewDialogue, #infoEditDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	$("#Refresh").click(function() {
		window.location.reload();
	});
	$("#EditINFODialogue, #AddINFODialogue").on("keypress",".sc", function (event) {
		    var regex = new RegExp("^[a-zA-Z0-9 \b:/.~!@#$*_-]+$");
 		    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
 		    if (!regex.test(key)) {
 		       event.preventDefault();
 		       return false;
 		    }
 		});
});
</script>