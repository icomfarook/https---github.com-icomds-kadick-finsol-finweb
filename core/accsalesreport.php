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
	$profileid = $_SESSION['profile_id'];
	$partytype = $_SESSION['party_type'];	
	$partycode = $_SESSION['party_code'];
	$agent_name	=   $_SESSION['party_name'];
?>

<div ng-controller='AccsalesReportCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!rptaccsal"><?php echo TRANSACTION_REPORT_MAIN_HEADING1; ?></a></li>
			<li><a href="#!rptaccsal">Account Sales Report</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Account Sales Report</span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding" data-ng-init="fn_load(<?php echo "'".$partytype."',"."'".$partycode."'" ?>)">			
			  <div style='text-align:center' ng-hide='isMainLoader' class="loading-spiner-holder" data-loading ><div class="loading-spiner"><img src="../common/img/gif1.gif" /></div></div>			
				<form name='trReportForm' action='accsalesrprtexcel.php' method='POST' >	
				<?php if($profileid ==50){ ?>
					<div class='row appcont' style='margin-bottom:0%' >
						<div  class='col-lg-3 col-xs-12 col-sm-12 col-md-12' ng-init="reportFor='ALL'">
					
							<input value='ALL'  type='radio' name='reportFor' ng-model='reportFor'  />&nbsp;<label>ALL</label>&nbsp;&nbsp;&nbsp;
							<input value='C'  type='radio' name='reportFor' ng-model='reportFor' />&nbsp;<label>Champion</label>&nbsp;&nbsp;&nbsp;
							<input value='A'  type='radio' name='reportFor' ng-model='reportFor' />&nbsp;<label>Agent</label>
						</div>
						<div  class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>	
							<select  ng-model='partycode' ng-show="reportFor=='A'" ng-init="partycode='ALL'"  class='form-control' name='partycode' required >
								<option value="ALL">ALL</option>												
								<option ng-repeat="info in infos" value="{{info.code}}">{{info.code}} - {{info.name}}</option>
							</select>	
						</div>
					</div>
				<?php } ?>	
					<div class='row appcont' style='margin-top:0%'>				
						<div class='row appcont' ng-init="creteria='BT'">
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='BT' ng-click='clickra(creteria)' ng-checked='true' type='radio' name='creteria' ng-model='creteria' /></label>
								<label><?php echo TRANSACTION_REPORT_MAIN_ORDER_TYPE; ?></label>
								<select ng-init = 'type="ALL"' ng-disabled="isOrderTypeDi" ng-model='type' class='form-control' name='type' required>
									<option value='ALL'><?php echo TRANSACTION_REPORT_MAIN_ORDER_TYPE_ALL; ?></option>
									<option ng-repeat="type in types" value="{{type.id}}">{{type.name}}</option>
								</select>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='BO' ng-click='clickra(creteria)' type='radio' name='creteria' ng-model='creteria' /></label>
								<label><?php echo TRANSACTION_REPORT_MAIN_ORDER_NO; ?><span class='spanre'>*</span><span ng-show=" trReportForm.orderNo.$touched || trReportForm.orderNo.$dirty &&  trReportForm.orderNo.$invalid">
									</span>	<span class = 'err' ng-show=" creteria=='BO'&& trReportForm.orderNo.$invalid && trReportForm.orderNo.$error.required"><?php echo REQUIRED;?>.</span></label>
								<input ng-disabled="isOrderNoDi" ng-model="orderNo" numbers-only id='orderNo' maxlength='10'  name='orderNo' required class='form-control'/>
							</div>
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='S' ng-click='clickra(creteria)' type='radio' name='creteria' ng-model='creteria' /></label>
								<select ng-hide='hide=true' ng-model="country" ng-init="country='<?php echo ADMIN_COUNTRY_ID; ?>';countrychange(this.country)"   class='form-control' name = 'country' id='country' required>											
									<option ng-repeat="country in countrys" value="{{country.id}}">{{country.description}}</option>
								</select>
								<label>State
								<span ng-show="infoViewForm.partyType.$dirty && infoViewForm.partyType.$invalid">
								<span class = 'err' ng-show="infoViewForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select  ng-model="state" ng-change='statechange(this.state)' ng-init="state='ALL'" ng-disabled="isstate" class='form-control' name = 'state' id='state' required>											
									<option value='ALL'>ALL</option>
									<option ng-repeat="state in states" value="{{state.id}}">{{state.name}}</option>
								</select>
							</div>
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label>Local Goverment<span class='spanre'>*</span><span ng-show="editINFOForm.local_govt_id.$touched ||editINFOForm.local_govt_id.$dirty && editINFOForm.local_govt_id.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="editINFOForm.local_govt_id.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-disabled="isstate" ng-model="local_govt_id"   class='form-control' name = 'local_govt_id' id='LocalGoverment'>											
									<option value=''><?php echo APPLICATION_ENTRY_SELECT_LOCAL_GOVT; ?></option>
									<option ng-repeat="localgvt in localgvts" value="{{localgvt.id}}">{{localgvt.name}}</option>
								</select>
							</div>
							</div>
							<div class='row appcont' style='margin-top:0%'>
							<?php  if($profileid == 1 || $profileid == 10 || $profileid == 24 || $profileid == 22 || $profileid == 20 || $profileid == 23 || $profileid == 26) { ?>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='C' ng-click='clickra(creteria)'  type='radio' name='creteria' ng-model='creteria' /></label>
								<label>Champion</label>
								<select ng-init = 'championCode="ALL"'  ng-model='championCode' ng-disabled="ischampionCode" class='form-control' name='championCode' required>
									<option value='ALL'>--ALL--</option>
										<option ng-repeat="champion in champions"  value="{{champion.code}}">{{champion.code}} - {{champion.name}}</option>		
								</select>
							</div>
							<?php } if($profileid == 51)  { ?>
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>		
									<label>Agent<span class='spanre'>*</span>
									<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
									<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input  readonly = 'true'  [(ngModel)] ="partycode" value = <?php echo "'".$partycode. "-".$agent_name.  "'" ?> type='text' id='partycode' name='partycode' autofocus='true' required class='form-control'/>
								</div>
							<?php } ?>
							 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							
						    	<label><?php echo TRANSACTION_REPORT_MAIN_START_DATE; ?></label>
								
								<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' id='startDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>							
								<label><?php echo TRANSACTION_REPORT_MAIN_END_DATE; ?></label>
								<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
								<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
							</div>
						</div>	
						
						<div class='row appcont' style='text-align:center'>
							<div style='text-align: -webkit-center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled="isQueryDi"  ng-click='query()' ng-hide='isHide'  id="Query"><?php echo TRANSACTION_REPORT_MAIN_QUERY_BUTTON; ?></button>
								<button type="button" ng-click='reset()'  class="btn btn-primary"   id="Reset"><?php echo TRANSACTION_REPORT_MAIN_RESET_BUTTON; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo TRANSACTION_REPORT_MAIN_REFRESH_BUTTON; ?></button>
							<button type="button" class="btn btn-primary" ng-disabled = 'infoViewForm.$invalid' ng-click='printAll()' ng-hide='isHide'  id="Query">Print</button>
								<button type="submit" class="btn btn-primary"   id="excel" ng-hide='isHideexcel;'>Excel</button>
								<button type="submit" class="btn btn-primary"  onclick='this.form.action="accsalesrprtnewexcel.php";'  id="excel" ng-hide='isHideexcel;'>Detail Excel</button>
						</div>
						</div>
					<div class='row appcont'>
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo TRANSACTION_REPORT_MAIN_TABLE_ORDER_NO; ?></th>
									<th><?php echo TRANSACTION_REPORT_MAIN_TABLE_CODE; ?></th>
									<th>Bank</th>
									<th><?php echo TRANSACTION_REPORT_MAIN_TABLE_TOTAL_AMOUNT; ?></th>
									<th><?php echo TRANSACTION_REPORT_MAIN_TABLE_AGENT_NAME; ?></th>
									<th><?php echo TRANSACTION_REPORT_MAIN_TABLE_DATE_TIME; ?></th>
									<th><?php echo TRANSACTION_REPORT_MAIN_TABLE_DETAIL; ?></th>
									<th>Print</th>										
								</tr>
							</thead>
							<tbody>
								 <tr ng-show='tablerow' ng-repeat="x in res">
									<td>{{ x.no }}</td>
									<td>{{ x.code}}</td>
									<td>{{ x.bank }}</td>
									<td>{{ x.toamount }}</td>
									<td>{{ x.user }}</td>
									<td>{{ x.dtime }}</td>									
									<td><a class='trReportDialogue' ng-click='view(x.no,x.code)' data-toggle='modal' data-target='#trReportDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
										<a href="#">|&nbsp;</a>
										<a class='trReportDialogue' ng-click='viewcomm(x.no)' data-toggle='modal' data-target='#trReportCommDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
									</td>
									<td><a class='trReportDialogue' ng-click='print(x.no,x.code)' data-toggle='modal' >
										<button class='icoimg'><img  src='../common/images/print1.jpg' /></button></a>
									</td>
									
								</tr>
								<tr ng-show="res.length==0">
									<td colspan='8' >
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
	 <div id='trReportCommDialogue' class='modal' role='dialog'>
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
				<form action="" method="POST" name='trReportDialogueForm' id="trReportDialogueForm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Sales Report Commission: # {{no}}</h2>
					</div>					 
					<div class='modal-body'>
					<div class='row'>
						<div class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					</div>
						<div id='trReportViewBody'  ng-hide='isLoader'>
							<table class='table table-borderd'>
								<thead>
								 <tr >
									<th><?php echo TRANSACTION_REPORT_MAIN_TABLE_TYPE; ?></th>
									<th><?php echo TRANSACTION_REPORT_MAIN_TABLE_AMOUNT; ?></th>
									<th><?php echo TRANSACTION_REPORT_MAIN_TABLE_PARTY; ?></th>
									<th><?php echo TRANSACTION_REPORT_MAIN_TABLE_PARTY_TYPE; ?></th>
									
								</tr>
								</thead>
								<tbody>
									 <tr ng-repeat="x in rescomms">
										<td>{{ x.rate_factor }}</td>
										<td>{{ x.rate_value}}</td>
										<td>{{ x.service_charge_group_name }}</td>
										<td>{{ x.service_charge_party_name }}</td>
										
									</tr>
									<tr ng-show="rescomms.length==0">
										<td colspan='4' >
											<?php echo NO_DATA_FOUND; ?>           
										</td>
									</tr>
								</tbody>									
							</table>
						</div>
					</div>				
					<div class='modal-footer'>					
						
					</div>
				</form>	
			</div>
		</div>	
	</div>	
	 <div id='trReportDialogue' class='modal' id='myModal' role='dialog'>
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
				<form action="" method="POST" name='trReportDialogueFormdetails' id="trReportDialogueFormdetails" ng-modal='clearAll'>
					<div class="modal-header">
						<button type="button" class="close"   ng-click='clear()' data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Sales Report : # {{no}}</h2>
					</div>					 
					<div class='modal-body'>
					<div class='row'>
						<div class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					</div>
						<div id='trReportViewBody'  ng-hide='isLoader'>
							<table class='table table-borderd'>
