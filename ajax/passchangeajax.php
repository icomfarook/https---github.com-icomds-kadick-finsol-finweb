<?php
include('../common/sessioncheck.php');
include("../common/admin/finsol_crypt.php");
include('../common/admin/configmysql.php');
//$action = $data->action;
	//$profile_id = $_SESSION['profile_id'];
	$data = json_decode(file_get_contents("php://input"));	
	//$uname = $data->username;
	$uname = $_SESSION['user_name'];
	$passwordtype= $data->passwordtype;
	$oladpassword =  $data->curpassword;
	$password = $data->newpassword;
	$repassword = $data->renewpassword;	
	$user_check_query = "SELECT user_id,user_name, password FROM user WHERE UPPER(user_name) = UPPER('".$uname."') limit 1";
	error_log("user_check_query".$user_check_query);
	$user_check_result = mysqli_query($con,$user_check_query);
	if ( $user_check_result ) {
		$user_check_count = mysqli_num_rows($user_check_result);
		if($user_check_count > 0){
			$row = mysqli_fetch_array($user_check_result);
			$passworddata = $row['password'];
			$username = $row['user_name'];
			$user_id = $row['user_id'];			
			$successpassword = ckdecrypt($oladpassword, $passworddata);	
			error_log("@@@@username = ".$uname.": successpassword = ".$successpassword);
				if($successpassword || $successpassword!="") {
					$hash_password = ckencrypt($password);
					$hash_password = mysqli_real_escape_string($con,$hash_password);
					if($passwordtype == 'L') {
					 $user_table_update_query = "UPDATE user SET password = '$hash_password' where user_id = ".$user_id." and UPPER(user_name) = UPPER('".$username."')";
					}
					if($passwordtype == 'T') {
					 $user_table_update_query = "UPDATE user SET transaction_password = '$hash_password' where user_id = ".$user_id." and UPPER(user_name) = UPPER('".$username."')";
					}
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
				error_log("@@@@username = ".$uname.": In-correct Old Password");
				$msg = "In-Correct Old Password";		
			}				
			}else {	
				error_log("@@@@username = ".$uname.": donot exists");
				$msg = "Invalid User Name/Password/Token";		
			}						
		}else {
			error_log("@@@@username = ".$uname." User DB2 read failure: ".mysqli_error($con));
			die("User DB2 read failure: ".mysqli_error($con));
		}
	echo $msg;
?>