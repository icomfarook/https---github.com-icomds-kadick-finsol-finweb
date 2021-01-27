<?php

	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;

	if($action == "query") {
		$id = $data->id;
		$climit = $data->credit_limit;
		$dlimit = $data->daily_limit;
		$advamount = $data->advance_amount;
		$avaibalce = $data->available_balance;
		$curbalance = $data->current_balance;
		$minibalance = $data->minimum_balance;
	  	$startDate = $data->startDate;
		$endDate = $data->endDate;	
		$startDate = date("Y-m-d", strtotime($startDate));
		$endDate = date("Y-m-d", strtotime($endDate));
		$query = "Select  nibss_account_id,ifNull(credit_limit,'-')as credit_limit, ifNull(daily_limit,'-')as daily_limit,ifNull(advance_amount,'-')as advance_amount,ifNull(available_balance,'-')as available_balance,ifNull(current_balance,'-')as current_balance,ifNull(minimum_balance,'-')as minimum_balance FROM nibss_account WHERE nibss_account_id='1'";
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			echo "Error in accessing fin_non_trans_log table";
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['nibss_account_id'],"climit"=>$row['credit_limit'],"dlimit"=>$row['daily_limit'],"advamount"=>$row['advance_amount'],"avaibalce"=>$row['available_balance'],"curbalance"=>$row['current_balance'],"minibalance"=>$row['minimum_balance']);           
		}
		echo json_encode($data);
	}
	else if($action == 'detail') {
	
		$id = $data->id;
		$query = "select credit_limit, daily_limit, advance_amount, available_balance, current_balance, minimum_balance, previous_current_balance, uncleared_balance, last_tx_no, last_tx_amount, last_tx_date, active, block_status, block_date, block_reason_id, create_user, create_time, update_user, update_time from nibss_account where nibss_account_id= 1";
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['nibss_account_id'],"climit"=>$row['credit_limit'],"dlimit"=>$row['daily_limit'],"advamount"=>$row['advance_amount'],"avaibalce"=>$row['available_balance'],"curbalance"=>$row['current_balance'],"minibalance"=>$row['minimum_balance'],"precurbalance"=>$row['previous_current_balance'],"unclbalance"=>$row['uncleared_balance'],"ltxno"=>$row['last_tx_no'], "ltxamount"=>$row['last_tx_amount'],"ltxdate"=>$row['last_tx_date'], "active"=>$row['active'],"blkstatus"=>$row['block_status'],"blkdate"=>$row['block_date'],"blkreasid"=>$row['block_reason_id'],"cuser"=>$row['create_user'],"ctime"=>$row['create_time'],"upuser"=>$row['update_user'],"uptime"=>$row['update_time']);                      
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}
	}
	else if($action == "view") {		
		$query = "SELECT nibss_account_id, credit_limit, daily_limit, advance_amount, available_balance, current_balance, minimum_balance, previous_current_balance, uncleared_balance, last_tx_no, last_tx_amount, last_tx_date, active, block_status, block_date, block_reason_id, create_user, create_time, update_user, update_time from nibss_account where nibss_account_id= 1";
		error_log($query);
		$result =  mysqli_query($con,$query);
		if(!$result) {
			die('result: ' . mysqli_error($con));
			echo " Payable result - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['nibss_account_id'],"climit"=>$row['credit_limit'],"dlimit"=>$row['daily_limit'],"advamount"=>$row['advance_amount'],"avaibalce"=>$row['available_balance'],"curbalance"=>$row['current_balance'],"minibalance"=>$row['minimum_balance'],"precurbalance"=>$row['previous_current_balance'],"unclbalance"=>$row['uncleared_balance'],"ltxno"=>$row['last_tx_no'], "ltxamount"=>$row['last_tx_amount'],"ltxdate"=>$row['last_tx_date'], "active"=>$row['active'],"blkstatus"=>$row['block_status'],"blkdate"=>$row['block_date'],"blkreasid"=>$row['block_reason_id'],"cuser"=>$row['create_user'],"ctime"=>$row['create_time'],"upuser"=>$row['update_user'],"uptime"=>$row['update_time']);                      
			}
			echo json_encode($data);
		}
			
	}
	
?>	