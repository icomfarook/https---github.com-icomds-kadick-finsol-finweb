<?php
	include('../common/admin/configmysql.php');
	include ("get_prime.php");
	require_once ("AesCipher.php");
	
	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;
	
	
	function finsol_name_enquiry($partnerId, $accountNumber, $bankCode, $userId) {
	
		date_default_timezone_set('Africa/Lagos');
		$nday = date('z')+1;
		$nyear = date('Y');
		$nth_day_prime = get_prime($nday);
		$nth_year_day_prime = get_prime($nday+$nyear);
		$local_signature = $nday + $nth_day_prime;
		error_log("local_signature = ".$local_signature);
		$server_signature = $nth_year_day_prime + $nday + $nyear;
		error_log("server_signature = ".$server_signature);
	
		$data = array();
		$data['partnerId'] = $partnerId;
		$data['accountNumber'] = $accountNumber;
		$data['bankCode'] = $bankCode;
		$data['userId'] = $userId;
		$data['countryId'] = ADMIN_COUNTRY_ID;
		$data['stateId'] = ADMIN_STATE_ID;
		$data['localGovtId'] = ADMIN_LOCAL_GOVT_ID;
		
		$url = FINAPI_SERVER_NAME_ENQUIRY_URL;
		$tsec = time();
		$raw_data1 = FINAPI_SERVER_APP_PASSWORD.FINWEB_SERVER_SHORT_NAME."|".FINAPI_SERVER_APP_USERNAME.FINWEB_SERVER_SHORT_NAME."|".$tsec;
		error_log("raw_data1 = ".$raw_data1);
		$key1 = base64_encode($raw_data1);
		error_log("key1 = ".$key1);
		error_log("before calling post");
		error_log("url = ".$url);
		$data['key1'] = $key1;
		$data['signature'] = $local_signature;
		error_log("request sent ==> ".json_encode($data));
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, NIBSS_CURL_CONNECTION_TIMEOUT);
		curl_setopt($ch, CURLOPT_TIMEOUT, NIBSS_CURL_TIMEOUT);
		$curl_response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$curl_error = curl_errno($ch);
		curl_close($ch);
		if ( $curl_error == 0 ) {
			error_log("curl_error == 0 ");
			error_log("response received = ".$curl_response);
			error_log("code = ".$httpcode);
			if ( $httpcode == 200 ) {
				error_log("inside httpcode == 200");
				$api_response = json_decode($curl_response, true);
				$statusCode = $api_response['responseCode'];
				$responseDescription = $api_response['responseDescription'];
				error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
				error_log("response_received <=== ".$curl_response);
				if($statusCode == 0) {
					$response["statusCode"] = "0";
					$response["result"] = "Success";
					$response["message"] = $responseDescription;
					$response["partnerId"] = $partnerId;
					$response["signature"] = $server_signature;
					$response["transactionId"] = $fin_request_id;
					$response["accountName"] = $api_response['accountName'];
					$response["sessionId"] = $api_response['sessionId'];
					$response["bvn"] = $api_response['bvn'];
					$response["kycLevel"] = $api_response['kycLevel'];
					$response["accountNumber"] = $api_response['accountNumber'];
				}
				else {
					error_log("inside statusCode != 0");
					$response["statusCode"] = $statusCode;
					$response["result"] = "Error";
					$response["message"] = $responseDescription;
					$response["partnerId"] = $partnerId;
					$response["signature"] = $server_signature;
				}
			}
			else {
				error_log("inside httpcode != 200");
				$statusCode = $httpcode;
				$responseDescription = "HTTP Protocol Error";
				error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
				
				$response["statusCode"] = $statusCode;
				$response["result"] = "Error";
				$response["message"] = "Error in connection to FinSol API Server";
				$response["partnerId"] = $partnerId;
				$response["signature"] = $server_signature;
			}
		}else {
			error_log("curl_error != 0 ");
			$statusCode = $curl_error;
			$responseDescription = "CURL Execution Error";
			error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
			$response["statusCode"] = $statusCode;
			$response["result"] = "Error";
			$response["message"] = "Error in communication protocol";
			$response["partnerId"] = $partnerId;
			$response["signature"] = $server_signature;
		}
		
		return $response;
	}
	
?>
	