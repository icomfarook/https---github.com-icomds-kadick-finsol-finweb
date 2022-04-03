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
.labspa{
	color:blue;
	padding-left:10px;
}
.labspafin{
	color:red;
	padding-left:10px;
}
.labspagreen{
	color:green;
	padding-left:10px;
}
#container{
	width:30%
	
}
#selUser  .select2-selection .select2-selection--single{
	height:30px !important;
}
</style>
<div ng-controller='AgentSummCtrl' >
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!agntsum"><?php echo JOUNRAL_ENTRY_COMMI_HEADING1; ?></a></li>
			<li><a href="#!agntsum">Agent Rank - Summary</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Agent Rank - Summary</span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			<div >
			<div class="box-content no-padding">	
			<div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>
				<form name='AgentSumForm'  action ='agntsumexcel.php' method='POST'>	
					<div class='row appcont' >									
					 	<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' style="width:28%;margin-left:35%">					<label style="text-align:center;margin-left: 30%;" ><?php echo STATISTICAL_REPORT_MAIN_AGENT_NAME; ?></label>
								<select  ng-init='agentCode = "ALL"' id='selUser'  ng-model='agentCode' class='form-control' name='agentCode' required>
									<option value='ALL'>--ALL--</option>
									<option ng-repeat="agent in agents" value="{{agent.agent_code}}">{{agent.agent_code}} - {{agent.agent_name}}</option>
									<option  ng-repeat="agent in agents" value="{{agent.code}}">{{agent.name}}</option>
								</select>
									
								</div>
							
						<div class='clearfix'></div>
							<div  style = 'text-align:Center;margin-top: inherit;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary enableOnInput"  ng-disabled = 'AgentSumForm.$invalid'  ng-click='AgentSumForm.$invalid=true;query()' disabled='disabled' ng-hide='isHide'  id="Query"><?php echo JOUNRAL_ENTRY_COMMI_MAIN_BUTTON_QUERY; ?></button>
								<button type="button" class="btn btn-primary"   ng-click='reset()' id="Refresh">Reset</button>
								<button type="submit" class="btn btn-primary"   id="excel"  ng-hide='isHideexcel;'>Excel</button>
							</div>
						</div></div></div>
																	
					<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th>Agent Code</th>
									<th>Assigned Category</th>
									<th>Last Month Achived Category</th>
									<th>Current Month Target</th>
									<th>Run Month</th>
									<th>Details</th>
								</tr>
							</thead>
							<tbody>
								 <tr ng-show='tablerow' ng-repeat="x in res">
									<td>{{ x.agent_name }}</td>
									<td>{{ x.assigned_category }}</td>
									<td>{{ x.ranked_category }}</td>
									<td>₦ {{ x.target_monthly_amount }} / {{x.target_monthly_count}}</td>
									<td>{{ x.run_month }}</td>
									<td><a id={{x.agent_name}} class='editpro' ng-click='detail($index,x.agent_name)' data-toggle='modal' data-target='#ServiceDialogue'>
								<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a></td>
								</tr>
								<tr ng-show="res.length==0">
									<td style='text-align:center' colspan='6' >
										<?php echo JOUNRAL_ENTRY_COMMI_MAIN_NO_DATA_FOUND; ?>            
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
			</div>
		
			 <div id='ServiceDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" >
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Agent Rank Summary Details - {{agent_name }}</h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'  ng-hide='isLoader'>
					<form action="" method="POST" name='ApplicatioViewDialgoue' id="ApplicatioViewDialgoue">
						<div id='ApplicationViewBody'>
							<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th>Date</th>
									<th>Cumulative</th>
									<th>Isolated</th>
									<th>Daily Trend</th>
								</tr>
							</thead>
							<tbody>
								 <tr ng-show='tablerow' ng-repeat="x in view">
									<td>{{ x.run_date }}</td>
									<td>₦ {{ x.DailyAmount }} / {{ x.DailyCount }}</td>
									<td>₦ {{ x.IsoAmount }} / {{ x.IsoCount }}</td>
									<td ng-if="x.DailyTrend =='U-UP'"  class='labspagreen'>{{ x.DailyTrend }}<img style='height:22px;width:22px' src='../common/img/sort-asc.png' /></td>
									<td ng-if="x.DailyTrend =='D-Down'" class='labspafin' >{{ x.DailyTrend }}<img style='height:22px;width:22px' src='../common/img/sort-desc.png' /></td>
									<td ng-if="x.DailyTrend =='N-No-Change'" class='labspafin' >{{ x.DailyTrend }}<img style='height:12px;width:12px' src='../common/images/error.png' /></td>
								</tr>
								<tr ng-show="view.length==0">
									<td style='text-align:center' colspan='5' >
										<?php echo JOUNRAL_ENTRY_COMMI_MAIN_NO_DATA_FOUND; ?>            
									</td>
								</tr>
							</tbody>
						</table>
							
							<div class='clearfix'></div>
						</div>
						 </form>	
					</div>				
					<div class='modal-footer'>					
						
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
		$('#selUser').val('').select2();
	    $('.dataTables_info').css("display","none"); // empty in case the columns change
		$('#datatable-1_paginate').css("display","none");
 
		});	
		
		$(function() {
  $('#MonthDrop, #YearDrop').on('keyup change',function() {
    if ($('#MonthDrop').val() == '' ||  $('#YearDrop').val() == '') {
      $('.enableOnInput').prop('disabled', true);
    } else {
      $('.enableOnInput').prop('disabled', false);
    }
  });
})
});
</script>