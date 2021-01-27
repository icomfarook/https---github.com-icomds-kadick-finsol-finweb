<?php
	error_log("inside sanef_acc_open.php");
	$error_path = "N";
	ini_set('max_execution_time', 200);
	set_time_limit(200);
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		error_log("inside post request method");
		$data = json_decode(file_get_contents("php://input"));
		error_log(json_encode($data));
	
		if ( isset($data->countryId ) && !empty($data->countryId) && isset($data->localGovtId) && !empty($data->localGovtId) 
			//&& isset($data->superAgentCode) && !empty($data->superAgentCode) && isset($data->stateId) && !empty($data->stateId)
			&& isset($data->agentCode) && !empty($data->agentCode) && isset($data->bankCode) && !empty($data->bankCode)
			&& isset($data->requestId) && !empty($data->requestId) && isset($data->bankVerificationNumber) && !empty($data->bankVerificationNumber)
			&& isset($data->lastName) && !empty($data->lastName) && isset($data->middleName) && !empty($data->middleName) 
			&& isset($data->firstName) && !empty($data->firstName) && isset($data->gender) && !empty($data->gender) 
			&& isset($data->dateOfBirth) && !empty($data->dateOfBirth) && isset($data->houseNumber) && !empty($data->houseNumber) 
			&& isset($data->streetName) && !empty($data->streetName) && isset($data->city) && !empty($data->city) 
			&& isset($data->lgaCode) && !empty($data->lgaCode) && isset($data->emailAddress) && !empty($data->emailAddress) 
			&& isset($data->phoneNumber) && !empty($data->phoneNumber) 
			//&& isset($data->customerImage) && !empty($data->customerImage) && isset($data->customerSignature) && !empty($data->customerSignature) 
			//&& isset($data->accountOpeningBalance) && !empty($data->accountOpeningBalance)
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
				$response["responseCode"] = 28;
				$response["signature"] = 100;
				$response["responseDescription"] = "Error in communication protocol";
				$response["success"] = "false";
				$response["processingStartTime"] = "2020-12-10 17:54:22";
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