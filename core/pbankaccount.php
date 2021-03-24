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
	$partyType = $_SESSION['party_type'];
	$profileId = $_SESSION['profile_id'];
	$partyCode = $_SESSION['party_code'];
	$agent_name	=   $_SESSION['party_name'];
?>
<style>
#AddCountryDialogue .table > tbody > tr > td {
	border:none;
}
.bigdrop {
    width: 250px !important;
}
</style>
<div ng-controller= "pBankCtrl">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!ptyacc"><?php echo BANK_ACCOUNT_MAIN_HEADING1; ?></a></li>
			<li><a href="#!ptyacc"><?php echo BANK_ACCOUNT_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class='row'>
		<?php   if($profileId == 1 || $profileId == 10 || ($profileId >= 20 && $profileId <= 30) || $profileId == 50 ) {?>
			<input type='button' style='float: right;margin-right: 2%;' class='btn btn-primary' value='<?php echo BANK_ACCOUNT_CREATE_HEADING1; ?>' id='Create' href='#' data-toggle='modal' data-target='#AddBankAccountDialogue'/>
		<?php } ?>
		<?php   if($profileId == 51) { ?>
		<input type='button' style='float: right;margin-right: 2%;' class='btn btn-primary' value='Create' ng-disabled="iscreate" id='Create' href='#' data-toggle='modal' data-target='#AddBankAccountDialogue'/>
		<?php } ?>
		</div>
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo BANK_ACCOUNT_MAIN_HEADING3; ?></span>
				</div>
				
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding" data-backdrop="static" data-keyboard="false">		
                <div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>			
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>
							<th><?php echo BANK_ACCOUNT_MAIN_TABLE_PARTY_CODE; ?></th>
      						<th><?php echo BANK_ACCOUNT_MAIN_TABLE_BANK_ACCOUNT; ?></th>
							<th><?php echo BANK_ACCOUNT_MAIN_TABLE_BANK_ACCOUNT_BRANCH; ?></th>
							<th><?php echo BANK_ACCOUNT_MAIN_TABLE_BANK_MASTER_NAME; ?></th>
							<?php   if($profileId == 1 || $profileId == 10 || ($profileId >= 20 && $profileId <= 30))  {?>
							<th>View</th>
							<th><?php echo BANK_ACCOUNT_MAIN_TABLE_EDIT; ?></th>
							<th>Approve | Reject</th>
							<?php } ?>
							<?php  if($profileId == 51 || $profileId == 50) { ?>
							<th>View</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
					      <tr ng-repeat="x in banklist">
							<td>{{ x.partyCode }}</td>	
						 	<td>{{ x.account }}</td>
							<td>{{ x.branch }}</td>
							<td>{{ x.name }}</td>
							<?php   if($profileId == 1 || $profileId == 10 || ($profileId >= 20 && $profileId <= 30)) {?>
							<td><a id={{x.id}} class='editcountry' ng-click='view($index,x.id)' data-toggle='modal' data-target='#LinkaccountDialogue'>
							<button  class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a></td>
							<td><a id={{x.id}} class='editcountry' ng-click='edit($index,x.id)' data-toggle='modal' data-target='#EditBankAccountrDialogue'>
							<button  class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
							<td ng-if = "x.status=='E'"><a id={{x.id}} class='editcountry'  data-toggle='modal' data-target='#ApproveBankAccountrDialogue'>
							<button  class='icoimg'><img style='height:22px;width:22px' ng-click="apprejId($index,x.id,'Approve')"  src='../common/images/tick.png' /></button></a> | &nbsp;
							<a id={{x.id}} class='editcountry'  data-toggle='modal' data-target='#ApproveBankAccountrDialogue'>
							<button  class='icoimg'><img style='height:22px;width:22px' ng-click="apprejId($index,x.id,'Reject')" src='../common/images/error.png' /></button></a></td>
							<td ng-if = "x.status!='E'">-</td>
								<?php } ?>
								<?php  if($profileId == 51 || $profileId == 50) { ?>
							<td><a id={{x.id}} class='editcountry' ng-click='view($index,x.id)' data-toggle='modal' data-target='#LinkaccountDialogue'>
							<button  class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a></td>
								<?php } ?>
							</tr>
						</tbody>
					
				</table>
			</div>
		</div>
	</div>
	 <div id='EditBankAccountrDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" ng-click="refresh()" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo BANK_ACCOUNT_EDIT_PARTY_BANK_ACCOUNT; ?></h2>
					</div>		
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>					
					<div class='modal-body' style='margin-top:2%'>
					<form action="" method="POST" name='editPBankForm' id="editPBankForm">
						<div id='PBankAccountEditBody'  ng-hide='isLoader'>
							<div class='row' >
							<?php   if($profileId == 1 || $profileId == 10 || ($profileId >= 20 && $profileId <= 30)) {?>
							 <div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 '>											
								<label><?php echo BANK_ACCOUNT_PARTY_TYPE; ?><span class='spanre'>*</span>
								<span ng-show="infoViewForm.partyType.$dirty && infoViewForm.partyType.$invalid">
								<span class = 'err' ng-show="infoViewForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select disabled  ng-model="partyType"  class='form-control' name = 'partyType' id='partytype' required>											
									<option value=""><?php echo BANK_ACCOUNT_CREATE_SELECT_TYPE; ?></option>
									<option value='A'><?php echo BANK_ACCOUNT_CREATE_AGENT; ?></option>
									<option value='C'><?php echo BANK_ACCOUNT_CREATE_CHAMPION; ?></option>
									<option value='A'><?php echo BANK_ACCOUNT_CREATE_SUB_AGENT; ?></option>
									<option value='P'><?php echo BANK_ACCOUNT_CREATE_PERSONAL; ?></option>
								</select>											
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>										
								<label><?php echo BANK_ACCOUNT_PARTY_CODE; ?><span class='spanre'>*</span>
								<span ng-show="infoViewForm.partyCode.$dirty && infoViewForm.partyCode.$invalid">
								<span class = 'err' ng-show="infoViewForm.partyCode.$error.required"><?php echo REQUIRED;?></span></span></label>	
								 <input type='text' readonly name='partyCode' required ng-model='partyCode' id='partyCode' class='form-control'/>	
																	
							</div>
							<?php } ?>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo BANK_ACCOUNT_BANK_MASTER; ?><span class='spanre'>*</span><span ng-show="editPBankForm.bankmaster.$touched ||editPBankForm.bankmaster.$dirty && editPBankForm.bankmaster.$invalid">
								<span class = 'err' ng-show="editPBankForm.bankmaster.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="bankmaster"  class='form-control' name = 'bankmaster' id='bankmaster' required>											
									<option value=''><?php echo BANK_ACCOUNT_CREATE_SELECT_BANK_MASTER; ?></option>
									<option ng-repeat="master in bankmasterss" value="{{master.id}}">{{master.name}}</option>
								</select>
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo BANK_ACCOUNT_ACC_NAME; ?><span class='spanre'>*</span><span ng-show="editPBankForm.accname.$touched ||editPBankForm.accname.$dirty && editPBankForm.accname.$invalid">
								<span class = 'err' ng-show="editPBankForm.accname.$error.required"><?php echo REQUIRED;?></span></span></label>
								 <input type='text' name='accname' required ng-model='accname' id='accname' class='form-control'/>	
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo BANK_ACCOUNT_ACC_NO; ?><span class='spanre'>*</span><span ng-show="editPBankForm.accno.$touched ||editPBankForm.accno.$dirty && editPBankForm.accno.$invalid">
								<span class = 'err' ng-show="editPBankForm.accno.$error.required"><?php echo REQUIRED;?></span></span></label>
								 <input autofocus="true" type='text' numbers-only name='accno'  required ng-model='accno' id='accno' class='form-control'/>	
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo BANK_ACCOUNT_ACC_RENO; ?><span class='spanre'>*</span><span ng-show="editPBankForm.reaccno.$touched ||editPBankForm.reaccno.$dirty && editPBankForm.reaccno.$invalid">
								<span class = 'err' ng-show="editPBankForm.reaccno.$error.required"><?php echo REQUIRED;?></span></span>
								<span class = 'err' ng-show="(editPBankForm.accno.$modelValue !== editPBankForm.reaccno.$modelValue) "><?php echo BANK_ACCOUNT_VALID_ACCNT_NO_DOESNOT_MATCH; ?></span></label>
								 <input type='text' numbers-only name='reaccno' required ng-model='reaccno'  id='reaccno' class='form-control'/>	
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo BANK_ACCOUNT_EDIT_ADDRESS; ?><span class='spanre'>*</span><span ng-show="editPBankForm.bankaddress.$touched ||editPBankForm.bankaddress.$dirty && editPBankForm.bankaddress.$invalid">
								<span class = 'err' ng-show="editPBankForm.bankaddress.$error.required"><?php echo REQUIRED;?></span></span></label>
								 <textarea type='text'  name='bankaddress' required style="height:34px" ng-model='bankaddress' id='bankaddress' class='form-control'/>	
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo BANK_ACCOUNT_BANK_BRANCH; ?><span class='spanre'>*</span><span ng-show="editPBankForm.bankbranch.$touched ||editPBankForm.bankbranch.$dirty && editPBankForm.bankbranch.$invalid">
								<span class = 'err' ng-show="editPBankForm.bankbranch.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input type='text'name='bankbranch' required ng-model='bankbranch' id='bankbranch' class='form-control'/>	
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo BANK_ACCOUNT_ACTIVE; ?><span ng-show="editPBankForm.active.$touched ||editPBankForm.active.$dirty && editPBankForm.active.$invalid">
								<span class = 'err' ng-show="editPBankForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<select ng-model="active" class='form-control' name = 'active' id='Active' required >
                               			<option value=''><?php echo BANK_ACCOUNT_CREATE_SELECT_ACTIVE; ?></option>
										<option value='Y'><?php echo BANK_ACCOUNT_CREATE_ACTIVE_YES; ?></option>
										<option value='N'><?php echo BANK_ACCOUNT_CREATE_ACTIVE_NO; ?></option>
									</select>
							</div>	
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label>Status<span class='spanre'></span><span ng-show="editPBankForm.statuss	.$touched ||editPBankForm.statuss.$dirty && editPBankForm.statuss.$invalid">
								<span class = 'err' ng-show="editPBankForm.statuss.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select  ng-model="statuss" class='form-control' ng-disabled = "statuss!='A' && statuss!='S'"  name = 'statuss' id='statuss' required >
										<option value='E' ng-hide="statuss=='A' || statuss=='S'">Entered</option>
										<option value='A' >Approved</option>
										<option value='R' ng-hide="statuss=='A' || statuss=='S'">Rejected</option>
										<option value='S' >Suspended</option>
										<option value='I' ng-hide="statuss=='A' || statuss=='S'">In-Progress</option>
										<option value='O' ng-hide="statuss=='A' || statuss=='S'">Open</option>
								</select>
								
							</div>	
							
						</div>
						</div>
						</form>	
						</div>
			<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal'  id='Ok' ng-hide='isHideOk' ><?php echo BANK_ACCOUNT_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-click="refresh()" ng-hide='isHide' ><?php echo BANK_ACCOUNT_CREATE_BUTTON_CANCEL; ?></a>
					<button type='button' class='btn btn-primary'  ng-hide='isHide' ng-disabled="(editPBankForm.$invalid) || (accno !== reaccno)" ng-click="editPBankForm.$invalid=true;update(id)"  id="Update" >Update</button>
			</div>		
	</div>
	</div>	
</div></div>
		
 <div id='ApproveBankAccountrDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" ng-click="refresh()">&times;</button>
						<h2 style='text-align:center'>Party Bank Account - {{flag}}</h2>
					</div>		
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>					
					<div class='modal-body' style='margin-top:2%'>
					<form action="" method="POST" name='approvePBankForm' id="approvePBankForm" ng-model='resmsg'>
						
					     <h3>Are You Sure {{flag}} This Bank?</h3>
					</form>	
						</div>
			<div class='modal-footer' >
					<button type='button' class='btn btn-primary' data-dismiss='modal'  id='Ok' ng-hide='isHideOk' ><?php echo BANK_ACCOUNT_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-click="refresh()" ng-hide='isHide' ><?php echo BANK_ACCOUNT_CREATE_BUTTON_CANCEL; ?></a>
					<button type='button' class='btn btn-primary'  ng-hide='isHide' ng-disabled="(approvePBankForm.$invalid) || (accno !== reaccno)" ng-click="approvePBankForm.$invalid=true;approve(id,flag)"  id="Update" >{{flag}}</button>
			</div>		
	</div>
	</div>	
</div>
		
	<div id='AddBankAccountDialogue' class='modal fade' role='dialog' data-backdrop="static" data-keyboard="false" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" ng-click="refresh()">&times;</button>
						<h2 style='text-align:center'><?php echo BANK_ACCOUNT_CREATE_HEADING1;?></h2>
					</div>					 
					<div  style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body' style='margin-top:2%'>
					<form action="" method="POST" name='addPBankForm' id="addPBankForm">					
						<div id='PBankAccountCreateBody'  ng-hide='isLoader'>
							<div class='row' >
							<?php   if($profileId == 1 || $profileId == 10 || ($profileId >= 20 && $profileId <= 30)) { ?>
							 <div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>											
								<label><?php echo BANK_ACCOUNT_CREATE_PARTY_TYPE; ?><span class='spanre'>*</span>
								<span ng-show="infoViewForm.partyType.$dirty && infoViewForm.partyType.$invalid">
								<span class = 'err' ng-show="infoViewForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-change='partyload(this.partyType)' ng-model="partyType"  class='form-control' name = 'partyType' id='partytype' required>											
									<option value=""><?php echo BANK_ACCOUNT_CREATE_SELECT_TYPE; ?></option>
									<option value='MA'><?php echo BANK_ACCOUNT_CREATE_AGENT; ?></option>
									<option value='C'><?php echo BANK_ACCOUNT_CREATE_CHAMPION; ?></option>
									<option value='SA'><?php echo BANK_ACCOUNT_CREATE_SUB_AGENT; ?></option>
									<option value='P'><?php echo BANK_ACCOUNT_CREATE_PERSONAL; ?></option>
								</select>											
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>										
								<label><?php echo BANK_ACCOUNT_CREATE_PARTY_CODE; ?><span class='spanre'>*</span>
								<span ng-show="infoViewForm.partyCode.$dirty && infoViewForm.partyCode.$invalid">
								<span class = 'err' ng-show="infoViewForm.partyCode.$error.required"><?php echo REQUIRED;?></span></span></label>	
								<select  ng-model='partyCode' class='form-control search' name='partyCode' required >
								<option value=""><?php echo BANK_ACCOUNT_CREATE_SELECT_PARTY_CODE; ?></option>												
								<option ng-repeat="info in infos" value="{{info.code}}">{{info.code}} - {{info.name}}</option>
								</select>										
							</div>
							<?php } ?>
							
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo BANK_ACCOUNT_CREATE_BANK_MASTER; ?><span class='spanre'>*</span><span ng-show="addPBankForm.bankmaster.$touched ||addPBankForm.bankmaster.$dirty && addPBankForm.bankmaster.$invalid">
								<span class = 'err' ng-show="addPBankForm.bankmaster.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="bankmaster"  class='form-control' name = 'bankmaster' id='bankmaster' required>											
									<option value=''><?php echo BANK_ACCOUNT_CREATE_SELECT_BANK_MASTER; ?></option>
									<option ng-repeat="master in bankmasterss" value="{{master.id}}">{{master.name}}</option>
								</select>
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo BANK_ACCOUNT_CREATE_ACC_NAME; ?><span class='spanre'>*</span><span ng-show="addPBankForm.accname.$touched ||addPBankForm.accname.$dirty && addPBankForm.accname.$invalid">
								<span class = 'err' ng-show="addPBankForm.accname.$error.required"><?php echo REQUIRED;?></span></span></label>
								 <input type='text' name='accname' required ng-model='accname' id='accname' class='form-control'/>	
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo BANK_ACCOUNT_CREATE_ACC_NO; ?><span class='spanre'>*</span><span ng-show="addPBankForm.accno.$touched ||addPBankForm.accno.$dirty && addPBankForm.accno.$invalid">
								<span class = 'err' ng-show="addPBankForm.accno.$error.required"><?php echo REQUIRED;?></span></span></label>
								 <input type='text' numbers-only name='accno' required ng-model='accno' id='accno' class='form-control'/>	
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo BANK_ACCOUNT_CREATE_ACC_RENO; ?><span class='spanre'>*</span><span ng-show="addPBankForm.reaccno.$touched ||addPBankForm.reaccno.$dirty && addPBankForm.reaccno.$invalid">
								<span class = 'err' ng-show="addPBankForm.reaccno.$error.required"><?php echo REQUIRED;?></span></span>
								<span class = 'err' ng-show="(addPBankForm.accno.$modelValue !== addPBankForm.reaccno.$modelValue) "><?php echo BANK_ACCOUNT_VALID_ACCNT_NO_DOESNOT_MATCH; ?></span></label>
								 <input type='text' numbers-only name='reaccno' required ng-model='reaccno' id='reaccno' class='form-control'/>	
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo BANK_ACCOUNT_CREATE_BANK_ADDRESS; ?><span class='spanre'>*</span><span ng-show="addPBankForm.bankaddress.$touched ||addPBankForm.bankaddress.$dirty && addPBankForm.bankaddress.$invalid">
								<span class = 'err' ng-show="addPBankForm.bankaddress.$error.required"><?php echo REQUIRED;?></span></span></label>
								 <textarea type='text'  name='bankaddress' required ng-model='bankaddress' id='bankaddress' class='form-control'/>	
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo BANK_ACCOUNT_BANK_BRANCH; ?><span class='spanre'>*</span><span ng-show="addPBankForm.bankbranch.$touched ||addPBankForm.bankbranch.$dirty && addPBankForm.bankbranch.$invalid">
								<span class = 'err' ng-show="addPBankForm.bankbranch.$error.required"><?php echo REQUIRED;?></span></span></label>
								 <input type='text'name='bankbranch' required ng-model='bankbranch' id='bankbranch' class='form-control'/>	
							</div>
							<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12'>
								<label><?php echo BANK_ACCOUNT_CREATE_ACTIVE; ?><span ng-show="addPBankForm.active.$touched ||addPBankForm.active.$dirty && addPBankForm.active.$invalid">
								<span class = 'err' ng-show="addPBankForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<select ng-model="active" class='form-control' name = 'active' id='Active' required >
                               			<option value=''><?php echo BANK_ACCOUNT_CREATE_SELECT_ACTIVE; ?></option>
										<option value='Y'><?php echo BANK_ACCOUNT_CREATE_ACTIVE_YES; ?></option>
										<option value='N'><?php echo BANK_ACCOUNT_CREATE_ACTIVE_NO; ?></option>
									</select>
							</div>	
						</div>
						</div>
						</form>	
						</div>
			<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo BANK_ACCOUNT_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-click="refresh()" id='cancel' ng-hide='isHide' ><?php echo BANK_ACCOUNT_CREATE_BUTTON_CANCEL; ?></a>
					<button type="button" class="btn btn-primary" ng-click='addPBankForm.$invalid=true;create()' ng-hide='isHide'  ng-disabled="(addPBankForm.$invalid) || (accno !== reaccno)"  id="Create"><?php echo BANK_ACCOUNT_CREATE_BUTTON_CREATE; ?></button>
			</div>
		
	
	</div>	
</div>
</div>
 <div id='LinkaccountDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Link Account Details - {{accname}}</h2>
		
				</div>				
					<div  style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
						<form action="" method="POST" name='editINFOForm' id="EditINFOForm">
						<div id='AuthBody'>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
							<label> Party Bank Account Id : <span style='color:blue'> {{id}}</span></label>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Party Type : <span style='color:blue'>{{PartyType}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Party Code : <span style='color:blue'>{{PartyCode}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Bank Master Id : <span style='color:blue'>{{bankmasterid}}</span></label>								
							</div>
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Account Number : <span style='color:blue'>{{accno}}</span></label>								
							</div>
							<div  class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Account Name : <span style='color:blue'>{{accname}}</span></label>								
							</div>
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Bank Address : <span style='color:blue'>{{bankaddress}}</span></label>								
							</div>	
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Bank Branch : <span style='color:blue'>{{bankbranch}}</span></label>								
							</div>
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Active : <span style='color:green'>{{Active}}</span></label>								
							</div>								
												
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Status : <span style='color:red'>{{Status}}</span></label>								
							</div>							
							<div ng-show='sub_agent' class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Create User : <span style='color:blue'>{{createuser}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Create Time: <span style='color:blue'>{{createtime}}</span></label>								
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

	$("#EditBankAccountrDialogue, #AddBankAccountDialogue, #ApproveBankAccountrDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	$("#EditBankAccountrDialogue, #AddBankAccountDialogue, #ApproveBankAccountrDialogue").on("click","#cancel",function() {
		window.location.reload();
	});
	/* window.alert = function() {};
     alert = function() {}; */
	 
	/*   $("#AddBankAccountDialogue .search").select2({
        dropdownParent: $('#AddBankAccountDialogue'),
		dropdownCssClass : 'bigdrop'
		} ); */
});
</script>

 