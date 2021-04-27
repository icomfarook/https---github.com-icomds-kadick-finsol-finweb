<?php
	sleep(3);
	$error_path = "N";
	
	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	$response = array();
	
	if ( $error_path == "N") {
		$response["responseCode"] = 0;
		$response["responseDescription"] = "Success";
		$response["name"] = "MR ABIODUN MATHEW ADEOYE";
		$response["status"] = "success";
		$response["amount"] = $data->amount;
		$response["totalAmount"] = $data->amount;
		$response["paymentFee"] = 0;
		$response["message"] = "Verification successful";
		$response["data"] = "Name:      MR ABIODUN MATHEW ADEOYE, Address: Cell:08033070901, Vend Type: PREPAID, Meter No: 04271892301, Free Units: false, Tariff: , Days Last Vend: , Min. Payable Amount: 0, Max. Payable Amount: 10000000";
		$response["minPayment"] = "500";
		$response["maxPayment"] = "1000000";
		$response["signature"] = 20471;
		$response["processingStartTime"] = "2020-03-20 20:30:56";
	}else {
		$response["responseCode"] = 25;
		$response["responseDescription"] = "Error";
		$response["status"] = "failure";
		$response["message"] = "Invalid Account";
		$response["amount"] = $data->amount;
		$response["totalAmount"] = $data->amount;
		$response["paymentFee"] = 0;
		$response["data"] = "";
		$response["signature"] = 20471;
		$response["processingStartTime"] = "2020-03-20 20:30:56";
	}
	error_log(json_encode($response));
	echo json_encode($response);

?>