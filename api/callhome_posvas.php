<?php
error_reporting(E_ERROR | E_PARSE);
error_log("inside callhome_posvas.php");
$error_path = "N";

$response = array();
$response["response"] = "00";
$response["description"] = "Callhome OK";

error_log("before sending ==> ".json_encode($response));
echo json_encode($response);
?>