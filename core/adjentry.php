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
.bigdrop {
    width: 250px !important;
}
</style>
<div ng-controller='adjEntryCtrl'>
<div class="row" >
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!adjent"><?php echo ADJUSTMENT_ENTRY_HEADING1; ?></a></li>
			<li><a href="#!adjent"><?php echo ADJUSTMENT_ENTRY_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

	<div class="row">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo ADJUSTMENT_ENTRY_HEADING3; ?></span>
				</div>
				
				<div class="no-move"></div> 
			</div>
			
			<div  class="box-content" style='padding: 0px 10px !important;'>	
              <div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>			
				<form name='adjustmentEntryForm' ng-model='adjustmentEntryForm' id='adjustmentEntryForm' method='POST' action=''>	
				  <div class='row'>
					</div>
                    <div id='adjentryCreateBody' ng-hide='isLoader' data-backdrop="static" data-keyboard="false">
					  <div class='rowcontent'>
						<div class='row appcont' style='padding:0px'>
						    <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo ADJUSTMENT_ENTRY_COUNTRY; ?><span class='spanre'>*</span><span ng-show="adjustmentEntryForm.country.$touched ||adjustmentEntryForm.country.$dirty && adjustmentEntryForm.country.$invalid">
								<span class = 'err' ng-show="adjustmentEntryForm.country.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="country"  class='form-control' name = 'country' id='country' required>											
									<option value=""><?php echo ADJUSTMENT_ENTRY_SELECT_COUNTRY; ?></option>
									<option ng-repeat="country in countrys" value="{{country.id}}">{{country.description}}</option>
								</select>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo ADJUSTMENT_ENTRY_PARTY_TYPE; ?><span class='spanre'>*</span><span ng-show="adjustmentEntryForm.partytype.$touched ||adjustmentEntryForm.partytype.$dirty && adjustmentEntryForm.partytype.$invalid">
								<span class = 'err' ng-show="adjustmentEntryForm.partytype.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="partytype"  class='form-control' name = 'partytype' id='partytype' required>											
									<option value=""><?php echo ADJUSTMENT_ENTRY_SELECT_TYPE; ?></option>
									<option value='A'><?php echo ADJUSTMENT_ENTRY_AGENT; ?></option>
									<option value='C'><?php echo ADJUSTMENT_ENTRY_CHAMPION; ?></option>
									<option value='S'><?php echo ADJUSTMENT_ENTRY_SUB_AGENT; ?></option>
									<option value='P'><?php echo ADJUSTMENT_ENTRY_PERSONAL; ?></option>
								</select>
							</div>
							<div ng-show="partytype=='A'" class='col-lg-3 col-xs-12 col-sm-12 col-md-12 partytype'>
								<label><?php echo ADJUSTMENT_ENTRY_PARTY_CODE_AGENT; ?><span class='spanre'>*</span><span class='err' ng-show="adjustmentEntryForm.partycode.$error.required && adjustmentEntryForm.partycode.$invalid"><?php echo REQUIRED;?></span></label>
								<select  ng-model="partycode"  name='partycode' id='partycode'  class='form-control search'  required>
									<option value=''><?php echo ADJUSTMENT_ENTRY_PARTY_CODE_SELECT_AGENT; ?></option>
									<option ng-repeat="agent in agents" value="{{agent.code}}">{{agent.code}} - {{agent.name}}</option>
								</select>
							</div>
							<div ng-show="partytype=='C'" class='col-lg-3 col-xs-12 col-sm-12 col-md-12 partytype'>
								<label><?php echo ADJUSTMENT_ENTRY_PARTY_CODE_CHAMPION; ?><span class='spanre'>*</span><span class='err' ng-show="adjustmentEntryForm.partycode.$error.required && adjustmentEntryForm.partycode.$invalid"><?php echo REQUIRED;?></span></label>
								<select  ng-model="partycode"  name='partycode' id='partycode'  class='form-control' required>
									<option value=''><?php echo ADJUSTMENT_ENTRY_PARTY_CODE_SELECT_CHAMPION; ?></option>
									<option ng-repeat="champion in champions" value="{{champion.code}}">{{champion.code}} - {{champion.name}}</option>
								</select>
							</div>
							<div ng-show="partytype=='S'" class='col-lg-3 col-xs-12 col-sm-12 col-md-12 partytype'>
								<label><?php echo ADJUSTMENT_ENTRY_PARTY_CODE_SUB_AGENT; ?><span class='spanre'>*</span><span class='err' ng-show="adjustmentEntryForm.partycode.$error.required && adjustmentEntryForm.partycode.$invalid"><?php echo REQUIRED;?></span></label>
								<select  ng-model="partycode"  name='partycode' id='partycode'  class='form-control' required>
									<option value=''><?php echo ADJUSTMENT_ENTRY_PARTY_CODE_SELECT_SUB_AGENT; ?></option>
									<option ng-repeat="agent in subagents" value="{{agent.code}}">{{agent.code}} - {{agent.name}}</option>
								</select>
							</div>							
							<div ng-show="partytype=='P'" class='col-lg-3 col-xs-12 col-sm-12 col-md-12 partytype'>
								<label><?php echo ADJUSTMENT_ENTRY_PARTY_CODE_PERSONAL; ?><span class='spanre'>*</span><span class='err' ng-show="adjustmentEntryForm.partycode.$error.required && adjustmentEntryForm.partycode.$invalid"><?php echo REQUIRED;?></span></label>
								<select  ng-model="partycode"  name='partycode' id='partycode'  class='form-control' required>
									<option value=''><?php echo ADJUSTMENT_ENTRY_PARTY_CODE_SELECT_PERSONAL; ?></option>
									<option ng-repeat="personal in personals" value="{{personal.code}}">{{personal.code}} - {{personal.name}}</option>
								</select>
							</div>
						</div>
						<div class='row appcont'>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo ADJUSTMENT_ENTRY_ADJUSTMENT_TYPE; ?><span class='spanre'>*</span><span ng-show="adjustmentEntryForm.adjustmenttype.$touched ||adjustmentEntryForm.adjustmenttype.$dirty && adjustmentEntryForm.adjustmenttype.$invalid">
								<span class = 'err' ng-show="adjustmentEntryForm.adjustmenttype.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="adjustmenttype"  class='form-control' name = 'adjustmenttype' id='adjustmenttype' required>											
									<option value=""><?php echo ADJUSTMENT_ENTRY_SELECT_ADJUSTMENT_TYPE; ?></option>
									<option value='P'><?php echo ADJUSTMENT_ENTRY_POSITIVE; ?></option>
									<option value='N'><?php echo ADJUSTMENT_ENTRY_NEGATIVE; ?></option>
								</select>
							</div>
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo ADJUSTMENT_ENTRY_ADJUSTMENT_DATE; ?><span class='spanre'>*</span><span ng-show="adjustmentEntryForm.adjustmentdate.$touched ||adjustmentEntryForm.adjustmentdate.$dirty && adjustmentEntryForm.adjustmentdate.$invalid">
								<span class = 'err' ng-show="adjustmentEntryForm.adjustmentdate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="adjustmentdate" type='date' id='adjustmentdate'   name='adjustmentdate' required class='form-control'/>
							</div>
						
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo ADJUSTMENT_ENTRY_ADJUSTMENT_AMOUNT; ?><span class='spanre'>*</span><span ng-show="adjustmentEntryForm.adjustmentamount.$dirty && adjustmentEntryForm.adjustmentamount.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
								<input  ng-model="adjustmentamount"  numbers-only type='text' id='adjustmentamount' maxlength='16'  name='adjustmentamount' required class='form-control'/>
							</div>
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo ADJUSTMENT_ENTRY_REFERENCE_NUMBER; ?><span class='spanre'>*</span><span ng-show="adjustmentEntryForm.refno.$touched ||adjustmentEntryForm.refno.$dirty && adjustmentEntryForm.refno.$invalid">
								<span class = 'err' ng-show="adjustmentEntryForm.refno.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="refno" type='text' id='refno' maxlength='30'  name='refno' required class='form-control'/>
							</div>							
							</div>
							<div class='row appcont'>
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo ADJUSTMENT_ENTRY_REFERENCE_DATE; ?><span class='spanre'>*</span><span ng-show="adjustmentEntryForm.refdate.$touched ||adjustmentEntryForm.refdate.$dirty && adjustmentEntryForm.refdate.$invalid">
								<span class = 'err' ng-show="adjustmentEntryForm.refdate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="refdate" type='date' id='refdate'  name='refdate' required class='form-control'/>
							</div>
							
							

							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo ADJUSTMENT_ENTRY_COMMENTS; ?><span class='spanre'>*</span><span ng-show="adjustmentEntryForm.comment.$touched ||adjustmentEntryForm.comment.$dirty && adjustmentEntryForm.comment.$invalid">
								<span class = 'err' ng-show="adjustmentEntryForm.comment.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="comment" type='text' id='comment' maxlength='256'  name='comment' required class='form-control'/>
							</div>	
						</div>
						<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12' id='mes' style='text-align:center'>
							<span style='color:red;font-size:17px;'>{{resmsg}}</span>						
						</div>
						</div>
						
						</div>
						<div class='row appcont' style='text-align:center' ng-hide='isLoader'>						
							<button type='button' class='btn btn-primary'  id='Ok' ng-hide='isHideOk' ><?php echo ADJUSTMENT_ENTRY_BUTTON_OK; ?></button>
							<button type="button" class="btn btn-primary" ng-click='adjustmentEntryForm.$invalid=true;adjustmentry()' ng-hide='isHide' ng-disabled = "adjustmentEntryForm.$invalid"  id="Submit"><?php echo ADJUSTMENT_ENTRY_BUTTON_SUBMIT_PAYMENT; ?></button>
							<button type="reset" class="btn btn-primary"  ng-hide='isHideReset' id="Reset"><?php echo ADJUSTMENT_ENTRY_BUTTON_RESET; ?></button>
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
	$("#adjustmentEntryForm").on("click","#Ok",function() {
		window.location.reload();
		});
	$("#Reset").click(function() {
		$(".partytype, #mes").hide();
	});
  
	$(".search").select2();
	$(".search").select2({ width: '100%' });   
   	
});
</script>
