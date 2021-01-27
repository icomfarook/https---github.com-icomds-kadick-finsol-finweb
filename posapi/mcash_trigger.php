<?php
	sleep(3);
	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	$response = array();
	$response["responseCode"] = 0;
	//$response["responseCode"] = 404;
	$response["responseDescription"] = "Success";
	//$response["responseDescription"] = "Error in accepting cashout";
	$response["signature"] = 20645;
	$response["processingStartTime"] = date('y-m-d h:i:s');
		
	$response["operationId"] = 'AGT001_1550597776';
	$response["recoveryShortcode"] = '*402*1234#';
	$response["partnerId"] = 3;
	$response["success"] = 'true';	
	//$response["success"] = 'false';	
	
	error_log(json_encode($response));
	echo json_encode($response);
									
?>
