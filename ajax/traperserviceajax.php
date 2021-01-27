 <?php
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	$action		=  $data->action;
	$state	=  $data->state;
	$localgovernment	=  $data->localgovernment;	
	$active		=  $data->active;
	$startDate		=  $data->startDate;
	$endDate	=  $data->endDate;
	$ba	=  $data->ba;
	$profileId = $_SESSION['profile_id'];
	$userId = $_SESSION['user_id'];
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
	if($profileId ==50 || $profileId ==51 || $profileId == 52){
			if($action == "query") {
				if($ba == "aw") {
						$query ="select date(b.date_time) as date, concat(c.feature_code, ' - ', c.feature_description) as transaction_type, sum(a.request_amount) as total_request_amount, sum(a.total_amount) as total_amount from fin_request a, fin_service_order b, service_feature c where a.order_no = b.fin_service_order_no and b.service_feature_code = c.feature_code and b.user_id = '$userId' and date(b.date_time) between '$startDate' and '$endDate'  group by date(b.date_time), transaction_type order by date(b.date_time), transaction_type";
				
				}
				if($ba == "cw") {
						$query ="select date(b.date_time) as date, concat(c.feature_code, ' - ', c.feature_description) as transaction_type,c.feature_code, sum(a.request_amount) as total_request_amount, sum(a.total_amount) as total_amount, concat(d.first_name, ' ', d.last_name,' (', d.user_name,') ') as userName, ifNULL(b.customer_name,'-') as customer_name,ifNULL(a.sender_name,'-') as sender_name from fin_request a, fin_service_order b, service_feature c, user d where  d.user_id = a.user_id and a.order_no = b.fin_service_order_no and b.service_feature_code = c.feature_code and b.user_id = '$userId' and date(b.date_time) between '$startDate' and '$endDate'  group by date(b.date_time), transaction_type order by date(b.date_time), transaction_type";
				}
				error_log($query);
				$result =  mysqli_query($con,$query);
				if (!$result) {
					printf("Error: %s\n".mysqli_error($con));
					//exit();
				}
				$data = array();
				while ($row = mysqli_fetch_array($result)) {
					$data[] = array("ba"=>$ba, "date"=>$row['date'],"ttype"=>$row['transaction_type'],
								"ramount"=>$row['total_request_amount'],"tamount"=>$row['total_amount'],"userName"=>$row['userName'],
								"customerName"=>$row['customer_name'],"sender_name"=>$row['sender_name'],"feature_code"=>$row['feature_code']);           
				}
				echo json_encode($data);
			}else if($action == "print") {
					$print ="select date(b.date_time) as date, concat(c.feature_code, ' - ', c.feature_description) as transaction_type, sum(a.request_amount) as total_request_amount, sum(a.total_amount) as total_amount, concat(d.first_name, ' ', d.last_name,' (', d.user_name,') ') as userName, ifNULL(b.customer_name,'-') as customer_name,ifNULL(a.sender_name,'-') as sender_name,c.feature_code from fin_request a, fin_service_order b, service_feature c, user d where  d.user_id = a.user_id and a.order_no = b.fin_service_order_no and b.service_feature_code = c.feature_code and b.user_id = '$userId' and date(b.date_time) between '$startDate' and '$endDate'  group by date(b.date_time), transaction_type order by date(b.date_time), transaction_type";
			
			error_log("print : ".$print);
			$result =  mysqli_query($con,$print);
			if (!$result) {
				printf("Error: %s\n".mysqli_error($con));
				//exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("ba"=>$ba, "date"=>$row['date'],"ttype"=>$row['transaction_type'],
							"ramount"=>$row['total_request_amount'],"tamount"=>$row['total_amount'],"userName"=>$row['userName'],
							"customerName"=>$row['customer_name'],"sender_name"=>$row['sender_name'],"feature_code"=>$row['feature_code']);           
			}
			echo json_encode($data);
		}
	}else{
		if($action == "query") {
			if($ba == "aw") {
			if($state == "ALL") {
				if($localgovernment=='ALL'){
					$query ="select date(b.date_time) as date, concat(c.feature_code, ' - ', c.feature_description) as transaction_type, sum(a.request_amount) as total_request_amount, sum(a.total_amount) as total_amount from fin_request a, fin_service_order b, service_feature c where a.order_no = b.fin_service_order_no and b.service_feature_code = c.feature_code and date(b.date_time) between '$startDate' and '$endDate' group by date(b.date_time), transaction_type order by date(b.date_time), transaction_type";
				}
				else {
					$query ="select date(b.date_time) as date, concat(c.feature_code, ' - ', c.feature_description) as transaction_type, sum(a.request_amount) as total_request_amount, sum(a.total_amount) as total_amount from fin_request a, fin_service_order b, service_feature c where a.order_no = b.fin_service_order_no and b.service_feature_code = c.feature_code and a.local_govt_id = $localgovernment and date(b.date_time) between '$startDate' and '$endDate'  group by date(b.date_time), transaction_type order by date(b.date_time), transaction_type";
				}
			}
			else {
				if($localgovernment=='ALL'){
					$query ="select date(b.date_time) as date, concat(c.feature_code, ' - ', c.feature_description) as transaction_type, sum(a.request_amount) as total_request_amount, sum(a.total_amount) as total_amount from fin_request a, fin_service_order b, service_feature c where a.order_no = b.fin_service_order_no and b.service_feature_code = c.feature_code and a.state_id = $state and date(b.date_time) between '$startDate' and '$endDate'  group by date(b.date_time), transaction_type order by date(b.date_time), transaction_type";
				}
				else {
					$query ="select date(b.date_time) as date, concat(c.feature_code, ' - ', c.feature_description) as transaction_type, sum(a.request_amount) as total_request_amount, sum(a.total_amount) as total_amount from fin_request a, fin_service_order b, service_feature c where a.order_no = b.fin_service_order_no and b.service_feature_code = c.feature_code and a.state_id = $state and a.local_govt_id = $localgovernment and date(b.date_time) between '$startDate' and '$endDate'  group by date(b.date_time), transaction_type order by date(b.date_time), transaction_type";
				}		
			}
		}
		if($ba == "cw") {
			if($state == "ALL") {
				if($localgovernment=='ALL'){
					$query ="select date(b.date_time) as date, concat(c.feature_code, ' - ', c.feature_description) as transaction_type, sum(a.request_amount) as total_request_amount, sum(a.total_amount) as total_amount, concat(d.first_name, ' ', d.last_name,' (', d.user_name,') ') as userName, b.customer_name,a.sender_name,c.feature_code from fin_request a, fin_service_order b, service_feature c, user d where  d.user_id = a.user_id and a.order_no = b.fin_service_order_no and b.service_feature_code = c.feature_code and date(b.date_time) between '$startDate' and '$endDate' group by date(b.date_time), transaction_type order by date(b.date_time), transaction_type";
				}
				else {
					$query ="select date(b.date_time) as date, concat(c.feature_code, ' - ', c.feature_description) as transaction_type, sum(a.request_amount) as total_request_amount, sum(a.total_amount) as total_amount, concat(d.first_name, ' ', d.last_name,' (', d.user_name,') ') as userName, b.customer_name,a.sender_name,c.feature_code from fin_request a, fin_service_order b, service_feature c, user d where  d.user_id = a.user_id and a.order_no = b.fin_service_order_no and b.service_feature_code = c.feature_code and a.local_govt_id = $localgovernment and date(b.date_time) between '$startDate' and '$endDate'  group by date(b.date_time), transaction_type order by date(b.date_time), transaction_type";
				}
			}
			else {
				if($localgovernment=='ALL'){
					$query ="select date(b.date_time) as date, concat(c.feature_code, ' - ', c.feature_description) as transaction_type, sum(a.request_amount) as total_request_amount, sum(a.total_amount) as total_amount, concat(d.first_name, ' ', d.last_name,' (', d.user_name,') ') as userName, b.customer_name,a.sender_name,c.feature_code from fin_request a, fin_service_order b, service_feature c, user d where  d.user_id = a.user_id and a.order_no = b.fin_service_order_no and b.service_feature_code = c.feature_code and a.state_id = $state and date(b.date_time) between '$startDate' and '$endDate'  group by date(b.date_time), transaction_type order by date(b.date_time), transaction_type";
				}
				else {
					$query ="select date(b.date_time) as date, concat(c.feature_code, ' - ', c.feature_description) as transaction_type, sum(a.request_amount) as total_request_amount, sum(a.total_amount) as total_amount, concat(d.first_name, ' ', d.last_name,' (', d.user_name,') ') as userName, b.customer_name,a.sender_name,c.feature_code from fin_request a, fin_service_order b, service_feature c, user d where  d.user_id = a.user_id and a.order_no = b.fin_service_order_no and b.service_feature_code = c.feature_code and a.state_id = $state and a.local_govt_id = $localgovernment and date(b.date_time) between '$startDate' and '$endDate'  group by date(b.date_time), transaction_type order by date(b.date_time), transaction_type";
				}		
			}
		}
		error_log($query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("ba"=>$ba, "date"=>$row['date'],"ttype"=>$row['transaction_type'],
						"ramount"=>$row['total_request_amount'],"tamount"=>$row['total_amount'],"userName"=>$row['userName'],
						"customerName"=>$row['customer_name'],"sender_name"=>$row['sender_name'],"feature_code"=>$row['feature_code']);           
		}
		echo json_encode($data);
	}

	else if($action == "view") {
		$agent_code	=  $data->agent_code;
				$query ="select a.agent_code, a.agent_name, a.login_name, ifnull(a.parent_code, 'None') as parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, i_format(d.available_balance) as available_balance, i_format(d.current_balance) as current_balance, i_format(d.advance_amount) as advance_amount, i_format(d.minimum_balance) as minimum_balance, i_format(d.daily_limit) as daily_limit, i_format(d.credit_limit) as credit_limit, b.name as state, c.name as local_govt, a.active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c, agent_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.agent_code = '$agent_code' order by a.agent_code";				
		
			error_log($query);
		$result =  mysqli_query($con,$query);
		if(!$result) {
			die('app_view_view_result: ' . mysqli_error($con));
			echo "app_view_view_result - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("waltype"=>$ba, "agent_code"=>$row['agent_code'],"agent_name"=>$row['agent_name'],"login_name"=>$row['login_name'],
						"parent_code"=>$row['parent_code'],"parent_type"=>$row['parent_type'], "local_govt"=>$row['local_govt'],
						"available_balance"=>$row['available_balance'],"current_balance"=>$row['current_balance'],
						"advance_amount"=>$row['advance_amount'],"minimum_balance"=>$row['minimum_balance'],"daily_limit"=>$row['daily_limit'],
						"credit_limit"=>$row['credit_limit'],"state"=>$row['state'],"active"=>$row['active'],
						"block_status"=>$row['block_status']);
				}
			echo json_encode($data);
		}
			
	}
}
?>