<?php
   	include('../common/admin/configmysql.php');
   	include ("get_prime.php");	
   	include ("functions.php");
	require_once ("AesCipher.php");
	error_log("inside posapi/betting_order_update.php");

	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;
	$journal_entry_error = "N";

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		error_log("inside post request method");
		$data = json_decode(file_get_contents("php://input"));
		error_log("order_update <== ".json_encode($data));	

		if ( isset($data -> orderNo ) && !empty($data -> orderNo) &&  isset($data -> status ) && !empty($data -> status) 
 		    && isset($data -> userId ) && !empty($data -> userId) 
    		&& isset($data -> partyCode ) && !empty($data -> partyCode) && isset($data -> partyType ) && !empty($data -> partyType) 
            && isset($data -> countryId ) && !empty($data -> countryId) && isset($data -> stateId ) && !empty($data -> stateId) 
			&& isset($data -> signature ) && !empty($data -> signature) && isset($data -> key1 ) && !empty($data -> key1 )) {
			
			error_log("inside all inputs are set correctly");
			$signature = $data -> signature;
			$countryId = $data -> countryId;
			$stateId = $data -> stateId;
			$orderNo = $data -> orderNo;
			$userId = $data -> userId;
			$status = $data-> status;
			$key1 = $data -> key1;
			$orderPartyCode = $data -> partyCode;
			$orderPartyType = $data -> partyType;
		
			date_default_timezone_set('Africa/Lagos');
			$nday = date('z')+1;
			$nyear = date('Y');
			$nth_day_prime = get_prime($nday);
			$nth_year_day_prime = get_prime($nday+$nyear);
			$local_signature = $nday + $nth_day_prime;
			$server_signature = $nth_year_day_prime + $nday + $nyear;
			error_log("local_signature = ".$local_signature.", server_signature = ".$server_signature);
			if ( $local_signature == $signature ){	
				error_log("inside local_signature == signature");
				$validate_result = validateKey3($key1, $local_signature, $con);
				error_log("validateKey3 result = ".$validate_result);
				if ( $validate_result != 0 ) {
					// Invalid key1
					$response["statusCode"] = "990";
					$response["result"] = "Error";
					$response["message"] = "Failure: Invalid User";
					$response["signature"] = 0;
					error_log(json_encode($response));
					echo json_encode($response);
					return;
				}

				if ( $status == 'F') {
					$statusComments = 'FAILED';
					$select_query_bp_request = "select user_id, bp_transaction_id, total_amount, order_no from bp_request where order_no = $orderNo";
					$select_query_bp_comm = "select ifnull(sum(charge_value),0) as totalAmount from bp_service_order_comm where bp_service_order_no = $orderNo";
					error_log("select_query_bp_request = ".$select_query_bp_request);
					error_log("select_query_bp_comm = ".$select_query_bp_comm);
					$select_result = mysqli_query($con, $select_query_bp_request);
					$select_comm_result = mysqli_query($con, $select_query_bp_comm);
					$row = mysqli_fetch_assoc($select_result);
					$row1 = mysqli_fetch_assoc($select_comm_result);
					$userId = $row['user_id'];
					$total_amount = $row['total_amount'];
					$transaction_id = $row['bp_transaction_id'];
					$bpServiceOrderNo = $row['order_no'];
					$commission_amount = $row1['totalAmount'];
					error_log("userId = ".$userId.", total_amount = ".$total_amount.", transaction_id = ".$transaction_id.", bpServiceOrderNo = ".$bpServiceOrderNo.", commissionAmount = ".$commissionAmount);
					if($select_result){
						$select_agent_code_query = "select agent_code, parent_code, parent_type from agent_info where user_id = $userId";
						error_log("select_agent_code_query = ".$select_agent_code_query);
						$select_agent_code_result = mysqli_query($con,$select_agent_code_query);
						$row = mysqli_fetch_assoc($select_agent_code_result);
						$partycode = $row['agent_code'];
						$parentcode = $row['parent_code'];
						$parenttype = $row['parent_type'];
						if($select_agent_code_result){
							$acc_trans_type = 'BRVL1';
							$firstpartycode = $partycode;
							$firstpartytype = "A";
							$secondpartycode = $parentcode;
							$secondpartytype = $parenttype;
							$narration = "BILLPAY-ORDER NO: ".$bpServiceOrderNo;
							if( $secondpartycode == "" || empty($secondpartycode) || $secondpartycode = null ) {
								$secondpartycode = "";
								$secondpartytype = "";
							}
							else {
								$secondpartytype = substr($secondpartycode, 0);
							}
							$journal_entry_id = process_glentry($acc_trans_type, $bpServiceOrderNo, $firstpartycode, $firstpartytype, $secondpartycode, $secondpartytype, $narration, $total_amount, $userId, $con);
							error_log("journal_entry_id = ".$journal_entry_id);
							if ($journal_entry_id > 0) {
								$get_acc_trans_type = getAcccTransType($acc_trans_type, $con);
								error_log("get_acc_trans_type = ".$get_acc_trans_type);	
								if($get_acc_trans_type != "-1"){
									$split = explode("|",$get_acc_trans_type);
									$ac_factor = $split[0];
									$cb_factor = $split[1];
									$acc_trans_type_id = $split[2];
									$update_wallet =  walletupdateWithTransaction($ac_factor, $cb_factor, $firstpartytype, $firstpartycode, $total_amount, $con, $userId, $journal_entry_id);
									if($update_wallet != 0) {
										$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
										if ( $gl_reverse_repsonse != 0 ) {
											//error_log("Error in BillPay gl_reverse for: ".$journal_entry_id);
											$insertJournalError = insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $total_amount, $con);
											if($insertJournalError > 0 ){
												error_log("Journal Error Table Inserted successfully");
												$response["statusCode"] = "800";
												$response["result"] = "Error";
												$response["message"] = "Failure: Journal Error Table Inserted successfully";
												$response["signature"] = 0;
											}else {
												error_log("Journal Error Table Inserted Failed");
												$response["statusCode"] = "810";
												$response["result"] = "Error";
												$response["message"] = "Failure: Journal Error Table Insert failed";
												$response["signature"] = 0;
											}
										}else {
											error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
											$response["statusCode"] = "820";
											$response["result"] = "Error";
											$response["message"] = "Failure: GL Reverse is complete";
											$response["signature"] = 0;
										}
									}else {
										$gl_post_return_value = process_glpost($journal_entry_id, $con);
										if ($gl_post_return_value == 0 ) {
											$acc_trans_type1 = "BCRVL";
											$journal_entry_com_id = process_comm_glentry($acc_trans_type1, $bpServiceOrderNo, $firstpartycode, $firstpartytype, $journal_entry_id, $commission_amount, $narration, $userId, $con);
											error_log("process_comm_glentry = ".$journal_entry_com_id);
											if($journal_entry_com_id > 0) {
												$get_acc_trans_type1 = getAcccTransType($acc_trans_type1, $con);
												error_log("get_acc_trans_type1 = ".$get_acc_trans_type1);
												 if($get_acc_trans_type1 != "-1"){
													$split = explode("|", $get_acc_trans_type1);
													$ac_factor1 = $split[0];
													$cb_factor1 = $split[1];
													$acc_trans_type_id1 = $split[2];
													$update_wallet1 = commWalletupdateWithTransaction($acc_trans_type1, $cb_factor1, $firstpartytype, $firstpartycode, $commission_amount, $con, $userId, $journal_entry_com_id);
													if($update_wallet1 == 0) {
														$gl_post_return_value1 = process_comm_glpost($journal_entry_com_id, $con);
														if ( $gl_post_return_value1 == 0 ) {
															$delete_bp_service_comm_query = "delete from bp_service_order_comm where bp_service_order_no = $orderNo";
															error_log("delete_bp_service_comm_query = ".$delete_bp_service_comm_query);
															$delete_bp_service_comm_result = mysqli_query($con, $delete_bp_service_comm_query);
															if($delete_bp_service_comm_result){
																$delete_bp_service_order_query = "delete from bp_service_order where bp_service_order_no = $orderNo";
																error_log("delete_bp_service_order_query = ".$delete_bp_service_order_query);
																$delete_bp_service_order_result = mysqli_query($con, $delete_bp_service_order_query);
																 if ($delete_bp_service_order_result){
																	$approver_comments ="Auto Treatment# ".$bpServiceOrderNo;
																	$update_bp_query = "update bp_request set status = 'E', approver_comments = '$approver_comments', update_time = now(), comments = '$statusComments' where order_no = $orderNo";
																	error_log("update_bp_query = ".$update_bp_query);
																	$update_bp_request_result = mysqli_query($con, $update_bp_query); 
																	if($update_bp_request_result){
																		error_log ("Success: Order Cancel is successful: ".$orderNo);
																		$response["statusCode"] = "0";
																		$response["result"] = "Success";
																		$response["message"] = "Success: Order Cancel is complete";
																		$response["signature"] = 0;
																	}else {
																		error_log ("Error in deleteing bp_service_order_comm: ".$orderNo);
																		$response["statusCode"] = "10";
																		$response["result"] = "Warning";
																		$response["message"] = "Warning: Order Cancel is partial complete, but status is not updated";
																		$response["signature"] = 0;
																	}
																}else {
																	error_log ("Error in deleteing bp_service_order: ".$orderNo);
																	$response["statusCode"] = "20";
																	$response["result"] = "Error";
																	$response["message"] = "Failure: Order Cancel is partial complete, but order records is not deleted";
																	$response["signature"] = 0;
																}
															}else {
																error_log ("Error in deleteing bp_service_order_comm: ".$orderNo);
																$response["statusCode"] = "30";
																$response["result"] = "Error";
																$response["message"] = "Failure: Order Cancel is partial complete, but Comm order records is not deleted";
																$response["signature"] = 0;
															}
														}else {
															error_log ("Error in process_comm_glpost: ".$orderNo);
															$response["statusCode"] = "40";
															$response["result"] = "Error";
															$response["message"] = "Failure: Order Cancel is not complete, Error in Comm GL Post";
															$response["signature"] = 0;
														}
													}else {
														error_log("Error in updating commission wallet: ".$orderNo);
														$gl_reverse_repsonse1 = process_comm_glreverse($journal_entry_com_id, $con);
														if ( $gl_reverse_repsonse1 != 0 ) {
															$insertJournalError = insertjournalerror($userId, $journal_entry_com_id, $acc_trans_type1, "AP", "W", "N", $commission_amount, $con);
															if($insertJournalError > 0 ){
																error_log("Error in inserting Journal Error table: ".$orderNo);
																$response["statusCode"] = "50";
																$response["result"] = "Error";
																$response["message"] = "Failure: Order Cancel is not complete, Error detaisl inserted in Journal Error Table";
																$response["signature"] = 0;
															}else {
																error_log("Journal Error Table Inserted Failed");
																$response["statusCode"] = "60";
																$response["result"] = "Error";
																$response["message"] = "Failure: Order Cancel is not complete, Error in inserting Journal Error Table";
																$response["signature"] = 0;
															}
														}else {
															error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id.", order_no = ".$orderNo);
															$response["statusCode"] = "60";
															$response["result"] = "Error";
															$response["message"] = "Failure: Order Cancel is not complete, Account Rollback Failed";
															$response["signature"] = 0;
														}
													}
												} else {
													error_log("Error Getting the Transaction Type for Journal Commission: ".$orderNo);
													$response["statusCode"] = "70";
													$response["result"] = "Error";
													$response["message"] = "Failure: Order Cancel is not complete, Error in getting transcation type";
													$response["signature"] = 0;
												}
											}else {
												error_log("Error in posting to comm GL Entry: ".$orderNo);
												$insertJournalError = insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AP", "W", "N", $total_amount, $con);
												if($insertJournalError > 0 ){
													error_log ("Journal Error Table Inserted successfully");
												 }else {
													error_log ("Journal Error Table Inserted Failed");
												}
												$response["statusCode"] = "80";
												$response["result"] = "Error";
												$response["message"] = "Failure: Order Cancel is not complete, Error in posting Comm GL Entry";
												$response["signature"] = 0;
											}
										}else {
											error_log("Error Posting the Status in Journal Entry: ".$orderNo);
											$response["statusCode"] = "90";
											$response["result"] = "Error";
											$response["message"] = "Failure: Order Cancel is not complete, Error in posting Journal Entry";
											$response["signature"] = 0;
										}
									}
								}else {
									error_log ("Error Getting the Main Transaction Type: ". $orderNo);
									$response["statusCode"] = "100";
									$response["result"] = "Error";
									$response["message"] = "Failure: Order Cancel is not complete, Error in getting Transaction Type";
									$response["signature"] = 0;
								}
							}else {
								error_log("DB Error in gettin journal entry id: ".$orderNo);
								$response["statusCode"] = "110";
								$response["result"] = "Error";
								$response["message"] = "Failure: Order Cancel is not complete, Error in getting journal_entry";
								$response["signature"] = 0;
							}
						}else {
							error_log("Error While Select the Agent Code: ".$orderNo);
							$response["statusCode"] = "120";
							$response["result"] = "Error";
							$response["message"] = "Failure: Order Cancel is not complete, Error in selecting agent_code";
							$response["signature"] = 0;
						}
					}else {
						error_log ("Error While selecting the Value in Bill Payment Request: ".$orderNo);
						$response["statusCode"] = "130";
						$response["result"] = "Error";
						$response["message"] = "Failure: Order Cancel is not complete, Error in selection bill_payment details";
						$response["signature"] = 0;
					}
				}		
       		}
			else {
				$response["statusCode"] = "300";
				$response["result"] = "Failure";
				$response["message"] = "Invalid signature";
				$response["signature"] = 0;
			}
		}
		else {
			$response["statusCode"] = "400";
			$response["result"] = "Failure";
			$response["message"] = "Invalid data";
			$response["signature"] = 0;
		}
	}
	else {
		$response["statusCode"] = "500";
		$response["result"] = "Failure";
		$response["message"] = "Invalid request";
	}
   	error_log("betting_order_update ==> ".json_encode($response));
	echo json_encode($response);

?>