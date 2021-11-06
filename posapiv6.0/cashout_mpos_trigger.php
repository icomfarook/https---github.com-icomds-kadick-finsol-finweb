<?php
	include('../common/admin/configmysql.php');
	include ("get_prime.php");	
	include ("functions.php");
	require_once ("AesCipher.php");
	error_log("inside pcposapi/cashout_mpos_trigger.php");

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

		if( isset($data->operation) && $data->operation == 'CASHOUT_MPOS_TRIGGER') {
			error_log("inside operation == CASHOUT_MPOS_TRIGGER method");

            		if ( isset($data->partnerId ) && !empty($data->partnerId) && isset($data->totalAmount) && !empty($data->totalAmount) 
                		&& isset($data->requestAmount) && !empty($data->requestAmount) 	&& isset($data->cardType) && !empty($data->cardType) 
                		&& isset($data->mobileNo) && !empty($data->mobileNo)  
		   		&& isset($data->countryId) && !empty($data->countryId) && isset($data->partyCode) && !empty($data->partyCode) 
				&& isset($data->partyType) && !empty($data->partyType) && isset($data->productId) && !empty($data->productId)
				&& isset($data->userId) && !empty($data->userId) && isset($data->signature) && !empty($data->signature)
                		&& isset($data->key1) && !empty($data->key1) && isset($data->amsCharge) && !empty($data->amsCharge)
				&& isset($data->stateId) && !empty($data->stateId) && isset($data->senderName) && !empty($data->senderName)
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
							
						$fin_trans_log_id = generate_seq_num(1600, $con);
						unset($data->key1);
						$request_message = json_encode($data);
						if( $fin_trans_log_id > 0 )  {
							error_log("fin_trans_log_id = ".$fin_trans_log_id);
							$request_message = mysqli_real_escape_string($con, $request_message);
							$fin_trans_log_query = "INSERT INTO fin_trans_log (fin_trans_log_id, fin_service_feature_id, partner_id, party_type, party_code, country_id, state_id, amount, request_message, message_send_time, create_user, create_time) VALUES ($fin_trans_log_id, 2, $partnerId, '$partyType', '$partyCode', $countryId, $stateId, $totalAmount, left('mPos Trigger Request = $request_message', 1000), now(), $userId, now())";
							error_log("fin_trans_log_query = ".$fin_trans_log_query);
							$fin_trans_log_result = mysqli_query($con, $fin_trans_log_query);
							if($fin_trans_log_result ) {
                                				$fin_request_id = generate_seq_num(2800, $con);
                                				if($fin_request_id > 0)  {
									$senderName = mysqli_real_escape_string($con, $senderName);
									if ( $txType == "F" ) {
										$newAmsCharge = $amsCharge + $agentCharge;
                                						$fin_request_query = "INSERT INTO fin_request (fin_request_id, fin_trans_log_id1, service_feature_code, country_id, state_id, request_amount, user_id, service_charge, partner_charge, other_charge, total_amount, sender_name, mobile_no, status, comments, create_time) VALUES ($fin_request_id, $fin_trans_log_id, 'MP0', $countryId, $stateId, $requestedAmount, $userId, $newAmsCharge, $partnerCharge, $otherCharge, $totalAmount, '$senderName', '$mobileNo', 'I', 'Card Type: $cardType', now())";
                                					}else {
                                						$fin_request_query = "INSERT INTO fin_request (fin_request_id, fin_trans_log_id1, service_feature_code, country_id, state_id, request_amount, user_id, service_charge, partner_charge, other_charge, total_amount, sender_name, mobile_no, status, comments, create_time) VALUES ($fin_request_id, $fin_trans_log_id, 'MP0', $countryId, $stateId, $requestedAmount, $userId, $amsCharge, $partnerCharge, $otherCharge, $totalAmount, '$senderName', '$mobileNo', 'I', 'Card Type: $cardType', now())";
                                					}
                                					error_log("fin_request_query = ".$fin_request_query);
                                					$fin_request_result = mysqli_query($con, $fin_request_query);
                                					if( $fin_request_result ) {
                                						$fin_service_order_no = generate_seq_num(1500, $con);
										if($fin_service_order_no > 0) {
											$new_ams_charge = floatval($amsCharge) - floatval($agentCharge);
											if ( $txType == "F" ) {
												$fin_service_order_query = "INSERT INTO fin_service_order (fin_service_order_no, fin_trans_log_id, service_feature_code, partner_id, user_id, total_amount, request_amount, ams_charge, partner_charge, other_charge, service_feature_config_id, mobile_no, comment, date_time, stamp_charge, agent_charge) VALUES ($fin_service_order_no, $fin_trans_log_id, 'MP0',$partnerId, $userId, $totalAmount, $requestedAmount, $amsCharge, $partnerCharge, $otherCharge, $service_feature_config_id, '$mobileNo', 'Card Type: $cardType', now(), $stampCharge, $agentCharge)";
											}else {
												$fin_service_order_query = "INSERT INTO fin_service_order (fin_service_order_no, fin_trans_log_id, service_feature_code, partner_id, user_id, total_amount, request_amount, ams_charge, partner_charge, other_charge, service_feature_config_id, mobile_no, comment, date_time, stamp_charge, agent_charge) VALUES ($fin_service_order_no, $fin_trans_log_id, 'MP0',$partnerId, $userId, $totalAmount, $requestedAmount, $new_ams_charge, $partnerCharge, $otherCharge, $service_feature_config_id, '$mobileNo', 'Card Type: $cardType', now(), $stampCharge, $agentCharge)";
											}
											error_log("fin_service_order_query = ".$fin_service_order_query);
											$fin_service_order_result = mysqli_query($con, $fin_service_order_query);
											    if( $fin_service_order_result ) {
												$update_query = "UPDATE fin_request SET status = 'G', order_no = $fin_service_order_no, update_time = now() WHERE fin_request_id = $fin_request_id ";
												error_log("update_query1 = ".$update_query);
												$update_query_result = mysqli_query($con, $update_query);
												if(!$update_query_result ) {
													error_log("update_query_result Error: ".$updatequery." - ".mysqli_error($con));
												}
												$response["statusCode"] = "0";		
												$response["result"] = "Success";															
												$response["message"] = "Your mPos Cash-Out Order# $fin_service_order_no for NGN $requestedAmount is about to be triggered.";
												$response["partnerId"] = $partnerId;
												$response["orderNo"] = $fin_service_order_no;
												$response["transactionId"] = $fin_request_id;
                                                						$response["signature"] = $server_signature;
											}																					
											else {
                                                						$approver_comments = "PT: Error in updating fin_request table";
											    	$updatequery = "UPDATE fin_request SET status = 'E', update_time = now(), order_no = $fin_service_order_no, approver_comments = '".$approver_comments."' WHERE fin_request_id = $fin_request_id";
											    	$update_query_result = mysql_query($updatequery);
											    	if(!$update_query_result ) {
												   	error_log("update_query_result Error: ".$updatequery." - ".mysqli_error($con));
												}
												$response["result"] = "Error";
												$response["statusCode"] = $statusCode;
												$response["partnerId"] = $partnerId;
												$response["signature"] = $server_signature;
												$response["message"] = $approver_comments;
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
                                    					//failure - Creating fin_request Order no table
									$response["result"] = "failure";
									$response["statusCode"] = 240;																	
									$response["message"] = "Error in creating order for Fin Transaction Request Table";
									$response["partnerId"] = $partnerId;
                                    					$response["signature"] = $server_signature;
                                				}
                            				}
                            				else {
    	    							//failure - Update fin_trans_log table
	    							$response["result"] = "failure";
								$response["statusCode"] = 250;																	
								$response["message"] = "Error in updating Fin Transaction Log Table";
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
   	error_log("cashout_mpos_trigger ==> ".json_encode($response));
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
