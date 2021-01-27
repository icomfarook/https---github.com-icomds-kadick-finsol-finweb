<?php
error_log("inside flexiRechargePost.php");
$error_path = "N";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	error_log("inside post request method");
	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	
	if ( isset($data->countryId ) && !empty($data->countryId) && isset($data->operatorId) && !empty($data->operatorId) && 
		isset($data->operatorCode) && !empty($data->operatorCode) && isset($data->operatorPlanId) && !empty($data->operatorPlanId) && 
		isset($data->mobileNumber) && !empty($data->mobileNumber) && isset($data->reMobileNumber) && !empty($data->reMobileNumber) && 
		isset($data->amount) && !empty($data->amount) && isset($data->total) && !empty($data->total) && 
		isset($data->key1) && !empty($data->key1) && isset($data->signature) && !empty($data->signature) && 
		isset($data->transLogId) && !empty($data->transLogId) ) {
	
		$decoded_key1 = base64_decode($data->key1);
		error_log("decode_key1 = ".$decoded_key1);
		if ( $error_path == "N") {
			// array for JSON response
			$response = array();
			$response["transLogId"] = $data->transLogId;
			$response["errorCode"] = 0;
			$response["signature"] = 100;
			$response["errorDescription"] = "Success";
			$response["flexiMessage"] = array();
			//$flexi_message = array();
			$response["flexiMessage"]['status'] = "S";
			$response["flexiMessage"]['refno'] = date('Ymdhis').$data->transLogId;
			$response["flexiMessage"]['mobileNumber'] = $data->mobileNumber;
			$response["flexiMessage"]['amount'] = $data->amount;
			$response["flexiMessage"]['statusLabel'] = "S - Success";
			$response["flexiMessage"]['seqNum'] = "123";
			$response["flexiMessage"]['message'] = "Your mobile $data->mobileNumber [$data->operatorCode] is recharged for NGN $data->amount, Ref: ".date('Ymdhis').$data->transLogId;
			$response["flexiMessage"]['showMessage'] = "false";
			$response["flexiMessage"]['operatorCode'] = $data->operatorCode;
			$response["flexiMessage"]['oprPlan'] = $data->operatorPlanId;
			//array_push($response["flexiMessage"], $flexi_message);
		}else {
			// array for JSON response
			$response = array();
			$response["transLogId"] = $data->transLogId;
			$response["errorCode"] = -100;
			$response["signature"] = 100;
			$response["errorDescription"] = "Error";
			$response["flexiMessage"] = array();
			//$flexi_message = array();
			$response["flexiMessage"]['status'] = "E";
			$response["flexiMessage"]['refno'] = "";
			$response["flexiMessage"]['mobileNumber'] = $data->mobileNumber;
			$response["flexiMessage"]['amount'] = $data->amount;
			$response["flexiMessage"]['statusLabel'] = "E - Error";
			$response["flexiMessage"]['seqNum'] = "123";
			$response["flexiMessage"]['message'] = "Your mobile $data->mobileNumber [$data->operatorCode] is NOT recharged for NGN $data->amount";
			$response["flexiMessage"]['showMessage'] = "false";
			$response["flexiMessage"]['operatorCode'] = $data->operatorCode;
			$response["flexiMessage"]['oprPlan'] = $data->operatorPlanId;
			//array_push($response["flexiMessage"], $flexi_message);
		}
		error_log("before sending ==> ".json_encode($response));
	       	echo json_encode($response);
	}else {
		error_log("Invalid Data");
		$response = array();
		$response["errorCode"] = -400;
		$response["signature"] = 100;
		$response["errorDescription"] = "Error: Invalid Data";
		$response["flexiMessage"] = array();
		//$flexi_message = array();
		$response["flexiMessage"]['message'] = "Not able to process request";
		//array_push($response["flexiMessage"], $flexi_message);
		error_log(json_encode($response));
		echo json_encode($response);
	}
}else {
	error_log("Invalid Method");
	$response = array();
	$response["errorCode"] = -500;
	$response["signature"] = 100;
	$response["errorDescription"] = "Error: Invalid Method";
	$response["flexiMessage"] = array();
	$response["flexiMessage"]['message'] = "Invalid Method";
	//$flexi_message = array();
	//array_push($response["flexiMessage"], $flexi_message);
	error_log(json_encode($response));
	echo json_encode($response);
}
?>