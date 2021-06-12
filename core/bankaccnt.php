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
.labspa {
	color:blue;
}
</style>
<div ng-controller='BankAccCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!bankacc"><?php echo STATE_MAIN_HEADING1; ?></a></li>
			<li><a href="#!bankacc">Payment Bank</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class='row'>
			<input type='button' style='float: right;margin-right: 2%;' class='btn btn-primary' value='Add Bank' id='Create'  data-toggle='modal' href='#' data-target='#AddBankDialogue'/>
		
		</div>
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Payment Bank</span>
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
							<th>Bank Name</th>
      						<th>Bank Branch</th>
							<th>Account Name</th>
							<th>Account Number</th>
							<th>Active</th>
							<th>Detail</th>
							<th>Edit</th>
						</tr>
					</thead>
					<tbody>
					 <tr ng-repeat="x in BankList">
						 	<td>{{ x.bankname }}</td>
							<td>{{ x.bankbranch }}</td>
							<td>{{ x.accname }}</td>
							<td>{{ x.accno }}</td>
							<td>{{ x.active }}</td>
							<td><a id={{x.id}} class='editpro' ng-click='detail($index,x.id)' data-toggle='modal' data-target='#DetailBankDialogue'>
								<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a></td>
								<td><a id={{x.id}} class='editpro' ng-click='edit($index,x.id)' data-toggle='modal' data-target='#EditBankDialogue'>
								<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
						</tr>
					</tbody>
				
				</table>
			</div>
		</div>
	</div>
		 <div id='DetailBankDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Bank Account Details - {{bankname}}</h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'  ng-hide='isLoader'>
					<form action="" method="POST" name='DetailBankDialogue' id="DetailBankDialogue">
						<div id='ApplicationViewBody'>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label># : <span class='labspa'>{{id}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Bank Name : <span class='labspa'>{{bankname}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Bank Branch : <span class='labspa'>{{bankaddress}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Bank Address : <span class='labspa'>{{bankbranch}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Account Name : <span class='labspa'>{{accname}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Account Number : <span class='labspa'>{{accno}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Bank Master : <span class='labspa'>{{bankmasterid}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Active : <span class='labspa'>{{active}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Start Date : <span class='labspa'>{{startdate}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Expiry Date : <span class='labspa'>{{expdate}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Create User : <span class='labspa'>{{create_user}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Create Time : <span class='labspa'>{{create_time}}</span></label>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Update User : <span class='labspa'>{{update_user}}</span></label>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Update Time : <span class='labspa'>{{update_time}}</span></label>
							</div>
							<div class='clearfix'></div>
						</div>
						 </form>	
					</div>				
					<div class='modal-footer'>					
						
					</div>
				
			</div>
		</div>	
	</div>	
		<div id='AddBankDialogue' class='modal ' role='dialog' data-backdrop="static" data-keyboard="false" >
	 <div class="modal-dialog modal-md">
		<div class="modal-content">
			
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 style='text-align:center'>Create Bank Details</h2>
				</div>		
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' style="text-align:center" src="../common/img/gif2.gif" /></div></div>
				<div class='modal-body'>
				<form action="" method="POST" name='addstateForm' id="AddstateForm">
				 <div  id='BankaccCreateBody'  ng-hide='isLoader'>
				 <div class='row' style='padding:0px 15px'>						
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
					<label>Bank Name <span class='spanre'>*</span><span ng-show="addstateForm.bankname.$touched ||addstateForm.bankname.$dirty && addstateForm.bankname.$invalid">
								<span class = 'err' ng-show="addstateForm.bankname.$error.required"><?php echo REQUIRED;?></span></span></label>
						<input type='text' ng-trim="false"   ng-model="bankname" name='bankname' maxlength="50"  id='bankname' required class='form-control'/>
					</div>
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
						<label>Bank Branch <span class='spanre'>*</span><span ng-show="addstateForm.bankbranch.$touched ||addstateForm.bankbranch.$dirty && addstateForm.bankbranch.$invalid">
								<span class = 'err' ng-show="addstateForm.bankbranch.$error.required"><?php echo REQUIRED;?></span></span></label>
						<input type='text'  ng-model="bankbranch" name='bankbranch' maxlength="30"  id='bankbranch' required class='form-control'/>
					</div>
					</div>
					 <div class='row' style='padding:0px 15px'>	
					
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
						<label>Account  Name <span class='spanre'>*</span><span ng-show="addstateForm.accname.$touched ||addstateForm.accname.$dirty && addstateForm.accname.$invalid">
								<span class = 'err' ng-show="addstateForm.accname.$error.required"><?php echo REQUIRED;?></span></span></label>
						<input type='text' ng-trim="false"   ng-model="accname" name='accname' maxlength="50"  id='accname' required class='form-control'/>
					</div>
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
						<label>Account Number <span class='spanre'>*</span><span ng-show="addstateForm.accno.$touched ||addstateForm.accno.$dirty && addstateForm.accno.$invalid">
								<span class = 'err' ng-show="addstateForm.accno.$error.required"><?php echo REQUIRED;?></span></span></label>
						<input type='text' ng-trim="false" spl-char-not restrict-field="accno" numbers-only  ng-model="accno" name='accno' maxlength="30"  id='accno' required class='form-control'/>
					</div>
					</div>
					 <div class='row' style='padding:0px 15px'>		
					 	<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
								<label>Bank Master<span class='spanre'>*</span><span ng-show="addstateForm.bankmasterid.$touched ||addstateForm.bankmasterid.$dirty && addstateForm.bankmasterid.$invalid">
								<span class = 'err' ng-show="addstateForm.bankmasterid.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="bankmasterid" class='form-control' name = 'bankmasterid' id='bankmasterid' required >											
									<option value=''><?php echo LOCAL_GOVT_CREATE_STATE_SELECT; ?></option>
									<option ng-repeat="bank in bankaccounts" value="{{bank.id}}">{{bank.name}}</option>
								</select>
							</div>	
					
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
						<label><?php echo STATE_CREATE_ACTIVE; ?> <span class='spanre'>*</span><span ng-show="addstateForm.active.$touched ||addstateForm.active.$dirty && addstateForm.active.$invalid">
								<span class = 'err' ng-show="addstateForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="active" class='form-control' name = 'active' id='Active' required >											
									<option value=''><?php echo STATE_CREATE_ACTIVE_SELECT; ?></option>
									<option value='Y'><?php echo STATE_CREATE_ACTIVE_YES; ?></option>
									<option value='N'><?php echo STATE_CREATE_ACTIVE_NO; ?></option>
								</select>
						</div>
					</div>
						<div class='row' style='padding:0px 15px'>
					 <div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label><?php echo APPLICATION_APPROVE_START_DATE; ?>
							<span class = 'err' ng-show="addstateForm.startDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="startdate" type='date' id='startdate' name='startdate'  class='form-control'/>
						</div>
						 <div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label><?php echo APPLICATION_APPROVE_END_DATE; ?>
							<span class = 'err' ng-show="addstateForm.endDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="expdate" type='date' id='expdate' name='expdate'  class='form-control'/>
						</div>
						<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 form_col12_element'>
						<label>Bank Address <span class='spanre'>*</span><span ng-show="addstateForm.bankaddress.$touched ||addstateForm.bankaddress.$dirty && addstateForm.bankaddress.$invalid">
								<span class = 'err' ng-show="addstateForm.bankaddress.$error.required"><?php echo REQUIRED;?></span></span></label>
						<input type='text' ng-trim="false"   ng-model="bankaddress" name='bankaddress' maxlength="100"  id='bankaddress' required class='form-control'/>
					</div>
						</div>	
					</div>
				<div class='clearfix'></div>
				</form>
				</div>
				<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-click="refresh()" ng-hide='isHideOk' ><?php echo STATE_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo STATE_CREATE_BUTTON_CANCEL; ?></a>
					<button type="button" class="btn btn-primary" ng-click='addstateForm.$invalid=true;create(id)' ng-hide='isHide' ng-disabled = "addstateForm.$invalid"  id="Create"><?php echo STATE_CREATE_BUTTON_CREATE; ?></button>
				</div>
			</div>
		</div>
	</div>	
	
	 <div id='EditBankDialogue' class='modal fade' role='dialog' data-backdrop="static" data-keyboard="false" >
		 <div class="modal-dialog modal-md">
			<div class="modal-content">
				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Edit Bank Details</h2>
					</div>					 
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>						 
					<div class="modal-body">		
						<form action="" method="POST" name='editbankForm' id="EditbankForm">
				 <div  id='BankaccountCreateBody'  ng-hide='isLoader'>
				 <div class='row' style='padding:0px 15px'>						
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
						<label>Bank Name<span ng-show="editbankForm.bankname.$touched ||editbankForm.bankname.$dirty && editbankForm.bankname.$invalid">
								<span class = 'err' ng-show="editbankForm.bankname.$error.required"><?php echo REQUIRED;?></span></span></label>
						<input type='text' ng-trim="false"   ng-model="bankname" name='bankname' maxlength="50"  id='bankname' required class='form-control'/>
					</div>
					
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
						<label>Bank Branch<span ng-show="editbankForm.bankbranch.$touched ||editbankForm.bankbranch.$dirty && editbankForm.bankbranch.$invalid">
								<span class = 'err' ng-show="editbankForm.bankbranch.$error.required"><?php echo REQUIRED;?></span></span></label>
						<input type='text'    ng-model="bankbranch" name='bankbranch' maxlength="30"  id='bankbranch' required class='form-control'/>
					</div>
					</div>
					 <div class='row' style='padding:0px 15px'>	
					
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
						<label>Account  Name<span ng-show="editbankForm.accname.$touched ||editbankForm.accname.$dirty && editbankForm.accname.$invalid">
								<span class = 'err' ng-show="editbankForm.accname.$error.required"><?php echo REQUIRED;?></span></span></label>
						<input type='text'    ng-model="accname" name='accname' maxlength="50"  id='accname' required class='form-control'/>
					</div>
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
						<label>Account Number<span ng-show="editbankForm.accno.$touched ||editbankForm.accno.$dirty && editbankForm.accno.$invalid">
								<span class = 'err' ng-show="editbankForm.accno.$error.required"><?php echo REQUIRED;?></span></span></label>
						<input type='text' ng-trim="false" spl-char-not restrict-field="accno" numbers-only  ng-model="accno" name='accno' maxlength="30"  id='accno' required class='form-control'/>
					</div>
					</div>
					 <div class='row' style='padding:0px 15px'>	
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
								<label>Bank Master<span class='spanre'>*</span><span ng-show="editbankForm.bankmasterid.$touched ||editbankForm.bankmasterid.$dirty && editbankForm.bankmasterid.$invalid">
								<span class = 'err' ng-show="editbankForm.bankmasterid.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="bankmasterid" class='form-control' name = 'bankmasterid' id='bankmasterid' required >											
									<option value=''><?php echo LOCAL_GOVT_CREATE_STATE_SELECT; ?></option>
									<option ng-repeat="bank in bankaccounts" value="{{bank.id}}">{{bank.name}}</option>
								</select>
							</div>	
					
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
						<label><?php echo STATE_CREATE_ACTIVE; ?><span ng-show="editbankForm.active.$touched ||editbankForm.active.$dirty && editbankForm.active.$invalid">
								<span class = 'err' ng-show="editbankForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="active" class='form-control' name = 'active' id='Active' required >											
									<option value=''><?php echo STATE_CREATE_ACTIVE_SELECT; ?></option>
									<option value='Y'><?php echo STATE_CREATE_ACTIVE_YES; ?></option>
									<option value='N'><?php echo STATE_CREATE_ACTIVE_NO; ?></option>
								</select>
						</div>
					</div>
					<div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label><?php echo APPLICATION_APPROVE_START_DATE; ?>
							<span class = 'err' ng-show="editstateForm.startdate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="startdate" date-format="yyyy-mm-dd" type='date' id='startdate'  pattern="\d{4}-\d{2}-\d{2}" name='startdate'  class='form-control'/>
						</div>
						 <div class='col-xs-6 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label><?php echo APPLICATION_APPROVE_END_DATE; ?>
							<span class = 'err' ng-show="editstateForm.endDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="expdate" type='date' id='expdate' name='expdate'  class='form-control'/>
								
						</div>
						<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 form_col12_element'>
						<label>Bank Address<span ng-show="editbankForm.bankaddress.$touched ||editbankForm.bankaddress.$dirty && editbankForm.bankaddress.$invalid">
								<span class = 'err' ng-show="editbankForm.bankaddress.$error.required"><?php echo REQUIRED;?></span></span></label>
						<input type='text'    ng-model="bankaddress" name='bankaddress' maxlength="100"  id='bankaddress' required class='form-control'/>
					</div>
						</div>	
					</div>
				<div class='clearfix'></div>
				</form>
					<div class='modal-footer ' >
						<button type='button' class='btn btn-primary' ng-click="refresh()" data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo STATE_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' ng-click='refresh()' data-dismiss='modal' ng-hide='isHide' ><?php echo STATE_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="editbankForm.$invalid" ng-click="editstateForm.$invalid=true;update(id)" id="Update"><?php echo STATE_EDIT_BUTTON_UPDATE; ?></button>
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
	LoadSelect2Script();
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
