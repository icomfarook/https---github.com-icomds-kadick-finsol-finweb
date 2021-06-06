<?php 	
	include('../common/admin/configmysql.php');
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
#selUser  .select2-selection .select2-selection--single{
	height:30px !important;
}
</style>
<div ng-controller='CardAllocCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!cardalloc"><?php echo CONTACT_HEADING1; ?></a></li>
			<li><a href="#!cardalloc">Card Allocation</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Card Allocation</span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			<div >
			    <div class="box-content no-padding">	
			        <div  style='text-align:center' ng-hide='isMainLoader' class="loading-spiner-holder" data-loading ><div class="loading-spiner"><img src="../common/img/gif1.gif" /></div></div>			
			        	<form name='contactForm' method='POST' ng-hide='isLoaderMain' action="">	
			 		<div class='row appcont' >		

							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12' style="width:28%;margin-left:35%">
								<label style='text-align:center;margin-left:62px'>Account Number<span class='spanre'>*</span><span ng-show="InventoryEditForm.AccountNumber.$touched ||InventoryEditForm.AccountNumber.$dirty && InventoryEditForm.AccountNumber.$invalid">
								<span class = 'err' ng-show="InventoryEditForm.AccountNumber.$error.required"><?php echo REQUIRED;?>.</span></span></label>
					<input   maxlength='30' ng-model='AccountNumber' class='form-control'/>										
							</div>
					            <div  style = 'text-align:Center;margin-top: 2%'; class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled = 'contactForm.$invalid' ng-click='query()' ng-hide='isHide'  id="Query">Search</button>
								<button type="button" class="btn btn-primary"   id="Refresh">Reset</button>
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
					<div class='row appcont'>
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr>
								 <th>Inventory Id</th>						 
								 <th>Card Type</th>
								 <th>Status</th>
								 <th>Account Number</th>
								 <th>Bank</th>	
								 <th>Card Agent Allocation</th>	
								 </tr>
							</thead>
							<tbody>
								 <tr  ng-repeat="x in AllocationList">
									<td>{{ x.inventory_id }}</td>
									<td>{{ x.card_type }}</td>
									<td>{{ x.Status }}</td>
									<td>{{ x.account_num }}</td>
									<td>{{ x.bank }}</td>
									<td ng-if="x.Status === 'A-Available'"><a id={{x.inventory_id}} class='icoimg' ng-click='allocationview($index,x.inventory_id)'  data-toggle='modal' data-target='#allocationview' >
										<button class='icoimg'><img class='icoimg' style="height:22px;width:22px;" src='../common/images/details_open.png'/></button></a>
									</td>
									
									<td ng-if="x.Status != 'A-Available'"><a id={{x.inventory_id}} class='icoimg' ng-click='allocationCancel($index,x.inventory_id)'  data-toggle='modal' data-target='#allocationCancel'>
										<button class='icoimg'><img class='icoimg' style='height:15px;width:15px' src='../common/images/error.png'/></button></a>
									</td>
									</tr>
								<tr ng-show="AllocationList.length==0">
									<td colspan='6' >
										<?php echo NO_DATA_FOUND; ?>           
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
			</div>




<div id='allocationview' class='modal' role='dialog'  data-backdrop="static" data-keyboard="false">
		<div class='modal-dialog'>
			<div class='modal-content modal-md'>
				<div class='modal-header'><h3>Card Allocation - {{agent_name}}<a class='close' data-dismiss='modal' ><i class='fa fa-close'></i></a></h3></div>
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					 <form  action='' name='allocateForm' method='POST' id='allocateForm'>
					  <div id= "allocaupdateBody">
					<div class='row appcont' >		

							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label style="text-align:center;margin-left: 42%;">AgentCode<span class='spanre'>*</span>
									<span ng-show="posActivityForm.agentCode.$dirty && posActivityForm.agentCode.$invalid">
									<span class = 'err' ng-show="posActivityForm.agentCode.$error.required"><?php echo REQUIRED;?></span></span></label>	
									<select  style='width:50%;margin-left:130px'; ng-model='agentCode'  id='agentCode' class='form-control' name='agentCode' required >
									<option value="">--Select Agent Code--</option>												
									<option ng-repeat="agent in agents" value="{{agent.agent_code}}">{{agent.agent_code}} - {{agent.agent_name}}</option>
									</select>										
							</div>
									</div>
						</form>				
					</div>
						<div class='modal-footer' style='text-align:Center'>
						<input type='button' class='btn btn-primary'  ng-disabled = "allocateForm.$invalid" ng-hide='isHide' ng-click="update(inventory_id,Status)"  value='<?php echo USER_MAIN_DYNAMIC_UPDATE_BUTTON; ?>'/>
						<input type='button' data-dismiss='modal' class='btn btn-primary' value='<?php echo USER_MAIN_DYNAMIC_CANCEL_BUTTON; ?>' ng-hide='isHideCancel' />
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-click="refresh()" id='Ok' ng-hide='isHideOk' ><?php echo USER_MAIN_DYNAMIC_OK_BUTTON; ?></button>
					</div>	
				</div>
		</div>	
	</div>	
	</div>	
<div id='allocationCancel' class='modal' role='dialog'  data-backdrop="static" data-keyboard="false">
		<div class='modal-dialog'>
			<div class='modal-content modal-md'>
				<div class='modal-header'><h3>Card Allocation Deallocate - {{agent}}<a class='close' data-dismiss='modal' ><i class='fa fa-close'></i></a></h3></div>
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					 <form  action='' name='allocateForm1' method='POST' id='allocateForm'>
					   	<div id="allocancelBody" class='row'>
						 <div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'  >
						       <label>Do you want to deallocate the Card for this user   {{agent}} ?</label>
								</div>
						<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>											
								<label>Status
								<span ng-show="allocateForm1.Status.$dirty && allocateForm1.Status.$invalid">
								<span class = 'err' ng-show="allocateForm1.Status.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select   ng-disabled='isInputDisabled' ng-model='CancStatus'  class='form-control' name = 'Status' id='Status' required>
								    <option value=''>--Select--</option> 
									<option value='A'>Available</option>
									<option value='B'>Bound</option>
									<option value='X'>Block</option>
									<option value='D'>Damage</option>
									<option value='F'>Fault</option>
									<option value='S'>Suspend</option>
									<option value='O'>Other</option>
								</select>											
							</div>
							</div>
							
					</div>
					<div class='modal-footer' style='text-align:Center'>
						<input type='button' class='btn btn-primary' ng-disabled = "allocateForm1.$invalid" ng-click='statusupdate(inventory_id)'    ng-hide='isHide' value='Update'/>
						<input type='button' data-dismiss='modal' class='btn btn-primary' value='<?php echo USER_MAIN_DYNAMIC_CANCEL_BUTTON; ?>' ng-hide='isHideCancel'/>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-click="refresh()" id='Ok' ng-hide='isHideOk' ><?php echo USER_MAIN_DYNAMIC_OK_BUTTON; ?></button>
					</div>	
					</form>			
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
$(document).ready(function() {	 
	$("#Query").click(function() {			
	
		//LoadDataTablesScripts(AllTables);
		
	});
	$("#Refresh").click(function() {
		window.location.reload();
	});
		 $("#selUser").select2();

  // Read selected option
  $('#but_read').click(function(){
    var username = $('#selUser option:selected').text();
    var userid = $('#selUser').val();

    $('#result').html("id : " + userid + ", name : " + username);

  });
});
</script>