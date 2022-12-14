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
<style>
#selUser  .select2-selection .select2-selection--single{
	height:30px !important;
}
</style>
<div ng-controller='statReportCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!rptsta"><?php echo STATISTICAL_REPORT_MAIN_HEADING1; ?></a></li>
			<li><a href="#!rptsta"><?php echo STATISTICAL_REPORT_MAIN_HEADING2; ?></a></li>
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
				<form name='stateReportForm'  action='statreportexcel.php' method='POST' novalidate>	
					<div class='row appcont'>
						<div class='row appcont' ng-init="creteria='BT'" >
						 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<select ng-hide='hide=true' ng-model="country" ng-init="country='<?php echo ADMIN_COUNTRY_ID; ?>';countrychange(this.country)"   class='form-control' name = 'country' id='country' required>											
									<option ng-repeat="country in countrys" value="{{country.id}}">{{country.description}}</option>
								</select>
								<label>State
								<span ng-show="infoViewForm.partyType.$dirty && infoViewForm.partyType.$invalid">
								<span class = 'err' ng-show="infoViewForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select  ng-model="state"  ng-change='statechange(this.state)' ng-init="state='ALL'" class='form-control' name = 'state' id='state' required>											
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
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo STATISTICAL_REPORT_MAIN_ORDER_TYPE; ?>&nbsp;&nbsp;<input name='typeDetail' type='checkbox' value='Y' ng-model='orderdetail'/></label>
								<select ng-init = 'type="ALL"' ng-disabled="isOrderTypeDi" ng-model='type' class='form-control' name='type' required>
									<option value='ALL'><?php echo STATISTICAL_REPORT_MAIN_ALL; ?></option>
									<option ng-repeat="type in types" value="{{type.id}}">{{type.name}}</option>
								</select>
							</div>	
							<?php  if($profileid == 51)  { ?>
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>		
								<label><?php echo STATISTICAL_REPORT_MAIN_AGENT_NAME; ?>&nbsp;&nbsp;<input  name='agentDetail' type='checkbox' value='Y' ng-model='agentDetail'/></label>
									<input  readonly = 'true'  [(ngModel)] ="partycode" value = <?php echo "'".$partycode. "-".$agent_name.  "'" ?> type='text' id='partycode' name='partycode' autofocus='true' required class='form-control'/>
								</div>
							<?php } else { ?>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo STATISTICAL_REPORT_MAIN_AGENT_NAME; ?>&nbsp;&nbsp;<input  name='agentDetail' type='checkbox' value='Y' ng-model='agentDetail'/></label>
								<select ng-init='agentName = "ALL"'  id='selUser' ng-disabled="isAgentDi" ng-model='agentName' class='form-control' name='agentName' required>
									<option value='ALL'><?php echo STATISTICAL_REPORT_MAIN_AGENT_ALL; ?></option>
									<option ng-if="state=='ALL'" ng-repeat="agent in agents" value="{{agent.agent_code}}">{{agent.agent_code}} - {{agent.agent_name}}</option>
									<option ng-if="state!='ALL'" ng-repeat="agent in agents" value="{{agent.code}}">{{agent.name}}</option>
								</select>
							</div>	
							<?php }  ?>	
						</div>								
								<div class='row appcont' style='margin-top:0%'>	
							 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
						    	<label><?php echo STATISTICAL_REPORT_MAIN_START_DATE; ?></label>
								<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' id='startDate'  name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>							
								<label><?php echo STATISTICAL_REPORT_MAIN_END_DATE; ?></label>
								<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
								<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'   ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
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
									<th ng-show="td"><?php echo STATISTICAL_REPORT_MAIN_TABLE_ORDER_TYPE; ?></th>
									<th ng-show="ad"><?php echo STATISTICAL_REPORT_MAIN_TABLE_AGENT; ?></th>
									<th >State</th>
									<th><?php echo STATISTICAL_REPORT_MAIN_TABLE_COUNT; ?></th>
								
									
								</tr>
							</thead>
							<tbody>
								 <tr ng-show="tablerow" ng-repeat="x in res">
								
									<td>{{ x.date }}</td>
									<td ng-show="x.td">{{ x.otype}}</td>
									<td  ng-show="x.ad">{{ x.agent }}</td>
									<td >{{x.state}}</td>
									<td>{{ x.count }}</td>
								</tr>
								<tr ng-show="res.length==0">
									<td style='text-align:center' colspan='4' >
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
		//LoadSelect2Script();
}

$(document).ready(function() {
	$("#Query").click(function() {				
	$('.dataTables_info').css("display","block"); 	
		$('#datatable-1_paginate').css("display","block");	
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
  $("#Reset").click(function() {
	   //alert();
		$('#selUser').select2('destroy');
		 $("#selUser").select2('val', val);
	    $('.dataTables_info').css("display","none"); // empty in case the columns change
		$('#datatable-1_paginate').css("display","none");
 
		});	
});
</script>