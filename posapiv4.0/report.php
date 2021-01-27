<?php
	include('../common/admin/configmysql.php');
	include("../common/admin/finsol_crypt.php");
	require '../api/get_prime.php';
	require_once("db_connect.php");
	include ("functions.php");
	error_log("inside pcposapi/my_account.php");
	//error_reporting(E_ALL);
	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		error_log("inside post request method");
        	$data = json_decode(file_get_contents("php://input"));
        	error_log("my_account <== ".json_encode($data));

		if(isset($data -> operation) && $data -> operation == 'REPORT_MYACCOUNT') {
			error_log("inside operation == REPORT_MYACCOUNT method");

			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1)
					&& isset($data->partyCode) && !empty($data->partyCode) && isset($data->partyType) && !empty($data->partyType)
                    			&& isset($data->countryId) && !empty($data->countryId) && isset($data->stateId) && !empty($data->stateId)
                    			&& isset($data->userId) && !empty($data->userId)
			){

				error_log("inside all inputs are set correctly");
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature= $data->signature;
                		$key1 = $data->key1;
				$userId = $data->userId;
				$session_validity = AGENT_SESSION_VALID_TIME;

				error_log("signature = ".$signature.", key1 = ".$key1);
				date_default_timezone_set('Africa/Lagos');
				$nday = date('z')+1;
				$nyear = date('Y');
				$nth_day_prime = get_prime($nday);
				$nth_year_day_prime = get_prime($nday+$nyear);
				$local_signature = $nday + $nth_day_prime;
				error_log("local_signature = ".$local_signature);
				$server_signature = $nth_year_day_prime + $nday + $nyear;
				error_log("server_signature = ".$server_signature);

				if ( $local_signature == $signature ) {
					$validate_result = validateKey1($key1, $userId, $session_validity, 'M', $con);
					error_log("validateKey1 result = ".$validate_result);
					if ( $validate_result != 0 ) {
						// Invalid key1 - Session Timeut
						$response["statusCode"] = "999";
						$response["result"] = "Error";
						$response["message"] = "Failure: Session Timeout";
						$response["signature"] = 0;
						error_log(json_encode($response));
						echo json_encode($response);
						return;
					}
					if ( $partyType == "A") {
						$myaccount_query = "select a.agent_code as party_code, a.agent_name as party_name, ifnull(a.parent_code, '-') as parent_code, i_format(b.available_balance) as available_balance, i_format(b.current_balance) as current_balance, i_format(b.daily_limit) as daily_limit, i_format(b.credit_limit) as credit_limit, i_format(b.minimum_balance) as minimum_balance, i_format(b.last_tx_amount) as last_tx_amount, ifnull(b.last_tx_date, '-') as last_tx_date, ifnull(b.last_tx_no, '-') as last_tx_no from agent_wallet b, agent_info a where a.agent_code = b.agent_code and a.agent_code ='$partyCode'";
					} else if ($partyType == "C") {
						$myaccount_query = "select a.champion_code as party_code, a.champion_name as party_name, '-' as parent_code, i_format(b.available_balance) as available_balance, i_format(b.current_balance) as current_balance, i_format(b.daily_limit) as daily_limit, i_format(b.credit_limit) as daily_limit, i_format(b.minimum_balance) as minimum_balanace, i_format(b.last_tx_amount) as last_tx_amount, ifnull(b.last_tx_date, '-') as last_tx_date, ifnull(b.last_tx_no, '-') as last_tx_no from champion_wallet b, champion_info a where a.champion_code = b.champion_code and a.champion_code ='$partyCode'";
					}
					error_log("myaccount_query = ".$myaccount_query);
					$myaccount_result = mysqli_query($con, $myaccount_query);
					if($myaccount_result) {
                        			$response["accounts"] = array();
						if (!empty($myaccount_result) && mysqli_num_rows($myaccount_result) > 0 ) {
							$response["result"] = "Success";
							$response["message"] = "Successfull Operation";
                            				$response["statusCode"] = "0";
                            				$response["signature"] = $server_signature;
							while ($row = mysqli_fetch_array($myaccount_result)) {
								$myAccount = array();
								$myAccount["partyCode"] = $row["party_code"];
								$myAccount["partyName"] = $row["party_name"];
								$myAccount["partyType"] = $partyType;
								$myAccount["parentCode"] = $row["parent_code"];
							    	$myAccount["availableBalance"] = $row["available_balance"];
								$myAccount["currentBalance"] = $row["current_balance"];
								$myAccount["dailyLimit"] = $row["daily_limit"];
								$myAccount["creditLimit"] = $row["credit_limit"];
								$myAccount["minimumBalance"] = $row["minimum_balance"];
								$myAccount["lastTxNo"] = $row["last_tx_no"];
								$myAccount["lastTxAmount"] = $row["last_tx_amount"];
								$myAccount["lastTxDate"] = $row["last_tx_date"];
								array_push($response["accounts"], $myAccount);
							}
						}else {
							// No Data
							$response["result"] = "Success";
							$response["message"] = "No Data found";
                            				$response["statusCode"] = "100";
                            				$response["signature"] = $server_signature;
						}
					}
					else {
						// DB failure
						$response["result"] = "Error";
						$response["statusCode"] = "200";
                        			$response["message"] = "Failure: Error in reading query from DB";
                        			$response["signature"] = $server_signature;
					}
				}else {
					// Invalid Singature
					$response["statusCode"] = "300";
					$response["result"] = "Error";
                    			$response["message"] = "Failure: Invalid request";
                    			$response["signature"] = $server_signature;
				}
			}else {
				// Invalid Data
				$response["statusCode"] = "400";
				$response["result"] = "Error";
                		$response["message"] = "Failure: Invalid Data";
                		$response["signature"] = 0;
			}
        	}
        	else if(isset($data -> operation) && $data -> operation == 'REPORT_COMMWALLET') {
			error_log("inside operation == REPORT_COMMWALLET method");

			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1)
					&& isset($data->partyCode) && !empty($data->partyCode) && isset($data->partyType) && !empty($data->partyType)
                    			&& isset($data->countryId) && !empty($data->countryId) && isset($data->stateId) && !empty($data->stateId)
                    			&& isset($data->userId) && !empty($data->userId)
			){

				error_log("inside all inputs are set correctly");
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature= $data->signature;
                		$key1 = $data->key1;
                		$userId = $data->userId;
				$session_validity = AGENT_SESSION_VALID_TIME;

				error_log("signature = ".$signature.", key1 = ".$key1);
				date_default_timezone_set('Africa/Lagos');
				$nday = date('z')+1;
				$nyear = date('Y');
				$nth_day_prime = get_prime($nday);
				$nth_year_day_prime = get_prime($nday+$nyear);
				$local_signature = $nday + $nth_day_prime;
				error_log("local_signature = ".$local_signature);
				$server_signature = $nth_year_day_prime + $nday + $nyear;
				error_log("server_signature = ".$server_signature);

				if ( $local_signature == $signature ) {
					$validate_result = validateKey1($key1, $userId, $session_validity, 'W', $con);
					error_log("validateKey1 result = ".$validate_result);
					if ( $validate_result != 0 ) {
						// Invalid key1 - Session Timeut
						$response["statusCode"] = "999";
						$response["result"] = "Error";
						$response["message"] = "Failure: Session Timeout";
						$response["signature"] = 0;
						error_log(json_encode($response));
						echo json_encode($response);
						return;
					}
					if ( $partyType == "A") {
						$commwallet_query = "select a.agent_code as party_code, a.agent_name as party_name, ifnull(a.parent_code, '-') as parent_code, i_format(b.available_balance) as available_balance, i_format(b.current_balance) as current_balance, i_format(b.daily_limit) as daily_limit, i_format(b.credit_limit) as credit_limit, i_format(b.minimum_balance) as minimum_balance, i_format(b.last_tx_amount) as last_tx_amount, ifnull(b.last_tx_date, '-') as last_tx_date, ifnull(b.last_tx_no, '-') as last_tx_no from agent_comm_wallet b, agent_info a where a.agent_code = b.agent_code and a.agent_code ='$partyCode'";
					} else if ($partyType == "C") {
						$commwallet_query = "select a.champion_code as party_code, a.champion_name as party_name, '-' as parent_code, i_format(b.available_balance) as available_balance, i_format(b.current_balance) as current_balance, i_format(b.daily_limit) as daily_limit, i_format(b.credit_limit) as daily_limit, i_format(b.minimum_balance) as minimum_balanace, i_format(b.last_tx_amount) as last_tx_amount, ifnull(b.last_tx_date, '-') as last_tx_date, ifnull(b.last_tx_no, '-') as last_tx_no from champion_comm_wallet b, champion_info a where a.champion_code = b.champion_code and a.champion_code ='$partyCode'";
					}
					error_log("commwallet_query = ".$commwallet_query);
					$commwallet_result = mysqli_query($con, $commwallet_query);
					if($commwallet_result) {
                        			$response["commWallets"] = array();
						if (!empty($commwallet_result) && mysqli_num_rows($commwallet_result) > 0 ) {
							$response["result"] = "Success";
							$response["message"] = "Successfull Operation";
                            				$response["statusCode"] = "0";
                            				$response["signature"] = $server_signature;
							while ($row = mysqli_fetch_array($commwallet_result)) {
								$commWallet = array();
								$commWallet["partyCode"] = $row["party_code"];
								$commWallet["partyName"] = $row["party_name"];
								$commWallet["partyType"] = $partyType;
								$commWallet["parentCode"] = $row["parent_code"];
							    	$commWallet["availableBalance"] = $row["available_balance"];
								$commWallet["currentBalance"] = $row["current_balance"];
								$commWallet["dailyLimit"] = $row["daily_limit"];
								$commWallet["creditLimit"] = $row["credit_limit"];
								$commWallet["minimumBalance"] = $row["minimum_balance"];
								$commWallet["lastTxNo"] = $row["last_tx_no"];
								$commWallet["lastTxAmount"] = $row["last_tx_amount"];
								$commWallet["lastTxDate"] = $row["last_tx_date"];
								array_push($response["commWallets"], $commWallet);
							}
						}else {
							// No Data
							$response["result"] = "Success";
							$response["message"] = "No Data found";
                            				$response["statusCode"] = "100";
                            				$response["signature"] = $server_signature;
						}
					}
					else {
						// DB failure
						$response["result"] = "Error";
						$response["statusCode"] = "200";
                        			$response["message"] = "Failure: Error in reading query from DB";
                        			$response["signature"] = $server_signature;
					}
				}else {
					// Invalid Singature
					$response["statusCode"] = "300";
					$response["result"] = "Error";
                    			$response["message"] = "Failure: Invalid request";
                    			$response["signature"] = $server_signature;
				}
			}else {
				// Invalid Data
				$response["statusCode"] = "400";
				$response["result"] = "Error";
                		$response["message"] = "Failure: Invalid Data";
                		$response["signature"] = 0;
			}
		}
        	else if(isset($data -> operation) && $data -> operation == 'REPORT_LAST_10TRANSACTION') {
			error_log("inside operation == REPORT_LAST_10TRANSACTION method");

			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1)
					&& isset($data->partyCode) && !empty($data->partyCode) && isset($data->partyType) && !empty($data->partyType)
                    			&& isset($data->countryId) && !empty($data->countryId) && isset($data->stateId) && !empty($data->stateId)
                    			&& isset($data->userId) && !empty($data->userId)
			){

				error_log("inside all inputs are set correctly");
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature= $data->signature;
                		$key1 = $data->key1;
				$userId = $data->userId;
				$session_validity = AGENT_SESSION_VALID_TIME;

				error_log("signature = ".$signature.", key1 = ".$key1);
				date_default_timezone_set('Africa/Lagos');
				$nday = date('z')+1;
				$nyear = date('Y');
				$nth_day_prime = get_prime($nday);
				$nth_year_day_prime = get_prime($nday+$nyear);
				$local_signature = $nday + $nth_day_prime;
				error_log("local_signature = ".$local_signature);
				$server_signature = $nth_year_day_prime + $nday + $nyear;
				error_log("server_signature = ".$server_signature);

				if ( $local_signature == $signature ) {
					$validate_result = validateKey1($key1, $userId, $session_validity, 'T', $con);
					error_log("validateKey1 result = ".$validate_result);
					if ( $validate_result != 0 ) {
						// Invalid key1 - Session Timeut
						$response["statusCode"] = "999";
						$response["result"] = "Error";
						$response["message"] = "Failure: Session Timeout";
						$response["signature"] = 0;
						error_log(json_encode($response));
						echo json_encode($response);
						return;
					}
					//$transaction_query = "(select a.fin_service_order_no as order_no1, c.fin_request_id as order_no, if(a.service_feature_code='CIN','Cash-In (Account)',if(a.service_feature_code='COU','Cash-Out (Account)', if(a.service_feature_code = 'MP0', 'Cash-Out (Card)', 'Other'))) as order_type, IFNULL(b.name, '-') as vendor, i_format(a.request_amount) as request_amount, i_format(a.total_amount) as total_amount, i_format(a.ams_charge + a.partner_charge) as service_charge, i_format(a.other_charge) as other_charge, ifnull(a.customer_name, '-') as customer_name, a.mobile_no, a.auth_code as session_id, a.reference_no, a.comment, c.approver_comments, a.date_time, c.status, c.sender_name from fin_service_order a left join bank_master b on a.bank_id = b.bank_master_id left join fin_request c on a.fin_service_order_no = c.order_no where a.user_id = $userId ) union all (select a.e_transaction_id as order_no, 'Recharge' as order_type, b.operator_code as vendor, i_format(a.request_amount) as request_amount, i_format(a.total_amount) as total_amount, i_format(a.ams_charge+a.partner_charge) as service_charge,  a.other_charge as other_charge, '-' as customer_name, a.mobile_number as mobile_no, '' as session_id, a.reference_no, a.opr_plan_desc as comments, '' as approver_comments, a.date_time, 'S' as status, '' as sender_name from evd_transaction a, operator b where a.operator_id = b.operator_id and a.user_id = $userId) order by date_time desc limit 10";
					//$transaction_query = "(select a.fin_service_order_no as order_no1, c.fin_request_id as order_no, if(a.service_feature_code='CIN','Cash-In (Account)',if(a.service_feature_code='COU','Cash-Out (Account)', if(a.service_feature_code = 'MP0', 'Cash-Out (Card)', 'Other'))) as order_type, IFNULL(b.name, '-') as vendor, i_format(a.request_amount) as request_amount, i_format(a.total_amount) as total_amount, i_format(a.ams_charge + a.partner_charge) as service_charge, i_format(a.other_charge) as other_charge, ifnull(a.customer_name, '-') as customer_name, a.mobile_no, a.auth_code as session_id, a.reference_no, a.comment, c.approver_comments, a.date_time, c.status, c.sender_name from fin_service_order a left join bank_master b on a.bank_id = b.bank_master_id left join fin_request c on a.fin_service_order_no = c.order_no where a.user_id = $userId ) union all (select a.e_transaction_id as order_no, 'Recharge' as order_type, b.operator_code as vendor, i_format(a.request_amount) as request_amount, i_format(a.total_amount) as total_amount, i_format(a.ams_charge+a.partner_charge) as service_charge,  a.other_charge as other_charge, '-' as customer_name, a.mobile_number as mobile_no, '' as session_id, a.reference_no, a.opr_plan_desc as comments, '' as approver_comments, a.date_time, 'S' as status, '' as sender_name from evd_transaction a, operator b where a.operator_id = b.operator_id and a.user_id = $userId) order by date_time desc limit 10";
					//$transaction_query = "(select a.fin_service_order_no as order_no, if(a.service_feature_code='CIN','Cash-In (Account)',if(a.service_feature_code='COU','Cash-Out (Account)', if(a.service_feature_code = 'MP0', 'Cash-Out (Card)', if(a.service_feature_code = 'MP9', 'Fund Wallet','Other')))) as order_type, IFNULL(b.name, '-') as vendor, i_format(a.request_amount) as request_amount, i_format(a.total_amount) as total_amount, i_format(a.ams_charge + a.partner_charge + a.agent_charge) as service_charge, i_format(a.other_charge + a.stamp_charge) as other_charge, ifnull(a.customer_name, '-') as customer_name, a.mobile_no, a.auth_code as session_id, a.reference_no, a.comment, c.approver_comments, a.date_time, c.status, c.sender_name from fin_service_order a left join bank_master b on a.bank_id = b.bank_master_id left join fin_request c on a.fin_service_order_no = c.order_no where a.user_id = $userId ) union all (select a.e_transaction_id as order_no, 'Recharge' as order_type, b.operator_code as vendor, i_format(a.request_amount) as request_amount, i_format(a.total_amount) as total_amount, i_format(a.ams_charge+a.partner_charge) as service_charge,  a.other_charge as other_charge, '-' as customer_name, a.mobile_number as mobile_no, '' as session_id, a.reference_no, a.opr_plan_desc as comments, '' as approver_comments, a.date_time, 'S' as status, '' as sender_name from evd_transaction a, operator b where a.operator_id = b.operator_id and a.user_id = $userId)  union all (select a.bp_service_order_no as order_no, if(a.service_feature_code='CIN','Cash-In (Account)',if(a.service_feature_code='COU','Cash-Out (Account)', if(a.service_feature_code = 'MP0', 'Cash-Out (Card)', if(a.service_feature_code='PEB','Bill Payment','Others')))) as order_type, IFNULL(c.bp_bank_code, '-') as vendor, i_format(a.request_amount) as request_amount, i_format(a.total_amount) as total_amount, i_format(a.ams_charge + a.partner_charge + a.agent_charge) as service_charge, i_format(a.other_charge + a.stamp_charge) as other_charge, ifnull(c.account_name, '-') as customer_name, c.mobile_no, c.session_id as session_id, c.bp_transaction_id as reference_no, c.comments as comment, c.approver_comments, a.date_time, c.status, c.account_no as sender_name from bp_service_order a, bp_request c WHERE a.bp_service_order_no = c.order_no and a.user_id = $userId ) union all (select a.acc_service_order_no as order_no, if(a.service_feature_code='BWO','Wallet Open',if(a.service_feature_code='BST','Account Status',if(a.service_feature_code='BAO','Account Open','Others'))) as order_type, IFNULL(b.name , '-') as vendor, 0.00 as request_amount, i_format(a.total_amount) as total_amount, i_format(a.ams_charge + a.partner_charge) as service_charge, i_format(a.other_charge + a.stamp_charge) as other_charge, ifnull(CONCAT(c.first_name, ' ', c.last_name), '-') as customer_name, c.mobile as mobile_no, c.request_id as session_id, IFNULL(c.account_number , '-') as reference_no, c.bvn as comment, d.name as approver_comments, a.date_time, c.status, c.city as sender_name from acc_service_order a left join bank_master b on a.bank_id = b.bank_master_id left join acc_request c on a.acc_service_order_no = c.order_no left join local_govt_list d on d.local_govt_id = c.local_govt_id  WHERE a.acc_service_order_no = c.order_no and a.user_id = $userId ) order by date_time desc limit 10";
					$transaction_query = "(select a.fin_service_order_no as order_no, if(a.service_feature_code='CIN','Cash-In (Account)',if(a.service_feature_code='COU','Cash-Out (Account)', if(a.service_feature_code = 'MP0', 'Cash-Out (Card)', if(a.service_feature_code = 'MP9', 'Fund Wallet','Other')))) as order_type, IFNULL(b.name, '-') as vendor, i_format(a.request_amount) as request_amount, i_format(a.total_amount) as total_amount, i_format(a.ams_charge + a.partner_charge + a.agent_charge) as service_charge, i_format(a.other_charge + a.stamp_charge) as other_charge, ifnull(a.customer_name, '-') as customer_name, a.mobile_no, a.auth_code as session_id, a.reference_no, a.comment, if(c.service_feature_code='CIN',c.account_no,c.approver_comments) as approver_comments, a.date_time, c.status, c.sender_name from fin_service_order a left join bank_master b on a.bank_id = b.bank_master_id left join fin_request c on a.fin_service_order_no = c.order_no where a.user_id = $userId ) union all (select a.e_transaction_id as order_no, 'Recharge' as order_type, b.operator_code as vendor, i_format(a.request_amount) as request_amount, i_format(a.total_amount) as total_amount, i_format(a.ams_charge+a.partner_charge) as service_charge,  a.other_charge as other_charge, '-' as customer_name, a.mobile_number as mobile_no, '' as session_id, a.reference_no, a.opr_plan_desc as comments, '' as approver_comments, a.date_time, 'S' as status, '' as sender_name from evd_transaction a, operator b where a.operator_id = b.operator_id and a.user_id = $userId)  union all (select a.bp_service_order_no as order_no, if(a.service_feature_code='PEB','Electricity Payment',if(a.service_feature_code='PIT','Internet Payment', if(a.service_feature_code = 'PTV', 'Cable TV Payment', if(a.service_feature_code='PTN','Tin Payment', if(a.service_feature_code='PFR','FRSC/Imm Payment', if(a.service_feature_code = 'PED', 'Education Payment', if(a.service_feature_code = 'PLT', 'Lottery Payment', if(a.service_feature_code = 'PFT', 'Flight Payment', 'Others')))))))) as order_type, IFNULL(c.bp_bank_code, '-') as vendor, i_format(a.request_amount) as request_amount, i_format(a.total_amount) as total_amount, i_format(a.ams_charge + a.partner_charge + a.agent_charge) as service_charge, i_format(a.other_charge + a.stamp_charge) as other_charge, ifnull(c.account_name, '-') as customer_name, c.mobile_no, c.session_id as session_id, c.bp_transaction_id as reference_no, c.comments as comment, c.approver_comments, a.date_time, c.status, c.account_no as sender_name from bp_service_order a, bp_request c WHERE a.bp_service_order_no = c.order_no and a.user_id = $userId ) union all (select a.acc_service_order_no as order_no, if(a.service_feature_code='BWO','Wallet Open',if(a.service_feature_code='BCL','Card Link',if(a.service_feature_code='BAO','Account Open','Others'))) as order_type, IFNULL(b.name , '-') as vendor, 0.00 as request_amount, i_format(a.total_amount) as total_amount, i_format(a.ams_charge + a.partner_charge) as service_charge, i_format(a.other_charge + a.stamp_charge) as other_charge, ifnull(CONCAT(ifnull(c.first_name,''), ' ', ifnull(c.last_name,'')), '-') as customer_name, c.mobile as mobile_no, c.request_id as session_id, IFNULL(c.account_number , '-') as reference_no, ifnull(c.bvn,'') as comment, ifnull(d.name,'') as approver_comments, a.date_time, c.status, ifnull(c.city,'') as sender_name from acc_service_order a left join bank_master b on a.bank_id = b.bank_master_id left join acc_request c on a.acc_service_order_no = c.order_no left join local_govt_list d on d.local_govt_id = c.local_govt_id  WHERE a.acc_service_order_no = c.order_no and a.user_id = $userId ) order by date_time desc limit 10";
                    			error_log("transaction_query = ".$transaction_query);
					$transaction_result = mysqli_query($con, $transaction_query);
					if($transaction_result) {
                        			$response["transactions"] = array();
						if (!empty($transaction_result) && mysqli_num_rows($transaction_result) > 0 ) {
							$response["result"] = "Success";
							$response["message"] = "Successfull Operation";
                            				$response["statusCode"] = "0";
                            				$response["signature"] = $server_signature;
							while ($row = mysqli_fetch_array($transaction_result)) {
								$myTransaction = array();
								$myTransaction["orderNo"] = $row["order_no"];
								$myTransaction["orderType"] = $row["order_type"];
								$myTransaction["vendor"] = $row["vendor"];
								$myTransaction["requestAmount"] = $row["request_amount"];
							    	$myTransaction["totalAmount"] = $row["total_amount"];
								$myTransaction["serviceCharge"] = $row["service_charge"];
								$myTransaction["otherCharge"] = $row["other_charge"];
								$myTransaction["customerName"] = $row["customer_name"];
								$myTransaction["mobileNo"] = $row["mobile_no"];
								$myTransaction["sessionId"] = $row["session_id"];
								$myTransaction["referenceNo"] = $row["reference_no"];
								$myTransaction["comment"] = $row["comment"];
								$myTransaction["approverComment"] = $row["approver_comments"];
								$myTransaction["dateTime"] = $row["date_time"];
								$myTransaction["status"] = $row["status"];
								$myTransaction["senderName"] = $row["sender_name"];
								array_push($response["transactions"], $myTransaction);
							}
						}else {
							// No Data
							$response["result"] = "Success";
							$response["message"] = "No Data found";
                            				$response["statusCode"] = "100";
                            				$response["signature"] = $server_signature;
						}
					}
					else {
						// DB failure
						$response["result"] = "Error";
						$response["statusCode"] = "200";
                        			$response["message"] = "Failure: Error in reading query from DB";
                        			$response["signature"] = $server_signature;
					}
				}else {
					// Invalid Singature
					$response["statusCode"] = "300";
					$response["result"] = "Error";
                    			$response["message"] = "Failure: Invalid request";
                    			$response["signature"] = $server_signature;
				}
			}else {
				// Invalid Data
				$response["statusCode"] = "400";
				$response["result"] = "Error";
                		$response["message"] = "Failure: Invalid Data";
                		$response["signature"] = 0;
			}
		}else if(isset($data -> operation) && $data -> operation == 'REPORT_RECEIPT') {
			error_log("inside operation == REPORT_RECEIPT method");

			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1)
					&& isset($data->partyCode) && !empty($data->partyCode) && isset($data->partyType) && !empty($data->partyType)
                    			&& isset($data->countryId) && !empty($data->countryId) && isset($data->stateId) && !empty($data->stateId)
					&& isset($data->userId) && !empty($data->userId) && isset($data->receiptType) && !empty($data->receiptType)
			){
				error_log("inside all inputs are set correctly");
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature= $data->signature;
                		$key1 = $data->key1;
				$userId = $data->userId;
				$orderNo = $data->orderNo;
				$reportDate = $data->reportDate;
				$receiptType = $data->receiptType;
				$session_validity = AGENT_SESSION_VALID_TIME;

				error_log("signature = ".$signature.", key1 = ".$key1);
				date_default_timezone_set('Africa/Lagos');
				$nday = date('z')+1;
				$nyear = date('Y');
				$nth_day_prime = get_prime($nday);
				$nth_year_day_prime = get_prime($nday+$nyear);
				$local_signature = $nday + $nth_day_prime;
				error_log("local_signature = ".$local_signature);
				$server_signature = $nth_year_day_prime + $nday + $nyear;
				error_log("server_signature = ".$server_signature);

				if ( $local_signature == $signature ) {
					$validate_result = validateKey1($key1, $userId, $session_validity, 'V', $con);
					error_log("validateKey1 result = ".$validate_result);
					if ( $validate_result != 0 ) {
						// Invalid key1 - Session Timeut
						$response["statusCode"] = "999";
						$response["result"] = "Error";
						$response["message"] = "Failure: Session Timeout";
						$response["signature"] = 0;
						error_log(json_encode($response));
						echo json_encode($response);
						return;
					}
					if($receiptType == "Recharge"){
						if ( $reportDate != "" && $orderNo != "" ) {
							$receipt_query = "select a.e_transaction_id as order_no, 'Recharge' as order_type, b.operator_code as vendor, i_format(a.request_amount) as request_amount, i_format(a.total_amount) as total_amount, i_format(a.ams_charge+a.partner_charge) as service_charge,  a.other_charge as other_charge, '-' as customer_name, a.mobile_number as mobile_no, '' as session_id, a.reference_no, a.opr_plan_desc as comments, '' as approver_comments, a.date_time, 'S' as status, '' as sender_name from evd_transaction a, operator b where a.operator_id = b.operator_id and a.user_id = $userId and a.e_transaction_id = $orderNo and date(date_time) = '$reportDate'";
						}else {
							if ( $reportDate != "" ) {
								$receipt_query = "select a.e_transaction_id as order_no, 'Recharge' as order_type, b.operator_code as vendor, i_format(a.request_amount) as request_amount, i_format(a.total_amount) as total_amount, i_format(a.ams_charge+a.partner_charge) as service_charge,  a.other_charge as other_charge, '-' as customer_name, a.mobile_number as mobile_no, '' as session_id, a.reference_no, a.opr_plan_desc as comments, '' as approver_comments, a.date_time, 'S' as status, '' as sender_name from evd_transaction a, operator b where a.operator_id = b.operator_id and a.user_id = $userId and date(date_time) = '$reportDate'";
							}else {
								$receipt_query = "select a.e_transaction_id as order_no, 'Recharge' as order_type, b.operator_code as vendor, i_format(a.request_amount) as request_amount, i_format(a.total_amount) as total_amount, i_format(a.ams_charge+a.partner_charge) as service_charge,  a.other_charge as other_charge, '-' as customer_name, a.mobile_number as mobile_no, '' as session_id, a.reference_no, a.opr_plan_desc as comments, '' as approver_comments, a.date_time, 'S' as status, '' as sender_name from evd_transaction a, operator b where a.operator_id = b.operator_id and a.user_id = $userId and a.e_transaction_id = $orderNo";
							}
						}
					}else if($receiptType == "Cash-In/Cash-Out"){
						if ( $reportDate != "" && $orderNo != "" ) {
							$receipt_query = "select a.fin_service_order_no as order_no, if(a.service_feature_code='CIN','Cash-In (Account)',if(a.service_feature_code='COU','Cash-Out (Account)', if(a.service_feature_code = 'MP0', 'Cash-Out (Card)', 'Other'))) as order_type, IFNULL(b.name, '-') as vendor, i_format(a.request_amount) as request_amount, i_format(a.total_amount) as total_amount, i_format(ifnull(c.service_charge,0) + a.partner_charge) as service_charge, i_format(a.other_charge) as other_charge, ifnull(a.customer_name, '-') as customer_name, a.mobile_no, a.auth_code as session_id, a.reference_no, a.comment, if(c.service_feature_code='CIN',c.account_no,c.approver_comments) as approver_comments, a.date_time, c.status, c.sender_name from fin_service_order a left join bank_master b on a.bank_id = b.bank_master_id left join fin_request c on a.fin_service_order_no = c.order_no where a.user_id = $userId and a.fin_service_order_no = $orderNo and date(a.date_time) = '$reportDate'";
						}else {
							if ( $reportDate != "" ) {
								$receipt_query = "select a.fin_service_order_no as order_no, if(a.service_feature_code='CIN','Cash-In (Account)',if(a.service_feature_code='COU','Cash-Out (Account)', if(a.service_feature_code = 'MP0', 'Cash-Out (Card)', 'Other'))) as order_type, IFNULL(b.name, '-') as vendor, i_format(a.request_amount) as request_amount, i_format(a.total_amount) as total_amount, i_format(ifnull(c.service_charge,0) + a.partner_charge) as service_charge, i_format(a.other_charge) as other_charge, ifnull(a.customer_name, '-') as customer_name, a.mobile_no, a.auth_code as session_id, a.reference_no, a.comment, if(c.service_feature_code='CIN',c.account_no,c.approver_comments) as approver_comments, a.date_time, c.status, c.sender_name from fin_service_order a left join bank_master b on a.bank_id = b.bank_master_id left join fin_request c on a.fin_service_order_no = c.order_no where a.user_id = $userId and date(a.date_time) = '$reportDate'";
							}else {
								$receipt_query = "select a.fin_service_order_no as order_no, if(a.service_feature_code='CIN','Cash-In (Account)',if(a.service_feature_code='COU','Cash-Out (Account)', if(a.service_feature_code = 'MP0', 'Cash-Out (Card)', 'Other'))) as order_type, IFNULL(b.name, '-') as vendor, i_format(a.request_amount) as request_amount, i_format(a.total_amount) as total_amount, i_format(ifnull(c.service_charge,0) + a.partner_charge) as service_charge, i_format(a.other_charge) as other_charge, ifnull(a.customer_name, '-') as customer_name, a.mobile_no, a.auth_code as session_id, a.reference_no, a.comment, if(c.service_feature_code='CIN',c.account_no,c.approver_comments) as approver_comments, a.date_time, c.status, c.sender_name from fin_service_order a left join bank_master b on a.bank_id = b.bank_master_id left join fin_request c on a.fin_service_order_no = c.order_no where a.user_id = $userId and a.fin_service_order_no = $orderNo";
							}
						}
					}else if($receiptType == "Bill Payment"){
						if ( $reportDate != "" && $orderNo != "" ) {
							$receipt_query = "select a.bp_service_order_no as order_no, if(a.service_feature_code='PEB','Electricity Payment',if(a.service_feature_code='PIT','Internet Payment', if(a.service_feature_code = 'PTV', 'Cable TV Payment', if(a.service_feature_code='PTN','Tin Payment', if(a.service_feature_code='PFR','FRSC/Imm Payment', if(a.service_feature_code = 'PED', 'Education Payment', if(a.service_feature_code = 'PLT', 'Lottery Payment', if(a.service_feature_code = 'PFT', 'Flight Payment', 'Others')))))))) as order_type, IFNULL(c.bp_bank_code, '-') as vendor, i_format(a.request_amount) as request_amount, i_format(a.total_amount) as total_amount, i_format(a.ams_charge + a.partner_charge + a.agent_charge) as service_charge, i_format(a.other_charge + a.stamp_charge) as other_charge, ifnull(c.account_name, '-') as customer_name, c.mobile_no, c.session_id as session_id, c.bp_transaction_id as reference_no, c.comments as comment, c.approver_comments, a.date_time, c.status, c.account_no as sender_name from bp_service_order a, bp_request c WHERE a.bp_service_order_no = c.order_no and a.user_id = $userId and a.bp_service_order_no = $orderNo and date(a.date_time) = '$reportDate'";
						}else {
							if ( $reportDate != "" ) {
								$receipt_query = "select a.bp_service_order_no as order_no, if(a.service_feature_code='PEB','Electricity Payment',if(a.service_feature_code='PIT','Internet Payment', if(a.service_feature_code = 'PTV', 'Cable TV Payment', if(a.service_feature_code='PTN','Tin Payment', if(a.service_feature_code='PFR','FRSC/Imm Payment', if(a.service_feature_code = 'PED', 'Education Payment', if(a.service_feature_code = 'PLT', 'Lottery Payment', if(a.service_feature_code = 'PFT', 'Flight Payment', 'Others')))))))) as order_type, IFNULL(c.bp_bank_code, '-') as vendor, i_format(a.request_amount) as request_amount, i_format(a.total_amount) as total_amount, i_format(a.ams_charge + a.partner_charge + a.agent_charge) as service_charge, i_format(a.other_charge + a.stamp_charge) as other_charge, ifnull(c.account_name, '-') as customer_name, c.mobile_no, c.session_id as session_id, c.bp_transaction_id as reference_no, c.comments as comment, c.approver_comments, a.date_time, c.status, c.account_no as sender_name from bp_service_order a, bp_request c WHERE a.bp_service_order_no = c.order_no and a.user_id = $userId  and date(a.date_time) = '$reportDate'";
							}else {
								$receipt_query = "select a.bp_service_order_no as order_no, if(a.service_feature_code='PEB','Electricity Payment',if(a.service_feature_code='PIT','Internet Payment', if(a.service_feature_code = 'PTV', 'Cable TV Payment', if(a.service_feature_code='PTN','Tin Payment', if(a.service_feature_code='PFR','FRSC/Imm Payment', if(a.service_feature_code = 'PED', 'Education Payment', if(a.service_feature_code = 'PLT', 'Lottery Payment', if(a.service_feature_code = 'PFT', 'Flight Payment', 'Others')))))))) as order_type, IFNULL(c.bp_bank_code, '-') as vendor, i_format(a.request_amount) as request_amount, i_format(a.total_amount) as total_amount, i_format(a.ams_charge + a.partner_charge + a.agent_charge) as service_charge, i_format(a.other_charge + a.stamp_charge) as other_charge, ifnull(c.account_name, '-') as customer_name, c.mobile_no, c.session_id as session_id, c.bp_transaction_id as reference_no, c.comments as comment, c.approver_comments, a.date_time, c.status, c.account_no as sender_name from bp_service_order a, bp_request c WHERE a.bp_service_order_no = c.order_no and a.user_id = $userId and a.bp_service_order_no = $orderNo";
							}
						}
					}else if($receiptType == "Account Services"){
						if ( $reportDate != "" && $orderNo != "" ) {
							$receipt_query = "select a.acc_service_order_no as order_no, if(a.service_feature_code='BWO','Wallet Open',if(a.service_feature_code='BCL','Card Link',if(a.service_feature_code='BAO','Account Open','Others'))) as order_type, IFNULL(b.name , '-') as vendor, 0 as request_amount, i_format(a.total_amount) as total_amount, i_format(a.ams_charge + a.partner_charge) as service_charge, i_format(a.other_charge + a.stamp_charge) as other_charge, ifnull(CONCAT(c.first_name, ' ', ifnull(c.last_name,'')), '-') as customer_name, c.mobile as mobile_no, c.request_id as session_id, IFNULL(c.account_number , '-') as reference_no, ifnull(c.bvn,'') as comment, d.name as approver_comments, a.date_time, c.status, ifnull(c.city,'') as sender_name from acc_service_order a left join bank_master b on a.bank_id = b.bank_master_id left join acc_request c on a.acc_service_order_no = c.order_no left join local_govt_list d on d.local_govt_id = c.local_govt_id  WHERE a.acc_service_order_no = c.order_no and a.user_id = $userId and a.acc_service_order_no = $orderNo and date(a.date_time) = '$reportDate'";
						}else {
							if ( $reportDate != "" ) {
								$receipt_query = "select a.acc_service_order_no as order_no, if(a.service_feature_code='BWO','Wallet Open',if(a.service_feature_code='BCL','Card Link',if(a.service_feature_code='BAO','Account Open','Others'))) as order_type, IFNULL(b.name , '-') as vendor, 0 as request_amount, i_format(a.total_amount) as total_amount, i_format(a.ams_charge + a.partner_charge) as service_charge, i_format(a.other_charge + a.stamp_charge) as other_charge, ifnull(CONCAT(c.first_name, ' ', ifnull(c.last_name,'')), '-') as customer_name, c.mobile as mobile_no, c.request_id as session_id, IFNULL(c.account_number , '-') as reference_no, ifnull(c.bvn,'') as comment, d.name as approver_comments, a.date_time, c.status, ifnull(c.city,'') as sender_name from acc_service_order a left join bank_master b on a.bank_id = b.bank_master_id left join acc_request c on a.acc_service_order_no = c.order_no left join local_govt_list d on d.local_govt_id = c.local_govt_id  WHERE a.acc_service_order_no = c.order_no and a.user_id = $userId  and date(a.date_time) = '$reportDate'";
							}else {
								$receipt_query = "select a.acc_service_order_no as order_no, if(a.service_feature_code='BWO','Wallet Open',if(a.service_feature_code='BCL','Card Link',if(a.service_feature_code='BAO','Account Open','Others'))) as order_type, IFNULL(b.name , '-') as vendor, 0 as request_amount, i_format(a.total_amount) as total_amount, i_format(a.ams_charge + a.partner_charge) as service_charge, i_format(a.other_charge + a.stamp_charge) as other_charge, ifnull(CONCAT(c.first_name, ' ', ifnull(c.last_name,'')), '-') as customer_name, c.mobile as mobile_no, c.request_id as session_id, IFNULL(c.account_number , '-') as reference_no, ifnull(c.bvn,'') as comment, d.name as approver_comments, a.date_time, c.status, ifnull(c.city,'') as sender_name from acc_service_order a left join bank_master b on a.bank_id = b.bank_master_id left join acc_request c on a.acc_service_order_no = c.order_no left join local_govt_list d on d.local_govt_id = c.local_govt_id  WHERE a.acc_service_order_no = c.order_no and a.user_id = $userId and a.acc_service_order_no = $orderNo";
							}
						}
					}

					error_log("receipt_query = ".$receipt_query);
					$receipt_result = mysqli_query($con, $receipt_query);
					if($receipt_result) {
                        			$response["transactions"] = array();
						if (!empty($receipt_result) && mysqli_num_rows($receipt_result) > 0 ) {
							$response["result"] = "Success";
							$response["message"] = "Successfull Operation";
                            				$response["statusCode"] = "0";
                            				$response["signature"] = $server_signature;
							while ($row = mysqli_fetch_array($receipt_result)) {
								$myTransaction = array();
								$myTransaction["orderNo"] = $row["order_no"];
								$myTransaction["orderType"] = $row["order_type"];
								$myTransaction["vendor"] = $row["vendor"];
								$myTransaction["requestAmount"] = $row["request_amount"];
							    	$myTransaction["totalAmount"] = $row["total_amount"];
								$myTransaction["serviceCharge"] = $row["service_charge"];
								$myTransaction["otherCharge"] = $row["other_charge"];
								$myTransaction["customerName"] = $row["customer_name"];
								$myTransaction["mobileNo"] = $row["mobile_no"];
								$myTransaction["sessionId"] = $row["session_id"];
								$myTransaction["referenceNo"] = $row["reference_no"];
								$myTransaction["comment"] = $row["comment"];
								$myTransaction["approverComment"] = $row["approver_comments"];
								$myTransaction["dateTime"] = $row["date_time"];
								$myTransaction["status"] = $row["status"];
								$myTransaction["senderName"] = $row["sender_name"];
								array_push($response["transactions"], $myTransaction);

							}
						}else {
							// No Data
							$response["result"] = "Success";
							$response["message"] = "No Data found";
                            				$response["statusCode"] = "100";
                            				$response["signature"] = $server_signature;
						}
					}
					else {
						// DB failure
						$response["result"] = "Error";
						$response["statusCode"] = "200";
                        			$response["message"] = "Failure: Error in reading query from DB";
                        			$response["signature"] = $server_signature;
					}
				}else {
					// Invalid Singature
					$response["statusCode"] = "300";
					$response["result"] = "Error";
                    			$response["message"] = "Failure: Invalid request";
                    			$response["signature"] = $server_signature;
				}
			}else {
				// Invalid Data
				$response["statusCode"] = "400";
				$response["result"] = "Error";
                		$response["message"] = "Failure: Invalid Data";
                		$response["signature"] = 0;
			}
		}
		else {
			// Invalid Operation
			$response["statusCode"] = "500";
			$response["result"] = "Error";
            		$response["message"] = "Failure: Invalid Operation";
            		$response["signature"] = 0;
		}
	}else {
		// Invalid Request Method
		$response["result"] = "success";
		$response["status"] = "600";
        	$response["message"] = "Post Failure";
        	$response["signature"] = 0;
	}
	error_log("my_account ==> ".json_encode($response));
	echo json_encode($response);
?>