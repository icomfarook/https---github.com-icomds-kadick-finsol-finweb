<?php
include('../common/admin/configmysql.php');
include("../common/admin/finsol_crypt.php");
include("../common/admin/finsol_otp_ini.php");
require_once('../common/otp/otphp.php');
//include('reqchk.php');
error_reporting(0);
if(isset($_POST['login'])) {
	set_time_limit(120);
	session_start();
	$uname = trim($_POST['username']);
	$password = trim($_POST['password']);
	$token = trim($_POST['token']);
	$otp_len = OTP_USER_DIGITS;
	$admin_attempt_limit = ADMIN_ATTEMPT_LIMIT;
	if ( empty($_SESSION['captcha_code'] ) || strcasecmp($_SESSION['captcha_code'], $_POST['captcha_code']) != 0 ){  
		$msg="Captcha is incorrect..!";// Captcha verification is incorrect.		
	} else if ( empty($uname) || strlen($uname) == 0  ) {
			$msg="Username is required.";
	} else if ( empty($password) || strlen($password) == 0  ) {
		$msg="Password is required.";
	} else if ( empty($token) || strlen($token) != $otp_len+4){
			error_log("Token Length = ".strlen($token));
			error_log("OTP_USER_DIGITS = ".$otp_len+4);
			$msg="Invalid Token code..!";
	} else {
		$user_check_query = "SELECT temp_password, first_time_login, user_id, user_name, password, active, COALESCE(invalid_attempt,0) as invalid_attempt, locked, access_restrict, pos_access FROM user WHERE UPPER(user_name) = UPPER('".$uname."') and profile_id in (51, 52) limit 1";
		error_log("user_check_query".$user_check_query);
		$user_check_result = mysqli_query($con,$user_check_query);
		if ( $user_check_result ) {
			$user_check_count = mysqli_num_rows($user_check_result);
			if($user_check_count > 0){
				$row = mysqli_fetch_array($user_check_result);
				$temp_password = $row['temp_password'];
				$first_time_login = $row['first_time_login'];
				$user_id = $row['user_id'];
				$username = $row['user_name'];
				$first_name = $row['first_name'];
				$last_name = $row['last_name'];
				$pin = $row['pin'];
				$hash_password1 = $row['password'];
				$user_active = $row['active'];
				$invalid_attempt = $row['invalid_attempt'];
				$locked = $row['locked'];
				$access_restrict = $row['access_restrict'];
				$pos_access = $row['pos_access'];
				if ( $user_active == "Y" ) {
					if ( $locked != "Y" && $invalid_attempt <= $admin_attempt_limit ) {
						if($first_time_login == "N") {
							$successpassword = ckdecrypt($password, $hash_password1);						
						}
						else {
							$successpassword = ckdecrypt($password, $temp_password);
						}
						if($successpassword) {
							if($pos_access == "N" || $pos_access == "B" )	{							
								$user_otp_query = "SELECT b.otp_dynamic, b.otp_value, b.key_string, b.pin, b.pin_flag FROM user a, user_otp b WHERE a.user_id = b.user_id and UPPER(a.user_name) = UPPER('".$uname."') and a.user_id = '".$user_id."' and a.active = 'Y' and a.profile_id in (51,52) limit 1";
								$user_otp_result = mysqli_query($con,$user_otp_query);
								if(!$user_otp_result) {
									error_log("@@@@username = ".$username." User DB3 read failure: ".mysqli_error($con));
									die("User DB3 read failure: ".mysqli_error($con));
								}else {
									$tokens = str_split($token, $otp_len);
									$tokensplit0 = $tokens[0];
									$tokensplit1 = $tokens[1];
									error_log("@@@@username = ".$uname.", token = ".$token.", tokensplit0 = ".$tokensplit0.", tokensplit1 = ".$tokensplit1);
										
									$user_otp_row = mysqli_fetch_assoc($user_otp_result);
									$otp_dynamic = $user_otp_row['otp_dynamic'];
									$otp_value = $user_otp_row['otp_value'];
									$keystring = $user_otp_row['key_string'];
									$pin = $user_otp_row['pin'];
									$pin_flag = $user_otp_row['pin_flag'];
									$currentdate = date('Y-m-d');
									$pin_sort = substr($pin,0,4);
									$formatted_otp_value = "";							
									if ( $otp_dynamic == "N" ) {
										$formatted_otp_value = $otp_value;
										error_log("Static OTP = ".$formatted_otp_value);
									}else {
										$totp = new \OTPHP\TOTP($keystring, Array('interval'=>OTP_USER_PERIODS, 'digits'=>OTP_USER_DIGITS, 'digest'=>OTP_ALGORITHM));
										$totp_value =  $totp->now();
										$formatted_otp_value = str_pad($totp_value, $otp_len, '0', STR_PAD_LEFT);
										error_log("Generated OTP = ".$totp_value.", formatted OTP = ".$formatted_otp_value);
									}
									if( $formatted_otp_value == $tokensplit0 ){
										error_log("successful token validation");
										error_log("pin_flag = ".$pin_flag.", pin_sort = ".$pin_sort.", tokensplit1 = ".$tokensplit1);
										if ( $pin_flag == "N" || ( $pin_flag == "Y" && $pin_sort == $tokensplit1 ) ) {
											error_log("first_time_login = ".$first_time_login);
											if($first_time_login == "N") {
												$user_sec_check_query = "SELECT a.user_id, a.user_name, a.first_name, a.last_name, a.active,a.start_date ,a.expiry_date, a.last_login, a.invalid_attempt,a.email, a.profile_id, a.locked, b.profile_name,c.agent_code,c.agent_name,c.parent_code,c.local_govt_id, c.country_id, c.state_id,c.sub_agent,c.party_category_type_id, b.auth_id, c.language_id,c.group_id,c.group_type from user a, profile b, agent_info c WHERE a.profile_id = b.profile_id and a.user_name = '".$uname."' and c.user_id = a.user_id and a.profile_id in (51,52) limit 1";
												$user_sec_check_result = mysqli_query($con,$user_sec_check_query);						
												if ( $user_sec_check_result ) {
													$user_sec_check_count = mysqli_num_rows($user_sec_check_result);
													if($user_sec_check_count > 0) {														
														$row = mysqli_fetch_array($user_sec_check_result);
														error_log("access_restrict = ".$access_restrict);
														$login_allow = "N";
														if($access_restrict == "Y") {
															error_log("date".date('N'));
															if(date('N') > 5) { 
																$user_access_query = "SELECT week_end_access, week_end_control, we_start_time, we_end_time FROM user_access WHERE user_id = $user_id";
																$user_access_result = mysqli_query($con,$user_access_query);	
																if ( $user_access_result ) {
																	$user_access_count = mysqli_num_rows($user_access_result);
																	if($user_access_count > 0) {
																		$row2 = mysqli_fetch_assoc($user_access_result);
																		$week_end_access = $row2['week_end_access'];
																		$week_end_control = $row2['week_end_control'];
																		$we_start_time = strtotime($row2['we_start_time']);
																		$we_end_time = strtotime($row2['we_end_time']);
																		if ( $week_end_access == "Y") {
																			if($week_end_control == "Y") {
																				$curtime = date('H:i:s');
																		 		error_log("@@@@curtime = ".$curtime);
																		 		error_log("@@@@username = ".$uname.", start_time = ".$row2['we_start_time'].", end time = ".$row2['we_end_time']);
																				if ((strtotime($curtime) > $we_start_time )&& (strtotime($curtime) < $we_end_time)){
																					$login_allow = "Y";
																				}
																				else {
																	  				$login_allow = "N";
																					error_log("@@@@username = ".$uname." current_time = $curtime, We_start_time = ".$we_start_time.", We_End_time = $we_end_time You can't login in this time..contact kadick for wekend access");
																					$msg = "You can't login in this time..contact kadick for wekend access";
																				}
																			}else {
																				error_log("Weekend access is allowed without any restriction");
																				$login_allow = "Y";
																			}
																		}else {
																			error_log("week_end_access = ".$week_end_access);
																			$login_allow = "N";
																		}
																	}else {
																		error_log("no records found in user_access table for username = ".$uname);
																		$login_allow = "N";
																		$msg = "Restriction Setup is partial. Contact Kadick Admin";
																	}
																}else {
																	error_log("Error in acessing user_access table for username = ".$uname);
																	$login_allow = "N";
																	$msg = "Error in accessing restriction setup is partial. Contact Kadick Admin";
																}
															}else {
																error_log("week day access..");
																$login_allow = "Y";
															}
														}else {
															$login_allow = "Y";
														}
														error_log("login_allow = ".$login_allow);
														if($login_allow == "Y") {
															$agent_code = $row['agent_code'];
															$profile_id = $row['profile_id'];
															$language_id = $row['language_id'];
															$SERVICE_GROUP_ARRAY = array();
															$SERVICE_FEATURE_ARRAY = array();
															$service_group_query = "SELECT distinct a.service_group_id, a.service_group_name, a.service_group_name_hausa FROM service_group a, service_feature_menu b, user_service_type c WHERE a.service_group_id = b.service_group_id and b.profile_id = $profile_id and a.active = 'Y' and b.active = 'Y' and c.active = 'Y' and c.service_group_id = b.service_group_id  and c.party_type = 'A' and c.party_code = '$agent_code' and c.active = 'Y' order by a.priority";
															error_log("service_group_query = ".$service_group_query);
															$service_group_result = mysqli_query($con, $service_group_query);
															if ( $service_group_result ) {
																while($service_group_row = mysqli_fetch_assoc($service_group_result)) {
																	error_log("service_group_id = ".$service_group_row['service_group_id'].", service_group_name =".$service_group_row['service_group_name']);
																	$service_group_obj = new stdClass;
																	$service_group_obj->id = $service_group_row['service_group_id'];
																	if ( $language_id == 2 ) {
																		$service_group_obj->name = $service_group_row['service_group_name_hausa'];
																	}else {
																		$service_group_obj->name = $service_group_row['service_group_name'];
																	}
																	$service_group_obj->features = array();
																	$service_feature_query = "select c.service_feature_id, c.feature_description, c.feature_description_hausa, c.href from service_group a,service_feature_menu b, service_feature c where a.service_group_id = ".$service_group_row['service_group_id']." and a.service_group_id = b.service_group_id and b.profile_id = ".$profile_id." and b.service_feature_id = c.service_feature_id and c.active = 'Y' and b.active = 'Y' and a.active= 'Y' order by b.priority"; 
																	error_log("service_feature_query = ".$service_feature_query);
																	$service_feature_result = mysqli_query($con, $service_feature_query);
																	if ( $service_feature_result ) {
																		while($service_feature_row = mysqli_fetch_assoc($service_feature_result)) {
																			error_log("service_feature_id = ".$service_feature_row['service_feature_id'].", feature_description = ".$service_feature_row['feature_description'].", href = ".$service_feature_row['href']);
																			$service_feature_obj = new stdClass;
																			$service_feature_obj->id = $service_feature_row['service_feature_id'];
																			if( $language_id == 2 ) { 
																				$service_feature_obj->name = $service_feature_row['feature_description_hausa'];
																			}else {
																				$service_feature_obj->name = $service_feature_row['feature_description'];
																			}
																			$service_feature_obj->href = $service_feature_row['href'];
																			array_push($service_group_obj->features, $service_feature_obj);
																			array_push($SERVICE_FEATURE_ARRAY, $service_feature_obj);
																		}
																	}
																	array_push($SERVICE_GROUP_ARRAY, $service_group_obj);	
																}  
															}
															$_SESSION['SERVICE_GROUP'] = $SERVICE_GROUP_ARRAY;
															$_SESSION['SERVICE_FEATURE'] = $SERVICE_FEATURE_ARRAY;
															$last_login_date = $row['last_login'];																
															$profile_name = $row['profile_name'];
															$username = $row['user_name'];
															$first_name = $row['first_name'];
															$last_name = $row['last_name'];
															$user_active = $row['active'];
															$start_date = $row['start_date'];
															$expiry_date = $row['expiry_date'];
															$email = $row['email'];
															$user_id = $row['user_id'];															
															$agent_name = $row['agent_name'];
															$parent_code = $row['parent_code'];
															$local_govt_id = $row['local_govt_id'];
															$group_id = $row['group_id'];
															$group_type = $row['group_type'];
															$state_id = $row['state_id'];
															$sub_agent = $row['sub_agent'];
															$country = $row['country_id'];
															$party_category_type_id = $row['party_category_type_id'];
															$invalid_attempt = $row['invalid_attempt'];
															$auth_id = $row['auth_id'];
															$_SESSION['user_id']	=$user_id;	
															$_SESSION['SESSION_ACCESS_COUNT']=1;											
															$_SESSION['user_name']	=$username;
															$_SESSION['first_name']	=$first_name;
															$_SESSION['last_name']	=$last_name;
															$_SESSION['active']	=$user_active;			
															$_SESSION['email']	=$email;																
															$_SESSION['last_login']	=$last_login_date;
															$_SESSION['profile_id'] =$profile_id;
															$_SESSION['start_date']	=$start_date;
															$_SESSION['expiry_date'] =$expiry_date;
															$_SESSION['profile_name'] =$profile_name;
															$_SESSION['party_code'] = $agent_code;
															$_SESSION['party_type'] = 'A';
															$_SESSION['party_name'] = $agent_name;
															$_SESSION['parent_code'] = $parent_code;
															$_SESSION['group_id'] = $group_id;
															$_SESSION['group_type'] = $group_type;
															$_SESSION['local_govt_id'] = $local_govt_id;
															$_SESSION['state_id'] = $state_id;
															$_SESSION['country_id'] = $country;
															$_SESSION['party_category_type_id'] = $party_category_type_id;
															$_SESSION['sub_agent'] = $sub_agent;
															$_SESSION['auth_id'] = $auth_id;
															$_SESSION['language_id'] = $language_id;
															$invalid_attempt = $row['invalid_attempt'];			
															if ( $invalid_attempt > 0 ) {
																$_SESSION['invalid_attempt'] = $invalid_attempt;
															}															
															$user_login_update = "UPDATE user SET last_login = now(), invalid_attempt = 0 WHERE user_name = '$uname' and user_id = ".$user_id;
															$user_login_update_result = mysqli_query($con,$user_login_update);
															$_SESSION['start'] = time();
															$_SESSION['expire'] = $_SESSION['start'] + (15 * 60);
															echo "<script>window.location.href = 'index.php';</script>";
														}else {
															error_log("@@@@username = ".$uname.": access restiction is efftive for weekends");
															$msg = "Access Restriction for weekend";
														}
													}else {
														error_log("@@@@username = ".$uname.": not found after token/pin validation");
														$msg = "Invalid User Name/Password/Token.....";
													}
												}else {
													error_log("@@@@username = ".$uname." User DB2 update failure: ".mysqli_error($con));
													$msg = "Invalid User Name/Password/Token....";
												}
											}else {
												error_log("@@@@username = ".$uname." First Time Login".$first_time_login);
												echo "<script>window.location.href = 'passwordchange.php';</script>";													
											}
										}else {
											error_log("@@@@username = ".$uname.": Pin mismatch. Entered Pin = ".$tokensplit1.", DB Pin = ".$pin_sort);
											$msg = "Invalid User Name/Password/Token...";
											$invalid_attempt = $invalid_attempt+1;
											if($invalid_attempt >= $admin_attempt_limit) {
												$admin_login_invalid_attempt_update = "UPDATE user SET invalid_attempt = COALESCE(invalid_attempt,0)+1 ,locked = 'Y', locked_time = now() where user_id =  ".$user_id;
											}else {
												$admin_login_invalid_attempt_update = "UPDATE user SET invalid_attempt = COALESCE(invalid_attempt,0)+1 where user_id =  ".$user_id;									
											}
											error_log("admin_login_invalid_attempt_update = ".$admin_login_invalid_attempt_update);
											$admin_login_invalid_attempt_update_result = mysqli_query($con,$admin_login_invalid_attempt_update);
											if(!$admin_login_invalid_attempt_update_result) {
												error_log("@@@@username = ".$uname." User DB2 update failure: ".mysqli_error($con));
												die("User DB2 update failure: ".mysqli_error($con));
											}
										}
									}else {
										error_log("@@@@username = ".$uname." Otp mismatch. formatted_otp_value = $formatted_otp_value, tokensplit0 = $tokensplit0");
										$msg = "Invalid User Name/Password/Token..";
										$invalid_attempt = $invalid_attempt+1;
										if($invalid_attempt >= $admin_attempt_limit) {
											$admin_login_invalid_attempt_update = "UPDATE user SET invalid_attempt = COALESCE(invalid_attempt,0)+1, locked = 'Y', locked_time = now() where user_id = ".$user_id;
										}
										else {
											$admin_login_invalid_attempt_update = "UPDATE user SET invalid_attempt = COALESCE(invalid_attempt,0)+1 where user_id = ".$user_id;									
										}
										error_log("admin_login_invalid_attempt_update = ".$admin_login_invalid_attempt_update);
										$admin_login_invalid_attempt_update_result = mysqli_query($con,$admin_login_invalid_attempt_update);
										if(!$admin_login_invalid_attempt_update_result){
											error_log("@@@@username = ".$uname." User DB2 update failure: ".mysqli_error($con));
											die("User DB2 update failure: ".mysqli_error($con));
										}						
									}
								}
							}else {
								error_log("@@@@username = ".$uname.": Web Acess is disabled");
								$msg = "Web Access for your Account is disabled. Contact Kadick Admin";
							}
						}else {
							error_log("@@@@username = ".$uname." Password dont match");
							$msg = "Invalid User Name/Password/Token.";
						}					
					}else {
						error_log("@@@@username = ".$uname.": Locked");
						$msg = "Your Account is locked. Contact Kadick Admin";
					}
				}else {
					error_log("@@@@username = ".$uname.": Inactive");
					$msg = "Your User  is not active";				
				}
			}else {	
				error_log("@@@@username = ".$uname.": donot exists");
				$msg = "Invalid User Name/Password/Token";		
			}						
		}else {
				error_log("@@@@username = ".$uname." User DB2 read failure: ".mysqli_error($con));
				die("User DB2 read failure: ".mysqli_error($con));
		}
	}
}	
?>
<html>
<body>
<head>
<meta charset="utf-8">
<title> Kadick Mo&#8358;ei</title>
<meta name="icom" content="icom">
<link href="css/loginstyle.css" rel="stylesheet" type="text/css" />
<link href="../common/css/fontawesome-all.css" rel="stylesheet" />
<link rel="shortcut icon" type="image/x-icon" href="../common/images/km_logo.ico" />
<style>
.group i,h2 {
	color:greenyellow;
}
button {
	background-color:greenyellow;
	color:black;
}
.loader {
	border: 1px solid #f3f3f3;
	border-radius: 100%;
	border-top: 10px dotted blue;
	border-right: 10px dotted green;
	border-bottom: 10px dotted red;
	border-left: 10px dotted red;
	width: 56px;
	height: 56px;
	-webkit-animation: spin 5s linear infinite;
	animation: spin s5linear infinite;
	position: absolute;
	margin-left: 420px;
}
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
.altclass	{
	color :red;
	margin-left:420px
}
.loading-spiner-holder {
	display:none;
}
</style>
</head>
    <div class=" w3l-login-form">
	 <p id='MessInfo3' style='margin-left:-200px'></p> 
		<h2>Agent Login
		<a href="login.php"><img src="../common/images/km_logo.png" height="42" width="100" style="float: right;"></a></h2>
          <div  style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:100%' align="middle" src="../common/img/loading-48.gif" /></div></div>
		 <form onsubmit="return validateForm()" class="form-signin" name='adminrloginform' method='POST' action="">            <div class=" w3l-form-group">
                <label>Username</label>
                <div class="group">
                    <i class="fas fa-user"></i>
			        <input type="text" id="UserName" name='username' autocomplete="off" class="form-control" placeholder="Username"  autofocus />
                </div>
            </div>
            <div class=" w3l-form-group">
                <label>Password</label>
                <div class="group">
                    <i class="fas fa-unlock"></i>
                    <input type="password" class="form-control" autocomplete="off" id="Password" name='password' class="form-control" placeholder="Password" >
                </div>
            </div>
			<div class=" w3l-form-group">
                <label>Token</label>
                <div class="group">
                    <i class="fas fa-unlock"></i>
                    <input type="password" class="form-control" autocomplete="off" name='token' maxlength="10" Placeholder="Token" >
                </div>
            </div>
			<div class=" w3l-form-group">
                <label>Captcha</label>
                <div class="group">
                    <i class="fas fa-unlock"></i>
                   <input id="captcha_code" class="form-control" autocomplete="off" name="captcha_code" maxlength="6" placeholder = 'Captcha' type="text" autocomplete="off"  />
                </div>
            </div>
			<div class=" w3l-form-group">
                <p ><img style='' src="../common/captcha.php?rand=<?php echo rand();?>" id='captchaimg'/><span style='padding-left:5px;color:white'>Cant read captcha? click <a style="color:blue;font-size:16px; " href='javascript: refreshCaptcha();'>here</a></span></p>
				<p style="color:red;font-size:16px;"><?php if(isset($_POST['login'])) { echo $msg;} ?></p>
				<button name='login' class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Sign in</button>
				<br /><br />				
				<button class="btn btn-lg btn-primary btn-block btn-signin" type="reset">Reset</button>
            </div>            
         </form>     
    </div>

	
