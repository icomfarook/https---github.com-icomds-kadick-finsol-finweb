 <?php

	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	require '../api/get_prime.php';
	require '../api/security.php';
	require '../common/gh/autoload.php';		
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;	
	$country = $_SESSION['country_id'];	
	$state = $_SESSION['state_id'];	
	$local = $_SESSION['local_govt_id'];	
	$accountNo = $data->accountNo;
	
	if($action == "getbalenq") {
		$create_user = $_SESSION['user_id'];
		$get_sequence_number_query = "SELECT get_sequence_num(2200) as id";
		$get_sequence_number_result =  mysqli_query($con,$get_sequence_number_query);
		if(!$get_sequence_number_result) {
			error_log('Get sequnce number 2 failed: ' . mysqli_error($con));
			echo "GETSEQ - Failed";				
		}	
		else{
			$query = "select ams_partner_id as partner_id from ams_partner_detail where account_no = '$accountNo'";
			error_log("entering query = ".$query);
			$result =  mysqli_query($con,$query);
				$get_sequence_num_row = mysqli_fetch_assoc($get_sequence_number_result);
				$id = $get_sequence_num_row['id'];
				$reqMsg = " accountNo: ".$accountNo;
				$query1 =  "INSERT INTO fin_non_trans_log (fin_non_trans_log_id, service_feature_id, message_send_time, create_user, create_time, request_message ) VALUES ($id, 4, now(), $create_user, now(), '$reqMsg')";
				error_log($query1);
				$result1 = mysqli_query($con,$query1);
				if (!$result1) {
					echo "Error: %s\n". mysqli_error($con);
				}
			elseif (!$result) {
				printf("Error: %s\n".mysqli_error($con));
			}
			else {
				$row = mysqli_fetch_assoc($result);
				$partnerId = $row['partner_id'];			
				$res = sendRequest($partnerId, $accountNo);
				//error_log("respnse = ".$response);
				$api_response = json_decode($res, true);
				$response_code = $api_response['responseCode'];
				$res_description = $api_response['responseDescription'];
				//$description = $api_response['description'];
				$query2 = "UPDATE fin_non_trans_log SET response_message ='$res', message_receive_time = now(), response_received = 'Y', error_code = '$response_code', error_description = '$res_description' where fin_non_trans_log_id = $id ";                 
				
				error_log($query2);
				$result = mysqli_query($con,$query2);
				echo $res;
				echo $response;
			}
			
			
		}
		
	}	
		
	function sendRequest($partnerId, $accountNo) {	
		error_log("entering sendRequest");
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
		error_log("url = ".FINAPI_SERVER_BALANCE_ENQUIREY_URL);		
		$body['countryId'] = $_SESSION['country_id'];
		$body['stateId'] =  $_SESSION['state_id'];
		$body['localGovtId'] =  $_SESSION['local_govt_id'];
		$body['partnerId'] = $partnerId;		
		$body['accountNo'] = $accountNo;
		$body['key1'] = $key1;
		$body['signature'] = $signature;
		error_log("request sent ==> ".json_encode($body));
		$ch = curl_init(FINAPI_SERVER_BALANCE_ENQUIREY_URL);
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
		error_log("exiting sendRequest");
      	return $response;
	}
	
	
?>	