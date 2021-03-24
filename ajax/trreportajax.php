 <?php
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	$action		=  $data->action;
	$type	=  $data->type;
	$state	=  $data->state;
	$championCode	=  $data->championCode;
	$status	=  $data->status;
	$orderNo	=  $data->orderNo;
	$startDate	=  $data->startDate;
	$endDate	=  $data->endDate;
	$creteria 	= $data->creteria;
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
	$profileid = $_SESSION['profile_id'];
	
	if($action == "getreport") {
	
		if($profileid == 1 || $profileid == 10 || $profileid == 24 || $profileid == 22 || $profileid == 20 || $profileid == 23 || $profileid == 26  || $profileid  == 50) {
			$query = "SELECT concat(c.service_feature_code, ' - ', d.feature_description) as service_feature_code, c.order_no, c.request_amount, c.total_amount, c.create_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user, if(c.status='I','I-Inprogress',if(c.status='N','N-Name Enqiury',if(c.status='S','S-Success',if(c.status='E','E-Error',if(c.status='T','T-Timeout',if(c.status='G','G-Triggered',if(c.status='R','R-Request Cancel',if(c.status='X','X-Cancelled',if(c.status='O','O-Request Confirm','-'))))))))) as status,  ifNULL(c.rrn,'-') as rrn FROM agent_info b, fin_request c, service_feature d WHERE c.user_id = b.user_id and c.service_feature_code = d.feature_code ";
		}
		if($profileid  == 51) {
			$query = "SELECT concat(c.service_feature_code, ' - ', d.feature_description) as service_feature_code, c.order_no, c.request_amount, c.total_amount, c.create_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user, if(c.status='I','I-Inprogress',if(c.status='N','N-Name Enqiury',if(c.status='S','S-Success',if(c.status='E','E-Error',if(c.status='T','T-Timeout',if(c.status='G','G-Triggered',if(c.status='R','R-Request Cancel',if(c.status='X','X-Cancelled',if(c.status='O','O-Request Confirm','-'))))))))) as status,  ifNULL(c.rrn,'-') as rrn FROM agent_info b, fin_request c, service_feature d WHERE  c.user_id = b.user_id and c.service_feature_code = d.feature_code and b.agent_code = '".$_SESSION['party_code']."'";
		}
		if($profileid  == 52) {
			$query = "SELECT concat(c.service_feature_code, ' - ', d.feature_description) as service_feature_code, c.order_no, c.request_amount, c.total_amount, c.create_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user, if(c.status='I','I-Inprogress',if(c.status='N','N-Name Enqiury',if(c.status='S','S-Success',if(c.status='E','E-Error',if(c.status='T','T-Timeout',if(c.status='G','G-Triggered',if(c.status='R','R-Request Cancel',if(c.status='X','X-Cancelled',if(c.status='O','O-Request Confirm','-'))))))))) as status, ifNULL(c.rrn,'-') as rrn FROM agent_info b, fin_request c, service_feature d WHERE  c.user_id = b.user_id and c.service_feature_code = d.feature_code and b.agent_code = '".$_SESSION['party_code']."' and b.sub_agent = 'Y'";
		}
		if($type == 'ALL') {
			if($status == "ALL") { 
			    if($state == "ALL") {
			        if($championCode == "ALL") { 
				  
				$query .= " and date(c.create_time) >= '$startDate' and  date(c.create_time) <= '$endDate' order by c.create_time desc ";
			}else{
				$query .= " and b.parent_code = '$championCode' and date(c.create_time) >= '$startDate' and  date(c.create_time) <= '$endDate' order by c.create_time desc ";
			}
				}else{
					$query .= " and c.state_id = '$state' and date(c.create_time) >= '$startDate' and  date(c.create_time) <= '$endDate' order by c.create_time desc ";
			}
				}
			}
			
		else {
			if($status == "ALL") { 
			    if($state == "ALL") {
			        if($championCode == "ALL") { 
				  
				$query .= " and  date(c.create_time) >= '$startDate' and  c.service_feature_code = '$type' and date(c.create_time) <= '$endDate' order by c.create_time desc ";
			}else{
				$query .= " and b.parent_code = '$championCode' and c.service_feature_code = '$type' and date(c.create_time) >= '$startDate' and  date(c.create_time) <= '$endDate' order by c.create_time desc ";
			}
				}else{
					$query .= " and c.state_id = '$state' and c.service_feature_code = '$type' and date(c.create_time) >= '$startDate' and  date(c.create_time) <= '$endDate' order by c.create_time desc ";
			    }
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
			$data[] = array("code"=>$row['service_feature_code'],"no"=>$row['order_no'],"reqmount"=>$row['request_amount'],"toamount"=>$row['total_amount'],"dtime"=>$row['create_time'],"user"=>$row['user'], "status"=>$row['status'], "rrn"=>$row['rrn']);           
		}
		echo json_encode($data);
	}
	else if($action == "view") {
		$orderNo	=  $data->orderNo;
		$code	=  $data->code;
		if($profileid == 1 || $profileid == 10 || $profileid == 24 || $profileid == 22 || $profileid == 20 || $profileid == 23 || $profileid == 26  || $profileid  == 50) {
			if($code =='CIN - Cash In'){
		    		$query = "SELECT a.order_no, a.fin_trans_log_id1, a.fin_trans_log_id2, a.service_feature_code, a.total_amount,a.request_amount, a.service_charge, a.partner_charge, a.other_charge, g.customer_name, a.mobile_no, a.auth_code, g.reference_no, a.comments, a.create_time, a.create_time, a.update_time, concat(b.user_name,' (',b.first_name,' - ', b.last_name,') ') as user,concat(d.bank_master_id,' - ',d.name) as bank, concat(e.partner_id,' - ',e.partner_name) as partner ,if(a.service_feature_code='CIN','Cash-In (Account)',if(a.service_feature_code='COU','Cash-Out (Account)',if(a.service_feature_code='MP0','Cash-Out (Card)','-'))) as transaction_type,  a.sender_name,if(a.status='I','I-Inprogress',if(a.status='N','N-Name Enqiury',if(a.status='S','S-Success',if(a.status='E','E-Error',if(a.status='T','T-Timeout',if(a.status='G','G-Triggered',if(a.status='R','R-Request Cancel',if(a.status='X','X-Cancelled',if(a.status='O','O-Request Confirm','-'))))))))) as status, a.approver_comments FROM  user b, bank_master d, ams_partner e, agent_info f,fin_request a LEFT JOIN  fin_service_order g  on g.fin_service_order_no = a.order_no  WHERE  a.user_id = f.user_id  and g.partner_id = e.partner_id and g.bank_id = d.bank_master_id and a.user_id = b.user_id  and a.order_no = $orderNo LIMIT 1";
			}else{
				$query = "SELECT a.order_no, a.fin_trans_log_id1, a.fin_trans_log_id2, a.service_feature_code, a.total_amount,a.request_amount, a.service_charge, a.partner_charge, a.other_charge,  a.mobile_no, a.auth_code,  a.comments, a.create_time, a.create_time, a.update_time, concat(b.user_name,' (',b.first_name,' - ', b.last_name,') ') as user, concat(e.partner_id,' - ',e.partner_name) as partner ,if(a.service_feature_code='CIN','Cash-In (Account)',if(a.service_feature_code='COU','Cash-Out (Account)',if(a.service_feature_code='MP0','Cash-Out (Card)','-'))) as transaction_type,  a.sender_name,if(a.status='I','I-Inprogress',if(a.status='N','N-Name Enqiury',if(a.status='S','S-Success',if(a.status='E','E-Error',if(a.status='T','T-Timeout',if(a.status='G','G-Triggered',if(a.status='R','R-Request Cancel',if(a.status='X','X-Cancelled',if(a.status='O','O-Request Confirm','-'))))))))) as status,a.approver_comments FROM fin_request a, user b, ams_partner e, agent_info f WHERE a.user_id = f.user_id  and a.user_id = b.user_id and  a.order_no = $orderNo LIMIT 1";
			}
		}
		else if($profileid  == 51) {
			if($code =='CIN - Cash In'){
		    		$query = "SELECT a.order_no, a.fin_trans_log_id1, a.fin_trans_log_id2, a.service_feature_code, a.total_amount,a.request_amount, a.service_charge, a.partner_charge, a.other_charge, g.customer_name, a.mobile_no, a.auth_code, g.reference_no, a.comments, a.create_time, a.create_time, a.update_time, concat(b.user_name,' (',b.first_name,' - ', b.last_name,') ') as user,concat(d.bank_master_id,' - ',d.name) as bank, concat(e.partner_id,' - ',e.partner_name) as partner ,if(a.service_feature_code='CIN','Cash-In (Account)',if(a.service_feature_code='COU','Cash-Out (Account)',if(a.service_feature_code='MP0','Cash-Out (Card)','-'))) as transaction_type,  a.sender_name,if(a.status='I','I-Inprogress',if(a.status='N','N-Name Enqiury',if(a.status='S','S-Success',if(a.status='E','E-Error',if(a.status='T','T-Timeout',if(a.status='G','G-Triggered',if(a.status='R','R-Request Cancel',if(a.status='X','X-Cancelled',if(a.status='O','O-Request Confirm','-'))))))))) as status,a.approver_comments FROM  user b, bank_master d, ams_partner e, agent_info f, fin_request a LEFT JOIN  fin_service_order g  on g.fin_service_order_no = a.order_no   WHERE a.user_id = f.user_id and f.agent_code = '".$_SESSION['party_code']."' and g.partner_id = e.partner_id and g.bank_id = d.bank_master_id and a.user_id = b.user_id and g.fin_service_order_no = a.order_no and a.order_no = $orderNo";
			}else{
				$query = "SELECT a.order_no, a.fin_trans_log_id1, a.fin_trans_log_id2, a.service_feature_code, a.total_amount,a.request_amount, a.service_charge, a.partner_charge, a.other_charge, g.customer_name, a.mobile_no, a.auth_code, g.reference_no, a.comments, a.create_time, a.create_time, a.update_time, concat(b.user_name,' (',b.first_name,' - ', b.last_name,') ') as user, concat(e.partner_id,' - ',e.partner_name) as partner ,if(a.service_feature_code='CIN','Cash-In (Account)',if(a.service_feature_code='COU','Cash-Out (Account)',if(a.service_feature_code='MP0','Cash-Out (Card)','-'))) as transaction_type,  a.sender_name,if(a.status='I','I-Inprogress',if(a.status='N','N-Name Enqiury',if(a.status='S','S-Success',if(a.status='E','E-Error',if(a.status='T','T-Timeout',if(a.status='G','G-Triggered',if(a.status='R','R-Request Cancel',if(a.status='X','X-Cancelled',if(a.status='O','O-Request Confirm','-'))))))))) as status,a.approver_comments FROM user b,  ams_partner e, agent_info f,fin_request a LEFT JOIN  fin_service_order g  on g.fin_service_order_no = a.order_no   WHERE a.user_id = f.user_id and f.agent_code = '".$_SESSION['party_code']."' and g.partner_id = e.partner_id  and a.user_id = b.user_id and g.fin_service_order_no = a.order_no and a.order_no = $orderNo";
			}
		}
		else if($profileid  == 52) {
			$query = " SELECT a.fin_service_order_no, a.fin_trans_log_id, a.service_feature_code, a.total_amount,a.request_amount, a.ams_charge, a.partner_charge, a.other_charge, a.customer_name, a.mobile_no, a.auth_code, a.reference_no, a.comment, a.date_time, a.post_status, a.post_time, a.service_feature_config_id, concat(b.user_name,' (',b.first_name,' - ', b.last_name,') ') as user, d.name as bank, concat(e.partner_id,' - ',e.partner_name) as partner,a.approver_comments FROM fin_service_order a, user b, bank_master d, ams_partner e, agent_info f WHERE a.user_id = f.user_id and f.sub_agent = 'Y' and f.agent_code = '".$_SESSION['party_code']."' and a.partner_id = e.partner_id and a.bank_id = d.bank_master_id  a.user_id = b.user_id and a.fin_service_order_no  = $orderNo";
		}	
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("no"=>$row['order_no'],"transLogId1"=>$row['fin_trans_log_id1'],"transLogId2"=>$row['fin_trans_log_id2'], "code"=>$row['service_feature_code'],"toamount"=>$row['total_amount'],"rmount"=>$row['request_amount'],"user"=>$row['user'],"service_charge"=>$row['service_charge'],"parcharge"=>$row['partner_charge'],"ocharge"=>$row['other_charge'],"name"=>$row['customer_name'],"mobile"=>$row['mobile_no'],"auth"=>$row['auth_code'],"refNo"=>$row['reference_no'],"fincomment"=>$row['comments'],"dtime"=>$row['create_time'],"pstatus"=>$row['post_status'],"update_time"=>$row['update_time'],"sconfid"=>$row['service_feature_config_id'],"user"=>$row['user'],"bank"=>$row['bank'],"partner"=>$row['partner'],"type"=>$row['transaction_type'],"sender_name"=>$row['sender_name'],"sts"=>$row['status'], "appcmt"=>$row['approver_comments']);           
		}
		echo json_encode($data);
	}
	else if($action == "viewcomm") {
		if($profileid == 1 || $profileid == 10 || $profileid == 24 || $profileid == 22 || $profileid == 20 || $profileid == 23 || $profileid == 26  || $profileid  == 50) {
			$query = " select 'Amount' as rate_factor, a.charge_value, b.user_name as service_charge_group_name, a.service_charge_party_name from fin_service_order_comm a, user b where a.user_id = b.user_id and a.fin_service_order_no =$orderNo";
		}
		else if($profileid  == 51) {
			$query = "select if(a.rate_factor = 'P','Percentage','Amount') as rate_factor,a.charge_value, concat(d.user_name,' [',e.agent_code,'] ') as service_charge_group_name,c.service_charge_party_name FROM service_charge_rate a, service_charge_group b, fin_service_order_comm c, user d, agent_info e WHERE e.user_id = d.user_id and e.agent_code = '".$_SESSION['party_code']."' and c.user_id = d.user_id and c.service_charge_rate_id  = a.service_charge_rate_id  and b.service_charge_group_id = a.service_charge_group_id  and c.fin_service_order_no = $orderNo";
		}
		else if($profileid  == 52) {
			$query = "select if(a.rate_factor = 'P','Percentage','Amount') as rate_factor,a.charge_value, concat(d.user_name,' [',e.agent_code,'] ') as service_charge_group_name,c.service_charge_party_name FROM service_charge_rate a, service_charge_group b, fin_service_order_comm c, user d, agent_info e WHERE e.user_id = d.user_id and e.sub_agent = 'Y' and e.agent_code = '".$_SESSION['party_code']."' and c.user_id = d.user_id and c.service_charge_rate_id  = a.service_charge_rate_id  and b.service_charge_group_id = a.service_charge_group_id  and c.fin_service_order_no = $orderNo";
		}
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("rate_factor"=>$row['rate_factor'],"rate_value"=>$row['charge_value'],"service_charge_group_name"=>$row['service_charge_group_name'],"service_charge_party_name"=>$row['service_charge_party_name']);           
		}
		echo json_encode($data);
	}
?>