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
<div ng-controller= "parTypeCtrl">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!ptntyp"><?php echo PARTNER_TYPE_MAIN_HEADING1; ?></a></li>
			<li><a href="#!ptntyp"><?php echo PARTNER_TYPE_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class='row'>
			<input type='button' style='float: right;margin-right: 2%;' class='btn btn-primary' value='<?php echo PARTNER_TYPE_MAIN_CREATE_PARTNER_TYPE; ?>' id='Create' href='#' data-toggle='modal' data-target='#AddPartnerTypeDialogue'/>
		</div>
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo PARTNER_TYPE_MAIN_HEADING3; ?></span>
				</div>
				
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding">		
                <div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>			
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>							
							<th><?php echo PARTNER_TYPE_MAIN_TABLE_PARTNER_TYPE_NAME; ?></th>
							<th><?php echo PARTNER_TYPE_MAIN_TABLE_ACTIVE; ?></th>
							<th><?php echo PARTNER_TYPE_MAIN_TABLE_EDIT; ?></th>
						</tr>
					</thead>
					<tbody>
					      <tr ng-repeat="x in partnertypelist">
							<td>{{ x.ams_partner_type_name }}</td>
							<td>{{ x.active }}</td>
							<td><a id={{x.id}} class='editcountry' ng-click='edit($index,x.id)' data-toggle='modal' data-target='#EditPartnerTypeDialogue'>
							<button  class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
							</tr>
						</tbody>
					
				</table>
			</div>
		</div>
	</div>
	 <div id='EditPartnerTypeDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo PARTNER_TYPE_EDIT_HEADING1; ?> -  {{partner_type_name}}</h2>
					</div>	
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					<form action="" method="POST" name='editPartnerTypeForm' id="EditPartnerTypeForm">
					<div id='PartnerTypeBody'  ng-hide='isLoader'>
						<div class='row' style='margin-top:2%'>
							
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
								<label> <?php echo PARTNER_TYPE_EDIT_NAME; ?><span class='spanre'>*</span><span ng-show="editPartnerTypeForm.outletname.$touched ||editPartnerTypeForm.outletname.$dirty && editPartnerTypeForm.outletname.$invalid">
								<span class = 'err' ng-show="editPartnerTypeForm.partner_type_name.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input type='text' maxlength='10' spl-char-not restrict-field="partner_type_name" name='partner_type_name' required ng-model='partner_type_name'  id='partner_type_name' class='form-control'/>
							</div>									
							
							
						
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
								<label><?php echo PARTNER_TYPE_EDIT_ACTIVE; ?><span ng-show="editPartnerTypeForm.active.$touched ||editPartnerTypeForm.active.$dirty && editPartnerTypeForm.active.$invalid">
								<span class = 'err' ng-show="editPartnerTypeForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<select ng-model="active" class='form-control' name = 'active' id='Active' required >
                               			<option value='Y'><?php echo PARTNER_TYPE_EDIT_ACTIVE_YES; ?></option>
										<option value='N'><?php echo PARTNER_TYPE_EDIT_ACTIVE_NO; ?></option>
									</select>
							</div>
						</div>
					</div>
	         		<div class='modal-footer'>
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo PARTNER_TYPE_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo PARTNER_TYPE_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="editPartnerTypeForm.$invalid" ng-click="editPartnerTypeForm.$invalid=true;update(id)" id="Update"><?php echo PARTNER_TYPE_EDIT_BUTTON_UPDATE; ?></button>
					</div>
		</form>	
	</div>
	</div>	
</div></div>
						
	<div id='AddPartnerTypeDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo PARTNER_TYPE_CREATE_HEADING1;?></h2>
					</div>	
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body' style='margin-top:2%'>
					 <form action="" method="POST" name='addPartnerTypeForm' id="AddPartnerTypeForm">
						<div id='PartnerTypeCreateBody'  ng-hide='isLoader'>
							<div class='row' style='margin-top:2%'>
									<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
								<label> <?php echo PARTNER_TYPE_CREATE_NAME; ?><span class='spanre'>*</span><span ng-show="addPartnerTypeForm.partner_type_name.$touched ||addPartnerTypeForm.partner_type_name.$dirty && addPartnerTypeForm.partner_type_name.$invalid">
								<span class = 'err' ng-show="addPartnerTypeForm.partner_type_name.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input type='text' name='partner_type_name' maxlength='10' spl-char-not restrict-field="partner_type_name" ng-trim="false"   required ng-model='partner_type_name'  id='partner_type_name' class='form-control'/>
							</div>	
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
								<label><?php echo PARTNER_TYPE_CREATE_ACTIVE; ?><span ng-show="addPartnerTypeForm.active.$touched ||addPartnerTypeForm.active.$dirty && addPartnerTypeForm.active.$invalid">
								<span class = 'err' ng-show="addPartnerTypeForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<select ng-model="active" class='form-control' name = 'active' id='Active' required >
                               			<option value=''><?php echo PARTNER_TYPE_CREATE_SELECT_ACTIVE; ?></option>
										<option value='Y'><?php echo PARTNER_TYPE_EDIT_ACTIVE_YES; ?></option>
										<option value='N'><?php echo PARTNER_TYPE_EDIT_ACTIVE_NO; ?></option>
									</select>
							</div>
						</div></div>
						</form>	</div>
			<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo PARTNER_TYPE_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo PARTNER_TYPE_CREATE_BUTTON_CANCEL; ?></a>
					<button type="button" class="btn btn-primary" ng-click='addPartnerTypeForm.$invalid=true;create()' ng-hide='isHide' ng-disabled = "addPartnerTypeForm.$invalid"  id="Create"><?php echo PARTNER_TYPE_CREATE_BUTTON_CREATE; ?></button>
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

	$("#EditPartnerTypeDialogue, #AddPartnerTypeDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	/* window.alert = function() {};
     alert = function() {}; */
});
</script>

 
 