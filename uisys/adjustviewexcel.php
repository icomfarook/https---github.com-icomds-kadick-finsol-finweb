<?php
ERROR_REPORTING(E_ALL);
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
include("excelfunctions.php");
require_once   '../common/PHPExcel/Classes/PHPExcel/IOFactory.php';

	$data = json_decode(file_get_contents("php://input"));
	$id	= $_POST['id'];
	$creteria	= $_POST['creteria'];	
	$status	= $_POST['crestatus'];	
	$adjustmentDate	= $_POST['adjustmentDate'];
	$approvedDate	= $_POST['approvedDate'];
		
	$title = "KadickMoni";
	if($startDate == null ){
		$startDate = date('Y-m-d');
	}
	if($endDate == null ){
			$endDate   =  date('Y-m-d');
	}
	//error_log($ba);
	//error_log($endDate);
	$msg = "Adjustment Report";
	$objPHPExcel = new PHPExcel();

		if($creteria == "BI") {
			$query = "SELECT adjustment_id, party_code, if(party_type = 'A','Agent',if(party_type = 'C','Champion',if(party_type = 'P','Personal','Sub-Agent'))) as party_type, if(adjustment_type = 'P','+ve Adjustment','-ve Adjustment' ) as adjustment_type, adjustment_amount, ifNull(adjustment_approved_amount, '-') as adjustment_approved_amount, if(adjustment_status = 'E','Entered',if(adjustment_status = 'P','Pending',if(adjustment_status = 'R','Rejected',if(adjustment_status = 'F','Failed','Approved')))) as status,IFNULL(approver_comments,'-') as acomment,comments, ifNull(adjustment_approved_date, '-') as adjustment_approved_date , ifNull(adjustment_reference_no, '-') as adjustment_reference_no , ifNull(adjustment_reference_date, '-') as adjustment_reference_date FROM adjustment_receipt WHERE  adjustment_id = ".$id;
		}
		else if($creteria == "BS") {
			if($status == "ALL") {
				$query = "SELECT adjustment_id, party_code, if(party_type = 'A','Agent',if(party_type = 'C','Champion',if(party_type = 'P','Personal','Sub-Agent'))) as party_type, if(adjustment_type = 'P','+ve Adjustment','-ve Adjustment' ) as adjustment_type, adjustment_amount, ifNull(adjustment_approved_amount, '-') as adjustment_approved_amount, if(adjustment_status = 'E','Entered',if(adjustment_status = 'P','Pending',if(adjustment_status = 'R','Rejected',if(adjustment_status = 'F','Failed','Approved')))) as status,IFNULL(approver_comments,'-') as acomment,comments, ifNull(adjustment_approved_date, '-') as adjustment_approved_date , ifNull(adjustment_reference_no, '-') as adjustment_reference_no, ifNull(adjustment_reference_date, '-') as adjustment_reference_date  FROM adjustment_receipt ";
			}
			else{
				$query = "SELECT adjustment_id, party_code, if(party_type = 'A','Agent',if(party_type = 'C','Champion',if(party_type = 'P','Personal','Sub-Agent'))) as party_type, if(adjustment_type = 'P','+ve Adjustment','-ve Adjustment' ) as adjustment_type, adjustment_amount, ifNull(adjustment_approved_amount, '-') as adjustment_approved_amount, if(adjustment_status = 'E','Entered',if(adjustment_status = 'P','Pending',if(adjustment_status = 'R','Rejected',if(adjustment_status = 'F','Failed','Approved')))) as status,IFNULL(approver_comments,'-') as acomment,comments, ifNull(adjustment_approved_date, '-') as adjustment_approved_date  , ifNull(adjustment_reference_no, '-') as adjustment_reference_no , ifNull(adjustment_reference_date, '-') as adjustment_reference_date FROM adjustment_receipt WHERE adjustment_status = '$status'";
			}
		
		}
		else if($creteria == "BPD") {
			$query = "SELECT adjustment_id, party_code, if(party_type = 'A','Agent',if(party_type = 'C','Champion',if(party_type = 'P','Personal','Sub-Agent'))) as party_type, if(adjustment_type = 'P','+ve Adjustment','-ve Adjustment') as adjustment_type ,adjustment_amount, ifNull(adjustment_approved_amount, '-') as adjustment_approved_amount,if(adjustment_status = 'E','Entered',if(adjustment_status = 'P','Pending',if(adjustment_status = 'R','Rejected',if(adjustment_status = 'F','Failed','Approved')))) as status FROM adjustment_receipt,IFNULL(approver_comments,'-') as acomment,comments, ifNull(adjustment_approved_date, '-') as adjustment_approved_date , ifNull(adjustment_reference_no, '-') as adjustment_reference_no , ifNull(adjustment_reference_date, '-') as adjustment_reference_date WHERE date(adjustment_date) = '$adjustmentDate'";
		}
		else if($creteria == "BAD") {
			$query = "SELECT adjustment_id, party_code, if(party_type = 'A','Agent',if(party_type = 'C','Champion',if(party_type = 'P','Personal','Sub-Agent'))) as party_type, if(adjustment_type = 'P','+ve Adjustment','-ve Adjustment' ) as adjustment_type ,adjustment_amount, ifNull(adjustment_approved_amount, '-') as adjustment_approved_amount,if(adjustment_status = 'E','Entered',if(adjustment_status = 'P','Pending',if(adjustment_status = 'R','Rejected',if(adjustment_status = 'F','Failed','Approved')))) as status,IFNULL(approver_comments,'-') as acomment,comments, ifNull(adjustment_approved_date, '-') as adjustment_approved_date  , ifNull(adjustment_reference_no, '-') as adjustment_reference_no , ifNull(adjustment_reference_date, '-') as adjustment_reference_date FROM adjustment_receipt WHERE date(adjustment_approved_date) = '$approvedDate'";
		}
			
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$heading = array("Adjustment Id","Party Code", "Party Type", "Adjustment Type", "Adjustment Amount", "Adjustment Approved Amount", "Status","Approver Comments", "Comments","Approved Date", "Reference Number" , "Refernce Date");
		$headcount = 12;		 
		
		error_log($query);
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
