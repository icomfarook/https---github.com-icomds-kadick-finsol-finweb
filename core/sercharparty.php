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
#AddCountryDialogue .table > tbody > tr > td {
	border:none;
}
</style>
<div ng-controller= "serCharParCtrl">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!ratpty"><?php echo SERVICE_CHARGE_PARTY_MAIN_HEADING1; ?></a></li>
			<li><a href="#!ratpty"><?php echo SERVICE_CHARGE_PARTY_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class='row'>
			<input type='button' style='float: right;margin-right: 2%;' class='btn btn-primary' value='<?php echo SERVICE_CHARGE_PARTY_MAIN_CREATE_PARTNER_TYPE; ?>' id='Create' href='#' data-toggle='modal' data-target='#AddserviceChargePartyDialogue'/>
		</div>
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo SERVICE_CHARGE_PARTY_MAIN_HEADING3; ?></span>
				</div>
				
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding">		
                <div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>			
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>							
							<th><?php echo SERVICE_CHARGE_PARTY_MAIN_TABLE_SERVICE_CHARGE_PARTY_NAME; ?></th>
							<th><?php echo SERVICE_CHARGE_PARTY_MAIN_TABLE_ACTIVE; ?></th>
							<th>Party Type</th>
							<th><?php echo SERVICE_CHARGE_PARTY_MAIN_TABLE_EDIT; ?></th>
						</tr>
					</thead>
					<tbody>
					      <tr ng-repeat="x in sechpalist">
							<td>{{ x.name }}</td>
							<td>{{ x.active }}</td>
							<td>{{ x.service_charge_party_type }}</td>
							<td><a id={{x.id}} class='editcountry' ng-click='edit($index,x.id)' data-toggle='modal' data-target='#EditserviceChargePartyDialogue'>
							<button  class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
							</tr>
						</tbody>
					
				</table>
			</div>
		</div>
	</div>
	 <div id='EditserviceChargePartyDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" ng-click='restric()' class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo SERVICE_CHARGE_PARTY_EDIT_HEADING1; ?> -  {{name}}</h2>
					</div>		
					 <div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					 <form action="" method="POST" name='editserviceChargePartyForm' id="EditserviceChargePartyForm">
						<div id='serviceChargePartyBody'  ng-hide='isLoader'>
						<div class='row' style='margin-top:2%'>
							
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12'>
								<label> <?php echo SERVICE_CHARGE_PARTY_EDIT_NAME; ?><span ng-show="editserviceChargePartyForm.partner_type_name.$touched ||editserviceChargePartyForm.partner_type_name.$dirty && editserviceChargePartyForm.partner_type_name.$invalid">
								<span class = 'err' ng-show="editserviceChargePartyForm.name.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input type='text' name='name' required ng-model='name' maxlength='20'  id='name' class='form-control'/>
							</div>									
							
							
						
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12'>
								<label><?php echo SERVICE_CHARGE_PARTY_EDIT_ACTIVE; ?><span ng-show="editserviceChargePartyForm.active.$touched ||editserviceChargePartyForm.active.$dirty && editserviceChargePartyForm.active.$invalid">
								<span class = 'err' ng-show="editserviceChargePartyForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<select ng-model="active" class='form-control' name = 'active' id='Active' required >
                               			<option value='Y'><?php echo SERVICE_CHARGE_PARTY_EDIT_ACTIVE_YES; ?></option>
										<option value='N'><?php echo SERVICE_CHARGE_PARTY_EDIT_ACTIVE_NO; ?></option>
									</select>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12'>
								<label>Party Type<span ng-show="editserviceChargePartyForm.ptype.$touched ||editserviceChargePartyForm.ptype.$dirty && editserviceChargePartyForm.ptype.$invalid">
								<span class = 'err' ng-show="editserviceChargePartyForm.ptype.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<select ng-model="ptype" class='form-control' name = 'ptype' id='ptype' required >
                               			<option value='A'>A - Agent</option>
										<option value='M'>M - Main Account</option>
										<option value='C'>C - Champion</option>
										<option value='O'>O - others</option>
									</select>
							</div>
						</div>
					</div>
	         		<div class='modal-footer'>
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo SERVICE_CHARGE_PARTY_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' ng-click='restric()' data-dismiss='modal' ng-hide='isHide' ><?php echo SERVICE_CHARGE_PARTY_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="editserviceChargePartyForm.$invalid" ng-click="editserviceChargePartyForm.$invalid=true;update(id)" id="Update"><?php echo SERVICE_CHARGE_PARTY_EDIT_BUTTON_UPDATE; ?></button>
					</div>
		</form>	
	</div>
	</div>	
