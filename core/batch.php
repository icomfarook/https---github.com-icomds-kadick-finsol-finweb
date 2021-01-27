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

<div ng-controller='batchCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!batch">Dash Board</a></li>
			<li><a href="#!batch">Batch</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Batch</span>
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
				<form name='batchReportForm'  method='POST' >	
					<div class='row appcont'>
						<div class='row appcont' ng-init="creteria='O'">
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo "Operation Id"; ?>&nbsp;&nbsp;<input type='radio' value='O' checked='true' ng-model='creteria'/></label>
								<input ng-disabled="creteria=='D'" ng-disabled="isOperationId" ng-model="oprId" type='text' maxlength='30' numbers-only id='operationId' name='oprId' required class='form-control'/>
							</div>													
							 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
						    	<label><?php echo "Start Date"; ?>&nbsp;&nbsp;<input type='radio' value='D' ng-model='creteria'/></label>
								<input ng-disabled="creteria=='O'" ng-model="startDate" type='date' id='startDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>							
								<label><?php echo "End Date"; ?></label>
								<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
								<input ng-disabled="creteria=='O'"  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo "Status"; ?></label>
								<select ng-init='status = "ALL"' ng-disabled="isOperationId" ng-model='status' class='form-control' name='status' required>
									<option value='ALL'>ALL</option>
									<option value='R'>R - Received</option>
									<option value='P'>P - Posted</option>
									<option value='N'>N - NotPosted</option>
									<option value='M'>M - Missing</option>
									<option value='E'>E - Error</option>
									<option value='O'>O - Other</option>								
								</select>
							</div>
						</div>	
						
						<div class='row appcont'>
							<div style='text-align: -webkit-center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled="isQueryDi"  ng-click='query()' ng-hide='isHide'  id="Query"><?php echo STATISTICAL_REPORT_MAIN_QUERY_BUTTON; ?></button>
								<button type="button" ng-click='reset()'  class="btn btn-primary"   id="Reset"><?php echo STATISTICAL_REPORT_MAIN_RESET_BUTTON; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo STATISTICAL_REPORT_MAIN_REFRESH_BUTTON; ?></button>
							</div>
						</div>
				
					</div>
					
					
						
					
				</form>		
<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th>Notification Id</th>
									<th>Operation Id</th>
									<th>Recived Time</th>
									<th>Processing Time</th>
									<th>Status</th>	
									<th>Action</th>	
								</tr>
							</thead>
							<tbody>
								 <tr  ng-repeat="x in batchList">
								
									<td>{{ x.nid }}</td>
									<td>{{ x.oid}}</td>
									<td>{{ x.rtime }}</td>	
									<td>{{ x.ptime }}</td>
									<td>{{ x.status }}</td>
									<td ng-show='x.sta == "R" || x.sta == "N" || x.sta == "E"'>
										<button class='icoimg'><img style='height:22px;width:22px' ng-click='process($index,x.oid,x.nid)' src='../common/images/detail.png' /></button>
									</td>
									<td ng-show='x.sta != "R" && x.sta != "N" && x.sta != "E"'>
										-
									</td>
								</tr>
								<tr ng-show="batchList.length==0">
									<td style='text-align:left' colspan='6' >
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
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings

$(document).ready(function() {
	
	$("#Refresh").click(function() {
		window.location.reload();
	});	
	
});
</script>