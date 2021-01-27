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
label {
	font-size:12px;
}
.form-control {
    height: 30px;
}
#selUser  .select2-selection .select2-selection--single{
	height:30px !important;
}
</style>
<div ng-controller='rulValCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!rulval"><?php echo RULE_VALIDATOR_MAIN_HEADING1; ?></a></li>
			<li><a href="#!rulval"><?php echo RULE_VALIDATOR_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo RULE_VALIDATOR_MAIN_HEADING3; ?></span>
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
				<form name='rulvalForm' action="" method='POST' >	
					<div class='row appcont'>
						<div class='row appcont'>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo RULE_VALIDATOR_STATE; ?><span class='spanre'>*</span></span></label>
								<select ng-disabled='isInputDisabled' ng-model="state" ng-init="state='ALL';statechange('ALL')" ng-change='statechange(this.state)' class='form-control' name = 'state' id='state' >											
									<option value='ALL'>ALL</option>
									<option ng-repeat="state in states" value="{{state.id}}">{{state.name}}</option>
								</select>
							</div>	
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12 parenttype'>
								<label><?php echo RULE_VALIDATOR_PARTY_CODE_AGENT; ?><span class='spanre'>*</span><span class='err' ng-show="rulvalForm.agentcode.$error.required && rulvalForm.agentcode.$invalid"><?php echo REQUIRED;?></span></label>
								<select ng-disabled = 'isSelectDisabled' ng-init="agentcode='ALL'"  ng-model="agentcode"  required name='agentcode'  id='selUser'  class='form-control'  >
									<option value='ALL'><?php echo RULE_VALIDATOR_PARTY_CODE_SELECT_AGENT; ?></option>
									<option ng-repeat="agent in agents" value="{{agent.id}}">{{agent.code}} - {{agent.name}}</option>
								</select>
							</div>
							
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo RULE_VALIDATOR_LOCAL_GOVERMENT; ?><span class='spanre'>*</span><span ng-show="rulvalForm.localgovernment.$touched ||rulvalForm.localgovernment.$dirty && rulvalForm.localgovernment.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="rulvalForm.localgovernment.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-disabled='isInputDisabled'  ng-init="localgovernment='ALL'"  ng-model="localgovernment" ng-init="ALL"  class='form-control' name = 'localgovernment' id='LocalGoverment' required>											
									<option value='ALL'>ALL</option>
									<option ng-repeat="localgvt in localgvts" value="{{localgvt.id}}">{{localgvt.name}}</option>
								</select>
							</div>
						</div>
						<div class='row appcont'>
							
							<div class='col-xs-3 col-md-12 col-lg-2 col-sm-12'>
								<label> <?php echo RULE_VALIDATOR_EDIT_SERVICE_FEATURE; ?><span class='spanre'>*</span><span ng-show="rulvalForm.serfea.$touched ||rulvalForm.serfea.$dirty && rulvalForm.serfea.$invalid">
								<span class = 'err' ng-show="rulvalForm.serfea.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="serfea" class='form-control' name = 'serfea' id='serfea' required>											
									<option value=''><?php echo RULE_VALIDATOR_SER_CHRG_FEAT_SELECT; ?></option>
									<option ng-repeat="ser in servfeas" value="{{ser.id}}">{{ser.name}}</option>
								</select>
							</div>
							<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12'>
								<label> <?php echo RULE_VALIDATOR_PARTNER; ?><span class='spanre'>*</span><span ng-show="rulvalForm.partner.$touched ||rulvalForm.partner.$dirty && rulvalForm.partner.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="rulvalForm.partner.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-disabled='isInputDisabled'   ng-model="partner" class='form-control' name = 'partner' id='partner' required>											
									<option value=''>--Select Partner--</option>
									<option ng-repeat="partner in partnerlist" value="{{partner.id}}">{{partner.name}}</option>
								</select>
							</div>
							<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo RULE_VALIDATOR_TRANS_TYPE; ?><span class='spanre'>*</span><span ng-show="rulvalForm.trType.$touched ||rulvalForm.trType.$dirty && rulvalForm.trType.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="rulvalForm.trType.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-disabled='isInputDisabled'   ng-model="trType" class='form-control' name = 'trType' id='trType' required>											
									<option value=''><?php echo RULE_VALIDATOR_TR_TYPE_SELECT; ?></option>
									<option value='I'>Internal</option>
									<option value='E'>External</option>
									<option value='F'>Flexi</option>
								</select>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-3 col-sm-12'>
								<label><?php echo RULE_VALIDATOR_SER_CHRG_GRP; ?><span ng-show=" rulvalForm.grpname.$touched || rulvalForm.grpname.$dirty &&  rulvalForm.grpname.$invalid">
								</span></label>
								<select ng-model="grpname"   class='form-control' name = 'grpname' id='grpname' required >											
									<option value=''><?php echo RULE_VALIDATOR_SER_CHRG_GRP_SELECT; ?></option>
									<option ng-repeat="ser in serchargrps" value="{{ser.id}}">{{ser.charge_group_name}}</option>
								</select>
							</div>
							
							<div class='col-xs-12 col-md-12 col-lg-3 col-sm-12'>
								 <label><?php echo RULE_VALIDATOR_CASH_IN_REQUEST_AMOUNT; ?><span class='spanre'>*</span><span ng-show="rulvalForm.reqamount.$dirty && rulvalForm.reqamount.$invalid"><span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
								 <input  numbers-only type='text' name='reqamount' required ng-model='reqamount' ng-trim="false" restrict-field="reqamount" maxlength='11' id='RequestAmount' class='form-control'/>
						   </div>
						  
						</div>
						<div class='row appcont' style='text-align:center'>
							<div style='text-align: -webkit-center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled = 'rulvalForm.$invalid' ng-click='query()' ng-hide='isHide'  id="Query"><?php echo RULE_VALIDATOR_MAIN_QUERY_BUTTON; ?></button>
								<button type="button" ng-click='reset()'  class="btn btn-primary"   id="Reset"><?php echo RULE_VALIDATOR_MAIN_RESET_BUTTON; ?></button>
								
							</div>
						</div>
				</form>
				<div id='divConDiv' ng-show = "isResDiv" class='row appcont' style='text-align:center'>
				</div>
				
		
			</div>
		</div>
	</div>
	

</div>
</div>
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings

$(document).ready(function() {
	$("#Query").click(function() {				
		LoadDataTablesScripts(AllTables);
		
	});
	  $('.modal-content').on('hidden', function() {
    clear()
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