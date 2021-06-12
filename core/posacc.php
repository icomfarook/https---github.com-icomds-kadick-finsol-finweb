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
	$profileId = $_SESSION['profile_id'];
?>

<div ng-controller= "posaccCtrl">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!acsacs"><?php echo POS_ACCESS_MAIN_HEADING1; ?></a></li>
			<li><a href="#!acsacs">POS ACCESS</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class='row'>
			<input type='button' style='float: right;margin-right: 2%;' class='btn btn-primary' value='<?php echo POS_ACCESS_MAIN_NEW_USER_POS; ?>' id='Create' href='#' data-toggle='modal' data-target='#AddPosaccDialogue'/>
		</div>
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>POS ACCESS</span>
				</div>
				
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding">		
                <div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>			
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>
							<th><?php echo POS_ACCESS_MAIN_TABLE_ID; ?></th>
      						<th><?php echo POS_ACCESS_MAIN_TABLE_USER; ?></th>
							<th><?php echo POS_ACCESS_MAIN_TABLE_IMEI_NO; ?></th>
							<th><?php echo POS_ACCESS_MAIN_TABLE_PIN_NO; ?></th>
							<th><?php echo POS_ACCESS_MAIN_TABLE_STATUS; ?></th>
							<th><?php echo POS_ACCESS_MAIN_TABLE_EDIT; ?></th>
							<th> Control </th>
							<th> NIBBS </th>
							<th> Limits </th>
						</tr>
					</thead>
					<tbody>
					      <tr ng-repeat="x in userposlist">
						 	<td>{{ x.id }}</td>
							<td>{{ x.name }}</td>
							<td>{{ x.imei }}</td>
							<td>{{ x.pin }}</td>
							<td>{{ x.status }}</td>
							<td><a id={{x.id}}class='editposacc' ng-click='edit($index,x.id)' data-toggle='modal' data-target='#EditPosaccDialogue'>
							<button id = '".$row['user_id']."' class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
							<td ><a id={{x.id}} class='icoimg' ng-click='control($index,x.userid,x.name)'  data-toggle='modal' data-target='#controlposDialogue'>
								<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/dotp.png' /></button></a>
							</td>
							<td ><a id={{x.id}} class='icoimg' ng-click='nibssedit($index,x.userid)'  data-toggle='modal' data-target='#nibssposDialogue'>
								<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
							</td>
							<td ><a id={{x.id}} class='icoimg' ng-click='limit($index,x.userid)'  data-toggle='modal' data-target='#limitposDialogue'>
								<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/sotp.png' /></button></a>
							</td>
							</tr>
						</tbody>
					
				</table>
			</div>
		</div>
	</div>
	 <div id='EditPosaccDialogue' class='modal fade' role='dialog'  data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo POS_ACCESS_EDIT_HEADING1; ?> {{code}}</h2>
					</div>		
					<div style='text-align:center'  class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>					
					<div class='modal-body'>
					<form action="" method="POST" name='editPosaccForm' id="EditPosaccForm">
						<div id='PosaccBody'  ng-hide='isLoader'>
							<div class='row' style='padding:0px 15px'>
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
									<label><?php echo POS_ACCESS_EDIT_USER; ?><span ng-show="editPosaccForm.name.$touched ||editPosaccForm.name.$dirty && editPosaccForm.name.$invalid">
									<span class = 'err' ng-show="editPosaccForm.name.$error.required"><?php echo REQUIRED;?></span></span></label>									
									<select  ng-model='name'  class='form-control' name='username' required id='username'>
										<option ng-repeat="user in users" value="{{user.id}}">{{user.name}}</option>
									</select>
								</div>
						
								
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
									<label> <?php echo POS_ACCESS_EDIT_PIN_NO; ?><span ng-show="editPosaccForm.pinno.$touched ||editPosaccForm.pinno.$dirty && editPosaccForm.pinno.$invalid">
									<span class = 'err' ng-show="editPosaccForm.pinno.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<input type='text' name='pinno' required ng-model='pin' maxlength='10' id='pinno' class='form-control'/>
								</div>
							
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
									<label> <?php echo POS_ACCESS_EDIT_IMEI_NO; ?><span ng-show="editPosaccForm.imeino.$touched ||editPosaccForm.imeino.$dirty && editPosaccForm.imeino.$invalid">
									<span class = 'err' ng-show="editPosaccForm.imeino.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<input type='text' name='imeino' required ng-model='imei' maxlength='50' id='imeino' class='form-control'/>
								</div>
									
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
									<label><?php echo POS_ACCESS_EDIT_STATUS; ?><span ng-show="addPosaccForm.status.$touched ||editPosaccForm.status.$dirty && editPosaccForm.status.$invalid">
									<span class = 'err' ng-show="editPosaccForm.status.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<select ng-model="status" class='form-control' name = 'status' id='status' required >
											<option value=''><?php echo POS_ACCESS_EDIT_STATUS_SELECT; ?></option>									
											<option value='B'><?php echo POS_ACCESS_EDIT_STATUS_B; ?></option>
											<option value='U'><?php echo POS_ACCESS_EDIT_STATUS_U; ?></option>
											<option value='X'><?php echo POS_ACCESS_EDIT_STATUS_X; ?></option>
											</select>
								 </div>	
							</div>								 
						</div>
						</form>
						</div>
						<div class='modal-footer'>
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo POS_ACCESS_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo POS_ACCESS_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="editPosaccForm.$invalid" ng-click="editPosaccForm.$invalid=true;update(id)" id="Update"><?php echo POS_ACCESS_EDIT_BUTTON_UPDATE; ?></button>
					</div>	
	</div>
	</div>	
