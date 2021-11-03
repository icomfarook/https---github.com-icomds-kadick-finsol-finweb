<?php
error_reporting(E_ERROR | E_PARSE);
error_log("inside resolve_bvn.php");
$error_path = "N";

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathComponents = explode("/", trim($path, "/")); 

$response = array();
$response["status"] = "true";
$response["message"] = "status";
$response["data"] = array();
$response["data"]['firstName'] = "Erinoso";
$response["data"]['lastName'] = "Olalekan";
$response["data"]['dob'] = "19900101";
$response["data"]['formattedDob'] = "1999-01-01";
$response["data"]['mobile'] = "8012345678";
$response["data"]['bvn'] = $pathComponents[2];

error_log("before sending ==> ".json_encode($response));
echo json_encode($response);
?>