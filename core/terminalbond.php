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
#AddCountryDialogue .table > tbody > tr > td {
	border:none;
}
.labspa {
	color:blue;
}
</style>
<div ng-controller= "TerminalBondCtrl">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!ascterbond">Dash Board</a></li>
			<li><a href="#!ascterbond">Terminal Bound</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
	<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Terminal Bound</span>
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
		<form action="termbondexcel.php" method="POST" name='terminalInventorySearchForm' id="terminalInventorySearchForm">
			<div class='row' class='row appcont'  style='margin:1%'>
				<div class='col-lg-4 col-xs-2 col-sm-2 col-md-2'>
					<label>Vendor</label> 
					<select   ng-model='vendor'  ng-init = "vendor='All'" class='form-control' name='vendor'  >
						<option value='All'>--ALL--</option>
						<option ng-repeat="vendor in vendors" value="{{vendor.id}}"> {{vendor.name}}</option>
						
					</select>										
				</div>
				<div class='col-lg-4 col-xs-2 col-sm-2 col-md-2'>
					<label>Terminal Id<span class='spanre'></span><span class='err' ng-show="terminalInventorySearchForm.terid.$error.required && terminalInventorySearchForm.terid.$invalid"><?php echo REQUIRED;?></span></label></label> 
					<input   type='terid' maxlength='10' name='terid' ng-model='terid' class='form-control'/>											
				</div>
				<div class='col-lg-4 col-xs-2 col-sm-2 col-md-2'>
					<label>Terminal Serial No<span class='spanre'></span><span class='err' ng-show="terminalInventorySearchForm.terslno.$error.required && terminalInventorySearchForm.terslno.$invalid"><?php echo REQUIRED;?></span></label></label> 
					<input   type='terslno' maxlength='20' name='terslno' ng-model='terslno' class='form-control'/>										
			</div>
			</div>
			<div class='row appcont'  style='text-align: -webkit-center;'>
				<div style='text-align: center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
					<button type="button" class="btn btn-primary"  ng-disabled = '' ng-click='query()' ng-hide='isHide'  id="Query">Search</button>
					<button type="button" class="btn btn-primary" ng-click='reset()'  id="Refresh">Reset</button>
					<button type="submit" class="btn btn-primary"   id="excel"  ng-hide='isHideexcel;'>Excel</button>
				</div>
			</div>	<div class='clearfix'></div><br />
		
			
			
				<table  class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>
							<th>Inventory Id</th>
							<th>Vendor</th>
      						<th>Agent Code</th>
							  <th>Bank Name</th>
							<th>Terminal Id</th>
							<th>Terminal Serial No</th>
						</tr>
					</thead>
					<tbody>
					      <tr ng-show='tablerow' ng-repeat="x in Inventory_list">
							<td>{{ x.inventory_id }}</td>
							<td>{{ x.vendor}}</td>
						 	<td>{{ x.agent_name }}</td>
							 <td>{{ x.bank }}</td>
							<td>{{ x.TerminalId }}</td>	
							<td>{{ x.TerminalSerialNo }}</td>								
							</tr>
							<tr ng-show="Inventory_list.length==0">
								<td colspan='6' >
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
	
		LoadDataTablesScripts(AllTables);
		
	});

	$("#EditBankAccountrDialogue, #AddBankAccountDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	
});
</script>

 