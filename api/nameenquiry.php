<?php
error_log("inside nameenquiry.php");
$error_path = "N";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	error_log("inside post request method");
	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	
	if ( isset($data->countryId ) && !empty($data->countryId) && isset($data->stateId) && !empty($data->stateId) 
		&& isset($data->localGovtId) && !empty($data->localGovtId) && isset($data->accountNumber) && !empty($data->accountNumber) 
		&& isset($data->bankId) && !empty($data->bankId) 
		&& isset($data->key1) && !empty($data->key1) && isset($data->signature) && !empty($data->signature) 
	) {
		
		$decoded_key1 = base64_decode($data->key1);
		if ( $error_path == "N") {
			
			// array for JSON response
			$response = array();
			$response["responseCode"] = 0;
			$response["signature"] = 100;
			$response["accountNo"] = $data->accountNo;
			$response["bankId"] = $data->bankId;
			$response["DestinationInstitutionCode"] = "000944";
			$response["channalCode"] = "2";
			$response["kycLevel"] = 3;
			$response["sessionId"] = "000944200128073021000001000021";		
			$response["bvn"] = 11223344550;		
			$response["responseDescription"] = "Successful";
		
			$response["accountName"] = "EMMANUEL ADEMUYIWA AJIBODU";
			$response["description"] = "Successful";
		}else {
			// array for JSON response
			$response = array();
			$response["responseCode"] = 100;
			$response["accountNo"] = $data->accountNo;
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