<?php
	include('../common/admin/configmysql.php');
	include("../common/admin/finsol_crypt.php");
	include ("get_prime.php");	
	require_once ("AesCipher.php");
    require_once("db_connect.php");
    include ("functions.php");
	error_log("inside pcposapi/link_account.php");
	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		error_log("inside post request method");
        $data = json_decode(file_get_contents("php://input"));
        error_log("link_account <== ".json_encode($data));

		if(isset($data -> operation) && $data -> operation == 'LINK_ACCOUNT_INSERT') {
			error_log("inside operation == LINK_ACCOUNT_INSERT method");
            if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
                   && isset($data->linkAccount->bankId) && !empty($data->linkAccount->bankId) 
                   && isset($data->linkAccount->accountNo) && !empty($data->linkAccount->accountNo) 
                   && isset($data->linkAccount->accountName) && !empty($data->linkAccount->accountName) 
                   && isset($data->linkAccount->bankAddress) && !empty($data->linkAccount->bankAddress) 
                   && isset($data->linkAccount->bankCity) && !empty($data->linkAccount->bankCity) 
                   && isset($data->userId) && !empty($data->userId) 
                   && isset($data->linkAccount->partyCode) && !empty($data->linkAccount->partyCode) 
                   && isset($data->linkAccount->partyType) && !empty($data->linkAccount->partyType)
                   && isset($data->countryId) && !empty($data->countryId) 
                   && isset($data->stateId) && !empty($data->stateId) 
			){

				error_log("inside all inputs are set correctly");
				$bankId = $data->linkAccount->bankId;
                $accountNo = $data->linkAccount->accountNo;
                $accountName = $data->linkAccount->accountName;
                $bankAddress = $data->linkAccount->bankAddress;
                $bankCity = $data->linkAccount->bankCity;
                $userId = $data->userId;
                $partyCode = $data->linkAccount->partyCode;
                $partyType = $data->linkAccount->partyType;
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
                    	$validate_result = validateKey1($key1, $userId, $session_validity, 'O', $con);
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
                    $party_bank_account_id = generate_seq_num(1700, $con);
                    if( $party_bank_account_id > 0 )  {
                        $accountName = mysqli_real_escape_string($con, $accountName);
                        $bankAddress = mysqli_real_escape_string($con, $bankAddress);
                        $bankCity = mysqli_real_escape_string($con, $bankCity);
                        
                        $insert_party_bank_account_query = "INSERT into party_bank_account (party_bank_account_id, party_type, party_code, bank_master_id, account_no, account_name, bank_address, bank_branch, active, status, create_user, create_time) values ($party_bank_account_id, '$partyType', '$partyCode', $bankId, '$accountNo', '$accountName', '$bankAddress', '$bankCity', 'Y', 'E', $userId, now())";
					    error_log("insert_party_bank_account_query = ".$insert_party_bank_account_query);
					    $insert_party_bank_account_result = mysqli_query($con, $insert_party_bank_account_query);
					    if($insert_party_bank_account_result) {
                            error_log("insert_cms_query is success");
                            $response["result"] = "Success";
                            $response["message"] = "Your Link Account submission #".$party_bank_account_id." is accepted";
                            $response["statusCode"] = 0;
                            $response["signature"] = $server_signature;
                            $response["linkAccountId"] = $party_bank_account_id;
                        }
                        else {
                            $response["result"] = "Error";
                            $response["message"] = "Error in submitting your Link Account request";
                            $response["statusCode"] = "100";
                            $response["signature"] = $server_signature;
                            $response["linkAccountId"] = 0;
                        }
                    }
					else {
						// DB failure
						$response["result"] = "Error";
						$response["statusCode"] = "200";
                        $response["message"] = "Failure: Error in getting Link Account submission no";
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
        else if(isset($data -> operation) && $data -> operation == 'LINK_ACCOUNT_LIST') {
			error_log("inside operation == LINK_ACCOUNT_LIST method");
            if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
                   && isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
                   && isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
                   && isset($data->stateId) && !empty($data->stateId) 
			){

				error_log("inside all inputs are set correctly");
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
                    $validate_result = validateKey1($key1, $userId, $session_validity, 'K', $con);
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
                    $select_link_account_query = "select a.party_bank_account_id, a.party_code, a.party_type, a.bank_master_id, b.name as bank_name, a.account_no, a.account_name, a.bank_address, a.bank_branch as bank_city, a.active, a.status, a.create_time, a.update_time from party_bank_account a, bank_master b where a.bank_master_id = b.bank_master_id and a.party_code = '$partyCode' and a.party_type = '$partyType' order by create_time desc";
                    error_log("select_link_account_query = ".$select_link_account_query);
                   	$select_link_account_result = mysqli_query($con, $select_link_account_query);
                   	$response["linkAccounts"] = array();
					if ( $select_link_account_result ) {
						while($select_link_account_row = mysqli_fetch_assoc($select_link_account_result)) {
                            $linkAccount = array();
                            $linkAccount['linkAccountId'] = $select_link_account_row['party_bank_account_id'];
                            $linkAccount['bankId'] = $select_link_account_row['bank_master_id'];
                            $linkAccount['partyCode'] = $select_link_account_row['party_code'];
                            $linkAccount['bankName'] = $select_link_account_row['bank_name'];
                            $linkAccount['accountNo'] = $select_link_account_row['account_no'];
                            $linkAccount['accountName'] = $select_link_account_row['account_name'];
                            $linkAccount['bankAddress'] = $select_link_account_row['bank_address'];
                            $linkAccount['bankCity'] = $select_link_account_row['bank_city'];
                            $linkAccount['active'] = $select_link_account_row['active'];
                            $linkAccount['status'] = $select_link_account_row['status'];
                            $linkAccount['createTime'] = $select_link_account_row['create_time'];
                            array_push($response["linkAccounts"], $linkAccount);
                        }
                        $response["result"] = "Success";
                        $response["message"] = "Your request is processed successfuly";
                        $response["statusCode"] = 0;
                        $response["signature"] = $server_signature;
					}
                   else {
                        $response["result"] = "Error";
                        $response["message"] = "Error in find your link account details";
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
    error_log("link_account ==> ".json_encode($response));
	echo json_encode($response);
?>