</div></div>
						
	<div id='AddserviceChargePartyDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo SERVICE_CHARGE_PARTY_CREATE_HEADING1;?></h2>
					</div>	
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body' style='margin-top:2%'>
					 <form action="" method="POST" name='addserviceChargePartyForm' id="AddserviceChargePartyForm">
						<div id='serviceChargePartyCreateBody'  ng-hide='isLoader'>
							<div class='row' style='margin-top:2%'>
								
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12'>
								<label> <?php echo SERVICE_CHARGE_PARTY_CREATE_NAME; ?><span ng-show="addserviceChargePartyForm.name.$touched ||addserviceChargePartyForm.name.$dirty && addserviceChargePartyForm.name.$invalid">
								<span class = 'err' ng-show="addserviceChargePartyForm.name.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input type='text' name='name' required ng-model='name' maxlength='20'  id='name' class='form-control'/>
							</div>	
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12'>
								<label><?php echo SERVICE_CHARGE_PARTY_CREATE_ACTIVE; ?><span ng-show="addserviceChargePartyForm.active.$touched ||addserviceChargePartyForm.active.$dirty && addserviceChargePartyForm.active.$invalid">
								<span class = 'err' ng-show="addserviceChargePartyForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<select ng-model="active" class='form-control' name = 'active' id='Active' required >
                               			<option value=''><?php echo SERVICE_CHARGE_PARTY_CREATE_SELECT_ACTIVE; ?></option>
										<option value='Y'><?php echo SERVICE_CHARGE_PARTY_EDIT_ACTIVE_YES; ?></option>
										<option value='N'><?php echo SERVICE_CHARGE_PARTY_EDIT_ACTIVE_NO; ?></option>
									</select>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12'>
								<label>Party Type<span ng-show="addserviceChargePartyForm.ptype.$touched ||addserviceChargePartyForm.ptype.$dirty && addserviceChargePartyForm.ptype.$invalid">
								<span class = 'err' ng-show="addserviceChargePartyForm.ptype.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<select ng-model="ptype" class='form-control' name = 'ptype' id='ptype' required >
									<option value=''>-- Select Party Type --</option>
                               			<option value='A'>A - Agent</option>
										<option value='M'>M - Main Account</option>
										<option value='C'>C - Champion</option>
										<option value='O'>O - others</option>
									</select>
							</div>
						</div></div>
						  </form>	
						</div>
			<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo SERVICE_CHARGE_PARTY_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo SERVICE_CHARGE_PARTY_CREATE_BUTTON_CANCEL; ?></a>
					<button type="button" class="btn btn-primary" ng-click='addserviceChargePartyForm.$invalid=true;create()' ng-hide='isHide' ng-disabled = "addserviceChargePartyForm.$invalid"  id="Create"><?php echo SERVICE_CHARGE_PARTY_CREATE_BUTTON_CREATE; ?></button>
			</div>
	</div>	
</div>
</div>
</div></div>
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	TestTable1();
	TestTable2();
	TestTable3();
	//LoadSelect2Script(MakeSelect2);
}

$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	//WinMove();

	$("#EditserviceChargePartyDialogue, #AddserviceChargePartyDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	
});
</script>

 
 