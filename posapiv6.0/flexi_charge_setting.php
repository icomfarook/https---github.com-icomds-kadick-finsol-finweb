<?php
	include('../common/admin/configmysql.php');
	include("../common/admin/finsol_crypt.php");
	include ("get_prime.php");	
	require_once ("AesCipher.php");
    require_once("db_connect.php");
    include ("functions.php");
	error_log("inside pcposapi/flexi_charge_setting.php");
	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		error_log("inside post request method");
        $data = json_decode(file_get_contents("php://input"));
        error_log("flexi_charge_setting <== ".json_encode($data));

		if(isset($data -> operation) && $data -> operation == 'FLEXI_CHARGE_SETTING_CREATE') {
			error_log("inside operation == FLEXI_CHARGE_SETTING_CREATE method");
            if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
                   && isset($data->flexiChargeSetting->partyType) && !empty($data->flexiChargeSetting->partyType) 
                   && isset($data->flexiChargeSetting->partyCode) && !empty($data->flexiChargeSetting->partyCode) 
                   && isset($data->flexiChargeSetting->serviceFeatureId) && !empty($data->flexiChargeSetting->serviceFeatureId) 
                   && isset($data->flexiChargeSetting->fromValue) && !empty($data->flexiChargeSetting->fromValue) 
                   && isset($data->flexiChargeSetting->toValue) && !empty($data->flexiChargeSetting->toValue) 
                   && isset($data->flexiChargeSetting->flexiCharge) && !empty($data->flexiChargeSetting->flexiCharge) 
                   && isset($data->userId) && !empty($data->userId) 
                   && isset($data->countryId) && !empty($data->countryId) 
                   && isset($data->stateId) && !empty($data->stateId) 
			){

				error_log("inside all inputs are set correctly");
				$serviceFeatureId = $data->flexiChargeSetting->serviceFeatureId;
                $fromValue = $data->flexiChargeSetting->fromValue;
                $toValue = $data->flexiChargeSetting->toValue;
                $flexiCharge = $data->flexiChargeSetting->flexiCharge;
                $partyCode = $data->flexiChargeSetting->partyCode;
                $partyType = $data->flexiChargeSetting->partyType;
                $userId = $data->userId;
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
                    $party_flexi_charge_id = generate_seq_num(4200, $con);
                    if( $party_flexi_charge_id > 0 )  {
                        $insert_party_flexi_charge_query = "INSERT into party_flexi_charge (party_flexi_charge_id, party_type, party_code, service_feature_id, from_value, to_value, flexi_charge, active, create_user, create_time) values ($party_flexi_charge_id, '$partyType', '$partyCode', $serviceFeatureId, $fromValue, $toValue, $flexiCharge, 'Y', $userId, now())";
					    error_log("insert_party_flexi_charge_query = ".$insert_party_flexi_charge_query);
					    $insert_party_flexi_charge_result = mysqli_query($con, $insert_party_flexi_charge_query);
					    if($insert_party_flexi_charge_result) {
                            error_log("insert_party_flexi_charge is success");
                            $response["result"] = "Success";
                            $response["message"] = "Your Flexi Charge Setting submission #".$party_flexi_charge_id." is accepted";
                            $response["statusCode"] = 0;
                            $response["signature"] = $server_signature;
                            $response["flexiChargeSettingId"] = $party_flexi_charge_id;
                        }
                        else {
                            $response["result"] = "Error";
                            $response["message"] = "Error in submitting your Flexi Charge Setting";
                            $response["statusCode"] = "100";
                            $response["signature"] = $server_signature;
                            $response["flexiChargeSettingId"] = 0;
                        }
                    }
					else {
						// DB failure
						$response["result"] = "Error";
						$response["statusCode"] = "200";
                        $response["message"] = "Failure: Error in getting Flexi Charge Setting submission no";
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
        } else if(isset($data -> operation) && $data -> operation == 'FLEXI_CHARGE_SETTING_UPDATE') {
			error_log("inside operation == FLEXI_CHARGE_SETTING_UPDATE method");
            if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
                   && isset($data->flexiChargeSetting->partyType) && !empty($data->flexiChargeSetting->partyType) 
                   && isset($data->flexiChargeSetting->partyCode) && !empty($data->flexiChargeSetting->partyCode) 
                   && isset($data->flexiChargeSetting->serviceFeatureId) && !empty($data->flexiChargeSetting->serviceFeatureId) 
                   && isset($data->flexiChargeSetting->fromValue) && !empty($data->flexiChargeSetting->fromValue) 
                   && isset($data->flexiChargeSetting->toValue) && !empty($data->flexiChargeSetting->toValue) 
                   && isset($data->flexiChargeSetting->flexiCharge) && !empty($data->flexiChargeSetting->flexiCharge) 
                   && isset($data->userId) && !empty($data->userId) && isset($data->flexiChargeSetting->partyFlexiChargeId) && !empty($data->flexiChargeSetting->partyFlexiChargeId) 
                   && isset($data->countryId) && !empty($data->countryId)  && isset($data->stateId) && !empty($data->stateId) 
			){

				error_log("inside all inputs are set correctly");
                $partyFlexiChargeId = $data->flexiChargeSetting->partyFlexiChargeId;
				$serviceFeatureId = $data->flexiChargeSetting->serviceFeatureId;
                $fromValue = $data->flexiChargeSetting->fromValue;
                $toValue = $data->flexiChargeSetting->toValue;
                $flexiCharge = $data->flexiChargeSetting->flexiCharge;
                $partyCode = $data->flexiChargeSetting->partyCode;
                $partyType = $data->flexiChargeSetting->partyType;
                $userId = $data->userId;
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
                    $update_party_flexi_charge_query = "UPDATE party_flexi_charge SET from_value = ".$fromValue.", to_value = ".$toValue.", flexi_charge = ".$flexiCharge.", update_user = ".$userId.", update_time = now() where party_flexi_charge_id = ".$partyFlexiChargeId." and party_type = '".$partyType."' and party_code = '".$partyCode."'";
					error_log("update_party_flexi_charge_query = ".$update_party_flexi_charge_query);
					$update_party_flexi_charge_result = mysqli_query($con, $update_party_flexi_charge_query);
					if($update_party_flexi_charge_result) {
                        error_log("update_party_flexi_charge is success");
                        $response["result"] = "Success";
                        $response["message"] = "Your Flexi Charge Setting update #".$flexiChargeSettingId." is accepted";
                        $response["statusCode"] = 0;
                        $response["signature"] = $server_signature;
                        $response["flexiChargeSettingId"] = $partyFlexiChargeId;
                    }
                    else {
                        $response["result"] = "Error";
                        $response["message"] = "Error in updating your Flexi Charge Setting";
                        $response["statusCode"] = "100";
                        $response["signature"] = $server_signature;
                        $response["flexiChargeSettingId"] = 0;
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
        else if(isset($data -> operation) && $data -> operation == 'FLEXI_CHARGE_SETTING_LIST') {
			error_log("inside operation == FLEXI_CHARGE_SETTING_LIST method");
            if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
                   && isset($data->userId) && !empty($data->userId) && isset($data->partyCode) && !empty($data->partyCode) 
                   && isset($data->partyType) && !empty($data->partyType) && isset($data->countryId) && !empty($data->countryId)
                   && isset($data->stateId) && !empty($data->stateId) && isset($data->serviceFeatureId) && !empty($data->serviceFeatureId) 
			){

				error_log("inside all inputs are set correctly");
                $serviceFeatureId = $data->serviceFeatureId;
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
                    $select_party_flexi_charge_query = "select a.party_flexi_charge_id, a.party_code, a.party_type, a.service_feature_id, b.feature_code, a.from_value, a.to_value, a.flexi_charge, a.active, a.create_time, a.update_time from party_flexi_charge a, service_feature b where a.service_feature_id = b.service_feature_id and a.party_code = '$partyCode' and a.party_type = '$partyType' order by create_time desc";
                    error_log("select_party_flexi_charge_query = ".$select_party_flexi_charge_query);
                   	$select_party_flexi_charge_result = mysqli_query($con, $select_party_flexi_charge_query);
                   	$response["flexiChargeSettings"] = array();
					if ( $select_party_flexi_charge_result ) {
						while($select_party_flexi_charge_row = mysqli_fetch_assoc($select_party_flexi_charge_result)) {
                            $flexiChargeSetting = array();
                            $flexiChargeSetting['partyFlexiChargeId'] = $select_party_flexi_charge_row['party_flexi_charge_id'];
                            $flexiChargeSetting['partyType'] = $select_party_flexi_charge_row['party_type'];
                            $flexiChargeSetting['partyCode'] = $select_party_flexi_charge_row['party_code'];
                            $flexiChargeSetting['serviceFeature'] = $select_party_flexi_charge_row['feature_code'];
                            $flexiChargeSetting['serviceFeatureId'] = $select_party_flexi_charge_row['service_feature_id'];
                            $flexiChargeSetting['fromValue'] = $select_party_flexi_charge_row['from_value'];
                            $flexiChargeSetting['toValue'] = $select_party_flexi_charge_row['to_value'];
                            $flexiChargeSetting['flexiCharge'] = $select_party_flexi_charge_row['flexi_charge'];
                            $flexiChargeSetting['active'] = $select_party_flexi_charge_row['active'];
                            $flexiChargeSetting['createTime'] = $select_party_flexi_charge_row['create_time'];
                            $flexiChargeSetting['updateTime'] = $select_party_flexi_charge_row['update_time'];
                            array_push($response["flexiChargeSettings"], $flexiChargeSetting);
                        }
                        $response["result"] = "Success";
                        $response["message"] = "Your request is processed successfuly";
                        $response["statusCode"] = 0;
                        $response["signature"] = $server_signature;
					}
                   else {
                        $response["result"] = "Error";
                        $response["message"] = "Error in find your Flexi Charge Setting details";
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
    error_log("flexi_charge_setting ==> ".json_encode($response));
	echo json_encode($response);
?>