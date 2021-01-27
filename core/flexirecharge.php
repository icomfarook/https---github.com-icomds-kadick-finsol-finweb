<?php 
	include('../common/admin/finsol_label_ini.php');
	include('../common/sessioncheck.php');
?>
<style>
#AddProfileDialogue .table > tbody > tr > td {
	border:none;
}
.col-lg-2 {
	width:2%;
}
</style>
<div ng-controller='flexiCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#"><?php echo FLEXI_RECHARGE_HEADING1; ?></a></li>
			<li><a href="#"><?php echo FLEXI_RECHARGE_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
	
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo FLEXI_RECHARGE_HEADING3; ?></span>
				</div>
				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding">
					<div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>			
				<a data-toggle='modal' data-target='#flexiRechargeMTN' ng-click='mtn()'><div  class='col-xs-12 col-md-12 col-lg-5 col-sm-12 form_col12_element oprmtn'>
				</div></a>
				<div class='col-xs-12 col-md-12 col-lg-2 col-sm-12 form_col12_element '>
				</div>
				<a data-toggle='modal' data-target='#flexiRechargeGLO' ng-click='glo()'><div class='col-xs-12 col-md-12 col-lg-5 col-sm-12 form_col12_element oprglo'>
				</div></a>
				<a data-toggle='modal' data-target='#flexiRechargeATL' ng-click='atl()'><div class='col-xs-12 col-md-12 col-lg-5 col-sm-12 form_col12_element opratl'>
				</div></a>
				<div class='col-xs-12 col-md-12 col-lg-2 col-sm-12 form_col12_element '>
				</div>
				<a data-toggle='modal' data-target='#flexiRecharge9M' ng-click='eti()'><div class='col-xs-12 col-md-12 col-lg-5 col-sm-12 form_col12_element opr9m'>
				</div></a>
			</div>
		</div>
	</div>
	 <div id='flexiRechargeMTN' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo FLEXI_RECHARGE_HEADING1; ?> - MTN </h2>
					</div>	
						<div style='text-align:center'  class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='row' style='padding:5px 0px'>
						<div class='modal-body'>
							<form action="" method="POST"  name='flexiRechargeForm' id="flexiRechargeForm">
							<div class='flexiOperatorBody'>
								
									<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
										<label> <?php echo FLEXI_RECHARGE_OPERATOR; ?><span ng-show="flexiRechargeForm.code.$touched ||flexiRechargeForm.code.$dirty && flexiRechargeForm.code.$invalid">
											<span class = 'err' ng-show="flexiRechargeForm.code.$error.required"><?php echo REQUIRED;?>.</span></span></label>
											<input type='text' readonly='true' ng-model='code'  required name='code' maxlength='3' id='OperatorCode' class='form-control'/>
									</div>	
									<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
									<br />
										<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
											<label>&nbsp;<input type='radio' name='lineType' value='pre' ng-model='lineType' /><?php echo FLEXI_RECHARGE_PRE_PAID; ?></label>
										</div>
										<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
											<label>&nbsp;<input type='radio' name='lineType' value='pos' ng-model='lineType' /><?php echo FLEXI_RECHARGE_POST_PAID; ?></label>
										</div>
									</div>
																	
								<div class='clearfix'></div>
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
									<label> <?php echo FLEXI_RECHARGE_MOBILE; ?><span ng-show="flexiRechargeForm.mobile.$touched ||flexiRechargeForm.mobile.$dirty && flexiRechargeForm.mobile.$invalid">
										<span class = 'err' ng-show="flexiRechargeForm.mobile.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<input type='text' numbers-only  maxlength='11' ng-model='mobile'  required name='mobile'  id='MobileNumber' class='form-control'/>
								</div>
								
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
									<label> <?php echo FLEXI_RECHARGE_REMOBILE; ?><span ng-show="flexiRechargeForm.remobile.$touched ||flexiRechargeForm.remobile.$dirty && flexiRechargeForm.remobile.$invalid">
										<span class = 'err' ng-show="flexiRechargeForm.remobile.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<input type='text' numbers-only  maxlength='11' ng-model='remobile'  required name='remobile'  id='ReMobileNumber' class='form-control'/>
								</div>
								
								<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 form_col12_element'>
									<label> <?php echo FLEXI_RECHARGE_AMOUNT; ?><span ng-show="flexiRechargeForm.amount.$touched ||flexiRechargeForm.amount.$dirty && flexiRechargeForm.amount.$invalid">
										<span class = 'err' ng-show="flexiRechargeForm.amount.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<input type='text' numbers-only  maxlength='11' ng-model='amount'  required name='amount'  id='amountNumber' class='form-control'/>
								</div>
							</div>
						</div>
					 </form>	
					</div>
					<div class='modal-footer'>
						<button type='button' class='btn btn-primary' data-dismiss='modal'  id='Ok' ng-hide='isHideOk' ><?php echo PROFILE_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo PROFILE_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide' ng-click= 'flexiRechargeForm.$invalid=true;recharge()' ng-disabled="flexiRechargeForm.$invalid" ng-click="flexiRechargeForm.$invalid=true;recharge()" id="Recharge"><?php echo FLEXI_RECHARGE_RECHARGE; ?></button>
					</div>
				</div>
	</div></div>
	
	<div id='flexiRechargeATL' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo FLEXI_RECHARGE_HEADING1; ?> - ATL </h2>
					</div>	
						<div style='text-align:center'  class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='row' style='padding:5px 0px'>		 
						<div class='modal-body'>	
							<form action="" method="POST"  name='flexiRechargeForm' id="flexiRechargeForm">
							<div class='flexiOperatorBody'>
								<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 form_col12_element'>
									<label> <?php echo FLEXI_RECHARGE_OPERATOR; ?><span ng-show="flexiRechargeForm.code.$touched ||flexiRechargeForm.code.$dirty && flexiRechargeForm.code.$invalid">
										<span class = 'err' ng-show="flexiRechargeForm.code.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<input type='text' readonly='true' ng-model='code'  required name='code' maxlength='3' id='OperatorCode' class='form-control'/>
								</div>	

								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
									<label> <?php echo FLEXI_RECHARGE_MOBILE; ?><span ng-show="flexiRechargeForm.mobile.$touched ||flexiRechargeForm.mobile.$dirty && flexiRechargeForm.mobile.$invalid">
										<span class = 'err' ng-show="flexiRechargeForm.mobile.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<input type='text' numbers-only  maxlength='11' ng-model='mobile'  required name='mobile'  id='MobileNumber' class='form-control'/>
								</div>
									<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
									<label> <?php echo FLEXI_RECHARGE_REMOBILE; ?><span ng-show="flexiRechargeForm.remobile.$touched ||flexiRechargeForm.remobile.$dirty && flexiRechargeForm.remobile.$invalid">
										<span class = 'err' ng-show="flexiRechargeForm.remobile.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<input type='text' numbers-only  maxlength='11' ng-model='remobile'  required name='remobile'  id='ReMobileNumber' class='form-control'/>
								</div>
								
								
								<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 form_col12_element'>
									<label> <?php echo FLEXI_RECHARGE_AMOUNT; ?><span ng-show="flexiRechargeForm.amount.$touched ||flexiRechargeForm.amount.$dirty && flexiRechargeForm.amount.$invalid">
										<span class = 'err' ng-show="flexiRechargeForm.amount.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<input type='text' numbers-only  maxlength='11' ng-model='amount'  required name='amount'  id='amountNumber' class='form-control'/>
								</div>
							</div>
						</form>	
						</div>
					</div>
					<div class='modal-footer'>
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo PROFILE_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo PROFILE_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide' ng-click= 'recharge()'  ng-disabled="flexiRechargeForm.$invalid" ng-click="recharge()" id="Recharge"><?php echo FLEXI_RECHARGE_RECHARGE; ?></button>
					</div>
				</div>
	</div></div>
	
	<div id='flexiRechargeGLO' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo FLEXI_RECHARGE_HEADING1; ?> - GLO </h2>
					</div>					 
					   <div style='text-align:center'  class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='row' style='padding:5px 0px'>		 
						<div class='modal-body'>
							<form action="" method="POST"  name='flexiRechargeForm' id="flexiRechargeForm">
							<div class='flexiOperatorBody'>
								<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 form_col12_element'>
									<label> <?php echo FLEXI_RECHARGE_OPERATOR; ?><span ng-show="flexiRechargeForm.code.$touched ||flexiRechargeForm.code.$dirty && flexiRechargeForm.code.$invalid">
										<span class = 'err' ng-show="flexiRechargeForm.code.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<input type='text' readonly='true' ng-model='code'  required name='code' maxlength='3' id='OperatorCode' class='form-control'/>
								</div>	

								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
									<label> <?php echo FLEXI_RECHARGE_MOBILE; ?><span ng-show="flexiRechargeForm.mobile.$touched ||flexiRechargeForm.mobile.$dirty && flexiRechargeForm.mobile.$invalid">
										<span class = 'err' ng-show="flexiRechargeForm.mobile.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<input type='text' numbers-only  maxlength='11' ng-model='mobile'  required name='mobile'  id='MobileNumber' class='form-control'/>
								</div>
									<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
									<label> <?php echo FLEXI_RECHARGE_REMOBILE; ?><span ng-show="flexiRechargeForm.remobile.$touched ||flexiRechargeForm.remobile.$dirty && flexiRechargeForm.remobile.$invalid">
										<span class = 'err' ng-show="flexiRechargeForm.remobile.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<input type='text' numbers-only  maxlength='11' ng-model='remobile'  required name='remobile'  id='ReMobileNumber' class='form-control'/>
								</div>
								
								<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 form_col12_element'>
									<label> <?php echo FLEXI_RECHARGE_AMOUNT; ?><span ng-show="flexiRechargeForm.amount.$touched ||flexiRechargeForm.amount.$dirty && flexiRechargeForm.amount.$invalid">
										<span class = 'err' ng-show="flexiRechargeForm.amount.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<input type='text' numbers-only  maxlength='11' ng-model='amount'  required name='amount'  id='amountNumber' class='form-control'/>
								</div>
							</div>
						 </form>	
						</div>
					</div>
					<div class='modal-footer'>
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo PROFILE_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo PROFILE_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide' ng-click= 'flexiRechargeForm.$invalid=true;recharge()' ng-disabled="flexiRechargeForm.$invalid" ng-click="flexiRechargeForm.$invalid=true;recharge()" id="Recharge"><?php echo FLEXI_RECHARGE_RECHARGE; ?></button>
					</div>
				</div>
	</div></div>
	
	<div id='flexiRecharge9M' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog modal-md">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo FLEXI_RECHARGE_HEADING1; ?> - 9M </h2>
					</div>	
						<div style='text-align:center'  class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='row' style='padding:5px 0px'>		 
						<div class='modal-body'>	
						    <form action="" method="POST"  name='flexiRechargeForm' id="flexiRechargeForm">
							<div class='flexiOperatorBody'>
								<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 form_col12_element'>
									<label> <?php echo FLEXI_RECHARGE_OPERATOR; ?><span ng-show="flexiRechargeForm.code.$touched ||flexiRechargeForm.code.$dirty && flexiRechargeForm.code.$invalid">
										<span class = 'err' ng-show="flexiRechargeForm.code.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<input type='text' readonly='true' ng-model='code'  required name='code' maxlength='3' id='OperatorCode' class='form-control'/>
								</div>	

								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
									<label> <?php echo FLEXI_RECHARGE_MOBILE; ?><span ng-show="flexiRechargeForm.mobile.$touched ||flexiRechargeForm.mobile.$dirty && flexiRechargeForm.mobile.$invalid">
										<span class = 'err' ng-show="flexiRechargeForm.mobile.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<input type='text' numbers-only  maxlength='11' ng-model='mobile'  required name='mobile'  id='MobileNumber' class='form-control'/>
								</div>
									<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
									<label> <?php echo FLEXI_RECHARGE_REMOBILE; ?><span ng-show="flexiRechargeForm.remobile.$touched ||flexiRechargeForm.remobile.$dirty && flexiRechargeForm.remobile.$invalid">
										<span class = 'err' ng-show="flexiRechargeForm.remobile.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<input type='text' numbers-only  maxlength='11' ng-model='remobile'  required name='remobile'  id='ReMobileNumber' class='form-control'/>
								</div>
								
								
								<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 form_col12_element'>
									<label> <?php echo FLEXI_RECHARGE_AMOUNT; ?><span ng-show="flexiRechargeForm.amount.$touched ||flexiRechargeForm.amount.$dirty && flexiRechargeForm.amount.$invalid">
										<span class = 'err' ng-show="flexiRechargeForm.amount.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<input type='text' numbers-only  maxlength='11' ng-model='amount'  required name='amount'  id='amountNumber' class='form-control'/>
								</div>
							</div>
						</div>
						</form>	
					</div>
					<div class='modal-footer'>
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo PROFILE_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo PROFILE_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide' ng-click= 'flexiRechargeForm.$invalid=true;recharge()' ng-disabled="flexiRechargeForm.$invalid" ng-click="flexiRechargeForm.$invalid=true;recharge()" id="Recharge"><?php echo FLEXI_RECHARGE_RECHARGE; ?></button>
					</div>
				</div>
	</div></div>
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
	LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	//WinMove();
	$("#flexiRecharge9M, #flexiRechargeATL, #flexiRechargeMTN,#flexiRechargeGLO").on("click","#Ok",function() {
		window.location.reload();
	});

});
</script>
