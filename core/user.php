<?php 	
	include("../common/admin/finsol_otp_ini.php");	
	include('../common/sessioncheck.php');
	error_reporting(0);
	$lang = $_SESSION['language_id'];
	if($lang == "1" || $lang=="") {
		include('../common/admin/finsol_label_ini.php');
	}
	if($lang == "2"){
		include('../common/admin/haus_finsol_label_ini.php');
	}
	$Hexcode = OTP_SERVER;
	$Hexcode = urlencode($Hexcode);
?>
<style>
#AddUserDialogue .table > tbody > tr > td {
	border:none;
}
</style>
<div ng-controller='userCtrl'>
	<div class="row">
		<div id="breadcrumb" class="col-xs-12">
			<a href="#" class="show-sidebar">
				<i class="fa fa-bars"></i>
			</a>
			<ol class="breadcrumb pull-left">
				<li><a href="#!acsusr"><?php echo USER_MAIN_HEADING1; ?></a></li>
				<li><a href="#!acsusr"><?php echo USER_MAIN_HEADING2; ?></a></li>
			</ol>
			
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<div class='row'>
				<input type='button' style='float: right;margin-right: 2%;' class='btn btn-warning' value=<?php echo USER_MAIN_BUTTON_CREATE; ?>' id='Create'  data-toggle='modal' data-target='#AddUserDialogue'/>
			</div>
			<div class="box">
				<div class="box-header">
					<div class="box-name">					
						<span><?php echo USER_MAIN_HEADING3; ?></span>
					</div>
					<div class="box-icons">
						
					</div>
					<div class="no-move"></div> 
				</div>
				
				<div class="box-content no-padding">
					<div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>			
					<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
						<thead>
							<tr>
							<th><?php echo USER_MAIN_TABLE_USER; ?></th>
							<th><?php echo USER_MAIN_TABLE_ACTIVE; ?></th>
      						<th><?php echo USER_MAIN_TABLE_EDIT; ?></th>
					    	<th><?php echo USER_MAIN_TABLE_DYNAMIC_OTP; ?></th>
							<th><?php echo USER_MAIN_TABLE_OTP; ?></th>
							<th><?php echo USER_MAIN_TABLE_CONTROL; ?></th>
							<th><?php echo USER_MAIN_TABLE_RESTRICT; ?></th>
							<th><?php echo USER_MAIN_TABLE_ACCESS; ?></th>
							<th><?php echo USER_MAIN_TABLE_POS_ACCESS; ?></th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="x in userList">
						 	<td>{{ x.user }} <span style='color:blue'> ({{ x.profileName }})</span><span style='color:red' ng-if="x.locked==='Y'">(Locked)</span></td>
							<td>{{ x.active }}</td>
							<td ><a id={{x.id}} class='icoimg' ng-click='edituser($index,x.id)'  data-toggle='modal' data-target='#EditUser'>
								<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
							</td>
							<td ng-if="x.dynamic === 'Y'"><a id={{x.id}} class='icoimg' ng-click='editotpdetails($index,x.id)'  data-toggle='modal' data-target='#OtpChange'>
								<button class='icoimg'><img class='icoimg' style='height:12px;width:12px' src='../common/images/accept.png'/></button></a>
							</td>
							<td ng-if="x.dynamic === 'N'"><a id={{x.id}} class='icoimg' ng-click='editotpdetails($index,x.id)'  data-toggle='modal' data-target='#OtpChange'>
								<button class='icoimg'><img class='icoimg' style='height:12px;width:12px' src='../common/images/error.png'/></button></a>
							</td>
							<td ng-if="x.dynamic === 'Y'"><a id={{x.id}} class='icoimg' ng-click='editdynamicotpdetails($index,x.id)'  data-toggle='modal' data-target='#EditOtp'>
								<button class='icoimg'><img class='icoimg' style='height:22px;width:20px' src='../common/images/dotp.png'/></button></a>
							</td>
							<td ng-if="x.dynamic === 'N'"><a id={{x.id}} class='icoimg' ng-click='editsotpdetails($index,x.id)'  data-toggle='modal' data-target='#EditSOtp'>
								<button class='icoimg'><img class='icoimg' style='height:22px;width:20px' src='../common/images/sotp.png'/></button></a>
							</td>
							<td ><a id={{x.id}} class='icoimg' ng-click='editcontrol($index,x.id)'  data-toggle='modal' data-target='#UserControl'>
								<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
							</td>
							<td ng-if="x.access === 'Y'"><a id={{x.id}} class='icoimg' ng-click='userrestrict($index,x.id)'  data-toggle='modal' data-target='#userrestrict' >
								<button class='icoimg'><img class='icoimg' style='height:12px;width:12px' src='../common/images/accept.png'/></button></a>
							</td>
							<td ng-if="x.access === 'N'"><a id={{x.id}} class='icoimg' ng-click='userrestrict($index,x.id)'  data-toggle='modal' data-target='#userrestrict'>
								<button class='icoimg'><img class='icoimg' style='height:12px;width:12px' src='../common/images/error.png'/></button></a>
							</td>
							<td ng-if="x.access === 'Y'"><a id={{x.id}} class='icoimg' ng-click="idd(x.id)"  data-toggle='modal' data-target='#UserAccsess'>
								<button class='icoimg'><img class='icoimg' style='height:22px;width:22px' src='../common/images/edit.png'/></button></a>
							</td>
							<td ng-if="x.access === 'N'">
								<label>-</label>
							</td>
							<td ng-if="x.posaccess === 'Y'"><a id={{x.id}} class='icoimg' ng-click='posaccess($index,x.id)'  data-toggle='modal' data-target='#posaccess' >
								<button class='icoimg'><img class='icoimg' style='height:12px;width:12px' src='../common/images/accept.png'/></button></a>
							</td>
							<td ng-if="x.posaccess === 'N'"><a id={{x.id}} class='icoimg' ng-click='posaccess($index,x.id)'  data-toggle='modal' data-target='#posaccess'>
								<button class='icoimg'><img class='icoimg' style='height:12px;width:12px' src='../common/images/error.png'/></button></a>
							</td>
						</tr>
						</tbody>
						
					</table>
				</div>
			</div>
		</div>
		 <div id='EditUser' class='modal' role='dialog'  data-backdrop="static" data-keyboard="false">
			<div class='modal-dialog modal-lg'>
				<div class='modal-content'>					
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h3><?php echo USER_MAIN_USER_EDIT; ?> {{user}}</h3>					
						</div>
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
						<div class='modal-body'>
						  <form action="" method="POST" name='editUserForm' id="EditUserEditForm">
							<div id='UserBody'>
								<table class='table table-bordered'>
									<thead>
										<tr>
											<th><?php echo USER_MAIN_USER_EDIT_FIRST_NAME; ?><span ng-show="editUserForm.fname.$touched ||editUserForm.fname.$dirty && editUserForm.fname.$invalid">
													  <span class = 'err' ng-show="editUserForm.fname.$error.required"><?php echo USER_MAIN_FIRST_NAME_IS_REQUIRED; ?></span></span></th>
											<th><input maxlength = "256" spl-char-not  type='text' name='fname' ng-model='fname' required  id='FirstName' class='form-control'/></th>
										</tr>
										<tr>
											<th><?php echo USER_MAIN_USER_EDIT_LAST_NAME; ?><span ng-show="editUserForm.lname.$touched ||editUserForm.lname.$dirty && editUserForm.lname.$invalid">
													  <span class = 'err' ng-show="editUserForm.lname.$error.required"><?php echo USER_MAIN_LAST_NAME_IS_REQUIRED; ?></span></span></th>
											<th><input maxlength = "256" spl-char-not type='text' name='lname' ng-model='lname' required  id='LastName' class='form-control'/></th>
										</tr>
										<tr>
											<th><?php echo USER_MAIN_USER_EDIT_ACTIVE; ?><span ng-show="editUserForm.active.$touched ||editUserForm.active.$dirty && editUserForm.active.$invalid">
													  <span class = 'err' ng-show="editUserForm.active.$error.required"><?php echo USER_MAIN_ACTIVE_SHOULD_BE_SELECTED; ?></span></span></th>
											<th>
												<select  class='form-control' ng-model='active' required name = 'active' id='Active'>											
													<option value='Y'><?php echo USER_MAIN_USER_EDIT_ACTIVE_YES; ?></option>
													<option value='N'><?php echo USER_MAIN_USER_EDIT_ACTIVE_NO; ?></option>
												</select>
											</th>
										</tr>
										<tr>
											<th><?php echo USER_MAIN_USER_EDIT_EMAIL; ?><span ng-show="editUserForm.email.$touched ||editUserForm.email.$dirty && editUserForm.email.$invalid">
													  <span class = 'err' ng-show="editUserForm.email.$error.required"><?php echo USER_MAIN_EMAIL_IS_REQUIRED; ?></span></span>
													<span style="color:Red" ng-show="editUserForm.email.$dirty&&editUserForm.email.$error.pattern"><?php echo USER_MAIN_PLEASE_ENTER_VALID_EMAIL; ?></span></th>
											<th><input maxlength = "256" type='email' ng-pattern="/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/" name='email' ng-model='email' required id='Email'  class='form-control'/></th>
										</tr>
										
									</thead>
								</table>
							</div>
						 </form>		
						</div>
						<div class='modal-footer'>
							<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo USER_MAIN_EDIT_OK_BUTTON; ?></button>
							<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo USER_MAIN_EDIT_CANCEL_BUTTON; ?></button>
							<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="editUserForm.$invalid" ng-click="editUserForm.$invalid=true;updateuser(x.id)" id="Update"><?php echo USER_MAIN_EDIT_UPDATE_BUTTON; ?></button>
						</div>
					</div>
			</div>
		</div>
