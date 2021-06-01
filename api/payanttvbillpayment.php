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
		
		$response["verifyBasketId"] = "193391791";
		$response["verifyName"] = "ERIMOSO IBRAHIM";
		$response["verifyAccountNumber"] = "34098355";
		$response["verifyCustomerNumber"] = "277504454";
		$response["verifyBoxOffice"] = "false";
		$response["verifyInvoicePeriod"] = "1";
		$response["verifyTotalAmount"] = "2512.00";
		$response["verifyDueDate"] = "2021-06-25T00:00:00";
		$response["verifyBalanceDue"] = "-53.00";
		$response["verifyStatus"] = "Open";
		
		$response["responseRawData"] = "{\"PayUVasResponse\":{\"MerchantReference\":\"60b3c255956e391f678e8054\",\"PayUVasReference\":\"\",\"ResultCode\":\"00\",\"ResultMessage\":\"Success\",\"VasProvider\":\"MCA_ACCOUNT_SQ_NG\",\"VasProviderReference\":\"\",\"CustomFields\":{\"Customfield\":[{\"$\":{\"Key\":\"Message\",\"Value\":\"Transaction in process, client will be notified\"}},{\"$\":{\"Key\":\"ReceiptNumber\",\"Value\":\"\"}}]}}}";
		$response["responsePayloadMessage"] = "Successful";
		$response["responsePayloadStatus"] = "success";
		
		$response["serviceCategoryId"] = 14;
		$response["serviceId"] = 1;
		$response["amount"] = $data->amount;
		$response["reference"] = password_generate_num(8);
		$response["id"] = password_generate(24);
		$response["createdAt"] = "2021-04-21T19:10:13.714Z";
		
		$response["requestAmount"] = $data->amount;
		$response["requestSmartcard"] = "$data->smartCard";
		$response["requestInvoicePeriod"] = "$data->invoicePeriod";
		$response["requestBundleCode"] = "$data->bundleCode";
		$response["requestNumber"] = "$data->phone";
		$response["requestName"] = "$data->name";
			
		$response["signature"] = 20471;
		$response["processingStartTime"] = date('Y-m-d h:i:s');
	}else {
		$response["responseCode"] = 25;
		$response["responseDescription"] = "Error";
		
		$response["status"] = "failure";
		$response["message"] = "Invalid Account";
		
		$response["signature"] = 20471;
		$response["processingStartTime"] = date('Y-m-d h:i:s');
	}
	error_log(json_encode($response));
	echo json_encode($response);


function password_generate($chars) 
{
  $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
  return substr(str_shuffle($data), 0, $chars);
}

function password_generate_num($chars) 
{
  $data = '1234567890';
  return substr(str_shuffle($data), 0, $chars);
}

?>