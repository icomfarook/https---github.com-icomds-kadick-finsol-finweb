<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input")); 
	
	$id   = $data->id;
	$agentCode    = $data->agentCode;
	$profile = $_SESSION['profile_id'];
	 $action = $data->action;
	 
	if($action == "query") {
				$upgrade_query = " select a.agent_code, a.agent_name, a.login_name, IFNULL(a.parent_code,'None') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c,application_main d where  d.application_id =  a.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and d.status='Z' and a.parent_code='$agentCode' order by a.agent_code";
		error_log("app_view_query == ".$upgrade_query);
		$upgrade_result =  mysqli_query($con,$upgrade_query);
		if(!$upgrade_result) {
			die('Get app_view_query : ' . mysqli_error($con));
			echo "app_view_query - Failed";				
		}
			$data = array();
			while ($row = mysqli_fetch_array($upgrade_result)) {
				$data[] = array("agent_code"=>$row['agent_code'],"agent_name"=>$row['agent_name'],"login_name"=>$row['login_name'],"parent_code"=>$row['parent_code'],"parent_type"=>$row['parent_type'],"state"=>$row['state'],"local_govt"=>$row['local_govt'],"block_status"=>$row['block_status'],
						"active"=>$row['active']);      
			}
			echo json_encode($data);
	}
	
	else if($action == "view") {
		$agent_code    = $data->agent_code;
		$upgrade_detail_query = "SELECT a.agent_code,a.agent_name, ifNULL(a.bvn,'-') as bvn ,a.address1, a.address2, a.local_govt_id, a.state_id, ifNULL(a.loc_latitude,'-') as loc_latitude, ifNULL(a.loc_longitude,'-') as loc_longitude, d.outlet_name, ifNull(c.parent_code,'Self') as pcode, ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname,ifNull(a.block_date,' - ') as block_date, if(a.block_status = 'Y','Yes','No') as block_status, if(a.active = 'Y','Yes','No') as active, a.agent_code as code, a.agent_name as name, a.login_name as lname, if(a.parent_type='C','Champion',if(a.parent_type= 'A','Agent',if(a.parent_type='S','Sub Agent',if(a.parent_type='P','Personal','')))) as ptype, if(a.party_category_type_id='1','BRONZE',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype, if(a.sub_agent = 'Y','Yes','No') as sub_agent,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.create_user) as create_user,a.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.update_user) as update_user,a.update_time, concat(e.country_code,' - ',e.country_description) as country,if(c.applier_type='C','C - Champion',if(c.applier_type= 'A','A - Agent',if(c.applier_type='S','S - Sub Agent',if(c.applier_type='P','P - Personal',''))))  as atype, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = a.block_reason_id) as block_reason_id, g.name as gvtname, h.name as state,a.zip_code, a.work_no, a.email, a.mobile_no, a.contact_person_name, a.contact_person_mobile, a.tax_number,a.application_id,ifNULL(a.start_date,'-') as start_date , ifNULL(a.expiry_date,'-') as expiry_date, ifNull(a.block_date,'-') as block_date,  (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.user_id) as user,ifNULL(a.dob,'-') as dob,ifNULL(a.gender,'-') as gender,if(a.business_type='0','Pharmacy',if(a.business_type='1','Gas Station',if(a.business_type='2','Saloon',if(a.business_type='3','Groceries Stores',if(a.business_type='4','Super Market',if(a.business_type='5','Mobile Network Outlets',if(a.business_type='6','Restaurants',if(a.business_type='7','Hotels',if(a.business_type='8','Cyber Cafe',if(a.business_type='9','Post Office','Others')))))))))) as business_type,if(a.group_type='P','P - Parent',if(a.group_type='C','C - Child','Others')) as group_type,ifNULL(a.group_id,'-') as group_id  FROM agent_info a , application_main c, application_info d , country e, local_govt_list g, state_list h WHERE h.state_id = a.state_id and g.local_govt_id = a.local_govt_id and e.country_id = a.country_id and  c.application_id = d.application_id and a.application_id = c.application_id and a.agent_code = '$agent_code'";
		error_log($upgrade_detail_query);
		$upgrade_detail_result=  mysqli_query($con,$upgrade_detail_query);
		if(!$upgrade_detail_result) {
			die('upgrade_detail_result: ' . mysqli_error($con));
			echo "upgrade_detail_result - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($upgrade_detail_result)) {
				$data[] = array("address1"=>$row['address1'],"address2"=>$row['address2'],"local_govt_id"=>$row['local_govt_id'],"state_id"=>$row['state_id'],"loc_latitude"=>$row['loc_latitude'], "loc_longitude"=>$row['loc_longitude'], "outlet_name"=>$row['outlet_name'],"pcode"=>$row['pcode'],"atype"=>$row['atype'],"parenroutletname"=>$row['parenroutletname'],"block_date"=>$row['block_date'],"block_status"=>$row['block_status'],"active"=>$row['active'],"code"=>$row['code'],"name"=>$row['name'],"lname"=>$row['lname'],"ptype"=>$row['ptype'],"partytype"=>$row['partytype'],"sub_agent"=>$row['sub_agent'],"create_user"=>$row['create_user'],"create_time"=>$row['create_time'],"update_user"=>$row['update_user'],"update_time"=>$row['update_time'],"country"=>$row['country'],"block_reason_id"=>$row['block_reason_id'],"gvtname"=>$row['gvtname'],"state"=>$row['state'],"zip_code"=>$row['zip_code'],"work_no"=>$row['work_no'],"email"=>$row['email'],"mobile_no"=>$row['mobile_no'],"contact_person_name"=>$row['contact_person_name'],"tax_number"=>$row['tax_number'],"user"=>$row['user'],"block_date"=>$row['block_date'],"start_date"=>$row['start_date'],"expiry_date"=>$row['expiry_date'],"application_id"=>$row['application_id'],"contact_person_mobile"=>$row['contact_person_mobile'],"dob"=>$row['dob'],"gender"=>$row['gender'],"BusinessType"=>$row['business_type'],"agent_code"=>$row['agent_code'],"agent_name"=>$row['agent_name'],"bvn"=>$row['bvn'],"group_id"=>$row['group_id'],"group_type"=>$row['group_type']);     
			}
			echo json_encode($data);
		}
			
	}
	

	else if($action == "update") {
		$agent_code = $data->agent_code;
				
		$select_query = "SELECT application_id FROM agent_info WHERE agent_code ='$agent_code'";
		error_log($select_query);
		$select_result = mysqli_query($con,$select_query);
		$row = mysqli_fetch_assoc($select_result);
		$application_id = $row['application_id'];
		error_log("application_id".$application_id);
		
		$query ="UPDATE agent_info SET group_id = $application_id, group_type = 'P' WHERE agent_code = '$agent_code'";
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