<?php
	include('../common/admin/configmysql.php');
	include("../common/admin/finsol_crypt.php");
	include ("get_prime.php");	
	require_once ("AesCipher.php");
    	require_once("db_connect.php");
    	include ("functions.php");
	error_log("inside pcposapi/card_link.php");
	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		error_log("inside post request method");
        	$data = json_decode(file_get_contents("php://input"));
        	error_log("card_link <== ".json_encode($data));

        	if(isset($data -> operation) && $data -> operation == 'CARD_LINK_TRIGGER_OTP') {
			error_log("inside operation == CARD_LINK_TRIGGER_OTP method");
			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
			  	&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
			   	&& isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
			   	&& isset($data->stateId) && !empty($data->stateId) && isset($data->partnerId) && !empty($data->partnerId) 
			   	&& isset($data->cardLink->bank) && !empty($data->cardLink->bank) && isset($data->cardLink->customerName) && !empty($data->cardLink->customerName)
			   	&& isset($data->cardLink->mobile) && !empty($data->cardLink->mobile) && isset($data->cardLink->accountNo) && !empty($data->cardLink->accountNo)
			   	&& isset($data->sanefAgentCode) && !empty($data->sanefAgentCode) && isset($data->cardLink->agentCharge) && !empty($data->cardLink->agentCharge)
			   	&& isset($data->flexiRate) && !empty($data->flexiRate) && isset($data->location) && !empty($data->location)
			){
				ini_set('max_execution_time', 120);
				set_time_limit(120);
				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$parentCode = $data->parentCode;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$bank = $data->cardLink->bank;
				$accBankCode = $data->cardLink->bank->code;
				$accBankId = $data->cardLink->bank->id;
				$customerName = $data->cardLink->customerName;
				$mobile = $data->cardLink->mobile;
				$accountNo = $data->cardLink->accountNo;
				$sanefAgentCode = $data->sanefAgentCode;
				$agentCharge = $data->cardLink->agentCharge;
				$amsCharge = $data->cardLink->amsCharge;
				$stampCharge = $data->cardLink->stampCharge;
				$otherCharge = $data->cardLink->otherCharge;
				$parnterCharge = $data->cardLink->parnterCharge;
				$partnerId = $data->partnerId;
				$location = $data->location;
				$requestAmount = 0;
				$flexiRate = $data->flexiRate;
				$serviceFeatureCode = "BCL";
				$session_validity = AGENT_SESSION_VALID_TIME;
				$localGovtId = ADMIN_LOCAL_GOVT_ID;
				$productId = 26;
				
				if ($parentCode == "") {
					$partyCount = 2;
				}else {
					$partyCount = 3;
				}

				$db_flexiRate = "N";
				$flexi_rate_query = "select state_flexi_rate_id from state_flexi_rate where state_id = $stateId and (service_feature_id is null or (service_feature_id = $productId)) and active = 'Y' and (start_date is null or (start_date is not null and date(start_date) <= current_date())) and (expiry_date is null or (expiry_date is not null and date(expiry_date) >= current_date())) order by state_flexi_rate_id limit 1";
				error_log("flexi_rate_query query = ".$flexi_rate_query);
				$flexi_rate_result = mysqli_query($con, $flexi_rate_query);
				if ($flexi_rate_result) {
					$flexi_rate_count = mysqli_num_rows($flexi_rate_result);
					if($flexi_rate_count > 0) {					
						$db_flexiRate = "Y";
					}
				}
				error_log("db_flexiRate = ".$db_flexiRate.", flexiRate = ".$flexiRate);										
				if ( "Y" == $flexiRate || "Y" == $db_flexiRate ) {
					$txtType = "F";
				}else {
					$txtType = "E";
				}
				error_log("txtType = ".$txtType.", partyCount = ".$partyCount);
				
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
					$validate_result = validateKey1($key1, $userId, $session_validity, 'Z', $con);
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
									
					error_log("before calling checking_feature_value: txtType = ".$txtType);
					$checking_feature_value_response = checking_feature_value($userId, $countryId, $stateId, $partyCount, $productId, $partnerId, $requestAmount, $txtType, $con);
					$checking_feature_value_response_split = explode("#",$checking_feature_value_response);
					$charges_details = $checking_feature_value_response_split[0];	
					$rateparties_details = $checking_feature_value_response_split[1];										
					$charges_details_split = explode("|",$charges_details);	
					$reponse_feature_value = $charges_details_split[0];	
					$service_feature_config_id = $charges_details_split[1];
					$ams_charge = $charges_details_split[2];
					$partner_charge = $charges_details_split[3];									
					$other_charge = $charges_details_split[4];
					
					$request_total_amount = floatval($ams_charge) +  floatval($partner_charge) + floatval($other_charge) + floatval($requestAmount);
					
					$daily_check_result = checkDailyLimit($userId, $request_total_amount, $con);
					error_log("daily_check_result = ".$daily_check_result);
					if ( $daily_check_result != 0 ){
						// Exceeded Daily Limit
						$response["statusCode"] = "998";
						$response["result"] = "Error";
						$response["message"] = "Failure: Exceeded Daily Limit";
						$response["signature"] = 0;
						error_log(json_encode($response));
						echo json_encode($response);
						return;
					}

					$agent_info_wallet_status = check_agent_info_wallet_status($partyType, $partyCode, $con);
					if ($agent_info_wallet_status == 0 ) {
						//Checking available_balance
						$available_balance = check_agent_available_balance($userId, $con);
						error_log("available_balance response for = ".$available_balance);
						if ( $available_balance >= 0 || $available_balance != "" || $available_balance != null ) {
							if ( floatval($request_total_amount) <= floatval($available_balance) ) {
								$acc_trans_log_id = generate_seq_num(3400, $con);
								if ($acc_trans_log_id > 0)  {
									error_log("acc_trans_log_id = ".$acc_trans_log_id);
									$sanef_request_id = generate_seq_num(3700, $con);
									$data = array();
									$data['agentCode'] = $sanefAgentCode;
									$data['bankCode'] = $accBankCode;
									$data['requestId'] = $sanef_request_id;
									$data['accountNumber'] = $accountNo;
									$data['location'] = $location;
									$data['signature'] = $local_signature;
									$data['countryId'] = $countryId;
									$data['userId'] = $userId;
									$data['stateId'] = $stateId;
									$data['superAgentCode'] = SANEF_SUPER_AGENT_CODE;
									$data['localGovtId'] = ADMIN_LOCAL_GOVT_ID;
									
									$request_message = json_encode($data);
									$request_message = mysqli_real_escape_string($con, $request_message);
									
									$acc_trans_log_query = "INSERT INTO acc_trans_log (acc_trans_log_id, service_feature_id, partner_id, party_type, party_code, country_id, state_id, request_message, message_send_time, create_user, create_time) VALUES ($acc_trans_log_id, $productId, $partnerId, '$partyType', '$partyCode', $countryId, $stateId, left('SANEF Send Otp Request = $request_message', 1000), now(), $userId, now())";
									error_log("acc_trans_log_query = ". $acc_trans_log_query);
									$acc_trans_log_result = mysqli_query($con, $acc_trans_log_query);
									if ( $acc_trans_log_result ) {
										$acc_request_id = generate_seq_num(3500, $con);
									   	if ( $acc_request_id > 0)  {
											$acc_request_query = "INSERT INTO acc_request (acc_request_id, acc_trans_log_id, service_feature_code, country_id, state_id, local_govt_id, user_id, request_id, first_name, mobile, account_number, status, create_time) VALUES ($acc_request_id, $acc_trans_log_id, '$serviceFeatureCode', $countryId, $stateId, $localGovtId, $userId, '$sanef_request_id', '$customerName', '$mobile', $accountNo, 'I', now())";
											error_log("acc_request_query = ".$acc_request_query);
											$acc_request_result = mysqli_query($con, $acc_request_query);
											if( $acc_request_result ) {
												$data['localGovtId'] = ADMIN_LOCAL_GOVT_ID;
												$url = SANEFAPI_SERVER_CARD_TRIGGER_OTP;
												$tsec = time();
												$raw_data1 = SANEFAPI_SERVER_APP_PASSWORD.FINWEB_SERVER_SHORT_NAME."|".SANEFAPI_SERVER_APP_USERNAME.FINWEB_SERVER_SHORT_NAME."|".$tsec;
												error_log("raw_data1 = ".$raw_data1);
												$key1 = base64_encode($raw_data1);
												error_log("key1 = ".$key1);
												error_log("before calling post");
												error_log("url = ".$url);
												$data['key1'] = $key1;
												$data['signature'] = $local_signature;
												error_log("request sent ==> ".json_encode($data));
												$ch = curl_init($url);
												curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
												curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
												curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
												curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, SANEF_CURL_CONNECTION_TIMEOUT);
												curl_setopt($ch, CURLOPT_TIMEOUT, SANEF_CURL_TIMEOUT);
												$curl_response = curl_exec($ch);
												$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
												$curl_error = curl_errno($ch);
												curl_close($ch);
												if ( $curl_error == 0 ) {
													error_log("curl_error == 0 ");
													error_log("response received = ".$curl_response);
													error_log("code = ".$httpcode);
													if ( $httpcode == 200 ) {
														error_log("inside httpcode == 200");
														$api_response = json_decode($curl_response, true);
														$statusCode = $api_response['responseCode'];
														$responseDescription = $api_response['responseDescription'];
														error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
														error_log("response_received <=== ".$curl_response);
														$update_query = "UPDATE acc_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE acc_trans_log_id = $acc_trans_log_id";
														error_log("update_query = ".$update_query);
														$update_query_result = mysqli_query($con, $update_query);

														if($statusCode === 0) {
															error_log("inside statusCode == 0");
															$update_query = "UPDATE acc_request SET status = 'G', update_time = now() WHERE acc_request_id  = $acc_request_id ";
															error_log("update_query = ".$update_query);
															$update_query_result = mysqli_query($con, $update_query);

															$response["statusCode"] = "0";
															$response["result"] = "Success";
															$response["message"] = $responseDescription;
															$response["partnerId"] = $partnerId;
															$response["signature"] = $server_signature;
															$response["transctionId"] = $acc_request_id;
															$response["sanefRequestId"] = $sanef_request_id;
														}
														else {
															error_log("inside statusCode != 0");
															if ( $statusCode == '') {
																$statusCode = 50;
															}
															$comments = $statusCode." - ".$responseDescription." @ ".$current_time;
															$update_query = "UPDATE acc_request SET status = 'E', comments = '$comments', update_time = now() WHERE acc_request_id = $acc_request_id ";
															error_log("update_query = ".$update_query);
															$update_query_result = mysqli_query($con, $update_query);
														
															$response["statusCode"] = $statusCode;
															$response["result"] = "Error";
															$response["message"] = $responseDescription;
															$response["partnerId"] = $partnerId;
															$response["sanefRequestId"] = $sanef_request_id;
															$response["signature"] = $server_signature;
														}
													}else {
														error_log("inside httpcode != 200");
														$statusCode = $httpcode;
														$responseDescription = "HTTP Protocol Error";
														error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
														$update_query = "UPDATE acc_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE acc_trans_log_id = $acc_trans_log_id";
														error_log("update_query = ".$update_query);
														$update_query_result = mysqli_query($con, $update_query);

														$comments = $statusCode." - ".$responseDescription;
														error_log("update_query = ".$update_query);
														$update_query = "UPDATE acc_request SET status = 'E', comments = '$comments', update_time = now() WHERE acc_request_id = $acc_request_id ";
														$update_query_result = mysqli_query($con, $update_query);
																			
														$response["statusCode"] = $statusCode;
														$response["result"] = "Error";
														$response["message"] = "Error in connection to SANEF API Server";
														$response["partnerId"] = $partnerId;
														$response["sanefRequestId"] = $sanef_request_id;
														$response["signature"] = $server_signature;
													}
												}else {
													error_log("curl_error != 0 ");
																		
													$statusCode = $curl_error;
													$responseDescription = "CURL Execution Error";
													$comments = $statusCode." - ".$responseDescription;
													error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
													$update_query = "UPDATE acc_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE acc_trans_log_id = $acc_trans_log_id";
													error_log("update_query = ".$update_query);
													$update_query_result = mysqli_query($con, $update_query);

													$update_query = "UPDATE acc_request SET status = 'E', comments = '$comments', update_time = now() WHERE acc_request_id = $acc_request_id ";
													error_log("update_query = ".$update_query);
													$update_query_result = mysqli_query($con, $update_query);

													$response["statusCode"] = $statusCode;
													$response["result"] = "Error";
													$response["message"] = "Error in communication protocol";
													$response["partnerId"] = $partnerId;
													$response["sanefRequestId"] = $sanef_request_id;
													$response["signature"] = $server_signature;
												}
											}
											else {
												//Error in Generating ACC Request Id Result
												$response["statusCode"] = "205";
												$response["result"] = "Error";
												$response["message"] = "DB Error in SANEF Request Result";
												$response["partnerId"] = $partnerId;
												$response["signature"] = $server_signature;
											}
										}
										else {
											//Error in Generating BP Request Id
											$response["statusCode"] = "210";
											$response["result"] = "Error";
											$response["message"] = "DB Error in SANEF Request";
											$response["partnerId"] = $partnerId;
											$response["signature"] = $server_signature;
										}
									}
									else {
										//Error in Generating Transaction Log Result
										$response["statusCode"] = "220";
										$response["result"] = "Error";
										$response["message"] = "DB Error in Trans Log Result";
										$response["partnerId"] = $partnerId;
										$response["signature"] = $server_signature;
									}
								}
								else {
									//Error in Generating Transaction Log
									$response["statusCode"] = "230";
									$response["result"] = "Error";
									$response["message"] = "DB Error in Trans Log";
									$response["partnerId"] = $partnerId;
									$response["signature"] = $server_signature;
								}
							}
							else {
								// Insufficient Agent Available Balance
								$response["statusCode"] = "240";
								$response["result"] = "Error";
								$response["message"] = "Insufficient Agent Available Balance";
								$response["partnerId"] = $partnerId;
								$response["signature"] = $server_signature;
							}
						}
						else {
							//Agent Available Balance is not available
							$response["statusCode"] = "250";
							$response["result"] = "Error";
							$response["message"] = "Agent Available Balance is not available";
							$response["partnerId"] = $partnerId;
							$response["signature"] = $server_signature;
						}
					}
					else {
						// Error in accessing Agent Info & Wallet details.
						$resp_message = "";
						if ( $agent_info_wallet_status == 1 ) {
							$resp_message = "Agent status is not active";
						}else if ( $agent_info_wallet_status == 2 ) {
							$resp_message = "Agent is blocked";
						}else if ( $agent_info_wallet_status == 3 ) {
							$resp_message = "Agent Wallet is not active";
						}else if ( $agent_info_wallet_status == 4 ) {
							$resp_message = "Agent Wallet is blocked";
						}else {
							$resp_message = "Error in accessing Agent Info & Wallet details. Contact Kadick Admin";
						}
						$response["statusCode"] = "260";
						$response["result"] = "Error";
						$response["message"] = $resp_message;
						$response["partnerId"] = $partnerId;
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
		}
		else if(isset($data -> operation) && $data -> operation == 'CARD_LINK_SUBMIT_OTP') {
			error_log("inside operation == CARD_LINK_SUBMIT_OTP method");
			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
			  	&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
			   	&& isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
			   	&& isset($data->stateId) && !empty($data->stateId) && isset($data->partnerId) && !empty($data->partnerId) 
			   	&& isset($data->accountNo) && !empty($data->accountNo) && isset($data->cardNumber) && !empty($data->cardNumber)
			   	&& isset($data->amsCharge) && !empty($data->amsCharge) && isset($data->bank) && !empty($data->bank)
			   	&& isset($data->sanefAgentCode) && !empty($data->sanefAgentCode) && isset($data->agentCharge) && !empty($data->agentCharge)
			   	&& isset($data->otp) && !empty($data->otp) && isset($data->transactionId) && !empty($data->transactionId)
			   	&& isset($data->flexiRate) && !empty($data->flexiRate) && isset($data->location) && !empty($data->location)
			){
				ini_set('max_execution_time', 120);
				set_time_limit(120);
				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$parentCode = $data->parentCode;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$accBankCode = $data->bank->code;
				$accBankId = $data->bank->id;
				$accountNo = $data->accountNo;
				$cardNumber = $data->cardNumber;
				$otp = $data->otp;
				$transactionId = $data->transactionId;
				$sanefAgentCode = $data->sanefAgentCode;
				$agentCharge = $data->agentCharge;
				$amsCharge = $data->amsCharge;
				$stampCharge = $data->stampCharge;
				$otherCharge = $data->otherCharge;
				$parnterCharge = $data->parnterCharge;
				$partnerId = $data->partnerId;
				$location = $data->location;
				$requestAmount = 0;
				$flexiRate = $data->flexiRate;
				$serviceFeatureCode = "BCL";
				$session_validity = AGENT_SESSION_VALID_TIME;
				$localGovtId = ADMIN_LOCAL_GOVT_ID;
				$productId = 26;
				
				if ($parentCode == "") {
					$partyCount = 2;
				}else {
					$partyCount = 3;
				}

				$db_flexiRate = "N";
				$flexi_rate_query = "select state_flexi_rate_id from state_flexi_rate where state_id = $stateId and (service_feature_id is null or (service_feature_id = $productId)) and active = 'Y' and (start_date is null or (start_date is not null and date(start_date) <= current_date())) and (expiry_date is null or (expiry_date is not null and date(expiry_date) >= current_date())) order by state_flexi_rate_id limit 1";
				error_log("flexi_rate_query query = ".$flexi_rate_query);
				$flexi_rate_result = mysqli_query($con, $flexi_rate_query);
				if ($flexi_rate_result) {
					$flexi_rate_count = mysqli_num_rows($flexi_rate_result);
					if($flexi_rate_count > 0) {					
						$db_flexiRate = "Y";
					}
				}
				error_log("db_flexiRate = ".$db_flexiRate.", flexiRate = ".$flexiRate);										
				if ( "Y" == $flexiRate || "Y" == $db_flexiRate ) {
					$txtType = "F";
				}else {
					$txtType = "E";
				}
				error_log("txtType = ".$txtType.", partyCount = ".$partyCount);
				
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
					$validate_result = validateKey1($key1, $userId, $session_validity, 'Z', $con);
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
									
					error_log("before calling checking_feature_value: txtType = ".$txtType);
					$checking_feature_value_response = checking_feature_value($userId, $countryId, $stateId, $partyCount, $productId, $partnerId, $requestAmount, $txtType, $con);
					$checking_feature_value_response_split = explode("#",$checking_feature_value_response);
					$charges_details = $checking_feature_value_response_split[0];	
					$rateparties_details = $checking_feature_value_response_split[1];										
					$charges_details_split = explode("|",$charges_details);	
					$reponse_feature_value = $charges_details_split[0];	
					$service_feature_config_id = $charges_details_split[1];
					$ams_charge = $charges_details_split[2];
					$partner_charge = $charges_details_split[3];									
					$other_charge = $charges_details_split[4];
					
					$request_total_amount = floatval($ams_charge) +  floatval($partner_charge) + floatval($other_charge) + floatval($requestAmount);
					
					$daily_check_result = checkDailyLimit($userId, $request_total_amount, $con);
					error_log("daily_check_result = ".$daily_check_result);
					if ( $daily_check_result != 0 ){
						// Exceeded Daily Limit
						$response["statusCode"] = "998";
						$response["result"] = "Error";
						$response["message"] = "Failure: Exceeded Daily Limit";
						$response["signature"] = 0;
						error_log(json_encode($response));
						echo json_encode($response);
						return;
					}

					$agent_info_wallet_status = check_agent_info_wallet_status($partyType, $partyCode, $con);
					if ($agent_info_wallet_status == 0 ) {
						//Checking available_balance
						$available_balance = check_agent_available_balance($userId, $con);
						error_log("available_balance response for = ".$available_balance);
						if ( $available_balance >= 0 || $available_balance != "" || $available_balance != null ) {
							if ( floatval($request_total_amount) <= floatval($available_balance) ) {
								$acc_trans_log_id = generate_seq_num(3400, $con);
								if ($acc_trans_log_id > 0)  {
									error_log("acc_trans_log_id = ".$acc_trans_log_id);
									$sanef_request_id = generate_seq_num(3700, $con);
									$data = array();
									$data['agentCode'] = $sanefAgentCode;
									$data['bankCode'] = $accBankCode;
									$data['requestId'] = $sanef_request_id;
									$data['accountNumber'] = $accountNo;
									$data['cardSerialNumber'] = $cardNumber;
									$data['otp'] = $otp;
									$data['location'] = $location;
									$data['signature'] = $local_signature;
									$data['countryId'] = $countryId;
									$data['userId'] = $userId;
									$data['stateId'] = $stateId;
									$data['superAgentCode'] = SANEF_SUPER_AGENT_CODE;
									$data['localGovtId'] = ADMIN_LOCAL_GOVT_ID;
									
									$request_message = json_encode($data);
									$request_message = mysqli_real_escape_string($con, $request_message);
									
									$acc_trans_log_query = "INSERT INTO acc_trans_log (acc_trans_log_id, service_feature_id, partner_id, party_type, party_code, country_id, state_id, request_message, message_send_time, create_user, create_time) VALUES ($acc_trans_log_id, $productId, $partnerId, '$partyType', '$partyCode', $countryId, $stateId, left('SANEF Card Link Request = $request_message', 1000), now(), $userId, now())";
									error_log("acc_trans_log_query = ". $acc_trans_log_query);
									$acc_trans_log_result = mysqli_query($con, $acc_trans_log_query);
									if ( $acc_trans_log_result ) {
										$acc_request_id = $transactionId;						
										$acc_request_query = "update acc_request set acc_trans_log_id2 = $acc_trans_log_id, update_time = now() where acc_request_id = $acc_request_id";
										error_log("acc_request_query = ".$acc_request_query);
										$acc_request_result = mysqli_query($con, $acc_request_query);
										if( $acc_request_result ) {
											$acc_service_order_no = generate_seq_num(3600, $con);
											if( $acc_service_order_no > 0 )  {
												error_log("Inside Acc Service Card Link Order ==> ACC_SERVICE_ORDER_NO ".$acc_service_order_no);
												$acc_trans_type = 'SACLO';
												$transaction_id = $acc_service_order_no;
												$firstpartycode = $partyCode;
												$firstpartytype = $partyType;
												$secondpartycode = $parentCode;
												$narration = "Sanef Card Link Order #".$acc_service_order_no;
												if(($secondpartycode == "") || empty($secondpartycode) || ($secondpartycode = null)) {
													$secondpartycode = "";
													$secondpartytype = "";
												}
												else {
													$secondpartytype = substr($secondpartycode,0);
												}
												$journal_entry_id = process_glentry($acc_trans_type, $transaction_id, $firstpartycode, $firstpartytype, $secondpartycode, $secondpartytype, $narration, $request_total_amount, $userId, $con);
												if($journal_entry_id > 0) {
													$journal_entry_error = "N";	
													$acc_service_order_query = "INSERT INTO acc_service_order (acc_service_order_no, acc_trans_log_id, service_feature_code, bank_id, partner_id, user_id, total_amount, ams_charge, partner_charge, other_charge, agent_charge, stamp_charge, service_feature_config_id, date_time) VALUES ($acc_service_order_no, $acc_trans_log_id, '$serviceFeatureCode', $accBankId, $partnerId, $userId, $request_total_amount, $ams_charge, $partner_charge, $other_charge, $agentCharge, $stampCharge, $service_feature_config_id, now())";
													error_log("acc_service_order_query = ".$acc_service_order_query);
													$acc_service_order_result = mysqli_query($con, $acc_service_order_query);
													if($acc_service_order_result ) {
														$acc_order_rollback = "N";					
														error_log("inside success acc_service_order table entry");																									
														$get_acc_trans_type = getAcccTransType($acc_trans_type, $con);
														error_log("get_acc_trans_type = ".$get_acc_trans_type);	
														if($get_acc_trans_type != "-1"){
															$split = explode("|",$get_acc_trans_type);
															$ac_factor = $split[0];
															$cb_factor = $split[1];
															$acc_trans_type_id = $split[2];
															$update_wallet = walletupdateWithTransaction($acc_trans_type, $cb_factor, $firstpartytype, $firstpartycode, $request_total_amount, $con, $userId, $journal_entry_id);
															if( $update_wallet == 0 ) {	
																	
																$data['localGovtId'] = ADMIN_LOCAL_GOVT_ID;
																$url = SANEFAPI_SERVER_CARD_CARD_LINK;
																$tsec = time();
																$raw_data1 = SANEFAPI_SERVER_APP_PASSWORD.FINWEB_SERVER_SHORT_NAME."|".SANEFAPI_SERVER_APP_USERNAME.FINWEB_SERVER_SHORT_NAME."|".$tsec;
																error_log("raw_data1 = ".$raw_data1);
																$key1 = base64_encode($raw_data1);
																error_log("key1 = ".$key1);
																error_log("before calling post");
																error_log("url = ".$url);
																$data['key1'] = $key1;
																$data['signature'] = $local_signature;
																error_log("request sent ==> ".json_encode($data));
																$ch = curl_init($url);
																curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
																curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
																curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
																curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, SANEF_CURL_CONNECTION_TIMEOUT);
																curl_setopt($ch, CURLOPT_TIMEOUT, SANEF_CURL_TIMEOUT);
																$curl_response = curl_exec($ch);
																$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
																$curl_error = curl_errno($ch);
																curl_close($ch);
																if ( $curl_error == 0 ) {
																	error_log("curl_error == 0 ");
																	error_log("response received = ".$curl_response);
																	error_log("code = ".$httpcode);
																	if ( $httpcode == 200 ) {
																		error_log("inside httpcode == 200");
																		$api_response = json_decode($curl_response, true);
																		$statusCode = $api_response['responseCode'];
																		$responseDescription = $api_response['responseDescription'];
																		error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																		error_log("response_received <=== ".$curl_response);
																		$update_query = "UPDATE acc_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE acc_trans_log_id = $acc_trans_log_id";
																		error_log("update_query = ".$update_query);
																		$update_query_result = mysqli_query($con, $update_query);

																		if($statusCode === 0) {
																			error_log("inside statusCode == 0");
																			$update_query = "UPDATE acc_request SET status = 'S', comments = concat('OTP Request Id: ', request_id), bvn = '$cardNumber', request_id = $sanef_request_id, update_time = now(), order_no = $acc_service_order_no WHERE acc_request_id  = $acc_request_id ";
																			error_log("update_query = ".$update_query);
																			$update_query_result = mysqli_query($con, $update_query);
																				
																			$gl_post_return_value = process_glpost($journal_entry_id, $con);
																			if ( $gl_post_return_value == 0 ) {
																				error_log("Success in sanef card link gl_post for: ".$journal_entry_id);
																			}
																			else{
																				error_log("Error in gl_post, with gl_post_return_value = ".$gl_post_return_value);
																				insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AP", "W", "N", $request_total_amount, $con);
																			}

																			$order_post_result = post_accorder($acc_service_order_no, $con);
																			if ( $order_post_result == 0 ) { 
																				error_log("Success in sanef card link order for: ".$acc_service_order_no);
																			}else {
																				error_log("Error in sanef card link order for: ".$acc_service_order_no);
																			}
																			$serviceconfig = explode(",", $rateparties_details);
																			$service_insert_count = 0;

																			//Insert into acc_service_order_comm table
																			for($i = 0; $i < sizeof($serviceconfig); $i++) {
																				$accService_flag = insertAccountServiceOrderComm($acc_service_order_no, $serviceconfig[$i], $journal_entry_id, $txType, $agent_charge, $ams_charge, $con);
																				if ( $accService_flag == 0 ) {
																					++$service_insert_count;
																				}
																			}
																			if ( $service_insert_count == sizeof($serviceconfig) ) {
																				error_log("All entries for service_order insert in commission table. Insert count = ".$service_insert_count);
																			}else {
																				error_log("Not all entries for service_order insert in commission table. Insert count = ".$service_insert_count.", serviceConfig count = ".sizeof($serviceconfig));
																			}
																			$pcu_result = process_acc_comm_update($acc_service_order_no, $con);
																			if ( $pcu_result > 0 ) {
																				if ( $pcu_result == sizeof($serviceconfig) ) {
																					error_log("All acc_service_order_comm updates are completed. Count = ".$pcu_result);
																				}else {
																					error_log("Warning acc_service_order_comm updates are not matching completed. Count = ".$pcu_result);
																				}
																			}else {
																				error_log("Error in acc_service_order_comm records insert. Insert Count = ".$pcu_result);
																			}
																			$availableBalance = check_party_available_balance($partyType, $userId, $con);
																			$orderTime = getAccOrderTime($acc_service_order_no, $con);
																		
																			$response["statusCode"] = "0";
																			$response["result"] = "Success";
																			$response["message"] = $responseDescription;
																			$response["partnerId"] = $partnerId;
																			$response["signature"] = $server_signature;
																			$response["orderNo"] = $acc_service_order_no;
																			$response["sanefRequestId"] = $sanef_request_id;
																			$response["accountNumber"] = $accountNo;
																			$response["transactionTime"] = $orderTime;
																			$response["availableBalance"] = $availableBalance;
																		}
																		else {
																			$acc_order_rollback = "Y";
																			error_log("inside statusCode != 0");
																			if ( $statusCode == '') {
																				$statusCode = 50;
																			}
																			$comments = $statusCode." - ".$responseDescription." @ ".$current_time;
																			$update_query = "UPDATE acc_request SET status = 'E', comments = '$comments', update_time = now() WHERE acc_request_id = $acc_request_id ";
																			error_log("update_query = ".$update_query);
																			$update_query_result = mysqli_query($con, $update_query);

																			$acc_order_rollback = "Y";
																			$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																			if ( $gl_reverse_repsonse != 0 ) {
																				error_log("Error in acc service card link gl_reverse for: ".$journal_entry_id);
																				insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $request_total_amount, $con);
																			}else {
																				error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																			}

																			//Rollback wallet update
																			$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $request_total_amount, $con, $userId);
																			if ( $update_wallet != 0 ) {
																				error_log("Error in acc service card link rollback_wallet for: ".$journal_entry_id);
																				insertaccountrollback($userId, $journal_entry_id, $acc_trans_type, $request_total_amount, 2, "F", $con);
																			}else {
																				error_log("Success in acc service card link rollback_wallet for: ".$journal_entry_id);
																				//Insert into account_rollback table with success status
																			}
																				
																			$response["statusCode"] = $statusCode;
																			$response["result"] = "Error";
																			$response["message"] = $responseDescription;
																			$response["partnerId"] = $partnerId;
																			$response["sanefRequestId"] = $sanef_request_id;
																			$response["signature"] = $server_signature;
																		}
																	}else {
																		error_log("inside httpcode != 200");
																		$acc_order_rollback = "Y";
																		$statusCode = $httpcode;
																		$responseDescription = "HTTP Protocol Error";
																		error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																		$update_query = "UPDATE acc_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE acc_trans_log_id = $acc_trans_log_id";
																		error_log("update_query = ".$update_query);
																		$update_query_result = mysqli_query($con, $update_query);

																		$comments = $statusCode." - ".$responseDescription;
																		error_log("update_query = ".$update_query);
																		$update_query = "UPDATE acc_request SET status = 'E', comments = '$comments', update_time = now() WHERE acc_request_id = $acc_request_id ";
																		$update_query_result = mysqli_query($con, $update_query);
																	
																		$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																		if ( $gl_reverse_repsonse != 0 ) {
																			error_log("Error in Acc Service Acc openr gl_reverse for: ".$journal_entry_id);
																			insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $request_total_amount, $con);
																		}else {
																			error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																		}

																		//Rollback wallet update
																		$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $request_total_amount, $con, $userId);
																		if ( $update_wallet != 0 ) {
																			error_log("Error in Acc Service Card Link rollback_wallet for: ".$journal_entry_id);
																			insertaccountrollback($userId, $journal_entry_id, $acc_trans_type, $request_total_amount, 2, "F", $con);
																		}else {
																			error_log("Success in Acc Service Card Link rollback_wallet for: ".$journal_entry_id);
																			//Insert into account_rollback table with success status
																		}
																			
																		$response["statusCode"] = $statusCode;
																		$response["result"] = "Error";
																		$response["message"] = "Error in connection to SANEF API Server";
																		$response["partnerId"] = $partnerId;
																		$response["sanefRequestId"] = $sanef_request_id;
																		$response["signature"] = $server_signature;
																	}
																}else {
																	error_log("curl_error != 0 ");
																	$acc_order_rollback = "Y";
																	$statusCode = $curl_error;
																	$responseDescription = "CURL Execution Error";
																	$comments = $statusCode." - ".$responseDescription;
																	error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																	$update_query = "UPDATE acc_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE acc_trans_log_id = $acc_trans_log_id";
																	error_log("update_query = ".$update_query);
																	$update_query_result = mysqli_query($con, $update_query);

																	$update_query = "UPDATE acc_request SET status = 'E', comments = '$comments', update_time = now() WHERE acc_request_id = $acc_request_id ";
																	error_log("update_query = ".$update_query);
																	$update_query_result = mysqli_query($con, $update_query);

																	$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																	if ( $gl_reverse_repsonse != 0 ) {
																		error_log("Error in Acc Service Card Link fundtransfer gl_reverse for: ".$journal_entry_id);
																		insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $request_total_amount, $con);
																	}else {
																		error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																	}

																	//Rollback wallet update
																	$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $request_total_amount, $con, $userId);
																	if ( $update_wallet != 0 ) {
																		error_log("Error in Acc Service Card Link rollback_wallet for: ".$journal_entry_id);
																		insertaccountrollback($userId, $journal_entry_id, $acc_trans_type, $request_total_amount, 2, "F", $con);
																	}else {
																		error_log("Success in Acc Service Card Link rollback_wallet for: ".$journal_entry_id);
																		//Insert into account_rollback table with success status
																	}

																	$response["statusCode"] = $statusCode;
																	$response["result"] = "Error";
																	$response["message"] = "Error in communication protocol";
																	$response["partnerId"] = $partnerId;
																	$response["sanefRequestId"] = $sanef_request_id;
																	$response["signature"] = $server_signature;
																}
															}else {
																//Inside not success wallet update	
																$acc_order_rollback = "Y";		
																error_log("inside not able to update wallet for $acc_service_order_no");
																$msg = "Error: Account Service Card Link [$description] Order $acc_service_order_no due to failure in account update. Contact Kadick Admin.";

																$journal_reverse_query = "select gl_reverse($journal_entry_id) as gl_reverse_result";
																$journal_reverse_result = mysqli_query($con, $journal_reverse_query);
																if ( $journal_reverse_result ) {
																	$journal_reverse_result_row = mysqli_fetch_array($journal_reverse_result);
																	$journal_reverse_result_code = $journal_reverse_result_row['gl_reverse_result'];
																	error_log("journal_reverse_result_code = ".$journal_reverse_result_code);
																	if($journal_reverse_result_code == 0) {
																		//Journal Reverse is success
																		error_log("Journal Reverse is done when account balance update failed for Acc Service Acc Open order no = $acc_service_order_no");
																	}else {
																		//journal_reverse_error, log it in journal_error table
																		$journal_error_query = "insert into journal_error(journal_error_id, user_id, user_type, transaction_id, acc_trans_type_code, amount, error_code, error_type, create_date, status) values (0, $user_id, '$journal_user_type', $acc_service_order_no, 'FTFRO', $request_total_amount, 'AE', 'S', now(), 'N')";
																		error_log("journal_error_query = " + $journal_error_query);
																		$journal_error_result = mysqli_query($con, $journal_error_query);
																		if ( $journal_error_result ) {
																			error_log("journal_error logged successfully");
																		}else {
																			error_log("Error: Not able to log AE journal_error - ".mysqli_error());
																		}
																	}
																}else {
																	//journal_reverse_error, log it in journal_error table
																	$journal_error_query = "insert into journal_error(journal_error_id, user_id, user_type, transaction_id, acc_trans_type_code, amount, error_code, error_type, create_date, status) values (0, $user_id, '$journal_user_type', $acc_service_order_no, 'FTFRO', $request_total_amount, 'AE', 'S', now(), 'N')";
																	error_log("journal_error_query = " + $journal_error_query);
																	$journal_error_result = mysqli_query($con, $journal_error_query);
																	if ( $journal_error_result ) {
																		error_log("journal_error logged successfully");
																	} else {
																		error_log("Error: Not able to log AE journal_error - ".mysqli_error());
																	}
																}
																$response["statusCode"] = 180;
																$response["result"] = "Error";
																$response["message"] = "Error in updating wallet. Contact Kadick Admin";
																$response["partnerId"] = $partnerId;
																$response["signature"] = $server_signature;
															}
														}
														else {	
															//Error in getting acc_trans_type
															$error_comments = "Error in getting acc_trans_type";
															update_acc_request_for_error($transactionId, $error_comments);
															$acc_order_rollback = "Y";
															$response["statusCode"] = 190;
															$response["result"] = "Error";
															$response["message"] = "Error in getting Acc Transaction Type";
															$response["partnerId"] = $partnerId;
															$response["signature"] = $server_signature;
														}

														if ( $acc_order_rollback == "Y") {
															//Remove Acc. Services Order because of balance update error
															$acc_services_order_delete_query = "delete from acc_service_order where acc_service_order_no = $acc_service_order_no";
															error_log("acc_services_order_delete_query = " . $acc_services_order_delete_query);
															$acc_services_order_delete_result = mysqli_query($con, $acc_services_order_delete_query);
															if ( $acc_services_order_delete_result ) {
																error_log("acc_service_order delete successful");
															}else {
																error_log("acc_service_order delete failure = ".mysqli_error());
															}
														}
													}
													else {																				
														//failure - Insert acc_service_order
														$error_comments = "Error in Insert Acc Service Order";
														update_acc_request_for_error($transactionId, $error_comments);
														$response["result"] = "failure";
														$response["statusCode"] = 200;																	
														$response["message"] = "Error in inserting into Acc. Service Order";
														$response["partnerId"] = $partnerId;
														$response["signature"] = $server_signature;
													}
												}
												else {
													//failure - Create Jounral Entries
													$error_comments = "Error in creating journal entries";
													update_acc_request_for_error($transactionId, $error_comments);
													$response["result"] = "failure";
													$response["statusCode"] = 210;																	
													$response["message"] = "Error in creating Journal Entries";
													$response["partnerId"] = $partnerId;
													$response["signature"] = $server_signature;
												}
											}
											else {
												//failure - Create Acc Service Order No
												$error_comments = "Error in createing Acc Service Order";
												update_acc_request_for_error($transactionId, $error_comments);
												$response["result"] = "failure";
												$response["statusCode"] = 220;																	
												$response["message"] = "Error in creating Service Order No";
												$response["partnerId"] = $partnerId;
												$response["signature"] = $server_signature;
											}
										}
										else {
											//Error in Generating Bp Request Id Result
											$error_comments = "Error in updating acc_request";
											update_acc_request_for_error($transactionId, $error_comments);
											$response["statusCode"] = "205";
											$response["result"] = "Error";
											$response["message"] = "DB Error in SANEF Request Result";
											$response["partnerId"] = $partnerId;
											$response["signature"] = $server_signature;
										}
									}
									else {
										//Error in Generating Transaction Log Result
										$error_comments = "Error in generating Trans Log Result";
										update_acc_request_for_error($transactionId, $error_comments);
										$response["statusCode"] = "220";
										$response["result"] = "Error";
										$response["message"] = "DB Error in Trans Log Result";
										$response["partnerId"] = $partnerId;
										$response["signature"] = $server_signature;
									}
								}
								else {
									//Error in Generating Transaction Log
									$error_comments = "Error in generating Trans Log";
									update_acc_request_for_error($transactionId, $error_comments);
									$response["statusCode"] = "230";
									$response["result"] = "Error";
									$response["message"] = "DB Error in Trans Log";
									$response["partnerId"] = $partnerId;
									$response["signature"] = $server_signature;
								}
							}
							else {
								// Insufficient Agent Available Balance
								$error_comments = "InSufficent Balance";
								update_acc_request_for_error($transactionId, $error_comments);
								$response["statusCode"] = "240";
								$response["result"] = "Error";
								$response["message"] = "Insufficient Agent Available Balance";
								$response["partnerId"] = $partnerId;
								$response["signature"] = $server_signature;
							}
						}
						else {
							//Agent Available Balance is not available
							$error_comments = "Available Balance is not available";
							update_acc_request_for_error($transactionId, $error_comments);
							$response["statusCode"] = "250";
							$response["result"] = "Error";
							$response["message"] = "Agent Available Balance is not available";
							$response["partnerId"] = $partnerId;
							$response["signature"] = $server_signature;
						}
					}
					else {
						// Error in accessing Agent Info & Wallet details.
						$error_comments = "Error in acess Wallet";
						update_acc_request_for_error($transactionId, $error_comments);
						$resp_message = "";
						if ( $agent_info_wallet_status == 1 ) {
							$resp_message = "Agent status is not active";
						}else if ( $agent_info_wallet_status == 2 ) {
							$resp_message = "Agent is blocked";
						}else if ( $agent_info_wallet_status == 3 ) {
							$resp_message = "Agent Wallet is not active";
						}else if ( $agent_info_wallet_status == 4 ) {
							$resp_message = "Agent Wallet is blocked";
						}else {
							$resp_message = "Error in accessing Agent Info & Wallet details. Contact Kadick Admin";
						}
						$response["statusCode"] = "260";
						$response["result"] = "Error";
						$response["message"] = $resp_message;
						$response["partnerId"] = $partnerId;
						$response["signature"] = $server_signature;
					}
				}else {
					// Invalid Signature
					$error_comments = "Invalid Signature";
					update_acc_request_for_error($transactionId, $error_comments);
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
		}else if(isset($data -> operation) && $data -> operation == 'CANCEL_ORDER_UPDATE') {
			error_log("inside operation == CANCEL_ORDER_UPDATE method");
			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
				&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
				&& isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
				&& isset($data->stateId) && !empty($data->stateId) && isset($data->transactionId) && !empty($data->transactionId)
				&& isset($data->comments) && !empty($data->comments) && isset($data->picPoint) && !empty($data->picPoint)
				&& isset($data->orderType) && !empty($data->orderType) 
			){

				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$parentCode = $data->parentCode;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$transactionId = $data->transactionId;
				$comments = $data->comments;
				$picPoint = $data->picPoint;
				$orderType = $data->orderType;
				$session_validity = AGENT_SESSION_VALID_TIME;
				
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
					$validate_result = validateKey1($key1, $userId, $session_validity, 'Z', $con);
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
					
					$error_comments = $orderType.": ".$picPoint."-".$comments;
					$update_cancel_query = "UPDATE acc_request SET status = 'X', comments = '$error_comments', update_time = now() WHERE acc_request_id = $transactionId";
					error_log("update_cancel_query = ".$update_cancel_query);
					$update_cancel_result = mysqli_query($con, $update_cancel_query);
					if ( $update_cancel_result) {
						error_log("successful order update for transaction_id = ".$transactionId);
					}else {
						error_log("error in order update for transaction_id = ".$transactionId);
					}
										
					$response["statusCode"] = "0";
					$response["result"] = "Succes";
					$response["message"] = "Successful cancel processing";
					$response["transactionId"] = $transactionId;
					$response["serverUpdate"] = "Y";
					$response["orderType"] = $orderType;
					$response["userId"] = $userId;
					
				}else {
					$response["statusCode"] = "100";
					$response["result"] = "Error";
					$response["message"] = "Invalid Signature";
					$response["transactionId"] = $transactionId;
					$response["serverUpdate"] = "N";
					$response["orderType"] = $orderType;
					$response["userId"] = $userId;
			
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
			$response["result"] = "success";
			$response["status"] = "500";
			$response["message"] = "Invalid Operation";
			$response["signature"] = 0;
		}
	}
	else {
		// Invalid Request Method
		$response["result"] = "success";
		$response["status"] = "600";
		$response["message"] = "Post Failure";
		$response["signature"] = 0;
	}
    	error_log("card_link ==> ".json_encode($response));
	echo json_encode($response);
	
function update_acc_request_for_error($transactionId, $error_comments) {
	
	$update_query = "UPDATE acc_request SET status = 'E', comments = '$error_comments', update_time = now() WHERE acc_request_id = $transactionId";
	error_log("error_update_query = ".$update_query);
	$update_query_result = mysqli_query($con, $update_query);
	if ( $update_query_result) {
		error_log("successful order update for transaction_id = ".$transactionId);
	}else {
		error_log("error in order update for transaction_id = ".$transactionId);
	}
}
	
function checking_feature_value($userId, $country, $state, $partyCount, $product, $partner, $requestAmount, $txtType, $con) {
			
	$res = -1;
	$query = "SELECT get_feature_value_new($country, $state, null, $product, $partner, $requestAmount, '$txtType', $partyCount, null, null, $userId, -1) as res";
	error_log($query);
	$result =  mysqli_query($con, $query);
	if (!$result) {
		error_log("Error: checking_feature_value = %s\n".mysqli_error($con));
	}
	$row = mysqli_fetch_assoc($result); 
	$res = $row['res']; 		
	return $res;
}
?>