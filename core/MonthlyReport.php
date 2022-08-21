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
</style>
<div ng-controller='MonthTransCtrl' data-ng-init="fn_load(<?php echo "'".$partyType."',"."'".$partyCode."'" ?>)">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!daitrans"><?php echo NON_TRANSACTION_REPORT_HEADING1; ?></a></li>
			<li><a href="#!daitrans">Monthly Transaction Report</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Monthly Transaction Report</span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding" >	
			<div style='text-align:center' class="loading-spiner-holder"   data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>
				<form name='infoViewForm' action='monthreportexcel.php' method='POST'>	
					<div  class='row appcont'>						
                            <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12 MonthPicker'>
                                    <label>Month<span class='spanre'>*</span><span class = 'err' ng-show="dateerr">{{dateerr}}</span></label>
                                    <input  ng-model="MonthDate"  date-format="yyyy-MM" ng-blur='checkdate(startDate,endDate)' type='month' id='MonthDate' name='MonthDate' required class='form-control'/>
                                </div>		
										<!-- <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>							
											<label><?php echo NON_TRANSACTION_REPORT_END_DATE; ?></label>
											<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
											<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
										</div> -->
                       							
						<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12 MonthDropDown' >
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
						<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12 MonthDropDown' >
								<label>Year<span class='spanre'>*</span><span ng-show=" AgentMonthForm.YearDrop.$touched || AgentMonthForm.YearDrop.$dirty &&  AgentMonthForm.YearDrop.$invalid">
									</span>	<span class = 'err' ng-show="AgentMonthForm.YearDrop.$invalid && AgentMonthForm.YearDrop.$error.required"></span></label>
									<select class='form-control' id="YearDrop" name="YearDrop" ng-model="YearDrop" >
									<option value="" >Select Year</option>
									<option value="{{year}}" ng-repeat="year in yearList">{{year}}</option>
								</select>



							</div>	
                                    <div  style='margin-top:28px;' class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
                                        <label><input type='checkbox' ng-model='Detail' name='Detail' />&nbsp;&nbsp;Internal Agents</label>
                                    </div>
										
									
											 <div  style = 'text-align-last:auto;margin-top:inherit;margin-left: -127px' class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
											<button type="button" class="btn btn-primary" ng-disabled = 'infoViewForm.$invalid' ng-click='infoViewForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo NON_TRANSACTION_REPORT_BUTTON_QUERY; ?></button>
											<button type="button" class="btn btn-primary"   id="Refresh"><?php echo NON_TRANSACTION_REPORT_BUTTON_REFRESH; ?></button>
                                            <button type="submit" class="btn btn-primary"   id="excel" ng-hide='isHideexcel;'>Excel</button>
										</div>
										</div>	
								 </form>
							</div>		
							</div>		
                            <div class='clearfix'></div>
                            <p  style="font-size: initial;color:initial;color: blue;">Type Table</p>						
					<table ng-hide='isLoader' class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th style="padding: 24px;width: 32%;" rowspan="2">Type</th>
									<th colspan="2">Month </th>
                                    
                                        <tr>
                                            <th>Count</td>
                                            <th >Value</td>
                                        </tr>
                                        
                                       
							</thead>
							<tbody>
								 <tr ng-repeat="x in nontrans">
									<td>{{x.type}}</td>
									<td>{{x.count}}</td>
									<td>{{x.value}}</td>
						 		</tr>
								<tr ng-show="nontrans.length==0">
									<td colspan='3' >
										<?php echo NO_DATA_FOUND; ?>              
									</td>
								</tr>
							</tbody>
						</table>

                        <div class='clearfix'></div>
                        <p  style="font-size: initial;color:initial;color: blue;">Region Table</p>						
					<table ng-hide='isLoader' class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th style="padding: 24px;width: 32%;" rowspan="6">Region</th>
									<th colspan="6">Month </th>
                                    
                                        <tr>
                                            <th colspan="2">Air Time</td>
                                            <th colspan="2">Cash Sales</td>
                                            <th colspan="2">Bill Payment</td>
                                        </tr>
                                        <tr>
                                            <th>Count</td>
                                            <th >Value</td>
                                        
                                            <th>Count</td>
                                            <th >Value</td>

                                            <th>Count</td>
                                            <th >Value</td>
                                        </tr>
                                        
                                       
							</thead>
							<tbody>
								 <tr ng-repeat="x in RegionTable">
									<td>{{x.regions}}</td>
									<td>{{x.Count}}</td>
									<td>{{x.Value}}</td>
                                    <td>{{x.Count}}</td>
									<td>{{x.Value}}</td>
                                    <td>{{x.Count}}</td>
									<td>{{x.Value}}</td>
						 		</tr>
								<tr ng-show="RegionTable.length==0">
									<td colspan='6' >
										<?php echo NO_DATA_FOUND; ?>              
									</td>
								</tr>
							</tbody>
						</table>

                        <p  style="font-size: initial;color:initial;color: blue;">Transacted Percentage</p>						
					<table ng-hide='isLoader' class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
							
									<th style="padding: 24px;width: 32%;" rowspan="2">% of Agents that transacted</th>
									                                      
							</thead>
							<tbody>
								 <tr ng-repeat="x in PercentageTable">
									<td>{{x.transact_percentage}}</td>
						 		</tr>
								<tr ng-show="nontrans.length==0">
									<td colspan='1' >
										<?php echo NO_DATA_FOUND; ?>              
									</td>
								</tr>
							</tbody>
						</table>
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
  //LoadDataTablesScripts(AllTables);
 // WinMove();
	
   /*  $("#Query").click(function() {			
		$('.dataTables_info').css("display","block"); 	
		$('#datatable-1_paginate').css("display","block");	
		LoadDataTablesScripts(AllTables);
		
	}); */
	$("#Refresh").click(function() {
		window.location.reload();
	});
	$("#EditINFODialogue, #AddINFODialogue").on("keypress",".sc", function (event) {
		    var regex = new RegExp("^[a-zA-Z0-9 \b:/.~!@#$*_-]+$");
 		    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
 		    if (!regex.test(key)) {
 		       event.preventDefault();
 		       return false;
 		    }
 		});
		 /* window.alert = function() {}; alert = function() {}; */

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