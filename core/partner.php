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
<div ng-controller= "parCtrl">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!ptnptn"><?php echo PARTNER_MAIN_HEADING1; ?></a></li>
			<li><a href="#!ptnptn"><?php echo PARTNER_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class='row'>
			<input type='button' style='float: right;margin-right: 2%;' class='btn btn-primary' value='<?php echo PARTNER_MAIN_CREATE_PARTNER; ?>' id='Create' href='#' data-toggle='modal' data-target='#AddPartnerDialogue'/>
		</div>
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo PARTNER_MAIN_HEADING3; ?></span>
				</div>
				
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding">		
                <div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>			
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>
							<th><?php echo PARTNER_MAIN_TABLE_PARTNER_TYPE; ?></th>
      						<th><?php echo PARTNER_MAIN_TABLE_PARTNER_NAME; ?></th>
							<th><?php echo PARTNER_MAIN_TABLE_LOCAL_GOVT_LIST; ?></th>
							<th><?php echo PARTNER_MAIN_TABLE_STATE; ?></th>
							<th><?php echo PARTNER_MAIN_TABLE_COUNTRY; ?></th>
							<th><?php echo PARTNER_MAIN_TABLE_EDIT; ?></th>
						</tr>
					</thead>
					<tbody>
					      <tr ng-repeat="x in partnerlist">
							<td>{{ x.type }}</td>
						 	<td>{{ x.name }}</td>
							<td>{{ x.lname }}</td>
							<td>{{ x.state }}</td>
							<td>{{ x.country }}</td>
							<td><a id={{x.id}} class='editcountry' ng-click='edit($index,x.id)' data-toggle='modal' data-target='#EditPartnerDialogue'>
							<button  class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
							</tr>
						</tbody>
					
				</table>
			</div>
		</div>
	</div>
	 <div id='EditPartnerDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo PARTNER_EDIT_HEADING1; ?> {{code}}</h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					<form action="" method="POST" name='editPartnerForm' id="EditPartnerForm">
						<div id='PartnerBody'  ng-hide='isLoader'>
						<div class='row' style='margin-top:2%'>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
								<label> <?php echo PARTNER_EDIT_PARTNER_TYPE; ?><span ng-show="editPartnerForm.partner_type_id.$touched ||editPartnerForm.partner_type_id.$dirty && editPartnerForm.partner_type_id.$invalid">
								<span class = 'err' ng-show="editPartnerForm.partner_type_id.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="partner_type_id" class='form-control' name = 'partner_type_id' id='partner_type_id' required>											
									<option value=''><?php echo PARTNER_EDIT_SELECT_PARTNER; ?></option>
									<option ng-repeat="partner in partnertype" value="{{partner.id}}">{{partner.name}}</option>
								</select>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
								<label> <?php echo PARTNER_EDIT_PARTNER_COUNTRY; ?><span ng-show="editPartnerForm.partner_country_id.$touched ||editPartnerForm.partner_country_id.$dirty && editPartnerForm.partner_country_id.$invalid">
								<span class = 'err' ng-show="editPartnerForm.partner_country_id.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="partner_country_id" ng-change='countrychange(this.partner_country_id)' class='form-control' name = 'partner_country_id' id='partner_country_id' required>											
									<option value=''><?php echo PARTNER_EDIT_SELECT_COUNTRY; ?></option>
									<option ng-repeat="country in countrys" value="{{country.id}}">{{country.description}}</option>
								</select>
							</div>
							
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PARTNER_EDIT_PARTNER_STATE; ?><span class='spanre'>*</span><span ng-show="editPartnerForm.partner_state_id.$touched ||editPartnerForm.partner_state_id.$dirty && editPartnerForm.partner_state_id.$invalid">
								<span class = 'err' ng-show="editPartnerForm.partner_state_id.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="partner_state_id" ng-change='statechange(this.partner_state_id)' class='form-control' name = 'partner_state_id' id='partner_state_id' required>											
									<option value=''><?php echo PARTNER_EDIT_SELECT_STATE; ?></option>
									<option ng-repeat="state in states" value="{{state.id}}">{{state.name}}</option>
								</select>
							</div>	
							
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PARTNER_EDIT_PARTNER_LOCAL_GOV_LIST; ?><span class='spanre'>*</span><span ng-show="editPartnerForm.partner_local_govt_id.$touched ||editPartnerForm.partner_local_govt_id.$dirty && editPartnerForm.partner_local_govt_id.$invalid">
								<span class = 'err' ng-show="editPartnerForm.partner_local_govt_id.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="localgovt"  class='form-control' name = 'partner_local_govt_id' id='partner_local_govt_id' required>											
									<option value=''><?php echo PARTNER_EDIT_SELECT_LOCAL_GOVT; ?></option>
									<option ng-repeat="localgvt in localgvts" value="{{localgvt.id}}">{{localgvt.name}}</option>
								</select>
							</div>
								
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
								<label> <?php echo PARTNER_EDIT_NAME; ?><span class='spanre'>*</span><span ng-show="editPartnerForm.partner_name.$touched ||editPartnerForm.partner_name.$dirty && editPartnerForm.partner_name.$invalid">
								<span class = 'err' ng-show="editPartnerForm.outletname.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input type='text' name='partner_name' ng-trim="false" maxlength="30" spl-char-not restrict-field="partner_name"   required ng-model='partner_name'  id='partner_name' class='form-control'/>
							</div>									
							
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
								<label><?php echo PARTNER_EDIT_ADDRESS; ?><span ng-show="editPartnerForm.partner_address.$touched ||editPartnerForm.partner_address.$dirty && editPartnerForm.partner_address.$invalid">
								<span class = 'err' ng-show="editPartnerForm.partner_address.$error.required"><?php echo REQUIRED;?></span></span></label>
								 <textarea  name='partner_address' required ng-model='partner_address' maxlength='50' id='partner_address' class='form-control'/>	
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PARTNER_EDIT_BANK_MASTER; ?><span class='spanre'>*</span><span ng-show="editPartnerForm.bankmaster.$touched ||editPartnerForm.bankmaster.$dirty && editPartnerForm.bankmaster.$invalid">
								<span class = 'err' ng-show="editPartnerForm.bankmaster.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="bankmaster"  class='form-control' name = 'bankmaster' id='bankmaster' required>											
									<option value=""><?php echo PARTNER_EDIT_SELECT_BANK_MASTER; ?></option>
									<option ng-repeat="master in bankmasterss" value="{{master.id}}">{{master.name}}</option>
								</select>
							</div>	
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
								<label><?php echo PARTNER_EDIT_ACTIVE; ?><span ng-show="editPartnerForm.active.$touched ||editPartnerForm.active.$dirty && editPartnerForm.active.$invalid">
								<span class = 'err' ng-show="editPartnerForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<select ng-model="active" class='form-control' name = 'active' id='Active' required >
                               			<option value='Y'><?php echo PARTNER_EDIT_ACTIVE_YES; ?></option>
										<option value='N'><?php echo PARTNER_EDIT_ACTIVE_NO; ?></option>
									</select>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
								<label><?php echo PARTNER_EDIT_START_DATE; ?><span ng-show="editPartnerForm.start_date.$touched ||editPartnerForm.start_date.$dirty && editPartnerForm.start_date.$invalid">
								<span class = 'err' ng-show="editPartnerForm.start_date.$error.required"><?php echo REQUIRED;?></span></span></label>
								 <input type='date' name='start_date' value='{{startdate}}' required ng-model='startdate' id='startdate' class='form-control'/>	
							</div>
							
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
								<label><?php echo PARTNER_EDIT_END_DATE; ?><span ng-show="editPartnerForm.end_date.$touched ||editPartnerForm.end_date.$dirty && editPartnerForm.end_date.$invalid">
								<span class = 'err' ng-show="editPartnerForm.end_date.$error.required"><?php echo REQUIRED;?></span></span></label>
								 <input type='date' name='end_date' value='{{enddate}}' required ng-model='enddate' id='enddate' class='form-control'/>	
							</div>
						
							
						</div>
					</div>
	         		<div class='modal-footer'>
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo PARTNER_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo PARTNER_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="editPartnerForm.$invalid" ng-click="editPartnerForm.$invalid=true;update(id)" id="Update"><?php echo PARTNER_EDIT_BUTTON_UPDATE; ?></button>
					</div>
		</form>	
	</div>
	</div>	
