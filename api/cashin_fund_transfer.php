<?php
	include('../common/admin/configmysql.php');
	$data = json_decode(file_get_contents("php://input"));	
	require '../api/get_prime.php';
	require_once("db_connect.php");
	error_log("inside pcposapi/cashin_fund_transfer.php");
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                error_log("inside post request method");
                $data = json_decode(file_get_contents("php://input"));
                error_log(json_encode($data));				
                if(isset($data -> operation)) {
                        error_log("inside isset data->operation method");
                        $operation = $data -> operation;
                        if(!empty($operation)) {
							error_log("inside not empty FINWEB_CASH_IN_FUND_TRANSFER method");
							if($operation == 'FINWEB_CASH_IN_FUND_TRANSFER'){
								error_log("inside operation == FINWEB_CASH_IN_FUND_TRANSFER method");
								$partnerId = $data->partnerId;
								$accountNumber    = $data->accountNumber;
								$accountName = $data->accountName;
								$bankCode= $data->bankCode;
								$sessionId = $data->sessionId;
								$bvn = $data->bvn;
								$kycLevel = $data->kycLevel;
								$totalAmount = $data->totalAmount;
								$requestedAmount = $data->requestedAmount;
								$narration = $data->narration;
								$transactionId = $data->transactionId;
								$signature = $data->signature;
								$key1 = $data->key1;
								$userid = $data->userid;
								if ( 
									 isset($partnerId ) && !empty($partnerId) && 
									 isset($accountNumber) && !empty($accountNumber) && 
									 isset($accountName) && !empty($accountName) && 
									 isset($bankCode) && !empty($bankCode) && 
									 isset($outletname) && !empty($outletname) && 
									 isset($sessionId) && !empty($sessionId) &&
									 isset($bvn) && !empty($bvn) &&
									 isset($kycLevel) && !empty($kycLevel) &&
									 isset($totalAmount ) && !empty($totalAmount) && 
									 isset($requestedAmount) && !empty($requestedAmount) && 
									 isset($narration ) && !empty($narration) && 
									 isset($transactionId) && !empty($transactionId) && 
									 isset($signature ) && !empty($signature) && 
									 isset($key1) && !empty($key1) && 
									 isset($userid) && !empty($userid) && 
									 ) {

									error_log("inside all inputs are set correctly");
									// connecting to db
									$db = new DB_CONNECT();
									error_log("db_connect done");
									// array for JSON response
									$response = array();

									$signature = $data -> signature;
									$key = $data -> key;
									error_log("signature = ".$signature.", key = ".$key);
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
									if ( $local_signature != $signature ){
										// failure
										$response["result"] = "failure";
										$response["action"] = "ERROR";
										$response["message"] = "Invalid Client Request";
										error_log(json_encode($response));

										// echoing JSON response
										echo json_encode($response);
										return;

									}
									else {
											$get_sequence_number_query = "SELECT get_sequence_num(200) as application_id";
											$get_sequence_number_result =  mysqli_query($con,$get_sequence_number_query);
											if(!$get_sequence_number_result) {
												error_log("Invalid Method");
												error_log("Sequence No".mysqli_error($con));
												// Failure - Invalid Method
												$response["result"] = "failure";
												$response["action"] = "ERROR";
												$response["message"] = "Error in Getting Sequence No";
												// echoing JSON response
												echo json_encode($response);
												return;				
											}
											else {
												$get_sequence_num_row = mysqli_fetch_assoc($get_sequence_number_result);
												$application_id = $get_sequence_num_row['application_id'];
												$application_main_query = "";
												if($appliertype == "S") {
													$parenttype = "A";
												}
												else if($appliertype == "A") {
													$parenttype = "C";
												}
												if($appliertype == "S" || $appliertype == "A") {
													if($parentcode != "" || !empty($parentcode) || $parentcode != null) {
														$application_main_query = "INSERT INTO application_main (application_id, application_category, version, outlet_code, status, comments, applier_type, parent_type, parent_code, create_user, create_time, login_name) VALUES ($application_id, '$category', $version, '$outlet_code', 'P', '$comment', '$appliertype', '$parenttype', '$parentcode', $createuser, now(), '$userName')";
													}
													else {
														$application_main_query = "INSERT INTO application_main (application_id, application_category, version, outlet_code, status, comments, applier_type, create_user, create_time, login_name) VALUES ($application_id, '$category', $version, '$outlet_code', 'P', '$comment', '$appliertype', $createuser, now(), '$userName')";
													}
												}
												if($appliertype == "P" || $appliertype == "C") {
													$application_main_query = "INSERT INTO application_main (application_id, application_category, version, outlet_code, status, comments, applier_type, create_user, create_time, login_name) VALUES ($application_id, '$category', $version, '$outlet_code', 'P', '$comment', '$appliertype', $createuser, now(), '$userName')";
												}
												error_log($application_main_query);
												$application_main_result =  mysqli_query($con,$application_main_query);
												if(!$application_main_result) {
													$response["result"] = "failure";
													$response["action"] = "ERROR";
													$response["message"] = "APPMAIN - Failed";
													error_log(' Application main entry failed: ' . mysqli_error($con));
												}
												else {
													$application_info_query = "INSERT INTO application_info (application_id, country_id, outlet_name, tax_number, address1, address2, state_id, local_govt_id, zip_code, mobile_no, work_no, email, contact_person_name, contact_person_mobile, language_id) VALUES ($application_id, $countryid, '$outletname', '$taxnumber', '$address1', '$address2', $stateid, $localgovernmentid, '$zipcode', '$mobileno', '$workno', '$email', '$cname', '$cmobile', '$langpref')";
													error_log("info_query = ".$application_info_query);
												$application_info_result =  mysqli_query($con,$application_info_query);
													if(!$application_info_result) {
														$response["result"] = "failure";
														$response["action"] = "ERROR";
														$response["message"] = "APPINFO - Failed";
														error_log(' Application info entry failed: ' . mysqli_error($con));
													}
													$response["result"] = "success";
                                                	$response["message"] = "Your Application No: $application_id submitted successfully";
                                                	$response["action"] = "NORMAL";
                                                	error_log(json_encode($response));
													// echoing JSON response
													echo json_encode($response);
													return;
												}
										}
									}
								}
								else {
									// Failure - Invalid Method
									$response["result"] = "failure";
									$response["action"] = "ERROR";
									$response["message"] = "InValid Data";
									// echoing JSON response
									echo json_encode($response);
									return;				
								}
							}
							else {
								$response["result"] = "failure";
								$response["action"] = "ERROR";
								$response["message"] = "Operation3 Faiure";
								// echoing JSON response
								echo json_encode($response);
								return;			
							}
						}					
					else {
				
						$response["result"] = "failure";
						$response["action"] = "ERROR";
						$response["message"] = "Operation2 Failue";
						// echoing JSON response
						echo json_encode($response);
						return;			
				
					}
				}			
				else {
					$response["result"] = "failure";
					$response["action"] = "ERROR";
					$response["message"] = "Operation1 Failue";
					// echoing JSON response
					echo json_encode($response);
					return;			
				}
		}
		else {
			$response["result"] = "failure";
			$response["action"] = "ERROR";
			$response["message"] = "Post Failure";
			// echoing JSON response
			echo json_encode($response);
			return;			
		}
									
?>
