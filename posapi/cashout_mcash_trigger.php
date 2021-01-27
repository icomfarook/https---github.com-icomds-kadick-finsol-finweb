<?php
	include('../common/admin/configmysql.php');
	include ("get_prime.php");	
	include ("functions.php");
	require_once ("AesCipher.php");
	error_log("inside pcposapi/cashout_mcash_trigger.php");

	//ERROR_REPORTING(E_ALL);
	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;
	$journal_entry_error = "N";

	//Checking Post Method.
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		error_log("inside post request method");
		$data = json_decode(file_get_contents("php://input"));
		error_log("cashout_mcash_trigger <== ".json_encode($data));

		if( isset($data->operation) && $data->operation == 'CASHOUT_MCASH_TRIGGER') {
			error_log("inside operation == CASHOUT_MCASH_TRIGGER method");

            if ( isset($data->partnerId ) && !empty($data->partnerId) && isset($data->totalAmount) && !empty($data->totalAmount) 
            	&& isset($data->requestAmount) && !empty($data->requestAmount) 	&& isset($data->narration) && !empty($data->narration) 
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
				$narration = $data->narration;
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
				$session_validity = AGENT_SESSION_VALID_TIME;

				if ( $partnerId == 1 ) {
					$txType = "I";
				}else {
					$txType = "E";
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

				//checking  signature 
				if ( $local_signature == $signature ){									
					
					$validate_result = validateKey1($key1, $userId, $session_validity, 'G', $con);
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

					$checking_feature_value_response = checking_feature_value($userId, $countryId, $stateId, $parentCode, $productId, $partnerId, $requestedAmount, $txType, $con);
					$checking_feature_value_response_split = explode("#",$checking_feature_value_response);
					$charges_details = $checking_feature_value_response_split[0];	
					$rateparties_details = $checking_feature_value_response_split[1];										
					$charges_details_split = explode("|",$charges_details);										
					$reponse_feature_value = $charges_details_split[0];	
					$service_feature_config_id = $charges_details_split[1];										
					$ams_charge = $charges_details_split[2];									
					$partner_charge = $charges_details_split[3];									
					$other_charge = $charges_details_split[4];
					$request_check_total_amount = floatval($amsCharge) +  floatval($partnerCharge) + floatval($otherCharge) + floatval($requestedAmount);

					// checkin get_feature_value response code
					if( $reponse_feature_value == 0 &&  (floatval($ams_charge) == floatval($amsCharge) )  
						&& (floatval($partner_charge) == floatval($partnerCharge)) && (floatval($other_charge) == floatval($otherCharge)) 
						&& floatval($request_check_total_amount) == floatval($totalAmount)) {
							
						$fin_trans_log_id = generate_seq_num(1600, $con);
						unset($data->key1);
						$request_message = json_encode($data);
						if( $fin_trans_log_id > 0 )  {
							error_log("fin_trans_log_id = ".$fin_trans_log_id);
							$request_message = mysqli_real_escape_string($con, $request_message);
							$fin_trans_log_query = "INSERT INTO fin_trans_log (fin_trans_log_id, fin_service_feature_id, partner_id, party_type, party_code, country_id, state_id, amount, request_message, message_send_time, create_user, create_time) VALUES ($fin_trans_log_id, 2, $partnerId, '$partyType', '$partyCode', $countryId, $stateId, $totalAmount, left('mCash Trigger Request = $request_message', 1000), now(), $userId, now())";
							error_log("fin_trans_log_query = ".$fin_trans_log_query);
							$fin_trans_log_result = mysqli_query($con, $fin_trans_log_query);
							if($fin_trans_log_result ) {
                                $fin_request_id = generate_seq_num(2800, $con);
                                if($fin_request_id > 0)  {	
									$narration = mysqli_real_escape_string($con, $narration);
									$senderName = mysqli_real_escape_string($con, $senderName);
                                	$fin_request_query = "INSERT INTO fin_request (fin_request_id, fin_trans_log_id1, service_feature_code, country_id, state_id, request_amount, user_id, service_charge, partner_charge, other_charge, total_amount, sender_name, mobile_no, status, comments, create_time) VALUES ($fin_request_id, $fin_trans_log_id, 'COU', $countryId, $stateId, $requestedAmount, $userId, $amsCharge, $partnerCharge, $otherCharge, $totalAmount, '$senderName', '$mobileNo', 'I', '$narration', now())";
                                	error_log("fin_request_query = ".$fin_request_query);
                                	$fin_request_result = mysqli_query($con, $fin_request_query);
                                	if( $fin_request_result ) {
                                		$fin_service_order_no = generate_seq_num(1500, $con);
										if($fin_service_order_no > 0) {
											$fin_service_order_query = "INSERT INTO fin_service_order (fin_service_order_no, fin_trans_log_id, service_feature_code, partner_id, user_id, total_amount, request_amount, ams_charge, partner_charge, other_charge, service_feature_config_id, mobile_no, comment, date_time) VALUES ($fin_service_order_no, $fin_trans_log_id, 'COU', $partnerId, $userId, $totalAmount, $requestedAmount, $amsCharge, $partnerCharge, $otherCharge, $service_feature_config_id, '$mobileNo', '$narration', now())";
											$fin_service_order_result = mysqli_query($con, $fin_service_order_query);
										    	if( $fin_service_order_result ) {
												$data = array();
												$data['msisdn'] = $mobileNo;
												$data['amount'] = $totalAmount;
												$data['branchName'] = 'FinWebPOS';
												$data['partnerId'] = $partnerId;
												$data['mode'] = 'WEB';
												$data['agentCode'] =  $partyCode;
												$data['transactionId'] = $fin_request_id;
												$data['countryId'] = $countryId;
												$data['userId'] = $userId;
												$data['stateId'] = $stateId;
												$data['localGovtId'] = ADMIN_LOCAL_GOVT_ID;
																																				
												$url = LIVE_CASHOUT_MCASH_TRIGGER_URL;
												//$sendreq = sendRequest($data, $url);
												$fin_order_rollback = "N";
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
												curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
												curl_setopt($ch, CURLOPT_TIMEOUT, 25);
												$curl_response = curl_exec($ch);
												$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
												$curl_error = curl_errno($ch);
												curl_close($ch);
                                                
                                                if ( $curl_error == 0 ) {
													error_log("curl_error == 0 ");
													error_log("response received <=== ".$curl_response);
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
                                                        if(!$update_query_result ) {
                                                        	error_log("update_query_result Error: ".$updatequery." - ".mysqli_error($con));
                                                        }

														if($statusCode == 0) {
															error_log("inside statusCode == 0");
															$short_code = $api_response['recoveryShortcode'];
															$operation_id = $api_response['operationId'];
                                                            
															$update_query = "UPDATE fin_request SET status = 'G', order_no = $fin_service_order_no, update_time = now(), auth_code = '".$operation_id."' WHERE fin_request_id = $fin_request_id ";
															error_log("update_query1 = ".$update_query);
															$update_query_result = mysqli_query($con, $update_query);
															if(!$update_query_result ) {
																error_log("update_query_result Error: ".$updatequery." - ".mysqli_error($con));
															}

                                                            $update_query = "UPDATE fin_service_order SET auth_code = '".$operation_id."', reference_no = '".$short_code."' WHERE fin_service_order_no = $fin_service_order_no";
															error_log("update_query2 = ".$update_query);
                                                            $update_query_result = mysqli_query($con, $update_query);
                                                            if(!$update_query_result ) {
                                                            	error_log("update_query_result Error: ".$updatequery." - ".mysqli_error($con));
                                                            }
															
                                                            $response["result"] = "Success";
															$response["statusCode"] = $statusCode;																	
															$response["message"] = "Your Cash-Out Order# $fin_service_order_no for NGN $requestedAmount submitted.";
															$response["partnerId"] = $partnerId;
															$response["shortCode"] = $api_response['recoveryShortcode'];
															$response["operationId"] = $api_response['operationId'];
															$response["transactionId"] = $fin_service_order_no;
                                                   			$response["signature"] = $server_signature;
														}																					
														else {
                                                            //mCash Trigger Request responseCode not equal to 0
                                                            error_log("inside statusCode != 0");
                                                            $fin_order_rollback = "Y";
                                                            $approver_comments = "MT: ".$sendreq['responseCode']." - ".$sendreq['responseDescription']." @ ".$sendreq['processingStartTime'];
											                $updatequery = "UPDATE fin_request SET status = 'E', update_time = now(), order_no = $fin_service_order_no, approver_comments = '".$approver_comments."' WHERE fin_request_id = $fin_req_id";
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
														error_log("inside httpcode != 200");
														$fin_order_rollback = "Y";
														$statusCode = $httpcode;
														$responseDescription = "HTTP Protocol Error";
														error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
														$update_query = "UPDATE fin_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE fin_trans_log_id = $fin_trans_log_id";
														error_log("update_query = ".$update_query);
                                                        $update_query_result = mysqli_query($con, $update_query);
                                                        if(!$update_query_result ) {
                                                        	error_log("update_query_result Error: ".$updatequery." - ".mysqli_error($con));
                                                        }
														
														$approver_comments = "MT: ".$statusCode." - ".$responseDescription." @ ".$sendreq['processingStartTime'];;
														error_log("update_query = ".$update_query);
														$update_query = "UPDATE fin_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE fin_request_id = $fin_request_id ";
                                                        $update_query_result = mysqli_query($con, $update_query);
                                                        if(!$update_query_result ) {
                                                        	error_log("update_query_result Error: ".$updatequery." - ".mysqli_error($con));
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
													$approver_comments = "MT ".$statusCode." - ".$responseDescription." @ ".$response['processingStartTime'];;
													error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
													$update_query = "UPDATE fin_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE fin_trans_log_id = $fin_trans_log_id";
													error_log("update_query = ".$update_query);
                                                    $update_query_result = mysqli_query($con, $update_query);
                                                    if(!$update_query_result ) {
                                                    	error_log("update_query_result Error: ".$updatequery." - ".mysqli_error($con));
                                                    }
				
													$update_query = "UPDATE fin_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE fin_request_id = $fin_request_id ";
													error_log("update_query = ".$update_query);
                                                    $update_query_result = mysqli_query($con, $update_query);
                                                    if(!$update_query_result ) {
                                                    	error_log("update_query_result Error: ".$updatequery." - ".mysqli_error($con));
                                                    }

													$response["statusCode"] = $statusCode;
													$response["result"] = "Error";
													$response["message"] = "Error in communication protocol";
													$response["partnerId"] = $partnerId;
													$response["signature"] = $server_signature;
												}
												if ( $fin_order_rollback == "Y") {
													//Remove Fin. Services Order because of balance update error
													$fin_services_order_delete_query = "delete from fin_service_order where fin_service_order_no = $fin_service_order_no";
													error_log("fin_services_order_delete_query = " . $fin_services_order_delete_query);
													$fin_services_order_delete_result = mysqli_query($con, $fin_services_order_delete_query);
													if ( $fin_services_order_delete_result ) {
														error_log("fin_service_order delete successful for fin_service_order_no = ".$fin_service_order_no);
													}else {
														error_log("fin_service_order delete failure = ".mysqli_error($con)."for fin_service_order_no = ".$fin_service_order_no);
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
   	error_log("cashout_mcash_trigger ==> ".json_encode($response));
	echo json_encode($response);
	return;			
		
function checking_feature_value($userId,$country, $state, $parentcode, $product, $partner, $requestedAmount, $txtype, $con) {
		
	$res = -1;
	if($parentcode == "") {
		$partyCount = 2;
	}
	else {
		$partyCount = 3;
	}
	$query = "SELECT get_feature_value($country, $state, null, $product, $partner, $requestedAmount, '$txtype', $partyCount, null, null, $userId, -1) as res";
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
