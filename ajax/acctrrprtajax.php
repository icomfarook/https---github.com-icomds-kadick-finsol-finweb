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
			$query = "SELECT c.acc_request_id, concat(c.service_feature_code, ' - ', d.feature_description) as service_feature_code, ifNULL(c.order_no,'-') as  order_no, c.create_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user, if(c.status='I','I-Inprogress',if(c.status='N','N-Name Enqiury',if(c.status='S','S-Success',if(c.status='E','E-Error',if(c.status='T','T-Timeout',if(c.status='G','G-Triggered',if(c.status='R','R-Request Cancel',if(c.status='X','X-Cancelled',if(c.status='O','O-Request Confirm','-'))))))))) as status,  ifNULL(c.account_number,'-') as rrn,c.bvn,c.account_balance FROM agent_info b, acc_request c, service_feature d WHERE c.user_id = b.user_id and c.service_feature_code = d.feature_code ";
		}
		if($profileid  == 51) {
			$query = "SELECT c.acc_request_id, concat(c.service_feature_code, ' - ', d.feature_description) as service_feature_code, c.order_no,  c.total_amount, c.create_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user, if(c.status='I','I-Inprogress',if(c.status='N','N-Name Enqiury',if(c.status='S','S-Success',if(c.status='E','E-Error',if(c.status='T','T-Timeout',if(c.status='G','G-Triggered',if(c.status='R','R-Request Cancel',if(c.status='X','X-Cancelled',if(c.status='O','O-Request Confirm','-'))))))))) as status,  ifNULL(c.account_number,'-') as rrn FROM agent_info b, acc_request c, service_feature d WHERE  c.user_id = b.user_id and c.service_feature_code = d.feature_code and b.agent_code = '".$_SESSION['party_code']."'";
		}
		if($profileid  == 52) {
			$query = "SELECT c.acc_request_id, concat(c.service_feature_code, ' - ', d.feature_description) as service_feature_code, c.order_no,  c.total_amount, c.create_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user, if(c.status='I','I-Inprogress',if(c.status='N','N-Name Enqiury',if(c.status='S','S-Success',if(c.status='E','E-Error',if(c.status='T','T-Timeout',if(c.status='G','G-Triggered',if(c.status='R','R-Request Cancel',if(c.status='X','X-Cancelled',if(c.status='O','O-Request Confirm','-'))))))))) as status, ifNULL(c.account_number,'-') as rrn FROM agent_info b, acc_request c, service_feature d WHERE  c.user_id = b.user_id and c.service_feature_code = d.feature_code and b.agent_code = '".$_SESSION['party_code']."' and b.sub_agent = 'Y'";
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
				}else{
					$query .= " and c.status = '$status' and date(c.create_time) >= '$startDate' and  date(c.create_time) <= '$endDate' order by c.create_time desc ";
							
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
			}else{
				$query .= " and c.status = '$status' and date(c.create_time) >= '$startDate' and  date(c.create_time) <= '$endDate' order by c.create_time desc ";	
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
			$data[] = array("id"=>$row['acc_request_id'],"code"=>$row['service_feature_code'],"no"=>$row['order_no'],"reqmount"=>$row['bvn'],"toamount"=>$row['account_balance'],"dtime"=>$row['create_time'],"user"=>$row['user'], "status"=>$row['status'], "rrn"=>$row['rrn']);           
		}
		echo json_encode($data);
	}
	else if($action == "view") {
		$orderNo	=  $data->orderNo;
		$id	=  $data->id;
		$code	=  $data->code;
		if($profileid == 1 || $profileid == 10 || $profileid == 24 || $profileid == 22 || $profileid == 20 || $profileid == 23 || $profileid == 26  || $profileid  == 50) {
			if($code =='BAO - Account Open' || $code == 'BWO - Wallet Open'  || $code == 'BCL - Card Link'){
		    		$query = "SELECT a.create_time, a.update_time, a.acc_request_id,ifNULL(a.account_number,'-') as account_number,ifNULL(a.bvn,'-') as bvn,  a.order_no,ifNULL(a.acc_trans_log_id,'-') as acc_trans_log_id,ifNULL(a.acc_trans_log_id2,'-') as acc_trans_log_id2, g.total_amount, g.ams_charge, g.partner_charge, g.other_charge, a.mobile, a.comments, a.update_time, concat(b.user_name,' (',b.first_name,' - ', b.last_name,') ') as user,concat(d.bank_master_id,' - ',d.name) as bank, concat(e.partner_id,' - ',e.partner_name) as partner ,if(a.service_feature_code='CIN','Cash-In (Account)',if(a.service_feature_code='COU','Cash-Out (Account)',if(a.service_feature_code='MP0','Cash-Out (Account)',if(a.service_feature_code='BAO','Account Open (Account)',if(a.service_feature_code='BWO','Wallet Open (Account)',if(a.service_feature_code='BCL','Card Link (Card)','-')))))) as code,  if(a.status='I','I-Inprogress',if(a.status='N','N-Name Enqiury',if(a.status='S','S-Success',if(a.status='E','E-Error',if(a.status='T','T-Timeout',if(a.status='G','G-Triggered',if(a.status='R','R-Request Cancel',if(a.status='X','X-Cancelled',if(a.status='O','O-Request Confirm','-'))))))))) as status ,a.account_balance,a.email,a.request_id,a.first_name,a.middle_name,a.last_name,a.gender,a.dob,a.house_no,a.street_name,a.city,a.country_id,(s.name) as state , (l.name) as local,(m.country_description) as country,g.post_status,g.post_time,g.service_feature_config_id,g.agent_charge,g.stamp_charge FROM  user b, bank_master d, ams_partner e,local_govt_list l,state_list s,country m, agent_info f,acc_request a LEFT JOIN  acc_service_order g  on g.acc_service_order_no = a.order_no  WHERE  a.country_id = m.country_id and  a.state_id=s.state_id and a.local_govt_id = l.local_govt_id and  a.user_id = f.user_id  and g.partner_id = e.partner_id and g.bank_id = d.bank_master_id and a.user_id = b.user_id  and a.acc_request_id = $orderNo LIMIT 1";
			}
		}
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("ctime"=>$row['create_time'],"utime"=>$row['update_time'],"account_number"=>$row['account_number'],"no"=>$row['order_no'],"acc_trans_log_id"=>$row['acc_trans_log_id'],"acc_trans_log_id2"=>$row['acc_trans_log_id2'], "code"=>$row['code'],"toamount"=>$row['total_amount'],"user"=>$row['user'],"ams_charge"=>$row['ams_charge'],"parcharge"=>$row['partner_charge'],"ocharge"=>$row['other_charge'],"name"=>$row['customer_name'],"mobile"=>$row['mobile'],"comments"=>$row['comments'],"ptime"=>$row['post_time'],"pstatus"=>$row['post_status'],"update_time"=>$row['update_time'],"sconfid"=>$row['service_feature_config_id'],"bank"=>$row['bank'],"partner"=>$row['partner'],"sts"=>$row['status'], "account_balance"=>$row['account_balance'],"email"=>$row['email'],"request_id"=>$row['request_id'],"first_name"=>$row['first_name'],"middle_name"=>$row['middle_name'],"last_name"=>$row['last_name'],"gender"=>$row['gender'],"dob"=>$row['dob'],"house_no"=>$row['house_no'],"street_name"=>$row['street_name'],"city"=>$row['city'],"state"=>$row['state'],"local"=>$row['local'],"country"=>$row['country'],"agent_charge"=>$row['agent_charge'],"stamp_charge"=>$row['stamp_charge'],"bvn"=>$row['bvn']);           
		}
		echo json_encode($data);
	}
	else if($action == "viewcomm") {
		if($profileid == 1 || $profileid == 10 || $profileid == 24 || $profileid == 22 || $profileid == 20 || $profileid == 23 || $profileid == 26  || $profileid  == 50) {
			$query = " select 'Amount' as rate_factor, a.charge_value, b.user_name as service_charge_group_name, a.service_charge_party_name from acc_service_order_comm a, user b where a.user_id = b.user_id and a.acc_service_order_no =$orderNo";
		}
		else if($profileid  == 51) {
			$query = "select if(a.rate_factor = 'P','Percentage','Amount') as rate_factor,a.charge_value, concat(d.user_name,' [',e.agent_code,'] ') as service_charge_group_name,c.service_charge_party_name FROM service_charge_rate a, service_charge_group b, acc_service_order_comm c, user d, agent_info e WHERE e.user_id = d.user_id and e.agent_code = '".$_SESSION['party_code']."' and c.user_id = d.user_id and c.service_charge_rate_id  = a.service_charge_rate_id  and b.service_charge_group_id = a.service_charge_group_id  and c.acc_service_order_no = $orderNo";
		}
		else if($profileid  == 52) {
			$query = "select if(a.rate_factor = 'P','Percentage','Amount') as rate_factor,a.charge_value, concat(d.user_name,' [',e.agent_code,'] ') as service_charge_group_name,c.service_charge_party_name FROM service_charge_rate a, service_charge_group b, acc_service_order_comm c, user d, agent_info e WHERE e.user_id = d.user_id and e.sub_agent = 'Y' and e.agent_code = '".$_SESSION['party_code']."' and c.user_id = d.user_id and c.service_charge_rate_id  = a.service_charge_rate_id  and b.service_charge_group_id = a.service_charge_group_id  and c.acc_service_order_no = $orderNo";
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