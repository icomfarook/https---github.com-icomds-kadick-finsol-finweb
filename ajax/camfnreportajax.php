 <?php
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	$action		=  $data->action;
	$type	=  $data->type;
	$chamName	=   $_SESSION['party_code'];
	$agentName	=  $data->agetnName;
	$startDate		=  $data->startDate;
	$endDate	=  $data->endDate;         
	$creteria 	= $data->creteria;    
	$champDetail 	= $data->championDetail;
    $agentDetail 	= $data->agentdetail;	
	$typeDetail 	= $data->typeDetail;
	if($creteria == "C") {
		$agentDetail = false;
	}
	$ba 	= $data->ba;
	$creteria 	= $data->creteria;	
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
		if($action == "getreport") {		
			if($type == "ALL") {
				if($typeDetail == false) {
					if($champDetail == false) {
						
						
						if($creteria == "all") {
							if($ba == 'ra') {
								$query ="SELECT  date(a.date_time) as Date , a.request_amount FROM agent_info b, fin_service_order a WHERE a.user_id = b.user_id and  b.parent_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate'  group by date(a.date_time) UNION ALL SELECT  date(a.date_time) as Date, a.request_amount  FROM champion_info b, fin_service_order a WHERE a.user_id =b.user_id and  b.champion_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate'  group by date(a.date_time) ";
							}
							if($ba == 'ta') {
								$query ="SELECT  date(a.date_time) as Date , a.total_amount FROM agent_info b, fin_service_order a WHERE a.user_id = b.user_id and  b.parent_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate'  group by date(a.date_time) UNION ALL SELECT  date(a.date_time) as Date, a.total_amount  FROM champion_info b, fin_service_order a WHERE a.user_id =b.user_id and  b.champion_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate'  group by date(a.date_time) ";
							}
							if($ba == 'bo') {
								$query ="SELECT  date(a.date_time) as Date , a.request_amount, a.total_amount FROM agent_info b, fin_service_order a WHERE a.user_id = b.user_id and  b.parent_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate'  group by date(a.date_time) UNION ALL SELECT  date(a.date_time) as Date, a.request_amount, a.total_amount  FROM champion_info b, fin_service_order a WHERE a.user_id =b.user_id and  b.champion_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate'  group by date(a.date_time) ";
							}
						}
						if($creteria == "C") {
							
								if($ba == 'ra') {
									$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount FROM fin_service_order a, champion_info b WHERE b.champion_code = '$chamName' and a.user_id = b.user_id and (date(a.date_time)) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
								}
								if($ba == 'ta') {
									$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount FROM fin_service_order a, champion_info b WHERE b.champion_code = '$chamName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
								}
								if($ba == 'bo') {
									$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, champion_info b WHERE b.champion_code = '$chamName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
								}
							
						}
						if($creteria == "A") {
							if($agentDetail == false) {
								if($agentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount FROM fin_service_order a, agent_info b WHERE b.parent_code = '$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE  b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and b.parent_code = '$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$agentName' and b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$agentName' and b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
									}
								}
							}
							else {
								//error_log("inside");
								if($agentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ') from champion_info where champion_code = b.parent_code),'] ') as champion FROM fin_service_order a, agent_info b WHERE b.parent_code = '$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by sub_agent, date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ')as champion FROM fin_service_order a, agent_info b WHERE  b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by sub_agent, date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ')as champion FROM fin_service_order a, agent_info b WHERE b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by sub_agent, date(a.date_time) order by date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ')as champion FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and b.parent_code = '$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ')as champion FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$agentName' and b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ')as champion FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$agentName' and b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
									}
								}
							}								
						}
					}
					else {
						if($creteria == "all") {
							if($ba == 'ra') {
								$query ="SELECT  date(a.date_time) as Date , a.request_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM agent_info b, fin_service_order a WHERE a.user_id = b.user_id and  b.parent_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate'  group by date(a.date_time) UNION ALL SELECT  date(a.date_time) as Date, a.request_amount, b.champion_code as agent  FROM champion_info b, fin_service_order a WHERE a.user_id =b.user_id and  b.champion_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate'  group by date(a.date_time) ";
							}
							if($ba == 'ta') {
								$query ="SELECT  date(a.date_time) as Date , a.total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM agent_info b, fin_service_order a WHERE a.user_id = b.user_id and  b.parent_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate'  group by date(a.date_time) UNION ALL SELECT  date(a.date_time) as Date, a.total_amount, b.champion_code as agent   FROM champion_info b, fin_service_order a WHERE a.user_id =b.user_id and  b.champion_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate'  group by date(a.date_time) ";
							}
							if($ba == 'bo') {
								$query ="SELECT  date(a.date_time) as Date , a.request_amount, a.total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM agent_info b, fin_service_order a WHERE a.user_id = b.user_id and  b.parent_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate'  group by date(a.date_time) UNION ALL SELECT  date(a.date_time) as Date, a.request_amount, a.total_amount, b.champion_code as agent   FROM champion_info b, fin_service_order a WHERE a.user_id =b.user_id and  b.champion_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate'  group by date(a.date_time) ";
							}
						}
						if($creteria == "C") {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(champion_code,' - ', champion_name) as champion FROM fin_service_order a, champion_info b WHERE b.champion_code = '$chamName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.champion_name, date(a.date_time) order by b.champion_name , date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(champion_code,' - ', champion_name) as champion FROM fin_service_order a, champion_info b WHERE b.champion_code = '$chamName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.champion_name,  date(a.date_time) order by b.champion_name,  date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(champion_code,' - ', champion_name) as champion FROM fin_service_order a, champion_info b WHERE b.champion_code = '$chamName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.champion_name,  date(a.date_time) order by b.champion_name,  date(a.date_time)";
							}
						}
						if($creteria == "A") {							
							if($agentDetail == false) {
								if($agentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM fin_service_order a, agent_info b WHERE  b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and  b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and  b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
								}
							}
							else {								
								if($agentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as champion,  CONCAT(b.agent_code, ' - ', b.agent_name) as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as champion,  CONCAT(b.agent_code, ' - ', b.agent_name) as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as champion, CONCAT(b.agent_code, ' - ', b.agent_name) as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as parent,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as champion FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as parent,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as champion FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName'  and b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as parent,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as champion FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName'  and b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
								}
							}
						}
					}					
				}
				else {					
										if($champDetail == false) {
						
						
						if($creteria == "all") {
							if($ba == 'ra') {
								$query ="SELECT  date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  a.request_amount FROM agent_info b, fin_service_order a WHERE a.user_id = b.user_id and  b.parent_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate'  group by date(a.date_time) UNION ALL SELECT  date(a.date_time) as Date, a.request_amount  FROM champion_info b, fin_service_order a WHERE a.user_id =b.user_id and  b.champion_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate'  group by a.service_feature_code,date(a.date_time) ";
							}
							if($ba == 'ta') {
								$query ="SELECT  date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  a.total_amount FROM agent_info b, fin_service_order a WHERE a.user_id = b.user_id and  b.parent_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate'  group by a.service_feature_code,date(a.date_time) UNION ALL SELECT  date(a.date_time) as Date, a.total_amount  FROM champion_info b, fin_service_order a WHERE a.user_id =b.user_id and  b.champion_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate'  group by a.service_feature_code,date(a.date_time) ";
							}
							if($ba == 'bo') {
								$query ="SELECT  date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  a.request_amount, a.total_amount FROM agent_info b, fin_service_order a WHERE a.user_id = b.user_id and  b.parent_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate'  group by a.service_feature_code,date(a.date_time) UNION ALL SELECT  date(a.date_time) as Date, a.request_amount, a.total_amount  FROM champion_info b, fin_service_order a WHERE a.user_id =b.user_id and  b.champion_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate'  group by a.service_feature_code,date(a.date_time) ";
							}
						}
						if($creteria == "C") {
							
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.request_amount) as request_amount FROM fin_service_order a, champion_info b WHERE b.champion_code = '$chamName' and a.user_id = b.user_id and (date(a.date_time)) between '$startDate' and '$endDate'  group by a.service_feature_code,date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.total_amount) as total_amount FROM fin_service_order a, champion_info b WHERE b.champion_code = '$chamName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code,date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, champion_info b WHERE b.champion_code = '$chamName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code,date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
						}
						if($creteria == "A") {
							if($agentDetail == false) {
								if($agentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.request_amount) as request_amount FROM fin_service_order a, agent_info b WHERE b.parent_code = '$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code,date(a.date_time) order by a.service_feature_code,date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE  b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code,date(a.date_time) order by a.service_feature_code,date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code,date(a.date_time) order by a.service_feature_code,date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.request_amount) as request_amount FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and b.parent_code = '$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code,date(a.date_time) order by a.service_feature_code,date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$agentName' and b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code,date(a.date_time) order by a.service_feature_code,date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$agentName' and b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code,date(a.date_time) order by a.service_feature_code,date(a.date_time)";
									}
								}
							}
							else {
								//error_log("inside");
								if($agentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ') from champion_info where champion_code = b.parent_code),'] ') as champion FROM fin_service_order a, agent_info b WHERE b.parent_code = '$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by sub_agent, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.total_amount) as total_amount,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ')as champion FROM fin_service_order a, agent_info b WHERE  b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by sub_agent, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ')as champion FROM fin_service_order a, agent_info b WHERE b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by sub_agent, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.request_amount) as request_amount,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ')as champion FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and b.parent_code = '$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code,date(a.date_time) order by a.service_feature_code,date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.total_amount) as total_amount,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ')as champion FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$agentName' and b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code,date(a.date_time) order by a.service_feature_code,date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ')as champion FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$agentName' and b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code,date(a.date_time) order by a.service_feature_code,date(a.date_time)";
									}
								}
							}								
						}
					}
					else {
						if($creteria == "all") {
							if($ba == 'ra') {
								$query ="SELECT  date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  a.request_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM agent_info b, fin_service_order a WHERE a.user_id = b.user_id and  b.parent_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate'  group by a.service_feature_code,date(a.date_time) UNION ALL SELECT  date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  a.request_amount, b.champion_code as agent  FROM champion_info b, fin_service_order a WHERE a.user_id =b.user_id and  b.champion_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate'  group by a.service_feature_code,date(a.date_time) ";
							}
							if($ba == 'ta') {
								$query ="SELECT  date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  a.total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM agent_info b, fin_service_order a WHERE a.user_id = b.user_id and  b.parent_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate'  group by a.service_feature_code,date(a.date_time) UNION ALL SELECT  date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  a.total_amount, b.champion_code as agent   FROM champion_info b, fin_service_order a WHERE a.user_id =b.user_id and  b.champion_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate'  group by a.service_feature_code,date(a.date_time) ";
							}
							if($ba == 'bo') {
								$query ="SELECT  date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  a.request_amount, a.total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM agent_info b, fin_service_order a WHERE a.user_id = b.user_id and  b.parent_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate'  group by a.service_feature_code,date(a.date_time) UNION ALL SELECT  date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  a.request_amount, a.total_amount, b.champion_code as agent   FROM champion_info b, fin_service_order a WHERE a.user_id =b.user_id and  b.champion_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate'  group by a.service_feature_code,date(a.date_time) ";
							}
						}
						if($creteria == "C") {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.request_amount) as request_amount, concat(champion_code,' - ', champion_name) as champion FROM fin_service_order a, champion_info b WHERE b.champion_code = '$chamName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.champion_name, date(a.date_time) order by b.champion_name , date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.total_amount) as total_amount, concat(champion_code,' - ', champion_name) as champion FROM fin_service_order a, champion_info b WHERE b.champion_code = '$chamName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.champion_name,  date(a.date_time) order by b.champion_name,  date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(champion_code,' - ', champion_name) as champion FROM fin_service_order a, champion_info b WHERE b.champion_code = '$chamName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.champion_name,  date(a.date_time) order by b.champion_name,  date(a.date_time)";
							}
						}
						if($creteria == "A") {							
							if($agentDetail == false) {
								if($agentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM fin_service_order a, agent_info b WHERE  b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code,b.agent_name, date(a.date_time) order by a.service_feature_code,b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code,b.agent_name, date(a.date_time) order by a.service_feature_code,b.agent_name, date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and  b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and  b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code,b.agent_name, date(a.date_time) order by a.service_feature_code,b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code,b.agent_name, date(a.date_time) order by a.service_feature_code,b.agent_name, date(a.date_time)";
									}
								}
							}
							else {								
								if($agentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as champion,  CONCAT(b.agent_code, ' - ', b.agent_name) as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as champion,  CONCAT(b.agent_code, ' - ', b.agent_name) as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code,b.agent_name, date(a.date_time) order by a.service_feature_code,b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as champion, CONCAT(b.agent_code, ' - ', b.agent_name) as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code,b.agent_name, date(a.date_time) order by a.service_feature_code,b.agent_name, date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as parent,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as champion FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as parent,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as champion FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName'  and b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code,b.agent_name, date(a.date_time) order by a.service_feature_code,b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as parent,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as champion FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName'  and b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code,b.agent_name, date(a.date_time) order by a.service_feature_code,b.agent_name, date(a.date_time)";
									}
								}
							}
						}
					}
				}
			}
			else {
								if($typeDetail == false) {
					if($champDetail == false) {
						
						
						if($creteria == "all") {
							if($ba == 'ra') {
								$query ="SELECT  date(a.date_time) as Date , a.request_amount FROM agent_info b, fin_service_order a WHERE a.user_id = b.user_id and  b.parent_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by date(a.date_time) UNION ALL SELECT  date(a.date_time) as Date, a.request_amount  FROM champion_info b, fin_service_order a WHERE a.user_id =b.user_id and  b.champion_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by date(a.date_time) ";
							}
							if($ba == 'ta') {
								$query ="SELECT  date(a.date_time) as Date , a.total_amount FROM agent_info b, fin_service_order a WHERE a.user_id = b.user_id and  b.parent_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by date(a.date_time) UNION ALL SELECT  date(a.date_time) as Date, a.total_amount  FROM champion_info b, fin_service_order a WHERE a.user_id =b.user_id and  b.champion_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by date(a.date_time) ";
							}
							if($ba == 'bo') {
								$query ="SELECT  date(a.date_time) as Date , a.request_amount, a.total_amount FROM agent_info b, fin_service_order a WHERE a.user_id = b.user_id and  b.parent_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by date(a.date_time) UNION ALL SELECT  date(a.date_time) as Date, a.request_amount, a.total_amount  FROM champion_info b, fin_service_order a WHERE a.user_id =b.user_id and  b.champion_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by date(a.date_time) ";
							}
						}
						if($creteria == "C") {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount FROM fin_service_order a, champion_info b WHERE b.champion_code = '$chamName' and a.user_id = b.user_id and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount FROM fin_service_order a, champion_info b WHERE b.champion_code = '$chamName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, champion_info b WHERE b.champion_code = '$chamName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by date(a.date_time) order by date(a.date_time)";
							}
						}
						if($creteria == "A") {
							if($agentDetail == false) {
								if($agentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount FROM fin_service_order a, agent_info b WHERE b.parent_code = '$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE  b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by date(a.date_time) order by date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and b.parent_code = '$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$agentName' and b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$agentName' and b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by date(a.date_time) order by date(a.date_time)";
									}
								}
							}
							else {
								//error_log("inside");
								if($agentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ') from champion_info where champion_code = b.parent_code),'] ') as champion FROM fin_service_order a, agent_info b WHERE b.parent_code = '$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by sub_agent, date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ')as champion FROM fin_service_order a, agent_info b WHERE  b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by sub_agent, date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ')as champion FROM fin_service_order a, agent_info b WHERE b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by sub_agent, date(a.date_time) order by date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ')as champion FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and b.parent_code = '$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ')as champion FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$agentName' and b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ')as champion FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$agentName' and b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by date(a.date_time) order by date(a.date_time)";
									}
								}
							}								
						}
					}
					else {
						if($creteria == "all") {
							if($ba == 'ra') {
								$query ="SELECT  date(a.date_time) as Date , a.request_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM agent_info b, fin_service_order a WHERE a.user_id = b.user_id and  b.parent_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by date(a.date_time) UNION ALL SELECT  date(a.date_time) as Date, a.request_amount, b.champion_code as agent  FROM champion_info b, fin_service_order a WHERE a.user_id =b.user_id and  b.champion_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by date(a.date_time) ";
							}
							if($ba == 'ta') {
								$query ="SELECT  date(a.date_time) as Date , a.total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM agent_info b, fin_service_order a WHERE a.user_id = b.user_id and  b.parent_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by date(a.date_time) UNION ALL SELECT  date(a.date_time) as Date, a.total_amount, b.champion_code as agent   FROM champion_info b, fin_service_order a WHERE a.user_id =b.user_id and  b.champion_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by date(a.date_time) ";
							}
							if($ba == 'bo') {
								$query ="SELECT  date(a.date_time) as Date , a.request_amount, a.total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM agent_info b, fin_service_order a WHERE a.user_id = b.user_id and  b.parent_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by date(a.date_time) UNION ALL SELECT  date(a.date_time) as Date, a.request_amount, a.total_amount, b.champion_code as agent   FROM champion_info b, fin_service_order a WHERE a.user_id =b.user_id and  b.champion_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by date(a.date_time) ";
							}
						}
						if($creteria == "C") {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(champion_code,' - ', champion_name) as champion FROM fin_service_order a, champion_info b WHERE b.champion_code = '$chamName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by  b.champion_name, date(a.date_time) order by b.champion_name , date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(champion_code,' - ', champion_name) as champion FROM fin_service_order a, champion_info b WHERE b.champion_code = '$chamName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by b.champion_name,  date(a.date_time) order by b.champion_name,  date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(champion_code,' - ', champion_name) as champion FROM fin_service_order a, champion_info b WHERE b.champion_code = '$chamName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by b.champion_name,  date(a.date_time) order by b.champion_name,  date(a.date_time)";
							}
						}
						if($creteria == "A") {							
							if($agentDetail == false) {
								if($agentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM fin_service_order a, agent_info b WHERE  b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by  b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and  b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by  b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and  b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
								}
							}
							else {								
								if($agentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as champion,  CONCAT(b.agent_code, ' - ', b.agent_name) as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by  b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as champion,  CONCAT(b.agent_code, ' - ', b.agent_name) as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as champion, CONCAT(b.agent_code, ' - ', b.agent_name) as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as parent,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as champion FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by  b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as parent,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as champion FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName'  and b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as parent,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as champion FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName'  and b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
								}
							}
						}
					}					
				}
				else {					
										if($champDetail == false) {
						
						
						if($creteria == "all") {
							if($ba == 'ra') {
								$query ="SELECT  date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  a.request_amount FROM agent_info b, fin_service_order a WHERE a.user_id = b.user_id and  b.parent_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by date(a.date_time) UNION ALL SELECT  date(a.date_time) as Date, a.request_amount  FROM champion_info b, fin_service_order a WHERE a.user_id =b.user_id and  b.champion_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by a.service_feature_code,date(a.date_time) ";
							}
							if($ba == 'ta') {
								$query ="SELECT  date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  a.total_amount FROM agent_info b, fin_service_order a WHERE a.user_id = b.user_id and  b.parent_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by a.service_feature_code,date(a.date_time) UNION ALL SELECT  date(a.date_time) as Date, a.total_amount  FROM champion_info b, fin_service_order a WHERE a.user_id =b.user_id and  b.champion_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by a.service_feature_code,date(a.date_time) ";
							}
							if($ba == 'bo') {
								$query ="SELECT  date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  a.request_amount, a.total_amount FROM agent_info b, fin_service_order a WHERE a.user_id = b.user_id and  b.parent_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by a.service_feature_code,date(a.date_time) UNION ALL SELECT  date(a.date_time) as Date, a.request_amount, a.total_amount  FROM champion_info b, fin_service_order a WHERE a.user_id =b.user_id and  b.champion_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by a.service_feature_code,date(a.date_time) ";
							}
						}
						if($creteria == "C") {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.request_amount) as request_amount FROM fin_service_order a, champion_info b WHERE b.champion_code = '$chamName' and a.user_id = b.user_id and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by a.service_feature_code,date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.total_amount) as total_amount FROM fin_service_order a, champion_info b WHERE b.champion_code = '$chamName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by a.service_feature_code,date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, champion_info b WHERE b.champion_code = '$chamName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by a.service_feature_code,date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
						}
						if($creteria == "A") {
							if($agentDetail == false) {
								if($agentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.request_amount) as request_amount FROM fin_service_order a, agent_info b WHERE b.parent_code = '$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by a.service_feature_code,date(a.date_time) order by a.service_feature_code,date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE  b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by a.service_feature_code,date(a.date_time) order by a.service_feature_code,date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by a.service_feature_code,date(a.date_time) order by a.service_feature_code,date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.request_amount) as request_amount FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and b.parent_code = '$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by a.service_feature_code,date(a.date_time) order by a.service_feature_code,date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$agentName' and b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by a.service_feature_code,date(a.date_time) order by a.service_feature_code,date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$agentName' and b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by a.service_feature_code,date(a.date_time) order by a.service_feature_code,date(a.date_time)";
									}
								}
							}
							else {
								//error_log("inside");
								if($agentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ') from champion_info where champion_code = b.parent_code),'] ') as champion FROM fin_service_order a, agent_info b WHERE b.parent_code = '$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by sub_agent, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.total_amount) as total_amount,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ')as champion FROM fin_service_order a, agent_info b WHERE  b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by sub_agent, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ')as champion FROM fin_service_order a, agent_info b WHERE b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by sub_agent, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.request_amount) as request_amount,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ')as champion FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and b.parent_code = '$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by a.service_feature_code,date(a.date_time) order by a.service_feature_code,date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.total_amount) as total_amount,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ')as champion FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$agentName' and b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by a.service_feature_code,date(a.date_time) order by a.service_feature_code,date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',   SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ')as champion FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$agentName' and b.parent_code = '$chamName'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by a.service_feature_code,date(a.date_time) order by a.service_feature_code,date(a.date_time)";
									}
								}
							}								
						}
					}
					else {
						if($creteria == "all") {
							if($ba == 'ra') {
								$query ="SELECT  date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  a.request_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM agent_info b, fin_service_order a WHERE a.user_id = b.user_id and  b.parent_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by a.service_feature_code,date(a.date_time) UNION ALL SELECT  date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  a.request_amount, b.champion_code as agent  FROM champion_info b, fin_service_order a WHERE a.user_id =b.user_id and  b.champion_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by a.service_feature_code,date(a.date_time) ";
							}
							if($ba == 'ta') {
								$query ="SELECT  date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  a.total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM agent_info b, fin_service_order a WHERE a.user_id = b.user_id and  b.parent_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by a.service_feature_code,date(a.date_time) UNION ALL SELECT  date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  a.total_amount, b.champion_code as agent   FROM champion_info b, fin_service_order a WHERE a.user_id =b.user_id and  b.champion_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by a.service_feature_code,date(a.date_time) ";
							}
							if($ba == 'bo') {
								$query ="SELECT  date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  a.request_amount, a.total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM agent_info b, fin_service_order a WHERE a.user_id = b.user_id and  b.parent_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by a.service_feature_code,date(a.date_time) UNION ALL SELECT  date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  a.request_amount, a.total_amount, b.champion_code as agent   FROM champion_info b, fin_service_order a WHERE a.user_id =b.user_id and  b.champion_code = '$chamName' and (date(a.date_time)) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by a.service_feature_code,date(a.date_time) ";
							}
						}
						if($creteria == "C") {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.request_amount) as request_amount, concat(champion_code,' - ', champion_name) as champion FROM fin_service_order a, champion_info b WHERE b.champion_code = '$chamName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by  b.champion_name, date(a.date_time) order by b.champion_name , date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.total_amount) as total_amount, concat(champion_code,' - ', champion_name) as champion FROM fin_service_order a, champion_info b WHERE b.champion_code = '$chamName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by b.champion_name,  date(a.date_time) order by b.champion_name,  date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(champion_code,' - ', champion_name) as champion FROM fin_service_order a, champion_info b WHERE b.champion_code = '$chamName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by b.champion_name,  date(a.date_time) order by b.champion_name,  date(a.date_time)";
							}
						}
						if($creteria == "A") {							
							if($agentDetail == false) {
								if($agentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM fin_service_order a, agent_info b WHERE  b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by a.service_feature_code, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by a.service_feature_code,b.agent_name, date(a.date_time) order by a.service_feature_code,b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by a.service_feature_code,b.agent_name, date(a.date_time) order by a.service_feature_code,b.agent_name, date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and  b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and  b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by a.service_feature_code,b.agent_name, date(a.date_time) order by a.service_feature_code,b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by a.service_feature_code,b.agent_name, date(a.date_time) order by a.service_feature_code,b.agent_name, date(a.date_time)";
									}
								}
							}
							else {								
								if($agentName == "ALL") {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as champion,  CONCAT(b.agent_code, ' - ', b.agent_name) as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as champion,  CONCAT(b.agent_code, ' - ', b.agent_name) as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by a.service_feature_code,b.agent_name, date(a.date_time) order by a.service_feature_code,b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as champion, CONCAT(b.agent_code, ' - ', b.agent_name) as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by a.service_feature_code,b.agent_name, date(a.date_time) order by a.service_feature_code,b.agent_name, date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as parent,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as champion FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as parent,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as champion FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName'  and b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by a.service_feature_code,b.agent_name, date(a.date_time) order by a.service_feature_code,b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date ,  a.service_feature_code as 'Order Type',  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as parent,  concat(b.agent_name,' [',(select ifnull(concat(champion_code,' - ',champion_name),' SELF ')  from champion_info where champion_code = b.parent_code),'] ') as champion FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName'  and b.parent_code='$chamName'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by a.service_feature_code,b.agent_name, date(a.date_time) order by a.service_feature_code,b.agent_name, date(a.date_time)";
									}
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
			$data[] = array("amtype"=>$ba,"agent"=>$row['agent'],"date"=>$row['Date'],"otype"=>$row['Order Type'],"reamt"=>$row['request_amount'],"toamt"=>$row['total_amount'],"ad"=>$champDetail,"td"=>$typeDetail,"sd"=>$agentDetail,"mssage"=>$mssage,"parent"=>$row['parent'],"champion"=>$row['champion']);                     
		}
		echo json_encode($data);
	}

?>