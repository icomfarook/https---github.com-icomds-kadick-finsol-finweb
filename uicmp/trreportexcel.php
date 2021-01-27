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
	$orderNo	=  $_POST['orderNo'];	
	$startDate		=  $_POST['startDate'];
	$endDate	=  $_POST['endDate'];
	$creteria 	= $_POST['creteria'];
	$userid 	=  $_SESSION['user_id'];
	$profileid = $_SESSION['profile_id'];
/* 	$agentDetail 	= $_POST['agentDetail'];
	$subAgentName 	= $_POST['subAgentName'];
	$subAgentDetail 	= $_POST['subAgentDetail'];	
	$typeDetail 	= $_POST['typeDetail']; */
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
$title = "";
 if($servername == "164") {
	$title = "NFCPORTAL - 164";
 }
 else {
	$title = "PORTAL - 202";
 }  
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
			if($profileid  == 1 || $profileid  == 10 || $profileid  == 50) {
				$query = "SELECT concat(c.service_feature_code, ' - ', d.feature_description) as service_feature_code, c.order_no, c.request_amount, c.total_amount, c.create_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user, if(c.status='I','I-Inprogress',if(c.status='N','N-Name Enqiury',if(c.status='S','S-Success',if(c.status='E','E-Error',if(c.status='T','T-Timeout',if(c.status='G','G-Triggered',if(c.status='R','R-Request Cancel',if(c.status='X','X-Cancelled',if(c.status='O','O-Request Confirm','-'))))))))) as status FROM fin_service_order a, agent_info b, fin_request c, service_feature d WHERE a.fin_service_order_no = c.order_no  and c.user_id = b.user_id and c.service_feature_code = d.feature_code";
			}
			if($profileid  == 51) {
				$query = "SELECT concat(c.service_feature_code, ' - ', d.feature_description) as service_feature_code, c.order_no, c.request_amount, c.total_amount, c.create_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user, if(c.status='I','I-Inprogress',if(c.status='N','N-Name Enqiury',if(c.status='S','S-Success',if(c.status='E','E-Error',if(c.status='T','T-Timeout',if(c.status='G','G-Triggered',if(c.status='R','R-Request Cancel',if(c.status='X','X-Cancelled',if(c.status='O','O-Request Confirm','-'))))))))) as status FROM fin_service_order a, agent_info b, fin_request c, service_feature d WHERE a.fin_service_order_no = c.order_no  and c.user_id = b.user_id and c.service_feature_code = d.feature_code and b.agent_code = '".$_SESSION['party_code']."'";
			}
			if($profileid  == 52) {
				$query = "SELECT concat(c.service_feature_code, ' - ', d.feature_description) as service_feature_code, c.order_no, c.request_amount, c.total_amount, c.create_time, concat(b.agent_name,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = b.parent_code), 'Self'),']') as user, if(c.status='I','I-Inprogress',if(c.status='N','N-Name Enqiury',if(c.status='S','S-Success',if(c.status='E','E-Error',if(c.status='T','T-Timeout',if(c.status='G','G-Triggered',if(c.status='R','R-Request Cancel',if(c.status='X','X-Cancelled',if(c.status='O','O-Request Confirm','-'))))))))) as status FROM fin_service_order a, agent_info b, fin_request c, service_feature d WHERE a.fin_service_order_no = c.order_no  and c.user_id = b.user_id and c.service_feature_code = d.feature_code and b.agent_code = '".$_SESSION['party_code']."' and b.sub_agent = 'Y'";
			}
			if($creteria == "BT") {
				if($type == "ALL") {
					$query .= " and date(c.create_time) >= '$startDate' and  date(c.create_time) <= '$endDate' order by c.create_time desc ";
				}
				else{ 
					$query .= " and c.service_feature_code = '$type' and date(c.create_time) >= '$startDate' and  date(c.create_time) <= '$endDate' order by c.create_time desc ";
				}
			}
			if($creteria == "BO") {
				$query .= " and c.order_no = $orderNo order by c.order_no";
			}	
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		
		////error_log($query);
		$heading = array("Code","Order No", "Request Amount", "Total Amount", "Date & Time", "User");
		$headcount = 6;
		heading($heading,$objPHPExcel,$headcount);
		$i = 2;						
		while ($row = mysqli_fetch_array($result))	{
			generateExcel ($i, $row,$objPHPExcel,$headcount);
			$i++;				
		}
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

