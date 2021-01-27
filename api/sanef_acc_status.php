<?php
	error_log("inside sanef_acc_status.php");
	$error_path = "Y";

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		error_log("inside post request method");
		$data = json_decode(file_get_contents("php://input"));
		error_log(json_encode($data));
	
		if ( isset($data->countryId ) && !empty($data->countryId) && isset($data->localGovtId) && !empty($data->localGovtId) 
			&& isset($data->bankCode) && !empty($data->bankCode) && isset($data->requestId) && !empty($data->requestId) 
		) {
			$decoded_key1 = base64_decode($data->key1);
			if ( $error_path == "N") {
				// array for JSON response
				$response = array();
				$response["responseCode"] = 0;
				$response["signature"] = 100;
				$response["responseDescription"] = "This transaction is successful";
				$response["processingStartTime"] = "2020-11-23 17:02:36.797";
				$response["accountNumber"] = "2379823950";
				$response["success"] = "true";
			}else {
				// array for JSON response
				$response = array();
				$response["responseCode"] = 100;
				$response["signature"] = 100;
				$response["responseDescription"] = "Request not found";
				$response["success"] = "false";
			}
			error_log("before sending ==> ".json_encode($response));
	       		echo json_encode($response);
		}else {
			error_log("Invalid Data");
			$response = array();
			$response["responseCode"] = 200;
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