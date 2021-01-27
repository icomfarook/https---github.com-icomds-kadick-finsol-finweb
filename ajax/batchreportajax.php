 <?php
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	$action		=  $data->action;
	$type	=  $data->type;
	$serverName	=  $data->serverName;	
	$startDate		=  $data->startDate;
	$endDate	=  $data->endDate;
	$creteria 	= $data->creteria;
	$serverdetail 	= $data->serverdetail;	
	$typeDetail 	= $data->typeDetail;
	$ba 	= $data->ba;
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
	$success='success';
	$failure='failure';
	//error_log($action."|".$type."|".$typeDetail."|".$serverName."|".$serverdetail."|".$ba);
		if($action == "batchreport") {		
			if($type == "ALL") {
				if($typeDetail == false) {
					if($serverName == "ALL"){
						if($serverdetail == false) {
							if($ba == 'S') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time FROM fin_trans_log_batch WHERE error_code = 0 and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'F') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time FROM fin_trans_log_batch WHERE error_code != 0 and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'A') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time FROM fin_trans_log_batch WHERE date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
						else {
							if($ba == 'S') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE error_code = 0 and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'F') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time,if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE error_code != 0 and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'A') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch WHERE date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
					}
					else {
						if($serverdetail == false) {
							if($ba == 'S') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time FROM fin_trans_log_batch WHERE  create_user=$serverName and error_code = 0 and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'F') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time FROM fin_trans_log_batch WHERE create_user=$serverName and error_code != 0 and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'A') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time FROM fin_trans_log_batch WHERE create_user=$serverName and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
						else {
							if($ba == 'S') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE create_user=$serverName and error_code = 0 and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'F') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time,if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE create_user=$serverName and error_code != 0 and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'A') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch WHERE  create_user=$serverName and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
					}
				}
				else {
					if($serverName == "ALL"){
						if($serverdetail == false) {
							if($ba == 'S') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time FROM fin_trans_log_batch WHERE error_code = 0 and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'F') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time FROM fin_trans_log_batch WHERE error_code != 0 and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'A') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time FROM fin_trans_log_batch WHERE date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
						else {
							if($ba == 'S') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE error_code = 0 and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'F') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time,if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE error_code != 0 and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'A') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch WHERE date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
					}
					else {
						if($serverdetail == false) {
							if($ba == 'S') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time FROM fin_trans_log_batch WHERE  create_user=$serverName and error_code = 0 and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'F') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time FROM fin_trans_log_batch WHERE create_user=$serverName and error_code != 0 and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'A') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time FROM fin_trans_log_batch WHERE create_user=$serverName and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
						else {
							if($ba == 'S') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE create_user=$serverName and error_description='$success' and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'F') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time,if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE create_user=$serverName and error_description='$failure' and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'A') {
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
								$query ="SELECT fin_trans_log_batch_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time FROM fin_trans_log_batch WHERE fin_service_feature_id=$type and error_description='$success' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'F') {
								$query ="SELECT fin_trans_log_batch_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time FROM fin_trans_log_batch WHERE fin_service_feature_id=$type and error_description='$failure' and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'A') {
								$query ="SELECT fin_trans_log_batch_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time FROM fin_trans_log_batch WHERE  fin_service_feature_id=$type and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
						else {
							if($ba == 'S') {
								$query ="SELECT fin_trans_log_batch_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE fin_service_feature_id=$type and error_code = 0 and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'F') {
								$query ="SELECT fin_trans_log_batch_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time,if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE fin_service_feature_id=$type and error_code != 0 and date(create_time) between '$startDate' and '$endDate'  order by date(create_time)";
							}
							if($ba == 'A') {
								$query ="SELECT fin_trans_log_batch_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch WHERE fin_service_feature_id=$type and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
					}
					else {
						if($serverdetail == false) {
							if($ba == 'S') {
								$query ="SELECT fin_trans_log_batch_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time FROM fin_trans_log_batch WHERE  fin_service_feature_id=$type and create_user=$serverName and error_code = 0 and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'F') {
								$query ="SELECT fin_trans_log_batch_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time FROM fin_trans_log_batch WHERE fin_service_feature_id=$type and create_user=$serverName and error_code != 0 and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'A') {
								$query ="SELECT fin_trans_log_batch_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time FROM fin_trans_log_batch WHERE  fin_service_feature_id=$type and create_user=$serverName and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
						else {
							if($ba == 'S') {
								$query ="SELECT fin_trans_log_batch_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE fin_service_feature_id=$type and create_user=$serverName and error_code = 0 and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'F') {
								$query ="SELECT fin_trans_log_batch_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time,if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE fin_service_feature_id=$type and create_user=$serverName and error_code != 0 and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'A') {
								$query ="SELECT fin_trans_log_batch_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,fin_service_feature_id,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch WHERE  fin_service_feature_id=$type and create_user=$serverName and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
					}
				}
				else {
					if($serverName == "ALL"){
						if($serverdetail == false) {
							if($ba == 'S') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time FROM fin_trans_log_batch WHERE  fin_service_feature_id=$type and error_code = 0 and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'F') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time FROM fin_trans_log_batch WHERE fin_service_feature_id=$type and error_code != 0 and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'A') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time FROM fin_trans_log_batch WHERE fin_service_feature_id=$type and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
						else {
							if($ba == 'S') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE fin_service_feature_id=$type and error_code = 0 and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'F') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time,if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE fin_service_feature_id=$type and error_code != 0 and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'A') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch WHERE fin_service_feature_id=$type and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
					}
					else {
						if($serverdetail == false) {
							if($ba == 'S') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time FROM fin_trans_log_batch WHERE  create_user=$serverName and error_code = 0 and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'F') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time FROM fin_trans_log_batch WHERE create_user=$serverName and error_code != 0 and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'A') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time FROM fin_trans_log_batch WHERE create_user=$serverName and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
						else {
							if($ba == 'S') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE fin_service_feature_id=$type and create_user=$serverName and error_code = 0 and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'F') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time,if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch  WHERE fin_service_feature_id=$type and create_user=$serverName and error_code != 0 and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
							if($ba == 'A') {
								$query ="SELECT fin_trans_log_batch_id,fin_service_feature_id,(select feature_code from service_feature where service_feature_id=fin_service_feature_id) as type_code,if(partner_id=1,'Zenith Bank',if(partner_id=2,'NIBSS','mCash')) as partner,party_type,party_code,branch_name,branch_user_id,transaction_id,operation_id,amount,request_message,response_message,message_send_time,message_receive_time,response_received,error_code,error_description,create_user,create_time, if(create_user=82,'Portal 164',if(create_user=83,'Portal 202','')) as server FROM fin_trans_log_batch WHERE  fin_service_feature_id=$type and create_user=$serverName and date(create_time) between '$startDate' and '$endDate' order by date(create_time)";
							}
						}
					}
				}
			}
			
		error_log("batch_report_query = ".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		$rowcount=mysqli_num_rows($result);
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("server"=>$row['server'],"sd"=>$serverdetail,"td"=>$typeDetail,"code"=>$row['type_code'] ,"partner_id"=>$row['partner'],"party_code"=>$row['party_code'],"branch_name"=>$row['branch_name'],"branch_user_id"=>$row['branch_user_id'],"transaction_id"=>$row['transaction_id'],"operation_id"=>$row['operation_id'],"amount"=>$row['amount'],"request_message"=>$row['request_message'] ,"response_message"=>$row['response_message'],"message_send_time"=>$row['message_send_time'],"message_receive_time"=>$row['message_receive_time'],"response_received"=>$row['response_received'],"error_code"=>$row['error_code'],"error_description"=>$row['error_description'],"create_user"=>$row['create_user'],"create_time"=>$row['create_time'],"serverName"=>$serverName,"startDate"=>$startDate,"endDate"=>$endDate);           
		}
		echo json_encode($data);
	}

?>