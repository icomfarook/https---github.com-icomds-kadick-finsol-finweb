 <?php

	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	require '../api/get_prime.php';
	require '../api/security.php';
	require '../common/gh/autoload.php';
	//error_reporting(E_ALL);
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;	
	$startDate = $data->startDate;
	$orderno = $data->orderno;
	$endDate = $data->endDate;
	$subquery = "";
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
	if($action == "query") {	
		if($orderno != null && !empty($orderno) &&  trim($orderno) != "" && $orderno != 'undefined') {
			$subquery = " and a.order_no = $orderno";
		}
		$query = "SELECT a.order_no, b.agent_code, a.request_amount, a.total_amount, a.sender_name, a.mobile_no, a.create_time  FROM fin_request a, agent_info b WHERE a.user_id = b.user_id and a.service_feature_code = 'MP0' and a.status = 'G' and date(a.create_time) between '$startDate' and '$endDate' $subquery";
		error_log("cashout - treatment ".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			//exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("orderno"=>$row['order_no'],"agentcode"=>$row['agent_code'],"reqamt"=>$row['request_amount'],"totamt"=>$row['total_amount'],"sendname"=>$row['sender_name'],"mblno"=>$row['mobile_no'],"cretime"=>$row['create_time']);           
		}
		echo json_encode($data);
		
	}	
	if($action == "view") {
	//	error_reporting(E_ALL);
		$query = "SELECT  IF(a.status = 'I', 'I-Inprogress', IF(a.status = 'N', 'N-Name Enqiury', IF(a.status = 'S','S-Success', IF(a.status = 'E','E-Error', IF(a.status = 'T','T-Timeout', IF(a.status = 'G','G-Triggered', IF(a.status = 'R',' R-Request Cancel', IF(a.status = 'X','X-Cancelled','O-Request Confirm')))))))) as status, IFNULL(a.comments, ' - ') as comments, IFNULL(a.approver_comments, ' - ') as approver_comments, a.create_time, IFNULL(a.update_time, ' - ') as update_time, a.sender_name, a.mobile_no, IFNULL(a.auth_code, ' - ') as auth_code, a.rrn, IFNULL((SELECT name FROM bank_master WHERE bank_master_id = a.bank_id), '-') as bank, IFNULL(a.account_no, ' - ') as account_no, IFNULL((SELECT name FROM local_govt_list WHERE local_govt_id = a.local_govt_id), '-') as locgov, c.name as state, a.fin_trans_log_id1, a.fin_trans_log_id2, a.order_no, b.agent_code, a.request_amount, a.total_amount, a.sender_name, a.mobile_no, a.create_time, a.service_charge, a.partner_charge, a.other_charge  FROM fin_request a, agent_info b, state_list c WHERE c.state_id = a.state_id and a.user_id = b.user_id and a.service_feature_code = 'MP0' and a.status = 'G' and date(a.create_time) and a.order_no = $orderno";
		error_log("cashout - treatment - Detail ".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			//exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("status"=>$row['status'],"utime"=>$row['update_time'],"ctime"=>$row['create_time'],"acomments"=>$row['approver_comments'],"comments"=>$row['comments'],"sename"=>$row['sender_name'],"mblno"=>$row['mobile_no'],"aucode"=>$row['auth_code'],"rrn"=>$row['rrn'],"baid"=>$row['bank'],"accno"=>$row['account_no'],"locgov"=>$row['locgov'],"state"=>$row['state'],"fntlog1"=>$row['fin_trans_log_id1'],"fntlog2"=>$row['fin_trans_log_id2'],"sercharge"=>$row['service_charge'],"parcharge"=>$row['partner_charge'],"othcharge"=>$row['other_charge'],"orderno"=>$row['order_no'],"agentcode"=>$row['agent_code'],"reqamt"=>$row['request_amount'],"totamt"=>$row['total_amount'],"sendname"=>$row['sender_name'],"mblno"=>$row['mobile_no'],"cretime"=>$row['create_time']);           
		}
		echo json_encode($data);
	}
	if($action == "process") {
		require 'functions.php';
		$pan = $data->pan;
		$rrn = $data->rrn;
		$authcode = $data->authcode;
		$stan = $data->stan;
		$rescode = $data->rescode;
		$orderno = $data->orderno;
		$query = "SELECT  a.user_id, a.total_amount, b.agent_code as partycode, c.terminal_id, b.country_id, b.state_id, c.flexi_rate FROM fin_request a, agent_info b, user_pos c WHERE a.user_id = b.user_id and a.user_id = c.user_id and b.user_id = c.user_id and a.order_no = $orderno";
		error_log("cashout - treatment - Process ".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			//exit();         
		}
		else {
			$data = array();
			$orderType = "Cash-Out (Card)";
			$row = mysqli_fetch_assoc($result);
			$partyCode = $row['partycode'];			
			$partyType = 'A';	
			$terminalId = $row['terminal_id'];
			$uesrId = $row['user_id'];
			$country = $row['country_id'];
			$state = $row['state_id'];
			$totalAmount = $row['total_amount'];
			$flexiRate = $row['flexi_rate'];
			$data['partyCode'] = $partyCode;
			$data['orderNo'] = $orderno;
			$data['responseCode'] = $rescode;
			$data['authCode'] = $authcode;
			$data['orderType'] = $orderType;
			$data['totalAmount'] = $totalAmount;
			$data['totalAmount'] = $totalAmount;
			$data['partyType'] = $partyType;
			//$data['key1'] = $key1;
			//$data['signature'] = getLocalSignature();
			$data['userId'] = $uesrId;
			$data['stateId'] = $state;
			$data['countryId'] = $country;
			$res = sendRequest($data);
			echo $res;
		}
	}		
	function sendRequest($data) {	
		error_log("entering sendRequest");
		date_default_timezone_set('Africa/Lagos');
		$nday = date('z')+1;
		$nyear = date('Y');
		$nth_day_prime = get_prime($nday);
		$nth_year_day_prime = get_prime($nday+$nyear);
		$signature = $nday + $nth_day_prime;
		$tsec = time();
		$raw_data1 = FINAPI_SERVER_APP_PASSWORD.FINWEB_SERVER_SHORT_NAME."|".FINAPI_SERVER_APP_USERNAME.FINWEB_SERVER_SHORT_NAME."|".$tsec;
		$key1 = base64_encode($raw_data1);
		error_log("before calling post");
		error_log("url = ".FINWEB_SERVER_CASHOUT_CARD_TREATMENT_URL);		
		$body['partyCode'] = $data['partyCode'];
		$body['orderNo'] = $data['orderNo'];
		$body['responseCode'] = $data['responseCode'];
		$body['authCode'] = $data['authCode'];
		$body['orderType'] = $data['orderType'];
		$body['totalAmount'] = $data['totalAmount'];
		$body['partyType'] = $data['partyType'];
		$body['userId'] = $data['userId'];
		$body['stateId'] = $data['stateId'];
		$body['countryId'] = $data['countryId'];
		$body['key1'] = $key1;
		$body['signature'] = $signature;
		error_log("request sent ==> ".json_encode($body));
		$ch = curl_init(FINWEB_SERVER_CASHOUT_CARD_TREATMENT_URL);
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
		error_log("exiting sendRequest");
      	return $response;
	}
?>	