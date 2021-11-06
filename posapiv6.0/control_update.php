<?php
	include('../common/admin/configmysql.php');
	include("../common/admin/finsol_crypt.php");
	include ("get_prime.php");	
	require_once ("AesCipher.php");
    	require_once("db_connect.php");
    	include ("functions.php");
	error_log("inside pcposapi/control_update.php");
	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		error_log("inside post request method");
        	$data = json_decode(file_get_contents("php://input"));
        	error_log("link_account <== ".json_encode($data));

		if(isset($data -> operation) && $data -> operation == 'CONTROL_UPDATE') {
			error_log("inside operation == CONTROL_UPDATE method");
            		if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
			   && isset($data->key2) && !empty($data->key2) && isset($data->controlType) && !empty($data->controlType)
			   && isset($data->countryId) && !empty($data->countryId) && isset($data->userId) && !empty($data->userId) 
			){

				error_log("inside all inputs are set correctly");
				$controlType = $data->controlType;
				$countryId = $data->countryId;
				$userId = $data->userId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$key2 = $data->key2;
				$session_validity = AGENT_SESSION_VALID_TIME;
				$admin_attempt_limit = ADMIN_ATTEMPT_LIMIT;
				$admin_password_valid_day = 90;

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
                                
				if ( $local_signature == $signature ) {
                    			$validate_result = validateKey1($key1, $userId, $session_validity, 'Y', $con);
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
                    			
                    			$session_key = getValidSessionKey($userId, $con);
                    			if ( strlen($session_key) > 1 ) {
						try {
							error_log("before calling Security::decrypt");
							$key2_result = AesCipher::decrypt($session_key, $key2);
							error_log("after calling Security::decrypt");
							error_log("key2_result = ".$key2_result);
							$tilda_found = strpos($key2_result, "~".$controlType."~");
							if ( $tilda_found == false ) {
								$response["statusCode"] = 660;
								$response["result"] = "Error";
								$response["message"] = "Invalid Client Request...";
								error_log(json_encode($response));
								echo json_encode($response);
								return;
							}
							$key2_array = explode("~".$controlType."~", $key2_result);
							$newControl = $key2_array[0];
							$currentControl = $key2_array[1];
							$location = $key2_array[2];
							$ltime = $key2_array[3];
							error_log ("newControl = ".$newControl.", currentControl = ".$currentControl.", location = ".$location);
							
							$select_user_query = "select ifnull(invalid_attempt,0) as invalid_attempt from user where user_id = $userId and active = 'Y' and (locked is null or locked = 'N')"; 
							error_log("select_user_query = ".$select_user_query);
							$invalid_attempt = -1;
							$invaidUserFlag = "Y";
							$select_user_result = mysqli_query($con, $select_user_query);
							if ( $select_user_result ) {
								error_log("1");
								$select_user_count = mysqli_num_rows($select_user_result);
								if ( $select_user_count == 1 ) {
									error_log("2");
									$select_user_row = mysqli_fetch_assoc($select_user_result);
									$invalid_attempt = $select_user_row['invalid_attempt'];
									if ( $invalid_attempt == -1 || $invalid_attempt <= $admin_attempt_limit ) {
										$invaidUserFlag = "N";
									}
								}									
							}
							error_log("invaidUserFlag = ".$invaidUserFlag);
							
							if ( $invaidUserFlag != "N" ) {
								$response["statusCode"] = 980;
								$response["result"] = "Error";
								$response["message"] = "User is not active/locked";
								error_log(json_encode($response));
								echo json_encode($response);
								return;
							}
								
							if ( $controlType == "T" ) {
								error_log("inside controlType = T for user_id = ".$userId);
								if ( (strlen($currentControl) == 10) && (strlen($newControl) == 10) ) {
									$current_otp = substr($currentControl, 0, 6);
									$current_pin = substr($currentControl, 6, 4);
									$select_user_otp_query = "select user_id from user_otp where user_id = $userId and otp_dynamic = 'N' and otp_value = '$current_otp' and pin = '$current_pin'";
									error_log("select_user_otp_query = ".$select_user_otp_query);
									$select_user_otp_result = mysqli_query($con, $select_user_otp_query);
									if ( $select_user_otp_result ) {
										$select_user_otp_count = mysqli_num_rows($select_user_otp_result);
										if ( $select_user_otp_count == 1 ) {
											$select_password_history_query = "select password_history_check($userId, '$newControl', null, $admin_password_valid_day, '$controlType') as result";
											error_log("select_password_history_query = ".$select_password_history_query);
											$select_password_history_result = mysqli_query($con, $select_password_history_query);
											if ( $select_password_history_result ) {
												$select_password_history_row = mysqli_fetch_assoc($select_password_history_result);
												$password_status_result = $select_password_history_row['result'];
												if ( $password_status_result == 0 ) {
													$response["result"] = "Success";
													$response["message"] = "Control Update is successful";
													$response["statusCode"] = $password_status_result;
						    							$response["signature"] = $server_signature;
												}else {
													$response["result"] = "Error";
													$response["message"] = "Already used control input";
													$response["statusCode"] = $password_status_result;
						    							$response["signature"] = $server_signature;
												}
											}else {
												$response["result"] = "Error";
												$response["message"] = "DB Error in password history";
												$response["statusCode"] = 100;
						    						$response["signature"] = $server_signature;
											}
										}else {
											$invalid_attempt = $invalid_attempt+1;
											if($invalid_attempt >= $admin_attempt_limit) {
												$admin_login_invalid_attempt_update = "UPDATE user SET invalid_attempt = COALESCE(invalid_attempt,0)+1 ,locked = 'Y', locked_time = now() where user_id =  ".$userId;
											}else {
												$admin_login_invalid_attempt_update = "UPDATE user SET invalid_attempt = COALESCE(invalid_attempt,0)+1 where user_id =  ".$userId;									
											}
											$admin_login_invalid_attempt_result = mysqli_query($con, $admin_login_invalid_attempt_update);
											if ( !$admin_login_invalid_attempt_result ) {
												error_log("Error in updating the user table for invalid_attempt = ".$invalid_attempt);
											}
											
											$controlId = -1;
											$response["result"] = "Error";
											$response["message"] = "Invalid Current Control Input.";
											$response["statusCode"] = 110;
						    					$response["signature"] = $server_signature;
										}
									}else {
										$controlId = -1;
										$response["result"] = "Error";
										$response["message"] = "DB Error. Invalid Current Control Input";
										$response["statusCode"] = 120;
						    				$response["signature"] = $server_signature;
									}
									
								}else {
									$controlId = -1;
									$response["result"] = "Error";
									$response["message"] = "Invalid Current Control Input";
									$response["statusCode"] = 100;
						    			$response["signature"] = $server_signature;
								}
							
							}else if ( $controlType == "L" ) {
								error_log("inside controlType = L for user_id = ".$userId);
								if ( strlen($newControl) >= 8 and strlen($newControl) <= 20 ) {
									$select_password_query = "select password from user where user_id = $userId";
									error_log("select_password_query = ".$select_password_query);
									$select_password_result = mysqli_query($con, $select_password_query);
									if ( $select_password_result ) {
										$select_password_count = mysqli_num_rows($select_password_result);
										if ( $select_password_count == 1 ) {
											error_log("inside select_password_count == 1");
											$select_password_row = mysqli_fetch_assoc($select_password_result);
											$hash_password = $select_password_row['password'];
											$successpassword = ckdecrypt($currentControl, $hash_password);
											if($successpassword) {
												error_log("inside successpassword = true");
												$new_hash_password = ckencrypt($newControl);
												$select_password_history_query = "select password_history_check($userId, '$newControl', '$new_hash_password', $admin_password_valid_day, '$controlType') as result";
												error_log("select_password_history_query = ".$select_password_history_query);
												$select_password_history_result = mysqli_query($con, $select_password_history_query);
												if ( $select_password_history_result ) {
													$select_password_history_row = mysqli_fetch_assoc($select_password_history_result);
													$password_status_result = $select_password_history_row['result'];
													if ( $password_status_result == 0 ) {
														$response["result"] = "Success";
														$response["message"] = "Control Update is successful";
														$response["statusCode"] = $password_status_result;
														$response["signature"] = $server_signature;
													}else {
														$response["result"] = "Error";
														$response["message"] = "Already used control input";
														$response["statusCode"] = $password_status_result;
														$response["signature"] = $server_signature;
													}
												}else {
													$response["result"] = "Error";
													$response["message"] = "DB Error in password history";
													$response["statusCode"] = 100;
													$response["signature"] = $server_signature;
												}
											}else {
												error_log("inside successpassword = false");
												$invalid_attempt = $invalid_attempt+1;
												if($invalid_attempt >= $admin_attempt_limit) {
													$admin_login_invalid_attempt_update = "UPDATE user SET invalid_attempt = COALESCE(invalid_attempt,0)+1 ,locked = 'Y', locked_time = now() where user_id =  ".$userId;
												}else {
													$admin_login_invalid_attempt_update = "UPDATE user SET invalid_attempt = COALESCE(invalid_attempt,0)+1 where user_id =  ".$userId;									
												}
												$admin_login_invalid_attempt_result = mysqli_query($con, $admin_login_invalid_attempt_update);
												if ( !$admin_login_invalid_attempt_result ) {
													error_log("Error in updating the user table for invalid_attempt = ".$invalid_attempt);
												}

												$controlId = -1;
												$response["result"] = "Error";
												$response["message"] = "Invalid Current Control Input..";
												$response["statusCode"] = 120;
												$response["signature"] = $server_signature;
											}
										}else {
											error_log("inside select_password_count != 1");
											$invalid_attempt = $invalid_attempt+1;
											if($invalid_attempt >= $admin_attempt_limit) {
												$admin_login_invalid_attempt_update = "UPDATE user SET invalid_attempt = COALESCE(invalid_attempt,0)+1 ,locked = 'Y', locked_time = now() where user_id =  ".$userId;
											}else {
												$admin_login_invalid_attempt_update = "UPDATE user SET invalid_attempt = COALESCE(invalid_attempt,0)+1 where user_id =  ".$userId;									
											}
											$admin_login_invalid_attempt_result = mysqli_query($con, $admin_login_invalid_attempt_update);
											if ( !$admin_login_invalid_attempt_result ) {
												error_log("Error in updating the user table for invalid_attempt = ".$invalid_attempt);
											}

											$controlId = -1;
											$response["result"] = "Error";
											$response["message"] = "Invalid Current Control Input.";
											$response["statusCode"] = 120;
											$response["signature"] = $server_signature;
										}
									}else {
										$controlId = -1;
										$response["result"] = "Error";
										$response["message"] = "DB Error. Invalid Current Control Input";
										$response["statusCode"] = 130;
										$response["signature"] = $server_signature;
									}

								}else {
									$controlId = -1;
									$response["result"] = "Error";
									$response["message"] = "Invalid Current Control Input";
									$response["statusCode"] = 100;
									$response["signature"] = $server_signature;
								}
							
							}else if ( $controlType == "P" ) {
								error_log("inside controlType = P for user_id = ".$userId);
								if ( (strlen($currentControl) == 6 ) && (strlen($newControl) == 6 ) ) {
									$select_user_pin_query = "select user_id from user where user_id = $userId and (pos_pin = '$currentControl' or pos_pin is null)";
									error_log("select_user_pin_query = ".$select_user_pin_query);
									$select_user_pin_result = mysqli_query($con, $select_user_pin_query);
									if ( $select_user_pin_result ) {
										$select_user_pin_count = mysqli_num_rows($select_user_pin_result);
										if ( $select_user_pin_count == 1 ) {
											$select_password_history_query = "select password_history_check($userId, '$newControl', null, $admin_password_valid_day, '$controlType') as result";
											error_log("select_password_history_query = ".$select_password_history_query);
											$select_password_history_result = mysqli_query($con, $select_password_history_query);
											if ( $select_password_history_result ) {
												$select_password_history_row = mysqli_fetch_assoc($select_password_history_result);
												$password_status_result = $select_password_history_row['result'];
												if ( $password_status_result == 0 ) {
													$response["result"] = "Success";
													$response["message"] = "Control Update ".$controlType." is successful";
													$response["statusCode"] = $password_status_result;
													$response["signature"] = $server_signature;
												}else {
													$response["result"] = "Error";
													$response["message"] = "Already used control input";
													$response["statusCode"] = $password_status_result;
													$response["signature"] = $server_signature;
												}
											}else {
												$response["result"] = "Error";
												$response["message"] = "DB Error in password history";
												$response["statusCode"] = 100;
												$response["signature"] = $server_signature;
											}
										}else {
											$invalid_attempt = $invalid_attempt+1;
											if($invalid_attempt >= $admin_attempt_limit) {
												$admin_login_invalid_attempt_update = "UPDATE user SET invalid_attempt = COALESCE(invalid_attempt,0)+1 ,locked = 'Y', locked_time = now() where user_id =  ".$userId;
											}else {
												$admin_login_invalid_attempt_update = "UPDATE user SET invalid_attempt = COALESCE(invalid_attempt,0)+1 where user_id =  ".$userId;									
											}
											$admin_login_invalid_attempt_result = mysqli_query($con, $admin_login_invalid_attempt_update);
											if ( !$admin_login_invalid_attempt_result ) {
												error_log("Error in updating the user table for invalid_attempt = ".$invalid_attempt);
											}

											$controlId = -1;
											$response["result"] = "Error";
											$response["message"] = "Invalid Current Control Input.";
											$response["statusCode"] = 110;
											$response["signature"] = $server_signature;
										}
									}else {
										$controlId = -1;
										$response["result"] = "Error";
										$response["message"] = "DB Error. Invalid Current Control Input";
										$response["statusCode"] = 120;
										$response["signature"] = $server_signature;
									}

								}else {
									$controlId = -1;
									$response["result"] = "Error";
									$response["message"] = "Invalid Current Control Input";
									$response["statusCode"] = 130;
									$response["signature"] = $server_signature;
								}
							}else {
								$controlId = -1;
								$response["result"] = "Error";
								$response["message"] = "Invalid Control Type";
								$response["statusCode"] = 140;
								$response["signature"] = $server_signature;
							}
						}catch(Exception $e1) {
							error_log("Exception in validateKey1: ".$e1->getMessage());
							$response["statusCode"] = "650";
							$response["result"] = "Error";
							$response["message"] = "Invalid Client Request..";
							$response["signature"] = $server_signature;
						}
					}
					else {
						// Invalid Session Key
						$response["statusCode"] = "640";
						$response["result"] = "Error";
						$response["message"] = "Invalid Client Request.";
						$response["signature"] = $server_signature;
					}
                    		}else {
					// Invalid Singature
					$response["statusCode"] = "300";
					$response["result"] = "Error";
                    			$response["message"] = "Failure: Invalid request";
                    			$response["signature"] = $server_signature;
				}
			}else {
				// Invalid Data
				$response["statusCode"] = "400";
				$response["result"] = "Error";
                		$response["message"] = "Failure: Invalid Data";
                		$response["signature"] = 0;
			}
        	}else {
			// Invalid Operation
			$response["statusCode"] = "500";
			$response["result"] = "Error";
            		$response["message"] = "Failure: Invalid Operation";
            		$response["signature"] = 0;
		}
	}else {
		// Invalid Request Method
		$response["result"] = "success";
		$response["status"] = "600";
        	$response["message"] = "Post Failure";
        	$response["signature"] = 0;
	}
    	error_log("link_account ==> ".json_encode($response));
	echo json_encode($response);
?>