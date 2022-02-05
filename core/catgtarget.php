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
<div ng-controller='CatTargetCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!mascatar"><?php echo STATE_MAIN_HEADING1; ?></a></li>
			<li><a href="#!mascatar">Party Category Target</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class='row'>
			<input type='button' style='float: right;margin-right: 2%;' class='btn btn-primary' value='Create Party Category Target' id='Create'  data-toggle='modal' href='#' data-target='#AddStateDialogue'/>
		
		</div>
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Party Category Target</span>
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
							<th>Target Name</th>
      							<th>Combo Name</th>
							<th>Party Category</th>
							<th>Count</th>
							<th>Amount</th>
							<th>Condition</th>
							<th>Edit</th>
						</tr>
					</thead>
					<tbody>
					 <tr ng-repeat="x in categorylist">
						 	<td>{{ x.name }}</td>
							<td>{{ x.combo_name }}</td>
							<td>{{ x.party_category }}</td>
							<td>{{ x.count }}</td>
							<td>{{ x.amount }}</td>
							<td>{{ x.condition }}</td>
							<td><a id={{x.id}} class='editpro' ng-click='edit($index,x.id)' data-toggle='modal' data-target='#EditstateDialogue'>
								<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
						</tr>
					</tbody>
				
				</table>
			</div>
		</div>
	</div>
		<div id='AddStateDialogue' class='modal ' role='dialog' data-backdrop="static" data-keyboard="false" >
	 <div class="modal-dialog modal-md">
		<div class="modal-content">
			
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 style='text-align:center'>Party Category Target Create</h2>
				</div>		
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' style="text-align:center" src="../common/img/gif2.gif" /></div></div>
				<div class='modal-body'>
				<form action="" method="POST" name='addstateForm' id="AddstateForm">
				 <div  id='TargetCreateBody'  ng-hide='isLoader'>
				 <div class='row' style='padding:0px 15px'>						
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
						<label>Target Name<span class='spanre'>*</span><span ng-show="addstateForm.name.$touched ||addstateForm.name.$dirty && addstateForm.name.$invalid">
								<span class = 'err' ng-show="addstateForm.name.$error.required"><?php echo REQUIRED;?></span></span></label>
						<input type='text' ng-model="name" name='name' maxlength="20"  id='name' required class='form-control'/>
					</div>
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label>Combo Name<span class='spanre'>*</span><span ng-show="addstateForm.ComboName.$touched ||addstateForm.ComboName.$dirty && addstateForm.ComboName.$invalid">
								<span class = 'err' ng-show="addstateForm.ComboName.$error.required"><?php echo REQUIRED;?>.</span></span></label>
							    <select ng-model="ComboName"  class='form-control' name = 'ComboName' id='type' required>											
								<option value=''>--Select Combo Name--</option>
								<option ng-repeat="X in partytarget" value="{{X.name}}">{{X.name}}</option>
							</select>
						</div>	
						</div>	
							 <div class='row' style='padding:0px 15px'>						
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label>Party Category<span class='spanre'>*</span><span ng-show="addstateForm.partycatype.$touched ||addstateForm.partycatype.$dirty && addstateForm.partycatype.$invalid">
								<span class = 'err' ng-show="addstateForm.partycatype.$error.required"><?php echo REQUIRED;?>.</span></span></label>
							    <select ng-model="partycatype"  class='form-control' name = 'partycatype' id='type' required>											
								<option value=''><?php echo APPLICATION_APPROVE_APPROVE_CATEGORY_SELECT; ?></option>
								<option ng-repeat="Y in partycatypes" value="{{Y.id}}">{{Y.name}}</option>
							</select>
						</div>	
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
							<label>Count<span class='spanre'>*</span><span ng-show="addstateForm.Count.$dirty && addstateForm.Count.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
								<input  ng-model="Count"  numbers-only type='text' id='Count' maxlength='10'  name='Count' required class='form-control'/>
						</div>
						</div>	
							 <div class='row' style='padding:0px 15px'>						
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
					<label>Amount<span class='spanre'>*</span><span ng-show="addstateForm.amount.$dirty && addstateForm.amount.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
								<input  ng-model="amount"  numbers-only type='text' id='amount' maxlength='10'  name='amount' required class='form-control'/>
					</div>
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
						<label>Condition<span class='spanre'>*</span><span ng-show="addstateForm.Condition.$touched ||addstateForm.Condition.$dirty && addstateForm.Condition.$invalid">
								<span class = 'err' ng-show="addstateForm.Condition.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="Condition" class='form-control' name = 'Condition' id='Condition' required >											
									<option value=''><?php echo STATE_CREATE_ACTIVE_SELECT; ?></option>
									<option value='A'>A - And</option>
									<option value='O'>O - OR</option>
								</select>
						</div>
						</div>	
				</div>
				<div class='clearfix'></div>
				</form>
				</div>
				<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo STATE_CREATE_BUTTON_OK; ?></button>
					<button type='button' ng-click='refresh()' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo STATE_CREATE_BUTTON_CANCEL; ?></a>
					<button type="button" class="btn btn-primary" ng-click='addstateForm.$invalid=true;create()' ng-hide='isHide' ng-disabled = "addstateForm.$invalid"  id="Create"><?php echo STATE_CREATE_BUTTON_CREATE; ?></button>
				</div>
			</div>
		</div>
	</div>	
	
	 <div id='EditstateDialogue' class='modal fade' role='dialog' data-backdrop="static" data-keyboard="false" >
		 <div class="modal-dialog modal-md">
			<div class="modal-content">
				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Edit Party Category Target</h2>
					</div>					 
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>						 
					<div class="modal-body">		
						<form action="" method="POST"  name='editstateForm' id="EditstateDialogue">				
						<div id='countryBody'  ng-hide='isLoader'>						
					 <div class='row' style='padding:0px 15px'>						
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
						<label>Target Name<span class='spanre'>*</span><span ng-show="editstateForm.name.$touched ||editstateForm.name.$dirty && editstateForm.name.$invalid">
								<span class = 'err' ng-show="editstateForm.name.$error.required"><?php echo REQUIRED;?></span></span></label>
						<input type='text' ng-model="name" name='name' maxlength="20"  id='name' required class='form-control'/>
					</div>
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label>Combo Name<span class='spanre'>*</span><span ng-show="editstateForm.ComboName.$touched ||editstateForm.ComboName.$dirty && editstateForm.ComboName.$invalid">
								<span class = 'err' ng-show="editstateForm.ComboName.$error.required"><?php echo REQUIRED;?>.</span></span></label>
							    <select ng-model="ComboName"  class='form-control' name = 'ComboName' id='type' required>											
								<option value=''>--Select Combo Name--</option>
								<option ng-repeat="X in partytarget" value="{{X.name}}">{{X.name}}</option>
							</select>
						</div>	
						</div>	
							 <div class='row' style='padding:0px 15px'>						
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label>Party Category<span class='spanre'>*</span><span ng-show="editstateForm.partycatype.$touched ||editstateForm.partycatype.$dirty && editstateForm.partycatype.$invalid">
								<span class = 'err' ng-show="editstateForm.partycatype.$error.required"><?php echo REQUIRED;?>.</span></span></label>
							    <select ng-model="partycatype"  class='form-control' name = 'partycatype' id='type' required>											
								<option value=''><?php echo APPLICATION_APPROVE_APPROVE_CATEGORY_SELECT; ?></option>
								<option ng-repeat="Y in partycatypes" value="{{Y.id}}">{{Y.name}}</option>
							</select>
						</div>	
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
							<label>Count<span class='spanre'>*</span><span ng-show="editstateForm.paymentamount.$dirty && editstateForm.paymentamount.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
								<input  ng-model="Count"  numbers-only type='text' id='Count' maxlength='10'  name='Count' required class='form-control'/>
						</div>
						</div>	
							 <div class='row' style='padding:0px 15px'>						
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
					<label>Amount<span class='spanre'>*</span><span ng-show="editstateForm.amount.$dirty && editstateForm.amount.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
								<input  ng-model="amount"  numbers-only type='text' id='amount' maxlength='10'  name='amount' required class='form-control'/>
					</div>
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
						<label>Condition<span class='spanre'>*</span><span ng-show="editstateForm.Condition.$touched ||editstateForm.Condition.$dirty && editstateForm.Condition.$invalid">
								<span class = 'err' ng-show="editstateForm.Condition.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="Condition" class='form-control' name = 'Condition' id='Condition' required >											
									<option value=''><?php echo STATE_CREATE_ACTIVE_SELECT; ?></option>
									<option value='A'>A - And</option>
									<option value='O'>O - OR</option>
								</select>
						</div>
						</div>	
						</div>
						 </form>	
					</div>
					<div class='modal-footer ' >
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo STATE_EDIT_BUTTON_OK; ?></button>
						<button type='button' ng-click='refresh()' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo STATE_EDIT_BUTTON_CANCEL; ?></button>
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
		  /* window.alert = function() {}; alert = function() {}; */
});
</script>
