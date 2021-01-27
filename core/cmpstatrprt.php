<?php 	
	include('../common/sessioncheck.php');
	error_reporting(0);
	$lang = $_SESSION['language_id'];
	$partyCode = $_SESSION['party_code'];
	$profileId = $_SESSION['profile_id'];
	$party_name	=   $_SESSION['party_name'];
		$partyType = $_SESSION['party_type'];
	//error_log($partyCode);
	if($lang == "1" || $lang=="") {
		include('../common/admin/finsol_label_ini.php');
	}
	if($lang == "2"){
		include('../common/admin/haus_finsol_label_ini.php');
	}
?>

<div ng-controller='cmpStatReportCtrl' data-ng-init="fn_load(<?php echo "'".$partyType."',"."'".$partyCode."'" ?>)">
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
				<form name='stateReportForm' action='chamstatrprtexcel.php'  method='POST' novalidate >	
						<div class='row appcont' style='margin-bottom:0%' >
						<div  class='col-lg-6 col-xs-12 col-sm-12 col-md-12' ng-init="reportFor='ALL'">
							<input value='ALL'  type='radio' name='creteria' ng-model='creteria' />&nbsp;<label>ALL</label>&nbsp;&nbsp;&nbsp;
							<input value='A'  type='radio' name='creteria' ng-model='creteria' />&nbsp;<label>Champion</label>&nbsp;&nbsp;&nbsp;
							<input value='S'  type='radio' name='creteria' ng-model='creteria' />&nbsp;<label>Agent</label>
						</div>
					</div>
						<div class='row appcont' ng-init="creteria='ALL'">
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo STATISTICAL_REPORT_MAIN_ORDER_TYPE; ?>&nbsp;<input name ='typeDetail' ng-click="impor()" type='checkbox' value='Y' ng-model='orderdetail'/></label>
								<select ng-init = 'type="ALL"' ng-disabled="isOrderTypeDi" ng-model='type' class='form-control' name='type' required>
									<option value='ALL'><?php echo STATISTICAL_REPORT_MAIN_ALL; ?></option>
									<option ng-repeat="type in types" value="{{type.id}}">{{type.name}}</option>
								</select>
							</div>	
							
							<div  ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
							<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12' ng-init="creteria='ALL'">
							<label>
									<?php echo JOUNRAL_ENTRY_CHAMPION; ?>&nbsp;&nbsp;<label><input ng-disabled="creteria =='ALL'"  ng-click="impor()"  value='SP' type='checkbox' name='agentDetail' ng-model='agentDetail' /></label>
									<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
									<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input  readonly = 'true' ng-disabled="creteria==='TP'" [(ngModel)] ="agentName" value = <?php echo "'".$partyCode. "-".$party_name.  "'" ?> type='text' id='agentName' name='agentName' autofocus='true' required class='form-control'/>
								</div>
						<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label> Agent &nbsp;<input type='checkbox' ng-disabled="creteria =='A' || creteria =='ALL'"  name='subAgentDetail' ng-click="impor()" value='Y' ng-model='subAgentDetail'/></label>
								<select ng-init='subAgentName = "ALL"' ng-disabled="creteria==='A' || creteria =='ALL'" ng-model='subAgentName' class='form-control' name='subAgentName' required>
									<option value='ALL'><?php echo STATISTICAL_REPORT_MAIN_AGENT_ALL; ?></option>
								<option ng-repeat="info in infos" value="{{info.code}}">{{info.name}}</option>
								</select>
							</div>	
													
							 <div class='col-lg-2 col-xs-12 col-sm-12 col-md-12'>
						    	<label><?php echo STATISTICAL_REPORT_MAIN_START_DATE; ?></label>
								<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' id='startDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12'>							
								<label><?php echo STATISTICAL_REPORT_MAIN_END_DATE; ?></label>
								<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
								<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
							</div>
						</div>	
						<div class='clearfix'></div> <br />
						<div class='row appcont' style='margin: 0%;'>
							<div style='text-align:center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled="isQueryDi"  ng-click='query()' ng-hide='isHide'  id="Query"><?php echo STATISTICAL_REPORT_MAIN_QUERY_BUTTON; ?></button>
								<button type="button" ng-click='reset()'  class="btn btn-primary"   id="Reset"><?php echo STATISTICAL_REPORT_MAIN_RESET_BUTTON; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo STATISTICAL_REPORT_MAIN_REFRESH_BUTTON; ?></button>
							<button type="button" class="btn btn-primary" ng-disabled = 'infoViewForm.$invalid' ng-click='print(creteria)' ng-hide='isHide'  id="Query">Print</button>
								<button type="submit" class="btn btn-primary"   id="excel"ng-hide='isHideexcel;'>Excel</button>
							</div>
						</div>
						<p style='color:brown'>{{mssage}}</p>
					<div class='row appcont'>
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo STATISTICAL_REPORT_MAIN_TABLE_DATE; ?></th>
									<th ng-show="td"><?php echo STATISTICAL_REPORT_MAIN_TABLE_ORDER_TYPE; ?></th>
									<th ng-show="creteria == 'ALL'">Champion / Agent </th>
									<th ng-show=" (creteria == 'A' || creteria == 'S') && agentDetail">Champion</th>
									<!--  <th  ng-show="creteria == 'ALL'">Party Names</th> -->
									<th ng-show="creteria == 'ALL'">Party Code</th>
									<th ng-show="creteria == 'S'  && subAgentDetail ">Agent</th>
									<th><?php echo STATISTICAL_REPORT_MAIN_TABLE_COUNT; ?></th>
								
									
								</tr>
							</thead>
							<tbody>
								 <tr ng-show='tablerow' ng-repeat="x in res">
								
									<td>{{ x.date}}</td>
									<td ng-show="x.td">{{ x.otype}}</td>
									<td   ng-show="x.ad || creteria == 'ALL'">{{ x.parent }}</td>							
									<td   ng-show="x.champ ">{{ x.champ }}</td>	
									<td   ng-show="x.agnt ">{{ x.agnt }}</td>	
									<td ng-show="x.subagent  && x.sd">{{ x.subagent }}</td>	
									<td>{{ x.count }}</td>
								</tr>
								<tr ng-show="res.length==0">
									<td style='text-align:center' colspan='6' >
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