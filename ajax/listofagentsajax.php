 <?php
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	
	$partyType = $_SESSION['party_type'];
	$profileId = $_SESSION['profile_id'];
	$partyCode = $_SESSION['party_code'];
	$action		=  $data->action;
	$state	=  $data->state;
	$localgovernment	=  $data->localgovernment;	
	$active		=  $data->active;
	$agentCode		=  $data->agentCode;
	$bvn		=  $data->bvn;
		$rpartytype = $data->rpartytype;
		$championCode = $data->championCode;
	//$partytype	=  $data->partytype;
	if($action == "getreport") {
		if($rpartytype=='A'){	
		    if($bvn =="ALL"){
		
				if($agentCode == 'ALL'){
					if($state == "ALL") {
						if($active == "ALL"){
							$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id order by a.agent_code";
						}else if($active == "Y") {
							$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y' order by a.agent_code";
						}
						else{
							$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N' order by a.agent_code";
						}
		
					}else{
						if($localgovernment=='ALL'){
							if($active == "ALL"){
								$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' order by a.agent_code";
							}else if($active == "Y") {
								$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' order by a.agent_code";
							}
							else{
								$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' order by a.agent_code";
							}
						
							
						}else{
							if($active == "ALL"){
								$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info  where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' order by a.agent_code";
							}else if($active == "Y") {
								$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' order by a.agent_code";
							}
							else{
								$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and a.local_govt_id = '$localgovernment' order by a.agent_code";
							}
						}
		
					}
						
				}else{
					if($state == "ALL") {
						if($active == "ALL"){
							$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.agent_code ='$agentCode'";
						}else if($active == "Y") {
							$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y' and a.agent_code ='$agentCode'";
						}
						else{
							$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N' and a.agent_code ='$agentCode'";
						}
		
					}else{
						if($localgovernment=='ALL'){
							if($active == "ALL"){
								$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and a.agent_code ='$agentCode'";
							}else if($active == "Y") {
								$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and a.agent_code ='$agentCode'";
							}
							else{
								$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and a.agent_code ='$agentCode'";
							}
						
							
						}else{
							if($active == "ALL"){
								$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.agent_code ='$agentCode'";
							}else if($active == "Y") {
								$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.agent_code ='$agentCode'";
							}
							else{
								$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.agent_code ='$agentCode'";
							}
						}
		
					}
				}
				
	 } else{
		if($agentCode == 'ALL'){
			if($state == "ALL") {
				if($active == "ALL"){
					$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and p.bvn_validated = '$bvn' order by a.agent_code";
				}else if($active == "Y") {
					$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and p.bvn_validated = '$bvn' order by a.agent_code";
				}
				else{
					$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'  and p.bvn_validated = '$bvn' order by a.agent_code";
				}

			}else{
				if($localgovernment=='ALL'){
					if($active == "ALL"){
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state'  and p.bvn_validated = '$bvn' order by a.agent_code";
					}else if($active == "Y") {
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state'  and p.bvn_validated = '$bvn' order by a.agent_code";
					}
					else{
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state'  and p.bvn_validated = '$bvn' order by a.agent_code";
					}
				
					
				}else{
					if($active == "ALL"){
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and a.local_govt_id = '$localgovernment'  and p.bvn_validated = '$bvn' order by a.agent_code";
					}else if($active == "Y") {
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and a.local_govt_id = '$localgovernment'  and p.bvn_validated = '$bvn' order by a.agent_code";
					}
					else{
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and a.local_govt_id = '$localgovernment'  and p.bvn_validated = '$bvn' order by a.agent_code";
					}
				}

			}
				
		}else{
			if($state == "ALL") {
				if($active == "ALL"){
					$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.agent_code ='$agentCode' and p.bvn_validated = '$bvn' ";
				}else if($active == "Y") {
					$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.agent_code ='$agentCode' and p.bvn_validated = '$bvn' ";
				}
				else{
					$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'  and a.agent_code ='$agentCode' and p.bvn_validated = '$bvn' ";
				}

			}else{
				if($localgovernment=='ALL'){
					if($active == "ALL"){
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state'  and a.agent_code ='$agentCode' and p.bvn_validated = '$bvn' ";
					}else if($active == "Y") {
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state'  and a.agent_code ='$agentCode' and p.bvn_validated = '$bvn' ";
					}
					else{
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state'  and a.agent_code ='$agentCode' and p.bvn_validated = '$bvn' ";
					}
				
					
				}else{
					if($active == "ALL"){
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and a.local_govt_id = '$localgovernment'  and a.agent_code ='$agentCode' and p.bvn_validated = '$bvn' ";
					}else if($active == "Y") {
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and a.local_govt_id = '$localgovernment'  and a.agent_code ='$agentCode' and p.bvn_validated = '$bvn' ";
					}
					else{
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where   champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where a.application_id = p.application_id and  a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and a.local_govt_id = '$localgovernment'  and a.agent_code ='$agentCode' and p.bvn_validated = '$bvn' ";
					}
				}

			}
		}
		
		}
		}else{	
			if($bvn == "ALL"){
				if($championCode == 'ALL'){
					if($state == "ALL") {
						if($active == "ALL"){
							$query ="select a.champion_code, a.champion_name, a.login_name, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from champion_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  order by a.champion_code";
						}else if($active == "Y") {
							$query ="select a.champion_code, a.champion_name, a.login_name, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from champion_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  order by a.champion_code";
						}
						else{
							$query ="select a.champion_code, a.champion_name, a.login_name,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from champion_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N' order by a.champion_code";
						}
		
					}else{
						if($localgovernment=='ALL'){
							if($active == "ALL"){
								$query ="select a.champion_code, a.champion_name, a.login_name,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from champion_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' order by a.champion_code";
							}else if($active == "Y") {
								$query ="select a.champion_code, a.champion_name, a.login_name,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from champion_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' order by a.champion_code";
							}
							else{
								$query ="select a.champion_code, a.champion_name, a.login_name,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from champion_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' order by a.champion_code";
							}
						
							
						}else{
							if($active == "ALL"){
								$query ="select a.champion_code, a.champion_name, a.login_name,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from champion_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' order by a.champion_code";
							}else if($active == "Y") {
								$query ="select a.champion_code, a.champion_name, a.login_name,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from champion_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' order by a.champion_code";
							}
							else{
								$query ="select a.champion_code, a.champion_name, a.login_name,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from champion_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and a.local_govt_id = '$localgovernment' order by a.champion_code";
							}
						}
		
					}
						
				}else{
					if($state == "ALL") {
						if($active == "ALL"){
							$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where  a.application_id = p.application_id and champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.parent_code ='$championCode'";
						}else if($active == "Y") {
							$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,  b.name as state,ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where  a.application_id = p.application_id and champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y' and a.parent_code ='$championCode'";
						}
						else{
							$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where  a.application_id = p.application_id and champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N' and a.parent_code ='$championCode'";
						}
		
					}else{
						if($localgovernment=='ALL'){
							if($active == "ALL"){
								$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where  a.application_id = p.application_id and champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and a.parent_code ='$championCode'";
							}else if($active == "Y") {
								$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where  a.application_id = p.application_id and champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and a.parent_code ='$championCode'";
							}
							else{
								$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where  a.application_id = p.application_id and champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and a.parent_code ='$championCode'";
							}
						
							
						}else{
							if($active == "ALL"){
								$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where  a.application_id = p.application_id and champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.parent_code ='$championCode'";
							}else if($active == "Y") {
								$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where  a.application_id = p.application_id and champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.parent_code ='$championCode'";
							}
							else{
								$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where  a.application_id = p.application_id and champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.parent_code ='$championCode'";
							}
						}
		
					}
				}
				
	}else{
		if($championCode == 'ALL'){
			if($state == "ALL") {
				if($active == "ALL"){
					$query ="select a.champion_code, a.champion_name, a.login_name, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from champion_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and p.bvn_validated = '$bvn'  order by a.champion_code";
				}else if($active == "Y") {
					$query ="select a.champion_code, a.champion_name, a.login_name, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from champion_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and p.bvn_validated = '$bvn'  order by a.champion_code";
				}
				else{
					$query ="select a.champion_code, a.champion_name, a.login_name,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from champion_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N' and p.bvn_validated = '$bvn'  order by a.champion_code";
				}

			}else{
				if($localgovernment=='ALL'){
					if($active == "ALL"){
						$query ="select a.champion_code, a.champion_name, a.login_name,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from champion_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and p.bvn_validated = '$bvn'  order by a.champion_code";
					}else if($active == "Y") {
						$query ="select a.champion_code, a.champion_name, a.login_name,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from champion_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and p.bvn_validated = '$bvn'  order by a.champion_code";
					}
					else{
						$query ="select a.champion_code, a.champion_name, a.login_name,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from champion_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and p.bvn_validated = '$bvn'  order by a.champion_code";
					}
				
					
				}else{
					if($active == "ALL"){
						$query ="select a.champion_code, a.champion_name, a.login_name,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from champion_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and p.bvn_validated = '$bvn'  order by a.champion_code";
					}else if($active == "Y") {
						$query ="select a.champion_code, a.champion_name, a.login_name,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from champion_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and p.bvn_validated = '$bvn'  order by a.champion_code";
					}
					else{
						$query ="select a.champion_code, a.champion_name, a.login_name,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from champion_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and p.bvn_validated = '$bvn'  order by a.champion_code";
					}
				}

			}
				
		}else{
			if($state == "ALL") {
				if($active == "ALL"){
					$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where  a.application_id = p.application_id and champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and p.bvn_validated = '$bvn' and a.parent_code ='$championCode'";
				}else if($active == "Y") {
					$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,  b.name as state,ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where  a.application_id = p.application_id and champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and p.bvn_validated = '$bvn' and a.parent_code ='$championCode'";
				}
				else{
					$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where  a.application_id = p.application_id and champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'  and p.bvn_validated = '$bvn' and a.parent_code ='$championCode'";
				}

			}else{
				if($localgovernment=='ALL'){
					if($active == "ALL"){
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where  a.application_id = p.application_id and champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state'  and p.bvn_validated = '$bvn' and a.parent_code ='$championCode'";
					}else if($active == "Y") {
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where  a.application_id = p.application_id and champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state'  and p.bvn_validated = '$bvn' and a.parent_code ='$championCode'";
					}
					else{
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where  a.application_id = p.application_id and champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state'  and p.bvn_validated = '$bvn' and a.parent_code ='$championCode'";
					}
				
					
				}else{
					if($active == "ALL"){
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where  a.application_id = p.application_id and champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and a.local_govt_id = '$localgovernment'  and p.bvn_validated = '$bvn' and a.parent_code ='$championCode'";
					}else if($active == "Y") {
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where  a.application_id = p.application_id and champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and a.local_govt_id = '$localgovernment'  and p.bvn_validated = '$bvn' and a.parent_code ='$championCode'";
					}
					else{
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info where  a.application_id = p.application_id and champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,  b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,ifNULL(if(p.bvn_validated = 'Y','Y-Yes',if(p.bvn_validated ='N','N-No','-')),'-') as Bvn from agent_info a, state_list b, local_govt_list c,application_info p where  a.application_id = p.application_id and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and a.local_govt_id = '$localgovernment'  and p.bvn_validated = '$bvn' and a.parent_code ='$championCode'";
					}
				}

			}
		}
		
	}
		}
		if($profileId == 50) {
		if($agentCode == 'ALL'){
			if($state == "ALL") {
				if($active == "ALL"){
					$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.parent_code='$partyCode' order by a.agent_code";
					
				}else if($active == "Y") {
					$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y' and a.parent_code='$partyCode'  order by a.agent_code";
				}
				else{
					$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N' and a.parent_code='$partyCode'  order by a.agent_code";
				}

			}else{
				if($localgovernment=='ALL'){
					if($active == "ALL"){
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and a.parent_code='$partyCode'  order by a.agent_code";
					}else if($active == "Y") {
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and a.parent_code='$partyCode'  order by a.agent_code";
					}
					else{
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and a.parent_code='$partyCode'  order by a.agent_code";
					}
				
					
				}else{
					if($active == "ALL"){
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.parent_code='$partyCode'  order by a.agent_code";
					}else if($active == "Y") {
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.parent_code='$partyCode'  order by a.agent_code";
					}
					else{
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile  from agent_info a, state_list b, local_govt_list c where   a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.parent_code='$partyCode'  order by a.agent_code";
					}
				}

			}
				
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
			$data[] = array("amtype"=>$ba, "agent_code"=>$row['agent_code'],"agent_name"=>$row['agent_name'],"champion_code"=>$row['champion_code'],"champion_name"=>$row['champion_name'],
						"login_name"=>$row['login_name'],"parent_code"=>$row['parent_code'],"parent_type"=>$row['parent_type'],
						"state"=>$row['state'],"local_govt"=>$row['local_govt'],"block_status"=>$row['block_status'],
						"active"=>$row['active'],"email"=>$row['email'],"contact_person_mobile"=>$row['contact_person_mobile'],"bvn"=>$row['Bvn']);           
		}
		echo json_encode($data);
	}
	else if($action == "view") {
		$agent_code	=  $data->agent_code;
				$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, ifNULL(concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']'),'-') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, i_format(d.available_balance) as available_balance, i_format(d.current_balance) as current_balance, i_format(d.advance_amount)as advance_amount, i_format(d.minimum_balance) as minimum_balance, i_format(d.daily_limit) as daily_limit, i_format(d.credit_limit) as credit_limit, b.name as state, c.name as local_govt, a.active, ifnull(a.block_status,'N') as block_status,a.email ,a.contact_person_mobile,a.contact_person_name,a.contact_person_mobile,ifNull(e.terminal_id,'-') as terminal_id from  state_list b, local_govt_list c, agent_wallet d,user_pos e right join agent_info a on e.user_id = a.user_id  where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.agent_code ='$agent_code'";				
		
			error_log($query);
		$result =  mysqli_query($con,$query);
		if(!$result) {
			die('app_view_view_result: ' . mysqli_error($con));
			echo "app_view_view_result - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("waltype"=>$ba,"rpartytype"=>$rpartytype, "agent_code"=>$row['agent_code'],"agent_name"=>$row['agent_name'],"login_name"=>$row['login_name'],
						"parent_code"=>$row['parent_code'],"parent_type"=>$row['parent_type'], "local_govt"=>$row['local_govt'],
						"available_balance"=>$row['available_balance'],"current_balance"=>$row['current_balance'],
						"advance_amount"=>$row['advance_amount'],"minimum_balance"=>$row['minimum_balance'],"daily_limit"=>$row['daily_limit'],
						"credit_limit"=>$row['credit_limit'],"state"=>$row['state'],"active"=>$row['active'],
						"block_status"=>$row['block_status'],"contact_person_name"=>$row['contact_person_name'],
						"contact_person_mobile"=>$row['contact_person_mobile'],"terminal_id"=>$row['terminal_id'],"email"=>$row['email'],"bvn"=>$row['Bvn']);
				}
			echo json_encode($data);
		}
			
	}
?>