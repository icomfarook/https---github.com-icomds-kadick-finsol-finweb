 <?php
 
    error_log("insideajax");
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	$action		=  $data->action;
	$state	=  $data->state;
	$localgovernment	=  $data->localgovernment;	
	$startDate =  $data->startDate;
	$endDate = $data->endDate;
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate)); 	
	
	if($action == "report") {
				if($state == "ALL") {
		$query="select a.agent_code, a.parent_code, d.date_time, concat(c.feature_code, ' - ', c.feature_description) as transaction_type, d.fin_service_order_no as order_no, b.fin_request_id as transaction_id, i_format(b.request_amount) as request_amount, i_format(b.service_charge) as service_charge, i_format(b.total_amount) as total_amount, e.name as state, f.name as local_govt, d.service_feature_config_id as rule_id from agent_info a, fin_request b, service_feature c, fin_service_order d, state_list e, local_govt_list f where b.order_no = d.fin_service_order_no and b.user_id = a.user_id and b.service_feature_code = c.feature_code and b.state_id = e.state_id  and b.local_govt_id = f.local_govt_id and b.status = 'S' and e.state_id = f.state_id  and a.local_govt_id = f.local_govt_id order by d.date_time, a.agent_code, c.feature_code and  date(d.date_time) >= date('$startDate') and date(d.date_time) <= date('$endDate')";
		error_log("qyetr".$query);
		
		
		}else {
			if ($localgovernment != "ALL"){
				$query="select a.agent_code, a.parent_code, d.date_time, concat(c.feature_code, ' - ', c.feature_description) as transaction_type, d.fin_service_order_no as order_no, b.fin_request_id as transaction_id, i_format(b.request_amount) as request_amount, i_format(b.service_charge) as service_charge, i_format(b.total_amount) as total_amount, e.name as state, f.name as local_govt, d.service_feature_config_id as rule_id from agent_info a, fin_request b, service_feature c, fin_service_order d, state_list e, local_govt_list f where b.order_no = d.fin_service_order_no and b.user_id = a.user_id and b.service_feature_code = c.feature_code and b.state_id = e.state_id  and b.local_govt_id = f.local_govt_id and b.status = 'S' and e.state_id = f.state_id  and a.local_govt_id = f.local_govt_id order by d.date_time, a.agent_code, c.feature_code and e.state = $state and f.local_govt = $localgovernment and date(d.date_time) >= date('$startDate') and date(d.date_time) <= date('$endDate')";
			}
		}
		
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}else{
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("agent_code"=>$row['agent_code'],"parent_code"=>$row['parent_code'],
						"transaction_type"=>$row['transaction_type'],"order_no"=>$row['order_no'],"transaction_id"=>$row['transaction_id'],	"request_amount"=>$row['request_amount'],"service_charge"=>$row['service_charge'],"total_amount"=>$row['total_amount'],	"state"=>$row['state'],	"local_govt"=>$row['local_govt'],	"rule_id"=>$row['rule_id']);           
			}
			echo json_encode($data);
		}
		
	}
	else if($action == "view") {
	$query="select a.agent_code, a.parent_code, d.date_time, concat(c.feature_code, ' - ', c.feature_description) as transaction_type, d.fin_service_order_no as order_no, b.fin_request_id as transaction_id, i_format(b.request_amount) as request_amount, i_format(b.service_charge) as service_charge, i_format(b.total_amount) as total_amount, e.name as state, f.name as local_govt, d.service_feature_config_id as rule_id from agent_info a, fin_request b, service_feature c, fin_service_order d, state_list e, local_govt_list f where b.order_no = d.fin_service_order_no and b.user_id = a.user_id and b.service_feature_code = c.feature_code and b.state_id = e.state_id and b.status = 'S' and e.state_id = f.state_id and a.local_govt_id = f.local_govt_id order by d.date_time, a.agent_code, c.feature_code";
	error_log($query);
		$result =  mysqli_query($con,$query);
		if(!$result) {
			die('app_view_view_result: ' . mysqli_error($con));
			echo "app_view_view_result - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
			$data[] = array("agentCode"=>$row['agent_code'],"ParentCode"=>$row['parent_code'],
				"TransType"=>$row['transaction_type'],"Orderno"=>$row['order_no'],"TransId"=>$row['transaction_id'],	"Reqamount"=>$row['request_amount'],"ServiceCharge"=>$row['service_charge'],"totalamount"=>$row['total_amount'],"state"=>$row['state'],	"locgovernment"=>$row['local_govt'],"RuleId"=>$row['rule_id']);           
		}
			echo json_encode($data);
		}
			
	}
	
?>