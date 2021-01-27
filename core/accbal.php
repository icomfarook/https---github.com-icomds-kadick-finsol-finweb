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
<div ng-controller='accBalCtrl' >
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!finacc"><?php echo ACCOUNT_MAIN_HEADING1; ?></a></li>
			<li><a href="#!finacc"><?php echo ACCOUNT_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo ACCOUNT_MAIN_HEADING3; ?></span>
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
				<form name='bankAccountForm' method='POST'>	
					<div class='row appcont'>	
						<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>										
							<label><?php echo ACCOUNT_MAIN_BANK_ACCOUNT; ?><span class='spanre'>*</span>
							<span ng-show="bankAccountForm.bankAccount.$dirty && bankAccountForm.bankAccount.$invalid">
							<span class = 'err' ng-show="bankAccountForm.bankAccount.$error.required"><?php echo REQUIRED;?></span></span></label>	
							<select  ng-model='accno' class='form-control' name='accno' required >
								<option value=""><?php echo ACCOUNT_MAIN_SELECT_BANK_ACCOUNT; ?></option>												
								<option ng-repeat="acc in bankaccts" value="{{acc.id}}">{{acc.name}}</option>
							</select>										
						</div>									
						 <div  class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
						 <br />
							<button type="button" class="btn btn-primary" ng-click='bankAccountForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo ACCOUNT_MAIN_BUTTON_QUERY; ?></button>
							<button type="button" class="btn btn-primary"   id="Refresh"><?php echo ACCOUNT_MAIN_BUTTON_REFRESH; ?></button>
						</div>
					</div>		
					<div class='row appcont' style='text-align:Cen' ng-hide='tabeHide'>	
						<table class='table tbble-bordered' style='background-color:black;color:white'>
							<tr>
								<td><?php echo ACCOUNT_MAIN_ACCOUNT_NO; ?></td> <td>{{accountNo}}</td>
							</tr>
							<tr>
								<td><?php echo ACCOUNT_MAIN_ACCOUNT_NAME; ?></td> <td>{{accname}}</td>
							</tr>
							<tr>
								<td><?php echo ACCOUNT_MAIN_ACCOUNT_BALANCE; ?></td><td>{{avabal}}</td>
							</tr>
						
							<tr>
							<td><?php echo ACCOUNT_MAIN_CURRENT_BALANCE; ?></td><td>{{currbal}}</td>
							</tr>

							<tr>
							<td><?php echo ACCOUNT_MAIN_RESPONSE_CODE; ?></td><td>{{rescode}}</td>
							</tr>

							<tr>
							<td><?php echo ACCOUNT_MAIN_RES_DESCRIPTION; ?></td><td>{{resdesc}}</td>
							</tr>
							
							<tr>
							<td><?php echo ACCOUNT_MAIN_PROCESS_START_TIME; ?></td><td>{{resdesc}}</td>
							</tr>
							</table>
						</div>
						<div ng-hide='tabeHide2' class='row appcont' style='text-align:Cen' >	
							<table  class='table table-bordered' style='background-color:red;color:white'>	
								<tr>
								<td><?php echo ACCOUNT_MAIN_RESPONSE_CODE; ?></td>
								<td>{{rescode}}</td>
								</tr>
								<tr>
								<td><?php echo ACCOUNT_MAIN_DESCRIPTION; ?></td>
								<td>{{resdesc}}</td>
								</tr>
								<tr>
								<td><?php echo ACCOUNT_MAIN_PROCESS_START_TIME; ?></td>
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
}

$(document).ready(function() {
  LoadDataTablesScripts(AllTables);
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