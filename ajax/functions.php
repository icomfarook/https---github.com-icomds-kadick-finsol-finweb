<?php

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
	function check_party_available_balance($partyType,$userId, $con){
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
	function transactionwalletupdate($ac_factor, $cb_factor,$type, $partycode, $amount, $con, $uid, $transaction_id) {
		
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
		$query ="UPDATE $table_name SET previous_current_balance = ifNull(current_balance,0), current_balance = (IFNULL(current_balance,0.00) + ($cb_factor * $totalamount)), available_balance = (ifNull(current_balance, 0) + ifNull(credit_limit, 0) + ifNull(advance_amount, 0) - ifNull(minimum_balance, 0)), update_user = $uid, update_time = now() WHERE $col_name = '$partycode'";
		error_log("walletupdateWithOutTransaction query = ".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			error_log("Error: walletupdateWithOutTransaction = ".$table_name." = ".mysqli_error($con));
			$ret_val = -1;
		}
		else {
			$ret_val = 0;
		}
		error_log("walletupdateWithOutTransaction : ret_val = ".$ret_val);
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

?>