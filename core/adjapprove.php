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
<div ng-controller='adjApproveCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!adjapr"><?php echo ADJUSTMENT_APPROVE_HEADING1; ?></a></li>
			<li><a href="#!adjapr"><?php echo ADJUSTMENT_APPROVE_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo ADJUSTMENT_APPROVE_ADJUSTMENT_APPROVE; ?></span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding">			
			<div  style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>			
				<form name='AdjustmentApproveForm' method='POST'>	
					<div class='row appcont'>
						<div class='row appcont' ng-init="creteria='BT'">
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><input ng-checked='true' value='BT' type='radio' name='creteria' ng-model='creteria' /></label>
								<label><?php echo ADJUSTMENT_APPROVE_TYPE; ?><span class='spanre'>*</span>
								<span ng-show="AdjustmentApproveForm.partyType.$dirty && AdjustmentApproveForm.partyType.$invalid">
								<span class = 'err' ng-show="AdjustmentApproveForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model='partyType' ng-disabled="creteria==='BD'" class='form-control' name='partyType' required>
									<option value=''><?php  echo ADJUSTMENT_APPROVE_TYPE_ALL; ?></option>
									<option value='A'><?php echo ADJUSTMENT_APPROVE_TYPE_AGENT; ?></option>
									<option value='C'><?php echo ADJUSTMENT_APPROVE_TYPE_CHAMPION; ?></option>
									<option value='P'><?php echo ADJUSTMENT_APPROVE_TYPE_PERSONAL; ?></option>		
									<option value='S'><?php echo ADJUSTMENT_APPROVE_TYPE_SUBAGENT; ?></option>										
								</select>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='BD' type='radio' name='creteria' ng-model='creteria' /></label>
								<label><?php echo ADJUSTMENT_APPROVE_DATE; ?><span class='spanre'>*</span><span class='err' ng-show="creteria == 'BD' && AdjustmentApproveForm.adjustmentDate.$error.required && AdjustmentApproveForm.adjustmentDate.$invalid"><?php echo REQUIRED;?></span></label>
								<input ng-disabled="creteria==='BT'" ng-model="adjustmentDate" type='date' id='adjustmentDate' name='adjustmentDate' required class='form-control'/>
							</div>							
							
							<div  style="margin-top: inherit;" class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='query()' ng-hide='isHide'  id="Query"><?php echo ADJUSTMENT_APPROVE_QUERY_BUTTON; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo ADJUSTMENT_APPROVE_REFRESH_BUTTON; ?></button>
							</div>
						</div>	
						
					<div class='row appcont'>
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php  echo ADJUSTMENT_APPROVE_LIST_TABLE_ID; ?></th>
									<th><?php  echo ADJUSTMENT_APPROVE_LIST_TABLE_PARTY_TYPE; ?></th>
									<th><?php  echo ADJUSTMENT_APPROVE_LIST_TABLE_PARTY_CODE; ?></th>
									<th><?php  echo ADJUSTMENT_APPROVE_LIST_TABLE_ADJUSTMENT_TYPE; ?></th>
									<th><?php  echo ADJUSTMENT_APPROVE_LIST_TABLE_ADJUSTMENT_AMOUNT; ?></th>
									<th><?php  echo ADJUSTMENT_APPROVE_LIST_TABLE_ADJUSTMENT_DATE; ?></th>
									<th><?php  echo ADJUSTMENT_APPROVE_LIST_TABLE_ADJUSTMENT_STATUS; ?></th>
									<th><?php  echo ADJUSTMENT_APPROVE_LIST_TABLE_APPROVE; ?></th>
									<th><?php  echo ADJUSTMENT_APPROVE_LIST_TABLE_REJECT; ?></th>
								
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in adjustmentapproves">
									<td>{{ x.id }}</td>
									<td>{{ x.type }}</td>
									<td>{{ x.code }}</td>
	     							<td>{{ x.adjtype }}</td>
									<td>{{ x.adjamount }}</td>
									<td>{{ x.adjdate}}</td>
									<td>{{ x.status}}</td>
									<td><a id={{x.id}} class='AdjustmentApproveDialogue' ng-click='view($index,x.id)' data-toggle='modal' data-target='#AdjustmentApproveDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
									</td>
									<td><a id={{x.id}}  class='print' ng-click='reject($index,x.id)' >
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
									</td>
									
								</tr>
								<tr ng-show="adjustmentapproves.length==0">
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
<div id='AdjustmentApproveDialogue' class='modal' role='dialog'  data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo ADJUSTMENT_APPROVE_APPROVE_HEADING1; ?> - <span style='color:blue'>{{partycode}}</span></h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>					
					<div class='modal-body'>
					<form action="" method="POST" name='AdjustmentApproveDialogue' id="AdjustmentApproveDialogue">
						<div id='ApproveBody' ng-hide='isLoader'>
							<div class='row'>
								<div  class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
									<label><?php echo ADJUSTMENT_APPROVE_ADJUSTMENT_ENTRY_FORM; ?><span style='color:blue'>{{fuser}} </span></label>									
								</div>	
								<div  class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
									<label><?php echo ADJUSTMENT_APPROVE_ADJUSTMENT_ENTRY_TO; ?><span style='color:blue'>{{touser}}</span> </label>									
								</div>									
							</div>
							<div class='row'>
								<div  class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
									<label><?php echo ADJUSTMENT_APPROVE_PARTY_TYPE; ?><span style='color:blue'>{{partytype}}</span> </label>									
								</div>	
								<div  class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
									<label><?php echo ADJUSTMENT_APPROVE_PARTY_Code; ?><span style='color:blue'>{{partycode}}</span> </label>									
								</div>									
							</div>
							<div class='row'>
								<div  class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
									<label><?php echo ADJUSTMENT_APPROVE_ADJUSTMENT_MODE; ?><span style='color:blue'>{{adjustmentmode}}</span> </label>									
								</div>	
								<div  class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
									<label><?php echo ADJUSTMENT_APPROVE_ADJUSTMENT_AMOUNT; ?><span style='color:blue'>{{adjustmentamount}}</span> </label>									
								</div>									
							</div>
							
							<div class='row'>
								
								<div  class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
									<label><?php echo ADJUSTMENT_APPROVE_ADJUSTMENT_DATE; ?><span style='color:blue'>{{adjustmentdate}}</span> </label>									
								</div>		
									<div  class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
									<label><?php echo ADJUSTMENT_APPROVE_STATUS; ?><span style='color:blue'>{{status}} </span></label>									
								</div>									
							</div>
							
							<div class='row form_col12_element'>								
								<div  class='col-xs-12 col-md-12 col-lg-12 col-sm-12 '>
									<label><?php echo ADJUSTMENT_APPROVE_AMOUNT; ?><span class='spanre'>*</span><span ng-show="AdjustmentApproveForm.approvedamount.$touched ||AdjustmentApproveForm.approvedamount.$dirty && AdjustmentApproveForm.approvedamount.$invalid">
									<span class = 'err' ng-show="AdjustmentApproveForm.approvedamount.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input  ng-model="approvedamount" type='text' id='approvedamount' name='approvedamount' required class='form-control'/>							
								</div>
							</div>	
							<div class='row form_col12_element'>
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
									<label><?php echo ADJUSTMENT_APPROVE_COMMENTS; ?></label>
									<textarea rows='4'  type='text' class='form-control'   name='comment' ng-model='comment' readonly="true"   />
								</div>
								
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
									<label><?php echo ADJUSTMENT_APPROVE_APPROVER_COMMENTS; ?><span class='spanre'>*</span><span ng-show="AdjustmentApproveDialogue.approveComment.$dirty && AdjustmentApproveDialogue.approveComment.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
									<textarea rows='4' placeholder='Please enter the comment' type='text' class='form-control'  name='approveComment' ng-model='approveComment' required />
								</div>
							</div>
							
							<div class='clearfix'></div>
						</div>
						</form>
					</div>				
					<div class='modal-footer' ng-hide='isLoader'>					
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-click='refresh()' id='Ok' ng-hide='isHideOk' ><?php echo ADJUSTMENT_APPROVE_APPROVE_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo ADJUSTMENT_APPROVE_APPROVE_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="AdjustmentApproveDialogue.$invalid" ng-click="AdjustmentApproveDialogue.$invalid=true;approve(id,partytype,partycode,adjustmenttype)" id="Approve"><?php echo ADJUSTMENT_APPROVE_APPROVE_BUTTON_APPROVE; ?></button>
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
	$("#AdjustmentApproveDialogue").on("click","#Ok",function() {
		window.location.reload();
		});
	$("#Refresh").click(function() {
		window.location.reload();
	});	
	
});
</script>