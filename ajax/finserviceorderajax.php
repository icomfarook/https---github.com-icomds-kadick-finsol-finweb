<?php

	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	
	include('functions.php');
	$data = json_decode(file_get_contents("php://input"));	
	$action = $data->action;
	$reqAmount = $data->reqAmount;
	$product = $data->product;
	$finCode = $data->finCode;
	$totalfromclient = $data->total;	
	$product = $data->product;	
	$scemecharge =$data->scharge;	
	$proccharge =$data->pcharge;	
	$othercharge = $data->ocharge;	
	$customer = $data->customer;	
	$mobile_no = $data->mobile;	
	$authorization = $data->authorization;	
	$reference = $data->ref;	
	$comment = $data->comment;	
	$userType = 'A';
	//$userId = $_SESSION['user_id'];
	//$partycode = $_SESSION['party_code'];
	$userId = 1005;
	$partycode = 'AG0107';

	if($action == "chargefind"){
		
		error_log("inside finance order");
		$totalAmount = $reqAmount + $scemecharge + $proccharge + $othercharge;
		$journal_value = "";		
		if($profile_id == "41") {
			$journal_user_type = "L";
		}else {
			$journal_user_type = "N";
		}
		error_log("totalAmount".$totalAmount);
		error_log("totalfromclient".$totalfromclient);
		
		if(floatval($totalfromclient) == floatval(number_format($totalAmount,2))) {
		
			$selectProductQuery = "SELECT service_charge_factor, service_charge, paycenter_charge_factor, paycenter_charge, other_charge_factor, other_charge, fin_service_type_code FROM fin_service_config WHERE fin_service_type_code = '".$product."' and  start_value <= ".$reqAmount." and end_value >= ".$reqAmount." limit 1";
			error_log("selectProductQuery = ".$selectProductQuery);
			$selectProductResult = mysqli_query($con,$selectProductQuery);
			
			if(!$selectProductResult) {
				error_log('Error....in select product query...: ' . $selectProductQuery);
				die('Error....in select product query: ' . mysqli_error($con));
				error_log('Error....in select product query...: ' . mysqli_error($con));
			}else {
				error_log("inside selectProductQuery for product = ".$product." and request amount = ".$reqAmount);
				$row = mysqli_fetch_assoc($selectProductResult);
				$numRows = mysqli_num_rows($selectProductResult);
				if($numRows > 0) {
					$rowsCharge = $row['service_charge'];
					$scFactor = $row['service_charge_factor'];
					$rowpCharge = $row['paycenter_charge'];
					$pFactor = $row['paycenter_charge_factor'];
					$rowoCharge = $row['other_charge'];
					$oFactor = $row['other_charge_factor'];
					$fnCode = $row['fin_service_type_code'];	
					if($scFactor == "P") {
						$sCharge = round($reqAmount * $rowsCharge/100,2);
					}else {
						$sCharge = $rowsCharge;
					}
					if($pFactor == "P") {
						$pCharge = round($reqAmount * $rowpCharge/100,2);
					}else {
						$pCharge = $rowpCharge;
					}
					if($oFactor == "P") {
						$oCharge = round($reqAmount * $rowoCharge/100,2);
					}else {
						$oCharge = $rowoCharge;
					}
					if($scemecharge == $sCharge && $proccharge == $pCharge && $othercharge == $oCharge) {
						
						error_log("inside all charge check");
						//Getting Fin. Serivces Order No from sequence_num
						$orderNo =  generate_seq_num(1300, $con);
						error_log("finance service order Number =  ".$orderNo);
						
						//Getting available credit for branch/user from database
						$available_credit = check_available_credit_for_agent_by_user_id($userId, $con);						
						error_log("available query for $finCode = ".$available_credit);
						if ( $available_credit <= 0 || $available_credit == "" || $available_credit == null ) {
							error_log("Available Credit Not Available: ".$available_credit);
							$msg = "Available Credit Not Available. Contact Kadick Admin.";
							//die('Error....in select available_credit query: '.mysql_error());
						}else {
							$fnCode_desc = "";
							$paymentAmount = 0.00;
							if($fnCode == "TFR") { 
								//For Transfer, Finance Amount is only Service Charge + Other Charge
								$financeAmount = $sCharge + $oCharge;
								$paymentAmount = $sCharge + $oCharge;
								$journal_value = "FTFRO";
								$fnCode_desc = "Transfer";
							}else if ($fnCode == "CHI") {
								//For Cash-In, Finance Amount is all amounts (Requested Amount + Service Charge + Paycenter Charge + Other Charge
								$financeAmount = $reqAmount + $sCharge + $pCharge + $oCharge;
								$paymentAmount = 0.00;
								$journal_value = "FCHIO";
								$fnCode_desc = "Cash-In";
							}else if ($fnCode == "CHO") {
								//For Cash-Out, Finance Amount is only Service Charge + Other Charge
								$financeAmount = $sCharge + $oCharge;
								$paymentAmount = $reqAmount + $sCharge + $oCharge;
								$journal_value = "FCOSO";
								$fnCode_desc = "Cash-Out";
							}else if ($fnCode == "BPM") {
								//For BillPay-Cash, Finance Amount is all amounts (Requested Amount + Service Charge + Paycenter Charge + Other Charge
								$financeAmount = $reqAmount + $sCharge + $pCharge + $oCharge;
								$paymentAmount = 0.00;
								$journal_value = "FBPMO";
								$fnCode_desc = "BillPay-Cash";
							}else if($fnCode == "BPT") { 
								//For BillPay-Card, Finance Amount is Requested Amount + Service Charge + Other Charge
								$financeAmount = $reqAmount + $sCharge + $oCharge;
								$paymentAmount = $reqAmount + $sCharge + $oCharge;
								$journal_value = "FBPTO";
								$fnCode_desc = "BillPay-Card";
							}
							error_log("financeAmount =  ".$financeAmount);
							error_log("available_credit =  ".$available_credit);
							if( floatval($financeAmount) <= floatval($available_credit) ) {
							
								//Fin. Services [$fnCode_desc] order finance amount is within available credit
								$description = "Req.Amt:$reqAmount Svcs.Chg:$sCharge";
								$get_acc_trans_type = getAcccTransType($journal_value,$con);
								error_log("get_acc_trans_type ".$get_acc_trans_type);
								if($get_acc_trans_type != "-1") 
									$split = explode("|",$get_acc_trans_type);
									$ac_factor = $split[0];
									$cb_factor = $split[1];
									$acc_trans_type_id = $split[2];
									error_log("type  ".$type);
									error_log("split 0  ".$split[0]."split 1  ".$split[1]."split 2  ".$split[2]);
									$journal_entry_id  = insertjournalentry($journal_value,$acc_trans_type_id,$partycode,$description,$financeAmount,$con); 
									if($journal_entry_id >  0) { 
										$journal_entry_error = "N";
										error_log("journal_entry_id = ".$journal_entry_id." for Fin. Services [$fnCode_desc] order = ".$orderNo);
									}
									else {
										$journal_error_query = insertjournalerror($userId, $journal_value,$type, 'BE', 'S', 'N', $financeAmount, $con);
									}									
									
								//Prepare to insert into fin_service_order table
								$insertquery = "INSERT INTO fin_service_order(fin_service_order_no, fin_service_type_code, user_id, total_amount, request_amount, service_charge, paycenter_charge, other_charge, finance_amount, customer_name, mobile_no, date_time,  auth_code, reference_no, comment) VALUES ($orderNo, '$finCode', $userId, $totalAmount, $reqAmount, $scemecharge, $proccharge, $othercharge, $financeAmount, '$customer', '$mobile_no', now(), '$authorization', '$reference', '$comment')";
								error_log("insertquery".$insertquery);
								$insertResult = mysqli_query($con,$insertquery);
								/*if(!$insertResult){
									error_log("Error in inserting finance Service order");
									$msg = "Fin. Services [$fnCode_desc] Order [$orderNo] insert Failed: ".mysql_error();
									if ( $journal_entry_error == "N" ) {
										//journal_entry is success, then reverse it
										$journal_reverse_query = "select gl_reverse($journal_entry_id) as gl_reverse_result";
										$journal_reverse_result = mysqli_query($con,$journal_reverse_query);
										if ( $journal_reverse_result ) {
											$journal_reverse_result_row = mysqli_fetch_array($journal_reverse_result);
											$journal_reverse_result_code = $journal_reverse_result_row['gl_reverse_result'];
											error_log("journal_reverse_result_code = ".$journal_reverse_result_code);
											if($journal_reverse_result_code == 0) {
												//journal Reverse is success
												error_log("journal reversal is sucess due to insert error for Fin. Services [$fnCode_desc] order $orderNo");
											}else {
												//journal_reverse_error, log it in journal_error table
												$journal_error_query = "insert into journal_error(journal_error_id, branch_id, user_id, user_type, transaction_id, acc_trans_type_code, amount, error_code, error_type, create_date, status) values (0, $branch, $user, '$journal_user_type', $orderNo, 'FTFRO', $financeAmount, 'AE', 'S', now(), 'N')";
												error_log("journal_error_query = " + $journal_error_query);
												$journal_error_result = mysqli_query($con,$journal_error_query);
												if ( $journal_error_result ) {
													error_log("journal_error logged successfully");
												}else {
													error_log("Error: Not able to log BE journal_error - ".mysql_error());
												}
											}
										}else {
											//journal_reverse_error, log it in journal_error table
											$journal_error_query = "insert into journal_error(journal_error_id, branch_id, user_id, user_type, transaction_id, acc_trans_type_code, amount, error_code, error_type, create_date, status) values (0, $branch, $user, '$journal_user_type', $orderNo, 'FTFRO', $financeAmount, 'AE', 'S', now(), 'N')";
											error_log("journal_error_query = " + $journal_error_query);
											$journal_error_result = mysqli_query($con,$journal_error_query);
											if ( $journal_error_result ) {
												error_log("journal_error logged successfully");
											}else {
												error_log("Error: Not able to log AE journal_error - ".mysql_error());
											}
										}
									}else {
										error_log("insert journal_entry_error == Y and no action required for Fin. Services [$fnCode_desc] order $orderNo table insert error");
									}
								}else { */
									error_log("Success in inserting Fin. Services [$fnCode_desc] order $orderNo");
									if ( $journal_entry_error == "N" ) {
										$update =  transactionwalletupdate($ac_factor, $cb_factor,'A', $partycode, $financeAmount, $con, $userId,$journal_entry_id);
									}else {
										$update =  transactionwalletupdate($ac_factor, $cb_factor,'A', $partycode, $financeAmount, $con, $userId,$orderNo);
									}									
									if ($update < 0) {
										error_log("inside account setting update failure for Fin. Services [$fnCode_desc] order $orderNo");
										$msg = "Error: Fin. Services [$fnCode_desc] Order $orderNo due to failure in account update. Contact Kadick Admin.";
										if ( $journal_entry_error == "N" ) {
											/* error_log("inside journal_entry_errror = N");
											$journal_reverse_query = "select gl_reverse($journal_entry_id) as gl_reverse_result";
											$journal_reverse_result = mysqli_query($con,$journal_reverse_query);
											if ( $journal_reverse_result ) {
												$journal_reverse_result_row = mysqli_fetch_array($journal_reverse_result);
												$journal_reverse_result_code = $journal_reverse_result_row['gl_reverse_result'];
												error_log("journal_reverse_result_code = ".$journal_reverse_result_code);
												if($journal_reverse_result_code == 0) {
													//Journal Reverse is success
													error_log("Journal Reverse is done when account balance update failed for receipt_id = $id");
												}else {
													//journal_reverse_error, log it in journal_error table
													$journal_error_query = "insert into journal_error(journal_error_id, branch_id, user_id, user_type, transaction_id, acc_trans_type_code, amount, error_code, error_type, create_date, status) values (0, $branch, $user, '$journal_user_type', $orderNo, 'FTFRO', $financeAmount, 'AE', 'S', now(), 'N')";
													error_log("journal_error_query = " + $journal_error_query);
													$journal_error_result = mysqli_query($con,$journal_error_query);
													if ( $journal_error_result ) {
														error_log("journal_error logged successfully");
													}else {
														error_log("Error: Not able to log AE journal_error - ".mysql_error());
													}
												}
											}else {
												//journal_reverse_error, log it in journal_error table
												$journal_error_query = "insert into journal_error(journal_error_id, branch_id, user_id, user_type, transaction_id, acc_trans_type_code, amount, error_code, error_type, create_date, status) values (0, $branch, $user, '$journal_user_type', $orderNo, 'FTFRO', $financeAmount, 'AE', 'S', now(), 'N')";
												error_log("journal_error_query = " + $journal_error_query);
												$journal_error_result = mysqli_query($con,$journal_error_query);
												if ( $journal_error_result ) {
													error_log("journal_error logged successfully");
												}else {
													error_log("Error: Not able to log AE journal_error - ".mysql_error());
												}
											} */
										}																				
										//Remove Fin. Services Order because of balance update error
										$fin_services_order_delete_query = "delete from fin_service_order where fin_service_order_no = $orderNo";
										error_log("fin_services_order_delete_query = " + $fin_services_order_delete_query);
										$fin_services_order_delete_result = mysqli_query($con,$fin_services_order_delete_query);
										if ( $fin_services_order_delete_result ) {
											error_log("fin_service_order delete successful");
										}else {
											error_log("fin_service_order delete failure = ".mysql_error());
										}
         								}else {
										error_log("inside update Fin. Services - Transfer account setting success");
										/*if($userType == "L") {
											$selectAccountBalanceQuery = "SELECT a.previous_account_balance, b.branch_name FROM portal_user_setting a, portal_branch b WHERE a.branch_id = b.branch_id and a.user_id = ".$user;
										}else {
											$selectAccountBalanceQuery = "SELECT a.previous_account_balance, b.branch_name FROM portal_branch_setting a, portal_branch b WHERE a.branch_id = b.branch_id and a.branch_id = ".$branch;
										}
										error_log("selectAccountBalanceQuery = ".$selectAccountBalanceQuery);
										$selectAccountBalanceResult = mysqli_query($con,$selectAccountBalanceQuery);
										if($selectAccountBalanceResult){
											error_log("inside select previous account balance success");
											$selectAccountBalanceRow = mysqli_fetch_array($selectAccountBalanceResult);
											$PreviousAccountBalance = $selectAccountBalanceRow['previous_account_balance'];
											$available_credit = $selectAccountBalanceRow['available_credit'];
											$account_balance = $selectAccountBalanceRow['account_balance'];
											$branchName = $selectAccountBalanceRow['branch_name'];
											$_SESSION['available_credit'] = $available_credit;
											$_SESSION['account_balance'] = $account_balance;
										}else {
											error_log("inside select previous account balance failure");
										} */
												
										if ( $journal_entry_error == "N" ) {
										/*	$journalPostQuery  = "SELECT gl_post($journal_entry_id, $PreviousAccountBalance) as glpost";
											error_log("journalPostQuery = ".$journalPostQuery);
											$journalPostResult = mysqli_query($con,$journalPostQuery);
											if ( !$journalPostResult ) {
												error_log("inside select gl post failure");
												$journal_post_error = "Y";
												// Journal Post Error Happened, log it in journal_error table
												$journal_error_query = "insert into journal_error(journal_error_id, branch_id, user_id, user_type, transaction_id, acc_trans_type_code, amount, error_code, error_type, create_date, status) values (0, $branch, $user, '$journal_user_type', $orderNo, 'FTFRO ', $financeAmount, 'BP', 'S', now(), 'N')";
												error_log("journal_error_query = " + $journal_error_query);
												$journal_error_result = mysqli_query($con,$journal_error_query);
												if ( $journal_error_result ) {
													error_log("journal_error logged successfully");
												}else {
													error_log("Error: Not able to log BP journal_error - ".mysql_error());
										 		}
											} else { */
											//	error_log("inside select gl post success");
												/*$journalPostReturnRow = mysqli_fetch_array($journalPostResult);
												$journalPostReturnValue = $journalPostReturnRow['glpost'];
												error_log("journalPostReturnValue = ".$journalPostReturnValue);
												if($journalPostReturnValue != 0) {
													//journal_post_error, log it in journal_error table
													$journal_post_error = "Y";
													$journal_error_query = "insert into journal_error(journal_error_id, branch_id, user_id, user_type, transaction_id, acc_trans_type_code, amount, error_code, error_type, create_date, status) values (0, $branch, $user, '$journal_user_type', $orderNo, 'FTFRO ', $financeAmount, 'BP', 'S', now(), 'N')";
													error_log("journal_error_query = " + $journal_error_query);
													$journal_error_result = mysqli_query($con,$journal_error_query);
													if ( $journal_error_result ) {
														error_log("journal_error logged successfully");
													}else {
														error_log("Error: Not able to log BP journal_error - ".mysql_error());
													}
												}else {
													$journal_post_error = "N";
													error_log("successful Fin. services order with journal entry for journal_entry_id = $journal_entry_id");
												} 
											//}
										}*/
											
										if ( $finCode == "CHO" || $finCode == "TFR" || $finCode == "BPT" ) {
											//Insert into portal_deposit_receipt
											$insertDepositQuery = "INSERT INTO payment_receipt(country_id, p_receipt_id, payment_date, payment_amount, payment_reference_no, payment_type, payment_status,  create_user, create_time, comments,payment_account_id, party_code, party_type) VALUES (566,0, now(), $paymentAmount, '$finCode$orderNo', 'OT', 'P', $userId, now(), '$comment', 1,'$partycode','$userType')";
											error_log("insertDepositQuery for transfer = ".$insertDepositQuery);
											$insertDepositResult = mysqli_query($con,$insertDepositQuery);
											if(!$insertDepositResult){
												error_log("inside Fin. Services deposit receipt insert for transfer failure");
												$msg = "Fin. Services Tranfer Deposit insert failed for order# $orderNo";
												die('Error....deposit receipt insert '.mysql_error());
											}else {
												if ( $finCode == "CHO" ) {
													$msg = "Fin. Services [Cash-Out] order #$orderNo is successfully submitted.";
												}else if ( $finCode == "TFR" ) {
													$msg = "Fin. Services [Transfer] order #$orderNo is successfully submitted.";
												}else {
													$msg = "Fin. Services [BillPay-Card] order #$orderNo is successfully submitted.";
												}
											}
										}else {
											if ( $finCode == "CHI" ) {
												$msg = "Fin. Services [Cash-In] order #$orderNo is successfully submitted.";
											}else {
												$msg = "Fin. Services [BillPay-Cash] order #$orderNo is successfully submitted.";
											}
										}
										/*$select_seq_query = "SELECT get_sequence_num(2600) as postid";
										$select_seq_result = mysqli_query($con,$select_seq_query);
										if(!$select_seq_result) {
											$msg = "Error In POST query get sequence num ".die(mysql_error());
											error_log($msg." select_seq_query ".$select_seq_query);
										}	
										else {
											$postrow = mysqli_fetch_assoc($select_seq_result);
											$postid = $postrow['postid'];
											$SERVER_SHORT_NAME = SERVER_SHORT_NAME;
											$IN_FILE_NAME = "FC_".SERVER_SHORT_NAME."_".$branchName."_$orderNo.csv";													
											$post_ext_query = "INSERT INTO portal_ext_post(post_id, reference_no, reference_type, branch_name, in_folder_name, in_file_name, status, ai_test_flag, pic_point, create_time) VALUES($postid, $orderNo, 'FC','$branchName','$AI_IN_FOLDER_NAME','$IN_FILE_NAME','E','$AI_TEST_FLAG',1,now())";
											error_log("post_ext_query ".$post_ext_query);
											$post_ext_result = mysqli_query($con,$post_ext_query);
											if(!$post_ext_result) {
												$msg = "Error In post_ext_query ".die(mysql_error());
												error_log($msg."  post_ext_query ".$post_ext_query);
											}
										} */
									}	
								}
							}else {
								$msg = "InSufficient Available Credit.";
								error_log("Message =".$msg." Request Amount= ".$financeAmount." Available Credit = ".$available_credit);
							}
						}
					}else {
						$msg = "Client request error for charges. Try your request again !!";
						error_log("Message = ".$msg.": Client Svcs Charge = ".$scemecharge.", Server Svcs Charge = ".$sCharge.", Client Processing Charge = ".$proccharge.", Server Processing Charge = ".$pCharge.", Server Other Charge = ".$othercharge.", Client Other Charge = ".$oCharge);
					}
				} else {
					$msg = "No Config found for selected Prdouct [".$product."] and Request Amount [".$reqAmount.". Contact Kadick Admin.";
					error_log("Message =".$msg." Count = ".$numRows);
				}			
			}
		}else {
			$msg = "Invalid Client Request. Try your request again !!!";
			error_log("Message =".$msg." Total from client = ".$totalfromclient." request amount = ".$totalAmount);
		}
		echo "<p style='color:red;font-size:14px;margin:0px' id='Mess'>$msg</p>";
	}
?>