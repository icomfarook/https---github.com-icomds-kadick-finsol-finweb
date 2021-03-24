<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	$data = json_decode(file_get_contents("php://input"));
	include ('./ajax/functions.php');
	$action = $data->action;
	$profile_id = $_SESSION['profile_id'];	
	$user_id = $_SESSION['user_id'];
	//$profile_id = 1;
	if($action == "query") {
		$query = "";
		$tablename = "";
		if($profile_id == 1 || $profile_id == 10 || $profile_id == 20 || $profile_id == 22 || $profile_id == 26 ) {
			$partyCode = $data->partyCode;
			$partyType = $data->partyType;
			if($partyType == "MA" || $partyType == "SA")
				$partyType = "A";
		}
		else {
			$partyCode = $_SESSION['party_code'];
			$partyType = $_SESSION['party_type'];
		}
		
		if($partyType == "A") {
			$tablename = "agent_comm_wallet";
			$colname = "agent_code";
		}
		if($partyType == "C") {
			$tablename = "champion_comm_wallet";
			$colname = "champion_code";
		}	
		if($partyType == "P") {
			$tablename = "personal_comm_wallet";
			$colname = "personal_code";
		}
		$query = "SELECT current_balance FROM $tablename WHERE $colname = '$partyCode'";
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("curbal"=>$row['current_balance']);           
		}
		echo json_encode($data);
	}
	
	if($action == "payout") {
		 $partyType = $data->partyType;
		 $partyCode = $data->partyCode;
		 $creteria = $data->creteria;
		 $curbalance = $data->curbalance;	
		 $paycomamt = $data->paycomamt;		
		 $procharge = $data->procharge;
		 $totalpaycom = $data->totalpaycom;		
		 $bankaccount = $data->bankaccount;
		 if($profile_id == 1 || $profile_id == 10 || $profile_id == 20 || $profile_id == 22 || $profile_id == 26) {
			$partyCode = $data->partyCode;
			$partyType = $data->partyType;
			if($partyType == "MA" || $partyType == "SA")
				$partyType = "A";
		}
		else {
			$partyCode = $_SESSION['party_code'];
			$partyType = $_SESSION['party_type'];
		}
		
		if($partyType == "A") {
			$tablename = "agent_comm_wallet";
			$colname = "agent_code";
		}
		if($partyType == "C") {
			$tablename = "champion_comm_wallet";
			$colname = "champion_code";
		}	
		if($partyType == "P") {
			$tablename = "personal_comm_wallet";
			$colname = "personal_code";
		}
		$query = "";
		$seq_no_query = "SELECT get_sequence_num(1900) as seq_no";
		error_log("seq_no_query = ".$seq_no_query);
		$seq_no_result = mysqli_query($con, $seq_no_query);
		if(!$seq_no_result) {
			$response = array();
			$response["msg"] = 'Getting Sequence No Failure';
			$response["responseCode"] = 100;
			$response["errorResponseDescription"] = mysqli_error($con);
		}
		else {
			$seq_no_row = mysqli_fetch_assoc($seq_no_result);
			$seq_no = $seq_no_row['seq_no'];	
			  error_log("seq_no".$seq_no);
			     if($creteria == "W") {
					   $query = "INSERT INTO comm_payout_request (comm_payout_request_id, party_type, party_code, payout_type, comm_payout_amount, processing_amount, comm_total_amount, status, create_user, create_time) VALUES($seq_no, '$partyType','$partyCode','$creteria', $paycomamt,  $procharge, $totalpaycom, 'P',$user_id,now())";
						error_log("queyr".$query);
						$result =  mysqli_query($con,$query);	
						if($result){
						error_log("$insert_payout_request_query is success");	
						$wallet_balance_query = "select champion_comm_wallet_id from champion_comm_wallet where champion_code = '$partyCode' and available_balance >= $totalpaycom";
						error_log("wallet_balance_query = ".$wallet_balance_query);
						$wallet_balance_result = mysqli_query($con, $wallet_balance_query);
						if ( $wallet_balance_result ) {
						     $wallet_balance_count = mysqli_num_rows($wallet_balance_result);
				            if ( $wallet_balance_count > 0 ) {
									$update_payout_query = "update comm_payout_request set  status = 'I', update_user = $user_id, update_time = now() where comm_payout_request_id = $seq_no";
									error_log("update_payout_query = ".$update_payout_query);
									$update_payout_result = mysqli_query($con, $update_payout_query);
							    if($update_payout_result){
						   				$acc_trans_type1 = "CPYTW";
						    				$acc_trans_type2 = "CPYFW"; 
						    				$from_comment = "Payout from Comm Wallet #".$seq_no;
						    				$to_comment = "Payout to Main Wallet #".$seq_no;
											//$parentCode =Null;
											//$parentType =Null;
						    				$journal_entry_id1 = process_glentry($acc_trans_type1, $seq_no, $partyCode, $partyType, $parentCode, $parentType, $from_comment, $totalpaycom, $user_id, $con);
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
											        $update_wallet1 = commWalletupdateWithTransaction($acc_trans_type1, $cb_factor1, $partyType, $partyCode, $totalpaycom, $con, $user_id, $journal_entry_id1);
                                             	if($update_wallet1 == 0) {
                                           			$journal_entry_id2 = process_glentry($acc_trans_type2, $seq_no, $partyCode, $partyType, $parentCode, $parentType, $to_comment, $paycomamt, $user_id, $con);
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
															$update_wallet2 = walletupdateWithTransaction($acc_trans_type2, $cb_factor2, $partyType, $partyCode, $paycomamt, $con, $user_id, $journal_entry_id2);
														  
                                            				if($update_wallet2 == 0) {
																$update_payout_query2 = "update comm_payout_request set status = 'S', update_user = $user_id, update_time = now() where comm_payout_request_id = $seq_no";
																error_log("update_payout_query2 = ".$update_payout_query2);
																$update_payout_result2 = mysqli_query($con, $update_payout_query2);
																if($update_payout_result2){
																	$response = array();
																	$response["msg"] = 'Success';
																	$response["responseCode"] = 200;
																	$response["errorResponseDescription"] = "Payout  Cash Transferred  Successfully.";
																}
															}else{
																$response = array();
																$response["msg"] = "Your Payout Request #".$seq_no." encountered error. Contact Kadick Admin";
																$response["responseCode"] = 110;
																$response["errorResponseDescription"] = mysqli_error($con);
																}
															
													     }else{
															$response = array();
															$response["msg"] = "Your Payout Request #".$seq_no." encountered error in acc code2. Contact Kadick Admin.";
															$response["responseCode"] = 120;
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
						   
							    }else{
								$response = array();
								$response["msg"] = "Error in updating Payout Request Status";
								$response["responseCode"] = 170;
								$response["errorResponseDescription"] = mysqli_error($con);
								}     
						   
						    }else{
								$response = array();
								$response["msg"] = "Insufficent Wallet Balance";
								$response["responseCode"] = 180;
								$response["errorResponseDescription"] = mysqli_error($con);
						    }
						}
						else {
								$response = array();
								$response["result"] = "Error";
								$response["msg"] = "Error in selecting wallet balance";
								$response["responseCode"] = 190;
								$response["errorResponseDescription"] = mysqli_error($con);
							}
				        } 
						else {
								$response = array();
								$response["result"] = "Error";
								$response["msg"] = "Error in submitting your Payout Request";
								$response["responseCode"] = 100;
								$response["errorResponseDescription"] = mysqli_error($con);
						}
				 } 
		
		
		}
		
			
		echo json_encode($response);
							
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
		function commWalletupdateWithTransaction($ac_factor, $cb_factor, $type, $partycode, $totalamount, $con, $uid, $transaction_id) {
		error_log("inside commWalletupdateWithTransaction: type = ".$type.", partycode = ".$partycode.", totalamount = ".$totalamount);
		$query = "";
		$table_name = "";
		$ret_val = '';
		if($type == 'S' || $type == 'A') {
			$table_name = 'agent_comm_wallet';	
			$col_name = 'agent_code';	
		}
		else if($type == 'P') {
			$table_name = 'personal_comm_wallet';	
			$col_name = 'personal_code';				
		}
		else if($type == 'C') {
			$table_name = 'champion_comm_wallet';	
			$col_name = 'champion_code';				
		}
		
		error_log("table_name = ".$table_name.", col_name = ".$col_name);
		$query = "UPDATE $table_name SET previous_current_balance = ifNull(current_balance,0), current_balance = (IFNULL(current_balance,0.00) + ($cb_factor * $totalamount)), available_balance = (ifNull(current_balance,0) + ifNull(credit_limit,0) + ifNull(advance_amount,0) - ifNull(minimum_balance,0)), last_tx_amount = $totalamount, last_tx_no = $transaction_id, last_tx_date = now(), update_user = $uid, update_time = now() WHERE $col_name = '$partycode'";
		error_log("commWalletupdateWithTransaction update query = ".$query);
		$result = mysqli_query($con, $query);
		if (!$result) {
			error_log("Error: commWalletupdateWithTransaction = :".$table_name." = ".mysqli_error($con));
			$ret_val = -1;
		}
		else {
			$ret_val = 0;
		}
		error_log("commWalletupdateWithTransaction : ret_val = ".$ret_val);
		return $ret_val;
	}	
	function walletupdateWithTransaction($ac_factor, $cb_factor, $type, $partycode, $totalamount, $con, $uid, $transaction_id) {
		error_log("inside walletupdateWithTransaction: type = ".$type.", partycode = ".$partycode.", totalamount = ".$totalamount);
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
		$query = "UPDATE $table_name SET previous_current_balance = ifNull(current_balance,0), current_balance = (IFNULL(current_balance,0.00) + ($cb_factor * $totalamount)), available_balance = (ifNull(current_balance,0) + ifNull(credit_limit,0) + ifNull(advance_amount,0) - ifNull(minimum_balance,0)), last_tx_amount = $totalamount, last_tx_no = $transaction_id, last_tx_date = now(), update_user = $uid, update_time = now() WHERE $col_name = '$partycode'";
		error_log("walletupdateWithTransaction update query = ".$query);
		$result = mysqli_query($con, $query);
		if (!$result) {
			error_log("Error: walletupdateWithTransaction = :".$table_name." = ".mysqli_error($con));
			$ret_val = -1;
		}
		else {
			$ret_val = 0;
		}
		error_log("walletupdateWithTransaction : ret_val = ".$ret_val);
		return $ret_val;
	}
?>	