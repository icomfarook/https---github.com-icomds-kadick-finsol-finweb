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
<div ng-controller='adjViewCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!adjviw"><?php echo ADJUSTMENT_VIEW_HEADING1; ?></a></li>
			<li><a href="#!adjviw"><?php echo ADJUSTMENT_VIEW_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo ADJUSTMENT_VIEW_HEADING3; ?></span>
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
				<form name='adjustmentViewForm' action="adjustviewexcel.php" method='POST'>	
					<div class='row appcont'>
						<div class='row appcont' ng-init="creteria='BI'">
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='BI' ng-checked='true' type='radio' name='creteria' ng-model='creteria' /></label>
								<label><?php echo ADJUSTMENT_VIEW_ID; ?>
								<span ng-show="adjustmentViewForm.id.$dirty && adjustmentViewForm.id.$invalid">
								<span class = 'err' ng-show="adjustmentViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  numbers_only maxlength='12' ng-trim="false"  restrict-field="id" ng-disabled="creteria==='BS' || creteria==='BPD' || creteria==='BAD'" ng-model="id" type='text' id='Id' name='id' autofocus='true' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='BS' type='radio' name='creteria' ng-model='creteria' /></label>
								<label><?php echo ADJUSTMENT_VIEW_STATUS; ?>
								</label>
								<select ng-model='crestatus' ng-init='crestatus = "ALL"' ng-disabled="creteria==='BI' || creteria==='BPD' || creteria==='BAD'" class='form-control' name='crestatus' required>
									<option value='ALL'><?php echo ADJUSTMENT_VIEW_ALL; ?></option>
									<option value='E'><?php echo ADJUSTMENT_VIEW_ENTERED; ?></option>
									<option value='P'><?php echo ADJUSTMENT_VIEW_PENDING; ?></option>
									<option value='R'><?php echo ADJUSTMENT_VIEW_REJECTED; ?></option>
									<option value='A'><?php echo ADJUSTMENT_VIEW_APPROVED; ?></option>
									<option value='F'><?php echo ADJUSTMENT_VIEW_FAILED; ?></option>
								</select>
							</div>
							
							 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='BPD' type='radio' name='creteria' ng-model='creteria' /></label>
						    	<label><?php echo ADJUSTMENT_VIEW_ADJUSTMENT_DATE; ?>
								<span ng-show="adjustmentViewForm.adjustmentDate.$touched ||adjustmentViewForm.adjustmentDate.$dirty && adjustmentViewForm.adjustmentDate.$invalid">
								<span class = 'err' ng-show="adjustmentViewForm.adjustmentDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-disabled="creteria==='BI' || creteria==='BS' || creteria==='BAD'" ng-model="adjustmentDate" type='date' id='adjustmentDate' name='adjustmentDate' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='BAD' type='radio' name='creteria' ng-model='creteria' /></label>
								<label><?php echo ADJUSTMENT_VIEW_APPROVED_DATE; ?>
									<span ng-show="adjustmentViewForm.approvedDate.$touched ||adjustmentViewForm.approvedDate.$dirty && adjustmentViewForm.approvedDate.$invalid">
									<span class = 'err' ng-show="adjustmentViewForm.approvedDate.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input ng-disabled="creteria==='BI' || creteria==='BS' || creteria==='BPD'" ng-model="approvedDate" type='date' id='approvedDate' name='approvedDate' required class='form-control'/>
							</div>
						</div>	
						<div class='row appcont'>
							<div style='text-align: -webkit-center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='adjustmentEntryForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo ADJUSTMENT_VIEW_QUERY_BUTTON; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo ADJUSTMENT_VIEW_REFRESH_BUTTON; ?></button>
								<button type="button" class="btn btn-primary" ng-disabled = 'infoViewForm.$invalid' ng-click='print()' ng-hide='isHide'  id="Query">Print</button>
								<button type="submit" class="btn btn-primary"   id="excel"ng-hide='isHideexcel;'>Excel</button>
							</div>
						</div>
					<div class='row appcont'>
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo ADJUSTMENT_ENTRY_VIEW_ID; ?></th>
									<th><?php echo ADJUSTMENT_ENTRY_VIEW_PARTY_CODE; ?></th>
									<th><?php echo ADJUSTMENT_ENTRY_VIEW_PARTY_TYPE; ?></th>
									<th><?php echo ADJUSTMENT_ENTRY_VIEW_ADJUSTMENT_TYPE; ?></th>
									<th><?php echo ADJUSTMENT_ENTRY_VIEW_ADJUSTMENT_AMOUNT; ?></th>
									<th><?php echo ADJUSTMENT_ENTRY_VIEW_ADJUSTMENT_APPROVED_AMOUNT; ?></th>
									<th><?php echo ADJUSTMENT_ENTRY_VIEW_STATUS; ?></th>
									<th><?php echo ADJUSTMENT_ENTRY_VIEW_DETAIL; ?></th>
									<th>Comments</th>
								
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in adjustmentviews">
									<td>{{ x.id }}</td>
									<td>{{ x.code}}</td>
									<td>{{ x.type }}</td>
									<td>{{ x.adjtype }}</td>
									<td>{{ x.adjamount }}</td>
									<td>{{ x.adjappamount }}</td>
									<td>{{ x.status }}</td>
									<td><a id={{x.id}} class='ApplicationViewDialogue' ng-click='view(x.id)' data-toggle='modal' data-target='#AdjustmentViewDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
									</td>
									<td><a id={{x.id}} class='AdjustmentCommentDialogue' ng-click='commentview(x.id)' data-toggle='modal' data-target='#AdjustmentCommentDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/attach.png' /></button></a>
									</td>
									
								</tr>
								<tr ng-show="adjustmentviews.length==0">
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
	
		 <div id='AdjustmentCommentDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" style='width:50%'>
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Adjusment Comments  For  Id - {{id}}</h2>
					</div>			
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					<form action="" method="POST" name='AdjustmentCommentDialogue' id="AdjustmentCommentDialogue">
						<div id='ApplicationViewBody'  ng-hide='isLoader'>
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label>User Comments :  <span class='labspa'>{{comment}}</span></label>
							</div>
							<br />
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label>Approver Comments :  <span class='labspa'>{{acomment}}</span></label>
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
	
	
	 <div id='AdjustmentViewDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo ADJUSTMENT_VIEW_DETAIL_HEADING1; ?> - For # {{id}}</h2>
					</div>			
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					<form action="" method="POST" name='AdjustmentViewDialogue' id="AdjustmentViewDialogue">
						<div id='ApplicationViewBody'  ng-hide='isLoader'>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label># :  <span class='labspa'>{{id}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo ADJUSTMENT_VIEW_DETAIL_COUNTRY; ?> :  <span class='labspa'>{{country}}</span></label>
							</div>							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo ADJUSTMENT_VIEW_DETAIL_PARTY_TYPE; ?> :  <span class='labspa'>{{partyType}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo ADJUSTMENT_VIEW_DETAIL_PARTY_CODE; ?> :  <span class='labspa'>{{partyCode}}</span></label>
							</div>							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo ADJUSTMENT_VIEW_DETAIL_ADJUSTMENT_AMOUNT; ?> :  <span class='labspa'>{{adjustmentAmount}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo ADJUSTMENT_VIEW_DETAIL_ADJUSTMENT_APPROVED_AMOUNT; ?> :  <span class='labspa'>{{adjustmentApprovedAmount}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo ADJUSTMENT_VIEW_DETAIL_ADJUSTMENT_APPROVED_DATE; ?> :  <span class='labspa'>{{adjustmentApprovedDate}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo ADJUSTMENT_VIEW_DETAIL_REF_NO; ?> :  <span class='labspa'>{{adjustmentRefNo}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo ADJUSTMENT_VIEW_DETAIL_REF_DATE; ?> :  <span class='labspa'>{{adjustmentRefDate}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo ADJUSTMENT_VIEW_DETAIL_STATUS; ?> :  <span class='labspa'>{{adjustmentStatus}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo ADJUSTMENT_VIEW_DETAIL_COMMENT; ?> :  <span class='labspa'>{{comment}}</span></label>
							</div>							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo ADJUSTMENT_VIEW_DETAIL_APPROVER_COMMENT; ?> :  <span class='labspa'>{{acomment}}</span></label>
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
	
});
</script>