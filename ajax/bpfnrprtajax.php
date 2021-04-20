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
	$ba 	= $data->ba;
	$state	=  $data->state;
	//error_log($state );
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
		if($action == "getreport") {	
            if ($state == "ALL") {		
			if($type == "ALL") {       
				if($typeDetail == false) {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount,concat(c.name) as State  FROM bp_service_order a,agent_info b,state_list c WHERE a.user_id= b.user_id and b.state_id = c.state_id and date(a.date_time) between '$startDate' and '$endDate' group by  State  ,b.agent_code,b.agent_name, date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount ,concat(c.name) as State  FROM bp_service_order a,agent_info b,state_list c WHERE a.user_id= b.user_id and b.state_id = c.state_id and date(a.date_time) between '$startDate' and '$endDate' group by  State  ,b.agent_code,b.agent_name ,date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, b.agent_code,b.agent_name ,concat(c.name) as State  FROM bp_service_order a,agent_info b,state_list c WHERE a.user_id= b.user_id and b.state_id = c.state_id and date(a.date_time) between '$startDate' and '$endDate'   group by  State,b.agent_code,b.agent_name , date(a.date_time) order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra'){
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent, concat(c.name) as State  FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id=c.state_id and  a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' group by  State,b.parent_code, agent, date(a.date_time) order by  agent, date(a.date_time)";
							}
							if($ba == 'ta'){
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id= c.state_id and a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' group by  State,b.parent_code  , agent, date(a.date_time) order by  agent, date(a.date_time)";
							}
							if($ba == 'bo'){
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b ,state_list c WHERE b.state_id= c.state_id and  a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' group by  State,b.parent_code  , agent, date(a.date_time) order by  agent, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount,concat(c.name) as State FROM bp_service_order a, agent_info b, state_list c WHERE b.state_id = c.state_id and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by  State  , date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount,concat(c.name) as State  FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by  State  , date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id and  b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  State  , date(a.date_time) order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id  and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  State,b.parent_code  , agent, date(a.date_time) order by agent, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State  FROM bp_service_order a, agent_info b, state_list c WHERE  b.state_id = c.state_id  and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  State,b.parent_code  , agent, date(a.date_time) order by agent, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat (c.name) as State FROM bp_service_order a, agent_info b  state_list c WHERE b.state_id = c.state_id  and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  State,b.parent_code  , agent, date(a.date_time) order by agent, date(a.date_time)";
							}
						}
					}
				}
				else {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type',concat(c.name) as State FROM bp_service_order a,agent_info b, state_list c WHERE a.user_id = b.user_id and b.state_id = c.state_id  and  date(a.date_time) between '$startDate' and '$endDate' group by  State  , service_feature_code, date(a.date_time) order by service_feature_code,date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type',concat(c.name) as State FROM bp_service_order a,agent_info b, state_list c WHERE a.user_id = b.user_id  and b.state_id = c.state_id  and  date(a.date_time) between '$startDate' and '$endDate' group by  State  , service_feature_code, date(a.date_time) order by service_feature_code,date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type',concat(c.name) as State FROM bp_service_order a,agent_info b, state_list c WHERE a.user_id = b.user_id and    b.state_id = c.state_id  and  date(a.date_time) between '$startDate' and '$endDate' group by  State  , service_feature_code, date(a.date_time) order by service_feature_code,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b, state_list c WHERE b.state_id = c.state_id  and  date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by  State,b.parent_code  , a.service_feature_code, agent, date(a.date_time) order by  a.service_feature_code, agent, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id  and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by  State,b.parent_code  , a.service_feature_code, agent, date(a.date_time) order by  a.service_feature_code, agent, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id  and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by  State,b.parent_code  , a.service_feature_code, agent, date(a.date_time) order by  a.service_feature_code, agent, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type',concat(c.name) as State  FROM bp_service_order a, agent_info b,state_list c  WHERE b.state_id = c.state_id  and  b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  State  , a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type',concat(c.name) as State  FROM bp_service_order a, agent_info b,state_list c  WHERE  b.state_id = c.state_id  and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  State  , a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(total_amount) as total_amount, a.service_feature_code as 'Order Type',concat(c.name) as State  FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id  and  b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  State  , a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State  FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id  and date(a.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and a.user_id = b.user_id group by  State,b.parent_code  , a.service_feature_code, agent, date(date_time) order by  a.service_feature_code,agent, date(date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State  FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id  and date(a.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and a.user_id = b.user_id group by  State,b.parent_code  , a.service_feature_code, agent, date(date_time) order by  a.service_feature_code,agent, date(date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(request_amount) as request_amount, SUM(total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State  FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id  and date(a.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and a.user_id = b.user_id group by  State,b.parent_code  , a.service_feature_code, agent, date(date_time) order by  a.service_feature_code,agent, date(date_time)";
							}
						}
					}
				}
			}
			else {
				if($typeDetail == false) {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(date_time) as Date, SUM(request_amount) as request_amount,concat(c.name) as State FROM bp_service_order a,agent_info b,state_list c  WHERE  a.user_id = b.user_id and b.state_id = c.state_id and service_feature_code = '$type' and date(date_time) between '$startDate' and '$endDate'group by  date(date_time),State order by date(date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(date_time) as Date, SUM(total_amount) as total_amount,concat(c.name) as State FROM bp_service_order a,agent_info b,state_list c  WHERE  a.user_id = b.user_id and b.state_id = c.state_id and service_feature_code = '$type' and date(date_time) between '$startDate' and '$endDate'group by date(date_time),State order by date(date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(date_time) as Date, SUM(request_amount) as request_amount, SUM(total_amount) as total_amount,concat(c.name) as State FROM bp_service_order a,agent_info b,state_list c  WHERE  a.user_id = b.user_id and b.state_id = c.state_id and service_feature_code = '$type' and date(date_time) between '$startDate' and '$endDate'group by State,  date(date_time) order by date(date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(request_amount) as request_amount , concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id=c.state_id and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by  State,b.parent_code  , agent, date(a.date_time) order by  agent, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(total_amount) as total_amount , concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id=c.state_id and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by  State,b.parent_code  , agent, date(a.date_time) order by  agent, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(request_amount) as request_amount, SUM(total_amount) as total_amount , concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b ,state_list c WHERE b.state_id=c.state_id and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by  State,b.parent_code  , agent, date(a.date_time) order by  agent, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount,concat(c.name) as State  FROM bp_service_order a, agent_info b,state_list c WHERE  b.state_id=c.state_id and  b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'group by  State  , date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount,concat(c.name) as State    FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id=c.state_id and  b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'group by  State  , date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount , SUM(a.total_amount) as total_amount,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id=c.state_id and  b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'group by  State  , date(a.date_time) order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id=c.state_id and  date(a.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and a.service_feature_code = '$type' and a.user_id = b.user_id group by  State,b.parent_code  , agent, date(a.date_time) order by agent, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id=c.state_id and  date(a.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and a.service_feature_code = '$type' and a.user_id = b.user_id group by  State,b.parent_code  , agent, date(a.date_time) order by agent, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id=c.state_id and  date(a.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and a.service_feature_code = '$type' and a.user_id = b.user_id group by  State,b.parent_code  , agent, date(a.date_time) order by agent, date(a.date_time)";
							}
						}
					}
				}
				else {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(date_time) as Date, SUM(request_amount) as request_amount, service_feature_code as 'Order Type',concat(c.name) as State FROM bp_service_order a,agent_info b,state_list c WHERE a.user_id = b.user_id and b.state_id = c.state_id and  service_feature_code = '$type' and date(date_time) between '$startDate' and '$endDate' group by  service_feature_code,State, date(date_time) order by service_feature_code,date(date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(date_time) as Date, SUM(total_amount) as total_amount, service_feature_code as 'Order Type',concat(c.name) as State FROM bp_service_order a,agent_info b,state_list c WHERE a.user_id = b.user_id and b.state_id = c.state_id and service_feature_code = '$type' and date(date_time) between '$startDate' and '$endDate' group by   service_feature_code,State, date(date_time) order by service_feature_code,date(date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(date_time) as Date, SUM(request_amount) as request_amount, SUM(total_amount) as total_amount, service_feature_code as 'Order Type',concat(c.name) as State FROM bp_service_order a,agent_info b,state_list c WHERE a.user_id = b.user_id and b.state_id = c.state_id and service_feature_code = '$type' and date(date_time) between '$startDate' and '$endDate' group by  service_feature_code, date(date_time),State order by service_feature_code,date(date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id and  date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and a.user_id = b.user_id group by  State,b.parent_code  , a.service_feature_code, agent, date(a.date_time) order by  a.service_feature_code, agent, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b ,state_list c WHERE b.state_id = c.state_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and a.user_id = b.user_id group by  State,b.parent_code  , a.service_feature_code, agent, date(a.date_time) order by  a.service_feature_code, agent, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and a.user_id = b.user_id group by  State,b.parent_code  , a.service_feature_code, agent, date(a.date_time) order by  a.service_feature_code, agent, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type',concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c  WHERE b.state_id = c.state_id and b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by  State  , a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type',concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c  WHERE b.state_id = c.state_id and  b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by  State  , a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as request_amount, a.service_feature_code as 'Order Type',concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c  WHERE b.state_id = c.state_id and  b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by  State  , a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c  WHERE b.state_id = c.state_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and b.agent_code = '$agentName' and a.user_id = b.user_id group by  State,b.parent_code  , a.service_feature_code, agent, date(date_time) order by  a.service_feature_code,agent, date(date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b ,state_list c WHERE  b.state_id = c.state_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and b.agent_code = '$agentName' and a.user_id = b.user_id group by  State,b.parent_code  , a.service_feature_code, agent, date(date_time) order by  a.service_feature_code,agent, date(date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and b.agent_code = '$agentName' and a.user_id = b.user_id group by  State,b.parent_code  , a.service_feature_code, agent, date(date_time) order by  a.service_feature_code,agent, date(date_time)";
							}
						}
					}
				}
			}
			}
	 else  {		
			if($type == "ALL") {
				if($typeDetail == false) {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, b.agent_code,b.agent_name ,concat(c.name) as State  FROM bp_service_order a,agent_info b,state_list c WHERE a.user_id= b.user_id and b.state_id = c.state_id and date(a.date_time) between '$startDate' and '$endDate'and c.state_id=$state  group by  State,b.agent_code, b.agent_name  , date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, b.agent_code,b.agent_name ,concat(c.name) as State  FROM bp_service_order a,agent_info b,state_list c WHERE a.user_id= b.user_id and b.state_id = c.state_id and date(a.date_time) between '$startDate' and '$endDate'and c.state_id=$state  group by  State  ,b.agent_code, b.agent_name, date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, b.agent_code,b.agent_name ,concat(c.name) as State  FROM bp_service_order a,agent_info b,state_list c WHERE a.user_id= b.user_id and b.state_id = c.state_id and date(a.date_time) between '$startDate' and '$endDate'and c.state_id=$state  group by  State  ,b.agent_code, b.agent_name, date(a.date_time) order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra'){
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent, concat(c.name) as State  FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id=c.state_id and  a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' and c.state_id='$state'  group by  State,b.parent_code  , agent, date(a.date_time) order by  agent, date(a.date_time)";
							}
							if($ba == 'ta'){
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id= c.state_id and a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' and c.state_id='$state'  group by  State,b.parent_code  , agent, date(a.date_time) order by  agent, date(a.date_time)";
							}
							if($ba == 'bo'){
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b ,state_list c WHERE b.state_id= c.state_id and  a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate'and c.state_id='$state'  group by  State,b.parent_code  , agent, date(a.date_time) order by  agent, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount,concat(c.name) as State FROM bp_service_order a, agent_info b, state_list c WHERE b.state_id = c.state_id and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'   group by  State  , date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount,concat(c.name) as State  FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'   group by  State  , date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id and  b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'and c.state_id='$state'  group by  State  , date(a.date_time) order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id  and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  group by  State,b.parent_code  , agent, date(a.date_time) order by agent, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State  FROM bp_service_order a, agent_info b, state_list c WHERE  b.state_id = c.state_id  and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  group by  State,b.parent_code  , agent, date(a.date_time) order by agent, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat (c.name) as State FROM bp_service_order a, agent_info, b  state_list c WHERE b.state_id = c.state_id  and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  group by  State,b.parent_code  , agent, date(a.date_time) order by agent, date(a.date_time)";
							}
						}
					}
				}
				else {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type',concat(c.name) as State FROM bp_service_order a,agent_info b, state_list c WHERE b.state_id = c.state_id  and  date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  group by  State  , service_feature_code, date(a.date_time) order by service_feature_code,date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type',concat(c.name) as State FROM bp_service_order a,agent_info b, state_list c WHERE b.state_id = c.state_id  and  date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  group by  State  , service_feature_code, date(a.date_time) order by service_feature_code,date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type',concat(c.name) as State FROM bp_service_order a,agent_info b, state_list c WHERE  b.state_id = c.state_id  and  date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  group by  State  , service_feature_code, date(a.date_time) order by service_feature_code,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b, state_list c WHERE b.state_id = c.state_id  and  date(a.date_time) between '$startDate' and '$endDate'and c.state_id='$state'  and a.user_id = b.user_id group by  State,b.parent_code  , a.service_feature_code, agent, date(a.date_time) order by  a.service_feature_code, agent, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id  and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  and a.user_id = b.user_id group by  State,b.parent_code  , a.service_feature_code, agent, date(a.date_time) order by  a.service_feature_code, agent, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id  and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  and a.user_id = b.user_id group by  State,b.parent_code  , a.service_feature_code, agent, date(a.date_time) order by  a.service_feature_code, agent, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type',concat(c.name) as State  FROM bp_service_order a, agent_info b,state_list c  WHERE b.state_id = c.state_id  and  b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  group by  State  , a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type',concat(c.name) as State  FROM bp_service_order a, agent_info b,state_list c  WHERE  b.state_id = c.state_id  and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  group by  State  , a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(total_amount) as total_amount, a.service_feature_code as 'Order Type',concat(c.name) as State  FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id  and  b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  group by  State  , a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State  FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id  and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  and b.agent_code = '$agentName' and a.user_id = b.user_id group by  State,b.parent_code  , a.service_feature_code, agent, date(date_time) order by  a.service_feature_code,agent, date(date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State  FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id  and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  and b.agent_code = '$agentName' and a.user_id = b.user_id group by  State,b.parent_code  , a.service_feature_code, agent, date(date_time) order by  a.service_feature_code,agent, date(date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(request_amount) as request_amount, SUM(total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State  FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id  and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  and b.agent_code = '$agentName' and a.user_id = b.user_id group by  State,b.parent_code  , a.service_feature_code, agent, date(date_time) order by  a.service_feature_code,agent, date(date_time)";
							}
						}
					}
				}
			}
			else {
				if($typeDetail == false) {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount ,b.agent_code,b.agent_name ,concat(c.name) as State  FROM bp_service_order a,agent_info b,state_list c WHERE a.user_id= b.user_id and b.state_id = c.state_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state' group by  State  , date(a.date_time),b.agent_code,b.agent_name order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount ,b.agent_code,b.agent_name ,concat(c.name) as State  FROM bp_service_order a,agent_info b,state_list c WHERE a.user_id= b.user_id and b.state_id = c.state_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state' group by  State  , date(a.date_time),b.agent_code,b.agent_name order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,b.agent_code,b.agent_name ,concat(c.name) as State  FROM bp_service_order a,agent_info b,state_list c WHERE a.user_id= b.user_id and b.state_id = c.state_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state' group by  State  , date(a.date_time),b.agent_code,b.agent_name order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(request_amount) as request_amount , concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id=c.state_id and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state' and a.service_feature_code = '$type' group by  State,b.parent_code  , agent, date(a.date_time) order by  agent, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(total_amount) as total_amount , concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id=c.state_id and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  and a.service_feature_code = '$type' group by  State,b.parent_code  , agent, date(a.date_time) order by  agent, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(request_amount) as request_amount, SUM(total_amount) as total_amount , concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b ,state_list c WHERE b.state_id=c.state_id and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  and a.service_feature_code = '$type' group by  State,b.parent_code  , agent, date(a.date_time) order by  agent, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount,concat(c.name) as State  FROM bp_service_order a, agent_info b,state_list c WHERE  b.state_id=c.state_id and  b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  group by  State  , date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount,concat(c.name) as State    FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id=c.state_id and  b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  group by  State  , date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount , SUM(a.total_amount) as total_amount,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id=c.state_id and  b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  group by  State  , date(a.date_time) order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id=c.state_id and  date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  and b.agent_code = '$agentName' and a.service_feature_code = '$type' and a.user_id = b.user_id group by  State,b.parent_code  , agent, date(a.date_time) order by agent, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id=c.state_id and  date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  and b.agent_code = '$agentName' and a.service_feature_code = '$type' and a.user_id = b.user_id group by  State,b.parent_code  , agent, date(a.date_time) order by agent, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id=c.state_id and  date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  and b.agent_code = '$agentName' and a.service_feature_code = '$type' and a.user_id = b.user_id group by  State,b.parent_code  , agent, date(a.date_time) order by agent, date(a.date_time)";
							}
						}
					}
				}
				else {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(date_time) as Date, SUM(request_amount) as request_amount, service_feature_code as 'Order Type',concat(c.name) as State  FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id and  service_feature_code = '$type' and date(date_time) between '$startDate' and '$endDate' and c.state_id='$state'  group by  State  , service_feature_code, date(date_time) order by service_feature_code,date(date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(date_time) as Date, SUM(total_amount) as total_amount, service_feature_code as 'Order Type',concat(c.name) as State  FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id and service_feature_code = '$type' and date(date_time) between '$startDate' and '$endDate' and c.state_id='$state'  group by  State  , service_feature_code, date(date_time) order by service_feature_code,date(date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type',concat(c.name) as State FROM bp_service_order  a,agent_info b,state_list c WHERE b.state_id = c.state_id and  service_feature_code = '$type' and date(date_time) between '$startDate' and '$endDate' and c.state_id='$state'  group by  State  , service_feature_code, date(date_time) order by service_feature_code,date(date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id and  date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  and a.service_feature_code = '$type' and a.user_id = b.user_id group by  State,b.parent_code  , a.service_feature_code, agent, date(a.date_time) order by  a.service_feature_code, agent, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b ,state_list c WHERE b.state_id = c.state_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  and a.service_feature_code = '$type' and a.user_id = b.user_id group by  State,b.parent_code  , a.service_feature_code, agent, date(a.date_time) order by  a.service_feature_code, agent, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  and a.service_feature_code = '$type' and a.user_id = b.user_id group by  State,b.parent_code  , a.service_feature_code, agent, date(a.date_time) order by  a.service_feature_code, agent, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type',concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c  WHERE b.state_id = c.state_id and b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  group by  State  , a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type',concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c  WHERE b.state_id = c.state_id and  b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  group by  State  , a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as request_amount, a.service_feature_code as 'Order Type',concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c  WHERE b.state_id = c.state_id and  b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'  and c.state_id='$state'  group by  State  , a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c  WHERE b.state_id = c.state_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  and a.service_feature_code = '$type' and b.agent_code = '$agentName' and a.user_id = b.user_id group by  State,b.parent_code  , a.service_feature_code, agent, date(date_time) order by  a.service_feature_code,agent, date(date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b ,state_list c WHERE  b.state_id = c.state_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  and a.service_feature_code = '$type' and b.agent_code = '$agentName' and a.user_id = b.user_id group by  State,b.parent_code  , a.service_feature_code, agent, date(date_time) order by  a.service_feature_code,agent, date(date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM bp_service_order a, agent_info b,state_list c WHERE b.state_id = c.state_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id='$state'  and a.service_feature_code = '$type' and b.agent_code = '$agentName' and a.user_id = b.user_id group by  State,b.parent_code  , a.service_feature_code, agent, date(date_time) order by  a.service_feature_code,agent, date(date_time)";
							}
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
			$data[] = array("amtype"=>$ba,"agent"=>$row['agent'],"date"=>$row['Date'],"otype"=>$row['Order Type'],"state"=>$row['State'],"reamt"=>$row['request_amount'],"toamt"=>$row['total_amount'],"ad"=>$agentDetail,"td"=>$typeDetail);           
		}
		echo json_encode($data);
	}

?>