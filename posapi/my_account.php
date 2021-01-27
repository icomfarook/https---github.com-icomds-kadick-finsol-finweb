<?php
	include('../common/admin/configmysql.php');
	include("../common/admin/finsol_crypt.php");
	require '../api/get_prime.php';
	require_once("db_connect.php");
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
			){

				error_log("inside all inputs are set correctly");
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature= $data->signature;
				$key1 = $data->key1;

				error_log("signature = ".$signature.", key1 = ".$key1);
				date_default_timezone_set('Africa/Lagos');
				$nday = date('z')+1;
				$nyear = date('Y');
				//error_log( "nday = ".$nday);
				//error_log( "nyear = ".$nyear);
				$nth_day_prime = get_prime($nday);
				$nth_year_day_prime = get_prime($nday+$nyear);
				//error_log("nth_day_prime = ".$nth_day_prime);
				//error_log("nth_year_day_prime = ".$nth_year_day_prime);
				$local_signature = $nday + $nth_day_prime;
				error_log("local_signature = ".$local_signature);
				$server_signature = $nth_year_day_prime + $nday + $nyear;
				error_log("server_signature = ".$server_signature);

				if ( $local_signature == $signature ) {
					if ( $partyCode == "A") {
						$myaccount_query = "select a.agent_code as party_code, a.agent_name as party_name, ifnull(a.parent_code, '-') as parent_code, i_format(b.available_balance) as available_balance, i_format(b.current_balance) as current_balance, i_format(b.daily_limit) as daily_limit, i_format(b.credit_limit) as credit_limit, i_format(b.minimum_balance) as minimum_balance, i_format(b.last_tx_amount) as last_tx_amount, i_format(b.last_tx_date) as last_tx_date, i_format(b.last_tx_no) as last_tx_no from agent_wallet b, agent_info a where a.agent_code = b.agent_code and a.agent_code ='$partyCode'";
					} else if ($partyCode == "C") {
						$myaccount_query = "select a.champion_code as party_code, a.champion_name as party_name, '-' as parent_code, i_format(b.available_balance) as available_balance, i_format(b.current_balance) as current_balance, i_format(b.daily_limit) as daily_limit, i_format(b.credit_limit) as daily_limit, i_format(b.minimum_balance) as minimum_balanace, i_format(b.last_tx_amount) as last_tx_amount, i_format(b.last_tx_date) as last_tx_date, i_format(b.last_tx_no) as last_tx_no from champion_wallet b, champion_info a where a.champion_code = b.champion_code and a.champion_code ='$partyCode'";
					}
					//error_log($my_query2);
					$result = mysqli_query($con,$query);
					if($result) {
						$response["response"] = array();
						if (!empty($result) && mysqli_num_rows($result) > 0 ) {
							$response["result"] = "Success";
							$response["message"] = "Successfull Operation";
							$response["statusCode"] = "0";

							while ($row = mysqli_fetch_array($result)) {
								$myAccount = array();
								$myAccount["partyCode"] = $row["party_code"];
								$myAccount["partyName"] = $row["party_name"];
								$myAccount["partyType"] = $partyCode;
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
						}
					}
					else {
						// DB failure
						$response["result"] = "Error";
						$response["statusCode"] = "200";
						$response["message"] = "Failure: Error in reading query from DB";
					}
				}else {
					// Invalid Singature
					$response["statusCode"] = "300";
					$response["result"] = "Error";
					$response["message"] = "Failure: Invalid request";
				}
			}else {
				// Invalid Data
				$response["statusCode"] = "400";
				$response["result"] = "Error";
				$response["message"] = "Failure: Invalid Data";
			}
		}else {
			// Invalid Operation
			$response["statusCode"] = "500";
			$response["result"] = "Error";
			$response["message"] = "Failure: Invalid Operation";
		}
	}else {
		// Invalid Request Method
		$response["result"] = "success";
		$response["status"] = "600";
		$response["message"] = "Post Failure";
	}
	error_log("my_account ==> ".json_encode($response));
	echo json_encode($response);
?>