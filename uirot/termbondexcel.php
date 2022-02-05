 <?php
 ERROR_REPORTING(E_ALL);
$data = json_decode(file_get_contents("php://input"));
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
include("excelfunctions.php");
require_once   '../common/PHPExcel/Classes/PHPExcel/IOFactory.php';
	$vendor	= $_POST['vendor'];
	$terslno	= $_POST['terslno'];	
	$terid	= $_POST['terid'];	
	
		
$title = "KadickMoni";
 
$msg = "Terminal Bound Status Report";
$objPHPExcel = new PHPExcel();
			
			
			
		$heading = array("Inventory Id","Vendor","Agent Name","Terminal Id","Terminal Serial Number","Create Time","Update Time");
		$headcount = 7;
			
		if(trim($vendor) != "" && !empty($vendor) &&  trim($vendor) != null &&  trim($vendor) != "All" &&  trim($vendor) != "All") {
				$queryapd .= "  and  a.vendor_id = $vendor";
			}else{
				$queryapd .= "order by a.inventory_id ";
			}
			
			if(trim($terid) != "" && !empty($terid) &&  trim($terid) != null) {
				if((trim($vendor) != "" && !empty($vendor) &&  trim($vendor) != null &&  trim($vendor) != "All" &&  trim($vendor) != "All") || (trim($terslno) != "" && !empty($terslno) &&  trim($terslno) != null)) {
					$queryapd .= "   and a.terminal_id = '$terid'";
				}
				else {
					$queryapd .= " and a.terminal_id = '$terid'";
				}
			}
			if(trim($terslno) != "" && !empty($terslno) &&  trim($terslno) != null) {
				if((trim($vendor) != "" && !empty($vendor) &&  trim($vendor) != null &&  trim($vendor) != "All" &&  trim($vendor) != "All") || (trim($terid) != "" && !empty($terid) &&  trim($terid) != null)) {
					$queryapd .= "   and a.terminal_serial_no = '$terslno'";
				}
				else {
					$queryapd .= " and a.terminal_serial_no = '$terslno'";
				}
			}
			
			
			$query = "select a.inventory_id,concat(d.vendor_name,'-',d.terminal_model) as vendor ,CONCAT(c.agent_code,' - ',c.agent_name) as agent_name,a.terminal_id,a.terminal_serial_no,a.create_time,ifNULL(a.update_time,'-') as update_time from terminal_inventory a,user_pos b,agent_info c,terminal_vendor d where a.terminal_id = b.terminal_id and b.user_id = c.user_id and a.vendor_id = d.terminal_vendor_id  and a.status ='B'   $queryapd";
	
					
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
