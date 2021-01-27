 <?php
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	$action		=  $data->action;
	$type	=  $data->type;
	$championName	=  $_SESSION['party_code'];	
	$startDate		=  $data->startDate;
	$endDate	=  $data->endDate;
	$creteria 	= $data->creteria;
	$agentDetail 	= $data->agentDetail;	
	$typeDetail 	= $data->typeDetail;
	$agentName 	= $data->agentName;
	$ba 	= $data->ba;
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
		if($action == "getreport") {		
			if($type == "ALL") {
				if($typeDetail == false) {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount FROM fin_service_order a, agent_info b WHERE a.user_id = b.user_id and b.parent_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE a.user_id = b.user_id and b.parent_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM  fin_service_order a, agent_info b WHERE a.user_id = b.user_id and b.parent_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$championName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$championName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$championName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount FROM fin_service_order a, agent_info b WHERE b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
					}
				}
				else {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE a.user_id = b.user_id and b.parent_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate' group by service_feature_code, date(a.date_time) order by service_feature_code,date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE a.user_id = b.user_id and b.parent_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate' group by service_feature_code, date(a.date_time) order by service_feature_code,date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE a.user_id = b.user_id and b.parent_code = '$championName' and  date(a.date_time) between '$startDate' and '$endDate' group by service_feature_code, date(a.date_time) order by service_feature_code,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(a.date_time) order by  a.service_feature_code, b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$championName' and  date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(a.date_time) order by  a.service_feature_code, b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(a.date_time) order by  a.service_feature_code, b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b  WHERE  b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b  WHERE  b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b  WHERE  b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(a.date_time) order by  a.service_feature_code,b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(a.date_time) order by  a.service_feature_code,b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(a.date_time) order by  a.service_feature_code,b.agent_name, date(a.date_time)";
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
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount FROM fin_service_order a, agent_info b  WHERE  b.parent_code = '$championName' and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate 'group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b  WHERE  b.parent_code = '$championName' and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate 'group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b  WHERE  b.parent_code = '$championName' and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate 'group by date(a.date_time) order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE a.user_id = b.user_id and b.parent_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by b.agent_name, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE a.user_id = b.user_id and b.parent_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by b.agent_name, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount , concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE a.user_id = b.user_id and b.parent_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by b.agent_name, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount  FROM fin_service_order a, agent_info b WHERE b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate 'group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount  FROM fin_service_order a, agent_info b WHERE b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate 'group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount , SUM(a.total_amount) as total_amount  FROM fin_service_order a, agent_info b WHERE b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate 'group by date(a.date_time) order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$championName' and b.agent_code = '$agentName' and a.service_feature_code = '$type' and a.user_id = b.user_id group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$championName' and b.agent_code = '$agentName' and a.service_feature_code = '$type' and a.user_id = b.user_id group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount,SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$championName' and b.agent_code = '$agentName' and a.service_feature_code = '$type' and a.user_id = b.user_id group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
					}
				}
				else {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, service_feature_code as 'Order Type' FROM fin_service_order  a, agent_info b  WHERE  b.parent_code = '$championName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by service_feature_code, date(a.date_time) order by service_feature_code,date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, service_feature_code as 'Order Type' FROM fin_service_order  a, agent_info b  WHERE  b.parent_code = '$championName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by service_feature_code, date(a.date_time) order by service_feature_code,date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b  WHERE  b.parent_code = '$championName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by service_feature_code, date(a.date_time) order by service_feature_code,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(a.date_time) order by  a.service_feature_code, b.agent_name, date(a.date_time)";
							}
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(a.date_time) order by  a.service_feature_code, b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(a.date_time) order by  a.service_feature_code, b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b  WHERE  b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b  WHERE  b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as request_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b  WHERE  b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(a.date_time) order by  a.service_feature_code,b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(a.date_time) order by  a.service_feature_code,b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(a.date_time) order by  a.service_feature_code,b.agent_name, date(a.date_time)";
							}
						}
					}
				}
			}
			
		error_log("qyetr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("amtype"=>$ba,"agent"=>$row['agent'],"date"=>$row['Date'],"otype"=>$row['Order Type'],"reamt"=>$row['request_amount'],"toamt"=>$row['total_amount'],"ad"=>$agentDetail,"td"=>$typeDetail);           
		}
		echo json_encode($data);
	}

?>