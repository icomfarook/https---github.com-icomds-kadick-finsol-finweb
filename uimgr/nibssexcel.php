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
		
	$partyCode	=  $_POST['partyCode'];	
	$startDate		=  $_POST['startDate'];
	$status	=  $_POST['ApiType'];
	$creteria 	= $_POST['creteria'];
	$profile_id = $_SESSION['profile_id'];
	
	$startDate = date("Y-m-d", strtotime($startDate));
	
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
$msg = "Detailed NIBSS  Report For Date  $startDate ";
$objPHPExcel = new PHPExcel();

		$topartyCode = $data->topartyCode;
		if($creteria == "TP") {
			$partyType = substr($topartyCode, 0, 1, "UTF-8");
			$partyCode = $topartyCode;
		}
		
			if($profile_id == 1){	
			  if ($status == "T"){
				  $query = "select b.party_code, a.order_no, b.message,b.pic_point, b.date_time from emv_request_detail a, mpos_debug_dump b where a.emv_tx_id = b.emv_tx_id and b.pic_point in (14000, 14001) and b.party_code = '$partyCode' and date(b.date_time) = '$startDate' order by a.order_no, b.date_time";
			  } else if($status == "P"){
				  $query = "select party_code,('-') as order_no,  message ,pic_point, date_time from mpos_debug_dump where party_code = '$partyCode' and date(date_time) = '$startDate' and pic_point in (14004, 14005) order by date_time";
				  
			  }else {
				  $query = "select party_code, ('-') as order_no, message,pic_point, date_time from mpos_debug_dump where party_code = '$partyCode' and date(date_time) = '$startDate' and pic_point in (14002, 14003) order by date_time";
			  }
			  
					
					}else{
						 if ($status == "T"){
						  $query = "select b.party_code, a.order_no, b.message,b.pic_point, b.date_time from emv_request_detail a, mpos_debug_dump b where a.emv_tx_id = b.emv_tx_id and b.pic_point in (14000, 14001) and party_code = '$partyCode' and date(date_time) = '$startDate' order by a.order_no, b.date_time";
					  } if($status == "P"){
						  $query = "select party_code, ('-') as order_no, message,pic_point, date_time from mpos_debug_dump where party_code = '$partyCode' and date(date_time) = '$startDate' and pic_point in (14004, 14005) order by date_time;";
													  
					  }if ($status == "C"){
						  $query = "select party_code, ('-') as order_no, message,pic_point, date_time from mpos_debug_dump where party_code = '$partyCode' and date(date_time) = '$startDate' and pic_point in (14002, 14003) order by date_time";
					  }
					}	
			
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();         
		}
		
		
	
		$heading = array("Party Code","Order No","Message","Pic Point", "Date Time");
		$headcount = 5;
		heading($heading,$objPHPExcel,$headcount);
		$i = 2;						
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$message = $row['message'];
			if($profile_id ==10){
				
				if(isJson($message) == 1){				
				$json_arr = json_decode($message, true);
				unset($json_arr['pin_key']);				
				$message = json_encode($json_arr);				
			}
			}
			$row['2'] = $message;
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
