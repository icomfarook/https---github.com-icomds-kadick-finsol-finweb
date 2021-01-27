 <?php
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	$action		=  $data->action;
	$type	=  $data->type;
	$agentName	= $_SESSION['party_code'];
	$creteria 	= $data->creteria;	
	$startDate		=  $data->startDate;
	$endDate	=  $data->endDate;
	$subAgentName	=  $data->subAgentName;
	$typeDetail 	= $data->typeDetail;
	$agentDetail 	= $data->agentDetail;
	$subAgentDetail = $data->subAgentDetail;	
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
		if($action == "getreport") {		
			if($type == "ALL") {
				if($typeDetail == false) {
					if($agentDetail == false) {
						if($creteria == "A") {
							$mssage = "Non Detailed and Non Agent detailed Statistical Report For ALL Sub Agent between date $startDate and $endDate";
							$query ="SELECT date(a.date_time) as Date, count(*) as Count FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									$mssage = "Non Detailed Statistical Report For Sub Agent - $subAgentName  between date $startDate and $endDate";
									$query ="SELECT date(a.date_time) as Date, count(*) as Count FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
								}
								else {
									$mssage = "Non Detailed Statistical Report For Sub Agent - $subAgentName  between date $startDate and $endDate";
									$query ="SELECT date(a.date_time) as Date, count(*) as Count FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
								}
							}
							else {
								if($subAgentName == "ALL") {
									$mssage = "Non Detailed and Sub Agent detailed Statistical Report For ALL Sub Agent between date $startDate and $endDate";
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,'[',ifNULL('self',(select agent_name FROM agent_info WHERE agent_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by agent, date(a.date_time) order by agent, date(a.date_time)";
								}
								else {
									$mssage = "Non Detailed and Sub Agent detailed Statistical Report For Sub Agent $subAgentName between date $startDate and $endDate";
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,'[',ifNULL('self',(select agent_name FROM agent_info WHERE agent_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by agent, date(a.date_time) order by agent, date(a.date_time)";
								}
							}
						}
					}
					else {
						if($creteria == "A") {
							$mssage = "Non Detailed and Agent Detailed Statistical Report For Agent $agentName between date $startDate and $endDate";
							$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								$mssage = "Non Detailed and Agent detailed Statistical Report For Sub Agent $subAgentName between date $startDate and $endDate";
								if($subAgentName == "ALL") {								
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by agent, date(a.date_time) order by agent, date(a.date_time)";
								}
								else {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by agent, date(a.date_time) order by agent, date(a.date_time)";
								}								
							}
							else {
								if($subAgentName == "ALL") {								
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type', concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by parent, subagent, date(a.date_time) order by parent, subagent, date(a.date_time)";
								}
								else {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type', concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by parent, subagent, date(a.date_time) order by parent, subagent, date(a.date_time)";
								}
							}
						}
					}
				}
				else {
					if($agentDetail == false) {
						if($creteria == "A") {
							$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b  WHERE  b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b  WHERE  b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
								}
								else {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b  WHERE   b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
								}
							}
							else {
								if($subAgentName == "ALL") {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type',concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b  WHERE  b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, subagent, date(a.date_time) order by a.service_feature_code,date(a.date_time), subagent";
								}
								else {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type',concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b  WHERE  b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type',concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b  WHERE  b.parent_code = 'AG0101' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '2019-12-03' and '2019-12-03' group by a.service_feature_code, subagent, date(a.date_time) order by a.service_feature_code,date(a.date_time), subagent, a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time), subagent";
								}
							}
						}
					}
					else {
						if($creteria == "A") {
							$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
								}
								else {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'Y' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
								}
							}
							else {
								if($subAgentName == "ALL") {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type', concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
								}
								else {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type', concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'Y' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
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
							$query ="SELECT date(a.date_time) as Date, count(*) as Count FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
								}
								else {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
								}
							}
							else {
								if($subAgentName == "ALL") {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time),subagent order by date(a.date_time),subagent";
								}
								else {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time),subagent order by date(a.date_time),subagent";
								}
							}
						}
					}
					else {
						if($creteria == "A") {
							$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and a.service_feature_code = '$type' and a.user_id = b.user_id group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.service_feature_code = '$type' and a.user_id = b.user_id group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
								}
								else {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'Y' and a.service_feature_code = '$type' and a.user_id = b.user_id group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
								}
							}
							else {
								if($subAgentName == "ALL") {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.service_feature_code = '$type' and a.user_id = b.user_id group by parent, subagent, date(a.date_time) order by parent, subagent, date(a.date_time)";
								}
								else {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'Y' and a.service_feature_code = '$type' and a.user_id = b.user_id group by parent, subagent, date(a.date_time) order by parent, subagent, date(a.date_time)";
								}
							}								
						}
					}					
				}
				else {
					if($agentDetail == false) {
						if($creteria == "A") {
							$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b  WHERE  b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b  WHERE  b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
								}
								else {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b  WHERE  b.parent_code = '$agentName' and  b.agent_code = '$subAgentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
								}
							}
							else {
								if($subAgentName == "ALL") {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type',concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b  WHERE  b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time), subagent order by a.service_feature_code,date(a.date_time), subagent";
								}
								else {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type',concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b  WHERE  b.parent_code = '$agentName' and  b.agent_code = '$subAgentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time), subagent order by a.service_feature_code,date(a.date_time), subagent";
								}
							}
						}
					}
					else {
						if($creteria == "A") {
							$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and b.agent_code = '$agentName' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
								}
								else {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'Y' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
								}
							}
							else {
								if($subAgentName == "ALL") {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type', concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time), parent, subagent order by  a.service_feature_code,b.agent_name, date(date_time), parent, subagent";
								}
								else {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type', concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'Y' and a.user_id = b.user_id group by a.service_feature_code, parent,subagent, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time), parent,subagent";
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
			$data[] = array("agent"=>$row['agent'],"date"=>$row['Date'],"otype"=>$row['Order Type'],"count"=>$row['Count'],"user"=>$row['user'],"ad"=>$agentDetail,"td"=>$typeDetail,"sd"=>$subAgentDetail,"mssage"=>$mssage,"parent"=>$row['parent'],"subagent"=>$row['subagent']);           
		}
		echo json_encode($data);
	}

?>