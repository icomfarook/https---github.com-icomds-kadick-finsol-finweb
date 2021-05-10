<?php
	sleep(3);
	$error_path = "N";
	
	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	$response = array();
	
	if ( $error_path == "N") {
		$response["responseCode"] = 0;
		$response["responseDescription"] = "Success";
		
		
		$response["status"] = "success";
		$response["message"] = "Successful";
		$response["pinCode"] = "604647211562";
		$response["pinSerialNumber"] = "WRN192623899";
		$response["serviceId"] = "3";
		$response["amount"] = "1850.0";
		$response["reference"] = "829120150";
		$response["id"] = "609291fc73c64e1c0ae32fec";
		
		$response["responseDataPinCode"] = "604647211562";
		$response["responseDataPinSerialNumber"] = "WRN192623899";
		$response["responseDataVendResponseResponseMessage"] = "SUCCESS";
		$response["responseDataVendResponseResponseCode"] = "0";
		$response["responseDataVendResponseProductType"] = "80";
		$response["responseDataVendResponseAmount"] = "1800";
		$response["responseDataVendResponseAccount"] = "";
		$response["responseDataVendResponseRef"] = "";
		$response["responseDataVendResponseAvref"] = "9024968";
		$response["responseDataVendDataStatusCode"] = "0";
		$response["responseDataVendDataStatus"] = "ACCEPTED";
		$response["responseDataVendDataStatusMessage"] = "Successful PIN purchase.";
		$response["responseDataVendDataExchangeReference"] = "107484866";
		$response["requestPayloadAmount"] = "1850";
		$response["requestPayloadPins"] = "1";
		$response["serviceCategoryId"] = "5";
		$response["success"] = "true";
		$response["createdAt"] = "2021-05-05T12:39:24.793Z";
		
		$response["signature"] = 20471;
		$response["processingStartTime"] = "2020-03-20 20:30:56";
	}else {
		$response["responseCode"] = 25;
		$response["responseDescription"] = "Error";
		
		$response["status"] = "failure";
		$response["message"] = "Invalid Account";
		
		$response["signature"] = 20471;
		$response["processingStartTime"] = "2020-03-20 20:30:56";
	}
	error_log(json_encode($response));
	echo json_encode($response);

?>