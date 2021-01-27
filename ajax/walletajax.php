<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');	
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$profile_id = $_SESSION['profile_id'];	

	if($action == "findlist") {
		$partyCode = $data->partyCode;
		$creteria = $data->creteria;
		$query = "";
		if($profile_id != 1 ||  $profile_id != 10 ||  $profile_id != 22 ||  $profile_id != 20) {
			$topartyCode = $data->topartyCode;
			$partyType = $_SESSION['party_type'];
			$sesion_party_code = $_SESSION['party_code'];
			if($creteria == "SP") {				
				$partyCode = $partyCode;
			}
			if($creteria == "TP") {
				$partyCode = $topartyCode;
			} 
			if($partyType == "C") {
				if($creteria == "SP") {		
					$query = "SELECT champion_code as party_code, concat(b.outlet_name,'(','Self',')') as party_name , login_name FROM champion_info a, application_info b WHERE a.application_id = b.application_id and a.champion_code = '$sesion_party_code'";
				}
				if($creteria == "TP") {		
				
					$query = "SELECT agent_code as party_code, concat(b.outlet_name,'(','Self',')') as party_name , login_name FROM agent_info a, application_info b WHERE a.application_id = b.application_id and a.agent_code = '$partyCode' and a.parent_code = '".$_SESSION['party_code']."'";
					if($partyCode == "ALL"){
						$query = "SELECT a.agent_code as party_code, if(a.sub_agent='Y',concat(a.agent_name,'[',ifNULL((select agent_name FROM agent_info WHERE agent_code = a.parent_code),'self'),']'),concat(a.agent_name,'[',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code),'self'),']')) as party_name, login_name FROM agent_info a, application_info b WHERE a.application_id = b.application_id  and a.parent_code = '$sesion_party_code'";
					
					}
					$partyType = "A";
				}
			
			}
			}
		if($profile_id == 1 ||  $profile_id == 10 ||  $profile_id == 22 ||  $profile_id == 20) {
			$partyCode = $data->partyCode;
			$partyType = $data->partyType;
			
			
			if($partyType == "C") {
				$query = "SELECT champion_code as party_code, concat(b.outlet_name,'(','Self',')') as party_name, login_name FROM champion_info a, application_info b WHERE a.application_id = b.application_id and a. champion_code = '$partyCode'";
			}
			if($partyType == "P") {
				$query = "SELECT personal_code as party_code, concat(b.outlet_name,'(','Self',')') as party_name, login_name FROM personal_info a, application_info b WHERE a.application_id = b.application_id and a.personal_code = '$partyCode'";
			} 
			if($partyType == "MA" || $partyType == "SA") {
				$query = "SELECT agent_code as party_code, b.outlet_name as party_name, login_name FROM agent_info a, application_info b WHERE a.application_id = b.application_id and a. agent_code = '$partyCode'";
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
			$data[] = array("partyCode"=>$row['party_code'],"name"=>$row['party_name'],"lname"=>$row['login_name'],"partyType"=>$partyType);           
		}
		echo json_encode($data);
	}
	
	else if($action == "edit") {
		$partyCode = $data->partyCode;
		$partyType = $data->partyType;
		$creteria = $data->creteria;
		if($partyType == "A" ) {
			$query = "SELECT d.outlet_name, ifNull(c.parent_code,'Self') as pcode, ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenrtoutletname, ifNull(b.block_date,' - ') as block_date, ifNull(b.credit_limit,0.00) as credit_limit,ifNull(b.daily_limit,0.00) as daily_limit, ifNull(b.advance_amount,0.00) as advance_amount, ifNull(b.available_balance,0.00) as available_balance, ifNull(b.current_balance,0.00) as current_balance, ifNull(b.minimum_balance,0.00) as minimum_balance,ifNull(b.previous_current_balance,0.00) as previous_current_balance, ifNull(b.uncleared_balance,0.00) as uncleared_balance,ifNull(b.last_tx_no,0) as last_tx_no, ifNull(b.last_tx_amount,0.00) as last_tx_amount, ifNull(b.last_tx_date,' - ') as last_tx_date, if(b.block_status = 'Y','Yes','No') as block_status, if(b.active = 'Y','Yes','No' ) as active, a.agent_code as code, a.agent_name as name, a.login_name as lname, if(a.parent_type='C','Champion',if(a.parent_type= 'A',' A - Agent',if(a.parent_type='S','S -Sub Agent',if(a.parent_type='P','Personal','')))) as ptype, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype, if(a.sub_agent = 'Y','Yes','No') as sub_agent,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = b.create_user) as create_user,b.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = b.update_user) as update_user,b.update_time, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = b.block_reason_id) as block_reason_id FROM agent_info a , agent_wallet b, application_main c, application_info d WHERE c.application_id = d.application_id and a.agent_code = b.agent_code and a.application_id = c.application_id and a.agent_code = '".$partyCode."'";
		}
		if($partyType == "S" ) {
			$query = "SELECT d.outlet_name, ifNull(c.parent_code,'Self') as pcode, ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenrtoutletname, ifNull(b.block_date,' - ') as block_date, ifNull(b.credit_limit,0.00) as credit_limit,ifNull(b.daily_limit,0.00) as daily_limit, ifNull(b.advance_amount,0.00) as advance_amount, ifNull(b.available_balance,0.00) as available_balance, ifNull(b.current_balance,0.00) as current_balance, ifNull(b.minimum_balance,0.00) as minimum_balance,ifNull(b.previous_current_balance,0.00) as previous_current_balance, ifNull(b.uncleared_balance,0.00) as uncleared_balance,ifNull(b.last_tx_no,0) as last_tx_no, ifNull(b.last_tx_amount,0.00) as last_tx_amount, ifNull(b.last_tx_date,' - ') as last_tx_date, if(b.block_status = 'Y','Yes','No') as block_status, if(b.active = 'Y','Yes','No' ) as active, a.agent_code as code, a.agent_name as name, a.login_name as lname, if(a.parent_type='C','Champion',if(a.parent_type= 'A','A - Agent',if(a.parent_type='S',' S-Sub Agent',if(a.parent_type='P','Personal','')))) as ptype, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype, if(a.sub_agent = 'Y','Yes','No') as sub_agent,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = b.create_user) as create_user,b.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = b.update_user) as update_user,b.update_time, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = b.block_reason_id) as block_reason_id FROM agent_info a , agent_wallet b, application_main c, application_info d WHERE a.sub_agent = 'Y' and c.application_id = d.application_id and a.agent_code = b.agent_code and a.application_id = c.application_id and a.agent_code = '".$partyCode."'";
		}
		if($profile_id != 1 ||  $profile_id != 10 ||  $profile_id != 22 ||  $profile_id != 20) {
		$partyCode = $data->partyCode;
		$partyType = $data->partyType;
		$creteria = $data->creteria;
		$topartyCode = $data->topartyCode;
			$partyType = $_SESSION['party_type'];
			$sesion_party_code = $_SESSION['party_code'];
			
			if($partyType == "C") {
				if($creteria == "SP") {
					$query = "SELECT d.outlet_name, ifNull(c.parent_code,'Self') as pcode, ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenrtoutletname, ifNull(b.block_date,' - ') as block_date, ifNull(b.credit_limit,0.00) as credit_limit,ifNull(b.daily_limit,0.00) as daily_limit, ifNull(b.advance_amount,0.00) as advance_amount,ifNull(b.available_balance,0.00) as available_balance, ifNull(b.current_balance,0.00) as current_balance, ifNull(b.minimum_balance,0.00) as minimum_balance, ifNull(b.previous_current_balance,0.00) as previous_current_balance, ifNull(b.uncleared_balance,0.00) as uncleared_balance,ifNull(b.last_tx_no,0) as last_tx_no, ifNull(b.last_tx_amount,0.00) as last_tx_amount,ifNull(b.last_tx_date,' - ') as last_tx_date, if(b.block_status = 'Y','Yes','No') as block_status, if(b.active = 'Y','Yes','No' ) as active, a.champion_code as code, a.champion_name as name, a.login_name as lname, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = b.create_user) as create_user,b.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = b.update_user) as update_user,b.update_time, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = b.block_reason_id) as block_reason_id FROM champion_info a , champion_wallet b, application_main c, application_info d WHERE c.application_id = d.application_id and a.champion_code = b.champion_code and a.application_id = c.application_id and a.champion_code = '".$sesion_party_code."'";
				}
				if($creteria == "TP") {
					$query = "SELECT d.outlet_name, ifNull(c.parent_code,'Self') as pcode, ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenrtoutletname, ifNull(b.block_date,' - ') as block_date, ifNull(b.credit_limit,0.00) as credit_limit,ifNull(b.daily_limit,0.00) as daily_limit, ifNull(b.advance_amount,0.00) as advance_amount,ifNull(b.available_balance,0.00) as available_balance, ifNull(b.current_balance,0.00) as current_balance, ifNull(b.minimum_balance,0.00) as minimum_balance, ifNull(b.previous_current_balance,0.00) as previous_current_balance, ifNull(b.uncleared_balance,0.00) as uncleared_balance,ifNull(b.last_tx_no,0) as last_tx_no, ifNull(b.last_tx_amount,0.00) as last_tx_amount,ifNull(b.last_tx_date,' - ') as last_tx_date, if(b.block_status = 'Y','Yes','No') as block_status, if(b.active = 'Y','Yes','No' ) as active, a.agent_code as code, a.agent_name as name, a.login_name as lname,	if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = b.create_user) as create_user,b.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = b.update_user) as update_user,b.update_time, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = b.block_reason_id) as block_reason_id FROM agent_info a , agent_wallet b, application_main c, application_info d WHERE c.application_id = d.application_id and a.agent_code = b.agent_code and a.application_id = c.application_id and a.agent_code = '".$partyCode."' and a.parent_code = '".$_SESSION['party_code']."'";
				}
			}
		}
		if($profile_id == 1 ||  $profile_id == 10 ||  $profile_id == 22  ||  $profile_id == 20) {
			if($partyType == "C") {
				$query = "SELECT d.outlet_name, ifNull(c.parent_code,'Self') as pcode,  ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenrtoutletname,ifNull(b.block_date,' - ') as block_date, ifNull(b.credit_limit,0.00) as credit_limit,ifNull(b.daily_limit,0.00) as daily_limit, ifNull(b.advance_amount,0.00) as advance_amount,ifNull(b.available_balance,0.00) as available_balance, ifNull(b.current_balance,0.00) as current_balance, ifNull(b.minimum_balance,0.00) as minimum_balance,ifNull(b.previous_current_balance,0.00) as previous_current_balance,ifNull(b.uncleared_balance,0.00) as uncleared_balance,ifNull(b.last_tx_no,0.00) as last_tx_no, ifNull(b.last_tx_amount,0.00) as last_tx_amount,ifNull(b.last_tx_date,' - ') as last_tx_date, if(b.block_status = 'Y','Yes','No') as block_status, if(b.active = 'Y','Yes','No' ) as  active, a.champion_code as code , a.champion_name as name, a.login_name as lname, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = b.create_user) as create_user,b.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = b.update_user) as update_user,b.update_time, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = b.block_reason_id) as block_reason_id FROM champion_info a , champion_wallet b, application_main c, application_info d WHERE c.application_id = d.application_id and a.champion_code = b.champion_code and a.application_id = c.application_id and a.champion_code = '".$partyCode."'";
			}
		
		if($partyType == "P") {
			$query = "SELECT d.outlet_name, ifNull(c.parent_code,'Self') as pcode, ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenrtoutletname,ifNull(b.block_date,' - ') as block_date, ifNull(b.credit_limit,0.00) as credit_limit,ifNull(b.daily_limit,0.00) as daily_limit, ifNull(b.advance_amount,0.00) as advance_amount,ifNull(b.available_balance,0.00) as available_balance, ifNull(b.current_balance,0.00) as current_balance, ifNull(b.minimum_balance,0.00) as minimum_balance,ifNull(b.previous_current_balance,0.00) as previous_current_balance,ifNull(b.uncleared_balance,0.00) as uncleared_balance,ifNull(b.last_tx_no,0.00) as last_tx_no, ifNull(b.last_tx_amount,0.00) as last_tx_amount,ifNull(b.last_tx_date,' - ') as last_tx_date, if(b.block_status = 'Y','Yes','No') as block_status, if(b.active = 'Y','Yes','No' ) as  active, a.personal_code as code , a.personal_name as name, a.login_name as lname, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = b.create_user) as create_user,b.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = b.update_user) as update_user,b.update_time, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = b.block_reason_id) as block_reason_id FROM personal_info a , personal_wallet b, application_main c, application_info d WHERE c.application_id = d.application_id and a.personal_code = b.personal_code and a.application_id = c.application_id and a.personal_code = '".$partyCode."'";
		}
		}
		//error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_assoc($result)) {
			$data[] = array("outlet_name"=>$row['outlet_name'],"block_reason_id"=>$row['block_reason_id'],"update_time"=>$row['update_time'],"update_user"=>$row['update_user'],"create_time"=>$row['create_time'],"create_user"=>$row['create_user'],"parenrtoutletname"=>$row['parenrtoutletname'],"block_status"=>$row['block_status'],"block_date"=>$row['block_date'],"active"=>$row['active'],"last_tx_date"=>$row['last_tx_date'],"last_tx_amount"=>$row['last_tx_amount'],"last_tx_no"=>$row['last_tx_no'],"uncleared_balance"=>$row['uncleared_balance'],"previous_current_balance"=>$row['previous_current_balance'],"minimum_balance"=>$row['minimum_balance'],"current_balance"=>$row['current_balance'],"available_balance"=>$row['available_balance'],"advance_amount"=>$row['advance_amount'],"credit_limit"=>$row['credit_limit'],"daily_limit"=>$row['daily_limit'],"code"=>$row['code'],"name"=>$row['name'],"lname"=>$row['lname'],"pcode"=>$row['pcode'],"ptype"=>$row['ptype'],"atype"=>$row['partytype'],"sub_agent"=>$row['sub_agent']);           
		}
		error_log($query);
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action == "walletedit") {
       $partyCode = $data->partyCode;
		$partyType = $data->partyType;
		$creteria = $data->creteria;
		error_log($partyType);
		if($partyType == "A" ) {
		$query = " select daily_limit,credit_limit,minimum_balance,advance_amount,active from agent_wallet where agent_code='".$partyCode."'";
		}
		if($partyType == "S" ) {
		$query = " select daily_limit,credit_limit,minimum_balance,advance_amount,active from agent_wallet where agent_code='".$partyCode."'";
		}
		if($partyType == "C") {
			$query = " select daily_limit,credit_limit,minimum_balance,advance_amount,active from champion_wallet where champion_code='".$partyCode."'";
			}
		
		if($partyType == "P") {
			$query = " select daily_limit,credit_limit,minimum_balance,advance_amount,active from personal_wallet where personal_code='".$partyCode."'";
		}
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("dailyLimit"=>$row['daily_limit'],"creditLimit"=>$row['credit_limit'],"minimumBalance"=>$row['minimum_balance'],"advanceAmount"=>$row['advance_amount'],"Active"=>$row['active']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action == "walletupdate") {
		$partyCode = $data->partyCode;
		$dailyLimit = $data->dailyLimit;
		$creditLimit = $data->creditLimit;
		$minimumBalance = $data->minimumBalance;
		$advanceAmount = $data->advanceAmount; 
		$Active = $data->Active;
		$type = substr($partyCode, 0, 1);
		
		$query = "";
		$table_name = "";
		$ret_val = '';
		if($type == 'S' || $type == 'A') {
			$table_name = 'agent_wallet';	
			$col_name = 'agent_code';	
		}
		else if($type == 'P') {
			$table_name = 'personal_wallet';	
			$col_name = 'personal_code';				
		}
		else if($type == 'C') {
			$table_name = 'champion_wallet';	
			$col_name = 'champion_code';				
		}
		
		error_log("table_name = ".$table_name.", col_name = ".$col_name);
		$query ="UPDATE $table_name SET daily_limit = '$dailyLimit', credit_limit = '$creditLimit', minimum_balance = '$minimumBalance', advance_amount = '$advanceAmount',  active = '$Active' WHERE $col_name = '$partyCode'";
		error_log("update query = ".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error:$table_name". mysqli_error($con);
			exit();
			$ret_val = -1;
		}
		else {
			 echo "Updated successfully";
		}
	}
	
?>	