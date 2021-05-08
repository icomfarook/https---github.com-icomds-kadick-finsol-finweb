<?php
error_log("inside cashoutussdpost.php");
$error_path = "N";
$second_validation = "N";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	error_log("inside post request method");
	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	
	if ( isset($data->countryId ) && !empty($data->countryId) && isset($data->stateId) && !empty($data->stateId) 
		&& isset($data->localGovtId) && !empty($data->localGovtId) && isset($data->signature) && !empty($data->signature) 
		&& isset($data->key1) && !empty($data->key1) && isset($data->userId) && !empty($data->userId)
		&& isset($data->totalAmount) && !empty($data->totalAmount) 
		//&& isset($data->transactionType) && !empty($data->transactionType)
		&& isset($data->orderId) && !empty($data->orderId) && isset($data->transactionId) && !empty($data->transactionId)
		
	) {
		$decoded_key1 = base64_decode($data->key1);
		if ( $error_path == "N") {
			
			// array for JSON response
			$response = array();
			$response["responseCode"] = 0;
			$response["signature"] = 100;
			
			$response["result"] = "Success";
			$response["statusCode"] = 0;
			$response["orderNo"] = $data->orderId;
			$response["transactionId"] = $data->transactionId;
			$response["reference"] = "2".$data->transactionId."10";
			$response["transactionId"] = "1901230100000".$data->transactionId;
			$response["amount"] = $data->totalAmount;
			$response["cpTraceId"] = "09283474728";
			$response["cpResponseCode"] = "00";
			$response["cpResponseMessage"] = "Success";
			$response["partnerId"] = 12;
			//$response["message"] = "CashOut USSD order with Order # ".$data->orderId." is trigerred. Use Short Code & Reference No to initiate USSD from Customer registered mobile";
				
			$response["processingStartTime"] = "2020-11-22 15:32:34";	
			$response["responseDescription"] = "Successful";
		}else {
			// array for JSON response
			$response = array();
			$response["responseCode"] = 100;
			$response["balance"] = "0";
			$response["signature"] = 100;
			$response["responseDescription"] = "Error";
		}
		error_log("before sending ==> ".json_encode($response));
	       	echo json_encode($response);
	}else {
		error_log("Invalid Data");
		$response = array();
		$response["responseCode"] = -100;
		$response["signature"] = 100;
		$response["responseDescription"] = "Invalid Data";
		error_log(json_encode($response));
		echo json_encode($response);
	}
}	
else {
	error_log("Invalid Method");
	$response = array();
	$response["responseCode"] = -200;
	$response["signature"] = 100;
	$response["responseDescription"] = "Error: Invalid Method";
	error_log(json_encode($response));
	echo json_encode($response);
}
?>