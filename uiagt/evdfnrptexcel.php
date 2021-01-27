<?php
ERROR_REPORTING(E_ALL);
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
include("excelfunctions.php");
require_once   '../common/PHPExcel/Classes/PHPExcel/IOFactory.php';

	$data = json_decode(file_get_contents("php://input"));
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
$msg = "";
$servername = SERVER_SHORT_NAME;
$title = "KadickMoni";

if($startDate == null ){
	$startDate = date('Y-m-d');
}
if($endDate == null ){
		$endDate   =  date('Y-m-d');
}

$msg = "";$heading = "";$headercount="";
$objPHPExcel = new PHPExcel();
			if($opr == "ALL") {
				if($oprDetail == false) {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount");
								$msg = "EVD Finance Report For Non Detailed Operator & Agent Request Amount date between $startDate to $endDate";
								$headercount = 2;
								$query ="SELECT date(date_time) as Date, SUM(request_amount) as request_amount FROM evd_transaction WHERE date(date_time) between '$startDate' and '$endDate' group by date(date_time) order by date(date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount");
								$msg = "EVD Finance Report For Non Detailed Operator & Agent Total Amount date between $startDate to $endDate";
								$headercount = 2;
								$query ="SELECT date(date_time) as Date, SUM(total_amount) as total_amount FROM evd_transaction WHERE date(date_time) between '$startDate' and '$endDate' group by date(date_time) order by date(date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount", "Total Amount");
								$msg = "EVD Finance Report For Non Detailed Operator & Agent Request & Total Amount date between $startDate to $endDate";
								$headercount = 3;
								$query ="SELECT date(date_time) as Date, SUM(request_amount) as request_amount, SUM(total_amount) as total_amount FROM evd_transaction WHERE date(date_time) between '$startDate' and '$endDate' group by date(date_time) order by date(date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount","Agent Name");
								$msg = "EVD Finance Report For  Detailed Operator & Detailed Agent Request Amount date between $startDate to $endDate";
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent FROM evd_transaction a, agent_info b WHERE a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount","Agent Name");
								$msg = "EVD Finance Report For  Detailed Operator & Detailed Agent Total Amount date between $startDate to $endDate";
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent FROM evd_transaction a, agent_info b WHERE a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount","Agent Name");
								$msg = "EVD Finance Report For  Detailed Operator & Detailed Agent Request & Total Amount date between $startDate to $endDate";
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent FROM evd_transaction a, agent_info b WHERE a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
						}
					}
					else {error_log(1);
						if($agentDetail == false) {error_log(2);
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount");
								$msg = "EVD Finance Report For non Detailed Operator & Agent  Requet Amount date between $startDate to $endDate";
								$headercount = 2;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount FROM evd_transaction a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount");
								$msg = "EVD Finance Report For non Detailed Operator & Agent  Total Amount date between $startDate to $endDate";
								$headercount = 2;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount FROM evd_transaction a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount", "Total Amount");
								$msg = "EVD Finance Report For non Detailed Operator & Agent  Request & Total Amount date between $startDate to $endDate";
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM evd_transaction a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
							}
						}
						else {error_log(3);
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount", "Agent ");
								$msg = "EVD Finance Report For non Detailed Operator & Detailed Agent  Request Amount date between $startDate to $endDate";
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent FROM evd_transaction a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount", "Agent ");
								$msg = "EVD Finance Report For non Detailed Operator & Detailed Agent  Total Amount date between $startDate to $endDate";
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent FROM evd_transaction a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount",  "Total Amount", "Agent ");
								$msg = "EVD Finance Report For non Detailed Operator & Detailed Agent  Request & Total Amount date between $startDate to $endDate";
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent FROM evd_transaction a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
					}
				}
				else { 
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount", "Operator");
								$msg = "EVD Finance Report For Detailed Operator & Non Detailed Agent  Request  date between $startDate to $endDate";
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' FROM evd_transaction a, operator b  WHERE a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by a.operator_id, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount", "Operator");
								$msg = "EVD Finance Report For Detailed Operator & Non Detailed Agent   Total Amount date between $startDate to $endDate";
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' FROM evd_transaction a, operator b  WHERE a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by a.operator_id, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount" ,"Operator");
								$msg = "EVD Finance Report For Detailed Operator & Non Detailed Agent  Request & Total Amount date between $startDate to $endDate";
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' FROM evd_transaction a, operator b  WHERE a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by a.operator_id, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount","Operator" ,"Agent");
								$msg = "EVD Finance Report For Detailed Operator &  Detailed Agent  Request  Amount date between $startDate to $endDate";
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent FROM evd_transaction a, agent_info b, operator c WHERE a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by a.operator_id, b.agent_name, date(a.date_time) order by  a.operator_id, b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Request Amount","Operator" ,"Agent");
								$msg = "EVD Finance Report For Detailed Operator &  Detailed Agent Total Amount date between $startDate to $endDate";
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent FROM evd_transaction a, agent_info b, operator c WHERE a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by a.operator_id, b.agent_name, date(a.date_time) order by  a.operator_id, b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount", "Operator" ,"Agent");
								$msg = "EVD Finance Report For Detailed Operator &  Detailed Agent Request & Total Amount date between $startDate to $endDate";
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent FROM evd_transaction a, agent_info b, operator c WHERE a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by a.operator_id, b.agent_name, date(a.date_time) order by  a.operator_id, b.agent_name, date(a.date_time)";
							}
						}
					}
					else { 
						if($agentDetail == false) {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount","Operator" );
								$msg = "EVD Finance Report For Detailed Operator & Non Detailed Agent  Request  Amount date between $startDate to $endDate";
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' FROM evd_transaction a, operator b, agent_info c  WHERE a.user_id = c.user_id and c.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by a.operator_id, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount","Operator" );
								$msg = "EVD Finance Report For Detailed Operator & Non Detailed Agent  Total  Amount date between $startDate to $endDate";
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' FROM evd_transaction a, operator b, agent_info c  WHERE a.user_id = c.user_id and c.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by a.operator_id, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount", "Operator" );
								$msg = "EVD Finance Report For Detailed Operator & Non Detailed Agent  Request  Amount date between $startDate to $endDate";
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' FROM evd_transaction a, operator b, agent_info c  WHERE a.user_id = c.user_id and c.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by a.operator_id, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount","Operator" ,"Agent");
								$msg = "EVD Finance Report For Detailed Operator &  Detailed Agent  Request  Amount date between $startDate to $endDate";
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent FROM evd_transaction a, agent_info b, operator c WHERE b.agent_code = '$agentName'  and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by a.operator_id, b.agent_name, date(a.date_time) order by  a.operator_id, b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Request Amount","Operator" ,"Agent");
								$msg = "EVD Finance Report For Detailed Operator &  Detailed Agent Total Amount date between $startDate to $endDate";
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent FROM evd_transaction a, agent_info b, operator c WHERE b.agent_code = '$agentName'  and  a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by a.operator_id, b.agent_name, date(a.date_time) order by  a.operator_id, b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount", "Operator" ,"Agent");
								$msg = "EVD Finance Report For Detailed Operator &  Agent Request & Total Amount date between $startDate to $endDate";
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent FROM evd_transaction a, agent_info b, operator c WHERE b.agent_code = '$agentName'  and  a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by a.operator_id, b.agent_name, date(a.date_time) order by  a.operator_id, b.agent_name, date(a.date_time)";
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
								$heading = array("Date", "Request Amount");
								$msg = "EVD Finance Report For Non Detailed Operator & Agent Request Amount date between $startDate to $endDate";
								$headercount = 2;
								$query ="SELECT date(date_time) as Date, SUM(request_amount) as request_amount FROM evd_transaction WHERE date(date_time) between '$startDate' and '$endDate' group by date(date_time) order by date(date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount");
								$msg = "EVD Finance Report For Non Detailed Operator & Agent Total Amount date between $startDate to $endDate";
								$headercount = 2;
								$query ="SELECT date(date_time) as Date, SUM(total_amount) as total_amount FROM evd_transaction WHERE date(date_time) between '$startDate' and '$endDate' group by date(date_time) order by date(date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount", "Total Amount");
								$msg = "EVD Finance Report For Non Detailed Operator & Agent Request & Total Amount date between $startDate to $endDate";
								$headercount = 3;
								$query ="SELECT date(date_time) as Date, SUM(request_amount) as request_amount, SUM(total_amount) as total_amount FROM evd_transaction WHERE date(date_time) between '$startDate' and '$endDate' group by date(date_time) order by date(date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount","Agent Name");
								$msg = "EVD Finance Report For  Detailed Operator & Detailed Agent Request Amount date between $startDate to $endDate";
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent FROM evd_transaction a, agent_info b WHERE a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount","Agent Name");
								$msg = "EVD Finance Report For  Detailed Operator & Detailed Agent Total Amount date between $startDate to $endDate";
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent FROM evd_transaction a, agent_info b WHERE a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount","Agent Name");
								$msg = "EVD Finance Report For  Detailed Operator & Detailed Agent Request & Total Amount date between $startDate to $endDate";
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent FROM evd_transaction a, agent_info b WHERE a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount");
								$msg = "EVD Finance Report For non Detailed Operator & Agent  Requet Amount date between $startDate to $endDate";
								$headercount = 2;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount FROM evd_transaction a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount");
								$msg = "EVD Finance Report For non Detailed Operator & Agent  Total Amount date between $startDate to $endDate";
								$headercount = 2;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount FROM evd_transaction a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount", "Total Amount");
								$msg = "EVD Finance Report For non Detailed Operator & Agent  Request & Total Amount date between $startDate to $endDate";
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM evd_transaction a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount", "Agent ");
								$msg = "EVD Finance Report For non Detailed Operator & Detailed Agent  Request Amount date between $startDate to $endDate";
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent FROM evd_transaction a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount", "Agent ");
								$msg = "EVD Finance Report For non Detailed Operator & Detailed Agent  Total Amount date between $startDate to $endDate";
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent FROM evd_transaction a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount",  "Total Amount", "Agent ");
								$msg = "EVD Finance Report For non Detailed Operator & Detailed Agent  Request & Total Amount date between $startDate to $endDate";
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent FROM evd_transaction a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
					}
				}
				else {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount", "Operator");
								$msg = "EVD Finance Report For Detailed Operator & Non Detailed Agent  Request  date between $startDate to $endDate";
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' FROM evd_transaction a, operator b  WHERE a.operator_id = $opr and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by a.operator_id, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount", "Operator");
								$msg = "EVD Finance Report For Detailed Operator & Non Detailed Agent   Total Amount date between $startDate to $endDate";
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' FROM evd_transaction a, operator b  WHERE a.operator_id = $opr and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by a.operator_id, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount" ,"Operator");
								$msg = "EVD Finance Report For Detailed Operator & Non Detailed Agent  Request & Total Amount date between $startDate to $endDate";
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' FROM evd_transaction a, operator b  WHERE a.operator_id = $opr and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by a.operator_id, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount","Operator" ,"Agent");
								$msg = "EVD Finance Report For Detailed Operator &  Detailed Agent  Request  Amount date between $startDate to $endDate";
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent FROM evd_transaction a, agent_info b, operator c WHERE a.operator_id = $opr and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by a.operator_id, b.agent_name, date(a.date_time) order by  a.operator_id, b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Request Amount","Operator" ,"Agent");
								$msg = "EVD Finance Report For Detailed Operator &  Detailed Agent Total Amount date between $startDate to $endDate";
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent FROM evd_transaction a, agent_info b, operator c WHERE a.operator_id = $opr and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by a.operator_id, b.agent_name, date(a.date_time) order by  a.operator_id, b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount", "Operator" ,"Agent");
								$msg = "EVD Finance Report For Detailed Operator &  Detailed Agent Request & Total Amount date between $startDate to $endDate";
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent FROM evd_transaction a, agent_info b, operator c WHERE a.operator_id = $opr and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by a.operator_id, b.agent_name, date(a.date_time) order by  a.operator_id, b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount","Operator" );
								$msg = "EVD Finance Report For Detailed Operator & Non Detailed Agent  Request  Amount date between $startDate to $endDate";
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' FROM evd_transaction a, operator b  WHERE a.operator_id = $opr and  b.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by a.operator_id, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Total Amount","Operator" );
								$msg = "EVD Finance Report For Detailed Operator & Non Detailed Agent  Total  Amount date between $startDate to $endDate";
								$headercount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' FROM evd_transaction a, operator b  WHERE a.operator_id = $opr and b.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by a.operator_id, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount", "Operator" );
								$msg = "EVD Finance Report For Detailed Operator & Non Detailed Agent  Request  Amount date between $startDate to $endDate";
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount,CONCAT(b.operator_code, ' - ', a.opr_plan_desc) as 'Operator' FROM evd_transaction a, operator b  WHERE a.operator_id = $opr and b.agent_code = '$agentName' and a.operator_id = b.operator_id and date(date_time) between '$startDate' and '$endDate' group by a.operator_id, date(a.date_time) order by a.operator_id,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date", "Request Amount","Operator" ,"Agent");
								$msg = "EVD Finance Report For Detailed Operator &  Detailed Agent  Request  Amount date between $startDate to $endDate";
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent FROM evd_transaction a, agent_info b, operator c WHERE a.operator_id = $opr and b.agent_code = '$agentName'  and a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by a.operator_id, b.agent_name, date(a.date_time) order by  a.operator_id, b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date", "Request Amount","Operator" ,"Agent");
								$msg = "EVD Finance Report For Detailed Operator &  Detailed Agent Total Amount date between $startDate to $endDate";
								$headercount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent FROM evd_transaction a, agent_info b, operator c WHERE a.operator_id = $opr and b.agent_code = '$agentName'  and  a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by a.operator_id, b.agent_name, date(a.date_time) order by  a.operator_id, b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date", "Request Amount","Total Amount", "Operator" ,"Agent");
								$msg = "EVD Finance Report For Detailed Operator &  Agent Request & Total Amount date between $startDate to $endDate";
								$headercount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, CONCAT(c.operator_code, ' - ', a.opr_plan_desc) as 'Operator', concat(b.agent_name,'-',b.agent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']')  as agent FROM evd_transaction a, agent_info b, operator c WHERE a.operator_id = $opr and b.agent_code = '$agentName'  and  a.operator_id = c.operator_id and date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by a.operator_id, b.agent_name, date(a.date_time) order by  a.operator_id, b.agent_name, date(a.date_time)";
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
		heading($heading,$objPHPExcel,$headercount);
		$i = 2;						
		while ($row = mysqli_fetch_array($result))	{
			generateExcel ($i, $row,$objPHPExcel,$headercount);
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
