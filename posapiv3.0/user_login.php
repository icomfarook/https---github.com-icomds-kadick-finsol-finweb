<?php
	include('../common/admin/configmysql.php');
	include("../common/admin/finsol_crypt.php");
	include("../common/admin/finsol_otp_ini.php");
	include ("get_prime.php");	
	include ("functions.php");	
	require_once ("AesCipher.php");
	require_once('../common/otp/otphp.php');
	//error_log("inside user_login.php");

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		//error_log("inside post request method");
		error_log(file_get_contents("php://input"));
		$data = json_decode(file_get_contents("php://input"));
		error_log(json_encode($data));
		if(isset($data -> operation) ) {
			//error_log("inside isset data->operation method");
			$operation = $data -> operation;
			if(!empty($operation)) {
				//error_log("inside not empty operator method");
				
				if($operation == 'LOGIN'){
					error_log("inside operation == userLogin method");
					
					if ( isset($data -> username) && !empty($data -> username) 
						&& isset($data -> signature) && !empty($data -> signature) 
						&& isset($data -> key1) && !empty($data -> key1) 
						&& isset($data -> imei) && !empty($data -> imei) 
						&& isset($data -> key2) && !empty($data -> key2) ) {
						error_log("inside all inputs are set correctly");
						
						// array for JSON response
						$response = array();
						$otp_len = OTP_USER_DIGITS;
						$admin_attempt_limit = ADMIN_ATTEMPT_LIMIT;
						$session_validity = AGENT_SESSION_VALID_TIME;
						$username = $data -> username;
						$signature = $data -> signature;
						$key1 = stripcslashes ($data -> key1);
						$key2 = stripcslashes ($data -> key2);
						error_log("before setting timezone");
						error_log("data->key1 = ".$data -> key1.", data->key2 = ".$data -> key2);
						error_log("key1 = ".$key1.", key2 = ".$key2);
						date_default_timezone_set('Africa/Lagos');
						$nday = date('z')+1;
						$nyear = date('Y');
						error_log( "nday = ".$nday);
						error_log( "nyear = ".$nyear);
						$nth_day_prime = get_prime($nday);
						$nth_year_day_prime = get_prime($nday+$nyear);
						error_log("nth_day_prime =".$nth_day_prime);
						error_log("nth_year_day_prime = ".$nth_year_day_prime);
						$local_signature = $nday + $nth_day_prime;
						$server_signature = $nth_year_day_prime + $nday + $nyear;
						error_log("local_signature = ".$local_signature);
						error_log("server_signature = ".$server_signature);
						if ( $local_signature != $signature ) {
							$response["statusCode"] = 650;
							$response["result"] = "Error";
							$response["message"] = "Invalid Client Request..";
							error_log(json_encode($response));
							echo json_encode($response);
            						return;
						}
						error_log("signature is validated");
						$skey0 = date("mdY");
						$skey1 = $nday;
						$skey2 = $local_signature;
						
						$skeya = str_pad($skey0.$skey1.$skey2, 16, '0', STR_PAD_LEFT);
						$skeyb = str_pad($skey2.$skey1.$skey0, 16, '0', STR_PAD_LEFT);
						error_log("skeya = ".$skeya.", skeyb = ".$skeyb);
						error_log("before calling Security::decrypt");
						$key1_result = AesCipher::decrypt($skeya, $key1);
						error_log("after calling Security::decrypt");
						error_log("key1_result = ".$key1_result);
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
						$password = $key1_array[0];
						$uname = $key1_array[1];
						$token = $key1_array[2];
						$ltime = $key1_array[3];
						error_log ("uname = ".$uname.", passwd = ".$password.", token = ".$token);
						
						$key2_result = AesCipher::decrypt($skeyb, $key2);
						error_log("key2_result = ".$key2_result);
						$tilda_found = strpos($key2_result, '~');
						if ( $tilda_found == false ) {
							$response["statusCode"] = 670;
							$response["result"] = "Error";
							$response["message"] = "Invalid Client Request....";
							error_log(json_encode($response));
							echo json_encode($response);
							return;
						}
						
						$key2_array = explode("~", $key2_result);
						$device_sno = $key2_array[0];
						$app_version = $key2_array[1];
						$device_location = $key2_array[2];
						$device_api = $key2_array[3];
						error_log("device_sno = ".$device_sno.", app_version = ".$app_version.", device_location = ".$device_location.", device_api = ".$device_api);

						$invalid_version = "Y";
						if ( $invalid_version == "Y" ) {
							$response["statusCode"] = 675;
							$response["result"] = "Error";
							$response["message"] = "Old App version. Please upgrade your Kadickmoni App..";
							error_log(json_encode($response));
							echo json_encode($response);
							return;
						}
						
						$user_check_query = "SELECT temp_password, first_time_login, user_id, user_name, password, active, profile_id, country_id, COALESCE(invalid_attempt,0) as invalid_attempt, locked, access_restrict, pos_access, use_otp FROM user WHERE UPPER(user_name) = UPPER(?) and profile_id in (50, 51, 52) and loginable = 'Y' limit 1";
						error_log("query1 = ".$user_check_query);
						$stmt = $con->prepare($user_check_query);
						$stmt->bind_param("s", $uname);
						$stmt->execute();
						$stmt->store_result();
						$num_of_rows = $stmt->num_rows;
						error_log("num_of_rows = ".$num_of_rows);
						if ( $num_of_rows <= 0 ) {
							$response["statusCode"] = 500;
							$response["result"] = "Error";
							$response["message"] = "Invalid Username/Password/Token.";
							error_log(json_encode($response));
							echo json_encode($response);
							return;
						}
							error_log("inside 1st num_of_rows > 0");
							$stmt->bind_result($temp_password, $first_time_login, $user_id, $uname, $hash_password1, $active, $profile_id, $country_id, $invalid_attempt, $locked, $access_restrict, $pos_access, $use_otp);
							$stmt->fetch();
							$stmt->free_result();
							$stmt->close();
							error_log("hash_password1 = ".$hash_password1);
							
							if ( $active == 'Y' ) {
								if ( $locked != "Y" && $invalid_attempt <= $admin_attempt_limit ) {
									if($first_time_login == "N") {
										$successpassword = ckdecrypt($password, $hash_password1);						
									}
									else {
										$successpassword = ckdecrypt($password, $temp_password);
									}
									if($successpassword) {
										error_log("inside successpassword = true");
										if($pos_access == "Y" || $pos_access == "B" )	{
											$user_otp_query = "SELECT b.otp_dynamic, b.otp_value, b.key_string, b.pin, b.pin_flag FROM user a, user_otp b WHERE a.user_id = b.user_id and a.user_id = ".$user_id." and a.active = 'Y' limit 1";
											$user_otp_result = mysqli_query($con, $user_otp_query);
											if($user_otp_result) {
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
															if ( $profile_id == 50 ) {
																$user_sec_check_query = "SELECT a.user_id, a.user_type as party_type, a.user_name, a.first_name, a.last_name, a.last_login, a.invalid_attempt, a.email, a.profile_id, b.profile_name, c.champion_code as party_code, c.champion_name as party_name, c.local_govt_id, c.state_id, c.party_category_type_id, b.auth_id, c.language_id, d.available_balance from user a, profile b, champion_info c, champion_wallet d WHERE a.profile_id = b.profile_id and a.user_id = ".$user_id." and c.agent_code = d.agent_code and (c.block_status is null or c.block_status = 'Y') and c.user_id = a.user_id and a.profile_id = 50 and (c.start_date is null or (c.start_date is not null and date(c.start_date) <= current_date())) and (c.expiry_date is null or (c.expiry_date is not null and date(c.expiry_date) > current_date())) limit 1";
															}else {
																$user_sec_check_query = "SELECT a.user_id, a.user_type as party_type, a.user_name, a.first_name, a.last_name, a.last_login, a.invalid_attempt, a.email, a.profile_id, b.profile_name, c.agent_code as party_code, c.agent_name as party_name, c.parent_code, c.local_govt_id, c.country_id, c.state_id, c.sub_agent, c.party_category_type_id, b.auth_id, c.language_id, d.available_balance from user a, profile b, agent_info c, agent_wallet d WHERE a.profile_id = b.profile_id and a.user_id = ".$user_id." and c.agent_code = d.agent_code and (c.block_status is null or c.block_status = 'Y') and c.user_id = a.user_id and a.profile_id in (51, 52) and (c.start_date is null or (c.start_date is not null and date(c.start_date) <= current_date())) and (c.expiry_date is null or (c.expiry_date is not null and date(c.expiry_date) > current_date())) limit 1";
															}
															$user_sec_check_result = mysqli_query($con,$user_sec_check_query);						
															if ( $user_sec_check_result ) {
																$user_sec_check_count = mysqli_num_rows($user_sec_check_result);
																if($user_sec_check_count > 0) {														
																	$row = mysqli_fetch_array($user_sec_check_result);
																	error_log("access_restrict = ".$access_restrict);
																	$login_allow = "N";
																	if($access_restrict == "Y") {
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
																							} else {
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
																		$party_code = $row['party_code'];
																		$profile_id = $row['profile_id'];
																		$party_type = $row['party_type'];
																		$language_id = $row['language_id'];
																		$user_access_control = new \stdClass();
																		$user_access_query = "select s.feature_code from user_pos_menu u, service_feature s where s.service_feature_id = u.service_feature_id and s.active = 'Y' and u.active = 'Y' and (u.start_date is null or (u.start_date is not null and date(u.start_date) <= current_date())) and (u.expiry_date is null or (u.expiry_date is not null and date(u.expiry_date) > current_date())) and u.user_id = ".$user_id;
																		error_log("user_access_query = ".$user_access_query);
																		$user_access_result = mysqli_query($con, $user_access_query);
																		if ( $user_access_result ) {
																			error_log("inside user_access_result");
																			while($user_access_row = mysqli_fetch_assoc($user_access_result)) {
																				if ( "CIN" == $user_access_row['feature_code'] ) {
																					$user_access_control->accoundBaseCashin = "Y";
																				} else if ( "COU" == $user_access_row['feature_code'] ) {
																					$user_access_control->accountBaseCashout = "Y";
																				} else if ( "CTR" == $user_access_row['feature_code'] ) {
																					$user_access_control->accountBaseTransfer = "Y";
																				}  else if ( "MP0" == $user_access_row['feature_code'] ) {
																					$user_access_control->cardBaseCashout = "Y";
																				} else if ( "MP1" == $user_access_row['feature_code'] ) {
																					$user_access_control->cardBaseCashback = "Y";
																				} else if ( "MP2" == $user_access_row['feature_code'] ) {
																					$user_access_control->cardBaseCashAdvance = "Y";
																				} else if ( "MP3" == $user_access_row['feature_code'] ) {
																					$user_access_control->cardBaseEndofSale = "Y";
																				} else if ( "MP4" == $user_access_row['feature_code'] ) {
																					$user_access_control->cardBaseEndoDay = "Y";
																				} else if ( "MP5" == $user_access_row['feature_code'] ) {
																					$user_access_control->cardBaseReversal = "Y";
																				} else if ( "MP6" == $user_access_row['feature_code'] ) {
																					$user_access_control->cardBaseRefund = "Y";
																				} else if ( "MP7" == $user_access_row['feature_code'] ) {
																					$user_access_control->cardBasePreAuthorization = "Y";
																				} else if ( "MP8" == $user_access_row['feature_code'] ) {
																					$user_access_control->cardBaseBalanceEnquiry = "Y";
																				} else if ( "MP9" == $user_access_row['feature_code'] ) {
																					$user_access_control->cardBaseTransfer = "Y";
																				}
																			}
																		}
																		$last_login_date = $row['last_login'];																
																		$profile_name = $row['profile_name'];
																		$username = $row['user_name'];
																		$first_name = $row['first_name'];
																		$last_name = $row['last_name'];
																		$email = $row['email'];
																		$user_id = $row['user_id'];															
																		$party_name = $row['party_name'];
																		$parent_code = $row['parent_code'];
																		$local_govt_id = $row['local_govt_id'];
																		$state_id = $row['state_id'];
																		$sub_agent = $row['sub_agent'];
																		$country_id = $row['country_id'];
																		$party_category_type_id = $row['party_category_type_id'];
																		$invalid_attempt = $row['invalid_attempt'];
																		$auth_id = $row['auth_id'];
																		$available_balance = $row['available_balance'];
																					
																		//Get POS Configuration
																		$user_pos_config_query = "select a.terminal_id, a.nibss_key1, a.nibss_key2, a.nibss_server_ip, a.nibss_server_port, a.app_timeout, a.imei, a.status, ifnull(a.debug_flag, 'N') as debug_flag, ifnull(a.mpos_simulate, 'N') as mpos_simulate, ifnull(a.control_field1, 'N') as control_field1, ifnull(a.control_field2, 'N') as control_field2, ifnull(a.control_field3, 'N') as control_field3, ifnull(a.control_field4, 'N') as control_field4, ifnull(a.control_field5, 'N') as control_field5, ifnull(a.control_field6, 'N') as control_field6, ifnull(a.pos_pin, '') as pos_pin, ifnull(a.pay_min_limit, 0) as pay_min_limit, ifnull(a.pay_max_limit, 1000000) as pay_max_limit, ifnull(a.cashin_min_limit, 0) as cashin_min_limit, ifnull(a.cashin_max_limit, 500000) as cashin_max_limit, ifnull(a.cashout_min_limit, 0) as cashout_min_limit, ifnull(a.cashout_max_limit, 500000) as cashout_max_limit, ifnull(a.recharge_min_limit, 0) as recharge_min_limit, ifnull(a.recharge_max_limit, 10000) as recharge_max_limit, ifnull(a.flexi_rate,'N') as flexi_rate, c.terminal_model from user_pos a, terminal_inventory b, terminal_vendor c where a.terminal_id = b.terminal_id and b.vendor_id = c.terminal_vendor_id and a.user_id = ".$user_id;
																		error_log("user_pos_config_query = ".$user_pos_config_query);
																		$user_pos_config_result = mysqli_query($con, $user_pos_config_query);
																		$user_pos_config_ready = "N";
																		if ( $user_pos_config_result ) {
																			$user_pos_config_count = mysqli_num_rows($user_pos_config_result);
																			if ( $user_pos_config_count > 0 ) {
																				$row3 = mysqli_fetch_assoc($user_pos_config_result);
																				$terminal_id = $row3['terminal_id'];
																				$nibss_key1 = $row3['nibss_key1'];
																				$nibss_key2 = $row3['nibss_key2'];
																				$nibss_server_ip = $row3['nibss_server_ip'];
																				$nibss_server_port = $row3['nibss_server_port'];
																				$app_timeout = $row3['app_timeout'];
																				//$session_key = $row3['session_key'];
																				$db_imei = $row3['imei'];
																				$pos_status = $row3['status'];
																				$pos_debug = $row3['debug_flag'];
																				$mpos_debug = $row3['mpos_simulate'];
																				$pos_pin = $row3['pos_pin'];
																				$pay_min_limit = $row3['pay_min_limit'];
																				$pay_max_limit = $row3['pay_max_limit'];
																				$cashin_min_limit = $row3['cashin_min_limit'];
																				$cashin_max_limit = $row3['cashin_max_limit'];
																				$cashout_min_limit = $row3['cashout_min_limit'];
																				$cashout_max_limit = $row3['cashout_max_limit'];
																				$recharge_min_limit = $row3['recharge_min_limit'];
																				$recharge_max_limit = $row3['recharge_max_limit'];
																				$account_base_access = $row3['control_field1'];
																				$flexi_rate = $row3['flexi_rate'];
																				$terminal_model = $row3['terminal_model'];
																				if ( "Y" == $account_base_access ) {
																					$user_access_control->accountBaseAccess = "Y";
																				}
																				$card_base_access = $row3['control_field2'];
																				if ( "Y" == $card_base_access ) {
																					$user_access_control->cardBaseAccess = "Y";
																				}
																				$recharge_access = $row3['control_field3'];
																				if ( "Y" == $recharge_access ) {
																					$user_access_control->rechargeAccess = "Y";
																				}
																				$bill_payment_access = $row3['control_field4'];
																				if ( "Y" == $bill_payment_access ) {
																					$user_access_control->billPaymentAccess = "Y";
																				}
																				$bank_service_access = $row3['control_field5'];
																				if ( "Y" == $bank_service_access ) {
																					$user_access_control->bankServiceAccess = "Y";
																				}
																				$group_service_access = $row3['control_field6'];
																				if ( "Y" == $group_service_access ) {
																					$user_access_control->groupServiecAccess = "Y";
																				}
																				$user_pos_config_ready = "Y";
																			}
																		}
																		if ( $user_pos_config_ready == "Y") {
																			$session_key = bin2hex(openssl_random_pseudo_bytes(8));
																			if ( $pos_status == "B") {
																				error_log("inside pos_status == B");
																				error_log("device_sno = ".$device_sno.", db_imei = ".$db_imei);
																				if ( $device_sno == $db_imei ) {
																					error_log("inside device_sno = db_imei");
																					$user_login_update = "UPDATE user SET last_login = now(), invalid_attempt = 0 WHERE user_name = '$uname' and user_id = ".$user_id;
																					error_log("user_login_update = ".$user_login_update);
																					$user_login_update_result = mysqli_query($con, $user_login_update);
																					if (!$user_login_update_result ) {
																						error_log("Error in user_login_update");
																					}

																					$user_session_update = "UPDATE user_pos set session_key = '".$session_key."', session_key_create_time = now(), session_key_valid_time = ADDTIME(now(), '".$session_validity."') where user_id = ".$user_id;
																					error_log("user_session_update = ".$user_session_update);
																					$user_session_result = mysqli_query($con, $user_session_update);
																					if (!$user_session_result ) {
																						error_log("Error in user_session_result");
																					}
																					recordUserPosActivity($user_id, $device_sno, 'L', $device_api."~".$device_location, $con);
																					$bank_master_query = "select bank_master_id as id, name, cbn_short_code as code from bank_master where country_id = ".ADMIN_COUNTRY_ID." and active = 'Y' order by bank_master_id";
																					error_log("bank_master_query = ".$bank_master_query);
																					$bank_master_result = mysqli_query($con, $bank_master_query);
																					$response["bankList"] = array();
																					if ( $bank_master_result ) {
																						while($bank_master_row = mysqli_fetch_assoc($bank_master_result)) {
																							$bank = array();
																							$bank['id'] = $bank_master_row['id'];
																							$bank['name'] = $bank_master_row['name'];
																							$bank['code'] = $bank_master_row['code'];
																							array_push($response["bankList"], $bank);
																						}
																					}
																					$response["userAccessControl"] = $user_access_control;
																					$response["statusCode"] = 0;
																					$response["result"] = "Success";
																					$response["message"] = "Login validated successfully";
																					$response["signature"] = $server_signature;
																					$response["debug"] = $pos_debug;
																					$response["mPosSimulate"] = $mpos_debug;
																					$userLogin = new \stdClass();
																					$userLogin->userId = $user_id;
																					$userLogin->userName = $username;
																					$userLogin->firstName = $first_name;
																					$userLogin->lastName = $last_name;
																					$userLogin->lastLogin = $last_login_date;
																					$userLogin->partyCode = $party_code;
																					$userLogin->partyType = $party_type;
																					$userLogin->partyName = $party_name;
																					$userLogin->profileId = $profile_id;
																					$userLogin->profileName = $profile_name;
																					$userLogin->email = $email;
																					$userLogin->parentCode = $parent_code;
																					$userLogin->localGovtId = $local_govt_id;
																					$userLogin->subAgent = $sub_agent;
																					$userLogin->countryId = $country_id;
																					$userLogin->stateId = $state_id;
																					$userLogin->partyCategoryTypeId = $party_category_type_id;
																					$userLogin->authId = $auth_id;
																					$userLogin->invalidAttempt = $invalid_attempt;
																					$userLogin->languageId = $language_id;
																					$userLogin->availableBalance = $available_balance;
																					$nibss_key = bin2hex(pack('H*',$nibss_key1) ^ pack('H*',$nibss_key2));
																					error_log("nibss_key = ".$nibss_key." for user_id = ".$user_id);
																					$userLogin->nibssPosServerName = $nibss_server_ip;
																					$userLogin->nibssPosServerPort = $nibss_server_port;
																					$userLogin->nibssPosCTMK = $nibss_key;
																					$userLogin->nibssServerTimeout = $app_timeout;
																					$userLogin->nibssTerminalId = $terminal_id;
																					$userLogin->sessionKey = $session_key;
																					$userLogin->posPin = $pos_pin;
																					$userLogin->payMinLimit = $pay_min_limit;
																					$userLogin->payMaxLimit = $pay_max_limit;
																					$userLogin->cashinMinLimit = $cashin_min_limit;
																					$userLogin->cashinMaxLimit = $cashin_max_limit;
																					$userLogin->cashoutMinLimit = $cashout_min_limit;
																					$userLogin->cashoutMaxLimit = $cashout_max_limit;
																					$userLogin->rechargeMinLimit = $recharge_min_limit;
																					$userLogin->rechargeMaxLimit = $recharge_max_limit;
																					$userLogin->flexiRate = $flexi_rate;
																					$userLogin->posType = $terminal_model;
																					
																					$response["user"] = $userLogin;
																					error_log(json_encode($response));
																				}else {
																					error_log("inside device_sno != db_imei");
																					error_log("@@@@username = ".$uname.": This device is not attached to login user");
																					$msg = "This device is not attched to login user. Are you using different device?";
																					$response["statusCode"] = 120;
																					$response["result"] = "Error";
																					$response["message"] = $msg;
																				}
																			} else if ($pos_status == 'U') {
																				error_log("Agent Login = ".$uname." is unbound and hence binding it to imei = ".$device_sno);
																				error_log("inside device_sno = db_imei");
																				$user_pos_update_query = "UPDATE user_pos SET imei = '".$device_sno."', status = 'B', update_time = now(), update_user = $user_id, session_key = '".$session_key."', session_key_create_time = now(), session_key_valid_time = ADDTIME(now(), '".$session_validity."') where user_id = ".$user_id;
																				error_log("user_pos_update_query = ".$user_pos_update_query);
																				$user_pos_update_result = mysqli_query($con, $user_pos_update_query);
																				if ( !$user_pos_update_result ) {
																					error_log("Error in binding User Login ".$uname." to device[imei] = ".$device_sno);
																				}else {
																					error_log("Success in binding User Login ".$uname." to device[imei] = ".$device_sno);
																				}

																				$user_login_update = "UPDATE user SET last_login = now(), invalid_attempt = 0 WHERE user_name = '$uname' and user_id = ".$user_id;
																				$user_login_update_result = mysqli_query($con,$user_login_update);
																				
																				recordUserPosActivity($user_id, $device_sno, 'L', $device_location, $con);
																				$bank_master_query = "select bank_master_id as id, name, cbn_short_code as code from bank_master where country_id = ".ADMIN_COUNTRY_ID." and active = 'Y' order by bank_master_id";
																				error_log("bank_master_query = ".$bank_master_query);
																				$bank_master_result = mysqli_query($con, $bank_master_query);
																				$response["bankList"] = array();
																				if ( $bank_master_result ) {
																					while($bank_master_row = mysqli_fetch_assoc($bank_master_result)) {
																						$bank = array();
																						$bank['id'] = $bank_master_row['id'];
																						$bank['name'] = $bank_master_row['name'];
																						$bank['code'] = $bank_master_row['code'];
																						array_push($response["bankList"], $bank);
																					}
																				}
																				$response["userAccessControl"] = $user_access_control;
																				$response["statusCode"] = 0;
																				$response["result"] = "Success";
																				$response["message"] = "Login validated successfully";
																				$response["signature"] = $server_signature;
																				$response["debug"] = $pos_debug;
																				$response["mPosSimulate"] = $mpos_debug;
																				$userLogin = new \stdClass();
																				$userLogin->userId = $user_id;
																				$userLogin->userName = $username;
																				$userLogin->firstName = $first_name;
																				$userLogin->lastName = $last_name;
																				$userLogin->lastLogin = $last_login_date;
																				$userLogin->partyCode = $party_code;
																				$userLogin->partyType = $party_type;
																				$userLogin->partyName = $party_name;
																				$userLogin->profileId = $profile_id;
																				$userLogin->profileName = $profile_name;
																				$userLogin->email = $email;
																				$userLogin->parentCode = $parent_code;
																				$userLogin->localGovtId = $local_govt_id;
																				$userLogin->subAgent = $sub_agent;
																				$userLogin->countryId = $country_id;
																				$userLogin->stateId = $state_id;
																				$userLogin->partyCategoryTypeId = $party_category_type_id;
																				$userLogin->authId = $auth_id;
																				$userLogin->invalidAttempt = $invalid_attempt;
																				$userLogin->languageId = $language_id;
																				$userLogin->availableBalance = $available_balance;
																				$nibss_key = bin2hex(pack('H*',$nibss_key1) ^ pack('H*',$nibss_key2));
																				error_log("nibss_key = ".$nibss_key." for user_id = ".$user_id);
																				$userLogin->nibssPosServerName = $nibss_server_ip;
																				$userLogin->nibssPosServerPort = $nibss_server_port;
																				$userLogin->nibssPosCTMK = $nibss_key;
																				$userLogin->nibssServerTimeout = $app_timeout;
																				$userLogin->nibssTerminalId = $terminal_id;
																				$userLogin->sessionKey = $session_key;
																				$userLogin->posPin = $pos_pin;
																				$userLogin->payMinLimit = $pay_min_limit;
																				$userLogin->payMaxLimit = $pay_max_limit;
																				$userLogin->cashinMinLimit = $cashin_min_limit;
																				$userLogin->cashinMaxLimit = $cashin_max_limit;
																				$userLogin->cashoutMinLimit = $cashout_min_limit;
																				$userLogin->cashoutMaxLimit = $cashout_max_limit;
																				$userLogin->rechargeMinLimit = $recharge_min_limit;
																				$userLogin->rechargeMaxLimit = $recharge_max_limit;
																				$userLogin->flexiRate = $flexi_rate;
																				$userLogin->posType = $terminal_model;
																				
																				$response["user"] = $userLogin;
																				error_log(json_encode($response));
																			} else if ($pos_status == "X") {
																				error_log("inside user_pos.status = X");
																				error_log("@@@@username = ".$uname.": User Pos Status is terminate X status");
																				$msg = "Your device [imei] is blocked. Contact Kadick Admin?";
																				$response["statusCode"] = 140;
																				$response["result"] = "Error";
																				$response["message"] = $msg;
																			} else {
																				error_log("inside user_pos.status is unknown");
																				error_log("@@@@username = ".$uname.": User Pos Status is Unknown");
																				$msg = "Your device [imei] status is Unknown. Contact Kadick Admin?";
																				$response["statusCode"] = 150;
																				$response["result"] = "Error";
																				$response["message"] = $msg;
																			}
																		}else {
																			error_log("@@@@username = ".$uname.": Failure in Session Info retrieve");
																			$msg = "Failure in Session Info retrieve";
																			$response["statusCode"] = 160;
																			$response["result"] = "Error";
																			$response["message"] = $msg;
																		}
																	}else {
																		error_log("@@@@username = ".$uname.": access restiction is effective for weekends");
																		$msg = "Access Restriction for weekend";
																		$response["statusCode"] = 170;
																		$response["result"] = "Error";
																		$response["message"] = $msg;
																	}
																} else {
																	$response["statusCode"] = 180;
																	$response["result"] = "Error";
																	$response["message"] = "User Pos account is not setup. Contact Kadick admin";
																}
															}else {
																$response["statusCode"] = 170;
																$response["result"] = "Error";
																$response["message"] = "User Pos account is not setup. Contact Kadick admin";
															}
														}else {
															//First Time Login
														}
													}else {
														$invalid_attempt = $invalid_attempt+1;
														if($invalid_attempt >= $admin_attempt_limit) {
															$admin_login_invalid_attempt_update = "UPDATE user SET invalid_attempt = COALESCE(invalid_attempt,0)+1 ,locked = 'Y', locked_time = now() where user_id =  ".$user_id;
														}else {
															$admin_login_invalid_attempt_update = "UPDATE user SET invalid_attempt = COALESCE(invalid_attempt,0)+1 where user_id =  ".$user_id;									
														}
														$response["statusCode"] = 190;
														$response["result"] = "Error";
														$response["message"] = "Invalid Username/Password/Token";
													}
												}else {
													$invalid_attempt = $invalid_attempt+1;
													if($invalid_attempt >= $admin_attempt_limit) {
														$admin_login_invalid_attempt_update = "UPDATE user SET invalid_attempt = COALESCE(invalid_attempt,0)+1 ,locked = 'Y', locked_time = now() where user_id =  ".$user_id;
													}else {
														$admin_login_invalid_attempt_update = "UPDATE user SET invalid_attempt = COALESCE(invalid_attempt,0)+1 where user_id =  ".$user_id;									
													}
													$response["statusCode"] = 180;
													$response["result"] = "Error";
													$response["message"] = "Invalid Username/Password/Token";
												}
											}else {
												$response["statusCode"] = 200;
												$response["result"] = "Error";
												$response["message"] = "User is not authorized to use App";
											}
										}else {
											$response["statusCode"] = 210;
											$response["result"] = "Error";
											$response["message"] = "User is not authorized to use App.";
										}
									}else {
										$invalid_attempt = $invalid_attempt+1;
										if($invalid_attempt >= $admin_attempt_limit) {
											$admin_login_invalid_attempt_update = "UPDATE user SET invalid_attempt = COALESCE(invalid_attempt,0)+1 ,locked = 'Y', locked_time = now() where user_id =  ".$user_id;
										}else {
											$admin_login_invalid_attempt_update = "UPDATE user SET invalid_attempt = COALESCE(invalid_attempt,0)+1 where user_id =  ".$user_id;									
										}
										$response["statusCode"] = 300;
										$response["result"] = "Error";
										$response["message"] = "Invalid Username/Password/Token";
									}
								}else {
									$response["statusCode"] = 400;
									$response["result"] = "Error";
									$response["message"] = "User is locked. Contact Kadick Admin";
								}
							}else{
								$response["statusCode"] = 410;
								$response["result"] = "Error";
								$response["message"] = "User is not active. Contact Kadick Admin";
							}
						
					}else {
						$response["statusCode"] = 600;
						$response["result"] = "Error";
						$response["message"] = "Invalid Request";
					}
				}else {
					$response["statusCode"] = 610;
					$response["result"] = "Error";
					$response["message"] = "Invalid Operation";
				}
			}else {
				$response["statusCode"] = 620;
				$response["result"] = "Error";
				$response["message"] = "Invalid Operation.";
			}
		}else {
			$response["statusCode"] = 630;
			$response["result"] = "Error";
			$response["message"] = "Invalid Operation..";
		}
	}else {
		$response["statusCode"] = 640;
		$response["result"] = "Error";
		$response["message"] = "Invalid Request";
	}
	error_log(json_encode($response));				
	echo json_encode($response);
?>