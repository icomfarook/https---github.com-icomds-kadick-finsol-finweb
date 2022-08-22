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
   //$MonthDate = date("Y-m", strtotime($MonthDate));
       
$title = "KadickMoni";

$msg = "Monthly Report between $MonthDate ";
$objPHPExcel = new PHPExcel();

           
                $heading = array("Type","Month");
                $headcount = 3;
                $query1 = "select 'Cashin' as type, format(ifNULL(sum(cashin_count),0),0) as count, format(ifNULL(sum(cashin_amount),0),2) as value from party_wallet_history where year(run_date) = year(current_date()) and month(run_date) = month(current_date()) and party_sales_type = 'External Agent' and date_format(run_date,'%Y-%m') = '$MonthDate' UNION ALL select 'Cashout' as type, format(ifNULL(sum(cashout_count),0),0) as count, format(ifNULL(sum(cashout_amount),0),2) as value from party_wallet_history where year(run_date) = year(current_date()) and month(run_date) = month(current_date()) and party_sales_type = 'External Agent' and date_format(run_date,'%Y-%m') = '$MonthDate'  UNION ALL  select 'Billpayemnt' as type, format(ifNULL(sum(billpay_count),0),0) as count, format(ifNULL(sum(billpay_amount),0),2) as value from party_wallet_history where year(run_date) = year(current_date()) and month(run_date) = month(current_date()) and party_sales_type = 'External Agent' and date_format(run_date,'%Y-%m') = '$MonthDate'  UNION ALL  select 'Airtime Recharge' as type, format(ifNULL(sum(evd_count),0),0) as count, format(ifNULL(sum(evd_amount),0),2) as value from party_wallet_history where year(run_date) = year(current_date()) and month(run_date) = month(current_date()) and party_sales_type = 'External Agent'  and date_format(run_date,'%Y-%m') = '$MonthDate' UNION ALL select 'Total' as type, format(sum(ifnull(cashin_count,0))+ sum(ifnull(cashout_count,0))+sum(ifnull(evd_count,0)) + sum(ifnull(billpay_count,0)),0) as count, format(sum(ifnull(cashin_amount,0)) + sum(ifnull(cashout_amount,0)) + sum(ifnull(billpay_amount,0)) + sum(ifnull(evd_count,0)),2) as value from party_wallet_history where year(run_date) = year(current_date()) and month(run_date) = month(current_date()) and date_format(run_date,'%Y-%m') = '$MonthDate'";


        
                   error_log("EXcelQuery".$query1);
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

		  $objPHPExcel->getActiveSheet()->getDefaultStyle()->applyFromArray($style);
		  $objPHPExcel->getActiveSheet()->getStyle("$Header[$j]"."$i")->applyFromArray($styleArray);		  
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
		}
	}	

       heading($heading,$objPHPExcel,$headcount);
       $i = 2;		
       				
       while ($row = mysqli_fetch_array($result))	{
           generateExcel ($i, $row,$objPHPExcel,$headcount);
           $i++;	
           			
       }
       
     /*   $row = $objPHPExcel->getActiveSheet()->getHighestRow();
       $objPHPExcel->getActiveSheet()->getStyle( 'A'.($row+1) )->getFont()->setBold( true );
       $objPHPExcel->getActiveSheet()->SetCellValue('A'.($row+1), "Row Count: ".($row -1));
         //error_log($query);
          */
       
   
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
