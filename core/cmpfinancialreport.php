<?php 	
	include('../common/sessioncheck.php');
	error_reporting(0);
	$lang = $_SESSION['language_id'];
	$partyCode = $_SESSION['party_code'];
	if($lang == "1" || $lang=="") {
		include('../common/admin/finsol_label_ini.php');
	}
	if($lang == "2"){
		include('../common/admin/haus_finsol_label_ini.php');
	}
?>

<div ng-controller='CmpFnReportCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#"><?php echo FINANCIAL_REPORT_MAIN_HEADING1; ?></a></li>
			<li><a href="#"><?php echo FINANCIAL_REPORT_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo FINANCIAL_REPORT_MAIN_HEADING3; ?></span>
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
					<div class='row appcont' ng-init="ba='ra'">
						<div class='row appcont col-lg-10 col-xs-12 col-sm-12 com-md-12' ng-init="creteria='BT'">
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo FINANCIAL_REPORT_MAIN_ORDER_TYPE; ?>&nbsp;<input type='checkbox' value='Y'  ng-model='orderdetail'/></label>
								<select ng-init = 'type="ALL"' ng-disabled="isOrderTypeDi" ng-model='type' class='form-control' name='type' required>
								<option value='ALL'><?php echo FINANCIAL_REPORT_MAIN_ORDER_TYPE_ALL; ?></option>
								<option ng-repeat="type in types" value="{{type.id}}">{{type.name}}</option>
								</select>
							</div>	
							
								 
							 <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo FINANCIAL_REPORT_MAIN_AGENT; ?>&nbsp;<input type='checkbox' value='Y' ng-model='agentdetail'/></label>
								<select ng-init='agentName = "ALL"' ng-disabled="isAgentDi" ng-model='agentName' class='form-control' name='agentName' required>
									<option value='ALL'><?php echo STATISTICAL_REPORT_MAIN_AGENT_ALL; ?></option>
									<option ng-repeat="agent in agents" value="{{agent.code}}">{{agent.name}}</option>
								</select>
							</div>	
						
							 <div class='col-lg-2 col-xs-12 col-sm-12 col-md-12'>
						    	<label><?php echo FINANCIAL_REPORT_MAIN_START_NAME; ?></label>
								<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' id='startDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12'>							
								<label><?php echo FINANCIAL_REPORT_MAIN_END_DATE; ?></label>
								<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
								<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
							</div>														
						</div>	
						<div class='row  col-lg-2 col-xs-12 col-sm-12 com-md-12' style='text-align:center' >
							<label><input type='radio' ng-click='radiochange()' name='ba' ng-model='ba' value='ra'><?php echo FINANCIAL_REPORT_MAIN_REQUEST_AMOUNT; ?></label><br />
							<label><input type='radio' ng-click='radiochange()' name='ba' ng-model='ba' value='ta'><?php echo FINANCIAL_REPORT_MAIN_TOTAL_AMOUNT; ?></label><br />
							<label><input type='radio' ng-click='radiochange()' name='ba' ng-model='ba' value='bo'><?php echo FINANCIAL_REPORT_MAIN_BOTH_AMOUNT; ?></label>
						</div>
						<div class='row appcont'>
							<div style='text-align: -webkit-center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled="isQueryDi"  ng-click='query()' ng-hide='isHide'  id="Query"><?php echo FINANCIAL_REPORT_MAIN_QUERY_BUTTON; ?></button>
									<button type="button" ng-click='reset()'  class="btn btn-primary"   id="Reset"><?php echo FINANCIAL_REPORT_MAIN_RESET_BUTTON; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo FINANCIAL_REPORT_MAIN_REFRESH_BUTTON; ?></button>
							</div>
						</div>
					<div class='row appcont'>
						<table show-filter="true" ng-table='' class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo FINANCIAL_REPORT_MAIN_TABLE_DATE; ?></th>
									<th ng-show="td"><?php echo FINANCIAL_REPORT_MAIN_TABLE_ORDER_TYPE; ?></th>
									<th ng-show="ad"><?php echo FINANCIAL_REPORT_MAIN_TABLE_AGENT; ?></th>
									<th ng-show='ba=="ra" || ba=="bo"'><?php echo FINANCIAL_REPORT_MAIN_TABLE_REQUEST_AMOUNT; ?></th>
									<th ng-show='ba=="ta"|| ba=="bo"'><?php echo FINANCIAL_REPORT_MAIN_TABLE_TOTAL_AMOUNT; ?></th>									
								</tr>
							</thead>
							<tbody id='tbody'>
								 <tr ng-show='tablerow' ng-repeat="x in res">
								
									<td>{{ x.date }}</td>
									<td ng-show="x.td">{{ x.otype}}</td>
									<td ng-show="x.ad">{{ x.agent }}</td>	
									<td ng-show='x.amtype=="ra" || x.amtype=="bo"'>{{ x.reamt }}</td>
									<td ng-show='x.amtype=="ta" || x.amtype=="bo"'>{{ x.toamt }}</td>
								</tr>
								<tr ng-show="res.length==0">
									<td colspan='6' >
										<?php echo FINANCIAL_REPORT_MAIN_NO_DATA_FOUND; ?>              
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

$(document).ready(function() {
	$("#Query").click(function() {				
		//LoadDataTablesScripts(AllTables);
		
	});
	$("#Refresh").click(function() {
		window.location.reload();
	});	
	
});
</script>