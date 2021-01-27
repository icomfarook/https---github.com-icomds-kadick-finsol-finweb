<?php
error_log("inside t1accstatuspost.php");
$error_path = "N";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	error_log("inside post request method");
	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	
	if ( isset($data->countryId ) && !empty($data->countryId) && isset($data->stateId) && !empty($data->stateId) 
		&& isset($data->localGovtId) && !empty($data->localGovtId) && isset($data->partnerId) && !empty($data->partnerId)
		&& isset($data->mobileNumber) && !empty($data->mobileNumber) 
		&& isset($data->key1) && !empty($data->key1) && isset($data->signature) && !empty($data->signature) 
	) {
		//sleep(4);
		//$decoded_key1 = base64_decode($data->key1);
		//error_log("decode_key1 = ".$decoded_key1);
		$transactionId = 123;
		$tx_ref = "T1".date('y').date('z').str_pad($transactionId, 7,'0',STR_PAD_LEFT);
		error_log("tx_ref = ".$tx_ref);
		if ( $error_path == "N") {
			// array for JSON response
			$response = array();
			$response["responseCode"] = "00";
			$response["signature"] = 100;
			$response["responseDescription"] = "Successful";
			$response["processingStartTime"] = "11/14/2019 5:19:58 PM";
			$mobileAccountData = array();
			$data1 = array();
			$data1["name"] = "Kenneth Emeka";
			$data1["reference"] = $tx_ref;
			$data1["accountNumber"] = "2142340812";
			$mobileAccountData[] = $data1;
			$data2 = array();
			$data2["name"] = "Erinoso Lekan";
			$data2["reference"] = $tx_ref;
			$data2["accountNumber"] = "2142340813";
			$mobileAccountData[] = $data2;
			$response["mobileAccountData"] = $mobileAccountData;
			$response["description"] = "Multiple Accounts";
			$response["partnerId"] = $data->partnerId;
			
		}else {
			// array for JSON response
			$response = array();
			$response["responseCode"] = 100;
			$response["signature"] = 100;
			$response["responseDescription"] = "Invalid Mobile";
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