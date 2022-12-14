 <?php
 ERROR_REPORTING(E_ALL);
$data = json_decode(file_get_contents("php://input"));
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
include("excelfunctions.php");
require_once   '../common/PHPExcel/Classes/PHPExcel/IOFactory.php';
	$agentCode	= $_POST['agentCode'];
	$state	= $_POST['state'];
	$localgovernment	= $_POST['localgovernment'];	
	
$title = "KadickMoni";
 
$msg = "Agent Ranking Summary Report For Party Code $agentCode between $MonthDate ";
$objPHPExcel = new PHPExcel();

			
			$query ="select a.agent_code, b.party_category_type_name as assigned_category, ifNULL((select b.party_category_type_name from party_category_type b where b.party_category_type_id = c.ranked_party_category_id),'Not-Available') as ranked_category, c.target_monthly_amount ,c.target_monthly_count ,c.run_month, d.run_date, d.actual_cum_daily_amount, d.actual_cum_daily_count as Cumulative,d.actual_iso_daily_amount,d.actual_iso_daily_count as IsoAmount, if(d.daily_trend = 'U','U-UP',if(d.daily_trend = 'D','D-Down',if(d.daily_trend = 'N','N-No-Change','-'))) as DailyTrend,e.name as State,f.name as LocalGovernment from agent_info a, party_category_type b, party_rank_month c,party_rank_day d,state_list e,local_govt_list f where a.agent_code = c.party_code and a.party_category_type_id = b.party_category_type_id  and a.state_id = e.state_id and a.local_govt_id = f.local_govt_id and c.run_month between DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 2 MONTH)), INTERVAL 1 DAY) and LAST_DAY(DATE_SUB(NOW(), INTERVAL 1 MONTH))";
	  
			if($agentCode != "ALL" && $state != "ALL" && $localgovernment != "ALL" ){
				$query .= " and  c.party_code='$agentCode' and a.state_id='$state' and a.local_govt_id = '$localgovernment'   order by d.run_date desc,a.agent_code  limit 1";
				
			}
			if($agentCode != "ALL" && $state != "ALL" && $localgovernment == "ALL" ){
				$query .= " and  c.party_code='$agentCode' and a.state_id='$state'    order by d.run_date desc,a.agent_code  limit 1";
			}
			if($agentCode != "ALL" && $state == "ALL" && $localgovernment == "ALL"){
				$query .= " and  c.party_code='$agentCode'   order by d.run_date desc,a.agent_code  limit 1";
			}
			if($state != "ALL" && $agentCode == "ALL" && $localgovernment == "ALL" ){
				$query .= " and a.state_id = '$state'   order by d.run_date desc,a.agent_code  limit 1";
			}
			if($state != "ALL" && $localgovernment != "ALL"  && $agentCode == "ALL"){
				$query .= " and a.state_id = '$state' and  a.local_govt_id = '$localgovernment'   order by d.run_date desc,a.agent_code  limit 1";
			}
					
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		
		error_log($query);
		$heading = array("Agent Code","Assigend Category","Ranked Category","Current Month Target Amount","Current Month Target Count","Run Month","Run Date","Cumulative Amount","Cumulative Count","IsoAmount Amount","Isolated Count","Daily Trend","State","Local Government");
		$headcount = 14;
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
