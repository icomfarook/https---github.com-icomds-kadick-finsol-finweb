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

<style>
#selUser  .select2-selection .select2-selection--single{
	height:30px !important;
}
</style>
<div ng-controller='evdstatreportCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!rptsta"><?php echo STATISTICAL_REPORT_MAIN_HEADING1; ?></a></li>
			<li><a href="#!rptsta">EVD Statistical Report</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>EVD Statistical Report</span>
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
				<form name='stateReportForm'  action='evdstatreportexcel.php' method='POST' novalidate>	
					<div class='row appcont'>
						<div class='row appcont' ng-init="creteria='BT'" style="margin-left:120px;">
						<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12'>
								<select ng-hide='hide=true' ng-model="country" ng-init="country='<?php echo ADMIN_COUNTRY_ID; ?>';countrychange(this.country)"   class='form-control' name = 'country' id='country' required>											
									<option ng-repeat="country in countrys" value="{{country.id}}">{{country.description}}</option>
								</select>
								<label>State</label>
								<select  ng-model="state" style="margin-top: 2px;" ng-change='statechange(this.state)' ng-init="state='ALL'" class='form-control' name = 'state' id='state' required>											
									<option value='ALL'>ALL</option>
									<option ng-repeat="state in states" value="{{state.id}}">{{state.name}}</option>
								</select>
								
							</div>
							<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12'>
								<label>Operator &nbsp;&nbsp;<input name='typeDetail' type='checkbox' value='Y' ng-model='orderdetail'/></label>
								<select ng-init = 'type="ALL"' ng-disabled="isOrderTypeDi" ng-model='type' class='form-control' name='type' required>
									<option value='ALL'><?php echo STATISTICAL_REPORT_MAIN_ALL; ?></option>
									<option ng-repeat="operator in operators" value="{{operator.operator_id}}">{{operator.operator_description}}</option>
								</select>
							</div>	
							<?php if($profileId == 1 || $profileId == 10 || $profileId == 24 || $profileId == 22 || $profileId == 20 || $profileId == 23 || $profileId == 26) { ?>
							<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo EVD_FINANCIAL_REPORT_MAIN_AGENT_NAME; ?>&nbsp;&nbsp;<input type='checkbox' name='agentdetail' value='Y' ng-model='agentdetail' /></label>
								<select ng-init='agentName = "ALL"' id='selUser'  ng-disabled="isAgentDi" ng-model='agentName' class='form-control' name='agentName' required>
									<option value='ALL'><?php echo STATISTICAL_REPORT_MAIN_AGENT_ALL; ?></option>
									<option ng-if="state=='ALL'" ng-repeat="agent in agents" value="{{agent.agent_code}}">{{agent.agent_code}} - {{agent.agent_name}}</option>
									<option ng-if="state!='ALL'" ng-repeat="agent in agents" value="{{agent.code}}">{{agent.name}}</option>
								</select>
							</div>	
							<?php }  if($profileId == 51 ) { ?>
								<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12' ng-init="creteria='A'">
									<label><input type='radio' value='A' name='creteria' ng-model='creteria'/>&nbsp;<?php echo EVD_FINANCIAL_REPORT_MAIN_AGENT_NAME; ?>&nbsp;&nbsp;<input ng-click="impor()" type='checkbox' name='agentdetail' value='Y' ng-model='agentdetail'/></label>
									<input ng-init= "agentName=<?php echo "'".$partyCode."'"; ?>"  readonly='true' ng-disabled="agtAgentName" id='agentName'   ng-model='agentName' class='form-control' name='agentName' required />
								</div>	
								 
							 <div class='col-lg-2 col-xs-12 col-sm-12 col-md-12'>
								<label><input type='radio' value='S' name='creteria' ng-model='creteria'/>&nbsp;Sub Agent&nbsp;&nbsp;<input ng-click="impor()" type='checkbox' value='Y' ng-model='subagentdetail'/></label>
								<select ng-init='subAgentName = "ALL"' ng-disabled="creteria==='A'" ng-model='subAgentName' class='form-control' name='subAgentName' required>
									<option value='ALL'><?php echo STATISTICAL_REPORT_MAIN_AGENT_ALL; ?></option>
									<option ng-repeat="agent in subagents"  value="{{agent.code}}">{{agent.code}} - {{agent.name}}</option>
								</select>
							</div>
							<?php }  if($profileId == 52 ) { ?>
								<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12' ng-init="creteria='A'">
									<label><input type='radio' value='A' name='creteria' ng-model='creteria'/>&nbsp;<?php echo EVD_FINANCIAL_REPORT_MAIN_AGENT_NAME; ?>&nbsp;<input ng-click="impor()" type='checkbox' name='agentdetail' value='Y' ng-model='agentdetail'/></label>
									<input ng-init= "agentName=<?php echo "'".$partyCode. "-".$party_name.  "'" ?>"  readonly='true' ng-disabled="agtAgentName" id='agentName'   ng-model='agentName' class='form-control' name='agentName' required />
								</div>	
							<?php }  if($profileId == 50 ) { ?>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo EVD_FINANCIAL_REPORT_MAIN_AGENT; ?>&nbsp;<input type='checkbox' value='Y' ng-model='agentdetail'/></label>
								<select ng-init='agentName = "ALL"' ng-disabled="isAgentDi" ng-model='agentName' class='form-control' name='agentName' required>
									<option value='ALL'><?php echo STATISTICAL_REPORT_MAIN_AGENT_ALL; ?></option>
									<option ng-repeat="agent in agents" value="{{agent.code}}">{{agent.name}}</option>
								</select>
							</div>	
							<?php } ?>					
												
							 <div class='col-lg-2 col-xs-12 col-sm-12 col-md-12'>
						    	<label><?php echo STATISTICAL_REPORT_MAIN_START_DATE; ?></label>
								<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' style="cursor: auto;width: 150px;" id='startDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12'>							
								<label><?php echo STATISTICAL_REPORT_MAIN_END_DATE; ?></label>
								<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
								<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)' style="cursor: auto;width: 150px;"  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
							</div>
						</div>	
						<div class='row appcont'>
							<div style='text-align:center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled="isQueryDi"  ng-click='query()' ng-hide='isHide'  id="Query"><?php echo STATISTICAL_REPORT_MAIN_QUERY_BUTTON; ?></button>
								<button type="button" ng-click='reset()'  class="btn btn-primary"   id="Reset"><?php echo STATISTICAL_REPORT_MAIN_RESET_BUTTON; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo STATISTICAL_REPORT_MAIN_REFRESH_BUTTON; ?></button>
									<button type="button" class="btn btn-primary" ng-disabled = 'infoViewForm.$invalid' ng-click='print()' ng-hide='isHide'  id="Query">Print</button>
								<button type="submit" class="btn btn-primary"   id="excel"ng-hide='isHideexcel;'>Excel</button>
							</div>
						</div>
					<div class='row appcont'>
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo STATISTICAL_REPORT_MAIN_TABLE_DATE; ?></th>
									<th ng-show="td">Operator</th>
									<th ng-show="ad"><?php echo STATISTICAL_REPORT_MAIN_TABLE_AGENT; ?></th>
									<th>State</th>
									<th><?php echo STATISTICAL_REPORT_MAIN_TABLE_COUNT; ?></th>
								
									
								</tr>
							</thead>
							<tbody>
								 <tr ng-show='tablerow' ng-repeat="x in res">
								
									<td>{{ x.date }}</td>
									<td ng-show="x.td">{{ x.operator}}</td>
									<td  ng-show="x.ad">{{ x.agent }}</td>	
									<td>{{ x.state }}</td>
									<td>{{ x.count }}</td>
								</tr>
								<tr ng-show="res.length==0">
									<td style='text-align:center' colspan='9' >
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
		LoadDataTablesScripts(AllTables);
		
	});
	$("#Refresh").click(function() {
		window.location.reload();
	});	
		 /* window.alert = function() {}; alert = function() {}; */
		  $("#selUser").select2();

  // Read selected option
  $('#but_read').click(function(){
    var username = $('#selUser option:selected').text();
    var userid = $('#selUser').val();

    $('#result').html("id : " + userid + ", name : " + username);

  });
});
</script>