<?php
	include('../common/admin/configmysql.php');
	include ("get_prime.php");	
	include ("functions.php");
	require_once ("AesCipher.php");
	error_log("inside pcposapi/cashin_fund_transfer.php");

	//ERROR_REPORTING(E_ALL);
	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;
	$journal_entry_error = "N";

	//Checking Post Method.
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		error_log("inside post request method");
		$data = json_decode(file_get_contents("php://input"));
		error_log("cashin_fund_transfer <== ".json_encode($data));

		if( isset($data->operation) && $data->operation == 'CASHIN_SUBMIT_OPERATION') {
			error_log("inside operation == CASHIN_SUBMIT_OPERATION method");

			if (  isset($data->accountNumber) && !empty($data->accountNumber) && isset($data->partnerId ) && !empty($data->partnerId)
				&& isset($data->bankCode) && !empty($data->bankCode) && isset($data->sessionId) && !empty($data->sessionId) 
				//&& isset($data->bvn) && !empty($data->bvn) 
				//&& isset($data->kycLevel) && !empty($data->kycLevel) 
		   		&& isset($data->totalAmount) && !empty($data->totalAmount) && isset($data->requestAmount) && !empty($data->requestAmount)
		   		&& isset($data->narration) && !empty($data->narration) && isset($data->mobileNo) && !empty($data->mobileNo)
		   		&& isset($data->countryId) && !empty($data->countryId) && isset($data->partyCode) && !empty($data->partyCode) 
				&& isset($data->partyType) && !empty($data->partyType) && isset($data->productId) && !empty($data->productId)
				&& isset($data->userId) && !empty($data->userId) && isset($data->signature) && !empty($data->signature)
				&& isset($data->key1) && !empty($data->key1) && isset($data->amsCharge) && !empty($data->amsCharge)
				&& isset($data->transactionId) && !empty($data->transactionId) && isset($data->bankId) && !empty($data->bankId)
				&& isset($data->stateId) && !empty($data->stateId) && isset($data->accountName) && !empty($data->accountName)
	  		) {
	  			ini_set('max_execution_time', 90);
				set_time_limit(90);
				error_log("inside all inputs are set correctly");	
				$partnerId = $data->partnerId;
				$accountNumber= $data->accountNumber;
				$bankCode = $data->bankCode;
				$bankId = $data->bankId;
				$sessionId = $data->sessionId;
				$bvn = $data->bvn;
				$kycLevel = $data->kycLevel;
				$requestedAmount = $data->requestAmount;
				$totalAmount = $data->totalAmount;
				$narration = $data->narration;
				$transactionId = $data->transactionId;
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
				$fin_request_id = $data->transactionId;
				$accountName = $data->accountName;
				$stampCharge = $data->stampCharge;
				$flexiRate = $data->flexiRate;
				$agentCharge = $data->agentCharge;
				$session_validity = AGENT_SESSION_VALID_TIME;
				$location = $data->location;
				
				//Ansari - 17Sep2021
				$flexiRate = "N";
				
				if(is_null($location)) $location = "6.47668,3.60819";

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
					//$partyCount = 2;
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
					
					$validate_result = validateKey1($key1, $userId, $session_validity, 'I', $con);
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
						error_log("db_max_charge_amount = ".$db_max_charge_amount.", request_check_max_total_amount = ".$request_check_max_total_amount);
						$request_check_max_total_amount = floatval($amsCharge) + floatval($agentCharge) + floatval($partner_charge) + floatval($other_charge);
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
							$available_balance = check_agent_available_balance($userId, $con);						
							if ( $available_balance >= 0 || $available_balance != "" || $available_balance != null ) {	
								if ( floatval($totalAmount) <= floatval($available_balance) ) {
									$fin_trans_log_id = generate_seq_num(1600, $con);
									unset($data->key1);
									$request_message = json_encode($data);
									if( $fin_trans_log_id > 0 )  {
										error_log("fin_trans_log_id = ".$fin_trans_log_id);
										$request_message = mysqli_real_escape_string($con, $request_message);
										$fin_trans_log_query = "INSERT INTO fin_trans_log (fin_trans_log_id, fin_service_feature_id, partner_id, party_type, party_code, country_id, state_id, amount, request_message, message_send_time, create_user, create_time) VALUES ($fin_trans_log_id, 1, $partnerId, '$partyType', '$partyCode', $countryId, $stateId, $totalAmount, left('Fund Transfer Request = $request_message', 1000), now(), $userId, now())";
										error_log("fin_trans_log_query = ".$fin_trans_log_query);
										$fin_trans_log_result = mysqli_query($con, $fin_trans_log_query);
										if($fin_trans_log_result ) {
											if ( $txType == "F" ) {
												$fin_request_update_query = "UPDATE fin_request SET service_charge = $amsCharge+$agentCharge, partner_charge = $partnerCharge, other_charge = $otherCharge,  mobile_no = '$mobileNo',  fin_trans_log_id2 = $fin_trans_log_id WHERE fin_request_id = ".$fin_request_id;
											}else {
												$fin_request_update_query = "UPDATE fin_request SET service_charge = $amsCharge, partner_charge = $partnerCharge, other_charge = $otherCharge,  mobile_no = '$mobileNo',  fin_trans_log_id2 = $fin_trans_log_id WHERE fin_request_id = ".$fin_request_id;
											}
											error_log("fin_request_update_query = ".$fin_request_update_query);
											$fin_request_update_result = mysqli_query($con, $fin_request_update_query);		
											if($fin_request_update_result) {																						
												$fin_service_order_no = generate_seq_num(1500, $con);
												if( $fin_service_order_no > 0 ) {
													error_log("Inside Fin Service Order ==> FIN_SERVICE_ORDER_NO ".$fin_service_order_no);
													$acc_trans_type = 'FCHIO';
													$transaction_id = $fin_service_order_no;
													$firstpartycode = $partyCode;
													$firstpartytype = $partyType;
													$secondpartycode = $parentCode;
													if(($secondpartycode == "") || empty($secondpartycode) || ($secondpartycode = null)) {
														$secondpartycode = "";
														$secondpartytype = "";
													}
													else {
														$secondpartytype = substr($secondpartycode,0);
													}
													$glComment = "Deposit Order #".$fin_service_order_no;
													$journal_entry_id = process_glentry($acc_trans_type, $transaction_id, $firstpartycode, $firstpartytype, $secondpartycode, $secondpartytype, $glComment, $totalAmount, $userId, $con);
													if($journal_entry_id > 0) {
														$journal_entry_error = "N";	
														$narration = mysqli_real_escape_string($con, $narration);
														$accountName1 = mysqli_real_escape_string($con, $accountName);
														$new_ams_charge = floatval($amsCharge) - floatval($agentCharge);
														if ( $txType == "F" ) {
															$fin_service_order_query = "INSERT INTO fin_service_order (fin_service_order_no, fin_trans_log_id, service_feature_code, bank_id, user_id, total_amount, request_amount, ams_charge, partner_charge, other_charge, customer_name, mobile_no, comment, date_time, service_feature_config_id, partner_id, stamp_charge, agent_charge) VALUES ($fin_service_order_no, $fin_trans_log_id, 'CIN', $bankId, $userId, $totalAmount, $requestedAmount, $amsCharge, $partnerCharge, $otherCharge, left('$accountName1', 45), '$mobileNo', '$narration', now(), $service_feature_config_id, $partnerId, $stampCharge, $agentCharge)";
														}else {
															$fin_service_order_query = "INSERT INTO fin_service_order (fin_service_order_no, fin_trans_log_id, service_feature_code, bank_id, user_id, total_amount, request_amount, ams_charge, partner_charge, other_charge, customer_name, mobile_no, comment, date_time, service_feature_config_id, partner_id, stamp_charge, agent_charge) VALUES ($fin_service_order_no, $fin_trans_log_id, 'CIN', $bankId, $userId, $totalAmount, $requestedAmount, $new_ams_charge, $partnerCharge, $otherCharge, left('$accountName1', 45), '$mobileNo', '$narration', now(), $service_feature_config_id, $partnerId, $stampCharge, $agentCharge)";
														}	
														error_log("fin_service_order_query = ".$fin_service_order_query);
														$fin_service_order_result = mysqli_query($con, $fin_service_order_query);
														if($fin_service_order_result ) {
															$fin_order_rollback = "N";					
															error_log("inside success fin_service_order table entry");																									
															$get_acc_trans_type = getAcccTransType($acc_trans_type, $con);
															error_log("get_acc_trans_type = ".$get_acc_trans_type);	
															if($get_acc_trans_type != "-1"){
																$split = explode("|",$get_acc_trans_type);
																$ac_factor = $split[0];
																$cb_factor = $split[1];
																$acc_trans_type_id = $split[2];
																$update_wallet = walletupdateWithTransaction($acc_trans_type, $cb_factor, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId, $journal_entry_id);
																		
																if( $update_wallet == 0 ) {	
																	//Inside success wallet update
																	$data = array();
																	$data['accountNumber'] = $accountNumber;
																	$data['accountName'] = $accountName;
																	$data['bankCode'] = $bankCode;
																	$data['bvn'] = $bvn;
																	$data['kycLevel'] = $kycLevel;
																	$data['partnerId'] = $partnerId;
																	$data['sessionId'] = $sessionId;
																	$data['totalAmount'] = $totalAmount;
																	$data['requestedAmount'] = $requestedAmount;
																	$data['naration'] = clean_special_chars($narration);
																	$data['transactionId'] = $fin_request_id;
																	$data['countryId'] = $countryId;
																	$data['stateId'] = $stateId;
																	$data['localGovtId'] = ADMIN_LOCAL_GOVT_ID;
																	$data['userId'] = $userId;
																	$data['mobile'] = $mobileNo;
																	//$data['location'] = "6.4300747,3.4110715";
																	$data['location'] = $location;
																																				
																	$url = FUND_TRANSFER_URL;
																	$tsec = time();
																	$raw_data1 = FINAPI_SERVER_APP_PASSWORD.FINWEB_SERVER_SHORT_NAME."|".FINAPI_SERVER_APP_USERNAME.FINWEB_SERVER_SHORT_NAME."|".$tsec;
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
																	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, NIBSS_CURL_CONNECTION_TIMEOUT);
																	curl_setopt($ch, CURLOPT_TIMEOUT, NIBSS_CURL_TIMEOUT);
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
																				$update_query = "UPDATE fin_request SET status = 'S', comments = '$narration', order_no = $fin_service_order_no, update_time = now(), auth_code = '".$api_response['sessionId']."' WHERE fin_request_id = $fin_request_id ";
																				error_log("update_query1 = ".$update_query);
																				$update_query_result = mysqli_query($con, $update_query);

																				$update_query = "UPDATE fin_service_order SET auth_code = '".$api_response['sessionId']."', reference_no = '".$api_response['paymentReference']."' WHERE fin_service_order_no = $fin_service_order_no";
																				error_log("update_query2 = ".$update_query);
																				$update_result = mysqli_query($con, $update_query);
																						
																				$gl_post_return_value = process_glpost($journal_entry_id, $con);
																				if ( $gl_post_return_value == 0 ) {
																					error_log("Success in cashin gl_post for: ".$journal_entry_id);
																				}
																				else{
																					error_log("Error in gl_post, with gl_post_return_value = ".$gl_post_return_value);
																					insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AP", "W", "N", $totalAmount, $con);
																				}

																				$order_post_result = post_finorder($fin_service_order_no, $con);
																				if ( $order_post_result == 0 ) { 
																					error_log("Success in cashin post_finorder for: ".$fin_service_order_no);
																				}else {
																					error_log("Error in cashin post_finorder for: ".$fin_service_order_no);
																				}
																				$serviceconfig = explode(",", $rateparties_details);
																				$service_insert_count = 0;
																					
																				//Insert into fin_service_order_comm table
																				for($i = 0; $i < sizeof($serviceconfig); $i++) {
																					$cashIn_flag = insertFinanceServiceOrderComm($fin_service_order_no, $serviceconfig[$i], $journal_entry_id, $txType, $agentCharge, $ams_charge, $con);
																					if ( $cashIn_flag == 0 ) {
																						++$service_insert_count;
																					}
																				}
																				if ( $service_insert_count == sizeof($serviceconfig) ) {
																					error_log("All entries for service_order insert in commission table. Insert count = ".$service_insert_count);
																				}else {
																					error_log("Not all entries for service_order insert in commission table. Insert count = ".$service_insert_count.", serviceConfig count = ".sizeof($serviceconfig));
																				}
																				$pcu_result = process_comm_update($fin_service_order_no, $con);
																				if ( $pcu_result > 0 ) {
																					if ( $pcu_result == sizeof($serviceconfig) ) {
																						error_log("All fin_service_order_comm updates are completed. Count = ".$pcu_result);
																					}else {
																						error_log("Warning fin_service_order_comm updates are not matching completed. Count = ".$pcu_result);
																					}
																				}else {
																					error_log("Error in fin_service_order_comm records insert. Insert Count = ".$pcu_result);
																				}
																				$availableBalance = check_party_available_balance($partyType, $userId, $con);

																				$response["result"] = "Success";
																				$response["statusCode"] = $statusCode;																	
																				$response["message"] = "Your Deposit Order# $fin_service_order_no for NGN $requestedAmount submitted.";
																				$response["partnerId"] = $partnerId;
																				$response["sessionId"] = $api_response['sessionId'];
																				$response["paymentReference"] = $api_response['paymentReference'];
																				$response["signature"] = $server_signature;
																				$response["availableBalance"] = $availableBalance;
																				$response["orderNo"] = $fin_service_order_no;
																			}																					
																			else {
																				//Fund Transfer Request responseCode not equal to 0
																				$fin_order_rollback = "Y";
																				$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																				if ( $gl_reverse_repsonse != 0 ) {
																					error_log("Error in Cashin fundtransfer gl_reverse for: ".$journal_entry_id);
																					insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $totalAmount, $con);
																				}else {
																					error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																				}
																						
																				//Rollback wallet update
																				$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId);
																				if ( $update_wallet != 0 ) {
																					error_log("Error in Cashin rollback_wallet for: ".$journal_entry_id);
																					insertaccountrollback($userId, $journal_entry_id, $acc_trans_type, $totalAmount, 2, "F", $con);
																				}else {
																					error_log("Success in Cashin rollback_wallet for: ".$journal_entry_id);
																					//Insert into account_rollback table with success status
																				}
																				$response["result"] = "Error";
																				$response["statusCode"] = $statusCode;
																				$response["partnerId"] = $partnerId;
																				$response["signature"] = $server_signature;
																				$response["message"] = $api_response["responseDescription"];
																			}
																		}
																		else {
																			error_log("inside httpcode != 200");
																			$fin_order_rollback = "Y";
																			$statusCode = $httpcode;
																			$responseDescription = "HTTP Protocol Error";
																			error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																			$update_query = "UPDATE fin_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE fin_trans_log_id = $fin_trans_log_id";
																			error_log("update_query = ".$update_query);
																			$update_query_result = mysqli_query($con, $update_query);
							
																			$approver_comments = "FT: ".$statusCode." - ".$responseDescription;
																			error_log("update_query = ".$update_query);
																			$update_query = "UPDATE fin_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE fin_request_id = $fin_request_id ";
																			$update_query_result = mysqli_query($con, $update_query);
																		
																			$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																			if ( $gl_reverse_repsonse != 0 ) {
																				error_log("Error in Cashin fundtransfer gl_reverse for: ".$journal_entry_id);
																				insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $totalAmount, $con);
																			}else {
																				error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																			}
																						
																			//Rollback wallet update
																			$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId);
																			if ( $update_wallet != 0 ) {
																				error_log("Error in Cashin rollback_wallet for: ".$journal_entry_id);
																				insertaccountrollback($userId, $journal_entry_id, $acc_trans_type, $totalAmount, 2, "F", $con);
																			}else {
																				error_log("Success in Cashin rollback_wallet for: ".$journal_entry_id);
																				//Insert into account_rollback table with success status
																			}

																			$response["statusCode"] = $statusCode;
																			$response["result"] = "Error";
																			$response["message"] = "Error in connection to FinSol API Server";
																			$response["partnerId"] = $partnerId;
																			$response["signature"] = $server_signature;
																		}
																	}
																	else {
																		error_log("curl_error != 0 ");
																		$fin_order_rollback = "Y";
																		$statusCode = $curl_error;
																		$responseDescription = "CURL Execution Error";
																		$approver_comments = "FT ".$statusCode." - ".$responseDescription;
																		error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																		$update_query = "UPDATE fin_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE fin_trans_log_id = $fin_trans_log_id";
																		error_log("update_query = ".$update_query);
																		$update_query_result = mysqli_query($con, $update_query);
				
																		$update_query = "UPDATE fin_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE fin_request_id = $fin_request_id ";
																		error_log("update_query = ".$update_query);
																		$update_query_result = mysqli_query($con, $update_query);

																		$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																		if ( $gl_reverse_repsonse != 0 ) {
																			error_log("Error in Cashin fundtransfer gl_reverse for: ".$journal_entry_id);
																			insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $totalAmount, $con);
																		}else {
																			error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																		}
																						
																		//Rollback wallet update
																		$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId);
																		if ( $update_wallet != 0 ) {
																			error_log("Error in Cashin rollback_wallet for: ".$journal_entry_id);
																			insertaccountrollback($userId, $journal_entry_id, $acc_trans_type, $totalAmount, 2, "F", $con);
																		}else {
																			error_log("Success in Cashin rollback_wallet for: ".$journal_entry_id);
																			//Insert into account_rollback table with success status
																		}

																		$response["statusCode"] = $statusCode;
																		$response["result"] = "Error";
																		$response["message"] = "Error in communication protocol";
																		$response["partnerId"] = $partnerId;
																		$response["signature"] = $server_signature;
																	}
																}
																else {
																	//Inside not success wallet update	
																	$fin_order_rollback = "Y";		
																	error_log("inside not able to update wallet for $fin_service_order_no");
																	$msg = "Error: Deposit [$description] Order $fin_service_order_no due to failure in account update. Contact Kadick Admin.";
																	
																	$journal_reverse_query = "select gl_reverse($journal_entry_id) as gl_reverse_result";
																	$journal_reverse_result = mysqli_query($con, $journal_reverse_query);
																	if ( $journal_reverse_result ) {
																		$journal_reverse_result_row = mysqli_fetch_array($journal_reverse_result);
																		$journal_reverse_result_code = $journal_reverse_result_row['gl_reverse_result'];
																		error_log("journal_reverse_result_code = ".$journal_reverse_result_code);
																		if($journal_reverse_result_code == 0) {
																			//Journal Reverse is success
																			error_log("Journal Reverse is done when account balance update failed for Live Cash-in order no = $fin_service_order_no");
																		}else {
																			//journal_reverse_error, log it in journal_error table
																			$journal_error_query = "insert into journal_error(journal_error_id, branch_id, user_id, user_type, transaction_id, acc_trans_type_code, amount, error_code, error_type, create_date, status) values (0, $branch_id, $user_id, '$journal_user_type', $fin_service_order_no, 'FTFRO', $financeAmount, 'AE', 'S', now(), 'N')";
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
																		$journal_error_query = "insert into journal_error(journal_error_id, branch_id, user_id, user_type, transaction_id, acc_trans_type_code, amount, error_code, error_type, create_date, status) values (0, $branch_id, $user_id, '$journal_user_type', $fin_service_order_no, 'FTFRO', $financeAmount, 'AE', 'S', now(), 'N')";
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
																$fin_order_rollback = "Y";
																$response["statusCode"] = 190;
																$response["result"] = "Error";
																$response["message"] = "Error in getting Acc Transaction Type";
																$response["partnerId"] = $partnerId;
																$response["signature"] = $server_signature;
															}

															if ( $fin_order_rollback == "Y") {
																//Remove Fin. Services Order because of balance update error
																$fin_services_order_delete_query = "delete from fin_service_order where fin_service_order_no = $fin_service_order_no";
																error_log("fin_services_order_delete_query = " . $fin_services_order_delete_query);
																$fin_services_order_delete_result = mysqli_query($con, $fin_services_order_delete_query);
																if ( $fin_services_order_delete_result ) {
																	error_log("fin_service_order delete successful");
																}else {
																	error_log("fin_service_order delete failure = ".mysqli_error());
																}
															}
														}
														else {																				
															//failure - Insert fin_service_order
															$response["result"] = "failure";
															$response["statusCode"] = 200;																	
															$response["message"] = "Error in inserting into Fin. Service Order";
															$response["partnerId"] = $partnerId;
															$response["signature"] = $server_signature;
														}
													}
													else {
														//failure - Create Jounral Entries
														$response["result"] = "failure";
														$response["statusCode"] = 210;																	
														$response["message"] = "Error in creating Journal Entries";
														$response["partnerId"] = $partnerId;
														$response["signature"] = $server_signature;
													}
												}
												else {
													//failure - Create Service Order No
													$response["result"] = "failure";
													$response["statusCode"] = 220;																	
													$response["message"] = "Error in creating Service Order No";
													$response["partnerId"] = $partnerId;
													$response["signature"] = $server_signature;
												}
											}
											else {
												//failure - Update fin_request table
												$response["result"] = "failure";
												$response["statusCode"] = 230;																	
												$response["message"] = "Error in updating Fin Request Table";
												$response["partnerId"] = $partnerId;
												$response["signature"] = $server_signature;
											}
										}
										else {
											//failure - Update fin_trans_log table
											$response["result"] = "failure";
											$response["statusCode"] = 240;																	
											$response["message"] = "Error in updating Fin Transaction Log Table";
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
									//failure - Insufficent Account Balance 
									$response["result"] = "failure";
									$response["statusCode"] = 260;																	
									$response["message"] = "Insufficient Account Balance";
									$response["partnerId"] = $partnerId;
									$response["signature"] = $server_signature;
								}
							}
							else {
								//failure - Error in retrieving Account Balance 
								$response["result"] = "failure";
								$response["statusCode"] = 270;																	
								$response["message"] = "Error in retrieving Account Balance";
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
							$response["statusCode"] = "280";
							$response["result"] = "Error";
							$response["message"] = $resp_message;
							$response["partnerId"] = $partnerId;
							$response["signature"] = $server_signature;
						}
					}
					else {
						//Error - Db total amount and client total amount are diferent
						$response["statusCode"] = "290";
						$response["result"] = "Error";
						$response["message"] = "Failure: Invalid request...";
						$response["partnerId"] = $partnerId;
						$response["signature"] = 0;
					}
				}
				else {
					// Invalid Singature
					$response["statusCode"] = "300";
					$response["result"] = "Error";
					$response["message"] = "Failure: Invalid request";
					$response["partnerId"] = $partnerId;
					$response["signature"] = 0;
				}								
			}
			else {
				// Failure - Invalid Data
				$response["result"] = "failure";
				$response["statusCode"] = "400";
				$response["message"] = "Failure: Invalid Data";
				$response["partnerId"] = 0;
				$response["signature"] = 0;
			}
		}
		else {	
			// Invalid Operation
			$response["statusCode"] = "500";
			$response["result"] = "Error";
			$response["message"] = "Failure: Invalid Operation";
			$response["partnerId"] = 0;
			$response["signature"] = 0;
		}
	}else {
		// Invalid Request Method
		$response["statusCode"] = "600";
		$response["result"] = "Error";
		$response["message"] = "Failure: Invalid Request Method";
		$response["partnerId"] = 0;
		$response["signature"] = 0;	
	}			
		
	// echoing JSON response
	error_log("cashin_fund_transfer ==> ".json_encode($response));
	echo json_encode($response);
	return;

		
function checking_feature_value($userId, $country, $state, $partyCount, $product, $partner, $requestedAmount, $txtype, $con) {
		
	$res = -1;
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

function clean_special_chars($input) {
	return preg_replace('/[^A-Za-z0-9-]/', ' ', $input);
}
					
?>