</div></div>

         <div id='nibssposDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'> User POS NIBSS Edit - {{userid}}</h2>
					</div>		
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>					
					<div class='modal-body' >
					<form action="" method="POST" name='InventoryEditForm' id="InventoryEditForm">
						<div id='nibsscreateBody'  ng-hide='isLoader'>
							<div class='row' >
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label>Nibbs Key 1</label><span ng-show="InventoryEditForm.id.$touched ||InventoryEditForm.id.$dirty && InventoryEditForm.id.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.id.$error.required"><?php echo REQUIRED;?></span></span>
								<input ng-model="nibskey" numbers-only type='text'  ng-disabled='isInputDisabled' id='id'  maxlength='10' name='id' required class='form-control'/>
							</div>	
							
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label>Nibss Key 2<span ng-show="InventoryEditForm.merchantid.$touched ||InventoryEditForm.merchantid.$dirty && InventoryEditForm.merchantid.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.merchantid.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="nibskey2"  type='text' ng-disabled='isInputDisabled' id='merchantid'  maxlength='11' name='merchantid' required class='form-control'/>
							</div>	
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label>Nibss Server IP<span ng-show="InventoryEditForm.merchantname.$touched ||InventoryEditForm.merchantname.$dirty && InventoryEditForm.merchantname.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.merchantname.$error.required"><?php echo REQUIRED;?></span></span></label>
								 <input type='text' name='merchantname' required ng-model='serverip' id='merchantname' class='form-control'/>	
							</div>
																		
							
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label>Nibbs Server Port<span ng-show="InventoryEditForm.termimodelCode.$touched ||InventoryEditForm.termimodelCode.$dirty && InventoryEditForm.termimodelCode.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.termimodelCode.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="serverport"  type='text' ng-disabled='isInputDisabled' id='termimodelCode'  maxlength='11' name='termimodelCode' required class='form-control'/>
							</div>	
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label>App Time Out<span ng-show="InventoryEditForm.TerminalId.$touched ||InventoryEditForm.TerminalId.$dirty && InventoryEditForm.TerminalId.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.TerminalId.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="Timeout"  type='text' ng-disabled='isInputDisabled' id='TerminalId'  maxlength='11' name='TerminalId' required class='form-control'/>
							</div>	
							
										
						</div>
						</div>
						</form>	
						</div>
			<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-click='refresh()' ng-hide='isHideOk' ><?php echo BANK_ACCOUNT_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo BANK_ACCOUNT_CREATE_BUTTON_CANCEL; ?></a>
					<button type='button' class='btn btn-primary'  ng-hide='isHide'  ng-click="InventoryEditForm.$invalid=true;nibssupdate(x.userid)" ng-click='refresh()'  id="Update" >Update</button>
			</div>	