</div></div>
						
	<div id='AddPartnerDialogue' class='modal fade' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo PARTNER_CREATE_HEADING1;?></h2>
					</div>	
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body' style='margin-top:2%'>
				   	  <form action="" method="POST" name='addPartnerForm' id="AddPartnerForm">
						<div id='PartnerCreateBody'  ng-hide='isLoader'>
							<div class='row' style='margin-top:2%'>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
								<label> <?php echo PARTNER_CREATE_PARTNER_TYPE; ?><span class='spanre'>*</span><span ng-show="addPartnerForm.partner_type_id.$touched ||addPartnerForm.partner_type_id.$dirty && addPartnerForm.partner_type_id.$invalid">
								<span class = 'err' ng-show="addPartnerForm.partner_type_id.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="partner_type_id" class='form-control' name = 'partner_type_id' id='partner_type_id' required>											
									<option value=''><?php echo PARTNER_CREATE_SELECT_PARTNER; ?></option>
									<option ng-repeat="partner in partnertype" value="{{partner.id}}">{{partner.name}}</option>
								</select>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
								<label> <?php echo PARTNER_CREATE_PARTNER_COUNTRY; ?><span ng-show="addPartnerForm.partner_country_id.$touched ||addPartnerForm.partner_country_id.$dirty && addPartnerForm.partner_country_id.$invalid">
								<span class = 'err' ng-show="addPartnerForm.partner_country_id.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="partner_country_id" ng-change='countrychange(this.partner_country_id)' class='form-control' name = 'partner_country_id' id='partner_country_id' required>											
									<option value=''><?php echo PARTNER_CREATE_SELECT_COUNTRY; ?></option>
									<option ng-repeat="country in countrys" value="{{country.id}}">{{country.description}}</option>
								</select>
							</div>
							
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PARTNER_CREATE_PARTNER_STATE; ?><span class='spanre'>*</span><span ng-show="addPartnerForm.partner_state_id.$touched ||addPartnerForm.partner_state_id.$dirty && addPartnerForm.partner_state_id.$invalid">
								<span class = 'err' ng-show="addPartnerForm.partner_state_id.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="partner_state_id" ng-change='statechange(this.partner_state_id)' class='form-control' name = 'partner_state_id' id='partner_state_id' required>											
									<option value=''><?php echo PARTNER_CREATE_SELECT_STATE; ?></option>
									<option ng-repeat="state in states" value="{{state.id}}">{{state.name}}</option>
								</select>
							</div>	
							
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PARTNER_CREATE_PARTNER_LOCAL_GOV_LIST; ?><span class='spanre'>*</span><span ng-show="addPartnerForm.partner_local_govt_id.$touched ||addPartnerForm.partner_local_govt_id.$dirty && addPartnerForm.partner_local_govt_id.$invalid">
								<span class = 'err' ng-show="addPartnerForm.partner_local_govt_id.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="partner_local_govt_id"  class='form-control' name = 'partner_local_govt_id' id='partner_local_govt_id' required>											
									<option value=''><?php echo PARTNER_CREATE_SELECT_LOCAL_GOVT; ?></option>
									<option ng-repeat="localgvt in localgvts" value="{{localgvt.id}}">{{localgvt.name}}</option>
								</select>
							</div>	
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
								<label> <?php echo PARTNER_CREATE_NAME; ?><span ng-show="addPartnerForm.partner_name.$touched ||addPartnerForm.partner_name.$dirty && addPartnerForm.partner_name.$invalid">
								<span class = 'err' ng-show="addPartnerForm.partner_name.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input type='text' name='partner_name' required ng-trim="false"  spl-char-not restrict-field="partner_name"  ng-model='partner_name' maxlength="30" id='partner_name' class='form-control'/>
							</div>									
							
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
								<label><?php echo PARTNER_CREATE_ADDRESS; ?><span ng-show="addPartnerForm.partner_address.$touched ||addPartnerForm.partner_address.$dirty && addPartnerForm.partner_address.$invalid">
								<span class = 'err' ng-show="addPartnerForm.partner_address.$error.required"><?php echo REQUIRED;?></span></span></label>
								 <textarea  name='partner_address' required ng-model='partner_address' maxlength='50' id='partner_address' class='form-control'/>	
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PARTNER_CREATE_BANK_MASTER; ?><span class='spanre'>*</span><span ng-show="addPartnerForm.bankmaster.$touched ||addPartnerForm.bankmaster.$dirty && addPartnerForm.bankmaster.$invalid">
								<span class = 'err' ng-show="addPartnerForm.bankmaster.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="bankmaster"  class='form-control' name = 'bankmaster' id='bankmaster' required>											
									<option value=''><?php echo PARTNER_CREATE_SELECT_BANK_MASTER; ?></option>
									<option ng-repeat="master in bankmasterss" value="{{master.id}}">{{master.name}}</option>
								</select>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
								<label><?php echo PARTNER_CREATE_ACTIVE; ?><span ng-show="addPartnerForm.active.$touched ||addPartnerForm.active.$dirty && addPartnerForm.active.$invalid">
								<span class = 'err' ng-show="addPartnerForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<select ng-model="active" class='form-control' name = 'active' id='Active' required >
                               			<option value=''><?php echo PARTNER_CREATE_SELECT_ACTIVE; ?></option>
										<option value='Y'><?php echo PARTNER_CREATE_ACTIVE_YES; ?></option>
										<option value='N'><?php echo PARTNER_CREATE_ACTIVE_NO; ?></option>
									</select>
							</div>							
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
								<label><?php echo PARTNER_CREATE_startdate; ?><span ng-show="addPartnerForm.startdate.$touched ||addPartnerForm.startdate.$dirty && addPartnerForm.startdate.$invalid">
								<span class = 'err' ng-show="addPartnerForm.startdate.$error.required"><?php echo REQUIRED;?></span></span></label>
								 <input type='date' name='startdate' required ng-model='startdate' id='startdate' class='form-control'/>	
							</div>
							
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
								<label><?php echo PARTNER_CREATE_enddate; ?><span ng-show="addPartnerForm.enddate.$touched ||addPartnerForm.enddate.$dirty && addPartnerForm.enddate.$invalid">
								<span class = 'err' ng-show="addPartnerForm.enddate.$error.required"><?php echo REQUIRED;?></span></span></label>
								 <input type='date' name='enddate' required ng-model='enddate' id='enddate' class='form-control'/>	
							</div>
						
						</div></div>
						</form>	
						</div>
			        <div class='modal-footer'>
			 		<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo PARTNER_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo PARTNER_CREATE_BUTTON_CANCEL; ?></a>
					<button type="button" class="btn btn-primary" ng-click='addPartnerForm.$invalid=true;create()' ng-hide='isHide' ng-disabled = "addPartnerForm.$invalid"  id="Create"><?php echo PARTNER_CREATE_BUTTON_CREATE; ?></button>
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
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	//WinMove();

	$("#EditPartnerDialogue, #AddPartnerDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	/* window.alert = function() {};
     alert = function() {};
	 */
});
</script>

 