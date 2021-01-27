<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));
	require '../api/get_prime.php';
	require '../api/security.php';
	require '../common/gh/autoload.php';	
	$action =$data->action;
	$crestatus = $data->crestatus;
	$startDate = $data->startDate;
	$endDate = $data->endDate;
	$mobileNumber = $data->mobileNumber;
	$creteria = $data->creteria;
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
	if($action == "query") {
		if($creteria == "BD") {
			$query = "SELECT t1account_request_id, concat(first_name,' ', last_name) as user, mobile, status, account_no, create_time FROM t1account_request WHERE date(create_time) between '$startDate' and '$endDate'";
		}
		if($creteria == "BM") {
			$query = "SELECT t1account_request_id, concat(first_name,' ', last_name) as user, mobile, status, account_no, create_time FROM t1account_request WHERE mobile = '$mobileNumber'";
		}		
		error_log($query);
		$result = mysqli_query($con, $query);
		if(!$result) {
			$response = array();
			$response["msg"] = 'Select request Failure';
			$response["responseCode"] = 100;
			$response["errorResponseDescription"] = mysqli_error($con);
		}
		else {
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['t1account_request_id'],"user"=>$row['user'],"mobile"=>$row['mobile'],"status"=>$row['status'],"acno"=>$row['account_no'],"cretime"=>$row['create_time']);           
			}
			echo json_encode($data);
		}
	}
	
	if($action == "post") {
		$create_user = $_SESSION['user_id'];
		$get_sequence_number_query = "SELECT get_sequence_num(2200) as id";
		$get_sequence_number_result =  mysqli_query($con,$get_sequence_number_query);
		if(!$get_sequence_number_result) {
			error_log('Get sequnce number 2 failed: ' . mysqli_error($con));
			echo "GETSEQ - Failed";				
		}	
		else {
			$get_sequence_num_row = mysqli_fetch_assoc($get_sequence_number_result);
			$id = $get_sequence_num_row['id'];
			$reqMsg = " startDate: ".$startDate.", startDate: ".$startDate.",mobileNumber: ".$mobileNumber;
			$query =  "INSERT INTO fin_non_trans_log (fin_non_trans_log_id, service_feature_id, message_send_time, create_user, create_time, request_message ) VALUES ($id, 17, now(), $create_user, now(), '$reqMsg')";
			error_log($query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				echo "Error: %s\n". mysqli_error($con);
			}
			else {
				$res = sendRequest($mobileNumber);
				$api_response = json_decode($res, true);
				$response_code = $api_response['responseCode'];
				$res_description = $api_response['responseDescription'];
				$description = $api_response['description'];
				$query1 = "UPDATE fin_non_trans_log SET response_message ='$res', message_receive_time = now(), response_received = 'Y', error_code = '$response_code', error_description = '$res_description' where fin_non_trans_log_id = $id ";                 
				$result = mysqli_query($con,$query1);
				error_log($query1);
				echo $res;
			}
		}		
		
		error_log("respnse = ".$res);
		echo $response;
		
	}
	function sendRequest($mobileNumber){
	
		error_log("entering sendTierA1Account");
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
		error_log("url = ".FINAPI_SERVER_TIER_AC1_URL);		
		$body['countryId'] = ADMIN_COUNTRY_ID;
		$body['stateId'] = ADMIN_STATE_ID;
		$body['localGovtId'] = ADMIN_LOCAL_GOVT_ID;
		$body['partnerId'] = 16;
		$body['mobileNumber'] = $mobileNumber;
		$body['key1'] = $key1;
		$body['signature'] = $Signature;
		error_log("request sent ==> ".json_encode($body));
		$ch = curl_init(FINAPI_SERVER_TIER_AC1_URL);
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
		error_log("exiting sendTierA1AccountRequest");
      	return $response;
	}
?>