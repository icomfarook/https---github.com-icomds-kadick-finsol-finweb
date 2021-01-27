 <?php
	$data = json_decode(file_get_contents("php://input"));

	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	require '../api/get_prime.php';
	require '../api/security.php';
	require '../common/gh/autoload.php';
	$action		=  $data->action;
	$oprId		=  $data->oprId;
	$creteria	=  $data->creteria;
	$startDate	=  $data->startDate;
	$endDate	=  $data->endDate;
	$status 	=  $data->status;
	$cuser =$_SESSION['user_id'];
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
	if($action == "query") {		
		if($creteria == "O") {
			if($status == "ALL") {
				$query = "SELECT status as sta, notification_id, operation_id, received_time, processed_time, if(status = 'R',' R-Received',if(status = 'P', 'P-Posted', if(status = 'N',' N-NotPosted',if(status = 'M','M - Missing','E - Error')))) as status FROM mcash_notification WHERE operation_id = $oprId ORDER BY notification_id";
			}
			else {
				$query = "SELECT status as sta, notification_id, operation_id, received_time, processed_time, if(status = 'R',' R-Received',if(status = 'P', 'P-Posted', if(status = 'N',' N-NotPosted',if(status = 'M','M - Missing','E - Error')))) as status FROM mcash_notification WHERE operation_id = $oprId and status = '$status' ORDER BY notification_id";
			}
		}
		if($creteria == "D") {
			if($status == "ALL") {
				$query = "SELECT status as sta, notification_id, operation_id, received_time, processed_time, if(status = 'R',' R-Received',if(status = 'P', 'P-Posted', if(status = 'N',' N-NotPosted',if(status = 'M','M - Missing','E - Error')))) as status FROM mcash_notification WHERE date(received_time) between '$startDate' and '$endDate' ORDER BY notification_id";
			}
			else {
				$query = "SELECT status as sta, notification_id, operation_id, received_time, processed_time, if(status = 'R',' R-Received',if(status = 'P', 'P-Posted', if(status = 'N',' N-NotPosted',if(status = 'M','M - Missing','E - Error')))) as status FROM mcash_notification WHERE date(received_time) between '$startDate' and '$endDate' and status = '$status' ORDER BY notification_id";
			}
		}			
		error_log("qyetr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			echo("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("sta"=>$row['sta'],"nid"=>$row['notification_id'],"oid"=>$row['operation_id'],"rtime"=>$row['received_time'],"ptime"=>$row['processed_time'],"status"=>$row['status']);           
		}
		echo json_encode($data);
	}
	
	if($action == "process") {	
		$oprId		=  $data->oprId;
		$nid		=  $data->nid;
		$query = "SELECT b.transaction_id, b.create_user, a.notification_text,a.status FROM fin_trans_log_batch b, mcash_notification a WHERE a.operation_id = b.operation_id and a.operation_id = $oprId";
		error_log("uer".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			echo("Error: %s\n".mysqli_error($con));
			//exit();
		}
		else {
			$count = mysqli_num_rows($result);
			error_log($count);
			if($count > 0) {				
				$data = array();
				$row = mysqli_fetch_assoc($result);
				$transction_id = $row['transaction_id'];
				$status = $row['status'];
				$create_user   = $row['create_user'];
				$error_description   = $row['error_description'];
				$notification_text   = $row['notification_text'];
				$data['transaction_id'] = $transction_id;
				$data['operation_id'] = $oprId;				
				$data['notification_text'] = $notification_text;
				error_log("notification_text".$notification_text);
				$text = json_decode($notification_text,true);
				$result = $text['status']['result'];
				$data['result'] = $result;
				$data['cause'] = $text['status']['cause'];
				error_log("result".$result);
				if($result == 'success') {
					$query = "UPDATE mcash_notification SET status = 'P' WHERE notification_id = $nid";
					error_log($query);
					$inqresult =  mysqli_query($con,$query);
					if (!$inqresult) {
						echo("Error: %s\n".mysqli_error($con));
						//exit();
					}
					$insertquery = "INSERT INTO mcash_notification_transfer (transfer_id, notification_id, from_status, to_status, create_user, create_time) VALUES (0, $nid,'$status','S',$cuser,now() )";
				}
				if($result == 'failure') {
					$insertquery = "INSERT INTO mcash_notification_transfer (transfer_id, notification_id, from_status, to_status, create_user, create_time) VALUES (0, $nid,'$status','E',$cuser,now() )";
				}
				
				error_log($insertquery);
				$insresult =  mysqli_query($con,$insertquery);
				if (!$insresult) {
					echo("Error: %s\n".mysqli_error($con));
					//exit();
				}
				if($create_user == "82") {					
					$url = MCASH_NOTIFICATION_URL1;					
				}
				else if($create_user == "83") {					
					$url = MCASH_NOTIFICATION_URL2;					
				}
				else if($create_user == "85") {					
					$url = MCASH_NOTIFICATION_URL3;					
				}
				$request = sendRequest($data,$url);
				
			}
			else {
				echo "No Rows Found";
			}
			
			
		}
	}
function sendRequest($data,$url){	
		error_log("entering batch ajax");
		date_default_timezone_set('Africa/Lagos');
		$nday = date('z')+1;
		$nyear = date('Y');
		$nth_day_prime = get_prime($nday);
		$nth_year_day_prime = get_prime($nday+$nyear);
		$Signature = $nday + $nth_day_prime;
		$tsec = time();
		$raw_data1 = mcash_notification_password.FINWEB_SERVER_SHORT_NAME."|".mcash_notification_username.FINWEB_SERVER_SHORT_NAME."|".$tsec;
		$key1 = base64_encode($raw_data1);
		error_log("before calling post");
		error_log("url = ".$url);		
		$body['transactionId'] = $data['transaction_id'];
		$body['operationId'] = $data['operation_id'];
		$body['result'] = $data['result'];
		$body['cause'] = $data['cause'];
		$body['key1'] = $key1;
		$body['signature'] = $Signature;
		error_log("request sent ==> ".json_encode($body));
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, FINAPI_SERVER_CONNECT_TIMEOUT);
		curl_setopt($ch, CURLOPT_TIMEOUT, FINAPI_SERVER_REQUEST_TIMEOUT);
		
		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		error_log("response received <== ".$response);
		error_log("code ".$httpcode);
		error_log("exiting sendTierA1AccountRequest");
      	return $httpcode;
	}
?>