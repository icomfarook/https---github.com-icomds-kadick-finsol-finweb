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
?>
<style>
#AddAuthorizationDialogue .table > tbody > tr > td {
	border:none;
}
.labspa {
	color:blue;
}
</style>
<div ng-controller='tier1AcstsCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!bnksts"><?php echo APPLICATION_VIEW_HEADING1; ?></a></li>
			<li><a href="#!bnksts">Account Status</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Tier 1 Account Status</span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding" data-backdrop="static" data-keyboard="false">	
                     	<div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>
				<form name='applicationViewForm' method='POST'>	
					<div class='row appcont'>
						<div class='row appcont' ng-init="creteria='BD'" >
																				
							 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='BD' type='radio' name='creteria' ng-model='creteria' /></label>
						    	<label><?php echo APPLICATION_VIEW_START_DATE; ?>
								<span ng-show="applicationViewForm.startDate.$touched ||applicationViewForm.startDate.$dirty && applicationViewForm.startDate.$invalid">
								<span class = 'err' ng-show="applicationViewForm.startDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="startDate" type='date' ng-disabled="creteria==='BM'" id='StartDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_END_DATE; ?>
									<span ng-show="applicationViewForm.endDate.$touched ||applicationViewForm.endDate.$dirty && applicationViewForm.endDate.$invalid">
									<span class = 'err' ng-show="applicationViewForm.endDate.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input ng-model="endDate" type='date' ng-disabled="creteria==='BM'" id='EndDate' name='endDate' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input  value='BM' type='radio' name='creteria' ng-model='creteria' /></label>
								<label>Mobile Number<span class='spanre'></span>
								<span ng-show="applicationViewForm.MobileNumber.$dirty && applicationViewForm.MobileNumber.$invalid">
								<span class = 'err' ng-show="applicationViewForm.MobileNumber.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input numbers_only maxlength='11' ng-trim="false" ng-disabled="creteria==='BD'" restrict-field="MobileNumber" " ng-model="mobileNumber" type='text' id='mobileNumber' name='MobileNumber' autofocus='true'required  class='form-control'/>
							</div>
						</div>	
						<div class='row appcont'  style='text-align: -webkit-center;'>
							<div style='text-align: center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='applicationViewForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo APPLICATION_VIEW_QUERY_BUTTON; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo APPLICATION_VIEW_REFRESH_BUTTON; ?></button>
							</div>
						</div>
					<div class='row appcont'>					
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th>Request #</th>
									<th>Name</th>
									<th>Mobile</th>
									<th>Status</th>
									<th>New Account No</th>
									<th>Create Date</th>
									<th>Lookup </th>
									
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in payouts">
									<td>{{ x.id }}</td>
									<td>{{ x.user}}</td>
									<td>{{ x.mobile }}</td>
									<td>{{ x.staus }}</td>
									<td>{{ x.acno }}</td>
									<td>{{ x.cretime }}</td>
									<td><a id={{x.id}} class='statusDialogue' ng-click='view($index,x.mobile)' data-toggle='modal' data-target='#statusDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
									</td>									
								</tr>
								<tr ng-show="payouts.length==0">
									<td colspan='7' >
										<?php echo APPLICATION_VIEW_NO_DATA_FOUND; ?>              
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</form>
		</div>
	</div>
	 <div id='statusDialogue' class='modal' role='dialog'>
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Status</h2>
					</div>					
					<div class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>					
					<div class='modal-body' >	
						<form action="" method="POST" name='statusDialogue' id="statusDialogue">					
						<div id='tableres'>						
						</div>
						</form>	
					</div>				
					<div class='modal-footer'>					
						<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
					</div>				
			</div>
		</div>	
	</div>	
	
</div>
</div>
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	//TestTable1();
	//$("#datatable-1_filter, #datatable-1_length, .maintable box-content").hide();
	//$(".box-content").css("padding","0px");
	//TestTable2();
	//TestTable3();
}
		var curDate = new Date();
		curDate =curDate.getFullYear()+"-"+(curDate.getMonth()+1)+"-"+curDate.getDate();
		
		$("#StartDate, #EndDate").val(curDate);
$(document).ready(function() {
	$("#Query").click(function() {				
		//LoadDataTablesScripts(AllTables);
		
	});
	$("#Refresh").click(function() {
		window.location.reload();
	});	
	
});
</script>