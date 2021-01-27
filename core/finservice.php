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
<div ng-controller='finCtrl'>
<div class="row" >
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#"><?php echo FINANCE_SEVICE_ORDER_HEADING1; ?></a></li>
			<li><a href="#"><?php echo FINANCE_SEVICE_ORDER_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

	<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo FINANCE_SEVICE_ORDER_HEADING3; ?></span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
					<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content" style='padding: 0px 10px !important;'>			
				<form name='fianceServieOrdeForm' id='fianceServieOrdeForm' method='POST' action=''>	
                    <div id='AppentryCreateBody'>
					   
					  <div class='rowcontent'>
						<div class='row appcont' style='padding:0px'>
						
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo FINANCE_SEVICE_ORDER_PRODUCT; ?><span class='spanre'>*</span><span ng-show="fianceServieOrdeForm.product.$touched ||fianceServieOrdeForm.product.$dirty && fianceServieOrdeForm.product.$invalid">
								<span class = 'err' ng-show="fianceServieOrdeForm.product.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="product" class='form-control' name = 'product' id='product' required>											
									<option value=''><?php echo FINANCE_SEVICE_ORDER_SELECT_PRODUCT; ?></option>
									<option ng-repeat="product in productlist" value="{{product.code}}">{{product.name}}</option>
								</select>
							</div>
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo FINANCE_SEVICE_ORDER_REQUEST_AMOUNT; ?><span class='spanre'>*</span><span ng-show="fianceServieOrdeForm.reqamount.$touched ||fianceServieOrdeForm.reqamount.$dirty && fianceServieOrdeForm.reqamount.$invalid">
								<span class = 'err' ng-show="fianceServieOrdeForm.reqamount.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="reqamount"  ng-blur="getcharge()" type='text' id='RequestAmount' ng-maxlength='11' name='reqamount' required class='form-control'/>
							</div>							
						
						<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo FINANCE_SEVICE_ORDER_SERVICE_CHARGE; ?><span class='spanre'>*</span><span ng-show="fianceServieOrdeForm.appliertype.$touched ||fianceServieOrdeForm.appliertype.$dirty && fianceServieOrdeForm.appliertype.$invalid">
								<span class = 'err' ng-show="fianceServieOrdeForm.appliertype.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="sercharge" numbers-only readonly="true" type='text' id='Servicecharge' ng-maxlength="11" name='sercharge' required class='form-control'/>
							</div>
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo FINANCE_SEVICE_ORDER_PROCESSING_CHARGE; ?><span class='spanre'>*</span><span ng-show="fianceServieOrdeForm.appliertype.$touched ||fianceServieOrdeForm.appliertype.$dirty && fianceServieOrdeForm.appliertype.$invalid">
								<span class = 'err' ng-show="fianceServieOrdeForm.appliertype.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="procharge" numbers-only readonly="true" type='text' id='ProcessingCharge' ng-maxlength="11" name='procharge' required class='form-control'/>
							</div>
							
						
						</div>
						<div class='row appcont'>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo FINANCE_SEVICE_ORDER_OTHER_CHARGE; ?><span class='spanre'>*</span><span ng-show="fianceServieOrdeForm.appliertype.$touched ||fianceServieOrdeForm.appliertype.$dirty && fianceServieOrdeForm.appliertype.$invalid">
								<span class = 'err' ng-show="fianceServieOrdeForm.appliertype.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="othercharge" readonly="true" numbers-only type='text' id='OtherCharge' ng-maxlength="11" name='othercharge' required class='form-control'/>
							</div>
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo FINANCE_SEVICE_ORDER_TOTAL; ?><span class='spanre'>*</span><span ng-show="fianceServieOrdeForm.appliertype.$touched ||fianceServieOrdeForm.appliertype.$dirty && fianceServieOrdeForm.appliertype.$invalid">
								<span class = 'err' ng-show="fianceServieOrdeForm.appliertype.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="total" readonly="true" numbers-only type='text' id='total' ng-maxlength="11" name='total' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo FINANCE_SEVICE_ORDER_MOBILE; ?><span class='spanre'>*</span><span ng-show="fianceServieOrdeForm.appliertype.$touched ||fianceServieOrdeForm.appliertype.$dirty && fianceServieOrdeForm.appliertype.$invalid">
								<span class = 'err' ng-show="fianceServieOrdeForm.appliertype.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="mobile" numbers-only type='text' id='mobile' ng-maxlength="11" name='mobile' required class='form-control'/>
							</div>
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo FINANCE_SEVICE_ORDER_CUSTOMER; ?><span class='spanre'>*</span><span ng-show="fianceServieOrdeForm.appliertype.$touched ||fianceServieOrdeForm.appliertype.$dirty && fianceServieOrdeForm.appliertype.$invalid">
								<span class = 'err' ng-show="fianceServieOrdeForm.appliertype.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="customer" type='text' id='customer' name='customer' required class='form-control'/>
							</div>
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo FINANCE_SEVICE_ORDER_AUTHORIZATION; ?><span class='spanre'>*</span><span ng-show="fianceServieOrdeForm.appliertype.$touched ||fianceServieOrdeForm.appliertype.$dirty && fianceServieOrdeForm.appliertype.$invalid">
								<span class = 'err' ng-show="fianceServieOrdeForm.appliertype.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="authorization" type='text' id='authorization'  name='authorization' required class='form-control'/>
							</div>
							
						</div>
						
						<div class='row appcont'>										
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo FINANCE_SEVICE_ORDER_REFERENCE; ?><span class='spanre'>*</span><span ng-show="fianceServieOrdeForm.appliertype.$touched ||fianceServieOrdeForm.appliertype.$dirty && fianceServieOrdeForm.appliertype.$invalid">
								<span class = 'err' ng-show="fianceServieOrdeForm.appliertype.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="ref" type='text' id='ref'  name='ref' required class='form-control'/>
							</div>
								
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo FINANCE_SEVICE_ORDER_COMMENT; ?><span class='spanre'>*</span><span ng-show="fianceServieOrdeForm.state.$touched ||fianceServieOrdeForm.state.$dirty && fianceServieOrdeForm.state.$invalid">
								<span class = 'err' ng-show="fianceServieOrdeForm.state.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="comment" type='text' id='comment'  name='comment' required class='form-control'/>
							</div>								
						</div>
	
						</div>
						</div>
						<div class='row appcont' style='text-align:center'>
						<button type='button' class='btn btn-primary'  id='Ok' ng-hide='isHideOk' ><?php echo APPLICATION_ENTRY_BUTTON_OK; ?></button>
						<button type="button" class="btn btn-primary" ng-click='finorder(fincode)' ng-hide='isHide' ng-disabled = "fianceServieOrdeForm.$invalid"  id="Submit"><?php echo APPLICATION_ENTRY_BUTTON_SUBMIT_APPLICATION; ?></button>
						<button type="reset" class="btn btn-primary"  ng-hide='isHideReset' id="Reset"><?php echo APPLICATION_ENTRY_BUTTON_RESET; ?></button>
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
	$("#fianceServieOrdeForm").on("click","#Ok",function() {
		window.location.reload();
		});
	$("#Reset").click(function() {
		$(".parenttype").hide();
	});
});
</script>
