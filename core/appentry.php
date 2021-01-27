<?php 	
	include('../common/sessioncheck.php');
	error_reporting(0);
	include('../common/admin/finsol_ini.php');
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
<style>
.fileUpload {
    position: relative;
    margin: 10px;
}
.fileUpload input.upload {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    padding: 0;
    font-size: 20px;
    cursor: pointer;
    opacity: 0;
    filter: alpha(opacity=0);
}
</style>
<div ng-controller='appentryCtrl'>
<div class="row" >
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!appent"><?php echo APPLICATION_ENTRY_HEADING1; ?></a></li>
			<li><a href="#!appent"><?php echo APPLICATION_ENTRY_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

	<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo APPLICATION_ENTRY_HEADING3; ?></span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
					<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div ng-app="" class="box-content" style='padding: 0px 10px !important;'>	              
				<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>
				<form name='applicationEntryForm' id='ApplicationEntryForm' method='POST' action=''>	
				
                    <div id='AppentryCreateBody'  ng-hide='isLoader'>
					  <div class='rowcontent'>
						<div class='row appcont' style='padding:0px'>
						    <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_COUNTRY; ?><span class='spanre'>*</span><span ng-show="applicationEntryForm.country.$touched ||applicationEntryForm.country.$dirty && applicationEntryForm.country.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationEntryForm.country.$error.required"></span></span></label>
								<select ng-disabled = 'isSelectDisabled' ng-model="country" ng-init="country='<?php echo ADMIN_COUNTRY_ID; ?>';countrychange(this.country)"   class='form-control' name = 'country' id='country' required>											
									<option ng-repeat="country in countrys" value="{{country.id}}">{{country.description}}</option>
								</select>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_CATEGORY; ?><span class='spanre'>*</span><span ng-show="applicationEntryForm.category.$touched ||applicationEntryForm.category.$dirty && applicationEntryForm.category.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationEntryForm.category.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-disabled = 'isSelectDisabled' ng-model="category"  name='category' id='Category' required class='form-control'>
									<option value=''><?php echo APPLICATION_ENTRY_SELECT_CATEGORY; ?></option>
									<option value='N'><?php echo APPLICATION_ENTRY_NEW; ?></option>
									<option value='C'><?php echo APPLICATION_ENTRY_CHANGE; ?></option>
									<option value='T'><?php echo APPLICATION_ENTRY_TRANSFER; ?></option>
									<option value='X'><?php echo APPLICATION_ENTRY_CANCEL; ?></option>
								</select>
							</div>							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_APPLIER_TYPE; ?><span class='spanre'>*</span><span ng-show="applicationEntryForm.appliertype.$touched ||applicationEntryForm.appliertype.$dirty && applicationEntryForm.appliertype.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationEntryForm.appliertype.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-disabled = 'isSelectDisabled' ng-model="appliertype"  name='appliertype' id='ApplierType' required class='form-control'>
									<option value=''><?php echo APPLICATION_ENTRY_SELECT_TYPE; ?></option>									
									<?php if($profile_id == 1 || $profile_id == 10) { ?>
										<option value='C'><?php echo APPLICATION_ENTRY_CHAMPION; ?></option>
										<option value='P'><?php echo APPLICATION_ENTRY_PERSONAL; ?></option>
									<?php }  if($profile_id == 1 || $profile_id == 10 || $profile_id == 50) {?>											
										<option value='A'><?php echo APPLICATION_ENTRY_AGENT; ?></option>
									<?php } if($profile_id == 1 || $profile_id == 10 || $profile_id == 51) { ?>
										<option value='S'><?php echo APPLICATION_ENTRY_SUB_AGENT; ?></option>									
									<?php } ?>
								</select>
							</div>
							<?php if($profile_id == 51) { ?>
							<div ng-show="appliertype=='S'" class='col-lg-3 col-xs-12 col-sm-12 col-md-12 parenttype'>
								<label><?php echo PAYMENT_ENTRY_PARTY_CODE_AGENT; ?><span class='spanre'>*</span><span class='err' ng-show="applicationEntryForm.parentcode.$error.required && applicationEntryForm.parentcode.$invalid"><?php echo REQUIRED;?></span></label>
								<input ng-disabled = 'isSelectDisabled'  ng-model="parentcode" type='text' ng-init = "parentcode = '<?php echo $_SESSION['party_code']; ?>'" readonly required class='form-control'/>
							</div>								
							<?php } if($profile_id == 1 || $profile_id == 10 || $profile_id == 50) { ?>
							<div ng-show="appliertype=='S'" class='col-lg-3 col-xs-12 col-sm-12 col-md-12 parenttype'>
								<label><?php echo PAYMENT_ENTRY_PARTY_CODE_AGENT; ?><span class='spanre'>*</span><span class='err' ng-show="applicationEntryForm.parentcode.$error.required && applicationEntryForm.parentcode.$invalid"><?php echo REQUIRED;?></span></label>
								<select ng-disabled = 'isSelectDisabled' ng-init="parentcode=''"  ng-model="parentcode"  name='parentcode' id='parentcodeSearch'  class='form-control'  >
									<option value=''><?php echo PAYMENT_ENTRY_PARTY_CODE_SELECT_AGENT; ?></option>
									<option ng-repeat="agent in agents" value="{{agent.code}}">{{agent.code}} - {{agent.name}}</option>
								</select>
							</div>
							<?php } if($profile_id == 1 || $profile_id == 10 ) { ?>
						   
							<div ng-show="appliertype=='A'" class='col-lg-3 col-xs-12 col-sm-12 col-md-12 parenttype'>
								<label><?php echo APPLICATION_ENTRY_PARENT_CODE_CHAMPION; ?><span ng-show="applicationEntryForm.parentcode.$touched ||applicationEntryForm.parentcode.$dirty && applicationEntryForm.parentcode.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationEntryForm.parentcode.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-disabled = 'isSelectDisabled' ng-model="parentcode"  ng-init = "parentcode = 'CA0000'"  name='parentcode' id='parentcode'  class='form-control'>
									<option value=''><?php echo APPLICATION_ENTRY_PARENT_CODE_SELECT_CHAMPION; ?></option>
									<option ng-repeat="champion in champions" value="{{champion.code}}">{{champion.code}} - {{champion.name}}</option>
								</select>
							</div>
							<?php } if($profile_id == 50) { ?>
							<div ng-show="appliertype=='A'" class='col-lg-3 col-xs-12 col-sm-12 col-md-12 parenttype'>
								<label><?php echo APPLICATION_ENTRY_PARENT_CODE_CHAMPION; ?><span class='spanre'>*</span><span ng-show="applicationEntryForm.parentcode.$touched ||applicationEntryForm.parentcode.$dirty && applicationEntryForm.parentcode.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationEntryForm.parentcode.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-disabled = 'isSelectDisabled' ng-model="parentcode" type='text' ng-init = "parentcode = '<?php echo $_SESSION['party_code']; ?>'" readonly required class='form-control'/>
							</div>	
							<?php } ?>
						</div>
						<div class='row appcont'>
							<div class='col-lg-5 col-xs-12 col-sm-12 col-md-12'>
								<label> <?php echo APPLICATION_ENTRY_USER_NAME; ?><span ng-hide = "isMsgSpanD" ng-if="msguser == 'User Name is Available'" style='color:#24b212;padding-left: 10px;'>{{msguser}}</span><span ng-hide = "isMsgSpanD" ng-if="msguser != 'User Name is Available'" style='color:red;padding-left: 10px;'>{{msguser}}</span><span class='spanre'>*</span><span ng-show="applicationEntryForm.userName.$touched ||applicationEntryForm.userName.$dirty && applicationEntryForm.userName.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationEntryForm.userName.$error.required"><?php echo REQUIRED;?></span><span style="color:Red" ng-show="applicationEntryForm.userName.$dirty && applicationEntryForm.userName.$error.minlength"> <?php echo MIN_10_CHARACTERS_REQUIRED; ?> </span></span></label>
								<input onkeypress="return AvoidSpace(event)" ng-disabled = "userNameDisabled || !category || !category || !appliertype || (appliertype == 'S' && !parentcode)   " placeholder = "Please Enter User Name" spl-char-not ng-model="userName" ng-keypress = "checkuservalid()" spl-char-not type='text' id='userName' maxlength='20' name='userName' ng-minlength="10" required class='form-control'/>
							</div>
							
							<div class='col-lg-1 col-xs-12 col-sm-12 col-md-12'><br />
								<button type="button" class="btn btn-primary"  ng-disabled="isGoDisbled"  ng-click='applicationEntryForm.$invalid=true;chkuser()' ng-hide='isHideGo'   id="GO">GO</button>
							
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo BVN ?><span class='spanre'>*</span><span ng-show="applicationEntryForm.bvn.$dirty && applicationEntryForm.bvn.$invalid">
									<span class = 'err'  ng-hide = "isMsgSpan" ng-message="required"><?php echo REQUIRED;?>.</span></span></label><span style="color:Red" ng-show="applicationEntryForm.bvn.$dirty && applicationEntryForm.bvn.$error.minlength"> <?php echo MIN_11_NUMBERS_REQUIRED; ?> </span>
								<input ng-model="bvn" numbers-only type='text' ng-disabled='isInputDisabled' id='BVN' ng-minlength="11" maxlength='11' name='bvn' required class='form-control'/>
							</div>	
						</div>
							<div class='row appcont' style='padding:0px'>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
							<label>Gender <span class='spanre'>*</span><span ng-show="applicationEntryForm.gender.$touched ||applicationEntryForm.gender.$dirty && applicationEntryForm.gender.$invalid">
								<span class = 'err'   ng-show="applicationEntryForm.gender.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="gender"  ng-disabled='isInputDisabled' class='form-control' name = 'gender' id='gender' required >											
									<option value=''>-- Select Gender --</option>
									<option value='Male'>Male</option>
									<option value='Female'>Female</option>
								<!--	<option value='Transgender'>TransGender</option> -->
								</select>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Date of Birth<span class='spanre'>*</span><span ng-show="applicationEntryForm.dob.$touched ||applicationEntryForm.dob.$dirty && applicationEntryForm.dob.$invalid">
								<span class = 'err'   ng-show="applicationEntryForm.dob.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="dob" ng-disabled='isInputDisabled' type='date' id='dob'  data-date-format="yyyy-mm-dd" name='dob' required class='form-control'/>
						</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label> Business Type
								<span ng-show="applicationEntryForm.BusinessType.$dirty && applicationEntryForm.BusinessType.$invalid">
								<span class = 'err' ng-show="applicationEntryForm.BusinessType.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select   ng-disabled='isInputDisabled' ng-model='BusinessType'  class='form-control' name = 'BusinessType' id='BusinessType' required>
								    <option value=''>--Select--</option> 
									<option value='0'>Pharmacy</option>
									<option value='1'>Gas Station</option>
									<option value='2'>Saloon</option>
									<option value='3'>Groceries Stores</option>
									<option value='4'>Super Market</option>
									<option value='5'>Mobile Network Outlets</option>
									<option value='6'>Restaurants</option>
									<option value='7'>Hotelst</option>
									<option value='8'>Cyber Cafe</option>
									<option value='9'>Post Office</option>
									<option value='10'>Others</option>
								</select>											
							</div>
							</div>
						<div class='row appcont'>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_OUTLET_NAME; ?><span class='spanre'>*</span><span ng-show="applicationEntryForm.outletname.$touched ||applicationEntryForm.outletname.$dirty && applicationEntryForm.outletname.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationEntryForm.outletname.$error.required"><?php echo REQUIRED;?><span style="color:Red" ng-show="applicationEntryForm.outletname.$dirty && applicationEntryForm.outletname.$error.minlength"> <?php echo MIN_4_CHARACTERS_REQUIRED; ?> </span></span></label>
								<input  ng-model="outletname" spl-char-not type='text' ng-disabled='isInputDisabled' id='OutLetName' maxlength='50' name='outletname' ng-minlength="4" required class='form-control'/>
							</div>
							
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_ADDRESS1; ?><span class='spanre'>*</span><span ng-show="applicationEntryForm.address1.$touched ||applicationEntryForm.address1.$dirty && applicationEntryForm.address1.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationEntryForm.address1.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="address1" type='text' ng-disabled='isInputDisabled' spl-char-not  id='Address1' maxlength='50' name='address1' required class='form-control'/>
							</div>
						</div>
						
						<div class='row appcont'>						
							
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_ADDRESS2; ?></label>
								<input ng-model="address2" type='text' ng-disabled='isInputDisabled' id='Address2' maxlength='50' name='address2'  class='form-control'/>
							</div>
								
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_STATE; ?><span class='spanre'>*</span><span ng-show="applicationEntryForm.state.$touched ||applicationEntryForm.state.$dirty && applicationEntryForm.state.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationEntryForm.state.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-disabled='isInputDisabled' ng-model="state" ng-change='statechange(this.state)' class='form-control' name = 'state' id='state' required>											
									<option value=''><?php echo APPLICATION_ENTRY_SELECT_STATE; ?></option>
									<option ng-repeat="state in states" value="{{state.id}}">{{state.name}}</option>
								</select>
							</div>	
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_LOCAL_GOVERMENT; ?><span class='spanre'>*</span><span ng-show="applicationEntryForm.localgovernment.$touched ||applicationEntryForm.localgovernment.$dirty && applicationEntryForm.localgovernment.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationEntryForm.localgovernment.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-disabled='isInputDisabled' ng-model="localgovernment"   class='form-control' name = 'localgovernment' id='LocalGoverment' required>											
									<option value=''><?php echo APPLICATION_ENTRY_SELECT_LOCAL_GOVT; ?></option>
									<option ng-repeat="localgvt in localgvts" value="{{localgvt.id}}">{{localgvt.name}}</option>
								</select>
							</div>
							
						</div>
						
						<div class='row appcont'>							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_ZIP_CODE; ?></label>
								<input ng-model="zipcode" type='text' ng-disabled='isInputDisabled' id='ZipCode' maxlength='15' name='zipcode'  class='form-control'/>
							</div>						
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_MOBILE_NO; ?><span class='spanre'>*</span><span ng-show="applicationEntryForm.mobileno.$dirty && applicationEntryForm.mobileno.$invalid">
									<span class = 'err'  ng-hide = "isMsgSpan" ng-message="required"><?php echo REQUIRED;?>.</span></span></label><span style="color:Red" ng-show="applicationEntryForm.mobileno.$dirty && applicationEntryForm.mobileno.$error.minlength"> <?php echo MIN_11_NUMBERS_REQUIRED; ?> </span>
								<input ng-model="mobileno" numbers-only type='text' ng-disabled='isInputDisabled' id='Mobile No' ng-minlength="11" maxlength='11' name='mobileno' required class='form-control'/>
							</div>	
					
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_EMAIL; ?><span class='spanre'>*</span><span ng-show="applicationEntryForm.email.$touched ||applicationEntryForm.email.$dirty && applicationEntryForm.email.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationEntryForm.email.$error.required"><?php echo REQUIRED;?></span></span>
								<span   style="color:Red" ng-show="applicationEntryForm.email.$dirty&&applicationEntryForm.email.$error.pattern"><?php echo APPLICATION_ENTRY_EMAIL_PLEASE_ENTER_VALID_EMAIL;?>.</span></label>
								<input ng-disabled='isInputDisabled' ng-model="email" type='email' ng-pattern="/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/" id='Email' maxlength='75' required name='email' class='form-control'/>
							</div>
							
						</div>
						
						<div class='row appcont' >
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_TAX_NUMBER; ?></label>
								<input ng-model="taxnumber"  type='text' ng-disabled='isInputDisabled' id='TaxNumber' maxlength='30' name='taxnumber' class='form-control'/>
							</div>	
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_WORK_NO; ?></label>
								<input ng-model="workno" numbers-only  type='text' ng-disabled='isInputDisabled'  spl-char-not ng-trim="false"  restrict-field="WorkNo" id='WorkNo' maxlength='20' name='workno' class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo Latitude; ?><span class='spanre'>*</span><span ng-show="applicationEntryForm.Latitude.$touched ||applicationEntryForm.Latitude.$dirty && applicationEntryForm.Latitude.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationEntryForm.Latitude.$error.required"><?php echo REQUIRED;?></span></span>
								<span   style="color:Red" ng-show="applicationEntryForm.Latitude.$dirty&&applicationEntryForm.Latitude.$error.pattern">Enter valid Coordinates.</span></label>
								<input  ng-disabled='isInputDisabled' ng-model="Latitude" type='text' ng-pattern="/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/" id='Latitude' maxlength='15' required name='Latitude' class='form-control'/>
								</div>	

							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo Longitude; ?><span class='spanre'>*</span><span ng-show="applicationEntryForm.Longitude.$touched ||applicationEntryForm.Longitude.$dirty && applicationEntryForm.Longitude.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationEntryForm.Longitude.$error.required"><?php echo REQUIRED;?></span></span>
								<span   style="color:Red" ng-show="applicationEntryForm.Longitude.$dirty&&applicationEntryForm.Longitude.$error.pattern">Enter valid Coordinates.</span></label>
								<input ng-disabled='isInputDisabled' ng-model="Longitude" type='text' ng-pattern="/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/" id='Longitude' maxlength='15' required name='Longitude' class='form-control'/>
							</div>
					
						</div>
						<div class='row appcont' >
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_CONTACT_NAME; ?><span class='spanre'>*</span><span ng-show="applicationEntryForm.cname.$dirty && applicationEntryForm.cname.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationEntryForm.cname.$error.required"><?php echo REQUIRED;?></span></span>
								<span style="color:Red" ng-show="applicationEntryForm.cname.$dirty && applicationEntryForm.cname.$error.minlength"> <?php echo MIN_4_CHARACTERS_REQUIRED; ?> </span></label>
								<input type='text' ng-disabled='isInputDisabled' ng-model="cname" id='ContactName' spl-char-not  maxlength='50' name='cname' ng-minlength="4" required class='form-control'  />
							</div>

							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_CONTACT_PHONE; ?></label>
								<input ng-model="cmobile"  numbers-only type='text' ng-disabled='isInputDisabled' id='ContactMobile' maxlength='20' name='cmobile' class='form-control'/>
							</div>
							
						</div>
						<div class='row appcont' >
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
							<label><?php echo "ID Document"; ?><span class='spanre'>*</span><span style="color:red" ng-show="(applicationEntryForm.attachment.$touched && applicationEntryForm.attachment.$error.validFile) ">File is required</span></label>
							<div style='display:flex; padding-top: 5px;width:460px'>
								<input id="IdDocument" required placeholder="Choose File"  disabled="disabled" class='form-control' />
								<div ng-disabled='isInputDisabled' class="fileUpload btn btn-primary" style='bottom:8px;' >
									<span>Upload</span>
									<input type="file" accept="image/jpg,image/jpeg,image/png,application/pdf" valid-file ng-file='uploadfiles' data-max-size="2097152 " name='attachment' required  ng-model="attachment" class="upload" id="attachment">
								</div>
							</div>
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
							<label><?php echo "Company Document"; ?><span class='spanre'>*</span><span style="color:red" ng-show="(applicationEntryForm.attachment2.$touched && applicationEntryForm.attachment2.$error.validFile) ">File is required</span></label>
							<div style='display:flex; padding-top: 5px;width:460px'>
								<input id="CompanyDocument" required placeholder="Choose File"  disabled="disabled" class='form-control' />
								<div ng-disabled='isInputDisabled' class="fileUpload btn btn-primary" style='bottom:8px;' >
									<span>Upload</span>
									<input type="file" accept="image/jpg,image/jpeg,image/png,application/pdf" valid-file ng-file='uploadfiles2' data-max-size="2097152 " name='attachment2' required  ng-model="attachment2" class="upload" id="attachment2">
								</div>
							</div>
							</div>

						</div>
						
						<div class='row appcont' style='margin-top: -20px;'>
							<div class='col-lg-9 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_COMMENT; ?></label>
								<input  ng-model="comment" type='text' ng-disabled='isInputDisabled' id='Comment' maxlength='256' name='comment' class='form-control'/>
							</div>
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_LANGUAGE_PREFRENCE; ?><span class='spanre'>*</span><span ng-show="applicationEntryForm.langpref.$touched ||applicationEntryForm.langpref.$dirty && applicationEntryForm.langpref.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationEntryForm.langpref.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="applicationEntryForm.langpref.$dirty && applicationEntryForm.langpref.$error.minlength">  </span></label>
								<select ng-disabled='isInputDisabled' ng-model="langpref"   class='form-control' name = 'langpref' id='langpref' required class='form-control'>											
									<option value=''><?php echo APPLICATION_ENTRY_LANGUAGE_PREFRENCE_SELECT; ?></option>	
									<option ng-repeat="lang in langs" value="{{lang.id}}">{{lang.name}}</option>									
								</select>
							</div>
						</div>
						</div>
						</div>
						
						<div class='row appcont' style='text-align:center'>
						<button type='button' class='btn btn-primary'  id='Ok' ng-hide='isHideOk' ><?php echo APPLICATION_ENTRY_BUTTON_OK; ?></button>
						<button type="button" class="btn btn-primary"  confirmed-click="applicationEntryForm.$invalid=true;create()" ng-confirm-click="Are you sure want to Submit Applciation ?" ng-hide='isHide' ng-disabled = "applicationEntryForm.$invalid"  id="Submit"><?php echo APPLICATION_ENTRY_BUTTON_SUBMIT_APPLICATION; ?></button>
						<button type="button" class="btn btn-primary" ng-click='reset()'  ng-hide='isHideReset' id="Reset"><?php echo APPLICATION_ENTRY_BUTTON_RESET; ?></button>
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
	function AvoidSpace(event) {
    var k = event ? event.which : window.event.keyCode;
    if (k == 32) return false;
	}
	
	$("#ApplicationEntryForm").on("click","#Ok",function() {
		window.location.reload();
		});
	$("#Reset").click(function() {
		$(".parenttype").hide();
	});
});
var d = new Date();
var n = d.getFullYear() + '' + ('0' + (d.getMonth()+1)).slice(-2) + '' + ('0' + d.getDate()).slice(-2) +''+ (d.getHours() < 10 ? '0' : '') + d.getHours() + "" +  (d.getMinutes() < 10 ? '0' : '') + d.getMinutes() + "" +  (d.getSeconds() < 10 ? '0' : '') + d.getSeconds() ;
var username='<?php echo $_SESSION['user_name'];?>';
document.getElementById("attachment").onchange = function () {
	var ext = $('#attachment').val().split('.').pop();
	//alert(ext);
	document.getElementById("IdDocument").value = username+ '_ID_'+n + '.' + ext;
		document.getElementById("attachment").value = username+ '_ID_'+n + '.' + ext;

 
};
document.getElementById("attachment2").onchange = function () {
	var ext = $('#attachment2').val().split('.').pop();
    document.getElementById("CompanyDocument").value =username+ '_BD_'+n+ '.' + ext;
	document.getElementById("attachment2").value =username+ '_BD_'+n+ '.' + ext;
};
				

   
    $('#attachment').change(function(e){
		 var fileInput = $('#attachment');
		 var maxSize = fileInput.data('max-size');
            var fileSize = fileInput.get(0).files[0].size; // in bytes
            if(fileSize>maxSize){
                alert('file size is more than ' + 2 + ' mb');
				document.getElementById("attachment").value ="";
				document.getElementById("IdDocument").value ="";

                return false;
            }
    });
	 $('#attachment2').change(function(e){
		 var fileInput = $('#attachment2');
		 var maxSize = fileInput.data('max-size');
            var fileSize = fileInput.get(0).files[0].size; // in bytes
            if(fileSize>maxSize){
                alert('file size is more than ' + 2 + ' mb');
				document.getElementById("attachment2").value ="";
				document.getElementById("CompanyDocument").value ="";

                return false;
            }
    });
$("#parentcodeSearch").select2();

</script>