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
			
			 if($creteria == "BT") {
					if($type == "ALL") {
				$heading = array("Code","Order No", "Request Amount", "Total Amount", "Date & Time", "User");
				$headcount = 6;
				$query = "SELECT a.service_feature_code, a.fin_service_order_no, a.request_amount, a.total_amount, a.date_time, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as user FROM fin_service_order a, agent_info b WHERE a.user_id = b.user_id  and b.user_id='$userid' and date(a.date_time) >= '$startDate' and  date(a.date_time)  <= '$endDate' order by a.date_time desc ";
			   	}
				else if($type != "ALL") {
					$heading = array("Code","Order No", "Request Amount", "Total Amount", "Date & Time", "User");
				$headcount = 6;
				$query = "SELECT a.service_feature_code, a.fin_service_order_no, a.request_amount, a.total_amount, a.date_time, concat(b.agent_name,'[',ifNULL('self',(select champion_name FROM champion_info WHERE champion_code = b.parent_code)),']') as user FROM fin_service_order a, agent_info b WHERE a.user_id = b.user_id  and b.user_id='$userid' and date(a.date_time) >= '$startDate' and  date(a.date_time)  <= '$endDate' order by a.date_time desc ";
					
				}
			else{ 
				$query .= " and a.service_feature_code = '$type' and date(a.date_time) >= '$startDate' and  date(a.date_time)  <= '$endDate' order by a.date_time desc ";
			}
		}
		if($creteria == "BO") {
			$query .= " and a.fin_service_order_no = $orderNo order by a.fin_service_order_no";
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

