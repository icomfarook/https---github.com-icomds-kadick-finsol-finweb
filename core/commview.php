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
	//$partyType = "A";	
	//$partyCode = "AG0101";
	//$profileId = 1;
?>
<style>
#AddINFODialogue .table > tbody > tr > td {
	border:none;
}
#selUser  .select2-selection .select2-selection--single{
	height:30px !important;
}
</style>
<div ng-controller='commviewCtrl' data-ng-init="fn_load(<?php echo "'".$partyType."',"."'".$partyCode."'" ?>)" >
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!comviw"><?php echo COMM_VIEW_MAIN_HEADING1; ?></a></li>
			<li><a href="#!comviw"><?php echo COMM_VIEW_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo COMM_VIEW_MAIN_HEADING3; ?></span>
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
				<form name='commViewForm' method='POST'>									
					<div class='row appcont'>			
						<?php  if($profileId == 1 || $profileId == 10 || $profileId == 20 || $profileId == 22 || $profileId == 26) {?>
						 <div class='row appcont'>
							 <div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>								
								<label><?php echo COMM_VIEW_MAIN_PARTY_TYPE ; ?><span class='spanre'>*</span>
								<span ng-show="commViewForm.partyType.$dirty && commViewForm.partyType.$invalid">
								<span class = 'err' ng-show="commViewForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-change='partyload(this.partyType)' ng-model="partyType"  class='form-control' name = 'partyType' id='partytype' required>											
									<option value=""><?php echo COMM_VIEW_MAIN_SELECT_TYPE; ?></option>
									<option value='MA'><?php echo COMM_VIEW_MAIN_AGENT; ?></option>
									<option value='C'><?php echo COMM_VIEW_MAIN_CHAMPION; ?></option>
									<option value='SA'><?php echo COMM_VIEW_MAIN_SUB_AGENT; ?></option>
									<option value='P'><?php echo COMM_VIEW_MAIN_PERSONAL; ?></option>
								</select>
								
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>										
								<label><?php echo COMM_VIEW_MAIN_PARTY_CODE; ?><span class='spanre'>*</span>
								<span ng-show="commViewForm.partyCode.$dirty && commViewForm.partyCode.$invalid">
								<span class = 'err' ng-show="commViewForm.partyCode.$error.required"><?php echo REQUIRED;?></span></span></label>	
								<select  ng-model='partyCode' id='selUser' class='form-control' name='partyCode' required >
								<option value=""><?php echo COMM_VIEW_MAIN_SELECT_PARTY_CODE; ?></option>												
								<option ng-repeat="info in infos" value="{{info.code}}">{{info.code}} - {{info.name}}</option>
								</select>										
							</div>
							<?php }  if($profileId == 51)  { ?>
								<div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
								<div style='margin-left:333px;' class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
									<label><input  ng-hide='true'  value='SP' type='radio' name='creteria' ng-model='creteria' /></label>&nbsp;&nbsp;
									<label>Agent<span class='spanre'>*</span>
									<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
									<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input  readonly = 'true'  [(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
								</div>
								<!-- <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
									<label><input value='TP' ng-init='topartyCode = "ALL"' type='radio' name='creteria' ng-model='creteria' /></label>&nbsp;&nbsp;
									<label><?php echo INFO_PARTY_CODE_AGENT; ?>	</label>
									<select  ng-model='topartyCode' ng-disabled="creteria==='SP'" class='form-control' name='topartyCode' required >
										<option value='ALL'><?php echo INFO_SELECT_PARTY_CODE_AGENT; ?></option>
										<option ng-repeat="info in infos" value="{{info.code}}">{{info.name}}</option>
									</select>										
								</div> -->
							<?php } ?>				
							<?php   if($profileId == 50)  { ?> ?>
								<div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
								<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
									<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>&nbsp;&nbsp;
									<label>Agent<span class='spanre'>*</span>
									<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
									<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input  readonly = 'true' ng-disabled="creteria==='TP'" [(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
								</div>
								<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
									<label><input value='TP' ng-init='topartyCode = "ALL"' type='radio' name='creteria' ng-model='creteria' /></label>&nbsp;&nbsp;
									<label><?php echo INFO_PARTY_CODE_AGENT; ?>	</label>
									<select  ng-model='topartyCode' ng-disabled="creteria==='SP'" class='form-control' name='topartyCode' required >
										<option value='ALL'><?php echo INFO_SELECT_PARTY_CODE_AGENT; ?></option>
										<option ng-repeat="info in infos" value="{{info.code}}">{{info.name}}</option>
									</select>										
								</div>
							<?php } ?>													 
						</div>		
						<div  style = 'text-align:Center' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
							<button type="button" class="btn btn-primary" ng-disabled = 'commViewForm.$invalid' ng-click='commViewForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo COMM_VIEW_MAIN_BUTTON_QUERY; ?></button>
							<button type="button" class="btn btn-primary"   id="Refresh"><?php echo COMM_VIEW_MAIN_BUTTON_REFRESH; ?></button>
						</div>									
				</div>
												
					<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo WALLET_MAIN_TABLE_ID; ?></th>
									<th><?php echo COMM_VIEW_MAIN_CURRENT_BALANCE; ?></th>
									<th><?php echo COMM_VIEW_MAIN_LAST_TX_AMOUNT; ?></th>
									<th><?php echo COMM_VIEW_MAIN_LAST_TX_DATE; ?></th>
									<th> <?php echo COMM_VIEW_MAIN_ACTIVE; ?></th>
									<th><?php echo WALLET_MAIN_TABLE_DETAIL; ?></th>
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in commviews">
									<td>{{ x.code }}</td>
									<td>{{ x.curbal }}</td>
									<td>{{ x.ltamt }}</td>
									<td>{{ x.ltdate }}</td>
									<td>{{ x.active }}</td>
									<?php if($profileId == 1 || $profileId == 10 || $profileId == 11 || $profileId == 20 || $profileId == 22 || $profileId == 26 ) { ?>
										<td>
											<a class='commViewDialogue' ng-click='edit($index,x.code,x.ptype, creteria)' data-toggle='modal' data-target='#commViewDialogue'>
											<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
										</td>
									<?php } if($profileId == 50 || $profileId == 51 || $profileId == 52 || $profileId == 53) { ?>
										<td>
											<a class='commViewDialogue2' ng-click='edit($index,x.code,x.ptype, creteria)' data-toggle='modal' data-target='#commViewDialogue2'>
											<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
										</td>
									<?php } ?>
								</tr>
								<tr ng-show="commviews.length==0">
									<td style='text-align:left' colspan='9' >
										   <?php echo NO_DATA_FOUNT; ?>         
									</td>
								</tr>
							</tbody>
						</table>
							</form>
					</div>
				
			</div>
				 <div id='commViewDialogue2' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'> <?php echo COMM_VIEW_COMM_VIEW_DETAILS; ?> - {{code}} </h2>
					</div>	
					<div class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>					
					<div class='modal-body'>
					
						<div id='AuthBody'>
							
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_PARTY_CODE; ?> <span style='color:blue'>{{code}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_CREDIT_LIMIT; ?>  <span style='color:blue'>{{climit}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label> <?php echo COMM_VIEW_DIALOGE_DAILY_LIMIT; ?>  <span style='color:blue'>{{dlimit}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label> <?php echo COMM_VIEW_DIALOGE_ADVANCE_AMOUNT; ?>  <span style='color:blue'>{{advamt}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label> <?php echo COMM_VIEW_DIALOGE_AVAILABLE_BALANCE; ?>  <span style='color:blue'>{{avlbalance}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_CURRENT_BALANCE; ?> <span style='color:red'>{{curbal}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label> <?php echo COMM_VIEW_DIALOGE_MINIMUM_BALANCE; ?>  <span style='color:blue'>{{minbalance}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label> <?php echo COMM_VIEW_DIALOGE_PREVIOUS_CURRENT_BALANCE; ?>  <span style='color:blue'>{{precbal}}</span></label>								
							</div>
							
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_UNCLEAR_BALANCE; ?>  <span style='color:blue'>{{ucbal}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_LAST_TRANSACTION_NO; ?>  <span style='color:blue'>{{ltno}}</span></label>								
							</div>														
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_LAST_TRANSACTION_AMOUNT; ?> <span style='color:blue'>{{ltamt}}</span></label>								
							</div>							
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_LAST_TRANSACTION_DATE; ?>  <span style='color:blue'>{{ltdate}}</span></label>								
							</div>		
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_ACTICE; ?>  <span style='color:blue'>{{active}}</span></label>								
							</div>	
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_BLOCK_STATUS; ?> <span style='color:blue'>{{blkstatus}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_BLOCK_DATE; ?>  <span style='color:blue'>{{blkdate}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_BLOCK_REASON_ID; ?><span style='color:blue'>{{brenid}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_CREATE_USER; ?>  <span style='color:blue'>{{cuser}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_CREATE_TIME; ?> <span style='color:blue'>{{ctime}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_UPDATE_USER; ?>  <span style='color:blue'>{{uuser}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_UPDATE_TIME; ?> <span style='color:blue'>{{utime}}</span></label>								
							</div>
						</div>
					</div>				
					<div class='modal-footer'>
					</div>
			
			</div>
		</div>	
	</div>	
		</div>
	</div>

	 <div id='commViewDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo COMM_VIEW_COMM_VIEW_DETAILS; ?> - {{code}}</h2>
					</div>		
					<div class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>					
					<div class='modal-body'>
					
						<div id='AuthBody'>
							
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_PARTY_CODE; ?> <span style='color:blue'>{{code}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_CREDIT_LIMIT; ?>  <span style='color:blue'>{{rootclimit}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label> <?php echo COMM_VIEW_DIALOGE_DAILY_LIMIT; ?>  <span style='color:blue'>{{rootdlimit}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label> <?php echo COMM_VIEW_DIALOGE_ADVANCE_AMOUNT; ?>  <span style='color:blue'>{{rootadvamt}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label> <?php echo COMM_VIEW_DIALOGE_AVAILABLE_BALANCE; ?>  <span style='color:blue'>{{rootavlbalance}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_CURRENT_BALANCE; ?> <span style='color:red'>{{rootcurbal}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label> <?php echo COMM_VIEW_DIALOGE_MINIMUM_BALANCE; ?>  <span style='color:blue'>{{rootminbalance}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label> <?php echo COMM_VIEW_DIALOGE_PREVIOUS_CURRENT_BALANCE; ?>  <span style='color:blue'>{{rootprecbal}}</span></label>								
							</div>
							
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_UNCLEAR_BALANCE; ?>  <span style='color:blue'>{{rootucbal}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_LAST_TRANSACTION_NO; ?>  <span style='color:blue'>{{rootltno}}</span></label>								
							</div>														
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_LAST_TRANSACTION_AMOUNT; ?> <span style='color:blue'>{{rootltamt}}</span></label>								
							</div>							
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_LAST_TRANSACTION_DATE; ?>  <span style='color:blue'>{{rootltdate}}</span></label>								
							</div>		
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_ACTICE; ?>  <span style='color:blue'>{{rootactive}}</span></label>								
							</div>	
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_BLOCK_STATUS; ?> <span style='color:blue'>{{rootblkstatus}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_BLOCK_DATE; ?>  <span style='color:blue'>{{rootblkdate}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_BLOCK_REASON_ID; ?><span style='color:blue'>{{rootbrenid}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_CREATE_USER; ?>  <span style='color:blue'>{{rootcuser}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_CREATE_TIME; ?> <span style='color:blue'>{{rootctime}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_UPDATE_USER; ?>  <span style='color:blue'>{{rootuuser}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label><?php echo COMM_VIEW_DIALOGE_UPDATE_TIME; ?> <span style='color:blue'>{{rootutime}}</span></label>								
							</div>
						</div>
					</div>				
					<div class='modal-footer'>
					</div>
				
			</div>
		</div>	
	</div>	
	
</div>

<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	////TestTable1();
	//TestTable2();
	//TestTable3();
}
$(document).ready(function() {
  LoadDataTablesScripts(AllTables);
 // WinMove();
	$("#commViewDialogue, #infoEditDialogue").on("click","#Ok",function() {
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