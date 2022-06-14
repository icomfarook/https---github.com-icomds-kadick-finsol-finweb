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
#AddFLEXI_RATE_AGENTDialogue .table > tbody > tr > td {
	border:none;
}
.form_col12_element {
	margin-top:1%;
}
#selUser  .select2-selection .select2-selection--single{
	height:30px !important;
}
</style>
<div ng-controller='flxRateCtrl' data-ng-init="fn_load(<?php echo "'".$partyType."',"."'".$partyCode."'" ?>)">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!ptyinf"><?php echo FLEXI_RATE_AGENT_MAIN_HEADING1; ?></a></li>
			<li><a href="#!ptyinf"><?php echo FLEXI_RATE_AGENT_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo FLEXI_RATE_AGENT_MAIN_HEADING3; ?></span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding"  data-backdrop="static" data-keyboard="false" >	
				<div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>																																	
				<form name='infoViewForm' method='POST'>	
					<div class='row appcont'>						
						 <?php if($profileId == 50) { ?>
							<div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>
									<label><?php echo FLEXI_RATE_AGENT_PARTY_CODE_CHAMPION ; ?><span class='spanre'>*</span>
									<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
									<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input  readonly = 'true' ng-disabled="creteria==='TP'" [(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
								</div>
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									<label><input value='TP' type='radio' name='creteria' ng-model='creteria' /></label>
									<label><?php echo FLEXI_RATE_AGENT_PARTY_CODE_AGENT; ?>	</label>
									<select  ng-model='topartyCode' ng-disabled="creteria==='SP'" class='form-control' name='topartyCode' required >
										<option value=''><?php echo FLEXI_RATE_AGENT_SELECT_PARTY_CODE_AGENT; ?></option>
										<option ng-repeat="info in infos" value="{{info.code}}">{{info.name}}</option>
									</select>										
								</div>
								
								 <div  class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
									<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='infoViewForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo FLEXI_RATE_AGENT_VIEW_QUERY_BUTTON; ?></button>
									<button type="button" class="btn btn-primary"   id="Refresh"><?php echo FLEXI_RATE_AGENT_VIEW_REFRESH_BUTTON; ?></button>
								</div>
							</div>	
								 <?php }  if($profileId == 51) {?>
									 <div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
										 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
											<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>
											<label><?php echo FLEXI_RATE_AGENT_PARTY_CODE_AGENT ; ?><span class='spanre'>*</span>
											<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
											<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
											<input  readonly = 'true'[(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
										</div>
										<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
											<label><input value='TP' type='radio' name='creteria' ng-model='creteria' /></label>
											<label><?php echo FLEXI_RATE_AGENT_PARTY_CODE_SUB_AGENT; ?>	</label>
											<select  ng-model='topartyCode' ng-disabled="creteria==='SP'" class='form-control' name='topartyCode' required >
												<option value=''><?php echo FLEXI_RATE_AGENT_SELECT_PARTY_CODE_SUB_AGENT; ?></option>
												<option ng-repeat="info in infos" value="{{info.code}}">{{info.name}}</option>
											</select>										
										</div>
										
										 <div  class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
											<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='query()' ng-hide='isHide'  id="Query"><?php echo FLEXI_RATE_AGENT_VIEW_QUERY_BUTTON; ?></button>
											<button type="button" class="btn btn-primary"   id="Refresh"><?php echo FLEXI_RATE_AGENT_VIEW_REFRESH_BUTTON; ?></button>
										</div>
									</div>	
								  <?php }  if($profileId == 52) { ?>
										<div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
										 <div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
											<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>
											<label><?php echo FLEXI_RATE_AGENT_PARTY_CODE_AGENT ; ?><span class='spanre'>*</span>
											<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
											<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
											<input  readonly = 'true'[(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
										</div>
										
								 <div  class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
									<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='infoViewForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo FLEXI_RATE_AGENT_VIEW_QUERY_BUTTON; ?></button>
									<button type="button" class="btn btn-primary"   id="Refresh"><?php echo FLEXI_RATE_AGENT_VIEW_REFRESH_BUTTON; ?></button>
								</div>
									</div>	
									
								  <?php }  if($profileId == 1 || $profileId == 10 || $profileId == 24 || $profileId == 22 || $profileId == 20  || $profileId == 26) {?>
									 <div class='row appcont'>
										 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
											
											<label><?php echo FLEXI_RATE_AGENT_PARTY_CODE_TYPE ; ?><span class='spanre'>*</span>
											<span ng-show="infoViewForm.partyType.$dirty && infoViewForm.partyType.$invalid">
											<span class = 'err' ng-show="infoViewForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></label>
											<select ng-change='partyload(this.partyType)' ng-model="partyType"  class='form-control' name = 'partyType' id='partytype' required>											
												<option value=""><?php echo FLEXI_RATE_AGENT_VIEW_SELECT_TYPE; ?></option>
												<option value='MA'><?php echo FLEXI_RATE_AGENT_VIEW_AGENT; ?></option>												
												<option value='SA'><?php echo FLEXI_RATE_AGENT_VIEW_SUB_AGENT; ?></option>
											
											</select>
											
										</div>
										<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>										
											<label><?php echo FLEXI_RATE_AGENT_PARTY_CODE; ?><span class='spanre'>*</span>
											<span ng-show="infoViewForm.partyCode.$dirty && infoViewForm.partyCode.$invalid">
											<span class = 'err' ng-show="infoViewForm.partyCode.$error.required"><?php echo REQUIRED;?></span></span></label>	
											<select  ng-model='partyCode'  id='selUser' class='form-control' name='partyCode' required >
											<option value=""><?php echo FLEXI_RATE_AGENT_VIEW_SELECT_CODE; ?></option>												
											<option ng-repeat="info in infos" value="{{info.code}}">{{info.code}} - {{info.name}}</option>
											</select>										
										</div>
									
										 <div style="margin-top: inherit;"  class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
											<button type="button" class="btn btn-primary" ng-disabled = 'infoViewForm.$invalid' ng-click='infoViewForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo FLEXI_RATE_AGENT_VIEW_QUERY_BUTTON; ?></button>
											<button type="button" class="btn btn-primary"   id="Refresh"><?php echo FLEXI_RATE_AGENT_VIEW_REFRESH_BUTTON; ?></button>
										</div>
									</div>	
								
								  <?php } ?>
								 
							</div>		
																	
					<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo FLEXI_RATE_AGENT_MAIN_TABLE_AGENT_CODE; ?></th>
									<th><?php echo FLEXI_RATE_AGENT_MAIN_TABLE_LOGIN_NAME; ?></th>
									<th><?php echo FLEXI_RATE_AGENT_MAIN_TABLE_FLEXI_RATE; ?></th>
									
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in infoss">
									<td>{{ x.agent }}</td>
									<td>{{ x.lname }}</td>
									<td ng-show="x.flexirate =='Y'"> 
										<a id={{x.code}} class='infoViewDialogue' ng-click='edit($index,x.agent,x.user_id)' data-toggle='modal' data-target='#infoViewDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/tick.png' /></button></a>
									</td>
								<td ng-show="x.flexirate =='N'"> 
										<a id={{x.code}} class='infoViewDialogue' data-target='#infoViewDialogue' data-toggle='modal' >
										<button class='icoimg'><img style='height:22px;width:22px' ng-click='edit($index,x.agent,x.user_id)' src='../common/images/error.png' /></button></a>
									</td>
									<td ng-show="x.flexirate =='H'"> 
										<a id={{x.code}} class='infoViewDialogue' data-target='#infoViewDialogue' data-toggle='modal' >
										<button class='icoimg'><img style='height:22px;width:22px' ng-click='edit($index,x.agent,x.user_id)' src='../common/images/error.png' /></button></a>
									</td>
									 
								</tr>
								<tr ng-show="infoss.length==0">
									<td style='text-align:left' colspan='4' >
										<?php echo NO_DATA_FOUND; ?>     
									</td>
								</tr>
							</tbody>
						</table>
						</form>
					</div>
				</div>
		</div>
		 <div id='infoViewDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false" >
		<div class="modal-dialog modal-sm">
			<form name='flexiRateForm' action='' method='POST'>
				<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h2 style='text-align:center'><?php echo FLEXI_RATE_AGENT_VIEW_HEADING1; ?>- {{outlet_name}}  <span ng-show='parenroutletname'>({{parenroutletname}})</span></h2>
			
					</div>				
						<div  style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
						<div id='flexirateagaentbody'>
							<div class='modal-body'>
									<div style="width: 26%;margin-left: 38%;" class='col-xs-6 col-md-12 col-lg-12 col-sm-12'>
									<label style='margin-left: inherit'>Flexi Rate</label>
										<select ng-model="flexirate" ng-init='Y' class='form-control' name = 'flexirate' id='flexirate' required >											
											<option value='Y'>Flexi Rating</option>
											<option value='N'>Fixed Rating</option>
											<option value='H'>Hybrid Rating</option>	
											
											
										</select>
								</div>
							</div>		
						</div>
						<br />
						
						<div class='modal-footer' style='margin:2%'>
							<div class='row appcont' style='text-align:center' ng-hide='isLoader'>						
								<button type='button' class='btn btn-primary'  id='Ok' ng-hide='isHideOk' >Ok</button>
								<button type="button" class="btn btn-primary" ng-click='flexiRateForm.$invalid=true;update(user_id)' ng-hide='isHide' ng-disabled = "flexiRateForm.$invalid"  id="Submit">Update</button>
								
							</div>
						</div>	
				</div>
			</form>
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
	$("#infoViewDialogue, #infoEditDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	$("#Refresh").click(function() {
		window.location.reload();
	});
	$("#EditFLEXI_RATE_AGENTDialogue, #AddFLEXI_RATE_AGENTDialogue").on("keypress",".sc", function (event) {
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