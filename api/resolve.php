<?php
error_reporting(E_ERROR | E_PARSE);
error_log("inside resolve.php");
$error_path = "N";

$response = array();
$response["status"] = "true";
$response["message"] = "status";
$response["data"] = array();
$response["data"]['account_number'] = $_GET["account_number"];
$response["data"]['account_name'] = "Erinoso Olalekan";

error_log("before sending ==> ".json_encode($response));
echo json_encode($response);
?>