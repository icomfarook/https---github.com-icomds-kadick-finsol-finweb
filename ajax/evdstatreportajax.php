 <?php
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	$action		=  $data->action;
	$type	=  $data->type;
	$agentName	=  $data->agentName;	
	$startDate		=  $data->startDate;
	$endDate	=  $data->endDate;
	$creteria 	= $data->creteria;
	$agentDetail 	= $data->agentDetail;	
	$typeDetail 	= $data->typeDetail;
	$state 	= $data->state;
	$local_govt_id 	= $data->local_govt_id;
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
	$profileid = $_SESSION['profile_id'];
	$sesion_party_code = $_SESSION['party_code'];
		if($action == "getreport") {
			if ($state == "ALL"){
				if($type == "ALL") {
					if($typeDetail == false) {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and  date(c.date_time) between '$startDate' and '$endDate' group by date(c.date_time),State order by date(c.date_time)";
							}
							else {
								$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State  FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id  and  date(c.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(c.date_time),State,b.parent_code order by b.agent_name, date(c.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and b.agent_code = '$agentName'  and date(c.date_time) between '$startDate' and '$endDate' group by date(c.date_time),State order by date(c.date_time)";
							}
							else {
								$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and  b.agent_code = '$agentName' and date(c.date_time) between '$startDate' and '$endDate'   group by  State,b.parent_code ,b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
							}
						}
					}
					else {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$query ="SELECT date(b.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State FROM  evd_transaction b, operator c,agent_info d,state_list e WHERE b.operator_id = c.operator_id and b.user_id = d.user_id and d.state_id = e.state_id and date(b.date_time) between '$startDate' and '$endDate'   group by  State, b.operator_id, date(b.date_time) order by b.operator_id, date(b.date_time)";
							}
							else {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and date(d.date_time) between '$startDate' and '$endDate'    group by  State,b.parent_code, d.operator_id, agent, date(d.date_time) order by d.operator_id, agent, date(d.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and b.agent_code = '$agentName' and  date(d.date_time) between '$startDate' and '$endDate'   group by  State, d.operator_id, date(d.date_time) order by d.operator_id,date(d.date_time)";
							}
							else {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State  FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and date(d.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName'    group by  State,b.parent_code, d.operator_id, b.agent_name, date(d.date_time) order by d.operator_id,b.agent_name, date(d.date_time)";
							}
						}
					}
				}
				else {
					if($typeDetail == false) {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and and b.operator_id = '$type' and date(c.date_time) between '$startDate' and '$endDate'  group by  State, date(c.date_time) order by date(c.date_time)";
							}
							else {
								$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and date(c.date_time) between '$startDate' and '$endDate' and c.operator_id = '$type'   group by  State,b.parent_code, b.agent_name, date(c.date_time) order by  b.agent_name, date(c.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State  FROM  agent_info b, evd_transaction c, state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and b.agent_code = '$agentName'  and c.operator_id = '$type' and date(c.date_time) between '$startDate' and '$endDate'  group by  State, date(c.date_time) order by date(c.date_time)";
							}
							else {
								$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and date(c.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and c.operator_id = '$type'    group by  State,b.parent_code, b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
							}
						}
					}
					else {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State  FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  b.operator_id = '$type' and date(d.date_time) between '$startDate' and '$endDate'   group by  State, b.operator_id, date(d.date_time) order by b.operator_id, date(d.date_time)";
							}
							else {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,'[',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State  FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  date(d.date_time) between '$startDate' and '$endDate' and d.operator_id = '$type'    group by  State,b.parent_code, d.operator_id, b.agent_name, date(d.date_time) order by d.operator_id, b.agent_name, date(d.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and   b.agent_code = '$agentName' and d.operator_id  = '$type' and date(d.date_time) between '$startDate' and '$endDate'   group by  State, d.operator_id, date(d.date_time) order by d.operator_id,date(d.date_time)";
							}
							else {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  date(d.date_time) between '$startDate' and '$endDate' and d.operator_id  = '$type' and b.agent_code = '$agentName'    group by  State,b.parent_code, d.operator_id, b.agent_name, date(d.date_time) order by  d.operator_id,b.agent_name, date(d.date_time)";
							}
						}
					}
				}
			}
			
			//=========
				else {
				if($local_govt_id == ""){
					if($type == "ALL") {
					    if($typeDetail == false) {
							if($agentName == "ALL"){
							if($agentDetail == false) {
								$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and  d.state_id=$state and date(c.date_time) between '$startDate' and '$endDate' group by  State, date(c.date_time) order by date(c.date_time)";
							}
							else {
								$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State  FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and  d.state_id=$state and  date(c.date_time) between '$startDate' and '$endDate' group by  State,b.parent_code, b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and   b.agent_code = '$agentName' and d.state_id=$state and  date(c.date_time) between '$startDate' and '$endDate' group by  State, date(c.date_time) order by date(c.date_time)";
							}
							else {
								$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and  b.agent_code = '$agentName'  and d.state_id=$state and  date(c.date_time) between '$startDate' and '$endDate' group by  State, b.parent_code, b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
							}
						}
					}
					else {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State  FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and b.operator_id = c.operator_id and  e.state_id=$state and  date(d.date_time) between '$startDate' and '$endDate' group by  State, b.operator_id, date(d.date_time) order by b.operator_id, date(d.date_time)";
							}
							else {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and e.state_id=$state and  date(d.date_time) between '$startDate' and '$endDate'   group by  State,b.parent_code, d.operator_id, agent, date(d.date_time) order by d.operator_id, agent, date(d.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and b.agent_code = '$agentName'   e.state_id=$state and  date(d.date_time) between '$startDate' and '$endDate' group by  State, d.operator_id, date(d.date_time) order by d.operator_id,date(d.date_time)";
							}
							else {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State  FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  e.state_id=$state and  date(d.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName'  group by  State,b.parent_code, d.operator_id, b.agent_name, date(d.date_time) order by d.operator_id,b.agent_name, date(d.date_time)";
							}
						}
					}
				}
				else {
					if($typeDetail == false) {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and b.operator_id = '$type'  and d.state_id=$state and  date(c.date_time) between '$startDate' and '$endDate' group by  State, date(c.date_time) order by date(c.date_time)";
							}
							else {
								$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and d.state_id=$state and  date(c.date_time) between '$startDate' and '$endDate' and c.operator_id = '$type' group by  State,b.parent_code, b.agent_name, date(c.date_time) order by  b.agent_name, date(c.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State  FROM  agent_info b, evd_transaction c, state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and b.agent_code = '$agentName' and c.operator_id = '$type' and d.state_id=$state and date(c.date_time) between '$startDate' and '$endDate'group by  State,b.parent_code, date(c.date_time) order by date(c.date_time)";
							}
							else {
								$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and d.state_id=$state and date(c.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and c.operator_id = '$type'  group by  State,b.parent_code, b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
							}
						}
					}
					else {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State   FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  e.state_id=$state and b.operator_id = '$type' and date(d.date_time) between '$startDate' and '$endDate' group by  State, b.operator_id, date(d.date_time) order by b.operator_id, date(d.date_time)";
							}
							else {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,'[',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State  FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and   e.state_id=$state and   date(d.date_time) between '$startDate' and '$endDate' and d.operator_id = '$type' group by  State,b.parent_code, d.operator_id, b.agent_name, date(d.date_time) order by d.operator_id, b.agent_name, date(d.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  b.agent_code = '$agentName' and  e.state_id=$state and d.operator_id  = '$type' and date(d.date_time) between '$startDate' and '$endDate' group by  State, d.operator_id, date(d.date_time) order by d.operator_id,date(d.date_time)";
							}
							else {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  e.state_id=$state and  c.operator_id = d.operator_id and date(d.date_time) between '$startDate' and '$endDate' and d.operator_id  = '$type' and b.agent_code = '$agentName' group by  State,b.parent_code ,d.operator_id, b.agent_name, date(d.date_time) order by  d.operator_id,b.agent_name, date(d.date_time)";
							}
						}
					}
				}
					}else{
					if($type == "ALL") {
					if($typeDetail == false) {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and date(c.date_time) between '$startDate' and '$endDate'   group by  State, date(c.date_time) order by date(c.date_time)";
							}
							else {
								$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State  FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id'and  date(c.date_time) between '$startDate' and '$endDate'   group by  State,b.parent_code, b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and   b.agent_code = '$agentName' and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  date(c.date_time) between '$startDate' and '$endDate'   group by  State, date(c.date_time) order by date(c.date_time)";
							}
							else {
								$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and  b.agent_code = '$agentName'  and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  date(c.date_time) between '$startDate' and '$endDate'   group by  State, b.parent_code, b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
							}
						}
					}
					else {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State  FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and b.operator_id = c.operator_id and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  date(d.date_time) between '$startDate' and '$endDate'   group by  State, b.operator_id, date(b.date_time) order by b.operator_id, date(d.date_time)";
							}
							else {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  date(d.date_time) between '$startDate' and '$endDate'     group by  State,b.parent_code, d.operator_id, agent, date(d.date_time) order by d.operator_id, agent, date(d.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and b.agent_code = '$agentName'   and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  date(d.date_time) between '$startDate' and '$endDate'   group by  State, d.operator_id, date(d.date_time) order by d.operator_id,date(d.date_time)";
							}
							else {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State  FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  date(d.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName'    group by  State,b.parent_code, d.operator_id, b.agent_name, date(d.date_time) order by d.operator_id,b.agent_name, date(d.date_time)";
							}
						}
					}
				}
				else {
					if($typeDetail == false) {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and b.operator_id = '$type'  and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  date(c.date_time) between '$startDate' and '$endDate'  group by  State, date(c.date_time) order by date(c.date_time)";
							}
							else {
								$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  date(c.date_time) between '$startDate' and '$endDate' and c.operator_id = '$type'   group by  State,b.parent_code, b.agent_name, date(c.date_time) order by  b.agent_name, date(c.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State  FROM  agent_info b, evd_transaction c, state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and b.agent_code = '$agentName' and c.operator_id = '$type' and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and date(c.date_time) between '$startDate' and '$endDate'  group by  State,b.parent_code, date(c.date_time) order by date(c.date_time)";
							}
							else {
								$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and date(c.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and c.operator_id = '$type'    group by  State,b.parent_code, b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
							}
						}
					}
					else {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State   FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and b.operator_id = '$type' and date(d.date_time) between '$startDate' and '$endDate'   group by  State, b.operator_id, date(d.date_time) order by b.operator_id, date(d.date_time)";
							}
							else {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,'[',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State  FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and   b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and   date(d.date_time) between '$startDate' and '$endDate' and d.operator_id = '$type'   group by  State,b.parent_code, d.operator_id, b.agent_name, date(d.date_time) order by d.operator_id, b.agent_name, date(d.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  b.agent_code = '$agentName' and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and d.operator_id  = '$type' and date(d.date_time) between '$startDate' and '$endDate'   group by  State, d.operator_id, date(d.date_time) order by d.operator_id,date(d.date_time)";
							}
							else {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  c.operator_id = d.operator_id and date(d.date_time) between '$startDate' and '$endDate' and d.operator_id  = '$type' and b.agent_code = '$agentName'   group by  State,b.parent_code ,d.operator_id, b.agent_name, date(d.date_time) order by  d.operator_id,b.agent_name, date(d.date_time)";
							}
						}
					}
				}
			}
		}
		if($profileid ==51){
			if ($state == "ALL"){
						if($type == "ALL") {
							if($typeDetail == false) {
								if($agentName == "ALL"){
									if($agentDetail == false) {
										$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and  b.agent_code='$sesion_party_code' and  date(c.date_time) between '$startDate' and '$endDate' group by date(c.date_time),State order by date(c.date_time)";
									}
									else {
										$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State  FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id  and  b.agent_code='$sesion_party_code' and  date(c.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(c.date_time),State,b.parent_code order by b.agent_name, date(c.date_time)";
									}
								}
								else {
									if($agentDetail == false) {
										$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id  and b.agent_code='$sesion_party_code' and  date(c.date_time) between '$startDate' and '$endDate' group by date(c.date_time),State order by date(c.date_time)";
									}
									else {
										$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id  and b.agent_code='$sesion_party_code' and  date(c.date_time) between '$startDate' and '$endDate'   group by  State,b.parent_code ,b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
									}
								}
							}
							else {
								if($agentName == "ALL"){
									if($agentDetail == false) {
										$query ="SELECT date(b.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State FROM  evd_transaction b, operator c,agent_info d,state_list e WHERE b.operator_id = c.operator_id and b.user_id = d.user_id and d.state_id = e.state_id and  d.agent_code = '$sesion_party_code' and date(b.date_time) between '$startDate' and '$endDate'   group by  State, b.operator_id, date(b.date_time) order by b.operator_id, date(b.date_time)";
									}
									else {
										$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and b.agent_code='$sesion_party_code' and  date(d.date_time) between '$startDate' and '$endDate'    group by  State,b.parent_code, d.operator_id, agent, date(d.date_time) order by d.operator_id, agent, date(d.date_time)";
									}
								}
								else {
									if($agentDetail == false) {
										$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id  and  b.agent_code='$sesion_party_code' and  date(d.date_time) between '$startDate' and '$endDate'   group by  State, d.operator_id, date(d.date_time) order by d.operator_id,date(d.date_time)";
									}
									else {
										$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State  FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and b.agent_code='$sesion_party_code' and  date(d.date_time) between '$startDate' and '$endDate'     group by  State,b.parent_code, d.operator_id, b.agent_name, date(d.date_time) order by d.operator_id,b.agent_name, date(d.date_time)";
									}
								}
							}
						}
						else {
							if($typeDetail == false) {
								if($agentName == "ALL"){
									if($agentDetail == false) {
										$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and and b.operator_id = '$type' and b.agent_code='$sesion_party_code' and  date(c.date_time) between '$startDate' and '$endDate'  group by  State, date(c.date_time) order by date(c.date_time)";
									}
									else {
										$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and b.agent_code='$sesion_party_code' and  date(c.date_time) between '$startDate' and '$endDate' and c.operator_id = '$type'   group by  State,b.parent_code, b.agent_name, date(c.date_time) order by  b.agent_name, date(c.date_time)";
									}
								}
								else {
									if($agentDetail == false) {
										$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State  FROM  agent_info b, evd_transaction c, state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id   and c.operator_id = '$type' and b.agent_code='$sesion_party_code' and  date(c.date_time) between '$startDate' and '$endDate'  group by  State, date(c.date_time) order by date(c.date_time)";
									}
									else {
										$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and b.agent_code='$sesion_party_code' and  date(c.date_time) between '$startDate' and '$endDate'  and c.operator_id = '$type'    group by  State,b.parent_code, b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
									}
								}
							}
							else {
								if($agentName == "ALL"){
									if($agentDetail == false) {
										$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State  FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  b.operator_id = '$type' and b.agent_code='$sesion_party_code' and  date(d.date_time) between '$startDate' and '$endDate'   group by  State, b.operator_id, date(d.date_time) order by b.operator_id, date(d.date_time)";
									}
									else {
										$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,'[',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State  FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  b.agent_code='$sesion_party_code' and  date(d.date_time) between '$startDate' and '$endDate' and d.operator_id = '$type'    group by  State,b.parent_code, d.operator_id, b.agent_name, date(d.date_time) order by d.operator_id, b.agent_name, date(d.date_time)";
									}
								}
								else {
									if($agentDetail == false) {
										$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and   b.agent_code = '$agentName' and d.operator_id  = '$type' and b.agent_code='$sesion_party_code' and  date(d.date_time) between '$startDate' and '$endDate'   group by  State, d.operator_id, date(d.date_time) order by d.operator_id,date(d.date_time)";
									}
									else {
										$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  b.agent_code='$sesion_party_code' and  date(d.date_time) between '$startDate' and '$endDate' and d.operator_id  = '$type'     group by  State,b.parent_code, d.operator_id, b.agent_name, date(d.date_time) order by  d.operator_id,b.agent_name, date(d.date_time)";
									}
								}
							}
						}
					}
					
					//=========
						else {
						if($local_govt_id == ""){
							if($type == "ALL") {
								if($typeDetail == false) {
									if($agentName == "ALL"){
									if($agentDetail == false) {
										$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and  d.state_id=$state and b.agent_code='$sesion_party_code' and  date(c.date_time) between '$startDate' and '$endDate' group by  State, date(c.date_time) order by date(c.date_time)";
									}
									else {
										$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State  FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and  d.state_id=$state and  b.agent_code='$sesion_party_code' and  date(c.date_time) between '$startDate' and '$endDate' group by  State,b.parent_code, b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
									}
								}
								else {
									if($agentDetail == false) {
										$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and   b.agent_code = '$agentName' and d.state_id=$state and  b.agent_code='$sesion_party_code' and  date(c.date_time) between '$startDate' and '$endDate' group by  State, date(c.date_time) order by date(c.date_time)";
									}
									else {
										$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id   and d.state_id=$state and  b.agent_code='$sesion_party_code' and  date(c.date_time) between '$startDate' and '$endDate' group by  State, b.parent_code, b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
									}
								}
							}
							else {
								if($agentName == "ALL"){
									if($agentDetail == false) {
										$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State  FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and b.operator_id = c.operator_id and  e.state_id=$state and  b.agent_code='$sesion_party_code' and  date(d.date_time) between '$startDate' and '$endDate' group by  State, b.operator_id, date(d.date_time) order by b.operator_id, date(d.date_time)";
									}
									else {
										$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and e.state_id=$state and  b.agent_code='$sesion_party_code' and  date(d.date_time) between '$startDate' and '$endDate'   group by  State,b.parent_code, d.operator_id, agent, date(d.date_time) order by d.operator_id, agent, date(d.date_time)";
									}
								}
								else {
									if($agentDetail == false) {
										$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and b.agent_code = '$agentName'   e.state_id=$state and  b.agent_code='$sesion_party_code' and  date(d.date_time) between '$startDate' and '$endDate' group by  State, d.operator_id, date(d.date_time) order by d.operator_id,date(d.date_time)";
									}
									else {
										$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State  FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  e.state_id=$state and  b.agent_code='$sesion_party_code' and  date(d.date_time) between '$startDate' and '$endDate'   group by  State,b.parent_code, d.operator_id, b.agent_name, date(d.date_time) order by d.operator_id,b.agent_name, date(d.date_time)";
									}
								}
							}
						}
						else {
							if($typeDetail == false) {
								if($agentName == "ALL"){
									if($agentDetail == false) {
										$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and b.operator_id = '$type'  and d.state_id=$state and  b.agent_code='$sesion_party_code' and  date(c.date_time) between '$startDate' and '$endDate' group by  State, date(c.date_time) order by date(c.date_time)";
									}
									else {
										$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and d.state_id=$state and  b.agent_code='$sesion_party_code' and  date(c.date_time) between '$startDate' and '$endDate' and c.operator_id = '$type' group by  State,b.parent_code, b.agent_name, date(c.date_time) order by  b.agent_name, date(c.date_time)";
									}
								}
								else {
									if($agentDetail == false) {
										$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State  FROM  agent_info b, evd_transaction c, state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id  and c.operator_id = '$type' and d.state_id=$state and b.agent_code='$sesion_party_code' and  date(c.date_time) between '$startDate' and '$endDate'group by  State,b.parent_code, date(c.date_time) order by date(c.date_time)";
									}
									else {
										$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and d.state_id=$state and b.agent_code='$sesion_party_code' and  date(c.date_time) between '$startDate' and '$endDate'  and c.operator_id = '$type'  group by  State,b.parent_code, b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
									}
								}
							}
							else {
								if($agentName == "ALL"){
									if($agentDetail == false) {
										$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State   FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  e.state_id=$state and b.operator_id = '$type' and b.agent_code='$sesion_party_code' and  date(d.date_time) between '$startDate' and '$endDate' group by  State, b.operator_id, date(d.date_time) order by b.operator_id, date(d.date_time)";
									}
									else {
										$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,'[',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State  FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and   e.state_id=$state and   b.agent_code='$sesion_party_code' and  date(d.date_time) between '$startDate' and '$endDate' and d.operator_id = '$type' group by  State,b.parent_code, d.operator_id, b.agent_name, date(d.date_time) order by d.operator_id, b.agent_name, date(d.date_time)";
									}
								}
								else {
									if($agentDetail == false) {
										$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id  and  e.state_id=$state and d.operator_id  = '$type' and b.agent_code='$sesion_party_code' and  date(d.date_time) between '$startDate' and '$endDate' group by  State, d.operator_id, date(d.date_time) order by d.operator_id,date(d.date_time)";
									}
									else {
										$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  e.state_id=$state and  c.operator_id = d.operator_id and b.agent_code='$sesion_party_code' and  date(d.date_time) between '$startDate' and '$endDate' and d.operator_id  = '$type'  group by  State,b.parent_code ,d.operator_id, b.agent_name, date(d.date_time) order by  d.operator_id,b.agent_name, date(d.date_time)";
									}
								}
							}
						}
							}else{
							if($type == "ALL") {
							if($typeDetail == false) {
								if($agentName == "ALL"){
									if($agentDetail == false) {
										$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and b.agent_code='$sesion_party_code' and  date(c.date_time) between '$startDate' and '$endDate'   group by  State, date(c.date_time) order by date(c.date_time)";
									}
									else {
										$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State  FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id'and  b.agent_code='$sesion_party_code' and  date(c.date_time) between '$startDate' and '$endDate'   group by  State,b.parent_code, b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
									}
								}
								else {
									if($agentDetail == false) {
										$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and   b.agent_code = '$agentName' and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  b.agent_code='$sesion_party_code' and  date(c.date_time) between '$startDate' and '$endDate'   group by  State, date(c.date_time) order by date(c.date_time)";
									}
									else {
										$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id   and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  b.agent_code='$sesion_party_code' and  date(c.date_time) between '$startDate' and '$endDate'   group by  State, b.parent_code, b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
									}
								}
							}
							else {
								if($agentName == "ALL"){
									if($agentDetail == false) {
										$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State  FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and b.operator_id = c.operator_id and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  b.agent_code='$sesion_party_code' and  date(d.date_time) between '$startDate' and '$endDate'   group by  State, b.operator_id, date(b.date_time) order by b.operator_id, date(d.date_time)";
									}
									else {
										$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  b.agent_code='$sesion_party_code' and  date(d.date_time) between '$startDate' and '$endDate'     group by  State,b.parent_code, d.operator_id, agent, date(d.date_time) order by d.operator_id, agent, date(d.date_time)";
									}
								}
								else {
									if($agentDetail == false) {
										$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and b.agent_code = '$agentName'   and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  b.agent_code='$sesion_party_code' and  date(d.date_time) between '$startDate' and '$endDate'   group by  State, d.operator_id, date(d.date_time) order by d.operator_id,date(d.date_time)";
									}
									else {
										$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State  FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  b.agent_code='$sesion_party_code' and  date(d.date_time) between '$startDate' and '$endDate'     group by  State,b.parent_code, d.operator_id, b.agent_name, date(d.date_time) order by d.operator_id,b.agent_name, date(d.date_time)";
									}
								}
							}
						}
						else {
							if($typeDetail == false) {
								if($agentName == "ALL"){
									if($agentDetail == false) {
										$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and b.operator_id = '$type'  and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  b.agent_code='$sesion_party_code' and  date(c.date_time) between '$startDate' and '$endDate'  group by  State, date(c.date_time) order by date(c.date_time)";
									}
									else {
										$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  b.agent_code='$sesion_party_code' and  date(c.date_time) between '$startDate' and '$endDate' and c.operator_id = '$type'   group by  State,b.parent_code, b.agent_name, date(c.date_time) order by  b.agent_name, date(c.date_time)";
									}
								}
								else {
									if($agentDetail == false) {
										$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State  FROM  agent_info b, evd_transaction c, state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id  and c.operator_id = '$type' and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and b.agent_code='$sesion_party_code' and  date(c.date_time) between '$startDate' and '$endDate'  group by  State,b.parent_code, date(c.date_time) order by date(c.date_time)";
									}
									else {
										$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and b.agent_code='$sesion_party_code' and  date(c.date_time) between '$startDate' and '$endDate'  and c.operator_id = '$type'    group by  State,b.parent_code, b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
									}
								}
							}
							else {
								if($agentName == "ALL"){
									if($agentDetail == false) {
										$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State   FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and b.operator_id = '$type' and b.agent_code='$sesion_party_code' and  date(d.date_time) between '$startDate' and '$endDate'   group by  State, b.operator_id, date(d.date_time) order by b.operator_id, date(d.date_time)";
									}
									else {
										$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,'[',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State  FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and   b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and   b.agent_code='$sesion_party_code' and  date(d.date_time) between '$startDate' and '$endDate' and d.operator_id = '$type'   group by  State,b.parent_code, d.operator_id, b.agent_name, date(d.date_time) order by d.operator_id, b.agent_name, date(d.date_time)";
									}
								}
								else {
									if($agentDetail == false) {
										$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id  and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and d.operator_id  = '$type' and b.agent_code='$sesion_party_code' and  date(d.date_time) between '$startDate' and '$endDate'   group by  State, d.operator_id, date(d.date_time) order by d.operator_id,date(d.date_time)";
									}
									else {
										$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  c.operator_id = d.operator_id and b.agent_code='$sesion_party_code' and  date(d.date_time) between '$startDate' and '$endDate' and d.operator_id  = '$type'    group by  State,b.parent_code ,d.operator_id, b.agent_name, date(d.date_time) order by  d.operator_id,b.agent_name, date(d.date_time)";
									}
								}
							}
						}
					}
				}
			}
		error_log("$query = ".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("agent"=>$row['agent'],"date"=>$row['Date'],"operator"=>$row['Operator'],"state"=>$row['State'],"count"=>$row['Count'],"user"=>$row['user'],"ad"=>$agentDetail,"td"=>$typeDetail);           
		}
		echo json_encode($data);
	}

?>