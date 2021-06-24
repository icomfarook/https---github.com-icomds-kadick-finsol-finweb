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
.form_col12_element {
	margin-top:1%;
}
#selUser  .select2-selection .select2-selection--single{
	height:30px !important;
	
}
</style>
<div ng-controller='WallHistoryCtrl' data-ng-init="fn_load(<?php echo "'".$partyType."',"."'".$partyCode."'" ?>)">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!walhis"><?php echo JOUNRAL_ENTRY_HEADING1; ?></a></li>
			<li><a href="#!walhis">Wallet History</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Wallet History</span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding" >	
			<div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>
				<form name='infoViewForm' action='wallhistexcel.php' method='POST'>	
					<div class='row appcont'>						
						 <?php if($profileId == 50) { ?>
							<div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>
									<label><?php echo JOUNRAL_ENTRY_CHAMPION; ?><span class='spanre'>*</span>
									<span ng-show="infoViewForm.partyCode.$dirty && infoViewForm.partyCode.$invalid">
									<span class = 'err' ng-show="infoViewForm.partyCode.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input  readonly = 'true' ng-disabled="creteria==='TP'" [(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
								</div>
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									<label><input value='TP' ng-init='topartyCode = "ALL"' type='radio' name='creteria' ng-model='creteria' /></label>
									<label><?php echo JOUNRAL_ENTRY_AGENT; ?><span class='spanre'>*</span>
									<span ng-show="infoViewForm.topartyCode.$dirty && infoViewForm.topartyCode.$invalid">
									<span class = 'err' ng-show="infoViewForm.topartyCode.$error.required"><?php echo REQUIRED;?></span></span></label>
									<select  ng-model='topartyCode' ng-disabled="creteria==='SP'" class='form-control' name='topartyCode' required >
										<option value='ALL'>-- ALL  --</option>
										<option ng-repeat="info in infos" value="{{info.code}}">{{info.name}}</option>
									</select>										
								</div>
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
						    	<label><?php echo JOUNRAL_ENTRY_START_DATE; ?></label>
								<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' id='startDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>							
								<label><?php echo JOUNRAL_ENTRY_END_DATE; ?></label>
								<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
								<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
							</div>
							 <div class='clearfix'></div><br />
								 <div style = 'text-align:Center'  class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
									<button type="button" class="btn btn-primary"  ng-click='query()' ng-hide='isHide'  id="Query"><?php echo JOUNRAL_ENTRY_QUERY_BUTTON; ?></button>
									<button type="button" class="btn btn-primary"   id="Refresh"><?php echo JOUNRAL_ENTRY_REFRESH_BUTTON; ?></button>
								</div>
							</div>	
								 <?php }  if($profileId == 51) {?>
									 <div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
										 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
											<label><input   value='SP' name='creteria' ng-model='creteria' /></label>
											<label><?php echo JOURNAL_ENTRY_AGENT ; ?><span class='spanre'>*</span>
											<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
											<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
											<input  readonly = 'true'[(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
										</div>
										<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
											<label><input value='TP' type='radio' name='creteria' ng-model='creteria' /></label>
											<label><?php echo JOUNRAL_ENTRY_SUB_AGENT; ?>	</label>
											<select  ng-model='topartyCode' ng-disabled="creteria==='SP'" class='form-control' name='topartyCode' required >
												<option value=''><?php echo JOUNRAL_ENTRY_SELECT_SUB_AGENT; ?></option>
												<option ng-repeat="info in infos" value="{{info.code}}">{{info.name}}</option>
											</select>										
										</div>
										<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
											<label><?php echo JOUNRAL_ENTRY_START_DATE; ?></label>
											<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' id='startDate' name='startDate' required class='form-control'/>
										</div>
										<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>							
											<label><?php echo JOUNRAL_ENTRY_END_DATE; ?></label>
											<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
											<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
										</div>
										
										 
									</div>	
									<div class='clearfix'></div>
									
									<div  style = 'text-align:Center' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
											<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='query()' ng-hide='isHide'  id="Query"><?php echo JOUNRAL_ENTRY_QUERY_BUTTON; ?></button>
											<button type="button" class="btn btn-primary"   id="Refresh"><?php echo JOUNRAL_ENTRY_REFRESH_BUTTON; ?></button>
										</div>
										
								  <?php }  if($profileId == 52) { ?>
										<div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
										 <div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
											<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>
											<label><?php echo JOUNRAL_ENTRY_AGENT ; ?><span class='spanre'>*</span>
											<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
											<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
											<input  readonly = 'true'[(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
										</div>
										<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
											<label><?php echo JOUNRAL_ENTRY_START_DATE; ?></label>
											<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' id='startDate' name='startDate' required class='form-control'/>
										</div>
										<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>							
											<label><?php echo JOUNRAL_ENTRY_END_DATE; ?></label>
											<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
											<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
										</div>
									
									</div>	
									
										 <div class='clearfix'></div>
								 <div style = 'text-align:Center'  class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
									<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='query()' ng-hide='isHide'  id="Query"><?php echo JOUNRAL_ENTRY_QUERY_BUTTON; ?></button>
									<button type="button" class="btn btn-primary"   id="Refresh"><?php echo JOUNRAL_ENTRY_REFRESH_BUTTON; ?></button>
								</div>
									
								  <?php }  if($profileId == 1 || $profileId == 10 || ($profileId >= 20 && $profileId <= 30)) {?>
									 <div class='row appcont'>
									<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12'>
									<label>Wallet Type<span ng-show="editstateForm.active.$touched ||editstateForm.active.$Walltype && editstateForm.active.$invalid">
										<span class = 'err' ng-show="editstateForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<select ng-model='Walltype' ng-init="Walltype='ALL'" required class='form-control' name = 'Walltype' id='Walltype'>		
											<option value="ALL">--ALL--</option>
											<option value='M'>M - Main</option>
											<option value='C'>C - Commission</option>
										</select>
										</div>
										 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
											
											<label><?php echo JOUNRAL_ENTRY_MAIN_PARTY_TYPE ; ?><span class='spanre'>*</span>
											<span ng-show="infoViewForm.partyType.$dirty && infoViewForm.partyType.$invalid">
											<span class = 'err' ng-show="infoViewForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></label>
											<select ng-init="partyType='ALL'" ng-change='partyload(this.partyType)' ng-model="partyType"  class='form-control' name = 'partyType' id='partytype' required>											
												<option value="ALL">--ALL--</option>
												<option value='MA'><?php echo JOUNRAL_ENTRY_MAIN_AGENT; ?></option>
												<option value='C'><?php echo JOUNRAL_ENTRY_MAIN_CHAMPION; ?></option>
												<option value='SA'><?php echo JOUNRAL_ENTRY_MAIN_SUB_AGENT; ?></option>
												<option value='P'><?php echo JOUNRAL_ENTRY_MAIN_PERSONAL; ?></option>
											</select>
											
										</div>
										<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>										
											<label><?php echo JOUNRAL_ENTRY_MAIN_PARTY_CODE; ?><span class='spanre'>*</span>
											<span ng-show="infoViewForm.partyCode.$dirty && infoViewForm.partyCode.$invalid">
											<span class = 'err' ng-show="infoViewForm.partyCode.$error.required"><?php echo REQUIRED;?></span></span></label>	
											<select  ng-model='partyCode' ng-init="partyCode='ALL'"  id='selUser' class='form-control' name='partyCode' required >
											<option value="ALL">--ALL--</option>												
											<option ng-repeat="info in infos" value="{{info.code}}">{{info.code}} - {{info.name}}</option>
											</select>										
										</div>
										<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12'>
											<label><?php echo JOUNRAL_ENTRY_MAIN_START_DATE; ?></label>
											<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' id='startDate' name='startDate' required class='form-control'/>
										</div>
										<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12'>							
											<label><?php echo JOUNRAL_ENTRY_MAIN_END_DATE; ?></label>
											<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
											<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
										</div>
										 
										 <div class='clearfix'></div>
									</div>	
											 <div  style = 'text-align:Center' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
											<button type="button" class="btn btn-primary"  ng-click='infoViewForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo JOUNRAL_ENTRY_MAIN_BUTTON_QUERY; ?></button>
											<button type="button" class="btn btn-primary"   id="Refresh"><?php echo JOUNRAL_ENTRY_MAIN_BUTTON_REFRESH; ?></button>
											<button type="submit" class="btn btn-primary"   id="excel"ng-hide='isHideexcel;'> Excel</button>
										</div>
								  <?php } ?>
								 
							</div>		
																	
					<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th>Wallet Type</th>
									<th>Party Type</th>
									<th>Party Code</th>
									<th>Available Balance</th>
									<th>Date Time</th>
									<th>Details</th>
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in jentrys">
									<td>{{ x.wallet_type }}</td>
									<td>{{ x.party_type }}</td>
									<td>{{ x.party_code }}</td>
									<td>{{ x.available_balance }}</td>
									<td>{{ x.date_time }}</td>
									<td>
										<a id={{x.id}} class='infoViewDialogue' ng-click='view($index,x.id)' data-toggle='modal' data-target='#infoViewDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
									</td>
								</tr>
								<tr ng-show="jentrys.length==0">
									<td style='text-align:center' colspan='8' >
										<?php echo NO_DATA_FOUND; ?>             
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
			</div>
		</div>
	</div>
		<div id='infoViewDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Wallet Balance History Details [{{party_type}}]</h2>
		
				</div>				
					<div  style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
						<form action="" method="POST" name='editINFOForm' id="EditINFOForm">
						<div id='AuthBody'>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
							<label> Id: <span style='color:blue'> {{id}}</span></label>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Party Type : <span style='color:blue'>{{party_type}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Party Code : <span style='color:blue'>{{party_code}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Wallet Type : <span style='color:blue'>{{wallet_type}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Credit Limit : <span style='color:red'>{{credit_limit}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Date Time : <span style='color:blue'>{{date_time}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Daily Limit : <span style='color:red'>{{daily_limit}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Advance Amount : <span style='color:red'>{{advance_amount}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Available Balance: <span style='color:red'>{{available_balance}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Current Balance: <span style='color:red'>{{current_balance}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label>Previous Current Balance: <span style='color:red'>{{previous_current_balance}}</span></label>								
							</div>
							<div  ng-show='ptype' class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Minimum Balance : <span style='color:red'>{{minimum_balance}}</span></label>								
							</div>
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Uncleared Balance : <span style='color:red'>{{uncleared_balance}}</span></label>								
							</div>	
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Last-Tx-Number : <span style='color:blue'>{{last_tx_no}}</span></label>								
							</div>
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Last-Tx-Amount : <span style='color:red'>{{last_tx_amount}}</span></label>								
							</div>								
												
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Last-Tx-Date : <span style='color:blue'>{{last_tx_date}}</span></label>								
							</div>							
							<div ng-show='sub_agent' class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Active : <span style='color:blue'>{{active}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Block Status : <span style='color:blue'>{{block_status}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Block Date : <span style='color:blue'>{{block_date}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Block Reason Id : <span style='color:blue'>{{block_reason_id}}</span></label>								
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
	//TestTable1();
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