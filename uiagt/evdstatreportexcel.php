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
	$subAgentName 	= $_POST['subAgentName'];
	$subAgentDetail 	= $_POST['subAgentDetail'];	
	$typeDetail 	= $_POST['typeDetail'];
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
$msg = "Stat Report For Date between $startDate and $endDate";
$objPHPExcel = new PHPExcel();
			if($type == "ALL") {
				if($typeDetail == false) {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							$heading = array("Date","Count");
							$headcount = 2;
							$query ="SELECT date(b.date_time) as Date, count(*) as Count FROM evd_service_order_comm a, evd_transaction b WHERE a.e_transaction_id = b.e_transaction_id and  date(b.date_time) between '$startDate' and '$endDate' group by date(b.date_time) order by date(b.date_time)";
						}
						else {
							$heading = array("Date","Count","Agent");
							$headcount = 3;
							$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent FROM evd_service_order_comm a, agent_info b, evd_transaction c WHERE c.e_transaction_id = a.e_transaction_id  and a.user_id = b.user_id and date(c.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
						}
					}
					else {
						if($agentDetail == false) {
							$heading = array("Date","Count");
							$headcount = 2;
							$query ="SELECT date(c.date_time) as Date, count(*) as Count FROM evd_service_order_comm a, agent_info b, evd_transaction c WHERE c.e_transaction_id = a.e_transaction_id and  b.agent_code = '$agentName' and a.user_id = b.user_id and date(c.date_time) between '$startDate' and '$endDate' group by date(c.date_time) order by date(c.date_time)";
						}
						else {
							$heading = array("Date","Count","Agent");
							$headcount = 3;
							$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent FROM evd_service_order_comm a, agent_info b, evd_transaction c WHERE a.e_transaction_id = c.e_transaction_id  and b.agent_code = '$agentName' and a.user_id = b.user_id and date(c.date_time) between '$startDate' and '$endDate' group by b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
						}
					}
				}
				else {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							$heading = array("Date","Count","Operator");
							$headcount = 3;
							$query ="SELECT date(b.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator' FROM evd_service_order_comm a, evd_transaction b, operator c WHERE a.e_transaction_id = b.e_transaction_id and  b.operator_id = c.operator_id and date(b.date_time) between '$startDate' and '$endDate' group by b.operator_id, date(b.date_time) order by b.operator_id, date(b.date_time)";
						}
						else {
							$heading = array("Date","Count","Operator","Agent");
							$headcount = 4;
							$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent FROM evd_service_order_comm a, agent_info b, operator c, evd_transaction d WHERE a.e_transaction_id = d.e_transaction_id and  c.operator_id = d.operator_id and date(d.date_time) between '$startDate' and '$endDate' and a.user_id = b.user_id group by d.operator_id, agent, date(d.date_time) order by d.operator_id, agent, date(d.date_time)";
						}
					}
					else {
						if($agentDetail == false) {
							$heading = array("Date","Count","Operator");
							$headcount = 3;
							$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator' FROM evd_service_order_comm a, agent_info b, operator c, evd_transaction d WHERE a.e_transaction_id = d.e_transaction_id and  c.operator_id = d.operator_id and b.agent_code = '$agentName' and a.user_id = b.user_id and date(d.date_time) between '$startDate' and '$endDate' group by d.operator_id, date(d.date_time) order by d.operator_id,date(d.date_time)";
						}
						else {
							$heading = array("Date","Count","Operator","Agent");
							$headcount = 4;
							$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent FROM evd_service_order_comm a, agent_info b, operator c, evd_transaction d WHERE a.e_transaction_id = d.e_transaction_id and c.operator_id = d.operator_id and date(d.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and a.user_id = b.user_id group by d.operator_id, b.agent_name, date(d.date_time) order by d.operator_id,b.agent_name, date(d.date_time)";
						}
					}
				}
			}
			else {
				if($typeDetail == false) {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							$heading = array("Date","Count");
							$headcount = 2;
							$query ="SELECT date(b.date_time) as Date, count(*) as Count FROM evd_service_order_comm a, evd_transaction b WHERE a.e_transaction_id = b.e_transaction_id and b.operator_id = '$type' and date(b.date_time) between '$startDate' and '$endDate'group by date(b.date_time) order by date(b.date_time)";
						}
						else {							
							$heading = array("Date","Count","Agent");
							$headcount = 3;
							$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent FROM evd_service_order_comm a, agent_info b, evd_transaction c WHERE a.e_transaction_id = c.e_transaction_id  and a.user_id = b.user_id and date(c.date_time) between '$startDate' and '$endDate' and c.operator_id = '$type' group by b.agent_name, date(c.date_time) order by  b.agent_name, date(c.date_time)";
						}
					}
					else {
						if($agentDetail == false) {
							$heading = array("Date","Count");
							$headcount = 2;
							$query ="SELECT date(c.date_time) as Date, count(*) as Count FROM evd_service_order_comm a, agent_info b, evd_transaction c WHERE a.e_transaction_id = c.e_transaction_id  and b.agent_code = '$agentName' and a.user_id = b.user_id and c.operator_id = '$type' and date(c.date_time) between '$startDate' and '$endDate'group by date(c.date_time) order by date(c.date_time)";
						}
						else {
							$heading = array("Date","Count","Agent");
							$headcount = 3;
							$query ="SELECT date(c.date_time) as Date, count(*) as Count, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent FROM evd_service_order_comm a, agent_info b, evd_transaction c WHERE a.e_transaction_id = c.e_transaction_id  and date(c.date_time) between '$startDate' and '$endDate' and b.agent_code = '$agentName' and c.operator_id = '$type' and a.user_id = b.user_id group by b.agent_name, date(c.date_time) order by b.agent_name, date(c.date_time)";
						}
					}
				}
				else {
					if($agentName == "ALL"){
						if($agentDetail == false) {
							$heading = array("Date","Count","Operator");
							$headcount = 3;
							$query ="SELECT date(b.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator' FROM evd_service_order_comm a, evd_transaction b, operator c WHERE a.e_transaction_id = b.e_transaction_id and  b.operator_id = c.operator_id and b.operator_id = '$type' and date(b.date_time) between '$startDate' and '$endDate' group by b.operator_id, date(b.date_time) order by b.operator_id, date(b.date_time)";
						}
						else {
							$heading = array("Date","Count","Operator","Agent");
							$headcount = 4;
							$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,'[',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent FROM evd_service_order_comm a, agent_info b, operator c, evd_transaction d WHERE a.e_transaction_id = d.e_transaction_id and  c.operator_id = d.operator_id and date(d.date_time) between '$startDate' and '$endDate' and d.operator_id = '$type' and a.user_id = b.user_id group by d.operator_id, b.agent_name, date(d.date_time) order by d.operator_id, b.agent_name, date(d.date_time)";
						}
					}
					else {
						if($agentDetail == false) {
							$heading = array("Date","Count","Operator");
							$headcount = 3;
							$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator' FROM evd_service_order_comm a, agent_info b, operator c, evd_transaction d WHERE a.e_transaction_id = d.e_transaction_id  and c.operator_id = d.operator_id and b.agent_code = '$agentName' and a.user_id = b.user_id and d.operator_id  = '$type' and date(d.date_time) between '$startDate' and '$endDate' group by d.operator_id, date(d.date_time) order by d.operator_id,date(d.date_time)";
						}
						else {
							$heading = array("Date","Count","Operator","Agent");
							$headcount = 4;
							$query ="SELECT date(d.date_time) as Date, count(*) as Count, concat(c.operator_code, ' - ', c.operator_description) as 'Operator', concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as agent FROM evd_service_order_comm a, agent_info b, operator c, evd_transaction d WHERE a.e_transaction_id = d.e_transaction_id  and c.operator_id = d.operator_id and date(d.date_time) between '$startDate' and '$endDate' and d.operator_id  = '$type' and b.agent_code = '$agentName' and a.user_id = b.user_id group by d.operator_id, b.agent_name, date(d.date_time) order by  d.operator_id,b.agent_name, date(d.date_time)";
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
