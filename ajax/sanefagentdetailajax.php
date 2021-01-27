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
		$agent_select_query = "SELECT agent_sanef_detail_id, agent_code, sanef_agent_code, sanef_request_id, acc_trans_log_id, status, create_time, update_time FROM agent_sanef_detail WHERE status = 'S' and agent_code = '".$agentCode."'";
		error_log("agent_select_query = ".$agent_select_query);
		$agent_select_result = mysqli_query($con,$agent_select_query);
		$agent_select_count = mysqli_num_rows($agent_select_result);
		if($agent_select_count > 0) {
			$data = array();
			while ($row = mysqli_fetch_array($agent_select_result)) {
			
				
				$data[] = array("agentCode"=>$row['agent_code'],"sanefAgentCode"=>$row['sanef_agent_code'],"sanefRequestId"=>$row['sanef_request_id'],
								"accTransLogId"=>$row['acc_trans_log_id'],"status"=>$row['status'],"createTime"=>$row['create_time'],"update_time"=>$row['updateTime']);           
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
	if($action == "view") {
		$accTransLogId = mysqli_real_escape_string($con, $data->accTransLogId);
		$acc_trans_log_query = "SELECT a.agent_code, a.sanef_agent_code, a.sanef_request_id, IF(a.status = 'E',' E-Error',IF(a.status = 'S','S-Success',IF(a.status = 'I','I-InProgress',' O-Other'))) as status, a.create_time, a.update_time, b.request_message FROM  agent_sanef_detail a, acc_trans_log b  WHERE a.acc_trans_log_id = b.acc_trans_log_id and b.acc_trans_log_id = ".$accTransLogId;
		error_log("acc_trans_log_query = ".$acc_trans_log_query);
		$acc_trans_log_result = mysqli_query($con,$acc_trans_log_query);
		$acc_trans_log_count = mysqli_num_rows($acc_trans_log_result);
		if($acc_trans_log_count > 0) {
			$data = array();
			while ($row = mysqli_fetch_array($acc_trans_log_result)) {			
				$data[] = array("updateTime"=>$row['update_time'],"createTime"=>$row['create_time'],"status"=>$row['status'],"requestMessage"=>$row['request_message'],"agentCode"=>$row['agent_code'],"sanefAgentCode"=>$row['sanef_agent_code'],"sanefRequestId"=>$row['sanef_request_id']);           
			}
			if (!$acc_trans_log_result) {
				echo "Error: %s\n", mysqli_error($con);
				//exit();
			}else {
				echo json_encode($data);
			}
		}
		else {
			echo "<script type=\"text/javascript\">window.alert('No Rows Found For Request "+$accTransLogId+" !!');</script>";
		}
	}
	else if($action == "send") {		
		ini_set('max_execution_time', 120);
		set_time_limit(120);
		$agentCode = mysqli_real_escape_string($con, $data->agentCode);
		$agent_select_query = "SELECT b.user_name,a.country_id,d.sanef_request_id,d.sanef_agent_code, a.state_id  FROM agent_info a, user b, agent_sanef_detail d WHERE a.agent_code = d.agent_code and d.status = 'S' and a.user_id = b.user_id  and a.user_id = b.user_id and a.agent_code = '".$agentCode."'";
		error_log("agent_select_query = ".$agent_select_query);
		$agent_select_result = mysqli_query($con,$agent_select_query);
		if ($agent_select_result) {
			$agent_select_count = mysqli_num_rows($agent_select_result);
			if($agent_select_count > 0) {
				
				$row = mysqli_fetch_array($agent_select_result);
				$userName = $row['user_name'];		
				$countryId = $row['country_id'];	
				$requestId = $row['sanef_request_id'];
				$stateId = $row['state_id'];
				$superAgentCode = $row['sanef_agent_code'];					
				$response = array();	
				$current_time = date('Y-m-d H:i:s');
				$response['processingStartTime'] = $current_time;
				$country = mysqli_real_escape_string($con,$countryId);
				$superAgentCode =  mysqli_real_escape_string($con,$superAgentCode);
				$userName = mysqli_real_escape_string($con,$userName);		
				$partyCode = $agentCode;
				$partyType ='A';
				$sanef_seq_no =$requestId ;
				if ( $sanef_seq_no > 0 ) {							
					$sanefreqsend['requestId'] = $sanef_seq_no;
					$sanefreqsend['superAgentCode'] = $superAgentCode;
					$sanefreqsend['userName'] = $userName;
					$sanefreqsend['countryId'] = $country;	
						
					$request_message = json_encode($sanefreqsend);
					$request_message = mysqli_real_escape_string($con, $request_message);														
					$acc_trans_log_id = generate_seq_num(3400, $con);
					if($acc_trans_log_id > 0) {
						$acc_trans_log_query = "INSERT INTO acc_trans_log (acc_trans_log_id, service_feature_id, partner_id, party_type, party_code, country_id, state_id, request_message, message_send_time, create_user, create_time) VALUES ($acc_trans_log_id, $productId, $partnerId, '$partyType', '$partyCode', $countryId, $stateId, left('SANEF Agent Detail Request = $request_message', 1000), now(), $userId, now())";
						error_log("acc_trans_log query = ".$acc_trans_log_query);
						$acc_trans_log_result = mysqli_query($con, $acc_trans_log_query);						
						if ( $acc_trans_log_result ) {					
							date_default_timezone_set('Africa/Lagos');
							$nday = date('z')+1;
							$nyear = date('Y');
							$nth_day_prime = get_prime($nday);
							$nth_year_day_prime = get_prime($nday+$nyear);
							$local_signature = $nday + $nth_day_prime;
							$url = SANEFAPI_SERVER_DETAIL_AGENT;
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
											$response["agentCode"] = $api_response['agentCode'];
											$response["superAgentCode"] = $superAgentCode;
											$response["signature"] = $api_response['signature'];
											$response["responseCode"] = $api_response['responseCode'];
											$response["responseDescription"] = $api_response['responseDescription'];
											$response["agentType"] = $api_response['agentType'];
											$response["lastName"] = $api_response['lastName'];
											$response["firstName"] = $api_response['firstName'];
											$response["middleName"] = $api_response['middleName'];
											$response["businessName"] = $api_response['businessName'];
											$response["gender"] = $api_response['gender'];
											$response["phoneNumber2"] = $api_response['phoneNumber2'];
											$response["agentAddress"] = $api_response['agentAddress'];
											$response["phoneNumber1"] = $api_response['phoneNumber1'];
											$response["closestLandMark"] = $api_response['closestLandMark'];
											$response["bankVerififcationNumber"] = $api_response['bankVerififcationNumber'];
											$response["taxIdentififcationNumber"] = $api_response['taxIdentififcationNumber'];
											$response["agentBusiness"] = $api_response['agentBusiness'];
											$response["dateOfBirth"] = $api_response['dateOfBirth'];
											$response["localGovermentCode"] = $api_response['localGovermentCode'];
											$response["userName"] = $api_response['userName'];
											$response["success"] = $api_response['success'];
											$response["emailAddress"] = $api_response['emailAddress'];
											$response["processingStartTime"] = $api_response['processingStartTime'];
											$response["statusCode"] = $statusCode;
											$response["result"] = "Success";
											$response["message"] = "Agent [".$agentCode."] Detail Get Successfully. Sanef Agent Code: ".$api_response['agentCode'];
											$response["partnerId"] = $partnerId;											
										}else {
											error_log("inside statusCode != 0");
											$response["agentCode"] = "";
											$response["statusCode"] = $statusCode;
											$response["result"] = "Error";
											$response["message"] = "Agent [".$agentCode."] Detail Not Get Successfully.";
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
										$response["message"] = "Agent [".$agentCode."] Detail Not Get Successfully.";
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
									$response["message"] = "Agent [".$agentCode."] Detail Not Get Successfully.";
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
						$response["message"] = "DBError in generate trans log id";
						$response["partnerId"] = $partnerId;
					}
					}else {
						$response["agentCode"] = "";
						$response["statusCode"] = 130;
						$response["result"] = "Error";
						$response["message"] = "DBError in getting sanef request Id";
						$response["partnerId"] = $partnerId;
					}
								
				}
				else {
					$response["agentCode"] = "";
					$response["statusCode"] = 130;
					$response["result"] = "Error";
					$response["message"] = "No Rows Found For Sanef Detail";
					$response["partnerId"] = $partnerId;
				}
			}
			else {
					$response["agentCode"] = "";
					$response["statusCode"] = 130;
					$response["result"] = "Error";
					$response["message"] = "DBError in Getting Sanef Detail";
					$response["partnerId"] = $partnerId;
				}
			error_log("response = ".json_encode($response));
			echo json_encode($response);
	}
?>