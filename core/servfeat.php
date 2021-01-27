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
<div ng-controller= "servfeatCtrl">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!ratfea"><?php echo SERVICE_FEATURE_MAIN_HEADING1; ?></a></li>
			<li><a href="#!ratfea"><?php echo SERVICE_FEATURE_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class='row'>
			<input type='button' style='float: right;margin-right: 2%;' class='btn btn-primary' value='<?php echo SERVICE_FEATURE_MAIN_NEW_SERVICE_FEATURE; ?>' id='Create' href='#' data-toggle='modal' data-target='#AddServicefeatureDialogue'/>
		</div>
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo SERVICE_FEATURE_MAIN_HEADING3; ?></span>
				</div>
				
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding">		
                <div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>			
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>
							<th><?php echo SERVICE_FEATURE_MAIN_TABLE_ID; ?></th>
      						<th><?php echo SERVICE_FEATURE_MAIN_TABLE_CODE; ?></th>
							<th><?php echo SERVICE_FEATURE_MAIN_TABLE_DESCRIPTION; ?></th>
							<th><?php echo SERVICE_FEATURE_MAIN_TABLE_TYPE_ID; ?></th>
							<th><?php echo SERVICE_FEATURE_MAIN_TABLE_ACTIVE; ?></th>
							<th><?php echo SERVICE_FEATURE_MAIN_TABLE_EDIT; ?></th>
						</tr>
					</thead>
					<tbody>
					      <tr ng-repeat="x in servicefeaturelist">
						 	<td>{{ x.id }}</td>
							<td>{{ x.code }}</td>
							<td>{{ x.desc }}</td>
							<td>{{ x.typeid }}</td>
							<td>{{ x.active }}</td>
							<td><a id={{x.id}}class='editservicefeature' ng-click='edit($index,x.id)' data-toggle='modal' data-target='#EditservicefeatureDialogue'>
							<button id = '".$row['service_feature_id']."' class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
							</tr>
						</tbody>
					
				</table>
			</div>
		</div>
	</div>
	 <div id='EditservicefeatureDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
						<button type="button" ng-click='restric()' class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo SERVICE_FEATURE_EDIT_HEADING1; ?> {{code}}</h2>
					</div>			
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					<form action="" method="POST" name='editservfeatForm' id="EditservfeatForm">
						<div class='row'>
						<div id='ServfeatBody'  ng-hide='isLoader'>
						<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label> <?php echo SERVICE_FEATURE_EDIT_ID; ?><span class='spanre'>*</span><span ng-show="editservfeatForm.id.$touched ||editservfeatForm.id.$dirty && editservfeatForm.id.$invalid">
								<span class = 'err' ng-show="editservfeatForm.id.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input type='text'  name='id' required ng-model='id' maxlength='10' id='servicefeatureid' class='form-control'/>
							</div>
																								
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label> <?php echo SERVICE_FEATURE_EDIT_CODE; ?><span class='spanre'>*</span><span ng-show="editservfeatForm.code.$touched ||editservfeatForm.code.$dirty && editservfeatForm.code.$invalid">
								<span class = 'err' ng-show="editservfeatForm.code.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input type='text'  name='code' spl-char-not ng-trim="false"  restrict-field="code" required ng-model='code' maxlength='3' id='featurecode' class='form-control'/>
							</div>
							
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label><?php echo SERVICE_FEATURE_EDIT_DESCRIPTION; ?><span class='spanre'>*</span><span ng-show="editservfeatForm.description.$touched ||editservfeatForm.description.$dirty && editservfeatForm.description.$invalid">
								<span class = 'err' ng-show="editservfeatForm.description.$error.required"><?php echo REQUIRED;?></span></span></label>
								 <input type='text' name='description' required ng-model='description' maxlength='20' id='featuredescription' class='form-control'/>	
							</div>
					
						
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label><?php echo SERVICE_FEATURE_EDIT_ACTIVE; ?><span class='spanre'>*</span><span ng-show="editservfeatForm.active.$touched ||editservfeatForm.active.$dirty && editservfeatForm.active.$invalid">
								<span class = 'err' ng-show="editservfeatForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<select ng-model="active" class='form-control' name = 'active' id='Active' required >
									    <option value=''><?php echo SERVICE_FEATURE_EDIT_ACTIVE_SELECT; ?></option>
                               			<option value='Y'><?php echo SERVICE_FEATURE_EDIT_ACTIVE_YES; ?></option>
										<option value='N'><?php echo SERVICE_FEATURE_EDIT_ACTIVE_NO; ?></option>
									</select>
							</div>
							
						<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label><?php echo SERVICE_FEATURE_EDIT_SERVICE_TYPE_ID; ?><span class='spanre'>*</span><span ng-show="editservfeatForm.servicetype.$touched ||editservfeatForm.servicetype.$dirty && editservfeatForm.servicetype.$invalid">
								<span class = 'err' ng-show="editservfeatForm.servicetype.$error.required"><?php echo REQUIRED;?>.</span></span></label>
							<select ng-model="servicetype"  class='form-control' name = 'servicetype' id='typeid' required>											
								<option value=''><?php echo SERVICE_FEATURE_EDIT_SERVICE_TYPE_SELECT; ?></option>
								<option ng-repeat="x in servfeat" value="{{x.id}}">{{x.name}}</option>
							</select>
						</div>
						<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label>Priority.<span class='spanre'>*</span><span ng-show="editservfeatForm.priority.$touched ||editservfeatForm.priority.$dirty && editservfeatForm.priority.$invalid">
								<span class = 'err' ng-show="editserCharGrpForm.priority.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								 <input  numbers-only type='text' name='priority' required ng-model='priority'  ng-trim="false"  restrict-field="priority" maxlength='10' id='priority' class='form-control'/>
							</div>								

						<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 form_col12_element'>
								<label><?php echo SERVICE_FEATURE_EDIT_LINK_NAME; ?><span class='spanre'>*</span><span ng-show="editservfeatForm.linkname.$touched ||editservfeatForm.linkname.$dirty && editservfeatForm.linkname.$invalid">
								<span class = 'err' ng-show="editservfeatForm.linkname.$error.required"><?php echo REQUIRED;?></span></span></label>
								 <input type='text' name='linkname' required ng-model="linkname" maxlength='20' id='featurelinkname' class='form-control'/>	
							</div>		
												
						
		               </div></div>
	         		<div class='modal-footer'>
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo SERVICE_FEATURE_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' ng-click='restric()' data-dismiss='modal' ng-hide='isHide' ><?php echo SERVICE_FEATURE_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="editservfeatForm.$invalid" ng-click="editservfeatForm.$invalid=true;update(id)" id="Update"><?php echo SERVICE_FEATURE_EDIT_BUTTON_UPDATE; ?></button>
					</div>
		</form>	
	</div>
	</div>	
