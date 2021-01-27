 <?php
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	$action		=  $data->action;
	$state	=  $data->state;
	$localgovernment	=  $data->localgovernment;	
	$active		=  $data->active;
	$agentCode		=  $data->agentCode;
	$championName	=  $_SESSION['party_code'];
	//$partytype	=  $data->partytype;
	if($action == "getreport") {
		if($agentCode == 'ALL'){
			if($state == "ALL") {
				if($active == "ALL"){
					$query ="select a.agent_code, a.agent_name, a.login_name, ifnull(a.parent_code, 'None') as parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id   and a.parent_code='$championName' order by a.agent_code";
				}else if($active == "Y") {
					$query ="select a.agent_code, a.agent_name, a.login_name, ifnull(a.parent_code, 'None') as parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.parent_code='$championName'  and a.active = 'Y' order by a.agent_code";
				}
				else{
					$query ="select a.agent_code, a.agent_name, a.login_name, ifnull(a.parent_code, 'None') as parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N' and a.parent_code='$championName'  order by a.agent_code";
				}

			}else{
				if($localgovernment=='ALL'){
					if($active == "ALL"){
						$query ="select a.agent_code, a.agent_name, a.login_name, ifnull(a.parent_code, 'None') as parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and a.parent_code='$championName'  order by a.agent_code";
					}else if($active == "Y") {
						$query ="select a.agent_code, a.agent_name, a.login_name, ifnull(a.parent_code, 'None') as parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and a.parent_code='$championName'  order by a.agent_code";
					}
					else{
						$query ="select a.agent_code, a.agent_name, a.login_name, ifnull(a.parent_code, 'None') as parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and a.parent_code='$championName'  order by a.agent_code";
					}
				
					
				}else{
					if($active == "ALL"){
						$query ="select a.agent_code, a.agent_name, a.login_name, ifnull(a.parent_code, 'None') as parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.parent_code='$championName'  order by a.agent_code";
					}else if($active == "Y") {
						$query ="select a.agent_code, a.agent_name, a.login_name, ifnull(a.parent_code, 'None') as parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.parent_code='$championName'  order by a.agent_code";
					}
					else{
						$query ="select a.agent_code, a.agent_name, a.login_name, ifnull(a.parent_code, 'None') as parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.parent_code='$championName'  order by a.agent_code";
					}
				}

			}
				
		}else{
			if($state == "ALL") {
				if($active == "ALL"){
					$query ="select a.agent_code, a.agent_name, a.login_name, ifnull(a.parent_code, 'None') as parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.parent_code='$championName'  and a.agent_code ='$agentCode'";
				}else if($active == "Y") {
					$query ="select a.agent_code, a.agent_name, a.login_name, ifnull(a.parent_code, 'None') as parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y' and a.parent_code='$championName'  and a.agent_code ='$agentCode'";
				}
				else{
					$query ="select a.agent_code, a.agent_name, a.login_name, ifnull(a.parent_code, 'None') as parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N' and a.parent_code='$championName'  and a.agent_code ='$agentCode'";
				}

			}else{
				if($localgovernment=='ALL'){
					if($active == "ALL"){
						$query ="select a.agent_code, a.agent_name, a.login_name, ifnull(a.parent_code, 'None') as parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and a.parent_code='$championName'  and a.agent_code ='$agentCode'";
					}else if($active == "Y") {
						$query ="select a.agent_code, a.agent_name, a.login_name, ifnull(a.parent_code, 'None') as parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and a.parent_code='$championName'  and a.agent_code ='$agentCode'";
					}
					else{
						$query ="select a.agent_code, a.agent_name, a.login_name, ifnull(a.parent_code, 'None') as parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and a.parent_code='$championName'  and a.agent_code ='$agentCode'";
					}
				
					
				}else{
					if($active == "ALL"){
						$query ="select a.agent_code, a.agent_name, a.login_name, ifnull(a.parent_code, 'None') as parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.parent_code='$championName'  and a.agent_code ='$agentCode'";
					}else if($active == "Y") {
						$query ="select a.agent_code, a.agent_name, a.login_name, ifnull(a.parent_code, 'None') as parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.parent_code='$championName'  and a.agent_code ='$agentCode'";
					}
					else{
						$query ="select a.agent_code, a.agent_name, a.login_name, ifnull(a.parent_code, 'None') as parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.parent_code='$championName'  and a.agent_code ='$agentCode'";
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
			$data[] = array("amtype"=>$ba, "agent_code"=>$row['agent_code'],"agent_name"=>$row['agent_name'],
						"login_name"=>$row['login_name'],"parent_code"=>$row['parent_code'],"parent_type"=>$row['parent_type'],
						"state"=>$row['state'],"local_govt"=>$row['local_govt'],"block_status"=>$row['block_status'],
						"active"=>$row['active']);           
		}
		echo json_encode($data);
	}
	
	else if($action == "view") {
		$agent_code	=  $data->agent_code;
				$query ="select a.agent_code, a.agent_name, a.login_name, ifnull(a.parent_code, 'None') as parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, i_format(d.available_balance) as available_balance, i_format(d.current_balance) as current_balance, i_format(d.advance_amount)as advance_amount, i_format(d.minimum_balance) as minimum_balance, i_format(d.daily_limit) as daily_limit, i_format(d.credit_limit) as credit_limit, b.name as state, c.name as local_govt, a.active, ifnull(a.block_status,'N') as block_status,a.contact_person_name,a.contact_person_mobile,ifNull(e.terminal_id,'-') as terminal_id from  state_list b, local_govt_list c, agent_wallet d,user_pos e right join agent_info a on e.user_id = a.user_id  where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.agent_code ='$agent_code'";				
		
			error_log($query);
		$result =  mysqli_query($con,$query);
		if(!$result) {
			die('app_view_view_result: ' . mysqli_error($con));
			echo "app_view_view_result - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("waltype"=>$ba, "agent_code"=>$row['agent_code'],"agent_name"=>$row['agent_name'],"login_name"=>$row['login_name'],
						"parent_code"=>$row['parent_code'],"parent_type"=>$row['parent_type'], "local_govt"=>$row['local_govt'],
						"available_balance"=>$row['available_balance'],"current_balance"=>$row['current_balance'],
						"advance_amount"=>$row['advance_amount'],"minimum_balance"=>$row['minimum_balance'],"daily_limit"=>$row['daily_limit'],
						"credit_limit"=>$row['credit_limit'],"state"=>$row['state'],"active"=>$row['active'],
						"block_status"=>$row['block_status'],"contact_person_name"=>$row['contact_person_name'],
						"contact_person_mobile"=>$row['contact_person_mobile'],"terminal_id"=>$row['terminal_id']);
				}
			echo json_encode($data);
		}
			
	}
?>