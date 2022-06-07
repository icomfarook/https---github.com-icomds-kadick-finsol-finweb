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
#selUser  .select2-selection .select2-selection--single{
	height:30px !important;
}

.labspa {
	color:blue;
}

</style>
<div ng-controller='ClientListCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!clntlist"><?php echo STATISTICAL_REPORT_MAIN_HEADING1; ?></a></li>
			<li><a href="#!clntlist">Client List</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Client List</span>
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
				<form name='stateReportForm'  action='statreportexcel.php' method='POST' novalidate>	
					<div class='row appcont'>
                    <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label>User Type<span class='spanre'>*</span></label>
								<select ng-init="UserType='L'" ng-model='UserType' class='form-control' name='UserType' required>
                                     <option value='L'>L-Authorized</option>
									<option value='I'>I-Installed</option>
									<option value='O'>O-Open</option>
									<option value='R'>R-Registered</option>
									
								</select>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label>Device Type<span class='spanre'>*</span></label>
								<select ng-init="DeviceType='ALL'" ng-model='DeviceType' class='form-control' name='DeviceType' required>
                                     <option value='ALL'>--ALL--</option>
									<option value='P'>P-POS</option>
									<option value='M'>M-Mobile</option>
								
									
								</select>
							</div>
                            <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo STATISTICAL_REPORT_MAIN_AGENT_NAME; ?></label>
								<select ng-init='agentCode = ""'  id='selUser' ng-model='agentCode' class='form-control' name='agentCode' required>
									<option value=''><?php echo STATISTICAL_REPORT_MAIN_AGENT_ALL; ?></option>
									<option  ng-repeat="agent in agents" value="{{agent.user_id}}">{{agent.agent_code}} - {{agent.agent_name}}</option>
								</select>
							</div>
                            <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label>IMEI</label>
								<input maxlength='100' ng-trim="false" ng-model="IMEI" type='text' id='IMEI' name='IMEI'   class='form-control'/>
							</div>
						</div>								
								
						<div class='row appcont'>
							<div style='text-align:center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled="isQueryDi"  ng-click='query()' ng-hide='isHide'  id="Query"><?php echo STATISTICAL_REPORT_MAIN_QUERY_BUTTON; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo STATISTICAL_REPORT_MAIN_REFRESH_BUTTON; ?></button>
							</div>
						</div>
					<div class='row appcont'>
                    <table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
                                <th>IMEI</th>
									<th>Agent Name</th>
									<th>User Type</th>
									<th>Device Type</th>
									<th>Create Time</th>
									<th>Details</th>
									<th>Delete</th>				
									
								</tr>
							</thead>
							<tbody>
								 <tr ng-show='tablerow' ng-repeat="x in infoss">
                                 <td>{{ x.imei }}</td>
									<td>{{ x.agent }}</td>
									<td>{{ x.status }}</td>
									<td>{{ x.device_type }}</td>
									<td>{{ x.create_time }}</td>
									<td><a id='{{x.id}}' class='ApplicationViewDialogue' ng-click='view($index,x.id)' data-toggle='modal' data-target='#ApplicationViewDialogue'>
									<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a></td>
                                     
                                    <td><a id='{{x.id}}' class='preApplicationDeleteDialogue' ng-click='delete($index,x.id)' data-toggle='modal' data-target='#preApplicationDeleteDialogue'>
									<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/error.png' /></button></a></td>
								</tr>
								<tr ng-show="infoss.length==0">
									<td style='text-align:center' colspan='7' >
										<?php echo JOUNRAL_ENTRY_COMMI_MAIN_NO_DATA_FOUND; ?>            
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					</div>
				</form>			
		</div>
	</div>
    <div id='ApplicationViewDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" >
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Client List Details - {{agent}}</h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					<form action="" method="POST" name='ApplicatioViewDialgoue' id="ApplicatioViewDialgoue">
						<div id='ApplicationViewBody'>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label># : <span class='labspa'>{{id}}</span></label>
							</div>
                            <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Agent: <span class='labspa'>{{agent}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>IMEI: <span class='labspa'>{{imei}}</span></label>
							</div>
                            
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Topic: <span class='labspa'>{{topic}}</span></label>
							</div>	
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>User Type :<span class='labspa'>{{status}}</span></label>
							</div>	
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Device Type :<span class='labspa'>{{device_type}}</span></label>
							</div>		
								<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Create Time: <span class='labspa'>{{create_time}}</span></label>
							</div>	
								<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Update Time: <span class='labspa'>{{update_time}}</span></label>
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

    <div id='preApplicationDeleteDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-md">
			<div class="modal-content" style='width:800px;margin-top:112px'>
					<div class="modal-header">
						<button  ng-hide='isLoader' type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Delete Form - {{agent}}</h2>
					</div>	
                    <div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					 <form action="" method="POST" name='ApplicationRejDialogue' id="ApplicationRejDialogue">
					<div class='modal-body'>
					
						<div id='DeleteBody' ng-hide='isLoader'>
						
						<h3 style='text-align:center'>Are you sure do you want to delete this Client List ?</h3>
					</div>
						</div>		
						 
						
					<div class='modal-footer' style='text-align:center' ng-hide='isLoader'>					
					<button type='button' class='btn btn-primary' ng-click='Delete($index,id)' ng-hide='isHide'  >Yes </button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' >No</button>
						<button type='button' class='btn btn-primary'  ng-click='refresh()' id='Ok' ng-hide='isHideOk' ><?php echo PRE_APPLICATION_VIEW_APPROVE_BUTTON_OK; ?></button>
					</div>
					<form>	
					
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
	$("#Query").click(function() {				
	$('.dataTables_info').css("display","block"); 	
		$('#datatable-1_paginate').css("display","block");	
		LoadDataTablesScripts(AllTables);
		
	});
	$("#Refresh").click(function() {
		window.location.reload();
	});	
	 /* window.alert = function() {}; alert = function() {}; */
	  $("#selUser").select2();

  // Read selected option
  $('#but_read').click(function(){
    var username = $('#selUser option:selected').text();
    var userid = $('#selUser').val();

    $('#result').html("id : " + userid + ", name : " + username);

  });
  $("#Reset").click(function() {
	   //alert();
		$('#selUser').select2('destroy');
		 $("#selUser").select2('val', val);
	    $('.dataTables_info').css("display","none"); // empty in case the columns change
		$('#datatable-1_paginate').css("display","none");
 
		});	
});
</script>