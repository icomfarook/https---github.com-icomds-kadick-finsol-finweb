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
	$profileId = $_SESSION['profile_id'];
	$partyCode = $_SESSION['party_code'];
	$agent_name	=   $_SESSION['party_name'];
	$group_type	=   $_SESSION['group_type'];
	$username	=   $_SESSION['user_name'];
	$parent_code	=   $_SESSION['parent_code'];
	
?>
<style>
fieldset.scheduler-border {
    border: 1px groove #ddd !important;
	padding: 0 0.1em 2.0em 0.1em !important;
    margin: 0 0 0.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
}

legend.scheduler-border {
    font-size: 1.0em !important;
    font-weight: bold !important;
    text-align: left !important;
	border:none;
	width:100px;
}
legend {
	border-bottom:none;
}
.center {
	text-align:center;
}
.appcont {
    margin: 0.5% 1%;
}
.box {
	border:none;
}
.form-control {
    display: inline-block;
     padding: 6px 12px;
    font-size: 13px;
	
}
.table > tbody > tr > td {
	border-top:none !important;
}
.rowcontent {
padding:0px;
}
#selUser  .select2-selection .select2-selection--single{
	height:30px !important;
}

</style>
<div ng-controller='TransFundCtrl' data-ng-init="fn_load(<?php echo "'".$group_type."'" ?>)">
<div class="row" >
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!transfund"><?php echo PAYOUT_REQUEST_HEADING1; ?></a></li>
			<li><a href="#!transfund">Transfer Fund</a></li>
		</ol>
		
	</div>
