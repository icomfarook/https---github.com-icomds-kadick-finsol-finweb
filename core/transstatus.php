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
	 $parent_code= $_SESSION['parent_code'];
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
<div ng-controller='TransStatusCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!transtatus"><?php echo TSS_ACCOUNT_HEADING1; ?></a></li>
			<li><a href="#!transtatus">Transfer Fund Status</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Transfer Fund Status</span>
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
				<form name='fundwaletForm' action='cashoutpaymentexcel.php' method='POST'>	
					<div class='row appcont'>
					<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Status</label>
								<select  ng-init="status='ALL'" ng-model='status'  class='form-control' name='status' required>
									<option value="ALL">--ALL--</option>
									<option value='E'>E - Entered</option>
									<option value='I'>I - Inprogress</option>
									<option value='C'>C - Complete</option>
									<option value='F'>F - Fail</option>
									<option value='O'>O - Others</option>
								</select>
							</div>							
							 <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
						    	<label><?php echo TSS_ACCOUNT_START_DATE; ?>
								<span ng-show="tssAccountForm.startDate.$touched ||tssAccountForm.startDate.$dirty && tssAccountForm.startDate.$invalid">
								<span class = 'err' ng-show="tssAccountForm.startDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="startDate" type='date' id='StartDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo TSS_ACCOUNT_END_DATE; ?>
									<span ng-show="tssAccountForm.endDate.$touched ||tssAccountForm.endDate.$dirty && tssAccountForm.endDate.$invalid">
									<span class = 'err' ng-show="tssAccountForm.endDate.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input  ng-model="endDate" type='date' id='EndDate' name='endDate' required class='form-control'/>
							</div>
							<div class='clearfix'></div>
						<div class='row appcont'  style='text-align: -webkit-center;'>
							<div style='text-align: center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary"   ng-click='tssAccountForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo TSS_ACCOUNT_BUTTON_QUERY; ?></button>
								<button type="button" ng-click='reset()'  class="btn btn-primary"   id="Reset">Reset</button>
						</div>
						</div>
					<div class='row appcont'>					
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th>Sender</th>
									<th>Sender Type</th>
         							<th>Receiver</th>
									<th>Receiver Type</th>
									<th>Status</th>
									<th>Create Time</th>
									<th>Details</th>
									</tr>
							</thead>
							<tbody ng-hide="tablerow">
								 <tr ng-repeat="x in transferstatus">
									<td>{{ x.sender_partner_code }}</td>
									<td>{{ x.sender_partner_type}}</td>
									<td>{{ x.receiver_partner_code }}</td>
									<td>{{ x.receiver_partner_type }}</td>
									<td>{{ x.status }}</td>
									<td>{{ x.create_time }}</td>
									<td><a id={{x.id}} class='transferstatusDialogue' ng-click='detail($index,x.id)' data-toggle='modal' data-target='#transferstatusDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
									</td>	
															
								</tr>
								<tr ng-show="transferstatus.length==0">
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
	 <div id='transferstatusDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Transfer Fund Status Details</h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'  ng-hide='isLoader'>
					<form action="" method="POST" name='cashoutpayDialogue' id="cashoutpayDialogue">
						<div id='fundWaletViewBody' >
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label >Wallet Fund Transfer Id :<span class='labspa'>{{id}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Sender Partner Code  :<span class='labspa'>{{sender_partner_code}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Sender Partner Type  :<span class='labspa'>{{sender_partner_type}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Sender Wallet Type 	:<span class='labspa'>{{sender_wallet_type}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Receiver Partner Code	:<span class='labspa'>{{receiver_partner_code}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Receiver Partner Type	:<span class='labspa'>{{receiver_partner_type}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Receiver Wallet Type	:<span class='labspa'>{{receiver_wallet_type}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Status	:<span class='labspa'>{{status}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Transfer Amount	:<span class='labspa'>{{transfer_amount}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Create User	:<span class='labspa'>{{create_user}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Create Time	:<span class='labspa'>{{create_time}}</span></label>
							</div>							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Update Time:<span class='labspa'>{{update_time}}</span></label>
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
  
  $("#Reset").click(function() {
		$('.dataTables_info').css("display","none"); // empty in case the columns change
		$('#datatable-1_paginate').css("display","none");
 
		});	
	/* window.alert = function() {};
	alert = function() {}; */
});
</script>