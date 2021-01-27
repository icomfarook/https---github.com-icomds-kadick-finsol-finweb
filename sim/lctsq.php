<?php

	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	$response = array();
	$response["responseCode"] = 0;
	//$response["responseCode"] = 404;
	$response["responseDescription"] = "Success";
	//$response["responseDescription"] = "Operation not found";
	$response["signature"] = 20645;
	$response["processingStartTime"] = date('y-m-d h:i:s');
	$response["operationId"] = 'AGT001_1550597732';
	$response["result"] = 'id=AGT001_1550597732,clientId=SmooWKZc,amount=100.0,customerMsisdn=+2348073905728,customerEmail=null,merchantCode=00001541,type=MerchantPayment,status=failure,cause=no_accounts,created=2020-04-07T08:28:58.352Z,updated=2020-04-07T08:28:58.681Z,chargeRequestId=null,chargeResponseId=null,delegatedTo=null';
	//$response["result"] = 'null';
	$response["partnerId"] = 3;
	$response["success"] = "true";	
	//$response["success"] = "failure";	

	error_log(json_encode($response));
	echo json_encode($response);
?>