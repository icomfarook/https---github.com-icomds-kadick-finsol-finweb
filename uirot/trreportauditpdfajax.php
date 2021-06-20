<?php
    ini_set("memory_limit",-1);
	$data = json_decode(file_get_contents("php://input"));
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
	require('../tcpdf/tcpdf.php'); 
	date_default_timezone_set("Africa/Lagos");
	ERROR_REPORTING(E_ALL);


	$partyCode = $_POST['partyCode'];
	//$AgentCode = $_POST['AgentCode'];
	$partyType = $_POST['partyType'];
	if($partyType == 'MA'){
		$partyType='Agent';
		error_log("partyTYpe ==".$partyType); 
		
	}
	if($partyType == 'SA'){
		$partyType='SubAgent';
		error_log("partyTYpe ==".$partyType); 
		
	}
	if($partyType == 'Champion'){
		$partyType='Champion';
		error_log("partyTYpe ==".$partyType); 
		
	}
	$startDate = $_POST['startDate'];
	$endDate = $_POST['endDate'];
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
	error_log("party_code ==".$partyCode);
	
	
	$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    	$obj_pdf->SetCreator(PDF_CREATOR);  
    	$obj_pdf->SetTitle("Transaction Audit Report");  
    	$obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
    	$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
    	$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
    	$obj_pdf->SetDefaultMonospacedFont('helvetica');  
    	$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
    	$obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);  
    	$obj_pdf->setPrintHeader(false);  
    	$obj_pdf->setPrintFooter(false);  
    	$obj_pdf->SetAutoPageBreak(TRUE, 10); 
		$obj_pdf->setCellPadding(2);
		//$obj_pdf->setCellPaddings( 1, 3, 3,1);
		$obj_pdf->Write(0, '   ', '*', 0, 'C', TRUE, 0, false, false, 0) ;



		
	$obj_pdf->SetFont('helvetica', '', 9);
$obj_pdf->AddPage();
$heading = "<h1 style='text-align:justify;margin-top: 500px;'>Transaction Audit Report</h1>";
   $obj_pdf->Image('../common/images/km_logo.png', 16, 10, 30, 10, 'PNG', '', '', true, 100, '', false, false, '', false, false, false);
   //$obj_pdf->SetXY(20,30);
   $obj_pdf->MultiCell(40,5,'Party Type: '.$partyType.'',1,'L',0,0,'',35,true,0,false,true,40,'T');
   $obj_pdf->MultiCell(50,5,'Party Code: '.$partyCode.'',1,'L',0,0,'','',true,0,false,true,40,'T');
   $obj_pdf->MultiCell(40,5,'Start Date: '.$startDate.'',1,'L',0,0,'','',true,0,false,true,40,'T');
   $obj_pdf->MultiCell(40,5,'End Date: '.$endDate.'',1,'L',0,0,'','',true,0,false,true,40,'T');
	$date_report = "Report Generated at ".date('Y-m-d h:i:s A');
	$obj_pdf->writeHTMLCell(150,2, 76, 1, $heading, 0, 1, 0, true, '', true);
	

	$obj_pdf->writeHTMLCell(170, 12, 130, 20, $date_report, 0, 1, 0, true, '', true);
	$obj_pdf->Ln();
	if($partyType == "Champion") {
				$query = "select b.champion_wallet_audit_id as id, b.champion_code as party_code, concat(c.acc_trans_description, '-', a.acc_trans_type_code) as trans_code, a.description, a.amount as journal_amount, b.old_available_balance, b.new_available_balance,ifNull(b.old_last_tx_no,' - ') as old_last_tx_no,ifNull(b.new_last_tx_no,' - ') as  new_last_tx_no,ifNull(b.old_last_tx_amount,' - ') as  old_last_tx_amount, ifNull(b.new_last_tx_amount,' - ') as new_last_tx_amount,ifNull(b.old_last_tx_date,' - ') as  old_last_tx_date, b.new_last_tx_date,b.create_time from acc_trans_type c, champion_wallet_audit b inner join journal_entry a on b.new_last_tx_no = a.journal_entry_id where date(b.create_time) >= date('$startDate') and date(b.create_time) <= date('$endDate') and b.champion_code = '$partyCode' and a.acc_trans_type_code = c.acc_trans_type_code order by b.create_time";
			}
			if($partyType == "Personal") {
				$query = "select b.personal_wallet_audit_id as id, b.personal_code, concat(c.acc_trans_description, '-', a.acc_trans_type_code) as trans_code, a.description, a.amount as journal_amount, b.old_available_balance, b.new_available_balance, b.old_last_tx_no, b.new_last_tx_no, b.old_last_tx_amount, b.new_last_tx_amount, b.old_last_tx_date, b.new_last_tx_date,b.create_time from acc_trans_type c, personal_wallet_audit b inner join journal_entry a on b.new_last_tx_no = a.journal_entry_id where date(b.create_time) >= date('$startDate') and date(b.create_time) <= date('$endDate')  and b.personal_code = '$partyCode' and a.acc_trans_type_code = c.acc_trans_type_code order by b.create_time";
			}
			if($partyType == "Agent" || $partyType == "SubAgent") {
				$query = "select b.agent_wallet_audit_id as id, concat(f.agent_name,'-','[',f.agent_code,']') as party_code, concat(c.acc_trans_description, '-', a.acc_trans_type_code) as trans_code, a.description, a.amount as journal_amount, b.old_available_balance, b.new_available_balance,ifNull(b.old_last_tx_no,' - ') as  old_last_tx_no,ifNull(b.new_last_tx_no,' - ') as  new_last_tx_no,ifNull(b.old_last_tx_amount,' - ') as  old_last_tx_amount, ifNull(b.new_last_tx_amount,' - ') as new_last_tx_amount,ifNull(b.old_last_tx_date,' - ') as  old_last_tx_date, b.new_last_tx_date,b.create_time from agent_info f,acc_trans_type c, agent_wallet_audit b inner join journal_entry a on b.new_last_tx_no = a.journal_entry_id  where    b.agent_code = f.agent_code and date(b.create_time) >= date('$startDate') and date(b.create_time) <= date('$endDate')  and b.agent_code = '$partyCode' and a.acc_trans_type_code = c.acc_trans_type_code order by b.create_time";
				if($partyType == "MA") {
					$partyType = "Agent";
				}
				if($partyType == "SA") {
					$partyType = "Sub";
				}
			}
			$msg = "Transaction_Audit_Report ".$partyCode." For Date between ".$startDate." and  ".$endDate."";
		
		
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query) or die("Error: " . mysqli_error($con));;	
	
		$html = "<html>
