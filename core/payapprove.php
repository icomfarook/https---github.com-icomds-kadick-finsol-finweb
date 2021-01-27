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
<div ng-controller='payApproveCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!payapr"><?php echo PAYMENT_APPROVE_HEADING1; ?></a></li>
			<li><a href="#!payapr"><?php echo PAYMENT_APPROVE_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo PAYMENT_APPROVE_MAIN_HEADING; ?></span>
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
				<form name='paymentApproveForm' method='POST'>	
					<div class='row appcont'>
						<div class='row appcont' ng-init="creteria='BT' ">
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label ><input value='BT' type='radio' name='creteria' ng-checked='true' ng-model='creteria' /></label>
								<label><?php echo PAYMENT_APPROVE_TYPE; ?></label>
								<select autofocus='true'  ng-model='partyType' ng-disabled="creteria==='BD'" class='form-control' name='partyType' required>
									<option value=''><?php  echo PAYMENT_APPROVE_TYPE_ALL; ?></option>
									<option value='A'><?php echo PAYMENT_APPROVE_TYPE_AGENT; ?></option>
									<option value='C'><?php echo PAYMENT_APPROVE_TYPE_CHAMPION; ?></option>
									<option value='P'><?php echo PAYMENT_APPROVE_TYPE_PERSONAL; ?></option>		
									<option value='S'><?php echo PAYMENT_APPROVE_TYPE_SUBAGENT; ?></option>											
								</select>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='BD' type='radio' name='creteria' ng-model='creteria' /></label>
								<label><?php echo PAYMENT_APPROVE_DATE; ?><span class='spanre'></span><span class='err' ng-show="creteria == 'BD' && paymentApproveForm.paymentDate.$error.required && paymentApproveForm.paymentDate.$invalid"><?php echo REQUIRED;?></span></label></label>
								<input ng-disabled="creteria==='BT'" ng-model="paymentDate" type='date' id='paymentDate' name='paymentDate' required class='form-control'/>
							</div>							
							
							<div  class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-click='query()' ng-hide='isHide'  id="Query"><?php echo PAYMENT_APPROVE_QUERY_BUTTON; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo PAYMENT_APPROVE_REFRESH_BUTTON; ?></button>
							</div>
						</div>	
						
					<div class='row appcont'>
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php  echo PAYMENT_APPROVE_LIST_TABLE_ID; ?></th>
									<th><?php  echo PAYMENT_APPROVE_LIST_TABLE_PARTY_TYPE; ?></th>
									<th><?php  echo PAYMENT_APPROVE_LIST_TABLE_PARTY_CODE; ?></th>
									<th><?php  echo PAYMENT_APPROVE_LIST_TABLE_PAYMENT_TYPE; ?></th>
									<th><?php  echo PAYMENT_APPROVE_LIST_TABLE_PAYMENT_AMOUNT; ?></th>
									<th><?php  echo PAYMENT_APPROVE_LIST_TABLE_PAYMENT_DATE; ?></th>
									<th><?php  echo PAYMENT_APPROVE_LIST_TABLE_PAYMENT_STATUS; ?></th>
									<th><?php  echo PAYMENT_APPROVE_LIST_TABLE_APPROVE; ?></th>
									<th><?php  echo PAYMENT_APPROVE_LIST_TABLE_REJECT; ?></th>
								
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in paymentapproves">
									<td>{{ x.id }}</td>
									<td>{{ x.type }}</td>
									<td>{{ x.code }}</td>
	     							<td>{{ x.paytype }}</td>
									<td>{{ x.payamount }}</td>
									<td>{{ x.paydate}}</td>
									<td>{{ x.status}}</td>
									<td><a id={{x.id}} class='PaymentApproveDialogue' ng-click='view($index,x.id)' data-toggle='modal' data-target='#PaymentApproveDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
									</td>
									<td><a id={{x.id}}  class='print' ng-click='reject($index,x.id)' >
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
									</td>
									
								</tr>
								<tr ng-show="paymentapproves.length==0">
									<td style='text-align:left' colspan='9' >
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
<div id='PaymentApproveDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo PAYMENT_APPROVE_APPROVE_HEADING1; ?> - <span style='color:blue'>{{partycode}}</span></h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>				
					<div class='modal-body'>
					<form action="" method="POST" name='PaymentApproveDialogue' id="PaymentApproveDialogue">
					
						<div id='ApproveBody'   ng-hide='isLoader'>
							<div class='row'>
								<div  class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
									<label><?php echo PAYMENT_APPROVE_PAYMENT_ENTRY_FORM; ?> <span style='color:blue'>{{fuser}} </span></label>									
								</div>	
								<div  class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
									<label><?php echo PAYMENT_APPROVE_PAYMENT_ENTRY_TO; ?> <span style='color:blue'>{{touser}}</span> </label>									
								</div>									
							</div>
							<div class='row'>
								<div  class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
									<label><?php echo PAYMENT_APPROVE_PARTY_TYPE; ?> <span style='color:blue'>{{partytype}}</span> </label>									
								</div>	
								<div  class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
									<label><?php echo PAYMENT_APPROVE_PARTY_CODE; ?> <span style='color:blue'>{{partycode}}</span> </label>									
								</div>									
							</div>
							<div class='row'>
								<div  class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
									<label><?php echo PAYMENT_APPROVE_PAYMENT_MODE; ?> <span style='color:blue'>{{paymentmode}}</span> </label>									
								</div>	
								<div  class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
									<label><?php echo PAYMENT_APPROVE_PAYMENT_AMOUNT; ?> <span style='color:blue'>{{paymentamount}}</span> </label>									
								</div>									
							</div>
							
							<div class='row'>
								
								<div  class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
									<label><?php echo PAYMENT_APPROVE_PAYMENT_DATE; ?> <span style='color:blue'>{{paymentdate}}</span> </label>									
								</div>		
									<div  class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
									<label><?php echo PAYMENT_APPROVE_STATUS; ?> <span style='color:blue'>{{status}} </span></label>									
								</div>									
							</div>
							
							<div class='row form_col12_element'>								
								<div  class='col-xs-12 col-md-12 col-lg-12 col-sm-12 '>
									<label><?php echo PAYMENT_APPROVE_PAYMENT_APPROVED_AMOUNT; ?> <span class='spanre'>*</span><span ng-show="paymentApproveForm.approvedamount.$touched ||paymentApproveForm.approvedamount.$dirty && paymentApproveForm.approvedamount.$invalid">
									<span class = 'err' ng-show="paymentApproveForm.approvedamount.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input  ng-model="approvedamount" type='text' id='approvedamount' name='approvedamount' required class='form-control'/>							
								</div>
							</div>	
							<div class='row form_col12_element'>
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
									<label><?php echo PAYMENT_APPROVE_COMMENTS; ?></label>
									<textarea rows='4'  type='text' class='form-control'   name='comment' ng-model='comment' readonly="true"   />
								</div>
								
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
									<label><?php echo PAYMENT_APPROVE_APPROVER_COMMENTS; ?><span class='spanre'>*</span><span ng-show="PaymentApproveDialogue.approveComment.$dirty && PaymentApproveDialogue.approveComment.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
									<textarea rows='4' placeholder='Please enter the comment' type='text' class='form-control'  name='approveComment' ng-model='approveComment' required />
								</div>
							</div>
							
							<div class='clearfix'></div>
						</div>
						</form>
					</div>				
					<div class='modal-footer' ng-hide='isLoader'>					
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-click='refresh()' id='Ok' ng-hide='isHideOk' ><?php echo PAYMENT_APPROVE_APPROVE_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="PaymentApproveDialogue.$invalid" ng-click="PaymentApproveDialogue.$invalid=true;approve(id,partytype,partycode)" id="Approve"><?php echo PAYMENT_APPROVE_APPROVE_BUTTON_APPROVE; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo PAYMENT_APPROVE_APPROVE_BUTTON_CANCEL; ?></button>
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
	$("#PaymentApproveDialogue").on("click","#Ok",function() {
		window.location.reload();
		});
	$("#Refresh").click(function() {
		window.location.reload();
	});	
	
});
</script>