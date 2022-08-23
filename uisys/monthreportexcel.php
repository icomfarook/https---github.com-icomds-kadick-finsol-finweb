<?php
ERROR_REPORTING(E_ALL);
$data = json_decode(file_get_contents("php://input"));
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
//include("excelfunctions.php");
require_once   '../common/PHPExcel/Classes/PHPExcel/IOFactory.php';
   $agentCode	= $_POST['agentCode'];
   $MonthDate	= $_POST['MonthDate'];	
   $MonthDate = date("Y-m", strtotime($MonthDate,'-1 month'));
   $MonthName = date('M-Y', strtotime($MonthDate));
   error_log("MonthName ==".$MonthName);
       
$title = "KadickMoni";

$msg = "Monthly Report between $MonthDate ";
$objPHPExcel = new PHPExcel();

           
                $heading = array("Type",$MonthName);
                $headcount = 3;
                $query1 = "select 'Cashin' as type, format(ifNULL(sum(cashin_count),0),0) as count, format(ifNULL(sum(cashin_amount),0),2) as value from party_wallet_history where year(run_date) = year(current_date()) and month(run_date) = month(current_date()) and date_format(run_date,'%Y-%m') = '$MonthDate' UNION ALL select 'Cashout' as type, format(ifNULL(sum(cashout_count),0),0) as count, format(ifNULL(sum(cashout_amount),0),2) as value from party_wallet_history where year(run_date) = year(current_date()) and month(run_date) = month(current_date()) and date_format(run_date,'%Y-%m') = '$MonthDate' UNION ALL  select 'Billpayemnt' as type, format(ifNULL(sum(billpay_count),0),0) as count, format(ifNULL(sum(billpay_amount),0),2) as value from party_wallet_history where year(run_date) = year(current_date()) and month(run_date) = month(current_date()) and date_format(run_date,'%Y-%m') = '$MonthDate' UNION ALL select 'Airtime Recharge' as type, format(ifNULL(sum(evd_count),0),0) as count, format(ifNULL(sum(evd_amount),0),2) as value from party_wallet_history where year(run_date) = year(current_date()) and month(run_date) = month(current_date()) and date_format(run_date,'%Y-%m') = '$MonthDate' UNION ALL select 'Total' as type, format(sum(ifnull(cashin_count,0))+ sum(ifnull(cashout_count,0))+sum(ifnull(evd_count,0)) + sum(ifnull(billpay_count,0)),0) as count, format(sum(ifnull(cashin_amount,0)) + sum(ifnull(cashout_amount,0)) + sum(ifnull(billpay_amount,0)) + sum(ifnull(evd_count,0)),2) as value from party_wallet_history where year(run_date) = year(current_date()) and month(run_date) = month(current_date())  and date_format(run_date,'%Y-%m') = '$MonthDate'";
                error_log("EXcelQuery".$query1);

               
                 $query2 = "(select r.name as regions, format(sum(ifnull(h.evd_count,0)),0) as airtime_count, format(sum(ifnull(h.evd_amount,0)),2) as airtime_value from party_wallet_history h, agent_info a, state_list s, region_list r where r.region_id = s.region_id and a.state_id = s.state_id and a.agent_code = h.party_code and h.party_sales_type = 'External Agent' and r.active = 'Y'and year(h.run_date) = year(current_date()) and month(h.run_date) = month(current_date()) and date_format(h.run_date,'%Y-%m') = '$MonthDate' group by r.name order by r.name) UNION ALL (select r.name as regions, format(sum(ifnull(h.cashin_count,0)) + sum(ifnull(h.cashout_count,0)),0) as cashcount, format(sum(ifnull(h.evd_amount,0)) + sum(ifnull(cashout_amount,0)),2) as cash_value from party_wallet_history h, agent_info a, state_list s, region_list r where r.region_id = s.region_id and a.state_id = s.state_id and a.agent_code = h.party_code and h.party_sales_type = 'External Agent' and r.active = 'Y'and year(h.run_date) = year(current_date()) and month(h.run_date) = month(current_date()) and date_format(h.run_date,'%Y-%m') = '$MonthDate' group by r.name order by r.name) UNION ALL (select ANY_VALUE(r.name) as regions, format(sum(ifnull(h.billpay_count,0)),0) as billpay_count, format(sum(ifnull(h.billpay_amount,0)),2) as billpay_value from party_wallet_history h, agent_info a, state_list s, region_list r where r.region_id = s.region_id and a.state_id = s.state_id and a.agent_code = h.party_code and h.party_sales_type = 'External Agent' and r.active = 'Y'and year(h.run_date) = year(current_date()) and month(h.run_date) = month(current_date()) and date_format(h.run_date,'%Y-%m') = '$MonthDate' group by r.name order by r.name) UNION ALL (select 'Total' as regions, format(sum(ifnull(h.cashin_count,0)) + sum(ifnull(h.cashout_count,0)),0) as cashin_count, format(sum(ifnull(h.evd_amount,0)) + sum(ifnull(cashout_amount,0)),2) as cashin_value from party_wallet_history h, agent_info a, state_list s, region_list r where r.region_id = s.region_id and a.state_id = s.state_id and a.agent_code = h.party_code and h.party_sales_type = 'External Agent' and r.active = 'Y'and year(h.run_date) = year(current_date()) and month(h.run_date) = month(current_date()) and date_format(h.run_date,'%Y-%m') = '$MonthDate' group by r.name order by r.name)";
                 error_log("detail query2 = ".$query2);
                 $query3 = "select format(count(t.party_code) / (select count(*) from agent_info where party_sales_chain_id = 10)*100,2) as transact_percentage from (select party_code from party_wallet_history where year(run_date) = year(current_date()) and month(run_date) = month(current_date())   and date_format(run_date,'%Y-%m') = '$MonthAndYear' and (cashin_count > 0 or cashout_count > 0 or evd_count > 0 or billpay_count > 0) group by party_code) as t;";
                 error_log("detail query3 = ".$query3);

                 $result3 = mysqli_query($con,$query3);
                 $result2 = mysqli_query($con,$query2);
                  $result =  mysqli_query($con,$query1);
                    if (!$result) {
                        printf("Error: %s\n".mysqli_error($con));
                        //exit();
                    }
       

	function generateExcel ($i,$row,$objPHPExcel,$column) { 
        $Header=array("A", "B", "C", "D", "E","F","G", "H", "I", "J", "K", "L", "M","N","O","P","Q","R","S","T","U");       
         /*   $Header2=array("A16", "B16", "C16", "D16", "E16","F16","G16", "H16", "I16", "J16", "K16", "L16", "M16","N16","O16","P16","Q16","R16","S16","T16","U16");   */       
        $style = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );    
		$styleArray = array(
		  'borders' => array(
			'allborders' => array(
			  'style' => PHPExcel_Style_Border::BORDER_THIN
			)
		  )
		); 		
        
        for($j=0; $j<$column; $j++) {        
            $objPHPExcel->getActiveSheet()->setCellValue("$Header[$j]"."$i","$row[$j]");
            $objPHPExcel->getActiveSheet()->getColumnDimension($Header[$j])->setWidth(25);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B1:C1');
            $objPHPExcel->getActiveSheet()->setCellValue('A2', '');
            $objPHPExcel->getActiveSheet()->setCellValue('B2', 'Count');
            $objPHPExcel->getActiveSheet()->setCellValue('C2', 'Value');
            $objPHPExcel->getActiveSheet()->getDefaultStyle()->applyFromArray($style);
		    $objPHPExcel->getActiveSheet()->getStyle("$Header[$j]"."$i")->applyFromArray($styleArray);		  
        }    
    }
    function generateExcel2 ($i,$row1,$objPHPExcel,$column) { 
       
        $Header16=array("A", "B", "C", "D", "E","F","G", "H", "I", "J", "K", "L", "M","N","O","P","Q","R","S","T","U");  
         $style = array(
         'alignment' => array(
             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
             )
         );    
         $styleArray = array(
           'borders' => array(
             'allborders' => array(
               'style' => PHPExcel_Style_Border::BORDER_THIN
             )
           )
         ); 		
        
         for($j=0; $j<$column; $j++) {        
            $MonthDate	= $_POST['MonthDate'];	
            $MonthDate = date("Y-m", strtotime($MonthDate,'-1 month'));
            $MonthName = date('M-Y', strtotime($MonthDate));
           //error_log("MonthName ==".$MonthName);
            $objPHPExcel->getActiveSheet()->setCellValue("$Header16[$j]"."$i","$row1[$j]");
            $objPHPExcel->getActiveSheet()->getColumnDimension($Header16[$j])->setWidth(25);
            $objPHPExcel->getActiveSheet()->setCellValue('A16', 'Region'); 
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B16:G16');
            $objPHPExcel->getActiveSheet()->getDefaultStyle()->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->setCellValue('B2', 'Count');
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B17:C17');
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D17:E17');
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F17:G17');
            $objPHPExcel->getActiveSheet()->setCellValue('B16',$MonthName);
            $objPHPExcel->getActiveSheet()->setCellValue('B17', 'Air Time');
            $objPHPExcel->getActiveSheet()->setCellValue('D17', 'Cash Sales');
            $objPHPExcel->getActiveSheet()->setCellValue('F17', 'Bill Payment');
            $objPHPExcel->getActiveSheet()->setCellValue('C2', 'Value');
            $objPHPExcel->getActiveSheet()->setCellValue('A16', 'Region');
            $objPHPExcel->getActiveSheet()->setCellValue('B18', 'Count');
            $objPHPExcel->getActiveSheet()->setCellValue('C18', 'Value');
            $objPHPExcel->getActiveSheet()->setCellValue('D18', 'Count');
            $objPHPExcel->getActiveSheet()->setCellValue('E18', 'Value');
            $objPHPExcel->getActiveSheet()->setCellValue('F18', 'Count');
            $objPHPExcel->getActiveSheet()->setCellValue('G18', 'Value');
            $objPHPExcel->getActiveSheet()->getStyle("A2:C2")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("A18:G18")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("A16")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("B16:G16")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("B17:C17")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("D17:E17")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("F17:G17")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("$Header16[$j]"."$i")->applyFromArray($styleArray);		  

        }
        
     }

     function generateExcel3 ($i,$row1,$objPHPExcel,$column) { 
         $Header34=array("A", "B", "C", "D", "E","F","G", "H", "I", "J", "K", "L", "M","N","O","P","Q","R","S","T","U");  
         $style = array(
         'alignment' => array(
             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
             )
         );    
         $styleArray = array(
           'borders' => array(
             'allborders' => array(
               'style' => PHPExcel_Style_Border::BORDER_THIN
             )
           )
         ); 		
      
         for($j=0; $j<$column; $j++) {        
            $MonthDate	= $_POST['MonthDate'];	
            $MonthDate = date("Y-m", strtotime($MonthDate,'-1 month'));
            $MonthName = date('F Y', strtotime($MonthDate));
          // error_log("MonthName ==".$MonthName);
            $objPHPExcel->getActiveSheet()->setCellValue("$Header34[$j]"."$i","$row1[$j]");
            $objPHPExcel->getActiveSheet()->getColumnDimension($Header34[$j])->setWidth(25);
            $objPHPExcel->getActiveSheet()->setCellValue('A34', '% of Agents that transacted');   
            $objPHPExcel->getActiveSheet()->setCellValue('A35', "(Performed at least one transaction in $MonthName)"); 
            $objPHPExcel->getActiveSheet()->getStyle("A34:A35")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("B34:B35")->applyFromArray($styleArray);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B34:B35');
            $objPHPExcel->getActiveSheet()->getDefaultStyle()->applyFromArray($style);
          	  
         }
        
     }
 
	function heading($heading,$objPHPExcel,$column) {
		$styleArray = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 13
			),
			'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ));
		$styleArray2 = array(
		  'borders' => array(
			'allborders' => array(
			  'style' => PHPExcel_Style_Border::BORDER_THIN
			)
		  )
		); 	
		$Header2=array("A", "B", "C", "D", "E","F","G", "H", "I", "J", "K", "L", "M","N","O","P","Q","R","S","T","U");		
		for($j=0; $j<$column; $j++) {			
			$objPHPExcel->getActiveSheet()->setCellValue($Header2[$j]."1",$heading[$j]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B1:C1');
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A16:A17');
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B16:C16:D13:E16:F16:G16');
            $objPHPExcel->getActiveSheet()->setCellValue('A2', '');
            $objPHPExcel->getActiveSheet()->setCellValue('A14', '');
            $objPHPExcel->getActiveSheet()->setCellValue('B2', 'Count');
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B17:C17');
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D17:E17');
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F17:G17');
            $objPHPExcel->getActiveSheet()->setCellValue('B16', 'Month');
            $objPHPExcel->getActiveSheet()->setCellValue('B17', 'Air Time');
            $objPHPExcel->getActiveSheet()->setCellValue('D17', 'Cash Sales');
            $objPHPExcel->getActiveSheet()->setCellValue('F17', 'Bill Payment');
            $objPHPExcel->getActiveSheet()->setCellValue('C2', 'Value');
            $objPHPExcel->getActiveSheet()->setCellValue('A16', 'Region');
            $objPHPExcel->getActiveSheet()->getStyle($Header2[$j]."1")->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle($Header2[$j]."1")->applyFromArray($styleArray2);	
            $objPHPExcel->getActiveSheet()->getStyle("B2")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("C2")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("A16")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("B16")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("B17")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("D17")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("F17")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("B18")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("C18")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("D18")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("E18")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("F18")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("G18")->applyFromArray($styleArray);
		}
	}	

       heading($heading,$objPHPExcel,$headcount);
       $i = 3;	
       $secondTable = 19;
       $ThirdTable = 36;	
       				
       while ($row = mysqli_fetch_array($result))	{
           generateExcel ($i, $row,$objPHPExcel,$headcount);
         
           $i++;	
           			
       }
        				
       while ($row = mysqli_fetch_array($result2))	{
              generateExcel2 ($secondTable, $row,$objPHPExcel,$headcount);
        $secondTable++;
                    
    }
    while ($row = mysqli_fetch_array($result3))	{
        generateExcel3 ($ThirdTable, $row,$objPHPExcel,$headcount);
  $ThirdTable++;
              
}
       
  
       
   
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
