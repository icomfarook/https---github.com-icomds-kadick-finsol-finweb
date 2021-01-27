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
<div ng-controller= "sergrpCtrl">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!mstsgp"><?php echo SERVICE_GROUP_MAIN_HEADING1; ?></a></li>
			<li><a href="#!mstsgp"><?php echo SERVICE_GROUP_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class='row'>
			<input type='button' style='float: right;margin-right: 2%;' class='btn btn-primary' value='<?php echo SERVICE_GROUP_MAIN_NEW_SERVICE_FEATURE; ?>' id='Create' href='#' data-toggle='modal' data-target='#AddservicegroupDialogue'/>
		</div>
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo SERVICE_GROUP_MAIN_HEADING3; ?></span>
				</div>
				
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding">		
                <div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>			
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>
							<th><?php echo SERVICE_GROUP_MAIN_TABLE_ID; ?></th>
      						<th><?php echo SERVICE_GROUP_MAIN_TABLE_NAME; ?></th>
							<th><?php echo SERVICE_GROUP_MAIN_TABLE_ACTIVE; ?></th>
							<th><?php echo SERVICE_GROUP_MAIN_TABLE_EDIT; ?></th>
						</tr>
					</thead>
					<tbody>
					      <tr ng-repeat="x in servicegrouplist">
						 	<td>{{ x.id }}</td>
							<td>{{ x.name }}</td>
							<td>{{ x.active }}</td>
							<td><a id={{x.id}}class='editservicegroup' ng-click='edit($index,x.id)' data-toggle='modal' data-target='#EditservicegroupDialogue'>
							<button id = '".$row['service_feature_id']."' class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
							</tr>
						</tbody>
					
				</table>
			</div>
		</div>
	</div>
	 <div id='EditservicegroupDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo SERVICE_GROUP_EDIT_HEADING1; ?> {{code}}</h2>
					</div>			
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
						<div class='modal-body'>
							<form action="" method="POST" name='editsergrpForm' id="EditsergrpForm">
								<div class='row' style='margin-top:2%'>
									<div id='EditsergrpBody'  ng-hide='isLoader'>																							
										<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
										<label> <?php echo SERVICE_GROUP_EDIT_NAME; ?><span class='spanre'>*</span><span ng-show="editsergrpForm.name.$touched ||editsergrpForm.name.$dirty && editsergrpForm.name.$invalid">
										<span class = 'err' ng-show="editsergrpForm.name.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<input type='text' maxlength='20' ng-trim="false"  spl-char-not restrict-field="name" name='name' required ng-model='name'  id='name' class='form-control'/>
										</div>					
							
										<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
											<label><?php echo SERVICE_GROUP_EDIT_ACTIVE; ?><span class='spanre'>*</span><span ng-show="editsergrpForm.active.$touched ||editsergrpForm.active.$dirty && editsergrpForm.active.$invalid">
											<span class = 'err' ng-show="editsergrpForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
												<select ng-model="active" class='form-control' name = 'active' id='Active' required >
													<option value=''><?php echo SERVICE_GROUP_EDIT_ACTIVE_SELECT; ?></option>
													<option value='Y'><?php echo SERVICE_GROUP_EDIT_ACTIVE_YES; ?></option>
													<option value='N'><?php echo SERVICE_GROUP_EDIT_ACTIVE_NO; ?></option>
												</select>
										</div>
									</div>
								</div>
								<div class='modal-footer'>
									<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo SERVICE_GROUP_EDIT_BUTTON_OK; ?></button>
									<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo SERVICE_GROUP_EDIT_BUTTON_CANCEL; ?></button>
									<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="editsergrpForm.$invalid" ng-click="editsergrpForm.$invalid=true;update(id)" id="Update"><?php echo SERVICE_GROUP_EDIT_BUTTON_UPDATE; ?></button>
								</div>
							</form>	
						</div>
					</div>	
				</div>
			</div>
						
	<div id='AddservicegroupDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo SERVICE_GROUP_CREATE_HEADING1;?></h2>
					</div>
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body' style='margin-top:2%'>
					 <form action="" method="POST" name='addsergrpForm' id="AddsergrpForm">
						<div id='sergrpCreateBody'  ng-hide='isLoader'>
							<div class='row' style='margin-top:2%'>	
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
									<label><?php echo SERVICE_GROUP_CREATE_NAME; ?><span class='spanre'>*</span><span ng-show="addsergrpForm.name.$touched ||addsergrpForm.name.$dirty && addsergrpForm.name.$invalid">
									<span class = 'err' ng-show="addsergrpForm.name.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<input ng-model="name" type='text' ng-trim="false"   name='name'  id='name' class='form-control' required />
								</div>
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
									<label><?php echo SERVICE_GROUP_CREATE_ACTIVE; ?><span class='spanre'>*</span><span ng-show="addsergrpForm.active.$touched ||addsergrpForm.active.$dirty && addsergrpForm.active.$invalid">
									<span class = 'err' ng-show="addsergrpForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<select ng-model="active" class='form-control' name = 'active' id='Active' required >
											<option value=''><?php echo SERVICE_GROUP_CREATE_ACTIVE_SELECT; ?></option>									
											<option value='Y'><?php echo SERVICE_GROUP_CREATE_ACTIVE_YES; ?></option>
											<option value='N'><?php echo SERVICE_GROUP_CREATE_ACTIVE_NO; ?></option>
										</select>
								</div>
						</div>
					</div>
				</form>	
			</div>
			<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo SERVICE_GROUP_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo SERVICE_GROUP_CREATE_BUTTON_CANCEL; ?></a>
					<button type="button" class="btn btn-primary" ng-click='addsergrpForm.$invalid=true;create()' ng-hide='isHide' ng-disabled = "addsergrpForm.$invalid"  id="Create"><?php echo SERVICE_GROUP_CREATE_BUTTON_CREATE; ?></button>
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

	$("#EditservicegroupDialogue, #AddservicegroupDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	  /* window.alert = function() {}; alert = function() {}; */
});
</script>
