<?php
	include('../common/admin/configmysql.php');
	include ("get_prime.php");	
	include ("functions.php");
	require_once ("AesCipher.php");
	error_log("inside pcposapi/server_updater.php");

	//ERROR_REPORTING(E_ALL);
	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;
	$journal_entry_error = "N";

	//Checking Post Method.
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		error_log("inside post request method");
		$data = json_decode(file_get_contents("php://input"));
		error_log("server_updater <== ".json_encode($data));

		if( isset($data->operation) && $data->operation == 'SERVER_UPDATER_AVAILABLE_BALANCE') {
			error_log("inside operation == SERVER_UPDATER_AVAILABLE_BALANCE method");

            		if ( isset($data->signature) && !empty($data->signature) && isset($data->partyCode) && !empty($data->partyCode) 
                		&& isset($data->partyType) && !empty($data->partyType) && isset($data->key1) && !empty($data->key1)  
		   		&& isset($data->partyCode) && !empty($data->partyCode) && isset($data->userId) && !empty($data->userId) 
				&& isset($data->userId) && !empty($data->userId) 
				
                	) {
				error_log("inside all inputs are set correctly");	
				$partyCode = $data->partyCode;
				$partyType =  $data->partyType;
				$parentCode = $data->parentCode;
				$parentType= $parentType->parentType;
				$userId = $data->userId;
				$signature= $data->signature;
				$key1 = $data->key1;								
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

				//checking  signature 
				if ( $local_signature == $signature ){		
					
					$validate_result = validateKey1($key1, $userId, $session_validity, 'S', $con);
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
		
					$new_available_balance = check_party_available_balance($partyType, $userId, $con);
					error_log("new_available_balance for userId [".$userId."] = ".$new_available_balance);
					
					$response["result"] = "Success";
					$response["message"] = "Your request is processed successfuly";
					$response["statusCode"] = 0;
					$response["signature"] = $server_signature;
					$response["availableBalance"] = $new_available_balance;
				}	
				else {
					// Invalid Singature
					$response["statusCode"] = 300;
					$response["result"] = "Error";
					$response["message"] = "Failure: Invalid request";
					$response["partnerId"] = $partnerId;
					$response["signature"] = 0;
				}								
			}
			else {
				// Failure - Invalid Data
				$response["result"] = "failure";
				$response["statusCode"] = 400;
				$response["message"] = "Failure: Invalid Data";
				$response["partnerId"] = 0;
				$response["signature"] = 0;
			}
		}
		else {	
			// Invalid Operation
			$response["statusCode"] = 500;
			$response["result"] = "Error";
			$response["message"] = "Failure: Invalid Operation";
			$response["partnerId"] = 0;
			$response["signature"] = 0;
		}
	}else {
		// Invalid Request Method
		$response["statusCode"] = 600;
		$response["result"] = "Error";
		$response["message"] = "Failure: Invalid Request Method";
		$response["partnerId"] = 0;
		$response["signature"] = 0;	
	}			
		
	// echoing JSON response
   	error_log("cashout_ussd_trigger ==> ".json_encode($response));
	echo json_encode($response);
	return;			
?>
