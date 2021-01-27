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
<div ng-controller='passwordChgCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!ctrpwd"><?php echo CONTROL_MAIN_HEADING1; ?></a></li>
			<li><a href="#!ctrpwd"><?php echo CONTROL_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo CONTROL_MAIN_HEADING3; ?></span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			
		<div ng-app="" class="box-content" style='padding: 0px 10px !important;'>	             
			<div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>
				
					<form name='controlForm' id='ControlForm' method='POST' action=''>		
						<div id='passChangeBody'>					
						<div class='row appcont'>	
							<label><?php echo CONTROL_USER_NAME; ?> : <?php echo $_SESSION['user_name'] ?></label>
							
						</div>
					     	<div class='row appcont'>	
							<label><?php echo CONTROL_PASSWORD_TYPE; ?></label>
									<select ng-model="passwordtype" class='form-control' placeholder='<?php echo CONTROL_PLACE_HOLDER_PASSWORD_TYPE ?>' name = 'passwordtype' id='passwordtype' required="required" required >
                               			<option value=''><?php echo CONTROL_SELECT_TYPE; ?></option>
										<option value='L'><?php echo CONTROL_LOGIN_PASSWORD; ?></option>
										<option value='T'><?php echo CONTROL_TRANSACTION_PASSWORD; ?></option>
										</select>
							</div>
						
						<div class='row appcont'>	
							<label style="margin-left:0%"><?php echo CONTROL_OLD_PASSWORD; ?></label>
							<div style='display:flex !important'>
							<input type="password" id="CurrentPassword" name='curpassword' ng-model='curpassword' class="form-control pwd" placeholder='<?php echo CONTROL_PLACE_HOLDER_CURRENT_PASSWORD;  ?>' style="cursor: auto;width: 97%;margin-left: 0%;" required="required" autofocus />
							  <span class="input-group-btn">
								<button class="btn btn-default form-control reveal" tabindex="-1" style='cursor: pointer;border-top-right-radius: 20%;width: 35px;   border-bottom-right-radius: 20%;' type="button"><i class="glyphicon glyphicon-eye-open"></i></button>
							  </span> 
							</div>		
						</div>	
						<div class='row appcont'>	
							<label><?php echo CONTROL_NEW_PASSWORD; ?><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
								<span ng-show="controlForm.newpassword.$dirty && controlForm.newpassword.$invalid">
								<span class = 'err' ng-show="controlForm.newpassword.$error.required"><?php echo CONTROL_PASSWORD_IS_REQUIRED; ?></span></span>
								<span  class = 'err'ng-show="controlForm.newpassword.$dirty&&controlForm.newpassword.$error.pattern"><?php echo CONTROL_PASSWORD_LENGTH_IS_REQUIRED; ?></span></label>
							<input type="password"  required id="NewPassword" name='newpassword' ng-model='newpassword' ng-pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d@_#*%]{8,}$/"  style="cursor: auto;width: 98%;margin-left: 0%;" class="form-control pwd1" placeholder='<?php echo CONTROL_PLACE_HOLDER_NEW_PASSWORD; ?>' required="required" autofocus /> <span class="input-group-btn">	
								<button class="btn btn-default revelpass" tabindex="-1" style="cursor: pointer;margin-left: 97%;margin-top: -35px;height: 35px;"  type="button"><i class="glyphicon glyphicon-eye-open"></i></button>
							  </span>   
							
						</div>	
						<div class='row appcont'>	
							<label><?php echo CONTROL_RENEW_PASSWORD; ?><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
								<span ng-show="controlForm.renewpassword.$touched ||controlForm.renewpassword.$dirty && controlForm.renewpassword.$invalid">
								<span class = 'err' ng-show="controlForm.renewpassword.$error.required">Re-Password is Required</span></span>
								<span class = 'err' ng-show="(controlForm.newpassword.$modelValue !== controlForm.renewpassword.$modelValue) "><?php echo CONTROL_RE_PASSWORD_DOESNOT_MATCH; ?></span>
								<span  class = 'err'ng-show="controlForm.renewpassword.$dirty&&controlForm.renewpassword.$error.pattern"><?php echo CONTROL_RE_PASSWORD_LENGTH_IS_REQUIRED; ?></span></label>
							<input type="password" id="ReNewPassword"  required name='renewpassword' ng-model='renewpassword'  ng-pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d@_#*%]{8,}$/" style="cursor: auto;width: 98%;margin-left: 0%;" class="form-control pwd2"  placeholder='<?php echo CONTROL_PLACE_HOLDER_RE_NEW_PASSWORD; ?>' required="required" autofocus />
							<span class="input-group-btn">	
								<button class="btn btn-default revelrepass" tabindex="-1" style="cursor: pointer;margin-left: 97%;margin-top: -35px;height: 35px;"  type="button"><i class="glyphicon glyphicon-eye-open"></i></button>
							  </span>   
						</div>
						</div>
						<div class='row appcont' style='text-align:center'>	
							<button type='button' class='btn btn-primary'  id='Ok' ng-hide='isHideOk'><?php echo CONTROL_OK_BUTTON; ?></button>					
							<button type="button" class="btn btn-primary" ng-disabled="(controlForm.$invalid) || (newpassword !== renewpassword)"   ng-click='change()' ng-hide='isHide'  id="Query"><?php echo CONTROL_QUERY_BUTTON; ?></button>
							<button type="button" class="btn btn-primary" ng-hide='isHideReset'  id="Refresh"><?php echo CONTROL_REFRESH_BUTTON; ?></button>
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
}
$(".reveal").on('click',function() {
    var $pwd = $(".pwd");
    if ($pwd.attr('type') === 'password') {
        $pwd.attr('type', 'text');
    } else {
        $pwd.attr('type', 'password');
    }
});
$(".revelpass").on('click',function() {
    var $pwd = $(".pwd1");
    if ($pwd.attr('type') === 'password') {
        $pwd.attr('type', 'text');
    } else {
        $pwd.attr('type', 'password');
    }
});
 $(".revelrepass").on('click',function() {
    var $pwd = $(".pwd2");
    if ($pwd.attr('type') === 'password') {
        $pwd.attr('type', 'text');
    } else {
        $pwd.attr('type', 'password');
    }
});

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