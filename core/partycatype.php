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
<div ng-controller= "partycatypeCtrl">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!ptncat"><?php echo PARTY_CATEGORY_TYPE_MAIN_HEADING1; ?></a></li>
			<li><a href="#!ptncat"><?php echo PARTY_CATEGORY_TYPE_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class='row'>
			<input type='button' style='float: right;margin-right: 2%;' class='btn btn-primary' value='<?php echo PARTY_CATEGORY_TYPE_MAIN_CREATE; ?>' id='Create' href='#' data-toggle='modal' data-target='#AddPartycatypeDialogue'/>
		</div>
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo PARTY_CATEGORY_TYPE_MAIN_HEADING3; ?></span>
				</div>
				
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding">		
                <div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>			
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>							
							<th><?php echo PARTY_CATEGORY_TYPE_MAIN_TABLE_NAME; ?></th>
							<th><?php echo PARTY_CATEGORY_TYPE_MAIN_TABLE_ACTIVE; ?></th>
							<th><?php echo PARTY_CATEGORY_TYPE_MAIN_TABLE_EDIT; ?></th>
						</tr>
					</thead>
					<tbody>
					      <tr ng-repeat="x in parcttylis">
							<td>{{ x.name }}</td>
							<td>{{ x.active }}</td>
							<td><a id={{x.id}} class='editPartycattype' ng-click='edit($index,x.id)' data-toggle='modal' data-target='#EditPartycatypeDialogue'>
							<button  class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
							</tr>
						</tbody>
					
				</table>
			</div>
		</div>
	</div>
	 <div id='EditPartycatypeDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo PARTY_CATEGORY_TYPE_EDIT_HEADING1; ?> -  {{name}}</h2>
					</div>	
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					 <form action="" method="POST" name='editpartycatypeForm' id="EditpartycatypeForm">
						<div id='partycatypeBody'  ng-hide='isLoader'>
						<div class='row' style='margin-top:2%'>
							
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
								<label> <?php echo PARTY_CATEGORY_TYPE_EDIT_NAME; ?><span class='spanre'>*</span><span ng-show="addpartycatypeForm.name.$touched ||addpartycatypeForm.name.$dirty && addpartycatypeForm.name.$invalid">
								<span class = 'err' ng-show="addpartycatypeForm.name.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input type='text' ng-trinm='false' maxlength='10' spl-char-not restrict-field="name" name='name' required ng-model='name'  id='name' class='form-control'/>
							</div>									
							
							
						
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
								<label><?php echo PARTY_CATEGORY_TYPE_EDIT_ACTIVE; ?><span ng-show="editpartycatypeForm.active.$touched ||editpartycatypeForm.active.$dirty && editpartycatypeForm.active.$invalid">
								<span class = 'err' ng-show="editpartycatypeForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<select ng-model="active" class='form-control' name = 'active' id='Active' required >
                               			<option value='Y'><?php echo PARTY_CATEGORY_TYPE_EDIT_ACTIVE_YES; ?></option>
										<option value='N'><?php echo PARTY_CATEGORY_TYPE_EDIT_ACTIVE_NO; ?></option>
									</select>
							</div>
						</div>
					</div>
	         		<div class='modal-footer'>
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo PARTY_CATEGORY_TYPE_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo PARTY_CATEGORY_TYPE_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="editpartycatypeForm.$invalid" ng-click="editpartycatypeForm.$invalid=true;update(id)" id="Update"><?php echo PARTY_CATEGORY_TYPE_EDIT_BUTTON_UPDATE; ?></button>
					</div>
		</form>	
	</div>
	</div>	
</div></div>
						
	<div id='AddPartycatypeDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo PARTY_CATEGORY_TYPE_CREATE_HEADING1;?></h2>
					</div>	
					 <div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body' style='margin-top:2%'>
					 <form action="" method="POST" name='addpartycatypeForm' id="AddpartycatypeForm">
						<div id='PartycatypeCreateBody'  ng-hide='isLoader'>
							<div class='row' style='margin-top:2%'>
								
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
								<label> <?php echo PARTY_CATEGORY_TYPE_CREATE_NAME; ?><span class='spanre'>*</span><span ng-show="addpartycatypeForm.name.$touched ||addpartycatypeForm.name.$dirty && addpartycatypeForm.name.$invalid">
								<span class = 'err' ng-show="addpartycatypeForm.name.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input type='text' ng-trinm='false' maxlength='10' spl-char-not restrict-field="name" name='name' required ng-model='name'  id='name' class='form-control'/>
							</div>	
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
								<label><?php echo PARTY_CATEGORY_TYPE_CREATE_ACTIVE; ?><span ng-show="addpartycatypeForm.active.$touched ||addpartycatypeForm.active.$dirty && addpartycatypeForm.active.$invalid">
								<span class = 'err' ng-show="addpartycatypeForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<select ng-model="active" class='form-control' name = 'active' id='Active' required >
                               			<option value=''><?php echo PARTY_CATEGORY_TYPE_CREATE_SELECT_ACTIVE; ?></option>
										<option value='Y'><?php echo PARTY_CATEGORY_TYPE_EDIT_ACTIVE_YES; ?></option>
										<option value='N'><?php echo PARTY_CATEGORY_TYPE_EDIT_ACTIVE_NO; ?></option>
									</select>
							</div>
						</div></div>
						</form>	</div>
			<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo PARTY_CATEGORY_TYPE_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo PARTY_CATEGORY_TYPE_CREATE_BUTTON_CANCEL; ?></a>
					<button type="button" class="btn btn-primary" ng-click='addpartycatypeForm.$invalid=true;create()' ng-hide='isHide' ng-disabled = "addpartycatypeForm.$invalid"  id="Create"><?php echo PARTY_CATEGORY_TYPE_CREATE_BUTTON_CREATE; ?></button>
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
	LoadSelect2Script(MakeSelect2);
}

$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	//WinMove();

	$("#EditPartycatypeDialogue, #AddPartycatypeDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	/* window.alert = function() {};
     alert = function() {}; */
	
});
</script>

 
 