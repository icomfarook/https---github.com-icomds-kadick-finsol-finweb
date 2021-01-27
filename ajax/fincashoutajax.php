 <?php

	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	require '../api/get_prime.php';
	require '../api/security.php';
	require '../common/gh/autoload.php';	
	require('cashoutrequest.php');
	require('functions.php');
	
	$server_app_password = FINAPI_SERVER_APP_PASSWORD.''.FINWEB_SERVER_SHORT_NAME;
	$server_app_name = FINAPI_SERVER_APP_USERNAME.''.FINWEB_SERVER_SHORT_NAME;	

	$action		= $data->action;
	$product	= $data->product;
	$partner	= $data->partner;
	$reqamount	= $data->reqamount;
	$bank		= $data->bank;
	$userid 	= $_SESSION['user_id'];
	$country 	= $_SESSION['country_id'];
	$state 		= $_SESSION['state_id'];
	$localgovt	= $_SESSION['local_govt_id'];
	$parentcode = $_SESSION['parent_code'];
	
	$txtype = "I";
	if ( $partner == 1 ) {
		$txtype = "I";
	}else {
		$txtype = "E";
	}

	if($action == "calculate") {
		$res = -1;
		if($parentcode == "") {
			$partyCount = 2;
		}
		else {
			$partyCount = 3;
		}
		$query = "SELECT get_feature_value($country, $state, null, $product, $partner, $reqamount, '$txtype', $partyCount, null, null, $userid, -1) as res";
		error_log("get_feature_value query = ".$query);
		$result =  mysqli_query($con, $query);
		if (!$result) {
			error_log("Error: get_feature_value = %s\n".mysqli_error($con));
		}else {
			$row = mysqli_fetch_assoc($result); 
			$res = $row['res'];
		} 		
		echo $res;
	}
	/* else if($action == "getotp") {
	
		$partnerId =  $data->partnerId;
		$accountNo =  $data->accountNo;
		$amount =  $data->amount;
		$mobileNumber =  $data->mobileNumber;

		$userid 	= $_SESSION['user_id'];
		$firstpartycode = $_SESSION['party_code'];
		$firstpartytype = $_SESSION['party_type'];

		$response = array();

		//Checking agent_info.active, agent_info.block_status, agent_wallet.active and agent_wallet.block_status
		error_log("before checking  check_agent_info_wallet_status");
		$agent_info_wallet_status = check_agent_info_wallet_status($firstpartytype, $firstpartycode, $con);
		error_log("after checking  check_agent_info_wallet_status = ".$agent_info_wallet_status);
		if ($agent_info_wallet_status != 0 ) {
			error_log("inside agent_info_wallet_status != 0 ");
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
			
			$response["responseCode"] = 1018;
			$response["signature"] = 1018;
			$response["responseDescription"] = $resp_message;
			error_log(json_encode($response));
		}else {
			error_log("inside agent_info_wallet_status == 0 ");
			//Checking available_balance
			$available_balance = check_agent_available_balance($userid, $con);						
			error_log("available_balance response for  = ".$available_balance);
			if ( $available_balance <= 0 || $available_balance == "" || $available_balance == null ) {
				error_log("inside available_balance is 0 or empty");
				$response["responseCode"] = 1016;
				$response["signature"] = 1016;
				$response["responseDescription"] = "Agent Available Balance is not available";
				error_log(json_encode($response));
			}else {
				error_log("inside available_balance is not empty");
				if ( floatval($amount) > floatval($available_balance) ) {
					error_log("inside totalamount < available_balance");
					error_log("available_balance = ".$available_balance.", total_amount = ".$amount);
					$response["responseCode"] = 1017;
					$response["signature"] = 1017;
					$response["responseDescription"] = "Insufficient Agent Available Balance";
					error_log(json_encode($response));
				}else {	
					error_log("available_balance = ".$available_balance.", total_amount = ".$amount);									
					$api_response_json = otpSendRequest($partnerId, $accountNo, $amount, $mobileNumber);
					$api_response = json_decode($api_response_json, true);
					$error_code = $api_response['responseCode'];
					error_log("Bank GetOtp Api error_code = ".$error_code);
					if ($error_log == 0 ) {
						$response["responseCode"] = 0;
						$response["signature"] = 0;
						$response["responseDescription"] = $api_response["responseDescription"];
						error_log("fincashoutajax.php = ".json_encode($response));	
					}
					else {
						$response["responseCode"] = $error_code;
						$response["signature"] = 100;
						$response["responseDescription"] = $api_response["responseDescription"];
						error_log("fincashoutajax.php = ".json_encode($response));	
					}
				}
			}
		}
		echo json_encode($response);
	} */
	else if($action == "pay") {

		try {
			$log_constant = "";
			$log = $log_constant.date('Y:m:d h.m.s')." ************ Entering in Cash-Out Payment Process ***************".PHP_EOL;
			$product	=  $data->product;
			$partner	=  $data->partner;
			$bank		=  $data->bank;
			$reqamount	=  $data->reqamount;
			$userid 	= $_SESSION['user_id'];
			$country 	= $_SESSION['country_id'];
			$state 		= $_SESSION['state_id'];
			$localgovt	= $_SESSION['local_govt_id'];
			$parentcode = $_SESSION['parent_code'];
			$card	=  $data->card;
			$exdate	=  $data->exdate;
			$cv	=  $data->cvc;
			$otp 	= $data->otp;
			$cardname	=  $data->cardname;
			$partner	=  $data->partner;
			$requestedAmount	=  $data->reqamount;
			$sedeco = $data->sedeco;
			$accountNo = $data->accountno;
			$reAccountNo = $data->reaccountno;
			$accountName = $data->name;
			$narration = $data->narration;
			$transType = 'cashout';
			$mobileNumber = $data->mobile;
			$totalAmount	=  $data->totalAmount;
			$totalCharge	=  $data->totalcharge;
			$amsCharge	=  $data->amscharge;
			$partnerCharge	=  $data->parcharge;
			$otherCharge	=  $data->othcharge;
			$serconfig	=  $data->serconfig;
			$firstpartycode = $_SESSION['party_code'];
			$firstpartytype = $_SESSION['party_type'];
			$secondpartycode = null;
			$secondpartytype = null;
			$log .= $log_constant.date('Y-m-d h:m:s:i')." Input request:".json_encode($data)."".PHP_EOL;

			if(!empty($accountNo) && !empty($reAccountNo)) {
				if($accountNo == $reAccountNo) {
					$accountType = "ACCNO";
					$cv = "none";
					$exdate = "none";
				}
				else {
					$response = array();
					$response["responseCode"] = 1001;
					$response["signature"] = 100;
					$response["responseDescription"] = "Please Enter Valid Account Number";
					error_log(json_encode($response));
				}
			}
			if (empty($otp)) {
				$response = array();
				$response["responseCode"] = 1002;
				$response["signature"] = 100;
				$response["responseDescription"] = "Please Enter OTP Number";
				error_log(json_encode($response));
			}

			//checking_get_feature_value
			$log .= $log_constant."".date('Y:m:d h.m.s')." start_checking feature value funcion".PHP_EOL;
			$checking_feature_value_response = checking_feature_value($product, $partner, $requestedAmount, $txtype, $con);
			$log .= $log_constant."".date('Y:m:d h.m.s')." cheking feature value response: ".$checking_feature_value_response.PHP_EOL;
			$checking_feature_value_response_split = explode("#",$checking_feature_value_response);
			$charges_details = $checking_feature_value_response_split[0];
			$log .= $log_constant."".date('Y:m:d h.m.s')." charges_details: ".$charges_details.PHP_EOL;
			$rateparties_details = $checking_feature_value_response_split[1];
			$log .= $log_constant."".date('Y:m:d h.m.s')." rateparties_details: ".$rateparties_details.PHP_EOL;
			$charges_details_split = explode("|",$charges_details);
			$reponse_feature_value = $charges_details_split[0];
			$log .= $log_constant."".date('Y:m:d h.m.s')." reponse_feature_value: ".$reponse_feature_value.PHP_EOL;
			$service_feature_config_id = $charges_details_split[1];
			$log .= $log_constant."".date('Y:m:d h.m.s')." service_feature_config_id: ".$service_feature_config_id.PHP_EOL;
			$ams_charge = $charges_details_split[2];
			$log .= $log_constant."".date('Y:m:d h.m.s')." ams_charge: ".$ams_charge.PHP_EOL;
			$partner_charge = $charges_details_split[3];
			$log .= $log_constant."".date('Y:m:d h.m.s')." partner_charge: ".$partner_charge.PHP_EOL;
			$other_charge = $charges_details_split[4];
			$log .= $log_constant."".date('Y:m:d h.m.s')." other_charge: ".$other_charge.PHP_EOL;
			
			if($reponse_feature_value == 0) {					
				$log .= $log_constant."".date('Y-m-d h:m:s:i')." inside reponse_feature_value success: ".PHP_EOL;
				$log .= $log_constant."".date('Y-m-d h:m:s:i')." checking service config id id:.......... ".PHP_EOL;
				$log .= $log_constant."".date('Y-m-d h:m:s:i')." Generated Service Config id id: ".$service_feature_config_id." Requested Service Config Id: ".$sedeco.PHP_EOL;
				if($service_feature_config_id == $sedeco) {
					$log .= $log_constant."".date('Y-m-d h:m:s:i')." inside checking service configuration success: ".PHP_EOL;
					$log .= $log_constant."".date('Y-m-d h:m:s:i')." checking Ams charge details:.......... ".PHP_EOL;
					$log .= $log_constant."".date('Y-m-d h:m:s:i')." Generated ams charge: ".$ams_charge." Requested ams charge: ".$amsCharge.PHP_EOL;
					if(floatval($ams_charge) == floatval($amsCharge)) {
						$log .= $log_constant."".date('Y-m-d h:m:s:i')." inside checking ams charge success: ".PHP_EOL;
						$log .= $log_constant."".date('Y-m-d h:m:s:i')." checking Partner charge details:.......... ".PHP_EOL;
						$log .= $log_constant."".date('Y-m-d h:m:s:i')." Generated Partner charge: ".$partner_charge." Requested Partner charge: ".$partnerCharge.PHP_EOL;
						if(floatval($partner_charge) == floatval($partnerCharge)) {
							$log .= $log_constant."".date('Y-m-d h:m:s:i')." inside checking partnerCharge charge success: ".PHP_EOL;
							$log .= $log_constant."".date('Y-m-d h:m:s:i')." checking Other charge details:.......... ".PHP_EOL;
							$log .= $log_constant."".date('Y-m-d h:m:s:i')." Generated Other charge: ".$other_charge." Requested Other charge: ".$otherCharge.PHP_EOL;
							if(floatval($other_charge) == floatval($otherCharge)) {
								$log .= $log_constant."".date('Y-m-d h:m:s:i')." inside checking other charge success: ".PHP_EOL;
								$log .= $log_constant."".date('Y-m-d h:m:s:i')." checking Service Configuration details:.......... ".PHP_EOL;
								$log .= $log_constant."".date('Y-m-d h:m:s:i')." Generated Service Configuration: ".$rateparties_details." Requested Service Configuration: ".$serconfig.PHP_EOL;
								if($rateparties_details == $serconfig) {
									$log .= $log_constant."".date('Y:m:d h.m.s')." checking_feature_value_functon_response".$checking_feature_value_response.PHP_EOL;
									//amount_request_check
									$request_check_total_amount = floatval($amsCharge) +  floatval($partnerCharge) + floatval($otherCharge) + floatval($requestedAmount);
									$request_check_total_charge = floatval($amsCharge) +  floatval($partnerCharge) + floatval($otherCharge);
									error_log("inside other_charge"." checking_total_amount_progress:"." request_total_amount = ".$totalAmount." checking_total_amount = ".$request_check_total_amount);
									$log .= $log_constant."".date('Y-m-d h:m:s:i')." checking_total_amount_progress:"." request_total_amount = ".$totalAmount." checking_total_amount = ".$request_check_total_amount.PHP_EOL;
									
									if(floatval($request_check_total_amount) == floatval($totalAmount)) {
										$log .= $log_constant."".date('Y-m-d h:m:s:i')." inside checking_total_amount after success";
										//checking_total_Charge
										$log .= $log_constant."".date('Y-m-d h:m:s:i')." checking_total_charge_progress:"." request_total_charge = ".$totalCharge." checking_total_amount = ".$request_check_total_charge.PHP_EOL;
				
										if(floatval($request_check_total_charge) == floatval($totalCharge)) {
											$log .= $log_constant."".date('Y-m-d h:m:s:i')." inside checking_total_charge after success".PHP_EOL;
											//Checking agent_info.active, agent_info.block_status, agent_wallet.active and agent_wallet.block_status
											$log .= $log_constant."".date('Y:m:d h.m.s')." before checking  check_agent_info_wallet_status".PHP_EOL;
											$agent_info_wallet_status = check_agent_info_wallet_status($firstpartytype, $firstpartycode, $con);
											$log .= $log_constant."".date('Y:m:d h.m.s')." after checking  check_agent_info_wallet_status = ".$agent_info_wallet_status.PHP_EOL;
											if ($agent_info_wallet_status != 0 ) {
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
												$response = array();
												$response["responseCode"] = 1018;
												$response["signature"] = 1018;
												$response["responseDescription"] = $resp_message;
												error_log(json_encode($response));
											}else {
												//Checking available_balance
												$available_balance = check_agent_available_balance($userid, $con);						
												error_log("available_balance response for  = ".$available_balance);
												$log .= $log_constant."".date('Y:m:d h.m.s')." available_balance = ".$available_balance.PHP_EOL;
												if ( $available_balance <= 0 || $available_balance == "" || $available_balance == null ) {
													$response = array();
													$response["responseCode"] = 1016;
													$response["signature"] = 1016;
													$response["responseDescription"] = "Agent Available Balance is not available";
													error_log(json_encode($response));
												}else {
													if ( floatval($totalAmount) > floatval($available_balance) ) {
														$log .= $log_constant."".date('Y:m:d h.m.s')." available_balance = ".$available_balance.", total_amount = ".$totalAmount.PHP_EOL;
														$response = array();
														$response["responseCode"] = 1017;
														$response["signature"] = 1017;
														$response["responseDescription"] = "Insufficient Agent Available Balance";
														error_log(json_encode($response));
													}else {	
														$log .= $log_constant."".date('Y:m:d h.m.s')." available_balance = ".$available_balance.", total_amount = ".$totalAmount.PHP_EOL;									
														if(!empty($cv) && !empty($exdate) && !empty($cardname) && !empty($card)) {
															$accountType = "CARD";
														}
														$fin_trans_log_id = generate_seq_num(1600, $con);
														if($fin_trans_log_id > 0)  {
															
															$location = FINAPI_LOG_LOCATION;
															$cashout = new cashoutRequest($country, $state, $localgovt, $partner,$accountType, $exdate, $cv, $accountNo, $accountName, $narration, $transType, $mobileNumber, $requestedAmount, $totalAmount, $partnerCharge, $amsCharge, $otherCharge, $fin_trans_log_id, '', '', $cardname);
															$code = "FCOUO";
															$get_acc_trans_type = getAcccTransType($code, $con);
															$log .= $log_constant."".date('Y:m:d h.m.s')." get_acc_trans_type = ".$get_acc_trans_type.PHP_EOL;
															if($get_acc_trans_type != "-1") {
																
																$split = explode("|",$get_acc_trans_type);
																$ac_factor = $split[0];
																$cb_factor = $split[1];
																$acc_trans_type_id = $split[2];
																$log .= $log_constant."".date('Y:m:d h.m.s')." firstpartytype  = ".$firstpartytype.PHP_EOL;
																$log .= $log_constant."".date('Y:m:d h.m.s')." split 0 = ".$split[0].", split 1 = ".$split[1].", split 2 = ".$split[2].PHP_EOL;
																$journal_entry_id = process_glentry($code, $fin_trans_log_id, $firstpartycode, $firstpartytype, $secondpartycode, $secondpartytype, $narration, $totalAmount, $userid, $con);
														
																if($journal_entry_id > 0) {
																	$update_wallet = walletupdateWithTransaction($ac_factor, $cb_factor, $firstpartytype, $firstpartycode, $totalAmount, $con, $userid, $journal_entry_id);
																	if($update_wallet == 0) {					
																		$cashout->insertTransLog($con, $fin_trans_log_id, $sedeco, $userid);

																		if ( $cashout->flag == 0) {
																			error_log("before cashout sendRequest");
																			$log .= $log_constant."".date('Y:m:d h.m.s')." before cashout sendRequest".PHP_EOL;								
																			$api_response_json = sendRequest($country, $state, $localgovt, $partner, $accountType, $exdate, $cv, $accountNo, $accountName, $narration, $transType, $mobileNumber, $requestedAmount, $totalAmount, $partnerCharge, $amsCharge, $otherCharge, $fin_trans_log_id, $otp);
																			$log .= $log_constant."".date('Y:m:d h.m.s')." after cashout sendRequest".PHP_EOL;
																			error_log("after cashout sendRequest");
																			$api_response = json_decode($api_response_json, true);
																			$error_code = $api_response['responseCode'];
																			error_log("Bank Api error_code = ".$error_code);
																			$log .= $log_constant."".date('Y:m:d h.m.s')." cashout sendRequest ==> Bank Api error_code = ".$error_code.PHP_EOL;
																			$cashout->updateTransLog($con, $fin_trans_log_id, $api_response_json);
																			if ( $cashout->flag != 0 ) {
																				error_log("error in updating translog table after cashout sendRequest response");
																				$log .= $log_constant."".date('Y:m:d h.m.s')." error in updating translog table after cashout sendRequest response".PHP_EOL;
																			}

																			if($error_code == 0) {
																				//Success response from Bank Api
																				//Execute gl_post. In case error. Report error in journal_error table and continue as normal
																				$log .= $log_constant."".date('Y:m:d h.m.s')." error_code = ".$error_code.PHP_EOL;
																				$gl_post_return_value = process_glpost($journal_entry_id, $con);
																				if ( $gl_post_return_value != 0 ) {
																					error_log("Error in cashout gl_post for: ".$journal_entry_id);
																					$log .= $log_constant."".date('Y:m:d h.m.s')." Error in cashout gl_post for ".$journal_entry_id.PHP_EOL;
																					insertjournalerror($userid, $journal_entry_id, $code, "AP", "W", "N", $totalAmount, $con);
																				}
																				else {
																					error_log("Success in cashout gl_post for: ".$journal_entry_id);
																					$log .= $log_constant."".date('Y:m:d h.m.s')." Success in cashout gl_post for ".$journal_entry_id.PHP_EOL;
																				}
																				
																				//Insert into fin_service_order table
																				$fin_service_order_no = generate_seq_num(1500, $con);
																				$log .= $log_constant."".date('Y:m:d h.m.s')." fin_service_order_no = ".$fin_service_order_no.PHP_EOL;	
																				$cashout->insertFinanceServiceOrder('COU', $fin_service_order_no, $fin_trans_log_id, $userid, $sedeco, $bank, $partner, $con);
																				
																				if ( $cashout->flag == 0) {
																					error_log("Success in insert into fin_service_order table for cashout order_no = ".$fin_service_order_no);
																					error_log("Success in insert into fin_service_order table for cashout order_no = ".$fin_service_order_no);
																					error_log("Before calling post_finorder for cashout order # ".$fin_service_order_no);
																					$order_post_result = post_finorder($fin_service_order_no, $con);
																					error_log("After calling post_finorder for cashout order # ".$fin_service_order_no." result = ".$order_post_result);
																					$log .= $log_constant."".date('Y:m:d h.m.s')." post_finorder for cashout order result = ".$order_post_result.PHP_EOL;
																					if ( $order_post_result == 0 ) {
																						$log .= $log_constant."".date('Y:m:d h.m.s')." Success in post_finorder for cashin order result = ".$order_post_result.PHP_EOL;
																					}else {
																						$log .= $log_constant."".date('Y:m:d h.m.s')." Error in post_finorder for cashin order result = ".$order_post_result.PHP_EOL;
																					}
																					$log .= $log_constant."".date('Y:m:d h.m.s')." Success in inserting fin_service_order table for fin_service_order_no = ".$fin_service_order_no.PHP_EOL;
																					$log .= $log_constant."".date('Y:m:d h.m.s')." before inserting fin_service_order_comm table".PHP_EOL;
																					$serviceconfig = explode(",",$serconfig);
																					$service_insert_count = 0;

																					//Insert into fin_service_order_comm table
																					for($i = 0; $i < sizeof($serviceconfig); $i++) {
																						$cashout->insertFinanceServiceOrderComm($fin_service_order_no, $serviceconfig[$i], $journal_entry_id, $con);
																						if ( $cashout->flag == 0 ) {
																							++$service_insert_count;
																						}
																					}
																					$log .= $log_constant."".date('Y:m:d h.m.s')." after inserting fin_service_order_comm tabe = ".PHP_EOL;
																					$log .= $log_constant."".date('Y:m:d h.m.s')." service_insert_count = ".$service_insert_count.", sizeof(serviceconfig) = ".sizeof($serviceconfig).PHP_EOL;
																					if ( $service_insert_count == sizeof($serviceconfig) ) {
																						$log .= $log_constant."".date('Y:m:d h.m.s')." All fin_service_order_comm records are inserted. Count = ".$service_insert_count.PHP_EOL;
																					}
																					else {
																						$log .= $log_constant."".date('Y:m:d h.m.s')." Error in fin_service_order_comm records insert. Insert Count = ".$service_insert_count.PHP_EOL;																				
																					}

																					//Call process_comm_update
																					$pcu_result = process_comm_update($fin_service_order_no, $con);
																					if ( $pcu_result > 0 ) {
																						if ( $pcu_result == sizeof($serviceconfig) ) {
																							$log .= $log_constant."".date('Y:m:d h.m.s')." All fin_service_order_comm updates are completed. Count = ".$pcu_result.PHP_EOL;
																						}else {
																							$log .= $log_constant."".date('Y:m:d h.m.s')." Warning fin_service_order_comm updates are not matching completed. Count = ".$pcu_result.PHP_EOL;	
																						}
																					}else {
																						$log .= $log_constant."".date('Y:m:d h.m.s')." Error in fin_service_order_comm records insert. Insert Count = ".$pcu_result.PHP_EOL;																				
																					}
																					error_log("completed table insert");
																					
																					//Insert payment receipt for cashout
																					$payment_id = generate_seq_num(1100, $con);
																					error_log("cashout paymentid = ".$payment_id);
																					if($payment_id > 0) {
																						$cashout->payment_receipt_insert($payment_id, $country, $firstpartycode, $firstpartytype, $requestedAmount, "Fin CashOut #:".$fin_service_order_no, $narration, $userid, $con);
																						if ( $cashout->flag == 0) {
																							$code = "PAYMT";
																							$get_acc_trans_type = getAcccTransType($code, $con);
																							error_log("get_acc_trans_type ".$get_acc_trans_type);
																							if($get_acc_trans_type != "-1") {
																								$split = explode("|",$get_acc_trans_type);
																								$ab_factor = $split[0];
																								$cb_factor = $split[1];
																								$acc_trans_type_id = $split[2];
																								error_log("cashout payment - split 0  ".$split[0]."split 1  ".$split[1]."split 2  ".$split[2]);
																								$payment_journal_entry_id = process_glentry($code, $payment_id, $firstpartycode, $firstpartytype, "", "", "Payment Receipt", $requestedAmount, $userid, $con); 
																								if($payment_journal_entry_id > 0) {
																									error_log("Cash_out_Payment journal_entry_id generated. Payment # $payment_journal_entry_id");
																									$log .= $log_constant."".date('Y:m:d h.m.s')." Cash_out_Payment Receipt Id generated. Payment # ".$payment_id.PHP_EOL;
																									$update_wallet = walletupdateWithTransaction($ab_factor, $cb_factor, $firstpartytype, $firstpartycode, $requestedAmount, $con, $userid, $payment_journal_entry_id);
																									if($update_wallet == 0) {
																										$gl_post_return_value = process_glpost($payment_journal_entry_id, $con);
																										if ( $gl_post_return_value != 0 ) {
																											error_log("Error in cashout payment gl_post for: ".$payment_journal_entry_id);
																											insertjournalerror($userid, $payment_journal_entry_id, $code, "AP", "W", "N", $requestedAmount, $con);
																										}
																										else {
																											error_log("Success in cashout payment gl_post for: ".$payment_journal_entry_id);
																										}
																										$update_pay_receipt_update = autoapprovepayment($payment_id, $narration, $firstpartycode, $requestedAmount, $con, $userid);
																										error_log("payment table autoapprove complete.");
																										if($update_pay_receipt_update == 0) {
																											error_log("Cash_out_Payment Receipt approved Successfully. Payment # $payment_id");
																											$log .= $log_constant."".date('Y:m:d h.m.s')." Cash_out_Payment Receipt approved Successfully. Payment # ".$payment_id.PHP_EOL;
																										}
																										else {
																											error_log("Cash_out_Payment Receipt autoapprove error. Payment # $payment_id");
																											$log .= $log_constant."".date('Y:m:d h.m.s')." Cash_out_Payment Receipt autoapprove error. Payment # ".$payment_id.PHP_EOL;
																										}
																									}
																									else {
																										//Error in updating wallet for cash_out_payment
																										error_log("Cash_out_Payment Receipt wallet update error. Payment # $payment_id");
																										$log .= $log_constant."".date('Y:m:d h.m.s')." Cash_out_Payment Receipt wallet update error. Payment # ".$payment_id.PHP_EOL;
																										$payment_journal_reversal_respone = process_glreverse($payment_journal_entry_id, $cont);
																										if ( $payment_journal_reversal_respone == 0 ) {
																											error_log("Success in Cash_out_Payment Receipt glreverse. payment_journal_entry_id =".$payment_journal_entry_id);
																											$log .= $log_constant."".date('Y:m:d h.m.s')." Success in Cash_out_Payment Receipt glreverse. payment_journal_entry_id Payment = ".$payment_journal_entry_id.PHP_EOL;
																										}
																										else {
																											error_log("Error in Cash_out_Payment Receipt glreverse. payment_journal_entry_id =".$payment_journal_entry_id);
																											$log .= $log_constant."".date('Y:m:d h.m.s')." Error in Cash_out_Payment Receipt glreverse. payment_journal_entry_id Payment = ".$payment_journal_entry_id.PHP_EOL;
																											insertjournalerror($userid, $payment_journal_entry_id, $code, "AR", "S", "N", $requestedAmount, $con);
																										}
																									}
																								}
																								else {
																									error_log("Cash_out_Payment journal_entry_id generation error for fin_service_order_no = ".$fin_service_order_no);
																									$log .= $log_constant."".date('Y:m:d h.m.s')." Cash_out_Payment journal_entry_id generation error for fin_service_order_no = ".$fin_service_order_no.PHP_EOL;
																								}
																							}
																							else {
																								error_log("Cash_out_Payment acc_trans_type lookup error for code = ".$code." for fin_service_order_no = ".$fin_service_order_no);
																								$log .= $log_constant."".date('Y:m:d h.m.s')." Cash_out_Payment acc_trans_type lookup error for code = ".$code."for fin_service_order_no = ".$fin_service_order_no.PHP_EOL;
																							}
																						}
																						else {
																							error_log("Cash_out_Payment payment_receipt insert error for fin_service_order_no = ".$fin_service_order_no);
																							$log .= $log_constant."".date('Y:m:d h.m.s')." Cash_out_Payment payment_receipt insert error for fin_service_order = ".$fin_service_order_no.PHP_EOL;
																						}
																					}
																					else {
																						error_log("Cash_out_Payment error in generating payment_id for fin_service_order_no = ".$fin_service_order_no);
																						$log .= $log_constant."".date('Y:m:d h.m.s')." Cash_out_Payment error in generating payment_id for fin_service_order = ".$fin_service_order_no.PHP_EOL;
																					}
																					
																					error_log("completed table insert");
																					$response = array();
																					$response["responseCode"] = 0;
																					$response["Signature"] = $fin_service_order_no;
																					//$response["ResponseDescription"] = "Success: Your Cash Out Order for amount NGN $requestedAmount is complete. Order Reference No is $fin_service_order_no.";
																					$response["responseDescription"] = "Success: Your Cash Out Order for amount NGN $requestedAmount is complete. CashOut Order No is $fin_service_order_no. Ref #".$api_response["transactionRef"];
																					error_log("fincashoutajax.php = ".json_encode($response));
																				}
																				else {
																					error_log("Error in insert into fin_service_order table for cashout order_no = ".$fin_service_order_no);
																					$log .= $log_constant."".date('Y:m:d h.m.s')." Error in  inserting fin_service_order table for fin_service_order_no = ".$fin_service_order_no.PHP_EOL;
																					$response = array();
																					error_log("inside error");
																					$response["responseCode"] = 0200;
																					$response["signature"] = $fin_service_order_no;
																					$response["responseDescription"] = "Error: Your Cash Out Order for amount NGN $requestedAmount is not complete. Order Reference No is $fin_service_order_no.";
																					error_log("fincashoutajax.php = ".json_encode($response));
																				}
																			}
																			else {
																				//Error response from Bank Api
																				//Execute process_glreverse
																				$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																				if ( $gl_reverse_repsonse != 0 ) {
																					error_log("Error in Cashout gl_reverse for: ".$journal_entry_id);
																					$log .= $log_constant."".date('Y:m:d h.m.s')." Success in process_glreverse for journal_entry_id = ".$journal_entry_id.PHP_EOL;
																					insertjournalerror($userid, $journal_entry_id, $code, "AR", "O", "N", $totalAmount, $con);
																				}
																				else {
																					error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																					$log .= $log_constant."".date('Y:m:d h.m.s')." Success in process_glreverse for journal_entry_id = ".$journal_entry_id.PHP_EOL;
																				}
																				//Rollback wallet update
																				$log .= $log_constant."".date('Y:m:d h.m.s')." Bank Api error_code = ".$error_code.PHP_EOL;
																				$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userid);
																				$log .= $log_constant."".date('Y:m:d h.m.s')." update_wallet = ".$update_wallet.PHP_EOL;
																				if ( $update_wallet != 0 ) {
																					error_log("Error in Cashout rollback_wallet for: ".$journal_entry_id);
																					$log .= $log_constant."".date('Y:m:d h.m.s')." Error in agent wallet rollback".PHP_EOL;
																					//Insert into account_rollback table with failure status
																					insertaccountrollback($userid, $journal_entry_id, $code, $totalAmount, 2, "F", $con);
																				}
																				else {
																					error_log("Success in Cashout rollback_wallet for: ".$journal_entry_id);
																					$log .= $log_constant."".date('Y:m:d h.m.s')." Success in agent wallet rollback".PHP_EOL;
																					//Insert into account_rollback table with success status
																					insertaccountrollback($userid, $journal_entry_id, $code, $totalAmount, 2, "S", $con);
																				}
																					
																				$response = array();
																				$response["responseCode"] = $error_code;
																				$response["signature"] = 100;
																				$response["responseDescription"] = $api_response["responseDescription"];
																				error_log("fincashoutajax.php = ".json_encode($response));
																			}
																		}
																		else {
																			//Error in inserting new row in fin_trans_log table
																			//Execute process_glreverse
																			error_log("Error in inserting transaction log. Starting to reverse wallet update");
																			$log .= $log_constant."".date('Y:m:d h.m.s')." Error in inserting transaction log = ".PHP_EOL;
																			$gl_reverse_response = process_glreverse($journal_entry_id, $con);
																			if ( $gl_reverse_response != 0 ) {
																				error_log("Error in process_glreverse for journal_entry_id = ".$journal_entry_id);
																				$log .= $log_constant."".date('Y:m:d h.m.s')." Error in process_glreverse for journal_entry_id = ".$journal_entry_id.PHP_EOL;
																				insertjournalerror($userid, $journal_entry_id, $code, "OO", "S", "N", $totalAmount, $con);
																			}
																			else {
																				error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																				$log .= $log_constant."".date('Y:m:d h.m.s')." Success in process_glreverse for journal_entry_id = ".$journal_entry_id.PHP_EOL;
																			}

																			//Reverse the wallet update
																			$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userid);
																			$log .= $log_constant."".date('Y:m:d h.m.s')." update_wallet = ".$update_wallet.PHP_EOL;
																			if ( $update_wallet != 0 ) {
																				error_log("Error in Cashout rollback_wallet for insert transaction log error");
																				$log .= $log_constant."".date('Y:m:d h.m.s')." Error in Cashout rollback_wallet for insert transaction log error".PHP_EOL;
																				//Insert into account_rollback table with failure status
																				insertaccountrollback($userid, $journal_entry_id, $code, $totalAmount, 3, "F", $con);
																			}
																			else {
																				//Insert into account_rollback table
																				error_log("Success in Cashout rollback_wallet for insert transaction log error");
																				$log .= $log_constant."".date('Y:m:d h.m.s')." Success in Cashout rollback_wallet for insert transaction log error".PHP_EOL;
																				//Insert into account_rollback table with success status
																				insertaccountrollback($userid, $journal_entry_id, $code, $totalAmount, 3, "S", $con);
																			}
																			$response = array();
																			$response["responseCode"] = 1006;
																			$response["signature"] = 1006;
																			$response["responseDescription"] = "Error in insert transaction log.. Contact Kadick";
																			error_log(json_encode($response));								
																		}
																	}
																	else {
																		$response = array();
																		$response["responseCode"] = 1005;
																		$response["signature"] = 1005;
																		$response["responseDescription"] = "Error in updating agent wallet. Contact Kadick";
																		error_log(json_encode($response));	
																	}
																}
																else {
																	insertjournalerror($userid, null, $code, "BE", "O", "N", $totalAmount, $con);
																	$response = array();
																	$response["responseCode"] = 1004;
																	$response["signature"] = 1004;
																	$response["responseDescription"] = "Error in Journal Entry";
																	error_log(json_encode($response));					
																}
															}
															else {
																$response = array();
																$response["responseCode"] = 1003;
																$response["signature"] = 1003;
																$response["responseDescription"] = "Error in Transaction Type..Conatct Kadick";
																error_log(json_encode($response));
															}
														}
														else {
															$response = array();
															$response["responseCode"] = 1002;
															$response["signature"] = 1002;
															$response["responseDescription"] = "Error in Generating Transaction Reference NUmber.Contact Kadick";
															error_log(json_encode($response));
														}
													}
												}
											}
										}
										else {
											$response = array();
											$response["responseCode"] = 1009;
											$response["signature"] = 1009;
											$response["responseDescription"] = "InValid Request..Contact Kadick";
											error_log(json_encode($response));
										}
									}
									else {
										$response = array();
										$response["responseCode"] = 1008;
										$response["signature"] = 1008;
										$response["responseDescription"] = "InValid Request. Contact Kadick";
										error_log(json_encode($response));									
									}
								}
								else {
									$response = array();
									$response["responseCode"] = 1015;
									$response["signature"] = 1015;
									$response["responseDescription"] = "InValid Request.. Contact Kadick";
								}
							}
							else {
								$response = array();
								$response["responseCode"] = 1014;
								$response["signature"] = 1014;
								$response["responseDescription"] = "InValid Request... Contact Kadick";
							}
						}
						else {
							$response = array();
							$response["responseCode"] = 1013;
							$response["signature"] = 1013;
							$response["responseDescription"] = "InValid Request.... Contact Kadick";
						}
					}
					else {
						$response = array();
						$response["responseCode"] = 1012;
						$response["signature"] = 1012;
						$response["responseDescription"] = "InValid Request..... Contact Kadick";
					}						
				}
				else {
					$response = array();
					$response["responseCode"] = 1011;
					$response["signature"] = 1011;
					$response["responseDescription"] = "InValid Request...... Contact Kadick";
				}
			}
			else {
				$response = array();
				$response["responseCode"] = 1010;
				$response["signature"] = 1011;
				$response["responseDescription"] = "InValid Request....... Contact Kadick";
				$log .= $log_constant."".date('Y:m:d h.m.s')." inside reponse_feature_value failure: ".$other_charge.PHP_EOL;
			}
			$log .= $log_constant."".date('Y:m:d h.m.s')." json_encode result = ".json_encode($response).PHP_EOL;
			file_put_contents($location."/$fin_trans_log_id-finweb_cashout_".date("Ydmhs").'.txt', $log, FILE_APPEND);
		}		//catch exception
		catch(Exception $e) {
			error_log( 'Message: ' .$e->getMessage());
		}
		echo json_encode($response);
	}
		
	function checking_feature_value($product, $partner, $requestedAmount, $txtype, $con) {

		$userid 	= $_SESSION['user_id'];
		$country 	= $_SESSION['country_id'];
		$state 		= $_SESSION['state_id'];
		$parentcode = $_SESSION['parent_code'];
		$res = -1;
		if($parentcode == "") {
			$partyCount = 2;
		}
		else {
			$partyCount = 3;
		}
		$query = "SELECT get_feature_value($country, $state, null, $product, $partner, $requestedAmount, '$txtype', $partyCount, null, null, $userid, -1) as res";
		//$GLOBALS[log] .= $query.PHP_EOL;
		$result =  mysqli_query($con,$query);
		if (!$result) {
			error_log("Error: checking_fetuare_value = %s\n".mysqli_error($con));
		}
		$row = mysqli_fetch_assoc($result); 
		$res = $row['res']; 		
		return $res;
	}
		
	function sendRequest($countryId, $stateId, $localGovtId, $partnerId, $accountType, $expiryDate, $ccv, $accountNo, $accountName, $narration, $transType, $mobileNumber, $requestedAmount, $totalAmount, $partnerCharge, $amsCharge, $otherCharge, $transactionId, $otp) {
	
		error_log("entering sendRequest");
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
		error_log("url = ".FINAPI_SERVER_CASHOUT_URL);		
		$body['countryId'] = $countryId;
		$body['stateId'] = $stateId;
		$body['localGovtId'] = $localGovtId;
		$body['partnerId'] = $partnerId;
		$body['accountType'] = $accountType;
		$body['expiryDate'] = $expiryDate;
		$body['ccv'] = $ccv;
		$body['accountNo'] = $accountNo;			
		$body['accountName'] = $accountName;	
		$body['narration'] = $narration;
		$body['transType'] = $transType;
		$body['mobileNumber'] = $mobileNumber;
		$body['requestedAmount'] = $requestedAmount;
		$body['totalAmount'] = $totalAmount;
		$body['partnerCharge'] = $partnerCharge;
		$body['amsCharge'] = $amsCharge;
		$body['otherCharge'] = $otherCharge;
		$body['transactionId'] = $transactionId;
		$body['paymentToken'] = $otp;
		$body['key1'] = $key1;
		$body['signature'] = $Signature;
		error_log("request sent ==> ".json_encode($body));
		$ch = curl_init(FINAPI_SERVER_CASHOUT_URL);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, FINAPI_SERVER_CONNECT_TIMEOUT);
		curl_setopt($ch, CURLOPT_TIMEOUT, FINAPI_SERVER_REQUEST_TIMEOUT);
		
		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		error_log("response received <== ".$response);
		error_log("code ".$httpcode);
		error_log("exiting sendRequest");
		return $response;
	}
	
	function otpSendRequest($partnerId, $accountNo, $amount, $mobileNumber){
	
		error_log("entering sendOtpRequest");
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
		error_log("url = ".FINAPI_SERVER_GENERATE_OTP_URL);		
		$body['countryId'] = $_SESSION['country_id'];
		$body['stateId'] = $_SESSION['state_id'];
		$body['localGovtId'] = $_SESSION['local_govt_id'];
		$body['partnerId'] = $partnerId;
		$body['accountNo'] = $accountNo;			
		$body['amount'] = $amount;	
		$body['mobileNumber'] = $mobileNumber;
		$body['key1'] = $key1;
		$body['signature'] = $Signature;
		error_log("request sent ==> ".json_encode($body));
		$ch = curl_init(FINAPI_SERVER_GENERATE_OTP_URL);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, FINAPI_SERVER_CONNECT_TIMEOUT);
		curl_setopt($ch, CURLOPT_TIMEOUT, FINAPI_SERVER_REQUEST_TIMEOUT);
		
		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		error_log("response received <== ".$response);
		error_log("code ".$httpcode);
		error_log("exiting sendOtpRequest");
      	return $response;
	}
	
	function autoapprovepayment($id, $appcomment, $partycode, $appppayamount, $con, $uid) {

		$query = "UPDATE payment_receipt SET payment_approved_amount = $appppayamount, payment_approved_date = now(), payment_status = 'A', approver_comments = left('$appcomment',200), update_user = $uid, update_time = now() WHERE p_receipt_id = $id and party_code = '$partycode'";
		error_log("autoapprovepayment query = ".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			error_log("Error:autoapprovepayment = ". mysqli_error($con));
			$ret_val = -1;
		}
		else {
			$ret_val = 0;
		}
		error_log("autoapprovepayment: ret_val = ".$ret_val);
		return $ret_val;
	}
?>