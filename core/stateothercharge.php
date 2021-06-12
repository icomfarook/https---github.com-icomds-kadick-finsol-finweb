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
<div ng-controller='StatOthrCharCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!ratvat"><?php echo STATE_MAIN_HEADING1; ?></a></li>
			<li><a href="#!ratvat">Value Added Tax</li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class='row'>
			<button  style='float: right;margin-right: 2%;' class='btn btn-primary' value='Create' id='Create'  data-toggle='modal' href='#' data-target='#AddStatechargeDialogue'>Create</button>
		
		</div>
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Value Added Tax</span>
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
							<th>ID</th>
      						<th>State</th>
							<th>Charge Factor</th>
							<th>Charge Value</th>
							<th>Active</th>
							<th>Start Date</th>
							<th>End Date</th>
							<th>Service Feature Code</th>
							<th>Edit</th>
						</tr>
					</thead>
					<tbody>
					 <tr ng-repeat="x in statechargelist">
						 	<td>{{ x.id }}</td>
							<td>{{ x.state }}</td>
							<td>{{ x.chargefactor }}</td>
							<td>{{ x.chargevalue }}</td>
							<td>{{ x.active }}</td>
							<td>{{ x.sdate }}</td>
							<td>{{ x.edate }}</td>
							<td>{{ x.code }}</td>
							<td><a id={{x.id}} class='editpro' ng-click='edit($index,x.id)' data-toggle='modal' data-target='#EditstatechrgeDialogue'>
								<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
						</tr>
					</tbody>
				
				</table>
			</div>
		</div>
	</div>
		<div id='AddStatechargeDialogue' class='modal ' role='dialog' data-backdrop="static" data-keyboard="false" >
	 <div class="modal-dialog modal-md">
		<div class="modal-content">
			
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 style='text-align:center'>State Other Charge - Create</h2>
				</div>		
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' style="text-align:center" src="../common/img/gif2.gif" /></div></div>
				<div class='modal-body'>
				<form action="" method="POST" name='addstateForm' id="AddstateForm">
				 <div  id='statechrCreateBody'  ng-hide='isLoader'>
				 <div class='row' style='padding:0px 15px'>						
					<div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
								<label>State<span class='spanre'>*</span><span ng-show="addstateForm.createstate.$touched ||addstateForm.createstate.$dirty && addstateForm.createstate.$invalid">
								<span class = 'err' ng-show="addstateForm.createstate.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="createstate" class='form-control' name = 'createstate' id='State' required >											
									<option value=''>Select State</option>
									<option ng-repeat="state in statelist" value="{{state.id}}">{{state.name}}</option>
								</select>
							</div>	
							
					<div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
						<label>Charge Factor<span class='spanre'>*</span><span ng-show="addstateForm.chargefactor.$touched ||addstateForm.chargefactor.$dirty && addstateForm.chargefactor.$invalid">
								<span class = 'err' ng-show="addstateForm.chargefactor.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="chargefactor" class='form-control' name = 'chargefactor' id='chargefactor' required >											
									<option value=''>Select Charge Factor</option>
									<option value='A'>A-Amount</option>
									<option value='P'>P-Percentage</option>
								</select>
						</div>
						</div>	
						<div class='row' style='padding:0px 15px'>
						<div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
									<label>Charge Value<span class='spanre'>*</span><span ng-show="addstateForm.chargevalue.$touched ||addstateForm.chargevalue.$dirty && addstateForm.chargevalue.$invalid">
										<span class = 'err' ng-show="addstateForm.chargevalue.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input type='text'    ng-model="chargevalue" required  name='chargevalue' maxlength="15" id='chargevalue' class='form-control'/>
								</div>
								<div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
										<label> <?php echo SERVICE_FEATURE_CONFIG_SERVICE_FEATURE; ?><span class='spanre'>*</span><span ng-show=" addstateForm.serfea.$touched || addstateForm.serfea.$dirty &&  addstateForm.serfea.$invalid">
										<span class = 'err' ng-show=" addstateForm.serfea.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<select ng-model="serfea" class='form-control' name = 'serfea' id='serfea' required>											
											<option value=''><?php echo SERVICE_FEATURE_CONFIG_SELECT_SERVICE_FEATURE; ?></option>
											<option ng-repeat="ser in servfeas" value="{{ser.id}}">{{ser.name}}</option>
										</select>
									</div>
						
						
					</div>
					<div class='row' style='padding:0px 15px'>
					 <div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label><?php echo APPLICATION_APPROVE_START_DATE; ?>
							<span class = 'err' ng-show="addstateForm.startDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="startdate" type='date' id='startdate' name='startdate'  class='form-control'/>
						</div>
						 <div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label><?php echo APPLICATION_APPROVE_END_DATE; ?>
							<span class = 'err' ng-show="addstateForm.endDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="expdate" type='date' id='expdate' name='expdate'  class='form-control'/>
						</div>
						</div>
						<div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
						<label><?php echo STATE_CREATE_ACTIVE; ?><span class='spanre'>*</span><span ng-show="addstateForm.active.$touched ||addstateForm.active.$dirty && addstateForm.active.$invalid">
								<span class = 'err' ng-show="addstateForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="active" class='form-control' name = 'active' id='Active' required >											
									<option value=''><?php echo STATE_CREATE_ACTIVE_SELECT; ?></option>
									<option value='Y'><?php echo STATE_CREATE_ACTIVE_YES; ?></option>
									<option value='N'><?php echo STATE_CREATE_ACTIVE_NO; ?></option>
								</select>
						</div>
				</div>
				<div class='clearfix'></div>
				</form>
				</div>
				<div class='modal-footer'>
					<button type='button' class='btn btn-primary' ng-click="refresh()" data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo STATE_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo STATE_CREATE_BUTTON_CANCEL; ?></a>
					<button type="button" class="btn btn-primary" ng-click='addstateForm.$invalid=false;create()' ng-hide='isHide' ng-disabled = "addstateForm.$invalid"  id="Create"><?php echo STATE_CREATE_BUTTON_CREATE; ?></button>
				</div>
			</div>
		</div>
	</div>	
	
	 <div id='EditstatechrgeDialogue' class='modal fade' role='dialog' data-backdrop="static" data-keyboard="false" >
		 <div class="modal-dialog modal-md">
			<div class="modal-content">
				
					<div class="modal-header">
						<button type="button" ng-click='restric()' class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>State Other Charge Edit</h2>
					</div>					 
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>						 
					<div class="modal-body">		
						<form action="" method="POST"  name='editstateForm' id="EditstateDialogue">				
						<div id='stateothrchrBody'  ng-hide='isLoader'>						
						 <div class='row' style='padding:0px 15px'>						
					<div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
					<label>State<span class='spanre'>*</span><span ng-show="editstateForm.createstate.$touched ||editstateForm.createstate.$dirty && editstateForm.createstate.$invalid">
								<span class = 'err' ng-show="editstateForm.createstate.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="createstate" class='form-control' name = 'createstate' id='State' required >											
									<option value=''>Select State</option>
									<option ng-repeat="state in statelist" value="{{state.id}}">{{state.name}}</option>
								</select>
							</div>	
							
					<div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
						<label>Charge Factor<span class='spanre'>*</span><span ng-show="editstateForm.chargefactor.$touched ||editstateForm.chargefactor.$dirty && editstateForm.chargefactor.$invalid">
								<span class = 'err' ng-show="editstateForm.chargefactor.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="chargefactor" class='form-control' name = 'chargefactor' id='chargefactor' required >											
									<option value=''>Select Charge Factor</option>
									<option value='A'>A</option>
									<option value='P'>P</option>
								</select>
						</div>
						</div>	
						<div class='row' style='padding:0px 15px'>
						<div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label>Charge Value<span class='spanre'>*</span><span ng-show="editstateForm.chargevalue.$touched ||editstateForm.chargevalue.$dirty && editstateForm.chargevalue.$invalid">
										<span class = 'err' ng-show="editstateForm.chargevalue.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input type='text'   ng-model="chargevalue" required  name='chargevalue' maxlength="15" id='chargevalue' class='form-control'/>
								</div>
								
								<div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
										<label> <?php echo SERVICE_FEATURE_CONFIG_SERVICE_FEATURE; ?><span class='spanre'>*</span><span ng-show=" editstateForm.serfea.$touched || editstateForm.serfea.$dirty &&  editstateForm.serfea.$invalid">
										<span class = 'err' ng-show=" editstateForm.serfea.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<select ng-model="serfea" class='form-control' name = 'serfea' id='serfea' required>											
											<option value=''><?php echo SERVICE_FEATURE_CONFIG_SELECT_SERVICE_FEATURE; ?></option>
											<option ng-repeat="ser in servfeas" value="{{ser.id}}">{{ser.name}}</option>
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
						<div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
						<label><?php echo STATE_CREATE_ACTIVE; ?><span class='spanre'>*</span><span ng-show="editstateForm.active.$touched ||editstateForm.active.$dirty && editstateForm.active.$invalid">
								<span class = 'err' ng-show="editstateForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="active" class='form-control' name = 'active' id='Active' required >											
									<option value=''><?php echo STATE_CREATE_ACTIVE_SELECT; ?></option>
									<option value='Y'><?php echo STATE_CREATE_ACTIVE_YES; ?></option>
									<option value='N'><?php echo STATE_CREATE_ACTIVE_NO; ?></option>
								</select>
						</div>
				</div>
					<div class='modal-footer ' >
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo STATE_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' ng-click='restric()'  data-dismiss='modal' ng-hide='isHide' ><?php echo STATE_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="editstateForm.$invalid" ng-click="editstateForm.$invalid=true;update(id)" id="Update"><?php echo STATE_EDIT_BUTTON_UPDATE; ?></button>
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
	/* 	window.alert = function() {};
     alert = function() {}; */
});
</script>
