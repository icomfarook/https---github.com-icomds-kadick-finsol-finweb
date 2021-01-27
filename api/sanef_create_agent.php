<?php
	error_log("inside sanef_create_agent.php");
	$error_path = "N";

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		error_log("inside post request method");
		$data = json_decode(file_get_contents("php://input"));
		error_log("sanef_create_agent <== ".json_encode($data));
		if ( $error_path == "N") {
			// array for JSON response
			$response = array();
			$response["responseCode"] = 0;
			$response["signature"] = 100;
			$response["agentCode"] = "02990800001";
			$response["responseDescription"] = "successful";
			$response["processingStartTime"] = "2020-11-23 17:02:36.797";
		}else {
			// array for JSON response
			$response = array();
			$response["responseCode"] = 100;
			$response["signature"] = 100;
			$response["responseDescription"] = "Error";
			$response["success"] = "false";
		}
		
	}else {
		error_log("Invalid Data");
		$response = array();
		$response["responseCode"] = -100;
		$response["signature"] = 100;
		$response["responseDescription"] = "Invalid Data";
		error_log(json_encode($response));
	}
	error_log("sanef_create_agent ==> ".json_encode($response));
       	echo json_encode($response);

?>