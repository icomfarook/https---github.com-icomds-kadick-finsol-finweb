<?php
error_log("inside bpvalidate.php");
$error_path = "N";
$second_validation = "N";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	error_log("inside post request method");
	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	
	if ( isset($data->countryId ) && !empty($data->countryId) && isset($data->stateId) && !empty($data->stateId) 
		&& isset($data->localGovtId) && !empty($data->localGovtId) && isset($data->amount) && !empty($data->amount) 
		&& isset($data->accountNo) && !empty($data->accountNo) && isset($data->mobile) && !empty($data->mobile)
		&& isset($data->email) && !empty($data->email) && isset($data->name) && !empty($data->name)
		&& isset($data->key1) && !empty($data->key1) && isset($data->signature) && !empty($data->signature) 
		&& isset($data->billerId) && !empty($data->billerId) //&& isset($data->billerName) && !empty($data->billerName) 
		&& isset($data->productId) && !empty($data->productId) //&& isset($data->productName) && !empty($data->productName) 
	) {
		
		$decoded_key1 = base64_decode($data->key1);
		if ( $error_path == "N") {
			
			// array for JSON response
			$response = array();
			$response["responseCode"] = 0;
			$response["signature"] = 100;
			$response["amount"] = $data->amount;
			$response["totalAmount"] = $data->amount;
			$response["transactionId"] = "110008201130151436783305751768";
			$response["bpAccountNo"] = "0043558587";
			$response["bpAccountName"] = "Oge Pse";
			$response["bpBankCode"] = "999000";		
			$response["paymentFee"] = 25;
			$response["productFormDetail"] = "1. PHONE NUMBER: 9790865619\n2. IKEDC CUSTOMER EMAIL ADDRESS: ansari@icomdatasystems.com\n3. Amount To Pay: 1000.0\n4. Product: PRE-PAID\n5. Customer Name: MR ABIODUN MATHEW DEOYE\n6. Customer Address: Cell:08033070901";
			$response["productFormTitle"] = "2: Confirm Transaction Details";
			$response["previousRecordDetail"] = "Step: 1\nCustomer ID: 1023320097-01\nPhone Number: 09790865619\nEmail: ansari@icomdatasystems.com\nAmount: 1000.0\nMin Amount: 100\nLASTNAME: ERIN OSO\nFIRSTNAME: MR\nProduct: Energy Bills\nDISTRICTCODE: IBEJU\nADDRESS: OBA OYE KAN CL  (IBEJU)LAGOS\nMeter Number/Account Number: 1023320097-01\nPhone Number: 09790865619\nEmail:ansari@icomdatasystems.com\nAmount: 1000.0";
			$response["processingStartTime"] = "2020-11-22 15:32:34";	
			$response["responseDescription"] = "Successful";
		}else {
			// array for JSON response
			$response = array();
			$response["responseCode"] = 100;
			$response["accountNo"] = $data->accountNo;
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