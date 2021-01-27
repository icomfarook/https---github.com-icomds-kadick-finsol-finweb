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
<div ng-controller='payViewCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!payviw"><?php echo PAYMENT_VIEW_HEADING1; ?></a></li>
			<li><a href="#!payviw"><?php echo PAYMENT_VIEW_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo PAYMENT_VIEW_HEADING3; ?></span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding" >			
			<div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>	
				<form name='paymentViewForm'  action='payviewexcel.php' method='POST'>	
					<div class='row appcont' ng-init = "creteria='BI'">
						<div class='row appcont' >
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='BI' type='radio' ng-click='radiochange()' name='creteria' ng-checked='true' ng-model='creteria' /></label>
								<label><?php echo PAYMENT_VIEW_ID; ?></label>
								<input numbers_only maxlength='12' ng-trim="false"  restrict-field="id" ng-disabled="creteria==='BS' || creteria==='BPD' || creteria==='BAD'" ng-model="id" type='text' id='Id' name='id' autofocus='true' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='BS' type='radio'  ng-click='radiochange()' name='creteria' ng-model='creteria' /></label>
								<label><?php echo PAYMENT_VIEW_STATUS; ?>
								</label>
								<select ng-model='crestatus' ng-init="crestatus='ALL'" id='crestatus' ng-disabled="(creteria==='BI' || creteria==='BPD' || creteria==='BAD' ) " class='form-control' name='crestatus' required>
									<option value="ALL"><?php echo PAYMENT_VIEW_ALL; ?></option>
									<option value='E'><?php echo PAYMENT_VIEW_ENTERED; ?></option>
									<option value='P'><?php echo PAYMENT_VIEW_PENDING; ?></option>
									<option value='R'><?php echo PAYMENT_VIEW_REJECTED; ?></option>
									<option value='A'><?php echo PAYMENT_VIEW_APPROVED; ?></option>
									<option value='F'><?php echo PAYMENT_VIEW_FAILED; ?></option>
								</select>
							</div>
							
							 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='BPD' type='radio' ng-change='radiochange()' ng-click='pay()' name='creteria' ng-model='creteria' /></label>&nbsp;
						    	<label>Payment Start Date
								<span ng-show="paymentViewForm.paymentstartDate.$touched ||paymentViewForm.paymentstartDate.$dirty && paymentViewForm.paymentstartDate.$invalid">
								<span class = 'err' ng-show="paymentViewForm.paymentstartDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-disabled="(creteria==='BI' || creteria==='BS' || creteria==='BAD' )" ng-model="paymentstartDate" type='date' id='paymentstartDate' name='paymentstartDate' required class='form-control'/>
							</div>
							 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							   	<label>Payment End Date
								<span ng-show="paymentViewForm.paymentendDate.$touched ||paymentViewForm.paymentendDate.$dirty && paymentViewForm.paymentendDate.$invalid">
								<span class = 'err' ng-show="paymentViewForm.paymentendDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-disabled="(creteria==='BI' || creteria==='BS' || creteria==='BAD' )" ng-model="paymentendDate" type='date' id='paymentendDate' name='paymentendDate' required class='form-control'/>
							</div>
							
								<div class='row appcont' style="margin-top: 8%;">	
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12' >
								<label><input value='BAD' type='radio' ng-change='radiochange()' name='creteria' ng-click='appdate()' ng-model='creteria' /></label>&nbsp;
								<label>Approved Start Date
									<span ng-show="paymentViewForm.approvedstartDate.$touched ||paymentViewForm.approvedstartDate.$dirty && paymentViewForm.approvedstartDate.$invalid">
									<span class = 'err' ng-show="paymentViewForm.approvedstartDate.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input ng-disabled="creteria==='BI' || creteria==='BS' || creteria==='BPD'" ng-model="approvedstartDate" type='date' id='approvedstartDate' name='approvedstartDate' required class='form-control'/>
									</div>
							
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label>Approved End Date
									<span ng-show="paymentViewForm.approvedendDate.$touched ||paymentViewForm.approvedendDate.$dirty && paymentViewForm.approvedendDate.$invalid">
									<span class = 'err' ng-show="paymentViewForm.approvedendDate.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input ng-disabled="creteria==='BI' || creteria==='BS' || creteria==='BPD'" ng-model="approvedendDate" type='date' id='approvedendDate' name='approvedendDate' required class='form-control'/>
							</div>
							
							<div style="margin-top: 2%;" class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='paymentViewForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo PAYMENT_VIEW_QUERY_BUTTON; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo PAYMENT_VIEW_REFRESH_BUTTON; ?></button>
								<button type="submit" class="btn btn-primary"   id="excel"ng-hide='isHideexcel;'>Excel</button>
							</div>
							</div>	
							<div class='clearfix'></div>
						
						
						<div class='row appcont'>
							
						</div>
						</div>	
							
					<div class='row appcont'>
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo PAYMENT_ENTRY_VIEW_ID; ?></th>
									<th><?php echo PAYMENT_ENTRY_VIEW_PARTY_CODE; ?></th>
									<th>Bank Name</th>
									<th><?php echo PAYMENT_ENTRY_VIEW_PAYMENT_TYPE; ?></th>
									<th><?php echo PAYMENT_ENTRY_VIEW_PAYMENT_AMOUNT; ?></th>
									<th>Payment Date</th>
									<th>Approve Date</th>
									<th><?php echo PAYMENT_ENTRY_VIEW_STATUS; ?></th>
									<th><?php echo PAYMENT_ENTRY_VIEW_DETAILS; ?></th>
								
								</tr>
							</thead>
							<tbody>
								 <tr  ng-repeat="x in paymentviews">
									<td>{{ x.id }}</td>
									<td>{{ x.code}}</td>
									<td>{{ x.bank_name }}</td>
									<td>{{ x.paytype }}</td>
									<td>{{ x.payamount }}</td>
									<td>{{ x.payment_date }}</td>
									<td>{{ x.payment_approved_date }}</td>
									<td>{{ x.status }}</td>
									<td><a id={{x.id}} class='ApplicationViewDialogue' ng-click='view(x.id)' data-toggle='modal' data-target='#PaymentViewDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
									</td>
								</tr>
								<tr ng-show="paymentviews.length==0">
									<td colspan='9' >
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

