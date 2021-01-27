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
<div ng-controller= "TermInvenCtrl">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!ptyacc">Dash Board</a></li>
			<li><a href="#!asctervend">Terminal Inventory</a></li>
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
					<label style='margin-right:1%'><input style='margin-right:5px' ng-checked='true' value='S' type='radio' name='creteria' ng-model='creteria' />Summary</label>
					<label><input  value='D' type='radio' style='margin-right:5px' name='creteria' ng-model='creteria' />Detail</label>
				</div>	
				<div class='col-lg-2 col-xs-2 col-sm-2 col-md-2'>
					<label>Vendor</label> 
					<select  ng-disabled="creteria=='S'" ng-model='vendor'  ng-init = "vendor='-1'" class='form-control' name='vendor'  >
						<option value='-1'>--Select--</option>
						<option ng-repeat="vendor in vendors" value="{{vendor.id}}"> {{vendor.name}}</option>
						
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
					<label>Terminal Id</label> 
					<input  ng-disabled="creteria=='S'" type='terid' maxlength='10' ng-model='terid' class='form-control'/>											
				</div>
				<div class='col-lg-2 col-xs-2 col-sm-2 col-md-2'>
					<label>Terminal Serial No</label> 
					<input  ng-disabled="creteria=='S'" type='terslno' maxlength='20' ng-model='terslno' class='form-control'/>										
			</div>
			</div>
			<div class='row appcont'  style='text-align: -webkit-center;'>
				<div style='text-align: center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
					<button type="button" class="btn btn-primary"  ng-disabled = '' ng-click='query()' ng-hide='isHide'  id="Query">Search</button>
					<button type="button" class="btn btn-primary" ng-click='refresh()'  id="Refresh">Refresh</button>
				</div>
			</div>
			
		</form>
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Terminal Inventory</span>
				</div>
				
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding" data-backdrop="static" data-keyboard="false">		
                <div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>			
				<table  ng-show='sushow' class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>
							<th>Vendor</th>
      						<th>Status</th>
							<th>Count</th>
						</tr>
					</thead>
					<tbody>
					      <tr ng-repeat="x in Inventory_list">
							<td>{{ x.name }}</td>
						 	<td>{{ x.status }}</td>
							<td>{{ x.count }}</td>							
							</tr>
							<tr ng-show="Inventory_list.length==0">
								<td colspan='6' >
										<?php echo NO_DATA_FOUND; ?>              
									</td>
								
							</tr>
					</tbody>					
				</table>
				<table  ng-show='deshow' class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>
							<th>Inventory id</th>
      						<th>Merchant Name</th>
							<th>Status</th>
							<th>Terminal Id</th>
							<th>Edit</th>
							<th>Detail</th>
						</tr>
					</thead>
					<tbody>
					      <tr ng-repeat="x in Inventory_list">
							<td>{{ x.inventory_id }}</td>
						 	<td>{{ x.merchantname }}</td>
							<td>{{ x.Status }}</td>
							<td>{{ x.TerminalId }}</td>
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
						<h2 style='text-align:center'>Terminary Inventory Details - {{id}}</h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'  ng-hide='isLoader'>
					<form action="" method="POST" name='ApplicatioViewDialgoue' id="ApplicatioViewDialgoue">
						<div id='InventoryViewBody'>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Inventory Id :<span class='labspa'>{{inventory_id}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Vendor Id :<span class='labspa'>{{id}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Merchant Id :<span class='labspa'>{{merchantid}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Merchant Name :<span class='labspa'>{{merchantname}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Status: <span class='labspa'>{{Status}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Terminal Model Code: <span class='labspa'>{{termimodelCode}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Terminal Id: <span class='labspa'>{{TerminalId}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Terminal Serial No: <span class='labspa'>{{TerminalSerialNo}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>SW Version: <span class='labspa'>{{Swversion}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>FW Version: <span class='labspa'>{{FwVersion}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Bank Code: <span class='labspa'>{{BankCode}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Bank Account No: <span class='labspa'>{{BankAccountNo}}</span></label>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Bank Account Type: <span class='labspa'>{{AccType}}</span></label>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Visa Acquirer Id: <span class='labspa'>{{VisaAcqID}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Verver Acquirer Id: <span class='labspa'>{{VerAcqID}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Master Acquirer Id: <span class='labspa'>{{MastAcqID}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>New Terminal Owner Code: <span class='labspa'>{{NewTerOwnCode}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>LGA :<span class='labspa'>{{Lga}}</span></label>
							</div>
						
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Merchant Account Name:<span class='labspa'>{{MerAccName}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>PTSP :<span class='labspa'>{{PTSP}}</span></label>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Test Terminal: <span class='labspa'>{{TestTerm}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Create Time :<span class='labspa'>{{cretime}}</span></label>
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
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Edit Terminal Inventory</h2>
					</div>		
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>					
					<div class='modal-body' style='margin-top:2%'>
					<form action="" method="POST" name='InventoryEditForm' id="InventoryEditForm">
						<div id='inventoryEditBody'  ng-hide='isLoader'>
							<div class='row' style='margin-top:2%'>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label>Vendor Id</label><span ng-show="InventoryEditForm.id.$touched ||InventoryEditForm.id.$dirty && InventoryEditForm.id.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.id.$error.required"><?php echo REQUIRED;?></span></span>
								<input ng-model="id" numbers-only type='text'  ng-disabled='isInputDisabled' id='id'  maxlength='10' name='id' required class='form-control'/>
							</div>	
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label>Merchant Id<span ng-show="InventoryEditForm.merchantid.$touched ||InventoryEditForm.merchantid.$dirty && InventoryEditForm.merchantid.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.merchantid.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="merchantid"  type='text' ng-disabled='isInputDisabled' id='merchantid'  maxlength='11' name='merchantid' required class='form-control'/>
							</div>	
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label>Merchant Name<span ng-show="InventoryEditForm.merchantname.$touched ||InventoryEditForm.merchantname.$dirty && InventoryEditForm.merchantname.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.merchantname.$error.required"><?php echo REQUIRED;?></span></span></label>
								 <input type='text' name='merchantname' required ng-model='merchantname' id='merchantname' class='form-control'/>	
							</div>
							 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>											
								<label>Status
								<span ng-show="InventoryEditForm.Status.$dirty && InventoryEditForm.Status.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.Status.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select  ng-model="Status"  class='form-control' name = 'Status' id='Status' required>											
									<option value="A">Available</option>
									<option value='B'>Bound</option>
									<option value='X'>Block</option>
									<option value='D'>Damage</option>
									<option value='F'>Fault</option>
									<option value='S'>Suspend</option>
									<option value='O'>Other</option>
								</select>											
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label>Terminal Model Code<span ng-show="InventoryEditForm.termimodelCode.$touched ||InventoryEditForm.termimodelCode.$dirty && InventoryEditForm.termimodelCode.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.termimodelCode.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="termimodelCode"  type='text' ng-disabled='isInputDisabled' id='termimodelCode'  maxlength='11' name='termimodelCode' required class='form-control'/>
							</div>	
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label>Terminal Id<span ng-show="InventoryEditForm.TerminalId.$touched ||InventoryEditForm.TerminalId.$dirty && InventoryEditForm.TerminalId.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.TerminalId.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="TerminalId"  type='text' ng-disabled='isInputDisabled' id='TerminalId'  maxlength='11' name='TerminalId' required class='form-control'/>
							</div>	
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label>Terminal Serial No<span ng-show="InventoryEditForm.TerminalSerialNo.$touched ||InventoryEditForm.TerminalSerialNo.$dirty && InventoryEditForm.TerminalSerialNo.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.TerminalSerialNo.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="TerminalSerialNo"  type='text' ng-disabled='isInputDisabled' id='TerminalSerialNo'  maxlength='11' name='TerminalSerialNo' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label>Sw Version<span ng-show="InventoryEditForm.Swversion.$touched ||InventoryEditForm.Swversion.$dirty && InventoryEditForm.Swversion.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.Swversion.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="Swversion"  type='text' ng-disabled='isInputDisabled' id='SWversion' maxlength='11' name='SWversion' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							       <label>Fw Version<<span ng-show="InventoryEditForm.FwVersion.$touched ||InventoryEditForm.FwVersion.$dirty && InventoryEditForm.FwVersion.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.FwVersion.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="FwVersion"  type='text' ng-disabled='isInputDisabled' id='FwVersion'  maxlength='11' name='FwVersion' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							       <label>Bank Code<span ng-show="InventoryEditForm.BankCode.$touched ||InventoryEditForm.BankCode.$dirty && InventoryEditForm.BankCode.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.BankCode.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="BankCode"  type='text' ng-disabled='isInputDisabled' id='BankCode' maxlength='11' name='BankCode' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							       <label>Bank Account Number<span ng-show="InventoryEditForm.BankAccountNo.$touched ||InventoryEditForm.BankAccountNo.$dirty && InventoryEditForm.BankAccountNo.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.BankAccountNo.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="BankAccountNo"  type='text' ng-disabled='isInputDisabled' id='BankAccountNo'  maxlength='11' name='BankAccountNo' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							       <label>Bank Account Type<span ng-show="InventoryEditForm.AccType.$touched ||InventoryEditForm.AccType.$dirty && InventoryEditForm.AccType.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.AccType.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="AccType"  type='text' ng-disabled='isInputDisabled' id='AccType'  maxlength='11' name='AccType' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							       <label>Visa Acquirer Id<span ng-show="InventoryEditForm.VisaAcqID.$touched ||InventoryEditForm.VisaAcqID.$dirty && InventoryEditForm.VisaAcqID.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.VisaAcqID.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="VisaAcqID"  type='text' ng-disabled='isInputDisabled' id='VisaAcqID' maxlength='11' name='VisaAcqID' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							       <label>Verve Acquirer Id<span ng-show="InventoryEditForm.VerAcqID.$touched ||InventoryEditForm.VerAcqID.$dirty && InventoryEditForm.VerAcqID.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.VerAcqID.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="VerAcqID"  type='text' ng-disabled='isInputDisabled' id='VerAcqID'  maxlength='11' name='VerAcqID' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							       <label>Master Acquirer Id<span ng-show="InventoryEditForm.MastAcqID.$touched ||InventoryEditForm.MastAcqID.$dirty && InventoryEditForm.MastAcqID.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.MastAcqID.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="MastAcqID"  type='text' ng-disabled='isInputDisabled' id='MastAcqID'  maxlength='11' name='MastAcqID' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							       <label>New Terminal Owner Code<span ng-show="InventoryEditForm.NewTerOwnCode.$touched ||InventoryEditForm.NewTerOwnCode.$dirty && InventoryEditForm.NewTerOwnCode.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.NewTerOwnCode.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="NewTerOwnCode"  type='text' ng-disabled='isInputDisabled' id='NewTerOwnCode' ng-minlength="11" maxlength='11' name='NewTerOwnCode' required class='form-control'/>
							</div>
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							       <label>Lga<span ng-show="InventoryEditForm.Lga.$touched ||InventoryEditForm.Lga.$dirty && InventoryEditForm.Lga.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.Lga.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="Lga"  type='text' ng-disabled='isInputDisabled' id='Lga'  maxlength='11' name='Lga' required class='form-control'/>
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
							       <label>Merchant Account Name<span ng-show="InventoryEditForm.MerAccName.$touched ||InventoryEditForm.MerAccName.$dirty && InventoryEditForm.MerAccName.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.MerAccName.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="MerAccName"  type='text' ng-disabled='isInputDisabled' id='MerAccName'  maxlength='11' name='MerAccName' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							       <label>PTSP<span ng-show="InventoryEditForm.PTSP.$touched ||InventoryEditForm.PTSP.$dirty && InventoryEditForm.PTSP.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.PTSP.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="PTSP"  type='text' ng-disabled='isInputDisabled' id='PTSP' ng-minlength="11" maxlength='11' name='PTSP' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							       <label>Test Terminal<span ng-show="InventoryEditForm.TestTerm.$touched ||InventoryEditForm.TestTerm.$dirty && InventoryEditForm.TestTerm.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.TestTerm.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="TestTerm"  type='text' ng-disabled='isInputDisabled' id='TestTerm' ng-minlength="11" maxlength='11' name='TestTerm' required class='form-control'/>
							</div>
												
						</div>
						</div>
						</form>	
						</div>
			<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo BANK_ACCOUNT_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo BANK_ACCOUNT_CREATE_BUTTON_CANCEL; ?></a>
					<button type='button' class='btn btn-primary'  ng-hide='isHide'  ng-click="InventoryEditForm.$invalid=true;update(inventory_id )"  id="Update" >Update</button>
			</div>		
	</div>
	</div>	
</div></div>
						
	<div id='CreateInventory' class='modal fade' role='dialog' data-backdrop="static" data-keyboard="false" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Create New Inventory</h2>
					</div>					 
					<div  style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body' style='margin-top:2;'>
					<form action ="" method="POST" name='addinventoryForm' id="addPBankForm" >					
					<div id='invenCreateBody'  ng-hide='isLoader'>
							<div class='row' style='margin-top:2%'>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label>Vendor Id<span ng-show="addinventoryForm.id.$touched ||addinventoryForm.id.$dirty && addinventoryForm.id.$invalid">
								<span class = 'err' ng-show="addinventoryForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="id" numbers-only type='text'  ng-disabled='isInputDisabled' id='id'  maxlength='11' name='id' required class='form-control'/>
							</div>	
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label>Merchant Id<span ng-show="addinventoryForm.merchantid.$touched ||addinventoryForm.merchantid.$dirty && addinventoryForm.merchantid.$invalid">
								<span class = 'err' ng-show="addinventoryForm.merchantid.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="merchantid"  type='text' ng-disabled='isInputDisabled' id='merchantid'  maxlength='20' name='merchantid' required class='form-control'/>
							</div>	
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label>Merchant Name<span ng-show="addinventoryForm.merchantname.$touched ||addinventoryForm.merchantname.$dirty && addinventoryForm.merchantname.$invalid">
								<span class = 'err' ng-show="addinventoryForm.merchantname.$error.required"><?php echo REQUIRED;?></span></span></label>
								 <input type='text' name='merchantname' required ng-model='merchantname' id='merchantname'  maxlength='50'class='form-control'/>	
							</div>
							 <div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>											
								<label>Status
								<span ng-show="infoViewForm.Status.$dirty && infoViewForm.Status.$invalid">
								<span class = 'err' ng-show="infoViewForm.Status.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select  ng-model="Status"  class='form-control' name = 'Status' id='Status' required>											
								    <option value="">--Select--</option>
									<option value="A">Available</option>
									<option value='B'>Bound</option>
									<option value='X'>Block</option>
									<option value='D'>Damage</option>
									<option value='F'>Fault</option>
									<option value='S'>Suspend</option>
									<option value='O'>Other</option>
								</select>											
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label>Terminal Model Code<span ng-show="addinventoryForm.termimodelCode.$touched ||addinventoryForm.termimodelCode.$dirty && addinventoryForm.termimodelCode.$invalid">
								<span class = 'err' ng-show="addinventoryForm.termimodelCode.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="termimodelCode"  type='text' ng-disabled='isInputDisabled' id='Mobile No'  maxlength='10' name='termimodelCode' required class='form-control'/>
							</div>	
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label>Terminal Id<span ng-show="addinventoryForm.TerminalId.$touched ||addinventoryForm.TerminalId.$dirty && addinventoryForm.TerminalId.$invalid">
								<span class = 'err' ng-show="addinventoryForm.TerminalId.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="TerminalId"  type='text' ng-disabled='isInputDisabled' id='TerminalId'  maxlength='10' name='TerminalId' required class='form-control'/>
							</div>	
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label>Terminal Serial No<span ng-show="addinventoryForm.TerminalSerialNo.$touched ||addinventoryForm.TerminalSerialNo.$dirty && addinventoryForm.TerminalSerialNo.$invalid">
								<span class = 'err' ng-show="addinventoryForm.TerminalSerialNo.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="TerminalSerialNo"  type='text' ng-disabled='isInputDisabled' id='TerminalSerialNo'  maxlength='30' name='TerminalSerialNo' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label>Sw Version<span ng-show="addinventoryForm.SWversion.$touched ||addinventoryForm.SWversion.$dirty && addinventoryForm.SWversion.$invalid">
								<span class = 'err' ng-show="addinventoryForm.SWversion.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="SWversion"  type='text' ng-disabled='isInputDisabled' id='SWversion' maxlength='10' name='SWversion' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							       <label>Fw Version<span ng-show="addinventoryForm.FwVersion.$touched ||addinventoryForm.FwVersion.$dirty && addinventoryForm.FwVersion.$invalid">
								<span class = 'err' ng-show="addinventoryForm.FwVersion.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="FwVersion"  type='text' ng-disabled='isInputDisabled' id='FwVersion'  maxlength='10' name='FwVersion' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							       <label>Bank Code<span ng-show="addinventoryForm.BankCode.$touched ||addinventoryForm.BankCode.$dirty && addinventoryForm.BankCode.$invalid">
								<span class = 'err' ng-show="addinventoryForm.BankCode.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="BankCode"  type='text' ng-disabled='isInputDisabled' id='BankCode' maxlength='11' name='BankCode' maxlength='5' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							       <label>Bank Account Number<span ng-show="addinventoryForm.BankAccountNo.$touched ||addinventoryForm.BankAccountNo.$dirty && addinventoryForm.BankAccountNo.$invalid">
								<span class = 'err' ng-show="addinventoryForm.BankAccountNo.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="BankAccountNo"  type='text' ng-disabled='isInputDisabled' id='BankAccountNo'  maxlength='15' name='BankAccountNo' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							       <label>Bank Account Type<span ng-show="addinventoryForm.AccType.$touched ||addinventoryForm.AccType.$dirty && addinventoryForm.AccType.$invalid">
								<span class = 'err' ng-show="addinventoryForm.AccType.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="AccType"  type='text' ng-disabled='isInputDisabled' id='AccType'  maxlength='2' name='AccType' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							       <label>Visa Acquirer Id<span ng-show="addinventoryForm.VisaAcqID.$touched ||addinventoryForm.VisaAcqID.$dirty && addinventoryForm.VisaAcqID.$invalid">
								<span class = 'err' ng-show="addinventoryForm.VisaAcqID.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="VisaAcqID"  type='text' ng-disabled='isInputDisabled' id='VisaAcqID' maxlength='10' name='VisaAcqID' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							       <label>Verve Acquirer Id<span ng-show="addinventoryForm.VerAcqID.$touched ||addinventoryForm.VerAcqID.$dirty && addinventoryForm.VerAcqID.$invalid">
								<span class = 'err' ng-show="addinventoryForm.VerAcqID.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="VerAcqID"  type='text' ng-disabled='isInputDisabled' id='VerAcqID'  maxlength='10' name='VerAcqID' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							       <label>Master Acquirer Id<span ng-show="addinventoryForm.MastAcqID.$touched ||addinventoryForm.MastAcqID.$dirty && addinventoryForm.MastAcqID.$invalid">
								<span class = 'err' ng-show="addinventoryForm.MastAcqID.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="MastAcqID"  type='text' ng-disabled='isInputDisabled' id='MastAcqID' ng-minlength="10" maxlength='11' name='MastAcqID' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							       <label>New Terminal Owner Code<span ng-show="addinventoryForm.NewTerOwnCode.$touched ||addinventoryForm.NewTerOwnCode.$dirty && addinventoryForm.NewTerOwnCode.$invalid">
								<span class = 'err' ng-show="addinventoryForm.NewTerOwnCode.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="NewTerOwnCode"  type='text' ng-disabled='isInputDisabled' id='NewTerOwnCode' maxlength='5' name='NewTerOwnCode' required class='form-control'/>
							</div>
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							       <label>Lga<span ng-show="addinventoryForm.Lga.$touched ||addinventoryForm.Lga.$dirty && addinventoryForm.Lga.$invalid">
								<span class = 'err' ng-show="addinventoryForm.Lga.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="Lga"  type='text' ng-disabled='isInputDisabled' id='Lga' ng-minlength="11" maxlength='11' name='Lga' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							       <label>Merchant Account Name<span ng-show="addinventoryForm.MerAccName.$touched ||addinventoryForm.MerAccName.$dirty && addinventoryForm.MerAccName.$invalid">
								<span class = 'err' ng-show="addinventoryForm.MerAccName.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="MerAccName"  type='text' ng-disabled='isInputDisabled' id='MerAccName'  maxlength='100' name='MerAccName' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							       <label>PTSP<span ng-show="addinventoryForm.PTSP.$touched ||addinventoryForm.PTSP.$dirty && addinventoryForm.PTSP.$invalid">
								<span class = 'err' ng-show="addinventoryForm.PTSP.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="PTSP"  type='text' ng-disabled='isInputDisabled' id='PTSP'  maxlength='20' name='PTSP' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							       <label>Test Terminal<span ng-show="addinventoryForm.TestTerm.$touched ||addinventoryForm.TestTerm.$dirty && addinventoryForm.TestTerm.$invalid">
								<span class = 'err' ng-show="addinventoryForm.TestTerm.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="TestTerm"  type='text' ng-disabled='isInputDisabled' id='TestTerm'  maxlength='11' name='TestTerm' required class='form-control'/>
							</div>
												
						</div>
						</div>
						</form>	
						</div>
			<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo BANK_ACCOUNT_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo BANK_ACCOUNT_CREATE_BUTTON_CANCEL; ?></a>
					<button type="button" class="btn btn-primary" ng-click='addPBankForm.$invalid=true;create()' ng-hide='isHide' ng-click='refresh()' id="Create"><?php echo BANK_ACCOUNT_CREATE_BUTTON_CREATE; ?></button>
			</div>
		
	
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

 