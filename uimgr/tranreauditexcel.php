<?php
ERROR_REPORTING(E_ALL);
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
include("excelfunctions.php");
require_once   '../common/PHPExcel/Classes/PHPExcel/IOFactory.php';

	$data = json_decode(file_get_contents("php://input"));
	$partyCode	= $_POST['partyCode'];	
	$partyType	= $_POST['partyType'];
		$startDate =  $_POST['startDate'];
		$endDate = $_POST['endDate'];
		$startDate = date("Y-m-d", strtotime($startDate));
		$endDate = date("Y-m-d", strtotime($endDate));
	date_default_timezone_set('Asia/Kolkata'); 
	$today = date("Y-m-d H:i:s"); 
	
		
	$title = "KadickMoni";
	$msg = "Transaction Audit Report  $partyCode - $auditDate - $today";
	$objPHPExcel = new PHPExcel();

		if($partyType == "C") {
				$query = "select b.champion_wallet_audit_id as id, b.champion_code as party_code, concat(c.acc_trans_description, '-', a.acc_trans_type_code) as trans_code, a.description, a.amount as journal_amount, b.old_available_balance, b.new_available_balance,ifNull(b.old_last_tx_no,' - ') as old_last_tx_no,ifNull(b.new_last_tx_no,' - ') as  new_last_tx_no,ifNull(b.old_last_tx_amount,' - ') as  old_last_tx_amount, ifNull(b.new_last_tx_amount,' - ') as new_last_tx_amount,ifNull(b.old_last_tx_date,' - ') as  old_last_tx_date, b.new_last_tx_date from acc_trans_type c, champion_wallet_audit b inner join journal_entry a on b.new_last_tx_no = a.journal_entry_id where date(b.create_time) >= date('$startDate') and date(b.create_time) <= date('$endDate') and b.champion_code = '$partyCode' and a.acc_trans_type_code = c.acc_trans_type_code order by b.create_time";
			}
			if($partyType == "P") {
				$query = "select b.personal_wallet_audit_id, b.personal_code, concat(c.acc_trans_description, '-', a.acc_trans_type_code) as trans_code, a.description, a.amount as journal_amount, b.old_available_balance, b.new_available_balance, b.old_last_tx_no, b.new_last_tx_no, b.old_last_tx_amount, b.new_last_tx_amount, b.old_last_tx_date, b.new_last_tx_date from acc_trans_type c, personal_wallet_audit b inner join journal_entry a on b.new_last_tx_no = a.journal_entry_id where date(b.create_time) >= date('$startDate') and date(b.create_time) <= date('$endDate')  and b.personal_code = '$partyCode' and a.acc_trans_type_code = c.acc_trans_type_code order by b.create_time";
			}
			if($partyType == "MA" || $partyType == "SA") {
				$query = "select b.agent_wallet_audit_id as id, b.agent_code as party_code, concat(c.acc_trans_description, '-', a.acc_trans_type_code) as trans_code, a.description, a.amount as journal_amount, b.old_available_balance, b.new_available_balance,ifNull(b.old_last_tx_no,' - ') as  old_last_tx_no,ifNull(b.new_last_tx_no,' - ') as  new_last_tx_no,ifNull(b.old_last_tx_amount,' - ') as  old_last_tx_amount, ifNull(b.new_last_tx_amount,' - ') as new_last_tx_amount,ifNull(b.old_last_tx_date,' - ') as  old_last_tx_date, b.new_last_tx_date from acc_trans_type c, agent_wallet_audit b inner join journal_entry a on b.new_last_tx_no = a.journal_entry_id where date(b.create_time) >= date('$startDate') and date(b.create_time) <= date('$endDate')  and b.agent_code = '$partyCode' and a.acc_trans_type_code = c.acc_trans_type_code order by b.create_time";
				if($partyType == "MA") {
					$partyType = "A";
				}
				if($partyType == "SA") {
					$partyType = "S";
				}
			}
			
		
		
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();         
		}
		
		error_log($query);
		$heading = array("Id","Party Code", " Transaction Code", "Description", "Journal Amount", "Old Available Balance", "New Available Balance", "Old Last Tx Number", "New Last Tx Number", "Old Last Tx amount" ,"New Last Tx amount", "Old Last Tx Date", "New Last Tx Date");
				$headcount = 13;
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
