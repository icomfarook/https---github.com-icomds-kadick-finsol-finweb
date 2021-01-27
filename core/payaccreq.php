
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
#AddAuthorizationDialogue .table > tbody > tr > td {
	border:none;
}
.labspa {
	color:blue;
}
</style>
<div ng-controller='payaccCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!appviw"><?php echo PAYABLE_ACCOUNT_HEADING1; ?></a></li>
			<li><a href="#!appviw"><?php echo PAYABLE_ACCOUNT_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo PAYABLE_ACCOUNT_HEADING3; ?></span>
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
				<form name='PayaccreqForm' method='POST'>	
					<div class='row appcont'>
						<div class='row appcont'  >	
							 <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYABLE_ACCOUNT_START_DATE; ?>
								<span ng-show="PayaccreqForm.startDate.$touched ||PayaccreqForm.startDate.$dirty && PayaccreqForm.startDate.$invalid">
								<span class = 'err' ng-show="PayaccreqForm.startDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-disabled="creteria==='BI' || creteria==='BS'" ng-model="startDate" type='date' id='StartDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYABLE_ACCOUNT_END_DATE; ?>
									<span ng-show="PayaccreqForm.endDate.$touched ||PayaccreqForm.endDate.$dirty && PayaccreqForm.endDate.$invalid">
									<span class = 'err' ng-show="PayaccreqForm.endDate.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input ng-disabled="creteria==='BI' || creteria==='BS'" ng-model="endDate" type='date' id='EndDate' name='endDate' required class='form-control'/>
							</div>
						</div>	
						<div class='row appcont'  style='text-align: -webkit-center;'>
							<div style='text-align: center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary"  ng-disabled = '' ng-click='PayaccreqForm.$invalid=true;query(id)' ng-hide='isHide'  id="Query"><?php echo PAYABLE_ACCOUNT_BUTTON_QUERY; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo PAYABLE_ACCOUNT_BUTTON_REFRESH; ?></button>
							</div>
						</div>
					<div class='row appcont'>					
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo PAYABLE_ACCOUNT_ID; ?></th>
									<th><?php echo PAYABLE_ACCOUNT_CRDEIT_LIMIT; ?></th>
									<th><?php echo PAYABLE_ACCOUNT_DAILY_LIMIT; ?></th>
									<th><?php echo PAYABLE_ACCOUNT_ADVANCE_AMOUNT; ?></th>
									<th><?php echo PAYABLE_ACCOUNT_AVAILABLE_BALANCE; ?></th>
									<th><?php echo PAYABLE_ACCOUNT_CURRENT_BALANCE; ?></th>
									<th><?php echo PAYABLE_ACCOUNT_MINIMUM_BALANCE; ?></th>
									<th><?php echo PAYABLE_ACCOUNT_DETAILS; ?></th>
									<th><?php echo PAYABLE_ACCOUNT_PRINT; ?></th>
									
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in payable">
									<td>{{ x.id }}</td>
									<td>{{ x.climit}}</td>
									<td>{{ x.dlimit }}</td>
									<td>{{ x.advamount }}</td>
									<td>{{ x.avaibalce }}</td>
									<td>{{ x.curbalance }}</td>
									<td>{{ x.minibalance }}</td>
									<td><a id={{x.id}} class='PayableDialogue' ng-click='detail($index,x.id)' data-toggle='modal' data-target='#PayableDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
									</td>
									<td><a id={{x.id}} class='print' ng-click='print($index,x.id)' >
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
									</td>
									
								</tr>
								<tr ng-show="payable.length==0">
									<td colspan='8' >
										<?php echo NO_DATA_FOUND; ?>              
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				
				</div>
			</form>
		</div>
	</div>
	 <div id='PayableDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h3 style='text-align:center'><?php echo PAYABLE_ACCOUNT_NIBSS_PAYABLE_ACC_DETAILS; ?></h3>
						</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'  ng-hide='isLoader'>
					<form action="" method="POST" name='PayableDialgoue' id="PayableDialgoue">
						<div id='PayableBody'>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYABLE_ACCOUNT_ID; ?><span class='labspa'>{{id}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYABLE_ACCOUNT_CREDIT_LIMIT; ?> :<span class='labspa'>{{climit}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYABLE_ACCOUNT_DAILY_LIMIT; ?> :<span class='labspa'>{{dlimit}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYABLE_ACCOUNT_ADVANCE_AMOUNT; ?> :<span class='labspa'>{{advamount}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYABLE_ACCOUNT_AVAILABLE_BALANCE; ?> :<span class='labspa'>{{avaibalce}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYABLE_ACCOUNT_CURRENT_BALANCE; ?> :<span class='labspa' style='color:red'>{{curbalance}}</span></label> 
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYABLE_ACCOUNT_MINIMUM_BALANCE; ?> :<span class='labspa'>{{minibalance}}</span></label> 
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYABLE_ACCOUNT_PREVIOUS_CURR_BALANCE; ?> :<span class='labspa'>{{precurbalance}}</span></label> 
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYABLE_ACCOUNT_UNCLEARED_BALANCE; ?> :<span class='labspa'>{{unclbalance}}</span></label> 
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYABLE_ACCOUNT_LAST_TX_NO; ?> :<span class='labspa'>{{ltxno}}</span></label> 
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYABLE_ACCOUNT_LAST_TX_AMOUNT; ?> :<span class='labspa'>{{ltxamount}}</span></label> 
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYABLE_ACCOUNT_LAST_TX_DATE; ?> :<span class='labspa'>{{ltxdate}}</span></label> 
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYABLE_ACCOUNT_ACTIVE; ?> :<span class='labspa' style='color:green'>{{active}}</span></label> 
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYABLE_ACCOUNT_BLOCK_STATUS; ?> :<span class='labspa'>{{blkstatus}}</span></label> 
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYABLE_ACCOUNT_BLOCK_DATE; ?> :<span class='labspa'>{{blkdate}}</span></label> 
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYABLE_ACCOUNT_BLOCK_REASON_ID; ?> :<span class='labspa'>{{blkreasid}}</span></label> 
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYABLE_ACCOUNT_CREATE_USER; ?> :<span class='labspa'>{{cuser}}</span></label> 
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYABLE_ACCOUNT_CREATE_TIME; ?> :<span class='labspa'>{{ctime}}</span></label> 
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYABLE_ACCOUNT_UPDATE_USER; ?> :<span class='labspa'>{{upuser}}</span></label> 
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYABLE_ACCOUNT_UPDATE_TIME; ?> :<span class='labspa'>{{uptime}}</span></label> 
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
	//TestTable1();
	//$("#datatable-1_filter, #datatable-1_length, .maintable box-content").hide();
	//$(".box-content").css("padding","0px");
	//TestTable2();
	//TestTable3();
}
		var curDate = new Date();
		curDate =curDate.getFullYear()+"-"+(curDate.getMonth()+1)+"-"+curDate.getDate();
		
		$("#StartDate, #EndDate").val(curDate);
$(document).ready(function() {
	$("#Query").click(function() {				
		//LoadDataTablesScripts(AllTables);
		
		
	});
	$("#ApplicationEditDialogue").on("click","#Ok",function() {
//alert("sfd");
		window.location.reload();

	});
	
	$("#Refresh").click(function() {
		window.location.reload();
	});	
	
});
</script>