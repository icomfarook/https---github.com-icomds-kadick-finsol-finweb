 <?php
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	$action		=  $data->action;
	$type	=  $data->type;
	$state	=  $data->state;
	$agentName	=  $data->agentName;	
	$startDate		=  $data->startDate;
	$endDate	=  $data->endDate;
	$creteria 	= $data->creteria;
	$agentDetail 	= $data->agentDetail;	
	$typeDetail 	= $data->typeDetail;
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
		if($action == "getreport") {
			if($state == "ALL"){
				if($type == "ALL") {
					if($typeDetail == false) {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State  FROM acc_service_order a, acc_request b,agent_info c,state_list d WHERE a.user_id = c.user_id and c.state_id=d.state_id and a.acc_service_order_no = b.order_no and b.status = 'S' and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time),State order by date(a.date_time)";
							}
							else {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent FROM acc_service_order a, agent_info b, acc_request c,state_list d WHERE b.state_id = d.state_id and c.order_no = a.acc_service_order_no and c.status = 'S' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time),b.parent_code, State order by b.agent_name, date(a.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State,concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent FROM acc_service_order a, agent_info b, acc_request c,state_list d  WHERE b.state_id = d.state_id and c.order_no = a.acc_service_order_no and c.status = 'S' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time),State order by date(a.date_time)";
							}
							else {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent FROM acc_service_order a, agent_info b, acc_request c,state_list d WHERE b.state_id = d.state_id and a.acc_service_order_no = c.order_no and c.status = 'S' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, b.parent_code, date(a.date_time),State order by b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type',concat(e.name) as State FROM acc_service_order a, acc_request b, service_feature c,agent_info d,state_list e WHERE a.user_id = d.user_id and d.state_id = e.state_id and a.acc_service_order_no = b.order_no and b.status = 'S' and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) ,State order by a.service_feature_code, date(a.date_time)";
							}
							else {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State FROM acc_service_order a, agent_info b, service_feature c, acc_request d,state_list e WHERE b.state_id = e.state_id and a.acc_service_order_no = d.order_no and d.status = 'S' and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by b.parent_code, a.service_feature_code, agent, date(a.date_time),State order by a.service_feature_code, agent, date(date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type',concat(e.name) as State FROM acc_service_order a, agent_info b, service_feature c, acc_request d,state_list e WHERE b.state_id=e.state_id and a.acc_service_order_no = d.order_no and d.status = 'S' and a.service_feature_code = c.feature_code and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  State  ,a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							else {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State FROM acc_service_order a, agent_info b, service_feature c, acc_request d,state_list e WHERE b.state_id = e.state_id and a.acc_service_order_no = d.order_no and d.status = 'S' and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and a.user_id = b.user_id group by b.parent_code,  State  ,a.service_feature_code, b.agent_name, date(date_time) order by a.service_feature_code,b.agent_name, date(date_time)";
							}
						}
					}
				}
				else {
					if($typeDetail == false) {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State FROM acc_service_order a, acc_request b,agent_info c,state_list d WHERE a.user_id = c.user_id and c.state_id = d.state_id and a.acc_service_order_no = b.order_no and b.status = 'S' and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'group by  State  ,date(a.date_time) order by date(a.date_time)";
							}
							else {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM acc_service_order a, agent_info b, acc_request c,state_list d WHERE b.state_id=d.state_id and a.acc_service_order_no = c.order_no and c.status = 'S' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by b.parent_code,  State  ,b.agent_name, date(date_time) order by  b.agent_name, date(date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State FROM acc_service_order a, agent_info b, acc_request c,state_list d WHERE b.state_id= d.state_id and  a.acc_service_order_no = c.order_no and c.status = 'S' and b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'group by  State  ,date(a.date_time) order by date(a.date_time)";
							}
							else {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM acc_service_order a, agent_info b, acc_request c,state_list d WHERE b.state_id=d.state_id and a.acc_service_order_no = c.order_no and c.status = 'S' and date(a.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and a.service_feature_code = '$type' and a.user_id = b.user_id group by b.parent_code,  State  ,b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(e.name) as State FROM acc_service_order a, acc_request b, service_feature c,agent_info d,state_list e WHERE a.user_id= d.user_id and d.state_id=e.state_id and a.acc_service_order_no = b.order_no and b.status = 'S' and a.service_feature_code = c.feature_code and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by  State  ,a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
							}
							else {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(b.agent_name,'[',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State FROM acc_service_order a, agent_info b, service_feature c, acc_request d,state_list e WHERE b.state_id = e.state_id and a.acc_service_order_no = d.order_no and d.status = 'S' and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and a.user_id = b.user_id group by b.parent_code,   State  ,a.service_feature_code, b.agent_name, date(date_time) order by a.service_feature_code, b.agent_name, date(date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(e.name) as State FROM acc_service_order a, agent_info b, service_feature c, acc_request d,state_list e WHERE b.state_id = e.state_id  and a.acc_service_order_no = d.order_no and d.status = 'S' and a.service_feature_code = c.feature_code and b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by  State  ,a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							else {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State FROM acc_service_order a, agent_info b, service_feature c, acc_request d,state_list e WHERE b.state_id = e.state_id and a.acc_service_order_no = d.order_no and d.status = 'S' and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and b.agent_code = '$agentName' and a.user_id = b.user_id group by b.parent_code,   State  ,a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
							}
						}
					}
				}
				
			}	
			//========================
			else {
				if($type == "ALL") {
					if($typeDetail == false) {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State  FROM acc_service_order a, acc_request b,agent_info c,state_list d WHERE a.user_id = c.user_id and c.state_id=d.state_id and a.acc_service_order_no = b.order_no and b.status = 'S' and  d.state_id=$state and date(a.date_time) between '$startDate' and '$endDate' group by  State  ,date(a.date_time) order by date(a.date_time)";
							}
							else {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent FROM acc_service_order a, agent_info b, acc_request c,state_list d WHERE b.state_id = d.state_id and c.order_no = a.acc_service_order_no and c.status = 'S' and d.state_id=$state  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.parent_code,  State  ,b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State FROM acc_service_order a, agent_info b, acc_request c,state_list d  WHERE b.state_id = d.state_id and c.order_no = a.acc_service_order_no and c.status = 'S' and  d.state_id=$state and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  State  ,date(a.date_time) order by date(a.date_time)";
							}
							else {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent FROM acc_service_order a, agent_info b, acc_request c,state_list d WHERE b.state_id = d.state_id and a.acc_service_order_no = c.order_no and c.status = 'S' and  d.state_id=$state and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.parent_code,   State  ,b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type',concat(e.name) as State FROM acc_service_order a, acc_request b, service_feature c,agent_info d,state_list e WHERE a.user_id = d.user_id and d.state_id = e.state_id and a.acc_service_order_no = b.order_no and b.status = 'S' and  e.state_id=$state and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate' group by  State  ,a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
							}
							else {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State FROM acc_service_order a, agent_info b, service_feature c, acc_request d,state_list e WHERE b.state_id = e.state_id and a.acc_service_order_no = d.order_no and d.status = 'S' and  e.state_id=$state  and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by b.parent_code,   State  ,a.service_feature_code, agent, date(date_time) order by a.service_feature_code, agent, date(date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type',concat(e.name) as State FROM acc_service_order a, agent_info b, service_feature c, acc_request d,state_list e WHERE b.state_id=e.state_id and a.acc_service_order_no = d.order_no and d.status = 'S' and  e.state_id=$state and a.service_feature_code = c.feature_code and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  State  ,a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							else {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State FROM acc_service_order a, agent_info b, service_feature c, acc_request d,state_list e WHERE b.state_id = e.state_id and a.acc_service_order_no = d.order_no and d.status = 'S' and  e.state_id=$state and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and a.user_id = b.user_id group by b.parent_code, State  ,a.service_feature_code, b.agent_name, date(date_time) order by a.service_feature_code,b.agent_name, date(date_time)";
							}
						}
					}
				}
				else {
					if($typeDetail == false) {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State FROM acc_service_order a, acc_request b,agent_info c,state_list d WHERE a.user_id = c.user_id and c.state_id = d.state_id and a.acc_service_order_no = b.order_no and b.status = 'S' and  d.state_id=$state  and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'group by  State  ,date(a.date_time) order by date(a.date_time)";
							}
							else {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM acc_service_order a, agent_info b, acc_request c,state_list d WHERE b.state_id=d.state_id and a.acc_service_order_no = c.order_no and c.status = 'S' and  d.state_id=$state and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by  State  ,b.agent_name, date(a.date_time),b.parent_code order by  b.agent_name, date(date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State FROM acc_service_order a, agent_info b, acc_request c,state_list d WHERE b.state_id= d.state_id and  a.acc_service_order_no = c.order_no and c.status = 'S' and  d.state_id=$state and b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'group by  State  ,date(a.date_time) order by date(a.date_time)";
							}
							else {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM acc_service_order a, agent_info b, acc_request c,state_list d WHERE b.state_id=d.state_id and a.acc_service_order_no = c.order_no and c.status = 'S' and  d.state_id=$state and date(a.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and a.service_feature_code = '$type' and a.user_id = b.user_id group by  State,b.parent_code  ,b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(e.name) as State FROM acc_service_order a, acc_request b, service_feature c,agent_info d,state_list e WHERE a.user_id= d.user_id and d.state_id=e.state_id and a.acc_service_order_no = b.order_no and b.status = 'S' and  e.state_id=$state and a.service_feature_code = c.feature_code and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by  State  ,a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
							}
							else {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(b.agent_name,'[',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State FROM acc_service_order a, agent_info b, service_feature c, acc_request d,state_list e WHERE b.state_id = e.state_id and a.acc_service_order_no = d.order_no and d.status = 'S' and  e.state_id=$state and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and a.user_id = b.user_id group by  State, b.parent_code ,a.service_feature_code, b.agent_name, date(a.date_time) order by a.service_feature_code, b.agent_name, date(date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(e.name) as State FROM acc_service_order a, agent_info b, service_feature c, acc_request d,state_list e WHERE b.state_id = e.state_id  and a.acc_service_order_no = d.order_no and d.status = 'S' and  e.state_id=$state and a.service_feature_code = c.feature_code and b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by  State  ,a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							else {
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State FROM acc_service_order a, agent_info b, service_feature c, acc_request d,state_list e WHERE b.state_id = e.state_id and a.acc_service_order_no = d.order_no and d.status = 'S' and  e.state_id=$state and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and b.agent_code = '$agentName' and a.user_id = b.user_id group by b.parent_code, State  ,a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
							}
						}
					}
				}
				
			}	
		error_log("Acc stat report query = ".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("agent"=>$row['agent'],"date"=>$row['Date'],"otype"=>$row['Order Type'],"count"=>$row['Count'],"state"=>$row['State'],"user"=>$row['user'],"ad"=>$agentDetail,"td"=>$typeDetail);           
		}
		echo json_encode($data);
	}

?>