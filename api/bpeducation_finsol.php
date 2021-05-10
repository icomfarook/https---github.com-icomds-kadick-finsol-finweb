<?php
error_log("inside bpeducation_finsol.php");
$error_path = "N";
$second_validation = "N";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	error_log("inside post request method");
	$data = json_decode(file_get_contents("php://input"));
	error_log(json_encode($data));
	
	echo "{\"status\": \"success\",  \"message\": \"Successful\", \"transaction\": { \"pin\": { \"pinCode\": \"604647211562\", \"serialNumber\": \"WRN192623899\" }, \"response_payload\": { \"data\": {  \"raw\": { \"VendResponse\": { \"vendData\": { \"statusCode\": \"0\", \"status\": \"ACCEPTED\", \"statusMessage\": \"Successful PIN purchase.\", \"pins\": { \"pin\": { \"pinCode\": \"604647211562\", \"serialNumber\": \"WRN192623899\" } }, \"exchangeReference\": \"107484866\" }, \"ResponseMessage\": \"SUCCESS\", \"ResponseCode\": \"0\", \"ProductType\": \"80\", \"Amount\": \"1800\", \"Account\": \"\", \"Ref\": \"\", \"AVRef\": \"9024968\" } }, \"pin\": { \"pinCode\": \"604647211562\", \"serialNumber\": \"WRN192623899\" } }, \"message\": \"Successful\", \"status\": \"success\" }, \"__v\": 0, \"status\": \"completed\", \"is_complete\": true, \"request_payload\": { \"amount\": \"1850\", \"pins\": \"1\" }, \"_service_category\": 5, \"_service\": 3, \"amount\": 1850, \"_user\": \"6034c0c473c64e1c0ac6a646\", \"reference\": \"829120150\", \"_id\": \"609291fc73c64e1c0ae32fec\", \"deleted_at\": null, \"updated_at\": null, \"created_at\": \"2021-05-05T12:39:24.793Z\", \"is_ended\": true } }";
}	
else {
	error_log("Invalid Method");
	$response = array();
	$response["responseCode"] = -200;
	$response["signature"] = 100;
	$response["responseDescription"] = "Error: Invalid Method";
	error_log(json_encode($response));
	echo json_encode($response);
}
?>