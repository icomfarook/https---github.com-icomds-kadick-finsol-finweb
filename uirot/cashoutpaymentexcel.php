 <?php
 ERROR_REPORTING(E_ALL);
$data = json_decode(file_get_contents("php://input"));
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
include("excelfunctions.php");
require_once   '../common/PHPExcel/Classes/PHPExcel/IOFactory.php';
	$agentCode	= $_POST['agentCode'];
	$startDate	= $_POST['startDate'];	
	$endDate	= $_POST['endDate'];
	
	
$title = "KadickMoni";

if($startDate == null ){
	$startDate = date('Y-m-d');
}
if($endDate == null ){
		$endDate   =  date('Y-m-d');
}
$msg = "CashoutPayment Report For Date between $startDate and $endDate";
$objPHPExcel = new PHPExcel();

		if($agentCode == "ALL"){
			$heading = array("Receipt Id","Country","Party Code","Payment Account Id","Payment Amount","Payment Approve Amount");
							$headcount = 6;
		$query = "select p_receipt_id,concat (b.country_code,' - ',b.country_description ) as Country, ifNull(party_code,' - ') as party_code, ifNull(payment_account_id,' - ') as payment_account_id ,  ifNull(payment_amount,' - ') as payment_amount,ifNull(payment_approved_amount,' - ') as payment_approved_amount , ifNull(party_type,' - ') as party_type   , ifNull(payment_date,' - ') as payment_date, ifNull(payment_type,' - ') as payment_type  ,ifNull(payment_approved_date,' - ') as payment_approved_date ,ifNull(payment_reference_no,' - ') as payment_reference_no  ,ifNull(payment_reference_date,' - ') as payment_reference_date   ,ifNull(payment_source,' - ') as payment_source   ,ifNull(payment_cheque_no,' - ') as payment_cheque_no ,ifNull(payment_status,' - ') as payment_status ,ifNull(comments,' - ') as comments ,ifNull(approver_comments,' - ') as approver_comments ,ifNull(create_user,' - ') as create_user,ifNull(create_time,' - ') as create_time,ifNull(update_user,' - ') as update_user,ifNull(update_time,' - ') as update_time  from payment_receipt a,country b where a.country_id=b.country_id  and a.payment_source='C' and date(a.create_time) >= date('$startDate') and date(a.create_time) <= date('$endDate')";
		}
		else{
			$heading = array("Receipt Id","Country","Party Code","Payment Account Id","Payment Amount","Payment Approve Amount");
							$headcount = 6;
			$query = "select p_receipt_id,concat (b.country_code,' - ',b.country_description ) as Country, ifNull(party_code,' - ') as party_code, ifNull(payment_account_id,' - ') as payment_account_id ,  ifNull(payment_amount,' - ') as payment_amount,ifNull(payment_approved_amount,' - ') as payment_approved_amount , ifNull(party_type,' - ') as party_type   , ifNull(payment_date,' - ') as payment_date, ifNull(payment_type,' - ') as payment_type  ,ifNull(payment_approved_date,' - ') as payment_approved_date ,ifNull(payment_reference_no,' - ') as payment_reference_no  ,ifNull(payment_reference_date,' - ') as payment_reference_date   ,ifNull(payment_source,' - ') as payment_source   ,ifNull(payment_cheque_no,' - ') as payment_cheque_no ,ifNull(payment_status,' - ') as payment_status ,ifNull(comments,' - ') as comments ,ifNull(approver_comments,' - ') as approver_comments ,ifNull(create_user,' - ') as create_user,ifNull(create_time,' - ') as create_time,ifNull(update_user,' - ') as update_user,ifNull(update_time,' - ') as update_time   from payment_receipt a,country b where a.country_id=b.country_id  and a.party_code = '$agentCode' and a.payment_source='C' and date(a.create_time) >= date('$startDate') and date(a.create_time) <= date('$endDate')";
		}

			
			
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		
		//error_log($query);
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
