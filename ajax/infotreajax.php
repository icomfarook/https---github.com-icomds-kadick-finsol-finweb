<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');
	
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$profile_id = $_SESSION['profile_id'];	
	$user_id = $_SESSION['user_id'];
	//$profile_id = 1;
	if($action == "findlist") {
		$partyCode = $data->partyCode;
		$creteria = $data->creteria;
		$query = "";
		if($profile_id != 1 ||  $profile_id != 10) {
			$topartyCode = $data->topartyCode;
			if($creteria == "SP") {
				$partyType = substr($partyCode, 0, 1);
				$partyCode = $partyCode;
			}
			if($creteria == "TP") {
				$partyType = substr($topartyCode, 0, 1);
				$partyCode = $topartyCode;
			} 
			if($partyType == "C") {
				$query = "SELECT champion_code as party_code, concat(b.outlet_name,'(','Self',')') as party_name, concat(champion_code,' - ',champion_name) as party_name, login_name FROM champion_info a, application_info b WHERE a.application_id = b.application_id and a.champion_code = '$partyCode'";
			}
			if($partyType == "P") {
				$query = "SELECT personal_code as party_code, concat(b.outlet_name,'(','Self',')') as party_name , login_name FROM personal_info a, application_info b WHERE a.application_id = b.application_id and a. personal_code = '$partyCode'";
			}
			if($partyType == "A") {
				$query = "SELECT agent_code as party_code, concat(b.outlet_name,'(',(SELECT outlet_name FROM application_info WHERE  party_code = a.parent_code),')') as party_name, login_name FROM agent_info a, application_info b WHERE a.application_id = b.application_id and a. agent_code = '$partyCode'";
				if($creteria == "TP") {
					$query .=" and sub_agent = 'Y'";
				}
				if($creteria == "TP") {
					$partyType = "S";
				}
				
			
			}
		}
		if($profile_id == 1 ||  $profile_id == 10) {
			$partyCode = $data->topartyCode;
			$partyType = $data->partyType;
			
			
			if($partyType == "C") {
				$query = "SELECT champion_code as party_code, concat(b.outlet_name,'(','Self',')') as party_name, login_name FROM champion_info a, application_info b WHERE a.application_id = b.application_id and a.champion_code = '$partyCode'";
			}
			if($partyType == "P") {
				$query = "SELECT personal_code as party_code,concat(b.outlet_name,'(','Self',')')  as party_name, login_name FROM personal_info a, application_info b WHERE a.application_id = b.application_id and a.personal_code = '$partyCode'";
			}
			if($partyType == "MA" || $partyType == "SA") {
				$partyType = "A";
				$query = "SELECT agent_code as party_code, b.outlet_name as party_name, login_name FROM agent_info a, application_info b WHERE a.application_id = b.application_id and a.agent_code = '$partyCode'";
				if($partyType == "SA") {
					$partyType = "S";
					$query .=" and sub_agent = 'Y'";
				}				
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
		if($type == "A" ) {
			$query = "SELECT d.outlet_name, ifNull(c.parent_code,'Self') as pcode,  ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname,ifNull(a.block_date,' - ') as block_date, if(a.block_status = 'Y','Yes','No') as block_status, if(a.active = 'Y','Yes','No' ) as  active, a.agent_code as code , a.agent_name as name, a.login_name as lname, if(a.parent_type='C','Champion',if(a.parent_type= 'A','Agent',if(a.parent_type='S','Sub Agent',if(a.parent_type='P','Personal','')))) as ptype, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype, if(a.sub_agent = 'Y','Yes','No') as sub_agent,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.create_user) as create_user,a.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.update_user) as update_user,a.update_time, concat(e.country_code,' - ',e.country_description) as country, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = a.block_reason_id) as block_reason_id, g.name as gvtname, h.name as state,a.zip_code, a.work_no, a.email, a.mobile_no, a.contact_person_name, a.contact_person_mobile, a.tax_number,a.application_id,a.start_date, a.expiry_date, a.block_date,  (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.user_id) as user FROM agent_info a , application_main c, application_info d , country e, local_govt_list g, state_list h WHERE h.state_id = a.state_id and g.local_govt_id = a.local_govt_id and e.country_id = a.country_id and  c.application_id = d.application_id and a.application_id = c.application_id and a.agent_code = '".$code."'";
		}
		if($type == "S" ) {
			$query = "SELECT d.outlet_name, ifNull(c.parent_code,'Self') as pcode,  ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname,ifNull(a.block_date,' - ') as block_date, if(a.block_status = 'Y','Yes','No') as block_status, if(a.active = 'Y','Yes','No' ) as  active, a.agent_code as code , a.agent_name as name, a.login_name as lname, if(a.parent_type='C','Champion',if(a.parent_type= 'A','Agent',if(a.parent_type='S','Sub Agent',if(a.parent_type='P','Personal','')))) as ptype, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype, if(a.sub_agent = 'Y','Yes','No') as sub_agent,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.create_user) as create_user,a.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.update_user) as update_user,a.update_time, concat(e.country_code,' - ',e.country_description) as country, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = a.block_reason_id) as block_reason_id, g.name as gvtname, h.name as state,a.zip_code, a.work_no, a.email, a.mobile_no, a.contact_person_name, a.contact_person_mobile, a.tax_number,a.application_id,a.start_date, a.expiry_date, a.block_date,  (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.user_id) as user FROM agent_info a , application_main c, application_info d , country e, local_govt_list g, state_list h WHERE h.state_id = a.state_id and g.local_govt_id = a.local_govt_id and e.country_id = a.country_id and  c.application_id = d.application_id and a.application_id = c.application_id and a.sub_agent = 'Y' and a.agent_code =  '".$code."'";
		}
		if($type == "C") {
			$query = "SELECT d.outlet_name, ifNull(c.parent_code,'Self') as pcode,  ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname,ifNull(a.block_date,' - ') as block_date, if(a.block_status = 'Y','Yes','No') as block_status, if(a.active = 'Y','Yes','No' ) as  active, a.champion_code as code , a.champion_name as name, a.login_name as lname, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype, (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.create_user) as create_user,a.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.update_user) as update_user,a.update_time, concat(e.country_code,' - ',e.country_description) as country, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = a.block_reason_id) as block_reason_id, g.name as gvtname, h.name as state,a.zip_code, a.work_no, a.email, a.mobile_no, a.contact_person_name, a.contact_person_mobile, a.tax_number,a.application_id,a.start_date, a.expiry_date, a.block_date,  (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.user_id) as user FROM champion_info a , application_main c, application_info d , country e, local_govt_list g, state_list h WHERE h.state_id = a.state_id and g.local_govt_id = a.local_govt_id and e.country_id = a.country_id and  c.application_id = d.application_id and a.application_id = c.application_id and a.champion_code = '".$code."'";
		}
		if($type == "P") {
			$query = "SELECT d.outlet_name, ifNull(c.parent_code,'Self') as pcode,  ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname,ifNull(a.block_date,' - ') as block_date, if(a.block_status = 'Y','Yes','No') as block_status, if(a.active = 'Y','Yes','No' ) as  active, a.personal_code as code , a.personal_name as name, a.login_name as lname, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype, (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.create_user) as create_user,a.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.update_user) as update_user,a.update_time, concat(e.country_code,' - ',e.country_description) as country, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = a.block_reason_id) as block_reason_id, g.name as gvtname, h.name as state,a.zip_code, a.work_no, a.email, a.mobile_no, a.contact_person_name, a.contact_person_mobile, a.tax_number,a.application_id,a.start_date, a.expiry_date, a.block_date,  (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.user_id) as user FROM personal_info a , application_main c, application_info d , country e, local_govt_list g, state_list h WHERE h.state_id = a.state_id and g.local_govt_id = a.local_govt_id and e.country_id = a.country_id and  c.application_id = d.application_id and a.application_id = c.application_id and a.personal_code = '".$code."'";
		}
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("outlet_name"=>$row['outlet_name'],"pcode"=>$row['pcode'],"parenroutletname"=>$row['parenroutletname'],"block_date"=>$row['block_date'],"block_status"=>$row['block_status'],"active"=>$row['active'],"code"=>$row['code'],"name"=>$row['name'],"lname"=>$row['lname'],"ptype"=>$row['ptype'],"partytype"=>$row['partytype'],"sub_agent"=>$row['sub_agent'],"create_user"=>$row['create_user'],"create_time"=>$row['create_time'],"update_user"=>$row['update_user'],"update_time"=>$row['update_time'],"country"=>$row['country'],"block_reason_id"=>$row['block_reason_id'],"gvtname"=>$row['gvtname'],"state"=>$row['state'],"zip_code"=>$row['zip_code'],"work_no"=>$row['work_no'],"email"=>$row['email'],"mobile_no"=>$row['mobile_no'],"contact_person_name"=>$row['contact_person_name'],"tax_number"=>$row['tax_number'],"user"=>$row['user'],"block_date"=>$row['block_date'],"start_date"=>$row['start_date'],"expiry_date"=>$row['expiry_date'],"application_id"=>$row['application_id'],"contact_person_mobile"=>$row['contact_person_mobile']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	
	else if($action == "detail") {
		$code = $data->code;
		$type = $data->type;
		if($type == "A" ) {
			$query = "SELECT d.outlet_name, ifNull(c.parent_code,'Self') as pcode,  ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname,ifNull(a.block_date,' - ') as block_date, if(a.block_status='Y','Y','N') as block_status, if(a.active='Y','Y','N') as active, a.agent_code as code , a.agent_name as name, a.login_name as lname, if(a.parent_type='C','Champion',if(a.parent_type= 'A','Agent',if(a.parent_type='S','Sub Agent',if(a.parent_type='P','Personal','')))) as ptype, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype, if(a.sub_agent = 'Y','Yes','No') as sub_agent,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.create_user) as create_user,a.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.update_user) as update_user,a.update_time, concat(e.country_code,' - ',e.country_description) as country, a.block_reason_id, g.name as gvtname, h.name as state,a.zip_code, a.work_no, a.email, a.mobile_no, a.contact_person_name, a.contact_person_mobile, a.tax_number,a.application_id,a.start_date, a.expiry_date, a.block_date,  (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.user_id) as user FROM agent_info a , application_main c, application_info d , country e, local_govt_list g, state_list h WHERE h.state_id = a.state_id and g.local_govt_id = a.local_govt_id and e.country_id = a.country_id and  c.application_id = d.application_id and a.application_id = c.application_id and a.agent_code = '".$code."'";
		}
		if($type == "S" ) {
			$query = "SELECT d.outlet_name, ifNull(c.parent_code,'Self') as pcode,  ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname,ifNull(a.block_date,' - ') as block_date, if(a.block_status='Y','Y','N') as block_status, if(a.active='Y','Y','N') as active, a.agent_code as code , a.agent_name as name, a.login_name as lname, if(a.parent_type='C','Champion',if(a.parent_type= 'A','Agent',if(a.parent_type='S','Sub Agent',if(a.parent_type='P','Personal','')))) as ptype, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype, if(a.sub_agent = 'Y','Yes','No') as sub_agent,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.create_user) as create_user,a.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.update_user) as update_user,a.update_time, concat(e.country_code,' - ',e.country_description) as country, a.block_reason_id, g.name as gvtname, h.name as state,a.zip_code, a.work_no, a.email, a.mobile_no, a.contact_person_name, a.contact_person_mobile, a.tax_number,a.application_id,a.start_date, a.expiry_date, a.block_date,  (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.user_id) as user FROM agent_info a , application_main c, application_info d , country e, local_govt_list g, state_list h WHERE h.state_id = a.state_id and g.local_govt_id = a.local_govt_id and e.country_id = a.country_id and  c.application_id = d.application_id and a.application_id = c.application_id and a.sub_agent = 'Y' and a.agent_code =  '".$code."'";
		}
		if($type == "C") {
			$query = "SELECT d.outlet_name, ifNull(c.parent_code,'Self') as pcode,  ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname,ifNull(a.block_date,' - ') as block_date, if(a.block_status='Y','Y','N') as block_status,if(a.active='Y','Y','N') as active, a.champion_code as code , a.champion_name as name, a.login_name as lname, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype, (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.create_user) as create_user,a.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.update_user) as update_user,a.update_time, concat(e.country_code,' - ',e.country_description) as country, a.block_reason_id, g.name as gvtname, h.name as state,a.zip_code, a.work_no, a.email, a.mobile_no, a.contact_person_name, a.contact_person_mobile, a.tax_number,a.application_id,a.start_date, a.expiry_date, a.block_date,  (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.user_id) as user FROM champion_info a , application_main c, application_info d , country e, local_govt_list g, state_list h WHERE h.state_id = a.state_id and g.local_govt_id = a.local_govt_id and e.country_id = a.country_id and  c.application_id = d.application_id and a.application_id = c.application_id and a.champion_code = '".$code."'";
		}
		if($type == "P") {
			$query = "SELECT d.outlet_name, ifNull(c.parent_code,'Self') as pcode,  ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname,ifNull(a.block_date,' - ') as block_date, if(a.block_status='Y','Y','N') as block_status, if(a.active='Y','Y','N') as active, a.personal_code as code , a.personal_name as name, a.login_name as lname, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype, (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.create_user) as create_user,a.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.update_user) as update_user,a.update_time, concat(e.country_code,' - ',e.country_description) as country, a.block_reason_id, g.name as gvtname, h.name as state,a.zip_code, a.work_no, a.email, a.mobile_no, a.contact_person_name, a.contact_person_mobile, a.tax_number,a.application_id,a.start_date, a.expiry_date, a.block_date,  (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.user_id) as user FROM personal_info a , application_main c, application_info d , country e, local_govt_list g, state_list h WHERE h.state_id = a.state_id and g.local_govt_id = a.local_govt_id and e.country_id = a.country_id and  c.application_id = d.application_id and a.application_id = c.application_id and a.personal_code = '".$code."'";
		}
		//error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("outlet_name"=>$row['outlet_name'],"lname"=>$row['lname'],"name"=>$row['name'],"code"=>$row['code'],"application_id"=>$row['application_id'],"tax_number"=>$row['tax_number'],"contact_person_mobile"=>$row['contact_person_mobile'],"contact_person_name"=>$row['contact_person_name'],"email"=>$row['email'],"work_no"=>$row['work_no'],"mobile_no"=>$row['mobile_no'],"zip_code"=>$row['zip_code'],"parentcode"=>$row['parentout'],"active"=>$row['active'],"block_reason_id"=>$row['block_reason_id'],"login_name"=>$row['login_name'],"pcode"=>$row['parent_code'],"block_status"=>$row['block_status'],"code"=>$row['code'],"name"=>$row['name'],"lname"=>$row['lname'],"pcode"=>$row['pcode'],"ptype"=>$row['ptype'],"atype"=>$row['partytype'],"sub_agent"=>$row['sub_agent']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	
	if($action == "update") {
		$code = $data->code;
		$blckstatus = $data->blckstatus;
		$blckreason = $data->blckreason;
		$active = $data->active;
		if(empty($blckreason) ||$blckreason == "" ) {
			$blckreason = 'null';
		}
		$type = substr($code, 0, 1);
		if($type == 'S' || $type == 'A') {
			$table_name = 'agent_info';	
			$col_name = 'agent_code';	
		}
		else if($type == 'P') {
			$table_name = 'personal_info';	
			$col_name = 'personal_code';				
		}
		else if($type == 'C') {
			$table_name = 'champion_info';	
			$col_name = 'champion_code';				
		}
		////error_log("table_name = ".$table_name.", col_name = ".$col_name);
		$query ="UPDATE $table_name SET block_status = '$blckstatus', block_reason_id = $blckreason, active = '$active', block_date = now(),update_user = '$user_id', update_time = now() WHERE $col_name = '$code'";
		////error_log("update query = ".$query);
		if(mysqli_query($con, $query)) {
			 echo "updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		 }			
	}
?>	