</div>					
	</div>
	</div>	
	
	 <div id='limitposDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'> User POS Limits Edit - {{userid}}</h2>
					</div>		
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>					
					<div class='modal-body' >
					<form action="" method="POST" name='LimitEditForm' id="LimitEditForm">
						<div id='limiteditBody'  ng-hide='isLoader'>
							<div class='row' >
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label>Payment Max Limit<span class='spanre'>*</span><span ng-show="LimitEditForm.paymaxlimit.$touched ||LimitEditForm.paymaxlimit.$dirty && LimitEditForm.paymaxlimit.$invalid">
								<span class = 'err' ng-show="LimitEditForm.paymaxlimit.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="paymaxlimit"  type='text'  ng-disabled='isInputDisabled' id='paymaxlimit'  maxlength='10' name='paymaxlimit' required class='form-control'/>
							</div>	
							
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label>Payment Min Limit<span class='spanre'>*</span><span ng-show="LimitEditForm.payminlimit.$touched ||LimitEditForm.payminlimit.$dirty && LimitEditForm.payminlimit.$invalid">
								<span class = 'err' ng-show="LimitEditForm.payminlimit.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="payminlimit"  type='text' ng-disabled='isInputDisabled' id='payminlimit'  maxlength='11' name='payminlimit' required class='form-control'/>
							</div>	
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label>Cash in Max Limit<span class='spanre'>*</span><span ng-show="LimitEditForm.cashinmax.$touched ||LimitEditForm.cashinmax.$dirty && LimitEditForm.cashinmax.$invalid">
								<span class = 'err' ng-show="LimitEditForm.cashinmax.$error.required"><?php echo REQUIRED;?></span></span></label>
								 <input type='text' name='cashinmax'  ng-disabled='isInputDisabled'  required ng-model='cashinmax' id='cashinmax' class='form-control'/>	
							</div>
																		
							
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label>Cash in Min Limit<span class='spanre'>*</span><span ng-show="LimitEditForm.cashinmin.$touched ||LimitEditForm.cashinmin.$dirty && LimitEditForm.cashinmin.$invalid">
								<span class = 'err' ng-show="LimitEditForm.cashinmin.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="cashinmin"  type='text' ng-disabled='isInputDisabled' id='cashinmin'  maxlength='11' name='cashinmin' required class='form-control'/>
							</div>	
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label>Cash out Max Limit<span class='spanre'>*</span><span ng-show="LimitEditForm.cashoutmax.$touched ||LimitEditForm.cashoutmax.$dirty && LimitEditForm.cashoutmax.$invalid">
								<span class = 'err' ng-show="LimitEditForm.cashoutmax.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="cashoutmax"  type='text' ng-disabled='isInputDisabled' id='cashoutmax'  maxlength='11' name='cashoutmax' required class='form-control'/>
							</div>	
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label>Cash out Min Limit<span class='spanre'>*</span><span ng-show="LimitEditForm.cashoutmin.$touched ||LimitEditForm.cashoutmin.$dirty && LimitEditForm.cashoutmin.$invalid">
								<span class = 'err' ng-show="LimitEditForm.cashoutmin.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="cashoutmin"  type='text' ng-disabled='isInputDisabled' id='cashoutmin'  maxlength='11' name='cashoutmin' required class='form-control'/>
							</div>	
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label>Recharge Max Limit<span class='spanre'>*</span><span ng-show="LimitEditForm.rechargemaxlimit.$touched ||LimitEditForm.rechargemaxlimit.$dirty && LimitEditForm.rechargemaxlimit.$invalid">
								<span class = 'err' ng-show="LimitEditForm.rechargemaxlimit.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="rechargemaxlimit"  type='text' ng-disabled='isInputDisabled' id='rechargemaxlimit'  maxlength='11' name='rechargemaxlimit' required class='form-control'/>
							</div>	
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label>Recharge Min Limit<span class='spanre'>*</span><span ng-show="LimitEditForm.rechargeminlimit.$touched ||LimitEditForm.rechargeminlimit.$dirty && LimitEditForm.rechargeminlimit.$invalid">
								<span class = 'err' ng-show="LimitEditForm.rechargeminlimit.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="rechargeminlimit"  type='text' ng-disabled='isInputDisabled' id='rechargeminlimit'  maxlength='11' name='rechargeminlimit' required class='form-control'/>
							</div>	
							
										
						</div>
						</div>
						</form>	
						</div>
			<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-click='refresh()' ng-hide='isHideOk' ><?php echo BANK_ACCOUNT_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo BANK_ACCOUNT_CREATE_BUTTON_CANCEL; ?></a>
					<button type='button' class='btn btn-primary'  ng-hide='isHide'   ng-disabled = "LimitEditForm.$invalid"  ng-click="LimitEditForm.$invalid=true;limitupdate(x.userid)"   id="Update" >Update</button>
			</div>	
