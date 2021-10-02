<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');	
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$profile_id = $_SESSION['profile_id'];	
	$agent_name	=   $_SESSION['party_name'];
	//$profile_id = 1;
	$dob = $data->dob;
	$dob = date("Y-m-d", strtotime($dob));
	if($action == "findlist") {
		$partyCode = $data->partyCode;
		$creteria = $data->creteria;
		$query = "";
		if($profile_id != 1 || $profile_id != 10 || $profile_id != 20 || $profile_id != 21 || $profile_id != 22 || $profile_id != 23 || $profile_id != 24 || $profile_id != 25 || $profile_id != 26 || $profile_id != 30) {
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
					$query = "SELECT a.champion_code as party_code, concat(b.outlet_name,'(','Self',')') as party_name, a.login_name FROM champion_info a, application_info b WHERE a.application_id = b.application_id and a.champion_code = '$partyCode'";
				}				
				if($creteria == "TP") {				
					$query = "SELECT a.agent_code as party_code, concat(b.outlet_name,'(','Self',')') as party_name, a.login_name FROM agent_info a, application_info b WHERE a.application_id = b.application_id and a.agent_code = '$partyCode' and a.parent_code = '$sesion_party_code' ";
					if($partyCode == "ALL"){
					$query = "SELECT a.agent_code as party_code,if(a.sub_agent='Y',concat(a.agent_name,'[',ifNULL((select agent_name FROM agent_info WHERE agent_code = a.parent_code),'self'),']'),concat(a.agent_name,'[',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code),'self'),']')) as party_name, a.login_name  FROM agent_info a,application_info b WHERE  a.application_id = b.application_id and a.parent_code = '$sesion_party_code'";
					
				}
				} 
			}
			if($partyType == "P") {
				$query = "SELECT a.personal_code as party_code, concat(b.outlet_name,'(','Self',')') as party_name, a.login_name FROM personal_info a, application_info b WHERE a.application_id = b.application_id and a.personal_code = '$partyCode'";
			}
			if($partyType == "A") {
				
				$query = "SELECT a.agent_code as party_code,if(a.sub_agent='Y',concat(a.agent_name,'[',ifNULL((select agent_name FROM agent_info WHERE agent_code = a.parent_code),'self'),']'),concat(a.agent_name,'[',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code),'self'),']')) as party_name, a.login_name  FROM agent_info a,application_info b WHERE  a.application_id = b.application_id and a.agent_code = '$partyCode'";
				if($creteria == "TP") {
					$partyType = "S";
					$query .=" and a.sub_agent = 'Y' and a.parent_code = '$sesion_party_code' ";
				}
			
			}
		}
		if($profile_id == 1 || $profile_id == 10 || $profile_id == 20 || $profile_id == 21 || $profile_id == 22 || $profile_id == 23 || $profile_id == 24 || $profile_id == 25 || $profile_id == 26 || $profile_id == 30) {
			$partyCode = $data->partyCode;
			$partyType = $data->partyType;
			
			
			if($partyType == "C") {
				$query = "SELECT a.champion_code as party_code, concat(b.outlet_name,'(','Self',')') as party_name, a.login_name FROM champion_info a, application_info b WHERE a.application_id = b.application_id and a.champion_code = '$partyCode'";
			}
			if($partyType == "P") {
				$query = "SELECT a.personal_code as party_code, concat(b.outlet_name,'(','Self',')') as party_name, a.login_name FROM personal_info a, application_info b WHERE a.application_id = b.application_id and a.personal_code = '$partyCode'";
			}
			if($partyType == "MA" || $partyType == "SA") {
				$query = "SELECT a.agent_code as party_code,b.outlet_name as party_name, a.login_name FROM agent_info a,application_info b WHERE  a.application_id = b.application_id and a.agent_code = '$partyCode'";
				if($partyType == "SA") {
					$query .=" and sub_agent = 'Y'";					
				}
				if($partyType == "MA") {
					$partyType = "A";
				}
				if($partyType == "SA") {
					$partyType = "S";
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
			$data[] = array("partyCode"=>$row['party_code'],"name"=>$row['party_name'],"lname"=>$row['login_name'],"partyType"=>$partyType);           
		}
		echo json_encode($data);
	}
	
	else if($action == "edit") {
		$partyCode = $data->partyCode;
		$partyType = $data->partyType;
		$creteria = $data->creteria;
		$gender = $data->gender;
		$dob = $data->dob;
		$dob = date("Y-m-d", strtotime($dob));
		$BusinessType = $data->BusinessType;
					
		if($partyType == "A" ) {
			$query = "SELECT a.address1, a.address2, a.local_govt_id, a.state_id, a.loc_latitude, a.loc_longitude, d.outlet_name, ifNull(c.parent_code,'Self') as pcode, ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname,ifNull(a.block_date,' - ') as block_date, if(a.block_status = 'Y','Yes','No') as block_status,  a.agent_code as code, a.agent_name as name, a.login_name as lname, if(a.parent_type='C','Champion',if(a.parent_type= 'A','Agent',if(a.parent_type='S','Sub Agent',if(a.parent_type='P','Personal','')))) as ptype, if(a.party_category_type_id='1','BRONZE',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype, if(a.sub_agent = 'Y','Yes','No') as sub_agent,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.create_user) as create_user,a.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.update_user) as update_user,a.update_time, concat(e.country_code,' - ',e.country_description) as country,if(c.applier_type='C','C - Champion',if(c.applier_type= 'A','A - Agent',if(c.applier_type='S','S - Sub Agent',if(c.applier_type='P','P - Personal',''))))  as atype, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = a.block_reason_id) as block_reason_id, g.name as gvtname, h.name as state,a.zip_code, a.work_no, a.email, a.mobile_no, a.contact_person_name, a.contact_person_mobile, a.tax_number,a.application_id,a.start_date, a.expiry_date, a.block_date,  (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.user_id) as user,a.dob,a.gender,a.business_type ,a.active FROM agent_info a , application_main c, application_info d , country e, local_govt_list g, state_list h WHERE h.state_id = a.state_id and g.local_govt_id = a.local_govt_id and e.country_id = a.country_id and  c.application_id = d.application_id and a.application_id = c.application_id and a.agent_code = '".$partyCode."'";
		}
		if($partyType == "S" ) {
			$query = "SELECT a.address1, a.address2, a.local_govt_id, a.state_id, a.loc_latitude, a.loc_longitude, d.outlet_name, ifNull(c.parent_code,'Self') as pcode, ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname,ifNull(a.block_date,' - ') as block_date, if(a.block_status = 'Y','Yes','No') as block_status,  a.agent_code as code, a.agent_name as name, a.login_name as lname, if(a.parent_type='C','Champion',if(a.parent_type= 'A','Agent',if(a.parent_type='S','Sub Agent',if(a.parent_type='P','Personal','')))) as ptype, if(a.party_category_type_id='1','BRONZE',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype, if(a.sub_agent = 'Y','Yes','No') as sub_agent,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.create_user) as create_user,a.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.update_user) as update_user,a.update_time, concat(e.country_code,' - ',e.country_description) as country, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = a.block_reason_id) as block_reason_id, g.name as gvtname, h.name as state,a.zip_code, a.work_no, a.email, a.mobile_no,if(c.applier_type='C','C - Champion',if(c.applier_type= 'A','A - Agent',if(c.applier_type='S','S - Sub Agent',if(c.applier_type='P','P - Personal',''))))  as atype, a.contact_person_name, a.contact_person_mobile, a.tax_number,a.application_id,a.start_date, a.expiry_date, a.block_date,  (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.user_id) as user,a.dob,a.gender,a.business_type,a.active FROM agent_info a , application_main c, application_info d , country e, local_govt_list g, state_list h WHERE h.state_id = a.state_id and g.local_govt_id = a.local_govt_id and e.country_id = a.country_id and  c.application_id = d.application_id and a.application_id = c.application_id and a.sub_agent = 'Y' and a.agent_code =  '".$partyCode."'";
		}
		if($profile_id == 1 || $profile_id == 10 || $profile_id == 20 || $profile_id == 21 || $profile_id == 22 || $profile_id == 23 || $profile_id == 24 || $profile_id == 25 || $profile_id == 26 || $profile_id == 30) {
			if($partyType == "C") {
				$query = "SELECT a.address1, a.address2, a.local_govt_id, a.state_id, a.loc_latitude, a.loc_longitude,  d.outlet_name, ifNull(c.parent_code,'Self') as pcode, ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname,ifNull(a.block_date,' - ') as block_date, if(a.block_status = 'Y','Yes','No') as block_status, a.champion_code as code, a.champion_name as name, a.login_name as lname, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype, (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.create_user) as create_user,a.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.update_user) as update_user,a.update_time,if(c.applier_type='C','C - Champion',if(c.applier_type= 'A','A - Agent',if(c.applier_type='S','S - Sub Agent',if(c.applier_type='P','P - Personal',''))))  as atype, concat(e.country_code,' - ',e.country_description) as country,if(c.applier_type='C','C - Champion',if(c.applier_type= 'A','A - Agent',if(c.applier_type='S','S - Sub Agent',if(c.applier_type='P','P - Personal',''))))  as atype, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = a.block_reason_id) as block_reason_id, g.name as gvtname, h.name as state,a.zip_code, a.work_no, a.email, a.mobile_no, a.contact_person_name, a.contact_person_mobile, a.tax_number,a.application_id,a.start_date, a.expiry_date, a.block_date, (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.user_id) as user,a.dob,a.gender,a.business_type,a.active FROM champion_info a , application_main c, application_info d , country e, local_govt_list g, state_list h WHERE h.state_id = a.state_id and g.local_govt_id = a.local_govt_id and e.country_id = a.country_id and  c.application_id = d.application_id and a.application_id = c.application_id and a.champion_code = '".$partyCode."'";
			}
		}
				if($profile_id != 1 || $profile_id != 10 || $profile_id != 20 || $profile_id != 21 || $profile_id != 22 || $profile_id != 23 || $profile_id != 24 || $profile_id != 25 || $profile_id != 26 || $profile_id != 30) {
		$partyCode = $data->code;
		$partyType = $data->partyType;
		$creteria = $data->creteria;
		$topartyCode = $data->topartyCode;
		$partyType = $_SESSION['party_type'];
		$sesion_party_code = $_SESSION['party_code'];
		$gender = $data->gender;
		$dob = $data->dob;
		$dob = date("Y-m-d", strtotime($dob));
		$BusinessType = $data->BusinessType;	
		
			if($partyType == "C") {
				if($creteria == "SP") {
					$query = "SELECT a.address1, a.address2, a.local_govt_id, a.state_id, a.loc_latitude, a.loc_longitude, d.outlet_name, ifNull(c.parent_code,'Self') as pcode, ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname, ifNull(a.block_date,' - ') as block_date, if(a.block_status = 'Y','Yes','No') as block_status,  a.champion_code as code, a.champion_name as name, a.login_name as lname, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype, (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.create_user) as create_user, a.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.update_user) as update_user,a.update_time, concat(e.country_code,' - ',e.country_description) as country, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = a.block_reason_id) as block_reason_id, g.name as gvtname, h.name as state,a.zip_code, a.work_no, a.email, a.mobile_no, a.contact_person_name,if(c.applier_type='C','C - Champion',if(c.applier_type= 'A','A - Agent',if(c.applier_type='S','S - Sub Agent',if(c.applier_type='P','P - Personal',''))))  as atype, a.contact_person_mobile, a.tax_number,a.application_id,a.start_date, a.expiry_date, a.block_date, (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.user_id) as user,a.dob,a.gender,a.business_type ,a.active FROM champion_info a , application_main c, application_info d , country e, local_govt_list g, state_list h WHERE h.state_id = a.state_id and g.local_govt_id = a.local_govt_id and e.country_id = a.country_id and  c.application_id = d.application_id and a.application_id = c.application_id and a.champion_code = '".$partyCode."'";
				}
				if($creteria == "TP") {
					$query = "SELECT a.address1, a.address2, a.local_govt_id, a.state_id, a.loc_latitude, a.loc_longitude, d.outlet_name, ifNull(c.parent_code,'Self') as pcode, ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname, ifNull(a.block_date,' - ') as block_date, if(a.block_status = 'Y','Yes','No') as block_status, a.agent_code as code, a.agent_name as name, a.login_name as lname, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype,if(a.parent_type='A','A - Agent',if(a.parent_type='C','C - Champion','None')) as ptype, (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.create_user) as create_user, a.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.update_user) as update_user,a.update_time, concat(e.country_code,' - ',e.country_description) as country, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = a.block_reason_id) as block_reason_id, g.name as gvtname, h.name as state,a.zip_code, a.work_no, a.email, a.mobile_no,if(c.applier_type='C','C - Champion',if(c.applier_type= 'A','A - Agent',if(c.applier_type='S','S - Sub Agent',if(c.applier_type='P','P - Personal',''))))  as atype, a.contact_person_name, a.contact_person_mobile, a.tax_number,a.application_id,a.start_date, a.expiry_date, a.block_date, (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.user_id) as user,a.active,a.dob,a.gender,a.business_type FROM agent_info a , application_main c, application_info d , country e, local_govt_list g, state_list h WHERE h.state_id = a.state_id and g.local_govt_id = a.local_govt_id and e.country_id = a.country_id and  c.application_id = d.application_id and a.application_id = c.application_id and a.parent_code='".$_SESSION['party_code']."' and a.agent_code = '".$partyCode."'";
				}
			}
		}
		if($partyType == "P") {
			$query = "SELECT d.outlet_name, ifNull(c.parent_code,'Self') as pcode, ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname, ifNull(a.block_date,' - ') as block_date, if(a.block_status = 'Y','Yes','No') as block_status,  a.personal_code as code, a.personal_name as name, a.login_name as lname, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype, (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.create_user) as create_user, a.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.update_user) as update_user,a.update_time, concat(e.country_code,' - ',e.country_description) as country, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = a.block_reason_id) as block_reason_id, g.name as gvtname, h.name as state,a.zip_code, a.work_no, a.email, a.mobile_no, a.contact_person_name, a.contact_person_mobile, a.tax_number,a.application_id,a.start_date, a.expiry_date, a.block_date, (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.user_id) as user,a.dob,a.gender,a.business_type,a.active FROM personal_info a , application_main c, application_info d , country e, local_govt_list g, state_list h WHERE h.state_id = a.state_id and g.local_govt_id = a.local_govt_id and e.country_id = a.country_id and  c.application_id = d.application_id and a.application_id = c.application_id and a.personal_code = '".$partyCode."'";
		}
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("address1"=>$row['address1'],"address2"=>$row['address2'],"local_govt_id"=>$row['local_govt_id'],"state_id"=>$row['state_id'],"loc_latitude"=>$row['loc_latitude'], "loc_longitude"=>$row['loc_longitude'], "outlet_name"=>$row['outlet_name'],"pcode"=>$row['pcode'],"atype"=>$row['atype'],"parenroutletname"=>$row['parenroutletname'],"block_date"=>$row['block_date'],"block_status"=>$row['block_status'],"active"=>$row['active'],"code"=>$row['code'],"name"=>$row['name'],"lname"=>$row['lname'],"ptype"=>$row['ptype'],"partytype"=>$row['partytype'],"sub_agent"=>$row['sub_agent'],"create_user"=>$row['create_user'],"create_time"=>$row['create_time'],"update_user"=>$row['update_user'],"update_time"=>$row['update_time'],"country"=>$row['country'],"block_reason_id"=>$row['block_reason_id'],"gvtname"=>$row['gvtname'],"state"=>$row['state'],"zip_code"=>$row['zip_code'],"work_no"=>$row['work_no'],"email"=>$row['email'],"mobile_no"=>$row['mobile_no'],"contact_person_name"=>$row['contact_person_name'],"tax_number"=>$row['tax_number'],"user"=>$row['user'],"block_date"=>$row['block_date'],"start_date"=>$row['start_date'],"expiry_date"=>$row['expiry_date'],"application_id"=>$row['application_id'],"contact_person_mobile"=>$row['contact_person_mobile'],"dob"=>$row['dob'],"gender"=>$row['gender'],"BusinessType"=>$row['business_type']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			exit();
		}
	}
		else if($action == "view") {
		$partyCode = $data->partyCode;
		$partyType = $data->partyType;
		$creteria = $data->creteria;
		$gender = $data->gender;
		$dob = $data->dob;
		$dob = date("Y-m-d", strtotime($dob));
		$BusinessType = $data->BusinessType;
					
		if($partyType == "A" ) {
			$query = "SELECT a.address1, a.address2, a.local_govt_id, a.state_id, a.loc_latitude, a.loc_longitude, d.outlet_name, ifNull(c.parent_code,'Self') as pcode, ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname,ifNull(a.block_date,' - ') as block_date, if(a.block_status = 'Y','Yes','No') as block_status, if(a.active = 'Y','Yes','No') as active, a.agent_code as code, a.agent_name as name, a.login_name as lname, if(a.parent_type='C','Champion',if(a.parent_type= 'A','Agent',if(a.parent_type='S','Sub Agent',if(a.parent_type='P','Personal','')))) as ptype, if(a.party_category_type_id='1','BRONZE',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype, if(a.sub_agent = 'Y','Yes','No') as sub_agent,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.create_user) as create_user,a.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.update_user) as update_user,a.update_time, concat(e.country_code,' - ',e.country_description) as country,if(c.applier_type='C','C - Champion',if(c.applier_type= 'A','A - Agent',if(c.applier_type='S','S - Sub Agent',if(c.applier_type='P','P - Personal',''))))  as atype, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = a.block_reason_id) as block_reason_id, g.name as gvtname, h.name as state,a.zip_code, a.work_no, a.email, a.mobile_no, a.contact_person_name, a.contact_person_mobile, a.tax_number,a.application_id,a.start_date, a.expiry_date, a.block_date,  (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.user_id) as user,a.dob,a.gender,if(a.business_type='0','Pharmacy',if(a.business_type='1','Gas Station',if(a.business_type='2','Saloon',if(a.business_type='3','Groceries Stores',if(a.business_type='4','Super Market',if(a.business_type='5','Mobile Network Outlets',if(a.business_type='6','Restaurants',if(a.business_type='7','Hotels',if(a.business_type='8','Cyber Cafe',if(a.business_type='9','Post Office','Others')))))))))) as business_type  FROM agent_info a , application_main c, application_info d , country e, local_govt_list g, state_list h WHERE h.state_id = a.state_id and g.local_govt_id = a.local_govt_id and e.country_id = a.country_id and  c.application_id = d.application_id and a.application_id = c.application_id and a.agent_code = '".$partyCode."'";
		}
		if($partyType == "S" ) {
			$query = "SELECT a.address1, a.address2, a.local_govt_id, a.state_id, a.loc_latitude, a.loc_longitude, d.outlet_name, ifNull(c.parent_code,'Self') as pcode, ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname,ifNull(a.block_date,' - ') as block_date, if(a.block_status = 'Y','Yes','No') as block_status, if(a.active = 'Y','Yes','No') as active, a.agent_code as code, a.agent_name as name, a.login_name as lname, if(a.parent_type='C','Champion',if(a.parent_type= 'A','Agent',if(a.parent_type='S','Sub Agent',if(a.parent_type='P','Personal','')))) as ptype, if(a.party_category_type_id='1','BRONZE',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype, if(a.sub_agent = 'Y','Yes','No') as sub_agent,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.create_user) as create_user,a.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.update_user) as update_user,a.update_time, concat(e.country_code,' - ',e.country_description) as country, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = a.block_reason_id) as block_reason_id, g.name as gvtname, h.name as state,a.zip_code, a.work_no, a.email, a.mobile_no,if(c.applier_type='C','C - Champion',if(c.applier_type= 'A','A - Agent',if(c.applier_type='S','S - Sub Agent',if(c.applier_type='P','P - Personal',''))))  as atype, a.contact_person_name, a.contact_person_mobile, a.tax_number,a.application_id,a.start_date, a.expiry_date, a.block_date,  (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.user_id) as user,a.dob,a.gender,if(a.business_type='0','Pharmacy',if(a.business_type='1','Gas Station',if(a.business_type='2','Saloon',if(a.business_type='3','Groceries Stores',if(a.business_type='4','Super Market',if(a.business_type='5','Mobile Network Outlets',if(a.business_type='6','Restaurants',if(a.business_type='7','Hotels',if(a.business_type='8','Cyber Cafe',if(a.business_type='9','Post Office','Others')))))))))) as business_type FROM agent_info a , application_main c, application_info d , country e, local_govt_list g, state_list h WHERE h.state_id = a.state_id and g.local_govt_id = a.local_govt_id and e.country_id = a.country_id and  c.application_id = d.application_id and a.application_id = c.application_id and a.sub_agent = 'Y' and a.agent_code =  '".$partyCode."'";
		}
		if($profile_id == 1 || $profile_id == 10 || $profile_id == 20 || $profile_id == 21 || $profile_id == 22 || $profile_id == 23 || $profile_id == 24 || $profile_id == 25 || $profile_id == 26 || $profile_id == 30) {
			if($partyType == "C") {
				$query = "SELECT a.address1, a.address2, a.local_govt_id, a.state_id, a.loc_latitude, a.loc_longitude,  d.outlet_name, ifNull(c.parent_code,'Self') as pcode, ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname,ifNull(a.block_date,' - ') as block_date, if(a.block_status = 'Y','Yes','No') as block_status, if(a.active = 'Y','Yes','No') as active, a.champion_code as code, a.champion_name as name, a.login_name as lname, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype, (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.create_user) as create_user,a.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.update_user) as update_user,a.update_time,if(c.applier_type='C','C - Champion',if(c.applier_type= 'A','A - Agent',if(c.applier_type='S','S - Sub Agent',if(c.applier_type='P','P - Personal',''))))  as atype, concat(e.country_code,' - ',e.country_description) as country,if(c.applier_type='C','C - Champion',if(c.applier_type= 'A','A - Agent',if(c.applier_type='S','S - Sub Agent',if(c.applier_type='P','P - Personal',''))))  as atype, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = a.block_reason_id) as block_reason_id, g.name as gvtname, h.name as state,a.zip_code, a.work_no, a.email, a.mobile_no, a.contact_person_name, a.contact_person_mobile, a.tax_number,a.application_id,a.start_date, a.expiry_date, a.block_date, (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.user_id) as user,a.dob,a.gender,if(a.business_type='0','Pharmacy',if(a.business_type='1','Gas Station',if(a.business_type='2','Saloon',if(a.business_type='3','Groceries Stores',if(a.business_type='4','Super Market',if(a.business_type='5','Mobile Network Outlets',if(a.business_type='6','Restaurants',if(a.business_type='7','Hotels',if(a.business_type='8','Cyber Cafe',if(a.business_type='9','Post Office','Others')))))))))) as business_type FROM champion_info a , application_main c, application_info d , country e, local_govt_list g, state_list h WHERE h.state_id = a.state_id and g.local_govt_id = a.local_govt_id and e.country_id = a.country_id and  c.application_id = d.application_id and a.application_id = c.application_id and a.champion_code = '".$partyCode."'";
			}
		}
				if($profile_id != 1 || $profile_id != 10 || $profile_id != 20 || $profile_id != 21 || $profile_id != 22 || $profile_id != 23 || $profile_id != 24 || $profile_id != 25 || $profile_id != 26 || $profile_id != 30) {
		$partyCode = $data->code;
		$partyType = $data->partyType;
		$creteria = $data->creteria;
		$topartyCode = $data->topartyCode;
		$partyType = $_SESSION['party_type'];
		$sesion_party_code = $_SESSION['party_code'];
		$gender = $data->gender;
		$dob = $data->dob;
		$dob = date("Y-m-d", strtotime($dob));
		$BusinessType = $data->BusinessType;	
		
			if($partyType == "C") {
				if($creteria == "SP") {
					$query = "SELECT a.address1, a.address2, a.local_govt_id, a.state_id, a.loc_latitude, a.loc_longitude, d.outlet_name, ifNull(c.parent_code,'Self') as pcode, ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname, ifNull(a.block_date,' - ') as block_date, if(a.block_status = 'Y','Yes','No') as block_status, if(a.active = 'Y','Yes','No') as active, a.champion_code as code, a.champion_name as name, a.login_name as lname, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype, (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.create_user) as create_user, a.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.update_user) as update_user,a.update_time, concat(e.country_code,' - ',e.country_description) as country, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = a.block_reason_id) as block_reason_id, g.name as gvtname, h.name as state,a.zip_code, a.work_no, a.email, a.mobile_no, a.contact_person_name,if(c.applier_type='C','C - Champion',if(c.applier_type= 'A','A - Agent',if(c.applier_type='S','S - Sub Agent',if(c.applier_type='P','P - Personal',''))))  as atype, a.contact_person_mobile, a.tax_number,a.application_id,a.start_date, a.expiry_date, a.block_date, (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.user_id) as user,a.dob,a.gender,if(a.business_type='0','Pharmacy',if(a.business_type='1','Gas Station',if(a.business_type='2','Saloon',if(a.business_type='3','Groceries Stores',if(a.business_type='4','Super Market',if(a.business_type='5','Mobile Network Outlets',if(a.business_type='6','Restaurants',if(a.business_type='7','Hotels',if(a.business_type='8','Cyber Cafe',if(a.business_type='9','Post Office','Others')))))))))) as business_type FROM champion_info a , application_main c, application_info d , country e, local_govt_list g, state_list h WHERE h.state_id = a.state_id and g.local_govt_id = a.local_govt_id and e.country_id = a.country_id and  c.application_id = d.application_id and a.application_id = c.application_id and a.champion_code = '".$partyCode."'";
				}
				if($creteria == "TP") {
					$query = "SELECT a.address1, a.address2, a.local_govt_id, a.state_id, a.loc_latitude, a.loc_longitude, d.outlet_name, ifNull(c.parent_code,'Self') as pcode, ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname, ifNull(a.block_date,' - ') as block_date, if(a.block_status = 'Y','Yes','No') as block_status, if(a.active = 'Y','Yes','No') as active, a.agent_code as code, a.agent_name as name, a.login_name as lname, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype,if(a.parent_type='A','A - Agent',if(a.parent_type='C','C - Champion','None')) as ptype, (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.create_user) as create_user, a.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.update_user) as update_user,a.update_time, concat(e.country_code,' - ',e.country_description) as country, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = a.block_reason_id) as block_reason_id, g.name as gvtname, h.name as state,a.zip_code, a.work_no, a.email, a.mobile_no,if(c.applier_type='C','C - Champion',if(c.applier_type= 'A','A - Agent',if(c.applier_type='S','S - Sub Agent',if(c.applier_type='P','P - Personal',''))))  as atype, a.contact_person_name, a.contact_person_mobile, a.tax_number,a.application_id,a.start_date, a.expiry_date, a.block_date, (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM userWHERE user_id = a.user_id) as user,a.dob,a.gender,if(a.business_type='0','Pharmacy',if(a.business_type='1','Gas Station',if(a.business_type='2','Saloon',if(a.business_type='3','Groceries Stores',if(a.business_type='4','Super Market',if(a.business_type='5','Mobile Network Outlets',if(a.business_type='6','Restaurants',if(a.business_type='7','Hotels',if(a.business_type='8','Cyber Cafe',if(a.business_type='9','Post Office','Others')))))))))) as business_type  FROM agent_info a , application_main c, application_info d , country e, local_govt_list g, state_list h WHERE h.state_id = a.state_id and g.local_govt_id = a.local_govt_id and e.country_id = a.country_id and  c.application_id = d.application_id and a.application_id = c.application_id and a.parent_code='".$_SESSION['party_code']."' and a.agent_code = '".$partyCode."'";
				}
			}
		}
		if($partyType == "P") {
			$query = "SELECT d.outlet_name, ifNull(c.parent_code,'Self') as pcode, ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname, ifNull(a.block_date,' - ') as block_date, if(a.block_status = 'Y','Yes','No') as block_status, if(a.active = 'Y','Yes','No') as active, a.personal_code as code, a.personal_name as name, a.login_name as lname, if(a.party_category_type_id='1','BROZEN',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype, (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.create_user) as create_user, a.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.update_user) as update_user,a.update_time, concat(e.country_code,' - ',e.country_description) as country, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = a.block_reason_id) as block_reason_id, g.name as gvtname, h.name as state,a.zip_code, a.work_no, a.email, a.mobile_no, a.contact_person_name, a.contact_person_mobile, a.tax_number,a.application_id,a.start_date, a.expiry_date, a.block_date, (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.user_id) as user,a.dob,a.gender,if(a.business_type='0','Pharmacy',if(a.business_type='1','Gas Station',if(a.business_type='2','Saloon',if(a.business_type='3','Groceries Stores',if(a.business_type='4','Super Market',if(a.business_type='5','Mobile Network Outlets',if(a.business_type='6','Restaurants',if(a.business_type='7','Hotels',if(a.business_type='8','Cyber Cafe',if(a.business_type='9','Post Office','Others')))))))))) as business_type FROM personal_info a , application_main c, application_info d , country e, local_govt_list g, state_list h WHERE h.state_id = a.state_id and g.local_govt_id = a.local_govt_id and e.country_id = a.country_id and  c.application_id = d.application_id and a.application_id = c.application_id and a.personal_code = '".$partyCode."'";
		}
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("address1"=>$row['address1'],"address2"=>$row['address2'],"local_govt_id"=>$row['local_govt_id'],"state_id"=>$row['state_id'],"loc_latitude"=>$row['loc_latitude'], "loc_longitude"=>$row['loc_longitude'], "outlet_name"=>$row['outlet_name'],"pcode"=>$row['pcode'],"atype"=>$row['atype'],"parenroutletname"=>$row['parenroutletname'],"block_date"=>$row['block_date'],"block_status"=>$row['block_status'],"active"=>$row['active'],"code"=>$row['code'],"name"=>$row['name'],"lname"=>$row['lname'],"ptype"=>$row['ptype'],"partytype"=>$row['partytype'],"sub_agent"=>$row['sub_agent'],"create_user"=>$row['create_user'],"create_time"=>$row['create_time'],"update_user"=>$row['update_user'],"update_time"=>$row['update_time'],"country"=>$row['country'],"block_reason_id"=>$row['block_reason_id'],"gvtname"=>$row['gvtname'],"state"=>$row['state'],"zip_code"=>$row['zip_code'],"work_no"=>$row['work_no'],"email"=>$row['email'],"mobile_no"=>$row['mobile_no'],"contact_person_name"=>$row['contact_person_name'],"tax_number"=>$row['tax_number'],"user"=>$row['user'],"block_date"=>$row['block_date'],"start_date"=>$row['start_date'],"expiry_date"=>$row['expiry_date'],"application_id"=>$row['application_id'],"contact_person_mobile"=>$row['contact_person_mobile'],"dob"=>$row['dob'],"gender"=>$row['gender'],"BusinessType"=>$row['business_type']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			exit();
		}
	}
	else if($action == "update") {
		$partyCode = $data->partyCode;
		$mobile = $data->mobile;
		$email = $data->email;
		$cpname = $data->cpname;
		$cpmobile = $data->cpmobile;
		$address1 = $data->address1;
		$address2 = $data->address2;
		$loc_latitude = $data->loc_latitude;
		$loc_longitude = $data->loc_longitude;
		$local_govt_id = $data->local_govt_id;
		$active = $data->active;
		$gender = $data->gender;
		$dob = $data->dob;
		$BusinessType = $data->BusinessType;
		$dob = date("Y-m-d", strtotime($dob));
		$state_id = $data->state_id;
		$type = mb_substr($partyCode, 0, 1, "UTF-8");
		
		$query = "";
		$table_name = "";
		$ret_val = '';
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
		$query ="UPDATE $table_name SET state_id = $state_id, local_govt_id = $local_govt_id,dob = '$dob',gender = '$gender',business_type = '$BusinessType', loc_latitude = '$loc_latitude',active = '$active', loc_longitude = '$loc_longitude', address1 = '$address1', address2 = '$address2',  contact_person_name = '$cpname', contact_person_mobile = '$cpmobile', email = '$email', mobile_no = '$mobile' WHERE $col_name = '$partyCode'";
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