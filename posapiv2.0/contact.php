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
        error_log("contact <== ".json_encode($data));

		if(isset($data -> operation) && $data -> operation == 'CONTACT') {
			error_log("inside operation == CONTACT method");
            if ( isset($data->signature) && !empty($data->signature) && isset($data->key1) && !empty($data->key1) 
                   && isset($data->contact->cmsType) && !empty($data->contact->cmsType) 
                   && isset($data->contact->category) && !empty($data->contact->category) 
                   && isset($data->contact->subCategory) && !empty($data->contact->subCategory) 
                   && isset($data->contact->subject) && !empty($data->contact->subject) 
                   && isset($data->contact->description) && !empty($data->contact->description) 
                   && isset($data->userId) && !empty($data->userId) 
                   && isset($data->contact->partyCode) && !empty($data->contact->partyCode) 
                   && isset($data->contact->partyType) && !empty($data->contact->partyType)
                   && isset($data->countryId) && !empty($data->countryId) 
                   && isset($data->stateId) && !empty($data->stateId) 
			){

				error_log("inside all inputs are set correctly");
				$contactType = $data->contact->cmsType;
                $contactCategory = $data->contact->category;
                $contactSubCategory = $data->contact->subCategory;
                $subject = $data->contact->subject;
                $description = $data->contact->description;
                $userId = $data->userId;
                $partyCode = $data->contact->partyCode;
                $partyType = $data->contact->partyType;
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
                    $cms_id = generate_seq_num(2900, $con);
                    if( $cms_id > 0 )  {
                        $subject = mysqli_real_escape_string($con, $subject);
                        $description = mysqli_real_escape_string($con, $description);
                        
                        $insert_cms_query = "INSERT into cms_main (cms_id, party_type, party_code, cms_type, category, sub_category, subject, description, status, create_user, create_time) values ($cms_id, '$partyType', '$partyCode', '$contactType', '$contactCategory', '$contactSubCategory', '$subject', '$description', 'O', $userId, now())";
					    error_log("insert_cms_query = ".$insert_cms_query);
					    $insert_cms_result = mysqli_query($con, $insert_cms_query);
					    if($insert_cms_result) {
                            error_log("insert_cms_query is success");
                            $response["result"] = "Success";
                            $response["message"] = "Your contact submission #".$cms_id." is accepted";
                            $response["statusCode"] = 0;
                            $response["signature"] = $server_signature;
                            $response["contactId"] = $cms_id;
                        }
                        else {
                            $response["result"] = "Error";
                            $response["message"] = "Error in submitting your contact request";
                            $response["statusCode"] = "100";
                            $response["signature"] = $server_signature;
                            $response["registrationId"] = 0;
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
        }
        else if(isset($data -> operation) && $data -> operation == 'CONTACT_FIND') {
			error_log("inside operation == CONTACT_FIND method");
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
                        $select_cms_query = "select cms_id, cms_type, category, sub_category, subject, description, status, create_time, ifnull(update_time, '-') as update_time from cms_main where party_code = '$partyCode' and party_type = '$partyType' and date(create_time) = '$dateValue' and create_user = $userId order by create_time desc";
                    }else {
                        $select_cms_query = "select cms_id, cms_type, category, sub_category, subject, description, status, create_time, ifnull(update_time, '-') as update_time from cms_main where party_code = '$partyCode' and party_type = '$partyType' and date(create_time) = '$dateValue' and status = '$status' and create_user = $userId order by create_time desc";
                    }
                    error_log("select_cms_query = ".$select_cms_query);
                    $select_response_query = "select concat(create_time, ': ', response_text) as response from cms_response where cms_id = ";
					$select_cms_result = mysqli_query($con, $select_cms_query);
                   	$response["contacts"] = array();
					if ( $select_cms_result ) {
						while($cms_contact_row = mysqli_fetch_assoc($select_cms_result)) {
                            $contact = array();
                            $cms_id = $cms_contact_row['cms_id'];
                            $contact['cmsId'] = $cms_contact_row['cms_id'];
                            $contact['cmsType'] = $cms_contact_row['cms_type'];
                            $contact['category'] = $cms_contact_row['category'];
                            $contact['subCategory'] = $cms_contact_row['sub_category'];
                            $contact['subject'] = $cms_contact_row['subject'];
                            $contact['description'] = $cms_contact_row['description'];
                            $contact['status'] = $cms_contact_row['status'];
                            $contact['createTime'] = $cms_contact_row['create_time'];
                            $contact['updateTime'] = $cms_contact_row['update_time'];
                            $contact["responses"] = array();
                            $select_response_full_query = $select_response_query.$cms_id;
                            error_log("select_response_full_query = ".$select_response_full_query);
                            $select_response_full_result = mysqli_query($con, $select_response_full_query);
                            if ( $select_response_full_result ) {
                                while($cms_response_row = mysqli_fetch_assoc($select_response_full_result)) {
                                    array_push($contact["responses"], $cms_response_row['response']);
                                }
                            }
							array_push($response["contacts"], $contact);
                        }
                        $response["result"] = "Success";
                        $response["message"] = "Your request is processed successfuly";
                        $response["statusCode"] = 0;
                        $response["signature"] = $server_signature;
					}
                   else {
                        $response["result"] = "Error";
                        $response["message"] = "Error in find your contact details";
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
    error_log("contact ==> ".json_encode($response));
	echo json_encode($response);
?>