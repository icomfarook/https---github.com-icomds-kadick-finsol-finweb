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
<div ng-controller= "serChargGrpCtrl">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!ratgrp"><?php echo SERVICE_CHARGE_GROUP_MAIN_HEADING1; ?></a></li>
			<li><a href="#!ratgrp"><?php echo SERVICE_CHARGE_GROUP_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class='row'>
			<input type='button' style='float: right;margin-right: 2%;' class='btn btn-primary' value='<?php echo SERVICE_CHARGE_GROUP_MAIN_CREATE_PARTNER; ?>' id='Create' href='#' data-toggle='modal' data-target='#AddserCharGrpDialogue'/>
		</div>
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo SERVICE_CHARGE_GROUP_MAIN_HEADING3; ?></span>
				</div>
				
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding">		
                <div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>			
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>
							<th><?php echo SERVICE_CHARGE_GROUP_MAIN_TABLE_SERVICE_CHARGE_GROUP_NAME; ?></th>
							<th><?php echo SERVICE_CHARGE_GROUP_MAIN_TABLE_SERVICE_FEATURE; ?></th>
							<th><?php echo SERVICE_CHARGE_GROUP_MAIN_TABLE_COUNTRY; ?></th>
							<th><?php echo SERVICE_CHARGE_GROUP_MAIN_TABLE_STATE; ?></th>
							<th><?php echo SERVICE_CHARGE_GROUP_MAIN_TABLE_LOCAL_GOVT_LIST; ?></th>
							<th><?php echo SERVICE_CHARGE_GROUP_MAIN_TABLE_EDIT; ?></th>
						</tr>
					</thead>
					<tbody>
					      <tr ng-repeat="x in servichgrplist">
							<td>{{ x.name }}</td>							
							<td>{{ x.serfea }}</td>
							<td>{{ x.country }}</td>
							<td>{{ x.state }}</td>
							<td>{{ x.locgvt }}</td>
							<td><a id={{x.id}} class='editcountry' ng-click='edit($index,x.id)' data-toggle='modal' data-target='#EditserCharGrpDialogue'>
							<button  class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
							</tr>
						</tbody>
					
				</table>
			</div>
		</div>
	</div>
	 <div id='EditserCharGrpDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo SERVICE_CHARGE_GROUP_EDIT_HEADING1; ?>  - {{name}}</h2>
					</div>			
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					 <form action="" method="POST" name='editserCharGrpForm' id="EditserCharGrpForm">
						<div id='serCharGrpBody'  ng-hide='isLoader'>
						<div class='row' style='margin-top:2%'>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label> <?php echo SERVICE_CHARGE_GROUP_EDIT_NAME; ?><span class='spanre'>*</span><span ng-show="editserCharGrpForm.name.$touched ||editserCharGrpForm.name.$dirty && editserCharGrpForm.name.$invalid">
								<span class = 'err' ng-show="editserCharGrpForm.name.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input type='text' maxlength='20' ng-trim="false"   restrict-field="name" name='name' required ng-model='name'  id='focusMe' class='form-control'/>
							</div>
							
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label> <?php echo SERVICE_CHARGE_GROUP_EDIT_SERVICE_FEATURE; ?><span class='spanre'>*</span><span ng-show="editserCharGrpForm.serfea.$touched ||editserCharGrpForm.serfea.$dirty && editserCharGrpForm.serfea.$invalid">
								<span class = 'err' ng-show="editserCharGrpForm.serfea.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="serfea" class='form-control' name = 'serfea' id='serfea' required>											
									<option value=''><?php echo SERVICE_CHARGE_GROUP_EDIT_SELECT_SERVICE_FEATURE; ?></option>
									<option ng-repeat="ser in servfeas" value="{{ser.id}}">{{ser.name}}</option>
								</select>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label> <?php echo SERVICE_CHARGE_GROUP_EDIT_SERVICE_CHARGE_GROUP_COUNTRY; ?><span class='spanre'>*</span><span ng-show="editserCharGrpForm.country.$touched ||editserCharGrpForm.country.$dirty && editserCharGrpForm.country.$invalid">
								<span class = 'err' ng-show="editserCharGrpForm.country.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="country" ng-change='countrychange(this.country)' class='form-control' name = 'country' id='country' required>											
									<option value=''><?php echo SERVICE_CHARGE_GROUP_EDIT_SELECT_COUNTRY; ?></option>
									<option ng-repeat="country in countrys" value="{{country.id}}">{{country.description}}</option>
								</select>
							</div>
							
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label><?php echo SERVICE_CHARGE_GROUP_EDIT_SERVICE_CHARGE_GROUP_STATE; ?><span class='spanre'>*</span><span ng-show="editserCharGrpForm.state.$touched ||editserCharGrpForm.state.$dirty && editserCharGrpForm.state.$invalid">
								<span class = 'err' ng-show="editserCharGrpForm.state.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="state" ng-change='statechange(this.state)' class='form-control' name = 'state' id='state' required>											
									<option value=''><?php echo SERVICE_CHARGE_GROUP_EDIT_SELECT_STATE; ?></option>
									<option ng-repeat="state in states" value="{{state.id}}">{{state.name}}</option>
								</select>
							</div>	
							
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label><?php echo SERVICE_CHARGE_GROUP_EDIT_SERVICE_CHARGE_GROUP_LOCAL_GOV_LIST; ?><span ng-show="editserCharGrpForm.locgvt.$touched ||editserCharGrpForm.locgvt.$dirty && editserCharGrpForm.locgvt.$invalid">
								<span class = 'err' ng-show="editserCharGrpForm.locgvt.$error.required"><?php echo REQUIRED;?></span></span></label></label>
								<select ng-model="locgvt"  class='form-control' name = 'locgvt' id='locgvt' >											
									<option value=''><?php echo SERVICE_CHARGE_GROUP_EDIT_SELECT_LOCAL_GOVT; ?></option>
									<option ng-repeat="localgvt in localgvts" value="{{localgvt.id}}">{{localgvt.name}}</option>
								</select>
							</div>	
																
							<div>
						<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label><?php echo SERVICE_CHARGE_GROUP_CREATE_PARTY_COUNT; ?><span class='spanre'>*</span><span ng-show="editserCharGrpForm.pcount.$dirty && editserCharGrpForm.pcount.$invalid"><span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
								 <input  numbers-only type='text' name='pcount' required ng-model='pcount'  ng-trim="false"  restrict-field="pcount" maxlength='10' id='pcount' class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label><?php echo SERVICE_CHARGE_GROUP_CREATE_ACTIVE; ?><span class='spanre'>*</span><span ng-show="editserCharGrpForm.active.$touched ||editserCharGrpForm.active.$dirty && editserCharGrpForm.active.$invalid">
								<span class = 'err' ng-show="editserCharGrpForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<select ng-model="active" class='form-control' name = 'active' id='Active' required >
									    <option value=''><?php echo SERVICE_CHARGE_GROUP_CREATE_SELECT_ACTIVE; ?></option>
                               			<option value='Y'><?php echo SERVICE_CHARGE_GROUP_CREATE_ACTIVE_YES; ?></option>
										<option value='N'><?php echo SERVICE_CHARGE_GROUP_CREATE_ACTIVE_NO; ?></option>
									</select>
							</div>
							</div>
						</div>
						
					</div>
									<div class='clearfix'></div>
						</form>	
	</div>
	         		<div class='modal-footer'>
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo SERVICE_CHARGE_GROUP_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo SERVICE_CHARGE_GROUP_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="editserCharGrpForm.$invalid" ng-click="editserCharGrpForm.$invalid=true;update(id)" id="Update"><?php echo SERVICE_CHARGE_GROUP_EDIT_BUTTON_UPDATE; ?></button>
					</div>
	
	</div>	
