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
#AddProfileDialogue .table > tbody > tr > td {
	border:none;
}
</style>
<div ng-controller='proCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!acspro"><?php echo PROFILE_MAIN_HEADING1; ?></a></li>
			<li><a href="#!acspro"><?php echo PROFILE_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class='row'>
			<input type='button' style='float: right;margin-right: 2%;' class='btn btn-primary' value='<?php echo PROFILE_MAIN_BUTTON_CREATE; ?>' id='Create'  data-toggle='modal' data-target='#AddProfileDialogue'/>
		</div>
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo PROFILE_MAIN_HEADING3; ?></span>
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
							<th><?php echo PROFILE_MAIN_TABLE_COUNTRY; ?></th>
      						<th><?php echo PROFILE_MAIN_TABLE_DESCRIPTION; ?></th>
							<th><?php echo PROFILE_MAIN_TABLE_ACTIVE; ?></th>
							<th><?php echo PROFILE_MAIN_TABLE_EDIT; ?></th>
						</tr>
					</thead>
					<tbody>
					 <tr ng-repeat="x in profilelist">
						 	<td>{{ x.profile }}</td>
							<td>{{ x.acode }}</td>
							<td>{{ x.active }}</td>
							<td><a id={{x.id}} class='editpro' ng-click='edit($index,x.id)' data-toggle='modal' data-target='#EditProfileDialogue'>
								<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
						</tr>
					</tbody>
				
				</table>
			</div>
		</div>
	</div>
	 <div id='EditProfileDialogue' class='modal fade' role='dialog' data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog modal-md">
			<div class="modal-content">
				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo PROFILE_EDIT_HEADING1; ?> {{code}}</h2>
					</div>					 
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>		 
					<div class='modal-body '>
					<form action="" method="POST"  name='editprofileForm' id="EditProfileForm">
					    <div id='proBody' ng-hide='isLoader'>
						<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label> <?php echo PROFILE_EDIT_CODE; ?><span ng-show="editprofileForm.code.$touched ||editprofileForm.code.$dirty && editprofileForm.code.$invalid">
								<span class = 'err' ng-show="editprofileForm.code.$error.required"><?php echo REQUIRED;?>.</span></span></label>
							<input type='text' ng-model='code' required name='code' maxlength='3' id='ProfileCode' class='form-control'/>
						</div>
						
						<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label><?php echo PROFILE_EDIT_NAME; ?><span ng-show="editprofileForm.profiledesc.$touched ||editprofileForm.profiledesc.$dirty && editprofileForm.profiledesc.$invalid">
								<span class = 'err' ng-show="editprofileForm.profiledesc.$error.required"><?php echo REQUIRED;?></span></span></label>
							<input type='text' ng-model='profiledesc' required  name='profiledesc' maxlength="15" id='ProfileName' class='form-control'/>
						</div>
						
						<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label><?php echo PROFILE_EDIT_ACTIVE; ?><span ng-show="editprofileForm.active.$touched ||editprofileForm.active.$dirty && editprofileForm.active.$invalid">
								<span class = 'err' ng-show="editprofileForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model='active' required class='form-control' name = 'active' id='Active'>											
									<option value='Y'><?php echo PROFILE_EDIT_ACTIVE_YES; ?></option>
									<option value='N'><?php echo PROFILE_EDIT_ACTIVE_NO; ?></option>
								</select>
						</div>

						<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label><?php echo PROFILE_EDIT_AUTHORIZATION; ?><span ng-show="editprofileForm.authorization.$touched ||editprofileForm.authorization.$dirty && editprofileForm.authorization.$invalid">
								<span class = 'err' ng-show="editprofileForm.authorization.$error.required"><?php echo REQUIRED;?>.</span></span></label>
							<select ng-model="authorization"  class='form-control' name = 'authorization' id='Authorization' required>											
								<option value=''><?php echo PROFILE_EDIT_AUTHORIZATION_SELECT; ?></option>
								<option ng-repeat="auth in auths" value="{{auth.id}}">{{auth.code}}</option>
							</select>
						</div>	
						<div class='clearfix'></div>
					</div>
					<div class='modal-footer'>
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo PROFILE_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo PROFILE_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="editAuthorizationForm.$invalid" ng-click="editprofileForm.$invalid=true;update(id)" id="Update"><?php echo PROFILE_EDIT_BUTTON_UPDATE; ?></button>
					</div>
				</form>	
			</div>
	</div></div>
	</div>		
	<div id='AddProfileDialogue' class='modal fade' role='dialog' ng-app="" data-backdrop="static" data-keyboard="false">
	 <div class="modal-dialog modal-md">
		<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 style='text-align:center'><?php echo PROFILE_CREATE_HEADING1;?></h2>
				</div>			
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>				
				<div class='modal-body'>
				<form action="" method="POST" name='addProfileForm' id="AddProfileForm">
				 <div  id='ProfileCreateBody'  ng-hide='isLoader'>
				 <div class='row' style='padding:0px 15px'>
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
						<label><?php echo PROFILE_CREATE_ID; ?><span ng-show="addProfileForm.id.$touched ||addProfileForm.id.$dirty && addProfileForm.id.$invalid">
								<span class = 'err' ng-show="addProfileForm.id.$error.required"><?php echo REQUIRED;?>.</span></span></label>
						<input ng-model="id" numbers-only type='text' name='id' maxlength='10' id='ProfileId' class='form-control' required />
					</div>
				
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
						<label><?php echo PROFILE_CREATE_CODE; ?><span ng-show="addProfileForm.code.$touched ||addProfileForm.code.$dirty && addProfileForm.code.$invalid">
								<span class = 'err' ng-show="addProfileForm.code.$error.required"><?php echo REQUIRED;?>.</span></span></label>
						<input ng-model="code" type='text' name='code' maxlength='3' id='ProfileCode' class='form-control' required />
					</div>
				</div>	
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
						<label><?php echo PROFILE_CREATE_NAME; ?><span ng-show="addProfileForm.profiledesc.$touched ||addProfileForm.profiledesc.$dirty && addProfileForm.profiledesc.$invalid">
								<span class = 'err' ng-show="addProfileForm.profiledesc.$error.required"><?php echo REQUIRED;?></span></span></label>
						<input type='text' ng-model="profiledesc" name='profiledesc' maxlength="15"  id='ProfileName' required class='form-control'/>
					</div>
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
						<label><?php echo PROFILE_CREATE_ACTIVE; ?><span ng-show="addProfileForm.active.$touched ||addProfileForm.active.$dirty && addProfileForm.active.$invalid">
								<span class = 'err' ng-show="addProfileForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="active" class='form-control' name = 'active' id='Active' required >											
									<option value=''><?php echo PROFILE_CREATE_ACTIVE_SELECT; ?></option>
									<option value='Y'><?php echo PROFILE_CREATE_ACTIVE_YES; ?></option>
									<option value='N'><?php echo PROFILE_CREATE_ACTIVE_NO; ?></option>
								</select>
						</div>
						<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 form_col12_element'>
							<label><?php echo PROFILE_CREATE_AUTHORIZATION; ?><span ng-show="addProfileForm.authorization.$touched ||addProfileForm.authorization.$dirty && addProfileForm.authorization.$invalid">
								<span class = 'err' ng-show="addProfileForm.authorization.$error.required"><?php echo REQUIRED;?>.</span></span></label>
							<select  ng-model="authorization"  class='form-control' name = 'authorization' id='Authorization' required>											
								<option value=''><?php echo PROFILE_CREATE_AUTHORIZATION_SELECT; ?></option>
								<option ng-repeat="auth in auths" value="{{auth.id}}">{{auth.code}}</option>
							</select>
						</div>
				</div>
				<div class='clearfix'></div>
				</form>	
				</div>
				<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo PROFILE_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo PROFILE_CREATE_BUTTON_CANCEL; ?></a>
					<button type="button" class="btn btn-primary" ng-click='addProfileForm.$invalid=true;create()' ng-hide='isHide' ng-disabled = "addProfileForm.$invalid"  id="Create"><?php echo PROFILE_CREATE_BUTTON_CREATE; ?></button>
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
	//LoadSelect2Script(MakeSelect2);
}
$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	//WinMove();
	$("#EditProfileDialogue, #AddProfileDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	$("#EditprofileDialogue, #AddprofileDialogue").on("keypress",".sc", function (event) {
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
