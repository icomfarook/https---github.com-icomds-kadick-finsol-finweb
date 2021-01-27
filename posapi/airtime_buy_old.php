<?php
	include('../common/admin/configmysql.php');
	include ("get_prime.php");	
	include ("functions.php");
	require_once ("AesCipher.php");
	error_log("inside pcposapi/airtime_buy.php");

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

		if( isset($data->operation) && $data->operation == 'AIRTIME_BUY') {
			error_log("inside operation == AIRTIME_BUY method");

            if (  isset($data->requestAmount) && !empty($data->requestAmount) && isset($data->operatorCode) && !empty($data->operatorCode)
                && isset($data->operatorId) && !empty($data->operatorId) && isset($data->mobile) && !empty($data->mobile)
                && isset($data->operatorPlanId) && !empty($data->operatorPlanId) && isset($data->totalAmount) && !empty($data->totalAmount)
                && isset($data->reMobile) && !empty($data->reMobile) && isset($data->countryId) && !empty($data->countryId) 
                && isset($data->partyCode) && !empty($data->partyCode) && isset($data->partyType) && !empty($data->partyType) 
                && isset($data->productId) && !empty($data->productId) && isset($data->userId) && !empty($data->userId) 
                && isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
			    && isset($data->stateId) && !empty($data->stateId)
	  		) {
				error_log("inside all inputs are set correctly");	
				$partnerId = 0;
                $requestedAmount = $data->requestAmount;
                $totalAmount = $data->totalAmount;
                $serviceCharge = 0;
				$partyCode = $data->partyCode;
				$partyType =  $data->partyType;
				$parentCode = $data->parentCode;
				$productId = $data->productId;	
				$userId = $data->userId;
				$signature= $data->signature;
				$key1 = $data->key1;								
                $operatorId = $data->operatorId;
                $operatorCode = $data->operatorCode;
                $operatorPlanId = $data->operatorPlanId;
				$countryId = $data->countryId;
				$stateId = $data->stateId;								 
                $mobile = $data->mobile;
                $reMobile = $data->reMobile;
                $amsCharge = 0;
                $partnerCharge = 0;
                $otherCharge = 0;	

				error_log("signature = ".$signature.", key1 = ".$key1);
				date_default_timezone_set('Africa/Lagos');
				$nday = date('z')+1;
				$nyear = date('Y');
				//error_log( "nday = ".$nday);
				//error_log( "nyear = ".$nyear);
				$nth_day_prime = get_prime($nday);
				$nth_year_day_prime = get_prime($nday+$nyear);
				//error_log("nth_day_prime = ".$nth_day_prime);
				//error_log("nth_year_day_prime = ".$nth_year_day_prime);
				$local_signature = $nday + $nth_day_prime;
				error_log("local_signature = ".$local_signature);
				$server_signature = $nth_year_day_prime + $nday + $nyear;
				error_log("server_signature = ".$server_signature);

				//checking  signature 
				if ( $local_signature == $signature ){									
							
					$agent_info_wallet_status = check_agent_info_wallet_status($partyType, $partyCode, $con);	
					if ($agent_info_wallet_status == 0 ) {
						$available_balance = check_agent_available_balance($userId, $con);						
						if ( $available_balance >= 0 || $available_balance != "" || $available_balance != null ) {	
							if ( floatval($totalAmount) <= floatval($available_balance) ) {
								$evd_trans_log_id = generate_seq_num(1300, $con);
								$request_message = json_encode($data);
								if( $evd_trans_log_id > 0 )  {
									error_log("evd_trans_log_id = ".$evd_trans_log_id);
									$evd_trans_log_query = "INSERT INTO evd_trans_log (evd_trans_log_id, party_type, party_code, country_id, state_id, request_message, message_send_time, create_user, create_time) VALUES ($evd_trans_log_id, '$partyType', '$partyCode', $countryId, $stateId, left('EVD Flexi Request = $request_message', 600), now(), $userId, now())";
									error_log("evd_trans_log_query = ".$evd_trans_log_query);
									$evd_trans_log_result = mysqli_query($con, $evd_trans_log_query);
									if($evd_trans_log_result ) {
										$evd_transaction_id = generate_seq_num(1400, $con);
										if( $evd_transaction_id > 0 ) {
											error_log("Inside EVD Transaction ==> evd_transaction_id ".$evd_transaction_id);
											$acc_trans_type = 'SALE0';
											$transaction_id = $evd_transaction_id;
											$firstpartycode = $partyCode;
											$firstpartytype = $partyType;
                                            $secondpartycode = $parentCode;
                                            $narration = "Flexi Recharge ".$operatorCode." - ".$operatorPlanId;
											if(($secondpartycode == "") || empty($secondpartycode) || ($secondpartycode = null)) {
												$secondpartycode = "";
												$secondpartytype = "";
											}
											else {
												$secondpartytype = substr($secondpartycode,0);
											}
											$journal_entry_id = process_glentry($acc_trans_type, $transaction_id, $firstpartycode, $firstpartytype, $secondpartycode, $secondpartytype, $narration, $totalAmount, $userId, $con);
											if($journal_entry_id > 0) {
												$journal_entry_error = "N";																								
												$evd_transaction_query = "INSERT INTO evd_transaction (e_transaction_id, evd_trans_log_id, user_id, total_amount, request_amount, ams_charge, partner_charge, other_charge, operator_id, opr_plan_id, opr_plan_desc, mobile_number, total_discount, date_time) VALUES ($evd_transaction_id, $evd_trans_log_id, $userId, $totalAmount, $requestedAmount, $amsCharge, $partnerCharge, $otherCharge, $operatorId, $operatorPlanId, '$narration', '$mobile', 0, now())";
												error_log("evd_transaction_query = ".$evd_transaction_query);
												$evd_transaction_result = mysqli_query($con, $evd_transaction_query);
												if($evd_transaction_result ) {
													$evd_order_rollback = "N";					
													error_log("inside success evd_transaction table entry");																									
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
															
															$data['countryId'] = $countryId;
															$data['operatorId'] = $operatorId;
															$data['operatorCode'] = $operatorCode;
															$data['operatorPlanId'] = $operatorPlanId;
											    			$data['mobileNumber'] = $mobile;
															$data['reMobileNumber'] = $reMobile;
															$data['dnValue'] = $totalAmount;
															$data['dnCode'] = 0;
															$data['amount'] = $requestedAmount;
															$data['total'] = $totalAmount;
															$data['lineType'] = $evd_trans_log_id;
															$data['transLogId'] = $evd_trans_log_id;
                                                                  
                                                            if ($operatorCode == "9M"){
                                                                $url = EVD_SERVER_9M_URL;
                                                            }else {
                                                                $url = EVD_SERVER_URL;
                                                            }
																									
															$tsec = time();
															$raw_data1 = EVDAPI_SERVER_APP_PASSWORD.FINWEB_SERVER_SHORT_NAME."|".EVDAPI_SERVER_APP_USERNAME.FINWEB_SERVER_SHORT_NAME."|".$tsec;
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
                                                            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                                                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
															curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
															curl_setopt($ch, CURLOPT_TIMEOUT, 25);
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
																	$statusCode = $api_response['errorCode'];
																	$responseDescription = $api_response['errorDescription'];
																	error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																	error_log("response_received <=== ".$curl_response);
																	$update_query = "UPDATE evd_trans_log SET response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE evd_trans_log_id = $evd_trans_log_id";
																	error_log("update_query = ".$update_query);
																	$update_query_result = mysqli_query($con, $update_query);
																	if($statusCode == 0) {
																		error_log("inside statusCode == 0");
															
																		$update_query = "UPDATE evd_transaction SET reference_no = '".$api_response['flexiMessage']['refno']."' WHERE e_transaction_id = $evd_transaction_id";
																		error_log("update_query2 = ".$update_query);
																		$update_result = mysqli_query($con, $update_query);
																				
																		$gl_post_return_value = process_glpost($journal_entry_id, $con);
																		if ( $gl_post_return_value == 0 ) {
																			error_log("Success in EVD Flexi Airtime gl_post for: ".$journal_entry_id);
																		}
																		else{
																			error_log("Error in gl_post, with gl_post_return_value = ".$gl_post_return_value);
																			insertjournalerror($userId, $journal_entry_id, $code, "AP", "W", "N", $totalAmount, $con);
																		}
																		$response["result"] = "Success";
																		$response["statusCode"] = $statusCode;																	
																		$response["message"] = "Your Airtime Order# $evd_trans_log_id for NGN $requestedAmount submitted.";
                                                                        $response["partnerId"] = $partnerId;
                                                                        $response["transactionId"] = $evd_trans_log_id;
																		$response["referenceNo"] = $api_response['flexiMessage']['refno'];
																		$response["signature"] = $server_signature;
																	}																					
																	else {
																		//EVD Transaction Request responseCode not equal to 0
																		$evd_order_rollback = "Y";
																		$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																		if ( $gl_reverse_repsonse != 0 ) {
																			error_log("Error in EVD Flexi Airtime gl_reverse for: ".$journal_entry_id);
																			insertjournalerror($userId, $journal_entry_id, $code, "AR", "O", "N", $totalAmount, $con);
																		}else {
																			error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																		}
																				
																		//Rollback wallet update
																		$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId);
																		if ( $update_wallet != 0 ) {
																			error_log("Error in EVD Flexi Airtime rollback_wallet for: ".$journal_entry_id);
																			insertaccountrollback($userId, $journal_entry_id, $code, $totalAmount, 2, "F", $con);
																		}else {
																			error_log("Success in EVD Flexi Airtime rollback_wallet for: ".$journal_entry_id);
																			//Insert into account_rollback table with success status
																		}
																		$response["result"] = "Error";
																		$response["statusCode"] = $statusCode;
																		$response["partnerId"] = $partnerId;
																		$response["signature"] = $server_signature;
																		$response["message"] = $api_response["errorDescription"];
                                                                    }
                                                                }
																else {
																	error_log("inside httpcode != 200");
																	$evd_order_rollback = "Y";
																	$statusCode = $httpcode;
																	$responseDescription = "HTTP Protocol Error";
																	error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																	$update_query = "UPDATE evd_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE evd_trans_log_id = $evd_trans_log_id";
																	error_log("update_query = ".$update_query);
																	$update_query_result = mysqli_query($con, $update_query);
                            
                                                                    $gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																	if ( $gl_reverse_repsonse != 0 ) {
							    										error_log("Error in EVD Flexi Airtime gl_reverse for: ".$journal_entry_id);
																		insertjournalerror($userId, $journal_entry_id, $code, "AR", "O", "N", $totalAmount, $con);
																	}else {
																		error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																	}
																				
																	//Rollback wallet update
																	$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId);
																	if ( $update_wallet != 0 ) {
																		error_log("Error in EVD Flexi Airtime rollback_wallet for: ".$journal_entry_id);
																		insertaccountrollback($userId, $journal_entry_id, $code, $totalAmount, 2, "F", $con);
																	}else {
																		error_log("Success in EVD Flexi Airtime rollback_wallet for: ".$journal_entry_id);
																		//Insert into account_rollback table with success status
																	}
																	$response["statusCode"] = $statusCode;
                                                                    $response["result"] = "Error";
    																$response["message"] = "Error in connection to EVD API Server";
																	$response["partnerId"] = $partnerId;
																	$response["signature"] = $server_signature;
																}
															}
															else {
																error_log("curl_error != 0 ");
																$evd_order_rollback = "Y";
																$statusCode = $curl_error;
																$responseDescription = "CURL Execution Error";
																$approver_comments = "AT ".$statusCode." - ".$responseDescription;
																error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																$update_query = "UPDATE evd_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE evd_trans_log_id = $evd_trans_log_id";
																error_log("update_query = ".$update_query);
																$update_query_result = mysqli_query($con, $update_query);
																$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
               
                                                                if ( $gl_reverse_repsonse != 0 ) {
	    															error_log("Error in EVD Flexi Airtime fundtransfer gl_reverse for: ".$journal_entry_id);
																	insertjournalerror($userId, $journal_entry_id, $code, "AR", "O", "N", $totalAmount, $con);
																}else {
																	error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																}
																						
																//Rollback wallet update
																$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId);
																if ( $update_wallet != 0 ) {
																	error_log("Error in EVD Flexi Airtime rollback_wallet for: ".$journal_entry_id);
																	insertaccountrollback($userId, $journal_entry_id, $code, $totalAmount, 2, "F", $con);
																}else {
																	error_log("Success in EVD Flexi Airtime rollback_wallet for: ".$journal_entry_id);
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
															$evd_order_rollback = "Y";		
															error_log("inside not able to update wallet for $evd_transaction");
															$msg = "Error: EVD Flexi Airtime [$narration] Order $evd_transaction_id due to failure in account update. Contact Kadick Admin.";
																
															$journal_reverse_query = "select gl_reverse($journal_entry_id) as gl_reverse_result";
															$journal_reverse_result = mysqli_query($con, $journal_reverse_query);
															if ( $journal_reverse_result ) {
																$journal_reverse_result_row = mysqli_fetch_array($journal_reverse_result);
																$journal_reverse_result_code = $journal_reverse_result_row['gl_reverse_result'];
																error_log("journal_reverse_result_code = ".$journal_reverse_result_code);
																if($journal_reverse_result_code == 0) {
																	//Journal Reverse is success
																	error_log("Journal Reverse is done when account balance update failed for EVD Flexi Airtime order no = $evd_transaction_id");
																}else {
																	//journal_reverse_error, log it in journal_error table
																	$journal_error_query = "insert into journal_error(journal_error_id, branch_id, user_id, user_type, transaction_id, acc_trans_type_code, amount, error_code, error_type, create_date, status) values (0, $branch_id, $user_id, '$journal_user_type', $evd_transaction_id, 'RVSL1', $totalAmount, 'AE', 'S', now(), 'N')";
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
																$journal_error_query = "insert into journal_error(journal_error_id, branch_id, user_id, user_type, transaction_id, acc_trans_type_code, amount, error_code, error_type, create_date, status) values (0, $branch_id, $user_id, '$journal_user_type', $evd_transaction_id, 'RVSL1', $totalAmount, 'AE', 'S', now(), 'N')";
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
														$evd_order_rollback = "Y";
														$response["statusCode"] = 190;
														$response["result"] = "Error";
														$response["message"] = "Error in getting Acc Transaction Type";
														$response["partnerId"] = $partnerId;
														$response["signature"] = $server_signature;
													}

                                                    if ( $evd_order_rollback == "Y") {
														//Remove evd_transaction because of balance update error
														$evd_transaction_delete_query = "delete from evd_transaction where e_transaction_id = $evd_transaction_id";
														error_log("evd_transaction_delete_query = " . $evd_transaction_delete_query);
														$evd_transaction_delete_result = mysqli_query($con, $evd_transaction_delete_query);
														if ( $evd_transaction_delete_result ) {
															error_log("evd_transaction delete successful");
														}else {
															error_log("evd_transaction delete failure = ".mysqli_error());
														}
													}
												}
												else {																				
													//failure - Insert evd_transaction
													$response["result"] = "failure";
													$response["statusCode"] = 200;																	
													$response["message"] = "Error in inserting into Evd Transaction Order";
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
    										//failure - Create EVD Transaction Order No
                                            $response["result"] = "failure";
                                            $response["statusCode"] = 230;																	
                                            $response["message"] = "Error in creating EVD Transaction Order No";
                                            $response["partnerId"] = $partnerId;
                                            $response["signature"] = $server_signature;
										}
									}
									else {
										//failure - Create evd_trans_log table
									    $response["result"] = "failure";
									    $response["statusCode"] = 240;																	
									    $response["message"] = "Error in creating EVD Transaction Log Table";
									    $response["partnerId"] = $partnerId;
									    $response["signature"] = $server_signature;
                                    }
								}
								else {
									//failure - Creating evd_trans_log_id
								    $response["result"] = "failure";
								    $response["statusCode"] = 250;																	
								    $response["message"] = "Error in creating order no for EVD Transaction Log Table";
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
	error_log(json_encode($response));
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
function sendRequest($body, $url) {

	require_once '../api/get_prime.php';
	require_once '../api//security.php';
	require_once '../common/gh/autoload.php';
	error_log("url = ==> ".$url);
	error_log("entering sendRequest");
	date_default_timezone_set('Africa/Lagos');
	$nday = date('z')+1;
	$nyear = date('Y');
	error_log( "nday = ".$nday);
	error_log( "nyear = ".$nyear);
	$nth_day_prime = get_prime($nday);
	$nth_year_day_prime = get_prime($nday+$nyear);
	error_log("nth_day_prime = ".$nth_day_prime);
	error_log("nth_year_day_prime = ".$nth_year_day_prime);
	$signature = $nday + $nth_day_prime;
	error_log("signature = ".$signature);
	$tsec = time();
	$raw_data1 = FINSOL_SERVER_APP_PASSWORD.SERVER_SHORT_NAME."|".FINSOL_SERVER_APP_USERNAME.SERVER_SHORT_NAME."|".$tsec;
	error_log("raw_data1 = ".$raw_data1);
	$key1 = base64_encode($raw_data1);
	error_log("key1 = ".$key1);
	error_log("before calling post");
	error_log("url = ".$url);		
	$body['key1'] = $key1;
	$body['signature'] = $signature;
	error_log("request sent ==> ".json_encode($body));
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	error_log("response received = ".$response);
	error_log("code ".$httpcode);
	error_log("exiting sendRequest");
	$api_response = json_decode($response, true);
	error_log("api_response received = ".$api_response);
	return $response;
}									
?>