</div>					
	</div>
	</div>	

			

  <div id='controlposDialogue' class='modal fade' role='dialog'  data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>User POS Control Edit - {{name}}</h2>
					</div>		
					<div style='text-align:center'  class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>					
					<div class='modal-body'>
					<form action="" method="POST" name='editPosaccForm' id="EditPosaccForm">
						<div id='PosacccrtlBody'  ng-hide='isLoader' >
							<div class='row' style='padding:0px 15px'>
								<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12 form_col12_element' style=" margin-left: 34%;">
							<label><input   id='ctrl1' ng-true-value="'Y'" ng-false-value="'N'" type='checkbox'  name='control'  ng-model="ctrl1" /></label>
							<label>Account Base Access </label>
							</div>
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12  form_col12_element' style="margin-left: 34%;">
							<label><input      id='ctrl2' type='checkbox' ng-true-value="'Y'" ng-false-value="'N'" name='control' ng-model='ctrl2' /></label>
							<label>Card Base Access </label>
							</div>
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12 form_col12_element' style="margin-left: 34%;">
							<label><input    id='ctrl3' type='checkbox' ng-true-value="'Y'" ng-false-value="'N'" name='control' ng-model='ctrl3' /></label>
							<label>Recharge Access </label>
							</div>
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12 form_col12_element' style="margin-left: 34%;">
							<label><input   id='ctrl4' type='checkbox' ng-true-value="'Y'" ng-false-value="'N'" name='control' ng-model='ctrl4' /></label>
							<label>Bill Payment Access </label>
							</div>
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12 form_col12_element'style="margin-left: 34%;" >
							<label><input   id='ctrl5' type='checkbox' ng-true-value="'Y'" ng-false-value="'N'" name='control' ng-model='ctrl5' /></label>
							<label>Bank Service Access </label>
							</div>
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12 form_col12_element' style="margin-left: 34%;">
							<label><input   id='ctrl6' type='checkbox' ng-true-value="'Y'" ng-false-value="'N'" name='control' ng-model='ctrl6' /></label>
							<label>Group Service Access </label>
							</div>
						<?php if($profileId == 1 || $profileId == 10) {?>
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12 form_col12_element' style="margin-left: 34%;">
							<label><input   id='ctrl7' type='checkbox' ng-true-value="'Y'" ng-false-value="'N'" name='control' ng-model='ctrl7' /></label>
							<label>Debug Flag </label>
							</div>
						<?php } if($profileId == 1 ) { ?>
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12 form_col12_element' style="margin-left: 34%;">
							<label><input   id='ctrl8' type='checkbox' ng-true-value="'Y'" ng-false-value="'N'" name='control' ng-model='ctrl8' /></label>
							<label>MPOS Stimulate </label>
							</div>
						<?php } ?>	
							</div>								 
						</div>
						</form>
						</div>
						<div class='modal-footer'>
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-click='refresh()' ng-hide='isHideOk' ><?php echo POS_ACCESS_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo POS_ACCESS_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="editPosaccForm.$invalid" ng-click="editPosaccForm.$invalid=true;controlupdate(index,x.userid)"  id="Update"><?php echo POS_ACCESS_EDIT_BUTTON_UPDATE; ?></button>
					</div>	
	</div>
	</div>	