</div></div>
						
	<div id='AddserCharGrpDialogue' class='modal fade' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo SERVICE_CHARGE_GROUP_CREATE_HEADING1;?></h2>
					</div>	
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body' >
					 <form action="" method="POST" name='createserCharGrpForm' id="AddserCharGrpForm">
						<div id='serCharGrpCreateBody'  ng-hide='isLoader'>
							<div class='row' style='margin-top:2%'>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label> <?php echo SERVICE_CHARGE_GROUP_CREATE_NAME; ?><span class='spanre'>*</span><span ng-show="createserCharGrpForm.name.$touched ||createserCharGrpForm.name.$dirty && createserCharGrpForm.name.$invalid">
								<span class = 'err' ng-show="createserCharGrpForm.name.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input type='text'   ng-trim="false"  restrict-field="name" maxlength='20' name='name' required ng-model='name'  id='focusMe' ' class='form-control' autofocus />
							</div>
							
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label> <?php echo SERVICE_CHARGE_GROUP_CREATE_SERVICE_FEATURE; ?><span class='spanre'>*</span><span ng-show="createserCharGrpForm.serfea.$touched ||createserCharGrpForm.serfea.$dirty && createserCharGrpForm.serfea.$invalid">
								<span class = 'err' ng-show="createserCharGrpForm.serfea.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="serfea" class='form-control' name = 'serfea' id='serfea' required>											
									<option value=''><?php echo SERVICE_CHARGE_GROUP_CREATE_SELECT_SERVICE_FEATURE; ?></option>
									<option ng-repeat="ser in servfeas" value="{{ser.id}}">{{ser.name}}</option>
								</select>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label> <?php echo SERVICE_CHARGE_GROUP_CREATE_SERVICE_CHARGE_GROUP_COUNTRY; ?><span class='spanre'>*</span><span ng-show="createserCharGrpForm.country.$touched ||createserCharGrpForm.country.$dirty && createserCharGrpForm.country.$invalid">
								<span class = 'err' ng-show="createserCharGrpForm.country.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="country" ng-change='countrychange(this.country)' class='form-control' name = 'country' id='country' required>											
									<option value=''><?php echo SERVICE_CHARGE_GROUP_CREATE_SELECT_COUNTRY; ?></option>
									<option ng-repeat="country in countrys" value="{{country.id}}">{{country.description}}</option>
								</select>
							</div>
							
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label><?php echo SERVICE_CHARGE_GROUP_CREATE_SERVICE_CHARGE_GROUP_STATE; ?><span class='spanre'>*</span><span ng-show="createserCharGrpForm.state.$touched ||createserCharGrpForm.state.$dirty && createserCharGrpForm.state.$invalid">
								<span class = 'err' ng-show="createserCharGrpForm.state.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="state" ng-change='statechange(this.state)' class='form-control' name = 'state' id='state' required>											
									<option value=''><?php echo SERVICE_CHARGE_GROUP_CREATE_SELECT_STATE; ?></option>
									<option ng-repeat="state in states" value="{{state.id}}">{{state.name}}</option>
								</select>
							</div>	
							
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label><?php echo SERVICE_CHARGE_GROUP_CREATE_SERVICE_CHARGE_GROUP_LOCAL_GOV_LIST; ?><span ng-show="createserCharGrpForm.locgvt.$touched ||createserCharGrpForm.locgvt.$dirty && createserCharGrpForm.locgvt.$invalid">
								<span  ng-show="createserCharGrpForm.locgvt.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="locgvt"  class='form-control' name = 'locgvt' id='locgvt' >											
									<option value=''><?php echo SERVICE_CHARGE_GROUP_CREATE_SELECT_LOCAL_GOVT; ?></option>
									<option ng-repeat="localgvt in localgvts" value="{{localgvt.id}}">{{localgvt.name}}</option>
								</select>
							</div>	
																
							<div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label><?php echo SERVICE_CHARGE_GROUP_CREATE_PARTY_COUNT; ?><span class='spanre'>*</span><span ng-show="createserCharGrpForm.pcount.$dirty && createserCharGrpForm.pcount.$invalid"><span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
								 <input  numbers-only type='text' name='pcount' required ng-model='pcount'  ng-trim="false"  restrict-field="pcount" maxlength='10' id='pcount' class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label><?php echo SERVICE_CHARGE_GROUP_CREATE_ACTIVE; ?><span class='spanre'>*</span><span ng-show="createserCharGrpForm.active.$touched ||createserCharGrpForm.active.$dirty && createserCharGrpForm.active.$invalid">
								<span class = 'err' ng-show="createserCharGrpForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<select ng-model="active" class='form-control' name = 'active' id='Active' required >
									    <option value=''><?php echo SERVICE_CHARGE_GROUP_CREATE_SELECT_ACTIVE; ?></option>
                               			<option value='Y'><?php echo SERVICE_CHARGE_GROUP_CREATE_ACTIVE_YES; ?></option>
										<option value='N'><?php echo SERVICE_CHARGE_GROUP_CREATE_ACTIVE_NO; ?></option>
									</select>
							</div>
							</div>
							
						
						
							
						</div></div>
						<div class='clearfix'></div>
						  </form>	
						</div>
			<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo SERVICE_CHARGE_GROUP_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo SERVICE_CHARGE_GROUP_CREATE_BUTTON_CANCEL; ?></a>
					<button type="button" class="btn btn-primary" ng-click='createserCharGrpForm.$invalid=true;create()' ng-hide='isHide' ng-disabled = "createserCharGrpForm.$invalid"  id="Create"><?php echo SERVICE_CHARGE_GROUP_CREATE_BUTTON_CREATE; ?></button>
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
	//LoadSelect2Script();
}
$(document).ready(function() {
	var input = document.getElementById("name").focus();
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	//WinMove();

	$("#EditserCharGrpDialogue, #AddserCharGrpDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	$("#AddserCharGrpDialogue, #EditserCharGrpDialogue").on('shown.bs.modal', function(){
        $(this).find('#focusMe').focus();
    });
	  /* window.alert = function() {}; alert = function() {}; */
});
</script>

 