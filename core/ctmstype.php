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
#AddStateDialogue .table > tbody > tr > td {
	border:none;
}
</style>
<div ng-controller='CtmsTypeCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!ctmstype"><?php echo STATE_MAIN_HEADING1; ?></a></li>
			<li><a href="#!ctmstype">CTMS Type - State</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class='row'>
			<input type='button' style='float: right;margin-right: 2%;' class='btn btn-primary' value='Create CTMS State' id='Create'  data-toggle='modal' href='#' data-target='#AddStateDialogue'/>
		
		</div>
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>CTMS Type - State</span>
				</div>
				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding">			
			<div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>
							<th>Id</th>
      						<th>State</th>
							<th>Local Government</th>
							<th>PTSP Type</th>
							<th>Active</th>
							<th>Edit</th>
						</tr>
					</thead>
					<tbody>
					 <tr ng-repeat="x in ctmsstatelist">
						 	<td>{{ x.id }}</td>
							<td>{{ x.state }}</td>
							<td>{{ x.local }}</td>
							<td>{{ x.ptsp_type }}</td>
							<td>{{ x.active }}</td>
							<td><a id={{x.id}} class='editpro' ng-click='edit($index,x.id)' data-toggle='modal' data-target='#EditstateDialogue'>
								<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
						</tr>
					</tbody>
				
				</table>
			</div>
		</div>
	</div>
		<div id='AddStateDialogue' class='modal ' role='dialog' data-backdrop="static" data-keyboard="false"  ng-init = "countrychange(<?php echo ADMIN_COUNTRY_ID; ?>)">
	 <div class="modal-dialog modal-md">
		<div class="modal-content">
			
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 style='text-align:center'>CTMS Type-State Create</h2>
				</div>		
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' style="text-align:center" src="../common/img/gif2.gif" /></div></div>
				<div class='modal-body'>
				<form action="" method="POST" name='addctmsstateForm' id="AddctmsstateForm">
				 <div  id='CtmsstateCreateBody'  ng-hide='isLoader'>
				 <div class='row' style='padding:0px 15px'>						
					<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label>State<span class='spanre'>*</span><span ng-show="addctmsstateForm.state_id.$touched ||addctmsstateForm.state_id.$dirty && addctmsstateForm.state_id.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="addctmsstateForm.state_id.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-disabled='isInputDisabled' ng-model="state_id"class='form-control' name = 'state' id='state' required>											
									<option value=''><?php echo APPLICATION_ENTRY_SELECT_STATE; ?></option>
									<option ng-repeat="state in states" value="{{state.id}}">{{state.name}}</option>
								</select>
							</div>	
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
								<label>Local Goverment<span ng-show="editINFOForm.local_govt_id.$touched ||editINFOForm.local_govt_id.$dirty && editINFOForm.local_govt_id.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="editINFOForm.local_govt_id.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select id='local_govt_id'  ng-model='local_govt_id'   class='form-control' name='local_govt_id'  >
									<option value=''><?php echo APPLICATION_ENTRY_SELECT_LOCAL_GOVT; ?></option>
									
									<option ng-repeat="localGvtId in localgvts" value="{{localGvtId.id}}">{{localGvtId.name}}</option>
									</select>
							</div>
						</div>	
						<div class='row' style='padding:0px 15px'>		
						<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
						<label><?php echo STATE_CREATE_ACTIVE; ?><span ng-show="addctmsstateForm.active.$touched ||addctmsstateForm.active.$dirty && addctmsstateForm.active.$invalid">
								<span class = 'err' ng-show="addctmsstateForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="active" class='form-control' name = 'active' id='Active' required >											
									<option value=''><?php echo STATE_CREATE_ACTIVE_SELECT; ?></option>
									<option value='Y'>Y-<?php echo STATE_CREATE_ACTIVE_YES; ?></option>
									<option value='N'>N-<?php echo STATE_CREATE_ACTIVE_NO; ?></option>
								</select>
						</div>
						<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
						<label>PTSP Type<span ng-show="addctmsstateForm.Ptsp.$touched ||addctmsstateForm.Ptsp.$dirty && addctmsstateForm.Ptsp.$invalid">
								<span class = 'err' ng-show="addctmsstateForm.Ptsp.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="Ptsp" class='form-control' name = 'Ptsp' id='Ptsp' required >											
									<option value=''>--Select PTSP Type--</option>
									<option value='E'>E-EPMS</option>
									<option value='P'>P-POSVAS</option>
								</select>
						</div>
						</div>
							<div class='row' style='padding:0px 15px'>
					<div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label><?php echo APPLICATION_APPROVE_START_DATE; ?>
							<span class = 'err' ng-show="editstateForm.startdate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="startdate" date-format="yyyy-mm-dd" type='date' id='startdate'  pattern="\d{4}-\d{2}-\d{2}" name='startdate'  class='form-control'/>
						</div>
						 <div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label><?php echo APPLICATION_APPROVE_END_DATE; ?>
							<span class = 'err' ng-show="editstateForm.endDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="expdate" type='date' id='expdate' name='expdate'  class='form-control'/>
								
						</div>
						</div>
				</div>
				<div class='clearfix'></div>
				</form>
				</div>
				<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo STATE_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo STATE_CREATE_BUTTON_CANCEL; ?></a>
					<button type="button" class="btn btn-primary" ng-click='addctmsstateForm.$invalid=true;create()' ng-hide='isHide' ng-disabled = "addctmsstateForm.$invalid"  id="Create"><?php echo STATE_CREATE_BUTTON_CREATE; ?></button>
				</div>
			</div>
		</div>
	</div>	
	
	 <div id='EditstateDialogue' class='modal fade' role='dialog' data-backdrop="static" data-keyboard="false" >
		 <div class="modal-dialog modal-md">
			<div class="modal-content">
				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>CTMS Type-State Edit</h2>
					</div>					 
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>						 
					<div class="modal-body">		
						<form action="" method="POST"  name='EditctmsstateForm' id="editctmsstateForm">				
						<div id='ctmscountryBody'  ng-hide='isLoader'>						
							 <div class='row' style='padding:0px 15px'>						
					<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label>State<span class='spanre'>*</span><span ng-show="EditctmsstateForm.state_id.$touched ||EditctmsstateForm.state_id.$dirty && EditctmsstateForm.state_id.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="EditctmsstateForm.state_id.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-disabled='isInputDisabled' ng-model="state_id"  class='form-control' name = 'state' id='state' required>											
									<option value=''><?php echo APPLICATION_ENTRY_SELECT_STATE; ?></option>
									<option ng-repeat="state in states" value="{{state.id}}">{{state.name}}</option>
								</select>
							</div>	
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label>Local Goverment<span ng-show="editINFOForm.local_govt_id.$touched ||editINFOForm.local_govt_id.$dirty && editINFOForm.local_govt_id.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="editINFOForm.local_govt_id.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select id='local_govt_id'  ng-model='local_govt_id'   class='form-control' name='local_govt_id'  >
									<option value=''><?php echo APPLICATION_ENTRY_SELECT_LOCAL_GOVT; ?></option>
									<option ng-repeat="localGvtId in localgvts" value="{{localGvtId.id}}">{{localGvtId.name}}</option>
									</select>
							</div>
						</div>	
						<div class='row' style='padding:0px 15px'>		
						<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
						<label><?php echo STATE_CREATE_ACTIVE; ?><span ng-show="EditctmsstateForm.active.$touched ||EditctmsstateForm.active.$dirty && EditctmsstateForm.active.$invalid">
								<span class = 'err' ng-show="EditctmsstateForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="active" class='form-control' name = 'active' id='Active' required >											
									<option value=''><?php echo STATE_CREATE_ACTIVE_SELECT; ?></option>
									<option value='Y'><?php echo STATE_CREATE_ACTIVE_YES; ?></option>
									<option value='N'><?php echo STATE_CREATE_ACTIVE_NO; ?></option>
								</select>
						</div>
						<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
						<label>PTSP Type<span ng-show="EditctmsstateForm.Ptsp.$touched ||EditctmsstateForm.Ptsp.$dirty && EditctmsstateForm.Ptsp.$invalid">
								<span class = 'err' ng-show="EditctmsstateForm.Ptsp.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="Ptsp" class='form-control' name = 'Ptsp' id='Ptsp' required >											
									<option value=''>--Select PTSP Type--</option>
									<option value='E'>E-EPMS</option>
									<option value='P'>P-POSVAS</option>
								</select>
						</div>
						</div>
							<div class='row' style='padding:0px 15px'>
					<div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label><?php echo APPLICATION_APPROVE_START_DATE; ?>
							<span class = 'err' ng-show="editstateForm.startdate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="startdate" date-format="yyyy-mm-dd" type='date' id='startdate'  pattern="\d{4}-\d{2}-\d{2}" name='startdate'  class='form-control'/>
						</div>
						 <div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label><?php echo APPLICATION_APPROVE_END_DATE; ?>
							<span class = 'err' ng-show="editstateForm.endDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="expdate" type='date' id='expdate' name='expdate'  class='form-control'/>
								
						</div>
						</div>
				</div>
						 </form>	
					</div>
					<div class='modal-footer ' >
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo STATE_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo STATE_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="EditctmsstateForm.$invalid" ng-click="EditctmsstateForm.$invalid=true;update($index,x.id)" id="Update"><?php echo STATE_EDIT_BUTTON_UPDATE; ?></button>
					</div>
				
			</div></div>

	
	
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
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	//WinMove();
	$("#EditstateDialogue, #AddStateDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	$("#EditstateDialogue, #AddstateDialogue").on("keypress",".sc", function (event) {
		    var regex = new RegExp("^[a-zA-Z0-9 \b:/.~!@#$*_-]+$");
 		    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
 		    if (!regex.test(key)) {
 		       event.preventDefault();
 		       return false;
 		    }
 		});
		  /* window.alert = function() {}; alert = function() {}; */
});
</script>
