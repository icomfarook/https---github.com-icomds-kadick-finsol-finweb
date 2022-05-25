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
	$state	= $_POST['state'];
	$localgovernment	= $_POST['localgovernment'];	
  $MonthDate = date("Y-m", strtotime($MonthDate,'-1 month'));


$title = "KadickMoni";
 
$msg = "Agent Ranking Monthly Report For Party Code $agentCode between $MonthDate ";
$objPHPExcel = new PHPExcel();

			$query ="select concat(a.party_code,' [',b.agent_name,']') as  agent_name,a.run_month,a.date_time,format(a.target_monthly_count,0) as target_monthly_count,i_format(a.target_monthly_amount) as target_monthly_amount,format(a.actual_iso_monthly_count,0) as actual_iso_monthly_count,i_format(a.actual_iso_monthly_amount) as actual_iso_monthly_amount,(select party_category_type_name from party_category_type where party_category_type_id = a.assigned_party_category_id) as  assigned_party_category_id,(select party_category_type_name from party_category_type where party_category_type_id = a.ranked_party_category_id) as ranked_party_category_id,c.name as state ,d.name as localGovt from party_rank_month a,agent_info b,state_list c,local_govt_list d where a.party_code = b.agent_code and b.state_id = c.state_id and b.local_govt_id = d.local_govt_id and date_format(a.run_month,'%Y-%m') = '$MonthDate'";
							
					
				
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
		$heading = array("Party Code","Run Month","Date Time","Target Monthly Count","Target Monthly Amount","Actual Monthly Count","Actual Monthly Amount","Assigned Rank","Monthly Rank","State","Local Government");
		$headcount = 11;
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
