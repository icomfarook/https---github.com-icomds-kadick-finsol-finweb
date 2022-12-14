<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));
	require '../api/get_prime.php';
	require '../api/security.php';
	require '../common/gh/autoload.php';	
	$action =$data->action;
	$userId = $_SESSION['user_id'];
	error_log($userId);
	if($action == "query") {

		$res = sendRequest($userId);
		$api_response = json_decode($res, true);
		$response = array();
		$response["pendingBalance"] = number_format($api_response['pendingBalance'], 2, '.', ',');
		$response["balance"] = number_format($api_response['balance'], 2, '.', ',');
		$response["updatedAt"] = $api_response['updatedAt'];
		$response["createdAt"] = $api_response['createdAt'];
		$response["name"] = $api_response['name'];
		$response["balanceStatus"] = $api_response['balanceStatus'];
		$response["processingStartTime"] = $api_response['processingStartTime'];
		$response["responseDescription"] = $api_response['responseDescription'];
		
		/*
		$pendingBalance = number_format($api_response['pendingBalance'], 2, ".", ",");
		$balance = number_format($api_response['balance'], 2, '.', ',');
		$updatedAt = $api_response['updatedAt'];
		$createdAt = $api_response['createdAt'];
		$name = $api_response['name'];
		$balanceStatus = $api_response['balanceStatus'];
		$processingStartTime = $api_response['processingStartTime'];
		$responseDescription = $api_response['responseDescription'];
		echo json_encode($api_response);
		*/
		echo json_encode($response);
	}
	
	function sendRequest($userId){
	
		error_log("entering sendWalatbalanceRequest");
		date_default_timezone_set('Africa/Lagos');
		$nday = date('z')+1;
		$nyear = date('Y');
		$nth_day_prime = get_prime($nday);
		$nth_year_day_prime = get_prime($nday+$nyear);
		$signature = $nday + $nth_day_prime;
		$tsec = time();
		//$raw_data1 = FINAPI_SERVER_APP_PASSWORD.FINWEB_SERVER_SHORT_NAME."|".FINAPI_SERVER_APP_USERNAME.FINWEB_SERVER_SHORT_NAME."|".$tsec;
		$raw_data1 = BPAPI_SERVER_APP_PASSWORD."|".BPAPI_SERVER_APP_USERNAME."|".$tsec;
		$key1 = base64_encode($raw_data1);
		error_log("before calling post");
		error_log("url = ".BPAPI_PAYANT_SERVER_WALLET_BALANCE_URL);		
		$body['countryId'] = ADMIN_COUNTRY_ID;
		$body['stateId'] = ADMIN_STATE_ID;
		$body['localGovtId'] = ADMIN_LOCAL_GOVT_ID;
		$body['userId'] = $userId;
		$body['key1'] = $key1;
		$body['signature'] = $signature;
		error_log("request sent ==> ".json_encode($body));
		$ch = curl_init(BPAPI_PAYANT_SERVER_WALLET_BALANCE_URL);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, FINAPI_SERVER_CONNECT_TIMEOUT);
		curl_setopt($ch, CURLOPT_TIMEOUT, FINAPI_SERVER_REQUEST_TIMEOUT);
		
		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		error_log("response received <== ".$response);
		error_log("code ".$httpcode);
		error_log("exiting WalletBalanceRequest");
      	return $response;
	}
?>