</div></div>
						
	<div id='AddServicefeatureDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo SERVICE_FEATURE_CREATE_HEADING1;?></h2>
					</div>
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body' >
					 <form action="" method="POST" name='addservfeatForm' id="AddservfeatForm">
						<div id='ServfeatCreateBody'  ng-hide='isLoader'>
							<div class='row' >	
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label><?php echo SERVICE_FEATURE_CREATE_CODE; ?><span class='spanre'>*</span><span ng-show="addservfeatForm.code.$touched ||addservfeatForm.code.$dirty && addservfeatForm.code.$invalid">
								<span class = 'err' ng-show="addservfeatForm.code.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input ng-model="code" type='text' spl-char-not ng-trim="false"  restrict-field="code" name='code' maxlength='3' id='code' class='form-control' required />
							</div>
						
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element '>
								<label><?php echo SERVICE_FEATURE_CREATE_DESCRIPTION; ?><span class='spanre'>*</span><span ng-show="addservfeatForm.description.$touched ||addservfeatForm.description.$dirty && addservfeatForm.description.$invalid">
								<span class = 'err' ng-show="addservfeatForm.description.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input ng-model="description" type='text' name='description' maxlength='20' id='featuredescription' class='form-control' required />
							</div>
						
						
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label><?php echo SERVICE_FEATURE_CREATE_SERVICE_TYPE_ID; ?><span class='spanre'>*</span><span ng-show="editservfeatForm.servicetype.$touched ||editservfeatForm.servicetype.$dirty && editservfeatForm.servicetype.$invalid">
								<span class = 'err' ng-show="editservfeatForm.servicetype.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="servicetype"  class='form-control' name = 'servicetype' id='typeid' required>											
									<option value=''><?php echo SERVICE_FEATURE_CREATE_SERVICE_TYPE_SELECT; ?></option>
									<option ng-repeat="x in servfeat" value="{{x.id}}">{{x.name}}</option>
								</select>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label><?php echo SERVICE_FEATURE_CREATE_ACTIVE; ?><span class='spanre'>*</span><span ng-show="addservfeatForm.active.$touched ||addservfeatForm.active.$dirty && addservfeatForm.active.$invalid">
								<span class = 'err' ng-show="addservfeatForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<select ng-model="active" class='form-control' name = 'active' id='Active' required >
                                        <option value=''><?php echo SERVICE_FEATURE_CREATE_ACTIVE_SELECT; ?></option>									
										<option value='Y'><?php echo SERVICE_FEATURE_CREATE_ACTIVE_YES; ?></option>
										<option value='N'><?php echo SERVICE_FEATURE_CREATE_ACTIVE_NO; ?></option>
									</select>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label><?php echo SERVICE_FEATURE_CREATE_LINK_NAME; ?><span class='spanre'>*</span><span ng-show="editservfeatForm.linkname.$touched ||editservfeatForm.linkname.$dirty && editservfeatForm.linkname.$invalid">
								<span class = 'err' ng-show="editservfeatForm.linkname.$error.required"><?php echo REQUIRED;?></span></span></label>
								 <input type='text' name='linkname' required ng-model="linkname" maxlength='20' id='featurelinkname' class='form-control'/>	
							</div>	
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label>Priority.<span class='spanre'>*</span><span ng-show="addservfeatForm.priority.$touched ||addservfeatForm.priority.$dirty && addservfeatForm.priority.$invalid">
								<span class = 'err' ng-show="addservfeatForm.priority.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								 <input  numbers-only type='text' name='priority' required ng-model='priority'  ng-trim="false"  restrict-field="priority" maxlength='10' id='priority' class='form-control'/>
							</div>			
					</div>
			</div>
			 </form>	
			    </div>
			<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo SERVICE_FEATURE_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo SERVICE_FEATURE_CREATE_BUTTON_CANCEL; ?></a>
					<button type="button" class="btn btn-primary" ng-click='addservfeatForm.$invalid=true;create()' ng-hide='isHide' ng-disabled = "addservfeatForm.$invalid"  id="Create"><?php echo SERVICE_FEATURE_CREATE_BUTTON_CREATE; ?></button>
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
	//LoadSelect2Script(MakeSelect2);
}
$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	//WinMove();

	$("#EditservicefeatureDialogue, #AddServicefeatureDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	
});
</script>
