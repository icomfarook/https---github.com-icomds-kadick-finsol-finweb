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
<div ng-controller= "serFeMenuCtrl">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!mstsfm"><?php echo SERVICE_FEATURE_MENU_MAIN_HEADING1; ?></a></li>
			<li><a href="#!mstsfm"><?php echo SERVICE_FEATURE_MENU_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class='row'>
			<input type='button' style='float: right;margin-right: 2%;' class='btn btn-primary' value='<?php echo SERVICE_FEATURE_MENU_MAIN_NEW_SERVICE_FEATURE; ?>' id='Create' href='#' data-toggle='modal' data-target='#AddsserFeatMenuDialogue'/>
		</div>
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo SERVICE_FEATURE_MENU_MAIN_HEADING3; ?></span>
				</div>
				
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding">		
                <div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>			
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>
							<th><?php echo SERVICE_FEATURE_MENU_MAIN_TABLE_ID; ?></th>
							<th><?php echo SERVICE_FEATURE_MENU_MAIN_TABLE_PROFILE; ?></th>
							<th><?php echo SERVICE_FEATURE_MENU_MAIN_TABLE_SERGROUP; ?></th>
							<th><?php echo SERVICE_FEATURE_MENU_MAIN_TABLE_SERFEATURE; ?></th>
							<th><?php echo SERVICE_FEATURE_MENU_MAIN_TABLE_ACTIVE; ?></th>
							<th><?php echo SERVICE_FEATURE_MENU_MAIN_TABLE_EDIT; ?></th>
						</tr>
					</thead>
					<tbody>
					      <tr ng-repeat="x in servicefetmenulist">
						 	<td>{{ x.id }}</td>
							<td>{{ x.pname }}</td>
							<td>{{ x.sgname }}</td>
							<td>{{ x.sfeature }}</td>
							<td>{{ x.active }}</td>
							<td><a id={{x.id}}class='editservicegroup' ng-click='edit($index,x.id)' data-toggle='modal' data-target='#EditserFeatMenuDialogue'>
								<button  class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
							</tr>
						</tbody>
					
				</table>
			</div>
		</div>
	</div>
	 <div id='EditserFeatMenuDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo SERVICE_FEATURE_MENU_EDIT_HEADING1; ?> - {{id}}</h2>
					</div>			
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
						<div class='modal-body'>
							<form action="" method="POST" name='serFeaEditForm' id="serFeaEditForm">
								<div class='row' style='margin-top:2%'>
									<div id='EditserfetmenuBody'  ng-hide='isLoader'>																							
										 <div class='row' style='padding:10px 15px'>	
											<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
												<label><?php echo SERVICE_FEATURE_MENU_EDIT_PROFILE; ?><span class='spanre'>*</span><span ng-show="serFeaEditForm.profile.$touched ||serFeaEditForm.profile.$dirty && serFeaEditForm.profile.$invalid">
												<span class = 'err'  ng-hide = "isMsgSpan" ng-show="serFeaEditForm.profile.$error.required"><?php echo REQUIRED;?></span></span></label>
												<select ng-disabled = 'isSelectDisabled' ng-model="profile" class='form-control' name = 'profile' id='profile' required>											
													<option value=""><?php echo SERVICE_FEATURE_MENU_EDIT_SELECT_PROFILE; ?></option>
													<option ng-repeat="profile in profiles" value="{{profile.id}}">{{profile.name}}</option>
												</select>
											</div>	

											<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
												<label><?php echo SERVICE_FEATURE_MENU_EDIT_SERVICE_GROUP; ?><span class='spanre'>*</span><span ng-show="serFeaEditForm.sergrp.$touched ||serFeaEditForm.sergrp.$dirty && serFeaEditForm.sergrp.$invalid">
												<span class = 'err'  ng-hide = "isMsgSpan" ng-show="serFeaEditForm.sergrp.$error.required"><?php echo REQUIRED;?></span></span></label>
												<select ng-disabled = 'isSelectDisabled' ng-model="sergrp" class='form-control' name = 'sergrp' id='sergrp' required>											
													<option value=""><?php echo SERVICE_FEATURE_MENU_EDIT_SELECT_SERVICE_GROUP; ?></option>
													<option ng-repeat="sergrp in sergrps" value="{{sergrp.id}}">{{sergrp.name}}</option>
												</select>
											</div>		
										</div>
										<div class='row' style='padding:10px 15px'>	
											<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
												<label><?php echo SERVICE_FEATURE_MENU_EDIT_SERVICE_FEATURE; ?><span class='spanre'>*</span><span ng-show="serFeaEditForm.serfet.$touched ||serFeaEditForm.serfet.$dirty && serFeaEditForm.serfet.$invalid">
												<span class = 'err'  ng-hide = "isMsgSpan" ng-show="serFeaEditForm.serfet.$error.required"><?php echo REQUIRED;?></span></span></label>
												<select ng-disabled = 'isSelectDisabled' ng-model="serfet" class='form-control' name = 'serfet' id='serfet' required>											
													<option value=""><?php echo SERVICE_FEATURE_MENU_EDIT_SELECT_SERVICE_FEATURE; ?></option>
													<option ng-repeat="servfea in servfeas" value="{{servfea.id}}">{{servfea.name}}</option>
												</select>
											</div>						
											<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
												<label><?php echo SERVICE_FEATURE_MENU_EDIT_ACTIVE; ?><span class='spanre'>*</span><span ng-show="serFeaEditForm.active.$touched ||serFeaEditForm.active.$dirty && serFeaEditForm.active.$invalid">
												<span class = 'err' ng-show="serFeaEditForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
													<select ng-model="active" class='form-control' name = 'active' id='Active' required >
														<option value=''><?php echo SERVICE_FEATURE_MENU_EDIT_ACTIVE_SELECT; ?></option>
														<option value='Y'><?php echo SERVICE_FEATURE_MENU_EDIT_ACTIVE_YES; ?></option>
														<option value='N'><?php echo SERVICE_FEATURE_MENU_EDIT_ACTIVE_NO; ?></option>
													</select>
											</div>
										</div>
										<div class='row' style='padding:10px 15px'>	
											<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12'>
												<label><?php echo SERVICE_FEATURE_MENU_EDIT_SERVICE_PRIORITY; ?><span class='spanre'>*</span><span ng-show="serFeaEditForm.crepriority.$dirty && serFeaEditForm.crepriority.$invalid">
												<span class = 'err'  ng-hide = "isMsgSpan" ng-message="required"><?php echo REQUIRED;?>.</span></span></label><span style="color:Red" ng-show="serFeaEditForm.crepriority.$dirty && serFeaEditForm.crepriority.$error.required"> </span>
												<input ng-model="crepriority" numbers-only type='text' id='crepriority'  maxlength='11' name='crepriority' required class='form-control'/>
											</div>
										</div>
								</div>
								<div class='modal-footer'>
									<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo SERVICE_FEATURE_MENU_EDIT_BUTTON_OK; ?></button>
									<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo SERVICE_FEATURE_MENU_EDIT_BUTTON_CANCEL; ?></button>
									<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="serFeaEditForm.$invalid" ng-click="serFeaEditForm.$invalid=true;update(id)" id="Update"><?php echo SERVICE_FEATURE_MENU_EDIT_BUTTON_UPDATE; ?></button>
								</div>
							</form>	
						</div>
					</div>	
				</div>
			</div>
			</div>			
	<div id='AddsserFeatMenuDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo SERVICE_FEATURE_MENU_CREATE_HEADING1;?></h2>
					</div>
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
							<form action="" method="POST" name='serFeaMenuCreateForm' id="serFeaMenuCreateForm">
								<div class='row' style='margin-top:2%'>
									<div id='CreateserfetmenuBody'  ng-hide='isLoader'>																							
										 <div class='row' style='padding:10px 15px'>	
											<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
												<label><?php echo SERVICE_FEATURE_MENU_CREATE_PROFILE; ?><span class='spanre'>*</span><span ng-show="serFeaMenuCreateForm.profile.$touched ||serFeaMenuCreateForm.profile.$dirty && serFeaMenuCreateForm.profile.$invalid">
												<span class = 'err'  ng-hide = "isMsgSpan" ng-show="serFeaMenuCreateForm.profile.$error.required"><?php echo REQUIRED;?></span></span></label>
												<select ng-disabled = 'isSelectDisabled' ng-model="profile" class='form-control' name = 'profile' id='profile' required>											
													<option value=""><?php echo SERVICE_FEATURE_MENU_CREATE_SELECT_PROFILE; ?></option>
													<option ng-repeat="profile in profiles" value="{{profile.id}}">{{profile.name}}</option>
												</select>
											</div>	

											<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
												<label><?php echo SERVICE_FEATURE_MENU_CREATE_SERVICE_GROUP; ?><span class='spanre'>*</span><span ng-show="serFeaMenuCreateForm.cresrg.$touched ||serFeaMenuCreateForm.cresrg.$dirty && serFeaMenuCreateForm.cresrg.$invalid">
												<span class = 'err'  ng-hide = "isMsgSpan" ng-show="serFeaMenuCreateForm.cresrg.$error.required"><?php echo REQUIRED;?></span></span></label>
												<select ng-disabled = 'isSelectDisabled' ng-model="cresrg" class='form-control' name = 'cresrg' id='cresrg' required>											
													<option value=""><?php echo SERVICE_FEATURE_MENU_CREATE_SELECT_SERVICE_GROUP; ?></option>
													<option ng-repeat="cresrg in sergrps" value="{{cresrg.id}}">{{cresrg.name}}</option>
												</select>
											</div>		
										</div>		
										 <div class='row' style='padding:10px 15px'>	
											<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
												<label><?php echo SERVICE_FEATURE_MENU_CREATE_SERVICE_FEATURE; ?><span class='spanre'>*</span><span ng-show="serFeaMenuCreateForm.creserfet.$touched ||serFeaMenuCreateForm.creserfet.$dirty && serFeaMenuCreateForm.creserfet.$invalid">
												<span class = 'err'  ng-hide = "isMsgSpan" ng-show="serFeaMenuCreateForm.creserfet.$error.required"><?php echo REQUIRED;?></span></span></label>
												<select ng-disabled = 'isSelectDisabled' ng-model="creserfet" class='form-control' name = 'creserfet' id='creserfet' required>											
													<option value=""><?php echo SERVICE_FEATURE_MENU_CREATE_SELECT_SERVICE_FEATURE; ?></option>
													<option ng-repeat="servfea in servfeas" value="{{servfea.id}}">{{servfea.name}}</option>
												</select>
											</div>						
											<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
												<label><?php echo SERVICE_FEATURE_MENU_CREATE_ACTIVE; ?><span class='spanre'>*</span><span ng-show="serFeaMenuCreateForm.active.$touched ||serFeaMenuCreateForm.active.$dirty && serFeaMenuCreateForm.active.$invalid">
												<span class = 'err' ng-show="serFeaMenuCreateForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
													<select ng-model="active" class='form-control' name = 'active' id='Active' required >
														<option value=''><?php echo SERVICE_FEATURE_MENU_EDIT_CREATE_SELECT; ?></option>
														<option value='Y'><?php echo SERVICE_FEATURE_MENU_CREATE_ACTIVE_YES; ?></option>
														<option value='N'><?php echo SERVICE_FEATURE_MENU_CREATE_ACTIVE_NO; ?></option>
													</select>
											</div>
										</div>		
										 <div class='row' style='padding:10px 15px'>	
											<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12'>
											<label><?php echo SERVICE_FEATURE_MENU_CREATE_SERVICE_PRIORITY; ?><span class='spanre'>*</span><span ng-show="serFeaMenuCreateForm.crepriority.$dirty && serFeaMenuCreateForm.crepriority.$invalid">
											<span class = 'err'  ng-hide = "isMsgSpan" ng-message="required"><?php echo REQUIRED;?>.</span></span></label><span style="color:Red" ng-show="serFeaMenuCreateForm.crepriority.$dirty && serFeaMenuCreateForm.crepriority.$error.required"> </span>
											<input ng-model="crepriority" numbers-only type='text'  id='crepriority'  maxlength='11' name='crepriority' required class='form-control'/>
											</div>	
										</div>
								</div>
								
							</form>	
						</div>
					</div>	
			<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo SERVICE_FEATURE_MENU_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo SERVICE_FEATURE_MENU_CREATE_BUTTON_CANCEL; ?></a>
					<button type="button" class="btn btn-primary" ng-click='serFeaMenuCreateForm.$invalid=true;create()' ng-hide='isHide' ng-disabled = "serFeaMenuCreateForm.$invalid"  id="Create"><?php echo SERVICE_FEATURE_MENU_CREATE_BUTTON_CREATE; ?></button>
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

	$("#EditserFeatMenuDialogue, #AddsserFeatMenuDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
/* 	 window.alert = function() {};
	alert = function() {}; */
	
});
</script>
