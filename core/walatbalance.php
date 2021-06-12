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
<div ng-controller='WalletBalanceCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!waltblnce"><?php echo TSS_ACCOUNT_HEADING1; ?></a></li>
			<li><a href="#!waltblnce">Wallet Balance</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Wallet Balance</span>
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
				<form name='walletbalanceEntryForm' id="walletbalanceEntryForm" action='' method='POST'>	
					<div class='row appcont'>
					<div class='row appcont' >	
					<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12' style="width:28%;margin-left:35%">
								<label style="text-align:center;margin-left: 30%;">Partner<span ng-show="walletbalanceEntryForm.Partner.$touched ||walletbalanceEntryForm.Partner.$dirty && walletbalanceEntryForm.Partner.$invalid">
								<span class = 'err' ng-show="walletbalanceEntryForm.Partner.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-disabled='isInputDisabled' id='selUser' ng-model='Partner'  class='form-control' name='Partner' required >
									<option value=''>-- Select Partner --</option>
									<option value='PB'>PayAnt Bill Pay</option>
								</select>								
						</div>
						</div>
						
							<div style = 'text-align:Center;margin-top: 2%'; class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled = "walletbalanceEntryForm.$invalid"  ng-click='walletbalanceEntryForm.$invalid=true;query()'  ng-hide='isHide'  id="Query"><?php echo TSS_ACCOUNT_BUTTON_QUERY; ?></button>
								<button type="button" ng-click='reset()'  class="btn btn-primary"   id="Reset">Reset</button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo STATISTICAL_REPORT_MAIN_REFRESH_BUTTON; ?></button>
								
							
						</div>
						<div class='clearfix'></div><div class='clearfix'></div>
						
					<div class='row appcont'>					
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th>Name</th>
									<th>Balance</th>
									<th>Pending Balance</th>
									<th>Status</th>
									<th>Created</th>
									<th>Updated</th>
								</tr>
							</thead>
							<tbody ng-hide="tablerow" >
								 <tr>
									<td>{{name}}</td>
									<td>{{balance}}</td>
									<td>{{pendingBalance}}</td>
									<td>{{balanceStatus}}</td>
									<td>{{createdAt}}</td>
									<td>{{updatedAt}}</td>							
								</tr>
								<tr ng-show="res.length==0">
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
		$('.dataTables_info').css("display","none"); // empty in case the columns change
		$('#datatable-1_paginate').css("display","none");
 
		});	
});
</script>