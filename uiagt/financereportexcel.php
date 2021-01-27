<?php
ERROR_REPORTING(E_ALL);
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
include("excelfunctions.php");
require_once   '../common/PHPExcel/Classes/PHPExcel/IOFactory.php';
	$data = json_decode(file_get_contents("php://input"));
	$type	=  $_POST['type'];
	$agentName	=  $_POST['agentName'];
	$startDate		=  $_POST['startDate'];
	$endDate	=  $_POST['endDate'];
	$creteria 	= $_POST['creteria'];
	$agentDetail 	= $_POST['agentdetail'];
	$typeDetail 	= $_POST['typeDetail'];
	$ba 	= $_POST['ba'];
	$agentName	=   $_SESSION['party_code'];
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
$title = "KadickMoni";
if($startDate == null ){
	$startDate = date('Y-m-d');
}
if($endDate == null ){
		$endDate   =  date('Y-m-d');
}

$msg = "Finance Report For Date between $startDate and $endDate";
$objPHPExcel = new PHPExcel();
			if($type == "ALL") {
				if($typeDetail == false) { error_log(1);
					if($agentDetail == false) {
						if($creteria == "A") {
							if($ba == 'ra') {
								$heading = array("Date","Request Amount");
								$headcount = 2;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and (date(a.date_time)) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date","Total Amount");
								$headcount = 2;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date","Request Amount","Total Amount");
								$headcount = 3;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
							}
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
										$heading = array("Date","Request Amount");
										$headcount = 2;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'ta') {
										$heading = array("Date","Total Amount");
										$headcount = 2;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE  b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'bo') {
										$heading = array("Date","Request Amount","Total Amount");
										$headcount = 3;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$heading = array("Date","Request Amount");
										$headcount = 2;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'ta') {
										$heading = array("Date","Total Amount");
										$headcount = 2;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'bo') {
										$heading = array("Date","Request Amount","Total Amount");
										$headcount = 3;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
									}
								}
							}
							else {
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
										$heading = array("Date","Request Amount", "Sub - Agent");
										$headcount = 3;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by sub_agent, date(a.date_time) order by date(a.date_time), sub_agent";
									}
									if($ba == 'ta') {
										$heading = array("Date","Total Amount", "Sub - Agent");
										$headcount = 3;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE  b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by sub_agent, date(a.date_time) order by date(a.date_time), sub_agent";
									}
									if($ba == 'bo') {
										$heading = array("Date","Request Amount","Total Amount", "Sub - Agent");
										$headcount = 4;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by sub_agent, date(a.date_time) order by date(a.date_time), sub_agent";
									}
								}
								else {
									if($ba == 'ra') {
										$heading = array("Date","Request Amount", "Sub - Agent");
										$headcount = 3;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time), sub_agent order by date(a.date_time), sub_agent";
									}
									if($ba == 'ta') {
										$heading = array("Date","Total Amount", "Sub - Agent");
										$headcount = 3;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time), sub_agent order by date(a.date_time), sub_agent";
									}
									if($ba == 'bo') {
										$heading = array("Date","Request Amount","Total Amount", "Sub - Agent");
										$headcount = 4;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time), sub_agent order by date(a.date_time), sub_agent";
									}
								}
							}								
						}
					}
					else {					
						if($creteria == "A") {
							if($ba == 'ra') {
								$heading = array("Date","Request Amount","Agent");
								$headcount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date","Total Amount","Agent");
								$headcount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date","Request Amount","Total Amount","Agent");
								$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
						if($creteria == "S") {							
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
										$heading = array("Date","Request Amount", "Sub - Agent");
										$headcount = 3;
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE  b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$heading = array("Date","Total Amount", "Sub - Agent");
										$headcount = 3;
										$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$heading = array("Date","Request Amount","Total Amount","Sub - Agent");
										$headcount = 4;
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$heading = array("Date","Request Amount", "Sub - Agent");
										$headcount = 3;
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and  b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$heading = array("Date","Total Amount", "Sub - Agent");
										$headcount = 3;
										$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and  b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$heading = array("Date","Request Amount","Total Amount","Sub - Agent");
										$headcount = 4;
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
								}
							}
							else {								
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
										$heading = array("Date","Request Amount", "Sub - Agent");
										$headcount = 3;
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$heading = array("Date","Total Amount", "Sub - Agent");
										$headcount = 3;
										$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$heading = array("Date","Request Amount","Total Amount","Sub - Agent");
										$headcount = 4;
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$heading = array("Date","Request Amount", "Sub - Agent");
										$headcount = 3;
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$heading = array("Date","Total Amount", "Sub - Agent");
										$headcount = 3;
										$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName'  and b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$heading = array("Date","Request Amount","Total Amount","Sub - Agent");
										$headcount = 4;
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
								error_log(1);
								$heading = array("Date","Request Amount","Order Type");
								$headcount = 3;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time),a.service_feature_code order by date(a.date_time), a.service_feature_code";
							}
							if($ba == 'ta') {
								$heading = array("Date","Total Amount","Order Type");
								$headcount = 3;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time), a.service_feature_code order by date(a.date_time), a.service_feature_code";
							}
							if($ba == 'bo') {
								$heading = array("Date","Request Amount","Total Amount","Order Type");
								$headcount = 4;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time), a.service_feature_code order by a.service_feature_code,date(a.date_time)";
							}
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
										$heading = array("Date","Request Amount","Order Type");
										$headcount = 3;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount,a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
									}
									if($ba == 'ta') {
										$heading = array("Date","Total Amount","Order Type");
										$headcount = 3;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount,a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE  b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
									}
									if($ba == 'bo') {
										$heading = array("Date","Request Amount","Total Amount","Order Type");
										$headcount = 4;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$heading = array("Date","Request Amount","Order Type");
										$headcount = 3;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time), a.service_feature_code order by a.service_feature_code, date(a.date_time)";
									}
									if($ba == 'ta') {
										$heading = array("Date","Total Amount","Order Type");
										$headcount = 3;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount,a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
									}
									if($ba == 'bo') {
										$heading = array("Date","Request Amount","Total Amount","Order Type");
										$headcount = 4;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
									}
								}
							}
							else {
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
										$heading = array("Date","Request Amount","Order Type","Sub - Agent");
										$headcount = 4;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by subagent, date(a.date_time), a.service_feature_code order by a.service_feature_code, date(a.date_time), subagent";
									}
									if($ba == 'ta') {
										$heading = array("Date","Total Amount","Order Type","Sub - Agent");
										$headcount = 4;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE  b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, subagent, date(a.date_time) order by a.service_feature_code, date(date(a.date_time)), subagent";
									}
									if($ba == 'bo') {
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, subagent, date(a.date_time) order by a.service_feature_code, date(date(a.date_time)), subagent";
									}
								}
								else {
									if($ba == 'ra') {
										$heading = array("Date","Request Amount","Order Type","Sub - Agent");
										$headcount = 4;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code, date(a.date_time), subagent order by date(a.date_time), subagent";
									}
									if($ba == 'ta') {
										$heading = array("Date","Total Amount","Order Type","Sub - Agent");
										$headcount = 4;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code, date(a.date_time), subagent order by date(a.date_time), subagent";
									}
									if($ba == 'bo') {
										$heading = array("Date","Request Amount","Order Type","Total Amount","Sub - Agent");
										$headcount = 5;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time),a.service_feature_code, subagent order by a.service_feature_code, date(a.date_time), subagent";
									}
								}
							}								
						}
					}
					else {
						if($creteria == "A") {
							if($ba == 'ra') {
								$heading = array("Date","Request Amount","Order Type","Agent");
								$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, a.service_feature_code, date(a.date_time) order by a.service_feature_code, b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date","Total Amount","Order Type","Agent");
								$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, a.service_feature_code, date(a.date_time) order by a.service_feature_code, b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date","Request Amount","Order Type","Total AMount","Agent");
								$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, a.service_feature_code, date(a.date_time) order by a.service_feature_code, b.agent_name, date(a.date_time)";
							}
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
										$heading = array("Date","Order Type","Request Amount","Sub - Agent");
										$headcount = 4;
										$query ="SELECT date(a.date_time) as Date, a.service_feature_code as 'Order Type', SUM(a.request_amount) as request_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE  b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, a.service_feature_code, date(a.date_time) order by a.service_feature_code, b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$heading = array("Date","Order Type","Total Amount","Sub - Agent");
										$headcount = 4;
										$query ="SELECT date(a.date_time) as Date, a.service_feature_code as 'Order Type', SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, b.agent_name, date(a.date_time) order by a.service_feature_code, b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$heading = array("Date","Order Type","Request Amount","Total Amount","Sub - Agent");
										$headcount = 5;
										$query ="SELECT date(a.date_time) as Date, a.service_feature_code as 'Order Type', SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, b.agent_name, date(a.date_time) order by a.service_feature_code, b.agent_name, date(a.date_time)";
									}
								}
							}
							else {
								if($ba == 'ra') {
									$heading = array("Date","Order Type","Request Amount","Sub - Agent");
									$headcount = 4;
									$query ="SELECT date(a.date_time) as Date, a.service_feature_code as 'Order Type', SUM(a.request_amount) as request_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, parent, subagent, date(a.date_time) order by a.service_feature_code, parent, subagent, date(a.date_time)";
								}
								if($ba == 'ta') {
									$heading = array("Date","Order Type","Total Amount","Sub - Agent");
										$headcount = 4;
									$query ="SELECT date(a.date_time) as Date, a.service_feature_code as 'Order Type', SUM(a.total_amount) as total_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName'  and b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, parent, subagent, date(a.date_time) order by a.service_feature_code, parent, subagent, date(a.date_time)";
								}
								if($ba == 'bo') {
									$heading = array("Date","Order Type","Request Amount","Total Amount","Sub - Agent");
										$headcount = 5;
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
									$heading = array("Date","Request Amount","Order Type","Agent");
									$headcount = 4;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and (date(a.date_time)) between '$startDate' and '$endDate' a.service_feature_code = '$type' group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date","Total Amount","Order Type","Agent");
								$headcount = 4;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' a.service_feature_code = '$type' group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date","Request Amount","Total Amount","Order Type","Agent");
								$headcount = 5;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
							}
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
									$heading = array("Date","Order Type","Request Amount","Sub - Agent");
									$headcount = 4;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'ta') {
										$heading = array("Date","Order Type","Total Amount","Sub - Agent");
										$headcount = 4;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE  b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'bo') {
										$heading = array("Date","Order Type","Request Amount","Total Amount","Sub - Agent");
									$headcount = 5;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
										$heading = array("Date","Order Type","Request Amount","Sub - Agent");
									$headcount = 4;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.service_feature_code = '$type' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'ta') {
										$heading = array("Date","Order Type","Total Amount","Sub - Agent");
										$headcount = 4;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.service_feature_code = '$type' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
									}
									if($ba == 'bo') {
										$heading = array("Date","Order Type","Request Amount","Total Amount","Sub - Agent");
									$headcount = 5;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and a.service_feature_code = '$type' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
									}
								}
							}
							else {
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
										$heading = array("Date","Order Type","Request Amount","Sub - Agent");
									$headcount = 4;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by agent, date(a.date_time) order by date(a.date_time), agent";
									}
									if($ba == 'ta') {
										$heading = array("Date","Order Type","Total Amount","Sub - Agent");
										$headcount = 4;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE  b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.service_feature_code = '$type' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by agent, date(a.date_time) order by date(a.date_time), agent";
									}
									if($ba == 'bo') {
										$heading = array("Date","Order Type","Request Amount","Total Amount","Sub - Agent");
										$headcount = 5;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by agent, date(a.date_time) order by date(a.date_time), agent";
									}
								}
								else {
									if($ba == 'ra') {
										$heading = array("Date","Order Type","Request Amount","Sub - Agent");
									$headcount = 4;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time), agent order by date(a.date_time), agent";
									}
									if($ba == 'ta') {
										$heading = array("Date","Order Type","Total Amount","Sub - Agent");
										$headcount = 4;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time), agent order by date(a.date_time), agent";
									}
									if($ba == 'bo') {
										$heading = array("Date","Order Type","Request Amount","Total Amount","Sub - Agent");
										$headcount = 5;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and a.service_feature_code = '$type' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time), agent order by date(a.date_time), agent";
									}
								}
							}								
						}
					}
					else {
						if($creteria == "A") {
							if($ba == 'ra') {
									$heading = array("Date","Request Amount","Agent");
									$headcount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date","Total Amount","Agent");
									$headcount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date","Request Amount","Total Amount","Agent");
									$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.service_feature_code = '$type' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
										$heading = array("Date","Request Amount","Sub - Agent");
									$headcount = 3;
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE  b.parent_code='$agentName' and b.sub_agent = 'Y' and a.service_feature_code = '$type' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
										$heading = array("Date","Total Amount","Sub - Agent");
									$headcount = 3;
										$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$agentName' and b.sub_agent = 'Y' and a.service_feature_code = '$type' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$heading = array("Date","Request Amount","Total Amount","Sub - Agent");
									$headcount = 4;
										$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
									}
								}
							}
							else {
								if($ba == 'ra') {
									$heading = array("Date","Request Amount","Sub - Agent");
									$headcount = 3;
									$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and a.service_feature_code = '$type' and b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
								}
								if($ba == 'ta') {
									$heading = array("Date","Total Amount","Sub - Agent");
									$headcount = 3;
									$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and a.service_feature_code = '$type' and b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
								}
								if($ba == 'bo') {
									$heading = array("Date","Request Amount","Total Amount","Sub - Agent");
									$headcount = 4;
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
								$heading = array("Date","Request Amount","Order Type");
									$headcount = 3;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time),a.service_feature_code order by date(a.date_time), a.service_feature_code";
							}
							if($ba == 'ta') {
								$heading = array("Date","Total Amount","Order Type");
									$headcount = 3;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time), a.service_feature_code order by date(a.date_time), a.service_feature_code";
							}
							if($ba == 'bo') {
								$heading = array("Date","Request Amount","Total Amount","Order Type");
									$headcount = 3;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.service_feature_code = '$type' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time), a.service_feature_code order by a.service_feature_code,date(a.date_time)";
							}
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
										$heading = array("Date","Request Amount","Order Type");
									$headcount = 3;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount,a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
									}
									if($ba == 'ta') {
										$heading = array("Date","Total Amount","Order Type");
									$headcount = 3;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount,a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE  b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
									}
									if($ba == 'bo') {
										$heading = array("Date","Request Amount","Total Amount","Order Type");
										$headcount = 4;	
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
									}
								}
								else {
									if($ba == 'ra') {
									$heading = array("Date","Request Amount","Order Type");
									$headcount = 3;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and a.service_feature_code = '$type' and b.parent_code = '$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time), a.service_feature_code order by a.service_feature_code, date(a.date_time)";
									}
									if($ba == 'ta') {
										$heading = array("Date","Total Amount","Order Type");
									$headcount = 3;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount,a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and a.service_feature_code = '$type' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
									}
									if($ba == 'bo') {
									$heading = array("Date","Request Amount","Total Amount","Order Type");
									$headcount = 4;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and a.service_feature_code = '$type' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
									}
								}
							}
							else {
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
											$heading = array("Date","Request Amount","Order Type","Sub - Agent");
									$headcount = 4;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by subagent, date(a.date_time), a.service_feature_code, order by a.service_feature_code, date(a.date_time), subagent";
									}
									if($ba == 'ta') {
											$heading = array("Date","Total Amount","Order Type","Sub - Agent");
									$headcount = 4;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE  b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, subagent, date(a.date_time) order by a.service_feature_code, date(date(a.date_time)), subagent";
									}
									if($ba == 'bo') {
										$heading = array("Date","Request Amount","Order Type","Total Amount","Sub - Agent");
									$headcount = 5;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, subagent, date(a.date_time) order by a.service_feature_code, date(date(a.date_time)), subagent";
									}
								}
								else {
									if($ba == 'ra') {
									$heading = array("Date","Request Amount","Order Type","Sub - Agent");
									$headcount = 4;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code, date(a.date_time), subagent order by date(a.date_time), subagent";
									}
									if($ba == 'ta') {
										$heading = array("Date","Total Amount","Order Type","Sub - Agent");
									$headcount = 4;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and b.parent_code = '$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by a.service_feature_code, date(a.date_time), subagent order by date(a.date_time), subagent";
									}
									if($ba == 'bo') {
										$heading = array("Date","Request Amount","Order Type","Total Amount","Sub - Agent");
									$headcount = 5;
										$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', SUM(a.total_amount) as total_amount, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE  b.agent_code = '$subAgentName' and a.service_feature_code = '$type' and b.parent_code = '$agentName' and b.sub_agent = 'Y'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time),a.service_feature_code, subagent order by a.service_feature_code, date(a.date_time), subagent";
									}
								}
							}								
						}
					}
					else {
						if($creteria == "A") {
							if($ba == 'ra') {
								$heading = array("Date","Request Amount","Order Type"," Agent");
									$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, a.service_feature_code, date(a.date_time) order by a.service_feature_code, b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
									$heading = array("Date","Total Amount","Order Type"," Agent");
									$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, a.service_feature_code, date(a.date_time) order by a.service_feature_code, b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date","Request Amount","Order Type","Total Amount","Agent");
									$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.service_feature_code = '$type' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, a.service_feature_code, date(a.date_time) order by a.service_feature_code, b.agent_name, date(a.date_time)";
							}
						}
						if($creteria == "S") {
							if($subAgentDetail == false) {
								if($subAgentName == "ALL") {
									if($ba == 'ra') {
									$heading = array("Date","Order Type","Request Amount","Sub - Agent");
									$headcount = 4;
										$query ="SELECT date(a.date_time) as Date, a.service_feature_code as 'Order Type', SUM(a.request_amount) as request_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE  b.parent_code='$agentName' and b.sub_agent = 'Y' and a.service_feature_code = '$type' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  b.agent_name, a.service_feature_code, date(a.date_time) order by a.service_feature_code, b.agent_name, date(a.date_time)";
									}
									if($ba == 'ta') {
									$heading = array("Date","Order Type","Total Amount","Sub - Agent");
									$headcount = 4;
										$query ="SELECT date(a.date_time) as Date, a.service_feature_code as 'Order Type', SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$agentName' and b.sub_agent = 'Y' and a.service_feature_code = '$type' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, b.agent_name, date(a.date_time) order by a.service_feature_code, b.agent_name, date(a.date_time)";
									}
									if($ba == 'bo') {
										$heading = array("Date","Order Type","Request Amount","Total Amount","Sub - Agent");
									$headcount = 5;
										$query ="SELECT date(a.date_time) as Date, a.service_feature_code as 'Order Type', SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.parent_code='$agentName' and a.service_feature_code = '$type' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, b.agent_name, date(a.date_time) order by a.service_feature_code, b.agent_name, date(a.date_time)";
									}
								}
							}
							else {
								if($ba == 'ra') {
									$heading = array("Date","Order Type","Request Amount","Sub - Agent");
									$headcount = 4;
									$query ="SELECT date(a.date_time) as Date, a.service_feature_code as 'Order Type', SUM(a.request_amount) as request_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and a.service_feature_code = '$type' and b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, parent, subagent, date(a.date_time) order by a.service_feature_code, parent, subagent, date(a.date_time)";
								}
								if($ba == 'ta') {
									$heading = array("Date","Order Type","Total Amount","Sub - Agent");
									$headcount = 4;
									$query ="SELECT date(a.date_time) as Date, a.service_feature_code as 'Order Type', SUM(a.total_amount) as total_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and a.service_feature_code = '$type' and b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, parent, subagent, date(a.date_time) order by a.service_feature_code, parent, subagent, date(a.date_time)";
								}
								if($ba == 'bo') {
										$heading = array("Date","Order Type","Request Amount","Total Amount","Sub - Agent");
									$headcount = 5;
									$query ="SELECT date(a.date_time) as Date, a.service_feature_code as 'Order Type', SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat((select concat(z.agent_name,' [',ifNull((select champion_name from champion_info where champion_code = z.parent_code),'Self'),']') from agent_info z where z.agent_code = b.parent_code)) as parent, concat(b.agent_name,' [',(select agent_name from agent_info where agent_code = b.parent_code),'] ') as subagent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$subAgentName' and a.service_feature_code = '$type' and b.parent_code='$agentName' and b.sub_agent = 'Y' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, parent, subagent, date(a.date_time) order by a.service_feature_code, parent, subagent, date(a.date_time)";
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
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.($row+1), "Row Count: ".($row -1));
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
