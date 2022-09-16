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
	$agentDetail 	= $_POST['agentDetail'];	
	$subAgentName 	= $_POST['subAgentName'];
	$subAgentDetail 	= $_POST['subAgentDetail'];	
	$typeDetail 	= $_POST['typeDetail'];
	$state 	= $_POST['state'];
	$local_govt_id 	= $_POST['local_govt_id'];
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
	$sesion_party_code = $_SESSION['party_code'];
	
$title = "KadickMoni";
if($startDate == null ){
	$startDate = date('Y-m-d');
}
if($endDate == null ){
		$endDate   =  date('Y-m-d');
}
$msg = "Bill_Payment_Stat Report For Date between $startDate and $endDate";
$objPHPExcel = new PHPExcel();
			if($state == "ALL"){
				if($type == "ALL") {
					if($typeDetail == false) {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$heading = array("Date","Count","State","Local Government");
								$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local  FROM bp_service_order a, bp_request b,agent_info c,state_list d WHERE a.user_id = c.user_id and c.state_id=d.state_id and a.bp_service_order_no = b.order_no and b.status = 'S' and c.agent_code='$sesion_party_code'   and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time),State order by date(a.date_time)";
							}
							else {
								$heading = array("Date","Count","Agent Name","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent FROM bp_service_order a, agent_info b, bp_request c,state_list d WHERE b.state_id = d.state_id and c.order_no = a.bp_service_order_no and c.status = 'S' and b.agent_code='$sesion_party_code'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time),b.parent_code, State  order by b.agent_name, date(a.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$heading = array("Date","Count","State","Local Government");
							$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, bp_request c,state_list d  WHERE b.state_id = d.state_id and c.order_no = a.bp_service_order_no and c.status = 'S' and b.agent_code='$sesion_party_code'    and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time),State  order by date(a.date_time)";
							}
							else {
								$heading = array("Date","Count","State","Local Government","Agent Name");
							$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent FROM bp_service_order a, agent_info b, bp_request c,state_list d WHERE b.state_id = d.state_id and a.bp_service_order_no = c.order_no and c.status = 'S' and b.agent_code='$sesion_party_code'    and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, b.parent_code, date(a.date_time),State  order by b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$heading = array("Date","Count","Order Type","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type',concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, bp_request b, service_feature c,agent_info d,state_list e WHERE a.user_id = d.user_id and d.state_id = e.state_id and a.bp_service_order_no = b.order_no and b.status = 'S' and c.agent_code='$sesion_party_code'   and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) ,State  order by a.service_feature_code, date(a.date_time)";
							}
							else {
								$heading = array("Date","Count","Order Type","Agent Name","State","Local Government");
								$headcount = 6;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, service_feature c, bp_request d,state_list e WHERE b.state_id = e.state_id and a.bp_service_order_no = d.order_no and d.status = 'S' and b.agent_code='$sesion_party_code' and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id  group by b.parent_code, a.service_feature_code, agent, date(a.date_time),State order by a.service_feature_code, agent, date(date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$heading = array("Date","Count","Order Type","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type',concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, service_feature c, bp_request d,state_list e WHERE b.state_id=e.state_id and a.bp_service_order_no = d.order_no and d.status = 'S' and b.agent_code='$sesion_party_code' and a.service_feature_code = c.feature_code  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'   group by  State  ,a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							else {
								$heading = array("Date","Count","Order Type","Agent Name","State","Local Government");
								$headcount =6;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, service_feature c, bp_request d,state_list e WHERE b.state_id = e.state_id and a.bp_service_order_no = d.order_no and d.status = 'S' and b.agent_code='$sesion_party_code' and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate'  and a.user_id = b.user_id  group by b.parent_code,  State  ,a.service_feature_code, b.agent_name, date(date_time) order by a.service_feature_code,b.agent_name, date(date_time)";
							}
						}
					}
				}
				else {
					if($typeDetail == false) {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$heading = array("Date","Count","State","Local Government");
							$headcount =4;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, bp_request b,agent_info c,state_list d WHERE a.user_id = c.user_id and c.state_id = d.state_id and a.bp_service_order_no = b.order_no and b.status = 'S' and c.agent_code='$sesion_party_code'   and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'  group by  State  ,date(a.date_time) order by date(a.date_time)";
							}
							else {
								$heading = array("Date","Count","Agent Name","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, bp_request c,state_list d WHERE b.state_id=d.state_id and a.bp_service_order_no = c.order_no and c.status = 'S' and b.agent_code='$sesion_party_code'   and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  group by b.parent_code,  State  ,b.agent_name, date(date_time) order by  b.agent_name, date(date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$heading = array("Date","Count","State","Local Government");
							$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, bp_request c,state_list d WHERE b.state_id= d.state_id and  a.bp_service_order_no = c.order_no and c.status = 'S' and b.agent_code='$sesion_party_code'    and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'  group by  State  ,date(a.date_time) order by date(a.date_time)";
							}
							else {
								$heading = array("Date","Count","Agent Name","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, bp_request c,state_list d WHERE b.state_id=d.state_id and a.bp_service_order_no = c.order_no and c.status = 'S' and b.agent_code='$sesion_party_code'   and date(a.date_time) between '$startDate' and '$endDate'  and a.service_feature_code = '$type' and a.user_id = b.user_id  group by b.parent_code,  State  ,b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$heading = array("Date","Count","Order Type","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, bp_request b, service_feature c,agent_info d,state_list e WHERE a.user_id= d.user_id and d.state_id=e.state_id and a.bp_service_order_no = b.order_no and b.status = 'S' and c.agent_code='$sesion_party_code'   and a.service_feature_code = c.feature_code and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'   group by  State  ,a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
							}
							else {
								$heading = array("Date","Count","Order Type","Agent Name","State","Local Government");
							$headcount = 6;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(b.agent_name,'[',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, service_feature c, bp_request d,state_list e WHERE b.state_id = e.state_id and a.bp_service_order_no = d.order_no and d.status = 'S'  and b.agent_code='$sesion_party_code'  and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and a.user_id = b.user_id  group by b.parent_code,   State  ,a.service_feature_code, b.agent_name, date(date_time) order by a.service_feature_code, b.agent_name, date(date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$heading = array("Date","Count","Order Type","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, service_feature c, bp_request d,state_list e WHERE b.state_id = e.state_id  and a.bp_service_order_no = d.order_no and d.status = 'S' and b.agent_code='$sesion_party_code'  and a.service_feature_code = c.feature_code  and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'   group by  State  ,a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							else {
								$heading = array("Date","Count","Order Type","Agent Name","State","Local Government");
							$headcount = 6;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, service_feature c, bp_request d,state_list e WHERE b.state_id = e.state_id and a.bp_service_order_no = d.order_no and d.status = 'S' and b.agent_code='$sesion_party_code'  and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  and a.user_id = b.user_id  group by b.parent_code,   State  ,a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
							}
						}
					}
				}
				
			}	
			
			
			//========================
			else {
			if($local_govt_id == ""){
				if($type == "ALL") {
					if($typeDetail == false) {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$heading = array("Date","Count","State","Local Goverment");
							$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local  FROM bp_service_order a, bp_request b,agent_info c,state_list d WHERE a.user_id = c.user_id and c.state_id=d.state_id and a.bp_service_order_no = b.order_no and b.status = 'S' and c.agent_code='$sesion_party_code'   and  d.state_id=$state and date(a.date_time) between '$startDate' and '$endDate' group by  State  ,date(a.date_time) order by date(a.date_time)";
							}
							else {
								$heading = array("Date","Count","Agent Name","State","Local Goverment");
							$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent FROM bp_service_order a, agent_info b, bp_request c,state_list d WHERE b.state_id = d.state_id and c.order_no = a.bp_service_order_no and c.status = 'S' and b.agent_code='$sesion_party_code'   and d.state_id=$state  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.parent_code,  State  ,b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$heading = array("Date","Count","State","Local Goverment");
							$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, bp_request c,state_list d  WHERE b.state_id = d.state_id and c.order_no = a.bp_service_order_no and c.status = 'S' and b.agent_code='$sesion_party_code'   and  d.state_id=$state  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  State  ,date(a.date_time) order by date(a.date_time)";
							}
							else {
								$heading = array("Date","Count","Agent Name","State","Local Govement");
							$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent FROM bp_service_order a, agent_info b, bp_request c,state_list d WHERE b.state_id = d.state_id and a.bp_service_order_no = c.order_no and c.status = 'S' and b.agent_code='$sesion_party_code'   and  d.state_id=$state  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.parent_code,   State  ,b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$heading = array("Date","Count","Order Type","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type',concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, bp_request b, service_feature c,agent_info d,state_list e WHERE a.user_id = d.user_id and d.state_id = e.state_id and a.bp_service_order_no = b.order_no and b.status = 'S' and c.agent_code='$sesion_party_code'   and  e.state_id=$state and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate' group by  State  ,a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
							}
							else {
								$heading = array("Date","Count","Order Type","Agent Name","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, service_feature c, bp_request d,state_list e WHERE b.state_id = e.state_id and a.bp_service_order_no = d.order_no and d.status = 'S' and b.agent_code='$sesion_party_code'  and  e.state_id=$state  and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by b.parent_code,   State  ,a.service_feature_code, agent, date(date_time) order by a.service_feature_code, agent, date(date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$heading = array("Date","Count","Order Type","State","Local Government");
							$headcount =5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type',concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, service_feature c, bp_request d,state_list e WHERE b.state_id=e.state_id and a.bp_service_order_no = d.order_no and d.status = 'S' and b.agent_code='$sesion_party_code'  and  e.state_id=$state and a.service_feature_code = c.feature_code  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by  State  ,a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							else {
								$heading = array("Date","Count","Order Type","Agent Name","State","Local Government");
							$headcount =6;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, service_feature c, bp_request d,state_list e WHERE b.state_id = e.state_id and a.bp_service_order_no = d.order_no and d.status = 'S' and b.agent_code='$sesion_party_code'  and  e.state_id=$state and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate'  and a.user_id = b.user_id group by b.parent_code, State  ,a.service_feature_code, b.agent_name, date(date_time) order by a.service_feature_code,b.agent_name, date(date_time)";
							}
						}
					}
				}
				else {
					if($typeDetail == false) {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$heading = array("Date","Count","State","Local Government");
							$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, bp_request b,agent_info c,state_list d WHERE a.user_id = c.user_id and c.state_id = d.state_id, a.bp_service_order_no = b.order_no and b.status = 'S' and c.agent_code='$sesion_party_code'   and  d.state_id=$state  and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'group by  State  ,date(a.date_time) order by date(a.date_time)";
							}
							else {
								$heading = array("Date","Count","Order Type","State","Local Government");
							$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, bp_request c,state_list d WHERE b.state_id=d.state_id and a.bp_service_order_no = c.order_no and c.status = 'S' and b.agent_code='$sesion_party_code'   and  d.state_id=$state and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by  State  ,b.agent_name, date(a.date_time),b.parent_code order by  b.agent_name, date(date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$heading = array("Date","Count","State","Local Government");
							$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, bp_request c,state_list d WHERE b.state_id= d.state_id and  a.bp_service_order_no = c.order_no and c.status = 'S' and b.agent_code='$sesion_party_code'   and  d.state_id=$state  and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'group by  State  ,date(a.date_time) order by date(a.date_time)";
							}
							else {
								$heading = array("Date","Count","Order Type","State","Local Goverment");
							$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, bp_request c,state_list d WHERE b.state_id=d.state_id and a.bp_service_order_no = c.order_no and c.status = 'S' and b.agent_code='$sesion_party_code'   and  d.state_id=$state and date(a.date_time) between '$startDate' and '$endDate'  and a.service_feature_code = '$type' and a.user_id = b.user_id group by  State,b.parent_code  ,b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$heading = array("Date","Count","Order Type","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, bp_request b, service_feature c,agent_info d,state_list e WHERE a.user_id= d.user_id and d.state_id=e.state_id and a.bp_service_order_no = b.order_no and b.status = 'S' and c.agent_code='$sesion_party_code'   and  e.state_id=$state and a.service_feature_code = c.feature_code and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by  State  ,a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
							}
							else {
								$heading = array("Date","Count","Order Type","Agent Name","State","Local Government");
							$headcount = 6;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(b.agent_name,'[',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, service_feature c, bp_request d,state_list e WHERE b.state_id = e.state_id and a.bp_service_order_no = d.order_no and d.status = 'S' and b.agent_code='$sesion_party_code' and  e.state_id=$state and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and a.user_id = b.user_id group by  State, b.parent_code ,a.service_feature_code, b.agent_name, date(a.date_time) order by a.service_feature_code, b.agent_name, date(date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$heading = array("Date","Count","Order Type","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, service_feature c, bp_request d,state_list e WHERE b.state_id = e.state_id  and a.bp_service_order_no = d.order_no and d.status = 'S'  and b.agent_code='$sesion_party_code' and  e.state_id=$state and a.service_feature_code = c.feature_code  and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by  State  ,a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							else {
								$heading = array("Date","Count","Order Type","Agent Name","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, service_feature c, bp_request d,state_list e WHERE b.state_id = e.state_id and a.bp_service_order_no = d.order_no and d.status = 'S'   and b.agent_code='$sesion_party_code' and  e.state_id=$state and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  and a.user_id = b.user_id group by b.parent_code, State  ,a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
							}
						}
					}
				}
			}else{
				if($type == "ALL") {
					if($typeDetail == false) {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$heading = array("Date","Count","State","Local Government");
							$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local  FROM bp_service_order a, bp_request b,agent_info c,state_list d WHERE a.user_id = c.user_id and c.state_id=d.state_id and a.bp_service_order_no = b.order_no and b.status = 'S' and c.agent_code='$sesion_party_code'   and  c.state_id = '$state' and c.local_govt_id = '$local_govt_id' and date(a.date_time) between '$startDate' and '$endDate'   group by  State  ,date(a.date_time) order by date(a.date_time)";
							}
							else {
								$heading = array("Date","Count","Agent Name","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent FROM bp_service_order a, agent_info b, bp_request c,state_list d WHERE b.state_id = d.state_id and c.order_no = a.bp_service_order_no and c.status = 'S' and b.agent_code='$sesion_party_code'   and b.state_id = '$state' and b.local_govt_id = '$local_govt_id'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by b.parent_code,  State  ,b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$heading = array("Date","Count","State","Local Government");
							$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, bp_request c,state_list d  WHERE b.state_id = d.state_id and c.order_no = a.bp_service_order_no and c.status = 'S' and b.agent_code='$sesion_party_code'   and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'   group by  State  ,date(a.date_time) order by date(a.date_time)";
							}
							else {
								$heading = array("Date","Count","Agent Name","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent FROM bp_service_order a, agent_info b, bp_request c,state_list d WHERE b.state_id = d.state_id and a.bp_service_order_no = c.order_no and c.status = 'S' and b.agent_code='$sesion_party_code'   and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id'  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by b.parent_code,   State  ,b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$heading = array("Date","Count","Order Type","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type',concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, bp_request b, service_feature c,agent_info d,state_list e WHERE a.user_id = d.user_id and d.state_id = e.state_id and a.bp_service_order_no = b.order_no and b.status = 'S' and c.agent_code='$sesion_party_code'   and  d.state_id = '$state' and d.local_govt_id = '$local_govt_id' and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate'   group by  State  ,a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
							}
							else {
								$heading = array("Date","Count","Order Type","Agent Name","State","Local Government");
							$headcount = 6;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, service_feature c, bp_request d,state_list e WHERE b.state_id = e.state_id and a.bp_service_order_no = d.order_no and d.status = 'S' and b.agent_code='$sesion_party_code'  and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id'  and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id  group by b.parent_code,   State  ,a.service_feature_code, agent, date(date_time) order by a.service_feature_code, agent, date(date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$heading = array("Date","Count","Order Type","State","Local Government");
							$headcount =5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type',concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, service_feature c, bp_request d,state_list e WHERE b.state_id=e.state_id and a.bp_service_order_no = d.order_no and d.status = 'S' and b.agent_code='$sesion_party_code' and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and a.service_feature_code = c.feature_code  and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'   group by  State  ,a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							else {
								$heading = array("Date","Count","Order Type","Agent Name","State","Local Government");
							$headcount =6;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, service_feature c, bp_request d,state_list e WHERE b.state_id = e.state_id and a.bp_service_order_no = d.order_no and d.status = 'S' and b.agent_code='$sesion_party_code' and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate'  and a.user_id = b.user_id  group by b.parent_code, State  ,a.service_feature_code, b.agent_name, date(date_time) order by a.service_feature_code,b.agent_name, date(date_time)";
							}
						}
					}
				}
				else {
					if($typeDetail == false) {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$heading = array("Date","Count","State","Local Government");
							$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, bp_request b,agent_info c,state_list d WHERE a.user_id = c.user_id and c.state_id = d.state_id, a.bp_service_order_no = b.order_no and b.status = 'S' and c.agent_code='$sesion_party_code'   and  c.state_id = '$state' and c.local_govt_id = '$local_govt_id'  and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'  group by  State  ,date(a.date_time) order by date(a.date_time)";
							}
							else {
								$heading = array("Date","Count","Agent Name","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, bp_request c,state_list d WHERE b.state_id=d.state_id and a.bp_service_order_no = c.order_no and c.status = 'S' and b.agent_code='$sesion_party_code'   and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type'   group by  State  ,b.agent_name, date(a.date_time),b.parent_code order by  b.agent_name, date(date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$heading = array("Date","Count","State","Local Government");
							$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, bp_request c,state_list d WHERE b.state_id= d.state_id and  a.bp_service_order_no = c.order_no and c.status = 'S' and b.agent_code='$sesion_party_code'   and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id'  and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'  group by  State  ,date(a.date_time) order by date(a.date_time)";
							}
							else {
								$heading = array("Date","Count","Agent Name","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, bp_request c,state_list d WHERE b.state_id=d.state_id and a.bp_service_order_no = c.order_no and c.status = 'S' and b.agent_code='$sesion_party_code'   and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and date(a.date_time) between '$startDate' and '$endDate'  and a.service_feature_code = '$type' and a.user_id = b.user_id   group by  State,b.parent_code  ,b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentName == "ALL"){
							if($agentDetail == false) {
								$heading = array("Date","Count","Order Type","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, bp_request b, service_feature c,agent_info d,state_list e WHERE a.user_id= d.user_id and d.state_id=e.state_id and a.bp_service_order_no = b.order_no and b.status = 'S' and c.agent_code='$sesion_party_code'   and  d.state_id = '$state' and d.local_govt_id = '$local_govt_id' and a.service_feature_code = c.feature_code and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'   group by  State  ,a.service_feature_code, date(a.date_time) order by a.service_feature_code, date(a.date_time)";
							}
							else {
								$heading = array("Date","Count","Order Type","Agent Name","State","Local Government");
							$headcount = 6;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(b.agent_name,'[',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, service_feature c, bp_request d,state_list e WHERE b.state_id = e.state_id and a.bp_service_order_no = d.order_no and d.status = 'S' and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and a.user_id = b.user_id   group by  State, b.parent_code ,a.service_feature_code, b.agent_name, date(a.date_time) order by a.service_feature_code, b.agent_name, date(date_time)";
							}
						}
						else {
							if($agentDetail == false) {
								$heading = array("Date","Count","Order Type","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, service_feature c, bp_request d,state_list e WHERE b.state_id = e.state_id  and a.bp_service_order_no = d.order_no and d.status = 'S' and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and a.service_feature_code = c.feature_code  and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'   group by  State  ,a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							else {
								$heading = array("Date","Count","Order Type","Agent Name","State","Local Government");
							$headcount = 6;
								$query ="SELECT date(a.date_time) as Date, count(*) as Count, concat(a.service_feature_code, ' - ', c.feature_description) as 'Order Type', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM bp_service_order a, agent_info b, service_feature c, bp_request d,state_list e WHERE b.state_id = e.state_id and a.bp_service_order_no = d.order_no and d.status = 'S' and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and a.service_feature_code = c.feature_code and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type'  and a.user_id = b.user_id  group by b.parent_code, State  ,a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
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
		
		error_log("excel stat report query = ".$query);
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