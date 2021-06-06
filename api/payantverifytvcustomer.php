<?php
	sleep(2);
	$error_path = "N";
	
	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	$response = array();
	
	if ( $error_path == "N") {
		$response["responseCode"] = 0;
		$response["responseDescription"] = "Success";
		
		$response["basketId"] = "193189538";
		$response["name"] = "ERIMOSO IBRAHIM";
		$response["accountNumber"] = $data->account;
		$response["customerNumber"] = "277504454";
		$response["verifyStatus"] = "Open";
		$response["message"] = "Verification successful";
		$response["boxOffice"] = "false";
		$response["invoicePeriod"] = "1";
		$response["totalAmount"] = 2512.00;
		$response["dueDate"] = "2021-06-25T00:00:00";
		$response["balanceDue"] = -53.00;
		
		$response["signature"] = 20471;
		$response["processingStartTime"] = date('Y-m-d h:i:s');
	}else {
		$response["responseCode"] = 25;
		$response["responseDescription"] = "Error";
		$response["status"] = "failure";
		$response["message"] = "Invalid Account";
		$response["totalAmount"] = $data->amount;
		$response["signature"] = 20471;
		$response["processingStartTime"] = date('Y-m-d h:i:s');
	}
	error_log(json_encode($response));
	echo json_encode($response);

?>