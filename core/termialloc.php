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
<div ng-controller='TermAllocCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!comlis"><?php echo CONTACT_HEADING1; ?></a></li>
			<li><a href="#!ctrcon">Terminal Allocation</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Terminal Allocation</span>
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
								<label style="text-align:center;margin-left: 30%;">Agent Code</label>
								<select  id='selUser'  ng-model='agentCode'   class='form-control' name='agentCode' required >
									<option style="text-align:center"; value=''>--Select--</option>
									<?php 
									$query="Select  agent_code,agent_name from agent_info";
									error_log($query);
									$result = mysqli_query($con, $query);
									if(!$result) {	
										$msg = die(" query failed = ". mysqli_error($con));
										error_log(" query failed = ".$msg);
									}
									else {
										while ($row = mysqli_fetch_assoc($result)){?>
										<option value="<?php echo $row['agent_code']?>"><?php echo $row['agent_code'];?> - <?php echo $row['agent_name'];?></option>
										<?php }
									}
									
									?> 
									
									</select>										
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
								 <th>Agent Name</th>						 
								 <th>Terminal Id</th>
								 <th>Terminal Serial No</th>
								 <th>Vendor Name</th>
								 <th>Default Allocation</th>	
								 <th>Serial No Allocation</th>	
								 </tr>
							</thead>
							<tbody>
								 <tr  ng-repeat="x in AllocationList">
									<td>{{ x.agent_name }}</td>
									<td>{{ x.terminal_id }}</td>
									<td>{{ x.terminal_serial_no }}</td>
									<td>{{ x.vendor_name }}</td>
									<td ng-if="x.terminal_id === '-'"><a id={{x.user_id}} class='icoimg' ng-click='allocationview($index,x.user_id)'  data-toggle='modal' data-target='#allocationview' >
										<button class='icoimg'><img class='icoimg' style="height:22px;width:22px;" src='../common/images/details_open.png'/></button></a>
									</td>
									<td ng-if="x.terminal_id === '-'"><a id={{x.user_id}} class='icoimg' ng-click='allocationview($index,x.user_id)'  data-toggle='modal' data-target='#allocationsview' >
										<button class='icoimg'><img class='icoimg' style="height:22px;width:22px;" src='../common/images/details_open.png'/></button></a>
									</td>
									<td ng-if="x.terminal_id != '-'"><a id={{x.terminal_id}} class='icoimg' ng-click='allocationCancel($index,x.terminal_id,x.user_id,x.terminal_serial_no,x.agent_name)'  data-toggle='modal' data-target='#allocationCancel'>
										<button class='icoimg'><img class='icoimg' style='height:15px;width:15px' src='../common/images/error.png'/></button></a>
									</td>
									<td ng-if="x.terminal_id != '-'"><a id={{x.terminal_id}} class='icoimg' ng-click='allocationCancel($index,x.terminal_id,x.user_id,x.terminal_serial_no,x.agent_name)'  data-toggle='modal' data-target='#allocationCancel'>
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



<div id='allocationsview' class='modal' role='dialog'  data-backdrop="static" data-keyboard="false">
		<div class='modal-dialog'>
			<div class='modal-content modal-md'>
				<div class='modal-header'><h3>Terminal Allocation {{agent_name}}<a class='close' data-dismiss='modal' ><i class='fa fa-close'></i></a></h3></div>
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					 <form  action='' name='allocateForm2' method='POST' id='allocateForm'>
					  <div id= "allocaupdateBody2">
					    <div class='row' style='margin:3%'>
						   <div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'  >
						   <label>Username
								</label> 
								<input readonly = 'true'  ng-model='userName'  type='text' id='Username' name='userName' autofocus='true' required class='form-control'/>
							</div>
						</div>
						  <div class='row' style='margin:3%'>
							  <div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'  >
						       <label>Agent Code
								<span ng-show="allocateForm2.Username.$dirty && allocateForm2.Username.$invalid">
								<span class = 'err' ng-show="allocateForm2.Username.$error.required"><?php echo REQUIRED;?> </span></span></label>
								<input  readonly = 'true' ng-model='aagentCode' name='agentCode'  type='text' id='Username' name='agentCode' autofocus='true' required class='form-control'/>
							</div>
						</div><input  type = 'hidden' ng-model='slnotr' name='slnotr'  id='slnotr' name='slnotr' autofocus='true'  class='form-control'/>
						  <div class='row' style='margin:3%'>
								<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
										<label>Vendor<span ng-show="allocateForm2.vendor.$dirty && allocateForm2.vendor.$invalid">
								<span class = 'err' ng-show="allocateForm2.vendor.$error.required"><?php echo REQUIRED;?> </span></span></label> 
										<select  ng-disabled='isInputDisabled' ng-model='vendor'   class='form-control' name='vendor' required >
											<option value=''>--Select--</option>
											<option ng-repeat="vendor in vendors" value="{{vendor.id}}"> {{vendor.name}}</option>											
										</select>										
								</div>
							</div>
						 <div class='row' style='margin:3%'>
							  <div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'  >
						       <label>Serial No
								<span ng-show="allocateForm2.slno.$dirty && allocateForm2.slno.$invalid">
								<span class = 'err' ng-show="allocateForm2.slno.$error.required"><?php echo REQUIRED;?> </span></span></label>
								<input   ng-model='slno' name='slno'  type='text' id='slno' name='slno' autofocus='true' required class='form-control'/>
							</div>
						</div>	
						 <div class='row' id='sltrdiv' ng-show='reiddiv' style='margin:3%'>
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'  ><label>Terminal Id</label><input  ng-model='tid' readonly='true' name='tid'  type='text' id='tid' name='tid' autofocus='true'  class='form-control'/></div>
						</div>
						
						<div style='text-align:center'>
							<input type='button' class='btn btn-primary'  ng-disabled = "allocateForm2.$invalid"  ng-click="searchsl(slno,vendor)" ng-hide='isHideSea' value='Search'/>
						</div>
						  
							</div>
						</form>				
					</div>
					<div class='modal-footer' style='text-align:Center' ng-show='btnter'>
						<input type='button' class='btn btn-primary'  ng-disabled = "isValidUpdate"  ng-click="update2(user_id,Status)" ng-hide='isHide' value='<?php echo USER_MAIN_DYNAMIC_UPDATE_BUTTON; ?>'/>
						<input type='button' data-dismiss='modal' class='btn btn-primary' value='<?php echo USER_MAIN_DYNAMIC_CANCEL_BUTTON; ?>' ng-hide='isHideCancel'/>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-click="refresh()" id='Ok' ng-hide='isHideOk' ><?php echo USER_MAIN_DYNAMIC_OK_BUTTON; ?></button>
					</div>	
				</div>
		</div>	
	</div>	

