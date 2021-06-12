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
<div ng-controller='nonTransCtrl' data-ng-init="fn_load(<?php echo "'".$partyType."',"."'".$partyCode."'" ?>)">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!nontrans"><?php echo NON_TRANSACTION_REPORT_HEADING1; ?></a></li>
			<li><a href="#!nontrans"><?php echo NON_TRANSACTION_REPORT_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo NON_TRANSACTION_REPORT_HEADING3; ?></span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding" >	
			<div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>
				<form name='infoViewForm' method='POST'>	
					<div style="margin-left:23%;" class='row appcont'>						
						<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
											<label><?php echo NON_TRANSACTION_REPORT_START_DATE; ?></label>
											<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' id='startDate' name='startDate' required class='form-control'/>
										</div>
										<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>							
											<label><?php echo NON_TRANSACTION_REPORT_END_DATE; ?></label>
											<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
											<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
										</div>
										 
										 <div class='clearfix'></div>
									</div>	
											 <div  style = 'text-align:Center' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
											<button type="button" class="btn btn-primary" ng-disabled = 'infoViewForm.$invalid' ng-click='infoViewForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo NON_TRANSACTION_REPORT_BUTTON_QUERY; ?></button>
											<button type="button" class="btn btn-primary"   id="Refresh"><?php echo NON_TRANSACTION_REPORT_BUTTON_REFRESH; ?></button>
										</div>
								 </form>
							</div>		
																	
					<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo NON_TRANSACTION_REPORT_ID; ?></th>
									<th><?php echo NON_TRANSACTION_REPORT_MESSAGE_SEND_TIME; ?></th>
									<th><?php echo NON_TRANSACTION_REPORT_MESSAGE_RECEIVE_TIME; ?></th>
									<th><?php echo NON_TRANSACTION_REPORT_RESPONSE_RECIVED; ?></th>
									<th><?php echo NON_TRANSACTION_REPORT_ERROR_DESCRIPTION; ?></th>
									<th><?php echo NON_TRANSACTION_REPORT_DETAILS; ?></th>
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in nontrans">
									<td>{{ x.id }}</td>
									<td>{{ x.msgsndtime }}</td>
									<td>{{ x.msgrectime }}</td>
									<td>{{ x.responserec }}</td>
									<td>{{ x.desc }}</td>
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
						<h2 style='text-align:center'><?php echo NON_TRANSACTION_REPORT_DETAILS; ?> - {{id}}</h2>
					</div>		
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body' >
					   <form action="" method="POST" name='nontransDetailForm' id="nontransDetailForm">

						<table    style="overflow-wrap: break-word ;table-layout: fixed"; class='table table-bordered'>
							<thead>
								<tr>
									<th><?php echo NON_TRANSACTION_DETAIL_REPORT_ID; ?></th>
									<th>{{id}}</th>
								</tr>
								<tr>
									<th><?php echo NON_TRANSACTION_DETAIL_REPORT_SERVICE_FEATURE_ID; ?></th>
									<th>{{Serviceid}}</th>
								</tr>
								<tr>
									<th><?php echo NON_TRANSACTION_DETAIL_REPORT_REQUEST_MESSAGE ?></th>
									<th>{{reqmsg}}</th>
								</tr>
								<tr>
									<th style= "vertical-align:middle"><?php echo NON_TRANSACTION_DETAIL_REPORT_RESPONSE_MESSAGE; ?></th>
									<th >{{responsemsg}}</th>
								</tr>
								<tr>
									<th><?php echo NON_TRANSACTION_DETAIL_REPORT_MESSAGE_SEND_TIME; ?></th>
									<th>{{msgsndtime}}</th>
								</tr>
								<tr>
									<th><?php echo NON_TRANSACTION_DETAIL_REPORT_MESSAGE_RECEIVE_TIME; ?></th>
									<th>{{msgrectime}}</th>
								</tr>
								<tr>
									<th><?php echo NON_TRANSACTION_DETAIL_REPORT_RESPONSE_RECIVED; ?></th>
									<th style='color:red'>{{responserec}}</th>
								</tr>
								<tr>
									<th><?php echo NON_TRANSACTION_DETAIL_REPORT_ERROR_CODE; ?></th>
									<th>{{errorcode}}</th>
								</tr>
								<tr>
									<th><?php echo NON_TRANSACTION_DETAIL_REPORT_ERROR_DESCRIPTION; ?></th>
									<th>{{desc}}</th>
								</tr>
								<tr>
									<th><?php echo NON_TRANSACTION_DETAIL_REPORT_CREATE_USER; ?></th>
									<th>{{createuser}}</th>
								</tr>
								<tr>
									<th><?php echo NON_TRANSACTION_DETAIL_REPORT_CREATE_TIME; ?></th>
									<th>{{createtime}}</th>
								</tr>						
							</thead>
						</table>
						
						<div class='clearfix'></div>
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
	$("#infoViewDialogue, #infoEditDialogue").on("click","#Ok",function() {
		window.location.reload();
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