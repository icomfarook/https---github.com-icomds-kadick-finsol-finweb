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
?>

<div ng-controller='PosvasapiCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!posapi"><?php echo JOUNRAL_ENTRY_HEADING1; ?></a></li>
			<li><a href="#!posapi">POSVAS API</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>POSVAS  API</span>
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
				<form name='PosApiForm' action='posapiexcel.php' method='POST'>	
							 <div class='row appcont'>
					
										 <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
						    	<label><?php echo STATISTICAL_REPORT_MAIN_START_DATE; ?></label>
								<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' id='startDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>							
								<label><?php echo STATISTICAL_REPORT_MAIN_END_DATE; ?></label>
								<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
								<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
							</div>
										<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>										
											<label>Transaction Type
											<span ng-show="PosApiForm.ApiType.$dirty && PosApiForm.ApiType.$invalid">
											<span class = 'err' ng-show="PosApiForm.ApiType.$error.required"><?php echo REQUIRED;?></span></span></label>	
											<select  ng-model="ApiType"  ng-init='ApiType = "ALL"' type='date' id='ApiType' name='ApiType' required class='form-control' >
											<option value="ALL">--ALL--</option>
											<option value="T">T - Transaction</option>
											<option value="P">P - Prep Key</option>												
											<option value="C">C - Call Home</option>	
											</select>										
							</div>
																	 
										 <div class='clearfix'></div>
										 <div class='clearfix'></div><br />
									
											 <div  style = 'text-align:Center' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
											<button type="button" class="btn btn-primary"  ng-click='PosApiForm.$invalid=true;query()'   id="Query"><?php echo JOUNRAL_ENTRY_MAIN_BUTTON_QUERY; ?></button>
											<button type="button" class="btn btn-primary"   id="Refresh"><?php echo JOUNRAL_ENTRY_MAIN_BUTTON_REFRESH; ?></button>
											<button type="submit" class="btn btn-primary"   id="excel"  ng-hide='isHideexcel;'>Excel</button>
										</div>
							
								 
							</div>		
						<div class='row appcont'>													
					<table class="table table-bordered table-striped table-hover table-heading table-datatable" class="table" id="datatable-1">
						<thead>
								<tr> 
									<th>Date</th>
									<th >API Type</th>
									<th>Count</th>
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in posapilist">
									<td>{{ x.date }}</td>
									<td>{{ x.api_type }}</td>
									<td>{{ x.count }}</td>
							</tr>
								<tr ng-show="posapilist.length==0">
									<td style='text-align:center' colspan='3' >
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
	</div>
		</div>


<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	TestTable1();
	TestTable2();
	TestTable3();
	LoadSelect2Script();
}

$(document).ready(function() {
	$("#Query").click(function() {				
		LoadDataTablesScripts(AllTables);
		
		
	});
	$("#Refresh").click(function() {
		window.location.reload();
	});
   
});
</script>