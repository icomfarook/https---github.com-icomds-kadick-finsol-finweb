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
	$profile_id  = $_SESSION['profile_id'];
	//error_log("prifilid".$profile_id);
?>
<div ng-controller='sanefBankAccCtrl'>
<div class="row" >
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!bnkacc"><?php echo TIER1_MAIN_HEADING1;?></a></li>
			<li><a href="#!bnkacc"><?php echo TIER1_MAIN_HEADING2;?></a></li>
		</ol>
		
	</div>
</div>
	
	<div class="row"   >
		<div class="col-lg-12"  ng-show = "isdetailcost" style='margin: 20% 30%'>
			<input type='button' class='btn btn-primary' data-toggle="modal" ng-click="getcharge()" data-target="#detailcost" value='Click to See Charges to Create Bank Account' />
		</div>
	</div>
	<div class="row"  ng-app="" >
<div id='detailcost' ng-show = "isdetailcost" class='modal'  role='dialog'>
		 <div class="modal-dialog modal-md">
			<div class="modal-content">				
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3><?php echo "Charge Details"; ?></h3>						
				</div>				 
				<div class='modal-body '>
				<div id='tableres'></div></div>
				<div class='modal-footer' style='text-align:center'>	
				<button type='button' class='btn btn-primary' id='confirmscreen' ng-click = "confirmscreen();" ng-hide='isHide' ><?php echo "Ok"; ?></button>				
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo "Cancel"; ?></button>
				</div>				
			</div>
		</div>
	</div>
	<div class="col-xs-12" ng-show = "isScreenHide" >
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo "Bank Account";?></span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
					<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			<style>
			.appcont {
				margin: 1% 1% !important;
			}
			</style>
				
			
			<div class="box-content" style='padding: 0px 10px !important;' data-backdrop="static" data-keyboard="false">	
				<div  style='text-align:center 'class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>			
				<form name='TieracForm' id='TieracForm' method='POST' action=''>	
				
                    <div id='TieracCreateBody'  ng-hide='isLoader'>
					
					  <div class='rowcontent' style='padding-top:10px'>
					  <div class='row appcont'>
					<div class='col-lg-9 col-xs-12 col-sm-12 col-md-12' >
					</div>
						<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12' >
								<label style='dispplay:inline-block'><?php echo PAYMENT_ENTRY_BANK; ?><span class='spanre'>*</span><span ng-show="paymentEntryForm.bankaccount.$touched ||paymentEntryForm.bankaccount.$dirty && paymentEntryForm.bankaccount.$invalid">
								<span class = 'err' ng-show="paymentEntryForm.bankaccount.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="bankaccount"  class='form-control' name = 'bankaccount' id='bankaccount' required>											
									<option value=""><?php echo PAYMENT_ENTRY_SELECT_BANK; ?></option>
									<option ng-repeat="bank in banks" value="{{bank.id}}">{{bank.name}}-{{bank.aname}}-{{bank.ano}}</option>
								</select>
							</div>
						</div>	
					  <div class='row appcont'>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo TIER1_MAIN_FIRST_NAME;?> <span class='spanre'>*</span><span ng-show="TieracForm.firstName.$touched ||TieracForm.firstName.$dirty && TieracForm.firstName.$invalid">
								<span class = 'err' ng-show="TieracForm.firstName.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="TieracForm.firstName.$dirty && TieracForm.firstName.$error.minlength"> <?php echo MIN_4_CHARACTERS_REQUIRED; ?> </span></label>
								<input  ng-model="firstName" spl-char-not type='text' id='firstName' maxlength='50' name='firstName' ng-minlength="4" required class='form-control'/>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo "Middle Name";?></label>
								<input ng-model="midName" type='text' id='midName' maxlength='50' name='midName'  class='form-control'/>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo TIER1_MAIN_LAST_NAME;?><span class='spanre'>*</span><span ng-show="TieracForm.lastName.$touched ||TieracForm.lastName.$dirty && TieracForm.lastName.$invalid">
								<span class = 'err' ng-show="TieracForm.lastName.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="lastName" type='text' id='lastName' maxlength='50' name='lastName' required class='form-control'/>
							</div>
						</div>
						
						<div class='row appcont'>	
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' style=''>
								<label><?php echo "Gender"; ?><span class='spanre'>*</span><span ng-show="TieracForm.gender.$touched ||TieracForm.gender.$dirty && TieracForm.gender.$invalid">
								<span class = 'err' ng-show="TieracForm.gender.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="gender" ng-init = "gender='Male'" ng-change='genderchange(this.gender)' class='form-control' name = 'gender' id='gender' required>											
									<option value='Male'><?php echo "Male"; ?></option>
									<option value='Female'><?php echo "FeMale"; ?></option>
									
									
								</select>
							 </div>		
							 
							 <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' style=''>
								<label><?php echo TIER1_MAIN_DATE_OF_BIRTH;?>
								<span class='spanre'>*</span><span ng-show="TieracForm.dob.$touched ||TieracForm.dob.$dirty && TieracForm.dob.$invalid">
								<span class = 'err' ng-show="TieracForm.dob.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="dob" type='date' id='dob'   name='dob' required class='form-control'/>
							 </div>	

						
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>										
								<label><?php echo TIER1_MAIN_BVN;?><span class='spanre'>*</span>
								<span ng-show="TieracForm.bvn.$dirty && TieracForm.bvn.$invalid">
								<span class = 'err' ng-show="TieracForm.bvn.$error.required"><?php echo REQUIRED;?></span></span></label>	
								<input type='text' name='bvn' ng-model='bvn' maxlength="20" placeholder='<?php echo BVN_ENQUIRY_MAIN_PLACE_HOLDER_BANK_VER_NO; ?>' class='form-control'/>										
						    </div>	
													 
						</div>
						
						<div class='row appcont'>	
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>										
								<label><?php echo "House No";?><span class='spanre'>*</span>
								<span ng-show="TieracForm.houseNo.$dirty && TieracForm.houseNo.$invalid">
								<span class = 'err' ng-show="TieracForm.houseNo.$error.required"><?php echo REQUIRED;?></span></span></label>	
								<input type='text' name='houseNo' ng-model='houseNo' maxlength="10" placeholder='<?php echo "House No is required"; ?>' class='form-control'/>										
						    </div>	
							
							<div class='col-lg-8 col-xs-12 col-sm-12 col-md-12'>										
								<label><?php echo "Street Name";?><span class='spanre'>*</span>
								<span ng-show="TieracForm.streetName.$dirty && TieracForm.streetName.$invalid">
								<span class = 'err' ng-show="TieracForm.streetName.$error.required"><?php echo REQUIRED;?></span></span></label>	
								<input type='text' name='streetName' ng-model='streetName' maxlength="70" placeholder='<?php echo "Street Name is required"; ?>' class='form-control'/>										
						    </div>
						</div>
						<div class='row appcont' style='padding:0px'>
						    <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>										
								<label><?php echo "City";?><span class='spanre'>*</span>
								<span ng-show="TieracForm.city.$dirty && TieracForm.city.$invalid">
								<span class = 'err' ng-show="TieracForm.city.$error.required"><?php echo REQUIRED;?></span></span></label>	
								<input type='text' name='city' ng-model='city' maxlength="50" placeholder='<?php echo "City is required"; ?>' class='form-control'/>										
						    </div>
							
						   <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' style='' ng-init = 'countrychange(566)'>
								<label><?php echo APPLICATION_ENTRY_STATE; ?><span class='spanre'>*</span><span ng-show="TieracForm.state.$touched ||TieracForm.state.$dirty && TieracForm.state.$invalid">
								<span class = 'err' ng-show="TieracForm.state.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="state" ng-change='statechange(this.state)' class='form-control' name = 'state' id='state' required>											
									<option value=''><?php echo APPLICATION_ENTRY_SELECT_STATE; ?></option>
									<option ng-repeat="state in states" value="{{state.id}}">{{state.name}}</option>
								</select>
							 </div>	

						<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_LOCAL_GOVERMENT; ?><span class='spanre'>*</span><span ng-show="TieracForm.localgovernment.$touched ||TieracForm.localgovernment.$dirty && TieracForm.localgovernment.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="TieracForm.localgovernment.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-disabled='isInputDisabled' ng-model="localgovernment"   class='form-control' name = 'localgovernment' id='LocalGoverment' required>											
									<option value=''><?php echo APPLICATION_ENTRY_SELECT_LOCAL_GOVT; ?></option>
									<option ng-repeat="localgvt in localgvts" value="{{localgvt.id}}">{{localgvt.name}}</option>
								</select>
							</div>							 
							 
					  </div>
						<div class='row appcont'>		
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo TIER1_MAIN_MOBILE_NO;?><span class='spanre'>*</span><span ng-show="TieracForm.mobileno.$dirty && TieracForm.mobileno.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label><span style="color:Red" ng-show="TieracForm.mobileno.$dirty && TieracForm.mobileno.$error.minlength"> <?php echo MIN_11_NUMBERS_REQUIRED; ?> </span>
								<input ng-model="mobileno" numbers-only type='text' id='Mobile No' ng-minlength="11" maxlength = '20' name='mobileno' required class='form-control'/>
							</div>	
							
							<div class='col-lg-8 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo TIER1_MAIN_EMAIL;?><span class='spanre'>*</span><span ng-show="TieracForm.email.$touched ||TieracForm.email.$dirty && TieracForm.email.$invalid">
								<span class = 'err' ng-show="TieracForm.email.$error.required"><?php echo REQUIRED;?></span></span>
								<span  style="color:Red" ng-show="TieracForm.email.$dirty&&TieracForm.email.$error.pattern"><?php echo APPLICATION_ENTRY_EMAIL_PLEASE_ENTER_VALID_EMAIL;?>.</span></label>
								<input ng-model="email" type='email' ng-pattern="/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/" id='Email' maxlength='50' required name='email' class='form-control'/>
																				
							</div>	
							
							
							</div>
							
							
					       
						<div class='row appcont'>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<div class="custom-file" >						
									<label style='text-align:left' ><?php echo "User Pic";?><input type="file" valid-file ng-file='usersign'  ng-model="usersign" class="custom-file-input" id="userpic"> </label>
								</div>									  
							</div>	
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<div class="user-pic" >						
									<label style='text-align:left' ><?php echo "User Sign";?><input type="file" valid-file ng-file='userpic'  ng-model="userpic" class="custom-file-input" id="usersign"> </label>
								</div>									  
							</div>		
							
						 </div>
						<div class='row appcont' style='text-align:center'>
						<button type="button" class="btn btn-primary" ng-click='TieracForm.$invalid=true;create()' ng-hide='isHide' ng-disabled = "TieracForm.$invalid"  id="Submit"><?php echo APPLICATION_ENTRY_BUTTON_CREATE; ?></button>
						<button type="reset" class="btn btn-primary"  ng-hide='isHideReset' id="Reset"><?php echo APPLICATION_ENTRY_BUTTON_RESET; ?></button>
						</div>				
				</div>
				</div>
		 
				</form>
			</div>
			
	   </div>
	   <div class='col-lg-12' style='text-align:center'>
		<button type='button' class='btn btn-primary'  ng-click='refresh()' id='Ok' ng-hide='isHideOk' ><?php echo PRE_APPLICATION_ENTRY_BUTTON_OK; ?></button>
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
	//LoadSelect2Script();
}

$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	 // LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	//WinMove();

		
	$("#Reset").click(function() {
		$(".parenttype").hide();
	});
});

							// Add the following code if you want the name of the file appear on select
									$(".custom-file-input").on("change", function() {
									  var fileName = $(this).val().split("\\").pop();
									 // alert(fileName);
									  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
									});

</script>