<div id='PaymentViewDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo PAYMENT_VIEW_DETAIL_HEADING1; ?> for # {{id}}</h2>
					</div>
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					<form action="" method="POST" name='PaymentViewDialogue' id="PaymentViewDialogue">
						<div id='PaymentViewBody'  ng-hide='isLoader'>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYMENT_VIEW_DETAIL_ID; ?>   <span class='labspa'>{{id}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYMENT_VIEW_DETAIL_COUNTRY; ?>:  <span class='labspa'>{{country}}</span></label>
							</div>	
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
															<label><?php echo PAYMENT_VIEW_DETAIL_PAYMENT_AMOUNT; ?>:  <span class='labspa'>{{PaymentAmount}}</span></label>
							</div>
							<div class='col-lg-8 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYMENT_VIEW_DETAIL_BANK_ACCOUNT; ?>:  <span class='labspa'>{{BankAccount}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYMENT_VIEW_DETAIL_PAYMENT_DATE; ?>:  <span class='labspa'>{{PaymentDate}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYMENT_VIEW_DETAIL_PARTY_TYPE; ?>:  <span class='labspa'>{{partyType}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYMENT_VIEW_DETAIL_PARTY_CODE; ?>:  <span class='labspa'>{{partyCode}}</span></label>
							</div>							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYMENT_VIEW_DETAIL_REF_NO; ?>:  <span class='labspa'>{{PaymentRefNo}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYMENT_VIEW_DETAIL_REF_DATE; ?>:  <span class='labspa'>{{PaymentRefDate}}</span></label>
							</div>
						
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYMENT_VIEW_DETAIL_CHEQUE_NO; ?>:  <span class='labspa'>{{ChequeNo}}</span></label>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYMENT_VIEW_DETAIL_STATUS; ?>:  <span class='labspa'>{{PaymentStatus}}</span></label>
							</div>
							<div class='col-lg-8 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYMENT_VIEW_DETAIL_COMMENT; ?>:  <span class='labspa'>{{comment}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYMENT_VIEW_DETAIL_PAYMENT_APPROVED_AMOUNT; ?>:  <span class='labspa'>{{PaymentApprovedAmount}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYMENT_VIEW_DETAIL_PAYMENT_APPROVED_DATE; ?>:  <span class='labspa'>{{PaymentApprovedDate}}</span></label>
							</div>
							<div class='col-lg-8 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYMENT_VIEW_DETAIL_APPROVER_COMMENT; ?>:  <span class='labspa'>{{acomment}}</span></label>
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
$(document).ready(function() {
	$("#Query").click(function() {				
		//LoadDataTablesScripts(AllTables);
		
	});
	$("#Refresh").click(function() {
		window.location.reload();
	});	
	
if( $('#crestatus [type=radio]').is(':checked') == false ) {

   $(this).attr('checked',false).val('');
}

});
</script>