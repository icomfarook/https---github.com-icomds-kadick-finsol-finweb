<?php
	sleep(3);
	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	$response = array();
	$response["partnerId"] = 2;
	//$response["responseCode"] = 25;
	$response["responseCode"] = 0;
	$response["accountName"] = 'DICKSON UMUSU';
	$response["accountNumber"] = '0006492419';
	$response["sessionId"] = '110008202003201953193798653928';
	//$response["responseDescription"] = "Bank code missing";
	$response["responseDescription"] = "Success";
	$response["totalAmount"] = 400.00;
	$response["paymentReference"] = 'ITFR20201584730440046';
	$response["signature"] = 498;
	$response["processingStartTime"] = "2020-03-20 20:30:56";
	error_log(json_encode($response));
	echo json_encode($response);

?>