<!--Edit Dynamic Otp Start -->	
	<div id='EditOtp' class='modal' role='dialog'  data-backdrop="static" data-keyboard="false">
		<div class='modal-dialog'  style='width:70%'>
			<div class='modal-content modal-md'>
				<div class="modal-header">
				<h3><?php echo USER_MAIN_OTP_USER; ?>: {{user}} - <?php echo USER_MAIN_OTP_DETAILS_BUTTON; ?> <button type="button" class="close" data-dismiss="modal">&times;</button></h3>
				</div>		
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
				<div class='modal-body' style='font-size:14px'>
					<form action="" class='border_form border_form_padding1' method="POST" name='editUserOtpForm' id="EditUserOtpForm">					
						<div class='col-lg-9 col-xs-12 col-sm-12 col-md-12'>
							<div class='row border_row'>
								<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
									<p class='no_margin'><?php echo USER_MAIN_OTP_ISSUER; ?> {{issuer}}</p>
								</div>
							</div>							
							<div class='row  border_row'>
								<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
									<p  class=''><?php echo USER_MAIN_OTP_HEX_CODE; ?> <?php echo $Hexcode; ?></p>
								</div>
								<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
									<p  class=''><?php echo USER_MAIN_OTP_TYPE; ?> {{otptype}}</p>
								</div>
							</div>
							<div class='row  border_row'>
								<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
									<p  class=''><?php echo USER_MAIN_OTP_DIGITS; ?> {{otplength}}</p>
								</div>
								<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
									<p  class=''><?php echo USER_MAIN_OTP_ALGORITHM; ?> {{algorithm}}</p>
								</div>
							</div>
							<div class='row  border_row'>
								<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
									<p  class='no_margin'><?php echo USER_MAIN_OTP_INTERVAL; ?> {{interval}}</p>
								</div>							
							</div>	
						</div>
						<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12' style='text-align: center;padding: 0px;'>
								<?php 
									$folder = QRLOC;
									$file_name  = "{{img}}";
									//$file_name ="qr_ADMIN_$name.png" ;
									$file_name2 = $folder.$file_name;
									echo "<img src='".$file_name2."' id='qrimagecode'>";?><br /><br />
									<input type='button'  class='btn btn-primary' ng-click="editUserForm.$invalid=true;sendmail(id)" value='<?php echo USER_MAIN_OTP_SEND_EMAIL_BUTTON; ?>'/>
							</div>
						</div>
						<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>						
							<div class='border_row'>
								<p  class='no_margin'><?php echo USER_MAIN_OTP_SECRET_KEY_BUTTON; ?>{{key}}<input type='button'  ng-click="editUserForm.$invalid=true;regenerate(id)" class='btn btn-primary' value='<?php echo USER_MAIN_OTP_REGENERATE_BUTTON; ?>'/></p>
							</div>
						</div>
						<div class='row  border_row'>		
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
									<?php echo USER_MAIN_OTP_PIN; ?> <input type='text' maxlength="4" required id="PinValue"  ng-model='spin'  name='spin' ng-minlength="4" class='form-control' value="" style='display:inline-block'/>
								</div>
								<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
									<input type='button' id='PinValue' class='btn btn-primary' ng-disabled="editUserOtpForm.$invalid " ng-click="editUserForm.$invalid=true;updatesotppin(id)"  value='<?php echo USER_MAIN_OTP_PIN_UPDATE_BUTTON; ?>'/>
								</div>
							</div>								
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<input type='button'  class='btn btn-primary'  ng-click="editUserForm.$invalid=true;getcurrentotp(id)" value='<?php echo USER_MAIN_OTP_GET_CURRENT_OTP; ?>'/><label id="CurrentOtp"></label><div id='currmsg'></div>
							</div>
						</div>
						
						
							
					
						<div class='clearfix'></div>
					</form>					
				</div>						
				<div class='modal-footer' style='text-align:center'>
					<a data-dismiss='modal' ><button type='button' class='btn btn-primary'><?php echo USER_MAIN_OTP_CANCEL_BUTTON; ?></button></a>
				</div>				
			</div>
		</div>	
	</div>
