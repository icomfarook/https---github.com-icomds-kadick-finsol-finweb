<?php
error_log("inside sanef_trigger_otp.php");
$error_path = "N";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	error_log("inside post request method");
	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	
	if ( isset($data->countryId ) && !empty($data->countryId) && isset($data->stateId) && !empty($data->stateId) 
		&& isset($data->localGovtId) && !empty($data->localGovtId) && isset($data->agentCode) && !empty($data->agentCode) 
		&& isset($data->bankCode) && !empty($data->bankCode) && isset($data->requestId) && !empty($data->requestId)
		&& isset($data->accountNumber) && !empty($data->accountNumber) && isset($data->location) && !empty($data->location)
		&& isset($data->key1) && !empty($data->key1) && isset($data->signature) && !empty($data->signature) 
		&& isset($data->superAgentCode) && !empty($data->superAgentCode) && isset($data->userId) && !empty($data->userId)
	) {
		
		$decoded_key1 = base64_decode($data->key1);
		if ( $error_path == "N") {
			
			// array for JSON response
			$response = array();
			$response["responseCode"] = 0;
			$response["signature"] = 100;
			$response["processingStartTime"] = "2020-11-23 15:32:34";	
			$response["responseDescription"] = "Successful";
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
}	else {
	error_log("Invalid Method");
	$response = array();
	$response["responseCode"] = -200;
	$response["signature"] = 100;
	$response["responseDescription"] = "Error: Invalid Method";
	error_log(json_encode($response));
	echo json_encode($response);
}
?>