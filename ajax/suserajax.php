<?php
	include('../common/sessioncheck.php');
	include('../common/qrlib/qrlib.php');
	include("mailfunction.php");
	include('../common/otp/otphp.php');
	include("../common/admin/finsol_crypt.php");
	include("../common/admin/configmysql.php");
	
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$id     = $data->id;
	$fname  = $data->fname;
	$lname  = $data->lname;
	$active = $data->active;
	$email  = $data->email;
	$sendmail  = $data->sendmail;
	$accessrestrict  = $data->accessrestrict;
	$otptype  = $data->otptype;
	$profile = $_SESSION['profile_id'];

	if($action == "list") {	
		if($profile == 1) {
			$userquery = "SELECT a.user_id,concat(a.first_name,' ', a.last_name,' (',a.user_name,') ') as user, if(a.active='Y','Yes','No') as active, if(c.otp_dynamic='Y','Y','N') as otp_dynamic,b.profile_name,a.locked,ifNull(a.access_restrict,'N') as access_restrict,ifNull(a.pos_access,'N') as pos_access  from user a, profile b, user_otp c where a.user_id = c.user_id and a.profile_id = b.profile_id and a.profile_id in (5, 10, 20, 21,22,23,24,25,26,27,28,29,100) and a.profile_id ";
		}
		if($profile == 10) {
			$userquery = "SELECT a.user_id,concat(a.first_name,' ', a.last_name,' (',a.user_name,') ') as user, if(a.active='Y','Yes','No') as active, if(c.otp_dynamic='Y','Y','N') as otp_dynamic,b.profile_name,a.locked,ifNull(a.access_restrict,'N') as access_restrict,ifNull(a.pos_access,'N') as pos_access from user a, profile b, user_otp c where a.user_id = c.user_id and a.profile_id = b.profile_id and a.profile_id in (10,20, 21,22,23,24,25,26,27,28,29, 100)";
		}
		error_log($userquery);
		$userresult =  mysqli_query($con,$userquery);
		if (!$userresult) {
			printf("Error: %s\n".mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($userresult)) {
			$data[] = array("id"=>$row['user_id'],"user"=>$row['user'],"active"=>$row['active'],"dynamic"=>$row['otp_dynamic'],"profileName"=>$row['profile_name'],"locked"=>$row['locked'],"access"=>$row['access_restrict'],"posaccess"=>$row['pos_access']);           
		}
		echo json_encode($data);
	}
	else if($action == "otpchange") {
		$query = "SELECT concat(a.user_name, ' (', a.first_name,' ', a.last_name, ') ') as user, b.otp_dynamic, a.user_id FROM user a, user_otp b WHERE a.user_id = b.user_id and a.user_id= ".$id;
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['user_id'],"user"=>$row['user'],"odynamic"=>$row['otp_dynamic']);           
		}
		echo json_encode($data);
	}
	else if($action == "control") {		
		$query = "SELECT concat(user_name, ' (', first_name,' ', last_name, ') ') as user, user_id, COALESCE(locked, 'N') as locked, COALESCE(locked_time, ' - ') as locked_time,COALESCE(first_time_login, 'N') as first_time_login  FROM user WHERE user_id= ".$id;
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['user_id'],"user"=>$row['user'],"locked"=>$row['locked'],"ltime"=>$row['locked_time'],"fstlogin"=>$row['first_time_login']);           
		}		
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			exit();
		}
		echo json_encode($data);		
	}
	else if($action == "lock") {		
		$query = "UPDATE user set locked = 'Y', locked_time = now() WHERE user_id = ".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			exit();
		}
		else {
			echo "Locked successfully";
		}			
	}
	else if($action == "changefstlogn") {		
		$query = "UPDATE user set first_time_login = 'N' WHERE user_id = ".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			exit();
		}
		else {
			echo "First Time Login Changed Successfully";
		}			
	}
	else if($action == "changefstlogy") {		
		$query = "UPDATE user set first_time_login = 'Y' WHERE user_id = ".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			exit();
		}
		else {
			echo "First Time Login Changed Successfully";
		}			
	}
	else if($action == "unlock") {		
		$query = "UPDATE user set locked = 'N', locked_time = null WHERE user_id = ".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			exit();
		}
		else {
			echo "Un-Locked successfully";
		}			
	}
	else if($action == "edit") {		
		$query = "SELECT user_id,concat(user_name, ' (', first_name,' ', last_name, ') ') as user, first_name, last_name, active, email FROM user WHERE user_id = ".$id;
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['user_id'],"user"=>$row['user'],"fname"=>$row['first_name'],"lname"=>$row['last_name'],"active"=>$row['active'],"email"=>$row['email']);           
		}		
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			exit();
		}
		echo json_encode($data);
	}
	else if($action == "ssotpupdate") {	
		$otpvalue = $data->otpvalue;
		$updateuserotpquery = "UPDATE user_otp set otp_value = '".$otpvalue."' WHERE user_id = ".$id;
		error_log($updateuserotpquery);
		$result = mysqli_query($con,$updateuserotpquery);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			exit();
		}
		else {
			echo "Static Otp [$otpvalue] Updated successfully";
		}	
	}
	else if($action == "scontrol") {
		$query = "SELECT concat(a.user_name, ' (', a.first_name,' ', a.last_name, ') ') as user, b.otp_value, b.pin,a.user_id FROM user a, user_otp b WHERE a.user_id = b.user_id and a.user_id= ".$id;
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['user_id'],"user"=>$row['user'],"ovalue"=>$row['otp_value'],"pin"=>$row['pin']);           
		}		
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			exit();
		}
		echo json_encode($data);
	}
	else if($action == "regenerate") {
		$userotpquery = "SELECT b.email, b.user_id, b.profile_id, c.auth_id FROM user_otp a, user b, profile c WHERE b.user_id = a.user_id and a.user_id = $id and b.profile_id = c.profile_id limit 1";
		$userotpresult = mysqli_query($con,$userotpquery);
		//error_log("userotpquery = ".$userotpquery);
		if($userotpresult) {
			$total = mysqli_num_rows($userotpresult);
			//error_log("total = ".$total);
			if ( $total > 0 ) {
				$otp = mysqli_fetch_array($userotpresult);
				$email = $otp['email'];
				$userid = $otp['user_id'];
				$profile_id = $otp['profile_id'];
				$auth_id = $otp['auth_id'];
				$email = @htmlspecialchars($email, ENT_QUOTES);
				$email = urlencode($email);
	
				$secretkey  = substr(md5(mt_rand()), 0, 20);
				$secretkey = Base32::encode($secretkey,false);
				$msg = array();
				$generatetype = "TOTP(Time based OTP)";
				if ($auth_id == 40 || $auth_id == 50 || $auth_id == 60 || $auth_id == 70 ) { 
					$digits = OTP_USER_DIGITS;
					$period = OTP_USER_PERIODS;
				}else {
					$digits = OTP_ADMIN_DIGITS;
					$period = OTP_ADMIN_PERIODS;
				}
				$algorithm = OTP_ALGORITHM;
				$text = "otpauth://totp/".$email.":".$Hexcode."?secret=".$secretkey."&algorithm=".$algorithm."&digits=".$digits."&period=".$period;

				$updateuserotp = "UPDATE user_otp set key_string = '".$secretkey."', qr_text = '".$text."', otp_length = ".$digits.", otp_type = 'T', otp_alg = '".$algorithm."', otp_interval = ".$period.", key_update_time = now() WHERE user_id = ".$id;
				//error_log("updateuserotpquery: ".$updateuserotp);
				$result = mysqli_query($con,$updateuserotp);
				if($result){
					//$msg  = "Secret Key regenrated successfully";
					  $error = 0;
					  $msg[] = array("error"=>$error,"msg"=>$secretkey);
					  
				}else {
					$error = 1;
					error_log("Error in regenerating Key: ". mysqli_error($con));
					$msg  = "Error in regernating Key";
					$msg[] = array("error"=>$error,"msg"=>$msg);
				}
			}else {
				$error = 2;
				error_log("No valid user in regenerating Key for : ".$id);
				$msg  = "Not a valid user for regernating Key";
				$msg[] = array("error"=>$error,"msg"=>$msg);
			}
		}else {
			$error = 3;
			error_log("Error in regenerating Key: ". mysqli_error($con));
			$msg  = "Error in regernating key";
			$msg[] = array("error"=>$error,"msg"=>$msg);
		}
		echo json_encode($msg);
	}
	else if($action == "sendmail"){
		$userotpquery = "SELECT a.key_string, a.otp_length, a.otp_type, a.otp_alg, a.otp_interval, if(a.otp_type,'TOTP(Time based OTP)','HMAC-based one-time password') as otptype, b.email, b.user_name,concat(b.user_name,' (',b.first_name,' ',b.last_name,')') as user, c.auth_id FROM user_otp a, user b, profile c WHERE b.user_id = a.user_id and a.user_id = $id and b.profile_id = c.profile_id limit 1";
		//error_log(" userotpquery = ".$userotpquery);
		$userotpresult = mysqli_query($con,$userotpquery);
		$otp = mysqli_fetch_array($userotpresult);
		$email = $otp['email'];
		$user_name = $otp['user_name'];
		$file = QRLOC."qr_Admin_$user_name.png";
		$user = $otp['user'];
		$interval = $otp['otp_interval'];
		$otptype = $otp['otptype'];
		$digits = $otp['otp_length'];
		$current_time = date('Y-m-d H:i:s');
		$secretkey = $otp['key_string'];
		$auth_id = $otp['auth_id'];
		$email_array = array();	
		$algorithm = OTP_ALGORITHM;
		$digits = OTP_ADMIN_DIGITS;
		$interval = OTP_ADMIN_PERIODS;
		if ($auth_id == 40 || $auth_id == 50 || $auth_id == 60 || $auth_id == 70 ) { 
			$digits = OTP_USER_DIGITS;
			$period = OTP_USER_PERIODS;
		}else {
			$digits = OTP_ADMIN_DIGITS;
			$period = OTP_ADMIN_PERIODS;
		}
		
		$otptype = 'TOTP(Time based OTP)';
		array_push($email_array, $email);
		$subject = 'Login Credential - '.$user_name;
		$body   = ' <p>Dear '.$user_name.',</p>
				<div>Here is your confidential security details for OTP. Please dont share with any one.</div><br />
				<div><label>Issuer: </label><label>'.$email.'</label></div>
				<div><label>Hex Code: </label><label>'.OTP_SERVER.'</label></div>
				<div><label>Secret key: </label><label>'.$secretkey.'</label></div>
				<div><label>OTP Type: </label><label>'.$otptype.'</label></div>
				<div><label>Digits: </label><label>'.$digits.'</label></div>
				<div><label>Algorithm: </label><label>'.$algorithm.'</label></div>
				<div><label>Interval: </label><label>'.$interval.'</label></div><br />
				<div>Please scan the below qr code to auto save the details</div><br />
				Note: This is an auto generated email. For more information contact Kadick Admin.<br />
				Generated @'.$current_time.' WAT<br /><br />';
				mailSend($email_array, $body, $subject,$file);
				$msg = "User: $user email sent successfully..";
				echo $msg;
		
	}
	else if($action == "getcurrentotp") {
		$otpquery = "SELECT a.key_string, c.auth_id, now() as date from user_otp a, user b, profile c WHERE a.user_id = b.user_id and b.profile_id = c.profile_id and a.user_id = $id limit 1";
		$otpresult = mysqli_query($con,$otpquery);
		error_log("otpquery = ".$otpquery);
		if(!$otpresult) {
			error_log("Error in otpresult: ". mysqli_error($con));
		}
		else {			
			$row = mysqli_fetch_assoc($otpresult);
			$keystring = $row['key_string'];
			$auth_id = $row['auth_id'];
			$date = $row['date'];
			$digits = OTP_ADMIN_DIGITS;
			$interval = OTP_ADMIN_PERIODS;
			if ($auth_id == 40 || $auth_id == 50 || $auth_id == 60 || $auth_id == 70 ) { 
				$digits = OTP_USER_DIGITS;
				$period = OTP_USER_PERIODS;
			}else {
				$digits = OTP_ADMIN_DIGITS;
				$period = OTP_ADMIN_PERIODS;
			}
			$totp = new \OTPHP\TOTP($keystring, Array('interval'=>$interval, 'digits'=>$digits, 'digest'=>OTP_ALGORITHM));
			//error_log("Err".$date);
			$totp =  $totp->now();
			$formatted_otp_value = str_pad($totp, $digits, '0', STR_PAD_LEFT);
			error_log("Generated OTP = ".$totp." date = ".$date."formatted_otp_value = ".$formatted_otp_value);
			echo "<br/><p style='margin:0px;font-size:13px'><b style='color:black'>Current OTP</b><b style='color:red'> :".$formatted_otp_value."</b></p><p style='margin:0px;font-size:13px'><b style='color:black'> Gen.Time: </b><b style='color:red'>".$date."</b></p>";		
			}
	}
	else if($action == "pinupdate"){
		$pinvalue = $data->pin;
		$updateuserotpquery = "UPDATE user_otp set pin = '".$pinvalue."' WHERE user_id = ".$id;
		//error_log($updateuserotpquery);
		$result = mysqli_query($con,$updateuserotpquery);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			exit();
		}
		else {
			echo "Pin [$pinvalue] Updated successfully.";
		}	
	}
	else if($action == "otytypeupdate") {
		$updateuserotpquery = "UPDATE user_otp set otp_dynamic = '".$otptype."' WHERE user_id = ".$id;
		$result = mysqli_query($con,$updateuserotpquery);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			exit();
		}
		else {
			$msg = "User: $user OTP Updated successfully";
			if($otptype == 'Y'){
			//error_log("Send Email ".$sendmail."	Type	 = ".$type);
			
			if($sendmail == 'Y') {
				$userotpquery = "SELECT a.key_string, a.otp_length, a.otp_type, a.otp_alg, a.otp_interval, if(a.otp_type,'TOTP(Time based OTP)','HMAC-based one-time password') as otptype, b.email, b.user_name,concat(b.user_name,' (',b.first_name,' ',b.last_name,')') as user FROM user_otp a, user b, profile c WHERE b.user_id = a.user_id and a.user_id = $id and b.profile_id = c.profile_id limit 1";
				//error_log(" userotpquery = ".$userotpquery);
				$userotpresult = mysqli_query($con,$userotpquery);
				$otp = mysqli_fetch_array($userotpresult);
				$email = $otp['email'];
				$user_name = $otp['user_name'];
				$auth_id = $otp['auth_id'];
				
								
				$user = $otp['user'];
				$interval = $otp['otp_interval'];
				$otptype = $otp['otptype'];
				$digits = $otp['otp_length'];
				$current_time = date('Y-m-d H:i:s');
				$secretkey = $otp['key_string'];
				$email_array = array();		
				$algorithm = OTP_ALGORITHM;
				if ($auth_id == 40 || $auth_id == 50 || $auth_id == 60 || $auth_id == 70 ) { 
					$digits = OTP_USER_DIGITS;
					$period = OTP_USER_PERIODS;
					$file = QRLOC."qr_USER_$user_name.png";
				
				}else {
					$digits = OTP_ADMIN_DIGITS;
					$period = OTP_ADMIN_PERIODS;
					$file = QRLOC."qr_ADMIN_$user_name.png";
				}
				//error_log("qr_file location = ".$file);	
				$otptype = 'TOTP(Time based OTP)';
				array_push($email_array, $email);
				$subject = 'Login Credential - '.$user_name;
				$body   = ' <p>Dear '.$user_name.',</p>
						<div>Here is your confidential security details for OTP. Please dont share with any one.</div><br />
						<div><label>Issuer: </label><label>'.$email.'</label></div>
						<div><label>Hex Code: </label><label>'.OTP_SERVER.'</label></div>
						<div><label>Secret key: </label><label>'.$secretkey.'</label></div>
						<div><label>OTP Type: </label><label>'.$otptype.'</label></div>
						<div><label>Digits: </label><label>'.$digits.'</label></div>
						<div><label>Algorithm: </label><label>'.$algorithm.'</label></div>
						<div><label>Interval: </label><label>'.$interval.'</label></div><br />
						<div>Please scan the below qr code to auto save the details</div><br />
						Note: This is an auto generated email. For more information contact Kadick Admin.<br />
						Generated @'.$current_time.' WAT<br /><br />';
						mailSend($email_array, $body, $subject,$file);
						$msg = "User: $user OTP details sent via email successfully";
				}
			}
		}
		echo $msg;
	}		
	else if($action == "passreset") {
		//include("../admin/finsol_crypt.php");
		$password = $data->password;
		$repassword = $data->repassword;
		//error_log("repassword ".$repassword);
		
		if($password == $repassword) {
			$hash_password = ckencrypt($password);
			$query = "UPDATE user set password = '$hash_password' WHERE user_id = ".$id;
			//error_log("query ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				echo "Error: %s\n".mysqli_error($con);
				exit();
			}
			else {
				$msg  = "Password updated successfully";
			}			
		}
		else {
			$msg  = "Password/RePassword Should be same";
		}	
			echo trim($msg) ;
	}	
	else if($action == "update") {		
		$query = "UPDATE user set first_name = '".$fname."', last_name = '".$lname."', email = '$email', active = '".$active."' WHERE user_id = ".$id;
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "User [$fname] updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		 }	
	}
