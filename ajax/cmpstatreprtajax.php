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
				if($creteria == "ALL") {
							$query ="SELECT date(a.date_time) as Date, count(*) as Count,b.champion_code as champ, a.service_feature_code as 'Order Type', b.champion_name as Parent FROM fin_service_order a, champion_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.champion_code = '$agentName' and a.user_id = b.user_id group by a.service_feature_code, b.champion_name, date(date_time)
							UNION 
							SELECT date(a.date_time) as Date, count(*) as Count,b.agent_code as agnt, a.service_feature_code as 'Order Type' , concat(b.agent_name,' [',(select champion_name from champion_info where champion_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$agentName' and b.sub_agent = 'N' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) ";
				}
				if($creteria == "A") {
							$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type', b.champion_name as Parent FROM fin_service_order a, champion_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.champion_code = '$agentName' and a.user_id = b.user_id group by a.service_feature_code, b.champion_name, date(date_time) order by  a.service_feature_code,b.champion_name, date(date_time)";
				}
				if($creteria == "S") {
							
								if($subAgentName == "ALL") {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type',concat(b.parent_code,'-',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)) as Parent , concat(b.agent_name,' [',(select champion_name from champion_info where champion_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$agentName' and b.sub_agent = 'N' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
								}
								else {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type',concat(b.parent_code,'-',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)) as Parent ,concat(b.agent_name,' [',(select champion_name from champion_info where champion_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'N' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
								}
								
						}
						
			}
		else {
			if($creteria == "ALL") {
							$query ="SELECT date(a.date_time) as Date, count(*) as Count,b.champion_code as champ, a.service_feature_code as 'Order Type', b.champion_name as Parent FROM fin_service_order a, champion_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.champion_code = '$agentName' and a.service_feature_code = '$type'  and a.user_id = b.user_id group by a.service_feature_code, b.champion_name, date(date_time)
							UNION 
							SELECT date(a.date_time) as Date, count(*) as Count,b.agent_code as agnt, a.service_feature_code as 'Order Type' , concat(b.agent_name,' [',(select champion_name from champion_info where champion_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'N' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) ";
				}
						if($creteria == "A") {
							$query ="SELECT date(a.date_time) as Date, count(*) as Count, b.champion_name as Parent FROM fin_service_order a, champion_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.champion_code = '$agentName' and a.service_feature_code = '$type' and a.user_id = b.user_id group by b.champion_name, date(a.date_time) order by b.champion_name, date(a.date_time)";
						}
						if($creteria == "S") {
								if($subAgentName == "ALL") {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count,  concat(b.parent_code,'-',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)) as Parent , concat(b.agent_name,' [',(select champion_name from champion_info where champion_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$agentName' and b.sub_agent = 'N' and a.service_feature_code = '$type' and a.user_id = b.user_id group by parent, subagent, date(a.date_time) order by parent, subagent, date(a.date_time)";
								}
								else {
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.parent_code,'-',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)) as Parent ,concat(b.agent_name,' [',(select champion_name from champion_info where champion_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'N' and a.service_feature_code = '$type' and a.user_id = b.user_id group by parent, subagent, date(a.date_time) order by parent, subagent, date(a.date_time)";
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
			$data[] = array("agent"=>$row['agent'],""=>$row['agnt'],"champ"=>$row['champ'],"date"=>$row['Date'],"otype"=>$row['Order Type'],"count"=>$row['Count'],"user"=>$row['user'],"ad"=>$agentDetail,"td"=>$typeDetail,"sd"=>$subAgentDetail,"mssage"=>$mssage,"parent"=>$row['Parent'],"subagent"=>$row['subagent']);           
		}
		echo json_encode($data);
	}

?>