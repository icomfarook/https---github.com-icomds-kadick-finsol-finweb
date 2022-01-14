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
		
	$endDate	=  $_POST['endDate'];	
	$startDate		=  $_POST['startDate'];
	$status	=  $_POST['ApiType'];
	$creteria 	= $_POST['creteria'];
	$profile_id = $_SESSION['profile_id'];
	
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
//error_log($ba);
//error_log($endDate);
$msg = "POSVAS API  Report Date Between $startDate and $endDate ";
$objPHPExcel = new PHPExcel();

			
			
		if($status == "ALL"){
				$query = "(select date(date_time) as Date, 'Transaction Request' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14000 and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all (select date(date_time) as Date, 'Transaction Response' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14001 and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all
				  (select date(date_time) as Date, 'Transaction Response - Success' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14001 and message like '%\"response\":\"00\"%' and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all 
				  (select date(date_time) as Date, 'Transaction Response - Not Prepped' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14001 and message like '%\"response\":\"05\"%' and date(date_time) between '$startDate' and '$endDate' group by date(date_time))  union all 
				  (select date(date_time) as Date, 'Transaction Response - Exception' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14001 and message like '%Transaction Exception%' and date(date_time) between '$startDate' and '$endDate' group by date(date_time))  union all
				  (select date(date_time) as Date, 'Prep Key Request' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14004 and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all
				(select date(date_time) as Date, 'Prep Key Response' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14005 and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all
				(select date(date_time) as Date, 'Prep Key Response - Success' as API_NAME, count(*)  as count from mpos_debug_dump where pic_point = 14005 and message like '%Prep Successful%' and date(date_time) between '$startDate' and '$endDate' group by date(date_time))  union all
				(select date(date_time) as Date, 'Call Home Request' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14002 and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all

				(select date(date_time) as Date, 'Call Home Response' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14003 and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all

				(select date(date_time) as Date, 'Call Home Response - Success' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14003 and message like '%\"response\":\"00\"%' and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) order by Date, API_NAME";
			}
			  if ($status == "T"){
				  $query = "(select date(date_time) as Date, 'Transaction Request' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14000 and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all (select date(date_time) as Date, 'Transaction Response' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14001 and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all
				  (select date(date_time) as Date, 'Transaction Response - Success' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14001 and (message like '%\"response\":\"00\"%') and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all 
				  (select date(date_time) as Date, 'Transaction Response - Not Prepped' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14001 and message like '%\"response\":\"05\"%' and date(date_time) between '$startDate' and '$endDate' group by date(date_time))  union all 
				  (select date(date_time) as Date, 'Transaction Response - Exception' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14001 and message like '%Transaction Exception%' and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) order by Date, API_NAME";
			  }  if($status == "P"){
				  $query = "(select date(date_time) as Date, 'Prep Key Request' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14004 and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all
				(select date(date_time) as Date, 'Prep Key Response' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14005 and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all
				(select date(date_time) as Date, 'Prep Key Response - Success' as API_NAME, count(*)  as count from mpos_debug_dump where pic_point = 14005 and message like '%Prep Successful%' and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) order by Date, API_NAME ";
				  
			  }if($status == "C") {
				  $query = "(select date(date_time) as Date, 'Call Home Request' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14002 and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all

				(select date(date_time) as Date, 'Call Home Response' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14003 and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all

				(select date(date_time) as Date, 'Call Home Response - Success' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14003 and message like '%\"response\":\"00\"%' and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) order by Date, API_NAME";

			  }
			  
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();         
		}
		
		
	
		$heading = array("Date","API Type","Count");
		$headcount = 3;
		heading($heading,$objPHPExcel,$headcount);
		$i = 2;						
		while ($row = mysqli_fetch_array($result)) {

		generateExcel ($i, $row,$objPHPExcel,$headcount);
			$i++;
		}
			$lastrow = $objPHPExcel->getActiveSheet()->getHighestRow();			
			$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$lastrow)
			->getNumberFormat()
			->setFormatCode('0');
		
		$objPHPExcel->getActiveSheet()
					->getColumnDimension('F')
					->setAutoSize(true);
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
		
			function isJson($string) {
	json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}
	

?>
