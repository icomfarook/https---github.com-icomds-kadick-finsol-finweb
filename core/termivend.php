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
<div ng-controller='TermvendcCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!mstste"><?php echo STATE_MAIN_HEADING1; ?></a></li>
			<li><a href="#!mstste">Terminal Vendor</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class='row'>
			<input type='button' style='float: right;margin-right: 2%;' class='btn btn-primary' value='Create New Vendor' id='Create'  data-toggle='modal' href='#' data-target='#CreateVendorDialogue'/>
		
		</div>
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Terminal Vendor</span>
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
							<th>Vendor Name</th>
							<th>Terminal Model</th>
							<th>Active</th>
							<th>Edit</th>
						</tr>
			    	</thead>
					<tbody>
					    <tr ng-repeat="x in vendor_list">
						 	<td>{{ x.terminal_vendor_id }}</td>
							<td>{{ x.vendor_name }}</td>
							<td>{{ x.terminal_model }}</td>
							<td>{{ x.active }}</td>
							<td><a id={{x.terminal_vendor_id}} class='editpro' ng-click='edit($index,x.terminal_vendor_id)' data-toggle='modal' data-target='#EditstateDialogue'>
								<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
						</tr>
					</tbody>
				
				</table>
			</div>
		</div>
	</div>
		<div id='CreateVendorDialogue' class='modal ' role='dialog' data-backdrop="static" data-keyboard="false" >
	        <div class="modal-dialog modal-md">
		        <div class="modal-content">
			      	<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 style='text-align:center'>Create Vendor</h2>
				    </div>		
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' style="text-align:center" src="../common/img/gif2.gif" /></div></div>
				<div class='modal-body'>
				    <form action="" method="POST" name='addVendorForm' id="addVendorForm">
				     <div id='VendorCreateBody'  ng-hide='isLoader'>						
							 <div class='row' style='padding:0px 15px'>						
					                <div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
						<label>Vendor Name<span ng-show="addVendorForm.vendor_name.$touched ||addVendorForm.vendor_name.$dirty && addVendorForm.vendor_name.$invalid">
								<span class = 'err' ng-show="addVendorForm.vendor_name.$error.required"><?php echo REQUIRED;?></span></span></label>
						<input type='text' ng-trim="false"  ng-model="vendor_name" name='vendor_name' maxlength="30"  id='vendor_name' required class='form-control'/>
					</div>
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
						<label><?php echo STATE_CREATE_ACTIVE; ?><span ng-show="addVendorForm.active.$touched ||addVendorForm.active.$dirty && addVendorForm.active.$invalid">
								<span class = 'err' ng-show="addVendorForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="active" class='form-control' name = 'active' id='Active' required >											
									<option value=''><?php echo STATE_CREATE_ACTIVE_SELECT; ?></option>
									<option value='Y'><?php echo STATE_CREATE_ACTIVE_YES; ?></option>
									<option value='N'><?php echo STATE_CREATE_ACTIVE_NO; ?></option>
								</select>
						</div>
						<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
						<label>Terminal Model<span ng-show="addVendorForm.terminal_model.$touched ||addVendorForm.terminal_model.$dirty && addVendorForm.terminal_model.$invalid">
								<span class = 'err' ng-show="addVendorForm.terminal_model.$error.required"><?php echo REQUIRED;?></span></span></label>
						<input type='text' restrict-field="terminal_model" ng-model="terminal_model" name='terminal_model' maxlength="20"  id='terminal_model' required class='form-control'/>
					</div>
						</div>	
						</div>
				<div class='clearfix'></div>
				    </form>
				</div>
				<div class='modal-footer'>
					<button type='button' class='btn btn-primary'   ng-click='refresh()'  data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo STATE_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo STATE_CREATE_BUTTON_CANCEL; ?></a>
					<button type="button" class="btn btn-primary" ng-click='addVendorForm.$invalid=true;create()' ng-hide='isHide' ng-disabled = "addVendorForm.$invalid"  id="Create"><?php echo STATE_CREATE_BUTTON_CREATE; ?></button>
				</div>
			    </div>
		    </div>
	    </div>	
	
	 <div id='EditstateDialogue' class='modal fade' role='dialog' data-backdrop="static" data-keyboard="false" >
		 <div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 style='text-align:center'>Vendor Edit</h2>
				</div>					 
			    	<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>						 
					<div class="modal-body">		
						<form action="" method="POST"  name='editvendorForm' id="EditstateDialogue">				
						<div id='VendorBody'  ng-hide='isLoader'>						
							 <div class='row' style='padding:0px 15px'>						
					                <div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
						<label>Vendor Name<span ng-show="editvendorForm.vendor_name.$touched ||editvendorForm.vendor_name.$dirty && editvendorForm.vendor_name.$invalid">
								<span class = 'err' ng-show="editvendorForm.vendor_name.$error.required"><?php echo REQUIRED;?></span></span></label>
						<input type='text' ng-trim="false"  ng-model="vendor_name" name='vendor_name' maxlength="30"  id='vendor_name' required class='form-control'/>
					</div>
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
						<label><?php echo STATE_CREATE_ACTIVE; ?><span ng-show="editvendorForm.active.$touched ||editvendorForm.active.$dirty && editvendorForm.active.$invalid">
								<span class = 'err' ng-show="editvendorForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="active" class='form-control' name = 'active' id='Active' required >											
									<option value=''><?php echo STATE_CREATE_ACTIVE_SELECT; ?></option>
									<option value='Y'><?php echo STATE_CREATE_ACTIVE_YES; ?></option>
									<option value='N'><?php echo STATE_CREATE_ACTIVE_NO; ?></option>
								</select>
						</div>
						<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
						<label>Terminal Model<span ng-show="editvendorForm.terminal_model.$touched ||editvendorForm.terminal_model.$dirty && editvendorForm.terminal_model.$invalid">
								<span class = 'err' ng-show="editvendorForm.terminal_model.$error.required"><?php echo REQUIRED;?></span></span></label>
						<input type='text' restrict-field="terminal_model" ng-model="terminal_model" name='terminal_model' maxlength="20"  id='terminal_model' required class='form-control'/>
					</div>
						</div>	
						</div>
						 </form>	
					</div>
					<div class='modal-footer ' >
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo STATE_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo STATE_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="editvendorForm.$invalid" ng-click="editvendorForm.$invalid=true;update(terminal_vendor_id)" id="Update"><?php echo STATE_EDIT_BUTTON_UPDATE; ?></button>
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
		/* window.alert = function() {};
     alert = function() {}; */
});
</script>