<!--Edit Dynamic Otp End -->
		<div id='EditSOtp' class='modal' role='dialog'>
			 <div class='modal-dialog'  style='width:70%'>
				 <div class='modal-content modal-md'>
					 <div class='modal-header'><div class='modal-close-area modal-close-df'>   
					        <a class='close' data-dismiss='modal' ><i class='fa fa-close'></i></a>
					            </div><h3><?php echo USER_MAIN_OTP_TYPES_USER; ?> {{user}} </h3>
					     </div>
						   <div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					     <div class='modal-body cusmodel'>
						  <form action="" method="POST"  name='EditUserControlForm' id="EditSUserControlForm">
					       <table class='table table-bordered'>
					       <thead>
					        <tr>
							<th style ='width:50%'><?php echo USER_MAIN_OTP_TYPES_OTP_TYPE; ?></th>
							<th style ='width:50%'><?php echo USER_MAIN_OTP_TYPES_STATIC_OTP1; ?></th>
					</tr>
					<tr>
					<label><th style ='width:50%'><?php echo USER_MAIN_OTP_TYPES_STATIC_OTP2; ?></th>
					<th style ='width:50%'><div class='col-xs-9 col-md-9 col-sm-9 col-sm-9'>
					<span ng-show="EditUserControlForm.stotp.$dirty && EditUserControlForm.stotp.$invalid"><span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
								   <input numbers-only type='text' ng-model='stotp'  spl-char-not ng-trim="false"  restrict-field="stotp" id='StaticOTPValue' name='stotp' maxlength="8" class='form-control' required />
					</div><div class='col-xs-3 col-md-3 col-sm-3 col-sm-3'>
					<button type='button' ng-click="updatesotpvalue(id)" id = '' class='btn btn-primary sotpupdate'>Update</button></div></th>
					</tr>
					<tr>
					<th><?php echo USER_MAIN_OTP_TYPES_PIN; ?></th>
					<th style ='width:50%'><div class='col-xs-9 col-md-9 col-sm-9 col-sm-9'>
					<span ng-show="EditUserControlForm.spin.$dirty && EditUserControlForm.spin.$invalid"><span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
								   <input numbers-only type='text' ng-model='spin'  spl-char-not ng-trim="false"  restrict-field="spin" id='StaticPINValue' name='spin' maxlength="4" class='form-control' required />
					   </div><div class='col-xs-3 col-md-3 col-sm-3 col-sm-3'>
					<button type='button' ng-click="updatesotppin(id)" class='btn btn-primary sotppinupdate'>Update</button></div></th>

					</tr>
					</thead>
					</table>
					</form>
					</div>
					<div class='modal-footer'>
					<a data-dismiss='modal' class='btn btn-primary' ><?php echo USER_MAIN_OTP_TYPES_CANCEL_BUTTON; ?></a>
					</div>
					</div>
		</div>			
