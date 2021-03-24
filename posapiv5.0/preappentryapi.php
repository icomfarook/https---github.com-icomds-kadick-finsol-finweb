<?php
	include('../common/admin/configmysql.php');
	$data = json_decode(file_get_contents("php://input"));	
	require '../api/get_prime.php';
	require_once("db_connect.php");
	error_log("inside pcposapi/preappentryapi.php");
	require_once("../ajax/mailfunction.php");
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                error_log("inside post request method");
                $data = json_decode(file_get_contents("php://input"));
                error_log(json_encode($data));				
                if(isset($data -> operation)) {
                        error_log("inside isset data->operation method");
                        $operation = $data -> operation;
                        if(!empty($operation)) {
							error_log("inside not empty PRE_APP_ENTRY method");
							if($operation == 'preApplicationEntry'){
								error_log("inside operation == preApplicationEntry method");
								$createuser = $data->createUser;
								$countryid    = $data->country;
								$outletname = $data->outletname;
								$taxnumber= $data->taxnumber;
								$address1 = $data->address1;
								$address2 = $data->address2;
								$stateid = $data->state;
								$zipcode = $data->zipcode;
								$mobileno= $data->mobileno;
								$workno = $data->workno;
								$email 	= $data->email;
								$cname 	= $data->cname;
								$cmobile= $data->cmobile;
								$action  = $data->action;	
								$comment  = $data->comment;	
								$langpref  = $data->langpref;
								$localgovernmentid = $data->localgovernment;
								$firstName = $data->firstName;
								$lastName = $data->lastName;
								$version =  2;
								if ( 
									 isset($data -> signature ) && !empty($data -> signature) && 
									 isset($countryid) && !empty($countryid) && 
									 isset($data -> key) && !empty($data -> key) && 
									 isset($createuser) && !empty($createuser) && 
									 isset($outletname) && !empty($outletname) && 
									 isset($taxnumber) && !empty($taxnumber) &&
									 isset($address1) && !empty($address1) &&
									 isset($address2) && !empty($address2) &&
									 isset($stateid) && !empty($stateid) &&
									 isset($zipcode) && !empty($zipcode) &&
									 isset($mobileno) && !empty($mobileno) &&
									 isset($workno) && !empty($workno) &&
									 isset($email) && !empty($email) &&
									 isset($cname) && !empty($cname) &&
									 isset($cmobile) && !empty($cmobile) &&
									 isset($action) && !empty($action) &&
									 isset($comment) && !empty($comment) &&
									 isset($langpref) && !empty($langpref) &&
									 isset($localgovernmentid) && !empty($localgovernmentid) &&
									 isset($firstName) && !empty($firstName) &&
									 isset($lastName) && !empty($lastName) &&
									 isset($version) && !empty($version) 
									) {

									error_log("inside all inputs are set correctly");
									// connecting to db
									$db = new DB_CONNECT();
									error_log("db_connect done");
									// array for JSON response
									$response = array();

									$signature = $data -> signature;
									$key = $data -> key;
									error_log("signature = ".$signature.", key = ".$key);
									date_default_timezone_set('Africa/Lagos');
									$nday = date('z')+1;
									$nyear = date('Y');
									error_log( "nday = ".$nday);
									error_log( "nyear = ".$nyear);
									$nth_day_prime = get_prime($nday);
									$nth_year_day_prime = get_prime($nday+$nyear);
									error_log("nth_day_prime = ".$nth_day_prime);
									error_log("nth_year_day_prime = ".$nth_year_day_prime);
									$local_signature = $nday + $nth_day_prime;
									error_log("local_signature = ".$local_signature);
									if ( $local_signature != $signature ){
										// failure
										$response["result"] = "failure";
										$response["action"] = "ERROR";
										$response["message"] = "Invalid Client Request";
										error_log(json_encode($response));

										// echoing JSON response
										echo json_encode($response);
										return;

									}
									else {
										if($action == "create") {		
											$get_sequence_number_query = "SELECT get_sequence_num(2100) as application_id";
											$get_sequence_number_result =  mysqli_query($con,$get_sequence_number_query);
											if(!$get_sequence_number_result) {
												error_log("Invalid Method");
												error_log("Sequence No".mysqli_error($con));
												// Failure - Invalid Method
												$response["result"] = "failure";
												$response["action"] = "ERROR";
												$response["message"] = "Error in Getting Sequence No";
												// echoing JSON response
												echo json_encode($response);
												return;				
											}
											else {
												$get_sequence_num_row = mysqli_fetch_assoc($get_sequence_number_result);
												$application_id = $get_sequence_num_row['application_id'];
												$pre_application_query = "INSERT INTO pre_application_info (pre_application_info_id, country_id, outlet_name, tax_number, address1, address2, local_govt_id, state_id, mobile_no, work_no, email, language_id, contact_person_name, contact_person_mobile, comments, status, create_user, create_time) 
																								VALUES ($application_id, $countryid, '$outletname', '$taxnumber', '$address1', '$address2', $localgovernmentid, $stateid, '$mobileno', '$workno', '$email', $langpref, '$cname', '$cmobile', '$comment','E', $createuser, now())";
												error_log($pre_application_query);
												$pre_application_result =  mysqli_query($con,$pre_application_query);
												if(!$pre_application_result) {
													$response["result"] = "failure";
													$response["action"] = "ERROR";
													$response["message"] = "Error in operation 1";
													error_log('Pre Application main entry failed: ' . mysqli_error($con));
												}
												else {
													$email_array = array();
													array_push($email_array, $email);
													$current_time = date('Y-m-d H:i:s');
													$subject = 'Kadick Monei: Pre Application ID: '.$application_id.' For - '.$firstName." ".$lastName;
													$body   = '<p>Dear '.$firstName." ".$lastName.',</p>
																<div>Your Pre Applcation Submitted Successfully..Out Kadick Authorized Person Will Contact You Shortly.Stay tuned</div><br />
																Note: This is an auto generated email. For more information contact Kadick Admin.<br />
																Generated @'.$current_time.' WAT<br /><br />';
														
													mailSend($email_array, $body, $subject,'');
													$response["result"] = "success";
                                                	$response["message"] = "Your Application No: $application_id submitted successfully";
                                                	$response["action"] = "NORMAL";
                                                	error_log(json_encode($response));
													// echoing JSON response
													echo json_encode($response);
													return;
												}
											}
										}
									}
								}
								else {
									// Failure - Invalid Method
									$response["result"] = "failure";
									$response["action"] = "ERROR";
									$response["message"] = "InValid Data";
									// echoing JSON response
									echo json_encode($response);
									return;				
								}
							}
							else {
								$response["result"] = "failure";
								$response["action"] = "ERROR";
								$response["message"] = "Operation3 Faiure";
								// echoing JSON response
								echo json_encode($response);
								return;			
							}
						}					
					else {
				
						$response["result"] = "failure";
						$response["action"] = "ERROR";
						$response["message"] = "Operation2 Failue";
						// echoing JSON response
						echo json_encode($response);
						return;			
				
					}
				}			
				else {
					$response["result"] = "failure";
					$response["action"] = "ERROR";
					$response["message"] = "Operation1 Failue";
					// echoing JSON response
					echo json_encode($response);
					return;			
				}
		}
		else {
			$response["result"] = "failure";
			$response["action"] = "ERROR";
			$response["message"] = "Post Failure";
			// echoing JSON response
			echo json_encode($response);
			return;			
		}
									
?>
