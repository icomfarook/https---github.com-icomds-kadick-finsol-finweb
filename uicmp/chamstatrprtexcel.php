<?php
ERROR_REPORTING(E_ALL);
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
include("excelfunctions.php");
require_once   '../common/PHPExcel/Classes/PHPExcel/IOFactory.php';

	$data = json_decode(file_get_contents("php://input"));
	$type	=  $_POST['type'];
	//$agentName	=  $_POST['agentName'];	
	$startDate		=  $_POST['startDate'];
	$endDate	=  $_POST['endDate'];
	$creteria 	= $_POST['creteria'];
	$agentDetail 	= $_POST['agentDetail'];	
	$subAgentName 	= $_POST['subAgentName'];
	$subAgentDetail 	= $_POST['subAgentDetail'];	
	$typeDetail 	= $_POST['typeDetail'];
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
	$title = "KadickMoni";
	$partyType = $_SESSION['party_type'];
	$profileId = $_SESSION['profile_id'];
	$agentName = $_SESSION['party_code'];

	if($startDate == null ){
		$startDate = date('Y-m-d');
	}
	if($endDate == null ){
			$endDate   =  date('Y-m-d');
	}
	error_log($agentName);
	//error_log($endDate);
	$msg = "Stat Report For Date between $startDate and $endDate";
	$objPHPExcel = new PHPExcel();
			if($type == "ALL") {
				if($typeDetail == false) {
					if($agentDetail == false) {
						if($creteria == "A") {
							$heading = array("Date","Count");
							$headcount = 2;
							
							$query ="SELECT date(a.date_time) as Date, count(*) as Count FROM fin_service_order a, champion_info b WHERE b.champion_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									$heading = array("Date","Count");
									$headcount = 2;
									
									$query ="SELECT date(a.date_time) as Date, count(*) as Count FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'N' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
								}
								else {
									$heading = array("Date","Count");
									$headcount = 2;
									
									$query ="SELECT date(a.date_time) as Date, count(*) as Count FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'N' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
								}
							}
							else {
								if($subAgentName == "ALL") {
									$heading = array("Date","Count", "Parent Name");
									$headcount = 3;
									
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'N' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by agent, date(a.date_time) order by agent, date(a.date_time)";
								}
								else {
									$heading = array("Date","Count", "Parent Name");
									$headcount = 3;
									
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'N' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by agent, date(a.date_time) order by agent, date(a.date_time)";
								}
							}
						}
					}
					else {
						if($creteria == "A") {
							$heading = array("Date","Count", "Parent Name");
							$headcount = 3;
							
							$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {		
									$heading = array("Date","Count", "Parent Name");
									$headcount = 3;								
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'N' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by agent, date(a.date_time) order by agent, date(a.date_time)";
								}
								else {
									$heading = array("Date","Count", "Parent Name");
									$headcount = 3;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'N' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by agent, date(a.date_time) order by agent, date(a.date_time)";
								}								
							}
							else {
								if($subAgentName == "ALL") {		
									$heading = array("Date","Count", "Order Type", "Parent",  "Agent Name");
									$headcount = 5;								
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type', concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select champion_name from champion_info where champion_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'N' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by parent, subagent, date(a.date_time) order by parent, subagent, date(a.date_time)";
								}
								else {
									$heading = array("Date","Count", "Order Type", "Parent", "Agent Name");
									$headcount = 5;		
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type', concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select champion_name from champion_info where champion_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'N' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by parent, subagent, date(a.date_time) order by parent, subagent, date(a.date_time)";
								}
							}
						}
					}
				}
				else {
					if($agentDetail == false) {
						if($creteria == "A") {
							$heading = array("Date","Count", "Order Type");
							$headcount = 3;
							$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type' FROM fin_service_order a, champion_info b  WHERE  b.champion_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									$heading = array("Date","Count", "Order Type");
									$headcount = 3;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b  WHERE  b.parent_code = '$agentName' and b.sub_agent = 'N' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
								}
								else {
									$heading = array("Date","Count", "Order Type");
									$headcount = 3;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b  WHERE   b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'N' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
								}
							}
							else {
								if($subAgentName == "ALL") {
									$heading = array("Date","Count", "Order Type", "Agent Name");
									$headcount = 4;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type',concat(b.agent_name,' [',(select champion_name from champion_info where champion_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b  WHERE  b.parent_code = '$agentName' and b.sub_agent = 'N' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, subagent, date(a.date_time) order by a.service_feature_code,date(a.date_time), subagent";
								}
								else {
									$heading = array("Date","Count", "Order Type", "Agent Name");
									$headcount = 4;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type',concat(b.agent_name,' [',(select champion_name from champion_info where champion_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b  WHERE  b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'N' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, subagent, date(a.date_time) order by a.service_feature_code,date(a.date_time), subagent, a.service_feature_code, date(a.date_time)";
								}
							}
						}
					}
					else {
						if($creteria == "A") {
							$heading = array("Date","Count", "Order Type", "Parent Name");
							$headcount = 4;
							$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									$heading = array("Date","Count", "Order Type", "Parent Name");
									$headcount = 4;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type', concat(b.parent_code,'-',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)) as Parent  FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$agentName' and b.sub_agent = 'N' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
								}
								else {
									$heading = array("Date","Count", "Order Type", "Parent Name");
									$headcount = 4;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'N' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
								}
							}
							else {
								if($subAgentName == "ALL") {
									$heading = array("Date","Count", "Order Type", "Parent",  "Agent Name");
									$headcount = 5;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type',concat(b.parent_code,'-',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)) as Parent , concat(b.agent_name,' [',(select champion_name from champion_info where champion_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$agentName' and b.sub_agent = 'N' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
								}
								else {
									$heading = array("Date","Count", "Order Type", "Parent",  "Agent Name");
									$headcount = 5;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type',concat(b.parent_code,'-',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)) as Parent ,concat(b.agent_name,' [',(select champion_name from champion_info where champion_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'N' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
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
							$heading = array("Date","Count");
							$headcount = 2;
							$query ="SELECT date(a.date_time) as Date, count(*) as Count FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									$heading = array("Date","Count");
									$headcount = 2;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'N' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
								}
								else {
									$heading = array("Date","Count");
									$headcount = 2;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'N' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
								}
							}
							else {
								if($subAgentName == "ALL") {
									$heading = array("Date","Count", "Agent Name");
									$headcount = 3;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(b.agent_name,' [',(select champion_name from champion_info where champion_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'N' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time),subagent order by date(a.date_time),subagent";
								}
								else {
									$heading = array("Date","Count", "Agent Name");
									$headcount = 3;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(b.agent_name,' [',(select champion_name from champion_info where champion_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'N' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time),subagent order by date(a.date_time),subagent";
								}
							}
						}
					}
					else {
						if($creteria == "A") {
							$heading = array("Date","Count", "Parent Name");
							$headcount = 3;
							$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and a.service_feature_code = '$type' and a.user_id = b.user_id group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									$heading = array("Date","Count", "Parent Name");
									$headcount = 3;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.parent_code,'-',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)) as Parent  FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$agentName' and b.sub_agent = 'N' and a.service_feature_code = '$type' and a.user_id = b.user_id group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
								}
								else {
									$heading = array("Date","Count", "Parent Name");
									$headcount = 3;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'N' and a.service_feature_code = '$type' and a.user_id = b.user_id group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
								}
							}
							else {
								if($subAgentName == "ALL") {
									$heading = array("Date","Count", "Parent", "Agent Name");
									$headcount = 4;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count,  concat(b.parent_code,'-',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)) as Parent , concat(b.agent_name,' [',(select champion_name from champion_info where champion_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$agentName' and b.sub_agent = 'N' and a.service_feature_code = '$type' and a.user_id = b.user_id group by parent, subagent, date(a.date_time) order by parent, subagent, date(a.date_time)";
								}
								else {
									$heading = array("Date","Count", "Parent", "Agent Name");
									$headcount = 4;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.parent_code,'-',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)) as Parent ,concat(b.agent_name,' [',(select champion_name from champion_info where champion_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'N' and a.service_feature_code = '$type' and a.user_id = b.user_id group by parent, subagent, date(a.date_time) order by parent, subagent, date(a.date_time)";
								}
							}								
						}
					}					
				}
				else {
					if($agentDetail == false) {
						if($creteria == "A") {
							$heading = array("Date","Count", "Order Type");
							$headcount = 3;
							$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b  WHERE  b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									$heading = array("Date","Count", "Order Type");
									$headcount = 3;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b  WHERE  b.parent_code = '$agentName' and b.sub_agent = 'N' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
								}
								else {
									$heading = array("Date","Count",  "Order Type");
									$headcount = 3;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b  WHERE  b.parent_code = '$agentName' and  b.agent_code = '$subAgentName' and b.sub_agent = 'N' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
								}
							}
							else {
								if($subAgentName == "ALL") {
									$heading = array("Date","Count",  "Order Type", "Agent Name");
									$headcount = 4;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type',concat(b.agent_name,' [',(select champion_name from champion_info where champion_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b  WHERE  b.parent_code = '$agentName' and b.sub_agent = 'N' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time), subagent order by a.service_feature_code,date(a.date_time), subagent";
								}
								else {
									$heading = array("Date","Count", "Order Type", "Agent Name");
									$headcount = 4;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type',concat(b.agent_name,' [',(select champion_name from champion_info where champion_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b  WHERE  b.parent_code = '$agentName' and  b.agent_code = '$subAgentName' and b.sub_agent = 'N' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time), subagent order by a.service_feature_code,date(a.date_time), subagent";
								}
							}
						}
					}
					else {
						if($creteria == "A") {
							$heading = array("Date","Count", "Order Type", "Parent");
							$headcount = 4;
							$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and b.agent_code = '$agentName' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									$heading = array("Date","Count", "Order Type", "Parent");
									$headcount = 4;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type', concat(b.parent_code,'-',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)) as Parent  FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and b.parent_code = '$agentName' and b.sub_agent = 'N' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
								}
								else {
									$heading = array("Date","Count", "Order Type", "Parent");
									$headcount = 4;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type', concat(b.parent_code,'-',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)) as Parent  FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'N' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
								}
							}
							else {
								if($subAgentName == "ALL") {
									$heading = array("Date","Count", "Order Type", "Parent", "Agent");
									$headcount = 5;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type', concat(b.parent_code,'-',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)) as Parent  , concat(b.agent_name,' [',(select champion_name from champion_info where champion_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and b.parent_code = '$agentName' and b.sub_agent = 'N' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time), parent, subagent order by  a.service_feature_code,b.agent_name, date(date_time), parent, subagent";
								}
								else {
									$heading = array("Date","Count", "Order Type", "Parent", "Agent");
									$headcount = 5;
									$query ="SELECT date(a.date_time) as Date, count(*) as Count, a.service_feature_code as 'Order Type',concat(b.parent_code,'-',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)) as Parent  , concat(b.agent_name,' [',(select champion_name from champion_info where champion_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and b.parent_code = '$agentName' and b.agent_code = '$subAgentName' and b.sub_agent = 'N' and a.user_id = b.user_id group by a.service_feature_code, parent,subagent, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time), parent,subagent";
								}
							}
						}
					}
					
				}
			}
			
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		
		error_log($query);
		heading($heading,$objPHPExcel,$headcount);
		$i = 2;						
		while ($row = mysqli_fetch_array($result))	{
			generateExcel ($i, $row,$objPHPExcel,$headcount);
			$i++;				
		}
		$row = $objPHPExcel->getActiveSheet()->getHighestRow();
		$objPHPExcel->getActiveSheet()->getStyle( 'A'.($row+1) )->getFont()->setBold( true );
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.($row+1), "Row Count : ".($row -1));
		
	  	//error_log($query);
			
		$objPHPExcel->getProperties()
					->setCreator($userName)
					->setLastModifiedBy($userName)
					->setTitle($msg)
					->setSubject($msg)
					->setDescription($msg)
					->setKeywords($msg)
					->setCategory($msg);
		$objPHPExcel->getActiveSheet()->setTitle($title);							
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$msg.'.xls"');
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header ('Cache-Control: cache, must-revalidate'); 
		header ('Pragma: public');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();
		$objWriter->save('php://output');
		exit;	

?>
