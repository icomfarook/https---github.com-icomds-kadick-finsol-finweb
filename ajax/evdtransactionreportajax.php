 <?php
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	$action		=  $data->action;
	$type	=  $data->type;
	$orderNo	=  $data->orderNo;
	$startDate	=  $data->startDate;
	$endDate	=  $data->endDate;
	$creteria 	= $data->creteria;
	$partycode 	= $data->partycode;
	$reportFor 	= $data->reportFor;
	$state 	= $data->state;	 
	$local_govt_id 	= $data->local_govt_id;
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));	
	$profileid = $_SESSION['profile_id'];
	if($action == "getreport") {
	
		if($profileid == 1 || $profileid == 10 || $profileid == 24 || $profileid == 22 || $profileid == 20 || $profileid == 23 || $profileid == 26 ) {
			$query = "SELECT a.e_transaction_id, a.request_amount, a.total_amount, a.ams_charge, a.partner_charge, a.other_charge, a.date_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user, c.operator_description FROM evd_transaction a, agent_info b, operator c  WHERE a.user_id = b.user_id and a.operator_id = c.operator_id ";
			
			if($creteria == "BT") {
				if($type == "ALL") {
					$query .= " and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
				}
				else{ 
					$query .= " and a.operator_id = '$type' and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
				}
			}
			if($creteria == "S"){
				if($state == "ALL"){
					$query .= " and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
					
				}else{
					$query .= " and b.state_id = '$state' and b.local_govt_id='$local_govt_id' and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
				}
			}
			if($creteria == "BO") {
				$query .= " and a.e_transaction_id = $orderNo order by a.e_transaction_id";
			}
		}
		if($profileid  == 50) {
			if($reportFor == 'ALL'){
				$query = "SELECT * FROM (
							  SELECT a.e_transaction_id, a.request_amount, a.total_amount, a.ams_charge, a.partner_charge, a.other_charge, a.date_time as date_time, champion_name as user, c.operator_description,a.operator_id as operator_id FROM evd_transaction a, champion_info b, operator c  WHERE a.user_id = b.user_id and a.operator_id = c.operator_id and b.champion_code='".$_SESSION['party_code']."'
							  UNION  
					  SELECT a.e_transaction_id, a.request_amount, a.total_amount, a.ams_charge, a.partner_charge, a.other_charge, a.date_time as date_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user, c.operator_description, a.operator_id as operator_id FROM evd_transaction a, agent_info b, operator c  WHERE a.user_id = b.user_id and a.operator_id = c.operator_id and b.parent_code='".$_SESSION['party_code']." '   
							) A  ";
				if($creteria == "BT") {
					if($type == "ALL") {
						$query .= " WHERE date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
					}
					else{ 
						$query .= " WHERE a.operator_id = '$type' and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
					}
				}
				else{
					$query .= " WHERE a.e_transaction_id = $orderNo order by a.e_transaction_id";
				}
			}else if($reportFor == 'C'){
				  $query = "SELECT a.e_transaction_id, a.request_amount, a.total_amount, a.ams_charge, a.partner_charge, a.other_charge, a.date_time as date_time, champion_name as user, c.operator_description FROM evd_transaction a, champion_info b, operator c  WHERE a.user_id = b.user_id and a.operator_id = c.operator_id and b.champion_code='".$_SESSION['party_code']."' ";
				if($creteria == "BT") {
					if($type == "ALL") {
						$query .= " and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
					}
					else{ 
						$query .= " and a.operator_id = '$type' and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
					}
				}
				else{
					$query .= " and a.e_transaction_id = $orderNo order by a.e_transaction_id";
				}			
				
			}else{
				if($partycode == 'ALL'){
					$query = "SELECT a.e_transaction_id, a.request_amount, a.total_amount, a.ams_charge, a.partner_charge, a.other_charge, a.date_time as date_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user, c.operator_description FROM evd_transaction a, agent_info b, operator c  WHERE a.user_id = b.user_id and a.operator_id = c.operator_id and b.parent_code='".$_SESSION['party_code']."' ";
				}else{
					$query = "SELECT a.e_transaction_id, a.request_amount, a.total_amount, a.ams_charge, a.partner_charge, a.other_charge, a.date_time as date_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user, c.operator_description FROM evd_transaction a, agent_info b, operator c  WHERE a.user_id = b.user_id and a.operator_id = c.operator_id and b.parent_code='".$_SESSION['party_code']."' and b.agent_code = '$partycode' ";
				}
				if($creteria == "BT") {
					if($type == "ALL") {
						$query .= " and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
					}
					else{ 
						$query .= " and a.operator_id = '$type' and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
					}
				}
				else{
					$query .= " and a.e_transaction_id = $orderNo order by a.e_transaction_id";
				}			
			}
				
		}
		if($profileid  == 51) {
			$query = "SELECT a.e_transaction_id, a.request_amount, a.total_amount, a.ams_charge, a.partner_charge, a.other_charge, a.date_time as date_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user, c.operator_description FROM evd_transaction a, agent_info b, operator c  WHERE a.user_id = b.user_id and a.operator_id = c.operator_id and b.user_id = '".$_SESSION['user_id']."'";
			if($creteria == "BT") {
				if($type == "ALL") {
					$query .= " and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
				}
				else{ 
					$query .= " and a.operator_id = '$type' and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
				}
			}
			else{
				$query .= " and a.e_transaction_id = $orderNo order by a.e_transaction_id";
			}
		}
		if($profileid  == 52) {
			$query = "SELECT a.e_transaction_id, a.request_amount, a.total_amount, a.ams_charge, a.partner_charge, a.other_charge, a.date_time as date_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user, c.operator_description FROM evd_transaction a, agent_info b, operator c  WHERE a.user_id = b.user_id and a.operator_id = c.operator_id and b.user_id = '".$_SESSION['user_id']."' and b.sub_agent = 'Y'";
			if($creteria == "BT") {
				if($type == "ALL") {
					$query .= " and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
				}
				else{ 
					$query .= " and a.operator_id = '$type' and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
				}
			}
			else{
				$query .= " and a.e_transaction_id = $orderNo order by a.e_transaction_id";
			}
		}
		
		
		error_log("qyetr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("no"=>$row['e_transaction_id'],"reqmount"=>$row['request_amount'],"toamount"=>$row['total_amount'],"dtime"=>$row['date_time'],"user"=>$row['user'], "operator"=>$row['operator_description']);           
		}
		echo json_encode($data);
	}
	else if($action == "view") {
		$code	=  $data->code;
		if($profileid == 1 || $profileid == 10 || $profileid == 24 || $profileid == 22 || $profileid == 20 || $profileid == 23 || $profileid == 26  || $profileid  == 50) {
		$reportFor	=  $data->reportFor;
			$query = "SELECT a.e_transaction_id, a.request_amount, a.total_amount, a.ams_charge, a.partner_charge, a.other_charge, a.date_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user, c.operator_description, a.opr_plan_desc, a.mobile_number, a.total_discount, a.reference_no,a.reference_no2,a.reference_no3, a.reference_no4,a.service_feature_config_id, ifNULL(a.device_id,'-') as device_id, ifNULL(a.ar_lock,'-') as ar_lock, a.evd_trans_log_id, c.operator_code,concat(b.agent_name,'-','[',b.agent_code,']')as Agent_code FROM evd_transaction a, agent_info b, operator c, champion_info d,user e WHERE IF(e.user_type ='C',a.user_id = d.user_id,a.user_id = b.user_id) and a.user_id=e.user_id and d.champion_code = b.parent_code and a.operator_id = c.operator_id and a.e_transaction_id = $orderNo limit 1";
		} 
		else if($profileid  == 51) {
		    $query = "SELECT a.e_transaction_id, a.request_amount, a.total_amount, a.ams_charge, a.partner_charge, a.other_charge, a.date_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user, c.operator_description, a.opr_plan_desc, a.mobile_number, a.total_discount, a.reference_no, a.service_feature_config_id, a.device_id, a.ar_lock, a.evd_trans_log_id, c.operator_code FROM evd_transaction a, agent_info b, operator c  WHERE a.user_id = b.user_id and a.operator_id = c.operator_id and b.user_id = '".$_SESSION['user_id']."' and a.e_transaction_id = $orderNo";
		}
		else if($profileid  == 52) {
			$query = "SELECT a.e_transaction_id, a.request_amount, a.total_amount, a.ams_charge, a.partner_charge, a.other_charge, a.date_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user, c.operator_description, a.opr_plan_desc, a.mobile_number, a.total_discount, a.reference_no, a.service_feature_config_id, a.device_id, a.ar_lock, a.evd_trans_log_id, c.operator_code FROM evd_transaction a, agent_info b, operator c  WHERE a.user_id = b.user_id and a.operator_id = c.operator_id and b.user_id = '".$_SESSION['user_id']."'  and b.sub_agent = 'Y' and a.e_transaction_id = $orderNo";
		}	
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("no"=>$row['e_transaction_id'],"request_amount"=>$row['request_amount'],"total_amount"=>$row['total_amount'], "ams_charge"=>$row['ams_charge'],"partner_charge"=>$row['partner_charge'],"other_charge"=>$row['other_charge'],"date_time"=>$row['date_time'],"user"=>$row['user'],"Agent_code"=>$row['Agent_code'],"operator_description"=>$row['operator_description'],"opr_plan_desc"=>$row['opr_plan_desc'],"mobile_number"=>$row['mobile_number'],"total_discount"=>$row['total_discount'],"reference_no"=>$row['reference_no'],"reference_no2"=>$row['reference_no2'],"reference_no3"=>$row['reference_no3'],"reference_no4"=>$row['reference_no4'],"service_feature_config_id"=>$row['service_feature_config_id'],"device_id"=>$row['device_id'],"ar_lock"=>$row['ar_lock'],"evd_trans_log_id"=>$row['evd_trans_log_id'],"operator_code"=>$row['operator_code']);           
		}
		echo json_encode($data);
	}
	else if($action == "viewcomm") {
		if($profileid == 1 || $profileid == 10 || $profileid == 24 || $profileid == 22 || $profileid == 20 || $profileid == 23 || $profileid == 26  || $profileid  == 50) {
			$query = "select if(a.rate_factor = 'P','Percentage','Amount') as rate_factor, b.charge_value,c.user_name,b.service_charge_party_name from  service_charge_rate a, evd_service_order_comm b,user c where a.service_charge_rate_id = b.service_charge_rate_id and c.user_id = b.user_id and b.e_transaction_id = $orderNo";
		}
		else if($profileid  == 51) {
			$query = "select if(a.rate_factor = 'P','Percentage','Amount') as rate_factor, b.charge_value,c.user_name,b.service_charge_party_name from  service_charge_rate a, evd_service_order_comm b,user c where a.service_charge_rate_id = b.service_charge_rate_id and c.user_id = b.user_id  and  b.e_transaction_id = $orderNo";
		}
		else if($profileid  == 52) {
			$query = "select if(a.rate_factor = 'P','Percentage','Amount') as rate_factor, b.charge_value,c.user_name,b.service_charge_party_name from  service_charge_rate a, evd_service_order_comm b,user c,agent_info e where a.service_charge_rate_id = b.service_charge_rate_id and c.user_id =  and e.sub_agent = 'Y' and b.e_transaction_id = $orderNo";
		}
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("rate_factor"=>$row['rate_factor'],"rate_value"=>$row['charge_value'],"service_charge_group_name"=>$row['user_name'],"service_charge_party_name"=>$row['service_charge_party_name']);           
		}
		echo json_encode($data);
	}
?>