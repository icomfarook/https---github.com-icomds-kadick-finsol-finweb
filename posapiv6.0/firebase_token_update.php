<?php
	include('../common/admin/configmysql.php');
	include ("get_prime.php");	
	include ("functions.php");
	require_once ("AesCipher.php");
	error_log("inside pcposapi/firebase_token_update.php");

	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		error_log("inside post request method");
		$data = json_decode(file_get_contents("php://input"));
		error_log("firebase_token_update <== ".json_encode($data));
		
		if( isset($data -> operation) && ($data -> operation == 'FB_APP_INSTALL_PRELOGIN_OPERATION' || $data -> operation == 'FB_APP_INSTALL_POSTLOGIN_OPERATION') )  {
			error_log("inside FB_APP_INSTALL_PRELOGIN_OPERATION || FB_APP_INSTALL_POSTLOGIN_OPERATION");
			if ( isset($data -> fbToken ) && !empty($data -> fbToken) && isset($data -> key1 ) && !empty($data -> key1) 
				&& isset($data->signature) && !empty($data->signature) && isset($data->imei) && !empty($data->imei)) {
				
				error_log("inside all inputs are set correctly");
				$fbToken = $data -> fbToken;
				$imei = $data -> imei;
				$userId = $data->userId;
				
				$signature = $data->signature;
				$key1 = $data->key1;
				$session_validity = AGENT_FULL_DAY_SESSION_VALID_TIME;
			 
				if ( isset($data -> deviceType ) && !empty($data -> deviceType)  ) {
					$deviceType = $data->deviceType;
				}else {
					$deviceType = "M";
				}
				error_log("signature = ".$signature.", key1 = ".$key1);
				date_default_timezone_set('Africa/Lagos');
				$nday = date('z')+1;
				$nyear = date('Y');
				$nth_day_prime = get_prime($nday);
				$nth_year_day_prime = get_prime($nday+$nyear);
				$local_signature = $nday + $nth_day_prime;
				error_log("local_signature = ".$local_signature);
				$server_signature = $nth_year_day_prime + $nday + $nyear;
				error_log("server_signature = ".$server_signature);
				
				if ( $local_signature == $signature ){	
				
					$skey0 = date("mdY");
					$skey1 = $nday;
					$skey2 = $local_signature;
					$skeya = str_pad($skey0.$skey1.$skey2, 16, '0', STR_PAD_LEFT);
					$skeyb = str_pad($skey2.$skey1.$skey0, 16, '0', STR_PAD_LEFT);
					//error_log("skeya = ".$skeya.", skeyb = ".$skeyb);
					
					if ( $data -> operation == 'FB_APP_INSTALL_PRELOGIN_OPERATION' ) {
						error_log("inside FB_APP_INSTALL_PRELOGIN_OPERATION");
						error_log("before calling Security::decrypt");
						$key1_result = AesCipher::decrypt($skeya, $key1);
						error_log("after calling Security::decrypt");
						//error_log("key1_result = ".$key1_result);
						$tilda_found = strpos($key1_result, '~');
						if ( $tilda_found == false ) {
							$response["statusCode"] = 660;
							$response["result"] = "Error";
							$response["message"] = "Invalid Client Request...";
							error_log(json_encode($response));
							echo json_encode($response);
							return;
						}
						$key1_array = explode("~", $key1_result);
						$device_sno = $key1_array[0];
						$app_version = $key1_array[1];
						$device_location = $key1_array[2];
						$device_api = $key1_array[3];
						$apk_type = $key1_array[4];
						error_log("device_sno = ".$device_sno.", app_version = ".$app_version.", device_location = ".$device_location.", device_api = ".$device_api.", apk_type = ".$apk_type);
						if (is_null($device_sno) )  $device_sno = "-";
						if (is_null($app_version) )  $app_version = "-";
						if (is_null($device_location) )  $device_location = "-";
						if (is_null($device_api) )  $device_api = "-";
						if (is_null($apk_type) )  $apk_type = "-";
						error_log("set ==> device_sno = ".$device_sno.", app_version = ".$app_version.", device_location = ".$device_location.", device_api = ".$device_api.", apk_type = ".$apk_type);
					}else {
						error_log("inside FB_APP_INSTALL_POSTLOGIN_OPERATION");
						$validate_result = validateKey1($key1, $userId, $session_validity, 'R', $con);
						error_log("validateKey1 result = ".$validate_result);
						if ( $validate_result != 0 ) {
							// Invalid key1 - Session Timeut
							$response["statusCode"] = "999";
							$response["result"] = "Error";
							$response["message"] = "Failure: Session Timeout";
							$response["signature"] = 0;
							error_log(json_encode($response));
							echo json_encode($response);
							return;
						}	 
					}
										
					$query = "SELECT imei, firebase_token, status FROM installed_user WHERE imei = '$imei'";
					error_log("query = ".$query);
					$result = mysqli_query($con, $query);
					if ($result) {
						$count = mysqli_num_rows($result);
						if($count > 0) {
							error_log("count > 0");
							$row = mysqli_fetch_assoc($result);
							$db_fb_token = $row['firebase_token'];
							error_log("db_fb_token = ".$db_fb_token.", fbToken = ".$fbToken);
							$fb_update_query = "UPDATE installed_user set status = 'I', firebase_token = '$fbToken', user_id = $userId, update_time = now() WHERE imei = '$imei'";
							error_log("fb_update_query update: ".$fb_update_query);
							$fb_update_result = mysqli_query($con, $fb_update_query);
							if ( !$fb_update_result ) {
								$response["statusCode"] = "190";
								$response["result"] = "Failure";
								$response["signature"] = $server_signature;
								$response["message"] = "Error - FB Status update Failed";
								error_log("Error in updating  Firebase Token ");
							}
							else {
								$response["statusCode"] = "0";
								$response["result"] = "Success";
								$response["signature"] = $server_signature;
								$response["message"] = "FB Status update Successfull";
								error_log("Success in updating Firebase Token");
							}
						}else{
							$insert_query = "INSERT INTO installed_user (installed_user_id, imei, firebase_token, status, user_id, device_type, create_time) VALUES (0, '$imei', '$fbToken', 'I', $userId, '$deviceType', now())";
							error_log("insert_query = ".$insert_query);
							$insert_result = mysqli_query($con, $insert_query);
							if( $insert_result ) {
								error_log("Insert - Firebase Token Registration Successfully");
								$response["statusCode"] = "0";
								$response["result"] = "Success";
								$response["signature"] = $server_signature;
								$response["message"] = "Firebase Token Registered Successfully";
							}else{
								error_log("Insert - Firebase Token Registration Failed");
								$response["statusCode"] = "290";
								$response["result"] = "Failure";
								$response["signature"] = $server_signature;
								$response["message"] = "Firebase Token Registration Failed";
							}
						}
					}else{
						$response["statusCode"] = "380";
						$response["result"] = "Failure";
						$response["signature"] = $server_signature;
						$response["message"] = "Error - ".mysqli_error($con);
					}
				}	
				else {
					$response["statusCode"] = "390";
					$response["result"] = "Failure";
					$response["message"] = "Invalid signature";
				}
			}
			else {
				// Invalid Data
				$response["statusCode"] = "400";
				$response["result"] = "Error";
				$response["message"] = "Failure: Invalid Data";
				$response["signature"] = 0;
			}
		}
		else if( isset($data -> operation) && ($data -> operation == 'FB_APP_OPEN_PRELOGIN_OPERATION' || $data -> operation == 'FB_APP_OPEN_POSTLOGIN_OPERATION') )  {
			error_log("inside FB_APP_OPEN_PRELOGIN_OPERATION || FB_APP_OPEN_POSTLOGIN_OPERATION");
			if ( isset($data -> fbToken ) && !empty($data -> fbToken) && isset($data -> key1 ) && !empty($data -> key1) 
				&& isset($data->signature) && !empty($data->signature) && isset($data->imei) && !empty($data->imei)) {
				
				error_log("inside all inputs are set correctly");
				$fbToken = $data -> fbToken;
				$imei = $data -> imei;
				$userId = $data->userId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$session_validity = AGENT_FULL_DAY_SESSION_VALID_TIME;
			 
				if ( isset($data -> deviceType ) && !empty($data -> deviceType)  ) {
					$deviceType = $data->deviceType;
				}else {
					$deviceType = "M";
				}

				error_log("signature = ".$signature.", key1 = ".$key1);
				date_default_timezone_set('Africa/Lagos');
				$nday = date('z')+1;
				$nyear = date('Y');
				$nth_day_prime = get_prime($nday);
				$nth_year_day_prime = get_prime($nday+$nyear);
				$local_signature = $nday + $nth_day_prime;
				error_log("local_signature = ".$local_signature);
				$server_signature = $nth_year_day_prime + $nday + $nyear;
				error_log("server_signature = ".$server_signature);
				
				if ( $local_signature == $signature ){	
				
					$skey0 = date("mdY");
					$skey1 = $nday;
					$skey2 = $local_signature;
					$skeya = str_pad($skey0.$skey1.$skey2, 16, '0', STR_PAD_LEFT);
					$skeyb = str_pad($skey2.$skey1.$skey0, 16, '0', STR_PAD_LEFT);
					//error_log("skeya = ".$skeya.", skeyb = ".$skeyb);
					
					if ( $data -> operation == 'FB_APP_OPEN_PRELOGIN_OPERATION' ) {
						error_log("inside FB_APP_OPEN_PRELOGIN_OPERATION");
						error_log("before calling Security::decrypt");
						$key1_result = AesCipher::decrypt($skeya, $key1);
						error_log("after calling Security::decrypt");
						//error_log("key1_result = ".$key1_result);
						$tilda_found = strpos($key1_result, '~');
						if ( $tilda_found == false ) {
							$response["statusCode"] = 660;
							$response["result"] = "Error";
							$response["message"] = "Invalid Client Request...";
							error_log(json_encode($response));
							echo json_encode($response);
							return;
						}
						$key1_array = explode("~", $key1_result);
						$device_sno = $key1_array[0];
						$app_version = $key1_array[1];
						$device_location = $key1_array[2];
						$device_api = $key1_array[3];
						$apk_type = $key1_array[4];
						error_log("device_sno = ".$device_sno.", app_version = ".$app_version.", device_location = ".$device_location.", device_api = ".$device_api.", apk_type = ".$apk_type);
						if (is_null($device_sno) )  $device_sno = "-";
						if (is_null($app_version) )  $app_version = "-";
						if (is_null($device_location) )  $device_location = "-";
						if (is_null($device_api) )  $device_api = "-";
						if (is_null($apk_type) )  $apk_type = "-";
						error_log("set ==> device_sno = ".$device_sno.", app_version = ".$app_version.", device_location = ".$device_location.", device_api = ".$device_api.", apk_type = ".$apk_type);
					}else {
						error_log("inside FB_APP_OPEN_POSTLOGIN_OPERATION");
						$validate_result = validateKey1($key1, $userId, $session_validity, 'R', $con);
						error_log("validateKey1 result = ".$validate_result);
						if ( $validate_result != 0 ) {
							// Invalid key1 - Session Timeut
							$response["statusCode"] = "999";
							$response["result"] = "Error";
							$response["message"] = "Failure: Session Timeout";
							$response["signature"] = 0;
							error_log(json_encode($response));
							echo json_encode($response);
							return;
						}	 
					}
										
					$query = "SELECT imei, firebase_token, status FROM installed_user WHERE imei = '$imei'";
					error_log("query = ".$query);
					$result = mysqli_query($con, $query);
					if ($result) {
						$count = mysqli_num_rows($result);
						if($count > 0) {
							error_log("count > 0");
							$row = mysqli_fetch_assoc($result);
							$db_fb_token = $row['firebase_token'];
							$db_status = $row['status'];
							error_log("db_fb_token = ".$db_fb_token.", fbToken = ".$fbToken.", db_status = ".$db_status);
							if ( $db_status == 'I' || $db_status == 'O') {
								$fb_update_query = "UPDATE installed_user set status = 'O', firebase_token = '$fbToken', user_id = $userId, update_time = now() WHERE imei = '$imei'";
								error_log("fb_update_query update: ".$fb_update_query);
								$fb_update_result = mysqli_query($con, $fb_update_query);
								if ( !$fb_update_result ) {
									$response["statusCode"] = "190";
									$response["result"] = "Failure";
									$response["signature"] = $server_signature;
									$response["message"] = "Error - FB Status Open Update Failed";
									error_log("Error in updating  Firebase Token ");
								}
								else {
									$response["statusCode"] = "0";
									$response["result"] = "Success";
									$response["signature"] = $server_signature;
									$response["message"] = "FB Status Open Update Successfull";
									error_log("Success in updating Firebase Token");
								}
							}else {
								error_log("status is past I or O, no update is required");
								$response["statusCode"] = "0";
								$response["result"] = "Success";
								$response["signature"] = $server_signature;
								$response["message"] = "FB Status Open Update already present";
							}
						}else{
							$insert_query = "INSERT INTO installed_user (installed_user_id, imei, firebase_token, status, user_id, device_type, create_time) VALUES (0, '$imei', '$fbToken', 'O', $userId, '$deviceType', now())";
							error_log("insert_query = ".$insert_query);
							$insert_result = mysqli_query($con, $insert_query);
							if( $insert_result ) {
								error_log("Insert - Firebase Token Insert Open Successfully");
								$response["statusCode"] = "0";
								$response["result"] = "Success";
								$response["signature"] = $server_signature;
								$response["message"] = "Firebase Token Registered Successfully";
							}else{
								error_log("Insert - Firebase Token Insert Open Failed");
								$response["statusCode"] = "290";
								$response["result"] = "Failure";
								$response["signature"] = $server_signature;
								$response["message"] = "Firebase Token Insert Open Failed";
							}
						}
					}else{
						$response["statusCode"] = "380";
						$response["result"] = "Failure";
						$response["signature"] = $server_signature;
						$response["message"] = "Error - ".mysqli_error($con);
					}
				}	
				else {
					$response["statusCode"] = "390";
					$response["result"] = "Failure";
					$response["message"] = "Invalid signature";
				}
			}
			else {
				// Invalid Data
				$response["statusCode"] = "400";
				$response["result"] = "Error";
				$response["message"] = "Failure: Invalid Data";
				$response["signature"] = 0;
			}
		}
		else if( isset($data -> operation) && ($data -> operation == 'FB_APP_REGISTER_PRELOGIN_OPERATION' || $data -> operation == 'FB_APP_REGISTER_POSTLOGIN_OPERATION') )  {
			error_log("inside FB_APP_REGISTER_PRELOGIN_OPERATION || FB_APP_REGISTER_POSTLOGIN_OPERATION");
			if ( isset($data -> fbToken ) && !empty($data -> fbToken) && isset($data -> key1 ) && !empty($data -> key1) 
				&& isset($data->signature) && !empty($data->signature) && isset($data->imei) && !empty($data->imei)) {
				
				error_log("inside all inputs are set correctly");
				$fbToken = $data -> fbToken;
				$imei = $data -> imei;
				$userId = $data->userId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$session_validity = AGENT_FULL_DAY_SESSION_VALID_TIME;
			 
				if ( isset($data -> deviceType ) && !empty($data -> deviceType)  ) {
					$deviceType = $data->deviceType;
				}else {
					$deviceType = "M";
				}

				error_log("signature = ".$signature.", key1 = ".$key1);
				date_default_timezone_set('Africa/Lagos');
				$nday = date('z')+1;
				$nyear = date('Y');
				$nth_day_prime = get_prime($nday);
				$nth_year_day_prime = get_prime($nday+$nyear);
				$local_signature = $nday + $nth_day_prime;
				error_log("local_signature = ".$local_signature);
				$server_signature = $nth_year_day_prime + $nday + $nyear;
				error_log("server_signature = ".$server_signature);
				
				if ( $local_signature == $signature ){	
				
					$skey0 = date("mdY");
					$skey1 = $nday;
					$skey2 = $local_signature;
					$skeya = str_pad($skey0.$skey1.$skey2, 16, '0', STR_PAD_LEFT);
					$skeyb = str_pad($skey2.$skey1.$skey0, 16, '0', STR_PAD_LEFT);
					//error_log("skeya = ".$skeya.", skeyb = ".$skeyb);
					
					if ( $data -> operation == 'FB_APP_REGISTER_PRELOGIN_OPERATION' ) {
						error_log("inside FB_APP_REGISTER_PRELOGIN_OPERATION");
						error_log("before calling Security::decrypt");
						$key1_result = AesCipher::decrypt($skeya, $key1);
						error_log("after calling Security::decrypt");
						//error_log("key1_result = ".$key1_result);
						$tilda_found = strpos($key1_result, '~');
						if ( $tilda_found == false ) {
							$response["statusCode"] = 660;
							$response["result"] = "Error";
							$response["message"] = "Invalid Client Request...";
							error_log(json_encode($response));
							echo json_encode($response);
							return;
						}
						$key1_array = explode("~", $key1_result);
						$device_sno = $key1_array[0];
						$app_version = $key1_array[1];
						$device_location = $key1_array[2];
						$device_api = $key1_array[3];
						$apk_type = $key1_array[4];
						error_log("device_sno = ".$device_sno.", app_version = ".$app_version.", device_location = ".$device_location.", device_api = ".$device_api.", apk_type = ".$apk_type);
						if (is_null($device_sno) )  $device_sno = "-";
						if (is_null($app_version) )  $app_version = "-";
						if (is_null($device_location) )  $device_location = "-";
						if (is_null($device_api) )  $device_api = "-";
						if (is_null($apk_type) )  $apk_type = "-";
						error_log("set ==> device_sno = ".$device_sno.", app_version = ".$app_version.", device_location = ".$device_location.", device_api = ".$device_api.", apk_type = ".$apk_type);
					}else {
						error_log("inside FB_APP_OPEN_POSTLOGIN_OPERATION");
						$validate_result = validateKey1($key1, $userId, $session_validity, 'R', $con);
						error_log("validateKey1 result = ".$validate_result);
						if ( $validate_result != 0 ) {
							// Invalid key1 - Session Timeut
							$response["statusCode"] = "999";
							$response["result"] = "Error";
							$response["message"] = "Failure: Session Timeout";
							$response["signature"] = 0;
							error_log(json_encode($response));
							echo json_encode($response);
							return;
						}	 
					}
										
					$query = "SELECT imei, firebase_token, status FROM installed_user WHERE imei = '$imei'";
					error_log("query = ".$query);
					$result = mysqli_query($con, $query);
					if ($result) {
						$count = mysqli_num_rows($result);
						if($count > 0) {
							error_log("count > 0");
							$row = mysqli_fetch_assoc($result);
							$db_fb_token = $row['firebase_token'];
							$db_status = $row['status'];
							error_log("db_fb_token = ".$db_fb_token.", fbToken = ".$fbToken.", db_status = ".$db_status);
							if ( $db_status == 'I' || $db_status == 'O' || $db_status == 'R') {
								$fb_update_query = "UPDATE installed_user set status = 'R', firebase_token = '$fbToken', user_id = $userId, update_time = now() WHERE imei = '$imei'";
								error_log("fb_update_query update: ".$fb_update_query);
								$fb_update_result = mysqli_query($con, $fb_update_query);
								if ( !$fb_update_result ) {
									$response["statusCode"] = "190";
									$response["result"] = "Failure";
									$response["signature"] = $server_signature;
									$response["message"] = "Error - FB Status Register Update Failed";
									error_log("Error in updating  Firebase Token ");
								}
								else {
									$response["statusCode"] = "0";
									$response["result"] = "Success";
									$response["signature"] = $server_signature;
									$response["message"] = "FB Status Register Update Successfull";
									error_log("Success in updating Firebase Token");
								}
							}else {
								error_log("status is past I or O or R, no update is required");
								$response["statusCode"] = "0";
								$response["result"] = "Success";
								$response["signature"] = $server_signature;
								$response["message"] = "FB Status Register Update already present";
							}
						}else{
							$insert_query = "INSERT INTO installed_user (installed_user_id, imei, firebase_token, status, user_id, device_type, create_time) VALUES (0, '$imei', '$fbToken', 'R', $userId, '$deviceType', now())";
							error_log("insert_query = ".$insert_query);
							$insert_result = mysqli_query($con, $insert_query);
							if( $insert_result ) {
								error_log("Insert - Firebase Token Insert Register Successfully");
								$response["statusCode"] = "0";
								$response["result"] = "Success";
								$response["signature"] = $server_signature;
								$response["message"] = "Firebase Token Insert Register Successfully";
							}else{
								error_log("Insert - Firebase Token Insert Register Failed");
								$response["statusCode"] = "290";
								$response["result"] = "Failure";
								$response["signature"] = $server_signature;
								$response["message"] = "Firebase Token Insert Register Failed";
							}
						}
					}else{
						$response["statusCode"] = "380";
						$response["result"] = "Failure";
						$response["signature"] = $server_signature;
						$response["message"] = "Error - ".mysqli_error($con);
					}
				}	
				else {
					$response["statusCode"] = "390";
					$response["result"] = "Failure";
					$response["message"] = "Invalid signature";
				}
			}
			else {
				// Invalid Data
				$response["statusCode"] = "400";
				$response["result"] = "Error";
				$response["message"] = "Failure: Invalid Data";
				$response["signature"] = 0;
			}
		}
		else if( isset($data -> operation) && ($data -> operation == 'FB_APP_LOGIN_PRELOGIN_OPERATION' || $data -> operation == 'FB_APP_LOGIN_POSTLOGIN_OPERATION') )  {
			error_log("inside FB_APP_LOGIN_PRELOGIN_OPERATION || FB_APP_LOGIN_POSTLOGIN_OPERATION");
			if ( isset($data -> fbToken ) && !empty($data -> fbToken) && isset($data -> key1 ) && !empty($data -> key1) 
				&& isset($data->signature) && !empty($data->signature) && isset($data->imei) && !empty($data->imei)) {
				
				error_log("inside all inputs are set correctly");
				$fbToken = $data -> fbToken;
				$imei = $data -> imei;
				$userId = $data->userId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$session_validity = AGENT_FULL_DAY_SESSION_VALID_TIME;
			 
				if ( isset($data -> deviceType ) && !empty($data -> deviceType)  ) {
					$deviceType = $data->deviceType;
				}else {
					$deviceType = "M";
				}

				error_log("signature = ".$signature.", key1 = ".$key1);
				date_default_timezone_set('Africa/Lagos');
				$nday = date('z')+1;
				$nyear = date('Y');
				$nth_day_prime = get_prime($nday);
				$nth_year_day_prime = get_prime($nday+$nyear);
				$local_signature = $nday + $nth_day_prime;
				error_log("local_signature = ".$local_signature);
				$server_signature = $nth_year_day_prime + $nday + $nyear;
				error_log("server_signature = ".$server_signature);
				
				if ( $local_signature == $signature ){	
				
					$skey0 = date("mdY");
					$skey1 = $nday;
					$skey2 = $local_signature;
					$skeya = str_pad($skey0.$skey1.$skey2, 16, '0', STR_PAD_LEFT);
					$skeyb = str_pad($skey2.$skey1.$skey0, 16, '0', STR_PAD_LEFT);
					//error_log("skeya = ".$skeya.", skeyb = ".$skeyb);
					
					if ( $data -> operation == 'FB_APP_LOGIN_PRELOGIN_OPERATION' ) {
						error_log("inside FB_APP_LOGIN_PRELOGIN_OPERATION");
						error_log("before calling Security::decrypt");
						$key1_result = AesCipher::decrypt($skeya, $key1);
						error_log("after calling Security::decrypt");
						//error_log("key1_result = ".$key1_result);
						$tilda_found = strpos($key1_result, '~');
						if ( $tilda_found == false ) {
							$response["statusCode"] = 660;
							$response["result"] = "Error";
							$response["message"] = "Invalid Client Request...";
							error_log(json_encode($response));
							echo json_encode($response);
							return;
						}
						$key1_array = explode("~", $key1_result);
						$device_sno = $key1_array[0];
						$app_version = $key1_array[1];
						$device_location = $key1_array[2];
						$device_api = $key1_array[3];
						$apk_type = $key1_array[4];
						error_log("device_sno = ".$device_sno.", app_version = ".$app_version.", device_location = ".$device_location.", device_api = ".$device_api.", apk_type = ".$apk_type);
						if (is_null($device_sno) )  $device_sno = "-";
						if (is_null($app_version) )  $app_version = "-";
						if (is_null($device_location) )  $device_location = "-";
						if (is_null($device_api) )  $device_api = "-";
						if (is_null($apk_type) )  $apk_type = "-";
						error_log("set ==> device_sno = ".$device_sno.", app_version = ".$app_version.", device_location = ".$device_location.", device_api = ".$device_api.", apk_type = ".$apk_type);
					}else {
						error_log("inside FB_APP_LOGIN_POSTLOGIN_OPERATION");
						$validate_result = validateKey1($key1, $userId, $session_validity, 'R', $con);
						error_log("validateKey1 result = ".$validate_result);
						if ( $validate_result != 0 ) {
							// Invalid key1 - Session Timeut
							$response["statusCode"] = "999";
							$response["result"] = "Error";
							$response["message"] = "Failure: Session Timeout";
							$response["signature"] = 0;
							error_log(json_encode($response));
							echo json_encode($response);
							return;
						}	 
					}
									
					$query = "SELECT imei, firebase_token, status FROM installed_user WHERE imei = '$imei'";
					error_log("query = ".$query);
					$result = mysqli_query($con, $query);
					if ($result) {
						$count = mysqli_num_rows($result);
						if($count > 0) {
							error_log("count > 0");
							$row = mysqli_fetch_assoc($result);
							$db_fb_token = $row['firebase_token'];
							$db_status = $row['status'];
							error_log("db_fb_token = ".$db_fb_token.", fbToken = ".$fbToken.", db_status = ".$db_status);
							if ( $db_status == 'I' || $db_status == 'O' || $db_status = 'R' || $db_status = 'L') {
								$fb_update_query = "UPDATE installed_user set status = 'L', firebase_token = '$fbToken', user_id = $userId, update_time = now() WHERE imei = '$imei'";
								error_log("fb_update_query update: ".$fb_update_query);
								$fb_update_result = mysqli_query($con, $fb_update_query);
								if ( !$fb_update_result ) {
									$response["statusCode"] = "190";
									$response["result"] = "Failure";
									$response["signature"] = $server_signature;
									$response["message"] = "Error - FB Status Login Update Failed";
									error_log("Error in updating Firebase Token for Login");
								}
								else {
									$response["statusCode"] = "0";
									$response["result"] = "Success";
									$response["signature"] = $server_signature;
									$response["message"] = "FB Status Login Update Successfull";
									error_log("Success in updating Firebase Token for Login");
								}
							}else {
								error_log("status is past I or O or R or L, no update is required");
								$response["statusCode"] = "0";
								$response["result"] = "Success";
								$response["signature"] = $server_signature;
								$response["message"] = "Firebase Token Login already present";
							}
						}else{
							$insert_query = "INSERT INTO installed_user (installed_user_id, imei, firebase_token, status, user_id, device_type, create_time) VALUES (0, '$imei', '$fbToken', 'L', $userId, '$deviceType', now())";
							error_log("insert_query = ".$insert_query);
							$insert_result = mysqli_query($con, $insert_query);
							if( $insert_result ) {
								error_log("Insert - Firebase Token Insert Login Successfully");
								$response["statusCode"] = "0";
								$response["result"] = "Success";
								$response["signature"] = $server_signature;
								$response["message"] = "Firebase Token Login Successfully";
							}else{
								error_log("Insert - Firebase Token Insert Login Failed");
								$response["statusCode"] = "290";
								$response["result"] = "Failure";
								$response["signature"] = $server_signature;
								$response["message"] = "Firebase Token Insert Login Failed";
							}
						}
					}else{
						$response["statusCode"] = "380";
						$response["result"] = "Failure";
						$response["signature"] = $server_signature;
						$response["message"] = "Error - ".mysqli_error($con);
					}
				}	
				else {
					$response["statusCode"] = "390";
					$response["result"] = "Failure";
					$response["message"] = "Invalid signature";
				}
			}
			else {
				// Invalid Data
				$response["statusCode"] = "400";
				$response["result"] = "Error";
				$response["message"] = "Failure: Invalid Data";
				$response["signature"] = 0;
			}
		}else if( isset($data -> operation) && ($data -> operation == 'FP_SUBSCRIPTION_NOTIFY_POSTLOGIN_OPERATION' || $data -> operation == 'FP_SUBSCRIPTION_NOTIFY_PRELOGIN_OPERATION') )  {
			error_log("inside FP_SUBSCRIPTION_NOTIFY_POSTLOGIN_OPERATION || FP_SUBSCRIPTION_NOTIFY_PRELOGIN_OPERATION");
			if ( isset($data->key1 ) && !empty($data->key1) && isset($data->type ) && !empty($data->type)
				&& isset($data->status ) && !empty($data->status)
				&& isset($data->signature) && !empty($data->signature) && isset($data->imei) && !empty($data->imei)) {
				
				error_log("inside all inputs are set correctly");
				$fbToken = $data -> fbToken;
				$imei = $data -> imei;
				$userId = $data->userId;
				$status = $data->status;
				$subscription_type = $data->type;
				$topic = $data->topic;
				$signature = $data->signature;
				$key1 = $data->key1;
				$session_validity = AGENT_FULL_DAY_SESSION_VALID_TIME;
				
				if ( isset($data -> deviceType ) && !empty($data -> deviceType)  ) {
					$deviceType = $data->deviceType;
				}else {
					$deviceType = "M";
				}

				error_log("signature = ".$signature.", key1 = ".$key1);
				date_default_timezone_set('Africa/Lagos');
				$nday = date('z')+1;
				$nyear = date('Y');
				$nth_day_prime = get_prime($nday);
				$nth_year_day_prime = get_prime($nday+$nyear);
				$local_signature = $nday + $nth_day_prime;
				error_log("local_signature = ".$local_signature);
				$server_signature = $nth_year_day_prime + $nday + $nyear;
				error_log("server_signature = ".$server_signature);
				
				if ( $local_signature == $signature ){	
				
					$skey0 = date("mdY");
					$skey1 = $nday;
					$skey2 = $local_signature;
					$skeya = str_pad($skey0.$skey1.$skey2, 16, '0', STR_PAD_LEFT);
					$skeyb = str_pad($skey2.$skey1.$skey0, 16, '0', STR_PAD_LEFT);
					//error_log("skeya = ".$skeya.", skeyb = ".$skeyb);
					
					if ( $data -> operation == 'FP_SUBSCRIPTION_NOTIFY_PRELOGIN_OPERATION' ) {
						error_log("inside FP_SUBSCRIPTION_NOTIFY_PRELOGIN_OPERATION");
						error_log("before calling Security::decrypt");
						$key1_result = AesCipher::decrypt($skeya, $key1);
						error_log("after calling Security::decrypt");
						//error_log("key1_result = ".$key1_result);
						$tilda_found = strpos($key1_result, '~');
						if ( $tilda_found == false ) {
							$response["statusCode"] = 660;
							$response["result"] = "Error";
							$response["message"] = "Invalid Client Request...";
							error_log(json_encode($response));
							echo json_encode($response);
							return;
						}
						$key1_array = explode("~", $key1_result);
						$device_sno = $key1_array[0];
						$app_version = $key1_array[1];
						$device_location = $key1_array[2];
						$device_api = $key1_array[3];
						$apk_type = $key1_array[4];
						error_log("device_sno = ".$device_sno.", app_version = ".$app_version.", device_location = ".$device_location.", device_api = ".$device_api.", apk_type = ".$apk_type);
						if (is_null($device_sno) )  $device_sno = "-";
						if (is_null($app_version) )  $app_version = "-";
						if (is_null($device_location) )  $device_location = "-";
						if (is_null($device_api) )  $device_api = "-";
						if (is_null($apk_type) )  $apk_type = "-";
						error_log("set ==> device_sno = ".$device_sno.", app_version = ".$app_version.", device_location = ".$device_location.", device_api = ".$device_api.", apk_type = ".$apk_type);
					}else {
						error_log("inside FP_SUBSCRIPTION_NOTIFY_POSTLOGIN_OPERATION");
						$validate_result = validateKey1($key1, $userId, $session_validity, 'R', $con);
						error_log("validateKey1 result = ".$validate_result);
						if ( $validate_result != 0 ) {
							// Invalid key1 - Session Timeut
							$response["statusCode"] = "999";
							$response["result"] = "Error";
							$response["message"] = "Failure: Session Timeout";
							$response["signature"] = 0;
							error_log(json_encode($response));
							echo json_encode($response);
							return;
						}	 
					}
					error_log("TOPIC_STATE = ".TOPIC_STATE.", TOPIC_LOCAL_GOVT = ".TOPIC_LOCAL_GOVT);
					error_log("strpos value = ".strpos($topic, TOPIC_STATE).", strpos value2 = ". strpos($topic, TOPIC_LOCAL_GOVT));
					if ( strpos($topic, TOPIC_STATE) === 0  || strpos($topic, TOPIC_LOCAL_GOVT) === 0) {
						error_log("inside installed_user_topic..");
						$select_query = "SELECT imei FROM installed_user_topic WHERE imei = '$imei' and topic = '$topic'";
						error_log("select_query = ".$select_query);
						$result = mysqli_query($con, $select_query);
						if ($result) {
							$count = mysqli_num_rows($result);
							if($count > 0) {
								error_log("count > 0");
								$update_query = "update installed_user_topic set update_time = now() where imei = '$imei' and topic = '$topic'";
								error_log("update_query = ".$update_query);
								$result = mysqli_query($con, $update_query);
								if ( $result ) {
									error_log("installed_user_topic is updated successfuly for imei = ".$imei);
								}else {
									error_log("installed_user_topic is not updated for imei = ".$imei);
								}
							}else {
								$insert_query = "insert into installed_user_topic (installed_user_topic_id, imei, topic, user_id, device_type, create_time) values (0, '$imei', '$topic', $userId, '$deviceType', now())";
								error_log("insert_query = ".$insert_query);
								$result = mysqli_query($con, $insert_query);
								if ( $result ) {
									error_log("installed_user_topic is inserted successfuly for imei = ".$imei);
								}else {
									error_log("installed_user_topic is not inserted for imei = ".$imei);
								}
							}
						}else {
							error_log("error in installed_user_topic query");
						}
					}
					
					$insert_query = "INSERT INTO topic_subscription (topic_subscription_id, imei, topic, subscription_type, status, user_id, device_type, create_time) VALUES (0, '$imei', '$topic', '$subscription_type', '$status', $userId, '$deviceType', now())";
					error_log("insert_query = ".$insert_query);
					$insert_result = mysqli_query($con, $insert_query);
					if( $insert_result ) {
						error_log("Insert - Firebase Topic subscription notification inserted Successfully");
						$response["statusCode"] = "0";
						$response["result"] = "Success";
						$response["signature"] = $server_signature;
						$response["message"] = "Firebase Token subscription notification inserted Successfully";
					}else{
						error_log("Insert - Firebase Token subscription notification insert Failed");
						$response["statusCode"] = "290";
						$response["result"] = "Failure";
						$response["signature"] = $server_signature;
						$response["message"] = "Firebase Token subscription notification insert failed";
					}
				}	
				else {
					$response["statusCode"] = "390";
					$response["result"] = "Failure";
					$response["message"] = "Invalid signature";
				}
			}
			else {
				// Invalid Data
				$response["statusCode"] = "400";
				$response["result"] = "Error";
				$response["message"] = "Failure: Invalid Data";
				$response["signature"] = 0;
			}
		}else if( isset($data -> operation) && $data -> operation == 'FB_APP_UNINSTALL_PRELOGIN_OPERATION' )  {
			error_log("inside FB_APP_UNINSTALL_PRELOGIN_OPERATION");
			if ( isset($data -> fbToken ) && !empty($data -> fbToken) && isset($data -> key1 ) && !empty($data -> key1) 
				&& isset($data->signature) && !empty($data->signature) && isset($data->imei) && !empty($data->imei)) {
				
				error_log("inside all inputs are set correctly");
				$fbToken = $data -> fbToken;
				$imei = $data -> imei;
				$userId = $data->userId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$session_validity = AGENT_FULL_DAY_SESSION_VALID_TIME;
					
				if ( isset($data -> deviceType ) && !empty($data -> deviceType)  ) {
					$deviceType = $data->deviceType;
				}else {
					$deviceType = "M";
				}
				
				error_log("signature = ".$signature.", key1 = ".$key1);
				date_default_timezone_set('Africa/Lagos');
				$nday = date('z')+1;
				$nyear = date('Y');
				$nth_day_prime = get_prime($nday);
				$nth_year_day_prime = get_prime($nday+$nyear);
				$local_signature = $nday + $nth_day_prime;
				error_log("local_signature = ".$local_signature);
				$server_signature = $nth_year_day_prime + $nday + $nyear;
				error_log("server_signature = ".$server_signature);
				
				if ( $local_signature == $signature ){	
				
					$skey0 = date("mdY");
					$skey1 = $nday;
					$skey2 = $local_signature;
					$skeya = str_pad($skey0.$skey1.$skey2, 16, '0', STR_PAD_LEFT);
					$skeyb = str_pad($skey2.$skey1.$skey0, 16, '0', STR_PAD_LEFT);
					//error_log("skeya = ".$skeya.", skeyb = ".$skeyb);
					
					if ( $data -> operation == 'FB_APP_INSTALL_PRELOGIN_OPERATION' ) {
						error_log("inside FB_APP_INSTALL_PRELOGIN_OPERATION");
						error_log("before calling Security::decrypt");
						$key1_result = AesCipher::decrypt($skeya, $key1);
						error_log("after calling Security::decrypt");
						//error_log("key1_result = ".$key1_result);
						$tilda_found = strpos($key1_result, '~');
						if ( $tilda_found == false ) {
							$response["statusCode"] = 660;
							$response["result"] = "Error";
							$response["message"] = "Invalid Client Request...";
							error_log(json_encode($response));
							echo json_encode($response);
							return;
						}
						$key1_array = explode("~", $key1_result);
						$device_sno = $key1_array[0];
						$app_version = $key1_array[1];
						$device_location = $key1_array[2];
						$device_api = $key1_array[3];
						$apk_type = $key1_array[4];
						error_log("device_sno = ".$device_sno.", app_version = ".$app_version.", device_location = ".$device_location.", device_api = ".$device_api.", apk_type = ".$apk_type);
						if (is_null($device_sno) )  $device_sno = "-";
						if (is_null($app_version) )  $app_version = "-";
						if (is_null($device_location) )  $device_location = "-";
						if (is_null($device_api) )  $device_api = "-";
						if (is_null($apk_type) )  $apk_type = "-";
						error_log("set ==> device_sno = ".$device_sno.", app_version = ".$app_version.", device_location = ".$device_location.", device_api = ".$device_api.", apk_type = ".$apk_type);
					}else {
						error_log("inside FB_APP_INSTALL_POSTLOGIN_OPERATION");
						$validate_result = validateKey1($key1, $userId, $session_validity, 'R', $con);
						error_log("validateKey1 result = ".$validate_result);
						if ( $validate_result != 0 ) {
							// Invalid key1 - Session Timeut
							$response["statusCode"] = "999";
							$response["result"] = "Error";
							$response["message"] = "Failure: Session Timeout";
							$response["signature"] = 0;
							error_log(json_encode($response));
							echo json_encode($response);
							return;
						}	 
					}
										
					$query = "SELECT imei, firebase_token, status FROM installed_user WHERE imei = '$imei'";
					error_log("query = ".$query);
					$result = mysqli_query($con, $query);
					if ($result) {
						$count = mysqli_num_rows($result);
						if($count > 0) {
							error_log("count > 0");
							$row = mysqli_fetch_assoc($result);
							$db_fb_token = $row['firebase_token'];
							error_log("db_fb_token = ".$db_fb_token.", fbToken = ".$fbToken);
							$fb_update_query = "UPDATE installed_user set status = 'U', firebase_token = '$fbToken', user_id = $userId, update_time = now() WHERE imei = '$imei'";
							error_log("fb_update_query update: ".$fb_update_query);
							$fb_update_result = mysqli_query($con, $fb_update_query);
							if ( !$fb_update_result ) {
								$response["statusCode"] = "190";
								$response["result"] = "Failure";
								$response["signature"] = $server_signature;
								$response["message"] = "Error - FB Status update Failed";
								error_log("Error in updating  Firebase Token ");
							}
							else {
								$response["statusCode"] = "0";
								$response["result"] = "Success";
								$response["signature"] = $server_signature;
								$response["message"] = "FB Status update Successfull";
								error_log("Success in updating Firebase Token");
							}
						}else{
							$insert_query = "INSERT INTO installed_user (installed_user_id, imei, firebase_token, status, user_id, device_type, create_time) VALUES (0, '$imei', '$fbToken', 'U', $userId, '$deviceType', now())";
							error_log("insert_query = ".$insert_query);
							$insert_result = mysqli_query($con, $insert_query);
							if( $insert_result ) {
								error_log("Insert - Firebase Token Registration Successfully");
								$response["statusCode"] = "0";
								$response["result"] = "Success";
								$response["signature"] = $server_signature;
								$response["message"] = "Firebase Token Registered Successfully";
							}else{
								error_log("Insert - Firebase Token Registration Failed");
								$response["statusCode"] = "290";
								$response["result"] = "Failure";
								$response["signature"] = $server_signature;
								$response["message"] = "Firebase Token Registration Failed";
							}
						}
					}else{
						$response["statusCode"] = "380";
						$response["result"] = "Failure";
						$response["signature"] = $server_signature;
						$response["message"] = "Error - ".mysqli_error($con);
					}
				}	
				else {
					$response["statusCode"] = "390";
					$response["result"] = "Failure";
					$response["message"] = "Invalid signature";
				}
			}
			else {
				// Invalid Data
				$response["statusCode"] = "400";
				$response["result"] = "Error";
				$response["message"] = "Failure: Invalid Data";
				$response["signature"] = 0;
			}
		}else{
			// Invalid Operation
			$response["result"] = "success";
			$response["statusCode"] = "500";
			$response["message"] = "Invalid Operation";
			$response["signature"] = 0;
		}
	}
	else {
		// Invalid Request Method
		$response["result"] = "success";
		$response["statusCode"] = "600";
		$response["message"] = "Post Failure";
		$response["signature"] = 0;
	}
    	error_log("order_update ==> ".json_encode($response));
	echo json_encode($response);
?>