<script>
	function refreshCaptcha(){
		var img = document.images['captchaimg'];
		img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
	}
</script>
<script>
 function validateForm() {
	var x = document.forms["adminrloginform"]["username"].value;
		y = document.forms["adminrloginform"]["password"].value;
		z = document.forms["adminrloginform"]["token"].value;
		a = document.forms["adminrloginform"]["token"].value.length;
		b = document.forms["adminrloginform"]["captcha_code"].value;
	if ( x == null || x == "" || x.trim() == "" ) {
		alert("Please Enter Username");
		document.forms["adminrloginform"]["username"].value = "";
		//focuscolor("#UserName");
		return false;
	}

	else if ( y == null || y == "" || y.trim() == "" ) {
		alert("Please Enter Password");
		document.forms["adminrloginform"]["password"].value = "";
		//focuscolor("#Password");
		return false;
	}
	
	else if ( z == null || z == "" || z.trim() == "" ) {
		alert("Please Enter token");
		document.forms["adminrloginform"]["token"].value = "";
		//focuscolor("#Token");
		return false;
	}
	
	else if (a < 10) {
		alert("Token should be 10 characters in length ");
		document.forms["adminrloginform"]["token"].value = "";
		//focuscolor("#Token");
		return false;
	}

	else if ( b == null || b == "" || b.trim() == "" ) {
		alert("Please Enter captcha");
		document.forms["adminrloginform"]["captcha_code"].value = "";
		//focuscolor("#captcha_code");
		return false;
	}
	else {		
		$("#MessInfo").html("");
		$("#MessInfo").removeClass("altclass");
		$("#MessInfo").html("<p id='MessagInfo' class='loader' style='margin-top:-50px;margin-left:400px;position:absolute'></p>");
		return true;
	}
}
</script>
<script>
 function validateForm() {
	var x = document.forms["adminrloginform"]["username"].value;
		y = document.forms["adminrloginform"]["password"].value;
		z = document.forms["adminrloginform"]["token"].value;
		a = document.forms["adminrloginform"]["token"].value.length;
		b = document.forms["adminrloginform"]["captcha_code"].value;
	if ( x == null || x == "" || x.trim() == "" ) {
		alert("Please Enter Username");
		document.forms["adminrloginform"]["username"].value = "";
		//focuscolor("#UserName");
		return false;
	}

	else if ( y == null || y == "" || y.trim() == "" ) {
		alert("Please Enter Password");
		document.forms["adminrloginform"]["password"].value = "";
		//focuscolor("#Password");
		return false;
	}
	
	else if ( z == null || z == "" || z.trim() == "" ) {
		alert("Please Enter token");
		document.forms["adminrloginform"]["token"].value = "";
		//focuscolor("#Token");
		return false;
	}
	
	else if (a < 10) {
		alert("Token should be 10 characters in length ");
		document.forms["adminrloginform"]["token"].value = "";
		//focuscolor("#Token");
		return false;
	}

	else if ( b == null || b == "" || b.trim() == "" ) {
		alert("Please Enter captcha");
		document.forms["adminrloginform"]["captcha_code"].value = "";
		//focuscolor("#captcha_code");
		return false;
	}
	else {		
		document.getElementsByClassName('loading-spiner-holder')[0].style.display = "block";	
		var elems = document.getElementsByClassName('w3l-form-group');
		for (var i=0;i<elems.length;i+=1){
		  elems[i].style.display = 'none';
		}		
		//.style.display = "none";
		
	}
}
</script>
</body>
</html>