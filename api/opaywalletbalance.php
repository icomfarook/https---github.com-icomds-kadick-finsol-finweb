<?php

error_log("inside opaywalletbalance.php");
$error_path = "N";
$second_validation = "N";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	error_log("inside post request method");
	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	
	if ( isset($data->countryId ) && !empty($data->countryId) && isset($data->stateId) && !empty($data->stateId) 
		&& isset($data->localGovtId) && !empty($data->localGovtId) && isset($data->signature) && !empty($data->signature) 
		&& isset($data->key1) && !empty($data->key1) && isset($data->userId) && !empty($data->userId)
		
	) {
		$decoded_key1 = base64_decode($data->key1);
	
	if ( $error_path == "N") {
		$response = array();
		$response["responseCode"] = 0;
		$response["responseDescription"] = "Success";
		$response["signature"] = 20471;
		$response["processingStartTime"] = date('Y-m-d h:i:s');
		$response["code"] = "0000";
		$response["message"] = "SUCCESSFUL";
		$response["cashBalanceCurrency"] = "NGN";
		$response["cashBalance"] = 100000.00;
		$response["bonusBalanceCurrency"] = "NGN";
		$response["bonusBalance"] = 100000.00;
		$response["freezeBalanceCurrency"] = "NGN";
		$response["freezeBalance"] = "100000.00";
		$response["pendingBalance"] = "0.0";
		$response["updatedAt"] = "2021-09-07T11:04:38.862Z";
		$response["createdAt"] = "2021-09-07T08:45:56.878Z";
		$response["name"] = "Kadick Integrated Limited";

	}else {
		$response["responseCode"] = 25;
		$response["responseDescription"] = "Error";
		$response["signature"] = 20471;
		$response["processingStartTime"] = date('Y-m-d h:i:s');
		
		$response["code"] = "4000";
		$response["message"] = "ERROR";

	}
	error_log(json_encode($response));
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