</div> 
	<!--User Control Lock & unlock and PasswordReset Dialogue Start -->
	<div id='UserControl' class='modal' role='dialog'  data-backdrop="static" data-keyboard="false">
		<div class='modal-dialog'>
			<div class='modal-content modal-md'>
				<div class='modal-close-area modal-close-df'>
					<a class='close' data-dismiss='modal' ><i class='fa fa-close'></i></a>
				</div>							
				<div class='modal-header'><h3><?php echo USER_MAIN_CONTROL_USER; ?> - {{user}}</h3></div>
				<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
				<div class='modal-body cusmodel'>	
					<form  name='editUserControlForm' action="" method="POST" id="EditUserControlForm">				
						<div class='row' style='margin-right:0px;margin-left:0px'>
						
							<div class='col-lg-6 col-xs-12 col-md-12 col-sm-12  border_form border_form_padding1'>
								<div class='row'>							
									<div class='col-lg-6 col-xs-12 col-md-12 col-sm-12  '>
										<label><?php echo USER_MAIN_CONTROL_LOCKED; ?>
									</div>
									<div class='col-lg-6 col-xs-12 col-md-12 col-sm-12 '>
										<div ng-if="locked==='Y'"> 
											<button type='button' ng-click="unlock(id)"  class='btn btn-primary unlock'><?php echo USER_MAIN_CONTROL_UN_LOCK_BUTTON; ?></button>
										</div>
										<div ng-if= "locked==='N'">
											<button type='button'  confirmed-click="lock(id)" ng-confirm-click="<?php echo ARE_YOU_SURE_WANT_LOCK_USER; ?> - {{user}}" class='btn btn-danger lock'><?php echo USER_MAIN_CONTORL_LOCK_BUTTON; ?></button>
										</div>{{msg}}
									</div>							
								</div>
								<div class='row'>
									<div class='col-lg-6 col-xs-12 col-md-12 col-sm-12  '>							
										<label><?php echo USER_MAIN_CONTROL_LOCKED_TIME; ?></label>
									</div>
									<div class='col-lg-6 col-xs-12 col-md-12 col-sm-12 '>	
										{{ltime}}
									</div>
								</div>	
							</div>
							<div class='col-lg-1 col-xs-12 col-md-12 col-sm-12  '>
							</div>
							<div class='col-lg-5 col-xs-12 col-md-12 col-sm-12  border_form border_form_padding1'>		
								<div class='col-lg-9 col-xs-12 col-md-12 col-sm-12  '>
										<label><?php echo USER_MAIN_CONTROL_FIRST_TIME_LOGIN; ?></label> 
								</div>
								<div class='col-lg-3 col-xs-12 col-md-12 col-sm-12  '>
									<div ng-if="fstlogin==='Y'">
										<?php echo USER_MAIN_CONTROL_FIRST_TIME_LOGIN_YES; ?>
									</div>
									<div ng-if="fstlogin==='N'">
										<?php echo USER_MAIN_CONTROL_FIRST_TIME_LOGIN_NO; ?>
									</div>
								</div>
								<div style='text-align:center' class='col-lg-12 col-xs-12 col-md-12 col-sm-12  '>
								
									<div ng-if="fstlogin==='Y'"> 
										<button type='button' ng-click="changefstlogn(id)"  class='btn btn-primary unlock'><?php echo USER_MAIN_CONTROL_FIRST_TIME_LOGIN_CHANGE; ?></button>
									</div>
									<div ng-if= "fstlogin==='N'">
										<button type='button'  confirmed-click="changefstlogy(id)" ng-confirm-click="<?php echo ARE_YOU_SURE_WANT_REMOVE_FIRST_TIME_LOGIN; ?> {{user}}" class='btn btn-danger lock'><?php echo USER_MAIN_CONTROL_FIRST_TIME_LOGIN_BUTTON_CHANGE; ?></button>
									</div></div>
							</div>
						</div>
					</form>	
					<h3 class="h3_with_1margin"><?php echo USER_MAIN_CONTROL_PASSWORD_RESET; ?></h3>
					
					<form class='border_form border_form_padding1' action='' name='passwordResetForm' method='POST' id='PasswordReset'>
					   <div class='row'>
						   <div class='col-lg-12 col-xs-12 col-md-12 col-sm-12 form_col12_element'>
								<label><?php echo USER_MAIN_CONTROL_PASSWORD; ?>
								<span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
								<span ng-show="passwordResetForm.password.$dirty && passwordResetForm.password.$invalid">
								<span class = 'err' ng-show="passwordResetForm.password.$error.required"><?php echo USER_MAIN_CONTROL_PASSWORD_IS_REQUIRED; ?></span></span>
								<span  class = 'err'ng-show="passwordResetForm.password.$dirty&&passwordResetForm.password.$error.pattern"><?php echo USER_MAIN_CONTROL_LENGTH_PASSWORD; ?></span></label>
								<input autofocus="true" type='password'  ng-model='password' ng-pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d@_#*%]{8,}$/"  name='password' required id='Password' class='form-control'/>
							</div>
						</div>
						<div class='row'>
							<div class='col-lg-12 col-xs-12 col-md-12 col-sm-12 form_col12_element'>
								<label><?php echo USER_MAIN_CONTROL_RE_PASSWORD; ?><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
								<span ng-show="passwordResetForm.repassword.$touched ||passwordResetForm.repassword.$dirty && passwordResetForm.repassword.$invalid">
								<span class = 'err' ng-show="passwordResetForm.repassword.$error.required"><?php echo USER_MAIN_CONTROL_RE_PASSWORD_IS_REQUIRED; ?></span></span>
								<span class = 'err' ng-show="(passwordResetForm.password.$modelValue !== passwordResetForm.repassword.$modelValue) "><?php echo USER_MAIN_CONTROL_RE_PASSWORD_DOESNOT_MATCH; ?>.</span>
								<span  class = 'err'ng-show="passwordResetForm.repassword.$dirty&&passwordResetForm.repassword.$error.pattern"><?php echo USER_MAIN_CONTROL_LENGTH_RE_PASSWORD; ?></span></label>
								<input type='password' ng-pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d@_#*%]{8,}$/"  ng-model='repassword' required name='repassword'  id='RePassword' class='form-control'/>
							</div>
						</div>
						<div class='row'>
							<div class='col-lg-12 col-xs-12 col-md-12 col-sm-12 form_col12_element' style='text-align:center'>
								<button type='button' class='btn btn-primary' ng-disabled="(passwordResetForm.$invalid) || (password !== repassword)" ng-click='editUserForm.$invalid=true;uppassreset(id)' ><?php echo USER_MAIN_CONTROL_UPDATE_BUTTON; ?></button>
								<button type='reset' id = 'Reset' class='btn btn-primary '><?php echo USER_MAIN_CONTROL_RESET_BUTTON; ?></button>
							</div>
						</div>
					</form>											
				</div>
				<div class='modal-footer'>
					<a data-dismiss='modal' class='btn btn-primary' ><?php echo USER_MAIN_CONTROL_CANCEL_BUTTON; ?></a>
				</div>		
			</div>
		</div>	
	</div>
	<!--User Control Lock & unlock and PasswordReset Dialogue End -->
	<!--Otp Change Dialogue Start -->
	<div id='OtpChange' class='modal' role='dialog'  data-backdrop="static" data-keyboard="false">
		<div class='modal-dialog'>
			<div class='modal-content modal-md'>
					<div class='modal-header'><h3><?php echo USER_MAIN_DYNAMIC_USER; ?> {{user}}<a class='close' data-dismiss='modal' ><i class='fa fa-close'></i></a></h3></div>
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					<form action="" method="POST" id="EditOtpChangeUserControlForm">
					<div id='OtpTypeBody'>					
						<table class='table table-bordered'>
							<thead>
								<tr>
									<th style ='width:50%'><?php echo USER_MAIN_DYNAMIC_OTP_TYPE; ?></th>
									<th style ='width:50%'>
									<input class="form-check-input" type="radio" ng-model = 'otptype' required name="otptype" id="StaticOtpRadio" value="N"  />
									<label class="form-check-label" for="StaticOtpRadio">
									<?php echo USER_MAIN_DYNAMIC_STATIC_OTP; ?>
									</label>
									<input class="form-check-input" type="radio" ng-model = 'otptype' required name="otptype" id="DynamicOtpRadio" value="Y"  />
									<label class="form-check-label" for="DynamicOtpRadio">
									<?php echo USER_MAIN_DYNAMIC_DYNAMIC_OTP; ?> </label>									
									</th>
								</tr>
								<tr ng-hide="otptype === 'N'" style='border:none'>
									<th><?php echo USER_MAIN_DYNAMIC_SEND_MAIL; ?></th>
									<th >
									<input type='checkbox' ng-model='mail' name='mail' required />										
									</th>
								</tr>
							</thead>
						</table>
					</div></div>
					<div class='modal-footer' style='text-align:Center'>
						<input type='button' class='btn btn-primary' ng-click="editUserForm.$invalid=true;otytypeupdate(id)" ng-hide='isHide' value='<?php echo USER_MAIN_DYNAMIC_UPDATE_BUTTON; ?>'/>
						<input type='button' data-dismiss='modal' class='btn btn-primary' value='<?php echo USER_MAIN_DYNAMIC_CANCEL_BUTTON; ?>' ng-hide='isHideCancel'/>
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo USER_MAIN_DYNAMIC_OK_BUTTON; ?></button>
					</div>	
				</form>			
			</div>
		</div>	
	</div>
	<div id='userrestrict' class='modal' role='dialog'  data-backdrop="static" data-keyboard="false">
		<div class='modal-dialog'>
			<div class='modal-content modal-md'>
				<div class='modal-header'><h3><?php echo USER_MAIN_DYNAMIC_USER; ?> {{user}}<a class='close' data-dismiss='modal' ><i class='fa fa-close'></i></a></h3></div>
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					 <form action="" method="POST" id="userrestrictform">
						<div id='userrestrictBody'>					
						<table class='table table-bordered'>
							<thead>
								<tr>
									<th style ='width:50%'><?php echo USER_USER_RESTRICT; ?></th>
									<th style ='width:50%'>
									<input class="form-check-input" type="radio" ng-model = 'accessrestrict' required name="accessrestrict" id="userrestrictyesradio" value="Y"  />
									<label class="form-check-label" for="userrestrictYradio">
									<?php echo USER_USER_RESTRICT_YES; ?>
									</label>
									<input class="form-check-input" type="radio" ng-model = 'accessrestrict' required name="accessrestrict" id="userrestrictnoradio" value="N"  />
									<label class="form-check-label" for="userrestrictnradio">
									<?php echo USER_USER_RESTRICT_NO; ?>							
									</th>
								</tr>
								
							</thead>
						</table>
					</div></form>	
					</div>
					<div class='modal-footer' style='text-align:Center'>
						<input type='button' class='btn btn-primary' confirmed-click="userrestrictupdate(id)" ng-confirm-click="<?php echo ARE_U_SURE_WANT_CHANGE_USER_ACCESS; ?>"  ng-hide='isHide' value='<?php echo USER_MAIN_DYNAMIC_UPDATE_BUTTON; ?>'/>
						<input type='button' data-dismiss='modal' class='btn btn-primary' value='<?php echo USER_MAIN_DYNAMIC_CANCEL_BUTTON; ?>' ng-hide='isHideCancel'/>
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo USER_MAIN_DYNAMIC_OK_BUTTON; ?></button>
					</div>	
				</div>
		</div>	
	</div>
	<!--USER RESTRICT START -->
	<div id='posaccess' class='modal' role='dialog'  data-backdrop="static" data-keyboard="false">
		<div class='modal-dialog'>
			<div class='modal-content modal-md'>
				<div class='modal-header'><h3><?php echo USER_MAIN_DYNAMIC_USER; ?> {{user}}<a class='close' data-dismiss='modal' ><i class='fa fa-close'></i></a></h3></div>
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					   <form action="" method="POST" id="posaccessform">
						<div id='posaccessBody'>					
						<table class='table table-bordered'>
							<thead>
								<tr>
									<th style ='width:50%'><?php echo USER_USER_POS_ACCESS; ?></th>
									<th style ='width:50%'>
									<input class="form-check-input" type="radio" ng-model = 'posaccess' required name="posaccess" id="posaccessyesradio" value="Y"  />
									<label class="form-check-label" for="posaccessYradio">
									<?php echo USER_USER_POS_ACCESS_YES; ?>
									</label>
									<input class="form-check-input" type="radio" ng-model = 'posaccess' required name="posaccess" id="posaccessnoradio" value="N"  />
									<label class="form-check-label" for="posaccessnradio">
									<?php echo USER_USER_POS_ACCESS_NO; ?>										
									</th>
								</tr>
								
							</thead>
						</table>
					</div> </form>
					</div>
					<div class='modal-footer' style='text-align:Center'>
						<input type='button' class='btn btn-primary' confirmed-click="posaccessupdate(id)" ng-confirm-click="<?php echo ARE_U_SURE_WANT_CHANGE_POS_ACCESS; ?>"  ng-hide='isHide' value='<?php echo USER_MAIN_DYNAMIC_UPDATE_BUTTON; ?>'/>
						<input type='button' data-dismiss='modal' class='btn btn-primary' value='<?php echo USER_MAIN_DYNAMIC_CANCEL_BUTTON; ?>' ng-hide='isHideCancel'/>
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo USER_MAIN_DYNAMIC_OK_BUTTON; ?></button>
					</div>	
				</div>
		</div>	
	</div>
		<!--USER RESTRICT End -->
	
	<!--Otp Change Dialogue End -->
	<!-- Start Create User Dialogue -->
	<div id='AddUserDialogue' class='modal' role='dialog'  data-backdrop="static" data-keyboard="false">		
		<div class='modal-dialog' style='width: 100%;'>
			<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h3><?php echo USER_MAIN_CREATE_USER; ?></h3></div>
			<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
			<div class='modal-content modal-md'>
				<div class='modal-body' >
					<div class="loading-spiner-holder" data-loading1 style='text-align:center'><div style='text-align:center' class="loading-spiner"><img style='text-align:center' style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
						<form method="post" class="signin" name='userCreateForm' id="createBoxFormId" action="">
						<div id='UserCreateBody'  ng-hide='isLoader'>
						
					<table class='table table-bordered' id='CreateTable'>
						<tbody>
							<tr >
							<td colspan = '1' style='width:33%'>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
							<label class="username lab"><span><?php echo USER_MAIN_CREATE_USER_NAME; ?></span><span style='color:red;font-size:14px;padding-left: 2px;'>*</span><span class="check" ng-show='loadgif' ><img src="../common/images/ajax-loading.gif" /></span>
							<span ng-show="userCreateForm.userName.$touched ||userCreateForm.userName.$dirty && userCreateForm.userName.$invalid">
							<span class = 'err' ng-show="userCreateForm.userName.$error.required">
							<br><?php echo USER_MAIN_CREATE_USER_NAME_IS_REQUIRED; ?>
							<span style="color:Red" ng-show="userCreateForm.userName.$dirty && userCreateForm.userName.$error.minlength"> <?php echo MIN_10_CHARACTERS_REQUIRED; ?> </span></span></span></label><br/>
							<input id="UserName" name="userName" style='width:240px;' ng-keypress = "checkuservalid()"  spl-char-not ng-trim="false" restrict-field="userName"  maxlength='25' class='form-control' ng-style = 'colstyle'  ng-model = 'userName' required type="text" autofocus />
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'><br />
									<button type="button" class="btn btn-primary"  style="cursor: pointer;margin-left: 50%;margin-top: 2%;" ng-disabled="isGoDisbled"  ng-click="usercheck($event, userName)" ng-hide='isHideGo'   id="GO"><?php echo PRE_APPLICATION_VIEW_USER_NAME_BUTTON_GO; ?></button>
								
								</div>
							</td>
								<td colspan = '2'>
								</td>
							</tr>

							<tr style='width:33%'>
								<td>
									<label class="firstname lab"><span><?php echo USER_MAIN_CREATE_FIRST_NAME; ?></span><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
									<span ng-show="userCreateForm.firstname.$touched ||userCreateForm.firstname.$dirty && userCreateForm.firstname.$invalid">
									<span class = 'err' ng-show="userCreateForm.firstname.$error.required"><?php echo USER_MAIN_CREATE_FIRST_NAME_IS_REQUIRED; ?>.</span></span>
									</label> <br/>
									<input id="FirstName" ng-model = 'firstname' ng-disabled='usrform' required  maxlength='30' name="firstname" class='form-control' type="text" />
								</td>

								<td>
									<label class="lastname lab"><span><?php echo USER_MAIN_CREATE_LAST_NAME; ?></span><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
									<span ng-show="userCreateForm.lastname.$touched ||userCreateForm.lastname.$dirty && userCreateForm.lastname.$invalid">
									<span class = 'err' ng-show="userCreateForm.lastname.$error.required"><?php echo USER_MAIN_CREATE_LAST_NAME_IS_REQUIRED; ?>.</span></span>
									</label> <br/>
									<input id="LastName" name="lastname" ng-disabled='usrform' ng-model = 'lastname' maxlength='30' required class='form-control' type="text" />
								</td>
							<td>
							<label class="profile lab"><span><?php echo USER_MAIN_CREATE_PROFILE; ?></span><span style='color:red;font-size:14px;padding-left: 2px;'>*</span><br/>
									<span ng-show="userCreateForm.profile.$touched ||userCreateForm.profile.$dirty && userCreateForm.profile.$invalid">
									<span class = 'err' ng-show="userCreateForm.profile.$error.required"><?php echo USER_MAIN_CREATE_PROFILE_SHOULD_BE_SELECTED; ?>.</span></span></label> 
									<select ng-model="profile" ng-disabled='usrform'  class='form-control' name = 'profile' id='Profile' required>											
										<option value=''><?php echo USER_MAIN_CREATE_SELECT_PROFILE; ?></option>
										<option ng-repeat="pro in profiles" value="{{pro.id}}">{{pro.name}}</option>
									</select>
								
							</td>
							</tr>

							<tr style='width:33%'>
								<td><label class="password1 lab"><span><?php echo USER_MAIN_CREATE_PASSWORD1; ?></span><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
									<span ng-show="userCreateForm.sysuserpassword.$touched ||userCreateForm.sysuserpassword.$dirty && userCreateForm.sysuserpassword.$invalid">
									<span class = 'err' ng-show="userCreateForm.sysuserpassword.$error.required"><?php echo USER_MAIN_CREATE_PASSWORD_IS_REQUIRED; ?>.</span></span>
									<span  class = 'err'ng-show="userCreateForm.sysuserpassword.$dirty&&userCreateForm.sysuserpassword.$error.pattern"><?php echo USER_MAIN_CREATE_LENGTH_PASSWORD; ?>.</span>
									</label> <br/>
									<input id="Password1"  ng-disabled='usrform' ng-minlength="8" maxlength='256'  ng-model = 'sysuserpassword' required name="sysuserpassword" class='form-control allow' type="password"  ng-pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d@_#*%]{8,}$/"/>
								</td>

								<td >
									<label class="repassword lab"><span><?php echo USER_MAIN_CREATE_RE_PASSWORD; ?></span><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
									<span ng-show="userCreateForm.sysuserrepassword.$touched ||userCreateForm.sysuserrepassword.$dirty && userCreateForm.sysuserrepassword.$invalid">
									<span class = 'err' ng-show="userCreateForm.sysuserrepassword.$error.required"><?php echo USER_MAIN_CREATE_RE_PASSWORD_IS_REQUIRED; ?>.</span></span>
									<span class = 'err' ng-show="(userCreateForm.sysuserpassword.$modelValue !== userCreateForm.sysuserrepassword.$modelValue) "><?php echo USER_MAIN_CREATE_RE_PASSWORD_DOESNOT_MATCH; ?>.</span>
									<span  class = 'err'ng-show="userCreateForm.sysuserrepassword.$dirty&&userCreateForm.sysuserrepassword.$error.pattern"><?php echo USER_MAIN_CREATE_LENGTH_RE_PASSWORD; ?>.</span>
									</label> <br/>
									<input id="Repassword" ng-disabled='usrform' ng-model = 'sysuserrepassword' maxlength='256' required  name="sysuserrepassword" class='form-control' type="password"  ng-pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d@_#*%]{8,}$/"/>
								</td>
								<td>
								<label class="active lab"><span><?php echo USER_MAIN_CREATE_ACTIVE; ?></span><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
									<span ng-show="userCreateForm.active.$touched ||userCreateForm.active.$dirty && userCreateForm.active.$invalid">
									<span class = 'err' ng-show="userCreateForm.active.$error.required"><?php echo USER_MAIN_CREATE_ACTIVE_SHOULD_BE_SELECTED; ?></span></span>
								</label> <br/>
								<select id='Active2' ng-model = 'active' ng-disabled='usrform' required  class = 'form-control' name='active'>
									<option value=''><?php echo USER_MAIN_CREATE_SELECT_ACTIVE; ?></option>
									<option value='Y'><?php echo USER_MAIN_CREATE_ACTIVE_YES; ?></option>
									<option value='N'><?php echo USER_MAIN_CREATE_ACTIVE_NO; ?></option>
								</select>
								</td>	
							</tr>	
							
							<tr style='width:33%'>
								<td><label class="password1 lab"><span><?php echo USER_MAIN_CREATE_START_DATE; ?></span></label> <br/>
									<input id="startDate" ng-disabled='usrform' name="startDate" ng-model = 'startDate' class='form-control' type="date" />
								</td>
								<td ><label class="repassword lab"><span><?php echo USER_MAIN_CREATE_END_DATE; ?></span></label><br/>
									<input id="endDate" ng-disabled='usrform' name="endDate" ng-model = 'endDate' class='form-control' type="date"/>
								</td>
								<td colspan='3'><label class="email lab"><span><?php echo USER_MAIN_CREATE_EMAIL; ?></span><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
									<span ng-show="userCreateForm.email.$touched ||userCreateForm.email.$dirty && userCreateForm.email.$invalid">
									<span class = 'err' ng-show="userCreateForm.email.$error.required"><?php echo USER_MAIN_CREATE_EMAIL_IS_REQUIRED; ?>.</span></span>
									<span style="color:Red" ng-show="userCreateForm.email.$dirty&&userCreateForm.email.$error.pattern"><?php echo USER_MAIN_CREATE_PLZ_ENTER_VALID_EMAIL; ?></span>
								</label> <br/>
								<input id="email2" ng-pattern="/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/" name="email" ng-disabled='usrform' ng-model = 'email' required class='form-control' type="email"  required />
								</td>
							</tr>	
							
						</tbody>
					</table>					
								
				</div>
				<div style='text-align:center'>
					<input type="button"  id="CreateSysUser" ng-click="editUserForm.$invalid=true;createuser()" ng-disabled = "(userCreateForm.$invalid) || (sysuserpassword  != sysuserrepassword)"  ng-hide="isHide" class='btn btn-primary' value='<?php echo USER_MAIN_USER_CREATE_BUTTON; ?>' />
					<input type="reset"  id="ResetCreate"   class='btn btn-primary' value='<?php echo USER_MAIN_USER_CREATE_RESET; ?>' ng-hide='isHideReset'/>			
					<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo USER_MAIN_USER_CREATE_OK; ?></button>
				</div>
				</form> 
				</div>
			</div>			
		</div> 
	</div>
