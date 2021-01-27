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

<div ng-controller='fnReportCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!rptfin"><?php echo FINANCIAL_REPORT_MAIN_HEADING1; ?></a></li>
			<li><a href="#!rptfin"><?php echo FINANCIAL_REPORT_MAIN_HEADING2; ?></a></li>
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
				<form name='finReportForm' action='financereportexcel.php' method='POST' novalidate>	
					<div class='row appcont' ng-init="ba='ra'">
						<div class='row appcont col-lg-10 col-xs-12 col-sm-12 com-md-12' ng-init="creteria='BT'"  style="margin-left: 23px;">
						<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12'>
								<select ng-hide='hide=true' ng-model="country" ng-init="country='<?php echo ADMIN_COUNTRY_ID; ?>';countrychange(this.country)"   class='form-control' name = 'country' id='country' required>											
									<option ng-repeat="country in countrys" value="{{country.id}}">{{country.description}}</option>
								</select>
								<label>State</label>
								<select  ng-model="state"  style="margin-top: 2px;" ng-change='statechange(this.state)' ng-init="state='ALL'" class='form-control' name = 'state' id='state' required>											
									<option value='ALL'>ALL</option>
									<option ng-repeat="state in states" value="{{state.id}}">{{state.name}}</option>
								</select>
								
							</div>
							<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo FINANCIAL_REPORT_MAIN_ORDER_TYPE; ?>&nbsp;&nbsp;<input type='checkbox' value='Y' name='typeDetail' ng-model='orderdetail'/></label>
								<select ng-init = 'type="ALL"' ng-disabled="isOrderTypeDi" ng-model='type' class='form-control' name='type' required>
									<option value='ALL'><?php echo FINANCIAL_REPORT_MAIN_ORDER_TYPE_ALL; ?></option>
									<option ng-repeat="type in types" value="{{type.id}}">{{type.name}}</option>
								</select>
							</div>
							<?php if($profileId == 1 || $profileId == 10 || $profileId == 24 || $profileId == 22 || $profileId == 20 || $profileId == 23 || $profileId == 26) { ?>
							<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo FINANCIAL_REPORT_MAIN_AGENT_NAME; ?>&nbsp;&nbsp;<input type='checkbox' name='agentdetail' value='Y' ng-model='agentdetail'/></label>
								<select ng-init='agentName = "ALL"' id='selUser' ng-disabled="isAgentDi" ng-model='agentName' class='form-control' name='agentName' required>
										<option value='ALL'><?php echo STATISTICAL_REPORT_MAIN_AGENT_ALL; ?></option>
									<option ng-if="state=='ALL'" ng-repeat="agent in agents" value="{{agent.agent_code}}">{{agent.agent_code}} - {{agent.agent_name}}</option>
									<option ng-if="state!='ALL'" ng-repeat="agent in agents" value="{{agent.code}}">{{agent.name}}</option>
								</select>
							</div>	
							<?php }  if($profileId == 51 ) { ?>
								<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12' ng-init="creteria='A'">
									<label><input type='radio' name='creteria' value='A' ng-model='creteria'/>&nbsp;<?php echo FINANCIAL_REPORT_MAIN_AGENT_NAME; ?>&nbsp;<input ng-click="impor()" type='checkbox' name='agentdetail' value='Y' ng-model='agentdetail'/></label>
									<input ng-init= "agentName=<?php echo "'".$partyCode. "-".$party_name.  "'" ?>"  readonly='true' ng-disabled="agtAgentName" id='agentName'   ng-model='agentName' class='form-control' name='agentName' required />
								</div>	
								 
							 <div class='col-lg-2 col-xs-12 col-sm-12 col-md-12'>
								<label><input type='radio' name='creteria' value='S' ng-model='creteria'/>&nbsp;<?php echo FINANCIAL_REPORT_MAIN_AGENT_SUBAGENT; ?>&nbsp;<input ng-click="impor()" type='checkbox' name='agentdetail' value='Y' ng-model='subagentdetail'/></label>
								<select ng-init='subAgentName = "ALL"' ng-disabled="creteria==='A'" ng-model='subAgentName' class='form-control' name='subAgentName' required>
									<option value='ALL'><?php echo STATISTICAL_REPORT_MAIN_AGENT_ALL; ?></option>
									<option ng-repeat="agent in subagents"  value="{{agent.code}}">{{agent.code}} - {{agent.name}}</option>
								</select>
							</div>
							<?php }  if($profileId == 52 ) { ?>
								<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12' ng-init="creteria='A'">
									<label><input type='radio' value='A' ng-model='creteria'/>&nbsp;<?php echo FINANCIAL_REPORT_MAIN_AGENT_NAME; ?>&nbsp;<input ng-click="impor()" type='checkbox' value='Y' ng-model='agentdetail'/></label>
									<input ng-init= "agentName=<?php echo "'".$partyCode. "-".$party_name.  "'" ?>"  readonly='true' ng-disabled="agtAgentName" id='agentName'   ng-model='agentName' class='form-control' name='agentName' required />
								</div>	
							<?php }  if($profileId == 50 ) { ?>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo FINANCIAL_REPORT_MAIN_AGENT; ?>&nbsp;<input type='checkbox' value='Y' ng-model='agentdetail'/></label>
								<select ng-init='agentName = "ALL"' ng-disabled="isAgentDi" ng-model='agentName' class='form-control' name='agentName' required>
									<option value='ALL'><?php echo STATISTICAL_REPORT_MAIN_AGENT_ALL; ?></option>
									<option ng-repeat="agent in agents" value="{{agent.code}}">{{agent.name}}</option>
								</select>
							</div>	
							<?php } ?>
							<?php if($profileId == 1 || $profileId == 10 || $profileId == 24 || $profileId == 22 || $profileId == 20 || $profileId == 23 || $profileId == 26) { ?>
							 <div  class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
						    	<label><?php echo FINANCIAL_REPORT_MAIN_START_NAME; ?></label>
								<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' style='width: 156px;'  id='startDate' name='startDate' required class='form-control'/>
							</div>
							<div  class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>							
								<label><?php echo FINANCIAL_REPORT_MAIN_END_DATE; ?></label>
								<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
								<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)' style="width: 156px;cursor: auto;margin-left: -34px;"  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
							</div>										
									<?php }  if($profileId == 52 || $profileId == 51 || $profileId == 52) { ?>
									<div  class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
						    	<label><?php echo FINANCIAL_REPORT_MAIN_START_NAME; ?></label>
								<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' style='width: 156px;'  id='startDate' name='startDate' required class='form-control'/>
							</div>
							<div  class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>							
								<label><?php echo FINANCIAL_REPORT_MAIN_END_DATE; ?></label>
								<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
								<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)' style="width: 156px;cursor: auto;margin-left: -34px;"  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
							</div>	
								<?php } ?>							
						</div>	
						<div class='row  col-lg-2 col-xs-12 col-sm-12 com-md-12' style="margin-left: -29px;margin-top: 14px;"    >
							<label><input type='radio' ng-click='radiochange()' name='ba' ng-model='ba' value='ra'>&nbsp;<?php echo FINANCIAL_REPORT_MAIN_REQUEST_AMOUNT; ?></label><br />
							<label><input type='radio' ng-click='radiochange()' name='ba' ng-model='ba' value='ta'>&nbsp;<?php echo FINANCIAL_REPORT_MAIN_TOTAL_AMOUNT; ?></label><br />
							<label><input type='radio' ng-click='radiochange()' name='ba' ng-model='ba' value='bo'>&nbsp;<?php echo FINANCIAL_REPORT_MAIN_BOTH_AMOUNT; ?></label>
						</div>
						<div class='row appcont'>
							<div style='text-align:center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled="isQueryDi"  ng-click='query()' ng-hide='isHide'  id="Query"><?php echo FINANCIAL_REPORT_MAIN_QUERY_BUTTON; ?></button>
									<button type="button" ng-click='reset()'  class="btn btn-primary"   id="Reset"><?php echo FINANCIAL_REPORT_MAIN_RESET_BUTTON; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo FINANCIAL_REPORT_MAIN_REFRESH_BUTTON; ?></button>
								<button type="button" class="btn btn-primary" ng-disabled = 'infoViewForm.$invalid' ng-click='print()' ng-hide='isHide'  id="Query">Print</button>
								<button type="submit" class="btn btn-primary"   id="excel"ng-hide='isHideexcel;'>Excel</button>
							</div>
						</div>
					<div class='row appcont'>
						<table show-filter="true" ng-table='' class="finreport table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo FINANCIAL_REPORT_MAIN_TABLE_DATE; ?></th>
									<th ng-show="td"><?php echo FINANCIAL_REPORT_MAIN_TABLE_ORDER_TYPE; ?></th>
									<th ng-show="ad"><?php echo FINANCIAL_REPORT_MAIN_TABLE_AGENT; ?></th>
									<th >State</th>
									<th ng-show='ba=="ra" || ba=="bo"'><?php echo FINANCIAL_REPORT_MAIN_TABLE_REQUEST_AMOUNT; ?></th>
									<th ng-show='ba=="ta"|| ba=="bo"'><?php echo FINANCIAL_REPORT_MAIN_TABLE_TOTAL_AMOUNT; ?></th>									
								</tr>
							</thead>
							<tbody id='tbody'>
								 <tr ng-show='tablerow' ng-repeat="x in res">
								
									<td>{{ x.date }}</td>
									<td ng-show="x.td">{{ x.otype}}</td>
									<td ng-show="x.ad">{{ x.agent }}</td>	
									<td  >{{ x.state }}</td>	
									<td ng-show='x.amtype=="ra" || x.amtype=="bo"'>{{ x.reamt }}</td>
									<td ng-show='x.amtype=="ta" || x.amtype=="bo"'>{{ x.toamt }}</td>
								</tr>
								<tr ng-show="res.length==0">
									<td colspan='6'  style='text-align:center'>
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