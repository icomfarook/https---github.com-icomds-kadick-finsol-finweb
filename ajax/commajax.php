<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');	
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$profile_id = $_SESSION['profile_id'];	
	//$profile_id = 1;
	if($action == "findlist") {
		$partyCode = $data->partyCode;
		$creteria = $data->creteria;
		$query = "";
		if($profile_id != 1 ||  $profile_id != 10) {
			$topartyCode = $data->topartyCode;
			$partyType = $_SESSION['party_type'];
			if($creteria == "SP") {				
				$partyCode = $partyCode;
			}
			if($creteria == "TP") {
				$partyCode = $topartyCode;
			} 
			if($partyType == "C") {
				if($creteria == "SP") {
					$query = "SELECT champion_code as party_code,  concat(b.outlet_name,'(','Self',')') as party_name , login_name FROM champion_info a, application_info b WHERE a.application_id = b.application_id and a.champion_code = '$partyCode'";
				}
				if($creteria == "TP") {
					$query = "SELECT agent_code as party_code,  concat(b.outlet_name,'(','Self',')') as party_name , login_name FROM agent_info a, application_info b WHERE a.application_id = b.application_id and a.agent_code = '$partyCode' and a.parent_code ='".$_SESSION['party_code']."'";
				}
			}
			if($partyType == "P") {
				$query = "SELECT personal_code as party_code, concat(b.outlet_name,'(','Self',')') as party_name, login_name FROM personal_info a, application_info b WHERE a.application_id = b.application_id and a.personal_code = '$partyCode'";
			}
			if($partyType == "A") {
				$query = "SELECT agent_code as party_code, if(a.sub_agent='Y',concat(a.agent_name,'[',ifNULL((select agent_name FROM agent_info WHERE agent_code = a.parent_code),'self'),']'),concat(a.agent_name,'[',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code),'self'),']')) as party_name, login_name FROM agent_info a, application_info b WHERE a.application_id = b.application_id and a.agent_code = '$partyCode'";
				if($creteria == "TP") {
					$partyType = "S";
					$query .=" and sub_agent = 'Y'";
				}
			
			}
		}
		if($profile_id == 1 ||  $profile_id == 10) {
			$partyCode = $data->partyCode;
			$partyType = $data->partyType;			
			
			if($partyType == "C") {
				$query = "SELECT a.champion_code as party_code,  concat(b.outlet_name,'(','Self',')') as party_name, login_name FROM champion_info a, application_info b, champion_comm_wallet c  WHERE a.champion_code = c.champion_code and a.application_id = b.application_id and a.champion_code = '$partyCode'";
			}
			if($partyType == "P") {
				$query = "SELECT a.personal_code as party_code, concat(b.outlet_name,'(','Self',')') as party_name, login_name FROM personal_info a, application_info b, personal_comm_wallet c WHERE a.personal_code = c.personal_code and a.application_id = b.application_id and a.personal_code = '$partyCode'";
			} 
			if($partyType == "MA" || $partyType == "SA") {
				$query = "SELECT a.agent_code as party_code, b.outlet_name as party_name, login_name FROM agent_info a, application_info b, agent_comm_wallet c WHERE a.agent_code = c.agent_code and a.application_id = b.application_id and a.agent_code = '$partyCode'";
				if($partyType == "SA") {
					$query .=" and sub_agent = 'Y'";
				}
				$partyType = "A";
			}
			if($partyType == "MA") {
				$partyType = "A";
			}
			if($partyType == "SA") {
				$partyType = "S";
			}			
		}
		
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("code"=>$row['party_code'],"name"=>$row['party_name'],"lname"=>$row['login_name'],"ptype"=>$partyType);           
		}
		echo json_encode($data);
	}
	
	else if($action == "edit") {
		$code = $data->code;
		$type = $data->type;
		$creteria = $data->creteria;
		if($type == "A" ) {
			$query = "SELECT d.outlet_name, ifNull(c.parent_code,'Self') as pcode,  ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname,ifNull(b.block_date,' - ') as block_date,ifNull(b.credit_limit,0.00) as credit_limit,ifNull(b.daily_limit,0.00) as daily_limit, ifNull(b.advance_amount,0.00) as advance_amount,ifNull(b.available_balance,0.00) as available_balance, ifNull(b.current_balance,0.00) as current_balance, ifNull(b.minimum_balance,0.00) as minimum_balance,ifNull(b.previous_current_balance,0.00) as previous_current_balance,ifNull(b.uncleared_balance,0.00) as uncleared_balance,ifNull(b.last_tx_no,0.00) as last_tx_no, ifNull(b.last_tx_amount,0.00) as last_tx_amount,ifNull(b.last_tx_date,' - ') as last_tx_date, if(b.block_status = 'Y','Yes','No') as block_status, if(b.active = 'Y','Yes','No' ) as  active, a.agent_code as code , a.agent_name as name, a.login_name as lname, if(a.parent_type='C','Champion',if(a.parent_type= 'A','Agent',if(a.parent_type='S','Sub Agent',if(a.parent_type='P','Personal','')))) as ptype, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype, if(a.sub_agent = 'Y','Yes','No') as sub_agent,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = b.create_user) as create_user,b.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = b.update_user) as update_user,b.update_time, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = b.block_reason_id) as block_reason_id FROM agent_info a , agent_comm_wallet b, application_main c, application_info d WHERE c.application_id = d.application_id and a.agent_code = b.agent_code and a.application_id = c.application_id and a.agent_code = '".$code."'";
		}
		if($type == "S" ) {
			$query = "SELECT d.outlet_name, ifNull(c.parent_code,'Self') as pcode,  ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname,ifNull(b.block_date,' - ') as block_date,ifNull(b.credit_limit,0.00) as credit_limit,ifNull(b.daily_limit,0.00) as daily_limit, ifNull(b.advance_amount,0.00) as advance_amount,ifNull(b.available_balance,0.00) as available_balance, ifNull(b.current_balance,0.00) as current_balance, ifNull(b.minimum_balance,0.00) as minimum_balance,ifNull(b.previous_current_balance,0.00) as previous_current_balance,ifNull(b.uncleared_balance,0.00) as uncleared_balance,ifNull(b.last_tx_no,0.00) as last_tx_no, ifNull(b.last_tx_amount,0.00) as last_tx_amount,ifNull(b.last_tx_date,' - ') as last_tx_date, if(b.block_status = 'Y','Yes','No') as block_status, if(b.active = 'Y','Yes','No' ) as  active, a.agent_code as code , a.agent_name as name, a.login_name as lname, if(a.parent_type='C','Champion',if(a.parent_type= 'A','Agent',if(a.parent_type='S','Sub Agent',if(a.parent_type='P','Personal','')))) as ptype, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype, if(a.sub_agent = 'Y','Yes','No') as sub_agent,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = b.create_user) as create_user,b.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = b.update_user) as update_user,b.update_time, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = b.block_reason_id) as block_reason_id FROM agent_info a , agent_comm_wallet b, application_main c, application_info d WHERE a.sub_agent = 'Y' and c.application_id = d.application_id and a.agent_code = b.agent_code and a.application_id = c.application_id and a.agent_code = '".$code."'";
		}
		if($profile_id == 1 ||  $profile_id == 10) {
			if($type == "C") {
				if($creteria == "SP") {
					$query = "SELECT d.outlet_name, ifNull(c.parent_code,'Self') as pcode,  ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname,ifNull(b.block_date,' - ') as block_date, ifNull(b.credit_limit,0.00) as credit_limit,ifNull(b.daily_limit,0.00) as daily_limit, ifNull(b.advance_amount,0.00) as advance_amount,ifNull(b.available_balance,0.00) as available_balance, ifNull(b.current_balance,0.00) as current_balance, ifNull(b.minimum_balance,0.00) as minimum_balance,ifNull(b.previous_current_balance,0.00) as previous_current_balance,ifNull(b.uncleared_balance,0.00) as uncleared_balance,ifNull(b.last_tx_no,0.00) as last_tx_no, ifNull(b.last_tx_amount,0.00) as last_tx_amount,ifNull(b.last_tx_date,' - ') as last_tx_date, if(b.block_status = 'Y','Yes','No') as block_status, if(b.active = 'Y','Yes','No' ) as  active, a.champion_code as code , a.champion_name as name, a.login_name as lname, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = b.create_user) as create_user,b.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = b.update_user) as update_user,b.update_time, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = b.block_reason_id) as block_reason_id FROM champion_info a , champion_comm_wallet b, application_main c, application_info d WHERE c.application_id = d.application_id and a.champion_code = b.champion_code and a.application_id = c.application_id and a.champion_code = '".$code."'";
				}
				if($creteria == "TP") {
					$query = "SELECT d.outlet_name, ifNull(c.parent_code,'Self') as pcode,  ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname,ifNull(b.block_date,' - ') as block_date, ifNull(b.credit_limit,0.00) as credit_limit,ifNull(b.daily_limit,0.00) as daily_limit, ifNull(b.advance_amount,0.00) as advance_amount,ifNull(b.available_balance,0.00) as available_balance, ifNull(b.current_balance,0.00) as current_balance, ifNull(b.minimum_balance,0.00) as minimum_balance,ifNull(b.previous_current_balance,0.00) as previous_current_balance,ifNull(b.uncleared_balance,0.00) as uncleared_balance,ifNull(b.last_tx_no,0.00) as last_tx_no, ifNull(b.last_tx_amount,0.00) as last_tx_amount,ifNull(b.last_tx_date,' - ') as last_tx_date, if(b.block_status = 'Y','Yes','No') as block_status, if(b.active = 'Y','Yes','No' ) as  active, a.champion_code as code , a.champion_name as name, a.login_name as lname, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = b.create_user) as create_user,b.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = b.update_user) as update_user,b.update_time, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = b.block_reason_id) as block_reason_id FROM agent_info a , agent_comm_wallet b, application_main c, application_info d WHERE c.application_id = d.application_id and a.agent_code = b.agent_code and a.application_id = c.application_id and a.parent_code = '".$_SESSION['party_code']."' and a.agent_code = '".$code."'";
				}
			}
		}
		if($profile_id != 1 ||  $profile_id != 10) {
			if($type == "C") {
				$query = "SELECT d.outlet_name, ifNull(c.parent_code,'Self') as pcode,  ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname,ifNull(b.block_date,' - ') as block_date, ifNull(b.credit_limit,0.00) as credit_limit,ifNull(b.daily_limit,0.00) as daily_limit, ifNull(b.advance_amount,0.00) as advance_amount,ifNull(b.available_balance,0.00) as available_balance, ifNull(b.current_balance,0.00) as current_balance, ifNull(b.minimum_balance,0.00) as minimum_balance,ifNull(b.previous_current_balance,0.00) as previous_current_balance,ifNull(b.uncleared_balance,0.00) as uncleared_balance,ifNull(b.last_tx_no,0.00) as last_tx_no, ifNull(b.last_tx_amount,0.00) as last_tx_amount,ifNull(b.last_tx_date,' - ') as last_tx_date, if(b.block_status = 'Y','Yes','No') as block_status, if(b.active = 'Y','Yes','No' ) as  active, a.champion_code as code , a.champion_name as name, a.login_name as lname, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = b.create_user) as create_user,b.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = b.update_user) as update_user,b.update_time, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = b.block_reason_id) as block_reason_id FROM champion_info a , champion_comm_wallet b, application_main c, application_info d WHERE c.application_id = d.application_id and a.champion_code = b.champion_code and a.application_id = c.application_id and a.champion_code = '".$code."'";
			}
		}
		
		if($type == "P") {
			$query = "SELECT d.outlet_name, ifNull(c.parent_code,'Self') as pcode, ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname,ifNull(b.block_date,' - ') as block_date, ifNull(b.credit_limit,0.00) as credit_limit,ifNull(b.daily_limit,0.00) as daily_limit, ifNull(b.advance_amount,0.00) as advance_amount,ifNull(b.available_balance,0.00) as available_balance, ifNull(b.current_balance,0.00) as current_balance, ifNull(b.minimum_balance,0.00) as minimum_balance,ifNull(b.previous_current_balance,0.00) as previous_current_balance,ifNull(b.uncleared_balance,0.00) as uncleared_balance,ifNull(b.last_tx_no,0.00) as last_tx_no, ifNull(b.last_tx_amount,0.00) as last_tx_amount,ifNull(b.last_tx_date,' - ') as last_tx_date, if(b.block_status = 'Y','Yes','No') as block_status, if(b.active = 'Y','Yes','No' ) as  active, a.personal_code as code , a.personal_name as name, a.login_name as lname, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = b.create_user) as create_user,b.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = b.update_user) as update_user,b.update_time, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = b.block_reason_id) as block_reason_id FROM personal_info a , personal_comm_wallet b, application_main c, application_info d WHERE c.application_id = d.application_id and a.personal_code = b.personal_code and a.application_id = c.application_id and a.personal_code = '".$code."'";
		}
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_assoc($result)) {
			$data[] = array("outlet_name"=>$row['outlet_name'],"block_reason_id"=>$row['block_reason_id'],"update_time"=>$row['update_time'],"update_user"=>$row['update_user'],"create_time"=>$row['create_time'],"create_user"=>$row['create_user'],"parenroutletname"=>$row['parenroutletname'],"block_status"=>$row['block_status'],"block_date"=>$row['block_date'],"active"=>$row['active'],"last_tx_date"=>$row['last_tx_date'],"last_tx_amount"=>$row['last_tx_amount'],"last_tx_no"=>$row['last_tx_no'],"uncleared_balance"=>$row['uncleared_balance'],"previous_current_balance"=>$row['previous_current_balance'],"minimum_balance"=>$row['minimum_balance'],"current_balance"=>$row['current_balance'],"available_balance"=>$row['available_balance'],"advance_amount"=>$row['advance_amount'],"credit_limit"=>$row['credit_limit'],"daily_limit"=>$row['daily_limit'],"code"=>$row['code'],"name"=>$row['name'],"lname"=>$row['lname'],"pcode"=>$row['pcode'],"ptype"=>$row['ptype'],"atype"=>$row['partytype'],"sub_agent"=>$row['sub_agent']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}
	}
	
?>	