<?php
	include('../common/admin/configmysql.php');
	$data = json_decode(file_get_contents("php://input"));	
	require '../api/get_prime.php';
	require 'functions.php';
	require_once("db_connect.php");
	error_log("inside pcposapi/cashin_name_enquiry.php");
	//ERROR_REPORTING(E_ALL);
	$response = array();
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		error_log("inside post request method");
		$data = json_decode(file_get_contents("php://input"));
		error_log(json_encode($data));				
		if(isset($data -> operation)) {
			error_log("inside isset data->operation method");
			$operation = $data -> operation;
			if(!empty($operation)) {
				error_log("inside not FINWEB_CASH_IN_NAME_ENQUIERY");
				if($operation == 'FINWEB_CASH_IN_NAME_ENQUIERY'){
					error_log("inside operation == FINWEB_CASH_IN_NAME_ENQUIERY method");
					$partnerId = $data->partnerId;
					$accountNumber= $data->accountNumber;
					$bankCode = $data->bankCode;
					$signature= $data->signature;
					$key1 = $data->key1;
					$userId = $data->userId;
					$partner = $data->partnerId;
					$totalAmount = $data->totalAmount;
					$state = $data->state;
					$partyCode = $data->partyCode;
					$partyType =  $data->partyType;
					if ( 
						 isset($partnerId ) && !empty($partnerId) && 
						 isset($accountNumber) && !empty($accountNumber) && 
						 isset($bankCode) && !empty($bankCode) && 
						 isset($signature) && !empty($signature) && 
						 isset($key1) && !empty($key1) && 
						 isset($userId) && !empty($userId) &&
						 isset($partner) && !empty($partner) &&
						 isset($partyCode) && !empty($partyCode)&&
						 isset($partyType) && !empty($partyType)
						 
						) {
						error_log("inside all inputs are set correctly");
						// connecting to db
						$db = new DB_CONNECT();
						error_log("db_connect done");
						// array for JSON response
						$response = array();
						$signature = $data -> signature;
						$key = $data -> key;
						error_log("signature = ".$signature.", key = ".$key1);
						date_default_timezone_set('Africa/Lagos');
						$nday = date('z')+1;
						$nyear = date('Y');
						error_log( "nday = ".$nday);
						error_log( "nyear = ".$nyear);
						$nth_day_prime = get_prime($nday);
						$nth_year_day_prime = get_prime($nday+$nyear);
						error_log("nth_day_prime = ".$nth_day_prime);
						error_log("nth_year_day_prime = ".$nth_year_day_prime);
						$local_signature = $nday + $nth_day_prime;
						error_log("local_signature = ".$local_signature);
						if ( $local_signature == $signature ){											
					    	$agent_info_wallet_status = check_agent_info_wallet_status($partyType, $partyCode, $con);																		
								if ($agent_info_wallet_status == 0 ) {
										//Checking available_balance
										$available_balance = check_agent_available_balance($userId, $con);						
										 error_log("available_balance response for  = ".$available_balance);
									if ( $available_balance >= 0 || $available_balance != "" || $available_balance != null ) {																			
										//error_log(json_encode($response));
										if ( floatval($totalAmount) < floatval($available_balance) ) {
												//error_log(json_encode($response));
												$fin_trans_log_id = generate_seq_num(1600, $con);
												$request_message = json_encode($data);
										    	if($fin_trans_log_id > 0)  {
													error_log("fin_trans_log_id ".$fin_trans_log_id);
													$fin_trans_log_query = "INSERT INTO fin_non_trans_log (fin_non_trans_log_id, request_message, message_send_time) VALUES ($fin_trans_log_id, 'Name Enquiry Request = $request_message', now())";
													error_log($fin_trans_log_query);
													$fin_trans_log_result = mysqli_query($con, $fin_trans_log_query);
													if($fin_trans_log_result ) {
														$data = array();
														$data['partnerId'] = $partner;
														$data['accoutNumber'] = $accoutNumber;
														$data['bankCode'] = $bankcode;
														$data['partnerId'] = $partner;
														$data['userId'] = $userId;
														$url = NAME_ENQUIRY_CHECK_URL;
														$sendreq = sendRequest($data, $url);
														$response_message = $sendreq;
														$sendreq = json_decode($sendreq, true);
														$statusCode = $sendreq['responseCode'];
														$responseDescription = $sendreq['responseDescription'];
														error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
														$updatequery = "UPDATE fin_non_trans_log SET  response_message = '$response_message', response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE fin_non_trans_log_id = $fin_trans_log_id";
														$update_result = mysqli_query($con, $updatequery);
														if($update_result ) {
															$response["result"] = "success";
															$response["action"] = "SUCCESS";
															$response["responseCode"] = $statusCode;																	
															$response["message"] = $responseDescription;
															$response["responseDescription"] = $response_message;
														}
														else {
															//DB2.die(mysql_error());
															$response["result"] = "failure";
															$response["action"] = "ERROR";
															$response["responseCode"] = "-12";																	
															$response["message"] = "DB2".die(mysql_error());
															$response["responseDescription"] = die(mysql_error());
														}
													}
													else {
														//DB1.die(mysql_error());
														$response["result"] = "failure";
														$response["action"] = "ERROR";
														$response["responseCode"] = "-11";																	
														$response["message"] = "DB1".die(mysql_error());
														$response["responseDescription"] = die(mysql_error());
													}	
												}
												else {
													//Error in Generating Transaction Reference Number
													$response["result"] = "failure";
													$response["action"] = "ERROR";
													$response["responseCode"] = "-10";																	
													$response["message"] = "Wallet Status1";
													$response["responseDescription"] = "Error in Generating Transaction Reference Number.";
													
												}
										}
										else {
											// Insufficient Agent Available Balance
											$response["result"] = "failure";
											$response["action"] = "ERROR";
											$response["responseCode"] = "-9";																	
											$response["message"] = "Wallet Status1";
											$response["responseDescription"] = "Insufficient Agent Available Balance";
										}
									}
									else {
										//Agent Available Balance is not available
										$response["result"] = "failure";
										$response["action"] = "ERROR";
										$response["responseCode"] = "-8";																	
										$response["message"] = "Wallet Status1";
										$response["responseDescription"] = "Agent Available Balance is not available";
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
								$response["result"] = "failure";
								$response["action"] = "ERROR";
								$response["responseCode"] = "-7";																	
								$response["message"] = "Wallet Status";
								$response["responseDescription"] = $resp_message;
							}
														
						}
						else {
							//Invalid Clinet Request 
							$response["result"] = "failure";
							$response["action"] = "ERROR";
							$response["responseCode"] = "-6";																	
							$response["responseDescription"] = "Invalid Client Request";
							$response["message"] = "Invalid Client Request";
						}
							}
							else {
								// Failure - Invalid Method
								$response["result"] = "failure";
								$response["action"] = "ERROR";
								$response["message"] = "InValid Data";
								$response["responseCode"] = "-5";
								$response["responseDescription"] = "InValid Data";											
							}
				}
				else {
					//In Correct Operation
					$response["result"] = "failure";
					$response["action"] = "ERROR";
					$response["message"] = "In Correct Operation";
					$response["responseCode"] = "-4";
					$response["responseDescription"] = "";			
				}
			}					
		else {				
		       //Operation2 Failure
		   	$response["result"] = "failure";
		 	$response["action"] = "ERROR";
			$response["message"] = "Operation2 Failue";
			$response["responseCode"] = "-3";
			$response["responseDescription"] = "";	
			 }
		}			
	   	else {
			//Operation1 Failure
			$response["result"] = "failure";
			$response["action"] = "ERROR";
			$response["message"] = "Operation1 Failure";
			$response["responseCode"] = "-2";
			$response["responseDescription"] = "";					
		 }
	}
	else {
		//Post Failure
		$response["result"] = "failure";
		$response["action"] = "ERROR";
		$response["message"] = "Post Failure";
		$response["responseCode"] = "-1";
		$response["responseDescription"] = "";
	 }
	
	// echoing JSON response
	error_log("cashin_name_enquiry ==> ".json_encode($response));
	echo json_encode($response);			

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
