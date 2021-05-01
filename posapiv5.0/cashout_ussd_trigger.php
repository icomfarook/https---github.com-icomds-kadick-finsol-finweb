<?php
	include('../common/admin/configmysql.php');
	include ("get_prime.php");	
	include ("functions.php");
	require_once ("AesCipher.php");
	error_log("inside pcposapi/cashout_ussd_trigger.php");

	//ERROR_REPORTING(E_ALL);
	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;
	$journal_entry_error = "N";

	//Checking Post Method.
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		error_log("inside post request method");
		$data = json_decode(file_get_contents("php://input"));
		error_log("cashout_ussd_trigger <== ".json_encode($data));

		if( isset($data->operation) && $data->operation == 'CASHOUT_USSD_TRIGGER') {
			error_log("inside operation == CASHOUT_USSD_TRIGGER method");

            		if ( isset($data->partnerId) && !empty($data->partnerId) && isset($data->totalAmount) && !empty($data->totalAmount) 
                		&& isset($data->requestAmount) && !empty($data->requestAmount) && isset($data->mobileNo) && !empty($data->mobileNo)  
		   		&& isset($data->countryId) && !empty($data->countryId) && isset($data->partyCode) && !empty($data->partyCode) 
				&& isset($data->partyType) && !empty($data->partyType) && isset($data->productId) && !empty($data->productId)
				&& isset($data->userId) && !empty($data->userId) && isset($data->signature) && !empty($data->signature)
                		&& isset($data->key1) && !empty($data->key1) && isset($data->amsCharge) && !empty($data->amsCharge)
				&& isset($data->stateId) && !empty($data->stateId) && isset($data->senderName) && !empty($data->senderName)
				&& isset($data->bank) && !empty($data->bank)
	  		) {
				error_log("inside all inputs are set correctly");	
				$partnerId = $data->partnerId;
				$requestedAmount = $data->requestAmount;
				$totalAmount = $data->totalAmount;
				$cardType = $data->cardType;
				$partyCode = $data->partyCode;
				$partyType =  $data->partyType;
				$parentCode = $data->parentCode;
				$productId = $data->productId;	
				$userId = $data->userId;
				$signature= $data->signature;
				$key1 = $data->key1;								
				$amsCharge = $data->amsCharge;
				$partnerCharge = $data->partnerCharge;
				$otherCharge = $data->otherCharge;		
				$countryId = $data->countryId;
				$stateId = $data->stateId;								 
				$mobileNo = $data->mobileNo;	
				$senderName = $data->senderName;
				$stampCharge = $data->stampCharge;
				$flexiRate = $data->flexiRate;
				$agentCharge = $data->agentCharge;
				$bank = $data->bank;
				$session_validity = AGENT_SESSION_VALID_TIME;

				if ( $partnerId == 1 ) {
					$txType = "I";
				}else {
					$txType = "E";
				}

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
					$txType = "F";
				}
				error_log("txType = ".$txType.", partyCount = ".$partyCount);
				
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

				//checking  signature 
				if ( $local_signature == $signature ){		
					
					$validate_result = validateKey1($key1, $userId, $session_validity, 'S', $con);
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
					
					$daily_check_result = checkDailyLimit($userId, $requestedAmount, $con);
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
					
					error_log("before calling checking_feature_value: txType = ".$txType);
					$checking_feature_value_response = checking_feature_value($userId, $countryId, $stateId, $partyCount, $productId, $partnerId, $requestedAmount, $txType, $con);
					$checking_feature_value_response_split = explode("#",$checking_feature_value_response);
					$charges_details = $checking_feature_value_response_split[0];	
					$rateparties_details = $checking_feature_value_response_split[1];										
					$charges_details_split = explode("|",$charges_details);										
					
					$rate_check_result = "N";
					$reponse_feature_value = $charges_details_split[0];	
					$service_feature_config_id = $charges_details_split[1];
					$ams_charge = $charges_details_split[2];
					$partner_charge = $charges_details_split[3];									
					$other_charge = $charges_details_split[4];
					if ( $txType == "F" ) {
						error_log("inside txType == F");
						$db_max_charge_amount = 0;
						$serviceconfig = explode(",", $rateparties_details);
						for($i = 0; $i < sizeof($serviceconfig); $i++) {
							if ( strpos($serviceconfig[$i], "Agent") !== false ) {
								$serviceconfig_array = explode("~", $serviceconfig[$i]);
								$db_max_charge_amount = $serviceconfig_array[4];
								break;
							}
						}
						$request_check_max_total_amount = floatval($amsCharge) + floatval($agentCharge) + floatval($partner_charge) + floatval($other_charge);
						error_log("db_max_charge_amount = ".$db_max_charge_amount.", request_check_max_total_amount = ".$request_check_max_total_amount);
						if( $reponse_feature_value == 0 && (floatval($ams_charge) == floatval($amsCharge)) 
							&& (floatval($partner_charge) == floatval($partnerCharge)) && $request_check_max_total_amount <= floatval($db_max_charge_amount) ) {
							$rate_check_result = "Y";
						}else{
							$rate_check_result = "N";
						}
					}else {								
						error_log("inside txType != F");									
						$request_check_total_amount = floatval($amsCharge) +  floatval($partnerCharge) + floatval($otherCharge) + floatval($requestedAmount);
						if( $reponse_feature_value == 0 &&  (floatval($ams_charge) == floatval($amsCharge) )  
							&& (floatval($partner_charge) == floatval($partnerCharge)) && (floatval($other_charge) == floatval($otherCharge)) 
							&& floatval($request_check_total_amount) == floatval($totalAmount)) {
							$rate_check_result = "Y";
						}else {
							$rate_check_result = "N";
						}
					}
					// checkin get_feature_value response code
					if( $rate_check_result == "Y" ) {
					
						$agent_info_wallet_status = check_agent_info_wallet_status($partyType, $partyCode, $con);
						if ($agent_info_wallet_status == 0 ) {
							$fin_trans_log_id = generate_seq_num(1600, $con);
							unset($data->key1);
						
							$requestData = array();
							$requestData['totalAmount'] = $totalAmount;
							$requestData['transactionType'] = "0";
							$requestData['signature'] = $signature;
							$requestData['countryId'] = $countryId;
							$requestData['stateId'] = $stateId;
							$requestData['localGovtId'] = ADMIN_LOCAL_GOVT_ID;
							$requestData['userId'] = $userId;
							$url = FINAPI_SERVER_CASHOUT_USSD_GENREF_URL;
							$tsec = time();
							$raw_data1 = BPAPI_SERVER_APP_PASSWORD."|".BPAPI_SERVER_APP_USERNAME."|".$tsec;
							error_log("raw_data1 = ".$raw_data1);
							$key1 = base64_encode($raw_data1);
							$requestData['key1'] = $key1;
							$request_message = json_encode($requestData);
											
							if( $fin_trans_log_id > 0 )  {
								error_log("fin_trans_log_id = ".$fin_trans_log_id);
								$fin_trans_log_query = "INSERT INTO fin_trans_log (fin_trans_log_id, fin_service_feature_id, partner_id, party_type, party_code, country_id, state_id, amount, request_message, message_send_time, create_user, create_time) VALUES ($fin_trans_log_id, $productId, $partnerId, '$partyType', '$partyCode', $countryId, $stateId, $totalAmount, left('USSD Trigger Request = $request_message', 1000), now(), $userId, now())";
								error_log("fin_trans_log_query = ".$fin_trans_log_query);
								$fin_trans_log_result = mysqli_query($con, $fin_trans_log_query);
								if($fin_trans_log_result ) {
                                					$fin_request_id = generate_seq_num(2800, $con);
                                					$fin_service_order_no = generate_seq_num(1500, $con);
                                					if($fin_request_id > 0)  {
										$senderName = mysqli_real_escape_string($con, $senderName);
										if ( $txType == "F" ) {
											$newAmsCharge = $amsCharge + $agentCharge;
                                							$fin_request_query = "INSERT INTO fin_request (fin_request_id, fin_trans_log_id1, service_feature_code, country_id, state_id, request_amount, user_id, service_charge, partner_charge, other_charge, total_amount, sender_name, mobile_no, status, create_time, bank_id) VALUES ($fin_request_id, $fin_trans_log_id, 'COD', $countryId, $stateId, $requestedAmount, $userId, $newAmsCharge, $partnerCharge, $otherCharge, $totalAmount, '$senderName', '$mobileNo', 'I', now(), $bank->id)";
                                						}else {
                                							$fin_request_query = "INSERT INTO fin_request (fin_request_id, fin_trans_log_id1, service_feature_code, country_id, state_id, request_amount, user_id, service_charge, partner_charge, other_charge, total_amount, sender_name, mobile_no, status, create_time, bank_id) VALUES ($fin_request_id, $fin_trans_log_id, 'COD', $countryId, $stateId, $requestedAmount, $userId, $amsCharge, $partnerCharge, $otherCharge, $totalAmount, '$senderName', '$mobileNo', 'I', now(), $bank->id)";
                                						}
                                						error_log("fin_request_query = ".$fin_request_query);
                                						$fin_request_result = mysqli_query($con, $fin_request_query);
                                						if( $fin_request_result ) {
                                							$requestData['orderId'] = $fin_service_order_no;
                                							$requestData['transactionId'] = $fin_request_id;
											$ch = curl_init($url);
											curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
											curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
											curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
											curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, CORALPAY_CURL_CONNECTION_TIMEOUT);
											curl_setopt($ch, CURLOPT_TIMEOUT, CORALPAY_CURL_TIMEOUT);
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
													$update_query = "UPDATE fin_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE fin_trans_log_id = $fin_trans_log_id";
													error_log("update_query = ".$update_query);
													$update_query_result = mysqli_query($con, $update_query);

													if($statusCode == 0) {
														error_log("inside statusCode == 0");
														$cpMessage = "TraceId: ".$api_response['cpTraceId'].", ResponseCode: ".$api_response['cpResponseCode'].", ResponseMessage: ".$api_response['cpResponseMessage'];
														$update_query = "UPDATE fin_request SET status = 'G', order_no = $fin_service_order_no, auth_code = '".$api_response['transactionId']."', rrn = '".$api_response['reference']."', comments = '".$cpMessage."', update_time = now() WHERE fin_request_id = $fin_request_id";
														error_log("update_query = ".$update_query);
														$update_query_result = mysqli_query($con, $update_query);
	
														if($fin_service_order_no > 0) {
															$new_ams_charge = floatval($amsCharge) - floatval($agentCharge);
															if ( $txType == "F" ) {
																$fin_service_order_query = "INSERT INTO fin_service_order (fin_service_order_no, fin_trans_log_id, service_feature_code, partner_id, user_id, total_amount, request_amount, ams_charge, partner_charge, other_charge, service_feature_config_id, mobile_no, date_time, stamp_charge, agent_charge, auth_code, bank_id) VALUES ($fin_service_order_no, $fin_trans_log_id, 'COD', $partnerId, $userId, $totalAmount, $requestedAmount, $amsCharge, $partnerCharge, $otherCharge, $service_feature_config_id, '$mobileNo', now(), $stampCharge, $agentCharge, '".$api_response['transactionId']."', $bank->id)";
															}else {
																$fin_service_order_query = "INSERT INTO fin_service_order (fin_service_order_no, fin_trans_log_id, service_feature_code, partner_id, user_id, total_amount, request_amount, ams_charge, partner_charge, other_charge, service_feature_config_id, mobile_no, date_time, stamp_charge, agent_charge, auth_code, bank_id) VALUES ($fin_service_order_no, $fin_trans_log_id, 'COD', $partnerId, $userId, $totalAmount, $requestedAmount, $new_ams_charge, $partnerCharge, $otherCharge, $service_feature_config_id, '$mobileNo', now(), $stampCharge, $agentCharge, '".$api_response['transactionId']."', $bank->id)";
															}
															error_log("fin_service_order_query = ".$fin_service_order_query);
															$fin_service_order_result = mysqli_query($con, $fin_service_order_query);
															if( $fin_service_order_result ) {
																$response["result"] = "Success";
																$response["statusCode"] = 0;
																$response["orderNo"] = $fin_service_order_no;
																$response["transactionId"] = $fin_request_id;
																$response["referenceNo"] = $api_response['reference'];
																$response["cpTransactionId"] = $api_response['transactionId'];
																$response["amount"] = $api_response['amount'];
																$response["traceId"] = $api_response['cpTraceId'];
																$response["cpResponseCode"] = $api_response['cpResponseCode'];
																$response["cpResponseMessage"] = $api_response['cpResponseMessage'];
																$response["signature"] = $server_signature;
																$response["partnerId"] = $partnerId;
																$response["message"] = "CashOut USSD with Order # ".$fin_service_order_no." is trigerred. Use Short Code & Reference No to initiate USSD from Customer registered mobile";
															}
															else {
																$response["result"] = "Failure";
																$response["statusCode"] = 230;																	
																$response["message"] = "Error in fin_service_order for CashOut USSD order # ".$fin_service_order_no;
																$response["partnerId"] = $partnerId;
																$response["signature"] = $server_signature;
															}
														}
														else {
															//failure - Create Service Order No
															$response["result"] = "failure";
															$response["statusCode"] = 240;																	
															$response["message"] = "Error in creating Service Order No";
															$response["partnerId"] = $partnerId;
															$response["signature"] = $server_signature;
															$response["message"] = "Error in getting fin_service_order_no for CashOut Phone order # ".$fin_service_order_no;
														}
													}
													else {
														error_log("inside statusCode != 0");
														$approver_comments = "CG: ".$api_response['status']." - ".$api_response['statusDescritpion']." @ ".$api_response['transactionTime'];
														$update_query = "UPDATE fin_request SET status = 'E', rrn = '".$api_response['orderId']."', approver_comments = '$approver_comments', auth_code = '".$api_response['transactionTime']."', comments = '".$api_response['status']."', account_no = '".$api_response['statusDescription']."', update_time = now() WHERE fin_request_id = $fin_request_id ";
														error_log("update_query = ".$update_query);
														$update_query_result = mysqli_query($con, $update_query);

														$response["statusCode"] = $statusCode;
														$response["result"] = "Error";
														$response["message"] = $responseDescription.", Tx Id: ".$api_response['orderId'].", ".$api_response['status']." - ".$api_response['statusDescritpion']." @ ".$api_response['transactionTime'];
														$response["partnerId"] = $partnerId;
														$response["signature"] = $server_signature;
													}
												}else {
													error_log("inside httpcode != 200");
													$statusCode = $httpcode;
													$responseDescription = "HTTP Protocol Error";
													error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
													$update_query = "UPDATE fin_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE fin_trans_log_id = $fin_trans_log_id";
													error_log("update_query = ".$update_query);
													$update_query_result = mysqli_query($con, $update_query);

													$approver_comments = "CG: ".$statusCode." - ".$responseDescription;
													error_log("update_query = ".$update_query);
													$update_query = "UPDATE fin_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE fin_request_id = $fin_request_id ";
													$update_query_result = mysqli_query($con, $update_query);
	
													$response["statusCode"] = $statusCode;
													$response["result"] = "Error";
													$response["message"] = "Error in connection to CoralPay API Server";
													$response["partnerId"] = $partnerId;
													$response["signature"] = $server_signature;
												}
											}else {
												error_log("curl_error != 0 ");
												$statusCode = $curl_error;
												$responseDescription = "CURL Execution Error";
												$approver_comments = "CG: ".$statusCode." - ".$responseDescription;
												error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
												$update_query = "UPDATE fin_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE fin_trans_log_id = $fin_trans_log_id";
												error_log("update_query = ".$update_query);
												$update_query_result = mysqli_query($con, $update_query);

												$update_query = "UPDATE fin_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE fin_request_id = $fin_request_id ";
												error_log("update_query = ".$update_query);
												$update_query_result = mysqli_query($con, $update_query);
	
												$response["statusCode"] = $statusCode;
												$response["result"] = "Error";
												$response["message"] = "Error in communication protocol";
												$response["partnerId"] = $partnerId;
												$response["signature"] = $server_signature;
											}
										}
										else {
											//failure - Creating fin_trans_log no table
											$response["result"] = "failure";
											$response["statusCode"] = 250;																	
											$response["message"] = "Error in creating order for Fin Transaction Log Table";
											$response["partnerId"] = $partnerId;
											$response["signature"] = $server_signature;
										}
									}
									else {
										//failure - Creating fin_trans_log no table
										$response["result"] = "failure";
										$response["statusCode"] = 260;																	
										$response["message"] = "Error in creating order for Fin Transaction Log Table";
										$response["partnerId"] = $partnerId;
										$response["signature"] = $server_signature;
									}
								}
								else {
									$response["result"] = "failure";
									$response["statusCode"] = 270;																	
									$response["message"] = "Error in updating Fin Transaction Log Table";
									$response["partnerId"] = $partnerId;
									$response["signature"] = $server_signature;
								}
							}
							else {
								$response["result"] = "Failure";
								$response["message"] = "Error in creating fin_trans_log_id for CashOut USSD";
								$response["statusCode"] = 280;	
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
							$response["statusCode"] = 290;
							$response["result"] = "Error";
							$response["message"] = $resp_message;
							$response["partnerId"] = $partnerId;
						}
					}
					else {
					    	//Error - Db total amount and client total amount are diferent
						$response["statusCode"] = 290;
						$response["result"] = "Error";
						$response["message"] = "Failure: Invalid request...";
						$response["partnerId"] = $partnerId;
						$response["signature"] = 0;
					}
				}
				else {
					// Invalid Singature
					$response["statusCode"] = 300;
					$response["result"] = "Error";
					$response["message"] = "Failure: Invalid request";
					$response["partnerId"] = $partnerId;
					$response["signature"] = 0;
				}								
			}
			else {
				// Failure - Invalid Data
				$response["result"] = "failure";
				$response["statusCode"] = 400;
				$response["message"] = "Failure: Invalid Data";
				$response["partnerId"] = 0;
				$response["signature"] = 0;
			}
		}
		else if(isset($data -> operation) && $data -> operation == 'CASHOUT_USSD_BANK_LIST') {
			error_log("inside operation == CASHOUT_USSD_BANK_LIST method");
			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
				&& isset($data->userId) && !empty($data->userId) && isset($data->stateId) && !empty($data->stateId) 
				&& isset($data->countryId) && !empty($data->countryId)
			){
				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$billerId = $data->billerId;
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
		                	$validate_result = validateKey1($key1, $userId, $session_validity, '3', $con);
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
		                	$select_ussd_bank_query = "select m.bank_master_id, m.name, u.ussd_code from bank_master m, cashout_ussd_bank u where m.bank_master_id = u.bank_master_id and u.active = 'Y' and m.active = 'Y' and (start_date is null or current_date >= start_date) and (expiry_date is null or current_date() <= expiry_date) order by m.name";
		                	error_log("select_ussd_bank_query = ".$select_ussd_bank_query);
		                	$select_ussd_bank_result = mysqli_query($con, $select_ussd_bank_query);
		                   	$response["ussdBanks"] = array();
					if ( $select_ussd_bank_result ) {
						while($select_ussd_bank_row = mysqli_fetch_assoc($select_ussd_bank_result)) {
						    	$ussdBank = array();
						    	$ussdBank['id'] = $select_ussd_bank_row['bank_master_id'];
						    	$ussdBank['name'] = $select_ussd_bank_row['name'];
						    	$ussdBank['ussdCode'] = $select_ussd_bank_row['ussd_code'];
						    	array_push($response["ussdBanks"], $ussdBank);
						}
		                        	$response["result"] = "Success";
		                        	$response["message"] = "Your request is processed successfuly";
		                        	$response["statusCode"] = 0;
		                        	$response["signature"] = $server_signature;
					}
		                	else {
		                	       	$response["result"] = "Error";
		                	       	$response["message"] = "Error in finding your Ussd Bank list";
		                	       	$response["statusCode"] = "100";
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
		else if(isset($data -> operation) && $data -> operation == 'CASHOUT_USSD_CHECK_STATUS') {
			error_log("inside operation == CASHOUT_USSD_CHECK_STATUS method");
			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
				&& isset($data->userId) && !empty($data->userId) && isset($data->stateId) && !empty($data->stateId) 
				&& isset($data->countryId) && !empty($data->countryId) && isset($data->partnerId) && !empty($data->partnerId)
				&& isset($data->partyCode) && !empty($data->partyCode) && isset($data->partyType) && !empty($data->partyType)
				&& isset($data->orderNo) && !empty($data->orderNo) && isset($data->referenceNo) && !empty($data->referenceNo)
				&& isset($data->transactionId) && !empty($data->transactionId)
			){
				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$orderNo = $data->orderNo;
				$referenceNo = $data->referenceNo;
				$transactionId = $data->transactionId;
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
		                	$validate_result = validateKey1($key1, $userId, $session_validity, '3', $con);
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
		                	$select_cashout_ussd_check_status_query = "select c.ussd_code, ifnull(a.reference_no, '-') as institution_code, ifnull(b.account_no,'-') as customer_mobile, b.status, a.fin_service_order_no, b.fin_request_id, b.rrn as reference_no, b.auth_code as cp_transaction_id, ifnull(b.comments,'-') as transaction_time from fin_service_order a, fin_request b, cashout_ussd_bank c where c.bank_master_id = b.bank_id and a.fin_service_order_no = b.order_no and a.fin_service_order_no = $orderNo and b.fin_request_id = $transactionId";
		                	error_log("select_cashout_ussd_check_status_query = ".$select_cashout_ussd_check_status_query);
		                	$select_cashout_ussd_check_status_result = mysqli_query($con, $select_cashout_ussd_check_status_query);
		                   	if ( $select_cashout_ussd_check_status_result ) {
		                   		if (!empty($select_cashout_ussd_check_status_result) && mysqli_num_rows($select_cashout_ussd_check_status_result) > 0 ) {
							$select_cashout_ussd_check_status_row = mysqli_fetch_assoc($select_cashout_ussd_check_status_result);
					    		$response['shortCode'] = $select_cashout_ussd_check_status_row['ussd_code'];
					    		$response['institutionCode'] = $select_cashout_ussd_check_status_row['institution_code'];
					    		$response['customerMobile'] = $select_cashout_ussd_check_status_row['customer_mobile'];
					    		$response['orderStatus'] = $select_cashout_ussd_check_status_row['status'];
					    		$response['orderNo'] = $select_cashout_ussd_check_status_row['fin_service_order_no'];
					    		$response['transactionId'] = $select_cashout_ussd_check_status_row['fin_request_id'];
					    		$response['referenceNo'] = $select_cashout_ussd_check_status_row['reference_no'];
					    		$response['cpTransactionId'] = $select_cashout_ussd_check_status_row['cp_transaction_id'];
					    		$response['dateTime'] = $select_cashout_ussd_check_status_row['transaction_time'];
					    		$response["result"] = "Success";
		                        		$response["message"] = "Your request is processed successfuly";
		                        		$response["statusCode"] = 0;
		                        		$response["signature"] = $server_signature;
		                        	}else {
		                        		$response["result"] = "Error";
							$response["message"] = "Your Cashout USSD Order is not available";
							$response["statusCode"] = "90";
		                	       		$response["signature"] = $server_signature;
		                        	}
					}
		                	else {
		                	       	$response["result"] = "Error";
		                	       	$response["message"] = "Error in finding your Cashout USSD Order";
		                	       	$response["statusCode"] = "100";
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
		else {	
			// Invalid Operation
			$response["statusCode"] = 500;
			$response["result"] = "Error";
			$response["message"] = "Failure: Invalid Operation";
			$response["partnerId"] = 0;
			$response["signature"] = 0;
		}
	}else {
		// Invalid Request Method
		$response["statusCode"] = 600;
		$response["result"] = "Error";
		$response["message"] = "Failure: Invalid Request Method";
		$response["partnerId"] = 0;
		$response["signature"] = 0;	
	}			
		
	// echoing JSON response
   	error_log("cashout_ussd_trigger ==> ".json_encode($response));
	echo json_encode($response);
	return;			
		
function checking_feature_value($userId, $country, $state, $partyCount, $product, $partner, $requestedAmount, $txtype, $con) {
		
	$query = "SELECT get_feature_value_new($country, $state, null, $product, $partner, $requestedAmount, '$txtype', $partyCount, null, null, $userId, -1) as res";
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
