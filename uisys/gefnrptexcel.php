 <?php
 ERROR_REPORTING(E_ALL);
$data = json_decode(file_get_contents("php://input"));
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
//error_log("s");
include("excelfunctions.php");
//error_log("1");
require_once   '../common/PHPExcel/Classes/PHPExcel/IOFactory.php';
//error_log("1");
	$type	=  $_POST['type'];
	$agentName	=  $_POST['agentName'];	
	$startDate		=  $_POST['startDate'];
	$endDate	=  $_POST['endDate'];
	$creteria 	= $_POST['creteria'];
	$agentDetail 	= $_POST['agentdetail'];
	$typeDetail 	= $_POST['typeDetail'];
	$ba 	= $_POST['ba'];
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
$title = "KadickMoni";  
if($startDate == null ){
	$startDate = date('Y-m-d');
}
if($endDate == null ){
		$endDate   =  date('Y-m-d');
}
//error_log($ba);
//error_log($endDate);
$msg = "Finance Report For Date between $startDate and $endDate";
$objPHPExcel = new PHPExcel();
			if($type == "ALL") {
				if($typeDetail == false) {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$heading = array("Date","Request Amount");
								$headcount = 2;
								$query ="SELECT date(date_time) as Date, SUM(request_amount) as request_amount FROM fin_service_order WHERE date(date_time) between '$startDate' and '$endDate' group by date(date_time) order by date(date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date","Total Amount");
								$headcount = 2;
								$query ="SELECT date(date_time) as Date, SUM(total_amount) as total_amount FROM fin_service_order WHERE date(date_time) between '$startDate' and '$endDate' group by date(date_time) order by date(date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date","Request Amount","Total Amount");
								$headcount = 3;
								$query ="SELECT date(date_time) as Date, SUM(request_amount) as request_amount, SUM(total_amount) as total_amount FROM fin_service_order WHERE date(date_time) between '$startDate' and '$endDate' group by date(date_time) order by date(date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date","Request Amount","Agent");
								$headcount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date","Total Amount","Agent");
								$headcount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date","Request Amount","Total Amount","Agent");
								$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE a.user_id = b.user_id and date(date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$heading = array("Date","Request Amount");
								$headcount = 2;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date","Total Amount");
								$headcount = 2;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate'  group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date","Request Amount", "Total Amount");
								$headcount = 3;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by date(a.date_time) order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date","Request Amount", "Agent");
								$headcount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date","Total Amount", "Agent");
								$headcount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date","Request Amount","Total Amount", "Agent");
								$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
					}
				}
				else {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$heading = array("Date","Request Amount","Order Type");
								$headcount = 3;
								$query ="SELECT date(date_time) as Date, SUM(request_amount) as request_amount, service_feature_code as 'Order Type' FROM fin_service_order WHERE date(date_time) between '$startDate' and '$endDate' group by service_feature_code, date(date_time) order by service_feature_code,date(date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date","Total Amount","Order Type");
								$headcount = 3;
								$query ="SELECT date(date_time) as Date, SUM(total_amount) as total_amount, service_feature_code as 'Order Type' FROM fin_service_order WHERE date(date_time) between '$startDate' and '$endDate' group by service_feature_code, date(date_time) order by service_feature_code,date(date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date","Request Amount","Total Amount","Order Type");
								$headcount = 4;
								$query ="SELECT date(date_time) as Date, SUM(request_amount) as request_amount, SUM(total_amount) as total_amount, service_feature_code as 'Order Type' FROM fin_service_order WHERE date(date_time) between '$startDate' and '$endDate' group by service_feature_code, date(date_time) order by service_feature_code,date(date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date","Request Amount","Order Type", "Agent");
								$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(a.date_time) order by  a.service_feature_code, b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date","Total Amount","Order Type", "Agent");
								$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(a.date_time) order by  a.service_feature_code, b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date","Request Amount","Total Amount","Order Type", "Agent");
								$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(a.date_time) order by  a.service_feature_code, b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$heading = array("Date","Request Amount", "Order Type");
								$headcount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b  WHERE  b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date","Total Amount", "Order Type");
								$headcount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b  WHERE  b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date","Request Amount","Total Amount", "Order Type");
								$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(total_amount) as total_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b  WHERE  b.agent_code = '$agentName' and a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date","Request Amount","Order Type","Agent");
								$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date","Total Amount","Order Type","Agent");
								$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date","Request Amount","Total Amount","Order Type","Agent");
								$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(request_amount) as request_amount, SUM(total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
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
								$heading = array("Date","Request Amount");
								$headcount = 2;
								$query ="SELECT date(date_time) as Date, SUM(request_amount) as request_amount FROM fin_service_order WHERE service_feature_code = '$type' and date(date_time) between '$startDate' and '$endDate'group by date(date_time) order by date(date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date","Total Amount");
								$headcount = 2;
								$query ="SELECT date(date_time) as Date, SUM(total_amount) as total_amount FROM fin_service_order WHERE service_feature_code = '$type' and date(date_time) between '$startDate' and '$endDate'group by date(date_time) order by date(date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date","Request Amount","Total Amount");
								$headcount = 3;
								$query ="SELECT date(date_time) as Date, SUM(request_amount) as request_amount, SUM(total_amount) as total_amount FROM fin_service_order WHERE service_feature_code = '$type' and date(date_time) between '$startDate' and '$endDate'group by date(date_time) order by date(date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date","Request Amount","Agent");
								$headcount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(request_amount) as request_amount , concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by b.agent_name, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date","Total Amount","Agent");
								$headcount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(total_amount) as total_amount , concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by b.agent_name, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date","Request Amount","Total Amount","Agent");
								$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(request_amount) as request_amount, SUM(total_amount) as total_amount , concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE a.user_id = b.user_id and date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' group by b.agent_name, date(a.date_time) order by  b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$heading = array("Date","Request Amount");
								$headcount = 2;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount  FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date","Total Amount");
								$headcount = 2;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount  FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'group by date(a.date_time) order by date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date","Request Amount","Total Amount");
								$headcount = 3;
								$query ="SELECT date(a.date_time) as Date,  SUM(a.request_amount) as request_amount , SUM(a.total_amount) as total_amount  FROM fin_service_order a, agent_info b WHERE b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate'group by date(a.date_time) order by date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date","Request Amount","Agent");
								$headcount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and a.service_feature_code = '$type' and a.user_id = b.user_id group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date","Total Amount","Agent");
								$headcount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and a.service_feature_code = '$type' and a.user_id = b.user_id group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date","Request Amount","Total Amount","Agent");
								$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount,, SUM(a.total_amount) as total_amount, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and a.service_feature_code = '$type' and a.user_id = b.user_id group by b.agent_name, date(a.date_time) order by b.agent_name, date(a.date_time)";
							}
						}
					}
				}
				else {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							if($ba == 'ra') {
								$heading = array("Date","Request Amount","Order Type");
								$headcount = 3;
								$query ="SELECT date(date_time) as Date, SUM(request_amount) as request_amount, service_feature_code as 'Order Type' FROM fin_service_order WHERE service_feature_code = '$type' and date(date_time) between '$startDate' and '$endDate' group by service_feature_code, date(date_time) order by service_feature_code,date(date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date","Total Amount","Order Type");
								$headcount = 3;
								$query ="SELECT date(date_time) as Date, SUM(total_amount) as total_amount, service_feature_code as 'Order Type' FROM fin_service_order WHERE service_feature_code = '$type' and date(date_time) between '$startDate' and '$endDate' group by service_feature_code, date(date_time) order by service_feature_code,date(date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date","Request Amount","Total Amount","Order Type");
								$headcount = 4;
								$query ="SELECT date(date_time) as Date, SUM(request_amount) as request_amount, SUM(total_amount) as total_amount, service_feature_code as 'Order Type' FROM fin_service_order WHERE service_feature_code = '$type' and date(date_time) between '$startDate' and '$endDate' group by service_feature_code, date(date_time) order by service_feature_code,date(date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date","Request Amount","Order Type","Agent");
								$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(a.date_time) order by  a.service_feature_code, b.agent_name, date(a.date_time)";
							}
							if($ba == 'ra') {
								$heading = array("Date","Total Amount","Order Type","Agent");
								$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(a.date_time) order by  a.service_feature_code, b.agent_name, date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date","Request Amount","Total Amount","Order Type","Agent");
								$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(a.date_time) order by  a.service_feature_code, b.agent_name, date(a.date_time)";
							}
						}
					}
					else {
						if($agentDetail == false) {
							if($ba == 'ra') {
								$heading = array("Date","Request Amount","Order Type");
								$headcount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b  WHERE  b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date","Total Amount","Order Type");
								$headcount = 3;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b  WHERE  b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date","Request Amount","Total Amount","Order Type");
								$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as request_amount, a.service_feature_code as 'Order Type' FROM fin_service_order a, agent_info b  WHERE  b.agent_code = '$agentName' and a.user_id = b.user_id and a.service_feature_code = '$type' and date(a.date_time) between '$startDate' and '$endDate' group by a.service_feature_code, date(a.date_time) order by a.service_feature_code,date(a.date_time)";
							}
						}
						else {
							if($ba == 'ra') {
								$heading = array("Date","Request Amount","Order Type","Agent");
								$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and b.agent_code = '$agentName' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
							}
							if($ba == 'ta') {
								$heading = array("Date","Total Amount","Order Type","Agent");
								$headcount = 4;
								$query ="SELECT date(a.date_time) as Date, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and b.agent_code = '$agentName' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
							}
							if($ba == 'bo') {
								$heading = array("Date","Request Amount","Total Amount","Order Type","Agent");
								$headcount = 5;
								$query ="SELECT date(a.date_time) as Date, SUM(a.request_amount) as request_amount, SUM(a.total_amount) as total_amount, a.service_feature_code as 'Order Type', concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as agent FROM fin_service_order a, agent_info b WHERE date(a.date_time) between '$startDate' and '$endDate' and a.service_feature_code = '$type' and b.agent_code = '$agentName' and a.user_id = b.user_id group by a.service_feature_code, b.agent_name, date(date_time) order by  a.service_feature_code,b.agent_name, date(date_time)";
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
		
		////error_log($query);
		heading($heading,$objPHPExcel,$headcount);
		$i = 2;						
		while ($row = mysqli_fetch_array($result))	{
			generateExcel ($i, $row,$objPHPExcel,$headcount);
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
