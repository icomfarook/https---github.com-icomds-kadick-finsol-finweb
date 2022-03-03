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
	$creteria 	= $data->creteria;
	$championCode 	= $data->championCode;
	$state 	= $data->state;	
	$local_govt_id 	= $data->local_govt_id;
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));	
	$profileid = $_SESSION['profile_id'];
	error_log($reportFor."|".$partycode);
	if($action == "getreport") {
	
		if($profileid == 1 || $profileid == 10 || $profileid == 20 || $profileid == 21 || $profileid == 22 || $profileid == 23 || $profileid == 24 || $profileid == 25 || $profileid == 26 ) {
			$query = "SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.bp_service_order_no, a.request_amount, a.total_amount, a.date_time as date_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user ,c.account_no  ,ifNULL(N.bp_payant_service_category_name,'-') name FROM bp_service_order a, agent_info b, bp_request c, service_feature d,bp_payant_service l, bp_payant_service_category N WHERE c.bp_biller_id = l.bp_payant_service_id and l.bp_payant_service_id = N.bp_payant_service_id and c.bp_product_id = N.bp_payant_service_category_id and  (a.service_feature_code ='PEB' OR  a.service_feature_code ='PED') and a.bp_service_order_no = c.order_no and c.status = 'S' and a.user_id = b.user_id and a.service_feature_code = d.feature_code and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' UNION ALL SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.bp_service_order_no, a.request_amount, a.total_amount, a.date_time as date_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user ,c.account_no ,ifNULL(l.bp_payant_service_category_name,'-') as  name FROM bp_service_order a, agent_info b, bp_request c, service_feature d,bp_payant_service_category l, bp_payant_service_product N where c.bp_biller_id = l.bp_payant_service_category_id and l.bp_payant_service_category_id = N.bp_payant_service_category_id and c.bp_product_id = N.bp_payant_service_product_id  and (a.service_feature_code ='PTV') and  a.bp_service_order_no = c.order_no and c.status = 'S' and a.user_id = b.user_id and a.service_feature_code = d.feature_code and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' UNION ALL SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.bp_service_order_no, a.request_amount, a.total_amount, a.date_time as date_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user ,c.account_no ,ifNULL(N.bp_opay_service_provider_name,'-') as name FROM bp_service_order a, agent_info b, bp_request c, service_feature d, bp_opay_service l, bp_opay_service_provider N WHERE c.bp_biller_id = l.bp_opay_service_id and c.bp_biller_id = N.bp_opay_service_id and c.bp_product_id = N.bp_opay_service_provider_id and  (a.service_feature_code ='PBT') and  a.bp_service_order_no = c.order_no and c.status = 'S' and a.user_id = b.user_id and a.service_feature_code = d.feature_code";
		}
		if($profileid  == 50) {
			if($reportFor == 'ALL'){
				$query = "SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.bp_service_order_no, a.request_amount, a.total_amount, a.date_time as date_time,  b.champion_name as user FROM bp_service_order a, champion_info b, bp_request c, service_feature d WHERE a.bp_service_order_no = c.order_no and c.status = 'S' and a.service_feature_code = d.feature_code and b.champion_code = '".$_SESSION['party_code']."' and a.user_id = b.user_id UNION SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.bp_service_order_no, a.request_amount, a.total_amount, a.date_time as date_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user FROM bp_service_order a, agent_info b, bp_request c, service_feature d WHERE a.bp_service_order_no = c.order_no and c.status = 'S' and a.service_feature_code = d.feature_code and b.parent_code = '".$_SESSION['party_code']."' and a.user_id = b.user_id ";
			}else if($reportFor == 'C'){
				$query = "SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.bp_service_order_no, a.request_amount, a.total_amount, a.date_time as date_time, b.champion_name as user FROM bp_service_order a, champion_info b, bp_request c, service_feature d WHERE a.bp_service_order_no = c.order_no and c.status = 'S' and a.service_feature_code = d.feature_code and b.champion_code = '".$_SESSION['party_code']."' and a.user_id = b.user_id ";
				
			}else{
				if($partycode == 'ALL'){
					$query = "SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.bp_service_order_no, a.request_amount, a.total_amount, a.date_time as date_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user FROM bp_service_order a, agent_info b, bp_request c, service_feature d WHERE a.bp_service_order_no = c.order_no and c.status = 'S' and a.service_feature_code = d.feature_code and b.parent_code = '".$_SESSION['party_code']."' and a.user_id = b.user_id ";
				}else{
					$query = "SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.bp_service_order_no, a.request_amount, a.total_amount, a.date_time as date_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user FROM bp_service_order a, agent_info b, bp_request c, service_feature d WHERE a.bp_service_order_no = c.order_no and c.status = 'S' and a.service_feature_code = d.feature_code and b.parent_code = '".$_SESSION['party_code']."' and a.user_id = b.user_id and  b.agent_code = '$partycode'";
				}
				
			}
			
		}
		if($profileid  == 52) {
			$query = "SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.bp_service_order_no, a.request_amount, a.total_amount, a.date_time as date_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user FROM bp_service_order a, agent_info b, bp_request c, service_feature d WHERE a.bp_service_order_no = c.order_no and c.status = 'S' and a.service_feature_code = d.feature_code and b.agent_code = '".$_SESSION['party_code']."' and b.sub_agent = 'Y' and a.user_id = b.user_id ";
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
			$query .= " and a.bp_service_order_no = $orderNo order by a.bp_service_order_no";
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
				$query .= " and b.state_id = '$state'  and b.local_govt_id='$local_govt_id' and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
			}
		}
		
		error_log("sales report query = ".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("code"=>$row['service_feature_code'],"no"=>$row['bp_service_order_no'],"reqmount"=>$row['request_amount'],"toamount"=>$row['total_amount'],"dtime"=>$row['date_time'],"user"=>$row['user'], "reference"=>$row['reference'],"account_no"=>$row['account_no'],"name"=>$row['name']);           
		}
		echo json_encode($data);
	}
	else if($action == "view") {
		$code	=  $data->code;
		 if($profileid == 1 || $profileid == 10 || $profileid == 24 || $profileid == 22 || $profileid == 20 || $profileid == 23 || $profileid == 26  || $profileid  == 50) {
			if($code =='PEB - Electricity Payment' || $code =='PED - Education Payment'){
		    		$query = "SELECT a.bp_service_order_no,ifNULL(g.bp_trans_log_id1,'-') as  bp_trans_log_id1 ,ifNULL(g.bp_trans_log_id2,'-') as  bp_trans_log_id2,ifNULL(g.bp_trans_log_id3,'-') as  bp_trans_log_id3,concat(f.agent_name,'-','[',f.agent_code,']')as Agent_code ,a.service_feature_code, (a.total_amount) as total_amount,(a.request_amount) as  request_amount, (a.ams_charge) as ams_charge, a.partner_charge, a.other_charge,  g.mobile_no,  g.session_id,IFNULL(g.comments,'-') as comments,ifNULL(g.approver_comments,'-') as approver_comments, a.date_time,   g.bp_transaction_id,g.payment_fee,a.agent_charge,a.stamp_charge,if(a.post_status='Y','Y - Yes',if(a.post_status='E','E-Error',if(a.post_status='O','O-others','-'))) as post_status,a.post_time, concat(b.user_name,' (',b.first_name,' - ', b.last_name,') ') as user, concat(e.partner_id,' - ',e.partner_name) as partner ,concat(z.bp_biller_id,' - ',z.bp_biller_name) as Biller , ifNUll(concat(i.bp_product_id,' - ',i.bp_product_name),'-') as  Product, if(g.status='I','I-Inprogress',if(g.status='S','SUCCESS',if(g.status='E','E-Error',if(g.status='T','T-Timeout',if(g.status='C','C-Cash In',if(g.status='I','I-Inprogress',if(g.status='V','V-Validate',if(g.status='P','P-Payment Notify',if(g.status='O','O-others','-'))))))))) as status,g.account_no,ifNULL(g.account_name,'-') as account_name,g.bp_account_no,g.bp_account_name,IFNULL(g.bp_bank_code,'-') as bp_bank_code,g.create_time, (a.ams_charge + a.partner_charge + a.agent_charge) as service_charge,ifNULL(j.bp_payant_service_category_name,'-') as date_time1  ,ifNULL(N.bp_payant_service_category_name,'-') name FROM  kadick_bp_biller z,  user b,  ams_partner e, agent_info f,bp_request g LEFT JOIN bp_payant_service_category j on  g.bp_product_id = j.bp_payant_service_category_id, champion_info h,bp_biller_product i RIGHT JOIN  bp_service_order a on  a.bp_product_id = i.bp_product_id,bp_opay_service m LEFT JOIN bp_request o on  m.bp_opay_service_id = o.bp_biller_id , bp_payant_service l, bp_payant_service_category N WHERE g.bp_biller_id = l.bp_payant_service_id and l.bp_payant_service_id = N.bp_payant_service_id and g.bp_product_id = N.bp_payant_service_category_id and  IF(b.user_type='C',a.user_id = h.user_id, a.user_id = f.user_id)  and a.partner_id = e.partner_id and  a.user_id = b.user_id and a.bp_service_order_no = g.order_no  and a.bp_service_order_no = $orderNo LIMIT 1";
			}
			if($code =='PTV - Cable TV Payment'){
				$query = "SELECT a.bp_service_order_no,ifNULL(g.bp_trans_log_id1,'-') as  bp_trans_log_id1 ,ifNULL(g.bp_trans_log_id2,'-') as  bp_trans_log_id2,ifNULL(g.bp_trans_log_id3,'-') as  bp_trans_log_id3,concat(f.agent_name,'-','[',f.agent_code,']')as Agent_code ,a.service_feature_code, (a.total_amount) as total_amount,(a.request_amount) as  request_amount, (a.ams_charge) as ams_charge, a.partner_charge, a.other_charge,  g.mobile_no,  g.session_id,IFNULL(g.comments,'-') as comments,ifNULL(g.approver_comments,'-') as approver_comments, a.date_time,   g.bp_transaction_id,g.payment_fee,a.agent_charge,a.stamp_charge,if(a.post_status='Y','Y - Yes',if(a.post_status='E','E-Error',if(a.post_status='O','O-others','-'))) as post_status,a.post_time, concat(b.user_name,' (',b.first_name,' - ', b.last_name,') ') as user, concat(e.partner_id,' - ',e.partner_name) as partner ,concat(z.bp_biller_id,' - ',z.bp_biller_name) as Biller , ifNUll(concat(i.bp_product_id,' - ',i.bp_product_name),'-') as  Product, if(g.status='I','I-Inprogress',if(g.status='S','SUCCESS',if(g.status='E','E-Error',if(g.status='T','T-Timeout',if(g.status='C','C-Cash In',if(g.status='I','I-Inprogress',if(g.status='V','V-Validate',if(g.status='P','P-Payment Notify',if(g.status='O','O-others','-'))))))))) as status,g.account_no,ifNULL(g.account_name,'-') as account_name,g.bp_account_no,g.bp_account_name,IFNULL(g.bp_bank_code,'-') as bp_bank_code,g.create_time, (a.ams_charge + a.partner_charge + a.agent_charge) as service_charge,ifNULL(j.bp_payant_service_category_name,'-') as date_time1,m.bp_opay_service_name , ifNULL(l.bp_payant_service_category_name,'-') as  name FROM  kadick_bp_biller z,  user b,  ams_partner e, agent_info f,bp_request g LEFT JOIN bp_payant_service_category j on  g.bp_product_id = j.bp_payant_service_category_id, champion_info h,bp_biller_product i RIGHT JOIN  bp_service_order a on  a.bp_product_id = i.bp_product_id,bp_opay_service m LEFT JOIN bp_request o on  m.bp_opay_service_id = o.bp_biller_id ,bp_payant_service_category l, bp_payant_service_product N where g.bp_biller_id = l.bp_payant_service_category_id and l.bp_payant_service_category_id = N.bp_payant_service_category_id and g.bp_product_id = N.bp_payant_service_product_id and IF(b.user_type='C',a.user_id = h.user_id, a.user_id = f.user_id)  and a.partner_id = e.partner_id and  a.user_id = b.user_id and a.bp_service_order_no = g.order_no and a.bp_service_order_no = $orderNo LIMIT 1";
			}
			if($code =='PBT - Betting'){
				$query = "SELECT a.bp_service_order_no,ifNULL(g.bp_trans_log_id1,'-') as  bp_trans_log_id1 ,ifNULL(g.bp_trans_log_id2,'-') as  bp_trans_log_id2,ifNULL(g.bp_trans_log_id3,'-') as  bp_trans_log_id3,concat(f.agent_name,'-','[',f.agent_code,']')as Agent_code ,a.service_feature_code, (a.total_amount) as total_amount,(a.request_amount) as  request_amount, (a.ams_charge) as ams_charge, a.partner_charge, a.other_charge,  g.mobile_no,  g.session_id,IFNULL(g.comments,'-') as comments,ifNULL(g.approver_comments,'-') as approver_comments, a.date_time,   g.bp_transaction_id,g.payment_fee,a.agent_charge,a.stamp_charge,if(a.post_status='Y','Y - Yes',if(a.post_status='E','E-Error',if(a.post_status='O','O-others','-'))) as post_status,a.post_time, concat(b.user_name,' (',b.first_name,' - ', b.last_name,') ') as user, concat(e.partner_id,' - ',e.partner_name) as partner ,concat(z.bp_biller_id,' - ',z.bp_biller_name) as Biller , ifNUll(concat(i.bp_product_id,' - ',i.bp_product_name),'-') as  Product, if(g.status='I','I-Inprogress',if(g.status='S','SUCCESS',if(g.status='E','E-Error',if(g.status='T','T-Timeout',if(g.status='C','C-Cash In',if(g.status='I','I-Inprogress',if(g.status='V','V-Validate',if(g.status='P','P-Payment Notify',if(g.status='O','O-others','-'))))))))) as status,g.account_no,ifNULL(g.account_name,'-') as account_name,g.bp_account_no,g.bp_account_name,IFNULL(g.bp_bank_code,'-') as bp_bank_code,g.create_time, (a.ams_charge + a.partner_charge + a.agent_charge) as service_charge,ifNULL(j.bp_payant_service_category_name,'-') as date_time1,m.bp_opay_service_name ,ifNULL(N.bp_opay_service_provider_name,'-') as name FROM  kadick_bp_biller z,  user b,  ams_partner e, agent_info f,bp_request g LEFT JOIN bp_payant_service_category j on  g.bp_product_id = j.bp_payant_service_category_id, champion_info h,bp_biller_product i RIGHT JOIN  bp_service_order a on  a.bp_product_id = i.bp_product_id,bp_opay_service m LEFT JOIN bp_request o on  m.bp_opay_service_id = o.bp_biller_id , bp_opay_service l, bp_opay_service_provider N WHERE g.bp_biller_id = l.bp_opay_service_id and g.bp_biller_id = N.bp_opay_service_id and g.bp_product_id = N.bp_opay_service_provider_id and IF(b.user_type='C',a.user_id = h.user_id, a.user_id = f.user_id)  and a.partner_id = e.partner_id and  a.user_id = b.user_id and a.bp_service_order_no = g.order_no  and a.bp_service_order_no = $orderNo LIMIT 1";
			}
		}
		else if($profileid  == 51) {
			if($code =='PEB - Electricity Payment'){
		    		$query = " SELECT a.bp_service_order_no, g.bp_trans_log_id1,g.bp_trans_log_id2,g.bp_trans_log_id3, a.service_feature_code, a.total_amount,a.request_amount, a.ams_charge, a.partner_charge, a.other_charge,  g.mobile_no,  g.session_id,	g.comments,g.approver_comments, a.date_time,   g.bp_transaction_id,g.payment_fee,a.agent_charge,a.stamp_charge, as post_status,a.post_time, concat(b.user_name,' (',b.first_name,' - ', b.last_name,') ') as user, concat(e.partner_id,' - ',e.partner_name) as partner ,concat(z.bp_biller_id,' - ',z.bp_biller_name) as Biller , ifNUll(concat(i.bp_product_id,' - ',i.bp_product_name),'-') as  Product, if(g.status='I','I-Inprogress',if(g.status='S','SUCCESS',if(g.status='E','E-Error',if(g.status='T','T-Timeout',if(g.status='C','C-Cash In',if(g.status='I','I-Inprogress',if(g.status='V','V-Validate',if(g.status='P','P-Payment Notify',if(g.status='O','O-others','-'))))))))) as status,g.account_no,g.account_name,g.bp_account_no,g.bp_account_name,g.bp_bank_code,g.create_time FROM kadick_bp_biller z,  user b,  ams_partner e, agent_info f, bp_request g,champion_info h,bp_biller_product i RIGHT JOIN  bp_service_order a on  a.bp_product_id = i.bp_product_id WHERE a.user_id = f.user_id and f.agent_code = '".$_SESSION['party_code']."' and a.partner_id = e.partner_id and a.bank_id = d.bank_master_id and a.user_id = b.user_id and a.bp_service_order_no = g.order_no and a.bp_service_order_no = $orderNo";
			}
		}
		else if($profileid  == 52) {
			$query = "SELECT a.bp_service_order_no, a.bp_trans_log_id, a.service_feature_code, a.total_amount,a.request_amount, a.ams_charge, a.partner_charge, a.other_charge, a.customer_name, a.mobile_no, a.auth_code, a.reference_no, a.comments, a.date_time, if(g.post_status='Y','Y - Yes',if(g.post_status='E','E-Error',if(g.post_status='O','O-others','-'))) as post_status, a.post_time, a.service_feature_config_id, concat(b.user_name,' (',b.first_name,' - ', b.last_name,') ') as user, d.name as bank, concat(e.partner_id,' - ',e.partner_name) as partner,f.agent_code FROM bp_service_order a, user b, bank_master d, ams_partner e, agent_info f WHERE a.user_id = f.user_id and f.sub_agent = 'Y' and f.agent_code = '".$_SESSION['party_code']."' and a.partner_id = e.partner_id and a.bank_id = d.bank_master_id  a.user_id = b.user_id and a.bp_service_order_no  = $orderNo";
		}	
		error_log("sales report query = ".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("no"=>$row['bp_service_order_no'],"date_time1"=>$row['date_time1'],"transLogId1"=>$row['bp_trans_log_id1'],"transLogId2"=>$row['bp_trans_log_id2'],"transLogId3"=>$row['bp_trans_log_id3'],"code"=>$row['service_feature_code'],"toamount"=>$row['total_amount'],"Biller"=>$row['Biller'],"Product"=>$row['Product'],"account_no"=>$row['account_no'],"account_name"=>$row['account_name'],"bp_account_no"=>$row['bp_account_no'],"bp_account_name"=>$row['bp_account_name'],"bp_bank_code"=>$row['bp_bank_code'],"session_id"=>$row['session_id'],"rmount"=>$row['request_amount'],"user"=>$row['user'],"amscharge"=>$row['ams_charge'],"parcharge"=>$row['partner_charge'],"ocharge"=>$row['other_charge'],"name"=>$row['customer_name'],"mobile"=>$row['mobile_no'],"auth"=>$row['auth_code'],"refNo"=>$row['reference_no'],"comments"=>$row['comments'],"dtime"=>$row['date_time'],"pstatus"=>$row['post_status'],"ptime"=>$row['post_time'],"sconfid"=>$row['service_feature_config_id'],"user"=>$row['user'],"bank"=>$row['bank'],"partner"=>$row['partner'],"type"=>$row['transaction_type'],"sender_name"=>$row['sender_name'],"sts"=>$row['status'], "appcmt"=>$row['approver_comments'], "agentCode"=>$row['agent_code'],"scharge"=>$row['service_charge'],"bp_transaction_id"=>$row['bp_transaction_id'],"payment_fee"=>$row['payment_fee'],"agent_charge"=>$row['agent_charge'],"stamp_charge"=>$row['stamp_charge'],"create_time"=>$row['create_time'],"Agent_code"=>$row['Agent_code'],"bp_opay_service_name"=>$row['bp_opay_service_name'],"sub_product"=>$row['name']);             
		}
		echo json_encode($data);
	}
	else if($action == "viewcomm") {
		 if($profileid == 1 || $profileid == 10 || $profileid == 24 || $profileid == 22 || $profileid == 20 || $profileid == 23 || $profileid == 26  || $profileid  == 50) {
			$query = "select if(a.rate_factor = 'P','Percentage','Amount') as rate_factor, a.rate_value, concat(d.user_name) as service_charge_group_name, c.service_charge_party_name FROM service_charge_rate a, service_charge_group b, bp_service_order_comm c, user d WHERE c.user_id = d.user_id and c.service_charge_rate_id = a.service_charge_rate_id  and b.service_charge_group_id = a.service_charge_group_id  and c.bp_service_order_no = $orderNo";
		}
		else if($profileid  == 51) {
			$query = "select if(a.rate_factor = 'P','Percentage','Amount') as rate_factor,a.rate_value, concat(d.user_name,' [',e.agent_code,'] ') as service_charge_group_name,c.service_charge_party_name FROM service_charge_rate a, service_charge_group b, bp_service_order_comm c, user d, agent_info e WHERE e.user_id = d.user_id and e.agent_code = '".$_SESSION['party_code']."' and c.user_id = d.user_id and c.service_charge_rate_id  = a.service_charge_rate_id  and b.service_charge_group_id = a.service_charge_group_id  and c.bp_service_order_no = $orderNo";
		}
		else if($profileid  == 52) {
			$query = "select if(a.rate_factor = 'P','Percentage','Amount') as rate_factor,a.rate_value, concat(d.user_name,' [',e.agent_code,'] ') as service_charge_group_name,c.service_charge_party_name FROM service_charge_rate a, service_charge_group b, bp_service_order_comm c, user d, agent_info e WHERE e.user_id = d.user_id and e.sub_agent = 'Y' and e.agent_code = '".$_SESSION['party_code']."' and c.user_id = d.user_id and c.service_charge_rate_id  = a.service_charge_rate_id  and b.service_charge_group_id = a.service_charge_group_id  and c.bp_service_order_no = $orderNo";
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