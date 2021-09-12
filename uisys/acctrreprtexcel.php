 <?php
 ERROR_REPORTING(E_ALL);
$data = json_decode(file_get_contents("php://input"));
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
include("excelfunctions.php");
require_once   '../common/PHPExcel/Classes/PHPExcel/IOFactory.php';
	$type	=  $_POST['type'];
	$orderNo	=  $_POST['orderNo'];
	$status	= $_POST['status'];
	$startDate		=  $_POST['startDate'];
	$endDate	=  $_POST['endDate'];
	$userid 	=  $_SESSION['user_id'];
	$profileid = $_SESSION['profile_id'];
/* 	$agentDetail 	= $_POST['agentDetail'];
	$subAgentName 	= $_POST['subAgentName'];
	$subAgentDetail 	= $_POST['subAgentDetail'];	
	$typeDetail 	= $_POST['typeDetail']; */
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
$title = "KadickMoni";

if($startDate == null ){
	$startDate = date('Y-m-d');
}
if($endDate == null ){
		$endDate   =  date('Y-m-d');
}
$msg = "Acc Transaction Report For Date between $startDate and $endDate";
$objPHPExcel = new PHPExcel();
		if($profileid == 1 || $profileid == 10 || $profileid == 24 || $profileid == 22 || $profileid == 20 || $profileid == 23 || $profileid == 26  || $profileid  == 50) {
			$query = "SELECT  ifNULL(c.order_no,'-') as order_no, concat(c.service_feature_code, ' - ', d.feature_description) as service_feature_code,  concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user, if(c.status='I','I-Inprogress',if(c.status='N','N-Name Enqiury',if(c.status='S','S-Success',if(c.status='E','E-Error',if(c.status='T','T-Timeout',if(c.status='G','G-Triggered',if(c.status='R','R-Request Cancel',if(c.status='X','X-Cancelled',if(c.status='O','O-Request Confirm','-'))))))))) as status,  ifNULL(c.account_number,'-') as rrn,c.account_balance,c.bvn,c.create_time FROM agent_info b, acc_request c, service_feature d WHERE c.user_id = b.user_id and c.service_feature_code = d.feature_code ";
		}
		if($profileid  == 51) {
			$query = "SELECT c.acc_request_id, concat(c.service_feature_code, ' - ', d.feature_description) as service_feature_code, c.order_no, c.create_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user, if(c.status='I','I-Inprogress',if(c.status='N','N-Name Enqiury',if(c.status='S','S-Success',if(c.status='E','E-Error',if(c.status='T','T-Timeout',if(c.status='G','G-Triggered',if(c.status='R','R-Request Cancel',if(c.status='X','X-Cancelled',if(c.status='O','O-Request Confirm','-'))))))))) as status,  ifNULL(c.account_number,'-') as rrn,c.bvn,c.account_balance  FROM agent_info b, acc_request c, service_feature d WHERE c.user_id = b.user_id and c.service_feature_code = d.feature_code and b.agent_code = '".$_SESSION['party_code']."'";
		}
		if($profileid  == 52) {
			$query = "SELECT c.acc_request_id, concat(c.service_feature_code, ' - ', d.feature_description) as service_feature_code, c.order_no, c.create_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user, if(c.status='I','I-Inprogress',if(c.status='N','N-Name Enqiury',if(c.status='S','S-Success',if(c.status='E','E-Error',if(c.status='T','T-Timeout',if(c.status='G','G-Triggered',if(c.status='R','R-Request Cancel',if(c.status='X','X-Cancelled',if(c.status='O','O-Request Confirm','-'))))))))) as status,  ifNULL(c.account_number,'-') as rrn,c.bvn,c.account_balance  FROM agent_info b, acc_request c, service_feature d WHERE c.user_id = b.user_id and c.service_feature_code = d.feature_code and b.agent_code = '".$_SESSION['party_code']."' and b.sub_agent = 'Y'";
		}
		if($type == 'ALL') {
			if($status == "ALL") { 
				$query .= " and date(c.create_time) >= '$startDate' and  date(c.create_time) <= '$endDate' order by c.create_time desc ";
			}
			else{ 
				$query .= " and c.status = '$status' and date(c.create_time) >= '$startDate' and  date(c.create_time) <= '$endDate' order by c.create_time desc ";
			}
		}
		else {
			if($status == "ALL") {
				$query .= " and c.service_feature_code = '$type' and date(c.create_time) >= '$startDate' and  date(c.create_time) <= '$endDate' order by c.create_time desc ";
			}
			else{ 
				$query .= " and c.service_feature_code = '$type' and c.status = '$status' and date(c.create_time) >= '$startDate' and  date(c.create_time) <= '$endDate' order by c.create_time desc ";
			}
		}	
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		
		error_log($query);
		$heading = array("Order #", "Order Type",  "Agent","Status","Account Number","Account Balance","BVN","Date Time");
		$headcount = 8;
		heading($heading,$objPHPExcel,$headcount);
		$i = 2;						
		while ($row = mysqli_fetch_array($result))	{
			generateExcel ($i, $row, $objPHPExcel,$headcount);
			$i++;			
			$lastrow = $objPHPExcel->getActiveSheet()->getHighestRow();			
			$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$lastrow)
			->getNumberFormat()
			->setFormatCode(
				'0'
			);
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




