<?php
error_log("inside fixedrecharge.php");
$error_path = "N";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	error_log("inside post request method");
	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	
	if ( isset($data->countryId ) && !empty($data->countryId) && isset($data->operatorId) && !empty($data->operatorId) 
		&& isset($data->operatorCode) && !empty($data->operatorCode) && isset($data->mobile) && !empty($data->mobile) 
		&& isset($data->amount) && !empty($data->amount) && isset($data->total) && !empty($data->total) 
		&& isset($data->key1) && !empty($data->key1) && isset($data->signature) && !empty($data->signature) 
		&& isset($data->agentCode) && !empty($data->agentCode) ) {
	
		$decoded_key1 = base64_decode($data->key1);
		error_log("decode_key1 = ".$decoded_key1);
		if ( $error_path == "N") {
			// array for JSON response
			$response = array();
			$response["errorCode"] = 0;
			$response["signature"] = 100;
			$response["errorDescription"] = "Success";
			$response["voucher"] = array();
			$response["voucher"]['status'] = "S";
			$response["voucher"]['serialNo'] = "622110000000001";
			$response["voucher"]['pin'] = "345665627155166";
			$response["voucher"]['operatorId'] = "81";
			$response["voucher"]['dateString'] = date('Y-m-d h:i:s');
			$response["voucher"]['mobileNumber'] = $data->mobile;
			$response["voucher"]['voucherValue'] = $data->amount;
			$response["voucher"]['total'] = $data->amount;
			$response["voucher"]['dialString'] = "Dial *1213#PIN";
			$response["voucher"]['contactInfo'] = "Contact @ 08000KADICK (08000523425)";
			$response["voucher"]['operatorCode'] = $data->operatorCode;
		}else {
			// array for JSON response
			$response = array();
			$response["errorCode"] = -100;
			$response["signature"] = 100;
			$response["errorDescription"] = "Error";
			$response["voucher"] = array();
			$response["voucher"]['status'] = "E";
			$response["voucher"]['serialNo'] = "";
			$response["voucher"]['pin'] = "";
			$response["voucher"]['operatorId'] = "81";
			$response["voucher"]['dateString'] = date('Ymdhis');
			$response["voucher"]['mobileNumber'] = $data->mobileNumber;
			$response["voucher"]['voucherValue'] = $data->amount;
			$response["voucher"]['total'] = $data->amount;
			$response["voucher"]['dialString'] = "";
			$response["voucher"]['contactInfo'] = "";
			$response["voucher"]['operatorCode'] = $data->operatorCode;
			
		}
		error_log("before sending ==> ".json_encode($response));
	       	echo json_encode($response);
	}else {
		error_log("Invalid Data");
		$response = array();
		$response["errorCode"] = -400;
		$response["signature"] = 100;
		$response["errorDescription"] = "Error: Invalid Data";
		$response["voucher"] = array();
		error_log(json_encode($response));
		echo json_encode($response);
	}
}else {
	error_log("Invalid Method");
	$response = array();
	$response["errorCode"] = -500;
	$response["signature"] = 100;
	$response["errorDescription"] = "Error: Invalid Method";
	$response["voucher"] = array();
	error_log(json_encode($response));
	echo json_encode($response);
}
?>