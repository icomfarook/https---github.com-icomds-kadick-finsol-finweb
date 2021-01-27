<?php 	
	include('../common/sessioncheck.php');
	error_reporting(0);
	$lang = $_SESSION['language_id'];
	$partyCode = $_SESSION['party_code'];
	//error_log($partyCode);
	if($lang == "1" || $lang=="") {
		include('../common/admin/finsol_label_ini.php');
	}
	if($lang == "2"){
		include('../common/admin/haus_finsol_label_ini.php');
	}
?>

<div ng-controller='CmpStatReportCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#"><?php echo STATISTICAL_REPORT_MAIN_HEADING1; ?></a></li>
			<li><a href="#"><?php echo STATISTICAL_REPORT_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo STATISTICAL_REPORT_MAIN_HEADING3; ?></span>
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
				<form name='stateReportForm'  method='POST' novalidate >	
					<div class='row appcont'>
						<div class='row appcont' ng-init="creteria='A'">
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo STATISTICAL_REPORT_MAIN_ORDER_TYPE; ?>&nbsp;<input  type='checkbox' value='Y' ng-model='orderdetail'/></label>
								<select ng-init = 'type="ALL"' ng-disabled="isOrderTypeDi" ng-model='type' class='form-control' name='type' required>
									<option value='ALL'><?php echo STATISTICAL_REPORT_MAIN_ALL; ?></option>
									<option ng-repeat="type in types" value="{{type.id}}">{{type.name}}</option>
								</select>
							</div>	
							 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo STATISTICAL_REPORT_MAIN_AGENT; ?>&nbsp;<input type='checkbox' value='Y' ng-model='agentDetail'/></label>
								<select ng-init='agentName = "ALL"' ng-disabled="isAgentDi" ng-model='agentName' class='form-control' name='agentName' required>
									<option value='ALL'><?php echo STATISTICAL_REPORT_MAIN_AGENT_ALL; ?></option>
									<option ng-repeat="agent in agents" value="{{agent.code}}">{{agent.name}}</option>
								</select>
							</div>	
												
							 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
						    	<label><?php echo STATISTICAL_REPORT_MAIN_START_DATE; ?></label>
								<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' id='startDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>							
								<label><?php echo STATISTICAL_REPORT_MAIN_END_DATE; ?></label>
								<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
								<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
							</div>
						</div>	
						<div class='row appcont' style='margin: 0%;'>
							<div style='text-align: -webkit-center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled="isQueryDi"  ng-click='query()' ng-hide='isHide'  id="Query"><?php echo STATISTICAL_REPORT_MAIN_QUERY_BUTTON; ?></button>
								<button type="button" ng-click='reset()'  class="btn btn-primary"   id="Reset"><?php echo STATISTICAL_REPORT_MAIN_RESET_BUTTON; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo STATISTICAL_REPORT_MAIN_REFRESH_BUTTON; ?></button>
							</div>
						</div>
						<p style='color:brown'>{{mssage}}</p>
					<div class='row appcont'>
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo STATISTICAL_REPORT_MAIN_TABLE_DATE; ?></th>
									<th ng-show="td"><?php echo STATISTICAL_REPORT_MAIN_TABLE_ORDER_TYPE; ?></th>
									<th ng-show="agentDetail && !subagentdetail"><?php echo STATISTICAL_REPORT_MAIN_TABLE_AGENT; ?></th>
									<th ng-show="subagentdetail && agentDetail"><?php echo STATISTICAL_REPORT_MAIN_TABLE_PARENT; ?>Parent</th>
									<th ng-show="creteria == 'S' && subagentdetail"><?php echo STATISTICAL_REPORT_MAIN_TABLE_SUB_AGENT; ?>Sub Agent</th>
									<th><?php echo STATISTICAL_REPORT_MAIN_TABLE_COUNT; ?></th>
								
									
								</tr>
							</thead>
							<tbody>
								 <tr ng-show='tablerow' ng-repeat="x in res">
								
									<td>{{ x.date }}</td>
									<td ng-show="x.td">{{ x.otype}}</td>
									<td  ng-show="x.ad && !x.sd">{{ x.agent }}</td>	
									<td  ng-show="x.parent">{{ x.parent }}</td>	
									<td  ng-show="x.subagent">{{ x.subagent }}</td>	
									<td>{{ x.count }}</td>
								</tr>
								<tr ng-show="res.length==0">
									<td style='text-align:left' colspan='9' >
										<?php echo STATISTICAL_REPORT_NO_DATA_FOUND; ?>              
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