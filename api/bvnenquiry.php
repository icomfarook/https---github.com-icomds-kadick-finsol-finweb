<?php
error_log("inside bvnenquiry.php");
$error_path = "N";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	error_log("inside post request method");
	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	
	if ( isset($data->countryId ) && !empty($data->countryId) && isset($data->stateId) && !empty($data->stateId) 
		&& isset($data->localGovtId) && !empty($data->localGovtId) && isset($data->partnerId) && !empty($data->partnerId)
		&& isset($data->bvn) && !empty($data->bvn) 
		&& isset($data->key1) && !empty($data->key1) && isset($data->signature) && !empty($data->signature) 
	) {
		
		$decoded_key1 = base64_decode($data->key1);
		//error_log("decode_key1 = ".$decoded_key1);
		if ( $error_path == "N") {
			
			// array for JSON response
			$response = array();
			$response["responseCode"] = 0;
			$response["signature"] = 100;
			$response["processingStartTime"] = "11/15/2019 6:34:58 AM";
			$response["responseDescription"] = "Successful";
			$response["firstName"] = "SAMUAEL";
			$response["middleName"] = "LARRY";
			$response["lastname"] = "AJIBODU";
			$response["enrolmentBank"] = "First Bank";
			$response["mobileNumber"] = "09790865619";
			$response["dateOfBirth"] = "20/11/1978";
			$response["registrationDate"] = "01/01/2017";
			$response["isTimeout"] = "false";
			$response["result"] = "";
			$response["loggerID"] = "";
			$response["hasToken"] = "0";

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