</div></div>
						
	<div id='AddPosaccDialogue' class='modal fade' role='dialog'>
		<div class="modal-dialog modal-md">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo POS_ACCESS_CREATE_HEADING1;?></h2>
					</div>		
						<div style='text-align:center'  class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body' style='margin-top:2%'>
					<form action="" method="POST" name='addPosaccForm' id="AddPosaccForm">
						<div id='PosaccCreateBody'  ng-hide='isLoader'>
							<div class='row' style='padding:0px 15px'>						
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12  form_col12_element'>
									<label><?php echo POS_ACCESS_CREATE_USER; ?><span ng-show="addPosaccForm.name.$touched ||addPosaccForm.name.$dirty && addPosaccForm.name.$invalid">
									<span class = 'err' ng-show="addPosaccForm.name.$error.required"><?php echo REQUIRED;?></span></span></label>
									<select  ng-model='name'  class='form-control' name='username' required >
									<option value=''>-- Select --</option>
										<option ng-repeat="user in users" value="{{user.id}}">{{user.name}}</option>
									</select>
								</div>						
						
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12  form_col12_element'>
									<label> <?php echo POS_ACCESS_CREATE_PIN_NO; ?><span ng-show="addPosaccForm.pin.$touched ||addPosaccForm.pin.$dirty && addPosaccForm.pin.$invalid">
									<span class = 'err' ng-show="addPosaccForm.pin.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<input  numbers-only type='text' name='pin' required ng-model='pin' maxlength='10' id='pin' class='form-control'/>
								</div>
						
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12  form_col12_element'>
									<label> <?php echo POS_ACCESS_CREATE_IMEI_NO; ?><span ng-show="addPosaccForm.imei.$touched ||addPosaccForm.imei.$dirty && addPosaccForm.imei.$invalid">
									<span class = 'err' ng-show="addPosaccForm.imei.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<input type='text' name='imei' required ng-model='imei' maxlength='50' id='imei' class='form-control'/>
				    			</div>			
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12   form_col12_element'>
								<label><?php echo POS_ACCESS_CREATE_STATUS; ?><span ng-show="addPosaccForm.status.$touched ||addCountryForm.status.$dirty && addCountryForm.status.$invalid">
								<span class = 'err' ng-show="addCountryForm.status.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<select ng-model="status" class='form-control' name = 'status' id='status' required >
                                        <option value=''><?php echo POS_ACCESS_CREATE_STATUS_SELECT; ?></option>									
										<option value='B'><?php echo POS_ACCESS_CREATE_STATUS_B; ?></option>
										<option value='U'><?php echo POS_ACCESS_CREATE_STATUS_U; ?></option>
										<option value='X'><?php echo POS_ACCESS_CREATE_STATUS_X; ?></option>
										</select>
							     </div>
							 </div>							
						</div>
						</form>	
					</div>
					<div class='modal-footer'>
							<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo 	POS_ACCESS_CREATE_BUTTON_OK; ?></button>
							<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo POS_ACCESS_CREATE_BUTTON_CANCEL; ?></a>
							<button type="button" class="btn btn-primary" ng-click='addPosaccForm.$invalid=true;create()' ng-hide='isHide' ng-disabled = "addPosaccForm.$invalid"  id="Create"><?php echo POS_ACCESS_CREATE_BUTTON_CREATE; ?></button>
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

	$("#EditPosaccDialogue, #AddPosaccDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	/* window.alert = function() {};
     alert = function() {}; */
});

</script>
