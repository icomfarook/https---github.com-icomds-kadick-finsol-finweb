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
<div ng-controller='CashoutPayCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!appviw"><?php echo TSS_ACCOUNT_HEADING1; ?></a></li>
			<li><a href="#!appviw">Cash Out Payment Report</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Cash Out Payment Report</span>
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
					<?php   if($profileId == 1 || $profileId == 10 || $profileId == 24 || $profileId == 22 || $profileId == 20 || $profileId == 23 || $profileId == 26)  { ?>
					<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Agent Name</label> 
								<select ng-disabled='isInputDisabled' id='selUser' ng-model='agentCode' ng-init='agentCode = "ALL"'  class='form-control' name='agentCode' required >
									<option value='ALL'>--ALL--</option>
									<option ng-repeat="agent in agents" value="{{agent.agent_code}}">{{agent.agent_code}} - {{agent.agent_name}}</option>
									
									</select>										
						</div>
						<?php } ?>
						<?php  if($profileId == 51 ) { ?>
								<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>												
									<label>Agent Name<span class='spanre'>*</span>
									<span ng-show="payOutListForm.id.$dirty && payOutListForm.id.$invalid">
									<span class = 'err' ng-show="payOutListForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input  readonly = 'true'[(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
								</div>
							<?php } ?>
							
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
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo TSS_ACCOUNT_BUTTON_REFRESH; ?></button>
								<button type="button" class="btn btn-primary" ng-disabled = 'infoViewForm.$invalid' ng-click='print()' ng-hide='isHide'  id="Query">Print</button>
								<button type="submit" class="btn btn-primary"   id="excel"ng-hide='isHideexcel;'>Excel</button>
							</div>
						</div>
					<div class='row appcont'>					
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th>Receipt Id</th>
									<th>Party Code</th>
									<th>Payment Amount</th>
									<th>Approved Amount</th>
									<th>Reference No</th>
									<th>Date Time</th>
									<th><?php echo TSS_ACCOUNT_DETAILS; ?></th>
									</tr>
							</thead>
							<tbody id='tbody'>
								 <tr ng-repeat="x in cashoutpay">
									<td>{{ x.id }}</td>
									<td>{{ x.party_code }}</td>
									<td>{{ x.payment_amount}}</td>
									<td>{{ x.payment_appro_amnt }}</td>
									<td>{{ x.payment_ref_no }}</td>
									<td>{{ x.create_time }}</td>
									<td><a id={{x.id}} class='cashoutpayDialogue' ng-click='detail($index,x.id)' data-toggle='modal' data-target='#cashoutpayDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
									</td>	
															
								</tr>
								<tr ng-show="cashoutpay.length==0">
									<td colspan='8' >
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
	 <div id='cashoutpayDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Cash Out Payment Report Details</h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'  ng-hide='isLoader'>
					<form action="" method="POST" name='cashoutpayDialogue' id="cashoutpayDialogue">
						<div id='fundWaletViewBody' >
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label >Payment Receipt Id :<span class='labspa'>{{id}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Country  :<span class='labspa'>{{country}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Payment Date  :<span class='labspa'>{{pDate}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Party Code 	:<span class='labspa'>{{party_code}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Party Type	:<span class='labspa'>{{partyType}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Payment Account Id	:<span class='labspa'>{{payment_account_id}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Payment Amount	:<span class='labspa'>{{payment_amount}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Payment Approved Amount	:<span class='labspa'>{{payment_appro_amnt}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Payment Approved Date	:<span class='labspa'>{{payment_appro_date}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Payment Reference No	:<span class='labspa'>{{payment_ref_no}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Payment Reference Date	:<span class='labspa'>{{payment_ref_date}}</span></label>
							</div>							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Payment Source:<span class='labspa'>{{payment_source}}</span></label>
							</div>							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Payment Cheque No	:<span class='labspa'>{{payment_chequ_no}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Payment Status:<span class='labspa'>{{payment_status}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Comments :<span class='labspa'>{{comments}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Approver Comments:<span class='labspa'>{{approve_comments}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Create User:<span class='labspa'>{{create_user}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Create Time	:<span class='labspa'>{{create_time}}</span></label>
							</div>						
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Update User	:<span class='labspa'>{{update_user}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Update Time	:<span class='labspa'>{{update_time}}</span></label>
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
	//LoadSelect2Script(MakeSelect2);
}
		var curDate = new Date();
		curDate =curDate.getFullYear()+"-"+(curDate.getMonth()+1)+"-"+curDate.getDate();
		
		$("#StartDate, #EndDate").val(curDate);
$(document).ready(function() {
	$("#Query").click(function() {				
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
	/* window.alert = function() {};
	alert = function() {}; */
});
</script>