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
	$paymentstartDate	= $_POST['paymentstartDate'];
	$paymentendDate	= $_POST['paymentendDate'];
	$approvedstartDate	= $_POST['approvedstartDate'];
	$approvedendDate	= $_POST['approvedendDate'];
	$profile = $_SESSION['profile_id'];
	$title = "KadickMoni";
	
	$party_type = $_SESSION['party_type'];
	$party_code = $_SESSION['party_code'];
	if($party_type == 'C') {
		$table_name = 'champion_info';
		$code='champion_code';
	}
	if($party_type == 'A') {
		$table_name = 'agent_info';	
			$code='agent_code';
	}
	//error_log($ba);
	//error_log($endDate);
	if($creteria =="BI"){
		$msg = "Payment Report_ID_#".$id;
	}else if($creteria =="BS"){
		$msg = "Payment Report_Status_".$status;
	}else if($creteria =="BPD"){
		$msg = "Payment Report_Payment_".$paymentstartDate."_".$paymentendDate;
	}
	else if($creteria =="BAD"){
		$msg = "Payment Report_Approve_".$approvedstartDate."_".$approvedendDate;
	}
	$objPHPExcel = new PHPExcel();

	if($profile == 1 || $profile == 10 || $profile == 20 || $profile == 22 || $profile == 23 || $profile == 24 || $profile == 25 ) {
			if($creteria == "BI") {
				$query = "SELECT a.p_receipt_id, a.party_code,ifNull(concat(b.bank_name , ' - ',b.account_no),'-')  as bank_name, if(payment_type = 'AC','Acccount Transfer',if(a.payment_type = 'CA','Cash',if(a.payment_type = 'CH','Cheque','Other' ))) as payment_type,a.payment_amount,a.payment_reference_no,ifNull(DATE(a.payment_date),'-') as payment_date, ifNull(a.payment_approved_amount, '-') as payment_approved_amount,ifNull(DATE(a.payment_approved_date),'-')  as payment_approved_date, if(a.payment_status = 'E','Entered',if(a.payment_status = 'P','Pending',if(a.payment_status = 'R','Rejected',if(a.payment_status = 'F','Failed','Approved')))) as status,a.comments,ifNull(a.approver_comments, '-') as approver_comments,a.payment_reference_date FROM payment_receipt a left join bank_account b on a.payment_account_id = b.bank_account_id WHERE payment_source = 'M' and p_receipt_id = ".$id;
			}
			else if($creteria == "BS") {
				if($status == "ALL") {
					$query = "SELECT a.p_receipt_id, a.party_code,ifNull(concat(b.bank_name , ' - ',b.account_no),'-')  as bank_name, if(payment_type = 'AC','Acccount Transfer',if(a.payment_type = 'CA','Cash',if(a.payment_type = 'CH','Cheque','Other' ))) as payment_type,a.payment_amount,a.payment_reference_no,ifNull(DATE(a.payment_date),'-') as payment_date, ifNull(a.payment_approved_amount, '-') as payment_approved_amount,ifNull(DATE(a.payment_approved_date),'-')  as payment_approved_date, if(a.payment_status = 'E','Entered',if(a.payment_status = 'P','Pending',if(a.payment_status = 'R','Rejected',if(a.payment_status = 'F','Failed','Approved')))) as status,a.comments,ifNull(a.approver_comments, '-') as approver_comments,a.payment_reference_date  FROM payment_receipt  a left join bank_account b on a.payment_account_id = b.bank_account_id WHERE payment_source = 'M'";
				}
				else{
					$query = "SELECT a.p_receipt_id, a.party_code,ifNull(concat(b.bank_name , ' - ',b.account_no),'-')  as bank_name, if(payment_type = 'AC','Acccount Transfer',if(a.payment_type = 'CA','Cash',if(a.payment_type = 'CH','Cheque','Other' ))) as payment_type,a.payment_amount,a.payment_reference_no,ifNull(DATE(a.payment_date),'-') as payment_date, ifNull(a.payment_approved_amount, '-') as payment_approved_amount,ifNull(DATE(a.payment_approved_date),'-')  as payment_approved_date, if(a.payment_status = 'E','Entered',if(a.payment_status = 'P','Pending',if(a.payment_status = 'R','Rejected',if(a.payment_status = 'F','Failed','Approved')))) as status,a.comments,ifNull(a.approver_comments, '-') as approver_comments,a.payment_reference_date FROM payment_receipt  a left join bank_account b on a.payment_account_id = b.bank_account_id WHERE payment_source = 'M' and payment_status = '$status'";
				}
			
			}
			else if($creteria == "BPD") {
				$query = "SELECT a.p_receipt_id, a.party_code,ifNull(concat(b.bank_name , ' - ',b.account_no),'-')  as bank_name, if(payment_type = 'AC','Acccount Transfer',if(a.payment_type = 'CA','Cash',if(a.payment_type = 'CH','Cheque','Other' ))) as payment_type,a.payment_amount,a.payment_reference_no,ifNull(DATE(a.payment_date),'-') as payment_date, ifNull(a.payment_approved_amount, '-') as payment_approved_amount,ifNull(DATE(a.payment_approved_date),'-')  as payment_approved_date, if(a.payment_status = 'E','Entered',if(a.payment_status = 'P','Pending',if(a.payment_status = 'R','Rejected',if(a.payment_status = 'F','Failed','Approved')))) as status,a.comments,ifNull(a.approver_comments, '-') as approver_comments,a.payment_reference_date  FROM payment_receipt  a left join bank_account b on a.payment_account_id = b.bank_account_id WHERE payment_source = 'M' and date(payment_date) >= date('$paymentstartDate') and date(payment_date) <= date('$paymentendDate')";
			}
			else if($creteria == "BAD") {
				$query = "SELECT a.p_receipt_id, a.party_code,ifNull(concat(b.bank_name , ' - ',b.account_no),'-')  as bank_name, if(payment_type = 'AC','Acccount Transfer',if(a.payment_type = 'CA','Cash',if(a.payment_type = 'CH','Cheque','Other' ))) as payment_type,a.payment_amount,a.payment_reference_no,ifNull(DATE(a.payment_date),'-') as payment_date, ifNull(a.payment_approved_amount, '-') as payment_approved_amount,ifNull(DATE(a.payment_approved_date),'-')  as payment_approved_date, if(a.payment_status = 'E','Entered',if(a.payment_status = 'P','Pending',if(a.payment_status = 'R','Rejected',if(a.payment_status = 'F','Failed','Approved')))) as status,a.comments,ifNull(a.approver_comments, '-') as approver_comments,a.payment_reference_date FROM payment_receipt  a left join bank_account b on a.payment_account_id = b.bank_account_id WHERE payment_source = 'M' and date(payment_approved_date) >= date('$approvedstartDate') and date(payment_approved_date) <= date('$approvedendDate')";
			}
		}
		else {
			
			if($creteria == "BI") {
				$query = "SELECT a.p_receipt_id, a.party_code, if(a.party_type = 'A','Agent',if(a.party_type = 'C','Champion',if(a.party_type = 'P','Personal','SubAgent'))) as party_type, if(a.payment_type = 'AC','Acccount Transfer',if(a.payment_type = 'CA','Cash',if(a.payment_type = 'CH','Cheque','Other' ))) as payment_type,a.payment_amount, ifNull(a.payment_approved_amount, '-') as payment_approved_amount, if(a.payment_status = 'E','Entered',if(a.payment_status = 'P','Pending',if(a.payment_status = 'R','Rejected',if(a.payment_status = 'F','Failed','Approved')))) as status FROM payment_receipt a, $table_name b WHERE a.party_code = b.$code and a.payment_source = 'M' and a.p_receipt_id = ".$id;
			}
			else if($creteria == "BS") {
				if($status == "") {
					$query = "SELECT a.p_receipt_id, a.party_code, if(a.party_type = 'A','Agent',if(a.party_type = 'C','Champion',if(a.party_type = 'P','Personal','SubAgent'))) as party_type, if(a.payment_type = 'AC','Acccount Transfer',if(a.payment_type = 'CA','Cash',if(a.payment_type = 'CH','Cheque','Other' ))) as payment_type,a.payment_amount, ifNull(a.payment_approved_amount, '-') as payment_approved_amount, if(a.payment_status = 'E','Entered',if(a.payment_status = 'P','Pending',if(a.payment_status = 'R','Rejected',if(a.payment_status = 'F','Failed','Approved')))) as status FROM  payment_receipt a, $table_name b WHERE a.party_code = b.$code and a.payment_source = 'M'";
				}
				else{
					$query = "SELECT a.p_receipt_id, a.party_code, if(a.party_type = 'A','Agent',if(a.party_type = 'C','Champion',if(a.party_type = 'P','Personal','SubAgent'))) as party_type, if(a.payment_type = 'AC','Acccount Transfer',if(a.payment_type = 'CA','Cash',if(a.payment_type = 'CH','Cheque','Other' ))) as payment_type,a.payment_amount, ifNull(a.payment_approved_amount, '-') as payment_approved_amount, if(a.payment_status = 'E','Entered',if(a.payment_status = 'P','Pending',if(a.payment_status = 'R','Rejected',if(a.payment_status = 'F','Failed','Approved')))) as status FROM  payment_receipt a, $table_name b WHERE a.party_code = b.$code and a.payment_source = 'M' and a.payment_status = '$status'";
				}
			
			}
			else if($creteria == "BPD") {
				$query = "SELECT a.p_receipt_id, a.party_code, if(a.party_type = 'A','Agent',if(a.party_type = 'C','Champion',if(a.party_type = 'P','Personal','SubAgent'))) as party_type, if(a.payment_type = 'AC','Acccount Transfer',if(a.payment_type = 'CA','Cash',if(a.payment_type = 'CH','Cheque','Other' ))) as payment_type,a.payment_amount, ifNull(a.payment_approved_amount, '-') as payment_approved_amount, if(a.payment_status = 'E','Entered',if(a.payment_status = 'P','Pending',if(a.payment_status = 'R','Rejected',if(a.payment_status = 'F','Failed','Approved')))) as status FROM payment_receipt a, $table_name b WHERE a.party_code = b.$code and a.payment_source = 'M' and date(payment_date) >= date('$paymentstartDate') and date(payment_date) <= date('$paymentendDate')";
			}
			else if($creteria == "BAD") {
				$query = "SELECT a.p_receipt_id, a.party_code, if(a.party_type = 'A','Agent',if(a.party_type = 'C','Champion',if(a.party_type = 'P','Personal','SubAgent'))) as party_type, if(a.payment_type = 'AC','Acccount Transfer',if(a.payment_type = 'CA','Cash',if(a.payment_type = 'CH','Cheque','Other' ))) as payment_type,a.payment_amount, ifNull(a.payment_approved_amount, '-') as payment_approved_amount, if(a.payment_status = 'E','Entered',if(a.payment_status = 'P','Pending',if(a.payment_status = 'R','Rejected',if(a.payment_status = 'F','Failed','Approved')))) as status FROM payment_receipt a, $table_name b WHERE a.party_code = b.$code and a.payment_source = 'M' and date(payment_approved_date) >= date('$approvedstartDate') and date(payment_approved_date) <= date('$approvedendDate')";
			}
		}
			
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$heading = array("Payment Id", "Party Code", "Bank Name", "Payment Type", "Payment Amount", "Reference No", "Payment Date", "Appoved Amount", "Approved Date", "Status", "Comments", "Approver Comments");
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
