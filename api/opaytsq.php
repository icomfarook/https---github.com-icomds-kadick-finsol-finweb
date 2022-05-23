<?php

error_log("inside opaytsq.php");
$error_path = "Y";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	error_log("inside post request method");
	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	
	if ( isset($data->countryId ) && !empty($data->countryId) && isset($data->stateId) && !empty($data->stateId) 
		&& isset($data->localGovtId) && !empty($data->localGovtId) && isset($data->signature) && !empty($data->signature) 
		&& isset($data->key1) && !empty($data->key1) && isset($data->userId) && !empty($data->userId)
		&& isset($data->orderNo) && !empty($data->orderNo) && isset($data->reference) && !empty($data->reference)
		
	) {
		$decoded_key1 = base64_decode($data->key1);
	
		if ( $error_path == "N") {
			$response = array();
			$response["responseCode"] = 0;
			$response["responseDescription"] = "Success";
			$response["signature"] = 20471;
			$response["processingStartTime"] = date('Y-m-d h:i:s');
			$response["orderNo"] = $data->orderNo;
			$response["reference"] = $data->reference;
			$response["status"] = "SUCCESS";
			$response["errorMsg"] = "";
		}else {
			$response["responseCode"] = 25;
			$response["responseDescription"] = "Error";
			$response["signature"] = 20471;
			$response["processingStartTime"] = date('Y-m-d h:i:s');
			$response["orderNo"] = $data->orderNo;
			$response["reference"] = $data->reference;
			$response["status"] = "FAIL";
			$response["errorMsg"] = "error";

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