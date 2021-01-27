<?php 

	include('../common/sessioncheck.php');
	include('flexiRechargeRequest.php');
	
	$data = json_decode(file_get_contents("php://input"));	
	$country = 566;
	$product = 1;
	$circle = 1;
	$brand = 1;
	$mobile =$data->mobile;
	$lineType =$data->lineType; 
	$reMobile = $data->reMobile;
	$operatorCode =$data->operatorCode; 
	$amount = $data->amount;
	$profileId = $_SESSION['profile_id'];	
	$userId = $_SESSION['user_id'];	
	$userType = $_SESSION['user_type'];
	$partytype = $_SESSION['party_type'];
	$partyCode = $_SESSION['party_code'];
	
	if($operatorCode == "MTN") {
		if($lineType == "pre") {
			$plan = 52;
		}
		else {
			$plan = 93;
		}
	}
	if($operatorCode == "ATL") {
		$plan = 53;
	}
	if($operatorCode == "GLO") {
		$plan = 77;
	}
	if($operatorCode == "9M") {
		$plan = 81;
	}
	$error_code = "";
	$error_description = "";
	$agent_info_wallet_status = check_agent_info_wallet_status($partytype, $partyCode, $con);
	if ($agent_info_wallet_status != 0 ) {
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
		$response = array();
		$response["responseCode"] = 1018;
		$response["signature"] = 1018;
		$response["responseDescription"] = $resp_message;
		error_log(json_encode($response));
	}
	else {
	//Checking available_balance
		$available_balance = check_agent_available_balance($userId, $con);						
		error_log("available_balance response for  = ".$available_balance);
		//$log .= $log_constant."".date('Y:m:d h.m.s')." available_balance = ".$available_balance.PHP_EOL;
		if ( $available_balance <= 0 || $available_balance == "" || $available_balance == null ) {
			$response = array();
			$response["responseCode"] = 1016;
			$response["signature"] = 1016;
			$response["responseDescription"] = "Agent Available Balance is not available";
			error_log(json_encode($response));
		}
		else {
			error_log("Amount ".floatval($amount));error_log("available_balance ".floatval($available_balance));
			if ( floatval($amount) < floatval($available_balance) ) {
				$rateforplanquery = "SELECT dn_code, dn_value, retailer_rate, operator_id, operator_plan_description from evd_master2 where operator_code = '".$operatorCode."' and brand_id = ".$brand." and circle_id = ".$circle." and product_id = ".$product." and operator_plan_id = ".$plan; 
				error_log("PlanRate query = ".$rateforplanquery);
				$rateforplanresult = mysqli_query($con,$rateforplanquery); 
				$row	=mysqli_fetch_assoc($rateforplanresult);
				$dnCode = $row['dn_code'];
				$dnValue = $row['dn_value'];
				$retailerRate = $row['retailer_rate'];
				$OperatorId = $row['operator_id'];
				$planDesc = $row['operator_plan_description'];
				error_log("DnCode = ".$dncode. ", Dn value = ".$dnvalue.", Retailer Rate = ".$retailerRate.", Operator Id = ".$operatorId);
				error_log("plan = ".$plan. ", operatorCode = ".$operatorCode.",reMobile= ".$reMobile.", brand Id = ".$brand);
				if($mobile != $reMobile) {
					echo $msg= "<span id='res_message' style='color:red;font-size:14px;'>Mobile Number and Re-mobile number should be same</span>";
					error_log("Mobile Number and Remobile Number do not match == > Mobile".$mobile." Re-mobile = ".$reMobile);
				}else {
					if($operatorCode == "MTN") {
						$lineType = $lineType;
					}
					else {
						$lineType = -1;
					}
					error_log("before calling new flexiRechargeRequest");
					$flexi = new flexiRechargeRequest($country, $product, $circle, $brand, $OperatorId, $operatorCode, $plan, $planDesc, $mobile, $reMobile, $dnValue, $dnCode, $amount, $userId, 1, $profileId ,$lineType);
					error_log("after calling new flexiRechargeRequest");
					error_log("before calling checkPlan");
					$flexi->checkPlan($con);
					error_log("after calling checkPlan");
					if( $flexi->flag == 0 ){
						$input_message = "Input => [".$flexi->operatorCode."] ".$flexi->mobile." - ".$flexi->oprPlanDesc." for NGN ".$flexi->amount;
						error_log("input_message".$input_message);
						$flexi->checkLimits($con);
						if ( $flexi->flag == 0 ) {
							$flexi->startEvdTransaction($con);
							if ( $flexi->flag == 0 ) {
								$requestSend = $flexi->sendRequest();
								error_log("just received");
								$statusCode = $requestSend->getStatusCode();
								error_log("status code = ".$statusCode);
								if( $statusCode == "200" ){
									$response_json_message = $requestSend->getBody();
									error_log("response_json_message = ".$response_json_message);

									$response_message = json_decode($response_json_message);
									$flexi->updateEvdTransactionLog($con, $response_json_message, $response_message->errorCode, $response_message->errorDescription);
									error_log("after calling updateEvdTransactionLog");
									if ( $flexi->flag == 0 ) {
										if($response_message->errorCode == 0){
											//$display_message = $response_message->errorDescription." => ".$response_message->{'flexiMessage'}->{'message'};
											$display_message = "Response => ".$response_message->{'flexiMessage'}->{'message'};
											//error_log("display_message = $display_message");
											//error_log("inside error_code = 0");
											$flexi->updateUserWallet($con,$userType,$partyCode,$response_message->{'flexiMessage'}->{'refno'});

											if( $flexi->flag == 0 ){
												//error_log("before calling finishEvdTransaction");
												$flexi->finishEvdTransaction($response_message->{'flexiMessage'}->{'refno'},$con);
												//error_log("after calling finishEvdTransaction");
												if($flexi->flag == 0) {
													$response["responseCode"] = 1024;
													$response["signature"] = 1024;
													$response["responseDescription"] = "$input_message $display_message";
													error_log(json_encode($response));						
													//echo $msg0 = "<span id='res_message0' style='color:blue;font-size:13px;'>$input_message</span><br />";
													//echo $msg0 = "<span id='res_message0' style='color:blue;font-size:14px;'>$input_message</span><br />";
													//echo $msg= "<span id='res_message' style='color:red;font-size:13px;'>$display_message</span>";
													//echo $msg= "<span id='res_message' style='color:red;font-size:14px;'>$display_message</span><input type='button' class='".$response_message->transLogId."' id='btn_print' value2='".$response_message->flexiMessage->operatorCode."' name='btn_print'  value='Print Receipt' style='padding-right:12px;padding-left:8px;padding-bottom:0px;border-bottom-width:0px;height:20px;border-right-width:0px;border-left-width:1px;width:120px;margin-right:5px;background-color:orange;color:white' />";
												}else {
													//echo $msg0 = "<span id='res_message0' style='color:blue;font-size:14px;'>$input_message</span><br/>";
													//echo $msg= "<span id='res_message' style='color:red;font-size:14px;'>Evd Transaction Error. Try After some time</span>";
													error_log("Error in updating Wallet");
													$response["responseCode"] = 1024;
													$response["signature"] = 1024;
													$response["responseDescription"] = "$input_message Evd Transaction Error. Try After some time";
													error_log(json_encode($response));	
													error_log("Error in finishing EVD transaction ");
												}
											}else {
												//echo $msg0 = "<span id='res_message0' style='color:blue;font-size:14px;'>$input_message</span><br/>";
												//echo $msg= "<span id='res_message' style='color:red;font-size:14px;'>Error in Wallet Update..Contact Kadick Admin</span>";
												error_log("Error in updating Wallet");
												$response["responseCode"] = 1023;
												$response["signature"] = 1023;
												$response["responseDescription"] = "$input_message Error in Wallet Update..Contact Kadick Admin";
												error_log(json_encode($response));	
											}
										}else{
											//echo $msg0 = "<span id='res_message0' style='color:blue;font-size:14px;'>$input_message</span><br/>";
											$display_message = "Error: [".$response_message->errorCode."] ".$response_message->errorDescription;
											error_log("display_message = $display_message");
											error_log("inside error_code != 0");
											//echo $msg= "<span id='res_message' style='color:red;font-size:14px;'>$display_message</span>";
											error_log("Response code is not equal to 0. error_desc = ".$error_desc);
											$response = array();
											$response["responseCode"] = 0;
											$response["signature"] = 0;
											$response["responseDescription"] = "$input_message <br /> $display_message";
											error_log(json_encode($response));	
										}													
									}else {
										$response = array();
										$response["responseCode"] = 1022;
										$response["signature"] = 1022;
										$response["responseDescription"] = "$input_message Evd Transaction Error..... Try After some time";
										error_log(json_encode($response));								}											
								}else {
									$response = array();
									$response["responseCode"] = 1021;
									$response["signature"] = 1021;
									$response["responseDescription"] = "$input_message Evd Transaction Error..... Try After some time";
									error_log(json_encode($response));								
									error_log("Evd Http Status Code Error. Status code = ".$statusCode);								
								}																												
							}else{
								$response = array();
								$response["responseCode"] = 1020;
								$response["signature"] = 1020;
								$response["responseDescription"] = "$input_message Evd Transaction Error..... Try After some time";
								error_log(json_encode($response));								
								error_log("Error in start Evd Transaction");
							}
						}else {
							$response = array();
							$response["responseCode"] = 1019;
							$response["signature"] = 1019;
							$response["responseDescription"] = "Exceed Available Credit Limit";
							error_log(json_encode($response));							
						}						
					}else {
						$response = array();
						$response["responseCode"] = 1018;
						$response["signature"] = 1018;
						$response["responseDescription"] = "Invalid Request";
						error_log(json_encode($response));
						
					}
				}
		}
		else {
			$response = array();
			$response["responseCode"] = 1016;
			$response["signature"] = 1016;
			$response["responseDescription"] = "Amount is greater than Available Balance";
			error_log(json_encode($response));
		}
	}
	echo json_encode($response);
}
?> 
 