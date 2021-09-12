<?php 	
	include('../common/sessioncheck.php');
	error_reporting(0);
	$lang = $_SESSION['language_id'];
	$lang  = 1;
		include('../common/admin/finsol_label_ini.php');
	if($lang == "1" || $lang=="") {
		include('../common/admin/finsol_label_ini.php');
	}
	if($lang == "2"){
		include('../common/admin/haus_finsol_label_ini.php');
	}
?>
<style>
.form_col12_element {
    margin-top: 2%;
    margin: auto;
    margin-top: 1%;
}
.labspa {
	color:blue;
}
</style>
<div ng-controller='BillPayBettingCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!billbet">Bill Payment Betting Treatment</a></li>
			<li><a href="#!billbet">Bill Payment Betting Treatment</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Bill Payment Betting Treatment</span>
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
				<form name='trReportForm' action='trreportexcel.php' method='POST' >	
					<div class='row appcont'>
						<div class='row appcont'>							
							 <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
							
						    	<label><?php echo CASHOUT_TREATMENT_MAIN_START_DATE; ?></label>
								
								<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' id='startDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>							
								<label><?php echo CASHOUT_TREATMENT_MAIN_END_DATE; ?></label>
								<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
								<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>							
								<label><?php echo CASHOUT_TREATMENT_MAIN_ORDER_NO; ?></label>
								<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
								<input  numbers-only  ng-disabled="isOrderNoDi"  ng-model="orderno" type='text' required class='form-control'/>
							</div>
						</div>	
						<div class='row appcont' style='text-align:center'>
							<div style='text-align: -webkit-center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled="isQueryDi"  ng-click='query()' ng-hide='isHide'  id="Query"><?php echo CASHOUT_TREATMENT_MAIN_QUERY_BUTTON; ?></button>
								<button type="button" ng-click='reset()'  class="btn btn-primary"   id="Reset">Reset</button>
											<button type="button" class="btn btn-primary"   id="Refresh"><?php echo JOUNRAL_ENTRY_COMMI_MAIN_BUTTON_REFRESH; ?></button>
								
							</div>
						</div>
					<div class='row appcont'>
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo CASHOUT_TREATMENT_MAIN_TABLE_ORDER_NO; ?></th>
									<th><?php echo CASHOUT_TREATMENT_MAIN_TABLE_AGENT_CODE; ?></th>
									<th><?php echo CASHOUT_TREATMENT_MAIN_TABLE_REQUEST_AMOUNT; ?></th>
									<th><?php echo CASHOUT_TREATMENT_MAIN_TABLE_TOTAL_AMOUNT; ?></th>
									<th>Account Name</th>
									<th><?php echo CASHOUT_TREATMENT_MAIN_TABLE_MOBILE_NUMBER; ?></th>
									<th><?php echo CASHOUT_TREATMENT_MAIN_TABLE_DATE_TIME; ?></th>
									<th><?php echo CASHOUT_TREATMENT_MAIN_TABLE_DETAIL; ?></th>
									<th><?php echo CASHOUT_TREATMENT_MAIN_TABLE_ACTION; ?></th>								
								</tr>
							</thead>
							<tbody>
								 <tr ng-show='tablerow' ng-repeat="x in ctress">
									<td>{{ x.orderno }}</td>
									<td>{{ x.agentcode}}</td>
									<td>{{ x.reqamt }}</td>
									<td>{{ x.totamt }}</td>
									<td>{{ x.sendname }}</td>
									<td>{{ x.mblno }}</td>
									<td>{{ x.cretime }}</td>
												
									<td><a class='BillPayBetDetDialogue' ng-click='view(x.orderno)' data-toggle='modal' data-target='#BillPayBetDetDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
									</td><td>
										<a class='BillpayBetDialogue' ng-click='action(x.orderno)' data-toggle='modal' data-target='#BillpayBetDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
									</td>
									<!--<td><a class='cashOtrDialogue' ng-click='print(x.no,x.code)' data-toggle='modal' >
										<button class='icoimg'><img  src='../common/images/print1.jpg' /></button></a>
									</td>-->
									
								</tr>
								<tr ng-show="ctress.length==0">
									<td style='text-align:center' colspan='9' >
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
    <div id='BillpayBetDialogue' class='modal' role='dialog'  data-backdrop="static" data-keyboard="false">
		<div class='modal-dialog'>
			<div class='modal-content modal-md'>
				<div class='modal-header'><h3> Edit Bill Payment Order Number  #{{no}} Status<a class='close' data-dismiss='modal' ><i class='fa fa-close'></i></a></h3></div>
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					 <form  action='' name='allocateForm1' method='POST' id='allocateForm1'>
					   	<div id="BillPaymentBody" class='row'>
						
						<div style='margin-left:25%'; class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>											
								<label style='text-align:center;margin-left:37%'>Status 
								<span ng-show="allocateForm1.Status.$dirty && allocateForm1.Status.$invalid">
								<span class = 'err' ng-show="allocateForm1.Status.$error.required"></span></span></label>
								<select   ng-disabled='isInputDisabled' ng-model='Status'  class='form-control' name = 'Status' id='Status' required>
								    <option value=''>--Select--</option> 
									<option value='S'>S - Success</option>
									<option value='E'>F - Failed</option>
								</select>										
							</div>
							</div>
							
					</div>
					<div style='text-align-last: center;' class='modal-footer'>					
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-click='refresh()' id='Ok' ng-hide='isHideOk' >Ok</button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="allocateForm1.$invalid"  ng-click="allocateForm1.$invalid=true;process(no)" id="Process">Process</button>
                        <button type='button' class='btn btn-primary'ng-click='resetstatus()' data-dismiss='modal' ng-hide='isHide' >Cancel</button>
					</div>
                    <div class='clearfix'></div>
					</form>			
				</div>
		</div>	
	</div>
	 <div id='BillPayBetDetDialogue' class='modal' id='myModal' role='dialog'>
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
				<form action="" method="POST" name='cashOtrDetailDialogueFormdetails' id="cashOtrDetailDialogueFormdetails" ng-modal='clearAll'>
					<div class="modal-header">
						<button type="button" class="close"   ng-click='clear()' data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Bill Payment Betting Treatment For -  {{no}}</h2>
					</div>					 
					<div class='modal-body'>
					<div class='row'>
						<div class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					</div>
						<div id='ashOtrDetBody'  ng-hide='isLoader'>
                        <table class='table table-borderd'>
								<tr>
									<td><?php echo FIN_SERVI_TRANSACTION_REPORT_ORDER_NO; ?><span class='labspa'>  {{no}}</td>
									<td><?php echo FIN_SERVI_TRANSACTION_REPORT_ORDER_TYPE; ?><span class='labspa'>  {{code}}</td>
								</tr>
								<tr>									
									<td><?php echo FIN_SERVI_TRANSACTION_REPORT_REQUEST_AMOUNT; ?><span class='labspa'> {{rmount}}</td>
									<td><?php echo FIN_SERVI_TRANSACTION_REPORT_TOTAL_AMOUNT; ?><span class='labspa'> {{toamount}}</td>
									
								</tr>
								<tr>									
									<td><?php echo FIN_SERVI_TRANSACTION_REPORT_AMS_CHARGE; ?><span class='labspa'>  {{amscharge}}</td>
									<td><?php echo FIN_SERVI_TRANSACTION_REPORT_PARTNER_CHARGE; ?><span class='labspa'>  {{parcharge}}</td>
								</tr>
								<tr>									
									<td><?php echo FIN_SERVI_TRANSACTION_REPORT_OTHER_CHARGE; ?><span class='labspa'>  {{ocharge}}</td>
									<td >Mobile Number :<span class='labspa'> {{mobile}}</td>
									
								</tr>
								<tr>									
									<td>Session Id:<span class='labspa'>  {{session_id}}</td>
									<td>Transaction Id:<span class='labspa'>  {{bp_transaction_id}}</td>
								</tr>
								<tr>									
									<td>Payment Fee :<span class='labspa'> {{payment_fee}}</td>
									<td>Agent Charge :<span class='labspa'> {{agent_charge}}</td>
								</tr>
								
								<tr>									
									<td>Stamp Charge :<span class='labspa'> {{stamp_charge}}</td>
									<td><?php echo FIN_SERVI_TRANSACTION_REPORT_USER; ?> :<span class='labspa'> {{user}}</td>
								</tr>
								<tr>									
									<td>Account Name :<span class='labspa'> {{account_name}}</td>
									<td>Account Number :<span class='labspa'> {{account_no}}</td>
								</tr>
								<tr>									
									<td>Bp  Account Name :<span class='labspa'>  {{bp_account_name}}</td>
									<td>Bp Account Number :<span class='labspa'> {{bp_account_no}}</td>
								</tr>
								<tr>									
									<td>Bp Bank Code :<span class='labspa'> {{bp_bank_code}}</td>
                                    <td><?php echo FIN_SERVI_TRANSACTION_REPORT_DATE_TIME; ?> :<span class='labspa'>  {{dtime}}</td>
								</tr>
								
								<tr>									
								
									<td><?php echo FIN_SERVI_TRANSACTION_REPORT_TRANSACTION_LOG_ID; ?> :<span class='labspa'>  {{transLogId1}}</td>
                                    <td>Translog Id 2 :<span class='labspa'>  {{transLogId2}}</td>
                                </tr>
								<tr>									
									
									<td>Translog Id 3 :<span class='labspa'> {{transLogId3}}</td>
                                    <td>Additional Comments :<span class='labspa'> {{appcmt}}</td>
								</tr>
								<tr>									
									<td><?php echo FIN_SERVI_TRANSACTION_REPORT_POST_TIME; ?><span class='labspa'> {{ptime}}</td>
									<td><?php echo FIN_SERVI_TRANSACTION_REPORT_POST_STATUS; ?><span class='labspa'> {{pstatus}}</td>
								</tr>
								<tr>
									<td colspan='2'><?php echo FIN_SERVI_TRANSACTION_REPORT_COMMENT; ?><span class='labspa'> {{comments}}</td>
								
								</tr>
							</table>
						</div>
					</div>				
					<div class='modal-footer'>					
						
					</div>
				</form>	
			</div>
		</div>	
	</div>	
</div>
</div></div>
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings

$(document).ready(function() {
	$("#Query").click(function() {				
		LoadDataTablesScripts(AllTables);
		
	});
	  $('.modal-content').on('hidden', function() {
    clear()
  });
	$("#Refresh").click(function() {
		window.location.reload();
	});	
	
});
</script>