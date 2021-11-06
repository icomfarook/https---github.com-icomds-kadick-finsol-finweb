<?php
	include('../common/admin/configmysql.php');
	include ("get_prime.php");	
	include ("functions.php");
	require_once ("AesCipher.php");
	error_log("inside pcposapi/message_update.php");

	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;
	$journal_entry_error = "N";

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		error_log("inside post request method");
		$data = json_decode(file_get_contents("php://input"));
		error_log("message_update <== ".json_encode($data));	

		if ( isset($data -> userId ) && !empty($data -> userId) 
            		&& isset($data -> countryId ) && !empty($data -> countryId) && isset($data -> stateId ) && !empty($data -> stateId) 
            		&& isset($data -> partyCode ) && !empty($data -> partyCode) && isset($data -> partyType ) && !empty($data -> partyType)
            		&& isset($data -> picPoint ) && !empty($data -> picPoint) && isset($data -> messageType ) && !empty($data -> messageType)
            		&& isset($data -> userId ) && !empty($data -> userId)
			&& isset($data -> signature ) && !empty($data -> signature) && isset($data -> key1 ) && !empty($data -> key1 )) {
			
			error_log("inside all inputs are set correctly");
            		$signature = $data -> signature;
            		$countryId = $data -> countryId;
            		$stateId = $data -> stateId;
       			$key1 = $data -> key1;
       			$partyCode = $data -> partyCode;
       			$partyType = $data -> partyType;
       			$picPoint = $data -> picPoint;
       			$messageType = $data -> messageType;
       			$userId = $data -> userId;
       			$message = $data -> message;
       			$evmTxId = $data -> emvTxId;
         
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
			
			if ( $local_signature == $signature ){	
                		$response["result"] = "Success";
                		$response["signature"] = $server_signature;
                		$response["message"] = "Messgae update is accepted";
                		
                		$insert_mpos_debug_query = "INSERT INTO mpos_debug_dump (mpos_debug_dump_id, user_id, party_code, party_type, message, emv_tx_id, pic_point, message_type, date_time) VALUES(0, $userId, '$partyCode', '$partyType', '$message', '$evmTxId', $picPoint, '$messageType', now())";
				error_log("insert_mpos_debug_query insert_query: ".$insert_mpos_debug_query);
				$insert_mpos_debug_result = mysqli_query($con, $insert_mpos_debug_query);
				if ( !$insert_mpos_debug_result ) {
					error_log("Error in inserting mpos_debug ");
				}
				else {
					error_log("Success in inserting mpos_debug");
				}
            		}	
			else {
				$response["result"] = "Failure";
				$response["message"] = "Invalid signature";
			}
		}
		else {
			$response["result"] = "Failure";
			$response["message"] = "Invalid data";
		}
	}
	else {
		$response["result"] = "Failure";
		$response["message"] = "Invalid request";
	}
    	error_log("order_update ==> ".json_encode($response));
	echo json_encode($response);
    
?>
