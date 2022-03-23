 <?php
 ERROR_REPORTING(E_ALL);
$data = json_decode(file_get_contents("php://input"));
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
include("excelfunctions.php");
require_once   '../common/PHPExcel/Classes/PHPExcel/IOFactory.php';
	$agentCode	= $_POST['agentCode'];
	$MonthDate	= $_POST['MonthDate'];	
	//error_log("MonthDate ==".$MonthDate);
	$MonthDate = date("Y-m", strtotime($MonthDate));
	error_log("MonthDate ==".$MonthDate);
	//$MonthDate = date("Y-m", strtotime($MonthDates));
$title = "KadickMoni";
 
$msg = "Agent Ranking Summary Report For Party Code $agentCode between $MonthDate ";
$objPHPExcel = new PHPExcel();

		if($agentCode == "ALL"){
			$query ="select a.agent_code, b.party_category_type_name as assigned_category, ifNULL((select b.party_category_type_name from party_category_type b where b.party_category_type_id = c.ranked_party_category_id),'Not-Available') as ranked_category, c.target_monthly_count, c.target_monthly_amount,c.run_month from agent_info a, party_category_type b, party_rank_month c where a.agent_code = c.party_code and a.party_category_type_id = b.party_category_type_id and date_format(c.run_month,'%Y-%m') = '$MonthDate' order by a.agent_code ;";
		}else{
			$query ="select a.agent_code, b.party_category_type_name as assigned_category, ifNULL((select b.party_category_type_name from party_category_type b where b.party_category_type_id = c.ranked_party_category_id),'Not-Available') as ranked_category, c.target_monthly_count, c.target_monthly_amount,c.run_month from agent_info a, party_category_type b, party_rank_month c where a.agent_code = c.party_code and a.party_category_type_id = b.party_category_type_id and date_format(c.run_month,'%Y-%m') = '$MonthDate'  and c.party_code='$agentCode' order by a.agent_code";
		}
	
					
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		
		error_log($query);
		$heading = array("Agent Code","Assigend Category","Ranked Category","Target Monthly Count","Target Monthly Amount","Run Month");
		$headcount = 6;
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
