<?php
error_log("inside cashoutpost.php");
$error_path = "N";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	error_log("inside post request method");
	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	
	if ( isset($data->countryId ) && !empty($data->countryId) && isset($data->stateId) && !empty($data->stateId) 
	    && isset($data->localGovtId) && !empty($data->localGovtId) && isset($data->partnerId) && !empty($data->partnerId)
		&& isset($data->accountNo) && !empty($data->accountNo) && isset($data->accountName) && !empty($data->accountName)
		&& isset($data->narration) && !empty($data->narration) && isset($data->accountType) && !empty($data->accountType) 
		&& isset($data->ccv) && !empty($data->ccv) && isset($data->expiryDate) && !empty($data->expiryDate)        
		&& isset($data->paymentToken) && !empty($data->paymentToken) && isset($data->cardname) && !empty($data->cardname)
		&& isset($data->transType) && !empty($data->transType) && isset($data->mobileNumber) && !empty($data->mobileNumber) 
		&& isset($data->requestedAmount) && !empty($data->requestedAmount) && isset($data->totalAmount) && !empty($data->totalAmount) 
		&& isset($data->partnerCharge) && !empty($data->partnerCharge) && isset($data->amsCharge) && !empty($data->amsCharge)
		&& isset($data->transactionId) && !empty($data->transactionId) && isset($data->bankId) && !empty($data->bankId) 
		&& isset($data->key1) && !empty($data->key1) && isset($data->signature) && !empty($data->signature)  
	) {
		
		$decoded_key1 = base64_decode($data->key1);
		//error_log("decode_key1 = ".$decoded_key1);
		$tx_ref = "CO".date('y').date('z').str_pad($data->transactionId, 7,'0',STR_PAD_LEFT);
		error_log("tx_ref = ".$tx_ref);
		if ( $error_path == "N") {
			// array for JSON response
			$response = array();
			$response["responseCode"] = "00";
			$response["signature"] = 100;
			$response["responseDescription"] = "Successful";
			$response["processingStartTime"] = "11/14/2019 5:19:58 PM";
			//$response["transactionRef"] = $data->transactionId;
			$response["transactionRef"] = $tx_ref;
			$response["batchID"] = "2019111417195926600004619";
			$response["datResponseCode"] = "00";
			$response["dataResponseDescription"] = "Successful";
			$response["errorResponseCode"] = "0";
			$response["errorResponseDescription"] = "";
		}else {
			// array for JSON response
			$response = array();
			$response["transactionId"] = $data->transactionId;
			$response["responseCode"] = 100;
			$response["signature"] = 100;
			$response["responseDescription"] = "Insufficent Funds";
			$response["dataResponseCode"] = "100";
			$response["dataResponseDescription"] = "Insufficent Funds";
			$response["errorResponseCode"] = "100";
			$response["errorResponseDescription"] = "Error";
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