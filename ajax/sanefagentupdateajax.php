<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));
	
	require '../api/get_prime.php';
	require '../api/security.php';
	require '../common/gh/autoload.php';
	include ("functions.php");
	//ERROR_REPORTING(E_ALL);
	$action = mysqli_real_escape_string($con, $data->action);
	$userId = $_SESSION['user_id'];
	$localGvtid = $_SESSION['local_govt_id'];
	$partnerId = 9;
	$productId = 28;
	$countryId = $_SESSION['country_id'];
	
	if($action == "query") {
	
		$agentCode = mysqli_real_escape_string($con, $data->agentCode);
		$agent_select_query = "SELECT a.agent_name, a.contact_person_name, a.state_id, a.contact_person_mobile, a.login_name, IFNULL(a.bvn,'-') as bvn, IFNULL(a.outlet_name,'-') as outlet_name, a.country_id,ifnull(a.gender,'Male') as gender, IFNULL(a.address1, '-') as address1, IFNULL(a.address2,'-') as address2, IFNULL(a.business_type,'5') as business_type, a.email, a.local_govt_id, IFNULL((SELECT name FROM local_govt_list WHERE local_govt_id = a.local_govt_id),'') as logvt, a.loc_latitude, a.loc_longitude, IFNULL(a.dob,'1997-01-01') as dob, IFNULL(c.pos_pin,'0000') as pos_pin FROM agent_info a, user b, user_pos c, agent_sanef_detail d WHERE a.agent_code = d.agent_code and d.status = 'S' and  b.user_id = c.user_id and a.user_id = b.user_id and a.user_id = c.user_id   and a.user_id = b.user_id and a.agent_code = '".$agentCode."'";
		error_log("agent_select_query = ".$agent_select_query);
		$agent_select_result = mysqli_query($con,$agent_select_query);
		$agent_select_count = mysqli_num_rows($agent_select_result);
		if($agent_select_count > 0) {
			$data = array();
			while ($row = mysqli_fetch_array($agent_select_result)) {
				$splitusername = explode(" ", $row['agent_name']);
				$firstName = $splitusername[0];
				$lastName = $splitusername[1];
								
				if(empty($lastName) || $lastName == "" || $lastName == null) {
					$lastName =  $row['contact_person_name'];
				}
				
				$data[] = array("state"=>$row['state_id'],"state"=>$row['state_id'],"pin"=>$row['pos_pin'],"mobile"=>$row['contact_person_mobile'],"country"=>$row['country_id'],"agentCode"=>$row['agentCode'],"firstName"=>$firstName,"lastName"=>$lastName,"userName"=>$row['login_name']
								,"bvn"=>$row['bvn'],"outletName"=>$row['outlet_name'],"gender"=>$row['gender'],"address1"=>$row['address1'],"businessType"=>$row['business_type']
								,"email"=>$row['email'],"localGovtId"=>$row['local_govt_id'],"logvt"=>$row['logvt'],"locLatitude"=>$row['loc_latitude']
								,"locLongitude"=>$row['loc_longitude'],"dob"=>$row['dob'],"businessTypeDesc"=>$row['business_type']);           
			}
			if (!$agent_select_result) {
				echo "Error: %s\n", mysqli_error($con);
				//exit();
			}else {
				echo json_encode($data);
			}
		}
		else {
			echo "<script type=\"text/javascript\">window.alert('No Rows Found For Agent "+$agentCode+" !!');</script>";
		}
	}
	else if($action == "submit") {		
		ini_set('max_execution_time', 120);
		set_time_limit(120);
		$agentCode = mysqli_real_escape_string($con, $data->agentCode);
		$agent_select_query = "SELECT a.agent_code,a.agent_name, a.contact_person_name, a.state_id, a.contact_person_mobile, a.login_name, IFNULL(a.bvn,'-') as bvn, IFNULL(a.outlet_name,'-') as outlet_name, a.country_id,ifnull(a.gender,'Male') as gender, IFNULL(a.address1, '-') as address1, IFNULL(a.address2,'-') as address2, IFNULL(a.business_type,'5') as business_type, a.email, a.local_govt_id, IFNULL((SELECT name FROM local_govt_list WHERE local_govt_id = a.local_govt_id),'') as logvt, a.loc_latitude, a.loc_longitude, IFNULL(a.dob,'1997-01-01') as dob, IFNULL(c.pos_pin,'0000') as pos_pin FROM agent_info a, user b, user_pos c, agent_sanef_detail d WHERE a.agent_code = d.agent_code and d.status = 'S' and  b.user_id = c.user_id and a.user_id = b.user_id and a.user_id = c.user_id   and a.user_id = b.user_id and a.agent_code = '".$agentCode."'";
		error_log("agent_select_query = ".$agent_select_query);
		$agent_select_result = mysqli_query($con,$agent_select_query);
		if ($agent_select_result) {
			$agent_select_count = mysqli_num_rows($agent_select_result);
			if($agent_select_count > 0) {
				$prvdata = array();
					$row = mysqli_fetch_array($agent_select_result);
					$splitusername = explode(" ", $row['agent_name']);
					$firstName = $splitusername[0];
					$lastName = $splitusername[1];
									
					if(empty($lastName) || $lastName == "" || $lastName == null) {
						$lastName =  $row['contact_person_name'];
					}
					$business_type_desc = "Others";
					if ($row['business_type'] == 0 ) {
						$business_type_desc = "0-Pharmacy";
					}else if ($row['business_type'] == 1 ) {
						$business_type_desc = "1-Gas Station";
					}else if ($row['business_type'] == 2 ) {
						$business_type_desc = "2-Saloon";
					}else if ($row['business_type'] == 3 ) {
						$business_type_desc = "3-Groceries Stores";
					}else if ($row['business_type'] == 4 ) {
						$business_type_desc = "4-Super Market";
					}else if ($row['business_type'] == 5 ) {
						$business_type_desc = "5-Mobile Network Outlets";
					}else if ($row['business_type'] == 6 ) {
						$business_type_desc = "6-Restaurants";
					}else if ($row['business_type'] == 7 ) {
						$business_type_desc = "7-Hotels";
					}else if ($row['business_type'] == 8 ) {
						$business_type_desc = "8-Cyber Cafe";
					}else if ($row['business_type'] == 9 ) {
						$business_type_desc = "9-Post Office";
					}else if ($row['business_type'] == 10 ) {
						$business_type_desc = "10-Pharmacy";
					}
					$userName =  $row ['login_name'];
					$prvdata['state'] = $row['state_id'];		
					$prvdata['pin'] = $row['pin'];	
					$prvdata['mobile'] = $row['contact_person_mobile'];	
					$prvdata['country'] = $row['country_id'];	
					$prvdata['agentCode'] = $row['agent_code'];
					$prvdata['firstName'] = $firstName;
					$prvdata['lastName'] = $lastName;
					$prvdata['userName'] = $row['login_name'];
					$prvdata['bvn'] = $row['bvn'];
					$prvdata['outletName'] = $row['outlet_name'];					
					$prvdata['gender'] = $row['gender'];
					$prvdata['address1'] = $row['address1'];
					$prvdata['businessType'] = $row['business_type'];
					$prvdata['email'] = $row['email'];
					$prvdata['localGovtId'] = $row['local_govt_id'];
					$prvdata['logvt'] = $row['logvt'];
					$prvdata['locLatitude'] = $row['loc_latitude'];
					$prvdata['locLongitude'] = $row['locLongitude'];
					$prvdata['dob'] = $row['dob'];
					$prvdata['businessTypeDesc'] = $business_type_desc;    
						
				$pre_req_msg =  json_encode($prvdata);			
				error_log("pre_req_msgit <== ".json_encode($prvdata));					
				$agent_sanef_update_id = generate_seq_num(3800, $con);
				$response = array();
				if($agent_sanef_update_id > 0) {
					$agent_saenf_update_query = "INSERT INTO agent_saenf_update (agent_sanef_update_id, agent_code, old_value, create_time) VALUES ($agent_sanef_update_id, '$agentCode', '$pre_req_msg', now())";
					error_log("agent_saenf_update query = ".$agent_saenf_update_query);
					$agent_saenf_update_result = mysqli_query($con, $agent_saenf_update_query);	
					$current_time = date('Y-m-d H:i:s');
					$response['processingStartTime'] = $current_time;
					$country = mysqli_real_escape_string($con,$data->country);
					$superAgentCode = SANEF_SUPER_AGENT_CODE;
					$bankCode = mysqli_real_escape_string($con,$data->bvn);
					$agentType = "SubAgent";
					$lastName = mysqli_real_escape_string($con,$data->lastName);
					$middleName = "";
					$gender = mysqli_real_escape_string($con,$data->gender);
					$mobile = mysqli_real_escape_string($con,$data->mobile);
					$agentAddress = mysqli_real_escape_string($con,$data->agentAddress);
					$businessAddress = mysqli_real_escape_string($con,$data->agentAddress);
					$email = mysqli_real_escape_string($con,$data->email);
					$bankVerififcationNumber = mysqli_real_escape_string($con,$data->bvn);	
					$agentBusiness = mysqli_real_escape_string($con,$data->outletName);	
					$dob = mysqli_real_escape_string($con,$data->dob);
					$localgovernment = mysqli_real_escape_string($con,$data->localGvtId);	
					$latitude = mysqli_real_escape_string($con,$data->latitude);
					$longitude = mysqli_real_escape_string($con,$data->longitude);
					$userName = mysqli_real_escape_string($con,$data->userName);				
					$password = $userName."000";
					$transactionPin = mysqli_real_escape_string($con,$data->pin);			
					$service_feature_code = 'SAU';							
					$sanefreqsend = array();							
					$partyCode = $agentCode;
					$partyType ='A';
					$agentCodeSplit = explode("AG",$agentCode);
					$agentCodeSeq = $agentCodeSplit[1];
					$kadickAgentCode = "9080".$agentCodeSeq;							
					$sanef_seq_no = generate_seq_num(3700, $con);
					$countryId = ADMIN_COUNTRY_ID;
					$stateId = ADMIN_STATE_ID;

					if ( $sanef_seq_no > 0 ) {							
						$sanefreqsend['requestId'] = $sanef_seq_no;
						$sanefreqsend['superAgentCode'] = $superAgentCode;
						$sanefreqsend['agentCode'] = $kadickAgentCode;
						$sanefreqsend['bankCode'] = $bankVerififcationNumber;
						$sanefreqsend['agentType'] = $agentType;
						$sanefreqsend['lastName'] = $lastName;
						$sanefreqsend['firstName'] = $firstName;
						$sanefreqsend['middleName'] = $middleName;
						$sanefreqsend['businessName'] = $agentBusiness;
						$sanefreqsend['gender'] = $gender;
						$sanefreqsend['phoneNumber1'] = $mobile;
						$sanefreqsend['agentAddress'] = $agentAddress;
						$sanefreqsend['businessAddress'] = $agentAddress;
						$sanefreqsend['latitude'] = $latitude;
						$sanefreqsend['longtitude'] = $longitude;
						$sanefreqsend['emailAddress'] = $email;
						$sanefreqsend['bankVerififcationNumber'] = $bankVerififcationNumber;
						$sanefreqsend['agentBusiness'] = $agentBusines;
						$sanefreqsend['dateOfBirth'] = $dob;
						$sanefreqsend['localGovermentCode'] = $localgovernment;
						$sanefreqsend['userName'] = $userName;
						$sanefreqsend['password'] = $password;
						$sanefreqsend['transactionPin'] = $transactionPin;
						$request_message = json_encode($sanefreqsend);
						$request_message = mysqli_real_escape_string($con, $request_message);														
						$acc_trans_log_id = generate_seq_num(3400, $con);
						if($acc_trans_log_id > 0) {
							$acc_trans_log_query = "INSERT INTO acc_trans_log (acc_trans_log_id, service_feature_id, partner_id, party_type, party_code, country_id, state_id, request_message, message_send_time, create_user, create_time) VALUES ($acc_trans_log_id, $productId, $partnerId, '$partyType', '$partyCode', $countryId, $stateId, left('SANEF Agent Update Request = $request_message', 1000), now(), $userId, now())";
							error_log("acc_trans_log query = ".$acc_trans_log_query);
							$acc_trans_log_result = mysqli_query($con, $acc_trans_log_query);						
							if ( $acc_trans_log_result ) {
								$sanef_select_query = "select agent_sanef_detail_id from agent_sanef_detail where agent_code = '".$agentCode."'";
								error_log("sanef_select_query = ".$sanef_select_query);
								$sanef_select_result = mysqli_query($con, $sanef_select_query);
								if ( $sanef_select_result ) {
									$sanef_select_count = mysqli_num_rows($sanef_select_result);
									if($sanef_select_count > 0) {
										$sanef_update_query = "update agent_sanef_detail set sanef_request_id = ".$sanef_seq_no.", acc_trans_log_id = ".$acc_request_id.", status = 'I', update_time = now() where agent_code = '".$agentCode."'";
										error_log("sanef_update_query = ".$sanef_update_query);
										$sanef_update_result = mysqli_query($con, $sanef_update_query);
										if (!$sanef_update_query) {
											error_log("Error in updating agent_sanef_detail table");
										}
									}else {
										$sanef_insert_query = "insert into agent_sanef_detail (agent_sanef_detail_id, agent_code, sanef_request_id, acc_trans_log_id, status, create_time) values (0, '$agentCode', $sanef_seq_no, $acc_trans_log_id, 'I', now())";
										error_log("sanef_insert_query = ".$sanef_insert_query);
										$sanef_insert_result = mysqli_query($con, $sanef_insert_query);
										if (!$sanef_insert_result) {
											error_log("Error in inserting agent_sanef_detail table");
										}
									}
									date_default_timezone_set('Africa/Lagos');
									$nday = date('z')+1;
									$nyear = date('Y');
									$nth_day_prime = get_prime($nday);
									$nth_year_day_prime = get_prime($nday+$nyear);
									$local_signature = $nday + $nth_day_prime;
									$url = SANEFAPI_SERVER_UPDATE_AGENT;
									$tsec = time();
									$raw_data1 = SANEFAPI_SERVER_APP_PASSWORD.FINWEB_SERVER_SHORT_NAME."|".SANEFAPI_SERVER_APP_USERNAME.FINWEB_SERVER_SHORT_NAME."|".$tsec;
									error_log("raw_data1 = ".$raw_data1);
									$key1 = base64_encode($raw_data1);
									error_log("key1 = ".$key1);
									error_log("before calling post");
									error_log("url = ".$url);
									$sanefreqsend['key1'] = $key1;
									$sanefreqsend['signature'] = $local_signature;
									error_log("request sent ==> ".json_encode($sanefreqsend));
									$ch = curl_init($url);
									curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($sanefreqsend));
									curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
									curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, SANEF_CURL_CONNECTION_TIMEOUT);
									curl_setopt($ch, CURLOPT_TIMEOUT, SANEF_CURL_TIMEOUT);
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
											$update_query = "UPDATE acc_trans_log SET  response_message = '$curl_response', response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE acc_trans_log_id = $acc_trans_log_id";
											error_log("update_query = ".$update_query);
											$update_query_result = mysqli_query($con, $update_query);
											if($statusCode === 0) {
												error_log("inside statusCode == 0");
												$update_query = "UPDATE agent_sanef_detail SET status = 'S', update_time = now(), sanef_agent_code = '".$api_response['agentCode']."' WHERE agent_code  = '$agentCode'";
												error_log("update_query = ".$update_query);
												$update_query_result = mysqli_query($con, $update_query);											
												$response["agentCode"] = $api_response['agentCode'];
												$response["statusCode"] = $statusCode;
												$response["result"] = "Success";
												$response["message"] = "Agent [".$agentCode."] Updated Successfully. Sanef Agent Code: ".$api_response['agentCode'];
												$response["partnerId"] = $partnerId;
												$update_agent_saenf_update_query = "UPDATE agent_saenf_update SET acc_trans_log_id =  $acc_trans_log_id, update_time = now(), new_value = '$request_message ' WHERE agent_sanef_update_id = $agent_sanef_update_id and agent_code  = '$agentCode'";
												error_log("update_query = ".$update_agent_saenf_update_query);
												$update_query_result = mysqli_query($con, $update_agent_saenf_update_query);
											}else {
												error_log("inside statusCode != 0");
												$response["agentCode"] = "";
												$response["statusCode"] = $statusCode;
												$response["result"] = "Error";
												$response["message"] = "Agent [".$agentCode."] not updated.";
												$response["partnerId"] = $partnerId;
											}
										}else {
											error_log("inside httpcode != 200");
											$statusCode = $httpcode;
											$responseDescription = "HTTP Protocol Error";
											error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
											$update_query = "UPDATE acc_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE acc_trans_log_id = $acc_trans_log_id";
											error_log("update_query = ".$update_query);
											$update_query_result = mysqli_query($con, $update_query);
											$response["agentCode"] = "";
											$response["statusCode"] = $statusCode;
											$response["result"] = "Error";
											$response["message"] = "Agent [".$agentCode."] not updated..";
											$response["partnerId"] = $partnerId;
										}
									}else {
										error_log("curl_error != 0 ");
										$statusCode = $curl_error;
										$responseDescription = "CURL Execution Error";
										$comments = $statusCode." - ".$responseDescription;
										error_log("statusCode = ".$statusCode.", responseDescription = ".$responseDescription);
										$update_query = "UPDATE acc_trans_log SET  response_received = 'Y', error_code = $statusCode, error_description = '".$responseDescription."', message_receive_time = now() WHERE acc_trans_log_id = $acc_trans_log_id";
										error_log("update_query = ".$update_query);
										$update_query_result = mysqli_query($con, $update_query);
										$response["agentCode"] = "";
										$response["statusCode"] = $statusCode;
										$response["result"] = "Error";
										$response["message"] = "Agent [".$agentCode."] not updated...";
										$response["partnerId"] = $partnerId;
									}
								}else {
									$response["agentCode"] = "";
									$response["statusCode"] = 110;
									$response["result"] = "Error";
									$response["message"] = "DBError in check agent details in sanef config";
									$response["partnerId"] = $partnerId;
								}
							}else {
								$response["agentCode"] = "";
								$response["statusCode"] = 120;
								$response["result"] = "Error";
								$response["message"] = "DBError in trans log result";
								$response["partnerId"] = $partnerId;
							}
						}else {
							$response["agentCode"] = "";
							$response["statusCode"] = 130;
							$response["result"] = "Error";
							$response["message"] = "DBError in trans log update";
							$response["partnerId"] = $partnerId;
						}
					}else {
						$response["agentCode"] = "";
						$response["statusCode"] = 130;
						$response["result"] = "Error";
						$response["message"] = "DBError in creating sanef request Id";
						$response["partnerId"] = $partnerId;
					}
				}	
				else {
					$response["agentCode"] = "";
					$response["statusCode"] = 130;
					$response["result"] = "Error";
					$response["message"] = "DBError in creating sanef request update Id";
					$response["partnerId"] = $partnerId;
				}					
			}
			else {
				$response["agentCode"] = "";
				$response["statusCode"] = 130;
				$response["result"] = "Error";
				$response["message"] = "DBError in creating sanef request update Id";
				$response["partnerId"] = $partnerId;
			}
		}
		else {
			$response["agentCode"] = "";
			$response["statusCode"] = 130;
			$response["result"] = "Error";
			$response["message"] = "No Rows Found For Sanedf Agent";
			$response["partnerId"] = $partnerId;
		}
		error_log("response = ".json_encode($response));
		echo json_encode($response);
	}
?>