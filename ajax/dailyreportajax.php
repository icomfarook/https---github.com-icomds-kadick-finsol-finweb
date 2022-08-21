<?php

	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	if($action == "query") {
		$Detail = $data->Detail;
    	$startDate = $data->startDate;
		//$endDate = $data->endDate;	
		$startDate = date("Y-m-d", strtotime($startDate));
		//$endDate = date("Y-m-d", strtotime($endDate. "+1 days"));
        if($Detail == true){
            $query = "select party_wallet_history_id,run_date,party_Code,party_name,state_name,local_govt_name,i_format(available_balance) as available_balance,i_format(current_balance) as current_balance,i_format(minimum_balance) as minimum_balance,i_format(advance_amount) as advance_amount,i_format(comm_withdraw_amount) as comm_withdraw_amount,i_format(wallet_fund_amount) as wallet_fund_amount,i_format(cashin_amount) as cashin_amount,i_format(cashout_amount) as cashout_amount,i_format(evd_amount) as evd_amount,i_format(billpay_amount) as billpay_amount from party_wallet_history where date(run_date) = '$startDate' and party_type='A'";
        }else{
            $query = "select party_wallet_history_id,run_date,party_Code,party_name,state_name,local_govt_name,i_format(available_balance) as available_balance,i_format(current_balance) as current_balance,i_format(minimum_balance) as minimum_balance,i_format(advance_amount) as advance_amount,i_format(comm_withdraw_amount) as comm_withdraw_amount,i_format(wallet_fund_amount) as wallet_fund_amount,i_format(cashin_amount) as cashin_amount,i_format(cashout_amount) as cashout_amount,i_format(evd_amount) as evd_amount,i_format(billpay_amount) as billpay_amount from party_wallet_history where date(run_date) = '$startDate'";
        }
		
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			echo "Error in accessing fin_non_trans_log table";
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['party_wallet_history_id'],"run_date"=>$row['run_date'],"party_Code"=>$row['party_Code'],"party_name"=>$row['party_name'],"state_name"=>$row['state_name'],"local_govt_name"=>$row['local_govt_name'],"available_balance"=>$row['available_balance'],"current_balance"=>$row['current_balance'],"minimum_balance"=>$row['minimum_balance'],"advance_amount"=>$row['advance_amount'], "comm_withdraw_amount"=>$row['comm_withdraw_amount'],"wallet_fund_amount"=>$row['wallet_fund_amount'], "cashin_amount"=>$row['cashin_amount'],"cashout_amount"=>$row['cashout_amount'], "evd_amount"=>$row['evd_amount'],"billpay_amount"=>$row['billpay_amount']);           
		}
		echo json_encode($data);
	}
	else if($action == 'view') {
	
		$id = $data->id;
        $query = "select party_wallet_history_id,if(party_type = 'A','A-Agent',if(party_type='C','C-Champion','O-Others')) as party_type,party_code,party_name,run_date,state_name,local_govt_name,party_sales_type,i_format(credit_limit) as credit_limit,i_format(daily_limit) as daily_limit,i_format(advance_amount) as advance_amount,i_format(available_balance) as available_balance,i_format(current_balance) as current_balance,i_format(minimum_balance) as minimum_balance,i_format(previous_current_balance) as previous_current_balance,i_format(comm_available_balance) as comm_available_balance,i_format(comm_current_balance) as comm_current_balance,i_format(comm_minimum_balance) as comm_minimum_balance,last_tx_no,i_format(last_tx_amount) as last_tx_amount,last_tx_date,comm_last_tx_no,i_format(comm_last_tx_amount) as comm_last_tx_amount,comm_last_tx_date,comm_withdraw_count,i_format(comm_withdraw_amount) as comm_withdraw_amount,comm_withdraw_time,wallet_fund_count,i_format(wallet_fund_amount) as wallet_fund_amount,wallet_fund_time,cashin_count,i_format(cashin_amount) as cashin_amount,cashin_time,cashout_count,i_format(cashout_amount) as cashout_amount,cashout_time,evd_count,i_format(evd_amount) as evd_amount,evd_time,billpay_count,i_format(billpay_amount) as billpay_amount,billpay_time,create_time from party_wallet_history where party_wallet_history_id='$id'";

		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['party_wallet_history_id'],"run_date"=>$row['run_date'],"party_type"=>$row['party_type'],"party_code"=>$row['party_code'],"party_name"=>$row['party_name'],"state_name"=>$row['state_name'],"local_govt_name"=>$row['local_govt_name'],"available_balance"=>$row['available_balance'],"current_balance"=>$row['current_balance'],"minimum_balance"=>$row['minimum_balance'],"advance_amount"=>$row['advance_amount'], "comm_withdraw_amount"=>$row['comm_withdraw_amount'],"wallet_fund_amount"=>$row['wallet_fund_amount'], "cashin_amount"=>$row['cashin_amount'],"cashout_amount"=>$row['cashout_amount'], "evd_amount"=>$row['evd_amount'],"billpay_amount"=>$row['billpay_amount'],"credit_limit"=>$row['credit_limit'],"party_sales_type"=>$row['party_sales_type'],"daily_limit"=>$row['daily_limit'],"previous_current_balance"=>$row['previous_current_balance'],"comm_available_balance"=>$row['comm_available_balance'],"comm_current_balance"=>$row['comm_current_balance'],"comm_minimum_balance"=>$row['comm_minimum_balance'],"last_tx_no"=>$row['last_tx_no'],"last_tx_amount"=>$row['last_tx_amount'], "last_tx_date"=>$row['last_tx_date'],"comm_last_tx_no"=>$row['comm_last_tx_no'], "comm_last_tx_amount"=>$row['comm_last_tx_amount'],"comm_last_tx_date"=>$row['comm_last_tx_date'], "comm_withdraw_count"=>$row['comm_withdraw_count'],"comm_withdraw_time"=>$row['comm_withdraw_time'],"wallet_fund_count"=>$row['wallet_fund_count'],"wallet_fund_time"=>$row['wallet_fund_time'],"cashin_count"=>$row['cashin_count'],"cashin_time"=>$row['cashin_time'],"cashout_count"=>$row['cashout_count'],"cashout_time"=>$row['cashout_time'],"evd_count"=>$row['evd_count'],"evd_time"=>$row['evd_time'],"billpay_count"=>$row['billpay_count'], "billpay_time"=>$row['billpay_time'],"create_time"=>$row['create_time']);             
		}
	
		
		echo json_encode($data);
	}
	
?>	