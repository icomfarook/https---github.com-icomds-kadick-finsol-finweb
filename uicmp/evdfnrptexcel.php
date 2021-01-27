 <?php
 ERROR_REPORTING(E_ALL);
$data = json_decode(file_get_contents("php://input"));
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
include("excelfunctions.php");
require_once   '../common/PHPExcel/Classes/PHPExcel/IOFactory.php';

	$opr	=  $_POST['opr'];
	$type	=  $_POST['type'];
	$agentName	=  $_POST['agentName'];	
	$startDate		=   $_POST['startDate'];
	$endDate	=   $_POST['endDate'];
	$creteria 	=  $_POST['creteria'];
	$agentDetail 	=  $_POST['agentdetail'];
	$oprDetail 	=  $_POST['orderdetail'];
	$ba 	= $_POST['ba'];
	$userName = $_SESSION['user_name'];
	$state 	=  $_POST['state'];
	$championName	=  $_SESSION['party_code'];	
$msg = "";
$servername = SERVER_SHORT_NAME;
$title = "";
$title = "KadickMoni";
 
if($startDate == null ){
	$startDate = date('Y-m-d');
}
if($endDate == null ){
		$endDate   =  date('Y-m-d');
}

$msg = "";$heading = "";$headercount="";
$objPHPExcel = new PHPExcel();
		 if($state == "ALL"){
			if($opr == "ALL") {
				if($oprDetail == false) {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
							$heading = array("Date", "Request Amount","State");
								$headercount = 3;
								$query ="SELECT  date(a.date_time) as Date, SUM(a.request_amount) as request_amount,concat (b.agent_name) as agent ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE a.user_id=b.user_id and b.parent_code = '$championName' and b.state_id=c.state_id and date(a.date_time) between '$startDate' and '$endDate'group by  date(a.date_time),b.agent_name,State order by date(a.date_time)";
							}
							if($ba == 'ta') {
							$heading = array("Date", "Total Amount","State");
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(total_amount) as total_amount ,concat (b.agent_name) as agent ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE a.user_id=b.user_id and b.parent_code = '$championName' and b.state_id=c.state_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time),b.agent_name,State order by date(a.date_time)";
							}
							if($ba == 'bo') {
							$heading = array("Date", "Request Amount", "Total Amount","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(request_amount) as request_amount, SUM(total_amount) as total_amount ,concat (b.agent_name) as agent ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and a.user_id=b.user_id and b.parent_code = '$championName' and  date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time),b.agent_name,State order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
							$heading = array("Date", "Request Amount","Agent Name","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and a.user_id = b.user_id and b.parent_code = '$championName' and date(date_time) between '$startDate' and '$endDate' group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
							$heading = array("Date", "Total Amount","Agent Name","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and a.user_id = b.user_id and b.parent_code = '$championName' and date(date_time) between '$startDate' and '$endDate' group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount","Agent Name","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and a.user_id = b.user_id and b.parent_code = '$championName' and date(date_time) between '$startDate' and '$endDate' group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount","State");
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and b.parent_code = '$championName' and  b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by State, date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount","State");
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and b.parent_code = '$championName' and  b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by State ,date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount", "Total Amount","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and b.parent_code = '$championName'  and  b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by State ,date(a.date_time) order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
							$heading = array("Date", "Request Amount", "Agent ","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount", "Agent ","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by State,b.parent_code,b.agent_code, b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount",  "Total Amount", "Agent ","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
					}
				}
				else {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount", "Operator","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b,agent_info c,state_list d  WHERE c.state_id = d.state_id and   a.user_id = c.user_id and c.parent_code = '$championName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by State,a.opr_plan_desc, a.operator_id, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount", "Operator","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' ,concat(d.name) as State FROM evd_transaction a, operator b,agent_info c,state_list d  WHERE c.state_id = d.state_id and   a.user_id = c.user_id  and b.parent_code = '$championName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by State,a.opr_plan_desc, a.operator_id, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount" ,"Operator","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' ,concat(d.name) as State FROM evd_transaction a, operator b,agent_info c,state_list d  WHERE c.state_id = d.state_id and c.parent_code = '$championName' and   a.user_id = c.user_id and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by State, a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount","Operator" ,"Agent Name","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE  b.state_id = d.state_id and b.parent_code = '$championName' and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc, b.agent_name,b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount","Operator" ,"Agent Name","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE  b.state_id = d.state_id and b.parent_code = '$championName' and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc, b.agent_name,b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'bo') {
							$heading = array("Date", "Request Amount","Total Amount", "Operator" ,"Agent Name","State");
								$headercount = 6;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent ,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = c.operator_id and b.parent_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State, a.operator_id, b.agent_name, b.agent_code,a.opr_plan_desc, date(a.date_time),b.parent_code order by  a.operator_id, b.agent_name,b.agent_code, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount","Operator" ,"State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b, agent_info c,state_list d  WHERE c.state_id = d.state_id and  a.user_id = c.user_id and c.parent_code = '$championName' and c.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by State, a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount","Operator" ,"State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' ,concat(d.name) as State FROM evd_transaction a, operator b, agent_info c,state_list d  WHERE c.state_id = d.state_id and a.user_id = c.user_id and c.parent_code = '$championName' and c.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by State ,a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount", "Operator" ,"State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b, agent_info c,state_list d  WHERE c.state_id = d.state_id and a.user_id = c.user_id and c.agent_code = '$agentName' and c.parent_code = '$championName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by State ,a.opr_plan_desc,a.operator_id, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount","Operator" ,"Agent Name","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and b.parent_code = '$championName' and b.agent_code = '$agentName'  and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,b.parent_code,a.opr_plan_desc, a.operator_id, b.agent_name, b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount","Operator" ,"Agent Name","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and b.parent_code = '$championName' and b.agent_code = '$agentName'  and  a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,a.opr_plan_desc,b.parent_code, a.operator_id, b.agent_name, b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount", "Operator" ,"Agent Name","State");
								$headercount = 6;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and b.agent_code = '$agentName' and b.parent_code = '$championName'  and  a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,a.opr_plan_desc,b.parent_code, a.operator_id, b.agent_name, b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
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
							$heading = array("Date", "Request Amount","State");
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount,concat (b.agent_name) as agent ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE a.user_id=b.user_id and b.parent_code = '$championName' and b.state_id=c.state_id and  operator_id = $opr and date(date_time) between '$startDate' and '$endDate' group by State,b.agent_name, date(a.date_time) order by date(date_time)";
							}
							if($ba == 'ta') {
							$heading = array("Date", "Total Amount","State");
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount,concat (b.agent_name) as agent ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE a.user_id=b.user_id and b.parent_code = '$championName' and b.state_id=c.state_id and operator_id = $opr and date(date_time) between '$startDate' and '$endDate' group by State,b.agent_name, date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
							$heading = array("Date", "Request Amount", "Total Amount","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,concat (b.agent_name) as agent ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE a.user_id=b.user_id and b.parent_code = '$championName' and b.state_id=c.state_id and operator_id = $opr and date(date_time) between '$startDate' and '$endDate' group by State,b.agent_name, date(a.date_time) order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
							$heading = array("Date", "Request Amount","Agent Name","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id=c.state_id and b.parent_code = '$championName' and  a.operator_id = $opr and a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' group by State,b.parent_code, b.agent_name, b.agent_code,date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
							$heading = array("Date", "Total Amount","Agent Name","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id=c.state_id and a.operator_id = $opr and b.parent_code = '$championName' and a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount","Agent Name","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id=c.state_id and a.operator_id = $opr and b.parent_code = '$championName' and a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' group by State,b.parent_code, b.agent_name, b.agent_code, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount","State");
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id = c.state_id and  a.operator_id = $opr and b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by State, date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount","State");
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount ,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id = c.state_id and a.operator_id = $opr and b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by State, date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount", "Total Amount","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount ,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id = c.state_id and a.operator_id = $opr and b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by State, date(a.date_time) order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount", "Agent ","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id = c.state_id and a.operator_id = $opr and b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount", "Agent ","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent ,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id = c.state_id and a.operator_id = $opr and b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount",  "Total Amount", "Agent ","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id = c.state_id and a.operator_id = $opr and b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by State,b.parent_code,b.agent_code, b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
					}
				}
				else {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
							$heading = array("Date", "Request Amount", "Operator","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b,agent_info c, state_list d  WHERE c.state_id = d.state_id and  a.user_id = c.user_id and  a.operator_id = $opr and c.parent_code = '$championName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by State ,a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount", "Operator","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b,agent_info c, state_list d  WHERE c.state_id = d.state_id and  a.user_id = c.user_id and  a.operator_id = $opr and c.parent_code = '$championName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by State ,a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount" ,"Operator","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b,agent_info c, state_list d  WHERE c.state_id = d.state_id and  a.user_id = c.user_id and  a.operator_id = $opr and c.parent_code = '$championName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by State,a.opr_plan_desc, a.operator_id, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount","Operator" ,"Agent Name","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and b.parent_code = '$championName' and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,b.parent_code, a.operator_id, b.agent_name, b.agent_code,a.opr_plan_desc, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount","Operator" ,"Agent Name","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and b.parent_code = '$championName' and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc, b.agent_name,b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount", "Operator" ,"Agent Name","State");
								$headercount = 6;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent ,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and b.parent_code = '$championName' and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc, b.agent_name,b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount","Operator" ,"State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b,agent_info c,state_list d  WHERE a.user_id = c.user_id and c.state_id = d.state_id and  a.operator_id = $opr and c.parent_code = '$championName' and b.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by State, a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount","Operator" ,"State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b,agent_info c,state_list d  WHERE a.user_id = c.user_id and c.state_id = d.state_id and  a.operator_id = $opr and c.parent_code = '$championName' and b.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by State, a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount", "Operator" ,"State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' ,concat(d.name) as State FROM evd_transaction a, operator b,agent_info c,state_list d  WHERE a.user_id = c.user_id and c.state_id = d.state_id and  a.operator_id = $opr and c.parent_code = '$championName' and b.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by State,a.opr_plan_desc, a.operator_id, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount","Operator" ,"Agent Name","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and b.agent_code = '$agentName' and b.parent_code = '$championName'  and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,b.parent_code, a.operator_id, b.agent_name, b.agent_code,a.opr_plan_desc, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount","Operator" ,"Agent Name","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and b.parent_code = '$championName' and b.agent_code = '$agentName'  and  a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,b.parent_code,a.opr_plan_desc, a.operator_id, b.agent_name, b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount", "Operator" ,"Agent Name","State");
								$headercount = 6;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and b.parent_code = '$championName' and b.agent_code = '$agentName'  and  a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by State,b.parent_code,a.opr_plan_desc, a.operator_id, b.agent_name, b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
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
							$heading = array("Date", "Request Amount","State");
								$headercount = 3;
								$query ="SELECT  date(a.date_time) as Date, (a.request_amount) as request_amount,concat (b.agent_name) as agent ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE a.user_id=b.user_id and b.parent_code = '$championName' and b.state_id=c.state_id and c.state_id = $state and date(a.date_time) between '$startDate' and '$endDate' ";
							}
							if($ba == 'ta') {
							$heading = array("Date", "Total Amount","State");
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(total_amount) as total_amount ,concat (b.agent_name) as agent ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE a.user_id=b.user_id and b.parent_code = '$championName' and b.state_id=c.state_id and c.state_id = $state and date(a.date_time) between '$startDate' and '$endDate' ";
							}
							if($ba == 'bo') {
							$heading = array("Date", "Request Amount", "Total Amount","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(request_amount) as request_amount, SUM(total_amount) as total_amount ,concat (b.agent_name) as agent ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and c.state_id = $state and a.user_id=b.user_id and b.parent_code = '$championName' and  date(a.date_time) between '$startDate' and '$endDate'";
							}
						}
						else {
							if($ba == 'ra') {
							$heading = array("Date", "Request Amount","Agent Name","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and a.user_id = b.user_id and b.parent_code = '$championName' and date(date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,b.parent_code, b.agent_name, b.agent_code,date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
							$heading = array("Date", "Total Amount","Agent Name","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and a.user_id = b.user_id and b.parent_code = '$championName' and date(date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount","Agent Name","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and a.user_id = b.user_id and b.parent_code = '$championName' and date(date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount","State");
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id  and  b.agent_code = '$agentName' and b.parent_code = '$championName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id = $state  group by State ,date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount","State");
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id and  b.agent_code = '$agentName' and b.parent_code = '$championName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id = $state  group by State, date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount", "Total Amount","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id  and  b.agent_code = '$agentName' and a.user_id = b.user_id and b.parent_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate'  and c.state_id = $state group by State, date(a.date_time) order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
							$heading = array("Date", "Request Amount", "Agent ","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id  and b.agent_code = '$agentName' and b.parent_code = '$championName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
							$heading = array("Date", "Total Amount", "Agent ","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id  and b.agent_code = '$agentName' and b.parent_code = '$championName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount",  "Total Amount", "Agent ","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE b.state_id=c.state_id  and b.agent_code = '$agentName' and b.parent_code = '$championName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
					}
				}
				else {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount", "Operator","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b,agent_info c,state_list d  WHERE c.state_id = d.state_id and   a.user_id = c.user_id and c.parent_code = '$championName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' and d.state_id = $state group by State ,a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount", "Operator","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' ,concat(d.name) as State FROM evd_transaction a, operator b,agent_info c,state_list d  WHERE c.state_id = d.state_id and   a.user_id = c.user_id and c.parent_code = '$championName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' and d.state_id = $state group by State ,a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount" ,"Operator","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' ,concat(d.name) as State FROM evd_transaction a, operator b,agent_info c,state_list d  WHERE c.state_id = d.state_id and c.parent_code = '$championName' and   a.user_id = c.user_id and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' and d.state_id = $state group by State, a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount","Operator" ,"Agent Name","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE  b.state_id = d.state_id and b.parent_code = '$championName' and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id, b.agent_name, b.agent_code,a.opr_plan_desc, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'ta') {
							$heading = array("Date", "Total Amount","Operator" ,"Agent Name","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE  b.state_id = d.state_id and b.parent_code = '$championName' and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state  and a.user_id = b.user_id group by State,b.parent_code,a.opr_plan_desc, a.operator_id, b.agent_name, b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'bo') {
							$heading = array("Date", "Request Amount","Total Amount", "Operator" ,"Agent Name","State");
								$headercount = 6;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent ,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE  b.state_id = d.state_id and a.operator_id = c.operator_id and b.parent_code = '$championName' and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc,b.agent_code, b.agent_name, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
							$heading = array("Date", "Request Amount","Operator" ,"State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b, agent_info c,state_list d  WHERE c.state_id = d.state_id and  a.user_id = c.user_id and c.agent_code = '$agentName' and c.parent_code = '$championName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' and d.state_id = $state group by State, a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount","Operator" ,"State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' ,concat(d.name) as State FROM evd_transaction a, operator b, agent_info c,state_list d  WHERE c.state_id = d.state_id and a.user_id = c.user_id and c.agent_code = '$agentName' and c.parent_code = '$championName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' and d.state_id = $state group by State, a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount", "Operator" ,"State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b, agent_info c,state_list d  WHERE c.state_id = d.state_id and a.user_id = c.user_id and c.agent_code = '$agentName' and c.parent_code = '$championName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' and d.state_id = $state group by State, a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount","Operator" ,"Agent Name","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and b.parent_code = '$championName' and b.agent_code = '$agentName'  and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc, b.agent_name,b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'ta') {
							$heading = array("Date", "Total Amount","Operator" ,"Agent Name","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and b.parent_code = '$championName' and b.agent_code = '$agentName'  and  a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,,a.opr_plan_desc b.agent_name,b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'bo') {
							$heading = array("Date", "Request Amount","Total Amount", "Operator" ,"Agent Name","State");
								$headercount = 6;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and b.agent_code = '$agentName' and b.parent_code = '$championName'  and  a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc,b.agent_code, b.agent_name, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
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
							$heading = array("Date", "Request Amount","State");
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount,concat (b.agent_name) as agent ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE a.user_id=b.user_id and b.state_id=c.state_id and  operator_id = $opr and b.parent_code = '$championName'  and c.state_id = $state and date(a.date_time) between '$startDate' and '$endDate'  and c.state_id = $state group by State,agent, date(date_time) order by date(date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount","State");
								$headercount = 3;
								$query ="SELECT date(date_time) as Date, SUM(total_amount) as total_amount,concat (b.agent_name) as agent ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE a.user_id=b.user_id and b.state_id=c.state_id and operator_id = $opr and b.parent_code = '$championName' and date(date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State, agent ,date(date_time) order by date(date_time)";
							}
							if($ba == 'bo') {
							$heading = array("Date", "Request Amount", "Total Amount","State");
								$headercount = 4;
								$query ="SELECT date(date_time) as Date, SUM(request_amount) as request_amount, SUM(total_amount) as total_amount,concat (b.agent_name) as agent ,concat(c.name) as State FROM evd_transaction a,agent_info b,state_list c WHERE a.user_id=b.user_id and b.state_id=c.state_id and operator_id = $opr and b.parent_code = '$championName' and date(date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,agent, date(date_time) order by date(date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount","Agent Name","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id=c.state_id and  a.operator_id = $opr and b.parent_code = '$championName' and a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,b.parent_code,b.agent_code, b.agent_name, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount","Agent Name","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id=c.state_id and a.operator_id = $opr and b.parent_code = '$championName' and a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount","Agent Name","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id=c.state_id and a.operator_id = $opr and b.parent_code = '$championName' and a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,b.parent_code, b.agent_name,b.agent_code, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount","State");
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id = c.state_id and  a.operator_id = $opr and b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id = $state  group by State, date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount","State");
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount ,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id = c.state_id and a.operator_id = $opr and b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id = $state  group by State, date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount", "Total Amount","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount ,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id = c.state_id and a.operator_id = $opr and b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State ,date(a.date_time) order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount", "Agent ","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id = c.state_id and a.operator_id = $opr and b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,b.parent_code,b.agent_code, b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount", "Agent ","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent ,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id = c.state_id and a.operator_id = $opr and b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,b.parent_code,b.agent_code, b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount",  "Total Amount", "Agent ","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(c.name) as State FROM evd_transaction a, agent_info b,state_list c WHERE b.state_id = c.state_id and a.operator_id = $opr and b.parent_code = '$championName' and b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and c.state_id = $state group by State,b.parent_code,b.agent_code, b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
					}
				}
				else {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
							$heading = array("Date", "Request Amount", "Operator","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b,agent_info c, state_list d  WHERE c.state_id = d.state_id and  a.user_id = c.user_id and  a.operator_id = $opr and c.parent_code = '$championName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' and d.state_id = $state group by State, a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount", "Operator","State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b,agent_info c, state_list d  WHERE c.state_id = d.state_id and  a.user_id = c.user_id and  a.operator_id = $opr and c.parent_code = '$championName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' and d.state_id = $state group by State, a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount" ,"Operator","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b,agent_info c, state_list d  WHERE c.state_id = d.state_id and  a.user_id = c.user_id and  a.operator_id = $opr and c.parent_code = '$championName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' and d.state_id = $state group by State, a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount","Operator" ,"Agent Name","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and b.parent_code = '$championName' and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc, b.agent_name,b.agent_code, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount","Operator" ,"Agent Name","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and b.parent_code = '$championName' and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id, b.agent_name, b.agent_code,a.opr_plan_desc, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount", "Operator" ,"Agent Name","State");
								$headercount = 6;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent ,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and b.parent_code = '$championName' and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id, b.agent_name, b.agent_code, a.opr_plan_desc,date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount","Operator" ,"State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b,agent_info c,state_list d  WHERE a.user_id = c.user_id and c.state_id = d.state_id and  a.operator_id = $opr and c.parent_code = '$championName' and c.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' and d.state_id = $state group by State ,a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount","Operator" ,"State");
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator',concat(d.name) as State FROM evd_transaction a, operator b,agent_info c,state_list d  WHERE a.user_id = c.user_id and c.state_id = d.state_id and  a.operator_id = $opr and c.parent_code = '$championName' and c.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' and d.state_id = $state group by State, a.operator_id,a.opr_plan_desc, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount", "Operator" ,"State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' ,concat(d.name) as State FROM evd_transaction a, operator b,agent_info c,state_list d  WHERE a.user_id = c.user_id and c.state_id = d.state_id and  a.operator_id = $opr and c.parent_code = '$championName' and c.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' and d.state_id = $state group by State,a.opr_plan_desc, a.operator_id, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount","Operator" ,"Agent Name","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and b.parent_code = '$championName' and b.agent_code = '$agentName'  and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc,b.agent_code, b.agent_name, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount","Operator" ,"Agent Name","State");
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and b.parent_code = '$championName'  and b.agent_code = '$agentName'  and  a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id,a.opr_plan_desc,b.agent_code, b.agent_name, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount", "Operator" ,"Agent Name","State");
								$headercount = 6;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent,concat(d.name) as State FROM evd_transaction a, agent_info b, operator c,state_list d WHERE b.state_id = d.state_id and a.operator_id = $opr and b.parent_code = '$championName' and b.agent_code = '$agentName'  and  a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and d.state_id = $state and a.user_id = b.user_id group by State,b.parent_code, a.operator_id, b.agent_name, b.agent_code,a.opr_plan_desc, date(a.date_time) order by  a.operator_id, b.agent_name, b.agent_code, date(a.date_time)";
							}
						}
					}
				}
			}
		 }
		error_log($query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$msg = "EVD Finance Report For Date between $startDate and $endDate";

		////error_log($query);
		heading($heading,$objPHPExcel,$headercount);
		$i = 2;						
		while ($row = mysqli_fetch_array($result))	{
			generateExcel ($i, $row,$objPHPExcel,$headercount);
			$i++;				
		}
			$row = $objPHPExcel->getActiveSheet()->getHighestRow();
		$objPHPExcel->getActiveSheet()->getStyle( 'A'.($row+1) )->getFont()->setBold( true );
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.($row+1), "Row Count: ".($row -1));
	  ////error_log($query);
		
	
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
