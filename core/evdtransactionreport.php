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
?>

<div ng-controller='evdtrreportCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!rpttra"><?php echo TRANSACTION_REPORT_MAIN_HEADING1; ?></a></li>
			<li><a href="#!rpttra">EVD Sales Report</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>EVD Sales Report</span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding" data-ng-init="fn_load(<?php echo "'".$partytype."',"."'".$partycode."'" ?>)">			
			  <div style='text-align:center' ng-hide='isMainLoader' class="loading-spiner-holder" data-loading ><div class="loading-spiner"><img src="../common/img/gif1.gif" /></div></div>			
				<form name='trReportForm' action='evdtrreportexcel.php' method='POST' >	
				<?php if($profileid ==50){ ?>
					<div class='row appcont' style='margin-bottom:0%' >
						<div  class='col-lg-3 col-xs-12 col-sm-12 col-md-12' ng-init="reportFor='ALL'">
						&nbsp;&nbsp;<label></label>
							<input value='ALL'  type='radio' name='reportFor' ng-model='reportFor'  />&nbsp;<label>ALL</label>&nbsp;&nbsp;&nbsp;
							<input value='C'  type='radio' name='reportFor' ng-model='reportFor' />&nbsp;<label>Champion</label>&nbsp;&nbsp;&nbsp;
							<input value='A'  type='radio' name='reportFor' ng-model='reportFor' />&nbsp;<label>Agent</label>
						</div>
						<div  class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>	
							<select  ng-model='partycode' ng-show="reportFor=='A'" ng-init="partycode='ALL'"  class='form-control' name='partycode' required >
								<option value="ALL">ALL</option>												
								<option ng-repeat="info in infos" value="{{info.code}}">{{info.code}} - {{info.name}}</option>
							</select>	
						</div>
					</div>
				<?php } ?>
					<div class='row appcont' style='margin-top:0%'>
						<div class='row appcont' ng-init="creteria='BT'">
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='BT' ng-click='clickra(creteria)' ng-checked='true' type='radio' name='creteria' ng-model='creteria' /></label>
								<label>Operator</label>
								<select ng-init = 'type="ALL"' ng-disabled="isOrderTypeDi" ng-model='type' class='form-control' name='type' required>
									<option value='ALL'><?php echo TRANSACTION_REPORT_MAIN_ORDER_TYPE_ALL; ?></option>
									<option ng-repeat="operator in operators" value="{{operator.operator_id}}">{{operator.operator_description}}</option>
								</select>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='BO' ng-click='clickra(creteria)' type='radio' name='creteria' ng-model='creteria' /></label>
								<label><?php echo TRANSACTION_REPORT_MAIN_ORDER_NO; ?><span class='spanre'>*</span><span ng-show=" trReportForm.orderNo.$touched || trReportForm.orderNo.$dirty &&  trReportForm.orderNo.$invalid">
									</span>	<span class = 'err' ng-show=" creteria=='BO'&& trReportForm.orderNo.$invalid && trReportForm.orderNo.$error.required"><?php echo REQUIRED;?>.</span></label>
								<input ng-disabled="isOrderNoDi" ng-model="orderNo" numbers-only id='orderNo' maxlength='10'  name='orderNo' required class='form-control'/>
							</div>
							
							 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							
						    	<label><?php echo TRANSACTION_REPORT_MAIN_START_DATE; ?></label>
								
								<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' id='startDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>							
								<label><?php echo TRANSACTION_REPORT_MAIN_END_DATE; ?></label>
								<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
								<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
							</div>
						</div>	
						<div class='row appcont' style='text-align:center'>
							<div style='text-align: -webkit-center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled="isQueryDi"  ng-click='query()' ng-hide='isHide'  id="Query"><?php echo TRANSACTION_REPORT_MAIN_QUERY_BUTTON; ?></button>
								<button type="button" ng-click='reset()'  class="btn btn-primary"   id="Reset"><?php echo TRANSACTION_REPORT_MAIN_RESET_BUTTON; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo TRANSACTION_REPORT_MAIN_REFRESH_BUTTON; ?></button>
							<button type="button" class="btn btn-primary" ng-disabled = 'infoViewForm.$invalid' ng-click='printAll()' ng-hide='isHide'  id="Query">Print</button>
								<button type="submit" class="btn btn-primary"   id="excel"ng-hide='isHideexcel;'>Excel</button>
							<button type="submit" class="btn btn-primary"  onclick='this.form.action="evdtrreportnewexcel.php";'  id="excel" ng-hide='isHideexcel;'>Detail Excel</button>
							</div>
						</div>
					<div class='row appcont'>
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo TRANSACTION_REPORT_MAIN_TABLE_ORDER_NO; ?></th>
									<th>Operator</th>
									<th><?php echo TRANSACTION_REPORT_MAIN_TABLE_REQUEST_AMOUNT; ?></th>
									<th><?php echo TRANSACTION_REPORT_MAIN_TABLE_TOTAL_AMOUNT; ?></th>
									<th><?php echo TRANSACTION_REPORT_MAIN_TABLE_AGENT_NAME; ?></th>
									<th><?php echo TRANSACTION_REPORT_MAIN_TABLE_DATE_TIME; ?></th>
									<th><?php echo TRANSACTION_REPORT_MAIN_TABLE_DETAIL; ?></th>
									<th>Print</th>								
								</tr>
							</thead>
							<tbody>
								 <tr ng-show='tablerow' ng-repeat="x in res">
									<td>{{ x.no }}</td>
									<td>{{ x.operator}}</td>
									<td>{{ x.reqmount }}</td>
									<td>{{ x.toamount }}</td>
									<td>{{ x.user }}</td>
									<td>{{ x.dtime }}</td>									
									<td><a class='trReportDialogue' ng-click='view(x.no,reportFor)' data-toggle='modal' data-target='#trReportDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
										<a href="#"> |&nbsp;&nbsp; </a>
										<a class='trReportDialogue' ng-click='viewcomm(x.no)' data-toggle='modal' data-target='#trReportCommDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a> 
									</td>
									<td><a class='trReportDialogue' ng-click='print(x.no)' data-toggle='modal' >
										<button class='icoimg'><img  src='../common/images/print1.jpg' /></button></a>
									</td>
									
								</tr>
								<tr ng-show="res.length==0">
									<td colspan='9' >
										<?php echo NO_DATA_FOUND; ?>              
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
				
		
			</div>
		</div>
	</div>
	 <div id='trReportCommDialogue' class='modal' role='dialog'>
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
				<form action="" method="POST" name='trReportDialogueForm' id="trReportDialogueForm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>EVD Sales Commission: # {{no}}</h2>
					</div>					 
					<div class='modal-body'>
					<div class='row'>
						<div class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					</div>
						<div id='trReportViewBody'  ng-hide='isLoader'>
							<table class='table table-borderd'>
								<thead>
								 <tr >
									<th><?php echo TRANSACTION_REPORT_MAIN_TABLE_TYPE; ?></th>
									<th><?php echo TRANSACTION_REPORT_MAIN_TABLE_AMOUNT; ?></th>
									<th><?php echo TRANSACTION_REPORT_MAIN_TABLE_PARTY; ?></th>
									<th><?php echo TRANSACTION_REPORT_MAIN_TABLE_PARTY_TYPE; ?></th>
									
								</tr>
								</thead>
								<tbody>
									 <tr ng-repeat="x in rescomms">
										<td>{{ x.rate_factor }}</td>
										<td>{{ x.rate_value}}</td>
										<td>{{ x.service_charge_group_name }}</td>
										<td>{{ x.service_charge_party_name }}</td>
										
									</tr>
									<tr ng-show="rescomms.length==0">
										<td colspan='4' >
											<?php echo NO_DATA_FOUND; ?>           
										</td>
									</tr>
								</tbody>									
							</table>
						</div>
					</div>				
					<div class='modal-footer'>					
						
					</div>
				</form>	
			</div>
		</div>	
	</div>	
	 <div id='trReportDialogue' class='modal' id='myModal' role='dialog'>
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
				<form action="" method="POST" name='trReportDialogueFormdetails' id="trReportDialogueFormdetails" ng-modal='clearAll' >
					<div class="modal-header">
						<button type="button" class="close"   ng-click='clear()' data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>EVD Sales Report : # {{no}}</h2>
					</div>					 
					<div class='modal-body'>
					<div class='row'>
						<div class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					</div>
						<div id='trReportViewBody'  ng-hide='isLoader' >
							<table class='table table-borderd' ng-repeat='x in resview'>
								<tr>
									<td>Order No : {{x.no}}</td>
									<td>Operator Code : {{x.operator_code}}</td>									
								</tr>
								<tr>									
									<td><?php echo FIN_SERVI_TRANSACTION_REPORT_TOTAL_AMOUNT; ?> {{x.total_amount}}</td>
									<td><?php echo FIN_SERVI_TRANSACTION_REPORT_REQUEST_AMOUNT; ?> {{x.request_amount}}</td>
								</tr>
								<tr>									
									<td>AMS Charge : {{x.ams_charge}}</td>
									<td><?php echo FIN_SERVI_TRANSACTION_REPORT_PARTNER_CHARGE; ?> {{x.partner_charge}}</td>
								</tr>
								<tr>									
									<td><?php echo FIN_SERVI_TRANSACTION_REPORT_OTHER_CHARGE; ?> {{x.other_charge}}</td>
									<td>Operator : {{x.operator_description}}</td>
								</tr>
								<tr>									
									<td>User :  {{x.user}}</td>
									<td>Plan Description : {{x.opr_plan_desc}}</td>

								</tr>
								<tr>									
									<td>Total Discount : {{x.total_discount}}</td>
									<td><?php echo FIN_SERVI_TRANSACTION_REPORT_REFERENCE_NO; ?> {{x.reference_no}}</td>
									
								</tr>
								
									<tr>									
									<td>Trans Log ID : {{x.evd_trans_log_id}}</td>
									<td ><?php echo FIN_SERVI_TRANSACTION_REPORT_DATE_TIME; ?> {{x.date_time}}</td>
								</tr>
								 <tr>
								 	<?php if($profileid ==1 || $profileid ==10 ||  $profileid ==20 ||  $profileid ==24 ||  $profileid ==22) { ?>
									<td>Pin: <input disabled type="password" value="{{x.reference_no2}}" id="myInput">
										<input type="checkbox" style='margin-right:1%'; onclick="myFunction()">Show Pin
									</td> 
									<td>Dial String : {{x.reference_no3}}</td>
								</tr>
									<?php } ?>
								<tr>
									<td>Reference No4 : {{x.reference_no4}}</td>
									<td>Mobile No : {{x.mobile_number}}</td>
								</tr>
								<tr>									
								   <td>Device ID : {{x.device_id}}</td>
									<td>ar Lock : {{x.ar_lock}}</td>
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
</div>
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	TestTable1();
	TestTable2();
	TestTable3();
	//LoadSelect2Script(MakeSelect2);
}
function myFunction() {
  var x = document.getElementById("myInput");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
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
		 /* window.alert = function() {}; alert = function() {}; */
		 
$("#EditINFODialogue, #AddINFODialogue").on("keypress",".sc", function (event) {
		    var regex = new RegExp("^[a-zA-Z0-9 \b:/.~!@#$*_-]+$");
 		    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
 		    if (!regex.test(key)) {
 		       event.preventDefault();
 		       return false;
 		    }
 		});
});

</script>