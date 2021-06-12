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
fieldset.scheduler-border {
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
}

legend.scheduler-border {
    font-size: 1.2em !important;
    font-weight: bold !important;
    text-align: left !important;
	border:none;
	width:0px;
}

legend {
	border-bottom:none;
}
.appcont {
	margin: 1% 1% !important;
}
</style>
<div ng-controller='finCashInCtrl'>
<div class="row" >
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#"><?php echo FINANCE_SEVICE_ORDER_CASH_IN_HEADING1; ?></a></li>
			<li><a href="#"><?php echo FINANCE_SEVICE_ORDER_CASH_IN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

	<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo FINANCE_SEVICE_ORDER_CASH_IN_HEADING3; ?></span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
					<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content" style='padding: 0px 10px !important;'>	
				 <div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div style='text-align:center' class="loading-spiner"><img style='width:20%;text-align:center' align="middle" src="../common/img/gif2.gif" /></div></div>
                    <div id='cinBody' ng-hide='isMainDiv'>
					  <div class='rowcontent' style='padding:0px'>
						<div class='row appcont' style='padding:0px'>
						   <fieldset class='scheduler-border' style='padding-bottom:0px !important'>						  
							<form name='cashInForm' id='cashInForm' method='POST' action=''>
								<legend class='scheduler-border'></legend>
								<div class='row appcont' style='padding:0px'>
									<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12' ng-init = "product='1'" >
										<label><?php echo FINANCE_SEVICE_ORDER_CASH_IN_PRODUCT; ?><span class='spanre'>*</span><span id='vali' ng-show="cashInForm.product.$touched ||cashInForm.product.$dirty && cashInForm.product.$invalid">
										<span class = 'err' ng-show="cashInForm.product.$error.required"><?php echo REQUIRED;?></span></span></label>
										<select ng-model="product" class='form-control' name = 'product' id='product' required>											
											<option ng-repeat="x in finser" value="{{x.id}}">{{x.name}}</option>							
										</select>
									</div>
								</div>

								<div class='row appcont' style='padding:0px'>
									<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12' >
										<label><?php echo FINANCE_SEVICE_ORDER_CASH_IN_BANK; ?><span class='spanre'>*</span><span id='vali' ng-show="cashInForm.bank.$touched ||cashInForm.bank.$dirty && cashInForm.bank.$invalid">
										<span class = 'err' ng-show="cashInForm.bank.$error.required"><?php echo REQUIRED;?></span></span></label>
										<select ng-change='changebank(this.bank)' ng-model="bank" class='form-control' name='bank' id='bank' required>											
											<option value=''><?php echo FINANCE_SEVICE_ORDER_CASH_IN_SELECT_BANK; ?></option>
											<option ng-repeat="bank in banks" lab='{{bank.name}}' value="{{bank.id}}">{{bank.name}}</option>
										</select>
									</div>
								
									<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12' >
										<label><?php echo FINANCE_SEVICE_ORDER_CASH_IN_PARTNER; ?><span class='spanre'>*</span><span id='vali' ng-show="cashInForm.partner.$touched ||cashInForm.partner.$dirty && cashInForm.partner.$invalid">
										<span class = 'err' ng-show="cashInForm.partnername.$error.required"><?php echo REQUIRED;?></span></span></label>
										<!-- <select ng-model="partner" class='form-control' name='partner' id='partner' required>											
											<option value=""> <?php //echo FINANCE_SEVICE_ORDER_CASH_IN_SELECT_PARTNER; ?></option>
											<option ng-repeat="partner in partners" lab='{{partner.name}}' value="{{partner.id}}">{{partner.name}}</option>
										</select> -->
										<input type='text' id='' ng-model='partnername' name='partnername' class='form-control' disabled />
									</div>
									<input  ng-model="partnerid" type='hidden' id='partnerid'/>
									<input  ng-model="sedeco" type='hidden' id='sedeco'/>
									<input  ng-model="seconf" type='hidden' id='seconf'/>
								</div>
								
								<div class='row appcont' style='padding:0px'>
									<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
										<label><?php echo FINANCE_SEVICE_ORDER_CASH_IN_MOBILE; ?><span class='spanre'>*</span><span id='vali' ng-show="cashInForm.mobile.$dirty && cashInForm.mobile.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span><span style="color:Red" ng-show="cashInForm.mobile.$dirty && cashInForm.mobile.$error.minlength"> <?php echo MIN_11_NUMBERS_REQUIRED; ?> </span></label>
										<input numbers-only ng-model="mobile" ng-init='mobile=0' ng-minlength="11" maxlength='11' type='text' id='Mobile'  name='mobile' required class='form-control'/>
									</div>
									<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
										<label><?php echo FINANCE_SEVICE_ORDER_CASH_IN_NAME; ?><span class='spanre'>*</span><span id='vali' ng-show="cashInForm.name.$touched ||cashInForm.name.$dirty && cashInForm.name.$invalid">
										<span class = 'err' ng-show="cashInForm.name.$error.required"><?php echo REQUIRED;?></span></span></label>
										<input   ng-model="name" type='text' id='Name' maxlength='20' name='name' required class='form-control'/>
									</div>
								</div>
								<div class='row appcont' style='padding:0px'>
									<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
									 <label><?php echo FINANCE_SEVICE_ORDER_CASH_IN_REQUEST_AMOUNT; ?><span class='spanre'>*</span><span ng-show="cashInForm.reqamount.$dirty && cashInForm.reqamount.$invalid"><span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
									 <input  numbers-only type='text' name='reqamount' required ng-model='reqamount' ng-trim="false" restrict-field="reqamount" maxlength='11' id='RequestAmount' class='form-control'/>
									   </div>
								   </div>
										
									<div class='row appcont' style='padding:0px'>
									<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
										<label><?php echo FINANCE_SEVICE_ORDER_CASH_IN_COMMENT; ?><span class='spanre'>*</span><span id='vali' ng-show="cashInForm.comment.$touched ||cashInForm.comment.$dirty && cashInForm.comment.$invalid">
										<span class = 'err' ng-show="cashInForm.comment.$error.required"><?php echo REQUIRED;?></span></span></label>
										<input ng-model="comment" type='text' id='comment' maxlength='150' name='comment' required class='form-control'/>
									</div>	
								</div>	
								<div class='row appcont' style='padding:0px'>									
									<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12' style='text-align:center'>								
										<button type="button" class="btn btn-primary" ng-click='calculate(product, partner, reqamount)' ng-hide='isHide'  ng-disabled = "cashInForm.$invalid"  id="Submit"><?php echo FINANCE_SEVICE_ORDER_CASH_IN_BUTTON_CALCULATE; ?></button>
										<button type='button' class='btn btn-primary' ng-click='reset()' id='Reset'  ><?php echo FINANCE_SEVICE_ORDER_CASH_IN_BUTTON_RESET ; ?></button>
									</div>
								</div>
							</form>
						</fieldset>
					<form name='fianceServieOrdeForm' ng-hide='fianceServieOrdeForm' id='fianceServieOrdeForm' method='POST' action=''>							
						<fieldset class='scheduler-border'>
							<legend class='scheduler-border'></legend>
							<div class='row appcont' style='padding:0px'>
								<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
									<label><?php echo FINANCE_SEVICE_ORDER_CASH_IN_AMS_CHARGE; ?><span class='spanre'>*</span><span ng-show="fianceServieOrdeForm.amscharge.$touched ||fianceServieOrdeForm.amscharge.$dirty && fianceServieOrdeForm.amscharge.$invalid">
									<span class = 'err' ng-show="fianceServieOrdeForm.amscharge.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input readonly='true' numbers-only ng-model="amscharge" type='text' id='RequestAmount' ng-maxlength='11' name='amscharge' required class='form-control'/>
								</div>
								
								<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
									<label><?php echo FINANCE_SEVICE_ORDER_CASH_IN_PARTNER_CHARGE; ?><span class='spanre'>*</span><span ng-show="fianceServieOrdeForm.parcharge.$touched ||fianceServieOrdeForm.parcharge.$dirty && fianceServieOrdeForm.parcharge.$invalid">
									<span class = 'err' ng-show="fianceServieOrdeForm.parcharge.$error.required"><?php echo REQUIRED;?></span></span></label><button type = 'button' style='float:right' class='icoimg'><img style='height:22px;width:22px;'  data-toggle='modal' data-target='#detailcost' src='../common/images/edit.png' /></button>
									<input readonly='true' numbers-only ng-model="parcharge" type='text' id='RequestAmount' ng-maxlength='11' name='parcharge' required class='form-control'/>
								</div>
							</div>
							<div class='row appcont' style='padding:0px'>
								<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
									<label><?php echo FINANCE_SEVICE_ORDER_CASH_IN_OTHER_CHARGE; ?><span class='spanre'>*</span><span ng-show="fianceServieOrdeForm.othcharge.$touched ||fianceServieOrdeForm.othcharge.$dirty && fianceServieOrdeForm.othcharge.$invalid">
									<span class = 'err' ng-show="fianceServieOrdeForm.othcharge.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input readonly='true' numbers-only ng-model="othcharge" type='text' id='RequestAmount' ng-maxlength='11' name='othcharge' required class='form-control'/>
								</div>
								<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
									<label><?php echo FINANCE_SEVICE_ORDER_CASH_IN_TOTAL_CHARGE; ?><span class='spanre'>*</span><span ng-show="fianceServieOrdeForm.totalcharge.$touched ||fianceServieOrdeForm.totalcharge.$dirty && fianceServieOrdeForm.totalcharge.$invalid">
									<span class = 'err' ng-show="fianceServieOrdeForm.totalcharge.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input readonly='true' numbers-only ng-model="totalcharge" type='text' id='RequestAmount' ng-maxlength='11' name='totalcharge' required class='form-control'/>
								</div>
							</div>
							<div class='row appcont' style='padding:0px'>
								<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
									<label><?php echo FINANCE_SEVICE_ORDER_CASH_IN_TOTAL; ?><span class='spanre'>*</span><span ng-show="fianceServieOrdeForm.total.$touched ||fianceServieOrdeForm.total.$dirty && fianceServieOrdeForm.total.$invalid">
									<span class = 'err' ng-show="fianceServieOrdeForm.total.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input readonly='true' numbers-only ng-model="total" type='text' id='RequestAmount' ng-maxlength='11' name='total' required class='form-control'/>
								</div>
							</div>
							<div class='row appcont' style=''>									
								<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12' style='text-align:center;margin-top: 1%;'>								
									<button type="button" class="btn btn-primary"  ng-click='payment' data-toggle='modal' data-target='#PayDialogue' ng-hide='isHidePayment' ng-disabled = "payDisable"  id="Payment"><?php echo FINANCE_SEVICE_ORDER_CASH_IN_BUTTON_PAYMENT; ?></button>
									<button type="button" class="btn btn-primary"  ng-hide='isHidePayment' ng-disabled = "payRefresh"  id="Refresh"><?php echo FINANCE_SEVICE_ORDER_CASH_IN_BUTTON_REFRESH; ?></button>
								</div>
							</div>
						</fieldset>						
						</form>	
					</div>
				</div>			
			</div>		
		</div>
	</div>
	
	<div id='detailcost' class='modal' role='dialog'>
		 <div class="modal-dialog modal-md">
			<div class="modal-content">				
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3><?php echo FINANCE_SEVICE_ORDER_CASH_IN_SERVICE_CHARGE_SPLIT; ?></h3>						
				</div>				 
				<div class='modal-body '>
				<div id='tableres'></div></div>
				<div class='modal-footer'>						
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo FINANCE_SEVICE_ORDER_CASH_IN_BUTTON_CANCEL; ?></button>
				</div>				
			</div>
		</div>
	</div>
		
	<div id='PayDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">			
				<div class="modal-header">						
					<h3 class="text-center"><?php echo FINANCE_SEVICE_ORDER_CASH_IN_PAYMENT_DETAILS; ?><button type="button" ng-hide='isCloTime' class="close" data-dismiss="modal">&times;</button></h3>						
				</div>								  
				<div class='modal-body'>
					<div class="loading-spiner-holder" style='text-align:center' data-loading1 ><div style='text-align:center' class="loading-spiner"><img style='width:20%;text-align:center' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div id='payBody'>	
						<div id='servFetConfEditBody'  ng-hide='isLoader'>						  
							<div class="row">
								<div class="col-xs-12 col-md-12 ">
									<div class="panel panel-default">											
										<div class="panel-body">
										 <ul class="nav nav-pills">
											<li class='active'><a data-toggle="pill" data-target="#account" ><?php echo FINANCE_SEVICE_ORDER_CASH_IN_ACCOUNT; ?></a></li>
											<li><a data-toggle="pill" data-target="#card" ><?php echo FINANCE_SEVICE_ORDER_CASH_IN_CARD; ?></a></li>																					
										 </ul>
										  <div class="tab-content">
											 <div id="account" class="tab-pane fade in active">
												<form role='form' name='serfetconfForm' id='serfetconfForm' method='POST' action=''>
													<h3>{{bankname}}</h3>
													<div class="row">
														<div class="col-xs-12">
															<div class="form-group">
																<label><?php echo FINANCE_SEVICE_ORDER_CASH_IN_ACCOUNT_NO; ?><span style='color:red;font-size:14px;padding-left: 2px;'>*</span></label>
																<div class="input-group">
																	<input maxlength='12' type="text" required  ng-model='accountno' name='accountno'  class="form-control" placeholder='<?php echo FINANCE_SEVICE_ORDER_CASH_IN_PLACE_HOLDER_VALID_ACC_NO; ?>' numbers-only  />
																	<span class="input-group-addon"><span class="fa fa-credit-card"></span></span>
																</div>
															</div>
														</div>
													</div>	
													<div class="row">
														<div class="col-xs-12">
															<div class="form-group">
																<label><?php echo FINANCE_SEVICE_ORDER_CASH_IN_RE_ACCOUNT_NO; ?><span style='color:red;font-size:14px;padding-left: 2px;'>*</span><span ng-show="serfetconfForm.reaccountno.$dirty && serfetconfForm.reaccountno.$invalid">
															<span class = 'err' ng-show="serfetconfForm.reaccountno.$error.required">Required</span></span></label>
																<div class="input-group">
																	<input maxlength='12' type="text" required ng-model='reaccountno'  class="form-control" placeholder='<?php echo FINANCE_SEVICE_ORDER_CASH_IN_PLACE_HOLDER_RE_VALID_ACC_NO; ?>' numbers-only  />
																	<span class="input-group-addon"><span class="fa fa-credit-card"></span></span>
																</div>
															</div>
														</div>
													</div>											
													<div class='modal-footer'>
														<div class="panel-footer">
															<div class="row">
																<div class="col-xs-12" style='text-align:center'>
																	<button type='button' ng-disabled = '(serfetconfForm.$invalid) || (accountno != reaccountno)  || (accountno.length < 10)  || (reaccountno.length < 10)'  ng-click='pay()' ng-hide='isProcess' class="btn btn-primary "><?php echo FINANCE_SEVICE_ORDER_CASH_IN_BUTTON_PROCESS_PAYMENT; ?></button>
																	<button type='reset' ng-hide='isProcess' class="btn btn-primary ">Reset</button>
																	<button type='button' ng-hide='isProcess' data-dismiss='modal' class="btn btn-primary ">Cancel</button>
																	<button type='button' id='Ok' ng-hide='isOk' class="btn btn-primary "><?php echo FINANCE_SEVICE_ORDER_CASH_IN_BUTTON_OK; ?></button>
																</div>
															</div>
														</div>
													</div>	
												</form>
											</div>
										 <div id="card" class="tab-pane fade in">
											<form role="form" name='finCahInCardForm' id='finCahInCardFormId'>
												<div class="row">
													<div class="col-xs-12">
														<div class="form-group">
															<label><?php echo FINANCE_SEVICE_ORDER_CASH_IN_CARD_NUMBER; ?></label>
															<div class="input-group">
																<input maxlength='16' type="text"  ng-model='card'  class="form-control" required placeholder='<?php echo FINANCE_SEVICE_ORDER_CASH_IN_PLACE_HOLDER_VALID_CARD_NO; ?>' numbers-only  />
																<span class="input-group-addon"><span class="fa fa-credit-card"></span></span>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-xs-7 col-md-7">
														<div class="form-group">
															<label><span class="hidden-xs"><?php echo FINANCE_SEVICE_ORDER_CASH_IN_EXPIRATION; ?></span><span class="visible-xs-inline">EXP</span><?php echo FINANCE_SEVICE_ORDER_CASH_IN_DATE; ?></label>
															<input type="tel" numbers-only required ng-model='exdate' maxlength="4" class="form-control" placeholder='<?php echo FINANCE_SEVICE_ORDER_CASH_IN_PLACE_HOLDER_MM_YY; ?>' />
														</div>
													</div>
													<div class="col-xs-5 col-md-5 pull-right">
														<div class="form-group">
															<label><?php echo FINANCE_SEVICE_ORDER_CASH_IN_CV_CODE; ?><span style='color:red;font-size:14px;padding-left: 2px;'>*</span><span ng-show="finCahInCardForm.cvc.$dirty && finCahInCardForm.cvc.$invalid">
															<span class = 'err' ng-show="finCahInCardForm.cvc.$error.required">Required</span></span></label>
															<input numbers-only ng-model='cvc' required maxlength='3' type="tel" class="form-control" placeholder='<?php echo FINANCE_SEVICE_ORDER_CASH_IN_PLACE_HOLDER_CVC; ?>' />
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-xs-12">
														<div class="form-group">
															<label><?php echo FINANCE_SEVICE_ORDER_CASH_IN_CARD_OWNER; ?></label>
															<input type="text" ng-model='cardname' required class="form-control" placeholder='<?php echo FINANCE_SEVICE_ORDER_CASH_IN_PLACE_HOLDER_CARD_OWNER_NAMES; ?>' />
														</div>
													</div>
												</div>
											</form>
											<div class='modal-footer'>
												<div class="panel-footer">
													<div class="row">
														<div class="col-xs-12">
															<button type='button' ng-disabled='finCahInCardForm.$invalid' ng-click='pay()' ng-hide='isProcess' class="btn btn-warning btn-lg btn-block"><?php echo FINANCE_SEVICE_ORDER_CASH_IN_BUTTON_PROCESS_PAYMENT; ?></button>
															<button type='button' id='Ok' ng-hide='isOk' class="btn btn-warning btn-lg btn-block"><?php echo FINANCE_SEVICE_ORDER_CASH_IN_BUTTON_OK; ?></button>
														</div>
													</div>
												</div>
											</div>	
										</div>
										
									</div>
										
								</div>											
							</div>
						</div>
					</div>
				 </div>
			 </div>
			 <div class='modal-footer'>
				<div class="panel-footer">
					<div class="row">
						<div class="col-xs-12">														
							<button type='button' id='Ok' ng-hide='isOk' class="btn btn-warning btn-lg btn-block"><?php echo FINANCE_SEVICE_ORDER_CASH_IN_BUTTON_OK; ?></button>
						</div>
					</div>
				</div>
			</div>
		 </div>			
		</div>
	</div>
	</div>
     </div>
</div></div>
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
	$("#Ok, #Refresh").click(function() {
		window.location.reload();
	});
	
});
</script>
