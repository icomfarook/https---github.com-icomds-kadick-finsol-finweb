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
#container{
	width:30%
	
}
#selUser  .select2-selection .select2-selection--single{
	height:30px !important;
}
</style>
<div ng-controller='AgentMonthCtrl' >
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!agntmnth"><?php echo JOUNRAL_ENTRY_COMMI_HEADING1; ?></a></li>
			<li><a href="#!agntmnth">Agent Rank - Monthly</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Agent Rank - Monthly</span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding">	
			<div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>
				<form name='infoViewForm'  action ='agentMonthExcel.php' method='POST'>	
					<div class='row appcont' >									
					 	<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>				<label><?php echo STATISTICAL_REPORT_MAIN_AGENT_NAME; ?></label>
								<select  ng-init='agentCode = ""' id='selUser'  ng-model='agentCode' class='form-control' name='agentCode' required>
									<option value=''>--Select--</option>
									<option ng-repeat="agent in agents" value="{{agent.agent_code}}">{{agent.agent_code}} - {{agent.agent_name}}</option>
									<option  ng-repeat="agent in agents" value="{{agent.code}}">{{agent.name}}</option>
								</select>
									
								</div>
						<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Month<span class='spanre'></span><span class='err' ng-show="paymentApproveForm.MonthDate.$error.required && paymentApproveForm.MonthDate.$invalid"><?php echo REQUIRED;?></span></label></label>
								<input  ng-model="MonthDate" type='date' id='MonthDate' name='MonthDate' required class='form-control'/>
							</div>			
							 
						</div>	<div class='clearfix'></div>
							<div  style = 'text-align:Center' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled = 'infoViewForm.$invalid' ng-click='query()' ng-hide='isHide'  id="Query"><?php echo JOUNRAL_ENTRY_COMMI_MAIN_BUTTON_QUERY; ?></button>
								<button type="button" class="btn btn-primary"   ng-click='reset()' id="Reset">Reset</button>
								<button type="submit" class="btn btn-primary"   id="excel"  ng-hide='isHideexcel;'>Excel</button>
							</div>
						</div>	<div class='clearfix'></div><br />	
																	
					<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th>Agent Code</th>
									<th>Run Month</th>
									<th>Assigned Rank</th>
									<th>Monthly Rank</th>
								</tr>
							</thead>
							<tbody>
								 <tr ng-show='tablerow' ng-repeat="x in res">
									<td>{{ x.agent_name }}</td>
									<td>{{ x.run_month }}</td>
									<td>{{ x.assigned_rank }}</td>
									<td>{{ x.monthly_rank }}</td>
								</tr>
								<tr ng-show="res.length==0">
									<td style='text-align:center' colspan='4' >
										<?php echo JOUNRAL_ENTRY_COMMI_MAIN_NO_DATA_FOUND; ?>            
									</td>
								</tr>
							</tbody>
						</table>
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