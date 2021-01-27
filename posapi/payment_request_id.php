<?php
	include('../common/admin/configmysql.php');
	include("../common/admin/finsol_crypt.php");
	include ("get_prime.php");	
	require_once ("AesCipher.php");
    require_once("db_connect.php");
    include ("functions.php");
	error_log("inside pcposapi/payment_request_id.php");

	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		error_log("inside post request method");
        $data = json_decode(file_get_contents("php://input"));
        error_log("payment_request_id <== ".json_encode($data));

		if(isset($data -> operation) && $data -> operation == 'PAYMENT_CARD_PAYMENT_ID') {
			error_log("inside operation == PAYMENT_CARD_PAYMENT_ID method");
            if ( isset($data->signature) && !empty($data->signature)
                    && isset($data->userId) && !empty($data->userId)  
                    && isset($data->countryId) && !empty($data->countryId)  
                    && isset($data->key1) && !empty($data->key1) 
             ){

				error_log("inside all inputs are set correctly");

                $userId = $data->userId;
                $countryId = $data->countryId;
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
                                
				if ( $local_signature == $signature ) {
					$validate_result = validateKey1($key1, $userId, $session_validity, 'P', $con);
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
                    $payment_receipt_id = generate_seq_num(1100, $con);
                    if( $payment_receipt_id > 0 )  {
                        error_log("get payment_receipt_id is success");
                        $response["result"] = "Success";
                        $response["message"] = "Your payment Id request is accepted";
                        $response["statusCode"] = 0;
                        $response["signature"] = $server_signature;
                        $response["paymentId"] = $payment_receipt_id;
                    }
					else {
						// DB failure
						$response["result"] = "Error";
						$response["statusCode"] = "200";
                        $response["message"] = "Failure: Error in getting payment submission no";
                        $response["signature"] = $server_signature;
					}
				}else {
					// Invalid Singature
					$response["statusCode"] = "300";
					$response["result"] = "Error";
                    $response["message"] = "Failure: Invalid request";
                    $response["signature"] = $server_signature;
				}
			}else {
				// Invalid Data
				$response["statusCode"] = "400";
				$response["result"] = "Error";
                $response["message"] = "Failure: Invalid Data";
                $response["signature"] = 0;
			}
        }else {
			// Invalid Operation
			$response["statusCode"] = "500";
			$response["result"] = "Error";
            $response["message"] = "Failure: Invalid Operation";
            $response["signature"] = 0;
		}
	}else {
		// Invalid Request Method
		$response["result"] = "success";
		$response["status"] = "600";
        $response["message"] = "Post Failure";
        $response["signature"] = 0;
	}
    error_log("payment_request_id ==> ".json_encode($response));
	echo json_encode($response);
?>