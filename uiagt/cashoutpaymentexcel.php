<?php
ERROR_REPORTING(E_ALL);
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
include("excelfunctions.php");
require_once   '../common/PHPExcel/Classes/PHPExcel/IOFactory.php';

	$data = json_decode(file_get_contents("php://input"));
	$startDate	= $_POST['startDate'];	
	$endDate	= $_POST['endDate'];
		
	$title = "KadickMoni";
	if($startDate == null ){
		$startDate = date('Y-m-d');
	}
	if($endDate == null ){
			$endDate   =  date('Y-m-d');
	}
	//error_log($ba);
	//error_log($endDate);
	$msg = "Cashout Payment Report For Date between $startDate and $endDate";
	$objPHPExcel = new PHPExcel();

		$heading = array("Receipt Id","Party Code","Payment Amount","Approved Amount","Reference No","Date Time");
		$headcount = 6;
		$query = "select p_receipt_id, ifNull(party_code,' - ') as party_code, ifNull(payment_amount,' - ') as payment_amount, ifNull(payment_approved_amount,' - ') as payment_approved_amount, ifNull(payment_reference_no,' - ') as payment_reference_no, ifNull(create_time,' - ') as create_time from payment_receipt where party_code = '".$_SESSION['party_code']."' and payment_source = 'C' and date(create_time) >= date('$startDate') and date(create_time) <= date('$endDate')";
					
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
