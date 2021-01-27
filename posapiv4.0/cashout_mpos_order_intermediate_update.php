<?php
    	error_log("inside posapi/cashout_mpos_order_update.php");
    	include('../common/admin/configmysql.php');
    	include ("get_prime.php");	
    	include ("functions.php");
	require_once ("AesCipher.php");
	error_log("inside posapi/cashout_mpos_order_intermediate_update.php");

	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;
	$journal_entry_error = "N";

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		error_log("inside post request method");
		$data = json_decode(file_get_contents("php://input"));
		error_log("order_intermediate_update <== ".json_encode($data));	

		if ( isset($data -> orderNo ) && !empty($data -> orderNo) &&  isset($data -> responseCode ) && !empty($data -> responseCode) 
    		        && isset($data -> stan ) && !empty($data -> stan) && isset($data -> rrn ) && !empty($data -> rrn) 
    		        && isset($data -> orderType ) && !empty($data -> orderType) && isset($data -> kadickTransactionId ) && !empty($data -> kadickTransactionId)
    		        && isset($data -> partyCode ) && !empty($data -> partyCode) && isset($data -> partyType ) && !empty($data -> partyType) 
            		&& isset($data -> userId ) && !empty($data -> userId) 
            		&& isset($data -> countryId ) && !empty($data -> countryId) && isset($data -> stateId ) && !empty($data -> stateId) 
			&& isset($data -> signature ) && !empty($data -> signature) && isset($data -> key1 ) && !empty($data -> key1 )) {
			
			error_log("inside all inputs are set correctly");
			$signature = $data -> signature;
			$countryId = $data -> countryId;
			$stateId = $data -> stateId;
			$orderNo = $data -> orderNo;
			$emvTansactionId = $data -> kadickTransactionId;
			$userId = $data -> userId;
			$mPosResponseCode = $data -> responseCode;
			$mPosResponseDesc = $data -> responseDesc;
			$stan = $data -> stan;
			$rrn = $data -> rrn;
			$mPan = $data -> mPan;
			$transactionId = $data -> transactionId;
			$transactionTime = $data -> transactionTime;
			$terminalId = $data -> terminalId;
			$orderType = $data -> orderType;
			$key1 = $data -> key1;
			$orderPartyCode = $data -> partyCode;
			$orderPartyType = $data -> partyType;
			$orderParentCode = $data -> parentCode;
			$orderParentType = $data -> parentType;
			
            		$comment = "TID: ".$terminalId.", PAN: ".$mPan.", ID: ".$transactionId.", DTime: ".$transactionTime;
            		$approverComment = "RC: ".$mPosResponseCode."-".$mPosResponseDesc.", STAN: ".$stan.", RRN: ".$rrn;
			
			date_default_timezone_set('Africa/Lagos');
			$nday = date('z')+1;
			$nyear = date('Y');
			$nth_day_prime = get_prime($nday);
			$nth_year_day_prime = get_prime($nday+$nyear);
			$local_signature = $nday + $nth_day_prime;
			$server_signature = $nth_year_day_prime + $nday + $nyear;
			
			if ( $local_signature == $signature ){	

				$insert_query = "INSERT into emv_request_detail (emv_request_detail_id, transaction_id, order_no, order_type, comments, approver_comments, create_time) values (0, $emvTansactionId, $orderNo, '$orderType', '$comment', '$approverComment', now())";
                		error_log("insert_query = ".$insert_query);
                		$insert_query_result = mysqli_query($con, $insert_query);
                		if ($insert_query_result) {
                			$response["result"] = "Success";
					$response["message"] = "Successfully processed intermediate update";
                		
                		}else {
                			$response["result"] = "Failure";
					$response["message"] = "Error in processing intermediate update";
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
    	error_log("order_intermediate_update ==> ".json_encode($response));
	echo json_encode($response);

?>
