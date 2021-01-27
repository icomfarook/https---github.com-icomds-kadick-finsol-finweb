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
	//$partyType = "A";	
	//$partyCode = "AG0101";
	//$profileId = 1;
?>
<style>
#AddINFODialogue .table > tbody > tr > td {
	border:none;
}
#selUser  .select2-selection .select2-selection--single{
	height:30px !important;
	
}
</style>
<div ng-controller='walletCtrl' data-ng-init="fn_load(<?php echo "'".$partyType."',"."'".$partyCode."'" ?>)">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!ptywlt"><?php echo WALLET_MAIN_HEADING1; ?></a></li>
			<li><a href="#!ptywlt"><?php echo WALLET_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo WALLET_MAIN_HEADING3; ?></span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding" data-backdrop="static" data-keyboard="false">	
			<div  style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>
				<form name='infoViewForm' method='POST'>									
					<div class='row appcont'>	
					 <?php if($profileId == 50) { ?>
						<div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>&nbsp;&nbsp;
								<label><?php echo WALLET_PARTY_CODE_CHAMPION ; ?><span class='spanre'>*</span>
								<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
								<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  readonly = 'true' ng-disabled="creteria==='TP'" [(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='TP' type='radio' ng-init='topartyCode = "ALL"' name='creteria' ng-model='creteria' /></label>&nbsp;&nbsp;
								<label><?php echo WALLET_PARTY_CODE_AGENT; ?>	</label>
								<select  ng-model='topartyCode' ng-disabled="creteria==='SP'" class='form-control' name='topartyCode' required >
									<option value='ALL'>--ALL--</option>
									<option ng-repeat="info in infos" value="{{info.code}}">{{info.name}}</option>
								</select>										
							</div>
							
							 <div  class='col-lg-4 col-xs-12 col-sm-12 col-md-12' style='margin-top: inherit;'>
								<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='query()' ng-hide='isHide'  id="Query"><?php echo WALLET_VIEW_QUERY_BUTTON; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo WALLET_VIEW_REFRESH_BUTTON; ?></button>
							</div>
						</div>	
							 <?php }  if($profileId == 51) {?>
								 <div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
									 <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
										<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>
										<label><?php echo WALLET_PARTY_CODE_AGENT ; ?><span class='spanre'>*</span>
										<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
										<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
										<input  readonly = 'true'[(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
									</div>
									<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
										<label><input value='TP' type='radio' name='creteria' ng-model='creteria' /></label>
										<label><?php echo WALLET_PARTY_CODE_SUB_AGENT; ?>	</label>
										<select  ng-model='topartyCode' ng-disabled="creteria==='SP'" class='form-control' name='topartyCode' required >
											<option value=''><?php echo WALLET_SELECT_PARTY_CODE_SUB_AGENT; ?></option>
											<option ng-repeat="info in infos" value="{{info.code}}">{{info.name}}</option>
										</select>										
									</div>
									
									 <div style="margin-top:2%" class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
										<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='query()' ng-hide='isHide'  id="Query"><?php echo WALLET_VIEW_QUERY_BUTTON; ?></button>
										<button type="button" class="btn btn-primary"   id="Refresh"><?php echo WALLET_VIEW_REFRESH_BUTTON; ?></button>
									</div>
								</div>	
							  <?php }  if($profileId == 52) { ?>
									<div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
									 <div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
										<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>
										<label><?php echo WALLET_PARTY_CODE_AGENT ; ?><span class='spanre'>*</span>
										<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
										<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
										<input  readonly = 'true'[(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
									</div>
									
							 <div  class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='query()' ng-hide='isHide'  id="Query"><?php echo WALLET_VIEW_QUERY_BUTTON; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo WALLET_VIEW_REFRESH_BUTTON; ?></button>
							</div>
								</div>	
								
							  <?php }  if($profileId == 1 || $profileId == 10 || $profileId == 22 || $profileId == 20) {?>
								 <div class='row appcont'>
									 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
										
										<label><?php echo WALLET_PARTY_CODE_TYPE ; ?><span class='spanre'>*</span>
										<span ng-show="infoViewForm.partyType.$dirty && infoViewForm.partyType.$invalid">
										<span class = 'err' ng-show="infoViewForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></label>
										<select ng-change='partyload(this.partyType)' ng-model="partyType"  class='form-control' name = 'partyType' id='partytype' required>											
											<option value=""><?php echo WALLET_VIEW_SELECT_TYPE; ?></option>
											<option value='MA'><?php echo WALLET_VIEW_AGENT; ?></option>
											<option value='C'><?php echo WALLET_VIEW_CHAMPION; ?></option>
											<option value='SA'><?php echo WALLET_VIEW_SUB_AGENT; ?></option>
											<option value='P'><?php echo WALLET_VIEW_PERSONAL; ?></option>
										</select>
										
									</div>
									<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>										
										<label><?php echo WALLET_PARTY_CODE; ?>	<span class='spanre'>*</span>
											<span ng-show="infoViewForm.partyCode.$dirty && infoViewForm.partyCode.$invalid">
											<span class = 'err' ng-show="infoViewForm.partyCode.$error.required"><?php echo REQUIRED;?></span></span></label>
										<select  ng-model='partyCode'  id='selUser' class='form-control' name='topartyCode' required >
										<option value=""><?php echo TREATMENT_WALLET_VIEW_SELECT_PARTY_CODE; ?></option>
											<option ng-repeat="info in infos" value="{{info.code}}">{{info.code}} - {{info.name}}</option>
										</select>										
									</div>
								
									 <div style='margin-top: 25px;' class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
										<button type="button" class="btn btn-primary" ng-disabled = 'infoViewForm.$invalid' ng-click='infoViewForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo WALLET_VIEW_QUERY_BUTTON; ?></button>
										<button type="button" class="btn btn-primary"   id="Refresh"><?php echo WALLET_VIEW_REFRESH_BUTTON; ?></button>
									</div>
								</div>	
							
							  <?php } ?>								 
						</div>
							
																	
					<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo WALLET_MAIN_TABLE_ID; ?></th>
									<th><?php echo WALLET_MAIN_TABLE_CODE; ?></th>
									<th><?php echo WALLET_MAIN_TABLE_ASSIGNABLE; ?></th>
									<?php   if($profileId == 1 || $profileId == 10 || $profileId == 22 || $profileId == 20 || $profileId == 50) {?>	<th>Edit</th> <?php } ?>
								<th>View</th>
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in infoss">
									<td>{{ x.partyCode }}</td>
									<td>{{ x.name }}</td>
									<td>{{ x.lname }}</td>
									
										<?php   if($profileId == 1 || $profileId == 10 || $profileId == 22 || $profileId == 20 || $profileId == 50 ) {?><td ><a id={{x.code}} class='icoimg' ng-click='walletedit($index,x.partyCode,x.partyType,creteria)'  data-toggle='modal' data-target='#walletEditDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
							</td> <?php } ?>
						<td>	<a id={{x.code}} class='infoViewDialogue' ng-click='edit($index,x.partyCode,x.partyType,creteria)' data-toggle='modal' data-target='#infoViewDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
									</td>									 
								</tr>
								<tr ng-show="infoss.length==0">
									<td style='text-align:left' colspan='9' >
										<?php echo NO_DATA_FOUND; ?>               
									</td>
								</tr>
							</tbody>
						</table>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	 <?php   if($profileId == 1 || $profileId == 10 || $profileId == 22 || $profileId == 20 || $profileId == 50) {?>
	 <div id='walletEditDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'> Wallet Edit - {{partyCode}}</h2>
					</div>		
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>					
					<div class='modal-body' >
					<form action="" method="POST" name='WalletUpdateEditForm' id="WalletUpdateEditForm">
						<div id='walletupdateCreateBody'  ng-hide='isLoader'>
							<div class='row' >
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label>Daily Limit</label><span ng-show="WalletUpdateEditForm.dailyLimit.$touched ||WalletUpdateEditForm.dailyLimit.$dirty && WalletUpdateEditForm.dailyLimit.$invalid">
								<span class = 'err' ng-show="WalletUpdateEditForm.dailyLimit.$error.required"><?php echo REQUIRED;?></span></span>
								<input ng-model="dailyLimit" numbers-only type='text'  ng-disabled='isInputDisabled' id='id'  maxlength='10' name='dailyLimit' required class='form-control'/>
							</div>	
							
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label>Credit Limit<span ng-show="WalletUpdateEditForm.creditLimit.$touched ||WalletUpdateEditForm.creditLimit.$dirty && WalletUpdateEditForm.creditLimit.$invalid">
								<span class = 'err' ng-show="WalletUpdateEditForm.creditLimit.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="creditLimit" numbers-only  type='text' ng-disabled='isInputDisabled' id='creditLimit'  maxlength='11' name='creditLimit' required class='form-control'/>
							</div>	
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label>Minimum Balance<span ng-show="WalletUpdateEditForm.minimumBalance.$touched ||WalletUpdateEditForm.minimumBalance.$dirty && WalletUpdateEditForm.minimumBalance.$invalid">
								<span class = 'err' ng-show="WalletUpdateEditForm.merchantname.$error.required"><?php echo REQUIRED;?></span></span></label>
								 <input type='text' numbers-only name='minimumBalance' ng-disabled='isInputDisabled' required ng-model='minimumBalance' id='minimumBalance' class='form-control'/>	
							</div>
																		
							
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label>Advance Amount<span ng-show="WalletUpdateEditForm.advanceAmount.$touched ||WalletUpdateEditForm.advanceAmount.$dirty && WalletUpdateEditForm.advanceAmount.$invalid">
								<span class = 'err' ng-show="WalletUpdateEditForm.advanceAmount.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="advanceAmount" numbers-only type='text' ng-disabled='isInputDisabled' id='advanceAmount'  maxlength='11' name='advanceAmount' required class='form-control'/>
							</div>	
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
						<label><?php echo STATE_CREATE_ACTIVE; ?><span ng-show="WalletUpdateEditForm.active.$touched ||WalletUpdateEditForm.active.$dirty && WalletUpdateEditForm.active.$invalid">
								<span class = 'err' ng-show="WalletUpdateEditForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="Active" class='form-control' ng-disabled='isInputDisabled' name = 'active' id='Active' required >											
									<option value=''><?php echo STATE_CREATE_ACTIVE_SELECT; ?></option>
									<option value='Y'><?php echo STATE_CREATE_ACTIVE_YES; ?></option>
									<option value='N'><?php echo STATE_CREATE_ACTIVE_NO; ?></option>
								</select>
						</div>
							
										
						</div>
						</div>
						</form>	
						</div>
			<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-click='refresh()' ng-hide='isHideOk' ><?php echo BANK_ACCOUNT_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo BANK_ACCOUNT_CREATE_BUTTON_CANCEL; ?></a>
					<button type='button' class='btn btn-primary'  ng-hide='isHide'  ng-disabled = "WalletUpdateEditForm.$invalid"  ng-click="WalletUpdateEditForm.$invalid=true;walletupdate(partyCode)"  id="Update" >Update</button>
			</div>	
</div>					
	</div>
	</div>	
	 <?php }  ?>
	 <div id='infoViewDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo WALLET_VIEW_HEADING1; ?>  {{code}}  - {{outlet_name}}  <span ng-show='parenrtoutletname'>({{parenrtoutletname}})</span></h2>
					</div>
					<div  style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>				
					<div class='modal-body'>
					<form action="" method="POST" name='editINFOForm' id="EditINFOForm">
						<div id='AuthBody'>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
							<label> <?php echo WALLET_VIEW_CODE; ?>: <span style='color:blue'>{{code}}</span></label>	
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo WALLET_VIEW_NAME; ?> : <span style='color:blue'>{{name}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo WALLET_VIEW_LOGINNAME; ?> : <span style='color:blue'>{{lname}}</span></label>								
							</div>
							<div ng-show='sub_agent' class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo WALLET_VIEW_SUB_AGENT; ?> : <span style='color:blue'>{{sub_agent}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Category : <span style='color:blue'>{{atype}}</span></label>								
							</div>
							<div ng-show='sub_agent' class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo WALLET_VIEW_TYPE; ?> : <span style='color:blue'>{{ptype}}</span></label>								
							</div>
							<div  ng-show='parenrtoutletname' class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo WALLET_VIEW_LAST_PARENT_OUTLET_NAME; ?> : <span style='color:blue'>{{parenrtoutletname}}</span></label>								
							</div>
																					
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo WALLET_VIEW_ADVANCEAMOUNT; ?> : <span style='color:blue'>{{advance_amount}}</span></label>								
							</div>						
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo WALLET_VIEW_CREDIT_LIMIT; ?> : <span style='color:blue'>{{credit_limit}}</span></label>								
							</div>		
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo WALLET_VIEW_MIN_BALANCE; ?> : <span style='color:blue'>{{minimum_balance}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo WALLET_VIEW_CURRENT_BALANCE; ?> : <span style='color:red'>{{current_balance}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo WALLET_VIEW_AVAILABLE_BALANCE; ?> : <span style='color:blue'>{{available_balance}}</span></label>								
							</div>							
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo WALLET_VIEW_LAST_TX_NO; ?> : <span style='color:blue'>{{last_tx_no}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo WALLET_VIEW_LAST_TX_AMOUNT; ?> : <span style='color:blue'>{{last_tx_amount}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo WALLET_VIEW_LAST_TX_DATE; ?> : <span style='color:blue'>{{application_id}}</span></label>								
							</div>
								<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo WALLET_VIEW_BLOCK_STATUS; ?> : <span style='color:blue'>{{block_status}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo WALLET_VIEW_ACTIVE; ?> : <span style='color:blue'>{{active}}</span></label>								
							</div>
							
							
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
	//////TestTable1();
	//TestTable2();
	//TestTable3();
}
$(document).ready(function() {
  LoadDataTablesScripts(AllTables);
 // WinMove();
	$("#infoViewDialogue, #infoEditDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	$("#Refresh").click(function() {
		window.location.reload();
	});
	$("#EditINFODialogue, #AddINFODialogue").on("keypress",".sc", function (event) {
		    var regex = new RegExp("^[a-zA-Z0-9 \b:/.~!@#$*_-]+$");
 		    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
 		    if (!regex.test(key)) {
 		       event.preventDefault();
 		       return false;
 		    }
 		});
		 $("#selUser").select2();

  // Read selected option
  $('#but_read').click(function(){
    var username = $('#selUser option:selected').text();
    var userid = $('#selUser').val();

    $('#result').html("id : " + userid + ", name : " + username);

  });
});
</script>