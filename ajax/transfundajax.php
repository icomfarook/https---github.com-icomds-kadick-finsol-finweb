<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	$data = json_decode(file_get_contents("php://input"));
	include ('./ajax/functions.php');
	$action = $data->action;
	$profile_id = $_SESSION['profile_id'];	
	$user_id = $_SESSION['user_id'];
	//$profile_id = 1;
	$creteria = $data->creteria;
	$agentCode = $data->agentCode;
	$childagentCode = $data->childagentCode;
	$parentwallet = $data->parentwallet;
	$availablebalance = $data->availablebalance;
	//$agentCode = $data->agentCode;
	//$agentCode = $data->agentCode;
	$agent_name	=   $_SESSION['party_name'];
	$partyCode = $_SESSION['party_code'];
	$group_type = $_SESSION['group_type'];
	error_log("agent_code".$agentCode);
	if($profile_id == 51) {
		if($action == "query") {
	  	$query = "";
		$tablename = "";
	
		$query = "SELECT current_balance,available_balance FROM agent_wallet WHERE agent_code = '$partyCode'";
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("parentwallet"=>$row['available_balance'],"curlbalance"=>$row['current_balance']);           
		}
		echo json_encode($data);
			
	}
	}
	else {
	if($action == "query") {
	    if($creteria == "P") {
		$query = "";
		$tablename = "";
	
		$query = "SELECT current_balance,available_balance FROM agent_wallet WHERE agent_code = '$agentCode'";
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("parentwallet"=>$row['available_balance'],"curlbalance"=>$row['current_balance']);           
		}
		echo json_encode($data);
	}
	else{
		$query = "SELECT ifNULL(current_balance,'-') as current_balance,ifNULL(available_balance,'-') as available_balance FROM agent_wallet WHERE agent_code = '$agentCode'";
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("parentwallet"=>$row['available_balance'],"curlbalance"=>$row['current_balance']);           
		}
		echo json_encode($data);
	}
		
	}
	
		
	}
	if($profile_id == 51) { 
	    if($action == "payout") {
		 $creteria = $data->creteria;
		 $agentCode = $data->agentCode;	
		 $transamnt = $data->transamnt;
		 $childagentCode = $data->childagentCode;		
		 $parentwallet = $data->parentwallet;
		 $partyCode = $_SESSION['party_code'];
		 $parent_code = $_SESSION['parent_code'];
		 $partyType = "A";
		    if($group_type == "P") {
			$wallet_balance_query="Select available_balance from agent_wallet where agent_code='$partyCode' and available_balance >= $transamnt";
			error_log("wallet_balance_query = ".$wallet_balance_query);
		    $wallet_balance_result = mysqli_query($con, $wallet_balance_query);
		    $wallet_balance_count = mysqli_num_rows($wallet_balance_result);
			if ( $wallet_balance_count > 0 ) {
					$seq_no_query = "SELECT get_sequence_num(3900) as seq_no";
					error_log("seq_no_query = ".$seq_no_query);
					$seq_no_result = mysqli_query($con, $seq_no_query);
					$seq_no_row = mysqli_fetch_assoc($seq_no_result);
					$seq_no = $seq_no_row['seq_no'];	
					error_log("seq_no".$seq_no);
				if($seq_no > 0 )  {
					    $parentagentquery = "Select parent_code,parent_type from agent_info where agent_code='$partyCode'";
							error_log("parent-agent_code".$parentagentquery);
							$parentagentresult = mysqli_query($con,$parentagentquery);
						 error_log("select_attachment_I_pre_query".$parentagentquery);
						 $row = mysqli_fetch_assoc($parentagentresult);
						 $parent_code = $row['parent_code'];
						 $parent_type = $row['parent_type'];
							$query = "INSERT INTO wallet_fund_transfer (wallet_fund_transfer_id, sender_partner_code, sender_partner_type, sender_wallet_type,receiver_partner_code,receiver_partner_type, receiver_wallet_type, transfer_amount, status, create_user, create_time) VALUES($seq_no, '$partyCode','$partyType','M','$childagentCode','$partyType','M', $transamnt, 'E', $user_id,now())";
							error_log("queyr".$query);
							$result =  mysqli_query($con,$query);
						if($result){
								$acc_trans_type1 = "GAWTF";
								$acc_trans_type2 = "GAWTT"; 
								$from_comment = "Fund Transfer From # ".$seq_no;
								$to_comment = "Fund Transfer To #".$seq_no;
								//$parentCode =Null;
								//$parentType ='C';
								$journal_entry_id1 = process_glentry($acc_trans_type1, $seq_no, $partyCode, $partyType, $parent_code, $parent_type, $from_comment, $transamnt, $user_id, $con);
								error_log("select_journal_entry1 = ".$journal_entry_id1);
								error_log("select_journal_entry1 = ".$journal_entry_id1);
                            if($journal_entry_id1 > 0) {
									$get_acc_trans_type1 = getAcccTransType($acc_trans_type1, $con);
									error_log("get_acc_trans_type1 = ".$get_acc_trans_type1);
								if($get_acc_trans_type1 != "-1"){
										$split = explode("|", $get_acc_trans_type1);
										$ac_factor1 = $split[0];
										$cb_factor1 = $split[1];
										$acc_trans_type_id1 = $split[2];
										$update_wallet1 = transactionwalletupdate($ac_factor1, $cb_factor1,$partyType, $partyCode, $transamnt, $con, $user_id, $journal_entry_id1);
                                    if($update_wallet1 == 0) {
											$journal_entry_id2 = process_glentry($acc_trans_type2, $seq_no, $partyCode, $partyType, $parent_code, $parent_type, $to_comment, $transamnt, $user_id, $con);
											error_log("select_journal_entry2 = ".$journal_entry_id2);
											error_log("select_journal_entry2 = ".$journal_entry_id2);
										if($journal_entry_id2 > 0) {
												$get_acc_trans_type2 = getAcccTransType($acc_trans_type2, $con);
												error_log("get_acc_trans_type2 = ".$get_acc_trans_type2);
											if($get_acc_trans_type2 != "-1"){
													$split = explode("|", $get_acc_trans_type2);
													$ac_factor2 = $split[0];
													$cb_factor2 = $split[1];
													$acc_trans_type_id2 = $split[2];
													$update_wallet2 =  transactionwalletupdate($ac_factor2, $cb_factor2,$partyType, $childagentCode, $transamnt, $con, $user_id, $journal_entry_id2);
												if($update_wallet2 == 0){
														$wallet_fund_query="Update wallet_fund_transfer set status='C' ,update_time=now() where wallet_fund_transfer_id = $seq_no";
														$wallet_fund_result=mysqli_query($con,$wallet_fund_query);
														error_log("wallet_fund_query".$wallet_fund_query);
													if(!$wallet_fund_result){
														$wallet_fund_update_query="Update wallet_fund_transfer set status='F' where wallet_fund_transfer_id = $seq_no";
														error_log("wallet_fund_update_query".$wallet_fund_update_query);
														$wallet_fund_update_result=mysqli_query($con,$wallet_fund_update_query);
													}else{
														$response = array();
														$response["msg"] = "Success";
														$response["responseCode"] = 200;
														$response["errorResponseDescription"] = "Payout Bank Cash Transferred  Successfully.";
														$response["errorResponseDescription"] = "Parent Wallet ".$partyCode." to Child ".$childagentCode." Cash Transferred  Successfully.";
													}
												}else{
													$response = array();
													$response["msg"] = "Your Payout Request #".$seq_no." encountered error. Contact Kadick Admin";
													$response["responseCode"] = 110;
													$response["errorResponseDescription"] = mysqli_error($con);
												}
														  
											}else{
												$response = array();
												$response["msg"] = "Your Payout Request #".$seq_no." encountered error in journal entry. Contact Kadick Admin.";
												$response["responseCode"] = 130;
												$response["errorResponseDescription"] = mysqli_error($con);
											} 	  
									}else{ 
										$response = array();
										$response["msg"] = "Your Payout Request #".$seq_no." encountered error. Contact Kadick Admin.";
										$response["responseCode"] = 140;
										$response["errorResponseDescription"] = mysqli_error($con);
							        } 
								}else{ 
										$response = array();
										$response["msg"] = "Your Payout Request #".$seq_no." encountered error. Contact Kadick Admin.";
										$response["responseCode"] = 140;
										$response["errorResponseDescription"] = mysqli_error($con);
							        } 
								
							}else{
									$response = array();
									$response["msg"] ="Your Payout Request #".$seq_no." encountered error in acc code1. Contact Kadick Admin.";
									$response["responseCode"] = 150;
									$response["errorResponseDescription"] = mysqli_error($con);
							    }       
				       
					}else{
								$response = array();
								$response["msg"] = "Your Payout Request #".$seq_no." encountered error in journal entry. Contact Kadick Admin.";
								$response["responseCode"] = 160;
								$response["errorResponseDescription"] = mysqli_error($con);
						    }
					
				}else {
					$response = array();
					$response["result"] = "Error";
					$response["msg"] = "Error in submitting your Payout Request";
					$response["responseCode"] = 170;
					$response["errorResponseDescription"] = mysqli_error($con);
					}
				
				}else {
					$response = array();
					$response["result"] = "Error";
					$response["msg"] = "Sequence No is Not Genereated";
					$response["responseCode"] = 180;
					$response["errorResponseDescription"] = mysqli_error($con);
					}
					
				
			
			}else {
					$response = array();
					$response["result"] = "Error";
					$response["msg"] = "Transaction Amount is Greater than Available Balance";
					$response["responseCode"] = 190;
					$response["errorResponseDescription"] = mysqli_error($con);
					}
					
			}else{
				$wallet_balance_query="Select available_balance from agent_wallet where agent_code='$partyCode' and available_balance >= $transamnt";
			error_log("wallet_balance_query = ".$wallet_balance_query);
		    $wallet_balance_result = mysqli_query($con, $wallet_balance_query);
		    $wallet_balance_count = mysqli_num_rows($wallet_balance_result);
			if ( $wallet_balance_count > 0 ) {
					$seq_no_query = "SELECT get_sequence_num(3900) as seq_no";
					error_log("seq_no_query = ".$seq_no_query);
					$seq_no_result = mysqli_query($con, $seq_no_query);
					$seq_no_row = mysqli_fetch_assoc($seq_no_result);
					$seq_no = $seq_no_row['seq_no'];	
					error_log("seq_no".$seq_no);
				if($seq_no > 0 )  {
					
							$query = "INSERT INTO wallet_fund_transfer (wallet_fund_transfer_id, sender_partner_code, sender_partner_type, sender_wallet_type,receiver_partner_code,receiver_partner_type, receiver_wallet_type, transfer_amount, status, create_user, create_time) VALUES($seq_no, '$partyCode','$partyType','M','$parent_code','$partyType','M', $transamnt, 'E', $user_id,now())";
							error_log("queyr".$query);
							$result =  mysqli_query($con,$query);
						if($result){
								$acc_trans_type1 = "GAWTF";
								$acc_trans_type2 = "GAWTT"; 
								$from_comment = "Fund Transfer From # ".$seq_no;
								$to_comment = "Fund Transfer To #".$seq_no;
								//$parentCode =Null;
								$parentType ='A';
								$journal_entry_id1 = process_glentry($acc_trans_type1, $seq_no, $partyCode, $partyType, $parent_code, $parentType, $from_comment, $transamnt, $user_id, $con);
								error_log("select_journal_entry1 = ".$journal_entry_id1);
								error_log("select_journal_entry1 = ".$journal_entry_id1);
                            if($journal_entry_id1 > 0) {
									$get_acc_trans_type1 = getAcccTransType($acc_trans_type1, $con);
									error_log("get_acc_trans_type1 = ".$get_acc_trans_type1);
								if($get_acc_trans_type1 != "-1"){
										$split = explode("|", $get_acc_trans_type1);
										$ac_factor1 = $split[0];
										$cb_factor1 = $split[1];
										$acc_trans_type_id1 = $split[2];
										$update_wallet1 = transactionwalletupdate($ac_factor1, $cb_factor1,$partyType, $partyCode, $transamnt, $con, $user_id, $journal_entry_id1);
                                    if($update_wallet1 == 0) {
											$journal_entry_id2 = process_glentry($acc_trans_type2, $seq_no, $partyCode, $partyType, $parent_code, 'A', $to_comment, $transamnt, $user_id, $con);
											error_log("select_journal_entry2 = ".$journal_entry_id2);
											error_log("select_journal_entry2 = ".$journal_entry_id2);
										if($journal_entry_id2 > 0) {
												$get_acc_trans_type2 = getAcccTransType($acc_trans_type2, $con);
												error_log("get_acc_trans_type2 = ".$get_acc_trans_type2);
											if($get_acc_trans_type2 != "-1"){
													$split = explode("|", $get_acc_trans_type2);
													$ac_factor2 = $split[0];
													$cb_factor2 = $split[1];
													$acc_trans_type_id2 = $split[2];
													$update_wallet2 =  transactionwalletupdate($ac_factor2, $cb_factor2,$partyType, $parent_code, $transamnt, $con, $user_id, $journal_entry_id2);
												if($update_wallet2 == 0){
														$wallet_fund_query="Update wallet_fund_transfer set status='C',update_time=now() where wallet_fund_transfer_id = $seq_no";
														$wallet_fund_result=mysqli_query($con,$wallet_fund_query);
														error_log("wallet_fund_query".$wallet_fund_query);
													if(!$wallet_fund_result){
														$wallet_fund_update_query="Update wallet_fund_transfer set status='F' where wallet_fund_transfer_id = $seq_no";
														error_log("wallet_fund_update_query".$wallet_fund_update_query);
														$wallet_fund_update_result=mysqli_query($con,$wallet_fund_update_query);
													}else{
														$response = array();
														$response["msg"] = "Success";
														$response["responseCode"] = 200;
														$response["errorResponseDescription"] = "Payout Bank Cash Transferred  Successfully.";
														$response["errorResponseDescription"] = "Child Wallet ".$partyCode." to Parent Wallet  ".$parent_code."  Cash Transferred processed Successfully.";
													}
												}else{
													$response = array();
													$response["msg"] = "Your Payout Request #".$seq_no." encountered error. Contact Kadick Admin";
													$response["responseCode"] = 110;
													$response["errorResponseDescription"] = mysqli_error($con);
												}
														  
											}else{
												$response = array();
												$response["msg"] = "Your Payout Request #".$seq_no." encountered error in journal entry. Contact Kadick Admin.";
												$response["responseCode"] = 130;
												$response["errorResponseDescription"] = mysqli_error($con);
											} 	  
									}else{ 
										$response = array();
										$response["msg"] = "Your Payout Request #".$seq_no." encountered error. Contact Kadick Admin.";
										$response["responseCode"] = 140;
										$response["errorResponseDescription"] = mysqli_error($con);
							        } 
								}else{ 
										$response = array();
										$response["msg"] = "Your Payout Request #".$seq_no." encountered error. Contact Kadick Admin.";
										$response["responseCode"] = 140;
										$response["errorResponseDescription"] = mysqli_error($con);
							        } 
								
							}else{
									$response = array();
									$response["msg"] ="Your Payout Request #".$seq_no." encountered error in acc code1. Contact Kadick Admin.";
									$response["responseCode"] = 150;
									$response["errorResponseDescription"] = mysqli_error($con);
							    }       
				       
					}else{
								$response = array();
								$response["msg"] = "Your Payout Request #".$seq_no." encountered error in journal entry. Contact Kadick Admin.";
								$response["responseCode"] = 160;
								$response["errorResponseDescription"] = mysqli_error($con);
						    }
					
				}else {
					$response = array();
					$response["result"] = "Error";
					$response["msg"] = "Error in submitting your Payout Request";
					$response["responseCode"] = 170;
					$response["errorResponseDescription"] = mysqli_error($con);
					}
				
				}else {
					$response = array();
					$response["result"] = "Error";
					$response["msg"] = "Sequence No is Not Genereated";
					$response["responseCode"] = 180;
					$response["errorResponseDescription"] = mysqli_error($con);
					}
					
				
			
			}else {
					$response = array();
					$response["result"] = "Error";
					$response["msg"] = "Transaction Amount is Greater than Available Balance";
					$response["responseCode"] = 190;
					$response["errorResponseDescription"] = mysqli_error($con);
					}
					
			}
		
		echo json_encode($response);
							
			
	}
	}else{
	
	if($action == "payout") {
		 $creteria = $data->creteria;
		 $agentCode = $data->agentCode;	
		 $transamnt = $data->transamnt;
		 $parentchildagentCode = $data->parentchildagentCode;		
		 $parentwallet = $data->parentwallet;
		 $agent_code = $data->agent_code;
		  $childagentCode = $data->childagentCode;	
		 $partyType = "A";
		    if($creteria == "P") {
			$wallet_balance_query="Select available_balance from agent_wallet where agent_code='$agentCode' and available_balance >= $transamnt";
			error_log("wallet_balance_query = ".$wallet_balance_query);
		    $wallet_balance_result = mysqli_query($con, $wallet_balance_query);
		    $wallet_balance_count = mysqli_num_rows($wallet_balance_result);
			if ( $wallet_balance_count > 0 ) {
					$seq_no_query = "SELECT get_sequence_num(3900) as seq_no";
					error_log("seq_no_query = ".$seq_no_query);
					$seq_no_result = mysqli_query($con, $seq_no_query);
					$seq_no_row = mysqli_fetch_assoc($seq_no_result);
					$seq_no = $seq_no_row['seq_no'];	
					error_log("seq_no".$seq_no);
				if($seq_no > 0 )  {
							$parentagentquery = "Select parent_code,parent_type from agent_info where agent_code='$agentCode'";
							error_log("parent-agent_code".$parentagentquery);
							$parentagentresult = mysqli_query($con,$parentagentquery);
						 error_log("select_attachment_I_pre_query".$parentagentquery);
						 $row = mysqli_fetch_assoc($parentagentresult);
						 $parent_code = $row['parent_code'];
						 $parent_type = $row['parent_type'];
						 	$query = "INSERT INTO wallet_fund_transfer (wallet_fund_transfer_id, sender_partner_code, sender_partner_type, sender_wallet_type,receiver_partner_code,receiver_partner_type, receiver_wallet_type, transfer_amount, status, create_user, create_time) VALUES($seq_no, '$agentCode','$partyType','M','$parentchildagentCode','$partyType','M', $transamnt, 'E', $user_id,now())";
							error_log("queyr".$query);
							$result =  mysqli_query($con,$query);
						if($result){
								$acc_trans_type1 = "GAWTF";
								$acc_trans_type2 = "GAWTT"; 
								$from_comment = "Fund Transfer From # ".$seq_no;
								$to_comment = "Fund Transfer To #".$seq_no;
								//$parentCode =Null;
								//$parentType ='C';
								$journal_entry_id1 = process_glentry($acc_trans_type1, $seq_no, $agentCode, $partyType, $parent_code, $parent_type, $from_comment, $transamnt, $user_id, $con);
								error_log("select_journal_entry1 = ".$journal_entry_id1);
								error_log("select_journal_entry1 = ".$journal_entry_id1);
                            if($journal_entry_id1 > 0) {
									$get_acc_trans_type1 = getAcccTransType($acc_trans_type1, $con);
									error_log("get_acc_trans_type1 = ".$get_acc_trans_type1);
								if($get_acc_trans_type1 != "-1"){
										$split = explode("|", $get_acc_trans_type1);
										$ac_factor1 = $split[0];
										$cb_factor1 = $split[1];
										$acc_trans_type_id1 = $split[2];
										$update_wallet1 = transactionwalletupdate($ac_factor1, $cb_factor1,$partyType, $agentCode, $transamnt, $con, $user_id, $journal_entry_id1);
                                    if($update_wallet1 == 0) {
											$journal_entry_id2 = process_glentry($acc_trans_type2, $seq_no, $agentCode, $partyType, $parent_code, $parent_type, $to_comment, $transamnt, $user_id, $con);
											error_log("select_journal_entry2 = ".$journal_entry_id2);
											error_log("select_journal_entry2 = ".$journal_entry_id2);
										if($journal_entry_id2 > 0) {
												$get_acc_trans_type2 = getAcccTransType($acc_trans_type2, $con);
												error_log("get_acc_trans_type2 = ".$get_acc_trans_type2);
											if($get_acc_trans_type2 != "-1"){
													$split = explode("|", $get_acc_trans_type2);
													$ac_factor2 = $split[0];
													$cb_factor2 = $split[1];
													$acc_trans_type_id2 = $split[2];
													$update_wallet2 =  transactionwalletupdate($ac_factor2, $cb_factor2,$partyType, $parentchildagentCode, $transamnt, $con, $user_id, $journal_entry_id2);
												if($update_wallet2 == 0){
														$wallet_fund_query="Update wallet_fund_transfer set status='C',update_time=now() where wallet_fund_transfer_id = $seq_no";
														$wallet_fund_result=mysqli_query($con,$wallet_fund_query);
														error_log("wallet_fund_query".$wallet_fund_query);
													if(!$wallet_fund_result){
														$wallet_fund_update_query="Update wallet_fund_transfer set status='F' where wallet_fund_transfer_id = $seq_no";
														error_log("wallet_fund_update_query".$wallet_fund_update_query);
														$wallet_fund_update_result=mysqli_query($con,$wallet_fund_update_query);
													}else{
														$response = array();
														$response["msg"] = "Success";
														$response["responseCode"] = 200;
														$response["errorResponseDescription"] = "Payout Bank Cash Transferred  Successfully.";
														$response["errorResponseDescription"] = "Parent Wallet ".$agentCode."  to Child Wallet ".$parentchildagentCode." Cash Transferred processed Successfully.";
													}
												}else{
													$response = array();
													$response["msg"] = "Your Payout Request #".$seq_no." encountered error. Contact Kadick Admin";
													$response["responseCode"] = 110;
													$response["errorResponseDescription"] = mysqli_error($con);
												}
														  
											}else{
												$response = array();
												$response["msg"] = "Your Payout Request #".$seq_no." encountered error in journal entry. Contact Kadick Admin.";
												$response["responseCode"] = 130;
												$response["errorResponseDescription"] = mysqli_error($con);
											} 	  
									}else{ 
										$response = array();
										$response["msg"] = "Your Payout Request #".$seq_no." encountered error. Contact Kadick Admin.";
										$response["responseCode"] = 140;
										$response["errorResponseDescription"] = mysqli_error($con);
							        } 
								}else{ 
										$response = array();
										$response["msg"] = "Your Payout Request #".$seq_no." encountered error. Contact Kadick Admin.";
										$response["responseCode"] = 140;
										$response["errorResponseDescription"] = mysqli_error($con);
							        } 
								
							}else{
									$response = array();
									$response["msg"] ="Your Payout Request #".$seq_no." encountered error in acc code1. Contact Kadick Admin.";
									$response["responseCode"] = 150;
									$response["errorResponseDescription"] = mysqli_error($con);
							    }       
				       
					}else{
								$response = array();
								$response["msg"] = "Your Payout Request #".$seq_no." encountered error in journal entry. Contact Kadick Admin.";
								$response["responseCode"] = 160;
								$response["errorResponseDescription"] = mysqli_error($con);
						    }
					
				}else {
					$response = array();
					$response["result"] = "Error";
					$response["msg"] = "Error in submitting your Payout Request";
					$response["responseCode"] = 170;
					$response["errorResponseDescription"] = mysqli_error($con);
					}
				
				}else {
					$response = array();
					$response["result"] = "Error";
					$response["msg"] = "Sequence No is Not Genereated";
					$response["responseCode"] = 180;
					$response["errorResponseDescription"] = mysqli_error($con);
					}
					
				
			
			}else {
					$response = array();
					$response["result"] = "Error";
					$response["msg"] = "Transaction Amount is Greater than Available Balance";
					$response["responseCode"] = 190;
					$response["errorResponseDescription"] = mysqli_error($con);
					}
					
			}else{
				$wallet_balance_query="Select available_balance from agent_wallet where agent_code='$childagentCode' and available_balance >= $transamnt";
			error_log("wallet_balance_query = ".$wallet_balance_query);
		    $wallet_balance_result = mysqli_query($con, $wallet_balance_query);
		    $wallet_balance_count = mysqli_num_rows($wallet_balance_result);
			if ( $wallet_balance_count > 0 ) {
					$seq_no_query = "SELECT get_sequence_num(3900) as seq_no";
					error_log("seq_no_query = ".$seq_no_query);
					$seq_no_result = mysqli_query($con, $seq_no_query);
					$seq_no_row = mysqli_fetch_assoc($seq_no_result);
					$seq_no = $seq_no_row['seq_no'];	
					error_log("seq_no".$seq_no);
				if($seq_no > 0 )  {
					
							$query = "INSERT INTO wallet_fund_transfer (wallet_fund_transfer_id, sender_partner_code, sender_partner_type, sender_wallet_type,receiver_partner_code,receiver_partner_type, receiver_wallet_type, transfer_amount, status, create_user, create_time) VALUES($seq_no, '$childagentCode','$partyType','M','$agent_code','$partyType','M', $transamnt, 'E', $user_id,now())";
							error_log("queyr".$query);
							$result =  mysqli_query($con,$query);
						if($result){
								$acc_trans_type1 = "GAWTF";
								$acc_trans_type2 = "GAWTT"; 
								$from_comment = "Fund Transfer From # ".$seq_no;
								$to_comment = "Fund Transfer To #".$seq_no;
								//$parentCode =Null;
								$parentType ='A';
								$journal_entry_id1 = process_glentry($acc_trans_type1, $seq_no, $childagentCode, $partyType, $agent_code, $parentType, $from_comment, $transamnt, $user_id, $con);
								error_log("select_journal_entry1 = ".$journal_entry_id1);
								error_log("select_journal_entry1 = ".$journal_entry_id1);
                            if($journal_entry_id1 > 0) {
									$get_acc_trans_type1 = getAcccTransType($acc_trans_type1, $con);
									error_log("get_acc_trans_type1 = ".$get_acc_trans_type1);
								if($get_acc_trans_type1 != "-1"){
										$split = explode("|", $get_acc_trans_type1);
										$ac_factor1 = $split[0];
										$cb_factor1 = $split[1];
										$acc_trans_type_id1 = $split[2];
										$update_wallet1 = transactionwalletupdate($ac_factor1, $cb_factor1,$partyType, $childagentCode, $transamnt, $con, $user_id, $journal_entry_id1);
                                    if($update_wallet1 == 0) {
											$journal_entry_id2 = process_glentry($acc_trans_type2, $seq_no, $childagentCode, $partyType, $agent_code, $parentType, $to_comment, $transamnt, $user_id, $con);
											error_log("select_journal_entry2 = ".$journal_entry_id2);
											error_log("select_journal_entry2 = ".$journal_entry_id2);
										if($journal_entry_id2 > 0) {
												$get_acc_trans_type2 = getAcccTransType($acc_trans_type2, $con);
												error_log("get_acc_trans_type2 = ".$get_acc_trans_type2);
											if($get_acc_trans_type2 != "-1"){
													$split = explode("|", $get_acc_trans_type2);
													$ac_factor2 = $split[0];
													$cb_factor2 = $split[1];
													$acc_trans_type_id2 = $split[2];
													$update_wallet2 =  transactionwalletupdate($ac_factor2, $cb_factor2,$partyType, $agent_code, $transamnt, $con, $user_id, $journal_entry_id2);
												if($update_wallet2 == 0){
														$wallet_fund_query="Update wallet_fund_transfer set status='C',update_time=now() where wallet_fund_transfer_id = $seq_no";
														$wallet_fund_result=mysqli_query($con,$wallet_fund_query);
														error_log("wallet_fund_query".$wallet_fund_query);
													if(!$wallet_fund_result){
														$wallet_fund_update_query="Update wallet_fund_transfer set status='F' where wallet_fund_transfer_id = $seq_no";
														error_log("wallet_fund_update_query".$wallet_fund_update_query);
														$wallet_fund_update_result=mysqli_query($con,$wallet_fund_update_query);
													}else{
														$response = array();
														$response["msg"] = "Success";
														$response["responseCode"] = 200;
														$response["errorResponseDescription"] = "Payout Bank Cash Transferred  Successfully.";
														$response["errorResponseDescription"] = "Child Wallet ".$childagentCode."  to Parent Wallet ".$agent_code." Cash Transferred processed Successfully.";
													}
												}else{
													$response = array();
													$response["msg"] = "Your Payout Request #".$seq_no." encountered error. Contact Kadick Admin";
													$response["responseCode"] = 110;
													$response["errorResponseDescription"] = mysqli_error($con);
												}
														  
											}else{
												$response = array();
												$response["msg"] = "Your Payout Request #".$seq_no." encountered error in journal entry. Contact Kadick Admin.";
												$response["responseCode"] = 130;
												$response["errorResponseDescription"] = mysqli_error($con);
											} 	  
									}else{ 
										$response = array();
										$response["msg"] = "Your Payout Request #".$seq_no." encountered error. Contact Kadick Admin.";
										$response["responseCode"] = 140;
										$response["errorResponseDescription"] = mysqli_error($con);
							        } 
								}else{ 
										$response = array();
										$response["msg"] = "Your Payout Request #".$seq_no." encountered error. Contact Kadick Admin.";
										$response["responseCode"] = 140;
										$response["errorResponseDescription"] = mysqli_error($con);
							        } 
								
							}else{
									$response = array();
									$response["msg"] ="Your Payout Request #".$seq_no." encountered error in acc code1. Contact Kadick Admin.";
									$response["responseCode"] = 150;
									$response["errorResponseDescription"] = mysqli_error($con);
							    }       
				       
					}else{
								$response = array();
								$response["msg"] = "Your Payout Request #".$seq_no." encountered error in journal entry. Contact Kadick Admin.";
								$response["responseCode"] = 160;
								$response["errorResponseDescription"] = mysqli_error($con);
						    }
					
				}else {
					$response = array();
					$response["result"] = "Error";
					$response["msg"] = "Error in submitting your Payout Request";
					$response["responseCode"] = 170;
					$response["errorResponseDescription"] = mysqli_error($con);
					}
				
				}else {
					$response = array();
					$response["result"] = "Error";
					$response["msg"] = "Sequence No is Not Genereated";
					$response["responseCode"] = 180;
					$response["errorResponseDescription"] = mysqli_error($con);
					}
					
				
			
			}else {
					$response = array();
					$response["result"] = "Error";
					$response["msg"] = "Transaction Amount is Greater than Available Balance";
					$response["responseCode"] = 190;
					$response["errorResponseDescription"] = mysqli_error($con);
					}
					
			}
		
		echo json_encode($response);
							
			
	}
	}
	function process_glentry($acc_trans_type, $transaction_id, $firstpartycode, $firstpartytype, $secondpartycode, $secondpartytype, $comment, $amount, $uid, $con) {
	    	
		if ( $secondpartycode == "" ) {
			$query = "select gl_entry('$acc_trans_type', $transaction_id, '$firstpartycode', '$firstpartytype', null, null, $amount, left('$comment', 49), $uid) as journal_entry_id";
		}else {
			$query = "select gl_entry('$acc_trans_type', $transaction_id, '$firstpartycode', '$firstpartytype', '$secondpartycode', '$secondpartytype', $amount, 'left($comment, 49)', $uid) as journal_entry_id";
		}
		error_log("process_glentry = ".$query);
		$result = mysqli_query($con, $query);
		if (!$result) {
			error_log("Error: process_glentry - = ".mysqli_error($con));
			$ret_val = -1;
		}
		else {
			$row = mysqli_fetch_array($result); 
			$journal_entry_id = $row['journal_entry_id'];
			$ret_val = $journal_entry_id;
		}
		error_log("journal_entry_id = ".$ret_val);
		return $ret_val;
	}
	function getAcccTransType($code, $con) {
	
		$query ="SELECT ab_factor, cb_factor, acc_trans_type_id FROM acc_trans_type WHERE acc_trans_type_code = '$code'";
		error_log("getAcccTransType = ".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			error_log("Error: getAcccTransType = ".mysqli_error($con));
			$ret_val = -1;
		}
		else {
			$row = mysqli_fetch_array($result); 
			$ab_factor = $row['ab_factor'];
			$cb_factor = $row['cb_factor'];
			$acc_trans_type_id = $row['acc_trans_type_id'];
		}
		//error_log("ret_val ".$ret_val);
		return $ab_factor."|".$cb_factor."|".$acc_trans_type_id;
	}
		
	function transactionwalletupdate($ac_factor, $cb_factor,$type, $partycode, $amount, $con, $user_id, $transaction_id) {
		
		$query = "";
		$table_name = "";
		$ret_val = '';
		if($type == 'S' || $type == 'A') {
			$table_name = 'agent_wallet';	
			$col_name = 'agent_code';	
		}
		else if($type == 'P') {
			$table_name = 'personal_wallet';	
			$col_name = 'personal_code';				
		}
		else if($type == 'C') {
			$table_name = 'champion_wallet';	
			$col_name = 'champion_code';				
		}
		
		error_log("table_name = ".$table_name.", col_name = ".$col_name);
		$query ="UPDATE $table_name SET last_tx_no = $transaction_id, last_tx_date = now(), last_tx_amount = ".$amount.", previous_current_balance = current_balance, current_balance = (IFNULL(current_balance,0.00) + ($cb_factor * $amount)), available_balance = (ifNull(current_balance, 0) + ifNull(credit_limit, 0) + ifNull(advance_amount, 0) - ifNull(minimum_balance, 0)), update_user = $user_id, update_time = now() WHERE $col_name = '$partycode'";
		error_log("transactionwalletupdate query = ".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			error_log("Error: transactionwalletupdate = ".$table_name." = ".mysqli_error($con));
			$ret_val = -1;
		}
		else {
			$ret_val = 0;
		}
		error_log("retval".$ret_val);
		return $ret_val;		
	}
?>	