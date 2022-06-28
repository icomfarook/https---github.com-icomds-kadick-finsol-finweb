<?php
	function generateExcel ($i,$row,$objPHPExcel,$column) { 
        $Header=array("A", "B", "C", "D", "E","F","G", "H", "I", "J", "K", "L", "M","N","O","P","Q","R","S","T","U");        
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
			$objPHPExcel->getActiveSheet()->getStyle($Header2[$j]."1")->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle($Header2[$j]."1")->applyFromArray($styleArray2);	
		}
	}	
?>