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
<div ng-controller='AccServiceBankCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!accservice"><?php echo STATE_MAIN_HEADING1; ?></a></li>
			<li><a href="#!accservice">Account Service Bank</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Account Service Bank</span>
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
							<th>Bank Master Id</th>
      						<th>CBN Short Code</th>
							<th>Bank Name</th>
							<th>Access Service Flag</th>
							<th><?php echo STATE_MAIN_TABLE_EDIT; ?></th>
						</tr>
					</thead>
					<tbody>
					 <tr ng-repeat="x in accservicelist">
						 	<td>{{ x.bankID }}</td>
							<td>{{ x.cbn }}</td>
							<td>{{ x.name }}</td>
							<td>{{ x.accservice }}</td>
							<td><a id={{x.id}} class='editpro' ng-click='edit($index,x.id)' data-toggle='modal' data-target='#EditAccserviceDialogue'>
								<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
						</tr>
					</tbody>
				
				</table>
			</div>
		</div>
	</div>

	 <div id='EditAccserviceDialogue' class='modal fade' role='dialog' data-backdrop="static" data-keyboard="false" >
		 <div class="modal-dialog modal-md">
			<div class="modal-content">
				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Access Service Bank #{{bankID}}</h2>
					</div>					 
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>						 
					<div class="modal-body">		
						<form action="" method="POST"  name='editaccserviceForm' id="EditAccserviceDialogue">				
						<div id='AccserviceBody'  ng-hide='isLoader'>						
							<div class='row' style='padding:0px 15px'>
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
									<label>Bank Master Id<span ng-show="editstateForm.bankID.$touched ||editstateForm.bankID.$dirty && editstateForm.bankID.$invalid">
										<span class = 'err' ng-show="editaccserviceForm.bankID.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input type='text' ng-trim="false"  spl-char-not restrict-field="bankID"  readonly='true' ng-disabled = 'isSelectDisabled' ng-model='bankID' required  name='bankID' maxlength="15" id='bankID' class='form-control'/>
								</div>
								
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
									<label>CBN Short Code<span ng-show="editaccserviceForm.cbn.$touched ||editaccserviceForm.cbn.$dirty && editaccserviceForm.cbn.$invalid">
										<span class = 'err' ng-show="editaccserviceForm.cbn.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input type='text' ng-trim="false"ng-disabled = 'isSelectDisabled' readonly='true' spl-char-not restrict-field="cbn" ng-model='cbn' required  name='cbn' maxlength="15" id='cbn' class='form-control'/>
								</div>

								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
									<label>Bank Name<span ng-show="editaccserviceForm.name.$touched ||editaccserviceForm.name.$dirty && editaccserviceForm.name.$invalid">
										<span class = 'err' ng-show="editaccserviceForm.name.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input type='text' ng-trim="false" ng-disabled = 'isSelectDisabled' readonly='true' spl-char-not restrict-field="name" ng-model='name' required  name='name' maxlength="15" id='name' class='form-control'/>
								</div>

									<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
									<label>Access Service Flag<span ng-show="editaccserviceForm.accservice.$touched ||editaccserviceForm.accservice.$dirty && editaccserviceForm.accservice.$invalid">
										<span class = 'err' ng-show="editaccserviceForm.accservice.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<select ng-model='accservice' required class='form-control' name = 'accservice' id='accservice'>		
											<option value=''><?php echo STATE_EDIT_ACTIVE_SELECT; ?></option>
											<option value='Y'>Y - <?php echo STATE_EDIT_ACTIVE_YES; ?></option>
											<option value='N'>N - <?php echo STATE_EDIT_ACTIVE_NO; ?></option>
										</select>
								</div>						
							</div>
						</div>
						 </form>	
					</div>
					<div class='modal-footer ' >
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-click='refresh()' id='Ok' ng-hide='isHideOk' ><?php echo STATE_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo STATE_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="editaccserviceForm.$invalid" ng-click="editaccserviceForm.$invalid=true;update(id)" id="Update"><?php echo STATE_EDIT_BUTTON_UPDATE; ?></button>
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
		  /* window.alert = function() {}; alert = function() {}; */
});
</script>
