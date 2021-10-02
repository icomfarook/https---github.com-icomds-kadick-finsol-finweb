<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');	
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$profile_id = $_SESSION['profile_id'];	
	
	//$profile_id = 1;
	if($action == "findlist") {
		$partyCode = $data->partyCode;
		
		
		if($profile_id == 1 || $profile_id == 10 || $profile_id == 20 || $profile_id == 22 || $profile_id == 26 || $profile_id == 23) {
			$partyCode = $data->partyCode;
			$partyType = $data->partyType;
			$startDate =  $data->startDate;
	$endDate = $data->endDate;
			$startDate = date("Y-m-d", strtotime($startDate));
			$endDate = date("Y-m-d", strtotime($endDate));
			
			
			if($partyType == "C") {
				$query = "select b.champion_wallet_audit_id as id, b.champion_code as party_code, concat(c.acc_trans_description, '-', a.acc_trans_type_code) as trans_code, a.description, a.amount as journal_amount, b.old_available_balance, b.new_available_balance,ifNull(b.old_last_tx_no,' - ') as old_last_tx_no,ifNull(b.new_last_tx_no,' - ') as  new_last_tx_no,ifNull(b.old_last_tx_amount,' - ') as  old_last_tx_amount, ifNull(b.new_last_tx_amount,' - ') as new_last_tx_amount,ifNull(b.old_last_tx_date,' - ') as  old_last_tx_date, b.new_last_tx_date from acc_trans_type c, champion_wallet_audit b inner join journal_entry a on b.new_last_tx_no = a.journal_entry_id where date(b.create_time) >= date('$startDate') and date(b.create_time) <= date('$endDate') and b.champion_code = '$partyCode' and a.acc_trans_type_code = c.acc_trans_type_code order by b.create_time";
			}
			if($partyType == "P") {
				$query = "select b.personal_wallet_audit_id, b.personal_code, concat(c.acc_trans_description, '-', a.acc_trans_type_code) as trans_code, a.description, a.amount as journal_amount, b.old_available_balance, b.new_available_balance, b.old_last_tx_no, b.new_last_tx_no, b.old_last_tx_amount, b.new_last_tx_amount, b.old_last_tx_date, b.new_last_tx_date from acc_trans_type c, personal_wallet_audit b inner join journal_entry a on b.new_last_tx_no = a.journal_entry_id where date(b.create_time) >= date('$startDate') and date(b.create_time) <= date('$endDate')  and b.personal_code = '$partyCode' and a.acc_trans_type_code = c.acc_trans_type_code order by b.create_time";
			}
			if($partyType == "MA" || $partyType == "SA") {
				$query = "select b.agent_wallet_audit_id as id, b.agent_code as party_code, concat(c.acc_trans_description, '-', a.acc_trans_type_code) as trans_code, a.description, a.amount as journal_amount, b.old_available_balance, b.new_available_balance,ifNull(b.old_last_tx_no,' - ') as  old_last_tx_no,ifNull(b.new_last_tx_no,' - ') as  new_last_tx_no,ifNull(b.old_last_tx_amount,' - ') as  old_last_tx_amount, ifNull(b.new_last_tx_amount,' - ') as new_last_tx_amount,ifNull(b.old_last_tx_date,' - ') as  old_last_tx_date, b.new_last_tx_date from acc_trans_type c, agent_wallet_audit b inner join journal_entry a on b.new_last_tx_no = a.journal_entry_id where date(b.create_time) >= date('$startDate') and date(b.create_time) <= date('$endDate')  and b.agent_code = '$partyCode' and a.acc_trans_type_code = c.acc_trans_type_code order by b.create_time";
				if($partyType == "MA") {
					$partyType = "A";
				}
				if($partyType == "SA") {
					$partyType = "S";
				}
			}
			
		}
		
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("partyCode"=>$row['party_code'],"id"=>$row['id'],"trans_code"=>$row['trans_code'],"description"=>$row['description'],"journal_amount"=>$row['journal_amount'],"old_available_balance"=>$row['old_available_balance'],"new_available_balance"=>$row['new_available_balance'],"old_last_tx_no"=>$row['old_last_tx_no'],"new_last_tx_no"=>$row['new_last_tx_no'],"old_last_tx_amount"=>$row['old_last_tx_amount'],"new_last_tx_amount"=>$row['new_last_tx_amount'],"old_last_tx_date"=>$row['old_last_tx_date'],"new_last_tx_date"=>$row['new_last_tx_date'],"partyType"=>$partyType);           
		}
		echo json_encode($data);
	}
	
	else if($action == "edit") {
		$partyCode = $data->partyCode;
		$partyType = $data->partyType;
		$id = $data->id;
		if($partyType == "A"   || $partyType == "S" ) {
			$query = "select b.agent_wallet_audit_id as id, b.agent_code as party_code, concat(c.acc_trans_description, '-', a.acc_trans_type_code) as trans_code, a.description, a.amount as journal_amount, b.old_available_balance, b.new_available_balance,ifNull(b.old_last_tx_no,' - ') as  old_last_tx_no,ifNull(b.new_last_tx_no,' - ') as  new_last_tx_no,ifNull(b.old_last_tx_amount,' - ') as  old_last_tx_amount, ifNull(b.new_last_tx_amount,' - ') as new_last_tx_amount,ifNull(b.old_last_tx_date,' - ') as  old_last_tx_date, b.new_last_tx_date from acc_trans_type c, agent_wallet_audit b inner join journal_entry a on b.new_last_tx_no = a.journal_entry_id where a.acc_trans_type_code = c.acc_trans_type_code and b.agent_code = '".$partyCode."' and agent_wallet_audit_id = $id";
		}
		
			if($partyType == "C") {
				$query = "select b.champion_wallet_audit_id as id, b.champion_code as party_code, concat(c.acc_trans_description, '-', a.acc_trans_type_code) as trans_code, a.description, a.amount as journal_amount, b.old_available_balance, b.new_available_balance,ifNull(b.old_last_tx_no,' - ') as old_last_tx_no,ifNull(b.new_last_tx_no,' - ') as  new_last_tx_no,ifNull(b.old_last_tx_amount,' - ') as  old_last_tx_amount, ifNull(b.new_last_tx_amount,' - ') as new_last_tx_amount,ifNull(b.old_last_tx_date,' - ') as  old_last_tx_date, b.new_last_tx_date from acc_trans_type c, champion_wallet_audit b inner join journal_entry a on b.new_last_tx_no = a.journal_entry_id where  a.acc_trans_type_code = c.acc_trans_type_code and b.champion_code  = '".$partyCode."' and agent_wallet_audit_id = $id";
			}
		
				
		if($partyType == "P") {
			$query = "select b.personal_wallet_audit_id, b.personal_code, concat(c.acc_trans_description, '-', a.acc_trans_type_code) as trans_code, a.description, a.amount as journal_amount, b.old_available_balance, b.new_available_balance, b.old_last_tx_no, b.new_last_tx_no, b.old_last_tx_amount, b.new_last_tx_amount, b.old_last_tx_date, b.new_last_tx_date from acc_trans_type c, personal_wallet_audit b inner join journal_entry a on b.new_last_tx_no = a.journal_entry_id where a.acc_trans_type_code = c.acc_trans_type_code and  b.personal_code = '".$partyCode."' and agent_wallet_audit_id = $id";
		}
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("partyCode"=>$row['party_code'],"id"=>$row['id'],"trans_code"=>$row['trans_code'],"description"=>$row['description'],"journal_amount"=>$row['journal_amount'],"old_available_balance"=>$row['old_available_balance'],"new_available_balance"=>$row['new_available_balance'],"old_last_tx_no"=>$row['old_last_tx_no'],"new_last_tx_no"=>$row['new_last_tx_no'],"old_last_tx_amount"=>$row['old_last_tx_amount'],"new_last_tx_amount"=>$row['new_last_tx_amount'],"old_last_tx_date"=>$row['old_last_tx_date'],"new_last_tx_date"=>$row['new_last_tx_date'],"partyType"=>$partyType);                 
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			exit();
		}
	}
	
?>	