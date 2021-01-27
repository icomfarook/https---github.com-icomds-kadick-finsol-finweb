<?php
include('../common/admin/configmysql.php');
include("../common/admin/finsol_crypt.php");

if(isset($_POST['submit'])) {
	session_start();
	$uname = trim($_POST['username']);
	$oladpassword = trim($_POST['oldpassword']);
	$password = trim($_POST['password']);
	$repassword = trim($_POST['repassword']);
	$admin_attempt_limit = ADMIN_ATTEMPT_LIMIT;
	if ( empty($_SESSION['captcha_code'] ) || strcasecmp($_SESSION['captcha_code'], $_POST['captcha_code']) != 0 ){  
		$msg="Captcha is incorrect..!";// Captcha verification is incorrect.		
	} else if ( empty($uname) || strlen($uname) == 0  ) {
			$msg="Username is required.";
	} else if ( empty($password) || strlen($password) == 0  ) {
		$msg="Password is required.";
	} 
	else if ( empty($repassword) || strlen($repassword) == 0  ) {
		$msg="Password is required.";
	} 
	else if ($password != $repassword) {
		$msg="Password and Re password should be same.";
	} 
	else {
		$user_check_query = "SELECT user_id,user_name, temp_password,active,invalid_attempt,locked FROM user WHERE UPPER(user_name) = UPPER('".$uname."') and profile_id in (50) and first_time_login = 'Y'  limit 1";
		error_log("user_check_query".$user_check_query);
		$user_check_result = mysqli_query($con,$user_check_query);
		if ( $user_check_result ) {
			$user_check_count = mysqli_num_rows($user_check_result);
			if($user_check_count > 0){
				$row = mysqli_fetch_array($user_check_result);
				$temp_password = $row['temp_password'];
				$username = $row['user_name'];
				$user_id = $row['user_id'];
				$user_active = $row['active'];
				$invalid_attempt = $row['invalid_attempt'];
				$locked = $row['locked'];
				if ( $user_active == "Y" ) {
					if ( $locked != "Y" && $invalid_attempt <= $admin_attempt_limit ) {						
						$successpassword = ckdecrypt($oladpassword, $temp_password);						
						if($successpassword) {
							$hash_password = ckencrypt($password);
							$hash_password = mysqli_real_escape_string($con,$hash_password);
							$user_table_update_query = "UPDATE user SET password = '$hash_password',first_time_login = 'N'  where user_id = ".$user_id." and UPPER(user_name) = UPPER('".$username."') and profile_id in (50)";
							error_log("user_table_update = ".$user_table_update_query);
							$user_table_update_result = mysqli_query($con,$user_table_update_query);
							if(!$user_table_update_result){
								error_log("@@@@username = ".$uname." User user_table_update_result update failure: ".mysqli_error($con));
								die("User user_table_update_result update failure: ".mysqli_error($con));
							}
							else{
								$retval = 0;
								$msg = "Password Changed Sucessfully.";							
							}								
						}else {
							error_log("@@@@username = ".$uname." User detail count not > 0");
							$msg = "Invalid User Name/Password/Token.";
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

</head>
    <div class=" w3l-login-form">
		<h2>Password Change</h2>
		  <form class="form-signin" name='adminrloginform' method='POST' action="">           
		  <div class=" w3l-form-group">
                <label><?php echo CONTROL_USER_NAME; ?></label>
                <div class="group">
                    <i class="fas fa-user"></i>
			        <input type="text" id="UserName" name='username' class="form-control" placeholder="Username" required="required" autofocus />
                </div>
            </div>
			  <div class=" w3l-form-group">
                <label>Old Password</label>
                <div class="group">
                    <i class="fas fa-unlock"></i>
                    <input type="password" class="form-control" id="OldPassword" name='oldpassword' class="form-control" placeholder="Password" required="required">
                </div>
            </div>
            <div class=" w3l-form-group">
                <label>New Password</label>
                <div class="group">
                    <i class="fas fa-unlock"></i>
                    <input type="password" class="form-control" id="RePassword" name='password' class="form-control" placeholder="Password" required="required">
                </div>
            </div>
			<div class=" w3l-form-group">
                <label>New Re - Password</label>
                <div class="group">
                    <i class="fas fa-unlock"></i>
                    <input type="password" class="form-control" id="ReNewPassword" name='repassword' class="form-control" placeholder="Password" required="required">
                </div>
            </div>
		
			<div class=" w3l-form-group">
                <label>Captcha</label>
                <div class="group">
                    <i class="fas fa-unlock"></i>
                   <input id="captcha_code" class="form-control"  name="captcha_code" maxlength="6" placeholder = 'Captcha' type="text" autocomplete="off" required="required" />
                </div>
            </div>
			<div class=" w3l-form-group">
                <p ><img style='' src="../common/captcha.php?rand=<?php echo rand();?>" id='captchaimg'/><span style='padding-left:5px'>Cant read captcha? click <a style="color:blue;font-size:16px; " href='javascript: refreshCaptcha();'>here</a></span></p>
				<?php if(isset($_POST['submit'])) {
							if($retval == 0) {					
								echo "  <div><strong>Hello ".$uname."</strong><span style='color:red'>".$msg."</span><br />
									   <a  style='color:red' href='login.php'>Please click here to go login page.</a>
									  </div>";
							}
							else {
								echo "<p style='color:red;font-size:16px;'>$msg</p>";
							}
						}
								?>
				<button name='submit' class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Sign in</button>
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
	function home(){
		window.location.href = 'login.php';
	}
</script>
</body>
</html>