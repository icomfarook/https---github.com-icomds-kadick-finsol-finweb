<?php
	include('../common/admin/configmysql.php');
	include("../common/admin/finsol_crypt.php");
	include ("get_prime.php");	
	require_once ("AesCipher.php");
    	require_once("db_connect.php");
    	include ("functions.php");
	error_log("inside pcposapi/contact.php");
	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		error_log("inside post request method");
        	$data = json_decode(file_get_contents("php://input"));
        	error_log("payout <== ".json_encode($data));

		if(isset($data -> operation) && $data -> operation == 'PAYOUT_INSERT') {
			error_log("inside operation == PAYOUT_INSERT method");
            		if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
			   && isset($data->payOut->payOutType) && !empty($data->payOut->payOutType) 
			   && isset($data->payOut->payOutAmount) && !empty($data->payOut->payOutAmount) 
			   && isset($data->payOut->processingAmount) && !empty($data->payOut->processingAmount) 
			   && isset($data->payOut->totalAmount) && !empty($data->payOut->totalAmount) 
			   && isset($data->userId) && !empty($data->userId) 
			   && isset($data->payOut->partyCode) && !empty($data->payOut->partyCode) 
			   && isset($data->payOut->partyType) && !empty($data->payOut->partyType)
			   && isset($data->countryId) && !empty($data->countryId) 
			   && isset($data->stateId) && !empty($data->stateId) 
			){

				error_log("inside all inputs are set correctly");
				$payOutType = $data->payOut->payOutType;
				$payOutAmount = $data->payOut->payOutAmount;
				$processingAmount = $data->payOut->processingAmount;
				$totalAmount = $data->payOut->totalAmount;
				$bankId = $data->payOut->bankId;
				$bankName = $data->payOut->bankName;
				$bankCode = $data->payOut->bankCode;
				$userId = $data->userId;
				$partyCode = $data->payOut->partyCode;
				$partyType = $data->payOut->partyType;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
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
                    			$validate_result = validateKey1($key1, $userId, $session_validity, 'E', $con);
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
                    			$payout_request_id = generate_seq_num(1900, $con);
                    			if( $payout_request_id > 0 )  {
                        			$insert_payout_request_query = "INSERT into comm_payout_request (comm_payout_request_id, party_type, party_code, payout_type, bank_id, comm_payout_amount, processing_amount, comm_total_amount, status, create_user, create_time) values ($payout_request_id, '$partyType', '$partyCode', '$payOutType', $bankId, $payOutAmount, $processingAmount, $totalAmount, 'P', $userId, now())";
					    	error_log("insert_payout_request_query = ".$insert_payout_request_query);
					    	$insert_payout_request_result = mysqli_query($con, $insert_payout_request_query);
					    	if($insert_payout_request_result) {
						    error_log("$insert_payout_request_query is success");
						    $response["result"] = "Success";
						    $response["message"] = "Your Payout Request #".$payout_request_id." is submitted";
						    $response["statusCode"] = 0;
						    $response["signature"] = $server_signature;
						    $response["payOutRequestId"] = $payout_request_id;
                        			}
						else {
						    $response["result"] = "Error";
						    $response["message"] = "Error in submitting your Payout Request";
						    $response["statusCode"] = "100";
						    $response["signature"] = $server_signature;
						    $response["payOutRequestId"] = 0;
						}
					}
					else {
						// DB failure
						$response["result"] = "Error";
						$response["statusCode"] = "200";
						$response["message"] = "Failure: Error in getting Payout Request no";
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
        	}
        	else if(isset($data -> operation) && $data -> operation == 'PAYOUT_PREP') {
			error_log("inside operation == PAYOUT_PREP method");
		   	if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
				&& isset($data->userId) && !empty($data->userId) 
				&& isset($data->payOut->partyCode) && !empty($data->payOut->partyCode) 
				&& isset($data->payOut->partyType) && !empty($data->payOut->partyType)
				&& isset($data->countryId) && !empty($data->countryId) 
				&& isset($data->stateId) && !empty($data->stateId) 
			){
		
				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->payOut->partyCode;
				$partyType = $data->payOut->partyType;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
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
					$validate_result = validateKey1($key1, $userId, $session_validity, 'E', $con);
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
		                    	$select_link_account_query = "select a.bank_master_id, a.name, a.cbn_short_code from bank_master a, party_bank_account b where b.bank_master_id = a.bank_master_id and b.active = 'Y' and b.status = 'A' and b.party_type = '$partyType' and b.party_code = '$partyCode' order by b.create_time";
					error_log("select_link_account_query = ".$select_link_account_query);
					$select_link_account_result = mysqli_query($con, $select_link_account_query);
					$response["linkAccounts"] = array();
					if($select_link_account_result) {
						while($select_link_account_row = mysqli_fetch_assoc($select_link_account_result)) {
							$bank = array();
							$bank['id'] = $select_link_account_row['bank_master_id'];
							$bank['name'] = $select_link_account_row['name'];
							$bank['code'] = $select_link_account_row['cbn_short_code'];
							array_push($response["linkAccounts"], $bank);
		                        	}
		                        	$comm_wallet_available_balance = check_party_comm_wallet_balance($partyCode, $partyType, $con);
		                        	$response["commWalletAmount"] = $comm_wallet_available_balance;
		                        	$response["processingAmountType"] = "A";
		                        	$response["processingAmount"] = "50.00";
		                        	$response["result"] = "Success";
						$response["statusCode"] = "0";
						$response["message"] = "Success: Your request is processed";
						$response["signature"] = $server_signature;
		                        }		                        
					else {
						// DB failure
						$response["result"] = "Error";
						$response["statusCode"] = "200";
						$response["message"] = "Failure: Error in getting payout prep request";
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
        	}
        	else if(isset($data -> operation) && $data -> operation == 'PAYOUT_FIND') {
			error_log("inside operation == PAYOUT_FIND method");
			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
				&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
				&& isset($data->partyType) && !empty($data->partyType) && isset($data->dateValue) && !empty($data->dateValue) 
				&& isset($data->status) && !empty($data->status) && isset($data->countryId) && !empty($data->countryId)
				&& isset($data->stateId) && !empty($data->stateId) 
			){

				error_log("inside all inputs are set correctly");
	            		$status = $data->status;
				$dateValue = $data->dateValue;
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
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
                    			$validate_result = validateKey1($key1, $userId, $session_validity, 'D', $con);
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
                    			if ( $status == "A") {
                        			$select_payout_request_query = "select a.comm_payout_request_id, a.party_type, a.party_code, a.payout_type, ifnull(a.bank_id,'') as bank_id, ifnull(b.name, '-') as bank_name, ifnull(b.cbn_short_code,'-') as bank_code, a.comm_payout_amount, a.processing_amount, a.comm_total_amount, a.status, a.create_time, ifnull(a.update_time, '-') as update_time from comm_payout_request a, bank_master b where a.bank_id = b.bank_master_id and a.party_code = '$partyCode' and a.party_type = '$partyType' and date(a.create_time) = '$dateValue' and a.create_user = $userId order by a.create_time desc";
					}else {
						$select_payout_request_query = "select a.comm_payout_request_id, a.party_type, a.party_code, a.payout_type, ifnull(a.bank_id,'') as bank_id, ifnull(b.name, '-') as bank_name, ifnull(b.cbn_short_code,'-') as bank_code, a.comm_payout_amount, a.processing_amount, a.comm_total_amount, a.status, a.create_time, ifnull(a.update_time, '-') as update_time from comm_payout_request a, bank_master b where a.bank_id = b.bank_master_id and a.party_code = '$partyCode' and a.party_type = '$partyType' and date(a.create_time) = '$dateValue' and a.create_user = $userId and status = '$status' order by a.create_time desc";
					}
					error_log("select_payout_request_query = ".$select_payout_request_query);
					$select_payout_request_query = mysqli_query($con, $select_payout_request_query);
                   			$response["payOuts"] = array();
					if ( $select_payout_request_query ) {
						while($select_payout_request_row = mysqli_fetch_assoc($select_payout_request_query)) {
							$payout = array();
							$payout['payoutRequestId'] = $select_payout_request_row['comm_payout_request_id'];
							$payout['partyCode'] = $select_payout_request_row['cms_type'];
							$payout['partyType'] = $select_payout_request_row['category'];
							$payout['payOutType'] = $select_payout_request_row['payout_type'];
							$payout['bankName'] = $select_payout_request_row['bank_name'];
							$payout['bankId'] = $select_payout_request_row['bank_id'];
							$payout['bankCode'] = $select_payout_request_row['bank_code'];
							$payout['payOutAmount'] = $select_payout_request_row['comm_payout_amount'];
							$payout['processingAmount'] = $select_payout_request_row['processing_amount'];
							$payout['totalAmount'] = $select_payout_request_row['comm_total_amount'];
							$payout['status'] = $select_payout_request_row['status'];
							$payout['createTime'] = $select_payout_request_row['create_time'];
							$payout['updateTime'] = $select_payout_request_row['update_time'];
							array_push($response["payOuts"], $payout);
						}
						$response["result"] = "Success";
						$response["message"] = "Your request is processed successfuly";
						$response["statusCode"] = 0;
						$response["signature"] = $server_signature;
					}
					else {
						$response["result"] = "Error";
						$response["message"] = "Error in find your payout details";
						$response["statusCode"] = "100";
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
    	error_log("payout ==> ".json_encode($response));
	echo json_encode($response);
?>