<?php
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	$action		=  $data->action;
	$type	=  $data->type;
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
			$query = "SELECT concat(c.service_feature_code, ' - ', d.feature_description) as service_feature_code, c.order_no, c.request_amount, c.total_amount, c.create_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user,if(c.status='I','I-Inprogress',if(c.status='S','S-Success',if(c.status='E','E-Error',if(c.status='T','T-Timeout',if(c.status='C','C-Cash In',if(c.status='V','V-Validate',if(c.status='S','S-Success',if(c.status='P','P-Payment Notify',if(c.status='O','O-others','-'))))))))) as status,   c.account_no as rrn FROM agent_info b, bp_request c, service_feature d WHERE c.user_id = b.user_id and c.service_feature_code = d.feature_code ";
		}
		if($profileid  == 51) {
			$query = "SELECT concat(c.service_feature_code, ' - ', d.feature_description) as service_feature_code, c.order_no, c.request_amount, c.total_amount, c.create_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user, if(c.status='I','I-Inprogress',if(c.status='S','S-Success',if(c.status='E','E-Error',if(c.status='T','T-Timeout',if(c.status='C','C-Cash In',if(c.status='V','V-Validate',if(c.status='S','S-Success',if(c.status='P','P-Payment Notify',if(c.status='O','O-others','-'))))))))) as status, c.account_no as  rrn FROM agent_info b, bp_request c, service_feature d WHERE  c.user_id = b.user_id and c.service_feature_code = d.feature_code and b.agent_code = '".$_SESSION['party_code']."'";
		}
		if($profileid  == 52) {
			$query = "SELECT concat(c.service_feature_code, ' - ', d.feature_description) as service_feature_code, c.order_no, c.request_amount, c.total_amount, c.create_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user,if(c.status='I','I-Inprogress',if(c.status='S','S-Success',if(c.status='E','E-Error',if(c.status='T','T-Timeout',if(c.status='C','C-Cash In',if(c.status='V','V-Validate',if(c.status='S','S-Success',if(c.status='P','P-Payment Notify',if(c.status='O','O-others','-'))))))))) as status, c.account_no  as rrn FROM agent_info b, bp_request c, service_feature d WHERE  c.user_id = b.user_id and c.service_feature_code = d.feature_code and b.agent_code = '".$_SESSION['party_code']."' and b.sub_agent = 'Y'";
		}
		if($type == 'ALL') {
			if($status == "ALL") { 
				$query .= " and date(c.create_time) >= '$startDate' and  date(c.create_time) <= '$endDate' order by c.create_time desc ";
			}
			else{ 
				$query .= " and c.status = '$status' and date(c.create_time) >= '$startDate' and  date(c.create_time) <= '$endDate' order by c.create_time desc ";
			}
		}
		else {
			if($status == "ALL") {
				$query .= " and c.service_feature_code = '$type' and date(c.create_time) >= '$startDate' and  date(c.create_time) <= '$endDate' order by c.create_time desc ";
			}
			else{ 
				$query .= " and c.service_feature_code = '$type' and c.status = '$status' and date(c.create_time) >= '$startDate' and  date(c.create_time) <= '$endDate' order by c.create_time desc ";
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
			if($code =='PEB - Electricity Payment'){
		    	$query = "SELECT a.order_no, ifNULL(a.bp_trans_log_id1,'-') as  bp_trans_log_id1 ,ifNULL(a.bp_trans_log_id2,'-') as  bp_trans_log_id2,ifNULL(a.bp_trans_log_id3,'-') as  bp_trans_log_id3, a.service_feature_code, a.total_amount,g.ams_charge,a.bp_transaction_id,a.request_amount, a.service_charge, a.partner_charge, a.other_charge, a.mobile_no,  a.comments, a.create_time, a.create_time,a.payment_fee,g.agent_charge,g.stamp_charge,g.post_time,if(g.post_status='Y','Y - Yes',if(g.post_status='E','E-Error',if(g.post_status='O','O-others','-'))) as post_status, a.update_time, concat(b.user_name,' (',b.first_name,' - ', b.last_name,') ') as user,concat(d.bank_master_id,' - ',d.name) as bank,concat(h.bp_biller_id,' - ',h.bp_biller_name) as Biller , ifNUll(concat(i.bp_product_id,' - ',i.bp_product_name),'-') as  Product, concat(e.partner_id,' - ',e.partner_name) as partner,  if(a.status='I','I-Inprogress',if(a.status='S','S-Success',if(a.status='E','E-Error',if(a.status='T','T-Timeout',if(a.status='C','C-Cash In',if(a.status='I','I-Inprogress',if(a.status='V','V-Validate',if(a.status='S','S-Success',if(a.status='P','P-Payment Notify',if(a.status='O','O-others','-')))))))))) as status, a.approver_comments,a.account_no,a.account_name,a.bp_account_no,a.bp_account_name,a.bp_bank_code,a.session_id FROM kadick_bp_biller h, user b, bank_master d, ams_partner e, agent_info f,bp_biller_product i RIGHT JOIN  bp_service_order z on  z.bp_product_id = i.bp_product_id,bp_request a LEFT JOIN  bp_service_order g  on g.bp_service_order_no = a.order_no  WHERE g.bp_biller_id = h.bp_biller_id  and  a.user_id = f.user_id  and g.partner_id = e.partner_id  and a.user_id = b.user_id  and a.order_no = $orderNo LIMIT 1";
			}
		}
		else if($profileid  == 51) {
			if($code =='PEB - Electricity Payment'){
		    		$query = "SELECT a.order_no, a.bp_trans_log_id1, a.bp_trans_log_id2, a.bp_trans_log_id3, a.service_feature_code, a.total_amount,a.request_amount, a.service_charge, a.partner_charge, a.other_charge,  a.mobile_no, g.reference_no, a.comments, a.create_time, a.create_time, a.update_time,g.payment_fee,g.agent_charge,stamp_charge,g.post_time,if(g.post_status='Y','Y - Yes',if(g.post_status='E','E-Error',if(g.post_status='O','O-others','-'))) as post_status, concat(b.user_name,' (',b.first_name,' - ', b.last_name,') ') as user,concat(h.bp_biller_id,' - ',h.bp_biller_name) as Biller Name, concat(i.bp_biller_group_id,' - ',i.bp_biller_group_name) as Biller Group,concat(e.partner_id,' - ',e.partner_name) as partner ,if(a.service_feature_code='CIN','Cash-In (Account)',if(a.service_feature_code='COU','Cash-Out (Account)',if(a.service_feature_code='MP0','Cash-Out (Card)','-'))) as transaction_type,  a.sender_name,if(a.status='I','I-Inprogress',if(a.status='S','S-Success',if(a.status='E','E-Error',if(a.status='T','T-Timeout',if(a.status='C','C-Cash In',if(a.status='I','I-Inprogress',if(a.status='V','V-Validate',if(a.status='S','S-Success',if(a.status='P','P-Payment Notify',if(a.status='O','O-others','-')))))))))) as status,a.approver_comments,a.account_no,a.account_name,a.bp_account_no,a.bp_account_name,a.bp_account_code,a.session___id FROM  kadick_bp_biller h,kadick_bp_biller_group i, user b, bank_master d, ams_partner e, agent_info f, bp_request a LEFT JOIN  bp_service_order g  on g.bp_service_order_no = a.order_no   WHERE  g.bp_biller_id=h.bp_biller_id and b.bp_biller_group_id  = i.bp_biller_group_id and a.user_id = f.user_id and f.agent_code = '".$_SESSION['party_code']."' and g.partner_id = e.partner_id and g.bank_id = d.bank_master_id and a.user_id = b.user_id and g.bp_service_order_no = a.order_no and a.order_no = $orderNo";
			}
		}
		else if($profileid  == 52) {
			$query = " SELECT a.bp_service_order_no, a.bp_trans_log_id, a.service_feature_code, a.total_amount,a.request_amount, a.ams_charge, a.partner_charge, a.other_charge, a.customer_name, a.mobile_no, a.auth_code, a.reference_no, a.comment, a.date_time, a.post_status, a.post_time, a.service_feature_config_id, concat(b.user_name,' (',b.first_name,' - ', b.last_name,') ') as user, d.name as bank, concat(e.partner_id,' - ',e.partner_name) as partner,a.approver_comments FROM bp_service_order a, user b, bank_master d, ams_partner e, agent_info f WHERE a.user_id = f.user_id and f.sub_agent = 'Y' and f.agent_code = '".$_SESSION['party_code']."' and a.partner_id = e.partner_id and a.bank_id = d.bank_master_id  a.user_id = b.user_id and a.bp_service_order_no  = $orderNo";
		}	
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("no"=>$row['order_no'],"transLogId1"=>$row['bp_trans_log_id1'],"transLogId2"=>$row['bp_trans_log_id2'],"transLogId3"=>$row['bp_trans_log_id3'], "code"=>$row['service_feature_code'],"Biller"=>$row['Biller'],"Product"=>$row['Product'],"account_no"=>$row['account_no'],"account_name"=>$row['account_name'],"bp_account_no"=>$row['bp_account_no'],"bp_account_name"=>$row['bp_account_name'],"bp_bank_code"=>$row['bp_bank_code'],"session_id"=>$row['session_id'],"toamount"=>$row['total_amount'],"rmount"=>$row['request_amount'],"user"=>$row['user'],"service_charge"=>$row['service_charge'],"amscharge"=>$row['ams_charge'],"parcharge"=>$row['partner_charge'],"ocharge"=>$row['other_charge'],"name"=>$row['customer_name'],"mobile"=>$row['mobile_no'],"auth"=>$row['auth_code'],"refNo"=>$row['reference_no'],"fincomment"=>$row['comments'],"dtime"=>$row['create_time'],"pstatus"=>$row['post_status'],"ptime"=>$row['post_time'],"update_time"=>$row['update_time'],"sconfid"=>$row['service_feature_config_id'],"user"=>$row['user'],"bank"=>$row['bank'],"partner"=>$row['partner'],"type"=>$row['transaction_type'],"sender_name"=>$row['sender_name'],"sts"=>$row['status'], "appcmt"=>$row['approver_comments'], "agentCode"=>$row['agent_code'],"scharge"=>$row['service_charge'],"bp_transaction_id"=>$row['bp_transaction_id'],"payment_fee"=>$row['payment_fee'],"agent_charge"=>$row['agent_charge'],"stamp_charge"=>$row['stamp_charge'],"create_time"=>$row['create_time']);         
		}
		echo json_encode($data);
	}
	else if($action == "viewcomm") {
		if($profileid == 1 || $profileid == 10 || $profileid == 24 || $profileid == 22 || $profileid == 20 || $profileid == 23 || $profileid == 26  || $profileid  == 50) {
			$query = " select 'Amount' as rate_factor, a.charge_value, b.user_name as service_charge_group_name, a.service_charge_party_name from bp_service_order_comm a, user b where a.user_id = b.user_id and a.bp_service_order_no =$orderNo";
		}
		else if($profileid  == 51) {
			$query = "select if(a.rate_factor = 'P','Percentage','Amount') as rate_factor,a.charge_value, concat(d.user_name,' [',e.agent_code,'] ') as service_charge_group_name,c.service_charge_party_name FROM service_charge_rate a, service_charge_group b, bp_service_order_comm c, user d, agent_info e WHERE e.user_id = d.user_id and e.agent_code = '".$_SESSION['party_code']."' and c.user_id = d.user_id and c.service_charge_rate_id  = a.service_charge_rate_id  and b.service_charge_group_id = a.service_charge_group_id  and c.bp_service_order_no = $orderNo";
		}
		else if($profileid  == 52) {
			$query = "select if(a.rate_factor = 'P','Percentage','Amount') as rate_factor,a.charge_value, concat(d.user_name,' [',e.agent_code,'] ') as service_charge_group_name,c.service_charge_party_name FROM service_charge_rate a, service_charge_group b, bp_service_order_comm c, user d, agent_info e WHERE e.user_id = d.user_id and e.sub_agent = 'Y' and e.agent_code = '".$_SESSION['party_code']."' and c.user_id = d.user_id and c.service_charge_rate_id  = a.service_charge_rate_id  and b.service_charge_group_id = a.service_charge_group_id  and c.bp_service_order_no = $orderNo";
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