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
	$partyCode = $_SESSION['party_code'];
	$party_name	=   $_SESSION['party_name'];
?>
<script src="https://rawgithub.com/eligrey/FileSaver.js/master/FileSaver.js" type="text/javascript"></script>
<div ng-controller='batchReportCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!rptfin">Batch Transaction Report</a></li>
			<li><a href="#!rptfin">Batch Transaction Report</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Batch Transaction Report</span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding">			
			  <div style='text-align:center' ng-hide='isMainLoader' class="loading-spiner-holder" data-loading ><div class="loading-spiner"><img src="../common/img/gif1.gif" /></div></div>			
				<form name='finReportForm'  method='POST' novalidate>	
					<div class='row appcont' ng-init="ba='S'">
						<div class='row appcont col-lg-12 col-xs-12 col-sm-12 com-md-12' ng-init="creteria='BT'">
							<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo FINANCIAL_REPORT_MAIN_ORDER_TYPE; ?>&nbsp;&nbsp;<input type='checkbox' value='Y'  ng-model='orderdetail'/></label>
								<select ng-init = 'type="ALL"' ng-disabled="isOrderTypeDi" ng-model='type' class='form-control' name='type' required>
									<option value='ALL'><?php echo FINANCIAL_REPORT_MAIN_ORDER_TYPE_ALL; ?></option>
									<option ng-repeat="type in types" value="{{type.sfid}}">{{type.name}}</option>
								</select>
							</div>
							<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo "Server"; ?>&nbsp;&nbsp;<input type='checkbox' value='Y' ng-model='serverdetail'/></label>
								<select ng-init='serverName = "ALL"' ng-disabled="isAgentDi" ng-model='serverName' class='form-control' name='serverName' required>
									<option value='ALL'>All</option>
									<option value='82'>Portal 164</option>
									<option value='83'>Portal 202</option>
								</select>
							</div>	
							
							 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
						    	<label><?php echo FINANCIAL_REPORT_MAIN_START_NAME; ?></label>
								<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' id='startDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>							
								<label><?php echo FINANCIAL_REPORT_MAIN_END_DATE; ?></label>
								<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
								<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
							</div>														
							
						<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12' >	
							<label><?php echo "Result Type"; ?></label><br />
							<label><input type='radio' ng-click='radiochange()' name='ba' ng-model='ba' value='S'  checked='true' >&nbsp;<?php echo "Success"; ?></label><br />
							<label><input type='radio' ng-click='radiochange()' name='ba' ng-model='ba' value='F'>&nbsp;<?php echo "Failure"; ?></label><br />
							<label><input type='radio' ng-click='radiochange()' name='ba' ng-model='ba' value='A'>&nbsp;<?php echo "All"; ?></label>
						</div>
						</div>
						<div class='row appcont'>
							<div style='text-align: -webkit-center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled="isQueryDi"  ng-click='query();' ng-hide='isHide'  id="Query"><?php echo FINANCIAL_REPORT_MAIN_QUERY_BUTTON; ?></button>
								<button type="button" ng-click='reset()'  class="btn btn-primary"   id="Reset"><?php echo FINANCIAL_REPORT_MAIN_RESET_BUTTON; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo FINANCIAL_REPORT_MAIN_REFRESH_BUTTON; ?></button>
								<span style="float:right"><button type="button" class="btn btn-primary"   id="print" ng-disabled="isQueryDi"  ng-click='print()' ng-hide='isHideprint;'>Print</button>&nbsp;
								<button type="button" class="btn btn-primary"   id="excel" ng-disabled="isQueryDi"  ng-click="exportData()" ng-hide='isHideexcel;'>Excel</button>
							</span></div>
						</div>
					<div class='row appcont' id="exportable">
						<table show-filter="true" ng-table='' class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th ng-show="td">Code</th>
									<th>Partner</th>
									<th ng-show="sd">Server</th>
									<th>Branch</th>
									<th>Transaction Id</th>
									<th>Operation Id</th>
									<th>Amount</th>
									<th>Status</th>
									<th>Sent Time</th>
									<th>Receive Time</th>
															 
								</tr>
							</thead>
							<tbody id='tbody'>
								 <tr ng-show='tablerow' ng-repeat="x in res">								
									<td ng-show="x.td">{{ x.code }}</td>
									<td>{{ x.partner_id }}</td>
									<td ng-show="x.sd">{{ x.server }}</td>
									<td>{{ x.branch_name }}</td>
									<td>{{ x.transaction_id }}</td>
									<td>{{ x.operation_id }}</td>
									<td>{{ x.amount }}</td>
									<td><b>{{ x.error_description }}</b></td>
									<td>{{ x.message_send_time }}</td>
									<td>{{ x.message_receive_time }}</td>									
								</tr>
								<tr ng-show="res.length==0">
									<td colspan='10' >
										<?php echo NO_DATA_FOUND; ?>              
									</td>
								</tr>
							</tbody>
						</table>
						</div>
					</div>
				</form>			
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
	$("#Query").click(function() {				
		//LoadDataTablesScripts(AllTables);
		
	});
	$("#Refresh").click(function() {
		window.location.reload();
	});	
	 /* window.alert = function() {}; alert = function() {}; */
	
});
</script>