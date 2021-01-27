<?php
	include('../common/admin/configmysql.php');
	include("../common/admin/finsol_crypt.php");
	include ("get_prime.php");	
	require_once ("AesCipher.php");
	error_log("inside pcposapi/version_check.php");

	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		error_log("inside post request method");
        $data = json_decode(file_get_contents("php://input"));
        error_log("version_check <== ".json_encode($data));				

		if(isset($data -> operation) && $data -> operation == 'VERSION_CHECK') {
			error_log("inside operation == VERSION_CHECK method");

			if ( isset($data->currentVersion) && !empty($data->currentVersion) && isset($data->signature) && !empty($data->signature) ) {			
			
				$signature= $data->signature;
				$key1 = $data->key1;
				$currentVersion = $data->currentVersion;

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
				
				if ( $local_signature != $signature ) {
					$response["statusCode"] = 650;
					$response["result"] = "Error";
					$response["message"] = "Invalid Client Request..";
					error_log(json_encode($response));
					echo json_encode($response);
					return;
				}

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
								
				if ( $local_signature == $signature ) {
                    $version_check_query = "SELECT control_key, control_value1 from icom_control where active = 'Y' and control_key in ('AGENT_FINWEB_APP_VER', 'AGENT_FINWEB_APP_URL')";
					error_log("version_check_query = ".$version_check_query);
                    $version_check_result = mysqli_query($con, $version_check_query);
                    $control_found = "N";
                    $db_version = "";
                    $db_apk_url = "";
                    if ($version_check_result) {		
                        while($version_check_row = mysqli_fetch_assoc($version_check_result)) {
                            $control_found = "Y";	
                            if ( "AGENT_FINWEB_APP_VER" == $version_check_row['control_key']) {
                                $db_version = $version_check_row['control_value1'];
                            }else if ("AGENT_FINWEB_APP_URL" == $version_check_row['control_key']) {
                                $db_apk_url = $version_check_row['control_value1'];
                            }
                        }
                        error_log("currentVersion = ".$currentVersion.", db_version = ".$db_version.", db_apk_url = ".$db_apk_url);
                        if ( "Y" == $control_found) {
                            if ( $db_apk_url != "" ) {
                                if ( $db_version != $currentVersion) {
                                    error_log("inside updateRequird = 100");
                                    $response['updateRequired'] = 100;
                                    $response['newAPKLocation'] = $db_apk_url;
                                    $response['newAPKVersion'] = $db_version;
                                }else {
                                    error_log("inside updateRequird = 2");
                                    $response['updateRequired'] = 200;
                                }
                            }else {
                                error_log("inside updateRequird = 3");
                                $response['updateRequired'] = 3;
                            }
                        }else {
                            error_log("inside updateRequird = 4");
                            $response['updateRequired'] = 4;
                        }
                        $response["signature"] = $server_signature;
                        $response["statusCode"] = "0";
                        $response["message"] = "Version Check completed";
                        $response["result"] = "Success";
                    }else {
                        // DB failure
                        $response['updateRequired'] = 5;
						$response["statusCode"] = "10";
						$response["result"] = "Error";
						$response["message"] = "Failure: Error in reading charges from DB";
					}
				}else {
                    // Invalid Singature
                    $response['updateRequired'] = 5;
					$response["statusCode"] = "20";
					$response["result"] = "Error";
					$response["message"] = "Failure: Invalid request";
				}
			}else {
                // Invalid Data
                $response['updateRequired'] = 5;
				$response["statusCode"] = "30";
				$response["result"] = "Error";
				$response["message"] = "Failure: Invalid Data";
				$response["signature"] = 0;	
			}
		}else {
            // Invalid Operation
            $response['updateRequired'] = 5;
			$response["statusCode"] = "40";
			$response["result"] = "Error";
			$response["message"] = "Failure: Invalid Operation";
			$response["signature"] = 0;	
		}
	}else {
        // Invalid Request Method
        $response['updateRequired'] = 5;
		$response["statusCode"] = "50";
		$response["result"] = "Error";
		$response["message"] = "Failure: Invalid Request Method";
		$response["signature"] = 0;	
	}
	error_log("version_check ==> ".json_encode($response));
	echo json_encode($response);
?>