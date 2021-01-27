<?php
error_log("inside bpreceipt.php");
$error_path = "N";
sleep(5);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	error_log("inside post request method");
	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	
	if ( isset($data->countryId ) && !empty($data->countryId) && isset($data->stateId) && !empty($data->stateId) 
		&& isset($data->localGovtId) && !empty($data->localGovtId) && isset($data->sessionId) && !empty($data->sessionId) 
		//&& isset($data->partyCode) && !empty($data->partyCode) && isset($data->partyType) && !empty($data->partyType) 
		&& isset($data->key1) && !empty($data->key1) && isset($data->signature) && !empty($data->signature) 
		&& isset($data->userId) && !empty($data->userId) && isset($data->orderNo) && !empty($data->orderNo)
	) {
		
		$decoded_key1 = base64_decode($data->key1);
		if ( $error_path == "N") {
			
			// array for JSON response
			$response = array();
			$response["responseCode"] = 0;
			$response["signature"] = 100;
			$response["totalAmount"] = 800;
			$response["paymentFee"] = 16;
			$response["transactionId"] = "110008201130151436783305751768";
			$response["billingAccountNumber"] = "0011881015";
			$response["billingAccountName"] = "IKEJA ELECTRICITY DISTRIBUTION PLC";
			$response["billingInstitutionCode"] = "110008";
			$response["billerImage"] = "";
			//$response["billerImage"] = "data:image/jpg;base64,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";
			$response["paymentReference"] = "eBillsPay/1606745676878";
			$response["feeBearer"] = "1";
			$response["billerName"] = "Ikeja Electricity Distribution Company";
			$response["productName"] = "Pre-Paid";
			$response["notificationResponse"] = "empty";
			$response["paymentType"] = "1";
			$response["customerName"] = "lekan";
			$response["customerAccountNumber"] = "04271892301";
			$response["orderNo"] = $data->orderNo;
			$response["transactionDate"] = "2020-11-30T15:14:37+01:00";
			$param1 = new \stdClass();
			$param1->name = "Meter Number";
			$param1->value = "04271892301";
			$param2 = new \stdClass();
			$param2->name = "Enter Amount";
			$param2->value = "800.0";
			$param3 = new \stdClass();
			$param3->name = "STS1Token";
			$param3->value = "42876812836960560309";
			$param4 = new \stdClass();
			$param4->name = "PHONE NUMBER";
			$param4->value = "08032034286";
			$param5 = new \stdClass();
			$param5->name = "Product";
			$param5->value = "PRE-PAID";
			$param6 = new \stdClass();
			$param6->name = "utilityType";
			$param6->value = "Electricity";
			$param7 = new \stdClass();
			$param7->name = "Customer Address";
			$param7->value = "Cell:08033070901";
			$param8 = new \stdClass();
			$param8->name = "Amount To Pay";
			$param8->value = "800.0";
			$param9 = new \stdClass();
			$param9->name = "receiptNo";
			$param9->value = "20490569";
			$param10 = new \stdClass();
			$param10->name = "TariffIndex";
			$param10->value = "31";
			$param11 = new \stdClass();
			$param11->name = "IKEDC CUSTOMER EMAIL ADDRESS";
			$param11->value = "lekan.e@kadickintegrated.com";
			$param12 = new \stdClass();
			$param12->name = "Customer Name";
			$param12->value = "     MR ABIODUN MATHEW ADEOYE";
			$param13 = new \stdClass();
			$param13->name = "UnitsValue";
			$param13->value = "9.8";
			$param14 = new \stdClass();
			$param14->name = "Can Vend";
			$param14->value = "true";
			$param15 = new \stdClass();
			$param15->name = "Product Selected";
			$param15->value = "PRE-PAID";
			$param16 = new \stdClass();
			$param16->name = "TariffDesc";
			$param16->value = "R2 SINGLE PHASE RESIDENTIAL";
			$params = array($param1, $param2, $param3, $param4, $param5, $param6, $param8, $param7, $param8, $param9, $param10, $param11, $param12, $param13, $param14, $param15, $param16);
			$response["params"] = $params;
			$response["amount"] = 784;	
			$response["processingStartTime"] = "2020-11-23 15:32:34";	
			$response["responseDescription"] = "SUCCESS";
		}else {
			// array for JSON response
			$response = array();
			$response["responseCode"] = 100;
			$response["accountNo"] = $data->bpAccountNo;
			$response["signature"] = 100;
			$response["responseDescription"] = "Error";
		}
		error_log("before sending ==> ".json_encode($response));
	       	echo json_encode($response);
	}else {
		error_log("Invalid Data");
		$response = array();
		$response["responseCode"] = -100;
		$response["signature"] = 100;
		$response["responseDescription"] = "Invalid Data";
		error_log(json_encode($response));
		echo json_encode($response);
	}
}	else {
	error_log("Invalid Method");
	$response = array();
	$response["responseCode"] = -200;
	$response["signature"] = 100;
	$response["responseDescription"] = "Error: Invalid Method";
	error_log(json_encode($response));
	echo json_encode($response);
}
?>