</div>

	<div class="row">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Transfer Fund</span>
				</div>
				
				<div class="no-move"></div> 
			</div>
			
		<div class="box-content" style='padding: 0px 10px !important;'>		
			<form  name='payOutListForm'  ng-model='payOutListForm' id='payOutListForm' >			
			  <div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
				<div ng-init = 'creteria = "P"' id='PayentryCreateBody'  ng-hide='isLoader'>
					 <h3>Transfer Fund</h3>
					 
						<div class='row appcont' style='width:85%;margin:auto'>
					 <fieldset class='scheduler-border' ng-hide='isUpForm'>					
						<legend class='scheduler-border'><?php echo PAYOUT_REQUEST_SUB_TITLE; ?> </legend>
														
								<?php if($profileId == 51)	{ ?>
									<div  class='col-lg-12 col-xs-12 col-md-12 col-sm-12' style="width:28%;margin-left:35%">
								<label>&nbsp;<br /><span class='spanre'></span>
									<span ng-show="payOutListForm.partyCode.$dirty && payOutListForm.partyCode.$invalid">
									<span class = 'err' ng-show="payOutListForm.creteria.$error.required"></span></span></label>	
									<?php if($group_type == 'P') { ?>
									<label style='margin-right:3%'><input value='P'  ng-checked="true"  type='radio'  ng-init="partyload(this.creteria)" name='creteria' ng-model='creteria' />&nbsp;&nbsp;From Parent</label>	
									<?php } else { ?>
									<label><input value='C' ng-init="partyload(this.creteria)" type='radio'  ng-checked="true" name='creteria' ng-model='creteria' />&nbsp;From Child</label>	
									<?php } ?>
							</div>
								<?php }  else{?>
							
							<div  class='col-lg-12 col-xs-12 col-md-12 col-sm-12' style="width:28%;margin-left:35%">
								<label>&nbsp;<br /><span class='spanre'></span>
									<span ng-show="payOutListForm.partyCode.$dirty && payOutListForm.partyCode.$invalid">
									<span class = 'err' ng-show="payOutListForm.creteria.$error.required"></span></span></label>	
									
									<label style='margin-right:3%'><input value='P' ng-click="oncheck()" type='radio' name='creteria' ng-model='creteria' />&nbsp;&nbsp;From Parent</label>	
									
									<label><input value='C' ng-click="oncheck()" type='radio'  name='creteria' ng-model='creteria' />&nbsp;From Child</label>	
									
							</div>
								<?php } ?>
							</form>
					</fieldset >
					</div>
				<form ng-hide='ispayRequestForm'  ng-show="creteria == 'P'" name='payRequestForm'  ng-hide='isLoader' ng-model='payRequestForm' id='payRequestForm' method='POST' action=''>	
					 <div class='rowcontent' style='width: 50%; margin: auto;'>
					 <?php if($profileId == 51)	{ ?> 
					 <div class='row appcont' style='padding:4px 0px;'>	
					 <div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label>Parent Code
									<span ng-show="payRequestForm.id.$dirty && payRequestForm.id.$invalid">
									<span class = 'err' ng-show="payRequestForm.id.$error.required"></span></span></label>
									<input  readonly = 'true' ng-disabled="creteria==='P'" [(ngModel)] ="agentCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='agentCode' name='agentCode' autofocus='true' required class='form-control'/>
								</div>
								</div>
					 <?php }else {?>
						<div class='row appcont' style='padding:4px 0px;'>		
					
						  <div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label>Parent
									<span ng-show="payRequestForm.agentCode.$dirty && payRequestForm.agentCode.$invalid">
									<span class = 'err' ng-show="payRequestForm.agentCode.$error.required"></span></span></label>
									<select  ng-change="root(this.agentCode);"  ng-disabled='isInputDisabled' id='selUser' ng-model='agentCode'  class='form-control' name='agentCode' required >
									<option  style="text-align:center"; value=''>------Select-----</option>
									<option   ng-repeat="agent in childagents" value="{{agent.application_id}},{{agent.agent_code}}">  {{agent.agent_code}} - {{agent.agent_name}}</option>
									
									</select>										
						</div>					
						</div>
					 <?php } ?>
						
						<div class='row appcont' style='padding:4px 0px;'>		
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label>Parent Wallet
									<span ng-show="payRequestForm.parentwallet.$dirty && payRequestForm.parentwallet.$invalid">
									<span class = 'err' ng-show="payRequestForm.parentwallet.$error.required"><?php echo REQUIRED;?></span></span></label>						
								<input   ng-model='parentwallet' id="parentwallet"  readonly='true' class='form-control' name='parentwallet' />
							</div>
						</div>
						<?php if($profileId == 51)	{ ?> 
						<div class='row appcont' style='padding:4px 0px;'>		
						<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label>Child</label> 
									<select  ng-change='(this.agentCode)' ng-disabled='isInputDisabled' id='selUser' ng-model='childagentCode'  class='form-control' name='childagentCode' required >
									<option  style="text-align:center"; value=''>------Select-----</option>
									<option  ng-repeat="agents in parentagent" value="{{agents.agent_code}}">  {{agents.agent_code}} - {{agents.login_name}}</option>
									
									</select>										
						</div>	
						</div>	
						<?php } else { ?>
						<div class='row appcont' style='padding:4px 0px;'>		
						<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label>Child
									<span ng-show="payRequestForm.parentchildagentCode.$dirty && payRequestForm.parentchildagentCode.$invalid">
									<span class = 'err' ng-show="payRequestForm.parentchildagentCode.$error.required"><?php echo REQUIRED;?></span></span></label>
									<select  ng-change='(this.agentCode)'  id='selUser' ng-disabled="!agentCode" ng-model='parentchildagentCode'  class='form-control' name='parentchildagentCode' required >
									<option  style="text-align:center"; value=''>------Select-----</option>
									<option  ng-repeat="agents in childagent" value="{{agents.agent_code}}">  {{agents.agent_code}} - {{agents.login_name}}</option>
									
									</select>										
						</div>	
						</div>	
						<?php } ?>
										<div class='row appcont' style='padding:4px 0px;'>		
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label>Transfer Amount
									<span ng-show="payRequestForm.transamnt.$dirty && payRequestForm.transamnt.$invalid">
									<span class = 'err' ng-show="payRequestForm.transamnt.$error.required"><?php echo REQUIRED;?></span></span></label>						
								<input ng-model='transamnt'  required class='form-control' name='transamnt'/>
							</div>
						</div>								
							<div  style='text-align:center'>
								<button type='button' confirmed-click='payRequestForm.$invalid=true;payout()' ng-disabled="payRequestForm.$invalid"  ng-confirm-click="Are you sure, do you want to transfer fund from Parent to Child?"  class='btn btn-primary'  id='Payout'  >Submit</button>
								<button type="reset" class="btn btn-primary" id="Reset"><?php echo PAYOUT_REQUEST_BUTTON_RESET; ?></button>		
							</div>
						</div>
				 </form>
			<form ng-hide='CispayRequestForm'   ng-show="creteria == 'C'" name='payRequestFormChild'  ng-hide='isLoader' ng-model='payRequestFormChild' id='payRequestForm' method='POST' action=''>	
					 <div class='rowcontent' style='width: 50%; margin: auto;'>
					 <?php if($profileId == 51)	{ ?> 
						<div class='row appcont' style='padding:4px 0px;'>		
						<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label>Child</label> 
								<input  readonly = 'true' [(ngModel)] ="childagentCode" value = <?php echo "'".$partyCode. "-".$username.  "'" ?> type='text' id='childagentCode' name='childagentCode' autofocus='true' required class='form-control'/>									
						</div>	
						</div>	
						<?php } else { ?>
						<div class='row appcont' style='padding:4px 0px;'>						
						<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label>Child<span ng-show="payRequestFormChild.childagentCode.$dirty && payRequestFormChild.childagentCode.$invalid">
									<span class = 'err' ng-show="payRequestFormChild.childagentCode.$error.required"><?php echo REQUIRED;?></span></span></label>				
									<select  ng-change="partyload(this.childagentCode)" ng-disabled='isInputDisabled' id='selUser'  ng-model='childagentCode'  class='form-control' name='childagentCode' required >
									<option  style="text-align:center"; value=''>------Select-----</option>
									<option  ng-repeat="agents in childagent" value="{{agents.group_id}},{{agents.agent_code}}">  {{agents.agent_code}} - {{agents.login_name}}</option>
									
									</select>	
						
						</div>							
						</div>
						<?php  } ?>
						
						
						<div class='row appcont' style='padding:4px 0px;'>		
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label>Child Wallet<span ng-show="payRequestFormChild.parentwallet.$dirty && payRequestFormChild.parentwallet.$invalid">
									<span class = 'err' ng-show="payRequestFormChild.parentwallet.$error.required"><?php echo REQUIRED;?></span></span></label>
									<span class = 'err' ng-show="payRequestForm.parentwallet.$error.required"><?php echo REQUIRED;?></span></span></label>										
								<input   ng-model='parentwallet' required readonly='true' class='form-control' name='parentwallet'/>
							</div>
						</div>
						 <?php if($profileId == 51)	{ ?> 
					 <div class='row appcont' style='padding:4px 0px;'>	
					 <div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label>Parent Code<span class='spanre'>*</span>
									<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
									<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input  readonly = 'true' ng-disabled="creteria==='P'" [(ngModel)] ="agentCode" value = <?php echo "'".$parent_code. "-".$agent_name.  "'" ?> type='text' id='agentCode' name='agentCode' autofocus='true' required class='form-control'/>
								</div>
								</div>
					 <?php }else {?>
						<div class='row appcont' style='padding:4px 0px;'>		
						<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label>Parent<span ng-show="payRequestFormChild.parentagentCode.$dirty && payRequestFormChild.parentagentCode.$invalid">
									<span class = 'err' ng-show="payRequestFormChild.parentagentCode.$error.required"><?php echo REQUIRED;?></span></span></label>			
									<input  ng-disabled='true' id='selUser' ng-model='parentagentCode'  class='form-control' name='parentagentCode' required   ng-selected="true"   value="{{parentagentCode}}" />
															
						</div>	
						<div ng-hide="true" class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label>Parent</label> 
									<input  ng-disabled='true' id='selUser' ng-model='agent_code'  class='form-control' name='agentCode' required   ng-selected="true"   value="{{agent_code}}" />
															
						</div>	
						</div>
					 <?php } ?>						
										<div class='row appcont' style='padding:4px 0px;'>		
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label>Transfer Amount<span ng-show="payRequestFormChild.transamnt.$dirty && payRequestFormChild.transamnt.$invalid">
								<span class = 'err' ng-show="payRequestFormChild.transamnt.$error.required"><?php echo REQUIRED;?></span></span></label>										
								<input  ng-model='transamnt' required class='form-control' name='transamnt'/>
							</div>
						</div>								
							<div  style='text-align:center'>
								<button type='button'  confirmed-click='payRequestFormChild.$invalid=true;payout()' ng-confirm-click="Are you sure, do you want to transfer fund from Child to Parent ?" class='btn btn-primary' ng-disabled="payRequestFormChild.$invalid" id='Payout'  >Submit</button>
								<button type="reset" class="btn btn-primary" id="Reset"><?php echo PAYOUT_REQUEST_BUTTON_RESET; ?></button>		
							</div>
						</div>
				 </form>
				<div class='row appcont' ng-hide='isResDiv' style='height:100px;border: none !important;width: 50%;margin: auto;'>
					
					<div class='row appcont'>
						<h3><span style='color:blue'>{{msg}} : {{errorResponseDescription}}</span></h3>
					</div>
					<div class='row appcont' style='text-align:center'>
						<button type="button" class="btn btn-primary"  id="Ok"><?php echo PAYOUT_REQUEST_BUTTON_OK; ?></button>
					</div>
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
	//TestTable2();
	//TestTable3();
	//LoadSelect2Script(MakeSelect2);
}

$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	 // LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	//WinMove();
	
	
	$("#Reset").click(function() {
		$(".partytype, #mes").hide();
	});
	$("#Ok").click(function() {			
			window.location.reload();
	});
	 $("#selUser").select2();

  // Read selected option
  $('#but_read').click(function(){
    var username = $('#selUser option:selected').text();
    var userid = $('#selUser').val();

    $('#result').html("id : " + userid + ", name : " + username);

  });
  
  $("#Reset").click(function() {
		$('#selUser').select2('destroy');
		$('#selUser').val('').select2();
	});	
});
</script>
