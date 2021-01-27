 <?php
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	$action		=  $data->action;
	$type	=  $data->type;
	$agentName	=   $_SESSION['party_code'];
   $subAgentName	=  $data->subAgentName;
	$startDate		=  $data->startDate;
	$endDate	=  $data->endDate;         
	$creteria 	= $data->creteria;    
	$agentDetail 	= $data->agentDetail;
    $subAgentDetail 	= $data->subagentDetail;	
	$typeDetail 	= $data->typeDetail;
	$ba 	= $data->ba;
	$creteria 	= $data->creteria;	
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
		if($action == "getreport") {		
			if($type == "ALL") {
				if($typeDetail == false) {
					if($agentDetail == false) {
						if($creteria == "A") {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and (date(a.date_time)) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
							}
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE  b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
									}
								}
							}
							else {
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by sub_agent, date(a.date_time) order by date(a.date_time), sub_agent";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE  b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by sub_agent, date(a.date_time) order by date(a.date_time), sub_agent";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by sub_agent, date(a.date_time) order by date(a.date_time), sub_agent";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time), sub_agent order by date(a.date_time), sub_agent";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time), sub_agent order by date(a.date_time), sub_agent";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time), sub_agent order by date(a.date_time), sub_agent";
									}
								}
							}								
						}
					}
					else {					
						if($creteria == "A") {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
						if($creteria == "S") {							
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE  b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and  b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and  b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
								}
							}
							else {								
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName'  and b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName'  and b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
								}
							}
						}
					}					
				}
				else {					
					if($agentDetail == false) {
						if($creteria == "A") {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time),a.service_feature_code order by date(a.date_time), a.service_feature_code";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time), a.service_feature_code order by date(a.date_time), a.service_feature_code";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time), a.service_feature_code order by a.service_feature_code,date(a.date_time)";
							}
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount,a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount,a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE  b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time), a.service_feature_code order by a.service_feature_code, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount,a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
									}
								}
							}
							else {
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by subagent, date(a.date_time), a.service_feature_code order by a.service_feature_code, date(a.date_time), subagent";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE  b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, subagent, date(a.date_time) order by a.service_feature_code, date(date(a.date_time)), subagent";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, subagent, date(a.date_time) order by a.service_feature_code, date(date(a.date_time)), subagent";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code, date(a.date_time), subagent order by date(a.date_time), subagent";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code, date(a.date_time), subagent order by date(a.date_time), subagent";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time),a.service_feature_code, subagent order by a.service_feature_code, date(a.date_time), subagent";
									}
								}
							}								
						}
					}
					else {
						if($creteria == "A") {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, a.service_feature_code, date(a.date_time) order by a.service_feature_code, b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, a.service_feature_code, date(a.date_time) order by a.service_feature_code, b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, a.service_feature_code, date(a.date_time) order by a.service_feature_code, b.agent_name, date(a.date_time)";
							}
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date, a.service_feature_code as 'Order Type', SUM(a.request_amount) as request_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE  b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, a.service_feature_code, date(a.date_time) order by a.service_feature_code, b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date, a.service_feature_code as 'Order Type', SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, b.agent_name, date(a.date_time) order by a.service_feature_code, b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date, a.service_feature_code as 'Order Type', SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, b.agent_name, date(a.date_time) order by a.service_feature_code, b.agent_name, date(a.date_time)";
									}
								}
							}
							else {
								if($ba == 'ra') {
									$query ="SELECT date(a.date_time) as Date, a.service_feature_code as 'Order Type', SUM(a.request_amount) as request_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, parent, subagent, date(a.date_time) order by a.service_feature_code, parent, subagent, date(a.date_time)";
								}
								if($ba == 'ta') {
									$query ="SELECT date(a.date_time) as Date, a.service_feature_code as 'Order Type', SUM(a.total_amount) as total_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName'  and b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, parent, subagent, date(a.date_time) order by a.service_feature_code, parent, subagent, date(a.date_time)";
								}
								if($ba == 'bo') {
									$query ="SELECT date(a.date_time) as Date, a.service_feature_code as 'Order Type', SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName'  and b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, parent, subagent, date(a.date_time) order by a.service_feature_code, parent, subagent, date(a.date_time)";
								}
							}
						}
					}								
				}
			}
			else {
				if($typeDetail == false) {
					if($agentDetail == false) {
						if($creteria == "A") {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and (date(a.date_time)) between '$startDate' and '$endDate' a.service_feature_code = '$type' group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' a.service_feature_code = '$type' group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
							}
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE  b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.service_feature_code = '$type' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.service_feature_code = '$type' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and a.service_feature_code = '$type' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
									}
								}
							}
							else {
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by agent, date(a.date_time) order by date(a.date_time), agent";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE  b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.service_feature_code = '$type' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by agent, date(a.date_time) order by date(a.date_time), agent";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by agent, date(a.date_time) order by date(a.date_time), agent";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time), agent order by date(a.date_time), agent";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time), agent order by date(a.date_time), agent";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and a.service_feature_code = '$type' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time), agent order by date(a.date_time), agent";
									}
								}
							}								
						}
					}
					else {
						if($creteria == "A") {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.service_feature_code = '$type' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE  b.parent_code='$agentName' and b.sub_agent = 'Y' and a.service_feature_code = '$type' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$agentName' and b.sub_agent = 'Y' and a.service_feature_code = '$type' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
								}
							}
							else {
								if($ba == 'ra') {
									$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and a.service_feature_code = '$type' and b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
								}
								if($ba == 'ta') {
									$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and a.service_feature_code = '$type' and b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
								}
								if($ba == 'bo') {
									$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and a.service_feature_code = '$type' and b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
								}
							}
						}
					}					
				}
				else {					
					if($agentDetail == false) {
						if($creteria == "A") {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time),a.service_feature_code order by date(a.date_time), a.service_feature_code";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time), a.service_feature_code order by date(a.date_time), a.service_feature_code";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.service_feature_code = '$type' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time), a.service_feature_code order by a.service_feature_code,date(a.date_time)";
							}
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount,a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount,a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE  b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and a.service_feature_code = '$type' and b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time), a.service_feature_code order by a.service_feature_code, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount,a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and a.service_feature_code = '$type' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and a.service_feature_code = '$type' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
									}
								}
							}
							else {
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by subagent, date(a.date_time), a.service_feature_code, order by a.service_feature_code, date(a.date_time), subagent";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE  b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, subagent, date(a.date_time) order by a.service_feature_code, date(date(a.date_time)), subagent";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, subagent, date(a.date_time) order by a.service_feature_code, date(date(a.date_time)), subagent";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code, date(a.date_time), subagent order by date(a.date_time), subagent";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code, date(a.date_time), subagent order by date(a.date_time), subagent";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and a.service_feature_code = '$type' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time),a.service_feature_code, subagent order by a.service_feature_code, date(a.date_time), subagent";
									}
								}
							}								
						}
					}
					else {
						if($creteria == "A") {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, a.service_feature_code, date(a.date_time) order by a.service_feature_code, b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, a.service_feature_code, date(a.date_time) order by a.service_feature_code, b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.service_feature_code = '$type' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, a.service_feature_code, date(a.date_time) order by a.service_feature_code, b.agent_name, date(a.date_time)";
							}
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date, a.service_feature_code as 'Order Type', SUM(a.request_amount) as request_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE  b.parent_code='$agentName' and b.sub_agent = 'Y' and a.service_feature_code = '$type' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, a.service_feature_code, date(a.date_time) order by a.service_feature_code, b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date, a.service_feature_code as 'Order Type', SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$agentName' and b.sub_agent = 'Y' and a.service_feature_code = '$type' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, b.agent_name, date(a.date_time) order by a.service_feature_code, b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date, a.service_feature_code as 'Order Type', SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, b.agent_name, date(a.date_time) order by a.service_feature_code, b.agent_name, date(a.date_time)";
									}
								}
							}
							else {
								if($ba == 'ra') {
									$query ="SELECT date(a.date_time) as Date, a.service_feature_code as 'Order Type', SUM(a.request_amount) as request_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and a.service_feature_code = '$type' and b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, parent, subagent, date(a.date_time) order by a.service_feature_code, parent, subagent, date(a.date_time)";
								}
								if($ba == 'ta') {
									$query ="SELECT date(a.date_time) as Date, a.service_feature_code as 'Order Type', SUM(a.total_amount) as total_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and a.service_feature_code = '$type' and b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, parent, subagent, date(a.date_time) order by a.service_feature_code, parent, subagent, date(a.date_time)";
								}
								if($ba == 'bo') {
									$query ="SELECT date(a.date_time) as Date, a.service_feature_code as 'Order Type', SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and a.service_feature_code = '$type' and b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, parent, subagent, date(a.date_time) order by a.service_feature_code, parent, subagent, date(a.date_time)";
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
			$data[] = array("amtype"=>$ba,"agent"=>$row['agent'],"date"=>$row['Date'],"otype"=>$row['Order Type'],"reamt"=>$row['request_amount'],"toamt"=>$row['total_amount'],"ad"=>$agentDetail,"td"=>$typeDetail,"sd"=>$subAgentDetail,"mssage"=>$mssage,"parent"=>$row['parent'],"subagent"=>$row['subagent']);                     
		}
		echo json_encode($data);
	}

?>