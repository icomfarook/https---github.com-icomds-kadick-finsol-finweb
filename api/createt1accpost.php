<?php
error_log("inside createt1accpost.php");
$error_path = "N";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	error_log("inside post request method");
	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	
	if ( isset($data->countryId ) && !empty($data->countryId) && isset($data->stateId) && !empty($data->stateId) 
		&& isset($data->localGovtId) && !empty($data->localGovtId) && isset($data->partnerId) && !empty($data->partnerId)
		&& isset($data->firstName) && !empty($data->firstName) && isset($data->lastName) && !empty($data->lastName)
		&& isset($data->mobileNumber) && !empty($data->mobileNumber) && isset($data->dateOfBirth) && !empty($data->dateOfBirth) 
		&& isset($data->emailAddress) && !empty($data->emailAddress) && isset($data->bvn) && !empty($data->bvn) 
		&& isset($data->referrerMobileNumber) && !empty($data->referrerMobileNumber) && isset($data->referenceNumber) && !empty($data->referenceNumber) 
		&& isset($data->key1) && !empty($data->key1) && isset($data->signature) && !empty($data->signature) 
	) {
	//	sleep(4);
		$decoded_key1 = base64_decode($data->key1);
		//error_log("decode_key1 = ".$decoded_key1);
		$tx_ref = "T1".date('y').date('z').str_pad($data->referenceNumber, 7,'0',STR_PAD_LEFT);
		error_log("tx_ref = ".$tx_ref);
		if ( $error_path == "N") {
			// array for JSON response
			$response = array();
			$response["responseCode"] = "00";
			$response["signature"] = 100;
			$response["responseDescription"] = "Successful";
			$response["processingStartTime"] = "11/14/2019 5:19:58 PM";
			$response["result"] = "Success";
			$response["transactionRef"] = $tx_ref;
			$response["newAccountNumber"] = "2141234509";
			$response["loggerId"] = "123";
			$response["hasToken"] = "0";
			$response["additionalDataLoggerId"] = "";
			$response["additionalDataNameInquirySessionId"] = "";
			$response["additionalDataSessionId"] = "";
			$response["billingRechargeReference"] = "";
		}else {
			// array for JSON response
			$response = array();
			$response["transactionRef"] = $tx_ref;
			$response["responseCode"] = 100;
			$response["signature"] = 100;
			$response["responseDescription"] = "Invalid BVN";
			$response["dataResponseCode"] = "100";
			$response["dataResponseDescription"] = "Invalid BVN";
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