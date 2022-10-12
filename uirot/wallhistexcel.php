<?php
ERROR_REPORTING(E_ALL);
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
//error_log("s");
include("excelfunctions.php");
require_once   '../common/PHPExcel/Classes/PHPExcel/IOFactory.php';

	
	$startDate	= $_POST['startDate'];	
	$endDate	= $_POST['endDate'];
	$Walltype   = $_POST['Walltype'];
	$partyType   = $_POST['partyType'];
	$partyCode   = $_POST['partyCode'];
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
	if($partyType == "MA") {
		$partyType = "A";
	}
	if($partyType == "SA") {
		$partyType = "S";
	}
   error_log("Walltype".$Walltype);
	error_log("partytype".$partyType);
	
	
$title = "KadickMoni";
if($startDate == null ){
	$startDate = date('Y-m-d');
}
if($endDate == null ){
	$endDate   =  date('Y-m-d');
}


$msg = "Wallet Balance History  Report For Date between $startDate and $endDate";
$objPHPExcel = new PHPExcel();
	if($Walltype == "ALL"){
				if($partyType == "ALL"){
					$query = "select a.balance_history_id, a.date_time,if(a.wallet_type='C','C - Commission Wallet', if(a.wallet_type='M','M - Main Wallet', if(a.wallet_type='O','O-Other Wallet','-'))) as wallet_type,  if(a.party_type='A','A-Agent',if(a.party_type='C','C-Champion',if(a.party_type='P','P-Personal', if(a.party_type='S','S-Sub-Agent',if(a.party_type='O','O-Others','-'))))) as party_type, if(a.party_type='A',(select concat(agent_code,'-',agent_name) from agent_info where agent_code=a.party_code),(select concat(champion_code,'-',champion_name) from champion_info where champion_code=a.party_code)) as party_code,ifnull(a.available_balance,'-') as available_balance, ifnull(a.current_balance,'-') as current_balance,ifnull(a.credit_limit,'-') as credit_limit, ifnull(a.daily_limit,'-') as daily_limit, ifnull(a.advance_amount,'-') as advance_amount, ifnull(a.minimum_balance,'-') as minimum_balance, ifnull(a.previous_current_balance,'-') as previous_current_balance, ifnull(a.uncleared_balance,'-') as uncleared_balance, ifnull(a.last_tx_no,'-') as last_tx_no, ifnull(a.last_tx_amount,'-') as last_tx_amount, ifnull(a.last_tx_date,'-') as last_tx_date, ifnull(a.active,'-') as active, ifnull(a.block_status,'-') as block_status, ifnull(a.block_date,'-') as block_date, ifnull(a.block_reason_id,'-') as block_reason_id FROM wallet_balance_history a left join agent_info b on a.party_code = b.agent_code ,champion_info c where  date(date_time) between '$startDate' and '$endDate' group by a.wallet_type, a.party_type, a.party_code, a.balance_history_id, a.credit_limit, a.daily_limit, a.advance_amount, a.available_balance, a.current_balance, a.minimum_balance, a.previous_current_balance, a.uncleared_balance, a.last_tx_no, a.last_tx_amount, a.last_tx_date, a.active, a.block_status, a.block_date, a.block_reason_id order by date_time";
				}
				else  if($partyCode !== "ALL" ){
					//error_log("Not Equal Conditon =".$partyCode);
					$query = "select a.balance_history_id, a.date_time,if(a.wallet_type='C','C - Commission Wallet', if(a.wallet_type='M','M - Main Wallet', if(a.wallet_type='O','O-Other Wallet','-'))) as wallet_type,  if(a.party_type='A','A-Agent',if(a.party_type='C','C-Champion',if(a.party_type='P','P-Personal', if(a.party_type='S','S-Sub-Agent',if(a.party_type='O','O-Others','-'))))) as party_type, if(a.party_type='A',(select concat(agent_code,'-',agent_name) from agent_info where agent_code=a.party_code),(select concat(champion_code,'-',champion_name) from champion_info where champion_code=a.party_code)) as party_code,ifnull(a.available_balance,'-') as available_balance, ifnull(a.current_balance,'-') as current_balance, ifnull(a.credit_limit,'-') as credit_limit, ifnull(a.daily_limit,'-') as daily_limit, ifnull(a.advance_amount,'-') as advance_amount,  ifnull(a.minimum_balance,'-') as minimum_balance, ifnull(a.previous_current_balance,'-') as previous_current_balance, ifnull(a.uncleared_balance,'-') as uncleared_balance, ifnull(a.last_tx_no,'-') as last_tx_no, ifnull(a.last_tx_amount,'-') as last_tx_amount, ifnull(a.last_tx_date,'-') as last_tx_date, ifnull(a.active,'-') as active, ifnull(a.block_status,'-') as block_status, ifnull(a.block_date,'-') as block_date, ifnull(a.block_reason_id,'-') as block_reason_id FROM wallet_balance_history a left join agent_info b on a.party_code = b.agent_code ,champion_info c where  party_type='$partyType' and party_code='$partyCode' and date(date_time) between '$startDate' and '$endDate' group by a.wallet_type, a.party_type, a.party_code, a.balance_history_id, a.credit_limit, a.daily_limit,a.advance_amount,a.available_balance,a.current_balance,a.minimum_balance, a.previous_current_balance, a.uncleared_balance, a.last_tx_no, a.last_tx_amount, a.last_tx_date, a.active, a.block_status, a.block_date, a.block_reason_id order by date_time";
				}
				else{
					$query = "select a.balance_history_id, a.date_time,if(a.wallet_type='C','C - Commission Wallet', if(a.wallet_type='M','M - Main Wallet', if(a.wallet_type='O','O-Other Wallet','-'))) as wallet_type,  if(a.party_type='A','A-Agent',if(a.party_type='C','C-Champion',if(a.party_type='P','P-Personal', if(a.party_type='S','S-Sub-Agent',if(a.party_type='O','O-Others','-'))))) as party_type, if(a.party_type='A',(select concat(agent_code,'-',agent_name) from agent_info where agent_code=a.party_code),(select concat(champion_code,'-',champion_name) from champion_info where champion_code=a.party_code)) as party_code,ifnull(a.available_balance,'-') as available_balance, ifnull(a.current_balance,'-') as current_balance, ifnull(a.credit_limit,'-') as credit_limit, a.date_time, ifnull(a.daily_limit,'-') as daily_limit, ifnull(a.advance_amount,'-') as advance_amount,  ifnull(a.minimum_balance,'-') as minimum_balance, ifnull(a.previous_current_balance,'-') as previous_current_balance, ifnull(a.uncleared_balance,'-') as uncleared_balance, ifnull(a.last_tx_no,'-') as last_tx_no, ifnull(a.last_tx_amount,'-') as last_tx_amount, ifnull(a.last_tx_date,'-') as last_tx_date, ifnull(a.active,'-') as active, ifnull(a.block_status,'-') as block_status, ifnull(a.block_date,'-') as block_date, ifnull(a.block_reason_id,'-') as block_reason_id FROM wallet_balance_history a left join agent_info b on a.party_code = b.agent_code ,champion_info c where  party_type='$partyType' and date(date_time) between '$startDate' and '$endDate' group by a.wallet_type, a.party_type, a.party_code, a.balance_history_id, a.credit_limit, a.daily_limit, a.advance_amount,a.available_balance,a.current_balance, a.minimum_balance, a.previous_current_balance, a.uncleared_balance, a.last_tx_no, a.last_tx_amount, a.last_tx_date, a.active, a.block_status, a.block_date, a.block_reason_id order by date_time";
				}
			}  
			else{
				if($partyType == "ALL"){
					$query = "select a.balance_history_id, a.date_time,if(a.wallet_type='C','C - Commission Wallet', if(a.wallet_type='M','M - Main Wallet', if(a.wallet_type='O','O-Other Wallet','-'))) as wallet_type,  if(a.party_type='A','A-Agent',if(a.party_type='C','C-Champion',if(a.party_type='P','P-Personal', if(a.party_type='S','S-Sub-Agent',if(a.party_type='O','O-Others','-'))))) as party_type, if(a.party_type='A',(select concat(agent_code,'-',agent_name) from agent_info where agent_code=a.party_code),(select concat(champion_code,'-',champion_name) from champion_info where champion_code=a.party_code)) as party_code,ifnull(a.available_balance,'-') as available_balance, ifnull(a.current_balance,'-') as current_balance, ifnull(a.credit_limit,'-') as credit_limit, ifnull(a.daily_limit,'-') as daily_limit, ifnull(a.advance_amount,'-') as advance_amount,  ifnull(a.minimum_balance,'-') as minimum_balance, ifnull(a.previous_current_balance,'-') as previous_current_balance, ifnull(a.uncleared_balance,'-') as uncleared_balance, ifnull(a.last_tx_no,'-') as last_tx_no, ifnull(a.last_tx_amount,'-') as last_tx_amount, ifnull(a.last_tx_date,'-') as last_tx_date, ifnull(a.active,'-') as active, ifnull(a.block_status,'-') as block_status, ifnull(a.block_date,'-') as block_date, ifnull(a.block_reason_id,'-') as block_reason_id FROM wallet_balance_history a left join agent_info b on a.party_code = b.agent_code ,champion_info c WHERE wallet_type='$Walltype' and date(date_time) between '$startDate' and '$endDate' group by a.wallet_type, a.party_type, a.party_code, a.balance_history_id, a.credit_limit, a.daily_limit, a.advance_amount,a.available_balance,a.current_balance, a.minimum_balance, a.previous_current_balance, a.uncleared_balance, a.last_tx_no, a.last_tx_amount, a.last_tx_date, a.active, a.block_status, a.block_date, a.block_reason_id order by date_time";
				}
				else  if($partyCode !== "ALL" ){ 
					$query = "select a.balance_history_id, a.date_time,if(a.wallet_type='C','C - Commission Wallet', if(a.wallet_type='M','M - Main Wallet', if(a.wallet_type='O','O-Other Wallet','-'))) as wallet_type,  if(a.party_type='A','A-Agent',if(a.party_type='C','C-Champion',if(a.party_type='P','P-Personal', if(a.party_type='S','S-Sub-Agent',if(a.party_type='O','O-Others','-'))))) as party_type, if(a.party_type='A',(select concat(agent_code,'-',agent_name) from agent_info where agent_code=a.party_code),(select concat(champion_code,'-',champion_name) from champion_info where champion_code=a.party_code)) as party_code,ifnull(a.available_balance,'-') as available_balance, ifnull(a.current_balance,'-') as current_balance, ifnull(a.credit_limit,'-') as credit_limit, ifnull(a.daily_limit,'-') as daily_limit, ifnull(a.advance_amount,'-') as advance_amount,  ifnull(a.minimum_balance,'-') as minimum_balance, ifnull(a.previous_current_balance,'-') as previous_current_balance, ifnull(a.uncleared_balance,'-') as uncleared_balance, ifnull(a.last_tx_no,'-') as last_tx_no, ifnull(a.last_tx_amount,'-') as last_tx_amount, ifnull(a.last_tx_date,'-') as last_tx_date, ifnull(a.active,'-') as active, ifnull(a.block_status,'-') as block_status, ifnull(a.block_date,'-') as block_date, ifnull(a.block_reason_id,'-') as block_reason_id FROM wallet_balance_history a left join agent_info b on a.party_code = b.agent_code ,champion_info c  where  party_type='$partyType' and party_Code='$partyCode' and wallet_type='$Walltype' and date(date_time) between '$startDate' and '$endDate' group by a.wallet_type, a.party_type, a.party_code, a.balance_history_id, a.credit_limit, a.daily_limit, a.advance_amount, a.available_balance, a.current_balance, a.minimum_balance, a.previous_current_balance, a.uncleared_balance, a.last_tx_no, a.last_tx_amount, a.last_tx_date, a.active, a.block_status, a.block_date, a.block_reason_id order by date_time";
				}
				else{	
					$query = "select a.balance_history_id, a.date_time,if(a.wallet_type='C','C - Commission Wallet', if(a.wallet_type='M','M - Main Wallet', if(a.wallet_type='O','O-Other Wallet','-'))) as wallet_type, if(a.party_type='A','A-Agent',if(a.party_type='C','C-Champion',if(a.party_type='P','P-Personal', if(a.party_type='S','S-Sub-Agent',if(a.party_type='O','O-Others','-'))))) as party_type, if(a.party_type='A',(select concat(agent_code,'-',agent_name) from agent_info where agent_code=a.party_code),(select concat(champion_code,'-',champion_name) from champion_info where champion_code=a.party_code)) as party_code,
					ifnull(a.available_balance,'-') as available_balance, ifnull(a.current_balance,'-') as current_balance,ifnull(a.credit_limit,'-') as credit_limit, ifnull(a.daily_limit,'-') as daily_limit, ifnull(a.advance_amount,'-') as advance_amount,  ifnull(a.minimum_balance,'-') as minimum_balance, ifnull(a.previous_current_balance,'-') as previous_current_balance, ifnull(a.uncleared_balance,'-') as uncleared_balance, ifnull(a.last_tx_no,'-') as last_tx_no, ifnull(a.last_tx_amount,'-') as last_tx_amount, ifnull(a.last_tx_date,'-') as last_tx_date, ifnull(a.active,'-') as active, ifnull(a.block_status,'-') as block_status, ifnull(a.block_date,'-') as block_date, ifnull(a.block_reason_id,'-') as block_reason_id FROM wallet_balance_history a left join agent_info b on a.party_code = b.agent_code ,champion_info c where  party_type='$partyType' and wallet_type='$Walltype' and date(date_time) between '$startDate' and '$endDate' group by a.wallet_type, a.party_type, a.party_code, a.balance_history_id, a.credit_limit, a.daily_limit, a.advance_amount, a.available_balance, a.current_balance, a.minimum_balance, a.previous_current_balance, a.uncleared_balance, a.last_tx_no, a.last_tx_amount, a.last_tx_date, a.active, a.block_status, a.block_date, a.block_reason_id order by date_time";
				}
			}
			error_log("Query ==".$query);
			
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$heading = array("History Id","Date Time","Wallet Type","Party Type", "Party Code","Available Balance","Current Balance","Credit Limit","Daily Limit","Advance Amount", "Minimum Balance","Previous Current Balance","Uncleared Balance", "Last Tx No","Last Tx Amount", "Last Tx Date","Active","Block Status","Block Date","Block Reason Id");
		$headcount = 20;
		error_log("excel stat report query = ".$query);
		heading($heading,$objPHPExcel,$headcount);
		$i = 2;						
		while ($row = mysqli_fetch_array($result))	{
			generateExcel ($i, $row,$objPHPExcel,$headcount);
			$i++;				
		}
		$row = $objPHPExcel->getActiveSheet()->getHighestRow();
		$objPHPExcel->getActiveSheet()->getStyle( 'A'.($row+1) )->getFont()->setBold( true );
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.($row+1), "Row Count : ".($row -1));
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