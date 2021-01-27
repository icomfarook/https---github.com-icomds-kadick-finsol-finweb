<?php
	include('../common/admin/configmysql.php');
	include("../common/admin/finsol_crypt.php");
	include ("get_prime.php");	
	require_once ("AesCipher.php");
    require_once("db_connect.php");
    include ("functions.php");
	error_log("inside pcposapi/payment_bank_deposit.php");
	//error_reporting(E_ALL);
	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		error_log("inside post request method");
        $data = json_decode(file_get_contents("php://input"));
        error_log("payment_bank_deposit <== ".json_encode($data));

		if(isset($data -> operation) && $data -> operation == 'PAYMENT_BANK_DEPOSIT') {
			error_log("inside operation == PAYMENT_BANK_DEPOSIT method");
            if ( isset($data->signature) && !empty($data->signature)
                    && isset($data->userId) && !empty($data->userId)  
                    && isset($data->countryId) && !empty($data->countryId)  
                    && isset($data->key1) && !empty($data->key1) 
                    && isset($data->payment->bankId) && !empty($data->payment->bankId) 
                    && isset($data->payment->paymentDate) && !empty($data->payment->paymentDate) 
                    && isset($data->payment->paymentType) && !empty($data->payment->paymentType) 
                    && isset($data->payment->paymentAmount) && !empty($data->payment->paymentAmount) 
                    && isset($data->payment->paymentReferenceNo) && !empty($data->payment->paymentReferenceNo) 
                    && isset($data->payment->paymentComments) && !empty($data->payment->paymentComments) 
                    && isset($data->payment->partyCode) && !empty($data->payment->partyCode) 
                    && isset($data->payment->partyType) && !empty($data->payment->partyType)
			){

				error_log("inside all inputs are set correctly");
				$bankId = $data->payment->bankId;
                $paymentDate = $data->payment->paymentDate;
                $paymentType = $data->payment->paymentType;
                $paymentAmount = $data->payment->paymentAmount;
                $paymentReferenceNo = $data->payment->paymentReferenceNo;
                $paymentComments = $data->payment->paymentComments;
                $userId = $data->userId;
                $countryId = $data->countryId;
                $partyCode = $data->payment->partyCode;
                $partyType = $data->payment->partyType;
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
                    $validate_result = validateKey1($key1, $userId, $session_validity, 'B', $con);
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
						$paymentReferenceNo = mysqli_real_escape_string($con, $paymentReferenceNo);
						$paymentComments = mysqli_real_escape_string($con, $paymentComments);
                        $insert_payment_query = "INSERT into payment_receipt (p_receipt_id, country_id, payment_date, party_type, party_code, payment_type, payment_account_id, payment_amount, payment_reference_no, payment_status, comments, create_user, create_time) values ($payment_receipt_id, $countryId, date('$paymentDate'), '$partyType', '$partyCode', '$paymentType', $bankId, $paymentAmount, '$paymentReferenceNo', 'E', '$paymentComments', $userId, now())";
					    error_log("insert_payment_query = ".$insert_payment_query);
					    $insert_payment_result = mysqli_query($con, $insert_payment_query);
					    if($insert_payment_result) {
                            error_log("insert_payment_query is success");
                            $response["result"] = "Success";
                            $response["message"] = "Your payment submission #".$payment_receipt_id." is accepted";
                            $response["statusCode"] = 0;
                            $response["signature"] = $server_signature;
                            $response["paymentId"] = $payment_receipt_id;
                        }
                        else {
                            $response["result"] = "Error";
                            $response["message"] = "Error in submitting your payment request";
                            $response["statusCode"] = "100";
                            $response["signature"] = $server_signature;
                            $response["paymentId"] = 0;
                        }
                    }
					else {
						// DB failure
						$response["result"] = "Error";
						$response["statusCode"] = "200";
                        $response["message"] = "Failure: Error in getting contact submission no";
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
    error_log("payment_bank_deposit ==> ".json_encode($response));
	echo json_encode($response);
?>