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
 	$partyType = $_SESSION['party_type'];
	$agent_name	=   $_SESSION['party_name'];
?>
<style>
#AddAuthorizationDialogue .table > tbody > tr > td {
	border:none;
}
fieldset.scheduler-border {
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
}

legend.scheduler-border {
    font-size: 1.2em !important;
    font-weight: bold !important;
    text-align: left !important;
	border:none;
	width:100px;
}
legend {
	border-bottom:none;
}
#selUser  .select2-selection .select2-selection--single{
	height:30px !important;
}
</style>
<div ng-controller='pOutCtrl' data-ng-init="fn_load(<?php echo "'".$partyType."',"."'".$partyCode."'" ?>)">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!comlis"><?php echo PAY_OUT_HEADING1; ?></a></li>
			<li><a href="#!comlis"><?php echo PAY_OUT_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo PAY_OUT_HEADING3; ?></span>
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
				<form name='payOutListForm' method='POST' ng-hide='isLoaderMain'>	
					<div class='row appcont'>					
						<?php  if($profileId == 1 || $profileId == 10 || $profileId == 20 || $profileId == 22 || $profileId == 26) {?>
						 <div class='row appcont'>
							 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								
								<label><?php echo PAY_OUT_MAIN_PARTY_TYPE ; ?><span class='spanre'>*</span>
								<span ng-show="payOutListForm.partyType.$dirty && payOutListForm.partyType.$invalid">
								<span class = 'err' ng-show="payOutListForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-change='partyload(this.partyType)' ng-model="partyType"  class='form-control' name = 'partyType' id='partytype' required>											
									<option value=""><?php echo PAY_OUT_MAIN_SELECT_TYPE; ?></option>
									<option value='MA'><?php echo PAY_OUT_MAIN_AGENT; ?></option>
									<option value='C'><?php echo PAY_OUT_MAIN_CHAMPION; ?></option>
									<option value='SA'><?php echo PAY_OUT_MAIN_SUB_AGENT; ?></option>
									<option value='P'><?php echo PAY_OUT_MAIN_PERSONAL; ?></option>
								</select>
								
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>										
								<label><?php echo PAY_OUT_MAIN_PARTY_CODE; ?><span class='spanre'>*</span>
								<span ng-show="payOutListForm.partyCode.$dirty && payOutListForm.partyCode.$invalid">
								<span class = 'err'  ng-show="payOutListForm.partyCode.$error.required"><?php echo REQUIRED;?></span></span></label>	
								<select  ng-model='partyCode'  id='selUser' class='form-control' name='partyCode' required >
								<option value=""><?php echo PAY_OUT_MAIN_SELECT_PARTY_CODE; ?></option>												
								<option ng-repeat="info in infos" value="{{info.code}}">{{info.code}} - {{info.name}}</option>
								</select>										
							</div>
							<?php } else { ?>
								<div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>&nbsp;&nbsp;
									<label><?php echo INFO_PARTY_CODE_CHAMPION ; ?><span class='spanre'>*</span>
									<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
									<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input  readonly = 'true' ng-disabled="creteria==='TP'" [(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
								</div>
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									<label><input value='TP' ng-init='topartyCode = "ALL"' type='radio' name='creteria' ng-model='creteria' /></label>&nbsp;&nbsp;
									<label><?php echo INFO_PARTY_CODE_AGENT; ?>	</label>
									<select  ng-model='topartyCode' ng-disabled="creteria==='SP'" class='form-control' name='topartyCode' required >
										<option value='ALL'><?php echo INFO_SELECT_PARTY_CODE_AGENT; ?></option>
										<option ng-repeat="info in infos" value="{{info.code}}">{{info.name}}</option>
									</select>										
								</div>
							<?php } ?>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									<label><?php echo PAY_OUT_MAIN_START_DATE; ?></label>
									<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' id='startDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>							
									<label><?php echo PAY_OUT_MAIN_END_DATE; ?></label>
									<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
									<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
							</div>							 
						</div>	
						<div  style = 'text-align:Center' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
							<button type="button" class="btn btn-primary" ng-click='query()' ng-hide='isHide'  id="Query"><?php echo PAY_OUT_MAIN_BUTTON_QUERY; ?></button>
							<button type="button" class="btn btn-primary"   id="Refresh"><?php echo PAY_OUT_MAIN_BUTTON_REFRESH; ?></button>
						</div>									
				</div>
				
					<div class='row appcont'>
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo PAY_OUT_MAIN_TABLE_CREATE_DATE; ?></th>
									<th><?php echo PAY_OUT_MAIN_TABLE_PAYOUT_TYPE; ?></th>
									<th><?php echo PAY_OUT_MAIN_TABLE_PAYOUT_AMOUNT; ?> </th>
									<th><?php echo PAY_OUT_MAIN_TABLE_PROCESSING_AMOUNT; ?></th>
									<th><?php echo PAY_OUT_MAIN_TABLE_TOTAL_AMOUNT; ?> </th>									
									<th><?php echo PAY_OUT_MAIN_TABLE_STATUS; ?></th>
									<th><?php echo PAY_OUT_MAIN_TABLE_DETAILS; ?></th>
									<th>Edit</th>
								</tr>
							</thead>
							<tbody>
								 <tr ng-show='tablerow' ng-repeat="x in payouts">
									<td>{{ x.date }}</td>
									<td>{{ x.type}}</td>
									<td>{{ x.payamount }}</td>
									<td>{{ x.proamount }}</td>
									<td>{{ x.totamount }}</td>		
									<td>{{ x.status }}</td>
									<td><a id={{x.id}} class='payOutDetail' ng-click='detail($index,x.id)' data-toggle='modal' data-target='#payOutDetail'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
									</td>
										<td ng-if="x.status=='P-Pending'"><a id={{x.id}} class='icoimg' ng-click='edit($index,x.id,x.ptype, x.payamount,x.proamount, x.totamount,x.bank)'  data-toggle='modal' data-target='#payOutEdit' >
										<button class='icoimg'><img class='icoimg' style='height:22px;width:22px' src='../common/images/edit.png'/></button></a>
										</td>
										<td ng-if="x.status!='P-Pending'">-	</td>
								</tr>
								<tr ng-show="payouts.length==0">
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
	<div id='payOutDetail' class='modal' role='dialog'>
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo PAY_OUT_MAIN_PAY_OUT_DETAILS; ?> - {{id}} <span ng-show='code'>[{{code}} <span ng-show='palogin'> - {{palogin}}</span>]</span></h2>
					</div>			
					<div style='text-align:center' ng-hide='isMainLoader' class="loading-spiner-holder" data-loading ><div class="loading-spiner"><img src="../common/img/gif2.gif" /></div></div>								
					<div class='modal-body' >
					<form action="" method="POST" name='payOutForm' id="payOutForm">
						<table class='table table-bordered'>
							<thead>
								<tr>
									<th><?php echo PAY_OUT_DETAILS_TABLE_ID; ?></th>
									<th>{{id}}</th>
								</tr>
								<tr>
									<th><?php echo PAY_OUT_DETAILS_TABLE_PARTY_TYPE; ?></th>
									<th>{{partype}}</th>
								</tr>
								<tr>
									<th><?php echo PAY_OUT_DETAILS_TABLE_PARTY_CODE; ?></th>
									<th>{{parcode}}</th>
								</tr>
								<tr>
									<th><?php echo PAY_OUT_DETAILS_TABLE_PAYOUT_TYPE; ?></th>
									<th>{{type}}</th>
								</tr>
								<tr>
									<th><?php echo PAY_OUT_DETAILS_TABLE_BANK_ID; ?></th>
									<th>{{bank}}</th>
								</tr>
								<tr>
									<th><?php echo PAY_OUT_DETAILS_TABLE_COMMISSION_PAYOUT_AMOUNT; ?></th>
									<th>{{payamount}}</th>
								</tr>
								<tr>
									<th><?php echo PAY_OUT_DETAILS_TABLE_PROCESSING_AMOUNT; ?></th>
									<th>{{proamount}}</th>
								</tr>
								
								<tr>
									<th><?php echo PAY_OUT_DETAILS_TABLE_TOTAL_AMOUNT; ?></th>
									<th>{{totamount}}</th>
								</tr>								
								<tr>
									<th><?php echo PAY_OUT_DETAILS_TABLE_STATUS; ?></th>
									<th>{{status}}</th>
								</tr>
								<tr>
									<th><?php echo PAY_OUT_DETAILS_TABLE_CREATE_TIME; ?></th>
									<th>{{date}}</th>
								</tr>
								<tr>
									<th><?php echo PAY_OUT_DETAILS_TABLE_UPDATE_TIME; ?></th>
									<th>{{utime}}</th>
								</tr>
								<tr>
									<th><?php echo PAY_OUT_DETAILS_TABLE_CREATE_USER; ?></th>
									<th>{{cuser}}</th>
								</tr>
								<tr>
									<th><?php echo PAY_OUT_DETAILS_TABLE_UPDATE_USER; ?></th>
									<th>{{uuser}}</th>
								</tr>								
							</thead>
						</table>
						
						<div class='clearfix'></div>
				
					</div>
					<div class='modal-footer'>
					 <button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide'  href='#'><?php echo APPLICATION_AUTHORIZE_DETAIL_BUTTON_CANCEL; ?></button>
					</div>
				</form>	
			</div>
		</div>
	</div>
	 
		<div id='payOutEdit' class='modal' role='dialog'>
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Pay Out Edit - {{id}} <span ng-show='code'>[{{code}} <span ng-show='palogin'> - {{palogin}}</span>]</span></h2>
					</div>			
					<div style='text-align:center' ng-hide='isMainLoader' class="loading-spiner-holder" data-loading ><div class="loading-spiner"><img src="../common/img/gif2.gif" /></div></div>								
					<div class='modal-body' id='modbody' >
					<form action="" method="POST" name='payOutForm' id="payOutForm">
					<div class='rowcontent' style=' margin: auto;'>
						<div class='row appcont' style='padding:4px 0px;'>						
						   <div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYOUT_REQUEST_CURRENT_COMMISSION_BALANCE; ?></label>		
									<input ng-model='curbalance' readonly='true' class='form-control' name='curbalance'/>
							</div>
						</div>
						<div class='row appcont' style='padding:4px 0px;'>		
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYOUT_REQUEST_PAYOUT_COMMISSION_AMOUNT; ?><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
								<span ng-show="payRequestForm.paycomamt.$dirty && payRequestForm.paycomamt.$invalid">
								<span class = 'err' ng-show="payRequestForm.paycomamt.$error.required"><?php echo REQUIRED;?></span></span></label>						
								<input ng-blur='cal()' ng-model='paycomamt' class='form-control' name='paycomamt'/>
							</div>
						</div>
						<div class='row appcont' style='padding:4px 0px;'>		
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYOUT_REQUEST_PROCESSING_CHARGE; ?></label>							
								<input ng-init='procharge=0.00' ng-model='procharge' readonly='true' class='form-control' name='procharge'/>
							</div>
						</div>
						<div class='row appcont' style='padding:4px 0px;'>		
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYOUT_REQUEST_TOTAL_PAYOUT_COMMISSION; ?></label>							
								<input ng-model='totalpaycom' readonly='true' class='form-control' name='totalpaycom'/>
							</div>
						</div>
						<div class='row appcont' style='padding:4px 0px;'>	
							<div  class='col-lg-12 col-xs-12 col-md-12 col-sm-12' style='float:center' width="20%" ng-show="payouttype == 'B'">
								<label><?php echo PAYOUT_REQUEST_BANK_ACCOUNT; ?></label>								
									<select ng-model="bankaccount"  class='form-control' name = 'bankaccount' id='bankaccount' required>											
										<option value=""><?php echo PAYMENT_ENTRY_SELECT_BANK; ?></option>
										<option ng-repeat="partybank in partybanks" value="{{partybank.id}}">{{partybank.name}}-{{partybank.account}}</option>
								</select>
							</div>
						</div>
						</div>
						
						<div class='clearfix'></div>
				
					</div>
					<div class='modal-footer' style='text-align:center'>
					<button type='button' ng-click='payRequestForm.$invalid=true;update(id)' class='btn btn-primary'  id='Payout' ng-hide='isPayoutUpdate' >Update </button>
					 <button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHidecancel'  href='#'><?php echo APPLICATION_AUTHORIZE_DETAIL_BUTTON_CANCEL; ?></button>
						<button type="button" ng-hide='isHideOk'  class="btn btn-primary"  ng-click='close()'  id="Ok"><?php echo PAYOUT_REQUEST_BUTTON_OK; ?></button>					 
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