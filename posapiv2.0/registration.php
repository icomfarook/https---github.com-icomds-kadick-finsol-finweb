<?php
	include('../common/admin/configmysql.php');
	include("../common/admin/finsol_crypt.php");
	include ("get_prime.php");	
	require_once ("AesCipher.php");
    	//require_once("db_connect.php");
    	include ("functions.php");
	error_log("inside pcposapi/registration.php");
	//error_reporting(E_ALL);
	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;
	

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		error_log("inside post request method");
        	$data = json_decode(file_get_contents("php://input"));
        	error_log("registration <== ".json_encode($data));

		if(isset($data -> operation) && $data -> operation == 'REGISTRATION') {
			error_log("inside operation == REGISTRATION method");
            		if ( isset($data->signature) && !empty($data->signature) 
                    && isset($data->key1) && !empty($data->key1) 
                    && isset($data->registration->firstName) && !empty($data->registration->firstName) 
                    && isset($data->registration->lastName) && !empty($data->registration->lastName) 
                    && isset($data->registration->bvn) && !empty($data->registration->bvn) 
                    && isset($data->registration->outletName) && !empty($data->registration->outletName)
                    && isset($data->registration->mobile) && !empty($data->registration->mobile) 
                    && isset($data->registration->email) && !empty($data->registration->email)
                    && isset($data->registration->address) && !empty($data->registration->address) 
                    && isset($data->registration->stateId) && !empty($data->registration->stateId)
                    && isset($data->registration->localGovtId) && !empty($data->registration->localGovtId) 
                    && isset($data->registration->countryId) && !empty($data->registration->countryId)
                    && isset($data->registration->idImage) && !empty($data->registration->idImage)
                    && isset($data->registration->idName) && !empty($data->registration->idName)
                    && isset($data->registration->locLatitude) && !empty($data->registration->locLatitude)
                    && isset($data->registration->locLongitude) && !empty($data->registration->locLongitude) 
					/* && isset($data->DOB) && !empty($data->DOB)
					&& isset($data->GENDER) && !empty($data->GENDER)
					&& isset($data->BUSINESS_TYPE) && !empty($data->BUSINESS_TYPE) */
                    && isset($data->key2) && !empty($data->key2)
                   // && isset($data->registration->idFileType) && !empty($data->registration->idFileType) 
                   // && isset($data->registration->businessFileType) && !empty($data->registration->businessFileType)
			){

				error_log("inside all inputs are set correctly");
				$firstName = $data->registration->firstName;
				$LastName = $data->registration->lastName;
				$bvn = $data->registration->bvn;
				$outletName = $data->registration->outletName;
				$mobile = $data->registration->mobile;
				$email = $data->registration->email;
				$address = $data->registration->address;
				$stateId = $data->registration->stateId;
				$localGovtId = $data->registration->localGovtId;
				$countryId = $data->registration->countryId;
				$contactName = $data->registration->firstName." ".$data->registration->lastName;
				$idImage = $data->registration->idImage;
				$idName = $data->registration->idName;
				$idFileType = $data->registration->idFileType;
				$dob = DOB;
				$gender = GENDER;
				$business_type = BUSINESS_TYPE;
				//$latitude = $data->registration->locLatitude;
				//$longitude = $data->registration->locLongitude;
				$signature= $data->signature;
				$key1 = $data->key1;
				$key2 = $data->key2;
				$language_id = 1;
  
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
				
				$skey0 = date("mdY");
				$skey1 = $nday;
				$skey2 = $local_signature;
						
				$skeya = str_pad($skey0.$skey1.$skey2, 16, '0', STR_PAD_LEFT);
				$skeyb = str_pad($skey2.$skey1.$skey0, 16, '0', STR_PAD_LEFT);
				error_log("skeya = ".$skeya.", skeyb = ".$skeyb);
				error_log("before calling Security::decrypt");
				$key1_result = AesCipher::decrypt($skeya, $key1);
				error_log("after calling Security::decrypt");
				error_log("key1_result = ".$key1_result);
				$tilda_found = strpos($key1_result, '~');
				if ( $tilda_found == false ) {
					$response["statusCode"] = 660;
					$response["result"] = "Error";
					$response["message"] = "Invalid Client Request...";
					error_log(json_encode($response));
					echo json_encode($response);
					return;
				}
				$key1_array = explode("~", $key1_result);
				$password = $key1_array[0];
				$uname = $key1_array[1];
				$token = $key1_array[2];
				$ltime = $key1_array[3];
				error_log ("uname = ".$uname.", passwd = ".$password.", token = ".$token);
						
				$key2_result = AesCipher::decrypt($skeyb, $key2);
				//error_log("key2_result = ".$key2_result);
				$tilda_found = strpos($key2_result, '~');
				if ( $tilda_found == false ) {
					$response["statusCode"] = 670;
					$response["result"] = "Error";
					$response["message"] = "Invalid Client Request....";
					error_log(json_encode($response));
					echo json_encode($response);
					return;
				}
						
				$key2_array = explode("~", $key2_result);
				$device_sno = $key2_array[0];
				$app_version = $key2_array[1];
				$device_location = $key2_array[2];
				error_log("device_sno = ".$device_sno.", app_version = ".$app_version.", device_location = ".$device_location);
				$pipe_found = strpos($device_location, '|');
				if ($pipe_found == true) {
					$device_location_array = explode("|", $device_location);
					$latitude = $device_location_array[0];
					$longitude = $device_location_array[1];
					error_log("latitude =".$latitude);
					error_log("longitude =".$longitude);
				}else {
					$latitude = "0.00";
					$longitude = "0.00";
				}
                        			
				if ( $local_signature == $signature ) {
			    $select_bvn_query = "select bvn from pre_application_info where bvn = '".$bvn."'";
			    error_log("select_bvn_query = ".$select_bvn_query);
			    $select_bvn_result = mysqli_query($con, $select_bvn_query);
			    if ($select_bvn_result) {
                        $select_bvn_count = mysqli_num_rows($select_bvn_result);
                        if ( $select_bvn_count == 0 ) {
                            $comments = "Registration request from mPos. IMEI = ".$device_sno;
                            $pre_application_id = generate_seq_num(2100, $con);
                            if($pre_application_id > 0) {
								$outletName = mysqli_real_escape_string($con, $outletName);
								$address = mysqli_real_escape_string($con, $address);
								$contactName = mysqli_real_escape_string($con, $contactName);
                                $insert_main_query = "INSERT into pre_application_info (pre_application_info_id, country_id, bvn, outlet_name, address1, local_govt_id, state_id, mobile_no, email, language_id, contact_person_name, contact_person_mobile, loc_latitude, loc_longitude, comments, status, create_user, create_time, dob,gender,business_type) values ($pre_application_id, $countryId, '$bvn', '$outletName', '$address', $localGovtId, $stateId, '$mobile', '$email', $language_id, '$contactName', '$mobile', left('$latitude', 10), left('$longitude', 10), '$comments', 'E', 6, now(),'$dob','$gender',$business_type)";
                                error_log("insert_main_query = ".$insert_main_query);
                                $insert_main_result = mysqli_query($con, $insert_main_query);
                                if($insert_main_result) {
                                    error_log("insert_main_query is success");
									$insert_second_query = "INSERT into pre_application_attachment (pre_application_attachment_id, pre_application_info_id, attachment_name, attachment_type, attachment_content,file) values (0, $pre_application_id, '$idName', '$idFileType', '$idImage','I')";
                                    $insert_second_result = mysqli_query($con, $insert_second_query);
                                    if ( $insert_second_result ) {
                                        $response["result"] = "Success";
                                        $response["message"] = "Registration is successful. Regd #".$pre_application_id;
                                        $response["statusCode"] = "0";
                                        $response["signature"] = $server_signature;
                                        $response["registrationId"] = $pre_application_id;
                                    }
                                    //$tmp_file = "id_file_".$pre_application_id.".jpg";
                                    //$image_data = base64_decode($idImage);
                                    //$id_image_new = imagecreatefromstring($image_data);
                                    //imagejpeg($id_image_new, $tmp_file);
                                    //imagedestroy($id_image_new);
                                }else {
                                    $response["result"] = "Error";
                                    $response["message"] = "DB Insert Error. Contact Kadick Admin";
                                    $response["statusCode"] = "190";
                                    $response["signature"] = $server_signature;
                                    $response["registrationId"] = 0;
                                }
                            }
                            else {
                                // DB failure
                                $response["result"] = "Error";
                                $response["statusCode"] = "200";
                                $response["message"] = "Failure: Error in getting registration no";
                                $response["signature"] = $server_signature;
                            }
                        }else {
                            // Duplicat BVN
                            $response["result"] = "Error";
                            $response["statusCode"] = "220";
                            $response["message"] = "Failure: BVN ".$bvn." is already used. Contact Kadick if otherwise";
                            $response["signature"] = $server_signature;
                        }
                    }else {
                            // DB failure
                            $response["result"] = "Error";
                            $response["statusCode"] = "210";
                            $response["message"] = "Failure: Error in checking BVN";
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
    	error_log("registration ==> ".json_encode($response));
	echo json_encode($response);
?>