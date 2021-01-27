<?php
	include('../common/admin/configmysql.php');
	require '../api/get_prime.php';
	include ("functions.php");
	require_once ("AesCipher.php");
	error_log("inside pcposapi/mcash_notification_cancel.php");

	$response = array();
	$current_time = date('Y-m-d H:i:s');
	$response['processingStartTime'] = $current_time;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		error_log("inside post request method");
        $data = json_decode(file_get_contents("php://input"));
        error_log("mcash_notification_cancel <== ".json_encode($data));				

		if ( isset($data -> result ) && !empty($data -> result) &&  isset($data -> operationId ) && !empty($data -> operationId) 
			&& isset($data -> finRequestId ) && !empty($data -> finRequestId) && isset($data -> cause ) && !empty($data -> cause) 
			&& isset($data -> signature ) && !empty($data -> signature) && isset($data -> key1 ) && !empty($data -> key1 )) {
				
			error_log("inside all inputs are set correctly");	
			$result = $data -> result;
			$signature = $data -> signature;
			$key1 = $data -> key1;
			$operationId = $data -> operationId;
			$fin_request_id = $data -> finRequestId;
			$comment = $data -> cause;
			
			error_log("signature = ".$signature.", key1 = ".$key1);
			date_default_timezone_set('Africa/Lagos');
			$nday = date('z')+1;
			$nyear = date('Y');
			//error_log( "nday = ".$nday);
			//error_log( "nyear = ".$nyear);
			$nth_day_prime = get_prime($nday);
			$nth_year_day_prime = get_prime($nday+$nyear);
			//error_log("nth_day_prime = ".$nth_day_prime);
			//error_log("nth_year_day_prime = ".$nth_year_day_prime);
			$local_signature = $nday + $nth_day_prime;
			error_log("local_signature = ".$local_signature);
			$server_signature = $nth_year_day_prime + $nday + $nyear;
			error_log("server_signature = ".$server_signature);
						
			if ( $local_signature == $signature ){	
			
				$query = "SELECT a.fin_service_order_no FROM fin_service_order a, fin_request b WHERE a.fin_service_order_no = b.order_no and a.auth_code = '".$operationId."' and b.fin_request_id = $fin_request_id";
				error_log("select query: ".$query);
				$result = mysqli_query($con, $query);
				if (!$result) {
					$response["message"] = "Error: cheking order: ".mysqli_error($con);
					$response["result"] = "failure";
					error_log("Error: cheking order: ".mysqli_error($con));
				}
				else {
					$count = mysqli_num_rows($result);
					error_log("Selet query count = ".$count." for fin_request_id = ".$fin_request_id);
					if($count > 0) {
						$row = mysqli_fetch_assoc($result);
						$fin_service_order_no = $row['fin_service_order_no'];
						$delete_query = "delete from fin_service_order where fin_service_order_no = ".$fin_service_order_no;
						error_log("Delete_query for Live Cashout: ".$delete_query);
						$delete_result = mysqli_query($con, $delete_query);
						if ( $delete_result ) {
							error_log("successful delete from fin_service_order table for fin_service_order_no = ".$fin_service_order_no);
						}else {
							error_log("error in delete from fin_service_order table for fin_service_order_no = ".$fin_service_order_no." - ".mysqli_error($con));
						}

						$updatequery = "UPDATE fin_request SET status = 'X', approver_comments = '$comment', update_time = now() WHERE fin_request_id = ".$fin_request_id;
						error_log("updatequery = ".$updatequery);
						$update_result = mysqli_query($con, $updatequery);
						if ( $update_result ) {
							$response["message"] = "Cash-Out order is rejected for Order # ".$fin_service_order_no;
							$response["result"] = "success";
							error_log("Live Cash-Out order is rejected for Order # ".$fin_service_order_no);
						}
						else {
							$response["message"] = "Error: Cash-Out order rejected for Order # ".$fin_service_order_no;
							$response["result"] = "failure";
							error_log("Error: Cash-Out rejected for order # ".$fin_service_order_no);
						}								
					}
					else {
						$response["message"] = "No Order found";
						$response["result"] = "failure";
						error_log("Error: No order found for fin_request_id = ".$fin_request_id);
					}
				}
			}else {
				$response["message"] = "Invalid signature";
				$response["result"] = "failure";
				error_log("Error: Invalid signature");
			}
		}else {
			$response["message"] = "Invalid input data";
			$response["result"] = "failure";
			error_log("Error: Invalid input data");
		}
	}else {
		$response["message"] = "Invalid request method";
		$response["result"] = "failure";
		error_log("Error: Invalid request method");
	}
	error_log("mcash_notification_cancel ==> ".json_encode($response));
	echo json_encode($response);

?>