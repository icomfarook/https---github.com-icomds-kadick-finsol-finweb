<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));
	
	require '../api/get_prime.php';
	require '../api/security.php';
	require '../common/gh/autoload.php';
	include ("functions.php");
	//ERROR_REPORTING(E_ALL);
	$action = mysqli_real_escape_string($con,$data->action);
	//error_log($action);
	$stateId = $_SESSION['state_id'];
	$user = $_SESSION['user_id'];
	$localGvtid = $_SESSION['local_govt_id'];
	$productId = 16;
	$partnerId = 9;
	$partyCount = 2;
	$txtType = 'E';
	$countryId = $_SESSION['country_id'];
	if($action == "check") {
	
		$data = array();
		$get_feature_value_query = "SELECT get_feature_value_new($countryId, $stateId, $localGvtid, $productId, $partnerId,0, '$txtType', $partyCount, null, null, $user, -1) as result";
		error_log("get_feature_value query = ".$get_feature_value_query);
		$get_feature_value_result = mysqli_query($con, $get_feature_value_query);
		if ($get_feature_value_result) {										
			$row = mysqli_fetch_assoc($get_feature_value_result); 
			$db_result = $row['result']; 
			

		}else {
			// DB failure
			$data["chargeDetail"] = ""; 
			$data["statusCode"] = "10";
			$data["result"] = "Error";
			$data["message"] = "Failure: Error in reading charges from DB";
			$data["partnerId"] = $partnerId;
		}
		echo $db_result;
	}
	$action = mysqli_real_escape_string($con,$_POST['action']);
	
	if($action == "create") {
		$location = '../upload/';
		$service_feature_code = 'BAO';
		$partyType = $_SESSION['party_type'];
		$partyCode= $_SESSION['party_code'];
		$agent_info_wallet_status = check_agent_info_wallet_status($partyType, $partyCode, $con);
		if ($agent_info_wallet_status == 0 ) {
			//Checking available_balance
			error_log("agent_info_wallet_status response for = ".$agent_info_wallet_status);
			$available_balance = check_agent_available_balance($user, $con);
			error_log("available_balance response for = ".$available_balance);
			if ( $available_balance >= 0 || $available_balance != "" || $available_balance != null ) {
				$countfiles = count($_FILES['file']['name']);
				$countfiles2 = count($_FILES['file2']['name']); 
				$customerpic = $_FILES['file']['name'][0];  
				$signpic = $_FILES['file2']['name'][0];  
				
				$parentCode= $_SESSION['parent_code'];		
				$check = filesize ( $customerpic );   
				$check2 = filesize ( $signpic );   
				compress($_FILES['file']['tmp_name'][0],$location.$customerpic, 30);
				//move_uploaded_file($_FILES['file']['tmp_name'][0],$location.$customerpic);  
				//move_uploaded_file($_FILES['file2']['tmp_name'][0],$location.$signpic);  
				compress($_FILES['file']['tmp_name'][0],$location.$signpic, 30);
				$content = file_get_contents($location.$customerpic);
				$content = base64_encode($content);
				$content2 = file_get_contents($location.$signpic);
				$content2 = base64_encode($content2);		
				$sanefreqsend = array();		
				$bankaccount =mysqli_real_escape_string($con,$_POST['bankaccount']);
				$firstName =mysqli_real_escape_string($con,$_POST['firstName']);
				$midName =mysqli_real_escape_string($con,$_POST['midName']);
				$lastName =mysqli_real_escape_string($con,$_POST['lastName']);
				$gender =mysqli_real_escape_string($con,$_POST['gender']);
				$email =mysqli_real_escape_string($con,$_POST['email']);
				$houseNo = mysqli_real_escape_string($con,$_POST['houseNo']);
				$streetName = mysqli_real_escape_string($con,$_POST['streetName']);
				$city = mysqli_real_escape_string($con,$_POST['city']);
				$bvn =mysqli_real_escape_string($con,$_POST['bvn']);
				$mobileno =mysqli_real_escape_string($con,$_POST['mobileno']);
				$state =mysqli_real_escape_string($con,$_POST['state']);
				$localgovernment =mysqli_real_escape_string($con,$_POST['localgovernment']);
				//$country =$_POST['country'];
				$dob = mysqli_real_escape_string($con,$_POST['dob']);
				$user =$_SESSION['user_id'];
				$streetName = $_POST['streetName'];	
				$sanefreqsend['bankaccount'] = $bankaccount;
				$sanefreqsend['firstName'] = $firstName;
				$sanefreqsend['countryId'] = $_SESSION['country_id'];
				$sanefreqsend['midName'] = $midName;
				$sanefreqsend['agentCode'] =  $_SESSION['party_code'];
				$sanefreqsend['lastName'] = $lastName;
				$sanefreqsend['gender'] = $gender;
				$sanefreqsend['email'] = $email;
				$sanefreqsend['houseNo'] = $houseNo;
				$sanefreqsend['streetName'] = $streetName;
				$sanefreqsend['city'] = $city;
				$sanefreqsend['mobileno'] = $mobileno;
				$sanefreqsend['state'] = $state;
				$sanefreqsend['state'] = $state;
				$sanefreqsend['localgovernment'] = $localgovernment;
				$sanefreqsend['customerImage'] = $content ;
				$sanefreqsend['customerSignature'] = $content2;
				$sanefreqsend['superAgentCode'] = SANEF_ACC_OPEN_SUPER_AGENT_CODE;
				$dob = date("Y-m-d", strtotime($dob));
				//error_log("datareq = ".$sanefreqsend);
				//var_dump("asanefreqsend".$sanefreqsend);
				$sanefreqsend['dob'] = $dob;
				$filetype =  mysqli_real_escape_string($con,pathinfo($location.$customerpic, PATHINFO_EXTENSION));
				$filetype2 =  mysqli_real_escape_string($con,pathinfo($location.$signpic, PATHINFO_EXTENSION));
				//error_log("filetype = ".$filetype);
				if($filetype != "pdf" || $filetype != "jpg" || $filetype != "png" || $filetype != "gif") {
					$filetype = "oth";
				}
				$get_feature_value_query = "SELECT get_feature_value_new($countryId, $stateId, $localGvtid, $productId, $partnerId,0, '$txtType', $partyCount, null, null, $user, -1) as result";
				error_log("get_feature_value query = ".$get_feature_value_query);
				$get_feature_value_result = mysqli_query($con, $get_feature_value_query);
				if ($get_feature_value_result) {										
					$row = mysqli_fetch_assoc($get_feature_value_result); 
					$db_result = $row['result']; 
					error_log("db_result = ".$db_result);
					if ( substr( $db_result, 0, 1 ) === "0" ) {
						error_log("checing inside total amount");
						$split2 = explode("#",$db_result);
						$rateparties_details = $split2[1];		
						//$serconfig =$split2[1];
						$split3 = explode("|",$split2[0]); 
						$split4 =explode(",",$split2[1]);  
						$otherCharge = $split3[4] ;
						$agentCharge = $split3[2];
						$partnerCharge =  $split3[3];
						$totalAmount = 	$otherCharge +  $partnerCharge + $agentCharge ; 
						if ( floatval($totalAmount) <= floatval($available_balance) ) {				
							error_log("floatval(totalAmount) <= floatval(available_balance)".$totalAmount);
							$seq_no = generate_seq_num(3700, $con);
							if($seq_no != -1) {
								$id = generate_seq_num(3400, $con);
								if($id != -1) {
									$reqMsg = "streetName: ".$streetName.", bankaccount: ".$bankaccount.", firstName: ".$firstName.", midName: ".$midName."lastName: ".$lastName.", gender: ".$gender.", lastName: ".$lastName.", dob: ".$dob."email: ".$email.", bvn: ".$bvn.", localgovernment: ".$localgovernment;
									$query1 =  "INSERT INTO acc_trans_log (acc_trans_log_id, service_feature_id, message_send_time, create_user, create_time, request_message, party_type, party_code, state_id  ) VALUES ($id, 16, now(), $user, now(), '$reqMsg', '$partyType', '$partyCode', $state)";
									error_log("acc_trans_log query".$query1);
									$result1 = mysqli_query($con,$query1);						
									$sanefreqsend['requestId'] = $seq_no;
									if ($result1) {	
										$query =  "INSERT INTO acc_request (acc_request_id, acc_trans_log_id, service_feature_code, state_id, local_govt_id,  first_name, middle_name, last_name, bvn, gender, dob, house_no, street_name, city, email, mobile, status,  create_time) VALUES  ($seq_no, '$id', 'BAO','$state', $localgovernment,'$firstName','$midName','$lastName', '$bvn','$gender','$dob', '$houseNo','$streetName', '$city', '$email',  '$mobileno', 'I',  now())";
										error_log("acconunt request query".$query);
										$result = mysqli_query($con,$query);						
										if ($result) {										
											$query =  "INSERT INTO acc_request_detail (acc_request_detail_id, acc_request_id, customer_image, customer_sign) VALUES  (0, $seq_no, '$content','$content2')";
											$result = mysqli_query($con,$query);
											if ($result) {													
												$response = sendRequest($sanefreqsend);
												$res = explode("BRK",$response);
												$json = json_decode($res[0], true);
												$curl_error = $res[2];
												error_log("curl_error ". $curl_error);
												if(	$curl_error == "0") {
													$httpCode = $res[1];
													error_log("httpCode ". $httpCode);
													if(	$httpCode == "200") {							
														$responseCode = $json['responseCode'];	
														$responseDescription = $json['responseDescription'];
														$accountNumber = $json['accountNumber'];					
														$api_response = json_decode($res[0], true);
														$response_code = $api_response['responseCode'];
														$res_description = $api_response['responseDescription'];
														$accountNumber = $api_response['accountNumber'];
														$status = "";
														if($response_code == 0) {
															$status = 'S';
														}
														else {
															$status = 'E';
														}
														error_log("accountNumber".$accountNumber);	
														$updaqueryquery =  "UPDATE acc_request SET account_number = '$accountNumber', status = '$status', update_time = now() WHERE acc_request_id = $seq_no";
														error_log("Account Request Update Query".$updaqueryquery);
														$query1 = "UPDATE acc_trans_log SET response_message ='$response', message_receive_time = now(), response_received = 'Y', error_code = '$response_code', error_description = '$res_description' where acc_trans_log_id = $id ";                 
														error_log("Account Trans Log Update Query".$query1);
														$result1 = mysqli_query($con,$query1);
														$result = mysqli_query($con,$updaqueryquery);
														if (!$result) {
															$response = array();
															$response["message"] = 'DB3:Filure'.mysqli_error($con);
															$response["responseCode"] = -17;
															$response["errorResponseDescription"] = mysqli_error($con);
														}
														elseif (!$result1) {
															error_log("acc_Trans_log update failed");
															$response = array();
															$response["message"] = 'DB3:Filure'.mysqli_error($con);
															$response["responseCode"] = -16;
															$response["errorResponseDescription"] = mysqli_error($con);
														}
														else {
															if($response_code == 0) {
																error_log("inside statusCode === 0");
																$bao_service_order_no = generate_seq_num(3600, $con);
																if ( $bao_service_order_no > 0)  {
																	error_log("Inside ACC REQUEST Service Order ==> Acc_request_SERVICE_ORDER_NO = ".$bao_service_order_no);
																	$acc_trans_type = 'BPAY1';
																	$firstpartycode = $_SESSION['party_code'];
																	$firstpartytype = $partyType;
																	$secondpartycode = $parentCode;
																	if(($secondpartycode == "") || empty($secondpartycode) || ($secondpartycode = null)) {
																		$secondpartycode = "";
																		$secondpartytype = "";
																	}
																	else {
																		$narration = "BANK ACCOUNT OPEN FOR ORDER_NO".$bao_service_order_no;
																		$secondpartytype = substr($secondpartycode,0);
																		$journal_entry_id = process_glentry($acc_trans_type, $bao_service_order_no, $firstpartycode, $firstpartytype, $secondpartycode, $secondpartytype, $narration, $totalAmount, $user, $con);
																		if($journal_entry_id > 0) {
																			$journal_entry_error = "N";	
																			$new_ams_charge = floatval($amsCharge) - floatval($agentCharge);
																			$bao_service_order_query = "INSERT INTO acc_service_order (acc_service_order_no, acc_trans_log_id, service_feature_code, bank_id, partner_id ,user_id, service_feature_config_id, date_time, post_status) VALUES ($bao_service_order_no, $id, 'BAO', $bankaccount, $partnerId,  $user, 16, now(),'N')";
																			
																			error_log("acc_service_order query = ".$bao_service_order_query);
																			$bao_service_order_result = mysqli_query($con, $bao_service_order_query);
																			if( $bao_service_order_result ) {
																				error_log("inside success bao_service_order table entry");
																				$get_acc_trans_type = getAcccTransType($acc_trans_type, $con);
																				error_log("get_acc_trans_type = ".$get_acc_trans_type);	
																				if($get_acc_trans_type != "-1"){
																					$split = explode("|",$get_acc_trans_type);
																					$ac_factor = $split[0];
																					$cb_factor = $split[1];
																					$acc_trans_type_id = $split[2];
																					$update_wallet = walletupdateWithTransaction($acc_trans_type, $cb_factor, $firstpartytype, $firstpartycode, $totalAmount, $con, $user, $journal_entry_id);
																					if( $update_wallet == 0 ) {	
																						$gl_post_return_value = process_glpost($journal_entry_id, $con);
																						if ( $gl_post_return_value == 0 ) {
																							error_log("Success in Bank Account Open gl_post for: ".$journal_entry_id);
																						}
																						else{
																							error_log("Error in gl_post, with gl_post_return_value = ".$gl_post_return_value);
																							insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AP", "W", "N", $totalAmount, $con);
																						}

																						$order_post_result = post_baoorder($bao_service_order_no, $con);
																						if ( $order_post_result == 0 ) { 
																							error_log("Success in Bank Account OpenBank Account Open post_baoorder for: ".$bao_service_order_no);
																						}else {
																							error_log("Error in Bank Account OpenBank Account Open post_baoorder for: ".$bao_service_order_no);
																						}
																						$serviceconfig = explode(",", $rateparties_details);
																						$service_insert_count = 0;

																						//Insert into bao_service_order_comm table
																						for($i = 0; $i < sizeof($serviceconfig); $i++) {
																							$baoOrder_flag = insertBankAccountOpenServiceOrderComm($bao_service_order_no, $serviceconfig[$i], $journal_entry_id, $con);
																							if ( $baoOrder_flag == 0 ) {
																								++$service_insert_count;
																							}
																						}
																						if ( $service_insert_count == sizeof($serviceconfig) ) {
																							error_log("All entries for service_order insert in commission table. Insert count = ".$service_insert_count);
																						}else {
																							error_log("Not all entries for service_order insert in commission table. Insert count = ".$service_insert_count.", serviceConfig count = ".sizeof($serviceconfig));
																						}
																						$pcu_result = process_comm_update($bao_service_order_no, $con);
																						if ( $pcu_result > 0 ) {
																							if ( $pcu_result == sizeof($serviceconfig) ) {
																								error_log("All bao_service_order_comm updates are completed. Count = ".$pcu_result);
																							}else {
																								error_log("Warning bao_service_order_comm updates are not matching completed. Count = ".$pcu_result);
																							}
																						}else {
																							error_log("Error in bao_service_order_comm records insert. Insert Count = ".$pcu_result);
																						}
																						$response = array();
																						$response["statusCode"] = "00";
																						$response["result"] = "SUCCESS";
																						$response["message"] = "Bank Account Created Successfully For Account Number".$accountNumber;
																						$response["accountNumber"] = $accountNumber;
																						//$response["signature"] = $server_signature;
																						$response["processingStartTime"] = $api_response['processingStartTime'];
																						$response["partnerId"] = $partnerId;
																						//$response["signature"] = $server_signature;
																						$response["totalAmount"] = $totalAmount;
																						$response["orderNo"] = $bao_service_order_no;
																						
																					}
																					else {
																						$response= array();
																						$response["statusCode"] = "-15";
																						$response["result"] = "Error";
																						$response["message"] = "DB Error in updating wallet";
																						$response["partnerId"] = $partnerId;
																						//$response["signature"] = $server_signature;
																					}
																				}
																			}
																			else {
																				$response= array();
																				//Error in insert bao Service Order
																				$response["statusCode"] = "-14";
																				$response["result"] = "Error";
																				$response["message"] = "DB Error in bao Service Order Request".mysqli_error($con);
																			}
																		}
																		else {
																			//Error in getting journal_entry_id
																			$response= array();
																			$response["statusCode"] = "-13";
																			$response["result"] = "Error";
																			$response["message"] = "DB Error in gettin journal entry id";
																			//$response["partnerId"] = $partnerId;
																			//$response["signature"] = $server_signature;
																		}
																	}
																}
																else {
																		$response= array();
																	//Error in Generating bao Service Order
																		$response["statusCode"] = "-12";
																		$response["result"] = "Error";
																		$response["message"] = "DB Error in bao Service Order Request";
																		//$response["partnerId"] = $partnerId;
																		//$response["signature"] = $server_signature;
																}
															}
															else {
																if ( $statusCode == '') {
																	$statusCode = 50;
																}
																error_log("inside statusCode != 0");
																$rollBackOrder = "Y";
																$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																if ( $gl_reverse_repsonse != 0 ) {
																	error_log("Error in Bank Account OpenBank Account Open gl_reverse for: ".$journal_entry_id);
																	insertjournalerror($user, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $totalAmount, $con);
																}else {
																	error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																}
																//Rollback wallet update
																$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $user);
																if ( $update_wallet != 0 ) {
																	error_log("Error in Bank Account OpenBank Account Open rollback_wallet for: ".$journal_entry_id);
																	insertaccountrollback($user, $journal_entry_id, $acc_trans_type, $totalAmount, 2, "F", $con);
																}else {
																	error_log("Success in Bank Account OpenBank Account Open rollback_wallet for: ".$journal_entry_id);
																	//Insert into account_rollback table with success status
																}
																
																$update_query = "UPDATE acc_request SET status = 'E', update_time = now() WHERE acc_request_id = $seq_no";
																error_log("update_query = ".$update_query);
																$update_query_result = mysqli_query($con, $update_query);

																$response["statusCode"] = $statusCode;
																$response["result"] = "Error";
																$response["message"] = $responseDescription;
																$response["partnerId"] = $partnerId;
																//$response["signature"] = $server_signature;
																							

															}
															
														}
													}
													else {//Error in Generating bao Service Order
														
														$response["statusCode"] = "-11";
														$response_code = $response["statusCode"] ;
														$response["result"] = "Error";														
														$response["message"] = "Http Error";
														$res_description  =$response["responseDescription"] ;
														//$response["partnerId"] = $partnerId;
														//$response["signature"] = $server_signature;
														$updaqueryquery =  "UPDATE acc_request SET  status = 'E', update_time = now() WHERE acc_request_id = $seq_no";
														error_log("acc_request update query".$updaqueryquery);														
														$result = mysqli_query($con,$updaqueryquery);
														if ($result) {
															$query1 = "UPDATE acc_trans_log SET  message_receive_time = now(), response_received = 'N', error_code = '$response_code', error_description = '$res_description' where acc_trans_log_id = $id ";                 
															error_log("UPDATE acc_trans_log query".$query1);														
															$result1 = mysqli_query($con,$query1);
															if ($result1) {
																$response = array();
																$response["msg"] = 'DB3:Filure';
																$response["responseCode"] = -10;
																$response["errorResponseDescription"] = "Error Http Error: ".mysqli_error($con);
																$response["message"] = 'DB:Filure'.mysqli_error($con);
															}
															else {
															}
														}
														else {
															$response = array();
															$response["msg"] = 'DB3:Filure';
															$response["responseCode"] = -9;
															$response["message"] = 'DB:Filure'.mysqli_error($con);
															$response["errorResponseDescription"] = "Error Http Error: ".mysqli_error($con);
														}
														
													}
												}
												else {
													//Error in Generating bao Service Order
													$response = array();
														$response["statusCode"] = "500";
														$response_code = $response["statusCode"] ;
														$response["result"] = "Error";
														$response["message"] = "Curl Error";
														$res_description  =$response["responseDescription"] ;
														//$response["partnerId"] = $partnerId;
														//$response["signature"] = $server_signature;
														$updaqueryquery =  "UPDATE acc_request SET  status = 'E', update_time = now() WHERE acc_request_id = $seq_no";
														error_log($updaqueryquery);
														$query1 = "UPDATE acc_trans_log SET  message_receive_time = now(), response_received = 'N', error_code = '$response_code', error_description = '$res_description' where acc_trans_log_id = $id ";                 
														error_log($query1);
														$result1 = mysqli_query($con,$query1);
														$result = mysqli_query($con,$updaqueryquery);
														if (!$result) {
															$response = array();
															$response["msg"] = 'DB3:Filure';
															$response["responseCode"] = -8;
															$response["errorResponseDescription"] = "Error Http Error: ".mysqli_error($con);
															$response["message"] = 'DB:Filure'.mysqli_error($con);
														}
														elseif (!$result1) {
															$response = array();
															$response["msg"] = 'DB3:Filure';
															$response["responseCode"] = -7;
															$response["errorResponseDescription"] = "Error Http Error: ".mysqli_error($con);
															$response["message"] = 'DB:Filure'.mysqli_error($con);
														}
													}
												}
											}
											else {
												$response = array();
												$response["message"] = 'DB:Filure'.mysqli_error($con);
												$response["responseCode"] = -6;
												$response["errorResponseDescription"] = mysqli_error($con);
												$response["message"] = mysqli_error($con);
											}
									}
									else {
										$response = array();
										$response["msg"] = 'DB:Filure';
										$response["responseCode"] = -5;
										$response["errorResponseDescription"] = mysqli_error($con);
										$response["message"] = mysqli_error($con);
										error_log("accrequest_non_trans_log insert failed");
									}
								}
									
							}
							else {
								$response = array();
								$response["msg"] = 'DB:Filure';
								$response["responseCode"] = -4;
								$response["errorResponseDescription"] = mysqli_error($con);
								$response["message"] = mysqli_error($con);
							}
						}
						else {
							// Insufficient Agent Available Balance
							$response["statusCode"] = -3;
							$response["result"] = "Error";
							$response["message"] = "Insufficient Agent Available Balance";
							//$response["partnerId"] = $partnerId;
							//$response["signature"] = $server_signature;
						}
						
					}
					else {
						$response = array();
						$response["msg"] = 'DB:Filure';
						$response["responseCode"] = -2;
						$response["errorResponseDescription"] = "Transaction LogError Sequence Number Error: < 0  ";
						$response["message"] = "Error Sequence Number Error: ";
					}
					
				}		
					
				else {
					$response = array();
					$response["msg"] = 'DB3:Filure';
					$response["responseCode"] = -1;
					$response["errorResponseDescription"] = "Error Sequence Number Error: < 0  ";
					$response["message"] = "Error Sequence Number Error: ";
				}
				
			}			
			else {
				$response = array();
				//Agent Available Balance is not available
				$response["responseCode"] = "-1";			
				$response["result"] = "Error";
				$response["message"] = "Agent Available Balance is not available";
				$response["errorResponseDescription"] = "Agent Available Balance is not available";
				//$response["partnerId"] = $partnerId;
				//$response["signature"] = $server_signature;
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
			//$response["partnerId"] = $partnerId;
			//$response["signature"] = $server_signature;
		}
			echo json_encode($response);
	}
		
		
		function sendRequest($data){		
			error_log("entering sendSanefAccRequest");
			date_default_timezone_set('Africa/Lagos');
			$nday = date('z')+1;
			$nyear = date('Y');
			$nth_day_prime = get_prime($nday);
			$nth_year_day_prime = get_prime($nday+$nyear);
			$Signature = $nday + $nth_day_prime;
			$tsec = time();
			$raw_data1 = FINAPI_SERVER_APP_PASSWORD.FINWEB_SERVER_SHORT_NAME."|".FINAPI_SERVER_APP_USERNAME.FINWEB_SERVER_SHORT_NAME."|".$tsec;
			$key1 = base64_encode($raw_data1);
			error_log("before calling post");
			error_log("url = ".FINAPI_SERVER_SANEF_ACC_OPEN);	
			$body['signature'] = $Signature;		
			$body['countryId'] = $data['countryId'] ;
			$body['stateId'] = $data['state'] ;
			$body['localGovtId'] = $data['localgovernment'] ;
			$body['key1'] = $key1;
			$body['superAgentCode'] = 	$data['superAgentCode'] ;
			$body['agentCode'] = 	$data['agentCode'] ;
			$body['bankCode'] = $data['bankaccount'] ;
			$body['requestId'] = $data['requestId'] ;
			$body['bankVerificationNumber'] =  $data['bankaccount'] ;
			$body['firstName'] =	$data['firstName'];
			$body['middleName'] = $data['midName'];
			$body['lastName'] = $data['lastName'];
			$body['gender'] = $data['gender'];
			$body['dateOfBirth'] = $data['dob'] ;
			$body['houseNumber'] = $data['houseNo'] ;
			$body['streetName'] = $data['streetName'] ;
			$body['city'] = $data['city'];
			$body['lgaCode'] = $data['localgovernment'] ;
			$body['emailAddress'] = $data['email'] ;
			$body['phoneNumber'] = $data['mobileno'] ;
			$body['accountOpeningBalance'] = 0.00 ;
			$body['customerImage'] = $data['customerImage'] ;
			$body['customerSignature'] = $data['customerSignature'] ;
			$ch = curl_init(FINAPI_SERVER_SANEF_ACC_OPEN);
		//	ERROR_REPORTING(E_ALL);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, FINAPI_SERVER_CONNECT_TIMEOUT);
			curl_setopt($ch, CURLOPT_TIMEOUT, FINAPI_SERVER_REQUEST_TIMEOUT);
			
			$response = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$curl_error = curl_errno($ch);
			curl_close($ch);
			
			error_log("response received <== ".$response);
			error_log("curl_error <== ".$curl_error);
			error_log("code ".$httpcode);
			error_log("exiting sendSanefAccRequest");
			return $response."BRK".$httpcode."BRK".$curl_error;
		}
		
	function compress($source, $destination, $quality) {

    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg') 
        $image = imagecreatefromjpeg($source);

    elseif ($info['mime'] == 'image/gif') 
        $image = imagecreatefromgif($source);

    elseif ($info['mime'] == 'image/png') 
        $image = imagecreatefrompng($source);

    imagejpeg($image, $destination, $quality);

    return $destination;
}

	
	function insertBankAccountOpenServiceOrderComm($bao_service_order_no, $serviceconfig, $journal_entry_id, $con) {
	
		//Format for serviceconfig
		//service_charge_rate_id~service_charge_party_name~comm_user_id~comm_user_name~charge_value
		$serviceconfig = explode("~",$serviceconfig);
		$service_charge_rate_id = $serviceconfig[0];
		$service_charge_party_name = $serviceconfig[1];
		$comm_user_id = $serviceconfig[2];
		$charge_value = $serviceconfig[4];
		$query =  "INSERT INTO acc_request_order_comm ( acc_request_order_no, service_charge_rate_id, service_charge_party_name, user_id, charge_value, journal_entry_id) VALUES ($bao_service_order_no, $service_charge_rate_id, '$service_charge_party_name', $comm_user_id, $charge_value, $journal_entry_id)";
		error_log("acc_request_order_comm query = ".$query);
		$result = mysqli_query($con,$query);
		if(!$result) {
			error_log("Error: insertBankAccountOpenServiceOrderComm = %s\n".mysqli_error($con));
			$msg = -1;
		}
		else {
				$msg = 0;
		}
		return $msg;
	}

		function post_baoorder($bao_order_no, $con) {
				
			$query = "update acc_service_order set post_status = 'Y', post_time = now() where acc_service_order_no = ".$bao_order_no;
			error_log("acc_service_order update = ".$query);
			$result = mysqli_query($con, $query);
			if (!$result) {
				error_log("Error: process_accrequestorderpost = ".mysqli_error($con));
				$ret_val = -1;
			}
			else {
				$ret_val = 0;
			}
			error_log("acc_service_order: result = ".$ret_val);
			return $ret_val;
		}	
	
?>