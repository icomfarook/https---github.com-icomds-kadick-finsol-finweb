<?php
	include('../common/admin/configmysql.php');
	include ("get_prime.php");	
	include ("functions.php");
	require_once ("AesCipher.php");
	error_log("inside pcposapi/cashout_phone_trigger.php");

	//ERROR_REPORTING(E_ALL);
	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;
	$journal_entry_error = "N";

	//Checking Post Method.
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		error_log("inside post request method");
		$data = json_decode(file_get_contents("php://input"));
		error_log("cashout_mpos_trigger <== ".json_encode($data));

		if( isset($data->operation) && $data->operation == 'CASHOUT_PHONE_TRIGGER') {
			error_log("inside operation == CASHOUT_PHONE_TRIGGER method");

            		if ( isset($data->partnerId ) && !empty($data->partnerId) && isset($data->totalAmount) && !empty($data->totalAmount) 
                		&& isset($data->requestAmount) && !empty($data->requestAmount) 
                		&& isset($data->mobileNo) && !empty($data->mobileNo)  
		   		&& isset($data->countryId) && !empty($data->countryId) && isset($data->partyCode) && !empty($data->partyCode) 
				&& isset($data->partyType) && !empty($data->partyType) && isset($data->productId) && !empty($data->productId)
				&& isset($data->userId) && !empty($data->userId) && isset($data->signature) && !empty($data->signature)
                		&& isset($data->key1) && !empty($data->key1) && isset($data->amsCharge) && !empty($data->amsCharge)
				&& isset($data->stateId) && !empty($data->stateId) && isset($data->senderName) && !empty($data->senderName)
	  		) {
	  			ini_set('max_execution_time', 110);
				set_time_limit(110);
				error_log("inside all inputs are set correctly");	
				$partnerId = $data->partnerId;
				$requestAmount = $data->requestAmount;
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
				$session_validity = AGENT_SESSION_VALID_TIME;
				$acc_trans_type = "PAYMT";
				$bank = $data->bank;
				
				if (isset($data->parentCode) && !empty($data->parentCode) ) {
					if ( substr($parentCode, 0, 1) == 'C') {
						$parentType = "C";
					}else {
						$parentType = "A";
					}
				}
				else {
					$parentType = "";
				}
				
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
					
					$daily_check_result = checkDailyLimit($userId, $requestAmount, $con);
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
					$checking_feature_value_response = checking_feature_value($userId, $countryId, $stateId, $partyCount, $productId, $partnerId, $requestAmount, $txType, $con);
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
						$request_check_total_amount = floatval($amsCharge) +  floatval($partnerCharge) + floatval($otherCharge) + floatval($requestAmount);
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
							$fin_request_id = generate_seq_num(2800, $con);
							$fin_service_order_no = generate_seq_num(1500, $con);
							unset($data->key1);
							$requestData = array();
							$requestData['description'] = "Kadick Cash-Out# ".$fin_service_order_no;
							$requestData['totalAmount'] = $totalAmount;
							$requestData['requestAmount'] = $requestAmount;
							$requestData['mobile'] = $mobileNo;
							$requestData['name'] = $senderName;
							$requestData['signature'] = $signature;
							$requestData['countryId'] = $countryId;
							$requestData['stateId'] = $stateId;
							$requestData['localGovtId'] = ADMIN_LOCAL_GOVT_ID;
							$requestData['userId'] = $userId;
							$url = FINAPI_SERVER_CASHOUT_PHONE_URL;
							$tsec = time();
							$raw_data1 = FINAPI_FWBANK_SERVER_APP_PASSWORD.FINWEB_FWBANK_SERVER_SHORT_NAME."|".FINAPI_FWBANK_SERVER_APP_USERNAME.FINWEB_FWBANK_SERVER_SHORT_NAME."|".$tsec;
							error_log("raw_data1 = ".$raw_data1);
							$key1 = base64_encode($raw_data1);
							$requestData['key1'] = $key1;

							$request_message = json_encode($requestData);
							if( $fin_trans_log_id > 0 )  {
								error_log("fin_trans_log_id = ".$fin_trans_log_id);
								$request_message = mysqli_real_escape_string($con, $request_message);
								$fin_trans_log_query = "INSERT INTO fin_trans_log (fin_trans_log_id, fin_service_feature_id, partner_id, party_type, party_code, country_id, state_id, amount, request_message, message_send_time, create_user, create_time) VALUES ($fin_trans_log_id, $productId, $partnerId, '$partyType', '$partyCode', $countryId, $stateId, $totalAmount, left('Cashout Phone Request = $request_message', 1000), now(), $userId, now())";
								error_log("fin_trans_log_query = ".$fin_trans_log_query);
								$fin_trans_log_result = mysqli_query($con, $fin_trans_log_query);
								if($fin_trans_log_result ) {
									if($fin_request_id > 0)  {
										$senderName = mysqli_real_escape_string($con, $senderName);
										if ( $txType == "F" ) {
											$newAmsCharge = $amsCharge + $agentCharge;
											$fin_request_query = "INSERT INTO fin_request (fin_request_id, fin_trans_log_id1, service_feature_code, country_id, state_id, request_amount, user_id, service_charge, partner_charge, other_charge, total_amount, sender_name, mobile_no, status, create_time, order_no, bank_id) VALUES ($fin_request_id, $fin_trans_log_id, 'COP', $countryId, $stateId, $requestAmount, $userId, $newAmsCharge, $partnerCharge, $otherCharge, $totalAmount, '$senderName', '$mobileNo', 'I', now(), $fin_service_order_no, $bank->id)";
										}else {
											$fin_request_query = "INSERT INTO fin_request (fin_request_id, fin_trans_log_id1, service_feature_code, country_id, state_id, request_amount, user_id, service_charge, partner_charge, other_charge, total_amount, sender_name, mobile_no, status, create_time, order_no, bank_id) VALUES ($fin_request_id, $fin_trans_log_id, 'COP', $countryId, $stateId, $requestAmount, $userId, $amsCharge, $partnerCharge, $otherCharge, $totalAmount, '$senderName', '$mobileNo', 'I', now(), $fin_service_order_no, $bank->id)";
										}
										error_log("fin_request_query = ".$fin_request_query);
										$fin_request_result = mysqli_query($con, $fin_request_query);
										if( $fin_request_result ) {

											$ch = curl_init($url);
											curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
											curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
											curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
											curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, PAYATTITUDE_CURL_CONNECTION_TIMEOUT);
											curl_setopt($ch, CURLOPT_TIMEOUT, PAYATTITUDE_CURL_TIMEOUT);
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
														
														$update_query = "UPDATE fin_request SET status = 'S', order_no = ".$fin_service_order_no.", approver_comments = '".$api_response['description']."', rrn = '".$api_response['orderId']."', auth_code = '".$api_response['transactionTime']."', comments = '".$api_response['status']."', account_no = '".$api_response['statusDescription']."', update_time = now() WHERE fin_request_id = $fin_request_id ";
														error_log("update_query = ".$update_query);
														$update_query_result = mysqli_query($con, $update_query);

														if($fin_service_order_no > 0) {
															$new_ams_charge = floatval($amsCharge) - floatval($agentCharge);
															if ( $txType == "F" ) {
																$fin_service_order_query = "INSERT INTO fin_service_order (fin_service_order_no, fin_trans_log_id, service_feature_code, partner_id, user_id, total_amount, request_amount, ams_charge, partner_charge, other_charge, service_feature_config_id, mobile_no, date_time, stamp_charge, agent_charge, reference_no, auth_code, comment, bank_id) VALUES ($fin_service_order_no, $fin_trans_log_id, 'COP', $partnerId, $userId, $totalAmount, $requestAmount, $amsCharge, $partnerCharge, $otherCharge, $service_feature_config_id, '$mobileNo', now(), $stampCharge, $agentCharge, '".$api_response['orderId']."', '".$api_response['transactionTime']."', '".$api_response['statusDescription']."', $bank->id)";
															}else {
																$fin_service_order_query = "INSERT INTO fin_service_order (fin_service_order_no, fin_trans_log_id, service_feature_code, partner_id, user_id, total_amount, request_amount, ams_charge, partner_charge, other_charge, service_feature_config_id, mobile_no, date_time, stamp_charge, agent_charge, reference_no, auth_code, comment, bank_id) VALUES ($fin_service_order_no, $fin_trans_log_id, 'COP', $partnerId, $userId, $totalAmount, $requestAmount, $new_ams_charge, $partnerCharge, $otherCharge, $service_feature_config_id, '$mobileNo', now(), $stampCharge, $agentCharge, '".$api_response['orderId']."', '".$api_response['transactionTime']."', '".$api_response['statusDescription']."', $bank->id)";
															}
															error_log("fin_service_order_query = ".$fin_service_order_query);
															$fin_service_order_result = mysqli_query($con, $fin_service_order_query);
															if( $fin_service_order_result ) {
																$glComment = "Cash-Out Phone Order #".$fin_service_order_no;
																$journal_entry_id = process_glentry($acc_trans_type, $fin_service_order_no, $partyCode, $partyType, $parentCode, $parentType, $glComment, $requestAmount, $userId, $con);
																error_log("select_journal_entry = ".$journal_entry_id);
																if($journal_entry_id > 0) {
																	$journal_entry_error = "N";
																	$get_acc_trans_type = getAcccTransType($acc_trans_type, $con);
																	error_log("get_acc_trans_type = ".$get_acc_trans_type);
																	if($get_acc_trans_type != "-1"){
																		$split = explode("|", $get_acc_trans_type);
																		$ac_factor = $split[0];
																		$cb_factor = $split[1];
																		$acc_trans_type_id = $split[2];
																		$update_wallet = walletupdateWithTransaction($acc_trans_type, $cb_factor, $partyType, $partyCode, $requestAmount, $con, $userId, $journal_entry_id);
																		if($update_wallet == 0) {
																			$reference_no = "COP-".$acc_trans_type."#".$fin_service_order_no;
																			$p_receipt_id = generate_seq_num(1100, $con);
																			$insertDepositQuery = "INSERT INTO payment_receipt(p_receipt_id, country_id, payment_date, party_code, party_type, payment_reference_no, payment_type, payment_amount, payment_approved_amount, payment_approved_date, payment_source, payment_status, create_user, create_time, comments, approver_comments) VALUES($p_receipt_id, $countryId, now(), '$partyCode', '$partyType', '$reference_no', 'CP', $requestAmount, $requestAmount, now(), 'C', 'A', $userId, now(), '$glComment', 'Cash-out Auto Approved by System')";
																			error_log("payment_receipt insert_query: ".$insertDepositQuery);
																			$insertDepositResult = mysqli_query($con, $insertDepositQuery);
																			if ( !$insertDepositResult ) {
																				error_log("Error in inserting payment receipt for fin_service_order_no = ".$fin_service_order_no);
																			}
																			else {
																				error_log("Success in inserting payment receipt for fin_service_ocer_no = ".$fin_service_order_no);
																			}
																			$gl_post_return_value = process_glpost($journal_entry_id, $con);
																			if ( $gl_post_return_value == 0 ) {
																			    error_log("Success in cashout phone gl_post for: ".$journal_entry_id.", fin_service_order_no = ".$fin_service_order_no);
																			}else {
																			    insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AP", "W", "N", $requestAmount, $con);
																			    error_log("Error in cashout phone gl_post for: ".$journal_entry_id.", fin_service_order_no = ".$fin_service_order_no);
																			}

																			$order_post_result = post_finorder($fin_service_order_no, $con);
																			if ( $order_post_result == 0 ) { 
																			    error_log("Success in cashout phone post_finorder for: ".$fin_service_order_no);
																			}else {
																			    error_log("Error in cashout phone post_finorder for: ".$fin_service_order_no);
																			}
																			
																			//$check_feature_value_result = check_feature_value($userId, $countryId, $stateId, $partyCount, $productId, $partnerId, $requestAmount, $txType, $con);
																			//error_log("check_feature_value_result = ".$check_feature_value_result);
																			//$check_feature_value_result_split = explode("#",$check_feature_value_result);
																			//$charges_details = $check_feature_value_result_split[0];
																			//$charges_details_split = explode("|",$charges_details);
																			//$ams_charge = $charges_details_split[2];
																			error_log("ams_charge = ".$ams_charge);
																			//$rateparties_details = $check_feature_value_result_split[1];
																			error_log("rateparties_details = ".$rateparties_details);
																			$serviceconfig = explode(",", $rateparties_details);
																			$service_insert_count = 0;

																			//Insert into fin_service_order_comm table
																			for($i = 0; $i < sizeof($serviceconfig); $i++) {
																			    $cashOut_flag = insertFinanceServiceOrderComm($fin_service_order_no, $serviceconfig[$i], $journal_entry_id, $txType, $agentCharge, $ams_charge, $con);
																			    if ( $cashOut_flag == 0 ) {
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

																			$new_available_balance = check_party_available_balance($partyType, $userId, $con);
																			error_log("new_available_balance for userId [".$userId."] = ".$new_available_balance);

																			$response["result"] = "Success";
																			$response["statusCode"] = 0;
																			$response["orderNo"] = $fin_service_order_no;
																			$response["transactionId"] = $api_response['orderId'];
																			$response["amount"] = $api_response['amount'];
																			$response["description"] = $api_response['description'];
																			$response["currency"] = $api_response['currency'];
																			$response["status"] = $api_response['status'];
																			$response["pan"] = $api_response['pan'];
																			$response["transactionTime"] = $api_response['transactionTime'];
																			$response["statusDescription"] = $api_response['statusDescription'];
																			$response["signature"] = $server_signature;
																			$response["partnerId"] = $partnerId;
																			$response["newAvailableBalance"] = $new_available_balance;
																			$response["message"] = "CashOut Phone order confirmed for Order # ".$fin_service_order_no;
																		}
																		else {
																			$response["result"] = "Failure";
																			$response["statusCode"] = 200;
																			$response["signature"] = $server_signature;
																			$response["partnerId"] = $partnerId;
																			$response["newAvailableBalance"] = $new_available_balance;
																			$response["message"] = "Error in updating wallet for CashOut Phone order # ".$fin_service_order_no;
																		}
																	}
																	else {
																		$response["result"] = "Failure";
																		$response["statusCode"] = 210;
																		$response["signature"] = $server_signature;
																		$response["newAvailableBalance"] = $new_available_balance;
																		$response["partnerId"] = $partnerId;
																		$response["signature"] = $server_signature;
																		$response["message"] = "Error in getting acc_trans_typet for CashOut Phone order # ".$fin_service_order_no;
																	}
																}
																else {
																	$response["result"] = "Failure";
																	$response["statusCode"] = 220;
																	$response["partnerId"] = $partnerId;
																	$response["signature"] = $server_signature;
																	$response["message"] = "Error in jounral_entry for CashOut (Card) order # ".$fin_service_order_no;
																}
															}
															else {
																$response["result"] = "Failure";
																$response["statusCode"] = 230;																	
																$response["message"] = "Error in fin_service_order for CashOut Phone order # ".$fin_service_order_no;
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
														$approver_comments = "PT: ".$api_response['status']." - ".$api_response['statusDescritpion']." @ ".$api_response['transactionTime'];
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

													$approver_comments = "PT: ".$statusCode." - ".$responseDescription;
													error_log("update_query = ".$update_query);
													$update_query = "UPDATE fin_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE fin_request_id = $fin_request_id ";
													$update_query_result = mysqli_query($con, $update_query);

													$response["statusCode"] = $statusCode;
													$response["result"] = "Error";
													$response["message"] = "Error in connection to PayAttitude API Server";
													$response["partnerId"] = $partnerId;
													$response["signature"] = $server_signature;
												}
											}else {
												error_log("curl_error != 0 ");
												$statusCode = $curl_error;
												$responseDescription = "CURL Execution Error";
												$approver_comments = "NE: ".$statusCode." - ".$responseDescription;
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
								$response["message"] = "Error in creating fin_trans_log_id for CashOut Phone";
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
						$response["statusCode"] = 300;
						$response["result"] = "Error";
						$response["message"] = "Failure: Invalid request...";
						$response["partnerId"] = $partnerId;
						$response["signature"] = 0;
					}
				}
				else {
					// Invalid Singature
					$response["statusCode"] = 310;
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
		else if(isset($data -> operation) && $data -> operation == 'CASHOUT_PHONE_BANK_LIST') {
			error_log("inside operation == CASHOUT_PHONE_BANK_LIST method");
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
		                	$select_phone_bank_query = "select m.bank_master_id, m.name from bank_master m, cashout_phone_bank p where m.bank_master_id = p.bank_master_id and p.active = 'Y' and m.active = 'Y' and (start_date is null or current_date >= start_date) and (expiry_date is null or current_date() <= expiry_date) order by m.name";
		                	error_log("select_ussd_bank_query = ".$select_phone_bank_query);
		                	$select_phone_bank_result = mysqli_query($con, $select_phone_bank_query);
		                   	$response["phoneBanks"] = array();
					if ( $select_phone_bank_result ) {
						while($select_phone_bank_row = mysqli_fetch_assoc($select_phone_bank_result)) {
						    	$phoneBank = array();
						    	$phoneBank['id'] = $select_phone_bank_row['bank_master_id'];
						    	$phoneBank['name'] = $select_phone_bank_row['name'];
						    	array_push($response["phoneBanks"], $phoneBank);
						}
		                        	$response["result"] = "Success";
		                        	$response["message"] = "Your request is processed successfuly";
		                        	$response["statusCode"] = 0;
		                        	$response["signature"] = $server_signature;
					}
		                	else {
		                	       	$response["result"] = "Error";
		                	       	$response["message"] = "Error in finding your Phone Bank list";
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
   	error_log("cashout_phone_trigger ==> ".json_encode($response));
	echo json_encode($response);
	return;			
		
function checking_feature_value($userId, $country, $state, $partyCount, $product, $partner, $requestAmount, $txtype, $con) {
		
	$query = "SELECT get_feature_value_new($country, $state, null, $product, $partner, $requestAmount, '$txtype', $partyCount, null, null, $userId, -1) as res";
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
