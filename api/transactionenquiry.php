<?php
	error_log("inside transactionenquiry.php");
	$error_path = "N";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	error_log("inside post request method");
	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	
	if ( isset($data->countryId ) && !empty($data->countryId) && isset($data->stateId) && !empty($data->stateId) 
		&& isset($data->localGovtId) && !empty($data->localGovtId) && isset($data->partnerId) && !empty($data->partnerId)
		&& isset($data->transactionRef) && !empty($data->transactionRef) 
		&& isset($data->key1) && !empty($data->key1) && isset($data->signature) && !empty($data->signature) 
	) {
		
		$decoded_key1 = base64_decode($data->key1);
		//error_log("decode_key1 = ".$decoded_key1);
		if ( $error_path == "N") {
			$dataref = $data->transactionRef;
			// array for JSON response
			$response = array();
			$response["responseCode"] = 0;
			$response["signature"] = 100;
			$response["processingStartTime"] = "11/15/2019 6:34:58 AM";
			$response["pesponseDescription"] = "Successful";
			$response["transactionRef"] = $dataref;
			$response["isReversed"] = "false";
			$response["reversalDate"] = "";
			$response["agencyCode"] = "";
			$response["agentRequestId"] = "";
			$response["amount"] = 400;
			$response["responseDescription"] = "Successful";
			$response["responseCode"] = "00";
			$response["status"] = "PROCESSED";
			$response["processed"] = "true";
			$response["transactionDate"] = "2019-11-15T06:38:35.97";
			$response["transactionID"] = "2019111506383279300002907";
			$response["creditAccount"] = "2255282794";
			$response["debitAccount"] = "2020478975";
			$response["operation"] = "CASH_IN";
			$response["transDescription"] = "Successful";
		}else {
			// array for JSON response
			$response = array();
			$response["responseCode"] = 100;
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
}else {
	error_log("Invalid Method");
	$response = array();
	$response["responseCode"] = -200;
	$response["signature"] = 100;
	$response["responseDescription"] = "Error: Invalid Method";
	error_log(json_encode($response));
	echo json_encode($response);
}
?>