<?php
	include('../common/admin/configmysql.php');
	include("../common/admin/finsol_crypt.php");
	include ("get_prime.php");
	include ("functions.php");	
	require_once ("AesCipher.php");
	error_log("inside pcposapi/cashin_charge_calcuate.php");

	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		error_log("inside post request method");
        	$data = json_decode(file_get_contents("php://input"));
        	error_log("cashin_charge_calculate <== ".json_encode($data));				

		if(isset($data -> operation) && $data -> operation == 'CASHIN_CHARGE_OPERATION') {
			error_log("inside operation == CASHIN_CHARGE_OPERATION method");

			if ( isset($data->productId) && !empty($data->productId) && isset($data->partnerId) && !empty($data->partnerId) 
				&& isset($data->requestAmount) && !empty($data->requestAmount) && isset($data->countryId) && !empty($data->countryId)
				&& isset($data->stateId) && !empty($data->stateId) && isset($data->partyCode) && !empty($data->partyCode)
				&& isset($data->partyType) && !empty($data->partyType) && isset($data->signature) && !empty($data->signature)
				&& isset($data->key1) && !empty($data->key1) && isset($data->userId) && !empty($data->userId)  ) {			
			
				error_log("inside all inputs are set correctly");	
				$productId = $data->productId;
				$partnerId = $data->partnerId;
				$requestAmount = $data->requestAmount;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature= $data->signature;
				$key1 = $data->key1;
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$parentCode = $data->parentCode;
				$partyType = $data->partyType;
				$flexiRate = $data->flexiRate;
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
					error_log("inside local_signature == signature");
					$validate_result = validateKey1($key1, $userId, $session_validity, 'C', $con);
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
					
					if ($partnerId == 1 ) {
						$txtType = "I";
					}else {
						$txtType = "E";
					}
					if ($parentCode == "") {
						$partyCount = 2;
					}else {
						$partyCount = 3;
					}

					$db_flexiRate = "N";
					$flexi_rate_query = "select state_flexi_rate_id from state_flexi_rate where state_id = $stateId and (service_feature_id is null or (service_feature_id = $productId)) and active = 'Y' and (start_date is null or (start_date is not null and date(start_date) <= current_date())) and (expiry_date is null or (expiry_date is not null and date(expiry_date) >= current_date())) order by state_flexi_rate_id limit 1";
					error_log("flexi_rate_query query = ".$flexi_rate_query);
					$flexi_rate_result = mysqli_query($con, $flexi_rate_query);
					if ($flexi_rate_result) {
						$flexi_rate_count = mysqli_num_rows($flexi_rate_result);
						if($flexi_rate_count > 0) {					
							$db_flexiRate = "Y";
						}
					}
										
					if ( "Y" == $flexiRate || "Y" == $db_flexiRate ) {
						$txtType = "F";
						$partyCount = 2;
					}
					
					$get_feature_value_query = "SELECT get_feature_value($countryId, $stateId, null, $productId, $partnerId, $requestAmount, '$txtType', $partyCount, null, null, $userId, -1) as result";
					error_log("get_feature_value query = ".$get_feature_value_query);
					$get_feature_value_result = mysqli_query($con, $get_feature_value_query);
					if ($get_feature_value_result) {										
						$row = mysqli_fetch_assoc($get_feature_value_result); 
						$db_result = $row['result']; 
						error_log("db_result = ".$db_result);
						if ( substr( $db_result, 0, 1 ) === "0" ) {
							$response["chargeDetail"] = $row['result']; 
							$response["statusCode"] = "0";
							$response["signature"] = $server_signature;
							$response["message"] = "CashIn Service Charge responded successfuly";
							$response["result"] = "Success";
							$response["partnerId"] = $partnerId;
							$response["txtType"] = $txtType;
						}else {
							$response["chargeDetail"] = ""; 
							$response["statusCode"] = "5";
							$response["signature"] = $server_signature;
							$response["message"] = "Error in getting charge rate for Agents";
							$response["result"] = "Error";
							$response["partnerId"] = $partnerId;
							$response["txtType"] = $txtType;
						}
					}else {
						// DB failure
						$response["chargeDetail"] = ""; 
						$response["statusCode"] = "10";
						$response["result"] = "Error";
						$response["message"] = "Failure: Error in reading charges from DB";
						$response["partnerId"] = $partnerId;
					}
				}else {
					// Invalid Singature
					$response["chargeDetail"] = ""; 
					$response["statusCode"] = "20";
					$response["result"] = "Error";
					$response["message"] = "Failure: Invalid request";
					$response["partnerId"] = $partnerId;	
				}
			}else {
				// Invalid Data
				$response["chargeDetail"] = ""; 
				$response["statusCode"] = "30";
				$response["result"] = "Error";
				$response["message"] = "Failure: Invalid Data";
				$response["partnerId"] = 0;
				$response["signature"] = 0;	
			}
		}else {
			// Invalid Operation
			$response["chargeDetail"] = ""; 
			$response["statusCode"] = "40";
			$response["result"] = "Error";
			$response["message"] = "Failure: Invalid Operation";
			$response["partnerId"] = 0;
			$response["signature"] = 0;	
		}
	}else {
		// Invalid Request Method
		$response["chargeDetail"] = ""; 
		$response["statusCode"] = "50";
		$response["result"] = "Error";
		$response["message"] = "Failure: Invalid Request Method";
		$response["partnerId"] = 0;
		$response["signature"] = 0;	
	}
	error_log("cashin_charge_calculate ==> ".json_encode($response));
	echo json_encode($response);
?>