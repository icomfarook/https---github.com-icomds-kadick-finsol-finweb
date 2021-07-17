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
		
		$response["orderNo"] = password_generate_num(20);
		$response["reference"] = $data->reference;
		$response["status"] = "SUCCESS";
		$response["errorMsg"] = "";
		
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