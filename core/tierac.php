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
<div ng-controller='tier1Ctrl'>
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

	<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo TIER1_MAIN_HEADING3;?></span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
					<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div ng-app="" class="box-content" style='padding: 0px 10px !important;' data-backdrop="static" data-keyboard="false">	
				<div  style='text-align:center 'class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>			
				<form name='TieracForm' id='TieracForm' method='POST' action=''>					
                    <div id='TieracCreateBody'  ng-hide='isLoader'>
					  <div class='rowcontent'>
						<div class='row appcont' style='padding:0px'>
						    <div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
								<label><?php echo SERVICE_FEATURE_CONFIG_PARTNER; ?><span ng-show=" TieracForm.partner.$touched || TieracForm.partner.$dirty &&  TieracForm.partner.$invalid">
								<span class = 'err' ng-show=" TieracForm.partner.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="partner" class='form-control' name = 'partner' id='partner' required>											
									<option value=''><?php echo SERVICE_FEATURE_CONFIG_SELECT_PARTNER; ?></option>
									<option ng-repeat="par in partners" value="{{par.id}}">{{par.name}}</option>
								</select>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'  >
								<label><?php echo APPLICATION_ENTRY_COUNTRY; ?><span class='spanre'>*</span><span ng-show="TieracForm.country.$touched ||TieracForm.country.$dirty && TieracForm.country.$invalid">
								<span class = 'err' ng-show="TieracForm.country.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="country" ng-change='countrychange(this.country)'  class='form-control' name = 'country' id='country' required>											
									<option value=''><?php echo APPLICATION_ENTRY_SELECT_COUNTRY; ?></option>
									<option ng-repeat="country in countrys" value="{{country.id}}">{{country.description}}</option>
								</select>
							</div>
						   <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12' style=''>
								<label><?php echo APPLICATION_ENTRY_STATE; ?><span class='spanre'>*</span><span ng-show="TieracForm.state.$touched ||TieracForm.state.$dirty && TieracForm.state.$invalid">
								<span class = 'err' ng-show="TieracForm.state.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="state" ng-change='statechange(this.state)' class='form-control' name = 'state' id='state' required>											
									<option value=''><?php echo APPLICATION_ENTRY_SELECT_STATE; ?></option>
									<option ng-repeat="state in states" value="{{state.id}}">{{state.name}}</option>
								</select>
							 </div>							
					  </div>
													
						<div class='row appcont'>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo TIER1_MAIN_FIRST_NAME;?> <span class='spanre'>*</span><span ng-show="TieracForm.firstName.$touched ||TieracForm.firstName.$dirty && TieracForm.firstName.$invalid">
								<span class = 'err' ng-show="TieracForm.firstName.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="TieracForm.firstName.$dirty && TieracForm.firstName.$error.minlength"> <?php echo MIN_4_CHARACTERS_REQUIRED; ?> </span></label>
								<input  ng-model="firstName" spl-char-not type='text' id='firstName' maxlength='50' name='firstName' ng-minlength="4" required class='form-control'/>
							</div>
							
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo TIER1_MAIN_LAST_NAME;?><span class='spanre'>*</span><span ng-show="TieracForm.lastName.$touched ||TieracForm.lastName.$dirty && TieracForm.lastName.$invalid">
								<span class = 'err' ng-show="TieracForm.lastName.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="lastName" type='text' id='lastName' maxlength='50' name='lastName' required class='form-control'/>
							</div>
						</div>
						
							<div class='row appcont'>		
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo TIER1_MAIN_MOBILE_NO;?><span class='spanre'>*</span><span ng-show="TieracForm.mobileno.$dirty && TieracForm.mobileno.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label><span style="color:Red" ng-show="TieracForm.mobileno.$dirty && TieracForm.mobileno.$error.minlength"> <?php echo MIN_11_NUMBERS_REQUIRED; ?> </span>
								<input ng-model="mobileno" numbers-only type='text' id='Mobile No' ng-minlength="11" maxlength = '20' name='mobileno' required class='form-control'/>
							</div>	
							
							
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo TIER1_MAIN_DATE_OF_BIRTH;?>
							<span class='spanre'>*</span><span ng-show="TieracForm.dob.$touched ||TieracForm.dob.$dirty && TieracForm.dob.$invalid">
								<span class = 'err' ng-show="TieracForm.dob.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="dob" type='date' id='dob'   name='dob' required class='form-control'/>
							</div>
							</div>
							
							
					        <div class='row appcont'>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>										
							<label><?php echo TIER1_MAIN_BVN;?><span class='spanre'>*</span>
							<span ng-show="TieracForm.refno.$dirty && TieracForm.refno.$invalid">
							<span class = 'err' ng-show="TieracForm.bvn.$error.required"><?php echo REQUIRED;?></span></span></label>	
							<input type='text' name='bvn' ng-model='bvn' maxlength="11" placeholder='<?php echo BVN_ENQUIRY_MAIN_PLACE_HOLDER_BANK_VER_NO; ?>' class='form-control'/>										
						    </div>							
									
						      <div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo TIER1_MAIN_REF_MOBILE_NO;?><span class='spanre'>*</span><span ng-show="TieracForm.refmobileno.$dirty && TieracForm.refmobileno.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label><span style="color:Red" ng-show="TieracForm.refmobileno.$dirty && TieracForm.refmobileno.$error.minlength"> <?php echo MIN_11_NUMBERS_REQUIRED; ?> </span>
								<input ng-model="refmobileno" numbers-only type='text' id='Mobile No' ng-minlength="11" maxlength = '20' name='refmobileno' required class='form-control'/>
							</div>
							</div>
							
							
						<div class='row appcont'>
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo TIER1_MAIN_EMAIL;?><span class='spanre'>*</span><span ng-show="TieracForm.email.$touched ||TieracForm.email.$dirty && TieracForm.email.$invalid">
								<span class = 'err' ng-show="TieracForm.email.$error.required"><?php echo REQUIRED;?></span></span>
								<span  style="color:Red" ng-show="TieracForm.email.$dirty&&TieracForm.email.$error.pattern"><?php echo APPLICATION_ENTRY_EMAIL_PLEASE_ENTER_VALID_EMAIL;?>.</span></label>
								<input ng-model="email" type='email' ng-pattern="/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/" id='Email' maxlength='75' required name='email' class='form-control'/>
																				
							</div>	
                          </div>							
						

						    <div class="custom-file" style='margin-left:30px' >
						
									<label style='text-align:left' ><?php echo TIER1_MAIN_DOCUMENT_ATTACHMENT;?><input type="file" valid-file ng-file='uploadfiles'  ng-model="attachment" class="custom-file-input" id="attachment"> </label>
										
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
	$("#TieracForm").on("click","#Ok",function() {
		window.location.reload();
		});
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