<head><title style='display:none'>Report</title><link rel='stylesheet' href='css/popup.css' type='text/css' media='screen' /><link rel='stylesheet' type='text/css' href='css/default1.css'/><link rel='stylesheet' type='text/css' href='css/layout.css' media='screen' />'
		<style>#footer {position: absolute;bottom: 0;width: 100%;height: 100px;}</style><span class='header'><p style='float:right;margin-top:0.4px'></p>
		<h2 style='text-align:center;margin-top:30px'>PDF Report</h2></span></head><body><br>
		<img style='float:left' id='myimg' src='../common/images/km_logo.png' width='100px' height='40px'/><style>th{color:red;width:60px;text-align:center;border:0px solid black;padding:4%}</style><style>td{text-align:center;width:60px;border:0px solid black;padding:4%}</style>
		<table style='font-family:Arial,Helvetica,sans-serif;width:100%;font-size:13px !important;border:none;width:500px'; id='table'>
<tr style='width: 100%;'>
									<th >Id</th>
									<th style='width:150px'>Party Code</th>
									<th>Transaction #</th>
									<th>Description</th>
									<th>Amount</th>
									<th>Old  Balance</th>
									<th>New Balance</th>
									<th>Date Time</th>
								</tr>";
						
	while ($row = mysqli_fetch_array($result)) {
			$html .= "<tr style='width: 100%;'>
						<td>".$row['id']."</td>
							<td style='width:150px'>".$row['party_code']."</td>
							<td>".$row['new_last_tx_no']."</td>
							<td>".$row['trans_code']."</td>
							<td>".$row['journal_amount']."</td>
							<td>".$row['old_available_balance']."</td>
							<td>".$row['new_available_balance']."</td>		
							<td>".$row['create_time']."</td>	
							</tr>";
							
		}
$html .= '</tbody></table>
</body>
</html>';
header('Content-type: application/pdf');
header('Content-Disposition: attachment; filename="file.pdf"');
$obj_pdf->writeHTML($html, true, 0, true, 0);
$obj_pdf->lastPage();
$obj_pdf->Output($msg.'.pdf', 'D');
?>
	
