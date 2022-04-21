<?php
error_reporting(E_ERROR | E_PARSE);
error_log("inside bvn_check.php");
$error_path = "N";

//Input fields: bvn, firstName, lastName, phone, dob, signature, countryId, stateId, localGovtId, key1, userId
//Output fields: requestStatus, bvn, validity, signature, responseCode, responseDescription, processingStartTime

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	error_log("inside post request method");
	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	
	if ( isset($data->countryId) && !empty($data->countryId) && isset($data->stateId) && !empty($data->stateId) 
			&& isset($data->localGovtId) && !empty($data->localGovtId) && isset($data->bvn) && !empty($data->bvn) 
			&& isset($data->firstName) && !empty($data->firstName) && isset($data->lastName) && !empty($data->lastName)
			&& isset($data->phone) && !empty($data->phone) && isset($data->dob) && !empty($data->dob)
			&& isset($data->key1) && !empty($data->key1) && isset($data->signature) && !empty($data->signature) 
		) {
			
		//$decoded_key1 = base64_decode($data->key1);
		if ( $error_path == "N") {
			$response = array();
			$response["requestStatus"] = "Success";
			$response["bvn"] = $data->bvn;
			$response["validity"] = "VALID";
			$response["signature"] = "status";
			$response["responseCode"] = "status";
			$response["responseDescription"] = "Successful";
			$response["processingStartTime"] = date('Y-m-d h:i:s');
		}else {
			$response = array();
			$response["requestStatus"] = "02";
			$response["responseCode"] = 100;
			$response["bvn"] = $data->bvn;
			$response["signature"] = 100;
			$response["responseDescription"] = "Invalid BVN";
			$response["processingStartTime"] = date('Y-m-d h:i:s');
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