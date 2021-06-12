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
	$partyType = $_SESSION['party_type'];
	$profileId = $_SESSION['profile_id'];
	$partyCode = $_SESSION['party_code'];
	$agent_name	=   $_SESSION['party_name'];
	
?>
<style>
#AddINFODialogue .table > tbody > tr > td {
	border:none;
}
.form_col12_element {
	margin-top:1%;
}
#selUser  .select2-selection .select2-selection--single{
	height:30px !important;
}
</style>
<div ng-controller='TransRepAuditCtrl' data-ng-init="fn_load(<?php echo "'".$partyType."',"."'".$partyCode."'" ?>)">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!ptyjen"><?php echo JOUNRAL_ENTRY_HEADING1; ?></a></li>
			<li><a href="#!ptyjen">Transaction  Audit</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Transaction Audit</span>
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
				<form name='trrepaduitForm' action='tranreauditexcel.php' method='POST'>	
					<div class='row appcont'>						
						
									
								  <?php   if($profileId == 1 || $profileId == 10 || $profileId == 20 || $profileId == 22 || $profileId == 26) {?>
									 <div class='row appcont'>
										 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
											
											<label><?php echo JOUNRAL_ENTRY_MAIN_PARTY_TYPE ; ?><span class='spanre'>*</span>
											<span ng-show="trrepaduitForm.partyType.$dirty && trrepaduitForm.partyType.$invalid">
											<span class = 'err' ng-show="trrepaduitForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></label>
											<select ng-change='partyload(this.partyType)' ng-model="partyType"  class='form-control' name = 'partyType' id='partytype' required>											
												<option value=""><?php echo JOUNRAL_ENTRY_MAIN_SELECT_TYPE; ?></option>
												<option value='MA'><?php echo JOUNRAL_ENTRY_MAIN_AGENT; ?></option>
												<option value='C'><?php echo JOUNRAL_ENTRY_MAIN_CHAMPION; ?></option>
												<option value='SA'><?php echo JOUNRAL_ENTRY_MAIN_SUB_AGENT; ?></option>
												<option value='P'><?php echo JOUNRAL_ENTRY_MAIN_PERSONAL; ?></option>
											</select>
											
										</div>
										<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>										
											<label><?php echo JOUNRAL_ENTRY_MAIN_PARTY_CODE; ?><span class='spanre'>*</span>
											<span ng-show="trrepaduitForm.partyCode.$dirty && trrepaduitForm.partyCode.$invalid">
											<span class = 'err' ng-show="trrepaduitForm.partyCode.$error.required"><?php echo REQUIRED;?></span></span></label>	
											<select  ng-model='partyCode'  class='form-control'   id='selUser' name='partyCode' required >
											<option value=""><?php echo JOUNRAL_ENTRY_MAIN_SELECT_PARTY_CODE; ?></option>												
											<option ng-repeat="info in infos" value="{{info.code}}">{{info.code}} - {{info.name}}</option>
											</select>										
										</div>
									
										<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12' >
						    	<label><?php echo TSS_ACCOUNT_START_DATE; ?>
								<span ng-show="trrepaduitForm.startDate.$touched ||trrepaduitForm.startDate.$dirty && trrepaduitForm.startDate.$invalid">
								<span class = 'err' ng-show="trrepaduitForm.startDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="startDate" type='date' id='StartDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo TSS_ACCOUNT_END_DATE; ?>
									<span ng-show="trrepaduitForm.endDate.$touched ||trrepaduitForm.endDate.$dirty && trrepaduitForm.endDate.$invalid">
									<span class = 'err' ng-show="trrepaduitForm.endDate.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input  ng-model="endDate" type='date' id='EndDate' name='endDate' required class='form-control'/>
							</div>
																				 
										 <div class='clearfix'></div>
									</div>	
											 <div  style = 'text-align:Center' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
											<button type="button" class="btn btn-primary" ng-disabled = 'trrepaduitForm.$invalid' ng-click='trrepaduitForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo JOUNRAL_ENTRY_MAIN_BUTTON_QUERY; ?></button>
											<button type="button" class="btn btn-primary"   id="Refresh"><?php echo JOUNRAL_ENTRY_MAIN_BUTTON_REFRESH; ?></button>
										 <button type="submit" class="btn btn-primary"   id="excel"ng-hide='isHideexcel;'>Excel</button>
										</div>
								  <?php } ?>
								 
							</div>		
																	
					<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th>Wallet Audit Id</th>
									<th>Party Code</th>
									<th>Transaction Code</th>
									<th>Journal Amount</th>
									<th>Old Available Balance</th>
									<th>New Available Balance</th>
									<th>Date Time</th>
									<th>Details</th>
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in tranaudit">
									<td>{{ x.id }}</td>
									<td>{{ x.partyCode }}</td>
									<td>{{ x.trans_code }}</td>
									<td>{{ x.journal_amount }}</td>
									<td>{{ x.old_available_balance }}</td>
									<td>{{ x.new_available_balance }}</td>
									<td>{{ x.new_last_tx_date }}</td>
									<td>
									<a id={{x.code}} class='infoViewDialogue' ng-click='edit($index,x.partyCode,x.partyType,x.id)' data-toggle='modal' data-target='#TransactionAuditDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
									</td>
								</tr>
								<tr ng-show="tranaudit.length==0">
									<td style='text-align:center' colspan='8' >
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
	 <div id='TransactionAuditDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Transaction Audit Details - {{partyCode}}  <span ng-show='parenroutletname'>({{parenroutletname}})</span></h2>
		
				</div>				
					<div  style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
						<form action="" method="POST" name='editINFOForm' id="EditINFOForm">
						<div id='AuthBody'>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
							<label> Wallet Audit Id : <span style='color:blue'> {{id}}</span></label>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Party Code : <span style='color:blue'>{{partyCode}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Transaction Code : <span style='color:blue'>{{trans_code}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Description : <span style='color:blue'>{{description}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Journal  Amount : <span style='color:blue'>{{journal_amount}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Old Tx Amount : <span style='color:blue'>{{old_available_balance}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> New Tx Amount : <span style='color:blue'>{{new_available_balance}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Old Last Tx Number: <span style='color:blue'>{{old_last_tx_no}}</span></label>								
							</div>
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> New Last Tx Number : <span style='color:blue'>{{new_last_tx_no}}</span></label>								
							</div>
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Old Last Tx Amount : <span style='color:blue'>{{old_last_tx_amount}}</span></label>								
							</div>
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> New Last Tx Amount : <span style='color:blue'>{{new_last_tx_amount}}</span></label>								
							</div>	
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Old Last Tx Date : <span style='color:blue'>{{old_last_tx_date}}</span></label>								
							</div>
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> New Last Tx Date : <span style='color:blue'>{{new_last_tx_date}}</span></label>								
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
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
/* function AllTables(){
	TestTable1();
	TestTable2();
	TestTable3();
	LoadSelect2Script();
} */
$(document).ready(function() {
 // LoadDataTablesScripts(AllTables);
 // WinMove();
	$("#infoViewDialogue, #infoEditDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	$("#Refresh").click(function() {
		window.location.reload();
	});
	$("#EditINFODialogue, #AddINFODialogue").on("keypress",".sc", function (event) {
		    var regex = new RegExp("^[a-zA-Z0-9 \b:/.~!@#$*_-]+$");
 		    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
 		    if (!regex.test(key)) {
 		       event.preventDefault();
 		       return false;
 		    }
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