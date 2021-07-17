<?php
	sleep(3);
	$error_path = "N";
	
	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	$response = array();
	
	if ( $error_path == "N") {
		$response["responseCode"] = 0;
		$response["responseDescription"] = "Success";
		$response["provider"] = $data->provider;
		$response["customerId"] = $data->customerId;
		$response["firstName"] = "Test First Name";
		$response["lastName"] = "Text Last Name";
		$response["userName"] = "testusername";
		$response["signature"] = 20471;
		$response["processingStartTime"] = date('Y-m-d h:i:s');
	}else {
		$response["responseCode"] = 25;
		$response["responseDescription"] = "Error";
		$response["status"] = "failure";
		$response["message"] = "Invalid Account";
		$response["signature"] = 20471;
		$response["processingStartTime"] = date('Y-m-d h:i:s');
	}
	error_log(json_encode($response));
	echo json_encode($response);

?>