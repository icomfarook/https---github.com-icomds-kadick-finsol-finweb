<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));
	require '../api/get_prime.php';
	require '../api/security.php';
	require '../common/gh/autoload.php';	
	$action =$data->action;
	$mobile =$data->mobile;
	$accountno =$data->accountno;
	$amount =$data->amount;
	$user =$_SESSION['user_id'];
	
	if($action == "gotp") {
		$seq_no_query = "SELECT get_sequence_num(1300) as seq_no";
		error_log("seq_no_query = ".$seq_no_query);
		$seq_no_result = mysqli_query($con, $seq_no_query);
		if(!$seq_no_result) {
			$response = array();
			$response["msg"] = 'Getting Sequence No Failure';
			$response["responseCode"] = 100;
			$response["errorResponseDescription"] = mysqli_error($con);
		}
		else {
			$seq_no_row = mysqli_fetch_assoc($seq_no_result);
			$seq_no = $seq_no_row['seq_no'];	
			$reqmessage = json_decode(file_get_contents("php://input"));
			$reqmessage = json_encode($data);			
			$query =  "INSERT INTO fin_trans_log (fin_trans_log_id, fin_service_feature_id, request_message, message_send_time, create_user, create_time)
										 VALUES  ($seq_no, 16,'$reqmessage',now(), $user, now())";
			error_log($query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				$response = array();
				$response["msg"] = 'DB1:Filure';
				$response["responseCode"] = 100;
				$response["errorResponseDescription"] = mysqli_error($con);
			}
			else {
				$response = sendRequest($mobile, $accountno, $amount);	
				$json = json_decode($response, true);
				$responseCode = $json['responseCode'];	
				$responseDescription = $json['responseDescription'];
				$updatequery =  "UPDATE fin_trans_log SET response_message = '$response', message_receive_time = now(), error_code = '$responseCode', error_description = '$responseDescription' WHERE  fin_trans_log_id = $seq_no";
				error_log($updatequery);
				$result = mysqli_query($con,$updatequery);
				if (!$result) {
					$response = array();
					$response["msg"] = 'DB1:Filure';
					$response["responseCode"] = 200;
					$response["errorResponseDescription"] = mysqli_error($con);
				}
				else {
					echo $response;
				}
			}
		
		}
	}
	
	function sendRequest($mobile, $accountno, $amount){
	
		error_log("entering generate otp request");
		date_default_timezone_set('Africa/Lagos');
		$nday = date('z')+1;
		$nyear = date('Y');
		$nth_day_prime = get_prime($nday);
		$nth_year_day_prime = get_prime($nday+$nyear);
		$Signature = $nday + $nth_day_prime;
		$tsec = time();
		$raw_data1 = FINAPI_SERVER_APP_PASSWORD.FINWEB_SERVER_SHORT_NAME."|".FINAPI_SERVER_APP_USERNAME.FINWEB_SERVER_SHORT_NAME."|".$tsec;
		$key1 = base64_encode($raw_data1);
		error_log("before calling post");
		error_log("url = ".FINAPI_SERVER_GENERATE_OTP_URL);		
		$body['countryId'] = ADMIN_COUNTRY_ID;
		$body['stateId'] = ADMIN_STATE_ID;
		$body['localGovtId'] = ADMIN_LOCAL_GOVT_ID;
		$body['partnerId'] = 16;
		$body['mobileNumber'] = $mobile;
		$body['amount'] = $amount;
		$body['accountNo'] = $accountno; 
		$body['key1'] = $key1;
		$body['signature'] = $Signature;
		error_log("request sent ==> ".json_encode($body));
		$ch = curl_init(FINAPI_SERVER_GENERATE_OTP_URL);
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
		error_log("exiting geneate otp request");
      	return $response;
	}
?>