<div id='allocationview' class='modal' role='dialog'  data-backdrop="static" data-keyboard="false">
		<div class='modal-dialog'>
			<div class='modal-content modal-md'>
				<div class='modal-header'><h3>Terminal Allocation {{agent_name}}<a class='close' data-dismiss='modal' ><i class='fa fa-close'></i></a></h3></div>
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					 <form  action='' name='allocateForm' method='POST' id='allocateForm'>
					  <div id= "allocaupdateBody">
					    <div class='row' style='margin:3%'>
						   <div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'  >
						   <label>Username
								</label> 
								<input readonly = 'true'  ng-model='userName'  type='text' id='Username' name='userName' autofocus='true' required class='form-control'/>
							</div>
						</div>
						  <div class='row' style='margin:3%'>
							  <div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'  >
						       <label>Agent Code
								<span ng-show="allocateForm.Username.$dirty && allocateForm.Username.$invalid">
								<span class = 'err' ng-show="allocateForm.Username.$error.required"><?php echo REQUIRED;?> </span></span></label>
								<input  readonly = 'true' ng-model='aagentCode' name='agentCode'  type='text' id='Username' name='agentCode' autofocus='true' required class='form-control'/>
							</div>
						</div>
						  <div class='row' style='margin:3%'>
								<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
										<label>Vendor</label> 
										<select  ng-change='venchange(this.vendor)' ng-disabled='isInputDisabled' ng-model='vendor'   class='form-control' name='vendor' required >
											<option value=''>--Select--</option>
											<option ng-repeat="vendor in vendors" value="{{vendor.id}}"> {{vendor.name}}</option>
											
											</select>										
								</div>
							</div>
						  <div class='row' style='margin:3%' >
								<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
										<label>Terminal Id</label> 
										<select ng-disabled='isInputDisabled' ng-model='TerminalID'   class='form-control' name='TerminalID' required >
											<option value=''>--Select--</option>
											<option ng-repeat="terminal in terminals" value="{{terminal.terminal_id}}">{{terminal.terminal_id}} - {{terminal.terminal_serial_no}}</option>
											
											</select>										
								</div>
							</div>
							</div>
						</form>				
					</div>
					<div class='modal-footer' style='text-align:Center'>
						<input type='button' class='btn btn-primary'  ng-disabled = "allocateForm.$invalid"  ng-click="update(user_id,inventory_id,Status)" ng-hide='isHide' value='<?php echo USER_MAIN_DYNAMIC_UPDATE_BUTTON; ?>'/>
						<input type='button' data-dismiss='modal' class='btn btn-primary' value='<?php echo USER_MAIN_DYNAMIC_CANCEL_BUTTON; ?>' ng-hide='isHideCancel'/>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-click="refresh()" id='Ok' ng-hide='isHideOk' ><?php echo USER_MAIN_DYNAMIC_OK_BUTTON; ?></button>
					</div>	
				</div>
		</div>	
	</div>


<div id='allocationCancel' class='modal' role='dialog'  data-backdrop="static" data-keyboard="false">
		<div class='modal-dialog'>
			<div class='modal-content modal-md'>
				<div class='modal-header'><h3>Terminal Allocation Deallocate {{agent}}<a class='close' data-dismiss='modal' ><i class='fa fa-close'></i></a></h3></div>
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					 <form  action='' name='allocateForm1' method='POST' id='allocateForm'>
					   	<div id="allocancelBody" class='row'>
						 <div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'  >
						       <label>Do you want to deallocate {{terminal_id}} / {{terminal_serial_no}} device for the user {{agent}} ?</label>
								</div>
						<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>											
								<label>Status
								<span ng-show="allocateForm1.Status.$dirty && allocateForm1.Status.$invalid">
								<span class = 'err' ng-show="allocateForm1.Status.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select   ng-disabled='isInputDisabled' ng-model='CancStatus'  class='form-control' name = 'Status' id='Status' required>
								    <option value=''>--Select--</option> 
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
						<input type='button' class='btn btn-primary' ng-disabled = "allocateForm1.$invalid" ng-click='statusupdate(user_id,inventory_id)'    ng-hide='isHide' value='Update'/>
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