<?php
	include('../common/admin/configmysql.php');
	include ("get_prime.php");	
	include ("functions.php");
	require_once ("AesCipher.php");
	error_log("inside pcposapi/cashin_charge_calcuate.php");

	//ERROR_REPORTING(E_ALL);
	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		error_log("inside post request method");
		$data = json_decode(file_get_contents("php://input"));
		error_log("cashin_name_enquiry <== ".json_encode($data));
		
		if( isset($data->operation) && $data->operation == 'CASHIN_VERIFY_OPERATION') {
			error_log("inside operation == CASHIN_VERIFY_OPERATION method");
			if (  isset($data->accountNumber) && !empty($data->accountNumber) && isset($data->partnerId ) && !empty($data->partnerId)
				  	&& isset($data->bankCode) && !empty($data->bankCode) && isset($data->signature) && !empty($data->signature) 
				 	&& isset($data->totalAmount) && !empty($data->totalAmount) && isset($data->requestAmount) && !empty($data->requestAmount)
				 	&& isset($data->key1) && !empty($data->key1) && isset($data->userId) && !empty($data->userId) 
				 	&& isset($data->countryId) && !empty($data->countryId) && isset($data->partyCode) && !empty($data->partyCode) 
					&& isset($data->partyType) && !empty($data->partyType) && isset($data->senderName) && !empty($data->senderName)
					&& isset($data->bankId) && !empty($data->bankId)
				) {

				ini_set('max_execution_time', 90);
				set_time_limit(90);
				error_log("inside all inputs are set correctly");	
				$partnerId = $data->partnerId;
				$accountNumber = $data->accountNumber;
				$totalAmount = $data->totalAmount;
				$requestAmount = $data->requestAmount;
				$bankCode = $data->bankCode;
				$signature= $data->signature;
				$userId = $data->userId;
				$stateId = $data->stateId;
				$countryId = $data->countryId;
				$partyCode = $data->partyCode;
				$parentCode = $data->parentCode;
				$partyType = $data->partyType;
				$key1 = $data->key1;
				$bankId = $data->bankId;
				$senderName = $data->senderName;
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

			    if ( $local_signature == $signature ){

					$validate_result = validateKey1($key1, $userId, $session_validity, 'N', $con);
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
					
					//check for own account transfer
					$own_tx_check_query = "select party_bank_account_id from party_bank_account where party_type = '$partyType' and party_code = '$partyCode' and account_no = '$accountNumber' and bank_master_id = $bankId and status = 'A' and active = 'Y'";
					$own_tx = 'N';
					error_log("own_tx_check_query = ".$own_tx_check_query);
					$own_tx_check_result = mysqli_query($con, $own_tx_check_query);
					if ( $own_tx_check_result ) {
						$own_tx_check_count = mysqli_num_rows($own_tx_check_result);
						if ( $own_tx_check_count > 0 ) {
							$own_tx = 'Y';
							error_log("inside own_tx = Y for party_code = ". $partyCode.", account_no = ".$accountNumber);
						}
					}
					error_log("PartyCode = ".$partyCode." own_tx = ".$own_tx);
					if ( $own_tx == 'N') {
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
					}
					$agent_info_wallet_status = check_agent_info_wallet_status($partyType, $partyCode, $con);
					if ($agent_info_wallet_status == 0 ) {
						//Checking available_balance
						$available_balance = check_agent_available_balance($userId, $con);
						error_log("available_balance response for  = ".$available_balance);
						if ( $available_balance >= 0 || $available_balance != "" || $available_balance != null ) {
							if ( floatval($totalAmount) <= floatval($available_balance) ) {
								$fin_trans_log_id = generate_seq_num(1600, $con);
								unset($data->key1);
								$request_message = json_encode($data);
								if ($fin_trans_log_id > 0)  {
									error_log("fin_trans_log_id = ".$fin_trans_log_id);
									$request_message = mysqli_real_escape_string($con, $request_message);
									$fin_trans_log_query = "INSERT INTO fin_trans_log (fin_trans_log_id, fin_service_feature_id, partner_id, party_type, party_code, country_id, state_id, amount, request_message, message_send_time, create_user, create_time) VALUES ($fin_trans_log_id, 1, $partnerId, '$partyType', '$partyCode', $countryId, $stateId, $totalAmount, left('Name Enquiry Request = $request_message', 1000), now(), $userId, now())";
									error_log("fin_trans_log_query = ". $fin_trans_log_query);
									$fin_trans_log_result = mysqli_query($con, $fin_trans_log_query);
									if ( $fin_trans_log_result ) {
										$fin_request_id = generate_seq_num(2800, $con);
									    if ( $fin_request_id > 0)  {
											$senderName = mysqli_real_escape_string($con, $senderName);
											$fin_request_query = "INSERT INTO fin_request (fin_request_id, fin_trans_log_id1, service_feature_code, country_id, state_id, request_amount, sender_name, account_no, bank_id, user_id, total_amount, status, create_time) VALUES ($fin_request_id, $fin_trans_log_id, 'CIN', $countryId, $stateId, $requestAmount, '$senderName', '$accountNumber', $bankId, $userId, $totalAmount, 'I', now())";
											error_log($fin_request_query);
											$fin_request_result = mysqli_query($con, $fin_request_query);
											if( $fin_request_result ) {
												$data = array();
												$data['partnerId'] = $partnerId;
												$data['accountNumber'] = $accountNumber;
												$data['bankCode'] = $bankCode;
												$data['userId'] = $userId;
												$data['countryId'] = $countryId;
												$data['stateId'] = $stateId;
												$data['localGovtId'] = ADMIN_LOCAL_GOVT_ID;

												$url = FINAPI_SERVER_NAME_ENQUIRY_URL;
												//$sendreq = sendRequest($data, $url);
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
															$update_query = "UPDATE fin_request SET status = 'N', update_time = now(), auth_code = '".$api_response['sessionId']."' WHERE fin_request_id  = $fin_request_id ";
															error_log("update_query = ".$update_query);
															$update_query_result = mysqli_query($con, $update_query);

															$response["statusCode"] = "0";
															$response["result"] = "Success";
															$response["message"] = $responseDescription;
															$response["partnerId"] = $partnerId;
															$response["signature"] = $server_signature;
															$response["transactionId"] = $fin_request_id;
															$response["accountName"] = $api_response['accountName'];
															$response["sessionId"] = $api_response['sessionId'];
															$response["bvn"] = $api_response['bvn'];
															$response["kycLevel"] = $api_response['kycLevel'];
															$response["accountNumber"] = $api_response['accountNumber'];
														}
														else {
															error_log("inside statusCode != 0");
															$approver_comments = "NE: ".$statusCode." - ".$responseDescription." @ ".$api_response['processingStartTime'];
															$update_query = "UPDATE fin_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE fin_request_id = $fin_request_id ";
															error_log("update_query = ".$update_query);
															$update_query_result = mysqli_query($con, $update_query);
														
															$response["statusCode"] = $statusCode;
															$response["result"] = "Error";
															$response["message"] = $responseDescription;
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

														$approver_comments = "NE: ".$statusCode." - ".$responseDescription;
														error_log("update_query = ".$update_query);
														$update_query = "UPDATE fin_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE fin_request_id = $fin_request_id ";
														$update_query_result = mysqli_query($con, $update_query);

														$response["statusCode"] = $statusCode;
														$response["result"] = "Error";
														$response["message"] = "Error in connection to FinSol API Server";
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
												//Error in Generating Fin Request Id Result
												$response["statusCode"] = "200";
												$response["result"] = "Error";
												$response["message"] = "DB Error in Fin Request Result";
												$response["partnerId"] = $partnerId;
												$response["signature"] = $server_signature;
											}
										}
										else {
											//Error in Generating Fin Request Id
											$response["statusCode"] = "210";
											$response["result"] = "Error";
											$response["message"] = "DB Error in Fin Request";
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
				// Invalid Data
				$response["statusCode"] = "400";
				$response["result"] = "Error";
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
    }
    else {
		// Invalid Request Method
		$response["statusCode"] = "600";
		$response["result"] = "Error";
		$response["message"] = "Failure: Invalid Request Method";
		$response["partnerId"] = 0;
		$response["signature"] = 0;	
    }

	// echoing JSON response
	error_log("cashin_name_enquiry ==> ".json_encode($response));
	echo json_encode($response);

?>
