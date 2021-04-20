<?php
error_log("inside bpwalletbalance.php");
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
			
			// array for JSON response
			$response = array();
			$response["responseCode"] = 0;
			$response["signature"] = 100;
			
			$response["pendingBalance"] = "0.00";
			$response["balance"] = "17754.455000000005";
			$response["updatedAt"] = "2021-04-07T11:04:38.862Z";
			$response["createdAt"] = "2021-02-23T08:45:56.878Z";
			$response["name"] = "Kadick Integrated Limited";
			$response["balanceStatus"] = "success";	
			
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