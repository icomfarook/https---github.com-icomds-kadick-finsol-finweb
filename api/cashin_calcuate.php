<?php
	include('../common/admin/configmysql.php');
	require '../api/get_prime.php';
	require_once("db_connect.php");
	error_log("inside pcposapi/cashin_calcuate.php");

	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		error_log("inside post request method");
        $data = json_decode(file_get_contents("php://input"));
        error_log("cashin_calcuate <== ".json_encode($data));				

		if(isset($data -> operation) && $data -> operation == 'FINWEB_CASH_IN_CALCULATE') {
			error_log("inside operation == FINWEB_CASH_IN_CALCULATE method");

			if ( isset($data->productId) && empty($data->productId) && isset($data->partnerId) && empty($data->partnerId) 
				&& isset($data->requestAmount) && empty($data->requestAmount) && isset($data->countryId) && empty($data->countryId)
				&& isset($data->stateId) && empty($data->stateId) && isset($data->partyCode) && empty($data->partyCode)
				&& isset($data->parentCode) && empty($data->parentCode) && isset($data->signature) && empty($data->signature)
				&& isset($data->key1) && empty($data->key1) && isset($data->userId) && empty($data->userId)  ) {			
			
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
								
				// connecting to db
				$db = new DB_CONNECT();
				error_log("db_connect done");
				// array for JSON response
				
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
								
				if ( $local_signature == $signature ) {
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

					$query = "SELECT get_feature_value($countryId, $stateId, null, $productId, $partnerId, $requestAmount, '$txtype', $partyCount, null, null, $userId, -1) as result";
					error_log("get_feature_value query = ".$query);
					$result = mysqli_query($con, $query);
					if ($result) {										
						$row = mysqli_fetch_assoc($result); 
						$response["chargeDetail"] = $row['result']; 
						$response["responseCode"] = "0";
						$response["responseDescription"] = "Success";
						$response["partnerId"] = $partnerId;
					}else {
						// DB failure
						$response["chargeDetail"] = ""; 
						$response["responseCode"] = "10";
						$response["responseDescription"] = "Failure: Error in reading charges from DB";
						$response["partnerId"] = $partnerId;
					}
				}else {
					// Invalid Singature
					$response["chargeDetail"] = ""; 
					$response["responseCode"] = "20";
					$response["responseDescription"] = "Failure: Invalid request";
					$response["partnerId"] = $partnerId;	
				}
			}else {
				// Invalid Data
				$response["chargeDetail"] = ""; 
				$response["responseCode"] = "30";
				$response["responseDescription"] = "Failure: Invalid Data";
				$response["partnerId"] = 0;
				$response["signature"] = 0;	
			}
		}else {
			// Invalid Operation
			$response["chargeDetail"] = ""; 
			$response["responseCode"] = "40";
			$response["responseDescription"] = "Failure: Invalid Operation";
			$response["partnerId"] = 0;
			$response["signature"] = 0;	
		}
	}else {
		// Invalid Request Method
		$response["chargeDetail"] = ""; 
		$response["responseCode"] = "50";
		$response["responseDescription"] = "Failure: Invalid Request Method";
		$response["partnerId"] = 0;
		$response["signature"] = 0;	
	}
	error_log("cashin_calcuate ==> ".json_encode($response));
	echo json_encode($response);
}
?>