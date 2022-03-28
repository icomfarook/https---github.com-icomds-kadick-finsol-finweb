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
				<form name='AgentMonthForm'  action ='agentMonthExcel.php' method='POST'>	
					<div class='row appcont' >									
					 	<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>				<label><?php echo STATISTICAL_REPORT_MAIN_AGENT_NAME; ?></label>
								<select  ng-init='agentCode = "ALL"' id='selUser'  ng-model='agentCode' class='form-control' name='agentCode' required>
									<option value='ALL'>--ALL--</option>
									<option ng-repeat="agent in agents" value="{{agent.agent_code}}">{{agent.agent_code}} - {{agent.agent_name}}</option>
									<option  ng-repeat="agent in agents" value="{{agent.code}}">{{agent.name}}</option>
								</select>
									
								</div>
								
								<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12 MonthPicker'>
								<label>Month<span class='spanre'>*</span><span ng-show=" AgentMonthForm.MonthDate.$touched || AgentMonthForm.MonthDate.$dirty &&  AgentMonthForm.MonthDate.$invalid">
									</span>	<span class = 'err' ng-show="AgentMonthForm.MonthDate.$invalid && AgentMonthForm.MonthDate.$error.required"></span></label>
								<input  ng-model="MonthDate"  date-format="yyyy-MM" type='month' id='MonthDate' name='MonthDate' required class='form-control'/>
							</div>		
							
							
						<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12 MonthDropDown' >
						<label>Month<span class='spanre'>*</span><span ng-show=" AgentMonthForm.MonthDrop.$touched || AgentMonthForm.MonthDrop.$dirty &&  AgentMonthForm.MonthDrop.$invalid">
									</span>	<span class = 'err' ng-show="AgentMonthForm.MonthDrop.$invalid && AgentMonthForm.MonthDrop.$error.required"></span></label>
								<select id="MonthDrop" class='form-control' ng-model="MonthDrop" name="MonthDrop">
									<option value="" >Select Month</option>
									<option value="01">January</option>
									<option value="02">February</option>
									<option value="03">March</option>
									<option value="04">April</option>
									<option value="05">May</option>
									<option value="06">June</option>
									<option value="07">July</option>
									<option value="08">August</option>
									<option value="09">September</option>
									<option value="10">October</option>
									<option value="11">November</option>
									<option value="12">December</option>
								</select>

							</div>		
						<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12 MonthDropDown' >
								<label>Year<span class='spanre'>*</span><span ng-show=" AgentMonthForm.YearDrop.$touched || AgentMonthForm.YearDrop.$dirty &&  AgentMonthForm.YearDrop.$invalid">
									</span>	<span class = 'err' ng-show="AgentMonthForm.YearDrop.$invalid && AgentMonthForm.YearDrop.$error.required"></span></label>
								<select class='form-control' id="YearDrop" name="YearDrop" ng-model="YearDrop" >
									<option value="" >Select Year</option>
									<option value="{{year}}" ng-repeat="year in yearList">{{year}}</option>
								</select>



							</div>									
							 
						</div>	<div class='clearfix'></div>
							<div  style = 'text-align:Center' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary enableOnInput"  ng-click='query()' ng-hide='isHide' ng-disabled = 'AgentMonthForm.$invalid' disabled='disabled'  id="Query"><?php echo JOUNRAL_ENTRY_COMMI_MAIN_BUTTON_QUERY; ?></button>
								<button type="button" class="btn btn-primary"   ng-click='reset()' id="Refresh">Reset</button>
								<button type="submit"  class="btn btn-primary"   id="excel"   ng-hide='isHideexcel;'>Excel</button>
							</div>
						<div class='clearfix'></div><br />	
																	
					<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th>Agent Code</th>
									<th>Run Month</th>
									<th>Assigned Rank</th>
									<th>Monthly Rank</th>
									<th>Details</th>
								</tr>
							</thead>
							<tbody>
								 <tr ng-show='tablerow' ng-repeat="x in res">
									<td>{{ x.agent_name }}</td>
									<td>{{ x.run_month }}</td>
									<td>{{ x.assigned_rank }}</td>
									<td>{{ x.monthly_rank }}</td>
									<td><a id={{x.id}} class='editpro' ng-click='detail($index,x.id)' data-toggle='modal' data-target='#ServiceDialogue'>
								<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a></td>
								</tr>
								<tr ng-show="res.length==0">
									<td style='text-align:center' colspan='5' >
										<?php echo JOUNRAL_ENTRY_COMMI_MAIN_NO_DATA_FOUND; ?>            
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
					</div>
			</div>
		
			 <div id='ServiceDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" style="width: fit-content;">
			<div class="modal-content" >
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Agent Rank Monthly - Details - {{agent_name}}</h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'  ng-hide='isLoader'>
					<form action="" method="POST" name='ApplicatioViewDialgoue' id="ApplicatioViewDialgoue">
						<div id='ApplicationViewBody'>
						<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><span >ID : </span><span class='labspa'>{{id}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><span >Party Type :</span><span class='labspa'>{{party_type}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Party Code :<span class='labspa'>{{agent_name }}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Run Month :<span class='labspa'>{{run_month}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Date Time :<span class='labspa'>{{date_time }}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Target Monthly Count :<span class='labspa'>{{target_monthly_count}}</span></label>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Target Monthly Amount :<span class='labspafin'>{{target_monthly_amount}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Actual ISO Daily Count :<span class='labspa'>{{actual_iso_daily_count}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Actual ISO Daily Amount :<span class='labspafin'>{{actual_iso_daily_amount}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Assigned Party Category :<span class='labspa'>{{assigned_rank}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Ranked Party Category :<span class='labspa'>{{monthly_rank}}</span></label>
							</div>
							
							<div class='clearfix'></div>
						</div>
						 </form>	
					</div>				
					<div class='modal-footer'>					
						
					</div>
				
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
