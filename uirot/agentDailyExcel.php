 <?php
 ERROR_REPORTING(E_ALL);
$data = json_decode(file_get_contents("php://input"));
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
include("excelfunctions.php");
require_once   '../common/PHPExcel/Classes/PHPExcel/IOFactory.php';
	$agentCode	= $_POST['agentCode'];
	$MonthDate	= $_POST['MonthDate'];	
	$state	= $_POST['state'];
	$localgovernment	= $_POST['localgovernment'];	
	$MonthDate = date("Y-m-d", strtotime($MonthDate));
		
$title = "KadickMoni";
 
$msg = "Agent Ranking - Daily Report For Party Code $agentCode between $MonthDate ";
$objPHPExcel = new PHPExcel();

			
				$query = "select concat(a.party_code,' [',b.agent_name,']') as  agent_name,a.run_date,a.date_time,a.target_monthly_count,a.target_monthly_amount,a.actual_cum_daily_count,a.actual_cum_daily_amount,a.actual_iso_daily_count,a.actual_iso_daily_amount,if(a.daily_trend = 'U','U-Up',if(a.daily_trend = 'D','D-Down',if(a.daily_trend='N','N-No Change','-'))) as daily_trend,c.name as state ,d.name as localGovt  from party_rank_day a,agent_info b,state_list c,local_govt_list d  where a.party_code = b.agent_code and b.state_id = c.state_id and b.local_govt_id = d.local_govt_id and  date(run_date) = '$MonthDate'";


				if($agentCode != "ALL" && $state != "ALL" && $localgovernment != "ALL" ){
					$query .= " and a.party_code = '$agentCode' and b.state_id='$state' and b.local_govt_id = '$localgovernment'";
						
					}
					if($agentCode != "ALL" && $state != "ALL" && $localgovernment == "ALL" ){
						$query .= " and a.party_code = '$agentCode' and b.state_id='$state'";
					}
					if($agentCode != "ALL" && $state == "ALL" && $localgovernment == "ALL"){
						$query .= " and a.party_code = '$agentCode'";
					}
					if($state != "ALL" && $agentCode == "ALL"  && $localgovernment == "ALL" ){
						$query .= " and b.state_id = '$state'";
					}
					if($state != "ALL" && $localgovernment != "ALL"  && $agentCode == "ALL"){
						$query .= " and b.state_id = '$state' and  b.local_govt_id = '$localgovernment'";
					}
	
					
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		
		error_log($query);
		$heading = array("Party Code","Run Date","Date Time","Target Monthly Count","Target Monthly Amount","Cumulative Daily Count","Cumulative Daily Amount","Actual Daily Count","Actual Daily Amount","Daily Trend","State","Local Government");
		$headcount = 12;
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