<!-- End Create User Dialogue -->
	<div id='UserAccsess' class='modal' role='dialog'  data-backdrop="static" data-keyboard="false">
		<div class='modal-dialog'>
			<div class='modal-content modal-md'>
				<div class='modal-header'><h3><?php echo USER_MAIN_DYNAMIC_USER; ?> <a class='close' data-dismiss='modal' ><i class='fa fa-close'></i></a></h3></div>
					  <div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					 <form action="" method="POST" id="UserAccsessForm">
						<div id='accessBody'>					
							<table class='table table-bordered'>							
								<tr>
									<td>
									<input class="form-check-input" type='checkbox' ng-model = 'weekendaccess'  name="week" id="weekaccess" value="Y"  />									
									<label class="form-check-label" for="weekaccess"><?php echo USER_MAIN_WEEKEND_ACCESS; ?></label>
									
									</td>
								</tr>
								<tr>
									<td>
									<input class="form-check-input" type="checkbox" ng-model = 'weekendcontrol'  name="week" id="weekcontrol"  value="Y" />
									<label class="form-check-label" for="weekcontrol"><?php echo USER_MAIN_WEEKEND_CONTROL; ?></label>									
									</td>
								</tr>
								
								<tr  ng-show="weekendcontrol" >
									<td> 
									<?php echo USER_MAIN_START_TIME; ?> :<input ng-model='stime' type="time" name='stime' required class='form'>									
									</td>									
								</tr>
								<tr  ng-show="weekendcontrol" >
									
									<td> 
									<?php echo USER_MAIN_END_TIME; ?> :<input ng-model='etime' type="time" name='etime' required class='form'>
									</td>
									
								</tr>
							
						</table>
					</div></form>	
					</div>
					<div class='modal-footer' style='text-align:Center'>
						<input type='button' class='btn btn-primary' ng-click="editUserForm.$invalid=true;updateaccess(id)" ng-hide='isHide' value='<?php echo USER_MAIN_DYNAMIC_UPDATE_BUTTON; ?>'/>
						<input type='button' data-dismiss='modal' class='btn btn-primary' value='<?php echo USER_MAIN_DYNAMIC_CANCEL_BUTTON; ?>' ng-hide='isHideCancel'/>
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo USER_MAIN_DYNAMIC_OK_BUTTON; ?></button>
					</div>	
				</div>
		</div>	
	</div>
	
<!--End-->		
	</div>
</div>
<script type="text/javascript">
function AllTables(){
	TestTable1();
	TestTable2();
	TestTable3();
	//LoadSelect2Script(MakeSelect2);
}
$(document).ready(function() {
	LoadDataTablesScripts(AllTables);
	// $.fn.dataTableExt.sErrMode = 'throw' ;
	
$("#AddUserDialogue, #EditUser, #OtpChange,#userrestrict, #UserAccsess, #posaccess").on("click","#Ok",function() {
		window.location.reload();
	});
	  /* window.alert = function() {}; alert = function() {}; */ 
		});
</script>
