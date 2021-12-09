<?php
    	error_log("inside posapi/ptsp_key_update.php");
    	include('../common/admin/configmysql.php');
    	include ("get_prime.php");	
    	include ("functions.php");
	require_once ("AesCipher.php");

	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;
	$journal_entry_error = "N";

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		error_log("inside post request method");
		$data = json_decode(file_get_contents("php://input"));
		error_log("key_update <== ".json_encode($data));	

		if ( isset($data -> partyCode ) && !empty($data -> partyCode) && isset($data -> partyType ) && !empty($data -> partyType) 
            		&& isset($data -> userId ) && !empty($data -> userId) && isset($data -> countryId ) && !empty($data -> countryId) 
            		&& isset($data -> stateId ) && !empty($data -> stateId) && isset($data -> ptspKey ) && !empty($data -> ptspKey) 
			&& isset($data -> signature ) && !empty($data -> signature) && isset($data -> key1 ) && !empty($data -> key1 )) {
			
			error_log("inside all inputs are set correctly");
			$signature = $data -> signature;
			$countryId = $data -> countryId;
			$stateId = $data -> stateId;
			$userId = $data -> userId;
			$key1 = $data -> key1;
			$partyCode = $data -> partyCode;
			$partyType = $data -> partyType;
			$ptspKey = $data -> ptspKey;
			
			date_default_timezone_set('Africa/Lagos');
			$nday = date('z')+1;
			$nyear = date('Y');
			$nth_day_prime = get_prime($nday);
			$nth_year_day_prime = get_prime($nday+$nyear);
			$local_signature = $nday + $nth_day_prime;
			$server_signature = $nth_year_day_prime + $nday + $nyear;
			
			if ( $local_signature == $signature ){	
				$key_select_query = "SELECT user_pos_key_id from user_pos_key where user_id = ".$userId." and party_code = '".$partyCode."' and party_type = '".$partyType."'";
				error_log("key_select_query = ".$key_select_query);
				$key_select_result = mysqli_query($con, $key_select_query);
				if ( $key_select_result ) {
					$key_select_count = mysqli_num_rows($key_select_result);
					if($key_select_count > 0) {
						$key_update_query = "UPDATE user_pos_key set previous_ptsp_key = ptsp_key, previous_create_time = create_time, ptsp_key = '$ptspKey', create_time = now() where user_id = ".$userId." and party_code = '".$partyCode."' and party_type = '".$partyType."'";
						error_log("key_update_query = ".$key_update_query);
						$key_update_result = mysqli_query($con, $key_update_query);
						if ( $key_update_result ) {
							$response["result"] = "Success";
							$response["message"] = "Successfully processed update key";
						}else {
							$response["result"] = "Failure";
							$response["message"] = "Error in processing update key";
						}
					}else {
						$key_insert_query = "INSERT INTO user_pos_key (user_pos_key_id, user_id, party_type, party_code, ptsp_key, create_time) values (0, $userId, '$partyType', '$partyCode', '$ptspKey', now())";
						error_log("key_insert_query = ". $key_insert_query);
						$key_insert_result = mysqli_query($con, $key_insert_query);
						if ( $key_insert_result ) {
							$response["result"] = "Success";
							$response["message"] = "Successfully processed key update";
						}else {
							$response["result"] = "Failure";
							$response["message"] = "Error in processing key update";
						}
					}
				}else {
					$response["result"] = "Failure";
					$response["message"] = "Error in select key update request";
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
    	error_log("key_update ==> ".json_encode($response));
	echo json_encode($response);
?>
