 <?php
 ERROR_REPORTING(E_ALL);
$data = json_decode(file_get_contents("php://input"));
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
include("excelfunctions.php");
require_once   '../common/PHPExcel/Classes/PHPExcel/IOFactory.php';
	$agentCode	= $_POST['agentCode'];
	$MonthDate	= $_POST['MonthDate'];	
	error_log("MonthDate ==".$MonthDate);
	$MonthDate = date("Y-m", strtotime($MonthDate));
$title = "KadickMoni";
 
$msg = "Agent Ranking Monthly Report For Party Code $agentCode between $MonthDate ";
$objPHPExcel = new PHPExcel();

			$heading = array("ID","Party Type","Party Code","Run Month","Date Time","Target Monthly Count","Target Monthly Amount","Actual ISO Daily Count","Actual ISO Daily Amount","Assigned Rank","Monthly Rank");
							$headcount = 11;
		$query = "select a.party_rank_month_id,if(a.party_type = 'A','A-Agent',if(a.party_type = 'C','C-Champion',if(a.party_type = 'S','S-Sub Agent','-'))) as party_type,concat(a.party_code,' [',b.agent_name,']') as  agent_name,a.run_month,a.date_time,a.target_monthly_count,a.target_monthly_amount,a.actual_iso_daily_count,a.actual_iso_daily_amount,a.assigned_party_category_id,a.ranked_party_category_id from party_rank_month a,agent_info b where a.party_code = b.agent_code and date(date_time) like '%$MonthDate%' and a.party_code = '$agentCode'";
	
					
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		
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
