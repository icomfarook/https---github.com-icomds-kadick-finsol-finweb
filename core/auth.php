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
#AddAuthorizationDialogue .table > tbody > tr > td {
	border:none;
}
</style>
<div ng-controller='authCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!acsaut"><?php echo AUTHORIZATION_MAIN_HEADING1; ?></a></li>
			<li><a href="#!acsaut"><?php echo AUTHORIZATION_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class='row'>
			<input type='button' style='float: right;margin-right: 2%;'class='btn btn-primary' value='<?php echo AUTHORIZATION_MAIN_NEW_AUTHORIZATION; ?>' id='Create' href='#' data-toggle='modal' data-target='#AddAuthorizationDialogue'/>
		</div>
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo AUTHORIZATION_MAIN_HEADING3; ?></span>
				</div>
				<div class="box-icons">
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
							<th><?php echo AUTHORIZATION_MAIN_TABLE_ID; ?></th>
      						<th><?php echo AUTHORIZATION_MAIN_TABLE_CODE; ?></th>
							<th><?php echo AUTHORIZATION_MAIN_TABLE_ASSIGNABLE; ?></th>
							<th><?php echo AUTHORIZATION_MAIN_TABLE_ACTIVE; ?></th>
							<th><?php echo AUTHORIZATION_MAIN_TABLE_EDIT; ?></th>
						</tr>
					</thead>
					<tbody>
						 <tr ng-repeat="x in authlist">
							<td>{{ x.id }}</td>
						 	<td>{{ x.code }}</td>
							<td>{{ x.assignable }}</td>
							<td>{{ x.active }}</td>
							<td><a id={{x.id}}class='editAuthorization' ng-click='edit($index,x.id)' data-toggle='modal' data-target='#EditAuthorizationDialogue'>
								<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	 <div id='EditAuthorizationDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo AUTHORIZATION_EDIT_HEADING1; ?> {{code}}</h2>
					</div>
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>					
					<div class='modal-body'>
					<form action="" method="POST" name='editAuthorizationForm' id="EditAuthorizationForm">
						<div id='AuthBody'>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label> <?php echo AUTHORIZATION_EDIT_ID; ?><span ng-show="editAuthorizationForm.id.$dirty && editAuthorizationForm.id.$invalid">
								<span class = 'err' ng-show="editAuthorizationForm.id.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input maxlength='10' numbers-only	type='text'  name='id'  ng-model='id'  id='id' class='form-control'/>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label> <?php echo AUTHORIZATION_EDIT_CODE; ?><span ng-show="editAuthorizationForm.code.$touched ||editAuthorizationForm.code.$dirty && editAuthorizationForm.code.$invalid">
								<span class = 'err' ng-show="editAuthorizationForm.code.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input maxlength='10' type='text' name='code' required ng-model='code' maxlength='10' id='authcode' class='sc form-control'/>
							</div>
							
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label><?php echo AUTHORIZATION_EDIT_ACTIVE; ?><span ng-show="editAuthorizationForm.active.$touched ||editAuthorizationForm.active.$dirty && editAuthorizationForm.active.$invalid">
								<span class = 'err' ng-show="editAuthorizationForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<select  class='form-control' ng-model='active' required name = 'active' id='Active'>											
										<option  value='Y'><?php echo AUTHORIZATION_EDIT_ACTIVE_YES; ?></option>
										<option  value='N'><?php echo AUTHORIZATION_EDIT_ACTIVE_NO; ?></option>
									</select>
							</div>						
							<div class='col-xs-12 col-md-6 col-lg-6 col-sm-12 form_col12_element'>
								<label><?php echo AUTHORIZATION_EDIT_ASSIGNABLE; ?><span ng-show="editAuthorizationForm.assignable.$touched ||editAuthorizationForm.assignable.$dirty && editAuthorizationForm.assignable.$invalid">
								<span class = 'err' ng-show="editAuthorizationForm.assignable.$error.required"><?php echo REQUIRED;?>.</span></span></label>							
									<select  class='form-control' ng-model='assignable' required name = 'assignable' id='assignable'>											
										<option value='X'><?php echo AUTHORIZATION_EDIT_ASSIGNABLE_X; ?></option>
										<option value='R'><?php echo AUTHORIZATION_EDIT_ASSIGNABLE_R; ?></option>
										<option value='Y'><?php echo AUTHORIZATION_EDIT_ASSIGNABLE_Y; ?></option>
										<option value='N'><?php echo AUTHORIZATION_EDIT_ASSIGNABLE_N; ?></option>
									</select>
							</div>
							<div class='clearfix'></div>
						</div>
						</form>	
					</div>				
					<div class='modal-footer'>
					
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo AUTHORIZATION_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo AUTHORIZATION_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="editAuthorizationForm.$invalid" ng-click="editAuthorizationForm.$invalid=true;update(id)" id="Update"><?php echo AUTHORIZATION_EDIT_BUTTON_UPDATE; ?></button>
					</div>
			</div>
		</div>	
	</div>	
	<div id='AddAuthorizationDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo AUTHORIZATION_CREATE_HEADING1;?></h2>
					</div>					
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>					
					<div class='modal-body' >
					<form action="" method="POST" name='addauthorizationForm' id="AddAuthorizationForm">
						<div id='AuthCreateBody'  ng-hide='isLoader'>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label><?php echo AUTHORIZATION_CREATE_ID; ?><span ng-show="addauthorizationForm.id.$touched ||addauthorizationForm.id.$dirty && addauthorizationForm.id.$invalid">
								<span class = 'err' ng-show="addauthorizationForm.id.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input ng-model="id" numbers-only type='text' maxlength='10' name='id'  id='auth_Id' class='form-control' required />
							</div>	
							
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label><?php echo AUTHORIZATION_CREATE_CODE; ?><span ng-show="addauthorizationForm.code.$touched ||addauthorizationForm.code.$dirty && addauthorizationForm.code.$invalid">
								<span class = 'err' ng-show="addauthorizationForm.code.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input ng-model="code" maxlength='10' ng-trim="false" name='code' id='code' class='sc form-control' required />
							</div>	
							
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label><?php echo AUTHORIZATION_CREATE_ACTIVE; ?><span ng-show="addauthorizationForm.active.$touched ||addauthorizationForm.active.$dirty && addauthorizationForm.active.$invalid">
								<span class = 'err' ng-show="addauthorizationForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="active" class='form-control' name = 'active' id='Active' required >											
									<option value=''><?php echo AUTHORIZATION_CREATE_ACTIVE_SELECT; ?></option>
									<option value='Y'><?php echo AUTHORIZATION_CREATE_ACTIVE_YES; ?></option>
									<option value='N'><?php echo AUTHORIZATION_CREATE_ACTIVE_NO; ?></option>
								</select>
							</div>					
							
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label><?php echo AUTHORIZATION_CREATE_ASSIGNABLE; ?><span ng-show="addauthorizationForm.assignable.$touched ||addauthorizationForm.assignable.$dirty && addauthorizationForm.assignable.$invalid">
								<span class = 'err' ng-show="addauthorizationForm.assignable.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="assignable" class='form-control' name = 'assignable' id='Assignable' required>	
									<option value=''><?php echo AUTHORIZATION_CREATE_ASSIGNABLE_SELECT; ?></option>		
									<option value='X'><?php echo AUTHORIZATION_CREATE_ASSIGNABLE_X; ?></option>
									<option value='R'><?php echo AUTHORIZATION_CREATE_ASSIGNABLE_R; ?></option>
									<option value='Y'><?php echo AUTHORIZATION_CREATE_ASSIGNABLE_Y; ?></option>
									<option value='N'><?php echo AUTHORIZATION_CREATE_ASSIGNABLE_N; ?></option>
								</select>
							</div>
							<div class='clearfix'></div>
						</div>
						</form>	
					</div>
					<div class='modal-footer'>
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo AUTHORIZATION_CREATE_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide'  href='#'><?php echo AUTHORIZATION_CREATE_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-click = 'addauthorizationForm.$invalid=true;create()' ng-hide='isHide'  ng-disabled = "addauthorizationForm.$invalid"  id="Create"><?php echo AUTHORIZATION_CREATE_BUTTON_CREATE; ?></button>
					</div>
			</div>
		</div>
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
  LoadDataTablesScripts(AllTables);
 // WinMove();
	$("#EditAuthorizationDialogue, #AddAuthorizationDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	$("#EditAuthorizationDialogue, #AddAuthorizationDialogue").on("keypress",".sc", function (event) {
		    var regex = new RegExp("^[a-zA-Z0-9 \b:/.~!@#$*_-]+$");
 		    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
 		    if (!regex.test(key)) {
 		       event.preventDefault();
 		       return false;
 		    }
 		});
		/*  window.alert = function() {};
       alert = function() {}; */
});
</script>