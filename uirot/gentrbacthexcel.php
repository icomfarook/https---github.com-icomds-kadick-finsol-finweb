 <?php
 ERROR_REPORTING(E_ALL);
$data = json_decode(file_get_contents("php://input"));
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
//error_log("s");
include("excelfunctions.php");
//error_log("1");
require_once   '../common/PHPExcel/Classes/PHPExcel/IOFactory.php';
//error_log("1");

$type	=  $_POST['type'];
	$serverName	=  $_POST['serverName'];	
	$startDate		=  $_POST['startDate'];
	$endDate	=  $_POST['endDate'];
	$creteria 	= $_POST['creteria'];
	$serverdetail 	= $_POST['serverdetail'];
	$typeDetail 	= $_POST['typeDetail'];
	$ba 	= $_POST['ba'];
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
	$success='success';
	$failure='failure';
$servername = SERVER_SHORT_NAME;
$title = "";
$title = "KadickMoni"; 
if($startDate == null ){
	$startDate = date('Y-m-d');
}
if($endDate == null ){
		$endDate   =  date('Y-m-d');
}
//error_log($ba);
//error_log($endDate);
$msg = "";
$objPHPExcel = new PHPExcel();
	if($type == "ALL") {
				if($typeDetail == false) {
					if($serverName == "ALL"){
						if($serverdetail == false) {
							
							if($ba == 'S') {
								$msg = "Batch Report FOR non detail and not server detail summary report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	FROM fin_trans_log_batch WHERE error_description='$success' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'F') {
									$msg = "Batch Report FOR non detail and not server detail failure report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	FROM fin_trans_log_batch WHERE error_description='$failure' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'A') {
									$msg = "Batch Report FOR non detail and not server detail ALL report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	FROM fin_trans_log_batch WHERE date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
						else {
							if($ba == 'S') {
								$msg = "Batch Report FOR non detail and server detail summary report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE error_description='$success' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'F') {
								$msg = "Batch Report FOR non detail and server detail failure report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	,if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE error_description='$failure' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'A') {
								$msg = "Batch Report FOR non detail and server detail all report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch WHERE date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
					}
					else {
						if($serverdetail == false) {
							if($ba == 'S') {
								$msg = "Batch Report FOR non detail and non server detail summary report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	 FROM fin_trans_log_batch WHERE  create_user=$serverName and error_description='$success' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'F') {
								$msg = "Batch Report FOR non detail and non server detail failure report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	 FROM fin_trans_log_batch WHERE create_user=$serverName and error_description='$failure' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'A') {
								$msg = "Batch Report FOR non detail and non server detail all report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	 FROM fin_trans_log_batch WHERE create_user=$serverName and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
						else {
							if($ba == 'S') {
								$msg = "Batch Report FOR non detail and server detail success report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE create_user=$serverName and error_description='$success' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'F') {
								$msg = "Batch Report FOR non detail and server detail failure report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	,if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE create_user=$serverName and error_description='$failure' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'A') {
								$msg = "Batch Report FOR non detail and server detail all report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch WHERE  create_user=$serverName and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
					}
				}
				else {
					if($serverName == "ALL"){
						if($serverdetail == false) {
							if($ba == 'S') {
								$msg = "Batch Report FOR  detail and server detail success report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	FROM fin_trans_log_batch WHERE  error_description='$success' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'F') {
								$msg = "Batch Report FOR  detail and server detail failure report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	FROM fin_trans_log_batch WHERE error_description='$failure' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'A') {
								$msg = "Batch Report FOR  detail and server detail all report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	FROM fin_trans_log_batch WHERE date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
						else {
							if($ba == 'S') {
								$msg = "Batch Report FOR  detail and server detail success report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE error_description='$success' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'F') {
								$msg = "Batch Report FOR  detail and server detail failure report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	,if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE error_description='$failure' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'A') {
								$msg = "Batch Report FOR  detail and server detail all report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch WHERE date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
					}
					else {
						if($serverdetail == false) {
							if($ba == 'S') {
								$msg = "Batch Report FOR  detail and server detail success report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	 FROM fin_trans_log_batch WHERE  create_user=$serverName and error_description='$success' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'F') {
								$msg = "Batch Report FOR  detail and server detail failure report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	 FROM fin_trans_log_batch WHERE create_user=$serverName and error_description='$failure' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'A') {
								$msg = "Batch Report FOR  detail and server detail all report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	 FROM fin_trans_log_batch WHERE create_user=$serverName and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
						else {
							if($ba == 'S') {
								$msg = "Batch Report FOR  detail and server detail success report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE create_user=$serverName and error_description='$success' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'F') {
								$msg = "Batch Report FOR  detail and server detail failure report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	,if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE create_user=$serverName and error_description='$failure' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'A') {
								$msg = "Batch Report FOR  detail and server detail all report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch WHERE  create_user=$serverName and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
					}
				}
			}
			else {
				if($typeDetail == false) {
					if($serverName == "ALL"){
						if($serverdetail == false) {
							if($ba == 'S') {
								$msg = "Batch Report FOR  type detail and detail and server detail success report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	FROM fin_trans_log_batch WHERE fin_service_feature_id=$type and error_description='$success' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'F') {
								$msg = "Batch Report FOR  type detail and detail and server detail failure report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	FROM fin_trans_log_batch WHERE fin_service_feature_id=$type and error_description='$failure' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'A') {
								$msg = "Batch Report FOR  type detail and detail and server detail all report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	FROM fin_trans_log_batch WHERE  fin_service_feature_id=$type and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
						else {
							if($ba == 'S') {
								$msg = "Batch Report FOR  type detail and detail and server detail success report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE fin_service_feature_id=$type and error_description='$success' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'F') {
								$msg = "Batch Report FOR  type detail and detail and server detail failure report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	,if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE fin_service_feature_id=$type and error_description='$failure' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'A') {
								$msg = "Batch Report FOR  type detail and detail and server detail all report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch WHERE fin_service_feature_id=$type and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
					}
					else {
						if($serverdetail == false) {
							if($ba == 'S') {
								$msg = "Batch Report FOR  type detail and detail and server detail success report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	 FROM fin_trans_log_batch WHERE  fin_service_feature_id=$type and create_user=$serverName and error_description='$success' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'F') {
								$msg = "Batch Report FOR  type detail and detail and server detail failure report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	 FROM fin_trans_log_batch WHERE fin_service_feature_id=$type and create_user=$serverName and error_description='$failure' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'A') {
								$msg = "Batch Report FOR  type detail and detail and server detail all report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	 FROM fin_trans_log_batch WHERE  fin_service_feature_id=$type and create_user=$serverName and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
						else {
							if($ba == 'S') {
								$msg = "Batch Report FOR  type detail and detail and server detail success report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE fin_service_feature_id=$type and create_user=$serverName and error_description='$success' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'F') {
								$msg = "Batch Report FOR  type detail and detail and server detail failure report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	,if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE fin_service_feature_id=$type and create_user=$serverName and error_description='$failure' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'A') {
								$msg = "Batch Report FOR  type detail and detail and server detail all report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch WHERE  fin_service_feature_id=$type and create_user=$serverName and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
					}
				}
				else {
					if($serverName == "ALL"){
						if($serverdetail == false) {
							if($ba == 'S') {
								$msg = "Batch Report FOR  type detail and detail and server detail success report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	FROM fin_trans_log_batch WHERE  fin_service_feature_id=$type and error_description='$success' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'F') {
								$msg = "Batch Report FOR  type detail and detail and server detail failure report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	FROM fin_trans_log_batch WHERE fin_service_feature_id=$type and error_description='$failure' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'A') {
								$msg = "Batch Report FOR  type detail and detail and server detail all report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	FROM fin_trans_log_batch WHERE fin_service_feature_id=$type and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
						else {
							if($ba == 'S') {
								$msg = "Batch Report FOR  type detail and detail and server detail success report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE fin_service_feature_id=$type and error_description='$success' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'F') {
								$msg = "Batch Report FOR  type detail and detail and server detail failure report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	,if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE fin_service_feature_id=$type and error_description='$failure' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'A') {
								$msg = "Batch Report FOR  type detail and detail and server detail all report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch WHERE fin_service_feature_id=$type and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
					}
					else {
						if($serverdetail == false) {
							if($ba == 'S') {
								$msg = "Batch Report FOR  type detail and detail and server detail success report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	 FROM fin_trans_log_batch WHERE  create_user=$serverName and error_description='$success' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'F') {
								$msg = "Batch Report FOR  type detail and detail and server detail failure report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	 FROM fin_trans_log_batch WHERE create_user=$serverName and error_description='$failure' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'A') {
								$msg = "Batch Report FOR  type detail and detail and server detail all report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	 FROM fin_trans_log_batch WHERE create_user=$serverName and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
						else {
							if($ba == 'S') {
								$msg = "Batch Report FOR  type detail and detail and server detail success report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE fin_service_feature_id=$type and create_user=$serverName and error_description='$success' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'F') {
								$msg = "Batch Report FOR  type detail and detail and server detail failure report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time	,if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE fin_service_feature_id=$type and create_user=$serverName and error_description='$failure' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'A') {
								$msg = "Batch Report FOR  type detail and detail and server detail all report for date between from $startDate and $endDate ";
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch WHERE  fin_service_feature_id=$type and create_user=$serverName and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
					}
				}
			}
			
		//error_log($query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		
		////error_log($query);
		heading($heading,$objPHPExcel,8);
		$i = 2;						
		while ($row = mysqli_fetch_array($result))	{
			generateExcel ($i, $row,$objPHPExcel,8);
			$i++;				
		}
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