<tr>
									<td><?php echo FIN_SERVI_TRANSACTION_REPORT_ORDER_NO; ?> {{no}}</td>
									<td><?php echo FIN_SERVI_TRANSACTION_REPORT_ORDER_TYPE; ?> {{code}}</td>
								</tr>
								<tr>									
									<td><?php echo FIN_SERVI_TRANSACTION_REPORT_TOTAL_AMOUNT; ?> {{toamount}}</td>
									<td>Ams Charge : {{ams_charge}}</td>
								</tr>
								<tr>									
									<td><?php echo FIN_SERVI_TRANSACTION_REPORT_PARTNER_CHARGE; ?> {{parcharge}}</td>
									<td>Other Charge : {{ocharge}}</td>
								</tr>
								<tr>									
									<td>Agent Charge {{agent_charge}}</td>
									<td >Stamp Charge {{stamp_charge}}</td>
									
								</tr>
								<tr>									
									<td>Country : {{country}}</td>
									<td>State : {{state}}</td>
								</tr>
								<tr>									
									<td>Local Government: {{local}}</td>
									<td><?php echo FIN_SERVI_TRANSACTION_REPORT_USER; ?> {{user}}</td>
								</tr>
								<tr>									
									<td>First Name: {{first_name}}</td>
									<td>Middle Name : {{middle_name}}</td>
								</tr>
								<tr>									
									<td>Last Name: {{last_name}}</td>
									<td>BVN : {{bvn}}</td>
								</tr>
								<tr>									
									<td>Gender: {{gender}}</td>
									<td>Dob : {{dob}}</td>
								</tr>
								<tr>									
									<td>House No: {{house_no}}</td>
									<td>Street Name : {{street_name}}</td>
								</tr>
								<tr>									
									<td>City : {{city}}</td>
									<td>Email : {{email}}</td>
								</tr>
								<tr>									
									<td>Mobile : {{mobile}}</td>
									<td>Request Id : {{request_id}}</td>
								</tr>
								<tr>									
									<td>Account Number : {{account_number}}</td>
									<td>Account Balance : {{account_balance}}</td>
								</tr>
								<tr>									
								   <td>Account Trans Log Id 1 : {{acc_trans_log_id}}</td>
									<td>Account Trans Log Id 2 : {{acc_trans_log_id2}}</td>
								</tr>
								<tr>
									<td >Status: <b>{{sts}}</b></td>
									<td >Comments : {{comments}}</td>
								</tr>
								<tr>
									<td><?php echo "Create Time : "; ?>  {{ctime}}</td>
									<td><?php echo "Update Time : "; ?>  {{utime}}</td>
								</tr>	
							</table>
						</div>
					</div>				
					<div class='modal-footer'>					
						
					</div>
				</form>	
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
	LoadSelect2Script();
}
$(document).ready(function() {
	$("#Query").click(function() {				
		LoadDataTablesScripts(AllTables);
		// $.fn.dataTableExt.sErrMode = 'throw' ;
	});
	  $('.modal-content').on('hidden', function() {
    clear()
  });
  $("#selUser").select2();

  // Read selected option
  $('#but_read').click(function(){
    var username = $('#selUser option:selected').text();
    var userid = $('#selUser').val();

    $('#result').html("id : " + userid + ", name : " + username);

  });
	$("#Refresh").click(function() {
		window.location.reload();
	});	
	$("#Reset").click(function() {
		$('#selUser').select2('destroy');
		$('#selUser').val('').select2();
	});	
});
</script>