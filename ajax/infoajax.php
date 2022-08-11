<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');	
	require '../api/get_prime.php';
	require '../api/security.php';
	require '../common/gh/autoload.php';
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
					$query = "SELECT a.champion_code as party_code, concat(b.outlet_name,'(','Self',')') as party_name, a.login_name,ifNULL(if(b.bvn_validated = 'Y','Y-Yes',if(b.bvn_validated ='N','N-No','-')),'-') as Bvn FROM champion_info a, application_info b  WHERE    a.application_id = b.application_id and a.champion_code = '$partyCode'";
				}				
				if($creteria == "TP") {				
					$query = "SELECT a.agent_code as party_code, concat(b.outlet_name,'(','Self',')') as party_name, a.login_name,ifNULL(if(b.bvn_validated = 'Y','Y-Yes',if(b.bvn_validated ='N','N-No','-')),'-') as Bvn FROM agent_info a, application_info b  WHERE    a.application_id = b.application_id and a.agent_code = '$partyCode' and a.parent_code = '$sesion_party_code' ";
					if($partyCode == "ALL"){
					$query = "SELECT a.agent_code as party_code,if(a.sub_agent='Y',concat(a.agent_name,'[',ifNULL((select agent_name FROM agent_info WHERE agent_code = a.parent_code),'self'),']'),concat(a.agent_name,'[',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code),'self'),']')) as party_name, a.login_name,ifNULL(if(b.bvn_validated = 'Y','Y-Yes',if(b.bvn_validated ='N','N-No','-')),'-') as Bvn  FROM agent_info a,application_info b  WHERE     a.application_id = b.application_id and a.parent_code = '$sesion_party_code'";
					
				}
				} 
			}
			if($partyType == "P") {
				$query = "SELECT a.personal_code as party_code, concat(b.outlet_name,'(','Self',')') as party_name, a.login_name,ifNULL(if(b.bvn_validated = 'Y','Y-Yes',if(b.bvn_validated ='N','N-No','-')),'-') as Bvn FROM personal_info a, application_info b  WHERE    a.application_id = b.application_id and a.personal_code = '$partyCode'";
			}
			if($partyType == "A") {
				
				$query = "SELECT a.agent_code as party_code,if(a.sub_agent='Y',concat(a.agent_name,'[',ifNULL((select agent_name FROM agent_info WHERE agent_code = a.parent_code),'self'),']'),concat(a.agent_name,'[',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code),'self'),']')) as party_name, a.login_name,ifNULL(if(b.bvn_validated = 'Y','Y-Yes',if(b.bvn_validated ='N','N-No','-')),'-') as Bvn  FROM agent_info a,application_info b  WHERE     a.application_id = b.application_id and a.agent_code = '$partyCode'";
				if($creteria == "TP") {
					$partyType = "S";
					$query .=" and a.sub_agent = 'Y' and a.parent_code = '$sesion_party_code' ";
				}
			
			}
		}
		if($profile_id == 1 || $profile_id == 10 || $profile_id == 20 || $profile_id == 21 || $profile_id == 22 || $profile_id == 23 || $profile_id == 24 || $profile_id == 25 || $profile_id == 26 || $profile_id == 30) {
			$partyCode = $data->partyCode;
			$partyType = $data->partyType;
			$bvn = $data->bvn;
			
			
			if($partyType == "C") {
				$query = "SELECT a.champion_code as party_code, concat(b.outlet_name,'(','Self',')') as party_name, a.login_name,ifNULL(if(b.bvn_validated = 'Y','Y-Yes',if(b.bvn_validated ='N','N-No','-')),'-') as Bvn FROM champion_info a, application_info b  WHERE    a.application_id = b.application_id and a.champion_code = '$partyCode'";
			}
			if($partyType == "P") {
				$query = "SELECT a.personal_code as party_code, concat(b.outlet_name,'(','Self',')') as party_name, a.login_name,ifNULL(if(b.bvn_validated = 'Y','Y-Yes',if(b.bvn_validated ='N','N-No','-')),'-') as Bvn FROM personal_info a, application_info b  WHERE    a.application_id = b.application_id and a.personal_code = '$partyCode'";
			}
			if($partyType == "MA" || $partyType == "SA") {
				$query = "SELECT a.agent_code as party_code,b.outlet_name as party_name, a.login_name,ifNULL(if(b.bvn_validated = 'Y','Y-Yes',if(b.bvn_validated ='N','N-No','-')),'-') as Bvn FROM agent_info a,application_info b  WHERE     a.application_id = b.application_id and a.agent_code = '$partyCode'";
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
			$data[] = array("partyCode"=>$row['party_code'],"name"=>$row['party_name'],"lname"=>$row['login_name'],"bvn"=>$row['Bvn'],"partyType"=>$partyType);           
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
		$type = substr($partyCode, 0, 1);
		
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
		$address1 = mysqli_real_escape_string($con, $address1);
		$address2 = mysqli_real_escape_string($con, $address2);
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



	if($action == "getbvn") {		

		$partyCode = $data->partyCode;
		error_log("partyCode ==".$partyCode);
		$userId = $_SESSION['user_id'];
		$dob = date("Y-m-d", strtotime($dob));	


		$PreAppQuery = "SELECT d.pre_application_info_id,a.application_id,a.first_name,a.last_name, a.country_id, a.outlet_name, a.bvn, a.tax_number, a.address1, a.address2, a.local_govt_id, a.state_id, a.mobile_no, a.work_no, a.email, a.language_id, a.contact_person_name, a.contact_person_mobile,a.loc_latitude, a.loc_longitude,a.dob,a.gender,a.business_type FROM application_info a,agent_info b,user c,pre_application_info d WHERE a.application_id = b.application_id   and b.user_id = c.user_id  and a.application_id = d.application_id and b.agent_code ='$partyCode'";
		error_log("PreAppQuery ==".$PreAppQuery);
		$selectresult =  mysqli_query($con,$PreAppQuery);
			$row = mysqli_fetch_assoc($selectresult);
		    $Preid = $row['pre_application_info_id'];
			$AppId = $row['application_id'];
			$firstName = $row['first_name'];
	    	$lastName= $row['last_name'];
			$countryid = $row['country_id'];
			$dob = $row['dob'];
			$localgovernmentid = $row['local_govt_id'];
			$stateid = $row['state_id'];
			$phone = $row['mobile_no'];
			$bvn = $row['bvn'];
       		if($selectresult){
				$count = mysqli_num_rows($Selectresult);
				error_log($count);
				if($count == 0) { 
					$PreAppQuery1 = "SELECT a.application_id,a.first_name,a.last_name, a.country_id, a.outlet_name, a.bvn, a.tax_number, a.address1, a.address2, a.local_govt_id, a.state_id, a.mobile_no, a.work_no, a.email, a.language_id, a.contact_person_name, a.contact_person_mobile,a.loc_latitude, a.loc_longitude,a.dob,a.gender,a.business_type FROM application_info a,agent_info b,user c WHERE a.application_id = b.application_id   and b.user_id = c.user_id and b.agent_code ='$partyCode'";
					error_log("PreAppQuery1 ==".$PreAppQuery1);
					$selectresult1 =  mysqli_query($con,$PreAppQuery1);
					$row = mysqli_fetch_assoc($selectresult1);
					//$Preid = $row['pre_application_info_id'];
					$AppId = $row['application_id'];
					$firstName = $row['first_name'];
					$lastName= $row['last_name'];
					$countryid = $row['country_id'];
					$dob = $row['dob'];
					$localgovernmentid = $row['local_govt_id'];
					$stateid = $row['state_id'];
					$phone = $row['mobile_no'];
					$bvn = $row['bvn'];
				}

			$create_user = $_SESSION['user_id'];
			//error_log("AppId ==".$AppId);
			$get_sequence_number_query = "SELECT get_sequence_num(2200) as id";
			$get_sequence_number_result =  mysqli_query($con,$get_sequence_number_query);
			if(!$get_sequence_number_result) {
				error_log('Get sequnce number 2 failed: ' . mysqli_error($con));
				echo "GETSEQ - Failed";				
		}	
		else {
			$get_sequence_num_row = mysqli_fetch_assoc($get_sequence_number_result);
			$id = $get_sequence_num_row['id'];
			$reqMsg = "{bvn: ".$bvn.", firstName: ".$firstName.",lastname: ".$lastName.",dob:".$dob.",phone:".$phone."}";
			$query =  "INSERT INTO fin_non_trans_log (fin_non_trans_log_id, service_feature_id, bank_id,source,message_send_time, create_user, create_time, request_message ) VALUES ($id, 19,NULL,'F', now(), $create_user, now(), '$reqMsg')";
			error_log($query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				echo "Error: %s\n". mysqli_error($con);
			}
			else {
				$res = sendRequest($userId,$firstName,$lastName,$phone,$dob,$bvn,$stateid,$countryid,$localgovernmentid);
				$api_response = json_decode($res, true);
				$response_code = $api_response['responseCode'];
				$res_description = $api_response['responseDescription'];
				$description = $api_response['description'];
				$query1 = "UPDATE fin_non_trans_log SET response_message ='$res', message_receive_time = now(), response_received = 'Y', error_code = '$response_code', error_description = '$res_description' where fin_non_trans_log_id = $id ";                 
				$result = mysqli_query($con,$query1);
				error_log("After Success Response Update Que".$query1);

				 if($result) {
                    $SelectQuery = "select * from fin_non_trans_log where fin_non_trans_log_id= $id and response_message like '%VALID%' and error_code=0";
					error_log("SelectQuery ==".$SelectQuery);
					$Selectresult =  mysqli_query($con,$SelectQuery);
					$count = mysqli_num_rows($Selectresult);
						error_log($count);
						if($count > 0) { 
							$updateQuery ="update pre_application_info set bvn_validated='Y',trans_log_id=$id where pre_application_info_id = $Preid";
							error_log("updateQuery ==".$updateQuery);
							$UpdateResult = mysqli_query($con,$updateQuery);
				
							$update_app_query ="update application_info set bvn_validated='Y' where application_id = $AppId";
							error_log("update_app_query ==".$update_app_query);
							$update_app_result  = mysqli_query($con,$update_app_query);
						}
						error_log("Error in  Update BVN validation in Pre application Info table");
						
				  }
				  error_log("Error in After Success Response Update Query");
				 

			}
			error_log("Error in Sending Request");
		
		}
			
		
		error_log("respnse = ".$res);		
	}	
	echo $res;
	//error_log("Error in Select Pre Application Info Statment".$PreAppQuery);
}
		
	function sendRequest($userId,$firstName,$lastName,$phone,$dob,$bvn,$stateid,$countryid,$localgovernmentid) {	
		error_log("entering sendRequest");
		date_default_timezone_set('Africa/Lagos');
		$nday = date('z')+1;
		$nyear = date('Y');
		$nth_day_prime = get_prime($nday);
		$nth_year_day_prime = get_prime($nday+$nyear);
		$signature = $nday + $nth_day_prime;
		$tsec = time();
		$raw_data1 = FINAPI_SERVER_APP_PASSWORD.FINWEB_SERVER_SHORT_NAME."|".FINAPI_SERVER_APP_USERNAME.FINWEB_SERVER_SHORT_NAME."|".$tsec;
		error_log("raw_data1 = ".$raw_data1);	
		$key1 = base64_encode($raw_data1);
		error_log("before calling post");
		error_log("url = ".FINAPI_SERVER_BVN_CHECK_URL);		
		$body['countryId'] = $countryid;
		$body['stateId'] =  $stateid;
		$body['localGovtId'] =  $localgovernmentid;
		$body['userId'] = $userId;
		$body['firstName'] = $firstName;
		$body['lastName'] = $lastName;
		$body['phone'] = $phone;
		$body['dob'] = $dob;
		$body['bvn'] = $bvn;
		$body['key1'] = $key1;
		$body['signature'] = $signature;
		error_log("request sent ==> ".json_encode($body));
		$ch = curl_init(FINAPI_SERVER_BVN_CHECK_URL);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, FINAPI_SERVER_CONNECT_TIMEOUT);
		curl_setopt($ch, CURLOPT_TIMEOUT, FINAPI_SERVER_REQUEST_TIMEOUT);
		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		error_log("response received <== ".$response);
		error_log("code ".$httpcode);
		error_log("exiting sendRequest");
      	return $response;
	}
?>	