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
#AddINFODialogue .table > tbody > tr > td {
	border:none;
}
</style>
<div ng-controller='passwrdCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#"><?php echo ACCESS_PASSWORD_MAIN_HEADING1; ?></a></li>
			<li><a href="#"><?php echo ACCESS_PASSWORD_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo ACCESS_PASSWORD_MAIN_HEADING3; ?></span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding">	
			<div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>
				<div id='passwrdBody'>
					<form name='controlForm' method='POST' action=''>									
						<div class='row appcont'>	
							<label><?php echo ACCESS_PASSWORD_USER_NAME; ?></label>
							<input type="text" id="UserName" name='username' ng-model='username' class="form-control" placeholder='<?php echo ACCESS_PASSWORD_PLACE_HOLDER_USER_NAME; ?>' required="required" autofocus />
						</div>
						<div class='row appcont'>	
							<label><?php echo ACCESS_PASSWORD_PASSWORD_TYPE; ?></label>
									<select ng-model="passwordtype" class='form-control' placeholder='<?php echo ACCESS_PASSWORD_PLACE_HOLDER_PASSWORD_TYPE; ?>' name = 'passwordtype' id='passwordtype' required="required" required >
                               			<option value=''><?php echo ACCESS_PASSWORD_SELECT_TYPE; ?></option>
										<option value='L'><?php echo ACCESS_PASSWORD_LOGIN_PASSWORD; ?></option>
										<option value='T'><?php echo ACCESS_PASSWORD_TRANSACTION_PASSWORD; ?></option>
										</select>
									</div>
						<div class='row appcont'>	
							<label><?php echo ACCESS_PASSWORD_NEW_PASSWORD; ?><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
								<span ng-show="controlForm.newpassword.$dirty && controlForm.newpassword.$invalid">
								<span class = 'err' ng-show="controlForm.newpassword.$error.required"><?php echo ACCESS_PASSWORD_IS_REQUIRED; ?></span></span>
								<span  class = 'err'ng-show="controlForm.newpassword.$dirty&&controlForm.newpassword.$error.pattern"><?php echo ACCESS_PASSWORD_LENGTH_REQUIRED; ?></span></label>
							<input type="text"  required id="NewPassword" name='newpassword' ng-model='newpassword' ng-pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d@_#*%]{8,}$/"  class="form-control" placeholder='<?php echo ACCESS_PASSWORD_PLACE_HOLDER_NEW_PASSWORD; ?>' required="required" autofocus />
						</div>	
						<div class='row appcont'>	
							<label><?php echo ACCESS_PASSWORD_RENEW_PASSWORD; ?><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
								<span ng-show="controlForm.renewpassword.$touched ||controlForm.renewpassword.$dirty && controlForm.renewpassword.$invalid">
								<span class = 'err' ng-show="controlForm.renewpassword.$error.required"><?php echo ACCESS_RE_PASSWORD_IS_REQUIRED; ?>.</span></span>
								<span class = 'err' ng-show="(controlForm.newpassword.$modelValue !== controlForm.renewpassword.$modelValue) "><?php echo ACCESS_RE_PASSWORD_DOES_NOT_MATCH; ?></span>
								<span  class = 'err'ng-show="controlForm.renewpassword.$dirty&&controlForm.renewpassword.$error.pattern"><?php echo ACCESS_RE_PASSWORD_LENGTH_REQUIRED; ?></span></label>
							<input type="text" id="ReNewPassword"  required name='renewpassword' ng-model='renewpassword'  ng-pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d@_#*%]{8,}$/" class="form-control" placeholder='<?php echo ACCESS_PASSWORD_PLACE_HOLDER_RE_NEW_PASSWORD; ?>' required="required" autofocus />
						</div>
						<div class='row appcont' style='text-align:center'>	
							<button type='button' class='btn btn-primary' data-dismiss='modal'  id='Ok' ng-hide='isHideOk' ><?php echo ACCESS_PASSWORD_OK_BUTTON; ?></button>						
							<button type="button" class="btn btn-primary"   data-dismiss='modal' ng-disabled="(controlForm.$invalid) || (newpassword !== renewpassword)"   ng-click='controlForm.$invalid=true;change()' ng-hide='isHide'  id="Query"><?php echo ACCESS_PASSWORD_QUERY_BUTTON; ?></button>
							<button type="button" class="btn btn-primary"   id="Refresh"><?php echo ACCESS_PASSWORD_REFRESH_BUTTON; ?></button>
						</div>
					</form>
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
}
$(document).ready(function() {
  LoadDataTablesScripts(AllTables);
 // WinMove();
	$("#controlDialogue, #infoEditDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	$("#Refresh, #Ok").click(function() {
		window.location.reload();
	});
	$("#EditINFODialogue, #AddINFODialogue").on("keypress",".sc", function (event) {
		    var regex = new RegExp("^[a-zA-Z0-9 \b:/.~!@#$*_-]+$");
 		    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
 		    if (!regex.test(key)) {
 		       event.preventDefault();
 		       return false;
 		    }
 		});
});
</script>