<?php

	require_once ("AesCipher.php");
	function generate_seq_num($seq, $con){
		$seq_no = -1;
		$seq_no_query = "SELECT get_sequence_num($seq) as seq_no";
		error_log("seq_no_query = ".$seq_no_query);
		$seq_no_result = mysqli_query($con, $seq_no_query);
		if(!$seq_no_result) {
			error_log("Error: generate_seq_num = ".mysqli_error($con));
			$seq_no = -1;
		}
		else {
			$seq_no_row = mysqli_fetch_assoc($seq_no_result);
			$seq_no = $seq_no_row['seq_no'];			
		}
		error_log("seq_no = ".$seq_no);
		return $seq_no;
	}
	 
	function check_agent_available_balance($userId, $con){

		$query = "SELECT ifNull(a.available_balance,0) as available_balance FROM agent_wallet a,user b, agent_info c WHERE c.user_id = b.user_id and c.agent_code = a.agent_code and b.user_id = $userId";
		error_log("check_agent_current_balance query = ".$query);
		$result = mysqli_query($con, $query);
		$available_balance = 0;
		if(!$result) {
			error_log("Error: check_agent_available_balance = ".mysqli_error($con));
			$available_balance = 0;
		}
		else {
			$row = mysqli_fetch_assoc($result);
			$available_balance = $row['available_balance'];			
		}
		return $available_balance;
	}

	function check_party_comm_wallet_balance($partyCode, $partyType, $con){

		if ( $partyType == "A" ) {
			$query = "SELECT ifNull(a.available_balance,0) as available_balance FROM agent_comm_wallet a WHERE a.agent_code = '$partyCode'";
		}else if ( $partyType == "C" ) {
			$query = "SELECT ifNull(a.available_balance,0) as available_balance FROM champion_comm_wallet a WHERE a.agent_code = '$partyCode'";
		}else {
			$query = "SELECT ifNull(a.available_balance,0) as available_balance FROM agent_comm_wallet a WHERE a.agent_code = '$partyCode'";
		}
		error_log("check_party_comm_wallet_balance query = ".$query);
		$result = mysqli_query($con, $query);
		$available_balance = 0;
		if(!$result) {
			error_log("Error: check_party_comm_wallet_balance = ".mysqli_error($con));
			$available_balance = 0;
		}
		else {
			$row = mysqli_fetch_assoc($result);
			$available_balance = $row['available_balance'];			
		}
		return $available_balance;
	}
	
	function check_party_available_balance($partyType, $userId, $con){
		if($partyType == "A") {
			$table_name1 = "agent_wallet";
			$table_name2 = "agent_info";
			$col_name = "agent_code";
		}
		if($partyType == "C") {
			$table_name1 = "champion_wallet";
			$table_name2 = "champion_info";
			$col_name = "champion_code";
		}
		$query = "SELECT ifNull(a.available_balance,0) as available_balance FROM $table_name1 a,user b, $table_name2 c WHERE c.user_id = b.user_id and c.$col_name = a.$col_name and b.user_id = $userId";
		error_log("check_party_type_current_balance query = ".$query);
		$result = mysqli_query($con, $query);
		$available_balance = 0;
		if(!$result) {
			error_log("Error: check_party_type_current_balance = ".mysqli_error($con));
			$available_balance = 0;
		}
		else {
			$row = mysqli_fetch_assoc($result);
			$available_balance = $row['available_balance'];			
		}
		return $available_balance;
	}

	function transactionwalletupdate($ac_factor, $cb_factor, $type, $partycode, $amount, $con, $uid, $transaction_id) {
		
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
		$query ="UPDATE $table_name SET last_tx_no = $transaction_id, last_tx_date = now(), last_tx_amount = ".$amount.", previous_current_balance = current_balance, current_balance = (IFNULL(current_balance,0.00) + ($cb_factor * $amount)), available_balance = (ifNull(current_balance, 0) + ifNull(credit_limit, 0) + ifNull(advance_amount, 0) - ifNull(minimim_balance, 0)), update_user = $uid, update_time = now() WHERE $col_name = '$partycode'";
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

	//Correct one
	function walletupdateWithOutTransaction($ac_factor, $cb_factor, $type, $partycode, $totalamount, $con, $uid) {
	
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
		$query = "UPDATE $table_name SET previous_current_balance = ifNull(current_balance,0), current_balance = (IFNULL(current_balance,0.00) + ($cb_factor * $totalamount)), available_balance = (ifNull(current_balance, 0) + ifNull(credit_limit, 0) + ifNull(advance_amount, 0) - ifNull(minimum_balance, 0)), update_user = $uid, update_time = now() WHERE $col_name = '$partycode'";
		error_log("walletupdateWithOutTransaction query = ".$query);
		$result = mysqli_query($con, $query);
		if (!$result) {
			error_log("Error: walletupdateWithOutTransaction = ".$table_name." = ".mysqli_error($con));
			$ret_val = -1;
		}
		else {
			$ret_val = 0;
		}
		error_log("walletupdateWithOutTransaction: ret_val = ".$ret_val);
		return $ret_val;
	}
	
	function check_agent_info_wallet_status($type, $partycode, $con) {

		//ret_val = 0 -> All Good
		//ret_val = 1 -> agent_info.status = N
		//ret_val = 2 -> agent_info.block_status = Y
		//ret_val = 3 -> agent_wallet.status = N
		//ret_val = 4 -> agent_wallet.block_status = Y
		//ret_val = -1 -> Not Good

		$query = "";
		$table_name1 = "";
		$table_name2 = "";
		$ret_val = -1;

		if($type == 'S' || $type == 'A') {
			$table_name1 = 'agent_info';
			$table_name2 = 'agent_wallet';	
			$col_name = 'agent_code';	
		}
		else if($type == 'P') {
			$table_name1 = 'personal_info';
			$table_name2 = 'personal_wallet';	
			$col_name = 'personal_code';				
		}
		else if($type == 'C') {
			$table_name1 = 'champion_info';
			$table_name2 = 'champion_wallet';	
			$col_name = 'champion_code';				
		}
		error_log("table_name1 = ".$table_name1.", table_name2 = ".$table_name2.", col_name = ".$col_name);
		$query = "select ifNull(a.active, 'N') as info_active, ifNull(a.block_status, 'N') as info_block_status, ifNull(b.active, 'N') as wallet_active, ifNull(b.block_status, 'N') as wallet_block_status from ".$table_name1." a, ".$table_name2." b where a.".$col_name." = b.".$col_name." and a.".$col_name." = '".$partycode."'";
		error_log("check_agent_info_wallet_status query = ".$query);
		$result = mysqli_query($con, $query);
		if(!$result) {
			error_log("Error: check_agent_info_wallet_status = ".mysqli_error($con));
			$ret_val = -1;
		}
		else {
			$row = mysqli_fetch_assoc($result);
			$info_active = $row['info_active'];
			$info_block_status = $row['info_block_status'];
			$wallet_active = $row['wallet_active'];
			$wallet_block_status = $row['wallet_block_status'];

			if ( $info_active == 'N') {
				$ret_val = 1;
			}else {
				if ( $info_block_status == 'Y') {
					$ret_val = 2;
				}else {
					if ( $wallet_active == 'N') {
						$ret_val = 3;
					}else {
						if ( $wallet_block_status == 'Y') {
							$ret_val = 4;
						}else {
							$ret_val = 0;
						}
					}
				}
			}

		}
		error_log("check_agent_info_wallet_status : ret_val = ".$ret_val);
		return $ret_val;
	}
	
	//Correct one
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
	

	function process_glentry($acc_trans_type, $transaction_id, $firstpartycode, $firstpartytype, $secondpartycode, $secondpartytype, $comment, $amount, $uid, $con) {
	    	
		if ( $secondpartycode == "" ) {
			$query = "select gl_entry('$acc_trans_type', $transaction_id, '$firstpartycode', '$firstpartytype', null, null, $amount, left('$comment', 49), $uid) as journal_entry_id";
		}else {
			$query = "select gl_entry('$acc_trans_type', $transaction_id, '$firstpartycode', '$firstpartytype', '$secondpartycode', '$secondpartytype', $amount, left('$comment', 49), $uid) as journal_entry_id";
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

	function process_glpost($journal_entry_id, $con) {
	    	
		$query = "select gl_post($journal_entry_id) as result";
		error_log("process_glpost = ".$query);
		$result = mysqli_query($con, $query);
		if (!$result) {
			error_log("Error: process_glpost = ".mysqli_error($con));
			$ret_val = -1;
		}
		else {
			$row = mysqli_fetch_array($result); 
			$ret_val = $row['result'];
		}
		error_log("result = ".$ret_val);
		return $ret_val;
	}

	function process_glreverse($journal_entry_id, $con) {
	    	
		$query = "select gl_reverse($journal_entry_id) as result";
		error_log("process_glreverse = ".$query);
		$result = mysqli_query($con, $query);
		if (!$result) {
			error_log("Error: process_glreverse = ".mysqli_error($con));
			$ret_val = -1;
		}
		else {
			$row = mysqli_fetch_array($result); 
			$ret_val = $row['result'];
		}
		error_log("result = ".$ret_val);
		return $ret_val;
	}
	
	function post_finorder($fin_service_order_no, $con) {
	    	
		$query = "update fin_service_order set post_status = 'Y', post_time = now() where fin_service_order_no = ".$fin_service_order_no;
		error_log("process_finorderpost = ".$query);
		$result = mysqli_query($con, $query);
		if (!$result) {
			error_log("Error: process_finorderpost = ".mysqli_error($con));
			$ret_val = -1;
		}
		else {
			$ret_val = 0;
		}
		error_log("process_finorderpost: result = ".$ret_val);
		return $ret_val;
	}
	
	function post_bporder($bp_service_order_no, $con) {
		   	
		$query = "update bp_service_order set post_status = 'Y', post_time = now() where bp_service_order_no = ".$bp_service_order_no;
		error_log("post_bporder query = ".$query);
		$result = mysqli_query($con, $query);
		if (!$result) {
			error_log("Error: post_bporder = ".mysqli_error($con));
			$ret_val = -1;
		}
		else {
			$ret_val = 0;
		}
		error_log("post_bporder: result = ".$ret_val);
		return $ret_val;
	}
	
	function post_accorder($acc_service_order_no, $con) {
			   	
		$query = "update acc_service_order set post_status = 'Y', post_time = now() where acc_service_order_no = ".$acc_service_order_no;
		error_log("post_accorder query = ".$query);
		$result = mysqli_query($con, $query);
		if (!$result) {
			error_log("Error: post_accorder = ".mysqli_error($con));
			$ret_val = -1;
		}
		else {
			$ret_val = 0;
		}
		error_log("post_accorder: result = ".$ret_val);
		return $ret_val;
	}

	function insertjournalerror($userId, $journal_entry_id, $type, $errorCode, $errorType, $status, $amount, $con) {
		
		$query = "insert into journal_error(journal_error_id, journal_entry_id, user_id, acc_trans_type_code, amount, error_code, error_type, create_date, status) values (0, $journal_entry_id, $userId, '$type', $amount, '$errorCode', '$errorType', now(), '$status')";
		error_log("insertjournalentry = ".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			error_log("Error: insertjournalentry = ".mysqli_error($con));
			$ret_val = -1;
		}
		else {
			$ret_val = $journal_entry_id;
		}
		//error_log("ret_val ".$ret_val);
		return $ret_val;
	}

	function insertaccountrollback($userId, $transaction_id, $type, $amount, $pic_point, $status, $con) {
		
		$query = "insert into account_rollback(account_rollback_id, transaction_id, acc_trans_type_code, amount, point_in_call, status, create_user, create_time) values (0, $transaction_id, '$type', $amount, $pic_point, '$status', $userId, now())";
		error_log("insertaccountrollback = ".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			error_log("Error: insertaccountrollback = ".mysqli_error($con));
			$ret_val = -1;
		}
		else {
			$ret_val = 0;
		}
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

	function process_comm_update($fin_service_order_no, $con) {
		
		$pcu_result = 0;
		$query = "select process_comm_update($fin_service_order_no) as result";
		error_log("process_comm_update = ".$query);
		$result = mysqli_query($con, $query);
		if (!$result) {
			error_log("Error: process_comm_update = ".mysqli_error($con));
			$pcu_result = -1;
		}
		else {
			$row = mysqli_fetch_array($result); 
			$pcu_result = $row['result'];
		}
		error_log("result = ".$pcu_result);
		return $pcu_result;
	}
	
	function process_bp_comm_update($bp_service_order_no, $con) {
			
		$pcu_result = 0;
		$query = "select process_bp_comm_update($bp_service_order_no) as result";
		error_log("process_bp_comm_update = ".$query);
		$result = mysqli_query($con, $query);
		if (!$result) {
			error_log("Error: process_bp_comm_update = ".mysqli_error($con));
			$pcu_result = -1;
		}
		else {
			$row = mysqli_fetch_array($result); 
			$pcu_result = $row['result'];
		}
		error_log("process_bp_comm_update result = ".$pcu_result);
		return $pcu_result;
	}

	function process_acc_comm_update($acc_service_order_no, $con) {
			
		$pcu_result = 0;
		$query = "select process_acc_comm_update($acc_service_order_no) as result";
		error_log("process_acc_comm_update = ".$query);
		$result = mysqli_query($con, $query);
		if (!$result) {
			error_log("Error: process_acc_comm_update = ".mysqli_error($con));
			$pcu_result = -1;
		}
		else {
			$row = mysqli_fetch_array($result); 
			$pcu_result = $row['result'];
		}
		error_log("process_acc_comm_update result = ".$pcu_result);
		return $pcu_result;
	}
	
	function insertFinanceServiceOrderComm($fin_service_order_no, $serviceconfig, $journal_entry_id, $txType, $agentCharge, $amsCharge, $con) {
	
		//Format for serviceconfig
		//service_charge_rate_id~service_charge_party_name~comm_user_id~comm_user_name~charge_value
		$serviceconfig = explode("~",$serviceconfig);
		$service_charge_rate_id = $serviceconfig[0];
		$service_charge_party_name = $serviceconfig[1];
		$comm_user_id = $serviceconfig[2];
		$charge_value = $serviceconfig[4];
		if ( $txType == "F" && $service_charge_party_name == "Agent" ) {
			$charge_value = $agentCharge;
		}else if ( $txType == "F" && $service_charge_party_name == "Kadick" ) {
			$charge_value = $amsCharge;
		}
		$query =  "INSERT INTO fin_service_order_comm (fin_service_order_no, service_charge_rate_id, service_charge_party_name, user_id, charge_value, journal_entry_id) VALUES ($fin_service_order_no, $service_charge_rate_id, '$service_charge_party_name', $comm_user_id, $charge_value, $journal_entry_id)";
		error_log("insertFinanceServiceOrderComm query = ".$query);
		$result = mysqli_query($con,$query);
		if(!$result) {
			error_log("Error: insertFinanceServiceOrderComm = %s\n".mysqli_error($con));
			return -1;
		}
		else {
			return 0;
		}
	}
	
	function insertBillPayServiceOrderComm($bp_service_order_no, $serviceconfig, $journal_entry_id, $txType, $agentCharge, $amsCharge, $con) {
		
		//Format for serviceconfig
		//service_charge_rate_id~service_charge_party_name~comm_user_id~comm_user_name~charge_value
		$serviceconfig = explode("~",$serviceconfig);
		$service_charge_rate_id = $serviceconfig[0];
		$service_charge_party_name = $serviceconfig[1];
		$comm_user_id = $serviceconfig[2];
		$charge_value = $serviceconfig[4];
		if ( $txType == "F" && $service_charge_party_name == "Agent" ) {
			$charge_value = $agentCharge;
		}else if ( $txType == "F" && $service_charge_party_name == "Kadick" ) {
			$charge_value = $amsCharge;
		}
		$query =  "INSERT INTO bp_service_order_comm (bp_service_order_no, service_charge_rate_id, service_charge_party_name, user_id, charge_value, journal_entry_id) VALUES ($bp_service_order_no, $service_charge_rate_id, '$service_charge_party_name', $comm_user_id, $charge_value, $journal_entry_id)";
		error_log("insertBillPayServiceOrderComm query = ".$query);
		$result = mysqli_query($con,$query);
		if(!$result) {
			error_log("Error: insertBillPayServiceOrderComm = %s\n".mysqli_error($con));
			return -1;
		}
		else {
			return 0;
		}
	}

	
	function insertAccountServiceOrderComm($acc_service_order_no, $serviceconfig, $journal_entry_id, $txType, $agentCharge, $amsCharge, $con) {
		
		//Format for serviceconfig
		//service_charge_rate_id~service_charge_party_name~comm_user_id~comm_user_name~charge_value
		$serviceconfig = explode("~",$serviceconfig);
		$service_charge_rate_id = $serviceconfig[0];
		$service_charge_party_name = $serviceconfig[1];
		$comm_user_id = $serviceconfig[2];
		$charge_value = $serviceconfig[4];
		if ( $txType == "F" && $service_charge_party_name == "Agent" ) {
			$charge_value = $agentCharge;
		}else if ( $txType == "F" && $service_charge_party_name == "Kadick" ) {
			$charge_value = $amsCharge;
		}
		$query =  "INSERT INTO acc_service_order_comm (acc_service_order_no, service_charge_rate_id, service_charge_party_name, user_id, charge_value, journal_entry_id) VALUES ($acc_service_order_no, $service_charge_rate_id, '$service_charge_party_name', $comm_user_id, $charge_value, $journal_entry_id)";
		error_log("insertAccountServiceOrderComm query = ".$query);
		$result = mysqli_query($con,$query);
		if(!$result) {
			error_log("Error: insertAccountServiceOrderComm = %s\n".mysqli_error($con));
			return -1;
		}
		else {
			return 0;
		}
	}
	
	function getValidSessionKey ($user_id, $con) {
		$session_key = "0";
		$session_check_query = "select session_key from user_pos where session_key_valid_time > current_timestamp() and user_id = ".$user_id;
		error_log("session_check_query = ".$session_check_query);
		$session_check_result = mysqli_query($con, $session_check_query);
		if (!$session_check_result) {
			error_log("Error in getValidSessionKey: ".mysqli_error($con));
			$session_key = "1";
		}else {
			$rowcount = mysqli_num_rows($session_check_result);
			if ( $rowcount > 0 ) {
				$session_check_row = mysqli_fetch_array($session_check_result); 
				$session_key = $session_check_row['session_key'];
			}else {
				$session_key = "2";
			}
		}
		error_log("getValidSessionKey: session_key = ".$session_key);
		return $session_key;
	}

	function validateKey1($key1, $user_id, $session_validity, $action, $con) {

		//valid key1: return 0, invalid key1: return 1;
		$result = 1;
		error_log("session_validity = ".$session_validity);
		$session_key = getValidSessionKey($user_id, $con);
		if ( strlen($session_key) > 1 ) {
			try {
				error_log("before calling Security::decrypt");
				$key1_result = AesCipher::decrypt($session_key, $key1);
				error_log("after calling Security::decrypt");
				error_log("key1_result = ".$key1_result);
				$tilda_found = strpos($key1_result, '~');
				if ( $tilda_found == true ) {
					$key1_array = explode("~", $key1_result);
					$key1_user_id = $key1_array[0];
					$key1_serial_no = $key1_array[1];
					$key1_time = $key1_array[2];
					error_log("key1_user_id = ".$key1_user_id.", key1_serial_no = ".$key1_serial_no.", key1_time = ".$key1_time);
					if ( $user_id == $key1_user_id ) {
						$user_session_update = "UPDATE user_pos set session_key_create_time = now(), session_key_valid_time = ADDTIME(now(), '".$session_validity."') where user_id = ".$user_id;
						error_log("user_session_update = ".$user_session_update);
						$user_session_result = mysqli_query($con, $user_session_update);
						if (!$user_session_result ) {
							error_log("Error in user_session_result");
						}
						if ( $action != 'L' ) {
							recordUserPosActivity($user_id, $key1_serial_no, $action, "", $con);
						}
						$result = 0;
					}else {
						error_log("validateKey1 -> invalid user_id");
					}
				}else {
					error_log("validateKey1 -> invalid key1 values");
				}
			}catch(Exception $e1) {
				error_log("Exception in validateKey1: ".$e1->getMessage());
			}
		}
		error_log("validateKey1.result = ".$result);
		return $result;
	}

	function checkDailyLimit($user_id, $requestAmount, $con) {
		//Within Daily Limit: return 0, Exceed Daily Limit: -1, Invalid User: -2, Other Error: -3
		$result = -1;
		$daily_limit_check_query = "select check_daily_limit ($user_id, $requestAmount) as result";
		error_log("daily_limit_check_query = ".$daily_limit_check_query);
		$daily_limit_check_result = mysqli_query($con, $daily_limit_check_query);
		if (!$daily_limit_check_result) {
			error_log("Error in checkDailyLimit: ".mysqli_error($con));
			$result = -3;
		}else {
			$daily_limit_check_row = mysqli_fetch_array($daily_limit_check_result); 
			$result = $daily_limit_check_row['result'];
		}
		error_log("checkDailyLimit for user_id = ".$user_id." for ".$requestAmount.", result = ".$result);
		return $result;
	}

	function getBpOrderTime($orderNo, $con) {
		$orderTime = date('Y-m-d H:i:s');
		$order_time_check_query = "select date_time from bp_service_order where bp_service_order_no = $orderNo";
		error_log("order_time_check_query = ".$order_time_check_query);
		$order_time_check_result = mysqli_query($con, $order_time_check_query);
		if (!$order_time_check_result) {
			error_log("Error in getBpOrderTime: ".mysqli_error($con));
		}else {
			$order_time_check_row = mysqli_fetch_array($order_time_check_result); 
			$orderTime = $order_time_check_row['date_time'];
		}
		error_log("getBpOrderTime for orderNo = ".$orderNo.", orderTime = ".$orderTime);
		return $orderTime;
	}
	
	function getAccOrderTime($orderNo, $con) {
		$orderTime = date('Y-m-d H:i:s');
		$order_time_check_query = "select date_time from acc_service_order where acc_service_order_no = $orderNo";
		error_log("order_time_check_query = ".$order_time_check_query);
		$order_time_check_result = mysqli_query($con, $order_time_check_query);
		if (!$order_time_check_result) {
			error_log("Error in getAccOrderTime: ".mysqli_error($con));
		}else {
			$order_time_check_row = mysqli_fetch_array($order_time_check_result); 
			$orderTime = $order_time_check_row['date_time'];
		}
		error_log("getAccOrderTime for orderNo = ".$orderNo.", orderTime = ".$orderTime);
		return $orderTime;
	}	
	
	function recordUserPosActivity($user_id, $imei, $action, $detail, $con) {
		$insert_pos_activity_query = "insert into user_pos_activity values (0, ".$user_id.", '".$imei."', '".$action."', left('".$detail."',70), now())";
		error_log("insert_pos_activity_query = ".$insert_pos_activity_query);
		$insert_pos_activity_result = mysqli_query($con, $insert_pos_activity_query);
		if ( !$insert_pos_activity_result ) {
			error_log("Error in recordUserPosActivity: ".mysqli_error($con));
		}
	}

	function checkForAlreadyProcessedCashOutOrder($user_id, $order_no, $con) {
		$result = -1;
		$order_check_query = "SELECT order_no, status from fin_request where order_no = ".$order_no." and status in ('S', 'G') and user_id = ".$user_id;
		error_log("order_check_query = ".$order_check_query);
		$order_check_result = mysqli_query($con, $order_check_query);
		if (!$order_check_result) {
			$result = -2;
			error_log("Error in checkForAlreadyProcessedCashOutOrder check query: ".mysqli_error($con));
		}else {
			$rowcount = mysqli_num_rows($order_check_result);
			if ( $rowcount > 0 ) {
				$order_check_row = mysqli_fetch_array($order_check_result);
				$db_status = $order_check_row['status'];
				if ( 'G' == $db_status ) {
					$result = 0;
				}else {
					$result = 1;
				}
			}else  {
				$result = -3;
			}
		}
		error_log("checkForAlreadyProcessedCashOutOrder for user_id = ".$user_id." for order_no = ".$order_no.", result = ".$result);
		return $result;
	}

	function checkForAlreadyProcessedJournalEntry($user_id, $description, $party_code, $con) {
		$result = -1;
		
		$order_check_query2 = "SELECT journal_entry_id from journal_entry where first_party_code = '".$party_code."' and description = '".$description."' and create_user = ".$user_id;
		error_log("order_check_query2 = ".$order_check_query2);
		
		$order_check_result2 = mysqli_query($con, $order_check_query2);
		if (!$order_check_result2) {
			$result = -2;
			error_log("Error in checkForAlreadyProcessedJournalEntry check query2: ".mysqli_error($con));
		}else {
			$rowcount = mysqli_num_rows($order_check_result2);
			if ( $rowcount == 0 ) {
				$result = 0;
			}else  {
				$result = -1;
			}
		}
		error_log("checkForAlreadyProcessedJournalEntry for user_id = ".$user_id." for description = ".$description.", party_code = ".$party_code.", result = ".$result);
		return $result;
	}	
	
	
	
	function checkForAlreadyProcessedFundWalletOrder($user_id, $payment_reference, $con) {
		$result = -1;
		$payment_check_query = "SELECT p_receipt_id, payment_status from payment_receipt where payment_reference_no = '".$payment_reference."'";
		error_log("payment_check_query = ".$payment_check_query);
		$payment_check_result = mysqli_query($con, $payment_check_query);
		if (!$payment_check_result) {
			$result = -2;
			error_log("Error in checkForAlreadyProcessedFundWalletOrder check query: ".mysqli_error($con));
		}else {
			$rowcount = mysqli_num_rows($payment_check_result);
			if ( $rowcount == 0 ) {
				$result = 0;
			}else  {
				$payment_check_row = mysqli_fetch_array($payment_check_result);
				$db_status = $payment_check_row['payment_status'];
				if ( 'E' == $db_status || 'F' == $db_status ) {
					$result = 1;
				}else {
					$result = -3;
				}
			}
		}
		error_log("checkForAlreadyProcessedFundWalletOrder for user_id = ".$user_id." for payment_reference = ".$payment_reference.", result = ".$result);
		return $result;
	}
?>