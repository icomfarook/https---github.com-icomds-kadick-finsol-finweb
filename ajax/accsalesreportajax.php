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
	$local_govt_id 	= $data->local_govt_id;
	$creteria 	= $data->creteria;
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));	
	$profileid = $_SESSION['profile_id'];
	$sesion_party_code = $_SESSION['party_code'];

	error_log($reportFor."|".$partycode);
	if($action == "getreport") {
	
		if($profileid == 1 || $profileid == 10 || $profileid == 20 || $profileid == 21 || $profileid == 22 || $profileid == 23 || $profileid == 24 || $profileid == 25 || $profileid == 26 ) {
			$query = "SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.acc_service_order_no, a.total_amount, a.date_time as date_time, concat(f.name) as bank,IF(a.service_feature_code='BAO','Account Open', IF(a.service_feature_code='BWL','Wallet Open', IF(a.service_feature_code='BCL','Card Link','-'))) as reference, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user FROM acc_service_order a, agent_info b, acc_request c, service_feature d,user_pos e,bank_master f WHERE a.acc_service_order_no = c.order_no and c.status = 'S' and a.user_id = b.user_id and a.service_feature_code = d.feature_code and a.user_id = e.user_id and a.bank_id = f.bank_master_id";
		}
		if($profileid  == 50) {
			if($reportFor == 'ALL'){
				$query = "SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.acc_service_order_no, a.total_amount, a.date_time as date_time, concat(f.name) as bank,IF(a.service_feature_code='BAO','Account Open', IF(a.service_feature_code='BWL','Wallet Open', IF(a.service_feature_code='BCL','Card Link','-'))) as reference, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user FROM acc_service_order a, champion_info b, acc_request c, service_feature d WHERE a.acc_service_order_no = c.order_no and c.status = 'S' and a.service_feature_code = d.feature_code and b.champion_code = '".$_SESSION['party_code']."' and a.user_id = b.user_id 
				UNION
				SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.acc_service_order_no, a.total_amount, a.date_time as date_time, concat(f.name) as bank,IF(a.service_feature_code='BAO','Account Open', IF(a.service_feature_code='BWL','Wallet Open', IF(a.service_feature_code='BCL','Card Link','-'))) as reference, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user FROM acc_service_order a, agent_info b, acc_request c, service_feature d WHERE a.acc_service_order_no = c.order_no and c.status = 'S' and a.service_feature_code = d.feature_code and b.parent_code = '".$_SESSION['party_code']."' and a.user_id = b.user_id ";
			}else if($reportFor == 'C'){
				$query = "SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.acc_service_order_no, a.request_amount, a.total_amount, a.date_time as date_time, IF(a.service_feature_code='CIN',a.auth_code , IF(a.service_feature_code='COU',a.auth_code, IF(a.service_feature_code='MP0',c.rrn,''))) as reference, b.champion_name as user FROM acc_service_order a, champion_info b, acc_request c, service_feature d WHERE a.acc_service_order_no = c.order_no and c.status = 'S' and a.service_feature_code = d.feature_code and b.champion_code = '".$_SESSION['party_code']."' and a.user_id = b.user_id ";
				
			}else{
				if($partycode == 'ALL'){
					$query = "SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.acc_service_order_no, a.total_amount, a.date_time as date_time, concat(f.name) as bank,IF(a.service_feature_code='BAO','Account Open', IF(a.service_feature_code='BWL','Wallet Open', IF(a.service_feature_code='BCL','Card Link','-'))) as reference, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user FROM acc_service_order a, agent_info b, acc_request c, service_feature d WHERE a.acc_service_order_no = c.order_no and c.status = 'S' and a.service_feature_code = d.feature_code and b.parent_code = '".$_SESSION['party_code']."' and a.user_id = b.user_id ";
				}else{
					$query = "SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.acc_service_order_no, a.total_amount, a.date_time as date_time, concat(f.name) as bank,IF(a.service_feature_code='BAO','Account Open', IF(a.service_feature_code='BWL','Wallet Open', IF(a.service_feature_code='BCL','Card Link','-'))) as reference, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user FROM acc_service_order a, agent_info b, acc_request c, service_feature d WHERE a.acc_service_order_no = c.order_no and c.status = 'S' and a.service_feature_code = d.feature_code and b.parent_code = '".$_SESSION['party_code']."' and a.user_id = b.user_id and  b.agent_code = '$partycode'";
				}
				
			}
			
		}
		if($profileid  == 51) {
			$query = "SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.acc_service_order_no, a.total_amount, a.date_time as date_time, concat(f.name) as bank,IF(a.service_feature_code='BAO','Account Open', IF(a.service_feature_code='BWL','Wallet Open', IF(a.service_feature_code='BCL','Card Link','-'))) as reference, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user FROM acc_service_order a, agent_info b, acc_request c, service_feature d,user_pos e,bank_master f WHERE a.acc_service_order_no = c.order_no and c.status = 'S' and a.user_id = b.user_id and a.service_feature_code = d.feature_code and a.user_id = e.user_id and a.bank_id = f.bank_master_id  and b.agent_code='$sesion_party_code'";
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
			$query .= " and a.acc_service_order_no = $orderNo order by a.acc_service_order_no";
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
			 if($local_govt_id ==""){
					$query .= " and b.state_id = '$state'  and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
						
				}
				else{
					$query .= " and b.state_id = '$state' and b.local_govt_id='$local_govt_id' and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
				}
			}
		}
			
		error_log("Account sales report query = ".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("code"=>$row['service_feature_code'],"no"=>$row['acc_service_order_no'],"reqmount"=>$row['request_amount'],"toamount"=>$row['total_amount'],"dtime"=>$row['date_time'],"user"=>$row['user'],"bank"=>$row['bank'], "reference"=>$row['reference']);           
		}
		echo json_encode($data);
	}
	else if($action == "view") {
		$code	=  $data->code;
		 if($profileid == 1 || $profileid == 10 || $profileid == 24 || $profileid == 22 || $profileid == 20 || $profileid == 23 || $profileid == 26  || $profileid  == 50) {
			if($code =='BAO - Account Open' || $code == 'BWO - Wallet Open'  || $code == 'BCL - Card Link'){
		    		$query = "SELECT a.acc_service_order_no, g.create_time,g.update_time,ifNULL(g.account_number,'-') as account_number,ifNULL(g.bvn,'-') as bvn,  a.acc_service_order_no,ifNULL(g.acc_trans_log_id,'-') as acc_trans_log_id,ifNULL(g.acc_trans_log_id2,'-') as acc_trans_log_id2, a.service_feature_code, g.account_balance,a.total_amount,a.ams_charge, a.partner_charge, a.other_charge, g.mobile,ifNULL(g.comments,'-') as comments, a.date_time, a.service_feature_config_id, concat(b.user_name,' (',b.first_name,' - ', b.last_name,') ') as user,concat(d.name) as bank, concat(e.partner_id,' - ',e.partner_name) as partner ,if(g.service_feature_code='CIN','Cash-In ',if(g.service_feature_code='COU','Cash-Out ',if(g.service_feature_code='MP0','Cash-Out ',if(g.service_feature_code='BAO','Account Open ',if(g.service_feature_code='BWO','Wallet Open ',if(g.service_feature_code='BCL','Card Link (Card)','-')))))) as transaction_type,if(g.status='S','SUCCESS',if(g.status='G','TRIGGERED','-')) as status,g.account_balance,g.email,g.request_id,concat(ifnull(g.first_name,''), ' ', ifnull(g.last_name,'')) as name,  ifNULL(g.middle_name,'-') as middle_name,g.gender,g.dob,g.house_no,g.street_name,g.city,g.country_id,(s.name) as state , (l.name) as local,(m.country_description) as country,a.post_status,a.post_time,a.agent_charge,a.stamp_charge ,concat(f.agent_name,'-','[',f.agent_code,']')as Agent_code,g.first_name,g.last_name FROM acc_service_order a, user b, bank_master d, ams_partner e, agent_info f, acc_request g,champion_info h,local_govt_list l,state_list s,country m WHERE IF(b.user_type='C',a.user_id = h.user_id, a.user_id = f.user_id) and g.country_id = m.country_id and  g.state_id=s.state_id and g.local_govt_id = l.local_govt_id and a.partner_id = e.partner_id and a.bank_id = d.bank_master_id and a.user_id = b.user_id and a.acc_service_order_no = g.order_no and a.acc_service_order_no = $orderNo LIMIT 1";
			}
		}
		else if($profileid  == 51) {
			if($code =='BAO - Account Open' || $code == 'BWO - Wallet Open'  || $code == 'BCL - Card Link'){
				$query = "SELECT a.acc_service_order_no, g.create_time,g.update_time,ifNULL(g.account_number,'-') as account_number,ifNULL(g.bvn,'-') as bvn,  a.acc_service_order_no,ifNULL(g.acc_trans_log_id,'-') as acc_trans_log_id,ifNULL(g.acc_trans_log_id2,'-') as acc_trans_log_id2, a.service_feature_code, g.account_balance,a.total_amount,a.ams_charge, a.partner_charge, a.other_charge, g.mobile,ifNULL(g.comments,'-') as comments, a.date_time, a.service_feature_config_id, concat(b.user_name,' (',b.first_name,' - ', b.last_name,') ') as user,concat(d.name) as bank, concat(e.partner_id,' - ',e.partner_name) as partner ,if(g.service_feature_code='CIN','Cash-In ',if(g.service_feature_code='COU','Cash-Out ',if(g.service_feature_code='MP0','Cash-Out ',if(g.service_feature_code='BAO','Account Open ',if(g.service_feature_code='BWO','Wallet Open ',if(g.service_feature_code='BCL','Card Link (Card)','-')))))) as transaction_type,if(g.status='S','SUCCESS',if(g.status='G','TRIGGERED','-')) as status,g.account_balance,g.email,g.request_id,concat(ifnull(g.first_name,''), ' ', ifnull(g.last_name,'')) as name,  ifNULL(g.middle_name,'-') as middle_name,g.gender,g.dob,g.house_no,g.street_name,g.city,g.country_id,(s.name) as state , (l.name) as local,(m.country_description) as country,a.post_status,a.post_time,a.agent_charge,a.stamp_charge ,concat(f.agent_name,'-','[',f.agent_code,']')as Agent_code,g.first_name,g.last_name FROM acc_service_order a, user b, bank_master d, ams_partner e, agent_info f, acc_request g,champion_info h,local_govt_list l,state_list s,country m WHERE IF(b.user_type='C',a.user_id = h.user_id, a.user_id = f.user_id) and g.country_id = m.country_id and  g.state_id=s.state_id and g.local_govt_id = l.local_govt_id and a.partner_id = e.partner_id and a.bank_id = d.bank_master_id and a.user_id = b.user_id and a.acc_service_order_no = g.order_no and f.agent_code='$sesion_party_code'  and a.acc_service_order_no = $orderNo LIMIT 1";
		}
		}
		else if($profileid  == 52) {
			$query = " SELECT a.acc_service_order_no, a.acc_trans_log_id, a.service_feature_code, a.total_amount,a.request_amount, a.ams_charge, a.partner_charge, a.other_charge, a.customer_name, a.mobile_no, a.auth_code, a.reference_no, a.comment, a.date_time, a.post_status, a.post_time, a.service_feature_config_id, concat(b.user_name,' (',b.first_name,' - ', b.last_name,') ') as user, d.name as bank, concat(e.partner_id,' - ',e.partner_name) as partner,f.agent_code FROM acc_service_order a, user b, bank_master d, ams_partner e, agent_info f WHERE a.user_id = f.user_id and f.sub_agent = 'Y' and f.agent_code = '".$_SESSION['party_code']."' and a.partner_id = e.partner_id and a.bank_id = d.bank_master_id  a.user_id = b.user_id and a.acc_service_order_no  = $orderNo";
		}	
		error_log("sales report query = ".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("ctime"=>$row['create_time'],"utime"=>$row['update_time'],"date_time"=>$row['date_time'],"account_number"=>$row['account_number'],"no"=>$row['acc_service_order_no'],"acc_trans_log_id"=>$row['acc_trans_log_id'],"acc_trans_log_id2"=>$row['acc_trans_log_id2'], "type"=>$row['transaction_type'],"code"=>$row['service_feature_code'],"toamount"=>$row['total_amount'],"user"=>$row['user'],"ams_charge"=>$row['ams_charge'],"parcharge"=>$row['partner_charge'],"ocharge"=>$row['other_charge'],"name"=>$row['name'],"mobile"=>$row['mobile'],"comments"=>$row['comments'],"ptime"=>$row['post_time'],"pstatus"=>$row['post_status'],"update_time"=>$row['update_time'],"sconfid"=>$row['service_feature_config_id'],"bank"=>$row['bank'],"partner"=>$row['partner'],"sts"=>$row['status'], "account_balance"=>$row['account_balance'],"email"=>$row['email'],"request_id"=>$row['request_id'],"first_name"=>$row['first_name'],"middle_name"=>$row['middle_name'],"last_name"=>$row['last_name'],"gender"=>$row['gender'],"dob"=>$row['dob'],"house_no"=>$row['house_no'],"street_name"=>$row['street_name'],"city"=>$row['city'],"state"=>$row['state'],"local"=>$row['local'],"country"=>$row['country'],"agent_charge"=>$row['agent_charge'],"stamp_charge"=>$row['stamp_charge'],"bvn"=>$row['bvn'],"Agent_code"=>$row['Agent_code']); 
			
		}
		echo json_encode($data);
	}
	else if($action == "viewcomm") {
		 if($profileid == 1 || $profileid == 10 || $profileid == 24 || $profileid == 22 || $profileid == 20 || $profileid == 23 || $profileid == 26  || $profileid  == 50) {
			$query = "select if(a.rate_factor = 'P','Percentage','Amount') as rate_factor, a.rate_value, concat(d.user_name) as service_charge_group_name, c.service_charge_party_name FROM service_charge_rate a, service_charge_group b, acc_service_order_comm c, user d WHERE c.user_id = d.user_id and c.service_charge_rate_id = a.service_charge_rate_id  and b.service_charge_group_id = a.service_charge_group_id  and c.acc_service_order_no = $orderNo";
		}
		else if($profileid  == 51) {
			$query = "select if(a.rate_factor = 'P','Percentage','Amount') as rate_factor, a.rate_value, concat(d.user_name) as service_charge_group_name, c.service_charge_party_name FROM service_charge_rate a, service_charge_group b, acc_service_order_comm c, user d,agent_info e WHERE d.user_id = e.user_id and  c.user_id = d.user_id and c.service_charge_rate_id = a.service_charge_rate_id  and b.service_charge_group_id = a.service_charge_group_id and e.agent_code='$sesion_party_code'  and c.acc_service_order_no = $orderNo";
		}
		else if($profileid  == 52) {
			$query = "select if(a.rate_factor = 'P','Percentage','Amount') as rate_factor,a.rate_value, concat(d.user_name,' [',e.agent_code,'] ') as service_charge_group_name,c.service_charge_party_name FROM service_charge_rate a, service_charge_group b, acc_service_order_comm c, user d, agent_info e WHERE e.user_id = d.user_id and e.sub_agent = 'Y' and e.agent_code = '".$_SESSION['party_code']."' and c.user_id = d.user_id and c.service_charge_rate_id  = a.service_charge_rate_id  and b.service_charge_group_id = a.service_charge_group_id  and c.acc_service_order_no = $orderNo";
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