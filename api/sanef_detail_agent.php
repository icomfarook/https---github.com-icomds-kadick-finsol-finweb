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
			$response["agentType"] = "1";
			$response["lastName"] = "AM";
			$response["firstName"] = "Ansari";
			$response["middleName"] = "-";
			$response["businessName"] = "KadickTest";
			$response["gender"] = "gender";
			$response["phoneNumber2"] = null;
			$response["agentAddress"] = "Lagos";
			$response["phoneNumber1"] = "08032034286";
			$response["closestLandMark"] = null;
			$response["emailAddress"] = null;
			$response["bankVerififcationNumber"] = "22257512839";
			$response["taxIdentififcationNumber"] = null;
			$response["agentBusiness"] = "5";
			$response["dateOfBirth"] = "1994-01-01T00:00:00";
			$response["localGovermentCode"] = "513";
			$response["userName"] = "username800001";
			$response["success"] = true;
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