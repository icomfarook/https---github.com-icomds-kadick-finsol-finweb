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
<div ng-controller='DailyTransCtrl' data-ng-init="fn_load(<?php echo "'".$partyType."',"."'".$partyCode."'" ?>)">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!daitrans"><?php echo NON_TRANSACTION_REPORT_HEADING1; ?></a></li>
			<li><a href="#!daitrans">Daily Transaction Report</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Daily Transaction Report</span>
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
				<form name='infoViewForm' action='dailyreportexcel.php' method='POST'>	
					<div  class='row appcont'>						
						<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
											<label>Run Date</label>
											<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' id='startDate' name='startDate' required class='form-control'/>
										</div>
										<!-- <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>							
											<label><?php echo NON_TRANSACTION_REPORT_END_DATE; ?></label>
											<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
											<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
										</div> -->
                                        <div  style='margin-top:28px;' class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
                                        <label><input type='checkbox' ng-model='Detail' name='Detail' />&nbsp;&nbsp;External Agents Only</label>
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
					<table ng-hide='isLoader' class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th>Run Date</th>
									<th>Party Code</th>
									<th>Party Name</th>
                                    <th>State</th>
									<th>Local Government</th>
									<th>Detail</th>
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in nontrans">
									<td>{{ x.run_date }}</td>
									<td>{{ x.party_Code }}</td>
									<td>{{ x.party_name }}</td>
									<td>{{ x.state_name }}</td>
									<td>{{ x.local_govt_name }}</td>
									<td><a id={{x.id}} class='nonTranscDetailDialogue' ng-click='detail(x.id)' data-toggle='modal' data-target='#NonTranscDetailDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
									</td>
						 		</tr>
								<tr ng-show="nontrans.length==0">
									<td colspan='9' >
										<?php echo NO_DATA_FOUND; ?>              
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				
			</div>
		</div>
	

	<div id='NonTranscDetailDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-md" style='width:1000px;'>
			<div class="modal-content">
				<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo NON_TRANSACTION_REPORT_DETAILS; ?> -  [ {{party_code}} - {{party_name}} ]</h2>
					</div>		
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body' >
					   <form action="" method="POST" name='nontransDetailForm' id="nontransDetailForm">
                       <div id='AuthBody'>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
							<label> # : <span style='color:blue'> {{id}}</span></label>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Party Type : <span style='color:blue'>{{party_type}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Party Code : <span style='color:blue'>{{party_code}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Party Name : <span style='color:blue'>{{party_name}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Run Date : <span style='color:blue'>{{run_date}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> State : <span style='color:blue'>{{state_name}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Local Government : <span style='color:blue'>{{local_govt_name}}</span></label>								
							</div>
							<!-- <div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Party Sales Type : <span style='color:blue'>{{party_sales_type}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Credit Limit : <span style='color:blue'>{{credit_limit}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Daily Limit : <span style='color:blue'>{{daily_limit}}</span></label>								
							</div> -->
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Advance Amount : <span style='color:blue'>{{advance_amount}}</span></label>								
							</div>
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Available Balance  : <span style='color:red'>{{available_balance}}</span></label>								
							</div>
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Current Balance : <span style='color:red'>{{current_balance}}</span></label>								
							</div>	
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Minimum Balance : <span style='color:blue'>{{minimum_balance}}</span></label>								
							</div>
							<!-- <div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Previous Current Balance : <span style='color:blue'>{{previous_current_balance}}</span></label>								
							</div>		 					
												
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Commission Available Balance : <span style='color:blue'>{{comm_available_balance}}</span></label>								
							</div>							
							<div ng-show='sub_agent' class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Commission Current Balance : <span style='color:blue'>{{comm_current_balance}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Commission Minimum Balance : <span style='color:blue'>{{comm_minimum_balance}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Last Tx No : <span style='color:blue'>{{last_tx_no}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Last Tx Amount : <span style='color:blue'>{{last_tx_amount}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Last Tx Date : <span style='color:blue'>{{last_tx_date}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Commission Last Tx No : <span style='color:blue'>{{comm_last_tx_no}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Commission Last Tx Amount : <span style='color:blue'>{{comm_last_tx_amount}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Commission Last Tx Date : <span style='color:blue'>{{comm_last_tx_date}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Commission Withdrawn Count : <span style='color:blue'>{{comm_withdraw_count}}</span></label>								
							</div> -->	
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Commission Withdrawn Amount : <span style='color:blue'>{{comm_withdraw_amount}}</span></label>								
							</div>
							<!-- <div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Commission Withdrawn Time : <span style='color:blue'>{{comm_withdraw_time}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Wallet Funding Count : <span style='color:blue'>{{wallet_fund_count}}</span></label>								
							</div> -->
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Wallet Funding Amount : <span style='color:blue'>{{wallet_fund_amount}}</span></label>								
							</div>
						<!-- 	<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Wallet Funding Time : <span style='color:blue'>{{wallet_fund_time}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Cash In Count : <span style='color:blue'>{{cashin_count}}</span></label>								
							</div> -->
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Cash In Amount : <span style='color:red'>{{cashin_amount}}</span></label>								
							</div>
							<!-- <div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Cash In Time  : <span style='color:blue'>{{cashin_time}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Cash Out Count : <span style='color:blue'>{{cashout_count}}</span></label>								
							</div> -->
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Cash Out Amount : <span style='color:red'>{{cashout_amount}}</span></label>								
							</div>
							<!-- <div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Cash Out Time : <span style='color:blue'>{{cashout_time}}</span></label>								
							</div>
                            <div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label>Evd Count: <span style='color:blue'>{{evd_count}}</span></label>								
							</div> -->
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Evd Amount : <span style='color:red'>{{evd_amount}}</span></label>								
							</div>
							<!-- <div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Evd Time  : <span style='color:blue'>{{evd_time}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label>Bill Pay Count : <span style='color:blue'>{{billpay_count}}</span></label>								
							</div> -->
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Bill Pay Amount : <span style='color:red'>{{billpay_amount}}</span></label>								
							</div>
							<!-- <div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Bill Pay Time : <span style='color:blue'>{{billpay_time}}</span></label>								
							</div> -->
                            <div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Create Time  : <span style='color:blue'>{{create_time}}</span></label>								
							</div>
							<div class='clearfix'></div>
						</div>
					 </form>	
					</div>
					<div class='modal-footer'>
					 <button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide'  href='#'><?php echo NON_TRANSACTION_DETAIL_REPORT_BUTTON_CANCEL; ?></button>
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
	
    $("#Query").click(function() {			
		$('.dataTables_info').css("display","block"); 	
		$('#datatable-1_paginate').css("display","block");	
		LoadDataTablesScripts(AllTables);
		
	});
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
});
</script>