 <?php
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	$championName	=  $_SESSION['party_code'];	
	$profileid = $_SESSION['profile_id'];
	$action		=  $data->action;
	$opr	=  $data->opr;
	$agentName	=  $data->agentName;	
	$startDate		=  $data->startDate;
	$endDate	=  $data->endDate;
	$creteria 	= $data->creteria;
	$agentDetail 	= $data->agentDetail;	
	$oprDetail 	= $data->typeDetail;
	$ba 	= $data->ba;
	$state 	= $data->state;
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate. '+1days'));
		if($action == "getreport") {		
		 if($profileid ==50){
			 if($creteria == "ALL"){
				if($state == "ALL"){
					if($opr == "ALL"){
						if($agentName == "ALL"){
							
							$query =" (SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', b.champion_name  as agent ,concat(d.name) as State FROM evd_transaction a, champion_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = c.operator_id and b.champion_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State, a.operator_id, b.champion_name, b.champion_code,a.opr_plan_desc, date(a.date_time) order by  a.operator_id, b.champion_name,b.champion_code, date(a.date_time)) 
							UNION  
							(SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent ,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = c.operator_id and b.parent_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State, a.operator_id, b.agent_name, b.agent_code,a.opr_plan_desc, date(a.date_time),b.parent_code order by  a.operator_id, b.agent_name,b.agent_code, date(a.date_time)) ";
						}
						else {
							$query =" (SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', b.champion_name  as agent ,concat(d.name) as State FROM evd_transaction a, champion_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = c.operator_id and b.champion_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State, a.operator_id, b.champion_name, b.champion_code,a.opr_plan_desc, date(a.date_time) order by  a.operator_id, b.champion_name,b.champion_code, date(a.date_time)) 
							UNION  
							(SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent ,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = c.operator_id and b.parent_code = '$championName' and   b.agent_code = '$agentName' and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State, a.operator_id, b.agent_name, b.agent_code,a.opr_plan_desc, date(a.date_time),b.parent_code order by  a.operator_id, b.agent_name,b.agent_code, date(a.date_time)) ";
						
						}
					}
					else {
						if($agentName == "ALL"){
						
							$query ="(SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', b.champion_name  as agent ,concat(d.name) as State FROM evd_transaction a, champion_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = $opr and a.operator_id = c.operator_id and b.champion_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State, a.operator_id, b.champion_name, b.champion_code,a.opr_plan_desc, date(a.date_time) order by  a.operator_id, b.champion_name,b.champion_code, date(a.date_time))
							UNION 
							(SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent ,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and b.parent_code = '$championName' and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc, b.agent_name,b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time))";
						}
						else {
							$query ="(SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', b.champion_name  as agent ,concat(d.name) as State FROM evd_transaction a, champion_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = $opr and a.operator_id = c.operator_id and b.champion_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State, a.operator_id, b.champion_name, b.champion_code,a.opr_plan_desc, date(a.date_time) order by  a.operator_id, b.champion_name,b.champion_code, date(a.date_time))
							UNION 
							(SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent ,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and b.parent_code = '$championName' and b.agent_code = '$agentName'  and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc, b.agent_name,b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time))";
							
							
						}
					}
			 }else {
				if($opr == "ALL") {
						if($agentName == "ALL"){
							$query =" (SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', b.champion_name  as agent ,concat(d.name) as State FROM evd_transaction a, champion_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = c.operator_id and b.champion_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate'  and d.state_id = $state  and a.user_id = b.user_id group by State, a.operator_id, b.champion_name, b.champion_code,a.opr_plan_desc, date(a.date_time) order by  a.operator_id, b.champion_name,b.champion_code, date(a.date_time)) 
							UNION  
							(SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent ,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = c.operator_id and b.parent_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate'  and d.state_id = $state  and a.user_id = b.user_id group by State, a.operator_id, b.agent_name, b.agent_code,a.opr_plan_desc, date(a.date_time),b.parent_code order by  a.operator_id, b.agent_name,b.agent_code, date(a.date_time)) ";
							
						}
						else {
							$query =" (SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', b.champion_name  as agent ,concat(d.name) as State FROM evd_transaction a, champion_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = c.operator_id and b.champion_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate'  and d.state_id = $state  and a.user_id = b.user_id group by State, a.operator_id, b.champion_name, b.champion_code,a.opr_plan_desc, date(a.date_time) order by  a.operator_id, b.champion_name,b.champion_code, date(a.date_time)) 
							UNION  
							(SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent ,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = c.operator_id and b.parent_code = '$championName' and b.agent_code = '$agentName' and date(a.date_time) between '$startDate' and '$endDate'  and d.state_id = $state  and a.user_id = b.user_id group by State, a.operator_id, b.agent_name, b.agent_code,a.opr_plan_desc, date(a.date_time),b.parent_code order by  a.operator_id, b.agent_name,b.agent_code, date(a.date_time)) ";						
						}
				}
				else {
					if($agentName == "ALL"){
							$query =" (SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', b.champion_name  as agent ,concat(d.name) as State FROM evd_transaction a, champion_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = c.operator_id and a.operator_id = $opr and b.champion_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate'  and d.state_id = $state  and a.user_id = b.user_id group by State, a.operator_id, b.champion_name, b.champion_code,a.opr_plan_desc, date(a.date_time) order by  a.operator_id, b.champion_name,b.champion_code, date(a.date_time)) 
							UNION  
							(SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent ,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = c.operator_id and a.operator_id = $opr and b.parent_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate'  and d.state_id = $state  and a.user_id = b.user_id group by State, a.operator_id, b.agent_name, b.agent_code,a.opr_plan_desc, date(a.date_time),b.parent_code order by  a.operator_id, b.agent_name,b.agent_code, date(a.date_time)) ";
							
					}
					else {
						$query =" (SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', b.champion_name  as agent ,concat(d.name) as State FROM evd_transaction a, champion_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = c.operator_id and a.operator_id = $opr  and b.champion_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate'  and d.state_id = $state  and a.user_id = b.user_id group by State, a.operator_id, b.champion_name, b.champion_code,a.opr_plan_desc, date(a.date_time) order by  a.operator_id, b.champion_name,b.champion_code, date(a.date_time)) 
						UNION  
						(SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent ,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = c.operator_id and a.operator_id = $opr  and b.parent_code = '$championName' and b.agent_code = '$agentName' and date(a.date_time) between '$startDate' and '$endDate'  and d.state_id = $state  and a.user_id = b.user_id group by State, a.operator_id, b.agent_name, b.agent_code,a.opr_plan_desc, date(a.date_time),b.parent_code order by  a.operator_id, b.agent_name,b.agent_code, date(a.date_time)) ";						
					}
				}
			 }
		 }
			 else if($creteria == "C"){
				if($state == "ALL"){
					if($opr == "ALL") {							
							$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', b.champion_name  as agent ,concat(d.name) as State FROM evd_transaction a, champion_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = c.operator_id and b.champion_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State, a.operator_id, b.champion_name, b.champion_code,a.opr_plan_desc, date(a.date_time) order by  a.operator_id, b.champion_name,b.champion_code, date(a.date_time)";						
					}
					else {
							$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', b.champion_name  as agent ,concat(d.name) as State FROM evd_transaction a, champion_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = $opr and a.operator_id = c.operator_id and b.champion_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State, a.operator_id, b.champion_name, b.champion_code,a.opr_plan_desc, date(a.date_time) order by  a.operator_id, b.champion_name,b.champion_code, date(a.date_time)";							
						
					}
				}
			 //=================
			else {
				if($opr == "ALL") {
					
							$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', b.champion_name  as agent ,concat(d.name) as State FROM evd_transaction a, champion_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = c.operator_id and b.champion_code = '$championName' and b.agent_code = '$agentName' and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State, a.operator_id, b.champion_name, b.champion_code,a.opr_plan_desc, date(a.date_time) order by  a.operator_id, b.champion_name,b.champion_code, date(a.date_time)";
				}
				else {
				
						$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', b.champion_name  as agent ,concat(d.name) as State FROM evd_transaction a, champion_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = $opr and a.operator_id = c.operator_id and b.champion_code = '$championName' and b.agent_code = '$agentName' and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id and d.state_id = $state group by State, a.operator_id, b.champion_name, b.champion_code,a.opr_plan_desc, date(a.date_time) order by  a.operator_id, b.champion_name,b.champion_code, date(a.date_time)";
				}
			 }
		 }
		else if($creteria == "A"){
				if($state == "ALL"){
					if($opr == "ALL") {
						if($agentName == "ALL"){
							
							$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent ,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = c.operator_id and b.parent_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State, a.operator_id, b.agent_name, b.agent_code,a.opr_plan_desc, date(a.date_time),b.parent_code order by  a.operator_id, b.agent_name,b.agent_code, date(a.date_time)";
						}
						else {
						
							$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and b.parent_code = '$championName' and   b.agent_code = '$agentName'  and  a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,a.opr_plan_desc,b.parent_code, a.operator_id, b.agent_name, b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
						}
					}
					else {
						if($agentName == "ALL"){
						
							$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent ,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and b.parent_code = '$championName' and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc, b.agent_name,b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
						}
						else {
						
							$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and b.parent_code = '$championName' and b.agent_code = '$agentName'  and  a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,b.parent_code,a.opr_plan_desc, a.operator_id, b.agent_name, b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
						}
					}
			 }
			 //=================
				else {
				if($opr == "ALL") {
						if($agentName == "ALL"){
						
							$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent ,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = c.operator_id and b.parent_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc,b.agent_code, b.agent_name, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
						}
						else {
							$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and b.parent_code = '$championName' and b.agent_code = '$agentName'  and  a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc,b.agent_code, b.agent_name, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
						}
				}
				else {
					
					if($agentName == "ALL"){
					
						$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent ,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and b.parent_code = '$championName' and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id, b.agent_name, b.agent_code, a.opr_plan_desc,date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
					}
					else {
					
						$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and b.parent_code = '$championName' and b.agent_code = '$agentName'  and  a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id, b.agent_name, b.agent_code,a.opr_plan_desc, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
					}
				}
			 }
		 }
	}else{ // profile other than 50
		if($state == "ALL"){
			if($opr == "ALL") {
				if($oprDetail == false) {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT  date(a.date_time) as Date, SUM(a.request_amount) as request_amount,concat (b.agent_name) as agent ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE a.user_id=b.user_id and b.state_id=c.state_id and date(a.date_time) between '$startDate' and '$endDate'group by  date(a.date_time),b.agent_name,State order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(total_amount) as total_amount ,concat (b.agent_name) as agent ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE a.user_id=b.user_id and b.state_id=c.state_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time),b.agent_name,State order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(request_amount) as request_amount, SUM(total_amount) as total_amount ,concat (b.agent_name) as agent ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and a.user_id=b.user_id and  date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time),b.agent_name,State order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id  and  b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by State, date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and  b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by State ,date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id  and  b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by State ,date(a.date_time) order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id  and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id  and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by State,b.parent_code,b.agent_code, b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id  and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
					}
				}
				else {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b,agent_info c,state_list d  WHERE c.state_id = d.state_id and   a.user_id = c.user_id and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by State,a.opr_plan_desc, a.operator_id, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' ,concat(d.name) as State FROM evd_transaction a, operator b,agent_info c,state_list d  WHERE c.state_id = d.state_id and   a.user_id = c.user_id and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by State,a.opr_plan_desc, a.operator_id, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' ,concat(d.name) as State FROM evd_transaction a, operator b,agent_info c,state_list d  WHERE c.state_id = d.state_id and   a.user_id = c.user_id and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by State, a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc, b.agent_name,b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc, b.agent_name,b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent ,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State, a.operator_id, b.agent_name, b.agent_code,a.opr_plan_desc, date(a.date_time),b.parent_code order by  a.operator_id, b.agent_name,b.agent_code, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b, agent_info c,state_list d  WHERE c.state_id = d.state_id and  a.user_id = c.user_id and c.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by State, a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' ,concat(d.name) as State FROM evd_transaction a, operator b, agent_info c,state_list d  WHERE c.state_id = d.state_id and a.user_id = c.user_id and c.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by State ,a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b, agent_info c,state_list d  WHERE c.state_id = d.state_id and a.user_id = c.user_id and c.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by State ,a.opr_plan_desc,a.operator_id, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and b.agent_code = '$agentName'  and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,b.parent_code,a.opr_plan_desc, a.operator_id, b.agent_name, b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and b.agent_code = '$agentName'  and  a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,a.opr_plan_desc,b.parent_code, a.operator_id, b.agent_name, b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and b.agent_code = '$agentName'  and  a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,a.opr_plan_desc,b.parent_code, a.operator_id, b.agent_name, b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
						}
					}
				}
			}
			else {
				if($oprDetail == false) {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount,concat (b.agent_name) as agent ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE a.user_id=b.user_id and b.state_id=c.state_id and  operator_id = $opr and date(date_time) between '$startDate' and '$endDate' group by State,b.agent_name, date(a.date_time) order by date(date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount,concat (b.agent_name) as agent ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE a.user_id=b.user_id and b.state_id=c.state_id and operator_id = $opr and date(date_time) between '$startDate' and '$endDate' group by State,b.agent_name, date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,concat (b.agent_name) as agent ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE a.user_id=b.user_id and b.state_id=c.state_id and operator_id = $opr and date(date_time) between '$startDate' and '$endDate' group by State,b.agent_name, date(a.date_time) order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id=c.state_id and  a.operator_id = $opr and a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' group by State,b.parent_code, b.agent_name, b.agent_code,date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id=c.state_id and a.operator_id = $opr and a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id=c.state_id and a.operator_id = $opr and a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' group by State,b.parent_code, b.agent_name, b.agent_code, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id = c.state_id and  a.operator_id = $opr and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by State, date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount ,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id = c.state_id and a.operator_id = $opr and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by State, date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount ,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id = c.state_id and a.operator_id = $opr and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by State, date(a.date_time) order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id = c.state_id and a.operator_id = $opr and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent ,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id = c.state_id and a.operator_id = $opr and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id = c.state_id and a.operator_id = $opr and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by State,b.parent_code,b.agent_code, b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
					}
				}
				else {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b,agent_info c, state_list d  WHERE c.state_id = d.state_id and  a.user_id = c.user_id and  a.operator_id = $opr and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by State ,a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b,agent_info c, state_list d  WHERE c.state_id = d.state_id and  a.user_id = c.user_id and  a.operator_id = $opr and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by State ,a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b,agent_info c, state_list d  WHERE c.state_id = d.state_id and  a.user_id = c.user_id and  a.operator_id = $opr and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by State,a.opr_plan_desc, a.operator_id, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,b.parent_code, a.operator_id, b.agent_name, b.agent_code,a.opr_plan_desc, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc, b.agent_name,b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent ,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc, b.agent_name,b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b,agent_info c,state_list d  WHERE a.user_id = c.user_id and c.state_id = d.state_id and  a.operator_id = $opr and b.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by State, a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b,agent_info c,state_list d  WHERE a.user_id = c.user_id and c.state_id = d.state_id and  a.operator_id = $opr and b.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by State, a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' ,concat(d.name) as State FROM evd_transaction a, operator b,agent_info c,state_list d  WHERE a.user_id = c.user_id and c.state_id = d.state_id and  a.operator_id = $opr and b.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by State,a.opr_plan_desc, a.operator_id, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and b.agent_code = '$agentName'  and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,b.parent_code, a.operator_id, b.agent_name, b.agent_code,a.opr_plan_desc, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and b.agent_code = '$agentName'  and  a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,b.parent_code,a.opr_plan_desc, a.operator_id, b.agent_name, b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and b.agent_code = '$agentName'  and  a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,b.parent_code,a.opr_plan_desc, a.operator_id, b.agent_name, b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
						}
					}
				}
			}
		 }
		 //=================
			else {
			if($opr == "ALL") {
				if($oprDetail == false) {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT  date(a.date_time) as Date, (a.request_amount) as request_amount,concat (b.agent_name) as agent ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE a.user_id=b.user_id and b.state_id=c.state_id and c.state_id = $state and date(a.date_time) between '$startDate' and '$endDate' ";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(total_amount) as total_amount ,concat (b.agent_name) as agent ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE a.user_id=b.user_id and b.state_id=c.state_id and c.state_id = $state and date(a.date_time) between '$startDate' and '$endDate' ";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(request_amount) as request_amount, SUM(total_amount) as total_amount ,concat (b.agent_name) as agent ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and c.state_id = $state and a.user_id=b.user_id and  date(a.date_time) between '$startDate' and '$endDate'";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,b.parent_code, b.agent_name, b.agent_code,date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id  and  b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id = $state  group by State ,date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and  b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id = $state  group by State, date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id  and  b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  and c.state_id = $state group by State, date(a.date_time) order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id  and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id  and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id  and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
					}
				}
				else {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b,agent_info c,state_list d  WHERE c.state_id = d.state_id and   a.user_id = c.user_id and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' and d.state_id = $state group by State ,a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' ,concat(d.name) as State FROM evd_transaction a, operator b,agent_info c,state_list d  WHERE c.state_id = d.state_id and   a.user_id = c.user_id and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' and d.state_id = $state group by State ,a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' ,concat(d.name) as State FROM evd_transaction a, operator b,agent_info c,state_list d  WHERE c.state_id = d.state_id and   a.user_id = c.user_id and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' and d.state_id = $state group by State, a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id, b.agent_name, b.agent_code,a.opr_plan_desc, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code,a.opr_plan_desc, a.operator_id, b.agent_name, b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent ,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc,b.agent_code, b.agent_name, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b, agent_info c,state_list d  WHERE c.state_id = d.state_id and  a.user_id = c.user_id and c.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' and d.state_id = $state group by State, a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' ,concat(d.name) as State FROM evd_transaction a, operator b, agent_info c,state_list d  WHERE c.state_id = d.state_id and a.user_id = c.user_id and c.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' and d.state_id = $state group by State, a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b, agent_info c,state_list d  WHERE c.state_id = d.state_id and a.user_id = c.user_id and c.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' and d.state_id = $state group by State, a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and b.agent_code = '$agentName'  and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc, b.agent_name,b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and b.agent_code = '$agentName'  and  a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,,a.opr_plan_desc b.agent_name,b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and b.agent_code = '$agentName'  and  a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc,b.agent_code, b.agent_name, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
						}
					}
				}
			}
			else {
				if($oprDetail == false) {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount,concat (b.agent_name) as agent ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE a.user_id=b.user_id and b.state_id=c.state_id and  operator_id = $opr  and c.state_id = $state and date(a.date_time) between '$startDate' and '$endDate'  and c.state_id = $state group by State,agent, date(date_time) order by date(date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(date_time) as Date, SUM(total_amount) as total_amount,concat (b.agent_name) as agent ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE a.user_id=b.user_id and b.state_id=c.state_id and operator_id = $opr and date(date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State, agent ,date(date_time) order by date(date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(date_time) as Date, SUM(request_amount) as request_amount, SUM(total_amount) as total_amount,concat (b.agent_name) as agent ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE a.user_id=b.user_id and b.state_id=c.state_id and operator_id = $opr and date(date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,agent, date(date_time) order by date(date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id=c.state_id and  a.operator_id = $opr and a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,b.parent_code,b.agent_code, b.agent_name, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id=c.state_id and a.operator_id = $opr and a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id=c.state_id and a.operator_id = $opr and a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id = c.state_id and  a.operator_id = $opr and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id = $state  group by State, date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount ,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id = c.state_id and a.operator_id = $opr and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id = $state  group by State, date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount ,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id = c.state_id and a.operator_id = $opr and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State ,date(a.date_time) order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id = c.state_id and a.operator_id = $opr and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,b.parent_code,b.agent_code, b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent ,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id = c.state_id and a.operator_id = $opr and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,b.parent_code,b.agent_code, b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id = c.state_id and a.operator_id = $opr and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,b.parent_code,b.agent_code, b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
					}
				}
				else {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b,agent_info c, state_list d  WHERE c.state_id = d.state_id and  a.user_id = c.user_id and  a.operator_id = $opr and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' and d.state_id = $state group by State, a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b,agent_info c, state_list d  WHERE c.state_id = d.state_id and  a.user_id = c.user_id and  a.operator_id = $opr and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' and d.state_id = $state group by State, a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b,agent_info c, state_list d  WHERE c.state_id = d.state_id and  a.user_id = c.user_id and  a.operator_id = $opr and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' and d.state_id = $state group by State, a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc, b.agent_name,b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id, b.agent_name, b.agent_code,a.opr_plan_desc, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent ,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id, b.agent_name, b.agent_code, a.opr_plan_desc,date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b,agent_info c,state_list d  WHERE a.user_id = c.user_id and c.state_id = d.state_id and  a.operator_id = $opr and c.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' and d.state_id = $state group by State ,a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b,agent_info c,state_list d  WHERE a.user_id = c.user_id and c.state_id = d.state_id and  a.operator_id = $opr and c.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' and d.state_id = $state group by State, a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' ,concat(d.name) as State FROM evd_transaction a, operator b,agent_info c,state_list d  WHERE a.user_id = c.user_id and c.state_id = d.state_id and  a.operator_id = $opr and c.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' and d.state_id = $state group by State,a.opr_plan_desc, a.operator_id, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and b.agent_code = '$agentName'  and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc,b.agent_code, b.agent_name, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'ta') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and b.agent_code = '$agentName'  and  a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc,b.agent_code, b.agent_name, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'bo') {
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and b.agent_code = '$agentName'  and  a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id, b.agent_name, b.agent_code,a.opr_plan_desc, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
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
			$data[] = array("amtype"=>$ba, "creteria"=>$creteria,"agent"=>$row['agent'],"date"=>$row['Date'],"otype"=>$row['Operator'],"state"=>$row['State'],"reamt"=>$row['request_amount'],"toamt"=>$row['total_amount'],"ad"=>$agentDetail,"td"=>$oprDetail);           
		}
		echo json_encode($data);
	}

?>