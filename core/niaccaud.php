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

</style>
<div ng-controller='nibsAccCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!nibacc"><?php echo NIBSS_ACC_AUDIT_HEADING1; ?></a></li>
			<li><a href="#!nibacc"><?php echo NIBSS_ACC_AUDIT_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo NIBSS_ACC_AUDIT_HEADING2; ?></span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding" >	
			<div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>
				<form name='nibsaccForm' method='POST'>	
					<div class='row appcont'>						
														
								 <div class='row appcont'>
										 <div style="margin-left: 30%;" class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
											<label style="text-align:center;margin-left: 22%;"><?php echo NIBSS_ACC_AUDIT_HEADING3; ?>
											<span ng-show="nibsaccForm.creteria.$dirty && nibsaccForm.creteria.$invalid">
											<span class = 'err' ng-show="nibsaccForm.creteria.$error.required"><?php echo REQUIRED;?></span></span></label>
											<select  ng-disabled=""  ng-model='creteria'  class='form-control' name = 'creteria' id='creteria' required>											
												<option value=""><?php echo NIBSS_ACC_AUDIT_SELECT_ACC_AUDIT; ?></option>
												<option value='AP'><?php echo NIBSS_ACC_AUDIT_ACC_PAYABLE; ?></option>
												<option value='AR'><?php echo NIBSS_ACC_AUDIT_RECIVABALE_ACC; ?></option>
												<option value='AT'><?php echo NIBSS_ACC_AUDIT_TSS_ACCONT; ?></option>
											</select>
										</div>							
										 
										 <div class='clearfix'></div>
									</div>	
											 <div  style = 'text-align:Center;margin-left: 22%;' class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
											<button type="button" class="btn btn-primary" ng-disabled = 'nibsaccForm.$invalid' ng-click='nibsaccForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo NIBSS_ACC_AUDIT_BUTTON_QUERY; ?></button>
											<button type="button" class="btn btn-primary"   id="Refresh"><?php echo NIBSS_ACC_AUDIT_BUTTON_REFRESH; ?></button>
										</div>

								 
							</div>		
																	
					<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									 
									<th><?php echo NIBSS_ACC_AUDIT_ID; ?></th>
									<th><?php echo NIBSS_ACC_AUDIT_PAYABLE_DESC; ?></th>
									<th><?php echo NIBSS_ACC_AUDIT_DEBIT; ?></th>
									<th><?php echo NIBSS_ACC_AUDIT_CREDIT; ?></th>
									<th><?php echo NIBSS_ACC_AUDIT_TOTAL; ?></th>
									<th><?php echo NIBSS_ACC_AUDIT_STATUS; ?></th>
									<th><?php echo NIBSS_ACC_AUDIT_PRINT; ?></th>
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in niaccaudit">
									<td>{{ x.id }}</td>
									<td>{{ x.payable_description }}</td>
									<td>{{ x.debit }}</td>
									<td>{{ x.credit }}</td>
									<td>{{ x.total }}</td>
									<td>{{ x.status }}</td>										
									<td><a id={{x.id}} class='print' ng-click='nibsaccForm.$invalid=true;print($index,x.id)' >
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
									</td>
									</tr>
								<tr ng-show="niaccaudit.length==0">
									<td style='text-align:center' colspan='6' >
										<?php echo NO_DATA_FOUND; ?>             
									</td>
								</tr>
							</tbody>
						</table>
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
	////TestTable1();
	//TestTable2();
	//TestTable3();
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