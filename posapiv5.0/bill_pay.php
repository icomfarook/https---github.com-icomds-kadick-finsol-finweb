<?php
	include('../common/admin/configmysql.php');
	include("../common/admin/finsol_crypt.php");
	include ("get_prime.php");	
	require_once ("AesCipher.php");
    	require_once("db_connect.php");
    	include ("functions.php");
	error_log("inside pcposapi/bill_pay.php");
	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		error_log("inside post request method");
        	$data = json_decode(file_get_contents("php://input"));
        	error_log("bill_pay <== ".json_encode($data));

		if(isset($data -> operation) && $data -> operation == 'BP_PRODUCT_LIST') {
			error_log("inside operation == BP_PRODUCT_LIST method");
			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
				&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
				&& isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
				&& isset($data->stateId) && !empty($data->stateId) && isset($data->billerId) && !empty($data->billerId) 
			){
				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$billerId = $data->billerId;
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
		                	$validate_result = validateKey1($key1, $userId, $session_validity, '3', $con);
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
		                	//$select_bp_product_query = "select bp_product_id, bp_product_name, bp_biller_id from kadick_bp_biller_product where active = 'Y' and bp_biller_id = $billerId";
		                	$select_bp_product_query = "select bp_product_id, bp_product_name, bp_biller_id from kadick_bp_biller_product where active = 'Y' and bp_biller_id = $billerId";
		                	error_log("select_bp_product_query = ".$select_bp_product_query);
		                	$select_bp_product_result = mysqli_query($con, $select_bp_product_query);
		                   	$response["bpProducts"] = array();
					if ( $select_bp_product_result ) {
						while($select_bp_product_row = mysqli_fetch_assoc($select_bp_product_result)) {
						    	$bpProduct = array();
						    	$bpProduct['productId'] = $select_bp_product_row['bp_product_id'];
						    	$bpProduct['productName'] = $select_bp_product_row['bp_product_name'];
						    	$bpProduct['billerId'] = $select_bp_product_row['bp_biller_id'];
						    	array_push($response["bpProducts"], $bpProduct);
						}
		                        	$response["result"] = "Success";
		                        	$response["message"] = "Your request is processed successfuly";
		                        	$response["statusCode"] = 0;
		                        	$response["signature"] = $server_signature;
					}
		                	else {
		                	       	$response["result"] = "Error";
		                	       	$response["message"] = "Error in finding your product list";
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
        	}
		else if(isset($data -> operation) && $data -> operation == 'BP_PAYANT_PRODUCT_LIST') {
			error_log("inside operation == BP_PAYANT_PRODUCT_LIST method");
			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
				&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
				&& isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
				&& isset($data->stateId) && !empty($data->stateId) && isset($data->billerId) && !empty($data->billerId) 
			){
				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$billerId = $data->billerId;
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
		                	$validate_result = validateKey1($key1, $userId, $session_validity, '3', $con);
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
		                	$select_bp_product_query = "select bundle_code, concat(amount, ' - ', name) as name, amount, bp_payant_service_category_id as bp_biller_id from bp_payant_service_product where active = 'Y' and bp_payant_service_category_id = $billerId order by name";
		                	error_log("select_bp_product_query = ".$select_bp_product_query);
		                	$select_bp_product_result = mysqli_query($con, $select_bp_product_query);
		                   	$response["bpProducts"] = array();
					if ( $select_bp_product_result ) {
						while($select_bp_product_row = mysqli_fetch_assoc($select_bp_product_result)) {
						    	$bpProduct = array();
						    	$bpProduct['productCode'] = $select_bp_product_row['bundle_code'];
						    	$bpProduct['productName'] = $select_bp_product_row['name'];
						    	$bpProduct['billerId'] = $select_bp_product_row['bp_biller_id'];
						    	$bpProduct['amount'] = $select_bp_product_row['amount'];
						    	array_push($response["bpProducts"], $bpProduct);
						}
		                        	$response["result"] = "Success";
		                        	$response["message"] = "Your request is processed successfuly";
		                        	$response["statusCode"] = 0;
		                        	$response["signature"] = $server_signature;
					}
		                	else {
		                	       	$response["result"] = "Error";
		                	       	$response["message"] = "Error in finding your product list";
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
        	}
		else if(isset($data -> operation) && $data -> operation == 'BP_OPAY_BETTING_PROVIDER_LIST') {
			error_log("inside operation == BP_OPAY_BETTING_PROVIDER_LIST method");
			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
				&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
				&& isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
				&& isset($data->stateId) && !empty($data->stateId) && isset($data->billerId) && !empty($data->billerId) 
			){
				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$billerId = $data->billerId;
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
		                	$validate_result = validateKey1($key1, $userId, $session_validity, '3', $con);
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
		                	$select_betting_provider_query = "select bp_opay_service_provider_id as id, bp_opay_service_provider_name as name, bp_opay_service_min_amount as min_amount, bp_opay_service_max_amount as max_amount from bp_opay_service_provider where active = 'Y' and bp_opay_service_id = $billerId order by bp_opay_service_provider_name";
		                	error_log("select_betting_provider_query = ".$select_betting_provider_query);
		                	$select_betting_provider_result = mysqli_query($con, $select_betting_provider_query);
		                   	$response["providers"] = array();
					if ( $select_betting_provider_result ) {
						while($select_betting_provider_row = mysqli_fetch_assoc($select_betting_provider_result)) {
						    	$provider = array();
						    	$provider['id'] = $select_betting_provider_row['id'];
						    	$provider['name'] = $select_betting_provider_row['name'];
						    	$provider['minimumAmount'] = $select_betting_provider_row['min_amount'];
						    	$provider['maximumAmount'] = $select_betting_provider_row['max_amount'];
						    	array_push($response["providers"], $provider);
						}
		                        	$response["result"] = "Success";
		                        	$response["message"] = "Your request is processed successfuly";
		                        	$response["statusCode"] = 0;
		                        	$response["signature"] = $server_signature;
					}
		                	else {
		                	       	$response["result"] = "Error";
		                	       	$response["message"] = "Error in finding your provider list";
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
        	}        	
        	else if(isset($data -> operation) && $data -> operation == 'BP_BILLER_LIST') {
			error_log("inside operation == BP_BILLER_LIST method");
            		if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
			  	&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
			   	&& isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
			   	&& isset($data->stateId) && !empty($data->stateId) && isset($data->billerGroupId) && !empty($data->billerGroupId) 
			){

				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$billerGroupId = $data->billerGroupId;
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
                    			$validate_result = validateKey1($key1, $userId, $session_validity, '3', $con);
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
                    			$select_bp_biller_query = "select bp_biller_id, bp_biller_name, bp_biller_group_id from kadick_bp_biller where active = 'Y' and bp_biller_group_id = $billerGroupId order by bp_biller_id";
                    			error_log("select_bp_biller_query = ".$select_bp_biller_query);
                   			$select_bp_biller_result = mysqli_query($con, $select_bp_biller_query);
                   			$response["bpBillers"] = array();
					if ( $select_bp_biller_result ) {
						while($select_bp_biller_row = mysqli_fetch_assoc($select_bp_biller_result)) {
					    	$bpBiller = array();
					    	$bpBiller['billerId'] = $select_bp_biller_row['bp_biller_id'];
					    	$bpBiller['billerName'] = $select_bp_biller_row['bp_biller_name'];
					    	$bpBiller['groupId'] = $select_bp_biller_row['bp_biller_group_id'];
					    	array_push($response["bpBillers"], $bpBiller);
					}
                        		$response["result"] = "Success";
                        		$response["message"] = "Your request is processed successfuly";
                        		$response["statusCode"] = 0;
                        		$response["signature"] = $server_signature;
					}
                		   	else {
                		        	$response["result"] = "Error";
                		        	$response["message"] = "Error in finding your biller list";
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
        	}
		else if(isset($data -> operation) && $data -> operation == 'BP_PAYANT_BILLER_LIST') {
			error_log("inside operation == BP_PAYANT_BILLER_LIST method");
            		if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
			  	&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
			   	&& isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
			   	&& isset($data->stateId) && !empty($data->stateId) && isset($data->billerGroupId) && !empty($data->billerGroupId) 
			){

				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$billerGroupId = $data->billerGroupId;
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
                    			$validate_result = validateKey1($key1, $userId, $session_validity, '3', $con);
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
                    			$select_bp_biller_query = "select bp_payant_service_category_id as bp_biller_id, bp_payant_service_category_name as bp_biller_name, bp_payant_service_id as bp_biller_group_id from bp_payant_service_category where active = 'Y' and bp_payant_service_id = $billerGroupId order by bp_payant_service_category_name";
                    			error_log("select_bp_biller_query = ".$select_bp_biller_query);
                   			$select_bp_biller_result = mysqli_query($con, $select_bp_biller_query);
                   			$response["bpBillers"] = array();
					if ( $select_bp_biller_result ) {
						while($select_bp_biller_row = mysqli_fetch_assoc($select_bp_biller_result)) {
					    	$bpBiller = array();
					    	$bpBiller['billerId'] = $select_bp_biller_row['bp_biller_id'];
					    	$bpBiller['billerName'] = $select_bp_biller_row['bp_biller_name'];
					    	$bpBiller['groupId'] = $select_bp_biller_row['bp_biller_group_id'];
					    	array_push($response["bpBillers"], $bpBiller);
					}
                        		$response["result"] = "Success";
                        		$response["message"] = "Your request is processed successfuly";
                        		$response["statusCode"] = 0;
                        		$response["signature"] = $server_signature;
					}
                		   	else {
                		        	$response["result"] = "Error";
                		        	$response["message"] = "Error in finding your biller list";
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
        	}        	
        	else if(isset($data -> operation) && $data -> operation == 'BP_CHARGE_OPERATION') {
			error_log("inside operation == BP_CHARGE_OPERATION method");
		    	if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
			  	&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
			   	&& isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
			   	&& isset($data->stateId) && !empty($data->stateId) && isset($data->billerGroupId) && !empty($data->billerGroupId) 
			   	&& isset($data->billerId) && !empty($data->billerId) && isset($data->bpProductId) && !empty($data->bpProductId) 
			   	&& isset($data->requestAmount) && !empty($data->requestAmount) 
			){
				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$parentCode = $data->parentCode;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$billerGroupId = $data->billerGroupId;
				$billerId = $data->billerId;
				$bpProductId = $data->bpProductId;
				$productId = $data->productId;
				$requestAmount = $data->requestAmount;
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
		                    	$validate_result = validateKey1($key1, $userId, $session_validity, '3', $con);
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
					$txtType = "E";
					$partnerId = 8;
					if ($parentCode == "") {
						$partyCount = 2;
					}else {
						$partyCount = 3;
					}
					$db_flexiRate = "N";
					$flexi_rate_query = "select state_flexi_rate_id from state_flexi_rate where state_id = $stateId and (service_feature_id is null or (service_feature_id = $productId)) and active = 'Y' and (start_date is null or (start_date is not null and date(start_date) <= current_date())) and (expiry_date is null or (expiry_date is not null and date(expiry_date) >= current_date())) order by state_flexi_rate_id limit 1";
					error_log("flexi_rate_query query = ".$flexi_rate_query);
					$flexi_rate_result = mysqli_query($con, $flexi_rate_query);
					if ($flexi_rate_result) {
						$flexi_rate_count = mysqli_num_rows($flexi_rate_result);
						if($flexi_rate_count > 0) {					
							$db_flexiRate = "Y";
						}
					}
															
					if ( "Y" == $flexiRate || "Y" == $db_flexiRate ) {
						$txtType = "F";
					}
					$get_feature_value_query = "SELECT get_feature_value_new($countryId, $stateId, null, $productId, $partnerId, $requestAmount, '$txtType', $partyCount, null, null, $userId, -1) as result";
					error_log("get_feature_value query = ".$get_feature_value_query);
					$get_feature_value_result = mysqli_query($con, $get_feature_value_query);
					if ($get_feature_value_result) {										
						$row = mysqli_fetch_assoc($get_feature_value_result); 
						$db_result = $row['result']; 
						error_log("db_result = ".$db_result);
						if ( substr( $db_result, 0, 1 ) === "0" ) {
							$response["chargeDetail"] = $row['result']; 
							$response["statusCode"] = "0";
							$response["signature"] = $server_signature;
							$response["message"] = "Bill Pay Service Charge responded successfuly";
							$response["result"] = "Success";
							$response["partnerId"] = $partnerId;
							$response["txtType"] = $txtType;
						}else {
							$response["chargeDetail"] = ""; 
							$response["statusCode"] = "5";
							$response["signature"] = $server_signature;
							$response["message"] = "Error in getting charge rate for Agents";
							$response["result"] = "Error";
							$response["partnerId"] = $partnerId;
							$response["txtType"] = $txtType;
						}
					}else {
						// DB failure
						$response["chargeDetail"] = ""; 
						$response["statusCode"] = "10";
						$response["result"] = "Error";
						$response["message"] = "Failure: Error in reading charges from DB";
						$response["partnerId"] = $partnerId;
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
		else if(isset($data -> operation) && $data -> operation == 'BP_PAYANT_CHARGE_OPERATION') {
			error_log("inside operation == BP_PAYANT_CHARGE_OPERATION method");
		    	if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
			  	&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
			   	&& isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
			   	&& isset($data->stateId) && !empty($data->stateId) && isset($data->billerGroupId) && !empty($data->billerGroupId) 
			   	&& isset($data->billerId) && !empty($data->billerId) && isset($data->partnerId) && !empty($data->partnerId) 
			   	&& isset($data->requestAmount) && !empty($data->requestAmount) && isset($data->bpProductId) && !empty($data->bpProductId)
			){
				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$parentCode = $data->parentCode;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$billerGroupId = $data->billerGroupId;
				$billerId = $data->billerId;
				$bpProductId = $data->bpProductId;
				$partnerId = $data->partnerId;
				$productId = $data->productId;
				$requestAmount = $data->requestAmount;
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
		                    	$validate_result = validateKey1($key1, $userId, $session_validity, '3', $con);
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
					$txtType = "E";
					if ($parentCode == "") {
						$partyCount = 2;
					}else {
						$partyCount = 3;
					}
					$db_flexiRate = "N";
					$flexi_rate_query = "select state_flexi_rate_id from state_flexi_rate where state_id = $stateId and (service_feature_id is null or (service_feature_id = $productId)) and active = 'Y' and (start_date is null or (start_date is not null and date(start_date) <= current_date())) and (expiry_date is null or (expiry_date is not null and date(expiry_date) >= current_date())) order by state_flexi_rate_id limit 1";
					error_log("flexi_rate_query query = ".$flexi_rate_query);
					$flexi_rate_result = mysqli_query($con, $flexi_rate_query);
					if ($flexi_rate_result) {
						$flexi_rate_count = mysqli_num_rows($flexi_rate_result);
						if($flexi_rate_count > 0) {					
							$db_flexiRate = "Y";
						}
					}
															
					if ( "Y" == $flexiRate || "Y" == $db_flexiRate ) {
						$txtType = "F";
					}
					$get_feature_value_query = "SELECT get_feature_value_new_skel($countryId, $stateId, null, $productId, $partnerId, $requestAmount, '$txtType', $partyCount, null, null, $userId, -1) as result";
					error_log("get_feature_value query = ".$get_feature_value_query);
					$get_feature_value_result = mysqli_query($con, $get_feature_value_query);
					if ($get_feature_value_result) {										
						$row = mysqli_fetch_assoc($get_feature_value_result); 
						$db_result = $row['result']; 
						error_log("db_result = ".$db_result);
						if ( substr( $db_result, 0, 1 ) === "0" ) {
							$response["chargeDetail"] = $row['result']; 
							$response["statusCode"] = "0";
							$response["signature"] = $server_signature;
							$response["message"] = "Bill Pay Service Charge responded successfuly";
							$response["result"] = "Success";
							$response["partnerId"] = $partnerId;
							$response["txtType"] = $txtType;
						}else {
							$response["chargeDetail"] = ""; 
							$response["statusCode"] = "5";
							$response["signature"] = $server_signature;
							$response["message"] = "Error in getting charge rate for Agents";
							$response["result"] = "Error";
							$response["partnerId"] = $partnerId;
							$response["txtType"] = $txtType;
						}
					}else {
						// DB failure
						$response["chargeDetail"] = ""; 
						$response["statusCode"] = "10";
						$response["result"] = "Error";
						$response["message"] = "Failure: Error in reading charges from DB";
						$response["partnerId"] = $partnerId;
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
		else if(isset($data -> operation) && $data -> operation == 'BP_OPAY_BETTING_CHARGE_OPERATION') {
			error_log("inside operation == BP_OPAY_BETTING_CHARGE_OPERATION method");
		    	if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
			  	&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
			   	&& isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
			   	&& isset($data->stateId) && !empty($data->stateId) && isset($data->productId) && !empty($data->productId) 
			   	&& isset($data->serviceId) && !empty($data->serviceId) && isset($data->partnerId) && !empty($data->partnerId) 
			   	&& isset($data->requestAmount) && !empty($data->requestAmount) && isset($data->providerName) && !empty($data->providerName)
			){
				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$parentCode = $data->parentCode;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$serviceId = $data->serviceId;
				$providerName = $data->providerName;
				$partnerId = $data->partnerId;
				$productId = $data->productId;
				$requestAmount = $data->requestAmount;
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
		                    	$validate_result = validateKey1($key1, $userId, $session_validity, '3', $con);
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
					$txtType = "E";
					if ($parentCode == "") {
						$partyCount = 2;
					}else {
						$partyCount = 3;
					}
					$db_flexiRate = "N";
					$flexi_rate_query = "select state_flexi_rate_id from state_flexi_rate where state_id = $stateId and (service_feature_id is null or (service_feature_id = $productId)) and active = 'Y' and (start_date is null or (start_date is not null and date(start_date) <= current_date())) and (expiry_date is null or (expiry_date is not null and date(expiry_date) >= current_date())) order by state_flexi_rate_id limit 1";
					error_log("flexi_rate_query query = ".$flexi_rate_query);
					$flexi_rate_result = mysqli_query($con, $flexi_rate_query);
					if ($flexi_rate_result) {
						$flexi_rate_count = mysqli_num_rows($flexi_rate_result);
						if($flexi_rate_count > 0) {					
							$db_flexiRate = "Y";
						}
					}
															
					if ( "Y" == $flexiRate || "Y" == $db_flexiRate ) {
						$txtType = "F";
					}
					$get_feature_value_query = "SELECT get_feature_value_new_skel($countryId, $stateId, null, $productId, $partnerId, $requestAmount, '$txtType', $partyCount, null, null, $userId, -1) as result";
					error_log("get_feature_value query = ".$get_feature_value_query);
					$get_feature_value_result = mysqli_query($con, $get_feature_value_query);
					if ($get_feature_value_result) {										
						$row = mysqli_fetch_assoc($get_feature_value_result); 
						$db_result = $row['result']; 
						error_log("db_result = ".$db_result);
						if ( substr( $db_result, 0, 1 ) === "0" ) {
							$response["chargeDetail"] = $row['result']; 
							$response["statusCode"] = "0";
							$response["signature"] = $server_signature;
							$response["message"] = "Bill Pay Service Charge responded successfuly";
							$response["result"] = "Success";
							$response["partnerId"] = $partnerId;
							$response["txtType"] = $txtType;
						}else {
							$response["chargeDetail"] = ""; 
							$response["statusCode"] = "5";
							$response["signature"] = $server_signature;
							$response["message"] = "Error in getting charge rate for Agents";
							$response["result"] = "Error";
							$response["partnerId"] = $partnerId;
							$response["txtType"] = $txtType;
						}
					}else {
						// DB failure
						$response["chargeDetail"] = ""; 
						$response["statusCode"] = "10";
						$response["result"] = "Error";
						$response["message"] = "Failure: Error in reading charges from DB";
						$response["partnerId"] = $partnerId;
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
 		else if(isset($data -> operation) && $data -> operation == 'BP_PAYANT_CHARGE_OLD_OPERATION') {
			error_log("inside operation == BP_PAYANT_CHARGE_OLD_OPERATION method");
		    	if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
			  	&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
			   	&& isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
			   	&& isset($data->stateId) && !empty($data->stateId) && isset($data->billerGroupId) && !empty($data->billerGroupId) 
			   	&& isset($data->billerId) && !empty($data->billerId) && isset($data->partnerId) && !empty($data->partnerId) 
			   	&& isset($data->requestAmount) && !empty($data->requestAmount) && isset($data->bpProductId) && !empty($data->bpProductId)
			){
				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$parentCode = $data->parentCode;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$billerGroupId = $data->billerGroupId;
				$billerId = $data->billerId;
				$bpProductId = $data->bpProductId;
				$partnerId = $data->partnerId;
				$productId = $data->productId;
				$requestAmount = $data->requestAmount;
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
		                    	$validate_result = validateKey1($key1, $userId, $session_validity, '3', $con);
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
					$txtType = "E";
					if ($parentCode == "") {
						$partyCount = 2;
					}else {
						$partyCount = 3;
					}
					$db_flexiRate = "N";
					$flexi_rate_query = "select state_flexi_rate_id from state_flexi_rate where state_id = $stateId and (service_feature_id is null or (service_feature_id = $productId)) and active = 'Y' and (start_date is null or (start_date is not null and date(start_date) <= current_date())) and (expiry_date is null or (expiry_date is not null and date(expiry_date) >= current_date())) order by state_flexi_rate_id limit 1";
					error_log("flexi_rate_query query = ".$flexi_rate_query);
					$flexi_rate_result = mysqli_query($con, $flexi_rate_query);
					if ($flexi_rate_result) {
						$flexi_rate_count = mysqli_num_rows($flexi_rate_result);
						if($flexi_rate_count > 0) {					
							$db_flexiRate = "Y";
						}
					}
															
					if ( "Y" == $flexiRate || "Y" == $db_flexiRate ) {
						$txtType = "F";
					}
					$get_feature_value_query = "SELECT get_feature_value_new($countryId, $stateId, null, $productId, $partnerId, $requestAmount, '$txtType', $partyCount, null, null, $userId, -1) as result";
					error_log("get_feature_value query = ".$get_feature_value_query);
					$get_feature_value_result = mysqli_query($con, $get_feature_value_query);
					if ($get_feature_value_result) {										
						$row = mysqli_fetch_assoc($get_feature_value_result); 
						$db_result = $row['result']; 
						error_log("db_result = ".$db_result);
						if ( substr( $db_result, 0, 1 ) === "0" ) {
							$response["chargeDetail"] = $row['result']; 
							$response["statusCode"] = "0";
							$response["signature"] = $server_signature;
							$response["message"] = "Bill Pay Service Charge responded successfuly";
							$response["result"] = "Success";
							$response["partnerId"] = $partnerId;
							$response["txtType"] = $txtType;
						}else {
							$response["chargeDetail"] = ""; 
							$response["statusCode"] = "5";
							$response["signature"] = $server_signature;
							$response["message"] = "Error in getting charge rate for Agents";
							$response["result"] = "Error";
							$response["partnerId"] = $partnerId;
							$response["txtType"] = $txtType;
						}
					}else {
						// DB failure
						$response["chargeDetail"] = ""; 
						$response["statusCode"] = "10";
						$response["result"] = "Error";
						$response["message"] = "Failure: Error in reading charges from DB";
						$response["partnerId"] = $partnerId;
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
	       	else if(isset($data -> operation) && $data -> operation == 'BP_FORM_VALIDATION') {
			error_log("inside operation == BP_FORM_VALIDATION method");
			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
				&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
				&& isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
				&& isset($data->stateId) && !empty($data->stateId) 
				&& isset($data->billerId) && !empty($data->billerId) && isset($data->productId) && !empty($data->productId) 
				&& isset($data->requestAmount) && !empty($data->requestAmount) && isset($data->totalAmount) && !empty($data->totalAmount) 
				//&& isset($data->agentCharge) && !empty($data->agentCharge) && isset($data->serviceCharge) && !empty($data->serviceCharge) 
				//&& isset($data->otherCharge) && !empty($data->otherCharge) && isset($data->stampCharge) && !empty($data->stampCharge) 
				&& isset($data->accountNo) && !empty($data->accountNo) && isset($data->accountName) && !empty($data->accountName)
				&& isset($data->email) && !empty($data->email) && isset($data->mobile) && !empty($data->mobile)
				&& isset($data->serviceFeatureCode) && !empty($data->serviceFeatureCode) && isset($data->partnerId) && !empty($data->partnerId)
			){
				ini_set('max_execution_time', 120);
				set_time_limit(120);
				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$parentCode = $data->parentCode;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$billerGroupId = $data->billerGroupId;
				$bpBillerId = $data->billerId;
				$bpProductId = $data->productId;
				$bpBillerName = $data->billerName;
				$bpProductName = $data->productName;
				$requestAmount = $data->requestAmount;
				$accountNo = $data->accountNo;
				$totalAmount = $data->totalAmount;
				$agentCharge = $data->agentCharge;
				$serviceCharge = $data->serviceCharge;
				$otherCharge = $data->otherCharge;
				$partnerCharge = $data->partnerCharge;
				$stampCharge = $data->stampCharge;
				$accountName = $data->accountName;
				$email = $data->email;
				$mobile = $data->mobile;
				$serviceFeatureCode = $data->serviceFeatureCode;
				$partnerId = $data->partnerId;
				$bankCode = $data->bankCode;
				$lgaId = ADMIN_LOCAL_GOVT_ID;
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
					$validate_result = validateKey1($key1, $userId, $session_validity, '4', $con);
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
					
					$agent_info_wallet_status = check_agent_info_wallet_status($partyType, $partyCode, $con);
					if ($agent_info_wallet_status == 0 ) {
						//Checking available_balance
						$available_balance = check_agent_available_balance($userId, $con);
						error_log("available_balance response for = ".$available_balance);
						if ( $available_balance >= 0 || $available_balance != "" || $available_balance != null ) {
							if ( floatval($totalAmount) <= floatval($available_balance) ) {
								$bp_trans_log_id = generate_seq_num(3100, $con);
								unset($data->key1);
								$request_message = json_encode($data);
								if ($bp_trans_log_id > 0)  {
									error_log("bp_trans_log_id = ".$bp_trans_log_id);
									$request_message = mysqli_real_escape_string($con, $request_message);
									$bp_trans_log_query = "INSERT INTO bp_trans_log (bp_trans_log_id, partner_id, party_type, party_code, service_feature_code, country_id, state_id, amount, request_message, message_send_time, create_user, create_time) VALUES ($bp_trans_log_id, $partnerId, '$partyType', '$partyCode', '$serviceFeatureCode', $countryId, $stateId, $totalAmount, left('Product Form Validate = $request_message', 1000), now(), $userId, now())";
									error_log("bp_trans_log_query = ". $bp_trans_log_query);
									$bp_trans_log_result = mysqli_query($con, $bp_trans_log_query);
									if ( $bp_trans_log_result ) {
										$bp_request_id = generate_seq_num(3200, $con);
									   	if ( $bp_request_id > 0)  {
											$accountName = mysqli_real_escape_string($con, $accountName);
											$bp_request_query = "INSERT INTO bp_request (bp_request_id, bp_trans_log_id1, service_feature_code, bp_biller_id, bp_product_id, country_id, state_id, local_govt_id, request_amount, service_charge, partner_charge, other_charge, total_amount, account_no, account_name, mobile_no, user_id, status, create_time) VALUES ($bp_request_id, $bp_trans_log_id, '$serviceFeatureCode', $bpBillerId, $bpProductId, $countryId, $stateId, $lgaId, $requestAmount, $serviceCharge, $partnerCharge, $otherCharge, $totalAmount, '$accountNo', '$accountName', '$mobile', $userId, 'I', now())";
											error_log("bp_request_query = ".$bp_request_query);
											$bp_request_result = mysqli_query($con, $bp_request_query);
											if( $bp_request_result ) {
												$data = array();
												$data['billerId'] = $bpBillerId;
												$data['billerName'] = $bpBillerName;
												$data['productId'] = $bpProductId;
												$data['productName'] = $bpProductName;
												$data['accountNo'] = $accountNo;
												$data['amount'] = $requestAmount;
												$data['mobile'] = $mobile;
												$data['email'] = $email;
												$data['name'] = $accountName;
												$data['countryId'] = $countryId;
												$data['stateId'] = $stateId;
												$data['userId'] = $userId;
												$data['signature'] = $local_signature;
												$data['key1'] = $key1;
												$data['localGovtId'] = ADMIN_LOCAL_GOVT_ID;

												$url = BPAPI_SERVER_PRODUCT_FORM_VALIDATE_URL;
												//$sendreq = sendRequest($data, $url);
												$tsec = time();
												$raw_data1 = BPAPI_SERVER_APP_PASSWORD."|".BPAPI_SERVER_APP_USERNAME."|".$tsec;
												error_log("raw_data1 = ".$raw_data1);
												$key1 = base64_encode($raw_data1);
												error_log("key1 = ".$key1);
												error_log("before calling post");
												error_log("url = ".$url);
												$data['key1'] = $key1;
												$data['signature'] = $local_signature;
												error_log("request sent ==> ".json_encode($data));
												$ch = curl_init($url);
												curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
												curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
												curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
												curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, BILLPAY_CURL_CONNECTION_TIMEOUT);
												curl_setopt($ch, CURLOPT_TIMEOUT, BILLPAY_CURL_TIMEOUT);
												$curl_response = curl_exec($ch);
												$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
												$curl_error = curl_errno($ch);
												curl_close($ch);
												if ( $curl_error == 0 ) {
													error_log("curl_error == 0 ");
													error_log("response received = ".$curl_response);
													error_log("code = ".$httpcode);
													if ( $httpcode == 200 ) {
														error_log("inside httpcode == 200");
														$api_response = json_decode($curl_response, true);
														$statusCode = $api_response['responseCode'];
														$responseDescription = $api_response['responseDescription'];
														error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
														error_log("response_received <=== ".$curl_response);
														$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
														error_log("update_query = ".$update_query);
														$update_query_result = mysqli_query($con, $update_query);

														if($statusCode === 0) {
															error_log("inside statusCode == 0");
															$update_query = "UPDATE bp_request SET status = 'V', update_time = now(), bp_account_name = '".$api_response['bpAccountName']."', bp_account_no = '".$api_response['bpAccountNo']."', bp_bank_code = '".$api_response['bpBankCode']."', payment_fee = ".$api_response['paymentFee'].", bp_transaction_id = '".$api_response['transactionId']."' WHERE bp_request_id  = $bp_request_id ";
															error_log("update_query = ".$update_query);
															$update_query_result = mysqli_query($con, $update_query);

															$response["statusCode"] = "0";
															$response["result"] = "Success";
															$response["message"] = $responseDescription;
															$response["partnerId"] = $partnerId;
															$response["signature"] = $server_signature;
															$response["transactionId"] = $bp_request_id;
															$response["bpAccountNo"] = $api_response['bpAccountNo'];
															$response["bpAccountName"] = $api_response['bpAccountName'];
															$response["bpBankCode"] = $api_response['bpBankCode'];
															$response["bpTransactionId"] = $api_response['transactionId'];
															$response["bpPaymentFee"] = $api_response['paymentFee'];
															$response["bpTotalAmount"] = $api_response['totalAmount'];
															$response["bpAmount"] = $api_response['amount'];
															$response["productFormDetail"] = $api_response['productFormDetail'];
															$response["previousRecordDetail"] = $api_response['previousRecordDetail'];
															$response["productFormTitle"] = $api_response['productFormTitle'];
															$response["accountNo"] = $accountNo;
															$response["accountName"] = $accountName;
														}
														else {
															error_log("inside statusCode != 0");
															if ( $statusCode == '') {
																$statusCode = 50;
															}
															$approver_comments = "PV: ".$statusCode." - ".$responseDescription." @ ".$api_response['processingStartTime'];
															$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $bp_request_id ";
															error_log("update_query = ".$update_query);
															$update_query_result = mysqli_query($con, $update_query);

															$response["statusCode"] = $statusCode;
															$response["result"] = "Error";
															$response["message"] = $responseDescription;
															$response["partnerId"] = $partnerId;
															$response["signature"] = $server_signature;
														}
													}else {
														error_log("inside httpcode != 200");
														$statusCode = $httpcode;
														$responseDescription = "HTTP Protocol Error";
														error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
														$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
														error_log("update_query = ".$update_query);
														$update_query_result = mysqli_query($con, $update_query);

														$approver_comments = "NE: ".$statusCode." - ".$responseDescription;
														error_log("update_query = ".$update_query);
														$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $bp_request_id ";
														$update_query_result = mysqli_query($con, $update_query);

														$response["statusCode"] = $statusCode;
														$response["result"] = "Error";
														$response["message"] = "Error in connection to BillPay API Server";
														$response["partnerId"] = $partnerId;
														$response["signature"] = $server_signature;
													}
												}else {
													error_log("curl_error != 0 ");
													$statusCode = $curl_error;
													$responseDescription = "CURL Execution Error";
													$approver_comments = "NE: ".$statusCode." - ".$responseDescription;
													error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
													$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
													error_log("update_query = ".$update_query);
													$update_query_result = mysqli_query($con, $update_query);

													$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $bp_request_id ";
													error_log("update_query = ".$update_query);
													$update_query_result = mysqli_query($con, $update_query);

													$response["statusCode"] = $statusCode;
													$response["result"] = "Error";
													$response["message"] = "Error in communication protocol";
													$response["partnerId"] = $partnerId;
													$response["signature"] = $server_signature;
												}
											}
											else {
												//Error in Generating Bp Request Id Result
												$response["statusCode"] = "205";
												$response["result"] = "Error";
												$response["message"] = "DB Error in BP Request Result";
												$response["partnerId"] = $partnerId;
												$response["signature"] = $server_signature;
											}
										}
										else {
											//Error in Generating BP Request Id
											$response["statusCode"] = "210";
											$response["result"] = "Error";
											$response["message"] = "DB Error in BP Request";
											$response["partnerId"] = $partnerId;
											$response["signature"] = $server_signature;
										}
									}
									else {
										//Error in Generating Transaction Log Result
										$response["statusCode"] = "220";
										$response["result"] = "Error";
										$response["message"] = "DB Error in Trans Log Result";
										$response["partnerId"] = $partnerId;
										$response["signature"] = $server_signature;
									}
								}
								else {
									//Error in Generating Transaction Log
									$response["statusCode"] = "230";
									$response["result"] = "Error";
									$response["message"] = "DB Error in Trans Log";
									$response["partnerId"] = $partnerId;
									$response["signature"] = $server_signature;
								}
							}
							else {
								// Insufficient Agent Available Balance
								$response["statusCode"] = "240";
								$response["result"] = "Error";
								$response["message"] = "Insufficient Agent Available Balance";
								$response["partnerId"] = $partnerId;
								$response["signature"] = $server_signature;
							}
						}
						else {
							//Agent Available Balance is not available
							$response["statusCode"] = "250";
							$response["result"] = "Error";
							$response["message"] = "Agent Available Balance is not available";
							$response["partnerId"] = $partnerId;
							$response["signature"] = $server_signature;
						}
					}
					else {
						// Error in accessing Agent Info & Wallet details.
						$resp_message = "";
						if ( $agent_info_wallet_status == 1 ) {
							$resp_message = "Agent status is not active";
						}else if ( $agent_info_wallet_status == 2 ) {
							$resp_message = "Agent is blocked";
						}else if ( $agent_info_wallet_status == 3 ) {
							$resp_message = "Agent Wallet is not active";
						}else if ( $agent_info_wallet_status == 4 ) {
							$resp_message = "Agent Wallet is blocked";
						}else {
							$resp_message = "Error in accessing Agent Info & Wallet details. Contact Kadick Admin";
						}
						$response["statusCode"] = "260";
						$response["result"] = "Error";
						$response["message"] = $resp_message;
						$response["partnerId"] = $partnerId;
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
        	else if(isset($data -> operation) && $data -> operation == 'BP_PAYANT_FORM_VALIDATION') {
			error_log("inside operation == BP_PAYANT_FORM_VALIDATION method");
			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
				&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
				&& isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
				&& isset($data->stateId) && !empty($data->stateId) 
				&& isset($data->billerId) && !empty($data->billerId) && isset($data->productId) && !empty($data->productId) 
				&& isset($data->requestAmount) && !empty($data->requestAmount) && isset($data->totalAmount) && !empty($data->totalAmount) 
				//&& isset($data->agentCharge) && !empty($data->agentCharge) && isset($data->serviceCharge) && !empty($data->serviceCharge) 
				//&& isset($data->otherCharge) && !empty($data->otherCharge) && isset($data->stampCharge) && !empty($data->stampCharge) 
				&& isset($data->accountNo) && !empty($data->accountNo) && isset($data->accountName) && !empty($data->accountName)
				&& isset($data->email) && !empty($data->email) && isset($data->mobile) && !empty($data->mobile)
				&& isset($data->serviceFeatureCode) && !empty($data->serviceFeatureCode) && isset($data->partnerId) && !empty($data->partnerId)
			){
				ini_set('max_execution_time', 120);
				set_time_limit(120);
				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$parentCode = $data->parentCode;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$billerGroupId = $data->billerGroupId;
				$bpBillerId = $data->billerId;
				$bpProductId = $data->productId;
				$bpBillerName = $data->billerName;
				$bpProductName = $data->productName;
				$requestAmount = $data->requestAmount;
				$accountNo = $data->accountNo;
				$totalAmount = $data->totalAmount;
				$agentCharge = $data->agentCharge;
				$serviceCharge = $data->serviceCharge;
				$otherCharge = $data->otherCharge;
				$partnerCharge = $data->partnerCharge;
				$stampCharge = $data->stampCharge;
				$accountName = $data->accountName;
				$email = $data->email;
				$mobile = $data->mobile;
				$serviceFeatureCode = $data->serviceFeatureCode;
				$partnerId = $data->partnerId;
				$bankCode = $data->bankCode;
				$lgaId = ADMIN_LOCAL_GOVT_ID;
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
					$validate_result = validateKey1($key1, $userId, $session_validity, '4', $con);
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
					
					$agent_info_wallet_status = check_agent_info_wallet_status($partyType, $partyCode, $con);
					if ($agent_info_wallet_status == 0 ) {
						//Checking available_balance
						$available_balance = check_agent_available_balance($userId, $con);
						error_log("available_balance response for = ".$available_balance);
						if ( $available_balance >= 0 || $available_balance != "" || $available_balance != null ) {
							if ( floatval($totalAmount) <= floatval($available_balance) ) {
								$bp_trans_log_id = generate_seq_num(3100, $con);
								unset($data->key1);
								$request_message = json_encode($data);
								if ($bp_trans_log_id > 0)  {
									error_log("bp_trans_log_id = ".$bp_trans_log_id);
									$request_message = mysqli_real_escape_string($con, $request_message);
									$bp_trans_log_query = "INSERT INTO bp_trans_log (bp_trans_log_id, partner_id, party_type, party_code, service_feature_code, country_id, state_id, amount, request_message, message_send_time, create_user, create_time) VALUES ($bp_trans_log_id, $partnerId, '$partyType', '$partyCode', '$serviceFeatureCode', $countryId, $stateId, $totalAmount, left('PayAnt Verify Customer = $request_message', 1000), now(), $userId, now())";
									error_log("bp_trans_log_query = ". $bp_trans_log_query);
									$bp_trans_log_result = mysqli_query($con, $bp_trans_log_query);
									if ( $bp_trans_log_result ) {
										$bp_request_id = generate_seq_num(3200, $con);
									   	if ( $bp_request_id > 0)  {
											$accountName = mysqli_real_escape_string($con, $accountName);
											$bp_request_query = "INSERT INTO bp_request (bp_request_id, bp_trans_log_id1, service_feature_code, bp_biller_id, bp_product_id, country_id, state_id, local_govt_id, request_amount, service_charge, partner_charge, other_charge, total_amount, account_no, account_name, mobile_no, user_id, status, create_time) VALUES ($bp_request_id, $bp_trans_log_id, '$serviceFeatureCode', $bpBillerId, $bpProductId, $countryId, $stateId, $lgaId, $requestAmount, $serviceCharge, $partnerCharge, $otherCharge, $totalAmount, '$accountNo', '$accountName', '$mobile', $userId, 'I', now())";
											error_log("bp_request_query = ".$bp_request_query);
											$bp_request_result = mysqli_query($con, $bp_request_query);
											if( $bp_request_result ) {
												$data = array();
												$data['account'] = $accountNo;
												$data['categoryId'] = $bpProductId;
												$data['countryId'] = $countryId;
												$data['stateId'] = $stateId;
												$data['userId'] = $userId;
												$data['signature'] = $local_signature;
												$data['key1'] = $key1;
												$data['amount'] = $requestAmount;
												$data['localGovtId'] = ADMIN_LOCAL_GOVT_ID;

												$url = BPAPI_PAYANT_SERVER_VERIFY_CUSTOMER_URL;
												//$sendreq = sendRequest($data, $url);
												$tsec = time();
												$raw_data1 = BPAPI_SERVER_APP_PASSWORD."|".BPAPI_SERVER_APP_USERNAME."|".$tsec;
												error_log("raw_data1 = ".$raw_data1);
												$key1 = base64_encode($raw_data1);
												error_log("key1 = ".$key1);
												error_log("before calling post");
												error_log("url = ".$url);
												$data['key1'] = $key1;
												$data['signature'] = $local_signature;
												error_log("request sent ==> ".json_encode($data));
												$ch = curl_init($url);
												curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
												curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
												curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
												curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, PAYANT_CURL_CONNECTION_TIMEOUT);
												curl_setopt($ch, CURLOPT_TIMEOUT, PAYANT_CURL_TIMEOUT);
												$curl_response = curl_exec($ch);
												$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
												$curl_error = curl_errno($ch);
												curl_close($ch);
												if ( $curl_error == 0 ) {
													error_log("curl_error == 0 ");
													error_log("response received = ".$curl_response);
													error_log("code = ".$httpcode);
													if ( $httpcode == 200 ) {
														error_log("inside httpcode == 200");
														$api_response = json_decode($curl_response, true);
														$statusCode = $api_response['responseCode'];
														$responseDescription = $api_response['responseDescription'];
														error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
														error_log("response_received <=== ".$curl_response);
														$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
														error_log("update_query = ".$update_query);
														$update_query_result = mysqli_query($con, $update_query);

														if($statusCode === 0) {
															error_log("inside statusCode == 0");
															$update_query = "UPDATE bp_request SET status = 'V', bp_account_name = left('".$api_response['name']."', 70), update_time = now() WHERE bp_request_id = $bp_request_id ";
															error_log("update_query = ".$update_query);
															$update_query_result = mysqli_query($con, $update_query);

															$response["statusCode"] = "0";
															$response["result"] = "Success";
															$response["message"] = $responseDescription;
															$response["partnerId"] = $partnerId;
															$response["signature"] = $server_signature;
															$response["transactionId"] = $bp_request_id;
															$response["bpAccountName"] = $api_response['name'];
															$response["totalAmount"] = $api_response['amount'];
															$response["bpPaymentFee"] = $api_response['paymentFee'];
															$response["bpTotalAmount"] = $api_response['totalAmount'];
															$response["productFormDetail"] = $api_response['status'];
															$response["previousRecordDetail"] = $api_response['data'];
															$response["productFormTitle"] = $api_response['message'];
															$response["bpMinPaymentAmount"] = $api_response['minPayment'];
															$response["bpMaxPaymentAmount"] = $api_response['maxPayment'];
															$response["accountNo"] = $accountNo;
																										}
														else {
															error_log("inside statusCode != 0");
															if ( $statusCode == '') {
																$statusCode = 50;
															}
															$approver_comments = "PV: ".$statusCode." - ".$responseDescription." @ ".$api_response['processingStartTime'];
															$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $bp_request_id ";
															error_log("update_query = ".$update_query);
															$update_query_result = mysqli_query($con, $update_query);

															$response["statusCode"] = $statusCode;
															$response["result"] = "Error";
															$response["message"] = $responseDescription;
															$response["partnerId"] = $partnerId;
															$response["signature"] = $server_signature;
														}
													}else {
														error_log("inside httpcode != 200");
														$statusCode = $httpcode;
														$responseDescription = "HTTP Protocol Error";
														error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
														$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
														error_log("update_query = ".$update_query);
														$update_query_result = mysqli_query($con, $update_query);

														$approver_comments = "PV: ".$statusCode." - ".$responseDescription;
														error_log("update_query = ".$update_query);
														$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $bp_request_id ";
														$update_query_result = mysqli_query($con, $update_query);

														$response["statusCode"] = $statusCode;
														$response["result"] = "Error";
														$response["message"] = "Error in connection to BillPay API Server";
														$response["partnerId"] = $partnerId;
														$response["signature"] = $server_signature;
													}
												}else {
													error_log("curl_error != 0 ");
													$statusCode = $curl_error;
													$responseDescription = "CURL Execution Error";
													$approver_comments = "PV: ".$statusCode." - ".$responseDescription;
													error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
													$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
													error_log("update_query = ".$update_query);
													$update_query_result = mysqli_query($con, $update_query);

													$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $bp_request_id ";
													error_log("update_query = ".$update_query);
													$update_query_result = mysqli_query($con, $update_query);

													$response["statusCode"] = $statusCode;
													$response["result"] = "Error";
													$response["message"] = "Error in communication protocol";
													$response["partnerId"] = $partnerId;
													$response["signature"] = $server_signature;
												}
											}
											else {
												//Error in Generating Bp Request Id Result
												$response["statusCode"] = "205";
												$response["result"] = "Error";
												$response["message"] = "DB Error in BP Request Result";
												$response["partnerId"] = $partnerId;
												$response["signature"] = $server_signature;
											}
										}
										else {
											//Error in Generating BP Request Id
											$response["statusCode"] = "210";
											$response["result"] = "Error";
											$response["message"] = "DB Error in BP Request";
											$response["partnerId"] = $partnerId;
											$response["signature"] = $server_signature;
										}
									}
									else {
										//Error in Generating Transaction Log Result
										$response["statusCode"] = "220";
										$response["result"] = "Error";
										$response["message"] = "DB Error in Trans Log Result";
										$response["partnerId"] = $partnerId;
										$response["signature"] = $server_signature;
									}
								}
								else {
									//Error in Generating Transaction Log
									$response["statusCode"] = "230";
									$response["result"] = "Error";
									$response["message"] = "DB Error in Trans Log";
									$response["partnerId"] = $partnerId;
									$response["signature"] = $server_signature;
								}
							}
							else {
								// Insufficient Agent Available Balance
								$response["statusCode"] = "240";
								$response["result"] = "Error";
								$response["message"] = "Insufficient Agent Available Balance";
								$response["partnerId"] = $partnerId;
								$response["signature"] = $server_signature;
							}
						}
						else {
							//Agent Available Balance is not available
							$response["statusCode"] = "250";
							$response["result"] = "Error";
							$response["message"] = "Agent Available Balance is not available";
							$response["partnerId"] = $partnerId;
							$response["signature"] = $server_signature;
						}
					}
					else {
						// Error in accessing Agent Info & Wallet details.
						$resp_message = "";
						if ( $agent_info_wallet_status == 1 ) {
							$resp_message = "Agent status is not active";
						}else if ( $agent_info_wallet_status == 2 ) {
							$resp_message = "Agent is blocked";
						}else if ( $agent_info_wallet_status == 3 ) {
							$resp_message = "Agent Wallet is not active";
						}else if ( $agent_info_wallet_status == 4 ) {
							$resp_message = "Agent Wallet is blocked";
						}else {
							$resp_message = "Error in accessing Agent Info & Wallet details. Contact Kadick Admin";
						}
						$response["statusCode"] = "260";
						$response["result"] = "Error";
						$response["message"] = $resp_message;
						$response["partnerId"] = $partnerId;
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
        	else if(isset($data -> operation) && $data -> operation == 'BP_OPAY_BETTING_FORM_VALIDATION') {
			error_log("inside operation == BP_OPAY_BETTING_FORM_VALIDATION method");
			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
				&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
				&& isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
				&& isset($data->stateId) && !empty($data->stateId) && isset($data->billerId) && !empty($data->billerId)  
				&& isset($data->requestAmount) && !empty($data->requestAmount) && isset($data->totalAmount) && !empty($data->totalAmount) 
				//&& isset($data->agentCharge) && !empty($data->agentCharge) && isset($data->serviceCharge) && !empty($data->serviceCharge) 
				//&& isset($data->otherCharge) && !empty($data->otherCharge) && isset($data->stampCharge) && !empty($data->stampCharge) 
				&& isset($data->accountNo) && !empty($data->accountNo) && isset($data->accountName) && !empty($data->accountName)
				&& isset($data->email) && !empty($data->email) && isset($data->mobile) && !empty($data->mobile)
				&& isset($data->productId) && !empty($data->productId)
				&& isset($data->serviceFeatureCode) && !empty($data->serviceFeatureCode) && isset($data->partnerId) && !empty($data->partnerId)
			){
				ini_set('max_execution_time', 120);
				set_time_limit(120);
				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$parentCode = $data->parentCode;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$billerGroupId = $data->billerGroupId;
				$bpBillerId = $data->billerId;
				$bpBillerName = $data->billerName;
				$bpProductName = $data->productName;
				$bpProductId = $data->productId;
				$requestAmount = $data->requestAmount;
				$accountNo = $data->accountNo;
				$totalAmount = $data->totalAmount;
				$agentCharge = $data->agentCharge;
				$serviceCharge = $data->serviceCharge;
				$otherCharge = $data->otherCharge;
				$partnerCharge = $data->partnerCharge;
				$stampCharge = $data->stampCharge;
				$accountName = $data->accountName;
				$email = $data->email;
				$mobile = $data->mobile;
				$serviceFeatureCode = $data->serviceFeatureCode;
				$partnerId = $data->partnerId;
				$lgaId = ADMIN_LOCAL_GOVT_ID;
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
					$validate_result = validateKey1($key1, $userId, $session_validity, '4', $con);
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
					
					$agent_info_wallet_status = check_agent_info_wallet_status($partyType, $partyCode, $con);
					if ($agent_info_wallet_status == 0 ) {
						//Checking available_balance
						$available_balance = check_agent_available_balance($userId, $con);
						error_log("available_balance response for = ".$available_balance);
						if ( $available_balance >= 0 || $available_balance != "" || $available_balance != null ) {
							if ( floatval($totalAmount) <= floatval($available_balance) ) {
								$bp_trans_log_id = generate_seq_num(3100, $con);
								unset($data->key1);
								$request_message = json_encode($data);
								if ($bp_trans_log_id > 0)  {
									error_log("bp_trans_log_id = ".$bp_trans_log_id);
									$request_message = mysqli_real_escape_string($con, $request_message);
									$bp_trans_log_query = "INSERT INTO bp_trans_log (bp_trans_log_id, partner_id, party_type, party_code, service_feature_code, country_id, state_id, amount, request_message, message_send_time, create_user, create_time) VALUES ($bp_trans_log_id, $partnerId, '$partyType', '$partyCode', '$serviceFeatureCode', $countryId, $stateId, $totalAmount, left('Opay Verify Customer = $request_message', 1000), now(), $userId, now())";
									error_log("bp_trans_log_query = ". $bp_trans_log_query);
									$bp_trans_log_result = mysqli_query($con, $bp_trans_log_query);
									if ( $bp_trans_log_result ) {
										$bp_request_id = generate_seq_num(3200, $con);
									   	if ( $bp_request_id > 0)  {
											$accountName = mysqli_real_escape_string($con, $accountName);
											$bp_request_query = "INSERT INTO bp_request (bp_request_id, bp_trans_log_id1, service_feature_code, bp_biller_id, bp_product_id, country_id, state_id, local_govt_id, request_amount, service_charge, partner_charge, other_charge, total_amount, account_no, account_name, mobile_no, user_id, status, create_time) VALUES ($bp_request_id, $bp_trans_log_id, '$serviceFeatureCode', $bpBillerId, $bpProductId, $countryId, $stateId, $lgaId, $requestAmount, $serviceCharge, $partnerCharge, $otherCharge, $totalAmount, '$accountNo', '$accountName', '$mobile', $userId, 'I', now())";
											error_log("bp_request_query = ".$bp_request_query);
											$bp_request_result = mysqli_query($con, $bp_request_query);
											if( $bp_request_result ) {
												$data = array();
												$data['customerId'] = $accountNo;
												$data['provider'] = $bpProductName;
												$data['serviceType'] = "betting";
												$data['countryId'] = $countryId;
												$data['stateId'] = $stateId;
												$data['userId'] = $userId;
												$data['signature'] = $local_signature;
												$data['key1'] = $key1;
												$data['amount'] = $requestAmount;
												$data['localGovtId'] = ADMIN_LOCAL_GOVT_ID;

												$url = BPAPI_OPAY_SERVER_VERIFY_BETTING_CUSTOMER_URL;
												//$sendreq = sendRequest($data, $url);
												$tsec = time();
												$raw_data1 = BPAPI_SERVER_APP_PASSWORD."|".BPAPI_SERVER_APP_USERNAME."|".$tsec;
												error_log("raw_data1 = ".$raw_data1);
												$key1 = base64_encode($raw_data1);
												error_log("key1 = ".$key1);
												error_log("before calling post");
												error_log("url = ".$url);
												$data['key1'] = $key1;
												$data['signature'] = $local_signature;
												error_log("request sent ==> ".json_encode($data));
												$ch = curl_init($url);
												curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
												curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
												curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
												curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, OPAY_CURL_CONNECTION_TIMEOUT);
												curl_setopt($ch, CURLOPT_TIMEOUT, OPAY_CURL_TIMEOUT);
												$curl_response = curl_exec($ch);
												$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
												$curl_error = curl_errno($ch);
												curl_close($ch);
												if ( $curl_error == 0 ) {
													error_log("curl_error == 0 ");
													error_log("response received = ".$curl_response);
													error_log("code = ".$httpcode);
													if ( $httpcode == 200 ) {
														error_log("inside httpcode == 200");
														$api_response = json_decode($curl_response, true);
														$statusCode = $api_response['responseCode'];
														$responseDescription = $api_response['responseDescription'];
														error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
														error_log("response_received <=== ".$curl_response);
														$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
														error_log("update_query = ".$update_query);
														$update_query_result = mysqli_query($con, $update_query);

														if($statusCode === 0) {
															error_log("inside statusCode == 0");
															$customerName = $api_response['firstName']." " .$api_response['lastName'];
															$update_query = "UPDATE bp_request SET status = 'V', bp_account_name = left('".$customerName."', 70), bp_bank_code = left('".$api_response['userName']."', 45), update_time = now() WHERE bp_request_id = $bp_request_id ";
															error_log("update_query = ".$update_query);
															$update_query_result = mysqli_query($con, $update_query);

															$response["statusCode"] = "0";
															$response["result"] = "Success";
															$response["message"] = $responseDescription;
															$response["partnerId"] = $partnerId;
															$response["signature"] = $server_signature;
															$response["transactionId"] = $bp_request_id;
															$response["provider"] = $api_response['provider'];
															$response["customerId"] = $api_response['customerId'];
															$response["firstName"] = $api_response['firstName'];
															$response["lastName"] = $api_response['lastName'];
															$response["userName"] = $api_response['userName'];
														}
														else {
															error_log("inside statusCode != 0");
															if ( $statusCode == '') {
																$statusCode = 50;
															}
															$approver_comments = "PV: ".$statusCode." - ".$responseDescription." @ ".$api_response['processingStartTime'];
															$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $bp_request_id ";
															error_log("update_query = ".$update_query);
															$update_query_result = mysqli_query($con, $update_query);

															$response["statusCode"] = $statusCode;
															$response["result"] = "Error";
															$response["message"] = $responseDescription;
															$response["partnerId"] = $partnerId;
															$response["signature"] = $server_signature;
														}
													}else {
														error_log("inside httpcode != 200");
														$statusCode = $httpcode;
														$responseDescription = "HTTP Protocol Error";
														error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
														$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
														error_log("update_query = ".$update_query);
														$update_query_result = mysqli_query($con, $update_query);

														$approver_comments = "PV: ".$statusCode." - ".$responseDescription;
														error_log("update_query = ".$update_query);
														$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $bp_request_id ";
														$update_query_result = mysqli_query($con, $update_query);

														$response["statusCode"] = $statusCode;
														$response["result"] = "Error";
														$response["message"] = "Error in connection to BillPay API Server";
														$response["partnerId"] = $partnerId;
														$response["signature"] = $server_signature;
													}
												}else {
													error_log("curl_error != 0 ");
													$statusCode = $curl_error;
													$responseDescription = "CURL Execution Error";
													$approver_comments = "PV: ".$statusCode." - ".$responseDescription;
													error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
													$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
													error_log("update_query = ".$update_query);
													$update_query_result = mysqli_query($con, $update_query);

													$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $bp_request_id ";
													error_log("update_query = ".$update_query);
													$update_query_result = mysqli_query($con, $update_query);

													$response["statusCode"] = $statusCode;
													$response["result"] = "Error";
													$response["message"] = "Error in communication protocol";
													$response["partnerId"] = $partnerId;
													$response["signature"] = $server_signature;
												}
											}
											else {
												//Error in Generating Bp Request Id Result
												$response["statusCode"] = "205";
												$response["result"] = "Error";
												$response["message"] = "DB Error in BP Request Result";
												$response["partnerId"] = $partnerId;
												$response["signature"] = $server_signature;
											}
										}
										else {
											//Error in Generating BP Request Id
											$response["statusCode"] = "210";
											$response["result"] = "Error";
											$response["message"] = "DB Error in BP Request";
											$response["partnerId"] = $partnerId;
											$response["signature"] = $server_signature;
										}
									}
									else {
										//Error in Generating Transaction Log Result
										$response["statusCode"] = "220";
										$response["result"] = "Error";
										$response["message"] = "DB Error in Trans Log Result";
										$response["partnerId"] = $partnerId;
										$response["signature"] = $server_signature;
									}
								}
								else {
									//Error in Generating Transaction Log
									$response["statusCode"] = "230";
									$response["result"] = "Error";
									$response["message"] = "DB Error in Trans Log";
									$response["partnerId"] = $partnerId;
									$response["signature"] = $server_signature;
								}
							}
							else {
								// Insufficient Agent Available Balance
								$response["statusCode"] = "240";
								$response["result"] = "Error";
								$response["message"] = "Insufficient Agent Available Balance";
								$response["partnerId"] = $partnerId;
								$response["signature"] = $server_signature;
							}
						}
						else {
							//Agent Available Balance is not available
							$response["statusCode"] = "250";
							$response["result"] = "Error";
							$response["message"] = "Agent Available Balance is not available";
							$response["partnerId"] = $partnerId;
							$response["signature"] = $server_signature;
						}
					}
					else {
						// Error in accessing Agent Info & Wallet details.
						$resp_message = "";
						if ( $agent_info_wallet_status == 1 ) {
							$resp_message = "Agent status is not active";
						}else if ( $agent_info_wallet_status == 2 ) {
							$resp_message = "Agent is blocked";
						}else if ( $agent_info_wallet_status == 3 ) {
							$resp_message = "Agent Wallet is not active";
						}else if ( $agent_info_wallet_status == 4 ) {
							$resp_message = "Agent Wallet is blocked";
						}else {
							$resp_message = "Error in accessing Agent Info & Wallet details. Contact Kadick Admin";
						}
						$response["statusCode"] = "260";
						$response["result"] = "Error";
						$response["message"] = $resp_message;
						$response["partnerId"] = $partnerId;
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
        	else if(isset($data -> operation) && $data -> operation == 'BP_PAYANT_TV_FORM_VALIDATION') {
			error_log("inside operation == BP_PAYANT_TV_FORM_VALIDATION method");
			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
				&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
				&& isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
				&& isset($data->stateId) && !empty($data->stateId) && isset($data->billerId) && !empty($data->billerId) 
				&& isset($data->productCode) && !empty($data->productCode) 
				&& isset($data->requestAmount) && !empty($data->requestAmount) && isset($data->totalAmount) && !empty($data->totalAmount) 
				&& isset($data->accountNo) && !empty($data->accountNo) && isset($data->accountName) && !empty($data->accountName)
				&& isset($data->email) && !empty($data->email) && isset($data->mobile) && !empty($data->mobile)
				&& isset($data->serviceFeatureCode) && !empty($data->serviceFeatureCode) && isset($data->partnerId) && !empty($data->partnerId)
			){
				ini_set('max_execution_time', 120);
				set_time_limit(120);
				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$parentCode = $data->parentCode;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$billerGroupId = $data->billerGroupId;
				$bpBillerId = $data->billerId;
				$bpProductId = $bpBillerId;
				$bpProductCode = $data->productCode;
				$bpBillerName = $data->billerName;
				$bpProductName = $data->productName;
				$requestAmount = $data->requestAmount;
				$accountNo = $data->accountNo;
				$totalAmount = $data->totalAmount;
				$agentCharge = $data->agentCharge;
				$serviceCharge = $data->serviceCharge;
				$otherCharge = $data->otherCharge;
				$partnerCharge = $data->partnerCharge;
				$stampCharge = $data->stampCharge;
				$accountName = $data->accountName;
				$email = $data->email;
				$mobile = $data->mobile;
				$serviceFeatureCode = $data->serviceFeatureCode;
				$partnerId = $data->partnerId;
				$bankCode = $data->bankCode;
				$lgaId = ADMIN_LOCAL_GOVT_ID;
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
					$validate_result = validateKey1($key1, $userId, $session_validity, '4', $con);
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
					
					$agent_info_wallet_status = check_agent_info_wallet_status($partyType, $partyCode, $con);
					if ($agent_info_wallet_status == 0 ) {
						//Checking available_balance
						$available_balance = check_agent_available_balance($userId, $con);
						error_log("available_balance response for = ".$available_balance);
						if ( $available_balance >= 0 || $available_balance != "" || $available_balance != null ) {
							if ( floatval($totalAmount) <= floatval($available_balance) ) {
								$bp_trans_log_id = generate_seq_num(3100, $con);
								unset($data->key1);
								$request_message = json_encode($data);
								if ($bp_trans_log_id > 0)  {
									error_log("bp_trans_log_id = ".$bp_trans_log_id);
									$request_message = mysqli_real_escape_string($con, $request_message);
									$bp_trans_log_query = "INSERT INTO bp_trans_log (bp_trans_log_id, partner_id, party_type, party_code, service_feature_code, country_id, state_id, amount, request_message, message_send_time, create_user, create_time) VALUES ($bp_trans_log_id, $partnerId, '$partyType', '$partyCode', '$serviceFeatureCode', $countryId, $stateId, $totalAmount, left('PayAnt Verify TV Customer = $request_message', 1000), now(), $userId, now())";
									error_log("bp_trans_log_query = ". $bp_trans_log_query);
									$bp_trans_log_result = mysqli_query($con, $bp_trans_log_query);
									if ( $bp_trans_log_result ) {
										$bp_request_id = generate_seq_num(3200, $con);
									   	if ( $bp_request_id > 0)  {
											$accountName = mysqli_real_escape_string($con, $accountName);
											$bp_request_query = "INSERT INTO bp_request (bp_request_id, bp_trans_log_id1, service_feature_code, bp_biller_id, bp_product_id, country_id, state_id, local_govt_id, request_amount, service_charge, partner_charge, other_charge, total_amount, account_no, account_name, mobile_no, user_id, status, create_time) VALUES ($bp_request_id, $bp_trans_log_id, '$serviceFeatureCode', $bpBillerId, $bpProductId, $countryId, $stateId, $lgaId, $requestAmount, $serviceCharge, $partnerCharge, $otherCharge, $totalAmount, '$accountNo', '$accountName', '$mobile', $userId, 'I', now())";
											error_log("bp_request_query = ".$bp_request_query);
											$bp_request_result = mysqli_query($con, $bp_request_query);
											if( $bp_request_result ) {
												$data = array();
												$data['account'] = $accountNo;
												$data['categoryId'] = $bpProductId;
												$data['countryId'] = $countryId;
												$data['stateId'] = $stateId;
												$data['userId'] = $userId;
												$data['signature'] = $local_signature;
												$data['key1'] = $key1;
												$data['amount'] = $requestAmount;
												$data['localGovtId'] = ADMIN_LOCAL_GOVT_ID;

												$url = BPAPI_PAYANT_SERVER_VERIFY_TV_CUSTOMER_URL;
												//$sendreq = sendRequest($data, $url);
												$tsec = time();
												$raw_data1 = BPAPI_SERVER_APP_PASSWORD."|".BPAPI_SERVER_APP_USERNAME."|".$tsec;
												error_log("raw_data1 = ".$raw_data1);
												$key1 = base64_encode($raw_data1);
												error_log("key1 = ".$key1);
												error_log("before calling post");
												error_log("url = ".$url);
												$data['key1'] = $key1;
												$data['signature'] = $local_signature;
												error_log("request sent ==> ".json_encode($data));
												$ch = curl_init($url);
												curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
												curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
												curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
												curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, PAYANT_CURL_CONNECTION_TIMEOUT);
												curl_setopt($ch, CURLOPT_TIMEOUT, PAYANT_CURL_TIMEOUT);
												$curl_response = curl_exec($ch);
												$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
												$curl_error = curl_errno($ch);
												curl_close($ch);
												if ( $curl_error == 0 ) {
													error_log("curl_error == 0 ");
													error_log("response received = ".$curl_response);
													error_log("code = ".$httpcode);
													if ( $httpcode == 200 ) {
														error_log("inside httpcode == 200");
														$api_response = json_decode($curl_response, true);
														$statusCode = $api_response['responseCode'];
														$responseDescription = $api_response['responseDescription'];
														error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
														error_log("response_received <=== ".$curl_response);
														$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
														error_log("update_query = ".$update_query);
														$update_query_result = mysqli_query($con, $update_query);

														if($statusCode === 0) {
															error_log("inside statusCode == 0");
															$update_query = "UPDATE bp_request SET status = 'V', bp_account_name = left('".$api_response['name']."', 70), update_time = now() WHERE bp_request_id = $bp_request_id ";
															error_log("update_query = ".$update_query);
															$update_query_result = mysqli_query($con, $update_query);

															$response["statusCode"] = "0";
															$response["result"] = "Success";
															$response["message"] = $responseDescription;
															$response["partnerId"] = $partnerId;
															$response["signature"] = $server_signature;
															
															$response["transactionId"] = $bp_request_id;
															$response["basketId"] = $api_response['basketId'];
															$response["name"] = $api_response['name'];
															$response["accountNumber"] = $api_response['accountNumber'];
															$response["customerNumber"] = $api_response['customerNumber'];
															$response["verifyStatus"] = $api_response['verifyStatus'];
															$response["boxOffice"] = $api_response['boxOffice'];
															$response["invoicePeriod"] = $api_response['invoicePeriod'];
															$response["totalAmount"] = $api_response['totalAmount'];
															$response["dueDate"] = $api_response['dueDate'];
															$response["balanceDue"] = $api_response['balanceDue'];;
																										}
														else {
															error_log("inside statusCode != 0");
															if ( $statusCode == '') {
																$statusCode = 50;
															}
															$approver_comments = "TV: ".$statusCode." - ".$responseDescription." @ ".$api_response['processingStartTime'];
															$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $bp_request_id ";
															error_log("update_query = ".$update_query);
															$update_query_result = mysqli_query($con, $update_query);

															$response["statusCode"] = $statusCode;
															$response["result"] = "Error";
															$response["message"] = $responseDescription;
															$response["partnerId"] = $partnerId;
															$response["signature"] = $server_signature;
														}
													}else {
														error_log("inside httpcode != 200");
														$statusCode = $httpcode;
														$responseDescription = "HTTP Protocol Error";
														error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
														$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
														error_log("update_query = ".$update_query);
														$update_query_result = mysqli_query($con, $update_query);

														$approver_comments = "TV: ".$statusCode." - ".$responseDescription;
														error_log("update_query = ".$update_query);
														$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $bp_request_id ";
														$update_query_result = mysqli_query($con, $update_query);

														$response["statusCode"] = $statusCode;
														$response["result"] = "Error";
														$response["message"] = "Error in connection to BillPay API Server";
														$response["partnerId"] = $partnerId;
														$response["signature"] = $server_signature;
													}
												}else {
													error_log("curl_error != 0 ");
													$statusCode = $curl_error;
													$responseDescription = "CURL Execution Error";
													$approver_comments = "TV: ".$statusCode." - ".$responseDescription;
													error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
													$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
													error_log("update_query = ".$update_query);
													$update_query_result = mysqli_query($con, $update_query);

													$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $bp_request_id ";
													error_log("update_query = ".$update_query);
													$update_query_result = mysqli_query($con, $update_query);

													$response["statusCode"] = $statusCode;
													$response["result"] = "Error";
													$response["message"] = "Error in communication protocol";
													$response["partnerId"] = $partnerId;
													$response["signature"] = $server_signature;
												}
											}
											else {
												//Error in Generating Bp Request Id Result
												$response["statusCode"] = "205";
												$response["result"] = "Error";
												$response["message"] = "DB Error in BP Request Result";
												$response["partnerId"] = $partnerId;
												$response["signature"] = $server_signature;
											}
										}
										else {
											//Error in Generating BP Request Id
											$response["statusCode"] = "210";
											$response["result"] = "Error";
											$response["message"] = "DB Error in BP Request";
											$response["partnerId"] = $partnerId;
											$response["signature"] = $server_signature;
										}
									}
									else {
										//Error in Generating Transaction Log Result
										$response["statusCode"] = "220";
										$response["result"] = "Error";
										$response["message"] = "DB Error in Trans Log Result";
										$response["partnerId"] = $partnerId;
										$response["signature"] = $server_signature;
									}
								}
								else {
									//Error in Generating Transaction Log
									$response["statusCode"] = "230";
									$response["result"] = "Error";
									$response["message"] = "DB Error in Trans Log";
									$response["partnerId"] = $partnerId;
									$response["signature"] = $server_signature;
								}
							}
							else {
								// Insufficient Agent Available Balance
								$response["statusCode"] = "240";
								$response["result"] = "Error";
								$response["message"] = "Insufficient Agent Available Balance";
								$response["partnerId"] = $partnerId;
								$response["signature"] = $server_signature;
							}
						}
						else {
							//Agent Available Balance is not available
							$response["statusCode"] = "250";
							$response["result"] = "Error";
							$response["message"] = "Agent Available Balance is not available";
							$response["partnerId"] = $partnerId;
							$response["signature"] = $server_signature;
						}
					}
					else {
						// Error in accessing Agent Info & Wallet details.
						$resp_message = "";
						if ( $agent_info_wallet_status == 1 ) {
							$resp_message = "Agent status is not active";
						}else if ( $agent_info_wallet_status == 2 ) {
							$resp_message = "Agent is blocked";
						}else if ( $agent_info_wallet_status == 3 ) {
							$resp_message = "Agent Wallet is not active";
						}else if ( $agent_info_wallet_status == 4 ) {
							$resp_message = "Agent Wallet is blocked";
						}else {
							$resp_message = "Error in accessing Agent Info & Wallet details. Contact Kadick Admin";
						}
						$response["statusCode"] = "260";
						$response["result"] = "Error";
						$response["message"] = $resp_message;
						$response["partnerId"] = $partnerId;
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
        	else if(isset($data -> operation) && $data -> operation == 'BP_PAYMENT_CONFIRM') {
			error_log("inside operation == BP_PAYMENT_CONFIRM method");
			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
				&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
				&& isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
				&& isset($data->stateId) && !empty($data->stateId) && isset($data->serviceFeatureId) && !empty($data->serviceFeatureId)
				&& isset($data->billerId) && !empty($data->billerId) && isset($data->productId) && !empty($data->productId) 
				&& isset($data->billerName) && !empty($data->billerName) && isset($data->productName) && !empty($data->productName)
				&& isset($data->requestAmount) && !empty($data->requestAmount) && isset($data->totalAmount) && !empty($data->totalAmount) 
				//&& isset($data->agentCharge) && !empty($data->agentCharge) && isset($data->serviceCharge) && !empty($data->serviceCharge) 
				//&& isset($data->otherCharge) && !empty($data->otherCharge) && isset($data->stampCharge) && !empty($data->stampCharge) 
				&& isset($data->accountNo) && !empty($data->accountNo) && isset($data->accountName) && !empty($data->accountName)
				&& isset($data->email) && !empty($data->email) && isset($data->mobile) && !empty($data->mobile)
				&& isset($data->serviceFeatureCode) && !empty($data->serviceFeatureCode) && isset($data->partnerId) && !empty($data->partnerId)
				&& isset($data->bpAccountNo) && !empty($data->bpAccountNo) && isset($data->bpAccountName) && !empty($data->bpAccountName)
				&& isset($data->bpBankCode) && !empty($data->bpBankCode) && isset($data->bpTransactionId) && !empty($data->bpTransactionId)
				&& isset($data->transactionId) && !empty($data->transactionId) && isset($data->location) && !empty($data->location)
				&& isset($data->amsCharge) && !empty($data->amsCharge)
			){
				ini_set('max_execution_time', 120);
				set_time_limit(120);
				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$parentCode = $data->parentCode;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$billerGroupId = $data->billerGroupId;
				$bpBillerId = $data->billerId;
				$bpProductId = $data->productId;
				$bpBillerName = $data->billerName;
				$bpProductName = $data->productName;
				$requestAmount = $data->requestAmount;
				$accountNo = $data->accountNo;
				$totalAmount = $data->totalAmount;
				$agentCharge = $data->agentCharge;
				$amsCharge = $data->amsCharge;
				$serviceCharge = $data->serviceCharge;
				$otherCharge = $data->otherCharge;
				$partnerCharge = $data->partnerCharge;
				$stampCharge = $data->stampCharge;
				$accountName = $data->accountName;
				$email = $data->email;
				$mobile = $data->mobile;
				$serviceFeatureCode = $data->serviceFeatureCode;
				$partnerId = $data->partnerId;
				$bpBankCode = $data->bpBankCode;
				$bpAccountNo = $data->bpAccountNo;
				$bpAccountName = $data->bpAccountName;
				$bpTransactionId = $data->bpTransactionId;
				$location = $data->location;
				$transactionId = $data->transactionId;
				$serviceFeatureId = $data->serviceFeatureId;
				$session_validity = AGENT_SESSION_VALID_TIME;
				$bp_service_order_no = 0;

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
					$validate_result = validateKey1($key1, $userId, $session_validity, '5', $con);
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
					
					$daily_check_result = checkDailyLimit($userId, $requestAmount, $con);
					//$daily_check_result = 0;
					error_log("daily_check_result = ".$daily_check_result);
					if ( $daily_check_result != 0 ){
						// Exceeded Daily Limit
						$response["statusCode"] = "998";
						$response["result"] = "Error";
						$response["message"] = "Failure: Exceeded Daily Limit";
						$response["signature"] = 0;
						error_log(json_encode($response));
						echo json_encode($response);
						return;
					}

					$txtType = "E";
					$partnerId = 8;
					if ($parentCode == "") {
						$partyCount = 2;
					}else {
						$partyCount = 3;
					}
					$db_flexiRate = "N";
					$flexi_rate_query = "select state_flexi_rate_id from state_flexi_rate where state_id = $stateId and (service_feature_id is null or (service_feature_id = $serviceFeatureId)) and active = 'Y' and (start_date is null or (start_date is not null and date(start_date) <= current_date())) and (expiry_date is null or (expiry_date is not null and date(expiry_date) >= current_date())) order by state_flexi_rate_id limit 1";
					error_log("flexi_rate_query query = ".$flexi_rate_query);
					$flexi_rate_result = mysqli_query($con, $flexi_rate_query);
					if ($flexi_rate_result) {
						$flexi_rate_count = mysqli_num_rows($flexi_rate_result);
						if($flexi_rate_count > 0) {					
							$db_flexiRate = "Y";
						}
					}

					if ( "Y" == $flexiRate || "Y" == $db_flexiRate ) {
						$txtType = "F";
					}
					error_log("before calling checking_feature_value: txType = ".$txtType);
					$checking_feature_value_response = checking_feature_value($userId, $countryId, $stateId, $partyCount, $serviceFeatureId, $partnerId, $requestAmount, $txtType, $con);
					$checking_feature_value_response_split = explode("#",$checking_feature_value_response);
					$charges_details = $checking_feature_value_response_split[0];	
					$rateparties_details = $checking_feature_value_response_split[1];										
					$charges_details_split = explode("|",$charges_details);	

					$rate_check_result = "N";
					$reponse_feature_value = $charges_details_split[0];	
					$service_feature_config_id = $charges_details_split[1];
					$ams_charge = $charges_details_split[2];
					$partner_charge = $charges_details_split[3];									
					$other_charge = $charges_details_split[4];
					if ( $txtType == "F" ) {
						error_log("inside txtType == F");
						$db_max_charge_amount = 0;
						$serviceconfig = explode(",", $rateparties_details);
						for($i = 0; $i < sizeof($serviceconfig); $i++) {
							if ( strpos($serviceconfig[$i], "Agent") !== false ) {
								$serviceconfig_array = explode("~", $serviceconfig[$i]);
								$db_max_charge_amount = $serviceconfig_array[4];
								break;
							}
						}
						error_log("db_max_charge_amount = ".$db_max_charge_amount.", request_check_max_total_amount = ".$request_check_max_total_amount);
						$request_check_max_total_amount = floatval($amsCharge) + floatval($agentCharge) + floatval($partner_charge) + floatval($other_charge);
						error_log("request_check_max_total_amount = ".$request_check_max_total_amount.", amsCharge = ".$amsCharge.", agentCharge = ".$agentCharge.", partner_charge = ".$partner_charge.", other_charge = ".$other_charge);
						if( $reponse_feature_value == 0 && (floatval($ams_charge) == floatval($amsCharge)) 
							&& (floatval($partner_charge) == floatval($partnerCharge)) && $request_check_max_total_amount <= floatval($db_max_charge_amount) ) {
							error_log("inside rate_check_result = Y");
							$rate_check_result = "Y";
						}else{
							error_log("inside rate_check_result = N");
							$rate_check_result = "N";
						}
					}else {								
						error_log("inside txtType != F");									
						$request_check_total_amount = floatval($amsCharge) +  floatval($partnerCharge) + floatval($otherCharge) + floatval($requestAmount);
						error_log("request_check_total_amount = ".request_check_total_amount.", amsCharge = ".$amsCharge.", agentCharge = ".$agentCharge.", partner_charge = ".$partner_charge.", other_charge = ".$other_charge);
						if( $reponse_feature_value == 0 &&  (floatval($ams_charge) == floatval($amsCharge) )  
							&& (floatval($partner_charge) == floatval($partnerCharge)) && (floatval($other_charge) == floatval($otherCharge)) 
							&& floatval($request_check_total_amount) == floatval($totalAmount)) {
							error_log("inside rate_check_result = Y");
							$rate_check_result = "Y";
						}else {
							error_log("inside rate_check_result = N");
							$rate_check_result = "N";
						}
					}
										
					// checkin get_feature_value response code
					if( $rate_check_result == "Y" ) {
						$agent_info_wallet_status = check_agent_info_wallet_status($partyType, $partyCode, $con);
						$rollBackOrder = "N";
						if ($agent_info_wallet_status == 0 ) {
							//Checking available_balance
							$available_balance = check_agent_available_balance($userId, $con);
							error_log("available_balance response for = ".$available_balance);
							if ( $available_balance >= 0 || $available_balance != "" || $available_balance != null ) {
								if ( floatval($totalAmount) <= floatval($available_balance) ) {
									$bp_trans_log_id = generate_seq_num(3100, $con);
									unset($data->key1);
									$request_message = json_encode($data);
									if ($bp_trans_log_id > 0)  {
										error_log("bp_trans_log_id = ".$bp_trans_log_id);
										$request_message = mysqli_real_escape_string($con, $request_message);
										$bp_trans_log_query = "INSERT INTO bp_trans_log (bp_trans_log_id, partner_id, party_type, party_code, service_feature_code, country_id, state_id, amount, request_message, message_send_time, create_user, create_time) VALUES ($bp_trans_log_id, $partnerId, '$partyType', '$partyCode', '$serviceFeatureCode', $countryId, $stateId, $totalAmount, left('Product Form Validate = $request_message', 1000), now(), $userId, now())";
										error_log("bp_trans_log_query = ". $bp_trans_log_query);
										$bp_trans_log_result = mysqli_query($con, $bp_trans_log_query);
										if( $bp_trans_log_result ) {
											if ( $txtType == "F" ) {
												$bp_request_update_query = "UPDATE bp_request SET service_charge = $amsCharge+$agentCharge, partner_charge = $partnerCharge, other_charge = $otherCharge, bp_trans_log_id2 = $bp_trans_log_id WHERE bp_request_id = ".$transactionId;
											}else {
												$bp_request_update_query = "UPDATE bp_request SET service_charge = $amsCharge, partner_charge = $partnerCharge, other_charge = $otherCharge,  bp_trans_log_id2 = $bp_trans_log_id WHERE bp_request_id = ".$transactionId;
											}
											error_log("bp_request_update_query = ".$bp_request_update_query);
											$bp_request_update_result = mysqli_query($con, $bp_request_update_query);		
											if($bp_request_update_result) {						
												$bp_service_order_no = generate_seq_num(3300, $con);
												if ( $bp_service_order_no > 0)  {
													error_log("Inside BP Service Order ==> BP_SERVICE_ORDER_NO = ".$bp_service_order_no);
													$acc_trans_type = 'BPAY1';
													$firstpartycode = $partyCode;
													$firstpartytype = $partyType;
													$secondpartycode = $parentCode;
													if(($secondpartycode == "") || empty($secondpartycode) || ($secondpartycode = null)) {
														$secondpartycode = "";
														$secondpartytype = "";
													}
													else {
														$secondpartytype = substr($secondpartycode,0);
													}
													$journal_entry_id = process_glentry($acc_trans_type, $bp_service_order_no, $firstpartycode, $firstpartytype, $secondpartycode, $secondpartytype, $narration, $totalAmount, $userId, $con);
													if($journal_entry_id > 0) {
														$journal_entry_error = "N";	
														$new_ams_charge = floatval($amsCharge) - floatval($agentCharge);
														if ( $txtType == "F" ) {
															$bp_service_order_query = "INSERT INTO bp_service_order (bp_service_order_no, service_feature_code, bp_biller_id, bp_product_id, partner_id, user_id, total_amount, request_amount, ams_charge, partner_charge, other_charge, agent_charge, stamp_charge, date_time) VALUES ($bp_service_order_no, '$serviceFeatureCode', $bpBillerId, $bpProductId, $partnerId, $userId, $totalAmount, $requestAmount, $amsCharge, $partnerCharge, $otherCharge, $agentCharge, $stampCharge, now())";
														}else {
															$bp_service_order_query = "INSERT INTO bp_service_order (bp_service_order_no, service_feature_code, bp_biller_id, bp_product_id, partner_id, user_id, total_amount, request_amount, ams_charge, partner_charge, other_charge, agent_charge, stamp_charge, date_time) VALUES ($bp_service_order_no, '$serviceFeatureCode', $bpBillerId, $bpProductId, $partnerId, $userId, $totalAmount, $requestAmount, $new_ams_charge, $partnerCharge, $otherCharge, $agentCharge, $stampCharge, now())";
														}
														error_log("bp_service_order_query = ".$bp_service_order_query);
														$bp_service_order_result = mysqli_query($con, $bp_service_order_query);
														if( $bp_service_order_result ) {
															error_log("inside success bp_service_order table entry");
															$get_acc_trans_type = getAcccTransType($acc_trans_type, $con);
															error_log("get_acc_trans_type = ".$get_acc_trans_type);	
															if($get_acc_trans_type != "-1"){
																$split = explode("|",$get_acc_trans_type);
																$ac_factor = $split[0];
																$cb_factor = $split[1];
																$acc_trans_type_id = $split[2];
																$update_wallet = walletupdateWithTransaction($acc_trans_type, $cb_factor, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId, $journal_entry_id);
																if( $update_wallet == 0 ) {	
																	//Inside success wallet update
																	error_log("bp_service_order_no = ".$bp_service_order_no);
																											
																	$data = array();
																	$data['orderNo'] = $bp_service_order_no;
																	$data['narration'] = "BILLPAY-ORDER NO: ".$bp_service_order_no;
																	$data['billerId'] = $bpBillerId;
																	$data['productId'] = $bpProductId;
																	$data['billerName'] = $bpBillerName;
																	$data['productName'] = $bpProductName;
																	$data['totalAmount'] = $requestAmount;
																	$data['partnerId'] = $partnerId;
																	$data['bpAccountNo'] = $bpAccountNo;
																	$data['bpAccountName'] = $bpAccountName;
																	$data['bpBankCode'] = $bpBankCode;
																	$data['bpTransactionId'] = $bpTransactionId;
																	$data['transactionId'] = $transactionId;
																	$data['location'] = $location;
																	$data['countryId'] = $countryId;
																	$data['stateId'] = $stateId;
																	$data['userId'] = $userId;
																	$data['localGovtId'] = ADMIN_LOCAL_GOVT_ID;

																	$url = BPAPI_SERVER_PAYMENT_CONFIRM_URL;
																	//$sendreq = sendRequest($data, $url);
																	$tsec = time();
																	$raw_data1 = BPAPI_SERVER_APP_PASSWORD."|".BPAPI_SERVER_APP_USERNAME."|".$tsec;
																	error_log("raw_data1 = ".$raw_data1);
																	$key1 = base64_encode($raw_data1);
																	error_log("key1 = ".$key1);
																	error_log("before calling post");
																	error_log("url = ".$url);
																	$data['key1'] = $key1;
																	$data['signature'] = $local_signature;
																	error_log("request sent ==> ".json_encode($data));
																	$ch = curl_init($url);
																	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
																	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
																	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
																	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, BILLPAY_CURL_CONNECTION_TIMEOUT);
																	curl_setopt($ch, CURLOPT_TIMEOUT, BILLPAY_CURL_TIMEOUT);
																	$curl_response = curl_exec($ch);
																	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
																	$curl_error = curl_errno($ch);
																	curl_close($ch);
																	if ( $curl_error === 0 ) {
																		error_log("curl_error == 0 ");
																		error_log("response received = ".$curl_response);
																		error_log("code = ".$httpcode);
																		if ( $httpcode == 200 ) {
																			error_log("inside httpcode == 200");
																			$api_response = json_decode($curl_response, true);
																			$statusCode = $api_response['responseCode'];
																			$responseDescription = $api_response['responseDescription'];
																			error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																			error_log("response_received <=== ".$curl_response);
																			$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
																			error_log("update_query = ".$update_query);
																			$update_query_result = mysqli_query($con, $update_query);

																			if($statusCode === 0) {
																			
																				error_log("inside statusCode === 0");
																				$update_query = "UPDATE bp_request SET order_no = $bp_service_order_no, status = 'S', update_time = now(), session_id = '".$api_response['nameSessionId']."' WHERE bp_request_id = $transactionId";
																				error_log("update_query = ".$update_query);
																				$update_query_result = mysqli_query($con, $update_query);

																				$gl_post_return_value = process_glpost($journal_entry_id, $con);
																				if ( $gl_post_return_value == 0 ) {
																					error_log("Success in BillPay gl_post for: ".$journal_entry_id);
																				}
																				else{
																					error_log("Error in gl_post, with gl_post_return_value = ".$gl_post_return_value);
																					insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AP", "W", "N", $totalAmount, $con);
																				}

																				$order_post_result = post_bporder($bp_service_order_no, $con);
																				if ( $order_post_result == 0 ) { 
																					error_log("Success in BillPay post_bporder for: ".$bp_service_order_no);
																				}else {
																					error_log("Error in BillPay post_bporder for: ".$bp_service_order_no);
																				}
																				$serviceconfig = explode(",", $rateparties_details);
																				$service_insert_count = 0;

																				//Insert into bp_service_order_comm table
																				for($i = 0; $i < sizeof($serviceconfig); $i++) {
																					$bpOrder_flag = insertBillPayServiceOrderComm($bp_service_order_no, $serviceconfig[$i], $journal_entry_id, $txtType, $agentCharge, $ams_charge, $con);
																					if ( $bpOrder_flag == 0 ) {
																						++$service_insert_count;
																					}
																				}
																				if ( $service_insert_count == sizeof($serviceconfig) ) {
																					error_log("All entries for service_order insert in commission table. Insert count = ".$service_insert_count);
																				}else {
																					error_log("Not all entries for service_order insert in commission table. Insert count = ".$service_insert_count.", serviceConfig count = ".sizeof($serviceconfig));
																				}
																				$pcu_result = process_bp_comm_update($bp_service_order_no, $con);
																				if ( $pcu_result > 0 ) {
																					if ( $pcu_result == sizeof($serviceconfig) ) {
																						error_log("All bp_service_order_comm updates are completed. Count = ".$pcu_result);
																					}else {
																						error_log("Warning bp_service_order_comm updates are not matching completed. Count = ".$pcu_result);
																					}
																				}else {
																					error_log("Error in bp_service_order_comm records insert. Insert Count = ".$pcu_result);
																				}
																				$availableBalance = check_party_available_balance($partyType, $userId, $con);
																				$orderTime = getBpOrderTime($bp_service_order_no, $con);

																				$response["statusCode"] = "0";
																				$response["result"] = "SUCCESS";
																				$response["message"] = $api_response['responseDescription'];
																				$response["processingStartTime"] = $api_response['processingStartTime'];
																				$response["partnerId"] = $partnerId;
																				$response["signature"] = $server_signature;
																				$response["totalAmount"] = $api_response['totalAmount'];
																				$response["nipSessionId"] = $api_response['nipSessionId'];
																				$response["ebSessionId"] = $bpTransactionId;
																				$response["nameSessionId"] = $api_response['nameSessionId'];
																				$response["transactionId"] = $transactionId;
																				$response["accountNo"] = $api_response['accountNo'];
																				$response["orderNo"] = $bp_service_order_no;
																				$response["transactionTime"] = $orderTime;
																				$response["availableBalance"] = $availableBalance;
																			}
																			else {
																				if ( $statusCode == '') {
																					$statusCode = 50;
																				}
																				error_log("inside statusCode != 0");
																				$rollBackOrder = "Y";
																				$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																				if ( $gl_reverse_repsonse != 0 ) {
																					error_log("Error in BillPay gl_reverse for: ".$journal_entry_id);
																					insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $totalAmount, $con);
																				}else {
																					error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																				}
																				//Rollback wallet update
																				$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId);
																				if ( $update_wallet != 0 ) {
																					error_log("Error in BillPay rollback_wallet for: ".$journal_entry_id);
																					insertaccountrollback($userId, $journal_entry_id, $acc_trans_type, $totalAmount, 2, "F", $con);
																				}else {
																					error_log("Success in BillPay rollback_wallet for: ".$journal_entry_id);
																					//Insert into account_rollback table with success status
																				}

																				$approver_comments = "PC: ".$statusCode." - ".$responseDescription." @ ".$api_response['processingStartTime'];
																				$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $transactionId";
																				error_log("update_query = ".$update_query);
																				$update_query_result = mysqli_query($con, $update_query);

																				$response["statusCode"] = $statusCode;
																				$response["result"] = "Error";
																				$response["message"] = $responseDescription;
																				$response["partnerId"] = $partnerId;
																				$response["signature"] = $server_signature;
																			}
																		}else {
																			error_log("inside httpcode != 200");
																			$rollBackOrder = "Y";

																			$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																			if ( $gl_reverse_repsonse != 0 ) {
																				error_log("Error in BillPay gl_reverse for: ".$journal_entry_id);
																				insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $totalAmount, $con);
																			}else {
																				error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																			}
																			//Rollback wallet update
																			$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId);
																			if ( $update_wallet != 0 ) {
																				error_log("Error in BillPay rollback_wallet for: ".$journal_entry_id);
																				insertaccountrollback($userId, $journal_entry_id, $acc_trans_type, $totalAmount, 2, "F", $con);
																			}else {
																				error_log("Success in BillPay rollback_wallet for: ".$journal_entry_id);
																				//Insert into account_rollback table with success status
																			}

																			$statusCode = $httpcode;
																			$responseDescription = "HTTP Protocol Error";
																			error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																			$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
																			error_log("update_query = ".$update_query);
																			$update_query_result = mysqli_query($con, $update_query);

																			$approver_comments = "PE: ".$statusCode." - ".$responseDescription;
																			error_log("update_query = ".$update_query);
																			$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $transactionId";
																			$update_query_result = mysqli_query($con, $update_query);

																			$response["statusCode"] = $statusCode;
																			$response["result"] = "Error";
																			$response["message"] = "Error in connection to BillPay API Server";
																			$response["partnerId"] = $partnerId;
																			$response["signature"] = $server_signature;
																		}
																	}else {
																		error_log("curl_error != 0 ");
																		$rollBackOrder = "Y";
																		$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																		if ( $gl_reverse_repsonse != 0 ) {
																			error_log("Error in BillPay gl_reverse for: ".$journal_entry_id);
																			insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $totalAmount, $con);
																		}else {
																			error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																		}
																		//Rollback wallet update
																		$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId);
																		if ( $update_wallet != 0 ) {
																			error_log("Error in BillPay rollback_wallet for: ".$journal_entry_id);
																			insertaccountrollback($userId, $journal_entry_id, $acc_trans_type, $totalAmount, 2, "F", $con);
																		}else {
																			error_log("Success in BillPay rollback_wallet for: ".$journal_entry_id);
																			//Insert into account_rollback table with success status
																		}

																		$statusCode = $curl_error;
																		$responseDescription = "CURL Execution Error";
																		$approver_comments = "PE: ".$statusCode." - ".$responseDescription;
																		error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																		$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
																		error_log("update_query = ".$update_query);
																		$update_query_result = mysqli_query($con, $update_query);

																		$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $transactionId";
																		error_log("update_query = ".$update_query);
																		$update_query_result = mysqli_query($con, $update_query);

																		$response["statusCode"] = $statusCode;
																		$response["result"] = "Error";
																		$response["message"] = "Error in communication protocol";
																		$response["partnerId"] = $partnerId;
																		$response["signature"] = $server_signature;
																	}
																}
																else {
																	//Not able to Update Wallet
																	$response["statusCode"] = "305";
																	$response["result"] = "Error";
																	$response["message"] = "DB Error in updating wallet";
																	$response["partnerId"] = $partnerId;
																	$response["signature"] = $server_signature;
																}
															}
															else {
																//Not able to get acc_trans_type
																$response["statusCode"] = "310";
																$response["result"] = "Error";
																$response["message"] = "DB Error in getting acc_trans_type";
																$response["partnerId"] = $partnerId;
																$response["signature"] = $server_signature;
															}

															//Clear bp_service_order
															if ( $rollBackOrder == "Y") {
																//Roll back of BP Service Order error
																$bp_services_order_delete_query = "delete from bp_service_order where bp_service_order_no = $bp_service_order_no";
																error_log("bp_services_order_delete_query = " . $bp_services_order_delete_query);
																$bp_services_order_delete_result = mysqli_query($con, $bp_services_order_delete_query);
																if ( $bp_services_order_delete_result ) {
																	error_log("bp_service_order delete successful");
																}else {
																	error_log("bp_service_order delete failure = ".mysqli_error());
																}
															}
														}else {
															//Error in inserting Bp Service Order Result
															$response["statusCode"] = "315";
															$response["result"] = "Error";
															$response["message"] = "DB Error in BP Service Order Result";
															$response["partnerId"] = $partnerId;
															$response["signature"] = $server_signature;
														}
													}else {
														//Error in getting journal_entry_id
														$response["statusCode"] = "320";
														$response["result"] = "Error";
														$response["message"] = "DB Error in gettin journal entry id";
														$response["partnerId"] = $partnerId;
														$response["signature"] = $server_signature;
													}
												}
												else {
													//Error in Generating Bp Service Order
													$response["statusCode"] = "325";
													$response["result"] = "Error";
													$response["message"] = "DB Error in BP Service Order Request";
													$response["partnerId"] = $partnerId;
													$response["signature"] = $server_signature;
												}
											}else {
												//Error in Updating Bp Request Table
												$response["statusCode"] = "330";
												$response["result"] = "Error";
												$response["message"] = "DB Error in updating BP Request";
												$response["partnerId"] = $partnerId;
												$response["signature"] = $server_signature;
											}
										}else {
											//Error in inserting Bp Transaction Log
											$response["statusCode"] = "335";
											$response["result"] = "Error";
											$response["message"] = "DB Error in BP Trans Log";
											$response["partnerId"] = $partnerId;
											$response["signature"] = $server_signature;
										}
									}else {
										//Error in generating Bp Transaction Log
										$response["statusCode"] = "340";
										$response["result"] = "Error";
										$response["message"] = "DB Error in BP Trans Log Request";
										$response["partnerId"] = $partnerId;
										$response["signature"] = $server_signature;
									}
								}else {
									// Insufficient Agent Available Balance
									$response["statusCode"] = "350";
									$response["result"] = "Error";
									$response["message"] = "Insufficient Agent Available Balance";
									$response["partnerId"] = $partnerId;
									$response["signature"] = $server_signature;
								}
							}
							else {
								//Agent Available Balance is not available
								$response["statusCode"] = "360";
								$response["result"] = "Error";
								$response["message"] = "Agent Available Balance is not available";
								$response["partnerId"] = $partnerId;
								$response["signature"] = $server_signature;
							}
						}
						else {
							// Error in accessing Agent Info & Wallet details.
							$resp_message = "";
							if ( $agent_info_wallet_status == 1 ) {
								$resp_message = "Agent status is not active";
							}else if ( $agent_info_wallet_status == 2 ) {
								$resp_message = "Agent is blocked";
							}else if ( $agent_info_wallet_status == 3 ) {
								$resp_message = "Agent Wallet is not active";
							}else if ( $agent_info_wallet_status == 4 ) {
								$resp_message = "Agent Wallet is blocked";
							}else {
								$resp_message = "Error in accessing Agent Info & Wallet details. Contact Kadick Admin";
							}
							$response["statusCode"] = "370";
							$response["result"] = "Error";
							$response["message"] = $resp_message;
							$response["partnerId"] = $partnerId;
							$response["signature"] = $server_signature;
						}
					}else {
						//Error - Db total amount and client total amount are diferent
						$response["statusCode"] = "380";
						$response["result"] = "Error";
						$response["message"] = "Failure: Invalid request...";
						$response["partnerId"] = $partnerId;
						$response["signature"] = 0;
					}
				}else {
					// Invalid Singature
					$response["statusCode"] = "390";
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
        	else if(isset($data -> operation) && $data -> operation == 'BP_PAYANT_PAYMENT_CONFIRM') {
			error_log("inside operation == BP_PAYANT_PAYMENT_CONFIRM method");
			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
				&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
				&& isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
				&& isset($data->stateId) && !empty($data->stateId) && isset($data->serviceFeatureId) && !empty($data->serviceFeatureId)
				&& isset($data->billerId) && !empty($data->billerId) && isset($data->productId) && !empty($data->productId) 
				&& isset($data->billerName) && !empty($data->billerName) && isset($data->productName) && !empty($data->productName)
				&& isset($data->requestAmount) && !empty($data->requestAmount) && isset($data->totalAmount) && !empty($data->totalAmount) 
				//&& isset($data->agentCharge) && !empty($data->agentCharge) && isset($data->serviceCharge) && !empty($data->serviceCharge) 
				//&& isset($data->otherCharge) && !empty($data->otherCharge) && isset($data->stampCharge) && !empty($data->stampCharge) 
				&& isset($data->accountNo) && !empty($data->accountNo) && isset($data->accountName) && !empty($data->accountName)
				&& isset($data->email) && !empty($data->email) && isset($data->mobile) && !empty($data->mobile)
				&& isset($data->serviceFeatureCode) && !empty($data->serviceFeatureCode) && isset($data->partnerId) && !empty($data->partnerId)
				//&& isset($data->bpAccountNo) && !empty($data->bpAccountNo) && isset($data->bpAccountName) && !empty($data->bpAccountName)
				//&& isset($data->bpBankCode) && !empty($data->bpBankCode) && isset($data->bpTransactionId) && !empty($data->bpTransactionId)
				&& isset($data->transactionId) && !empty($data->transactionId) && isset($data->location) && !empty($data->location)
				//&& isset($data->amsCharge) && !empty($data->amsCharge)
			){
				ini_set('max_execution_time', 120);
				set_time_limit(120);
				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$parentCode = $data->parentCode;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$billerGroupId = $data->billerGroupId;
				$bpBillerId = $data->billerId;
				$bpProductId = $data->productId;
				$bpBillerName = $data->billerName;
				$bpProductName = $data->productName;
				$requestAmount = $data->requestAmount;
				$accountNo = $data->accountNo;
				$totalAmount = $data->totalAmount;
				$agentCharge = $data->agentCharge;
				$amsCharge = $data->amsCharge;
				$serviceCharge = $data->serviceCharge;
				$otherCharge = $data->otherCharge;
				$partnerCharge = $data->partnerCharge;
				$stampCharge = $data->stampCharge;
				$accountName = $data->accountName;
				$email = $data->email;
				$mobile = $data->mobile;
				$serviceFeatureCode = $data->serviceFeatureCode;
				$partnerId = $data->partnerId;
				//$bpBankCode = $data->bpBankCode;
				//$bpAccountNo = $data->bpAccountNo;
				$bpAccountName = $data->bpAccountName;
				//$bpTransactionId = $data->bpTransactionId;
				$location = $data->location;
				$transactionId = $data->transactionId;
				$serviceFeatureId = $data->serviceFeatureId;
				$session_validity = AGENT_SESSION_VALID_TIME;
				$bp_service_order_no = 0;

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
					$validate_result = validateKey1($key1, $userId, $session_validity, '5', $con);
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
					
					$daily_check_result = checkDailyLimit($userId, $requestAmount, $con);
					//$daily_check_result = 0;
					error_log("daily_check_result = ".$daily_check_result);
					if ( $daily_check_result != 0 ){
						// Exceeded Daily Limit
						$response["statusCode"] = "998";
						$response["result"] = "Error";
						$response["message"] = "Failure: Exceeded Daily Limit";
						$response["signature"] = 0;
						error_log(json_encode($response));
						echo json_encode($response);
						return;
					}

					$txtType = "E";
					if ($parentCode == "") {
						$partyCount = 2;
					}else {
						$partyCount = 3;
					}
					$db_flexiRate = "N";
					$flexi_rate_query = "select state_flexi_rate_id from state_flexi_rate where state_id = $stateId and (service_feature_id is null or (service_feature_id = $serviceFeatureId)) and active = 'Y' and (start_date is null or (start_date is not null and date(start_date) <= current_date())) and (expiry_date is null or (expiry_date is not null and date(expiry_date) >= current_date())) order by state_flexi_rate_id limit 1";
					error_log("flexi_rate_query query = ".$flexi_rate_query);
					$flexi_rate_result = mysqli_query($con, $flexi_rate_query);
					if ($flexi_rate_result) {
						$flexi_rate_count = mysqli_num_rows($flexi_rate_result);
						if($flexi_rate_count > 0) {					
							$db_flexiRate = "Y";
						}
					}

					if ( "Y" == $flexiRate || "Y" == $db_flexiRate ) {
						$txtType = "F";
					}
					
					$agent_info_wallet_status = check_agent_info_wallet_status($partyType, $partyCode, $con);
					$rollBackOrder = "N";
					if ($agent_info_wallet_status == 0 ) {
						//Checking available_balance
						$available_balance = check_agent_available_balance($userId, $con);
						error_log("available_balance response for = ".$available_balance);
						if ( $available_balance >= 0 || $available_balance != "" || $available_balance != null ) {
							if ( floatval($totalAmount) <= floatval($available_balance) ) {
								$bp_trans_log_id = generate_seq_num(3100, $con);
																		
								$data = array();
								$data['serviceCategoryId'] = $bpProductId;
								$data['meterNumber'] = $accountNo;
								$data['amount'] = $requestAmount;
								$data['phone'] = $mobile;
								$data['countryId'] = $countryId;
								$data['stateId'] = $stateId;
								$data['userId'] = $userId;
								$data['localGovtId'] = ADMIN_LOCAL_GOVT_ID;
									
								$request_message = json_encode($data);
								if ($bp_trans_log_id > 0)  {
									error_log("bp_trans_log_id = ".$bp_trans_log_id);
									$request_message = mysqli_real_escape_string($con, $request_message);
									$bp_trans_log_query = "INSERT INTO bp_trans_log (bp_trans_log_id, partner_id, party_type, party_code, service_feature_code, country_id, state_id, amount, request_message, message_send_time, create_user, create_time) VALUES ($bp_trans_log_id, $partnerId, '$partyType', '$partyCode', '$serviceFeatureCode', $countryId, $stateId, $totalAmount, left('PayAnt Bill Payment = $request_message', 1000), now(), $userId, now())";
									error_log("bp_trans_log_query = ". $bp_trans_log_query);
									$bp_trans_log_result = mysqli_query($con, $bp_trans_log_query);
									if( $bp_trans_log_result ) {
										if ( $txtType == "F" ) {
											$bp_request_update_query = "UPDATE bp_request SET service_charge = $amsCharge+$agentCharge, partner_charge = $partnerCharge, other_charge = $otherCharge, bp_trans_log_id2 = $bp_trans_log_id WHERE bp_request_id = ".$transactionId;
										}else {
											$bp_request_update_query = "UPDATE bp_request SET service_charge = $amsCharge, partner_charge = $partnerCharge, other_charge = $otherCharge,  bp_trans_log_id2 = $bp_trans_log_id WHERE bp_request_id = ".$transactionId;
										}
										error_log("bp_request_update_query = ".$bp_request_update_query);
										$bp_request_update_result = mysqli_query($con, $bp_request_update_query);		
										if($bp_request_update_result) {						
											$bp_service_order_no = generate_seq_num(3300, $con);
											if ( $bp_service_order_no > 0)  {
												error_log("Inside BP Service Order ==> BP_SERVICE_ORDER_NO = ".$bp_service_order_no);
												$acc_trans_type = 'BPAY1';
												$firstpartycode = $partyCode;
												$firstpartytype = $partyType;
												$secondpartycode = $parentCode;
												if(($secondpartycode == "") || empty($secondpartycode) || ($secondpartycode = null)) {
													$secondpartycode = "";
													$secondpartytype = "";
												}
												else {
													$secondpartytype = substr($secondpartycode,0);
												}
												$journal_entry_id = process_glentry($acc_trans_type, $bp_service_order_no, $firstpartycode, $firstpartytype, $secondpartycode, $secondpartytype, $narration, $totalAmount, $userId, $con);
												if($journal_entry_id > 0) {
													$journal_entry_error = "N";	
													$new_ams_charge = floatval($amsCharge) - floatval($agentCharge);
													if ( $txtType == "F" ) {
														$bp_service_order_query = "INSERT INTO bp_service_order (bp_service_order_no, service_feature_code, bp_biller_id, bp_product_id, partner_id, user_id, total_amount, request_amount, ams_charge, partner_charge, other_charge, agent_charge, stamp_charge, date_time) VALUES ($bp_service_order_no, '$serviceFeatureCode', $bpBillerId, $bpProductId, $partnerId, $userId, $totalAmount, $requestAmount, $amsCharge, $partnerCharge, $otherCharge, $agentCharge, $stampCharge, now())";
													}else {
														$bp_service_order_query = "INSERT INTO bp_service_order (bp_service_order_no, service_feature_code, bp_biller_id, bp_product_id, partner_id, user_id, total_amount, request_amount, ams_charge, partner_charge, other_charge, agent_charge, stamp_charge, date_time) VALUES ($bp_service_order_no, '$serviceFeatureCode', $bpBillerId, $bpProductId, $partnerId, $userId, $totalAmount, $requestAmount, $new_ams_charge, $partnerCharge, $otherCharge, $agentCharge, $stampCharge, now())";
													}
													error_log("bp_service_order_query = ".$bp_service_order_query);
													$bp_service_order_result = mysqli_query($con, $bp_service_order_query);
													if( $bp_service_order_result ) {
														error_log("inside success bp_service_order table entry");
														$get_acc_trans_type = getAcccTransType($acc_trans_type, $con);
														error_log("get_acc_trans_type = ".$get_acc_trans_type);	
														if($get_acc_trans_type != "-1"){
															$split = explode("|",$get_acc_trans_type);
															$ac_factor = $split[0];
															$cb_factor = $split[1];
															$acc_trans_type_id = $split[2];
															$update_wallet = walletupdateWithTransaction($acc_trans_type, $cb_factor, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId, $journal_entry_id);
															if( $update_wallet == 0 ) {	
																//Inside success wallet update
																error_log("bp_service_order_no = ".$bp_service_order_no);
																	
																$url = BPAPI_PAYANT_SERVER_BILL_PAYMENT_URL;
																$tsec = time();
																$raw_data1 = BPAPI_SERVER_APP_PASSWORD."|".BPAPI_SERVER_APP_USERNAME."|".$tsec;
																error_log("raw_data1 = ".$raw_data1);
																$key1 = base64_encode($raw_data1);
																error_log("key1 = ".$key1);
																error_log("before calling post");
																error_log("url = ".$url);
																$data['key1'] = $key1;
																$data['signature'] = $local_signature;
																error_log("request sent ==> ".json_encode($data));
																$ch = curl_init($url);
																curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
																curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
																curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
																curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, PAYANT_CURL_CONNECTION_TIMEOUT);
																curl_setopt($ch, CURLOPT_TIMEOUT, PAYANT_CURL_TIMEOUT);
																$curl_response = curl_exec($ch);
																$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
																$curl_error = curl_errno($ch);
																curl_close($ch);
																if ( $curl_error === 0 ) {
																	error_log("curl_error == 0 ");
																	error_log("response received = ".$curl_response);
																	error_log("code = ".$httpcode);
																	if ( $httpcode == 200 ) {
																		error_log("inside httpcode == 200");
																		$api_response = json_decode($curl_response, true);
																		$statusCode = $api_response['responseCode'];
																		$responseDescription = $api_response['responseDescription'];
																		$statusMessage = $api_response['status']." - ".$api_response['message'];
																		error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																		error_log("response_received <=== ".$curl_response);
																		$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
																		error_log("update_query = ".$update_query);
																		$update_query_result = mysqli_query($con, $update_query);

																		if($statusCode === 0) {
																			
																			error_log("inside statusCode === 0");
																			$bp_resp_dataVendTime = $api_response['dataVendTime'];
																			if ( empty($bp_resp_dataVendTime) || !isset($bp_resp_dataVendTime) ){
																				$bp_resp_dataVendTime = $api_response['createdAt'];	
																			}
																			$bp_resp_transaction_id = $api_response['id'];
																			if ( strlen($bp_resp_transaction_id) > 45 ){
																				$bp_resp_transaction_id = "*".$bp_resp_transaction_id;
																			}
																			$bp_resp_account_no = $api_response['reference']." / ".$api_response['dataVendRef'];
																			$bp_resp_account_no_len = strlen($bp_resp_account_no);
																			$bp_resp_reference = "";
																			$bp_resp_dataVendRef = "";
																			if ( $bp_resp_account_no_len > 30 ) {
																				$bp_resp_account_no = "*".$bp_resp_account_no;
																				$bp_resp_reference_len = strlen($api_response['reference']);
																				if ( $bp_resp_reference_len <= 27 ) {
																					$bp_resp_reference = "*".$api_response['reference'];
																					$bp_resp_dataVendRef_len = 27 - $bp_resp_reference_len;
																					if ( $bp_resp_dataVendRef_len > 1 ) {
																						$bp_resp_dataVendRef = substr($api_response['dataVendRef'], 0, $bp_resp_dataVendRef_len-1);
																					}
																				}else {
																					$bp_resp_reference = substr($api_response['reference'], 0, 25);
																					$bp_resp_reference = "*".$bp_resp_reference;
																				}
																			}else {
																				$bp_resp_reference = $api_response['reference'];
																				$bp_resp_dataVendRef = $api_response['dataVendRef'];
																			}
																			$bp_resp_account_no_new = $bp_resp_reference." / ".$bp_resp_dataVendRef;
																			
																			$bp_resp_bank_code = $api_response['dataReceiptNo'];
																			if ( empty($bp_resp_bank_code) || !isset($bp_resp_bank_code) ){
																				$bp_resp_bank_code = "-";
																			}
																			if ( strlen($bp_resp_bank_code) > 45 ) {
																				$bp_resp_bank_code = "*".$bp_resp_bank_code;
																			}
																			$bp_resp_account_name = $api_response['verifyName'];
																			if ( strlen($bp_resp_account_name) > 70) {
																				$bp_resp_account_name = "*".$bp_resp_account_name;
																			}
																			$bp_resp_comments = $api_response['pinCode'];
																			if ( strlen($bp_resp_comments) > 256) {
																				$bp_resp_comments = "*".$bp_resp_comments;
																			} 
																			$bp_resp_approver_comments = "Pin SNo: ".$api_response['pinSerialNumber'].", Units: ".$api_response['pinUnits'].", AmountPaid: ".$api_response['pinUnits'].", AmountGenerated: ".$api_response['dataAmountGenerated'];
																			$update_query = "UPDATE bp_request SET order_no = $bp_service_order_no, status = 'S', update_time = now(), bp_transaction_id = left('$bp_resp_transaction_id', 45), bp_account_no = left('$bp_resp_account_no_new', 30), bp_bank_code = left('$bp_resp_bank_code', 45), bp_account_name = left('$bp_resp_account_name', 70), comments = left('$bp_resp_comments', 256), approver_comments = left('$bp_resp_approver_comments', 256), session_id = '$bp_resp_dataVendTime' WHERE bp_request_id = $transactionId";
																			error_log("update_query = ".$update_query);
																			$update_query_result = mysqli_query($con, $update_query);

																			$gl_post_return_value = process_glpost($journal_entry_id, $con);
																			if ( $gl_post_return_value == 0 ) {
																				error_log("Success in BillPay gl_post for: ".$journal_entry_id);
																			}
																			else{
																				error_log("Error in gl_post, with gl_post_return_value = ".$gl_post_return_value);
																				insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AP", "W", "N", $totalAmount, $con);
																			}

																			$order_post_result = post_bporder($bp_service_order_no, $con);
																			if ( $order_post_result == 0 ) { 
																				error_log("Success in BillPay post_bporder for: ".$bp_service_order_no);
																			}else {
																				error_log("Error in BillPay post_bporder for: ".$bp_service_order_no);
																			}
																			
																			$checking_feature_value_response = checking_feature_value($userId, $countryId, $stateId, $partyCount, $serviceFeatureId, $partnerId, $requestAmount, $txtType, $con);
																			$checking_feature_value_response_split = explode("#",$checking_feature_value_response);
																			$rateparties_details = $checking_feature_value_response_split[1];
																			$service_insert_count = 0;
																			$serviceconfig = explode(",", $rateparties_details);
																			error_log("bil_pay serviceconfig = ".$serviceconfig);
																			
																			//Insert into bp_service_order_comm table
																			for($i = 0; $i < sizeof($serviceconfig); $i++) {
																				$bpOrder_flag = insertBillPayServiceOrderComm($bp_service_order_no, $serviceconfig[$i], $journal_entry_id, $txtType, $agentCharge, $ams_charge, $con);
																				if ( $bpOrder_flag == 0 ) {
																					++$service_insert_count;
																				}
																			}
																			if ( $service_insert_count == sizeof($serviceconfig) ) {
																				error_log("All entries for service_order insert in commission table. Insert count = ".$service_insert_count);
																			}else {
																				error_log("Not all entries for service_order insert in commission table. Insert count = ".$service_insert_count.", serviceConfig count = ".sizeof($serviceconfig));
																			}
																			$pcu_result = process_bp_comm_update($bp_service_order_no, $con);
																			if ( $pcu_result > 0 ) {
																				if ( $pcu_result == sizeof($serviceconfig) ) {
																					error_log("All bp_service_order_comm updates are completed. Count = ".$pcu_result);
																				}else {
																					error_log("Warning bp_service_order_comm updates are not matching completed. Count = ".$pcu_result);
																				}
																			}else {
																				error_log("Error in bp_service_order_comm records insert. Insert Count = ".$pcu_result);
																			}
																			$availableBalance = check_party_available_balance($partyType, $userId, $con);
																			$orderTime = getBpOrderTime($bp_service_order_no, $con);

																			$response["statusCode"] = "0";
																			$response["result"] = "SUCCESS";
																			$response["message"] = $api_response['responseDescription'];
																			$response["processingStartTime"] = $api_response['processingStartTime'];
																				
																			$response["transactionId"] = $transactionId;
																			$response["orderNo"] = $bp_service_order_no;
																			$response["status"] = $api_response['status'];
																			$response["pinUnits"] = $api_response['pinUnits'];
																			//$response["pinCode"] = $api_response['pinCode'];
																			$response["pinCode"] = $bp_resp_comments;
																			$response["pinSerialNumber"] = $api_response['pinSerialNumber'];
																			$response["amount"] = $api_response['amount'];
																			//$response["reference"] = $api_response['reference'];
																			$response["reference"] = $bp_resp_reference;
																			//$response["id"] = $api_response['id'];
																			$response["id"] = $bp_resp_transaction_id;
																			$response["dataResponseMessage"] = $api_response['dataResponseMessage'];
																			$response["dataResponseCode"] = $api_response['dataResponseCode'];
																			//$response["dataVendRef"] = $api_response['dataVendRef'];
																			$response["dataVendRef"] = $bp_resp_dataVendRef;
																			$response["dataVendAmount"] = $api_response['dataVendAmount'];
																			$response["dataUnits"] = $api_response['dataUnits'];
																			$response["dataTotalAmountPaid"] = $api_response['dataTotalAmountPaid'];
																			$response["dataToken"] = $api_response['dataToken'];
																			//$response["dataVendTime"] = $api_response['dataVendTime'];
																			$response["dataVendTime"] = $bp_resp_dataVendTime;
																			$response["dataTax"] = $api_response['dataTax'];
																			//$response["dataReceiptNo"] = $api_response['dataReceiptNo'];
																			$response["dataReceiptNo"] = $bp_resp_bank_code;
																			$response["dataOrderId"] = $api_response['dataOrderId'];
																			$response["dataFreeUnits"] = $api_response['dataFreeUnits'];
																			$response["dataDisco"] = $api_response['dataDisco'];
																			$response["dataDebtRemaining"] = $api_response['dataDebtRemaining'];
																			$response["dataDebtAmount"] = $api_response['dataDebtAmount'];
																			$response["dataTariff"] = $api_response['dataTariff'];
																			$response["dataAmountGenerated"] = $api_response['dataAmountGenerated'];
																			$response["dataId"] = $api_response['dataId'];
																			$response["verifyMaxPayableAmount"] = $api_response['verifyMaxPayableAmount'];
																			$response["verifyMinPayableAmount"] = $api_response['verifyMinPayableAmount'];
																			$response["verifyDaysLastVend"] = $api_response['verifyDaysLastVend'];
																			$response["verifyTariff"] = $api_response['verifyTariff'];
																			$response["verifyFreeUnits"] = $api_response['verifyFreeUnits'];
																			$response["verifyMeterNo"] = $api_response['verifyMeterNo'];
																			$response["verifyVendType"] = $api_response['verifyVendType'];
																			$response["verifyAddress"] = $api_response['verifyAddress'];
																			//$response["verifyName"] = $api_response['verifyName'];
																			$response["verifyName"] = $bp_resp_account_name;
																			$response["serviceCategoryId"] = $api_response['serviceCategoryId'];
																			$response["availableBalance"] = $availableBalance;
																			
																			$response["partnerId"] = $partnerId;
																			$response["signature"] = $server_signature;
																		}
																		else {
																			if ( $statusCode == '') {
																				$statusCode = 50;
																			}
																			error_log("inside statusCode != 0");
																			$rollBackOrder = "Y";
																			$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																			if ( $gl_reverse_repsonse != 0 ) {
																				error_log("Error in BillPay gl_reverse for: ".$journal_entry_id);
																				insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $totalAmount, $con);
																			}else {
																				error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																			}
																			//Rollback wallet update
																			$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId);
																			if ( $update_wallet != 0 ) {
																				error_log("Error in BillPay rollback_wallet for: ".$journal_entry_id);
																				insertaccountrollback($userId, $journal_entry_id, $acc_trans_type, $totalAmount, 2, "F", $con);
																			}else {
																				error_log("Success in BillPay rollback_wallet for: ".$journal_entry_id);
																				//Insert into account_rollback table with success status
																			}

																			$approver_comments = "PC: ".$statusCode." - ".$responseDescription." @ ".$api_response['processingStartTime'];
																			$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $transactionId";
																			error_log("update_query = ".$update_query);
																			$update_query_result = mysqli_query($con, $update_query);
																			
																			$response["statusCode"] = $statusCode;
																			$response["result"] = "Error";
																			//$response["message"] = $responseDescription;
																			$response["message"] = $statusMessage;
																			$response["partnerId"] = $partnerId;
																			$response["signature"] = $server_signature;
																		}
																	}else {
																		error_log("inside httpcode != 200");
																		$rollBackOrder = "Y";

																		$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																		if ( $gl_reverse_repsonse != 0 ) {
																			error_log("Error in BillPay gl_reverse for: ".$journal_entry_id);
																			insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $totalAmount, $con);
																		}else {
																			error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																		}
																		//Rollback wallet update
																		$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId);
																		if ( $update_wallet != 0 ) {
																			error_log("Error in BillPay rollback_wallet for: ".$journal_entry_id);
																			insertaccountrollback($userId, $journal_entry_id, $acc_trans_type, $totalAmount, 2, "F", $con);
																		}else {
																			error_log("Success in BillPay rollback_wallet for: ".$journal_entry_id);
																			//Insert into account_rollback table with success status
																		}

																		$statusCode = $httpcode;
																		$responseDescription = "HTTP Protocol Error";
																		error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																		$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
																		error_log("update_query = ".$update_query);
																		$update_query_result = mysqli_query($con, $update_query);

																		$approver_comments = "PE: ".$statusCode." - ".$responseDescription;
																		error_log("update_query = ".$update_query);
																		$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $transactionId";
																		$update_query_result = mysqli_query($con, $update_query);
																			
																		$response["statusCode"] = $statusCode;
																		$response["result"] = "Error";
																		$response["message"] = "Error in connection to BillPay API Server";
																		$response["partnerId"] = $partnerId;
																		$response["signature"] = $server_signature;
																	}
																}else {
																	error_log("curl_error != 0 ");
																	$rollBackOrder = "Y";
																	$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																	if ( $gl_reverse_repsonse != 0 ) {
																		error_log("Error in BillPay gl_reverse for: ".$journal_entry_id);
																		insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $totalAmount, $con);
																	}else {
																		error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																	}
																	//Rollback wallet update
																	$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId);
																	if ( $update_wallet != 0 ) {
																		error_log("Error in BillPay rollback_wallet for: ".$journal_entry_id);
																		insertaccountrollback($userId, $journal_entry_id, $acc_trans_type, $totalAmount, 2, "F", $con);
																	}else {
																		error_log("Success in BillPay rollback_wallet for: ".$journal_entry_id);
																		//Insert into account_rollback table with success status
																	}

																	$statusCode = $curl_error;
																	$responseDescription = "CURL Execution Error";
																	$approver_comments = "PE: ".$statusCode." - ".$responseDescription;
																	error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																	$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
																	error_log("update_query = ".$update_query);
																	$update_query_result = mysqli_query($con, $update_query);
																	
																	$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $transactionId";
																	error_log("update_query = ".$update_query);
																	$update_query_result = mysqli_query($con, $update_query);

																	$response["statusCode"] = $statusCode;
																	$response["result"] = "Error";
																	$response["message"] = "Error in communication protocol";
																	$response["partnerId"] = $partnerId;
																	$response["signature"] = $server_signature;
																}
															}
															else {
																//Not able to Update Wallet
																$response["statusCode"] = "305";
																$response["result"] = "Error";
																$response["message"] = "DB Error in updating wallet";
																$response["partnerId"] = $partnerId;
																$response["signature"] = $server_signature;
															}
														}
														else {
															//Not able to get acc_trans_type
															$response["statusCode"] = "310";
															$response["result"] = "Error";
															$response["message"] = "DB Error in getting acc_trans_type";
															$response["partnerId"] = $partnerId;
															$response["signature"] = $server_signature;
														}

														//Clear bp_service_order
														if ( $rollBackOrder == "Y") {
															//Roll back of BP Service Order error
															$bp_services_order_delete_query = "delete from bp_service_order where bp_service_order_no = $bp_service_order_no";
															error_log("bp_services_order_delete_query = " . $bp_services_order_delete_query);
															$bp_services_order_delete_result = mysqli_query($con, $bp_services_order_delete_query);
															if ( $bp_services_order_delete_result ) {
																error_log("bp_service_order delete successful");
															}else {
																error_log("bp_service_order delete failure = ".mysqli_error());
															}
														}
													}else {
														//Error in inserting Bp Service Order Result
														$response["statusCode"] = "315";
														$response["result"] = "Error";
														$response["message"] = "DB Error in BP Service Order Result";
														$response["partnerId"] = $partnerId;
														$response["signature"] = $server_signature;
													}
												}else {
													//Error in getting journal_entry_id
													$response["statusCode"] = "320";
													$response["result"] = "Error";
													$response["message"] = "DB Error in gettin journal entry id";
													$response["partnerId"] = $partnerId;
													$response["signature"] = $server_signature;
												}
											}
											else {
												//Error in Generating Bp Service Order
												$response["statusCode"] = "325";
												$response["result"] = "Error";
												$response["message"] = "DB Error in BP Service Order Request";
												$response["partnerId"] = $partnerId;
												$response["signature"] = $server_signature;
											}
										}else {
											//Error in Updating Bp Request Table
											$response["statusCode"] = "330";
											$response["result"] = "Error";
											$response["message"] = "DB Error in updating BP Request";
											$response["partnerId"] = $partnerId;
											$response["signature"] = $server_signature;
										}
									}else {
										//Error in inserting Bp Transaction Log
										$response["statusCode"] = "335";
										$response["result"] = "Error";
										$response["message"] = "DB Error in BP Trans Log";
										$response["partnerId"] = $partnerId;
										$response["signature"] = $server_signature;
									}
								}else {
									//Error in generating Bp Transaction Log
									$response["statusCode"] = "340";
									$response["result"] = "Error";
									$response["message"] = "DB Error in BP Trans Log Request";
									$response["partnerId"] = $partnerId;
									$response["signature"] = $server_signature;
								}
							}else {
								// Insufficient Agent Available Balance
								$response["statusCode"] = "350";
								$response["result"] = "Error";
								$response["message"] = "Insufficient Agent Available Balance";
								$response["partnerId"] = $partnerId;
								$response["signature"] = $server_signature;
							}
						}
						else {
							//Agent Available Balance is not available
							$response["statusCode"] = "360";
							$response["result"] = "Error";
							$response["message"] = "Agent Available Balance is not available";
							$response["partnerId"] = $partnerId;
							$response["signature"] = $server_signature;
						}
					}
					else {
						// Error in accessing Agent Info & Wallet details.
						$resp_message = "";
						if ( $agent_info_wallet_status == 1 ) {
							$resp_message = "Agent status is not active";
						}else if ( $agent_info_wallet_status == 2 ) {
							$resp_message = "Agent is blocked";
						}else if ( $agent_info_wallet_status == 3 ) {
							$resp_message = "Agent Wallet is not active";
						}else if ( $agent_info_wallet_status == 4 ) {
							$resp_message = "Agent Wallet is blocked";
						}else {
							$resp_message = "Error in accessing Agent Info & Wallet details. Contact Kadick Admin";
						}
						$response["statusCode"] = "370";
						$response["result"] = "Error";
						$response["message"] = $resp_message;
						$response["partnerId"] = $partnerId;
						$response["signature"] = $server_signature;
					}
				}else {
					// Invalid Singature
					$response["statusCode"] = "390";
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
        	else if(isset($data -> operation) && $data -> operation == 'BP_PAYANT_TV_PAYMENT_CONFIRM') {
			error_log("inside operation == BP_PAYANT_TV_PAYMENT_CONFIRM method");
			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
				&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
				&& isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
				&& isset($data->stateId) && !empty($data->stateId) && isset($data->serviceFeatureId) && !empty($data->serviceFeatureId)
				&& isset($data->billerId) && !empty($data->billerId) && isset($data->productCode) && !empty($data->productCode) 
				&& isset($data->billerName) && !empty($data->billerName) && isset($data->productName) && !empty($data->productName)
				&& isset($data->requestAmount) && !empty($data->requestAmount) && isset($data->totalAmount) && !empty($data->totalAmount) 
				&& isset($data->bpAccountNo) && !empty($data->bpAccountNo) && isset($data->bpAccountName) && !empty($data->bpAccountName)
				&& isset($data->email) && !empty($data->email) && isset($data->mobile) && !empty($data->mobile)
				&& isset($data->serviceFeatureCode) && !empty($data->serviceFeatureCode) && isset($data->partnerId) && !empty($data->partnerId)
				//&& isset($data->bpInvoicePeriod) && !empty($data->bpInvoicePeriod) && isset($data->bpAmount) && !empty($data->bpAmount)
				&& isset($data->transactionId) && !empty($data->transactionId) && isset($data->location) && !empty($data->location)
			){
				ini_set('max_execution_time', 120);
				set_time_limit(120);
				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$parentCode = $data->parentCode;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$billerGroupId = $data->billerGroupId;
				$bpBillerId = $data->billerId;
				$bpProductCode = $data->productCode;
				$bpProductName = $data->productName;
				$bpBillerName = $data->billerName;
				$bpInvoicePeriod = $data->bpInvoicePeriod;
				$bpAmount = $data->bpAmount;
				$requestAmount = $data->requestAmount;
				$totalAmount = $data->totalAmount;
				$agentCharge = $data->agentCharge;
				$amsCharge = $data->amsCharge;
				$serviceCharge = $data->serviceCharge;
				$otherCharge = $data->otherCharge;
				$partnerCharge = $data->partnerCharge;
				$stampCharge = $data->stampCharge;
				$email = $data->email;
				$mobile = $data->mobile;
				$serviceFeatureCode = $data->serviceFeatureCode;
				$partnerId = $data->partnerId;
				$bpAccountNo = $data->bpAccountNo;
				$bpAccountName = $data->bpAccountName;
				$location = $data->location;
				$transactionId = $data->transactionId;
				$serviceFeatureId = $data->serviceFeatureId;
				$session_validity = AGENT_SESSION_VALID_TIME;
				$bp_service_order_no = 0;
				$bpProductId = $bpBillerId;

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
					$validate_result = validateKey1($key1, $userId, $session_validity, '5', $con);
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
					
					$daily_check_result = checkDailyLimit($userId, $requestAmount, $con);
					//$daily_check_result = 0;
					error_log("daily_check_result = ".$daily_check_result);
					if ( $daily_check_result != 0 ){
						// Exceeded Daily Limit
						$response["statusCode"] = "998";
						$response["result"] = "Error";
						$response["message"] = "Failure: Exceeded Daily Limit";
						$response["signature"] = 0;
						error_log(json_encode($response));
						echo json_encode($response);
						return;
					}

					$txtType = "E";
					if ($parentCode == "") {
						$partyCount = 2;
					}else {
						$partyCount = 3;
					}
					$db_flexiRate = "N";
					$flexi_rate_query = "select state_flexi_rate_id from state_flexi_rate where state_id = $stateId and (service_feature_id is null or (service_feature_id = $serviceFeatureId)) and active = 'Y' and (start_date is null or (start_date is not null and date(start_date) <= current_date())) and (expiry_date is null or (expiry_date is not null and date(expiry_date) >= current_date())) order by state_flexi_rate_id limit 1";
					error_log("flexi_rate_query query = ".$flexi_rate_query);
					$flexi_rate_result = mysqli_query($con, $flexi_rate_query);
					if ($flexi_rate_result) {
						$flexi_rate_count = mysqli_num_rows($flexi_rate_result);
						if($flexi_rate_count > 0) {					
							$db_flexiRate = "Y";
						}
					}

					if ( "Y" == $flexiRate || "Y" == $db_flexiRate ) {
						$txtType = "F";
					}
					
					$agent_info_wallet_status = check_agent_info_wallet_status($partyType, $partyCode, $con);
					$rollBackOrder = "N";
					if ($agent_info_wallet_status == 0 ) {
						//Checking available_balance
						$available_balance = check_agent_available_balance($userId, $con);
						error_log("available_balance response for = ".$available_balance);
						if ( $available_balance >= 0 || $available_balance != "" || $available_balance != null ) {
							if ( floatval($totalAmount) <= floatval($available_balance) ) {
								$bp_trans_log_id = generate_seq_num(3100, $con);
																		
								$data = array();
								$data['serviceCategoryId'] = $bpBillerId;
								$data['smartCard'] = $bpAccountNo;
								$data['amount'] = $requestAmount;
								$data['phone'] = $mobile;
								$data['invoicePeriod'] = $bpInvoicePeriod;
								$data['name'] = $bpAccountName;
								$data['bundleCode'] = $bpProductCode;
								$data['countryId'] = $countryId;
								$data['stateId'] = $stateId;
								$data['userId'] = $userId;
								$data['localGovtId'] = ADMIN_LOCAL_GOVT_ID;
									
								$request_message = json_encode($data);
								if ($bp_trans_log_id > 0)  {
									error_log("bp_trans_log_id = ".$bp_trans_log_id);
									$request_message = mysqli_real_escape_string($con, $request_message);
									$bp_trans_log_query = "INSERT INTO bp_trans_log (bp_trans_log_id, partner_id, party_type, party_code, service_feature_code, country_id, state_id, amount, request_message, message_send_time, create_user, create_time) VALUES ($bp_trans_log_id, $partnerId, '$partyType', '$partyCode', '$serviceFeatureCode', $countryId, $stateId, $totalAmount, left('PayAnt TV Bill Payment = $request_message', 1000), now(), $userId, now())";
									error_log("bp_trans_log_query = ". $bp_trans_log_query);
									$bp_trans_log_result = mysqli_query($con, $bp_trans_log_query);
									if( $bp_trans_log_result ) {
										if ( $txtType == "F" ) {
											$bp_request_update_query = "UPDATE bp_request SET service_charge = $amsCharge+$agentCharge, partner_charge = $partnerCharge, other_charge = $otherCharge, bp_trans_log_id2 = $bp_trans_log_id WHERE bp_request_id = ".$transactionId;
										}else {
											$bp_request_update_query = "UPDATE bp_request SET service_charge = $amsCharge, partner_charge = $partnerCharge, other_charge = $otherCharge,  bp_trans_log_id2 = $bp_trans_log_id WHERE bp_request_id = ".$transactionId;
										}
										error_log("bp_request_update_query = ".$bp_request_update_query);
										$bp_request_update_result = mysqli_query($con, $bp_request_update_query);		
										if($bp_request_update_result) {						
											$bp_service_order_no = generate_seq_num(3300, $con);
											if ( $bp_service_order_no > 0)  {
												error_log("Inside BP Service Order ==> BP_SERVICE_ORDER_NO = ".$bp_service_order_no);
												$acc_trans_type = 'BPAY1';
												$firstpartycode = $partyCode;
												$firstpartytype = $partyType;
												$secondpartycode = $parentCode;
												if(($secondpartycode == "") || empty($secondpartycode) || ($secondpartycode = null)) {
													$secondpartycode = "";
													$secondpartytype = "";
												}
												else {
													$secondpartytype = substr($secondpartycode,0);
												}
												$journal_entry_id = process_glentry($acc_trans_type, $bp_service_order_no, $firstpartycode, $firstpartytype, $secondpartycode, $secondpartytype, $narration, $totalAmount, $userId, $con);
												if($journal_entry_id > 0) {
													$journal_entry_error = "N";	
													$new_ams_charge = floatval($amsCharge) - floatval($agentCharge);
													if ( $txtType == "F" ) {
														$bp_service_order_query = "INSERT INTO bp_service_order (bp_service_order_no, service_feature_code, bp_biller_id, bp_product_id, partner_id, user_id, total_amount, request_amount, ams_charge, partner_charge, other_charge, agent_charge, stamp_charge, date_time) VALUES ($bp_service_order_no, '$serviceFeatureCode', $bpBillerId, $bpProductId, $partnerId, $userId, $totalAmount, $requestAmount, $amsCharge, $partnerCharge, $otherCharge, $agentCharge, $stampCharge, now())";
													}else {
														$bp_service_order_query = "INSERT INTO bp_service_order (bp_service_order_no, service_feature_code, bp_biller_id, bp_product_id, partner_id, user_id, total_amount, request_amount, ams_charge, partner_charge, other_charge, agent_charge, stamp_charge, date_time) VALUES ($bp_service_order_no, '$serviceFeatureCode', $bpBillerId, $bpProductId, $partnerId, $userId, $totalAmount, $requestAmount, $new_ams_charge, $partnerCharge, $otherCharge, $agentCharge, $stampCharge, now())";
													}
													error_log("bp_service_order_query = ".$bp_service_order_query);
													$bp_service_order_result = mysqli_query($con, $bp_service_order_query);
													if( $bp_service_order_result ) {
														error_log("inside success bp_service_order table entry");
														$get_acc_trans_type = getAcccTransType($acc_trans_type, $con);
														error_log("get_acc_trans_type = ".$get_acc_trans_type);	
														if($get_acc_trans_type != "-1"){
															$split = explode("|",$get_acc_trans_type);
															$ac_factor = $split[0];
															$cb_factor = $split[1];
															$acc_trans_type_id = $split[2];
															$update_wallet = walletupdateWithTransaction($acc_trans_type, $cb_factor, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId, $journal_entry_id);
															if( $update_wallet == 0 ) {	
																//Inside success wallet update
																error_log("bp_service_order_no = ".$bp_service_order_no);
																	
																$url = BPAPI_PAYANT_SERVER_TV_BILL_PAYMENT_URL;
																$tsec = time();
																$raw_data1 = BPAPI_SERVER_APP_PASSWORD."|".BPAPI_SERVER_APP_USERNAME."|".$tsec;
																error_log("raw_data1 = ".$raw_data1);
																$key1 = base64_encode($raw_data1);
																error_log("key1 = ".$key1);
																error_log("before calling post");
																error_log("url = ".$url);
																$data['key1'] = $key1;
																$data['signature'] = $local_signature;
																error_log("request sent ==> ".json_encode($data));
																$ch = curl_init($url);
																curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
																curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
																curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
																curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, PAYANT_CURL_CONNECTION_TIMEOUT);
																curl_setopt($ch, CURLOPT_TIMEOUT, PAYANT_CURL_TIMEOUT);
																$curl_response = curl_exec($ch);
																$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
																$curl_error = curl_errno($ch);
																curl_close($ch);
																if ( $curl_error === 0 ) {
																	error_log("curl_error == 0 ");
																	error_log("response received = ".$curl_response);
																	error_log("code = ".$httpcode);
																	if ( $httpcode == 200 ) {
																		error_log("inside httpcode == 200");
																		$api_response = json_decode($curl_response, true);
																		$statusCode = $api_response['responseCode'];
																		$responseDescription = $api_response['responseDescription'];
																		$statusMessage = $api_response['status']." - ".$api_response['message'];
																		error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																		error_log("response_received <=== ".$curl_response);
																		$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
																		error_log("update_query = ".$update_query);
																		$update_query_result = mysqli_query($con, $update_query);

																		if($statusCode === 0) {
																			
																			error_log("inside statusCode === 0");
																			$bp_resp_smartcard = $api_response['requestSmartcard'];
																			$bp_resp_bundle_code = $api_response['requestBundleCode'];
																			$bp_resp_amount = $api_response['requestAmount'];
																			$comments = $bpProductName;
																			$bp_resp_dataVendTime = $api_response['createdAt'];	
																			$bp_resp_transaction_id = $api_response['id'];
																			if ( strlen($bp_resp_transaction_id) > 45 ){
																				$bp_resp_transaction_id = "*".$bp_resp_transaction_id;
																			}
																			$bp_resp_reference_len = strlen($api_response['reference']);
																			$bp_resp_reference = "";
																			if ( $bp_resp_reference_len > 30 ) {
																				$bp_resp_reference = "*".substr($api_response['reference'], 0, 29);
																			}else {
																				$bp_resp_reference = $api_response['reference'];
																			}
																																				
																			$bp_resp_account_name = $api_response['verifyName'];
																			if ( strlen($bp_resp_account_name) > 70) {
																				$bp_resp_account_name = "*".$bp_resp_account_name;
																			}
																			$update_query = "UPDATE bp_request SET order_no = $bp_service_order_no, status = 'S', update_time = now(), bp_transaction_id = left('$bp_resp_transaction_id', 45), bp_account_no = left('$bp_resp_reference', 30), bp_account_name = left('$bp_resp_account_name', 70), session_id = '$bp_resp_dataVendTime', bp_bank_code = '$bp_resp_smartcard', comments = '$comments' WHERE bp_request_id = $transactionId";
																			error_log("update_query = ".$update_query);
																			$update_query_result = mysqli_query($con, $update_query);

																			$gl_post_return_value = process_glpost($journal_entry_id, $con);
																			if ( $gl_post_return_value == 0 ) {
																				error_log("Success in BillPay gl_post for: ".$journal_entry_id);
																			}
																			else{
																				error_log("Error in gl_post, with gl_post_return_value = ".$gl_post_return_value);
																				insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AP", "W", "N", $totalAmount, $con);
																			}

																			$order_post_result = post_bporder($bp_service_order_no, $con);
																			if ( $order_post_result == 0 ) { 
																				error_log("Success in BillPay post_bporder for: ".$bp_service_order_no);
																			}else {
																				error_log("Error in BillPay post_bporder for: ".$bp_service_order_no);
																			}
																			
																			$checking_feature_value_response = checking_feature_value($userId, $countryId, $stateId, $partyCount, $serviceFeatureId, $partnerId, $requestAmount, $txtType, $con);
																			$checking_feature_value_response_split = explode("#",$checking_feature_value_response);
																			$rateparties_details = $checking_feature_value_response_split[1];
																			$service_insert_count = 0;
																			$serviceconfig = explode(",", $rateparties_details);
																			error_log("bil_pay serviceconfig = ".$serviceconfig);
																			
																			//Insert into bp_service_order_comm table
																			for($i = 0; $i < sizeof($serviceconfig); $i++) {
																				$bpOrder_flag = insertBillPayServiceOrderComm($bp_service_order_no, $serviceconfig[$i], $journal_entry_id, $txtType, $agentCharge, $ams_charge, $con);
																				if ( $bpOrder_flag == 0 ) {
																					++$service_insert_count;
																				}
																			}
																			if ( $service_insert_count == sizeof($serviceconfig) ) {
																				error_log("All entries for service_order insert in commission table. Insert count = ".$service_insert_count);
																			}else {
																				error_log("Not all entries for service_order insert in commission table. Insert count = ".$service_insert_count.", serviceConfig count = ".sizeof($serviceconfig));
																			}
																			$pcu_result = process_bp_comm_update($bp_service_order_no, $con);
																			if ( $pcu_result > 0 ) {
																				if ( $pcu_result == sizeof($serviceconfig) ) {
																					error_log("All bp_service_order_comm updates are completed. Count = ".$pcu_result);
																				}else {
																					error_log("Warning bp_service_order_comm updates are not matching completed. Count = ".$pcu_result);
																				}
																			}else {
																				error_log("Error in bp_service_order_comm records insert. Insert Count = ".$pcu_result);
																			}
																			$availableBalance = check_party_available_balance($partyType, $userId, $con);
																			$orderTime = getBpOrderTime($bp_service_order_no, $con);

																			$response["statusCode"] = "0";
																			$response["result"] = "SUCCESS";
																			$response["message"] = $api_response['responseDescription'];
																			$response["processingStartTime"] = $api_response['processingStartTime'];
																				
																			$response["transactionId"] = $transactionId;
																			$response["orderNo"] = $bp_service_order_no;
																			$response["status"] = $api_response['status'];
																			
																			$response["verifyBasketId"] = $api_response['verifyBasketId'];
																			$response["verifyAccountNumber"] = $api_response['verifyAccountNumber'];
																			$response["verifyCustomerNumber"] = $api_response['verifyCustomerNumber'];
																			$response["verifyBoxOffice"] = $api_response['verifyBoxOffice'];
																			$response["verifyInvoicePeriod"] = $api_response['verifyInvoicePeriod'];
																			$response["verifyTotalAmount"] = $api_response['verifyTotalAmount'];
																			$response["verifyDueDate"] = $api_response['verifyDueDate'];
																			$response["verifyBalanceDue"] = $api_response['verifyBalanceDue'];
																			$response["verifyStatus"] = $api_response['verifyStatus'];
																			$response["verifyName"] = $bp_resp_account_name;
																			
																			$response["responseRawData"] = $api_response['responseRawData'];
																			$response["responsePayloadMessage"] = $api_response['responsePayloadMessage'];
																			$response["responsePayloadStatus"] = $api_response['responsePayloadStatus'];
																			
																			$response["requestAmount"] = $api_response['requestAmount'];
																			$response["requestSmartcard"] = $bp_resp_smartcard;
																			$response["requestInvoicePeriod"] = $api_response['requestInvoicePeriod'];
																			$response["requestBundleCode"] = $api_response['requestBundleCode'];
																			$response["requestNumber"] = $api_response['requestNumber'];
																			$response["requestName"] = $api_response['requestName'];
																												
																			$response["id"] = $bp_resp_transaction_id;
																			$response["createdAt"] = $bp_resp_dataVendTime;
																			$response["reference"] = $bp_resp_reference;							
																			$response["serviceCategoryId"] = $api_response['serviceCategoryId'];
																			$response["availableBalance"] = $availableBalance;
																			
																			$response["partnerId"] = $partnerId;
																			$response["signature"] = $server_signature;
																		}
																		else {
																			if ( $statusCode == '') {
																				$statusCode = 50;
																			}
																			error_log("inside statusCode != 0");
																			$rollBackOrder = "Y";
																			$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																			if ( $gl_reverse_repsonse != 0 ) {
																				error_log("Error in BillPay gl_reverse for: ".$journal_entry_id);
																				insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $totalAmount, $con);
																			}else {
																				error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																			}
																			//Rollback wallet update
																			$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId);
																			if ( $update_wallet != 0 ) {
																				error_log("Error in BillPay rollback_wallet for: ".$journal_entry_id);
																				insertaccountrollback($userId, $journal_entry_id, $acc_trans_type, $totalAmount, 2, "F", $con);
																			}else {
																				error_log("Success in BillPay rollback_wallet for: ".$journal_entry_id);
																				//Insert into account_rollback table with success status
																			}

																			$approver_comments = "PC: ".$statusCode." - ".$responseDescription." @ ".$api_response['processingStartTime'];
																			$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $transactionId";
																			error_log("update_query = ".$update_query);
																			$update_query_result = mysqli_query($con, $update_query);
																			
																			$response["statusCode"] = $statusCode;
																			$response["result"] = "Error";
																			//$response["message"] = $responseDescription;
																			$response["message"] = $statusMessage;
																			$response["partnerId"] = $partnerId;
																			$response["signature"] = $server_signature;
																		}
																	}else {
																		error_log("inside httpcode != 200");
																		$rollBackOrder = "Y";

																		$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																		if ( $gl_reverse_repsonse != 0 ) {
																			error_log("Error in BillPay gl_reverse for: ".$journal_entry_id);
																			insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $totalAmount, $con);
																		}else {
																			error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																		}
																		//Rollback wallet update
																		$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId);
																		if ( $update_wallet != 0 ) {
																			error_log("Error in BillPay rollback_wallet for: ".$journal_entry_id);
																			insertaccountrollback($userId, $journal_entry_id, $acc_trans_type, $totalAmount, 2, "F", $con);
																		}else {
																			error_log("Success in BillPay rollback_wallet for: ".$journal_entry_id);
																			//Insert into account_rollback table with success status
																		}

																		$statusCode = $httpcode;
																		$responseDescription = "HTTP Protocol Error";
																		error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																		$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
																		error_log("update_query = ".$update_query);
																		$update_query_result = mysqli_query($con, $update_query);

																		$approver_comments = "PE: ".$statusCode." - ".$responseDescription;
																		error_log("update_query = ".$update_query);
																		$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $transactionId";
																		$update_query_result = mysqli_query($con, $update_query);
																			
																		$response["statusCode"] = $statusCode;
																		$response["result"] = "Error";
																		$response["message"] = "Error in connection to BillPay API Server";
																		$response["partnerId"] = $partnerId;
																		$response["signature"] = $server_signature;
																	}
																}else {
																	error_log("curl_error != 0 ");
																	$rollBackOrder = "Y";
																	$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																	if ( $gl_reverse_repsonse != 0 ) {
																		error_log("Error in BillPay gl_reverse for: ".$journal_entry_id);
																		insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $totalAmount, $con);
																	}else {
																		error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																	}
																	//Rollback wallet update
																	$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId);
																	if ( $update_wallet != 0 ) {
																		error_log("Error in BillPay rollback_wallet for: ".$journal_entry_id);
																		insertaccountrollback($userId, $journal_entry_id, $acc_trans_type, $totalAmount, 2, "F", $con);
																	}else {
																		error_log("Success in BillPay rollback_wallet for: ".$journal_entry_id);
																		//Insert into account_rollback table with success status
																	}

																	$statusCode = $curl_error;
																	$responseDescription = "CURL Execution Error";
																	$approver_comments = "PE: ".$statusCode." - ".$responseDescription;
																	error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																	$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
																	error_log("update_query = ".$update_query);
																	$update_query_result = mysqli_query($con, $update_query);
																	
																	$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $transactionId";
																	error_log("update_query = ".$update_query);
																	$update_query_result = mysqli_query($con, $update_query);

																	$response["statusCode"] = $statusCode;
																	$response["result"] = "Error";
																	$response["message"] = "Error in communication protocol";
																	$response["partnerId"] = $partnerId;
																	$response["signature"] = $server_signature;
																}
															}
															else {
																//Not able to Update Wallet
																$response["statusCode"] = "305";
																$response["result"] = "Error";
																$response["message"] = "DB Error in updating wallet";
																$response["partnerId"] = $partnerId;
																$response["signature"] = $server_signature;
															}
														}
														else {
															//Not able to get acc_trans_type
															$response["statusCode"] = "310";
															$response["result"] = "Error";
															$response["message"] = "DB Error in getting acc_trans_type";
															$response["partnerId"] = $partnerId;
															$response["signature"] = $server_signature;
														}

														//Clear bp_service_order
														if ( $rollBackOrder == "Y") {
															//Roll back of BP Service Order error
															$bp_services_order_delete_query = "delete from bp_service_order where bp_service_order_no = $bp_service_order_no";
															error_log("bp_services_order_delete_query = " . $bp_services_order_delete_query);
															$bp_services_order_delete_result = mysqli_query($con, $bp_services_order_delete_query);
															if ( $bp_services_order_delete_result ) {
																error_log("bp_service_order delete successful");
															}else {
																error_log("bp_service_order delete failure = ".mysqli_error());
															}
														}
													}else {
														//Error in inserting Bp Service Order Result
														$response["statusCode"] = "315";
														$response["result"] = "Error";
														$response["message"] = "DB Error in BP Service Order Result";
														$response["partnerId"] = $partnerId;
														$response["signature"] = $server_signature;
													}
												}else {
													//Error in getting journal_entry_id
													$response["statusCode"] = "320";
													$response["result"] = "Error";
													$response["message"] = "DB Error in gettin journal entry id";
													$response["partnerId"] = $partnerId;
													$response["signature"] = $server_signature;
												}
											}
											else {
												//Error in Generating Bp Service Order
												$response["statusCode"] = "325";
												$response["result"] = "Error";
												$response["message"] = "DB Error in BP Service Order Request";
												$response["partnerId"] = $partnerId;
												$response["signature"] = $server_signature;
											}
										}else {
											//Error in Updating Bp Request Table
											$response["statusCode"] = "330";
											$response["result"] = "Error";
											$response["message"] = "DB Error in updating BP Request";
											$response["partnerId"] = $partnerId;
											$response["signature"] = $server_signature;
										}
									}else {
										//Error in inserting Bp Transaction Log
										$response["statusCode"] = "335";
										$response["result"] = "Error";
										$response["message"] = "DB Error in BP Trans Log";
										$response["partnerId"] = $partnerId;
										$response["signature"] = $server_signature;
									}
								}else {
									//Error in generating Bp Transaction Log
									$response["statusCode"] = "340";
									$response["result"] = "Error";
									$response["message"] = "DB Error in BP Trans Log Request";
									$response["partnerId"] = $partnerId;
									$response["signature"] = $server_signature;
								}
							}else {
								// Insufficient Agent Available Balance
								$response["statusCode"] = "350";
								$response["result"] = "Error";
								$response["message"] = "Insufficient Agent Available Balance";
								$response["partnerId"] = $partnerId;
								$response["signature"] = $server_signature;
							}
						}
						else {
							//Agent Available Balance is not available
							$response["statusCode"] = "360";
							$response["result"] = "Error";
							$response["message"] = "Agent Available Balance is not available";
							$response["partnerId"] = $partnerId;
							$response["signature"] = $server_signature;
						}
					}
					else {
						// Error in accessing Agent Info & Wallet details.
						$resp_message = "";
						if ( $agent_info_wallet_status == 1 ) {
							$resp_message = "Agent status is not active";
						}else if ( $agent_info_wallet_status == 2 ) {
							$resp_message = "Agent is blocked";
						}else if ( $agent_info_wallet_status == 3 ) {
							$resp_message = "Agent Wallet is not active";
						}else if ( $agent_info_wallet_status == 4 ) {
							$resp_message = "Agent Wallet is blocked";
						}else {
							$resp_message = "Error in accessing Agent Info & Wallet details. Contact Kadick Admin";
						}
						$response["statusCode"] = "370";
						$response["result"] = "Error";
						$response["message"] = $resp_message;
						$response["partnerId"] = $partnerId;
						$response["signature"] = $server_signature;
					}
				}else {
					// Invalid Singature
					$response["statusCode"] = "390";
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
		else if(isset($data -> operation) && $data -> operation == 'BP_PAYANT_EDUCATION_PAYMENT_CONFIRM') {
			error_log("inside operation == BP_PAYANT_EDUCATION_PAYMENT_CONFIRM method");
			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
				&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
				&& isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
				&& isset($data->stateId) && !empty($data->stateId) && isset($data->serviceFeatureId) && !empty($data->serviceFeatureId)
				&& isset($data->billerId) && !empty($data->billerId) && isset($data->productId) && !empty($data->productId) 
				&& isset($data->billerName) && !empty($data->billerName) && isset($data->productName) && !empty($data->productName)
				&& isset($data->requestAmount) && !empty($data->requestAmount) && isset($data->totalAmount) && !empty($data->totalAmount) 
				//&& isset($data->agentCharge) && !empty($data->agentCharge) && isset($data->serviceCharge) && !empty($data->serviceCharge) 
				//&& isset($data->otherCharge) && !empty($data->otherCharge) && isset($data->stampCharge) && !empty($data->stampCharge) 
				&& isset($data->email) && !empty($data->email) && isset($data->mobile) && !empty($data->mobile)
				&& isset($data->serviceFeatureCode) && !empty($data->serviceFeatureCode) && isset($data->partnerId) && !empty($data->partnerId)
				&& isset($data->bpPins) && !empty($data->bpPins) && isset($data->location) && !empty($data->location)
				//&& isset($data->amsCharge) && !empty($data->amsCharge)
			){
				ini_set('max_execution_time', 120);
				set_time_limit(120);
				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$parentCode = $data->parentCode;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$billerGroupId = $data->billerGroupId;
				$bpBillerId = $data->billerId;
				$bpProductId = $data->productId;
				$bpBillerName = $data->billerName;
				$bpProductName = $data->productName;
				$requestAmount = $data->requestAmount;
				$accountNo = $data->accountNo;
				$totalAmount = $data->totalAmount;
				$agentCharge = $data->agentCharge;
				$amsCharge = $data->amsCharge;
				$serviceCharge = $data->serviceCharge;
				$otherCharge = $data->otherCharge;
				$partnerCharge = $data->partnerCharge;
				$stampCharge = $data->stampCharge;
				$accountName = $data->bpAccountName;
				$email = $data->email;
				$mobile = $data->mobile;
				$serviceFeatureCode = $data->serviceFeatureCode;
				$partnerId = $data->partnerId;
				$bpPins = $data->bpPins;
				$location = $data->location;
				$transactionId = $data->transactionId;
				$serviceFeatureId = $data->serviceFeatureId;
				$session_validity = AGENT_SESSION_VALID_TIME;
				$lgaId = ADMIN_LOCAL_GOVT_ID;
				$bp_service_order_no = 0;

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
					$validate_result = validateKey1($key1, $userId, $session_validity, '5', $con);
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
					
					$daily_check_result = checkDailyLimit($userId, $requestAmount, $con);
					//$daily_check_result = 0;
					error_log("daily_check_result = ".$daily_check_result);
					if ( $daily_check_result != 0 ){
						// Exceeded Daily Limit
						$response["statusCode"] = "998";
						$response["result"] = "Error";
						$response["message"] = "Failure: Exceeded Daily Limit";
						$response["signature"] = 0;
						error_log(json_encode($response));
						echo json_encode($response);
						return;
					}

					$txtType = "E";
					if ($parentCode == "") {
						$partyCount = 2;
					}else {
						$partyCount = 3;
					}
					$db_flexiRate = "N";
					$flexi_rate_query = "select state_flexi_rate_id from state_flexi_rate where state_id = $stateId and (service_feature_id is null or (service_feature_id = $serviceFeatureId)) and active = 'Y' and (start_date is null or (start_date is not null and date(start_date) <= current_date())) and (expiry_date is null or (expiry_date is not null and date(expiry_date) >= current_date())) order by state_flexi_rate_id limit 1";
					error_log("flexi_rate_query query = ".$flexi_rate_query);
					$flexi_rate_result = mysqli_query($con, $flexi_rate_query);
					if ($flexi_rate_result) {
						$flexi_rate_count = mysqli_num_rows($flexi_rate_result);
						if($flexi_rate_count > 0) {					
							$db_flexiRate = "Y";
						}
					}

					if ( "Y" == $flexiRate || "Y" == $db_flexiRate ) {
						$txtType = "F";
					}
					
					$agent_info_wallet_status = check_agent_info_wallet_status($partyType, $partyCode, $con);
					$rollBackOrder = "N";
					if ($agent_info_wallet_status == 0 ) {
						//Checking available_balance
						$available_balance = check_agent_available_balance($userId, $con);
						error_log("available_balance response for = ".$available_balance);
						if ( $available_balance >= 0 || $available_balance != "" || $available_balance != null ) {
							if ( floatval($totalAmount) <= floatval($available_balance) ) {
								$bp_trans_log_id = generate_seq_num(3100, $con);
																		
								$data = array();
								$data['serviceCategoryId'] = $bpProductId;
								$data['pins'] = $bpPins;
								$data['amount'] = $requestAmount;
								$data['phone'] = $mobile;
								$data['countryId'] = $countryId;
								$data['stateId'] = $stateId;
								$data['userId'] = $userId;
								$data['localGovtId'] = ADMIN_LOCAL_GOVT_ID;
									
								$request_message = json_encode($data);
								if ($bp_trans_log_id > 0)  {
									error_log("bp_trans_log_id = ".$bp_trans_log_id);
									$request_message = mysqli_real_escape_string($con, $request_message);
									$bp_trans_log_query = "INSERT INTO bp_trans_log (bp_trans_log_id, partner_id, party_type, party_code, service_feature_code, country_id, state_id, amount, request_message, message_send_time, create_user, create_time) VALUES ($bp_trans_log_id, $partnerId, '$partyType', '$partyCode', '$serviceFeatureCode', $countryId, $stateId, $totalAmount, left('PayAnt Education Payment = $request_message', 1000), now(), $userId, now())";
									error_log("bp_trans_log_query = ". $bp_trans_log_query);
									$bp_trans_log_result = mysqli_query($con, $bp_trans_log_query);
									if( $bp_trans_log_result ) {
										$bp_request_id = generate_seq_num(3200, $con);
										$bp_service_order_no = generate_seq_num(3300, $con);
										if ( $bp_request_id > 0)  {
											if ( $txtType == "F" ) {
												$bp_request_insert_query = "INSERT INTO bp_request (bp_request_id, bp_trans_log_id1, service_feature_code, bp_biller_id, bp_product_id, country_id, state_id, local_govt_id, request_amount, service_charge, partner_charge, other_charge, total_amount, account_name, account_no, mobile_no, user_id, status, create_time, order_no) VALUES ($bp_request_id, $bp_trans_log_id, '$serviceFeatureCode', $bpBillerId, $bpProductId, $countryId, $stateId, $lgaId, $requestAmount, $amsCharge+$agentCharge, $partnerCharge, $otherCharge, $totalAmount, '$bpPins', '$accountName', '$mobile', $userId, 'I', now(), $bp_service_order_no)";
											}else {
												$bp_request_insert_query = "INSERT INTO bp_request (bp_request_id, bp_trans_log_id1, service_feature_code, bp_biller_id, bp_product_id, country_id, state_id, local_govt_id, request_amount, service_charge, partner_charge, other_charge, total_amount, account_name, account_no, mobile_no, user_id, status, create_time, order_no) VALUES ($bp_request_id, $bp_trans_log_id, '$serviceFeatureCode', $bpBillerId, $bpProductId, $countryId, $stateId, $lgaId, $requestAmount, $amsCharge, $partnerCharge, $otherCharge, $totalAmount, '$bpPins', '$accountName', '$mobile', $userId, 'I', now(), $bp_service_order_no)";
											}
											error_log("bp_request_insert_query = ".$bp_request_insert_query);
											$bp_request_insert_result = mysqli_query($con, $bp_request_insert_query);		
											if($bp_request_insert_result) {						
												if ( $bp_service_order_no > 0)  {
													error_log("Inside BP Service Order ==> BP_SERVICE_ORDER_NO = ".$bp_service_order_no);
													$acc_trans_type = 'BPAY1';
													$firstpartycode = $partyCode;
													$firstpartytype = $partyType;
													$secondpartycode = $parentCode;
													if(($secondpartycode == "") || empty($secondpartycode) || ($secondpartycode = null)) {
														$secondpartycode = "";
														$secondpartytype = "";
													}
													else {
														$secondpartytype = substr($secondpartycode,0);
													}
													$journal_entry_id = process_glentry($acc_trans_type, $bp_service_order_no, $firstpartycode, $firstpartytype, $secondpartycode, $secondpartytype, $narration, $totalAmount, $userId, $con);
													if($journal_entry_id > 0) {
														$journal_entry_error = "N";	
														$new_ams_charge = floatval($amsCharge) - floatval($agentCharge);
														if ( $txtType == "F" ) {
															$bp_service_order_query = "INSERT INTO bp_service_order (bp_service_order_no, service_feature_code, bp_biller_id, bp_product_id, partner_id, user_id, total_amount, request_amount, ams_charge, partner_charge, other_charge, agent_charge, stamp_charge, date_time) VALUES ($bp_service_order_no, '$serviceFeatureCode', $bpBillerId, $bpProductId, $partnerId, $userId, $totalAmount, $requestAmount, $amsCharge, $partnerCharge, $otherCharge, $agentCharge, $stampCharge, now())";
														}else {
															$bp_service_order_query = "INSERT INTO bp_service_order (bp_service_order_no, service_feature_code, bp_biller_id, bp_product_id, partner_id, user_id, total_amount, request_amount, ams_charge, partner_charge, other_charge, agent_charge, stamp_charge, date_time) VALUES ($bp_service_order_no, '$serviceFeatureCode', $bpBillerId, $bpProductId, $partnerId, $userId, $totalAmount, $requestAmount, $new_ams_charge, $partnerCharge, $otherCharge, $agentCharge, $stampCharge, now())";
														}
														error_log("bp_service_order_query = ".$bp_service_order_query);
														$bp_service_order_result = mysqli_query($con, $bp_service_order_query);
														if( $bp_service_order_result ) {
															error_log("inside success bp_service_order table entry");
															$get_acc_trans_type = getAcccTransType($acc_trans_type, $con);
															error_log("get_acc_trans_type = ".$get_acc_trans_type);	
															if($get_acc_trans_type != "-1"){
																$split = explode("|",$get_acc_trans_type);
																$ac_factor = $split[0];
																$cb_factor = $split[1];
																$acc_trans_type_id = $split[2];
																$update_wallet = walletupdateWithTransaction($acc_trans_type, $cb_factor, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId, $journal_entry_id);
																if( $update_wallet == 0 ) {	
																	//Inside success wallet update
																	error_log("bp_service_order_no = ".$bp_service_order_no);
																	
																	$url = BPAPI_PAYANT_SERVER_BILL_PAYMENT_EDUCATION_URL;
																	$tsec = time();
																	$raw_data1 = BPAPI_SERVER_APP_PASSWORD."|".BPAPI_SERVER_APP_USERNAME."|".$tsec;
																	//error_log("raw_data1 = ".$raw_data1);
																	$key1 = base64_encode($raw_data1);
																	error_log("key1 = ".$key1);
																	error_log("before calling post");
																	error_log("url = ".$url);
																	$data['key1'] = $key1;
																	$data['signature'] = $local_signature;
																	error_log("request sent ==> ".json_encode($data));
																	$ch = curl_init($url);
																	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
																	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
																	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
																	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, PAYANT_CURL_CONNECTION_TIMEOUT);
																	curl_setopt($ch, CURLOPT_TIMEOUT, PAYANT_CURL_TIMEOUT);
																	$curl_response = curl_exec($ch);
																	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
																	$curl_error = curl_errno($ch);
																	curl_close($ch);
																	if ( $curl_error === 0 ) {
																		error_log("curl_error == 0 ");
																		error_log("response received = ".$curl_response);
																		error_log("code = ".$httpcode);
																		if ( $httpcode == 200 ) {
																			error_log("inside httpcode == 200");
																			$api_response = json_decode($curl_response, true);
																			$statusCode = $api_response['responseCode'];
																			$responseDescription = $api_response['responseDescription'];
																			$statusMessage = $api_response['status']." - ".$api_response['message'];
																			error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																			error_log("response_received <=== ".$curl_response);
																			$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
																			error_log("update_query = ".$update_query);
																			$update_query_result = mysqli_query($con, $update_query);

																			if($statusCode === 0) {
																			
																				error_log("inside statusCode === 0");
																				$bp_resp_dataVendTime = $api_response['createdAt'];	
																				$bp_resp_transaction_id = $api_response['id'];
																				if ( strlen($bp_resp_transaction_id) > 45 ){
																					$bp_resp_transaction_id = "*".$bp_resp_transaction_id;
																				}
																				$bp_resp_account_no = $api_response['reference']." / ".$api_response['responseDataVendDataExchangeReference'];
																				$bp_resp_account_no_len = strlen($bp_resp_account_no);
																				$bp_resp_reference = "";
																				$bp_resp_dataVendRef = "";
																				if ( $bp_resp_account_no_len > 30 ) {
																					$bp_resp_account_no = "*".$bp_resp_account_no;
																					$bp_resp_reference_len = strlen($api_response['reference']);
																					if ( $bp_resp_reference_len <= 27 ) {
																						$bp_resp_reference = "*".$api_response['reference'];
																						$bp_resp_dataVendRef_len = 27 - $bp_resp_reference_len;
																						if ( $bp_resp_dataVendRef_len > 1 ) {
																							$bp_resp_dataVendRef = substr($api_response['responseDataVendDataExchangeReference'], 0, $bp_resp_dataVendRef_len-1);
																						}
																					}else {
																						$bp_resp_reference = substr($api_response['reference'], 0, 25);
																						$bp_resp_reference = "*".$bp_resp_reference;
																					}
																				}else {
																					$bp_resp_reference = $api_response['reference'];
																					$bp_resp_dataVendRef = $api_response['responseDataVendDataExchangeReference'];
																				}
																				$bp_resp_account_no_new = $bp_resp_reference." / ".$bp_resp_dataVendRef;
																			
																				$bp_resp_bank_code = $api_response['pinSerialNumber'];
																				if ( empty($bp_resp_bank_code) || !isset($bp_resp_bank_code) ){
																					$bp_resp_bank_code = "-";
																				}
																				if ( strlen($bp_resp_bank_code) > 45 ) {
																					$bp_resp_bank_code = "*".$bp_resp_bank_code;
																				}
																				$bp_resp_account_name = $api_response['responseDataVendDataStatusMessage'];
																				if ( strlen($bp_resp_account_name) > 70) {
																					$bp_resp_account_name = "*".$bp_resp_account_name;
																				}
																				$bp_resp_comments = $api_response['pinCode'];
																				if ( strlen($bp_resp_comments) > 256) {
																					$bp_resp_comments = "*".$bp_resp_comments;
																				} 
																				$bp_resp_approver_comments = "AV Ref: ".$api_response['responseDataVendResponseAvref'].", Amount: ".$api_response['amount'].", Vend Amount: ".$api_response['responseDataVendResponseAmount'];
																				$update_query = "UPDATE bp_request SET order_no = $bp_service_order_no, status = 'S', update_time = now(), bp_transaction_id = left('$bp_resp_transaction_id', 45), bp_account_no = left('$bp_resp_account_no_new', 30), bp_bank_code = left('$bp_resp_bank_code', 45), bp_account_name = left('$bp_resp_account_name', 70), comments = left('$bp_resp_comments', 256), approver_comments = left('$bp_resp_approver_comments', 256), session_id = '$bp_resp_dataVendTime' WHERE bp_request_id = $bp_request_id";
																				error_log("update_query = ".$update_query);
																				$update_query_result = mysqli_query($con, $update_query);

																				$gl_post_return_value = process_glpost($journal_entry_id, $con);
																				if ( $gl_post_return_value == 0 ) {
																					error_log("Success in BillPay gl_post for: ".$journal_entry_id);
																				}
																				else{
																					error_log("Error in gl_post, with gl_post_return_value = ".$gl_post_return_value);
																					insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AP", "W", "N", $totalAmount, $con);
																				}

																				$order_post_result = post_bporder($bp_service_order_no, $con);
																				if ( $order_post_result == 0 ) { 
																					error_log("Success in BillPay post_bporder for: ".$bp_service_order_no);
																				}else {
																					error_log("Error in BillPay post_bporder for: ".$bp_service_order_no);
																				}
																			
																				$checking_feature_value_response = checking_feature_value($userId, $countryId, $stateId, $partyCount, $serviceFeatureId, $partnerId, $requestAmount, $txtType, $con);
																				$checking_feature_value_response_split = explode("#",$checking_feature_value_response);
																				$rateparties_details = $checking_feature_value_response_split[1];
																				$service_insert_count = 0;
																				$serviceconfig = explode(",", $rateparties_details);
																				error_log("bil_pay education serviceconfig = ".$serviceconfig);
																			
																				//Insert into bp_service_order_comm table
																				for($i = 0; $i < sizeof($serviceconfig); $i++) {
																					$bpOrder_flag = insertBillPayServiceOrderComm($bp_service_order_no, $serviceconfig[$i], $journal_entry_id, $txtType, $agentCharge, $ams_charge, $con);
																					if ( $bpOrder_flag == 0 ) {
																						++$service_insert_count;
																					}
																				}	
																				if ( $service_insert_count == sizeof($serviceconfig) ) {
																					error_log("All entries for service_order insert in commission table. Insert count = ".$service_insert_count);
																				}else {
																					error_log("Not all entries for service_order insert in commission table. Insert count = ".$service_insert_count.", serviceConfig count = ".sizeof($serviceconfig));
																				}
																				$pcu_result = process_bp_comm_update($bp_service_order_no, $con);
																				if ( $pcu_result > 0 ) {
																					if ( $pcu_result == sizeof($serviceconfig) ) {
																						error_log("All bp_service_order_comm updates are completed. Count = ".$pcu_result);
																					}else {
																						error_log("Warning bp_service_order_comm updates are not matching completed. Count = ".$pcu_result);
																					}
																				}else {
																					error_log("Error in bp_service_order_comm records insert. Insert Count = ".$pcu_result);
																				}
																				$availableBalance = check_party_available_balance($partyType, $userId, $con);
																				$orderTime = getBpOrderTime($bp_service_order_no, $con);

																				$response["statusCode"] = "0";
																				$response["result"] = "SUCCESS";
																				$response["message"] = $api_response['responseDescription'];
																				$response["processingStartTime"] = $api_response['processingStartTime'];
																				
																				$response["transactionId"] = $bp_request_id;
																				$response["orderNo"] = $bp_service_order_no;
																				$response["status"] = $api_response['status'];
																				$response["pinCode"] = $bp_resp_comments;
																				$response["pinSerialNumber"] = $api_response['pinSerialNumber'];
																				$response["amount"] = $api_response['amount'];
																				$response["reference"] = $bp_resp_reference;
																				$response["id"] = $bp_resp_transaction_id;
																				$response["statusMessagae"] = $bp_resp_account_name;
																				$response["responseMessage"] = $api_response['responseMessage'];
																				$response["responseStatus"] = $api_response['responseStatus'];
																				$response["dataVendRef"] = $bp_resp_dataVendRef;
																				$response["dataVendTime"] = $bp_resp_dataVendTime;
																				$response["dataReceiptNo"] = $bp_resp_bank_code;
																				$response["serviceCategoryId"] = $api_response['serviceCategoryId'];
																				$response["availableBalance"] = $availableBalance;
																			
																				$response["partnerId"] = $partnerId;
																				$response["signature"] = $server_signature;
																			}
																			else {
																				if ( $statusCode == '') {
																					$statusCode = 50;
																				}
																				error_log("inside statusCode != 0");
																				$rollBackOrder = "Y";
																				$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																				if ( $gl_reverse_repsonse != 0 ) {
																					error_log("Error in BillPay gl_reverse for: ".$journal_entry_id);
																					insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $totalAmount, $con);
																				}else {
																					error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																				}
																				//Rollback wallet update
																				$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId);
																				if ( $update_wallet != 0 ) {
																					error_log("Error in BillPay rollback_wallet for: ".$journal_entry_id);
																					insertaccountrollback($userId, $journal_entry_id, $acc_trans_type, $totalAmount, 2, "F", $con);
																				}else {
																					error_log("Success in BillPay rollback_wallet for: ".$journal_entry_id);
																					//Insert into account_rollback table with success status
																				}

																				$approver_comments = "PC: ".$statusCode." - ".$responseDescription." @ ".$api_response['processingStartTime'];
																				$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $transactionId";
																				error_log("update_query = ".$update_query);
																				$update_query_result = mysqli_query($con, $update_query);
																			
																				$response["statusCode"] = $statusCode;
																				$response["result"] = "Error";
																				//$response["message"] = $responseDescription;
																				$response["message"] = $statusMessage;
																				$response["partnerId"] = $partnerId;
																				$response["signature"] = $server_signature;
																			}
																		}else {
																			error_log("inside httpcode != 200");
																			$rollBackOrder = "Y";

																			$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																			if ( $gl_reverse_repsonse != 0 ) {
																				error_log("Error in BillPay gl_reverse for: ".$journal_entry_id);
																				insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $totalAmount, $con);
																			}else {
																				error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																			}
																			//Rollback wallet update
																			$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId);
																			if ( $update_wallet != 0 ) {
																				error_log("Error in BillPay rollback_wallet for: ".$journal_entry_id);
																				insertaccountrollback($userId, $journal_entry_id, $acc_trans_type, $totalAmount, 2, "F", $con);
																			}else {
																				error_log("Success in BillPay rollback_wallet for: ".$journal_entry_id);
																				//Insert into account_rollback table with success status
																			}

																			$statusCode = $httpcode;
																			$responseDescription = "HTTP Protocol Error";
																			error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																			$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
																			error_log("update_query = ".$update_query);
																			$update_query_result = mysqli_query($con, $update_query);

																			$approver_comments = "PE: ".$statusCode." - ".$responseDescription;
																			error_log("update_query = ".$update_query);
																			$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $transactionId";
																			$update_query_result = mysqli_query($con, $update_query);
																			
																			$response["statusCode"] = $statusCode;
																			$response["result"] = "Error";
																			$response["message"] = "Error in connection to BillPay API Server";
																			$response["partnerId"] = $partnerId;
																			$response["signature"] = $server_signature;
																		}
																	}else {
																		error_log("curl_error != 0 ");
																		$rollBackOrder = "Y";
																		$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																		if ( $gl_reverse_repsonse != 0 ) {
																			error_log("Error in BillPay gl_reverse for: ".$journal_entry_id);
																			insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $totalAmount, $con);
																		}else {
																			error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																		}
																		//Rollback wallet update
																		$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId);
																		if ( $update_wallet != 0 ) {
																			error_log("Error in BillPay rollback_wallet for: ".$journal_entry_id);
																			insertaccountrollback($userId, $journal_entry_id, $acc_trans_type, $totalAmount, 2, "F", $con);
																		}else {
																			error_log("Success in BillPay rollback_wallet for: ".$journal_entry_id);
																			//Insert into account_rollback table with success status
																		}

																		$statusCode = $curl_error;
																		$responseDescription = "CURL Execution Error";
																		$approver_comments = "PE: ".$statusCode." - ".$responseDescription;
																		error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																		$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
																		error_log("update_query = ".$update_query);
																		$update_query_result = mysqli_query($con, $update_query);
																	
																		$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $transactionId";
																		error_log("update_query = ".$update_query);
																		$update_query_result = mysqli_query($con, $update_query);

																		$response["statusCode"] = $statusCode;
																		$response["result"] = "Error";
																		$response["message"] = "Error in communication protocol";
																		$response["partnerId"] = $partnerId;
																		$response["signature"] = $server_signature;
																	}
																}
																else {
																	//Not able to Update Wallet
																	$response["statusCode"] = "305";
																	$response["result"] = "Error";
																	$response["message"] = "DB Error in updating wallet";
																	$response["partnerId"] = $partnerId;
																	$response["signature"] = $server_signature;
																}
															}
															else {
																//Not able to get acc_trans_type
																$response["statusCode"] = "310";
																$response["result"] = "Error";
																$response["message"] = "DB Error in getting acc_trans_type";
																$response["partnerId"] = $partnerId;
																$response["signature"] = $server_signature;
															}

															//Clear bp_service_order
															if ( $rollBackOrder == "Y") {
																//Roll back of BP Service Order error
																$bp_services_order_delete_query = "delete from bp_service_order where bp_service_order_no = $bp_service_order_no";
																error_log("bp_services_order_delete_query = " . $bp_services_order_delete_query);
																$bp_services_order_delete_result = mysqli_query($con, $bp_services_order_delete_query);
																if ( $bp_services_order_delete_result ) {
																	error_log("bp_service_order delete successful");
																}else {
																	error_log("bp_service_order delete failure = ".mysqli_error());
																}
															}
														}else {
															//Error in inserting Bp Service Order Result
															$response["statusCode"] = "315";
															$response["result"] = "Error";
															$response["message"] = "DB Error in BP Service Order Result";
															$response["partnerId"] = $partnerId;
															$response["signature"] = $server_signature;
														}
													}else {
														//Error in getting journal_entry_id
														$response["statusCode"] = "320";
														$response["result"] = "Error";
														$response["message"] = "DB Error in gettin journal entry id";
														$response["partnerId"] = $partnerId;
														$response["signature"] = $server_signature;
													}
												}
												else{
													//Error in Generating Bp Service Order
													$response["statusCode"] = "325";
													$response["result"] = "Error";
													$response["message"] = "DB Error in BP Service Order Request";
													$response["partnerId"] = $partnerId;
													$response["signature"] = $server_signature;
												}
											}else {
												//Error in Inserting Bp Request Table
												$response["statusCode"] = "330";
												$response["result"] = "Error";
												$response["message"] = "DB Error in updating BP Request";
												$response["partnerId"] = $partnerId;
												$response["signature"] = $server_signature;
											}
										}else {
											//Error in Generation Bp Request Id
											$response["statusCode"] = "335";
											$response["result"] = "Error";
											$response["message"] = "DB Error in BP Trans Log";
											$response["partnerId"] = $partnerId;
											$response["signature"] = $server_signature;
										}
									}else {
										//Error in inserting Bp Transaction Log
										$response["statusCode"] = "335";
										$response["result"] = "Error";
										$response["message"] = "DB Error in BP Trans Log";
										$response["partnerId"] = $partnerId;
										$response["signature"] = $server_signature;
									}
								}else {
									//Error in generating Bp Transaction Log
									$response["statusCode"] = "340";
									$response["result"] = "Error";
									$response["message"] = "DB Error in BP Trans Log Request";
									$response["partnerId"] = $partnerId;
									$response["signature"] = $server_signature;
								}
							}else {
								// Insufficient Agent Available Balance
								$response["statusCode"] = "350";
								$response["result"] = "Error";
								$response["message"] = "Insufficient Agent Available Balance";
								$response["partnerId"] = $partnerId;
								$response["signature"] = $server_signature;
							}
						}
						else {
							//Agent Available Balance is not available
							$response["statusCode"] = "360";
							$response["result"] = "Error";
							$response["message"] = "Agent Available Balance is not available";
							$response["partnerId"] = $partnerId;
							$response["signature"] = $server_signature;
						}
					}
					else {
						// Error in accessing Agent Info & Wallet details.
						$resp_message = "";
						if ( $agent_info_wallet_status == 1 ) {
							$resp_message = "Agent status is not active";
						}else if ( $agent_info_wallet_status == 2 ) {
							$resp_message = "Agent is blocked";
						}else if ( $agent_info_wallet_status == 3 ) {
							$resp_message = "Agent Wallet is not active";
						}else if ( $agent_info_wallet_status == 4 ) {
							$resp_message = "Agent Wallet is blocked";
						}else {
							$resp_message = "Error in accessing Agent Info & Wallet details. Contact Kadick Admin";
						}
						$response["statusCode"] = "370";
						$response["result"] = "Error";
						$response["message"] = $resp_message;
						$response["partnerId"] = $partnerId;
						$response["signature"] = $server_signature;
					}
				}else {
					// Invalid Singature
					$response["statusCode"] = "390";
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
		else if(isset($data -> operation) && $data -> operation == 'BP_OPAY_BETTING_PAYMENT_CONFIRM') {
			error_log("inside operation == BP_OPAY_BETTING_PAYMENT_CONFIRM method");
			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
				&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
				&& isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
				&& isset($data->stateId) && !empty($data->stateId) && isset($data->serviceFeatureId) && !empty($data->serviceFeatureId)
				&& isset($data->billerId) && !empty($data->billerId) && isset($data->productId) && !empty($data->productId) 
				&& isset($data->billerName) && !empty($data->billerName) && isset($data->productName) && !empty($data->productName)
				&& isset($data->requestAmount) && !empty($data->requestAmount) && isset($data->totalAmount) && !empty($data->totalAmount) 
				//&& isset($data->agentCharge) && !empty($data->agentCharge) && isset($data->serviceCharge) && !empty($data->serviceCharge) 
				//&& isset($data->otherCharge) && !empty($data->otherCharge) && isset($data->stampCharge) && !empty($data->stampCharge) 
				&& isset($data->email) && !empty($data->email) && isset($data->mobile) && !empty($data->mobile)
				&& isset($data->serviceFeatureCode) && !empty($data->serviceFeatureCode) && isset($data->partnerId) && !empty($data->partnerId)
				&& isset($data->location) && !empty($data->location) && isset($data->bpAccountNo) && !empty($data->bpAccountNo)
				&& isset($data->bpAccountName) && !empty($data->bpAccountName) && isset($data->transactionId) && !empty($data->transactionId)
			){
				ini_set('max_execution_time', 120);
				set_time_limit(120);
				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$parentCode = $data->parentCode;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$bpBillerId = $data->billerId;
				$bpProductId = $data->productId;
				$bpBillerName = $data->billerName;
				$bpProductName = $data->productName;
				$requestAmount = $data->requestAmount;
				$bpAccountName = $data->bpAccountName;
				$bpAccountNo = $data->bpAccountNo;
				$totalAmount = $data->totalAmount;
				$agentCharge = $data->agentCharge;
				$amsCharge = $data->amsCharge;
				$serviceCharge = $data->serviceCharge;
				$otherCharge = $data->otherCharge;
				$partnerCharge = $data->partnerCharge;
				$stampCharge = $data->stampCharge;
				$email = $data->email;
				$mobile = $data->mobile;
				$serviceFeatureCode = $data->serviceFeatureCode;
				$partnerId = $data->partnerId;
				$location = $data->location;
				$transactionId = $data->transactionId;
				$serviceFeatureId = $data->serviceFeatureId;
				$session_validity = AGENT_SESSION_VALID_TIME;
				$lgaId = ADMIN_LOCAL_GOVT_ID;
				$bp_service_order_no = 0;

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
					$validate_result = validateKey1($key1, $userId, $session_validity, '5', $con);
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
					
					$daily_check_result = checkDailyLimit($userId, $requestAmount, $con);
					//$daily_check_result = 0;
					error_log("daily_check_result = ".$daily_check_result);
					if ( $daily_check_result != 0 ){
						// Exceeded Daily Limit
						$response["statusCode"] = "998";
						$response["result"] = "Error";
						$response["message"] = "Failure: Exceeded Daily Limit";
						$response["signature"] = 0;
						error_log(json_encode($response));
						echo json_encode($response);
						return;
					}

					$txtType = "E";
					if ($parentCode == "") {
						$partyCount = 2;
					}else {
						$partyCount = 3;
					}
					$db_flexiRate = "N";
					$flexi_rate_query = "select state_flexi_rate_id from state_flexi_rate where state_id = $stateId and (service_feature_id is null or (service_feature_id = $serviceFeatureId)) and active = 'Y' and (start_date is null or (start_date is not null and date(start_date) <= current_date())) and (expiry_date is null or (expiry_date is not null and date(expiry_date) >= current_date())) order by state_flexi_rate_id limit 1";
					error_log("flexi_rate_query query = ".$flexi_rate_query);
					$flexi_rate_result = mysqli_query($con, $flexi_rate_query);
					if ($flexi_rate_result) {
						$flexi_rate_count = mysqli_num_rows($flexi_rate_result);
						if($flexi_rate_count > 0) {					
							$db_flexiRate = "Y";
						}
					}

					if ( "Y" == $flexiRate || "Y" == $db_flexiRate ) {
						$txtType = "F";
					}
					
					$agent_info_wallet_status = check_agent_info_wallet_status($partyType, $partyCode, $con);
					$rollBackOrder = "N";
					if ($agent_info_wallet_status == 0 ) {
						//Checking available_balance
						$available_balance = check_agent_available_balance($userId, $con);
						error_log("available_balance response for = ".$available_balance);
						if ( $available_balance >= 0 || $available_balance != "" || $available_balance != null ) {
							if ( floatval($totalAmount) <= floatval($available_balance) ) {
								$bp_trans_log_id = generate_seq_num(3100, $con);
								$bp_service_order_no = generate_seq_num(3300, $con);									
								$data = array();
								$data['serviceCategoryId'] = $bpProductId;
								$data['country'] = "NG";
								$data['currency'] = "NGN";
								$data['amount'] = $requestAmount;
								$data['customerId'] = $bpAccountNo;
								$data['provider'] = $bpProductName;
								$data['reference'] = "BETPAY#".$bp_service_order_no;
								$data['countryId'] = $countryId;
								$data['stateId'] = $stateId;
								$data['userId'] = $userId;
								$data['localGovtId'] = ADMIN_LOCAL_GOVT_ID;
									
								$request_message = json_encode($data);
								if ($bp_trans_log_id > 0)  {
									error_log("bp_trans_log_id = ".$bp_trans_log_id);
									$request_message = mysqli_real_escape_string($con, $request_message);
									$bp_trans_log_query = "INSERT INTO bp_trans_log (bp_trans_log_id, partner_id, party_type, party_code, service_feature_code, country_id, state_id, amount, request_message, message_send_time, create_user, create_time) VALUES ($bp_trans_log_id, $partnerId, '$partyType', '$partyCode', '$serviceFeatureCode', $countryId, $stateId, $totalAmount, left('PayAnt Education Payment = $request_message', 1000), now(), $userId, now())";
									error_log("bp_trans_log_query = ". $bp_trans_log_query);
									$bp_trans_log_result = mysqli_query($con, $bp_trans_log_query);
									if( $bp_trans_log_result ) {
										if ( $bp_service_order_no > 0)  {
											error_log("Inside BP Service Order ==> BP_SERVICE_ORDER_NO = ".$bp_service_order_no);
											$acc_trans_type = 'BPAY1';
											$firstpartycode = $partyCode;
											$firstpartytype = $partyType;
											$secondpartycode = $parentCode;
											if(($secondpartycode == "") || empty($secondpartycode) || ($secondpartycode = null)) {
												$secondpartycode = "";
												$secondpartytype = "";
											}
											else {
												$secondpartytype = substr($secondpartycode,0);
											}
											$journal_entry_id = process_glentry($acc_trans_type, $bp_service_order_no, $firstpartycode, $firstpartytype, $secondpartycode, $secondpartytype, $narration, $totalAmount, $userId, $con);
											if($journal_entry_id > 0) {
												$journal_entry_error = "N";	
												$new_ams_charge = floatval($amsCharge) - floatval($agentCharge);
												if ( $txtType == "F" ) {
													$bp_service_order_query = "INSERT INTO bp_service_order (bp_service_order_no, service_feature_code, bp_biller_id, bp_product_id, partner_id, user_id, total_amount, request_amount, ams_charge, partner_charge, other_charge, agent_charge, stamp_charge, date_time) VALUES ($bp_service_order_no, '$serviceFeatureCode', $bpBillerId, $bpProductId, $partnerId, $userId, $totalAmount, $requestAmount, $amsCharge, $partnerCharge, $otherCharge, $agentCharge, $stampCharge, now())";
												}else {
													$bp_service_order_query = "INSERT INTO bp_service_order (bp_service_order_no, service_feature_code, bp_biller_id, bp_product_id, partner_id, user_id, total_amount, request_amount, ams_charge, partner_charge, other_charge, agent_charge, stamp_charge, date_time) VALUES ($bp_service_order_no, '$serviceFeatureCode', $bpBillerId, $bpProductId, $partnerId, $userId, $totalAmount, $requestAmount, $new_ams_charge, $partnerCharge, $otherCharge, $agentCharge, $stampCharge, now())";
												}
												error_log("bp_service_order_query = ".$bp_service_order_query);
												$bp_service_order_result = mysqli_query($con, $bp_service_order_query);
												if( $bp_service_order_result ) {
													error_log("inside success bp_service_order table entry");
													$get_acc_trans_type = getAcccTransType($acc_trans_type, $con);
													error_log("get_acc_trans_type = ".$get_acc_trans_type);	
													if($get_acc_trans_type != "-1"){
														$split = explode("|",$get_acc_trans_type);
														$ac_factor = $split[0];
														$cb_factor = $split[1];
														$acc_trans_type_id = $split[2];
														$update_wallet = walletupdateWithTransaction($acc_trans_type, $cb_factor, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId, $journal_entry_id);
														if( $update_wallet == 0 ) {	
															//Inside success wallet update
															error_log("bp_service_order_no = ".$bp_service_order_no);
																
															$url = BPAPI_OPAY_SERVER_PAYMENT_BETTING_CUSTOMER_URL;
															$tsec = time();
															$raw_data1 = BPAPI_SERVER_APP_PASSWORD."|".BPAPI_SERVER_APP_USERNAME."|".$tsec;
															//error_log("raw_data1 = ".$raw_data1);
															$key1 = base64_encode($raw_data1);
															error_log("key1 = ".$key1);
															error_log("before calling post");
															error_log("url = ".$url);
															$data['key1'] = $key1;
															$data['signature'] = $local_signature;
															error_log("request sent ==> ".json_encode($data));
															$ch = curl_init($url);
															curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
															curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
															curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
															curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, OPAY_CURL_CONNECTION_TIMEOUT);
															curl_setopt($ch, CURLOPT_TIMEOUT, OPAY_CURL_TIMEOUT);
															$curl_response = curl_exec($ch);
															$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
															$curl_error = curl_errno($ch);
															curl_close($ch);
															if ( $curl_error === 0 ) {
																error_log("curl_error == 0 ");
																error_log("response received = ".$curl_response);
																error_log("code = ".$httpcode);
																if ( $httpcode == 200 ) {
																	error_log("inside httpcode == 200");
																	$api_response = json_decode($curl_response, true);
																	$statusCode = $api_response['responseCode'];
																	$responseDescription = $api_response['responseDescription'];
																	$statusMessage = $api_response['status']." - ".$api_response['message'];
																	error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																	error_log("response_received <=== ".$curl_response);
																	$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
																	error_log("update_query = ".$update_query);
																	$update_query_result = mysqli_query($con, $update_query);

																	if($statusCode === 0) {
																			
																		error_log("inside statusCode === 0");
																		$bp_resp_dataTime = $api_response['processingStartTime'];	
																		$bp_resp_transaction_id = $api_response['orderNo'];
																		$bp_resp_reference = $api_response['reference'];
																		$bp_comments = $api_response['status']." ".$api_response['errorMsg'];
																		$update_query = "UPDATE bp_request SET order_no = $bp_service_order_no, status = 'S', update_time = now(), bp_transaction_id = left('$bp_resp_transaction_id', 45), bp_account_no = left('$bp_resp_reference', 30), comments = left('$bp_comments', 256), session_id = '$bp_resp_dataTime', approver_comments = left('$bpProductName', 256) WHERE bp_request_id = $transactionId";
																		error_log("update_query = ".$update_query);
																		$update_query_result = mysqli_query($con, $update_query);

																		$gl_post_return_value = process_glpost($journal_entry_id, $con);
																		if ( $gl_post_return_value == 0 ) {
																			error_log("Success in BillPay gl_post for: ".$journal_entry_id);
																		}
																		else{
																			error_log("Error in gl_post, with gl_post_return_value = ".$gl_post_return_value);
																			insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AP", "W", "N", $totalAmount, $con);
																		}

																		$order_post_result = post_bporder($bp_service_order_no, $con);
																		if ( $order_post_result == 0 ) { 
																			error_log("Success in BillPay post_bporder for: ".$bp_service_order_no);
																		}else {
																			error_log("Error in BillPay post_bporder for: ".$bp_service_order_no);
																		}
																			
																		$checking_feature_value_response = checking_feature_value($userId, $countryId, $stateId, $partyCount, $serviceFeatureId, $partnerId, $requestAmount, $txtType, $con);
																		$checking_feature_value_response_split = explode("#",$checking_feature_value_response);
																		$rateparties_details = $checking_feature_value_response_split[1];
																		$service_insert_count = 0;
																		$serviceconfig = explode(",", $rateparties_details);
																		error_log("bil_pay education serviceconfig = ".$serviceconfig);
																			
																		//Insert into bp_service_order_comm table
																		for($i = 0; $i < sizeof($serviceconfig); $i++) {
																			$bpOrder_flag = insertBillPayServiceOrderComm($bp_service_order_no, $serviceconfig[$i], $journal_entry_id, $txtType, $agentCharge, $ams_charge, $con);
																			if ( $bpOrder_flag == 0 ) {
																				++$service_insert_count;
																			}
																		}	
																		if ( $service_insert_count == sizeof($serviceconfig) ) {
																			error_log("All entries for service_order insert in commission table. Insert count = ".$service_insert_count);
																		}else {
																			error_log("Not all entries for service_order insert in commission table. Insert count = ".$service_insert_count.", serviceConfig count = ".sizeof($serviceconfig));
																		}
																		$pcu_result = process_bp_comm_update($bp_service_order_no, $con);
																		if ( $pcu_result > 0 ) {
																			if ( $pcu_result == sizeof($serviceconfig) ) {
																				error_log("All bp_service_order_comm updates are completed. Count = ".$pcu_result);
																			}else {
																				error_log("Warning bp_service_order_comm updates are not matching completed. Count = ".$pcu_result);
																			}
																		}else {
																			error_log("Error in bp_service_order_comm records insert. Insert Count = ".$pcu_result);
																		}
																		$availableBalance = check_party_available_balance($partyType, $userId, $con);
																		$orderTime = getBpOrderTime($bp_service_order_no, $con);

																		$response["statusCode"] = "0";
																		$response["result"] = "SUCCESS";
																		$response["message"] = $api_response['responseDescription'];
																		$response["processingStartTime"] = $api_response['processingStartTime'];
																				
																		$response["transactionId"] = $transactionId;
																		$response["orderNo"] = $bp_service_order_no;
																		$response["status"] = $api_response['status'];
																		$response["errorMsg"] = $api_response['errorMsg'];
																		$response["reference"] = $bp_resp_reference;
																		$response["id"] = $bp_resp_transaction_id;
																		$response["createdAt"] = $bp_resp_dataTime;
																		$response["availableBalance"] = $availableBalance;
																		$response["requestAmount"] = $requestAmount;
																			
																		$response["partnerId"] = $partnerId;
																		$response["signature"] = $server_signature;
																	}
																	else {
																		if ( $statusCode == '') {
																			$statusCode = 50;
																		}
																		error_log("inside statusCode != 0");
																		$rollBackOrder = "Y";
																		$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																		if ( $gl_reverse_repsonse != 0 ) {
																			error_log("Error in BillPay gl_reverse for: ".$journal_entry_id);
																			insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $totalAmount, $con);
																		}else {
																			error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																		}
																		//Rollback wallet update
																		$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId);
																		if ( $update_wallet != 0 ) {
																			error_log("Error in BillPay rollback_wallet for: ".$journal_entry_id);
																			insertaccountrollback($userId, $journal_entry_id, $acc_trans_type, $totalAmount, 2, "F", $con);
																		}else {
																			error_log("Success in BillPay rollback_wallet for: ".$journal_entry_id);
																			//Insert into account_rollback table with success status
																		}

																		$approver_comments = "PC: ".$statusCode." - ".$responseDescription." @ ".$api_response['processingStartTime'];
																		$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $transactionId";
																		error_log("update_query = ".$update_query);
																		$update_query_result = mysqli_query($con, $update_query);
																			
																		$response["statusCode"] = $statusCode;
																		$response["result"] = "Error";
																		$response["message"] = $responseDescription;
																		//$response["message"] = $statusMessage;
																		$response["partnerId"] = $partnerId;
																		$response["signature"] = $server_signature;
																	}
																}else {
																	error_log("inside httpcode != 200");
																	$rollBackOrder = "Y";

																	$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																	if ( $gl_reverse_repsonse != 0 ) {
																		error_log("Error in BillPay gl_reverse for: ".$journal_entry_id);
																		insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $totalAmount, $con);
																	}else {
																		error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																	}
																	//Rollback wallet update
																	$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId);
																	if ( $update_wallet != 0 ) {
																		error_log("Error in BillPay rollback_wallet for: ".$journal_entry_id);
																		insertaccountrollback($userId, $journal_entry_id, $acc_trans_type, $totalAmount, 2, "F", $con);
																	}else {
																		error_log("Success in BillPay rollback_wallet for: ".$journal_entry_id);
																		//Insert into account_rollback table with success status
																	}

																	$statusCode = $httpcode;
																	$responseDescription = "HTTP Protocol Error";
																	error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																	$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
																	error_log("update_query = ".$update_query);
																	$update_query_result = mysqli_query($con, $update_query);

																	$approver_comments = "PE: ".$statusCode." - ".$responseDescription;
																	error_log("update_query = ".$update_query);
																	$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $transactionId";
																	$update_query_result = mysqli_query($con, $update_query);
																			
																	$response["statusCode"] = $statusCode;
																	$response["result"] = "Error";
																	$response["message"] = "Error in connection to BillPay API Server";
																	$response["partnerId"] = $partnerId;
																	$response["signature"] = $server_signature;
																}
															}else {
																error_log("curl_error != 0 ");
																$rollBackOrder = "Y";
																$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																if ( $gl_reverse_repsonse != 0 ) {
																	error_log("Error in BillPay gl_reverse for: ".$journal_entry_id);
																	insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $totalAmount, $con);
																}else {
																	error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																}
																//Rollback wallet update
																$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId);
																if ( $update_wallet != 0 ) {
																	error_log("Error in BillPay rollback_wallet for: ".$journal_entry_id);
																	insertaccountrollback($userId, $journal_entry_id, $acc_trans_type, $totalAmount, 2, "F", $con);
																}else {
																	error_log("Success in BillPay rollback_wallet for: ".$journal_entry_id);
																	//Insert into account_rollback table with success status
																}

																$statusCode = $curl_error;
																$responseDescription = "CURL Execution Error";
																$approver_comments = "PE: ".$statusCode." - ".$responseDescription;
																error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
																error_log("update_query = ".$update_query);
																$update_query_result = mysqli_query($con, $update_query);
																	
																$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $transactionId";
																error_log("update_query = ".$update_query);
																$update_query_result = mysqli_query($con, $update_query);

																$response["statusCode"] = $statusCode;
																$response["result"] = "Error";
																$response["message"] = "Error in communication protocol";
																$response["partnerId"] = $partnerId;
																$response["signature"] = $server_signature;
															}
														}
														else {
															//Not able to Update Wallet
															$response["statusCode"] = "305";
															$response["result"] = "Error";
															$response["message"] = "DB Error in updating wallet";
															$response["partnerId"] = $partnerId;
															$response["signature"] = $server_signature;
														}
													}
													else {
														//Not able to get acc_trans_type
														$response["statusCode"] = "310";
														$response["result"] = "Error";
														$response["message"] = "DB Error in getting acc_trans_type";
														$response["partnerId"] = $partnerId;
														$response["signature"] = $server_signature;
													}

													//Clear bp_service_order
													if ( $rollBackOrder == "Y") {
														//Roll back of BP Service Order error
														$bp_services_order_delete_query = "delete from bp_service_order where bp_service_order_no = $bp_service_order_no";
														error_log("bp_services_order_delete_query = " . $bp_services_order_delete_query);
														$bp_services_order_delete_result = mysqli_query($con, $bp_services_order_delete_query);
														if ( $bp_services_order_delete_result ) {
															error_log("bp_service_order delete successful");
														}else {
															error_log("bp_service_order delete failure = ".mysqli_error());
														}
													}
												}else {
													//Error in inserting Bp Service Order Result
													$response["statusCode"] = "315";
													$response["result"] = "Error";
													$response["message"] = "DB Error in BP Service Order Result";
													$response["partnerId"] = $partnerId;
													$response["signature"] = $server_signature;
												}
											}else {
												//Error in getting journal_entry_id
												$response["statusCode"] = "320";
												$response["result"] = "Error";
												$response["message"] = "DB Error in gettin journal entry id";
												$response["partnerId"] = $partnerId;
												$response["signature"] = $server_signature;
											}
										}
										else{
											//Error in Generating Bp Service Order
											$response["statusCode"] = "325";
											$response["result"] = "Error";
											$response["message"] = "DB Error in BP Service Order Request";
											$response["partnerId"] = $partnerId;
											$response["signature"] = $server_signature;
										}
									}else {
										//Error in inserting Bp Transaction Log
										$response["statusCode"] = "335";
										$response["result"] = "Error";
										$response["message"] = "DB Error in BP Trans Log";
										$response["partnerId"] = $partnerId;
										$response["signature"] = $server_signature;
									}
								}else {
									//Error in generating Bp Transaction Log
									$response["statusCode"] = "340";
									$response["result"] = "Error";
									$response["message"] = "DB Error in BP Trans Log Request";
									$response["partnerId"] = $partnerId;
									$response["signature"] = $server_signature;
								}
							}else {
								// Insufficient Agent Available Balance
								$response["statusCode"] = "350";
								$response["result"] = "Error";
								$response["message"] = "Insufficient Agent Available Balance";
								$response["partnerId"] = $partnerId;
								$response["signature"] = $server_signature;
							}
						}
						else {
							//Agent Available Balance is not available
							$response["statusCode"] = "360";
							$response["result"] = "Error";
							$response["message"] = "Agent Available Balance is not available";
							$response["partnerId"] = $partnerId;
							$response["signature"] = $server_signature;
						}
					}
					else {
						// Error in accessing Agent Info & Wallet details.
						$resp_message = "";
						if ( $agent_info_wallet_status == 1 ) {
							$resp_message = "Agent status is not active";
						}else if ( $agent_info_wallet_status == 2 ) {
							$resp_message = "Agent is blocked";
						}else if ( $agent_info_wallet_status == 3 ) {
							$resp_message = "Agent Wallet is not active";
						}else if ( $agent_info_wallet_status == 4 ) {
							$resp_message = "Agent Wallet is blocked";
						}else {
							$resp_message = "Error in accessing Agent Info & Wallet details. Contact Kadick Admin";
						}
						$response["statusCode"] = "370";
						$response["result"] = "Error";
						$response["message"] = $resp_message;
						$response["partnerId"] = $partnerId;
						$response["signature"] = $server_signature;
					}
				}else {
					// Invalid Singature
					$response["statusCode"] = "390";
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
        	else if(isset($data -> operation) && $data -> operation == 'BP_PAYANT_PAYMENT_OLD_CONFIRM') {
			error_log("inside operation == BP_PAYANT_PAYMENT_OLD_CONFIRM method");
			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
				&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
				&& isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
				&& isset($data->stateId) && !empty($data->stateId) && isset($data->serviceFeatureId) && !empty($data->serviceFeatureId)
				&& isset($data->billerId) && !empty($data->billerId) && isset($data->productId) && !empty($data->productId) 
				&& isset($data->billerName) && !empty($data->billerName) && isset($data->productName) && !empty($data->productName)
				&& isset($data->requestAmount) && !empty($data->requestAmount) && isset($data->totalAmount) && !empty($data->totalAmount) 
				//&& isset($data->agentCharge) && !empty($data->agentCharge) && isset($data->serviceCharge) && !empty($data->serviceCharge) 
				//&& isset($data->otherCharge) && !empty($data->otherCharge) && isset($data->stampCharge) && !empty($data->stampCharge) 
				&& isset($data->accountNo) && !empty($data->accountNo) && isset($data->accountName) && !empty($data->accountName)
				&& isset($data->email) && !empty($data->email) && isset($data->mobile) && !empty($data->mobile)
				&& isset($data->serviceFeatureCode) && !empty($data->serviceFeatureCode) && isset($data->partnerId) && !empty($data->partnerId)
				//&& isset($data->bpAccountNo) && !empty($data->bpAccountNo) && isset($data->bpAccountName) && !empty($data->bpAccountName)
				//&& isset($data->bpBankCode) && !empty($data->bpBankCode) && isset($data->bpTransactionId) && !empty($data->bpTransactionId)
				&& isset($data->transactionId) && !empty($data->transactionId) && isset($data->location) && !empty($data->location)
				&& isset($data->amsCharge) && !empty($data->amsCharge)
			){
				ini_set('max_execution_time', 120);
				set_time_limit(120);
				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$parentCode = $data->parentCode;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$billerGroupId = $data->billerGroupId;
				$bpBillerId = $data->billerId;
				$bpProductId = $data->productId;
				$bpBillerName = $data->billerName;
				$bpProductName = $data->productName;
				$requestAmount = $data->requestAmount;
				$accountNo = $data->accountNo;
				$totalAmount = $data->totalAmount;
				$agentCharge = $data->agentCharge;
				$amsCharge = $data->amsCharge;
				$serviceCharge = $data->serviceCharge;
				$otherCharge = $data->otherCharge;
				$partnerCharge = $data->partnerCharge;
				$stampCharge = $data->stampCharge;
				$accountName = $data->accountName;
				$email = $data->email;
				$mobile = $data->mobile;
				$serviceFeatureCode = $data->serviceFeatureCode;
				$partnerId = $data->partnerId;
				//$bpBankCode = $data->bpBankCode;
				//$bpAccountNo = $data->bpAccountNo;
				$bpAccountName = $data->bpAccountName;
				//$bpTransactionId = $data->bpTransactionId;
				$location = $data->location;
				$transactionId = $data->transactionId;
				$serviceFeatureId = $data->serviceFeatureId;
				$session_validity = AGENT_SESSION_VALID_TIME;
				$bp_service_order_no = 0;

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
					$validate_result = validateKey1($key1, $userId, $session_validity, '5', $con);
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
					
					$daily_check_result = checkDailyLimit($userId, $requestAmount, $con);
					//$daily_check_result = 0;
					error_log("daily_check_result = ".$daily_check_result);
					if ( $daily_check_result != 0 ){
						// Exceeded Daily Limit
						$response["statusCode"] = "998";
						$response["result"] = "Error";
						$response["message"] = "Failure: Exceeded Daily Limit";
						$response["signature"] = 0;
						error_log(json_encode($response));
						echo json_encode($response);
						return;
					}

					$txtType = "E";
					if ($parentCode == "") {
						$partyCount = 2;
					}else {
						$partyCount = 3;
					}
					$db_flexiRate = "N";
					$flexi_rate_query = "select state_flexi_rate_id from state_flexi_rate where state_id = $stateId and (service_feature_id is null or (service_feature_id = $serviceFeatureId)) and active = 'Y' and (start_date is null or (start_date is not null and date(start_date) <= current_date())) and (expiry_date is null or (expiry_date is not null and date(expiry_date) >= current_date())) order by state_flexi_rate_id limit 1";
					error_log("flexi_rate_query query = ".$flexi_rate_query);
					$flexi_rate_result = mysqli_query($con, $flexi_rate_query);
					if ($flexi_rate_result) {
						$flexi_rate_count = mysqli_num_rows($flexi_rate_result);
						if($flexi_rate_count > 0) {					
							$db_flexiRate = "Y";
						}
					}

					if ( "Y" == $flexiRate || "Y" == $db_flexiRate ) {
						$txtType = "F";
					}
					error_log("before calling checking_feature_value: txType = ".$txtType);
					$checking_feature_value_response = checking_feature_value($userId, $countryId, $stateId, $partyCount, $serviceFeatureId, $partnerId, $requestAmount, $txtType, $con);
					$checking_feature_value_response_split = explode("#",$checking_feature_value_response);
					$charges_details = $checking_feature_value_response_split[0];	
					$rateparties_details = $checking_feature_value_response_split[1];										
					$charges_details_split = explode("|",$charges_details);	

					$rate_check_result = "N";
					$reponse_feature_value = $charges_details_split[0];	
					$service_feature_config_id = $charges_details_split[1];
					$ams_charge = $charges_details_split[2];
					$partner_charge = $charges_details_split[3];									
					$other_charge = $charges_details_split[4];
					if ( $txtType == "F" ) {
						error_log("inside txtType == F");
						$db_max_charge_amount = 0;
						$serviceconfig = explode(",", $rateparties_details);
						for($i = 0; $i < sizeof($serviceconfig); $i++) {
							if ( strpos($serviceconfig[$i], "Agent") !== false ) {
								$serviceconfig_array = explode("~", $serviceconfig[$i]);
								$db_max_charge_amount = $serviceconfig_array[4];
								break;
							}
						}
						error_log("db_max_charge_amount = ".$db_max_charge_amount.", request_check_max_total_amount = ".$request_check_max_total_amount);
						$request_check_max_total_amount = floatval($amsCharge) + floatval($agentCharge) + floatval($partner_charge) + floatval($other_charge);
						error_log("request_check_max_total_amount = ".$request_check_max_total_amount.", amsCharge = ".$amsCharge.", agentCharge = ".$agentCharge.", partner_charge = ".$partner_charge.", other_charge = ".$other_charge);
						if( $reponse_feature_value == 0 && (floatval($ams_charge) == floatval($amsCharge)) 
							&& (floatval($partner_charge) == floatval($partnerCharge)) && $request_check_max_total_amount <= floatval($db_max_charge_amount) ) {
							error_log("inside rate_check_result = Y");
							$rate_check_result = "Y";
						}else{
							error_log("inside rate_check_result = N");
							$rate_check_result = "N";
						}
					}else {								
						error_log("inside txtType != F");									
						$request_check_total_amount = floatval($amsCharge) +  floatval($partnerCharge) + floatval($otherCharge) + floatval($requestAmount);
						error_log("request_check_total_amount = ".request_check_total_amount.", amsCharge = ".$amsCharge.", agentCharge = ".$agentCharge.", partner_charge = ".$partner_charge.", other_charge = ".$other_charge);
						if( $reponse_feature_value == 0 &&  (floatval($ams_charge) == floatval($amsCharge) )  
							&& (floatval($partner_charge) == floatval($partnerCharge)) && (floatval($other_charge) == floatval($otherCharge)) 
							&& floatval($request_check_total_amount) == floatval($totalAmount)) {
							error_log("inside rate_check_result = Y");
							$rate_check_result = "Y";
						}else {
							error_log("inside rate_check_result = N");
							$rate_check_result = "N";
						}
					}
										
					// checkin get_feature_value response code
					if( $rate_check_result == "Y" ) {
						$agent_info_wallet_status = check_agent_info_wallet_status($partyType, $partyCode, $con);
						$rollBackOrder = "N";
						if ($agent_info_wallet_status == 0 ) {
							//Checking available_balance
							$available_balance = check_agent_available_balance($userId, $con);
							error_log("available_balance response for = ".$available_balance);
							if ( $available_balance >= 0 || $available_balance != "" || $available_balance != null ) {
								if ( floatval($totalAmount) <= floatval($available_balance) ) {
									$bp_trans_log_id = generate_seq_num(3100, $con);
																		
									$data = array();
									$data['serviceCategoryId'] = $bpProductId;
									$data['meterNumber'] = $accountNo;
									$data['amount'] = $requestAmount;
									$data['phone'] = $mobile;
									$data['countryId'] = $countryId;
									$data['stateId'] = $stateId;
									$data['userId'] = $userId;
									$data['localGovtId'] = ADMIN_LOCAL_GOVT_ID;
									
									$request_message = json_encode($data);
									if ($bp_trans_log_id > 0)  {
										error_log("bp_trans_log_id = ".$bp_trans_log_id);
										$request_message = mysqli_real_escape_string($con, $request_message);
										$bp_trans_log_query = "INSERT INTO bp_trans_log (bp_trans_log_id, partner_id, party_type, party_code, service_feature_code, country_id, state_id, amount, request_message, message_send_time, create_user, create_time) VALUES ($bp_trans_log_id, $partnerId, '$partyType', '$partyCode', '$serviceFeatureCode', $countryId, $stateId, $totalAmount, left('PayAnt Bill Payment = $request_message', 1000), now(), $userId, now())";
										error_log("bp_trans_log_query = ". $bp_trans_log_query);
										$bp_trans_log_result = mysqli_query($con, $bp_trans_log_query);
										if( $bp_trans_log_result ) {
											if ( $txtType == "F" ) {
												$bp_request_update_query = "UPDATE bp_request SET service_charge = $amsCharge+$agentCharge, partner_charge = $partnerCharge, other_charge = $otherCharge, bp_trans_log_id2 = $bp_trans_log_id WHERE bp_request_id = ".$transactionId;
											}else {
												$bp_request_update_query = "UPDATE bp_request SET service_charge = $amsCharge, partner_charge = $partnerCharge, other_charge = $otherCharge,  bp_trans_log_id2 = $bp_trans_log_id WHERE bp_request_id = ".$transactionId;
											}
											error_log("bp_request_update_query = ".$bp_request_update_query);
											$bp_request_update_result = mysqli_query($con, $bp_request_update_query);		
											if($bp_request_update_result) {						
												$bp_service_order_no = generate_seq_num(3300, $con);
												if ( $bp_service_order_no > 0)  {
													error_log("Inside BP Service Order ==> BP_SERVICE_ORDER_NO = ".$bp_service_order_no);
													$acc_trans_type = 'BPAY1';
													$firstpartycode = $partyCode;
													$firstpartytype = $partyType;
													$secondpartycode = $parentCode;
													if(($secondpartycode == "") || empty($secondpartycode) || ($secondpartycode = null)) {
														$secondpartycode = "";
														$secondpartytype = "";
													}
													else {
														$secondpartytype = substr($secondpartycode,0);
													}
													$journal_entry_id = process_glentry($acc_trans_type, $bp_service_order_no, $firstpartycode, $firstpartytype, $secondpartycode, $secondpartytype, $narration, $totalAmount, $userId, $con);
													if($journal_entry_id > 0) {
														$journal_entry_error = "N";	
														$new_ams_charge = floatval($amsCharge) - floatval($agentCharge);
														if ( $txtType == "F" ) {
															$bp_service_order_query = "INSERT INTO bp_service_order (bp_service_order_no, service_feature_code, bp_biller_id, bp_product_id, partner_id, user_id, total_amount, request_amount, ams_charge, partner_charge, other_charge, agent_charge, stamp_charge, date_time) VALUES ($bp_service_order_no, '$serviceFeatureCode', $bpBillerId, $bpProductId, $partnerId, $userId, $totalAmount, $requestAmount, $amsCharge, $partnerCharge, $otherCharge, $agentCharge, $stampCharge, now())";
														}else {
															$bp_service_order_query = "INSERT INTO bp_service_order (bp_service_order_no, service_feature_code, bp_biller_id, bp_product_id, partner_id, user_id, total_amount, request_amount, ams_charge, partner_charge, other_charge, agent_charge, stamp_charge, date_time) VALUES ($bp_service_order_no, '$serviceFeatureCode', $bpBillerId, $bpProductId, $partnerId, $userId, $totalAmount, $requestAmount, $new_ams_charge, $partnerCharge, $otherCharge, $agentCharge, $stampCharge, now())";
														}
														error_log("bp_service_order_query = ".$bp_service_order_query);
														$bp_service_order_result = mysqli_query($con, $bp_service_order_query);
														if( $bp_service_order_result ) {
															error_log("inside success bp_service_order table entry");
															$get_acc_trans_type = getAcccTransType($acc_trans_type, $con);
															error_log("get_acc_trans_type = ".$get_acc_trans_type);	
															if($get_acc_trans_type != "-1"){
																$split = explode("|",$get_acc_trans_type);
																$ac_factor = $split[0];
																$cb_factor = $split[1];
																$acc_trans_type_id = $split[2];
																$update_wallet = walletupdateWithTransaction($acc_trans_type, $cb_factor, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId, $journal_entry_id);
																if( $update_wallet == 0 ) {	
																	//Inside success wallet update
																	error_log("bp_service_order_no = ".$bp_service_order_no);
																	
																	$url = BPAPI_PAYANT_SERVER_BILL_PAYMENT_URL;
																	$tsec = time();
																	$raw_data1 = BPAPI_SERVER_APP_PASSWORD."|".BPAPI_SERVER_APP_USERNAME."|".$tsec;
																	error_log("raw_data1 = ".$raw_data1);
																	$key1 = base64_encode($raw_data1);
																	error_log("key1 = ".$key1);
																	error_log("before calling post");
																	error_log("url = ".$url);
																	$data['key1'] = $key1;
																	$data['signature'] = $local_signature;
																	error_log("request sent ==> ".json_encode($data));
																	$ch = curl_init($url);
																	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
																	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
																	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
																	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, PAYANT_CURL_CONNECTION_TIMEOUT);
																	curl_setopt($ch, CURLOPT_TIMEOUT, PAYANT_CURL_TIMEOUT);
																	$curl_response = curl_exec($ch);
																	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
																	$curl_error = curl_errno($ch);
																	curl_close($ch);
																	if ( $curl_error === 0 ) {
																		error_log("curl_error == 0 ");
																		error_log("response received = ".$curl_response);
																		error_log("code = ".$httpcode);
																		if ( $httpcode == 200 ) {
																			error_log("inside httpcode == 200");
																			$api_response = json_decode($curl_response, true);
																			$statusCode = $api_response['responseCode'];
																			$responseDescription = $api_response['responseDescription'];
																			$statusMessage = $api_response['status']." - ".$api_response['message'];
																			error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																			error_log("response_received <=== ".$curl_response);
																			$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
																			error_log("update_query = ".$update_query);
																			$update_query_result = mysqli_query($con, $update_query);

																			if($statusCode === 0) {
																			
																				error_log("inside statusCode === 0");
																				$update_query = "UPDATE bp_request SET order_no = $bp_service_order_no, status = 'S', update_time = now(), bp_transaction_id = '".$api_response['id']."', bp_account_no = '".$api_response['reference']." / ".$api_response['dataVendRef']."', bp_bank_code = '".$api_response['dataReceiptNo']."', bp_account_name = '".$api_response['verifyName']."', comments = '".$api_response['pinCode']."', approver_comments = 'Pin SNo: ".$api_response['pinSerialNumber'].", Units: ".$api_response['pinUnits'].", AmountPaid: ".$api_response['pinUnits'].", AmountGenerated: ".$api_response['dataAmountGenerated']."', session_id = '".$api_response['dataVendTime']."' WHERE bp_request_id = $transactionId";
																				error_log("update_query = ".$update_query);
																				$update_query_result = mysqli_query($con, $update_query);

																				$gl_post_return_value = process_glpost($journal_entry_id, $con);
																				if ( $gl_post_return_value == 0 ) {
																					error_log("Success in BillPay gl_post for: ".$journal_entry_id);
																				}
																				else{
																					error_log("Error in gl_post, with gl_post_return_value = ".$gl_post_return_value);
																					insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AP", "W", "N", $totalAmount, $con);
																				}

																				$order_post_result = post_bporder($bp_service_order_no, $con);
																				if ( $order_post_result == 0 ) { 
																					error_log("Success in BillPay post_bporder for: ".$bp_service_order_no);
																				}else {
																					error_log("Error in BillPay post_bporder for: ".$bp_service_order_no);
																				}
																				$serviceconfig = explode(",", $rateparties_details);
																				$service_insert_count = 0;

																				//Insert into bp_service_order_comm table
																				for($i = 0; $i < sizeof($serviceconfig); $i++) {
																					$bpOrder_flag = insertBillPayServiceOrderComm($bp_service_order_no, $serviceconfig[$i], $journal_entry_id, $txtType, $agentCharge, $ams_charge, $con);
																					if ( $bpOrder_flag == 0 ) {
																						++$service_insert_count;
																					}
																				}
																				if ( $service_insert_count == sizeof($serviceconfig) ) {
																					error_log("All entries for service_order insert in commission table. Insert count = ".$service_insert_count);
																				}else {
																					error_log("Not all entries for service_order insert in commission table. Insert count = ".$service_insert_count.", serviceConfig count = ".sizeof($serviceconfig));
																				}
																				$pcu_result = process_bp_comm_update($bp_service_order_no, $con);
																				if ( $pcu_result > 0 ) {
																					if ( $pcu_result == sizeof($serviceconfig) ) {
																						error_log("All bp_service_order_comm updates are completed. Count = ".$pcu_result);
																					}else {
																						error_log("Warning bp_service_order_comm updates are not matching completed. Count = ".$pcu_result);
																					}
																				}else {
																					error_log("Error in bp_service_order_comm records insert. Insert Count = ".$pcu_result);
																				}
																				$availableBalance = check_party_available_balance($partyType, $userId, $con);
																				$orderTime = getBpOrderTime($bp_service_order_no, $con);

																				$response["statusCode"] = "0";
																				$response["result"] = "SUCCESS";
																				$response["message"] = $api_response['responseDescription'];
																				$response["processingStartTime"] = $api_response['processingStartTime'];
																				
																				$response["transactionId"] = $transactionId;
																				$response["orderNo"] = $bp_service_order_no;
																				$response["status"] = $api_response['status'];
																				$response["pinUnits"] = $api_response['pinUnits'];
																				$response["pinCode"] = $api_response['pinCode'];
																				$response["pinSerialNumber"] = $api_response['pinSerialNumber'];
																				$response["amount"] = $api_response['amount'];
																				$response["reference"] = $api_response['reference'];
																				$response["id"] = $api_response['id'];
																				$response["dataResponseMessage"] = $api_response['dataResponseMessage'];
																				$response["dataResponseCode"] = $api_response['dataResponseCode'];
																				$response["dataVendRef"] = $api_response['dataVendRef'];
																				$response["dataVendAmount"] = $api_response['dataVendAmount'];
																				$response["dataUnits"] = $api_response['dataUnits'];
																				$response["dataTotalAmountPaid"] = $api_response['dataTotalAmountPaid'];
																				$response["dataToken"] = $api_response['dataToken'];
																				$response["dataVendTime"] = $api_response['dataVendTime'];
																				$response["dataTax"] = $api_response['dataTax'];
																				$response["dataReceiptNo"] = $api_response['dataReceiptNo'];
																				$response["dataOrderId"] = $api_response['dataOrderId'];
																				$response["dataFreeUnits"] = $api_response['dataFreeUnits'];
																				$response["dataDisco"] = $api_response['dataDisco'];
																				$response["dataDebtRemaining"] = $api_response['dataDebtRemaining'];
																				$response["dataDebtAmount"] = $api_response['dataDebtAmount'];
																				$response["dataTariff"] = $api_response['dataTariff'];
																				$response["dataAmountGenerated"] = $api_response['dataAmountGenerated'];
																				$response["dataId"] = $api_response['dataId'];
																				$response["verifyMaxPayableAmount"] = $api_response['verifyMaxPayableAmount'];
																				$response["verifyMinPayableAmount"] = $api_response['verifyMinPayableAmount'];
																				$response["verifyDaysLastVend"] = $api_response['verifyDaysLastVend'];
																				$response["verifyTariff"] = $api_response['verifyTariff'];
																				$response["verifyFreeUnits"] = $api_response['verifyFreeUnits'];
																				$response["verifyMeterNo"] = $api_response['verifyMeterNo'];
																				$response["verifyVendType"] = $api_response['verifyVendType'];
																				$response["verifyAddress"] = $api_response['verifyAddress'];
																				$response["verifyName"] = $api_response['verifyName'];
																				$response["serviceCategoryId"] = $api_response['serviceCategoryId'];
																				$response["availableBalance"] = $availableBalance;
																			
																				$response["partnerId"] = $partnerId;
																				$response["signature"] = $server_signature;
																			}
																			else {
																				if ( $statusCode == '') {
																					$statusCode = 50;
																				}
																				error_log("inside statusCode != 0");
																				$rollBackOrder = "Y";
																				$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																				if ( $gl_reverse_repsonse != 0 ) {
																					error_log("Error in BillPay gl_reverse for: ".$journal_entry_id);
																					insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $totalAmount, $con);
																				}else {
																					error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																				}
																				//Rollback wallet update
																				$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId);
																				if ( $update_wallet != 0 ) {
																					error_log("Error in BillPay rollback_wallet for: ".$journal_entry_id);
																					insertaccountrollback($userId, $journal_entry_id, $acc_trans_type, $totalAmount, 2, "F", $con);
																				}else {
																					error_log("Success in BillPay rollback_wallet for: ".$journal_entry_id);
																					//Insert into account_rollback table with success status
																				}

																				$approver_comments = "PC: ".$statusCode." - ".$responseDescription." @ ".$api_response['processingStartTime'];
																				$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $transactionId";
																				error_log("update_query = ".$update_query);
																				$update_query_result = mysqli_query($con, $update_query);

																				$response["statusCode"] = $statusCode;
																				$response["result"] = "Error";
																				//$response["message"] = $responseDescription;
																				$response["message"] = $statusMessage;
																				$response["partnerId"] = $partnerId;
																				$response["signature"] = $server_signature;
																			}
																		}else {
																			error_log("inside httpcode != 200");
																			$rollBackOrder = "Y";

																			$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																			if ( $gl_reverse_repsonse != 0 ) {
																				error_log("Error in BillPay gl_reverse for: ".$journal_entry_id);
																				insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $totalAmount, $con);
																			}else {
																				error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																			}
																			//Rollback wallet update
																			$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId);
																			if ( $update_wallet != 0 ) {
																				error_log("Error in BillPay rollback_wallet for: ".$journal_entry_id);
																				insertaccountrollback($userId, $journal_entry_id, $acc_trans_type, $totalAmount, 2, "F", $con);
																			}else {
																				error_log("Success in BillPay rollback_wallet for: ".$journal_entry_id);
																				//Insert into account_rollback table with success status
																			}

																			$statusCode = $httpcode;
																			$responseDescription = "HTTP Protocol Error";
																			error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																			$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
																			error_log("update_query = ".$update_query);
																			$update_query_result = mysqli_query($con, $update_query);

																			$approver_comments = "PE: ".$statusCode." - ".$responseDescription;
																			error_log("update_query = ".$update_query);
																			$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $transactionId";
																			$update_query_result = mysqli_query($con, $update_query);

																			$response["statusCode"] = $statusCode;
																			$response["result"] = "Error";
																			$response["message"] = "Error in connection to BillPay API Server";
																			$response["partnerId"] = $partnerId;
																			$response["signature"] = $server_signature;
																		}
																	}else {
																		error_log("curl_error != 0 ");
																		$rollBackOrder = "Y";
																		$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
																		if ( $gl_reverse_repsonse != 0 ) {
																			error_log("Error in BillPay gl_reverse for: ".$journal_entry_id);
																			insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $totalAmount, $con);
																		}else {
																			error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
																		}
																		//Rollback wallet update
																		$update_wallet = walletupdateWithOutTransaction($ac_factor*-1, $cb_factor*-1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId);
																		if ( $update_wallet != 0 ) {
																			error_log("Error in BillPay rollback_wallet for: ".$journal_entry_id);
																			insertaccountrollback($userId, $journal_entry_id, $acc_trans_type, $totalAmount, 2, "F", $con);
																		}else {
																			error_log("Success in BillPay rollback_wallet for: ".$journal_entry_id);
																			//Insert into account_rollback table with success status
																		}

																		$statusCode = $curl_error;
																		$responseDescription = "CURL Execution Error";
																		$approver_comments = "PE: ".$statusCode." - ".$responseDescription;
																		error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
																		$update_query = "UPDATE bp_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE bp_trans_log_id = $bp_trans_log_id";
																		error_log("update_query = ".$update_query);
																		$update_query_result = mysqli_query($con, $update_query);

																		$update_query = "UPDATE bp_request SET status = 'E', approver_comments = '$approver_comments', update_time = now() WHERE bp_request_id = $transactionId";
																		error_log("update_query = ".$update_query);
																		$update_query_result = mysqli_query($con, $update_query);

																		$response["statusCode"] = $statusCode;
																		$response["result"] = "Error";
																		$response["message"] = "Error in communication protocol";
																		$response["partnerId"] = $partnerId;
																		$response["signature"] = $server_signature;
																	}
																}
																else {
																	//Not able to Update Wallet
																	$response["statusCode"] = "305";
																	$response["result"] = "Error";
																	$response["message"] = "DB Error in updating wallet";
																	$response["partnerId"] = $partnerId;
																	$response["signature"] = $server_signature;
																}
															}
															else {
																//Not able to get acc_trans_type
																$response["statusCode"] = "310";
																$response["result"] = "Error";
																$response["message"] = "DB Error in getting acc_trans_type";
																$response["partnerId"] = $partnerId;
																$response["signature"] = $server_signature;
															}

															//Clear bp_service_order
															if ( $rollBackOrder == "Y") {
																//Roll back of BP Service Order error
																$bp_services_order_delete_query = "delete from bp_service_order where bp_service_order_no = $bp_service_order_no";
																error_log("bp_services_order_delete_query = " . $bp_services_order_delete_query);
																$bp_services_order_delete_result = mysqli_query($con, $bp_services_order_delete_query);
																if ( $bp_services_order_delete_result ) {
																	error_log("bp_service_order delete successful");
																}else {
																	error_log("bp_service_order delete failure = ".mysqli_error());
																}
															}
														}else {
															//Error in inserting Bp Service Order Result
															$response["statusCode"] = "315";
															$response["result"] = "Error";
															$response["message"] = "DB Error in BP Service Order Result";
															$response["partnerId"] = $partnerId;
															$response["signature"] = $server_signature;
														}
													}else {
														//Error in getting journal_entry_id
														$response["statusCode"] = "320";
														$response["result"] = "Error";
														$response["message"] = "DB Error in gettin journal entry id";
														$response["partnerId"] = $partnerId;
														$response["signature"] = $server_signature;
													}
												}
												else {
													//Error in Generating Bp Service Order
													$response["statusCode"] = "325";
													$response["result"] = "Error";
													$response["message"] = "DB Error in BP Service Order Request";
													$response["partnerId"] = $partnerId;
													$response["signature"] = $server_signature;
												}
											}else {
												//Error in Updating Bp Request Table
												$response["statusCode"] = "330";
												$response["result"] = "Error";
												$response["message"] = "DB Error in updating BP Request";
												$response["partnerId"] = $partnerId;
												$response["signature"] = $server_signature;
											}
										}else {
											//Error in inserting Bp Transaction Log
											$response["statusCode"] = "335";
											$response["result"] = "Error";
											$response["message"] = "DB Error in BP Trans Log";
											$response["partnerId"] = $partnerId;
											$response["signature"] = $server_signature;
										}
									}else {
										//Error in generating Bp Transaction Log
										$response["statusCode"] = "340";
										$response["result"] = "Error";
										$response["message"] = "DB Error in BP Trans Log Request";
										$response["partnerId"] = $partnerId;
										$response["signature"] = $server_signature;
									}
								}else {
									// Insufficient Agent Available Balance
									$response["statusCode"] = "350";
									$response["result"] = "Error";
									$response["message"] = "Insufficient Agent Available Balance";
									$response["partnerId"] = $partnerId;
									$response["signature"] = $server_signature;
								}
							}
							else {
								//Agent Available Balance is not available
								$response["statusCode"] = "360";
								$response["result"] = "Error";
								$response["message"] = "Agent Available Balance is not available";
								$response["partnerId"] = $partnerId;
								$response["signature"] = $server_signature;
							}
						}
						else {
							// Error in accessing Agent Info & Wallet details.
							$resp_message = "";
							if ( $agent_info_wallet_status == 1 ) {
								$resp_message = "Agent status is not active";
							}else if ( $agent_info_wallet_status == 2 ) {
								$resp_message = "Agent is blocked";
							}else if ( $agent_info_wallet_status == 3 ) {
								$resp_message = "Agent Wallet is not active";
							}else if ( $agent_info_wallet_status == 4 ) {
								$resp_message = "Agent Wallet is blocked";
							}else {
								$resp_message = "Error in accessing Agent Info & Wallet details. Contact Kadick Admin";
							}
							$response["statusCode"] = "370";
							$response["result"] = "Error";
							$response["message"] = $resp_message;
							$response["partnerId"] = $partnerId;
							$response["signature"] = $server_signature;
						}
					}else {
						//Error - Db total amount and client total amount are diferent
						$response["statusCode"] = "380";
						$response["result"] = "Error";
						$response["message"] = "Failure: Invalid request...";
						$response["partnerId"] = $partnerId;
						$response["signature"] = 0;
					}
				}else {
					// Invalid Singature
					$response["statusCode"] = "390";
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
		else if(isset($data -> operation) && $data -> operation == 'BP_PAYMENT_RECEIPT') {
			error_log("inside operation == BP_PAYMENT_RECEIPT method");
			if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
				&& isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
				&& isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
				&& isset($data->stateId) && !empty($data->stateId) && isset($data->sessionId) && !empty($data->sessionId)
				&& isset($data->orderNo) && !empty($data->orderNo)
			) {
				ini_set('max_execution_time', 120);
				set_time_limit(120);
				error_log("inside all inputs are set correctly");
				$userId = $data->userId;
				$partyCode = $data->partyCode;
				$partyType = $data->partyType;
				$countryId = $data->countryId;
				$stateId = $data->stateId;
				$signature = $data->signature;
				$key1 = $data->key1;
				$sessionId = $data->sessionId;
				$orderNo = $data->orderNo;
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
					$validate_result = validateKey1($key1, $userId, $session_validity, '6', $con);
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
					
					$data = array();
					$data['sessionId'] = $sessionId;
					$data['orderNo'] = $orderNo;
					$data['countryId'] = $countryId;
					$data['stateId'] = $stateId;
					$data['userId'] = $userId;
					$data['signature'] = $local_signature;
					$data['key1'] = $key1;
					$data['localGovtId'] = ADMIN_LOCAL_GOVT_ID;

					$url = BPAPI_SERVER_PAYMENT_RECEIPT_URL;
					//$sendreq = sendRequest($data, $url);
					$tsec = time();
					$raw_data1 = BPAPI_SERVER_APP_PASSWORD."|".BPAPI_SERVER_APP_USERNAME."|".$tsec;
					error_log("raw_data1 = ".$raw_data1);
					$key1 = base64_encode($raw_data1);
					error_log("key1 = ".$key1);
					error_log("before calling post");
					error_log("url = ".$url);
					$data['key1'] = $key1;
					$data['signature'] = $local_signature;
					error_log("request sent ==> ".json_encode($data));
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
					curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, BILLPAY_CURL_CONNECTION_TIMEOUT);
					curl_setopt($ch, CURLOPT_TIMEOUT, BILLPAY_CURL_TIMEOUT);
					$curl_response = curl_exec($ch);
					$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					$curl_error = curl_errno($ch);
					curl_close($ch);
					if ( $curl_error == 0 ) {
						error_log("curl_error == 0 ");
						error_log("response received = ".$curl_response);
						error_log("code = ".$httpcode);
						if ( $httpcode == 200 ) {
							error_log("inside httpcode == 200");
							$api_response = json_decode($curl_response, true);
							$statusCode = $api_response['responseCode'];
							$responseDescription = $api_response['responseDescription'];
							error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
							error_log("response_received <=== ".$curl_response);
							
							if($statusCode === 0) {
								error_log("inside statusCode == 0");
								
								$bp_receipt_query = "INSERT INTO bp_receipt (bp_receipt_id, order_no, receipt_content) VALUES (0, $orderNo, '$curl_response')";
								error_log("bp_receipt_query = ". $bp_receipt_query);
								$bp_receipt_result = mysqli_query($con, $bp_receipt_query);
								if( $bp_receipt_result ) {
									error_log("successfully inserted bp_receipt table");
								}else {
									error_log("error in inserting bp_receipt table".mysqli_error($con));
								}
								
								$searchedValue = "Customer Name";
								$customerNameObject = null;
								foreach($api_response['params'] as $struct) {
									error_log("1==>".$struct['name'].":".$struct['value']." # ".$searchedValue);
								    	if ($searchedValue == $struct['name']) {
								    		error_log("found..".$struct['value']);
								        	$customerNameObject = $struct;
								        	break;
								    	}
								}
								$searchedValue = "STS1Token";
								$tokenObject = null;
								foreach($api_response['params'] as $struct) {
									error_log("2==>".$struct['name'].":".$struct['value']." # ".$searchedValue);
									if ($searchedValue == $struct['name']) {
										error_log("found..".$struct['value']);
										$tokenObject = $struct;
										break;
									}
								}
								$searchedValue = "Customer Address";
								$customerAddressObject = null;
								foreach($api_response['params'] as $struct) {
									error_log("3==>".$struct['name'].":".$struct['value']." # ".$searchedValue);
									if ($searchedValue == $struct['name']) {
										error_log("found..".$struct['value']);
										$customerAddressObject = $struct;
										break;
									}
								}				
								$bp_field1 = "Name: ";
								if ( !is_null($customerNameObject) ) {
									$bp_field1 = $bp_field1.trim($customerNameObject['value']);	
								}else {
									$bp_field1 = $bp_field1."-";
								}
								$bp_field2 = "Token: ";
								if ( !is_null($tokenObject) ) {
									$bp_field2 = $bp_field2.trim($tokenObject['value']);	
								}else {
									$bp_field2 = $bp_field2."-";
								}
								$bp_field3 = "Address: ";
								if ( !is_null($customerAddressObject) ) {
									$bp_field3 = $bp_field3.trim($customerAddressObject['value']);	
								}else {
									$bp_field3 = $bp_field3."-";
								}
								
								$bp_comments = "Ref: ".$api_response['paymentReference'].", Biller: ".$api_response['billerName'].", Product: ".$api_response['productName'].", TDate: ".$api_response['transactionDate'];
								$bp_approve_comments = $bp_field1.", ".$bp_field2.", ".$bp_field3;
								error_log("bp_comments = ".$bp_comments.", bp_approve_comments = ".$bp_approve_comments);
								$bp_request_udpate_query = "UPDATE bp_request set comments = left('".$bp_comments."', 256), approver_comments = left('".$bp_approve_comments."', 256) where order_no = ".$orderNo;
								error_log("bp_request_udpate_query = ".$bp_request_udpate_query);
								$bp_request_udpate_result = mysqli_query($con, $bp_request_udpate_query);
								if( $bp_request_udpate_result ) {
									error_log("bp_comments, bp_approver_comments updated successfully for order: ".$orderNo);
								}else {
									error_log("bp_comments, bp_approver_comments not updated for order: ".$orderNo);
								}
								
								$response["statusCode"] = "0";
								$response["result"] = "Success";
								$response["message"] = $responseDescription;
								$response["signature"] = $server_signature;
								
								$response["transactionId"] = $api_response['transactionId'];
								$response["billingAccountNumber"] = $api_response['billingAccountNumber'];
								$response["billingAccountName"] = $api_response['billingAccountName'];
								$response["billingInstitutionCode"] = $api_response['billingInstitutionCode'];
								$response["amount"] = $api_response['amount'];
								$response["paymentFee"] = $api_response['paymentFee'];
								$response["totalAmount"] = $api_response['totalAmount'];
								$response["billerImage"] = $api_response['billerImage'];
								$response["paymentReference"] = $api_response['paymentReference'];
								$response["feeBearer"] = $api_response['feeBearer'];
								$response["billerName"] = $api_response['billerName'];
								$response["productName"] = $api_response['productName'];
								$response["notificationResponse"] = $api_response['notificationResponse'];
								$response["paymentType"] = $api_response['paymentType'];
								$response["customerName"] = $api_response['customerName'];
								$response["customerAccountNumber"] = $api_response['customerAccountNumber'];
								$response["transactionDate"] = $api_response['transactionDate'];
								$response["orderNo"] = $orderNo;
								$response["params"] = $api_response['params'];
							}
							else {
								error_log("inside statusCode != 0");
								if ( $statusCode == '') {
									$statusCode = 50;
								}
								$response["statusCode"] = $statusCode;
								$response["result"] = "Error";
								$response["message"] = $responseDescription;
								$response["partnerId"] = $partnerId;
								$response["signature"] = $server_signature;
							}
						}else {
							error_log("inside httpcode != 200");
							$statusCode = $httpcode;
							$responseDescription = "HTTP Protocol Error";
							$response["statusCode"] = $statusCode;
							$response["result"] = "Error";
							$response["message"] = "Error in connection to BillPay API Server";
							$response["partnerId"] = $partnerId;
							$response["signature"] = $server_signature;
						}
					}else {
						error_log("curl_error != 0 ");
						$statusCode = $curl_error;
						$responseDescription = "CURL Execution Error";
						$response["statusCode"] = $statusCode;
						$response["result"] = "Error";
						$response["message"] = "Error in communication protocol";
						$response["partnerId"] = $partnerId;
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
			$response["result"] = "success";
			$response["status"] = "500";
			$response["message"] = "Invalid Operation";
			$response["signature"] = 0;
		}
	}
	else {
		// Invalid Request Method
		$response["result"] = "success";
		$response["status"] = "600";
		$response["message"] = "Post Failure";
		$response["signature"] = 0;
	}
    	error_log("bill_pay ==> ".json_encode($response));
	echo json_encode($response);
	
	
function checking_feature_value($userId, $country, $state, $partyCount, $product, $partner, $requestAmount, $txtType, $con) {
			
	$res = -1;
	$query = "SELECT get_feature_value_new($country, $state, null, $product, $partner, $requestAmount, '$txtType', $partyCount, null, null, $userId, -1) as res";
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