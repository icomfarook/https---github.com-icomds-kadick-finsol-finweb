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
#AddBlckreasonDialogue .table > tbody > tr > td {
	border:none;
}
</style>
<div ng-controller= "blckreasonCtrl">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!acsrsn"><?php echo BLOCK_REASON_MAIN_HEADING1; ?></a></li>
			<li><a href="#!acsrsn"><?php echo BLOCK_REASON_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class='row'>
			<input type='button' style='float: right;margin-right: 2%;' class='btn btn-primary' value='<?php echo BLOCK_REASON_MAIN_NEW_BLOCK_REASON; ?>' id='Create' href='#' data-toggle='modal' data-target='#AddBlckreasonDialogue'/>
		</div>
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo BLOCK_REASON_MAIN_HEADING3; ?></span>
				</div>
				
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding">		
                			<div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>			
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>
							<th><?php echo BLOCK_REASON_MAIN_TABLE_ID; ?></th>
      						<th><?php echo BLOCK_REASON_MAIN_TABLE_CODE; ?></th>
							<th><?php echo BLOCK_REASON_MAIN_TABLE_DESCRIPTION; ?></th>
							<th><?php echo BLOCK_REASON_MAIN_TABLE_ACTION; ?></th>
						</tr>
					</thead>
					<tbody>
					      <tr ng-repeat="x in blockreasonlist">
						   <td>{{ x.id }}</td>
						 	<td>{{ x.code }}</td>
							<td>{{ x.desc }}</td>
							<td><a id={{x.id}}class='editblckreason' ng-click='edit($index,x.id)' data-toggle='modal' data-target='#EditBlckreasonDialogue'>
							<button id = '".$row['block_reason_id']."' class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
							</tr>
						</tbody>
					
				</table>
			</div>
		</div>
	</div>
	 <div id='EditBlckreasonDialogue' class='modal fade' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo BLOCK_REASON_EDIT_HEADING1; ?> {{code}}</h2>
					</div>	
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>					
					<div class='modal-body'>
					<form action="" method="POST" name='editblckreasonform' id="EditblckreasonForm">
						<div id='BlckreasonBody'  ng-hide='isLoader'>
						     <div class='col-xs-12 col-md-12 col-lg-12 col-sm-12'>
								<label> <?php echo BLOCK_REASON_EDIT_ID; ?><span ng-show="editblckreasonform.id.$touched ||editblckreasonform.id.$dirty && editblckreasonform.id.$invalid">
								<span class = 'err' ng-show="editblckreasonform.id.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input type='text' name='id' required ng-model='id' maxlength='3' id='blockreasonid' class='form-control'/>
							</div>
							
							<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12'>
								<label> <?php echo BLOCK_REASON_EDIT_CODE; ?><span ng-show="editblckreasonform.code.$touched ||editblckreasonform.code.$dirty && editblckreasonform.code.$invalid">
								<span class = 'err' ng-show="editblckreasonform.code.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input type='text' name='code' required ng-model='code' maxlength='3' id='blockreasoncode' class='form-control'/>
							</div>
							
							<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12'>
								<label><?php echo BLOCK_REASON_EDIT_DESCRIPTION; ?><span ng-show="editblckreasonform.description.$touched ||editblckreasonform.description.$dirty && editblckreasonform.description.$invalid">
								<span class = 'err' ng-show="editblckreasonform.description.$error.required"><?php echo REQUIRED;?></span></span></label>
								 <input type='text' name='description' required ng-model='description' maxlength='50' id='blckreasondescription' class='form-control'/>	
							</div>
							<div class='clearfix'></div>
							</div>
	         		<div class='modal-footer'>
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo BLOCK_REASON_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo BLOCK_REASON_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="editblckreasonform.$invalid" ng-click="editblckreasonform.$invalid=true;update(id)" id="Update"><?php echo BLOCK_REASON_EDIT_BUTTON_UPDATE; ?></button>
					</div>
		</form>	
	</div>
	</div>	
</div></div>
						
	<div id='AddBlckreasonDialogue' class='modal fade' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo BLOCK_REASON_CREATE_HEADING1;?></h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>					
					<div class='modal-body' style='margin-top:2%'>
					<form action="" method="POST" name='addBlckreasonForm' id="AddBlckreasonForm">
						<div id='BlckreasonCreateBody'  ng-hide='isLoader'>
						     <div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 '>
								<label><?php echo BLOCK_REASON_CREATE_ID; ?><span ng-show="addBlckreasonForm.id.$touched ||addBlckreasonForm.id.$dirty && addBlckreasonForm.id.$invalid">
								<span class = 'err' ng-show="addBlckreasonForm.id.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input ng-model="id" type='text' name='id' maxlength='10' id='id' class='form-control' required />
							</div>
						
							<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 '>
								<label><?php echo BLOCK_REASON_CREATE_CODE; ?><span ng-show="addBlckreasonForm.code.$touched ||addBlckreasonForm.code.$dirty && addBlckreasonForm.code.$invalid">
								<span class = 'err' ng-show="addBlckreasonForm.code.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input ng-model="code" type='text' name='code' maxlength='3' id='code' class='form-control' required />
							</div>
						
							<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 '>
								<label><?php echo BLOCK_REASON_CREATE_DESCRIPTION; ?><span ng-show="addBlckreasonForm.description.$touched ||addBlckreasonForm.description.$dirty && addBlckreasonForm.description.$invalid">
								<span class = 'err' ng-show="addBlckreasonForm.description.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="description" type='text' name='description' maxlength='15' id='blckreasondescription' class='form-control' required />
							</div>	
						 <div class='clearfix'></div>
			</div>
			</form>
			</div>
			<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo BLOCK_REASON_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo BLOCK_REASON_CREATE_BUTTON_CANCEL; ?></a>
					<button type="button" class="btn btn-primary" ng-click='addBlckreasonForm.$invalid=true;create()' ng-hide='isHide' ng-disabled = "addBlckreasonForm.$invalid"  id="Create"><?php echo BLOCK_REASON_CREATE_BUTTON_CREATE; ?></button>
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

	$("#EditBlckreasonDialogue, #AddBlckreasonDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	/*  window.alert = function() {};
     alert = function() {}; */
});
</script>
