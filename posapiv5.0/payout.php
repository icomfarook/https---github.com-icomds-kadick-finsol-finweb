<?php
	include('../common/admin/configmysql.php');
	include("../common/admin/finsol_crypt.php");
	include ("get_prime.php");	
	require_once ("AesCipher.php");
    	require_once("db_connect.php");
    	include ("functions.php");
	error_log("inside pcposapi/payout.php");
	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		error_log("inside post request method");
        	$data = json_decode(file_get_contents("php://input"));
        	error_log("payout <== ".json_encode($data));

		if(isset($data -> operation) && $data -> operation == 'PAYOUT_INSERT') {
			error_log("inside operation == PAYOUT_INSERT method");
            		if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
			   && isset($data->payOut->payOutType) && !empty($data->payOut->payOutType) 
			   && isset($data->payOut->payOutAmount) && !empty($data->payOut->payOutAmount) 
			   //&& isset($data->payOut->processingAmount) && !empty($data->payOut->processingAmount) 
			   && isset($data->payOut->totalAmount) && !empty($data->payOut->totalAmount) 
			   && isset($data->userId) && !empty($data->userId) 
			   && isset($data->payOut->partyCode) && !empty($data->payOut->partyCode) 
			   && isset($data->payOut->partyType) && !empty($data->payOut->partyType)
			   && isset($data->countryId) && !empty($data->countryId) 
			   && isset($data->stateId) && !empty($data->stateId) 
			){

				error_log("inside all inputs are set correctly");
				$payOutType = $data->payOut->payOutType;
				$payOutAmount = $data->payOut->payOutAmount;
				$processingAmount = $data->payOut->processingAmount;
				$totalAmount = $data->payOut->totalAmount;
				$bankId = $data->payOut->bankId;
				$bankName = $data->payOut->bankName;
				$bankCode = $data->payOut->bankCode;
				$userId = $data->userId;
				$partyCode = $data->payOut->partyCode;
				$partyType = $data->payOut->partyType;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature= $data->signature;
				$key1 = $data->key1;
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
                    			$validate_result = validateKey1($key1, $userId, $session_validity, 'Q', $con);
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
                    			$payout_request_id = generate_seq_num(1900, $con);
                    			if( $payout_request_id > 0 )  {
                        			$insert_payout_request_query = "INSERT into comm_payout_request (comm_payout_request_id, party_type, party_code, payout_type, bank_id, comm_payout_amount, processing_amount, comm_total_amount, status, create_user, create_time) values ($payout_request_id, '$partyType', '$partyCode', '$payOutType', $bankId, $payOutAmount, $processingAmount, $totalAmount, 'P', $userId, now())";
					    	error_log("insert_payout_request_query = ".$insert_payout_request_query);
					    	$insert_payout_request_result = mysqli_query($con, $insert_payout_request_query);
					    	if($insert_payout_request_result) {
							error_log("$insert_payout_request_query is success");
						    
						    	//Check the wallet balance
						    	$wallet_balance_query = "select agent_comm_wallet_id from agent_comm_wallet where agent_code = '$partyCode' and available_balance >= $totalAmount";
						    	error_log("wallet_balance_query = ".$wallet_balance_query);
						    	$wallet_balance_result = mysqli_query($con, $wallet_balance_query);
						    	if ( $wallet_balance_result ) {
						    		$wallet_balance_count = mysqli_num_rows($wallet_balance_result);
						    		if ( $wallet_balance_count > 0 ) {
						    			$update_payout_query = "update comm_payout_request set status = 'I', update_user = $userId, update_time = now() where comm_payout_request_id = $payout_request_id";
						    			error_log("update_payout_query = ".$update_payout_query);
						    			$update_payout_result = mysqli_query($con, $update_payout_query);
						    			if( $update_payout_result ) {
						    				$acc_trans_type1 = "CPYTW";
						    				$acc_trans_type2 = "CPYFW"; 
						    				$from_comment = "Payout from Comm Wallet #".$payout_request_id;
						    				$to_comment = "Payout to Main Wallet #".$payout_request_id;
						    				$journal_entry_id = 0;
						    				$journal_entry_com_id = 0;
						    				$journal_entry_com_id = process_comm_glentry($acc_trans_type1, $payout_request_id, $partyCode, $partyType, $journal_entry_id, $totalAmount, $from_comment, $userId, $con);
										error_log("process_comm_glentry = ".$journal_entry_com_id);
                                    						if ( $journal_entry_com_id == -1 ) {
											error_log("Error in getting journal_entry_com_id..trying one more time.");
											$journal_entry_com_id = process_comm_glentry($acc_trans_type1, $payout_request_id, $partyCode, $partyType, $journal_entry_id, $totalAmount, $from_comment, $userId, $con);
											error_log("process_comm_glentry = ".$journal_entry_com_id);
										}
                                    						if($journal_entry_com_id > 0) {
                                    							$get_acc_trans_type1 = getAcccTransType($acc_trans_type1, $con);
											error_log("get_acc_trans_type1 = ".$get_acc_trans_type1);
											if($get_acc_trans_type1 != "-1"){
												$split = explode("|", $get_acc_trans_type1);
												$ac_factor1 = $split[0];
											        $cb_factor1 = $split[1];
											        $acc_trans_type_id1 = $split[2];
											        $update_wallet1 = commWalletupdateWithTransaction($acc_trans_type1, $cb_factor1, $partyType, $partyCode, $totalAmount, $con, $userId, $journal_entry_com_id);
                                            							if($update_wallet1 == 0) {
                                            								$journal_entry_id = process_glentry($acc_trans_type2, $payout_request_id, $partyCode, $partyType, $parentCode, $parentType, $to_comment, $payOutAmount, $userId, $con);
													error_log("process_glentry journal_entry_id = ".$journal_entry_id);
													if ( $journal_entry_id == -1 ) {
														error_log("Error in getting journal_entry_id..trying one more time.");
														$journal_entry_id = process_glentry($acc_trans_type2, $payout_request_id, $partyCode, $partyType, $parentCode, $parentType, $to_comment, $payOutAmount, $userId, $con);
														error_log("process_glentry journal_entry_id = ".$journal_entry_id);
													}
													if($journal_entry_id > 0) {
														$get_acc_trans_type2 = getAcccTransType($acc_trans_type2, $con);
														error_log("get_acc_trans_type2 = ".$get_acc_trans_type2);
														if($get_acc_trans_type2 != "-1"){
															$split = explode("|", $get_acc_trans_type2);
															$ac_factor2 = $split[0];
															$cb_factor2 = $split[1];
															$acc_trans_type_id2 = $split[2];
															$update_wallet2 = walletupdateWithTransaction($acc_trans_type2, $cb_factor2, $partyType, $partyCode, $payOutAmount, $con, $userId, $journal_entry_id);
                                            										if($update_wallet2 == 0) {
																$gl_post_return_value1 = process_comm_glpost($journal_entry_com_id, $con);
																if ( $gl_post_return_value1 == 0 ) {
																    error_log("Success in payout1 gl_comm_post for: ".$journal_entry_com_id);
																}else {
																    insertjournalerror($user_id, $journal_entry_com_id, $get_acc_trans_type1, "AP", "W", "N", $totalAmount, $con);
																    error_log("Error in payout1 gl_comm_post for: ".$journal_entry_com_id);
																}
																$gl_post_return_value2 = process_glpost($journal_entry_id, $con);
																if ( $gl_post_return_value2 == 0 ) {
																    error_log("Success in payout2 gl_post for: ".$journal_entry_id);
																}else {
																    insertjournalerror($user_id, $journal_entry_id, $get_acc_trans_type1, "AP", "W", "N", $totalAmount, $con);
																    error_log("Error in payout2 gl_post for: ".$journal_entry_id);
																}
                                            											$update_payout_query2 = "update comm_payout_request set status = 'S', update_user = $userId, update_time = now() where comm_payout_request_id = $payout_request_id";
													    			error_log("update_payout_query2 = ".$update_payout_query2);
													    			$update_payout_result2 = mysqli_query($con, $update_payout_query2);
						    										if( $update_payout_result2 ) {
						    										
						    											$new_available_balance = check_party_available_balance($partyType, $userId, $con);
																	error_log("new_available_balance for userId [".$userId."] = ".$new_available_balance);
                                            											
																	$response["result"] = "Success";
																	$response["message"] = "Your Payout Request #".$payout_request_id." is submitted";
																	$response["statusCode"] = 0;
																	$response["signature"] = $server_signature;
																	$response["availableBalance"] = $new_available_balance;
																	$response["payOutRequestId"] = $payout_request_id;
																}else {
																	$response["result"] = "Error";
																	$response["message"] = "Your Payout Request #".$payout_request_id." encountered error. Contact Kadick Admin";
																	$response["statusCode"] = 100;
																	$response["signature"] = $server_signature;
																	$response["availableBalance"] = 0;
						    											$response["payOutRequestId"] = $payout_request_id;	
																}
                                            										}else {
                                            											$response["result"] = "Error";
																$response["message"] = "Your Payout Request #".$payout_request_id." encountered error. Contact Kadick Admin";
																$response["statusCode"] = 110;
																$response["signature"] = $server_signature;
																$response["availableBalance"] = 0;
						    										$response["payOutRequestId"] = $payout_request_id;
                                            										}
                                            									}else {
                                            										$response["result"] = "Error";
															$response["message"] = "Your Payout Request #".$payout_request_id." encountered error in acc code2. Contact Kadick Admin.";
															$response["statusCode"] = 120;
															$response["signature"] = $server_signature;
															$response["availableBalance"] = 0;
						    									$response["payOutRequestId"] = $payout_request_id;
                                            									}
                                            								}else {
                                            									$response["result"] = "Error";
														$response["message"] = "Your Payout Request #".$payout_request_id." encountered error in journal entry. Contact Kadick Admin.";
														$response["statusCode"] = 130;
														$response["signature"] = $server_signature;
														$response["availableBalance"] = 0;
						    								$response["payOutRequestId"] = $payout_request_id;
                                            								}
                                            							}else {
                                            								$response["result"] = "Error";
													$response["message"] = "Your Payout Request #".$payout_request_id." encountered error. Contact Kadick Admin.";
													$response["statusCode"] = 140;
													$response["signature"] = $server_signature;
													$response["availableBalance"] = 0;
						    							$response["payOutRequestId"] = $payout_request_id;
                                            							}
                                            						}else {
                                            							$response["result"] = "Error";
												$response["message"] = "Your Payout Request #".$payout_request_id." encountered error in acc code1. Contact Kadick Admin.";
												$response["statusCode"] = 150;
												$response["signature"] = $server_signature;
												$response["availableBalance"] = 0;
						    						$response["payOutRequestId"] = $payout_request_id;
                                            						}
                                            					}else{
                                            						$response["result"] = "Error";
											$response["message"] = "Your Payout Request #".$payout_request_id." encountered error in journal entry. Contact Kadick Admin.";
											$response["statusCode"] = 160;
											$response["signature"] = $server_signature;
											$response["availableBalance"] = 0;
						    					$response["payOutRequestId"] = $payout_request_id;
                                            					}
                                    					}else {
						    				$response["result"] = "Error";
										$response["message"] = "Error in updating Payout Request Status";
										$response["statusCode"] = 170;
										$response["signature"] = $server_signature;
										$response["availableBalance"] = 0;
						    				$response["payOutRequestId"] = 0;
						    			}
						    		}else {
						    			$response["result"] = "Error";
									$response["message"] = "Insufficent Wallet Balance";
									$response["statusCode"] = 180;
									$response["signature"] = $server_signature;
									$response["availableBalance"] = 0;
						    			$response["payOutRequestId"] = 0;
						    		}
							}else {
						    		$response["result"] = "Error";
								$response["message"] = "Error in selecting wallet balance";
								$response["statusCode"] = 190;
								$response["signature"] = $server_signature;
								$response["availableBalance"] = 0;
						    		$response["payOutRequestId"] = 0;
						    	}
						}
						else {
						    $response["result"] = "Error";
						    $response["message"] = "Error in submitting your Payout Request";
						    $response["statusCode"] = "100";
						    $response["signature"] = $server_signature;
						    $response["availableBalance"] = 0;
						    $response["payOutRequestId"] = 0;
						}
					}
					else {
						// DB failure
						$response["result"] = "Error";
						$response["statusCode"] = "200";
						$response["message"] = "Failure: Error in getting Payout Request no";
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
        	else if(isset($data -> operation) && $data -> operation == 'PAYOUT_PREP') {
			error_log("inside operation == PAYOUT_PREP method");
		   	if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
				&& isset($data->userId) && !empty($data->userId) 
				&& isset($data->payOut->partyCode) && !empty($data->payOut->partyCode) 
				&& isset($data->payOut->partyType) && !empty($data->payOut->partyType)
				&& isset($data->countryId) && !empty($data->countryId) 
				&& isset($data->stateId) && !empty($data->stateId) 
			){
		
				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->payOut->partyCode;
				$partyType = $data->payOut->partyType;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature= $data->signature;
				$key1 = $data->key1;
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
					$validate_result = validateKey1($key1, $userId, $session_validity, 'X', $con);
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
		                    	$select_link_account_query = "select a.bank_master_id, a.name, a.cbn_short_code from bank_master a, party_bank_account b where b.bank_master_id = a.bank_master_id and b.active = 'Y' and b.status = 'A' and b.party_type = '$partyType' and b.party_code = '$partyCode' order by b.create_time";
					error_log("select_link_account_query = ".$select_link_account_query);
					$select_link_account_result = mysqli_query($con, $select_link_account_query);
					$response["linkAccounts"] = array();
					if($select_link_account_result) {
						while($select_link_account_row = mysqli_fetch_assoc($select_link_account_result)) {
							$bank = array();
							$bank['id'] = $select_link_account_row['bank_master_id'];
							$bank['name'] = $select_link_account_row['name'];
							$bank['code'] = $select_link_account_row['cbn_short_code'];
							array_push($response["linkAccounts"], $bank);
		                        	}
		                        	$comm_wallet_available_balance = check_party_comm_wallet_balance($partyCode, $partyType, $con);
		                        	$response["commWalletAmount"] = $comm_wallet_available_balance;
		                        	$response["processingAmountType"] = "A";
		                        	$response["processingAmount"] = "0.00";
		                        	$response["result"] = "Success";
						$response["statusCode"] = "0";
						$response["message"] = "Success: Your request is processed";
						$response["signature"] = $server_signature;
		                        }		                        
					else {
						// DB failure
						$response["result"] = "Error";
						$response["statusCode"] = "200";
						$response["message"] = "Failure: Error in getting payout prep request";
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
        	else if(isset($data -> operation) && $data -> operation == 'PAYOUT_FIND') {
			error_log("inside operation == PAYOUT_FIND method");
			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
				&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
				&& isset($data->partyType) && !empty($data->partyType) && isset($data->dateValue) && !empty($data->dateValue) 
				&& isset($data->status) && !empty($data->status) && isset($data->countryId) && !empty($data->countryId)
				&& isset($data->stateId) && !empty($data->stateId) 
			){

				error_log("inside all inputs are set correctly");
	            		$status = $data->status;
				$dateValue = $data->dateValue;
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
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
                    			$validate_result = validateKey1($key1, $userId, $session_validity, 'A', $con);
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
                    			if ( $status == "A") {
                        			$select_payout_request_query = "select a.comm_payout_request_id, a.party_type, a.party_code, a.payout_type, ifnull(a.bank_id,'') as bank_id, ifnull(b.name, '-') as bank_name, ifnull(b.cbn_short_code,'-') as bank_code, a.comm_payout_amount, a.processing_amount, a.comm_total_amount, a.status, a.create_time, ifnull(a.update_time, '-') as update_time from comm_payout_request a left join bank_master b on a.bank_id = b.bank_master_id where a.party_code = '$partyCode' and a.party_type = '$partyType' and date(a.create_time) = '$dateValue' and a.create_user = $userId order by a.create_time desc";
					}else {
						$select_payout_request_query = "select a.comm_payout_request_id, a.party_type, a.party_code, a.payout_type, ifnull(a.bank_id,'') as bank_id, ifnull(b.name, '-') as bank_name, ifnull(b.cbn_short_code,'-') as bank_code, a.comm_payout_amount, a.processing_amount, a.comm_total_amount, a.status, a.create_time, ifnull(a.update_time, '-') as update_time from comm_payout_request a left join bank_master b on a.bank_id = b.bank_master_id where a.party_code = '$partyCode' and a.party_type = '$partyType' and date(a.create_time) = '$dateValue' and a.create_user = $userId and status = '$status' order by a.create_time desc";
					}
					error_log("select_payout_request_query = ".$select_payout_request_query);
					$select_payout_request_query = mysqli_query($con, $select_payout_request_query);
                   			$response["payOuts"] = array();
					if ( $select_payout_request_query ) {
						while($select_payout_request_row = mysqli_fetch_assoc($select_payout_request_query)) {
							$payout = array();
							$payout['payoutRequestId'] = $select_payout_request_row['comm_payout_request_id'];
							$payout['partyCode'] = $select_payout_request_row['cms_type'];
							$payout['partyType'] = $select_payout_request_row['category'];
							$payout['payOutType'] = $select_payout_request_row['payout_type'];
							$payout['bankName'] = $select_payout_request_row['bank_name'];
							$payout['bankId'] = $select_payout_request_row['bank_id'];
							$payout['bankCode'] = $select_payout_request_row['bank_code'];
							$payout['payOutAmount'] = $select_payout_request_row['comm_payout_amount'];
							$payout['processingAmount'] = $select_payout_request_row['processing_amount'];
							$payout['totalAmount'] = $select_payout_request_row['comm_total_amount'];
							$payout['status'] = $select_payout_request_row['status'];
							$payout['createTime'] = $select_payout_request_row['create_time'];
							$payout['updateTime'] = $select_payout_request_row['update_time'];
							array_push($response["payOuts"], $payout);
						}
						$response["result"] = "Success";
						$response["message"] = "Your request is processed successfuly";
						$response["statusCode"] = 0;
						$response["signature"] = $server_signature;
					}
					else {
						$response["result"] = "Error";
						$response["message"] = "Error in find your payout details";
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
        	}else {
			// Invalid Operation
			$response["statusCode"] = "500";
			$response["result"] = "Error";
            	$response["message"] = "Failure: Invalid Operation";
            	$response["signature"] = 0;
		}
	}else {
		// Invalid Request Method
		$response["result"] = "success";
		$response["status"] = "600";
        	$response["message"] = "Post Failure";
        	$response["signature"] = 0;
	}
    	error_log("payout ==> ".json_encode($response));
	echo json_encode($response);
?>