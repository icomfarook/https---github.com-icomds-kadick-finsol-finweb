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
$state	=$_POST['state'];
$localgovernment	= $_POST['localgovernment'];	

$startDate		=   $_POST['startDate'];
$endDate	=  $_POST['endDate'];
$ba	=   $_POST['ba'];
$userName = $_SESSION['user_name'];
$msg = "";
$startDate = date("Y-m-d", strtotime($startDate));
$endDate = date("Y-m-d", strtotime($endDate));
$servername = SERVER_SHORT_NAME;
$title = "";
$title = "KadickMoni";
if($startDate == null ){
	$startDate = date('Y-m-d');
}
if($endDate == null ){
		$endDate   =  date('Y-m-d');
}
//error_log($ba);
//error_log($endDate);
$objPHPExcel = new PHPExcel();
	if($ba == "aw") {
		$heading = array("Date","Transaction Type", "Request Amount","Total Amount");
		$msg = "Transaction Per Service Summary Report date between $startDate to $endDate";
			if($state == "ALL") {
				if($localgovernment=='ALL'){
					$query ="select date(b.date_time) as date, concat(c.feature_code, ' - ', c.feature_description) as transaction_type, sum(a.request_amount) as total_request_amount, sum(a.total_amount) as total_amount from fin_request a, fin_service_order b, service_feature c where a.order_no = b.fin_service_order_no and b.service_feature_code = c.feature_code and date(b.date_time) between '$startDate' and '$endDate' group by date(b.date_time), transaction_type order by date(b.date_time), transaction_type";
				}
				else {
					$query ="select date(b.date_time) as date, concat(c.feature_code, ' - ', c.feature_description) as transaction_type, sum(a.request_amount) as total_request_amount, sum(a.total_amount) as total_amount from fin_request a, fin_service_order b, service_feature c where a.order_no = b.fin_service_order_no and b.service_feature_code = c.feature_code and a.local_govt_id = $localgovernment and date(b.date_time) between '$startDate' and '$endDate'  group by date(b.date_time), transaction_type order by date(b.date_time), transaction_type";
				}
			}
			else {
				if($localgovernment=='ALL'){
					$query ="select date(b.date_time) as date, concat(c.feature_code, ' - ', c.feature_description) as transaction_type, sum(a.request_amount) as total_request_amount, sum(a.total_amount) as total_amount from fin_request a, fin_service_order b, service_feature c where a.order_no = b.fin_service_order_no and b.service_feature_code = c.feature_code and a.state_id = $state and date(b.date_time) between '$startDate' and '$endDate'  group by date(b.date_time), transaction_type order by date(b.date_time), transaction_type";
				}
				else {
					$query ="select date(b.date_time) as date, concat(c.feature_code, ' - ', c.feature_description) as transaction_type, sum(a.request_amount) as total_request_amount, sum(a.total_amount) as total_amount from fin_request a, fin_service_order b, service_feature c where a.order_no = b.fin_service_order_no and b.service_feature_code = c.feature_code and a.state_id = $state and a.local_govt_id = $localgovernment and date(b.date_time) between '$startDate' and '$endDate'  group by date(b.date_time), transaction_type order by date(b.date_time), transaction_type";
				}		
			}
		}
		if($ba == "cw") {
			$heading = array("User","Customer","Date","Transaction Type", "Request Amount","Total Amount");
			$msg = "Transaction Per Service Detail Report date between $startDate to $endDate";
			if($state == "ALL") {
				if($localgovernment=='ALL'){
					$query ="SELECT concat(d.first_name, ' ', d.last_name,' (', d.user_name,') ') as userName, b.customer_name,  date(b.date_time) as date, concat(c.feature_code, ' - ', c.feature_description) as transaction_type, sum(a.request_amount) as total_request_amount, sum(a.total_amount) as total_amount from fin_request a, fin_service_order b, service_feature c, user d where  d.user_id = a.user_id and a.order_no = b.fin_service_order_no and b.service_feature_code = c.feature_code and date(b.date_time) between '$startDate' and '$endDate' group by date(b.date_time), transaction_type order by date(b.date_time), transaction_type";
				}
				else {
					$query ="SELECT concat(d.first_name, ' ', d.last_name,' (', d.user_name,') ') as userName, b.customer_name, date(b.date_time) as date, concat(c.feature_code, ' - ', c.feature_description) as transaction_type, sum(a.request_amount) as total_request_amount, sum(a.total_amount) as total_amount from fin_request a, fin_service_order b, service_feature c, user d where  d.user_id = a.user_id and a.order_no = b.fin_service_order_no and b.service_feature_code = c.feature_code and a.local_govt_id = $localgovernment and date(b.date_time) between '$startDate' and '$endDate'  group by date(b.date_time), transaction_type order by date(b.date_time), transaction_type";
				}
			}
			else {
				if($localgovernment=='ALL'){
					$query ="SELECT concat(d.first_name, ' ', d.last_name,' (', d.user_name,') ') as userName, b.customer_name, date(b.date_time) as date, concat(c.feature_code, ' - ', c.feature_description) as transaction_type, sum(a.request_amount) as total_request_amount, sum(a.total_amount) as total_amount from fin_request a, fin_service_order b, service_feature c, user d where  d.user_id = a.user_id and a.order_no = b.fin_service_order_no and b.service_feature_code = c.feature_code and a.state_id = $state and date(b.date_time) between '$startDate' and '$endDate'  group by date(b.date_time), transaction_type order by date(b.date_time), transaction_type";
				}
				else {
					$query ="SELECT concat(d.first_name, ' ', d.last_name,' (', d.user_name,') ') as userName, b.customer_name, date(b.date_time) as date, concat(c.feature_code, ' - ', c.feature_description) as transaction_type, sum(a.request_amount) as total_request_amount, sum(a.total_amount) as total_amount from fin_request a, fin_service_order b, service_feature c, user d where  d.user_id = a.user_id and a.order_no = b.fin_service_order_no and b.service_feature_code = c.feature_code and a.state_id = $state and a.local_govt_id = $localgovernment and date(b.date_time) between '$startDate' and '$endDate'  group by date(b.date_time), transaction_type order by date(b.date_time), transaction_type";
				}		
			}
		}
		//error_log($query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		
		////error_log($query);
		heading($heading,$objPHPExcel,8);
		$i = 2;						
		while ($row = mysqli_fetch_array($result))	{
			generateExcel ($i, $row,$objPHPExcel,8);
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
