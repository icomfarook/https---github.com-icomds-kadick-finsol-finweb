<?php
	include('../common/admin/configmysql.php');
	include("../common/admin/finsol_crypt.php");
	require '../api/get_prime.php';
	require_once("db_connect.php");
	include ("functions.php");
	error_log("inside pcposapi/agent_ranking.php");
	//error_reporting(E_ALL);
	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		error_log("inside post request method");
        	$data = json_decode(file_get_contents("php://input"));
        	error_log("agent_ranking <== ".json_encode($data));

      		if(isset($data -> operation) && $data -> operation == 'REPORT_AGENT_RANKING') {
			error_log("inside operation == REPORT_AGENT_RANKING method");

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
					$summaryTransaction = array();
					$agent_rank_query = "select a.agent_code, b.party_category_type_name, i_format(c.amount_value) as amount_value, format(c.count_value,0) as count_value from agent_info a, party_category_type b, party_category_target c where a.party_category_type_id = b.party_category_type_id and a.party_category_type_id = c.party_category_type_id and c.party_target_combo_name = 'MONTHLY_RANK_GROUP' and a.agent_code = '$partyCode'";
					error_log("agent_rank_query = ".$agent_rank_query);
					$agent_rank_result = mysqli_query($con, $agent_rank_query);
					$party_category_type_name = "Not Available";
					$monthly_target_amount = "0.00";
					$monthly_target_count = "0";
					if (!empty($agent_rank_result) && mysqli_num_rows($agent_rank_result) > 0 ) {
						while ($row0 = mysqli_fetch_array($agent_rank_result)) {
							$party_category_type_name = $row0["party_category_type_name"];
							$monthly_target_amount = $row0["amount_value"];
							$monthly_target_count = $row0["count_value"];
						}
					}	
					
					$transaction_previous_query = "select b.party_category_type_name as 'ranked_category' from party_rank_month a, party_category_type b where a.ranked_party_category_id = b.party_category_type_id and a.party_code = '$partyCode' and a.run_month between DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 2 MONTH)), INTERVAL 1 DAY) and DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 2 MONTH)), INTERVAL 1 DAY) limit 1";
					error_log("transaction_previous_query = ".$transaction_previous_query);
					$transaction_previous_result = mysqli_query($con, $transaction_previous_query);
					$previous_month_rank = "Not Available";
					if (!empty($transaction_previous_result) && mysqli_num_rows($transaction_previous_result) > 0 ) {
						while ($row1 = mysqli_fetch_array($transaction_previous_result)) {
							$previous_month_rank = $row1["ranked_category"];
						}
					}
					$summaryTransaction["previousMonthRank"] = $previous_month_rank;
					$summaryTransaction["targetAmount"] = $monthly_target_amount;
					$summaryTransaction["targetCount"] = $monthly_target_count;
					$summaryTransaction["rank"] = $party_category_type_name;
					$summaryTransaction["agentCode"] = $partyCode;
					$summaryTransaction["actualAmount"] = "Not Available";
					$summaryTransaction["actualCount"] = "-";
					$summaryTransaction["month"] = "None";
					$summaryTransaction["rankingDailyTransactions"] = array();
										
					$response["result"] = "Success";
					$response["message"] = "Successfull Operation";
					$response["statusCode"] = "0";
					$response["signature"] = $server_signature;
						
					$transaction_query = "select a.party_code, b.party_category_type_name, date_format(a.run_date, '%Y-%b')as month, a.run_date, i_format(a.target_monthly_amount) as target_monthly_amount, format(a.target_monthly_count, 0) as target_monthly_count, format(a.actual_cum_daily_count, 0) as actual_cum_daily_count, i_format(a.actual_cum_daily_amount) as actual_cum_daily_amount, format(a.actual_iso_daily_count, 0) as actual_iso_daily_count, i_format(a.actual_iso_daily_amount) as actual_iso_daily_amount, a.daily_trend from party_rank_day a, agent_info c, party_category_type b where c.agent_code = a.party_code and c.party_category_type_id = b.party_category_type_id and c.agent_code = '$partyCode' and a.run_date between date_sub(current_date(), INTERVAL DAYOFMONTH(current_date())-1 DAY) and current_date() order by a.run_date";
                    			error_log("agent_ranking_query = ".$transaction_query);
					$transaction_result = mysqli_query($con, $transaction_query);
					if($transaction_result) {
						//error_log("1");
						if (!empty($transaction_result) && mysqli_num_rows($transaction_result) > 0 ) {
							//error_log("2");
		            				while ($row = mysqli_fetch_array($transaction_result)) {
		            					//error_log("3");
								$summaryTransaction["month"] = $row["month"];
								$summaryTransaction["actualAmount"] = $row["actual_cum_daily_amount"];
								$summaryTransaction["actualCount"] = $row["actual_cum_daily_count"];
								$dailyTransaction = array();
								$dailyTransaction["cumulativeTotal"] = $row["actual_cum_daily_amount"];
								$dailyTransaction["cumulativeCount"] = $row["actual_cum_daily_count"];
								$dailyTransaction["isolatedTotal"] = $row["actual_iso_daily_amount"];
								$dailyTransaction["isolatedCount"] = $row["actual_iso_daily_count"];
								$dailyTransaction["date"] = $row["run_date"];
								$dailyTransaction["trend"] = $row["daily_trend"];
								array_push($summaryTransaction["rankingDailyTransactions"], $dailyTransaction);
							}
						}
					}
					$response["transaction"] = $summaryTransaction;
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
	error_log("agent_ranking ==> ".json_encode($response));
	echo json_encode($response);
?>