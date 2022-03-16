 <?php
 ERROR_REPORTING(E_ALL);
$data = json_decode(file_get_contents("php://input"));
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
include("excelfunctions.php");
require_once   '../common/PHPExcel/Classes/PHPExcel/IOFactory.php';
	$type	=  $_POST['type'];	
	$orderNo	=  $_POST['orderNo'];	
	$startDate		=  $_POST['startDate'];
	$endDate	=  $_POST['endDate'];
	$creteria 	= $_POST['creteria'];
	$Terminal 	= $_POST['Terminal'];
	$championCode 	= $_POST['championCode'];
	$state 	= $_POST['state'];
	$profileid = $_SESSION['profile_id'];

	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
$title = "";
$title = "KadickMoni";
if($startDate == null ){
	$startDate = date('Y-m-d');
}
if($endDate == null ){
		$endDate   =  date('Y-m-d');
}
$msg = "Sales Report For Date between $startDate and $endDate";
$objPHPExcel = new PHPExcel();

		if($profileid == 1 || $profileid == 10 || $profileid == 24 || $profileid == 22 || $profileid == 20 || $profileid == 23 || $profileid == 26 || $profileid  == 50) {
			$query = "SELECT a.fin_service_order_no,concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code,concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user,  a.request_amount, a.total_amount,IF(a.service_feature_code='CIN',a.auth_code, IF(a.service_feature_code='COU',a.auth_code, IF(a.service_feature_code='MP0',c.rrn,'-'))) as reference,  a.date_time as date_time ,c.update_time,ifNULL(a.agent_charge,'-') as agent_charge,ifNULL(a.ams_charge,'-') as ams_charge,ifNULL(a.stamp_charge,'-') as stamp_charge,ifNULL(a.partner_charge,'-') as partner_charge,ifNULL(a.other_charge,'-') as other_charge,ifNULL(c.service_charge,'-') as service_charge,if(c.all_in = 'Y',IFNULL(c.total_amount,0.00) - (c.request_amount),'0') as cash  FROM fin_service_order a, agent_info b, fin_request c, service_feature d,user_pos e WHERE a.fin_service_order_no = c.order_no and c.status = 'S' and a.user_id = b.user_id and a.service_feature_code = d.feature_code and a.user_id = e.user_id";
		}
		if($profileid  == 51) {
			$query = "SELECT a.fin_service_order_no, concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.request_amount, a.total_amount,a.date_time,  IF(a.service_feature_code='CIN',a.auth_code , IF(a.service_feature_code='COU',a.auth_code, IF(a.service_feature_code='MP0',c.rrn,''))) as reference,concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user,ifNULL(a.agent_charge,'-') as agent_charge,ifNULL(a.ams_charge,'-') as ams_charge,ifNULL(a.stamp_charge,'-') as stamp_charge,ifNULL(a.partner_charge,'-') as partner_charge,ifNULL(a.other_charge,'-') as other_charge,ifNULL(c.service_charge,'-') as service_charge FROM fin_service_order a, agent_info b, fin_request c, service_feature d WHERE a.fin_service_order_no = c.order_no and c.status = 'S' and a.user_id = b.user_id and a.service_feature_code = d.feature_code and b.agent_code = '".$_SESSION['party_code']."' and a.user_id = b.user_id ";
		}
		if($profileid  == 52) {
			$query = "SELECT a.fin_service_order_no, concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.request_amount, a.total_amount, a.date_time, IF(a.service_feature_code='CIN',a.auth_code , IF(a.service_feature_code='COU',a.auth_code, IF(a.service_feature_code='MP0',c.rrn,''))) as reference, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user,ifNULL(a.agent_charge,'-') as agent_charge,ifNULL(a.ams_charge,'-') as ams_charge,ifNULL(a.stamp_charge,'-') as stamp_charge,ifNULL(a.partner_charge,'-') as partner_charge,ifNULL(a.other_charge,'-') as other_charge,ifNULL(c.service_charge,'-') as service_charge FROM fin_service_order a, agent_info b, fin_request c, service_feature d WHERE a.fin_service_order_no = c.order_no and c.status = 'S' and a.user_id = b.user_id and a.service_feature_code = d.feature_code and b.agent_code = '".$_SESSION['party_code']."' and b.sub_agent = 'Y' and a.user_id = b.user_id ";
		}
	if($creteria == "BT") {
			if($type == "ALL") {
				$query .= " and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
			}
			else{ 
				$query .= " and a.service_feature_code = '$type' and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
			}
		}
		if($creteria == "BO") {
			$query .= " and a.fin_service_order_no = $orderNo order by a.fin_service_order_no";
		}
		if($creteria == "C") { 
			if($championCode == "ALL") {
				$query .= " and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
			}
			else{ 
				$query .= " and b.parent_code = '$championCode' and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
			}
		}
		if($creteria == "S") { 
			if($state == "ALL") {
				$query .= " and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
			}
			else{ 
				if($local_govt_id == ""){
					$query .= " and b.state_id = '$state'  and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
				}else{
					$query .= " and b.state_id = '$state' and b.local_govt_id='$local_govt_id' and  and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc ";
				}
			}
		}
		if($creteria == "T") { 
			$query .= " and e.terminal_id = '$Terminal' and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' order by date_time desc";
		}
	
			
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		
		error_log($query);
		$heading = array("Order No","Order Type","Agent","Request Amount","Total Amount","Reference", "Date Time","Update Time","Agent Charge","Ams Charge","Stamp Charge","Partner Charge","Other Charge","Total Charge","Cash");
		$headcount = 15;
		heading($heading,$objPHPExcel,$headcount);
		$i = 2;						
		while ($row = mysqli_fetch_array($result))	{
			
			$cash = -($row['cash']);
			$row['14'] = $cash;
			
			error_log("cash ==".$cash);
			
			generateExcel ($i, $row,$objPHPExcel,$headcount);
			$i++;
			$lastrow = $objPHPExcel->getActiveSheet()->getHighestRow();			
			$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$lastrow)
			->getNumberFormat()
			->setFormatCode('0');
		}
		$objPHPExcel->getActiveSheet()
					->getColumnDimension('F')
					->setAutoSize(true);
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
