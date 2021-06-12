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
	  $partyCode = $_SESSION['party_code'];
	 $profileId = $_SESSION['profile_id'];
	 $agent_name= $_SESSION['party_name'];
?>
<style>
#AddAuthorizationDialogue .table > tbody > tr > td {
	border:none;
}
.labspa {
	color:blue;
}
#selUser  .select2-selection .select2-selection--single{
	height:30px !important;
}

</style>
<div ng-controller='UpGradeCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!upgrade"><?php echo TSS_ACCOUNT_HEADING1; ?></a></li>
			<li><a href="#!upgrade">Upgrade</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Upgrade</span>
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
				<form name='upgradeEntryForm' id="upgradeEntryForm" action='' method='POST'>	
					<div class='row appcont'>
					<div class='row appcont' >	
					<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12' style="width:28%;margin-left:35%">
								<label style="text-align:center;margin-left: 30%;">Stand-Alone Agent<span ng-show="upgradeEntryForm.agentCode.$touched ||upgradeEntryForm.agentCode.$dirty && upgradeEntryForm.agentCode.$invalid">
								<span class = 'err' ng-show="upgradeEntryForm.agentCode.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-disabled='isInputDisabled' id='selUser' ng-model='agentCode'  class='form-control' name='agentCode' required >
									<option  style="text-align:center"; value=''>------Select Agent -----</option>
									
									<option ng-repeat="agent in Groupagents" value="{{agent.agent_code}}">{{agent.agent_code}} - {{agent.agent_name}}</option>
									
									</select>										
						</div>
						</div>
						
							<div style = 'text-align:Center;margin-top: 2%'; class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled = "upgradeEntryForm.$invalid"  ng-click='upgradeEntryForm.$invalid=true;query()'  ng-hide='isHide'  id="Query"><?php echo TSS_ACCOUNT_BUTTON_QUERY; ?></button>
								<button type="button" ng-click='reset()'  class="btn btn-primary"   id="Reset">Reset</button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo STATISTICAL_REPORT_MAIN_REFRESH_BUTTON; ?></button>
								
							
						</div>
						<div class='clearfix'></div><div class='clearfix'></div>
						
					<div class='row appcont'>					
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th>Agent Code</th>
									<th>Agent Name</th>
									<th>State</th>
									<th>Local Government</th>
									<th>Outlet Name</th>
									<th>View</th>
									<th>Upgrade</th>
									</tr>
							</thead>
							<tbody  ng-hide="tablerow">
								 <tr  ng-repeat="x in upgrade">
									<td>{{ x.agent_code }}</td>
									<td>{{ x.agent_name }}</td>
									<td>{{ x.state}}</td>
									<td>{{ x.local_govt }}</td>
									<td>{{ x.login_name }}</td>
									<td>
										<a id={{x.agent_code}} class='UpgradeViewDialogue' ng-click='view($index,x.agent_code)' data-toggle='modal' data-target='#UpgradeViewDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
									</td>
										<td><a  id={{x.agent_code}} class='UpgradeUpdaetDialogue' ng-click='view($index,x.agent_code)' data-toggle='modal' data-target='#UpgradeUpdaetDialogue' >
										<button class='icoimg'><img  src='../common/images/password.png' style="width: 30%;height: 30%;"/></button></a>
									
									</td>	
															
								</tr>
								<tr ng-show="upgrade.length==0">
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
		 <div id='UpgradeViewDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Upgrade Agent Details- {{outlet_name}}  <span ng-show='parenroutletname'>({{parenroutletname}})</span></h2>
		
				</div>				
					<div  style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
						<form action="" method="POST" name='editINFOForm' id="EditINFOForm">
						<div id='AuthBody'>
						
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
							<label> Agent Code : <span style='color:blue'> {{agent_code}}</span></label>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Agent Name : <span style='color:blue'>{{agent_name}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Login Name : <span style='color:blue'>{{lname}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Gender : <span style='color:blue'>{{gender}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Date of Birth : <span style='color:blue'>{{dob}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Business Type : <span style='color:blue'>{{BusinessType}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> BVN : <span style='color:blue'>{{bvn}}</span></label>								
							</div>
								<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Group Id : <span style='color:blue'>{{group_id}}</span></label>								
							</div>
								<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Group Type : <span style='color:blue'>{{group_type}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_TYPE; ?> : <span style='color:blue'>{{atype}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Address 1 : <span style='color:blue'>{{address1}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Address 2: <span style='color:blue'>{{address2}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Latitude: <span style='color:blue'>{{loc_latitude}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Longitude: <span style='color:blue'>{{loc_longitude}}</span></label>								
							</div>
							<div  ng-show='ptype' class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_LAST_PARENT_TYPE; ?> : <span style='color:blue'>{{ptype}}</span></label>								
							</div>
							<div ng-show='pcode'  class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_LAST_PARENT_CODE; ?> : <span style='color:blue'>{{pcode}}</span></label>								
							</div>
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo COUNTRY; ?> : <span style='color:blue'>{{country}}</span></label>								
							</div>	
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_LOCAL_STATE; ?> : <span style='color:blue'>{{state}}</span></label>								
							</div>
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_LOCAL_GOVERMENT; ?> : <span style='color:blue'>{{gvtname}}</span></label>								
							</div>								
												
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_BLOCK_STATUS; ?> : <span style='color:blue'>{{block_status}}</span></label>								
							</div>							
							<div ng-show='sub_agent' class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_SUB_AGENT; ?> : <span style='color:blue'>{{sub_agent}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_ZIP_CODE; ?> : <span style='color:blue'>{{zip_code}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_ACTIVE; ?> : <span style='color:blue'>{{active}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_APPLICATION_ID; ?> : <span style='color:blue'>{{application_id}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_BLOCK_REASON_ID; ?> : <span style='color:blue'>{{block_reason_id}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_BLOCK_STATUS; ?> : <span style='color:blue'>{{block_status}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_BLOCK_DATE; ?> : <span style='color:blue'>{{block_date}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_CONTACT_PERSON_NAME; ?> : <span style='color:blue'>{{contact_person_name}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_CONTACT_PERSON_MOBILE; ?> : <span style='color:blue'>{{contact_person_mobile}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_EMAIL; ?> : <span style='color:blue'>{{email}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_MOBILE_NO; ?> : <span style='color:blue'>{{mobile_no}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_WORK_NO; ?> : <span style='color:blue'>{{work_no}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_TAX_NO; ?> : <span style='color:blue'>{{tax_number}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_START_DATE; ?> : <span style='color:blue'>{{start_date}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_EXPIRY_DATE; ?> : <span style='color:blue'>{{expiry_date}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_USER; ?> : <span style='color:blue'>{{user}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_CREATE_USER; ?> : <span style='color:blue'>{{create_user}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_CREATE_TIME; ?> : <span style='color:blue'>{{create_time}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_UPDATE_USER; ?> : <span style='color:blue'>{{update_user}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_UPDATE_TIME; ?> : <span style='color:blue'>{{update_time}}</span></label>								
							</div>
							<div class='clearfix'></div>
						</div>
						</form>
					</div>				
					<div class='modal-footer'>
					</div>	
			</div>
		</div>	
	</div>	
	<div id='UpgradeUpdaetDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 style='text-align:center'>Upgrade {{agent_code}} - [ {{lname}} ] as Parent</h2>
				</div>	
				<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
				<div class='modal-body'  ng-hide='isLoader'>
					<div id="upgradeBody">	
						<form action="" method="POST" name='ApplicatioTransferDForm' id="ApplicatioTransferDForm">
						<div class='row appcont'>
						<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
							<label> Agent Code : <span style='color:blue'> {{agent_code}}</span></label>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Agent Name : <span style='color:blue'>{{agent_name}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Login Name : <span style='color:blue'>{{lname}}</span></label>								
							</div>
							
						
						<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_LAST_PARENT_TYPE; ?> : <span style='color:blue'>{{ptype}}</span></label>								
							</div>
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_LAST_PARENT_CODE; ?> : <span style='color:blue'>{{pcode}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> BVN : <span style='color:blue'>{{bvn}}</span></label>								
							</div>
								</div>
							<div class='row appcont'>				
								
								
								<div  class='col-lg-4 col-xs-12 col-sm-12 col-md-12 parenttype'>
									<label>Group ID<span class='spanre'>*</span><span class='err' ng-show="ApplicatioTransferDForm.parentcode.$error.required && ApplicatioTransferDForm.parentcode.$invalid"><?php echo REQUIRED;?></span></label>
									<input ng-disabled = 'isSelectDisabled'  readonly  ng-model="application_id"   name='application_id' id='application_id'  class='form-control'  
										value="'.{{application_id}}.'" />
									
								</div>
									<div  class='col-lg-4 col-xs-12 col-sm-12 col-md-12 parenttype'>
									<label>Group Type<span class='spanre'>*</span><span class='err' ng-show="ApplicatioTransferDForm.parentcode.$error.required && ApplicatioTransferDForm.parentcode.$invalid"><?php echo REQUIRED;?></span></label>
									<input ng-disabled = 'isSelectDisabled' readonly ng-init="parentcode = 'P-Parent'"  ng-model="parentcode"  name='parentcode' id='parentcode' value="p"   class='form-control'  />
										
								</div>
							   
													
							</div>				
						</form>
					</div> 
				</div>
				<div class='modal-footer' ng-hide='isLoader'>	
					<button type='button' class='btn btn-primary'  ng-click='refresh()' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo STATE_EDIT_BUTTON_OK; ?></button>				
					<button type='button' class='btn btn-primary'  ng-click='cancel()' data-dismiss='modal' ng-hide='isHide' ><?php echo PRE_APPLICATION_VIEW_APPROVE_BUTTON_CANCEL; ?></button>
					<button type='button' class='btn btn-primary'  ng-dblclick="false" ng-hide='isHide' ng-click="ApplicatioTransferDForm.$invalid=true;update(agent_code)"     ng-disabled="transferbtn" id="TranferFinal">Upgrade</button>
				</div>
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
		var curDate = new Date();
		curDate =curDate.getFullYear()+"-"+(curDate.getMonth()+1)+"-"+curDate.getDate();
		
		$("#StartDate, #EndDate").val(curDate);
$(document).ready(function() {
	$("#Query").click(function() {				
	$('.dataTables_info').css("display","block"); 	
		$('#datatable-1_paginate').css("display","block");
		LoadDataTablesScripts(AllTables);
		
		});
	$("#ApplicationEditDialogue").on("click","#Ok",function() {
//alert("sfd");
		window.location.reload();

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
   /* window.alert = function() {}; alert = function() {}; */
	 $("#Reset").click(function() {
		$('#selUser').select2('destroy');
		$('#selUser').val('').select2();
	    //$('#datatable-1').destroy();
		$('.dataTables_info').css("display","none"); // empty in case the columns change
		$('#datatable-1_paginate').css("display","none");
 
		});	
});
</script>