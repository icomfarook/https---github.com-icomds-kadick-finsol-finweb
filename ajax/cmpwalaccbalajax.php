 <?php
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	$action		=  $data->action;
	$state	=  $data->state;
	$localgovernment =  $data->localgovernment;	
	$active		=  $data->active;
	$ba	=  $data->ba;
	$partytype	=  $data->partytype;
	$championName	=  $_SESSION['party_code'];
/* 	$creteria 	= $data->creteria;
	$agentDetail 	= $data->agentDetail;	
	$typeDetail 	= $data->typeDetail;
	$ba 	= $data->ba;
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate)); */
	
	
	if($action == "getreport") {
		if($partytype=='A'){		
			if($state == "ALL") {
				if($active == "ALL"){
					if($ba=='aw'){
						$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, i_format(d.available_balance) as available_balance, i_format(d.current_balance) as current_balance, i_format(d.advance_amount) as advance_amount, i_format(d.minimum_balance) as minimum_balance, i_format(d.daily_limit) as daily_limit, i_format(d.credit_limit) as credit_limit, ifNull(d.last_tx_no, '-') as last_tx_no, ifNull(d.last_tx_amount, '-') as last_tx_amount, ifNull(d.last_tx_date, '-') as last_tx_date  from agent_info a, state_list b, local_govt_list c, agent_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.parent_code = '$championName' order by a.agent_code";
					}else{
						$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, i_format(d.available_balance) as available_balance, i_format(d.current_balance) as current_balance, i_format(d.advance_amount) as advance_amount, i_format(d.minimum_balance) as minimum_balance, i_format(d.daily_limit) as daily_limit, i_format(d.credit_limit) as credit_limit, ifNull(d.last_tx_no, '-') as last_tx_no, ifNull(d.last_tx_amount, '-') as last_tx_amount, ifNull(d.last_tx_date, '-') as last_tx_date from agent_info a, state_list b, local_govt_list c, agent_comm_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.parent_code = '$championName' order by a.agent_code";
					}
				}else {
					if($ba=='aw'){
						$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, i_format(d.available_balance) as available_balance, i_format(d.current_balance) as current_balance, i_format(d.advance_amount) as advance_amount, i_format(d.minimum_balance) as minimum_balance, i_format(d.daily_limit) as daily_limit, i_format(d.credit_limit) as credit_limit, ifNull(d.last_tx_no, '-') as last_tx_no, ifNull(d.last_tx_amount, '-') as last_tx_amount, ifNull(d.last_tx_date, '-') as last_tx_date  from agent_info a, state_list b, local_govt_list c, agent_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.active = '$active' and a.parent_code = '$championName'  order by a.agent_code";
					}else{
						$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, i_format(d.available_balance) as available_balance, i_format(d.current_balance) as current_balance, i_format(d.advance_amount) as advance_amount, i_format(d.minimum_balance) as minimum_balance, i_format(d.daily_limit) as daily_limit, i_format(d.credit_limit) as credit_limit, ifNull(d.last_tx_no, '-') as last_tx_no, ifNull(d.last_tx_amount, '-') as last_tx_amount, ifNull(d.last_tx_date, '-') as last_tx_date from agent_info a, state_list b, local_govt_list c, agent_comm_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = '$active' and a.parent_code = '$championName'  order by a.agent_code";
					}
				}

			}else{
				if($localgovernment=='ALL'){
					if($active == "ALL"){
						if($ba=='aw'){
							$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, i_format(d.available_balance) as available_balance, i_format(d.current_balance) as current_balance, i_format(d.advance_amount) as advance_amount, i_format(d.minimum_balance) as minimum_balance, i_format(d.daily_limit) as daily_limit, i_format(d.credit_limit) as credit_limit, ifNull(d.last_tx_no, '-') as last_tx_no, ifNull(d.last_tx_amount, '-') as last_tx_amount, ifNull(d.last_tx_date, '-') as last_tx_date  from agent_info a, state_list b, local_govt_list c, agent_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.state_id = '$state' and a.parent_code = '$championName'  order by a.agent_code";
						}else{
							$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, i_format(d.available_balance) as available_balance, i_format(d.current_balance) as current_balance, i_format(d.advance_amount) as advance_amount, i_format(d.minimum_balance) as minimum_balance, i_format(d.daily_limit) as daily_limit, i_format(d.credit_limit) as credit_limit, ifNull(d.last_tx_no, '-') as last_tx_no, ifNull(d.last_tx_amount, '-') as last_tx_amount, ifNull(d.last_tx_date, '-') as last_tx_date from agent_info a, state_list b, local_govt_list c, agent_comm_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.state_id = '$state' and a.parent_code = '$championName'  order by a.agent_code";
						}
					}else {
						if($ba=='aw'){
							$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, i_format(d.available_balance) as available_balance, i_format(d.current_balance) as current_balance, i_format(d.advance_amount) as advance_amount, i_format(d.minimum_balance) as minimum_balance, i_format(d.daily_limit) as daily_limit, i_format(d.credit_limit) as credit_limit, ifNull(d.last_tx_no, '-') as last_tx_no, ifNull(d.last_tx_amount, '-') as last_tx_amount, ifNull(d.last_tx_date, '-') as last_tx_date  from agent_info a, state_list b, local_govt_list c, agent_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.active = '$active' and a.state_id = '$state' and a.parent_code = '$championName'  order by a.agent_code";
						}else{
							$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, i_format(d.available_balance) as available_balance, i_format(d.current_balance) as current_balance, i_format(d.advance_amount) as advance_amount, i_format(d.minimum_balance) as minimum_balance, i_format(d.daily_limit) as daily_limit, i_format(d.credit_limit) as credit_limit, ifNull(d.last_tx_no, '-') as last_tx_no, ifNull(d.last_tx_amount, '-') as last_tx_amount, ifNull(d.last_tx_date, '-') as last_tx_date from agent_info a, state_list b, local_govt_list c, agent_comm_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = '$active' and a.state_id = '$state' and a.parent_code = '$championName'  order by a.agent_code";
						}
					}
					
				}else{
					if($active == "ALL"){
						if($ba=='aw'){
							$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, i_format(d.available_balance) as available_balance, i_format(d.current_balance) as current_balance, i_format(d.advance_amount) as advance_amount, i_format(d.minimum_balance) as minimum_balance, i_format(d.daily_limit) as daily_limit, i_format(d.credit_limit) as credit_limit, ifNull(d.last_tx_no, '-') as last_tx_no, ifNull(d.last_tx_amount, '-') as last_tx_amount, ifNull(d.last_tx_date, '-') as last_tx_date  from agent_info a, state_list b, local_govt_list c, agent_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.parent_code = '$championName'  order by a.agent_code";
						}else{
							$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, i_format(d.available_balance) as available_balance, i_format(d.current_balance) as current_balance, i_format(d.advance_amount) as advance_amount, i_format(d.minimum_balance) as minimum_balance, i_format(d.daily_limit) as daily_limit, i_format(d.credit_limit) as credit_limit, ifNull(d.last_tx_no, '-') as last_tx_no, ifNull(d.last_tx_amount, '-') as last_tx_amount, ifNull(d.last_tx_date, '-') as last_tx_date from agent_info a, state_list b, local_govt_list c, agent_comm_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.parent_code = '$championName'  order by a.agent_code";
						}
					}else {
						if($ba=='aw'){
							$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, i_format(d.available_balance) as available_balance, i_format(d.current_balance) as current_balance, i_format(d.advance_amount) as advance_amount, i_format(d.minimum_balance) as minimum_balance, i_format(d.daily_limit) as daily_limit, i_format(d.credit_limit) as credit_limit, ifNull(d.last_tx_no, '-') as last_tx_no, ifNull(d.last_tx_amount, '-') as last_tx_amount, ifNull(d.last_tx_date, '-') as last_tx_date  from agent_info a, state_list b, local_govt_list c, agent_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.active = '$active' and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.parent_code = '$championName'  order by a.agent_code";
						}else{
							$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, i_format(d.available_balance) as available_balance, i_format(d.current_balance) as current_balance, i_format(d.advance_amount) as advance_amount, i_format(d.minimum_balance) as minimum_balance, i_format(d.daily_limit) as daily_limit, i_format(d.credit_limit) as credit_limit, ifNull(d.last_tx_no, '-') as last_tx_no, ifNull(d.last_tx_amount, '-') as last_tx_amount, ifNull(d.last_tx_date, '-') as last_tx_date from agent_info a, state_list b, local_govt_list c, agent_comm_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = '$active' and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.parent_code = '$championName'  order by a.agent_code";
						}
					}
				}

			}
		}else{
			if($ba=='aw'){
						$query ="select DISTINCT a.champion_code, a.champion_name,  b.name as state, c.name as local_govt, i_format(d.available_balance) as available_balance, i_format(d.current_balance) as current_balance, i_format(d.advance_amount) as advance_amount, i_format(d.minimum_balance) as minimum_balance, i_format(d.daily_limit) as daily_limit, i_format(d.credit_limit) as credit_limit, ifNull(d.last_tx_no, '-') as last_tx_no, ifNull(d.last_tx_amount, '-') as last_tx_amount, ifNull(d.last_tx_date, '-') as last_tx_date  from champion_info a, state_list b, local_govt_list c, champion_wallet d where a.champion_code = d.champion_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and  a.champion_code = '$championName' order by a.champion_code";
					}else{
						$query = "select  DISTINCT a.champion_code, a.champion_name,  b.name as state, c.name as local_govt, i_format(d.available_balance) as available_balance, i_format(d.current_balance) as current_balance, i_format(d.advance_amount) as advance_amount, i_format(d.minimum_balance) as minimum_balance, i_format(d.daily_limit) as daily_limit, i_format(d.credit_limit) as credit_limit, ifNull(d.last_tx_no, '-') as last_tx_no, ifNull(d.last_tx_amount, '-') as last_tx_amount, ifNull(d.last_tx_date, '-') as last_tx_date from champion_info a, state_list b, local_govt_list c, champion_comm_wallet d where a.champion_code = d.champion_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and  a.champion_code = '$championName' order by a.champion_code;";
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
			$data[] = array("amtype"=>$ba,"partytype"=>$partytype,"agent_code"=>$row['agent_code'],"agent_name"=>$row['agent_name'],
						"champion_code"=>$row['champion_code'],"champion_name"=>$row['champion_name'],"state"=>$row['state'],
						"local_govt"=>$row['local_govt'],"available_balance"=>$row['available_balance'],"current_balance"=>$row['current_balance'],
						"advance_amount"=>$row['advance_amount'],"minimum_balance"=>$row['minimum_balance'],"daily_limit"=>$row['daily_limit'],
						"credit_limit"=>$row['credit_limit'],"last_tx_no"=>$row['last_tx_no'],"last_tx_amount"=>$row['last_tx_amount'],
						"last_tx_date"=>$row['last_tx_date']);           
		}
		echo json_encode($data);
	}
		else if($action == "view") {
		$ba	=  $data->ba;
		$agent_code	=  $data->agent_code;
		$champion_code	=  $data->champion_code;
		$partytype = $data->partytype;
		if($partytype=='A'){
				if($ba=='aw'){
					$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, i_format(d.available_balance) as available_balance, i_format(d.current_balance) as current_balance, i_format(d.advance_amount) as advance_amount, i_format(d.minimum_balance) as minimum_balance, i_format(d.daily_limit) as daily_limit, i_format(d.credit_limit) as credit_limit, ifNull(d.last_tx_no, '-') as last_tx_no, ifNull(d.last_tx_amount, '-') as last_tx_amount, ifNull(d.last_tx_date, '-') as last_tx_date  from agent_info a, state_list b, local_govt_list c, agent_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.agent_code = '$agent_code'";
				}else{
					$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, i_format(d.available_balance) as available_balance, i_format(d.current_balance) as current_balance, i_format(d.advance_amount) as advance_amount, i_format(d.minimum_balance) as minimum_balance, i_format(d.daily_limit) as daily_limit, i_format(d.credit_limit) as credit_limit, ifNull(d.last_tx_no, '-') as last_tx_no, ifNull(d.last_tx_amount, '-') as last_tx_amount, ifNull(d.last_tx_date, '-') as last_tx_date from agent_info a, state_list b, local_govt_list c, agent_comm_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.agent_code = '$agent_code'";
				}
		}
		else{
			if($ba=='aw'){
				$query ="select DISTINCT a.champion_code, a.champion_name,  b.name as state, c.name as local_govt, i_format(d.available_balance) as available_balance, i_format(d.current_balance) as current_balance, i_format(d.advance_amount) as advance_amount, i_format(d.minimum_balance) as minimum_balance, i_format(d.daily_limit) as daily_limit, i_format(d.credit_limit) as credit_limit, ifNull(d.last_tx_no, '-') as last_tx_no, ifNull(d.last_tx_amount, '-') as last_tx_amount, ifNull(d.last_tx_date, '-') as last_tx_date  from champion_info a, state_list b, local_govt_list c, champion_wallet d where a.champion_code = d.champion_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.champion_code = '$champion_code'";
			}else{
				$query ="select DISTINCT a.champion_code, a.champion_name,  b.name as state, c.name as local_govt, i_format(d.available_balance) as available_balance, i_format(d.current_balance) as current_balance, i_format(d.advance_amount) as advance_amount, i_format(d.minimum_balance) as minimum_balance, i_format(d.daily_limit) as daily_limit, i_format(d.credit_limit) as credit_limit, ifNull(d.last_tx_no, '-') as last_tx_no, ifNull(d.last_tx_amount, '-') as last_tx_amount, ifNull(d.last_tx_date, '-') as last_tx_date from champion_info a, state_list b, local_govt_list c, champion_comm_wallet d where a.champion_code = d.champion_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.champion_code = '$champion_code' ";
			}
			
		}
			error_log($query);
		$result =  mysqli_query($con,$query);
		if(!$result) {
			die('app_view_view_result: ' . mysqli_error($con));
			echo "app_view_view_result - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("waltype"=>$ba, "partytype"=>$partytype, "agent_code"=>$row['agent_code'],"agent_name"=>$row['agent_name'],"champion_code"=>$row['champion_code'],
						"champion_name"=>$row['champion_name'],"state"=>$row['state'], "local_govt"=>$row['local_govt'],
						"available_balance"=>$row['available_balance'],"current_balance"=>$row['current_balance'],
						"advance_amount"=>$row['advance_amount'],"minimum_balance"=>$row['minimum_balance'],"daily_limit"=>$row['daily_limit'],
						"credit_limit"=>$row['credit_limit'],"last_tx_no"=>$row['last_tx_no'],"last_tx_amount"=>$row['last_tx_amount'],
						"last_tx_date"=>$row['last_tx_date']);
				}
			echo json_encode($data);
		}
			
	}
?>