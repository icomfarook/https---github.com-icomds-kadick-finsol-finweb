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
.labspa {
	color:blue;
}
</style>
<div ng-controller= "CardInvenCtrl">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!cardinvn">Dash Board</a></li>
			<li><a href="#!cardinvn">Card Inventory</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<form action="" method="POST" name='terminalInventorySearchForm' id="terminalInventorySearchForm">
			<div class='row' class='row appcont' ng-init = "creteria='S'" style='margin:1%'>
				<input type='button' style='float: right;margin-right: 2%;' class='btn btn-primary' value='Create Inventory' id='Create' href='#' data-toggle='modal' data-target='#CreateInventory'/>
				<div class="col-xs-2">
				<label>Criteria</label>  <br />
					<label style='margin-right:1%'><input style='margin-right:5px' ng-checked='true'  ng-click='changes()' value='S' type='radio' name='creteria' ng-model='creteria' />Summary</label>
					<label><input  value='D'  ng-click='change()' type='radio' style='margin-right:5px' name='creteria' ng-model='creteria' />Detail</label>
				</div>
				<div class='col-lg-2 col-xs-2 col-sm-2 col-md-2'>
					<label>Card Type</label> 
					<select  ng-disabled="creteria=='S'" ng-model='vendor'  ng-init = "vendor='-1'" class='form-control' name='vendor'  >
						<option value='-1'>--Select--</option>			
						<option value='M'>M-Master</option>			
						<option value='V'>V-Visa</option>	
						<option value='D'>D-Discover</option>
						<option value='C'>C-Citi Diners</option>
						<option value='A'>A-Amex</option>
						<option value='V'>V-Verve</option>
						<option value='O'>O-Others</option>
					</select>																	
				</div>
				<div class='col-lg-2 col-xs-2 col-sm-2 col-md-2'>
					<label>Status</label> 
					<select   ng-disabled="creteria=='S'" ng-model='status'  ng-init = "status='-1'" class='form-control' name='status'  >
						<option value='-1'>--Select--</option>			
						<option value='A'>A-Avaialble</option>			
						<option value='B'>B-Bound</option>	
						<option value='X'>X-Block</option>
						<option value='D'>D-Damage</option>
						<option value='F'>F-Fault</option>
						<option value='S'>S-Suspended</option>
						<option value='O'>O-Others</option>
					</select>										
				</div>
				<div class='col-lg-2 col-xs-2 col-sm-2 col-md-2'>
					<label>Account Number</label> 
					<input  ng-disabled="creteria=='S'" type='text' numbers-only maxlength='30' ng-model='terid'  ng-init = "terid = ''" class='form-control'/>											
				</div>
				
				<div class='col-lg-2 col-xs-2 col-sm-2 col-md-2'>
								<label>Bank Master<span class='spanre'>*</span><span ng-show="addstateForm.bankmasterid.$touched ||addstateForm.bankmasterid.$dirty && addstateForm.bankmasterid.$invalid">
								<span class = 'err' ng-show="addstateForm.bankmasterid.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-disabled="creteria=='S'" ng-init = "terslno = ''"  ng-model='terslno' class='form-control'>											
									<option value=""><?php echo LOCAL_GOVT_CREATE_STATE_SELECT; ?></option>
									<option ng-repeat="bank in bankaccounts" value="{{bank.id}}">{{bank.name}}</option>
								</select>
				</div>	
					
			</div>
			<div class='row appcont'  style='text-align: -webkit-center;'>
				<div style='text-align: center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
					<button type="button" class="btn btn-primary"  ng-disabled = "(creteria == 'D' && vendor == '-1' && status == '-1' && terid == '' && terslno == '' )"  ng-click='query()' ng-hide='isHide'  id="Query">Search</button>
					<button type="button" class="btn btn-primary" ng-click='refresh()'  id="Refresh">Refresh</button>
				</div>
			</div>
			
		</form>
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Card Inventory</span>
				</div>
				
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding" data-backdrop="static" data-keyboard="false">		
                <div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>			
				<table  ng-show='sushow' class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>
							<th>Bank</th>
							<th>Card Type</th>
      						<th>Status</th>
							<th>Count</th>
						</tr>
					</thead>
					<tbody>
					      <tr ng-repeat="x in Inventory_list" ng-hide='susshow'>
							<td>{{ x.bank }}</td>
							<td>{{ x.name }}</td>
						 	<td>{{ x.status }}</td>
							<td>{{ x.count }}</td>							
							</tr>
							<tr ng-show="Inventory_list.length==0">
								<td colspan='4' >
										<?php echo NO_DATA_FOUND; ?>              
									</td>
								
							</tr>
					</tbody>					
				</table>
				<table  ng-show='deshow' class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>
							<th>Inventory id</th>
      						<th>Account Number</th>
							<th>Card Type</th>
							<th>Status</th>
							<th>Edit</th>
							<th>Detail</th>
						</tr>
					</thead>
					<tbody>
					      <tr ng-repeat="x in Inventory_list" ng-hide='desshow' >
							<td>{{ x.inventory_id }}</td>
						 	<td>{{ x.TerminalSerialNo }}</td>
							<td>{{ x.card_type }}</td>
							<td>{{ x.Status }}</td>
							<td><a id={{x.inventory_id }} class='editcountry' ng-click='edit($index,x.inventory_id )' data-toggle='modal' data-target='#EditinventoryDialogue'>
							<button  class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
							<td><a id={{x.inventory_id}} class='detail' ng-click='detail($index,x.inventory_id)' data-toggle='modal' data-target='#DetailViewDialogue'>
							<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
							</td>
							</tr>
							<tr ng-show="Inventory_list.length==0">
								<td colspan='6' >
										<?php echo NO_DATA_FOUND; ?>              
									</td>
								
							</tr>
						</tbody>
					
				</table>
			</div>
		</div>
	</div>
	
	<div id='DetailViewDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Card Inventory Details - {{inventory_id}}</h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'  ng-hide='isLoader'>
					<form action="" method="POST" name='ApplicatioViewDialgoue' id="ApplicatioViewDialgoue">
						<div id='InventoryViewBody'>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Inventory Id :<span class='labspa'>{{inventory_id}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label> Card Type :<span class='labspa'>{{CardType}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Bank Master :<span class='labspa'>{{BankMasterid}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Status :<span class='labspa'>{{Status}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Account Number: <span class='labspa'>{{AccountNumber}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Card Number: <span class='labspa'>{{card_num}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Reference No: <span class='labspa'>{{reference_num}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Agent Allocated: <span class='labspa'>{{agent_allocated}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Agent Sold: <span class='labspa'>{{agent_sold}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Create User: <span class='labspa'>{{create_user}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Create Time: <span class='labspa'>{{create_time}}</span></label>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Update Time: <span class='labspa'>{{update_time}}</span></label>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Allocated Time: <span class='labspa'>{{allocate_time}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Sold Time: <span class='labspa'>{{sold_time}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Order No: <span class='labspa'>{{order_no}}</span></label>
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
	
	
	
	 <div id='EditinventoryDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Edit Card Inventory</h2>
					</div>		
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>					
					<div class='modal-body' style='margin-top:2%'>
					<form action="" method="POST" name='InventoryEditForm' id="InventoryEditForm">
						<div id='inventoryEditBody'  ng-hide='isLoader'>
						 <div class='row' style='padding:0px 15px'>						
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
						<label>Bank Master<span class='spanre'>*</span><span ng-show="InventoryEditForm.BankMasterid.$touched ||InventoryEditForm.BankMasterid.$dirty && InventoryEditForm.BankMasterid.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.BankMasterid.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select required  maxlength='20' ng-model='BankMasterid' class='form-control'>											
									<option value=''><?php echo LOCAL_GOVT_CREATE_STATE_SELECT; ?></option>
									<option ng-repeat="bank in bankaccounts" value="{{bank.id}}">{{bank.name}}</option>
								</select>
					</div>
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
					<label>Card Type<span class='spanre'>*</span><span ng-show="InventoryEditForm.CardType.$touched ||InventoryEditForm.CardType.$dirty && InventoryEditForm.CardType.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.CardType.$error.required"><?php echo REQUIRED;?>.</span></span></label>
					<select   ng-model='CardType' required class='form-control' name='CardType'  >
						<option value=''>--Select--</option>			
						<option value='M'>M-Master</option>			
						<option value='V'>V-Visa</option>	
						<option value='D'>D-Discover</option>
						<option value='C'>C-Citi Diners</option>
						<option value='A'>A-Amex</option>
						<option value='R'>V-Verve</option>
						<option value='O'>O-Others</option>
					</select>																	
				
						</div>
						
						<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
								<label>Account Number<span class='spanre'>*</span><span ng-show="InventoryEditForm.AccountNumber.$touched ||InventoryEditForm.AccountNumber.$dirty && InventoryEditForm.AccountNumber.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.AccountNumber.$error.required"><?php echo REQUIRED;?>.</span></span></label>
					<input   maxlength='30'  required ng-model='AccountNumber' class='form-control'/>	
							</div>	
							</div>	
						</div>
						</form>	
						</div>
			<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok'  ng-click='refresh()' ng-hide='isHideOk' ><?php echo BANK_ACCOUNT_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo BANK_ACCOUNT_CREATE_BUTTON_CANCEL; ?></a>
					<button type='button' class='btn btn-primary'  ng-hide='isHide' ng-disabled= "InventoryEditForm.$invalid" ng-click="InventoryEditForm.$invalid=true;update(inventory_id )"  id="Update" >Update</button>
			</div>		
	</div>
	</div>	
</div></div>
						
	<div id='CreateInventory' class='modal fade' role='dialog' data-backdrop="static" data-keyboard="false" >
		<div class="modal-dialog modal-md">
			<div class="modal-content">				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Create New Card Inventory</h2>
					</div>					 
					<div  style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body' style='margin-top:2;'>
					<form action ="" method="POST" name='addPBankForm' id="AddPbankForm" >					
					<div id='invenCreateBody'  ng-hide='isLoader'>
						 <div class='row' style='padding:0px 15px'>						
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
						<label>Bank Master<span ng-show="addPBankForm.BankMasterid.$touched ||addPBankForm.BankMasterid.$dirty && addPBankForm.BankMasterid.$invalid">
								<span class = 'err' ng-show="addPBankForm.BankMasterid.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select   maxlength='20' ng-model='BankMasterid' class='form-control' required >							
									<option value=''><?php echo LOCAL_GOVT_CREATE_STATE_SELECT; ?></option>
									<option ng-repeat="bank in bankaccounts" value="{{bank.id}}">{{bank.name}}</option>
								</select>
					</div>
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' required>
					<label>Card Type<span ng-show="addPBankForm.CardType.$touched ||addPBankForm.CardType.$dirty && addPBankForm.CardType.$invalid">
								<span class = 'err' ng-show="addPBankForm.CardType.$error.required"><?php echo REQUIRED;?></span></span></label>
					<select required   ng-model='CardType'  class='form-control' name='CardType'  >
						<option value=''>--Select--</option>			
						<option value='M'>M-Master</option>			
						<option value='V'>V-Visa</option>	
						<option value='D'>D-Discover</option>
						<option value='C'>C-Citi Diners</option>
						<option value='A'>A-Amex</option>
						<option value='V'>V-Verve</option>
						<option value='O'>O-Others</option>
					</select>																	
				
						</div>
						
						<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
								<label>Account Number<span ng-show="addPBankForm.AccountNumber.$touched ||addPBankForm.AccountNumber.$dirty && addPBankForm.AccountNumber.$invalid">
								<span class = 'err' ng-show="addPBankForm.AccountNumber.$error.required"><?php echo REQUIRED;?></span></span></label>
					<input   maxlength='30' ng-model='AccountNumber' class='form-control' required />	
							</div>	
							</div>	
				
				<div class='clearfix'></div>
					</form>
			</div>	
			</div>
			<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-click='refresh()' ng-hide='isHideOk' ><?php echo BANK_ACCOUNT_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo BANK_ACCOUNT_CREATE_BUTTON_CANCEL; ?></a>
					<button type="button" class="btn btn-primary" ng-click='addPBankForm.$invalid=true;create()' ng-disabled = "addPBankForm.$invalid" ng-hide='isHide' id="Create"><?php echo BANK_ACCOUNT_CREATE_BUTTON_CREATE; ?></button>
			</div>
		
			
	
	</div>	
</div>
</div>

<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	//TestTable1();
	//TestTable2();
	//TestTable3();
	//LoadSelect2Script(MakeSelect2);
}

$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	//LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	//WinMove();

	$("#EditBankAccountrDialogue, #AddBankAccountDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	
});

</script>

 