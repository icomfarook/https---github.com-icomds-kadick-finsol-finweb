 <?php
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	$action		=  $data->action;
	$type		=  $data->type;
	$orderNo	=  $data->orderNo;
	$startDate	=  $data->startDate;
	$endDate	=  $data->endDate;
	$partycode 	= $data->partycode;
	$reportFor 	= $data->reportFor;
	$championCode 	= $data->championCode;
	$Terminal 	= $data->Terminal;	
	$state 	= $data->state;	
	$creteria 	= $data->creteria;
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));	
	$profileid = $_SESSION['profile_id'];
	error_log($reportFor."|".$partycode);
	if($action == "getreport") {
	
		if($profileid == 1 || $profileid == 10 || $profileid == 20 || $profileid == 21 || $profileid == 22 || $profileid == 23 || $profileid == 24 || $profileid == 25 || $profileid == 26 ) {
			$query = "SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.fin_service_order_no, a.request_amount, a.total_amount, a.date_time as date_time, IF(a.service_feature_code='CIN',a.auth_code , IF(a.service_feature_code='COU',a.auth_code, IF(a.service_feature_code='MP0',c.rrn,''))) as reference, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user FROM fin_service_order a, agent_info b, fin_request c, service_feature d,user_pos e WHERE a.fin_service_order_no = c.order_no and c.status = 'S' and a.user_id = b.user_id and a.service_feature_code = d.feature_code and a.user_id = e.user_id";
		}
		if($profileid  == 50) {
			if($reportFor == 'ALL'){
				$query = "SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.fin_service_order_no, a.request_amount, a.total_amount, a.date_time as date_time, IF(a.service_feature_code='CIN',a.auth_code , IF(a.service_feature_code='COU',a.auth_code, IF(a.service_feature_code='MP0',c.rrn,''))) as reference, b.champion_name as user FROM fin_service_order a, champion_info b, fin_request c, service_feature d WHERE a.fin_service_order_no = c.order_no and c.status = 'S' and a.service_feature_code = d.feature_code and b.champion_code = '".$_SESSION['party_code']."' and a.user_id = b.user_id 
				UNION
				SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.fin_service_order_no, a.request_amount, a.total_amount, a.date_time as date_time, IF(a.service_feature_code='CIN',a.auth_code , IF(a.service_feature_code='COU',a.auth_code, IF(a.service_feature_code='MP0',c.rrn,''))) as reference, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user FROM fin_service_order a, agent_info b, fin_request c, service_feature d WHERE a.fin_service_order_no = c.order_no and c.status = 'S' and a.service_feature_code = d.feature_code and b.parent_code = '".$_SESSION['party_code']."' and a.user_id = b.user_id ";
			}else if($reportFor == 'C'){
				$query = "SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.fin_service_order_no, a.request_amount, a.total_amount, a.date_time as date_time, IF(a.service_feature_code='CIN',a.auth_code , IF(a.service_feature_code='COU',a.auth_code, IF(a.service_feature_code='MP0',c.rrn,''))) as reference, b.champion_name as user FROM fin_service_order a, champion_info b, fin_request c, service_feature d WHERE a.fin_service_order_no = c.order_no and c.status = 'S' and a.service_feature_code = d.feature_code and b.champion_code = '".$_SESSION['party_code']."' and a.user_id = b.user_id ";
				
			}else{
				if($partycode == 'ALL'){
					$query = "SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.fin_service_order_no, a.request_amount, a.total_amount, a.date_time as date_time, IF(a.service_feature_code='CIN',a.auth_code , IF(a.service_feature_code='COU',a.auth_code, IF(a.service_feature_code='MP0',c.rrn,''))) as reference, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user FROM fin_service_order a, agent_info b, fin_request c, service_feature d WHERE a.fin_service_order_no = c.order_no and c.status = 'S' and a.service_feature_code = d.feature_code and b.parent_code = '".$_SESSION['party_code']."' and a.user_id = b.user_id ";
				}else{
					$query = "SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.fin_service_order_no, a.request_amount, a.total_amount, a.date_time as date_time, IF(a.service_feature_code='CIN',a.auth_code , IF(a.service_feature_code='COU',a.auth_code, IF(a.service_feature_code='MP0',c.rrn,''))) as reference, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user FROM fin_service_order a, agent_info b, fin_request c, service_feature d WHERE a.fin_service_order_no = c.order_no and c.status = 'S' and a.service_feature_code = d.feature_code and b.parent_code = '".$_SESSION['party_code']."' and a.user_id = b.user_id and  b.agent_code = '$partycode'";
				}
				
			}
			
		}
		if($profileid  == 52) {
			$query = "SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.fin_service_order_no, a.request_amount, a.total_amount, a.date_time as date_time, IF(a.service_feature_code='CIN',a.auth_code , IF(a.service_feature_code='COU',a.auth_code, IF(a.service_feature_code='MP0',c.rrn,''))) as reference, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user FROM fin_service_order a, agent_info b, fin_request c, service_feature d WHERE a.fin_service_order_no = c.order_no and c.status = 'S' and a.service_feature_code = d.feature_code and b.agent_code = '".$_SESSION['party_code']."' and b.sub_agent = 'Y' and a.user_id = b.user_id ";
		}
		if($creteria == "BT") {
			if($type == "ALL") {
				$query .= " and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
			}
			else{ 
				$query .= " and a.service_feature_code = '$type' and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
			}
		}
		if($creteria == "BO") {
			$query .= " and a.fin_service_order_no = $orderNo order by a.fin_service_order_no";
		}
		if($creteria == "C") { 
			if($championCode == "ALL") {
				$query .= " and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
			}
			else{ 
				$query .= " and b.parent_code = '$championCode' and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
			}
		}
		if($creteria == "S") { 
			if($state == "ALL") {
				$query .= " and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
			}
			else{ 
				$query .= " and b.state_id = '$state' and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
			}
		}
		if($creteria == "T") { 
			$query .= " and e.terminal_id = '$Terminal'";
		}
	
		
		error_log("sales report query = ".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("code"=>$row['service_feature_code'],"no"=>$row['fin_service_order_no'],"reqmount"=>$row['request_amount'],"toamount"=>$row['total_amount'],"dtime"=>$row['date_time'],"user"=>$row['user'], "reference"=>$row['reference']);           
		}
		echo json_encode($data);
	}
	else if($action == "view") {
		$code	=  $data->code;
		 if($profileid == 1 || $profileid == 10 || $profileid == 24 || $profileid == 22 || $profileid == 20 || $profileid == 23 || $profileid == 26  || $profileid  == 50) {
			if($code =='CIN - Cash In'){
		    		$query = "SELECT a.fin_service_order_no, a.fin_trans_log_id, a.service_feature_code, a.total_amount,a.request_amount, a.ams_charge, a.partner_charge, a.other_charge, a.customer_name, a.mobile_no, a.auth_code, a.reference_no, a.comment, a.date_time, a.post_status, a.post_time, a.service_feature_config_id, concat(b.user_name,' (',b.first_name,' - ', b.last_name,') ') as user,concat(d.bank_master_id,' - ',d.name) as bank, concat(e.partner_id,' - ',e.partner_name) as partner ,if(a.service_feature_code='CIN','Cash-In (Account)',if(a.service_feature_code='COU','Cash-Out (Account)',if(a.service_feature_code='MP0','Cash-Out (Card)','-'))) as transaction_type,  g.sender_name,if(g.status='S','SUCCESS',if(g.status='G','TRIGGERED','-')) as status FROM fin_service_order a, user b, bank_master d, ams_partner e, agent_info f, fin_request g,champion_info h WHERE IF(b.user_type='C',a.user_id = h.user_id, a.user_id = f.user_id) and a.partner_id = e.partner_id and a.bank_id = d.bank_master_id and a.user_id = b.user_id and a.fin_service_order_no = g.order_no and a.fin_service_order_no = $orderNo";
			}else{
				$query = "SELECT a.fin_service_order_no, a.fin_trans_log_id, a.service_feature_code, g.total_amount,g.request_amount, a.ams_charge, g.partner_charge, g.other_charge,(g.service_charge + g.partner_charge) as service_charge, a.customer_name, a.mobile_no, a.auth_code, a.reference_no, a.comment, a.date_time, a.post_status, a.post_time, a.service_feature_config_id, concat(b.user_name,' (',b.first_name,' - ', b.last_name,') ') as user,concat(e.partner_id,' - ',e.partner_name) as partner, if(a.service_feature_code='CIN','Cash-In (Account)',if(a.service_feature_code='COU','Cash-Out (Account)',if(a.service_feature_code='MP0','Cash-Out (Card)','-'))) as transaction_type, g.sender_name,if(g.status='S','SUCCESS',if(g.status='G','TRIGGERED','-')) as status, g.approver_comments,f.agent_code  FROM fin_service_order a, user b, ams_partner e, agent_info f, fin_request g, champion_info h WHERE IF(b.user_type='C',a.user_id = h.user_id, a.user_id = f.user_id)  and a.partner_id = e.partner_id and a.user_id = b.user_id and a.fin_service_order_no = g.order_no and a.fin_service_order_no = $orderNo";
			}
		}
		else if($profileid  == 51) {
			if($code =='CIN - Cash In'){
		    		$query = " SELECT a.fin_service_order_no, a.fin_trans_log_id, a.service_feature_code, a.total_amount,a.request_amount, a.ams_charge, a.partner_charge, a.other_charge, a.customer_name, a.mobile_no, a.auth_code, a.reference_no, a.comment, a.date_time, a.post_status, a.post_time, a.service_feature_config_id, concat(b.user_name,' (',b.first_name,' - ', b.last_name,') ') as user,concat(d.bank_master_id,' - ',d.name) as bank, concat(e.partner_id,' - ',e.partner_name) as partner ,if(a.service_feature_code='CIN','Cash-In (Account)',if(a.service_feature_code='COU','Cash-Out (Account)',if(a.service_feature_code='MP0','Cash-Out (Card)','-'))) as transaction_type,  g.sender_name,if(g.status='S','SUCCESS',if(g.status='G','TRIGGERED','-')) as status FROM fin_service_order a, user b, bank_master d, ams_partner e, agent_info f, fin_request g WHERE a.user_id = f.user_id and f.agent_code = '".$_SESSION['party_code']."' and a.partner_id = e.partner_id and a.bank_id = d.bank_master_id and a.user_id = b.user_id and a.fin_service_order_no = g.order_no and a.fin_service_order_no = $orderNo";
			}else{
				$query = " SELECT a.fin_service_order_no, a.fin_trans_log_id, a.service_feature_code, g.total_amount,g.request_amount, a.ams_charge, g.partner_charge, g.other_charge,(g.service_charge + g.partner_charge) as service_charge, a.customer_name, a.mobile_no, a.auth_code, a.reference_no, a.comment, a.date_time, a.post_status, a.post_time, a.service_feature_config_id, concat(b.user_name,' (',b.first_name,' - ', b.last_name,') ') as user,concat(e.partner_id,' - ',e.partner_name) as partner, if(a.service_feature_code='CIN','Cash-In (Account)',if(a.service_feature_code='COU','Cash-Out (Account)',if(a.service_feature_code='MP0','Cash-Out (Card)','-'))) as transaction_type,  g.sender_name,if(g.status='S','SUCCESS',if(g.status='G','TRIGGERED','-')) as status, g.approver_comments,f.agent_code FROM fin_service_order a, user b, ams_partner e, agent_info f, fin_request g WHERE a.user_id = f.user_id and f.agent_code = '".$_SESSION['party_code']."' and a.partner_id = e.partner_id and a.user_id = b.user_id and a.fin_service_order_no = g.order_no and a.fin_service_order_no = $orderNo";
			}
		}
		else if($profileid  == 52) {
			$query = " SELECT a.fin_service_order_no, a.fin_trans_log_id, a.service_feature_code, a.total_amount,a.request_amount, a.ams_charge, a.partner_charge, a.other_charge, a.customer_name, a.mobile_no, a.auth_code, a.reference_no, a.comment, a.date_time, a.post_status, a.post_time, a.service_feature_config_id, concat(b.user_name,' (',b.first_name,' - ', b.last_name,') ') as user, d.name as bank, concat(e.partner_id,' - ',e.partner_name) as partner,f.agent_code FROM fin_service_order a, user b, bank_master d, ams_partner e, agent_info f WHERE a.user_id = f.user_id and f.sub_agent = 'Y' and f.agent_code = '".$_SESSION['party_code']."' and a.partner_id = e.partner_id and a.bank_id = d.bank_master_id  a.user_id = b.user_id and a.fin_service_order_no  = $orderNo";
		}	
		error_log("sales report query = ".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("no"=>$row['fin_service_order_no'],"transLogId"=>$row['fin_trans_log_id'],"code"=>$row['service_feature_code'],"toamount"=>$row['total_amount'],"rmount"=>$row['request_amount'],"user"=>$row['user'],"amscharge"=>$row['ams_charge'],"parcharge"=>$row['partner_charge'],"ocharge"=>$row['other_charge'],"name"=>$row['customer_name'],"mobile"=>$row['mobile_no'],"auth"=>$row['auth_code'],"refNo"=>$row['reference_no'],"fincomment"=>$row['comment'],"dtime"=>$row['date_time'],"pstatus"=>$row['post_status'],"ptime"=>$row['post_time'],"sconfid"=>$row['service_feature_config_id'],"user"=>$row['user'],"bank"=>$row['bank'],"partner"=>$row['partner'],"type"=>$row['transaction_type'],"sender_name"=>$row['sender_name'],"sts"=>$row['status'], "appcmt"=>$row['approver_comments'], "agentCode"=>$row['agent_code'],"scharge"=>$row['service_charge']);           
		}
		echo json_encode($data);
	}
	else if($action == "viewcomm") {
		 if($profileid == 1 || $profileid == 10 || $profileid == 24 || $profileid == 22 || $profileid == 20 || $profileid == 23 || $profileid == 26  || $profileid  == 50) {
			$query = "select if(a.rate_factor = 'P','Percentage','Amount') as rate_factor, a.rate_value, concat(d.user_name) as service_charge_group_name, c.service_charge_party_name FROM service_charge_rate a, service_charge_group b, fin_service_order_comm c, user d WHERE c.user_id = d.user_id and c.service_charge_rate_id = a.service_charge_rate_id  and b.service_charge_group_id = a.service_charge_group_id  and c.fin_service_order_no = $orderNo";
		}
		else if($profileid  == 51) {
			$query = "select if(a.rate_factor = 'P','Percentage','Amount') as rate_factor,a.rate_value, concat(d.user_name,' [',e.agent_code,'] ') as service_charge_group_name,c.service_charge_party_name FROM service_charge_rate a, service_charge_group b, fin_service_order_comm c, user d, agent_info e WHERE e.user_id = d.user_id and e.agent_code = '".$_SESSION['party_code']."' and c.user_id = d.user_id and c.service_charge_rate_id  = a.service_charge_rate_id  and b.service_charge_group_id = a.service_charge_group_id  and c.fin_service_order_no = $orderNo";
		}
		else if($profileid  == 52) {
			$query = "select if(a.rate_factor = 'P','Percentage','Amount') as rate_factor,a.rate_value, concat(d.user_name,' [',e.agent_code,'] ') as service_charge_group_name,c.service_charge_party_name FROM service_charge_rate a, service_charge_group b, fin_service_order_comm c, user d, agent_info e WHERE e.user_id = d.user_id and e.sub_agent = 'Y' and e.agent_code = '".$_SESSION['party_code']."' and c.user_id = d.user_id and c.service_charge_rate_id  = a.service_charge_rate_id  and b.service_charge_group_id = a.service_charge_group_id  and c.fin_service_order_no = $orderNo";
		}
		error_log("sales report query = ".$query);
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