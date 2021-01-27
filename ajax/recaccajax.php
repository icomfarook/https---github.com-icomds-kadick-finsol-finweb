<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input")); 
	$id   = $data->id;
	$startDate = $data->startDate;
	$endDate= $data->endDate;
	$action = $data->action;
	/* $profile = $_SESSION['profile_id']; */
	//$startDate = date("Y-m-d", strtotime($startDate. "+1 days"));
	//$endDate = date("Y-m-d", strtotime($endDate. "+1 days"));
	$startDate = date("Y-m-d", strtotime($startDate));
    $endDate = date("Y-m-d", strtotime($endDate));
    	
	if($action == "list") {
		$query = "SELECT nibss_account_id,IFNULL(credit_limit, '-')credit_limit,IFNULL(daily_limit, '-') daily_limit,IFNULL(advance_amount, '-') advance_amount,IFNULL(available_balance, '-') available_balance,IFNULL(current_balance, '-') current_balance,
				  IFNULL(minimum_balance, '-')  minimum_balance, previous_current_balance, uncleared_balance, last_tx_no, last_tx_amount, last_tx_date, active, block_status, block_date, block_reason_id, create_user, create_time, update_user, update_time	 from nibss_account
				  WHERE nibss_account_id =2 and date(create_time) >= '$startDate' and date(create_time) <= '$endDate'";
		error_log("receivableAcc_query == ".$query);
		$result =  mysqli_query($con,$query);
		if(!$result) {
			die('Get query : ' . mysqli_error($con));
			echo "Receivable Account query - Failed";				
		}
		else {
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['nibss_account_id'],"credit_limit"=>$row['credit_limit'],"daily_limit"=>$row['daily_limit'],"advance_amount"=>$row['advance_amount'],"available_balance"=>$row['available_balance'],"current_balance"=>$row['current_balance'],"minimum_balance"=>$row['minimum_balance'],"previous_current_balance"=>$row['previous_current_balance'],           
								"uncleared_balance"=> $row['uncleared_balance'],"last_tx_no"=>$row['last_tx_no'],"last_tx_amount"=>$row['last_tx_amount'],"last_tx_date"=>$row['last_tx_date'],"active"=>$row['active'],"block_status"=>$row['block_status'],"block_date"=>$row['block_date'],"block_reason_id"=>$row['block_reason_id'],
								"create_user"=> $row['create_user'],"create_time"=>$row['create_time'],"update_user"=>$row['update_user'],"update_time"=>$row['update_time']);           
			}
			echo json_encode($data);
		}
	}
	?>	