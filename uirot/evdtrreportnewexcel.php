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
	$profileid = $_SESSION['profile_id'];
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
$msg = "Deatiled EVD Sales Report For Date between $startDate and $endDate";
$objPHPExcel = new PHPExcel();

		if($profileid == 1 || $profileid == 10 || $profileid == 24 || $profileid == 22 || $profileid == 20 || $profileid == 23 || $profileid == 26 || $profileid  == 50) {
			$query = "SELECT a.e_transaction_id, c.operator_description, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user,(select name from state_list where state_id=b.state_id) as state,(select name from local_govt_list where local_govt_id = b.local_govt_id) as local, a.request_amount,ifNULL(a.partner_charge,'-') as partner_charge,ifNULL(a.other_charge,'-') as other_charge, a.total_amount, a.date_time ,a.ams_charge,group_concat(d.charge_value ORDER BY d.service_charge_party_name) as charges  FROM evd_transaction a, agent_info b, operator c,evd_service_order_comm d  WHERE a.user_id = b.user_id and a.operator_id = c.operator_id and a.e_transaction_id = d.e_transaction_id";
		}
		if($profileid  == 51) {
			$query = "SELECT a.e_transaction_id, c.operator_description, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user, a.request_amount,ifNULL(a.partner_charge,'-') as partner_charge,ifNULL(a.other_charge,'-') as other_charge, a.total_amount, a.date_time   FROM evd_transaction a, agent_info b, operator c  WHERE a.user_id = b.user_id and a.operator_id = c.operator_id and b.user_id = '".$_SESSION['user_id']."'";
		}
		if($profileid  == 52) {
			$query = "SELECT a.e_transaction_id, c.operator_description, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user, a.request_amount,ifNULL(a.partner_charge,'-') as partner_charge,ifNULL(a.other_charge,'-') as other_charge, a.total_amount, a.date_time   FROM evd_transaction a, agent_info b, operator c  WHERE a.user_id = b.user_id and a.operator_id = c.operator_id and b.user_id = '".$_SESSION['user_id']."' and b.sub_agent = 'Y'";
		}
		
		if($creteria == "BT") {
			if($type == "ALL") {
				$query .= " and date(a.date_time) >= '$startDate' and  date(a.date_time) <= '$endDate'  group by service_feature_code, a.e_transaction_id, a.request_amount, a.total_amount, a.date_time,b.agent_name,b.parent_code, user, a.ams_charge order by a.date_time desc ";
			}
			else{ 
				
				$query .= " and a.operator_id = '$type' and date(a.date_time) >= '$startDate' and  date(a.date_time) <= '$endDate' group by a.e_transaction_id,b.agent_name,b.parent_code,state,local order by a.date_time desc ";
			}
		}
		if($creteria == "BO") {
			$query .= " and a.e_transaction_id = $orderNo group by a.e_transaction_id,b.agent_name,b.parent_code,state,local order by a.e_transaction_id";
		}
		
			if($creteria == "S"){
				if($state == "ALL"){
					$query .= " and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' group by a.e_transaction_id,b.agent_name,b.parent_code,state,local order by date_time desc ";
					
				}else{
					$query .= " and b.state_id = '$state' and b.local_govt_id='$local_govt_id' and date(date_time) >= '$startDate' and  date(date_time) <= '$endDate' group by a.e_transaction_id,b.agent_name,b.parent_code,state,local order by date_time desc ";
				}
			}
			
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		
		error_log($query);
		$heading = array("Order No","Operator","Agent","State","Local Government","Request Amount","Partner Charge","Other Charge","Total Amount","Date Time","Ams Charges","Total Splited Commissions","Agent Commission","Champion Commission","Kadick Commission");
		$headcount = 15;
		heading($heading,$objPHPExcel,$headcount);
		$i = 2;						
		while ($row = mysqli_fetch_array($result))	{
			//error_log("agentrow['10']_slit  ==".$row['11']);
			$split_charges = explode(",",$row['11']);
			$agent_slit = $split_charges[0] ;
			$row['12'] = $agent_slit;
			
			//error_log("agent_slit  ==".$agent_slit);
			//error_log("agent_slit1  ==".$row['14'] );
			//error_log("split_charges  ==".$split_charges );
			$champion_slit = $split_charges[1];
			$row['13'] = $champion_slit;
			$kadick_slit = $split_charges[2];
			$row['14'] = $kadick_slit;
			/* if ($split_charges[2] == '') { 
			$kadick_slit = '-';
			} */
			//$row['10'] = null;
			if($kadick_slit === NULL || $kadick_slit  === '') {
    				$kadick_slit = 0;
			}
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
