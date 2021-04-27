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
		$response["pinUnits"] = "2.5Kwh";
		$response["pinCode"] = "1854-8374-9528-5083-0023";
		$response["pinSerialNumber"] = "28001096";
		$response["serviceId"] = "2";
		$response["amount"] = "107.0";
		$response["reference"] = "705780612";
		$response["id"] = "606b6b0e73c64e1c0acb83a6";
		$response["dataResponseMessage"] = "ACCEPTED";
		$response["dataResponseCode"] = "100";
		$response["dataVendRef"] = "28001096";
		$response["dataVendAmount"] = "0";
		$response["dataUnits"] = "2.5";
		$response["dataTotalAmountPaid"] = "107";
		$response["dataToken"] = "1854-8374-9528-5083-0023";
		$response["dataVendTime"] = "2021-04-05 20:54:58";
		$response["dataTax"] = "0";
		$response["dataReceiptNo"] = "102989817";
		$response["dataOrderId"] = "606b6b0e73c64e1c0acb83a6";
		$response["dataFreeUnits"] = "0";
		$response["dataDisco"] = "IKEJA";
		$response["dataDebtRemaining"] = "0";
		$response["dataDebtAmount"] = "0";
		$response["dataTariff"] = "null";
		$response["dataAmountGenerated"] = "99.53";
		$response["dataId"] = "14576521";
		$response["verifyMaxPayableAmount"] = "10000000";
		$response["verifyMinPayableAmount"] = "0";
		$response["verifyDaysLastVend"] = "";
		$response["verifyTariff"] = "";
		$response["verifyFreeUnits"] = "false";
		$response["verifyMeterNo"] = "04271892301";
		$response["verifyVendType"] = "PREPAID";
		$response["verifyAddress"] = "Cell:08033070901";
		$response["verifyName"] = "MR ABIODUN MATHEW ADEOYE";
		$response["serviceCategoryId"] = "1";
		$response["createdAt"] = "2021-04-21T19:10:13.714Z";
		
		/*
		$response["status"] = "success";
		$response["message"] = "Successful";
		$response["pinUnits"] = "62.00Kwh";
		$response["pinCode"] = "5411-0770-6139-5564-3761";
		$response["pinSerialNumber"] = "9501d592-c47c-4e22-afa8-0dd9fdb1d538";
		$response["serviceId"] = "2";
		$response["amount"] = "107.0";
		//$response["reference"] = "829515572";
		$response["reference"] = "829515572122222233dddcDFDDAFDDSSD";
		$response["id"] = "606b6b0e73c64e1c0acb83a6";
		$response["dataResponseMessage"] = "ACCEPTED";
		$response["dataResponseCode"] = "100";
		$response["dataVendRef"] = "9501d592-c47c-4e22-afa8-0dd9fdb1d538";
		$response["dataVendAmount"] = "0";
		$response["dataUnits"] = "2.5";
		$response["dataTotalAmountPaid"] = "2000";
		$response["dataToken"] = "1854-8374-9528-5083-0023";
		$response["dataVendTime"] = "";
		$response["dataTax"] = "0";
		$response["dataReceiptNo"] = "";
		$response["dataOrderId"] = "606b6b0e73c64e1c0acb83a6";
		$response["dataFreeUnits"] = "0";
		$response["dataDisco"] = "KADUNA";
		$response["dataDebtRemaining"] = "0";
		$response["dataDebtAmount"] = "0";
		$response["dataTariff"] = "null";
		$response["dataAmountGenerated"] = "99.53";
		$response["dataId"] = "14942401";
		$response["verifyMaxPayableAmount"] = "10000000";
		$response["verifyMinPayableAmount"] = "0";
		$response["verifyDaysLastVend"] = "";
		$response["verifyTariff"] = "";
		$response["verifyFreeUnits"] = "false";
		$response["verifyMeterNo"] = "30630079231";
		$response["verifyVendType"] = "PREPAID";
		$response["verifyAddress"] = "ALU QUARTERS AREA   ";
		$response["verifyName"] = "DAN BELLO  SHEHU YABO";
		$response["serviceCategoryId"] = "1";
		$response["createdAt"] = "2021-04-21T19:10:13.714Z";
		*/
				
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