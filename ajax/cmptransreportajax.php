 <?php
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	$action		=  $data->action;
	$type	=  $data->type;
	$orderNo	=  $data->orderNo;
	$startDate		=  $data->startDate;
	$endDate	=  $data->endDate;
	$creteria 	= $data->creteria;
	$championName	=  $_SESSION['party_code'];	
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));	
	if($action == "getreport") {
		$query = "SELECT a.service_feature_code, a.fin_service_order_no, a.request_amount, a.total_amount, a.date_time, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as user FROM fin_service_order a, agent_info b WHERE b.parent_code = '$championName' and a.user_id = b.user_id ";
		if($creteria == "BT") {
			if($type == "ALL") {
				$query .= " and date(a.date_time) >= '$startDate' and  date(a.date_time)  <= '$endDate' order by a.date_time desc ";
			}
			else{ 
				$query .= " and a.service_feature_code = '$type' and date(a.date_time) >= '$startDate' and  date(a.date_time)  <= '$endDate' order by a.date_time desc ";
			}
		}
		if($creteria == "BO") {
			$query .= " and a.fin_service_order_no = $orderNo order by a.fin_service_order_no";
		}
		
		error_log("qyetr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("code"=>$row['service_feature_code'],"no"=>$row['fin_service_order_no'],"reqmount"=>$row['request_amount'],"toamount"=>$row['total_amount'],"dtime"=>$row['date_time'],"user"=>$row['user']);           
		}
		echo json_encode($data);
	}

	if($action == "view") {
		$query = "SELECT a.fin_service_order_no, a.fin_trans_log_id, a.service_feature_code,a.total_amount,a.request_amount, a.ams_charge,a.partner_charge,a.other_charge, a.customer_name, a.mobile_no, a.auth_code, a.reference_no, a.comment, a.date_time, a.post_status, a.post_time ,a.service_feature_config_id, concat(b.user_name,' (',b.first_name,' - ', b.last_name,') ') as user FROM fin_service_order a, user b, service_feature_config c, agent_info d WHERE a.user_id = d.user_id and b.user_id = d.user_id and b.parent_code = '$championName' and a.service_feature_config_id = c.service_feature_config_id and a.user_id = b.user_id and a.fin_service_order_no = $orderNo";
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("no"=>$row['fin_service_order_no'],"transLogId"=>$row['fin_trans_log_id'],"code"=>$row['service_feature_code'],"toamount"=>$row['total_amount'],"rmount"=>$row['request_amount'],"user"=>$row['user'],"amscharge"=>$row['ams_charge'],
							"parcharge"=>$row['partner_charge'],"ocharge"=>$row['other_charge'],"name"=>$row['customer_name'],"mobile"=>$row['mobile_no'],"auth"=>$row['auth_code'],"refNo"=>$row['reference_no']," comment"=>$row['comment'],"dtime"=>$row['date_time'],"pstatus"=>$row['post_status'],"ptime"=>$row['post_time'],"sconfid"=>$row['service_feature_config_id'],"user"=>$row['user']);           
		}
		echo json_encode($data);
	}
	
	if($action == "viewcomm") {
		$query = "select if(a.rate_factor = 'P','Percentage','Amount') as rate_factor,a.rate_value, b.service_charge_group_name,c.service_charge_party_name FROM service_charge_rate a, service_charge_group b , fin_service_order_comm c, agent_info d WHERE d.user_id = c.user_id and d.parent_code = '$championName' and c.service_charge_rate_id  = a.service_charge_rate_id  and b.service_charge_group_id = a.service_charge_group_id  and c.fin_service_order_no = $orderNo";
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("rate_factor"=>$row['rate_factor'],"rate_value"=>$row['rate_value'],"service_charge_group_name"=>$row['service_charge_group_name'],"service_charge_party_name"=>$row['service_charge_party_name']);           
		}
		echo json_encode($data);
	}
?>