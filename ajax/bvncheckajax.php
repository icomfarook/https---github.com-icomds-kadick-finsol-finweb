<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));
	require '../api/get_prime.php';
	require '../api/security.php';
	require '../common/gh/autoload.php';	
	$action =$data->action;
    $firstName = $data->FirstName;
    $lastName = $data->LastName;
    $phone = $data->mobileno;
    $dob = $data->dob;
    $bvn = $data->BVN;
	$userId = $_SESSION['user_id'];
	date_default_timezone_set("Africa/Lagos");
	$CurrentDate = date("Y-m-d H:i:s");
	$dob = date("Y-m-d", strtotime($dob));	

	if($action == "query") {

		$res = sendRequest($userId,$firstName,$lastName,$phone,$dob,$bvn);
		$api_response = json_decode($res, true);
		$response = array();

            $response["requestStatus"] = $api_response['requestStatus'];
			$response["bvn"] = $bvn;
			$response["validity"] = $api_response['validity'];
			$response["signature"] = $api_response['signature'];
			$response["responseCode"] = $api_response['responseCode'];
			$response["responseDescription"] = $api_response['responseDescription'];
			$response["processingStartTime"] = $CurrentDate;

		echo json_encode($response);
	}
	
	function sendRequest($userId,$firstName,$lastName,$phone,$dob,$bvn){
        
	
		error_log("entering SendBvnCheckReqeust");
		date_default_timezone_set('Africa/Lagos');
		$nday = date('z')+1;
		$nyear = date('Y');
		$nth_day_prime = get_prime($nday);
		$nth_year_day_prime = get_prime($nday+$nyear);
		$signature = $nday + $nth_day_prime;
		$tsec = time();
		$raw_data1 = FINAPI_SERVER_APP_PASSWORD.FINWEB_SERVER_SHORT_NAME."|".FINAPI_SERVER_APP_USERNAME.FINWEB_SERVER_SHORT_NAME."|".$tsec;
		$key1 = base64_encode($raw_data1);
		error_log("before calling post");
		error_log("url = ".BVN_CHECK_URL);		
		$body['countryId'] = ADMIN_COUNTRY_ID;
    	$body['stateId'] = ADMIN_STATE_ID;
		$body['localGovtId'] = ADMIN_LOCAL_GOVT_ID;
       
		$body['userId'] = $userId;
		$body['key1'] = $key1;
		$body['signature'] = $signature;
        $body['firstName'] = $firstName;
        $body['lastName'] = $lastName;
        $body['phone'] = $phone;
        $body['dob'] = $dob;
        $body['bvn'] = $bvn;
		error_log("request sent ==> ".json_encode($body));
		$ch = curl_init(BVN_CHECK_URL);
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
		error_log("exiting BvnCheckReqeust");
      		return $response;
	}
?>