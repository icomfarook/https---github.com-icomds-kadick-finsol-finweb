<?php
    	//error_log("inside posapi/cashout_mpos_order_update.php");
    	include('../common/admin/configmysql.php');
    	include ("get_prime.php");	
    	include ("functions.php");
	require_once ("AesCipher.php");
	error_log("inside posapi/cashout_mpos_order_update.php");

	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;
	$journal_entry_error = "N";

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		error_log("inside post request method");
		$data = json_decode(file_get_contents("php://input"));
		error_log("order_update <== ".json_encode($data));	

		if ( isset($data -> orderNo ) && !empty($data -> orderNo) &&  isset($data -> responseCode ) && !empty($data -> responseCode) 
    		        //&& isset($data -> stan ) && !empty($data -> stan) && isset($data -> rrn ) && !empty($data -> rrn) 
    		        //&& isset($data -> authCode ) && !empty($data -> authCode) 
    		        && isset($data -> orderType ) && !empty($data -> orderType)
    		        && isset($data -> totalAmount ) && !empty($data -> totalAmount) 
            		&& isset($data -> partyCode ) && !empty($data -> partyCode) && isset($data -> partyType ) && !empty($data -> partyType) 
            		//&& isset($data -> terminalId ) && !empty($data -> terminalId) 
            		&& isset($data -> userId ) && !empty($data -> userId) 
            		//&& isset($data -> orderComments ) && !empty($data -> orderComments)
            		&& isset($data -> countryId ) && !empty($data -> countryId) && isset($data -> stateId ) && !empty($data -> stateId) 
			&& isset($data -> signature ) && !empty($data -> signature) && isset($data -> key1 ) && !empty($data -> key1 )) {
			
			error_log("inside all inputs are set correctly");
			$signature = $data -> signature;
			$countryId = $data -> countryId;
			$stateId = $data -> stateId;
			$orderNo = $data -> orderNo;
			$userId = $data -> userId;
			$mPosResponseCode = $data -> responseCode;
			$mPosResponseDesc = $data -> responseDesc;
			$stan = $data -> stan;
			$rrn = $data -> rrn;
			$authCode = $data -> authCode;
			$mPan = $data -> mPan;
			$transactionId = $data -> transactionId;
			$transactionTime = $data -> transactionTime;
			$terminalId = $data -> terminalId;
			$orderType = $data -> orderType;
			$key1 = $data -> key1;
			$operationId = $data -> operationId;
			$orderComments = $data -> orderComments;
			$orderPartyCode = $data -> partyCode;
			$orderPartyType = $data -> partyType;
			$orderParentCode = $data -> parentCode;
			$orderParentType = $data -> parentType;
			$agentCharge = $data -> agentCharge;
			$stampCharge = $data -> stampCharge;
			$serviceCharge = $data -> serviceCharge;
			$otherCharge = $data -> otherCharge;
			$flexiRate = $data -> flexiRate;
			$orderAmount = $data -> totalAmount;
			$requestAmount = $data -> requestAmount;
			$new_available_balance = 0;
			$aid = $data -> aid;

            		//$comment = "TID: ".$terminalId.", PAN: ".$mPan.", ID: ".$transactionId.", Time :".$transactionTime;
            		$comment = "TID: ".$terminalId.", PAN: ".$mPan.", ID: ".$transactionId.", DTime: ".$transactionTime.", AID: ".$aid;
            		$approverComment = "RC: ".$mPosResponseCode."-".$mPosResponseDesc.", STAN: ".$stan.", RRN: ".$rrn;
            		error_log("agentCharge = ".$agentCharge.", stampCharge = ".$stampCharge.", flexiRate = ".$flexiRate);
			
			date_default_timezone_set('Africa/Lagos');
			$nday = date('z')+1;
			$nyear = date('Y');
			$nth_day_prime = get_prime($nday);
			$nth_year_day_prime = get_prime($nday+$nyear);
			$local_signature = $nday + $nth_day_prime;
			$server_signature = $nth_year_day_prime + $nday + $nyear;
			
			if ( $local_signature == $signature ){	

                		if ( "Cash-Out (Card)" == $orderType || "Cash-Out (Card)." == $orderType ) {
					$query = "SELECT a.fin_service_order_no, b.fin_request_id, a.user_id, a.request_amount, a.total_amount, b.sender_name, b.mobile_no, c.agent_code, c.country_id, c.state_id, c.local_govt_id, ifnull(c.parent_code, '') as parent_code, ifnull(c.parent_type, '') as parent_type, date(b.create_time) as create_date, ifnull(b.all_in, 1) as all_in FROM fin_service_order a, fin_request b, agent_info c WHERE a.user_id = c.user_id and a.fin_service_order_no = b.order_no and a.fin_service_order_no = ".$orderNo;
                    			error_log("select query: ".$query);
                   			$result = mysqli_query($con, $query);
                    			if (!$result) {
                        			$response["message"] = "Error: cheking order: ".$orderNo;
                        			$response["result"] = "failure";
                        			error_log("Error: cheking order: ".mysqli_error($con));
                    			}	
                    			else {
                    		    		$count = mysqli_num_rows($result);
                        			error_log("Select query count = ".$count." for order_no = ".$orderNo);
                        			if($count > 0) {
                            				$row = mysqli_fetch_assoc($result);
						    	$fin_service_order_no = $row['fin_service_order_no'];
						    	$fin_request_id = $row['fin_request_id'];
						    	$user_id = $row['user_id'];
						    	$countryId = $row['country_id'];
						    	$stateId = $row['state_id'];
						    	$localGovtId = $row['local_govt_id'];
						    	$mobile = $row['mobile_no'];
						    	$senderName = $row['sender_name'];
						    	$requestAmount = $row['request_amount'];
						    	$totalAmount = $row['total_amount'];
						    	$partyCode = $row['agent_code'];
						    	$partyType = "A";
						    	$parentCode = $row['parent_code'];
						    	$parentType = $row['parent_type'];
						    	$order_create_date = $row['create_date'];
						    	$cashoutAllIn = $row['all_in'];
						    	$acc_trans_type = "PAYMT";
						    	$productId = 90;
						    	$partnerId = 2;
						    	$txType = "E";
						    	
						    	$now_date = date("Y-m-d");
						    	error_log("now_date = ".$now_date.", order_create_date = ".$order_create_date." for order No = ".$orderNo);
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
							error_log("txtType = ".$txType.", partyCount = ".$partyCount);
				
                            				$glComment = "Cash-Out (Card) Order #".$fin_service_order_no;
                            				$check_order_result = checkForAlreadyProcessedCashOutOrder($user_id, $orderNo, $con);
                            				error_log("checkForAlreadyProcessedCashOutOrder.check_order_result = ".$check_order_result);
                            				
                            				$check_order_result2 = checkForAlreadyProcessedJournalEntry($user_id, $glComment, $partyCode, $con);
                            				error_log("checkForAlreadyProcessedJournalEntry.check_order_result2 = ".$check_order_result2);
                            				
                            				if ( $check_order_result == 0  && $check_order_result2 == 0 ) {
                                				error_log("First time order for ".$orderNo." for user_id = ".$user_id);
                                				if ( "00" == $mPosResponseCode ) {
                                					//Insert into Payment Receipt Table                                					
                                					$reference_no = "COU-".$acc_trans_type."#".$fin_service_order_no;
									$p_receipt_id = generate_seq_num(1100, $con);
									error_log("before requestAmount = ".$requestAmount.", cashoutAllIn = ".$cashoutAllIn);
									if ( "Y" == $cashoutAllIn ) {
										$serviceChargeAlone = $totalAmount - $requestAmount;
										error_log("serviceChargeAlone = ".$serviceChargeAlone.", cashoutAllIn = ".$cashoutAllIn);
										$requestAmount = $requestAmount - $serviceChargeAlone;
										$reference_no = "COU-AI".$acc_trans_type."#".$fin_service_order_no;
									}
									error_log("after requestAmount = ".$requestAmount.", cashoutAllIn = ".$cashoutAllIn);
									$insertDepositQuery = "INSERT INTO payment_receipt(p_receipt_id, country_id, payment_date, party_code, party_type, payment_reference_no, payment_type, payment_amount, payment_approved_amount, payment_approved_date, payment_source, payment_status, create_user, create_time, comments, approver_comments) VALUES($p_receipt_id, $countryId, now(), '$partyCode', '$partyType', '$reference_no', 'CP', $requestAmount, $requestAmount, now(), 'C', 'E', $user_id, now(), '$glComment', 'Cash-out Auto Approved by System')";
									error_log("payment_receipt insert_query: ".$insertDepositQuery);
									$insertDepositResult = mysqli_query($con, $insertDepositQuery);
									if ( !$insertDepositResult ) {
										error_log("Error in inserting payment receipt for fin_service_ocer_no = ".$fin_service_order_no);
										error_log("Skipping...Duplicate order for ".$orderNo." for user_id = ".$user_id);
										$response["message"] = "Duplicate: CashOut (Card) order skipped for Order # ".$orderNo;
										$response["result"] = "Failure";
										$response["signature"] = $server_signature;
									}
									else {
										error_log("Success in inserting payment receipt for fin_service_ocer_no = ".$fin_service_order_no);
										$journal_entry_id = process_glentry($acc_trans_type, $fin_service_order_no, $partyCode, $partyType, $parentCode, $parentType, $glComment, $requestAmount, $user_id, $con);
										error_log("select_journal_entry = ".$journal_entry_id);
										if ( $journal_entry_id == -1 ) {
											error_log("Error in getting journal_entry..trying one more time.");
											$journal_entry_id = process_glentry($acc_trans_type, $fin_service_order_no, $partyCode, $partyType, $parentCode, $parentType, $glComment, $requestAmount, $user_id, $con);
											error_log("select_journal_entry2 = ".$journal_entry_id);
                                    						}
										if($journal_entry_id > 0) {
											$journal_entry_error = "N";
											$get_acc_trans_type = getAcccTransType($acc_trans_type, $con);
											error_log("get_acc_trans_type = ".$get_acc_trans_type);
											if($get_acc_trans_type != "-1"){
												$split = explode("|", $get_acc_trans_type);
												$ac_factor = $split[0];
												$cb_factor = $split[1];
												$acc_trans_type_id = $split[2];
												$update_wallet = walletupdateWithTransaction($acc_trans_type, $cb_factor, $partyType, $partyCode, $requestAmount, $con, $user_id, $journal_entry_id);
												if($update_wallet == 0) {

													$gl_post_return_value = process_glpost($journal_entry_id, $con);
													if ( $gl_post_return_value == 0 ) {
													    error_log("Success in cashout gl_post for: ".$journal_entry_id.", fin_service_order_no = ".$fin_service_order_no);
													}else {
													    insertjournalerror($user_id, $journal_entry_id, $acc_trans_type, "AP", "W", "N", $requestAmount, $con);
													    error_log("Error in cashout gl_post for: ".$journal_entry_id.", fin_service_order_no = ".$fin_service_order_no);
													}
													$updatequery = "UPDATE fin_request set status = 'S', rrn = '$rrn', update_time = now(), auth_code = '$authCode', comments = '$comment', approver_comments = '$approverComment' WHERE order_no = $orderNo";
													error_log("update_query = ".$updatequery);
													$update_result = mysqli_query($con, $updatequery);
													if ( $update_result ) {
													    error_log("Success in purchase fin_request status update for: fin_service_order_no = ".$fin_service_order_no);
													}else {
													    error_log("Error in purchase fin_request status update for: fin_service_order_no = ".$fin_service_order_no);
													}

													$update_query2 = "UPDATE fin_service_order set auth_code = '$authCode', reference_no = '$transactionId' WHERE fin_service_order_no = $orderNo";
													error_log("update_query2 = ".$update_query2);
													$update_result2 = mysqli_query($con, $update_query2);
													if ( $update_result2 ) {
													    error_log("Success in purchase fin_service_order update for: fin_service_order_no = ".$fin_service_order_no);
													}else {
													    error_log("Error in purchase fin_service_order update for: fin_service_order_no = ".$fin_service_order_no);
													}

													$order_post_result = post_finorder($fin_service_order_no, $con);
													if ( $order_post_result == 0 ) { 
													    error_log("Success in purchase post_finorder for: ".$fin_service_order_no);
													}else {
													    error_log("Error in purchase post_finorder for: ".$fin_service_order_no);
													}
													$check_feature_value_result = check_feature_value($user_id, $countryId, $stateId, $partyCount, $productId, $partnerId, $requestAmount, $txType, $con);
													error_log("check_feature_value_result = ".$check_feature_value_result);
													$check_feature_value_result_split = explode("#",$check_feature_value_result);
													$charges_details = $check_feature_value_result_split[0];
													$charges_details_split = explode("|",$charges_details);
													$ams_charge = $charges_details_split[2];
													error_log("$ams_charge = ".$ams_charge);
													$rateparties_details = $check_feature_value_result_split[1];
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
													$response["serverUpdate"] = "Y";
													$response["orderNo"] = $orderNo;
													$response["orderType"] = $orderType;
													$response["signature"] = $server_signature;
													$response["newAvailableBalance"] = $new_available_balance;
													$response["message"] = "CashOut (Card) order confirmed for Order # ".$fin_service_order_no;
												}
												else {
													//Delete payment receipt
													$deletePaymentReceiptQuery = "delete from payment_receipt where p_receipt_id = ".$p_receipt_id;
													error_log("deletePaymentReceiptQuery = ".$deletePaymentReceiptQuery);
													$deletePaymentReceiptResult = mysqli_query($con, $deletePaymentReceiptQuery);
													if ( $deletePaymentReceiptResult) {
														error_log("successfully deleted entry in payment_receipt for p_receipt_id = ".$p_receipt_id);
													}else {
														error_log("error in deleting entry in payment_receipt for p_receipt_id = ".$p_receipt_id);
													}
													
													$response["result"] = "Failure";
													$response["signature"] = $server_signature;
													$response["newAvailableBalance"] = $new_available_balance;
													$response["message"] = "Error in updating wallet for CashOut (Card) order # ".$fin_service_order_no;
												}
											}
											else {
											
												//Delete payment receipt
												$deletePaymentReceiptQuery = "delete from payment_receipt where p_receipt_id = ".$p_receipt_id;
												error_log("deletePaymentReceiptQuery = ".$deletePaymentReceiptQuery);
												$deletePaymentReceiptResult = mysqli_query($con, $deletePaymentReceiptQuery);
												if ( $deletePaymentReceiptResult) {
													error_log("successfully deleted entry in payment_receipt for p_receipt_id = ".$p_receipt_id);
												}else {
													error_log("error in deleting entry in payment_receipt for p_receipt_id = ".$p_receipt_id);
												}
													
												$response["result"] = "Failure";
												$response["signature"] = $server_signature;
												$response["newAvailableBalance"] = $new_available_balance;
												$response["message"] = "Error in getting acc_trans_typet for CashOut (Card) order # ".$fin_service_order_no;
											}
										}
										else {
											//Delete payment receipt
											$deletePaymentReceiptQuery = "delete from payment_receipt where p_receipt_id = ".$p_receipt_id;
											error_log("deletePaymentReceiptQuery = ".$deletePaymentReceiptQuery);
											$deletePaymentReceiptResult = mysqli_query($con, $deletePaymentReceiptQuery);
											if ( $deletePaymentReceiptResult) {
												error_log("successfully deleted entry in payment_receipt for p_receipt_id = ".$p_receipt_id);
											}else {
												error_log("error in deleting entry in payment_receipt for p_receipt_id = ".$p_receipt_id);
											}
											$response["result"] = "Failure";
											$response["message"] = "Error in jounral_entry for CashOut (Card) order # ".$fin_service_order_no;
										}
                                                			}
                                    					
								}else {
									//Error Order
									$delete_query = "delete from fin_service_order where fin_service_order_no = ".$fin_service_order_no;
									error_log("Delete_query for Card Payment: ".$delete_query);
									$delete_result = mysqli_query($con, $delete_query);
									if ( $delete_result ) {
										error_log("successful delete from fin_service_order table for fin_service_order_no = ".$fin_service_order_no);
									}else {
										error_log("error in delete from fin_service_order table for fin_service_order_no = ".$fin_service_order_no." - ".mysqli_error($con));
									}

									//$updatequery = "UPDATE fin_request SET status = 'X', comments = '$comment', approver_comments = '$approverComment', update_time = now() WHERE fin_request_id = ".$fin_request_id;
									$updatequery = "UPDATE fin_request SET status = 'X', approver_comments = '$approverComment', update_time = now() WHERE fin_request_id = ".$fin_request_id;
									error_log("updatequery = ".$updatequery);
									$update_result = mysqli_query($con, $updatequery);
									if ( $update_result ) {
										$response["message"] = "CashOut (Card) order is rejected for Order # ".$fin_service_order_no;
										$response["result"] = "Success";
										$response["serverUpdate"] = "Y";
										$response["orderNo"] = $orderNo;
										$response["orderType"] = $orderType;
										$response["signature"] = $server_signature;
										$response["newAvailableBalance"] = 0;
										error_log("Purchase order is rejected for Order # ".$fin_service_order_no);
									}
									else {
										$response["message"] = "Error: Cashout (Card) order rejected for Order # ".$fin_service_order_no;
										$response["result"] = "Failure";
										$response["signature"] = $server_signature;
										error_log("Error: Purchase rejected for order # ".$fin_service_order_no);
									}								
								}
                            				}else {
								error_log("Skipping...Duplicate order for ".$orderNo." for user_id = ".$user_id);
								$response["message"] = "Duplicate: CashOut (Card) order skipped for Order # ".$orderNo;
								$response["result"] = "Failure";
								$response["signature"] = $server_signature;
							}
						}
						else {
							$response["result"] = "Failure";
							$response["message"] = "No Purchase order for fin_request_id = ".$orderNo;
						}
					}
                		} else if ("Fund Wallet" == $orderType ) {
                    			$acc_trans_type = "PAYMT";
                    			$info1 = $comment;
                    			$info2 = $approverComment;
                    			$reference_no = $acc_trans_type."#".$orderNo;
                    			$check_payment_result = checkForAlreadyProcessedFundWalletOrder($userId, $reference_no, $con);
                    			error_log("check_payment_result = ".$check_payment_result);
                    			if ( $check_payment_result == 0 ) {
                    	    			if ( "00" == $mPosResponseCode ) {
                            				$p_receipt_id = generate_seq_num(1100, $con);
                            				$glComment = "Fund Wallet (Card) #".$orderNo;
                            				$insertDepositQuery = "INSERT INTO payment_receipt(p_receipt_id, country_id, payment_date, party_code, party_type, payment_reference_no, payment_type, payment_amount, ams_charge, partner_charge, other_charge, stamp_charge, payment_approved_amount, payment_approved_date, payment_source, payment_status, create_user, create_time, comments, approver_comments, info1, info2) VALUES($orderNo, $countryId, now(), '$orderPartyCode', '$orderPartyType', '$reference_no', 'CP', $orderAmount, $serviceCharge, 0, $otherCharge, $stampCharge, $requestAmount, now(), 'F', 'A', $userId, now(), '$glComment', 'Card Payment using mPos', '$info1', '$info2')";
                            				error_log("payment_receipt insert_query: ".$insertDepositQuery);
                            				$insertDepositResult = mysqli_query($con, $insertDepositQuery);
                            				if ( $insertDepositResult ) {
                            	    				error_log("Success in inserting Fund Wallet receipt for payment Id = ".$orderNo);
                                				$journal_entry_id = process_glentry($acc_trans_type, $orderNo, $orderPartyCode, $orderPartyType, $orderParentCode, $orderParentType, $glComment, $requestAmount, $userId, $con);
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
                                        					$update_wallet = walletupdateWithTransaction($acc_trans_type, $cb_factor, $orderPartyType, $orderPartyCode, $requestAmount, $con, $userId, $journal_entry_id);
                                        					if($update_wallet == 0) {
                                            						$gl_post_return_value = process_glpost($journal_entry_id, $con);
                                            						if ( $gl_post_return_value == 0 ) {
                                                						error_log("Success in Fund Wallet gl_post for: ".$journal_entry_id.", payment Order = ".$orderNo);
                                            						}else {
                                                						insertjournalerror($user_id, $journal_entry_id, $acc_trans_type, "AP", "W", "N", $requestAmount, $con);
                                                						error_log("Error in Fund Wallet gl_post for: ".$journal_entry_id.", orderNo = ".$orderNo);
                                            						}
                                            						$new_available_balance = check_party_available_balance($orderPartyType, $userId, $con);
                                            						error_log("new_available_balance for userId [".$userId."] = ".$new_available_balance);
                                            						$response["result"] = "Success";
                                            						$response["orderType"] = $orderType;
                                            						$response["orderNo"] = $orderNo;
                                            						$response["serverUpdate"] = "Y";
                                            						$response["signature"] = $server_signature;
                                            						$response["newAvailableBalance"] = $new_available_balance;
                                            						$response["message"] = "Success in updating wallet for Fund Wallet order # ".$orderNo;
                                        					}else {
                                            						$response["result"] = "Failure";
                                            						$response["orderType"] = $orderType;
                                            						$response["orderNo"] = $orderNo;
                                            						$response["serverUpdate"] = "N";
                                            						$response["signature"] = $server_signature;
                                            						$response["newAvailableBalance"] = $new_available_balance;
                                            						$response["message"] = "Error in updating wallet for Fund Wallet order # ".$orderNo;
                                        					}
                                    					}else {
                                        					$response["result"] = "Failure";
                                        					$response["orderType"] = $orderType;
                                        					$response["orderNo"] = $orderNo;
                                        					$response["serverUpdate"] = "N";
                                        					$response["signature"] = $server_signature;
                                        					$response["newAvailableBalance"] = $new_available_balance;
                                        					$response["message"] = "Error in getting acc_trans_typet for Fund Wallet order # ".$orderNo;
                                    					}
                                				}
                                				else {
                                    					$response["result"] = "Failure";
                                    					$response["message"] = "Error in jounral_entry for Fund Wallet order # ".$orderNo;
                                				}
                            				}else {
                                				error_log("Error in inserting Fund Wallet receipt for payment Id = ".$orderNo);
                                				error_log("May be duplicate for orderNo = ".$orderNo.", error desc = ".mysqli_error($con));
                                				$response["result"] = "Failure";
                                				$response["message"] = "Error in inserting payments for Fund Wallet order # ".$orderNo;
                            				}
                        			}else {
                            				$response["result"] = "Failure";
                            				$response["message"] = "Error response code for Fund Wallet order # ".$orderNo;
                        			}
                    			}else {
                        			error_log("Skipping...Duplicate Fund Wallet for ".$reference_no." for user_id = ".$user_id);
                        			$response["message"] = "Error: Fund Wallet order skipped for Payment Reference # ".$reference_no;
                        			$response["result"] = "Failure";
                        			$response["signature"] = $server_signature;
                    			}
                		}else {
                    			$response["result"] = "Failure";
				    	$response["message"] = "Invalid Order Type";
                		}
			}
			else {
				$response["result"] = "Failure";
				$response["message"] = "Invalid signature";
			}
		}
		else {
			$response["result"] = "Failure";
			$response["message"] = "Invalid data";
		}
	}
	else {
		$response["result"] = "Failure";
		$response["message"] = "Invalid request";
	}
    	error_log("order_update ==> ".json_encode($response));
	echo json_encode($response);

    function check_feature_value($userId, $country, $state, $partyCount, $product, $partner, $requestedAmount, $txtype, $con) {
		
        $res = -1;
        $query = "SELECT get_feature_value_new($country, $state, null, $product, $partner, $requestedAmount, '$txtype', $partyCount, null, null, $userId,-1) as res";
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
