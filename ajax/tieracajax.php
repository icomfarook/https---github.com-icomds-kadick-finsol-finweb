<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));
	require '../api/get_prime.php';
	require '../api/security.php';
	require '../common/gh/autoload.php';
	$location = '../upload/';
	$action = $_POST['action'];
	$countfiles = count($_FILES['file']['name']);
	$filename_arr = array(); 
	$filename = $_FILES['file']['name'][0];  
	error_log("filename".$filename);
	$check = filesize ($filename);   
	move_uploaded_file($_FILES['file']['tmp_name'][0],$location.$filename);  
    $content = file_get_contents($location.$filename);
	   
	$filename_arr[] = $filename;		
	$arr = array('name' => $filename_arr);
	$refmobileno =$_POST['refmobileno'];
	$dob =$_POST['dob'];
	$lastName =$_POST['lastName'];
	$firstName =$_POST['firstName'];
	$email =$_POST['email'];
	$bvn =$_POST['bvn'];
	$mobileno =$_POST['mobileno'];
	$state =$_POST['state'];
	$country =$_POST['country'];
	$user =$_SESSION['user_id'];
	$partner = $_POST['partner'];	
	$dob = date("Y-m-d", strtotime($dob));
	$filetype =  mysqli_real_escape_string($con,pathinfo($location.$filename, PATHINFO_EXTENSION));
	error_log("filetype = ".$filetype);
	if($filetype != "pdf" || $filetype != "jpg" || $filetype != "png" || $filetype != "gif") {
		$filetype = "oth";
	}
	if($action == "create") {
		$seq_no_query = "SELECT get_sequence_num(2000) as seq_no";
		error_log("seq_no_query = ".$seq_no_query);
		$seq_no_result = mysqli_query($con, $seq_no_query);
		$get_sequence_number_query = "SELECT get_sequence_num(2200) as id";
		$get_sequence_number_result =  mysqli_query($con,$get_sequence_number_query);
		if(!$seq_no_result) {
			$response = array();
			$response["msg"] = 'Getting Sequence No Failure';
			$response["responseCode"] = 100;
			$response["errorResponseDescription"] = mysqli_error($con);
		}
		elseif(!$get_sequence_number_result) {
			error_log('Get sequnce number 2 failed: ' . mysqli_error($con));
			echo "GETSEQ - Failed";				
		}
		else {
			$get_sequence_num_row = mysqli_fetch_assoc($get_sequence_number_result);
			$id = $get_sequence_num_row['id'];
			$reqMsg = "country: ".$country.", state: ".$state.", partner: ".$partner."mobileno: ".$mobileno.", firstName: ".$firstName.", lastName: ".$lastName.", dob: ".$dob."email: ".$email.", bvn: ".$bvn.", refmobileno: ".$refmobileno;
			$seq_no_row = mysqli_fetch_assoc($seq_no_result);
			$seq_no = $seq_no_row['seq_no'];		
			$query =  "INSERT INTO t1account_request (t1account_request_id, first_name, last_name, mobile, partner_id, dob, email, bvn, referrer_mobile, country_id, state_id, create_user, create_time)
											 VALUES  ($seq_no, '$firstName', '$lastName', '$mobileno','$partner', '$dob', '$email', '$bvn', '$refmobileno', $country, $state, $user, now())";
			error_log($query);
			$query1 =  "INSERT INTO fin_non_trans_log (fin_non_trans_log_id, service_feature_id, message_send_time, create_user, create_time, request_message ) VALUES ($id, 16, now(), $user, now(), '$reqMsg')";
			error_log($query1);
			$result = mysqli_query($con,$query);
			$result1 = mysqli_query($con,$query1);
			if (!$result) {
				$response = array();
				$response["msg"] = 'DB1:Filure';
				$response["responseCode"] = 100;
				$response["errorResponseDescription"] = mysqli_error($con);
			}
			elseif (!$result1) {
				error_log("fin_non_trans_log insert failed");
			}
			else {
				$content = mysqli_real_escape_string($con,$content);
				error_log("content".$content);
				$query =  "INSERT INTO t1account_document (t1account_document_id, t1account_request_id, document_name, content, document_type)
														VALUES  (0, $seq_no, '$filename','$content','$filetype')";
				error_log($query);
				$result = mysqli_query($con,$query);
				if (!$result) {
					$response = array();
					$response["msg"] = 'DB2:Filure';
					$response["responseCode"] = 200;
					$response["errorResponseDescription"] = mysqli_error($con);
				}
				else {
					$response = sendRequest($country, $state, $partner, $mobileno, $firstName, $lastName, $dob, $email, $bvn, $refmobileno);	
					$json = json_decode($response, true);
					$responseCode = $json['responseCode'];	
					$responseDescription = $json['responseDescription'];
					$accountNumber = $json['newAccountNumber'];
					$transactionRef = $json['transactionRef'];
					$api_response = json_decode($response, true);
					$response_code = $api_response['responseCode'];
					$res_description = $api_response['responseDescription'];
					error_log("accountNumber".$accountNumber);	
					$updaqueryquery =  "UPDATE t1account_request SET reference_no = '$transactionRef',account_no = '$accountNumber', response_code = $responseCode, response_description = '$responseDescription', update_user = $user, update_time = now() WHERE t1account_request_id = $seq_no";
					error_log($updaqueryquery);
						$query1 = "UPDATE fin_non_trans_log SET response_message ='$response', message_receive_time = now(), response_received = 'Y', error_code = '$response_code', error_description = '$res_description' where fin_non_trans_log_id = $id ";                 
						error_log($query1);
						$result1 = mysqli_query($con,$query1);
					$result = mysqli_query($con,$updaqueryquery);
					if (!$result) {
						$response = array();
						$response["msg"] = 'DB3:Filure';
						$response["responseCode"] = 300;
						$response["errorResponseDescription"] = mysqli_error($con);
					}
					elseif (!$result1) {
						error_log("fin_non_trans_log update failed");
					}
					else {
						$response = array();
						$response["msg"] = 'Success';
						$response["responseCode"] = 00;
						$response["errorResponseDescription"] = "Tier 1 Account $firstName - $lastName Inserted Successfully";
					}
				}
			}
		}
		echo json_encode($response);
	}
	
	
	function sendRequest($country, $state, $partner, $mobileno, $firstName, $lastName, $dateOfBirth, $emailAddress, $bvn, $refmobileno){
	
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
		error_log("url = ".FINAPI_SERVER_TIER_AC1_CREATE_URL);		
		$body['countryId'] = $country;
		$body['stateId'] = $state;
		$body['localGovtId'] = 'null';
		$body['partnerId'] = $partner;
		$body['mobileNumber'] = $mobileno;
		$body['firstName'] = $firstName;
		$body['lastName'] = $lastName;
		$body['dateOfBirth'] = $dateOfBirth;
		$body['emailAddress'] = $emailAddress;
		$body['bvn'] = $bvn;
		$body['referrerMobileNumber'] = $refmobileno;
		$body['referenceNumber'] = mt_rand(1000,9999); 
		$body['key1'] = $key1;
		$body['signature'] = $Signature;
		error_log("request sent ==> ".json_encode($body));
		$ch = curl_init(FINAPI_SERVER_TIER_AC1_CREATE_URL);
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