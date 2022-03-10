 <?php
 ERROR_REPORTING(E_ALL);
$data = json_decode(file_get_contents("php://input"));
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
include("excelfunctions.php");
require_once   '../common/PHPExcel/Classes/PHPExcel/IOFactory.php';
	$type	=  $_POST['type'];
	$orderNo	=  $_POST['orderNo'];	
	$championCode		=  $_POST['championCode'];
	$startDate		=  $_POST['startDate'];
	$endDate	=  $_POST['endDate'];
	$creteria 	= $_POST['creteria'];
	$profileid = $_SESSION['profile_id'];
	$reportFor 	= $_POST['reportFor'];
	$state 	= $_POST['state'];
	$local_govt_id 	= $_POST['local_govt_id'];
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
if($creteria =="BT"){
		$msg = "Detail Bill_payment_Sales_Report_Order_Type_".$type."_And_Date_Between_".$startDate."_".$endDate;
	}else if($creteria =="BO"){
		$msg = "Detail Bill_payment_Sales_Report_Order_Type_".$orderNo;
	}
	else {
		$msg = "Detail Bill_payment_Sales_Report_Date_Between_".$startDate."_".$endDate;
	}
$objPHPExcel = new PHPExcel();

		if($profileid == 1 || $profileid == 10 || $profileid == 24 || $profileid == 22 || $profileid == 20 || $profileid == 23 || $profileid == 26 || $profileid  == 50) {
			$query = " SELECT a.bp_service_order_no,concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code,concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user ,(select name from state_list where state_id=b.state_id) as state,(select name from local_govt_list where local_govt_id = b.local_govt_id) as local,  a.request_amount,ifNULL(a.stamp_charge,'-') as stamp_charge,ifNULL(a.partner_charge,'-') as partner_charge,ifNULL(a.other_charge,'-') as other_charge, a.total_amount,a.date_time as date_time ,c.update_time,c.account_no,c.account_name,a.agent_charge,a.ams_charge, group_concat(p.charge_value ORDER BY p.service_charge_party_name) as charges FROM bp_service_order a, agent_info b, bp_request c, service_feature d,bp_service_order_comm p WHERE a.bp_service_order_no = c.order_no and c.status = 'S' and a.user_id = b.user_id and a.service_feature_code = d.feature_code and a.bp_service_order_no = p.bp_service_order_no";
		}
			if($profileid  == 50) {
			if($reportFor == 'ALL'){
				$query = "SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.bp_service_order_no, a.request_amount,ifNULL(a.stamp_charge,'-') as stamp_charge,ifNULL(a.partner_charge,'-') as partner_charge,ifNULL(a.other_charge,'-') as other_charge, a.total_amount, a.date_time as date_time,  b.champion_name as user ,c.update_time FROM bp_service_order a, champion_info b, bp_request c, service_feature d WHERE a.bp_service_order_no = c.order_no and c.status = 'S' and a.service_feature_code = d.feature_code and b.champion_code = '".$_SESSION['party_code']."' and a.user_id = b.user_id 
				UNION
				SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.bp_service_order_no, a.request_amount,ifNULL(a.stamp_charge,'-') as stamp_charge,ifNULL(a.partner_charge,'-') as partner_charge,ifNULL(a.other_charge,'-') as other_charge, a.total_amount, a.date_time as date_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user,c.update_time FROM bp_service_order a, agent_info b, bp_request c, service_feature d WHERE a.bp_service_order_no = c.order_no and c.status = 'S' and a.service_feature_code = d.feature_code and b.parent_code = '".$_SESSION['party_code']."' and a.user_id = b.user_id ";
			}else if($reportFor == 'C'){
				$query = "SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.bp_service_order_no, a.request_amount,ifNULL(a.stamp_charge,'-') as stamp_charge,ifNULL(a.partner_charge,'-') as partner_charge,ifNULL(a.other_charge,'-') as other_charge, a.total_amount, a.date_time as date_time, b.champion_name as user,c.update_time FROM bp_service_order a, champion_info b, bp_request c, service_feature d WHERE a.bp_service_order_no = c.order_no and c.status = 'S' and a.service_feature_code = d.feature_code and b.champion_code = '".$_SESSION['party_code']."' and a.user_id = b.user_id ";
				
			}else{
				if($partycode == 'ALL'){
					$query = "SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.bp_service_order_no, a.request_amount,ifNULL(a.stamp_charge,'-') as stamp_charge,ifNULL(a.partner_charge,'-') as partner_charge,ifNULL(a.other_charge,'-') as other_charge, a.total_amount, a.date_time as date_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user,c.update_time FROM bp_service_order a, agent_info b, bp_request c, service_feature d WHERE a.bp_service_order_no = c.order_no and c.status = 'S' and a.service_feature_code = d.feature_code and b.parent_code = '".$_SESSION['party_code']."' and a.user_id = b.user_id ";
				}else{
					$query = "SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.bp_service_order_no, a.request_amount,ifNULL(a.stamp_charge,'-') as stamp_charge,ifNULL(a.partner_charge,'-') as partner_charge,ifNULL(a.other_charge,'-') as other_charge, a.total_amount, a.date_time as date_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user,c.update_time FROM bp_service_order a, agent_info b, bp_request c, service_feature d WHERE a.bp_service_order_no = c.order_no and c.status = 'S' and a.service_feature_code = d.feature_code and b.parent_code = '".$_SESSION['party_code']."' and a.user_id = b.user_id and  b.agent_code = '$partycode'";
				}
				
			}
			
		}
		if($profileid  == 52) {
			$query = "SELECT concat(a.service_feature_code, ' - ', d.feature_description) as service_feature_code, a.bp_service_order_no, a.request_amount,ifNULL(a.stamp_charge,'-') as stamp_charge,ifNULL(a.partner_charge,'-') as partner_charge,ifNULL(a.other_charge,'-') as other_charge, a.total_amount, a.date_time as date_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user,c.update_time FROM bp_service_order a, agent_info b, bp_request c, service_feature d WHERE a.bp_service_order_no = c.order_no and c.status = 'S' and a.service_feature_code = d.feature_code and b.agent_code = '".$_SESSION['party_code']."' and b.sub_agent = 'Y' and a.user_id = b.user_id ";
		}
		if($creteria == "BT") {
			if($type == "ALL") {
				$query .= " and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' group by a.bp_service_order_no,b.agent_name,b.parent_code,c.account_no,c.account_name,d.feature_description,c.update_time,state,local order by date_time desc ";
			}
			else{
					$query .= " and a.service_feature_code = '$type' and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' group by a.bp_service_order_no,b.agent_name,b.parent_code,c.account_no,c.account_name,d.feature_description,c.update_time,state,local  order by date_time desc ";
			}
		}
		if($creteria == "BO") {
			$query .= " and a.bp_service_order_no = $orderNo ggroup by a.bp_service_order_no,b.agent_name,b.parent_code,c.account_no,c.account_name,d.feature_description,c.update_time,state,local order by a.bp_service_order_no";
		}
		if($creteria == "C") { 
			if($championCode == "ALL") {
				$query .= " and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' group by a.bp_service_order_no,b.agent_name,b.parent_code,c.account_no,c.account_name,d.feature_description,c.update_time,state,local order by date_time desc ";
			}
			else{ 
				$query .= " and b.parent_code = '$championCode' and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' group by a.bp_service_order_no,b.agent_name,b.parent_code,c.account_no,c.account_name,d.feature_description,c.update_time,state,local order by date_time desc ";
			}
		}
		if($creteria == "S") { 
			if($state == "ALL") {
				$query .= " and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' group by a.bp_service_order_no,b.agent_name,b.parent_code,c.account_no,c.account_name,d.feature_description,c.update_time,state,local order by date_time desc ";
			}
			else{ 
				if($local_govt_id == ""){
					$query .= " and b.state_id = '$state'  and  date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' group by a.bp_service_order_no,b.agent_name,b.parent_code,c.account_no,c.account_name,d.feature_description,c.update_time,state,local order by date_time desc ";
				}else{
					$query .= " and b.state_id = '$state' and b.local_govt_id = '$local_govt_id'  and  date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' group by a.bp_service_order_no,b.agent_name,b.parent_code,c.account_no,c.account_name,d.feature_description,c.update_time,state,local order by date_time desc ";
				}
				
			}
		}
		
			
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		
		error_log($query);
		$heading = array("Order No","Order Type","Agent Name","State","Local Government","Request Amount","Stamp Charge","Partner Charge","Other Charge","Total Amount","Date Time","Update Time",  "Account Number","Account Name","Agent Charges","Ams Charge","Total Splited Commission","Agent Commission","Champion Commission","Kadick Commission");
		$headcount = 20;
		heading($heading,$objPHPExcel,$headcount);
		$i = 2;						
		while ($row = mysqli_fetch_array($result))	{
			//error_log("agentrow['10']_slit  ==".$row['14']);
			$split_charges = explode(",",$row['16']);
			$agent_slit = $split_charges[0] ;
			$row['17'] = $agent_slit;
			
			//error_log("agent_slit  ==".$agent_slit);
			//error_log("agent_slit1  ==".$row['15'] );
			//error_log("split_charges  ==".$split_charges );
			$champion_slit = $split_charges[1];
			$row['18'] = $champion_slit;
			$kadick_slit = $split_charges[2];
			$row['19'] = $kadick_slit;
			//$row['10'] = null;
			
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
