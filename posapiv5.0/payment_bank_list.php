<?php
	include('../common/admin/configmysql.php');
	include("../common/admin/finsol_crypt.php");
	include ("functions.php");
	include ("get_prime.php");	
	require_once ("AesCipher.php");
	error_log("inside pcposapi/payment_bank_list.php");

	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		error_log("inside post request method");
        	$data = json_decode(file_get_contents("php://input"));
        	error_log("payment_bank_list <== ".json_encode($data));				

		if(isset($data -> operation) && $data -> operation == 'PAYMENT_BANK_LIST') {
			error_log("inside operation == PAYMENT_BANK_LIST method");

            		if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
            		        && isset($data->userId) && !empty($data->userId)  ) {			
			
				error_log("inside all inputs are set correctly");	
				$signature= $data->signature;
				$key1 = $data->key1;
				$userId = $data->userId;
				$session_validity = AGENT_SESSION_VALID_TIME;	

				error_log("signature = ".$signature.", key1 = ".$key1);
				date_default_timezone_set('Africa/Lagos');
				$nday = date('z')+1;
				$nyear = date('Y');
				$nth_day_prime = get_prime($nday);
				$nth_year_day_prime = get_prime($nday+$nyear);
				$local_signature = $nday + $nth_day_prime;
				error_log("local_signature = ".$local_signature);
				$server_signature = $nth_year_day_prime + $nday + $nyear;
				error_log("server_signature = ".$server_signature);
								
				if ( $local_signature == $signature ) {
					$validate_result = validateKey1($key1, $userId, $session_validity, 'U', $con);
					error_log("validateKey1 result = ".$validate_result);
					if ( $validate_result != 0 ) {
						// Invalid key1 - Session Timeut
						$response["statusCode"] = "999";
						$response["result"] = "Error";
						$response["message"] = "Failure: Session Timeout";
						$response["signature"] = 0;
						error_log(json_encode($response));
						echo json_encode($response);
						return;
					} 
					$bank_list_query = "select bank_account_id, concat(bank_name, '-', account_no) as bank_name, account_name as account from bank_account where active = 'Y'";
					error_log("bank_list_query = ".$bank_list_query);
                   	 		$bank_list_result = mysqli_query($con, $bank_list_query);
                    			$response["bankList"] = array();
					if ($bank_list_result) {
                        			while($bank_list_row = mysqli_fetch_assoc($bank_list_result)) {	
                            				$bank = array();
							$bank['id'] = $bank_list_row['bank_account_id'];
							$bank['name'] = $bank_list_row['bank_name'];
							$bank['code'] = $bank_list_row['account'];
							array_push($response["bankList"], $bank);
                        			}
                       				$response["statusCode"] = "0";
						$response["signature"] = $server_signature;
						$response["message"] = "Bank List request is processed";
						$response["result"] = "Success";
					}else {
						// DB failure
						$response["statusCode"] = "10";
						$response["result"] = "Error";
						$response["message"] = "Failure: Error in reading charges from DB";
					}
				}else {
					// Invalid Singature
					$response["statusCode"] = "20";
					$response["result"] = "Error";
					$response["message"] = "Failure: Invalid request";
				}
			}else {
				// Invalid Data
				$response["statusCode"] = "30";
				$response["result"] = "Error";
				$response["message"] = "Failure: Invalid Data";
				$response["signature"] = 0;	
			}
		}else {
			// Invalid Operation
			$response["statusCode"] = "40";
			$response["result"] = "Error";
			$response["message"] = "Failure: Invalid Operation";
			$response["signature"] = 0;	
		}
	}else {
		// Invalid Request Method
		$response["statusCode"] = "50";
		$response["result"] = "Error";
		$response["message"] = "Failure: Invalid Request Method";
		$response["signature"] = 0;	
	}
	error_log("payment_bank_list ==> ".json_encode($response));
	echo json_encode($response);
?>