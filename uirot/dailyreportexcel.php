<?php

set_time_limit(100);

ERROR_REPORTING(E_ALL);
$data = json_decode(file_get_contents("php://input"));
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
include("excelfunctions.php");
require_once   '../common/PHPExcel/Classes/PHPExcel/IOFactory.php';

   $startDate		=  $_POST['startDate'];
  // $endDate	=  $_POST['endDate'];
   $Detail 	= $_POST['Detail'];
   
   $startDate = date("Y-m-d", strtotime($startDate));
   //$endDate = date("Y-m-d", strtotime($endDate));
$title = "";
$title = "KadickMoni";
if($startDate == null ){
   $startDate = date('Y-m-d');
}
/* if($endDate == null ){
       $endDate   =  date('Y-m-d');
}
//error_log($ba);
//error_log($endDate); */
$msg = "Daily Transaction Report For Date between $startDate and $endDate";
$objPHPExcel = new PHPExcel();

if($Detail == true){
    $heading = array("Agent Code","Agent Name","State","Local Government","Run Date","Available Balance","Current Balance","Advance Amount","Minimum Balance","Commission Withdrawn","Wallet Funding","Cash In","Cash Out","Evd","Bill Payment");
    $headcount = 15;
    $query = "select party_Code,party_name,state_name,local_govt_name,run_date,i_format(available_balance) as available_balance,i_format(current_balance) as current_balance,i_format(advance_amount) as advance_amount,i_format(minimum_balance) as minimum_balance,i_format(comm_withdraw_amount) as comm_withdraw_amount,i_format(wallet_fund_amount) as wallet_fund_amount,i_format(cashin_amount) as cashin_amount,i_format(cashout_amount) as cashout_amount,i_format(evd_amount) as evd_amount,i_format(billpay_amount) as billpay_amount from party_wallet_history where date(run_date) = '$startDate' and party_type='A'";
}else{
    $heading = array("Party Code","Party Name","State","Local Government","Run Date","Available Balance","Current Balance","Advance Amount","Minimum Balance","Commission Withdrawn","Wallet Funding","Cash In","Cash Out","Evd","Bill Payment");
    $headcount = 15;
    $query = "select party_Code,party_name,state_name,local_govt_name,run_date,i_format(available_balance) as available_balance,i_format(current_balance) as current_balance,i_format(advance_amount) as advance_amount,i_format(minimum_balance) as minimum_balance,i_format(comm_withdraw_amount) as comm_withdraw_amount,i_format(wallet_fund_amount) as wallet_fund_amount,i_format(cashin_amount) as cashin_amount,i_format(cashout_amount) as cashout_amount,i_format(evd_amount) as evd_amount,i_format(billpay_amount) as billpay_amount from party_wallet_history where date(run_date) = '$startDate'";
}

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
           $lastrow = $objPHPExcel->getActiveSheet()->getHighestRow();			
           $objPHPExcel->getActiveSheet()->getStyle('F1:F'.$lastrow)
           ->getNumberFormat()
           ->setFormatCode('0');
       }
       $objPHPExcel->getActiveSheet()
                   ->getColumnDimension('F')
                   ->setAutoSize(true);
       $row = $objPHPExcel->getActiveSheet()->getHighestRow();
       $objPHPExcel->getActiveSheet()->getStyle( 'A'.($row+1) )->getFont()->setBold( true );
       $objPHPExcel->getActiveSheet()->SetCellValue('A'.($row+1), "Row Count: ".($row -1));
     ////error_log($query);
       
   
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
