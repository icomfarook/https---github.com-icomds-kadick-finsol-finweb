 <?php
 ERROR_REPORTING(E_ALL);
$data = json_decode(file_get_contents("php://input"));
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
include("excelfunctions.php");
require_once   '../common/PHPExcel/Classes/PHPExcel/IOFactory.php';
	$type	=  $_POST['type'];
	$agentName	=  $_POST['agentName'];	
	$startDate		=  $_POST['startDate'];
	$endDate	=  $_POST['endDate'];
	$creteria 	= $_POST['creteria'];
	$agentDetail 	= $_POST['agentdetail'];	
	$subAgentName 	= $_POST['subAgentName'];
	$subAgentDetail 	= $_POST['subAgentDetail'];	
	$typeDetail 	= $_POST['typeDetail'];
	$state 	= $_POST['state'];
	$local_govt_id 	= $_POST['local_govt_id'];
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
$title = "KadickMoni";
 
if($startDate == null ){
	$startDate = date('Y-m-d');
}
if($endDate == null ){
		$endDate   =  date('Y-m-d');
}
$msg = "EVD Stat Report For Date between $startDate and $endDate";
$objPHPExcel = new PHPExcel();
				
			if ($state == "ALL"){
				if($type == "ALL") {
					if($typeDetail == false) {
						if($agentName == "ALL"){
							if($agentDetail == false) {
							$heading = array("Date","Count","State","Local Government");
							$headcount = 3;
								$query ="SELECT date(b.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and  date(b.date_time) between '$startDate' and '$endDate' group by date(b.date_time),State,local order by date(b.date_time)";
							}
							else {
							$heading = array("Date","Count","Agent","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State ,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and  date(c.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(c.date_time),State,local,b.parent_code order by b.agent_name, date(c.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
							$heading = array("Date","Count","State","Local Government");
							$headcount = 4;
								$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and   b.agent_code = '$agentName'  and date(c.date_time) between '$startDate' and '$endDate' group by date(c.date_time),State,local order by date(c.date_time)";
							}
							else {
							$heading = array("Date","Count","Agent","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and  b.agent_code = '$agentName' and date(c.date_time) between '$startDate' and '$endDate'   group by  State,local,b.parent_code ,b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
							}
						}
					}
					else {
						if($agentName == "ALL"){
							if($agentDetail == false) {
							$heading = array("Date","Count","Operator","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(b.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State,(select name from local_govt_list where local_govt_id=d.local_govt_id) as local FROM  evd_transaction b, operator c,agent_info d,state_list e WHERE b.operator_id = c.operator_id and b.user_id = d.user_id and d.state_id = e.state_id and date(b.date_time) between '$startDate' and '$endDate'   group by  State,local, b.operator_id, date(b.date_time) order by b.operator_id, date(b.date_time)";
							}
							else {
							$heading = array("Date","Count","Operator","Agent","State","Local Government");
							$headcount = 6;
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and date(d.date_time) between '$startDate' and '$endDate'    group by  State,local,b.parent_code, d.operator_id, agent, date(d.date_time) order by d.operator_id, agent, date(d.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
							$heading = array("Date","Count","Operator","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and b.agent_code = '$agentName' and  date(d.date_time) between '$startDate' and '$endDate'   group by  State,local, d.operator_id, date(d.date_time) order by d.operator_id,date(d.date_time)";
							}
							else {
							$heading = array("Date","Count","Operator","Agent","State","Local Government");
							$headcount = 6;
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local  FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and date(d.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName'    group by  State,local,b.parent_code, d.operator_id, b.agent_name, date(d.date_time) order by d.operator_id,b.agent_name, date(d.date_time)";
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
								$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and and b.operator_id = '$type' and date(b.date_time) between '$startDate' and '$endDate'  group by  State,local, date(b.date_time) order by date(b.date_time)";
							}
							else {
							$heading = array("Date","Count","Agent","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and date(c.date_time) between '$startDate' and '$endDate' and c.operator_id = '$type'   group by  State,local,b.parent_code, b.agent_name, date(c.date_time) order by  b.agent_name, date(c.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
							$heading = array("Date","Count","State","Local Government");
							$headcount = 4;
								$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local  FROM  agent_info b, evd_transaction c, state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and b.agent_code = '$agentName'  and c.operator_id = '$type' and date(c.date_time) between '$startDate' and '$endDate'  group by  State,local, date(c.date_time) order by date(c.date_time)";
							}
							else {
							$heading = array("Date","Count","Agent","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and date(c.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and c.operator_id = '$type'    group by  State,local,b.parent_code, b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
							}
						}
					}
					else {
						if($agentName == "ALL"){
							if($agentDetail == false) {
							$heading = array("Date","Count","Operator","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local  FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  b.operator_id = '$type' and date(b.date_time) between '$startDate' and '$endDate'   group by  State,local, b.operator_id, date(b.date_time) order by b.operator_id, date(b.date_time)";
							}
							else {
							$heading = array("Date","Count","Operator","Agent","State","Local Government");
							$headcount = 6;
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,'[',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local  FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  date(d.date_time) between '$startDate' and '$endDate' and d.operator_id = '$type'    group by  State,local,b.parent_code, d.operator_id, b.agent_name, date(d.date_time) order by d.operator_id, b.agent_name, date(d.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
							$heading = array("Date","Count","Operator","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and   b.agent_code = '$agentName' and d.operator_id  = '$type' and date(d.date_time) between '$startDate' and '$endDate'   group by  State,local, d.operator_id, date(d.date_time) order by d.operator_id,date(d.date_time)";
							}
							else {
							$heading = array("Date","Count","Operator","Agent","State","Local Government");
							$headcount = 6;
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  date(d.date_time) between '$startDate' and '$endDate' and d.operator_id  = '$type' and b.agent_code = '$agentName'    group by  State,local,b.parent_code, d.operator_id, b.agent_name, date(d.date_time) order by  d.operator_id,b.agent_name, date(d.date_time)";
							}
						}
					}
				}
			}
			//=========
				else {
					if($local_govt_id == ""){
						if($type == "ALL") {
							if($typeDetail == false) {
								if($agentName == "ALL"){
									if($agentDetail == false) {
										$heading = array("Date","Count","State");
										$headcount = 3;
										$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and  d.state_id=$state and date(b.date_time) between '$startDate' and '$endDate' group by  State, date(b.date_time) order by date(b.date_time)";
									}
							else {
							$heading = array("Date","Count","Agent","State");
							$headcount = 4;
								$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State  FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and  d.state_id=$state and  date(c.date_time) between '$startDate' and '$endDate' group by  State,b.parent_code, b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
							$heading = array("Date","Count","State");
							$headcount = 3;
								$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and   b.agent_code = '$agentName' and d.state_id=$state and  date(c.date_time) between '$startDate' and '$endDate' group by  State, date(c.date_time) order by date(c.date_time)";
							}
							else {
							$heading = array("Date","Count","Agent","State");
							$headcount = 4;
								$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and  b.agent_code = '$agentName'  and d.state_id=$state and  date(c.date_time) between '$startDate' and '$endDate' group by  State, b.parent_code, b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
							}
						}
					}
					else {
						if($agentName == "ALL"){
							if($agentDetail == false) {
							$heading = array("Date","Count","Operator","State");
							$headcount = 4;
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State  FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and b.operator_id = c.operator_id and  e.state_id=$state and  date(b.date_time) between '$startDate' and '$endDate' group by  State, b.operator_id, date(b.date_time) order by b.operator_id, date(b.date_time)";
							}
							else {
							$heading = array("Date","Count","Operator","Agent","State");
							$headcount = 5;
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and e.state_id=$state and  date(d.date_time) between '$startDate' and '$endDate'   group by  State,b.parent_code, d.operator_id, agent, date(d.date_time) order by d.operator_id, agent, date(d.date_time)";
							}
						}
						else {
						$heading = array("Date","Count","Operator","State");
							$headcount = 4;
							if($agentDetail == false) {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id andb.agent_code = '$agentName'   e.state_id=$state and  date(d.date_time) between '$startDate' and '$endDate' group by  State, d.operator_id, date(d.date_time) order by d.operator_id,date(d.date_time)";
							}
							else {
							$heading = array("Date","Count","Operator","Agent","State");
							$headcount = 5;
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State  FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  e.state_id=$state and  date(d.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName'  group by  State,b.parent_code, d.operator_id, b.agent_name, date(d.date_time) order by d.operator_id,b.agent_name, date(d.date_time)";
							}
						}
					}
				}
				else {
					if($typeDetail == false) {
						if($agentName == "ALL"){
							if($agentDetail == false) {
							$heading = array("Date","Count","State");
							$headcount = 3;
								$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and b.operator_id = '$type'  and d.state_id=$state and  date(b.date_time) between '$startDate' and '$endDate'group by  State, date(b.date_time) order by date(b.date_time)";
							}
							else {
							$heading = array("Date","Count","Agent","State");
							$headcount = 4;
								$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list dWHERE b.user_id = c.user_id and b.state_id = d.state_id and d.state_id=$state and  date(c.date_time) between '$startDate' and '$endDate' and c.operator_id = '$type' group by  State,b.parent_code, b.agent_name, date(c.date_time) order by  b.agent_name, date(c.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
							$heading = array("Date","Count","State");
							$headcount = 3;
								$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State  FROM  agent_info b, evd_transaction c, state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and b.agent_code = '$agentName' and c.operator_id = '$type' and d.state_id=$state and date(c.date_time) between '$startDate' and '$endDate'group by  State,b.parent_code, date(c.date_time) order by date(c.date_time)";
							}
							else {
							$heading = array("Date","Count","Agent","State");
							$headcount = 4;
								$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and d.state_id=$state and date(c.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and c.operator_id = '$type'  group by  State,b.parent_code, b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
							}
						}
					}
					else {
						if($agentName == "ALL"){
							if($agentDetail == false) {
							$heading = array("Date","Count","Operator","State");
							$headcount = 4;
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State   FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  e.state_id=$state and b.operator_id = '$type' and date(b.date_time) between '$startDate' and '$endDate' group by  State, b.operator_id, date(b.date_time) order by b.operator_id, date(b.date_time)";
							}
							else {
							$heading = array("Date","Count","Operator","Agent","State");
							$headcount = 5;
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,'[',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State  FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and   e.state_id=$state and   date(d.date_time) between '$startDate' and '$endDate' and d.operator_id = '$type' group by  State,b.parent_code, d.operator_id, b.agent_name, date(d.date_time) order by d.operator_id, b.agent_name, date(d.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
							$heading = array("Date","Count","Operator","State");
							$headcount = 4;
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  b.agent_code = '$agentName' and  e.state_id=$state and d.operator_id  = '$type' and date(d.date_time) between '$startDate' and '$endDate' group by  State, d.operator_id, date(d.date_time) order by d.operator_id,date(d.date_time)";
							}
							else {
							$heading = array("Date","Count","Operator","Agent","State");
							$headcount = 5;
							$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  e.state_id=$state and  c.operator_id = d.operator_id and date(d.date_time) between '$startDate' and '$endDate' and d.operator_id  = '$type' and b.agent_code = '$agentName' group by  State,b.parent_code ,d.operator_id, b.agent_name, date(d.date_time) order by  d.operator_id,b.agent_name, date(d.date_time)";
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
								$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and date(b.date_time) between '$startDate' and '$endDate'   group by  State,local, date(b.date_time) order by date(b.date_time)";
							}
							else {
							$heading = array("Date","Count","Agent","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local  FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  date(c.date_time) between '$startDate' and '$endDate'   group by  State,local,b.parent_code, b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
							$heading = array("Date","Count","State","Local Government");
							$headcount = 4;
								$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and   b.agent_code = '$agentName' and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  date(c.date_time) between '$startDate' and '$endDate'   group by  State,local, date(c.date_time) order by date(c.date_time)";
							}
							else {
							$heading = array("Date","Count","Agent","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and  b.agent_code = '$agentName'  and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  date(c.date_time) between '$startDate' and '$endDate'   group by  State,local, b.parent_code, b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
							}
						}
					}
					else {
						if($agentName == "ALL"){
							if($agentDetail == false) {
							$heading = array("Date","Count","Operator","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local  FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and b.operator_id = c.operator_id and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  date(b.date_time) between '$startDate' and '$endDate'   group by  State,local, b.operator_id, date(b.date_time) order by b.operator_id, date(b.date_time)";
							}
							else {
							$heading = array("Date","Count","Operator","Agent","State","Local Government");
							$headcount = 6;
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  date(d.date_time) between '$startDate' and '$endDate'     group by  State,local,b.parent_code, d.operator_id, agent, date(d.date_time) order by d.operator_id, agent, date(d.date_time)";
							}
						}
						else {
						$heading = array("Date","Count","Operator","State","Local Government");
							$headcount = 5;
							if($agentDetail == false) {
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and b.agent_code = '$agentName'  and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  date(d.date_time) between '$startDate' and '$endDate'   group by  State,local, d.operator_id, date(d.date_time) order by d.operator_id,date(d.date_time)";
							}
							else {
							$heading = array("Date","Count","Operator","Agent","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local  FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  date(d.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName'    group by  State,local,b.parent_code, d.operator_id, b.agent_name, date(d.date_time) order by d.operator_id,b.agent_name, date(d.date_time)";
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
								$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and b.operator_id = '$type'  and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  date(b.date_time) between '$startDate' and '$endDate'  group by  State,local, date(b.date_time) order by date(b.date_time)";
							}
							else {
							$heading = array("Date","Count","Agent","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM  agent_info b, evd_transaction c,state_list dWHERE b.user_id = c.user_id and b.state_id = d.state_id and b.state_id = '$state' and b.local_govt_id = '$local_govt_id'and  date(c.date_time) between '$startDate' and '$endDate' and c.operator_id = '$type'   group by  State,local,b.parent_code, b.agent_name, date(c.date_time) order by  b.agent_name, date(c.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
							$heading = array("Date","Count","State","Local Government");
							$headcount = 4;
								$query ="SELECT date(c.date_time) as Date, count(*) as Count,concat(d.name) as State ,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM  agent_info b, evd_transaction c, state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and b.agent_code = '$agentName' and c.operator_id = '$type' and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and date(c.date_time) between '$startDate' and '$endDate'  group by  State,local,b.parent_code, date(c.date_time) order by date(c.date_time)";
							}
							else {
							$heading = array("Date","Count","Agent","State","Local Government");
							$headcount = 4;
								$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(d.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM  agent_info b, evd_transaction c,state_list d WHERE b.user_id = c.user_id and b.state_id = d.state_id and b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and date(c.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and c.operator_id = '$type'    group by  State,local,b.parent_code, b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
							}
						}
					}
					else {
						if($agentName == "ALL"){
							if($agentDetail == false) {
							$heading = array("Date","Count","Operator","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local   FROM  agent_info b, operator c, evd_transaction d,state_list e  WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and b.operator_id = '$type' and date(b.date_time) between '$startDate' and '$endDate'   group by  State,local, b.operator_id, date(b.date_time) order by b.operator_id, date(b.date_time)";
							}
							else {
							$heading = array("Date","Count","Operator","Agent","State","Local Government");
							$headcount = 6;
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,'[',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local  FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and   b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and   date(d.date_time) between '$startDate' and '$endDate' and d.operator_id = '$type'   group by  State,local,b.parent_code, d.operator_id, b.agent_name, date(d.date_time) order by d.operator_id, b.agent_name, date(d.date_time)";
							}
						}
						else {
							if($agentDetail == false) {
							$heading = array("Date","Count","Operator","State","Local Government");
							$headcount = 5;
								$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator',concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  b.agent_code = '$agentName' and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and d.operator_id  = '$type' and date(d.date_time) between '$startDate' and '$endDate'   group by  State,local, d.operator_id, date(d.date_time) order by d.operator_id,date(d.date_time)";
							}
							else {
							$heading = array("Date","Count","Operator","Agent","State","Local Government");
							$headcount = 6;
							$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(e.name) as State,(select name from local_govt_list where local_govt_id=b.local_govt_id) as local FROM  agent_info b, operator c, evd_transaction d,state_list e WHERE b.user_id = d.user_id and c.operator_id = d.operator_id and b.state_id = e.state_id and  b.state_id = '$state' and b.local_govt_id = '$local_govt_id' and  c.operator_id = d.operator_id and date(d.date_time) between '$startDate' and '$endDate' and d.operator_id  = '$type' and b.agent_code = '$agentName'   group by  State,local,b.parent_code ,d.operator_id, b.agent_name, date(d.date_time) order by  d.operator_id,b.agent_name, date(d.date_time)";
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
