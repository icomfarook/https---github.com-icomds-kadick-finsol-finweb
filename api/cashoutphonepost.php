<?php
	sleep(3);
	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	$response = array();
	$response["orderId"] = 1234;
	//$response["responseCode"] = 25;
	$response["responseCode"] = 0;
	//$response["responseDescription"] = "Bank code missing";
	$response["responseDescription"] = "Success";
	
	$response["amount"] = $data->totalAmount;
	$response["description"] = $data->description;
	$response["currency"] = "566";
	$response["status"] = "APPROVED";
	$response["pan"] = $data->mobile;
	$response["transactionTime"] = "3/18/2021 4:31:28 PM";
	$response["statusDescription"] = "APPROVED";
	$response["signature"] = 20471;
	$response["processingStartTime"] = "2020-03-20 20:30:56";
	error_log(json_encode($response));
	echo json_encode($response);

?>