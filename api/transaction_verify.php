<?php
error_reporting(E_ERROR | E_PARSE);
error_log("inside transaction_verify.php");
$error_path = "N";

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathComponents = explode("/", trim($path, "/")); 

$response = array();
$response["status"] = "true";
$response["message"] = "status";
$response["data"] = array();
$response["data"]['amount'] = 5000.00;
$response["data"]['currency'] = "NGN";
$response["data"]['transaction_date'] = "19900101";
$response["data"]['status'] = "success";
$response["data"]['reference'] = $pathComponents[2];
$response["data"]['domain'] = "paystack";
$response["data"]['gateway_response'] = "success";
$response["data"]['message'] = "success payment";
$response["data"]['channel'] = "bank";
$response["data"]['ip_address'] = "123.456.789.10";
$response["data"]['fees'] = "0";
$response["data"]['plan'] = "special_plan";
$response["data"]['paid_at'] = "Lagos";

error_log("before sending ==> ".json_encode($response));
echo json_encode($response);
?>