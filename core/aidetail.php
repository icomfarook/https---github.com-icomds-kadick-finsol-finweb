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
<style>
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    border-bottom: 1px solid #bbb !important;
}
</style>

<div ng-controller='AlDetailCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!aldetail"><?php echo TRANSACTION_REPORT_MAIN_HEADING1; ?></a></li>
			<li><a href="#!aldetail">Adempiere Detail</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Adempiere Detail</span>
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
					<div class='row appcont' style='margin-top:0%'>
						<div class='row appcont' ng-init="creteria='BT'">
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							<label><input value='BT' ng-click='clickra(creteria)' ng-checked='true' type='radio' name='creteria' ng-model='creteria' /></label>
									<label><?php echo TRANSACTION_REPORT_MAIN_ORDER_TYPE; ?></label>
							<select ng-init = 'type="ALL"' ng-disabled="isOrderTypeDi" ng-model='type' class='form-control' name='type' required>
								<option value='ALL'>--ALL--</option>
									<option ng-repeat="ser in servfeas" value="{{ser.id}}">{{ser.name}}</option>
								</select>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='BO' ng-click='clickra(creteria)' type='radio' name='creteria' ng-model='creteria' /></label>
								<label><?php echo TRANSACTION_REPORT_MAIN_ORDER_NO; ?><span class='spanre'>*</span><span ng-show=" trReportForm.orderNo.$touched || trReportForm.orderNo.$dirty &&  trReportForm.orderNo.$invalid">
									</span>	<span class = 'err' ng-show=" creteria=='BO'&& trReportForm.orderNo.$invalid && trReportForm.orderNo.$error.required"></span></label>
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
							</div>
						</div>
					<div class='row appcont'>
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th>Reference No</th>
									<th>Order  Type</th>
									<th>Status</th>
									<th>Create Time</th>
									<th>Al Document #</th>
									<th>Detail</th>
									<th>Reprocess</th>
								</tr>
							</thead>
							<tbody>
								 <tr ng-show='tablerow' ng-repeat="x in aidetail">
									<td>{{ x.reference_no }}</td>
									<td>{{ x.service_feature_code}}</td>
									<td>{{ x.status }}</td>
									<td>{{ x.create_time }}</td>
									<td>{{ x.document_no }}</td>			
									<td><a class='aidetailDialogue' ng-click='detail(x.reference_no)' data-toggle='modal' data-target='#AiDetailDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
									</td>
									<td ng-show ="x.status === 'Failure' && x.reprocess ==='N'"><a class='aidetailDialogue' confirmed-click='update(x.reference_no)' ng-confirm-click="Are You Sure Want to Reprocess this Order No ?"   data-toggle='modal'  data-target='#AiReprocessDialogue'>
										<button class='icoimg'><img  style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
									</td>
									<td ng-show ="x.status === 'Failure' && x.reprocess ==='Y'">Yes</td>								
									<td ng-show="x.status !=='Failure'"> - </td>								
								</tr>
								<tr ng-show="aidetail.length==0">
									<td colspan='7' >
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
	 <div id='AiDetailDialogue' class='modal' id='myModal' role='dialog'>
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
				<form action="" method="POST" name='trReportDialogueFormdetails' id="trReportDialogueFormdetails" ng-modal='clearAll'>
					<div class="modal-header">
						<button type="button" class="close"   ng-click='clear()' data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Adempiere Details: {{reference_no}}</h2>
					</div>					 
					<div class='modal-body'>
					<div class='row'>
						<div class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					</div>
						<div id='trReportViewBody'  ng-hide='isLoader'>
							<table class='table table-borderd'>
							<tr>
									<td>Ai Document No # : {{document_no}}</td>
									<td>Pic Point : {{pic_point}}</td>
								</tr>
							
								<tr>									
									<td>Order Type : {{service_feature_code}}</td>
									<td>Reference  No : {{reference_no}}</td>
								</tr>
								<tr>									
									<td>File Name : {{infilefol}}</td>
									<td>Out File Name : {{outfilefol}}</td>
								</tr>
								<tr>									
									<td>Status : {{status}}</td>
									<td >Process Count : {{process_count}}</td>
									
								</tr>
								<tr>									
									<td>Create Time : {{create_time}}</td>
									<td>Update Time : {{update_time}}</td>
								</tr>
								<tr>									
									<td>Reprocess Time : {{reprocess_time}}</td>
									<td>Complete Time : {{complete_time}}</td>
								</tr>
								<tr>
									<td colspan='2' >Reprocess : {{reprocess}}</td>
								</tr>
								
								<tr>
									<td colspan='2' >In File Content : {{in_file_content}}</td>
								</tr>
								<tr>
									<td colspan='2' >Out File Content : {{out_file_content}}</td>
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
	
});
</script>