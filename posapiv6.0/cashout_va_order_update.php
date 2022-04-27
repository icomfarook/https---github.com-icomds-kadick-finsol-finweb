<?php
    include('../common/admin/configmysql.php');
    include ("get_prime.php");	
    include ("functions.php");
	require_once ("AesCipher.php");
	error_log("inside posapi/cashout_va_order_update.php");

	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;
	$journal_entry_error = "N";

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		error_log("inside post request method");
		$data = json_decode(file_get_contents("php://input"));
		error_log("cashout_va_order_update <== ".json_encode($data));	

		if ( isset($data -> requestRef ) && !empty($data -> requestRef) &&  isset($data -> requestType ) && !empty($data -> requestType) 
    		&& isset($data -> requester ) && !empty($data -> requester) && isset($data -> mockMode ) && !empty($data -> mockMode) 
    		&& isset($data -> amount ) && !empty($data -> amount ) && isset($data -> status ) && !empty($data -> status )
    		&& isset($data -> provider ) && !empty($data -> provider) && isset($data -> provider ) && !empty($data -> provider) 
    		&& isset($data -> customerRef ) && !empty($data -> customerRef) && isset($data -> customerEmail ) && !empty($data -> customerEmail) 
            && isset($data -> transactionRef ) && !empty($data -> transactionRef) && isset($data -> customerSurName ) && !empty($data -> customerSurName)
            && isset($data -> transactionDesc ) && !empty($data -> transactionDesc) && isset($data -> transactionType ) && !empty($data -> transactionType) 
			&& isset($data -> customerFirstName ) && !empty($data -> customerFirstName) && isset($data -> customerMobile ) && !empty($data -> customerMobile)
			&& isset($data -> signature ) && !empty($data -> signature) && isset($data -> key1 ) && !empty($data -> key1 )) {
			
			error_log("inside all inputs are set correctly");
			$signature = $data -> signature;
			$key1 = $data -> key1;
			
			$requestRef = $data -> requestRef;
			$requestType = $data -> requestType;
			$requester = $data -> requester;
			$mockMode = $data -> mockMode;
			$amount = $data -> amount;
			$status = $data -> status;
			$provider = $data -> provider;
			$customerRef = $data -> customerRef;
			$customerEmail = $data -> customerEmail;
			$transactionRef = $data -> transactionRef;
			$customerSurName = $data -> customerSurName;
			$transactionDesc = $data -> transactionDesc;
			$transactionType = $data -> transactionType;
			$customerFirstName = $data -> customerFirstName;
			$customerMobile = $data -> customerMobile;
			$dataObj = $data -> dataObj;
			$appCode = $data -> appCode;
		
            $info1 = "Provider: ".$provider.", Customer Ref: ".$customerRef.", Trans Ref: ".$transactionRef.", Trans Type: ".$transactionType;
            $info2 = $dataObj;
            error_log("provider = ".$provider.", Customer Ref = ".$customerRef.", Trans Ref = ".$transactionRef.", amount = ".$amount);
			
			date_default_timezone_set('Africa/Lagos');
			$nday = date('z')+1;
			$nyear = date('Y');
			$nth_day_prime = get_prime($nday);
			$nth_year_day_prime = get_prime($nday+$nyear);
			$local_signature = $nday + $nth_day_prime;
			$server_signature = $nth_year_day_prime + $nday + $nyear;
			
			$db_party_type;
			$db_party_code;
			$db_party_user_id;

			if ( $local_signature == $signature ){	

                if ("transaction_notification" == $requestType ) {
					$party_acc_check_query = "select party_type, party_code, party_user_id from party_virtual_account where account_number = '$customerRef'";
					error_log("party_acc_check_query = ".$party_acc_check_query);
					$party_acc_check_result = mysqli_query($con, $party_acc_check_query);
					if ($party_acc_check_result) {
						$party_acc_check_count = mysqli_num_rows($party_acc_check_result);
						if ( $party_acc_check_count > 0 ) {
							$party_acc_check_row = mysqli_fetch_array($party_acc_check_result);
							$db_party_type = $party_acc_check_row['party_type'];
							$db_party_code = $party_acc_check_row['party_code'];
							$db_party_user_id = $party_acc_check_row['party_user_id'];
						}else {
							//No Account found
						}
					}else {
						//Error case

					}
					$acc_trans_type = "PAYVA";
                 	$reference_no = $acc_trans_type."#".$requestRef;
                    $check_payment_result = checkForAlreadyProcessedFundWalletOrder($userId, $reference_no, $con);
                    error_log("check_payment_result = ".$check_payment_result);
                    if ( $check_payment_result == 0 ) {
                    	if ( "Successful" == $status ) {
                            $p_receipt_id = generate_seq_num(1100, $con);
                            $glComment = "Fund Wallet (VA) #".$requestRef;
                            $insertDepositQuery = "INSERT INTO payment_receipt(p_receipt_id, country_id, payment_date, party_code, party_type, payment_reference_no, payment_type, payment_amount, ams_charge, partner_charge, other_charge, stamp_charge, payment_approved_amount, payment_approved_date, payment_source, payment_status, create_user, create_time, comments, approver_comments, info1, info2) VALUES($orderNo, $countryId, now(), '$orderPartyCode', '$orderPartyType', '$reference_no', 'CP', $orderAmount, $serviceCharge, 0, $otherCharge, $stampCharge, $requestAmount, now(), 'F', 'A', $userId, now(), '$glComment', 'Card Payment using mPos', '$info1', '$info2')";
                            error_log("payment_receipt insert_query: ".$insertDepositQuery);
                            $insertDepositResult = mysqli_query($con, $insertDepositQuery);
                            if ( $insertDepositResult ) {
                            				error_log("Success in inserting Fund Wallet receipt for payment Id = ".$orderNo);
                                				$journal_entry_id = process_glentry($acc_trans_type, $orderNo, $orderPartyCode, $orderPartyType, $orderParentCode, $orderParentType, $glComment, $requestAmount, $userId, $con);
                                				error_log("select_journal_entry = ".$journal_entry_id);
                                				if($journal_entry_id > 0) {
                                    					$journal_entry_error = "N";
                                    					$get_acc_trans_type = getAcccTransType($acc_trans_type, $con);
                                    					error_log("get_acc_trans_type = ".$get_acc_trans_type);
                                    					if($get_acc_trans_type != "-1"){
                                        					$split = explode("|", $get_acc_trans_type);
                                        					$ac_factor = $split[0];
                                        					$cb_factor = $split[1];
                                        					$acc_trans_type_id = $split[2];
                                        					$update_wallet = walletupdateWithTransaction($acc_trans_type, $cb_factor, $orderPartyType, $orderPartyCode, $requestAmount, $con, $userId, $journal_entry_id);
                                        					if($update_wallet == 0) {
                                            						$gl_post_return_value = process_glpost($journal_entry_id, $con);
                                            						if ( $gl_post_return_value == 0 ) {
                                                						error_log("Success in Fund Wallet gl_post for: ".$journal_entry_id.", payment Order = ".$orderNo);
                                            						}else {
                                                						insertjournalerror($user_id, $journal_entry_id, $acc_trans_type, "AP", "W", "N", $requestAmount, $con);
                                                						error_log("Error in Fund Wallet gl_post for: ".$journal_entry_id.", orderNo = ".$orderNo);
                                            						}
                                            						$new_available_balance = check_party_available_balance($orderPartyType, $userId, $con);
                                            						error_log("new_available_balance for userId [".$userId."] = ".$new_available_balance);
                                            						$response["result"] = "Success";
                                            						$response["orderType"] = $orderType;
                                            						$response["orderNo"] = $orderNo;
                                            						$response["serverUpdate"] = "Y";
                                            						$response["signature"] = $server_signature;
                                            						$response["newAvailableBalance"] = $new_available_balance;
                                            						$response["message"] = "Success in updating wallet for Fund Wallet order # ".$orderNo;
                                        					}else {
                                            						$response["result"] = "Failure";
                                            						$response["orderType"] = $orderType;
                                            						$response["orderNo"] = $orderNo;
                                            						$response["serverUpdate"] = "N";
                                            						$response["signature"] = $server_signature;
                                            						$response["newAvailableBalance"] = $new_available_balance;
                                            						$response["message"] = "Error in updating wallet for Fund Wallet order # ".$orderNo;
                                        					}
                                    					}else {
                                        					$response["result"] = "Failure";
                                        					$response["orderType"] = $orderType;
                                        					$response["orderNo"] = $orderNo;
                                        					$response["serverUpdate"] = "N";
                                        					$response["signature"] = $server_signature;
                                        					$response["newAvailableBalance"] = $new_available_balance;
                                        					$response["message"] = "Error in getting acc_trans_typet for Fund Wallet order # ".$orderNo;
                                    					}
                                				}
                                				else {
                                    					$response["result"] = "Failure";
                                    					$response["message"] = "Error in jounral_entry for Fund Wallet order # ".$orderNo;
                                				}
                            				}else {
                                				error_log("Error in inserting Fund Wallet receipt for payment Id = ".$orderNo);
                                				error_log("May be duplicate for orderNo = ".$orderNo.", error desc = ".mysqli_error($con));
                                				$response["result"] = "Failure";
                                				$response["message"] = "Error in inserting payments for Fund Wallet order # ".$orderNo;
                            				}
                        			}else {
                            				$response["result"] = "Failure";
                            				$response["message"] = "Error response code for Fund Wallet order # ".$orderNo;
                        			}
                    			}else {
                        			error_log("Skipping...Duplicate Fund Wallet for ".$reference_no." for user_id = ".$user_id);
                        			$response["message"] = "Error: Fund Wallet order skipped for Payment Reference # ".$reference_no;
                        			$response["result"] = "Failure";
                        			$response["signature"] = $server_signature;
                    			}
                		}else {
                    			$response["result"] = "Failure";
				    	$response["message"] = "Invalid Order Type";
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
    	error_log("cashout_va_order_update.php ==> ".json_encode($response));
	echo json_encode($response);

    function check_feature_value($userId, $country, $state, $partyCount, $product, $partner, $requestedAmount, $txtype, $con) {
		
        $res = -1;
        $query = "SELECT get_feature_value_new($country, $state, null, $product, $partner, $requestedAmount, '$txtype', $partyCount, null, null, $userId,-1) as res";
        error_log($query);
        $result =  mysqli_query($con, $query);
        if (!$result) {
            error_log("Error: checking_feature_value = %s\n".mysqli_error($con));
        }
        $row = mysqli_fetch_assoc($result); 
        $res = $row['res']; 		
        return $res;
    }

?>
