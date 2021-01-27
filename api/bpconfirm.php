<?php
error_log("inside bpconfirm.php");
$error_path = "N";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	error_log("inside post request method");
	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	
	if ( isset($data->countryId ) && !empty($data->countryId) && isset($data->stateId) && !empty($data->stateId) 
		&& isset($data->localGovtId) && !empty($data->localGovtId) && isset($data->totalAmount) && !empty($data->totalAmount) 
		&& isset($data->bpAccountNo) && !empty($data->bpAccountNo) && isset($data->partnerId) && !empty($data->partnerId)
		&& isset($data->bpAccountName) && !empty($data->bpAccountName) && isset($data->bpBankCode) && !empty($data->bpBankCode)
		&& isset($data->key1) && !empty($data->key1) && isset($data->signature) && !empty($data->signature) 
		&& isset($data->location) && !empty($data->location) && isset($data->bpTransactionId) && !empty($data->bpTransactionId) 
		&& isset($data->narration) && !empty($data->narration) && isset($data->transactionId) && !empty($data->transactionId) 
		&& isset($data->userId) && !empty($data->userId)
	) {
		
		$decoded_key1 = base64_decode($data->key1);
		if ( $error_path == "N") {
			
			// array for JSON response
			$response = array();
			$response["responseCode"] = 0;
			$response["signature"] = 100;
			$response["accountNumber"] = $data->bpAccountNo;
			$response["accountName"] = $data->bpAccountName;
			$response["totalAmount"] = $data->totalAmount;
			$response["partnerId"] = $data->partnerId;
			$response["transactionId"] = "110008201130151436783305751768";
			$response["nameSessionId"] = "100082181020000825468656510981";
			$response["orderNo"] = 4657;
			$response["paymentReference"] = "ITR202991";
			$response["availableBalance"] = 456789.90;	
			$response["processingStartTime"] = "2020-11-23 15:32:34";	
			$response["responseDescription"] = "Successful";
		}else {
			// array for JSON response
			$response = array();
			$response["responseCode"] = 100;
			$response["accountNo"] = $data->bpAccountNo;
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