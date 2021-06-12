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
	$partytype = $_SESSION['party_type'];	
	$partycode = $_SESSION['party_code']
?>
<div ng-controller='payEntryCtrl'>
<div class="row" >
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!payent"><?php echo PAYMENT_ENTRY_HEADING1; ?></a></li>
			<li><a href="#!payent"><?php echo PAYMENT_ENTRY_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

	<div class="row">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo PAYMENT_ENTRY_HEADING3; ?></span>
				</div>
				
				<div class="no-move"></div> 
			</div>
			
			<div ng-app="" data-ng-init="fn_load(<?php echo "'".$partytype."',"."'".$partycode."'" ?>)" class="box-content" style='padding: 0px 10px !important;' data-backdrop="static" data-keyboard="false">	
			<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>
			<form name='paymentEntryForm'  ng-hide='isLoader' ng-model='paymentEntryForm' id='paymentEntryForm' method='POST' action=''>	
				<div id='PayentryCreateBody'  ng-hide='isLoader' ng-init="partycode='<?php echo $partycode; ?>';creteria='SP'">
					 <div class='rowcontent'>
					  	<div class='row appcont'>
						    <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYMENT_ENTRY_COUNTRY; ?><span class='spanre'>*</span><span ng-show="paymentEntryForm.country.$touched ||paymentEntryForm.country.$dirty && paymentEntryForm.country.$invalid">
								<span class = 'err' ng-show="paymentEntryForm.country.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="country"  class='form-control' name = 'country' id='country' required>											
									<option value=""><?php echo PAYMENT_ENTRY_SELECT_COUNTRY; ?></option>
									<option ng-repeat="country in countrys " value="{{country.id}}">{{country.description}}</option>
								</select>
							</div>
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYMENT_ENTRY_PAYMENT_TYPE; ?><span class='spanre'>*</span><span ng-show="paymentEntryForm.paymenttype.$touched ||paymentEntryForm.paymenttype.$dirty && paymentEntryForm.paymenttype.$invalid">
								<span class = 'err' ng-show="paymentEntryForm.paymenttype.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="paymenttype"  class='form-control' name = 'paymenttype' id='paymenttype' required>											
									<option value=""><?php echo PAYMENT_ENTRY_SELECT_PAYMENT_TYPE; ?></option>
									<option value='AC'><?php echo PAYMENT_ENTRY_ACCOUNT_TRANSFER; ?></option>
									<option value='CA'><?php echo PAYMENT_ENTRY_CASH; ?></option>
									<option value='CH'><?php echo PAYMENT_ENTRY_CHEQUE; ?></option>
									<option value='OT'><?php echo PAYMENT_ENTRY_OTHERS; ?></option>
								</select>
							</div>
							 <?php if($profileId == 50) { ?>
						
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>
									<label><?php echo INFO_PARTY_CODE_CHAMPION ; ?><span class='spanre'>*</span>
									<span ng-show="paymentEntryForm.id.$dirty && paymentEntryForm.id.$invalid">
									<span class = 'err' ng-show="paymentEntryForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input  readonly = 'true' ng-disabled="creteria==='TP'" [(ngModel)] ="partycode" value = '<?php echo $partycode; ?>' type='text' id='partycode' name='partycode' autofocus='true'  class='form-control'/>
								</div>
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									<label><input value='TP' type='radio' name='creteria' ng-model='creteria' /></label>
									<label><?php echo JOUNRAL_ENTRY_AGENT; ?>	</label>
									<select  ng-model='topartyCode' ng-disabled="creteria==='SP'" class='form-control' name='topartyCode' required >
										<option value=''><?php echo JOUNRAL_ENTRY_SELECT_AGENT; ?></option>
										<option ng-repeat="info in infos" value="{{info.code}}">{{info.name}}</option>
									</select>										
								</div>						
								
								
								 <?php }  if($profileId == 51) {?>
									
										 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
											<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>
											<label><?php echo INFO_PARTY_CODE_AGENT ; ?><span class='spanre'>*</span>
											<span ng-show="paymentEntryForm.id.$dirty && paymentEntryForm.id.$invalid">
											<span class = 'err' ng-show="paymentEntryForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
											<input  readonly = 'true'[(ngModel)] ="partycode" value = '<?php echo $partycode; ?>' type='text' id='partycode' name='partycode' autofocus='true'  class='form-control'/>
										</div>
										<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
											<label><input value='TP' type='radio' name='creteria' ng-model='creteria' /></label>
											<label><?php echo INFO_PARTY_CODE_SUB_AGENT; ?>	</label>
											<select  ng-model='topartycode' ng-disabled="creteria==='SP'" class='form-control' name='topartycode'  >
												<option value=''><?php echo INFO_SELECT_PARTY_CODE_SUB_AGENT; ?></option>
												<option ng-repeat="info in infos" value="{{info.code}}">{{info.name}}</option>
											</select>										
										</div>									
								  <?php }  if($profileId == 52) { ?>
										
										 <div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
											<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>
											<label><?php echo INFO_PARTY_CODE_AGENT ; ?><span class='spanre'>*</span>
											<span ng-show="paymentEntryForm.id.$dirty && paymentEntryForm.id.$invalid">
											<span class = 'err' ng-show="paymentEntryForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
											<input  readonly = 'true'[(ngModel)] ="partycode" value = '<?php echo $partycode; ?>' type='text' id='partycode' name='partycode' autofocus='true' required class='form-control'/>
										</div>
										
								
								  <?php }  if($profileId == 1 || $profileId == 10  || $profileId == 20 ||  $profileId == 22) {?>
									
										 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
											
											<label><?php echo INFO_PARTY_CODE_TYPE ; ?><span class='spanre'>*</span>
											<span ng-show="paymentEntryForm.partytype.$dirty && paymentEntryForm.partytype.$invalid">
											<span class = 'err' ng-show="paymentEntryForm.partytype.$error.required"><?php echo REQUIRED;?></span></span></label>
											<select ng-change='partyload(this.partytype)' ng-model="partytype"  class='form-control' name = 'partytype' id='partytype' required>											
												<option value=""><?php echo INFO_VIEW_SELECT_TYPE; ?></option>
												<option value='MA'><?php echo INFO_VIEW_AGENT; ?></option>
												<option value='C'><?php echo INFO_VIEW_CHAMPION; ?></option>
												<option value='SA'><?php echo INFO_VIEW_SUB_AGENT; ?></option>
												<option value='P'><?php echo INFO_VIEW_PERSONAL; ?></option>
											</select>
											
										</div>
										<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>										
											<label><?php echo INFO_PARTY_CODE; ?><span class='spanre'>*</span>
											<span ng-show="paymentEntryForm.partycode.$dirty && paymentEntryForm.partycode.$invalid">
											<span class = 'err' ng-show="paymentEntryForm.partycode.$error.required"><?php echo REQUIRED;?></span></span></label>	
											<select  ng-model='partycode' class='form-control search' name='partycode' required >
											<option value=""><?php echo INFO_VIEW_SELECT_CODE; ?></option>												
											<option ng-repeat="info in infos" value="{{info.code}}">{{info.code}} - {{info.name}}</option>
											</select>										
										</div>
									</div>
										 
								  <?php } ?>								 
							
							
							
						
						<div class='row appcont'>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo PAYMENT_ENTRY_BANK; ?><span class='spanre'>*</span><span ng-show="paymentEntryForm.bankaccount.$touched ||paymentEntryForm.bankaccount.$dirty && paymentEntryForm.bankaccount.$invalid">
								<span class = 'err' ng-show="paymentEntryForm.bankaccount.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-disabled="paymenttype=='CA'" ng-model="bankaccount"   class='form-control' name = 'bankaccount' id='bankaccount' >											
									<option value=""><?php echo PAYMENT_ENTRY_SELECT_BANK; ?></option>
									<option ng-repeat="bank in banks" value="{{bank.id}}">{{bank.name}}-{{bank.aname}}-{{bank.ano}}</option>
								</select>
								
							</div>
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYMENT_ENTRY_PAYMENT_DATE; ?><span class='spanre'>*</span><span ng-show="paymentEntryForm.paymentdate.$touched ||paymentEntryForm.paymentdate.$dirty && paymentEntryForm.paymentdate.$invalid">
								<span class = 'err' ng-show="paymentEntryForm.paymentdate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="paymentdate" type='date' id='paymentdate'   name='paymentdate' required class='form-control'/>
							</div>
						
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYMENT_ENTRY_PAYMENT_AMOUNT; ?><span class='spanre'>*</span><span ng-show="paymentEntryForm.paymentamount.$dirty && paymentEntryForm.paymentamount.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
								<input  ng-model="paymentamount"  numbers-only type='text' id='paymentamount' maxlength='16'  name='paymentamount' required class='form-control'/>
							</div>
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYMENT_ENTRY_REFERENCE_NUMBER; ?><span class='spanre'>*</span><span ng-show="paymentEntryForm.refno.$touched ||paymentEntryForm.refno.$dirty && paymentEntryForm.refno.$invalid">
								<span class = 'err' ng-show="paymentEntryForm.refno.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="refno" type='text' id='refno' maxlength='30'  name='refno' required class='form-control'/>
							</div>							
						</div>
						<div class='row appcont'>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYMENT_ENTRY_REFERENCE_DATE; ?><span class='spanre'>*</span><span ng-show="paymentEntryForm.refdate.$touched ||paymentEntryForm.refdate.$dirty && paymentEntryForm.refdate.$invalid">
								<span class = 'err' ng-show="paymentEntryForm.refdate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="refdate" type='date' id='refdate'  name='refdate' required class='form-control'/>
							</div>
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYMENT_ENTRY_CHEQUE_NUMBER; ?><span ng-show="paymentEntryForm.chequeno.$touched ||paymentEntryForm.chequeno.$dirty && paymentEntryForm.chequeno.$invalid">
								<span class = 'err' ng-show="paymentEntryForm.chequeno.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="chequeno" type='text' id='chequeno'  maxlength='20' name='chequeno'  class='form-control'/>
							</div>	

							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PAYMENT_ENTRY_COMMENTS; ?><span class='spanre'>*</span><span ng-show="paymentEntryForm.comment.$touched ||paymentEntryForm.comment.$dirty && paymentEntryForm.comment.$invalid">
								<span class = 'err' ng-show="paymentEntryForm.comment.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="comment" type='text' id='comment' maxlength='256'  name='comment' required class='form-control'/>
							</div>	
						</div>
						<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12' id='mes' style='text-align:center'>
							<span style='color:red;font-size:17px;'>{{resmsg}}</span>						
						</div>
						</div>
						</div>
					
						<div class='row appcont' style='text-align:center' ng-hide='isLoader'>						
							<button type='button' class='btn btn-primary'  id='Ok' ng-hide='isHideOk' ><?php echo PAYMENT_ENTRY_BUTTON_OK; ?></button>
							<button type="button" class="btn btn-primary" ng-click='paymentEntryForm.$invalid=true;paymentry()' ng-hide='isHide' ng-disabled = "paymentEntryForm.$invalid" id="Submit"><?php echo PAYMENT_ENTRY_BUTTON_SUBMIT_PAYMENT; ?></button>
							<button type="reset" class="btn btn-primary"  ng-hide='isHideReset' id="Reset"><?php echo PAYMENT_ENTRY_BUTTON_RESET; ?></button>
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
	//TestTable2();
	//TestTable3();
	//LoadSelect2Script();
}

$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	 // LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	//WinMove();
	
	
	$(".search").select2();
	
	$("#Ok").click(function() {
			
			window.location.reload();
	});
});
</script>