else if($action == "accessrestrict") {
		$query = "SELECT concat(user_name, ' (', first_name,' ', last_name, ') ') as user, access_restrict, user_id FROM user WHERE user_id = ".$id;
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['user_id'],"user"=>$row['user'],"accessrestrict"=>$row['access_restrict']);           
		}
		echo json_encode($data);
	}
	
	else if($action == "posaccess") {
		$query = "SELECT concat(user_name, ' (', first_name,' ', last_name, ') ') as user, pos_access, user_id FROM user WHERE user_id = ".$id;
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['user_id'],"user"=>$row['user'],"posaccess"=>$row['pos_access']);           
		}
		echo json_encode($data);
	}
	else if($action == "posaccessupdate") {
		$pos_access = $data->posaccess;
		$query = "UPDATE user set pos_access = '".$pos_access."' WHERE user_id = ".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			//exit();
		}
		else {		
			$msg = "Pos Access: $user Updated successfully";
			}
		echo $msg;
	}
	if($action == "updateaccess") {	
		$weekendaccess = $data->weekendaccess;
		$weekendcontrol= $data->weekendcontrol;
		$stime= $data->stime;
		$etime= $data->etime;
		$stime=date("h:i:s", strtotime($stime));
		$etime=date("h:i:s", strtotime($etime));
		error_log($stime);error_log($etime);
		if($weekendcontrol == "Y") {
			if($stime == "" || empty($stime) || $stime == null){
				$stime = date('h:i:s');
			}
			if($etime == "" || empty($etime) || $etime == null){
				$etime = date('h:i:s');
			}
		}
		if($weekendcontrol == "" || empty($weekendcontrol) || $weekendcontrol == null){
				$weekendcontrol = "N";
		}
		if($weekendaccess == "" || empty($weekendaccess) || $weekendaccess == null){
				$weekendaccess = "N";
		}
		error_log($stime);error_log($etime);
		$query = "UPDATE user_access SET week_end_access = '".$weekendaccess."', week_end_control = '".$weekendcontrol."', 
				we_start_time = '".$stime."', we_end_time = '".$etime."' WHERE user_id = ".$id;
		error_log("query = ".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			//exit();
		}
		else {
			$msg = "User Restrict: $user Updated successfully";
		}
		echo $msg;
	}
	else if($action == "userrestrictupdate") {
		$query = "UPDATE user set access_restrict = '".$accessrestrict."' WHERE user_id = ".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			//exit();
		}
		else {
			if($accessrestrict == "Y") {
				$query = "INSERT INTO user_access (week_end_access, week_end_control, user_id) VALUES ('Y','N',$id)";
			}
			if($accessrestrict == "N") {
				$query = "DELETE FROM user_access WHERE user_id = $id";
			}
			$result = mysqli_query($con,$query);
			if (!$result) {
				echo "Error2: %s\n".mysqli_error($con);
				//exit();
			}
			else {
				$msg = "User Restrict: $user Updated successfully";
			}
			
		}
		echo $msg;
	}	
	else if($action == "insertsysuser"){	
		$username  =$data->userName;
		$firstName = $data->firstName;
		$lastName  = $data->lastName;	
		$password = $data->password;
		$repassword = $data->repassword;
		$active = $data->active;	
		$sdate2 =  $data->startDate;
		$edate2 =  $data->endDate;
		$sdate = date("Y-m-d", strtotime($sdate2. "+1 days"));
		$edate = date("Y-m-d", strtotime($edate2. "+1 days"));
		$email = $data->email;
		$profile = $data->profile;
		$hash_password = ckencrypt($password);
		$hash_password = mysqli_real_escape_string($con, $hash_password);
		//$hash_token = mysql_real_escape_string($hash_token);
		
		if($password != $repassword) {
			echo "Password should be same";
			error_log("Password not mismatched :");
		}else{ 
			$check_user_name_query = "SELECT user_id FROM user WHERE UPPER(user_name) = UPPER('$username')";
			error_log("check_user_name_query = ". $check_user_name_query);
			$check_user_name_result = mysqli_query($con,$check_user_name_query);
			if(!$check_user_name_result) {	
				$msg = die("Check User Name Failure = ". mysqli_error($con));
				error_log("Inside Check User Name Failure = ".$msg);
			}
			else {
			$count = mysqli_num_rows($check_user_name_result);
			if($count <= 0) {
				$get_sequence_num_query = "SELECT get_sequence_num(100) as userid";
				error_log("get_sequence_num_query = ".$get_sequence_num_query);
				$get_sequence_num_result = mysqli_query($con, $get_sequence_num_query);
				if(!$get_sequence_num_result) {	
					$msg = die("Get Sequence Number Failure = ". mysqli_error($con));
					error_log("Get Sequence Number Failure = ".$msg);
				}
				else {
					$user = mysqli_fetch_assoc($get_sequence_num_result);
					$userid = $user['userid'];
					error_log("userid = ".$userid);
					if ( trim($sdate) == '' && trim($edate) == '' ) {
						$insert_user_query = "INSERT INTO user(user_id, user_name, first_name, last_name, password, active, profile_id, email,country_id) VALUES ($userid, '$username', '$firstName', '$lastName', '$hash_password', '$active', $profile, '$email',566)";
						//error_log("1.insert_user_query = ". $insert_user_query);
					}else {
						if ( trim($sdate) == '' && trim($edate) != '' ) {
							$insert_user_query = "INSERT INTO user(user_id, user_name, first_name, last_name, password, active, profile_id, expiry_date, email,user_type, access_restrict,country_id) VALUES ($userid, '$username', '$firstName', '$lastName', '$hash_password', '$active', $profile, '$edate', '$email','U','Y',566)";
						}else if ( trim($edate) == '' && trim($sdate) != '' ) {
							$insert_user_query = "INSERT INTO user(user_id, user_name, first_name, last_name, password, active, profile_id, start_date, email,user_type, access_restrict,country_id) VALUES ($userid, '$username', '$firstName', '$lastName', '$hash_password', '$active', $profile, '$sdate', '$email','U','Y',566)";
						}else {
							$insert_user_query = "INSERT INTO user(user_id, user_name, first_name, last_name, password, active, profile_id, start_date, expiry_date, email,user_type, access_restrict,country_id) VALUES ($userid, '$username', '$firstName', '$lastName', '$hash_password', '$active', $profile, '$sdate', '$edate', '$email','U','Y',566)";
						}
					}	
					error_log("insert_user_query = ". $insert_user_query);
					$insert_user_result = mysqli_query($con,$insert_user_query);
					if(!$insert_user_result) {		
						$msg = die("ins user query failure ". mysqli_error($con));
						error_log("Inside insert_user_result failure  = ".$msg);
					}
					else {
							$check_auth_id_query = "SELECT auth_id FROM profile where profile_id = $profile";
							$check_auth_id_result = mysqli_query($con, $check_auth_id_query);
							if(!$check_auth_id_result) {
								error_log("Get auth_id query Failure = ".$msg);
								$auth_id = 30; 
							}else {
								$auth_id_row = mysqli_fetch_assoc($check_auth_id_result);
								$auth_id = $auth_id_row['auth_id'];
							}
							error_log("auth_id = ".$auth_id);
							$algorithm = OTP_ALGORITHM;
							if ($auth_id == 40 || $auth_id == 50 || $auth_id == 60 || $auth_id == 70 ) { 
								$digits = OTP_USER_DIGITS;
								$period = OTP_USER_PERIODS;
								$file_name ="qr_USER_".$username.".png";
											
							}else {
								$digits = OTP_ADMIN_DIGITS;
								$period = OTP_ADMIN_PERIODS;
								$file_name ="qr_ADMIN_".$username.".png";
							}
							$secretkey  = substr(md5(mt_rand()), 0, 20);
							$secretkey = Base32::encode($secretkey, false);
							$Hexcode = OTP_SERVER;
							$Hexcode = urlencode($Hexcode);
							$email_array = array();
							$text = "otpauth://totp/".$email.":".$Hexcode."?secret=".$secretkey."&algorithm=".$algorithm."&digits=".$digits."&period=".$period;
							$insert_finsol_otp_query = "INSERT INTO user_otp(access_id, user_id, otp_dynamic, key_string, qr_text, otp_length, otp_type, otp_alg, otp_interval, access_count, fail_count, pin_flag, pin, key_create_time) values (0, '$userid', 'Y', '$secretkey', '$text', ".$digits.", 'T', '".$algorithm."', ".$period.", 0, 0, 'Y', '7070', now())";
							error_log("insert_finsol_otp_query = ".$insert_finsol_otp_query);
							$insert_finsol_otp_result = mysqli_query($con,$insert_finsol_otp_query);
							if(!$insert_finsol_otp_result) {		
								$msg = die("ins user otp query failure ". mysqli_error($con));
								error_log("Inside insert_otp_result failure  = ".$msg);
							}
							$query = "INSERT INTO user_access (week_end_access, week_end_control, user_id) VALUES ('Y','N',$userid)";
							$result = mysqli_query($con,$query);
							if (!$result) {
								echo "Error: %s\n".mysqli_error($con);
								exit();
							}
							else {
								$current_time = date('Y-m-d H:i:s');
								$folder = QRLOC;
								if (!file_exists($folder)) {
									mkdir(QRLOC, 0777, true);
								}
								$qrcodeimage = $text;
								$file_name2 = $folder.$file_name;	
								QRcode::png($qrcodeimage, $file_name2);													
								$otptype = 'TOTP(Time based OTP)';
								array_push($email_array, $email);
								$subject = 'Login Credential - '.$username;
								$body   = ' <p>Dear '.$username.',</p>
											<div>Here is your confidential security details for OTP Setup. Please dont share with any one.</div><br />
											<div><label>Issuer: </label><label>'.$email.'</label></div>
											<div><label>Hex Code: </label><label>'.OTP_SERVER.'</label></div>
											<div><label>Secret key: </label><label>'.$secretkey.'</label></div>
											<div><label>OTP Type: </label><label>'.$otptype.'</label></div>
											<div><label>Digits: </label><label>'.$digits.'</label></div>
											<div><label>Algorithm: </label><label>'.$algorithm.'</label></div>
											<div><label>Interval: </label><label>'.$interval.'</label></div><br />
											<div>Please scan the below qr code to auto save the details</div><br />
											Note: This is an auto generated email. For more information contact Kadick Admin.<br />
											Generated @'.$current_time.' WAT<br /><br />';
							
								mailSend($email_array, $body, $subject,$file_name2);
								$msg = "User: $firstName $lastName [$username] inserted successfully..";
							}
						}
					}			
				}
				else {
					$msg = "User Name already used..Please try another User Name";
					error_log("UserName = $username screen message $msg Db count = ".$count);
				}
			}			
		}		
		echo $msg;
	}
	else if($action == "usercheck") {
		$userName = $data->userName;
		$query = "SELECT UPPER(user_name) FROM user WHERE user_name = UPPER('".$userName."') ORDER BY user_id";
		error_log("Use Name check ".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			exit();
		}
		else {
			$rowcount=mysqli_num_rows($result);
			echo $rowcount;
			//error_log("RowCount".$rowcount);
		}	
		
	}	
?>	