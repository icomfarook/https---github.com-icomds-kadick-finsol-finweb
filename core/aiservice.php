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
.labspa {
	color:blue;
}
</style>
<div ng-controller='AiServiceCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!aiservice"><?php echo STATE_MAIN_HEADING1; ?></a></li>
			<li><a href="#!aiservice">Adempiere Services</li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class='row'>
			<input type='button' style='float: right;margin-right: 2%;' class='btn btn-primary' value='Create' id='Create'  data-toggle='modal' href='#' data-target='#AddServiceDialogue'/>
		
		</div>
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Adempiere Services</span>
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
      						<th>Order Type</th>
							<th>Active</th>
							<th>Start Date</th>
							<th>Expiry Date</th>
							<th>Detail</th>
							<th>Edit</th>
						</tr>
					</thead>
					<tbody>
					 <tr ng-repeat="x in serviceList">
						 	<td>{{ x.id }}</td>
							<td>{{ x.service_feature_code }}</td>
							<td>{{ x.active }}</td>
							<td>{{ x.sdate }}</td>
							<td>{{ x.edate }}</td>
							<td><a id={{x.id}} class='editpro' ng-click='detail($index,x.id)' data-toggle='modal' data-target='#ServiceDialogue'>
								<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a></td>
							<td><a id={{x.id}} class='editpro' ng-click='edit($index,x.id)' data-toggle='modal' data-target='#EditserviceDialogue'>
								<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
						</tr>
					</tbody>
				
				</table>
			</div>
		</div>
	</div>
	
	
	
	
	 <div id='ServiceDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Adempiere Service Details - {{id}}</h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'  ng-hide='isLoader'>
					<form action="" method="POST" name='ApplicatioViewDialgoue' id="ApplicatioViewDialgoue">
						<div id='ApplicationViewBody'>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label># : <span class='labspa'>{{id}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Order Type : <span class='labspa'>{{name}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Active : <span class='labspa'>{{active}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Start Date :<span class='labspa'>{{sdate}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>End Date : <span class='labspa'>{{edate}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Create User : <span class='labspa'>{{create_user}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Create Time : <span class='labspa'>{{create_time}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Update User : <span class='labspa'>{{update_user}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Update Time : <span class='labspa'>{{update_time}}</span></label>
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
	
		<div id='AddServiceDialogue' class='modal ' role='dialog' data-backdrop="static" data-keyboard="false" >
	 <div class="modal-dialog modal-md">
		<div class="modal-content">
			
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 style='text-align:center'>Adempiere Service - Create</h2>
				</div>		
				<div class='modal-body'>
				<form action="" method="POST" name='addstampdutyForm' id="AddstampdutyForm">
				 <div  id='AiserviceCreateBody'  ng-hide='isLoader'>
				 <div class='row' style='padding:0px 15px'>						
						<div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
								<label> <?php echo SERVICE_CHARGE_GROUP_CREATE_SERVICE_FEATURE; ?><span class='spanre'>*</span><span ng-show="addstampdutyForm.serfea.$touched ||addstampdutyForm.serfea.$dirty && addstampdutyForm.serfea.$invalid">
								<span  ng-show="addstampdutyForm.serfea.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="serfea" class='form-control' name = 'serfea' id='serfea' >											
									<option value=''><?php echo SERVICE_CHARGE_GROUP_CREATE_SELECT_SERVICE_FEATURE; ?></option>
									<option ng-repeat="ser in servfeas" value="{{ser.id}}">{{ser.name}}</option>
								</select>
							</div>
							<div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
						
						<label><?php echo STATE_CREATE_ACTIVE; ?><span ng-show="addstampdutyForm.active.$touched ||addstampdutyForm.active.$dirty && addstampdutyForm.active.$invalid">
								<span ng-show="addstampdutyForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="active" class='form-control' name = 'active' id='Active' required >											
									<option value=''><?php echo STATE_CREATE_ACTIVE_SELECT; ?></option>
									<option value='Y'><?php echo STATE_CREATE_ACTIVE_YES; ?></option>
									<option value='N'><?php echo STATE_CREATE_ACTIVE_NO; ?></option>
								</select>
						</div>
						</div>	
				
					<div class='row' style='padding:0px 15px'>
					 <div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label><?php echo APPLICATION_APPROVE_START_DATE; ?><span ng-show="addstampdutyForm.startdate.$touched ||addstampdutyForm.startdate.$dirty && addstampdutyForm.startdate.$invalid">
								<span class = 'err' ng-show="addstampdutyForm.startdate.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input   ng-model="startdate" type='date' id='startdate' name='startdate'  class='form-control'/>
						</div>
						 <div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label><?php echo APPLICATION_APPROVE_END_DATE; ?><span ng-show="addstampdutyForm.expdate.$touched ||addstampdutyForm.expdate.$dirty && addstampdutyForm.expdate.$invalid">
								<span class = 'err' ng-show="addstampdutyForm.expdate.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input  ng-model="expdate" type='date' id='expdate' name='expdate'   class='form-control'/>
						</div>
						</div>
				</div>
				<div class='clearfix'></div>
				</form>
				</div>
				<div class='modal-footer'>
					<button type='button' class='btn btn-primary' ng-click="refresh()" data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo STATE_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo STATE_CREATE_BUTTON_CANCEL; ?></a>
					<button type="button" class="btn btn-primary" ng-click='addstampdutyForm.$invalid=false;create()' ng-hide='isHide' ng-disabled = "addstampdutyForm.$invalid"  id="Create"><?php echo STATE_CREATE_BUTTON_CREATE; ?></button>
				</div>
			</div>
		</div>
	</div>	
	
	 <div id='EditserviceDialogue' class='modal fade' role='dialog' data-backdrop="static" data-keyboard="false" >
		 <div class="modal-dialog modal-md">
			<div class="modal-content">
				
					<div class="modal-header">
						<button type="button" ng-click='restric()' class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Adempiere Service Edit</h2>
					</div>					 
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>						 
					<div class="modal-body">		
						<form action="" method="POST"  name='editstamDutyFrom' id="EditstamDutyFrom">				
						<div id='ServiceCreateBody'  ng-hide='isLoader'>						
						<div class='row' style='padding:0px 15px'>						
							<div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
								<label> <?php echo SERVICE_CHARGE_GROUP_CREATE_SERVICE_FEATURE; ?><span class='spanre'>*</span><span ng-show="editstamDutyFrom.serfea.$touched ||editstamDutyFrom.serfea.$dirty && editstamDutyFrom.serfea.$invalid">
								<span class = 'err' ng-show="editstamDutyFrom.serfea.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="serfea" class='form-control' name = 'serfea' id='serfea'>											
									<option value=''><?php echo SERVICE_CHARGE_GROUP_CREATE_SELECT_SERVICE_FEATURE; ?></option>
									<option ng-repeat="ser in servfeas" value="{{ser.id}}">{{ser.name}}</option>
								</select>
							</div>
							<div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
						
						<label><?php echo STATE_CREATE_ACTIVE; ?><span ng-show="editstamDutyFrom.active.$touched ||editstamDutyFrom.active.$dirty && editstamDutyFrom.active.$invalid">
								<span class = 'err' ng-show="editstamDutyFrom.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="active" class='form-control' name = 'active' id='Active' required >											
									<option value=''><?php echo STATE_CREATE_ACTIVE_SELECT; ?></option>
									<option value='Y'><?php echo STATE_CREATE_ACTIVE_YES; ?></option>
									<option value='N'><?php echo STATE_CREATE_ACTIVE_NO; ?></option>
								</select>
						</div>
						</div>			
					<div class='row' style='padding:0px 15px'>
					 <div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label><?php echo APPLICATION_APPROVE_START_DATE; ?><span ng-show="editstamDutyFrom.startdate.$touched ||editstamDutyFrom.startdate.$dirty && editstamDutyFrom.startdate.$invalid">
								<span class = 'err' ng-show="editstamDutyFrom.startdate.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input  ng-model="startdate" date-format="yyyy-mm-dd" type='date' id='startdate' name='startdate' pattern="\d{4}-\d{2}-\d{2}"  class='form-control'/>
						</div>
						 <div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label><?php echo APPLICATION_APPROVE_END_DATE; ?><span ng-show="editstamDutyFrom.expdate.$touched ||editstamDutyFrom.expdate.$dirty && editstamDutyFrom.expdate.$invalid">
								<span class = 'err' ng-show="editstamDutyFrom.expdate.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input  ng-model="expdate" type='date' id='expdate' name='expdate'  class='form-control'/>
						</div>
						</div>
				</div>
					<div class='modal-footer ' >
						<button type='button' class='btn btn-primary' ng-click='restric()' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo STATE_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' ng-click='restric()' data-dismiss='modal' ng-hide='isHide' ><?php echo STATE_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="editstamDutyFrom.$invalid" ng-click="editstamDutyFrom.$invalid=true;update(id)" id="Update"><?php echo STATE_EDIT_BUTTON_UPDATE; ?></button>
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
	//LoadSelect2Script(MakeSelect2);
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
		/* window.alert = function() {};
     alert = function() {}; */
});
</script>
